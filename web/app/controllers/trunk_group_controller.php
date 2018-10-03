<?php

class TrunkGroupController extends AppController
{

    var $name = "TrunkGroup";
    var $helpers = array('Javascript', 'Html', 'Text', 'Common');
    var $components = array('RequestHandler');
    var $uses = array('TrunkGroup','Client','Resource');

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

    public function index($trunk_type = 1)
    {
        $this->pageTitle = "Configuration/Trunk Group";
        $order_arr = array(
            'group_name' => 'asc'
        );
        if (!$this->TrunkGroup->find('count',array('conditions' => array('trunk_type' => $trunk_type)))){
            $this->redirect('save/'.$trunk_type);
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
                'group_id','group_name','(select count(1) from resource inner join client on client.client_id = resource.client_id where resource.group_id = "TrunkGroup".group_id ) as use_total'
            ),
            'limit' => $page,
            'order' => $order_arr,
            'conditions' => array(
                'trunk_type' => $trunk_type
            ),
        );
        $this->data = $this->paginate('TrunkGroup');
        $this->set('trunk_type',$trunk_type);
    }

    public function save($trunk_type = 0,$encode_group_id = '')
    {
        if ($trunk_type){
            $this->pageTitle = "Configuration/Save Egress Trunk Group";
        }else{
            $this->pageTitle = "Configuration/Save Ingress Trunk Group";
        }

        $group_id = base64_decode($encode_group_id);

        if ($this->RequestHandler->isPost())
        {
            $group_name = $this->data['group_name'];
            $count = $this->TrunkGroup->find('count',array(
                'conditions' => array(
                    'group_name' => $group_name,
                    'group_id != ?' => intval($group_id)
                ),
            ));
            if ($count){
                $this->Session->write('m', $this->TrunkGroup->create_json(101, __('Group name is exist!', true)));
                $this->redirect('save/'.$encode_group_id);
            }
            if (!$group_id){
                $this->data['trunk_type'] = $trunk_type;
            }
            if ($this->TrunkGroup->save($this->data) === false){
                $this->Session->write('m', $this->TrunkGroup->create_json(101, __('Save Failed!', true)));
                $this->redirect('save/'.$encode_group_id);
            }
            if (!$group_id){
                $group_id = $this->TrunkGroup->getLastInsertID();
            }

            //reseting existings
            $sql = "UPDATE resource SET group_id=NULL WHERE group_id='$group_id';";
            $this->Resource->query($sql);

            $trunk_arr = array();
            foreach ($this->params['form']['trunk'] as $trunk_id){
                $trunk_arr[] = array(
                    'group_id' => $group_id,
                    'resource_id' => $trunk_id
                );
            }

            $this->Resource->saveAll($trunk_arr);
            $nname = $this->TrunkGroup->find(array('group_id' => $group_id))['TrunkGroup']['group_name'];
            if($trunk_type == 1){
                $ttype = 'Egress';
            }else{
                $ttype = 'Ingress';
            }
            $this->Session->write('m', $this->TrunkGroup->create_json(201, __('The ' . $ttype . ' trunk group [' . $nname . '] is modified successfully!', true)));
            $this->redirect("index");
        }
        $group_id = intval($group_id);
        $group_info = $this->TrunkGroup->findByGroupId($group_id);
        $group_name = '';
        if ($group_info){
            $this->data = $group_info['TrunkGroup'];
            $group_name = $this->data['group_name'];
        }
        $this->set('group_name',$group_name);
        if ($trunk_type){
            $this->set('trunk_group',$this->TrunkGroup->get_group_egress_group());
        }else{
            $this->set('trunk_group',$this->TrunkGroup->get_group_ingress_group());
        }
        $this->set('trunk_type',$trunk_type);


    }


    public function ajax_check_group_name()
    {
        Configure::write('debug', 0);
        $this->autoLayout = false;
        $this->autoRender = false;
        $group_name = $this->params['url']['fieldValue'];
        $group_id = $this->params['url']['rule_id'];
        $ajax_id = $this->params['url']['fieldId'];

        $count = $this->TrunkGroup->find('count', array(
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
        $group_info = $this->TrunkGroup->findByGroupId($id);
        if (!$group_info){
            $this->Session->write('m', $this->TrunkGroup->create_json(101, __('Illegal operation!', true)));
            $this->redirect("index");
        }
        $group_name = $group_info['TrunkGroup']['group_name'];
        $typeName = $group_info['TrunkGroup']['trunk_type'] == '1' ? 'Egress' : 'Ingress';
        if ($this->TrunkGroup->del($id) === false){
            $this->Session->write('m', $this->TrunkGroup->create_json(101, __('Delete failed!', true)));
        }else{
            $this->Session->write('m', $this->TrunkGroup->create_json(201, __('The %s trunk group [%s] is deleted successfully!', true,array($typeName, $group_name))));
        }
        $this->redirect("index");


    }

    public function view_trunk_list($encode_id){
        $id = base64_decode($encode_id);
        $group_info = $this->TrunkGroup->findByGroupId($id);
        if (!$group_info){
            $this->Session->write('m', $this->TrunkGroup->create_json(101, __('Illegal operation!', true)));
            $this->redirect("index");
        }
        $trunk_type = $group_info['TrunkGroup']['trunk_type'];
        if ($trunk_type){
            $this->redirect('/prresource/gatewaygroups/view_egress?group_id='.$encode_id);
        }else{
            $this->redirect('/prresource/gatewaygroups/view_ingress?group_id='.$encode_id);
        }


    }


}
