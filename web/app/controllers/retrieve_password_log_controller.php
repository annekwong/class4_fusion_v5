<?php

class RetrievePasswordLogController extends AppController
{
    var $name = "RetrievePasswordLog";
    var $helpers = array('Javascript', 'Html', 'Text');
    var $components = array('RequestHandler');
    var $uses = array('RetrievePasswordLog');

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

    public function index(){
        $this->pageTitle = 'Retrieve Password Log';
        $start_time = date("Y-m-d 00:00:00");
        $end_time = date("Y-m-d 23:59:59");

        if (isset($_GET['start_time']))
        {
            $start_time = $_GET['start_time'];
            $end_time = $_GET['end_time'];
        }

        $conditions =  array(
            'RetrievePasswordLog.operation_time BETWEEN ? and ?' => array($start_time, $end_time)
        );

        if (isset($_GET['status_type']) && !empty($_GET['status_type']))
        {
            $conditions["RetrievePasswordLog.status"] = $_GET['status_type'];
        }


        $order_arr = array();
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
        } else {
            $order_arr = array('RetrievePasswordLog.operation_time' => 'desc');
        }
        //$order_arr = array_merge($order_arr,array('RetrievePasswordLog.id' => 'desc'));


        $this->paginate = array(
            'fields' => array(
                'operation_time','username','email_addresses','status','modify_time'
            ),
            'limit' => 100,

            'order' => $order_arr,
            'conditions' => $conditions
        );


        $status_name = array(
            1 => __('Modify failure',true),
            2 => __('E-mail has been sent',true),
            3 => __('Modified successfully',true),
            4 => __('E-mail has been sent,and link is effective',true)
        );

        $this->data = $this->paginate('RetrievePasswordLog');
        $now_time = time();
        foreach ($this->data as $key => $val) {
            if( $val['RetrievePasswordLog']['status']==2 && (($now_time - strtotime($val['RetrievePasswordLog']['operation_time'])) < 3600) ){
                $this->data[$key]['RetrievePasswordLog']['mark'] = true;
            }
        }
        $this->set('status_name', $status_name);
        $this->set('start_time', $start_time);
        $this->set('end_time', $end_time);
    }

}
