<?php

class Invoice extends AppModel {

    var $name = 'Invoice';
    var $useTable = 'invoice';
    var $primaryKey = 'invoice_id';

    const TYPE_BUY = 0;
    const TYPE_SELL = 1;

    function get_unpaid_invoice_count($where) {
        $sql = <<<EOT
   SELECT 

count(*)

FROM invoice 

WHERE 

$where

EOT;
        $result = $this->query($sql);
        return $result[0][0]['count'];
    }

    function get_unpaid_invoice($where, $pageSize, $offset,$order_sql = "") {
        if(! $order_sql)
        {
            $order_sql = "ORDER BY invoice.invoice_id DESC";
        }
        $sql = <<<EOT
   SELECT 

invoice.invoice_number,

(SELECT name FROM client WHERE client_id = invoice.client_id) AS carrier_name,

invoice.invoice_time,

invoice.due_date,

invoice.total_amount,

invoice.pay_amount,

(SELECT COALESCE(sum(amount), 0)
FROM client_payment 
WHERE invoice_number = invoice.invoice_number and (payment_type = 7 or payment_type = 8)) AS credit_note,

(SELECT COALESCE(sum(amount), 0)
FROM client_payment 
WHERE invoice_number = invoice.invoice_number and (payment_type = 11 or payment_type = 12)) AS debit_note,

(SELECT COALESCE(sum(amount), 0) FROM client_payment WHERE invoice_number = invoice.invoice_number and (payment_type = 3 or payment_type = 4)) AS payment,

case when invoice.invoice_zone is null then invoice_start::text else (invoice_start AT TIME ZONE (substring(invoice.invoice_zone for 3)||':00')::INTERVAL)||invoice.invoice_zone::TEXT end as invoice_start, case when invoice.invoice_zone is null then invoice_end::text else (invoice_end AT TIME ZONE (substring(invoice.invoice_zone for 3)||':00')::INTERVAL)||invoice.invoice_zone::TEXT end as invoice_end,
            
type

FROM invoice 

WHERE 

$where

{$order_sql} LIMIT $pageSize OFFSET $offset
EOT;
        return $this->query($sql);
    }

    public function get_carriers() {
        $sql = "SELECT client_id, name FROM client ORDER BY name ASC";
        return $this->query($sql);
    }

    function get_total_due_info($client_id) {
        $sql = "SELECT * FROM invoice WHERE client_id = {$client_id} ORDER BY invoice_end ASC";
        $total = 0;
        $invoice_time = '';
        foreach ($this->query($sql) as $invoice) {
            $invoice = $invoice[0];
            if ($invoice) {
                $invoice_time = $invoice['invoice_end'];
                $total += (float) $invoice['buy_total'] - (float) $invoice['sell_total'] +
                        (float) $invoice['buy_service_charge'] + (float) $invoice['sell_service_charge'] - (float) $invoice['pay_amount'];
            }
        }
        return compact('total', 'invoice_time');
    }

    public function getInvoices($create_type, $order = null) {
        if (empty($order)) {
            $order = "invoice_id  desc";
        }
        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = 10 : $pageSize = $_GET['size'];

//分页信息
        require_once 'MyPage.php';
        $page = new MyPage();
        $login_type = $_SESSION['login_type'];
        $privilege = ''; //权限条件
        $create_type_where = "  and invoice.create_type={$create_type} ";
        if ($login_type == 3) {
            $privilege = "  and invoice.client_id={$_SESSION['sst_client_id']} ";
            $create_type_where = '';
        }
//模糊搜索
        $like_where = !empty($_GET['search']) ? " and ( client.name ilike '%{$_GET['search']}%'  or  invoice.client_id::text ilike '%{$_GET['search']}%'  or
	invoice_number ilike '%{$_GET['search']}%')" : '';
        $invoice_start_where = !empty($_GET['invoice_start']) ? "  and invoice_start<='{$_GET['invoice_start']}'" : '';
        $invoice_end_where = !empty($_GET['invoice_end']) ? "  and invoice_end>='{$_GET['invoice_end']}'" : '';
        $number_where = !empty($_GET['invoice_number']) ? " and invoice_number='{$_GET['invoice_number']}'" : '';
        $disputed_where = (isset($_GET['disputed'])) ? " and disputed={$_GET['disputed']}" : '';

        $due_inteval_where = (!empty($_GET['due_inteval_type']) && isset($_GET['due_inteval']) && !empty($_GET['due_inteval'])) ? " and ((  date_part('day',due_date)-date_part('day',now())){$_GET['due_inteval_type']}'{$_GET['due_inteval']}')" : '';
        //是否付清
        $paid_where = !empty($_GET['paid']) ? " and paid={$_GET['paid']}" : '';
        $type_where = !empty($_GET['type']) ? " and type={$_GET['type']}" : '';
        $state_where = !empty($_GET['state']) ? " and (state={$_GET['state']})" : '';
        $client_where = !empty($_GET ['query'] ['id_clients']) ? "  and invoice.client_id::integer={$_GET ['query'] ['id_clients']}" : '';
        //按时间搜索
        $date_where = '';
        if (!empty($_GET['start_date']) || !empty($_GET['end_date'])) {
            $start = !empty($_GET['start_date']) ? $_GET['start_date'] : date("Y-m-1  00:00:00");
            $end = !empty($_GET['end_date']) ? $_sGET['end_date'] : date("Y-m-d 23:59:59");
            $date_where = "  and  (invoice_time  between   '$start'  and  '$end')";
        }
        $pay_mode_condtion = '';
        if (!empty($_GET['pay_mode'])) {
            $pay_mode_condtion = " and client.mode = {$_GET['pay_mode']}";
        }

        $totalrecords = $this->query("select count(*) as c from invoice
inner join client   on client.client_id=invoice.client_id
	 where 1=1  $create_type_where $disputed_where
	  $like_where  $invoice_start_where  $invoice_end_where $number_where
   $paid_where  $type_where   $state_where $due_inteval_where $pay_mode_condtion
	  $client_where      $privilege");
        $page->setTotalRecords($totalrecords[0][0]['c']); //总记录数
        $page->setCurrPage($currPage); //当前页
        $page->setPageSize($pageSize); //页大小
        //$page = $page->checkRange($page);//检查当前页范围

        $currPage = $page->getCurrPage() - 1;

        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;
        $sql = "select disputed, invoice_id, invoice_number, state, type, invoice.client_id, send_time, invoice_time, case when invoice_zone is null then invoice_start||'+0000'::text
else
(invoice_start AT TIME ZONE  (substring(invoice_zone for 3))::INTERVAL)||invoice_zone::TEXT
end as invoice_start,
case when invoice_zone is null then invoice_end||'+0000'::text
else
(invoice_end AT TIME ZONE   (substring(invoice_zone for 3))::INTERVAL)||invoice_zone::TEXT
end as invoice_end, total_amount::numeric(30,5), paid, due_date, pay_amount::numeric(30,5), (select balance from c4_client_balance where client_id=invoice.client_id::text)::numeric(30,5) as balance, pdf_path, cdr_path, (select COALESCE(sum(amount),0) from client_payment where invoice_number = invoice.invoice_number)::numeric(30,5) as invoice_payment,
(select sum(total_amount) as past_due from invoice as inner_invoice where client_id = invoice.client_id and inner_invoice.invoice_end <= invoice.invoice_end)  as total, (  date_part('day',due_date)-date_part('day',now()))  as  due_inteval, client.name as client, attach_cdrs_list, client.invoice_show_details, (select COALESCE(sum(amount),0) from client_payment where client_id = client.client_id and payment_type=2)::numeric(30,5) -client.allowed_credit::numeric(30,5) as client_credit  from invoice	inner join client  on client.client_id=invoice.client_id		where  1=1    $create_type_where $disputed_where	 $like_where  $invoice_start_where  $invoice_end_where  $due_inteval_where $paid_where  $type_where  $state_where  $number_where  $client_where $pay_mode_condtion  $privilege";
        $sql .= "   order by $order  	limit '$pageSize' offset '$offset'";
        $results = $this->query($sql);
        $page->setDataArray($results);
        return $page;
    }

    public function getUnpaidInvoices($conditions = null) {
        if (!empty($conditions)) {
            $conditions = ' AND ' . $conditions;
        }
        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = 10 : $pageSize = $_GET['size'];

        //分页信息
        require_once 'MyPage.php';
        $page = new MyPage();
        $totalrecords = $this->query(
                "SELECT count(*) AS c FROM invoice 
		LEFT JOIN client   ON (client.client_id=invoice.client_id)
	WHERE 1=1    $conditions");
        $page->setTotalRecords($totalrecords[0][0]['c']); //总记录数
        $page->setCurrPage($currPage); //当前页
        $page->setPageSize($pageSize); //页大小
        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;
        $sql = "select invoice_id,invoice_number,state,type,invoice.client_id,invoice_time,send_time,invoice_start,invoice_end,total_amount::numeric(30,5),paid,due_date,
pay_amount::numeric(30,5),current_balance::numeric(30,5),
(select sum(total_amount) as past_due from invoice as inner_invoice where client_id = invoice.client_id and inner_invoice.invoice_end <= invoice.invoice_end)  as total,

  (  date_part('day',due_date)-date_part('day',now()))  as  due_inteval 
,client.name as client    ,client.invoice_show_details  from invoice
		left join client  on client.client_id=invoice.client_id
		where  1=1  $conditions";


        $sql .= "   	limit '$pageSize' offset '$offset'";
        $results = $this->query($sql);

        $page->setDataArray($results);
        return $page;
    }

    ///////////////INVOICE PDF 用的///////////////////////////

    function pdf_show_number($content) {
        $fcontent = (float) $content;
        if ($fcontent) {
            if ($fcontent < 0) {
                return '(' . str_replace('-', '', $content) . ')';
            } else {
                return $content;
            }
        } else {
            return '0';
        }
    }

    function pdf_get_past_due($invoice) {
//		$total = 0;
//		$invoice_no = '';
//		$sql = "SELECT * FROM invoice WHERE client_id = {$invoice['client_id']} AND invoice_time::date < '{$invoice['invoice_time']}' ORDER BY invoice_time DESC LIMIT 2";
//		$invoices = $this->query($sql);
//		foreach($invoices as $i){
//			if($i[0]['type'] == self::TYPE_BUY){
//				$payment = $this->query("select sum(amount) as val from client_payment where result = true and client_id = {$i['client_id']} and payment_type = 1 and invoice_number = '{$invoice[0]['invoice_number']}'");			
//				$total += (float)$i[0]['total_amount'] - (float)$payment[0][0]['val'];
//				$invoice_no = $i[0]['invoice_number'] ;				
//			}
//			if($invoice[0]['type'] == self::TYPE_SELL){
//				$total -= (float)$i[0]['total_amount'];
//				$invoice_no = $i[0]['invoice_number'];	
//			}
//		}	
//		
//		if($invoice_no){
//			$sql = "SELECT 	SUM(less_rate_charges) as less_rate_charges,
//							SUM(greater_rate_charges) as greater_rate_charges ,
//							SUM(greater_max_rate_charges) as greater_max_rate_charges 
//					FROM invoice_service_charge 
//					WHERE invoice_no = '$invoice_no'";		
//			foreach($this->query($sql) as $payment){
//				$total += (float)$payment[0]['less_rate_charges'] + (float)$payment[0]['greater_rate_charges'] + (float)$payment[0]['greater_max_rate_charges'];
//			}
//		}
//		return $total;
        $sql = "SELECT * FROM invoice WHERE client_id = {$invoice['client_id']} AND invoice_end::date < '{$invoice['invoice_end']}'";
        $total = 0;
        foreach ($this->query($sql) as $invoice) {
            $invoice = $invoice[0];
            if ($invoice) {
                $total += (float) $invoice['buy_total'] - (float) $invoice['sell_total'] +
                        (float) $invoice['buy_service_charge'] + (float) $invoice['sell_service_charge'] - (float) $invoice['pay_amount'];
            }
        }
        return $total;
    }

    function pdf_get_minutes_bought($invoice) {
        $html = <<<EOD
		<span style=""> </span><br />
		<span style="font-size:32px;font-weight:bold">Summary of Minutes Bought</span><br />
		<table cellpadding="1" cellspacing="0" border="1" nobr="true">
		<tr style="background-color:#ddd;text-align:center;">
		<td>Code Name</td><td>Completed Calls</td><td>Total Minutes</td><td>Average Rate(US)</td><td>Total Charges(US)</td>
		</tr>
EOD;
        $total = 0;
        $sql = "SELECT * FROM invoice_calls WHERE invoice_type=0 AND invoice_no = '{$invoice['invoice_number']}'";
        foreach ($this->query($sql) as $invoice_call) {
            $calls = number_format($invoice_call[0]['calls_count']);
            $mins = number_format($invoice_call[0]['total_minutes']);
            $avg_rate = number_format($invoice_call[0]['avg_rate'], 5, '.', ',');
            $cost = number_format($invoice_call[0]['cost'], 5, '.', ',');
            $total += $invoice_call[0]['cost'];
            $html .= <<<EOD
		<tr>
			<td>{$invoice_call[0]['code_name']}</td>
			<td style="text-align:center;">{$calls}</td>
			<td style="text-align:center;">{$mins}</td>
			<td style="text-align:center;">{$avg_rate}</td>
			<td style="text-align:center;">{$cost}</td>
		</tr>
EOD;
        }
        $total = number_format($total, 5, '.', ',');
        $html .= <<<EOD
		<tr>
		<td><b>Total</b></td><td></td><td></td><td></td><td  style="text-align:center;">{$total}</td>
		</tr>
		</table>
EOD;
        return $html;
    }

    function pdf_get_minutes_sell($invoice) {
        $html = <<<EOD
		<span style=""> </span><br />
		<span style="font-size:32px;font-weight:bold">Summary of Minutes Sold</span><br />
		<table cellpadding="1" cellspacing="0" border="1" nobr="true">
		<tr style="background-color:#ddd;text-align:center;">
		<td>Code Name</td><td>Completed Calls</td><td>Total Minutes</td><td>Average Rate(US)</td><td>Total Charges(US)</td>
		</tr>
EOD;
        $total = 0;
        $sql = "SELECT * FROM invoice_calls WHERE invoice_type=1 AND invoice_no = '{$invoice['invoice_number']}'";
        foreach ($this->query($sql) as $invoice_call) {
            $calls = number_format($invoice_call[0]['calls_count']);
            $mins = number_format($invoice_call[0]['total_minutes']);
            $avg_rate = number_format($invoice_call[0]['avg_rate'], 5, '.', ',');
            $cost = number_format($invoice_call[0]['cost'], 5, '.', ',');
            $total += $invoice_call[0]['cost'];
            $html .= <<<EOD
		<tr>
			<td>{$invoice_call[0]['code_name']}</td>
			<td style="text-align:center;">{$calls}</td>
			<td style="text-align:center;">{$mins}</td><td  style="text-align:center;">{$avg_rate}</td>
			<td style="text-align:center;">{$cost}</td>
		</tr>
EOD;
        }
        $total = number_format($total, 5, '.', ',');
        $html .= <<<EOD
		<tr>
		<td><b>Total</b></td><td></td><td></td><td></td><td  style="text-align:center;">{$total}</td>
		</tr>
		</table>
EOD;
        return $html;
    }

    function pdf_get_service_charge($invoice) {
        $html = <<<EOD
		<span style=""> </span><br />
		<span style="font-size:32px;font-weight:bold">Summary of Service Charge</span><br />
		<table cellpadding="1" cellspacing="0" border="1" nobr="true">
		<tr style="background-color:#ddd;text-align:center;">
		<td>Value of trade minute</td><td>Buy/Sell</td><td>Minutes</td><td>Average Fee(US)</td><td>Total Charges(US)</td>
		</tr>
EOD;
        $total = 0;
        $sql = "SELECT * FROM invoice_service_charge WHERE invoice_type=1 AND invoice_no = '{$invoice['invoice_number']}'";
        foreach ($this->query($sql) as $charge) {
            $rate = number_format($charge[0]['rate'], 2, '.', ',');
            $max_rate = number_format($charge[0]['max_rate'], 2, '.', ',');

            $less_rate_usage_fee = number_format($charge[0]['less_rate_usage_fee'], 5, '.', ',');
            $less_rate_charges = number_format($charge[0]['less_rate_charges'], 5, '.', ',');
            $less_rate_minutes = number_format($charge[0]['less_rate_minutes']);

            $greater_rate_usage_fee = number_format($charge[0]['greater_rate_usage_fee'], 5, '.', ',');
            $greater_rate_charges = number_format($charge[0]['greater_rate_charges'], 5, '.', ',');
            $greater_rate_minutes = number_format($charge[0]['greater_rate_minutes']);

            $greater_max_rate_usage_fee = number_format($charge[0]['greater_max_rate_usage_fee'], 5, '.', ',');
            $greater_max_rate_charges = number_format($charge[0]['greater_max_rate_charges'], 5, '.', ',');
            $greater_max_rate_minutes = number_format($charge[0]['greater_max_rate_minutes']);

            $total += $charge[0]['less_rate_charges'];
            $total += $charge[0]['greater_rate_charges'];
            $total += $charge[0]['greater_max_rate_charges'];

            $html .= <<<EOD
		<tr>
			<td>&lt;{$rate}</td>
			<td style="text-align:center;">Sell</td>
			<td style="text-align:center;">{$less_rate_minutes}</td>
			<td style="text-align:center;">{$less_rate_usage_fee}</td>
			<td style="text-align:center;">{$less_rate_charges}</td>
		</tr>
		<tr>
			<td>&gt;={$rate} and &lt;{$max_rate}</td>
			<td style="text-align:center;">Sell</td>
			<td style="text-align:center;">{$greater_rate_minutes}</td>
			<td style="text-align:center;">{$greater_rate_usage_fee}</td>
			<td style="text-align:center;">{$greater_rate_charges}</td>
		</tr>
		<tr>
			<td>&gt;={$max_rate}</td>
			<td style="text-align:center;">Sell</td>
			<td style="text-align:center;">{$greater_max_rate_minutes}</td>
			<td style="text-align:center;">{$greater_max_rate_usage_fee}</td>
			<td style="text-align:center;">{$greater_max_rate_charges}</td>
		</tr>
EOD;
        }
        $sql = "SELECT * FROM invoice_service_charge WHERE invoice_type=0 AND invoice_no = '{$invoice['invoice_number']}'";
        foreach ($this->query($sql) as $charge) {
            $rate = number_format($charge[0]['rate'], 2, '.', ',');
            $max_rate = number_format($charge[0]['max_rate'], 2, '.', ',');

            $less_rate_usage_fee = number_format($charge[0]['less_rate_usage_fee'], 5, '.', ',');
            $less_rate_charges = number_format($charge[0]['less_rate_charges'], 5, '.', ',');
            $less_rate_minutes = number_format($charge[0]['less_rate_minutes']);

            $greater_rate_usage_fee = number_format($charge[0]['greater_rate_usage_fee'], 5, '.', ',');
            $greater_rate_charges = number_format($charge[0]['greater_rate_charges'], 5, '.', ',');
            $greater_rate_minutes = number_format($charge[0]['greater_rate_minutes']);

            $greater_max_rate_usage_fee = number_format($charge[0]['greater_max_rate_usage_fee'], 5, '.', ',');
            $greater_max_rate_charges = number_format($charge[0]['greater_max_rate_charges'], 5, '.', ',');
            $greater_max_rate_minutes = number_format($charge[0]['greater_max_rate_minutes']);

            $total += $charge[0]['less_rate_charges'];
            $total += $charge[0]['greater_rate_charges'];
            $total += $charge[0]['greater_max_rate_charges'];

            $html .= <<<EOD
		<tr>
			<td>&lt;{$rate}</td>
			<td style="text-align:center;">Buy</td>
			<td style="text-align:center;">{$less_rate_minutes}</td>
			<td style="text-align:center;">{$less_rate_usage_fee}</td>
			<td style="text-align:center;">{$less_rate_charges}</td>
		</tr>
		<tr>
			<td>&gt;={$rate} and &lt;{$max_rate}</td>
			<td style="text-align:center;">Buy</td>
			<td style="text-align:center;">{$greater_rate_minutes}</td>
			<td style="text-align:center;">{$greater_rate_usage_fee}</td>
			<td style="text-align:center;">{$greater_rate_charges}</td>
		</tr>
		<tr>
			<td>&gt;={$max_rate}</td>
			<td style="text-align:center;">Buy</td>
			<td style="text-align:center;">{$greater_max_rate_minutes}</td>
			<td style="text-align:center;">{$greater_max_rate_usage_fee}</td>
			<td style="text-align:center;">{$greater_max_rate_charges}</td>
		</tr>
EOD;
        }
        $_total = number_format($total, 5, '.', ',');
        $html .= <<<EOD
		<tr>
		<td><b>Total</b></td><td></td><td></td><td></td><td style="text-align:center;">$_total</td>
		</tr>
		</table>
EOD;
        return compact('html', 'total');
    }

    function generate_pdf_content($invoice_number) {

        //$invoice = $this->find("invoice_number = '$invoice_number'");
        if (empty($invoice_number)) {
            return '';
        }
        $invoice = $this->query("select * from invoice where invoice_number = '$invoice_number'");
        //var_dump($invoice);
        $invoice = $invoice ? $invoice[0][0] : null;

        $system_params = $this->query("select * from system_parameter");
        $pdf_tpl = str_replace("\n", "<br />", $system_params[0][0]['pdf_tpl']);

        $invoice_time = date("M j Y", strtotime($invoice['invoice_time']));
        $due_date = date("M j Y", strtotime($invoice['due_date']));
        $billing_period = date("m/j/Y", strtotime($invoice['invoice_start'])) . '-' . date("m/j/Y", strtotime($invoice['invoice_end']));
        $bought_total = number_format($invoice['buy_total'], 5, '.', ',');
        $sold_total = number_format($invoice['sell_total'], 5, '.', ',');
        $bought_minutes = number_format($invoice['buy_minutes']);
        $sold_minutes = number_format($invoice['sell_minutes']);

        $past_due = $this->pdf_get_past_due($invoice);
        $summary_of_minutes_bought = $this->pdf_get_minutes_bought($invoice);
        $summary_of_minutes_sell = $this->pdf_get_minutes_sell($invoice);
        $summary_of_service_charge = $this->pdf_get_service_charge($invoice);

        $serice_charge_total = number_format((float) $invoice['buy_service_charge'] + (float) $invoice['sell_service_charge'], 5, '.', ',');
        $total = (float) $invoice['buy_total'] - (float) $invoice['sell_total'] + (float) $invoice['buy_service_charge'] + (float) $invoice['sell_service_charge'];

        $_total = $this->pdf_show_number(number_format($total, 5, '.', ','));
        $_past_due = $this->pdf_show_number(number_format($past_due, 5, '.', ','));
        $_total_due = $this->pdf_show_number(number_format($total + $past_due, 5, '.', ','));
        $client = $this->query("select * from client where client_id = " . $invoice['client_id']);
        $member_name = '';
        $_link_cdr = '';
        if ($client) {
            $member_name = $client[0][0]['name'];
            if ($client[0][0]['is_link_cdr'] && !empty($invoice['link_cdr'])) {
                $_link_cdr = '<a href="' . $invoice['link_cdr'] . '">My CDR Reports</a><br />';
            }
        }
        $member_number = 12345 + $invoice['client_id'];
        return <<<EOD
		<div style="width:100%;text-align:center;margin-top:0px;"><span style="font-size:42px;font-weight:bold">International Carrier Exchange Ltd</span></div>
		<div style="height:10px;"></div>
		<table cellpadding="1" cellspacing="0" border="1" nobr="true" width="100%">
		<tr>
		<th>Member Name</th>
		<td>{$member_name}</td>
		<th>Invoice Number</th>
		<td>{$invoice['invoice_number']}</td>
		</tr>
		<tr>
		<td>Member Number</td>
		<td>{$member_number}</td>
		<th>Invoice Date</th>
		<td>{$invoice_time}</td>
		</tr>
		<tr>
		<td></td>
		<td></td>
		<th>Payment Due Date</th>
		<td>{$due_date}</td>
		</tr>
		<tr>
		<td></td>
		<td></td>
		<th>Billing Period</th>
		<td>{$billing_period}</td>
		</tr>
		</table>
		<br/>
		</center>
		<span style=""> </span><br />
		<span style="font-size:32px;font-weight:bold">Summary of Trades and Charges</span><br />
		<table cellpadding="1" cellspacing="0" border="1" nobr="true" width="100%"  >
		<tr style="background-color:#ddd;text-align:center;">
		<td>&nbsp;</td><td>Total Minutes</td><td>Total Charges(US)</td>
		</tr>
		<tr>
		<td>Minutes Bought</td><td style="text-align:center;">{$bought_minutes}</td><td  style="text-align:center;">{$bought_total}</td>
		</tr>
		<tr>
		<td>Minutes Sold</td><td style="text-align:center;">{$sold_minutes}</td><td  style="text-align:center;">{$sold_total}</td>
		</tr>
		<tr>
		<td>Service Charges</td><td></td><td  style="text-align:center;">{$serice_charge_total}</td>
		</tr>
		<tr>
		<td></td><td>Total</td><td  style="text-align:center;">{$_total}</td>
		</tr>
		<tr>
		<td></td><td>Past Due Amount</td><td  style="text-align:center;">{$_past_due}</td>
		</tr>
		<tr>
		<td></td><td>Total Due</td><td style="text-align:center;">{$_total_due}</td>
		</tr>
		</table>
		$summary_of_minutes_bought
		$summary_of_minutes_sell
		{$summary_of_service_charge['html']}
		<br />
		{$_link_cdr}
		{$pdf_tpl}
EOD;
    }

}

?>
