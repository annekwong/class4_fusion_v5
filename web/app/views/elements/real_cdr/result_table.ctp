<?php if (isset ( $exception_msg ) && $exception_msg) :	?>
		<?php	echo $this->element ( 'common/exception_msg' );?>		
	<?php endif; ?>	
<?php 
 $size=count($client_org);
 $flag='false';
 if($size>0){
 		$cdr_size=$client_org[0][0]['cdr_size'];
 	}
	if($size==0){$flag='true';}
	if($size==1&&$cdr_size==0){
		$flag='true';
	}
	if($flag=='true'){
?>
<div class="msg"><?php echo __('no_data_found',true);?></div>
<?php }else{?>
<?php echo $this->element('report/real_period')?>
      
      <!-- ***************************************************************************************************************************************************** -->
  <!-- ****************************************subgroups******************************************* -->
        <!-- ***************************************************************************************************************************************************** -->
<?php if(!empty($show_subgroups)){
	$pre_field='' ;
	$curr_field='';
	$next_field='';
	$field_name=$group_by_field_arr[0];
	$curr_field=$client_org[0][0][$field_name];
	$pre_field=$curr_field;

	//分组字段头
$groupby_head='';
$total_cdr=0;
$total_duration=0;
$c=count($group_by_field_arr);
 	for ($ii=0;$ii<$c;$ii++){
 		$field_name=$group_by_field_arr[$ii];
	 $curr_field=$client_org[0][0][$field_name];
	 if(empty($curr_field)||$curr_field==''){
	 		$groupby_head=$groupby_head.  __($field_name,true).": <span>".__('Unknown',true)."</span><br>";
	 }else{
	 	 	$groupby_head=$groupby_head.  __($field_name,true).": <span>".$curr_field."</span><br>";
	 }
 	}
 	
 $head_div=" <div class='group-title'>".$groupby_head."</div>";
	$table_head="<table class='list nowrap with-fields'  ><thead><tr>
	              <td width='10%' rel='0' >&nbsp; ".__('RateTable',true)."&nbsp;</td>
	             <td width='10%' rel='1' >Cdr count</td>
	             <td width='10%' rel='2' class='cset-1'>&nbsp;%  &nbsp;</td>
	             <td width='30%' rel='1' ></td>
	                       <td width='10%' rel='1' >Call Duration(min)</td>
	             <td width='10%' rel='2' class='cset-1'>&nbsp;%  &nbsp;</td>
	             <td width='30%' rel='1' ></td>
	            </tr></thead><tbody class='orig-calls'>" ;
	$for_tr='';//循环输出的tr
	$sub_tr='';//子统计的tr
 $table_footer="</tbody></table>";
 
 
 //输出头
 echo $head_div,$table_head;
 

   $size=count($client_org);
   
   
   //计算断开码百分比
   $total_cdr_array=array();
   $total_duration_array=array();
   for($k=0;$k<$size;$k++){
    $cdr_size=(empty($client_org[$k][0]['cdr_size']))?0:($client_org[$k][0]['cdr_size']);
     $duration=(empty($client_org[$k][0]['call_duration']))?0:($client_org[$k][0]['call_duration']);
   if($k>0){
	        //取最后1个分组条件
	     $field_name=$group_by_field_arr[0];
	     $curr_field=$client_org[$k][0][$field_name];//上一个分组值
	     $pre_field=$client_org[$k-1][0][$field_name];//当前分组值
 	 }else{
 	 	   $total_disconnect=0;
 	 	    $total_duration=0;
 	 	   $field_name=$group_by_field_arr[0];
 	     $curr_field=$client_org[0][0][$field_name];//上一个分组值
	    $pre_field=$curr_field;//当前分组值
 	 }
 
 	if($curr_field==$pre_field){
 		$total_cdr=$total_cdr+$client_org[$k][0]['cdr_size'];
 			$total_duration=$total_duration+$client_org[$k][0]['call_duration'];
 	}else{
 		$total_cdr_array[$k-1]=$total_cdr;
 		$total_cdr=$client_org[$k][0]['cdr_size'];
 		
 		$total_duration_array[$k-1]=$total_duration;
 		$total_duration=$client_org[$k][0]['call_duration'];
 	}
 	
   }
  
    //循环输出表格
   for ($i=0;$i<$size;$i++){
   	$groupby_head='';
   	//生成分组字段td
    	for ($ii=0;$ii<$c;$ii++){
 		     $field_name=$group_by_field_arr[$ii];
	      $curr_field=$client_org[$i][0][$field_name];
	      $curr_field=(empty($curr_field)||$curr_field=='')?(__('Unknown',true)):$curr_field;
	      $groupby_head=$groupby_head.  __($field_name,true).": <span>".$curr_field."</span><br>";
    	}
    	
  $cdr_size=$client_org[$i][0]['cdr_size'];
  $cdr_size=(empty($cdr_size)||$cdr_size=='0')?0:$client_org[$i][0]['cdr_size'];
   
    $duration=$client_org[$i][0]['call_duration'];
  $duration=(empty($duration)||$duration=='0')?0:$client_org[$i][0]['call_duration'];
   //找不到就继续向后找
   if(!isset($total_cdr_array[$i])){
   	for($t=$i;$t<$size;$t++){
   		if(!empty($total_cdr_array[$t])){
   			 $total_cdr=	$total_cdr_array[$t];
   			 break;
   		}
   		
   	}
   }else{
   
   	$total_cdr=	$total_cdr_array[$i];
   }
   
    $per_dis=($total_cdr=='0')?0:($cdr_size/$total_cdr*100);
      //找不到就继续向后找 通话时间
   if(!isset($total_call_duration_array[$i])){
   	for($t=$i;$t<$size;$t++){
   		if(!empty($total_call_duration_array[$t])){
   			 $total_duration=	$total_call_duration_array[$t];
   			 break;
   		}
   		
   	}
   }else{
   
   	$total_duration=	$total_call_duration_array[$i];
   }
  
   $per_duration=($total_duration=='0')?0:($duration/$total_duration*100);
 	//循环的表格
 	$for_tr="	<tr class=' row-2'   style='color: #4B9100'>
   <td class='in-decimal'><strong>".$client_org[$i][0]['rate_table_name'] ." </strong></td> 
   <td class='in-decimal'><strong>" .number_format($cdr_size, 0)." </strong></td>
   <td class='in-decimal'>".number_format($per_dis, 2)."%</td>
    <td class='in-decimal'>
  <div class='bar'><div style='font-size:0.9em;width:".number_format($per_dis, 2)."%;'> ".number_format($per_dis, 2)." %&nbsp;</div></div></td>
   <td class='in-decimal'><strong>" .number_format($duration/60, 0)." </strong></td>
   <td class='in-decimal'>".number_format($per_duration, 2)."%</td>
    <td class='in-decimal'>
  <div class='bar'><div style='font-size:0.9em;width:".number_format($per_duration, 2)."%;'> ".number_format($per_duration, 2)." %&nbsp;</div></div></td>
  </tr>";
if($i>0){
	//取最后1个分组条件
	$field_name=$group_by_field_arr[0];
	$curr_field=$client_org[$i][0][$field_name];//上一个分组值
	$pre_field=$client_org[$i-1][0][$field_name];//当前分组值
 	 }else{
 	 
 	 	$field_name=$group_by_field_arr[0];
 	 $curr_field=$client_org[0][0][$field_name];//上一个分组值
	$pre_field=$curr_field;//当前分组值
 	 }
   	 
if($curr_field!=$pre_field){
   	 	 	  	//只要当前和前一个不相等  就生成子统计tr
   	  echo  $table_footer;
   	   $head_div=" <div class='group-title'>".$groupby_head."</div>";
   	   echo $head_div,$table_head;
   	 	 echo $for_tr;

   	 }else{
   	 	//如果当前和上一个相等就统计一次
 echo $for_tr;
  	 }
      	 	 if($i==$size-1){
   	 	 	 echo  $table_footer;
   	 	 }
   }
}?>
<?php if(!empty($show_comm)){?>
      <!-- ***************************************************************************************************************************************************** -->
  <!-- ****************************************普通输出******************************************* -->
        <!-- ***************************************************************************************************************************************************** -->
<table class="list nowrap with-fields"  style="width: 100%">
<thead>
<tr>
 <td width="10%" rel="0" >&nbsp;  &nbsp;</td>
 <?php
 //输出分组的字段
$c=0;
 if(!empty($group_by_field_arr)){
 	$c=count($group_by_field_arr);

 	for ($i=0;$i<$c;$i++){
 		if($group_by_field_arr[$i]=='term_country'){
 			continue;
 		}
 		echo " <td rel='8'  width='6%'>&nbsp;&nbsp;&nbsp;&nbsp; ".$appCommon->show_order($group_by_field_arr[$i],__($group_by_field_arr[$i],true))."  &nbsp;&nbsp;</td>";
 	}
 	
 }?>
  <td width="10%" rel="0" >&nbsp;<?php echo $appCommon->show_order('cdr_size',__('Cdr Count',true)); ?>  &nbsp;</td>
 	<td width="10%" rel="2" class="cset-1"><?php echo $appCommon->show_order('call_count_percentage',__('Cdr Count Percentage',true)); ?> &nbsp;</td>
	<td width="30%" rel="2" class="cset-1">&nbsp; &nbsp;</td>
	<td width="10%" rel="0" >&nbsp;<?php echo $appCommon->show_order('call_duration',__('duration',true)); ?> &nbsp;</td>
	<td width="10%" rel="2" class="cset-1">&nbsp; <?php echo $appCommon->show_order('call_duration_percentage',__('duration',true)); ?>%&nbsp;</td>
	<td width="30%" rel="2" class="cset-1">&nbsp; &nbsp;</td>
  </tr>
    </thead>
 <tbody>
<?php  
$size=count($client_org);
$total_cdr=0;
$total_call_duration=0;
if($size>0){
	for ($i=0;$i<$size;$i++){
		$total_cdr=$total_cdr+$client_org[$i][0]['cdr_size'];
			$total_call_duration=$total_call_duration+$client_org[$i][0]['call_duration'];
	}
}
	for ($i=0;$i<$size;$i++){
     	$total_per=0;
     		$total_per1=0;
     if($total_cdr=='0'){echo $total_per;}else{$total_per=$client_org[$i][0]['cdr_size']/$total_cdr;} 
     if($total_call_duration=='0'){echo $total_per1;}else{$total_per1=$client_org[$i][0]['call_duration']/$total_call_duration;} 
     	?>
  <tr>
     <td class="in-decimal"></td>
          <?php
 //输出分组的字段
if(!empty($group_by_field_arr)){
 	$c=count($group_by_field_arr);
 	for ($ii=0;$ii<$c;$ii++){
 		$f=$group_by_field_arr[$ii];
	 $field=$client_org[$i][0][$f];
	 if(trim($field)==''){
			 echo "<td  class='in-decimal'  style='text-align:center;color:#6694E3;'><strong  style='color:red;'>".__('Unknown',true)."</strong></td>";
	 }else{
	 		 echo " <td  class='in-decimal'  style='text-align:center;color:#6694E3;'>".$field ."</td>";}
	  }
 }?>
   <td class="in-decimal">
   	<a href="<?php echo $this->webroot?>clientsummarystatis/summary_reports?<?php echo (''==$this->params['getUrl'])?"" :($this->params['getUrl']."&code=") ?>"><strong><?php  echo  number_format($client_org[$i][0]['cdr_size'], 0); ?> </strong></a>
   </td>
 		<td class="in-decimal"><?php echo number_format($total_per*100,2) ?>%</td>
		<td class="in-decimal">
			<div class="bar">
				<div style="font-size:1.2em;width: <?php echo number_format($total_per*100,2) ?>%;">
					<?php echo number_format($total_per*100,2) ?>%&nbsp;
				</div>
			</div>
		</td>
   <td class="in-decimal"><strong><?php  echo  number_format($client_org[$i][0]['call_duration'], 2); ?> </strong></td>
		<td class="in-decimal"><?php echo number_format($total_per1*100,2) ?>%</td>
		<td class="in-decimal">
			<div class="bar">
				<div style="font-size:1.2em;width: <?php echo number_format($total_per1*100,2) ?>%;"><?php echo number_format($total_per1*100,2) ?>%&nbsp;</div>
			</div>
		</td>
  </tr>
<?php }?>
  <tr class="totals row-1 row-2">
  	<td colspan="<?php  echo $c+1;  ?>" class="in-decimal"><b><?php echo __('Total',true);?>:</b></td>
		<td class="in-decimal"><a href="<?php echo $this->webroot?>cdrreports/summary_reports?<?php echo $this->params['getUrl']?>"><strong><?php  echo  number_format($sum_data[0][0]['cdr_size'], 0); ?> </strong></a></td>
		<td class="in-decimal"><?php echo number_format($sum_data[0][0]['call_count_percentage']*100,2) ?>%</td>
		<td class="in-decimal">
			<div class="bar"><div style="font-size:1.2em;width: <?php echo number_format($sum_data[0][0]['call_count_percentage']*100,2) ?>%;"><?php echo number_format($sum_data[0][0]['call_count_percentage']*100,2) ?>%&nbsp;</div></div>
		</td>
   <td class="in-decimal"><strong><?php  echo  number_format($sum_data[0][0]['call_duration'], 2); ?> </strong></td>
		<td class="in-decimal"><?php echo number_format($sum_data[0][0]['call_duration_percentage']*100,2) ?>%</td>
		<td class="in-decimal">
			<div class="bar"><div style="font-size:1.2em;width: <?php echo number_format($sum_data[0][0]['call_duration_percentage']*100,2) ?>%;"><?php echo number_format($sum_data[0][0]['call_duration_percentage']*100,2) ?>%&nbsp;</div></div>
		</td>
	</tr>
 </tbody>
 </table>
<?php }}?>