<?php  echo $form->create("Dynamicroute")?>
<table style="width:100%">
	<tr>
		<td><?php echo $form->input('name',Array('div'=>false,'label'=>false))?></td>
		<td><?php echo $form->input('routing_rule',Array('div'=>false,'label'=>false))?></td>
		<td><?php echo $form->input('time_profile_id',Array('div'=>false,'label'=>false))?></td>
		<td colspan=3>
			<div style="margin:5px">
				<ul>
					<li>
						<?php echo $form->input('',Array('div'=>false,'label'=>false,'type'=>'select'))?>
					</li>
					<li></li>
				</ul>
			</div>
		</td>
	</tr>
</table>
<?php echo $form->end()?>