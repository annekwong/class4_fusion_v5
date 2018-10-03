<td><?php echo array_keys_value($o_order,"0.country")?></td>
<td><?php echo $appOrderResponses->format_price(array_keys_value($o_order,"0.rate"))?></td>
<td><?php echo $appOrderResponses->format_radio(array(false=>"No",true=>"Yes"),array_keys_value($o_order,"0.is_commit"))?></td>
<td><?php echo $appOrderResponses->format_order_commit_minutes($o_order)?></td>
<td><?php echo $appOrderResponses->order_status(array_keys_value($o_order,"0.status"))?></td>
<?php
		$acd = array_keys_value($o_order,"0.acd");
		$asr = array_keys_value($o_order,"0.asr");
		if(empty($acd)){$acd = '-'; }else{$acd = number_format($acd,2);}
		if(empty($asr)){$asr = '-'; }else{$asr = number_format($asr,2).'%';}

?>
<?php echo $appOrderResponses->order_list_col('acd',$acd,true)?>
<?php echo $appOrderResponses->order_list_col('asr',$asr,true)?>
<?php echo $appOrderResponses->order_list_col('cli',array_keys_value($o_order,"0.cli"),false)?>
<?php echo $appOrderResponses->order_list_col('g729',array_keys_value($o_order,"0.g729"),false)?>
<?php echo $appOrderResponses->order_list_col('fax',array_keys_value($o_order,"0.fax"),false)?>
<?php echo $appOrderResponses->order_list_col('create_time',array_keys_value($o_order,"0.create_time"),true)?>
<?php echo $appOrderResponses->order_list_col('update_time',array_keys_value($o_order,"0.update_time"),true)?>		
<td><?php echo $appOrderResponses->format_order_expire_time($o_order)?></td>
<td style="text-align:center">
	<?php if($do_action == 'buy'):?>
		<?php echo $appOrderResponses->private_buy_operation($order,$o_order)?>
	<?php else:?>
		<?php echo $appOrderResponses->private_sell_operation($order,$o_order)?>
	<?php endif;?>
</td>	
