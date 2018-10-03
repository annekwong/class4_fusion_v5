<?php

class TrunksController extends AppController
{

    var $name = 'Trunks';
    var $uses = Array('Resource');

    public function beforeFilter()
    {
//        $this->checkSession("login_type"); //核查用户身份
//        parent::beforeFilter(); //调用父类方法
    }

    function _render_index_bindModel()
    {
        $this->Resource->unbindModel(Array('hasMany' => Array('ResourceIp', 'InRealcdr', 'ERealcdr')), false);
        $bindModel = Array();
        $bindModel['belongsTo'] = Array();
        $bindModel['belongsTo']['Client'] = Array('className' => 'Client', 'fields' => Array('client_id', 'name'));
        $bindModel['belongsTo']['RateTable'] = Array('className' => 'Rate', 'fields' => Array('rate_table_id', 'name'));
        $this->Resource->bindModel($bindModel, false);
    }

    function _render_index_fields()
    {
        $this->paginate['Resource']['fields'] = Array('resource_id', 'alias', 'Resource.name', 'cps_limit', 'capacity', 'ingress', 'egress', 'active', 'proto');
    }

    function _render_index_data()
    {
        $this->_render_index_bindModel();
        $this->_render_index_fields();
        $this->data = $this->paginate('Resource');
    }

    function index()
    {
        $this->layout = 'ajax';
        $this->_render_index_data();
    }

    function ajax_options()
    {
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        if (!isset($this->params['url']['trunk_type2']))
        {
            $this->params['url']['trunk_type2'] = 0;
        }

        $conditions = $this->_filter_conditions(Array('id' => 'Resource.client_id', 'type', 'trunk_type2' => 'Resource.trunk_type2', 'show_type' => 'Resource.ingress'));
        $conditions .= " and Resource.active=true";
        if ($_SESSION['login_type'] == 2)
        {
            $agent_id = (int) $_SESSION['sst_agent_info']['Agent']['agent_id'];
            $conditions .= " and exists(select 1 from agent_clients where client_id = Resource.client_id and agent_id = {$agent_id})";
        }
        $options = Array(
            'conditions' => $conditions,
            'fields' => Array('resource_id', 'alias'),
            'order' => 'alias asc'
        );

        $this->Resource->unbindModel(Array('hasMany' => Array('ResourceIp', 'InRealcdr', 'ERealcdr')));
        if(isset($_POST['a'])){
            $this->loadModel('Cdr');
            $ingress = $this->Cdr->findAll_ingress_id();
            $output = array(); $i=0;
            foreach ($ingress as $key => $value) {
                $output[$i]['id'] = $key;
                $output[$i]['name'] = $value;
                $i++;
            }
            echo json_encode($output);
        }else{
            $this->data = $this->Resource->find('all', $options);
        }

    }

    public function _filter_trunk_type2()
    {
        $type = array_keys_value($this->params, 'url.trunk_type2');
        $return = "trunk_type2 = {$type}";
        return $return;
    }

    public function _filter_show_type()
    {
        if (array_keys_value($this->params, 'url.show_type') !== NULL)
        {
            $type = array_keys_value($this->params, 'url.show_type') == 1 ? 'Resource.ingress' : 'Resource.egress';
            ;
            $return = "$type = true";
            return $return;
        }
        return '';
    }

    function _filter_type()
    {
        $return = '';
        $type = array_keys_value($this->params, 'url.type');
        if ($type == 'ingress')
        {
            $return .= "ingress =true";
        }
        if ($type == 'egress')
        {
            $return .= "egress=true";
        }

        $client_id = array_keys_value($this->params, 'url.id');
        if (!empty($client_id))
        {
            $return .= " and client_id = " . intval($client_id);
        }
        return $return;
    }

    public function unclaimed_trunks()
    {
        $conditions['client_id'] = null;
        $conditions[] = 'Resource.is_virtual is not true';
        $this->paginate = array(
            'fields' => "Resource.resource_id, Resource.rate_table_id,Resource.alias,Resource.update_at,RateTable.name,Resource.is_virtual",
            'limit' => 100,
            'joins' => array(
                0 => array(
                    'table' => 'rate_table',
                    'alias' => 'RateTable',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'RateTable.rate_table_id = Resource.rate_table_id',
                    )
                )
            ),
            'order' => array(
            //'id' => 'desc',
            ),
            'conditions' => $conditions,
        );
        $this->data = $this->paginate();
//        pr($this->data);
    }

    public function delete_trunk($encode_id)
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $id = base64_decode($encode_id);
        $flg = $this->Resource->del($id);
        if ($flg === false)
            $this->Session->write('m', $this->Resource->create_json(101, __('Delete unclaimed trunk Failed',true)));
        else
            $this->Session->write('m', $this->Resource->create_json(201, __('Delete unclaimed trunk successfully',true)));
        $this->redirect("unclaimed_trunks");
    }

}

?>