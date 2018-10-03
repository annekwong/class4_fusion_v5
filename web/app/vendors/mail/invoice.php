<?php
require_once(dirname(dirname(__FILE__)).'/tcpdf/config/lang/eng.php');
require_once(dirname(dirname(__FILE__)).'/tcpdf/tcpdf.php');
require_once(dirname(__FILE__).'/sendinvoice.php');
require_once(dirname(dirname(dirname(__FILE__))).'/config/database.php');
error_reporting(E_ALL);
$class_dbconfig = new DATABASE_CONFIG();
$conn_config = $class_dbconfig->default;
$db = pg_connect("host={$conn_config['host']} port={$conn_config['port']} dbname={$conn_config['database']} user={$conn_config['login']} password={$conn_config['password']}") or die("can't connect pg");


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
function pdf_show_number($content){ 
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

function get_pdf_tpl(){
	$sql = "SELECT pdf_tpl FROM system_parameter";
//	echo $sql;
	$rlts=pg_query($sql);
	$return = '';
	if ($line=pg_fetch_array($rlts,null,PGSQL_ASSOC)){
		$return = $line['pdf_tpl'];
	}
	pg_free_result($rlts);
	$return = str_replace("\n", '<br />', $return);
	return $return;
}


	function pdf_get_past_due($invoice){			

	$sql = "SELECT * FROM invoice WHERE client_id = {$invoice['client_id']} AND invoice_end::date < '{$invoice['invoice_end']}'";
	$total = 0;
	$rlts=pg_query($sql);
	while ($invoice=pg_fetch_array($rlts,null,PGSQL_ASSOC)){
			$total +=  (float)$invoice['buy_total'] - (float)$invoice['sell_total'] +
			 (float)$invoice['buy_service_charge'] + (float)$invoice['sell_service_charge']  - (float)$invoice['pay_amount'];	
	}
	pg_free_result($rlts);
	return $total;
	}
function get_datas(){
	$sql="select invoice.*,client.name as client_name,client.is_link_cdr as is_link_cdr,client.billing_email as billing_email,client.attach_cdrs_list,client.cdr_list_format,client.scc_bellow,client.scc_percent,client.scc_charge,client.currency_id,client.code,client.rate,client.company from invoice left join (select client.*,currency.code,(select rate from currency_updates where currency_id=client.currency_id order by modify_time desc limit 1) as rate from client left join currency on client.currency_id=currency.currency_id) as client on client.client_id = invoice.client_id where type=0 and create_type=0 and state = 0 and invoice_time::date=current_date order by invoice.client_id";
	$rlts=pg_query($sql);
	$array = array();
	while ($line=pg_fetch_array($rlts,null,PGSQL_ASSOC)){
		$array[] = $line;
	}
	pg_free_result($rlts);
	return $array;
}

function get_all_client_ids($c_array){
	return array_keys($c_array);
}


//alter:让此函数返回生成的pdf文件名
function create_PDF($name,$html){
	$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
	$pdf->SetCreator(PDF_CREATOR);
	$pdf->SetHeaderData('ilogo.png', 15, "", "");
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


	function pdf_get_minutes_bought($invoice, $num_format = 5){		
	$html =<<<EOD
		<span style=""> </span><br />
		<span style="font-size:32px;font-weight:bold">Summary of Minutes Bought</span><br />
		<table cellpadding="1" cellspacing="0" border="1" nobr="true">
		<tr style="background-color:#ddd;text-align:center;">
		<td>Code Name</td><td>Completed Calls</td><td>Total Minutes</td><td>Average Rate(US)</td><td>Effective Date</td><td>Total Charges(US)</td>
		</tr>
EOD;
	$total  = 0;
	$sql = "SELECT * FROM invoice_calls WHERE invoice_type=0 AND invoice_no = '{$invoice['invoice_number']}' order by code_name";
	$rlts=pg_query($sql);
	while ($invoice_call = pg_fetch_array($rlts,null,PGSQL_ASSOC)){
	//foreach($this->query($sql) as $invoice_call){
		$calls = number_format($invoice_call['calls_count']);
		$mins = number_format($invoice_call['total_minutes']);
		$avg_rate = number_format($invoice_call['avg_rate'],5,'.',',');
		$effective_date = $invoice_call['effective_date'];
		$cost = number_format($invoice_call['cost'],$num_format,'.',',');
		$total += $invoice_call['cost'];
		$html .= <<<EOD
		<tr>
			<td>{$invoice_call['code_name']}</td>
			<td style="text-align:center;">{$calls}</td>
			<td style="text-align:center;">{$mins}</td>
			<td style="text-align:center;">{$avg_rate}</td>
			<td style="text-align:center;">{$effective_date}</td>
			<td style="text-align:center;">{$cost}</td>
		</tr>
EOD;
	}
	$total = number_format($total,$num_format,'.',',');
	$html .= <<<EOD
		<tr>
		<td><b>Total</b></td><td></td><td></td><td></td><td></td><td  style="text-align:center;">{$total}</td>
		</tr>
		</table>
EOD;
	return $html;
	}
	
	function pdf_get_minutes_sell($invoice, $num_format = 5){
		$html =<<<EOD
		<span style=""> </span><br />
		<span style="font-size:32px;font-weight:bold">Summary of Minutes Sold</span><br />
		<table cellpadding="1" cellspacing="0" border="1" nobr="true">
		<tr style="background-color:#ddd;text-align:center;">
		<td>Code Name</td><td>Completed Calls</td><td>Total Minutes</td><td>Average Rate(US)</td><td>Effective Date</td><td>Total Charges(US)</td>
		</tr>
EOD;
	$total  = 0;
	$sql = "SELECT * FROM invoice_calls WHERE invoice_type=1 AND invoice_no = '{$invoice['invoice_number']}' order by code_name";
	$rlts=pg_query($sql);
	while ($invoice_call = pg_fetch_array($rlts,null,PGSQL_ASSOC)){
	//foreach($this->query($sql) as $invoice_call){
		$calls = number_format($invoice_call['calls_count']);
		$mins = number_format($invoice_call['total_minutes']);
		$avg_rate = number_format($invoice_call['avg_rate'],5,'.',',');
		$effective_date = $invoice_call['effective_date'];
		$cost = number_format($invoice_call['cost'],$num_format,'.',',');
		$total += $invoice_call['cost'];
		$html .= <<<EOD
		<tr>
			<td>{$invoice_call['code_name']}</td>
			<td style="text-align:center;">{$calls}</td>
			<td style="text-align:center;">{$mins}</td><td  style="text-align:center;">{$avg_rate}</td>
			<td style="text-align:center;">{$effective_date}</td>
			<td style="text-align:center;">{$cost}</td>
		</tr>
EOD;
	}
	$total = number_format($total,$num_format,'.',',');
	$html .= <<<EOD
		<tr>
		<td><b>Total</b></td><td></td><td></td><td></td><td></td><td  style="text-align:center;">{$total}</td>
		</tr>
		</table>
EOD;
	return $html;
	}
	
function pdf_get_service_charge($d){
	$html =<<<EOD
		<div style="height:10px;"></div>
		<span style="font-size:32px;font-weight:bold">Summary of Service Charge</span><br />
		<table cellpadding="1" cellspacing="0" border="1" nobr="true">
		<tr style="background-color:#ddd;text-align:center;">
		<td>Value of trade minute</td><td>Buy/Sell</td><td>Minutes</td><td>Average Fee()</td><td>Total Charges()</td>
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
	$sql = "SELECT * FROM client_cdr".date("Ymd")." WHERE (ingress_client_id='{$client_id}' or egress_client_id='{$client_id}') AND time >= '{$invoice_start}' AND time <= '{$invoice_end}'";
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
	'content'	=> 'Dear {company_name},

This is invoice {invoice_number} for {start_date} - {end_date}.
Please remit your due balance within the terms of your contractual
agreement to avoid disconnection of service.'
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
/*
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
        $class_mail->SMTPDebug = 2;
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

$sql = 'SELECT finance_email,fromemail as "from", smtphost, smtpport,emailusername as username, emailpassword as  "password", emailname as "name",smtp_secure,realm,workstation,loginemail FROM system_parameter';

$result = pg_query($db, $sql);

$row = pg_fetch_assoc($result);

pg_free_result($result);

$host = $row['smtphost'];
$port = $row['smtpport'];
$username = $row['username'];
$from = $row['from'];
$password = $row['password'];
$name = $row['name'];
$finance_email = $row['finance_email'];
$smtp_secure = $row['smtp_secure'];

if ($row['loginemail'] === 'false')
{
    $class_mail->IsMail();
}
else
{
$class_mail->IsSMTP();
}
$class_mail->SMTPDebug = 2;
$class_mail->SMTPAuth = $row['loginemail'] === 'false' ? false : true;
switch ($smtp_secure) {
    case 1:
//        $class_mail->SMTPSecure = 'tls';
        break;
    case 2:
        $class_mail->SMTPSecure = 'ssl';
        break;
    case 3:
            $mail->AuthType = 'NTLM';
            $mail->Realm = $row2['realm'];
            $mail->Workstation = $row2['workstation'];
}
$class_mail->Host = $host;
$class_mail->Post = $port;
$class_mail->Username = $username;
$class_mail->Password = $password;
$class_mail->SetFrom($username, $name);
$class_mail->AddReplyTo($username);

	

foreach (get_datas() as $invoice){
	$num_format = 5;
	$pdf_tpl = get_pdf_tpl();
	//var_dump($invoice);
	$invoice_time = date("M j Y",strtotime($invoice['invoice_time']));
	$due_date = date("M j Y",strtotime($invoice['due_date']));
	$billing_period = date("m/d/Y",strtotime($invoice['invoice_start'])).'-'.date("m/d/Y",strtotime($invoice['invoice_end']));
	$bought_total = number_format(abs($invoice['buy_total']),$num_format,'.',',');
	$sold_total= number_format(abs($invoice['sell_total']),$num_format,'.',',');
	$bought_minutes = number_format(abs($invoice['buy_minutes']));
	$sold_minutes = number_format(abs($invoice['sell_minutes']));	
	
	$past_due = pdf_get_past_due($invoice);
	$summary_of_minutes_bought = ($invoice['type'] == 0 || $invoice['type'] == 2) ? pdf_get_minutes_bought($invoice, $num_format) : '';
	$summary_of_minutes_sell = ($invoice['type'] == 1 || $invoice['type'] == 2) ? pdf_get_minutes_sell($invoice, $num_format) : '';
	
	
	$summary_of_service_charge = pdf_get_service_charge($invoice);
	
	$serice_charge_total = number_format((float)$invoice['buy_service_charge'] + (float)$invoice['sell_service_charge'],$num_format,'.',',');	
	$total = $invoice['type'] == 0 ? (float)$invoice['buy_total'] : (float)$invoice['sell_total'];
	
	$_total = number_format(abs($total),$num_format,'.',',');
	$_past_due = number_format(abs($past_due),$num_format,'.',',');
	$_total_due = number_format(abs($total + $past_due),$num_format,'.',',');
	$sql = "select * from client where client_id = ". $invoice['client_id'];
	$member_name = '';
	$_link_cdr = '';
	$result = pg_query($sql);
	if($client = pg_fetch_array($result)){
		$member_name = $client['company'];
		if($client['is_link_cdr'] && !empty($invoice['link_cdr'])){
			$_link_cdr = '<a href="'.$invoice['link_cdr'].'">My CDR Reports</a><br />';					
		}	
	}
	pg_free_result($result);
	$member_number = 12345+$invoice['client_id'];

	//new pdf body
	if (!empty($system_params[0][0]['tpl_number']) && 1==$system_params[0][0]['tpl_number'])
	{
		$html = <<<EOD
		<div style="width:100%;text-align:left;margin-top:0px;"><span style="font-size:32px;">Customer: {$member_name}</span></div>
		<br /><br />
		{$pdf_tpl}
		<br /><br />
		<div style="width:100%;text-align:right;margin-top:0px;"><span style="font-size:32px;">
	  Invoice: {$member_number} <br/>
   Invoice Date: {$invoice_time}<br/>
   Payment Due Date: {$due_date}<br/>
   Billing Period: $billing_period<br/>
   Invoice time zone: GMT +00<br/>
		</span></div>
		<div style="height:10px;"></div>
		<span style=""> </span><br />
		<span style="font-size:32px;font-weight:bold">Summary of Trades and Charges</span><br />
		<table cellpadding="1" cellspacing="0" border="1" nobr="true" width="100%"  >
		<tr style="background-color:#ddd;text-align:center;">
		<td>&nbsp;</td><td>Total Minutes</td><td>Total Charges(US)</td>
		</tr>
EOD;
	}
	else
	{	
	$html = <<<EOD
		<div style="width:100%;text-align:left;margin-top:0px;"><span style="font-size:32px;">Customer: {$member_name}</span></div>
		<div style="width:100%;text-align:right;margin-top:0px;"><span style="font-size:32px;">
	  Invoice: {$member_number} <br/>
   Invoice Date: {$invoice_time}<br/>
   Payment Due Date: {$due_date}<br/>
   Billing Period: $billing_period<br/>
   Invoice time zone: GMT +00<br/>
		</span></div>
		<div style="height:10px;"></div>
		<span style=""> </span><br />
		<span style="font-size:32px;font-weight:bold">Summary of Trades and Charges</span><br />
		<table cellpadding="1" cellspacing="0" border="1" nobr="true" width="100%"  >
		<tr style="background-color:#ddd;text-align:center;">
		<td>&nbsp;</td><td>Total Minutes</td><td>Total Charges(US)</td>
		</tr>
EOD;
	}
	
if ($invoice['type'] == 0)
{
	$html .= <<<EOD
		<tr>
		<td>Minutes Bought</td><td style="text-align:center;">{$bought_minutes}</td><td  style="text-align:center;">{$bought_total}</td>
		</tr>
EOD;
}
else
{
	$html .= <<<EOD
		<tr>
		<td>Minutes Sold</td><td style="text-align:center;">{$sold_minutes}</td><td  style="text-align:center;">{$sold_total}</td>
		</tr>
EOD;
}	
if (empty($system_params[0][0]['tpl_number']))
{
	$html .= <<<EOD
		<tr>
		<td></td><td>Total</td><td  style="text-align:center;">{$_total}</td>
		</tr>
		</table>
		$summary_of_minutes_bought
		$summary_of_minutes_sell
		<br /><br /><br /><br />
		{$_link_cdr}
		{$pdf_tpl}
EOD;
}
elseif (!empty($system_params[0][0]['tpl_number']) && 2==$system_params[0][0]['tpl_number'] )
{
		$html .= <<<EOD
		<tr>
		<td></td><td>Total</td><td  style="text-align:center;">{$_total}</td>
		</tr>
		</table>
		$summary_of_minutes_bought
		<br /><br />
		{$pdf_tpl}
		<br /><br />
		$summary_of_minutes_sell
		<br /><br /><br /><br />
		{$_link_cdr}
EOD;
}
else
{
	$html .= <<<EOD
		<tr>
		<td></td><td>Total</td><td  style="text-align:center;">{$_total}</td>
		</tr>
		</table>
		$summary_of_minutes_bought
		$summary_of_minutes_sell
		<br /><br /><br /><br />
		{$_link_cdr}
		{$pdf_tpl}
EOD;
}
		$pdf_name = create_PDF("{$invoice['client_id']}_{$invoice['invoice_number']}",$html);
		//$pdf_csv_filename = $invoice['client_id']."_".$invoice['invoice_number'].".csv";
		if ($invoice['attach_cdrs_list'])
		{
			$csv_name = create_CSV($dir, $invoice['client_id'], $invoice['invoice_number'],$invoice['invoice_start'],$invoice['invoice_end'],$invoice['cdr_list_format']);
		}
		if (!empty($finance_email))//(!empty($invoice['billing_email']))
		{
			$class_mail->ClearAddresses();
			$class_mail->AddAddress($invoice['billing_email'], $invoice['client_name']);
			$class_mail->AddCC($finance_email, ''); //copy send to finance treasurer
			//$class_mail->AddAddress($mail_set_arr['finance_email'], '');
			$subject = str_replace(array('{company_name}', '{invoice_period_start}', '{invoice_period_finish}'), array($invoice['company'], $invoice['invoice_start'], $invoice['invoice_end']), $mail_tmplate['subject']);
			$content = str_replace(array('{company_name}', '{invoice_period_start}', '{invoice_period_finish}', '{invoice_number}'), array($invoice['company'], $invoice['invoice_start'], $invoice['invoice_end'], $invoice['invoice_number']), $mail_tmplate['content']);
                        $class_mail->Subject = $subject; 						//mail title
			$class_mail->Body = $content; 	//mail content
			//$class_mail->Subject = "Invoice"; 						//mail title
			//$class_mail->Body = 'Your Invoice.'; 	//mail content
			$class_mail->ClearAttachments();
			$class_mail->AddAttachment($pdf_name);		//
			if (!empty($csv_name) && is_file($csv_name))
			{		
				if (3 == $invoice['cdr_list_format'])
				{
					$csv_tar_path = "/tmp/{$invoice['client_id']}_{$invoice['invoice_number']}_cdr.zip";
					system("zip -p {$csv_tar_path} {$csv_name}", $return_val);
				}	
				else
				{	
					$csv_tar_path = "/tmp/{$invoice['client_id']}_{$invoice['invoice_number']}_cdr.tar.gz";
					system("tar -zcf {$csv_tar_path} {$csv_name}", $return_val);
				}
				$class_mail->AddAttachment($csv_tar_path);
			}
			//$class_mail->AltBody = "This is the body in plain text for non-HTML mail clients"; //附加信息，可以省略
			if(!$class_mail->Send())
			{
			}
			else
			{
				$upd_mail_sended = pg_query($db,"update invoice set state = 9, send_time = current_timestamp(0) where invoice_id = {$invoice['invoice_id']}"); 
				if (!$upd_mail_sended)
				{
					//log
				}
                                $sql = "INSERT INTO invoice_email (invoice_no, send_time, mail_sub, mail_content, send_address, pdf_file) VALUES ('{$invoice['invoice_number']}', CURRENT_TIMESTAMP, '{$subject}', '{$content}', '{$invoice['billing_email']}', '{$pdf_name}')";
                                    //echo "mail send false. <p>";
                                    //echo "Error: " . $class_mail->ErrorInfo;
                                    //log
                                pg_query($db, $sql);
			}
		}
		else 
		{
				$upd_mail_sended = pg_query($db, "update invoice set state = 3 where invoice_id = {$invoice['invoice_id']}"); 
				if (!$upd_mail_sended)
				{
					//log
				}
		}
}
pg_close($db);
?>

