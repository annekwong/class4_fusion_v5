<ul class="breadcrumb">
    <li><?php echo __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>reports_db/usagereport">
            <?php __('Real Time Statistics') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot . $this->params['url']['url'] ?>">
            <?php echo __('Qos Summary Report New') ?></a></li>
</ul>
<?php

$user_id = $_SESSION['sst_user_id'];
$res = $cdr_db->query("select * from users where user_id = {$user_id} ");

?>
<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Qos Summary Report') ?></h4>
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>

<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-head">
            <ul class="tabs">
                <li <?php if ($type == 1) echo 'class="active"'; ?>>
                    <a href="<?php echo $this->webroot; ?>cdrreports_db/qos_summary_new/1" class="glyphicons left_arrow">
                        <i></i>
                        <?php __('Origination')?>
                    </a>
                </li>
                <?php
                if($res[0][0]['all_termination'] == 't'){
                    ?>
                    <li <?php if ($type == 2) echo 'class="active"'; ?>>
                        <a href="<?php echo $this->webroot; ?>cdrreports_db/qos_summary_new/2"  class="glyphicons right_arrow">
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
                <?php if ($show_nodata): ?><div class="msg"><?php  echo __('no_data_found') ?></div><?php endif; ?>
            <?php else: ?>
                <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary" style="color:#4B9100;">
                    <thead>
                    <tr>
<!--                        --><?php //foreach ($show_fields as $field): ?>
<!--                            <th  rowspan="2">--><?php //echo $replace_fields[$field]; ?><!--</th>-->
<!--                        --><?php //endforeach; ?>
                        <th rowspan="2"><?php __('ABR')?></th>
                        <th rowspan="2"><?php __('ASR')?></th>
                        <th rowspan="2"><?php __('ACD(min)')?></th>
                        <th rowspan="2"><?php __('ALOC')?></th>
                        <th rowspan="2"><?php __('PDD(ms)')?></th>
                        <th colspan="1"><?php __('Time(min)')?></th>
                        <th colspan="4"><?php __('Calls')?></th>
                    </tr>
                    <tr>
                        <th><?php __('Total Billable Time')?></th>
                        <th><?php __('Total Calls')?></th>
                        <th><?php __('Not Zero')?></th>
                        <th><?php __('Success Calls')?></th>
                        <th><?php __('Busy Calls')?></th>
                    </thead>
                    <tbody>
                        <tr>
<!--                            --><?php //foreach (array_keys($show_fields) as $key): ?>
<!--                                <td style="color:#6694E3;">--><?php //echo $item[0][$key]; ?><!--</td>-->
<!--                            --><?php //endforeach; ?>
                            <td><?php echo round($data['total_calls'] == 0 ? 0 : $data['not_zero_calls'] / $data['total_calls'] * 100, 2); ?>%</td>
                            <td><?php echo ($data['busy_calls'] + $data['cancel_calls'] + $data['not_zero_calls']) == 0 ? 0 : round($data['not_zero_calls'] / ($data['busy_calls'] + $data['cancel_calls'] + $data['not_zero_calls']) * 100, 2) ?>%</td>
                            <td><?php echo round($data['not_zero_calls'] == 0 ? 0 : $data['duration'] / $data['not_zero_calls'] / 60, 2); ?></td>
                            <td>
                                <?php
                                echo round((($data['busy_calls'] + $data['cancel_calls'] + $data['not_zero_calls']) == 0 ? 0 : $data['not_zero_calls'] / ($data['busy_calls'] + $data['cancel_calls'] + $data['not_zero_calls'])) * ($data['not_zero_calls'] == 0 ? 0 : $data['duration'] / $data['not_zero_calls'] / 60), 2);
                                ?>


                            </td>
                            <td><?php echo round($data['not_zero_calls'] == 0 ? 0 : $data['pdd'] / $data['not_zero_calls']); ?></td>
                            <td><?php echo number_format($data['bill_time'] / 60, 2); ?></td>

                            <td><?php echo round($data['total_calls']); ?></td>
                            <td><?php echo round($data['not_zero_calls']); ?></td>
                            <td><?php echo round($data['success_calls']); ?></td>
                            <td><?php echo round($data['busy_calls']); ?></td>

                        </tr>
                    </tbody>
                </table>
            <?php endif; ?>
            <?php echo $this->element('report_db/query_box_newcdr', array('fields' => false)); ?>
            <!--            --><?php //echo $form->create('Cdr', array('type' => 'get', 'url' => "/reports_db/usagereport/{$type}", 'onsubmit' => "if($('select[name=show_type]').val() == 0) loading();")); ?>
            <!--            --><?php //echo $this->element('report_db/cdr_report/select_fieldset'); ?>
            <!--            --><?php //echo $form->end(); ?>

        </div>
    </div>
</div>