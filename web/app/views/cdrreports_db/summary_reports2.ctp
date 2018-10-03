<style>
    #stats-period{display: inline-block}
    input[type="text"]{width: 220px;}
</style>
<!--导入所有reoprt页面的input和select样式文件-->
<?php echo $this->element('magic_css');?>
<ul class="breadcrumb">
    <li><?php __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>cdrreports_db/summary_reports2">
        <?php __('Statistics') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <?php if ($session->read('login_type') == 3): ?>
        <li><a href="<?php echo $this->webroot . $this->params['url']['url'] ?>">
            <?php echo __('CDR') ?></a></li>
        <li class="divider"><i class="icon-caret-right"></i></li>
        <li><a href="<?php echo $this->webroot . $this->params['url']['url'] ?>">
            <?php echo $cdr_type == 'term_service_buy' ? 'Ingress' : 'Egress';?></a></li>
    <?php else: ?>
        <li>
            <a href="<?php echo $this->webroot . $this->params['url']['url'] ?>">
            <?php if($rate_type=='all'){   
                __('CDRs Search');
            }?>
            <?php if($rate_type=='spam'){
                echo "Spam Report ";

            }?></a></li>
    <?php endif; ?>
</ul>
<div class="heading-buttons">
    <h4 class="heading">
        <?php if($rate_type=='all') { __('CDRs Search'); }?>
        <?php if($rate_type=='spam'){ echo "Spam Report "; }?>
    </h4>
</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
    <a class="btn btn-icon glyphicons btn-inverse circle_arrow_left" onclick="history.go(-1);">
        <i></i>
        <?php __('Back') ?>
    </a>
</div>
<div class="clearfix"></div>
<div class="innerLR">
    <div class="widget widget-tabs widget-body-white">
        <div class="widget-head">
            <?php if($rate_type != 'spam'): ?>

                <?php if ($session->read('login_type') == 3): ?>

                    <?php echo $this->element('report_db/cdr_report/cdr_report_user_tab', array('active' => $cdr_type))?>
                <?php else: ?>

                    <ul>
                        <li class="active">
                            <a href="<?php echo $this->webroot; ?>cdrreports_db/summary_reports2" class="glyphicons list">
                                <i></i>
                                <?php __('CDR Search') ?>
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo $this->webroot; ?>cdrreports_db/export_log" class="glyphicons book_open">
                                <i></i>
                                <?php __('CDR Export Log') ?>
                            </a>
                        </li>

<!--                        <li>-->
<!--                            <a href="--><?php //echo $this->webroot; ?><!--cdrreports_db/consolidated_cdr" class="glyphicons book_open">-->
<!--                                <i></i>-->
<!--                                --><?php //__('Consolidated Cdr') ?>
<!--                            </a>-->
<!--                        </li>-->
                        <!--<li>
            <a href="<?php echo $this->webroot; ?>cdrreports_db/mail_send_log" class="glyphicons no-js e-mail" >
                <i></i>
                Mail CDR Log
            </a>
        </li>-->
                    </ul>
                <?php endif; ?>

            <?php endif; ?>
        </div>
        <div class="widget-body">
            <?php if($_SESSION['login_type']==3){
                echo $this->element('report_db/cdr_report/query_box_carrier');
            }elseif($_SESSION['login_type'] == 2){
                echo $this->element('report_db/cdr_report/query_box_agent');
            }
            ?>
            <div  id="refresh_div">
                <?php
                if (isset ( $exception_msg ) && $exception_msg) :	?>
                    <?php	echo $this->element ( 'common/exception_msg' );?>
                <?php endif;?>
                <?php if($show_nodata): ?>
                    <?php if(isset($results_count) && $results_count): ?>
                    <?php echo $this->element('report_db/real_period',array('is_cdr' => true))?>
                    <?php else: ?>
                        <?php echo $this->element('report_db/real_period')?>
                    <?php endif; ?>
                <?php endif; ?>
                <?php echo $this->element('report_db/cdr_report/cdr_table')?>
            </div>
            <!--生成图片报表-->
            <?php //echo $this->element("report/image_report")?>
            <!--//生成图片报表-->
            <?php
            if($_SESSION['login_type']== 1){
                if($rate_type == 'spam') {
                    echo $this->element('report_db/cdr_report/query_box_admin_2');
                } else {
                    echo $this->element('report_db/cdr_report/query_box_admin');
                }
            }
//            else{
//                echo $this->element('report_db/cdr_report/query_box_carrier');
//
//            }
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
    </div>
</div>
<script>
    $(function(){

        $('#query-fields').live('click',function(){
            $.cookie('select_all_columns',1, { path: "/"});
        })


    })
</script>