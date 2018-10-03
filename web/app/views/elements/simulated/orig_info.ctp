
	<span   style="font-weight: bold;color: #4B9100"><?php  echo "Origination";?></span>
	<table class="list"  style="margin-top: 20px;">
		<thead>
			<tr>
				<td><?php echo __('Src-ANI',true);?></td>
				<td><?php echo __('Src-DNIS',true);?></td>
				<td><?php echo __('Src-IP',true);?></td>
				<td><?php echo __('carrier',true);?></td>
				<td><?php echo __('Trunk',true);?></td>
		
				<td><?php echo __('Ingress IP',true);?></td>
				<td><?php echo __('Static-Route',true);?></td>
				<td><?php echo __('Dynamic-Route',true);?></td>
				<td><?php echo __('Routing-Plan',true);?></td>
				<td><?php echo __('Routing-Plan-Prefix',true);?></td>
					<td style="color: red"><?php echo __('Error',true);?></td>
				
				
</tr>
		</thead>
		<tbody>
		
<tr>
	<td><?php echo  array_keys_value($simulated_info,'Origination.O-Src-Ani') ?></td>
<td><?php echo  array_keys_value($simulated_info,'Origination.O-Src-Dnis') ?></td>
<td><?php echo  array_keys_value($simulated_info,'Origination.O-Src-IP') ?></td>
<td><?php echo  $appSimulateCall->format_carrier(array_keys_value($simulated_info,'Origination.O-Carrier')) ?></td>

		<?php if(isset($simulated_info['Origination']['O-Host-Trunk'])){?>
				<td  style="color: red"><?php echo  $appSimulateCall->format_trunk(array_keys_value($simulated_info,'Origination.O-Host-Trunk')) ?></td>
				<?php }else{?>
				<td><?php echo  $appSimulateCall->format_trunk(array_keys_value($simulated_info,'Origination.O-Trunk')) ?></td>
				<?php }?>

<td><?php echo  $appSimulateCall->format_host_id(array_keys_value($simulated_info,'Origination.O-Host')) ?></td>
<td><?php echo  $appSimulateCall->format_static_route_id(array_keys_value($simulated_info,'Origination.O-Static-Route')) ?></td>
<td><?php echo  $appSimulateCall->format_dy_route_id(array_keys_value($simulated_info,'Origination.O-Dynamic-Route')) ?></td>
<td><?php echo  $appSimulateCall->format_route_plan_id(array_keys_value($simulated_info,'Origination.O-Routing-Plan')) ?></td>
<td><?php echo  array_keys_value($simulated_info,'Origination.O-Routing-Plan-Prefix') ?></td>
	
<td style="color: red"><?php echo  array_keys_value($simulated_info,'Origination.O-Error') ?></td>

</tr>
		</tbody>
	</table>


<table class="list"  style="margin-top: 20px;">
		<thead>
			<tr>
			<td><?php echo __('Ingress IP cps',true);?></td>
				<td><?php echo __('Ingress IP cap',true);?></td>
				<td><?php echo __('Ingress resource cps',true);?></td>
				<td><?php echo __('Ingress resource cap',true);?></td>
				
				<td><?php echo __('Digit-Translation-ANI',true);?></td>
				<td><?php echo __('Digit-Translation-DNIS',true);?></td>
				<td><?php echo __('DNIS-Only',true);?></td>
				<td><?php echo __('Rate',true);?></td>
				<td><?php echo __('Margin',true);?></td>


</tr>
		</thead>
		<tbody>
		
<tr>
	
		<td><?php echo  array_keys_value($simulated_info,'Origination.O-Host-Cps') ?></td>
			<td><?php echo  array_keys_value($simulated_info,'Origination.O-Host-Cap') ?></td>
				<td><?php echo  array_keys_value($simulated_info,'Origination.O-Trunk-Cps') ?></td>
					<td><?php echo  array_keys_value($simulated_info,'Origination.O-Trunk-Cap') ?></td>
					
					
						<td><?php echo  array_keys_value($simulated_info,'Origination.O-Digit-Translation-ani') ?></td>
							<td><?php echo  array_keys_value($simulated_info,'Origination.O-Digit-Translation-Dnis') ?></td>
								<td><?php echo  array_keys_value($simulated_info,'Origination.O-Dnis-Only') ?></td>
									<td><?php echo  array_keys_value($simulated_info,'Origination.O-Rate') ?></td>
										<td><?php echo  array_keys_value($simulated_info,'Origination.O-Margin') ?></td>


</tr>
		</tbody>
	</table>

