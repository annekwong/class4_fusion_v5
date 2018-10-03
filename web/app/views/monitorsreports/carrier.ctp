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


<ul class="breadcrumb">
    <li><?php __('You are here')?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Statistics') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('QoS Monitor') ?></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Statistics'); ?>&gt;&gt;<?php echo Inflector::humanize($h_title) ?> <?php __('Report')?> </h4>
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>

<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-head">
            <?php echo $this->element('qos/qos_tab', array('active_tab' => $this->params['pass'][0])) ?> 
        </div>
        <div class="widget-body">
            <div class="filter-bar">
                <form method="get">
                    <!-- Filter -->
                    <div>
                        <label><?php echo __('Refresh Every', true); ?>:</label>
                        <select id="changetime">
                            <option value="180">3 <?php __('minutes')?></option>
                            <option value="300">5 <?php __('minutes')?></option>
                            <option value="800">15<?php __('minutes')?></option>
                        </select>
                    </div>
                    <!-- // Filter END -->
                    <!-- Filter -->

                    <!-- // Filter END -->
                </form>
            </div>

            <div class="clearfix"></div>
            <?php echo $this->element('carrier/table'); ?>
        </div>
    </div>
</div>












<script type="text/javascript">

    $(function() {
        var interv = null;

        $('#changetime').change(function() {
            if (interv)
                window.clearInterval(interv);
            var time = $(this).val() * 1000;
            interv = window.setInterval("loading();window.location.reload()", time);
        });

        $('#changetime').change();
    });

</script>
