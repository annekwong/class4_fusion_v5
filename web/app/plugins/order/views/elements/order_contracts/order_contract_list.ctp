<?php if(empty($contracts)):?>
	<?php echo $this->element('common/no_result')?>
<?php else:?>
<table class="list">
	<thead>
		<tr>
			<td><?php echo $appOrder->show_order('contract_id',__('Confirmd Order#',true))?></td>
			<td></td>			
			<td><?php echo $appOrder->show_order('country',__('country',true))?></td>
			<td><?php echo $appOrder->show_order('rate',__('rate',true))?></td>
			<td><?php echo __('Private/Public',true);?></td>				
			<td><?php echo __('commit',true);?></td>
			<td><?php echo __('commit minutes',true);?></td>
			<td><?php echo __('status',true);?></td>	
			<td><?php echo $appOrder->show_order('contract_create_time',__('Start Time',true))?></td>
			<td><?php echo $appOrder->show_order('contract_update_time', __('Update Time',true))?></td>		
			<td><?php echo $appOrder->show_order('contract_expire_time',__('Expire Time',true))?></td>
			<td><?php echo $appOrder->show_order('user_name',__('By Updated',true))?></td>
			<td style="text-align:center;width:200px"><td><?php echo __('Operation',true);?></td></td>
		</tr>
	</thead>
	<tbody>	
	<?php $m = new BuyOrder?>
	<?php foreach($contracts as $contract):?>	
	<tr rel="tooltip" id="contract_<?php echo array_keys_value($contract,"0.contract_id")?>">
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
	</tr>
	<?php endforeach;?>	
	</tbody>
</table>
<?php endif;?>
<style>		
	#tooltip dd { float:left; display:inline-block;width:50px;}
	#tooltip dt { float:left;clear:both; display:inline-block;width:120px;font-weight:bold;}
</style>
<?php foreach($order_codes as $k => $order_code):?>
<?php if(!empty($order_code)):?>
<dl id="contract_<?php echo $k ?>-tooltip" class="tooltip">
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
