<!--导入所有reoprt页面的input和select样式文件-->
<?php echo $this->element('magic_css_three');?>
<div id="title"> <h1> <?php __('Statistics')?>&gt;&gt;<?php  __('SummaryReport')?></h1> </div>
<div id="container">
<ul class="tabs">
<?php 
		//$report_type=isset($_GET['data']['Cdr']['report_type'])?$_GET['data']['Cdr']['report_type']:'orig';
?>
	  <li   <?php if($report_type=='orig'){  echo "class='active'";}?>><a href="<?php echo $this->webroot?>clientsummarystatis/summary_reports/orig/"><img width="16" height="16" src="<?php echo $this->webroot?>images/list.png"><?php echo __('Origination',true);?></a></li>
	  <li   <?php if($report_type=='term'){  echo "class='active'";}?>><a href="<?php echo $this->webroot?>clientsummarystatis/summary_reports/term/"> <img width="16" height="16" src="<?php echo $this->webroot?>images/menuIcon.gif"><?php echo __('Termination',true);?></a>  </li>
	</ul>
    
<?php
//显示子统计
	if($show_subtotals=='true')
	{
		echo  $this->element('summary_report/sub_total_table') ;
	}
	else
        {               
		echo $this->element('summary_report/common_table');
	}
   ?>
<?php echo $form->create ('Cdr', array ('type'=>'get','url' => "/clientsummarystatis/summary_reports/$report_type" ,'onsubmit'=>"if ($('#query-output').val() == 'web') loading();"));?>

<?php //echo $this->element('summary_report/report_amchart')?>
<!--生成图片报表-->
<?php //echo $this->element("report/image_report")?>
<!--//生成图片报表-->
<?php echo $this->element('summary_report/search')?>  

<?php echo $form->end();?>

<?php	echo $this->element ( 'search_report/search_js_show' );?>

</div>