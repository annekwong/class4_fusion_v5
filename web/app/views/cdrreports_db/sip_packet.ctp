<ul class="breadcrumb">
    <li><?php __('You are here')?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>cdrreports_db/sip_packet">
        <?php __('Tools') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>cdrreports_db/sip_packet">
        <?php echo __('SIP PACKET Search') ?></a></li>
</ul>
<div class="clearfix"></div>
<div class="innerLR">
    <div class="widget widget-tabs widget-body-white">
        <div class="widget-head">
            <ul>
                <li class="active" ><a href="<?php echo $this->webroot; ?>cdrreports_db/sip_packet" class="glyphicons left_arrow"><i></i><?php __('SIP PACKET'); ?></a></li>
                <li><a href="<?php echo $this->webroot; ?>cdrreports_db/sip_requests" class="glyphicons right_arrow"><i></i><?php __('SIP Requests'); ?></a></li>
            </ul>
        </div>
        <div class="widget-body">
            <div  id="refresh_div">
                <?php
                if (isset ( $exception_msg ) && $exception_msg) :	?>
                    <?php	echo $this->element ( 'common/exception_msg' );?>
                <?php endif;?>
                <?php if($_SESSION['login_type']==3): ?>
                    <?php echo $this->element('report_db/cdr_report/query_box_carrier',array('query_type' => 1)); ?>
                    <hr />
                <?php elseif($_SESSION['login_type']==2): ?>
                    <?php echo $this->element('report_db/cdr_report/query_box_sip_agent'); ?>
                <?php endif; ?>
                <?php if($show_nodata): ?>
                    <?php echo $this->element('report_db/real_period')?>
                <?php endif; ?>
                <?php echo $this->element('report_db/cdr_report/cdr_table')?>
            </div>
            <!--生成图片报表-->
            <?php //echo $this->element("report/image_report")?>
            <!--//生成图片报表-->
            <?php
            if($_SESSION['login_type']==1)
            {
                if ($rate_type == 'spam')
                {
                    echo $this->element('report_db/cdr_report/query_box_admin_2');
                }
                else
                {
                    echo $this->element('report_db/cdr_report/query_box_sip');
                }
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


            <?php echo  $this->element('spam/refresh_spam_js');?>
        </div>
    </div>
</div>
<script>
    $(function(){
        $.sort_select('#query-res_status_ingress');
        $.sort_select('#query-res_status');
        $.sort_select('#cdr_release_cause');
    })
</script>