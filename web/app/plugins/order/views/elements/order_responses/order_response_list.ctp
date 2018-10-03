<center>
<?php $m = new BuyOrder(); ?>
<?php $prefix = Inflector::camelize($do_action); ?>			
<table class="list" style="width:50%">
	<tr>
		<td><?php echo __('#Rsp',true);?></td>
		<td><?php echo __('acd',true);?></td>
		<td><?php echo __('asr',true);?></td>
		<?php if($m->is_commit($order) && $m->is_private($order)):?>	
			<td><?php echo __('commit',true);?></td>
			<td><?php echo __('commit minutes',true);?></td>
		<?php endif;?>
		<td><?php echo __('status',true);?></td>
		<td><?php echo __('create_time',true);?></td>
		<td><?php echo __('update_time',true);?></td>
		<?php if($m->is_commit($order) && $m->is_private($order)):?>	
		<td><?php echo __('Expire Time',true);?></td>
		<?php endif;?>
		<td style="text-align:center;"><td><?php echo __('Operation',true);?></td></td>
	</tr>
	<?php foreach($order_responses as $order_response):?>
	<tr id="response_<?php echo array_keys_value($order_response,$prefix."OrderResponse.id")?>">
		<td><?php echo array_keys_value($order_response,$prefix."OrderResponse.id")?></td>
		<?php $resource_id = array_keys_value($order_response,$prefix."OrderResponse.resource_id")?>
		<?php $resource_info = $appOrderResponses->find_resource_acd_asr($resource_infos,$resource_id);?>
		<?php if(empty($resource_info)):?>
			<td></td><td></td>
		<?php else:?>
			<td><?php echo array_keys_value($resource_info,"0.acd_24h")?></td>
			<td><?php echo number_format(array_keys_value($resource_info,"0.asr_24h"),2).'%'?></td>
		<?php endif;?>
		<?php if($m->is_commit($order) && $m->is_private($order)):?>
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
		<td><?php echo $appOrder->format_time(array_keys_value($order_response,$prefix."OrderResponse.create_time"))?></td>
		<td><?php echo $appOrder->format_time(array_keys_value($order_response,$prefix."OrderResponse.update_time"))?></td>
		<?php if($m->is_commit($order) && $m->is_private($order)):?>	
			<td><?php echo $appOrder->format_time($appOrderResponses->format_order_expire_time($order_response,$prefix."OrderResponse"))?></td>
		<?php endif;?>
		<td style="text-align:center;">
		<?php if($prefix == "Buy"):?>
			<?php echo $appOrderResponses->buy_order_response_operation($order_response);?>
		<?php else:?>
			<?php echo $appOrderResponses->sell_order_response_operation($order_response);?>
		<?php endif;?>
		</td>
	</tr>
	<?php endforeach;?>	
</table>
</center>
