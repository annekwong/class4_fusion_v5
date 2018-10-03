<style>
    input[type="text"],textarea{margin-bottom: 0;}
    textarea{max-width: 800px;}
</style>
<ul class="breadcrumb">
    <li><?php __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>homes/dashboard">
        <?php __('Statistics') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>homes/dashboard">
        <?php echo __('Dashboard') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot . $this->params['url']['url'] ?>">
        <?php
        if($type == 'ingress') {
            echo __('Ingress QoS Monitor');
        } else {
            echo __('Egress QoS Monitor');
        }
        ?></a></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php
        if($type == 'ingress') {
            echo __('Ingress QoS Monitor');
        } else {
            echo __('Egress QoS Monitor');
        }
        ?></h4>
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>


<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-head">
            <ul class="tabs">
                <li>
                    <a href="<?php echo $this->webroot ?>homes/dashboard" class="glyphicons dashboard">
                        <i></i><?php __('Dashboard')?>
                    </a>
                </li>
                <li>
                    <a href="<?php echo $this->webroot ?>homes/report" class="glyphicons stats">
                        <i></i><?php __('Report')?>
                    </a>
                </li>
                <li>
                    <a href="<?php echo $this->webroot ?>homes/search_charts"  class="glyphicons charts">
                        <i></i><?php __('Charts')?>
                    </a>
                </li>
                <li>
                    <a href="<?php echo $this->webroot ?>homes/qos_report"  class="glyphicons notes">
                        <i></i><?php __('Qos Report')?>
                    </a>
                </li>
                <li <?php if($this->params['pass'][0] == 'ingress') echo 'class="active"'; ?>>
                    <a href="<?php echo $this->webroot ?>homes/dashboard_trunk_carriers/ingress"  class="glyphicons eye_open">
                        <i></i><?php __('Ingress Clients Qos')?>
                    </a>
                </li>
                <li <?php if($this->params['pass'][0] != 'ingress') echo 'class="active"'; ?>>
                    <a href="<?php echo $this->webroot ?>homes/dashboard_trunk_carriers/egress"  class="glyphicons eye_open">
                        <i></i><?php __('Egress Clients Qos')?>
                    </a>
                </li>
<!--                <li>-->
<!--                    <a href="--><?php //echo $this->webroot ?><!--homes/alert"  class="glyphicons alarm">-->
<!--                        <i></i>--><?php //__('Alert')?>
<!--                    </a>-->
<!--                </li>-->
            </ul>
        </div>
        <div class="widget-body">
            <div class="filter-bar">
                <form method="get">
                    <div>
                        <label>Resource Name:</label>
                        <input type="text" name="search" id="search-_q" style="height: 24px">
                    </div>
                    <!--
                    <div>
                        <label><?php __('Show Type'); ?>:</label>
                        <select name="not_zero" class="width80">
                            <option value=""><?php __('Show All'); ?></option>
                            <option value="1" <?php if ($appCommon->_get('not_zero',1)): ?>selected='selected'<?php endif; ?>><?php __('Not Zero'); ?></option>
                        </select>
                    </div>
                    -->
                    <div>
                        <button name="submit" class="btn query_btn" style="padding-top: 2px;padding-bottom: 2px;">Query</button>
                    </div>

                    <!-- Filter -->
                    <div class="pull-right">
                        <label><?php echo __('Refresh Every', true); ?>:</label>
                        <select id="changetime" style="height: 24px">
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
            <div style="width: 100%;; margin: 0px ">
                <fieldset>

                    <?php if(empty($data)): ?>
                        <h2 class="msg center"><?php echo __('no_data_found') ?></h2>
                    <?php else: ?>

                        <table class="list list-form footable table table_page_num table-striped tableTools table-bordered  table-white table-primary">
                            <thead>
                            <tr>
                                <th width="10%" rowspan="2" style="padding-bottom: 20px;" rel="0"><?php __('Resource'); ?></th>
                                <th class="cset-1" colspan="4">15 <?php echo __('minutes', true) ?></th>
                                <th colspan="4" class="cset-2">1 <?php echo __('hour', true) ?></th>
                                <th colspan="4" class="cset-3"><span id="ht-100002" class="helptip" rel="helptip">24 <?php echo __('hour', true) ?>s</span><span id="ht-100002-tooltip" class="tooltip">Average successful rate (percent of successful calls)</span></th>
                            </tr>
                            <tr>
                                <th width="6%" class="cset-1" rel="2">&nbsp;<?php echo __('avgduration', true) ?>(s)&nbsp;</th>
                                <th width="6%" class="cset-1" rel="3">&nbsp;<?php echo __('ASR', true) ?>&nbsp;</th>
                                <th width="6%" class="cset-1" rel="2">&nbsp;<?php echo __('calls', true)?>&nbsp;</th>
                                <th width="6%" class="cset-1" rel="3">&nbsp;<?php echo  __('calldelay', true) ?>&nbsp;</th>
                                <th width="6%" class="cset-1" rel="2">&nbsp;<?php echo  __('avgduration', true) ?>(s)&nbsp;</th>
                                <th width="6%" class="cset-1" rel="3">&nbsp;<?php echo  __('ASR', true) ?>&nbsp;</th>
                                <th width="6%" class="cset-1" rel="2">&nbsp;<?php echo  __('calls', true) ?>&nbsp;</th>
                                <th width="6%" class="cset-1" rel="3">&nbsp;<?php echo  __('calldelay', true) ?>&nbsp;</th>
                                <th width="6%" class="cset-1" rel="2">&nbsp;<?php echo  __('avgduration', true) ?>(s)&nbsp;</th>
                                <th width="6%" class="cset-1" rel="3">&nbsp;<?php echo  __('ASR', true) ?>&nbsp;</th>
                                <th width="6%" class="cset-1" rel="2">&nbsp;<?php echo  __('calls', true) ?>&nbsp;</th>
                                <th width="6%" class="cset-1" rel="3">&nbsp;<?php echo  __('calldelay', true) ?>&nbsp;</th>
                            </tr>
                            </thead>
                            <?php foreach ($data as $resource_id => $item) : ?>
                                <tbody>
                                <tr>
                                    <td class="in-decimal">
                                        <?php echo isset($resource_arr[$resource_id]) ? $resource_arr[$resource_id] : ""; ?>
                                    </td>
                                    <td class="in-decimal"><?php echo number_format($item['acd_15'], 2) ?></td>
                                    <td class="in-decimal"><?php echo number_format($item['asr_15'], 2) ?></td>
                                    <td class="in-decimal"><?php echo number_format($item['calls_15'], 0) ?></td>
                                    <td class="in-decimal"><?php echo number_format($item['pdd_15'], 0) ?></td>
                                    <td class="in-decimal"><?php echo number_format($item['acd_1'], 2) ?></td>
                                    <td class="in-decimal"><?php echo number_format($item['asr_1'], 2) ?></td>
                                    <td class="in-decimal"><?php echo number_format($item['calls_1'], 0) ?></td>
                                    <td class="in-decimal"><?php echo number_format($item['pdd_1'], 0) ?></td>
                                    <td class="in-decimal"><?php echo number_format($item['acd_24'], 2) ?></td>
                                    <td class="in-decimal"><?php echo number_format($item['asr_24'], 2) ?></td>
                                    <td class="in-decimal"><?php echo number_format($item['calls_24'], 0) ?></td>
                                    <td class="in-decimal"><?php echo number_format($item['pdd_24'], 0) ?></td>
                                </tr>
                                </tbody>
                                <?php
                            endforeach;
                            ?>
                        </table>
                        <div class="separator row-fluid">
                            <div class="pagination pagination-large pagination-right margin-none">
                                <?php echo $this->element('page'); ?>
                            </div>
                        </div>

                    <?php endif;?>
            </div>
            </fieldset>
        </div>
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