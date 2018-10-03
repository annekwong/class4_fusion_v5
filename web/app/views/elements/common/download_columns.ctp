<?php if(isset($schema) && is_array($schema)):?>

<?php $index = 1;?>
<table>
	<?php foreach($schema as $field_name => $t):?>
	<tr>
		<td><?php echo __('Column',true);?> #<?php echo $index ?>:</td>
		<td style="text-align:left;"><?php echo $appDownload->display_field_select($schema,$field_name) ?></td>
	</tr>
	<?php $index = $index + 1;?>
<?php endforeach;?>
</table>
<?php endif;?>

