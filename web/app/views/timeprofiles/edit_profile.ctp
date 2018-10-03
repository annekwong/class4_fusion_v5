<script type="text/javascript" src="<?php echo $this->webroot?>js/My97DatePicker/WdatePicker.js"></script>
<ul class="breadcrumb">
    <li><?php echo __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Switch') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('edittimeprofile') ?></li>
</ul>
<div class="heading-buttons">
    <h4 class="heading"><?php echo __('edittimeprofile')?></h4>
</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
        <a class="link_back circle_arrow_left" href="<?php echo $this->webroot?>timeprofiles/profile_list">&nbsp;<?php echo __('goback')?> </a> 
</div>
<div class="clearfix"></div>

<div class="container">
  <form method="post" action="<?php echo $this->webroot?>timeprofiles/edit_profile">
    <input type="hidden" value="<?php echo $profile[0][0]['time_profile_id']?>" id="time_profile_id" name="time_profile_id"/>
    <table class="list">
      <tbody>
        <tr>
          <td class="label label2"  style="width:150px;"><?php echo __('timeprofilename')?>:</td>
          <td class="value value2"><input type="text" style="float:left;width:200px;" id="name" value="<?php echo $profile[0][0]['name']?>" name="name" class="input in-text"></td>
        </tr>
        <tr><td>&nbsp;</td>
          <td><!--<div class="form_panel_table_Pre1" style="text-align: left;">-->
              <div id="context_right_nav_div">
                <ul>
                  <li id="li1" style="float:left;margin-left:20px;" class="selected"><a class=" route_add_Edit_style_15" onclick="javascript:switchTime(this,0)" href="javascript:;" id="switch0">
                    <input type="radio" checked="checked" value="0" name="type" id="repeat1">
                    <span class="top"><?php echo __('alltime')?></span></a></li>
                  <li style="float:left;margin-left:20px;" id="li2"><a class=" route_add_Edit_style_16" onclick="javascript:switchTime(this,1)" href="javascript:;" id="switch1">
                    <input type="radio" value="1" name="type" id="repeat2">
                    <span class="top"><?php echo __('weekly')?></span></a></li>
                  <li style="float:left;margin-left:20px;" id="li3"><a class=" route_add_Edit_style_17" onclick="javascript:switchTime(this,2)" href="javascript:;" id="switch2">
                    <input type="radio" value="2" name="type" id="repeat3">
                    <span class="top"><?php echo __('daily')?></span></a></li>
                </ul>
                <?php
					$switch = 0;
				 if ($profile[0][0]['type'] == 0)
				 		$switch = 'switch0';
				 	else if ($profile[0][0]['type'] == 1)
				 		$switch = 'switch1';
				 	else 
				 		$switch = 'switch2';
			?>
              </div>
              </td>
              </tr>
              <tr><td>&nbsp;</td>
              <td style="text-align:left;">
              <div id="context_right_nav_div_down1" style="display: block;"> </div>
              <div id="context_right_nav_div_down2" style="display: none;">
                <p><span id="stSpanWord" style="margin-left:78px;"><?php echo __('start_time',true);?></span> <span id="swSpan" style="display: inline;">
                  <select name="start_week" id="start_week">
                    <option value="7"><?php echo __('sunday')?></option>
                    <option value="1"><?php echo __('monday')?></option>
                    <option value="2"><?php echo __('tuesday')?></option>
                    <option value="3"><?php echo __('wed')?></option>
                    <option value="4"><?php echo __('thuesday')?></option>
                    <option value="5"><?php echo __('friday')?></option>
                    <option value="6"><?php echo __('sat')?></option>
                  </select>
                  <script>document.getElementById('start_week').value="<?php echo $profile[0][0]['start_week']?>"</script> 
                  </span> <span id="stSpan">
                  <input name="start_time" value="<?php echo $profile[0][0]['start_time']?>" id="startTime" readonly class="Wdate" onfocus="WdatePicker({dateFmt:'HH:mm'});"/>
                  <!--  <select name="start_time" id="startTime">
                    <option value="00:00:00">00:00</option>
                    <option value="00:30:00">00:30</option>
                    <option value="01:00:00">01:00</option>
                    <option value="01:30:00">01:30</option>
                    <option value="02:00:00">02:00</option>
                    <option value="02:30:00">02:30</option>
                    <option value="03:00:00">03:00</option>
                    <option value="03:30:00">03:30</option>
                    <option value="04:00:00">04:00</option>
                    <option value="04:30:00">04:30</option>
                    <option value="05:00:00">05:00</option>
                    <option value="05:30:00">05:30</option>
                    <option value="06:00:00">06:00</option>
                    <option value="06:30:00">06:30</option>
                    <option value="07:00:00">07:00</option>
                    <option value="07:30:00">07:30</option>
                    <option value="08:00:00">08:00</option>
                    <option value="08:30:00">08:30</option>
                    <option value="09:00:00">09:00</option>
                    <option value="09:30:00">09:30</option>
                    <option value="10:00:00">10:00</option>
                    <option value="10:30:00">10:30</option>
                    <option value="11:00:00">11:00</option>
                    <option value="11:30:00">11:30</option>
                    <option value="12:00:00">12:00</option>
                    <option value="12:30:00">12:30</option>
                    <option value="13:00:00">13:00</option>
                    <option value="13:30:00">13:30</option>
                    <option value="14:00:00">14:00</option>
                    <option value="14:30:00">14:30</option>
                    <option value="15:00:00">15:00</option>
                    <option value="15:30:00">15:30</option>
                    <option value="16:00:00">16:00</option>
                    <option value="16:30:00">16:30</option>
                    <option value="17:00:00">17:00</option>
                    <option value="17:30:00">17:30</option>
                    <option value="18:00:00">18:00</option>
                    <option value="18:30:00">18:30</option>
                    <option value="19:00:00">19:00</option>
                    <option value="19:30:00">19:30</option>
                    <option value="20:00:00">20:00</option>
                    <option value="20:30:00">20:30</option>
                    <option value="21:00:00">21:00</option>
                    <option value="21:30:00">21:30</option>
                    <option value="22:00:00">22:00</option>
                    <option value="22:30:00">22:30</option>
                    <option value="23:00:00">23:00</option>
                    <option value="23:30:00">23:30</option>
                  </select>--> 
                  </span>
               <span id="etSpanWord"><?php echo __('end_time',true);?></span> <span id="ewSpan" style="display: inline;">
                  <select name="end_week" id="endWeek">
                    <option value="7"><?php echo __('sunday')?></option>
                    <option value="1"><?php echo __('monday')?></option>
                    <option value="2"><?php echo __('tuesday')?></option>
                    <option value="3"><?php echo __('wed')?></option>
                    <option value="4"><?php echo __('thuesday')?></option>
                    <option value="5"><?php echo __('friday')?></option>
                    <option value="6"><?php echo __('sat')?></option>
                  </select>
                  <script>document.getElementById('endWeek').value="<?php echo $profile[0][0]['end_week']?>"</script> 
                  </span> <span id="etSpan">
                  <input name="end_time" value="<?php echo $profile[0][0]['end_time']?>" id="endTime" readonly class="Wdate" onfocus="WdatePicker({dateFmt:'HH:mm'});"/>
                  <!--  <select name="end_time" id="endTime">
                     <option value="00:00:00">00:00</option>
                    <option value="00:30:00">00:30</option>
                    <option value="01:00:00">01:00</option>
                    <option value="01:30:00">01:30</option>
                    <option value="02:00:00">02:00</option>
                    <option value="02:30:00">02:30</option>
                    <option value="03:00:00">03:00</option>
                    <option value="03:30:00">03:30</option>
                    <option value="04:00:00">04:00</option>
                    <option value="04:30:00">04:30</option>
                    <option value="05:00:00">05:00</option>
                    <option value="05:30:00">05:30</option>
                    <option value="06:00:00">06:00</option>
                    <option value="06:30:00">06:30</option>
                    <option value="07:00:00">07:00</option>
                    <option value="07:30:00">07:30</option>
                    <option value="08:00:00">08:00</option>
                    <option value="08:30:00">08:30</option>
                    <option value="09:00:00">09:00</option>
                    <option value="09:30:00">09:30</option>
                    <option value="10:00:00">10:00</option>
                    <option value="10:30:00">10:30</option>
                    <option value="11:00:00">11:00</option>
                    <option value="11:30:00">11:30</option>
                    <option value="12:00:00">12:00</option>
                    <option value="12:30:00">12:30</option>
                    <option value="13:00:00">13:00</option>
                    <option value="13:30:00">13:30</option>
                    <option value="14:00:00">14:00</option>
                    <option value="14:30:00">14:30</option>
                    <option value="15:00:00">15:00</option>
                    <option value="15:30:00">15:30</option>
                    <option value="16:00:00">16:00</option>
                    <option value="16:30:00">16:30</option>
                    <option value="17:00:00">17:00</option>
                    <option value="17:30:00">17:30</option>
                    <option value="18:00:00">18:00</option>
                    <option value="18:30:00">18:30</option>
                    <option value="19:00:00">19:00</option>
                    <option value="19:30:00">19:30</option>
                    <option value="20:00:00">20:00</option>
                    <option value="20:30:00">20:30</option>
                    <option value="21:00:00">21:00</option>
                    <option value="21:30:00">21:30</option>
                    <option value="22:00:00">22:00</option>
                    <option value="22:30:00">22:30</option>
                    <option value="23:00:00">23:00</option>
                    <option value="23:30:00">23:30</option>
                    </select>--> 
                  </span> <span class="errorInfo" style="display: none;">&nbsp;&nbsp;</span>
              </div>
              <div id="context_right_nav_div_down3" style="display: none;"> </div>
            <!--</div>--></td></tr>
        </tr>
      </tbody>
    </table>
    <div id="form_footer">
      <input type="submit" value="<?php echo __('submit')?>" class="input btn btn-primary">
      <input type="reset" value="<?php echo __('reset')?>" class="input btn btn-default">
    </div>
  </form>
</div>
<script>
			switchTime(document.getElementById('<?php echo $switch?>'),<?php echo $profile[0][0]['type']?>);
	</script> 

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
