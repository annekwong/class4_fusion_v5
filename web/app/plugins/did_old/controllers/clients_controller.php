<?php

class ClientsController extends DidAppController
{

    var $name = 'Clients';
    var $uses = array('Client', 'User', 'prresource.Gatewaygroup', 'Rate', 'did.DidBillingPlan','did.OrigLog');
    var $components = array('RequestHandler');
    var $helpers = array('javascript', 'html', 'Common');
    var $rollback = false;

    public function index()
    {
        $this->pageTitle = "Origination/Clients";
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
            'conditions' => array('Client.client_type' => 1),
        );

        $this->data = $this->paginate('Client');
        if (empty($this->data))
        {
            $msg = "Origination Client";
            $add_url = "add";
            $model_name = "Client";
            $this->to_add_page($model_name,$msg,$add_url);
        }



        foreach ($this->data as &$item)
        {
            $item['ResourceIps'] = $this->Client->get_resource_ips($item['Client']['client_id']);

            $item["Client"]['egress_id'] = $this->Client->get_egress_resource_id($item['Client']['client_id']);
        }
        $this->set("routing_rules", $this->Gatewaygroup->getBillingRules());
    }

    public function edit($client_id)
    {
        $client_id = base64_decode($client_id);
        $client = array();
        $clientInfo = $this->Client->findByClientId($client_id);
        $client['client_id'] = $clientInfo['Client']['client_id'];
        $client['name'] = $clientInfo['Client']['name'];
        $client['mode'] = $clientInfo['Client']['mode'];
        $client['allowed_credit'] = $clientInfo['Client']['allowed_credit'];
        $client['unlimited_credit'] = $clientInfo['Client']['unlimited_credit'];
        $client['auto_invoicing'] = $clientInfo['Client']['auto_invoicing'];
        $userInfo = $this->User->findByClientId($client_id);
        $client['login_username'] = $userInfo['User']['name'];
        $resourceInfo = $this->Gatewaygroup->findByClientId($client_id);
        $client['call_limit'] = $resourceInfo['Gatewaygroup']['capacity'];
        $client['media_type'] = $resourceInfo['Gatewaygroup']['media_type'];
//        $client['t_38'] = $resourceInfo['Gatewaygroup']['t38'];
//        $client['rfc2833'] = $resourceInfo['Gatewaygroup']['rfc2833'];
        $client['billing_rule'] = $resourceInfo['Gatewaygroup']['billing_rule'];
        $client['resource_ips'] = array();

        if ($resourceInfo['Gatewaygroup']['resource_id'])
        {
            $resource_ips_result = $this->Client->query("select ip from resource_ip where resource_id = {$resourceInfo['Gatewaygroup']['resource_id']}");
            foreach ($resource_ips_result as $resource_ip)
            {
                array_push($client['resource_ips'], $resource_ip[0]['ip']);
            }
        }
        if ($this->RequestHandler->isPost())
        {
            $name = $_POST['name'];
            $login_username = $_POST['login_username'];
            $login_password = $_POST['login_password'];
            $ip_addresses = $_POST['ip_addresses'];
            $call_limit = $_POST['call_limit'];
            $media_type = $_POST['media_type'];
//            $t_38 = isset($_POST['t_38']) ? TRUE : FALSE;
//            $rfc2833 = isset($_POST['rfc2833']) ? 1 : 0;
            $auto_invocing = isset($_POST['auto_invoicing']) ? TRUE : FALSE;
            $billing_rule = $_POST['pricing_rule'];

            $clientInfo['Client']['name'] = $name;
            $clientInfo['Client']['login'] = $login_username;
            $clientInfo['Client']['auto_invoicing'] = $auto_invocing;
            $clientInfo['Client']['update_at'] = date("Y-m-d H:i:s");
            $clientInfo['Client']['update_by'] = $_SESSION['sst_user_name'];
            $clientInfo['Client']['mode'] = $_POST['data']['mode'];
            $clientInfo['Client']['allowed_credit'] = $_POST['data']['allowed_credit'];
            $clientInfo['Client']['unlimited_credit'] = $_POST['data']['unlimited_credit'];
            $this->Client->begin();
            if ($login_password != $userInfo['User']['password'])
                $clientInfo['Client']['password'] = $login_password;

            $flg = $this->Client->save($clientInfo);
            if ($flg === false)
            {
                $this->Client->rollback();
            }

            if (empty($userInfo))
            {
                if ($login_password)
                {
                    $possoword = md5($login_password);
                }
                $sql = "insert into users (name,password,client_id,user_type) values('$login_username','$possoword','$client_id',3)";
                $flg1 = $this->User->query($sql, false);
                if ($flg1 === false)
                {
                    $this->Client->rollback();
                }
            }
            else
            {
                if ($login_password != $userInfo['User']['password'])
                    $userInfo['User']['password'] = md5($login_password);
                $flg2 = $this->User->save($userInfo);
                if ($flg2 === false)
                {
                    $this->Client->rollback();
                }
            }

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

            foreach ($ip_addresses as $ip_address)
            {
                if (!empty($ip_address))
                {
                    $sql = "insert into resource_ip(resource_id, ip) values($resource_id, '{$ip_address}')";

                    $this->Gatewaygroup->query($sql);
                }
            }
            $this->Client->commit();
            //            did assign
            $OrigLog_arr = array();
            if($this->params['form']['did_assign'])
            {
                $this->loadModel('did.DidAssign');
                $egress_id = $resource_id;
                $numbers = $this->params['form']['did_assign'];
                $product_id = $this->DidAssign->check_default_static();
                foreach ($numbers as $number)
                {
                    $resource = $this->Gatewaygroup->findByResourceId($egress_id);
                    $rate_table_id = $resource['Gatewaygroup']['rate_table_id'];
                    $billing_rule_id = $resource['Gatewaygroup']['billing_rule'];
                    $billing_rule = $this->DidBillingPlan->findById($billing_rule_id);
                    $min_price = $billing_rule['DidBillingPlan']['min_price'];

                    $sql = "INSERT INTO rate(rate_table_id, code, rate, min_time, interval, intra_rate, inter_rate) VALUES ($rate_table_id, '{$number}', $min_price, 6, 6, $min_price, $min_price)";
                    $this->Gatewaygroup->query($sql);

                    $item_id = $this->DidAssign->add_new_number($number, $product_id);
                    $this->DidAssign->add_new_resouce($item_id, $egress_id);
                    $this->DidAssign->add_assign($number, $egress_id);

                    $log_detail = "#{$number} assign => {$resource['Gatewaygroup']['alias']}";
                    $OrigLog_arr[] = array(
                        'module' => "Egress DID",
                        'update_by' => $_SESSION['sst_user_name'],
                        'type'  => 1,
                        'detail' => $log_detail
                    );
                }
            }
            
            $log_detail_arr = array();
            if (strcmp($name, $client['name']))
                $log_detail_arr[] = "Name[{$client['name']}] => {$name}";
            if (strcmp($billing_rule, $client['billing_rule']))
                $log_detail_arr[] = "Pricing Rule[{$routing_rules[$client['billing_rule']]}] => {$routing_rules[$billing_rule]}";
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

            if ($log_detail_arr)
            {
                $action = 2;
                $log_head = "Client name [{$name}] :";
                $log_detail = $log_head.implode("<br />", $log_detail_arr);
                $OrigLog_arr[] = array(
                    'module' => $log_head,
                    'update_by' => $_SESSION['sst_user_name'],
                    'type'  => $action,
                    'detail' => $log_detail
                );
                $this->OrigLog->saveAll($OrigLog_arr);
            }

            $this->Session->write('m', $this->Client->create_json(201, __('The  Client [' . $name . '] is modified successfully!', true)));

            $this->redirect('/did/clients/index');
        }


        $this->set("routing_rules", $this->Gatewaygroup->getBillingRules());

        $this->set('client', $client);
        //pr($client);
    }

    public function ajax_add_client($type = '')
    {
        Configure::write('debug', 0);
        if($type)
            $this->set("routing_rules", $this->Gatewaygroup->getBillingRules());
        $this->set('type',$type);
    }

    public function add($ajax = 0,$has_rate = '')
    {
        if ($ajax)
        {
            Configure::write('debug', 0);
            $this->autoLayout = false;
            $this->autoRender = false;
        }
        if ($this->RequestHandler->isPost())
        {
            $name = $_POST['name'];
            $login_username = $_POST['login_username'];
            $login_password = $_POST['login_password'];
            $ip_addresses = $_POST['ip_addresses'];
            $call_limit = $_POST['call_limit'];
            $media_type = $_POST['media_type'];
//            $t_38 = isset($_POST['t_38']) ? TRUE : FALSE;
//            $rfc2833 = isset($_POST['rfc2833']) ? 1 : 0;
            $auto_invocing = isset($_POST['auto_invoicing']) ? TRUE : FALSE;
            $billing_rule = $_POST['pricing_rule'];

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
                    'mode' => $_POST['data']['mode'],
                    'unlimited_credit' => $_POST['data']['unlimited_credit'],
                    'allowed_credit' => $_POST['data']['allowed_credit'],
                    'currency_id' => $currency_result[0][0]['currency_id'],
                    'is_panelaccess' => true,
                    'is_client_info' => true,
                    'is_invoices' => true,
                    'is_rateslist' => true,
                    'is_summaryreport' => true,
                    'is_cdrslist' => true,
                    'is_mutualsettlements' => true,
                    'is_changepassword' => true,
                    'client_type' => 1,
                    'auto_invoicing' => $auto_invocing,
                    'login' => !empty($login_username) ? $login_username : NULL,
                    'password' => !empty($login_password) ? $login_password : NULL,
                    'update_by' => $_SESSION['sst_user_name'],
                    'enough_balance' => true,
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

            if (!empty($login_username) and !empty($login_password))
            {
                $passord = md5($login_password);
                $sql = "insert into users (name,password,client_id,user_type) values('{$login_username}','{$passord}','{$client_id}','3')";
                $flg2 = $this->User->query($sql, false);
                if ($flg2 === false)
                {
                    $this->rollback = TRUE;
                    $this->Client->rollback();
                }
            }


            $rate_table_id = $this->Rate->create_ratetable($name, 'NULL', $currency_result[0][0]['currency_id'], 0, true, 2, true);
            if ($rate_table_id === false)
            {
                $this->rollback = TRUE;
                $this->Client->rollback();
            }
            // 创建 Egress

            $egress = array(
                'Gatewaygroup' => array(
                    'alias' => $name,
                    'client_id' => $client_id,
                    'media_type' => $media_type,
                    'capacity' => $call_limit,
//                    't38' => $t_38,
//                    'rfc2833' => $rfc2833,
                    'trunk_type2' => 1,
                    'egress' => true,
                    'billing_rule' => $billing_rule,
                    'rate_table_id' => $rate_table_id,
                    'enough_balance' => true
                )
            );

            $flg3 = $this->Gatewaygroup->save($egress);
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

            if (!$ajax || $has_rate)
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
            $this->Client->commit();

//            did assign
            $OrigLog_arr = array();
            if($this->params['form']['did_assign'] && !$this->rollback)
            {
                $this->loadModel('did.DidAssign');
                $egress_id = $resource_id;
                $numbers = $this->params['form']['did_assign'];
                $product_id = $this->DidAssign->check_default_static();
                foreach ($numbers as $number)
                {
                    $resource = $this->Gatewaygroup->findByResourceId($egress_id);
                    $rate_table_id = $resource['Gatewaygroup']['rate_table_id'];
                    $billing_rule_id = $resource['Gatewaygroup']['billing_rule'];
                    $billing_rule = $this->DidBillingPlan->findById($billing_rule_id);
                    $min_price = $billing_rule['DidBillingPlan']['min_price'];

                    $sql = "INSERT INTO rate(rate_table_id, code, rate, min_time, interval, intra_rate, inter_rate) VALUES ($rate_table_id, '{$number}', $min_price, 6, 6, $min_price, $min_price)";
                    $this->Gatewaygroup->query($sql);

                    $item_id = $this->DidAssign->add_new_number($number, $product_id);
                    $this->DidAssign->add_new_resouce($item_id, $egress_id);
                    $this->DidAssign->add_assign($number, $egress_id);

                    $log_detail = "#{$number} assign => {$resource['Gatewaygroup']['alias']}";
                    $OrigLog_arr[] = array(
                        'module' => "Egress DID",
                        'update_by' => $_SESSION['sst_user_name'],
                        'type'  => 1,
                        'detail' => $log_detail
                    );
                }
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
                    $log_detail = "Client name [{$name}]";
                    $this->OrigLog->add_orig_log("Client", $action, $log_detail);
                    echo 1;
                }
            }
            else
            {
                if ($this->rollback)
                {
                    $this->Session->write('m', $this->Client->create_json(101, __('The  Client [' . $name . '] is created failed!', true)));
                }
                else
                {
                    $action = 0;
                    $log_detail = "Client name [{$name}]";
                    $OrigLog_arr[] = array(
                        'module' => "Client",
                        'update_by' => $_SESSION['sst_user_name'],
                        'type'  => $action,
                        'detail' => $log_detail
                    );
                    $this->OrigLog->saveAll($OrigLog_arr);
                    $this->Session->write('m', $this->Client->create_json(201, __('The  Client [' . $name . '] is created successfully!', true)));
                }
                $this->redirect('/did/did_assign/create/'.base64_encode($resource_id));
            }
        }
        $this->set("routing_rules", $this->Gatewaygroup->getBillingRules());
    }

    public function disable()
    {
        $id = base64_decode($this->params['pass'][0]);
        $mesg_info = $this->Client->query("select name from client where client_id = {$id}");
        $this->Client->query("update client set  status=false where  client_id= $id;");
        $this->Client->query("update resource set  active=false where  client_id= $id;");
        $this->Client->create_json_array('', 201, __('The Client [' . $mesg_info[0][0]['name'] . '] is disabled successfully!', true));
        $this->Session->write("m", Client::set_validator());
        $this->redirect('/did/clients/index');
    }

    public function enable()
    {
        $id = base64_decode($this->params['pass'][0]);
        $mesg_info = $this->Client->query("select name from client where client_id = {$id}");
        $this->Client->active($id);
        $this->Client->query("update resource set  active=true where  client_id= $id;");
        $this->Client->create_json_array('', 201, __('The Client [' . $mesg_info[0][0]['name'] . '] is enabled successfully!', true));
        $this->Session->write("m", Client::set_validator());
        $this->redirect('/did/clients/index');
        ;
    }

    public function delete($client_id)
    {
        $client_id = base64_decode($client_id);
        $clientInfo = $this->Client->findByClientId($client_id);
        $sql = "select * from resource where client_id = $client_id";
        $resource = $this->Client->query($sql);
        $this->Client->begin();

        $delete_user_sql = "DELETE FROM users WHERE name = '{$resource[0][0]['alias']}'";
        if ($this->Client->query($delete_user_sql) === false)
        {
            $this->rollback = TRUE;
            $this->Client->rollback();
        }
        $delete_did_sql = "delete from did_assign where egress_id = {$resource[0][0]['resource_id']}";
        if ($this->Client->query($delete_did_sql) === false)
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
        $flg = $this->Client->query("delete from users_limit where client_id = {$client_id}");
        if ($flg === false)
        {
            $this->rollback = TRUE;
            $this->Client->rollback();
        }
        if ($this->Client->del($client_id, 'false') === false)
        {
            $this->rollback = TRUE;
            $this->Client->rollback();
        }
        $this->Client->commit();
        if ($this->rollback === false)
        {
            $this->Client->create_json_array('', 201, __('The Client [' . $clientInfo['Client']['name'] . '] is deleted successfully!', true));
        }
        else
        {
            $this->Client->create_json_array('', 101, __('The Client [' . $clientInfo['Client']['name'] . '] is deleted failed!', true));
        }
        $this->Session->write("m", Client::set_validator());
        $this->redirect('/did/clients/index');
    }

}
