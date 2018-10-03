<?php

class SendEmailShell extends Shell
{

    var $uses = array('EmailLog','IpModifyLog','RateDownloadLog','pr.VendorInvoice','prresource.Gatewaygroup','Rate','ResourceIp','ResourcePrefix');

    function main()
    {
        $type = (int) $this->args[0];
        $is_false = false;
        switch ($type){
            case 1 :
//                trunk update ip
                $this->trunk_update();
                break;
            case 2 :
//                trunk update prefix
                $this->trunk_update_prefix();
                break;
            case 3 :
//                download rate notice
                $this->download_rate_notice();
                break;
            case 4 :
//                vendor_invoice_dispute
                $this->vendor_invoice_dispute();
                break;
            case 5 :
                $this->send_interop();
                break;
            case 6 :
                $this->send_payment();
                break;
            default:$is_false = true;
        }

        if($is_false)
        {
            echo "type " . $type . " is Undefined";
            return false;
        }
    }


    function send_payment(){
        $client_payment_id = $this->args[1];
        if (!$client_payment_id)
            return false;
        $sql = "select client.company,client.name,client.client_id,p.receiving_time,p.amount,client.billing_email as email FROM client_payment as p inner join client ON p.client_id = client.client_id  where client_payment_id = $client_payment_id";
        $client_payment_info = $this->EmailLog->query($sql);
        $payment_type = $this->args[2];
        if (!$payment_type || $payment_type == 'received'){
            $mail_template_sql = "SELECT payment_received_from as mail_from,payment_received_subject as mail_subject,payment_received_content as mail_content,payment_received_cc as mail_cc FROM mail_tmplate limit 1";
            $email_log_type = 41;
        }else{
            $mail_template_sql = "SELECT payment_sent_from as mail_from,payment_sent_subject as mail_subject,payment_sent_content as mail_content,payment_sent_cc as mail_cc FROM mail_tmplate limit 1";
            $email_log_type = 40;
        }

        $mail_template_info = $this->EmailLog->query($mail_template_sql);
        $mail_from = $mail_template_info[0][0]['mail_from'];
        $mail_subject = $mail_template_info[0][0]['mail_subject'];
        $mail_content = $mail_template_info[0][0]['mail_content'];
        $mail_cc = $mail_template_info[0][0]['mail_cc'];
        $carrier_name = $client_payment_info[0][0]['name'];
        $company_name = $client_payment_info[0][0]['company'];
        $amount = $client_payment_info[0][0]['amount'];
        $receiving_time = $client_payment_info[0][0]['receiving_time'];
        $mail_subject = str_replace(array('{amount}', '{receiving_time}', '{client_name}', '{company_name}'),array(round(floatval($amount),2), $receiving_time, $carrier_name, $company_name),$mail_subject);
        $mail_content = str_replace(array('{amount}', '{receiving_time}', '{client_name}', '{company_name}'),array(round(floatval($amount),2), $receiving_time, $carrier_name, $company_name),$mail_content);


        if ($client_payment_info[0][0]['email'])
            $mail_send = $client_payment_info[0][0]['email'];
        else
        {
            $save_arr = array(
                'send_time' => date('Y-m-d H:i:sO'),
                'client_id' => $client_payment_info[0][0]['client_id'],
                'email_addresses' => '',
                'type' => $email_log_type,
                'status' => 1,
                'error' => __('email empty',true),
            );
            $this->EmailLog->save($save_arr);
            return false;
        }
        $save_arr = array(
            'send_time' => date('Y-m-d H:i:sO'),
            'client_id' => $client_payment_info[0][0]['client_id'],
            'email_addresses' => $mail_send,
            'type' => $email_log_type,
        );
        if(empty($mail_subject) || empty ($mail_content))
        {
            $error_info = empty($mail_subject) ? __('subject empty',true): __('content empty',true);
            $save_arr['error'] = $error_info;
            $save_arr['status'] = 1;
            $this->EmailLog->save($save_arr);
            return false;
        }
        $mail_info = $this->get_mail_server_info($mail_from);
        $flg = $this->send_mail($mail_info,$mail_send,$mail_subject,$mail_content,$mail_cc);
        if($flg === true){
            $save_arr['status'] = 0;
        }else{
            $save_arr['status'] = 1;
            $save_arr['error'] = $flg;
        }
        $this->EmailLog->save($save_arr);
    }

    function send_interop()
    {
        $resource_id = $this->args[1];
        if (!$resource_id)
            return false;
        $resource_info = $this->Gatewaygroup->find('first',array(
            'fields' => array(
                'Client.company','Client.email','Gatewaygroup.alias','Gatewaygroup.trunk_type2','Gatewaygroup.rate_table_id',
                'Gatewaygroup.resource_id','Gatewaygroup.ingress','Gatewaygroup.egress','Client.name','Client.client_id'
            ),
            'joins' => array(
                array(
                    'alias' => 'Client',
                    'table' => 'client',
                    'type' => 'inner',
                    'conditions' => array(
                        'Client.client_id = Gatewaygroup.client_id'
                    ),
                ),
            ),
            'conditions' => array(
                'Gatewaygroup.resource_id' => $resource_id,
            ),
        ));

        $mail_template_sql = "SELECT trunk_interop_from,trunk_interop_subject,trunk_interop_content,trunk_interop_cc FROM mail_tmplate limit 1";
        $mail_template_info = $this->Gatewaygroup->query($mail_template_sql);
        $mail_from = $mail_template_info[0][0]['trunk_interop_from'];
        $mail_subject = $mail_template_info[0][0]['trunk_interop_subject'];
        $mail_content = $mail_template_info[0][0]['trunk_interop_content'];
        $mail_cc = $mail_template_info[0][0]['trunk_interop_cc'];
        $trunk_name = $resource_info['Gatewaygroup']['alias'];
        $carrier_name = $resource_info['Client']['name'];
        $company = $resource_info['Client']['company'];
        $server_type = $resource_info['Gatewaygroup']['trunk_type2'] == 1 ? __('Origination',true) : __('termination',true);
        $route_table_body = "";
        $jur_type_arr = $this->Rate->jurTypeArr;

        if ($resource_info['Gatewaygroup']['egress'])
        {
            $rate_info = $this->Rate->find('first',array(
                'fields' => array(
                    'name','jur_type'
                ),
            ));
            if ($rate_info)
                $route_table_body = "<tr><td></td><td>{$rate_info['Rate']['name']}</td><td>{$jur_type_arr[$rate_info['Rate']['jur_type']]}</td></tr>";
            else
                $route_table_body = "<tr><td>--</td><td>--</td><td>--</td></tr>";
        }
        else
        {
            $rates_info = $this->ResourcePrefix->find('all',array(
                'fields' => array(
                    'RateTable.name','RateTable.jur_type','ResourcePrefix.tech_prefix'
                ),
                'joins' => array(
                    array(
                        'alias' => 'RateTable',
                        'table' => 'rate_table',
                        'type' => 'inner',
                        'conditions' => array(
                            'ResourcePrefix.rate_table_id = RateTable.rate_table_id',
                        ),
                    ),
                ),
                'conditions' => array(
                    'ResourcePrefix.resource_id' => $resource_id,
                    'RateTable.is_virtual is not true'
                ),
            ));
            foreach ($rates_info as $rate_info)
                $route_table_body .= "<tr style='height: 20px'><td>{$rate_info['ResourcePrefix']['tech_prefix']}</td><td>{$rate_info['RateTable']['name']}</td><td>{$jur_type_arr[$rate_info['RateTable']['jur_type']]}</td></tr>";
        }
        $prefix_header = __('Prefix',true);
        $rate_table_header = __('Rate Table',true);
        $rate_table_type_header = __('Route Type',true);
        $route_table_html = <<<HTML
<table border="1" cellpadding="1" cellspacing="1" style="width:500px"><tr><th>$prefix_header</th><th>$rate_table_header</th><th>$rate_table_type_header</th></tr>$route_table_body</table>
HTML;

        $ip_tbody_html = '';
        $ips_info = $this->ResourceIp->find('all',array(
            'fields' => array(
                'ip','port'
            ),
            'conditions' => array(
                'resource_id' => $resource_id,
                'reg_type' => 0
            ),
        ));
        foreach ($ips_info as $ip_info)
            $ip_tbody_html .= "<tr style='height: 20px'><td>{$ip_info['ResourceIp']['ip']}</td><td>{$ip_info['ResourceIp']['port']}</td></tr>";

        $ip_header = __('IP',true);
        $port_header = __('Port',true);
        $ip_table_html = <<<HTML
<table border="1" cellpadding="1" cellspacing="1" style="width:500px">
<tr><th>$ip_header</th><th>$port_header</th></tr>$ip_tbody_html</table>
HTML;

        $mail_content = str_replace(array('{company_name}', '{client_name}','{trunk_name}', '{carrier_name}', '{trunk_type}','{route_info}','{IP_listing}'),
            array($company?:$carrier_name, $carrier_name, $trunk_name, $carrier_name, $server_type,$route_table_html,$ip_table_html),$mail_content);


        if ($resource_info['Client']['email'])
            $mail_send = $resource_info['Client']['email'];
        else
        {
            $save_arr = array(
                'send_time' => date('Y-m-d H:i:sO'),
                'client_id' => $resource_info['Client']['client_id'],
                'email_addresses' => '',
                'type' => 38,
                'status' => 1,
                'error' => __('email empty',true),
            );
            $this->EmailLog->save($save_arr);
            return false;
        }
        $save_arr = array(
            'send_time' => date('Y-m-d H:i:sO'),
            'client_id' => $resource_info['Client']['client_id'],
            'email_addresses' => $mail_send,
            'type' => 38,
        );
        if(empty($mail_subject) || empty ($mail_content))
        {
            $error_info = empty($mail_subject) ? __('subject empty',true): __('content empty',true);
            $save_arr['error'] = $error_info;
            $save_arr['status'] = 1;
            $this->EmailLog->save($save_arr);
            return false;
        }
        $mail_info = $this->get_mail_server_info($mail_from);

        $flg = $this->send_mail($mail_info,$mail_send,$mail_subject,$mail_content,$mail_cc);
        if($flg === true){
            $save_arr['status'] = 0;
        }else{
            $save_arr['status'] = 1;
            $save_arr['error'] = $flg;
        }
        $this->EmailLog->save($save_arr);
    }

    function vendor_invoice_dispute()
    {
        $vendor_invoice_id = $this->args[1];
        if (!$vendor_invoice_id)
            return false;
        $vendor_invoice_info = $this->VendorInvoice->find('first',array(
            'fields' => array(
                'Client.name','Client.client_id','Client.email','Client.billing_email','VendorInvoice.system_total',
                'VendorInvoice.billing_total','VendorInvoice.billing_start','VendorInvoice.billing_end',
            ),
            'joins' => array(
                array(
                    'alias' => 'Client',
                    'table' => 'client',
                    'type' => 'inner',
                    'conditions' => array(
                        'Client.client_id = VendorInvoice.client_id'
                    ),
                ),
            ),
            'conditions' => array(
                'VendorInvoice.vendor_invoice_id' => $vendor_invoice_id,
            ),
        ));
        $mail_template_sql = "SELECT vendor_invoice_dispute_from,vendor_invoice_dispute_subject,vendor_invoice_dispute_content,vendor_invoice_dispute_cc FROM mail_tmplate limit 1";
        $mail_template_info = $this->VendorInvoice->query($mail_template_sql);
        $mail_from = $mail_template_info[0][0]['vendor_invoice_dispute_from'];
        $mail_subject = $mail_template_info[0][0]['vendor_invoice_dispute_subject'];
        $mail_content = $mail_template_info[0][0]['vendor_invoice_dispute_content'];
        $mail_cc = $mail_template_info[0][0]['vendor_invoice_dispute_cc'];
        $dispute_value = $vendor_invoice_info['VendorInvoice']['billing_total'] - $vendor_invoice_info['VendorInvoice']['system_total'];
        $carrier_name = $vendor_invoice_info['Client']['name'];
        $billing_duration = $vendor_invoice_info['VendorInvoice']['billing_start'] . "--" .$vendor_invoice_info['VendorInvoice']['billing_end'];

        $mail_content = str_replace(array('{dispute_value}', '{carrier_name}', '{billing_duration}'),array($dispute_value, $carrier_name, $billing_duration),$mail_content);


        if ($vendor_invoice_info['Client']['billing_email'])
            $mail_send = $vendor_invoice_info['Client']['billing_email'];
        elseif ($vendor_invoice_info['Client']['email'])
            $mail_send = $vendor_invoice_info['Client']['email'];
        else
        {
            $save_arr = array(
                'send_time' => date('Y-m-d H:i:sO'),
                'client_id' => $vendor_invoice_info['Client']['client_id'],
                'email_addresses' => '',
                'type' => 37,
                'status' => 1,
                'error' => __('email empty',true),
            );
            $this->EmailLog->save($save_arr);
            return false;
        }
        $save_arr = array(
            'send_time' => date('Y-m-d H:i:sO'),
            'client_id' => $vendor_invoice_info['Client']['client_id'],
            'email_addresses' => $mail_send,
            'type' => 37,
        );
        if(empty($mail_subject) || empty ($mail_content))
        {
            $error_info = empty($mail_subject) ? __('subject empty',true): __('content empty',true);
            $save_arr['error'] = $error_info;
            $save_arr['status'] = 1;
            $this->EmailLog->save($save_arr);
            return false;
        }
        $mail_info = $this->get_mail_server_info($mail_from);
        $flg = $this->send_mail($mail_info,$mail_send,$mail_subject,$mail_content,$mail_cc);
        if($flg === true){
            $save_arr['status'] = 0;
        }else{
            $save_arr['status'] = 1;
            $save_arr['error'] = $flg;
        }
        $this->EmailLog->save($save_arr);
        if ($flg === true)
        {
//            $sql = "UPDATE vendor_invoice_dispute set dispute = $dispute_value WHERE vendor_invoice_id = $vendor_invoice_id";
//            $this->EmailLog->query($sql);
        }




    }

    function download_rate_notice()
    {
        $download_log_id = $this->args[1];
        if (!$download_log_id)
            return false;
        $resources = [];
        $log_info_rows = $this->RateDownloadLog->find('all',array(
            'fields' => array(
                'Resource.resource_id','RateDownloadLog.download_time','RateDownloadLog.download_ip','Client.name','RateSendLogDetail.salt',
                'Client.rate_email','Client.email','Client.client_id','Client.company', 'Resource.alias'
            ),
            'joins' => array(
                array(
                    'alias' => 'RateSendLogDetail',
                    'table' => 'rate_send_log_detail',
                    'type' => 'left',
                    'conditions' => array(
                        'RateSendLogDetail.log_id = RateDownloadLog.log_detail_id and RateSendLogDetail.resource_id = RateDownloadLog.resource_id'
                    ),
                ),
                array(
                    'alias' => 'Resource',
                    'table' => 'resource',
                    'type' => 'left',
                    'conditions' => array(
                        'Resource.resource_id = RateSendLogDetail.resource_id'
                    ),
                ),
                array(
                    'alias' => 'Client',
                    'table' => 'client',
                    'type' => 'left',
                    'conditions' => array(
                        'Resource.client_id = Client.client_id'
                    ),
                )
            ),
            'conditions' => array(
                'RateDownloadLog.id' => $download_log_id
            ),
        ));

        foreach($log_info_rows as $key => &$info){
            if(in_array($info['Resource']['resource_id'], $resources)){
                unset($log_info_rows[$key]);
            }
            $resources[]= $info['Resource']['resource_id'];
        }

        Configure::load('myconf');
        $web_url = Configure::read('web_base.url');
        $mail_template_sql = "SELECT download_rate_notice_from,download_rate_notice_subject,download_rate_notice_content FROM mail_tmplate limit 1";
        $mail_template_info = $this->RateDownloadLog->query($mail_template_sql);
        $mail_from = $mail_template_info[0][0]['download_rate_notice_from'];
        $mail_subject = $mail_template_info[0][0]['download_rate_notice_subject'];
        $mail_content = $mail_template_info[0][0]['download_rate_notice_content'];

        foreach($log_info_rows as $log_info){
            $download_ip = $log_info['RateDownloadLog']['download_ip'];
            $download_time = $log_info['RateDownloadLog']['download_time'];
            $client_name = $log_info['Client']['name'];
            $company = $log_info['Client']['company'];
            $download_salt = urlencode($log_info['RateSendLogDetail']['salt']);
            $trunk_name = $log_info['Resource']['alias'];
            $tech_prefix = '';
            if ($log_info['Resource']['resource_id']) {
                $techPrefixFind = $this->RateDownloadLog->query("SELECT tech_prefix from resource_prefix where resource_id = {$log_info['Resource']['resource_id']} order by id desc limit 1");
                $tech_prefix = $techPrefixFind[0][0]['tech_prefix'];
            }

            $rate_download_link = $web_url.'download_rate/index?salt='.$download_salt;
            $rate_download_link = "<a href='". $rate_download_link ."'>$rate_download_link</a>";

            // mail content
//            if (strpos($mail_content,'{client_name}') === false)
//                $mail_content .= "<br />Client Name: $client_name";
//            else
            $mail_content = str_replace('{client_name}',$client_name,$mail_content);

//            if (strpos($mail_content,'{download_ip}') === false)
//                $mail_content .= "<br />Download IP: $download_ip";
//            else
            $mail_content = str_replace('{download_ip}',$download_ip,$mail_content);

//            if (strpos($mail_content,'{rate_download_link}') === false)
//                $mail_content .= "<br />Rate Download Link: $rate_download_link";
//            else
            $mail_content = str_replace('{rate_download_link}',$rate_download_link,$mail_content);

//            if (strpos($mail_content,'{download_time}') === false)
//                $mail_content .= "<br />Download Time: $download_time";
//            else
            $mail_content = str_replace('{download_time}',$download_time,$mail_content);

            $mail_content = str_replace('{trunk_name}',$trunk_name,$mail_content);
            $mail_content = str_replace('{company_name}',$company,$mail_content);
            $mail_content = str_replace('{prefix}',$tech_prefix,$mail_content);

            // mail subject
            $mail_subject = str_replace('{client_name}',$client_name,$mail_subject);
            $mail_subject = str_replace('{download_ip}',$download_ip,$mail_subject);
            $mail_subject = str_replace('{rate_download_link}',$rate_download_link,$mail_subject);
            $mail_subject = str_replace('{download_time}',$download_time,$mail_subject);
            $mail_subject = str_replace('{trunk_name}',$trunk_name,$mail_subject);
            $mail_subject = str_replace('{company_name}',$company,$mail_subject);
            $mail_subject = str_replace('{prefix}',$tech_prefix,$mail_subject);

            // if Not Applicable"
            $mail_subject = str_replace([
                '{company_name}',
                '{client_name}',
                '{download_ip}',
                '{rate_download_link}',
                '{download_time}',
                '{trunk_name}',
                '{prefix}'
            ], "Not Applicable", $mail_subject);

            if ($log_info['Client']['rate_email'])
                $mail_send = $log_info['Client']['rate_email'];
            elseif ($log_info['Client']['email'])
                $mail_send = $log_info['Client']['email'];
            else
            {
                $save_arr = array(
                    'send_time' => date('Y-m-d H:i:sO'),
                    'client_id' => $log_info['Client']['client_id'],
                    'email_addresses' => '',
                    'type' => 35,
                    'status' => 1,
                    'error' => __('email empty',true),
                );
                $this->EmailLog->save($save_arr);
                continue;
            }
            $save_arr = array(
                'send_time' => date('Y-m-d H:i:sO'),
                'client_id' => $log_info['Client']['client_id'],
                'email_addresses' => $mail_send,
                'type' => 35,
            );
            if(empty($mail_subject) || empty ($mail_content))
            {
                $error_info = empty($mail_subject) ? __('subject empty',true): __('content empty',true);
                $save_arr['error'] = $error_info;
                $save_arr['status'] = 1;
                $this->EmailLog->save($save_arr);
                continue;
            }
            $mail_info = $this->get_mail_server_info($mail_from);
            $flg = $this->send_mail($mail_info,$mail_send,$mail_subject,$mail_content);
            if($flg === true){
                $save_arr['status'] = 0;
            }else{
                $save_arr['status'] = 1;
                $save_arr['error'] = $flg;
            }
            $this->EmailLog->save($save_arr);

        }
    }

    function trunk_update()
    {
        $ip_modify_log_id = $this->args[1];
        $ip_modify_log_info = $this->IpModifyLog->find('all',array(
            'fields' => array(
                'Client.client_id','Client.login','Client.name','Client.email','Client.noc_email', 'Client.company','Resource.alias','IpModifyLog.modify','IpModifyLog.old','IpModifyLog.new'
            ),
            'conditions' => array("id in ($ip_modify_log_id)"),
            'joins' => array(
                array(
                    'table' => 'resource',
                    'alias' => 'Resource',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'Resource.resource_id = IpModifyLog.trunk_id',
                    )
                ),
                array(
                    'table' => 'client',
                    'alias' => 'Client',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'Resource.client_id = Client.client_id',
                    )
                ),
            ),
        ));

        $modify_type = $ip_modify_log_info[0]['IpModifyLog']['modify'];
        $trunk_name = $ip_modify_log_info[0]['Resource']['alias'];

        $mail_template_sql = "SELECT trunk_change_from,trunk_change_subject,trunk_change_content,trunk_change_cc FROM mail_tmplate limit 1";
        $mail_template_info = $this->IpModifyLog->query($mail_template_sql);
        $mail_from = $mail_template_info[0][0]['trunk_change_from'];
        $mail_subject = $mail_template_info[0][0]['trunk_change_subject'];
        $mail_content = $mail_template_info[0][0]['trunk_change_content'];
        $mail_cc = $mail_template_info[0][0]['trunk_change_cc'];

        //替换邮件模板
        $ip_table_data = '';
        foreach($ip_modify_log_info as $val){
            $old = $val['IpModifyLog']['old'];
            $new = $val['IpModifyLog']['new'];
            $modify_type = $val['IpModifyLog']['modify'];
            $content_info = $this->IpModifyLog->get_action_type($modify_type);
            $ip_table_data .= '<tr style="height: 20px"><td style="border:1px solid #cfcfcf">'.$trunk_name.'</td><td style="border:1px solid #cfcfcf">'.$content_info.'</td><td style="border:1px solid #cfcfcf">'.$old.'</td><td style="border:1px solid #cfcfcf">'.$new.'</td></tr>';
        }
        $company = $ip_modify_log_info[0]['Client']['company'];
        $login = $ip_modify_log_info[0]['Client']['login'];
        $client_name = $ip_modify_log_info[0]['Client']['login']?:$ip_modify_log_info[0]['Client']['name'];

        $ip_table = '<div style="width: 500px;margin: 0 auto 0;"><table border=0 cellpadding=5 cellspacing=0 style="background-color:#FAFAFA; border-collapse:collapse; border:0px solid #ccc; width:100%"><thead><tr><th style="background-color:#51A351; text-align:left;border:1px solid #cfcfcf"><span style="color:#FFFFFF">Trunk Name</span></th><th style="background-color:#51A351; text-align:left;border:1px solid #cfcfcf"><font color="#ffffff">Action</font></th><th style="background-color:#51A351; text-align:left;border:1px solid #cfcfcf"><font color="#ffffff">Old Values</font></th><th style="background-color:#51A351; text-align:left;border:1px solid #cfcfcf"><span style="color:#FFFFFF">New Values</span></th></tr></thead><tbody>'.$ip_table_data.'</tbody></table></div><div style="height: 15px;"> </div>';
        $mail_content = str_replace(array('{detail_table}', '{client_name}', '{company}','{company_name}',  '{username}', '{trunk_name}'),array($ip_table,$client_name, $company ?: 'No company',$company ?: 'No company', $login?:$client_name, $trunk_name),$mail_content);
        $mail_subject = str_replace(array('{detail_table}', '{client_name}', '{company}','{company_name}',  '{username}', '{trunk_name}'),array($ip_table,$client_name, $company ?: 'No company',$company ?: 'No company', $login?:$client_name, $trunk_name),$mail_subject);
        $mail_send = isset($ip_modify_log_info[0]['Client']['noc_email']) ? $ip_modify_log_info[0]['Client']['noc_email'] : $ip_modify_log_info[0]['Client']['email'];
        $save_arr = array(
            'send_time' => date('Y-m-d H:i:sO'),
            'client_id' => $ip_modify_log_info[0]['Client']['client_id'],
            'email_addresses' => $mail_send,
            'type' => 10,
        );

        if(empty($mail_subject) || empty ($mail_content))
        {
            $error_info = empty($mail_subject) ? __('subject empty',true): __('content empty',true);
            $save_arr['error'] = $error_info;
            $save_arr['status'] = 1;
            $this->EmailLog->save($save_arr);
            return false;
        }
        $mail_info = $this->get_mail_server_info($mail_from);
        $flg = $this->send_mail($mail_info,$mail_send,$mail_subject,$mail_content, $mail_cc);
        if($flg === true){
            $save_arr['status'] = 0;
        }else{
            $save_arr['status'] = 1;
            $save_arr['error'] = $flg;
        }
        $this->EmailLog->save($save_arr);
    }

    function trunk_update_prefix(){
        $pass = unserialize(base64_decode($this->args[1]));
        $pass_data = $pass['pass_data'];


        pr($pass_data);

        $mail_template_sql = "SELECT trunk_change_from,trunk_change_subject,trunk_change_content FROM mail_tmplate limit 1";
        $mail_template_info = $this->IpModifyLog->query($mail_template_sql);
        $mail_from = $mail_template_info[0][0]['trunk_change_from'];
        $mail_subject = $mail_template_info[0][0]['trunk_change_subject'];
        $mail_content = $mail_template_info[0][0]['trunk_change_content'];

        //替换邮件模板
        $prefix_table_data = '';
        foreach($pass_data as $val){
            $old_product_id = $val['old']['product_id'];pr($old_product_id);
            if($old_product_id === null){
                $old_product_name = "";
            } elseif($old_product_id == 0){
                $old_product_name = "(By Rate and Route Plan)";
            } else {
                $old_product_name = $this->EmailLog->query("select product_name from product_route_rate_table where id = $old_product_id ");
                $old_product_name = $old_product_name[0][0]['product_name'];
            }

            $new_product_id = $val['new']['product_id'];pr($old_product_id);
            if($new_product_id === null){
                $new_product_name = "";
            } elseif($new_product_id == 0){
                $new_product_name = "(By Rate and Route Plan)";
            } else {
                $new_product_name = $this->EmailLog->query("select product_name from product_route_rate_table where id = $new_product_id ");
                $new_product_name = $new_product_name[0][0]['product_name'];
            }




            $old_tech_prefix = $val['old']['tech_prefix'];
            $new_tech_prefix = $val['new']['tech_prefix'];
            $old_code_cps = $val['old']['code_cps'];
            $new_code_cps = $val['new']['code_cps'];
            $action_type = $val['action_type'];



            $prefix_table_data .= '<tr style="height:20px"><td style="border:1px solid #cfcfcf">'.$action_type.'</td><td style="border:1px solid #cfcfcf">'.$old_product_name.'</td><td style="border:1px solid #cfcfcf">'.$new_product_name.'</td><td style="border:1px solid #cfcfcf">'.$old_tech_prefix.'</td><td style="border:1px solid #cfcfcf">'.$new_tech_prefix.'</td><td style="border:1px solid #cfcfcf">'.$old_code_cps.'</td><td style="border:1px solid #cfcfcf">'.$new_code_cps.'</td></tr>';
        }

        $company = $pass['company'];
        $login = $pass['login'];
        $trunk_name = $pass['alias'];
        $prefix_table = '<div style="text-align:center"><div style="width:540px;margin:0 auto 0"><table border="0" cellpadding="5" cellspacing="0" style="background-color:#FAFAFA;border-collapse:collapse;border:0 solid #ccc;width:100%;white-space:nowrap;font-size:10px"><thead><tr><th rowspan="2" style="background-color:#51A351;text-align:left;border:1px solid #cfcfcf"><font color="#ffffff">Action</font></th><th colspan="2" style="background-color:#51A351;text-align:left;border:1px solid #cfcfcf"><font color="#ffffff">Product Name</font></th><th colspan="2" style="background-color:#51A351;text-align:left;border:1px solid #cfcfcf"><span style="color:#FFF">Tech Prefix</span></th><th colspan="2" style="background-color:#51A351;text-align:left;border:1px solid #cfcfcf"><span style="color:#FFF">CPS</span></th></tr><tr><th style="background-color:#51A351;text-align:left;border:1px solid #cfcfcf"><font color="#ffffff">Old Values</font></th><th style="background-color:#51A351;text-align:left;border:1px solid #cfcfcf"><font color="#ffffff">New Values</font></th><th style="background-color:#51A351;text-align:left;border:1px solid #cfcfcf"><span style="color:#FFF">Old Values</span></th><th style="background-color:#51A351;text-align:left;border:1px solid #cfcfcf"><span style="color:#FFF">New Values</span></th><th style="background-color:#51A351;text-align:left;border:1px solid #cfcfcf"><span style="color:#FFF">Old Values</span></th><th style="background-color:#51A351;text-align:left;border:1px solid #cfcfcf"><span style="color:#FFF">New Values</span></th></tr></thead><tbody>'.$prefix_table_data.'</tbody></table></div><div style="height:15px"></div></div>';

        $mail_content = str_replace(array('{detail_table}', '{company}', '{username}', '{trunk_name}'),array($prefix_table, $company, $login, $trunk_name),$mail_content);
        $mail_send = $pass['email'];
        $save_arr = array(
            'send_time' => date('Y-m-d H:i:sO'),
            'client_id' => $pass['client_id'],
            'email_addresses' => $mail_send,
            'type' => 11,
        );
        if(empty($mail_subject) || empty ($mail_content))
        {
            $error_info = empty($mail_subject) ? __('subject empty',true): __('content empty',true);
            $save_arr['error'] = $error_info;
            $save_arr['status'] = 1;
            $this->EmailLog->save($save_arr);
            return false;
        }
        $mail_info = $this->get_mail_server_info($mail_from);
        $flg = $this->send_mail($mail_info,$mail_send,$mail_subject,$mail_content);
        var_dump($mail_send);
        var_dump($flg);
        if($flg === true){
            $save_arr['status'] = 0;
        }else{
            $save_arr['status'] = 1;
            $save_arr['error'] = $flg;
        }
        $this->EmailLog->save($save_arr);
    }


    function get_mail_server_info($send_from)
    {
        if (strcmp(strtolower($send_from),'default'))
        {
            $send_from_id = intval($send_from);
            $where_rate_from = " WHERE id = {$send_from_id}";
            $email_info = $this->EmailLog->query('SELECT email as "from", smtp_host AS smtphost, smtp_port AS smtpport,username,loginemail, password as  "password", name as "name", secure as smtp_secure FROM mail_sender'.$where_rate_from);
        }
        else
            $email_info = $this->EmailLog->query('SELECT fromemail as "from", smtphost, smtpport,emailusername as username,loginemail, emailpassword as  "password", emailname as "name", smtp_secure,realm,workstation FROM system_parameter');
        return $email_info;
    }

    function send_mail($email_info,$send_mail,$subject,$content,$cc = '',$file_path = '')
    {
        App::import('Vendor', 'nmail/phpmailer');
        $mailer = new phpmailer();
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
                $mailer->SMTPSecure = 'tls';
                break;
            case 2:
                $mailer->SMTPSecure = 'ssl';
                break;
            case 3:
                $mailer->AuthType = 'NTLM';
                $mailer->Realm = isset($email_info[0][0]['realm']) ? $email_info[0][0]['realm'] : "";
                $mailer->Workstation = isset($email_info[0][0]['workstation']) ? $email_info[0][0]['workstation'] : "";
        }
        $mailer->IsHTML(true);
        $mailer->From = trim($email_info[0][0]['from']);
        $mailer->FromName = trim($email_info[0][0]['name']);
        $mailer->Host = trim($email_info[0][0]['smtphost']);
        $mailer->Port = intval($email_info[0][0]['smtpport']);
        $mailer->Username = trim($email_info[0][0]['username']);
        $mailer->Password = trim($email_info[0][0]['password']);
        $mailer->Subject = $subject;
        $mailer->Body = $content;
        $mailer->CharSet = "UTF-8";
        if($file_path)
            $mailer->AddAttachment($file_path);
        if($cc)
            $mailer->AddCC($cc);
        $send_mail = explode(';', trim($send_mail));
        foreach ($send_mail as $item) {
            $mailer->AddAddress(trim($item));
        }
        if($mailer->Send() === false)
            return $mailer->ErrorInfo;
        else
            return true;
    }

}

