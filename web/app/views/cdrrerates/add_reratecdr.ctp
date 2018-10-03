<div id="title">
   <h1>Cdr Rerate&gt;&gt;Add/Edit
         <font class="editname" title="Name">   <?php echo empty($p['name'])||$p['name']==''?'':"[".$p['name']."]" ?></font>
   
   </h1>
   <ul id="title-menu">
		<li>
			<?php echo $this->element('xback',Array('backUrl'=>'cdrrerates/view'))?>
		</li>
  	</ul>
</div>
<div class="container">

<?php $id=array_keys_value($this->params,'pass.0')?>
<?php echo $form->create ('Cdrrerate', array ('action' =>'add_reratecdr'));?>
	<table width="100%">
		<col width="50%"/>
		<col width="50%"/>
		<tbody>
			<tr>
				<td class="first" style="vertical-align:top;">
    				<table class="form">
    					<tbody>
    						<tr>
        						<td class="label label2"><?php echo __('Cdr Id')?>:</td>
        						<td class="value value2">
        						<input  type="hidden"   id="cdr_id" name="cdr_id" value="<?php echo array_keys_value($this->params,'pass.0'); ?>">  
        									<?php echo array_keys_value($this->params,'pass.0'); ?>
        							</td>
    						</tr>
    						
    						<tr>
        						<td class="label label2"><?php echo __('Start Time')?>:</td>
        						<td class="value value2">
        					<?php         					
        					echo empty($p['start_time_of_date'])?'':date("m/d/Y H:i:s", $p['start_time_of_date']); 
        								?>
        						</td>
    						</tr>
    						<tr>
        						<td class="label label2"><?php echo __('End Time')?>:</td>
        						<td class="value value2">
        					<?php 
        					echo empty($p['release_tod'])?'':date("m/d/Y H:i:s", $p['release_tod']); 
        								?>
        						</td>
    						</tr>
    						
    						<tr>
        						<td class="label label2"><?php echo __('Orig Carrier')?>:</td>
        						<td class="value value2">
        						<?php 
 							 echo empty($p['ingress_client_id'])?'':$p['ingress_client_id'];
 							     ?>
    									</td>
    						</tr> 
    						
    							<tr>
        						<td class="label label2"><?php echo __('Orig Trunk')?>:</td>
        						<td class="value value2">
        						<?php 
 							 echo empty($p['ingress_id'])?'':$p['ingress_id'];
 							     ?>
    									</td>
    						</tr> 
    						
    						<tr>
        						<td class="label label2"><?php echo __('Orig Host')?>:</td>
        						<td class="value value2">
        						<?php 
 							 echo empty($p['origination_source_host_name'])?0:$p['origination_source_host_name'];
    ?>
    									</td>
    						</tr> 
    						<tr>
        						<td class="label label2"><?php echo __('Term Carrier')?>:</td>
        						<td class="value value2">
        						<?php
        						echo isset($p['egress_client_id'])?$p['egress_client_id']:'';
        						?>
        							
    									</td>
    						</tr> 
    						
    						<tr>
        						<td class="label label2"><?php echo __('ORIG Rate Table')?>:</td>
        						<td class="value value2">
        						<?php
        						echo isset($p['ingress_client_rate_table_id'])?$p['ingress_client_rate_table_id']:'';
        						?>
        							
    									</td>
    						</tr> 
    						<tr>
        						<td class="label label2"><?php echo __('New ORIG Rate Table')?>:</td>
        						<td class="value value2">
        						<?php
        						echo $form->input('new_orig_rate_table_id',
 		array('options'=>$name_join_arr['rate_table'], 'label'=>false , 'div'=>false, 'type'=>'select', 'class'=>'input in-text', 'selected'=>empty($p['new_orig_rate_table_id'])?0:$p['new_orig_rate_table_id']));
        						?>
        							
    									</td>
    						</tr> 
    						   
    						
						</tbody>
					</table>
				</td>
				
				
					<td class="last" style="vertical-align:top;">
    				<table class="form">
    					<tbody>
    							<tr><td>&nbsp;</td><td>&nbsp;</td></tr>
    						<tr>
        						<td class="label label2"><?php echo __('Term Trunk')?>:</td>
        						<td class="value value2">
        						<?php
        						echo isset($p['egress_id'])?$p['egress_id']:'';
        						?>
        							
    									</td>
    						</tr> 
    						<tr>
        						<td class="label label2"><?php echo __('Term Host')?>:</td>
        						<td class="value value2">
        						<?php
        						echo isset($p['termination_source_host_name'])?$p['termination_source_host_name']:'';
        						?>
        							
    									</td>
    						</tr>  						
    						<tr>
        						<td class="label label2"><?php echo __('Code Deck')?>:</td>
        						<td class="value value2">
        						<?php 
 							 echo empty($p['code_deck_id'])?'':$p['code_deck_id'];
    ?>
    									</td>
    						</tr> 
    						
    						</tr> 
    						
    						<tr>
        						<td class="label label2"><?php echo __('Country')?>:</td>
        						<td class="value value2">
        						<?php 
 							 echo empty($p['country'])?'':$p['country'];
    ?>
    									</td>
    						</tr> 
    						<tr>
        						<td class="label label2"><?php echo __('Code Name')?>:</td>
        						<td class="value value2">
        						<?php 
 							 echo empty($p['code_name'])?'':$p['code_name'];
    ?>
    									</td>
    						</tr> 
    						
    						</tr> 
    						
    						<tr>
        						<td class="label label2"><?php echo __('Code')?>:</td>
        						<td class="value value2">
        						<?php 
 							 echo empty($p['code'])?'':$p['code'];
    ?>
    									</td>
    						</tr> 
    						<tr>
        						<td class="label label2"><?php echo __('TERM Rate Table')?>:</td>
        						<td class="value value2">
        									
        						<?php 
        						echo empty($p['egress_rate_table_id'])?'':$p['egress_rate_table_id'];
        						?>        						
    									</td>
    						</tr> 
    						<tr>
        						<td class="label label2"><?php echo __('New TERM Rate Table')?>:</td>
        						<td class="value value2">
        									
        						<?php         						
        						echo $form->input('new_term_rate_table_id',
 		array('options'=>$name_join_arr['rate_table'], 'label'=>false , 'div'=>false, 'type'=>'select', 'class'=>'input in-text', 'selected'=>empty($p['new_term_rate_table_id'])?0:$p['new_term_rate_table_id']));
        						?>        						
    									</td>
    						</tr> 
    						
						</tbody>
					</table>
				</td>
			</tr>
		</tbody>
	</table>
<div id="form_footer">
            <input type="submit" value="<?php echo __('submit')?>" class="input in-submit"/>
</div>
<?php echo $form->end();?>
<script type="text/javascript">
jQuery(document).ready(function(){			
	change_freq();
    //jQuery('#AlertAddConditionForm').submit(function(){ 
   //     return true;
     //});
  


 });     

function change_freq()
{
	var comp = $("#AlertFreqType").val();
	if (comp == 1)
	{
		$("#fv_0").show();
		$("#fv_1").hide();
	}
	if (comp == 2)
	{
		$("#fv_1").show();
		$("#fv_0").hide();
	}
}

function save_rule()
{
	var flag = true;
	
	var action_name = $("#AlertName").val();
	if(!/^[a-zA-Z]{1}([a-zA-Z0-9]|[._]){4,19}$/.exec(action_name)){
		$("#AlertName").attr('class','invalid');
		jGrowl_to_notyfy('Action Name must composed by more than 5 English letters or ._',{theme:'jmsg-alert'});
		flag=false ;
	}
	else
	{
		$("#AlertName").attr('class','input in-text');
	}
	return flag;
}

</script>
			
</div>