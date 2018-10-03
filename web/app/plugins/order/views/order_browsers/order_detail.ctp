<style type="text/css">
.query_ul li span.left_name{width:130px;}
.query_table tbody td.td_left{width:20%; border:none; border-bottom:1px dashed #ccc;}
.query_table tbody td.td_right{width:10%; border:none; border-bottom:1px dashed #ccc;}
</style>
<div id="title" style="text-align: justify;">
  <ul style="padding-top:10px;display: inline;  z-index:1;list-style-type:none">
    <h1>
      <?php if($this->params['action']=='buy')echo 'Sell' ;else echo 'Buy'?>
      &gt;&gt;<?php __('Order Detail')?> <?php echo Inflector::humanize($do_action)?> </h1>
  </ul>
  <ul id="title-menu">
    <li>
    <a class="link_back" href="javascript:history.go(-1);">
<img width="16" src="<?php echo $this->webroot;?>images/icon_back_white.png" heigh="16">
&nbsp; <?php echo __('Back',true);?>
</a>
   </li>
  </ul>
</div>

<div class="container" >


<script type="text/javascript">
function click_code(){
	$('#validate_qa').fadeIn('slow');
	$('#close').css({padding:'0px 10px',cursor:'pointer',width:'20px'}).click(function() {
        $('#validate_qa ').fadeOut('slow');
    });
}

jQuery(function() {
	  $('.container').css('position','static');
	  var width = document.documentElement.clientWidth;
      var height = document.documentElement.clientHeight;
      var width1 = $("#validate_qa").width();
      var height1 = $("#validate_qa").height();
      var temp_width = (width - width1)/2 + "px";
      var temp_top = (height - height1)/2 + "px";
      $("#validate_qa").css("margin-left",temp_width);
      $("#validate_qa").css("margin-top",temp_top);	
});

</script> 

<div class="container">
<div id="validate_qa" style="display:none;  height:250px;padding:0px;margin:0px; vertical-align:middle;"><h3 style="text-align:right; margin:3px;height:20px; line-height:20px;"><span id="close" style=" "><img src="<?php echo $this->webroot;?>images/delete_alert.png" height="16" /></span></h3>
    <div style="overflow:auto;">
    
		<table class="list" style="margin:0px;" >
        	<thead>
            	<tr>
                	<td><span> <b><?php echo __('Destination',true);?></b></span></td>
                    <td>
                        <span> <b><?php echo __('Code',true);?></b> </span>
                    </td>
                </tr>
            </thead>
          	<tbody>
			<tr rel="tooltip">
            	<td height="33" width="150">
				<div class="codename_area_show" style="height:auto;border:none; width:auto !important;"><dl class="codename_list codename_area_list" style="border:none; vertical-align:top;">
                <?php 
					foreach ($code_name as $k=>$v)
					{
						echo "<dd>{$v}</dd>";
						
					}
				?>
				</dl>
                	</div>
				</td>
                <td>
                	<div class="codename_area_show" style="height:auto; width:auto; border:none;"><dl class="codename_area_list" style="border:none;">
				<dd>
					 <?php 
					foreach ($code as $k=>$v)
					{
						echo "<dd>{$v}</dd>";
						
					}
				?>
				<?php //echo $order_arr['code_true'];?></dd></dl>
                	</div>
                </td>
            </tr>
          </tbody>
       </table>
   </div>
</div>       
            
<?php if ($this->params['action'] == 'buy'):?>
<ul class="query_ul query_table">
    <li><span class="left_name"><?php echo __('Order ID',true);?>:</span><span class="right_value"><?php echo $order_arr['id'];?></span> </li>
    <li><span class="left_name"><?php echo __('rate',true);?>:</span><span class="right_value">
	<?php echo number_format($order_arr['rate'],5);?></span></li>
    <li><span class="left_name"><?php echo __('G729',true);?>:</span><span class="right_value"><?php if($order_arr['g729']=='t'){echo 'Yes';}else{ echo 'No';}?></span></li>
    
    <li  class="liodd"><span class="left_name"><?php echo __('status',true);?>:</span><span class="right_value"><?php echo $order_arr['status'];?></span></li>
    <li class="liodd"><span class="left_name"><?php echo __('Country',true);?>:</span><span class="right_value"><?php echo $order_arr['country'];?></span></li>
    <li class="liodd"><span class="left_name"><?php echo __('G711',true);?>:</span><span class="right_value"><?php if($order_arr['g711']=='t'){echo 'Yes';}else{ echo 'No';}?></span></li>
    
    <li ><span class="left_name"><?php echo __('Rated Only',true);?>:</span><span class="right_value">
    <?php if($order_arr['rate_only']=='t'){echo 'Yes';}else{ echo 'No';}?></span></li>
    <li><span class="left_name"><?php echo __('Destination',true);?>:</span><span class="right_value"><a href="javascript:void(0)" title="<?php echo $order_arr['code_name_true'];?>" onclick="return click_code();" id="clickcode"><?php echo $order_arr['code_name'];?></a></span></li>
    <li ><span class="left_name"><?php echo __('G723',true);?>:</span><span class="right_value"><?php if($order_arr['G723']=='t'){echo 'Yes';}else{ echo 'No';}?></span></li>
    
    <li class="liodd"><span class="left_name"><?php echo __('Route Type',true);?>:</span><span class="right_value"><?php if($order_arr['route_type']=='1'){echo 'Prime';}elseif($order_arr['route_type']=='2'){echo 'Direct';}else{ echo 'Prime Select Direct';}?>
   </span></li>
    <li class="liodd"><span class="left_name"><?php echo __('code',true);?>:</span><span class="right_value"><a href="javascript:void(0)" title="<?php echo $order_arr['code_true'];?>" onclick="return click_code();" id="clickcode"><span><?php echo $order_arr['code'];?></span></a></span></li>
    <li  class="liodd"><span class="left_name"><?php echo __('CLI Type',true);?>:</span><span class="right_value"><?php if($order_arr['cli_type']=='2'){echo 'Grey';}elseif($order_arr['cli_type']=='1'){echo 'White Non-CLI';}else{ echo 'White';}?></span></li>
    
    <li ><span class="left_name"><?php echo __('asr',true);?>:</span><span class="right_value"><?php echo $order_arr['asr'];?>%</span></li>
     <li><span class="left_name"><?php echo __('acd',true);?>:</span><span class="right_value"><?php echo $order_arr['acd'];?></span></li>
    <li><span class="left_name">PDD(ms):</span><span class="right_value"><?php echo $order_arr['pdd_timeout'];?></span></li>

    <li><span class="left_name">ASR VAR:</span><span class="right_value"><?php echo $order_arr['asr_var'];?>%</span></li>
    <li><span class="left_name">ACD VAR:</span><span class="right_value"> <?php echo $order_arr['acd_var'];?>% </span></li>
    <li><span class="left_name" >PDD VAR:</span><span class="right_value"> <?php echo $order_arr['pdd_var'];?>% </span></li>

    
    <li class="liodd"><span class="left_name"><?php echo __('start_time',true);?>:</span><span class="right_value"><?php echo $order_arr['state_date'];?></span></li>

    <li  class="liodd"><span class="left_name"><?php echo __('dtmf',true);?>:</span><span class="right_value"><?php  if($order_arr['dtmf']=='t'){echo 'Yes';}else{echo 'No';}?></span></li>
    
     <li class="liodd"><span class="left_name"><?php echo __('FAX(T38)',true);?>:</span><span class="right_value"><?php  if($order_arr['fax']=='t'){echo 'Yes';}else{echo 'No';}?></span></li>
     
     <li><span class="left_name"><?php echo __('end_time',true);?>:</span><span class="right_value"><?php echo $order_arr['end_date'];?></span></li>
    <li ><span class="left_name"><?php echo __('interval',true);?>(s):</span><span class="right_value"><?php echo $order_arr['interval'];?></span></li>
    <li ><span class="left_name"><?php echo __('match type',true);?>:</span><span class="right_value">
    <?php if($order_arr['interval']=='1'){echo 'Var';}elseif($order_arr['interval']=='2'){echo 'Soft';}else{echo 'Hard';}?></span></li>
    
    <li  class="liodd"><span class="left_name"><?php echo __('Time-of-Date',true);?>:</span><span class="right_value"><?php  echo $order_arr['tod'];?></span></li>
    
    <li  class="liodd"><span class="left_name" style="width:120px;"><?php echo __('minimal duration',true);?>(s):</span><span class="right_value"><?php echo $order_arr['minimal_duration'];?></span></li>
    <li  class="liodd"><span class="left_name"><?php echo __('routing policy',true);?>:</span><span class="right_value"><?php if($order_arr['route_priority']=='1'){echo 'Quality';}else{echo 'Price';}?></span></li>

  </ul>
<?php else:?>
  <ul class="query_ul query_table">
    <li><span class="left_name"><?php echo __('Order ID',true);?>:</span><span class="right_value"><?php echo $order_arr['id'];?></span> </li>
    <li><span class="left_name"><?php echo __('rate',true);?>:</span><span class="right_value"><?php echo number_format($order_arr['rate'],5);?></span></li>
    <li><span class="left_name"><?php echo __('G729',true);?>:</span><span class="right_value"><?php echo $order_arr['g729'] ? 'YES' : 'NO';?></span></li>
    
    <li class="liodd"><span class="left_name"><?php echo __('status',true);?>:</span><span class="right_value"><?php echo $order_arr['status'];?></span></li>
    <li class="liodd"><span class="left_name"><?php echo __('Country',true);?>:</span><span class="right_value"><?php echo $order_arr['country'];?></span></li>
    <li class="liodd"><span class="left_name"><?php echo __('G711',true);?>:</span><span class="right_value"><?php if($order_arr['g711']=='t'){echo 'Yes';}else{ echo 'No';}?></span></li>
    
    <li ><span class="left_name"><?php echo __('Rated Only',true);?>:</span><span class="right_value"><?php if($order_arr['rate_only']=='t'){echo 'Yes';}else{ echo 'No';}?></span></li>
    <li ><span class="left_name"><?php echo __('Destination',true);?>:</span><span class="right_value"><a href="javascript:void(0)" title="<?php echo $order_arr['code_name_true'];?>" onclick="return click_code();" id="clickcode"><?php echo $order_arr['code_name'];?></a></span></li>
    <li ><span class="left_name"><?php echo __('G723',true);?>:</span><span class="right_value"><?php if($order_arr['G723']=='t'){echo 'Yes';}else{ echo 'No';}?></span></li>
    
    <li  class="liodd"><span class="left_name"><?php echo __('Route Type',true);?>:</span><span class="right_value"><?php if($order_arr['route_type']=='1'){echo 'Prime';}elseif($order_arr['route_type']=='2'){echo 'Direct';}else{ echo 'Prime Select Direct';}?></span></li>
    <li class="liodd"><span class="left_name"><?php echo __('code',true);?>:</span><span class="right_value"><a href="javascript:void(0)" title="<?php echo $order_arr['code_true'];?>" onclick="return click_code();" id="clickcode"><span><?php echo $order_arr['code'];?></span></a></span></li>
    <li  class="liodd"><span class="left_name"><?php echo __('CLI Type',true);?>:</span><span class="right_value"><?php if($order_arr['cli_type']=='2'){echo 'Grey';}elseif($order_arr['cli_type']=='1'){echo 'White Non-CLI';}else{ echo 'White';}?></span></li>
    
    <li ><span class="left_name"><?php echo __('asr',true);?>:</span><span class="right_value"><?php echo $order_arr['asr'];?>%</span></li>
     <li><span class="left_name"><?php echo __('acd',true);?>:</span><span class="right_value"><?php echo $order_arr['acd'];?></span></li>
    <li><span class="left_name"><?php echo __('pdd',true);?>(ms):</span><span class="right_value"><?php echo $order_arr['pdd_timeout'];?></span></li>

    <li class="liodd"><span class="left_name"><?php echo __('start_time',true);?>:</span><span class="right_value"><?php echo $order_arr['state_date'];?></span></li>

    <li  class="liodd"><span class="left_name"><?php echo __('dtmf',true);?>:</span><span class="right_value"><?php  if($order_arr['dtmf']=='t'){echo 'Yes';}else{echo 'No';}?></span></li>
    
     <li class="liodd"><span class="left_name"><?php echo __('FAX(T38)',true);?>:</span><span class="right_value"><?php  if($order_arr['fax']=='t'){echo 'Yes';}else{echo 'No';}?></span></li>
     
     <li><span class="left_name"><?php echo __('end_time',true);?>:</span><span class="right_value"><?php echo $order_arr['end_date'];?></span></li>
    <li ><span class="left_name"><?php echo __('interval',true);?>(s):</span><span class="right_value"><?php echo $order_arr['interval'];?></span></li>
    <li ><span class="left_name"><?php echo __('match type',true);?>:</span><span class="right_value"><?php if($order_arr['interval']=='1'){echo 'Var';}elseif($order_arr['interval']=='2'){echo 'Soft';}else{echo 'Hard';}?></span></li>
    
    <li  class="liodd"><span class="left_name"><?php echo __('Time-of-Date',true);?>:</span><span class="right_value"><?php  echo $order_arr['tod'];?></span></li>
    
    <li  class="liodd"><span class="left_name" style="width:120px;"><?php echo __('minimal duration',true);?>(s):</span><span class="right_value"><?php echo $order_arr['minimal_duration'];?></span></li>
    <li  class="liodd"><span class="left_name"><?php echo __('routing policy',true);?>:</span><span class="right_value"><?php if($order_arr['route_priority']=='1'){echo 'Quality';}else{echo 'Price';}?></span></li>

  </ul>
  <?php endif;?>
</div>

</div>

