<?php $mydata =$p->getDataArray();	$loop = count($mydata);?>
<table class="list nowrap with-fields"  style="width: 100%">
<thead>
<?php 
 	$c=count($show_field_array);
?>

    <tr>
    		<td><?php echo $appCommon->show_order('time',__('Period',true));?></td>
    		<td><?php echo $appCommon->show_order('origination_source_number',__('origination_source_number',true));?></td>
    		<td><?php echo $appCommon->show_order('origination_destination_number',__('origination_destination_number',true));?></td>
    		<td><?php echo $appCommon->show_order('origination_source_host_name ',__('origination_source_host_name',true));?></td>
    		<td><?php echo $appCommon->show_order('ingress_client_cost',__('ingress_client_cost',true));?></td>
    	</tr>
    </thead>
 <tbody >
		<?php 	 for ($i=0;$i<$loop;$i++) {	?>
  <tr style="color: #4B9100">
		 <?php 
		 for ($ii=0;$ii<$c;$ii++){
		 		$f=$show_field_array[$ii];
		 		$field=$mydata[$i][0][$f];
		 		if(trim($field)==''){
						echo  "<td  class='in-decimal'  style='text-align:center;color:#6694E3;'><strong  style='color:red;'>".__('Unknown',true)."</strong></td>";
		 		}else{	
		 				echo  " <td  class='in-decimal'  style='text-align:center;color:#6694E3;'>".$field."</td>";
		 		}
		   }
		     ?>
  </tr>

   <?php }?>
 </tbody>
 </table>