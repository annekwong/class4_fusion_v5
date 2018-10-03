	
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
$t_org_pdd=0;
$t_asr_std=0;
$t_asr_cur=0;
$t_acd_std=0;
$t_acd_cur=0;
$t_lnp_dipping_cost = 0;
	
	?>
		<tbody class="orig-calls" id="orig-calls">
<!--
    <tr class="subheader row-1">
   	 <td colspan="<?php  echo  $td_size+14;?>"   style="text-align: center; font-size:16px; font-weight:bold;"><?php __('origination')?></td>
 	</tr>
-->
	 <?php 
	 			$size=count($client_org);     
	 			for ($i=0;$i<$size;$i++){
					$org_total_cost=$client_org[$i][0]['org_total_cost'];
					$org_total_duration=$client_org[$i][0]['total_duration'];
					$org_bill_minute=$client_org[$i][0]['org_bill_minute'];
					$org_avg_rate=$client_org[$i][0]['org_avg_rate'];
					$org_total_calls=$client_org[$i][0]['org_total_calls'];
					$org_notzero_calls=$client_org[$i][0]['notzero_calls'];
					$org_succ_calls=$client_org[$i][0]['succ_calls'];
					$org_busy_calls=$client_org[$i][0]['busy_calls'];
					$org_nochannel_calls=$client_org[$i][0]['channel_unavailable'];
					$org_lrn_calls=$client_org[$i][0]['lrn_calls'];
					//$asr_std=$client_org[$i][0]['asr_std'];
					$asr_cur=$client_org[$i][0]['asr_cur'];
					//$acd_std=$client_org[$i][0]['acd_std'];
					$acd_cur=$client_org[$i][0]['acd_cur'];
					$org_pdd=$client_org[$i][0]['pdd'];
					$org_lnp_dipping_cost = $client_org[$i][0]['lnp_dipping_cost'];
                                        $t_lnp_dipping_cost += $org_lnp_dipping_cost;
					$t_org_total_cost=$t_org_total_cost+$org_total_cost;
					$t_org_total_duration=$t_org_total_duration+$org_total_duration;
					$t_org_bill_minute=$t_org_bill_minute+$org_bill_minute;
					if(empty($t_org_bill_minute)){
						$t_org_avg_rate=0.00;
					}else{
						$t_org_avg_rate=$t_org_total_cost/$t_org_bill_minute;
					}
					$t_org_total_calls=$t_org_total_calls+$org_total_calls;
					$t_org_notzero_calls=$t_org_notzero_calls+$org_notzero_calls;
					$t_org_succ_calls=$t_org_succ_calls+$org_succ_calls;	
					$t_org_busy_calls=$t_org_busy_calls+$org_busy_calls;
					$t_org_nochannel_calls=$t_org_nochannel_calls+$org_nochannel_calls;
					$t_org_lrn_calls=$t_org_lrn_calls+$org_lrn_calls;
					$t_org_pdd=$t_org_pdd+$org_pdd;
					if($t_org_total_calls==0){
						$t_asr_std=0.00;
					}else{
						$t_asr_std=$t_org_succ_calls/($t_org_total_calls);
					}
					if(empty($t_org_total_calls)){
						$t_asr_cur=0.00;
					}else{
						$t_asr_cur=$t_org_notzero_calls/$t_org_total_calls;
					}
					if(empty($t_org_succ_calls)){
						$t_acd_std=0.00;
					}else{
						$t_acd_std=$t_org_total_duration/$t_org_succ_calls;
					}
					if(empty($t_org_notzero_calls)){
						$t_acd_cur=0.00;
					}else{
						$t_acd_cur=$t_org_total_duration/$t_org_notzero_calls;
					}
					if($show_subtotals=='true'){
						$sub_org_total_cost=$sub_org_total_cost+$org_total_cost;
						$html_org_total_cost=number_format($sub_org_total_cost,5);
						$sub_org_total_duration=$sub_org_total_duration+$org_total_duration;
						$html_org_total_duration=number_format($sub_org_total_duration,2);
						$sub_org_bill_minute=$sub_org_bill_minute+$org_bill_minute;
						$html_org_bill_minute=number_format($sub_org_bill_minute,2);
						if(empty($sub_org_bill_minute)){
							$sub_org_avg_rate=0.00;
						}else{
							$sub_org_avg_rate=$sub_org_total_cost/$sub_org_bill_minute;
						}
						$html_org_avg_rate=number_format($sub_org_avg_rate,5);
						$sub_org_total_calls=$sub_org_total_calls+$org_total_calls;
						$sub_org_notzero_calls=$sub_org_notzero_calls+$org_notzero_calls;
						$sub_org_succ_calls=$sub_org_succ_calls+$org_succ_calls;
						$sub_org_busy_calls=$sub_org_busy_calls+$org_busy_calls;
						$sub_org_nochannel_calls=$sub_org_nochannel_calls+$org_nochannel_calls;
						$sub_org_lrn_calls=$sub_org_lrn_calls+$org_lrn_calls;
						$sub_org_pdd=$sub_org_pdd+$org_pdd;
						
						if($sub_org_total_calls==0){
							$sub_asr_std=0.00;
						}else{
							$sub_asr_std=$sub_org_succ_calls/($sub_org_total_calls);
						}
$html_asr_std=number_format($sub_asr_std,2);
if(empty($sub_org_total_calls)){
	$sub_asr_cur=0.00;
}else{
	$sub_asr_cur=$sub_org_notzero_calls/$sub_org_total_calls;
}

$html_asr_cur=number_format($sub_asr_cur,2);
if(empty($sub_org_succ_calls)){
	$sub_acd_std=0.00;
}else{
	$sub_acd_std=$sub_org_total_duration/$sub_org_succ_calls;
}

$html_acd_std=number_format($sub_acd_std,2);
if(empty($sub_org_notzero_calls)){
	$sub_acd_cur=0.00;
}else{
	$sub_acd_cur=$sub_org_total_duration/$sub_org_notzero_calls;
}
	$html_acd_cur=number_format($sub_acd_cur,2);
	$html_pdd_cur = empty($sub_org_notzero_calls) ? 0 : number_format($sub_org_pdd/$sub_org_notzero_calls,2);
//***************************************************************************************************************************************            	
                                                                                                        //生成子统计 html
//***************************************************************************************************************************************
 $f=$group_by_field_arr[0];
 $field=$client_org[$i][0][$f];
 $colspan=$td_size-1;
 	$subtotals_html=<<<EOD
<tr class="subtotals row-1">
                <td> {$field}  </td>
                <td class="in-decimal" colspan="{$colspan}">SubTotal:</td>    
                <td class="in-decimal">{$sub_org_total_calls}</td>
                <td class="in-decimal">{$sub_org_notzero_calls}</td>
                <td class="in-decimal">{$sub_org_succ_calls}</td>
                <td class="in-decimal">{$sub_org_busy_calls}</td>
                <td class="in-decimal">{$sub_org_nochannel_calls}</td>
                <td class="in-decimal">{$sub_org_lrn_calls}</td>
                <!--
				<td class="in-decimal">{$html_asr_std} %</td>
				-->
                <td class="in-decimal">{$html_asr_cur} %</td>
                <td class="in-decimal">{$html_acd_cur}</td>
                <!--
				<td class="in-decimal">{$html_acd_std}</td>
				-->
                <td class="in-decimal last">{$html_pdd_cur}</td>
				 <td class="in-decimal">{$html_org_total_cost}</td>
                 <td class="in-decimal">{$html_org_avg_rate}</td>
                 <td class="in-decimal">{$html_org_total_duration}</td>
                <td class="in-decimal">{$html_org_bill_minute}</td> 
				</tr>
EOD;
	
 	
 	
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
 	
 	
 }else{
 	$subtotals_html='';
 	
 }
}




            	?>
         <tr class=" row-2"   style="color: #4B9100">
  <?php
 //输出分组的字段
 if(!empty($group_by_field_arr)){
 	$c=count($group_by_field_arr);
 	for ($ii=0;$ii<$c;$ii++){
 		$f=$group_by_field_arr[$ii];
 $field=$client_org[$i][0][$f];
 if(trim($field)==''){
 echo "<td  class='in-decimal'  style='text-align:center;color:#6694E3;'><strong  style='color:#992F00;'>".__('Unknown',true)."</td>";
 } else{	echo " <td  class='in-decimal'  style='text-align:center;color:#6694E3;'>".$field ."</td>";}
 	}
 	
 	
 }?>

   
   
     
       <td class="in-decimal"><?php  echo  number_format($asr_cur,2) ?>%</td>
        
        <td class="in-decimal"><?php  echo  number_format($acd_cur,2) ?></td>
        <td class="in-decimal"><?php  echo  empty($org_notzero_calls) ? 0 : number_format($org_pdd/$org_notzero_calls,0) ?></td>
        
        <td class="in-decimal"><?php  echo  number_format($org_total_duration,2) ?></td>
   <td class="in-decimal"><?php  echo  number_format($org_bill_minute,2) ?></td>

        
        <td class="in-decimal"><?php  echo   number_format($appCommon->currency_rate_conversion($org_total_cost),5); ?> </td>
        <td><?php echo  number_format($org_lnp_dipping_cost,5); ?></td>
        <td class="in-decimal"><?php  echo   number_format($org_total_cost + $org_lnp_dipping_cost,5); ?> </td>
   <td class="in-decimal"><?php  echo   number_format($appCommon->currency_rate_conversion($org_avg_rate),5); ?> </td>
   
	<td class="in-decimal"><?php  echo  number_format($org_total_calls,0) ?></td>
   <td class="in-decimal"><?php  echo  number_format($org_notzero_calls,0) ?></td>
   <td class="in-decimal"><?php  echo  number_format($org_succ_calls,0) ?></td>
    <td class="in-decimal"><?php  echo  number_format($org_busy_calls,0) ?></td>
     <td class="in-decimal"><?php  echo  number_format($org_nochannel_calls,0) ?></td>
     <td class="in-decimal"><?php  echo  number_format($org_lrn_calls,0) ?></td>
    </tr>
            
            
            
 <?php 
  if($show_subtotals=='true'){
 	 echo $subtotals_html;
 }
 }
            
  if($size>1){?>
  <tr class="totals row-1">
   <td class="in-decimal" colspan="<?php echo $td_size; ?>"><?php echo __('Total',true);?>:</td>
   
   
   <!--
   <td class="in-decimal"><?php  echo  number_format($t_asr_std*100,2) ?>%</td>
   -->
   <td class="in-decimal"><?php  echo  number_format($t_asr_cur*100,2) ?>%</td>
   <!--
   <td class="in-decimal"><?php  echo  number_format($t_acd_std,2) ?></td>
   -->
   <td class="in-decimal"><?php  echo  number_format($t_acd_cur,2) ?></td>
   <td class="in-decimal"><?php  echo  empty($t_org_notzero_calls) ? 0 : number_format($t_org_pdd/$t_org_notzero_calls,0); ?></td>
   
   <td class="in-decimal"><?php  echo  number_format($t_org_total_duration,2) ?></td>
   <td class="in-decimal"><?php  echo  number_format($t_org_bill_minute,2) ?></td>
   
   
   <td class="in-decimal"><?php  echo  number_format($appCommon->currency_rate_conversion($t_org_total_cost),5);?> </td>
   <td><?php echo  number_format($appCommon->currency_rate_conversion($t_lnp_dipping_cost),5); ?></td>
   <td class="in-decimal"><?php  echo   number_format($appCommon->currency_rate_conversion($t_org_total_cost + $t_lnp_dipping_cost),5); ?> </td>
   <td class="in-decimal"><?php  echo   number_format($appCommon->currency_rate_conversion($t_org_avg_rate),5);?> </td>
   
   
   
   <td class="in-decimal"><?php  echo  number_format($t_org_total_calls,0) ?></td>
   <td class="in-decimal"><?php  echo  number_format($t_org_notzero_calls,0) ?></td>
   <td class="in-decimal"><?php  echo  number_format($t_org_succ_calls,0) ?></td>
   <td class="in-decimal"><?php  echo  number_format($t_org_busy_calls,0) ?></td>
   <td class="in-decimal"><?php  echo  number_format($t_org_nochannel_calls,0) ?></td>
   <td class="in-decimal"><?php  echo  number_format($t_org_lrn_calls,0) ?></td>
  </tr>
 <?php }?>
 </tbody>
 
 