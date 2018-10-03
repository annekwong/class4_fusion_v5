
<?php

class AgentController extends AppController
{

    var $name = 'Agent';
    var $helpers = array('javascript', 'html', 'AppClients', 'Common');
    var $uses = array("prresource.Gatewaygroup","Agent", "Client",'User','AgentClients','AgentCommissionHistory');


    function index()
    {
        $this->redirect('management');
    }

    function init()
    {
        $this->set('frequency_type',$this->Agent->get_frequency_type());
        $this->set('method_type',$this->Agent->get_method_type());
    }

    function management()
    {
        $this->pageTitle = __('Agent Management',true);
        $this->init();
        $conditions = array(
        );
        $get_id= $this->_get('id');
        if($get_id)
            $conditions = "Agent.agent_id='$get_id'";
        $pageSize = $this->_get('size') ? $this->_get('size') : 100;

        $order_arr = array('agent_name' => 'asc');
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
            ),
            'limit' => $pageSize,
            'order' => $order_arr,
            'conditions' => $conditions,
        );
        $this->data = $this->paginate('Agent');
        $data = $this->Agent->findAll();

        $this->set('data', $data);
        if(empty($this->data) && !$get_id)
        {
            $model_name = "Agent";
            $msg = "Agent";
            $add_url = "save_agent";
            $this->to_add_page($model_name, $msg, $add_url);
        }
    }

    public function get_product_list($agent_id = ''){
        Configure::write('debug', 0);
        $this->autoLayout = false;
        $this->loadModel('ProductRouteRateTable');
        $this->loadModel('ProductAgentsRef');

        $products = $this->ProductRouteRateTable->get_product(true);

        $res = $this->ProductAgentsRef->findAllByAgentId($agent_id);
        $assigned_products = array();
        foreach($res as $item){
            $assigned_products[] = $item['ProductAgentsRef']['product_id'];
        }
        $this->set('products', $products);
        $this->set('assigned_products', $assigned_products);
        $this->set('agent_id', $agent_id);
        $this->jsonResponse(['status' => true, 'data' => $this->render()]);

    }

    public function assign_product(){
        Configure::write('debug', 0);
        $this->autoLayout = false;
        $this->autoRender = false;
        $this->loadModel('ProductAgentsRef');
        $this->loadModel('ProductClientsRef');
        $products = isset($this->params['form']['products']) ? array_filter($this->params['form']['products']) : [];
        $agent_id = $this->params['form']['agent_id'];

        $res = $this->Agent->query("SELECT agent_name FROM agent WHERE agent_id='{$agent_id}'");
        $agent_name =  $res[0][0]['agent_name'];

        // remove previous assignments
        $this->ProductAgentsRef->query("DELETE FROM product_agents_ref WHERE agent_id='{$agent_id}'");

        if(!empty($products)){

            $this->loadModel('AgentClients');
            foreach($products as $product){
                $data[] = [
                    'product_id' => $product,
                    'agent_id'   => $agent_id
                ];
            }
            $this->ProductAgentsRef->saveAll($data);
            $agent_client_ids = $this->AgentClients->getClientByAgents([$agent_id]);
            $agent_client_ids = array_unique(array_filter($agent_client_ids));
            $data = [];
            foreach($agent_client_ids as $client_id) {
                foreach ($products as $product) {
                    $data[] = [
                        'product_id' => $product,
                        'client_id' => $client_id
                    ];
                }
            }

            $this->ProductClientsRef->saveAll($data);
        }

        $this->jsonResponse(['status' => true, 'msg' => __('Products assigned to the agent [' . $agent_name . '] successfully!', true)]);
    }

    public function save_agent($encode_agent_id = '')
    {
        if ($encode_agent_id)
            $this->pageTitle = __('Edit Agent Management', true);
        else
            $this->pageTitle = __('Add Agent Management', true);
        $this->init();
        if ($this->RequestHandler->ispost()) {
            $agent = $this->data;

            $agent['update_by'] = $_SESSION['sst_user_name'];
            $agent['update_on'] = date('Y-m-d H:i:sO');
            if ($encode_agent_id)
                $agent['agent_id'] = base64_decode($encode_agent_id);
            else
                $agent['create_on'] = date('Y-m-d H:i:sO');
            $login_username = $agent['login_username'];
            $login_password = $agent['login_password'];
            $user_id = intval($agent['user_id']);
            if ($login_username && $login_password) {
                $count = $this->User->find('count', array('conditions' => array(
                    'name' => $login_username,
                    'user_id != ?' => $user_id,
                )));

                if ($count) {
                    $this->Session->write('m', $this->Agent->create_json(101, __('This name is already taken', true)));
                    $this->redirect('save_agent/' . $encode_agent_id);
                }
                $user_arr = array(
                    'name' => $login_username,
                    'password' => md5($login_password),
                    'active' => true,
                    'email' => $agent['email'],
                    'user_type' => 2,
                    'create_user_id' => $_SESSION['sst_user_id'],
                    'user_id' => $user_id
                );

                if ($this->User->save($user_arr) === false) {
                    $this->Session->write('m', $this->Agent->create_json(101, __('User create failed', true)));
                    $this->redirect('save_agent/' . $encode_agent_id);
                }
                $agent['user_id']  = isset($user_id) && $user_id  ? $user_id : $this->User->getLastInsertID();

            } else {
                $count = $this->Agent->find('count', array('conditions' => array(
                    'agent_name' => $agent['agent_name'],
                    'agent_id !=' => $agent['agent_id']
                )));
                if ($count) {
                    $this->Session->write('m', $this->Agent->create_json(101, __('This Agent Name is already taken', true)));
                    $this->redirect('save_agent/' . $encode_agent_id);
                }
            }
            unset($agent['login_username']);
            unset($agent['login_password']);
            $flg = false;
            if($agent['user_id']) {
                $flg = $this->Agent->save($agent);
            }
            if ($flg === false) {
                $this->User->delete($agent['user_id']);
                $this->Session->write('m', $this->Agent->create_json(101, __('Save Failed!', true)));
                $this->redirect('save_agent/' . $encode_agent_id);
            }
            if ($encode_agent_id) {
                $action = 'modified';
            } else {
                $action = 'created';
            }
            $this->Session->write('m', $this->Agent->create_json(201, __('The %s [%s] is ' . $action . ' successfully!', true, array(__('agent', true), $agent['agent_name']))));
            $this->redirect('management');
        }
        if ($encode_agent_id) {
            $agent_id = base64_decode($encode_agent_id);
            $agent_info = $this->Agent->find('first', array(
                'conditions' => array(
                    'agent_id' => $agent_id
                )
            ));
            $this->data = $agent_info['Agent'];
            $user_id = $agent_info['Agent']['user_id'];
            if ($user_id) {
                $user_info = $this->User->find('first', array(
                    'fields' => array('name'),
                    'conditions' => array('user_id' => $user_id),
                ));
                $this->data['login_username'] = $user_info['User']['name'];
            }
            $this->set('encode_agent_id', $encode_agent_id);
        }
    }

    public function delete_agent($encode_agent_id)
    {
        $this->autoLayout = false;
        $this->autoRender = false;
        $id = base64_decode($encode_agent_id);
        $agent_info = $this->Agent->findByAgentId($id);
        $flg = $this->Agent->del($id);
        if ($flg === false)
            $this->Session->write('m', $this->Agent->create_json(101, __('Delete Failed!', true)));
        else
            $this->Session->write('m', $this->Agent->create_json(201, __('The %s [%s] is deleted successfully!', true,array(__('agent',true),$agent_info['Agent']['agent_name']))));
        $this->redirect('management');
    }


    public function agent_client($encode_agent_id = '')
    {
        Configure::write('debug', 2);
        $this->pageTitle = __('Client Assignment',true);
        $this->init();
        $conditions = array(
        );
        $get_id = $this->_get('id');
        if($get_id)
            $conditions[] = "Client.client_id='$get_id'";

        if($encode_agent_id){
            $conditions['AgentClients.agent_id'] = base64_decode($encode_agent_id);

            $this->set('currentAgent', $this->Agent->findByAgentId(base64_decode($encode_agent_id)));
        }

        $pageSize = $this->_get('size') ? $this->_get('size') : 100;

        $order_arr = array('AgentClients.id' => 'asc');
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
        $this->loadModel('Clients');
        $clients = $this->Clients->findAll();
        $this->set('clients', $clients);

        $signup_status = ['Waiting for Approve','Approved','Rejected'];
        $this->set('signup_status', $signup_status);

        $this->paginate = array(
            'fields' => array(
                'Agent.agent_name','Agent.commission','AgentClients.commission','AgentClients.update_on',
                'AgentClients.update_by','Client.name','AgentClients.id','Client.create_time','Signup.status','Signup.signup_time'
            ),
            'limit' => $pageSize,
            'order' => $order_arr,
            'joins' => array(
                array(
                    'alias' => 'Agent',
                    'table' => 'agent',
                    'type' => 'INNER',
                    'conditions' => array(
                        'Agent.agent_id = AgentClients.agent_id'
                    ),
                ),
                array(
                    'alias' => 'Client',
                    'table' => 'client',
                    'type' => 'INNER',
                    'conditions' => array(
                        'AgentClients.client_id = Client.client_id'
                    ),
                ),
                array(
                    'alias' => 'Signup',
                    'table' => 'signup',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'cast(AgentClients.agent_id as varchar(255)) = cast(Signup.agent_assoc_id as varchar(255)) AND Client.name=Signup.contact_name'
                    ),
                ),
            ),
            'conditions' => $conditions,
        );
        $this->data = $this->paginate('AgentClients');
    }

    public function assign_agent_client()
    {
        Configure::write('debug',0);
        $encode_agent_id = $this->_post('agent_id');
        $this->loadModel('Clients');
        $this->loadModel('AgentClient');
        $data = $this->Clients->find('all',array(
            'fields' => array(
                'Clients.name','Clients.client_id'
            ),
            'conditions' => array(
                'status' => 'true'
            ),
            'order' => array(
                'Clients.name'
            )
        ));

        $resData = array();

        foreach ($data as $item) {
            $tmpData = $this->AgentClient->find('first', array(
                'conditions' => array(
                    'client_id' => $item['Clients']['client_id']
                )
            ));
            if(empty($tmpData)) {
                array_push($resData, $item);
            }
        }

        $data = $resData;
        $this->set('method_type',$this->AgentClients->get_method_type());
        $this->set('agent_id',base64_decode($encode_agent_id));
        $this->set('agents',$this->Agent->find_agents());
        $this->set('client_data',$data);
    }

    public function assign_agent_client_handle()
    {
        $this->autoLayout = false;
        $this->autoRender = false;
        if(!$this->RequestHandler->ispost())
            $this->redirect('agent_client');
        $this->Agent->delByClient($this->params['form']['client']);

        $save_data = array(
            'agent_id' => $this->params['form']['agent'],
            'client_id' => $this->params['form']['client'],
            'update_on' => date('Y-m-d H:i:s'),
            'update_by' => $this->Session->read('sst_user_name'),
            'method_type' => (int) $this->params['form']['method_type']
        );
        $flg = $this->AgentClients->save($save_data);
        if ($flg === false)
            $this->Session->write('m', $this->Agent->create_json(101, __('Save Failed!', true)));
        else
            $this->Session->write('m', $this->Agent->create_json(201, __('Save successfully!', true)));
        if ($this->params['form']['agent_flg'])
            $this->redirect('agent_client/'.base64_encode($this->params['form']['agent_flg']));
        else
            $this->redirect('agent_client');
    }

    public function delete_agent_client($encode_agent_client_id,$encode_agent_id = '')
    {
        $this->autoLayout = false;
        $this->autoRender = false;
        $agent_client_id = base64_decode($encode_agent_client_id);
        $flg = $this->AgentClients->del($agent_client_id);
        if ($flg === false)
            $this->Session->write('m', $this->AgentClients->create_json(101, __('Delete Failed!', true)));
        else
            $this->Session->write('m', $this->AgentClients->create_json(201, __('Delete successfully!', true)));
        $this->redirect('agent_client/'. $encode_agent_id);
    }

    public function active($encode_agent_id)
    {
        $this->autoRender = false;
        $this->autoLayout = false;
        if (!$encode_agent_id)
        {
            $this->Session->write('m', $this->Agent->create_json(101, __('Failed!', true)));
            $this->redirect('index');
        }
        $flg = $this->Agent->change_status_agent(base64_decode($encode_agent_id),'true');
        if ($flg === false)
            $this->Session->write('m', $this->Agent->create_json(101, __('Active Failed!', true)));
        else
            $this->Session->write('m', $this->Agent->create_json(201, __('Activated successfully!', true)));
        $this->redirect('index');
    }

    public function dis_able($encode_agent_id)
    {
        $this->autoRender = false;
        $this->autoLayout = false;
        if (!$encode_agent_id)
        {
            $this->Session->write('m', $this->Agent->create_json(101, __('Failed!', true)));
            $this->redirect('index');
        }
        $flg = $this->Agent->change_status_agent(base64_decode($encode_agent_id),'false');
        if ($flg === false)
            $this->Session->write('m', $this->Agent->create_json(101, __('Inactive Failed!', true)));
        else
            $this->Session->write('m', $this->Agent->create_json(201, __('Deactivated successfully!', true)));
        $this->redirect('index');
    }



    public function commission_history()
    {
        $this->pageTitle = __('Agent Commission History',true);
        $conditions = array(
            'amount != ?' => 0
        );
        $get_agent_name = $this->_get('data.agent_name');
        if($get_agent_name)
            $conditions['agent_id'] = $get_agent_name;
        if ($this->Session->read('login_type') == 2)
            $conditions['agent_id'] = $this->Session->read('sst_agent_info.Agent.agent_id');

        if (isset($this->params['url']['data']))
        {
            $get_amount_type = $this->_get('data.amount');
            if(!$get_amount_type)
                unset($conditions['amount != ?']);
        }
        $get_status = $this->_get('data.status');
        if ($get_status == 1)
            $conditions['finished'] = true;
        elseif ($get_status == 2)
            $conditions['finished'] = false;

        $time_start = $this->_get('query_time_start',date('Y-m-d',strtotime(' -30 day')));
        $time_end = $this->_get('query_time_end',date('Y-m-d'));
        $this->params['url']['query_time_start'] = $time_start;
        $this->params['url']['query_time_end'] = $time_end;
        $conditions['create_date between ? and ?'] = array(
            $time_start,$time_end
        );
        $pageSize = $this->_get('size') ? $this->_get('size') : 100;

        $order_arr = array('create_date' => 'asc');
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
            ),
            'limit' => $pageSize,
            'order' => $order_arr,
            'conditions' => $conditions,
        );

        $tmpData = $this->AgentCommissionHistory->find('all');

        $this->data = $this->paginate('AgentCommissionHistory');
//        pr($this->data);die;
        $this->set('clients',$this->AgentCommissionHistory->findClient(true));
        $this->set('agents',$this->AgentCommissionHistory->find_agents(true));

    }

    public function add_payment()
    {
        if(!$this->RequestHandler->ispost())
            $this->redirect('commission_history');
        $count = $this->AgentCommissionHistory->judge_pay_status($this->params['form']['payment_id']);
        if ($count)
        {
            $this->Session->write('m', $this->AgentCommissionHistory->create_json(101, __('The Commission is paid!', true)));
            $this->redirect('commission_history');
        }
        $save_data = array(
            'history_id' => $this->params['form']['payment_id'],
            'amount' => $this->params['form']['payment_amount'],
            'note' => $this->params['form']['payment_note'],
            'create_by' => $this->Session->read('sst_user_name'),
        );
        $this->loadModel('AgentCommissionPayment');
        $flg = $this->AgentCommissionPayment->save($save_data);
        if ($flg === false)
            $this->Session->write('m', $this->AgentCommissionHistory->create_json(101, __('Save Failed!', true)));
        else
        {
            $this->Session->write('m', $this->AgentCommissionHistory->create_json(201, __('Save successfully!', true)));
            $this->AgentCommissionHistory->change_status_by_pay($this->params['form']['payment_id']);
        }
        $this->redirect('commission_history');
    }

    public function ajax_check_exist()
    {
        Configure::write('debug', 0);
        $this->loadModel('User');
        $this->autoLayout = false;
        $this->autoRender = false;
        $name = $this->params['url']['fieldValue'];
        $ajax_id = $this->params['url']['fieldId'];
        $user_id = substr($ajax_id,strrpos($ajax_id,'_')+1);
        $count = $this->User->find('count',array(
            'conditions' => array(
                'name' => $name,
                'user_id != ?' => intval($user_id),
            )
        ));
        if ($count)
            echo json_encode(array($ajax_id,false));
        else
            echo json_encode(array($ajax_id,true));
    }

}
