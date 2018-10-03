<ul class="breadcrumb">
    <li><?php echo __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>reports_db/usagereport">
            <?php __('Real Time Statistics') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot . $this->params['url']['url'] ?>">
            <?php echo __('Location Report New') ?></a></li>
</ul>
<?php

$user_id = $_SESSION['sst_user_id'];
$res = $cdr_db->query("select * from users where user_id = {$user_id} ");

?>
<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Location Report') ?></h4>
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>

<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-head">
            <ul class="tabs">
                <li <?php if ($type == 1) echo 'class="active"'; ?>>
                    <a href="<?php echo $this->webroot; ?>cdrreports_db/profit_new/1"  class="glyphicons left_arrow">
                        <i></i>
                        <?php __('Origination')?>
                    </a>
                </li>

                <?php
                if($res[0][0]['all_termination'] == 't'){
                    ?>
                    <li <?php if ($type == 2) echo 'class="active"'; ?>>
                        <a href="<?php echo $this->webroot; ?>cdrreports_db/profit_new/2"  class="glyphicons right_arrow">
                            <i></i>
                            <?php __('Termination')?>
                        </a>
                    </li>
                    <?php
                }
                ?>
            </ul>
        </div>
        <div class="widget-body">

            <?php if ($show_nodata): ?><h1 style="font-size:14px;"><?php __('Report Period')?> <?php echo $start_date ?> — <?php echo $end_date ?></h1><?php endif; ?>
            <?php if (empty($data)): ?>
                <?php if ($show_nodata): ?><div class="msg center"><h2><?php  echo __('no_data_found') ?></h2></div><?php endif; ?>
            <?php else: ?>
                <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary" style="color:#4B9100;">
                    <thead>
                    <tr>

                        <th rowspan="2"><?php echo $type == '1' ? 'Ingress Trunk' : 'Egress Trunk'; ?></th>
                        <th colspan="2"><?php __('Call Duration') ?></th>
                        <th colspan="2"><?php __('Profit') ?></th>
                        <th colspan="3"><?php __('Calls') ?></th>
                        <th rowspan="2"><?php __('Ingress Cost') ?></th>
                        <th rowspan="2"><?php __('Egress Cost') ?></th>
                    </tr>
                    <tr>
                        <!--                        --><?php //for ($i = 0; $i < count($show_fields); $i++): ?>
                        <!--                            <th>&nbsp;</th>-->
                        <!--                        --><?php //endfor; ?>
                        <th><?php __('min') ?></th>
                        <th>%</th>
                        <th><?php __('USD') ?></th>
                        <th>%</th>
                        <th><?php __('Total') ?></th>
                        <th><?php __('Not Zero') ?></th>
                        <th><?php __('Success') ?></th>
                        <!--                        <th></th>-->
                        <!--                        <th></th>-->
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $i = 0;
                    $arr = array(
                        'inbound_call_cost' => array(),
                        'outbound_call_cost' => array(),
                        'duration' => array(),
                        'total_calls' => array(),
                        'not_zero_calls' => array(),
                        'success_calls' => array(),
                        'bill_time' => array()
                    );

                    $calculateResponse = $type == 1 ? 'release_cause_from_protocol_stack' : 'binary_value_of_release_cause_from_protocol_stack';
                    $calculateBillTime = $type == 1 ? 'ingress_client_bill_time' : 'egress_bill_time';

                    foreach ($data as $item):
                        $arr['inbound_call_cost'][$i] = 0;
                        $arr['outbound_call_cost'][$i] = 0;
                        $arr['duration'][$i] = 0;
                        $arr['total_calls'][$i] = 0;
                        $arr['not_zero_calls'][$i] = 0;
                        $arr['success_calls'][$i] = 0;
                        $arr['bill_time'][$i] = 0;

                        foreach ($item as $subItem) {
                            $arr['inbound_call_cost'][$i] += $subItem['ingress_client_cost'];
                            $arr['outbound_call_cost'][$i] += $subItem['egress_cost'];
                            $arr['duration'][$i] += $subItem['call_duration'];
                            $arr['total_calls'][$i] += 1;

                            if($subItem['call_duration'] > 0) {
                                $arr['not_zero_calls'][$i] += 1;
                            }

                            if($subItem[$calculateResponse] == '200:OK') {
                                $arr['success_calls'][$i] += 1;
                            }

                            $arr['bill_time'][$i] += $subItem[$calculateBillTime];
                        }
                        $i++;
                        endforeach;
                        $i = 0;
                        foreach ($data as $key=>$item):
                        ?>
                        <tr>
                            <td><?php echo $key == "NULL" ? "~" : $key; ?></td>
                            <td><?php echo round($arr['bill_time'][$i] / 60, 2); ?></td>
                            <td><?php echo array_sum($arr['duration']) == 0 ? 0 : round($arr['duration'][$i] / array_sum($arr['duration']) * 100, 2); ?>%</td>
                            <td><?php echo number_format($arr['inbound_call_cost'][$i] - $arr['outbound_call_cost'][$i], 5); ?></td>
                            <td><?php echo number_format($arr['inbound_call_cost'][$i] == 0 ? 0 : ($arr['inbound_call_cost'][$i] - $arr['outbound_call_cost'][$i]) / $arr['inbound_call_cost'][$i] * 100, 5); ?>%</td>
                            <td><?php echo round($arr['total_calls'][$i]); ?></td>
                            <td><?php echo round($arr['not_zero_calls'][$i]); ?></td>
                            <td><?php echo round($arr['success_calls'][$i]); ?></td>
                            <td><?php echo round($arr['inbound_call_cost'][$i], 5); ?></td>
                            <td><?php echo round($arr['outbound_call_cost'][$i], 5); ?></td>
                        </tr>
                        <?php
                        $i++;
                    endforeach;
                    ?>
                    </tbody>
                    <tfoot>
                        <tr style="color:#000000;">
                            <td>Total:</td>
                            <td><?php echo round(array_sum($arr['bill_time']) / 60, 2); ?></td>
                            <td>100%</td>
                            <td><?php echo number_format(array_sum($arr['inbound_call_cost']) - array_sum($arr['outbound_call_cost']), 5); ?></td>
                            <td><?php echo number_format(array_sum($arr['inbound_call_cost']) == 0 ? 0 : (array_sum($arr['inbound_call_cost']) - array_sum($arr['outbound_call_cost'])) / array_sum($arr['inbound_call_cost']) * 100, 5); ?>%</td>
                            <td><?php echo round(array_sum($arr['total_calls'])); ?></td>
                            <td><?php echo round(array_sum($arr['not_zero_calls'])); ?></td>
                            <td><?php echo round(array_sum($arr['success_calls'])); ?></td>
                            <td><?php echo round(array_sum($arr['inbound_call_cost']), 5); ?></td>
                            <td><?php echo round(array_sum($arr['outbound_call_cost']), 5); ?></td>
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