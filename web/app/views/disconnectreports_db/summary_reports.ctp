<!--导入所有reoprt页面的input和select样式文件-->
<?php echo $this->element('magic_css'); ?>
<?php
$user_id = $_SESSION['sst_user_id'];
$res = $cdr_db->query("select * from users where user_id = {$user_id} ");
?>
<ul class="breadcrumb">
    <li><?php echo __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>disconnectreports_db/summary_reports">
        <?php __('Statistics') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot . $this->params['url']['url'] ?>">
        <?php echo __('Disconnect Causes') ?></a></li>
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
                <li <?php if ($rate_type == 'org' && !isset($this->params['pass'][1]))
                {
                    echo "class='active'";
                } ?>>
                    <a  class="glyphicons left_arrow" href="<?php echo $this->webroot ?>disconnectreports_db/summary_reports/org/">
                        <i></i><?php echo __('Origination', true); ?>
                    </a>
                </li>
                <li <?php if ($rate_type == 'org' && isset($this->params['pass'][1]))
                {
                    echo "class='active'";
                } ?>>
                    <a  class="glyphicons left_arrow" href="<?php echo $this->webroot ?>disconnectreports_db/summary_reports/org/1">
                        <i></i><?php echo __('Orig. Disc. Cause by Ingress Trunk', true); ?>
                    </a>
                </li>

                <?php
                if ($res[0][0]['all_termination'] == 't')
                {
                    ?>
                    <li <?php if ($rate_type == 'term' && !isset($this->params['pass'][1]))
                    {
                        echo "class='active'";
                    } ?>>
                        <a  class="glyphicons right_arrow" href="<?php echo $this->webroot ?>disconnectreports_db/summary_reports/term/">
                            <i></i><?php echo __('Termination', true); ?></a>
                    </li>
                    <li <?php if ($rate_type == 'term' && isset($this->params['pass'][1]))
                    {
                        echo "class='active'";
                    } ?>>
                        <a  class="glyphicons right_arrow" href="<?php echo $this->webroot ?>disconnectreports_db/summary_reports/term/1">
                            <i></i><?php echo __('Term. Disc. Cause by Egress Trunk', true); ?>
                        </a>
                    </li>
                <?php
                }
                ?>
            </ul>

        </div>
        <div class="widget-body">


            <?php
            if (isset($this->params['pass'][1]))
                echo $this->element('discon_report_db/result_table_2');
            else
                echo $this->element('discon_report_db/result_table');
            ?>
            <?php
            //echo $this->element ( 'discon_report/report_amchart' );
            //echo $this->element("report/image_report");
            ?>
            <?php echo $this->element('discon_report_db/search') ?>
            <?php
            echo $this->element('search_report/search_js_show');
            ?>



        </div>
    </div>
</div>
<script type="text/javascript">
    <!--
    jQuery(document).ready(function() {
        jQuery('#query-dst_number,#query-interval_from,#query-interval_to').xkeyvalidate({type: 'checkNum'});
    });
    //-->
</script>

</body>

</html>