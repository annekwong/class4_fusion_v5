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
$total_profit=0;
if($size>0){
	for ($i=0;$i<$size;$i++){
		$total_cdr=$total_cdr+$client_org[$i][0]['cdr_size'];
			$total_call_duration=$total_call_duration+$client_org[$i][0]['call_duration'];
		$total_profit=$total_profit+$client_org[$i][0]['call_duration'];
	}
}
	for ($i=0;$i<$size;$i++){
     	$total_per=0;
     		$total_per1=0;
     if($total_cdr=='0'){
     	echo $total_per;
     }else{
     	$total_per=$client_org[$i][0]['cdr_size']/$total_cdr;} 
     if($total_call_duration=='0'){
     	echo $total_per1;
     }else{
     	$total_per1=$client_org[$i][0]['call_duration']/$total_call_duration;
     } 
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
			 echo "<td  class='in-decimal'  style='text-align:center;color:#6694E3;'>".__('Unknown',true)."</td>";
	 }else{
	 		 echo " <td  class='in-decimal'  style='text-align:center;color:#6694E3;'>".$field ."</td>";}
	  }
 }?>
   <td class="in-decimal">
   	<a href="<?php echo $this->webroot?>clientsummarystatis/summary_reports?<?php echo (''==$this->params['getUrl'])?"" :($this->params['getUrl']."&code=") ?>"><?php  echo  number_format($client_org[$i][0]['cdr_size'], 0); ?> </a>
   </td>
 		<td class="in-decimal"><?php echo number_format($total_per*100,2) ?>%</td>
		<td class="in-decimal">
			<div class="bar">
				<div style="font-size:1.2em;width: <?php echo number_format($total_per*100,2) ?>%;">
					<?php echo number_format($total_per*100,2) ?>%&nbsp;
				</div>
			</div>
		</td>
   <td class="in-decimal"><?php  echo  number_format($client_org[$i][0]['call_duration'], 2); ?> </td>
		<td class="in-decimal"><?php echo number_format($total_per1*100,2) ?>%</td>
		<td class="in-decimal">
			<div class="bar">
				<div style="font-size:1.2em;width: <?php echo number_format($total_per1*100,2) ?>%;"><?php echo number_format($total_per1*100,2) ?>%&nbsp;</div>
			</div>
		</td>
  </tr>
<?php }?>
  <tr class="totals row-1 row-2">
  	<td colspan="<?php  echo $c+1;  ?>" class="in-decimal"><?php echo __('Total',true);?>:</td>
		<td class="in-decimal"><a href="<?php echo $this->webroot?>cdrreports/summary_reports?<?php echo $this->params['getUrl']?>"><?php  echo  number_format($sum_data[0][0]['cdr_size'], 0); ?> </a></td>
		<td class="in-decimal"><?php echo number_format($sum_data[0][0]['call_count_percentage']*100,2) ?>%</td>
		<td class="in-decimal">
			<div class="bar"><div style="font-size:1.2em;width: <?php echo number_format($sum_data[0][0]['call_count_percentage']*100,2) ?>%;"><?php echo number_format($sum_data[0][0]['call_count_percentage']*100,2) ?>%&nbsp;</div></div>
		</td>
   <td class="in-decimal"><?php  echo  number_format($sum_data[0][0]['call_duration'], 2); ?> </td>
		<td class="in-decimal"><?php echo number_format($sum_data[0][0]['call_duration_percentage']*100,2) ?>%</td>
		<td class="in-decimal">
			<div class="bar"><div style="font-size:1.2em;width: <?php echo number_format($sum_data[0][0]['call_duration_percentage']*100,2) ?>%;"><?php echo number_format($sum_data[0][0]['call_duration_percentage']*100,2) ?>%&nbsp;</div></div>
		</td>
	</tr>
 </tbody>
 </table>