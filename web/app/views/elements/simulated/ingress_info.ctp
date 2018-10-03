
	<span   style="font-weight: bold;color: #4B9100"><?php echo __('Ingress Info',true);?></span>
	<table class="list"  style="margin-top: 20px;">
		<thead>
			<tr>
			<td><?php echo __('carrier',true);?></td>
						<td><?php echo __('Trunk',true);?></td>
										<td><?php echo __('host',true);?></td>
				<td><?php echo __('Src-ANI',true);?></td>
				<td><?php echo __('Src-DNIS',true);?></td>
				<td><?php echo __('Src-IP',true);?></td>
				<td><?php echo __('Static-Route',true);?></td>
				<td><?php echo __('Dynamic-Route',true);?></td>
				<td><?php echo __('Routing-Plan',true);?></td>
				<td><?php echo __('Routing-Plan-Prefix',true);?></td>
				
				
</tr>
		</thead>
		<tbody>
		
<tr>
<td><?php echo  $appSimulateCall->format_carrier(array_keys_value($simulated_info,'Origination.O-Carrier')) ?></td>

		<?php if(isset($simulated_info['Origination']['O-Host-Trunk'])){?>
				<td  style="color: red"><?php echo  $appSimulateCall->format_trunk(array_keys_value($simulated_info,'Origination.O-Host-Trunk')) ?></td>
				<?php }else{?>
				<td><?php echo  $appSimulateCall->format_trunk(array_keys_value($simulated_info,'Origination.O-Trunk')) ?></td>
				<?php }?>
				
				
				<td><?php echo  $appSimulateCall->format_host_id(array_keys_value($simulated_info,'Origination.O-Host')) ?></td>
	<td><?php echo  array_keys_value($simulated_info,'Origination.O-Src-Ani') ?></td>
<td><?php echo  array_keys_value($simulated_info,'Origination.O-Src-Dnis') ?></td>
<td><?php echo  array_keys_value($simulated_info,'Origination.O-Src-IP') ?></td>





<td><?php echo  $appSimulateCall->format_static_route_id(array_keys_value($simulated_info,'Origination.O-Static-Route')) ?></td>
<td><?php echo  $appSimulateCall->format_dy_route_id(array_keys_value($simulated_info,'Origination.O-Dynamic-Route')) ?></td>
<td><?php echo  $appSimulateCall->format_route_plan_id(array_keys_value($simulated_info,'Origination.O-Routing-Plan')) ?></td>
<td><?php echo  array_keys_value($simulated_info,'Origination.O-Routing-Plan-Prefix') ?></td>
	


</tr>
		</tbody>
	</table>



		<?php if(isset($simulated_info['Origination']['Origination.O-Error'])){?>
	<span   style="font-weight: bold;color: red"><?php echo __('Error',true);?></span>
		<table class="list"  style="margin-top: 20px;">
		<tbody>
<tr>
<td style="color: red"><?php echo  array_keys_value($simulated_info,'Origination.O-Error') ?></td>
</tr>
		</tbody>
	</table>

<?php }?>	





