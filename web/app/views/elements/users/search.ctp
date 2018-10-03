<fieldset id="advsearch" class="title-block" style="display:none;">
	<form method="get" action="">
		<table style="width: auto;">
			<tbody>
				<tr>
    				<td><label><?php echo __('Username',true);?>:</label>
						<?php echo $xform->search('filter_name')?>
    				</td>
					<td><label><?php echo __('Role',true);?>:</label>
						<?php echo $xform->search('filter_role_id',Array('options'=>$appUsers->_get_select_options($RoleList,'Role','role_id','role_name'),'empty'=>''))?>

    				</td>
					<td><label><?php echo __('status',true);?>:</label>
						<?php echo $xform->search('filter_active',Array('options'=>Array('1'=>'Active','0'=>'Stop'),'empty'=>''))?>
    				</td>
					<td class="buttons">
						<input type="submit" value="<?php echo __('submit',true);?>" class="in-submit"/>
					</td>
				</tr>
			</tbody>
		</table>
	</form>
</fieldset>