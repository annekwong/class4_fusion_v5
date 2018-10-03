<style type="text/css">
.group-title.bottom {
	-moz-border-radius: 0 0 6px 6px;
	border-top: 1px solid #809DBA;
	margin: 15px auto 10px;
}

.list td.in-decimal {
	text-align: center;
}

.value input,.value select,.value textarea,.value .in-text,.value .in-password,.value .in-textarea,.value .in-select
	{
	-moz-box-sizing: border-box;
	width: 100px;;
}

.list {
	font-size: 1em;
	margin: 0 auto 20px;
	width: 100%;
}

#container .form {
	margin: 0 auto;
	width: 750px;
}
</style>
<div id="title">
<h1><?php echo __('Statistics');?>&gt;&gt;<?php echo Inflector::humanize($h_title)?> Report <!--  <a title="add to smartbar" href="<?php echo $this->webroot?>clients/view">
      <img width="10" height="10" alt="+" src="<?php echo $this->webroot?>images/qb-plus.png"></a>-->
</h1>



<?php echo  $this->element('qos/title_menu_ul');?>

</div>

<div id="container">
<?php echo  $this->element('qos/qos_tab2',array('active_tab'=>$this->params['pass'][0]))?>

<?php echo $this->element('carrier/table2'); ?>
			
			









</div>




