<?php $m = new BuyOrder(); ?>
<?php $prefix = Inflector::camelize($do_action); ?>	
<?php $is_my_orders = strpos($referer,"my_orders");?>	
<?php if (isset ( $exception_msg ) && $exception_msg) :	?>
	<?php if($is_my_orders): ?>
		<td colspan="10"><?php echo $this->element ( 'common/exception_msg' );?></td>
	<?php else:?>
		<?php if($m->is_commit($order_response,$prefix."OrderResponse")):?>	
		<td colspan="8"><?php echo $this->element ( 'common/exception_msg' );?></td>
		<?php else:?>
		<td colspan="6"><?php echo $this->element ( 'common/exception_msg' );?></td>
		<?php endif;?>	
	<?php endif;?>
	
<?php else : ?>	
	<?php if($is_my_orders): ?>		
		<td><?php echo array_keys_value($order_response,$prefix."OrderResponse.id")?></td>
		<td><?php echo array_keys_value($order_response,$prefix."Order.country")?></td>
		<td><?php echo $appOrderResponses->format_price(array_keys_value($order_response,$prefix."Order.rate"))?></td>	
		<td><?php echo $appOrderResponses->format_radio(array(false=>"No",true=>"Yes"),array_keys_value($order_response,$prefix."OrderResponse.is_commit"))?></td>
		<td><?php echo $appOrderResponses->format_order_commit_minutes($order_response,$prefix."OrderResponse")?></td>		
		<td>		
		<?php if($prefix == "Buy"):?>
		<?php echo $appOrderResponses->sell_order_response_status(array_keys_value($order_response,$prefix."OrderResponse.status"))?>
		<?php else:?>
		<?php echo $appOrderResponses->buy_order_response_status(array_keys_value($order_response,$prefix."OrderResponse.status"))?>
		<?php endif;?>
		</td>
		<td><?php echo array_keys_value($order_response,$prefix."OrderResponse.create_time")?></td>
		<td><?php echo array_keys_value($order_response,$prefix."OrderResponse.update_time")?></td>
		<td><?php echo $appOrderResponses->format_order_expire_time($order_response,$prefix."OrderResponse")?></td>
		<td style="text-align:center;"  class="last">
		 <?php if($prefix == "Buy"):?>
			<?php echo $appOrderResponses->buy_order_response_operation($order_response);?>
		<?php else:?>
			<?php echo $appOrderResponses->sell_order_response_operation($order_response);?>
		<?php endif;?>
		</td>
	<?php else:?>
		<td><?php echo array_keys_value($order_response,$prefix."OrderResponse.id")?></td>
		<?php $resource_id = array_keys_value($order_response,$prefix."OrderResponse.resource_id")?>
		<?php $resource_info = $appOrderResponses->find_resource_acd_asr($resource_infos,$resource_id);?>
		<?php if(empty($resource_info)):?>
			<td></td><td></td>
		<?php else:?>
			<td><?php echo number_format(array_keys_value($resource_info,"0.acd_24h"))?></td>
			<td><?php echo number_format(array_keys_value($resource_info,"0.asr_24h").'%')?></td>
		<?php endif;?>
		<?php if($m->is_commit($order_response,$prefix."OrderResponse") && $m->is_private($order_response,$prefix."OrderResponse")):?>
			<td><?php echo $appOrderResponses->format_radio(array(false=>"No",true=>"Yes"),array_keys_value($order_response,$prefix."OrderResponse.is_commit"))?></td>
			<td><?php echo $appOrderResponses->format_order_commit_minutes($order_response,$prefix."OrderResponse")?></td>
		<?php endif;?>
		<td>
		<?php if($prefix == "Buy"):?>
			<?php echo $appOrderResponses->buy_order_response_status(array_keys_value($order_response,$prefix."OrderResponse.status"))?>
		<?php else:?>
			<?php echo $appOrderResponses->sell_order_response_status(array_keys_value($order_response,$prefix."OrderResponse.status"))?>
		<?php endif;?>
		</td>
		<td><?php echo array_keys_value($order_response,$prefix."OrderResponse.create_time")?></td>
		<td><?php echo array_keys_value($order_response,$prefix."OrderResponse.update_time")?></td>
		<?php if($m->is_commit($order_response,$prefix."OrderResponse") && $m->is_private($order_response,$prefix."OrderResponse")):?>
			<td><?php echo $appOrderResponses->format_order_expire_time($order_response,$prefix."OrderResponse")?></td>
		<?php endif;?>
		<td style="text-align:center;" class="last">
		 <?php if($prefix == "Buy"):?>
			<?php echo $appOrderResponses->buy_order_response_operation($order_response);?>
		<?php else:?>
			<?php echo $appOrderResponses->sell_order_response_operation($order_response);?>
		<?php endif;?>
		</td>	
	<?php endif;?>	

<?php endif;?>
