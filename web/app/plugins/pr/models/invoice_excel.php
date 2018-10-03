<?php
	class InvoiceExcel extends AppModel{
		var $name = 'InvoiceExcel';
		var $useTable = 'invoice';
		var $primaryKey = 'invoice_id';
		
		const TYPE_BUY = 0;
		const TYPE_SELL = 1;	

	
	function get_total_due_info($client_id){
		$sql = "SELECT * FROM invoice WHERE client_id = {$client_id} ORDER BY invoice_end ASC";
		$total = 0;
		$invoice_time = '';
		foreach($this->query($sql) as $invoice){
			$invoice = $invoice[0];
			if($invoice){				
				$invoice_time = $invoice['invoice_end'];
				$total +=  (float)$invoice['buy_total'] - (float)$invoice['sell_total'] +
				 (float)$invoice['buy_service_charge'] + (float)$invoice['sell_service_charge']  - (float)$invoice['pay_amount'] + (float)$invoice['lrn_cost'];
			}
		}
		return  compact('total','invoice_time');
	}
		
///////////////INVOICE PDF 用的///////////////////////////
	
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
	
	
	function pdf_get_past_due($invoice){			

	$sql = "SELECT * FROM invoice WHERE client_id = {$invoice['client_id']} AND invoice_end::date < '{$invoice['invoice_end']}'";
	$total = 0;
	foreach($this->query($sql) as $invoice){
		$invoice = $invoice[0];
		if($invoice){
			$total +=  (float)$invoice['buy_total'] - (float)$invoice['sell_total'] +
			 (float)$invoice['buy_service_charge'] + (float)$invoice['sell_service_charge']  - (float)$invoice['pay_amount'];
		}
	}
	return $total;
	}
	
	
	function pdf_get_minutes_bought($invoice, $num_format = 5){		
		$return = array();
		$return['total'] = 0;

		$sql = "SELECT * FROM invoice_calls WHERE invoice_type=0 AND invoice_no = '{$invoice['invoice_number']}' order by code_name";
		foreach($this->query($sql) as $k=>$invoice_call){
			$return['list'][$k]['code_name'] = $invoice_call[0]['code_name'];
			$return['list'][$k]['calls'] = number_format($invoice_call[0]['calls_count']);
			$return['list'][$k]['mins'] = number_format($invoice_call[0]['total_minutes']);
			$return['list'][$k]['avg_rate'] = number_format($invoice_call[0]['avg_rate'],5,'.',',');
			$return['list'][$k]['effective_date'] = $invoice_call[0]['effective_date'];
			$return['list'][$k]['cost'] = number_format($invoice_call[0]['cost'],$num_format,'.',',');
			$return['total'] += $invoice_call[0]['cost'];		
		}
	
		return $return;
	}
	
	function pdf_get_minutes_sell($invoice, $num_format = 5){
		$return = array();
		$return['total'] = 0;
		
		$sql = "SELECT * FROM invoice_calls WHERE invoice_type=1 AND invoice_no = '{$invoice['invoice_number']}' order by code_name";
		foreach($this->query($sql) as $k=>$invoice_call){
			$return['list'][$k]['code_name'] = $invoice_call[0]['code_name'];
			$return['list'][$k]['calls'] = number_format($invoice_call[0]['calls_count']);
			$return['list'][$k]['mins'] = number_format($invoice_call[0]['total_minutes']);
			$return['list'][$k]['avg_rate'] = number_format($invoice_call[0]['avg_rate'],5,'.',',');
			$return['list'][$k]['effective_date'] = $invoice_call[0]['effective_date'];
			$return['list'][$k]['cost'] = number_format($invoice_call[0]['cost'],$num_format,'.',',');
			$return['total'] += $invoice_call[0]['cost'];		
		}
	
		return $return;
	}
	
	function pdf_get_service_charge($invoice){
		$return = array('sell'=>array('total'=>0), 'buy'=>array('total'=>0));
		
	$sql = "SELECT * FROM invoice_service_charge WHERE invoice_type=1 AND invoice_no = '{$invoice['invoice_number']}'";
	foreach($this->query($sql) as $k=>$charge){
		$return['sell'][$k]['rate'] = number_format($charge[0]['rate'],2,'.',',');
		$return['sell'][$k]['max_rate'] = number_format($charge[0]['max_rate'],2,'.',',');		
		
		$return['sell'][$k]['less_rate_usage_fee'] = number_format($charge[0]['less_rate_usage_fee'],5,'.',',');
		$return['sell'][$k]['less_rate_charges'] = number_format($charge[0]['less_rate_charges'],5,'.',',');
		$return['sell'][$k]['less_rate_minutes'] = number_format($charge[0]['less_rate_minutes']);
		
		$return['sell'][$k]['greater_rate_usage_fee'] = number_format($charge[0]['greater_rate_usage_fee'],5,'.',',');
		$return['sell'][$k]['greater_rate_charges'] = number_format($charge[0]['greater_rate_charges'],5,'.',',');
		$return['sell'][$k]['greater_rate_minutes'] = number_format($charge[0]['greater_rate_minutes']);
		
		$return['sell'][$k]['greater_max_rate_usage_fee'] = number_format($charge[0]['greater_max_rate_usage_fee'],5,'.',',');
		$return['sell'][$k]['greater_max_rate_charges'] = number_format($charge[0]['greater_max_rate_charges'],5,'.',',');
		$return['sell'][$k]['greater_max_rate_minutes'] = number_format($charge[0]['greater_max_rate_minutes']);
		
		$return['sell']['total'] += $charge[0]['less_rate_charges'];
		$return['sell']['total'] += $charge[0]['greater_rate_charges'];
		$return['sell']['total'] += $charge[0]['greater_max_rate_charges'];
		
		
	}
	$sql = "SELECT * FROM invoice_service_charge WHERE invoice_type=0 AND invoice_no = '{$invoice['invoice_number']}'";
	foreach($this->query($sql) as $k=>$charge){
		$return['buy'][$k]['rate'] = number_format($charge[0]['rate'],2,'.',',');
		$return['buy'][$k]['max_rate'] = number_format($charge[0]['max_rate'],2,'.',',');		
		
		$return['buy'][$k]['less_rate_usage_fee'] = number_format($charge[0]['less_rate_usage_fee'],5,'.',',');
		$return['buy'][$k]['less_rate_charges'] = number_format($charge[0]['less_rate_charges'],5,'.',',');
		$return['buy'][$k]['less_rate_minutes'] = number_format($charge[0]['less_rate_minutes']);
		
		$return['buy'][$k]['greater_rate_usage_fee'] = number_format($charge[0]['greater_rate_usage_fee'],5,'.',',');
		$return['buy'][$k]['greater_rate_charges'] = number_format($charge[0]['greater_rate_charges'],5,'.',',');
		$return['buy'][$k]['greater_rate_minutes'] = number_format($charge[0]['greater_rate_minutes']);
		
		$return['buy'][$k]['greater_max_rate_usage_fee'] = number_format($charge[0]['greater_max_rate_usage_fee'],5,'.',',');
		$return['buy'][$k]['greater_max_rate_charges'] = number_format($charge[0]['greater_max_rate_charges'],5,'.',',');
		$return['buy'][$k]['greater_max_rate_minutes'] = number_format($charge[0]['greater_max_rate_minutes']);
		
		$return['buy']['total'] += $charge[0]['less_rate_charges'];
		$return['buy']['total'] += $charge[0]['greater_rate_charges'];
		$return['buy']['total'] += $charge[0]['greater_max_rate_charges'];
		
		
	}
	return $return;
	}
	
	
	function generate_pdf_content($invoice_number, $num_format = 5){
	App::import("Vendor", "invoice_excel", array('file'=>"invoice_excel.php"));
	if (empty($invoice_number))
	{
		return '';
	}
	$invoice = $this->query("select * from invoice where invoice_number = '$invoice_number'");
	//var_dump("select * from invoice where invoice_number = '$invoice_number'", $invoice);
	$invoice = $invoice ? $invoice[0][0] : null;	
	
	$system_params = $this->query("select * from system_parameter");
	$pdf_tpl = str_replace("\n", "\r\n", $system_params[0][0]['pdf_tpl']);
	$invoice_time = date("M j Y",strtotime($invoice['invoice_time']));
	$due_date = date("M j Y",strtotime($invoice['due_date']));
	$billing_period = date("m/d/Y",strtotime($invoice['invoice_start'])).'-'.date("m/d/Y",strtotime($invoice['invoice_end']));
	$bought_total = number_format($invoice['buy_total'], $num_format,'.',',');
	$sold_total= number_format($invoice['sell_total'],$num_format,'.',',');
	$bought_minutes = number_format($invoice['buy_minutes']);
	$sold_minutes = number_format($invoice['sell_minutes']);	
	
	$past_due = $this->pdf_get_past_due($invoice);
	
	$summary_of_minutes_bought = ($invoice['type'] == 0 || $invoice['type'] == 2) ? $this->pdf_get_minutes_bought($invoice, $num_format) : '';
	$summary_of_minutes_sell = ($invoice['type'] == 1 || $invoice['type'] == 2) ? $this->pdf_get_minutes_sell($invoice, $num_format) : '';
	
	$summary_of_service_charge = $this->pdf_get_service_charge($invoice);
	
	$serice_charge_total = number_format((float)$invoice['buy_service_charge'] + (float)$invoice['sell_service_charge'],$num_format,'.',',');	
	//$total = (float)$invoice['buy_total'] - (float)$invoice['sell_total'] + (float)$invoice['buy_service_charge'] + (float)$invoice['sell_service_charge'];
	$total = $invoice['type'] == 0 ? (float)$invoice['buy_total'] : (float)$invoice['sell_total'];
	
	$_total = $this->pdf_show_number(number_format($total,$num_format,'.',','));
	$_past_due = $this->pdf_show_number(number_format($past_due,$num_format,'.',','));
	$_total_due = $this->pdf_show_number(number_format($total + $past_due,$num_format,'.',','));
	$client = $this->query("select * from client where client_id = ". $invoice['client_id']);
	$member_name = '';
	$_link_cdr = '';
	if($client){
		$member_name = $client[0][0]['name'];
		if($client[0][0]['is_link_cdr'] && !empty($invoice['link_cdr'])){
			$_link_cdr = '<a href="'.$invoice['link_cdr'].'">My CDR Reports</a><br />';					
		}	
	}
	$member_number = 12345+$invoice['client_id'];
	$invoice['lrn_rate'] = number_format($invoice['lrn_rate'],$num_format,'.',',');
	$invoice['lrn_cost'] = number_format($invoice['lrn_cost'],$num_format,'.',',');	

$objPHPExcel = new PHPExcel();


$objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
							 ->setLastModifiedBy("Maarten Balliauw")
							 ->setTitle("Office 2007 XLSX Test Document")
							 ->setSubject("Office 2007 XLSX Test Document")
							 ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
							 ->setKeywords("office 2007 openxml php")
							 ->setCategory("Test result file");


$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', "Customer: {$client[0][0]['company']}")
            ->setCellValue('A2', "Invoice: {$invoice_number}")
            ->setCellValue('A3', "Invoice Date: {$invoice_time}")
            ->setCellValue('A4', "Payment Due Date: {$due_date}");

$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A5', "Billing Period: $billing_period")
            ->setCellValue('A6', "Invoice time zone: GMT {$client[0][0]['invoice_zone']}");
            
//$objPHPExcel->getActiveSheet()->mergeCells('A1:F1');
for ($i=1; $i<=8; $i++)
{
	$objPHPExcel->getActiveSheet()->mergeCells('A'.$i.':F'.$i);
}

//Summary of Trades and Charges
for ($i = 9; $i<=11; $i++)
{
	$objPHPExcel->getActiveSheet()->mergeCells('A'.$i.':B'.$i);
	$objPHPExcel->getActiveSheet()->mergeCells('C'.$i.':D'.$i);
	$objPHPExcel->getActiveSheet()->mergeCells('E'.$i.':F'.$i);
}

$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A8', "Summary of Trades and Charges")
            ->setCellValue('C9', "Total Minutes")
            ->setCellValue('E9', "Total Charges(US)");

  
$cur = 10;
if ($invoice['type'] == 0)
{
	$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$cur, "Minutes Client")
            ->setCellValue('C'.$cur, $bought_minutes)
            ->setCellValue('E'.$cur, $bought_total);
}
else
{
	$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$cur, "Minutes Vendor")
            ->setCellValue('C'.$cur, $sold_minutes)
            ->setCellValue('E'.$cur, $sold_total);
}
$cur++;
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('C'.$cur, "Total")
            ->setCellValue('E'.$cur, $_total);
$cur++;

//Summary Of Minutes Bought/Sold
$cur++;

$objPHPExcel->getActiveSheet()->mergeCells('A'.$cur.':F'.$cur);
if (!empty($summary_of_minutes_bought))
{
	//var_dump($summary_of_minutes_bought);
	$minute_cur = $cur;
	$objPHPExcel->setActiveSheetIndex(0)
	            ->setCellValue('A'.$cur, "Summary of Minutes Client");
	$cur++;
	$objPHPExcel->setActiveSheetIndex(0)
	            ->setCellValue('A'.$cur, 'Code Name')
	            ->setCellValue('B'.$cur, 'Completed Calls')
	            ->setCellValue('C'.$cur, 'Total Minutes')
	            ->setCellValue('D'.$cur, 'Average Rate(US)')
	            ->setCellValue('E'.$cur, 'Effective Date')
	            ->setCellValue('F'.$cur, 'Total Charges(US)');
	 $cur++;
	if (!empty($summary_of_minutes_bought['list']))
	{
		foreach ($summary_of_minutes_bought['list'] as $k=>$v)
		{
			$objPHPExcel->setActiveSheetIndex(0)
	            ->setCellValue('A'.$cur, $v['code_name'])
	            ->setCellValue('B'.$cur, $v['calls'])
	            ->setCellValue('C'.$cur, $v['mins'])
	            ->setCellValue('D'.$cur, $v['avg_rate'])
	            ->setCellValue('E'.$cur, $v['effective_date'])
	            ->setCellValue('F'.$cur, $v['cost']);
			$cur++;
		}
	}
	$objPHPExcel->setActiveSheetIndex(0)
	            ->setCellValue('A'.$cur, "Total")
	            ->setCellValue('F'.$cur, $summary_of_minutes_bought['total']);
	$cur++;
}
elseif (!empty($summary_of_minutes_sell)) 
{
	//var_dump($summary_of_minutes_sell);
	$minute_cur = $cur;
	$objPHPExcel->setActiveSheetIndex(0)
	            ->setCellValue('A'.$cur, "Summary of Minutes Vendor");
	$cur++;	
	$objPHPExcel->setActiveSheetIndex(0)
	            ->setCellValue('A'.$cur, 'Code Name')
	            ->setCellValue('B'.$cur, 'Completed Calls')
	            ->setCellValue('C'.$cur, 'Total Minutes')
	            ->setCellValue('D'.$cur, 'Average Rate(US)')
	            ->setCellValue('E'.$cur, 'Effective Date')
	            ->setCellValue('F'.$cur, 'Total Charges(US)');
	$cur++;
	if (!empty($summary_of_minutes_sell['list']))
	{
		foreach ($summary_of_minutes_sell['list'] as $k=>$v)
		{
			$objPHPExcel->setActiveSheetIndex(0)
	            ->setCellValue('A'.$cur, $v['code_name'])
	            ->setCellValue('B'.$cur, $v['calls'])
	            ->setCellValue('C'.$cur, $v['mins'])
	            ->setCellValue('D'.$cur, $v['avg_rate'])
	            ->setCellValue('E'.$cur, $v['effective_date'])
	            ->setCellValue('F'.$cur, $v['cost']);
			$cur++;
		}
	}
	$objPHPExcel->setActiveSheetIndex(0)
	            ->setCellValue('A'.$cur, "Total")
	            ->setCellValue('F'.$cur, $summary_of_minutes_sell['total']);
	$cur++;
}  

//lrn
$cur++;

$objPHPExcel->getActiveSheet()->mergeCells('A'.$cur.':F'.$cur);
if (1)
{
	//var_dump($summary_of_minutes_bought);
	$minute_cur = $cur;
	$objPHPExcel->setActiveSheetIndex(0)
	            ->setCellValue('A'.$cur, "Summary of LRN Charges");
	$cur++;
	$objPHPExcel->getActiveSheet()->mergeCells('A'.$cur.':B'.$cur);
	$objPHPExcel->getActiveSheet()->mergeCells('C'.$cur.':D'.$cur);
	$objPHPExcel->getActiveSheet()->mergeCells('E'.$cur.':F'.$cur);
	$objPHPExcel->setActiveSheetIndex(0)
	            ->setCellValue('A'.$cur, 'Number of LRN Calls')
	            ->setCellValue('C'.$cur, 'LRN Rate(US)')
	            ->setCellValue('E'.$cur, 'LRN Total Cost(US)');
	$cur++;
	$objPHPExcel->getActiveSheet()->mergeCells('A'.$cur.':B'.$cur);
	$objPHPExcel->getActiveSheet()->mergeCells('C'.$cur.':D'.$cur);
	$objPHPExcel->getActiveSheet()->mergeCells('E'.$cur.':F'.$cur);
	
	$objPHPExcel->setActiveSheetIndex(0)
	            ->setCellValue('A'.$cur, $invoice['lrn_numbers'])
	            ->setCellValue('C'.$cur, $invoice['lrn_rate'])
	            ->setCellValue('E'.$cur, $invoice['lrn_cost']);
	$cur++;	
}

//tpl
$cur++;
$objPHPExcel->getActiveSheet()->mergeCells('A'.$cur.':F'.$cur);
$objPHPExcel->setActiveSheetIndex(0)
	            ->setCellValue('A'.$cur, $pdf_tpl);

$objPHPExcel->getActiveSheet()->getStyle('A2:E6')->applyFromArray(
		array(			
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
			),
		)
);
$objPHPExcel->getActiveSheet()->getStyle('A8:E8')->applyFromArray(
		array(
			'font'    => array(
				'bold'      => true
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
			),
			'borders' => array(
				'top'     => array(
 					'style' => PHPExcel_Style_Border::BORDER_THIN
 				)
			),
			'fill' => array(
	 			'type'       => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
	  			'rotation'   => 90,
	 			'startcolor' => array(
	 				'argb' => 'FFFFFFFF'//'FFA0A0A0'
	 			),
	 			'endcolor'   => array(
	 				'argb' => 'FFFFFFFF'
	 			)
	 		)
		)
);

$objPHPExcel->getActiveSheet()->getStyle('A'.$minute_cur.':E'.$minute_cur)->applyFromArray(
		array(
			'font'    => array(
				'bold'      => true
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
			),
		)
);

// Rename sheet
//echo date('H:i:s') . " Rename sheet\n";
$objPHPExcel->getActiveSheet()->setTitle('Invoice');


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);


// Save Excel 2007 file
//echo date('H:i:s') . " Write to Excel2007 format\n";
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	$save_file = date("Ymd-His") . "_invoice.xlsx";
	//$objWriter->save(str_replace('.php', '.xlsx', __FILE__));
	$objWriter->save('/tmp/'.$save_file);
	return $save_file;
/*header("Content-Type: application/vnd.ms-excel; charset=UTF-8");
header("Content-Disposition: inline; filename=\"" . $save_file . "\"");
echo file_get_contents('/tmp/'.$save_file);
return $return;*/
	}		
		

}
?>
