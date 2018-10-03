<?php
class ClientsController extends AppController
{

    var $name = 'Clients';
    var $helpers = array('javascript', 'html', 'AppClients', 'Common', 'App');
    var $components = array('RequestHandler', 'Email');
    var $uses = array("prresource.Gatewaygroup", "Client", 'Clients', 'Credit', 'Orderuser', 'CreditLog', 'MailSender', 'Mailtmp', 'Systemparam',  'Transaction','ApiLog');

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
        try {
            $this->set('extensionBeans', 1);
        } catch (Exception $e) {
            echo "Server Exception";
        }
    }

    public function upload_reset_balance_change_header()
    {
        if ($this->RequestHandler->isPost()) {
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
            if (!$with_header) {
                // sed 插入第一行插入空行
                //$cmd = "'1i\\\\'";
                $cmd_awk = "awk -F ',' 'NR==1 {print NF}' {$abspath}";
                $awk_result = shell_exec($cmd_awk);
                $line_rows = (int)$awk_result - 1;
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

            while ($row <= 21 && $data = fgetcsv($handle, 1000, ",")) {
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
        if ($this->RequestHandler->isPost()) {
            if (isset($this->params['form']['password'])) {
                $password = $this->params['form']['password'];
                $sql1 = "update client set password='$password' where  client_id=$client_id ";
                $sql2 = "update users set password=md5('$password') where  client_id=$client_id ";
                $this->Client->query($sql1);
                $this->Client->query($sql2);
                $client_name_info = $this->Client->query("select name from client where client_id = {$client_id}");
                $client_name = $client_name_info[0][0]['name'];
                $this->Client->create_json_array('#ClientOrigRateTableId', 201, __('The password of client [%s] is modified successfully!', true, $client_name));
                $this->Session->write("m", Client::set_validator());
                $this->redirect("/clients/index");
            }
        }
    }

    public function get_mutual_ingress_egress_detail($client_id)
    {
        $this->loadModel('FinanceHistory');
        if (isset($this->params['form']['balance'])) {
            $balance = $this->params['form']['balance'];
            $begin_time = $this->params['form']['begin_time'];
            $description = $this->params['form']['description'];
            $this->set('balance', $balance);
            $this->set('begin_time', $begin_time);
            $this->set('description', $description);
        }
        $start_time = date("Y-m-d");
        if (isset($begin_time) && $begin_time) {
            $start_time = $begin_time;
        } else {//错误
        }
        $end_time = date("Y-m-d");

        $client_id = base64_decode($client_id);
        $begin_balance_result = $this->FinanceHistory->find('first', array('order' => array('FinanceHistory.date DESC'), 'conditions' => array('FinanceHistory.date < ' => $start_time, 'client_id' => $client_id),));
        if (!$begin_balance_result) {
            $begin_balance_result = $this->FinanceHistory->find('first', array('order' => array('FinanceHistory.date ASC'), 'conditions' => array('client_id' => $client_id),));
        }
        $begin_balance = $begin_balance_result['FinanceHistory']['mutual_balance'];
        $start_time = empty($begin_balance_result['FinanceHistory']['date']) ? $start_time : $begin_balance_result['FinanceHistory']['date'];
//        $start_time = $begin_balance_result['FinanceHistory']['date'];
        $end_balance_result = $this->FinanceHistory->find('first', array('order' => array('FinanceHistory.date DESC'), 'conditions' => array('FinanceHistory.date <= ' => $end_time, 'client_id' => $client_id),));


        $financehistories = $this->FinanceHistory->find('all', array('order' => array('FinanceHistory.date'), 'conditions' => array('FinanceHistory.date BETWEEN ? AND ?' => array($start_time, $end_time), 'client_id' => $client_id, 'or' => array('FinanceHistory.invoice_set != ?' => 0, 'FinanceHistory.payment_received != ?' => 0, 'FinanceHistory.credit_note_sent != ?' => 0, 'FinanceHistory.debit_note_sent != ?' => 0, 'FinanceHistory.invoice_received != ?' => 0, 'FinanceHistory.payment_sent != ?' => 0, 'FinanceHistory.credit_note_received != ?' => 0, 'FinanceHistory.debit_note_received != ?' => 0,),),));
        $current_date = date("Y-m-d");
        if (strtotime($end_time) >= strtotime($current_date)) {
            $end_time = $current_date;

            $current_finance = $this->FinanceHistory->get_current_finance_detail($client_id, "limit 100");
            $current_finance['date'] = $current_date;

            $financehistories[] = array('FinanceHistory' => $current_finance);

            $end_balance = isset($current_finance['mutual_balance']) ? $current_finance['mutual_balance'] : '';
        } else {
            if (count($end_balance_result) == 1) {
                $end_balance = $end_balance_result['FinanceHistory']['mutual_balance'];
                $end_time = $end_balance_result['FinanceHistory']['date'];
            } else {
                $end_balance = 0;
            }
        }


        $this->set('start_time', $start_time);
        $this->set('end_time', $end_time);

        $sum_arr = array();
        foreach ($financehistories as $financehistory) {
            foreach ($financehistory['FinanceHistory'] as $key => $value) {
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


        if (isset($_GET['export'])) {
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
        if ($this->RequestHandler->isPost()) {
//           echo "<pre>"; print_r($_POST);die;
            if (isset($this->params['form']['balance'])) {
                $balance = $this->params['form']['balance'];
                $begin_time = $this->params['form']['begin_time'];
                $description = $this->params['form']['description'];

                if (isset($this->params['form']['ids'])) {
                    $ids = explode(',', $this->params['form']['ids']);

                    foreach ($ids as $id) {
                        $sql = "INSERT INTO client_payment (result, receiving_time, amount, client_id, description, payment_type, payment_time)
                    VALUES(true, CURRENT_TIMESTAMP, {$balance}, {$id}, '{$description}', 13, '{$begin_time}')";
                        $this->Client->query($sql);
                        $this->Client->clientBalanceOperation($id, $balance, 1);
                    }
                    $this->Client->create_json(201, __('Balance for selected clients successfully reset!', true));
                } else {
                    $client_name_info = $this->Client->query("select name from client where client_id = {$client_id}");
                    $client_name = $client_name_info[0][0]['name'];
                    $sql = "INSERT INTO client_payment (result, receiving_time, amount, client_id, description, payment_type, payment_time)
                    VALUES(true, CURRENT_TIMESTAMP, {$balance}, {$client_id}, '{$description}', 13, '{$begin_time}')";
                    $this->Client->query($sql);
                    $info = $this->Systemparam->find('first',array(
                        'fields' => array('cmd_debug'),
                    ));
                    if (Configure::read('cmd.debug')) {
                        file_put_contents($info["Systemparam"]["cmd_debug"], $cmd);
                    }

                    $this->Client->logging(0, 'Reset Balance', "Client:{$client_name}, Balance:[{$balance}]");
                    $this->Client->create_json_array('#ClientOrigRateTableId', 201, sprintf(__('You Job [#%s] is scheduled to execute in the queue.', true), $pid));
                }
                $this->Session->write("m", Client::set_validator());
                $this->redirect("/clients/index");
            }
        }
    }

    public function upload_reset_balance()
    {
        if ($this->RequestHandler->isPost()) {
            $abspath = $_POST['abspath'];
            $new_columns = $_POST['columns'];
            $type = $_POST['type'];
            $new_columns_str = implode(',', $new_columns);
            $cmd_ = "sed -i '1s/.*/{$new_columns_str}/g' {$abspath}";
            shell_exec($cmd_);
            $script_path = Configure::read('script.path');
            $script_conf = Configure::read('script.conf');
            $object_name = $type == 0 ? 'Reset Actual Balance' : 'Reset Mutual Balance';
            $sql = "INSERT INTO import_export_logs(file_path,  status,
		user_id, obj, log_type, time) VALUES('{$abspath}', 1, {$_SESSION['sst_user_id']}, '$object_name', 1, CURRENT_TIMESTAMP(0)) returning id";
            $result = $this->Client->query($sql);
            $log_id = $result[0][0]['id'];
            $statistics = array('log_id' => $log_id,);
            $this->set('statistics', $statistics);
        }
        $this->set('example', $this->webroot . 'example/reset_balance.csv');
    }

    function add_resouce_ingress()
    {
        if (!empty($this->data ['Gatewaygroup'])) {
            $resource_id = $this->Gatewaygroup->saveOrUpdate($this->data, $_POST);
            if ($resource_id == 'fail') {
                $this->set('m', Gatewaygroup::set_validator()); //向界面设置验证信息
                $this->set('post', $this->data);
                $this->init_info();
            } else {
                $_SESSION ['resource_id'] = $resource_id;
                $_SESSION ['resource_name'] = $this->data ['Gatewaygroup'] ['name'];
                $_SESSION ['gress'] = 'ingress';
                $this->redirect("/clientrates/view_rate_detail/");
            }
        } else {
            $this->init_info();
        }
    }

    public function low_balance_alert($encode_client_id, $redirect = true)
    {

        Configure::write('debug', 2);
        $this->autoRender = false;
        $this->autoLayout = false;
        $client_id = base64_decode($encode_client_id);
//        $client_id = $encode_client_id;
        $sql = "SELECT client.*, (select name from payment_term where payment_term_id = client.payment_term_id) as payment_terms_name
                FROM client
                WHERE client_id = {$client_id}";
        $result = $this->Client->query($sql);
        $balance = $this->Client->query("SELECT * FROM c4_client_balance WHERE client_id::integer={$client_id}");
        if (!$result) {
            $this->redirect($this->referer());
        }
        $userData = $result[0][0];
        $userBalance = $balance[0][0];
        $userData['allowed_credit'] = ($userData['allowed_credit'] < 0) ? str_replace('-', '', $userData['allowed_credit']) : $userData['allowed_credit'];

        $mailTemplates = $this->Mailtmp->find('all');
        $mailTemplates = $mailTemplates[0]['Mailtmp'];

        $to = $userData['billing_email'];
        $cc = $mailTemplates['lowbalance_cc'];
        $subject = $mailTemplates['lowbalance_subject'];
        $subject = str_replace('{company_name}', $userData['company'], $subject);
        $subject = str_replace('{current_date}', date('d.m.Y'), $subject);
        $subject = str_replace('{balance}', number_format($userBalance['balance'], 2), $subject);
        $subject = str_replace('{payment_terms}', $userData['payment_terms_name'], $subject);
        $subject = str_replace('{credit_limit}', number_format($userData['allowed_credit'], 2), $subject);
        $subject = str_replace('{remaining_credit}', number_format($userData['notify_admin_balance'], 2), $subject);

        $body = $mailTemplates['lowbalance_content'];
        $body = str_replace('{company_name}', $userData['company'], $body);
        $body = str_replace('{current_date}', date('d.m.Y'), $body);
        $body = str_replace('{balance}', number_format($userBalance['balance'], 2), $body);
        $body = str_replace('{payment_terms}', number_format($userData['payment_terms_name'], 2), $body);
        $body = str_replace('{credit_limit}', number_format($userData['allowed_credit'], 2), $body);
        $body = str_replace('{remaining_credit}', number_format($userData['notify_admin_balance'], 2), $body);

        $result = $this->VendorMailSender->send($subject, $body, $to, $cc);

        $mailStatus = ($result == true) ? 0 : 1;
        $subject = pg_escape_string($subject);
        $body = pg_escape_string($body);
        $sql = "INSERT INTO email_log (send_time,type,email_addresses,status,error,subject,content,client_id)
                VALUES (current_timestamp(0), 1, '{$to}', {$mailStatus}, '', '{$subject}', '{$body}', {$client_id})";
        $tmpRes = $this->Client->query($sql);
        if ($result) {
            $this->Client->create_json_array('#ClientOrigRateTableId', 201, __('The email is sent successfully!', true, $userData['name']));
        } else {
            $this->Client->create_json_array('#ClientOrigRateTableId', 101, __('Failed sending to [%s]!', true, $userData['name']));
        }

        if ($redirect) {
            $this->Session->write("m", Client::set_validator());
            $this->redirect($this->referer());
        }
    }

    public function send_welcome($encode_client_id, $redirect = true)
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $client_id = base64_decode($encode_client_id);
        $sql = "SELECT *
FROM client WHERE client_id = {$client_id}";
        $result = $this->Client->query($sql);
        if (!$result) {
            $this->redirect($this->referer());
        }
        $userData = $result[0][0];
        if(empty($userData['email'])) {
            $this->Client->create_json_array('#ClientOrigRateTableId', 101, __('Main email is empty on carrier [%s]!', true, $userData['name']));
            $this->Session->write("m", Client::set_validator());
            $this->redirect($this->referer());
        }

        $mailTemplates = $this->Mailtmp->find('all');
        $mailTemplates = $mailTemplates[0]['Mailtmp'];
        $to = $userData['email'];
        $from = $mailTemplates['welcom_from'] == 'default' ? null : $mailTemplates['welcom_from'];
        $subject = $mailTemplates['welcom_subject'];
        $body = $mailTemplates['welcom_content'];
        $body = str_replace('{company_name}', $userData['company'], $body);
        $body = str_replace('{username}', $userData['login'], $body);
        $body = str_replace('{client_name}', $userData['name'], $body);
        $url = "{$_SERVER['HTTP_HOST']}{$this->webroot}homes/login";
        $body = str_replace('{login_url}', "{$url}", $body);

        $result = $this->VendorMailSender->send($subject, $body, $to, null, $from);
        $body = pg_escape_string($body);
        $body = htmlspecialchars($body);
        $sql = "INSERT INTO email_log (send_time,type,email_addresses,status,error,subject,content,client_id)
                VALUES (current_timestamp(0), 31, '{$to}', {$result['status']}, '{$result['error']}', '{$subject}', '{$body}', {$client_id})";

        $this->Client->query($sql);

        if ($result['status'] == 0) {
            $this->Client->create_json_array('#ClientOrigRateTableId', 201, __('The email is sent successfully!', true, $userData['name']));
        } else {
            $this->Client->create_json_array('#ClientOrigRateTableId', 101, __("Failed sending. %s!", true, trim(strip_tags($result['error']))));
        }
        if ($redirect) {
            $this->Session->write("m", Client::set_validator());
            $this->redirect($this->referer());
        }
    }

    function add_resouce_egress()
    {
        if (!empty($this->data ['Gatewaygroup'])) {
            $resource_id = $this->Gatewaygroup->saveOrUpdate($this->data, $_POST);
            if ($resource_id == 'fail') {
                $this->set('m', Gatewaygroup::set_validator()); //向界面设置验证信息
                $this->set('post', $this->data);
                $this->init_info();
            } else {
                $_SESSION ['resource_id'] = $resource_id;
                $_SESSION ['resource_name'] = $this->data ['Gatewaygroup'] ['name'];
                $_SESSION ['gress'] = 'egress';
                $this->redirect("/gatewaygroups/add_host/");
            }
        } else {
            $this->init_info();
        }
    }

    function getclient()
    {
        $this->autoLayout = FALSE;
        $this->autoRender = FALSE;
        Configure::write('debug', 0);
        $q = '';
        if (isset($_GET['q'])) {
            $q = strtolower($_GET['q']);
        }
        if (!$q) {
            return;
        }
        $result = $this->Client->query("select client_id, name from client order by name asc");
        $items = array();
        foreach ($result as $val) {
            $items[$val[0]['name']] = $items[0]['client_id'];
        }
        foreach ($items as $key => $value) {
            if (strpos(strtolower($key), $q) !== false) {
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
        $sql = "INSERT INTO client_balance_operation_action (client_id, balance, ingress_balance, egress_balance, action) VALUES ('{$client_id}', '{$balance}', $ingress, $egress, 1)";
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
        if (isset($_GET['q'])) {
            $q = strtolower($_GET['q']);
        }
        if (!$q) {
            return;
        }
        $result = $this->Client->query("select client_id, name from client where auto_invoicing = false order by name asc");
        $items = array();
        foreach ($result as $val) {
            $items[$val[0]['name']] = $items[0]['client_id'];
        }
        foreach ($items as $key => $value) {
            if (strpos(strtolower($key), $q) !== false) {
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
        if (isset($_GET['q'])) {
            $q = strtolower($_GET['q']);
        }
        if (!$q) {
            return;
        }
        $result = $this->Client->query("select client_id, name from client order by name asc");
        $items = array();
        foreach ($result as $val) {
            $items[$val[0]['name']] = $items[0]['client_id'];
        }
        foreach ($items as $key => $value) {
            if (strpos(strtolower($key), $q) !== false) {
                echo "$key|$value\n";
            }
        }
    }

    function addroutingplan()
    {
        Configure::write('debug', 0);
        $this->layout = '';
    }

    function addproduct()
    {
        Configure::write('debug', 0);
        $this->layout = '';

        $this->set("rate_table", $this->Gatewaygroup->find_rate_table());
        $this->set('rout_list', $this->Gatewaygroup->find_route_strategy());
    }

    function addroute_strategy()
    {
        $this->autoRender = false;
        Configure::write('debug', 0);
        $this->layout = '';
        $sql = "select count(*) from route_strategy where name = '{$_POST['name']}'";
        $count = $this->Client->query($sql);
        if ($count[0][0]['count'] > 0) {
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
        for ($i = 0; $i < $count; $i++) {
            $prefix = $_POST['prefix'][$i];
            $type = $_POST['routetype'][$i];
            $static = $_POST['static'][$i];
            $dynamic = $_POST['dynamic'][$i];
            if ($type == '1') {
                $static = 'NULL';
            } else if ($type == '2') {
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
        foreach ($result as $val) {
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
        foreach ($result as $val) {
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
        foreach ($result as $val) {
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
        foreach ($result as $val) {
            array_push($arr, $val[0]);
        }
        echo json_encode($arr);
    }

    public function view_egress()
    {
        $this->redirect('/clients/view_ingress');
        exit;

//        $this->init_info();
//        $client_id = $_SESSION['sst_client_id'];
//        if(empty($client_id)) {
//            $this->redirect('/clients/index');
//        }
//        $this->set('sst_client_id', $client_id);
//        $order_field = "alias asc";
//        if ($this->isnotEmpty($this->params['url'], array('order_by'))) {
//            $order_by = $this->params['url']['order_by'];
//            $order_arr = explode('-', $order_by);
//            if (count($order_arr) == 2) {
//                $field = $order_arr[0];
//                $sort = $order_arr[1];
//                $order_field = $field . " " . $sort;
//            }
//        }
//
//        $this->loadModel('Resource');
//        $user_id = $this->Session->read('sst_user_id');
//        $sql = "select count(*) from resource where client_id = $user_id and egress = 't'";
//        $resource_table = $this->Resource->query($sql);
//        $row_count = $resource_table[0][0]['count'];
//
////        if($row_count == 0){
////            $_SESSION['pp'] = true;
////            $this->redirect('/clients/view_ingress');
////        }
//
//        $this->set('p', $this->Gatewaygroup->findAll_egress($order_field));
//        $prefix_rate_table_sql = "select rate_table.name,rate_table.rate_table_id,resource.resource_id
//from resource inner join rate_table on resource.rate_table_id = rate_table.rate_table_id where
//resource_id is not null";
//        $prefix_rate_table = $this->Client->query($prefix_rate_table_sql);
//        $size = count($prefix_rate_table);
//        $l = array();
//        for ($i = 0; $i < $size; $i++) {
//            $key = $prefix_rate_table [$i] [0] ['resource_id'];
//            $l [$key]['rate_table_id'] = $prefix_rate_table [$i] [0] ['rate_table_id'];
//            $l [$key]['rate_table_name'] = $prefix_rate_table [$i] [0] ['name'];
//        }
//        $this->set("prefix_rate_table", $l);
//
//        $resource_ip_arr = $this->Gatewaygroup->get_resource_ip_by_client_id($client_id, $order_field);
//        $this->set('resource_ip_arr', $resource_ip_arr);
//        $this->set('change_ip', Configure::read('portal.change_ip'));
        //var_dump($l,$resource_ip_arr);
    }

    //对接网关
    public function view_ingress()
    {
        $this->loadModel('Systemparam');
        $systemParams = $this->Systemparam->find('first', array(
            'fields' => array('enable_client_download_rate', 'enable_client_delete_trunk', 'enable_client_disable_trunk')
        ));
        $this->set('systemParams', $systemParams['Systemparam']);

        $this->init_info();
        $client_id = $_SESSION['sst_client_id'];
        if(empty($client_id)) {
            $this->redirect('/clients/index');
        }
        $this->set('sst_client_id', $client_id);
        $order_field = "alias asc";
        if ($this->isnotEmpty($this->params['url'], array('order_by'))) {
            $order_by = $this->params['url']['order_by'];
            $order_arr = explode('-', $order_by);
            if (count($order_arr) == 2) {
                $field = $order_arr[0];
                $sort = $order_arr[1];
                $order_field = $field . " " . $sort;
            }
        }

        $this->loadModel('Resource');
        $user_id = $this->Session->read('sst_user_id');
        $sql = "select count(*) from resource where client_id = $user_id and egress = 't'";
        $resource_table = $this->Resource->query($sql);
        $row_count = $resource_table[0][0]['count'];

        if($row_count == 0){
            $_SESSION['pp'] = true;
        }

        $results = $this->Gatewaygroup->findAll_ingress($order_field);
        $data = &$results->dataArray;
        $this->loadModel('ResourcePrefix');
        foreach ($data as &$item) {
            $resourceItemId = $item[0]['resource_id'];
            $resource_prefix = $this->ResourcePrefix->get_list_by_resource($resourceItemId);
            $item[0]['products'] = $resource_prefix;
            $prefix_or_name = $resource_prefix[0]["ResourcePrefix"]["tech_prefix"]?:$resource_prefix[0]["ProductRouteRateTable"]["product_name"];
            $item[0]['prefix'] = $resource_prefix[0]["ResourcePrefix"]["tech_prefix"]?:'';
            $item[0]['rate_table_id'] = $resource_prefix[0]["RateTable"]["rate_table_id"]?:'';
            $item[0]['name'] = $item[0]["client_name"]. ($prefix_or_name ? ("_".$prefix_or_name) : "");
        }
        $this->set('p', $results);
        $resource_ip_arr = $this->Gatewaygroup->get_resource_ip_by_client_id($client_id, $order_field);
        $this->set('resource_ip_arr', $resource_ip_arr);
        $this->set('change_ip', Configure::read('portal.change_ip'));
    }

    public function ajax_ip()
    {
        Configure::write('debug', 2);
        $this->set('extensionBeans', $this->Gatewaygroup->findAllres_ip($this->params ['pass'] [0]));
    }

    function dis_able_resource()
    {
        Configure::write("debug", "0");
        $this->autoLayout = false;
        $this->autoRender = false;
        $id = base64_decode($this->params['pass'][0]);
        $page = $this->params['pass'][1];
        $flg = $this->Client->query("update resource set active=false where resource_id =$id");
        if ($flg === false)
            $this->Session->write('m', $this->Client->create_json(101, __('Failed!', true)));
        else
            $this->Session->write('m', $this->Client->create_json(201, __('Succeed!', true)));

        if ($page == 'carrier') {
            $page .= '/true';
        }
        $this->redirect($page);
    }

    function active_resource()
    {
        Configure::write("debug", "0");
        $this->autoLayout = false;
        $this->autoRender = false;
        $id = base64_decode($this->params['pass'][0]);
        $page = $this->params['pass'][1];
        $flg = $this->Client->query("update resource set active=true where resource_id =$id");
        if ($flg === false)
            $this->Session->write('m', $this->Client->create_json(101, __('Failed!', true)));
        else
            $this->Session->write('m', $this->Client->create_json(201, __('Succeed!', true)));

        if ($page == 'carrier') {
            $page .= '/true';
        }
        $this->redirect($page);
    }


    public function del_resource()
    {
        Configure::write("debug", "0");
        $this->autoLayout = false;
        $this->autoRender = false;
        $resource_id = base64_decode($this->params['pass'][0]);
        $page = $this->params['pass'][1];
        $sql = "SELECT alias FROM resource WHERE resource_id = {$resource_id}";
        $data = $this->Gatewaygroup->query($sql);
        $flg = $this->Gatewaygroup->delete($resource_id);
        if ($flg === false)
            $this->Session->write('m', $this->Client->create_json(101, __('Failed!', true)));
        else {
            $this->Session->write('m', $this->Client->create_json(201, __('Succeed!', true)));
            $this->Gatewaygroup->logging(1, 'Trunk', "Trunk Name:{$data[0][0]['alias']}");
        }

        if ($page == 'carrier') {
            $page .= '/true';
        }
        $this->redirect($page);


    }

    public function download_rate()
    {
        Configure::write("debug", "0");
        $this->loadModel('RateTable');
        $rate_table_id = base64_decode($this->params['pass'][0]);
        $jur_type = $this->RateTable->query("select jur_type FROM rate_table WHERE rate_table_id = $rate_table_id");
        $this->loadModel('Rate');
        $rate_file = $this->Rate->create_rate_file($rate_table_id, 1, '', '', '', '', $jur_type[0][0]['jur_type']);
        ob_clean();
        header("Content-Type: text/csv");
        header("Content-Disposition: attachment; filename=rate.csv");
        header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
        header('Expires:0');
        header('Pragma:public');
        readfile($rate_file);
        exit;
    }

    public function ajax_tran()
    {
        Configure::write('debug', 0);
        $client_id = $this->params['pass'][0];
        $this->set('extensionBeans', $this->Client->query("select create_time,amount,tran_type,balance,cause,description  from  transation  where user_type=3 and id=$client_id"));
    }

//读取该模块的执行和修改权限
    public function beforeFilter()
    {
        $login_type = $this->Session->read('login_type');
        if ($this->params['action'] == 'notify' || (($this->params['action'] == "clients_payment" || $this->params['action'] == "client_pay" || $this->params['action'] == "client_pay_do") && $login_type == 2))
            return true;
        $this->checkSession("login_type"); //核查用户身份
        if (PRI) {
            $this->Session->write('executable', $_SESSION['role_menu']['Management']['clients']['model_x']);
            $this->Session->write('writable', $_SESSION['role_menu']['Management']['clients']['model_w']);
        } else {
            if ($login_type == 1) {
                $this->Session->write('executable', true);
                $this->Session->write('writable', true);
            } else {
                $limit = $this->Session->read('sst_wholesale');
                $this->Session->write('executable', $limit['executable']);
                $this->Session->write('writable', $limit['writable']);
            }
        }
        if (!$_SESSION['role_menu']['Management']['clients']['model_r']) {
            $this->redirect_denied();
        }
        parent::beforeFilter();
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
        $this->set('sendemailTerm', $this->Client->findsendemailTerm());
        $this->set('transation_fees', $this->Client->findTransFees());
    }

    /**
     * 编辑客户信息
     */
    function edit()
    {
        $this->pageTitle = "Management/Edit Carrier";
        if ($this->RequestHandler->isPost()) {
            $this->_render_edit_impl();
        }
        $this->_render_edit_data();
    }

    function _render_edit_impl()
    {
        //pr($this->data);
//		pr("--------------------------------------");
        //pr($_POST);
        if ($_SESSION['role_menu']['Management']['clients']['model_w']) {
            $this->data['Client']['allowed_credit'] = 0 - $this->data['Client']['allowed_credit'];
            $mass_edit = false;
            $edit = true;
            if(!isset($this->data['Client']['daily_cdr_generation'])){
                $this->data['Client']['daily_cdr_generation'] = 'false';
            }
            $flag = $this->Client->saveOrUpdate($this->data, $_POST, $mass_edit, $edit); //保存

            if(isset($flag['client_id']) && intval($flag['client_id']) != 0){
                $low_balance_config = $this->data['low_balance'];
                $low_balance_config['client_id'] = intval($flag['client_id']);
                $this->loadModel('ClientLowBalance');
                $tmpRes = $this->ClientLowBalance->save($low_balance_config);
            }

            if (!is_array($flag)){
                $this->Session->write("m",$flag);
            }else{
                if ($flag['client_id']) {
                    $this->Session->write("m", $this->Client->create_json(201, __('The carrier [%s] is modified successfully!', true,$this->data['Client']['name'])));
                    $url_flug = "clients-index";
                    $this->modify_log_noty($flag['log_id'], $url_flug);
                }
            }
        } else {
            $this->redirect_denied();
        }
    }

    function _render_edit_data()
    {
        $this->Client->id = base64_decode($this->params ['pass'] [0]);
        $clientData = $this->Client->find('first', array(
            'conditions' => array(
                'client_id' => $this->Client->id
            )
        ));
//        die(var_dump($clientData));
        $this->data = $clientData;
        $this->set('post', $this->Client->read());
        $this->set('gate_client_id', base64_decode($this->params ['pass'] [0]));
        $this->loadModel('ClientLowBalance');
        $low_balance_config = $this->ClientLowBalance->findByClientId(base64_decode($this->params ['pass'] [0]));
        $this->data['low_balance'] = array();
        if ($low_balance_config){
            $this->data['low_balance'] = $low_balance_config['ClientLowBalance'];
        }
        $this->init_info();
    }

    /**
     * 添加客户信息
     */
    function add()
    {
        if ($_SESSION['role_menu']['Management']['clients']['model_w']) {
            if ($this->RequestHandler->isPost()) {
                $this->_render_add_impl();
            }
            $this->_render_add_data();
        } else {
            $this->redirect_denied();
        }
        $tz = $this->Client->get_sys_timezone();
        $gmt = "+00:00";
        if ($tz) {
            $gmt = substr($tz, 0, 3) . ":00";
        }

        $have = $this->Client->query('select count(*) from carrier_template');
        $this->set('have_template', $have[0][0]['count']);

        $this->set('gmt', $gmt);
        $default_currency = $this->Client->query("SELECT sys_currency FROM system_parameter LIMIT 1");
        $this->set('default_currency', $default_currency[0][0]['sys_currency']);
    }

    function _render_add_data()
    {
        if (!empty($this->params['url']['order_user_id'])) {
            $order_user_id = intval($this->params['url']['order_user_id']);
            //var_dump($order_user_id);die;
            $this->set('post', $this->Client->query("select * from order_user where id = " . $order_user_id));
        }
        $this->init_info();
    }

    function _render_add_impl_save()
    {
        $this->data['Client']['allowed_credit'] = 0 - $this->data['Client']['allowed_credit'];
        if(!isset($this->data['Client']['zero_balance_notice_time'])) {
            $this->data['Client']['zero_balance_notice_time'] = 0;
        }
        if(!isset($this->data['Client']['is_daily_balance_notification'])) {
            $this->data['Client']['is_daily_balance_notification'] = 0;
        }
        if(!isset($this->data['Client']['low_balance_notice'])) {
            $this->data['Client']['low_balance_notice'] = 0;
        }
        if(!isset($this->data['Client']['zero_balance_notice'])) {
            $this->data['Client']['zero_balance_notice'] = 0;
        }
        if(!isset($this->data['Client']['daily_balance_recipient'])) {
            $this->data['Client']['daily_balance_recipient'] = 0;
        }
        if (empty($_POST['order_user_id'])) {

            if (isset($_POST['client_id']) && $_POST['client_id']) {
                $this->Client->query("DELETE FROM client WHERE client_id = {$_POST['client_id']}");
            }
            if (isset($this->data['Client']['login']) && $this->data['Client']['login']) {
                $this->Client->query("DELETE FROM users WHERE name = '{$this->data['Client']['login']}'");
            }
            $return = $this->Client->saveOrUpdate($this->data, $_POST); //保存

            if(isset($this->data['Client']['send_welcome']) && $this->data['Client']['send_welcome'] == 1) {
                $this->send_welcome(base64_encode($return['client_id']), false);
            }
            if(isset($return['client_id']) && intval($return['client_id']) != 0){
                $low_balance_config = $this->data['low_balance'];
                $low_balance_config['client_id'] = intval($return['client_id']);
                $this->loadModel('ClientLowBalance');
                $this->ClientLowBalance->save($low_balance_config);
            }
            return $return;
        } else {
            $order_user_info = $this->Client->query("select * from order_user where id = " . intval($_POST['order_user_id']));
            //var_dump($order_user_info);exit;
            $return = $this->Client->saveOrUpdate_orderuser($this->data, $_POST); //保存
            if ($return) {
                require_once(APP . 'vendors/mail_order_user.php');
            }
            return $return;  //传一个有client_id 有log_id 的数组
        }
    }

    function _render_add_impl_redirect($flag_arr)
    {
        if (is_array($flag_arr) && $flag_arr['client_id']) {
            $name = $_POST['data']['Client']['name'];
            //$this->Client->create_json_array('#ClientOrigRateTableId',201,__('Create carriers successfully!',true));
            $this->Client->create_json_array('#ClientOrigRateTableId', 201, __('The Carrier [%s] is created successfully', true,array($name)));

            //send welcom letter
            if ($_POST['is_send_welcom_letter'] == '1') {
                $this->send_welcom_letter($flag_arr['client_id']);
            }

            //TODO 注册向导
            if ($flag_arr['log_id']) {
//                $this->xredirect("/logging/index/{$flag_arr['log_id']}/clients-step2-{$flag_arr['client_id']}");
                $url_flug = "clients-step2-{$flag_arr['client_id']}";
                $this->modify_log_noty($flag_arr['log_id'], $url_flug);
            } else {
                $this->xredirect("/clients/step2/{$flag_arr['client_id']}");
            }
            //$this->xredirect ("/clients/index"); // succ
        } else {
            //$this->Client->create_json_array('#ClientOrigRateTableId',101,__('Create carriers Failed!',true));
            $this->Session->write('m',$flag_arr);
            $this->xredirect(array('controller' => 'clients', 'action' => 'add')); // failed
        }
    }

    function step2($client_id, $registration_id = NULL)
    {
        $in_have = $this->Client->query('select count(*) from resource_template where trunk_type = 0');
        $this->set('have_in_template', $in_have[0][0]['count']);
        $e_have = $this->Client->query('select count(*) from resource_template where trunk_type = 1');
        $this->set('have_e_template', $e_have[0][0]['count']);

        $this->set("client_id", $client_id);
        $this->set("registration_id", $registration_id);
    }

    function addegress($client_id, $registration_id = NULL)
    {
        if ($this->RequestHandler->isPost()) {
            //print_r($_POST);
            if (empty($_POST['data']['Client']['rate_table_id'])) {
                $_POST['data']['Client']['rate_table_id'] = "NULL";
            }


            $is_same = $this->Clients->checkIsHaveByName($_POST['data']['Client']['alias']);


            if ($is_same != 0) {
                $this->Client->create_json_array('#ClientOrigRateTableId', 101, __('Name already exists!', true));
                $this->xredirect("/clients/addegress/" . $client_id."/".$registration_id);
            }

            if (empty($_POST['data']['Client']['alias'])) {
                $this->Client->create_json_array('#ClientOrigRateTableId', 101, __('Name can not be empty!', true));
                $this->xredirect("/clients/addegress/" . $client_id."/".$registration_id);
            }

            $res = $this->Clients->getAllowedCredit($client_id);
            $enough_balance = 't';

            $res_id = $this->Clients->insertResource1($_POST['data']['Client']['ingress'], $_POST['data']['Client']['egress'], $_POST['data']['Client']['alias'], $client_id, $_POST['data']['Client']['rate_table_id'], $enough_balance);


            $resource_id = $res_id[0][0]['resource_id'];

            if (isset($_POST['accounts'])) {
                $len = count($_POST['accounts']['ip']);
                for ($i = 0; $i < $len; $i++) {
                    $this->Clients->insertHosts2($_POST['accounts']['ip'][$i],0, $_POST['accounts']['port'][$i], $resource_id);
                }
            }
            $this->Client->create_json_array('#ClientOrigRateTableId', 201, __('Succeeded', true));
            $is_finished = $_POST['is_finished'];
            if ($is_finished == 1) {
                $this->xredirect('/clients/index');
            } else {
                $this->xredirect("/clients/addegress/" . $client_id."/".$registration_id); // succ
            }
        }
        $sql = "select name from client where client_id = {$client_id}";
        $result = $this->Gatewaygroup->query($sql);
        $client_name = $result[0][0]['name'];
        $this->set('client_name', $client_name);
        $this->set("client_id", $client_id);
        $this->set("registration_id", $registration_id);
        $this->loadModel('Rate');
        $results = $this->Rate->getAllRates();
        $rate_t = [];
        foreach($results->dataArray as $item){
            $rate_t[$item[0]['rate_table_id']] = $item[0]['name'];
        }
        $this->set('rate', $rate_t);
        $this->autoFillData($registration_id);
    }

    function addingress($client_id, $registration_id = NULL)
    {
        if ($this->RequestHandler->isPost()) {

            $res = $this->Clients->getAllowedCredit($client_id);
            $enough_balance = 't';

            $is_same = $this->Clients->checkIsHaveByName($_POST['data']['Clients']['alias']);
            if ($is_same != 0) {
                $this->Client->create_json_array('#ClientOrigRateTableId', 101, __('Name already exists!', true));
                $this->xredirect("/clients/addingress/" . $client_id."/".$registration_id);
            }


            $arr = array();


            foreach ($_POST['resource']['tech_prefix'] as $v) {
                if (in_array($v, $arr)) {
                    $this->Client->create_json_array('#ClientOrigRateTableId', 101, __('Tech Prefix is duplicate!', true));
                    $this->xredirect("/clients/addingress/" . $client_id."/".$registration_id);
                }
                $arr[] = $v;
            }

            $res_id = $this->Clients->insertResource3($_POST['data']['Clients']['ingress'], $_POST['data']['Clients']['egress'], $_POST['data']['Clients']['alias'], $client_id, $enough_balance);

            $resource_id = $res_id[0][0]['resource_id'];
            if (isset($_POST['accounts'])) {
                $len = count($_POST['accounts']['ip']);
                for ($i = 0; $i < $len; $i++) {
                    $this->Clients->insertHosts2($_POST['accounts']['ip'][$i],0, $_POST['accounts']['port'][$i], $resource_id);
                }
            }
            if (isset($_POST['resource'])) {
                $len2 = count($_POST['resource']['product_id']);
                for ($i = 0; $i < $len2; $i++) {
                    if(empty($_POST['resource']['product_id'][$i])) $_POST['resource']['product_id'][$i] = 0;
                    $this->Clients->opt_client_has_product($client_id, $_POST['resource']['product_id'][$i]);
                    $this->Clients->insertResourcePrefix($resource_id, $_POST['resource']['rate_table_id'][$i], $_POST['resource']['route_strategy_id'][$i], $_POST['resource']['tech_prefix'][$i], $_POST['resource']['product_id'][$i]);
                }
            }
            $this->Client->create_json_array('#ClientOrigRateTableId', 201, __('Succeeded', true));
            $is_finished = $_POST['is_finished'];
            if ($is_finished == 1) {
                $this->xredirect('/clients/index');
            } else {
                $this->xredirect("/clients/addingress/" . $client_id."/".$registration_id); // succ
            }
        }
        $sql = "select name from client where client_id = {$client_id}";
        $result = $this->Gatewaygroup->query($sql);
        $client_name = $result[0][0]['name'];
        $this->set('client_name', $client_name);
        $this->set("client_id", $client_id);
        $this->set("registration_id", $registration_id);

        $this->loadModel('Rate');
        $results = $this->Rate->getAllRates();
        $rate_t = [];
        foreach($results->dataArray as $item){
            $rate_t[$item[0]['rate_table_id']] = $item[0]['name'];
        }
        $this->set('rate_tables', $rate_t);

        $this->set('rout_list', $this->Gatewaygroup->find_route_strategy());

        $sql = "select product_name, id, tech_prefix,rate_table_id,route_strategy_id from product_route_rate_table";
        $items = $this->Gatewaygroup->query($sql);

        $product_arr = array();
        $product_name_arr = array();
        foreach ($items as $item) {
            $product_name_arr[$item[0]['id']] = $item[0]['product_name'];
            $arr = array(
                'product_name' => $item[0]['product_name'],
                'route_strategy_id' => $item[0]['route_strategy_id'],
                'rate_table_id' => $item[0]['rate_table_id'],
                'tech_prefix' => $item[0]['tech_prefix']

            );

            $product_arr[$item[0]['id']] = $arr;
        }

        $this->set('product_arr', $product_arr);
        $this->set('product_name_arr', $product_name_arr);

        $this->autoFillData($registration_id);
    }

    private function autoFillData($registration_id){
        $selected_products_info =[];
        $ips = [];
        if($registration_id){
            $this->loadModel('Registration');
            $this->loadModel('ProductRouteRateTable');
            $registr_products = $this->Client->query("SELECT product_id FROM signup where id = {$registration_id}");
            $selected_products = $registr_products[0][0]['product_id'];
            if(!empty($selected_products)){
                $selected_products = explode(',', $selected_products);
                $selected_products_info = $this->ProductRouteRateTable->find('all', array(
                    'conditions' => array('ProductRouteRateTable.id' => $selected_products),
                ));
            }

            if(!empty($selected_products_info)) {
                foreach ($selected_products_info as &$selected_product) {
                    $selected_product[0] = $selected_product['ProductRouteRateTable'];
                    $selected_product[0]['product_id'] = $selected_product['ProductRouteRateTable']['id'];
                }
            }

            $sql = "SELECT * from signup_ip where signup_id= {$registration_id}";
            $selected_ips = $this->Registration->query($sql);

            if(!empty($selected_ips)) {
                foreach ($selected_ips as $ip) {
                    $ips['ip'][] = $ip[0]['ip'];
                    $ips['need_register'][] = $ip[0]['port'];
                    $ips['mask'][] = $ip[0]['netmark'];
                }
            }
        }
        $this->set('selected_products', $selected_products_info);
        $this->set('ips', $ips);
    }

    function ajax_procuct_list()
    {
        $this->autoRender = false;
        Configure::write('debug', 0);
        $this->layout = '';

        $sql = "select product_name, id, tech_prefix,rate_table_id,route_strategy_id from product_route_rate_table";
        $items = $this->Gatewaygroup->query($sql);

        $product_arr = array();
        $product_name_arr = array();
        foreach ($items as $item) {
            $product_name_arr[$item[0]['id']] = $item[0]['product_name'];
            $arr = array(
                'product_name' => $item[0]['product_name'],
                'route_strategy_id' => $item[0]['route_strategy_id'],
                'rate_table_id' => $item[0]['rate_table_id'],
                'tech_prefix' => $item[0]['tech_prefix']

            );

            $product_arr[$item[0]['id']] = $arr;
        }

        echo json_encode($product_arr);
    }

    function ajax_product_add()
    {
        $this->autoRender = false;
        Configure::write('debug', 0);
        $this->layout = false;
        $product_name = $_POST['product_name'];

        $sql = "select id from product_route_rate_table where product_name = '$product_name' ";
        $items = $this->Gatewaygroup->query($sql);


        if ($items) {
            $ret = false;

        } else {

            $tech_prefix = $_POST['tech_prefix'];
            $rate_table_id = $_POST['rate_table_id'];
            $route_strategy_id = $_POST['route_strategy_id'];

            $sql = "insert into product_route_rate_table(product_name,tech_prefix,rate_table_id,route_strategy_id) values('$product_name','$tech_prefix',$rate_table_id,$route_strategy_id)";
            $this->Gatewaygroup->query($sql);
            $ret = true;

        }


        echo json_encode($ret);
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
        for ($i = 0; $i < $count; $i++) {
            if ($_POST['strategy'] == 0) {
                $sql = "insert into product_items_resource (item_id, resource_id, by_percentage)
                        values ({$item_id}, {$_POST['trunks'][$i]}, {$_POST['percents'][$i]})";
            } else {
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
        for ($i = 0; $i < $count; $i++) {
            if($dynamic_route_id && $_POST['trunks'][$i]) {
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
        if ($count[0][0]['count'] > 0) {
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
        if ($count[0][0]['count'] > 0) {
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
        foreach ($result as $val) {
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
        foreach ($result as $val) {
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
        foreach ($result as $val) {
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
        if ($this->Client->del($id)) {
            $this->Client->create_json_array('', 201, __('del_suc', true));
        } else {
            $this->Client->create_json_array('', 101, __('del_fail', true));
        }
        $this->Session->write('m', Client::set_validator());
        $this->redirect(array('action' => 'index'));
    }

    function ajax_del($id, $type = 'false')
    {
        Configure::write('debug', 0);
        if ($this->Session->read('login_type') == 2)
        {
            if ($this->Session->read('sst_agent_info.Agent.edit_permission'))
                $this->redirect_denied();
        }
        $type = _filter_array(Array('false' => false, 'true' => true), $type);
        $this->Client->query("delete from users_limit where client_id = {$id}");
        $rst = $this->Client->del($id, $type);
//        sleep(5);
//        $rst = 1;
        if ($rst === false)
            echo json_encode(array('status' => 0));
        else
            echo json_encode(array('status' => 1));
    }

//    function del_carrier_log($name = "")
//    {
////        Configure::write('debug', 0);
//        $this->autoLayout = FALSE;
//        $this->autoRender = FALSE;
//        $detail = "Carrier\'s name:{$name}";
//        $log_id = $this->Client->logging(1, 'Carrier', $detail);
//        $this->xredirect("/logging/index/{$log_id}/clients-index");
//
//    }

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
        if (isset($_POST['searchkey'])) {
            $results = $this->Client->likequery_rss($_POST['searchkey'], $currPage, $pageSize, $search_res);
            $this->set('searchkey', $_POST['searchkey']);
            $this->set('p', $results);
            return;
        }
        //搜索
        if (!empty($this->data['Client'])) {
            $results = $this->Client->queryClient($this->data, $currPage, $pageSize, $search_res);
            $this->set('search', 'search'); //搜索设置
        } else {
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
        if (isset($_POST['searchkey'])) {
            $results = $this->Client->likequery_rss($_POST['searchkey'], $currPage, $pageSize, $search_res);
            $this->set('searchkey', $_POST['searchkey']);
            $this->set('p', $results);
            return;
        }

        //搜索
        if (!empty($this->data['Client'])) {
            $results = $this->Client->queryClient($this->data, $currPage, $pageSize, $search_res);
            $this->set('search', 'search'); //搜索设置
        } else {
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
        if (isset($_POST['searchkey'])) {
            $results = $this->Client->likequery_cardss($_POST['searchkey'], $currPage, $pageSize);
            $this->set('searchkey', $_POST['searchkey']);
            $this->set('p', $results);
            return;
        }
        //搜索
        if (!empty($this->data['Client'])) {
            $results = $this->Client->queryClient($this->data, $currPage, $pageSize, $search_res);
            $this->set('search', 'search'); //搜索设置
        } else {
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
        if (isset($_POST['searchkey'])) {
            $results = $this->Client->likequery_seriess($_POST['searchkey'], $currPage, $pageSize);
            $this->set('searchkey', $_POST['searchkey']);
            $this->set('p', $results);
            return;
        }
        //搜索
        if (!empty($this->data['Client'])) {
            $results = $this->Client->queryClient($this->data, $currPage, $pageSize, $search_res);
            $this->set('search', 'search'); //搜索设置
        } else {
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
        if (isset($_POST['searchkey'])) {
            $batch = $_SESSION['batch'];
            $results = $this->Client->likequery_batchss($_POST['searchkey'], $currPage, $pageSize, $batch);
            $this->set('searchkey', $_POST['searchkey']);
            $this->set('p', $results);
            return;
        }
        //搜索
        if (!empty($this->data['Client'])) {
            $results = $this->Client->queryClient($this->data, $currPage, $pageSize, $search_res);
            $this->set('search', 'search'); //搜索设置
        } else {
            if (!empty($this->params['pass'])) {
                $batch = $this->params['pass'][0];
                $_SESSION['batch'] = $batch;
            } else {
                $batch = $_SESSION['batch'];
            }
            $results = $this->Client->findAll_batchss($currPage, $pageSize, $batch);
        }
        $this->set('p', $results);
    }

    public function ss_rate()
    {
        $this->layout = '';
        $search = (!empty($_GET['search']) && strcmp('Keyword', $_GET['search'])) ? $_GET['search'] : "";
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
        if (isset($_POST['searchkey'])) {
            $results = $this->Client->likequery_codess($_POST['searchkey'], $currPage, $pageSize);
            $this->set('searchkey', $_POST['searchkey']);
            $this->set('p', $results);
            return;
        }
        //搜索
        if (!empty($this->data['Client'])) {
            $results = $this->Client->queryClient($this->data, $currPage, $pageSize, $search_res);
            $this->set('search', 'search'); //搜索设置
        } else {
            $results = $this->Client->findAll_codess($currPage, $pageSize);
        }
        $this->set('p', $results);
    }

    /**
     * 禁用客户
     */
    function dis_able($credit_management = false)
    {
        $id = base64_decode($this->params['pass'][0]);
        $mesg_info = $this->Client->query("select name from client where client_id = {$id}");
        $this->Client->query("update client set  status=false where  client_id= $id;");
        $this->Client->query("update resource set  active=false where  client_id= $id;");
        $this->Client->create_json_array('', 201, __('The Carrier[%s] is disabled successfully!', true, $mesg_info[0][0]['name']));
        $this->Session->write("m", Client::set_validator());
        if($credit_management){
            $this->redirect('/credit_managements/index');
        }
        $this->redirect(array('action' => 'index', '?' => $this->params['getUrl']));
    }

    function inactivateAjax() {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $clientId = base64_decode($this->params['form']['clientId']);
        $mesg_info = $this->Client->query("select name from client where client_id = {$clientId}");
        $this->Client->query("update client set  status=false where  client_id= $clientId;");
        $this->Client->query("update resource set  active=false where  client_id= $clientId;");

        $sqls = [
            "update users  set   active= true  where client_id=$clientId  and user_type=3;",
            "update resource  set   active= true  where client_id=$clientId;",
            "update client  set   status= true  where client_id=$clientId;",
        ];
        $rollback_sql = implode("::",$sqls);
        $rollback_msg = "Modify Carrier [" . $mesg_info[0][0]['name'] . "] operation have been rolled back!";
        $this->Client->logging(6, 'Carrier', "Carrier name::{$mesg_info[0][0]['name']}",$rollback_sql,$rollback_msg);

        echo json_encode(array('success' => true, 'theme' => 'jmsg-success', 'message' => __('The Carrier [%s] is disabled successfully!', true, $mesg_info[0][0]['name'])));
    }

    function activateAjax() {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $clientId = base64_decode($this->params['form']['clientId']);
        $mesg_info = $this->Client->query("select name from client where client_id = {$clientId}");
        $this->Client->active($clientId);
        $this->Client->query("update resource set  active=true where  client_id= $clientId;");

        $sql1 = "update client set  status=false where  client_id= $clientId;";
        $sql2 = "update resource set  active=false where  client_id= $clientId;";
        $rollback_sql = $sql1."::".$sql2;
        $rollback_msg = "Modify Carrier [" . $mesg_info[0][0]['name'] . "] operation have been rolled back!";

        $this->Client->logging(5, 'Carrier', "Carrier name::{$mesg_info[0][0]['name']}",$rollback_sql,$rollback_msg);

        echo json_encode(array('success' => true, 'theme' => 'jmsg-success', 'message' => __('The Carrier [%s] is enabled successfully!', true, $mesg_info[0][0]['name'])));
    }

    function active($credit_management = false)
    {
        $id = base64_decode($this->params['pass'][0]);
        $mesg_info = $this->Client->query("select name from client where client_id = {$id}");
        $this->Client->active($id);
        $this->Client->query("update resource set  active=true where  client_id= $id;");
        $this->Client->create_json_array('', 201, __('The Carrier[%s] is enabled successfully!', true, $mesg_info[0][0]['name']));
        $this->Session->write("m", Client::set_validator());
        if($credit_management){
            $this->redirect('/credit_managements/index');
        }
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
        if (!preg_match('/^[0-9]+(\.[0-9]{1,3})?$/', $balance)) {
            echo 'format';
            exit();
        }
        $now_balance = $this->Client->query("select balance from c4_client_balance where client_id = '$resid' ");
        $new_balance = $balance;
        $n_b = empty($now_balance[0][0]['balance']) ? 0 : $now_balance[0][0]['balance'];
        if ($way == 'inc') {
            $new_balance = $n_b + $balance;
        }
        if ($way == 'dec') {
            $new_balance = $n_b - $balance;
        }
        if ($way == 'perin') {
            $new_balance = $n_b + $n_b * $balance / 100;
        }
        if ($way == 'perde') {
            $new_balance = $n_b - $n_b * $balance / 100;
        }

        $qs = $this->Client->clientBalanceOperation($resid, $new_balance, 0, true);

        if (count($qs) == 0) {
            echo $resid;
        } else {
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
        if ($this->Client->download_by_sql($download_sql, array('objectives' => 'clients'))) {
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

        if ($_SESSION['login_type'] == 3) {
            $this->paginate['conditions'] = array('Client.client_id' => $_SESSION['sst_client_id']);
            // $this->paginate['conditions']['Users_limit.user_id'] = $_SESSION['sst_user_id'];
        } else {
            $this->paginate['conditions'] = $this->_filter_conditions(Array('id' => 'Client.client_id', 'payment_term_id' => 'Client.payment_term_id', 'name' => 'Client.search_name', 'client_type', 'search', 'company' => 'Client.company', 'status'));
            if (empty($this->paginate['conditions'])) {
                $this->paginate['conditions'] .= "Users_limit.user_id = {$_SESSION['sst_user_id']}";
            } else {
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
        if ($this->_isAjax()) {
            $this->render('index_ajax');
        }
    }

    function tobu_client()
    {
//        $this->autoRender = false;
//        $this->autoLayout = false;
//        $sql = "select t1.create_time as new_time,t2.create_time,t1.client_id from client as t1 inner join c4_client_balance as t2 on t1.client_id = t2.client_id::integer ;";
//        $data = $this->Client->query($sql);
//        foreach ($data as $item)
//        {
//
//            $sql1 = "update client_balance_operation_action set create_time = {$item[0]['new_time']} where client_id = '{$item[0]['client_id']}'";
//            $this->Client->query($sql1);
//        }
    }
    function index()
    {
        if ($_SESSION['login_type'] != 1)
        {
            $this->redirect('/clients/carrier/');
        }
        $this->pageTitle = 'Management/Carriers';
        $temp = isset($_SESSION['paging_row']) ? $_SESSION['paging_row'] : 100;
        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = $temp : $pageSize = $_GET['size'];
        //$_SESSION['paging_row'] = $pageSize;
        //check_route
        //$config['check_route']['carrier_name'] = "Check Route";
        //$config['check_route']['trunk_name'] = "check_route_ingress_trunk";
        Configure::load('myconf');
        $test_carrier = Configure::read('check_route.carrier_name');

        $where = " AND client.client_type is null and client.name != '{$test_carrier}' ";
        //var_dump($where);
        if (isset($_GET['filter_payment_term_id']))
        {
            $where .= " AND client.payment_term_id =" . intval($_GET['filter_payment_term_id']);
        }

        if(isset($_GET['advsearch'])) {
            if(isset($_GET['terms']) && !empty($_GET['terms'])) {
                $where .= " AND client.mode = {$_GET['terms']}";
            }
            if(isset($_GET['payment_term_id']) && !empty($_GET['payment_term_id'])) {
                $where .= " AND client.payment_term_id = {$_GET['payment_term_id']}";
            }
        }

        if (isset($_GET['submit']))
        {
            $filter_type = $_GET['filter_client_type'];
            $client_name = $_GET['search'];
            switch ((int) $filter_type)
            {
                case 1:
                    $where .= " AND client.status = true";
                    break;
                case 2:
                    $where .= " AND client.status = false";
                    break;
            }

            $mode_type = $_GET['terms'];
            switch ((int) $mode_type)
            {
                case 1:
                    $where .= " AND client.mode = 1";
                    break;
                case 2:
                    $where .= " AND client.mode = 2";
                    break;
            }


            if (!empty($client_name) && $client_name != 'Search') {
                $client_name = trim($client_name);
                $where .= " AND (client.company ilike '%{$client_name}%' or client.name ilike '%{$client_name}%')";
            }
        } else
        {
            $where .= " AND client.status = true";
        }

        if ($this->_get('group_id')){
            $group_id = intval(base64_decode($this->_get('group_id')));
            $where .= " AND client.group_id =" . $group_id;
            $this->set('group_id', $group_id);
        }


        $sst_user_id = $_SESSION['sst_user_id'];
        $count = $this->Client->getclients_count($sst_user_id, $where);
        require_once 'MyPage.php';
        $page = new MyPage ();
        $page->setTotalRecords($count);
        $page->setCurrPage($currPage);
        $page->setPageSize($pageSize);
        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;
        $order_by = 'order by client.name ASC';
        if (isset($_GET['order_by']))
        {
            $order_by_arr = explode('-', $_GET['order_by']);
            if (count($order_by_arr) == 2)
                $order_by = "order by " . $order_by_arr[0] . ' ' . $order_by_arr[1];
        }
        $data = $this->Client->getclients($sst_user_id, $order_by, $where, $pageSize, $offset);

        if(isset($_GET['advsearch'])) {

            if (isset($_GET['last_login_on']) && !empty($_GET['last_login_on'])) {
                $resData = array();
                foreach ($data as $dataItem) {
                    $sql = "select web_session.user_id,users.name as user_name ,web_session.create_time,host,agent,msg from web_session left join users on users.user_id=web_session.user_id	where (users.name != 'dnl_support' or web_session.user_id is null) and users.client_id={$dataItem[0]['client_id']} and web_session.create_time between '{$_GET['last_login_on']} 00:00:00' and '{$_GET['last_login_on']} 23:59:59' order by create_time desc limit 1";
                    $lastLogin = $this->Client->query($sql);
                    if (!empty($lastLogin)) {
                        array_push($resData, $dataItem);
                    }
                }
                $data = $resData;
            }

            if (isset($_GET['registered_on']) && !empty($_GET['registered_on'])) {
                $resData = array();
                foreach ($data as $dataItem) {
                    $sql = "SELECT * FROM signup WHERE contact_name='{$dataItem[0]['name']}' ORDER BY signup_time DESC LIMIT 1";

                    $registeredOn = $this->Client->query($sql);
                    if (!empty($registeredOn) && isset($registeredOn[0][0]['signup_time'])) {
                        $tempDate = strtotime($registeredOn[0][0]['signup_time']);
                        if (date("Y-m-d", $tempDate) == $_GET['registered_on']) {
                            array_push($resData, $dataItem);
                        }
                    }
                }
                $data = $resData;
            }
        }
        $this->loadModel('FinanceHistoryActual');
//        foreach ($data as &$item)
//        {
//            $item[0] = array_merge($item[0], $this->getBalance($item[0]['client_id']));
//        }
        $page->setDataArray($data);
        $this->set('p', $page);
        $this->set('lang', $this->Session->read("Config.language"));
        $this->set('debug',Configure::read("debug"));
        $sql = "SELECT is_show_mutual_balance FROM system_parameter";
        $is_show_mutual_balance_arr = $this->Client->query($sql);
        $is_show_mutual_balance = $is_show_mutual_balance_arr[0][0]['is_show_mutual_balance'];
        $paymentTerms = $this->Client->query("SELECT * FROM payment_term");
        $this->set('payment_terms', $paymentTerms);
        $this->set('get_data', $_GET);
        $this->set('is_show_mutual_balance', $is_show_mutual_balance);

    }

    function getBalance($client_id){

        $current_finance = $this->FinanceHistoryActual->get_current_finance_detail($client_id);
        $last_day_balance = $this->FinanceHistoryActual->get_last_day_balance($client_id);
        $balance = $last_day_balance
            + $current_finance['payment_received']
            + $current_finance['credit_note_received']
            + $current_finance['debit_received']
            + $current_finance['unbilled_outgoing_traffic']
            - $current_finance['payment_sent']
            - $current_finance['credit_note_sent']
            - $current_finance['debit_sent']
            - $current_finance['unbilled_incoming_traffic'];
        return ['balance' => $balance];
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

    public function clients_payment($type = 0, $clientId = null)
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

        if(!empty($clientId)) {
            $client_id = $clientId;
        } else {
            $client_id = $_SESSION['sst_client_id'];
        }

        $temp = isset($_SESSION['paging_row']) ? $_SESSION['paging_row'] : 100;
        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = $temp : $pageSize = $_GET['size'];

//        $count = $this->Client->clients_payment_count($client_id, $start_time, $end_time);
        require_once 'MyPage.php';
        $page = new MyPage ();
//        $page->setTotalRecords($count);
        $page->setCurrPage($currPage);
        $page->setPageSize($pageSize);
        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;

        $data = $this->Client->client_payment($client_id, $start_time, $end_time, $pageSize, $offset);
        $method = array('paypal', 'yourpay','Stripe', 'Received', 'Sent');
        $this->set('method', $method);
        $page->setDataArray($data);
        $this->set('p', $page);
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
        }else{
            echo 'true';
        }
        die;
    }

    public function check_name($name = null, $client_id = null)
    {
        Configure::write('debug', 0);
        $ch_name = null;
        $this->layout = 'ajax';
        if (!empty($name))
        {
            $sql = "select count(*) as name_num from client where name='$name'";
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
        if ($_POST['jur_type'] == 0 || $_POST['jur_type'] == 1) {
            $add_ratetable_result = $this->Client->query("insert into rate_table(name,code_deck_id,currency_id, rate_type,jur_type)
                values ('{$_POST['name']}', {$_POST['codedeck']}, {$_POST['currency']}, {$_POST['ratetype']},{$_POST['jur_type']}) 
                RETURNING rate_table_id");
        } else {
            $jurcountry = $_POST['jurcountry'] ? $_POST['jurcountry'] : 'null';
            $add_ratetable_result = $this->Client->query("insert into rate_table(name,code_deck_id,currency_id,jurisdiction_country_id, rate_type,jur_type)
                values ('{$_POST['name']}', {$_POST['codedeck']}, {$_POST['currency']}, {$jurcountry}, {$_POST['ratetype']},{$_POST['jur_type']})
                RETURNING rate_table_id");
        }
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
        $sql = "INSERT INTO client_balance_operation_action (ingress_balance, egress_balance, balance, client_id, action)   VALUES (ingress_balance::real + {$ingress_amount}, egress_balance::real + {$egress_amount}, balance = balance::real + {$total_amount}, {$client_id}, 1)";
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
            and ingress_client_id::integer = c4_client_balance.client_id::integer) as buy, (select sum(egress_cost::numeric)
            from client_cdr{$today_date} where time >= '{$cdr_start_date}' and time <= '{$cdr_end_date}' 
            and egress_client_id::integer = c4_client_balance.client_id::integer) as sell, balance::numeric as bod_balance, '{$cdr_end_date}' as date 
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
                    and ingress_client_id::integer = c4_client_balance.client_id::integer) as buy, (select sum(egress_cost::numeric)
                    from client_cdr{$today_date} where time >= '{$cdr_start_date}' and time <= '{$cdr_end_date}' 
                    and egress_client_id::integer = c4_client_balance.client_id::integer) as sell, balance::numeric as bod_balance, '{$cdr_end_date}' as date 
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
                $this->Client->create_json_array('', 201, __('Successfully!', true));
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
                $this->Client->create_json_array('', 201, __('Successfully!', true));
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
//
//        foreach ($rate_watch_emails as $rate_watch_email)
//        {
//            if (preg_match('/^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+((\.[a-zA-Z0-9_-]{2,3}){1,2})$/', $rate_watch_email[0]['corporate_contact_email']))
//            {
//                $rate_emails[] = $rate_watch_email[0]['corporate_contact_email'];
//            }
//        }

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

        if (!class_exists('Systemparam')) {
            $this->loadModel('Systemparam');
        }

        // save response
        if(!empty($_POST) && is_array($_POST) && isset($_POST['invoice'])){
            $res = json_encode($_POST);
            $sql = "update payline_history set response = '{$res}' where invoice_id='{$_POST['invoice']}'";
            $this->Client->query($sql);
        }

        $paypalTestMode = $this->Systemparam->find('first', array(
            'fields' => array('paypal_test_mode')
        ));

        $sandboxIfNeeded = $paypalTestMode['Systemparam']['paypal_test_mode'] ? "sandbox." : "";

//        if ($paypalTestMode['Systemparam']['paypal_test_mode']) {
//            // test mode
//
//        } else {
//            // real money
//        }

        $fh = fopen("/tmp/test", 'w+');
        fwrite($fh, print_r($_POST, TRUE) . "\n\n");

        $this->ApiLog->write('Response: ' . json_encode($_POST), 4);

        // STEP 1: read POST data
        // Reading POSTed data directly from $_POST causes serialization issues with array data in the POST.
        // Instead, read raw POST data from the input stream.
        $raw_post_data = file_get_contents('php://input');
        $raw_post_array = explode('&', $raw_post_data);
        $myPost = array();
        foreach ($raw_post_array as $keyval) {
            $keyval = explode ('=', $keyval);
            if (count($keyval) == 2)
                $myPost[$keyval[0]] = urldecode($keyval[1]);
        }
        // read the IPN message sent from PayPal and prepend 'cmd=_notify-validate'
        $req = 'cmd=_notify-validate';
        if (function_exists('get_magic_quotes_gpc')) {
            $get_magic_quotes_exists = true;
        }
        foreach ($myPost as $key => $value) {
            if ($get_magic_quotes_exists == true && get_magic_quotes_gpc() == 1) {
                $value = urlencode(stripslashes($value));
            } else {
                $value = urlencode($value);
            }
            $req .= "&$key=$value";
        }

        $logString = '';

        // Step 2: POST IPN data back to PayPal to validate
        $ch = curl_init("https://ipnpb.{$sandboxIfNeeded}paypal.com/cgi-bin/webscr");
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));
        // In wamp-like environments that do not come bundled with root authority certificates,
        // please download 'cacert.pem' from "https://curl.haxx.se/docs/caextract.html" and set
        // the directory path of the certificate as shown below:
        // curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__) . '/cacert.pem');
        if ( !($res = curl_exec($ch)) ) {
            $logString .= "Got " . curl_error($ch) . " when processing IPN data\r\n";
            // error_log("Got " . curl_error($ch) . " when processing IPN data");
            curl_close($ch);
        } else {
            curl_close($ch);

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

            $logString .= "{$res}\r\n";

            // inspect IPN validation result and act accordingly
            if (strcmp ($res, "VERIFIED") == 0) {
                // The IPN is verified, process it
                $current_invoice_info = $this->Client->check_finance_info($invoice);
                fwrite($fh, print_r($current_invoice_info, TRUE) . "\n\n");
                $logString .= json_encode($current_invoice_info) . "\r\n";
                if ($current_invoice_info[0][0]['status'] == 0) {
                    $this->Client->change_finance_status($current_invoice_info[0][0]['id'], 2, floatval($payment_fee), $txn_id, $payer_email);
                    $amount = floatval($payment_amount) - floatval($payment_fee);
                    $client_id = $current_invoice_info[0][0]['client_id'];
                    $charge = $this->Client->query("SELECT chargetotal,charge_type, charge_amount FROM payline_history WHERE id = '{$current_invoice_info[0][0]['id']}'");
                    if ($charge[0][0]['charge_type'] == 0) {
                        $to_service = $amount;
                        $to_update = $amount - $charge[0][0]['charge_amount'];
                    } else {
                        $to_service = $amount + $charge[0][0]['charge_amount'];
                        $to_update = $amount;
                    }
                    $this->Client->update_finance($client_id, $to_update, $current_invoice_info[0][0]['id']);

                    $client_info = $this->Client->query("select name, email, company from client where client_id = '{$client_id}'");

                    //发邮件

                    $sql = "select sys_id, daily_payment_confirmation, daily_payment_email, notify_carrier, notify_carrier_cc
                                from system_parameter";
                    $mail_info_arr = $this->Client->query($sql);

                    $sql = <<<SQL
select payment_from, payment_from_cc, payment_subject, payment_content
from mail_tmplate
SQL;
                    $mailTemplateData = $this->Client->query($sql);

                    if ($mail_info_arr[0][0]['notify_carrier']) {

                        $tagsArray =  array(
                            '{amount}',
                            '{receiving_time}',
                            '{client_name}',
                            '{company_name}',
                            '{actual received}',
                            '{total debit amount}',
                            '{transaction_id}',
                            '{finance_fee}'
                        );
                        $replaceTagsArray = array(
                            round(floatval($amount), 2),
                            date("Y-m-d H:i:s"),
                            $client_info[0][0]['name'],
                            $client_info[0][0]['company'],
                            round(floatval($amount - $payment_fee), 2),
                            round(floatval($to_update), 2),
                            $txn_id,
                            round(floatval($payment_fee), 2)
                        );

                        $mail_subject = str_replace($tagsArray, $replaceTagsArray, $mailTemplateData[0][0]['payment_subject']);
                        $mail_content = str_replace($tagsArray, $replaceTagsArray, $mailTemplateData[0][0]['payment_content']);
                        $cc = array_merge(array($mailTemplateData[0][0]['payment_from_cc']), array($mail_info_arr[0][0]['notify_carrier_cc']));
                        $from = $mailTemplateData[0][0]['payment_from'];
                        $cc = implode(';', $cc);

                        // Save to email log
                        $this->loadModel('EmailLog');

                        $this->EmailLog->save(array(
                            'send_time' => date('Y-m-d H:i:s'),
                            'client_id' => $client_id,
                            'email_addresses' => $client_info[0][0]['billing_email'],
                            'type' => 21,
                            'subject' => $mail_subject,
                            'content' => $mail_content
                        ));

                        $logId = $this->EmailLog->getLastInsertId();
                        $sendResult = $this->VendorMailSender->send(
                            $mail_subject,
                            $mail_content,
                            $client_info[0][0]['email'],
                            $cc,
                            $from,
                            null,
                            $logId
                        );

                        if ($sendResult['status'] != 1) {
                            $this->Client->create_json_array('', 201, __('The Email is sent successfully!', true));
                            $this->Session->write('m', Transaction::set_validator());
                        } else {
                            $this->Client->create_json_array('', 101, __('Failed!', true));
                            $this->Session->write('m', Transaction::set_validator());
                        }
                    }
                }
            } else if (strcmp ($res, "INVALID") == 0) {
                // IPN invalid, log for manual investigation
                $current_invoice_info = $this->Client->check_finance_info($invoice);
                fwrite($fh, print_r($current_invoice_info, TRUE) . "\n\n");
                if ($current_invoice_info[0][0]['status'] == 0)
                {
                    $this->Client->change_finance_status($current_invoice_info[0][0]['id'], 1, floatval($payment_fee), $txn_id, $payer_email);
                }
            }
        }

        $this->ApiLog->write($logString, 4);

    }

    public function client_pay($type = 1, $clientId = null, $invoiceID = null)
    {
        $this->set('pay_type', $type);
        $this->loadModel('Systemparam');
        $pay_info = $this->Systemparam->find('first',array(
            'fields' => array('paypal_account','stripe_public_account','stripe_account', 'stripe_service_charge', 'paypal_service_charge')
        ));
        $pay_type_arr = array();
        if($pay_info['Systemparam']['paypal_account'])
            $pay_type_arr[1] = 'Paypal';
        if($pay_info['Systemparam']['stripe_public_account'] && $pay_info['Systemparam']['stripe_account'])
            $pay_type_arr[2] = 'Stripe';
        if(empty($pay_type_arr))
            $this->redirect("/payment_history");
        $this->set('pay_type_arr',$pay_type_arr);
//        $s_key = "sk_test_XUlAkpW2bDQ6ytSrfapWvnfT";
//        $skey = "pk_test_XRRz9QPMVds1zSkQWz5IqTOM";
//        $this->set('scripe_key',$skey);
//        $this->set('pkey', $pay_key);
        if($invoiceID){
            $this->set('invoiceID', base64_decode($invoiceID));
        }
        $this->set('clientId', $clientId);
        $this->set('scripe_key',$pay_info['Systemparam']['stripe_public_account']);
        $this->set('stripe_service_charge',$pay_info['Systemparam']['stripe_service_charge']);
        $this->set('paypal_service_charge',$pay_info['Systemparam']['paypal_service_charge']);
    }

    public function writeLog()
    {
        Configure::write('debug', 0);

        if ($this->RequestHandler->isPost()) {
            $url = $_POST['url'];
            $dataJson = $_POST['data'];
            $this->ApiLog->write('Request: ' . json_encode($dataJson) . $url, 4);
        }
        exit;
    }

    public function client_pay_do($clientId = null)
    {

        if ($this->RequestHandler->isPost())
        {

            $handle = fopen('/tmp/payment.log', 'a');
            fwrite($handle, print_r($_POST, TRUE));

            if(!empty($clientId)) {
                $client_id = $clientId;
            } else {
                $client_id = $_SESSION['sst_client_id'];
            }
            $method = (int) $_POST['platform'];
            $cardnumber = @$_POST['cardnumber'];
            $cardexpmonth = @$_POST['cardexpmonth'];
            $cardexpyear = @$_POST['cardexpyear'];
            $cvmvalue = @$_POST['cvmvalue'];
            $address1 = @$_POST['address1'];
            $address2 = @$_POST['address2'];
            $credit_card_type = @$_POST['credit_card_type'];
            $city = @$_POST['city'];
            $state_province = @$_POST['state_province'];
            $zip_code = @$_POST['zip_code'];
            $country = @$_POST['country'];

            $this->loadModel('Systemparam');
            $pay_info = $this->Systemparam->find('first',array(
                'fields' => array('paypal_account','stripe_public_account','stripe_account', 'stripe_service_charge',
                    'stripe_service_charge','paypal_service_charge','charge_type')
            ));

            $pay_info['Systemparam']['paypal_service_charge'] = $pay_info['Systemparam']['paypal_service_charge'] == '' ? 0 : $pay_info['Systemparam']['paypal_service_charge'];
            $pay_info['Systemparam']['stripe_service_charge'] = $pay_info['Systemparam']['stripe_service_charge'] == '' ? 0 : $pay_info['Systemparam']['stripe_service_charge'];
            // 0 - Credit Total Amount (amount to service: amount; amount to update: amount - service charge)
            // 1 - Create Actual Received Amount (amount to service: amount + service charge; amount to update: amount)
            $charge_type = $pay_info['Systemparam']['charge_type'] ? $pay_info['Systemparam']['charge_type'] : 0;

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
                $this->redirect('/clients/client_pay/2');
            }
            // stripe
            else if ($method == 2)
            {
                require_once("vendors/stripe/lib/Stripe.php");
                $data = array('error' => '');
                $da = array();
                $da['card_num'] = $this->_post('card_num');
                $da['monthly_fee'] = $this->_post('monthly_fee');
                if ($charge_type == 0) {
                    $to_service = $da['monthly_fee'];
                    $to_update = $da['monthly_fee'] - $pay_info['Systemparam']['stripe_service_charge'];
                } else {
                    $to_service = $da['monthly_fee'] + $pay_info['Systemparam']['stripe_service_charge'];
                    $to_update = $da['monthly_fee'];
                }
                $myorder["cardexpmonth"] = $this->_post('month');
                $myorder["cardexpyear"] = $this->_post('year');

                if (!preg_match('/^[\-+]?[0-9]*\.?[0-9]+$/', $da['monthly_fee']))
                {
                    $data['error'] = "The Monthly fee must be numeric.";
                }
                else if (empty($da['monthly_fee']))
                {
                    $data['error'] = "The Monthly fee can not be empty.";
                }

                $skey = $pay_info['Systemparam']['stripe_account'];
                Stripe::setApiKey($skey);
                $error = '';
                $success = '';

                $stripetoken = $this->_post('stripeToken');


                try
                {
                    if (empty($stripetoken))
                        throw new Exception("The Stripe Token was not generated correctly");
                    $stripe = Stripe_Charge::create(array("amount" => $to_service * 100,
                        "currency" => "usd",
                        "card" => $stripetoken));

                    $transaction_id = $stripe->id ?: '';
                }
                catch (Exception $e)
                {
                    $data['error'] = $e->getMessage();
                }


                if (empty($data['error']))
                {


                    $sql = "insert into payline_history(chargetotal, method, cardnumber, cardexpmonth, cardexpyear,charge_type,charge_amount, client_id, transaction_id) values (
    				{$to_service}, 2,  '{$da['card_num']}', '{$cardexpmonth}', '{$cardexpyear}',{$charge_type},{$pay_info['Systemparam']['stripe_service_charge']}, {$client_id},'{$transaction_id}') returning id";
                    $result = $this->Client->query($sql);
                    $id = $result[0][0]['id'];

                    if ($id)
                    {
                        $current_invoice_info = $this->Client->check_finance_info2($id);
                        $status = $current_invoice_info[0][0]['status'];
                        fwrite($handle, print_r($current_invoice_info, TRUE));
                        if ($status == 0)
                        {
                            $this->Client->change_finance_status($id, 2);
                            $client_id = $current_invoice_info[0][0]['client_id'];
                            $this->Client->update_finance($client_id, $to_update);
                            $this->Client->create_json_array('', 201, __('Payment successfully', true));

                            $client_info = $this->Client->query("select name,billing_email,company_name from client where client_id = '{$client_id}'");
                            $sql = "select sys_id, daily_payment_confirmation, daily_payment_email, notify_carrier, notify_carrier_cc,"
                                . "payment_from,payment_setting_subject,payment_content from system_parameter";
                            $mail_info_arr = $this->Client->query($sql);

                            $sql = "select payment_from_cc,payment_subject,payment_content from mail_tmplate";
                            $mail_tmp_arr = $this->Client->query($sql);

                            if ($mail_info_arr[0][0]['notify_carrier'])
                            {
                                $convert_table = array(
                                    '{amount}' => round(floatval($da['monthly_fee']),2),
                                    '{receiving_time}' => date("Y-m-d H:i:s"),
                                    '{client_name}' => $client_info[0][0]['name'],
                                    '{company_name}' => $client_info[0][0]['company_name'],
                                    '{actual received}' => round(floatval($da['monthly_fee']),2),
                                    '{total debit amount}' => round(floatval($to_update),2),
                                    '{transaction_id}' => $id,
                                    '{finance_fee}' => 0
                                );

                                $mail_subject = strtr($mail_info_arr[0][0]['payment_setting_subject'], $convert_table);
                                $mail_content = strtr($mail_info_arr[0][0]['payment_content'], $convert_table);
                                $mail_list = explode(';', $client_info[0][0]['billing_email']);
                                $cc_list = explode(';', $mail_info_arr[0][0]['notify_carrier_cc']);

                                $sendResult = $this->VendorMailSender->send($mail_subject, $mail_content, $mail_list, $cc_list);

                                if ($sendResult['status'] != 1)
                                {
                                    $this->Client->create_json_array('', 201, __('The Email is sent succesfully!', true));
                                    $this->Session->write('m', Client::set_validator());
                                }
                                else
                                {
                                    $this->Session->write('m', $this->Client->create_json(101, $sendResult['error']));
                                }
                            }
                        }
                    }
                }
                else
                {
                    $sql = "insert into payline_history(chargetotal, method, cardnumber, cardexpmonth, cardexpyear, client_id) values (
    				{$to_service}, 2,  '{$da['card_num']}', '{$cardexpmonth}', '{$cardexpyear}', {$client_id}) returning id";

                    $result = $this->Client->query($sql);
                    $id = $result[0][0]['id'];

                    if ($id)
                    {
                        $current_invoice_info = $this->Client->check_finance_info2($id);
                        $status = $current_invoice_info[0][0]['status'];
                        fwrite($handle, print_r($current_invoice_info, TRUE));
                        if ($status == 0)
                        {
                            $now = date("Y-m-d H:i:s");
                            $error = $data['error'];
                            $this->Client->query("update payline_history set status = 1,modified_time = '{$now}',error = '$error' where id = {$id}");

                        }
                    }
                    //_dloaderror('public/error',$data['error']);
                    $this->Client->create_json_array('', 301, __('The Payment is failed. Reason:' . $data['error'], true));
                }

                $this->Session->write('m', Client::set_validator());

                if (empty($data['error']))
                {
                    $this->redirect('/payment_history/index');
                }
                else
                {
                    $this->xredirect('/clients/client_pay/3');
                }
            }
            // paypal (0)
            else
            {
                $amount = $_POST['chargetotal2'];
                $domain = $this->get_domain();
                $invoice =  uniqid();
                $sql = "insert into payline_history(chargetotal, method, client_id, invoice_id,charge_type,charge_amount) values (
    			{$amount}, 0, {$client_id}, '{$invoice}',{$charge_type},{$pay_info['Systemparam']['paypal_service_charge']}) returning id";

                $invoice_id = isset($_POST['invoice_id']) && !empty(trim($_POST['invoice_id'])) ? $_POST['invoice_id'] :$invoice;
                $result = $this->Client->query($sql);
                $id = $result[0][0]['id'];
                $payinfo = $this->Client->query("select daily_payment_email, paypal_account, paypal_test_mode from system_parameter");
                $business = $payinfo[0][0]['paypal_account'];
                $paypalTestMode = $payinfo[0][0]['paypal_test_mode'];

                $this->set('business', $business);
                $this->set('paypalTestMode', $paypalTestMode);
                $this->set('id', $id);
                if ($charge_type == 0) {
                    $to_service = $amount;
                    $to_update = $amount - $pay_info['Systemparam']['paypal_service_charge'];
                } else {
                    $to_service = $amount + $pay_info['Systemparam']['paypal_service_charge'];
                    $to_update = $amount;
                }

                // make payment against invoice
                if(isset($_POST['invoice_id']) && $client_id && is_numeric($_POST['invoice_id'])) {
                    $this->loadModel('Invoice');
                    $invoice = $this->Invoice->findByInvoiceId($invoice_id);
                    if (!empty($invoice)) {
                        $should_pay_amount = $invoice['Invoice']['total_amount'] - $invoice['Invoice']['pay_amount'];

                        if ($amount > 0)
                        {
                            if ($amount >= $should_pay_amount)
                            {
                                $invoice['Invoice']['pay_amount'] += $should_pay_amount;
                                $invoice['Invoice']['paid'] = true;

                            } else
                            {
                                $invoice['Invoice']['pay_amount'] += $amount;
                            }
                            $this->Invoice->save($invoice);
                        }
                    }
                }

                $this->set('amount', $to_service);
                $this->set('invoice', $invoice_id);
                $this->set('domain', $domain);
            }
        }
    }

    function _post($view)
    {
        return addslashes($_POST[$view]);
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
        {
            $insert_data = $this->params['data'];
            $insert_data["Client"]["client_id"] = $insert_data["client_id"];
            if ($this->Client->save($insert_data["Client"]))
            {
                $this->Session->write('m', $this->Client->create_json(201, 'The Auto Invoice Management is modified successfully!'));
                $this->redirect('/pr/pr_invoices/carrier_invoice');
            }
            else
            {
                $this->Session->write('m', $this->Client->create_json(101, 'Save Failed!'));
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
            echo ucfirst($type) . " Auto Invoice is successful!";
        }
        else
        {
            echo ucfirst($type) . " Auto Invoice is failed!";
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
            $group_id = $this->params['form']['group_id'] ?  intval($this->params['form']['group_id']) : '';
            if (!$date)
            {
                $date = date('Y-m-d', time());
            }
            $return_data = $this->Client->download_balance($date, $group_id);
            if ($return_data)
            {
                $this->set('data',$return_data);
                $random_filename = uniqid('balance[' . $date . ']_') . '.csv';
                $this->render('download_balance_csv');
                header("Content-Type: text/csv");
                header("Content-Disposition: attachment; filename={$random_filename}");
                header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
                header('Expires:0');
                header('Pragma:public');
            }
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

//        获取所有client的trunk
        if ($this->RequestHandler->isPost())
        {
            $post_arr = $this->params['data'];

            $credit_modify_arr = array();
            foreach ($post_arr as $post_item)
            {
                $old_credit = $this->Client->findByClientId($post_item['client_id'], array('Client.allowed_credit', 'Client.name'));
//                var_dump($old_credit['Client']['allowed_credit']);
//                var_dump($post_item['allowed_credit']);
                $post_item['allowed_credit'] = 0 - $post_item['allowed_credit'];
                $flg = $this->Client->save($post_item);
                if ($flg !== false)
                {
                    if ($old_credit['Client']['allowed_credit'] != $post_item['allowed_credit'])
                    {
                        $credit_modify_arr[]['CreditLog'] = array(
                            'modified_by' => $_SESSION['sst_user_name'],
                            'modified_from' => $old_credit['Client']['allowed_credit'],
                            'modified_to' => $post_item['allowed_credit'],
                            'carrier_name' => $old_credit['Client']['name'],
                        );
                    }
                }
            }
//            pr($credit_modify_arr);die;
            $this->Client->create_json_array('', 201, 'All changes are saved successfully!');
            $this->Session->write("m", Client::set_validator());
            $this->CreditLog->saveAll($credit_modify_arr);
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
        foreach ($data_arr as $data_arr_key => $data_arr_items)
        {
            $resource_data = $this->Gatewaygroup->find('all', array(
                'fields' => array('capacity', 'cps_limit', 'alias', 'ingress', 'egress', 'resource_id'),
                'conditions' => array("client_id = {$data_arr_items[0]['client_id']}")
            ));
            $data_arr[$data_arr_key]['resource'] = $resource_data;
        }
        $page->setDataArray($data_arr);
        $this->set('p', $page);
    }

    public function ajax_save_resource_ip()
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $type = $_POST['type'];
        if(!strcmp($type,'sip'))
        {
            $user_name = $_POST['user_name'];
            $password = $_POST['password'];
            $resource_id = $_POST['resource_id'];
            $re_ip_id = $_POST['re_ip_id'];
            if(!$this->judge_resource_ip_name($user_name,$re_ip_id))
            {
                echo json_encode(array('flg'=> 0,'msg' => __('Username already exists',true)));
                return ;
            }

            $sql = "INSERT INTO resource_ip (resource_id,password,username,need_register) VALUES
({$resource_id},'{$password}','{$user_name}',true) returning resource_ip_id";

            
            if($re_ip_id)
            {
                $sql = "UPDATE resource_ip set username = '{$user_name}', password = '{$password}'
WHERE resource_ip_id = {$re_ip_id} returning resource_ip_id";
            }

            $flg = $this->Client->query($sql);
            if($flg === false)
                echo json_encode(array('flg'=> 0,'msg' => __('save failed',true)));
            else
            {
                $resource_ip_id = $flg[0][0]['resource_ip_id'];

                // Logging rollback data
                $this->loadModel('Logging');

                $loggingArray = array(
                    'module' => 'Client Trunk',
                    'type' => 0,
                    'name' => $_SESSION['sst_user_name'],
                    'rollback' => "DELETE from resource_ip WHERE resource_ip_id = {$resource_ip_id}"
                );

                if ($re_ip_id) {
                    $loggingArray['type'] = 1;
                }

                $this->Logging->save($loggingArray);

                echo json_encode(array('flg'=> 1,'re_ip_id' =>$resource_ip_id));
            }
        }
        else
        {
            $ip = $_POST['ip'];
            $port = $_POST['port'];
            $resource_id = $_POST['resource_id'];
            $product_id = $_POST['product_id'];
            $product_name = $_POST['product_name'];
            $client_id = $_POST['client_id'];

            // creating resource if
            if (!$resource_id && $product_id) {
                $this->loadModel('prresource.Gatewaygroup');
                $this->loadModel('ProductRouteRateTable');
                // required data
                $gateway_data = [];
                $resource = [];
                $post = [];
                $gateway_data["Gatewaygroup"]["ingress"] = true;
                $gateway_data["Gatewaygroup"]["egress"] = false;
                $gateway_data["Gatewaygroup"]["alias"] = $product_name."_ingress_".rand();
                $gateway_data["Gatewaygroup"]["client_id"] = $client_id;
                $gateway_data["Gatewaygroup"]["media_type"] = 2;
                $gateway_data["Gatewaygroup"]["select2"] = null;

                $resource["id"] = [];
                $resource["id"][] = "";
                $resource["code"] = [];
                $resource["code"][] = "";
                $resource["code_cps"] = [];
                $resource["code_cps"][] = "";
                $resource["code_cap"] = [];
                $resource["code_cap"][] = "";
                $resource["product_id"] = [];
                $resource["product_id"][] = $product_id;
                $resource["tech_prefix"] = [];
                $resource["rate_table_id"] = [];
                $resource["route_strategy_id"] = [];
                $sql = "select tech_prefix,rate_table_id,route_strategy_id from product_route_rate_table  where id='{$product_id}'";
                $product_info = $this->ProductRouteRateTable->query($sql);
                if(!empty($product_info)){
                    $resource["tech_prefix"][] = $product_info[0][0]["tech_prefix"];
                    $resource["rate_table_id"][] = $product_info[0][0]["rate_table_id"];
                    $resource["route_strategy_id"][] = $product_info[0][0]["route_strategy_id"];
                }

                $post["data"] = $gateway_data;
                $post["resource"] = $resource;
                $post["t38"] = true;
                $post["reg_type"] = 0;

                $resource_id = $this->Gatewaygroup->saveOrUpdate($gateway_data, $post);
            }


            $re_ip_id = $_POST['re_ip_id'];
            if(!$this->judge_resource_ip($ip,$re_ip_id,$resource_id))
            {
                echo json_encode(array('flg'=> 0,'msg' => __('IP already exists',true)));
                return ;
            }
            $sql = "INSERT INTO resource_ip (resource_id,ip,port,need_register) VALUES
({$resource_id},'{$ip}',{$port},false) returning resource_ip_id";
            if($re_ip_id)
            {
                $sql = "UPDATE resource_ip set ip = '{$ip}', port = {$port}
WHERE resource_ip_id = {$re_ip_id} returning resource_ip_id";
            }
            $flg = $this->Client->query($sql);

            if($flg === false)
                echo json_encode(array('flg'=> 0,'msg' => __('save failed',true)));
            else
            {
                $resource_ip_id = $flg[0][0]['resource_ip_id'];

                // Logging rollback data
                $this->loadModel('Logging');

                $loggingArray = array(
                    'module' => 'Client Trunk',
                    'type' => 0,
                    'name' => $_SESSION['sst_user_name'],
                    'rollback' => "DELETE from resource_ip WHERE resource_ip_id = {$resource_ip_id}",
                    'detail' => "Resource ip {$ip}"
                );

                if ($re_ip_id) {
                    $loggingArray['type'] = 2;
                }
                $this->Logging->save($loggingArray);

                echo json_encode(array('flg'=> 1,'re_ip_id' =>$resource_ip_id));
            }
        }
    }

    public function judge_resource_ip_name($username,$re_ip_id)
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $re_ip_id = intval($re_ip_id);
        $this->loadModel('ResourceIp');
        $data = $this->ResourceIp->find('first',array(
            'conditions' => array(
                'username' => $username,
                "resource_ip_id != {$re_ip_id}"
            )
        ));
        if(empty($data))
            return true;
        else
            return false;
    }


    public function judge_resource_ip($ip,$re_ip_id,$resource_id)
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $re_ip_id = intval($re_ip_id);
        $resource_id = intval($resource_id);
        $this->loadModel('ResourceIp');
        $data = $this->ResourceIp->find('first',array(
            'conditions' => array(
                'ip' => $ip,
                "resource_ip_id != {$re_ip_id}",
                "resource_id" =>$resource_id,
            )
        ));
        if(empty($data))
            return true;
        else
            return false;
    }

    public function ajax_delete_resource_ip()
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $resource_ip_id = $_POST['re_ip_id'];
        $this->loadModel('ResourceIp');
        $flg = $this->ResourceIp->del($resource_ip_id);
        if($flg === false)
            echo json_encode(array('flg'=> 0));
        else
            echo json_encode(array('flg'=> 1));
    }


    private function auto_redirect($flag_arr,$is_registration=null,$registration_id=null)
    {
        if (isset($flag_arr['client_id']))
        {
            $name = $this->data['Client']['name'];
            //$this->Client->create_json_array('#ClientOrigRateTableId',201,__('Create carriers successfully!',true));
            if($is_registration){
                $this->requestAction('/registration/approve/'.base64_encode($registration_id).'/0/1');
                $this->Client->create_json_array('#ClientOrigRateTableId', 201, __('The Carrier [%s] is created successfully', true, $name));

                $this->requestAction('/clients/send_welcom_letter/'.base64_encode($this->data['Client']['client_id']));

                if(!$_SESSION['role_menu']['Template']['carrier_template']['model_w']){
                    $this->xredirect("/clients/index");
                }

                $this->xredirect("/clients/step2/{$this->data['Client']['client_id']}");
//                $this->xredirect("/clients/step2_registration/".base64_encode($flag_arr['client_id']).'/'.base64_encode($registration_id).'/null/true');
            }

            $this->Client->create_json_array('#ClientOrigRateTableId', 201, __('The Carrier [%s] is created successfully', true, $name));

            //send welcom letter
            if($_POST['is_send_welcom_letter'] == '1' || $_POST['is_send_welcom_letter'] == 'on'){
                $this->requestAction('/clients/send_welcom_letter/'.$this->data['Client']['client_id']);
            }

            //$is_registration, approve


            $this->xredirect("/clients/step2/{$this->data['Client']['client_id']}");

        }
        else
        {
            //$this->Client->create_json_array('#ClientOrigRateTableId',101,__('Create carriers Failed!',true));
            $this->Client->create_json_array('#ClientOrigRateTableId', 101, __('Fail to create Carrier', true));

            if($is_registration){
                if(isset($return_url) && !empty($return_url)){
                    $this->Session->write('mm', 2);
                }
                $this->xredirect("/registration/?".base64_encode($return_url));
            }

            $this->xredirect(array('controller' => 'clients', 'action' => 'add_registration')); // failed
        }
    }

    /**
     * Registration过来的页面
     */
    function add_registration()
    {

        //是否从registration提交过来
        if(isset($_POST['approve_id'])){
            $approve_id = $_POST['approve_id'];
            $approve_url = $_POST['approve_url'];


            $_POST['registration_id'] = $approve_id;
            $_POST['registration_url'] = $approve_url;
        } else { //是否本页面提交
            if ($this->RequestHandler->isPost())
            {
                $registration_id = $_POST['registration_id'];
                $return_url = $_POST['registration_url'];
                $this->_render_add_impl_registration($registration_id,$return_url);
            }
        }

        $this->_render_add_data();

        $tz = $this->Client->get_sys_timezone();
        $gmt = "+00:00";
        if ($tz)
        {
            $gmt = substr($tz, 0, 3) . ":00";
        }
        $this->set('gmt', $gmt);
        $default_currency = $this->Client->query("SELECT sys_currency FROM system_parameter LIMIT 1");
        $this->set('default_currency', $default_currency[0][0]['sys_currency']);
        //用户数据
        $registration_id = intval($_POST['registration_id']);

        $registration_info = $this->Client->query("SELECT * FROM signup where id = {$registration_id}");
        $client_info = $this->Client->query("SELECT client.client_id FROM client LEFT JOIN signup ON (signup.id = {$registration_id}) WHERE signup.login = client.login AND signup.password = client.password");
        $client_info = $client_info ? $client_info[0][0]['client_id'] : null;

        $this->set('registration_info',$registration_info[0][0]);
        $this->set('registration_id',$_POST['registration_id']);
        $this->set('registration_url',$_POST['registration_url']);
        $this->set('client_id', $client_info);
    }


    //registration来的数据
    function _render_add_impl_registration($registration_id,$return_url=null)
    {
        $flag_arr = $this->_render_add_impl_save();
        $this->_render_add_impl_redirect_registration($flag_arr,$registration_id,$return_url);
    }

    //registration来的数据
    function _render_add_impl_redirect_registration($flag_arr,$registration_id,$return_url=null)
    {
        if ($flag_arr['client_id'])
        {
            $client_id = $flag_arr['client_id'];
            $flag_arr['client_id'] = base64_encode($flag_arr['client_id']);
//            $registration_id = base64_encode($registration_id);
            $return_url = base64_encode($return_url);

            $name = $_POST['data']['Client']['name'];
            //$this->Client->create_json_array('#ClientOrigRateTableId',201,__('Create carriers successfully!',true));
//            $this->requestAction('/registration/approve/'.$registration_id.'/0/1');
//            Configure::write('debug', 2);
            $res = $this->Client->query("UPDATE signup SET status = 1 WHERE id = {$registration_id}");
            $this->Client->create_json_array('#ClientOrigRateTableId', 201, __('The Carrier [%s] is created successfully', true, $name));


            //send welcom letter
            if($_POST['is_send_welcom_letter'] == '1'){
                $this->send_welcom_letter($client_id, false);
            }



            //TODO 注册向导
//            if ($flag_arr['log_id'])
//            {//exit('1');
////                $this->xredirect("/logging/index/{$flag_arr['log_id']}/clients-step2-{$flag_arr['client_id']}");
//                $url_flug = "clients-step2_registration-{$flag_arr['client_id']}-{$registration_id}-{$return_url}";
//                $this->modify_log_noty($flag_arr['log_id'], $url_flug);
//            }
//            else
//            {//exit('2');
//                $this->xredirect("/clients/step2_registration/{$flag_arr['client_id']}/{$registration_id}/{$return_url}");
//            }

//            $this->xredirect("/clients/step2_registration/{$flag_arr['client_id']}/{$registration_id}/{$return_url}");
            //$this->xredirect ("/clients/index"); // succ
            $decodedClientId = base64_decode($flag_arr['client_id']);

            $this->xredirect("/clients/step2/{$decodedClientId}/{$registration_id}");

        }
        else
        {
            //$this->Client->create_json_array('#ClientOrigRateTableId',101,__('Create carriers Failed!',true));
            $this->Client->create_json_array('#ClientOrigRateTableId', 101, __('Fail to create Carrier', true));
            $this->Session->write("m", Client::set_validator());//pr($_SESSION['m']);exit;
            if(!empty($return_url)){

                $this->Session->write("mm", 2);
            }
            $this->xredirect("/registration/?$return_url"); // failed
        }
    }

    private function addProductResource($product_ids, $client_id){
        Configure::write('debug', 0);
        $this->loadModel('prresource.Gatewaygroup');
        $this->loadModel('ProductRouteRateTable');
        $rst = $this->ProductRouteRateTable->query("select orig_rate_table_id,profit_margin,enough_balance,client.client_id,client.name from client left join users on client.user_id = users.user_id where users.user_id = {$_SESSION['sst_user_id']}");
        $enough_balance = 'true';

        foreach ($product_ids as $product_id) {

            $product_info = $this->ProductRouteRateTable->findAllById($product_id);
            if(empty($product_info)){
                continue;
            }
            $ingress_name = $product_info[0]['ProductRouteRateTable']['product_name'] . '_' .date("Y_m_d_H_i_s");
            $data = array();
            $data['Gatewaygroup']['client_id'] = $client_id;
            $data['Gatewaygroup']['alias'] = $ingress_name;
            $data['Gatewaygroup']['ingress'] = true;
            $data['Gatewaygroup']['egress'] = false;
            $data['Gatewaygroup']['enough_balance'] = $enough_balance;
            $data['Gatewaygroup']['route_strategy_id'] = $product_info[0]['ProductRouteRateTable']['route_strategy_id'];
            $data['Gatewaygroup']['rate_table_id'] = $product_info[0]['ProductRouteRateTable']['rate_table_id'];

            $post['resource']['id'] = array();
            $post['resource']['id'][] = '';
            $post['resource']['tech_prefix'] = array();
            $post['resource']['tech_prefix'][] = $product_info[0]['ProductRouteRateTable']['tech_prefix'];
            $post['resource']['product_id'] = array();
            $post['resource']['product_id'][] = $product_id;
            $post['resource']['rate_table_id'] = array();
            $post['resource']['rate_table_id'][] = $product_info[0]['ProductRouteRateTable']['rate_table_id'];
            $post['resource']['route_strategy_id'] = array();
            $post['resource']['route_strategy_id'][] = $product_info[0]['ProductRouteRateTable']['route_strategy_id'];
            $post['reg_type'] = 0;
            $res_id = $this->Gatewaygroup->saveOrUpdate_resource($data, $post);
            $this->Gatewaygroup->saveResouce($post['resource'], $res_id);

        }
        return true;
    }

    function step2_registration($en_client_id,$en_registration_id,$en_return_url=null, $is_template = false)
    {
        $client_id = base64_decode($en_client_id);
        $registration_id = base64_decode($en_registration_id);
        $return_url = base64_decode($en_return_url);


        $sql = "select name from client where client_id = {$client_id}";
        $result = $this->Gatewaygroup->query($sql);
        $client_name = $result[0][0]['name'];
        $this->set('client_name', $client_name);
        $this->set("client_id", $client_id);

        $this->set("rate_table", $this->Gatewaygroup->find_rate_table());

        $this->set('route_list', $this->Gatewaygroup->find_route_strategy());
        $this->set('is_template', $is_template);

        $sql = "select * from signup_ip where signup_id = $registration_id";
        $registration_ip = $this->Gatewaygroup->query($sql);
        $this->set('registration_ip',$registration_ip);


        //products
        $sql = "select product_id from signup where id = $registration_id";
        $product_id_str = $this->Gatewaygroup->query($sql);
        $product_id_arr = explode(',',$product_id_str[0][0]['product_id']);
        $this->set('product_id_arr', $product_id_arr);


        $sql ="select product_name, id, tech_prefix,rate_table_id,route_strategy_id

from product_route_rate_table
";
        $items = $this->Gatewaygroup->query($sql);

        $product_arr = array();
        $product_name_arr = array();
        /**
         * Auto ingress trunk create
         */
        if(empty($product_id_arr) || empty($product_id_arr[0])) {
            $res = $this->Clients->getAllowedCredit($client_id);
            $enough_balance = 't';

            $clientData = $this->Client->find('first', array(
                'conditions' => array(
                    'client_id' => $client_id
                )
            ));

            $resourceName = $clientData['Client']['name'] . uniqid();
            $res_id = $this->Clients->insertResource3(true, false, $resourceName, $client_id, $enough_balance);
            $resource_id = $res_id[0][0]['resource_id'];

            foreach ($items as $item) {
                $this->Clients->insertResourcePrefix($resource_id, $item[0]['rate_table_id'], $item[0]['route_strategy_id'], $item[0]['tech_prefix'], $item[0]['id']);
            }

            $this->Session->write('m', $this->Client->create_json_array('#ClientOrigRateTableId', 201, __('Succeeded', true)));
            $this->redirect("/registration/index");
            exit;
        }

        foreach($items as $item){
            $product_name_arr[$item[0]['id']] = $item[0]['product_name'];
            $arr = array(
                'product_name' => $item[0]['product_name'],
                'route_strategy_id' => $item[0]['route_strategy_id'],
                'rate_table_id' => $item[0]['rate_table_id'],
                'tech_prefix' => $item[0]['tech_prefix']

            );

            $product_arr[$item[0]['id']] = $arr;
        }
//pr($product_arr);exit;

        $this->set('product_arr', $product_arr);
        $this->set('product_name_arr', $product_name_arr);



        $this->set('return_url',$return_url);

        if ($this->RequestHandler->isPost())
        {
//            pr($this->params);die;
            $res = $this->Clients->getAllowedCredit($client_id);
            $enough_balance = 't';

            $is_same = $this->Clients->checkIsHaveByName($_POST['resource']['name']);
            if ($is_same != 0)
            {
                $this->Client->create_json_array('#ClientOrigRateTableId', 101, __('Name already exists!', true));
                $this->xredirect("/clients/step2_registration/$en_client_id/$en_registration_id/$en_return_url");
            }

            $arr = array();

            foreach($_POST['resource']['tech_prefix'] as $v){
                if(in_array($v,$arr)) {
                    $this->Client->create_json_array('#ClientOrigRateTableId', 101, __('Tech Prefix is duplicate!', true));
                    return;
                }
                $arr[] = $v;
            }


            $res_id = $this->Clients->insertResource3($_POST['data']['Clients']['ingress'], $_POST['data']['Clients']['egress'], $_POST['resource']['name'], $client_id, $enough_balance);
            foreach($_POST['resource']['product_id'] as $key => $v){

                $resource_id = $res_id[0][0]['resource_id'];
                if (isset($_POST['accounts']))
                {
                    $len = count($_POST['accounts']['ip']);
                    for ($i = 0; $i < $len; $i++)
                    {
                        $this->Clients->insertHosts2($_POST['accounts']['ip'][$i], $_POST['accounts']['need_register'][$i], $_POST['accounts']['port'][$i], $resource_id);
                    }
                }

                if(!$_POST['resource']['product_id'][$key]){
                    $_POST['resource']['product_id'][$key] = 0;
                }

                $this->Clients->insertResourcePrefix($resource_id, $_POST['resource']['rate_table_id'][$key], $_POST['resource']['route_strategy_id'][$key], $_POST['resource']['tech_prefix'][$key], $_POST['resource']['product_id'][$key]);


            }
            $this->Client->create_json_array('#ClientOrigRateTableId', 201, __('Succeeded', true));
            $is_finished = $_POST['is_finished'];
            if ($is_finished == 1)
            {
                $this->xredirect("/registration/approve/$en_registration_id/$en_return_url");
            }
            else
            {
                $this->xredirect("/clients/step2_registration/$en_client_id/$en_registration_id/$en_return_url"); // succ
            }
        }


    }




    function addingress_registration($client_id)
    {
        if ($this->RequestHandler->isPost())
        {

            $res = $this->Clients->getAllowedCredit($client_id);
            $enough_balance = 't';

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
                    $this->Clients->opt_client_has_product($client_id, $_POST['resource']['product_id'][$i]);
                    $this->Clients->insertResourcePrefix($resource_id, $_POST['resource']['rate_table_id'][$i], $_POST['resource']['route_strategy_id'][$i], $_POST['resource']['tech_prefix'][$i], $_POST['resource']['product_id'][$i]);
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

    public function product_list($encode_resource_id)
    {
        $this->pageTitle = 'Management/Product';
        $this->loadModel('ProductRouteRateTable');
        $this->loadModel('ResourcePrefix');
        $resource_id = base64_decode($encode_resource_id);
        $this->set('resource_id',$resource_id);
        //$this->set('ingress_name',$this->ResourcePrefix->findAll_ingress()[$resource_id]);
        $res = $this->ResourcePrefix->findAll_ingress();
        $this->set('ingress_name',$res[$resource_id]);
        $resource_prefix_arr = $this->ResourcePrefix->get_list_by_resource($resource_id);
        foreach ($resource_prefix_arr as $key => $resource_prefix)
        {
            if(!$resource_prefix['ProductRouteRateTable']['product_name'])
                $resource_prefix_arr[$key]['ProductRouteRateTable']['product_name'] = __('Admin-Define',true)."[".$resource_prefix['RateTable']['name']."]";
        }
        $this->set('data',$resource_prefix_arr);
        $this->set('route_plan',$this->ProductRouteRateTable->find_routepolicy());
        $this->set('rate_table',$this->ProductRouteRateTable->find_all_rate_table());

    }

    public function product_add_panel($resource_id)
    {
        Configure::write('debug',0);
        $this->loadModel('ProductRouteRateTable');
        $product_data = $this->ProductRouteRateTable->get_not_used_by_trunk($resource_id);

        $product_lists = array();
        foreach ($product_data as $product_data_item)
        {
            $product_lists[$product_data_item[0]['id']] = array(
                'product_name' => $product_data_item[0]['product_name'],
                'tech_prefix' => $product_data_item[0]['tech_prefix'],
            );
        }
        $this->set('product_lists',$product_lists);
        if($this->RequestHandler->ispost())
        {
            Configure::write('debug',1);
            $product_id = $this->params['form']['product'];
            $flg = $this->ProductRouteRateTable->insert_into_prefix($product_id,$resource_id);
            if ($flg === false)
                $this->Session->write('m', $this->ProductRouteRateTable->create_json(101, __('Failed!', true)));
            else
                $this->Session->write('m', $this->ProductRouteRateTable->create_json(201, __('Succeed!', true)));
            $this->redirect('product_list/'.base64_encode($resource_id));
        }
    }

    public function delete_product_by_trunk($encode_resource_prefix_id,$encode_resource_id)
    {
        $prefix_id = base64_decode($encode_resource_prefix_id);
        $this->loadModel('ResourcePrefix');
        $flg = $this->ResourcePrefix->del($prefix_id);
        if ($flg === false)
            $this->Session->write('m', $this->ResourcePrefix->create_json(101, __('Failed!', true)));
        else
            $this->Session->write('m', $this->ResourcePrefix->create_json(201, __('Succeed!', true)));
        $this->redirect('product_list/'.$encode_resource_id);
    }


    //ajax 获得数据
    public function get_ajax(){
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $client_id = $this->_post('client_id');
        $this->loadModel('FinanceHistoryActual');
        $ret = $this->FinanceHistoryActual->get_current_finance_detail($client_id);

        echo json_encode($ret);
    }

    public function get_ajax_all()
    {
        //die('im here');
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $clientIds = $_REQUEST['client_ids'];
        $this->loadModel('FinanceHistoryActual');
        $res = array();
        foreach ($clientIds as $ci) {
            $res[] = $this->FinanceHistoryActual->get_current_finance_detail($ci);
        }
        echo json_encode($res);
    }


    public function rate_deck($encode_prefix_id, $encode_resource_id)
    {
        if ($_SESSION['login_type'] != 3)
            $this->redirect("index");
        $this->pageTitle = __("Rate Deck", true);

        $prefix_id = base64_decode($encode_prefix_id);
        $resource_id = base64_decode($encode_resource_id);

        $this->loadModel('ResourcePrefix');
        $prefix_data = $this->ResourcePrefix->find('first',array(
            'fields' => array('ResourcePrefix.tech_prefix','RateTable.name','Product.product_name','Resource.alias',
                'RateTable.rate_table_id'),
            'joins' => array(
                array(
                    'table' => 'rate_table',
                    'alias' => 'RateTable',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'RateTable.rate_table_id = ResourcePrefix.rate_table_id'
                    ),
                ),
                array(
                    'table' => 'product_route_rate_table',
                    'alias' => 'Product',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'Product.id = ResourcePrefix.product_id'
                    ),
                ),
                array(
                    'table' => 'resource',
                    'alias' => 'Resource',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'Resource.resource_id = ResourcePrefix.resource_id'
                    ),
                ),
            ),
            'conditions' => array(
                'ResourcePrefix.id' => $prefix_id,
            )
        ));

        $rate_table_id = $prefix_data['RateTable']['rate_table_id'];

        $conditions = array(
            'resource_id' => $resource_id,
            'rate_table_id' => $rate_table_id
        );
        $order_arr = array('RateSendLog.id' => 'desc');

        $this->paginate = array(
            'fields' => array('RateSendLog.create_time', 'RateSendLogDetail.send_to', 'RateSendLog.effective_date',
                'RateSendLogDetail.id','RateSendLog.id','RateSendLogDetail.status','RateSendLog.file'
            ),
            'limit' => 100,
            'order' => $order_arr,
            'joins' => array(
                array(
                    'table' => 'rate_send_log',
                    'alias' => 'RateSendLog',
                    'type' => 'INNER',
                    'conditions' => array(
                        'RateSendLog.id = RateSendLogDetail.log_id'
                    ),
                )
            ),
            'conditions' => $conditions,
        );
        $this->loadModel('RateSendLogDetail');
        $this->data = $this->paginate('RateSendLogDetail');
        $status = array(
            0 => "Waiting",
            1 => 'In Progress',
            2 => 'completed',
            3  =>  'failed',
        );
        $this->set('prefix_data',$prefix_data);
        $this->set('status', $status);
        $this->set('get_data',  $this->params['url']);
    }


    //dashboard
    public function carrier_dashboard(){
        $this->pageTitle = __("Management/Client Dashboard", true);
        $user_id = $_SESSION['sst_user_id'];
        $login_type = $this->Session->read('login_type');

        if ($login_type != 3) {
            $this->redirect_denied();
        }

        $data = $this->Client->get_carrier_info($user_id);
        $client_id = $data[0][0]['client_id'];
        $this->set('ingress_list',$this->Client->query("select resource_id,alias from resource where client_id=$client_id and ingress=true and active=true and is_virtual is not false"));


        //清理一个月前的dashboard_time_option
        $pre_month = date('Y-m-d H:i:s',strtotime('-1 month'));
        $sql = "delete from dashboard_time_option where client_point_time < '$pre_month'";
        $this->Client->query($sql);
        $sql = "select resource_id,alias from resource where client_id={$client_id} and ingress=true and active=true and is_virtual is not false";
        $trunks = $this->Client->query($sql);
        $this->set('trunks',$trunks);
    }


    /*
     * param $time: 时间段，
     * param $type:
     * type:1 第一部分：  ajax_text1
     * type:2 第二部分：  ajax_chart1
     */

    /*
     * param $time: 时间段，
     * param $type:
     * type:1 第一部分：  ajax_text1
     * type:2 第二部分：  ajax_chart1
     */
//    public function insert($i=0){
//        $this->autoLayout = false;
//        $this->autoRender = false;
//
//        if($i){
//            $_SESSION['i'] = 0;
//        }
//        $_SESSION['i'] = $_SESSION['i']+1;
//        $i = $_SESSION['i'];
//            echo $i;
//            $time = date('Y-m-d H:i:00+0000',strtotime(date('Y-m-d H:i:00+0000')) - $i*60);
//            $tem = rand(500,3000);
//            $sql=<<<EOD
//INSERT INTO public.qos_route_report (report_time, resource_id, code, not_zero_calls, total_calls, bill_time, cancel_calls, busy_calls, direction, server_ip, pdd, cost) VALUES ('$time', 4, '1', $tem, $tem, $tem, 0, 0, 0, '192.168.1.6', $tem, $tem);
//INSERT INTO public.qos_route_report (report_time, resource_id, code, not_zero_calls, total_calls, bill_time, cancel_calls, busy_calls, direction, server_ip, pdd, cost) VALUES ('$time', 70, '1', $tem, $tem, $tem, 0, 0, 1, '192.168.1.6', $tem, $tem);
//EOD;
//            $this->Clients->query($sql);
//
//
//
//    }

    public function get_dashboard_data($type,$time=1,$is_load=0)
    {
        Configure::write('debug', 0);
        $this->autoLayout = false;
        $this->autoRender = false;

        $this->loadModel('Dashboard');
        $data = array();
        switch(intval($type)){
            case 1:
                /* 第一部分：  ajax_text1
                 * 构造数组： array, key:non_zero,calls,spending,volume
                 * $time 1 -》 1小时，24 -》 24小时
                 */

                //时间
                //if($is_load) {
                $data = $this->Dashboard->ajax_text1($time);
                // } else {
                //     $data = $this->Dashboard->ajax_add_text1($time);
                // }
                break;

            case 2:
                /* 第二部分：  ajax_chart1
                 * 构造数组： array 多维数组, key:call_attempts,non_zero,cost,volume,
                 * 每一个key，对应的数组为二维数组，array(array(time,value),array(time,value)...)
                 * 对应的数组为二维数组 ,默认$time=1，则以每个小时为一个的， 最后24个小时的数据；
                 * $time 1 -》 24小时，7 -》 7天，15 -》 15天，30 -》 30天，60 -》 60天，
                 */

                $tab_value = $_POST['tab_value'];
                if($is_load){

                    $data = $this->Dashboard->ajax_chart1($time,$tab_value);
                } else {

                    $data = $this->Dashboard->ajax_add_chart1($time,$tab_value);
                }

                break;
            case 3:
                /* 第三部分：  ajax_text2
                 * 构造数组： array, key:asr,acd
                 */
                $data = $this->Dashboard->ajax_text2();

                break;
            case 4:
                /* 第四部分：  ajax_table1
                 * 构造数组： array(page_num,page_now,data),
                 * page_num: 页面数
                 * page_now: 当前页
                 * page_opt: 翻页操作
                 * data-> array: key:code_name,attempt,non_zero,min,cost,asr,acd
                 * $time 1->1小时， 24->24小时
                 * sort: 排序变量
                 */


                $sort='0';
                $trunk_id = isset($_POST['trunk_id']) ? $_POST['trunk_id'] : '';
                //如果没有传过来page_opt，则为初始化操作
                if(isset($_POST['page_opt']) && $_POST['page_opt'] != '0'){

                    $page_opt = $_POST['page_opt'];
                    $page_num = $_POST['page_num'];
                    $page_now = $_POST['page_now'];

                    $sort = $_POST['sort'];



                    //当前页
                    if(is_numeric($page_opt)){
                        $page_now = intval($page_opt);
                    } else {
                        switch($page_opt){
                            case 'first':
                                $page_now = 1;
                                break;
                            case 'last':
                                $page_now = $page_num;
                                break;
                            case 'prev':
                                if($page_now != 1)
                                    $page_now = $page_now - 1;
                                else
                                    $page_now = 1;
                                break;
                            case 'next':
                                if($page_now != $page_num)
                                    $page_now = $page_now + 1;
                                else
                                    $page_now = $page_num;
                                break;
                        }
                    }

                    //limit
                    $limit = ($page_now - 1) * 20;

                } else {
                    $sort = $_POST['sort'];
                    //计算数据总数
                    $cnt = $this->Dashboard->count_ajax_table1($time, $trunk_id);
                    $cnt = count($cnt);
                    $page_num = ceil($cnt / 20);
                    if(!$page_num){
                        $page_num = 1;
                    }

                    $page_now = 1;

                    $limit = 0;

                }

                $limit = " limit 20 offset $limit ";

                //排序
                if($sort == '0'){
                    $sort = ' ingress_code_name asc ';
                }
                $sort = " order by $sort ";


                $data = array();
                //翻页信息
                $data['page_now'] = $page_now;
                $data['page_num'] = $page_num;

                //生成数据 使用$time及 limit
                $data['data'] = $this->Dashboard->ajax_table1($time, $sort, $limit, $trunk_id);
                $data['time_intval'] = $data['data']['time_intval'];
                unset($data['data']['time_intval']);


                break;
            case 5:
                /* 第五部分：  ajax_table2
                 * 构造数组： array(page_num,page_now,data),
                 * page_num: 页面数
                 * page_now: 当前页
                 * page_opt: 翻页操作
                 * data-> array: key:time_period,attempt,non_zero,min,cost,asr,acd
                 * $time 1->24小时， 2->7天， 3->4周， 4->3个月
                 * sort 排序
                 */



                $trunk = $_POST['trunk'];
                $sort = isset($_POST['sort'])? $_POST['sort'] : '0';
                //如果传过来page_opt，则为翻页，否则为初始化操作
//                if(isset($_POST['page_opt'])){
//
//                    $page_opt = $_POST['page_opt'];
//                    $page_num = $_POST['page_num'];
//                    $page_now = $_POST['page_now'];
//                    $trunk = $_POST['trunk'];
//                    $sort = $_POST['sort'];
//
//
//                    //当前页
//                    if(is_numeric($page_opt)){
//                        $page_now = intval($page_opt);
//                    } else {
//                        switch($page_opt){
//                            case 'first':
//                                $page_now = 1;
//                                break;
//                            case 'last':
//                                $page_now = $page_num;
//                                break;
//                            case 'prev':
//                                if($page_now != 1)
//                                    $page_now = $page_now - 1;
//                                else
//                                    $page_now = 1;
//                                break;
//                            case 'next':
//                                if($page_now != $page_num)
//                                    $page_now = $page_now + 1;
//                                else
//                                    $page_now = $page_num;
//                                break;
//                        }
//                    }
//
//                    //limit
//                    $limit = ($page_now - 1) * 20;
//
//                } else {
//
//                    //计算数据总数
//                    //$cnt = $this->Dashboard->count_ajax_table2($time, $trunk);
//                    $cnt = count($cnt);
//                    $page_num = ceil($cnt / 20);
//                    if(!$page_num){
//                        $page_num = 1;
//                    }
//                    $page_now = 1;
//
//
//                    $limit = 0;
//                }
//
//                $limit = " limit 20 offset $limit ";

                //排序
                if($sort == '0'){
                    $sort = ' order by report_time desc ';
                } else{
                    $sort = " order by $sort ";
                }



                $data = array();
//                //翻页信息
//                $data['page_now'] = $page_now;
//                $data['page_num'] = $page_num;

                //生成数据 使用$time,$trunk及 limit
                $data['data'] = $this->Dashboard->ajax_table2($time, $trunk, $sort);



                break;
            case 6:
                /* 第六部分：  ajax_chart2
                 * 构造数组： array 多维数组, key:minutes,cost
                 * 每一个key，对应的数组为二维数组，array(array(time,value),array(time,value)...)
                 * 对应的数组为二维数组 ,默认$time=1，则以每个小时为一个的， 最后24个小时的数据；
                 * $time 1 -》 按天，2 -》 按周，3 -》 按月，
                 */
                $which_value = $_POST['which_value'];

                $data = $this->Dashboard->ajax_chart2($time, $which_value);

                break;


        }

        echo json_encode($data);
    }

    public function carrier($is_account=false)
    {
        $this->loadModel('Systemparam');
        $systemParams = $this->Systemparam->find('first', array(
            'fields' => array('enable_client_download_rate', 'enable_client_delete_trunk', 'enable_client_disable_trunk')
        ));
        $this->set('systemParams', $systemParams['Systemparam']);

        $sections = parse_ini_file(CONF_PATH, TRUE, INI_SCANNER_RAW);
        $this->set('data_window', $sections['web_feature']['pay_in_new_window']);
        if(!$is_account){
            $this->xredirect('/clients/carrier_dashboard');
        }

        $this->pageTitle = 'Management/Accounts';
        $user_id = $_SESSION['sst_user_id'];
        $data = $this->Client->get_carrier_info($user_id);
        $client_id = $data[0][0]['client_id'];


        $this->loadModel('FinanceHistoryActual');
        $current_finance = $this->FinanceHistoryActual->get_current_finance_detail($client_id);
        $last_day_balance = $this->FinanceHistoryActual->get_last_day_balance($client_id);
        $data = array_merge($data[0][0], $current_finance);

        $actual_balance = $last_day_balance
            + $current_finance['payment_received']
            + $current_finance['credit_note_received']
            + $current_finance['debit_received']
            + $current_finance['unbilled_outgoing_traffic']
            - $current_finance['payment_sent']
            - $current_finance['credit_note_sent']
            - $current_finance['debit_sent']
            - $current_finance['unbilled_incoming_traffic'];

        $this->set('data', $data);
        $this->set('actual_balance', $actual_balance);
        if($client_balance_arr = $this->Client->get_client_balance($client_id)){
            $this->set('client_balance', $client_balance_arr[0][0]['balance']);
        }


        $this->set('post',$this->Client->findByClientId($client_id));

        $this->layout = 'carrier';


        $order_field = "alias asc";
        $egress_order_field = "alias asc";
        if ($this->isnotEmpty($this->params['url'], array('order_by')))
        {
            $order_by = $this->params['url']['order_by'];
            $order_arr = explode('-', $order_by);
            if (count($order_arr) == 2)
            {
                $field = $order_arr[0];
                $sort = $order_arr[1];

                if($field == 'alias'){
                    $order_field = 'alias'." ".$sort;
                } else {
                    $egress_order_field = 'alias'." ".$sort;
                }

            }
        }

        //ingress
        //$this->init_info();
        //$this->set('sst_client_id', $client_id);

        $this->set('p', $this->Gatewaygroup->findAll_ingress($order_field));
        $resource_ip_arr = $this->Gatewaygroup->get_resource_ip_by_client_id($client_id,$order_field);
        $this->set('resource_ip_arr',$resource_ip_arr);
        $this->set('change_ip',Configure::read('portal.change_ip'));

        $this->loadModel('ResourcePrefix');
        $resource_data = [];
        if ($client_id) {
            $resource_sql = "SELECT resource_id from resource where client_id = {$client_id} AND ingress=true order by $order_field";
            $resource_data = $this->Gatewaygroup->query($resource_sql);
        }
        $resource_prefix_arr_ok = array();
        foreach($resource_data as $k => $v){
            $resource_id = $v[0]['resource_id'];
            $resource_prefix_arr = $this->ResourcePrefix->get_list_by_resource($resource_id);

            if(!empty($resource_prefix_arr)) {
                foreach ($resource_prefix_arr as $key => $resource_prefix) {
                    if (!$resource_prefix['ProductRouteRateTable']['product_name'])
                        $resource_prefix_arr[$key]['ProductRouteRateTable']['product_name'] = __('Admin-Define', true) . "[" . $resource_prefix['RateTable']['name'] . "]";
                }
            }else{
                $resource_prefix_arr = $this->Gatewaygroup->find('all', array(
                    'fields' => 'RateTable.name, RateTable.rate_table_id,Gatewaygroup.client_id ,Client.name,Client.rate_email,Gatewaygroup.alias',
                    'conditions' => array('Gatewaygroup.resource_id' => $resource_id),
                    'joins' => array(
                        array(
                            'table' => 'rate_table',
                            'alias' => 'RateTable',
                            'type' => 'LEFT',
                            'conditions' => array(
                                'RateTable.rate_table_id = Gatewaygroup.rate_table_id',
                            )
                        ),
                        array(
                            'table' => 'client',
                            'alias' => 'Client',
                            'type' => 'LEFT',
                            'conditions' => array(
                                'Client.client_id = Gatewaygroup.client_id',
                            )
                        )
                    ),
                    'order' => ''
                ));
                $resource_prefix_arr[0]['Resource'] =  $resource_prefix_arr[0]['Gatewaygroup'];
            }
            $resource_prefix_arr_ok[$k] = $resource_prefix_arr;
//            $sql = "select count(*) as cnt from resource_prefix where resource_id = $resource_id";
//            $res = $this->Gatewaygroup->query($sql);
//            $resource_prefix_arr_ok[$k] = $res[0][0];
        }
        $resource_prefix_arr_ok_x = array();
        foreach($resource_prefix_arr_ok as $k => $v){
            foreach($v as $kv => $vv) {
                $resource_prefix_arr_ok_x[$k][$kv]['product'] = isset($vv['ProductRouteRateTable']['product_name']) ? $vv['ProductRouteRateTable']['product_name'] : '';
                $resource_prefix_arr_ok_x[$k][$kv]['prefix'] = isset($vv['ResourcePrefix']['tech_prefix']) ? $vv['ResourcePrefix']['tech_prefix'] : '';
            }

        }


        $this->set('product_prefix',$resource_prefix_arr_ok_x);
//        $this->set('route_plan',$this->ProductRouteRateTable->find_routepolicy());
//        $this->set('rate_table',$this->ProductRouteRateTable->find_all_rate_table());

        //egress
//        $egress_order_field = "alias asc";

        $this->set('p_egress', $this->Gatewaygroup->findAll_egress($egress_order_field));
        $egress_rate_table_sql = "select rate_table.name,rate_table.rate_table_id,resource.resource_id
from resource inner join rate_table on resource.rate_table_id = rate_table.rate_table_id where
resource_id is not null";
        $egress_rate_table = $this->Client->query($egress_rate_table_sql);
        $size = count($egress_rate_table);
        $l = array();
        for ($i = 0; $i < $size; $i++)
        {
            $key = $egress_rate_table [$i] [0] ['resource_id'];
            $l [$key]['rate_table_id'] = $egress_rate_table [$i] [0] ['rate_table_id'];
            $l [$key]['rate_table_name'] = $egress_rate_table [$i] [0] ['name'];
        }
        $this->set("egress_rate_table", $l);

        $egress_ip_arr = $this->Gatewaygroup->get_resource_ip_by_client_id($client_id,$egress_order_field);
        $this->set('egress_ip_arr',$egress_ip_arr);
        //$this->set('change_ip',Configure::read('portal.change_ip'));
    }

    public function save_user()
    {
        Configure::write('debug','0');
        $this->autoRender = false;
        $this->autoLayout = false;
        $save_arr = $this->data;
        $save_arr['client_id'] = $_SESSION['carrier_panel']['Client']['client_id'];
        $flg = $this->Client->save($save_arr);
        if ($flg === false)
        {
            $this->Client->create_json_array('', 101, __('Failed!', true));
            $this->Session->write('m', Client::set_validator());
        }
        else
        {
            $this->Client->create_json_array('', 201, __('Succeed!', true));
            $this->Session->write('m', Client::set_validator());
        }
        $this->redirect("carrier/true");
    }

    public function ajax_product_list(){
        Configure::write('debug','0');
        $this->autoLayout = false;

        $this->pageTitle = 'Management/Product';
        $this->loadModel('ProductRouteRateTable');
        $this->loadModel('ResourcePrefix');
        $resource_id = intval($_POST['resource_id']);
        $this->set('resource_id',$resource_id);
        $ingress_arr = $this->ResourcePrefix->findAll_ingress();
        $ingress_name = $ingress_arr[$resource_id];
        $this->set('ingress_name',$ingress_name);
        $resource_prefix_arr = $this->ResourcePrefix->get_list_by_resource($resource_id);
        foreach ($resource_prefix_arr as $key => $resource_prefix)
        {
            if(!$resource_prefix['ProductRouteRateTable']['product_name'])
                $resource_prefix_arr[$key]['ProductRouteRateTable']['product_name'] = __('Admin-Define',true)."[".$resource_prefix['RateTable']['name']."]";
        }
        $this->set('data',$resource_prefix_arr);
        $this->set('resource_id',$resource_id);
        $this->set('route_plan',$this->ProductRouteRateTable->find_routepolicy());
        $this->set('rate_table',$this->ProductRouteRateTable->find_all_rate_table());
    }

    public function get_product_info ($product_id){
        Configure::write('debug','0');
        if($product_id) {
            $this->loadModel('ProductRouteRateTable');
            $sql = "select tech_prefix,rate_table_id,route_strategy_id from product_route_rate_table  where id='{$product_id}'";
            $data = $this->ProductRouteRateTable->query($sql);
            echo (json_encode(['data' => $data[0][0], 'status' => true]));
            die;
        }
        echo json_encode(['status' => false]);
        die;
    }

    public function messages($show_tab=1){

        if($_SESSION['login_type'] !=3){
            $this->redirect_denied();
        }
        $this->pageTitle = 'Management/Messages';
        $this->layout = 'carrier';

        $this->set('show_tab',$show_tab);


        //Recent Rate Update
        $user_id = $_SESSION['sst_user_id'];
        $data = $this->Client->get_carrier_info($user_id);
        $client_id = $data[0][0]['client_id'];
        $ingress_list_arr = $this->Client->query("select resource_id,alias from resource where client_id=$client_id and ingress=true and active=true and is_virtual is not false");

        $ingress_list = array();
        $arr = array();
        foreach($ingress_list_arr as $v){
            $arr[$v[0]['resource_id']] = $v[0]['alias'];
            $ingress_list[] = $v[0]['resource_id'];
        }
        $ingress_list_arr = $arr;

        $this->loadModel('ResourcePrefix');
        $resultList = array();
        $resource_prefix_arr = array();
        if (!empty($ingress_list_arr)) {
            $resource_prefix_arr = $this->ResourcePrefix->get_all_product_and_rate_table($ingress_list);
            foreach ($resource_prefix_arr as $key => $resource_prefix)
            {

                if(!$resource_prefix['ProductRouteRateTable']['product_name']){
                    $resource_prefix_arr[$key]['ProductRouteRateTable']['product_name'] = __('Admin-Define',true);
                }
                $resource_prefix_arr[$key]['ProductRouteRateTable']['rate_deck'] = $resource_prefix['RateTable']['name']?:'';
            }

            $this->loadModel('RateSendLog');


            $start = date('Y-m-d  00:00:00', strtotime('today - 30 days'));
            foreach ($resource_prefix_arr as $item) {
                $mailHistory = $this->RateSendLog->find('first', array(
                    'fields' => array('RateSendLog.create_time', 'RateSendLog.id', 'RateSendLogDetail.send_to'),
                    'conditions' => array('RateSendLogDetail.resource_id' => $item['ResourcePrefix']['resource_id']),
                    'joins' => array(
                        array(
                            'table' => 'rate_send_log_detail',
                            'alias' => 'RateSendLogDetail',
                            'type' => 'LEFT',
                            'conditions' => array(
                                'RateSendLogDetail.log_id = RateSendLog.id',
                            )
                        )
                    ),
                    'order' => 'RateSendLog.create_time DESC'
                ));

                if($mailHistory['RateSendLog']['create_time'] < $start){
                    continue;
                }

                array_push($resultList, array(
                    'sent_on' => $mailHistory['RateSendLog']['create_time'],
                    'sent_to' => $mailHistory['RateSendLogDetail']['send_to'],
                    'product_name' => $item['ProductRouteRateTable']['product_name'],
                    'rate_deck' => $item['ProductRouteRateTable']['rate_deck']
                ));
            }
        }

        $this->set('data', $resultList);



        $this->set('resource_prefix_arr', $resource_prefix_arr);
        $this->set('ingress_list_arr', $ingress_list_arr);


        //Recent Invoices
        $sql = "select invoice_id, invoice_number, state, type, invoice.client_id,
                invoice_time, output_type, invoice_start, invoice_end, total_amount::numeric(30,5)
              from invoice where ( type = 0 or type = 2) and state = 9
              and invoice.total_amount > 0 and  invoice.client_id = $client_id order by invoice_id desc";

        require_once 'MyPage.php';
        $page = new MyPage ();
        $totalrecords = $this->Client->query($sql);
        $totalrecords = count($totalrecords);
        $page->setTotalRecords($totalrecords); //总记录数
        $page->setTotalRecords($totalrecords [0] [0] ['c']); //总记录数
        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = 20 : $pageSize = $_GET['size'];
        $page->setCurrPage($currPage); //当前页
        $page->setPageSize($pageSize); //页大小
        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $_GET['page'] = $currPage;
        $_GET['size'] = $pageSize;
        $offset = $currPage * $pageSize;
        $page_where = " limit '$pageSize' offset '$offset'";

        $sql .= $page_where;


        $invoices_arr = $this->Client->query($sql);
        $page->setDataArray($invoices_arr);
        $this->set('p', $page);

        //alerts
        $sql = "select email_addresses, subject, id,type from email_log where status != 1 and client_id = $client_id and (type= 1 or type= 22) and is_view != 1";
        $alerts_arr = $this->Client->query($sql);
        $alerts_type_arr = array(
            22 => __('Rule Alert',true),
            1 => __('Low Balance Alerts',true),
        );
        $this->set('alerts_arr',$alerts_arr);
        $this->set('alerts_type_arr',$alerts_type_arr);


        //Unpaid Invoice
        $sql = "select invoice_id, invoice_number, state, type, invoice.client_id, invoice_time,output_type,paid,due_date, invoice_start, invoice_end,
              total_amount::numeric(30,5),(select sum(amount)::numeric(30,5) from client_payment where client_payment.invoice_number = invoice.invoice_number and (payment_type = 3 OR payment_type = 4) and client_id = $client_id ) as paid
              from invoice where ( type = 0 or type = 2) and state = 9 and paid is not true
              and invoice.total_amount > 0 and  invoice.client_id = $client_id order by invoice_id desc";
        $unpaid_invoices_arr = $this->Client->query($sql);
        $this->set('unpaid_invoices_arr', $unpaid_invoices_arr);



        //Messages
        $sql = "select email_addresses, subject, id,type, send_time from email_log where status != 1 and client_id = $client_id and (type= 9 or type= 21 or type=2) and is_view != 1";
        $messages_arr = $this->Client->query($sql);
        $messages_type_arr = array(
            9 => __('Payment Received',true),
            21 => __('Payment Received',true),
            2 => __('Daily Usage',true),
        );
        $this->set('messages_arr',$messages_arr);
        $this->set('messages_type_arr',$messages_type_arr);
    }


    //自助页面view操作
    public function ajax_email_log_view(){
        Configure::write('debug', 0);
        $this->autoLayout = false;
        $e_id = $_POST['e_id'];
        $sql = "select subject,content from email_log where id= $e_id";
        $rst = $this->Client->query($sql);
        if(!empty($rst)){
            $rst[0][0]['content'] = trim(str_replace('\r\n', '', $rst[0][0]['content']), '"');
            $rst[0][0]['subject'] = trim(str_replace('\r\n', '', $rst[0][0]['subject']), '"');
        }
        $this->set('rst',$rst);

        $sql = "update email_log set is_view=1 where id= $e_id";
        $this->Client->query($sql);

    }


    public function send_welcom_letter($encode_client_id, $redirect = false)
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
//        $client_id = base64_decode($encode_client_id);
        $sql = "SELECT *
FROM client WHERE client_id = {$encode_client_id}";
        $result = $this->Client->query($sql);

        if (!$result) {
            $this->redirect($this->referer());
        }
        $userData = $result[0][0];
        if (empty($userData['email'])) {
            $this->Client->create_json_array('#ClientOrigRateTableId', 101, __('Main email is empty on carrier [%s]!', true, $userData['name']));
            $this->Session->write("m", Client::set_validator());
            $this->redirect($this->referer());
        }

        $mailTemplates = $this->Mailtmp->find('all');
        $mailTemplates = $mailTemplates[0]['Mailtmp'];
        $to = $userData['email'];

        $url = "{$_SERVER['HTTP_HOST']}{$this->webroot}homes/login";

        $subject = $mailTemplates['welcom_subject'];
        $subject = str_replace('{company_name}', $userData['company'], $subject);
        $subject = str_replace('{username}', $userData['login'], $subject);
        $subject = str_replace('{client_name}', $userData['name'], $subject);
        $subject = str_replace('{login_url}', "<a href='{$url}'>{$url}</a>", $subject);

        $body = $mailTemplates['welcom_content'];
        $body = str_replace('{company_name}', $userData['company'], $body);
        $body = str_replace('{username}', $userData['login'], $body);
        $body = str_replace('{client_name}', $userData['name'], $body);
        $body = str_replace('{login_url}', "<a href='{$url}'>{$url}</a>", $body);

        $result = $this->VendorMailSender->send($subject, $body, $to);
//        $result = $this->sendEmail($subject, $body, $to);
        $body = pg_escape_string($body);
        $body = htmlspecialchars($body);
        $sql = "INSERT INTO email_log (send_time,type,email_addresses,status,error,subject,content,client_id)
                VALUES (current_timestamp(0), 31, '{$to}', {$result['status']}, '{$result['error']}', '{$subject}', '{$body}', {$client_id})";

        $this->Client->query($sql);

        if ($result['status'] == 0) {
            $this->Client->create_json_array('#ClientOrigRateTableId', 201, __('The email is sent successfully!', true, $userData['name']));
        } else {
            $this->Client->create_json_array('#ClientOrigRateTableId', 101, __('Failed sending to [%s]!', true, $userData['name']));
        }
        if ($redirect) {
            $this->Session->write("m", Client::set_validator());
            $this->redirect($this->referer());
        }

    }

    //Client Rate Summary
    public function rate_summary()
    {
        $this->loadModel('ResourcePrefix');
        $conditions = array(
            "Resource.alias != ?"  => '',
            'Client.name != ?' => 'Check Route',
        );
        if(isset($this->params['url']['search_name']))
        {
            $search_name = trim($this->params['url']['search_name']);
            if($search_name)
            {
                $conditions['or'] = array(
                    'Client.name like ?' => "%$search_name%",
                    'RateTable.name like ?' => "%$search_name%",
                    'Resource.alias like ?' => "%$search_name%",
                );
            }
        }
        $joins_arr = array(
            array(
                'table' => 'rate_table',
                'alias' => 'RateTable',
                'type' => 'left',
                'conditions' => array(
                    'RateTable.rate_table_id = ResourcePrefix.rate_table_id'
                ),
            ),
            array(
                'table' => 'resource',
                'alias' => 'Resource',
                'type' => 'inner',
                'conditions' => array(
                    'Resource.resource_id = ResourcePrefix.resource_id'
                ),
            ),
            array(
                'table' => 'client',
                'alias' => 'Client',
                'type' => 'inner',
                'conditions' => array(
                    'Client.client_id = Resource.client_id'
                ),
            ),
        );

        $order_arr = array('Client.name' => 'ASC');
        if ($this->isnotEmpty($this->params['url'], array('order_by')))
        {
            $order_by = $this->params['url']['order_by'];
            $order_arr = explode('-', $order_by);
            if (count($order_arr) == 2)
            {
                $field = $order_arr[0];
                $sort = $order_arr[1];
                $order_arr = array($field => $sort);
            }
        }
        $this->paginate = array(
            'fields' => array(
                'Client.name','Resource.alias','ResourcePrefix.tech_prefix','Client.rate_email','RateTable.name',
                'RateTable.rate_table_id'
            ),
            'limit' => isset($_GET['size']) ? $_GET['size'] : 100,
            'order' => $order_arr,
            'conditions' => $conditions,
            'joins' => $joins_arr,
        );
        $this->set('get_data', $this->params['url']);
        $this->data = $this->paginate('ResourcePrefix');
    }

    public function export_rate_summary($term = false)
    {
        $this->loadModel('ResourcePrefix');
        $conditions = array(
            "Resource.alias != ?"  => '',
            'Client.name != ?' => 'Check Route',
        );

        if(isset($term))
        {
            $search_name = base64_decode($term);
            if($search_name)
            {
                $conditions['or'] = array(
                    'Client.name like ?' => "%$search_name%",
                    'RateTable.name like ?' => "%$search_name%",
                    'Resource.alias like ?' => "%$search_name%",
                );
            }
        }
        $joins_arr = array(
            array(
                'table' => 'rate_table',
                'alias' => 'RateTable',
                'type' => 'left',
                'conditions' => array(
                    'RateTable.rate_table_id = ResourcePrefix.rate_table_id'
                ),
            ),
            array(
                'table' => 'resource',
                'alias' => 'Resource',
                'type' => 'inner',
                'conditions' => array(
                    'Resource.resource_id = ResourcePrefix.resource_id'
                ),
            ),
            array(
                'table' => 'client',
                'alias' => 'Client',
                'type' => 'inner',
                'conditions' => array(
                    'Client.client_id = Resource.client_id'
                ),
            ),
        );

        $order_arr = array('Client.name' => 'ASC');
        $find_arr =array(
            'fields' => array(
                'Client.name','Resource.alias','ResourcePrefix.tech_prefix','Client.rate_email','RateTable.name',
                'RateTable.rate_table_id'
            ),
            'order' => $order_arr,
            'conditions' => $conditions,
            'joins' => $joins_arr,
        );
        $this->data = $this->ResourcePrefix->find('all',$find_arr);
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Content-Type: text/csv");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header("Content-Disposition: attachment;filename=client_rate_summary.csv");
        header("Content-Transfer-Encoding: binary ");
        Configure::write('debug', 0);
        $this->autoLayout = FALSE;
        $this->render('rate_summary_csv');
    }

    public function ingress_host(){
        $this->pageTitle = 'Management/Ingress Host';

        $conditions = array(
            'ResourceIp.direction' => '0'
        );

        if (isset($_GET['client_name']) && !empty($_GET['client_name']))
        {
            $conditions["client.name like"] = "%".$_GET['client_name']."%";
        }

        if (isset($_GET['resource_name']) && !empty($_GET['resource_name']))
        {
            $conditions["resource.alias like"] = "%".$_GET['resource_name']."%";
        }

        if (isset($_GET['filter_status']) && !empty($_GET['filter_status']))
        {
            $conditions["resource.active"] = $_GET['filter_status'] == 1 ? true : false;
        }

        $order_arr = array('ResourceIp.ip' => 'asc');
        if ($this->isnotEmpty($this->params['url'], array('order_by')))
        {
            $order_by = $this->params['url']['order_by'];
            $order_arr = explode('-', $order_by);
            if (count($order_arr) == 2)
            {
                $field = $order_arr[0];
                $sort = $order_arr[1];
                $order_arr = array($field => $sort);
            }
        }

        $size = 100;
        if (isset($_GET['size']))
        {
            $size = $_GET['size'];
        }

        $this->paginate = array(
            'fields' => array(
                "ResourceIp.ip","ResourceIp.port","resource_ip_limit.cps","resource_ip_limit.capacity",
                "client.name","client.call_limit", "client.cps_limit", "resource.active","resource.alias","resource.cps_limit","resource.capacity"
            ),
            'limit' => $size,
            'joins' => array(
                array(
                    'table' => 'resource_ip_limit',
                    'type' => 'left',
                    'conditions' => array(
                        'ResourceIp.resource_ip_id = resource_ip_limit.ip_id'
                    ),
                ),
                array(
                    'table' => 'resource',
                    'type' => 'left',
                    'conditions' => array(
                        'ResourceIp.resource_id = resource.resource_id'
                    ),
                ),
                array(
                    'table' => 'client',
                    'type' => 'left',
                    'conditions' => array(
                        'resource.client_id = client.client_id'
                    ),
                )

            ),
            'order' => $order_arr,
            'conditions' => $conditions
        );

        $this->loadModel('ResourceIp');

        $this->data = $this->paginate('ResourceIp');
    }

    function changeSelectedClientsActiveVal()
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $clientIds = rtrim($this->params['url']['ids'], ',');
        $task = $this->params['url']['task'];
        $status = $task == 'activate' ? 'true' : 'false';
        $this->loadModel('Client');
        $clientIds = explode(',', $clientIds);
        $clientIds = "'".join("','", $clientIds)."'";
        $sql = "update client set status={$status} where client_id in ({$clientIds})";
//        $sql = "select proname,prosrc from pg_proc where proname='class4_trigfun_record_client'";
//        $sql = "select * from client_record LIMIT 1";
        $tmpRes = $this->Client->query($sql);

        $this->Client->query("update resource set  active={$status} where  client_id in ($clientIds)");

        echo json_encode(array('result' => ucfirst($task) . 'd'));
    }

    function changeClientsActiveAll()
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $clientIds = rtrim($this->params['url']['ids'], ',');
        $task = $this->params['url']['task'];
        $status = $task == 'activate' ? 'true' : 'false';
        $this->loadModel('Client');
        if ($clientIds) {
            $this->Client->query("update client set  status={$status} where client_id in ({$clientIds});");
            $this->Client->query("update resource set  active={$status} where  client_id in ($clientIds);");

            $rollback_sql = "UPDATE client SET status={$status} WHERE client_id in ({$clientIds});";
            $rollback_sql .= "UPDATE resource SET  active={$status} WHERE  client_id in ($clientIds);";
            $rollback_msg = "Operation have been rolled back!";
            $this->Client->logging(2, 'Carrier', "All Carriers",$rollback_sql,$rollback_msg);
        }

        echo json_encode(array('result' => ucfirst($task) . 'd'));
    }

    public function getClientIPs()
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;

        $order_field = "alias asc";
        $client_id = rtrim($this->params['pass'][0]);
        $this->loadModel('Client');
        $IPs = [];
        $resource_ip_arr = $this->Gatewaygroup->get_resource_ip_by_client_id($client_id, $order_field);

        foreach($resource_ip_arr as $resource_ip){
            if(!empty($resource_ip['resource_ip'])){
                $ip = $resource_ip['resource_ip'][0][0]['ip'];
                $IPs[$ip] = $resource_ip[0]['alias'];
            }

        }
        $this->jsonResponse(['status'=> true, 'data'=> $IPs]);
    }

    function mass_edit($clientIds)
    {
        $this->pageTitle = "Management/Mass Edit Carrier";
        $this->init_info();
        if ($this->RequestHandler->isPost()) {
            $clientIds = base64_decode($clientIds);
            $clientIds = rtrim($clientIds, ',');
            $clientIds = explode(',', $clientIds);
            foreach ($clientIds as $clientId) {
                $client = $this->Client->findByClientId($clientId);
                $client['Client']['allowed_credit'] = 0 - $client['Client']['allowed_credit'];
                $flag = $this->Client->saveOrUpdate($client, $_POST['data'], true); //保存
                if(isset($flag['client_id']) && intval($flag['client_id']) != 0){
                    $low_balance_config = $client['low_balance'];
                    $low_balance_config['client_id'] = intval($flag['client_id']);
                    $this->loadModel('ClientLowBalance');
                    $this->ClientLowBalance->save($low_balance_config);
                }
            }
            if (!is_array($flag)){
                $this->Session->write("m",$flag);
            }else{
                if ($flag['client_id']) {
                    $this->Session->write("m", $this->Client->create_json(201, __('Selected Carriers are edited successfully!.', true)));
                    $url_flug = "clients-index";
                    $this->modify_log_noty($flag['log_id'], $url_flug);
                }
            }
            $this->redirect("/clients/index");
        }
    }


    public function ajax_change_account_setting()
    {
        Configure::write('debug', 0);
        $this->autoLayout = false;
        $this->autoRender = false;
        $client_id = $this->Session->read('sst_client_id');
        if(!$client_id){
            $this->jsonResponse(['status' => false, 'msg'=> __('Account not found!', true)]);
        }
        $sql = "UPDATE client SET email = '{$this->data["email"]}',noc_email = '{$this->data["noc_email"]}',billing_email = '{$this->data["billing_email"]}',rate_email = '{$this->data["rate_email"]}',rate_delivery_email = '{$this->data["rate_delivery_email"]}' WHERE client_id = {$client_id}";
        $res = $this->Client->query($sql);
        if($res === false){
            $this->jsonResponse(['status' => false, 'msg'=> __('Save Failed!', true)]);
        }else{
            $this->jsonResponse(['status' => true, 'msg'=> __('Saved Successfully!', true)]);
        }
    }

}

?>
