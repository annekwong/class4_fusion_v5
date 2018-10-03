<center>
<table class="list" style="width:50%">
<?php 
	$m = new BuyOrder();
?>
	<tr>
		<th><?php echo __('country',true);?></th>
		<td><?php echo array_keys_value($order,"0.country")?></td>
		<th><?php echo __('status',true);?></th>
		<td><?php echo $appOrderResponses->order_status(array_keys_value($order,"0.status"))?></td>
		<th><?php echo __('rate',true);?></th>
		<td class="last"><?php echo $appOrderResponses->format_price(array_keys_value($order,"0.rate"))?></td>		
	</tr>
	<tr>	
	<?php
		$acd = array_keys_value($order,"0.acd");
		$asr = array_keys_value($order,"0.asr");
		if(empty($acd)){$acd = '-'; }else{$acd = number_format($acd,2);}
		if(empty($asr)){$asr = '-'; }else{$asr = number_format($asr,2).'%';}
?>
		<th><?php echo __('acd',true);?></th>
		<td><?php echo $acd?></td>

		<th><?php echo __('asr',true);?></th>
		<td><?php echo $asr?></td>
		<th><?php echo __('G729',true);?></th>
		<td class="last"><?php echo array_keys_value($order,"0.g729")?></td>
	</tr>	
	<tr>
		<th><?php echo __('commit',true);?></th>		
		<td><?php 
			if($m->is_private($order)){
				echo $appOrderResponses->format_radio(array(false=>"No",true=>"Yes"),array_keys_value($order,"0.is_commit"));
			}else{
				echo '-';
			}
		?></td>
		<th><?php echo __('Commit minutes',true);?></th>		
		<td><?php 
			if($m->is_private($order)){
				echo $appOrderResponses->format_order_commit_minutes($order);
			}else{
				echo '-';
			}
		?></td>
		<th><?php echo __('fax',true);?></th>
		<td class="last"><?php echo array_keys_value($order,"0.fax")?></td>
	</tr>
	<tr>
		<th><?php echo __('Expire Time',true);?></th>
		<td><?php 
			if($m->is_private($order)){
				echo $appOrder->format_time($appOrderResponses->format_order_expire_time($order));
			}else{
				echo '-';
			}
		?></td>
		<th><?php echo __('start_time',true);?></th>
		<td><?php echo $appOrder->format_time(array_keys_value($order,"0.create_time"))?></td>
		<th><?php echo __('cli',true);?></th>
		<td class="last"><?php echo array_keys_value($order,"0.cli")?></td>
	</tr>
	
</table>
</center>
