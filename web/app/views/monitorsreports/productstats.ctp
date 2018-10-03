<style type="text/css"></style>


<ul class="breadcrumb">
    <li><?php __('You are here')?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Statistics') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Routing Plan') ?></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Routing Plan') ?></h4>
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>

<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-head">
            <?php echo $this->element('qos/qos_tab', array('active_tab' => 'product')) ?> 
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
                            <option value="800">15 <?php __('minutes')?></option>
                        </select>
                    </div>
                    <!-- // Filter END -->
                    <!-- Filter -->

                    <!-- // Filter END -->
                </form>
            </div>

            <div class="clearfix"></div>
            <div class="separator bottom row-fluid">
                <div class="pagination pagination-large pagination-right margin-none">
                    <?php echo $this->element('page'); ?>
                </div> 
            </div>
            <table class="list list-form   footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
                <thead>
                    <tr>
                        <th width="10%" rowspan="2" style="padding-bottom: 20px;" rel="0"><?php echo __('Product Name', true); ?></th>
                        <th class="cset-1" colspan="4">15 <?php echo __('minutes', true) ?></th>
                        <th colspan="4" class="cset-2">1 <?php echo __('hour', true) ?></th>
                        <th colspan="4" class="cset-3"><span id="ht-100002" class="helptip" rel="helptip">24 <?php echo __('hour', true) ?>s</span><span id="ht-100002-tooltip" class="tooltip">Average successful rate (percent of successful calls)</span></th>
                    </tr>
                    <tr>
                        <th width="6%" class="cset-1" rel="2">&nbsp;<?php echo __('avgduration') ?>&nbsp;</th>
                        <th width="6%" class="cset-1" rel="3">&nbsp;<?php echo __('ABR') ?>&nbsp;</th>
                        <th width="6%" class="cset-1" rel="2">&nbsp;<?php echo __('calls') ?>&nbsp;</th>
                        <th width="6%" class="cset-1" rel="3">&nbsp;<?php echo __('calldelay') ?>&nbsp;</th>
                        <!-- 
                             <th width="6%" class="cset-1" rel="3">&nbsp;profitability&nbsp;</th> 
                               
                        -->
                        <th width="6%" class="cset-1" rel="2">&nbsp;<?php echo __('avgduration') ?>&nbsp;</th>
                        <th width="6%" class="cset-1" rel="3">&nbsp;<?php echo __('ABR') ?>&nbsp;</th>
                        <th width="6%" class="cset-1" rel="2">&nbsp;<?php echo __('calls') ?>&nbsp;</th>
                        <th width="6%" class="cset-1" rel="3">&nbsp;<?php echo __('calldelay') ?>&nbsp;</th>
                        <!--    
                              <th width="6%" class="cset-1" rel="3">&nbsp;profitability&nbsp;</th> 
                              
                              
                        -->
                        <th width="6%" class="cset-3" rel="10">&nbsp;<?php echo __('avgduration') ?></th>
                        <th width="6%" class="cset-3 last" rel="10" >&nbsp; <?php echo __('ABR') ?> &nbsp;</th>
                        <th width="6%" class="cset-3" rel="10" >&nbsp; <?php echo __('calls') ?> &nbsp;</th>
                        <th width="6%" class="cset-3 last" rel="10" >&nbsp;<?php echo __('calldelay') ?></th>
                        <!--
                             <th width="6%" class="cset-1" rel="3">&nbsp;profitability&nbsp;</th> 
                        --></tr>
                </thead>
                <tbody class="orig-calls" id='tbodytotal'>
                    <?php $data = $p->getDataArray(); ?>
                    <?php foreach ($data as $val): ?>
                        <tr>
                <!--            <td><a href="<?php echo $this->webroot ?>monitorsreports/prefix/<?php echo $val['qos_name'] ?>"><?php echo $val['name'] ?></a></td>-->
                            <td><a href="###"><?php echo $val['name'] ?></a></td>
                            <td><?php echo number_format($val['acd1'] / 60, 2) ?></td>
                            <td><?php echo number_format($val['asr1'], 2) ?></td>
                            <td><?php echo number_format($val['ca1'], 0) ?></td>
                            <td><?php echo number_format($val['pdd1'], 0) ?></td>
                            <td><?php echo number_format($val['acd2'] / 60, 2) ?></td>
                            <td><?php echo number_format($val['asr2'], 2) ?></td>
                            <td><?php echo number_format($val['ca2'], 0) ?></td>
                            <td><?php echo number_format($val['pdd2'], 0) ?></td>
                            <td><?php echo number_format($val['acd3'] / 60, 2) ?></td>
                            <td><?php echo number_format($val['asr3'], 2) ?></td>
                            <td><?php echo number_format($val['ca3'], 0) ?></td>
                            <td><?php echo number_format($val['pdd3'], 0) ?></td>
                        </tr> 
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div class="separator bottom row-fluid">
                <div class="pagination pagination-large pagination-right margin-none">
                    <?php echo $this->element('page'); ?>
                </div> 
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