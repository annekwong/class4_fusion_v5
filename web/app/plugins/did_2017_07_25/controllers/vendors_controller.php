<?php

class VendorsController extends DidAppController
{

    var $name = 'Vendors';
    var $uses = array('Client', 'User', 'prresource.Gatewaygroup', 'Rate', 'did.DidBillingPlan','did.OrigLog','did.Did', 'Systemparam');
    var $components = array('RequestHandler');
    var $helpers = array('javascript', 'html', 'Common');
    var $rollback = false;

    public function index()
    {
//        $tmpRes = $this->Client->query('SELECT * FROM did_billing_plan LIMIT 1');
//        die(var_dump($tmpRes));

        $this->pageTitle = "Origination/Vendors";
        $search = isset($this->params['url']['search']) ? $this->params['url']['search'] : '';
        $pageSize = isset($this->params['url']['size']) ? $this->params['url']['size'] : 100;
        $conditions = array('Client.client_type' => 0);
        if($search != '') {
            $conditions = array(
                'Client.client_type' => 0,
                'Client.name like' => "%$search%"
            );
        }
        $this->paginate = array(
            'limit' => $pageSize,
            'fields' => array('Client.client_id', 'Client.name', 'Balance.balance', 'Client.update_at', 'Client.update_by', 'Client.status', 'Resource.billing_rule', 'Resource.resource_id'),
            'order' => array(
                'Client.name' => 'asc',
            ),
            'joins' => array(
                array(
                    'table' => 'c4_client_balance',
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
            'conditions' => $conditions
        );

        $this->data = $this->paginate('Client');

        if (empty($this->data) && $search == '')
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
            $rateTableId = $this->Client->query("SELECT rate_table_id FROM resource WHERE resource_id = {$item["Client"]['ingress_id']}");
            $item['Client']['rate_table_id'] = $rateTableId[0][0]['rate_table_id'];
            $sql = "SELECT translation_id FROM digit_translation WHERE translation_name='{$item["Client"]["name"]}'";
            $res = $this->Client->query($sql);
            if(isset($res[0][0]["translation_id"]))
                $item["TranslationId"] = $res[0][0]["translation_id"];
        }
        $this->set("routing_rules", $this->Gatewaygroup->getBillingRules());
    }

    public function edit($client_id)
    {
        $client = array();
        $clientInfo = $this->Client->findByClientId($client_id);
        $client['client_id'] = $clientInfo['Client']['client_id'];
        $client['name'] = $clientInfo['Client']['name'];
        $resourceInfo = $this->Gatewaygroup->findByClientId($client_id);
        $resource_id = $resourceInfo['Gatewaygroup']['resource_id'];
        $client['t38'] = $resourceInfo['Gatewaygroup']['t38'];
        $client['call_limit'] = $resourceInfo['Gatewaygroup']['capacity'];
        $client['media_type'] = $resourceInfo['Gatewaygroup']['media_type'];
        $client['billing_type'] = $resourceInfo['Gatewaygroup']['billing_type'];
        $client['unlimited_credit'] = true;
        $client['auto_invoicing'] = $clientInfo['Client']['auto_invoicing'];
        $client['billing_rule'] = $resourceInfo['Gatewaygroup']['billing_rule'];
        $client['digit_mapping'] = $this->Client->get_digit_mapping($resource_id);
        $client['resource_ips'] = array();
        $client['resource_ports'] = array();
        $resource_ips_result = $this->Client->query("select ip,port from resource_ip where resource_id = {$resourceInfo['Gatewaygroup']['resource_id']}");
        foreach ($resource_ips_result as $resource_ip)
        {
            array_push($client['resource_ips'], $resource_ip[0]['ip']);
            array_push($client['resource_ports'], $resource_ip[0]['port']);
        }
        $digit_mappings = $this->Client->get_digit_mappings();
        $routing_rules = $this->Gatewaygroup->getBillingRules();
        $this->set("digit_mappings", $digit_mappings);
        $this->set("routing_rules", $routing_rules);
        if ($this->RequestHandler->isPost())
        {
            $t38 = $_POST['t38'];
            $name = $_POST['name'];
            $ip_addresses = $_POST['ip_addresses'];
            $ip_port = $_POST['ip_port'];
            $call_limit = $_POST['call_limit'];
            $media_type = $_POST['media_type'];
            $billing_rule = $_POST['pricing_rule'];
            $billing_type = $_POST['billing_type'];
            $digit_mapping = $_POST['digit_mapping'];
            $auto_invoicing = isset($_POST['auto_invoicing']) ? true : false;
            $add_did_numbers = $this->params['form']['did_number'];

            $clientInfo['Client']['name'] = $name;
            $clientInfo['Client']['auto_invoicing'] = $auto_invoicing;
            $clientInfo['Client']['update_at'] = date("Y-m-d H:i:s");
            $clientInfo['Client']['update_by'] = $_SESSION['sst_user_name'];

            $this->Client->begin();
            $flg = $this->Client->save($clientInfo);
            if ($flg === false)
            {
                $this->Client->rollback();
            }


            $resourceInfo['Gatewaygroup']['t38'] = $t38;
            $resourceInfo['Gatewaygroup']['alias'] = $name;
            $resourceInfo['Gatewaygroup']['media_type'] = $media_type;
            $resourceInfo['Gatewaygroup']['capacity'] = $call_limit;
            $resourceInfo['Gatewaygroup']['billing_rule'] = $billing_rule;
            $resourceInfo['Gatewaygroup']['billing_type'] = $billing_type;

            $flg3 = $this->Gatewaygroup->save($resourceInfo);
            if ($flg3 === false)
            {
                $this->Client->rollback();
            }

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

            foreach ($ip_addresses as $ip_key =>$ip_address)
            {
                if (!empty($ip_address))
                {
                    $sql = "insert into resource_ip(resource_id, ip,port) values($resource_id, '{$ip_address}',$ip_port[$ip_key])";
                    $this->Gatewaygroup->query($sql);
                }
            }
            foreach ($add_did_numbers as $add_did_number)
            {
                if(intval($add_did_number))
                {
                    if($this->Did->check_exist_number($add_did_number))
                    {
                        $msg = sprintf(__('The number of [%s] is exist!', true),$add_did_number);
                        $this->Session->write('m', $this->Client->create_json(101, $msg));
                        continue;
                    }
                    $this->Did->begin();
                    $new_rate = $this->Did->insert_to_repository($resource_id,$add_did_number,'','');
                    if (empty($new_rate))
                    {
                        $this->Did->rollback();
                        continue;
                    }
                    else
                        $this->Did->commit();
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
                $info = $this->Systemparam->find('first',array(
                    'fields' => array('cmd_debug'),
                ));
                if(Configure::read('cmd_debug'))
                {
                    file_put_contents($info["Systemparam"]["cmd_debug"],$cmd);
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
            $this->Session->write('m', $this->Client->create_json(201, __('The  Vendor [%s] is modified successfully!', true, $name)));
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
        if ($ajax)
        {
            Configure::write('debug', 0);
            $this->autoLayout = false;
            $this->autoRender = false;
        }
        if ($this->RequestHandler->isPost())
        {
            $ip_addresses = $_POST['ip_addresses'];

            // check if IP exists
            if ($ip_addresses)
            {
                $ip_addresses = array_filter($ip_addresses, 'strlen');
                $ip_addresses = array_map('strval', $ip_addresses);
                $sql = "SELECT count(*) FROM resource_ip  WHERE ip IN ('" . implode("', '", $ip_addresses) . "')";
                $res = $this->Gatewaygroup->query($sql);
                if ($res[0][0]['count']) {
                    $this->Session->write('m', $this->Client->create_json(101, __('IP Address [%s] already exists!', true, $ip_addresses)));
                    $this->redirect('/did/vendors/add');
                }
            }

            $name = $_POST['name'];
            $ip_port = $_POST['ip_port'];
            $call_limit = $_POST['call_limit'];
            $media_type = $_POST['media_type'];
//            $billing_rule = $_POST['pricing_rule'];
            $auto_invoicing = isset($_POST['auto_invoicing']) ? true : false;
            $add_did_numbers = $this->params['form']['did_number'];

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
                    'client_type' => 0,
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
            $flg1 = $this->Client->clientBalanceOperation($client_id, 1, 0);
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

            $sql = "SELECT route_strategy_id from route_strategy where name = 'ORIGINATION_ROUTING_PLAN'";
            $result = $this->Gatewaygroup->query($sql);

            if (empty($result)) {
                $sql = "insert into route_strategy (name) values ('ORIGINATION_ROUTING_PLAN') returning route_strategy_id";
                $flg4 = $result = $this->Gatewaygroup->query($sql);
                if ($flg4 === false) {
                    $this->rollback = TRUE;
                    $this->Client->rollback();
                }
            }

            $routeStrategyId = $result[0][0]['route_strategy_id'];


            $ingress = array(
                'Gatewaygroup' => array(
                    'alias' => $name,
                    'client_id' => $client_id,
                    'media_type' => $media_type,
                    'capacity' => $call_limit,
                    'trunk_type2' => 1,
                    'ingress' => true,
                    'enough_balance' => true,
                    'rate_table_id' => $rate_table_id,
                    'route_strategy_id' => $routeStrategyId,
                    't38' => isset($_POST['t_38']) ? $_POST['t_38'] : true
//                    'product_id' => $productId,
//                    'billing_rule' => $billing_rule,
                )
            );

            $flg3 = $this->Gatewaygroup->save($ingress);

            if ($flg3 === false)
            {
                $this->rollback = TRUE;
                $this->Client->rollback();
            }
            $resource_id = $this->Gatewaygroup->getLastInsertID();
            foreach ($ip_addresses as $ip_key => $ip_address)
            {
                if (!empty($ip_address))
                {
                    $sql = "insert into resource_ip(resource_id, ip,port) values($resource_id, '{$ip_address}','{$ip_port[$ip_key]}')";
                    $this->Gatewaygroup->query($sql);
                }
            }

            if ($this->rollback)
            {
                $this->Session->write('m', $this->Client->create_json(101, __('Failed to create the Vendor [%s]!', true, $name)));
                $this->redirect('/did/vendors/index');
            }

//            die(var_dump($add_did_numbers));

            foreach ($add_did_numbers as $add_did_number)
            {
                if(intval($add_did_number))
                {
                    if($this->Did->check_exist_number($add_did_number))
                    {
                        $msg = sprintf(__('The number of [%s] is exist!', true),$add_did_number);
                        $this->Session->write('m', $this->Client->create_json(101, $msg));
                        continue;
                    }
//                    $this->Did->begin();
                    $new_rate = $this->Did->insert_to_repository($resource_id,$add_did_number,'','');
                }
            }

            $sql = "DELETE FROM digit_translation WHERE translation_name = '{$name}'";
            $this->Client->query($sql);

            $sql = "INSERT INTO digit_translation (translation_name) VALUES ('{$name}')";
            $this->Client->query($sql);

            if (!empty($digit_mapping))
            {
                $sql = "select time_profile_id from time_profile where type = 0 limit 1";
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
                $sql = "insert into resource_translation_ref(resource_id, translation_id, time_profile_id) VALUES($resource_id, $digit_mapping, {$time_profile_result[0][0]['time_profile_id']})";
                $this->Gatewaygroup->query($sql);

//                $sql = "SELECT * FROM resource_translation_ref";
//                $tmpRes = $this->Gatewaygroup->query($sql);
//                die(var_dump($tmpRes));
            }

//            if (!$ajax)
//            {
//                if(isset($_POST['billing_type']) && $_POST['billing_type'] == 2) {
//                    $billing_rule = $this->DidBillingPlan->findById($billing_rule);
//                    $min_price = (isset($_POST['min_price']) && !empty($_POST['min_price'])) ? $_POST['min_price'] : $billing_rule['DidBillingPlan']['min_price'];
//                    $rate_insert_flg = $this->save_did_rate($rate_table_id, $min_price);
//                    if ($rate_insert_flg === false) {
//                        $this->rollback = TRUE;
//                        $this->Client->rollback();
//                    }
//                }
//            }

            if ($this->rollback)
                $this->Client->rollback();
            $this->Client->commit();

            if(isset($_POST['is_upload']))
            {
                Configure::load('myconf');
                $duplicate_type = $_POST['duplicate_type'];
                $path = APP . 'tmp' . DS . 'upload' . DS . 'csv';
                $upload_file = $path . DS . trim($_POST['myfile_guid']) . ".csv";
                $row = 1;
                $handle = fopen($upload_file, "r");
                if ($handle !== FALSE) {
                    while (($add_did_number = fgetcsv($handle, 1000, ",")) !== FALSE) {
                        if ($row > 1) {
                            $add_did_number = $add_did_number[0];
                            if (intval($add_did_number)) {
//                                if ($this->Did->check_exist_number($add_did_number)) {
//                                    $msg = sprintf(__('The number of [%s] is exist!', true), $add_did_number);
//                                    $this->Session->write('m', $this->Client->create_json(101, $msg));
//                                    continue;
//                                }
                                $this->Did->insert_to_repository($resource_id, $add_did_number, '', '');
                            }
                        }
                        $row++;
                    }
                    fclose($handle);
                }
//                $this->Did->query("COPY zip_codes FROM '{$upload_file}' DELIMITER ',' CSV;");
//                $cmds = array();
//
//                array_push($cmds, "'s/\\r/\\n/g'");
//                array_push($cmds, "'/^$/d'");
//                array_push($cmds, "'s/\?//g'");
//                array_push($cmds, "'1s/$/,vendor,client/g'");
//                array_push($cmds, "'2,\$s/$/,$name,/g'");
//                $cmd_str = implode(' -e ', $cmds);
//                $sed_cmd = "sed -i -e {$cmd_str} {$upload_file}";
//                echo '<pre>';
//                die(var_dump($upload_file, $_POST, $_FILES));
//                shell_exec($sed_cmd);
                $user_id = 0;
                if (isset($_SESSION ['sst_user_id']))
                    $user_id = $_SESSION ['sst_user_id'];
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
            if(isset($_POST['billing_type']) && $_POST['billing_type'] == 1) {
                $this->redirect('/clientrates/import/' . base64_encode($rate_table_id));
//                die(var_dump($_POST));
//                if($_POST['billing_type'] == 1) {
//                    $_POST['myfile_guid'] = $_POST['billed_by_ani_guid'];
//                    $_POST['myfile_filename'] = $_POST['billed_by_ani_filename'];
//                    $path = APP . 'tmp' . DS . 'upload' . DS . 'csv';
//                    $upload_file = $path . DS . trim($_POST['myfile_guid']) . ".csv";
////                    die(var_dump($_POST));
//                    App::import('Controller', 'Clientrates');
//                    $clientRates = new ClientratesController;
//                    $clientRates->constructClasses();
//                    $clientRates->change_header($rate_table_id, $upload_file);
//                }
            }
            if ($ajax)
            {
                if ($this->rollback)
                    echo 0;
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
                if ($this->rollback){
                    //$this->Session->write('m', $this->Client->create_json(101, __('Failed to create the Vendor [%s]!', true, $name)));
                }
                else
                {
                    $action = 0;
                    $log_detail = "Vendor name [{$name}]";
                    $this->OrigLog->add_orig_log("Vendor", $action, $log_detail);
                    $this->Session->write('m', $this->Client->create_json(201, __('The  Vendor [%s] is created successfully!', true, $name)));

                    $this->redirect('/did/vendors/index');
                }
                
            }
        }

        $this->set("digit_mappings", $this->Client->get_digit_mappings());
        $this->set("routing_rules", $this->Gatewaygroup->getBillingRules());

    }

    public function chech_ip($ip)
    {
        Configure::write('debug', 0);echo 111;die;
        $this->autoRender = false;
        $this->autoLayout = false;
        $is_exists = $this->DidRepos->check_num($number);
        if ($is_exists)
            echo 'true';
        else
            echo 'false';
    }

    public function disable()
    {
        $id = $this->params['pass'][0];
        $mesg_info = $this->Client->query("select name from client where client_id = {$id}");
        $this->Client->query("update client set  status=false where  client_id= $id;");
        $this->Client->query("update resource set  active=false where  client_id= $id;");
        $this->Client->create_json_array('', 201, __('The Vendor [%s] is disabled successfully!', true, $mesg_info[0][0]['name']));
        $this->Session->write("m", Client::set_validator());
        $this->redirect('/did/vendors/index');
    }

    public function enable()
    {
        $id = $this->params['pass'][0];
        $mesg_info = $this->Client->query("select name from client where client_id = {$id}");
        $this->Client->active($id);
        $this->Client->query("update resource set  active=true where  client_id= $id;");
        $this->Client->create_json_array('', 201, __('The Vendor [%s] is enabled successfully!', true, $mesg_info[0][0]['name']));
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
        if ($this->Client->del($client_id) === false)
        {
            $this->rollback = true;
            $this->Client->rollback();
        }
        $this->Client->commit();
        if ($this->rollback === false)
        {
            $this->Client->create_json_array('', 201, __('The Vendor [%s] is deleted successfully!', true, $clientInfo['Client']['name']));
            $action = 1;
            $log_detail = "Vendor name [{$clientInfo['Client']['name']}]";
            $this->OrigLog->add_orig_log("Vendor", $action, $log_detail);
        }
        else
        {
            $this->Client->create_json_array('', 201, __('The Vendor [%s] is deleted failed!', true, $clientInfo['Client']['name']));
        }
        $this->Session->write("m", Client::set_validator());
        $this->redirect('/did/vendors/index');
    }

    public function dids($encodedVendorId)
    {
        if (!class_exists('Did')) {
            $this->loadModel('did.Did');
        }

        $vendorId = base64_decode($encodedVendorId);
        $count = $this->Did->get_data_count($vendorId, null, null, null);
        $data = $this->Did->get_data($vendorId, null, null, null, null, $count, 0);

        $this->set('data', $data);
    }

    public function deleteSelected()
    {
        if ($this->RequestHandler->isPost()) {
            Configure::write('debug', 0);
            $this->autoRender = false;
            $this->layout = false;

            $selected = $_POST['selected'];
            $type = $_POST['type'];
            $return = array('status' => 1);

            $this->Did->begin();

            if ($type == 1 || $type == 3) {
                $databaseExportPath = Configure::read('database_export_path');
                $filename = 'did_' . time() . '.csv';
                $file = $databaseExportPath  . DS . $filename;

                $this->Did->exportDid($selected, $file, $type);

                $this->loadModel('ImportExportLog');

                $save = array(
                    'file_path' => $filename,
                    'upload_type' => 10,
                    'time' => date('Y-m-d h:i:s'),
                    'status' => 6,
                    'obj' => 'DIDs',
                    'log_type' => 0,
                    'user_id' => $_SESSION['sst_user_id']
                );

                $this->ImportExportLog->save($save);
            }

            foreach ($selected as $item) {
                $result = $this->Did->deleteByResourceId($item);

                if (!$result) {
                    $return['status'] = 0;
                    break;
                }
            }

            $this->Did->commit();

            echo json_encode($return);
        }
    }
}
