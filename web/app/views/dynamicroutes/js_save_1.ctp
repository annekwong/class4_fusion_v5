<?php echo $form->create('Dynamicroute')?>
<table style="width:100%">
<tbody class="add">
<tr>
	<td>
			 <img title="findegress" src="<?php echo $this->webroot?>images/+.gif" class="jsp_resourceNew_style_1" onclick="pull('<?php echo $this->webroot?>',this,-1)" id="image-1"/>
	</td>
	<td></td>
	<td><?php echo $form->input('name',Array('div'=>false,'label'=>false,'class'=>'input in-input','value'=>array_keys_value($post,'Dynamicroute.name')))?></td>
	<td>
		<?php $arr1=array('4'=>__('routerule1',true),'5'=>__('routerule2',true),'6'=>__('routerule3',true));?>
               
		<?php echo $form->input('routing_rule',Array('div'=>false,'label'=>false,'type'=>'select','options'=>$arr1,'class'=>'select in-select','selected'=>array_keys_value($post,'Dynamicroute.routing_rule')))?>
	</td>
	<td>
		<?php echo $form->input('Dynamicroute.time_profile_id',	array('options'=>$user,'label'=>false ,	'empty'=>'','div'=>false,'type'=>'select','class'=>'select in-select','selected'=>array_keys_value($post,'Dynamicroute.time_profile_id')));?>
	</td>
	<td>
	</td>
	<td>
			<a id="save" href="#" title="Save">
		    <img src="<?php echo $this->webroot?>images/menuIcon_004.gif"/>
		  </a>
		  <a id="delete" href="#" title="Deleted">
		    	<i class='icon-remove'></i>
		  </a>	
	</td>
</tr>
<tr style="height: auto;" class="row-2">
	<td colspan="7" class="last">
		<div style="padding: 5px;display:none" class="jsp_resourceNew_style_2" id="ipInfo-1">
			<table>
				<tr>
					<td colspan=6 style="text-align:left">
						<a id="addUser" href="" onclick="add_user('#addUser');return false;">
							<img  src="<?php echo $this->webroot?>images/add.png"/> <?php echo __('createnew',true);?>
						</a>
					</td>
				</tr>
				<tr>
					<th style="width:13%"><?php echo __('id',true);?></th>
					<th style="width:13%"><?php echo __('Carriers',true);?></th>
					<th style="width:13%"><?php echo __('Trunk Name',true);?></th>
					<th style="width:13%"><?php echo __('active',true);?></th>
				</tr>
			</table>
                     <?php echo $xform->search('Carriers1',Array('options'=>$appProduct->_get_select_options($ClientList,'Client','client_id','name'),'value'=>'','onchange'=>'client(this)'))?>
			<script type="text/javascript">
				function add_user(a,client_id,trunk_id){
					var table=jQuery(a).parent().parent().parent();
					var resource=<?php echo json_encode($appDynamicRoute->format_client_and_resource_options($clients))?>;
					var clientSelect=jQuery('<select/>').append('<option value="" />').css('width','120px').addClass('select in-select');
					var egressSelect=jQuery('<select/>').append('<option value="" />').css('width','120px').attr('id','egressSelect').addClass('select in-select').attr('name','engress_res_id[]');
						jQuery.each(resource,function(k,v){
						jQuery('<option/>').attr('value',resource[k].id).html(resource[k].name).appendTo(clientSelect);
					});
					clientSelect.change(
						function(){
							var client_id=jQuery(this).val();
							var egressSelect=jQuery(this).parent().parent().find('#egressSelect');
							egressSelect.html('');
							jQuery.each(resource[client_id].egress,function(k,v){
								jQuery('<option/>').attr('value',k).html(v).appendTo(egressSelect);
							});
						}
					);
					var del=jQuery("<images/>").attr('src','<?php echo $this->webroot?>images/delete.png').click(function(){jQuery(this).parent().parent().remove()});
					jQuery('<tr/>').append(
							jQuery('<td/>')
					).append(
							jQuery('<td/>').append(clientSelect)
					).append(
							jQuery('<td/>').append(egressSelect)
					).append(
							jQuery('<td/>').append("<a href=\"#\" onclick=\"jQuery(this).parent().parent().remove();return false;\"><img src=\"/exchangeimages/delete.png\"/></a>")
					).appendTo(table);
					if(trunk_id){
						clientSelect.val(client_id).change();
						egressSelect.val(trunk_id);
					}else{
						//clientSelect.change();
					}
				}
			</script>
			<script type="text/javascript">
			function jsSaveCallback(){
			<?php if(isset($res_dynamic) && !empty($res_dynamic) && isset($egresses)):?>
			<?php
				foreach($res_dynamic as $res_egress_id):
				$egress = $appDynamicRoute->find_egress($res_egress_id,$egresses);
				if($egress):
			?>		
				add_user('#addUser','<?php echo $egress['Resource']['client_id']?>','<?php echo $egress['Resource']['resource_id']?>');
			<?php
					endif; 
				endforeach;
			?>    
			<?php else:?>
			add_user('#addUser');
			<?php endif;?>
			}
			</script>
		</div>
	</td>
</tr>
</tbody>
</table>
<?php echo $form->end()?>
<script type="text/javascript">
<?php if(isset($res_dynamic) && !empty($res_dynamic) && isset($egresses)):?>
<?php
	foreach($res_dynamic as $res_egress_id):
	$egress = $appDynamicRoute->find_egress($res_egress_id,$egresses);
	if($egress):
?>		
	add_user('#addUser','<?php pr( $egress)?>');
<?php
		endif; 
	endforeach;
?>    
<?php endif;?>


</script>
