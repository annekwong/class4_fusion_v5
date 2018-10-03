<?php

class VendorsController extends DidAppController
{

    var $name = 'Vendors';
    var $uses = array('Client', 'User', 'prresource.Gatewaygroup', 'Rate', 'did.DidBillingPlan','did.OrigLog');
    var $components = array('RequestHandler');
    var $helpers = array('javascript', 'html', 'Common');
    var $rollback = false;

    public function index()
    {
        $this->pageTitle = "Origination/Vendors";
        $this->paginate = array(
            'limit' => 100,
            'fields' => array('Client.client_id', 'Client.name', 'Balance.balance', 'Client.update_at', 'Client.update_by', 'Client.status', 'Resource.billing_rule'),
            'order' => array(
                'Client.name' => 'asc',
            ),
            'joins' => array(
                array(
                    'table' => 'client_balance_operation_action',
                    'alias' => "Balance",
                    'type' => 'LEFT',
                    'conditions' => array(
                        'Balance.client_id::integer = Client.client_id',
                    ),
                ),
                array(
                    'table' => 'resource',
                    'alias' => "Resource",
                    'type' => 'INNER',
                    'conditions' => array(
                        'Resource.alias = Client.name',
                    ),
                )
            ),
            'conditions' => array('Client.client_type' => 0),
        );

        $this->data = $this->paginate('Client');
        if (empty($this->data))
        {
            $msg = "Origination Vendors";
            $add_url = "add";
            $model_name = "Client";
            $this->to_add_page($model_name,$msg,$add_url);
        }



        foreach ($this->data as &$item)
        {
            $item['ResourceIps'] = $this->Client->get_resource_ips($item['Client']['client_id']);

            $item["Client"]['ingress_id'] = $this->Client->get_ingress_resource_id($item['Client']['client_id']);
        }
        $this->set("routing_rules", $this->Gatewaygroup->getBillingRules());
    }

    public function edit($client_id)
    {
        $client = array();
        $clientInfo = $this->Client->findByClientId($client_id);
        $client['client_id'] = $clientInfo['Client']['client_id'];
        $client['name'] = $clientInfo['Client']['name'];
//        $userInfo = $this->User->findByClientId($client_id);
//        $client['login_username'] = $userInfo['User']['name'];
        $resourceInfo = $this->Gatewaygroup->findByClientId($client_id);
        $client['call_limit'] = $resourceInfo['Gatewaygroup']['capacity'];
        $client['media_type'] = $resourceInfo['Gatewaygroup']['media_type'];
        $client['unlimited_credit'] = true;
//        $client['t_38'] = $resourceInfo['Gatewaygroup']['t38'];
//        $client['rfc2833'] = $resourceInfo['Gatewaygroup']['rfc2833'];
        $client['auto_invoicing'] = $clientInfo['Client']['auto_invoicing'];
        $client['billing_rule'] = $resourceInfo['Gatewaygroup']['billing_rule'];
        $client['digit_mapping'] = $this->Client->get_digit_mapping($resourceInfo['Gatewaygroup']['resource_id']);
        $client['resource_ips'] = array();
        $resource_ips_result = $this->Client->query("select ip from resource_ip where resource_id = {$resourceInfo['Gatewaygroup']['resource_id']}");
        foreach ($resource_ips_result as $resource_ip)
        {
            array_push($client['resource_ips'], $resource_ip[0]['ip']);
        }
        $digit_mappings = $this->Client->get_digit_mappings();
        $routing_rules = $this->Gatewaygroup->getBillingRules();
        $this->set("digit_mappings", $digit_mappings);
        $this->set("routing_rules", $routing_rules);
        if ($this->RequestHandler->isPost())
        {
            $name = $_POST['name'];
//            $login_username = $_POST['login_username'];
//            $login_password = $_POST['login_password'];
            $ip_addresses = $_POST['ip_addresses'];
            $call_limit = $_POST['call_limit'];
            $media_type = $_POST['media_type'];
//            $t_38 = isset($_POST['t_38']) ? TRUE : FALSE;
//            $rfc2833 = isset($_POST['rfc2833']) ? 1 : 0;
            $billing_rule = $_POST['pricing_rule'];
            $digit_mapping = $_POST['digit_mapping'];
            $auto_invoicing = isset($_POST['auto_invoicing']) ? true : false;

            $clientInfo['Client']['name'] = $name;
//            $clientInfo['Client']['login'] = $login_username;
            $clientInfo['Client']['auto_invoicing'] = $auto_invoicing;
            $clientInfo['Client']['update_at'] = date("Y-m-d H:i:s");
            $clientInfo['Client']['update_by'] = $_SESSION['sst_user_name'];
//            if ($login_password != $userInfo['User']['password'])
//                $clientInfo['Client']['password'] = $login_password;
            $this->Client->begin();
            $flg = $this->Client->save($clientInfo);
            if ($flg === false)
            {
                $this->Client->rollback();
            }

//            if (empty($userInfo))
//            {
//                if ($login_password)
//                {
//                    $possoword = md5($login_password);
//                }
//                $sql = "insert into users (name,password,client_id,user_type) values('$login_username','$possoword','$client_id',3)";
//                $flg1 = $this->User->query($sql, false);
//                if ($flg1 === false)
//                {
//                    $this->Client->rollback();
//                }
//            }
//            else
//            {
//                if ($login_password != $userInfo['User']['password'])
//                    $userInfo['User']['password'] = md5($login_password);
//                $flg2 = $this->User->save($userInfo);
//                if ($flg2 === false)
//                {
//                    $this->Client->rollback();
//                }
//            }

            $resourceInfo['Gatewaygroup']['alias'] = $name;
            $resourceInfo['Gatewaygroup']['media_type'] = $media_type;
            $resourceInfo['Gatewaygroup']['capacity'] = $call_limit;
//            $resourceInfo['Gatewaygroup']['t38'] = $t_38;
//            $resourceInfo['Gatewaygroup']['rfc2833'] = $rfc2833;
            $resourceInfo['Gatewaygroup']['billing_rule'] = $billing_rule;

            $flg3 = $this->Gatewaygroup->save($resourceInfo);
            if ($flg3 === false)
            {
                $this->Client->rollback();
            }

            $resource_id = $resourceInfo['Gatewaygroup']['resource_id'];
            $sql = "delete from resource_ip where resource_id = {$resource_id}";

            $flg4 = $this->Gatewaygroup->query($sql);
            if ($flg4 === false)
            {
                $this->Client->rollback();
            }
            $flg5 = $this->Gatewaygroup->query("delete from  resource_translation_ref  where resource_id = $resource_id");
            if ($flg5 === false)
            {
                $this->Client->rollback();
            }

            foreach ($ip_addresses as $ip_address)
            {
                if (!empty($ip_address))
                {
                    $sql = "insert into resource_ip(resource_id, ip) values($resource_id, '{$ip_address}')";
                    $this->Gatewaygroup->query($sql);
                }
            }

            if (!empty($digit_mapping))
            {
                $sql = "select time_profile_id from time_profile where type = 0";
                $time_profile_result = $this->Gatewaygroup->query($sql);
                if (empty($time_profile_result))
                {
                    $flg6 = $time_profile_result = $this->Gatewaygroup->query("insert into time_profile (name, type) VALUES('All', 0) returning time_profile_id");
                    if ($flg6 === false)
                    {
                        $this->Client->rollback();
                    }
                }
                $this->Gatewaygroup->query("insert into resource_translation_ref(resource_id, translation_id, time_profile_id) VALUES($resource_id, $digit_mapping, {$time_profile_result[0][0]['time_profile_id']})");
            }

            $log_detail_arr = array();
            if (strcmp($name, $client['name']))
                $log_detail_arr[] = "Name[{$client['name']}] => {$name}";
            if (strcmp($billing_rule, $client['billing_rule']))
                $log_detail_arr[] = "Pricing Rule[{$routing_rules[$client['billing_rule']]}] => {$routing_rules[$billing_rule]}";
            if (strcmp($digit_mapping, $client['digit_mapping']))
            {
                $old_sql = "select translation_id,translation_name from digit_translation where translation_id = {$client['digit_mapping']}";
                $new_sql = "select translation_id,translation_name from digit_translation where translation_id = {$digit_mapping}";
                $old_digit_mapping = $this->Client->query($old_sql);
                $new_digit_mapping = $this->Client->query($new_sql);
                $log_detail_arr[] = "Digit Mapping[{$old_digit_mapping['translation_name']}] => {$new_digit_mapping['translation_name']}";
            }
            if (strcmp($call_limit, $client['call_limit']))
                $log_detail_arr[] = "Call Limit[{$client['call_limit']}] => {$call_limit}";
            if (strcmp($media_type, $client['media_type']))
            {
                $media_type_arr = array(
                    2 => 'Bypass Media',
                    1 => 'Proxy Media',
                    0 => 'Transcoding media'
                );
                $log_detail_arr[] = "Media Type[{$media_type_arr[$client['media_type']]}] => {$media_type_arr[$media_type]}";
            }
            $delete_ips = implode(",", array_diff($client['resource_ips'], $ip_addresses));
            $add_ips = implode(",", array_diff($ip_addresses, $client['resource_ips']));
            if ($add_ips)
                $log_detail_arr[] = "Add IP[{$add_ips}]";
            if ($delete_ips)
                $log_detail_arr[] = "Delete IP[{$delete_ips}]";

            if(isset($_POST['is_upload']))
            {
                Configure::load('myconf');
                $duplicate_type = $_POST['duplicate_type'];
                $path = APP . 'tmp' . DS . 'upload' . DS . 'csv';
                $upload_file = $path . DS . trim($_POST['myfile_guid']) . ".csv";
                $cmds = array();

                array_push($cmds, "'s/\\r/\\n/g'");
                array_push($cmds, "'/^$/d'");
                array_push($cmds, "'s/\?//g'");
                array_push($cmds, "'1s/$/,vendor,client/g'");
                array_push($cmds, "'2,\$s/$/,$name,/g'");
                $cmd_str = implode(' -e ', $cmds);
                $sed_cmd = "sed -i -e {$cmd_str} {$upload_file}";
                shell_exec($sed_cmd);
                $user_id = 0;
                if (isset($_SESSION ['sst_user_id']))
                {
                    $user_id = $_SESSION ['sst_user_id'];
                }
                App::import('Model', 'ImportExportLog');
                $export_log = new ImportExportLog();
                $data = array();
                $data ['ImportExportLog']['ext_attributes'] = array();
                $data ['ImportExportLog']['time'] = gmtnow();
                $data ['ImportExportLog']['obj'] = 'DID';
                $data ['ImportExportLog']['file_path'] = $upload_file;
                $error_file = $upload_file . '.error';
                new File($error_file, true, 0777);
                $data ['ImportExportLog']['error_file_path'] = $error_file;
                $data ['ImportExportLog']['user_id'] = $user_id;
                $data ['ImportExportLog']['log_type'] = ImportExportLog::LOG_TYPE_IMPORT;
                $data ['ImportExportLog']['upload_type'] = '13';
                $data ['ImportExportLog']['duplicate_type'] = $duplicate_type;
                $data ['ImportExportLog']['myfile_filename'] = isset($this->params['form']['myfile_filename']) ? $this->params['form']['myfile_filename'] : "";
                $export_log->save($data);
                $script_path = Configure::read('script.path');
                $perl_path = $script_path . DS . 'class4_upload_check.pl';
                $perl_conf = CONF_PATH;
                $id = $export_log->id;
                $cmd = "perl $perl_path -c $perl_conf -i {$id}  &";
                if (Configure::read('cmd.debug'))
                {
                    file_put_contents('/tmp/cmd_debug', $cmd);
                }
                shell_exec($cmd);
            }



            if ($log_detail_arr)
            {
                $action = 2;
                $log_head = "Vendor name [{$name}] :";
                $log_detail = $log_head.implode("<br />", $log_detail_arr);
                $this->OrigLog->add_orig_log("Vendor", $action, $log_detail);
            }
            $this->Session->write('m', $this->Client->create_json(201, __('The  Vendor [' . $name . '] is modified successfully!', true)));
            $this->Client->commit();
            $this->redirect('/did/vendors/index');
        }
        $this->set('client', $client);
    }

    public function ajax_add_vendor()
    {
        Configure::write('debug', 0);
        $this->set("digit_mappings", $this->Client->get_digit_mappings());
    }

    public function add($ajax = 0)
    {
        $cmds = array();

        if ($ajax)
        {
            Configure::write('debug', 0);
            $this->autoLayout = false;
            $this->autoRender = false;
        }
        if ($this->RequestHandler->isPost())
        {
            $name = $_POST['name'];
//            $login_username = $_POST['login_username'];
//            $login_password = $_POST['login_password'];
            $ip_addresses = $_POST['ip_addresses'];
            $call_limit = $_POST['call_limit'];
            $media_type = $_POST['media_type'];
//            $t_38 = isset($_POST['t_38']) ? TRUE : FALSE;
//            $rfc2833 = isset($_POST['rfc2833']) ? 1 : 0;
            $billing_rule = $_POST['pricing_rule'];
            $digit_mapping = $_POST['digit_mapping'];
            $auto_invoicing = isset($_POST['auto_invoicing']) ? true : false;

            $sql = "SELECT currency_id FROM currency WHERE code = 'USA' lIMIT 1";
            $currency_result = $this->Client->query($sql);
            $this->Client->begin();
            if (empty($currency_result))
            {
                $currency_result = $this->Client->query("INSERT INTO currency (code) VALUES ('USA') returning currency_id");
                if ($currency_result === false)
                {
                    $this->rollback = TRUE;
                    $this->Client->rollback();
                }
            }

            $client = array(
                'Client' => array(
                    'name' => $name,
                    'mode' => 2,
                    'currency_id' => $currency_result[0][0]['currency_id'],
                    'is_panelaccess' => true,
                    'is_client_info' => true,
                    'is_invoices' => true,
                    'is_rateslist' => true,
                    'is_summaryreport' => true,
                    'is_cdrslist' => true,
                    'is_mutualsettlements' => true,
                    'is_changepassword' => true,
                    'client_type' => 0,
//                    'login' => !empty($login_username) ? $login_username : NULL,
//                    'password' => !empty($login_password) ? $login_password : NULL,
                    'update_by' => $_SESSION['sst_user_name'],
                    'enough_balance' => true,
                    'auto_invoicing' => $auto_invoicing,
                    'unlimited_credit' => true
                )
            );

            // 检测是否存在相同client

            $flg = $this->Client->save($client);
            if ($flg === false)
            {
                $this->rollback = TRUE;
                $this->Client->rollback();
            }
            $client_id = $this->Client->getLastInsertID();

            // 创建Balance
            $sql = "insert into client_balance_operation_action(client_id, balance) values ($client_id, 1)";
            $flg1 = $this->Client->query($sql);
            if ($flg1 === false)
            {
                $this->rollback = TRUE;
                $this->Client->rollback();
            }

            // 创建 Ingress

            $rate_table_id = $this->Rate->create_ratetable($name, 'NULL', $currency_result[0][0]['currency_id'], 0, true, 2, true);
            if ($rate_table_id === false)
            {
                $this->rollback = TRUE;
                $this->Client->rollback();
            }

            $ingress = array(
                'Gatewaygroup' => array(
                    'alias' => $name,
                    'client_id' => $client_id,
                    'media_type' => $media_type,
                    'capacity' => $call_limit,
//                    't38' => $t_38,
//                    'rfc2833' => $rfc2833,
                    'trunk_type2' => 1,
                    'ingress' => true,
                    'billing_rule' => $billing_rule,
                    'enough_balance' => true,
                    'rate_table_id' => $rate_table_id,
                )
            );




            $flg3 = $this->Gatewaygroup->save($ingress);
            if ($flg3 === false)
            {
                $this->rollback = TRUE;
                $this->Client->rollback();
            }
            $resource_id = $this->Gatewaygroup->getLastInsertID();

            foreach ($ip_addresses as $ip_address)
            {
                if (!empty($ip_address))
                {
                    $sql = "insert into resource_ip(resource_id, ip) values($resource_id, '{$ip_address}')";
                    $this->Gatewaygroup->query($sql);
                }
            }

            $sql = "SELECT route_strategy_id from route_strategy where name = 'ORIGINATION_ROUTING_PLAN'";
            $result = $this->Gatewaygroup->query($sql);
            if (empty($result))
            {
                $sql = "insert into route_strategy (name) values ('ORIGINATION_ROUTING_PLAN') returning route_strategy_id";
                $flg4 = $result = $this->Gatewaygroup->query($sql);
                if ($flg4 === false)
                {
                    $this->Client->rollback();
                }

                $sql = "select product_id from product where name = 'ORIGINATION_STATIC_ROUTE'";
                $result_static = $this->Gatewaygroup->query($sql);
                if (empty($result_static))
                {
                    $sql = "insert into product(name) values ('ORIGINATION_STATIC_ROUTE') returning product_id";
                    $flg5 = $result_static = $this->Gatewaygroup->query($sql);
                    if ($flg5 === false)
                    {
                        $this->rollback = TRUE;
                        $this->Client->rollback();
                    }
                }
                $sql = "insert into route(static_route_id, route_type, route_strategy_id) values({$result_static[0][0]['product_id']}, 2, {$result[0][0]['route_strategy_id']});";
                $flg6 = $this->Gatewaygroup->query($sql);
                if ($flg6 === false)
                {
                    $this->rollback = TRUE;
                    $this->Client->rollback();
                }
            }
            $sql = "insert into resource_prefix (rate_table_id, route_strategy_id, resource_id) 
                            values ({$rate_table_id},{$result[0][0]['route_strategy_id']}, {$resource_id})";

            $flg7 = $this->Gatewaygroup->query($sql);
            if ($flg7 === false)
            {
                $this->rollback = TRUE;
                $this->Client->rollback();
            }

            if (!empty($digit_mapping))
            {
                $sql = "select time_profile_id from time_profile where type = 0";
                $time_profile_result = $this->Gatewaygroup->query($sql);
                if (empty($time_profile_result))
                {
                    $time_profile_result = $this->Gatewaygroup->query("insert into time_profile (name, type) VALUES('All', 0) returning time_profile_id");
                    if ($time_profile_result === false)
                    {
                        $this->rollback = TRUE;
                        $this->Client->rollback();
                    }
                }
                $this->Gatewaygroup->query("insert into resource_translation_ref(resource_id, translation_id, time_profile_id) VALUES($resource_id, $digit_mapping, {$time_profile_result[0][0]['time_profile_id']})");
            }

            if (!$ajax)
            {
                $billing_rule = $this->DidBillingPlan->findById($billing_rule);
                $min_price = $billing_rule['DidBillingPlan']['min_price'];
                $rate_insert_flg = $this->save_did_rate($rate_table_id,$min_price);
                if($rate_insert_flg === false)
                {
                    $this->rollback = TRUE;
                    $this->Client->rollback();
                }
            }

            if ($this->rollback)
            {
                $this->Client->rollback();
            }
            $this->Client->commit();

            if(isset($_POST['is_upload']) && !$this->rollback)
            {
                Configure::load('myconf');
                $duplicate_type = $_POST['duplicate_type'];
                $path = APP . 'tmp' . DS . 'upload' . DS . 'csv';
                $upload_file = $path . DS . trim($_POST['myfile_guid']) . ".csv";
                $cmds = array();

                array_push($cmds, "'s/\\r/\\n/g'");
                array_push($cmds, "'/^$/d'");
                array_push($cmds, "'s/\?//g'");
                array_push($cmds, "'1s/$/,vendor,client/g'");
                array_push($cmds, "'2,\$s/$/,$name,/g'");
                $cmd_str = implode(' -e ', $cmds);
                $sed_cmd = "sed -i -e {$cmd_str} {$upload_file}";
                shell_exec($sed_cmd);
                $user_id = 0;
                if (isset($_SESSION ['sst_user_id']))
                {
                    $user_id = $_SESSION ['sst_user_id'];
                }
                App::import('Model', 'ImportExportLog');
                $export_log = new ImportExportLog();
                $data = array();
                $data ['ImportExportLog']['ext_attributes'] = array();
                $data ['ImportExportLog']['time'] = gmtnow();
                $data ['ImportExportLog']['obj'] = 'DID';
                $data ['ImportExportLog']['file_path'] = $upload_file;
                $error_file = $upload_file . '.error';
                new File($error_file, true, 0777);
                $data ['ImportExportLog']['error_file_path'] = $error_file;
                $data ['ImportExportLog']['user_id'] = $user_id;
                $data ['ImportExportLog']['log_type'] = ImportExportLog::LOG_TYPE_IMPORT;
                $data ['ImportExportLog']['upload_type'] = '13';
                $data ['ImportExportLog']['duplicate_type'] = $duplicate_type;
                $data ['ImportExportLog']['myfile_filename'] = isset($this->params['form']['myfile_filename']) ? $this->params['form']['myfile_filename'] : "";
                $export_log->save($data);
                $script_path = Configure::read('script.path');
                $perl_path = $script_path . DS . 'class4_upload_check.pl';
                $perl_conf = CONF_PATH;
                $id = $export_log->id;
                $cmd = "perl $perl_path -c $perl_conf -i {$id}  &";
                if (Configure::read('cmd.debug'))
                {
                    file_put_contents('/tmp/cmd_debug', $cmd);
                }
                shell_exec($cmd);
            }
            if ($ajax)
            {
                if ($this->rollback)
                {
                    echo 0;
                }
                else
                {
                    $action = 0;
                    $log_detail = "Vendor name [{$name}]";
                    $this->OrigLog->add_orig_log("Vendor", $action, $log_detail);
                    echo 1;
                }
            }
            else
            {
                if ($this->rollback)
                {
                    $this->Session->write('m', $this->Client->create_json(101, __('The  Vendor [' . $name . '] is created failed!', true)));
                }
                else
                {
                    $action = 0;
                    $log_detail = "Vendor name [{$name}]";
                    $this->OrigLog->add_orig_log("Vendor", $action, $log_detail);
                    $this->Session->write('m', $this->Client->create_json(201, __('The  Vendor [' . $name . '] is created successfully!', true)));
                }
                $this->redirect('/did/vendors/index');
            }
        }
        $this->set("digit_mappings", $this->Client->get_digit_mappings());
        $this->set("routing_rules", $this->Gatewaygroup->getBillingRules());
    }

    public function disable()
    {
        $id = $this->params['pass'][0];
        $mesg_info = $this->Client->query("select name from client where client_id = {$id}");
        $this->Client->query("update client set  status=false where  client_id= $id;");
        $this->Client->query("update resource set  active=false where  client_id= $id;");
        $this->Client->create_json_array('', 201, __('The Vendor [' . $mesg_info[0][0]['name'] . '] is disabled successfully!', true));
        $this->Session->write("m", Client::set_validator());
        $this->redirect('/did/vendors/index');
    }

    public function enable()
    {
        $id = $this->params['pass'][0];
        $mesg_info = $this->Client->query("select name from client where client_id = {$id}");
        $this->Client->active($id);
        $this->Client->query("update resource set  active=true where  client_id= $id;");
        $this->Client->create_json_array('', 201, __('The Vendor [' . $mesg_info[0][0]['name'] . '] is enabled successfully!', true));
        $this->Session->write("m", Client::set_validator());
        $this->redirect('/did/vendors/index');
        ;
    }

    public function delete($client_id)
    {
        $clientInfo = $this->Client->findByClientId($client_id);
        $sql = "select * from resource where client_id = $client_id";
        $resource = $this->Client->query($sql);
        $this->Client->begin();
        $del_sql = "DELETE FROM ingress_did_repository WHERE egress_id =$client_id ";
        if ($this->Client->query($del_sql) === false)
        {
            $this->rollback = true;
            $this->Client->rollback();
        }
        $delete_user_sql = "DELETE FROM users WHERE name = '{$resource[0][0]['alias']}'";
        if ($this->Client->query($delete_user_sql) === false)
        {
            $this->rollback = TRUE;
            $this->Client->rollback();
        }
        $delete_rate_table_sql = "delete from rate_table where rate_table_id = {$resource[0][0]['rate_table_id']}";
        if ($this->Client->query($delete_rate_table_sql) === false)
        {
            $this->rollback = TRUE;
            $this->Client->rollback();
        }
        $delete_resource_sql = "delete from resource where resource_id = {$resource[0][0]['resource_id']}";
        if ($this->Client->query($delete_resource_sql) === false)
        {
            $this->rollback = TRUE;
            $this->Client->rollback();
        }
        $delete_resourceip_sql = "delete from resource_ip where resource_id = {$resource[0][0]['resource_id']}";
        if ($this->Client->query($delete_resourceip_sql) === false)
        {
            $this->rollback = TRUE;
            $this->Client->rollback();
        }
        $delete_resource_prefix_sql = "delete from resource_prefix where resource_id = {$resource[0][0]['resource_id']}";
        if ($this->Client->query($delete_resource_prefix_sql) === false)
        {
            $this->rollback = TRUE;
            $this->Client->rollback();
        }
        $flg = $this->Client->query("delete from users_limit where client_id = {$client_id}");
        if ($flg === false)
        {
            $this->rollback = true;
            $this->Client->rollback();
        }
        if ($this->Client->del($client_id, 'false') === false)
        {
            $this->rollback = true;
            $this->Client->rollback();
        }
        $this->Client->commit();
        if ($this->rollback === false)
        {
            $this->Client->create_json_array('', 201, __('The Vendor [' . $clientInfo['Client']['name'] . '] is deleted successfully!', true));
        }
        else
        {
            $this->Client->create_json_array('', 201, __('The Vendor [' . $clientInfo['Client']['name'] . '] is deleted failed!', true));
        }
        $this->Session->write("m", Client::set_validator());
        $this->redirect('/did/vendors/index');
    }

}
