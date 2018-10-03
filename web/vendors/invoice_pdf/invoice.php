<?php
require_once(dirname(__FILE__).'/config/lang/eng.php');
require_once(dirname(__FILE__).'/tcpdf.php');
error_reporting(E_ALL);
$db=pg_connect("host=192.168.1.115  dbname=exchange user=root");

function show_number($content){
	$fcontent = (float)$content;
	if($fcontent){
		if($fcontent < 0){
			return '(' .str_replace('-','',$content).')';
		}else{
			return $content;
		}
	}else{
		return '0';
	}
}

//function get_past_due($client_id,$current_time){
//	$sql = "SELECT * FROM invoice WHERE client_id = $client_id AND invoice_time::date < '$current_time' ORDER BY invoice_time DESC LIMIT 2";
////	echo $sql."\n";
//	$rlts=pg_query($sql);
//	$array = array();
//	while ($line=pg_fetch_array($rlts,null,PGSQL_ASSOC)){
//		$array[] = $line;
//	}
//	pg_free_result($rlts);
//	$total = 0;
//	$invoice_no = '';
////	var_dump($array);
//	foreach($array as $a){
//		if($a['type'] == 0){
//			$payment = 0;
////			echo "select sum(amount) as val from client_payment where result = true and client_id = $client_id and payment_type = 1 and invoice_number = '{$a['invoice_number']}'";
//			$payment_rlts=pg_query("select sum(amount) as val from client_payment where result = true and client_id = $client_id and payment_type = 1 and invoice_number = '{$a['invoice_number']}'");
//			while ($line=pg_fetch_array($payment_rlts,null,PGSQL_ASSOC)){
//				$payment += (float)$line['val'];
//			}
//			pg_free_result($payment_rlts);
//			$total = (float)$a['total_amount'] - $payment;
//			$invoice_no = $a['invoice_number'] ;
//		}
//		if($a['type'] == 1){
//			$total -= (float)$a['total_amount'];
//			$invoice_no = $a['invoice_number'];
//		}
//	}
//	if($invoice_no){
//		$sql = "SELECT 	SUM(less_rate_charges) as less_rate_charges,
//						SUM(greater_rate_charges) as greater_rate_charges ,
//						SUM(greater_max_rate_charges) as greater_max_rate_charges
//				FROM invoice_service_charge
//				WHERE invoice_no = '$invoice_no'";
//		$rlts=pg_query($sql);
//		$array = array();
//		while ($line=pg_fetch_array($rlts,null,PGSQL_ASSOC)){
//			$array[] = $line;
//		}
//		pg_free_result($rlts);
//		foreach($array as $a){
//			$total += (float)$a['less_rate_charges'] + (float)$a['greater_rate_charges'] + (float)$a['greater_max_rate_charges'];
//		}
//	}
//	return $total;
//}

function get_past_due($client_id,$current_invoice_end){
	$sql = "SELECT * FROM invoice WHERE client_id = $client_id AND invoice_end::date < '$current_invoice_end'";
//	echo $sql;
	$rlts=pg_query($sql);
	$array = array();
	while ($line=pg_fetch_array($rlts,null,PGSQL_ASSOC)){
		$array[] = $line;
	}
	pg_free_result($rlts);
	$total = 0;
	foreach($array as $invoice){
		$total +=  (float)$invoice['buy_total'] - (float)$invoice['sell_total'] +
			 (float)$invoice['buy_service_charge'] + (float)$invoice['sell_service_charge']  - (float)$invoice['pay_amount'];
	}
	
	return $total;
}

function get_datas(){
	$sql="select invoice.*,client.name as client_name,client.is_link_cdr as is_link_cdr from invoice left join client on client.client_id = invoice.client_id where invoice_time::date=current_date order by invoice.client_id";
	$rlts=pg_query($sql);
	$array = array();
	while ($line=pg_fetch_array($rlts,null,PGSQL_ASSOC)){
		$array[] = $line;
	}
	pg_free_result($rlts);
	return $array;
}
//	$c_array = array();
//	foreach($array as $a){
//		if(!isset($c_array[$a['client_id']])){
//			$c_array[$a['client_id']] = array();
//		}
//		$c_array[$a['client_id']][] = $a;
//	}
//	return $c_array;


function get_all_client_ids($c_array){
	return array_keys($c_array);
}

//function filter_data($c_array,$client_id,$type){
//	if(isset($c_array[$client_id])){
//		foreach($c_array[$client_id] as $d){
//			if($d['type'] == $type){
//				return $d;
//			}
//		}
//	}
//	return null;
//}

function create_PDF($name,$html){
	$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
	$pdf->SetCreator(PDF_CREATOR);
	$pdf->SetHeaderData('ilogo.png', 30, "", "");
	$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
	$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
	$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
	$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
	$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
	$pdf->SetFont('helvetica', '', 10);
	$pdf->AddPage();
	$pdf->writeHTML($html, true, false, true, false, '');
	$pdf->Output($name.'.pdf', 'F');
	$pdf->Close();
}


function get_minutes_bought($d){
	$html =<<<EOD
		<div style="height: 10px;"></div>
		<span style="font-size:32px;font-weight:bold">Summary of Minutes Bought</span><br />
		<table cellpadding="1" cellspacing="0" border="1" nobr="true" style="margin: 0px;">
		<tr style="background-color: #ddd; text-align: center;">
		<td>Code Name</td><td>Completed Calls</td><td>Total Minutes</td><td>Average Rate(US)</td><td>Total Charges(US)</td>
		</tr>
EOD;
	$total  = 0;
	$rlts=pg_query("SELECT * FROM invoice_calls WHERE invoice_type=0 AND invoice_no = '{$d['invoice_number']}'");
	while ($line=pg_fetch_array($rlts,null,PGSQL_ASSOC)){
		$calls = number_format($line['calls_count']);
		$mins = number_format($line['total_minutes']);
		$avg_rate = number_format($line['avg_rate'],5,'.',',');
		$cost = number_format($line['cost'],5,'.',',');
		$total += $line['cost'];
		$html .= <<<EOD
		<tr>
			<td>{$line['code_name']}</td>
			<td style="text-align:center;">{$calls}</td>
			<td style="text-align:center;">{$mins}</td>
			<td style="text-align:center;">{$avg_rate}</td>
			<td style="text-align:center;">{$cost}</td>
		</tr>
EOD;
	}
	$total = number_format($total,5,'.',',');
	pg_free_result($rlts);
	$html .= <<<EOD
		<tr>
		<td><b>Total</b></td><td></td><td></td><td></td><td  style="text-align:center;">{$total}</td>
		</tr>
		</table>
EOD;
	return $html;
}

function get_minutes_sell($d){
	$html =<<<EOD
		<div style="height:10px;"></div>
		<span style="font-size:32px;font-weight:bold">Summary of Minutes Sold</span><br />
		<table cellpadding="1" cellspacing="0" border="1" nobr="true">
		<tr style="background-color:#ddd;text-align:center;">
		<td>Code Name</td><td>Completed Calls</td><td>Total Minutes</td><td>Average Rate(US)</td><td>Total Charges(US)</td>
		</tr>
EOD;
	$total  = 0;
	$rlts=pg_query("SELECT * FROM invoice_calls WHERE invoice_type=1 AND invoice_no = '{$d['invoice_number']}'");
	while ($line=pg_fetch_array($rlts,null,PGSQL_ASSOC)){
		$calls = number_format($line['calls_count']);
		$mins = number_format($line['total_minutes']);
		$avg_rate = number_format($line['avg_rate'],5,'.',',');
		$cost = number_format($line['cost'],5,'.',',');
		$total += $line['cost'];
		$html .= <<<EOD
		<tr>
			<td>{$line['code_name']}</td>
			<td style="text-align:center;">{$calls}</td>
			<td style="text-align:center;">{$mins}</td>
			<td  style="text-align:center;">{$avg_rate}</td>
			<td style="text-align:center;">{$cost}</td>
		</tr>
EOD;
	}
	$total = number_format($total,5,'.',',');
	pg_free_result($rlts);
	$html .= <<<EOD
		<tr>
		<td><b>Total</b></td><td></td><td></td><td></td><td  style="text-align:center;">{$total}</td>
		</tr>
		</table>
EOD;
	return $html;
}
function get_service_charge($d){
	$html =<<<EOD
		<div style="height:10px;"></div>
		<span style="font-size:32px;font-weight:bold">Summary of Service Charge</span><br />
		<table cellpadding="1" cellspacing="0" border="1" nobr="true">
		<tr style="background-color:#ddd;text-align:center;">
		<td>Value of trade minute</td><td>Buy/Sell</td><td>Minutes</td><td>Average Fee(US)</td><td>Total Charges(US)</td>
		</tr>
EOD;
	$total  = 0;
	$rlts=pg_query("SELECT * FROM invoice_service_charge WHERE invoice_type=1 AND invoice_no = '{$d['invoice_number']}'");
	while ($line=pg_fetch_array($rlts,null,PGSQL_ASSOC)){
		$rate = number_format($line['rate'],2,'.',',');
		$max_rate = number_format($line['max_rate'],2,'.',',');

		$less_rate_usage_fee = number_format($line['less_rate_usage_fee'],5,'.',',');
		$less_rate_charges = number_format($line['less_rate_charges'],5,'.',',');
		$less_rate_minutes = number_format($line['less_rate_minutes']);

		$greater_rate_usage_fee = number_format($line['greater_rate_usage_fee'],5,'.',',');
		$greater_rate_charges = number_format($line['greater_rate_charges'],5,'.',',');
		$greater_rate_minutes = number_format($line['greater_rate_minutes']);

		$greater_max_rate_usage_fee = number_format($line['greater_max_rate_usage_fee'],5,'.',',');
		$greater_max_rate_charges = number_format($line['greater_max_rate_charges'],5,'.',',');
		$greater_max_rate_minutes = number_format($line['greater_max_rate_minutes']);

		$total += $line['less_rate_charges'];
		$total += $line['greater_rate_charges'];
		$total += $line['greater_max_rate_charges'];

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
	pg_free_result($rlts);
	$rlts=pg_query("SELECT * FROM invoice_service_charge WHERE invoice_type=0 AND invoice_no = '{$d['invoice_number']}'");
	while ($line=pg_fetch_array($rlts,null,PGSQL_ASSOC)){
		$rate = number_format($line['rate'],2,'.',',');
		$max_rate = number_format($line['max_rate'],2,'.',',');

		$less_rate_usage_fee = number_format($line['less_rate_usage_fee'],5,'.',',');
		$less_rate_charges = number_format($line['less_rate_charges'],5,'.',',');
		$less_rate_minutes = number_format($line['less_rate_minutes']);

		$greater_rate_usage_fee = number_format($line['greater_rate_usage_fee'],5,'.',',');
		$greater_rate_charges = number_format($line['greater_rate_charges'],5,'.',',');
		$greater_rate_minutes = number_format($line['greater_rate_minutes']);

		$greater_max_rate_usage_fee = number_format($line['greater_max_rate_usage_fee'],5,'.',',');
		$greater_max_rate_charges = number_format($line['greater_max_rate_charges'],5,'.',',');
		$greater_max_rate_minutes = number_format($line['greater_max_rate_minutes']);

		$total += $line['less_rate_charges'];
		$total += $line['greater_rate_charges'];
		$total += $line['greater_max_rate_charges'];
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
	$_total = number_format($total,5,'.',',');
	pg_free_result($rlts);
	$html .= <<<EOD
		<tr>
		<td><b>Total</b></td><td></td><td></td><td></td><td style="text-align:center;">$_total</td>
		</tr>
		</table>
EOD;
	return compact('html','total');
}


foreach(get_datas() as $invoice){
	$html = '<div style="width:100%;text-align:center;margin-top:0px;"><span style="font-size:42px;font-weight:bold">International Carrier Exchange Ltd</span></div>';
	$past_due = get_past_due($invoice['client_id'],$invoice['invoice_end']);
	$invoice_time = date("M j Y",strtotime($invoice['invoice_time']));
	$due_date = date("M j Y",strtotime($invoice['due_date']));
	$billing_period = date("m/j/Y",strtotime($invoice['invoice_start'])).'-'.date("m/j/Y",strtotime($invoice['invoice_end']));

	$bought_total = number_format($invoice['buy_total'],5,'.',',');
	$sold_total= number_format($invoice['sell_total'],5,'.',',');
	$bought_minutes = number_format($invoice['buy_minutes']);
	$sold_minutes = number_format($invoice['sell_minutes']);

	$summary_of_minutes_bought = get_minutes_bought($invoice);
	$summary_of_minutes_sell = get_minutes_sell($invoice);
	$summary_of_service_charge = get_service_charge($invoice);
	$serice_charge_total = number_format((float)$invoice['buy_service_charge'] + (float)$invoice['sell_service_charge'],5,'.',',');
	$total = (float)$invoice['buy_total'] - (float)$invoice['sell_total'] + (float)$invoice['buy_service_charge'] + (float)$invoice['sell_service_charge'];

	$_total = show_number(number_format($total,5,'.',','));
	$_past_due = show_number(number_format($past_due,5,'.',','));
	$_total_due = show_number(number_format($total + $past_due,5,'.',','));
	$_link_cdr = '';
	if($invoice['is_link_cdr'] == 't' && !empty($invoice['link_cdr'])){
		$_link_cdr = '<a href="'.$invoice['link_cdr'].'">My CDR Reports</a><br />';
	}
	$html .= <<<EOD
		<div style="height:10px;"></div>
		<table cellpadding="1" cellspacing="0" border="1" nobr="true" width="100%">
		<tr>
		<th>Member Name</th>
		<td>{$invoice['client_name']}</td>
		<th>Invoice Number</th>
		<td>{$invoice['invoice_number']}</td>
		</tr>
		<tr>
		<td>Member Number</td>
		<td>{$invoice['client_id']}</td>
		<th>Invoice Date</th>
		<td>{$invoice_time}</td>
		</tr>
		<tr>
		<td></td>
		<td></td>
		<th>Payment due Date</th>
		<td>{$due_date}</td>
		</tr>
		<tr>
		<td></td>
		<td></td>
		<th>Billing Period</th>
		<td>{$billing_period}</td>
		</tr>
		</table>
		</center>
		<div style="height:10px;"></div>
		<span style="font-size:32px;font-weight:bold">Summary of Trades and Charges</span><br />
		<table cellpadding="1" cellspacing="0" border="1" nobr="true" width="100%" style="margin-top:0px;">
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
		PLEASE REMIT PAYMENT VIA WIRE TRANSFER:<br />
		Bank Name:  Bank of China<br/>
		Account Name:  International Carrier Exchange Ltd<br/>
		Account Number:  223344556677<br/>
		SWIFT: ABCDEF7G<br>
		Please contact <a href="mailto:billing@intlcx.com">billing@intlcx.com</a> if you have any question.

EOD;
	create_PDF("{$invoice['client_id']}_{$invoice['invoice_number']}",$html);
}
pg_close($db);
?>

