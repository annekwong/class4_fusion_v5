<!--导入所有reoprt页面的input和select样式文件-->
<?php echo $this->element('magic_css_three');?>
<div id="title">
  <h1>
    <?php __('Statistics')?>
    &gt;&gt;
    <?php __('Orig-TermReport')?>
  </h1>
</div>
<div id="container">
<?php 
 $size=count($client_org);
if($size==0){?>
<div class="msg"><?php echo __('no_data_found',true);?></div>
<?php }else{?>
<?php echo $this->element('report/real_period')?>
<?php 
$td_size=0;
$t_total_cost=0.000;
$t_avg_rate=0.000;

$t_total_calls=0;
$t_notzero_calls=0;
$t_succ_calls=0;
$t_busy_calls=0;
$t_nochannel_calls=0;
$t_asr_std=0.00;
$t_asr_cur=0.00;
$t_acd_std=0.00;
$t_acd_cur=0.00;
	?>

<!-- ****************************************普通输出******************************************* -->
<div class="table_container">
<?php 
 
if(0){//empty($client_org[0][0]['org_bill_minute']) && empty($client_org[0][0]['term_bill_minute'])){?>
<div class="msg"><?php echo __('no_data_found',true);?></div>
<?php }else{?>
  <table class="list nowrap with-fields" style="width: 100%">
    <thead>
      <tr>
        <?php

 //输出分组的字段

 if(!empty($group_by_field_arr)){
 	 
 	$c=count($group_by_field_arr);

 	for ($i=0;$i<$c;$i++){
 		$td_size++;
 		$groupby_field=$appCommon->show_order($group_by_field_arr[$i],__($group_by_field_arr[$i],true));
 		    	echo "<td> ".$groupby_field ."&nbsp;&nbsp;</td>";
    
 	}
 	
 }?>
        <td class="cset-1" colspan="3"><?php __('origination')?></td>
        <td class="cset-2" colspan="3"><?php  __('termination')?></td>
        <td class="cset-3" colspan="2"><?php echo $appCommon->show_order('profit',__('profit',true)); ?></td>
        <td rel="8"><?php echo $appCommon->show_order('total_duration',__('totalcalltime',true)); ?> <?php __('Time')?>, <?php __('min')?> </td>
        
        <td class="cset-4" colspan="1"><?php __('asr')?></td>
        <td class="cset-5" colspan="1"><?php __('acd')?></td>
        <td class="cset-5" colspan="1"><?php __('pdd')?></td>
        <td class="cset-6 last" colspan="5" style="text-align: center;"><span>
          <?php __('calls')?>
          </span> <span class="helptip" id="ht-100004"></span><span class="tooltip"
				id="ht-100004-tooltip"><?php __('These values (except for not zero calls) are approximate and may slightly differ from the values in the summary report. For exact values refer to summary report')?>.</span></td>
      </tr>
      <tr>
<?php

 //输出分组的字段

 if(!empty($group_by_field_arr)){
 	 
 	$c=count($group_by_field_arr);

 	for ($i=0;$i<$c;$i++){
 		
 		$groupby_field=$appCommon->show_order($group_by_field_arr[$i],__($group_by_field_arr[$i],true));
 		    	echo "<td>&nbsp;&nbsp;</td>";
    
 	}
 	
 }?>
        <td class="cset-1" rel="0"><?php echo $appCommon->show_order('org_bill_minute',__('billedtime',true)); ?></td>
        <td class="cset-1" rel="1"><?php echo $appCommon->show_order('org_total_cost',__('Cost',true)); echo $appCommon->show_sys_curr();?></td>
        <td class="cset-1" rel="2"><?php echo $appCommon->show_order('org_avg_rate',__('avgrate',true)); echo $appCommon->show_sys_curr();?></td>
        <td class="cset-1" rel="0"><?php echo $appCommon->show_order('term_bill_minute',__('billedtime',true)); ?></td>
        <td class="cset-2" rel="4"><?php echo $appCommon->show_order('term_total_cost',__('Cost',true)); echo $appCommon->show_sys_curr();?></td>
        <td class="cset-2" rel="5"><?php echo $appCommon->show_order('term_avg_rate',__('avgrate',true));echo $appCommon->show_sys_curr(); ?></td>
        <td class="cset-3" rel="6"><?php echo $appCommon->show_sys_curr();?></td>
        <td class="cset-3" rel="7">%</td>
        <td>&nbsp;</td>
        <!--
        <td width="6%" class="cset-4" rel="9"><?php echo $appCommon->show_order('asr_std','Std'); ?></td>
        -->
        <td width="6%" class="cset-4" rel="10"><?php echo $appCommon->show_order('asr_cur', __('Cur',true)); ?></td>
        <!--
        <td width="5%" class="cset-5" rel="11"><?php echo $appCommon->show_order('acd_std','Std'); ?></td>
        -->
        <td width="5%" class="cset-5" rel="12"><?php echo $appCommon->show_order('acd_cur', __('Cur',true)); ?></td>
        <td>&nbsp;</td>
        <td width="6%" class="cset-6" rel="13"><?php echo $appCommon->show_order('total_calls',__('totalofcalls',true)); ?></td>
        <td width="6%" class="cset-6" rel="14"><?php echo $appCommon->show_order('notzero_calls',__('notzerocall',true)); ?></td>
        <td width="6%" class="cset-6" rel="15"><?php echo $appCommon->show_order('succ_calls',__('Success',true)); ?></td>
        <td width="6%" class="cset-6" rel="16"><?php echo $appCommon->show_order('busy_calls',__('busycalls',true)); ?></td>
        <td width="6%" class="cset-6 last" rel="17"><?php echo $appCommon->show_order('notchannel_calls',__('nochannelcalls',true)); ?></td>
      </tr>
    </thead>
    <tbody class="rows">
      <?php  

 //总统计变量
$t_org_total_cost=0.00;
$t_org_bill_minute=0;
$t_term_total_cost=0.00;
$t_term_bill_minute=0;
$t_total_duration=0;
$t_pdd = 0;
//子统计变量
$sub_org_total_cost=0;
$sub_org_bill_minute=0;
$sub_term_total_cost=0;
$sub_term_bill_minute=0;
$sub_total_duration = 0;
$sub_total_calls = 0;
$sub_notzero_calls = 0;
$sub_succ_calls = 0;
$sub_busy_calls = 0;
$sub_nochannel_calls = 0;
	$size = count ( $client_org );
	
	for($i = 0; $i < $size; $i ++) {
 		
//***************************************************************************************************************************************            	
                                                                                                        //单个行记录 
//***************************************************************************************************************************************
$org_total_cost=$client_org[$i][0]['org_total_cost'];
$org_bill_minute=$client_org[$i][0]['org_bill_minute'];
$term_total_cost=$client_org[$i][0]['term_total_cost'];
$term_bill_minute=$client_org[$i][0]['term_bill_minute'];
$total_duration=$client_org[$i][0]['total_duration'];
$org_avg_rate=$client_org[$i][0]['org_avg_rate'];
$term_avg_rate=$client_org[$i][0]['term_avg_rate'];
$profit=$client_org[$i][0]['profit'];
$profit_per=$client_org[$i][0]['profit_percentage'];
$pdd=$client_org[$i][0]['pdd'];
$total_calls=$client_org[$i][0]['total_calls'];
$notzero_calls=$client_org[$i][0]['notzero_calls'];
$succ_calls=$client_org[$i][0]['succ_calls'];
$busy_calls=$client_org[$i][0]['busy_calls'];
$nochannel_calls=$client_org[$i][0]['notchannel_calls'];
$asr_std=$client_org[$i][0]['asr_std'];
$asr_cur=$client_org[$i][0]['asr_cur'];
$acd_std=$client_org[$i][0]['acd_std'];
$acd_cur=$client_org[$i][0]['acd_cur'];

//***************************************************************************************************************************************            	
                                                                                                        //计算总统计
//***************************************************************************************************************************************
$t_org_total_cost=$t_org_total_cost+$org_total_cost;
$t_org_bill_minute=$t_org_bill_minute+$org_bill_minute;
$t_term_total_cost=$t_term_total_cost+$term_total_cost;
$t_term_bill_minute=$t_term_bill_minute+$term_bill_minute;
$t_total_duration=$t_total_duration+$total_duration;
$t_pdd += $pdd;
if(empty($t_org_bill_minute)){
	$t_org_avg_rate=0.00;
}else{
	$t_org_avg_rate=$t_org_total_cost/$t_org_bill_minute;
}
 if(empty($t_term_bill_minute)){
	$t_term_avg_rate=0.00;
}else{
	$t_term_avg_rate=$t_term_total_cost/$t_term_bill_minute;
}
$t_profit=$t_org_total_cost-$t_term_total_cost;
if(empty($t_org_total_cost)){
	$t_profit_per=0.00;
}else{
	$t_profit_per=$t_profit/$t_org_total_cost*100;
}
$t_total_calls=$t_total_calls+$total_calls;
$t_notzero_calls=$t_notzero_calls+$notzero_calls;
$t_succ_calls=$t_succ_calls+$succ_calls;
$t_busy_calls=$t_busy_calls+$busy_calls;
$t_nochannel_calls=$t_nochannel_calls+$nochannel_calls;
if($t_total_calls==$t_nochannel_calls){
	$t_asr_std=0.00;
}else{
	$t_asr_std=$t_succ_calls/($t_total_calls-$t_nochannel_calls)*100;
}
if(empty($t_total_calls)){
	$t_asr_cur=0.00;
	
}else{
	$t_asr_cur=$t_notzero_calls/$t_total_calls*100;
	
}
if(empty($t_succ_calls)){
	$t_acd_std=0.00;
}else{
	$t_acd_std=$t_total_duration/$t_succ_calls;
}

if(empty($t_notzero_calls)){
	$t_acd_cur=0.00;
}else{
	$t_acd_cur=$t_total_duration/$t_notzero_calls;
	
}


//***************************************************************************************************************************************            	
                                                                                                        //计算子统计
//***************************************************************************************************************************************

if($show_subtotals=='true'){
$sub_org_total_cost=$sub_org_total_cost+$org_total_cost;
$html_sub_org_total_cost=number_format($sub_org_total_cost,5);
$sub_org_bill_minute=$sub_org_bill_minute+$org_bill_minute;
$html_sub_org_bill_minute=number_format($sub_org_bill_minute,2);
$sub_term_total_cost=$sub_term_total_cost+$term_total_cost;
$html_sub_term_total_cost=number_format($sub_term_total_cost,5);
$sub_term_bill_minute=$sub_term_bill_minute+$term_bill_minute;
$html_sub_term_bill_minute=number_format($sub_term_bill_minute,2);
$sub_total_duration=$sub_total_duration+$total_duration;
$html_sub_total_duration=number_format($sub_total_duration,2);

if(empty($sub_org_bill_minute)){
	$sub_org_avg_rate=0.00;
}else{
	$sub_org_avg_rate=$sub_org_total_cost/$sub_org_bill_minute;

}
	$html_sub_org_avg_rate=number_format($sub_org_avg_rate,5);
 if(empty($sub_term_bill_minute)){
	$sub_term_avg_rate=0.00;
}else{
	$sub_term_avg_rate=$sub_term_total_cost/$sub_term_bill_minute;
}

$html_sub_term_avg_rate=number_format($sub_term_avg_rate,5);

$sub_profit=$sub_org_total_cost-$sub_term_total_cost;
$html_sub_profit=number_format($sub_profit,5);
if(empty($sub_org_total_cost)){
	$sub_profit_per=0.00;
}else{
	
	$sub_profit_per=$sub_profit/$sub_org_total_cost*100;
}


$html_sub_profit_per=number_format($sub_profit_per,5);
$sub_total_calls=$sub_total_calls+$total_calls;
$sub_notzero_calls=$sub_notzero_calls+$notzero_calls;
$sub_succ_calls=$sub_succ_calls+$succ_calls;
$sub_busy_calls=$sub_busy_calls+$busy_calls;
$sub_nochannel_calls=$sub_nochannel_calls+$nochannel_calls;

if($sub_total_calls==$sub_nochannel_calls){
	$sub_asr_std=0.00;
}else{
	$sub_asr_std=$sub_succ_calls/($sub_total_calls-$sub_nochannel_calls)*100;
}
$html_sub_asr_std=number_format($sub_asr_std,2);
if(empty($sub_total_calls)){
	$sub_asr_cur=0.00;
}else{
	$sub_asr_cur=$sub_notzero_calls/$sub_total_calls*100;
}
$html_sub_asr_cur=number_format($sub_asr_cur,2);
if(empty($sub_succ_calls)){
	$sub_acd_std=0.00;
}else{
	$sub_acd_std=$sub_total_duration/$sub_succ_calls;
}
$html_sub_acd_std=number_format($sub_acd_std,2);

if(empty($sub_notzero_calls)){
	$sub_acd_cur=0.00;
}else{
	$sub_acd_cur=$sub_total_duration/$sub_notzero_calls;
}
$html_sub_acd_cur=number_format($sub_acd_cur,2);


 	//***************************************************************************************************************************************            	
                                                                                                        //生成子统计 html
//***************************************************************************************************************************************
 $f=$group_by_field_arr[0];
 $field=$client_org[$i][0][$f];
 $colspan=$td_size-1;
 	$subtotals_html=<<<EOD
<tr class="subtotals row-2">
            <td>{$field}  </td>
            <td align="right" colspan="{$colspan}">SubTotal:</td>
            <td class="in-decimal">{$html_sub_org_bill_minute}</td>
            <td class="in-decimal pos">{$html_sub_org_total_cost}</td>
            <td class="in-decimal right">{$html_sub_org_avg_rate}</td>
            <td class="in-decimal">{$html_sub_term_bill_minute}</td>
            <td class="in-decimal neg">{$html_sub_term_total_cost}</td>
            <td class="in-decimal right">{$html_sub_term_avg_rate}</td>
            <td class="in-decimal pos-b">{$html_sub_profit}</td>
            <td class="in-decimal pos-b right">{$html_sub_profit_per}%</td>
            <td class="in-decimal right">{$html_sub_total_duration}</td>
		   <td class="in-decimal">{$html_sub_asr_cur} %</td>
		   <td class="in-decimal right">{$html_sub_acd_cur}</td>
            <td class="in-decimal">{$sub_total_calls}</td>
            <td class="in-decimal">{$sub_notzero_calls}</td>
            <td class="in-decimal">{$sub_succ_calls}</td>
            <td class="in-decimal">{$sub_busy_calls}</td>
            <td class="in-decimal last">{$sub_nochannel_calls}</td>
        </tr>
EOD;

/*
 <td class="in-decimal">{$html_sub_asr_std} %</td>
<td class="in-decimal">{$html_sub_asr_cur} %</td>
<td class="in-decimal">{$html_sub_acd_std}</td>
<td class="in-decimal right">{$html_sub_acd_cur}</td>
*/ 
//上面四列在EOD中的空行处

//***************************************************************************************************************************************            	
                                                                                                 //重置子统计变量
//***************************************************************************************************************************************
if($size>1){
	if($i<$size-1){
			$next_field=$client_org[$i+1][0][$f];
	}else{
		$next_field=$client_org[$i][0][$f];
	}

}else{
	
	$next_field=$client_org[$i][0][$f];
}

 if($field!=$next_field){
$sub_org_bill_minute=0;
$sub_org_total_cost=0;
$sub_org_avg_rate=0;
$sub_term_bill_minute=0;
$sub_term_total_cost=0;
$sub_term_avg_rate=0;
$sub_profit=0;
$sub_profit_per=0;
$sub_total_duration = 0;
$sub_asr_std=0;
$sub_asr_cur=0;
$sub_acd_std=0;
$sub_acd_cur=0;
$sub_pdd = 0;
$sub_total_calls = 0;
$sub_notzero_calls = 0;
$sub_succ_calls = 0;
$sub_busy_calls = 0;
$sub_nochannel_calls = 0;
 }else{
 	$subtotals_html='';
 	
 }
}

 ?>
      <tr class=" row-2" style="color: #4B9100">
        <?php
 //输出分组的字段
 if(!empty($group_by_field_arr)){
 	$c=count($group_by_field_arr);
 	for ($ii=0;$ii<$c;$ii++){
 		$f=$group_by_field_arr[$ii];
 $field=$client_org[$i][0][$f];
 if(trim($field)==''){
 echo "<td  class='in-decimal'  style='text-align:center;color:#6694E3;'>".__('Unknown',true)."</td>";
 } else{	echo " <td  class='in-decimal'  style='text-align:center;color:#6694E3;'>".$field ."</td>";}
 	}
 }?>
        <td class="in-decimal"><?php echo   number_format($org_bill_minute,2)?></td>
        <td class="in-decimal pos"><?php echo  number_format($appCommon->currency_rate_conversion($org_total_cost),5);?></td>
        <td class="in-decimal right zero"><?php  echo  number_format($appCommon->currency_rate_conversion($org_avg_rate),5); ?></td>
        <td class="in-decimal"><?php echo number_format($term_bill_minute,2)?></td>
        <td class="in-decimal neg"><?php echo   number_format($appCommon->currency_rate_conversion($term_total_cost),5);?></td>
        <td class="in-decimal right zero"><?php  echo  number_format( $appCommon->currency_rate_conversion($term_avg_rate),5); ?></td>
        <td class="in-decimal pos-b"><?php echo number_format($profit,5)?></td>
        <td class="in-decimal pos-b right"><?php echo  number_format($profit_per,5);?>%</td>
        <td class="in-decimal right"><?php echo number_format($total_duration,2)?></td>
     
     <!--
        <td class="in-decimal"><?php  echo number_format($asr_std,2);?>
          %</td>
          -->
        <td class="in-decimal"><?php  echo number_format($asr_cur,2);?>
          %</td>
          <!--
        <td class="in-decimal"><?php echo  number_format($acd_std,2);?></td>
        -->
        <td class="in-decimal right"><?php echo  number_format($acd_cur,2);?></td>
        <td class="in-decimal"><?php echo empty($notzero_calls) ? 0 : number_format($pdd/$notzero_calls, 0);?></td>
        <td class="in-decimal"><?php echo $total_calls?></td>
        <td class="in-decimal"><?php echo $notzero_calls?></td>
        <td class="in-decimal"><?php echo $succ_calls?></td>
        <td class="in-decimal"><?php echo $busy_calls?></td>
        <td class="in-decimal last"><?php echo $nochannel_calls?></td>
      </tr>
      <?php 
 if($show_subtotals=='true'){
 	 echo $subtotals_html;
 	}
 
 }

if($size>1){?>
      <tr class="totals row-1">
        <td class="in-decimal" colspan="<?php echo $td_size; ?>"><?php __('Total')?>:</td>
        <td class="in-decimal">
          <?php  echo number_format( $t_org_bill_minute,2); ?>
          </td>
        <td class="in-decimal">
          <?php  echo  number_format($t_org_total_cost,5) ?>
          </td>
        <td class="in-decimal"><?php  echo  number_format($t_org_avg_rate,5) ?></td>
        <td class="in-decimal"><?php  echo  number_format($t_term_bill_minute,2) ?></td>
        <td class="in-decimal"><?php  echo  number_format($t_term_total_cost,5) ?></td>
        <td class="in-decimal"><?php  echo  number_format($t_term_avg_rate,5) ?></td>
        <td class="in-decimal"><?php  echo  number_format($t_profit,5) ?></td>
        <td class="in-decimal"><?php  echo  number_format($t_profit_per,2) ?>
          %</td>
        <td class="in-decimal"><?php  echo  number_format($t_total_duration,2) ?></td>
        
        <!--
        <td class="in-decimal"><?php  echo  number_format($t_asr_std,2);?>
          %</td>
          -->
        <td class="in-decimal"><?php  echo number_format($t_asr_cur,0);?>
          %</td>
          <!--
        <td class="in-decimal"><?php echo  number_format($t_acd_std,2);?></td>
         -->
        <td class="in-decimal right"><?php echo  number_format($t_acd_cur,2);?></td>
        <td class="in-decimal right"><?php echo empty($t_notzero_calls) ? 0 : number_format($t_pdd/$t_notzero_calls, 0);?></td>
       
        <td class="in-decimal"><?php echo number_format($t_total_calls,0)?></td>
        <td class="in-decimal"><?php echo number_format($t_notzero_calls,0)?></td>
        <td class="in-decimal"><?php echo number_format($t_succ_calls,0)?></td>
        <td class="in-decimal"><?php echo number_format($t_busy_calls,0)?></td>
        <td class="in-decimal last"><?php echo number_format($t_nochannel_calls,0)?></td>
      </tr>
      <?php }?>
    </tbody>
  </table>
  <?php }?>
  <?php }?>
  <?php   //echo  $this->element('orig_term_report/report_amchart')?>
  <!--生成图片报表-->
<?php //echo $this->element("report/image_report")?>
<!--//生成图片报表-->
  <?php   echo  $this->element('orig_term_report/search')?>
  <?php echo $this->element('search_report/search_js_show');?> 
 </div>

</div>