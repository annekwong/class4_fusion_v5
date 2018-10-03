<!--导入所有reoprt页面的input和select样式文件-->
<?php echo $this->element('magic_css_three');?>

<div id="title">
  <h1>
    <?php __('Statistics')?>
    &gt;&gt;<?php echo __('Bandwidth Report',true);?> </h1>
</div>
<div id="container"> 
<?php echo $this->element('bandwidth_report/result_table')?> 
<?php echo $form->create ('Cdr', array ('type'=>'get','url' => '/bandwidths/summary_reports/' ,'onsubmit'=>"if ($('#query-output').val() == 'web') loading();"));?> 
<?php //echo $this->element('bandwidth_report/report_amchart')?> 
<?php echo $this->element('bandwidth_report/search')?> 
<?php echo $form->end();?>
<?php echo $this->element ( 'search_report/search_js_show' );?>
</div>
