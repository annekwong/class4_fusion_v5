<ul class="breadcrumb">
    <li><?php echo __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>reports_db/usagereport">
            <?php __('Real Time Statistics') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot . $this->params['url']['url'] ?>">
            <?php echo __('Inbound/Outbound Report') ?></a></li>
</ul>
<?php

$user_id = $_SESSION['sst_user_id'];
$res = $cdr_db->query("select * from users where user_id = {$user_id} ");

?>
<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Inbound/Outbound Report') ?></h4>
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>

<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-body">

            <?php if ($show_nodata): ?><h1 style="font-size:14px;"><?php __('Report Period')?> <?php echo $start_date ?> — <?php echo $end_date ?></h1><?php endif; ?>
            <?php if (empty($data)): ?>
                <?php if ($show_nodata): ?><div class="msg center"><h2><?php  echo __('no_data_found') ?></h2></div><?php endif; ?>
            <?php else: ?>
                <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary" style="color:#4B9100;">
                    <thead>
                    <tr>

                        <th rowspan="2">Ingress Trunk</th>
                        <th colspan="3">inbound</th>
                        <th colspan="3">outbound</th>
                        <th colspan="2">profit</th>
                        <th rowspan="2">total duration(min)</th>
                        <th rowspan="2">asr</th>
                        <th rowspan="2">acd(min)</th>
                        <th rowspan="2">pdd(ms)</th>
                        <th colspan="4">calls</th>
                    </tr>
                    <tr>
                        <th>billed time</th>
                        <th>cost(usa)</th>
                        <th>avg rate(usa)</th>
                        <th>biiled time</th>
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
                    $arr = array(
                        'inbound_call_cost' => array(),
                        'inbound_bill_time' => array(),
                        'outbound_call_cost' => array(),
                        'outbound_bill_time' => array(),
                        'duration' => array(),
                        'total_calls' => array(),
                        'not_zero_calls' => array(),
                        'success_calls' => array(),
                        'busy_calls' => array(),
                        'pdd' => array()
                    );

                    $calculateResponse = 'binary_value_of_release_cause_from_protocol_stack';

                    foreach ($data as $item):
                        $arr['inbound_call_cost'][$i] = 0;
                        $arr['inbound_bill_time'][$i] = 0;
                        $arr['outbound_call_cost'][$i] = 0;
                        $arr['outbound_bill_time'][$i] = 0;
                        $arr['duration'][$i] = 0;
                        $arr['total_calls'][$i] = 0;
                        $arr['not_zero_calls'][$i] = 0;
                        $arr['success_calls'][$i] = 0;
                        $arr['busy_calls'][$i] = 0;
                        $arr['pdd'][$i] = 0;

                        foreach ($item as $subItem) {
                            $arr['inbound_call_cost'][$i] += $subItem['ingress_client_cost'];
                            $arr['inbound_bill_time'][$i] += $subItem['ingress_client_bill_time'];
                            $arr['outbound_call_cost'][$i] += $subItem['egress_cost'];
                            $arr['outbound_bill_time'][$i] += $subItem['egress_bill_time'];
                            $arr['duration'][$i] += $subItem['call_duration'];
                            $arr['total_calls'][$i] += 1;

                            if($subItem['call_duration'] > 0) {
                                $arr['not_zero_calls'][$i] += 1;
                            }

                            if($subItem[$calculateResponse] == '200:OK') {
                                $arr['success_calls'][$i] += 1;
                            } else if($subItem[$calculateResponse] == '403:Forbidden') {
                                $arr['busy_calls'][$i] += 1;
                            }

                            $arr['pdd'][$i] += $subItem['pdd'];
                        }
                        $i++;
                    endforeach;
                    $i = 0;
                    foreach ($data as $key=>$item):
                        ?>
                        <tr>
                            <td><?php echo $key == "NULL" ? "~" : $key; ?></td>
                            <td><?php echo round($arr['inbound_bill_time'][$i] / 60, 2); ?></td>
                            <td><?php echo round($arr['inbound_call_cost'][$i], 5); ?></td>
                            <td><?php echo number_format($arr['inbound_bill_time'][$i] == 0 ? 0 : $arr['inbound_call_cost'][$i] / ($arr['inbound_bill_time'][$i] / 60), 5); ?></td>
                            <td><?php echo round($arr['outbound_bill_time'][$i] / 60, 2); ?></td>
                            <td><?php echo round($arr['outbound_call_cost'][$i], 5); ?></td>
                            <td><?php echo number_format($arr['outbound_bill_time'][$i] == 0 ? 0 : $arr['outbound_call_cost'][$i] / ($arr['outbound_bill_time'][$i] / 60), 5); ?></td>
                            <td><?php echo number_format($arr['inbound_call_cost'][$i] - $arr['outbound_call_cost'][$i], 5); ?></td>
                            <td><?php echo number_format($arr['inbound_call_cost'][$i] == 0 ? 0 : ($arr['inbound_call_cost'][$i] - $arr['outbound_call_cost'][$i]) / $arr['inbound_call_cost'][$i] * 100, 5); ?>%</td>
                            <td><?php echo round($arr['duration'][$i] / 60, 2); ?></td>
                            <td><?php echo number_format($arr['total_calls'][$i] == 0 ? 0 : $arr['not_zero_calls'][$i] / $arr['total_calls'][$i] * 100, 2); ?>%</td>
                            <td><?php echo number_format($arr['not_zero_calls'][$i] == 0 ? 0 : $arr['duration'][$i] / $arr['not_zero_calls'][$i] / 60, 2); ?></td>
                            <td><?php echo number_format($arr['not_zero_calls'][$i] == 0 ? 0 : $arr['pdd'][$i] / $arr['not_zero_calls'][$i]); ?></td>
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
                    <tfoot>
                    <tr style="color:#000000;">
                        <td>Total:</td>
                        <td><?php echo round(array_sum($arr['inbound_bill_time']) / 60, 2); ?></td>
                        <td><?php echo round(array_sum($arr['inbound_call_cost']), 5); ?></td>
                        <td><?php echo number_format(array_sum($arr['inbound_bill_time']) == 0 ? 0 : array_sum($arr['inbound_call_cost']) / (array_sum($arr['inbound_bill_time']) / 60), 5); ?></td>
                        <td><?php echo round(array_sum($arr['outbound_bill_time']) / 60, 2); ?></td>
                        <td><?php echo round(array_sum($arr['outbound_call_cost']), 5); ?></td>
                        <td><?php echo number_format(array_sum($arr['outbound_bill_time']) == 0 ? 0 : array_sum($arr['outbound_call_cost']) / (array_sum($arr['outbound_bill_time']) / 60), 5); ?></td>
                        <td><?php echo number_format(array_sum($arr['inbound_call_cost']) - array_sum($arr['outbound_call_cost']), 5); ?></td>
                        <td><?php echo number_format(array_sum($arr['inbound_call_cost']) == 0 ? 0 : (array_sum($arr['inbound_call_cost']) - array_sum($arr['outbound_call_cost'])) / array_sum($arr['inbound_call_cost']) * 100, 5); ?>%</td>
                        <td><?php echo round(array_sum($arr['duration']) / 60, 2); ?></td>
                        <td><?php echo number_format(array_sum($arr['total_calls']) == 0 ? 0 : array_sum($arr['not_zero_calls']) / array_sum($arr['total_calls']) * 100, 2); ?>%</td>
                        <td><?php echo number_format(array_sum($arr['not_zero_calls']) == 0 ? 0 : array_sum($arr['duration']) / array_sum($arr['not_zero_calls']) / 60, 2); ?></td>
                        <td><?php echo number_format(array_sum($arr['not_zero_calls']) == 0 ? 0 : array_sum($arr['pdd']) / array_sum($arr['not_zero_calls'])); ?></td>
                        <td><?php echo round(array_sum($arr['total_calls'])); ?></td>
                        <td><?php echo round(array_sum($arr['not_zero_calls'])); ?></td>
                        <td><?php echo round(array_sum($arr['success_calls'])); ?></td>
                        <td><?php echo round(array_sum($arr['busy_calls'])); ?></td>
                    </tr>
                    </tfoot>
                </table>
            <?php endif; ?>
            <?php echo $this->element('report_db/query_box_newcdr', array('fields' => false)); ?>
            <!--            --><?php //echo $form->create('Cdr', array('type' => 'get', 'url' => "/reports_db/usagereport/{$type}", 'onsubmit' => "if($('select[name=show_type]').val() == 0) loading();")); ?>
            <!--            --><?php //echo $this->element('report_db/cdr_report/select_fieldset'); ?>
            <!--            --><?php //echo $form->end(); ?>

        </div>
    </div>
</div>
