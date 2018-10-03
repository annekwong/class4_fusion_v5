<script language=JavaScript>
 		//加载全局的报表
		var c1;//左侧报表
		var c2;//右侧报表
		var globalUrl = "<?php echo $this->webroot?>";//相当于<?php echo $this->webroot?>
 		function globalChart(id,type,obj,LOrR,url){
 	 		var c;//生成的报表对象
 			var columns = getcolumns();

			//设置Ajax请求同步
			jQuery.ajaxSetup({
				  async: false
			}); 

			var url = globalUrl+"monitors/report?whichTime="+type+"&type=1";

			//获得数据生成报表
			jQuery.get(url,function(data){
				c = Chart(data,columns,id);//生成报表的方法
			});
			

			//时间段   下划线
			underlineornot(LOrR,obj);

			return c;
 	 	}

 		//加载 history 表格的信息
 		function f_history() {
 			historycal(globalUrl+"monitors/history");//加载历史信息
 	 	}
 		
 		google.setOnLoadCallback(loadChartLeft);//加载左侧报表


 		//=====> Historical
 		google.setOnLoadCallback(f_history);//加载Historycal信息


 		//======>当前最高,System Cap,Point in time
 		google.setOnLoadCallback(api_getsyslimit);//加载get_sys_limit的信息   

 </script>
 
 <script>
//刷新数据
var time = window.setInterval("refreshPro()",180000);
function refreshPro(){
	loadChartLeft();//加载报表
	f_history();//加载历史信息
	api_getsyslimit();//get_sys_limit命令返回的实时信息
}
</script>


<div id="proStatsDivPic" class='monitor5'>
	<div id="leftChart" class="divStyle10">
	<div id="leftTitleChart" class="divStyle11">
		<table width='100%' cellspacing=0 cellpadding=0>
			<tr>
				<td style="border:1px solid gray;background:#eeeeee;" class='monitor8'>
					<span class="spanStyle2"><?php __('Volume Metrics')?></span>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;
					&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;
					&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;
					&nbsp;&nbsp;&nbsp;&nbsp;
					<span id="menufive" class='monitor15'><!--  <img src="images/spand.gif" />--></span>
					<input type='hidden' id='isSMV1' name='isSMV1' value='1'>
				</td>
			</tr>
		</table>
	</div>

	<div id="leftControlBar" class='monitor19'>
		<span class='monitor20'><?php __('View')?>:</span>
		<input type='hidden' id='currentView1' value='call1,cps1' />
		<input type="hidden" id='currentColumn1' value='2' />
		<span class='monitor21'>
			<input type='checkbox' checked class='monitor22' onclick='onlychoosetwo(this)' id="call1" />
			&nbsp;&nbsp; <?php __('Calls')?>
			<input type="hidden" name="call_1" id="call_1" value="0"></input>
	 </span>
	 <span class='monitor21'>
		 	<input type='checkbox' checked class='monitor22' onclick='onlychoosetwo(this)' id="cps1" />
		 	&nbsp;&nbsp; <?php __('CPS')?>
		 	<input type="hidden" name="cps_1_m" id="cps_1_m" value="1">
		 	<input type="hidden" name="cps_1" id="cps_1" value="1000"></input> 
	 </span>
	 <span class='monitor21'> <input type='checkbox' disabled class='monitor22' onclick='onlychoosetwo(this)' id="asr1" />
		 &nbsp;&nbsp; <?php __('ASR')?>
		 <input type="hidden" name="asr_1_m" id="asr_1_m" value="1000">
		 <input type="hidden" name="asr_1" id="asr_1" value="1000"></input>
	 </span>
	 <span class='monitor21'> <input type='checkbox' disabled class='monitor22' onclick='onlychoosetwo(this)' id="acd1" />
		 &nbsp;&nbsp; <?php __('ACD')?>
		 <input type="hidden" name="acd_1_m" id="acd_1_m" value="1000"></input>
		 <input type="hidden" name="acd_1" id="acd_1" value="1000"></input>
	</span>
	<span class='monitor21'>
		<input type='checkbox' disabled class='monitor22' onclick='onlychoosetwo(this)' id="pdd1" />
		&nbsp;&nbsp; <?php __('PDD')?>
		<input type="hidden" name="pdd_1_m" id="pdd_1_m" value="1000"></input> <input	type="hidden" name="pdd_1" id="pdd_1" value="1000"></input> 
	</span>
</div>


<div id="leftTimeBar" class='monitor23'><span class='monitor21'>Zoom:</span>
<span class="zoomline"
	onclick="loadset('coordinateID',3,this,this.parentNode);"><?php echo __('lasthour')?></span>
<span class="zoomline"
	onclick="loadset('coordinateID',2,this,this.parentNode);"><?php echo __('last3hr')?></span>
<span id="defaultchecked" class="zoomnone"
	onclick="loadset('coordinateID',1,this,this.parentNode);"><?php echo __('last24hrs')?></span>
<span class="zoomline"
	onclick="loadset('coordinateID',4,this,this.parentNode);"><?php echo __('last3days')?></span>
<span class="zoomline"
	onclick="loadset('coordinateID',5,this,this.parentNode);"><?php echo __('last7days')?></span>
</div>

<div class='monitor24'>
<div id="coordinateID" class='monitor25'></div>
</div>

</div>



<div id="rightChart" class="divStyle12">
	<div id="rightTitleChart" class="divStyle13">
	<table width='100%' cellspacing=0 cellpadding=0>
		<tr>
			<td class='monitor8' style="border:1px solid gray;background:#eeeeee;"><span class="spanStyle2"><?php __('Quality Metrics')?></span> <span
				class="spanStyle5"> <!--  <span id='qualStart' class="spanStyle4">&nbsp;</span>
				span>--</span>--> <span id='qualEnd' class="spanStyle4"> </span>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;
				&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;
				&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;
				&nbsp;&nbsp;&nbsp;&nbsp; 
				<span id="menufives" class='monitor15'><!--  <img src="images/spand.gif" />--></span>
	
				<input type='hidden' id='isSMV2' name='isSMV2' value='1'> </span>
				&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;
			</td>
		</tr>
	</table>
</div>

<div id="rightControlBar" class='monitor19'><span class='monitor20'><?php __('View')?>:</span>
<input type='hidden' id='currentView2' value='asr2,acd2'> <input
	type="hidden" id='currentColumn2' value='2'> <span class='monitor21'> <input
	type='checkbox' disabled class='monitor22'
	onclick='onlychoosetwo(this)' id="call2" />&nbsp;&nbsp; <?php __('Calls')?><input
	type="hidden" name="call_2" id="call_2" value="1000"></input> </span> <span
	class='monitor21'> <input type='checkbox' disabled class='monitor22'
	onclick='onlychoosetwo(this)' id="cps2" />&nbsp;&nbsp; <?php __('CPS')?><input
	type="hidden" name="cps_2_m" id="cps_2_m" value="1000"></input> <input
	type="hidden" name="cps_2" id="cps_2" value="1000"></input> </span> <span
	class='monitor21'> <input type='checkbox' checked class='monitor22'
	onclick='onlychoosetwo(this)' id="asr2" />&nbsp;&nbsp; <?php __('ASR')?><input
	type="hidden" name="asr_2_m" id="asr_2_m" value="0"></input> <input
	type="hidden" name="asr_2" id="asr_2" value="1000"></input> </span> <span
	class='monitor21'> <input type='checkbox' checked class='monitor22'
	onclick='onlychoosetwo(this)' id="acd2" />&nbsp;&nbsp; <?php __('ACD')?><input
	type="hidden" name="acd_2_m" id="acd_2_m" value="1"></input> <input
	type="hidden" name="acd_2" id="acd_2" value="1000"></input> </span> <span
	class='monitor21'> <input type='checkbox' disabled class='monitor22'
	onclick='onlychoosetwo(this)' id="pdd2" />&nbsp;&nbsp; <?php __('PDD')?><input
	type="hidden" name="pdd_2_m" id="pdd_2_m" value="1"></input> <input
	type="hidden" name="pdd_2" id="pdd_2" value="1000"></input> </span></div>


<div id="rightTimeBar" class='monitor23'><span class='monitor21'><?php __('Zoom')?>:</span>
<span class="zoomline"
	onclick="loadset('longerID',3,this,this.parentNode);"><?php echo __('lasthour')?></span>
<span class="zoomline"
	onclick="loadset('longerID',2,this,this.parentNode);"><?php echo __('last3hr')?></span>
<span id="defaultchecked1" class="loadset"
	onclick="loadset('longerID',1,this,this.parentNode);"><?php echo __('last24hrs')?></span>
<span class="zoomline"
	onclick="loadset('longerID',4,this,this.parentNode);"><?php echo __('last3days')?></span>
<span class="zoomline"
	onclick="loadset('longerID',5,this,this.parentNode);"><?php echo __('last7days')?></span>
</div>

<div class='monitor24'>
<div id="longerID"  class='monitor25'></div>
</div>

</div>
<div style="clear: both;"></div>
</div>
<a name="ur"></a>
<div class="monitor_global_styles">

<div class=" monitor_global_style_4">

<table width="100%" cellpadding=0 cellspacing=0>
	<tr>
		<td class='monitor40'><span class=" monitor_global_style_5"><img
					alt="" src="images/angle.gif">&nbsp;&nbsp;<?php echo __('pointintime')?></span></td>
	</tr>
</table>
</div>

<div>
	<table style="width: 100% !important; width: 96%;" cellpadding=0 cellspacing=0>
		<tr>
			<td height='60' class='monitor42'>&nbsp;</td>
			<td width="60%" valign="top" class='monitor43'>
				<div class='monitor_global_style_7'>
					<table width="100%" cellpadding=0 cellspacing=0 class='monitor44'>
						<tr height="30px" bgcolor='#eeeeee'>
							<th class="bcess_td" width="150px">&nbsp;</th>
							<th class="bcess_td" width="150px"><?php echo __('currently')?></th>
							<th class="bcess_td" width="150px"><?php echo __('24hrpeak')?></th>
							<th class="bcess_td" width="150px"><?php echo __('7dayspeak')?></th>
							<th class="bcess_td" width="150px"><?php Echo __('recentpeak')?></th>
						</tr>
			
			<tbody id="syslimit">
				<tr height="27px">
					<td class="bcess_td textcenter"><?php __('Total SIP')?></td>
					<td class="bcess_td textcenter">0</td>
					<td class="bcess_td textcenter">0</td>
					<td class="bcess_td textcenter">0</td>
					<td class="bcess_td textcenter">0</td>
				</tr>
				
				<tr height="27px" bgcolor='#eeeeee'>
					<td class="bcess_td textcenter">-<?php __('With Media')?></td>
					<td class="bcess_td textcenter">0</td>
					<td class="bcess_td textcenter">0</td>
					<td class="bcess_td textcenter">0</td>
					<td class="bcess_td textcenter">0</td>
				</tr>
				
				<tr height="27px">
					<td class="bcess_td textcenter">-<?php __('w/0 Media')?></td>
					<td class="bcess_td textcenter">0</td>
					<td class="bcess_td textcenter">0</td>
					<td class="bcess_td textcenter">0</td>
					<td class="bcess_td textcenter">0</td>
				</tr>
				
				<tr height="27px" bgcolor='#eeeeee'>
					<td class="bcess_td textcenter"><?php __('SIP cps')?></td>
					<td class="bcess_td textcenter">0</td>
					<td class="bcess_td textcenter">0</td>
					<td class="bcess_td textcenter">0</td>
					<td class="bcess_td textcenter">0</td>
				</tr>
			</tbody>
		</table>
		</div>
		</td>

		<td width="39%" valign="top" class='monitor43'>
		<div id='monitor_global_style_8'>
		<table width="100%" cellpadding=0 cellspacing=0 class='monitor44'>
			<tr height="30px" bgcolor='#eeeeee'>
				<th class="bcess_td" width="100px">&nbsp;</th>
				<th class="bcess_td" width="150px"><?php echo __('currently')?></th>
				<th class="bcess_td" width="150px"><?php __('System Cap')?></th>
			</tr>
			
			<tbody id="currentSys">
				<tr height="28px">
				<td class="bcess_td" align="center"><?php __('Total Calls')?></td>
				<td class="bcess_td" align="center">0</td>
				<td class="bcess_td" align="center">0</td>
			</tr>
			<tr height="28px" bgcolor='#eeeeee'>
				<td class="bcess_td" align="center"><?php __('Total CPS')?></td>
				<td class="bcess_td" align="center">0</td>
				<td class="bcess_td" align="center">0</td>
			</tr>
			</tbody>
		</table>
		</div>
		</td>
</tr>
	<tr height="2px">
		<td colspan=2 class='monitor46'>&nbsp;</td>
	</tr>
</table>
</div>

</div>
<div>
<div class=" monitor_global_style_9">
<table style="width: 100% !important; width: 96%;" cellpadding=0
	cellspacing=0>
	<tr>
		<td class='monitor40'><span class=" monitor_global_style_10"><img
			alt="" src="images/angle.gif">&nbsp;&nbsp;<?php echo __('historical')?></span></td>
	</tr>
</table>
</div>

<div class="monitor_global_style_11">
<table id="show_table" cellspacing=0 cellpadding=0 width="100%">
	<thead>
		<tr height="30px" bgcolor='#eeeeee'>
			<th class="bcess_td">&nbsp;</th>
			<th class="bcess_td">15 <?php echo __('minutes')?></th>
			<th class="bcess_td">1 <?php echo __('hour')?></th>
			<th class="bcess_td">24 <?php echo __('hour')?></th>
		</tr>

		<tr height="28px" bgcolor='#eeeeee'>
			<td width="200px" class="bcess_td monitor47">&nbsp;</td>
			<td class="bcess_td">
			<div class=" monitor_product_style_10"><?php __('ACD')?></div>
			<div class=" monitor_product_style_11"><?php __('ASR')?></div>
			<div class=" monitor_product_style_11"><?php __('CA')?></div>
			<div class=" monitor_product_style_12"><?php __('PDD')?></div>
			</td>
			<td class="bcess_td">
			<div class=" monitor_product_style_13"><?php __('ACD')?></div>
			<div class=" monitor_product_style_11"><?php __('ASR')?></div>
			<div class=" monitor_product_style_11"><?php __('CA')?></div>
			<div class=" monitor_product_style_12"><?php __('PDD')?></div>
			</td>
			<td class="bcess_td">
			<div class=" monitor_product_style_10"><?php __('ACD')?></div>
			<div class=" monitor_product_style_11"><?php __('ASR')?></div>
			<div class=" monitor_product_style_11"><?php __('CA')?></div>
			<div class=" monitor_product_style_18"><?php __('PDD')?></div>
			</td>
		</tr>
	</thead>

	<tbody id="tbodyOfShowTable">

	</tbody>

</table>
</div>

<div class='monitor48'>
<table width="100%" cellpadding=0 cellspacing=0>
	<tr>
		<td class='monitor46'>&nbsp;</td>
	</tr>
</table>
</div>
</div>

<div class='monitor49'><span class="groupBy"><?php echo __('refreshtime')?>:</span> <span>
<input type='radio' name="reTime" onclick="changeTime()" value='0'
	checked>3 <?php echo __('minutes')?> <input type='radio' name="reTime"
	onclick="changeTime()" value='1'>5 <?php echo __('minutes')?> <input type='radio'
	name="reTime" onclick="changeTime()" value='2'>15  <?php echo __('minutes')?> </span>
<input type="button" onclick="changeTime();" value="<?php echo __('refresh')?>"/>
<input type="button" onclick="clearTime();" value="<?php echo __('clear')?>"/>
</div>
