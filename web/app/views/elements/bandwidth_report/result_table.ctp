<?php 
 $size=count($client_org);
 $no_data=false;
if($size==0){
	$no_data=true;
}
if($size==1){
	if(empty($client_org[0][0]['calls'])){
		$no_data=true;
	}
}
	if($no_data){
	?>
        <?php if($show_nodata): ?>
<div class="msg"><?php echo __('no_data_found',true);?></div> <?php endif; ?>
<?php }else{?>
<table class="list nowrap with-fields"  style="width: 100%">
<thead>
<tr>
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
 <td width="10%" rel="0" >&nbsp;<?php echo $appCommon->show_order('calls',__('Calls',true))?>&nbsp;</td>
  <td width="10%" rel="2" class="cset-1">&nbsp;<?php echo $appCommon->show_order('incoming_bandwidth',__('Incoming Bandwidth',true))?> &nbsp;</td>
  <td width="10%" rel="0" >&nbsp;<?php echo $appCommon->show_order('outgoing_bandwidth',__('Outgoing Bandwidth',true))?> &nbsp;</td>


  </tr>
</thead>
 <tbody >
<?php  
$size=count($client_org);
     	$total_calls=0;
      $total_incoming_bandwidth=0; 
     	$total_outgoing_bandwidth=0;   
     for ($i=0;$i<$size;$i++){
     	$calls=$client_org[$i][0]['calls'];
      $incoming_bandwidth=$client_org[$i][0]['incoming_bandwidth']; 
     	$outgoing_bandwidth=$client_org[$i][0]['outgoing_bandwidth']; 
     	$total_calls=$total_calls+$calls;
      $total_incoming_bandwidth=$total_incoming_bandwidth+$incoming_bandwidth; 
     	$total_outgoing_bandwidth=$total_outgoing_bandwidth+$outgoing_bandwidth;  
     	?>
  <tr class=" row-<?php echo $i%2+1?>"   style="color: #4B9100">
   <?php
 //输出分组的字段
 if(!empty($group_by_field_arr)){
 	$c=count($group_by_field_arr);
 
 	for ($ii=0;$ii<$c;$ii++){
 		$f=$group_by_field_arr[$ii];

 $field=$client_org[$i][0][$f];
 if(trim($field)==''){
		 echo "<td  class='in-decimal'  style='text-align:center;color:#6694E3;'><strong  style='color:red;'>".__('Unknown',true)."</td>";
 }else{
 		 echo " <td  class='in-decimal'  style='text-align:center;color:#6694E3;'>".$field ."</td>";}
  }
  
  
 }?>
  <td class="in-decimal"><?php  echo  $calls; ?> </td>
   <td class="in-decimal"><?php  echo  strtoupper($incoming_bandwidth);?></td>
   <td class="in-decimal"><?php  echo  strtoupper($outgoing_bandwidth);?></td>
    </tr>
    <?php }?>
    <?php if(!empty($c)){?>
    <tr>
  	<td colspan="<?php  echo $c;  ?>" class="in-decimal"><?php echo __('Total',true);?>:</td>
   <td class="in-decimal"><?php  echo  $total_calls; ?> </td>
   <td class="in-decimal"><?php  echo  strtoupper($total_incoming_bandwidth);?></td>
   <td class="in-decimal"><?php  echo  strtoupper($total_outgoing_bandwidth);?></td>
    </tr>
            <?php }?>
 </tbody>
 </table>
 <?php }?>
