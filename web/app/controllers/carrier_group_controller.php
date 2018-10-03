<?php

class CarrierGroupController extends AppController
{

    var $name = "CarrierGroup";
    var $helpers = array('Javascript', 'Html', 'Text', 'Common');
    var $components = array('RequestHandler');
    var $uses = array('CarrierGroup','Client');

    public function beforeFilter()
    {
        $this->checkSession("login_type"); //核查用户身份
        $login_type = $this->Session->read('login_type');
        if ($login_type == 1)
        {
            $this->Session->write('executable', true);
            $this->Session->write('writable', true);
        }
        else
        {
            $limit = $this->Session->read('sst_config_CodeDeck');
            $this->Session->write('executable', $limit['executable']);
            $this->Session->write('writable', $limit['writable']);
        }
        parent::beforeFilter();
    }

    public function index()
    {
        $this->pageTitle = "Configuration/Carrier Group";
        $order_arr = array(
            'group_name' => 'asc'
        );
        if (!$this->CarrierGroup->find('count',array())){
            $this->redirect('save');
        }
        if ($this->isnotEmpty($this->params['url'], array('order_by')))
        {
            $order_by = $this->params['url']['order_by'];
            $order_temp_arr = explode('-', $order_by);
            if (count($order_temp_arr) == 2)
            {
                $field = $order_temp_arr[0];
                $sort = $order_temp_arr[1];
                $order_arr[$field] = $sort;
            }
        }
        $page = $this->_get('size',100);
        $this->paginate = array(
            'fields' => array(
                'group_id','group_name','(select count(1) from client where group_id = "CarrierGroup".group_id) as use_total'
            ),
            'limit' => $page,
            'order' => $order_arr,
            'conditions' => array(
            ),
        );
        $this->data = $this->paginate('CarrierGroup');
    }

    public function save($encode_group_id = '')
    {
        $this->pageTitle = "Configuration/Save Carrier Group";
        $group_id = base64_decode($encode_group_id);

        if ($this->RequestHandler->isPost())
        {
            $group_name = $this->data['group_name'];
            $count = $this->CarrierGroup->find('count',array(
                'conditions' => array(
                    'group_name' => $group_name,
                    'group_id != ?' => intval($group_id)
                ),
            ));
            if ($count){
                $this->Session->write('m', $this->CarrierGroup->create_json(101, __('Carrier group name [' . $group_name . '] is already in use!', true)));
                $this->redirect('save/'.$encode_group_id);
            }
            if ($this->CarrierGroup->save($this->data) === false){
                $this->Session->write('m', $this->CarrierGroup->create_json(101, __('Save Failed!', true)));
                $this->redirect('save/'.$encode_group_id);
            }
            if (!$group_id){
                $group_id = $this->CarrierGroup->getLastInsertID();
            }
            $carrier_arr = array();
            foreach ($this->params['form']['carrier'] as $carrier_id){
                $carrier_arr[] = array(
                    'group_id' => $group_id,
                    'client_id' => $carrier_id
                );
            }
            $this->Client->saveAll($carrier_arr);

            if($encode_group_id == ''){
                $action = 'added';
            }else{
                $action = 'is modified';
            }

            $this->Session->write('m', $this->CarrierGroup->create_json(201, __('Carrier group name [' . $this->data['group_name'] . '] ' . $action . ' successfully!', true)));
            $this->redirect("index");
        }
        $group_id = intval($group_id);
        $group_info = $this->CarrierGroup->findByGroupId($group_id);
        $group_name = '';
        if ($group_info){
            $this->data = $group_info['CarrierGroup'];
            $group_name = $this->data['group_name'];
        }
        $this->set('group_name',$group_name);
        $this->set('carrier_group',$this->CarrierGroup->get_client_group());

    }


    public function ajax_check_group_name()
    {
        Configure::write('debug', 0);
        $this->autoLayout = false;
        $this->autoRender = false;
        $group_name = $this->params['url']['fieldValue'];
        $group_id = $this->params['url']['rule_id'];
        $ajax_id = $this->params['url']['fieldId'];

        $count = $this->CarrierGroup->find('count', array(
            'conditions' => array(
                'rule_name' => $group_name,
                'group_id != ?' => intval($group_id)
            ),
        ));
        if ($count)
            return json_encode(array($ajax_id, false));
        else
            return json_encode(array($ajax_id, true));
    }

    public function delete_group($encode_id){
        $id = base64_decode($encode_id);
        $group_info = $this->CarrierGroup->findByGroupId($id);
        if (!$group_info){
            $this->Session->write('m', $this->CarrierGroup->create_json(101, __('Illegal operation!', true)));
            $this->redirect("index");
        }
        $group_name = $group_info['CarrierGroup']['group_name'];
        if ($this->CarrierGroup->del($id) === false){
            $this->Session->write('m', $this->CarrierGroup->create_json(101, __('Delete failed!', true)));
        }else{
            $this->Session->write('m', $this->CarrierGroup->create_json(201, __('Carrier group name [%s] is deleted successfully!', true,array($group_name))));
        }
        $this->redirect("index");


    }

    public function view_carrier_list($encode_id){
        $id = base64_decode($encode_id);
        $group_info = $this->CarrierGroup->findByGroupId($id);
        if (!$group_info){
            $this->Session->write('m', $this->CarrierGroup->create_json(101, __('Illegal operation!', true)));
            $this->redirect("index");
        }
        $this->redirect('/clients/index?group_id='.$encode_id);

    }


}
