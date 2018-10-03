<?php

class ResellersController extends AppController
{

    var $name = "Resellers";
    var $uses = array('Reseller', 'ResellerClient', 'Client', 'User', 'Gatewaygroup');
    var $components = array('RequestHandler');
    var $helpers = array('javascript', 'html', 'Common');

    public function beforeFilter()
    {
        if ($this->Session->check('login_type')) {
            $this->set('login_type', $this->Session->read('login_type'));
        }

        if ($this->Session->check('reseller_id')) {
            return TRUE;
        }
    }

    public function auth()
    {
        $this->checkSession("login_type");
        $login_type = $this->Session->read('login_type');
        if ($login_type == 1) {
            $this->Session->write('executable', true);
            $this->Session->write('writable', true);
        } else {
            $limit = $this->Session->read('sst_config_CodeDeck');
            $this->Session->write('executable', $limit['executable']);
            $this->Session->write('writable', $limit['writable']);
        }
        parent::beforeFilter();
    }

    public function index()
    {
        $this->auth();
        $this->pageTitle = "Dialer Management/Reseller Management";
        $this->paginate = array(
            'limit' => 100,
            'order' => array(
                'name' => 'asc',
            ),
        );
        $this->data = $this->paginate('Reseller');
    }

    public function action_edit_panel($id = null)
    {
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        if ($this->isPost()) {
            if ($id != null) {
                $this->data['Reseller']['id'] = $id;
                $this->Session->write('m', $this->Reseller->create_json(201, __('The Reseller [%s] is modified successfully!', true, $this->data['Reseller']['name'])));
            } else {
                $this->Session->write('m', $this->Reseller->create_json(201, __('The Reseller [%s] is created successfully!', true, $this->data['Reseller']['name'])));
            }
            $this->Reseller->save($this->data);
            $this->xredirect("/resellers/index");
        }
        $this->data = $this->Reseller->find('first', Array('conditions' => Array('id' => $id)));
        $this->set('id', $id);
    }

    public function client()
    {
        $this->pageTitle = "Dialer Management/Client Management";
        $this->paginate = array(
            'limit' => 100,
            'fields' => array('ResellerClient.id','ResellerClient.login_id', 'ResellerClient.reseller_id','ResellerClient.email', 'Client.client_id', 
                'Balance.balance', 'Client.status', 'User.last_login_time'),
            'order' => array(
                'login_id' => 'asc',
            ),
            'joins' => array(
                array(
                    'table' => 'c4_client_balance',
                    'alias' => "Balance",
                    'type' => 'LEFT',
                    'conditions' => array(
                        'Balance.client_id::integer = ResellerClient.client_id',
                    ),
                ),
                array(
                    'table' => 'client',
                    'alias' => "Client",
                    'type' => 'LEFT',
                    'conditions' => array(
                        'Client.client_id = ResellerClient.client_id',
                    ),
                ),
                array(
                    'table' => 'users',
                    'alias' => "User",
                    'type' => 'LEFT',
                    'conditions' => array(
                        'User.client_id = ResellerClient.client_id',
                    ),
                ),
            ),
        );
        $resellers = $this->Reseller->find('list', array('fields' => array('id', 'name')));
        
        $login_type = $this->Session->read('login_type');
        if ($login_type == 10)
            $this->paginate['conditions'] = array(
                'ResellerClient.reseller_id' => $this->Session->read('reseller_id'),
            );
        $this->data = $this->paginate('ResellerClient');
        $this->set('resellers', $resellers);
    }
    
    public function disable()
    {                    
        $id = $this->params['pass'][0];
        $client = $this->ResellerClient->findById($id);
        if (($this->Session->check('reseller_id') and $client['ResellerClient']['reseller_id'] == $this->Session->read('reseller_id')) or
                ($this->Session->check('login_type') and $this->Session->read('login_type') == 1))
        {
            $client_id = $client['ResellerClient']['client_id'];
            $mesg_info = $this->Client->query("select name from client where client_id = {$client_id}");
            $this->Client->query("update client set  status=false where  client_id= $client_id;");
            $this->Client->query("update resource set  active=false where  client_id= $client_id;");
            $this->Client->create_json_array('', 201,  __('The Client [%s] is disabled successfully!', true, $mesg_info[0][0]['name']));
            $this->Session->write("m", Client::set_validator());
        }
        $this->redirect('/resellers/client');
    }

    public function enable()
    {
        $id = $this->params['pass'][0];
        $client = $this->ResellerClient->findById($id);
        if (($this->Session->check('reseller_id') and $client['ResellerClient']['reseller_id'] == $this->Session->read('reseller_id')) or
                ($this->Session->check('login_type') and $this->Session->read('login_type') == 1))
        {
            $client_id = $client['ResellerClient']['client_id'];
            $mesg_info = $this->Client->query("select name from client where client_id = {$client_id}");
            $this->Client->active($client_id);
            $this->Client->query("update resource set  active=true where  client_id= $client_id;");
            $this->Client->create_json_array('', 201, __('The Client [%s] is enabled successfully!', true, $mesg_info[0][0]['name']));
            $this->Session->write("m", Client::set_validator());
        }
        $this->redirect('/resellers/client');;
    }

    public function client_action_edit_panel($id = null)
    {
        Configure::write('debug', 0);
        Configure::load("myconf");
        $login_type = $this->Session->read('login_type');
        $this->layout = 'ajax';
        if ($this->isPost()) {
            if ($id != null) {

                $this->data['ResellerClient']['id'] = $id;
                $reseller = $this->ResellerClient->findById($id);
                $login_username = $this->data['ResellerClient']['login_id'];
                $login_password = $this->data['ResellerClient']['password'];
                $client_id = $reseller['ResellerClient']['client_id'];

                $count = $this->ResellerClient->find('count', array(
                    'conditions' => array(
                        'login_id' => $login_username,
                        'id != ' => $id,
                    )
                ));

                if ($count > 0) {
                    $this->Session->write('m', $this->ResellerClient->create_json(101, __('The Client [%s] already exists!', true, $this->data['ResellerClient']['login_id'])));

                    $this->xredirect("/resellers/client");

                }

                $clientInfo = $this->Client->findByClientId($client_id);
                $userInfo = $this->User->findByClientId($client_id);
                if ($login_password != $userInfo['User']['password']) {
                    $clientInfo['Client']['password'] = $login_password;
                }
                $clientInfo['Client']['name'] = $login_username;
                $clientInfo['Client']['login'] = $login_username;
                $this->Client->save($clientInfo);

                $userInfo['User']['name'] = $login_username;
                if ($login_password != $userInfo['User']['password'])
                    $userInfo['User']['password'] = md5($login_password);
                $this->User->save($userInfo);

                $this->Session->write('m', $this->ResellerClient->create_json(201, __('The Client [%s] is modified successfully!', true, $this->data['ResellerClient']['login_id'])));
            } else {

                $count = $this->ResellerClient->find('count', array(
                    'conditions' => array(
                        'login_id' => $login_username,
                    )
                ));

                if ($count > 0) {
                    $this->Session->write('m', $this->ResellerClient->create_json(101, __('The Client [%s] already exists!', true, $this->data['ResellerClient']['login_id'])));

                    $this->xredirect("/resellers/client");

                }

                $login_username = $this->data['ResellerClient']['login_id'];
                $login_password = $this->data['ResellerClient']['password'];


                $client = array(
                    'Client' => array(
                        'name' => $login_username,
                        'mode' => 2,
                        'currency_id' => 1,
                        'is_panelaccess' => true,
                        'is_client_info' => true,
                        'is_invoices' => true,
                        'is_rateslist' => true,
                        'is_summaryreport' => true,
                        'is_cdrslist' => true,
                        'is_mutualsettlements' => true,
                        'is_changepassword' => true,
                        'client_type' => 1,
                        'auto_invoicing' => true,
                        'login' => $login_username,
                        'password' => $login_password,
                        'update_by' => $_SESSION['sst_user_name'],
                    )
                );

                // 检测是否存在相同client

                $this->Client->save($client);
                $client_id = $this->Client->getLastInsertID();

                $prefix = $this->ResellerClient->generate_prefix();
                $this->data['ResellerClient']['prefix'] = $prefix;
                $this->data['ResellerClient']['client_id'] = $client_id;

                // 创建Balance
                $this->Client->clientBalanceOperation($client_id, 1, 0);
                $user = array(
                    'User' => array(
                        'name' => $login_username,
                        'password' => md5($login_password),
                        'client_id' => $client_id,
                        'user_type' => 3,
                    )
                );

                $this->User->save($user);

                $static_id = $this->ResellerClient->check_default_static();
                $route_id  = $this->ResellerClient->check_default_route();

                $egress = array(
                    'Gatewaygroup' => array(
                        'alias' => $login_username,
                        'client_id' => $client_id,
                        'egress' => true,
                        'trunk_type2' => 0,
                    )
                );

                $this->Gatewaygroup->save($egress);
                $resource_id = $this->Gatewaygroup->getLastInsertID();



                $sql = "insert into resource_prefix(resource_id, tech_prefix, route_strategy_id, rate_table_id) values($resource_id, '$prefix', $route_id, $static_id)";
                $this->Gatewaygroup->query($sql);

                $ip_address = Configure::read('host_dialer.freeswitch_ip');
                $port = Configure::read('host_dialer.freeswitch_port');
                $sql = "insert into resource_ip(resource_id, ip, port) values($resource_id, '{$ip_address}', $port)";
                $this->ResellerClient->query($sql);


                $ingress = array(
                    'Gatewaygroup' => array(
                        'alias' => $login_username,
                        'client_id' => $client_id,
                        'ingress' => true,
                        'trunk_type2' => 1,
                    )
                );

                $this->Gatewaygroup->save($ingress);
                $resource_id = $this->Gatewaygroup->getLastInsertID();
                $sql = "insert into resource_ip(resource_id, ip, port) values($resource_id, '{$ip_address}', $port)";
                $this->ResellerClient->query($sql);

                if ($login_type == 10) {
                    $this->data['ResellerClient']['reseller_id'] = $this->Session->read('reseller_id');
                }

                $this->Session->write('m', $this->ResellerClient->create_json(201, __('The Client [%s] is created successfully!', true, $this->data['ResellerClient']['login_id'])));
            }
            $this->ResellerClient->save($this->data);
            $this->xredirect("/resellers/client");
        }
        $resellers = $this->Reseller->find('list', array('fields' => array('id', 'name')));
        $this->set('resellers', $resellers);
        $this->data = $this->ResellerClient->find('first', Array('conditions' => Array('id' => $id)));
        $this->set('id', $id);
    }

}