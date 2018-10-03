<!--导入所有reoprt页面的input和select样式文件-->
<?php echo $this->element('magic_css');?>
<div id="title"> 

	<h1> <?php __('Statistics')?>&gt;&gt;<?php echo __('Active Call Report',true);?> </h1>
	<ul id="title-menu">
    		<li>
    				<?php if(isset($this->params['url']['type']) && $this->params['url']['type']=='egress_report'){?>
    				<?php echo $this->element("xback",Array('backUrl'=>'gatewaygroups/egress_report'))?>
    				<?php }else{?>
	    			<font class="fwhite"><?php echo __('Refresh Every',true);?>:</font>
      <select id="changetime">
        <option value="180">3 <?php __('minutes')?></option>
        <option value="300">5 <?php __('minutes')?></option>
        <option value="800">15 <?php __('minutes')?></option>
      </select>
	    			<?php }?>
    		</li>
  		</ul>
	</div>
<div id="container">

<div  id="refresh_div">
<?php if (isset ( $exception_msg ) && $exception_msg) :	?>
	<?php	echo $this->element ( 'common/exception_msg' );?>		
<?php endif; ?>

<?php if($show_nodata): ?>

<?php 
$mydata =$data;	$loop = count($mydata);

if($loop==0){?>
<div class="msg"><?php echo __('no_data_found',true);?></div>
<?php }else{?>
<ul id="stats-extra"  style="font-weight: bolder;font-size: 1.1em;color: #6694E3">
  <li id="stats-time"><?php __('QueryTime')?>: <?php echo $quey_time?> <?php __('ms')?></li></ul>
      <!-- ***************************************************************************************************************************************************** -->
  		<!-- ****************************************普通输出******************************************* -->
        <!-- ***************************************************************************************************************************************************** -->
<!--        <div id="toppage"></div>-->
<table class="list nowrap with-fields"  style="width: 100%">
<thead>
<tr>
<?php 
 	$c=count($show_field_array);
 	for ($ii=0;$ii<$c;$ii++){
 		
 		echo  "<td rel='8'  width='10%'>&nbsp;&nbsp;&nbsp;&nbsp; ".__($cdr_field[$show_field_array[$ii]],true);	
 		if($cdr_field[$show_field_array[$ii]] == 'duration') {
 			echo $appCommon->show_order('duration',__(' ',true));
 		} 
 		if($cdr_field[$show_field_array[$ii]] == 'ans_time_b') {
 			echo $appCommon->show_order('ans_time_b',__(' ',true));
 		}
 		if($cdr_field[$show_field_array[$ii]] == 'a_rate') {
 			echo $appCommon->show_order('a_rate',__(' ',true));
 		}
 		if($cdr_field[$show_field_array[$ii]] == 'b_rate') {
 			echo $appCommon->show_order('b_rate',__(' ',true));
 		}		
		echo "  &nbsp;&nbsp;</td>";
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
                            <a class="input in-submit" href="<?php echo $this->webroot?>realcdrreports/breakone/<?php echo $mydata[$i][0]['uuid_a']; ?>"><?php __('Kill')?></a>
                          </td>         
                            
	    		</tr>
   <?php }?>
 </tbody>
 </table>
   <!--<div id="tmppage">
			<?php //echo $this->element('page_other');?>
		</div>-->
   <?php }?>
   
   <?php endif; ?>
   </div>
   <!--生成图片报表-->
<?php //echo $this->element("report/image_report")?>
<!--//生成图片报表-->
 <?php //***********************报表查询参数*********************?>
<?php echo  $this->element('real_cdr/search')?>



        

<?php echo $this->element('search_report/search_js_show');?>

</div>
<div>


	<script type="text/javascript">


function  update_report(){

	$.ajax({
		url:'<?php echo  $this->webroot?>'+'realcdrreports/js_save/',
		type:'GET',
		success:function(html){
                    $('#refresh_div').html(html);
                    jGrowl_to_notyfy('Report Has been refreshed',{theme:'jmsg-success'});
                },
                 error:function(){
		
                }
	});

	
}


</script>

	<script type="text/javascript" src="<?php echo $this->webroot?>js/My97DatePicker/WdatePicker.js"></script>
	</div>

<script type="text/javascript">
$(function() {
    var interv = null;

    $('#changetime').change(function() {
        if(interv) 
            window.clearInterval(interv);
        var time = $(this).val() * 1000;
        interv = window.setInterval("loading();window.location.reload()", time); 
    });

    $('#changetime').change();

});
</script>