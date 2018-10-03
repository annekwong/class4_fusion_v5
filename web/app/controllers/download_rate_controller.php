<?php

class DownloadRateController extends appController
{

    var $name = 'DownloadRate';
    var $components = array('RequestHandler');
    var $uses = array('RateTable','RateSendLogDetail','RateSendLog','Rate', 'Systemparam');

    function index()
    {
//        Configure::write('debug', 0);
        Configure::write('Config.language', 'eng');

        $this->layout = false;
        $salt = isset($this->params['url']['salt'])? $this->params['url']['salt'] : '';
        if (!$salt)
        {
            $this->set('error_flg',__('Illegal Request',true));
            $this->set('error_msg',__('Your request is seems to be illegal,Please make sure.',true));
            return;
        }

        $data = $this->RateSendLogDetail->find('all',array(
            'conditions' => array(
                'salt' => $salt
            ),
        ));

        $this->params['getUrl'] = $this->_request_string();
        if (isset($data[0])) {
            $log_id = $data[0]['RateSendLogDetail']['log_id'];
            $log_info = $this->RateSendLog->findById($log_id);
            $rateTableName = $this->Rate->find('first', array(
                'fields' => array('name'),
                'conditions' => array(
                    'rate_table_id' => $log_info['RateSendLog']['rate_table_id']
                )
            ));
            $rateTableName = $rateTableName['Rate']['name'];
            $downloadDeadline = $log_info['RateSendLog']['download_deadline'];
            $message = "If you accept this rate update notice, you may confirm this rate deck by clicking on \"Download\" button to download your rate update.";


            if ($log_info['RateSendLog']['download_deadline'] && $log_info['RateSendLog']['download_deadline'] < date('Y-m-d')) {
                $this->set('error_flg', __('Timeout request', true));
                $this->set('error_msg', __('download_rate_expired[%s]', true, array($log_info['RateSendLog']['download_deadline'])));
                $this->set('download_ip', $this->get_onlineip());
            } else {
                $file_name = $rateTableName . '_' . date('Y_m_d_H_i_s');

                if ($log_info['RateSendLog']['zip'])
                    $file_name .= '.zip';
                else {
                    $file_name .= $log_info['RateSendLog']['format'] == 2 ? '.xls' : '.csv';
                }

                if ($log_info['RateSendLog']['is_disable']) {
                    $message = "If you accept this rate update notice, you may confirm this rate deck by clicking on \"Download\" button to download your rate update. If this rate update is not accepted by
{$downloadDeadline} 23:59:59 GMT, your trunk will be suspended after {$downloadDeadline} 23:59:59 GMT";
                }

                $this->set('download_ip', $this->get_onlineip());
                $this->set('download_file_name', $file_name);
                $this->set('email_date', $log_info['RateSendLog']['create_time']);
                $this->set('effective_date', $log_info['RateSendLog']['effective_date']);
                $this->set('download_deadline', $downloadDeadline);
                $this->set('message', $message);
                $this->set('email_address', $data[0]['RateSendLogDetail']['send_to']);
            }
        }
        else
        {
            $this->set('error_flg',__('Mismatched request',true));
            $this->set('error_msg',__('Your request does not appear to be matched,Please make sure.',true));
        }
    }


    public function download()
    {
        configure::write('debug',0);
//        $this->autoRender = false;
//        $this->autoLayout = false;

        if (!$this->RequestHandler->isPost()) {
            return false;
        }

        $salt = isset($_POST['salt'])? $_POST['salt'] : '';
        if (!$salt)
        {
            $this->set('error_flg',__('Illegal Request',true));
            $this->set('error_msg',__('Your request is seems to be illegal,Please make sure.',true));
            return;
        }
        $data = $this->RateSendLogDetail->find('all',array(
            'conditions' => array(
                'salt' => $salt
            ),
        ));

        if (isset($data[0])) {
            $log_id = $data[0]['RateSendLogDetail']['log_id'];
            $log_info = $this->RateSendLog->findById($log_id);
            if ($log_info['RateSendLog']['download_deadline'] && $log_info['RateSendLog']['download_deadline'] < date('Y-m-d')) {
                $this->redirect('index');
            }
        }else
            $this->redirect('index');

        $file_path = $log_info["RateSendLog"]["file"];
        if (!file_exists($file_path)) {
            $this->Session->write('m', $this->RateSendLog->create_json(101, 'File not found!'));
            $this->redirect('/homes/login');
        }

        $send_type = $log_info["RateSendLog"]["send_type"];
        $rate_table_id = $log_info['RateSendLog']['rate_table_id'];
        $format = $log_info['RateSendLog']['format'];
        $flg_zip = $log_info['RateSendLog']['zip'];
        $start_effective_date = $log_info['RateSendLog']['start_effective_date'];
        if($send_type){
            $start_effective_date= 'NOW()';
        }

        $email_template_id = $log_info['RateSendLog']['email_template_id'];
        $rateTableName = $this->Rate->find('first', array(
            'fields' => array('name'),
            'conditions' => array(
                'rate_table_id' => $log_info['RateSendLog']['rate_table_id']
            )
        ));
        $rateTableName = $rateTableName['Rate']['name'];
        $file_name = $rateTableName . '_' . date('Y_m_d_H_i_s');

        if ($flg_zip)
            $file_name .= '.zip';
        else{
            $file_name .= $log_info['RateSendLog']['format'] == 2 ? '.xls' : '.csv';
        }

        $mail_sql = "SELECT content,email_cc,email_from,subject,headers FROM rate_email_template WHERE id = $email_template_id";
        $mail_info = $this->RateSendLogDetail->query($mail_sql, false);
        if (empty($mail_info))
            $headers = $log_info['RateSendLog']['headers'];
        else
            $headers = $mail_info[0][0]['headers'];
        foreach ($data as &$data_item) {
            $data_item['RateSendLogDetail']['download_date'] = date('Y-m-d');
            $data_item['RateSendLogDetail']['status'] = 3;
        }
        $this->RateSendLogDetail->saveAll($data);

        // if file does not exists
        if (!$file_path) {
            $rate_table_info = $this->RateTable->query("select jur_type from rate_table where rate_table_id = $rate_table_id");
            $default_schema = $this->RateTable->get_schema($rate_table_info[0][0]['jur_type']);
            $headers_arr = explode(",", $headers);
            if ($rate_table_info[0][0]['jur_type'] == 0 && $start_effective_date > date('Y-m-d') && in_array('change_status', $headers_arr)) {
//                a-z 并且有change_status
                $download_sql = $this->get_rate_table_sql($rate_table_id, $headers, $default_schema, $start_effective_date);
                $file_path = $this->Rate->create_rate_file($rate_table_id, $format, $flg_zip, '', $start_effective_date, $download_sql);
            } else {
                $headers_sql = $this->get_rate_table_fields_sql($headers, $default_schema);
                $file_path = $this->Rate->create_rate_file($rate_table_id, $format, $flg_zip, $headers_sql, $start_effective_date);
            }
        }
        $this->Rate->query("UPDATE rate_send_log SET status=4 WHERE id={$log_id}");
        $download_log_id = $this->insert_download_log($data[0]['RateSendLogDetail'],$file_path,$log_id);
        $_GET = array();
        Configure::load('myconf');
        $php_path = Configure::read('php_exe_path');

        $cmd = APP . "../cake/console/cake.php send_email 3 $download_log_id & /dev/null";
        $info = $this->Systemparam->find('first',array(
            'fields' => array('cmd_debug'),
        ));
        file_put_contents($info["Systemparam"]["cmd_debug"],$cmd);
        shell_exec($cmd);
//        $file_name = basename($file_path);
        header("Content-Type: application/octet-stream");
        header("Content-Disposition: attachment; filename=\"$file_name\"");
//        header("Content-Disposition: attachment; filename=download_rate.csv");
        header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
        header('Expires:0');
        header('Pragma:public');
        readfile($file_path);
        exit;
        die;
    }



    public function beforeFilter()
    {
//        $this->checkSession("login_type"); //核查用户身份
//        parent::beforeFilter();
    }

    public function insert_download_log($data_info,$file_path,$detail_id)
    {
        $this->loadModel('RateDownloadLog');
        $insert_arr = array(
            'resource_id' => $data_info['resource_id'],
            'file_path' => $file_path,
            'download_time' => date('Y-m-d H:i:s'),
            'download_ip' => $this->get_onlineip(),
            'log_detail_id' => $detail_id
        );
        $this->RateDownloadLog->save($insert_arr);

        return $this->RateDownloadLog->getLastInsertID();
    }

    function get_rate_table_fields_sql($headers,$default_schema)
    {
        $sql_arr = array();
        $headers_arr = explode(",",$headers);
        foreach ($headers_arr as $header)
        {
            $field_name = isset($default_schema[$headers]['name']) ?  Inflector::humanize($default_schema[$headers]['name']) :  Inflector::humanize($header);
            if(isset($default_schema[$header]['sql']))
                $sql_arr[] = $default_schema[$header]['sql'] . ' AS ' . '\"' .$field_name .'\"';
            else
                $sql_arr[] = $header . ' AS ' . '\"' .$field_name .'\"';
        }
        return implode(",",$sql_arr);
    }

    function get_onlineip() {
        $onlineip = '';
        if(getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
            $onlineip = getenv('HTTP_CLIENT_IP');
        } elseif(getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
            $onlineip = getenv('HTTP_X_FORWARDED_FOR');
        } elseif(getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
            $onlineip = getenv('REMOTE_ADDR');
        } elseif(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
            $onlineip = $_SERVER['REMOTE_ADDR'];
        }
        return $onlineip;
    }


    function get_rate_table_sql($rate_table_id,$headers,$default_schema,$start_effective_date)
    {
        $sql_arr = array();
        $union_fields_arr = array();
        $total_fields_arr = array();
        $total_fields_arr[] = <<<EOT
(case when new_rate is null Then 'Expired Code' when now_rate is null Then 'New Code'
when now_rate > new_rate then 'Decrease' when now_rate < new_rate then 'Increase' else 'No Change' end)  as \"Change Status\"
EOT;
        $headers_arr = explode(",",$headers);
        foreach ($headers_arr as &$headers_arr_item)
            $headers_arr_item = strtolower($headers_arr_item);
        if (!in_array('code',$headers_arr))
            $headers_arr[] = 'code';
        if (!in_array('rate',$headers_arr))
            $headers_arr[] = 'rate';
        foreach ($headers_arr as $header) {
            if (!strcmp('change_status',$header))
                continue;
            $field_name = isset($default_schema[$headers]['name']) ?  Inflector::humanize($default_schema[$headers]['name']) :  Inflector::humanize($header);
            if (isset($default_schema[$header]['sql']))
                $sql_arr[] = $default_schema[$header]['sql'] . ' AS ' . $header;
            else
                $sql_arr[] = $header . ' AS ' . $header;
            $union_fields_arr[] = 't1.'.$header.' AS now_'.$header;
            $union_fields_arr[] = 't2.'.$header.' AS new_'.$header;
            $total_fields_arr[] = '(case when new_rate is null Then now_'.$header. ' else new_'.$header. ' end) AS ' . '\"' .$field_name .'\"';
        }
        $single_sql = implode(",", $sql_arr);
        $union_fields_sql = implode(",", $union_fields_arr);
        $total_fields_sql = implode(",", $total_fields_arr);
        $right_sql = <<<EOT
select $single_sql from rate where rate_table_id = $rate_table_id and effective_date <= '$start_effective_date'
and (end_date is null or end_date >= '$start_effective_date')
EOT;
        $left_sql = <<<EOT
select $single_sql from rate where rate_table_id = $rate_table_id and effective_date <= now() and
(end_date is null or end_date >= now())
EOT;
        $union_sql = <<<EOT
select $union_fields_sql from ($left_sql) as t1 left join ($right_sql) as t2 on t1.code = t2.code where t2.code is null
union all
select $union_fields_sql from ($left_sql) as t1 right join ($right_sql) as t2 on t1.code = t2.code
EOT;

        $total_sql = <<<EOT
SELECT $total_fields_sql FROM ($union_sql) AS t3
EOT;
        return $total_sql;

    }
}

?>
