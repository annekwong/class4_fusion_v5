<!--导入所有reoprt页面的input和select样式文件-->
<?php echo $this->element('magic_css_three');?>
<body>
<div id="title">
<h1><?php __('Statistics')?>&gt;&gt;
 <font color= 'red' title="Name"> </font>
CDR Rerating
</h1>

</div>
<div id="container">
<?php
if (isset ( $exception_msg ) && $exception_msg) :	?>
	<?php	echo $this->element ( 'common/exception_msg' );?>		
<?php endif;?>
<?php echo $this->element('report/real_period')?>
<?php echo $this->element('cdr_rerate/result_table')?>
<?php echo $this->element('cdr_rerate/query_box');
?>
<div style="display: none;" id="charts_holder">
<script	type="text/javascript">


//<![CDATA[
function showClients_term ()
{
    ss_ids_custom['client_term'] = _ss_ids_client_term;
    winOpen('<?php echo $this->webroot?>clients/ss_client_term?types=2&type=0', 500, 530);

}

tz = $('#query-tz').val();
function showClients ()
{
    ss_ids_custom['client'] = _ss_ids_client;
    winOpen('<?php echo $this->webroot?>clients/ss_client?types=2&type=0', 500, 530);
}



function repaintOutput() {
    if ($('#query-output').val() == 'web') {
        $('#output-sub').show();
    } else {
        $('#output-sub').hide();
			}
}
repaintOutput();
//]]>
</script></div>
<script type="text/javascript"
	src="<?php echo $this->webroot?>js/My97DatePicker/WdatePicker.js"></script>
<script type="text/javascript">
<!--
jQuery(document).ready(function(){
	 jQuery('#query-dst_number,#query-src_number,#query-interval_from,#query-interval_to').xkeyvalidate({type:'checkNum'});	
});
  
//-->
</script>
</div>