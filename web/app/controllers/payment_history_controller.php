<?php

class PaymentHistoryController extends AppController
{

    var $name = "PaymentHistory";
    var $helpers = array('Javascript', 'Html', 'Text');
    var $components = array('RequestHandler');
    var $uses = array('PaymentHistory');

    public function beforeFilter()
    {
        $this->checkSession("login_type"); //核查用户身份

        parent::beforeFilter();
    }

    public function index()
    {
        $login_type = $_SESSION['login_type'];
        $this->loadModel('Systemparam');
        $pay_info = $this->Systemparam->find('first',array(
            'fields' => array('paypal_account','stripe_account','stripe_public_account')
        ));
        $pay_type_arr = array();
        if($pay_info['Systemparam']['paypal_account'])
            $pay_type_arr[1] = 'Paypal';
        if($pay_info['Systemparam']['stripe_account'] && $pay_info['Systemparam']['stripe_public_account'])
            $pay_type_arr[2] = 'Stripe';
        $this->set('pay_type_arr',$pay_type_arr);


        $order_arr = array('PaymentHistory.id' => 'desc');

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

        $this->set('client', $this->PaymentHistory->findClient(true));



        $get_data =  $this->params['url'];

        $this->set('get_data', $get_data);

        $condition_arr = array();

        //pr($get_data);die;

        if (isset($get_data['status']) && !empty($get_data['status']))
        {
            $condition_arr['PaymentHistory.status'] = $get_data['status'];
        }
        elseif (isset($get_data['status']) && !strcmp($get_data['status'], '0'))
        {
            $condition_arr['PaymentHistory.status'] = '0';
        }

        if ($login_type == 3)
        {
            $condition_arr['client.client_id'] = $_SESSION['sst_client_id'];
        } else {
            if (isset($get_data['client_id']) && $get_data['client_id'])
            {
                $condition_arr['client.client_id'] = $get_data['client_id'];
            }
        }


        if (isset($get_data['submitted_time_start']) && $get_data['submitted_time_start'])
        {
            $condition_arr[] = "PaymentHistory.created_time >= '" . $get_data['submitted_time_start'] . "'";
        }

        if (isset($get_data['submitted_time_end']) && $get_data['submitted_time_end'])
        {
            $condition_arr[] = "PaymentHistory.created_time <= '" . $get_data['submitted_time_end'] . "'";
        }

        $join_arr = array();
        $join_arr[] = array(
            'table' => 'client',
            'type' => 'left',
            'conditions' => array(
                'PaymentHistory.client_id = client.client_id'
            ),
        );
        if ($login_type == 2)
        {
            $join_arr[] = array(
                'alias' => 'AgentClients',
                'table' => 'agent_clients',
                'type' => 'inner',
                'conditions' => array(
                    'PaymentHistory.client_id = AgentClients.client_id',
                    'AgentClients.agent_id =' . $this->Session->read('sst_agent_info.Agent.agent_id')
                ),
            );
//            $condition_arr[] = "AgentClients.agent_id = " . $this->Session->read('sst_agent_info.Agent.agent_id');
        }
        $this->paginate = array(
            'fields' => array(
                'PaymentHistory.id',
                'PaymentHistory.chargetotal', 'PaymentHistory.method', 'PaymentHistory.cardnumber', 'PaymentHistory.cardexpmonth',
                'PaymentHistory.cardexpyear', 'PaymentHistory.created_time', 'PaymentHistory.modified_time', 'PaymentHistory.error',
                'PaymentHistory.confirmed', 'client.name', 'PaymentHistory.status', 'PaymentHistory.fee','PaymentHistory.return_code',
                'PaymentHistory.transaction_id', 'PaymentHistory.paypal_id', 'PaymentHistory.response', 'PaymentHistory.charge_amount'
            ),
            'limit' => 100,
            'conditions' => $condition_arr,
            'joins' => $join_arr,
            'order' => $order_arr,
        );


        $method = array('paypal', 'yourpay','Stripe');
        $status = array(__('Requested',true), __('Failed',true), __('Succeed',true));
        $this->data = $this->paginate('PaymentHistory');

        foreach($this->data as &$item){
            $item['PaymentHistory']['response'] = (isset($item['PaymentHistory']['response']) && $this->isJson($item['PaymentHistory']['response'])) ? json_decode( $item['PaymentHistory']['response'], true) : '';
        }

        $sql = "select yourpay_store_number from system_parameter limit 1";
        $result = $this->PaymentHistory->query($sql);
        if (empty($result[0][0]['yourpay_store_number']))
        {
            $this->set('credit_card', false);
        }
        else
        {
            $this->set('credit_card', true);
        }

        $this->set('method', $method);
        $this->set('status', $status);
        $this->set('login_type', $login_type);
    }

    public function get_error_file($history_id)
    {
        $this->autoRender = false;
        $this->autoLayout = false;
        if (empty($history_id))
        {
            $this->Session->write('m', $this->PaymentHistory->create_json(101, 'Failed!'));
            $this->redirect("/payment_history");
        }
        $history_id = base64_decode($history_id);
        $sql = "SELECT error FROM payline_history WHERE id = {$history_id}";
        $error_date = $this->PaymentHistory->query($sql, false);
        $error_info = $error_date[0][0]['error'];
        if (empty($error_info))
        {
            $this->Session->write('m', $this->PaymentHistory->create_json(101, 'Failed2!'));
            $this->redirect("/payment_history");
        }
        $db_path = Configure::read('database_export_path');
        $file = $db_path . "/error.txt";
        file_put_contents($file, $error_info);
        if (file_exists($file))
        {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename=' . basename($file));
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file));
            ob_clean();
            flush();
            readfile($file);
            exit;
        }
    }

}
