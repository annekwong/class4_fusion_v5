<?php

class CreditLogsController extends AppController
{
    var $name = "CreditLogs";
    var $uses = array('CreditLog', 'EmailLog');
    var $components = array('RequestHandler');
    var $helpers = array('javascript', 'html', 'Common');
    
    public function beforeFilter() 
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
        $this->pageTitle = "Credit Mondification Log";
        
        $start_time = date("Y-m-d 00:00:00");
        $end_time   = date("Y-m-d 23:59:59");
        
        if (isset($_GET['start_time']))
        {
            $start_time = $_GET['start_time'];
            $end_time   = $_GET['end_time'];            
        }
        
        $conditions =  array(
            'CreditLog.modified_on BETWEEN ? and ?' => array($start_time, $end_time) 
        );
        
        if (isset($_GET['client']) && !empty($_GET['client']))
        {
            $conditions["CreditLog.carrier_name"] = $_GET['client'];
        }
        
        $this->paginate = array(
            'limit' => 100,
            'order' => array(
                'CreditLog.id' => 'desc',
            ),
            'conditions' => $conditions
        );
        
       $this->data = $this->paginate('CreditLog');
       $this->set('clients', $this->EmailLog->get_carriers());
        $this->set('start_time', $start_time);
        $this->set('end_time', $end_time);
    }
    
    
}