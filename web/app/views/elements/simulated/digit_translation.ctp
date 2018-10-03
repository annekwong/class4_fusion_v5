<span style="font-weight: bold; color: #4B9100"><?php echo __('Digit Translation',true);?></span>
<table class="list" style="margin-top: 20px;">
	<thead>
		<tr>
			<td><?php echo __('ani',true);?>/<?php echo __('dnis',true);?></td>
			<td><?php echo __('action',true);?></td>
			<td><?php echo __('Char to Add',true);?></td>
			<td><?php echo __('Result ANI/Result DNIS',true);?></td>
			
		</tr>
	</thead>
	<tbody>

		<tr>
			<td><?php echo __('ani',true);?>(<?php echo  array_keys_value($simulated_info,'Origination.O-Src-Ani') ?>)</td>
			<td></td>
			<td></td>
			<td><?php echo  array_keys_value($simulated_info,'Origination.O-Digit-Translation.O-Digit-Translation-ani') ?></td>
		</tr>
		
				<tr>
			<td><?php echo __('dnis',true);?>(<?php echo  array_keys_value($simulated_info,'Origination.O-Src-Dnis') ?>)</td>
			<td></td>
			<td></td>
			<td><?php echo  array_keys_value($simulated_info,'Origination.O-Digit-Translation.O-Digit-Translation-Dnis') ?></td>
		</tr>
		
	</tbody>
</table>
