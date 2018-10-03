<?php

class PaymentsController extends AppController {

    var $name = 'Payments';
    var $helpers = array('form', 'AppCommon');
    var $uses = array('Invoice', 'PaymentHistory');

    public function beforeFilter() {
        $this->checkSession("login_type"); //核查用户身份
        parent::beforeFilter(); //调用父类方法
    }

    function unpaid_bills() {
        $this->pageTitle = "Management/Unpaid Bills Summary";
        $results = $this->Invoice->getUnpaidInvoices($this->_filter_conditions(array('search_name', 'range_t_invoice_time', 'paid_type')));
        $this->set('p', $results);
    }

    function _filter_search_name() {
        $value = (string) array_keys_value($this->params, "url.filter_search_name");
        $value = trim($value);
        $value = $this->quote_sql_string($value);
        if (!empty($value)) {
            return "(client.name like '%{$value}%' OR invoice.client_id::text like '%{$value}%' OR invoice_number LIKE '%{$value}%')";
        }
        return null;
    }

    function _filter_paid_type() {
        $value = (string) array_keys_value($this->params, "url.filter_paid_type");
        switch ($value) {
            case '1' : return null;
            case '2' : return "(pay_amount is null or pay_amount = 0)";
            case '3' : return "pay_amount > 0";
            default : return null;
        }
    }

    public function paypal_transaction() {

//        echo '<pre>';
//                print_r($this->params);die;
        $this->pageTitle = 'Finance/Paypal_transaction';
        $temp = isset($_SESSION['paging_row']) ? $_SESSION['paging_row'] : 100;
        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = $temp : $pageSize = $_GET['size'];
        $_SESSION['paging_row'] = $pageSize;
        $where = "";
        $client_id = "";

        $start_date = "";

        $end_date = "";

        $payment_id = "";
        if (isset($this->params['url']['submit'])) {
            if ($this->params['url']['carrier']) {
                $client_id = $this->params['url']['carrier'];
                $where .= " and cl.client_id = '{$client_id}'";
            }

            if ($this->params['url']['end_date']) {
                $end_date = $this->params['url']['end_date'];
                $where .= " and modified_time <= '{$end_date}'";
            }
            if ($this->params['url']['start_date']) {
                $start_date = $this->params['url']['start_date'];
                $where .= " and modified_time >= '{$start_date}'";
            }
            if ($this->params['url']['payment_id']) {
                $payment_id = $this->params['url']['payment_id'];
                $where .= " and invoice_id like '%{$payment_id}%'";
            }
        }
        $this->set('client_id', $client_id);
        $this->set('payment_id', $payment_id);
        $this->set('start_date', $start_date);
        $this->set('end_date', $end_date);
        $count = $this->PaymentHistory->get_paypal_count($where);
        require_once 'MyPage.php';
        $page = new MyPage ();
        $page->setTotalRecords($count);
        $page->setCurrPage($currPage);
        $page->setPageSize($pageSize);
        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;

         $order_sql = "";

        if ($this->isnotEmpty($this->params['url'], array('order_by')))
        {
            $order_by = $this->params['url']['order_by'];
            $order_arr = explode('-', $order_by);
            if (count($order_arr) == 2)
            {
                $field = $order_arr[0];
                $sort = $order_arr[1];
                $order_sql = "ORDER BY {$field} {$sort}";
            }
        }    
        $data = $this->PaymentHistory->get_paypal_data($where, $pageSize, $offset,$order_sql);

        $client_arr = $this->PaymentHistory->query("select client_id,name from client");

        $this->set('client_arr', $client_arr);
        $page->setDataArray($data);
        $this->set('p', $page);
        $this->set('data', $data);
    }

}

?>