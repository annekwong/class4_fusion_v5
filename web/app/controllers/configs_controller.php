
<?php

class ConfigsController extends AppController
{

    var $name = 'Configs';
    var $helpers = array('javascript', 'html', 'AppClients', 'Common');
    var $components = array('RequestHandler');
    var $uses = array('Clients', 'Systemparam');

    //读取该模块的执行和修改权限
    public function beforeFilter()
    {
        parent::beforeFilter();
    }

    public function index()
    {
        if ($_SESSION['login_type'] != 1)
        {
            $this->redirect('/clients/carrier/');
        }
        $sections = parse_ini_file(CONF_PATH, TRUE, INI_SCANNER_RAW);
        $web_switch_ip = !empty($sections['web_switch']) ? $sections['web_switch']['event_ip'] : '';
        $web_switch_port = !empty($sections['web_switch']) ? $sections['web_switch']['event_port'] : '';
        $web_sip_capture_ip = !empty($sections['web_sip_capture']) ? $sections['web_sip_capture']['ip'] : '';
        $web_sip_capture_port = !empty($sections['web_sip_capture']) ? $sections['web_sip_capture']['port'] : '';

        $redis_ip = !empty($sections['redis']) ? $sections['redis']['ip'] : '';
        $redis_port = !empty($sections['redis']) ? $sections['redis']['port'] : '';

        $sips = $this->Clients->query(" select * from switch_profile where sip_capture_ip != '' and sip_capture_ip is not null ");
        $sipss = $this->Clients->query(" select * from switch_profile where lan_ip != '' and lan_port is not null ");

        //var_dump($sipss);

        $this->set('web_switch_ip', $web_switch_ip);
        $this->set('web_switch_port', $web_switch_port);
        $this->set('sips', $sips);
        $this->set('sipss', $sipss);
        $this->set('redis_ip', $redis_ip);
        $this->set('redis_port', $redis_port);

        $path = Configure::read('script.path');
        $files = scandir($path);
        $ok_file = array();
        foreach ($files as $file)
        {
            if (!empty($file) && (preg_match("/\.pl$/", $file) || preg_match("/\.sh$/", $file) || preg_match("/\.py$/", $file)))
            {

                $var_array = array('file' => $file, 'msg' => '');
                $file_path = $path . DS . $file;
                $fileperms = substr(base_convert(@fileperms($file_path), 10, 8), -3);

                if ($fileperms == '777')
                {
                    $var_array['msg'] = "OK";
                }
                else
                {
                    $var_array['msg'] = "$fileperms, you must set it to 777 ";
                }
                $ok_file[] = $var_array;
            }
        }
        $this->set('files', $ok_file);
    }

    function change_config()
    {
        Configure::write('debug', 0);
        $this->autoLayout = FALSE;
        $this->autoRender = FALSE;

        $data = array('status' => 'no', 'msg' => '', 'data' => '');

        $tou_arr = array(
            'web_switch' => array('event_ip', 'event_port'),
            'web_sip_capture' => array('ip', 'port'),
            'redis' => array('ip', 'port')
        );

        $tou = $_POST['tou'];
        $type = $_POST['type'];
        $value = addslashes($_POST['value']);
        $sections = parse_ini_file(CONF_PATH, TRUE, INI_SCANNER_RAW);

        if (key_exists($tou, $tou_arr))
        {

            if ($type == 'ip')
            {
                $sections[$tou][$tou_arr[$tou][0]] = $value;
            }
            else if ($type == 'port')
            {
                $sections[$tou][$tou_arr[$tou][1]] = $value;
            }

            $result = $this->write_ini_file($sections, CONF_PATH, true);
            if ($result)
            {
                $data['status'] = 'yes';
                $data['msg'] = __('The setting of Web is modified successfully!', true);
            }
            else
            {
                $data['msg'] = __('Insufficient permissions configuration file!', true);
            }
        }
        echo json_encode($data);
    }

    function write_ini_file($assoc_arr, $path, $has_sections = FALSE)
    {
        $content = "";
        if ($has_sections)
        {
            foreach ($assoc_arr as $key => $elem)
            {
                $content .= "[" . $key . "]\n";
                foreach ($elem as $key2 => $elem2)
                {
                    if (is_array($elem2))
                    {
                        for ($i = 0; $i < count($elem2); $i++)
                        {
                            //$content .= $key2."[] = \"".$elem2[$i]."\"\n"; 
                            $content .= $key2 . "[]=" . $elem2[$i] . "\n";
                        }
                    }
                    else if ($elem2 == "")
                    {
                        $content .= $key2 . " = \n";
                        //else $content .= $key2." = \"".$elem2."\"\n"; 
                    }
                    else
                    {


                        if ($key2 == 'cdr_head' || $key2 == 'cdr_alias')
                        {
                            $content .= $key2 . " = \"" . $elem2 . "\"\n";
                        }
                        else
                        {
                            $content .= $key2 . "=" . $elem2 . "\n";
                        }
                    }
                }
            }
        }

        // var_dump($content);exit;

        if (!$handle = @fopen($path, 'w'))
        {
            //$this->Session->write('m', $this->Systemparam->create_json(101, ''));
            return false;
        }
        if (!fwrite($handle, $content))
        {
            return false;
        }
        fclose($handle);
        return true;
    }

    function change_pri()
    {

        exit;
        Configure::write('debug', 0);
        $this->autoLayout = FALSE;
        $this->autoRender = FALSE;

        $data = array('status' => 'no', 'msg' => '');
        $file_name = $_POST['value'];
        $sections = parse_ini_file(CONF_PATH, TRUE, INI_SCANNER_RAW);

        $path = Configure::read('script.path');
        $files = scandir($path);
        $ok_file = array();
        foreach ($files as $file)
        {
            if (!empty($file) && (preg_match("/\.pl$/", $file) || preg_match("/\.sh$/", $file) || preg_match("/\.py$/", $file)))
            {
                $ok_file[] = $file;
            }
        }


        if (in_array($file_name, $ok_file))
        {
            $file = $path . DS . $file_name;
            $res = shell_exec(" chmod -R 777  {$file} ");

            $data['status'] = 'yes';
            $data['msg'] = " chmod -R 777  {$file} ";
        }
        else
        {
            $data['msg'] = "";
        }


        echo json_encode($data);
    }

    function test_config()
    {
        Configure::write('debug', 0);
        $this->autoLayout = FALSE;
        $this->autoRender = FALSE;
        $data = array('status' => 'no');
        $type = $_POST['type'];
        $sections = parse_ini_file(CONF_PATH, TRUE, INI_SCANNER_RAW);
        $path = Configure::read('script.path');

        //var_dump($type);
        switch ($type)
        {
            case 'web_switch':
                $data = $this->_test_qos_server($type);
                break;
            case 'web_sip_capture':
                $data = $this->_test_qos_server($type);
                break;
            case 'redis':
                $data = $this->_test_qos_server($type);
                break;
            case 3:
                echo $this->upload->example_international;
                break;
            default :
                $file = $path . DS . $type;
                $data = $this->_test_script_file($file);
                break;
        }
        echo json_encode($data);
    }

    function test_config_sip()
    {
        Configure::write('debug', 0);
        $this->autoLayout = FALSE;
        $this->autoRender = FALSE;
        $data = array('status' => 'no');
        $id = $_POST['id'];
        $id = addslashes($id);
        $data = $this->_test_qos_server('web_sip_capture', $id);
        echo json_encode($data);
    }

    function test_config_lan()
    {
        Configure::write('debug', 0);
        $this->autoLayout = FALSE;
        $this->autoRender = FALSE;
        $data = array('status' => 'no');
        $id = $_POST['id'];
        $id = addslashes($id);
        $data = $this->_test_qos_server('web_lan', $id);
        echo json_encode($data);
    }

    function _test_script_file($file)
    {

        $fileperms = substr(base_convert(@fileperms($file), 10, 8), -3);

        if ($fileperms == '777')
        {
            $data['status'] = "yes";
        }
        else
        {
            $data['msg'] = "$fileperms, you must set it to 777 ";
        }

        return $data;
    }

    function _test_qos_server($type, $id = 0)
    {
        $data = array('status' => 'no', 'msg' => '');

        $tou_arr = array(
            'web_switch' => array('event_ip', 'event_port'),
            'web_sip_capture' => array('ip', 'port'),
            'redis' => array('ip', 'port')
        );

        $sections = parse_ini_file(CONF_PATH, TRUE, INI_SCANNER_RAW);

        $tcp_upd = 'tcp';
        if ($type == 'web_switch')
        {
            $ip = $sections['web_switch']['event_ip'];
            $port = $sections['web_switch']['event_port'];
        }
        else if ($type == 'web_sip_capture')
        {
            $sip = $this->Clients->query("select * from switch_profile where id = {$id} ");
            $ip = $sip[0][0]['sip_capture_ip'];
            $port = $sip[0][0]['sip_capture_port'];
            $tcp_upd = 'udp';
        }
        else if ($type == 'web_lan')
        {
            $sip = $this->Clients->query("select * from switch_profile where id = {$id} ");
            $ip = $sip[0][0]['lan_ip'];
            $port = $sip[0][0]['lan_port'];
            $tcp_upd = 'tcp';
        }
        else if ($type == 'redis')
        {
            $ip = $sections['redis']['ip'];
            $port = $sections['redis']['port'];
        }

        $res = $this->_socket_test($ip, $port, $tcp_upd);

        if ($res)
        {
            $data['status'] = "yes";
        }
        else
        {
            if ($tcp_upd == 'tcp' && $type != 'web_lan')
            {
                $data['msg'] = "Connection to " . $ip . "/" . $port . " is failed, you can update it in this page.";
            }
            else
            {
                $data['msg'] = "Connection to $ip / $port  failed .";
            }
        }
        return $data;
    }

    public function _socket_test($ip, $port, $type)
    {
        if ($type == 'tcp')
        {
            $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        }
        else
        {
            $socket = socket_create(AF_INET, SOCK_STREAM, SOL_UDP);
        }

        $res = @socket_connect($socket, $ip, $port);
        if ($res)
        {
            //socket_write($socket, $cmd, strlen($cmd));

            socket_close($socket);
            return true;
        }
        else
        {
            socket_close($socket);
            return false;
        }
    }

    public function ajax_update_password()
    {
        Configure::write('debug', 0);
        $password = $_POST['password'];
        $client_id = $_POST['client_id'];
        $sql1 = "update client set password='$password' where  client_id=$client_id ";
        $sql2 = "update users set password=md5('$password') where  client_id=$client_id ";
        $this->Client->query($sql1);
        $this->Client->query($sql2);
        $this->Client->create_json_array('#ClientOrigRateTableId', 201, __('Your password is modified successfully!', true));
        $this->Session->write("m", Client::set_validator());
        try
        {
            $this->set('extensionBeans', 1);
        }
        catch (Exception $e)
        {
            echo "Server Exception";
        }
    }

    public function upload_reset_balance_change_header()
    {
        if ($this->RequestHandler->isPost())
        {
            $path = APP . 'tmp' . DS . 'upload' . DS . 'csv';
            $filename = trim($_POST['myfile_guid']);
            $with_header = isset($_POST['with_header']);
            $type = $_POST['balance_type'];
            $abspath = $path . DS . $filename . ".csv";
            $cmds = array();
            $schema = $this->requestAction('/down/get_schema_reset_balance');
            $fields = array_keys($schema);
            array_push($cmds, "'s/\\r/\\n/g'");
            array_push($cmds, "'/^$/d");
            if (!$with_header)
            {
                // sed 插入第一行插入空行
                //$cmd = "'1i\\\\'";
                $cmd_awk = "awk -F ',' 'NR==1 {print NF}' {$abspath}";
                $awk_result = shell_exec($cmd_awk);
                $line_rows = (int) $awk_result - 1;
                $quote_str = str_repeat(',', $line_rows);

                array_push($cmds, "'1i\\{$quote_str}\\'");
                //$cmd = "sed -i '1i\\\\' {$abspath}";
            }
            $cmd_str = implode(' -e ', $cmds);
            $cmd_line = "sed -i -e {$cmd_str} {$abspath}";
            shell_exec($cmd_line);

            $table = array();
            $row = 1;

            $handle = popen("head -n 21 {$abspath}", "r");

            while ($row <= 21 && $data = fgetcsv($handle, 1000, ","))
            {
                $row++;
                array_push($table, $data);
            }

            pclose($handle);
            $this->set('table', $table);
            $this->set('columns', $fields);
            $this->set('abspath', $abspath);
            $this->set('type', $type);
        }
    }

    public function change_password($client_id)
    {
        Configure::write('debug', 0);
        $this->autoLayout = false;
        if ($this->RequestHandler->isPost())
        {
            if (isset($this->params['form']['password']))
            {
                $password = $this->params['form']['password'];
                $sql1 = "update client set password='$password' where  client_id=$client_id ";
                $sql2 = "update users set password=md5('$password') where  client_id=$client_id ";
                $this->Client->query($sql1);
                $this->Client->query($sql2);
                $client_name_info = $this->Client->query("select name from client where client_id = {$client_id}");
                $client_name = $client_name_info[0][0]['name'];
                $this->Client->create_json_array('#ClientOrigRateTableId', 201, __('The password of client %s is modified successfully!', true, $client_name));
                $this->Session->write("m", Client::set_validator());
                $this->redirect("/clients/index");
            }
        }
    }

    public function get_mutual_ingress_egress_detail($client_id)
    {
        $this->loadModel('FinanceHistory');
        if (isset($this->params['form']['balance']))
        {
            $balance = $this->params['form']['balance'];
            $begin_time = $this->params['form']['begin_time'];
            $description = $this->params['form']['description'];
            $this->set('balance', $balance);
            $this->set('begin_time', $begin_time);
            $this->set('description', $description);
        }
        if ($begin_time)
        {
            $start_time = $begin_time;
        }
        else
        {//错误 
        }
        $end_time = date("Y-m-d");

        $client_id = base64_decode($client_id);
        $begin_balance_result = $this->FinanceHistory->find('first', array(
            'order' => array('FinanceHistory.date DESC'),
            'conditions' => array('FinanceHistory.date < ' => $start_time, 'client_id' => $client_id),
        ));

        if (!$begin_balance_result)
        {
            $begin_balance_result = $this->FinanceHistory->find('first', array(
                'order' => array('FinanceHistory.date ASC'),
                'conditions' => array('client_id' => $client_id),
            ));
        }

        $begin_balance = $begin_balance_result['FinanceHistory']['mutual_balance'];
        $start_time = $begin_balance_result['FinanceHistory']['date'];

        $end_balance_result = $this->FinanceHistory->find('first', array(
            'order' => array('FinanceHistory.date DESC'),
            'conditions' => array('FinanceHistory.date <= ' => $end_time, 'client_id' => $client_id),
        ));


        $financehistories = $this->FinanceHistory->find('all', array(
            'order' => array('FinanceHistory.date'),
            'conditions' => array('FinanceHistory.date BETWEEN ? AND ?' => array($start_time, $end_time), 'client_id' => $client_id),
        ));

        $current_date = date("Y-m-d");
        if (strtotime($end_time) >= strtotime($current_date))
        {
            $end_time = $current_date;

            $current_finance = $this->FinanceHistory->get_current_finance_detail($client_id, "limit 100");

            $current_finance['date'] = $current_date;

            $financehistories[] = array(
                'FinanceHistory' => $current_finance
            );

            $end_balance = $current_finance['mutual_balance'];
        }
        else
        {
            if (count($end_balance_result) == 1)
            {
                $end_balance = $end_balance_result['FinanceHistory']['mutual_balance'];
                $end_time = $end_balance_result['FinanceHistory']['date'];
            }
            else
            {
                $end_balance = 0;
            }
        }


        $this->set('start_time', $start_time);
        $this->set('end_time', $end_time);

        $sum_arr = array();
        foreach ($financehistories as $financehistory)
        {
            foreach ($financehistory['FinanceHistory'] as $key => $value)
            {
                @$sum_arr[$key] += $value;
            }
        }
        $this->set('type_sum', $sum_arr);

        $this->set('financehistories', $financehistories);

        $this->set('client_id', $client_id);
        $this->set('begin_balance', $begin_balance);
        $this->set('end_balance', $end_balance);
        $name = $this->Client->get_client_name($client_id);
        $this->set('client_name', $name);


        if (isset($_GET['export']))
        {
            $name = str_replace(' ', '_', $name);
            header("Pragma: public");
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Content-Type: application/force-download");
            header("Content-Type: application/octet-stream");
            header("Content-Type: application/download");
            header("Content-Disposition: attachment;filename=balance_{$name}.xls");
            header("Content-Transfer-Encoding: binary ");
            Configure::write('debug', 0);
            $this->autoLayout = FALSE;
            $this->render('get_mutual_ingress_egress_detail_xls');
        }
    }

    public function reset_balance($client_id)
    {
        Configure::write('debug', 0);
        $this->autoLayout = false;
        if ($this->RequestHandler->isPost())
        {
//           echo "<pre>"; print_r($_POST);die;
            if (isset($this->params['form']['balance']))
            {
                $balance = $this->params['form']['balance'];
                $begin_time = $this->params['form']['begin_time'];
                $description = $this->params['form']['description'];
                $client_name_info = $this->Client->query("select name from client where client_id = {$client_id}");
                $client_name = $client_name_info[0][0]['name'];
                $sql = "INSERT INTO client_payment (result, receiving_time, amount, client_id, description, payment_type, payment_time)
                    VALUES(true, CURRENT_TIMESTAMP, {$balance}, {$client_id}, '{$description}', 13, '{$begin_time}')";
                $this->Client->query($sql);

                $this->Client->logging(0, 'Reset Balance', "Client:{$client_name}, Balance:[{$balance}]");
                $this->Client->create_json_array('#ClientOrigRateTableId', 201, __('The balance of %s is reset successfully!', true, $client_name));
                $this->Session->write("m", Client::set_validator());
                $this->redirect("/clients/index");
            }
        }
    }

    public function upload_reset_balance()
    {
        if ($this->RequestHandler->isPost())
        {
            $abspath = $_POST['abspath'];
            $new_columns = $_POST['columns'];
            $type = $_POST['type'];
            $new_columns_str = implode(',', $new_columns);
            $cmd_ = "sed -i '1s/.*/{$new_columns_str}/g' {$abspath}";
            shell_exec($cmd_);
            $object_name = $type == 0 ? 'Reset Actual Balance' : 'Reset Mutual Balance';
            $sql = "INSERT INTO import_export_logs(file_path,  status,
		user_id, obj, log_type, time) VALUES('{$abspath}', 1, {$_SESSION['sst_user_id']}, '$object_name', 1, CURRENT_TIMESTAMP(0)) returning id";
            $result = $this->Client->query($sql);
            $log_id = $result[0][0]['id'];
            $statistics = array(
                'log_id' => $log_id,
            );
            $this->set('statistics', $statistics);
            //$this->Client->create_json_array('#ClientOrigRateTableId', 201, __('The file of Reset Balance is uploaded succesfully!', true));
            //$this->Session->write("m", Client::set_validator());
            //$this->redirect("/clients/upload_reset_balance");
        }
        $this->set('example', $this->webroot . 'example/reset_balance.csv');
    }

    function add_resouce_ingress()
    {
        if (!empty($thiservices->data ['Gatewaygroup']))
        {
            $resource_id = $this->Gatewaygroup->saveOrUpdate($this->data, $_POST);
            if ($resource_id == 'fail')
            {
                $this->set('m', Gatewaygroup::set_validator()); //向界面设置验证信息
                $this->set('post', $this->data);
                $this->init_info();
            }
            else
            {
                $_SESSION ['resource_id'] = $resource_id;
                $_SESSION ['resource_name'] = $this->data ['Gatewaygroup'] ['name'];
                $_SESSION ['gress'] = 'ingress';
                $this->redirect("/clientrates/view_rate_detail/");
            }
        }
        else
        {
            $this->init_info();
        }
    }

    public function summary_reporter()
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;

        $client_id = $_POST['client_id'];
        $date = $_POST['date'];

        $conf_path = Configure::read('script.conf');

        $sql = "SELECT name,auto_send_zone, auto_summary_group_by
FROM client WHERE client_id = {$client_id}";
        $result = $this->Client->query($sql);

        switch ($result[0][0]['auto_summary_group_by'])
        {
            case 0:
                $group = 'country';
                break;
            case 1:
                $group = 'code_name';
                break;
            case 2:
                $group = 'code';
                break;
            default:
                $group = 'country';
                break;
        }

        $start_datetime = $date . ' 00:00:00 ' . $result[0][0]['auto_send_zone'];
        $end_datetime = $date . ' 23:59:59 ' . $result[0][0]['auto_send_zone'];

        echo 'Daily summary report is sending to [' . $result[0][0]['name'] . ']!';
        //$this->Client->create_json_array('#ClientOrigRateTableId', 201, __('Daily summary report is sending to [' . $result[0][0]['name'] .']!', true));
        //$this->Session->write("m", Client::set_validator());
        //$this->redirect("/clients/index");
    }

    public function low_balance_alert($client_id)
    {
        $this->redirect("/clients/index");
    }

    function add_resouce_egress()
    {
        if (!empty($this->data ['Gatewaygroup']))
        {
            $resource_id = $this->Gatewaygroup->saveOrUpdate($this->data, $_POST);
            if ($resource_id == 'fail')
            {
                $this->set('m', Gatewaygroup::set_validator()); //向界面设置验证信息
                $this->set('post', $this->data);
                $this->init_info();
            }
            else
            {
                $_SESSION ['resource_id'] = $resource_id;
                $_SESSION ['resource_name'] = $this->data ['Gatewaygroup'] ['name'];
                $_SESSION ['gress'] = 'egress';
                $this->redirect("/gatewaygroups/add_host/");
            }
        }
        else
        {
            $this->init_info();
        }
    }

    function getclient()
    {
        $this->autoLayout = FALSE;
        $this->autoRender = FALSE;
        Configure::write('debug', 0);
        $q = '';
        if (isset($_GET['q']))
        {
            $q = strtolower($_GET['q']);
        }
        if (!$q)
        {
            return;
        }
        $result = $this->Client->query("select client_id, name from client order by name asc");
        $items = array();
        foreach ($result as $val)
        {
            $items[$val[0]['name']] = $items[0]['client_id'];
        }
        foreach ($items as $key => $value)
        {
            if (strpos(strtolower($key), $q) !== false)
            {
                echo "$key|$value\n";
            }
        }
    }

    public function total_avaliable_balance($client_id)
    {
        //Configure::write('debug', 0);
        $sql = "SELECT 
(SELECT COALESCE(sum(amount), 0) FROM client_payment WHERE client_id = {$client_id} AND payment_type in (4,5)) 
-
(SELECT COALESCE(sum(ingress_client_cost::real + lnp_dipping_cost::real), 0) FROM client_cdr".date("Ymd")." WHERE ingress_client_id = '{$client_id}') AS ingress,
(SELECT COALESCE(sum(amount), 0) FROM client_payment WHERE client_id = {$client_id} AND payment_type in (6, 11))
-
(SELECT COALESCE(sum(egress_cost::real), 0) FROM client_cdr".date("Ymd")." WHERE egress_client_id = '{$client_id}') AS egress,
(SELECT COALESCE(sum(amount), 0) FROM client_payment WHERE client_id = {$client_id} AND payment_type = 10) AS offset,
(SELECT COALESCE(sum(amount), 0) FROM client_payment WHERE client_id = {$client_id} and payment_type = 7 ) AS credit";
        $result = $this->Client->query($sql);
        $ingress = empty($result[0][0]['ingress']) ? 0 : $result[0][0]['ingress'];
        $egress = empty($result[0][0]['egress']) ? 0 : $result[0][0]['egress'];
        $offset = empty($result[0][0]['offset']) ? 0 : $result[0][0]['offset'];
        $credit = empty($result[0][0]['credit']) ? 0 : $result[0][0]['credit'];
        $balance = $ingress - $egress + $offset - $credit;
        $sql = "INSERT INTO client_balance_operation_action (balance, ingress_balance, egress_balance, client_id, action) VALUES($balance, $ingress, $egress, '{$client_id}', 1)";
        $this->Client->query($sql);
        $this->Client->create_json_array('#ClientOrigRateTableId', 201, __('Succeeded', true));
        $this->Session->write("m", Client::set_validator());
        $this->redirect('/clients/index');
    }

    //获取非auto_invoice的client
    function getManualClient()
    {
        $this->autoLayout = FALSE;
        $this->autoRender = FALSE;
        Configure::write('debug', 0);
        $q = '';
        if (isset($_GET['q']))
        {
            $q = strtolower($_GET['q']);
        }
        if (!$q)
        {
            return;
        }
        $result = $this->Client->query("select client_id, name from client where auto_invoicing = false order by name asc");
        $items = array();
        foreach ($result as $val)
        {
            $items[$val[0]['name']] = $items[0]['client_id'];
        }
        foreach ($items as $key => $value)
        {
            if (strpos(strtolower($key), $q) !== false)
            {
                echo "$key|$value\n";
            }
        }
    }

    function getManualClient1()
    {
        $this->autoLayout = FALSE;
        $this->autoRender = FALSE;
        Configure::write('debug', 0);
        $q = '';
        if (isset($_GET['q']))
        {
            $q = strtolower($_GET['q']);
        }
        if (!$q)
        {
            return;
        }
        $result = $this->Client->query("select client_id, name from client order by name asc");
        $items = array();
        foreach ($result as $val)
        {
            $items[$val[0]['name']] = $items[0]['client_id'];
        }
        foreach ($items as $key => $value)
        {
            if (strpos(strtolower($key), $q) !== false)
            {
                echo "$key|$value\n";
            }
        }
    }

    function addroutingplan()
    {
        $this->layout = '';
    }

    function addroute_strategy()
    {
        $this->autoRender = false;
        Configure::write('debug', 0);
        $this->layout = '';
        $sql = "select count(*) from route_strategy where name = '{$_POST['name']}'";
        $count = $this->Client->query($sql);
        if ($count[0][0]['count'] > 0)
        {
            echo "0";
            return;
        }
        $sql = "insert into route_strategy(name) values ('{$_POST['name']}') RETURNING route_strategy_id";
        $result = $this->Client->query($sql);
        return $result[0][0]['route_strategy_id'];
    }

    function sendroutingplan()
    {
        $this->autoRender = false;
        Configure::write('debug', 0);
        $this->autoLayout = false;
        $count = count($_POST['prefix']);
        $route_strategy_id = $_POST['route_strategy_id'];
        for ($i = 0; $i < $count; $i++)
        {
            $prefix = $_POST['prefix'][$i];
            $type = $_POST['routetype'][$i];
            $static = $_POST['static'][$i];
            $dynamic = $_POST['dynamic'][$i];
            if ($type == '1')
            {
                $static = 'NULL';
            }
            else if ($type == '2')
            {
                $dynamic = 'NULL';
            }
            $sql = "insert into route(digits, dynamic_route_id, static_route_id, route_type, route_strategy_id)
                    values ('{$prefix}', $dynamic, $static, $type, $route_strategy_id)";
            echo $sql;
            $this->Client->query($sql);
        }
    }

    function getstaticroute()
    {
        Configure::write('debug', 0);
        $this->autoLayout = FALSE;
        $this->autoRender = FALSE;
        $sql = "select product_id as id, name from product order by name asc";
        $result = $this->Client->query($sql);
        //print_r($result);
        $arr = array();
        foreach ($result as $val)
        {
            array_push($arr, $val[0]);
        }
        echo json_encode($arr);
    }

    function getrouteplan()
    {
        Configure::write('debug', 0);
        $this->autoLayout = FALSE;
        $this->autoRender = FALSE;
        $sql = "select route_strategy_id as id, name from route_strategy order by name asc";
        $result = $this->Client->query($sql);
        //print_r($result);
        $arr = array();
        foreach ($result as $val)
        {
            array_push($arr, $val[0]);
        }
        echo json_encode($arr);
    }

    function getratetable()
    {
        Configure::write('debug', 0);
        $this->autoLayout = FALSE;
        $this->autoRender = FALSE;
        $sql = "select rate_table_id as id, name from rate_table order by name asc";
        $result = $this->Client->query($sql);
        //print_r($result);
        $arr = array();
        foreach ($result as $val)
        {
            array_push($arr, $val[0]);
        }
        echo json_encode($arr);
    }

    function getdynamicroute()
    {
        Configure::write('debug', 0);
        $this->autoLayout = FALSE;
        $this->autoRender = FALSE;
        $sql = "select dynamic_route_id as id, name from dynamic_route order by name asc";
        $result = $this->Client->query($sql);
        //print_r($result);
        $arr = array();
        foreach ($result as $val)
        {
            array_push($arr, $val[0]);
        }
        echo json_encode($arr);
    }

    public function view_egress()
    {
        $this->init_info();
        $client_id = empty($this->params['pass'][0]) ? $_SESSION['gate_client_id'] : $this->params['pass'][0];
        $this->select_naem($client);
        $_SESSION['gate_client_id'] = $client_id;
        $this->set('gate_client_id', $client_id);
        $this->set('p', $this->Client->findAll_egress($client_id));
    }

    //对接网关
    public function view_ingress()
    {
        $this->init_info();
        $client_id = empty($this->params['pass'][0]) ? $_SESSION['gate_client_id'] : $this->params['pass'][0];
        $this->set('gate_client_id', $client_id);
        $this->set('p', $this->Client->findAll_ingress($client_id));
    }

    function dis_able_resource()
    {
        $id = $this->params['pass'][0];
        $page = $this->params['pass'][1];
        $this->Client->query("update resource set active=false where resource_id =$id");
        $this->redirect(array('action' => $page));
    }

    function active_resource()
    {
        if (!$_SESSION['role_menu']['Management']['clients']['model_w'])
        {
            $this->redirect_denied();
        }
        $id = $this->params['pass'][0];
        $page = $this->params['pass'][1];
        $this->Client->query("update resource set active=true where resource_id =$id");
        $this->redirect(array('action' => $page));
    }

    public function ajax_tran()
    {
        Configure::write('debug', 0);
        $client_id = $this->params['pass'][0];
        $this->set('extensionBeans', $this->Client->query("select create_time,amount,tran_type,balance,cause,description  from  transation  where user_type=3 and id=$client_id"));
    }

    /**
     * 初始化信息
     */
    function init_info()
    {
        //	$this->set('rate',$this->Client->findRates());
        $this->set('currency', $this->Client->findCurrency());
        $this->set('service_charge', $this->Client->findservice_charge());
        //$this->set('product', $this->Client->findAllProducts());
        //$this->set('dyn_route', $this->Client->findDyn_routes());
        $this->set('paymentTerm', $this->Client->findPaymentTerm());
        $this->set('transation_fees', $this->Client->findTransFees());
    }

    /**
     * 编辑客户信息
     */
    function edit()
    {
        $this->pageTitle = "Management/Edit Carrier";
        if ($this->RequestHandler->isPost())
        {
            $this->_render_edit_impl();
        }
        $this->_render_edit_data();
    }

    function _render_edit_impl()
    {
        //pr($this->data);
//		pr("--------------------------------------");
        //pr($_POST);
        if ($_SESSION['role_menu']['Management']['clients']['model_w'])
        {
            $this->data['Client']['allowed_credit'] = 0 - $this->data['Client']['allowed_credit'];
            $flag = $this->Client->saveOrUpdate($this->data, $_POST); //保存
            if ($flag['client_id'])
            {
                //$this->Client->query("update resource set active = {$this->data['Client']['status']},profit_margin = " . (empty($this->data['Client']['profit_margin']) ? 0 : $this->data['Client']['profit_margin']) . ", profit_type = " . (empty($this->data['Client']['profit_type']) ? 1 : $this->data['Client']['profit_type']) . " where client_id = " . intval($this->data['Client']['client_id']));
                //$this->Client->query("update resource set profit_margin = " . (empty($this->data['Client']['profit_margin']) ? 0 : $this->data['Client']['profit_margin']) . ", profit_type = " . (empty($this->data['Client']['profit_type']) ? 1 : $this->data['Client']['profit_type']) . " where client_id = " . intval($this->data['Client']['client_id']));
                $this->Client->create_json_array('#ClientOrigRateTableId', 201, __('The Carrier[%s] modified successfully.', true, $this->data['Client']['name']));
                $this->Session->write("m", Client::set_validator());
                $url_flug = "clients-index";
                $this->modify_log_noty($flag['log_id'], $url_flug);
//                $this->xredirect("/logging/index/{$flag['log_id']}/clients-index");
            }
        }
        else
        {
            $this->redirect_denied();
        }
    }

    function _render_edit_data()
    {
        $this->Client->id = base64_decode($this->params ['pass'] [0]);
        $this->set('post', $this->Client->read());
        $this->set('gate_client_id', base64_decode($this->params ['pass'] [0]));
        $this->init_info();
    }

    /**
     * 添加客户信息
     */
    function add()
    {
        if ($_SESSION['role_menu']['Management']['clients']['model_w'])
        {
            if ($this->RequestHandler->isPost())
            {
                $this->_render_add_impl();
            }
            $this->_render_add_data();
        }
        else
        {
            $this->redirect_denied();
        }
    }

    function _render_add_data()
    {
        if (!empty($this->params['url']['order_user_id']))
        {
            $order_user_id = intval($this->params['url']['order_user_id']);
            $this->set('post', $this->Client->query("select * from order_user where id = " . $order_user_id));
        }
        $this->init_info();
    }

    function _render_add_impl_save()
    {
        $this->data['Client']['allowed_credit'] = 0 - $this->data['Client']['allowed_credit'];
        if (empty($_POST['order_user_id']))
        {
            return $this->Client->saveOrUpdate($this->data, $_POST); //保存
        }
        else
        {
            $order_user_info = $this->Client->query("select * from order_user where id = " . intval($_POST['order_user_id']));
            //var_dump($order_user_info);exit;
            $return = $this->Client->saveOrUpdate_orderuser($this->data, $_POST); //保存
            if ($return)
            {
                require_once(APP . 'vendors/mail_order_user.php');
            }
            return $return;  //传一个有client_id 有log_id 的数组
        }
    }

    function _render_add_impl_redirect($flag_arr)
    {
        if ($flag_arr['client_id'])
        {
            $name = $_POST['data']['Client']['name'];
            //$this->Client->create_json_array('#ClientOrigRateTableId',201,__('Create carriers successfully!',true));
            $this->Client->create_json_array('#ClientOrigRateTableId', 201, __('The Carrier [%s] is created successfully', true, $name));
            //TODO 注册向导
            if ($flag_arr['log_id'])
            {
                $url_flug = "clients-step2-{$flag_arr['client_id']}";
                $this->modify_log_noty($flag_arr['log_id'], $url_flug);
//                $this->xredirect("/logging/index/{$flag_arr['log_id']}/clients-step2-{$flag_arr['client_id']}");
            }
            else
            {
                $this->xredirect("/clients/step2/{$flag_arr['client_id']}");
            }
            //$this->xredirect ("/clients/index"); // succ
        }
        else
        {
            //$this->Client->create_json_array('#ClientOrigRateTableId',101,__('Create carriers Failed!',true));
            $this->Client->create_json_array('#ClientOrigRateTableId', 101, __('Fail to create Carrier', true));
            $this->xredirect(array('controller' => 'clients', 'action' => 'add')); // failed	
        }
    }

    function step2($client_id)
    {
        $this->set("client_id", $client_id);
    }

    function addegress($client_id)
    {
        if ($this->RequestHandler->isPost())
        {
            //print_r($_POST);
            if (empty($_POST['data']['Client']['rate_table_id']))
            {
                $_POST['data']['Client']['rate_table_id'] = "NULL";
            }


            $is_same = $this->Clients->checkIsHaveByName($_POST['data']['Client']['alias']);


            if ($is_same != 0)
            {
                $this->Client->create_json_array('#ClientOrigRateTableId', 101, __('Name already exists!', true));
                $this->xredirect("/clients/addegress/" . $client_id);
            }

            if (empty($_POST['data']['Client']['alias']))
            {
                $this->Client->create_json_array('#ClientOrigRateTableId', 101, __('Name can not be empty!', true));
                $this->xredirect("/clients/addegress/" . $client_id);
            }

            $res = $this->Clients->getAllowedCredit($client_id);
            if ($res != 0)
            {
                $enough_balance = 't';
            }
            else
            {
                $enough_balance = 'f';
            }

            $res_id = $this->Clients->insertResource1($_POST['data']['Client']['ingress'], $_POST['data']['Client']['egress'], $_POST['data']['Client']['alias'], $client_id, $_POST['data']['Client']['rate_table_id'], $enough_balance);


            $resource_id = $res_id[0][0]['resource_id'];

            $len = count($_POST['ip']);
            for ($i = 0; $i < $len; $i++)
            {
                $this->Clients->insertHosts($_POST['ip'][$i], $_POST['port'][$i], $resource_id);
            }
            $this->Client->create_json_array('#ClientOrigRateTableId', 201, __('Succeeded', true));
            $is_finished = $_POST['is_finished'];
            if ($is_finished == 1)
            {
                $this->xredirect('/clients/index');
            }
            else
            {
                $this->xredirect("/clients/addegress/" . $client_id); // succ
            }
        }
        $sql = "select name from client where client_id = {$client_id}";
        $result = $this->Gatewaygroup->query($sql);
        $client_name = $result[0][0]['name'];
        $this->set('client_name', $client_name);
        $this->set("client_id", $client_id);
        $this->set('rate', $this->Gatewaygroup->findAllRate());
    }

    function addingress($client_id)
    {
        if ($this->RequestHandler->isPost())
        {

            $res = $this->Clients->getAllowedCredit($client_id);
            if ($res != 0)
            {
                $enough_balance = 't';
            }
            else
            {
                $enough_balance = 'f';
            }

            $is_same = $this->Clients->checkIsHaveByName($_POST['data']['Clients']['alias']);
            if ($is_same != 0)
            {
                $this->Client->create_json_array('#ClientOrigRateTableId', 101, __('Name already exists!', true));
                $this->xredirect("/clients/addingress/" . $client_id);
            }

            $res_id = $this->Clients->insertResource3($_POST['data']['Clients']['ingress'], $_POST['data']['Clients']['egress'], $_POST['data']['Clients']['alias'], $client_id, $enough_balance);

            $resource_id = $res_id[0][0]['resource_id'];
            if (isset($_POST['accounts']))
            {
                $len = count($_POST['accounts']['ip']);
                for ($i = 0; $i < $len; $i++)
                {
                    $this->Clients->insertHosts2($_POST['accounts']['ip'][$i], $_POST['accounts']['need_register'][$i], $_POST['accounts']['port'][$i], $resource_id);
                }
            }
            if (isset($_POST['resource']))
            {
                $len2 = count($_POST['resource']['id']);
                for ($i = 0; $i < $len2; $i++)
                {
                    $this->Clients->insertResourcePrefix($resource_id, $_POST['resource']['rate_table_id'][$i], $_POST['resource']['route_strategy_id'][$i], $_POST['resource']['tech_prefix'][$i]);
                }
            }
            $this->Client->create_json_array('#ClientOrigRateTableId', 201, __('Succeeded', true));
            $is_finished = $_POST['is_finished'];
            if ($is_finished == 1)
            {
                $this->xredirect('/clients/index');
            }
            else
            {
                $this->xredirect("/clients/addingress/" . $client_id); // succ
            }
        }
        $sql = "select name from client where client_id = {$client_id}";
        $result = $this->Gatewaygroup->query($sql);
        $client_name = $result[0][0]['name'];
        $this->set('client_name', $client_name);
        $this->set("client_id", $client_id);
        $this->set("rate_table", $this->Gatewaygroup->find_rate_table());
        $this->set('rout_list', $this->Gatewaygroup->find_route_strategy());
    }

    function addstatictable()
    {
        $this->layout = '';
    }

    function adddynamictable()
    {
        $this->layout = '';
    }

    function addstatictable_sub()
    {
        Configure::write('debug', 0);
        $this->autoLayout = FALSE;
        $this->autoRender = FALSE;
        $sql = "insert into product_items (product_id, digits, strategy, time_profile_id)
                    values ({$_POST['product_id']}, '{$_POST['prefix']}', {$_POST['strategy']}, {$_POST['profile']})
                    RETURNING item_id";
        $return = $this->Client->query($sql);
        $item_id = $return[0][0]['item_id'];
        $count = count($_POST['trunks']);
        for ($i = 0; $i < $count; $i++)
        {
            if ($_POST['strategy'] == 0)
            {
                $sql = "insert into product_items_resource (item_id, resource_id, by_percentage)
                        values ({$item_id}, {$_POST['trunks'][$i]}, {$_POST['percents'][$i]})";
            }
            else
            {
                $sql = "insert into product_items_resource (item_id, resource_id)
                        values ({$item_id}, {$_POST['trunks'][$i]})";
            }
            $this->Client->query($sql);
        }
        echo 1;
    }

    function adddynamictable_sub()
    {

        Configure::write('debug', 0);
        $this->autoLayout = FALSE;
        $this->autoRender = FALSE;
        $sql = "insert into dynamic_route(name,routing_rule,time_profile_id)
                    values('{$_POST['name']}', {$_POST['routing_rule']},{$_POST['profile']})
                    RETURNING dynamic_route_id";
        $return = $this->Client->query($sql);
        $dynamic_route_id = $return[0][0]['dynamic_route_id'];
        $count = count($_POST['trunks']);
        for ($i = 0; $i < $count; $i++)
        {
            if($_POST['trunks'][$i] && $dynamic_route_id) {
                $sql = "insert into dynamic_route_items (dynamic_route_id, resource_id)
                        values ({$dynamic_route_id}, {$_POST['trunks'][$i]})";
                $this->Client->query($sql);
            }
        }
        echo $dynamic_route_id;
    }

    function add_statictable_name()
    {
        $this->autoRender = false;
        Configure::write('debug', 0);
        $this->layout = '';
        $sql = "select count(*) from product where name = '{$_POST['name']}'";
        $count = $this->Client->query($sql);
        if ($count[0][0]['count'] > 0)
        {
            echo "0";
            return;
        }
        $sql = "insert into product(name) values ('{$_POST['name']}') RETURNING product_id";
        $result = $this->Client->query($sql);
        return $result[0][0]['product_id'];
    }

    function add_dynamictable_name()
    {
        $this->autoRender = false;
        Configure::write('debug', 0);
        $this->layout = '';
        $sql = "select count(*) from dynamic_route where name = '{$_POST['name']}'";
        $count = $this->Client->query($sql);
        if ($count[0][0]['count'] > 0)
        {
            echo "0";
            return;
        }
        $sql = "insert into dynamic_route(name) values ('{$_POST['name']}') RETURNING dynamic_route_id";
        $result = $this->Client->query($sql);
        return $result[0][0]['dynamic_route_id'];
    }

    // TODO 
    function get_name_statictable($id)
    {
        Configure::write('debug', 0);
        $this->autoLayout = FALSE;
        $this->autoRender = FALSE;
        $sql = "select item_id as id,digits as prefix,strategy,
                    (select name from time_profile where time_profile_id = product_items.time_profile_id) as time_profile,
                    array(
                    select resource.alias from resource left join product_items_resource on resource.resource_id = product_items_resource.resource_id
                    where product_items_resource.item_id =product_items.item_id order by product_items_resource.id asc
                    ) as trunks
                    from product_items
                    where product_id = {$id}";
        $result = $this->Client->query($sql);
        $arr = array();
        foreach ($result as $val)
        {
            array_push($arr, $val[0]);
        }
        echo json_encode($arr);
    }

    function delete_static_table($id)
    {
        Configure::write('debug', 0);
        $this->autoLayout = FALSE;
        $this->autoRender = FALSE;
        $sql = "delete from product_items where item_id = {$id}";
        $this->Client->query($sql);
        $sql = "delete from product_items_resource where item_id = {$id}";
        $this->Client->query($sql);
        echo 1;
    }

    function get_profile()
    {
        Configure::write('debug', 0);
        $this->autoLayout = FALSE;
        $this->autoRender = FALSE;
        $sql = "select time_profile_id as id, name from time_profile";
        $result = $this->Client->query($sql);
        $arr = array();
        foreach ($result as $val)
        {
            array_push($arr, $val[0]);
        }
        echo json_encode($arr);
    }

    function get_carriers()
    {
        Configure::write('debug', 0);
        $this->autoLayout = FALSE;
        $this->autoRender = FALSE;
        $sql = "select distinct client.client_id as id, client.name from client inner join resource on client.client_id = resource.client_id where resource.egress = true
                    order by client.name";
        $result = $this->Client->query($sql);
        $arr = array();
        foreach ($result as $val)
        {
            array_push($arr, $val[0]);
        }
        echo json_encode($arr);
    }

    function _render_add_impl()
    {
        $flag_arr = $this->_render_add_impl_save();

        $this->_render_add_impl_redirect($flag_arr);
    }

    function del($id)
    {
        if ($this->Client->del($id))
        {
            $this->Client->create_json_array('', 201, __('del_suc', true));
        }
        else
        {
            $this->Client->create_json_array('', 101, __('del_fail', true));
        }
        $this->Session->write('m', Client::set_validator());
        $this->redirect(array('action' => 'index'));
    }

    function ajax_del($id, $type = 'false')
    {
        $type = _filter_array(Array('false' => false, 'true' => true));
        $this->Client->query("delete from users_limit where client_id = {$id}");
        $log_id = $this->Client->del($id, $type);
        echo $log_id;
    }

    public function view()
    {
        $this->redirect("/clients/index");
        /* if (!empty($_REQUEST['edit_id'])){
          $sql = "select client.login,client.password,client.client_id ,name as client_name,client.status,mode,egress,ingress,
          (select balance from client_balance where client_id::integer =client.client_id ) as balance,
          (select current_balance from invoice where client_id=client.client_id order by invoice_id desc limit 1)as mutual_balance
          from  client
          left join (select client_id,count(ingress) as ingress,count(egress)  as egress from resource group by client_id) as resource on client.client_id =resource.client_id
          where Client.client_id = {$_REQUEST['edit_id']}
          ";
          $this->_order_condtions(array('c_client_id','client_name','balance'));
          $result = $this->Client->query ( $sql );
          require_once 'MyPage.php';
          $results = new MyPage ();
          $results->setTotalRecords ( 1 ); //总记录数
          $results->setCurrPage ( 1 ); //当前页
          $results->setPageSize ( 1 ); //页大小
          $results->setDataArray ( $result );
          $this->set('edit_return',true);
          $this->set('p',$results);
          }else{
          $this->set('p',$this->Client->findAll($this->_order_condtions(array('client_id','client_name','balance','mode'))));
          } */
    }

    public function ss_client()
    {
        $this->layout = '';
        $this->set('p', $this->Client->findAll_ss());
    }

    public function ss_client_term($search_res = null)
    {
        $this->layout = '';
        $this->set('p', $this->Client->findAll_ss());
    }

    public function ss_reseller($search_res = null)
    {
        $this->layout = '';
        //		$this->init_info ();
        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = 100 : $pageSize = $_GET['size'];
        //模糊搜索
        if (isset($_POST['searchkey']))
        {
            $results = $this->Client->likequery_rss($_POST['searchkey'], $currPage, $pageSize, $search_res);
            $this->set('searchkey', $_POST['searchkey']);
            $this->set('p', $results);
            return;
        }
        //搜索 
        if (!empty($this->data['Client']))
        {
            $results = $this->Client->queryClient($this->data, $currPage, $pageSize, $search_res);
            $this->set('search', 'search'); //搜索设置
        }
        else
        {
            $results = $this->Client->findAll_rss($currPage, $pageSize, $search_res);
        }
        $this->set('p', $results);
    }

    public function ss_reseller_term($search_res = null)
    {
        $this->layout = '';
        //		$this->init_info ();
        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = 100 : $pageSize = $_GET['size'];
        //模糊搜索
        if (isset($_POST['searchkey']))
        {
            $results = $this->Client->likequery_rss($_POST['searchkey'], $currPage, $pageSize, $search_res);
            $this->set('searchkey', $_POST['searchkey']);
            $this->set('p', $results);
            return;
        }

        //搜索 
        if (!empty($this->data['Client']))
        {
            $results = $this->Client->queryClient($this->data, $currPage, $pageSize, $search_res);
            $this->set('search', 'search'); //搜索设置
        }
        else
        {
            $results = $this->Client->findAll_rss($currPage, $pageSize, $search_res);
        }
        $this->set('p', $results);
    }

    /* 8
     * 
     * 查找帐号卡
     */

    public function ss_card($search_res = null)
    {
        $this->layout = '';
        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = 100 : $pageSize = $_GET['size'];
        //模糊搜索
        if (isset($_POST['searchkey']))
        {
            $results = $this->Client->likequery_cardss($_POST['searchkey'], $currPage, $pageSize);
            $this->set('searchkey', $_POST['searchkey']);
            $this->set('p', $results);
            return;
        }
        //搜索 
        if (!empty($this->data['Client']))
        {
            $results = $this->Client->queryClient($this->data, $currPage, $pageSize, $search_res);
            $this->set('search', 'search'); //搜索设置
        }
        else
        {
            $results = $this->Client->findAll_cardss($currPage, $pageSize);
        }
        $this->set('p', $results);
    }

    /*
     * 
     * 查找帐号池
     */

    public function ss_serie($search_res = null)
    {
        $this->layout = 'ajax';
        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = 100 : $pageSize = $_GET['size'];
        //模糊搜索
        if (isset($_POST['searchkey']))
        {
            $results = $this->Client->likequery_seriess($_POST['searchkey'], $currPage, $pageSize);
            $this->set('searchkey', $_POST['searchkey']);
            $this->set('p', $results);
            return;
        }
        //搜索 
        if (!empty($this->data['Client']))
        {
            $results = $this->Client->queryClient($this->data, $currPage, $pageSize, $search_res);
            $this->set('search', 'search'); //搜索设置
        }
        else
        {
            $results = $this->Client->findAll_seriess($currPage, $pageSize);
        }
        $this->set('p', $results);
    }

    /*
     * 
     * 查找帐号池的批次
     */

    public function ss_batch($search_res = null)
    {
        $this->layout = '';
        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = 100 : $pageSize = $_GET['size'];
        //模糊搜索
        if (isset($_POST['searchkey']))
        {
            $batch = $_SESSION['batch'];
            $results = $this->Client->likequery_batchss($_POST['searchkey'], $currPage, $pageSize, $batch);
            $this->set('searchkey', $_POST['searchkey']);
            $this->set('p', $results);
            return;
        }
        //搜索 
        if (!empty($this->data['Client']))
        {
            $results = $this->Client->queryClient($this->data, $currPage, $pageSize, $search_res);
            $this->set('search', 'search'); //搜索设置
        }
        else
        {
            if (!empty($this->params['pass']))
            {
                $batch = $this->params['pass'][0];
                $_SESSION['batch'] = $batch;
            }
            else
            {
                $batch = $_SESSION['batch'];
            }
            $results = $this->Client->findAll_batchss($currPage, $pageSize, $batch);
        }
        $this->set('p', $results);
    }

    public function ss_rate()
    {
        $this->layout = '';
        $search = (!empty($_GET['search']) && strcmp('Keyword', $_GET['search']) ) ? $_GET['search'] : "";
        $this->set('search', $search);
        $this->set('p', $this->Client->findAll_ratess());
    }

    public function ss_rate_term()
    {
        $this->layout = '';
        $this->set('p', $this->Client->findAll_ratess());
    }

    /**
     * 
     * 
     * 查找费率
     * @param unknown_type $search_res
     */
    public function ss_codename_term($search_res = null)
    {
        $this->layout = '';
        //		$this->init_info ();
        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = 100 : $pageSize = $_GET['size'];
        //模糊搜索
        if (isset($_POST['searchkey']))
        {
            $results = $this->Client->likequery_codess($_POST['searchkey'], $currPage, $pageSize);
            $this->set('searchkey', $_POST['searchkey']);
            $this->set('p', $results);
            return;
        }
        //搜索 
        if (!empty($this->data['Client']))
        {
            $results = $this->Client->queryClient($this->data, $currPage, $pageSize, $search_res);
            $this->set('search', 'search'); //搜索设置
        }
        else
        {
            $results = $this->Client->findAll_codess($currPage, $pageSize);
        }
        $this->set('p', $results);
    }

    /**
     * 禁用客户
     */
    function dis_able()
    {
        $id = base64_decode($this->params['pass'][0]);
        $mesg_info = $this->Client->query("select name from client where client_id = {$id}");
        $this->Client->query("update client set  status=false where  client_id= $id;");
        $this->Client->query("update resource set  active=false where  client_id= $id;");
        $this->Client->create_json_array('', 201, __('The Carrier[%s] is disabled successfully!', true, $mesg_info[0][0]['name']));
        $this->Session->write("m", Client::set_validator());
        $this->redirect(array('action' => 'index', '?' => $this->params['getUrl']));
    }

    function active()
    {
        $id = base64_decode($this->params['pass'][0]);
        $mesg_info = $this->Client->query("select name from client where client_id = {$id}");
        $this->Client->active($id);
        $this->Client->query("update resource set  active=true where  client_id= $id;");
        $this->Client->create_json_array('', 201, __('The Carrier[%s] is enabled successfully!', true, $mesg_info[0][0]['name']));
        $this->Session->write("m", Client::set_validator());
        $this->redirect(array('action' => 'index', '?' => $this->params['getUrl']));
    }

    //设置是否能打国内电话
    function call_internal_f()
    {
        $id = $this->params['pass'][0];
        $this->Client->query("update client  set   call_internal= false  where client_id=$id");
        $this->redirect(array('action' => 'view', '?' => $this->params['getUrl']));
    }

    function call_internal_t()
    {
        $id = $this->params['pass'][0];
        $this->Client->query("update client  set   call_internal= true  where client_id=$id");
        $this->redirect(array('action' => 'view'));
    }

    //设置是否能打国际电话
    function call_international_t()
    {
        $id = $this->params['pass'][0];
        $this->Client->query("update client  set   call_international= true  where client_id=$id");
        $this->redirect(array('action' => 'view'));
    }

    function call_international_f()
    {
        $id = $this->params['pass'][0];
        $this->Client->query("update client  set   call_international= false  where client_id=$id");
        $this->redirect(array('action' => 'view'));
    }

    /**
     * 修改余额
     */
    public function edit_balance()
    {
        Configure::write('debug', 0);
        $resid = $_POST['cliid'];
        $way = $_POST['way'];
        $balance = $_POST['balance'];
        if (!preg_match('/^[0-9]+(\.[0-9]{1,3})?$/', $balance))
        {
            echo 'format';
            exit();
        }
        $now_balance = $this->Client->query("select balance from c4_client_balance where client_id = '$resid' ");
        $new_balance = $balance;
        $n_b = empty($now_balance[0][0]['balance']) ? 0 : $now_balance[0][0]['balance'];
        if ($way == 'inc')
        {
            $new_balance = $n_b + $balance;
        }
        if ($way == 'dec')
        {
            $new_balance = $n_b - $balance;
        }
        if ($way == 'perin')
        {
            $new_balance = $n_b + $n_b * $balance / 100;
        }
        if ($way == 'perde')
        {
            $new_balance = $n_b - $n_b * $balance / 100;
        }

        $qs = $this->Client->clientBalanceOperation($resid, $new_balance, 1, true);

        if (count($qs) == 0)
        {
            echo $resid;
        }
        else
        {
            echo 'false';
        }
    }

    function download()
    {
        Configure::write('debug', 0);
        $this->_catch_exception_msg(array('ClientsController', '_download_impl'), array('download_sql' => "SELECT * FROM client"));
    }

    function _download_impl($params = array())
    {
        extract($params);
        if ($this->Client->download_by_sql($download_sql, array('objectives' => 'clients')))
        {
            exit(1);
        }
    }

    function _render_index_bindModel()
    {
        $bindModel = Array();
        $bindModel['hasOne'] = Array('ClientBalance' => Array('foreignKey' => 'client_id::integer', 'fields' => 'balance'));
        $bindModel['hasMany']['Invoice'] = Array('className' => 'Invoice', 'fields' => 'current_balance', 'order' => 'invoice_id desc', 'limit' => 1);
        $this->Client->bindModel($bindModel, false);
        $this->Client->unbindModel(Array('hasMany' => Array('Resource')), false);
    }

    function _render_index_order()
    {
        $this->paginate['order'] = $this->_order_condtions(Array('Client.client_id', 'Client.name', 'current_balance', 'Client.update_at', 'Client.update_by', 'ClientBalance.balance', 'Client.allowed_credit', 'egress_count' => '"Client__egress_count"', 'ingress_count' => '"Client__ingress_count"'), null, 'Client.name asc');
    }

    function _render_index_fields()
    {
        //$this->paginate['fields']=Array('login', 'password', 'Client.client_id', 'name', 'status', 'mode', '(select sum(amount) from client_payment where client_id="Client"."client_id" and payment_type=4) as "Payment__balance_4"', '(select sum(amount) from client_payment where client_id="Client"."client_id" and payment_type=1) as "Payment__balance_1"', '(select sum(amount) from client_payment where client_id="Client"."client_id" and payment_type=2) as "Payment__balance_2"', '(select sum(amount) from client_payment where client_id="Client"."client_id" and payment_type=3) as "Payment__balance_3"','ClientBalance.balance', 'ClientBalance.ingress_balance', 'ClientBalance.egress_balance', 'allowed_credit', 'profit_margin', 'profit_type', '(select count(*) from resource where client_id="Client"."client_id" and egress=true)::float as "Client__egress_count"', '(select count(*) from resource where client_id="Client"."client_id" and ingress=true)::float as "Client__ingress_count"');
        $this->paginate['fields'] = Array('DISTINCT Client.login', 'password', 'Client.client_id', 'name', 'status', 'mode', 'update_at', 'update_by', '(select sum(client_payment.amount) from client_payment left join invoice on client_payment.invoice_number=invoice.invoice_number where client_payment.client_id="Client"."client_id" and client_payment.payment_type=4 and client_payment.approved = true and invoice.type=0) as "Payment__balance_invoice_buy"', '(select sum(client_payment.amount) from client_payment left join invoice on client_payment.invoice_number=invoice.invoice_number where client_payment.client_id="Client"."client_id" and client_payment.payment_type=4 and client_payment.approved = true and invoice.type=1 and invoice.state != -1) as "Payment__balance_invoice_sell"', '(SELECT (SELECT COALESCE(sum(amount), 0) FROM client_payment WHERE client_id = "Client"."client_id" AND payment_type in (4,5))-(SELECT COALESCE(sum(total_amount), 0) FROM invoice WHERE client_id = "Client"."client_id" AND state = 9 AND type = 0)) AS "Payment__balance_1"', '(SELECT (SELECT COALESCE(sum(amount), 0) FROM client_payment WHERE client_id = "Client"."client_id" AND payment_type in (6, 11)) - (SELECT COALESCE(sum(total_amount), 0) FROM invoice WHERE client_id = "Client"."client_id" AND type = 1) ) AS "Payment__balance_2"', '(SELECT COALESCE(sum(amount), 0) FROM client_payment WHERE client_id = "Client"."client_id" AND payment_type = 10) AS offset', '(SELECT COALESCE(sum(amount), 0) FROM client_payment WHERE client_id = "Client"."client_id" and payment_type = 7 ) AS credit', '(select sum(amount) from client_payment where client_id="Client"."client_id" and payment_type=3  and approved = true) as "Payment__balance_3"', '(select sum(total_amount) from invoice where client_id="Client"."client_id" and type=0 and invoice.state != -1) as "Invoice__balance_buy"', '(select sum(total_amount) from invoice where client_id="Client"."client_id" and type=1 and invoice.state != -1) as "Invoice__balance_sell"', 'ClientBalance.balance', 'ClientBalance.ingress_balance', 'ClientBalance.egress_balance', 'allowed_credit', '(select count(*) from resource where client_id="Client"."client_id" and egress=true)::float as "Client__egress_count"', '(select count(*) from resource where client_id="Client"."client_id" and ingress=true)::float as "Client__ingress_count"');
    }

    function _render_index_conditions($options = Array())
    {

        $this->paginate["joins"] = array(
            array('table' => 'users_limit',
                'alias' => 'Users_limit',
                'type' => 'INNER',
                'conditions' => array(
                    'Client.client_id = Users_limit.client_id',
                )
            )
        );

        if ($_SESSION['login_type'] == 3)
        {
            $this->paginate['conditions'] = array('Client.client_id' => $_SESSION['sst_client_id']);
            // $this->paginate['conditions']['Users_limit.user_id'] = $_SESSION['sst_user_id'];
        }
        else
        {
            $this->paginate['conditions'] = $this->_filter_conditions(Array('id' => 'Client.client_id', 'payment_term_id' => 'Client.payment_term_id', 'name' => 'Client.search_name', 'client_type', 'search', 'company' => 'Client.company', 'status'));
            if (empty($this->paginate['conditions']))
            {
                $this->paginate['conditions'] .= "Users_limit.user_id = {$_SESSION['sst_user_id']}";
            }
            else
            {
                $this->paginate['conditions'] .= " and Users_limit.user_id = {$_SESSION['sst_user_id']}";
            }
        }
    }

    function _render_index_data()
    {

        $this->_render_index_bindModel();
        $this->_render_index_order();
        $this->_render_index_fields();

        $options['joins'] = /*
                  $options['conditions'] = array(
                  'User_limit.user_id' => $_SESSION['sst_user_id']
                  );
                 * 
                 */
                $this->_render_index_conditions(Array('Client__ingress_count>0'));


        $this->data = $this->paginate('Client');
    }

    function _render_index_lang()
    {
        $this->set('lang', $this->Session->read("Config.language"));
    }

    /*
      function get_payment_term_type()
      {
      Configure::write('debug', 0);
      $this->autoRender = false;
      $this->autoLayout = false;
      $payment_term_id = $_POST['payment_term_id'];
      $sql = "select type from payment_term where payment_term_id = $payment_term_id";
      $result = $this->Client->query($sql);
      echo json_encode($result);
      }
     * 
     */

    function _render_index_layout()
    {
        $this->pageTitle = 'Management/Carriers';
        if ($this->_isAjax())
        {
            $this->render('index_ajax');
        }
    }

    public function carrier()
    {
        $this->pageTitle = 'Management/Client Portal';
        $user_id = $_SESSION['sst_user_id'];
        $data = $this->Client->get_carrier_info($user_id);
        $data = array_merge($data[0][0], $this->Client->get_balance($data[0][0]['client_id']));
        $this->set('data', $data);
    }

    public function clients_balance()
    {
        $client_id = $_SESSION['sst_client_id'];
        if (isset($_GET['start_time']))
        {
            $start_time = $_GET['start_time'];
        }
        else
        {
            $start_time = date("Y-m-d 00:00:00");
        }
        $this->set('start_time', $start_time);
        if (isset($_GET['end_time']))
        {
            $end_time = $_GET['end_time'];
        }
        else
        {
            $end_time = date("Y-m-d 23:59:59");
        }
        $this->set('end_time', $end_time);
        $reset_balance_info = $this->Client->get_reset_balance($client_id, $start_time);
        if (!empty($reset_balance_info))
        {
            $reset_balance = $reset_balance_info[0][0]['amount'];
            $reset_time = $reset_balance_info[0][0]['payment_time'];
        }
        else
        {
            $reset_balance = 0;
            $reset_time = $this->Client->get_create_time($client_id);
        }

        if (strtotime($start_time) <= strtotime($reset_time))
        {
            $start_time = $reset_time;
        }

        if (strtotime($end_time) <= strtotime($reset_time))
        {
            $end_time = $reset_time;
        }
        // 获取begin_balance
        if ($start_time == $reset_time)
        {
            $begin_balance = $reset_balance;
        }
        else
        {
            $begin_balance = $this->Client->get_begin_balance($reset_time, $start_time, $client_id) + $reset_balance;
        }

        $data = array();
        $dur_records = $this->Client->get_client_ingress_balance_record($client_id, $start_time, $end_time);
        foreach ($dur_records as $item)
        {
            $data[$item[0]['time']][$item[0]['type']] = $item[0]['amount'];
        }
        $dur_records = $this->Client->get_client_egress_balance_record($client_id, $start_time, $end_time);
        foreach ($dur_records as $item)
        {
            $data[$item[0]['time']][$item[0]['type'] + 4] = $item[0]['amount'];
        }
        ksort($data);

        $this->set('begin_balance', $begin_balance);
        $this->set('data', $data);
    }

    public function clients_payment($type = 0)
    {
        if (isset($_GET['start_time']) && !empty($_GET['start_time']))
        {
            $start_time = $_GET['start_time'];
        }
        else
        {
            $start_time = date("Y-m-d 00:00:00");
        }

        if (isset($_GET['end_time']) && !empty($_GET['end_time']))
        {
            $end_time = $_GET['end_time'];
        }
        else
        {
            $end_time = date("Y-m-d 23:59:59");
        }
        $client_id = $_SESSION['sst_client_id'];
        $data = $this->Client->get_client_payment($client_id, $start_time, $end_time, $type);
        $this->set('data', $data);
        $this->set('start_time', $start_time);
        $this->set('end_time', $end_time);
        $this->set('type', $type);
    }

    public function clients_usage_report()
    {
        $this->loadModel('Cdr');
        $client_id = $_SESSION['sst_client_id'];
        if (isset($_GET['timezone']))
        {
            $gmt = $_GET['timezone'];
        }
        else
        {
            $gmt = "+00";
        }
        if (isset($_GET['start_date']))
        {
            $start_date = $_GET['start_date'] . ' ' . $_GET['start_time'];
        }
        else
        {
            $this->set('start_date', date("Y-m-d", strtotime('-30 days')));
            $start_date = date("Y-m-d 00:00:00", strtotime('-30 days'));
        }
        if (isset($_GET['stop_date']))
        {
            $end_date = $_GET['stop_date'] . ' ' . $_GET['stop_time'];
        }
        else
        {
            $end_date = date("Y-m-d 23:59:59");
        }


        if (isset($_GET['prefix']) && $_GET['prefix'] != 'all')
        {
            $prefix = $_GET['prefix'];
        }
        else
        {
            $prefix = NULL;
        }


        $start_time = $start_date . ' ' . $gmt;
        $end_time = $end_date . ' ' . $gmt;
        $report_max_time = $this->Cdr->get_report_maxtime($start_time, $end_time);
        $select_time_end = strtotime($end_time);
        $is_from_client_cdr = false;
        if (empty($report_max_time))
        {
            $is_from_client_cdr = true;
            $report_max_time = $start_time;
        }

        $prefixs = $this->Client->get_prefixs($client_id);

        $system_max_end = strtotime($report_max_time);
        if ($select_time_end > $system_max_end)
        {
            if ($is_from_client_cdr)
            {
                $data = $this->Client->get_client_cdr2($client_id, $report_max_time, $end_time, $prefix);
            }
            else
            {
                $data1 = $this->Client->get_client_cdr1($client_id, $start_time, $report_max_time, $prefix);
                $data = $this->Client->get_client_cdr2($client_id, $report_max_time, $end_time, $prefix);

                foreach ($data1 as $key1 => $item1)
                {
                    foreach ($item1[0] as $key2 => $item2)
                    {
                        $data[$key1][0][$key2] = $item2;
                    }
                }
            }
        }
        else
        {
            $data = $this->Client->get_client_cdr1($client_id, $start_date, $end_date, $prefix);
        }
        $this->set('data', $data);
        $this->set('prefixs', $prefixs);
    }

    public function reset_balance_panel($client_id)
    {
        Configure::write('debug', 0);
        $this->autoLayout = false;
        $this->set('client_id', $client_id);
    }

    function _filter_client_type()
    {
        $client_type = array_keys_value($this->params, 'url.filter_client_type');
        if ($client_type == Client::CLIENT_CLIENT_TYPE_INGRESS)
        {
            return "(select count(*) from resource where client_id=\"Client\".\"client_id\" and ingress=true)::float>0";
        }
        if ($client_type == Client::CLIENT_CLIENT_TYPE_EGRESS)
        {
            return "(select count(*) from resource where client_id=\"Client\".\"client_id\" and egress=true)::float>0";
        }
        return "";
    }

    function render_ingress_data()
    {
        $this->_render_index_bindModel();
        $this->_render_index_order();
        $this->_render_index_fields();
        $this->_render_index_conditions();
        $this->data = $this->paginate('Client');
    }

    function ingress()
    {
        $this->render_ingress_data();
    }

    function get_payment_term_type()
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;

        $result = false;
        $payment_term_id = $_POST['payment_term_id'];

        if ($payment_term_id) {
            $sql = "select type from payment_term where payment_term_id = $payment_term_id";
            $result = $this->Client->query($sql);
        }

        echo json_encode($result);
    }

    function _filter_search()
    {
        $search = array_keys_value($this->params, 'url.search');
        $client_type = array_keys_value($this->params, 'url.filter_client_type');
        if (!empty($search))
        {
            switch ($client_type)
            {
                case 'ingress':
                    return "Client.client_id in (select client_id from resource left join resource_ip on resource.resource_id = resource_ip.resource_id where resource.ingress = true and ( resource.alias ilike '%$search%' or resource_ip.ip::text ilike '%$search%'))";
                    break;
                case 'egress':
                    return "Client.client_id in (select client_id from resource left join resource_ip on resource.resource_id = resource_ip.resource_id where resource.egress = true and ( resource.alias ilike '%$search%' or resource_ip.ip::text ilike '%$search%'))";
                    break;
                case 'all':
                    return "Client.name ilike '%$search%' or Client.company ilike '%$search%' or Client.client_id in (select client_id from resource left join resource_ip on resource.resource_id = resource_ip.resource_id where resource_ip.ip::text ilike '%$search%' or resource.alias ilike '%$search%')";
            }
        }
        return "";
    }

    function ss_client_all()
    {
        $this->ss_client();
    }

    function client_options()
    {
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        $this->data = $this->Client->find('all');
    }

    function check_login($id = null)
    {
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        $conditions = Array();
        $count = 0;

        if (!empty($id))
        {
            if (!intval($id)) {
                $id = base64_decode($id);
            }
            
            $conditions[] = "client_id <> $id";

            $login = $this->_get('login');

            if (!empty($login))
            {
                $conditions['login'] = $login;
            }

            $count = $this->Client->find('count', Array('conditions' => $conditions));

            $sql = "select count(*) from users where name = '{$login}' and client_id <> $id";
            $result = $this->Client->query($sql);

            $count += $result[0][0]['count'];
        }

        if ($count > 0)
        {
            echo 'false';
        }
    }

    public function check_name($name = null, $client_id = null)
    {
        Configure::write('debug', 0);
        $ch_name = null;
        $this->layout = 'ajax';
        if (!empty($name))
        {
            $sql = "select count(*) as name_num from Client where name='$name'";
            if ($client_id !== null)
            {
                $sql .= " and client_id != {$client_id}";
            }
            $ch_name = $this->Client->query($sql);
        }
        if ($ch_name[0][0]['name_num'] > 0)
        {
            echo 'false';
        }
        else
        {
            echo 'true';
        }
    }

    public function select_naem($id = null)
    {
        if (!empty($id))
        {
            $sql = "select name from client where client_id=$id ";
            $list = $this->Client->query($sql);
            $this->set('name', $list[0][0]['name']);
        }
        else
        {
            $this->set('name', '');
        }
    }

    function down_client_cdr($id)
    {
        Configure::write('debug', 0);
        if (!empty($id))
        {
            $sql = "SELECT * FROM client_cdr".date("Ymd")." WHERE ingress_client_id={$id}::text";
            $this->Client->export__sql_data('download Cdr', $sql, 'cdr');
            $this->layout = 'csv';
            exit();
        }
        else
        {
            $this->redirect('/invoices/view/');
        }
    }

    function _render_wizard_impl_redirect($type = null)
    {
        $client_id = $this->Client->getlastinsertId();
        $this->Client->create_json_array('', 201, __('Client Save Success', true));
        $action = _filter_array(Array('egress' => 'add_resouce_egress', 'ingress' => 'add_resouce_ingress'), $type);
        if (Configure::read('project_name') == 'exchange')
        {
            $this->xredirect("/gatewaygroups/$action?query[id_clients]=$client_id&viewtype=wizard");
        }
        else
        {
            $this->xredirect("/prresource/gatewaygroups/$action?query[id_clients]=$client_id&viewtype=wizard");
        }
    }

    function _render_wizard_impl($type = null)
    {
        $flag = $this->_render_add_impl_save();
        if ($flag)
        {
            $this->_render_wizard_impl_redirect($type);
        }
    }

    function wizard($type = null)
    {
        Configure::write('debug', 2);
        $type = _filter_array(Array('ingress' => 'ingress', 'egress' => 'egress'), $this->_get('type'));
        if ($this->RequestHandler->isPost())
        {
            $this->_render_wizard_impl($type);
        }
        $this->_render_add_data();
    }

    public function accept_orderuser()
    {
        $this->layout = 'ajax';
        Configure::write('debug', 0);
        $id = $this->params ['pass'][0];

        $carrier_id = $this->Client->user_registration(intval($id));


        if ($carrier_id)
        {
            echo '{"res":"succ","info":"' . $carrier_id . '"}';
        }
        else
        {
            echo '{"res":"fail","info":"Regist Fail"}';
        }
    }

    function credit_view()
    {
        $this->pageTitle = "Configuration/Credit Application";

        $currPage = 1;
        $pageSize = 100;
        $search_arr = array();
        $order_arr = array();
        if (!empty($_REQUEST['order_by']))
        {
            $order_by = explode("-", $_REQUEST['order_by']);
            $order_arr[$order_by[0]] = $order_by[1];
        }

        if (!empty($_REQUEST['search']))
        {   //模糊查询
            $search_type = 0;
            $search_arr['search'] = !empty($_REQUEST['search']) ? $_REQUEST['search'] : '';
        }
        else
        {                      //按条件搜索
            $search_type = 1;
        }
//			
//			if (! empty ( $_REQUEST ['page'] )) {
//				$currPage = $_REQUEST ['page'];
//			}
//			
//			if (! empty ( $_REQUEST ['size'] )) {
//				$pageSize = $_REQUEST ['size'];
//			}
//		
        $temp = isset($_SESSION['paging_row']) ? $_SESSION['paging_row'] : 100;

        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = $temp : $pageSize = $_GET['size'];

        $_SESSION['paging_row'] = $pageSize;
        $results = $this->Client->ListCredit($currPage, $pageSize, $search_arr, $search_type, $order_arr);
        $this->set('p', $results);
    }

    function credit_detail($id = null)
    {
        $this->pageTitle = "View Credit Application";
        $id = empty($this->params['pass'][0]) ? null : $this->params['pass'][0];
        $id_decode = base64_decode($id);
        $this->_catch_exception_msg(array($this, '_add_credit_impl'), array('id' => $id_decode));
        $this->_render_credit_save_options();
        $this->render('credit_detail');
        $this->Session->write('m', Credit::set_validator());
    }

    function _add_credit_impl($params = array())
    {
        #post

        if ($this->RequestHandler->isPost())
        {
            $this->_create_or_update_credit_data($this->params['form']);
        }
        #get
        else
        {
            if (isset($params['id']) && !empty($params['id']))
            {
                $this->data = $this->Credit->find("first", Array('conditions' => array('Credit.id' => $params['id'])));
                if (empty($this->data))
                {
                    throw new Exception("Permission denied");
                }
                else
                {
                    $this->set('p', $this->data['Credit']);
                }
            }
            else
            {
                //void
            }
        }
    }

    function _create_or_update_credit_data($params = array())
    {
        if (isset($params['id']) && !empty($params['id']))
        {
            $id = (int) $params ['id'];
            $this->data ['Credit'] ['id'] = $id;
//            $credit_old = $this->Credit->query("select * from credit_application where id = " . $this->data['Credit']['id']);
            if ($this->Credit->save($this->data))
            {
                //$this->Credit->create_json_array('',201,'Credit , Edit successfullyfully');
                $this->Credit->create_json_array('', 201, 'The Credit is modified successfully!');
                $this->xredirect('/clients/credit_view');
                //	$this->redirect ( array ('id' => $id ) );
            }
        }
        # add
        else
        {
            //void
        }
    }

    function _render_credit_save_options()
    {
        $this->loadModel('Credit');
        $this->set('CreditList', $this->Credit->find('all')); //,Array('fields'=>Array('id','name'))));
    }

    function get_static_item($id)
    {
        Configure::write('debug', 0);
        $this->autoRender = FALSE;
        $this->autoLayout = FALSE;
        $product_items = array();
        $product_items_result = $this->Client->query("select item_id,product_id,digits, strategy, time_profile_id from product_items where item_id = {$id}");
        $product_items['item'] = $product_items_result[0][0];
        $product_resource_result = $this->Client->query("
            select product_items_resource.id, product_items_resource.resource_id, product_items_resource.by_percentage,resource.client_id
            from product_items_resource left join 
            resource on product_items_resource.resource_id = resource.resource_id where item_id = {$id} order by product_items_resource.id asc");
        $len = count($product_resource_result);
        $product_items['len'] = $len;
        foreach ($product_resource_result as $val)
        {
            $product_items['resource'][] = $val[0];
        }
        echo json_encode($product_items);
    }

    function addstatictable_edit()
    {
        Configure::write('debug', 0);
        $this->autoLayout = FALSE;
        $this->autoRender = FALSE;
        $sql = "update product_items set product_id = {$_POST['product_id']}, digits = '{$_POST['prefix']}', 
                    strategy = {$_POST['strategy']}, time_profile_id = {$_POST['profile']}
                    where item_id = {$_POST['id']}";
        $this->Client->query($sql);
        $this->Client->query("delete from product_items_resource where item_id = {$_POST['id']}");
        $count = count($_POST['trunks']);
        for ($i = 0; $i < $count; $i++)
        {
            if ($_POST['strategy'] == 0)
            {
                $sql = "insert into product_items_resource (item_id, resource_id, by_percentage)
                        values ({$_POST['id']}, {$_POST['trunks'][$i]}, {$_POST['percents'][$i]})";
            }
            else
            {
                $sql = "insert into product_items_resource (item_id, resource_id)
                            values ({$_POST['id']}, {$_POST['trunks'][$i]})";
            }
            $this->Client->query($sql);
        }
        echo 1;
    }

    function addratetable()
    {
        Configure::write('debug', 0);
        $this->autoLayout = FALSE;
        $code_deck_result = $this->Client->query("select code_deck_id as id, name from code_deck");
        $currency_result = $this->Client->query("select currency_id as id, code as name from currency where active = true");
        $jurcountry_result = $this->Client->query("select id, name from jurisdiction_country order by name");
        $profile_result = $this->Client->query("select time_profile_id as id, name from time_profile order by name asc");
        $this->set("code_deck_result", $code_deck_result);
        $this->set("currency_result", $currency_result);
        $this->set("jurcountry_result", $jurcountry_result);
        $this->set("profile_result", $profile_result);
    }

    function addratetable_first()
    {
        Configure::write('debug', 0);
        $this->autoLayout = FALSE;
        $this->autoRender = FALSE;
        $check_name = $this->Client->query("select count(*) from rate_table where name = '{$_POST['name']}'");
        if (!$_POST['name'])
        {
            echo 'namenone';
            return;
        }
        if ($check_name[0][0]['count'] > 0)
        {
            echo 0;
            return;
        }
        if (!isset($_POST['codedeck']) || $_POST['codedeck'] == '')
        {
            $_POST['codedeck'] = 'NULL';
        }
        $jurcountry = $_POST['jurcountry'] ? $_POST['jurcountry'] : 'null';
        $add_ratetable_result = $this->Client->query("insert into rate_table(name,code_deck_id,currency_id,jurisdiction_country_id, rate_type)
                values ('{$_POST['name']}', {$_POST['codedeck']}, {$_POST['currency']}, {$jurcountry}, {$_POST['ratetype']}) 
                RETURNING rate_table_id");
        echo $add_ratetable_result[0][0]['rate_table_id'];
    }

    function addratetable_second($id)
    {
        Configure::write('debug', 0);
        $this->autoLayout = FALSE;
        $this->autoRender = FALSE;
        $len = count($_POST['code']);
        for ($i = 0; $i < $len; $i++)
        {
            $rate = $_POST['rate'][$i] ? $_POST['rate'][$i] : 0;
            $intrarate = $_POST['intrarate'][$i] ? $_POST['intrarate'][$i] : 'null';
            $interrate = $_POST['interrate'][$i] ? $_POST['interrate'][$i] : 'null';
            $enddate = $_POST['endate'][$i] ? "'" + $_POST['endate'][$i] + "'" : 'null';
            $profile = $_POST['profile'][$i] ? $_POST['profile'][$i] : 'null';
            $this->Client->query("insert into rate 
                (rate_table_id,code,code_name,country,rate,intra_rate,inter_rate,effective_date,end_date,time_profile_id,zone,min_time,interval)
                values
                ({$id}, '{$_POST['code'][$i]}', '{$_POST['codename'][$i]}', '{$_POST['country'][$i]}',{$rate},
                {$intrarate}, {$interrate}, '{$_POST['effectdate'][$i]}', {$enddate},
                {$profile}, '{$_POST['timezone'][$i]}', {$_POST['min_time'][$i]}, {$_POST['interval'][$i]})");
        }
    }

//生成pdf	
    public function createpdf_credit($id)
    {
        ob_start();
        Configure::write('debug', 0);
        $this->autoLayout = FALSE;
        $this->autoRender = FALSE;
        App::import("Model", 'Credit');
        $credit_model = new Credit;
        $num_format = empty($this->params['pass'][1]) ? 5 : intval($this->params['pass'][1]);
        $id_decode = base64_decode($id);
        $html = $credit_model->generate_pdf_content($id_decode, $num_format);
        App::import("Vendor", "tcpdf", array('file' => "tcpdf/pdf.php"));
        $credit_pdf = create_PDF("credit", $html);
        ob_end_flush();
        return $credit_pdf;
        //echo $html;
    }

    public function set_balance()
    {
        //Configure::write('debug', 0);
        $this->autoLayout = FALSE;
        $this->autoRender = FALSE;
        $ingress_balance = $_POST['ingress_balance'];
        $egress_balance = $_POST['egress_balance'];
        $begin_time = $_POST['begin_time'];
        $client_id = $_POST['client_id'];
        $description = $_POST['description'];
        $client_name_info = $this->Client->query("select name from client where client_id = {$client_id}");
        $client_name = $client_name_info[0][0]['name'];
        /*
          if ($reset_type == '0')
          {
          // Mutual
          $sql = "INSERT INTO client_payment (result, receiving_time, amount, client_id, description, payment_type, payment_time, egress_amount)
          VALUES(true, CURRENT_TIMESTAMP, {$ingress_balance}, {$client_id}, '{$description}', 13, '{$begin_time}', {$egress_balance})";
          $this->Client->query($sql);
          $this->Client->logging(0, 'Mutual Balance', "Reset Balace:{$client_name},Ingress:[{$ingress_balance}], Egress{$egress_balance}");
          } else
          {
          // Actual
          $balance = $ingress_balance + $egress_balance;
          $sql = "UPDATE client_balance SET ingress_balance = '{$ingress_balance}' ,egress_balance = '{$egress_balance}' , balance = '{$balance}' WHERE client_id = '{$client_id}' RETURNING CURRENT_TIMESTAMP";
          $end_time_result = $this->Client->query($sql);
          $sql = "INSERT INTO client_payment (result, receiving_time, amount, client_id, description, payment_type, payment_time, egress_amount)
          VALUES(true, CURRENT_TIMESTAMP, {$ingress_balance}, {$client_id}, '{$description}', 14, '{$begin_time}', {$egress_balance})";
          $this->Client->query($sql);
          $end_time = $end_time_result[0][0]['now'];
          $this->_total_actual_balance($client_id, $begin_time, $end_time);
          $this->Client->logging(0, 'Actual Balance', "Reset Balace:{$client_name},Ingress:[{$ingress_balance}], Egress{$egress_balance}");
          }
         * 
         */

        $sql = "INSERT INTO client_payment (result, receiving_time, amount, client_id, description, payment_type, payment_time, egress_amount)
            VALUES(true, CURRENT_TIMESTAMP, {$ingress_balance}, {$client_id}, '{$description}', 13, '{$begin_time}', {$egress_balance})";
        $this->Client->query($sql);
        $this->Client->logging(0, 'Reset Balance', "Client:{$client_name}, Ingress Balance:[{$ingress_balance}], Egress Balance:[{$egress_balance}]");


        echo 1;
    }

    public function _total_actual_balance($client_id, $begin_time, $end_time)
    {
        $sql = "SELECT (SELECT COALESCE(sum(amount), 0) 
FROM client_payment 
WHERE payment_type = 5 AND payment_time BETWEEN '$begin_time' AND '$end_time' AND client_id = '$client_id')
-
(SELECT COALESCE(sum(ingress_client_cost::numeric(10,4)), 0) FROM client_cdr 
WHERE ingress_client_id = '$client_id'  AND time BETWEEN '$begin_time' AND '$end_time')
+
(SELECT COALESCE(sum(amount), 0) 
FROM client_payment 
WHERE payment_type = 8 AND payment_time BETWEEN '$begin_time' AND '$end_time' AND client_id = '$client_id')
-
(SELECT COALESCE(sum(amount), 0) 
FROM client_payment 
WHERE payment_type = 12 AND payment_time BETWEEN '$begin_time' AND '$end_time' AND client_id = '$client_id') AS ingress";

        $ingress_result = $this->Client->query($sql);
        $ingress_amount = $ingress_result[0][0]['ingress'];
        $sql = "SELECT 
(SELECT COALESCE(sum(egress_cost::numeric(10,4)), 0) FROM client_cdr 
WHERE egress_client_id = '$client_id'  AND time BETWEEN '$begin_time' AND '$end_time')
-
(SELECT COALESCE(sum(amount), 0) 
FROM client_payment 
WHERE payment_type = 6 AND payment_time BETWEEN '$begin_time' AND '$end_time' AND client_id = '$client_id')
-
(SELECT COALESCE(sum(amount), 0) 
FROM client_payment 
WHERE payment_type = 7 AND payment_time BETWEEN '$begin_time' AND '$end_time' AND client_id = '$client_id')
+
(SELECT COALESCE(sum(amount), 0) 
FROM client_payment 
WHERE payment_type = 11 AND payment_time BETWEEN '$begin_time' AND '$end_time' AND client_id = '$client_id') AS egress";
        $egress_result = $this->Client->query($sql);
        $egress_amount = $egress_result[0][0]['egress'];
        $total_amount = $ingress_amount + $egress_amount;
        $sql = "INSERT INTO client_balance_operation_action (balance, ingress_balance, egress_balance, client_id, action) VALUES($total_amount, $ingress_amount, $egress_amount, '{$client_id}', 1)";
        $this->Client->query($sql);
    }

    public function admin_login()
    {
        Configure::write('debug', 2);
        $this->autoLayout = FALSE;
        $this->autoRender = FALSE;
        $admin_login_key = time();
        $sql = "update order_user set admin_login_key ='" . md5($admin_login_key) . "' where client_id = " . $_GET['client_id'];
        $this->Client->query($sql);
        $location = Configure::read('admin_login');
        header('Location:' . $location . '?client_id=' . $_GET['client_id'] . '&login_key=' . $admin_login_key);
    }

    function new_registration()
    {
        $this->pageTitle = 'Management/New Registration';

        $where = " create_time::date=current_date ";

        //var_dump($_GET);

        if (isset($_GET['search']))
        {
            $name = $_GET['search'];
            $start_date = $_GET['start_date'];
            $end_date = $_GET['stop_date'];
            $tz = $_GET['tz'];

            if (!empty($name) && $name != 'Search')
            {
                $where = "create_time between '{$start_date} {$tz}' and  '{$end_date} {$tz}' and order_user.name like '%{$name}%'";
            }
            else
            {
                $where = "create_time between '{$start_date} {$tz}' and  '{$end_date} {$tz}'";
            }
        }

        $sql = "select count(*) 
from order_user where client_id in (SELECT client_id::integer from c4_client_balance where {$where})";


        $temp = isset($_SESSION['paging_row']) ? $_SESSION['paging_row'] : 100;
        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = $temp : $pageSize = $_GET['size'];
        $_SESSION['paging_row'] = $pageSize;


        $count = $this->Client->query($sql);
        $count = $count[0][0]['count'];
        require_once 'MyPage.php';
        $page = new MyPage ();
        $page->setTotalRecords($count);
        $page->setCurrPage($currPage);
        $page->setPageSize($pageSize);
        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;

        $order_sql = "order by create_time desc";

        if ($this->isnotEmpty($this->params['url'], array('order_by')))
        {
            $order_by = $this->params['url']['order_by'];
            $order_arr = explode('-', $order_by);
            if (count($order_arr) == 2)
            {
                $field = $order_arr[0];
                $sort = $order_arr[1];
                $order_sql = "ORDER BY {$field} {$sort}";
            }
        }

        $sql = "select (select create_time from c4_client_balance where client_balance.client_id::integer =order_user.client_id ) as create_time
        ,order_user.client_id, order_user.company_name,order_user.name,order_user.corporate_contact_email,order_user.last_login_time 
from order_user where client_id in (SELECT client_id::integer from c4_client_balance where {$where}) {$order_sql}   LIMIT {$pageSize} OFFSET {$offset}";

        //echo $sql;
        $data = $this->Client->query($sql);
        $page->setDataArray($data);
        $this->set('p', $page);

        $sql = "select (select create_time from c4_client_balance where client_balance.client_id::integer =order_user.client_id ) as create_time
        ,order_user.client_id, order_user.company_name,order_user.name,order_user.corporate_contact_email,order_user.last_login_time  
from order_user where client_id in (SELECT client_id::integer from c4_client_balance where {$where}) {$order_sql}  ";

        $data = $this->Client->query($sql);

        if (!empty($_GET['out_put']) && $_GET['out_put'] == 'csv')
        {
            $file_name = "registration_report_{$start_date}_{$end_date}.csv";
            $file_name = str_ireplace(' ', '_', $file_name);
            ob_clean();
            header("Pragma: public");
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Content-Type: text/csv");
            header("Content-Type: application/octet-stream");
            header("Content-Type: application/download");
            ;
            header("Content-Disposition: attachment;filename={$file_name}");
            header("Content-Transfer-Encoding: binary ");
            echo "Registration Time" . ",";
            echo "Company" . ",";
            echo "Name" . ",";
            echo "Last Login Time" . ",";
            echo "Email";

            echo "\n";
            foreach ($data as $value)
            {
                echo $value[0]['create_time'] . ",";
                echo $value[0]['company_name'] . ",";
                echo $value[0]['name'] . ",";
                echo $value[0]['last_login_time'] . ",";
                echo $value[0]['corporate_contact_email'];
                echo "\n";
            }
            exit();
        }
        else if (!empty($_GET['out_put']) && $_GET['out_put'] == 'xls')
        {
            $file_name = "registration_report_{$start_date}_{$end_date}.xls";
            $file_name = str_ireplace(' ', '_', $file_name);
            ob_clean();
            header("Pragma: public");
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Content-Type: application/force-download");
            header("Content-Type: application/octet-stream");
            header("Content-Type: application/download");
            ;
            header("Content-Disposition: attachment;filename={$file_name}");
            header("Content-Transfer-Encoding: binary ");
            echo "Registration Time" . "	";
            echo "Company" . "	";
            echo "Name" . "	";
            echo "Last Login Time" . "	";
            echo "Email";

            echo "\n";
            foreach ($data as $value)
            {
                echo $value[0]['create_time'] . "	";
                echo $value[0]['company_name'] . "	";
                echo $value[0]['name'] . "	";
                echo $value[0]['last_login_time'] . "	";
                echo $value[0]['corporate_contact_email'] . "	";
                echo "\n";
            }
            exit();
        }
    }

    function new_login()
    {
        Configure::write('debug', 0);
        $this->pageTitle = 'Management/New Login';

        $where = " last_login_time::date=current_date ";

        //var_dump($_GET);
        $start_date = date("Y-m-d 00:00:00");
        $end_date = date("Y-m-d 23:59:59");
        if (isset($_GET['search']))
        {
            $name = $_GET['search'];
            $start_date = $_GET['start_date'];
            $end_date = $_GET['stop_date'];
            $tz = $_GET['tz'];

            if (!empty($name) && $name != 'Search')
            {
                $where = "last_login_time between '{$start_date} {$tz}' and  '{$end_date} {$tz}' and order_user.name like '%{$name}%'";
            }
            else
            {
                $where = "last_login_time between '{$start_date} {$tz}' and  '{$end_date} {$tz}'";
            }
        }


        $sql = "select
                count(*)
                from order_user
                where {$where}";


        $temp = isset($_SESSION['paging_row']) ? $_SESSION['paging_row'] : 100;
        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = $temp : $pageSize = $_GET['size'];
        $_SESSION['paging_row'] = $pageSize;


        $count = $this->Client->query($sql);
        $count = $count[0][0]['count'];
        require_once 'MyPage.php';
        $page = new MyPage ();
        $page->setTotalRecords($count);
        $page->setCurrPage($currPage);
        $page->setPageSize($pageSize);
        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;

        $order_sql = "order by last_login_time desc";

        if ($this->isnotEmpty($this->params['url'], array('order_by')))
        {
            $order_by = $this->params['url']['order_by'];
            $order_arr = explode('-', $order_by);
            if (count($order_arr) == 2)
            {
                $field = $order_arr[0];
                $sort = $order_arr[1];
                $order_sql = "ORDER BY {$field} {$sort}";
            }
        }

        $sql = "select order_user.last_login_time,
                order_user.company_name,order_user.name,order_user.corporate_contact_email
                from order_user
                where {$where} {$order_sql} LIMIT {$pageSize} OFFSET {$offset}";

        $data = $this->Client->query($sql);
        $page->setDataArray($data);
        $this->set('p', $page);

        $sql = "select order_user.last_login_time,
                order_user.company_name,order_user.name,order_user.corporate_contact_email
                from order_user
                where {$where} {$order_sql} ";

        $data = $this->Client->query($sql);

        if (!empty($_GET['out_put']) && $_GET['out_put'] == 'csv')
        {
            $file_name = "login_report_{$start_date}_{$end_date}.csv";
            $file_name = str_ireplace(' ', '_', $file_name);
            ob_clean();
            header("Pragma: public");
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Content-Type: text/csv");
            header("Content-Type: application/octet-stream");
            header("Content-Type: application/download");
            ;
            header("Content-Disposition: attachment;filename={$file_name}");
            header("Content-Transfer-Encoding: binary ");
            echo "Time" . ",";
            echo "Company" . ",";
            echo "Name" . ",";
            echo "Email";

            echo "\n";
            foreach ($data as $value)
            {
                echo $value[0]['last_login_time'] . ",";
                echo $value[0]['company_name'] . ",";
                echo $value[0]['name'] . ",";
                echo $value[0]['corporate_contact_email'];
                echo "\n";
            }
            exit();
        }
        else if (!empty($_GET['out_put']) && $_GET['out_put'] == 'xls')
        {
            $file_name = "login_report_{$start_date}_{$end_date}.xls";
            $file_name = str_ireplace(' ', '_', $file_name);
            ob_clean();
            header("Pragma: public");
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Content-Type: application/force-download");
            header("Content-Type: application/octet-stream");
            header("Content-Type: application/download");
            ;
            header("Content-Disposition: attachment;filename={$file_name}");
            header("Content-Transfer-Encoding: binary ");
            echo "Time" . "	";
            echo "Company" . "	";
            echo "Name" . "	";
            echo "Email";

            echo "\n";
            foreach ($data as $value)
            {
                echo $value[0]['last_login_time'] . "	";
                echo $value[0]['company_name'] . "	";
                echo $value[0]['name'] . "	";
                echo $value[0]['corporate_contact_email'] . "	";
                echo "\n";
            }
            exit();
        }
    }

    function new_buy_order()
    {
        $this->pageTitle = 'Management/New Buy Order';

        $where = " request_time::date=current_date ";
        $where1 = '1 = 1';
        //var_dump($_GET);

        if (isset($_GET['search']))
        {
            $name = $_GET['search'];
            $start_date = $_GET['start_date'];
            $end_date = $_GET['stop_date'];
            $tz = $_GET['tz'];


            $where = "request_time between '{$start_date} {$tz}' and  '{$end_date} {$tz}'";

            if (!empty($name) && $name != 'Search')
            {
                $where1 = "client.name like '%{$name}%'";
            }
        }

//        $sql = "select
//                count(*)
//                from
//                (
//                SELECT
//                client_route_request.client_id,asr,acd,client_route_request_code.code_name,rate
//
//                from client_route_request left join client_route_request_code on
//                client_route_request.id=client_route_request_code.request_id where
//                {$where}
//                ) as t
//                left join client
//                on client.client_id=t.client_id where {$where1}";


        $temp = isset($_SESSION['paging_row']) ? $_SESSION['paging_row'] : 100;
        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = $temp : $pageSize = $_GET['size'];
        $_SESSION['paging_row'] = $pageSize;

//        $count = $this->Client->query($sql);
//        $count = $count[0][0]['count'];
        $count = 0;
        require_once 'MyPage.php';
        $page = new MyPage ();
        $page->setTotalRecords($count);
        $page->setCurrPage($currPage);
        $page->setPageSize($pageSize);
        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;

        $order_sql = "order by request_time desc";

        if ($this->isnotEmpty($this->params['url'], array('order_by')))
        {
            $order_by = $this->params['url']['order_by'];
            $order_arr = explode('-', $order_by);
            if (count($order_arr) == 2)
            {
                $field = $order_arr[0];
                $sort = $order_arr[1];
                $order_sql = "ORDER BY {$field} {$sort}";
            }
        }

//        $sql = "select
//                client.name,t.code_name,t.rate,t.asr,t.acd,t.request_time
//                from
//                (
//                SELECT
//                client_route_request.request_time,client_route_request.client_id,asr,acd,client_route_request_code.code_name,rate
//
//                from client_route_request left join client_route_request_code on
//                client_route_request.id=client_route_request_code.request_id where
//                {$where} order by request_time desc
//                ) as t
//                left join client
//                on client.client_id=t.client_id where {$where1} {$order_sql} LIMIT {$pageSize} OFFSET {$offset}";
//
//        $data = $this->Client->query($sql);
        $data = array();
        $page->setDataArray($data);
        $this->set('p', $page);
    }

    function new_sell_order()
    {
        $this->pageTitle = 'Management/New Sell Order';

        $start_date = date("Y-m-d 00:00:00");
        $end_date = date("Y-m-d 23:59:59");
        $tz = "+0000";

        $where1 = "1 = 1";
        if (isset($_GET['search']))
        {
            $name = $_GET['search'];
            $start_date = $_GET['start_date'];
            $end_date = $_GET['stop_date'];
            $tz = $_GET['tz'];


            //$where = "request_time between '{$start_date} {$tz}' and  '{$end_date} {$tz}'";

            if (!empty($name) && $name != 'Search')
            {
                $where1 = "client_name like '%{$name}%'";
            }
        }


        $sql = "select count(*) from (select * from (select (select name from client where client_id=t2.client_id) as client_name, t2.client_id,t1.rate_table_id,t1.flag,t1.total
from
(SELECT rate_table_id,flag,count(*) as total from rate_record
where time between extract(epoch from '{$start_date} {$tz}'::timestamp with time zone) and extract(epoch from '{$end_date} {$tz}'::timestamp with time zone)
group by rate_table_id,flag) as t1
left join
(SELECT client_id,rate_table_id from resource where egress=true) as t2
on t1.rate_table_id=t2.rate_table_id
where t2.client_id is not null) as result where {$where1} ) as sell_order";


        $temp = isset($_SESSION['paging_row']) ? $_SESSION['paging_row'] : 100;
        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = $temp : $pageSize = $_GET['size'];
        $_SESSION['paging_row'] = $pageSize;

        $count = $this->Client->query($sql);
        $count = $count[0][0]['count'];
        require_once 'MyPage.php';
        $page = new MyPage ();
        $page->setTotalRecords($count);
        $page->setCurrPage($currPage);
        $page->setPageSize($pageSize);
        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;

        $order_sql = "order by client_name asc";

        if ($this->isnotEmpty($this->params['url'], array('order_by')))
        {
            $order_by = $this->params['url']['order_by'];
            $order_arr = explode('-', $order_by);
            if (count($order_arr) == 2)
            {
                $field = $order_arr[0];
                $sort = $order_arr[1];
                $order_sql = "ORDER BY {$field} {$sort}";
            }
        }

        $sql = "select * from (select (select name from client where client_id=t2.client_id) as client_name, t2.client_id,t1.rate_table_id,t1.flag,t1.total
from
(SELECT rate_table_id,flag,count(*) as total from rate_record
where time between extract(epoch from '{$start_date} {$tz}'::timestamp with time zone) and extract(epoch from '{$end_date} {$tz}'::timestamp with time zone)
group by rate_table_id,flag) as t1
left join
(SELECT client_id,rate_table_id from resource where egress=true) as t2
on t1.rate_table_id=t2.rate_table_id
where t2.client_id is not null) as result where {$where1} {$order_sql} LIMIT {$pageSize} OFFSET {$offset}";


        //echo $sql;
        $data = $this->Client->query($sql);
        $this->set('start_date', "{$start_date} {$tz}");
        $this->set('end_date', "{$end_date} {$tz}");
        //echo $count;
        //var_dump($data);

        $page->setDataArray($data);
        $this->set('p', $page);
    }

    function new_sell_order_records()
    {
        $this->pageTitle = 'Management/New Sell Order Records';

        $rate_table_id = $_GET['rate_table_id'];
        $flag = $_GET['flag'];
        $start_date = $_GET['start_date'];
        $end_date = $_GET['end_date'];


        $sql = "select count(*) from (SELECT time, country,code_name,code,rate from rate_record
where time between extract(epoch from '$start_date'::timestamp with time zone) and extract(epoch from '$end_date'::timestamp with time zone)
and rate_table_id={$rate_table_id} and flag='$flag') as sell_order";


        $temp = isset($_SESSION['paging_row']) ? $_SESSION['paging_row'] : 100;
        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = $temp : $pageSize = $_GET['size'];
        $_SESSION['paging_row'] = $pageSize;

        $count = $this->Client->query($sql);
        $count = $count[0][0]['count'];
        require_once 'MyPage.php';
        $page = new MyPage ();
        $page->setTotalRecords($count);
        $page->setCurrPage($currPage);
        $page->setPageSize($pageSize);
        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;

        $sql = "SELECT time, country,code_name,code,rate from rate_record
where time between extract(epoch from '$start_date'::timestamp with time zone) and extract(epoch from '$end_date'::timestamp with time zone)
and rate_table_id={$rate_table_id} and flag='$flag' LIMIT {$pageSize} OFFSET {$offset}";



        //echo $sql;
        $data = $this->Client->query($sql);

        //echo $count;
        //var_dump($data);

        $page->setDataArray($data);
        $this->set('p', $page);
    }

    function transaction($client_id = null)
    {
        $today_date = date("Ymd");
        if (!empty($client_id))
        {
            $start_date = date("Y-m-d 00:00:00O", strtotime('-6 day'));
            $end_date = date("Y-m-d 23:59:59O");
            $cdr_start_date = date("Y-m-d 00:00:00O");
            $cdr_end_date = date("Y-m-d 23:59:59O");
            $this->pageTitle = 'Management/Transaction';

            $sql = <<<EOT
            select count(*) from (select * from (select * from transaction 
            where date >= '{$start_date}' and date <= '{$end_date}' 
            and client_id = {$client_id} and coalesce(buy, sell, wire_in, wire_out, bod_balance, 0) > 0 
            union select 0 as id, client_id::integer, (select sum(ingress_client_cost::numeric) 
            from client_cdr{$today_date} where time >= '{$cdr_start_date}' and time <= '{$cdr_end_date}' 
            and ingress_client_id = c4_client_balance.client_id) as buy, (select sum(egress_cost::numeric)
            from client_cdr{$today_date} where time >= '{$cdr_start_date}' and time <= '{$cdr_end_date}' 
            and egress_client_id = c4_client_balance.client_id) as sell, balance::numeric as bod_balance, '{$cdr_end_date}' as date 
            from c4_client_balance where client_id::integer = {$client_id}) as tran order by date ASC ) as ordercount
EOT;


            $temp = isset($_SESSION['paging_row']) ? $_SESSION['paging_row'] : 100;
            empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
            empty($_GET['size']) ? $pageSize = $temp : $pageSize = $_GET['size'];
            $_SESSION['paging_row'] = $pageSize;

            $count = $this->Client->query($sql);
            $count = $count[0][0]['count'];
            require_once 'MyPage.php';
            $page = new MyPage ();
            $page->setTotalRecords($count);
            $page->setCurrPage($currPage);
            $page->setPageSize($pageSize);
            $currPage = $page->getCurrPage() - 1;
            $pageSize = $page->getPageSize();
            $offset = $currPage * $pageSize;



            $sql = "select * from (select * from transaction 
                    where date >= '{$start_date}' and date <= '{$end_date}' 
                    and client_id = {$client_id} and coalesce(buy, sell, wire_in, wire_out, bod_balance, 0) > 0 
                    union select 0 as id, client_id::integer, (select sum(ingress_client_cost::numeric) 
                    from client_cdr{$today_date} where time >= '{$cdr_start_date}' and time <= '{$cdr_end_date}' 
                    and ingress_client_id = c4_client_balance.client_id) as buy, (select sum(egress_cost::numeric)
                    from client_cdr{$today_date} where time >= '{$cdr_start_date}' and time <= '{$cdr_end_date}' 
                    and egress_client_id = c4_client_balance.client_id) as sell, balance::numeric as bod_balance, '{$cdr_end_date}' as date 
                    from c4_client_balance where client_id::integer = {$client_id}) as tran order by date ASC LIMIT {$pageSize} OFFSET {$offset}";

            $data = $this->Client->query($sql);
            $page->setDataArray($data);
            $this->set('p', $page);
        }
        else
        {
            
        }
    }

    public function registration()
    {
        $counties = $this->Client->query("SELECT DISTINCT country FROM code where country != '' ORDER BY country ASC");
        $this->set('counties', $counties);
        $transaction_fees = $this->Client->query("SELECT * FROM transaction_fee  ORDER BY id ASC");
        $this->set('transaction_fees', $transaction_fees);
        if (!empty($_POST))
        {
            $client_id = $this->Clients->insert_client();
            //$client_id = 1398;
            $res2 = $this->Orderuser->insert_user($client_id);

            if (is_array($res2) && (count($res2) == 1))
            {
                $this->Client->create_json_array('#ClientOrigRateTableId', 201, __('Sucessful Registration!', true));
                //创建Code Deck
                $this->Clients->insert_codedeck($client_id);

                //创建banlance
                $this->Clients->create_balance($client_id);

                //创建creditapplication
                $this->Clients->create_credit($client_id);
                $this->xredirect('/users/registration');
            }
            else
            {
                $this->Client->create_json_array('', 101, __('Fail Registration!', true));
            }
        }
    }

    public function check_username($username)
    {
        $this->autoLayout = FALSE;
        $this->autoRender = FALSE;
        Configure::write('debug', 0);
        ob_clean();
        if (!empty($username))
        {
            $res1 = $this->Client->query("select * from order_user where name = '{$username}'");
            if (count($res1) > 0)
            {
                echo "no";
            }
            else
            {
                echo "yes";
            }
        }
    }

    public function check_email($email, $client_id)
    {
        $this->autoLayout = FALSE;
        $this->autoRender = FALSE;
        Configure::write('debug', 0);
        ob_clean();
        if (!empty($email))
        {
//            $res1 = $this->Client->query("select * from order_user where corporate_contact_email = '{$email}' and client_id != {$client_id}");
//            $res2 = $this->Client->query("select * from order_user_alert where email = '{$email}' and client_id != {$client_id}");
//            if (count($res1) > 0 || count($res2) > 0)
//            {
//                echo "no";
//            }
//            else
//            {
//                echo "yes";
//            }
        }
    }

    public function check_add_email($email)
    {
        $this->autoLayout = FALSE;
        $this->autoRender = FALSE;
        Configure::write('debug', 0);
        ob_clean();
        if (!empty($email))
        {
//            $res1 = $this->Client->query("select * from order_user where corporate_contact_email = '{$email}' ");
//            $res2 = $this->Client->query("select * from order_user_alert where email = '{$email}' ");
//            if (count($res1) > 0 || count($res2) > 0)
//            {
//                echo "no";
//            }
//            else
//            {
//                echo "yes";
//            }
        }
    }

    public function product_list()
    {

        Configure::write('debug', 0);
        $this->pageTitle = 'Management/Product';

        $where = " 1=1  ";


        if (isset($_GET['search']))
        {
            $name = $_GET['search'];

            if (!empty($name) && $name != 'Search')
            {
                $where = "product_name like '%{$name}%'";
            }
        }


        $sql = "select
                count(*)
                from product_rout_rate_table
                where {$where}";


        $temp = isset($_SESSION['paging_row']) ? $_SESSION['paging_row'] : 100;
        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = $temp : $pageSize = $_GET['size'];
        $_SESSION['paging_row'] = $pageSize;


        $count = $this->Client->query($sql);
        $count = $count[0][0]['count'];
        require_once 'MyPage.php';
        $page = new MyPage ();
        $page->setTotalRecords($count);
        $page->setCurrPage($currPage);
        $page->setPageSize($pageSize);
        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;

        $order_sql = "order by product_name desc";

        if ($this->isnotEmpty($this->params['url'], array('order_by')))
        {
            $order_by = $this->params['url']['order_by'];
            $order_arr = explode('-', $order_by);
            if (count($order_arr) == 2)
            {
                $field = $order_arr[0];
                $sort = $order_arr[1];
                $order_sql = "ORDER BY {$field} {$sort}";
            }
        }

        $sql = "select product_rout_rate_table.*,route_strategy.name as rout_name
                ,rate_table.name as rate_table_name
                from product_rout_rate_table
                left join route_strategy
                on route_strategy.route_strategy_id = product_rout_rate_table.rout_id
                left join rate_table
                on rate_table.rate_table_id = product_rout_rate_table.rate_table_id
                where {$where} {$order_sql} LIMIT {$pageSize} OFFSET {$offset}";

        $data = $this->Client->query($sql);
        $page->setDataArray($data);
        $this->set('p', $page);


        $rout_list = $this->Gatewaygroup->find_route_strategy();
        $this->set('rout_list', $rout_list);

        $rate_table = $this->Gatewaygroup->find_rate_table();
        $this->set("rate_table", $rate_table);

        //var_dump($rout_list);
        //var_dump($rate_table);
    }

    public function add_product()
    {
        $this->autoLayout = FALSE;
        $this->autoRender = FALSE;
        Configure::write('debug', 0);
        ob_clean();
        $rout_id = $_POST['rout_id'];
        $rate_table_id = $_POST['rate_table_id'];
        $product_name = $_POST['product_name'];

        if (!empty($product_name))
        {
            $res = $this->Client->query("select count(*) from product_rout_rate_table  where product_name = '{$product_name}' or (rout_id = {$rout_id} and rate_table_id = {$rate_table_id})");

            if ($res[0][0]['count'] != 0)
            {
                echo 'isHave';
            }
            else
            {
                $this->Client->query("insert into product_rout_rate_table (product_name,rout_id,rate_table_id) values ('{$product_name}',{$rout_id} ,{$rate_table_id})");
                $this->Client->logging(0, 'Product', "Product Name:{$product_name}");
                $this->Client->create_json_array('', 201, __('Sucessful!', true));
                echo "success";
            }
        }
        else
        {
            echo 'isEmpty';
        }
    }

    public function del_product($id, $product_name)
    {
        Configure::write('debug', 0);

        $res = $this->Client->query("select count(*) from resource_prefix where resource_id in (select resource_id from resource where product_id = {$id} and service_type = 1) ");

        if ($res[0][0]['count'] != 0)
        {
            $this->Client->create_json_array('', 101, __('The Product[%s] has been used, can not be deleted', true, $product_name));
        }
        else
        {
            $this->Client->query("delete from product_rout_rate_table where id = {$id}");
            $this->Client->logging(1, 'Product', "Product Name:{$product_name}");
            $this->Client->create_json_array('', 201, __('The Product[%s] is deleted successfully.', true, $product_name));
        }


        $this->xredirect('/clients/product_list');
    }

    public function save_product()
    {
        $this->autoLayout = FALSE;
        $this->autoRender = FALSE;
        Configure::write('debug', 0);
        ob_clean();
        $rout_id = $_POST['rout_id'];
        $rate_table_id = $_POST['rate_table_id'];
        $id = $_POST['id'];
        $product_name = $_POST['product_name'];

        if (!empty($product_name))
        {
            $res = $this->Client->query("select count(*) from product_rout_rate_table  where (product_name = '{$product_name}' or (rout_id = {$rout_id} and rate_table_id = {$rate_table_id})) and id != {$id} ");

            if ($res[0][0]['count'] != 0)
            {
                echo 'isHave';
            }
            else
            {
                $this->Client->query("update product_rout_rate_table set product_name = '{$product_name}', rout_id = {$rout_id} , rate_table_id = {$rate_table_id} where id = {$id} ");
                $this->Client->logging(2, 'Product', "Product Name:{$product_name}");
                $this->Client->query("update resource_prefix set route_strategy_id = {$rout_id} , rate_table_id = {$rate_table_id} 
                where resource_id in (select resource_id from resource where product_id = {$id} and service_type = 1) ");
                $this->Client->create_json_array('', 201, __('Sucessful!', true));
                echo "success";
            }
        }
        else
        {
            echo 'isEmpty';
        }
    }

    public function product_list_first()
    {
        //$this->Client->create_json_array('', 201, __('Sucessful!', true));
        $this->xredirect('/clients/product_list');
    }

    public function route_block()
    {
        Configure::write('debug', 0);
        $this->pageTitle = 'Management/Product';
        $where = " 1=1  ";


        if (isset($_GET['search']))
        {
            $name = $_GET['search'];

            if (!empty($name) && $name != 'Search')
            {
                $where = "code_name ilike '%{$name}%'";
            }
        }


        $sql = "select
                count(*)
                from route_block
                where {$where}";


        $temp = isset($_SESSION['paging_row']) ? $_SESSION['paging_row'] : 100;
        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = $temp : $pageSize = $_GET['size'];
        $_SESSION['paging_row'] = $pageSize;


        $count = $this->Client->query($sql);
        $count = $count[0][0]['count'];
        require_once 'MyPage.php';
        $page = new MyPage ();
        $page->setTotalRecords($count);
        $page->setCurrPage($currPage);
        $page->setPageSize($pageSize);
        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;

        $order_sql = "order by code_name asc";

        if ($this->isnotEmpty($this->params['url'], array('order_by')))
        {
            $order_by = $this->params['url']['order_by'];
            $order_arr = explode('-', $order_by);
            if (count($order_arr) == 2)
            {
                $field = $order_arr[0];
                $sort = $order_arr[1];
                $order_sql = "ORDER BY {$field} {$sort}";
            }
        }

        $sql = "select route_block.*,order_user.name,resource.alias from route_block 
                left join resource on resource.resource_id = route_block.egress_trunk_id
                left join order_user on order_user.client_id = resource.client_id
                where {$where} {$order_sql} LIMIT {$pageSize} OFFSET {$offset}";

        $data = $this->Client->query($sql);


        $page->setDataArray($data);
        $this->set('p', $page);


        $code_name = $this->Client->query("SELECT DISTINCT code.name FROM code 
                                            left join code_deck on code_deck.code_deck_id = code.code_deck_id 
                                            where code_deck.client_id = 0 and code.name != '' ORDER BY name ASC");
        $this->set('code_name', $code_name);

        //var_dump($code_name[0]);

        $egress_trunk = $this->Client->query("SELECT 
                                            alias, rate_table_id,resource_id
                                            FROM resource
                                            WHERE egress = true ORDER BY resource.alias ASC");
        $this->set("egress_trunk", $egress_trunk);
        //var_dump($egress_trunk);
        //exit();
    }

    public function add_route_block()
    {
        $this->autoLayout = FALSE;
        $this->autoRender = FALSE;
        Configure::write('debug', 0);
        ob_clean();
        $egress_id = $_POST['egress_id'];
        $code_name = $_POST['code_name'];

        $client = $this->Client->query("select order_user.corporate_contact_email, order_user.name,order_user.client_id,resource.alias from order_user 
                                        left join resource on order_user.client_id = resource.client_id 
                                        where resource_id = {$egress_id} ");

        if (count($client) == 0)
        {
            echo "no_client";
            exit();
        }
        //$carrier_name = $client[0][0]['name'];
        $client_id = $client[0][0]['client_id'];
        $egress_trunk_name = $client[0][0]['alias'];
        $create_by = $this->Session->read('sst_user_name');
        $email = $client[0][0]['corporate_contact_email'];

        $res = $this->Client->query("select count(*) from route_block  where egress_trunk_id = {$egress_id} and code_name = '{$code_name}' ");
        $codes = $this->Client->query("select DISTINCT code from code where name = '{$code_name}'");

        if ($res[0][0]['count'] != 0)
        {
            echo 'isHave';
        }
        else
        {
            $this->Client->query('begin');

            $this->Client->query("insert into route_block (egress_trunk_id,code_name,create_by,create_on) values ({$egress_id},'{$code_name}' ,'{$create_by}',CURRENT_TIMESTAMP(0) )");
            $code_sql = array();
            foreach ($codes as $code)
            {
                $code_sql[] = "insert into resource_block (engress_res_id,digit,egress_client_id) values({$egress_id},'{$code[0]['code']}',{$client_id})";
            }
            $code_sql = implode(";", $code_sql);

            if (!empty($code_sql))
            {
                $this->Client->query($code_sql);
            }
            $this->Client->logging(0, 'Route Block', "Egress Trunk Name:{$egress_trunk_name}/Code Name:{$code_name}");
            $this->Client->create_json_array('', 201, __('Sucessful!', true));
            $this->Client->query('commit');
            echo "success";
        }
    }

    public function save_route_block()
    {
        /* $this->autoLayout = FALSE;
          $this->autoRender = FALSE;
          Configure::write('debug', 0);
          ob_clean();
          $egress_id = $_POST['egress_id'];
          $code_name = $_POST['code_name'];
          $id = $_POST['id'];

          $client = $this->Client->query("select order_user.name,order_user.client_id from order_user
          left join resource on order_user.client_id = resource.client_id
          where resource_id = {$egress_id} ");

          if(count($client) == 0){
          echo "no_client";
          exit();
          }
          //$carrier_name = $client[0][0]['name'];
          $client_id = $client[0][0]['client_id'];

          $create_by = $this->Session->read('sst_user_name');

          $res = $this->Client->query("select count(*) from route_block  where egress_trunk_id = {$egress_id} and code_name = '{$code_name}' and id != {$id} ");
          $codes = $this->Client->query("select DISTINCT code from code where name = '{$code_name}'");

          if ($res[0][0]['count'] != 0)
          {
          echo 'isHave';
          } else
          {
          $this->Client->query('begin');

          $this->Client->query("update route_block set egress_trunk_id = {$egress_id} ,code_name = '{$code_name}',create_by = '{$create_by}',create_on = CURRENT_TIMESTAMP(0) where id = {$id} ");
          $code_sql = array();
          foreach($codes as $code){
          $code_sql[] = "insert into resource_block (engress_res_id,digit,egress_client_id) values({$egress_id},'{$code[0]['code']}',{$client_id})";
          }
          $code_sql = implode(";", $code_sql);

          if(!empty($code_sql)){
          $this->Client->query($code_sql);
          }

          $this->Client->create_json_array('', 201, __('Sucessful!', true));

          $this->Client->query('commit');
          echo "success";
          } */
    }

    public function del_route_block($id)
    {
        Configure::write('debug', 0);

        $route_block = $this->Client->query("select * from route_block where id = {$id} ");

        if (count($route_block) == 1)
        {

            $egress_id = $route_block[0][0]['egress_trunk_id'];
            $code_name = $route_block[0][0]['code_name'];

            $client = $this->Client->query("select order_user.corporate_contact_email, order_user.name,order_user.client_id ,resource.alias from order_user 
                                            left join resource on order_user.client_id = resource.client_id 
                                            where resource_id = {$egress_id} ");

            if (count($client) == 0)
            {
                echo "no_client";
                exit();
            }
            //$carrier_name = $client[0][0]['name'];
            $client_id = $client[0][0]['client_id'];
            $egress_trunk_name = $client[0][0]['alias'];
            $email = $client[0][0]['corporate_contact_email'];

            $codes = $this->Client->query("select DISTINCT code from code where name = '{$code_name}'");
            foreach ($codes as $code)
            {
                $code_sql[] = "delete from resource_block where engress_res_id = {$egress_id} and digit = '{$code[0]['code']}' and egress_client_id = {$client_id} ";
            }
            $code_sql = implode(";", $code_sql);

            $this->Client->query('begin');

            $this->Client->query($code_sql);
            $this->Client->query("delete from route_block where id = {$id}");
            $this->Client->logging(1, 'Route Block', "Egress Trunk Name:{$egress_trunk_name}/Code Name:{$code_name}");
            $this->Client->create_json_array('', 201, __('The Route Block is deleted successfully.', true));

            $this->Client->query('commit');
            $this->xredirect('/clients/route_block');
        }
    }

    public function send_email($email, $egress_id, $code_name, $type)
    {

        $egress_trunk_email = array();

        if (preg_match('/^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+((\.[a-zA-Z0-9_-]{2,3}){1,2})$/', $email))
        {
            $egress_trunk_email[] = $rate_watch_email[0]['corporate_contact_email'];
        }

        $rate_emails = array();
//        $rate_watch_emails = $this->Client->query("select DISTINCT order_user.corporate_contact_email  from  order_user_rate_watch
//                                                    left join order_user_alert
//                                                    on order_user_rate_watch.order_user_alert_id = order_user_alert.id
//                                                    left join order_user on order_user.client_id = order_user_alert.client_id
//                                                   where code_name = '$code_name'");

        foreach ($rate_watch_emails as $rate_watch_email)
        {
            if (preg_match('/^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+((\.[a-zA-Z0-9_-]{2,3}){1,2})$/', $rate_watch_email[0]['corporate_contact_email']))
            {
                $rate_emails[] = $rate_watch_email[0]['corporate_contact_email'];
            }
        }

        $buy_trunk_code_name_email = array();

        $buy_emails = $this->Client->query("select resource_id from resource_prefix where route_strategy_id in (

                                                    select route_strategy_id from route where 
                                                    digits in (select code from code where name = '{$code_name}') 
                                                    and 
                                                    ( dynamic_route_id in (select dynamic_route_id from dynamic_route_items where resource_id = {$egress_id}) 
                                                    or 
                                                    static_route_id in (select product_id from product_items where item_id 
                                                                            in (select item_id from product_items_resource where resource_id = {$egress_id})  
                                                                        )  
                                                    )
                                            ) "
        );





        if ($type == 'add')
        {
            
        }
        else if ($type == 'del')
        {
            
        }
    }

    public function notify()
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $fh = fopen("/tmp/test", 'w+');
        fwrite($fh, print_r($_POST, TRUE) . "\n\n");
        //从 PayPal 出读取 POST 信息同时添加变量„cmd‟
        $req = 'cmd=_notify-validate';

        foreach ($_POST as $key => $value)
        {
            //$value = urlencode(stripslashes(str_replace("\n", "\r\n",$value)));
            $value = urlencode(stripslashes(preg_replace("/(?<!\r)\n/", "\r\n", $value)));
            $req .= "&$key=$value";
        }

        //建议在此将接受到的信息记录到日志文件中以确认是否收到 IPN 信息
        //将信息 POST 回给 PayPal 进行验证
        $header = "POST /cgi-bin/webscr HTTP/1.0\r\n";
        $header .= "Content-Type:application/x-www-form-urlencoded\r\n";
        $header .= "Content-Length:" . strlen($req) . "\r\n\r\n";
        //在 Sandbox 情况下,设置:
        $fp = fsockopen("ssl://www.paypal.com", 443, $errno, $errstr, 30);
        //$fp = fsockopen ('www.paypal.com', 80, $errno, $errstr, 30);
        //将 POST 变量记录在本地变量中
        //该付款明细所有变量可参考:
        //https://www.paypal.com/IntegrationCenter/ic_ipn-pdt-variable-reference.html
        $item_name = $_POST['item_name'];
        $invoice = $_POST['invoice'];
        $payment_status = $_POST['payment_status'];
        $payment_amount = $_POST['mc_gross'];
        $payment_currency = $_POST['mc_currency'];
        $txn_id = $_POST['txn_id'];
        $receiver_email = $_POST['receiver_email'];
        $payer_email = $_POST['payer_email'];
        $payment_fee = $_POST['payment_fee'];
        $payment_type = $_POST['payment_type'];

        //...
        //判断回复 POST 是否创建成功
        if (!$fp)
        {
            fwrite($fh, "Socket ERROR!\n\n");
            //HTTP 错误
        }
        else
        {
            fwrite($fh, "Socket Sucessfully!\n\n");
            //将回复 POST 信息写入 SOCKET 端口
            fputs($fp, $header . $req);
            //开始接受 PayPal 对回复 POST 信息的认证信息
            while (!feof($fp))
            {
                $res = fgets($fp, 1024);
                fwrite($fh, "{$res}\n\n");
                //已经通过认证
                if (strcmp($res, "VERIFIED") == 0 and $payment_type !== 'echeck')
                {
                    //检查付款状态
                    //检查 txn_id 是否已经处理过
                    //检查 receiver_email 是否是您的 PayPal 账户中的 EMAIL 地址
                    //检查付款金额和货币单位是否正确
                    //处理这次付款,包括写数据库
                    $current_invoice_info = $this->Client->check_finance_info($invoice);
                    fwrite($fh, print_r($current_invoice_info, TRUE) . "\n\n");
                    if ($current_invoice_info[0][0]['status'] == 0)
                    {
                        $this->Client->change_finance_status($current_invoice_info[0][0]['id'], 2, floatval($payment_fee));
                        $amount = floatval($payment_amount) - floatval($payment_fee);
                        $client_id = $current_invoice_info[0][0]['client_id'];
                        $this->Client->update_finance($client_id, $amount, $current_invoice_info[0][0]['id']);

                        $client_info = $this->Client->query("select name,billing_email from client where client_id = '{$client_id}'");

                        //发邮件

                        $sql = "select sys_id, daily_payment_confirmation, daily_payment_email, notify_carrier, notify_carrier_cc,"
                                . "payment_setting_subject,payment_content from system_parameter";
                        $mail_info_arr = $this->Transaction->query($sql);

                        if ($mail_info_arr[0][0]['notify_carrier'])
                        {
                            $convert_table = array(
                                '{amount}' => $amount,
                                '{receiving_time}' => date("Y-m-d H:i:s"),
                                '{client_name}' => $client_info[0][0]['name']
                            );

                            $email_info = $this->Transaction->query('SELECT fromemail as "from", smtphost, smtpport,emailusername as username, 
                 emailpassword as  "password", emailname as "name", loginemail, smtp_secure,realm,workstation,system_admin_email FROM system_parameter');
                            App::import('Vendor', 'nmail/phpmailer');
                            $mailer = new phpmailer(true);
                            if ($email_info[0][0]['loginemail'] === 'false')
                            {
                                $mailer->IsMail();
                            }
                            else
                            {
                                $mailer->IsSMTP();
                            }
                            //$mailer->SMTPDebug   = 2;
                            $mailer->SMTPAuth = $email_info[0][0]['loginemail'] === 'false' ? false : true;
                            $mailer->IsHTML(true);
                            //$mailer->Mailer = 'mail';

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
                                    $mailer->Realm = $email_info[0][0]['realm'];
                                    $mailer->Workstation = $email_info[0][0]['workstation'];
                            }


                            $mail_subject = strtr($mail_info_arr[0][0]['payment_setting_subject'], $convert_table);
                            $mail_content = strtr($mail_info_arr[0][0]['payment_content'], $convert_table);

                            $mailer->From = $email_info[0][0]['from'];
                            $mailer->FromName = $email_info[0][0]['name'];
                            $mailer->Host = $email_info[0][0]['smtphost'];
                            $mailer->Port = intval($email_info[0][0]['smtpport']);
                            $mailer->Username = $email_info[0][0]['username'];
                            $mailer->Password = $email_info[0][0]['password'];
                            $mailer->Subject = $mail_subject;
                            $mailer->Body = $mail_content;
                            $mailer->IsHTML(true);
                            $mail_list = explode(';', $client_info[0][0]['billing_email']);
                            $cc_list = explode(';', $mail_info_arr[0][0]['notify_carrier_cc']);

                            foreach ($mail_list as $email_address)
                            {
                                $mailer->AddAddress($email_address);
                            }
                            foreach ($cc_list as $cc_item)
                            {
                                if (!empty($cc_item))
                                {
                                    $mailer->AddCC($cc_item);
                                }
                            }

                            $billing_email = $email_info[0][0]['system_admin_email'];
                            $mailer->AddAddress($billing_email);

                            if ($mailer->Send())
                            {
                                $this->Transaction->create_json_array('', 201, __('The Email is sended succesfully!', true));
                                $this->Session->write('m', Transaction::set_validator());
                            }
                            else
                            {
                                $this->Transaction->create_json_array('', 201, __('Failed!', true));
                                $this->Session->write('m', Transaction::set_validator());
                            }
                        }
                    }
                }
                else if (strcmp($res, "INVALID") == 0)
                {
                    //未通过认证,有可能是编码错误或非法的 POST 信息
                }
            }
            fclose($fp);
            fclose($fh);
        }
    }

    public function client_pay()
    {
        
    }

    public function client_pay_do()
    {
        if ($this->RequestHandler->isPost())
        {

            $handle = fopen('/tmp/payment.log', 'a');
            fwrite($handle, print_r($_POST, TRUE));
            $client_id = $_SESSION['sst_client_id'];
            $method = (int) $_POST['platform'];
            $cardnumber = $_POST['cardnumber'];
            $cardexpmonth = $_POST['cardexpmonth'];
            $cardexpyear = $_POST['cardexpyear'];
            $cvmvalue = $_POST['cvmvalue'];
            $address1 = $_POST['address1'];
            $address2 = $_POST['address2'];
            $credit_card_type = $_POST['credit_card_type'];
            $city = $_POST['city'];
            $state_province = $_POST['state_province'];
            $zip_code = $_POST['zip_code'];
            $country = $_POST['country'];


            if ($method == 1)
            {
                $amount = $_POST['chargetotal1'];
                App::import('Vendor', 'lphp');
                $sql = "insert into payline_history(chargetotal, method, cardnumber, cardexpmonth, cardexpyear, client_id, address1, address2, credit_card_type, city, state_province, zip_code, country) values (
    				{$amount}, 1,  '{$cardnumber}', '{$cardexpmonth}', '{$cardexpyear}', {$client_id}, '{$address1}', '{$address2}', {$credit_card_type}, '{$city}', '{$state_province}', '{$zip_code}', '{$country}') returning id";
                $result = $this->Client->query($sql);
                $id = $result[0][0]['id'];

                $payinfo = $this->Client->query("select yourpay_store_number from system_parameter");


                $myorder["host"] = Configure::read('payline.yourpay_host');
                $myorder["port"] = Configure::read('payline.yourpay_port');
                $myorder["keyfile"] = APP . 'webroot' . DS . 'upload' . DS . 'yourpay' . DS . 'YOURCERT.perm'; # location of your certificate file 
                $myorder["configfile"] = $payinfo[0][0]['yourpay_store_number']; # This is would be the clients assigned store ID. 
                # form data
                $myorder["cardnumber"] = $cardnumber;
                $myorder["cardexpmonth"] = $cardexpmonth;
                $myorder["cardexpyear"] = $cardexpyear;
                $myorder['cvmindicator'] = 'provided';
                $myorder['cvmvalue'] = $cvmvalue;
                $myorder["chargetotal"] = $amount;
                $myorder["ordertype"] = 'SALE';
                $myorder["oid"] = $id;
                $myorder["debugging"] = "true";

                //	$result = $mylphp->process($myorder);       # use shared library model
                $mylphp = new lphp;
                $result = $mylphp->curl_process($myorder);  # use curl methods


                fwrite($handle, print_r($result, TRUE));
                if ($result["r_approved"] != "APPROVED")
                { // transaction failed, print the reason
                    print "Status: {$result['r_approved']}\n";
                    print "Error: {$result['r_error']}\n";
                    $sql = "update payline_history set status = 1 , error = '{$result['r_error']}' where id = {$id}";
                    $this->Client->query($sql);
                    $this->Client->create_json_array('', 301, __('The Payment is failed. Reason:' . $result['r_error'], true));
                }
                else
                {
                    // success
                    $current_invoice_info = $this->Client->check_finance_info2($id);
                    $status = $current_invoice_info[0][0]['status'];
                    fwrite($handle, print_r($current_invoice_info, TRUE));
                    if ($status === 0)
                    {
                        $this->Client->change_finance_status($id, 2);
                        $client_id = $current_invoice_info[0][0]['client_id'];
                        $this->Client->update_finance($client_id, $amount);
                        $this->Client->create_json_array('', 201, __('Payment Succeeded', true));
                    }
                }
                fclose($handle);
                $this->Session->write('m', Client::set_validator());
                $this->redirect('/clients/client_pay');
            }
            else
            {
                $amount = $_POST['chargetotal2'];
                $domain = $this->get_domain();
                $invoice = uniqid();
                $sql = "insert into payline_history(chargetotal, method, client_id, invoice_id) values (
    			{$amount}, 0, {$client_id}, '{$invoice}') returning id";
                $result = $this->Client->query($sql);
                $id = $result[0][0]['id'];
                $payinfo = $this->Client->query("select paypal_account from system_parameter");

                $this->set('business', $payinfo[0][0]['paypal_account']);
                $this->set('id', $id);
                $this->set('amount', $amount);
                $this->set('invoice', $invoice);
                $this->set('domain', $domain);
            }
        }
    }

    public function get_domain()
    {
        /* 协议 */
        $protocol = (isset($_SERVER['HTTPS']) && (strtolower($_SERVER['HTTPS']) != 'off')) ? 'https://' : 'http://';

        /* 域名或IP地址 */
        if (isset($_SERVER['HTTP_X_FORWARDED_HOST']))
        {
            $host = $_SERVER['HTTP_X_FORWARDED_HOST'];
        }
        elseif (isset($_SERVER['HTTP_HOST']))
        {
            $host = $_SERVER['HTTP_HOST'];
        }
        else
        {
            /* 端口 */
            if (isset($_SERVER['SERVER_PORT']))
            {
                $port = ':' . $_SERVER['SERVER_PORT'];
                if ((':80' == $port && 'http://' == $protocol) || (':443' == $port && 'https://' == $protocol))
                {
                    $port = '';
                }
            }
            else
            {
                $port = '';
            }

            if (isset($_SERVER['SERVER_NAME']))
            {
                $host = $_SERVER['SERVER_NAME'] . $port;
            }
            elseif (isset($_SERVER['SERVER_ADDR']))
            {
                $host = $_SERVER['SERVER_ADDR'] . $port;
            }
        }

        return $protocol . $host;
    }

    public function edit_invoice()
    {
        if ($this->RequestHandler->isPost())
        {//$this->print_rr($this->params);
            $insert_data = $this->params['data'];
            if (!empty($insert_data['Client']['usage_detail_fields']))
            {
                $insert_data['usage_detail_fields'] = implode(',', $insert_data['Client']['usage_detail_fields']);
                unset($insert_data['Client']);
                if ($this->Client->save($insert_data))
                {
                    $this->Session->write('m', $this->Client->create_json(201, 'Successfully!'));
                    $this->redirect('/pr/pr_invoices/carrier_invoice');
                }
                else
                {
                    $this->Session->write('m', $this->Client->create_json(101, 'Failed!'));
                }
            }
        }
        else
        {
            $client_id = $this->params ['pass'] [0];
            if (!$client_id)
            {
                $this->redirect('/pr/pr_invoices/carrier_invoice');
            }
            $this->Client->id = $client_id;
        }
        $this->set('post', $this->Client->read());
        //$this->print_rr($this->Client->read());
        $this->init_info();
    }

    /**
     * 
     * @param type $type
     * 处理自动发送invoice
     */
    public function auto_invoice($type)
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $this->layout = 'ajax';
        $type_allow_arr = array('stop', 'start');
        if (!in_array($type, $type_allow_arr))
        {
            echo "Failed!";
            return false;
//            $this->Session->write('m', $this->Client->create_json(101, 'Failed!'));
//            $this->redirect('/pr/pr_invoices/carrier_invoice');
        }
        if (!$this->isnotEmpty($this->params['url'], array('client_id')))
        {
            echo "Failed!";
            return false;
        }
        $auto_invoicing = 0;
        if (strcmp('stop', $type))
        {
            $auto_invoicing = 1;
        }
        $sql = "update client set auto_invoicing = '{$auto_invoicing}' where client_id = '{$this->params['url']['client_id']}' returning client_id";

        if ($this->Client->query($sql))
        {
            echo "Successfully!";
        }
        else
        {
            echo "Failed!";
            return false;
        }
    }

    public function ajax_check_login()
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        if ($this->RequestHandler->isPost())
        {
            $id = $this->params['form']['id'];
            $sql = "select count(*) as sum from client where client_id = {$id} and is_panelaccess is not null"
                    . " and login is not null and password is not null ";

            $result = $this->Client->query($sql, false);
            echo $result[0][0]['sum'];
        }
        else
        {
            echo 0;
        }
    }

    public function ajax_get_limit()
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        if ($this->RequestHandler->isPost())
        {
            $id = $this->params['form']['id'];
            $sql = "select cps_limit,call_limit from client where client_id = '{$id}'";
            $result = $this->Client->query($sql, false);
            if (!$result)
            {
                echo "{'cps_limit':null,'call_limit':null}";
            }
            else
            {
                echo json_encode($result[0][0]);
            }
        }
        else
        {
            echo 0;
        }
    }

    public function download_balance()
    {
        Configure::write('debug', 0);
        $this->autoLayout = false;
        if ($this->params['form'])
        {
            $date = $this->params['form']['balance_date'];
            if (!$date)
            {
                $date = date('Y-m-d', time());
            }
            $this->Client->download_balance($date);
        }
    }

//    可以同时修改所有的carrier的limit
    public function client_limit()
    {
        if ($_SESSION['login_type'] != 1)
        {
            $this->redirect('/clients/carrier/');
        }
        $this->pageTitle = 'Management/Carriers';
        if ($this->RequestHandler->isPost())
        {
            $post_arr = $this->params['data'];
            foreach ($post_arr as $post_item)
            {
                $this->Client->save($post_item);
            }
        }
        $order_sql = "ORDER BY client_id ASC";
        if ($this->isnotEmpty($this->params['url'], array('order_by')))
        {
            $order_by = $this->params['url']['order_by'];
            $order_arr = explode('-', $order_by);
            if (count($order_arr) == 2)
            {
                $field = $order_arr[0];
                $sort = $order_arr[1];
                $order_sql = "ORDER BY {$field} {$sort}";
            }
        }
        $temp = isset($_SESSION['paging_row']) ? $_SESSION['paging_row'] : 100;
        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = $temp : $pageSize = $_GET['size'];
        $_SESSION['paging_row'] = $pageSize;

        $sst_user_id = $_SESSION['sst_user_id'];
        $where = "";
        if (isset($_GET['submit']))
        {
            $filter_type = $_GET['filter_client_type'];
            $client_name = $_GET['search'];
            switch ((int) $filter_type)
            {
                case 1:
                    $where = " AND client.status = true";
                    break;
                case 2:
                    $where = " AND client.status = false";
                    break;
            }

            if (!empty($client_name) && $client_name != 'Search')
                $where .= " AND client.name ilike '%{$client_name}%'";
        } else
        {
            $where = " AND client.status = true";
        }
        $count = $this->Client->getclients_count($sst_user_id, $where);
        require_once 'MyPage.php';
        $page = new MyPage ();
        $page->setTotalRecords($count);
        $page->setCurrPage($currPage);
        $page->setPageSize($pageSize);
        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;
        $sql = "SELECT client_id,name,allowed_credit,call_limit, cps_limit FROM client WHERE 1=1 {$where} {$order_sql}";
        $sql .= "  limit '$pageSize' offset '$offset'";
        $data_arr = $this->Client->query($sql);
        $page->setDataArray($data_arr);
        $this->set('p', $page);
    }

}

?>
