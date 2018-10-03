<?php 

   

$t_org_total_cost=0;
$t_org_total_duration=0;
$t_org_bill_minute=0;
$t_org_avg_rate=0;
$t_org_total_calls=0;
$t_org_notzero_calls=0;
$t_org_succ_calls=0;
$t_org_busy_calls=0;
$t_org_nochannel_calls=0;
$t_org_lrn_calls=0;
$t_asr_std=0;
$t_asr_cur=0;
$t_acd_std=0;
$t_acd_cur=0;

$sub_org_total_cost=0;
$sub_org_total_duration=0;
$sub_org_bill_minute=0;
$sub_org_avg_rate=0;
$sub_org_total_calls=0;
$sub_org_notzero_calls=0;
$sub_org_succ_calls=0;
$sub_org_busy_calls=0;
$sub_org_nochannel_calls=0;
$sub_org_lrn_calls=0;
$sub_asr_std=0;
$sub_asr_cur=0;
$sub_acd_std=0;
$sub_acd_cur=0;
$nodata='1';
if($report_type=='term'){ 
	if(empty( $client_term[0][0]['org_total_calls'])){
 		$nodata='0';
 	}
 }else{
	if(empty($client_org[0][0]['org_total_calls'])){
		$nodata='0';
	}
}

if(false){?>

<div class="msg"><?php echo __('no_data_found',true);?></div>
<?php }else{?>
<?php echo $this->element('report/real_period')?>
<table class="list nowrap with-fields" style="min-width:980px;">
<thead>
	<tr>
		 <?php
		 //输出分组的字段
		 
		$td_size=0;
		 if(!empty($group_by_field_arr)){
		 	$c=count($group_by_field_arr);
		 	for ($i=0;$i<$c;$i++){
		 		$td_size++;
		 		    	echo "<td rel='8'> ". $appCommon->show_order($group_by_field_arr[$i],__($group_by_field_arr[$i],true))."</td>";
		 	}
		 }?>
	 	
   
   
   <td class="cset-3" colspan="1"><?php __('asr')?></td>
   <td class="cset-4" colspan="1"><?php echo __('acd',true);?> (min)</td> 
   <td class="cset-4 last"><?php echo __('pdd',true);?> (ms)</td>
   
   <td colspan="2" class="cset-1"><?php __('totaltime')?></td>
   
   <td width="10%" rel="0">&nbsp;<?php echo $appCommon->show_order('org_total_cost',__('Usage Charge',true));echo $appCommon->show_sys_curr(); ?>&nbsp;</td>
   <?php if($report_type=='orig'): ?>
      <td rel="1"><?php echo __('LRN Charge',true);?></td>
   <?php endif; ?>
   <td><?php echo __('Total Cost',true);?></td>
   <td width="6%" rel="1">
   	<?php echo $appCommon->show_order('org_avg_rate',__('avgrate',true)); ?>
   </td>
   
   
   <td class="cset-2" colspan="<?php if($report_type=='orig')
      {?>6<?php }else{ ?>5<?php }?>"><?php __('Calls')?></td>
      
  </tr>
	<tr>
	<?php 
	if(!empty($group_by_field_arr)){
		 	$c=count($group_by_field_arr);
		 	for ($i=0;$i<$c;$i++){
		 		    	echo "<td>&nbsp;</td>";
		 	}
		 }
		 ?>
      
   
    
    <!--
    <td width="6%" rel="10" ><?php echo $appCommon->show_order('asr_std','std'); ?>	 </td>
    -->
    <td width="6%" rel="10" ><?php echo $appCommon->show_order('asr_cur', __('Cur',true)); ?>	 </td>
    <!--
    <td width="6%" rel="10" ><?php echo $appCommon->show_order('acd_std','std'); ?>	 </td>
    -->
    <td width="6%" rel="10" ><?php echo $appCommon->show_order('acd_cur', __('Cur',true)); ?>	 </td>
    <td width="6%" rel="10" ><?php echo __('Cur',true); ?>	 </td>
    
    <td width="6%" rel="2" class="cset-1">&nbsp;<?php echo $appCommon->show_order('total_duration',__('totalcalltime',true)); ?>&nbsp;</td>
   <td width="6%" rel="3" class="cset-1">&nbsp;<?php echo $appCommon->show_order('org_bill_minute',__('totalbilltime',true)); ?>&nbsp;</td>
    
    
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
   <?php if($report_type=='orig')
      {?>
    <td>&nbsp;</td>
    <?php }?>
   
   
   <td width="6%" rel="4" class="cset-2">&nbsp;<?php echo $appCommon->show_order('org_total_calls',__('totalcalls',true)); ?></td>
   <td width="6%" rel="5" class="cset-2"><?php echo $appCommon->show_order('notzero_calls',__('notzerocall',true)); ?>		</td>
    <td width="6%" rel="5" class="cset-2"><?php echo $appCommon->show_order('succ_calls',__('succcalls',true)); ?>		</td>
    <td width="6%" rel="5" class="cset-2"><?php echo $appCommon->show_order('busy_calls',__('busycalls',true)); ?>		</td>
 		 <td width="6%" rel="5" class="cset-2"><?php echo $appCommon->show_order('notchannel_calls',__('nochannelcalls',true)); ?>		</td>
    
    <?php if($report_type=='orig')
      {?>
    <td width="6%" rel="5" class="cset-2"><?php echo $appCommon->show_order('lrn_calls',__('LRN Calls',true)); ?>		</td>
    <?php }?>
   
   </tr>
  </thead>
  <?php 
  	
	//$report_type=isset($_GET['data']['Cdr']['report_type'])?$_GET['data']['Cdr']['report_type']:'';
	//$direction = empty($_GET['query']['direction']) ? 'all' : $_GET['query']['direction'];
	
     

    //if($report_type=='term'||$report_type=='')
    if($report_type=='term')
	{
		//if (!empty($direction) && ('termination' == $direction || 'all' == $direction))
		//{
   			echo $this->element('summary_report/term_common_table',array('td_size'=>$td_size));
		//}
    
    }
	// if($report_type=='orig'||$report_type=='')
	  else
      {
		  
		 // if (!empty($direction) && ('origination' == $direction || 'all' == $direction))
		 // {
			 
      		echo $this->element('summary_report/orig_common_table',array('td_size'=>$td_size));
		 // }
      }
      	
        ?>

 
 </table>
  <?php }?>
  <!--
  <div style=" height:25px; line-height:25px; padding:5px 10px;">
  	Direction:<select onchange="filter_orig_term()" name="direction" id="direction" class="select in-select">
    	<option value="all" <?php if (empty($direction) || 'all' == $direction) echo "selected='selected'"; ?> >All</option>
        <option value="origination" <?php if (!empty($direction) && 'origination' == $direction) echo "selected='selected'"; ?> >Inbound</option>
        <option value="termination" <?php if (!empty($direction) && 'termination' == $direction) echo "selected='selected'"; ?> >Outbound</option>
    </select>
  </div>
  -->
  <script type="text/javascript">
  	/*
	function filter_orig_term(){
		var direction=$("#direction").val();
		$("#query-direction").val(direction);
	}
	*/
	jQuery(document).ready(function(){
		<?php if($report_type=='orig'){?>
			$("#query-direction").val('origination');
		<?php }elseif($report_type=='term'){?>
			$("#query-direction").val('termination');
		<?php }else{?>
			$("#query-direction").val('origination');
		<?php }?>
	});
	function filter_orig_term(){
		var direction=$("#direction").val();
		$("#query-direction").val(direction);
	}
  </script>
  