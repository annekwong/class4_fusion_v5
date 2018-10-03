<?php

class BillsController extends AppController
{

    var $name = 'Bills';
    var $uses = array('Invoice');
    var $helpers = array('AppProduct');

    function summary()
    {
        $temp = isset($_SESSION['paging_row']) ? $_SESSION['paging_row'] : 100;
        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = $temp : $pageSize = $_GET['size'];
        $_SESSION['paging_row'] = $pageSize;
        require_once 'MyPage.php';

        $where_arr = array();

        if (isset($_GET['paid_type']) && !empty($_GET['paid_type']))
        {
            $paid_type = $_GET['paid_type'];
            if ($paid_type == '1')
                array_push($where_arr, "pay_amount < total_amount and pay_amount > 0 AND total_amount != 0 ");
            else if ($paid_type == "2")
                array_push($where_arr, "pay_amount  = 0 AND total_amount != 0");
        } else
        {
            array_push($where_arr, "pay_amount  = 0 AND total_amount != 0");
        }

        if (isset($_GET['carrier']) && !empty($_GET['carrier']))
        {
            $carrier = $_GET['carrier'];
            array_push($where_arr, "client_id = {$carrier}");
        }

        if (isset($_GET['direction']) && !empty($_GET['direction']))
        {
            $direction = $_GET['direction'];
            if ($direction == '1')
                array_push($where_arr, "type = 1");
            elseif ($direction == '2')
                array_push($where_arr, "type = 0");
        }

        if (isset($_GET['invoice_start_date']) && !empty($_GET['invoice_start_date']))
        {
            $invoice_start_date = $_GET['invoice_start_date'];
            array_push($where_arr, "invoice_time >= '{$invoice_start_date}'");
        }

        if (isset($_GET['invoice_end_date']) && !empty($_GET['invoice_end_date']))
        {
            $invoice_end_date = $_GET['invoice_end_date'];
            array_push($where_arr, "invoice_time <= '{$invoice_end_date}'");
        }

        if (isset($_GET['due_start_date']) && !empty($_GET['due_start_date']))
        {
            $due_start_date = $_GET['due_start_date'];
            array_push($where_arr, "due_date >= '{$due_start_date}'");
        }

        if (isset($_GET['due_end_date']) && !empty($_GET['due_end_date']))
        {
            $due_end_date = $_GET['due_end_date'];
            array_push($where_arr, "due_date <= '{$due_end_date}'");
        }

        if (isset($_GET['start_amt']) && !empty($_GET['start_amt']))
        {
            $start_amt = $_GET['start_amt'];
            array_push($where_arr, "total_amount > '{$start_amt}'");
        }

        if (isset($_GET['end_amt']) && !empty($_GET['end_amt']))
        {
            $end_amt = $_GET['end_amt'];
            array_push($where_arr, "total_amount < '{$end_amt}'");
        }

        if (isset($_GET['start_unpaid_amt']) && !empty($_GET['start_unpaid_amt']))
        {
            $start_unpaid_amt = $_GET['start_unpaid_amt'];
            array_push($where_arr, "pay_amount > {$start_unpaid_amt}");
        }

        if (isset($_GET['end_unpaid_amt']) && !empty($_GET['end_unpaid_amt']))
        {
            $end_unpaid_amt = $_GET['end_unpaid_amt'];
            array_push($where_arr, "pay_amount < {$end_unpaid_amt}");
        }

        if (isset($_GET['start_past_due']) && !empty($_GET['start_past_due']))
        {
            $start_past_due = $_GET['start_past_due'];
            array_push($where_arr, "(current_date - due_date) > {$start_past_due}");
        }

        if (isset($_GET['end_past_due']) && !empty($_GET['end_past_due']))
        {
            $end_past_due = $_GET['end_past_due'];
            array_push($where_arr, "(current_date - due_date) < {$end_past_due}");
        }

        array_push($where_arr, 'invoice.state != -1');

        $where = implode(' AND ', $where_arr);

        $counts = $this->Invoice->get_unpaid_invoice_count($where);
        $page = new MyPage ();
        $page->setTotalRecords($counts);
        $page->setCurrPage($currPage);
        $page->setPageSize($pageSize);
        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;

        //$order_sql = "order by id desc";
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

        $data = $this->Invoice->get_unpaid_invoice($where, $pageSize, $offset, $order_sql);
        $page->setDataArray($data);
        $this->set('p', $page);
        $this->_render_set_options('Client', Array('Client' => Array('order' => 'name asc')));
    }

    function _filter_search()
    {
        $search = $this->_get('search');
        if (!empty($search))
        {
            return "(Client.name like '%$search%' or Invoice.invoice_number like '%$search%')";
        }
        return null;
    }

    function _filter_paid_type()
    {
        $paid_type = $this->_get('paid_type');
        if ($paid_type == 1)
        {
            //return "Invoice.pay_amount::float=0";
            return "Invoice.pay_amount::float<Invoice.total_amount and Invoice.pay_amount > 0";
        }
        elseif ($paid_type == 2)
        {
            //return "Invoice.pay_amount::float <> 0";
            return "Invoice.pay_amount::float = 0";
        }
        else
        {
            return "Invoice.pay_amount::float<abs(Invoice.total_amount)";
        }
        return null;
    }

    function _filter_invoice_start_date()
    {
        $invoice_start_date = $this->_get('invoice_start_date');
        if (!empty($invoice_start_date))
        {
            return "Invoice.invoice_time>'$invoice_start_date'";
        }
        return null;
    }

    function _filter_invoice_end_date()
    {
        $invoice_end_date = $this->_get('invoice_end_date');
        if (!empty($invoice_end_date))
        {
            return "Invoice.invoice_time<'$invoice_end_date'";
        }
        return null;
    }

    function _filter_due_start_date()
    {
        $due_start_date = $this->_get('due_start_date');
        if (!empty($due_start_date))
        {
            return "Invoice.due_date >'$due_start_date'";
        }
        return null;
    }

    function _filter_due_end_date()
    {
        $due_end_date = $this->_get('due_end_date');
        if (!empty($due_end_date))
        {
            return "Invoice.due_date <'$due_end_date'";
        }
        return null;
    }

    function _filter_start_amt()
    {
        $start_amt = $this->_get('start_amt');
        if (isset($start_amt) && $start_amt !== '')
        {
            return "Invoice.total_amount>'$start_amt'";
        }
        return null;
    }

    function _filter_end_amt()
    {
        $end_amt = $this->_get('end_amt');
        if (isset($end_amt) && $end_amt !== '')
        {
            return "Invoice.total_amount<'$end_amt'";
        }
        return null;
    }

    function _filter_sql_condition()
    {
        return "Invoice.total_amount!=0";
    }

    /**
     * 获取所有payment的credit值和offset值
     */
    private function _get_client_payment()
    {
        $return = array();
        $sql = "select sum(amount) as credit,invoice_number from client_payment where payment_type = 2 and invoice_number != '' group by invoice_number";
        $result = $this->Invoice->query($sql);
        foreach ($result as $k => $v)
        {
            $return['credit'][$v[0]['invoice_number']] = $v[0]['credit'];
        }
        $sql = "select sum(amount) as offset,invoice_number from client_payment where payment_type = 3 and invoice_number != '' group by invoice_number";
        $result = $this->Invoice->query($sql);
        foreach ($result as $k => $v)
        {
            $return['offset'][$v[0]['invoice_number']] = $v[0]['offset'];
        }
        return $return;
    }

}

?>
