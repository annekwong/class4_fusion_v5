	<?php if(isset($simulated_info['Terminations']['T-Trunks']['T-Actions'][0]['T-method'])){?>
	<span  style="font-weight: bold;"><?php  echo "Digit Mapping Ani";?></span>
	<table class="list"  style="margin-top: 20px; ">
		<thead>
			<tr>
			<td  width="30%"></td>
				<td   width="30%"><?php echo __('action',true);?></td>
				<td   width="30%"><?php echo __('digit',true);?></td>
</tr>
		</thead>
		<tbody>
		
		
			<?php

			#only one element
	if(array_key_exists('T-action',$simulated_info['Terminations']['T-Trunks']['T-Actions'][0]['T-method']))
	{
		$method = $simulated_info['Terminations']['T-Trunks']['T-Actions'][0];
		foreach ($method as $key=>$value){
		?>
		<tr>
	<td></td>
	<td><?php echo  		array_keys_value($value,'T-action') ?></td>
<td><?php echo  		array_keys_value($value,'T-digit') ?></td>

</tr>
<?php }?>

		<?php 
	}
	#这里可以有多个 action
	else
	{?>
		
		
		<?php
		$method = $simulated_info['Terminations']['T-Trunks']['T-Actions'][0]['T-method'];
						foreach ($method as $key=>$value){
		?>
<tr>
	<td></td>
	<td><?php echo  array_keys_value($value,'T-action') ?></td>
<td><?php echo  		array_keys_value($value,'T-digit') ?></td>

</tr>
<?php }?>

<?php }?>
<tr>
	<td><?php echo __('Digit-Maped-ANI',true);?></td>
<td ><?php echo $simulated_info['Terminations']['T-Trunks']['T-Ani']?></td>
	<td></td>
</tr>
		</tbody>
	</table>
	
	<?php }?>