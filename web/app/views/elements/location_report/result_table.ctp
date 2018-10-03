
<?php 
	 $size=count($client_org);
		if($size==0){?>
			<div class="msg"><?php echo __('no_data_found',true);?></div>
<?php }else{?>
<?php echo $this->element('report/real_period')?>
					<?php if(!empty($show_subgroups)){
						//子统计字段
							if(!empty($show_subtotals)){
							 	$sub_total_cdr_cost=0;
								$sub_total_call_duration=0;
								$sub_total_calls=0;
							 	$sub_total_egress_cost=0;
								$sub_percent=0.00;
								$sub_percent_100=0.00;
							}
							$pre_field='';
							$curr_field='';
							$next_field='';
							$field_name=$group_by_field_arr[0];
							$curr_field=$client_org[0][0][$field_name];
							$pre_field=$curr_field;
							//条件字段
							$t1='';
							$t2='';
							$t3='';
							$t4='';
							$t5='';
							$t6='';
							$t7='';
							$t8='';
							$t9='';
							$for_td1='';
							$for_td2='';
							$for_td3='';
							$for_td4='';
							$for_td5='';
							$for_td6='';
							$for_td7='';
							$for_td8='';
							$for_td9='';
							//分组字段头
							$groupby_th='';
							$groupby_td_v='';//分组字段值
							$groupby_subth='';//自统计字段
							 	$c=count($group_by_field_arr);
							 	for ($ii=1;$ii<$c;$ii++){
							 		$groupby_th=$groupby_th. "<td rel='8'  width='6%'>&nbsp;&nbsp;&nbsp;&nbsp; ".__($group_by_field_arr[$ii],true)."  &nbsp;&nbsp;</td>";
							 		if(!empty($show_subtotals)){
							 			$tmp_str='';
							 			if($ii==$c-1){
							 				$tmp_str="Sub Total";
							 			}
							 			$groupby_subth=$groupby_subth. "<td rel='8'  width='6%'>&nbsp;&nbsp;&nbsp;&nbsp; ".$tmp_str."  &nbsp;&nbsp;</td>";
							 		}
							 	}
						$head_div=" <div class='group-title'  >".  __($field_name,true).": <span>".$curr_field."</span><br></div>";
						$table_head="<table class='list' ><thead><tr>$t1 $t2   $t3   $t4  $t5  $t6 $t7  $t8  $t9  $groupby_th
            <td width='8%' rel='0' >&nbsp;Duration&nbsp;</td>
           	<td width='8%' rel='1' >Call Count</td>
            <td width='8%' rel='2' class='cset-1'>&nbsp;Revenue &nbsp;</td>
            <td width='6%' rel='2' class='cset-1'>&nbsp;Total Cost&nbsp;</td>
            <td width='6%' rel='2' class='cset-1'>&nbsp;".__('profit',true)." &nbsp;</td>
            <td width='6%' rel='2' class='cset-1'>&nbsp;".__('profit',true)." % &nbsp;</td>
            <td width='16%' rel='2' class='cset-1'>&nbsp;% &nbsp;</td>
            </tr></thead><tbody class='orig-calls'>" ;
						$for_tr='';//循环输出的tr
						$sub_tr='';//子统计的tr
					 $table_footer="</tbody></table>";
					 //输出头
 					echo $head_div,$table_head;
					 //循环输出表格
   			$size=count($client_org);
   for ($i=0;$i<$size;$i++){
   	$groupby_td_v='';//重置分组字段td
   	//生成分组字段td
    	for ($ii=1;$ii<$c;$ii++){
 				$f=$group_by_field_arr[$ii];
 				$field=$client_org[$i][0][$f];
			 if(trim($field)==''){
			 		$groupby_td_v=$groupby_td_v. "<td    style='text-align:center;color:#6694E3;'><strong  style='color:red;'>".__('Unknown',true)."</td>";
			 }else{
			 		$groupby_td_v=$groupby_td_v. " <td    style='text-align:center;color:#6694E3;'>".$field ."</td>";
			 }
    	}
  	$cdr_cost=$client_org[$i][0]['total_cdr_cost'];
 		$egress_cost=$client_org[$i][0]['total_egress_cost'];
 		$calls=$client_org[$i][0]['total_calls'];
 		$duration=$client_org[$i][0]['total_duration'];
 		$egress_cost=$client_org[$i][0]['total_egress_cost'];
   if(empty($cdr_cost)||$cdr_cost=='0'){
 			$percent=0.00;
 		}else{
 			$percent=($cdr_cost-$egress_cost)/$cdr_cost;
 		}
 	//循环的表格
	 	$for_tr="	<tr class=' row-2'   style='color: #4B9100'>$for_td1 $for_td2  $for_td3  $for_td4  $for_td5 $for_td6  $for_td7  $for_td8 $for_td9  $groupby_td_v
	   <td >".number_format($duration,2) .
	 	 " </td> <td >" . number_format($calls, 0)." </td>
	   <td >". number_format($cdr_cost, 3)."</td>
	   <td >". number_format($egress_cost, 3)."</td>
	   <td >". number_format($cdr_cost-$egress_cost, 3)."</td>
	   <td >". number_format($percent*100, 3)."%</td>
	   <td >
	  	<div class='bar'><div style='font-size:1.2em;width:  ".number_format($percent*100, 2)." %;'> ".number_format($percent*100, 2)." %&nbsp;</div></div></td>
	   </tr>";
		if($i>0){
			$field_name=$group_by_field_arr[0];
			$curr_field=$client_org[$i][0][$field_name];//上一个分组值
			$pre_field=$client_org[$i-1][0][$field_name];//当前分组值
 	 	}
		if($curr_field!=$pre_field){
	 	if(!empty($show_subtotals)){
				$sub_tr="	<tr class='subtotals row-2'   style='color: #4B9100'>$for_td1 $for_td2  $for_td3  $for_td4  $for_td5 $for_td6  $groupby_subth
			    <td >".number_format($sub_total_call_duration,0) .
			 	 " </td> <td >".number_format($sub_total_calls, 0)." </td>
			   <td >". number_format($sub_total_cdr_cost, 3)."</td>
			   <td >". number_format($sub_total_egress_cost, 3)."</td>
			    <td >". number_format($sub_total_cdr_cost-$sub_total_egress_cost, 3)."</td>
			   <td >". number_format($sub_percent*100, 2)."%</td>
			    <td >
			  <div class='bar'><div style='font-size:1.2em;width:".number_format($sub_percent*100, 2)." %;'> ".number_format($sub_percent*100, 2)."% &nbsp;</div></div></td>
			   </tr>";
	 	}
	 	
	 	 
	 		echo  $sub_tr;
	 		$sub_total_cdr_cost=0;
			$sub_total_call_duration=0;
			$sub_total_calls=0;
	 		$sub_total_egress_cost=0;
	 		$sub_percent=0.00;
	   echo  $table_footer;
	   $head_div=" <div class='group-title'  >".  __($field_name,true).": <span>".$curr_field."</span><br></div>";
	   echo $head_div,$table_head;
	   echo $for_tr;
	  	if(!empty($show_subtotals)){
	  		$sub_total_cdr_cost=$sub_total_cdr_cost+$client_org[$i][0]['total_cdr_cost'];
				$sub_total_call_duration=$sub_total_call_duration+$client_org[$i][0]['total_duration'];
				$sub_total_calls=$sub_total_calls+$client_org[$i][0]['total_calls'];
	 			$sub_total_egress_cost=$sub_total_egress_cost+$client_org[$i][0]['total_egress_cost'];
			 	$sub_percent=0.00;
	 		}else{
	 			$sub_percent=($sub_total_cdr_cost-$sub_total_egress_cost)/$sub_total_cdr_cost;
		   }
	}else{
   	 	//如果当前和上一个相等就统计一次
   	 	 	if(!empty($show_subtotals)){
   	 	 	  $sub_total_cdr_cost=$sub_total_cdr_cost+$client_org[$i][0]['total_cdr_cost'];
	$sub_total_call_duration=$sub_total_call_duration+$client_org[$i][0]['total_duration'];
	$sub_total_calls=$sub_total_calls+$client_org[$i][0]['total_calls'];
 	$sub_total_egress_cost=$sub_total_egress_cost+$client_org[$i][0]['total_egress_cost'];
 	
   	 	 	    if(empty($sub_total_cdr_cost)||$sub_total_cdr_cost=='0'){
 	$sub_percent=0.00;
 }else{
 	$sub_percent=($sub_total_cdr_cost-$sub_total_egress_cost)/$sub_total_cdr_cost;

 }
   	 	 	}
 echo $for_tr;
  	 }
      	 	 if($i==$size-1){
   	 	  	if(!empty($show_subtotals)){
	$sub_tr="	<tr class='subtotals row-2'   style='color: #4B9100'>$for_td1 $for_td2  $for_td3  $for_td4  $for_td5 $for_td6  $groupby_subth
    <td >".number_format($sub_total_call_duration,2) .
 	 " </td> <td >".number_format($sub_total_calls, 0)." </td>
   <td >". number_format($sub_total_cdr_cost, 2)."</td>
   <td >". number_format($sub_total_egress_cost, 2)."</td>
    <td >". number_format($sub_total_cdr_cost-$sub_total_egress_cost, 2)."</td>
   <td >". number_format($sub_percent*100, 2)."%</td>
    <td >
 <div class='bar'><div style='font-size:1.2em;width:".number_format($sub_percent*100, 2)."%;'> ".number_format($sub_percent*100, 2)."% &nbsp;</div></div></td>
   
   </tr>";
 	}
   	 	  	echo  "\"$sub_tr\"";
   	 	  	            
   	 	 	 echo  $table_footer;
   	 	 }
   }
}?>
<?php if(!empty($show_comm)){?>
  <!-- ***************************普通输出*********************** -->
<table class="list">
<thead>
<tr>
 <?php
 //输出分组的字段
 if(!empty($group_by_field_arr)){
 	$c=count($group_by_field_arr);

 	for ($i=0;$i<$c;$i++){
 		if($group_by_field_arr[$i]=='term_country'){
 			continue;
 		}
 		echo " <td rel='8'  width='6%'>&nbsp;&nbsp;&nbsp;&nbsp; ".$appCommon->show_order($group_by_field_arr[$i],__($group_by_field_arr[$i],true))."  &nbsp;&nbsp;</td>";
 	}
 	
 }?>
 	<td  rel="0" >
 
    <?php echo  $appCommon->show_order('term_country','Term Country');?>

 	</td>
     <td  rel="0" >
          <?php echo $appCommon->show_order('total_duration',__('Duration',true))?>
     </td>
     <td  rel="0" > <?php echo $appCommon->show_order('total_calls',__('ofcdrs',true)); ?> </td>
     <td  rel="0" ><?php echo $appCommon->show_order('total_cdr_cost',__('callcharge',true));echo $appCommon->show_sys_curr(); ?> </td>
     <td  rel="1" ><?php echo $appCommon->show_order('total_egress_cost',__('callcost',true)); echo $appCommon->show_sys_curr(); ?></td>
        <td  rel="2" class="cset-1">&nbsp; <?php echo $appCommon->show_order('profit',__('profit',true)); echo $appCommon->show_sys_curr(); ?>&nbsp;</td>
     <td  rel="2" class="cset-1">&nbsp; <?php echo $appCommon->show_order('profit_percentage',__('profit',true)); ?>%&nbsp;</td>
     <td rel="2" class="cset-1">&nbsp; %&nbsp;</td>
</tr>
 </thead>

  <?php 
    $size=count($client_org);
    for ($i=0;$i<$size;$i++){
    ?>
    <tbody>
  <tr >
 <?php
	 $cdr_cost=$client_org[$i][0]['total_cdr_cost'];
	 $egress_cost=$client_org[$i][0]['total_egress_cost'];
	 if(empty($cdr_cost)||$cdr_cost=='0'){
	 		$percent=0.00;
	 }else{
	 	 		if(empty($cdr_cost)){
	 			$percent=0.00000;
	 		}else{
	 	//			$percent=($cdr_cost-$egress_cost)/$cdr_cost;
	 			
	 		}
	 	

	 		
	  }
	  
 //输出分组的字段
 if(!empty($group_by_field_arr)){
 	$c=count($group_by_field_arr);
 	for ($ii=0;$ii<$c;$ii++){
 		$f=$group_by_field_arr[$ii];
 	 		if($f=='term_country'){
 			continue;
 		}
 $field=$client_org[$i][0][$f];
 if(trim($field)==''){
		 echo "<td    style='text-align:center;color:#6694E3;'><strong  style='color:red;'>".__('Unknown',true)."</td>";
 }else{
 		 echo " <td    style='text-align:center;color:#6694E3;'>".$field ."</td>";}
  }
  
  
 }?>
		<td ><?php  echo  $client_org[$i][0]['term_country'];?> </td>
   <td ><?php  echo  number_format($client_org[$i][0]['total_duration'],2); ?> </td>
   <td ><?php  echo  $client_org[$i][0]['total_calls']; ?> </td>
   <td ><?php  echo  number_format($appCommon->currency_rate_conversion($cdr_cost),5); ?></td>
 		<td ><?php  echo  number_format($appCommon->currency_rate_conversion($egress_cost),5); ?></td>
 		<td ><?php  echo number_format($appCommon->currency_rate_conversion($client_org[$i][0]['profit']),5); ?></td>
 		<td ><?php  echo  number_format($client_org[$i][0]['profit_percentage'],2); ?>%</td>
  	<td >
  <div class="bar"><div style="font-size:1.2em;width: <?php  echo  number_format($client_org[$i][0]['profit_percentage'],2); ?>%;">
 <?php  echo  number_format($client_org[$i][0]['profit_percentage'],2); ?>%&nbsp;</div></div>
  
  </td>
    </tr>  <?php }?>
            </tbody>
            
  <tbody  class="term-calls">
      <tr style="color: rgb(75, 145, 0);">
   <td   colspan="<?php  if(isset($c)){echo $c+1;}else{echo '1';}  ?>"    ><?php echo __('Total',true);?>:</td>
   <td ><?php  echo  number_format($sum_list[0][0]['total_duration'],2); ?> </td>
   <td ><?php  echo  $sum_list[0][0]['total_calls']; ?> </td>
   <td ><?php  echo  number_format($appCommon->currency_rate_conversion($sum_list[0][0]['total_cdr_cost']),5); ?></td>
   <td > <?php  echo  number_format($appCommon->currency_rate_conversion($sum_list[0][0]['total_egress_cost']),5); ?></td>
 		<td > <?php  echo number_format($appCommon->currency_rate_conversion($sum_list[0][0]['profit']),5); ?> </td>
 		<td >  <?php  echo  number_format($sum_list[0][0]['profit_percentage'],2); ?>% </td>
 		<td > 
 		
 		<div class="bar"><div style="font-size:1.2em;width: <?php  echo  number_format($sum_list[0][0]['profit_percentage'],2); ?>%;">
 <?php  echo  $sum_list[0][0]['profit_percentage']; ?>%&nbsp;</div></div>
 		 </td>
  </tr>
            </tbody>
            
            
 </table>
      <?php }}?>
