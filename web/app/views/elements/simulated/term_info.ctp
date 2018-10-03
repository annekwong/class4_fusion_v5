
<span  style="font-weight: bolder;color:#912C00"><?php 
	$terminations = $simulated_info['Terminations'];
echo "Egress  Info";?>  </span>
<fieldset   class="query-box" ></fieldset>
<table class="list"  style="margin-top: 20px;">
		<thead>
			<tr>
				<td><?php echo __('Digit-Type',true);?></td>
				<td><?php echo __('Route-Digit',true);?></td>
</tr>
		</thead>
		<tbody>
<?php 
if( is_array($simulated_info['Terminations']['T-Digit-Type'])){
foreach($simulated_info['Terminations']['T-Digit-Type'] as $k=>$v)
	{?>
<tr>
	<td><?php echo  $simulated_info['Terminations']['T-Digit-Type'][$k]; ?></td>
	<td><?php echo  $simulated_info['Terminations']['T-Route-Digit'][$k];   ?></td>
</tr>
<?php }}else{?>
<tr>
	<td><?php echo  $simulated_info['Terminations']['T-Digit-Type']; ?></td>
	<td><?php echo  $simulated_info['Terminations']['T-Route-Digit'];   ?></td>
</tr>
<?php }?>
</tbody>
	</table>
<br/>
<?php if(isset($simulated_info['Terminations']['T-Trunks'][0])){?>
<span  style='font-weight: bold;color:#912C00'>
<?php 
	foreach($simulated_info['Terminations']['T-Trunks'] as $k=>$v)
	{
		$y=$k+1;
echo "  <span   style=' color: #FF6D06;font-weight: bold;font-size: 1em;'> Egress   Number  $y   </span>     ["; echo $appSimulateCall->format_trunk(array_keys_value($v,'T-Trunk')) ?>]</span>
	<table class="list"  style="margin-top: 20px;">
		<thead>
			<tr>
				<td><?php echo __('Egress Carriers',true);?></td>
				<td><?php echo __('Egress resource',true);?></td>
				<td><?php echo __('Egress rate',true);?></td>
				<td><?php echo __('Egress RateTable',true);?></td>
				<td><?php echo __('Egress resource limit cps',true);?></td>
				<td><?php echo __('Egress resource limit cap',true);?></td>


</tr>
		</thead>
		<tbody>
<tr>
	<td><?php echo  $appSimulateCall->format_carrier(array_keys_value($v,'T-Carrier')) ?></td>
	<td><?php echo  $appSimulateCall->format_trunk(array_keys_value($v,'T-Trunk')) ?></td>
	<td><?php echo  array_keys_value($simulated_info,'T-Rate') ?></td>
	<td><?php echo  $appSimulateCall->format_rate_table(array_keys_value($v,'T-Rate-Table')) ?></td>
	<td><?php echo  array_keys_value($v,'T-Trunk-Cps') ?></td>
	<td><?php echo  array_keys_value($v,'T-Trunk-Cap') ?></td>

</tr>
</tbody>
	</table>
	<!-- print  host -->
	<span  style="font-weight: bold;color:#912C00">
<?php 
echo "Egress ({$appSimulateCall->format_trunk(array_keys_value($v,'T-Trunk'))})==> Host"; ?></span>
	<table class="list"  style="margin-top: 20px;">
		<thead>
			<tr>
				<td><?php echo __('Egress-Host-ID',true);?></td>
				<td><?php echo __('Egress-IP',true);?></td>
				<td><?php echo __('Host-Port',true);?></td>
				<td><?php echo __('Host-Cps',true);?></td>
				<td><?php echo __('Host-Cap',true);?></td>
				


</tr>
		</thead>
		<tbody>
		
<?php 

if(isset($v['T-Hosts']['T-Host-ID'])){
	if(is_array($v['T-Hosts']['T-Host-ID'])){
	foreach($v['T-Hosts']['T-Host-ID'] as $k1=>$v1)
	{
?>
<tr>
	<td><?php echo  $v1; ?></td>
	<td><?php echo  $v['T-Hosts']['T-Host-IP'][$k1]; ?></td>
	<td><?php echo  $v['T-Hosts']['T-Host-Port'][$k1];  ?></td>
	<td><?php echo $v['T-Hosts']['T-Host-Cps'][$k1]; ?></td>
	<td><?php echo  $v['T-Hosts']['T-Host-Cap'][$k1];  ?></td>
	
</tr>
<?php 
	}
	}else{
		?>

		<tr>
	<td><?php echo  $v['T-Hosts']['T-Host-ID']; ?></td>
	<td><?php echo  $v['T-Hosts']['T-Host-IP']; ?></td>
	<td><?php echo  $v['T-Hosts']['T-Host-Port'];  ?></td>
	<td><?php echo $v['T-Hosts']['T-Host-Cps']; ?></td>
	<td><?php echo  $v['T-Hosts']['T-Host-Cap'];  ?></td>
</tr>
		<?php 
	}
}else{
?>
<tr>
	<td></td>
	<td></td>
	<td></td>
	<td></td>
	<td></td>
	
</tr>
<?php }?>
		</tbody>
	</table>


<span style="font-weight: bold;color:#912C00">Egress(<?php echo  $appSimulateCall->format_trunk(array_keys_value($v,'T-Trunk'))?>)==>Action</span>
<table class="list"  style="margin-top: 20px;">
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
 	 if(isset($simulated_info['Origination']['O-Digit-Translation']['O-Digit-Translation-ani']))
{		
		$egress_ani=  $simulated_info['Origination']['O-Digit-Translation']['O-Digit-Translation-ani'];
	}else{
		 if(isset($simulated_info['Origination']['O-Digit-Maped-ani']['O-Digit-ani']))
	{
		$egress_ani=  $simulated_info['Origination']['O-Digit-Maped-ani']['O-Digit-ani']; 
	}else{
		$egress_ani=  array_keys_value($simulated_info,'Origination.O-Src-Ani') ;
	}
	}
if(isset($v)){
 	if(!isset($v['T-Digit-Maped-ani']['T-Actions']['T-method']['T-action']) ){
 		 	if(isset($v['T-Digit-Maped-ani']['T-Actions']['T-method']) ){
 		# 主叫多条转换规则
?>
	<?php foreach ($v['T-Digit-Maped-ani']['T-Actions']['T-method'] as  $key =>$value ){?>
<tr>
	

	<td><?php echo __('ani',true);?>(<?php  echo  $appSimulateCall->egress_show_action_ani_pre($key,$egress_ani)?>)</td>
	<td><?php echo  $appSimulateCall->show_action_type(array_keys_value($value,'T-action'))?></td>
	<td><?php echo  array_keys_value($value,'T-digit') ?></td>

		<td><?php echo  $appSimulateCall->egress_show_action_ani_sub($key+1,$egress_ani,array_keys_value($value,'O-action'),array_keys_value($value,'O-digit')) ?></td>
	
</tr>
<?php }
 		 	}
 	# 主叫1条转换规则
}else{?>
<tr>
	<td><?php echo __('ani',true);?>(<?php  echo 	$egress_ani;	  ?>)</td>

	<td><?php echo  $appSimulateCall->show_action_type(array_keys_value($v,'T-Digit-Maped-ani.T-Actions.T-method.T-action')) ?></td>
	<td><?php echo array_keys_value($v,'T-Digit-Maped-ani.T-Actions.T-method.T-digit')?></td>
	<td><?php echo  array_keys_value($v,'T-Digit-Maped-ani.T-Ani') ?></td>
</tr>
 <?php 	}}else{?>

 
 <tr>
	<td><?php echo __('ani',true);?>(<?php  echo 	$egress_ani; ?>)</td>
	<td></td>
	<td></td>
	<td><?php echo __('ani',true);?>(<?php  echo 	$egress_ani;	  ?>)</td>
</tr>
<?php }?>
		<?php 
		if( isset($v)){
			# 被叫多条转换规则
		 	if(!isset($v['T-Digit-Maped-Dnis']['T-Actions']['T-method']['T-action'])){
		 	?>
		<?php 
		if(isset($v['T-Digit-Maped-Dnis']['T-Actions']['T-method'])){
		foreach ($v['T-Digit-Maped-Dnis']['T-Actions']['T-method']  as  $key =>$value){
			?>
		<tr>
	<td><?php echo __('dnis',true);?>(<?php  echo  $appSimulateCall->egress_show_action_dnis_pre($key,array_keys_value($simulated_info,'Terminations.T-Route-Digit'))?>)</td>
	<td><?php echo  $appSimulateCall->show_action_type(array_keys_value($value,'T-action')) ?></td>
	<td><?php echo  array_keys_value($value,'T-digit') ?></td>
<td><?php echo  $appSimulateCall->egress_show_action_dnis_sub($key+1,array_keys_value($simulated_info,'Terminations.T-Route-Digit'),array_keys_value($value,'O-action'),array_keys_value($value,'O-digit')) ?></td>
</tr>
<?php }
		}else{?>
			 <tr>
	<td><?php echo __('ani',true);?>(<?php  echo 	$egress_ani; ?>)</td>
	<td></td>
	<td></td>
	<td><?php echo __('ani',true);?>(<?php  echo 	$egress_ani;	  ?>)</td>
</tr>
		<?php }
		 }
		 if(isset($v['T-Digit-Maped-Dnis']['T-Actions']['T-method']['T-action']))
		 {
		 	# 被叫1条转换规则
		 	?>
		<tr>
	<td><?php echo __('dnis',true);?>(<?php echo  array_keys_value($simulated_info,'Terminations.T-Route-Digit') ?>)</td>
	<td><?php echo  $appSimulateCall->show_action_type(array_keys_value($v,'T-Digit-Maped-Dnis.T-Actions.T-method.T-action')) ?></td>
	<td><?php echo array_keys_value($v,'T-Digit-Maped-Dnis.T-Actions.T-method.T-digit')?></td>
	<td><?php echo  array_keys_value($v,'T-Digit-Maped-Dnis.T-Dnis') ?></td>
</tr>
		 <?php
		  }} ?>
		</tbody>
	</table>
<fieldset   class="query-box" ></fieldset>
<?php } }else{ echo  $this->element('simulated/term_one');}?>
