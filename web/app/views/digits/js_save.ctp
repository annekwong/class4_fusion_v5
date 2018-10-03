<?php echo $form->create('TranslationItem')?>
			<?php echo $xform->input('translation_id',Array('type'=>'hidden'))?>
			<table class="form" style="margin-left:15%;">
				<tbody>
					<tr>
                                            <td><input type="hidden" value="<?php echo $ref_id; ?>" id="check_repeat_ref_id" /></td>

						<td>
							<?php echo $xform->input('ani_method',Array('options'=>Array(0=>__('ignore',true),1=>__('Replace the Matched Portion',true),2=>__('Replace the Entire Number',true)),'style'=>'width:auto'))?>
						</td>
					   <td >
					    	<?php echo $xform->input('ani',Array('style'=>'width:100px','type'=>'text')) ?>
					   </td>
						<td >
							<?php echo $xform->input('action_ani',Array('style'=>'width:100px','type'=>'text'))?>
						</td>
						<td>
							<?php echo $xform->input('dnis_method',Array('options'=>Array(0=>'ignore',1=>'Replace the Matched Portion',2=>'Replace the Entire Number'),'style'=>'width:auto'))?>
						</td>
					   <td >
					   	<?php echo $xform->input('dnis',Array('style'=>'width:100px','type'=>'text')) ?>
					   </td>
					   <td>
					   	<?php echo $xform->input('action_dnis',Array('style'=>'width:100px','type'=>'text'))?>
					   </td>
					   <td>
						   <a title="Save" id="save" href="" onclick="return false">
							   <i class="icon-save"></i>
								</a>
								<a title="Exit" id="delete"  href="">
									 <i class="icon-remove"></i>
								</a>
					   </td>
					</tr>
				</tbody>
			</table>
<?php echo $form->end()?>