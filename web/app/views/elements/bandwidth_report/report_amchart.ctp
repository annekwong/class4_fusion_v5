
<div class="group-title bottom"><a onclick="$('#charts_holder').toggle();return false;" href="#">
<?php	echo __ ( 'viewcharts' )?></a>


</div>


<div style="display: none;" id="charts_holder">
<fieldset  style="display: block; border-top: 1px solid rgb(181, 199, 216);">
<legend style="color: rgb(125, 133, 142); font-size: 1.1em; font-weight: bold;">

	</legend></fieldset>
<div id="chart_9be11_div" class="amChart">
<div id="chart_9be11_div_inner" class="amChartInner">
<script	type="text/javascript" src="<?php 	echo $this->webroot?>amcolumn/swfobject.js"></script>
	
	
	
<div id="flashcontent">
<strong><?php __('You need to upgrade your Flash Player')?></strong>
</div>
<script type="text/javascript">
	 var so = new SWFObject("<?php echo $this->webroot?>amstock/amstock.swf", "amstock", "100%", "450", "8", "#FFFFFF");
	 so.addVariable("path", "<?php	echo $this->webroot?>amstock/");
	 so.addVariable("settings_file", encodeURIComponent("<?php echo $this->webroot?>bandwidths/flash_setting.xml"));
	 so.write("flashcontent");//将flash放入div
	</script>
	
	
	


	</div>
</div>
</div>