<style type="text/css">
    #overflow_x{overflow-x:auto; margin-bottom: 10px;}
</style>
<ul class="breadcrumb">
    <li><?php echo __('you are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>reports_db/inout_report">
            <?php __('statistics') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>reports_db/inout_report">
            <?php echo __('inbound/outbound report') ?></a></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('inbound/outbound report') ?></h4>
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>

<div class="innerlr">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-body">
            <?php if ($show_nodata): ?>
                <h1 style="font-size:14px;">report period <?php echo $start_date ?> — <?php echo $end_date ?></h1>
            <?php endif; ?>
            <?php if (empty($data)): ?>
                <?php if ($show_nodata): ?><div class="msg center">
                    <h2><?php  echo __('no_data_found') ?></h2>
                    </div>
                <?php endif; ?>
            <?php else: ?>
                <div class="overflow_x">
                    <table class="list footable table table-striped dynamicTable tabletools table-bordered  table-white table-primary" style="color:#4b9100;">
                        <thead>
                        <tr>
                            <?php foreach ($show_fields as $field): ?>
                                <th rowspan="2"><?php echo $replace_fields[$field]; ?></th>
                            <?php endforeach; ?>
                            <th colspan="3">inbound</th>
                            <th colspan="3">outbound</th>
                            <th colspan="2">profit</th>
                            <th rowspan="2">total duration(min)</th>
                            <th rowspan="2">asr</th>
                            <th rowspan="2">acd(min)</th>
                            <th rowspan="2">pdd(ms)</th>
                            <th rowspan="2">NPR Count</th>
                            <th rowspan="2">NPR</th>
                            <th colspan="4">calls</th>
                        </tr>
                        <tr>
                            <th>billed time</th>
                            <th>cost(usa)</th>
                            <th>avg rate(usa)</th>
                            <th>billed time</th>
                            <th>cost(usa)</th>
                            <th>avg rate(usa)</th>
                            <th>(usa)</th>
                            <th>%</th>
                            <th>total</th>
                            <th>not zero</th>
                            <th>success</th>
                            <th>busy calls</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $i = 0;
                        $arr = array();
                        foreach ($data as $item):
                            $arr['inbound_bill_time'][$i] = $item[0]['inbound_bill_time'];
                            $arr['inbound_call_cost'][$i] = $item[0]['inbound_call_cost'];
                            $arr['outbound_bill_time'][$i] = $item[0]['outbound_bill_time'];
                            $arr['outbound_call_cost'][$i] = $item[0]['outbound_call_cost'];
                            $arr['duration'][$i] = $item[0]['duration'];
                            $arr['total_calls'][$i] = $item[0]['total_calls'];
                            $arr['not_zero_calls'][$i] = $item[0]['not_zero_calls'];
                            $arr['success_calls'][$i] = $item[0]['success_calls'];
                            $arr['busy_calls'][$i] = $item[0]['busy_calls'];
                            $arr['pdd'][$i] = $item[0]['pdd'];
                            $arr['npr_count'][$i] = $item[0]['npr_value'];
                            ?>
                            <tr>
                                <?php foreach (array_keys($show_fields) as $key): ?>
                                    <td style="color:#6694e3;"><?php echo $item[0][$key]; ?></td>
                                <?php endforeach; ?>
                                <td><?php echo round($arr['inbound_bill_time'][$i] / 60, 2); ?></td>
                                <td><?php echo round($arr['inbound_call_cost'][$i], 5); ?></td>
                                <td><?php echo number_format($arr['inbound_bill_time'][$i] == 0 ? 0 : $arr['inbound_call_cost'][$i] / ($arr['inbound_bill_time'][$i] / 60), 5); ?></td>
                                <td><?php echo round($arr['outbound_bill_time'][$i] / 60, 2); ?></td>
                                <td><?php echo round($arr['outbound_call_cost'][$i], 5); ?></td>
                                <td><?php echo number_format($arr['outbound_bill_time'][$i] == 0 ? 0 : $arr['outbound_call_cost'][$i] / ($arr['outbound_bill_time'][$i] / 60), 5); ?></td>
                                <td><?php echo number_format($arr['inbound_call_cost'][$i] - $arr['outbound_call_cost'][$i], 5); ?></td>
                                <td><?php echo $arr['outbound_call_cost'][$i] == 0 ? '-' : number_format(($arr['inbound_call_cost'][$i] - $arr['outbound_call_cost'][$i]) / $arr['outbound_call_cost'][$i] * 100, 5) . '%'; ?></td>
                                <td><?php echo round($arr['duration'][$i] / 60, 2); ?></td>
                                <td><?php echo number_format($arr['total_calls'][$i] == 0 ? 0 : $arr['not_zero_calls'][$i] / $arr['total_calls'][$i] * 100, 2); ?>%</td>
                                <td><?php echo number_format($arr['not_zero_calls'][$i] == 0 ? 0 : $arr['duration'][$i] / $arr['not_zero_calls'][$i] / 60, 2); ?></td>
                                <td><?php echo number_format($arr['not_zero_calls'][$i] == 0 ? 0 : $arr['pdd'][$i] / $arr['not_zero_calls'][$i]); ?></td>
                                <td><?php echo number_format($arr['npr_count'][$i]); ?></td>
                                <td><?php echo number_format($arr['total_calls'][$i] == 0 ? 0 : $arr['npr_count'][$i] / $arr['total_calls'][$i] * 100, 2); ?>%</td>
                                <td><?php echo round($arr['total_calls'][$i]); ?></td>
                                <td><?php echo round($arr['not_zero_calls'][$i]); ?></td>
                                <td><?php echo round($arr['success_calls'][$i]); ?></td>
                                <td><?php echo round($arr['busy_calls'][$i]); ?></td>
                            </tr>
                            <?php
                            $i++;
                        endforeach;
                        ?>
                        </tbody>
                        <?php
                        $count_group = count($show_fields);
                        if ($count_group && count($data)):
                            ?>
                            <tfoot>
                            <tr style="color:#000;">
                                <td>total:</td>
                                <?php for ($i = 0; $i < count($show_fields) -1; $i++): ?>
                                    <th>&nbsp;</th>
                                <?php endfor; ?>
                                <td><?php echo round(array_sum($arr['inbound_bill_time']) / 60, 2); ?></td>
                                <td><?php echo round(array_sum($arr['inbound_call_cost']), 5); ?></td>
                                <td><?php echo number_format(array_sum($arr['inbound_bill_time']) == 0 ? 0 : array_sum($arr['inbound_call_cost']) / (array_sum($arr['inbound_bill_time']) / 60), 5); ?></td>
                                <td><?php echo round(array_sum($arr['outbound_bill_time']) / 60, 2); ?></td>
                                <td><?php echo round(array_sum($arr['outbound_call_cost']), 5); ?></td>
                                <td><?php echo number_format(array_sum($arr['outbound_bill_time']) == 0 ? 0 : array_sum($arr['outbound_call_cost']) / (array_sum($arr['outbound_bill_time']) / 60), 5); ?></td>
                                <td><?php echo number_format(array_sum($arr['inbound_call_cost']) - array_sum($arr['outbound_call_cost']), 5); ?></td>
                                <td><?php echo array_sum($arr['outbound_call_cost']) == 0 ? '-' : number_format((array_sum($arr['inbound_call_cost']) - array_sum($arr['outbound_call_cost'])) / array_sum($arr['outbound_call_cost']) * 100, 5) . '%'; ?></td>
                                <td><?php echo round(array_sum($arr['duration']) / 60, 2); ?></td>
                                <td><?php echo number_format(array_sum($arr['total_calls']) == 0 ? 0 : array_sum($arr['not_zero_calls']) / array_sum($arr['total_calls']) * 100, 2); ?>%</td>
                                <td><?php echo number_format(array_sum($arr['not_zero_calls']) == 0 ? 0 : array_sum($arr['duration']) / array_sum($arr['not_zero_calls']) / 60, 2); ?></td>
                                <td><?php echo number_format(array_sum($arr['not_zero_calls']) == 0 ? 0 : array_sum($arr['pdd']) / array_sum($arr['not_zero_calls'])); ?></td>
                                <td><?php echo number_format(array_sum($arr['npr_count'])); ?></td>
                                <td><?php echo number_format(array_sum($arr['total_calls']) == 0 ? 0 : array_sum($arr['npr_count']) / array_sum($arr['total_calls']) * 100, 2); ?>%</td>
                                <td><?php echo round(array_sum($arr['total_calls'])); ?></td>
                                <td><?php echo round(array_sum($arr['not_zero_calls'])); ?></td>
                                <td><?php echo round(array_sum($arr['success_calls'])); ?></td>
                                <td><?php echo round(array_sum($arr['busy_calls'])); ?></td>
                            </tr>
                            </tfoot>
                        <?php
                        endif;
                        ?>
                    </table>
                    <div class="separator"></div>
                </div>
            <?php endif; ?>
            <?php echo $form->create('cdr', array('class'=>'scheduled_report_form','type' => 'get', 'url' => "/reports_db/inout_report", 'onsubmit' => "if($('select[name=show_type]').val() == 0) loading();")); ?>
            <?php echo $this->element('report_db/cdr_report/select_fieldset'); ?>
            <?php echo $form->end(); ?>
        </div>
    </div>
</div>

