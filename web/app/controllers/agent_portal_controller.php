<?php

class AgentPortalController extends appController
{

    var $name = 'AgentPortal';
    var $components = array('RequestHandler');
    var $uses = array('Agent', 'AgentClients', 'Client');
    var $helpers = array('javascript', 'html', 'AppClients', 'Common');


    function beforeFilter()
    {


        $user_type = $this->Session->read('login_type');
        // if ($user_type != 2){
        //     $this->redirect("/homes/logout");
        // }
        // parent::beforeFilter();
    }

    function index()
    {
        $this->redirect('client_list');
    }

    function client_list()
    {
        $user_type = $this->Session->read('login_type');
        if ($user_type != 2) {
            $this->redirect("/homes/logout");
        }
        $this->pageTitle = __('Client List', true);
        $conditions = array();
        $where = '';
        $where_arr = '';
        $filter_client_type = $this->_get('filter_client_type');
        if ($filter_client_type) {
            if ($filter_client_type == 1) {
                $conditions['Client.status'] = true;
                $where_arr[] = 'Client.status = true';
            } else {
                $conditions['Client.status'] = false;
                $where_arr[] = 'Client.status = false';
            }
        }
        Configure::load('myconf');
        $this->set('url', Configure::read('web_base.url'));

        $filter_client_name = $this->_get('search');
        if ($filter_client_name) {
            $conditions[] = "Client.name like '%" . trim($filter_client_name) . "%'";
            $where_arr[] = "Client.name like '%" . trim($filter_client_name) . "%'";
        }

        if ($this->Session->read('sst_agent_info.Agent.agent_id')) {
            $conditions['AgentClients.agent_id'] = $this->Session->read('sst_agent_info.Agent.agent_id');
            $where_arr[] = 'AgentClients.agent_id = ' . $this->Session->read('sst_agent_info.Agent.agent_id');
        }

        $pageSize = $this->_get('size') ? $this->_get('size') : 100;

        $order_str = 'Client.name asc';
        if ($this->isnotEmpty($this->params['url'], array('order_by'))) {
            $order_by = $this->params['url']['order_by'];
            $order_arr = explode('-', $order_by);
            if (count($order_arr) == 2) {
                $field = $order_arr[0];
                $sort = $order_arr[1];
//                $order_arr = array($field => $sort);
                $order_str = $field . ' ' . $sort;
            }
        }
        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];

        $signup_status = ['Waiting for Approve', 'Approved', 'Rejected'];
        $this->set('signup_status', $signup_status);

        $count = $this->AgentClients->find('count', array(
            'conditions' => $conditions,
            'joins' => array(
                array(
                    'alias' => 'Client',
                    'table' => 'client',
                    'type' => 'inner',
                    'conditions' => array(
                        'Client.client_id = AgentClients.client_id'
                    ),
                ),
            ),
        ));
        require_once 'MyPage.php';
        $page = new MyPage ();
        $page->setTotalRecords($count);
        $page->setCurrPage($currPage);
        $page->setPageSize($pageSize);
        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;
        if (!empty($where_arr)) {
            $where = implode(' and ', $where_arr);
            $where = " where {$where} ";
        }
        $data = $this->Agent->findClientsInfo($where, $order_str, $pageSize, $offset);
        $this->loadModel('FinanceHistoryActual');
//        foreach ($data as &$item)
//        {
//            $item[0] = array_merge($item[0], $this->getBalance($item[0]['client_id']));
//        }
        $page->setDataArray($data);
        $this->set('p', $page);
        $this->set('debug', Configure::read("debug"));
        $sql = "SELECT is_show_mutual_balance FROM system_parameter";
        $is_show_mutual_balance_arr = $this->Agent->query($sql);
        $is_show_mutual_balance = $is_show_mutual_balance_arr[0][0]['is_show_mutual_balance'];
        $this->set('is_show_mutual_balance', $is_show_mutual_balance);
        $agentId = $this->Session->read('sst_agent_info.Agent.agent_id');
        $this->set('agentId', $agentId);
        $this->render('/clients/index');


    }

    function getBalance($client_id)
    {

        $current_finance = $this->FinanceHistoryActual->get_current_finance_detail($client_id);
        $last_day_balance = $this->FinanceHistoryActual->get_last_day_balance($client_id);
        $balance = $last_day_balance
            + $current_finance['payment_received']
            + $current_finance['credit_note_received']
            + $current_finance['debit_received']
            + $current_finance['unbilled_outgoing_traffic']
            - $current_finance['payment_sent']
            - $current_finance['credit_note_sent']
            - $current_finance['debit_sent']
            - $current_finance['unbilled_incoming_traffic'];
        return ['balance' => $balance];
    }

    public function products()
    {
        Configure::write('debug', 2);
        $user_type = $this->Session->read('login_type');
        if ($user_type != 2) {
            $this->redirect("/homes/logout");
        }
        $this->pageTitle = __('Products', true);
        $this->loadModel('ProductAgentsRef');
        $this->loadModel('ProductClientsRef');
        $agent_id = $this->Session->read('sst_agent_info.Agent.agent_id');

        $data = $this->ProductAgentsRef->find('all', array(
            'fields' => [
                'ProductRoute.product_name', 'ProductRoute.id', 'ProductRoute.is_private', 'RateTable.jur_type',
                'RateTable.rate_table_id'
            ],
            'conditions' => [
                'ProductAgentsRef.agent_id' => $agent_id
            ],
            'joins' => array(
                array(
                    'alias' => 'ProductRoute',
                    'table' => 'product_route_rate_table',
                    'type' => 'inner',
                    'conditions' => array(
                        'ProductRoute.id = ProductAgentsRef.product_id'
                    ),
                ), array(
                    'alias' => 'RateTable',
                    'table' => 'rate_table',
                    'type' => 'inner',
                    'conditions' => array(
                        'RateTable.rate_table_id = ProductRoute.rate_table_id'
                    ),
                )
            ),
        ));

        foreach ($data as &$item) {
            $item['count'] = $this->ProductClientsRef->getClientCounts($item['ProductRoute']['id'], $agent_id);
        }
        $this->set('url', $this->getUrl());
        $jur_types = ['A-Z', 'US Non JD', 'US JD'];
        $this->set('jur_types', $jur_types);
        $this->set('data', $data);
    }

    public function view_rate($rate_table_id = null)
    {
        Configure::write('debug', 0);
        $user_type = $this->Session->read('login_type');
        if ($user_type != 2) {
            $this->redirect("/homes/logout");
        }
        $this->pageTitle = __('Rates', true);

        $this->loadModel('Clientrate');
        $rate_table_id = base64_decode($rate_table_id);

        $list = $this->Clientrate->query("select name, jur_type from  rate_table  where  rate_table_id=$rate_table_id");

        $rate_list = $this->Clientrate->find_all_rate($rate_table_id, $list[0][0]['jur_type'], '', $this->_order_condtions(array('code', 'code_name', 'rate', 'setup_fee', 'effective_date', 'end_date')), array());
        $this->set('jur_type', $list[0][0]['jur_type']);
        $this->set('p', $rate_list);
        $this->set('table_name', $list[0][0]['name']);
    }

    public function download_rate()
    {
        Configure::write("debug", "0");
        $this->loadModel('RateTable');
        $this->loadModel('Rate');

        $rate_table_id = base64_decode($this->params['pass'][0]);
        $jur_type = $this->RateTable->query("select jur_type FROM rate_table WHERE rate_table_id = $rate_table_id");
        $rate_file = $this->Rate->create_rate_file($rate_table_id, 1, '', '', '', '', $jur_type[0][0]['jur_type']);
        ob_clean();
        header("Content-Type: text/csv");
        header("Content-Disposition: attachment; filename=rate.csv");
        header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
        header('Expires:0');
        header('Pragma:public');
        readfile($rate_file);
        exit;
    }

    function judge_client($client_id)
    {
        $count = $this->AgentClients->find('count', array(
            'conditions' => array(
                'client_id' => $client_id,
                'agent_id' => $this->Session->read('sst_agent_info.Agent.agent_id'),
            ),
        ));
        if ($count > 0)
            return true;
        return false;
    }


    function dis_able()
    {
        if (!$this->Session->read('sst_agent_info.Agent.edit_permission'))
            $this->redirect_denied();
        $id = base64_decode($this->params['pass'][0]);
        if ($this->judge_client($id) === false) {
            $this->Session->write('m', $this->Agent->create_json(101, __('illegal action!', true)));
            $this->redirect(array('action' => 'index', '?' => $this->params['getUrl']));
        }
        $mesg_info = $this->Agent->query("select name from client where client_id = {$id}");
        $this->Agent->query("update client set  status=false where  client_id= $id;");
        $this->Agent->query("update resource set  active=false where  client_id= $id;");
        $this->Session->write('m', $this->Agent->create_json(201, __('The Carrier[%s] is disabled successfully!', true, $mesg_info[0][0]['name'])));
        $this->redirect(array('action' => 'client_list', '?' => $this->params['getUrl']));
    }

    function active()
    {
        if (!$this->Session->read('sst_agent_info.Agent.edit_permission'))
            $this->redirect_denied();
        $id = base64_decode($this->params['pass'][0]);
        if ($this->judge_client($id) === false) {
            $this->Session->write('m', $this->Agent->create_json(101, __('illegal action!', true)));
            $this->redirect(array('action' => 'index', '?' => $this->params['getUrl']));
        }
        $mesg_info = $this->Agent->query("select name from client where client_id = {$id}");
        $this->Agent->query("update client set  status=true where  client_id= $id;");
        $this->Agent->query("update resource set  active=true where  client_id= $id;");
        $this->Session->write('m', $this->Agent->create_json(201, __('The Carrier[%s] is enabled successfully!', true, $mesg_info[0][0]['name'])));
        $this->redirect(array('action' => 'client_list', '?' => $this->params['getUrl']));
    }

    function edit_client()
    {
        $this->Client->id = base64_decode($this->params ['pass'] [0]);
        if ($this->RequestHandler->isPost()) {
            if (!$this->Session->read('sst_agent_info.Agent.edit_permission'))
                $this->redirect_denied();
            $this->data['Client']['allowed_credit'] = 0 - $this->data['Client']['allowed_credit'];
            $flag = $this->Client->saveOrUpdate($this->data, $_POST); //保存
            if ($flag['client_id']) {
                $this->Client->create_json_array('#ClientOrigRateTableId', 201, __('The Carrier[%s] modified successfully.', true, $this->data['Client']['name']));
                $this->Session->write("m", Client::set_validator());
                $url_flug = "agent_portal-client_list";
                $this->modify_log_noty($flag['log_id'], $url_flug);
            }
        }
        $this->set('post', $this->Client->read());
        $this->set('gate_client_id', base64_decode($this->params ['pass'] [0]));
        $this->set('paymentTerm', $this->Client->findPaymentTerm());
        $this->render('/clients/edit');

    }

    public function agent_dashboard()
    {
        $this->set('clients_balance', $this->AgentClients->findAgentBalance());

    }

    public function ajax_get_agent_balance()
    {
        Configure::write('debug', 0);
        $this->autoLayout = false;
        $this->autoRender = false;
        $data = $this->AgentClients->findAgentBalance();
        $ajax_data = array();
        foreach ($data as $item) {
            $ajax_data[] = array($item[0]['name'], $item[0]['balance']);
        }
        $return_arr = array(
            'data' => $ajax_data
        );
        return json_encode($return_arr);
    }

    public function ajax_get_agent_report()
    {
        Configure::write('debug', 0);
        $this->autoLayout = false;
        $this->autoRender = false;
        $time_interval = $this->params['form']['time_interval'];
        if (!in_array(intval($time_interval), array(1, 24)))
            $time_interval = 24;
        $end_time = date('Y-m-d H:i:sO');
        if ($time_interval == 1)
            $start_time = date('Y-m-d H:i:sO', strtotime('-1 hours'));
        else
            $start_time = date('Y-m-d H:i:sO', strtotime('-1 days'));

        $this->loadModel('CdrsRead');
        $clients = $this->AgentClients->findClient();
        $data = $this->CdrsRead->getSingleAgentReport($start_time, $end_time);
        $ajax_data = array();
        foreach ($data as $item) {
            $bill_time = $item[0]['bill_time'];
            $call_cost = $item[0]['call_cost'];
            $item[0]['bill_time'] = number_format($bill_time / 60, 2);
            $item[0]['avg_rate'] = $bill_time == 0 ? 0 : $call_cost / ($bill_time / 60);
            $item[0]['client_name'] = isset($clients[$item[0]['ingress_client_id']]) ? $clients[$item[0]['ingress_client_id']] : '--';
            $ajax_data[] = $item[0];
        }
        $arr = array(
            'data' => $ajax_data,
            'data_count' => count($ajax_data),
            'time_interval' => $start_time . '~' . $end_time
        );
        return json_encode($arr);
    }


    public function get_dashboard_data()
    {
        Configure::write('debug', 0);
        $this->autoLayout = false;
        $this->autoRender = false;
        $chart_type = $this->params['form']['chart_type'];
        $time_type = $this->params['form']['time_type'];
        $which_value = $this->params['form']['which_value'];
//        $chart_type = 1;
//        $time_type = 1;
//        $which_value = 'call_attempts';
        $this->loadModel('Dashboard');
        switch ($chart_type) {
            case '1':
                $series_type = $this->params['form']['series_type'];
                $data = $this->Dashboard->getAgentQosData($time_type, $which_value, $series_type);
                break;
            case '2':
                $data = $this->Dashboard->ajax_chart2($time_type, $which_value);
                break;
            default:
        }

        echo json_encode($data);
    }

    public function downloadSharedRate($rateTableId)
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $this->loadModel('Clientrate');

        $rateTableId = base64_decode($rateTableId);
        $list = $this->Clientrate->query("select name, jur_type from  rate_table  where  rate_table_id = $rateTableId");
        $rate_list = $this->Clientrate->find_all_rate($rateTableId, $list[0][0]['jur_type'], '', $this->_order_condtions(array('code', 'code_name', 'rate', 'setup_fee', 'effective_date', 'end_date')), array());
        $rate_list = $rate_list->dataArray;

        if (count($rate_list) > 0) {
            $filepath = ROOT . DS . '../' . DS . 'download' . DS . 'rate_download' . DS . 'agentRates.csv';
            $handle = fopen($filepath, 'w');

            fputcsv($handle, array_keys($rate_list[0][0]));

            foreach ($rate_list as $item) {
                fputcsv($handle, $item[0]);
            }
            fclose($handle);

            $this->loadModel('Download');
            $this->Download->csv($filepath);

        } else {
            header("HTTP/1.0 404 Not Found");
        }
//        exit;
    }



}

?>
