<?php
require_once('./config/lang/eng.php');
require_once('./tcpdf.php');
function create_PDF($html,$name){
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
?>
