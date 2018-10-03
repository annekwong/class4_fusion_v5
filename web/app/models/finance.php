<?php
class Finance extends AppModel{
//	var $name = 'Finance';
	var $useTable = false;
//	var $primaryKey = 'id';

	public function ListFinance($currPage=1, $pageSize=15, $search_arr=array(), $search_type = 0, $order_arr=array())
	{
		$action_type=empty($_GET['action_type'])?'2':$_GET['action_type'];
		require_once 'MyPage.php';

		$page = new MyPage();

		$totalrecords = 0;

		$sql_where = '';
		if ($search_type == 1)
		{
			if (!empty($search_arr['action_type']))
			{
				$sql_where .= " and  action_type = ".intval($action_type);
			}
			if (isset($search_arr['status']) && $search_arr['status'] !== '')
			{
//				$sql_where .= " and  exchange_finance.status = ".intval($search_arr['status']);
			}
			if (!empty($search_arr['descript']))
			{
				$sql_where .= " and descript like '%".addslashes($search_arr['descript'])."%'";
			}
			if (!empty($search_arr['start_date']))
			{
				$sql_where .= " and  action_time >= '".addslashes($search_arr['start_date'])."'";
			}
			if (!empty($search_arr['end_date']))
			{
				$sql_where .= " and  action_time <= '".addslashes($search_arr['end_date'])."'";
			}
		}
		else
		{
			if (!empty($search_arr['search']))
			{
				$sql_where .= " and (action_number ilike '%".addslashes($search_arr['search'])."%' or descript like '%".addslashes($search_arr['search'])."%' or client.name ilike '%".addslashes($search_arr['search'])."%')";
			}
		}
		$sql_order = '';
		if (!empty($order_arr))
		{
				$sql_order = ' order by ';
				foreach ($order_arr as $k=>$v)
				{
					$sql_order .= $k . ' ' . $v;
				}
		}

//		$sql = "select count(id) as c from exchange_finance left join client on exchange_finance.client_id=client.client_id where true".$sql_where;
//		$totalrecords = $this->query($sql);
	 	//echo $pageSize;
                $_SESSION['paging_row'] = $pageSize;
//		$page->setTotalRecords($totalrecords[0][0]['c']);//总记录数
		$page->setCurrPage($currPage);//当前页
		$page->setPageSize($pageSize);//页大小

		//$page = $page->checkRange($page);//检查当前页范围

		$currPage = $page->getCurrPage()-1;
		$pageSize = $page->getPageSize();
		$offset = $currPage * $pageSize;


		//查询Client groups
//		$sql = "select exchange_finance.*,client.name from exchange_finance left join client on exchange_finance.client_id=client.client_id where 1=1 and action_type =$action_type ".$sql_where.$sql_order;

//		$sql .= " limit '$pageSize' offset '$offset'";
//
		//echo $sql;
//		$results = $this->query($sql);

//		$page->setDataArray($results);//Save Data into $page
		//////////////////////////////////////////

		return $page;
	}

        public function ListAllFinance($search_arr=array(), $search_type = 0, $order_arr=array())
	{
		$action_type=empty($_GET['action_type'])?'2':$_GET['action_type'];
		require_once 'MyPage.php';

		$page = new MyPage();


		$sql_where = '';
		if ($search_type == 1)
		{
			if (!empty($search_arr['action_type']))
			{
				$sql_where .= " and  action_type = ".intval($action_type);
			}
			if (isset($search_arr['status']) && $search_arr['status'] !== '')
			{
//				$sql_where .= " and  exchange_finance.status = ".intval($search_arr['status']);
			}
			if (!empty($search_arr['descript']))
			{
				$sql_where .= " and descript like '%".addslashes($search_arr['descript'])."%'";
			}
			if (!empty($search_arr['start_date']))
			{
				$sql_where .= " and  action_time >= '".addslashes($search_arr['start_date'])."'";
			}
			if (!empty($search_arr['end_date']))
			{
				$sql_where .= " and  action_time <= '".addslashes($search_arr['end_date'])."'";
			}
		}
		else
		{
			if (!empty($search_arr['search']))
			{
				$sql_where .= " and (action_number ilike '%".addslashes($search_arr['search'])."%' or descript like '%".addslashes($search_arr['search'])."%' or client.name ilike '%".addslashes($search_arr['search'])."%')";
			}
		}
		$sql_order = '';
		if (!empty($order_arr))
		{
				$sql_order = ' order by ';
				foreach ($order_arr as $k=>$v)
				{
					$sql_order .= $k . ' ' . $v;
				}
		}




		//查询Client groups
//		$sql = "select exchange_finance.*,client.name from exchange_finance left join client on exchange_finance.client_id=client.client_id where 1=1 and action_type =$action_type ".$sql_where.$sql_order;

		//echo $sql;
//		$results = $this->query($sql);

		//////////////////////////////////////////

//		return $results;
	}






    public function ListInvoice($currPage=1, $pageSize=15, $search_arr=array(), $order_arr=array())
	{
		require_once 'MyPage.php';

		$page = new MyPage();

		$totalrecords = 0;

		$sql_where = '';

                if (!empty($search_arr['carrier_name']))
                {
                        $sql_where .= " and (name ilike '%".addslashes($search_arr['carrier_name'])."%')";
                }
                if (!empty($search_arr['payment_term']))
                {
                        $sql_where .= " and (payment_term_id = '".addslashes($search_arr['payment_term'])."')";
                }
                if (!empty($search_arr['balance']))
                {
                        $sql_where .= " and ((select balance::real from c4_client_balance where client_id = client.client_id::text)  ".addslashes($search_arr['balance']).")";
                }
                if (!empty($search_arr['unin_amount']))
                {
                        //$sql_where .= " and (name ilike '%".addslashes($search_arr['carrier_name'])."%')";
                }

                if (!empty($search_arr['next_invoice_date']))
                {
                        $sql_where .= " and ((last_invoiced +  (select COALESCE(sum(days), 0) * interval '1 day' from payment_term where payment_term_id::text = client.payment_term_id::text))  ".($search_arr['next_invoice_date']).")";
                }
                if (!empty($search_arr['invoice_compare']))
                {
                        $sql_where .= " and ((select max(invoice_end)  from invoice group by client_id having invoice.client_id = client.client_id)  ".$search_arr['invoice_compare'].")";
                }


		$sql_order = '';
		if (!empty($order_arr))
		{
				$sql_order = ' order by ';
				foreach ($order_arr as $k=>$v)
				{
					$sql_order .= $k . ' ' . $v;
				}
		}

		$sql = "select client_id, name ,allowed_credit,(select balance from c4_client_balance where client_id = client.client_id::text) as balance,
                        (select name from payment_term where payment_term_id::text = client.payment_term_id::text) as payment_term,
                        last_invoiced +  (select COALESCE(sum(days), 0) * interval '1 day' from payment_term where payment_term_id::text = client.payment_term_id::text) as next_invoiced,
                        (select max(invoice_end)  from invoice group by client_id having invoice.client_id = client.client_id) as invoice_end,
                        (select sum(total_amount)  from invoice group by client_id having invoice.client_id = client.client_id) as total_amount,
                        (select sum(pay_amount)  from invoice group by client_id having invoice.client_id = client.client_id) as pay_amount,
   (SELECT COALESCE(sum(amount), 0) FROM client_payment where client_id = client.client_id
and (payment_type = 11 or payment_type = 12)) as debit_note,

(SELECT COALESCE(sum(amount), 0) FROM client_payment where client_id = client.client_id
and (payment_type = 7 or payment_type = 8)) as credit_note
                        from client where 1=1 ".$sql_where;

		$totalrecords = $this->query($sql);
	 	//echo $pageSize;
                $_SESSION['paging_row'] = $pageSize;
		$page->setTotalRecords(count($totalrecords));//总记录数
		$page->setCurrPage($currPage);//当前页
		$page->setPageSize($pageSize);//页大小

		//$page = $page->checkRange($page);//检查当前页范围

		$currPage = $page->getCurrPage()-1;
		$pageSize = $page->getPageSize();
		$offset = $currPage * $pageSize;


		//查询Client groups
		$sql = "select client_id, name ,allowed_credit,(select balance from c4_client_balance where client_id = client.client_id::text) as balance,
                        (select name from payment_term where payment_term_id::text = client.payment_term_id::text) as payment_term,
                        last_invoiced +  (select COALESCE(sum(days), 0) * interval '1 day' from payment_term where payment_term_id::text = client.payment_term_id::text) as next_invoiced,
                        (select max(invoice_end)  from invoice group by client_id having invoice.client_id = client.client_id) as invoice_end,
                        (select sum(total_amount)  from invoice group by client_id having invoice.client_id = client.client_id) as total_amount,
                        (select sum(pay_amount)  from invoice group by client_id having invoice.client_id = client.client_id) as pay_amount,
   (SELECT COALESCE(sum(amount), 0) FROM client_payment where client_id = client.client_id
and (payment_type = 11 or payment_type = 12)) as debit_note,

(SELECT COALESCE(sum(amount), 0) FROM client_payment where client_id = client.client_id
and (payment_type = 7 or payment_type = 8)) as credit_note
                        from client where 1=1 ".$sql_where.$sql_order;

		$sql .= " limit '$pageSize' offset '$offset'";
		//echo $sql;
		$results = $this->query($sql);

		$page->setDataArray($results);//Save Data into $page
		//////////////////////////////////////////

		return $page;
	}



         public function ListCarrierInvoice($currPage=1, $pageSize=15, $search_arr=array(), $order_arr=array())
	{
		require_once 'MyPage.php';

		$page = new MyPage();

		$totalrecords = 0;

		$sql_where = '';

                if (!empty($search_arr['search']))
                {
                        $sql_where .= " and (name ilike '%".addslashes($search_arr['search'])."%')";
                }

		$sql_order = '';
		if (!empty($order_arr))
		{
				$sql_order = ' order by ';
				foreach ($order_arr as $k=>$v)
				{
					$sql_order .= $k . ' ' . $v;
				}
		}

		$sql = "select count(invoice_id) as c from invoice left join client on client.client_id = invoice.client_id ".$sql_where;
		$totalrecords = $this->query($sql);
	 	//echo $pageSize;
                $_SESSION['paging_row'] = $pageSize;
		$page->setTotalRecords($totalrecords[0][0]['c']);//总记录数
		$page->setCurrPage($currPage);//当前页
		$page->setPageSize($pageSize);//页大小

		//$page = $page->checkRange($page);//检查当前页范围

		$currPage = $page->getCurrPage()-1;
		$pageSize = $page->getPageSize();
		$offset = $currPage * $pageSize;


		//查询Client groups
		$sql = "select * from (select invoice_id,(select name  from client where client_id::text = invoice.client_id::text) as name ,invoice_number, total_amount, invoice_start, invoice_end , due_date from invoice) as invo where 1=1 ".$sql_where.$sql_order;

		$sql .= " limit '$pageSize' offset '$offset'";
		//echo $sql;
		$results = $this->query($sql);

		$page->setDataArray($results);//Save Data into $page
		//////////////////////////////////////////

		return $page;
	}


        public function ListCarrierInvoiceLog($currPage=1, $pageSize=15, $search_arr=array(), $order_arr=array())
	{
		require_once 'MyPage.php';

		$page = new MyPage();

		$totalrecords = 0;

		$sql_where = ' and state = 9';

                //pr($search_arr);
                if (!empty($search_arr['search']))
                {
                        $sql_where .= " and (name ilike '%".addslashes($search_arr['search'])."%')";
                }
		if (!empty($search_arr['client_name']))
                {
                        $sql_where .= " AND name = '{$search_arr['client_name']}'";
                }
                if (!empty($search_arr['time_start']))
                {
                        $sql_where .= " AND invoice_time >= '{$search_arr['time_start']}'";
                }
                if (!empty($search_arr['time_end']))
                {
                        $sql_where .= " AND invoice_time <= '{$search_arr['time_end']}'";
                }

		$sql_order = '';
		if (!empty($order_arr))
		{
				$sql_order = ' order by ';
				foreach ($order_arr as $k=>$v)
				{
					$sql_order .= $k . ' ' . $v;
				}
		}

		$sql = "select count(invoice_id) as c from invoice left join client on client.client_id = invoice.client_id where 1=1 ".$sql_where;
		$totalrecords = $this->query($sql);
	 	//echo $pageSize;
                $_SESSION['paging_row'] = $pageSize;
		$page->setTotalRecords($totalrecords[0][0]['c']);//总记录数
		$page->setCurrPage($currPage);//当前页
		$page->setPageSize($pageSize);//页大小

		//$page = $page->checkRange($page);//检查当前页范围

		$currPage = $page->getCurrPage()-1;
		$pageSize = $page->getPageSize();
		$offset = $currPage * $pageSize;


		//查询Client groups
		$sql = "select * from (select invoice_number, state,invoice_id,invoice_time,(select name  from client where client_id::text = invoice.client_id::text) as name , total_amount , invoice_number,due_date from invoice) as invo where 1=1 ".$sql_where.$sql_order;

		$sql .= " limit '$pageSize' offset '$offset'";
		//echo $sql;
		$results = $this->query($sql);

		$page->setDataArray($results);//Save Data into $page
		//////////////////////////////////////////

		return $page;
	}


         public function ListCarrierInvoiceEmail($currPage=1, $pageSize=15, $search_arr=array(), $order_arr=array(),$invoice_number)
	{
		require_once 'MyPage.php';

		$page = new MyPage();

		$totalrecords = 0;

		$sql_where = " and invoice_no = '{$invoice_number}'";

                if (!empty($search_arr['search']))
                {
                        //$sql_where .= " and (name ilike '%".addslashes($search_arr['search'])."%')";
                }

		$sql_order = '';
		if (!empty($order_arr))
		{
				$sql_order = ' order by ';
				foreach ($order_arr as $k=>$v)
				{
					$sql_order .= $k . ' ' . $v;
				}
		}

		$sql = "select count(invoice_no) as c from invoice_email  where 1=1 ".$sql_where;
		$totalrecords = $this->query($sql);
	 	//echo $pageSize;
                $_SESSION['paging_row'] = $pageSize;
		$page->setTotalRecords($totalrecords[0][0]['c']);//总记录数
		$page->setCurrPage($currPage);//当前页
		$page->setPageSize($pageSize);//页大小

		//$page = $page->checkRange($page);//检查当前页范围

		$currPage = $page->getCurrPage()-1;
		$pageSize = $page->getPageSize();
		$offset = $currPage * $pageSize;


		//查询Client groups
		$sql = "select * from invoice_email where 1=1 ".$sql_where.$sql_order;

		$sql .= " limit '$pageSize' offset '$offset'";
		//echo $sql;
		$results = $this->query($sql);

		$page->setDataArray($results);//Save Data into $page
		//////////////////////////////////////////

		return $page;
	}





	/**
	 * 	增加client的client_balance
	 * @param $balance
	 * @param $clientId
	 */
	public function addClientBalance($balance, $clientId)
	{
		return $this->clientBalanceOperation($clientId, $balance, 1);
	}

	public function getFinanceInfo($id)
	{
		$return = false;

//		$query_sql = "select * from exchange_finance where id = {$id}";
//		$return = $this->query($query_sql);
//		return $return;
	}

        public function past_due_log($pageSize, $offset,$order_sql = "") {
            if(! $order_sql)
            {
                $order_sql = "ORDER BY id DESC";
            }
            $sql = "SELECT
invoice_email.id,
(SELECT name FROM client WHERE client_id = invoice.client_id) as carrier_name,
invoice_email.send_time, total_amount, invoice.due_date, pdf_file, send_address
FROM invoice_email
JOIN
invoice ON invoice_email.invoice_no = invoice.invoice_number
WHERE invoice.due_date < current_timestamp
{$order_sql} LIMIT {$pageSize} OFFSET {$offset}";
            return $this->query($sql);
        }

        public function past_due_log_count() {
            $sql = "SELECT count(*) FROM invoice_email
JOIN
invoice ON invoice_email.invoice_no = invoice.invoice_number
WHERE invoice.due_date < current_timestamp
";
            $result = $this->query($sql);
            return $result[0][0]['count'];
        }

        /*
        public function get_invoice_send($client_id) {
            $sql = "SELECT sum(total_amount) as amount,invoice_time::DATE FROM invoice
WHERE state = 9 AND type = 0 AND client_id = {$client_id} GROUP BY invoice_time::DATE ORDER BY 2 ASC";
            return $this->query($sql);
        }

        public function get_payment_received($client_id) {
            $sql = "SELECT sum(amount) as amount, payment_time::DATE FROM client_payment
WHERE  payment_type in (4,5) AND client_id = {$client_id} GROUP BY payment_time::DATE ORDER BY 2 ASC";
            return $this->query($sql);
        }

        public function get_credit_note_send($client_id) {
            $sql = "SELECT sum(amount) as amount, payment_time::DATE FROM client_payment
WHERE payment_type = 8 AND client_id = {$client_id} GROUP BY payment_time::DATE ORDER BY 2 ASC";
            return $this->query($sql);
        }

        public function get_debit_note_send($client_id) {
            $sql = "SELECT sum(amount) as amount, payment_time::DATE FROM client_payment
WHERE payment_type = 12 AND client_id = {$client_id} GROUP BY payment_time::DATE ORDER BY 2 ASC";
            return $this->query($sql);
        }

        public function get_mutual_reset($client_id) {
            $sql = "SELECT sum(amount) as amount, payment_time::DATE
FROM client_payment WHERE payment_type = 13 AND client_id = {$client_id} GROUP BY payment_time::DATE ORDER BY 2 ASC";
            return $this->query($sql);
        }
        */

        public function get_mutual_ingress_detail($client_id) {
            $sql = <<<EOT
SELECT sum(total_amount) as amount,invoice_time::DATE as time, 1 as type FROM invoice
WHERE state != -1 AND type = 0 AND client_id = $client_id GROUP BY invoice_time::DATE
UNION
SELECT sum(amount) as amount, receiving_time::DATE as time, 2 as type FROM client_payment
WHERE  payment_type in (4,5) AND client_id = $client_id GROUP BY receiving_time::DATE
UNION
SELECT sum(amount) as amount, payment_time::DATE as time, 3 as type FROM client_payment
WHERE payment_type = 8 AND client_id = $client_id GROUP BY payment_time::DATE
UNION
SELECT sum(amount) as amount, payment_time::DATE as time, 4 as type FROM client_payment
WHERE payment_type = 12 AND client_id = $client_id GROUP BY payment_time::DATE
UNION
SELECT amount, payment_time::DATE - 1 as time, 5 as type
FROM client_payment
INNER JOIN
(SELECT max(client_payment_id) FROM client_payment WHERE payment_type = 13 AND client_id = $client_id GROUP BY payment_time::DATE) as t
ON client_payment.client_payment_id = t.max
WHERE payment_type = 13 AND client_id = $client_id
ORDER BY 2, 3 ASC
EOT;
            return $this->query($sql);
        }

        public function get_mutual_egress_detail($client_id) {
            $sql = <<<EOT
SELECT sum(total_amount) as amount,invoice_time::DATE as time, 1 as type FROM invoice
WHERE type = 3 AND client_id = $client_id GROUP BY invoice_time::DATE
UNION
SELECT sum(amount) as amount, receiving_time::DATE as time, 2 as type FROM client_payment
WHERE  payment_type in (3, 6) AND client_id = $client_id GROUP BY receiving_time::DATE
UNION
SELECT sum(amount) as amount, payment_time::DATE, 3 as type FROM client_payment
WHERE payment_type = 7 AND client_id = $client_id GROUP BY payment_time::DATE
UNION
SELECT egress_amount as amount, payment_time::DATE - 1 as time, 5 as type
FROM client_payment
INNER JOIN
(SELECT max(client_payment_id) FROM client_payment WHERE payment_type = 13 AND client_id = $client_id GROUP BY payment_time::DATE) as t
ON client_payment.client_payment_id = t.max
WHERE payment_type = 13 AND client_id = $client_id
ORDER BY 2, 3 ASC
EOT;
            return $this->query($sql);
        }

        public function get_create_time($client_id)
        {
            $sql = "SELECT create_time FROM c4_client_balance WHERE client_id = '$client_id'";
            $result = $this->query($sql);
            return $result[0][0]['create_time'];
        }

        public function get_ingress_cost($client_id, $start_time, $end_time)
        {
            $sql = "select sum(ingress_client_cost) as cost from client_cdr".date("Ymd")." where ingress_client_id = {$client_id} and is_final_call=1 and time between '{$start_time}' and '{$end_time}'";
            $result = $this->query($sql);
            return $result[0][0]['cost'];
        }

        public function get_egress_cost($client_id, $start_time, $end_time)
        {
            $sql = "select sum(egress_cost) as cost from client_cdr".date("Ymd")." where egress_client_id = {$client_id} and time between '{$start_time}' and '{$end_time}'";
            $result = $this->query($sql);
            return $result[0][0]['cost'];
        }



        public function get_actual_ingress_detail($client_id) {
            $sql = <<<EOT
SELECT sum(amount) as amount, receiving_time::DATE as time, 1 as type FROM client_payment
WHERE  payment_type in (4,5) AND client_id = $client_id GROUP BY receiving_time::DATE
UNION
SELECT sum(amount) as amount, payment_time::DATE as time, 2 as type FROM client_payment
WHERE payment_type = 8 AND client_id = $client_id GROUP BY payment_time::DATE
UNION
SELECT sum(amount) as amount, payment_time::DATE as time, 3 as type FROM client_payment
WHERE payment_type = 12 AND client_id = $client_id GROUP BY payment_time::DATE
UNION
select  sum(d::numeric) as amount, a::DATE as time, 4 as type
from actual_trans((SELECT create_time FROM c4_client_balance WHERE client_id = '$client_id'), CURRENT_TIMESTAMP, $client_id,11)
as t(a text,b text,c text,d text) GROUP BY a::date
UNION
SELECT amount, payment_time::DATE - 1 as time, 5 as type
FROM client_payment
INNER JOIN
(SELECT max(client_payment_id) FROM client_payment WHERE payment_type = 14 AND client_id = $client_id GROUP BY payment_time::DATE) as t
ON client_payment.client_payment_id = t.max
WHERE payment_type = 14 AND client_id = $client_id

UNION
SELECT sum(amount) as amount, payment_time::DATE as time, 6 as type FROM client_payment
WHERE payment_type = 15 AND client_id = $client_id GROUP BY payment_time::DATE

ORDER BY 2, 3 ASC
EOT;
            return $this->query($sql);
        }

        public function get_actual_egress_detail($client_id) {
            $sql = <<<EOT
SELECT sum(amount) as amount, receiving_time::DATE as time, 1 as type FROM client_payment
WHERE  payment_type in (3,6) AND client_id = $client_id GROUP BY receiving_time::DATE
UNION
SELECT sum(amount) as amount, payment_time::DATE as time, 2 as type FROM client_payment
WHERE payment_type = 7 AND client_id = $client_id GROUP BY payment_time::DATE
UNION
SELECT sum(amount) as amount, payment_time::DATE as time, 3 as type FROM client_payment
WHERE payment_type = 11 AND client_id = $client_id GROUP BY payment_time::DATE
UNION
select  sum(d::numeric) as amount, a::DATE as time, 4 as type
from actual_trans((SELECT create_time FROM c4_client_balance WHERE client_id = '$client_id'), CURRENT_TIMESTAMP, $client_id,10)
as t(a text,b text,c text,d text) GROUP BY a::date
UNION
SELECT egress_amount as amount, payment_time::DATE - 1 as time, 5 as type
FROM client_payment
INNER JOIN
(SELECT max(client_payment_id) FROM client_payment WHERE payment_type = 14 AND client_id = $client_id GROUP BY payment_time::DATE) as t
ON client_payment.client_payment_id = t.max
WHERE payment_type = 14 AND client_id = $client_id
ORDER BY 2, 3 ASC
EOT;
            return $this->query($sql);
        }




}
?>