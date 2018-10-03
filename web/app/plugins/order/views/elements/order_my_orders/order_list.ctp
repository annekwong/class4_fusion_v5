<center>
<?php if(empty($order_responses)):?>
	<?php echo $this->element('common/no_result')?>
<?php else:?>
<?php $prefix = Inflector::camelize($do_action) == "Buy" ? "Sell" : "Buy"; ?>

	<table class="list">
		<tr>
			<td><?php echo __('Rsp #',true);?></td>
			<td><?php echo __('Country',true);?></td>
			<td><?php echo __('rate',true);?></td>
			<td><?php echo __('commit',true);?></td>
			<td><?php echo __('commit minutes',true);?></td>		
			<td><?php echo __('status',true);?></td>
			<td><?php echo __('create_time',true);?></td>
			<td><?php echo __('update_time',true);?></td>
			<td><td><?php echo __('Expire Time',true);?></td></td>
			<td style="text-align:center;"><td><?php echo __('Operation',true);?></td></td>
		</tr>
		<?php foreach($order_responses as $order_response):?>
		<tr id="response_<?php echo array_keys_value($order_response,$prefix."OrderResponse.id")?>" rel="tooltip">
			<td><?php echo array_keys_value($order_response,$prefix."OrderResponse.id")?></td>					
			<td><?php echo array_keys_value($order_response,$prefix."Order.country")?></td>
			<td><?php echo $appOrderResponses->format_price(array_keys_value($order_response,$prefix."Order.rate"))?></td>
			<td><?php echo $appOrderResponses->format_radio(array(false=>"No",true=>"Yes"),array_keys_value($order_response,$prefix."OrderResponse.is_commit"))?></td>
			<td><?php echo $appOrderResponses->format_order_commit_minutes($order_response,$prefix."OrderResponse")?></td>
			<td>
			<?php if($prefix == "Buy"):?>
				<?php echo $appOrderResponses->buy_order_response_status(array_keys_value($order_response,$prefix."OrderResponse.status"))?>
			<?php else:?>
				<?php echo $appOrderResponses->sell_order_response_status(array_keys_value($order_response,$prefix."OrderResponse.status"))?>
			<?php endif;?>
			</td>
			<td><?php echo $appOrder->format_time(array_keys_value($order_response,$prefix."OrderResponse.create_time"))?></td>
			<td><?php echo $appOrder->format_time(array_keys_value($order_response,$prefix."OrderResponse.update_time"))?></td>
			<td><?php echo $appOrder->format_time($appOrderResponses->format_order_expire_time($order_response,$prefix."OrderResponse"))?></td>
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
<style>		
	#tooltip dd { float:left; display:inline-block;width:50px;}
	#tooltip dt { float:left;clear:both; display:inline-block;width:120px;font-weight:bold;}
</style>
<?php $m = new OrderCode() ?>
<?php foreach($order_responses as $order_response):?>
<?php if($prefix == "Buy"):?>
	<?php $order_code = $m->find_buy_code_by_order_id(array_keys_value($order_response,"BuyOrderResponse.buy_order_id"))?>
<?php else:?>
	<?php $order_code = $m->find_sell_code_by_order_id(array_keys_value($order_response,"SellOrderResponse.sell_order_id"))?>
<?php endif;?>
<?php if(!empty($order_code)):?>
<dl id="response_<?php echo array_keys_value($order_response,$prefix."OrderResponse.id") ?>-tooltip" class="tooltip">
<?php	
	$order_code_regions = array_map(create_function('$d','return array_keys_value($d,"OrderCode.code_name");'),$order_code);
	$order_code_regions = array_unique($order_code_regions);
	foreach($order_code_regions as $order_code_region){
		echo "<dt>$order_code_region</dt>";
		$codes = array_filter($order_code,create_function('$d','return array_keys_value($d,"OrderCode.code_name") == "'.$order_code_region.'";'));
			foreach($codes as $code){
				echo "<dd>".array_keys_value($code,"OrderCode.code")."</dd>";								
			}				
		}
?>
</dl>
<?php endif;?>
<?php endforeach;?>
<?php endif; ?>
</center>
