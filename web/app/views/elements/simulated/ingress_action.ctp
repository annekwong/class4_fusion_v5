
<span style="font-weight: bold; color: #4B9100"><?php echo __('Ingress Action',true);?></span>
<table class="list" style="margin-top: 20px;">
	<thead>
		<tr>
			<td><?php echo __('Target',true);?></td>
			<td><?php echo __('Action Type',true);?></td>
			<td width="10%"><?php echo __('Char to Add of Char to Dalete',true);?></td>
			<td><?php echo __('Result ANI/Result DNIS',true);?></td>
			
		</tr>
	</thead>
		<tbody>
		
		<?php 
		
if(isset($simulated_info['Origination']['O-Digit-Maped-ani']['O-Actions']['O-method'])){
 	if(!isset($simulated_info['Origination']['O-Digit-Maped-ani']['O-Actions']['O-method']['O-action']) ){
 		# 主叫多条转换规则
 	?>
			<?php foreach ($simulated_info['Origination']['O-Digit-Maped-Dnis']['O-Actions']['O-method'] as  $key =>$value ){?>
		<tr>
	<td><?php echo __('ani',true);?>(<?php  echo  $appSimulateCall->show_action_ani_pre($key,array_keys_value($simulated_info,'Origination.O-Src-Ani'))?>)</td>
	<td><?php echo  $appSimulateCall->show_action_type(array_keys_value($value,'O-action')) ?></td>
	<td><?php echo  array_keys_value($value,'O-digit') ?></td>
	<td><?php echo  $appSimulateCall->show_action_ani_sub($key+1,array_keys_value($simulated_info,'Origination.O-Digit-Maped-ani.O-Digit-ani'),array_keys_value($value,'O-action'),array_keys_value($value,'O-digit')) ?></td>
	</tr>
<?php }
 	# 主叫1条转换规则
 	}else{?>
	<tr>
	<td><?php echo __('ani',true);?>(<?php echo  array_keys_value($simulated_info,'Origination.O-Src-Ani') ?>)</td>
	<td><?php echo  $appSimulateCall->show_action_type(array_keys_value($simulated_info,'Origination.O-Digit-Maped-ani.O-Actions.O-method.O-action')) ?></td>
	<td><?php echo array_keys_value($simulated_info,'Origination.O-Digit-Maped-ani.O-Actions.O-method.O-digit')?></td>
	<td><?php echo  array_keys_value($simulated_info,'Origination.O-Digit-Maped-ani.O-Digit-ani') ?></td>
</tr>

<?php }}else{?>

		<tr>
	<td><?php echo __('ani',true);?>(<?php echo  array_keys_value($simulated_info,'Origination.O-Src-Ani') ?>)</td>
	<td></td>
	<td></td>
	<td><?php echo  array_keys_value($simulated_info,'Origination.O-Digit-Maped-ani.O-Digit-ani') ?></td>
</tr>
<?php }?>





		<?php 
		if( isset($simulated_info['Origination']['O-Digit-Maped-Dnis']['O-Actions']['O-method'])){
			# 被叫多条转换规则
		 	if( !isset($simulated_info['Origination']['O-Digit-Maped-Dnis']['O-Actions']['O-method']['O-action'])){
		 	?>
		
		<?php foreach ($simulated_info['Origination']['O-Digit-Maped-Dnis']['O-Actions']['O-method'] as  $key =>$value ){
			?>
		<tr>
	<td><?php echo __('dnis',true);?>(<?php  echo  $appSimulateCall->show_action_dnis_pre($key,array_keys_value($simulated_info,'Origination.O-Src-Dnis'))?>)</td>
	<td><?php echo  $appSimulateCall->show_action_type(array_keys_value($value,'O-action')) ?></td>
	<td><?php echo  array_keys_value($value,'O-digit') ?></td>
	<td><?php echo  $appSimulateCall->show_action_dnis_sub($key+1,array_keys_value($simulated_info,'Origination.O-Digit-Maped-Dnis.O-Digit-Dnis'),array_keys_value($value,'O-action'),array_keys_value($value,'O-digit')) ?></td>
</tr>

<?php }
		 }else{
		 	# 被叫1条转换规则
		 	?>
		<tr>
	<td><?php echo __('dnis',true);?>(<?php echo  array_keys_value($simulated_info,'Origination.O-Src-Dnis') ?>)</td>
	<td><?php echo  $appSimulateCall->show_action_type(array_keys_value($simulated_info,'Origination.O-Digit-Maped-Dnis.O-Actions.O-method.O-action')) ?></td>
	<td><?php echo array_keys_value($simulated_info,'Origination.O-Digit-Maped-Dnis.O-Actions.O-method.O-digit')?></td>
	<td><?php echo  array_keys_value($simulated_info,'Origination.O-Digit-Maped-Dnis.O-Digit-Dnis') ?></td>
</tr>
		 <?php
		  }} ?>
		</tbody>
</table>


