<?php

class CidBlockingController extends AppController
{

    var $name = 'CidBlocking';
    var $components = array('RequestHandler');
    var $uses = array('AlertRules',  'AlertRulesLog', 'AlertRulesLogDetail');

    public function beforeFilter() {
        $this->checkSession("login_type"); //核查用户身份
        parent::beforeFilter();
    }

    public function index()
    {

        $this->pageTitle = __('CID Blocking', true);
        $this->initData();
        $conditions = array(
            'AlertRules.auto_define' => true
        );
        $login_type = $_SESSION['login_type'];
        if ($login_type == '3')
        {
            $client_id = $this->Session->read('sst_client_id');
            $conditions['Resource.client_id'] = $client_id;
        }elseif(isset($_GET['data']['client_id']) && $_GET['data']['client_id']){
            $conditions['Resource.client_id'] = $_GET['data']['client_id'];
        }

        if((isset($_GET['data']['ingress_id']) && $_GET['data']['ingress_id']) || (isset($_GET['data']['egress_id']) && $_GET['data']['egress_id'])){
            $resource_id = isset($_GET['data']['ingress_id']) && $_GET['data']['ingress_id'] ? $_GET['data']['ingress_id'] : $_GET['data']['egress_id'];
            $conditions['Resource.resource_id'] = $resource_id;
        }

        if(isset($_GET['data']['start_time']) && isset($_GET['data']['end_time']) && $_GET['data']['start_time'] && $_GET['data']['end_time']){
            $start = $_GET['data']['start_time'];
            $end = $_GET['data']['end_time'];
            $conditions[] = " AlertRulesLog.create_on between  '$start'  and  '$end'";
        }

        $order_arr = array('AlertRulesLogDetail.id' => 'asc');
        if (isset($_GET['data']['order_by']) && $_GET['data']['order_by']) {
            $order_arr = array($_GET['data']['order_by'] => 'asc');
        }
        $pageSize = $this->_get('size') ? $this->_get('size') : 100;

        if ($this->isnotEmpty($this->params['url'], array('order_by'))) {
            $order_by = $this->params['url']['order_by'];
            $order_arr = explode('-', $order_by);
            if (count($order_arr) == 2) {
                $field = $order_arr[0];
                $sort = $order_arr[1];
                $order_arr = array($field => $sort);
            }
        }
        $this->paginate = array(
            'fields' => array(
                'AlertRules.id', 'AlertRules.asr_value','AlertRules.acd_value','AlertRulesLogDetail.id','AlertRulesLogDetail.sdp_value', 'AlertRulesLogDetail.*', 'AlertRulesLog.*',
                'AlertRules.rule_name', 'Resource.alias', 'AlertRules.res_id',
                'Resource.client_id','Resource.cid_max_sdp', 'AlertRules.res_id','AlertRules.auto_define','Client.name'
            ),
            'limit' => $pageSize,
            'order' => $order_arr,
            'conditions' => $conditions,
            'joins' => array(
                array(
                    'alias' => 'AlertRulesLog',
                    'table' => 'alert_rules_log',
                    'type' => 'inner',
                    'conditions' => array(
                        'AlertRulesLog.id = AlertRulesLogDetail.alert_rules_log_id'
                    ),
                ),
                array(
                    'alias' => 'AlertRules',
                    'table' => 'alert_rules',
                    'type' => 'inner',
                    'conditions' => array(
                        'AlertRules.id = AlertRulesLog.alert_rules_id'
                    ),
                ),
                array(
                    'alias' => 'Resource',
                    'table' => 'resource',
                    'type' => 'left',
                    'conditions' => array(
                        'Resource.resource_id::text = AlertRules.res_id'
                    ),
                ),
                array(
                    'alias' => 'Client',
                    'table' => 'client',
                    'type' => 'left',
                    'conditions' => array(
                        'Resource.client_id = Client.client_id'
                    ),
                ),
            ),
        );
        $this->data = $this->paginate('AlertRulesLogDetail');
        foreach($this->data as &$item){
            $sql = "SELECT create_on as first_block FROM alert_rules_log WHERE alert_rules_id='{$item["AlertRules"]["id"]}' ORDER by create_on LIMIT 1";
            $res = $this->AlertRulesLog->query($sql);
            $item['AlertRules']['first_block'] = $res[0][0]["first_block"];
            $sql = "SELECT create_on as last_block FROM alert_rules_log WHERE alert_rules_id='{$item["AlertRules"]["id"]}' ORDER by create_on DESC LIMIT 1";
            $res = $this->AlertRulesLog->query($sql);
            $item['AlertRules']['last_block'] = $res[0][0]["last_block"];
        }

    }

    function initData(){
        $this->set('carriers', $this->AlertRules->findClient(true));
        $this->set('ingress', $this->AlertRules->findAll_ingress_id());
        $this->set('egress', $this->AlertRules->findAll_egress_id());
        $this->set('order_by', [
            'Resource.alias' => 'Trunks',
            'AlertRulesLog.create_on' => 'Start',
            'AlertRulesLog.finish_time' => 'End',
        ]);
    }

}