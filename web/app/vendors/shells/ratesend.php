<?php

class RatesendShell extends Shell
{

    var $uses = array('Rate','RateSendLogDetail','RateTable', 'EmailLog', 'ServerConfig', 'SwitchProfile');
    var $flg_zip = '';
    function main()
    {
        App::import('Vendor', 'nmail/custom_mailer');
        $log_id = (int) $this->args[0];
        $download_method = @$this->args[1];
        $extra_params = @$this->args[2];
        $route_wizard_id = @$this->args[3];
        $detail_id = @$this->args[4];
        if ($detail_id) {
            $detailed_info = $this->RateSendLogDetail->query("SELECT * FROM rate_send_log_detail WHERE id = {$detail_id}");
            $log_id = $detailed_info[0][0]['log_id'];
        }

        $log_sql = "SELECT * FROM rate_send_log WHERE id = {$log_id}";
        $log_info = $this->Rate->query($log_sql);
        $send_type = $log_info[0][0]['send_type'];
        $is_temp = $log_info[0][0]['is_temp'];
        $rate_table_id = $log_info[0][0]['rate_table_id'];
        $format = $log_info[0][0]['format'];
        $flg_zip = $log_info[0][0]['zip'];
        $this->flg_zip = $flg_zip;
        $start_effective_date = $log_info[0][0]['start_effective_date'] ? $log_info[0][0]['start_effective_date'] : date('Y-m-d');
        $email_template_id = $log_info[0][0]['email_template_id'];
        $mail_sql = "SELECT content,email_cc,email_from,subject,headers,download_method FROM rate_email_template WHERE id = $email_template_id";
        $mail_info = $this->Rate->query($mail_sql, false);

        if (empty($mail_info))
            $headers = $log_info[0][0]['headers'];
        else
            $headers = $mail_info[0][0]['headers'];
        $download_method = $download_method?:$mail_info[0][0]['download_method'];
        // changing email info with custom ones
        if($email_template_id && $extra_params){
            $extra_params = unserialize($extra_params);
            $mail_info[0][0]['email_cc'] = $extra_params['email_cc'] ? :  $mail_info[0][0]['email_cc'];
            $mail_info[0][0]['content'] = $extra_params['content'] ? base64_decode($extra_params['content']):  $mail_info[0][0]['content'];
            $mail_info[0][0]['subject'] = $extra_params['subject'] ? base64_decode($extra_params['subject']) :  $mail_info[0][0]['subject'];
            $headers = isset($extra_params['headers']) && $extra_params['headers'] ? $extra_params['headers'] :  $headers;
        }

        // generate file anyway
        $rate_table_info = $this->RateTable->query("select jur_type from rate_table where rate_table_id = $rate_table_id");
        $default_schema = $this->RateTable->get_schema($rate_table_info[0][0]['jur_type']);

        if (is_array($headers)) {
            $headers = implode(',', $headers);
        }
        $headers_arr = explode(",", $headers);

        if($send_type){
            // to get all rates in this case
            $start_effective_date = $start_effective_date?:'NOW()';
        }

        if ($rate_table_info[0][0]['jur_type'] == 0 && (in_array('change_status',$headers_arr) || in_array('new_rate',$headers_arr)))
        {

            echo "have change status\n";
//                a-z 并且有change_status
//            $download_sql = $this->get_rate_table_sql($rate_table_id,$headers,$default_schema,$start_effective_date, $rate_table_info[0][0]['jur_type']);
//            $generatedFile = $this->Rate->create_rate_file($rate_table_id, $format, $flg_zip,$headers,$start_effective_date,$download_sql);

            // Generate file

            $sql = $this->get_rate_table_sql($rate_table_id,$headers,$default_schema,$start_effective_date);
            $newRateSql = $this->get_rate_table_sql($rate_table_id,$headers,$default_schema,$start_effective_date, true);
            $generatedFile = $this->Rate->generateFile($sql, $format, $flg_zip, $newRateSql, $log_id);
        }
        else
        {
            echo "not change status\n";
            $headers_sql = $this->get_rate_table_fields_sql($headers,$default_schema, $rate_table_info[0][0]['jur_type']);

            $options = array(
                'logId' => $log_id
            );
            $generatedFile = $this->Rate->create_rate_file($rate_table_id, $format, $flg_zip,$headers_sql,$start_effective_date, null, null, $options);
        }

        if (!$generatedFile)
        {
            $error_info = "Rate file is not generated";
            $this->Rate->query("UPDATE rate_send_log set status = 3,error ='$error_info'  WHERE id = {$log_id}");
            $this->log_email('', 1, $error_info);
            return false;
        }

        $rate_from = $mail_info[0][0]['email_from'];
        if (strcmp($rate_from,'default'))
        {
            $rate_from_id = intval($rate_from);
            $where_rate_from = " WHERE id = {$rate_from_id}";
            $email_info = $this->Rate->query('SELECT email as "from", smtp_host AS smtphost, smtp_port AS smtpport,username,loginemail, password as  "password", name as "name", secure as smtp_secure FROM mail_sender'.$where_rate_from);
        }
        else
        {
            $email_info = $this->Rate->query('SELECT fromemail as "from", smtphost, smtpport,emailusername as username,loginemail, emailpassword as  "password", emailname as "name", smtp_secure,realm,workstation FROM system_parameter');
        }

        if ($detail_id) {
            $send_specify_email = $detailed_info[0][0]['send_to'];
            $resource_ids = $detailed_info[0][0]['resource_id'];
        }else {
            $send_specify_email = $log_info[0][0]['send_specify_email'];
            $resource_ids = $log_info[0][0]['resource_ids'];
        }

        $resource_id_arr = explode(',',$resource_ids);
        if ($send_type){
//            发送给特别email
            $salt = $this->create_random_str(10);
            $email_address = array(
                $send_specify_email => array(
                    'salt' => md5($salt.time()),
                    'resource_id' => [$resource_id_arr['0']],
                ),
            );
        }else{
            $email_address = array();
            $sql = <<<SQL
select client.rate_email,client.email,resource.resource_id FROM resource INNER JOIN client ON resource.client_id = client.client_id
WHERE resource_id in ($resource_ids)
SQL;
            $mail_arr = $this->Rate->query($sql);
        }

//        $mail_arr = $this->Rate->get_client_email_by_ratetable($rate_table_id);

        $send_detail_arr = array();
        $error_info = '';

        if (isset($mail_arr)) {
            foreach ($mail_arr as $mail_item)
            {
                $send_mail = $mail_item[0]['rate_email'];
                if(empty($mail_item[0]['rate_email']))
                    $send_mail = $mail_item[0]['email'];
                if(!$send_mail)
                {
                    $send_detail_arr[] = array(
                        'resource_id' => $mail_item[0]['resource_id'],
                        'log_id'    => $log_id,
                        'status'    => false,
                        'error' =>  __('email empty',true)
                    );
                    continue;
                }
                else
                {
                    if (!array_key_exists($send_mail,$email_address))
                    {
                        $salt = $this->create_random_str(10);
                        $email_address[$send_mail] = array(
                            'salt' => md5($salt.time()),
                            'resource_id' => array($mail_item[0]['resource_id'])
                        );
                    }
                    else
                        array_push($email_address[$send_mail]['resource_id'],$mail_item[0]['resource_id']);

                    $send_detail_arr[] = array(
                        'resource_id' => $mail_item[0]['resource_id'],
                        'send_to'   => $send_mail,
                        'log_id'    => $log_id,
                        'status'    => true,
                        'salt'      => $email_address[$send_mail]['salt'],
                    );
                }

            }
        }

        //getting $send_detail_arr
        if ($send_type && !empty($email_address)){
            $specify_email = explode(";",key($email_address));

            if($send_type == 1) {
                foreach($specify_email as $email){
                    $send_detail_arr[] = array(
                        'resource_id' => $email_address[key($email_address)]['resource_id'][0],
                        'send_to'   => $email,
                        'log_id'    => $log_id,
                        'status'    => 1,
                        'salt'      => $email_address[key($email_address)]['salt'],
                    );
                }
            } else {
                foreach($specify_email as $email){
                    $send_detail_arr[] = array(
                        'send_to'   => $email,
                        'log_id'    => $log_id,
                        'status'    => 1,
                        'salt'      => $email_address[key($email_address)]['salt'],
                    );
                }
            }
        }

        if(empty($email_address))
        {
            $error_info = __('email empty',true);
            $this->Rate->query("UPDATE rate_send_log set status = 3, error = '{$error_info}' WHERE id = {$log_id}");
            $this->log_email('', 1, $error_info);
            return false;
        }

        $flg = $this->RateSendLogDetail->saveAll($send_detail_arr);


        if($flg === false)
        {
            $error_info = __(' detail log save failed ',true)."\n";
            $this->log_email('', 1, $error_info);
        }

        foreach ($send_detail_arr as $email_address_info)
        {
            $send_info = $this->c_send_mail($email_info,$email_address_info['send_to'],$generatedFile,$mail_info,$total_flg,$email_address_info,$rate_table_id, $log_info, $send_type);

            if ($send_info['code'] != 200)
            {
                $error_info .= $send_info['msg']."\n";
                $this->log_email($email_address_info['send_to'], 1, $error_info);
                $this->Rate->query("UPDATE rate_send_log_detail set status = 2,error = '{$send_info['msg']}' where send_to = '{$email_address_info['send_to']}' AND log_id = $log_id");
            }else{
                $sent_on = date('Y-m-d H:i:s');
                $this->Rate->query("UPDATE rate_send_log_detail set status=1, sent_on = '$sent_on' where send_to = '{$email_address_info['send_to']}' AND log_id = $log_id");
                $client_id = null;
                if($email_address_info['resource_id']){
                    $client = $this->RateTable->query("select client_id from resource WHERE resource_id = {$email_address_info['resource_id']}");
                    $client_id = $client[0][0]['client_id'];
                }
                $log_emails[] = [
                    'send_time' => date('Y-m-d H:i:sO'),
                    'client_id' => $client_id,
                    'email_addresses' => $email_address_info['send_to'],
                    'type' => 42,
                    'status' => 0,
                    'error' => ''
                ];

                $this->EmailLog->saveAll($log_emails);
                $total_flg = true;
            }

        }

        if($total_flg)
        {
            $effective_date = $this->Rate->get_effective_date($rate_table_id,$start_effective_date);
            $max_effective_date = date('Y-m-d',strtotime($effective_date[0][0]['max_date']));
            $min_effective_date = date('Y-m-d',strtotime($effective_date[0][0]['min_date']));
            $date_interval = $max_effective_date;
            if (strcmp($max_effective_date,$min_effective_date))
                $date_interval = $min_effective_date . " to " . $max_effective_date;
            $sendStatus = $download_method == 1 ? 2 : 5;
            $this->Rate->query("UPDATE rate_send_log set status = {$sendStatus}, file = '{$generatedFile}', error = '{$error_info}',
            effective_date = '{$date_interval}' WHERE id = {$log_id}");
            if(!empty($route_wizard_id)){
                $this->Rate->query("UPDATE routing_wizard_list set send_rate_on = CURRENT_TIMESTAMP(0) WHERE id = {$route_wizard_id}");
            }
        }
        else
            $this->Rate->query("UPDATE rate_send_log set status = 3, file = '{$generatedFile}', error = '{$error_info}' WHERE id = {$log_id}");
        if($is_temp === true)
        {
            $sql = "DELETE FROM rate_email_template WHERE id = $email_template_id";
            $this->Rate->query($sql);
        }

    }


    function c_send_mail($email_info,$send_mail,$file_path,$mail_info,$total_flg,$email_address_info,$rate_table_id, $log_info, $send_type)
    {
        Configure::write('debug', 2);
        $rate_subject = $mail_info[0][0]['subject'];
        $rate_content = $mail_info[0][0]['content'];
        if (strpos($rate_content,'{IP_info_table}') !== false && !$send_type && $email_address_info['resource_id'])
        {
            $hasIpInfo = true;
            $tbody_html = '';

            $ip_table_data = $this->Rate->get_ip_prefix($rate_table_id, $email_address_info['resource_id']);
            foreach ($ip_table_data as $item)
            {
                $tbody_html .= <<<HTML
    <tr><td>{$item[0]['alias']}</td><td>{$item[0]['ip']}</td><td>{$item[0]['port']}</td><td>{$item[0]['tech_prefix']}</td></tr>
HTML;
            }
            $table_html = '';
            if ($tbody_html)
            {
                $table_html = <<<TABLE
<table border="1" cellpadding="1" cellspacing="1" style="width:500px"><tr><td>Ingress</td><td>IP</td><td>Port</td><td>Prefix</td></tr>$tbody_html</table>
TABLE;
            }
            $rate_content = str_replace('{IP_info_table}',$table_html,$rate_content);

        } elseif(strpos($rate_content,'{IP_info_table}') !== false){

            $rate_content = str_replace('{IP_info_table}','',$rate_content);

        }

        if ((strpos($rate_content,'{rate_type}') !== false) || (strpos($rate_subject,'{rate_type}') !== false)) {
            $rate_table_info = $this->RateTable->query("select jur_type from rate_table where rate_table_id = $rate_table_id");
            $jur_type_arr = $this->Rate->jurTypeArr;
            $jur_type = $jur_type_arr[$rate_table_info[0][0]['jur_type']];
            $rate_content = str_replace('{rate_type}',$jur_type,$rate_content);
            $rate_subject = str_replace('{rate_type}',$jur_type,$rate_subject);
        }

        $company = '';
        if (!$log_info[0][0]['send_type'] && ((strpos($rate_content,'{company_name}') !== false) || (strpos($rate_subject,'{company_name}') !== false)) && $email_address_info['resource_id']) {
            $company_name = $this->RateTable->query("select client.company,resource.resource_id FROM resource INNER JOIN client ON resource.client_id = client.client_id WHERE resource_id = {$email_address_info['resource_id']}");
            $company = $company_name[0][0]['company'];
        }
        $rate_content = str_replace('{company_name}',$company,$rate_content);
        $rate_subject = str_replace('{company_name}',$company,$rate_subject);

        if ((strpos($rate_content,'{download_deadline}') !== false) || (strpos($rate_subject,'{download_deadline}') !== false)) {
            $rate_content = str_replace('{download_deadline}',$log_info[0][0]['download_deadline'],$rate_content);
            $rate_subject = str_replace('{download_deadline}',$log_info[0][0]['download_deadline'],$rate_subject);
        }

        if ((strpos($rate_content,'{rate_filename}') !== false) || (strpos($rate_subject,'{rate_filename}') !== false)) {
            $rate_content = str_replace('{rate_filename}',basename($file_path), $rate_content);
        }

        if ((strpos($rate_content,'{effective_date}') !== false) || (strpos($rate_subject,'{effective_date}') !== false)) {
            $rate_content = str_replace('{effective_date}',$log_info[0][0]['start_effective_date'],$rate_content);
            $rate_subject = str_replace('{effective_date}',$log_info[0][0]['start_effective_date'],$rate_subject);
        }

        $company_name = '';
        if (((strpos($rate_content,'{trunk_name}') !== false) || (strpos($rate_subject,'{trunk_name}') !== false)) && $email_address_info['resource_id']) {
            if (!$log_info[0][0]['send_type'] == 1 && !empty($log_info[0][0]['resource_ids'])) {
                $company_name = $this->RateTable->query("select alias FROM resource WHERE resource_id = {$email_address_info['resource_id']}");
                $company_name = isset($company_name[0][0]['alias']) ? $company_name[0][0]['alias'] : '';
            }
        }
        $rate_content = str_replace('{trunk_name}',$company_name, $rate_content);
        $rate_subject = str_replace('{trunk_name}',$company_name, $rate_subject);

        $limit = '';
        if (((strpos($rate_content,'{cps_limit}') !== false) || (strpos($rate_subject,'{cps_limit}') !== false)) && $email_address_info['resource_id']) {
            $company_name = $this->RateTable->query("select cps_limit FROM resource WHERE resource_id = {$email_address_info['resource_id']}");
            $limit = $company_name[0][0]['cps_limit'] ?: 'Unlimited';
        }
        $rate_content = str_replace('{cps_limit}', $limit, $rate_content);
        $rate_subject = str_replace('{cps_limit}', $limit, $rate_subject);

        $digits = '';
        if (((strpos($rate_content,'{rounding_digits}') !== false) || (strpos($rate_subject,'{rounding_digits}') !== false)) && $email_address_info['resource_id']) {
            $company_name = $this->RateTable->query("select rate_decimal FROM resource WHERE resource_id = {$email_address_info['resource_id']}");
            $digits = $company_name[0][0]['rate_decimal'];
        }
        $rate_content = str_replace('{rounding_digits}', $digits, $rate_content);

        $capacity = '';
        if (((strpos($rate_content,'{channel_limit}') !== false) || (strpos($rate_subject,'{channel_limit}') !== false)) && $email_address_info['resource_id']) {
            $company_name = $this->RateTable->query("select capacity FROM resource WHERE resource_id = {$email_address_info['resource_id']}");
            $capacity = $company_name[0][0]['capacity'] ?: 'Unlimited';
        }
        $rate_content = str_replace('{channel_limit}', $capacity, $rate_content);
        $rate_subject = str_replace('{channel_limit}', $capacity, $rate_subject);

        $company_name = '';
        if (((strpos($rate_content,'{allowed_ip}') !== false) || (strpos($rate_subject,'{allowed_ip}') !== false)) && $email_address_info['resource_id']) {
            $company_name = $this->RateTable->query("select resource_ip.ip as ip FROM resource INNER JOIN resource_ip on resource_ip.resource_id = resource.resource_id WHERE resource.resource_id = {$email_address_info['resource_id']}");
            $company_name = $company_name[0][0]['ip'];
        }
        $rate_content = str_replace('{allowed_ip}', $company_name, $rate_content);
        $rate_subject = str_replace('{allowed_ip}', $company_name, $rate_subject);

        if ((strpos($rate_content,'{prefix}') !== false) || (strpos($rate_subject,'{prefix}') !== false)) {
            $tech_prefix = $this->RateTable->query("select tech_prefix from resource_prefix where rate_table_id = $rate_table_id");
            $rate_content = str_replace('{prefix}',$tech_prefix[0][0]['tech_prefix'],$rate_content);
            $rate_subject = str_replace('{prefix}',$tech_prefix[0][0]['tech_prefix'],$rate_subject);
        }

        if ((strpos($rate_content,'{billing_type}') !== false) || (strpos($rate_subject,'{billing_type}') !== false)) {
            $tech_prefix = $this->RateTable->query("select rate_type from rate_table where rate_table_id = $rate_table_id");
            $rateTypeArr = array('DNIS', 'LRN', 'LRN Block');

            $rate_content = str_replace('{billing_type}',$rateTypeArr[$tech_prefix[0][0]['rate_type']],$rate_content);
        }

        if ((strpos($rate_content,'{az_or_us}') !== false) || (strpos($rate_subject,'{az_or_us}') !== false)) {
            $jurTypeArr = $this->Rate->jurTypeArr;
            $jur_type = $this->RateTable->query("select jur_type FROM rate_table WHERE rate_table_id = $rate_table_id");
            $rate_content = str_replace('{az_or_us}',$jurTypeArr[$jur_type[0][0]['jur_type']],$rate_content);
            $rate_subject = str_replace('{az_or_us}',$jurTypeArr[$jur_type[0][0]['jur_type']],$rate_subject);
        }

        if ((strpos($rate_content,'{switch_ip}') !== false) || (strpos($rate_subject,'{switch_ip}') !== false)) {
            $ips = $this->RateTable->query("select id, lan_ip, lan_port from voip_gateway");
            $ips_arr = array();
            foreach ($ips as $item) {
                $ip = $item[0]['lan_ip'];
                $port = $item[0]['lan_port'];
                $isActive = $this->ServerConfig->connection_test($ip,$port);

                if ($isActive) {
                    $tmpIps = $this->SwitchProfile->find('all', array(
                        'order' => array(
                            'id' => 'desc',
                        ),
                        'conditions' => array(
                            'voip_gateway_id' => $item[0]['id']
                        ),
                    ));

                    foreach ($tmpIps as $tmpIp) {
                        array_push($ips_arr, $tmpIp['SwitchProfile']['sip_ip']);
                    }
                }
            }
            $rate_content = str_replace('{switch_ip}',implode(',',$ips_arr),$rate_content);
            $rate_subject = str_replace('{switch_ip}',implode(',',$ips_arr),$rate_subject);
        }

//        $mailer = new CustomMail();
        App::import('Vendor', 'nmail/phpmailer');
        $mailer = new phpmailer(true);
        $result = array(
            'code' => 200,
            'msg' => null
        );
        try {
            if ($email_info[0][0]['loginemail'] === 'false')
            {
                $mailer->IsMail();
            }
            else
            {
                $mailer->IsSMTP();
            }
            $mailer->SMTPAuth = $email_info[0][0]['loginemail'] === 'false' ? false : true;
            switch ($email_info[0][0]['smtp_secure'])
            {
                case 1:
//                    $mailer->SMTPSecure = 'tls';
                    break;
                case 2:
                    $mailer->SMTPSecure = 'ssl';
                    break;
                case 3:
                    $mailer->AuthType = 'NTLM';
                    $mailer->Realm = $email_info[0][0]['realm'];
                    $mailer->Workstation = $email_info[0][0]['workstation'];
            }
            $mailer->SMTPDebug = 1;
            $mailer->IsHTML(true);
            $mailer->SMTPDebug = 1;
            $mailer->From = $email_info[0][0]['from'];
            $mailer->FromName = $email_info[0][0]['name'];
            $mailer->Host = $email_info[0][0]['smtphost'];
            $mailer->Port = intval($email_info[0][0]['smtpport']);
            $mailer->Username = $email_info[0][0]['username'];
            $mailer->Password = $email_info[0][0]['password'];

            if (strpos($mailer->Host, 'denovolab.com') !== false) {
                $mailer->Helo = 'demo.denovolab.com';
            }

            $toMails = explode(';',$send_mail);

            foreach ($toMails as $toMail) {
                if (trim($toMail)) {
                    $mailer->AddAddress(trim($toMail));
                }
            }

            if ($mail_info[0][0]['email_cc']) {
                $ccMails = explode(';', trim($mail_info[0][0]['email_cc']));

                foreach ($ccMails as $ccMail) {
                    if (trim($ccMail)) {
                        $mailer->AddCC(trim($ccMail));
                    }
                }
            }

            $web_url = $this->RateTable->get_server();

            if ($mail_info[0][0]['download_method'] == 2)
            {
                $email_address_salt = $email_address_info['salt'];
                $download_url = $web_url."download_rate/index?salt=".urlencode($email_address_salt);
                $download_link = "\n<a href='$download_url' style='color:red; font-weight: bold'>Download Here</a>";
                $rate_content = str_replace('{download_link}', $download_link, $rate_content);
            }
            else
                $mailer->AddAttachment($file_path);

            $mailer->SetLanguage( 'en', 'phpmailer/language/' );
            $mailer->Subject = $rate_subject;
            $mailer->Body = $rate_content;
            $sendResult = $mailer->Send();
            var_dump($sendResult);
        } catch (phpmailerException $e) {
            $result['code'] = 0;
            $result['msg'] = $e->errorMessage();
        } catch (Exception $e) {
            $result['code'] = 0;
            $result['msg'] = $e->getMessage();
        }

        return $result;
    }

    function get_rate_table_fields_sql($headers,$default_schema, $tableType)
    {
        $sql_arr = array();
        $headers_arr = explode(",",$headers);
        foreach ($headers_arr as $header)
        {
            if (!strcmp(strtolower($header),'change_status'))
                continue;
            $field_name = isset($default_schema[$headers]['name']) ?  Inflector::humanize($default_schema[$headers]['name']) :  Inflector::humanize($header);
            if(isset($default_schema[$header]['sql']))
                $sql_arr[] = $default_schema[$header]['sql'] . ' AS ' . '"' . strtolower($field_name) .'"';
            else
                $sql_arr[] = $header . ' AS ' . '"' .$field_name .'"';
        }
        return implode(",",$sql_arr);
    }

    function get_rate_table_sql($rate_table_id,$headers,$default_schema,$start_effective_date, $newSql = false)
    {
        $sql_arr = array();
        $headers_arr = explode(",", $headers);

        foreach ($headers_arr as $key => $headers_arr_item)
            $headers_arr[$key] = strtolower($headers_arr_item);

        if (!in_array('code', $headers_arr))
            $headers_arr[] = 'code';
        if (!in_array('rate', $headers_arr))
            $headers_arr[] = 'rate';

        foreach ($headers_arr as $header) {
            if (!strcmp('change_status', $header))
                continue;
            if (strcmp('new_rate', $header) && strcmp('old_rate', $header)) {
                if (isset($default_schema[$header]['sql']))
                    $sql_arr[] = $default_schema[$header]['sql'] . ' AS ' . $header;
                else
                    $sql_arr[] = $header . ' AS ' . $header;
            }
            if (!strcmp('rate', $header))
                continue;
        }
        $orderHeaders = array(
            0 => 'rate.code',
            1 => 'rate.code_name',
            2 => 'rate.country',
            3 => 'rate.rate',
            4 => 'new_rate',
            5 => 'change_status',
            6 => 'rate.effective_date',
            7 => 'rate.end_date',
            8 => 'rate.min_time',
            9 => 'rate.interval'
        );

        foreach ($headers_arr as &$item) {
            if (!in_array($item, array('new_rate', 'change_status'))) {
                $item = 'rate.' . $item;
            }
        }
        foreach ($orderHeaders as $key => $item) {
            if (!in_array($item, $headers_arr)) {
                unset($orderHeaders[$key]);
            } else {
                if ($item == 'new_rate') {
                    $orderHeaders[$key] = "NULL as new_rate";
                } else if ($item == 'change_status') {
                    $orderHeaders[$key] = "NULL as change_status";
                }
            }
        }
        $headers_arr = $orderHeaders;
        $headersList = implode(',', $headers_arr);
        $whereSql = " and (rate.end_date is null or rate.end_date >= '$start_effective_date')";

        if ($newSql) {
            $sql = <<<SQL
select rate_id,{$headersList} from rate
where rate.rate_table_id = {$rate_table_id} ORDER BY effective_date ASC
SQL;
        } else {
            $sql = <<<SQL
WITH rate AS
(
   SELECT rate_id,{$headersList},
         ROW_NUMBER() OVER (PARTITION BY code ORDER BY effective_date ASC, end_date DESC) AS rn
   FROM rate
   where rate.rate_table_id = {$rate_table_id} {$whereSql}
)
SELECT rate_id,{$headersList}
FROM rate
WHERE rn = 1
SQL;
        }

        return $sql;
    }

    public function create_random_str($num)
    {
        $num_flg = intval($num);
        $rand_str = "";
        for ($i = 0; $i < $num_flg; $i++)
        {
            $rand_str .= chr(mt_rand(33, 126));
        }
        return $rand_str;
    }

    function log_email($email_addresses, $status, $error, $client_id = null){
        $send_rate_type = 42;
        $save_arr = array(
            'send_time' => date('Y-m-d H:i:sO'),
            'client_id' => $client_id ? : null,
            'email_addresses' => $email_addresses,
            'type' => $send_rate_type,
            'status' => $status,
            'error' => $error
        );
        $this->EmailLog->save($save_arr);
    }

    function get_enviroment($str){
        return $str = preg_replace('#^https?://#', '', rtrim($str,'/'));
    }

}