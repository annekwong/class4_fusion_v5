<!--导入所有reoprt页面的input和select样式文件-->
<?php echo $this->element('magic_css'); ?>

<ul class="breadcrumb">
    <li><?php echo __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>dips">
        <?php __('Statistics') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot . $this->params['url']['url'] ?>"><?php
        if ($rate_type == 'all')
        {
            echo __('LRN Dipping Record');
            echo (empty($name) || $name == '') ? "  " : " <font color ='red' title='Name'>[" . $name . "]</font>";
        }
        ?>
        <?php
        if ($rate_type == 'spam')
        {
            echo "Spam Report ";
        }
        ?></a></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"> <?php
        if ($rate_type == 'all')
        {
            echo "LRN Dipping Record";
            echo (empty($name) || $name == '') ? "  " : " <font color ='red' title='Name'>[" . $name . "]</font>";
        }
        ?>
        <?php
        if ($rate_type == 'spam')
        {
            echo "Spam Report ";
        }
        ?></h4>
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>

<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-head">
            <ul>
                <li class="active">
                    <a href="<?php echo $this->webroot; ?>dips/index"  class="glyphicons list">
                        <i></i><?php __('LRN Dipping Record')?>
                    </a>
                </li>
                <li>
                    <a href="<?php echo $this->webroot; ?>dips/export_log" class="glyphicons book_open">
                        <i></i><?php __('LRN Dipping Record Export Log')?>
                    </a>
                </li>
            </ul>


        </div>
        <div class="widget-body">
            <div  id="refresh_div">
                <?php if (isset($exception_msg) && $exception_msg) : ?>
                    <?php echo $this->element('common/exception_msg'); ?>		
                <?php endif; ?>
                <?php echo $this->element('report_db/real_period') ?>
                <?php echo $this->element('report_db/cdr_report/cdr_table2') ?>
            </div>
            <!--生成图片报表-->
            <?php //echo $this->element("report/image_report") ?>
            <!--//生成图片报表-->
            <?php
            if ($_SESSION['login_type'] == 1)
            {
                echo $this->element('report_db/cdr_report/query_box_admin_1');
            }
            else
            {

                echo $this->element('report_db/cdr_report/query_box_carrier');
            }
            ?>
            <div style="display: none;" id="charts_holder">
                <script	type="text/javascript">


                    //<![CDATA[
                    function showClients_term()
                    {
                        ss_ids_custom['client_term'] = _ss_ids_client_term;
                        winOpen('<?php echo $this->webroot ?>clients/ss_client_term?types=2&type=0', 500, 530);

                    }

                    tz = $('#query-tz').val();
                    function showClients()
                    {
                        ss_ids_custom['client'] = _ss_ids_client;
                        winOpen('<?php echo $this->webroot ?>clients/ss_client?types=2&type=0', 500, 530);
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


            <?php echo $this->element('spam/refresh_spam_js') ?>
        </div>
    </div></div>