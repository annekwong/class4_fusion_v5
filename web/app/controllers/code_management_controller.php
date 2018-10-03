<?php

class CodeManagementController extends AppController
{

    var $name = 'CodeManagement';
    var $components = array('RequestHandler');
    var $uses = array('RateTable');

    function index()
    {
        $this->redirect('no_route');
    }

    public function beforeFilter()
    {
//        $this->checkSession("login_type"); //核查用户身份
//        parent::beforeFilter();
    }

    public function init()
    {
        $product_list = $this->RateTable->product_route_rate_table(true);
        $product_list[0] = __('By Routing Plan and Rate Table',true);
        $this->set('product_list',$product_list);
        $this->set('rate_table',$this->RateTable->find_all_rate_table());
        $this->set('route_plan',$this->RateTable->find_routepolicy());
    }

    public function no_route()
    {
        $this->init();
        if($this->RequestHandler->isPost())
        {
            $rate_table_id = $this->data['CodeManagement']['rate_table'];
            $route_plan = $this->data['CodeManagement']['route_plan'];
            $condition = $this->data['CodeManagement']['condition'];


        }
    }


}

?>
