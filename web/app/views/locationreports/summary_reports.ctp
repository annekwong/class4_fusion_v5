<!--导入所有reoprt页面的input和select样式文件-->
<?php echo $this->element('magic_css_three');?>
<?php echo  $this->element('report_title')?>
<div id="container">
<?php echo $this->element("location_report/result_table")?>
<!--生成图片报表-->
<?php //echo $this->element("report/image_report")?>
<!--//生成图片报表-->
<?php  // echo  $this->element('location_report/report_amchart')?>
<?php  echo $this->element("location_report/search")?>

<script type="text/javascript">
//<![CDATA[

function showClients ()
{
    ss_ids_custom['client'] = _ss_ids_client;
    winOpen('<?php echo $this->webroot?>clients/ss_client?types=2&type=0', 500, 530);

}
function repaintOutput() {
    if ($('#query-output').val() == 'web') {
        $('#output-sub').show();
    } else {
        //$('#output-sub').hide();
    }
}
repaintOutput();
//]]>
</script>


</div>
