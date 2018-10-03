<?php
require_once(dirname(__FILE__).'/tcpdf/config/lang/eng.php');
require_once(dirname(__FILE__).'/tcpdf/tcpdf.php');

error_reporting(E_ALL);
$db=pg_connect("host=192.168.1.115  dbname=class4_pr user=root");

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
	//$sql="select invoice.*,client.name as client_name,client.is_link_cdr as is_link_cdr,client.billing_email as billing_email,client.attach_cdrs_list,client.cdr_list_format,client.scc_bellow,client.scc_percent,client.scc_charge,client.currency_id,client.code,client.rate from invoice left join (select client.*,currency.code,currency_updates.rate from client left join currency on client.currency_id=currency.currency_id left join currency_updates on client.currency_id = currency_updates.currency_id) as client on client.client_id = invoice.client_id where invoice_time::date=current_date and create_type=0 and state <=1 order by invoice.client_id";
//	$sql = "select * from partition_invoice";
//	$rlts=pg_query($sql);
	$array = array();
//	while ($line=pg_fetch_array($rlts,null,PGSQL_ASSOC)){
//		$array[] = $line;
//	}
//	pg_free_result($rlts);
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

//alter:让此函数返回生成的pdf文件名
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
	//$pdf->SetFont('stsongstdlight', '', 10);
	$pdf->AddPage();
	$pdf->writeHTML($html, true, false, true, false, '');
	$pdf->Output($name.'.pdf', 'F');
	$pdf->Close();
	return $name.'.pdf';
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
	$rlts=pg_query($db, "SELECT * FROM invoice_calls WHERE invoice_type=0 AND invoice_no = '{$d['invoice_number']}'");
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
			<tignore_user_abort();
set_time_limit(0);d style="text-align:center;">{$avg_rate}</td>
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
	while ($line = pg_fetch_array($rlts,null,PGSQL_ASSOC)){
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
function create_CSV($dir, $client_id, $invoice_number,$invoice_start,$invoice_end,$cdr_list_format)
{
	$pdf_csv_filename = $client_id."_".$invoice_number.".csv";
	$title = 0;
	$sql = "SELECT * FROM client_cdr".date("Ymd")." WHERE ingress_client_id={$client_id}::text AND time >= '{$invoice_start}' AND time <= '{$invoice_end}'";
	$rlts=pg_query($sql);
	if (pg_num_rows($rlts)>0)
	{
		$handle = fopen($dir."/".$pdf_csv_filename, "w");
		while ($line=pg_fetch_array($rlts,null,PGSQL_ASSOC))
		{
			if ($cdr_list_format == 2)
			{
				if ($title == 0)
				{
					fputcsv($handle, array_keys($line));
					$title = 1;
				}
				fputcsv($handle, $line);
			}
			else 
			{
				if ($title == 0)
				{
					fwrite($handle, implode(chr(9),array_keys($line)));
					fwrite($handle, chr(13));
					$title = 1;
				}
				fwrite($handle, implode(chr(9),$line));
				fwrite($handle, chr(13));
			}
		}
		fclose($handle);
		$return = $dir."/".$pdf_csv_filename;
	}
	else
	{
		$return = '';
	}
	pg_free_result($rlts);
	return $return;
}
function pdf_get_minutes_bought($invoice){		
	//	$rlts=pg_query("select * from partition_invoice where invoice_no={$invoice['invoice_no']}");
	//	$list=pg_fetch_array($rlts,null,PGSQL_ASSOC);
		$currency = '';//!empty($list['code'])?$list['code']:'';
		$rate = 1;//!empty($list['rate']) ? $list['rate'] : 1;
	$html =<<<EOD
		<span style=""> </span><br />
		<span style="font-size:32px;font-weight:bold"></span><br />
		<table cellpadding="1" cellspacing="0" border="1" nobr="true">
		<tr style="background-color:#ddd;text-align:center;">
		<td></td><td>Completed Calls</td><td>Total Minutes</td><td>Rate</td><td>Total Charges</td>
		</tr>
EOD;
		$total_min =0 ;
		$total_calls = 0;
		$total_cost = 0;
//	$sql = "SELECT invoice_start,invoice_end,client_id,due_date FROM invoice WHERE  invoice_number = '{$invoice['invoice_number']}'  limit 1";
//	$list=pg_query($sql);
//	$start=$list['invoice_start'];
//	$end=$list['invoice_end'];
//	$client_id=$list['client_id'];
//	pg_free_result($rlts);
//	$sql="select  calls_count,total_minutes,cost  from    invoice_calls    where   invoice_no='{$invoice['invoice_number']}'";
//	$rlts = pg_query($sql);
//	while($invoice_call=pg_fetch_array($rlts,null,PGSQL_ASSOC)){
		//$mins = number_format($invoice['total_minutes']);
		//$cost = number_format($invoice['total_cost']*$rate, 5, '.', ',');
		$total_min += $invoice['total_minutes'];
		$total_calls += $invoice['total_calls'];
		$total_cost += $invoice['total_cost'];
/*		$html .= <<<EOD
		<tr>
			<td style="text-align:center;">{$invoice['total_calls']}</td>
			<td style="text-align:center;">{$mins}</td>
			
			<td style="text-align:center;">{$cost}</td>
		</tr>
EOD;
	}*/
//	pg_free_result($rlts);
	$total_min = number_format($total_min,0,'.',',');
	$total_cost = number_format($total_cost,5,'.',',');
	$rate = number_format($invoice['rate'],5,'.',',');
	$html .= <<<EOD
		<tr>
		<td><b>Total</b></td><td style="text-align:center;">{$total_calls}</td><td  style="text-align:center;">{$total_min}</td><td style="text-align:center;">{$rate}</td><td  style="text-align:center;">{$total_cost}</td>
		</tr>
		</table>
EOD;
	return $html;
	}
	
	/**
	 * Within a billing cycle, if more than __ % of all/answered calls are below __ seconds, add ___ ( currency ) to each of those calls.
	 * @param int $clientId: carrie id
	 * @param int $bellow: bellow seconds
	 * @param int $type: 1 answered 0 all
	 * @param $invoice_start, $invoice_end时间段
	 * @return int $reutrn 
	 */
	function getShortCallChargeNum($clientId, $invoice_start, $invoice_end, $bellow = 0, $type=1)
	{
		$return = 0;
		if ($type == 1)
		{
			$sql = "select count(*) as num from client_cdr".date("Ymd")." where ingress_client_id='".intval($clientId)."' and call_duration::integer<".intval($bellow)." and time >= '{$invoice_start}' AND time <= '{$invoice_end}' and answer_time_of_date!='0' and answer_time_of_date!=''";
		}
		else
		{
			$sql = "select count(*) as num from client_cdr".date("Ymd")." where ingress_client_id='".intval($clientId)."' and time >= '{$invoice_start}' AND time <= '{$invoice_end}' and answer_time_of_date!='0' and answer_time_of_date!=''";
		}
		$rlts = pg_query($sql);
		if ($scc_num=pg_fetch_array($rlts,null,PGSQL_ASSOC))
		{
			$return = $scc_num['num'];
		}
		pg_free_result($rlts);
		return $return;
	}
/*	
$class_mail = new phpmailer();

//invoice的cdr附加csv存储临时文件夹
$dir = "/tmp/invoice_csv";
if (!is_dir($dir))
{
	mkdir($dir);		
}
$mail_to_carrie_array = array(); 		//要发送客户邮件的数组
$mail_set_arr = array();		//系统参数
$mail_tmplate = array(		//邮件模板
	'subject'	=> 'Invoice from {company_name} for {invoice_period_start} - {invoice_period_finish}',
	'content'	=> '--
Autogenerated by ICX.'
);
//读取系统参数，初始化邮件模板
$sql = "select * from mail_tmplate";
$result_tpl = pg_query($sql);
$amount_tpl = pg_num_rows($result_tpl);
if ($amount_tpl > 0)
{
	if ($row_tpl = pg_fetch_array($result_tpl)) {
		$mail_tmplate['subject'] = $row_tpl['invoice_subject'];
		$mail_tmplate['content'] = $row_tpl['invoice_content'];
	}
}
pg_free_result($result_tpl);
//读取系统参数，初始化邮件发送类
$sql = "select * from system_parameter";
$result = pg_query($db, $sql);
$amount = pg_num_rows($result);
if ($amount > 0)
{
	if ($row = pg_fetch_array($result)) {
		$mail_set_arr = $row;
	}
	//mail class 初始化
	$class_mail->IsSMTP();
	$class_mail->SMTPAuth 	= true; 
	$class_mail->From 		= $mail_set_arr['fromemail'];	//"xiezx@mail.yht.com";
	$class_mail->FromName	= $mail_set_arr['emailname'];
	$class_mail->Host		= $mail_set_arr['smtphost'];		//"192.168.1.125";
	$class_mail->Port		= $mail_set_arr['smtpport'];		//25
	$class_mail->Username	= $mail_set_arr['emailusername'];	//"test@mail.yht.com";
	$class_mail->Password	= $mail_set_arr['emailpassword'];		//"123456";
}
pg_free_result($result);

*/
	

foreach(get_datas() as $invoice){
	//$html = '<div style="width:100%;text-align:center;margin-top:0px;"><span style="font-size:42px;font-weight:bold">International Carrier Exchange Ltd</span></div>';
	//$past_due = get_past_due($invoice['client_id'],$invoice['invoice_end']);
	$invoice_time = date("M j Y",strtotime($invoice['invoice_time']));
	$due_date = date("M j Y",strtotime($invoice['due_date']));
	$billing_period = date("m/j/Y",strtotime($invoice['invoice_start'])).'-'.date("m/j/Y",strtotime($invoice['invoice_end']));
/*
	$bought_total = number_format($invoice['buy_total'],5,'.',',');
	$sold_total= number_format($invoice['sell_total'],5,'.',',');
	$bought_minutes = number_format($invoice['buy_minutes']);
	$sold_minutes = number_format($invoice['sell_minutes']);
	$rate = empty($invoice['rate']) ? 1 : number_format($invoice['rate'],2);
	$currency = empty($invoice['code']) ? '' : $invoice['code'];
	$total_amount = number_format($invoice['total_amount']*$rate,2);
	$current_balance = number_format($invoice['current_balance']*$rate,2);
	$credit_amount = number_format($invoice['credit_amount']*$rate,2);
	$call_all = getShortCallChargeNum($invoice['client_id'],$invoice['invoice_start'],$invoice['invoice_end'],$invoice['scc_bellow'],0);
	$call_answer = getShortCallChargeNum($invoice['client_id'],$invoice['invoice_start'],$invoice['invoice_end'],$invoice['scc_bellow'],1);
	//添加short call charge
	$add_charge = '';
	if ($call_all > 0 && $call_answer*100/$call_all > $invoice['scc_percent'])
	{
		$add_charge = 'Short Call Charge: '.number_format($invoice['scc_charge']*$rate, 2);
	}
	
	//$summary_of_minutes_bought = get_minutes_bought($invoice);
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
	}*/
	$summary_of_minutes_bought = pdf_get_minutes_bought($invoice);
  
	$html = <<<EOD
		<div style="width:100%;text-align:left;margin-top:0px;"><span style="font-size:32px;">Customer: {$invoice['customer_name']}</span></div>
		<div style="width:100%;text-align:right;margin-top:0px;"><span style="font-size:32px;">
	Invoice: {$invoice['invoice_no']} <br/>
   Invoice Date: {$invoice_time}<br/>
   Payment Due Date: {$due_date}<br/>
   Billing Period: {$billing_period}<br/>
   Invoice time zone: GMT +00<br/>
	
		</span></div>
		<div style="height:10px;"></div>
		
		$summary_of_minutes_bought
		<br />	<br />	<br />		
		PLEASE REMIT PAYMENT VIA WIRE TRANSFER:<br />
		Bank Name:  Bank of China (Hong Kong) Limited<br />
		Bank Address:10/F,Bank of China  1 Garden Road, Hong Kong<br />
		Account Name:  International Carrier Exchange Limited<br />
		Account Number:  012-875-9-252697-2<br />
		SWIFT: BKCHHKHHXXX<br />
		Please contact <a href="mailto:billing@intlcx.com">billing@intlcx.com</a> if you have any question.
EOD;
	/*$html .= <<<EOD
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
		<br /><br /><br />
		{$_link_cdr}
		<br /><br />
		PLEASE REMIT PAYMENT VIA WIRE TRANSFER:<br />
		Bank Name:  Bank of China<br/>
		Account Name:  International Carrier Exchange Ltd<br/>
		Account Number:  223344556677<br/>
		SWIFT: ABCDEF7G<br>
		Please contact <a href="mailto:billing@intlcx.com">billing@intlcx.com</a> if you have any question.
{invoice_period_start}
EOD;*/
		$pdf_name = create_PDF("{$invoice['customer_name']}_{$invoice['invoice_no']}",$html);
		//$pdf_csv_filename = $invoice['client_id']."_".$invoice['invoice_number'].".csv";
		if (0)//($invoice['attach_cdrs_list'])
		{
			$csv_name = create_CSV($dir, $invoice['client_id'], $invoice['invoice_number'],$invoice['invoice_start'],$invoice['invoice_end'],$invoice['cdr_list_format']);
		}
		if (0)//(!empty($invoice['billing_email']))
		{
			$class_mail->ClearAddresses();
			//----------------20110316 先把invoice发送给财务，不发送给客户
			//$class_mail->AddAddress($invoice['billing_email'], $invoice['client_name']);
			//$class_mail->AddCC($mail_set_arr['finance_email'], ''); //copy send to finance treasurer
			$class_mail->AddAddress($mail_set_arr['finance_email'], '');
			
			$class_mail->Subject = str_replace(array('{company_name}', '{invoice_period_start}', '{invoice_period_finish}'), array($invoice['client_name'], $invoice['invoice_start'], $invoice['invoice_end']), $mail_tmplate['subject']); 						//mail title
			$class_mail->Body = $mail_tmplate['content']; 	//mail content
			//$class_mail->Subject = "Invoice"; 						//mail title
			//$class_mail->Body = 'Your Invoice.'; 	//mail content
			$class_mail->ClearAttachments();
			$class_mail->AddAttachment($pdf_name);		//
			if (!empty($csv_name) && is_file($csv_name))
			{				
				$csv_tar_path = "/tmp/{$invoice['client_id']}_{$invoice['invoice_number']}_cdr.tar.gz";
				system("tar -zcf {$csv_tar_path} {$csv_name}", $return_val);
				$class_mail->AddAttachment($csv_tar_path);
			}
			//$class_mail->AltBody = "This is the body in plain text for non-HTML mail clients"; //附加信息，可以省略
			if(!$class_mail->Send())
			{
				//echo "mail send false. <p>";
				//echo "Error: " . $class_mail->ErrorInfo;
				//log
			}
			else
			{
				$upd_mail_sended = pg_query($db,"update invoice set state = 9, send_time = current_timestamp(0) where invoice_id = {$invoice['invoice_id']}"); 
				if (!$upd_mail_sended)
				{
					//log
				}
			}
		}
		else 
		{
				//$upd_mail_sended = pg_query($db, "update invoice set state = 3 where invoice_id = {$invoice['invoice_id']}"); 
				//if (!$upd_mail_sended)
				{
					//log
				}
		}
}
pg_close($db);
?>

