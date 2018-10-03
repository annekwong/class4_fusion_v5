<?php

class BalanceLogController extends AppController
{

    var $name = "BalanceLog";
    var $uses = array('BalanceLogs');
    var $components = array('RequestHandler');
    var $helpers = array('javascript', 'html', 'Common');
    
    public function beforeFilter() {
        parent::beforeFilter();
    }
    
    public function index()
    {
        $this->pageTitle = 'Balance Log::Log';
	$sort = isset($_GET['order_by']) ? $_GET['order_by'] : '';;
	$sortField = 'date';
	$sortDir = 'desc';
	if (!empty($sort)) {
		$sort = explode('-', $sort);
		$sortField = $sort[0];
		$sortDir = $sort[1];
	}
        $this->paginate = array(
            'limit' => 100,
            'fields' => array('BalanceLogs.*', "Client.name"),
            'order' => array(
                $sortField => $sortDir,
            ),
            'joins' => array(
                array(
                    'table' => 'client',
                    'alias' => "Client",
                    'type' => 'LEFT',
                    'conditions' => array(
                        'Client.client_id = BalanceLogs.client_id',
                    ),
                )
            ),
        );
        
        if (isset($_GET['client_id']) and !empty($_GET['client_id'])) {
             $this->paginate['conditions'][] = "BalanceLogs.client_id = {$_GET['client_id']}";
        }
        
        if (isset($_GET['start_date']) and isset($_GET['end_date']) and !empty($_GET['start_date']) and !empty($_GET['end_date'])) {
             $this->paginate['conditions'][] = "BalanceLogs.date between '{$_GET['start_date']}' and '{$_GET['end_date']}'";
        }

        $this->data = $this->paginate('BalanceLogs');
        $this->set("clients", $this->BalanceLogs->clients());
    }
    
    public function reset($balance_log_id)
    {
        Configure::write("debug", 0);
         $this->autoLayout = false;
          $balance_log = $this->BalanceLogs->findById($balance_log_id);
          
           if ($this->RequestHandler->isPost())
          {
               $client_id = $balance_log['BalanceLogs']['client_id'];
               $balance =  $balance_log['BalanceLogs']['balance'];
               $date = $_POST['date'];
               
               
               $sql = "INSERT INTO client_payment (result, receiving_time, amount, client_id, description, payment_type, payment_time, egress_amount)
            VALUES(true, CURRENT_TIMESTAMP, {$balance}, {$client_id}, 'Synchronize', 14, '{$date}', 0)";
                $this->BalanceLogs->query($sql);

                $this->BalanceLogs->create_json_array("", 201, sprintf(__('New balance is scheduled to execute in the queue!',true),$pid));
                $this->Session->write('m', BalanceLogs::set_validator());
                $this->redirect('/balance_log/');
               
          }
          
         
          
          
          
    }
    
}
