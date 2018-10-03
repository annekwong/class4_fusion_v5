 <script type="text/javascript" src="<?php echo $this->webroot?>js/My97DatePicker/WdatePicker.js"></script>
	<div id="title">
	   <h1><?php echo __('newtimeprofile')?></h1>
	   <ul id="title-menu">
    		<li>
    			<a class="link_back" href="<?php echo $this->webroot?>timeprofiles/profile_list">
    				<img width="16" height="16"  alt="" src="<?php echo $this->webroot?>images/icon_back_white.png">
    				&nbsp;<?php echo __('goback')?>
    			</a>
    		</li>
  		</ul>
	</div>
	<div class="container">
		<form method="post" id="submitForm" action="<?php  echo $this->webroot ?>timeprofiles/add_profile">
			<table class="form" style="margin-left:10%;wdith:960px;min-width: 960PX">
				<tbody>
					<tr>
    				<td class="label label2" style="width="350%" ><?php echo __('timeprofilename')?>:</td>
    				<td class="value value2"><input type="text" style="float:left;width:200px;" id="name" value="" name="name" class="input in-text" maxLength="16"></td>
				 </tr>
				 <tr align="left">
				 			<td colspan="2" style="text-align:center; padding-right: 30%;width=960px">
				 			<div class="form_panel_table_Pre1" style="display: inline; text-align: left" >
                    
        <div id="context_right_nav_div" align="right">
                    
		    <ul >
			   <li id="li1" class="selected"><a class=" route_add_Edit_style_15" onclick="javascript:switchTime(this,0)" href="javascript:;" id="switch0"><input type="radio" checked="checked" value="0" name="type" id="repeat1"><span class="top"><?php echo __('alltime')?></span></a></li>
			   <li id="li2"><a class=" route_add_Edit_style_16" onclick="javascript:switchTime(this,1)" href="javascript:;" id="switch1"><input type="radio" value="1" name="type" id="repeat2"><span class="top"><?php echo __('weekly')?></span></a></li>
			   <li id="li3"><a class=" route_add_Edit_style_17" onclick="javascript:switchTime(this,2)" href="javascript:;" id="switch2"><input type="radio" value="2" name="type" id="repeat3"><span class="top"><?php echo __('daily')?></span></a></li>
			</ul>
		</div>
		<div id="context_right_nav_div_down1" style="display: block;">
		  
		</div>
        <div id="context_right_nav_div_down2" style="display: none; margin-left:10px;">
		  <p><span id="stSpanWord" style="margin-left:75px;"><?php echo __('start_time',true);?></span>
		        <span id="swSpan" style="display: inline;">
                  <select name="start_week" id="start_week">
                    <option value="7"><?php echo __('sunday')?></option>
                    <option value="1"><?php echo __('monday')?></option>
                    <option value="2"><?php echo __('tuesday')?></option>
                    <option value="3"><?php echo __('wed')?></option>
                    <option value="4"><?php echo __('thuesday')?></option>
                    <option value="5"><?php echo __('friday')?></option>
                    <option value="6"><?php echo __('sat')?></option>
                  </select>
                </span>
                <span id="stSpan">
                	<input name="start_time" value="00:00" id="startTime" readonly class="Wdate" onfocus="WdatePicker({dateFmt:'HH:mm'});"/>
                  <!--  <select name="start_time" id="startTime">
                    <option value="00:00">00:00</option>
                    <option value="00:30">00:30</option>
                    <option value="01:00">01:00</option>
                    <option value="01:30">01:30</option>
                    <option value="02:00">02:00</option>
                    <option value="02:30">02:30</option>
                    <option value="03:00">03:00</option>
                    <option value="03:30">03:30</option>
                    <option value="04:00">04:00</option>
                    <option value="04:30">04:30</option>
                    <option value="05:00">05:00</option>
                    <option value="05:30">05:30</option>
                    <option value="06:00">06:00</option>
                    <option value="06:30">06:30</option>
                    <option value="07:00">07:00</option>
                    <option value="07:30">07:30</option>
                    <option value="08:00">08:00</option>
                    <option value="08:30">08:30</option>
                    <option value="09:00">09:00</option>
                    <option value="09:30">09:30</option>
                    <option value="10:00">10:00</option>
                    <option value="10:30">10:30</option>
                    <option value="11:00">11:00</option>
                    <option value="11:30">11:30</option>
                    <option value="12:00">12:00</option>
                    <option value="12:30">12:30</option>
                    <option value="13:00">13:00</option>
                    <option value="13:30">13:30</option>
                    <option value="14:00">14:00</option>
                    <option value="14:30">14:30</option>
                    <option value="15:00">15:00</option>
                    <option value="15:30">15:30</option>
                    <option value="16:00">16:00</option>
                    <option value="16:30">16:30</option>
                    <option value="17:00">17:00</option>
                    <option value="17:30">17:30</option>
                    <option value="18:00">18:00</option>
                    <option value="18:30">18:30</option>
                    <option value="19:00">19:00</option>
                    <option value="19:30">19:30</option>
                    <option value="20:00">20:00</option>
                    <option value="20:30">20:30</option>
                    <option value="21:00">21:00</option>
                    <option value="21:30">21:30</option>
                    <option value="22:00">22:00</option>
                    <option value="22:30">22:30</option>
                    <option value="23:00">23:00</option>
                    <option value="23:30">23:30</option>
                  </select>-->
                </span></p>
                <p>
          <span id="etSpanWord"><?php echo __('end_time',true);?></span>
                  <span id="ewSpan" style="display: inline;">
                    <select name="end_week" id="endWeek">
	                    <option value="7"><?php echo __('sunday')?></option>
	                    <option value="1"><?php echo __('monday')?></option>
	                    <option value="2"><?php echo __('tuesday')?></option>
	                    <option value="3"><?php echo __('wed')?></option>
	                    <option value="4"><?php echo __('thuesday')?></option>
	                    <option value="5"><?php echo __('friday')?></option>
	                    <option value="6"><?php echo __('sat')?></option>
                    </select>
                  </span>
                  <span id="etSpan">
                  	<input name="end_time" value="23:59" id="endTime" readonly class="Wdate" onfocus="WdatePicker({dateFmt:'HH:mm'});"/>
                    <!--  <select name="end_time" id="endTime">
                      									<option value="00:00">00:00</option>
                    <option value="00:30">00:30</option>
                    <option value="01:00">01:00</option>
                    <option value="01:30">01:30</option>
                    <option value="02:00">02:00</option>
                    <option value="02:30">02:30</option>
                    <option value="03:00">03:00</option>
                    <option value="03:30">03:30</option>
                    <option value="04:00">04:00</option>
                    <option value="04:30">04:30</option>
                    <option value="05:00">05:00</option>
                    <option value="05:30">05:30</option>
                    <option value="06:00">06:00</option>
                    <option value="06:30">06:30</option>
                    <option value="07:00">07:00</option>
                    <option value="07:30">07:30</option>
                    <option value="08:00">08:00</option>
                    <option value="08:30">08:30</option>
                    <option value="09:00">09:00</option>
                    <option value="09:30">09:30</option>
                    <option value="10:00">10:00</option>
                    <option value="10:30">10:30</option>
                    <option value="11:00">11:00</option>
                    <option value="11:30">11:30</option>
                    <option value="12:00">12:00</option>
                    <option value="12:30">12:30</option>
                    <option value="13:00">13:00</option>
                    <option value="13:30">13:30</option>
                    <option value="14:00">14:00</option>
                    <option value="14:30">14:30</option>
                    <option value="15:00">15:00</option>
                    <option value="15:30">15:30</option>
                    <option value="16:00">16:00</option>
                    <option value="16:30">16:30</option>
                    <option value="17:00">17:00</option>
                    <option value="17:30">17:30</option>
                    <option value="18:00">18:00</option>
                    <option value="18:30">18:30</option>
                    <option value="19:00">19:00</option>
                    <option value="19:30">19:30</option>
                    <option value="20:00">20:00</option>
                    <option value="20:30">20:30</option>
                    <option value="21:00">21:00</option>
                    <option value="21:30">21:30</option>
                    <option value="22:00">22:00</option>
                    <option value="22:30">22:30</option>
                    <option value="23:00">23:00</option>
                    <option value="23:30">23:30</option>
                    </select>-->
                  </span>
                  <span class="errorInfo" style="display: none;">&nbsp;&nbsp;</span></p>
		</div>
		<div id="context_right_nav_div_down3" style="display: none;">

		</div>
                    
                  </div>
				 			</td>
				 			
				 </tr>
				</tbody>
			</table>

			<div id="form_footer">
        <input type="submit" value="<?php echo __('submit')?>" class="input in-submit">
   			 <input type="reset" value="<?php echo __('reset')?>" class="input in-submit">
    </div>
		</form>
	</div>
	
	<!-- 如果验证没通过  将用户输入的表单信息重新显示 -->
<?php
			$backform = $session->read('backform');//用户刚刚输入的表单数据
			if (!empty($backform)) {
				$session->del('backform');//清除错误信息
		
				//将用户刚刚输入的数据显示到页面上
				$d = array_keys($backform);
			 foreach($d as $k) {?>
						<script>document.getElementById("<?php echo $k?>").value = "<?php echo $backform[$k]?>";</script>
<?php }?>
<?php
			}
	?>
<script type="text/javascript">
<!--
      jQuery(document).ready(function(){
         jQuery('#name').xkeyvalidate({type:'strNum'});

                 });
jQuery(document).ready(function(){
	 jQuery('#submitForm').submit(function(){
      if(/[^0-9A-Za-z-\_\s]+/.test(jQuery('#name'))){
	    	  jQuery(that).addClass('invalid');
			    jGrowl_to_notyfy('Name must contain alphanumeric characters only.',{theme:'jmsg-error'});
			    return false;  
                }
	 }); 
});
//-->
</script>