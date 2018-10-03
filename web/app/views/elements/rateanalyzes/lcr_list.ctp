<table class="list">
	<thead>
		<tr>
				<td><?php echo __('country',true);?></td>
				<td><?php echo __('code_name',true);?></td>
				<td><?php echo __('code',true);?></td>
				<td></td>
				<td></td>
		</tr>
	</thead>
	<tbody>
	<?php foreach($CodeList as $List):?>
		<tr>
				<td><?php echo $List['Code']['country']?></td>
				<td><?php echo $List['Code']['name']?></td>
				<td><?php echo $List['Code']['code']?></td>
				<td></td>
				<td></td>
		</tr>
		<?php endforeach?>
	</tbody>
</table>