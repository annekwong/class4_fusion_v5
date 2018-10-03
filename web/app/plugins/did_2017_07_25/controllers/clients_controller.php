<?php

class ClientsController extends DidAppController
{

    var $name = 'Clients';
    var $uses = array('Client', 'User', 'prresource.Gatewaygroup', 'Rate', 'did.DidBillingPlan','did.OrigLog', 'did.Did');
    var $components = array('RequestHandler');
    var $helpers = array('javascript', 'html', 'Common');
    var $rollback = false;

    public function index()
    {
        $this->pageTitle = "Origination/Clients";
        $pageSize = isset($this->params['url']['size']) ? $this->params['url']['size'] : 100;
        $search = isset($this->params['url']['search']) ? $this->params['url']['search'] : '';
        $conditions = array('Client.client_type' => 1);

        if($search != '') {
            $conditions = array(
                'Client.client_type' => 1,
                'Client.name like' => "%$search%"
            );
        }
        $this->paginate = array(
            'limit' => $pageSize,
            'fields' => array('Client.client_id', 'Client.name', 'Client.mode', 'Client.unlimited_credit', 'Client.allowed_credit', 'Balance.balance', 'Client.update_at', 'Client.update_by', 'Client.status', 'Resource.billing_rule', 'Resource.resource_id'),
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
                        'Resource.client_id = Client.client_id',
                        'Resource.alias NOT LIKE \'%_' . base64_encode('DID') . '_%\''
                    ),
                )
            ),
            'conditions' => $conditions,
        );

        $this->data = $this->paginate('Client');

        if (empty($this->data) && $search == '')
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
        Configure::write('debug', 2);

        $client_id = base64_decode($client_id);
        $client = array();
        $clientInfo = $this->Client->findByClientId($client_id);
        $client['client_id'] = $clientInfo['Client']['client_id'];
        $client['name'] = $clientInfo['Client']['name'];
        $client['company'] = $clientInfo['Client']['company'];
        $client['email'] = $clientInfo['Client']['email'];
        $client['noc_email'] = $clientInfo['Client']['noc_email'];
        $client['billing_email'] = $clientInfo['Client']['billing_email'];
        $client['address'] = $clientInfo['Client']['address'];
        $client['mode'] = $clientInfo['Client']['mode'];
        $client['allowed_credit'] =  -abs($clientInfo['Client']['allowed_credit']);
        $client['unlimited_credit'] = $clientInfo['Client']['unlimited_credit'];
        $client['auto_invoicing'] = $clientInfo['Client']['auto_invoicing'];
        $userInfo = $this->User->find('first', array(
            'conditions' => array(
                'client_id' => intval($client_id)
            )
        ));
        $client['login_username'] = $userInfo['User']['name'];
        $resourceInfo = $this->Gatewaygroup->find('first', Array('conditions' => Array('client_id' => $client_id),'order'=>Array('resource_id'=>'asc')));
        $client['t38'] = $resourceInfo['Gatewaygroup']['t38'];
        $client['call_limit'] = $resourceInfo['Gatewaygroup']['capacity'];
        $client['media_type'] = $resourceInfo['Gatewaygroup']['media_type'];
        $client['billing_rule'] = $resourceInfo['Gatewaygroup']['billing_rule'];
        $client['price_per_max_channel'] = $resourceInfo['Gatewaygroup']['price_per_max_channel'];
        $client['billing_port_type'] = isset($resourceInfo['Gatewaygroup']['billing_port_type']) ? $resourceInfo['Gatewaygroup']['billing_port_type'] : '';
        $client['cost_per_port'] = isset($resourceInfo['Gatewaygroup']['cost_per_port']) ? $resourceInfo['Gatewaygroup']['cost_per_port'] : '';
        $client['resource_ips'] = array();
        $client['resource_ports'] = array();



        if ($resourceInfo['Gatewaygroup']['resource_id'])
        {
            $resource_ips_result = $this->Client->query("select ip,port from resource_ip where resource_id = {$resourceInfo['Gatewaygroup']['resource_id']}");
            foreach ($resource_ips_result as $resource_ip)
            {
                array_push($client['resource_ips'], $resource_ip[0]['ip']);
                array_push($client['resource_ports'], $resource_ip[0]['port']);
            }
        }
        $routing_rules = $this->Gatewaygroup->getBillingRules();
        $this->set("routing_rules", $routing_rules);
        if ($this->RequestHandler->isPost())
        {
            $t38 = $_POST['t38'];
            $name = $_POST['name'];
            $login_username = $_POST['login_username'];
            $login_password = $_POST['login_password'];
            $ip_addresses = $_POST['ip_addresses'];
            $ip_port = $_POST['ip_port'];
            $call_limit = $_POST['call_limit'];
            $price_per_max_channel = $_POST['price_per_max_channel'];
            $media_type = $_POST['media_type'];
            $billing_port_type = $_POST['billing_port_type'];
            $cost_per_port = $_POST['cost_per_port'];
            $auto_invocing = isset($_POST['auto_invoicing']) ? TRUE : FALSE;
            $billing_rule = isset($_POST['pricing_rule']) ? $_POST['pricing_rule'] : '';
            $clientInfo['Client']['name'] = $name;
            $clientInfo['Client']['company'] = $_POST['company'];
            $clientInfo['Client']['email'] = $_POST['email'];
            $clientInfo['Client']['noc_email'] = $_POST['noc_email'];
            $clientInfo['Client']['billing_email'] = $_POST['billing_email'];
            $clientInfo['Client']['address'] = $_POST['address'];
            $clientInfo['Client']['login'] = $login_username ? $login_username : NULL;
            $clientInfo['Client']['auto_invoicing'] = $auto_invocing;
            $clientInfo['Client']['update_at'] = date("Y-m-d H:i:s");
            $clientInfo['Client']['update_by'] = $_SESSION['sst_user_name'];
            $clientInfo['Client']['mode'] = $_POST['data']['mode'];
            $clientInfo['Client']['allowed_credit'] = -abs($_POST['data']['allowed_credit']);
            $clientInfo['Client']['unlimited_credit'] = $_POST['data']['unlimited_credit'];
            $clientInfo['Client']['call_limit'] = $call_limit;

            foreach($_POST['data'] as $k => $v){
                if($k == 'mode' || $k == 'unlimited_credit' || $k == 'allowed_credit') continue;

                $clientInfo['Client'][$k] = $v;
            }
            $this->Client->begin();
            if ($login_password && (md5($login_password) != $userInfo['User']['password']))
                $clientInfo['Client']['password'] = $login_password;

            $flg = $this->Client->save($clientInfo);

            if ($flg === false)
            {
                $this->Client->rollback();
                $this->Session->write('m', $this->Client->create_json(101, 'Update client failed!'));
                $this->xredirect('/did/clients/edit/' . base64_encode($client_id));
            }

//            $flg1 = $this->Client->clientBalanceOperation($client_id, $_POST['data']['allowed_credit'], 1);
//            if ($flg1 === false)
//            {
//                $this->Session->write('m', $this->Client->create_json(101, __('Failed to save the Client Balance for [%s] !', true, $name)));
//                $this->rollback = TRUE;
//                $this->Client->rollback();
//                $this->Session->write('m', $this->Client->create_json(101, 'Update client balance failed!'));
//                $this->xredirect('/did/clients/edit/' . base64_encode($client_id));
//            }

            if (empty($userInfo))
            {
                if ($login_password) {
                    $possword = $login_password ? md5($login_password) : '';
                    $sql = "insert into users (name,password,client_id,user_type) values('$login_username','$possword','$client_id',3)";
                    $flg1 = $this->User->query($sql, false);

                    if ($flg1 === false) {
                        $this->Client->rollback();
                        $this->Session->write('m', $this->Client->create_json(101, 'Update user failed!'));
                        $this->xredirect('/did/clients/edit/' . base64_encode($client_id));
                    }
                }
            }
            else
            {
                if ($login_password && (md5($login_password) != $userInfo['User']['password']))
                    $userInfo['User']['password'] = md5($login_password);

                $flg2 = $this->User->save($userInfo);

                if ($flg2 === false)
                {
                    $this->Client->rollback();
                    $this->Session->write('m', $this->Client->create_json(101, 'Update user failed!'));
                    $this->xredirect('/did/clients/edit/' . base64_encode($client_id));
                }
            }
            $resourceInfo['Gatewaygroup']['t38'] = $t38;
            $resourceInfo['Gatewaygroup']['alias'] = $name;
            $resourceInfo['Gatewaygroup']['media_type'] = $media_type;
//            $resourceInfo['Gatewaygroup']['capacity'] = $call_limit;
            $resourceInfo['Gatewaygroup']['billing_port_type'] = $billing_port_type;
            $resourceInfo['Gatewaygroup']['cost_per_port'] = $cost_per_port;
            $resourceInfo['Gatewaygroup']['price_per_max_channel'] = $price_per_max_channel;
            $resourceInfo['Gatewaygroup']['billing_rule'] = $billing_rule;
            $flg3 = $this->Gatewaygroup->save($resourceInfo);
            if ($flg3 === false)
            {
                $this->Client->rollback();
                $this->Session->write('m', $this->Client->create_json(101, 'Update resource failed!'));
                $this->xredirect('/did/clients/edit/' . base64_encode($client_id));
            }

            $resource_id = $resourceInfo['Gatewaygroup']['resource_id'];
            $sql = "update resource set t38 = 't' where client_id = {$client_id}";
            $this->Gatewaygroup->query($sql);
            $sql = "delete from resource_ip where resource_id = {$resource_id}";
            $flg4 = $this->Gatewaygroup->query($sql);
            if ($flg4 === false)
            {
                $this->Client->rollback();
                $this->Session->write('m', $this->Client->create_json(101, 'Delete resource IPs failed!'));
                $this->xredirect('/did/clients/edit/' . base64_encode($client_id));
            }


            foreach ($ip_addresses as $ip_key =>$ip_address)
            {
                if (!empty($ip_address))
                {
                    $sql = "insert into resource_ip(resource_id, ip,port) values($resource_id, '{$ip_address}',$ip_port[$ip_key])";
                    $this->Gatewaygroup->query($sql);
                }
            }

            // Update REIE egresses of the client
            $sql = "SELECT resource_id FROM resource WHERE client_id='{$client_id}' AND egress=true AND resource_id<>'{$resource_id}'";
            $res = $this->Gatewaygroup->query($sql);
            if(!empty($res)) {
                foreach ($res as $reie_res){
                    $res_id = $reie_res[0]['resource_id'];
                    $sql = "delete from resource_ip where resource_id = {$res_id}";
                    $this->Gatewaygroup->query($sql);
                    foreach ($ip_addresses as $ip_key => $ip_address) {
                        if (!empty($ip_address) && $res_id) {
                            $sql = "insert into resource_ip(resource_id, ip,port) values($res_id, '{$ip_address}',$ip_port[$ip_key])";
                            $this->Gatewaygroup->query($sql);
                        }
                    }
                }
            }

            $this->Client->commit();

            $log_detail_arr = array();
            if (strcmp($name, $client['name']))
                $log_detail_arr[] = "Name[{$client['name']}] => {$name}";
//            if (strcmp($billing_rule, $client['billing_rule']))
//                $log_detail_arr[] = "Pricing Rule[{$routing_rules[$client['billing_rule']]}] => {$routing_rules[$billing_rule]}";
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

            $this->Session->write('m', $this->Client->create_json(201, __('The  Client [%s] is modified successfully!', true, $name)));

            $this->redirect('/did/clients/index');
        }

        $this->set('client', $client);
        $this->set('client_permission', $clientInfo['Client']);
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
            $ip_port = $_POST['ip_port'];
            $call_limit = $_POST['call_limit'];
            $billing_port_type = $_POST['billing_port_type'];
            $price_per_max_channel = $_POST['price_per_max_channel'];
            $media_type = $_POST['media_type'];
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
                    $this->Session->write('m', $this->Client->create_json(101, __('Carrency is empty!', true)));
                    $this->Client->rollback();
                }
            }

            $client = array(
                'Client' => array(
                    'name' => $name,
                    'company' => $_POST['company'],
                    'address' => $_POST['address'],
                    'email' => $_POST['email'],
                    'noc_email' => $_POST['noc_email'],
                    'billing_email' => $_POST['billing_email'],
                    'mode' => $_POST['data']['mode'],
                    'unlimited_credit' => $_POST['data']['unlimited_credit'],
                    'allowed_credit' => -abs($_POST['data']['allowed_credit']),
                    'currency_id' => $currency_result[0][0]['currency_id'],
                    'is_panelaccess' => true,
                    /*'is_client_info' => true,
                    'is_invoices' => true,
                    'is_rateslist' => true,
                    'is_summaryreport' => true,
                    'is_cdrslist' => true,
                    'is_mutualsettlements' => true,
                    'is_changepassword' => true,*/
                    'is_panel_didrequest' => true,
                    'is_panel_mydid' => true,
                    'client_type' => 1,
                    'auto_invoicing' => $auto_invocing,
                    'login' => !empty($login_username) ? $login_username : NULL,
                    'password' => !empty($login_password) ? $login_password : NULL,
                    'update_by' => $_SESSION['sst_user_name'],
                    'enough_balance' => true,
                    'call_limit' => $call_limit
                )
            );

            foreach($_POST['data'] as $k => $v){
                if($k == 'mode' || $k == 'unlimited_credit' || $k == 'allowed_credit') continue;

                $client['Client'][$k] = $v;
            }

            // 检测是否存在相同client
            $flg = $this->Client->save($client);
            if ($flg === false)
            {
                $this->Session->write('m', $this->Client->create_json(101, __('Failed to create the Client [%s] !', true, $name)));
                $this->rollback = TRUE;
                $this->Client->rollback();
            }
            $client_id = $this->Client->getLastInsertID();
            // 创建Balance
//            $flg1 = $this->Client->clientBalanceOperation($client_id, $_POST['data']['allowed_credit'], 0);
//            if ($flg1 === false)
//            {
//                $this->Session->write('m', $this->Client->create_json(101, __('Failed to save the Client Balance for [%s] !', true, $name)));
//                $this->rollback = TRUE;
//                $this->Client->rollback();
//            }

            if (!empty($login_username) and !empty($login_password)) {
                $countUsers = $this->User->find('count', array(
                    'conditions' => array(
                        'name' => $login_username
                    )
                ));

                if ($countUsers == 0) {
                    $passord = md5($login_password);
                    $sql = "insert into users (name,password,client_id,user_type) values('{$login_username}','{$passord}','{$client_id}','3')";
                    $flg2 = $this->User->query($sql);
                    if ($flg2 === false) {
                        $this->rollback = TRUE;
                        $this->Client->rollback();
                    }
                } else {
                    $this->rollback = TRUE;
                    $this->Client->rollback();
                    $this->Session->write('m', $this->Client->create_json(101, "Username [{$login_username}] already in use"));
                    $this->redirect('/did/clients/add');
                }
            }
            /*  CLAS-4079
            if ($this->Rate->is_exists_name($name)) {
                $this->Client->rollback();
                $this->Session->write('m', $this->Client->create_json(101, __('Rate table [%s] already exists!', true, $name)));
                $this->redirect('/did/clients');
            }
            $rate_table_id = $this->Rate->create_ratetable($name, 'NULL', $currency_result[0][0]['currency_id'], 0, true, 2, true);
            if ($rate_table_id === false)
            {
                $this->rollback = TRUE;
                $this->Client->rollback();
            }
            */
            // 创建 Egress

            $cost_per_port = $billing_port_type == 0 ? '0' : $cost_per_port;

            $sql = "SELECT route_strategy_id from route_strategy where name = 'ORIGINATION_ROUTING_PLAN'";
            $result = $this->Gatewaygroup->query($sql);
            if (empty($result))
            {
                $sql = "insert into route_strategy (name) values ('ORIGINATION_ROUTING_PLAN') returning route_strategy_id";
                $flg4 = $result = $this->Gatewaygroup->query($sql);
                if ($flg4 === false)
                {
                    $this->rollback = TRUE;
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
            $egress = array(
                'Gatewaygroup' => array(
                    'alias' => $name,
                    'client_id' => $client_id,
                    'media_type' => $media_type,
//                    'capacity' => $call_limit,
                    'trunk_type2' => 1,
                    'egress' => true,
                    'enough_balance' => true,
                    'price_per_max_channel' => $price_per_max_channel,
                    'billing_port_type' => $billing_port_type,
                    'billing_rule' => $billing_rule,
                    'route_strategy_id' => $result[0][0]['route_strategy_id'],
                    't38' => isset($_POST['t_38']) ? $_POST['t_38'] : true
                )
            );

            $flg3 = $this->Gatewaygroup->save($egress);
            if ($flg3 === false)
            {
                $this->rollback = TRUE;
                $this->Client->rollback();
            }
            $resource_id = $this->Gatewaygroup->getLastInsertID();

            foreach ($ip_addresses as $ip_key=>$ip_address)
            {
                if (!empty($ip_address))
                {
                    $sql = "insert into resource_ip(resource_id, ip,port) values($resource_id, '{$ip_address}',$ip_port[$ip_key])";
                    $this->Gatewaygroup->query($sql);
                }
            }

//            if (!$ajax || $has_rate)
//            {
//                $billing_rule = $this->DidBillingPlan->findById($billing_rule);
//                $min_price = $billing_rule['DidBillingPlan']['min_price'];
//                $rate_insert_flg = $this->save_did_rate($rate_table_id,$min_price);
//                if($rate_insert_flg === false)
//                {
//                    $this->rollback = TRUE;
//                    $this->Client->rollback();
//                }
//            }
            $this->Client->commit();
            if ($ajax)
            {
                if ($this->rollback)
                    echo 0;
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
                if ($this->rollback){
                    $this->Session->write('m', $this->Client->create_json(101, __('Failed to create the Client [%s] !', true, $name)));
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
                    $this->Session->write('m', $this->Client->create_json(201, __('The  Client [%s] is created successfully!', true, $name)));
                }
                $this->redirect('/did/clients');
            }
        }
        $this->set("routing_rules", $this->Gatewaygroup->getBillingRules());
    }

    private function check_if_exists($ip_addresses, $resource_id = false){
        $ip_addresses_str = "'" . implode("','",$ip_addresses) . "'";
        $sql = "SELECT resource_ip.resource_id, resource.alias, resource_ip.ip  from resource_ip left join resource on resource.resource_id = resource_ip.resource_id 
                left join client on client.client_id = resource.client_id where ip in ($ip_addresses_str) AND client.client_type = 1";

        if($resource_id){
            $sql .= " AND resource.client_id <> (SELECT client_id FROM resource WHERE resource_id = $resource_id)";
        }
        $result = $this->Gatewaygroup->query($sql);

        if(!empty($result)){
            return $result[0][0];
        }
        return false;
    }

    public function disable()
    {
        $id = base64_decode($this->params['pass'][0]);
        $mesg_info = $this->Client->query("select name from client where client_id = {$id}");
        $this->Client->query("update client set  status=false where  client_id= $id;");
        $this->Client->query("update resource set  active=false where  client_id= $id;");
        $this->Client->create_json_array('', 201, __('The Client [%s] is disabled successfully!', true, $mesg_info[0][0]['name']));
        $this->Session->write("m", Client::set_validator());
        $this->redirect('/did/clients/index');
    }

    public function enable()
    {
        $id = base64_decode($this->params['pass'][0]);
        $mesg_info = $this->Client->query("select name from client where client_id = {$id}");
        $this->Client->active($id);
        $this->Client->query("update resource set  active=true where  client_id= $id;");
        $this->Client->create_json_array('', 201, __('The Client [%s] is enabled successfully!', true, $mesg_info[0][0]['name']));
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

        $delete_user_sql = "DELETE FROM users WHERE client_id = {$client_id}";
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
        $delete_resource_sql = "delete from resource where client_id = {$client_id}";
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
            $this->Client->create_json_array('', 201, __('The Client [%s] is deleted successfully!', true, $clientInfo['Client']['name']));
            $action = 1;
            $log_detail = "Client name [{$clientInfo['Client']['name']}]";
            $this->OrigLog->add_orig_log("Client", $action, $log_detail);
        }
        else
        {
            $this->Client->create_json_array('', 101, __('The Client [%s] is deleted failed!', true, $clientInfo['Client']['name']));
        }
        $this->Session->write("m", Client::set_validator());
        $this->redirect('/did/clients/index');
    }

    public function deleteSelected()
    {
        if ($this->RequestHandler->isPost()) {
            Configure::write('debug', 0);
            $this->autoRender = false;
            $this->layout = false;

            $selected = $_POST['selected'];
            $return = array('status' => 1);

            $this->Did->begin();

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
