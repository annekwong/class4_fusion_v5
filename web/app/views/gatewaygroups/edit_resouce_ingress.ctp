<?php echo $this->element("gatewaygroups_edit_resouce_ingress/js")?>
<?php echo $this->element("gatewaygroups_edit_resouce_ingress/title")?>
	<div class="container">
		<?php echo $this->element("gatewaygroups_edit_resouce_ingress/menu")?>
		<?php echo $form->create ('Gatewaygroup', array ('action' => 'edit_resouce_ingress','onsubmit'=>'return checkform();' ));?>
		<?php echo $form->input('resource_id',array('id'=>'alias','label'=>false ,'value'=>$post['Gatewaygroup']['resource_id'],'div'=>false,'type'=>'hidden','maxlength'=>'6'));?>
  	<?php echo $form->input('ingress',array('label'=>false ,'value'=>'true','div'=>false,'type'=>'hidden'));?>
		<?php echo $form->input('egress',array('label'=>false ,'value'=>'false','div'=>false,'type'=>'hidden'));?>
		<input type="hidden" id="delete_rate_id" value="" name="delete_rate_id" class="input in-hidden">
	 	<input type="hidden" value="<?php echo $post['Gatewaygroup']['resource_id'];?>" name="resource_id"/>
		<input type="hidden" value="<?php echo $post['Gatewaygroup']['resource_id'];?>" name="inputRId"/>
		<table class="cols" style="width:80%;">
				<col width="35%"/>
				<col width="38%"/>
				<col width="27%"/>
		<tr>
				<td>
					<?php echo $this->element("gatewaygroups_edit_resouce_ingress/fieldset1")?>
					<fieldset><legend><?php __('rateTable')?></legend>
						<table class="form" style="height: 60px;">
							<tr>
							    <td ><?php __('rateTable')?>:</td>
							    <td>
											<?php echo $form->input('rate_table_id',array('options'=>$rate,'selected'=>$post['Gatewaygroup']['rate_table_id'],'empty'=>'  ','label'=>false, 'class' =>'select' ,'div'=>false,'type'=>'select'));?>
							    </td>
							</tr>
							<tr>
								<td colspan="2" style="text-align: center; padding-left: 0px;">
									<fieldset style="margin-bottom:0px;">
										<legend><?php __('Routing Plan')?></legend>
									</fieldset>
								</td>
							</tr>
							<tr>
							    <td><?php echo __('Routing Plan',true);?>:</td>
							    <td>
											<?php echo $form->input('route_strategy_id',array('options'=>$route_policy,'selected'=>$post['Gatewaygroup']['route_strategy_id'],	'empty'=>'  ','label'=>false, 'class' =>'select' ,'div'=>false,'type'=>'select'));?>
							    </td>
							</tr>
						</table>
					</fieldset>
                    <?php echo $this->element("gatewaygroups_edit_resouce_ingress/fieldset2")?>
				</td>
				<td>
					
					<?php echo $this->element("gatewaygroups_edit_resouce_ingress/fieldset3")?>
				</td>
				<td></td>
		 </tr>
		</table>
		<div style="width:95%;">
<?php echo $this->element("gatewaygroups/host")?>
<!-- ********************************************************************************************************** -->
<?php  echo $this->element("gatewaygroups/resource_prefix")?>
</div>
		<div id="footer">
    <input type="submit"   onclick="seleted_codes();" value="<?php echo __('submit')?>" />
   </div>
		<?php echo $form->end();?>
</div>
<script type="text/javascript" src="<?php echo $this->webroot?>js/gateway.js"></script>
<script type="text/javascript">
jQuery(document).ready(
		function(){
			jQuery('#totalCall,#totalCPS').xkeyvalidate({type:'Num'});
			jQuery('input[check=strNum]').xkeyvalidate({type:'strNum'});
		  }
);
</script>

<script type="text/javascript">
<!--
  jQuery(document).ready(function(){
	   jQuery('#GatewaygroupLrnBlock').change(function(){
  	    if(jQuery('#GatewaygroupLrnBlock').attr('checked')==true){
           jQuery('#GatewaygroupLnp').attr('checked',true);
           jQuery('#GatewaygroupLnp').attr('disabled','disabled'); 
           jQuery('#GatewaygroupDnisOnly').attr('checked',false);  
    		  }else{
    			  jQuery('#GatewaygroupLnp').removeAttr('disabled');
        		     }
		   });
	   jQuery('#GatewaygroupDnisOnly').change(function(){
        if(jQuery('#GatewaygroupDnisOnly').attr('checked')==true){
	        	jQuery('#GatewaygroupLnp').attr('checked',false);
	        	jQuery('#GatewaygroupLrnBlock').attr('checked',false);
                      }
		   });
			jQuery('#GatewaygroupLnp').change(function(){
				  if(jQuery('#GatewaygroupLnp').attr('checked')==true){
			        	jQuery('#GatewaygroupDnisOnly').attr('checked',false);
		              }
			});
	   
	  });
function checkform(){
	var flag=true;
	$("input[name='resource[tech_prefix][]']").each(function(i,e)
	{
		if (!/^[\*|\.|\-|_|#|\+|\w]*$/.test($(e).val()))
		{
			jQuery(this).jGrowlError('Tech prefix, invalide format!',{theme:'jmsg-error'});
			flag= false;
		}
	});

	var arr = new Array();
	$('#resource_table').find('input[name^=resource[tech_prefix]]').each(function (){
		arr.push($(this).val());
	});
	var arr2=$.uniqueArray(arr);
	if(arr.length!=arr2.length){
		$('#resource_table').find('input[name^=resource[tech_prefix]]').each(function (){
			flag=false;
	    });
	    jGrowl_to_notyfy('Tech Prefix  Happen  Repeat.',{theme:'jmsg-error'});
   }
	return flag;
}
//-->
</script>
