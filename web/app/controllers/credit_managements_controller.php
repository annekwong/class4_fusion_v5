<?php

class CreditManagementsController extends AppController
{
    var $name = "CreditManagements";
    var $uses = array('Client');
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
//        pr($this->params);die;
        if ($_SESSION['login_type'] != 1)
        {
            $this->redirect('/clients/carrier/');
        }
        $this->pageTitle = 'Management/Carriers';
        $temp = isset($_SESSION['paging_row']) ? $_SESSION['paging_row'] : 100;
        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = $temp : $pageSize = $_GET['size'];
        $_SESSION['paging_row'] = $pageSize;

        $order_str = 'name asc';
        if ($this->isnotEmpty($this->params['url'], array('order_by')))
        {
            $order_by = $this->params['url']['order_by'];
            $order_arr = explode('-', $order_by);
            if (count($order_arr) == 2)
            {
                $field = $order_arr[0];
                $sort = $order_arr[1];
                $order_str = $field . ' ' . $sort;
            }
        }



        $where = "";
        if (isset($_GET['submit']))
        {
            $filter_type = $_GET['filter_client_type'];
            $client_name = $_GET['search'];
            switch ((int) $filter_type)
            {
                case 1:
                    $where = " AND client.status = true";
                    break;
                case 2:
                    $where = " AND client.status = false";
                    break;
            }

            if (!empty($client_name) && $client_name != 'Search')
                $where .= " AND client.name ilike '%{$client_name}%'";
        } else
        {
            $where = " AND client.status = true";
        }
        $where .= " AND client.client_type is null";
        $sst_user_id = $_SESSION['sst_user_id'];
        $count = $this->Client->getclients_count($sst_user_id, $where);

        //排序
        $where .= " order by $order_str ";

        require_once 'MyPage.php';
        $page = new MyPage ();
        $page->setTotalRecords($count);
        $page->setCurrPage($currPage);
        $page->setPageSize($pageSize);
        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;
        $data = $this->Client->getclients2($sst_user_id, $where, $pageSize, $offset);
        // echo "<pre>";
// die(var_dump($data));
        $page->setDataArray($data);
        $this->set('p', $page);
    }
    
    public function action_edit_panel($client_id)
    {
        Configure::write('debug', 0);
        $this->layout = 'ajax';
//        echo base64_decode($this->_get(['get_back_url']));die;
//        pr($this->params);die;
//        pr($this->data['Client']);die;
        if ($this->isPost()) {
            $this->data['Client']['client_id'] = $client_id;
            $this->Session->write('m', $this->Client->create_json(201, __('The Client [%s] is modified successfully!', true, $this->data['Client']['name'])));
            
            $orig_data = $this->Client->findByClientId($client_id);
             if ($this->data['Client']['allowed_credit'] != number_format(abs($orig_data['Client']['allowed_credit']), 3) || $this->data['Client']['unlimited_credit'] != $orig_data['Client']['unlimited_credit']) {
                $modified_from = $orig_data['Client']['unlimited_credit'] ? 'NULL' : $orig_data['Client']['allowed_credit'];
                $modified_to = $this->data['Client']['unlimited_credit'] ? 'NULL' : $this->data['Client']['allowed_credit'];
                $sql = "insert into credit_log(modified_by, modified_from, modified_to, modified_on, carrier_name) VALUES ('{$_SESSION['sst_user_name']}', $modified_from, $modified_to, CURRENT_TIMESTAMP(0), '{$this->data['Client']['name']}')";
                $this->Client->query($sql);
            }
//            pr($this->data);die;
            $this->data['Client']['allowed_credit'] = 0 - floatval(str_replace(',', '', $this->data['Client']['allowed_credit']));
            $this->Client->save($this->data);
            $this->Session->write('mm',2);
            $this->xredirect("/credit_managements/index?" .base64_decode($this->_get('get_back_url')));
        }
        $this->data = $this->Client->find('first', Array('conditions' => Array('client_id' => $client_id)));
    }
}