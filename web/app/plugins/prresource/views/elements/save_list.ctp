<ul class="tabs">
	<li  class="active">
		<a>
			<img width="16" height="16" src="<?php echo $this->webroot?>images/list.png"/><?php __('Routestrategy')?>
		</a>
	</li>
</ul>
<?php echo $form->create ('Resource',Array('url'=>'/prresource/gatewaygroups/save/'.$type.'/'.array_keys_value($this->data,'Resource.resource_id'),'id'=>'Resource'));?>
	<table class="cols">
		<col width="35%"/>
		<col width="38%"/>
		<col width="27%"/>
		<tr>
			<td>
				<fieldset>
					<legend> <?php __('Routestrategy')?></legend>
						<table class="form">
							<tr>
		    				<td><?php echo __('Ingress Name',true);?>:</td>
					    	<td>
					   		<?php echo $xform->input('alias',array('id'=>'alias','type'=>'text','maxlength'=>'16'));?>
					    	</td>
							</tr>
						<?php if($$hel->_get('viewType')=='client'){?>
							<tr style="display:none">
		    				<td></td>
					    	<td>
									<?php 
										echo $xform->input('client_id',array('value'=>$$hel->_get('query.id_clients')));
									?>
					    	</td>
							</tr>
						<?php  }else{ ?>
							<tr>
									<td>
											<?php echo __('client')?>:
									</td>
									<td><?php echo $xform->input('client_id',array('options'=>$c,'empty'=>'', 'class' =>'select'));?></td>
							</tr>
						<?php }?>
							<tr>
		    					<td><?php echo __('Media Type',true);?>:</td>
		    					<td>
										<?php 
											if($$hel->getProductName()=='partition'){
												$t=array('1'=>'Proxy Media ','2'=>'Bypass Media');
											}else{
												$t=array('0'=>'Proxy Media + Transcoding','1'=>'Proxy Media ','2'=>'Bypass Media');
											}
											echo $xform->input('media_type',array('options'=>$t,'class' =>'select','value'=>array_keys_value($this->data,'Resource.media_type')));
										?>
		    					</td>
		    			</tr>
							<tr>
							    <td><?php __('calllimit')?></td>
							    <td>
							   	<?php echo $xform->input('capacity',array('id'=>'totalCall','type'=>'text','maxlength'=>'8'));?>
							    </td>
							</tr>
							<tr>
							    <td><?php __('cps')?></td>
							    <td>
							  		<?php echo $form->input('cps_limit',array('id'=>'totalCPS','label'=>false ,'div'=>false,'type'=>'text','maxlength'=>'8'));?>
							    </td>
							</tr>
							<tr>
							    <td><?php __('proto')?></td>
							    <td>
							  				<?php echo $form->input('proto',array('label'=>false ,'div'=>false,'type'=>'select','options'=>Array(Resource::RESOURCE_PROTO_ALL=>'All',Resource::RESOURCE_PROTO_SIP=>'SIP',Resource::RESOURCE_PROTO_PROTO=>'H323')));?>
							    </td>
							</tr>
						</table>
				 </fieldset>
				 <fieldset>
				 		<legend><?php __('rateTable')?></legend>
						<table class="form">
							<tr>
		    					<td><?php __('rateTable')?>:</td>
		    					<td>
											<?php echo $form->input('rate_table_id',array('options'=>$rate,'empty'=>'  ','label'=>false, 'class' =>'select' ,'div'=>false,'type'=>'select'));?>
		    					</td>
							</tr>
						</table>
				</fieldset>
				<fieldset>
						<legend><?php echo __('Route Plan',true);?></legend>
						<table class="form">
							<tr>
							    <td><?php echo __('Route Plan',true);?>:</td>
							    <td>
							 				<?php echo 
							 						$form->input('route_strategy_id',array('options'=>$route_policy,'empty'=>'  ','label'=>false ,'div'=>false,'type'=>'select'));
							 				?>
							    </td>
							</tr>
						</table>
			 </fieldset>
		</td>
		<td>
		<fieldset>
			<table class="form">
				<col style="width:37%;"/>
				<col style="width:62%;"/>
				<tr>
		    	<td colspan="2" class="value">
		    		<div class="cb_select" style="height:90px;text-align: left">
		     		<div>
		     		 <?php echo $form->checkbox('active',array('checked'=>'checked'))?>
		         <label for="cp_modules-c_info"><?php __('active')?></label>
		         <?php echo $form->checkbox('t38',array('checked'=>'false'))?>
		         <label for="cp_modules-c_rates"><?php __('T38')?></label></div><div>
		         <?php echo $form->checkbox('lnp',array('checked'=>'false'))?>
		         <label for="cp_modules-c_invoices"><?php __('LRN')?></label></div><div>
		       	 <?php echo $form->checkbox('lrn_block',array('checked'=>'false'))?>
		         <label for="cp_modules-c_stats_summary"><?php __('Block LRN')?></label></div><div>
		         <?php echo $form->checkbox('dnis_only',array('checked'=>'false'))?>
		         <label for="cp_modules-c_stats_summary"><?php __('DNIS Only')?></label>
		        </div>
		      </div>
		     </td>
				</tr>
		</table>
		</fieldset>
		<fieldset><legend> <?php __('codec')?></legend>
		<table class="form">
		<tr>
		    <td>
		    		<?php  echo $form->input('select1',array('id'=>'select1','options'=>$nousecodes,'multiple' => true,'style'=>'width: 200px; height: 150px;','label'=>false, 'class' =>'select' ,'div'=>false,'type'=>'select'));?>
		    </td>
				 <td>
						<input  style="width: 48px; height: 25px; margin-left: 0px;"    onclick="DoAdd();"  type="button"  value="<?php __('add')?>"  />
						<br/><br/>
						<input  type="button"   style="width: 48px; height: 25px; margin-left: 0px;"  onclick="DoDel();"   value="<?php __('delete')?>"  />
				 </td>
		    <td>
		      <?php echo $form->input('select2',array('id'=>'select2','options'=>$usecodes,'multiple' => true,'style'=>'width: 200px; height: 150px;','label'=>false, 'class' =>'select' ,'div'=>false,'type'=>'select'));?>
		    </td>
		    <td>
						<input  style="width: 48px; height: 25px; margin-left: 0px;"    onclick="moveOption('select2','up');"  type="button"  value="<?php __('up')?>"  />
						 <br/><br/>
						<input  type="button"   style="width: 48px; height: 25px; margin-left: 0px;"  onclick="moveOption('select2','down');"   value="<?php __('Down')?>"  />
					</td>
			</tr>
			</table>
		 </fieldset>
		</td>
		<td></td>
		</tr>
	</table>
	<?php echo $this->element("gatewaygroups/save_host",Array('hosts'=>array_keys_value($this->data,'ResourceIp')))?>
	<div id="footer">
		 <input type="submit" value="<?php echo __('submit')?>" />
		 <input type="reset"  value="<?php echo __('reset')?>" class="input in-submit" />
	</div>
	<?php echo $form->end();?>
	<script type="text/javascript" src="<?php echo $this->webroot?>js/gateway.js"></script>
	<script type="text/javascript">
		 jQuery(document).ready(
					function(){
						jQuery('#totalCall,#totalCPS').xkeyvalidate({type:'Num'});
					}
			);
	</script>
	<script type="text/javascript">
	jQuery('#Resource').submit(function(){
			jQuery('#select1 option,#select2 option').attr('selected','true');
			return true;
	});
	</script>