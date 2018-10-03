                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                       <!--导入所有reoprt页面的input和select样式文件-->
<?php echo $this->element('magic_css');?>
<ul class="breadcrumb">
    <li><?php __('You are here')?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Reports') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('CDRs List') ?></li>
</ul>
    <div class="heading-buttons">
    <h4 class="heading"><?php echo __('Reports',true);?>&gt;&gt;<?php echo $cdr_type == 'orig_service_buy' ? 'Orig. Service Buy' : 'Orig. Service Sell';?></h4>
    
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>
<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-head">
<?php echo $this->element("did_report_tab", array('active' => $cdr_type))?>
        </div>
<div id="title">

</div>
<div id="container">
   
    
<div  id="refresh_div">
<?php
if (isset ( $exception_msg ) && $exception_msg) :	?>
	<?php	echo $this->element ( 'common/exception_msg' );?>		
<?php endif;?>
<?php if($show_nodata): ?>
<?php echo $this->element('report/real_period')?>
<?php endif; ?>
<?php echo $this->element('report/cdr_report/cdr_table')?>
</div>
<!--生成图片报表-->
<?php //echo $this->element("report/image_report")?>
<!--//生成图片报表-->
<?php
if($_SESSION['login_type']==1){
        if($rate_type == 'spam') {
            echo $this->element('report/cdr_report/query_box_admin_2');
        } else {
            echo $this->element('report/cdr_report/query_box_admin');
        }
}else{
	
	echo $this->element('report/cdr_report/query_box_carrier');
}
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


<?php echo  $this->element('spam/refresh_spam_js')?>
</div>