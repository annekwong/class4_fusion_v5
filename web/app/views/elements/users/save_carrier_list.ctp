<div class="innerLR">

	<div class="widget widget-heading-simple widget-body-white">
		<div class="widget-body">

			<?php $user_id=array_keys_value($this->params,'pass.0')?>
			<?php echo $form->create ('User', array ('action' =>'add_carrier_user'));?>
			<table class="table dynamicTable tableTools table-bordered  table-white">
				<tbody>
				<tr>
					<td class="first" style="vertical-align:top">
						<table class="form">
							<tbody>
							<tr>
								<td class="right"><?php echo __('username')?>* </td>
								<td >
									<input  type="hidden"   id="user_id" value="<?php echo array_keys_value($this->params,'pass.0'); ?>">
									<?php echo $form->input('name',array('label'=>false ,'div'=>false,'type'=>'text','maxLength'=>'256','class' => 'validate[required,custom[onlyLetterNumberLineSpace],funcCall[notEqualAdmin]]'));?>
								</td>
							</tr>
							<tr>
								<td class="right"><?php //echo __('New',true);?> <?php echo __('password')?>* </td>
								<td ><?php echo $form->input('password',array('label'=>false,'maxLength'=>'66' ,'div'=>false,'type'=>'password','class'=>'validate[required]'));?></td>
							</tr>
							<tr>
								<td class="right"><?php echo __('Confirm Password',true);?>* </td>
								<td ><?php echo $form->input('repassword',array('label'=>false,'maxLength'=>'66' ,'div'=>false,'type'=>'password','name'=>'','class'=>'validate[required]'));?></td>
							</tr>
							<tr>
								<td class="right"><?php echo __('fullname')?> </td>
								<td >
									<?php echo $form->input('fullname',array('label'=>false ,'div'=>false,'type'=>'text','maxLength'=>'256'));?>
								</td>
							</tr>
							<tr>
								<td class="right"><?php echo __('email')?> </td>
								<td >
									<?php echo $form->input('email',array('label'=>false ,'div'=>false,'type'=>'text'));?>
								</td>
							</tr>
							<?php $t= $session->read('login_type'); ?>
							</tbody>
						</table>
					</td>
					<td class="last"  style="vertical-align:top">
						<table class="form">
							<tbody>
							<tr>
								<td class="right"><?php echo __('client')?> </td>
								<td >
									<?php echo $form->input('client_id',array('options'=>$appUsers->_get_select_options($ClientList,'Client','client_id','name'),'label'=>false ,'div'=>false,'type'=>'select'));?>
								</td>
							</tr>
							<tr>
								<td valign="top" class="right"><?php echo __('Status')?> </td>
								<td >
									<?php echo $form->checkbox('active')?>

								</td>
							</tr>

							</tbody>
						</table>
					</td>
				</tr>
				<tr>
			                            <td class="align_right padding-r20"><?php echo __('Permission') ?> </td>
			                            <td class="value" colspan="3">
			                                <?php echo $this->element('portal/add_permission_div',array('check_data' => $client['Client'])); ?>
			                            </td>
			                        </tr>
				</tbody>
			</table>
			<div id="form_footer" class="button-groups center">
				<input type="submit" value="<?php echo __('submit')?>" class="input in-submit btn btn-primary"/>
				<input type="reset" value="<?php __('reset')?>" class="btn btn-inverse">
			</div>
			<?php echo $form->end();?>
		</div>
	</div>
	<script type="text/javascript">
		jQuery(document).ready(function(){
			jQuery('#UserName,#UserFullname').xkeyvalidate({type:'strNum'})
			jQuery('#UserEmail').xkeyvalidate({type:'Email'});
			jQuery('#UserAddCarrierUserForm').submit(function(){
				if ($("#UserPassword").val() != $("#UserRepassword").val()){
					jGrowl_to_notyfy('<?php __('Entered passwords differ'); ?>', {theme: 'jmsg-error'});
					return false;
				}
				return true;
			});


			jQuery('#UserPassword').val('');

		});

	</script>
