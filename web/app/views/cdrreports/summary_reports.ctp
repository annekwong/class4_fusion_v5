                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                       <!--导入所有reoprt页面的input和select样式文件-->
<?php echo $this->element('magic_css');?>
                                                                                                                                                                                                        
<ul class="breadcrumb">
    <li><?php __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Statistics') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <?php if ($session->read('login_type') == 3): ?>
    <li><?php echo __('Reports') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo $cdr_type == 'term_service_buy' ? 'Term. Service Buy' : 'Term. Service Sell';?></li>
    <?php else: ?>
    <li><?php if($rate_type=='all'){   __('CDRs List'); 
 echo  (empty($name)||$name=='')? "  ":" <font class='editname'>[".$name."]</font>";
 }?>
 <?php if($rate_type=='spam'){  
 	        echo "Spam Report ";
           
 }?></li>
    <?php endif; ?>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php if ($session->read('login_type') == 3): ?><?php echo $cdr_type == 'term_service_buy' ? 'Term. Service Buy' : 'Term. Service Sell';?><?php else: ?><?php if($rate_type=='all'){   __('CDRs List'); 
 echo  (empty($name)||$name=='')? "  ":" <font class='editname'>[".$name."]</font>";
 }?>
 <?php if($rate_type=='spam'){  
 	        echo "Spam Report ";
           
 }?><?php endif; ?></h4>
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>
                                                                                                                                                                                                        
<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-head">
            <?php if($rate_type != 'spam'): ?>
    
            <?php if ($session->read('login_type') == 3): ?>

                <?php echo $this->element('report/cdr_report/cdr_report_user_tab', array('active' => $cdr_type))?>
            <?php else: ?>

            <ul>
                <li class="active">
                    <a href="<?php echo $this->webroot; ?>cdrreports/summary_reports"  class="glyphicons list">
                        <?php __('CDR Search') ?> <i></i>
                    </a>
                </li>
                <li>
                    <a href="<?php echo $this->webroot; ?>cdrreports/cdr_list_export" class="glyphicons book_open">
                        <i></i><?php __('CDR Export Log') ?>
                    </a>
                </li>
                
                <li>
                    <a href="<?php echo $this->webroot; ?>cdrreports/cdr_list_export" class="glyphicons book_open">
                        <i></i><?php __('CDR Export Log') ?>
                    </a>
                </li>

<!--                <li>
                    <a href="<?php echo $this->webroot; ?>cdrreports/mail_send_log" class="glyphicons no-js e-mail" >
                        <i></i> Mail CDR Log
                    </a>
                </li>-->
            </ul>
            <?php endif; ?>

            <?php endif; ?>
        </div>
        <div class="widget-body">
    
  
    
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
<?php if(isset($send) && !empty($send)): ?>
    <div><?php echo $send; ?></div>
    <?php endif; ?>
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
    </div>
</div>