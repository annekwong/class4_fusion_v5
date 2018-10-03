<?php

class InvoiceCdrLogController extends AppController
{

    var $name = 'InvoiceCdrLog';
    var $uses = array('InvoiceCdrLog');
    var $components = array();
    var $helpers = array('javascript', 'html',  'AppCommon');

    public function beforeFilter()
    {
        $this->checkSession("login_type"); //核查用户身份exprot
        parent::beforeFilter(); //调用父类方法
    }
    
    public function index($type=0)
    {
        $this->pageTitle="Log/Invoice CDR Log";
        
        $order_arr = array('id' => 'desc');
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
        
        $conditions_arr = array(
                'type' => $type,
            );
        
        $this->set('client', $this->InvoiceCdrLog->findClient());

        $get_data =  $this->params['url'];

        $this->set('get_data', $get_data);
        
        if (isset($get_data['carrier_name']) && $get_data['carrier_name'])
        {
            $conditions_arr['carrier_name'] = $get_data['carrier_name'];
        }

        if (isset($get_data['time']) && $get_data['time'])
        {
            $conditions_arr[] = "start_time <= '" . $get_data['time'] . "'";
            $conditions_arr[] = "end_time >= '" . $get_data['time'] . "'";
        }
        $this->paginate = array(
            'limit' => 100,
            'order' => $order_arr,
            'conditions' => $conditions_arr
        );
       $this->data = $this->paginate('InvoiceCdrLog');
       $this->set('type', $type);
    }

}