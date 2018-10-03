<?php

class HomesController extends AppController
{
    const VERSION_KEY = "dnl_softswitch";

    var $name = 'Homes';
    var $uses = array('User', 'Onlineuser', 'Client', 'Reseller', 'Mailtmp', 'ApiLog');
    var $helper = array('html', 'javascript', 'RequestHandler');

    /* 	public function beforeFilter(){
      //parent::beforeFilter();//调用父类方法
      } */
    public function index()
    {
        $this->redirect('login');
    }

    public function permission_denied()
    {

    }

    public function beforeFilter()
    {
        $this->params['hasGet'] = count($this->params['url']) > 2;
        $this->params['hasPost'] = $this->RequestHandler->isPost();
        $this->params['getUrl'] = $this->_request_string();
    }

    public function no_data()
    {

    }

    public function bad_url()
    {

    }

    public function getUrl()
    {
        if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on")
        {

            $url = 'https://' . $_SERVER['SERVER_NAME'] . ':' . $_SERVER["SERVER_PORT"] . $this->webroot;
        }
        else
        {
            $url = 'http://' . $_SERVER['SERVER_NAME'] . ':' . $_SERVER["SERVER_PORT"] . $this->webroot;
        }
        return $url;
    }

    public function getInvoicePdf($encodedInvoiceId)
    {
        Configure::load('myconf');
        Configure::write("debug", 0);

        $this->loadModel('pr.Invoice');
        $invoice_path = Configure::read('invoice_download_dir');
        $invoiceId = base64_decode($encodedInvoiceId);


        $sql = "SELECT pdf_path, invoice_number, invoice_time::DATE FROM invoice WHERE invoice_id = {$invoiceId}";
        $data = $this->Invoice->query($sql);
        $pdfFile = $data[0][0]['pdf_path'];
        $invoice_number = $data[0][0]['invoice_number'];
        $invoice_date = $data[0][0]['invoice_time'];
        $filename = $invoice_number . '_' . $invoice_date . '.pdf';
        ob_start();
        if (!empty($pdfFile)) {
            $pdfUrl = $invoice_path .'/'. $pdfFile;

            $content = file_get_contents($pdfUrl);
            if ($content != false) {
                header('Content-Description: File Transfer');
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename="' . $filename . '"');
                header('Content-Transfer-Encoding: binary');
                header('Expires: 0');
                header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                header('Pragma: public');

                ob_clean();
                echo $content;
            } else {
                $invoice_path = Configure::read('invoice_url');
                $pdfUrl = $invoice_path .DS. $pdfFile;
                $content=file_get_contents($pdfUrl);

                if ($content) {
                    header('Content-Description: File Transfer');
                    header('Content-Type: application/octet-stream');
                    header('Content-Disposition: attachment; filename="' . $filename . '"');
                    header('Content-Transfer-Encoding: binary');
                    header('Expires: 0');
                    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                    header('Pragma: public');

                    ob_clean();
                    echo $content; exit;
                } else {
                    $this->Session->write('m', $this->Invoice->create_json(101, 'Unable to get PDF file from API'));
                    $this->redirect('/pr/pr_invoices');
                }
                ob_end_flush();
            }
        } else {
            $this->Session->write('m', $this->Invoice->create_json(101, 'File not found'));
            $this->redirect('login');
        }
        exit;
    }

    public function getInvoiceLogoUrl()
    {
        Configure::write('debug', 0);

        $url = $this->getUrl();
        $logoii = APP . 'webroot' . DS . 'upload' . DS . 'images' . DS . 'ilogo.png';
        $logoi = $url . 'upload' . DS . 'images' . DS . 'ilogo.png';

        if (!file_exists($logoii)) {
            $logoi = $url . 'images' . DS . 'logo.png';
        }

        header('Access-Control-Allow-Origin: *');

        echo $logoi;
        exit;

    }

    public function test()
    {
        $this->auth_ip(1024, '192.168.1.120');
    }

    public function check_ip()
    {
        $ip = "192.168.1.115";
        $user_ip = '192.168.1.101';
        $user_ip2 = '192.168.2.115';

        //explode()
        $netmask = "255.255.255.0"; //24
        $ip_int = bindec(decbin(ip2long($ip)));
        $mask_int = bindec(decbin(ip2long($netmask)));

        $user_ip_int = bindec(decbin(ip2long($user_ip)));
        $user_ip2_int = bindec(decbin(ip2long($user_ip2)));

        if ($ip_int & $mask_int == $user_ip_int & $mask_int) {
            pr(' equ');
        } else {

            pr("不相等");
        }
    }

    public function beforeFilter1()
    {
        $this->checkSession("login_type"); //核查用户身份
        $login_type = $this->Session->read('login_type');
        if ($login_type == 1) {
            $this->Session->write('executable', true);
            $this->Session->write('writable', true);
        } else {
            $limit = $this->Session->read('sst_tools_sipcapture');
            $this->Session->write('executable', $limit['executable']);
            $this->Session->write('writable', $limit['writable']);
        }
        parent::beforeFilter();
    }

    public function show_charts()
    {
        $this->beforeFilter1();
        $type = $_GET['type'];
        $group_time = $_GET['group_time'];
        $report_type = $_GET['report_type'];
        $timezone = $_GET['timezone'];
        $start_time = $_GET['start_time'];
        $end_time = $_GET['end_time'];
        $country = $_GET['country'];
        $destination = $_GET['destination'];
        $ingress_trunk = $_GET['ingress_trunk'];
        $egress_trunk = $_GET['egress_trunk'];

        $this->set('start_time', $start_time);
        $this->set('end_time', $end_time);
        $this->set('timezone', $timezone);

        $date = date("Y-m-d", strtotime("-1 days"));
        $this->set('date', $date);
        $this->set('ingress_trunks', $this->Onlineuser->getIngressResource());
        $this->set('egress_trunks', $this->Onlineuser->getEgressResource());

        $params = array();

        array_push($params, "type=" . urlencode($type));
        array_push($params, "report_type=" . urlencode($report_type));
        array_push($params, "group_time=" . urlencode($group_time));
        if (!empty($start_time))
            array_push($params, "start_time=" . urlencode($start_time));
        if (!empty($end_time))
            array_push($params, "end_time=" . urlencode($end_time));
        if (!empty($country))
            array_push($params, "country_search=" . urlencode($country));
        if (!empty($destination))
            array_push($params, "destination=" . urlencode($destination));
        if (!empty($ingress_trunk))
            array_push($params, "ingress_trunk=" . urlencode($ingress_trunk));
        if (!empty($egress_trunk))
            array_push($params, "egress_trunk=" . urlencode($egress_trunk));
        if (!empty($timezone))
            array_push($params, "timezone=" . urlencode($timezone));

        $param = implode("&", $params);
        $this->set('param', bin2hex(gzcompress($param, 9)));
    }

    public function get_charts_data()
    {
        $this->beforeFilter1();
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $type = $_GET['type'];
        $group_time = $_GET['group_time'];
        $report_type = $_GET['report_type'];
        $timezone = $_GET['timezone'];
        $start_time = $_GET['start_time'];
        $end_time = $_GET['end_time'];
        $country = $_GET['country'];
        $destination = $_GET['destination'];
        $ingress_trunk = $_GET['ingress_trunk'];
        $egress_trunk = $_GET['egress_trunk'];

        $group_time_str = "";
        switch ($group_time) {
            case 0:
                $group_time_str = "date_trunc('day', report_time)";
                break;
            case 1:
                $group_time_str = "date_trunc('hour', report_time)";
                break;
        }

        $content = "";
//        pr($report_type, $start_time, $end_time, $timezone, $country, $destination, $ingress_trunk, $egress_trunk, $group_time_str);
        switch ($type) {
            case 0:
                $content = $this->Onlineuser->get_asr_chart($report_type, $start_time, $end_time, $timezone, $country, $destination, $ingress_trunk, $egress_trunk, $group_time_str);
                break;
            case 1:
                $content = $this->Onlineuser->get_acd_chart($report_type, $start_time, $end_time, $timezone, $country, $destination, $ingress_trunk, $egress_trunk, $group_time_str);
                break;
            case 2:
                $content = $this->Onlineuser->get_total_calls_chart($report_type, $start_time, $end_time, $timezone, $country, $destination, $ingress_trunk, $egress_trunk, $group_time_str);
                break;
            case 3:
                $content = $this->Onlineuser->get_total_billable_time_chart($report_type, $start_time, $end_time, $timezone, $country, $destination, $ingress_trunk, $egress_trunk, $group_time_str);
                break;
            case 4:
                $content = $this->Onlineuser->get_total_pdd_chart($report_type, $start_time, $end_time, $timezone, $country, $destination, $ingress_trunk, $egress_trunk, $group_time_str);
                break;
            case 5:
                $content = $this->Onlineuser->get_total_cost_chart($report_type, $start_time, $end_time, $timezone, $country, $destination, $ingress_trunk, $egress_trunk, $group_time_str);
                break;
            case 6:
                $content = $this->Onlineuser->get_total_margin_chart($report_type, $start_time, $end_time, $timezone, $country, $destination, $ingress_trunk, $egress_trunk, $group_time_str);
                break;
            case 7:
                $content = $this->Onlineuser->get_total_call_attemp($report_type, $start_time, $end_time, $timezone, $country, $destination, $ingress_trunk, $egress_trunk, $group_time_str);
        }

        $find = strpos($content, 'point');
        if ($find === false) {
            $content = '';
        }

        echo $content;
    }

    public function search_charts()
    {
        $this->beforeFilter1();
        $date = date("Y-m-d", strtotime("-1 days"));
        $this->set('date', $date);
        $this->set('ingress_trunks', $this->Onlineuser->getIngressResource());
        $this->set('egress_trunks', $this->Onlineuser->getEgressResource());
    }

    public function report()
    {
        $this->beforeFilter1();
        $date = date("Y-m-d");
        $this->set('date', $date);
        $this->set('ingress_trunks', $this->Onlineuser->getIngressResource());
        $this->set('egress_trunks', $this->Onlineuser->getEgressResource());
    }

    public function show_report()
    {
        $this->beforeFilter1();
        Configure::write('debug', 0);
        //$this->autoLayout = FALSE;
        $this->autoRender = FALSE;
        $report_type = $_POST['report_type'];
        $timezone = $_POST['timezone'];
        $start_time = $_POST['start_time'];
        $end_time = $_POST['end_time'];
        $country = $_POST['country'];
        $destination = $_POST['destination'];
        $ingress_trunk = $_POST['ingress_trunk'];
        $egress_trunk = $_POST['egress_trunk'];

        $this->set('start_time', $start_time);
        $this->set('end_time', $end_time);
        $this->set('timezone', $timezone);

        $date = date("Y-m-d");
        $this->set('date', $date);
        $this->set('ingress_trunks', $this->Onlineuser->getIngressResource());
        $this->set('egress_trunks', $this->Onlineuser->getEgressResource());

        /*
          $this->set('end_time', $country);
          $this->set('destination', $destination);
          $this->set('ingress_trunk', $ingress_trunk);
          $this->set('egress_trunk', $egress_trunk);
         * 
         */
        $params = array();

        if (!empty($start_time))
            array_push($params, "start_time=" . urlencode($start_time));
        if (!empty($end_time))
            array_push($params, "end_time=" . urlencode($end_time));
        if (!empty($country))
            array_push($params, "country_search=" . urlencode($country));
        if (!empty($destination))
            array_push($params, "destination=" . urlencode($destination));
        if (!empty($ingress_trunk))
            array_push($params, "ingress_trunk=" . urlencode($ingress_trunk));
        if (!empty($egress_trunk))
            array_push($params, "egress_trunk=" . urlencode($egress_trunk));
        if (!empty($timezone))
            array_push($params, "timezone=" . urlencode($timezone));

        $param = implode("&", $params);
        $this->set('param', $param);

        switch ($report_type) {
            case 0:
                $this->render('orig_report');
                break;

            case 1:
                $this->render('term_report');
                break;

            case 2:
                $this->render('dest_report');
                break;
        }


    }

    private function getActiveDashboard($time = 1)
    {
        $activeIp = Configure::read('active_call_ip');
        $activePort = Configure::read('active_call_port');
        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        $socketFlag = socket_connect($socket, $activeIp, $activePort);
        $resultArray = array(
            'call_mins' => 0.00,
            'margin' => 0.00,
            'margin_pre' => 0.00,
            'asr' => 0.00,
            'acd' => 0.00,
            'revenue' => 0.00,
            'profitability' => 0.00,
            'non_zero_calls' => 0,
            'calls' => 0
        );
        $todayMinutes = round(abs(strtotime(date('Y-m-d H:i:s')) - strtotime(date('Y-m-d 00:00:00'))) / 60);
        $timeArray = array(
            1 => 60,
            2 => $todayMinutes,
        );

        $logString = '';

        if($socketFlag) {
            $str = "login\r\n";
            $logString .= $str;

            socket_write($socket, $str, strlen($str));
            $out = socket_read($socket, 2046);
            $logString .= $out;
            if(strcmp($out, 'Welcome') !== FALSE) {
                $timeValue = $timeArray[$time];
                $str = "get_call_statistics {$timeValue}\r\n";
                $logString .= $str;
                socket_write($socket, $str, strlen($str));

                $out = socket_read($socket, 2048);
                $logString .= $out;
                if(!empty($out)) {
                    $resultArray = explode(',', trim($out));
                    $resultArray = array(
                        'call_mins' => $resultArray[0],
                        'margin' => $resultArray[1],
                        'margin_pre' => $resultArray[2],
                        'asr' => number_format($resultArray[3], 2),
                        'acd' => number_format($resultArray[4], 2),
                        'revenue' => number_format($resultArray[5], 2),
                        'profitability' => number_format($resultArray[6], 2),
                        'non_zero_calls' => $resultArray[7],
                        'calls' => $resultArray[8]
                    );
                }

                $str = "logout\r\n";
                $logString .= $str;
                socket_write($socket, $str, strlen($str));
            } else {
                $socketFlag = false;
            }

        }

        $this->ApiLog->write("IP: {$activeIp}:{$activePort}\r\n{$logString}", 3);

        return $resultArray;

    }

    public function getServerLimit() {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->layout = false;

        if($this->RequestHandler->isPost()) {

            $sql = "select name, lan_ip, lan_port from voip_gateway WHERE paid_replace_ip = 1";
            $limit_servers = $this->Client->query($sql);
            $max_channel = 0;
            $max_cps = 0;

            foreach ($limit_servers as $limit_servers_key => $limit_server) {
                $sip_ip = $limit_server[0]['lan_ip'];
                $sip_port = $limit_server[0]['lan_port'];
                $system_limit = $this->_get_system_limit($sip_ip, $sip_port);
                $limit_servers[$limit_servers_key][0]['max_channel'] = 0;
                $limit_servers[$limit_servers_key][0]['max_cps'] = 0;

                if (empty($system_limit))
                    continue;

                $limit_servers[$limit_servers_key][0]['max_channel'] = $system_limit['max_channel_limit'];
                $limit_servers[$limit_servers_key][0]['max_cps'] = $system_limit['max_cps_limit'];
                $max_channel += $system_limit['max_channel_limit'];
                $max_cps += $system_limit['max_cps_limit'];
            }

            $result = array('max_channel' => $max_channel, 'max_cps' => $max_cps);

            return json_encode($result);
        }
    }

    public function dashboard()
    {
        $this->beforeFilter1();
        $show_filed = array('call' => 'Connected Calls', 'qos' => 'QoS');
        if (isset($_SESSION['role_menu']['Payment_Invoice']['view_cost_and_rate']) && $_SESSION['role_menu']['Payment_Invoice']['view_cost_and_rate'] == 1) {
            $show_filed['revenue_and_profitability'] = 'Revenue and Profitability';
        }
        $this->set('show_filed', $show_filed);

        $date_intervals = array(4 => 'Last 30-Mins', 5 => 'Last 15-Mins', 1 => 'Last Hour', 2 => 'Last 24-Hours', 3 => 'Last 7-Days', 6 => 'Last 15-Days', 7 => 'Last 30-Days', 8 => 'Last 60-Days',);
        $this->set('date_intervals', $date_intervals);
//        $this->set('daily_data', $this->get_daily_data());
        $sql = "select name, lan_ip, lan_port from voip_gateway";
        $limit_servers = $this->Client->query($sql);

        $this->set('limit_servers', $limit_servers);

        //清理一个月前的dashboard_time_option
//        $pre_month = date('Y-m-d H:i:s', strtotime('-1 month'));
//        $sql = "delete from dashboard_time_option where admin_point_time < '$pre_month'";
//        $this->Client->query($sql);


    }

    public function get_current_data()
    {
        $this->beforeFilter1();
        Configure::write('debug', 0);
        $this->autoLayout = false;
        $this->autoRender = false;
        $server = $_POST['server'];
        $ingress_cps = 0;
        $egress_cps = 0;
        $channel = 0;
        $call = 0;
//        $now_l_1m = date('Y-m-d H:i:00');
//        $now_l_1m = date('Y-m-d H:i:00', strtotime($now_l_1m) - 60);
////        $now_l_1m = '2015-09-21 07:03:00';
//        $sql = "select name, lan_ip, lan_port from voip_gateway";
//        $limit_servers = $this->Client->query($sql);
//        foreach ($limit_servers as $limit_servers_key => $limit_server) {
////            $item_select_sql = "SELECT cps,channel,call,create_time FROM current_dashboard_data WHERE server= '{$system_limit_server}' ORDER BY create_time desc DESC LIMIT 1";
////            $item_data = $this->User->query($item_select_sql,false);
//            $sip_ip = $limit_server[0]['lan_ip'];
//            $sip_port = $limit_server[0]['lan_port'];
//            $system_limit_server = "$sip_ip:$sip_port";
//            $sys_ip = $limit_server[0]['lan_ip'];
//            $sys_port = $limit_server[0]['lan_port'];
//            $item_select_sql = "SELECT ingress_cps,egress_cps,ingress_channels as channel,call FROM qos_total WHERE server_ip= '{$sys_ip}' and server_port = '{$sys_port}' and to_char(report_time, 'YYYY-MM-DD HH24:MI:00') = '{$now_l_1m}' LIMIT 1";
//            $item_data = $this->User->query($item_select_sql, false);
//            if (!$item_data)
//                continue;
////            $item_create_time = $item_data[0][0]['create_time'];
////            $item_update_sql = "UPDATE current_dashboard_data set select_time = CURRENT_TIMESTAMP(0) WHERE create_time < {$item_create_time} ";
////            $this->User->query($item_update_sql);
//            if (empty($server)) {
//                $ingress_cps += $item_data[0][0]['ingress_cps'];
//                $egress_cps += $item_data[0][0]['egress_cps'];
//                $channel += $item_data[0][0]['channel'];
//                $call += $item_data[0][0]['call'];
//            } elseif (!strcmp($system_limit_server, $server)) {
//                $ingress_cps += $item_data[0][0]['ingress_cps'];
//                $egress_cps += $item_data[0][0]['egress_cps'];
//                $channel = $item_data[0][0]['channel'];
//                $call = $item_data[0][0]['call'];
//            }
//        }

        $sections = parse_ini_file(CONF_PATH, TRUE, INI_SCANNER_RAW);
        $activeIp = $sections['web_switch']['event_ip'];
        $activePort = $sections['web_switch']['event_port'];;
        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        socket_set_timeout($socket, 60);
        $socketFlag = socket_connect($socket, $activeIp, $activePort);
        $resultArray = array(
            'o_cps' => 0,
            't_cps' => 0,
            'o_chan' => 0,
            'call' => 0
        );
        $logString = '';

        if($socketFlag) {
            $str = "login\r\n";
            $logString .= $str;

            socket_write($socket, $str, strlen($str));
            $out = socket_read($socket, 2046);
            $logString .= $out;
            if(strcmp($out, 'Welcome') !== FALSE) {
                $str = "get_system_call_statist\r\n";
                $logString .= $str;
                socket_write($socket, $str, strlen($str));
                for($i = 0; $i < 7; $i++) {
                    $out = socket_read($socket, 2048, PHP_NORMAL_READ);
                    $logString .= $out;
                    $explodedRes = explode('=', trim($out));
                    $resultArray[$explodedRes[0]] = $explodedRes[1];
                }

                $str = "logout\r\n";
                $logString .= $str;
                socket_write($socket, $str, strlen($str));
            } else {
                $socketFlag = false;
            }

            $this->ApiLog->write("IP: {$activeIp}:{$activePort}\r\n{$logString}", 3);

        }

        $return_data = array(
            'ingress_cps' => $resultArray['o_cps'],
            'egress_cps' => $resultArray['t_cps'],
            'channel' => $resultArray['o_chan'],
            'call' => $resultArray['call']
        );
        echo json_encode($return_data);
    }

    public function get_daily_data(){
        $this->beforeFilter1();
        $this->loadModel('Cdr');

        $today_table_suffix = date("Ymd");
        $table_name = CDR_TABLE . $today_table_suffix;

        $sql = <<<SQL
SELECT sum(egress_call_cost) as egress_cost, sum(ingress_call_cost) as ingress_cost,sum(ingress_bill_time) as call_second,max(report_time) as max_report_time
 FROM $table_name
SQL;

        $cdr_report_data = $this->Cdr->query($sql);

        $this->loadModel('CdrMem');
        $max_report_time = $cdr_report_data[0][0]['max_report_time'];
        if (!$max_report_time){
            $yesterday_suffix = date('Ymd',strtotime("-1 days"));
            $yesterday_report_data = $this->Cdr->query("select max(report_time) as max_report_time from " . CDR_TABLE . $yesterday_suffix);
            $max_report_time = $yesterday_report_data[0][0]['max_report_time'];
        }
        $cdr_data = $this->CdrMem->get_daily_data($max_report_time);
//            echo $sql .'<br>';
//            echo $cdr_data;
//            exit;
        $egress_cost = floatval($cdr_report_data[0][0]['egress_cost'] + $cdr_data[0][0]['egress_cost']);
        $ingress_cost =  floatval($cdr_report_data[0][0]['ingress_cost'] + $cdr_data[0][0]['ingress_cost']);
        $call_mins = floatval(($cdr_report_data[0][0]['call_second'] + $cdr_data[0][0]['call_second'])/60);


        $margin = $ingress_cost - $egress_cost;
        $margin_pre = empty($ingress_cost) ? 0 : $margin / $ingress_cost * 100;

        $return_arr = array(
            'call_mins' => round($call_mins, 1),
            'margin' => "$" . round($margin, 2),
            'margin_pre' => round($margin_pre, 2),
        );
        return $return_arr;
    }

    public function get_daily_data_bak()
    {
        $this->beforeFilter1();
        $this->loadModel('Cdr');
        $start_date = date('Y-m-d') . " 00:00:00";
        $end_date = date('Y-m-d H:i:s');
        $tz = $this->Cdr->get_sys_timezone();
        $start_date .= ' ' . $tz;
        $end_date .= ' ' . $tz;
//        $report_max_time = $this->Cdr->get_report_maxtime($start_date, $end_date);
//        if (empty($report_max_time))
//        {
////            全部从client_cdr 里取数据
//            $sql = "SELECT sum(egress_cost) as egress_cost, sum(ingress_client_cost) as ingress_cost,sum(ingress_bill_minutes) as call_mins FROM client_cdr WHERE "
//                . "time BETWEEN '{$start_date}' AND '{$end_date}' AND is_final_call=1";
//            $client_cdr_data = $this->Cdr->query($sql);
//            $egress_cost = floatval($client_cdr_data[0][0]['egress_cost']);
//            $ingress_cost = floatval($client_cdr_data[0][0]['ingress_cost']);
//            $call_mins = floatval($client_cdr_data[0][0]['call_mins']);
//        }
//        else
//        {
//            $sql = "SELECT sum(egress_cost) as egress_cost, sum(ingress_client_cost) as ingress_cost,sum(ingress_bill_minutes) as call_mins FROM client_cdr WHERE "
//                . "time BETWEEN '{$report_max_time}' AND '{$end_date}'";
//            $client_cdr_data = $this->Cdr->query($sql);
//
//            $sql = "SELECT sum(egress_call_cost) as egress_cost, sum(ingress_call_cost) as ingress_cost,sum(ingress_bill_time) as call_second FROM cdr_report WHERE "
//                . "report_time BETWEEN '{$start_date}' AND '{$report_max_time}'";
//            $cdr_report_data = $this->Cdr->query($sql);
//            $egress_cost = floatval($client_cdr_data[0][0]['egress_cost']) + floatval($cdr_report_data[0][0]['egress_cost']);
//            $ingress_cost =  floatval($client_cdr_data[0][0]['ingress_cost']) + floatval($cdr_report_data[0][0]['ingress_cost']);
//            $call_mins = floatval($client_cdr_data[0][0]['call_mins']) + floatval($cdr_report_data[0][0]['call_second']/60);
//        }


        //qos
        $sql = "SELECT sum(case direction when 0 then bill_time else 0 end) as bill_time ,
sum(case direction when 0 then cost else 0 end) as ingress_cost,
sum(case direction when 1 then cost else 0 end) as egress_cost
FROM qos_route_report
WHERE report_time BETWEEN '{$start_date}' AND '{$end_date}'";
        $data = $this->Cdr->query($sql);
        $ingress_cost = $data[0][0]['ingress_cost'];
        $egress_cost = $data[0][0]['egress_cost'];
        $call_mins = floatval($data[0][0]['bill_time']) / 60;

        $margin = $ingress_cost - $egress_cost;
        $margin_pre = empty($ingress_cost) ? 0 : $margin / $ingress_cost * 100;
        $return_arr = array(
            'call_mins' => round($call_mins, 1),
            'margin' => "$" . round($margin, 2),
            'margin_pre' => round($margin_pre, 2),
        );
        return $return_arr;
    }

    public function ajax_draws_single_data()
    {
        $this->beforeFilter1();
        Configure::write('debug', 0);
        $this->autoLayout = false;
        $this->autoRender = false;
        $type = $_POST['type'];
        $date = date('Y-m-d H:i:00', intval($_POST['time'] / 1000));
        $trunk = $_POST['trunk'];
        $trunk_ip = $_POST['trunk_ip'];
        $data_type = $_POST['data_type'];
        $server = $_POST['server'];
        $show_type = $_POST['show_type'];
        $duration = $_POST['duration'];
        $old_time = date('Y-m-d H:i:00', intval($_POST['refresh_time'] / 1000));
        $is_report = true;
        if ($show_type == 'call') {
            $is_report = false;
        }
        if (!empty($server)) {
            $server = explode(':', $server);
        } else {
            $server = NULL;
        }

        $content = array(
            'chart_1' => array('0' => 0),
            'chart_2' => array('0' => 0),
            'chart_3' => array('0' => 0),
        );

        switch ($type) {
            case 1:
                $data = $this->Onlineuser->get_network_total_single($date, $server);
                if (!empty($data)) {
                    $content['chart_1'][0] = $data[0][0]['call'];
                    $content['chart_2'][0] = $data[0][0]['cps'];
                    $content['chart_3'][0] = $data[0][0]['channel'];
                }
                if ($is_report) {
                    $data_report = $this->Onlineuser->get_network_total_report_single($date, $server);
                    $content['chart_1'][0] = $this->_ready_network_data_report_single($data_report, $show_type);
                }
                break;
            case 2:
                $content = array();
                $data = $this->Onlineuser->get_draw_trunk_data_single(0, $duration, $date, $trunk, $trunk_ip, $server, $old_time);
                $content['chart_1'] = $data['call'];
                $content['chart_2'] = $data['cps'];
                $content['chart_3'] = $data['channel'];
                if ($is_report) {
                    $data_report = $this->Onlineuser->get_draw_trunk_data_report_single(0, $date, $server, $trunk, $show_type, $old_time);
                    $content['chart_1'] = $this->_ready_draw_trunk_report_single($data_report, $show_type);
                }
                break;
            case 3:
                $content = array();
                $data = $this->Onlineuser->get_draw_trunk_data_single(1, $duration, $date, $trunk, $trunk_ip, $server, $old_time);
                $content['chart_1'] = $data['call'];
                $content['chart_2'] = $data['cps'];
                $content['chart_3'] = $data['channel'];
                if ($is_report) {
                    $data_report = $this->Onlineuser->get_draw_trunk_data_report_single(1, $duration, $server, $trunk, $show_type);
                    $content['chart_1'] = $this->_ready_draw_trunk_report_single($data_report, $show_type);
                }
                break;
        }
        echo json_encode($content);
    }


    public function _generate_zero_data($duration,$show_type = 'call'){

        $arr = array();
        $duration = (int) $duration;
        switch ($duration)
        {
            case 1:
                $time = "1 hours";
                break;
            case 2:
                $time = "24 hours";
                break;
            case 3:
                $time = "7 days";
                break;
            case 4:
                $time = "30 minutes";
                break;
            case 5:
                $time = "15 minutes";
                break;
            case 6:
                $time = "15 days";
                break;
            case 7:
                $time = "30 days";
                break;
            case 8:
                $time = "60 days";
                break;
        }

        $start_time = strtotime('-'.$time);
        $end_time = time() -60;
        if ($show_type != 'call' ){
            $end_time = time() - 5 * 60;
        }

//        pr($start_time,$end_time,date('Y-m-d H:i:00',$start_time),date('Y-m-d H:i:00',$end_time),strtotime(date('Y-m-d H:i:00',$start_time)),strtotime(date('Y-m-d H:i:00',$end_time)));die;
        if ($duration >= 6){
//            1小时一个点
//            for ($start = strtotime(date('Y-m-d H:00:00',$start_time)); $start < strtotime(date('Y-m-d H:00:00',$end_time)); $start += 3600*24){
            for ($start = strtotime(date('Y-m-d 00:00:00',$start_time)); $start < strtotime(date('Y-m-d 00:00:00',$end_time)); $start += 3600*24){
                $time_key = $start * 1000;
                $arr[$time_key] = array(
                    $time_key,0
                );
            }
        }else{
            for ($start = strtotime(date('Y-m-d H:i:00',$start_time)); $start < strtotime(date('Y-m-d H:i:00',$end_time)); $start += 60){

                $time_key = $start * 1000;
                $arr[$time_key] = array(
                    $time_key,0
                );
//                if ($show_type == 'call' || (intval(date('i',$start)) % 5 == 0)){
//                    $time_key = $start * 1000;
//                    $arr[$time_key] = array(
//                        $time_key,0
//                    );
//                }
            }
        }

        return $arr;
//        pr($arr);die;
    }

    public function get_draws_data_client()
    {
        //$this->beforeFilter1();
        Configure::write('debug', 0);
        $this->autoLayout = false;
        $this->autoRender = false;

        $max_time = date('Y-m-d H:i:00');

        $trunk = $_POST['trunk'];
        $server = NULL;
        $show_type = 'call';

        switch ($_POST['duration']) {
            case 1: {
                $duration = 2;
                break;
            }
            case 7: {
                $duration = 3;
                break;
            }
            case 15: {
                $duration = 6;
                break;
            }
            case 30: {
                $duration = 7;
                break;
            }
            case 60: {
                $duration = 8;
                break;
            }
            default : {
                $duration = 1;
                break;
            }
        }

        $zero_data = $this->_generate_zero_data($duration);

        if ($trunk == '0') {
            $trunkIds = $this->Onlineuser->query('SELECT resource_id FROM resource WHERE active = true AND client_id = ' . $_SESSION['sst_client_id']);
            $trunk = array();

            foreach ($trunkIds as $item) {
                array_push($trunk, $item[0]['resource_id']);
            }
        } elseif ($trunk == '-1' || $trunk == '-2') {
            $limit = $trunk == '-1' ? 5 : 10;
            $_duration = $this->Onlineuser->get_time_by_duration($duration);

            $sql = <<<SQL
SELECT res_id as resource_id, sum(call) as calls
FROM qos_resource
LEFT JOIN resource on resource.resource_id = qos_resource.res_id
WHERE resource.client_id = {$_SESSION['sst_client_id']} AND report_time BETWEEN CURRENT_TIMESTAMP - interval '{$_duration}' AND CURRENT_TIMESTAMP
GROUP BY res_id
ORDER BY calls desc
LIMIT {$limit}
SQL;
            $trunkIds = $this->Onlineuser->query($sql);
            $trunk = array();

            foreach ($trunkIds as $item) {
                array_push($trunk, $item[0]['resource_id']);
            }
        } elseif ($trunk == '-3') {
            $_duration = $this->Onlineuser->get_time_by_duration($duration);

            $sql = <<<SQL
SELECT res_id as resource_id, sum(call) as calls
FROM qos_resource
LEFT JOIN resource on resource.resource_id = qos_resource.res_id
WHERE resource.client_id = {$_SESSION['sst_client_id']} AND report_time BETWEEN CURRENT_TIMESTAMP - interval '{$_duration}' AND CURRENT_TIMESTAMP
GROUP BY res_id
HAVING sum(call) > 0
ORDER BY calls desc
SQL;
            $trunkIds = $this->Onlineuser->query($sql);
            $trunk = array();

            foreach ($trunkIds as $item) {
                array_push($trunk, $item[0]['resource_id']);
            }
        }

        $content = $this->Onlineuser->get_draw_trunk_data_client(0, $duration, $trunk, '', $server,$zero_data);

        $max_time = strtotime($max_time) - 60;
        $iden = $_SESSION['sst_user_id'] . '_' . date('Ymd_His');
        $max_time = date('Y-m-d H:i:00', $max_time);
        $this->Onlineuser->set_admin_dashboard_time($iden, $max_time);
        $content['iden'] = $iden;
        echo json_encode($content);
    }

    /*
     * param $type:  1.network 2.orig  3.term
     * param $type:  1. 1 hour  2. 24 hour
     */

    public function get_draws_data()
    {
        $this->beforeFilter1();
        Configure::write('debug', 0);
        $this->autoLayout = false;
        $this->autoRender = false;

        $max_time = date('Y-m-d H:i:00');

        $type = $_POST['type'];
        $duration = $_POST['duration'];
        $trunk = $_POST['trunk'];
        $trunk_ip = $_POST['trunk_ip'];
        $data_type = $_POST['data_type'];
        $server = $_POST['server'];
        $show_type = $_POST['show_type'];
//        $show_type = "abr";
        $is_report = true;
        if ($show_type == 'call') {
            $is_report = false;
        }
        if (!empty($server)) {
            $server = explode(':', $server);
        } else {
            $server = NULL;
        }

        $content = array();
        $zero_data = $this->_generate_zero_data($duration);
        $report_zero_data = $zero_data;
        if ($is_report) {
            $report_zero_data = $this->_generate_zero_data($duration,$show_type);
        }

        if ($data_type == '1') {
            switch ($type) {
                case 1: //network
                    $data = $this->Onlineuser->get_network_total($duration, $server);
                    $content = $this->_ready_network_data($data, $zero_data, $duration);
                    if ($is_report) {
                        $data_report = $this->Onlineuser->get_network_total_report($duration, $server);
                        $report_content = $this->_ready_network_data_report($data_report, $show_type,$report_zero_data);
//                        $content = $data_report;
                        $content = array_merge($content, $report_content);
                    }
                    break;
                case 2: //Orig Trunks ingress
                    $content = $this->Onlineuser->get_draw_trunk_data(0, $duration, $trunk, $trunk_ip, $server,$zero_data);
                    if ($is_report) {
                        $data_report = $this->Onlineuser->get_draw_trunk_data_report(0, $duration, $server, $trunk, $show_type,$report_zero_data);
                        $select_trunk = $content['select_trunk'];
                        $content = array_merge($content, $data_report);
                        $content['select_trunk'] = array_merge_recursive($content['select_trunk'], $select_trunk);
                    }
                    break;
                case 3: //Term Trunks egress
                    $content = $this->Onlineuser->get_draw_trunk_data(1, $duration, $trunk, $trunk_ip, $server,$zero_data);
                    if ($is_report) {
                        $data_report = $this->Onlineuser->get_draw_trunk_data_report(1, $duration, $server, $trunk, $show_type,$report_zero_data);
                        $select_trunk = $content['select_trunk'];
                        $content = array_merge($content, $data_report);
                        $content['select_trunk'] = array_merge_recursive($content['select_trunk'], $select_trunk);

                    }
                    break;
            }
        } else if ($data_type == '2') {
            /*
              switch ($type)
              {
              case 1:
              $content = $this->Onlineuser->get_network_call_atempts($duration);
              break;
              case 2:

              }
             */
            $content = $this->Onlineuser->get_network_call_atempts($start_time, $end_time, $type, $server);
        }
//        $daily_data = $this->get_daily_data();
//        $content = array_merge($content,$daily_data);
        //$content['max_time'] = $max_time;
        //时间写入dashboard_time_option

        $max_time = strtotime($max_time) - 60;
        $iden = $_SESSION['sst_user_id'] . '_' . date('Ymd_His');
        $max_time = date('Y-m-d H:i:00', $max_time);
        $this->Onlineuser->set_admin_dashboard_time($iden, $max_time);
        $content['iden'] = $iden;
        echo json_encode($content);
    }


    //admin dashboard
    public function get_admin_dashboard($type, $time = 0)
    {
        Configure::write('debug', 0);
        $this->autoLayout = false;
        $this->autoRender = false;

        if($type == 1) {
            $data = $this->getActiveDashboard($time);
        }else if($type == 2) {
            $data = $this->Onlineuser->get_ajax_table1();
        }

        echo json_encode($data);
    }

    public function get_chart_point()
    {
        $this->beforeFilter1();
        Configure::write('debug', 0);
        $this->autoLayout = false;
        $this->autoRender = false;
        //        $i = $_POST['i'];
//        $max_time = $_POST['max_time'];
//        $max_time = strtotime($max_time) + $i*60;
        $iden = $_POST['iden'];
        $time = $this->Onlineuser->get_admin_dashboard_time($iden);
//        pr($time);
        $max_time = strtotime($time) * 1000;
        $report_max_time = (strtotime($time) - 5 * 60) * 1000;
        $report_show_time = date('Y-m-d H:i:00',(strtotime($time) - 5 * 60));

        $this->Onlineuser->set_admin_dashboard_time($iden, date('Y-m-d H:i:00', strtotime($time) + 60));

        $type = $_POST['type'];
        $trunk = $_POST['trunk'];
        $trunk_ip = $_POST['trunk_ip'];
        $server = $_POST['server'];
        $show_type = $_POST['show_type'];

        $is_report = true;
        if ($show_type == 'call') {
            $is_report = false;
        }
        if (!empty($server)) {
            $server = explode(':', $server);
        } else {
            $server = NULL;
        }

        $content = array();


        switch ($type) {
            case 1: //network
                $data = $this->Onlineuser->get_network_point($time, $server);
                $data = $data[0][0];
                $arr = array();
                foreach ($data as $k => $item) {
                    $item = $item + 0;
                    $arr[$k][] = array($max_time, $item);
                }
                $content = $arr;


                if ($is_report) {
//                    $data_report = $this->Onlineuser->get_network_report_point($report_show_time, $server);
                    $data_report = $this->Onlineuser->get_network_report_point($time, $server);
                    $data_report = $data_report[0][0];
                    $arr = array();
                    $bill_time = intval($data_report['bill_time']);
                    $not_zero_calls = intval($data_report['not_zero_calls']);
                    $busy_calls = intval($data_report['busy_calls']);
                    $total_calls = intval($data_report['total_calls']);
                    $cancel_calls = intval($data_report['cancel_calls']);
                    $ingress_client_cost_total = floatval($data_report['ingress_cost']);
                    $egress_cost_total = floatval($data_report['egress_cost']);
                    $pdd = $data_report['pdd'];
                    $revenue = $ingress_client_cost_total - $egress_cost_total;

                    $ready_data = array();

                    $ready_data['acd'] = !empty($not_zero_calls) ? round($bill_time / $not_zero_calls / 60, 2) : 0;
                    $ready_data['abr'] = !empty($total_calls) ? round($not_zero_calls / $total_calls * 100, 2) : 0;
                    $asr_ = intval($busy_calls) + intval($cancel_calls) + intval($not_zero_calls);
                    $ready_data['asr'] = !empty($asr_) ? round($not_zero_calls / $asr_ * 100, 2) : 0;
                    $ready_data['pdd'] = !empty($not_zero_calls) ? round($pdd / $not_zero_calls) : 0;
                    $ready_data['profitability'] = !empty($ingress_client_cost_total) ? round($revenue / $ingress_client_cost_total, 5) * 100 : 0;
                    $ready_data['revenue'] = $ingress_client_cost_total;


                    foreach ($ready_data as $k => $item) {
                        $arr[$k][] = array($report_max_time, $item);
                    }


                    $content = array_merge($content, $arr);
                }
                break;
            case 2: //Orig Trunks ingress
                $content = $this->Onlineuser->get_trunk_point(0, $time, $trunk, $trunk_ip, $server);
                if ($is_report) {
                    $data_report = $this->Onlineuser->get_trunk_report_point(0, $report_show_time, $server, $trunk, $show_type);
//                        $tmp = $data_report;
//                        $content = $tmp;
                    $content = array_merge($content, $data_report);
                }
                break;
            case 3: //Term Trunks egress
                $content = $this->Onlineuser->get_trunk_point(1, $time, $trunk, $trunk_ip, $server);
                if ($is_report) {
                    $data_report = $this->Onlineuser->get_trunk_report_point(1, $report_show_time, $server, $trunk, $show_type);
//                        $content = $data_report;
                    $content = array_merge($content, $data_report);
                }
                break;
        }

//        $data = array($time,rand(1000,10000));
//        $data['channel'] = array($time,rand(1000,10000));
//        $data['cps'] = array($time,rand(1000,10000));
        $content['is_qos_time'] = 0;
        if (intval(date('i', strtotime($time))) % 5 == 0){
            $content['is_qos_time'] = 1;
        }

        echo json_encode($content);
    }

    public function ajax_get_daily()
    {
        $this->beforeFilter1();
        Configure::write('debug', 0);
        $this->autoLayout = false;
        $this->autoRender = false;

        $data = $this->get_daily_data();

        echo json_encode($data);
    }

    public function _ready_network_data_report_single($data, $show_type)
    {

        $ready_data = array();
        $bill_time = intval($data[0][0]['bill_time']);
        $not_zero_calls = intval($data[0][0]['not_zero_calls']);
        $busy_calls = intval($data[0][0]['busy_calls']);
        $total_calls = intval($data[0][0]['total_calls']);
        $cancel_calls = intval($data[0][0]['cancel_calls']);
//            $ingress_client_cost_total = $data[0][0]['ingress_client_cost_total'];
//            $egress_cost_total = $data[0][0]['egress_cost_total'];
//            $pdd = $data[0][0]['pdd'];
        $time = strtotime($data[0][0]['report_time']) * 1000;
        $ready_data['report_time'] = $time;
        $ready_data['acd'] = !empty($not_zero_calls) ? round($bill_time / $not_zero_calls / 60, 2) : 0;
        $ready_data['abr'] = !empty($total_calls) ? round($not_zero_calls / $total_calls * 100, 2) : 0;
        $asr_ = intval($busy_calls) + intval($cancel_calls) + intval($not_zero_calls);
        $ready_data['asr'] = !empty($asr_) ? round($not_zero_calls / $asr_ * 100, 2) : 0;
        $ready_data['pdd'] = "";
        $ready_data['profitability'] = "";
        $ready_data['revenue'] = "";
        return $ready_data[$show_type];
    }

    public function _ready_network_data_report($data, $show_type,$zero_data)
    {
        $acd_zero_data = $zero_data;
        $abr_zero_data = $zero_data;
        $asr_zero_data = $zero_data;
        $pdd_zero_data = $zero_data;
        $revenue_zero_data = $zero_data;
        $profitability_zero_data = $zero_data;
        $show_acd = 'acd';
        $show_abr = 'abr';
        $show_asr = 'asr';
        $show_pdd = 'pdd';

        $show_revenue = 'revenue';
        $show_profitability = 'profitability';


        if ($show_type == 'qos') {
            $draw_data = array(
                $show_acd => array(
                    array(
                        'name' => strtoupper($show_acd),
                        'data' => array(),
                        'tooltip' => array(
                            'valueDecimals' => 4,
                        )
                    )
                ),
                $show_abr => array(
                    array(
                        'name' => strtoupper($show_abr),
                        'data' => array(),
                        'tooltip' => array(
                            'valueDecimals' => 4,
                        )
                    )
                ),
                $show_asr => array(
                    array(
                        'name' => strtoupper($show_asr),
                        'data' => array(),
                        'tooltip' => array(
                            'valueDecimals' => 4,
                        )
                    )
                ),
                $show_pdd => array(
                    array(
                        'name' => strtoupper($show_pdd),
                        'data' => array(),
                        'tooltip' => array(
                            'valueDecimals' => 4,
                        )
                    )
                ),


            );
        } else {
            $draw_data = array(

                $show_revenue => array(
                    array(
                        'name' => ucfirst($show_revenue),
                        'data' => array(),
                        'tooltip' => array(
                            'valueDecimals' => 4,
                        )
                    )
                ),
                $show_profitability => array(
                    array(
                        'name' => ucfirst($show_profitability),
                        'data' => array(),
                        'tooltip' => array(
                            'valueDecimals' => 5,
                        )
                    )
                ),

            );
        }

        $ready_data = array();
        foreach ($data as $key => $item) {
            $bill_time = intval($item[0]['bill_time']);
            $not_zero_calls = intval($item[0]['not_zero_calls']);
            $busy_calls = intval($item[0]['busy_calls']);
            $total_calls = intval($item[0]['total_calls']);
            $cancel_calls = intval($item[0]['cancel_calls']);
            $ingress_client_cost_total = floatval($item[0]['ingress_cost']);
            $egress_cost_total = floatval($item[0]['egress_cost']);
            $pdd = $item[0]['pdd'];
            $revenue = $ingress_client_cost_total - $egress_cost_total;
            $time = strtotime($item[0]['report_time']) * 1000;
            $ready_data[$key]['report_time'] = $time;
            $ready_data[$key]['acd'] = !empty($not_zero_calls) ? round($bill_time / $not_zero_calls / 60, 4) : 0;
            $ready_data[$key]['abr'] = !empty($total_calls) ? round($not_zero_calls / $total_calls * 100, 4) : 0;
            $asr_ = intval($busy_calls) + intval($cancel_calls) + intval($not_zero_calls);
            $ready_data[$key]['asr'] = !empty($asr_) ? round($not_zero_calls / $asr_ * 100, 4) : 0;
            $ready_data[$key]['pdd'] = !empty($not_zero_calls) ? round($pdd / $not_zero_calls) : 0;
            $ready_data[$key]['profitability'] = !empty($ingress_client_cost_total) ? round($revenue / $ingress_client_cost_total, 5) * 100 : 0;
            $ready_data[$key]['revenue'] = $ingress_client_cost_total;
        }

        if ($show_type == 'qos') {
            foreach ($ready_data as $item) {

                $time_key = $item['report_time'];
                if (array_key_exists($time_key,$acd_zero_data)){
                    $acd_zero_data[$time_key][1] = (float)$item[$show_acd];
                    $abr_zero_data[$time_key][1] = (float)$item[$show_abr];
                    $asr_zero_data[$time_key][1] = (float)$item[$show_asr];
                    $pdd_zero_data[$time_key][1] = (float)$item[$show_pdd];
                }
//                array_push($draw_data[$show_acd][0]['data'], array($item['report_time'], (float)$item[$show_acd]));
//                array_push($draw_data[$show_abr][0]['data'], array($item['report_time'], (float)$item[$show_abr]));
//                array_push($draw_data[$show_asr][0]['data'], array($item['report_time'], (float)$item[$show_asr]));
//                array_push($draw_data[$show_pdd][0]['data'], array($item['report_time'], (float)$item[$show_pdd]));
            }
        } else {
            foreach ($ready_data as $item) {

                $time_key = $item['report_time'];
                if (array_key_exists($time_key,$revenue_zero_data)){
                    $revenue_zero_data[$time_key][1] = (float)$item[$show_revenue];
                    $profitability_zero_data[$time_key][1] = (float)$item[$show_profitability];
                }

//                array_push($draw_data[$show_revenue][0]['data'], array($item['report_time'], (float)$item[$show_revenue]));
//                array_push($draw_data[$show_profitability][0]['data'], array($item['report_time'], (float)$item[$show_profitability]));
            }
        }

        sort($acd_zero_data);
        sort($abr_zero_data);
        sort($asr_zero_data);
        sort($pdd_zero_data);
        sort($revenue_zero_data);
        sort($profitability_zero_data);
        $draw_data[$show_acd][0]['data'] = $acd_zero_data;
        $draw_data[$show_abr][0]['data'] = $abr_zero_data;
        $draw_data[$show_asr][0]['data'] = $asr_zero_data;
        $draw_data[$show_pdd][0]['data'] = $pdd_zero_data;
        $draw_data[$show_revenue][0]['data'] = $revenue_zero_data;
        $draw_data[$show_profitability][0]['data'] = $profitability_zero_data;


//        $now_date = strtotime(date("Y-m-d H:i:00")) * 1000;
//        $previous_date = (strtotime(date("Y-m-d H:i:00")) - 60) * 1000;
//        foreach ($draw_data as $k => $v) {
//            if (!$v[0]['data']) {
//                $draw_data[$k][0]['data'] = array(array($previous_date, 0), array($now_date, 0));
//            }
//        }


        return $draw_data;
    }

    public function ready_data_init()
    {
        $start_time = "	2015-03-03 00:00:00";
        $end_time = "2015-03-03 23:59:59";
        $server = NULL;
        $data_report = $this->Onlineuser->get_network_total_report($start_time, $end_time, $server);
//        pr($data_report);die;
        $report_content = $this->_ready_network_data_report($data_report, 'abr',array());
        pr($report_content);
        die;
    }

    public function _ready_network_data($data,$zero_data, $duration)
    {
        $call_zero_data = $zero_data;
        $cps_zero_data = $zero_data;
        $channel_zero_data = $zero_data;

        $draw_data = array(
            'call' => array(
                array(
                    'name' => 'Connected Calls',
                    'data' => array(),
                    'tooltip' => array(
                        'valueDecimals' => 0,
                    ),
//
                )
            ),
            'cps' => array(
                array(
                    'name' => 'Ingress CPS',
                    'data' => array(),
                    'tooltip' => array(
                        'valueDecimals' => 0,
                    ),
                )
            ),
            'channel' => array(
                array(
                    'name' => 'Channel',
                    'data' => array(),
                    'tooltip' => array(
                        'valueDecimals' => 0,
                    ),
                )
            ),
        );

        if ($duration >= 6) {

            foreach ($data as $item) {
                $time_key = strtotime(date("Y-m-d 00:00:00", strtotime($item[0]['report_time']))) * 1000;
                if (array_key_exists($time_key,$call_zero_data)){
                    $call_zero_data[$time_key][1] = intval($item[0]['call']);
                    $cps_zero_data[$time_key][1] = intval($item[0]['cps']);
                    $channel_zero_data[$time_key][1] = intval($item[0]['channel']);
                }
            }
        } else {

            foreach ($data as $item) {
                $time_key = strtotime(date("Y-m-d H:i:00", strtotime($item[0]['report_time']))) * 1000;
                if (array_key_exists($time_key,$call_zero_data)){
                    $call_zero_data[$time_key][1] = intval($item[0]['call']);
                    $cps_zero_data[$time_key][1] = intval($item[0]['cps']);
                    $channel_zero_data[$time_key][1] = intval($item[0]['channel']);
                }
            }
        }
        sort($call_zero_data);
        sort($cps_zero_data);
        sort($channel_zero_data);
        $draw_data['call'][0]['data'] = $call_zero_data;
        $draw_data['cps'][0]['data'] = $cps_zero_data;
        $draw_data['channel'][0]['data'] = $channel_zero_data;

        return $draw_data;
    }

    public function get_mask($mask)
    {
        $net_mask_array = array(
            '10' => '255.192.0.0', '11' => '255.224.0.0', '12' => '255.240.0.0', '13' => '255.248.0.0', '14' => '255.252.0.0', '15' => '255.254.0.0', '16' => ' 255.255.0.0',
            '17' => '255.255.128.0 ', '18' => '255.255.192.0 ', '19' => '255.255.224.0', '20' => '255.255.240.0', '21' => '255.255.248.0', '22' => '255.255.252.0',
            '23' => '255.255.254.0', '24' => '255.255.255.0 ', '25' => '255.255.255.128', '26' => '255.255.255.192 ', '27' => '255.255.255.224', '28' => '255.255.255.240', '29' => ' 255.255.255.248',
            '30' => '255.255.255.252 '
        );
        if (isset($net_mask_array[$mask])) {
            return $net_mask_array[$mask];
        } else {
            'no_mask';
        }
    }

    public function get_trunks($type)
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        if ($type == 2) {
            $sql = "SELECT resource_id, alias FROM resource WHERE ingress=true and active=true ORDER BY alias";
        } else {
            $sql = "SELECT resource_id, alias FROM resource WHERE egress=true and active=true ORDER BY alias";
        }
        echo json_encode($this->User->query($sql));
    }

    public function get_trunk_ips($trunk_id)
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $whereClause = '1 = 1';
        if (is_int($trunk_id)) {
            $whereClause = "resource_id = {$trunk_id}";
        } else {
            if ($trunk_id == 'all') {
                $whereClause = "1 = 1";
            } elseif (strpos($trunk_id, 'top') !== false) {
                $trunk_id = str_replace('top', '', $trunk_id);
                $whereClause = "1 = 1 LIMIT {$trunk_id}";
            }
        }
        $sql = "SELECT resource_ip_id,ip FROM resource_ip WHERE $whereClause";
        echo json_encode($this->User->query($sql));
    }

    public function auth_ip($user_id, $user_ip)
    {
        $auth_flag = false;
        $user_auth_ip = $this->User->query("select  count(*) as cnt from  user_auth_ip  where user_id = {$user_id}");
        if (0 == $user_auth_ip[0][0]['cnt']) {
            $auth_flag = true;
        } else {
            $ip_list = $this->User->query("select  count(*) as cnt from  user_auth_ip  where user_id = {$user_id} and ip = '{$user_ip}'");
            if (!empty($ip_list[0][0]['cnt'])) {
                $auth_flag = true;
            }
        }
        return $auth_flag;

//        if (0) {
//            $user_ip_int = bindec(decbin(ip2long($user_ip)));
//            $list = $this->User->query("select  ip from  user_auth_ip  where user_id=$user_id");
//            if (!empty($list[0])) {
//                foreach ($list as $key => $value) {
//                    $t = $value[0]['ip'];
//                    $arr = split("/", $t);
//                    $ip = $arr[0];
//                    $mask = isset($arr[1]) ? $arr[1] : '32';
//                    $netmask = $this->get_mask($mask);
//                    //no  netmask
//                    if ($netmask == 'no_mask') {
//                        //没有netmask的情况 直接比较IP
//                        if ($user_ip == $ip) {
//                            return true;
//                        } else {
//                            continue;
//                        }
//                    } else {
//                        //有netmask
//                        $ip_int = bindec(decbin(ip2long($ip)));
//                        $mask_int = bindec(decbin(ip2long($netmask)));
//                        if (bindec(decbin($ip_int & $mask_int)) == bindec(decbin($user_ip_int & $mask_int))) {
//                            //合法IP
//                            return true;  //验证通过
//                        } else {
//
//                            continue;
//                        }
//                    }
//                }
//            } else {
//                return true;  //验证通过
//            }
//        }
    }

    public function init_sys_timezone()
    {
        $list = $this->User->query("select sys_timezone from  system_parameter limit 1");
        if (isset($list[0][0]['sys_timezone']) && !empty($list[0][0]['sys_timezone'])) {
            $_SESSION['sys_timezone'] = $list[0][0]['sys_timezone'];
        } else {
            $_SESSION['sys_timezone'] = '+0000'; //gmt
        }
    }

//获取用户登录角色权限模块
    public function init_role_menu($role_id)
    {

        if (!PRI) {
            $list = $this->User->query("select *  from  role where  role_id=$role_id");
            if (isset($list[0][0]) && !empty($list[0][0])) {
                $s = $list[0][0];
                unset($s['role_id']);
                unset($s['client_id']);
                unset($s['default_sysfunc_id']);
                unset($s['view_pw]']);
            }
            $_SESSION['role_menu'] = $s;
        }

        if (PRI) {
            $return = array();
            $menu_status = array();
            $sql = "SELECT delete_invoice, delete_payment, delete_credit_note, delete_debit_note, reset_balance, modify_credit_limit, modify_min_profit, view_cost_and_rate, role_name FROM sys_role WHERE role_id = {$role_id}";
            $payment_invoice_info = $this->User->query($sql);

            $sql = "SELECT is_show_mutual_balance FROM system_parameter";
            $is_show_mutual_balance_arr = $this->User->query($sql);
            $is_show_mutual_balance = $is_show_mutual_balance_arr[0][0]['is_show_mutual_balance'];

            if ('admin' == trim($payment_invoice_info[0][0]['role_name'])) {

                $sql = "select  sys_pri.pri_name, sys_pri.pri_val, sys_pri.pri_url, sys_module.module_name, sys_module.status

from  sys_pri
left join sys_module on sys_pri.module_id = sys_module.id where sys_pri.flag = true 
order by 
order_num, sys_pri.pri_val asc";
                $list = $this->User->query($sql);
                if (!empty($list)) {
                    foreach ($list as $k => $v) {
                        if (!$is_show_mutual_balance && preg_match('/Mutual/', $v['0']['pri_val'])) {
                            continue;
                        }
                        $v[0]['model_r'] = TRUE;
                        $v[0]['model_w'] = TRUE;
                        $v[0]['model_x'] = TRUE;
                        $menu_status[$v[0]['module_name']]['status'] = $v[0]['status'];
                        $return[$v[0]['module_name']][$v[0]['pri_name']] = $v[0];
                    }
                }
                $return['Payment_Invoice']['delete_invoice'] = TRUE;
                $return['Payment_Invoice']['delete_payment'] = TRUE;
                $return['Payment_Invoice']['delete_credit_note'] = TRUE;
                $return['Payment_Invoice']['delete_debit_note'] = TRUE;
                $return['Payment_Invoice']['reset_balance'] = TRUE;
                $return['Payment_Invoice']['modify_credit_limit'] = TRUE;
                $return['Payment_Invoice']['modify_min_profit'] = TRUE;
                $return['Payment_Invoice']['view_cost_and_rate'] = TRUE;
            } else {
                $sql = "select sys_role_pri.*, sys_pri.pri_name, sys_pri.pri_val, sys_pri.pri_url, sys_module.module_name from sys_role_pri left join sys_pri on sys_role_pri.pri_name = sys_pri.pri_name left join sys_module on sys_pri.module_id = sys_module.id where sys_pri.flag = true and sys_role_pri.role_id = " . intval($role_id) . " order by module_name, sys_pri.pri_val, order_num, sys_pri.id asc";
                $list = $this->User->query($sql);

                if (!empty($list)) {
                    foreach ($list as $k => $v) {
                        if (!$is_show_mutual_balance && preg_match('/Mutual/', $v['0']['pri_val'])) {
                            continue;
                        }
                        $menu_status[$v[0]['module_name']]['status'] = 1;
                        $return[$v[0]['module_name']][$v[0]['pri_name']] = $v[0];
                    }
                }
                $return['Payment_Invoice']['delete_invoice'] = $payment_invoice_info[0][0]['delete_invoice'];
                $return['Payment_Invoice']['delete_payment'] = $payment_invoice_info[0][0]['delete_payment'];
                $return['Payment_Invoice']['delete_credit_note'] = $payment_invoice_info[0][0]['delete_credit_note'];
                $return['Payment_Invoice']['delete_debit_note'] = $payment_invoice_info[0][0]['delete_debit_note'];
                $return['Payment_Invoice']['reset_balance'] = $payment_invoice_info[0][0]['reset_balance'];
                $return['Payment_Invoice']['modify_credit_limit'] = $payment_invoice_info[0][0]['modify_credit_limit'];
                $return['Payment_Invoice']['modify_min_profit'] = $payment_invoice_info[0][0]['modify_min_profit'];
                $return['Payment_Invoice']['view_cost_and_rate'] = $payment_invoice_info[0][0]['view_cost_and_rate'];
            }
            Configure::load('myconf');
            if (!Configure::read('did.enable')) {
                if (isset($return['Origination'])) {
                    unset($return['Origination']);
                }
                if (isset($return['Statistics']['reports/did'])) {
                    unset($return['Statistics']['reports/did']);
                }
            }
            $_SESSION['menu_status'] = $menu_status;
            $_SESSION['role_menu'] = $return;
        }
    }

//    查询switch_license时间。
    private function check_switch_license()
    {
//        添加License 时间提示
        $sip_servers = $this->User->query("select switch_name,lan_ip, lan_port from switch_profile order by switch_name");
        foreach ($sip_servers as $sip_server) {
            $sip_ip = $sip_server[0]['lan_ip'];
            $sip_port = $sip_server[0]['lan_port'];

            $switch_name = $sip_server[0]['switch_name'];
            $system_limit = $this->_get_system_limit($sip_ip, $sip_port);

            $license_date = $system_limit['license_date'];
            $now = time();
            $before7 = (time() + 7 * 24 * 3600);
            if (!$license_date) {
                continue;
            }
            $time = intval($license_date);
            $end_date = date('Y-m-d H:i:sO', $time);

            if ($license_date < $before7 && $license_date >= $now) {
                $key = "before7";
            } elseif ($license_date < $now) {
                $key = "expired";
            } else {
                continue;
            }
            $_SESSION['license_date'][$key][] = array(
                'switch_name' => $switch_name,
                'license_date' => $end_date,
                'ip' => $sip_ip,
                'port' => $sip_port,
            );
        }
    }

//初始登录后跳转页面
    public function init_login_url($user_id = 0, $is_admin = FALSE)
    {
        Configure::load('myconf');
        $this->check_necessary_configuration($user_id);
        $userName = $_SESSION['sst_user_name'];
        $time = date("Y-m-d H:i:s");
        $ip = $this->RequestHandler->getClientIP();
        if (empty($ip)) {
            $ip = 'null';
        }
        $this->User->query("update   users set last_login_time='$time',login_ip='$ip'   where  name='$userName'");
        $read_arr = Configure::read('file_permission.read');
        $write_arr = Configure::read('file_permission.write');

        foreach ($read_arr as $read_item) {
            if (file_exists($read_item) && !is_readable($read_item)) {
                $_SESSION['file_permission']['read'][] = $read_item;
            }
        }
        foreach ($write_arr as $write_item) {
            if (!is_writable($write_item) && file_exists($write_item)) {
                $_SESSION['file_permission']['write'][] = $write_item;
            }
        }
        $is_check_switch = Configure::read('web_base.checkswitch');
        if ($is_admin && $is_check_switch) {
            $this->check_switch_license();
        }

        if (!PRI) {
            extract($_SESSION['role_menu']);
            if ($is_carriers) {
                //$this->redirect("/clients/index");
                $this->redirect("/homes/dashboard");
            }
            if ($is_cdr_list) {
                $this->redirect("/cdrreports_db/summary_reports");
            }
            if ($is_call_simulation) {
                $this->redirect("/simulatedcalls/simulated_call");
            }
            if ($is_digit_mapping) {
                $this->redirect("/digits/view");
            }
            if ($is_rate_table) {
                $this->redirect("/rates/rates_list");
            }
            if ($is_role) {
                $this->redirect("/roles/view");
            }
            if ($is_import_log) {
                $this->redirect("/import_export_log/import");
            }
            if ($is_export_log) {
                $this->redirect("/import_export_log/export");
            }
        }
        if (PRI) {

            $role_menu = $_SESSION['role_menu'];

            if (!empty($role_menu)) {
                $this->landing_page();
            }
        }

    }

    public function check_necessary_configuration($user_id)
    {
        $user_sql = "SELECT count(*) as sum FROM users WHERE user_id = {$user_id} AND (last_login_time is not null or name != 'admin')";
        $user_data_count = $this->User->query($user_sql);
        if ($user_data_count[0][0]['sum'])
            return;
//        currency
        $curr_sql = "SELECT * FROM currency where active = true limit 1";
        $curr_data = $this->User->query($curr_sql);
        if (empty($curr_data)) {
            $this->redirect("/necessary_configuration/currs/$user_id");
        }

//        code deck
        $code_deck_exist = $this->User->query("SELECT code_deck_id FROM code_deck WHERE client_id = 0 limit 1");
        if (!isset($code_deck_exist[0][0]['code_deck_id']))
            $code_deck_exist = $this->User->query("INSERT INTO code_deck (name,client_id) VALUES ('A-Z',0) returning code_deck_id ");

        $a_z_code_deck_id = $code_deck_exist[0][0]['code_deck_id'];
        $a_z_codedeck_sql = "SELECT count(*) as sum from code where code_deck_id = {$a_z_code_deck_id} ";
        $code_count = $this->User->query($a_z_codedeck_sql);
        if ($code_count[0][0]['sum'] == 0) {
            $this->redirect("/necessary_configuration/codes_deck/$a_z_code_deck_id/$user_id");
        }

        if ($this->Mailtmp->check_mail_tmp()) {
            $this->redirect("/necessary_configuration/mailtmp/$user_id");
        }

//       billing account
        $billing_sql = "SELECT count(*) as sum FROM system_parameter WHERE paypal_account is not null AND stripe_account is not null";
        $billing_result_count = $this->User->query($billing_sql);
        if ($billing_result_count[0][0]['sum'] == 0) {
            $this->redirect("/necessary_configuration/setup_billing/$user_id");
        }

//        mail config
        $config_conditions = array(
            "smtphost is not null",
            "smtpport is not null",
            "emailusername is not null",
            "emailpassword is not null",
            "loginemail is not null",
            "fromemail is not null",
            "emailname is not null",
            "smtp_secure is not null",
            "smtphost != ''",
            "smtpport != ''",
            "emailusername != ''",
            "emailpassword != ''",
            "fromemail != ''",
            "emailname != ''",
        );
        $config_conditions_str = implode(" AND ", $config_conditions);
        $mail_config_sql = "SELECT count(*) as sum FROM system_parameter WHERE {$config_conditions_str}";
        $mail_config_result_count = $this->User->query($mail_config_sql);
        if ($mail_config_result_count[0][0]['sum'] == 0) {
            $this->redirect("/necessary_configuration/setup_mail_config/$user_id");
        }
//       payment term account
        $payment_term_sql = "SELECT count(*) as sum FROM payment_term";
        $payment_term_count = $this->User->query($payment_term_sql);
        if ($payment_term_count[0][0]['sum'] == 0) {
            $this->redirect("/necessary_configuration/setup_payment_term/$user_id");
        }

//       voip gateway
        $gateway_sql = "SELECT count(*) as sum FROM voip_gateway";
        $gateway_count = $this->User->query($gateway_sql);
        if ($gateway_count[0][0]['sum'] == 0) {
            $this->redirect("/necessary_configuration/setup_voip_gateway/$user_id");
        }

//        LRN Setting
//        $lrn_sql = "SELECT count(*) as sum FROM lrn_groups";
//        $lrn_count = $this->User->query($lrn_sql);
//        if ($lrn_count[0][0]['sum'] == 0) {
//            $this->redirect("/necessary_configuration/setup_lrn/$user_id");
//        }

//        setup_jurisdiction
        $jur_sql = "SELECT count(*) as sum FROM jurisdiction_prefix";
        $jur_count = $this->User->query($jur_sql);
        if ($jur_count[0][0]['sum'] == 0) {
            $this->redirect("/necessary_configuration/setup_jurisdiction/$user_id");
        }
    }

    public function init_system()
    {
        $list = $this->User->query("select  sys_currency  from    system_parameter");
        $sys_currency = !empty($list[0][0]['sys_currency']) ? $list[0][0]['sys_currency'] : '';
        $list = $this->User->query("
			   select rate  from currency_updates where currency_id = (
		   				select  currency_id  from  currency  where code=(select  sys_currency  from    system_parameter)
					) and modify_time=(
		  					select max(modify_time) from currency_updates where currency_id = (
		   select  currency_id  from  currency  where code=(select  sys_currency  from    system_parameter)
		  	)
		 	 )
	 	");
        $sys_currency_rate = !empty($list[0][0]['rate']) ? $list[0][0]['rate'] : '';
        $_SESSION['system_currency'] = compact('sys_currency', 'sys_currency_rate');

//        //------------------localdata.js自动完成
//        $localdata = "js/localdata.js";
//        if (file_exists($localdata)) {
//            if (is_writable($localdata)) {
//                /*
//                  $code_deck = "";
//                  $default_code_deck = $this->User->query("SELECT default_code_deck FROM system_parameter LIMIT 1");
//                  if($default_code_deck != '') {
//                  $code_deck = "WHERE code_deck_id = {$default_code_deck[0][0]['default_code_deck']}";
//                  }
//                 */
//                //$code_info = $this->User->query("select * from code {$code_deck}");
//                $code_info = $this->User->query("select * from code");
//                $client_info = $this->User->query("select client.client_id,name from  client  where 1=1 order by name");
//                $rate_info = $this->User->query("select rate_table_id,name as table_name,code_name,currency_code from rate_table left join (select code_deck_id,name as code_name from code_deck )deck on deck.code_deck_id=rate_table.code_deck_id left join (select code as currency_code,currency_id from currency) curr on curr.currency_id=rate_table.currency_id where 1=1 limit '10' offset '0' ");
//                $rate_info_term = $this->User->query("select rate_table_id,name as table_name,code_name,currency_code from rate_table left join (select code_deck_id,name as code_name from code_deck )deck on deck.code_deck_id=rate_table.code_deck_id left join (select code as currency_code,currency_id from currency) curr on curr.currency_id=rate_table.currency_id where 1=1 limit '10' offset '0' ");
//                if (!empty($code_info) || !empty($client_info)) {
//                    foreach ($code_info as $k => $v) {
//                        $country_arr[$v[0]['country']] = '"' . $v[0]['country'] . '"';
//                        $code_name_arr[$v[0]['name']] = '"' . $v[0]['name'] . '"';
//                        $code_arr[$v[0]['code']] = '"' . $v[0]['code'] . '"';
//                    }
//                    foreach ($client_info as $m => $n) {
//                        $client_arr[$n[0]['name']] = '"' . $n[0]['name'] . '"';
//                    }
//                    foreach ($rate_info as $i => $j) {
//                        $rate_arr[$j[0]['table_name']] = '"' . $j[0]['table_name'] . '"';
//                    }
//                    foreach ($rate_info_term as $a => $b) {
//                        $rate_arr_term[$b[0]['table_name']] = '"' . $b[0]['table_name'] . '"';
//                    }
//
//                    $jsdata = "var countries = [" . implode(",", $country_arr) . "];\n";
//                    $jsdata .= "var cities = [" . implode(",", $code_name_arr) . "];\n";
//                    $jsdata .= "var codes = [" . implode(",", $code_arr) . "];\n";
//                    $jsdata .= "var names = [" . implode(",", $client_arr) . "];\n";
//                    $jsdata .= "var rates = [" . implode(",", $rate_arr) . "];\n";
//
//                    $jsdata .= "var rates_term = [" . implode(",", $rate_arr_term) . "];\n";
//
//                    $handle = au$localdata, "w");
//                    fwrite($handle, $jsdata);
//
//                    if (flock($handle, LOCK_EX)) {
//                        fwrite($handle, $jsdata);
//                        flock($handle, LOCK_UN);
//                    }
//
//                    fclose($handle);
//                }
//            }
//        }
    }

    public function add_web_session($msg = '')
    {
        App::import('Model', 'Websession');
        $web_model = new Websession();
        $this->data ['Websession'] ['user_id'] = @$_SESSION['sst_user_id'] or 0;
        $this->data ['Websession'] ['host'] = $this->getIP();
        $this->data ['Websession'] ['agent'] = $_SERVER['HTTP_USER_AGENT'];
        $this->data ['Websession'] ['msg'] = $msg;
        $web_model->save($this->data ['Websession']);

        if ($msg != '') {
            $user = $_POST['username'];
            $subject = __("User[%s] attempted to log in with failure.", true, $user);
            $content = "$msg";
            $sql = "insert into admin_alert(subject, content, create_time, type) values('$subject', '$content', CURRENT_TIMESTAMP(0), 3)";
            $web_model->query($sql);
        }
    }

    public function getIP()
    {
        if (@$_SERVER["HTTP_X_FORWARDED_FOR"])
            $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
        else if (@$_SERVER["HTTP_CLIENT_IP"])
            $ip = $_SERVER["HTTP_CLIENT_IP"];
        else if (@$_SERVER["REMOTE_ADDR"])
            $ip = $_SERVER["REMOTE_ADDR"];
        else if (@getenv("HTTP_X_FORWARDED_FOR"))
            $ip = getenv("HTTP_X_FORWARDED_FOR");
        else if (@getenv("HTTP_CLIENT_IP"))
            $ip = getenv("HTTP_CLIENT_IP");
        else if (@getenv("REMOTE_ADDR"))
            $ip = getenv("REMOTE_ADDR");
        else
            $ip = "Unknown";
        return $ip;
    }

    public function validate_code()
    {
        header("content-type:image/png");     //设置创建图像的格式
        $image_width = 100;                      //设置图像宽度
        $image_height = 25;                     //设置图像高度
        srand(microtime() * 100000);            //设置随机数的种子
        $new_number = '';
        for ($i = 0; $i < 4; $i++) {                  //循环输出一个4位的随机数
            $new_number = $new_number . dechex(rand(0, 15));
        }
        $_SESSION['validate_code'] = $new_number;    //将获取的随机数验证码写入到SESSION变量中
        $num_image = imagecreate($image_width, $image_height);  //创建一个画布
        imagesavealpha($num_image, true);
        imagealphablending($num_image, false);
        $white = imagecolorallocatealpha($num_image, 255, 255, 255, 127);          //设置画布的颜色
        imagefill($num_image, 0, 0, $white);
        for ($i = 0; $i < strlen($_SESSION['validate_code']); $i++) { //循环读取SESSION变量中的验证码
            $font = mt_rand(16, 28);                              //设置随机的字体
            $x = mt_rand(1, 8) + $image_width * $i / 4;               //设置随机字符所在位置的X坐标
            $y = mt_rand(1, $image_height / 4);                   //设置随机字符所在位置的Y坐标
            $color = imagecolorallocate($num_image, mt_rand(0, 100), mt_rand(0, 150), mt_rand(0, 200));  //设置字符的颜色
            imagestring($num_image, $font, $x, $y, $_SESSION['validate_code'][$i], $color);              //水平输出字符
        }
        ob_clean();
        imagepng($num_image);               //生成PNG格式的图像
        imagedestroy($num_image);           //释放图像资源
    }

    public function login($notes_key = 0)
    {
        Configure::write('debug', 0);
        Configure::load("myconf");
        $this->layout = '';
        $welcome_message_result = $this->Onlineuser->query("SELECT welcome_message,login_page_content, login_captcha, allow_registration FROM system_parameter LIMIT 1");
        $this->set('welcome_message', $welcome_message_result[0][0]['welcome_message']);
        $this->set('login_page_content', $welcome_message_result[0][0]['login_page_content']);
        $this->set('login_captcha', $welcome_message_result[0][0]['login_captcha']);
        $this->set('allow_registration', $welcome_message_result[0][0]['allow_registration']);
        $release_note = $this->_get_release();
        $this->set('release_note', $release_note);
        $notes = "";
//        switch ($notes_key)
//        {
//            case '1':
//                $notes = "You are unable to login now due to system error. Please contact your system administrator.";
//                break;
//            default :$notes = "";
//        }
        $this->set("notes", $notes);
        $sections = parse_ini_file(CONF_PATH, TRUE, INI_SCANNER_RAW);
        $this->set("is_copyright_hypelink", isset($section["web_feature"]["copyright_link"]) ? $section["web_feature"]["copyright_link"] :0);
        $this->loadModel('Systemparam');
        $info = $this->Systemparam->find('first',array(
            'fields' => array('login_page_content','login_fit_image','sys_id'),
        ));
        $admin_content = $info['Systemparam']['login_page_content'];
        $this->set("admin_content", $admin_content);
        $this->set('login_fit_image',$info['Systemparam']['login_fit_image']);

        $background_tmp_path = APP . 'webroot' . DS . 'upload' . DS . 'images' . DS. 'tmp'. DS. 'background_tmp.png';
        $background_path = APP . 'webroot' . DS . 'upload' . DS . 'images' . DS . 'background.png';
        if (file_exists($background_tmp_path)) {
            $background = $this->webroot . 'upload/images/tmp/background_tmp.png';
        } elseif (file_exists($background_path)) {
            $background = $this->webroot . 'upload/images/background.png';
        } else {
            $background = '';
        }
        $this->set('background', $background);
    }

    public function _get_release()
    {
        return '';
// Remove release notes
        $release_file = ROOT . DS . 'release.note';
        $content = file_get_contents($release_file);
        $content_arr = explode("\n", trim($content));
        foreach ($content_arr as $item) {
            $item_arr = explode("=", $item);
            if ('Web' == trim($item_arr[0])) {
                return $item_arr[1];
            }
        }
    }

    public function login_test()
    {
        $this->layout = '';
        $this->set('f', $_REQUEST);
    }

//退出
    public function logout()
    {
//App::import('Vendor', 'logging');
//Logging::log($this->Session->read('sst_user_id'), "System", "Logout",$this->User);
        $time = date("Y-m-d H:i:s");
        $user_name = $_SESSION['sst_user_name'];
        $this->Onlineuser->query("update  users  set last_login_time='$time'  where  name='$user_name'");
        $this->Onlineuser->query("delete from  online_users  where  user_name='$user_name'");
        $_SESSION = array(); //清空所有session变量
        $this->redirect(array('controller' => 'homes', 'action' => 'login'));
    }

    function auth_user()
    {
        $sql = "select inactivity_timeout, login_captcha from system_parameter limit 1";
        $result = $this->User->query($sql);
        $result[0][0]['inactivity_timeout'] = $result[0][0]['inactivity_timeout'] > 0 ? $result[0][0]['inactivity_timeout'] : 5;
        /* set the cache limiter to 'private' */

        session_cache_limiter('private');
        $cache_limiter = session_cache_limiter();

        /* set the cache expire to 30 minutes */
        session_cache_expire((int)($result[0][0]['inactivity_timeout']));
        $cache_expire = session_cache_expire();
//        $this->Session->cookieLifeTime = (int)($result[0][0]['inactivity_timeout']);
        $this->Session->cookieLifeTime = (int)($result[0][0]['inactivity_timeout']);
        $_SESSION['expiretime'] = time() + 60*(int)$result[0][0]['inactivity_timeout'];
        /* start the session */

        $userName = '';
        $password = '';
        $f = array();
        if (!empty($this->params['form'])) {
            $f = $this->params['form'];
//            $captcha = $f['captcha']; //验证码
//            if (empty($captcha)) {
//                $this->Session->write('login_failed', 'please  input  aptcha');
//                $this->Session->write('backform', $f);
//                $this->redirect('/homes/login');
//                exit();
//            }
//
//            $c_code = isset($_SESSION['validate_code']) ? $_SESSION['validate_code'] : rand();
//
//            if (empty($c_code)) {
//                $this->redirect('/homes/login');
//            }
//            Configure::load("myconf");
//            if (Configure::read("debug") == 0) {
//                if (strcmp($captcha, $c_code)) {
//                    $this->Session->write('login_failed', __('entercaptchaerror', true));
//                    $this->Session->write('backform', $f);
//                    $this->redirect('/homes/login');
//                    exit();
//                }
//            }
        } else {
            $f = $this->params['url'];
        }
//            if (isset($f['client_id']) && preg_match('/^[0-9]+$/', $f['client_id'])) {
        if (isset($f['client_id'])) {
            $f['client_id'] = base64_decode($f['client_id']);
            $client_id = $f['client_id'];
            $list = $this->User->query("select  login,password  from  client  where   client_id={$client_id}");
            if (!empty($list)) {
                $f['username'] = $list[0][0]['login'];
                $f['password'] = $list[0][0]['password'];
                $f['lang'] = $f['lang'];
            }
        }
//        }
        $userName = $f['username'];
        $password = $f['password'];

        if($result[0][0]['login_captcha'] && $result[0][0]['login_captcha'] !== 'false' && !isset($f['client_id'])) {
            $captcha = $this->data['captcha']; //验证码
            if ($captcha != $_SESSION['validate_code']) {
                $this->User->create_json_array('', 101, __('Please input correctly Verify Code', true));
                $this->Session->write('m', $this->User->set_validator_data());
                $this->redirect('/homes/login');
                exit();
            }
        }


        if (empty($userName) || empty($password)) {
            $this->Session->write('login_failed', __('Please input user name and password', true));
            $this->Session->write('backform', $f);
            $this->redirect('/homes/login');
            exit();
        }
        if (!preg_match('/^[0-9a-zA-Z_][0-9a-zA-Z_ \|\.\=\-@]+[0-9a-zA-Z_]$/', $userName)) {
            $this->Session->write('login_failed', __('username_format', true));
            $this->Session->write('backform', $f);
            $this->redirect('/homes/login');
            exit();
        }
//			if (!preg_match('/^[a-zA-Z0-9]+$/',$password)) {
//				$this->Session->write('login_failed',__('userpass_format',true));
//				$this->Session->write('backform',$f);
//				$this->redirect('/homes/login');
//				exit();
//			}
        $_SESSION['role_menu'] = array(
            'Management' => array(
                'clients' => array('model_r' => true, 'model_w' => false, 'model_x' => false),
                'pr_invoices' => array('model_r' => true, 'model_w' => false, 'model_x' => false),
                'clientmutualsettlements' => array('model_r' => true, 'model_w' => false, 'model_x' => false)
            ),
            'Statistics' => array('cdrreports' => array('model_r' => true, 'model_x' => true, 'model_w' => false),
                'monitorsreports' => array('model_r' => true, 'model_x' => true, 'model_w' => false)),
            'Switch' => array('clientrates' => array('model_r' => true, 'model_x' => false, 'model_w' => false)),
            'Configuration' => array('users:changepassword' => array('model_r' => true, 'model_w' => true, 'model_x' => false)),
        );
        $this->user_login($f);
    }

    function user_login($f)
    {
        $userName = $f['username'];
        $password = $f['password'];
        $auth_result = $this->User->auth_user($userName, $password);
        if ($auth_result == true) {
            // version info
            Configure::write('debug', 0);
            $this->loadModel('VersionInfo');
            $version_info = $this->VersionInfo->find('all', array('order'=>array('id'=>'asc')));
            foreach($version_info as $program){
                if(isset($program["VersionInfo"]["program_name"]) && $program["VersionInfo"]["program_name"] == self::VERSION_KEY){
                    $this->Session->write('current_version', $program["VersionInfo"]["major_ver"]);
                    break;
                }
            }
            $this->Session->write('version_info', $version_info);
            // base url
            $this->Session->write('base_url', $this->User->get_server());
            // get support
            $email_info = $this->User->query('SELECT fromemail as "from", smtphost, smtpport,emailusername as username,loginemail, emailpassword as "password", emailname as "name", smtp_secure,realm,workstation FROM system_parameter');
            $this->Session->write('get_support_enabled', !empty($email_info) ? $this->email_settings($email_info[0][0]): false);

            $_SESSION['login_success'] = 'succ';
            $user_type = $auth_result[0][0]['user_type']; //用户身份

            $this->Session->write('server_host', $_SERVER['HTTP_HOST']);

            if(isset($f['lang'])) {
                $this->Session->write('Config.language', $f['lang']);
            }
            $this->Session->write('sst_user_id', $auth_result[0][0]['user_id']);
            $this->Session->write('sst_password', $password); //当前用户的密码
            $this->Session->write('sst_user_name', $userName);
            $this->Session->write('sst_role_id', $auth_result[0][0]['role_id']);
            $this->Session->write('login_type', $user_type); //登录身份

            $role_info = $this->User->query("select * from sys_role where role_id = " . intval($auth_result[0][0]['role_id']));
            $this->Session->write('sst_role_name', empty($role_info[0][0]['role_name']) ? '' : $role_info[0][0]['role_name']);
            //var_dump($auth_result);

//$this->add_web_session();
//App::import('Vendor', 'logging');
//Logging::log($this->Session->read('sst_user_id'), "System", "Log in",$this->User);
            $time = date("Y-m-d H:i:s");
            $ip = $this->RequestHandler->getClientIP();
            if (empty($ip)) {
                $ip = 'null';
            }

            if (!$this->auth_ip($auth_result[0][0]['user_id'], $ip)) {
                $this->Session->write('login_failed', 'IP has been banned  ');
                $this->Session->write('backform', $f);
                $this->add_web_session("IP is banned");
                $this->redirect('/homes/login');
            }
            if ($user_type != 1)
                $this->User->query("update   users set last_login_time='$time',login_ip='$ip'   where  name='$userName'");
            $this->User->query("delete from  online_users  where  user_name='$userName'");
//            $this->init_system();
            $this->init_sys_timezone();
            if ($user_type == 1) {
                $this->add_web_session();
                $this->init_admininfo($auth_result, $userName, TRUE);
                die(var_dump('1'));
            }
            if ($user_type == 3) {
                $this->init_clientinfo($auth_result, $userName);
            }
            if ($user_type == 5) {
                $this->add_web_session();
                $this->init_commoninfo($auth_result, $userName);
            }
            if ($user_type == 2) {
                $this->add_web_session();
                $this->init_agent_info($auth_result, $userName);
            }
//            if ($user_type == 6) {
//                $this->Session->write('sst_role_name', 'new_user');
//                die(var_dump($_SESSION));
//                $this->add_web_session();
////                $this->init_agent_info($auth_result, $userName);
//            }
        } else {
            Configure::load('myconf');
            $is_enable_host_dialer = Configure::read('host_dialer.enabled');
            if ($is_enable_host_dialer) {
                $reseller = $this->Reseller->find('first', array(
                    'conditions' => array(
                        'login_id' => $userName,
                        'password' => $password,
                    )
                ));
                if (!empty($reseller)) {
                    $this->Session->write('Config.language', $f['lang']);
                    $this->init_reseller($userName, $reseller['Reseller']['id']);
                }
            }
            $ip = $this->RequestHandler->getClientIP();
            $this->add_web_session("User Name or Password is incorrect! <br />UserName: {$userName}<br /> Password: {$password}<br />Attempted IP:$ip");
            $this->Session->write('login_failed', __('nameorpass_incorrect', true));
            $this->Session->write('backform', $f);
            $this->redirect('/homes/login');
            return;
        }
    }

    function init_reseller($username, $id)
    {
        $limits = array(
            'Dialer Management' => array(
                'resellers' => array(
                    'pri_name' => 'resellers:client',
                    'pri_val' => 'Client',
                    'pri_url' => 'resellers/client',
                    'module_name' => 'Dialer Management',
                    'model_r' => 't',
                    'model_w' => 't',
                    'model_x' => 't',
                )
            )
        );
        $this->Session->write('role_menu', $limits);
        $this->Session->write('login_type', 10);
        $this->Session->write('sst_user_name', $username);
        $this->Session->write('reseller_id', $id);


        $this->redirect('/resellers/client');
    }

    function init_agent_info($auth_result, $userName)
    {
        $user_id = $auth_result[0][0]['user_id'];

//        $this->Session->write('sst_role', $this->User->findRoleInfo_user_id($user_id)); //初始化用户角色信息
//        $role = $this->Session->read('sst_role');
//        $this->User->findPrivilegeInfo($role_id); //初始化用户的权限
//        $url = $role['func_url'];

        $this->loadModel('Agent');

        $agent_info = $this->Agent->find('first', array(
            'conditions' => array(
                'user_id' => intval($user_id)
            )
        ));
//        die(var_dump($user_id, $agent_info));
        $this->data ['Onlineuser'] ['user_id'] = $user_id;
        $this->data ['Onlineuser'] ['agent_id'] = $agent_info['Agent']['agent_id'];
        $this->data ['Onlineuser'] ['user_type'] = 2;
        $this->data ['Onlineuser'] ['user_name'] = $userName;
        $online_id = $this->Onlineuser->add_online_user($this->data);
        $this->Session->write('sst_agent_info', $agent_info);
        $this->Session->write('sst_online_id', $online_id); //管理员对应的顶极代理商
        $_SESSION['role_menu']['Routing']['gatewaygroups:view']['model_r'] = true;
        $this->redirect('/agent_portal/agent_dashboard');
//pr($this->Session->read("sst_manager_reseller"));
//pr($this->Session->read("sst_retail"));
    }


    function init_commoninfo($auth_result, $userName)
    {
        $reseler_id = $auth_result[0][0]['reseller_id'];
        $user_id = $auth_result[0][0]['user_id'];
        $role_id = $auth_result[0][0]['role_id'];
        $this->Session->write('sst_role', $this->User->findRoleInfo_user_id($user_id)); //初始化用户角色信息
        $role = $this->Session->read('sst_role');
        $this->User->findPrivilegeInfo($role_id); //初始化用户的权限
        $url = $role['func_url'];


        $this->data ['Onlineuser'] ['user_id'] = $user_id;
        $this->data ['Onlineuser'] ['agent'] = $reseler_id;
        $this->data ['Onlineuser'] ['user_type'] = 5;
        $this->data ['Onlineuser'] ['user_name'] = $userName;
        $online_id = $this->Onlineuser->add_online_user($this->data);
        $this->Session->write('sst_online_id', $online_id); //管理员对应的顶极代理商
        $this->redirect('/' . $url);
//pr($this->Session->read("sst_manager_reseller"));
//pr($this->Session->read("sst_retail"));
    }

//初始化管理员信息
    function init_admininfo($auth_result, $userName, $is_admin = FALSE)
    {
        $user_id = $auth_result[0][0]['user_id'];
        $role_id = $auth_result[0][0]['role_id'];
        $this->init_role_menu($auth_result[0][0]['role_id']);
        $this->data ['Onlineuser'] ['user_id'] = $user_id;
        $this->data ['Onlineuser'] ['user_type'] = 1;
        $this->data ['Onlineuser'] ['user_name'] = $userName;
        $online_id = $this->Onlineuser->add_online_user($this->data);

        $this->Session->write('sst_online_id', $online_id); //管理员对应的顶极代理商
        $this->init_login_url($user_id, $is_admin);
    }

    function _switch_database()
    {
        if (isset($_SESSION['carrier_panel']['database_name'])) {
            $database_name = $_SESSION['carrier_panel']['database_name'];
            $list = $this->Client->query(" select   datname  from  pg_database where datname='$database_name' ;");
            if (empty($list[0][0]['datname'])) {
                return false;
            } else {
                return true;
            }
        }
        return false;
    }

#初始化partition 项目

    function _init_partition_project()
    {
//	return $this->_switch_database();
        return true;
    }

//初始化客户id,角色
    function init_clientinfo($auth_result, $userName)
    {
        $user_id = $auth_result[0][0]['user_id'];
        $client_id = $auth_result[0][0]['client_id'];
        $clientInfo = $this->Client->find('first', array('conditions' => array('Client.client_id' => $client_id)));
        $this->Session->write('carrier_panel', $clientInfo);

        // if has ingress
        $this->loadModel('Resource');
        $sql = "select count(*) from resource where client_id = $client_id and ingress = 't'";
        $resource_table = $this->Resource->query($sql);
        $row_count = $resource_table[0][0]['count'];
        $this->Session->write('ingress_cnt', $row_count);

        $role_id = $auth_result[0][0]['role_id'];
//        $res = $this->User->query('SELECT view_cost_and_rate FROM sys_role WHERE role_id = 2');
//        if (!empty($res[0][0]['view_cost_and_rate']) && $res[0][0]['view_cost_and_rate'] = 1) {
//            $_SESSION['role_menu']['Payment_Invoice']['view_cost_and_rate'] = $res[0][0]['view_cost_and_rate'];
//        }
        $this->Session->write('sst_client_id', $client_id);
//记录登录信息
        $this->data ['Onlineuser'] ['user_id'] = $user_id;

        $this->data ['Onlineuser'] ['user_type'] = 3;
        $this->data ['Onlineuser'] ['user_name'] = $userName;
        $online_id = $this->Onlineuser->add_online_user($this->data);
        $this->Session->write('sst_online_id', $online_id);
        $this->init_client_role($client_id);

        // welcome message
        $welcome_message_result = $this->Onlineuser->query("SELECT welcome_message,login_page_content, login_captcha FROM system_parameter LIMIT 1");
        $this->User->create_json_array('', 200, __($welcome_message_result[0][0]['welcome_message'], true));
        $this->Session->write("m", User::set_validator());

        if ($clientInfo['Client']['client_type'] == 1) {
            $this->redirect("/did_client/index");
        } else {
            $project_name = Configure::read('project_name');
            if ($project_name == 'exchange') {
                $this->redirect("/clientcdrreports/credit_balance/");
            } else {

                $post = $_SESSION['carrier_panel']['Client'];

                if (empty($post['is_panel_accountsummary']) && empty($post['is_panel_ratetable']) && empty($post['is_panel_trunks']) && empty($post['is_panel_products']) &&
                    empty($post['is_panel_balance']) && empty($post['is_panel_paymenthistory']) && empty($post['is_panel_onlinepayment']) && empty($post['is_panel_invoices']) &&
                    empty($post['is_panel_cdrslist']) && empty($post['is_panel_summaryreport']) && empty($post['is_panel_sippacket'])
                ) {
                    $this->add_web_session("Do not have any privileges");
                    $this->redirect('/homes/login');
                }

                $this->add_web_session();
//$this->redirect('http://'.$_SERVER['HTTP_HOST'].$this->webroot."homes/login?sessinfo=".urlencode($sess_info));
                $this->redirect("/clients/view");
                /*if ($post['is_client_info'])
                    $this->redirect("/clients/view/");
                if ($post['is_invoices'])
                    $this->redirect("/pr/pr_invoices/view");
                if ($post['is_cdrslist'])
                    $this->redirect("/cdrreports_db/summary_reports");
                if ($post['is_rateslist'])
                    $this->redirect("/clientrates/view_rate");
                if ($post['is_changepassword'])
                    $this->redirect("/users/changepassword");*/
            }
        }
    }

    function init_client_role($client_id)
    {
        $list = $this->User->query("select   is_panel_accountsummary, is_panel_ratetable, is_panel_trunks, is_panel_products, is_panel_balance,is_panel_paymenthistory,
is_panel_onlinepayment, is_panel_invoices, is_panel_cdrslist, is_panel_summaryreport,is_panel_sippacket, is_panel_mydid, is_panel_didrequest
			  from  client where  client_id=$client_id");
        $this->Session->write('sst_client_role', $list);
    }

    function ping_and_traceroute()
    {
        if ($this->RequestHandler->isPost()) {
            $type = $_POST['type'];
            $ip_address = $_POST['ip_address'];
            if ($type == 0) {
                $shell_type = "ping -c 10";
            } else {
                $shell_type = "traceroute";
            }
            $cmd = "$shell_type $ip_address";
            $result = shell_exec($cmd);
            $result = str_replace("\n", "<br />", $result);
            $this->set('data', $result);
            $this->set('type', $type);
            $this->set('ip_address', $ip_address);
        }
    }

    public function auto_delivery()
    {
        if ($this->RequestHandler->isPost()) {
            $group_by = $_POST['group_by'];
            $timezone = $_POST['timezone'];
            $address = $_POST['email_address'];
            $auto_delivery_subject = $_POST['auto_delivery_subject'];
            $auto_delivery_content = $_POST['auto_delivery_content'];
            $sql1 = "update system_parameter set auto_delivery_timezone = '{$timezone}', auto_delivery_address = '{$address}', auto_delivery_group_by = {$group_by}";
            $sql2 = "update mail_tmplate set auto_delivery_subject = '{$auto_delivery_subject}', auto_delivery_content = '{$auto_delivery_content}'";
//            $sql = "update system_parameter set auto_delivery_timezone = '{$timezone}', auto_delivery_address = '{$address}', auto_delivery_group_by = {$group_by};
//            update mail_tmplate set auto_delivery_subject = '{$auto_delivery_subject}', auto_delivery_content = '{$auto_delivery_content}'";

            $this->Onlineuser->query($sql1);
            $this->Onlineuser->query($sql2);
            $this->Onlineuser->create_json_array('', 201, __('Succeeded', true));
            $this->Session->write("m", Onlineuser::set_validator());
        }
        $sql = "SELECT auto_delivery_timezone, auto_delivery_address, auto_delivery_subject, auto_delivery_content,auto_delivery_group_by FROM system_parameter left join mail_tmplate on true  LIMIT 1";
        $data = $this->Onlineuser->query($sql);
        $this->set('data', $data);
    }

    public function permission()
    {
        clearCache();
        Configure::write('debug', 0);
    }

    //找回密码
    public function forgot_password()
    {

        $this->layout = '';
    }

    //发送邮件找回密码
    public function send_email_to_retrieve_password()
    {

        $name = trim($_POST['username']);


        $sql = "select users.user_id,users.name,users.password,users.email as user_email,client.email as to_email, client.name as client_name, client.company as company from users left join client on (users.user_id = client.user_id OR users.client_id = client.client_id) where users.name = '{$name}'";
        $data = $this->User->query($sql);

        if (empty($data)) {

            $this->User->create_json_array('', 101, __('The field username is incorrect.', true));
            $this->Session->write("m", User::set_validator());
            $this->xredirect('/homes/forgot_password');
        }


        //$email_info = $this->User->query('SELECT fromemail as "from", smtphost, smtpport,emailusername as username,loginemail, emailpassword as "password", emailname as "name", smtp_secure,realm,workstation FROM system_parameter');

        if (empty($data[0][0]['to_email'])) {
            $data[0][0]['to_email'] = $data[0][0]['user_email'];
            $email_to = $data[0][0]['to_email'];
            if(empty($data[0][0]['to_email'])){
                $current_datetime = date("Y-m-d H:i:s");
                $sql = "insert into retrieve_password_log (username,operation_time,status) values('$name','{$current_datetime}', 1)";
                $this->User->query($sql);

                $this->User->create_json_array('', 101, __('Your e-mail is incorrect, you can not change your password.', true));
                $this->Session->write("m", User::set_validator());
                $this->xredirect('/homes/forgot_password');
            }

        } else {
            $email_to = $data[0][0]['to_email'];
        }
        $email_to = explode(';', $email_to);
        //生成验证号
        $code = md5($data[0][0]['user_id'] . $data[0][0]['name'] . $data[0][0]['password']);
        $sections = parse_ini_file(CONF_PATH, TRUE, INI_SCANNER_RAW);
        $url = $_SERVER['HTTP_HOST'];
        $time = time();
        $url_code = base64_encode($name . '/' . $code . '/' . $time);
        $url = "http://{$url}/{$this->webroot}homes/check_and_reset_password/{$url_code}";


        $sql = "SELECT retrieve_password_from from mail_tmplate";
        $retrieve_password_from = $this->User->query($sql);

        if ($retrieve_password_from[0][0]['retrieve_password_from'] && strcmp(strtolower($retrieve_password_from[0][0]['retrieve_password_from']),'default')) {
            $email_info = $this->User->query("SELECT loginemail, smtp_host as smtphost, smtp_port as smtpport, username as username, password,name as name, email as from, secure as smtp_secure FROM mail_sender WHERE id = {$retrieve_password_from[0][0]['retrieve_password_from']}");
        } else {
            $email_info = $this->User->query('SELECT fromemail as "from", smtphost, smtpport,emailusername as username,loginemail, emailpassword as  "password", emailname as "name", smtp_secure,realm,workstation FROM system_parameter');
        }

        App::import('Vendor', 'nmail/phpmailer');
        $mailer = new phpmailer();
        if ($email_info[0][0]['loginemail'] === 'false') {
            $mailer->IsMail();
        } else {
            $mailer->IsSMTP();
        }
        $mailer->SMTPDebug = 2;
        $mailer->SMTPAuth = $email_info[0][0]['loginemail'] === 'false' ? false : true;
        switch ($email_info[0][0]['smtp_secure']) {
            case 1:
//                $mailer->SMTPSecure = 'tls';
                break;
            case 2:
                $mailer->SMTPSecure = 'ssl';
                break;
            case 3:
                $mailer->AuthType = 'NTLM';
                $mailer->Realm = $email_info[0][0]['realm'];
                $mailer->Workstation = $email_info[0][0]['workstation'];
        }

        $mailer->From = $email_info[0][0]['from'];
        $mailer->FromName = $email_info[0][0]['name'];
        $mailer->Host = $email_info[0][0]['smtphost'];
        $mailer->Port = intval($email_info[0][0]['smtpport']);
        $mailer->Username = $email_info[0][0]['username'];
        $mailer->Password = $email_info[0][0]['password'];
        $mailer->CharSet = "UTF-8";

        if (strpos($mailer->Host, 'denovolab.com') !== false) {
            $mailer->Helo = 'demo.denovolab.com';
        }

        //邮件模板
        $result = $this->User->query("select retrieve_password_from,retrieve_password_subject,retrieve_password_content from mail_tmplate");
        $username = $name;
        $content = $result[0][0]['retrieve_password_content'];

        $subject = $result[0][0]['retrieve_password_subject'];
        $replaced_arr = array('{client_name}', '{company_name}', '{username}', '{url}');
        $replace_arr = array(
            $data[0][0]['client_name'] ? : 'Not Applicable',
            $data[0][0]['company'] ? : 'Not Applicable',
            $username,
            $url
        );
        $content = str_replace($replaced_arr,$replace_arr,$content);
        foreach($email_to as $email){
            $mailer->AddAddress($email);
        }



        $mailer->ClearAttachments();

        $mailer->IsHTML(true);
        $mailer->Subject = $subject;
        $mailer->Body = $content;

        if ($mailer->Send()) {
            $current_datetime = date("Y-m-d H:i:s");
            $sql = "insert into retrieve_password_log (username,operation_time,email_addresses,status) values('$name','{$current_datetime}','{$email_to}', 2)";
            $this->User->query($sql);

            $this->User->create_json_array('', 201, __('The e-mail has been sent successfully, Please check your e-mail and reset your password.', true));
            $this->Session->write("m", User::set_validator());
            $this->xredirect('/homes/forgot_password');
        } else {
            $current_datetime = date("Y-m-d H:i:s");
            $sql = "insert into retrieve_password_log (username,operation_time,email_addresses,status) values('$name','{$email_to}','{$current_datetime}', 1)";
            $this->User->query($sql);

            $this->User->create_json_array('', 101, __('This e-mail has been sent to fail, Please check your e-mail is available.', true));
            $this->Session->write("m", User::set_validator());
            $this->xredirect('/homes/forgot_password');
        }

    }

    //验证并修改密码
    public function check_and_reset_password($url_code = null)
    {

        $url_code = trim($url_code);
        if (empty($url_code)) {
            $this->User->create_json_array('', 101, __('The password change failed, please try again.', true));
            $this->Session->write("m", User::set_validator());
            $this->xredirect("/homes/forgot_password");
        }

        $arr = explode('/', base64_decode($url_code));
        $name = $arr[0];
        $code = $arr[1];

        if (empty($name) || empty($code)) {
            $this->User->create_json_array('', 101, __('The password change failed, please try again.', true));
            $this->Session->write("m", User::set_validator());
            $this->xredirect("/homes/forgot_password");
        }

        $time = intval($arr[2]) + 60 * 60;
        $check_time = time();
        if ($check_time > $time) {
            $current_datetime = date("Y-m-d H:i:s");
            $sql = "update retrieve_password_log set modify_time = '{$current_datetime}' where id = (select id from retrieve_password_log where username = '{$name}' order by operation_time desc, id desc limit 1)";
            $this->User->query($sql);


            $this->User->create_json_array('', 101, __('The link has expired, please re-send the e-mail.', true));
            $this->Session->write("m", User::set_validator());
            $this->xredirect('/homes/forgot_password');
        }

        $sql = "select users.user_id,users.name,users.password,client.email as to_email from users left join client on users.user_id = client.user_id where users.name = '{$name}'";
        $data = $this->User->query($sql);


        $check_code = md5($data[0][0]['user_id'] . $data[0][0]['name'] . $data[0][0]['password']);

        if ($check_code !== $code) {
            $this->User->create_json_array('', 101, __('The password change failed, please try again.', true));
            $this->Session->write("m", User::set_validator());
            $this->xredirect('/homes/forgot_password');
        }

        if ($this->RequestHandler->isPost()) {
            $password = trim($_POST['password']);
            $repassword = trim($_POST['repassword']);

            if ((strlen($password) < 6) || ($password !== $repassword)) {
                $this->User->create_json_array('', 101, __('Password is entered incorrectly.', true));
                $this->Session->write("m", User::set_validator());
                $this->xredirect("/homes/check_and_reset_password/{$url_code}");
            } else {
                $password = md5($password);
                $sql = "update users set password='{$password}' where name = '{$name}' returning user_id";
                $result = $this->User->query($sql);
            }

            $msg = $name . "`s password changed successfully.";
            if ($result) {
                $current_datetime = date("Y-m-d H:i:s");
                $sql = "update retrieve_password_log set modify_time = '{$current_datetime}',status = 3 where id = (select id from retrieve_password_log where username = '{$name}' order by operation_time desc, id desc limit 1)";
                $this->User->query($sql);


                $this->User->create_json_array('', 201, $msg);
                $this->Session->write("m", User::set_validator());
                $this->xredirect("/homes/forgot_password");
            } else {
                $current_datetime = date("Y-m-d H:i:s");
                $sql = "update retrieve_password_log set modify_time = '{$current_datetime}',status = 1 where id = (select id from retrieve_password_log where username = '{$name}' order by operation_time desc, id desc limit 1)";
                $this->User->query($sql);


                $this->User->create_json_array('', 101, __('The password change failed, please try again.', true));
                $this->Session->write("m", User::set_validator());
                $this->xredirect("/homes/check_and_reset_password/{$url_code}");
            }

        }

        $this->layout = '';
        $this->set('url_code', $url_code);
    }


    public function ajax_change_password()
    {
        Configure::write('debug', 0);
        if (isset($_POST['old_pwd'])) {
            $this->autoLayout = false;
            $this->autoRender = false;
            $old_password = $_POST['old_pwd'];
            $new_password = $_POST['new_pwd'];
            $Retype_password = $_POST['re_pwd'];
            if (strcmp($new_password, $Retype_password)) {
                $return_arr = array(
                    'flg' => false,
                    'msg' => __('The two entries are inconsistent', true),
                );
                echo json_encode($return_arr);
                return;
            }
            $sst_pwd = $_SESSION['sst_password'];
            if (strcmp($sst_pwd, $old_password)) {
                $return_arr = array(
                    'flg' => false,
                    'msg' => __('The old password is error', true),
                );
                echo json_encode($return_arr);
                return;
            }
            $update_arr = array(
                'user_id' => $_SESSION['sst_user_id'],
                'password' => md5($Retype_password),
            );
            $flg = $this->User->save($update_arr);
            if ($flg === false) {
                $return_arr = array(
                    'flg' => false,
                    'msg' => __('update failed', true),
                );
                echo json_encode($return_arr);
            } else {
                $return_arr = array(
                    'flg' => true,
                    'msg' => __('update succeed', true),
                );
                echo json_encode($return_arr);
            }
        }
    }

    public function ajax_account_setting()
    {
        Configure::write('debug', 0);
        $this->autoLayout = false;
        $this->autoRender = false;
        $client = [];
        $client_id = $this->Session->read('sst_client_id');

        if ($client_id) {
            $sql = "SELECT email ,noc_email ,billing_email ,rate_email ,rate_delivery_email FROM client WHERE client_id = {$client_id};";
            $res = $this->Client->query($sql);
            $client = $res[0][0];
        }
        $this->set('client', $client);
        $this->render();
    }


    //保持主题配色
    function save_themer()
    {
        Configure::write('debug', 0);
        $this->autoLayout = false;
        $this->autoRender = false;

        $css = $_POST['css'];

        $file = WWW_ROOT . "css/themer.css";
        if (!is_writable($file)) {
            $this->User->create_json_array('', 101, "The css file[css/themer.css] is not writable");
        }

        file_put_contents($file, $css);


        $val = intval($_POST['val']);
        $this->User->query("update system_parameter set themer = $val ");


        $this->User->create_json_array('', 201, __('Succeed!', true));
        $data = User::set_validator();
        echo $data;
    }

    //qos_report
    public function qos_report()
    {
        unset($_SESSION['file_permission']);
        //$this->pageTitle = "Statistics/QoS Report";
        $this->beforeFilter1();
        $this->loadModel('Monitor');
        $sql = "select name, lan_ip, lan_port from voip_gateway";
        $limit_servers = $this->Monitor->query($sql);
        $this->set('limit_servers', $limit_servers);
//        $historys = $this->Monitor->get_history();
//        $this->set('historys', $historys);

        $this->render('globalstats');
    }

    public function landing_page()
    {
        $user_id = $_SESSION['sst_user_id'];
        if (empty($user_id))
            $this->redirect('login');
        $sql = "SELECT pri_url FROM sys_pri WHERE id = (SELECT default_mod FROM users WHERE user_id = {$user_id})";
        $url_info = $this->User->query($sql);
        if (empty($url_info[0][0]['pri_url'])) {
            #$this->redirect('/clients/index');
            $sql = "SELECT landing_page FROM system_parameter LIMIT 1";
            $landing_page = $this->User->query($sql);
            $landing_page = empty($landing_page) ? 0 : $landing_page;
            switch ($landing_page[0][0]['landing_page']) {
                case 0:
                    $url = "/homes/qos_report";
                    break;
                case 1:
                    $url = "/reports_db/summary";
                    break;
                case 2:
                    $url = "/reports_db/inout_report";
                    break;
                case 3:
                    $url = "/clients/index";
                    break;
            }
            $this->redirect($url);
        } else {
            $this->redirect('/' . $url_info[0][0]['pri_url']);
        }
    }

    //alert
    public function alert()
    {
        // redirect to qos_report
        $this->redirect('/homes/qos_report');

        $conditions = array();
        $is_view = $this->_get('viewed',0);
        if ($is_view != 2)
            $conditions['is_view'] = $is_view;

        if ($this->_get('type'))
            $conditions['type'] = $this->_get('type');

        $this->params['url']['viewed'] = $is_view;
        if ($this->isnotEmpty($this->params['url'], array('order_by'))) {
            $order_by = $this->params['url']['order_by'];
            $order_arr = explode('-', $order_by);
            if (count($order_arr) == 2) {
                $field = $order_arr[0];
                $sort = $order_arr[1];
                $order_arr = array($field => $sort);
                if ($field != 'create_time') {
                    $order_arr['create_time'] = 'desc';
                }
            }
        } else {
            $order_arr = array('create_time' => 'desc');
        }

        $temp = isset($_SESSION['paging_row']) ? $_SESSION['paging_row'] : 100;

        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = $temp : $pageSize = $_GET['size'];

        $_SESSION['paging_row'] = $pageSize;

        $this->loadModel('AdminAlert');

        $this->paginate = array(
            'fields' => array(
//                'AdminAlert.id', 'AdminAlert.create_time', 'AdminAlert.type', 'AdminAlert.subject','AdminAlert.is_view'
            ),
            'limit' => $pageSize,
            'order' => $order_arr,
            'conditions' => $conditions
        );

        $type_name = $this->AdminAlert->get_type_arr();


        $this->data = $this->paginate('AdminAlert');
        $this->set('type_name', $type_name);
//        pr($this->params['url']);die;
    }

    //自助页面view操作
    public function ajax_admin_alert_view($a_id)
    {
        Configure::write('debug', 0);
        $this->autoLayout = false;
        $this->autoRender = false;
//        $a_id = $_POST['a_id'];
        $this->loadModel('AdminAlert');

        $save_data = array(
            'view_time' => date('Y-m-d H:i:sO'),
            'view_by' => $this->Session->read('sst_user_name'),
            'id' => intval($a_id),
            'is_view' => 1
        );
        $this->AdminAlert->save($save_data);

        $sql = "select * from admin_alert where id= $a_id";
        $rst = $this->Client->query($sql);

        $type_name = $this->AdminAlert->get_type_arr();
        $data = $rst[0][0];
        $data['body'] = $rst[0][0]['content'];
        $data['title'] = $type_name[$rst[0][0]['type']];

        echo json_encode($data);


    }


    //


    //dashboard egress carriers, ingress carriers
    public function dashboard_trunk_carriers($type = "ingress")
    {
        $this->beforeFilter1();
        if (!in_array($type,array('ingress','egress')))
            $type = 'ingress';
//        $orders = ' order by alias asc ';
//        if ($this->isnotEmpty($this->params['url'], array('order_by'))) {
//            $order_by = $this->params['url']['order_by'];
//            $order_arr = explode('-', $order_by);
//            if (count($order_arr) == 2) {
//                $field = $order_arr[0];
//                $sort = $order_arr[1];
//                $orders = " order by $field $sort ";
//            }
//        }

        $filter = " and alias != '' ";
        if (isset($_GET['search']) && !empty($_GET['search'])) {
            $filter_alias = trim($_GET['search']);
            $filter .= " and alias like '%$filter_alias%' ";
        }



//        $sql = "select count(1) as totals from resource where egress = true and is_virtual is not true and active is true $filter ";
//
//        if ($type == "ingress")
//            $sql = "select count(1) as totals from resource where ingress = true and is_virtual is not true and active is true $filter ";


        $sql = <<<SQL
select count(1) as totals from (
select resource_id from qos_route_report WHERE report_time BETWEEN CURRENT_TIMESTAMP - interval '24 hours' AND CURRENT_TIMESTAMP group by resource_id
)as t1 inner join resource on resource.resource_id = t1.resource_id WHERE resource.$type = true and resource.is_virtual is not true
SQL;

        $res = $this->Client->query($sql);
        $count = $res[0][0]['totals'];

        //分页
        require_once 'MyPage.php';
        $page = new MyPage();
        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = 100 : $pageSize = $_GET['size'];
        $page->setTotalRecords($count); //总记录数
        $page->setCurrPage($currPage); //当前页
        $page->setPageSize($pageSize); //页大小


        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;


        $sql = <<<EOT
select t24.resource_id,t15.not_zero_calls as not_zero_calls_15,t1.not_zero_calls as not_zero_calls_1,t24.not_zero_calls as not_zero_calls_24,
t15.total_calls as total_calls_15,t1.total_calls as total_calls_1,t24.total_calls as total_calls_24,t15.busy_calls as busy_calls_15,t1.busy_calls as busy_calls_1,t24.busy_calls as busy_calls_24,
t15.cancel_calls as cancel_calls_15,t1.cancel_calls as cancel_calls_1,t24.cancel_calls as cancel_calls_24,
t15.bill_time as bill_time_15,t1.bill_time as bill_time_1,t24.bill_time as bill_time_24,
t15.pdd as pdd_15,t1.pdd as pdd_1,t24.pdd as pdd_24

from
(
	select resource_id,sum(not_zero_calls) as not_zero_calls, sum(total_calls) as total_calls, sum(busy_calls) as busy_calls,
	sum(cancel_calls) as cancel_calls, sum(bill_time) as bill_time,sum(pdd) as pdd FROM qos_route_report
	where report_time BETWEEN CURRENT_TIMESTAMP - interval '15 minutes' AND CURRENT_TIMESTAMP group by resource_id
) as t15 
full join
(
	select resource_id,sum(not_zero_calls) as not_zero_calls, sum(total_calls) as total_calls, sum(busy_calls) as busy_calls,
	sum(cancel_calls) as cancel_calls, sum(bill_time) as bill_time,sum(pdd) as pdd FROM qos_route_report
	where report_time BETWEEN CURRENT_TIMESTAMP - interval '1 hours' AND CURRENT_TIMESTAMP group by resource_id
) as t1 
 on t15.resource_id = t1.resource_id
full join
(
	select resource_id,sum(not_zero_calls) as not_zero_calls, sum(total_calls) as total_calls, sum(busy_calls) as busy_calls,
	sum(cancel_calls) as cancel_calls, sum(bill_time) as bill_time,sum(pdd) as pdd FROM qos_route_report
	 where report_time BETWEEN CURRENT_TIMESTAMP - interval '24 hours' AND CURRENT_TIMESTAMP group by resource_id
 ) as t24
 on t15.resource_id = t24.resource_id
 inner join resource on resource.resource_id = t24.resource_id WHERE resource.$type = true and resource.is_virtual is not true $filter
  order by t15.total_calls desc nulls last,t1.total_calls desc nulls last,t24.total_calls desc nulls last offset $offset limit $pageSize
EOT;

        $data = $this->Client->query($sql);
//        echo $sql ."<br />";
//die;

        $ready_data = array();
        foreach ($data as $item) {
            $bill_time = intval($item[0]['bill_time_15']);
            $not_zero_calls = intval($item[0]['not_zero_calls_15']);
            $total_calls = intval($item[0]['total_calls_15']);
            $busy_calls = intval($item[0]['busy_calls_15']);
            $cancel_calls = intval($item[0]['cancel_calls_15']);
            $pdd = $item[0]['pdd_15'];
            $asr_ = intval($busy_calls) + intval($cancel_calls) + intval($not_zero_calls);

            $ready_data[$item[0]['resource_id']]['acd_15'] = !empty($not_zero_calls) ? round($bill_time / $not_zero_calls, 2) : 0;
            $ready_data[$item[0]['resource_id']]['asr_15'] = !empty($not_zero_calls) ? round($not_zero_calls / $total_calls * 100, 2) : 0;
            $ready_data[$item[0]['resource_id']]['pdd_15'] = !empty($not_zero_calls) ? round($pdd / $not_zero_calls) : 0;
            $ready_data[$item[0]['resource_id']]['calls_15'] = $total_calls;


            $bill_time = intval($item[0]['bill_time_1']);
            $not_zero_calls = intval($item[0]['not_zero_calls_1']);
            $total_calls = intval($item[0]['total_calls_1']);
            $busy_calls = intval($item[0]['busy_calls_1']);
            $cancel_calls = intval($item[0]['cancel_calls_1']);
            $pdd = $item[0]['pdd_1'];
            $asr_ = intval($busy_calls) + intval($cancel_calls) + intval($not_zero_calls);

            $ready_data[$item[0]['resource_id']]['acd_1'] = !empty($not_zero_calls) ? round($bill_time / $not_zero_calls, 2) : 0;
            $ready_data[$item[0]['resource_id']]['asr_1'] = !empty($not_zero_calls) ? round($not_zero_calls / $total_calls * 100, 2) : 0;
            $ready_data[$item[0]['resource_id']]['pdd_1'] = !empty($not_zero_calls) ? round($pdd / $not_zero_calls) : 0;
            $ready_data[$item[0]['resource_id']]['calls_1'] = $total_calls;


            $bill_time = intval($item[0]['bill_time_24']);
            $not_zero_calls = intval($item[0]['not_zero_calls_24']);
            $total_calls = intval($item[0]['total_calls_24']);
            $busy_calls = intval($item[0]['busy_calls_24']);
            $cancel_calls = intval($item[0]['cancel_calls_24']);
            $pdd = $item[0]['pdd_24'];
            $asr_ = intval($busy_calls) + intval($cancel_calls) + intval($not_zero_calls);

            $ready_data[$item[0]['resource_id']]['acd_24'] = !empty($not_zero_calls) ? round($bill_time / $not_zero_calls, 2) : 0;
            $ready_data[$item[0]['resource_id']]['asr_24'] = !empty($not_zero_calls) ? round($not_zero_calls / $total_calls * 100, 2) : 0;
            $ready_data[$item[0]['resource_id']]['pdd_24'] = !empty($not_zero_calls) ? round($pdd / $not_zero_calls) : 0;
            $ready_data[$item[0]['resource_id']]['calls_24'] = $total_calls;
            $ready_data[$item[0]['resource_id']]['is_null'] = 1;
            foreach ($ready_data[$item[0]['resource_id']] as $data_value)
            {
                if ($data_value){
                    $ready_data[$item[0]['resource_id']]['is_null'] = 0;
                    break;
                }
            }
        }

//        pr($ready_data);die;
        $this->set('type', $type);
        $this->set('data', $ready_data);
        if ($type == "ingress")
            $this->set('resource_arr', $this->Client->findAll_ingress_id(false));
        else
            $this->set('resource_arr', $this->Client->findAll_egress_id(false));
        $this->set('p', $page);

        $this->render('dashboard_trunk_carriers');

        //后台删除超过24小时的qos数据
        $sql = "select report_time from qos_route_report order by report_time asc limit 1";
        $rst = $this->Client->query($sql);
        $min_report_time = $rst ? strtotime($rst[0][0]['report_time']) : 0;
        $curr_24 = strtotime(date('Y-m-d H:00:00')) - 24 * 60 * 60;
        if ($min_report_time && $min_report_time < $curr_24) {
            Configure::load('myconf');
            $php_path = Configure::read('php_exe_path');

            $cmd = "{$php_path} " . APP . "../cake/console/cake.php delete_qos_route_report $curr_24";
            shell_exec($cmd);
        }
    }

    public function dashboard_trunk_carriers_bak($type = "ingress")
    {
        $orders = ' order by alias asc ';
        if ($this->isnotEmpty($this->params['url'], array('order_by'))) {
            $order_by = $this->params['url']['order_by'];
            $order_arr = explode('-', $order_by);
            if (count($order_arr) == 2) {
                $field = $order_arr[0];
                $sort = $order_arr[1];
                $orders = " order by $field $sort ";
            }
        }

        $filter = " and alias != '' ";
        if (isset($_GET['search']) && !empty($_GET['search'])) {
            $filter_alias = trim($_GET['search']);
            $filter .= " and alias like '%$filter_alias%' ";
        }



        $sql = "select resource_id,alias from resource where egress = true and is_virtual is not true and active is true $filter ";

        if ($type == "ingress")
            $sql = "select resource.resource_id,resource.alias from resource where ingress = true and is_virtual is not true and active is true $filter ";

        if ($this->_get('not_zero',1))
        {
            $sql = <<<SQL
SELECT resource.resource_id,resource.alias from qos_route_report inner join resource on
qos_route_report.resource_id = resource.resource_id WHERE resource.egress = true and resource.is_virtual is not true and resource.active is true
$filter and qos_route_report.report_time BETWEEN CURRENT_TIMESTAMP - interval '24 hours' AND CURRENT_TIMESTAMP group by resource.resource_id order by resource.alias ASC
SQL;
            if ($type == "ingress")
            {
                $sql = <<<SQL
SELECT resource.resource_id,resource.alias from qos_route_report inner join resource on
qos_route_report.resource_id = resource.resource_id WHERE resource.ingress = true and resource.is_virtual is not true and resource.active is true
$filter and qos_route_report.report_time BETWEEN CURRENT_TIMESTAMP - interval '24 hours' AND CURRENT_TIMESTAMP group by resource.resource_id order by resource.alias ASC
SQL;
            }
        }

        $res = $this->Client->query($sql);
        $count = count($res);

        //分页
        require_once 'MyPage.php';
        $page = new MyPage();
        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = 100 : $pageSize = $_GET['size'];
        $page->setTotalRecords($count); //总记录数
        $page->setCurrPage($currPage); //当前页
        $page->setPageSize($pageSize); //页大小


        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;

        $resource_id_arr = array();
        $i = 1;
        foreach ($res as $index => $v) {
            if ($index < $offset)
                continue;
            if ($i > $pageSize)
                continue;
            $resource_id_arr[] = $v[0]['resource_id'];
            $resource_arr[$v[0]['resource_id']] = $v[0]['alias'];
            $i ++;
        }
        $resource_ids = implode(',',$resource_id_arr);

        $where = " and resource_id in ( $resource_ids ) ";


//        $count_sql = "  select resource_id
//                  FROM qos_route_report WHERE  report_time BETWEEN CURRENT_TIMESTAMP - interval '24 hours' AND CURRENT_TIMESTAMP $where group by resource_id";
//        $count = $this->Client->query($count_sql);


        $sql = <<<EOT
select t24.resource_id,t15.not_zero_calls as not_zero_calls_15,t1.not_zero_calls as not_zero_calls_1,t24.not_zero_calls as not_zero_calls_24,
t15.total_calls as total_calls_15,t1.total_calls as total_calls_1,t24.total_calls as total_calls_24,t15.busy_calls as busy_calls_15,t1.busy_calls as busy_calls_1,t24.busy_calls as busy_calls_24,
t15.cancel_calls as cancel_calls_15,t1.cancel_calls as cancel_calls_1,t24.cancel_calls as cancel_calls_24,
t15.bill_time as bill_time_15,t1.bill_time as bill_time_1,t24.bill_time as bill_time_24,
t15.pdd as pdd_15,t1.pdd as pdd_1,t24.pdd as pdd_24

from
(
	select resource_id,sum(not_zero_calls) as not_zero_calls, sum(total_calls) as total_calls, sum(busy_calls) as busy_calls,sum(cancel_calls) as cancel_calls, sum(bill_time) as bill_time,sum(pdd) as pdd FROM qos_route_report WHERE report_time BETWEEN CURRENT_TIMESTAMP - interval '15 minutes' AND CURRENT_TIMESTAMP $where group by resource_id
) as t15
full join
(
	select resource_id,sum(not_zero_calls) as not_zero_calls, sum(total_calls) as total_calls, sum(busy_calls) as busy_calls,sum(cancel_calls) as cancel_calls, sum(bill_time) as bill_time,sum(pdd) as pdd FROM qos_route_report WHERE report_time BETWEEN CURRENT_TIMESTAMP - interval '1 hours' AND CURRENT_TIMESTAMP $where group by resource_id
) as t1
 on t15.resource_id = t1.resource_id
full join
(
	select resource_id,sum(not_zero_calls) as not_zero_calls, sum(total_calls) as total_calls, sum(busy_calls) as busy_calls,sum(cancel_calls) as cancel_calls, sum(bill_time) as bill_time,sum(pdd) as pdd FROM qos_route_report WHERE report_time BETWEEN CURRENT_TIMESTAMP - interval '24 hours' AND CURRENT_TIMESTAMP $where group by resource_id
 ) as t24
 on t15.resource_id = t24.resource_id
EOT;

        $data = $this->Client->query($sql);


        $ready_data = array();
        foreach ($data as $item) {
            $bill_time = intval($item[0]['bill_time_15']);
            $not_zero_calls = intval($item[0]['not_zero_calls_15']);
            $total_calls = intval($item[0]['total_calls_15']);
            $busy_calls = intval($item[0]['busy_calls_15']);
            $cancel_calls = intval($item[0]['cancel_calls_15']);
            $pdd = $item[0]['pdd_15'];
            $asr_ = intval($busy_calls) + intval($cancel_calls) + intval($not_zero_calls);

            $ready_data[$item[0]['resource_id']]['acd_15'] = !empty($not_zero_calls) ? round($bill_time / $not_zero_calls, 2) : 0;
            $ready_data[$item[0]['resource_id']]['asr_15'] = !empty($asr_) ? round($not_zero_calls / $asr_ * 100, 2) : 0;
            $ready_data[$item[0]['resource_id']]['pdd_15'] = !empty($not_zero_calls) ? round($pdd / $not_zero_calls) : 0;
            $ready_data[$item[0]['resource_id']]['calls_15'] = $total_calls;


            $bill_time = intval($item[0]['bill_time_1']);
            $not_zero_calls = intval($item[0]['not_zero_calls_1']);
            $total_calls = intval($item[0]['total_calls_1']);
            $busy_calls = intval($item[0]['busy_calls_1']);
            $cancel_calls = intval($item[0]['cancel_calls_1']);
            $pdd = $item[0]['pdd_1'];
            $asr_ = intval($busy_calls) + intval($cancel_calls) + intval($not_zero_calls);

            $ready_data[$item[0]['resource_id']]['acd_1'] = !empty($not_zero_calls) ? round($bill_time / $not_zero_calls, 2) : 0;
            $ready_data[$item[0]['resource_id']]['asr_1'] = !empty($asr_) ? round($not_zero_calls / $asr_ * 100, 2) : 0;
            $ready_data[$item[0]['resource_id']]['pdd_1'] = !empty($not_zero_calls) ? round($pdd / $not_zero_calls) : 0;
            $ready_data[$item[0]['resource_id']]['calls_1'] = $total_calls;


            $bill_time = intval($item[0]['bill_time_24']);
            $not_zero_calls = intval($item[0]['not_zero_calls_24']);
            $total_calls = intval($item[0]['total_calls_24']);
            $busy_calls = intval($item[0]['busy_calls_24']);
            $cancel_calls = intval($item[0]['cancel_calls_24']);
            $pdd = $item[0]['pdd_24'];
            $asr_ = intval($busy_calls) + intval($cancel_calls) + intval($not_zero_calls);

            $ready_data[$item[0]['resource_id']]['acd_24'] = !empty($not_zero_calls) ? round($bill_time / $not_zero_calls, 2) : 0;
            $ready_data[$item[0]['resource_id']]['asr_24'] = !empty($asr_) ? round($not_zero_calls / $asr_ * 100, 2) : 0;
            $ready_data[$item[0]['resource_id']]['pdd_24'] = !empty($not_zero_calls) ? round($pdd / $not_zero_calls) : 0;
            $ready_data[$item[0]['resource_id']]['calls_24'] = $total_calls;
            $ready_data[$item[0]['resource_id']]['is_null'] = 1;
            foreach ($ready_data[$item[0]['resource_id']] as $data_value)
            {
                if ($data_value){
                    $ready_data[$item[0]['resource_id']]['is_null'] = 0;
                    break;
                }
            }
        }


        $this->set('data', $ready_data);
        $this->set('resource_arr', $resource_arr);
        $this->set('p', $page);

        $this->render('dashboard_trunk_carriers');

        //后台删除超过24小时的qos数据
        $sql = "select report_time from qos_route_report order by report_time asc limit 1";
        $rst = $this->Client->query($sql);
        $min_report_time = $rst ? strtotime($rst[0][0]['report_time']) : 0;
        $curr_24 = strtotime(date('Y-m-d H:00:00')) - 24 * 60 * 60;
        if ($min_report_time && $min_report_time < $curr_24) {
            Configure::load('myconf');
            $php_path = Configure::read('php_exe_path');

            $cmd = "{$php_path} " . APP . "../cake/console/cake.php delete_qos_route_report $curr_24";
            shell_exec($cmd);
        }
    }

    //生成i18n, $path相对于web的path app-controllers-homes_controller@php
    public function generate_i18n($path = '')
    {
        set_time_limit(45);

        $path_def_arr = array();
        if (empty($path)) {
            $path_def_arr[] = ROOT . DS . APP_DIR . DS . 'controllers';
            $path_def_arr[] = ROOT . DS . APP_DIR . DS . 'models';
            $path_def_arr[] = ROOT . DS . APP_DIR . DS . 'views';
            $path_def_arr[] = ROOT . DS . APP_DIR . DS . 'plugins';
        }
        $path = str_replace('@', '.', $path);
        $path_arr = explode('-', $path);
        $path = ROOT . '/' . implode('/', $path_arr);
        if (!file_exists($path)) {
            echo '目录或文件不存在';
            exit;
        }

        $lan_path = ROOT . DS . APP_DIR . DS . 'locale/eng/LC_MESSAGES/default.po';
        $lan_exist_path = ROOT . DS . APP_DIR . DS . 'locale/eng/LC_MESSAGES/default_exist.po';

        if (!is_writable($lan_path)) {
            echo 'default.po 文件不可写';
            exit;
        }

        if (!is_writable($lan_exist_path)) {
            echo 'default_exist.po 文件不可写';
            exit;
        }

        file_put_contents($lan_exist_path, "");

        $lan_handle = fopen($lan_path, 'a+');
        $lan_exist_handle = fopen($lan_exist_path, 'a+');

        if (!class_exists('I18n')) {
            App::import('Core', 'i18n');
        }
        $i18n = I18n::getInstance();
        $i18n->__bindTextDomain('default');
        $lan_arr = $i18n->__domains['LC_MESSAGES']['']['default'];
        //pr($lan_arr);exit;

        $file_arr = array();
        if (!empty($path_def_arr)) {
            foreach ($path_def_arr as $path_def) {
                searchDir($path_def, $file_arr);
            }
        } else {
            if (is_dir($path)) {

                searchDir($path, $file_arr);
            } else {
                $file_arr[] = $path;
            }
        }


        $is_generate = false;
        $generate_path = strstr($path,'web/app');
        if(!empty($path_def_arr)){
            $b = array_map(function(&$n){ return strstr($n,'web/app');}, $path_def_arr);
            $generate_path = implode("; ",$b);
        }

        pr("generate path: $generate_path");
        pr("file count: " . count($file_arr));
        ob_end_flush();
        $i = 1;
        $str_lan = '';
        $str_lan_exist = '';

        foreach ($file_arr as $file) {
            //过滤文件

            if(strpos($file,".php.orig") !== FALSE || strpos($file,".ctp.orig") !== FALSE || strpos($file,".bak") !== FALSE)
                continue;
            pr(strstr($file,'web/app'));


//            echo strstr('web/app',$file) . "<br />";
            $match_arr = array();
            $file_string = file_get_contents($file);

            $search = array(
                /*"'<script[^>]*?>.*?</script>'si",   // 去掉 javascript*/
                /*"'<style[^>]*?>.*?</style>'si",   // 去掉 css*/
                /*"'<[/!]*?[^<>]*?>'si",           // 去掉 HTML 标记*/

                "'<!--.*?-->'si",           // 去掉 html注释 标记
                "'\/\*.*?\*\/'si",           // 去掉 js,php注释 标记
                "'\/\/.*'i",           // 去掉 js,php注释
                /*"'([rn])[s]+'",                 // 去掉空白字符*/
                /*"'&(quot|#34);'i",                 // 替换 HTML 实体*/

                /*"'&(amp|#38);'i",
                "'&(lt|#60);'i",
                "'&(gt|#62);'i",
                "'&(nbsp|#160);'i",
                "'&(iexcl|#161);'i",
                "'&(cent|#162);'i",
                "'&(pound|#163);'i",
                "'&(copy|#169);'i",
                "'&#(d+);'e"*/


            );                    // 作为 PHP 代码运行

            $replace = array(
//                "",
//                "",
//                "",
                "",
                "",
                "",
                /*"\1",
                "\"",
                "&",
                "<",
                ">",
                " ",
                chr(161),
                chr(162),
                chr(163),
                chr(169),
                "chr(\1)"*/
            );



//            $file_string = preg_replace($search, $replace, $file_string);
            $file_string = preg_replace_callback($search,function($mat){return str_repeat("\n",substr_count($mat[0],"\n"));},$file_string);
            $file_string_arr = explode("\n",$file_string);

            $is_to_lan = false;
            $is_to_exist = false;
            foreach($file_string_arr as $fk => $fv){
                preg_match_all("/__\([ ]*?(['\"])(.+?)\\1/x", $fv, $match_arr);
                $no = $fk + 1;
                if(!empty($match_arr[2])){

                    pr($match_arr[0]);
                    flush();


                    foreach ($match_arr[2] as $item) {
                        if (!array_key_exists($item, $lan_arr)) {
                            //不存在写入
                            if (!$is_generate) {
                                //file_put_contents($lan_path,"\n\n#    $file\n");
//                        fwrite($lan_handle, "\n\n\n\n#<<generate date: ".date("Y-m-d H:i:s").", generate path: $generate_path >>\n\n");
                                $str_lan .= "\n\n\n\n#<<generate date: ".date("Y-m-d H:i:s").", generate path: $generate_path >>\n\n";
                                $is_generate = true;
                            }

                            if (!$is_to_lan) {
                                //file_put_contents($lan_path,"\n\n#    $file\n");
//                        fwrite($lan_handle, "\n\n#    $file\n");
                                $str_lan .= "\n\n#    $file\n";
                                $is_to_lan = true;
                            }

                            //file_put_contents($lan_path,"msgid \"$item\"\nmsgstr \"$item\"\n");
//                    fwrite($lan_handle, "msgid \"$item\"\nmsgstr \"$item\"\n");
                            $str_lan .= "#$no\nmsgid \"$item\"\nmsgstr \"$item\"\n";
                            //$i18n->__bindTextDomain('default');
                            $lan_arr[$item] = $item;

                        } else {
                            //已经存在，判断是否正确
                            if (!$is_to_exist) {
                                //file_put_contents($lan_path,"\n\n#    $file\n");
                                //fwrite($lan_exist_handle, "\n\n#    $file\n");
                                $str_lan_exist .= "\n\n#    $file\n";
                                $is_to_exist = true;
                            }

                            //file_put_contents($lan_path,"msgid \"$item\"\nmsgstr \"$item\"\n");
                            //fwrite($lan_exist_handle, "msgid \"$item\"\nmsgstr \"$item\"\n");
                            $str_lan_exist .= "#$no\nmsgid \"$item\"\nmsgstr \"$item\"\n";
                        }
                    }

                    if($i == 100){
                        fwrite($lan_handle,$str_lan);
                        fwrite($lan_exist_handle,$str_lan_exist);
                        $str_lan = '';
                        $str_lan_exist = '';
                        $i = 1;
                    }
                    $i++;
                }

                continue;
            }




//            preg_match_all("/__\([ ]*?['\"](.+?)['\"][ ,true]*?\)/x", $file_string, $match_arr);
//            preg_match_all("/__\([ ]*?(['\"])(.+?)\\1/x", $file_string, $match_arr);




        }
        fwrite($lan_handle,$str_lan);
        fwrite($lan_exist_handle,$str_lan_exist);

        if($is_generate){
            fwrite($lan_handle, "\n\n#<<// generate end date: ".date("Y-m-d H:i:s").", generate path: $generate_path //>>\n\n");
        }

        fclose($lan_handle);
        fclose($lan_exist_handle);


        echo 'Ok';
        exit;

    }

    public function get_support() {
        Configure::load('myconf');
        Configure::write('debug',0);
        $this->autoLayout = false;
        $this->autoRender = false;
        if (!empty($this->params['form']))
        {
            $email_info = $this->Mailtmp->query('SELECT fromemail as "from", smtphost, smtpport,emailusername as username,loginemail, emailpassword as  "password", emailname as "name", smtp_secure,realm,workstation FROM system_parameter');
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
            $mailer->From = $email_info[0][0]['from'];
            $mailer->FromName = $email_info[0][0]['name'];
            $mailer->Host = $email_info[0][0]['smtphost'];
            $mailer->Port = intval($email_info[0][0]['smtpport']);
            $mailer->Username = $email_info[0][0]['username'];
            $mailer->Password = $email_info[0][0]['password'];

            if (strpos($mailer->Host, 'denovolab.com') !== false) {
                $mailer->Helo = 'demo.denovolab.com';
            }

            $mailer->CharSet = "UTF-8";
            if ($this->Session->read('login_type') == 1) {
                $send_address = empty(trim(Configure::read('support_email.to_email'))) ? 'support@denovolab.com' : trim(Configure::read('support_email.to_email'));
                $user = Configure::read('client.name');
            } else {
                $sys_noc_mail = $this->Mailtmp->query("select noc_email from system_parameter limit 1");
                $send_address = isset($sys_noc_mail[0][0]['noc_email']) ? $sys_noc_mail[0][0]['noc_email'] : 'support@denovolab.com';
                $user = $this->Session->read('carrier_panel.Client.name');
            }
            $mailer->AddAddress($send_address);

            $url = $this->params['form']['curr_url'];//"http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

            $IP = GetHostByName($_SERVER['SERVER_NAME']);
            $browser =  $_SERVER['HTTP_USER_AGENT'];
            $content = <<<EOT
<body bgcolor="#FFFFFF" text="#000000"><div style="background-color:#F7F9FA;width: 1024px; height: 600px;       margin: 0 auto 0;"><div style="height: 40px;">&nbsp;</div><div style="background-color:#ffffff;width: 600px; margin: 0 auto         0;"><div style="background-color:#51A351;width: 600px;height: 80px;"><div style="height: 10px;">&nbsp;</div><h2 style=text-align:center><strong><span style="color:white; font-size:22px">Get Support&nbsp;for DENOVOLAB Switch</span></strong></h2></div><div style="margin:0 30px 0 30px;word-wrap: break-word;"><div style="height: 30px;">&nbsp;</div><div><span style=font-size:16px><strong>Url :</strong>{url}</span></div><div><span style=font-size:16px><strong>Server&nbsp;IP :</strong>{IP}</span></div><div><span style=font-size:16px><strong>Client Identity</strong></span><span style=font-size:16px><strong>:</strong>{user}</span></div><div><span style=font-size:16px><strong>Client Browser :</strong>{browser}</span></div><div>&nbsp;</div><div><span style=font-size:16px><strong>Issue:</strong></span></div><blockquote><div><span style=font-size:16px>&nbsp; &nbsp; &nbsp;                &nbsp; {content}</span></div></blockquote><div style="height: 60px;">&nbsp;</div></div><div style="margin: 0 30px 0 30px;"><div style="height: 15px;">&nbsp;</div></div></div></div>
EOT;

            $content = str_replace(array('{url}', '{user}', '{IP}', '{browser}','{content}'),
                array($url,$user,$IP,$browser,$this->params['form']['support_content']),$content);
            $mailer->Subject = $this->params['form']['support_subject'];
            $mailer->Body = $content;//$this->params['form']['support_content'];
            //$mailer->Send();

            if ($mailer->Send()){
                $return_arr = array('flg' => 1,'msg' => 'Successfully');
                $current_datetime = date("Y-m-d H:i:s");
                $sql = "insert into email_log (send_time, client_id, email_addresses, type, status) values('{$current_datetime}', -1, '{$send_address}', 32, 0)";
                $data = $this->Mailtmp->query($sql);
            }

            else
            {
                $error_info = strval($mailer->ErrorInfo);
                $return_arr = array('flg' => 0,'msg' => $error_info);
                $current_datetime = date("Y-m-d H:i:s");
                $sql = "insert into email_log (send_time, client_id, email_addresses, type, status, error) values('{$current_datetime}', -1, '{$send_address}', 32, 1, '{$error_info}')";
                $data = $this->Mailtmp->query($sql);
            }
            echo json_encode($return_arr);

        }
    }



    public function get_support_api()
    {
        Configure::write('debug','0');
        $this->autoLayout = false;
        $this->autoRender = false;
        if (!empty($this->params['form']) || $_GET['ok'])
        {
            if ($_GET['ok']){
                $this->params['form']['support_subject'] = 'Thundercats are GO!!!';
                $this->params['form']['support_content'] = 'Sword of Omens, give me sight BEYOND sight';
            }
            Configure::load('myconf');
            if ($this->Session->read('login_type') == 1) {
                $send_address = trim(Configure::read('support_email.to_email'));
                $user = Configure::read('client.name');
            } else {
                $sys_noc_mail = $this->Mailtmp->query("select noc_email from system_parameter limit 1");
                $send_address = $sys_noc_mail[0][0]['noc_email'];
                $user = $this->Session->read('carrier_panel.Client.name');
            }
            $url = $this->params['form']['curr_url'];//"http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

            $IP = GetHostByName($_SERVER['SERVER_NAME']);
            $browser =  $_SERVER['HTTP_USER_AGENT'];
            $content = <<<EOT
<body bgcolor="#FFFFFF" text="#000000"><div style="background-color:#F7F9FA;width: 1024px; height: 600px;       margin: 0 auto 0;"><div style="height: 40px;">&nbsp;</div><div style="background-color:#ffffff;width: 600px; margin: 0 auto         0;"><div style="background-color:#51A351;width: 600px;height: 80px;"><div style="height: 10px;">&nbsp;</div><h2 style=text-align:center><strong><span style="color:white; font-size:22px">Get Support&nbsp;for DENOVOLAB Switch</span></strong></h2></div><div style="margin:0 30px 0 30px;word-wrap: break-word;"><div style="height: 30px;">&nbsp;</div><div><span style=font-size:16px><strong>Url :</strong>{url}</span></div><div><span style=font-size:16px><strong>Server&nbsp;IP :</strong>{IP}</span></div><div><span style=font-size:16px><strong>Client Identity</strong></span><span style=font-size:16px><strong>:</strong>{user}</span></div><div><span style=font-size:16px><strong>Client Browser :</strong>{browser}</span></div><div>&nbsp;</div><div><span style=font-size:16px><strong>Issue:</strong></span></div><blockquote><div><span style=font-size:16px>&nbsp; &nbsp; &nbsp;                &nbsp; {content}</span></div></blockquote><div style="height: 60px;">&nbsp;</div></div><div style="margin: 0 30px 0 30px;"><div style="height: 15px;">&nbsp;</div></div></div></div>
EOT;

            $content = str_replace(array('{url}', '{user}', '{IP}', '{browser}','{content}'),
                array($url,$user,$IP,$browser,$this->params['form']['support_content']),$content);

            $subject = $this->params['form']['support_subject'];
            $url = Configure::read('support_email.api');
            $api_key = Configure::read('support_email.api_key');
            $send_address = 'hzjkb24@163.com';
            $data =
                array(
                    'content' => array(
                        'from' => "sandbox@sparkpostbox.com",
                        'subject' => $subject,
                        'text' => 'content',
                    ),
                    'recipients' => array(
                        array(
                            'address' => $send_address
                        ),
                    ),
                );
//            echo json_encode($data);die;
            $header = array(
                'Authorization: '.$api_key,
                'Content-Type: application/json',
            );

            $crl = curl_init();
            curl_setopt($crl, CURLOPT_HTTPHEADER,$header);
            curl_setopt($crl, CURLOPT_URL, $url);
            curl_setopt($crl, CURLOPT_POST, true);
            curl_setopt($crl, CURLOPT_POSTFIELDS, $data);
            curl_setopt($crl, CURLOPT_RETURNTRANSFER, 1);
            $handles = curl_exec($crl);
            curl_close($crl);
            var_dump($handles);die;
            echo json_decode($handles);
        }

    }

    public function get_support_bak()
    {
        Configure::write('debug','0');
        $this->autoLayout = false;
        $this->autoRender = false;
        if (!empty($this->params['form'])) {
            App::import('Vendor', 'nmail/phpmailer');
            $mailer = new phpmailer();
            Configure::load('myconf');
            $mailer->IsSMTP();
            $mailer->SMTPAuth = false;
//            $mailer->SMTPDebug  = 2;
            $mailer->Debugoutput = 'html';

            $mailer->From = 'hzjkb24@gmail.com';
            $mailer->FromName = 'hzjkb24';
            $mailer->Host = 'smtp.gmail.com';
            $mailer->Port = 587;
            $mailer->Username = 'hzjkb24@gmail.com';
            $mailer->Password = '520966Hzj';
            $mailer->CharSet = "UTF-8";

            $mailer->SMTPSecure = 'tls';
            $mailer->IsHTML(true);
//            switch (Configure::read('support_email.smtp_secure'))
//            {
//                case 'tls':
//                    $mailer->SMTPSecure = 'tls';
//                    break;
//                case 'ssl':
//                    $mailer->SMTPSecure = 'ssl';
//                    break;
//            }
            //$mailer->SMTPSecure = 'tls';
            // $mailer->SMTPSecure = 'ssl';

            if ($this->Session->read('login_type') == 1) {
                $send_address = trim(Configure::read('support_email.to_email'));
                $user = Configure::read('client.name');
            } else {
                $sys_noc_mail = $this->Mailtmp->query("select noc_email from system_parameter limit 1");
                $send_address = $sys_noc_mail[0][0]['noc_email'];
                $user = $this->Session->read('carrier_panel.Client.name');
            }
            $mailer->AddAddress($send_address);

            $url = $this->params['form']['curr_url'];//"http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

            $IP = GetHostByName($_SERVER['SERVER_NAME']);
            $browser = $_SERVER['HTTP_USER_AGENT'];
            $content = <<<EOT
<body bgcolor="#FFFFFF" text="#000000"><div style="background-color:#F7F9FA;width: 1024px; height: 600px;       margin: 0 auto 0;"><div style="height: 40px;">&nbsp;</div><div style="background-color:#ffffff;width: 600px; margin: 0 auto         0;"><div style="background-color:#51A351;width: 600px;height: 80px;"><div style="height: 10px;">&nbsp;</div><h2 style=text-align:center><strong><span style="color:white; font-size:22px">Get Support&nbsp;for DENOVOLAB Switch</span></strong></h2></div><div style="margin:0 30px 0 30px;word-wrap: break-word;"><div style="height: 30px;">&nbsp;</div><div><span style=font-size:16px><strong>Url :</strong>{url}</span></div><div><span style=font-size:16px><strong>Server&nbsp;IP :</strong>{IP}</span></div><div><span style=font-size:16px><strong>Client Identity</strong></span><span style=font-size:16px><strong>:</strong>{user}</span></div><div><span style=font-size:16px><strong>Client Browser :</strong>{browser}</span></div><div>&nbsp;</div><div><span style=font-size:16px><strong>Issue:</strong></span></div><blockquote><div><span style=font-size:16px>&nbsp; &nbsp; &nbsp;                &nbsp; {content}</span></div></blockquote><div style="height: 60px;">&nbsp;</div></div><div style="margin: 0 30px 0 30px;"><div style="height: 15px;">&nbsp;</div></div></div></div>
EOT;

            $content = str_replace(array('{url}', '{user}', '{IP}', '{browser}','{content}'),
                array($url,$user,$IP,$browser,$this->params['form']['support_content']),$content);
            $mailer->Subject = $this->params['form']['support_subject'];
            $mailer->Body = $content;//$this->params['form']['support_content'];
            //$mailer->Send();

            if ($mailer->Send()){
                $return_arr = array('flg' => 1,'msg' => 'Successfully');
                $current_datetime = date("Y-m-d H:i:s");
                $sql = "insert into email_log (send_time, client_id, email_addresses, type, status) values('{$current_datetime}', -1, '{$send_address}', 32, 0)";
                //$data = $this->Mailtmp->query($sql);
            } else {
                $error_info = strval($mailer->ErrorInfo);
                $return_arr = array('flg' => 0,'msg' => $error_info);
                $current_datetime = date("Y-m-d H:i:s");
                $sql = "insert into email_log (send_time, client_id, email_addresses, type, status, error) values('{$current_datetime}', -1, '{$send_address}', 32, 1, '{$error_info}')";
                //$data = $this->Mailtmp->query($sql);
            }
            echo json_encode($return_arr);
        }

    }

    private function email_settings($email_info){
        if($email_info['from'] && $email_info['smtphost'] && $email_info['username'] && $email_info['password']){
            return true;
        }
        return false;
    }

    public function cdr_from_invoice($invoiceId) {
        $this->loadModel('Invoice');

        if (!$invoiceId) {
            $this->Session->write('m', $this->Invoice->create_json(101, 'Invoice ID is not provided'));
            $this->redirect('login');
        }

        $this->layout = false;
        $this->autoRender = false;
        Configure::load('myconf');
        Configure::write("debug", 2);


        $invoice = $this->Invoice->find('first', array('conditions' => array('invoice_id' => $invoiceId)));

        if (!$invoice) {
            $this->Session->write('m', $this->Invoice->create_json(101, 'Invoice does not exists'));
            $this->redirect('login');
        }

        App::import('Vendor', 'CallDetailReport', array('file' => 'api/cdr/class.cdr.php'));

        $requestData = array(
            'start_time' => strtotime($invoice['Invoice']['invoice_start']),
            'end_time' => strtotime($invoice['Invoice']['invoice_end']),
        );

        $callDetailReport = new CallDetailReport();
        $res = $callDetailReport->process($requestData);

        if ($res) {
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename=cdr.csv');
            header('Content-Length: ' . strlen($res));
            header('Pragma: no-cache');
            ob_clean();
            echo $res;
            exit;
        } else {
            $this->Session->write('m', $this->Invoice->create_json(101, 'Could not get cdr file'));
            $this->redirect('login');
        }
    }

}