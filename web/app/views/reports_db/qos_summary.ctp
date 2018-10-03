<ul class="breadcrumb">
    <li><?php echo __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>reports_db/qos_summary">
        <?php __('Statistics') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot . $this->params['url']['url'] ?>">
        <?php __('QoS Summary') ?></a></li>
</ul>
<?php

$user_id = $_SESSION['sst_user_id'];
$res = $cdr_db->query("select * from users where user_id = {$user_id} ");

?>
<div class="heading-buttons">
    <h4 class="heading"><?php __('QoS Summary') ?></h4>
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>


<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-head">
            <ul class="tabs">
                <li <?php if ($type == 1) echo 'class="active"'; ?>>
                    <a href="<?php echo $this->webroot; ?>reports_db/qos_summary/1" class="glyphicons left_arrow">
                        <i></i>
                        <?php __('Origination')?>
                    </a>
                </li>

                <?php
                if($res[0][0]['all_termination'] == 't'){
                    ?>
                    <li <?php if ($type == 2) echo 'class="active"'; ?>>
                        <a href="<?php echo $this->webroot; ?>reports_db/qos_summary/2"  class="glyphicons right_arrow">
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


            <?php if ($show_nodata): ?><h1 style="font-size:14px;">Report Period <?php echo $start_date ?> — <?php echo $end_date ?></h1><?php endif; ?>
            <?php if (empty($data)): ?>
                <?php if ($show_nodata): ?><div class="msg"><?php  echo __('no_data_found') ?></div><?php endif; ?>
            <?php else: ?>
                <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary" style="color:#4B9100;">
                    <thead>
                    <tr>
                        <?php foreach ($show_fields as $field): ?>
                            <th  rowspan="2"><?php echo $replace_fields[$field]; ?></th>
                        <?php endforeach; ?>
<!--                        <th rowspan="2">--><?php //__('ABR')?><!--</th>-->
                        <th rowspan="2"><?php __('ASR')?></th>
                        <th rowspan="2"><?php __('ACD(min)')?></th>
<!--                        <th rowspan="2">--><?php //__('ALOC')?><!--</th>-->
                        <th rowspan="2"><?php __('PDD(ms)')?></th>
                        <th colspan="1"><?php __('Time(min)')?></th>
                        <th colspan="4"><?php __('Calls')?></th>
                    </tr>
                    <tr>
                        <th><?php __('Total Billable Time')?></th>
                        <th><?php __('Total Calls')?></th>
                        <th><?php __('Not Zero')?></th>
                        <th><?php __('Busy Calls')?></th>
                    </thead>
                    <tbody>
                    <?php
                    $i = 0;
                    $arr = array();
                    foreach ($data as $item):
                        if ($type == 2 && isset($item[0]['egress_id']) && !$item[0]['egress_id']){
                            continue;
                        }
                        if ($type == 1 && isset($item[0]['ingress_id']) && !$item[0]['ingress_id']){
                            continue;
                        }
                        $arr['duration'][$i] = $item[0]['duration'];
                        $arr['bill_time'][$i] = $item[0]['bill_time'];
                        $arr['cancel_calls'][$i] = $item[0]['cancel_calls'];
                        $arr['total_calls'][$i] = $item[0]['total_calls'];
                        $arr['not_zero_calls'][$i] = $item[0]['not_zero_calls'];
                        $arr['success_calls'][$i] = $item[0]['success_calls'];
                        $arr['busy_calls'][$i] = $item[0]['busy_calls'];
                        $arr['pdd'][$i] = $item[0]['pdd'];
                        ?>
                        <tr>
                            <?php foreach (array_keys($show_fields) as $key): ?>
                                <td style="color:#6694E3;"><?php echo $item[0][$key]; ?></td>
                            <?php endforeach; ?>
                            <td><?php echo round($arr['total_calls'][$i] == 0 ? 0 : $arr['not_zero_calls'][$i] / $arr['total_calls'][$i] * 100, 2); ?>%</td>
<!--                            <td>--><?php //echo ($arr['busy_calls'][$i] + $arr['cancel_calls'][$i] + $arr['not_zero_calls'][$i]) == 0 ? 0 : round($arr['not_zero_calls'][$i] / ($arr['busy_calls'][$i] + $arr['cancel_calls'][$i] + $arr['not_zero_calls'][$i]) * 100, 2) ?><!--%</td>-->
                            <td><?php echo round($arr['not_zero_calls'][$i] == 0 ? 0 : $arr['duration'][$i] / $arr['not_zero_calls'][$i] / 60, 2); ?></td>
<!--                            <td>-->
<!--                                --><?php
//                                echo round((($arr['busy_calls'][$i] + $arr['cancel_calls'][$i] + $arr['not_zero_calls'][$i]) == 0 ? 0 : $arr['not_zero_calls'][$i] / ($arr['busy_calls'][$i] + $arr['cancel_calls'][$i] + $arr['not_zero_calls'][$i])) * ($arr['not_zero_calls'][$i] == 0 ? 0 : $arr['duration'][$i] / $arr['not_zero_calls'][$i] / 60), 2);
//                                ?>
<!--                            </td>-->
                            <td><?php echo round($arr['not_zero_calls'][$i] == 0 ? 0 : $arr['pdd'][$i] / $arr['not_zero_calls'][$i]); ?></td>
                            <td><?php echo number_format($arr['bill_time'][$i] / 60, 2); ?></td>

                            <td><?php echo round($arr['total_calls'][$i]); ?></td>
                            <td><?php echo round($arr['not_zero_calls'][$i]); ?></td>
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
                            <td><?php echo __('Total') ?>:</td>
                            <?php for ($i = 0; $i < count($show_fields) -1; $i++): ?>
                                <td>&nbsp;</td>
                            <?php endfor; ?>
                            <td><?php echo round(array_sum($arr['total_calls']) == 0 ? 0 : array_sum($arr['not_zero_calls']) / array_sum($arr['total_calls']) * 100, 2); ?>%</td>
                            <td><?php echo round(array_sum($arr['not_zero_calls']) == 0 ? 0 : array_sum($arr['duration']) / array_sum($arr['not_zero_calls']) / 60, 2); ?></td>
                            <td><?php echo round(array_sum($arr['not_zero_calls']) == 0 ? 0 : array_sum($arr['pdd']) / array_sum($arr['not_zero_calls'])); ?></td>
                            <td><?php echo number_format(array_sum($arr['bill_time']) / 60, 2); ?></td>
                            <td><?php echo round(array_sum($arr['total_calls'])); ?></td>
                            <td><?php echo round(array_sum($arr['not_zero_calls'])); ?></td>
                            <td><?php echo round(array_sum($arr['busy_calls'])); ?></td>
                        </tr>
                        </tfoot>
                        <?php
                    endif;
                    ?>
                </table>
            <?php endif; ?>
            <?php echo $form->create('Cdr', array('type' => 'get', 'url' => "/reports_db/qos_summary/{$type}", 'onsubmit' => "if($('select[name=show_type]').val() == 0) loading();")); ?>
            <?php echo $this->element('report_db/cdr_report/select_fieldset'); ?>
            <?php echo $form->end(); ?>

        </div>
    </div>
</div>
