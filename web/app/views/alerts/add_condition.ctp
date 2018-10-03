<style type="text/css">
.in-text, .in-password, .in-textarea, .value select, .value textarea, .value .in-text, .value .in-password, .value .in-textarea, .value .in-select{ width:40%;}
</style>


<ul class="breadcrumb">
    <li><?php __('You are here'); ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Monitoring') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Add Condition',true);?>  <?php echo empty($p['name'])||$p['name']==''?'':"[".$p['name']."]" ?></li>
</ul>


<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Condition',true);?></h4>
    
</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
        <?php echo $this->element('xback',Array('backUrl'=>'alerts/condition'))?>
    </div>
    <div class="clearfix"></div>


<div class="innerLR">

    <div class="widget widget-heading-simple widget-body-white">
        <div class="widget-body">


<?php
    $flag = isset($_GET['flag']) ? "?flag=1" : "";
?>
<?php $id=array_keys_value($this->params,'pass.0')?>
<?php echo $form->create ('Alert', array ('action' =>"add_condition{$flag}", 'onsubmit'=>"return save_action();"));?>
	<table width="100%" class="form">
		<tbody>
			<tr>
				<td class="first" style="vertical-align:top;">
    				<table class="form  table table-condensed">
    					<tbody>
    						<tr>
        						<td><?php echo __('Condition name')?>:</td>
        						<td>
        						<input  type="hidden"   id="condition_id" name="condition_id" value="<?php echo array_keys_value($this->params,'pass.0'); ?>">  
        						
        							<?php echo $form->input('name',array('label'=>false ,'div'=>false,'type'=>'text','maxLength'=>'100', 'value'=>empty($p['name'])?'':$p['name']));?>
        							</td>
    						</tr>
    						<tr>
        						<td><?php echo __('or/and',true);?>:</td>
        						<td>
<input type="radio" name="data[Alert][for_all]" value="0" <?php if(!isset($p['for_all']) || $p['for_all'] == 0 ) echo 'checked';  ?> /> <?php __('or')?> &nbsp; <input type="radio" name="data[Alert][for_all]" value="1" <?php if(isset($p['for_all']) && $p['for_all'] == 1 ) echo 'checked';  ?> /> <?php __('and')?>
                    
    									</td>
    						</tr>
    						<tr>
        						<td><?php echo __('acd',true);?>:</td>
        						<td>
        				<div style="white-space:nowrap;">
        						<div style="display:block;float:left;">
        						<?php 
        			$tmp=array('0'=>'Less Than','1'=>'Between', 2=>'Ignore');
         		echo $form->input('acd_comparator',
 		array('options'=>$tmp, 'label'=>false , 'div'=>false,'style'=>'width:100px', 'type'=>'select', 'class'=>'input in-text', 'onchange'=>"change_acd();", 'selected'=>empty($p['acd_comparator'])?0:$p['acd_comparator']));
 											?>
 											</div>
 											<div id="acd_0" style="display:block;float:left;">
 									<?php __('ACD')?> &lt= <input type="text" id="acd_min_0" name="acd_min_0" value="<?php echo empty($p['acd_value_min'])?0:$p['acd_value_min'];?>" class="input in-text">
 											</div>
 											<div id="acd_1" style="display:none;float:left;">
 											<input type="text" id="acd_min_1" name="acd_min_1" value="<?php echo empty($p['acd_value_min'])?0:$p['acd_value_min'];?>"> &lt=	ACD &lt= <input type="text" id="acd_max_1" name="acd_max_1" value="<?php echo empty($p['acd_value_max'])?0:$p['acd_value_max'];?>">
 											</div>
 								</div>
								</td>
    						</tr>
    						
    						<tr>
        						<td><?php echo __('asr',true)?>:</td>
        						<td>
        			<div style="white-space:nowrap;">
        						<div style="display:block;float:left;">
        						<?php 
        			$tmp=array('0'=>'Less Than','1'=>'Between', 2=>'Ignore');
         		echo $form->input('asr_comparator',
 		array('options'=>$tmp, 'label'=>false , 'div'=>false,'style'=>'width:100px' ,  'type'=>'select', 'class'=>'input in-text', 'onchange'=>"change_asr();", 'selected'=>empty($p['asr_comparator'])?0:$p['asr_comparator']));
 											?></div>
 											<div id="asr_0" style="display:block;float:left;">
 											<?php __('ASR')?> &lt= <input type="text" id="asr_min_0" name="asr_min_0" value="<?php echo empty($p['asr_value_min'])?0:$p['asr_value_min'] * 100;?>">%
 											</div>
 											<div id="asr_1" style="display:none;float:left;">
 											<input type="text"  id="asr_min_1" name="asr_min_1" value="<?php echo empty($p['asr_value_min'])?0:$p['asr_value_min'] * 100;;?>">%
 										&lt=  ASR &lt= 
 											<input type="text"  id="asr_max_1" name="asr_max_1" value="<?php echo empty($p['asr_value_max'])?0:$p['asr_value_max'] * 100;;?>">%
 											</div>
 											</div>
								</td>
    						</tr>
    						
    						<tr>
        						<td><?php echo __('Margin')?>:</td>
        						<td>
        						<div style="white-space:nowrap;">
        						<div style="display:block;float:left;">
        						<?php 
        			$tmp=array('0'=>'Less Than','1'=>'Between', 2=>'Ignore');
         		echo $form->input('margin_comparator',
 		array('options'=>$tmp, 'label'=>false ,'style'=>'width:100px', 'div'=>false, 'type'=>'select', 'class'=>'input in-text', 'onclick'=>"change_margin();", 'selected'=>empty($p['margin_comparator'])?0:$p['margin_comparator']));
 											?></div>
 											<div id="margin_0" style="display:block;float:left;">
 											<?php __('Margin')?> &lt= <input type="text" id="margin_min_0" name="margin_min_0" value="<?php echo empty($p['margin_value_min'])?0:$p['margin_value_min'] * 100;;?>">%
 											</div>
 											<div id="margin_1" style="display:none;float:left;">
 											<input type="text"  class="in-input input in-text" id="margin_min_1" name="margin_min_1" value="<?php echo empty($p['margin_value_min'])?0:$p['margin_value_min'] * 100;;?>">%
 										&lt=  Margin&lt= 
 											<input type="text" style="width:auto;" id="margin_max_1" name="margin_max_1" value="<?php echo empty($p['margin_value_max'])?0:$p['margin_value_max'] * 100;;?>">%
 											</div>
 											</div>
								</td>
    						</tr>
                                                <tr>
        						<td><?php echo __('ABR')?>:</td>
        						<td>
        						<div style="white-space:nowrap;">
        						<div style="display:block;float:left;">
        						<?php 
        			$tmp=array('0'=>'Less Than','1'=>'Between', 2=>'Ignore');
         		echo $form->input('abr_comparator',
 		array('options'=>$tmp, 'label'=>false ,'style'=>'width:100px', 'div'=>false, 'type'=>'select', 'class'=>'input in-text', 'onclick'=>"change_abr();", 'selected'=>empty($p['abr_comparator'])?0:$p['abr_comparator']));
 											?></div>
 											<div id="abr_0" style="display:block;float:left;">
 											<?php __('ABR')?> &lt= <input type="text" id="abr_min_0" name="abr_min_0" value="<?php echo empty($p['abr_value_min'])?0:$p['abr_value_min'] * 100;;?>">%
 											</div>
 											<div id="abr_1" style="display:none;float:left;">
 											<input type="text"  class="in-input input in-text" id="abr_min_1" name="abr_min_1" value="<?php echo empty($p['abr_value_min'])?0:$p['abr_value_min'] * 100;;?>">%
                                                                                        &lt= ABR &lt= 
 											<input type="text" style="width:auto;" id="abr_max_1" name="abr_max_1" value="<?php echo empty($p['abr_value_max'])?0:$p['abr_value_max'] * 100;;?>">%
 											</div>
 											</div>
								</td>
    						</tr>
                                                <tr>
                                                    <td><?php echo __('Occurence Of A specific ANI') ?>:</td>
                                                    <td>
                                                        <div style="white-space:nowrap;">
                                                            <div style="display:block;float:left;">
                                                                <?php
                                                                $tmp=array('0'=>'Great Than','1'=>'Less Than', '2' => 'Ignore');
                                                                echo $form->input('special_ani_comparator',
                                                                    array('options'=>$tmp, 'label'=>false ,'style'=>'width:100px', 
                                                                    'div'=>false, 'type'=>'select', 'class'=>'input in-text', 'onclick'=>"change_special_ani();", 
                                                                    'selected'=>empty($p['special_ani_comparator'])?0:$p['special_ani_comparator']));
                                                                ?>
                                                            </div>
                                                                <div id="special_ani_0" style="display:block;float:left;">
                                                                   <?php __('ANI')?> &gt= <input type="text" id="special_ani_value_0" name="special_ani_value_0" value="<?php echo empty($p['special_ani_value'])?0:$p['special_ani_value'];?>">
                                                                </div>
                                                                <div id="special_ani_1" style="display:block;float:left;">
                                                                   ANI &lt= <input type="text" id="special_ani_value_1" name="special_ani_value_1" value="<?php echo empty($p['special_ani_value'])?0:$p['special_ani_value'];?>">
                                                                </div>
                                                        </div>
                                                    </td>
                                                </tr>
						</tbody>
					</table>
				</td>
				
			</tr>
		</tbody>
	</table>
    <?php  if ($_SESSION['role_menu']['Monitoring']['alerts:condition']['model_w']) {?>
<div id="form_footer" class="buttons-group center">
            <input type="submit" value="<?php echo __('submit')?>" class="input in-submit btn btn-primary"/>
</div>
<?php }?>
<?php echo $form->end();?>
<script type="text/javascript">
jQuery(document).ready(function(){
			change_acd();
			change_asr();
			change_margin();
                        change_abr();
                        change_special_ani();
 //  jQuery('#AlertAddConditionForm').submit(function(){ 
   //     return ;
   //  });
  


 });     

function change_acd()
{
	var comp = $("#AlertAcdComparator").val();
	if (comp == 0)
	{
		$("#acd_0").show();
		$("#acd_1").hide();
	}
	else if(comp == 1)
	{
		$("#acd_1").show();
		$("#acd_0").hide();
	}
        else 
        {
                $("#acd_1").hide();
		$("#acd_0").hide();
        }
}

function change_asr()
{
	var comp = $("#AlertAsrComparator").val();
	if (comp == 0)
	{
		$("#asr_0").show();
		$("#asr_1").hide();
	}
	else if(comp == 1)
	{
		$("#asr_1").show();
		$("#asr_0").hide();
	}
        else
        {
                $("#asr_1").hide();
		$("#asr_0").hide();
        }
}

function change_margin()
{
	var comp = $("#AlertMarginComparator").val();
	if (comp == 0)
	{
		$("#margin_0").show();
		$("#margin_1").hide();
	}
	else if(comp == 1)
	{
		$("#margin_1").show();
		$("#margin_0").hide();
	}
        else 
        {
                $("#margin_1").hide();
		$("#margin_0").hide();
        }
}

function change_abr()
{
	var comp = $("#AlertAbrComparator").val();
	if (comp == 0)
	{
		$("#abr_0").show();
		$("#abr_1").hide();
	}
	else if(comp == 1)
	{
		$("#abr_1").show();
		$("#abr_0").hide();
	}
        else 
        {
                $("#abr_1").hide();
		$("#abr_0").hide();
        }
}

function change_special_ani()
{
	var comp = $("#AlertSpecialAniComparator").val();
	if (comp == 0)
	{
		$("#special_ani_0").show();
		$("#special_ani_1").hide();
	}
	else if(comp == 1)
	{
		$("#special_ani_0").hide();
		$("#special_ani_1").show();
	}
        else 
        {
                $("#special_ani_0").hide();
		$("#special_ani_1").hide();
        }
}

function isFloat (n) {
  return n===+n && n!==(n|0);
}

function isInteger (n) {
  return n===+n && n===(n|0);
}

function save_action()
{
	var flag = true;
	
	var action_name = $("#AlertName").val();
	if(action_name==''||action_name==null){
		jGrowl_to_notyfy('Condition name is required',{theme:'jmsg-alert'});
		flag=false ;
	}
	if(!/^([a-zA-Z0-9\._\- ]){0,15}$/.exec(action_name)){
		//$("#AlertName").attr('class','input in-text');
		//jGrowl_to_notyfy('Condition name, allowed characters:a-z,A-Z,0-9,-,_,space, maximum  of 16 characters in length',{theme:'jmsg-alert'});
		//flag=false ;
	}
	else
	{
		$("#AlertName").attr('class','input in-text');
	}
	var acd = $("#AlertAcdComparator").val();
	var acd_value_0 = $("#acd_min_0").val();
	var acd_value_min_1 = $("#acd_min_1").val();
	var acd_value_max_1 = $("#acd_max_1").val();
	if (acd == 0 && ((/[^\d\.]/.exec(acd_value_0))) || isFloat(acd_value_0))
	{
		$("#acd_min_0").attr('class','input in-text');
		jGrowl_to_notyfy('The field ACD Less value must be numeric.',{theme:'jmsg-alert'});
		flag = false;
	}
	else if (acd == 1 && (/[^\d]/.exec(acd_value_min_1) || /[^\d]/.exec(acd_value_max_1) || Number(acd_value_min_1) > Number(acd_value_max_1) || isFloat(acd_value_min_1)|| isFloat(acd_value_max_1)))
	{
		$("#acd_min_1").attr('class','input in-text');
		$("#acd_max_1").attr('class','input in-text');
		jGrowl_to_notyfy('Acd between value must be number and min<max',{theme:'jmsg-alert'});
		flag = false;
	}
	else
	{
		$("#acd_min_0").attr('class','input in-text');
		$("#acd_min_1").attr('class','input in-text');
		$("#acd_max_1").attr('class','input in-text');
	}

	var asr = $("#AlertAsrComparator").val();
	var asr_value_0 = $("#asr_min_0").val();
	var asr_value_min_1 = $("#asr_min_1").val();
	var asr_value_max_1 = $("#asr_max_1").val();
	if (asr == 0 && (/[^\d\.]/.exec(asr_value_0) || parseFloat(asr_value_0) > 100))
	{
		$("#asr_min_0").attr('class','input in-text');
		jGrowl_to_notyfy('Asr Less value must be number and less than 100',{theme:'jmsg-alert'});
		flag = false;
	}
	else if (asr == 1 && (/[^\d\.]/.exec(asr_value_min_1) || /[^\d\.]/.exec(asr_value_max_1) || parseFloat(asr_value_min_1) > 100 || parseFloat(asr_value_max_1) > 100 || parseFloat(asr_value_min_1) > parseFloat(asr_value_max_1)))
	{
		$("#asr_min_1").attr('class','input in-text');
		$("#asr_max_1").attr('class','input in-text');
		jGrowl_to_notyfy('Asr between value must be number and less than 100 and min<max',{theme:'jmsg-alert'});
		flag = false;
	}
	else
	{
		$("#asr_min_0").attr('class','input in-text');
		$("#asr_min_1").attr('class','input in-text');
		$("#asr_max_1").attr('class','input in-text');
	}

	var margin = $("#AlertMarginComparator").val();
	var margin_value_0 = $("#margin_min_0").val();
	var margin_value_min_1 = $("#margin_min_1").val();
	var margin_value_max_1 = $("#margin_max_1").val();
	if (margin == 0 && (/[^\d\.]/.exec(margin_value_0) || parseFloat(margin_value_0) > 100))
	{
		$("#margin_min_0").attr('class','input in-text');
		jGrowl_to_notyfy('margin Less value must be number and less than 100',{theme:'jmsg-alert'});
		flag = false;
	}
	else if (margin == 1 && (/[^\d\.]/.exec(margin_value_min_1) || /[^\d\.]/.exec(margin_value_max_1) || parseFloat(margin_value_min_1) > 100 || parseFloat(margin_value_max_1) > 100 || parseFloat(margin_value_min_1) > parseFloat(margin_value_max_1)))
	{
		$("#margin_min_1").attr('class','input in-text');
		$("#margin_max_1").attr('class','input in-text');
		jGrowl_to_notyfy('margin between value must be number and less than 100 and min<max',{theme:'jmsg-alert'});
		flag = false;
	}
	else
	{
		$("#margin_min_0").attr('class','input in-text');
		$("#margin_min_1").attr('class','input in-text');
		$("#margin_max_1").attr('class','input in-text');
	}
	
	return flag;
}

</script>
			
</div>
    </div>
</div>
