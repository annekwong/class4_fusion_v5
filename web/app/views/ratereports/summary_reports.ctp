<!--导入所有reoprt页面的input和select样式文件-->
<?php echo $this->element('magic_css_three');?>

<div id="title"> <h1><?php __('Statistics')?>&gt;&gt;<?php __('UsageReport')?></h1> </div>
<div id="container" >
	<ul class="tabs">
	  <li   <?php if($rate_type=='org'){  echo "class='active'";}?>><a href="<?php echo $this->webroot?>ratereports/summary_reports/org/"><img width="16" height="16" src="<?php echo $this->webroot?>images/list.png"><?php __('Origination')?></a></li>
	  <li   <?php if($rate_type=='term'){  echo "class='active'";}?>><a href="<?php echo $this->webroot?>ratereports/summary_reports/term/"> <img width="16" height="16" src="<?php echo $this->webroot?>images/menuIcon.gif"><?php __('Termination')?></a>  </li>
	</ul>
 
<?php echo  $this->element('rate_report/result_table')?>
<?php  // echo  $this->element('rate_report/report_amchart')?>
<!--生成图片报表-->
<?php //echo $this->element("report/image_report")?>
<!--//生成图片报表-->
<?php   echo  $this->element('rate_report/search')?>
<?php 	echo $this->element('search_report/search_js_show');?>


</div>
<div></div>

<script type="text/javascript">
<!--
		jQuery(document).ready(function(){
			jQuery('#CdrSourceNumber,#CdrDestinationNumber,#query-interval_from,#query-interval_to,#query-src_number,#query-dst_number').xkeyvalidate({type:'checkNum'}).attr('maxLength','16');	
		});
//-->
</script>
