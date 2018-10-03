	
	<?php if(isset($simulated_info['Origination']['O-Actions'][0]['O-method'])){?>
	<span  style="font-weight: bold;"><?php echo __('Ingress Action',true);?></span>
	<table class="list"  style="margin-top: 20px; ">
		<thead>
			<tr>
			<td  width="30%"><?php echo __('Target',true);?></td>
				<td   width="30%"><?php echo __('action',true);?></td>
				<td   width="30%"><?php echo __('digit',true);?></td>
</tr>
		</thead>
		<tbody>
	<?php

			#only one element
	if(array_key_exists('O-action',$simulated_info['Origination']['O-Actions'][0]['O-method']))
	{
		$method = $simulated_info['Origination']['O-Actions'][0];
	
						foreach ($method as $key=>$value){
		?>
		<tr>
	<td></td>
	<td><?php echo  		array_keys_value($value,'O-action') ?></td>
<td><?php echo  		array_keys_value($value,'O-digit') ?></td>

</tr>
<?php }?>

		<?php 
	}
	#这里可以有多个 action
	else
	{?>
		
		
		
		<?php
				$method = $simulated_info['Origination']['O-Actions'][0]['O-method'];
						foreach ($method as $key=>$value){
		?>
<tr>
	<td></td>
	<td><?php echo  array_keys_value($value,'O-action') ?></td>
<td><?php echo  		array_keys_value($value,'O-digit') ?></td>

</tr>
<?php }?>
<?php }?>
<tr>
	<td><?php echo __('Digit-Maped-ANI',true);?></td>
<td ><?php echo $simulated_info['Origination']['O-Digit-Maped-ani']?></td>
	<td></td>
</tr>
		</tbody>
	</table>
	
	<?php }?>