<?php if (isset ( $exception_msg ) && $exception_msg) :	?>
	<?php	echo $this->element ( 'common/exception_msg' );?>
<?php else : ?>	
		<?php $m = new BuyOrder?>
		<td><?php echo array_keys_value($contract,"0.confirm_order_number")?></td>
		<td><?php echo $appOrderContracts->type($contract)?></td>
		<td><?php echo array_keys_value($contract,"0.country")?></td>
		<td><?php echo $appOrderContracts->format_price(array_keys_value($contract,"0.rate"))?></td>
		<td><?php echo $appOrderContracts->format_radio(array(false=>"Public",true=>"Private"),array_keys_value($contract,"0.is_private"))?></td>
		<?php if($m->is_private($contract)):?>
			<td><?php echo $appOrderContracts->format_radio(array(false=>"No",true=>"Yes"),array_keys_value($contract,"0.contract_is_commit"))?></td>
			<td><?php echo $appOrderContracts->format_order_commit_minutes($contract)?></td>
		<?php else:?>
			<td>-</td><td>-</td>
		<?php endif;?>
		<td><?php echo $appOrderContracts->order_status(array_keys_value($contract,"0.contract_status"))?></td>
		<td><?php echo $appOrder->format_time(array_keys_value($contract,"0.contract_create_time"))?></td>
		<td><?php echo $appOrder->format_time(array_keys_value($contract,"0.contract_update_time"))?></td>
		<?php if($m->is_private($contract)):?>
			<td><?php echo $appOrder->format_time($appOrderContracts->format_order_expire_time($contract))?></td>
		<?php else:?>
			<td>-</td>
		<?php endif;?>
		<td><?php echo array_keys_value($contract,"0.user_name")?></td>
		<td style="text-align:center"><?php echo $appOrderContracts->contract_status_operation($contract,'contract')?></td>	
<?php endif;?>