<!--导入所有reoprt页面的input和select样式文件-->
<?php echo $this->element('magic_css'); ?>

<ul class="breadcrumb">
    <li><?php echo __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Statistics') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Disconnect Causes') ?></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Disconnect Causes') ?></h4>
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>


<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-head">
            <ul class="tabs">
                <li <?php if ($rate_type == 'org')
{
    echo "class='active'";
} ?>>
                    <a  class="glyphicons left_arrow" href="<?php echo $this->webroot ?>disconnectreports/summary_reports/org/">
                        <i></i><?php echo __('Origination', true); ?>
                    </a>
                </li>
                <li <?php if ($rate_type == 'term')
{
    echo "class='active'";
} ?>>
                    <a  class="glyphicons right_arrow" href="<?php echo $this->webroot ?>disconnectreports/summary_reports/term/">
                        <i></i><?php echo __('Termination', true); ?></a>
                </li>
            </ul>

        </div>
        <div class="widget-body">

            <?php echo $this->element('discon_report/result_table') ?>
            <?php
            //echo $this->element ( 'discon_report/report_amchart' );
            //echo $this->element("report/image_report");
            ?>
            <?php echo $this->element('discon_report/search') ?>
            <?php
            echo $this->element('search_report/search_js_show');
            ?>

            <div>
            </div>

        </div>
        <?php if (isset($send) && !empty($send)): ?>
            <div><?php echo $send; ?></div>
<?php endif; ?>
        <script type="text/javascript">
            <!--
         jQuery(document).ready(function() {
                jQuery('#query-dst_number,#query-interval_from,#query-interval_to').xkeyvalidate({type: 'checkNum'});
            });
//-->
        </script>

        </body>

        </html>