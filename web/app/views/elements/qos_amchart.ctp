     <div class="group-title bottom"  ><a onclick="$('#charts_holder').toggle();return false;" href="#"><?php echo __('viewcharts')?></a></div>  
     <div style="display: none" id="charts_holder">
			<div id="chart_9be11_div" class="amChart">
			<div id="chart_9be11_div_inner" class="amChartInner">
<script type="text/javascript" src="<?php echo $this->webroot?>amcolumn/swfobject.js"></script>
	<div id="flashcontent">
		<strong><?php __('You need to upgrade your Flash Player')?></strong>
	</div>
	<script type="text/javascript">
	 var so = new SWFObject("<?php echo $this->webroot?>amstock/amstock.swf", "amstock", "100%", "450", "8", "#FFFFFF");
	 so.addVariable("path", "<?php echo $this->webroot?>amstock/");
	 so.addVariable("settings_file", encodeURIComponent("<?php echo $this->webroot?>amstock/amstock_settings.xml?random=<?=substr(md5(microtime()), 0, 5)?>"));
	 so.write("flashcontent");//将flash放入div
	</script>
</div>
</div>
  </div>