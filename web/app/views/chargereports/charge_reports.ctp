<script type="text/javascript">
    			var result = eval(<?php echo $result?>);
</script>
</head>
<div id="title">
            <h1>
    <?php echo __('chargereport')?>
                        </h1>
    </div>

<?php
	//系统管理员登录显示过滤和未过滤的数据    其他代理商则只显示过滤后的数据
	$login_type = $session->read('login_type');
	if($login_type == 1){ 
?>
		<div id="container">
<div id="cover"></div>
<div id="cover_tmp"></div>


<?php //****************************************表格报表?>
<table class="list nowrap with-fields">
<thead>
            
    <tr>
                        <td class="cset-1" colspan="2"></td>
        <td class="cset-2" colspan="2"><?php echo __('syscard')?></td>
        <td class="cset-2" colspan="2"><?php echo __('othercharge')?></td>
        <td class="cset-6 last" colspan="6"><span><?php echo __('platform')?></span></td>
    </tr>
    <tr>
        <td class="cset-1"><?php echo __('date')?></td>
        <td class="cset-1"><?php echo __('Reseller')?></td>
        <td class="cset-1"><?php echo __('chargeamount')?></td>
        <td class="cset-1"><?php echo __('ofcharges')?></td>
        <td class="cset-1"><?php echo __('chargeamount')?></td>
        <td class="cset-1"><?php echo __('ofcharges')?></td>
        <td class="cset-1"><?php echo __('chargeamount')?></td>
        <td class="cset-1"><?php echo __('ofcharges')?></td>
        <td class="cset-1"><?php echo __('chargeamountsuc')?></td>
        <td class="cset-1"><?php echo __('ofcahrgessuc')?></td>
        <td class="cset-1"><?php echo __('chargeamountfail')?></td>
        <td class="cset-1"><?php echo __('ofcahrgesfail')?></td>
    </tr>
</thead>
<tbody class="rows" id="reporttab">
  <tr class="row-1">
     <td style="font-weight:bold;color:green;"><script>document.writeln(result[0].st+" ---- "+result[0].et.substring(0,result[0].et.indexOf("+")))</script></td>
   	 <td><b style="color:red;"><script>document.writeln(result[0].res)</script></b></td>
     <td class="in-decimal right" style="color:green"><script>document.writeln(result[1].amount)</script></td>
    <td class="in-decimal" style="color:red"><script>document.writeln(result[1].of_charges!=0?"<form method='post' action='<?php echo $this->webroot?>chargereports/view_payments' style='display:none' id='form1'><input name='ids' value="+result[1].ids+"></form><a href='javascript:void(0)' onclick='document.getElementById(\"form1\").submit();'>"+result[1].of_charges+"</a>":"<font color='red'>"+result[1].of_charges+"</font>")</script></td>
    <td class="in-decimal right" style="color:green"><script>document.writeln(result[3].amount)</script></td>
    <td><script>document.writeln(result[3].of_charges != 0?"<form method='post' action='<?php echo $this->webroot?>chargereports/view_payments' style='display:none' id='form2'><input name='ids' value="+result[3].ids+"></form><a href='javascript:void(0)' onclick='document.getElementById(\"form2\").submit();'>"+result[3].of_charges+"</a>":"<font color='red'>"+result[3].of_charges+"</font>")</script></td>
    <td class="in-decimal right" style="color:green"><script>document.writeln(result[2].amount)</script></td>
    <td><script>document.writeln(result[2].of_charges != 0?"<form method='post' action='<?php echo $this->webroot?>chargereports/view_payments' style='display:none' id='form3'><input name='ids' value="+result[2].ids+"></form><a href='javascript:void(0)' onclick='document.getElementById(\"form3\").submit();'>"+result[2].of_charges+"</a>":"<font color='red'>"+result[2].of_charges+"</font>")</script></td>
    <td class="in-decimal right" style="color:green"><script>document.writeln(result[2].amount_suc)</script></td>
    <td><script>document.writeln(result[2].of_charges_suc !=0?"<form method='post' action='<?php echo $this->webroot?>chargereports/view_payments' style='display:none' id='form4'><input name='ids' value="+result[2].ids_suc+"></form><a href='javascript:void(0)' onclick='document.getElementById(\"form4\").submit();'>"+result[2].of_charges_suc+"</a>":"<font color='red'>"+result[2].of_charges_suc+"</font>")</script></td>
    <td style="color:green" class="in-decimal right"><script>document.writeln(result[2].amount_failure)</script></td>
    <td><script>document.writeln(result[2].of_charges_failure != 0?"<form method='post' action='<?php echo $this->webroot?>chargereports/view_payments' style='display:none' id='form5'><input name='ids' value="+result[2].ids_failure+"></form><a href='javascript:void(0)' onclick='document.getElementById(\"form5\").submit();'>"+result[2].of_charges_failure+"</a>":"<font color='red'>"+result[2].of_charges_failure+"</font>")</script></td>
</tr>
</tbody>
</table>

<table class="list nowrap with-fields">
<thead>
            
    <tr>
                        <td class="cset-1" colspan="2"></td>
        <td class="cset-2" colspan="2"><?php echo __('syscard')?></td>
        <td class="cset-2" colspan="2"><?php echo __('othercharge')?></td>
        <td class="cset-6 last" colspan="6"><span><?php echo __('platform')?></span></td>
    </tr>
    <tr>
        <td class="cset-1"><?php echo __('date')?></td>
        <td class="cset-1"><?php echo __('Reseller')?></td>
        <td class="cset-1"><?php echo __('chargeamountfilter')?></td>
        <td class="cset-1"><?php echo __('ofchargesfilter')?></td>
        <td class="cset-1"><?php echo __('chargeamountfilter')?></td>
        <td class="cset-1"><?php echo __('ofchargesfilter')?></td>
        <td class="cset-1"><?php echo __('chargeamountfilter')?></td>
        <td class="cset-1"><?php echo __('ofchargesfilter')?></td>
        <td class="cset-1"><?php echo __('chargeamountsucfilter')?></td>
        <td class="cset-1"><?php echo __('ofcahrgessucfilter')?></td>
        <td class="cset-1"><?php echo __('chargeamountfailfilter')?></td>
        <td class="cset-1"><?php echo __('ofcahrgesfailfilter')?></td>
    </tr>
</thead>
<tbody class="rows" id="reporttab">
  <tr class="row-1">
     <td style="font-weight:bold;color:green;"><script>document.writeln(result[0].st+" ---- "+result[0].et.substring(0,result[0].et.indexOf("+")))</script></td>
   	 <td><b style="color:red;"><script>document.writeln(result[0].res)</script></b></td>
     <td class="in-decimal right" style="color:green"><script>document.writeln(result[1].amount_filter)</script></td>
    <td class="in-decimal" style="color:red"><script>document.writeln(result[1].of_charges_filter!=0?"<form method='post' action='<?php echo $this->webroot?>chargereports/view_payments' style='display:none' id='filter_form1'><input name='ids' value="+result[1].ids_filter+"></form><a href='javascript:void(0)' onclick='document.getElementById(\"filter_form1\").submit();'>"+result[1].of_charges_filter+"</a>":"<font color='red'>"+result[1].of_charges_filter+"</font>")</script></td>
    <td class="in-decimal right" style="color:green"><script>document.writeln(result[3].amount_filter)</script></td>
    <td><script>document.writeln(result[3].of_charges_filter != 0?"<form method='post' action='<?php echo $this->webroot?>chargereports/view_payments' style='display:none' id='filter_form2'><input name='ids' value="+result[3].ids_filter+"></form><a href='javascript:void(0)' onclick='document.getElementById(\"filter_form2\").submit();'>"+result[3].of_charges_filter+"</a>":"<font color='red'>"+result[3].of_charges_filter+"</font>")</script></td>
    <td class="in-decimal right" style="color:green"><script>document.writeln(result[2].amount_filter)</script></td>
    <td><script>document.writeln(result[2].of_charges_filter != 0?"<form method='post' action='<?php echo $this->webroot?>chargereports/view_payments' style='display:none' id='filter_form3'><input name='ids' value="+result[2].ids_filter+"></form><a href='javascript:void(0)' onclick='document.getElementById(\"filter_form3\").submit();'>"+result[2].of_charges_filter+"</a>":"<font color='red'>"+result[2].of_charges_filter+"</font>")</script></td>
    <td class="in-decimal right" style="color:green"><script>document.writeln(result[2].amount_suc_filter)</script></td>
    <td><script>document.writeln(result[2].of_charges_suc_filter !=0?"<form method='post' action='<?php echo $this->webroot?>chargereports/view_payments' style='display:none' id='filter_form4'><input name='ids' value="+result[2].ids_suc_filter+"></form><a href='javascript:void(0)' onclick='document.getElementById(\"filter_form4\").submit();'>"+result[2].of_charges_suc_filter+"</a>":"<font color='red'>"+result[2].of_charges_suc_filter+"</font>")</script></td>
    <td style="color:green" class="in-decimal right"><script>document.writeln(result[2].amount_failure_filter)</script></td>
    <td><script>document.writeln(result[2].of_charges_failure_filter != 0?"<form method='post' action='<?php echo $this->webroot?>chargereports/view_payments' style='display:none' id='filter_form5'><input name='ids' value="+result[2].ids_failure_filter+"></form><a href='javascript:void(0)' onclick='document.getElementById(\"filter_form5\").submit();'>"+result[2].of_charges_failure_filter+"</a>":"<font color='red'>"+result[2].of_charges_failure_filter+"</font>")</script></td>
</tr>
</tbody>
</table>
 
 
 
 <div class="group-title bottom">
 <img width="16" height="16" src="<?php echo $this->webroot?>images/charts.png"> 
 <a onclick="$('#charts_holder').toggle();return false;" href="#"><?php echo __('viewcharts')?> »</a>
 </div>
 
 
 
 
 
 
 
 <?php //******************************统计图报表totalcost********************************?>
       
        <div style="display: none" id="charts_holder">
        
        
         <?php //****总价格报表1************?>
 <div       class="group-title bottom"  >
 
 <a onclick="$('#charts_holder').toggle();return false;" href="#"><?php echo __('viewcharts')?></a>
 
 
 </div>  
  
<div id="chart_9be11_div" class="amChart">
<div id="chart_9be11_div_inner" class="amChartInner">

<script type="text/javascript" src="<?php echo $this->webroot?>amcolumn/swfobject.js"></script>
	<div id="flashcontent">
		<strong>You need to upgrade your Flash Player</strong>
	</div>

	<script type="text/javascript">
	 // <![CDATA[
	 var so = new SWFObject("<?php echo $this->webroot?>amstock/amstock.swf", "amstock", "100%", "450", "8", "#FFFFFF");
	 so.addVariable("path", "<?php echo $this->webroot?>amstock/");
	
	 //so.addVariable("chart_settings", encodeURIComponent("<settings><width>100%</width><height>300</height><preloader_on_reload>1</preloader_on_reload><redraw>1</redraw><digits_after_decimal>2</digits_after_decimal><background><alpha>100</alpha><border_alpha>20</border_alpha></background><grid><category><dashed>1</dashed></category><value><dashed>1</dashed></value></grid><axes><category><width>1</width><color>E7E7E7</color></category><value><width>1</width><color>E7E7E7</color></value></axes><values><value><min>0</min></value></values><depth>25</depth><column><width>85</width><balloon_text>{title}: {value} EUR</balloon_text><grow_time>0</grow_time></column><balloon><text_size>15</text_size><corner_radius>6</corner_radius><max_width>400</max_width><text_color>000000</text_color></balloon><labels><label><text>Total Cost, EUR</text><x>20</x><y>20</y><text_size>20</text_size></label></labels><graphs><graph gid='0'><color>EB690C</color></graph></graphs></settings>"));
	 //so.addVariable("chart_data",encodeURIComponent("<chart><series><value xid='0'>origination</value><value xid='1'>termination</value></series><graphs><graph gid='0' title='Total Cost'><value xid='0'>88888.0</value><value xid='1'>755665.28</value></graph></graphs></chart>"));
	
	
	
	 //so.addVariable("settings_file", encodeURIComponent("<?php echo $this->webroot?>amcolumn/amcolumn_settings_cost.xml"));
	 // so.addVariable("data_file", encodeURIComponent("<?php echo $this->webroot?>amcolumn/amcolumn_data_cost.xml"));
	 //so.addVariable("preloader_color", "#ffffff");
	
	 so.addVariable("settings_file", encodeURIComponent("<?php echo $this->webroot?>amstock/amstock_settings.xml"));
	 so.write("flashcontent");//将flash放入div
	
	
	 // ]]> 
	</script>
<!-- end of amcolumn script -->

</div>
</div>

  </div>

<?php //***********************报表查询参数*********************?>
<fieldset class="query-box"><legend><?php echo __('query')?></legend>
<form onsubmit="loading();" method="post">
<table style="width: 960px;" class="form">
<col style="width: 80px;">
<col style="width: 240px;">
<col style="width: 80px;">
<col style="width: 230px;">
<col style="width: 80px;">
<col style="width: 170px;">
<tbody><tr class="period-block">
<td class="label"><?php echo __('Reseller')?></td>
<td class="value"><select style="width:200px;"  id="reseller" name="reseller">
<?php
							for ($i=0;$i<count($r_reseller);$i++){ 
						?>
								<option value="<?php echo $r_reseller[$i][0]['reseller_id']?>">
									<?php
										$space = "";
										for ($j=0;$j<$r_reseller[$i][0]['spaces'];$j++) {
											 	$space .= "&nbsp;&nbsp;";
										}
										if ($i==0){
											echo "{$r_reseller[$i][0]['name']}";
										} else {
											echo "&nbsp;&nbsp;".$space."↳".$r_reseller[$i][0]['name'];
										}
									?>
								</option>
							<?php
								} 
							?>
</select></td>
    <td class="label"><?php echo __('start_time',true);?>:</td>
    <td  class="value">
			<input style="width:200px;" readonly class="wdate in-text input" id="st" name="st" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})"/>
			</td>
			<td class="label"><?php echo __('end_time',true);?></td>
			<td class="value">
			<input style="width:200px;" readonly class="wdate in-text input" id="et" name="et" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})"/>
			</td>
			<td class="value">
			<input type="submit" value="<?php echo __('submit')?>"/>
			</td>
</tr>

</tbody></table>
</form>
</fieldset>
<div>

</div></div>
<?php
	} else { 
?>
		<div id="container">
<div id="cover"></div>
<div id="cover_tmp"></div>


<?php //****************************************表格报表?>
<table class="list nowrap with-fields">
<thead>
            
    <tr>
        <td class="cset-1"><?php echo __('date')?></td>
        <td class="cset-1"><?php echo __('Reseller')?></td>
        <td class="cset-1"><?php echo __('chargeamount')?></td>
        <td class="cset-1"><?php echo __('ofcharges')?></td>
    </tr>
</thead>
<tbody class="rows" id="reporttab">
  <tr class="row-1">
     <td style="font-weight:bold;color:green;"><script>document.writeln(result[0].st+" ---- "+result[0].et.substring(0,result[0].et.indexOf("+")))</script></td>
   	 <td><b style="color:red;"><script>document.writeln(result[0].res)</script></b></td>
     <td class="in-decimal right" style="color:green"><script>document.writeln(result[1].amount_filter+result[3].amount_filter+result[2].amount_filter+result[2].amount_suc_filter+result[2].amount_failure_filter)</script></td>
    <td class="in-decimal" style="color:red"><script>document.writeln(result[1].of_charges_filter+result[3].of_charges_filter+result[2].of_charges_filter+result[2].of_charges_suc_filter+result[2].of_charges_failure_filter!=0?"<form method='post' action='<?php echo $this->webroot?>chargereports/view_payments' style='display:none' id='form1'><input name='ids' value="+result[1].ids_filter+","+result[3].ids_filter+","+result[2].ids_filter+","+result[2].ids_suc_filter+","+result[2].ids_failure_filter+"></form><a href='javascript:void(0)' onclick='document.getElementById(\"form1\").submit();'>"+(result[1].of_charges_filter+result[3].of_charges_filter+result[2].of_charges_filter+result[2].of_charges_suc_filter+result[2].of_charges_failure_filter)+"</a>":"<font color='red'>0</font>")</script></td>
</tr>


</tbody>
</table>
 
 
<!--   <div class="group-title bottom">
 <img width="16" height="16" src="<?php echo $this->webroot?>images/charts.png"> 
 <a onclick="$('#charts_holder').toggle();return false;" href="#"><?php echo __('viewcharts')?> »</a>
 </div>-->
 
 
 
 
 
 
 
 <?php //******************************统计图报表totalcost********************************?>
 <div style="display: none;" id="charts_holder">
        
        
         <?php //****价格报表1************?>

<div id="chart_9be11_div" class="amChart">
<div id="chart_9be11_div_inner" class="amChartInner" style="width:50%;float:left;">

<script type="text/javascript" src="<?php echo $this->webroot?>amcolumn/swfobject.js"></script>
	<div id="flashcontent">
		<strong></strong>
	</div>
		<script type="text/javascript">
		// <![CDATA[		
		var so = new SWFObject("<?php  echo  $this->webroot?>amcolumn/ampie.swf", "amcolumn", "100%", "280", "8", "#000000");
		so.addVariable("path", "<?php echo  $this->webroot?>amcolumn/");
		so.addVariable("chart_settings", encodeURIComponent("<settings><width>100%</width><background><alpha>100</alpha><border_alpha>20</border_alpha></background><balloon><show>{title}: {value} ({percents}%)</show><text_color>000000</text_color><text_size>15</text_size><max_width>400</max_width><corner_radius>6</corner_radius></balloon><legend><align>center</align></legend><pie><inner_radius>40</inner_radius><height>20</height><angle>20</angle><outline_alpha>50</outline_alpha><alpha>80</alpha><hover_brightness>30</hover_brightness><gradient_ratio>-50,0,0,-50</gradient_ratio></pie><animation><start_radius>0%</start_radius><pull_out_time>1.5</pull_out_time><pull_out_effect>strong</pull_out_effect><pull_out_radius>25%</pull_out_radius></animation><data_labels><show>{title}: {percents}%</show></data_labels><labels><label lid='0'><text><?php echo __('charges')?></text><x>20</x><y>20</y><text_size>20</text_size></label></labels></settings>"));
		so.addVariable("chart_data",encodeURIComponent("<pie><slice title='<?php echo __('systemrefillcardtotal',true)."(".__('money',true).")"?>'>"+result[1].amount_filter+"</slice><slice title='<?php echo __('proxyfillcardtotal')?>'>"+result[2].amount_filter+"</slice><slice title='<?php echo __('otheramount')?>'>"+result[3].amount_filter+"</slice></pie>"));
		so.addVariable("preloader_color", "#ffffff");
		so.write("flashcontent");
		// ]]>
	</script>
<!-- end of amcolumn script -->



</div>

<div id="chart_9bde11_div_inner" class="amChartInner">
<div id="flashcontent1" style="width:50%;float:left;">
		<strong></strong>
	</div>

	<script type="text/javascript">
		// <![CDATA[		
		var so = new SWFObject("<?php  echo  $this->webroot?>amcolumn/amcolumn.swf", "amcolumn", "100%", "280", "8", "#000000");
		so.addVariable("path", "<?php echo  $this->webroot?>amcolumn/");

		so.addVariable("chart_settings", encodeURIComponent("<settings><width>100%</width><height>280</height><preloader_on_reload>1</preloader_on_reload><redraw>1</redraw><digits_after_decimal>2</digits_after_decimal><background><alpha>100</alpha><border_alpha>20</border_alpha></background><grid><category><dashed>1</dashed></category><value><dashed>1</dashed></value></grid><axes><category><width>1</width><color>E7E7E7</color></category><value><width>1</width><color>E7E7E7</color></value></axes><values><value><min>0</min></value></values><depth>25</depth><column><width>85</width><balloon_text>{title}: {value}</balloon_text><grow_time>0</grow_time></column><balloon><text_size>15</text_size><corner_radius>6</corner_radius><max_width>400</max_width><text_color>000000</text_color></balloon><labels><label><text><?php echo __('ofcharge')?></text><x>20</x><y>20</y><text_size>20</text_size></label></labels><graphs><graph gid='0'><color>EB690C</color></graph></graphs></settings>"));
		so.addVariable("chart_data",encodeURIComponent("<chart><series><value xid='0'><?php echo __('syscardcharges')?></value><value xid='1'><?php echo __('platformcharges')?></value><value xid='2'><?php echo __('othercharges')?></value><value xid='3'><?php echo __('platformchargessuc')?></value><value xid='4'><?php echo __('platformchargesfail')?></value></series><graphs><graph gid='0' title='<?php echo __('syscardcharges')?>'><value xid='0'>"+result[1].of_charges_filter+"</value><value xid='1' title='<?php echo __('platformcharges')?>'>"+result[2].of_charges_filter+"</value><value xid='2' title='<?php echo __('othercharges')?>'>"+result[3].of_charges_filter+"</value><value xid='3' title='<?php echo __('platformchargessuc')?>'>"+result[2].of_charges_suc_filter+"</value><value xid='4' title='<?php echo __('platformchargesfail')?>'>"+result[2].of_charges_failure_filter+"</value></graph></graphs></chart>"));
		so.addVariable("preloader_color", "#ffffff");
		so.write("flashcontent1");
		// ]]>
	</script>
	</div>
</div>
</div>

<?php //***********************报表查询参数*********************?>
<fieldset class="query-box"><legend><?php echo __('query')?></legend>
<form onsubmit="loading();" method="post">
<table style="width: 960px;" class="form">
<col style="width: 80px;">
<col style="width: 240px;">
<col style="width: 80px;">
<col style="width: 230px;">
<col style="width: 80px;">
<col style="width: 170px;">
<tbody><tr class="period-block">
<td class="label"><?php echo __('Reseller')?></td>
<td class="value"><select style="width:200px;"  id="reseller" name="reseller"></select></td>
    <td class="label"><?php echo __('start_time',true);?>:</td>
    <td  class="value">
			<input style="width:200px;" readonly class="wdate in-text input" id="st" name="st" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})"/>
			</td>
			<td class="label"><?php echo __('end_time',true);?></td>
			<td class="value">
			<input style="width:200px;" readonly class="wdate in-text input" id="et" name="et" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})"/>
			</td>
			<td class="value">
			<input type="submit" value="<?php echo __('submit')?>"/>
			</td>
</tr>

</tbody></table>
</form>
</fieldset>
<div>

</div></div>
<?php
	} 
?>

<div id="livemargins_control" style="position: absolute; display: none; z-index: 9999;"><img width="77" height="5" style="position: absolute; left: -77px; top: -5px;" src="chrome://livemargins/skin/monitor-background-horizontal.png">	
<img style="position: absolute; left: 0pt; top: -5px;" src="chrome://livemargins/skin/monitor-background-vertical.png">
	<img style="position: absolute; left: 1px; top: 0pt; opacity: 0.5; cursor: pointer;" onmouseout="this.style.opacity=0.5" onmouseover="this.style.opacity=1" src="chrome://livemargins/skin/monitor-play-button.png" id="monitor-play-button"></div>
	<script type="text/javascript" src="<?php echo $this->webroot?>js/My97DatePicker/WdatePicker.js"></script>