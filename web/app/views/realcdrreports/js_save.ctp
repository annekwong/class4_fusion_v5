<?php if (isset ( $exception_msg ) && $exception_msg) :	?>
	<?php	echo $this->element ( 'common/exception_msg' );?>		
<?php endif; ?>
<?php 
$mydata =$p->getDataArray();	$loop = count($mydata);

if($loop==0){?>
<div class="msg"><?php echo __('no_data_found',true);?></div>
<?php }else{?>
<ul id="stats-extra"  style="font-weight: bolder;font-size: 1.1em;color: #6694E3">
  <li id="stats-time"><?php __('QueryTime')?>: <?php echo $quey_time?> ms</li></ul>
      <!-- ***************************************************************************************************************************************************** -->
  		<!-- ****************************************普通输出******************************************* -->
        <!-- ***************************************************************************************************************************************************** -->
         <div id="toppage"><?php echo $this->element('page');?></div>
<table class="list nowrap with-fields"  style="width: 100%">
<thead>
<tr>
<?php 
 	$c=count($show_field_array);
 	for ($ii=0;$ii<$c;$ii++){
 		echo  "<td rel='8'  width='10%'>&nbsp;&nbsp;&nbsp;&nbsp; ".__($cdr_field[$show_field_array[$ii]],true)."  &nbsp;&nbsp;</td>";

 	}
     
?>
<td style="padding:0px 25px;">&nbsp;&nbsp;</td>

  </tr>
    </thead>
 <tbody class="orig-calls">
		<?php 	 for ($i=0;$i<$loop;$i++) {?>
  			<tr class=" row-2"   style="color: #4B9100">
                            
         <?php 
   
      
for ($ii=0;$ii<$c;$ii++){
    $f=$cdr_field[$show_field_array[$ii]];
    $field=$mydata[$i][0][$f];
    if(trim($field)==''){
        echo  "<td  class='in-decimal'  style='text-align:center;color:#6694E3;'><strong  style='color:red;'>".__('Unknown',true)."</strong></td>";
    } else{	
        echo  " <td  class='in-decimal'  style='text-align:center;color:#6694E3;'>".$field ."</td>";
    }
}

	         ?>
                            <td style="width:60px;">
                            <input class="input in-submit" type="button" onclick="window.open('<?php echo $this->webroot?>realcdrreports/breakone/<?php echo $mydata[$i][0]['uuid_a']; ?>');jQuery(this).remove();" value="Kill" style="width:40px; font-size:13px;"/>
                          </td>         
                            
	    		</tr>
   <?php }?>
 </tbody>
 </table>
   <div id="tmppage">
			<?php echo $this->element('page');?>
		</div>
   <?php }?>
   
   