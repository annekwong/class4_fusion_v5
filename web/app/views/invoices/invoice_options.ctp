<?php 
$options=Array();
foreach($Invoice as $in){
	$options[$in[0]['invoice_number']]=$in[0]['invoice_number'];
}
echo $form->input('Invoice',Array('div'=>false,'label'=>false,'options'=>$options));
?>