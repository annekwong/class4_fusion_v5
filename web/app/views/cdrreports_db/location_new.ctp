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
        <div class="widget-body">

            <?php if ($show_nodata): ?><h1 style="font-size:14px;"><?php __('Report Period')?> <?php echo $start_date ?> — <?php echo $end_date ?></h1><?php endif; ?>
            <?php if (empty($data)): ?>
                <?php if ($show_nodata): ?><div class="msg center"><h2><?php  echo __('no_data_found') ?></h2></div><?php endif; ?>
            <?php else: ?>
                <table class="list  footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
                    <thead>
                    <tr>
<!--                        --><?php //foreach ($show_fields as $field): ?>
<!--                            <th>--><?php //echo $replace_fields[$field]; ?><!--</th>-->
<!--                        --><?php //endforeach; ?>
                        <th><?php echo __('ORIG Country') ?></th>
                        <th><?php echo __('Duration') ?></th>
                        <th><?php echo __('Call Count') ?></th>
                        <th><?php echo __('Revenue') ?></th>
                        <th><?php echo __('Cost') ?></th>
                        <th><?php echo __('Profit') ?></th>
                        <th><?php echo __('Profit(%)') ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $i = 0;
                    $arr = array(
                        'inbound_call_cost' => array(),
                        'outbound_call_cost' => array(),
                        'duration' => array(),
                        'total_calls' => array()
                    );
                    foreach ($data as $key=>$item):

                        $arr['inbound_call_cost'][$i] = 0;
                        $arr['outbound_call_cost'][$i] = 0;
                        $arr['duration'][$i] = 0;
                        $arr['total_calls'][$i] = 0;

                        foreach ($item as $subItem) {
                            $arr['inbound_call_cost'][$i] += $subItem['ingress_client_cost'];
                            $arr['outbound_call_cost'][$i] += $subItem['egress_cost'];
                            $arr['duration'][$i] += $subItem['call_duration'];
                            $arr['total_calls'][$i] += 1;
                        }

                        ?>
                        <tr>
<!--                            --><?php //foreach (array_keys($show_fields) as $key): ?>
<!--                                <td style="color:#6694E3;">--><?php //echo $item[0][$key]; ?><!--</td>-->
<!--                            --><?php //endforeach; ?>
                            <td style="color:#6694E3;"><?php echo $key == "NULL" ? "~" : $key; ?></td>
                            <td><?php echo round($arr['duration'][$i] / 60, 2); ?></td>
                            <td><?php echo round($arr['total_calls'][$i]); ?></td>
                            <td><?php echo round($arr['inbound_call_cost'][$i], 5); ?></td>
                            <td><?php echo round($arr['outbound_call_cost'][$i], 5); ?></td>
                            <td><?php echo number_format($arr['inbound_call_cost'][$i] - $arr['outbound_call_cost'][$i], 5); ?></td>
                            <td><?php echo number_format($arr['inbound_call_cost'][$i] == 0 ? 0 : ($arr['inbound_call_cost'][$i] - $arr['outbound_call_cost'][$i]) / $arr['inbound_call_cost'][$i] * 100, 5); ?>%</td>

                        </tr>
                        <?php
                        $i++;
                    endforeach;
                    ?>
                    </tbody>
                    <tfoot>
                        <tr style="color:#000;">
                            <td><?php echo __('Total') ?>:</td>
                            <td><?php echo round(array_sum($arr['duration']) / 60, 2); ?></td>
                            <td><?php echo round(array_sum($arr['total_calls'])); ?></td>
                            <td><?php echo round(array_sum($arr['inbound_call_cost']), 5); ?></td>
                            <td><?php echo round(array_sum($arr['outbound_call_cost']), 5); ?></td>
                            <td><?php echo number_format(array_sum($arr['inbound_call_cost']) - array_sum($arr['outbound_call_cost']), 5); ?></td>
                            <td><?php echo number_format(array_sum($arr['inbound_call_cost']) == 0 ? 0 : (array_sum($arr['inbound_call_cost']) - array_sum($arr['outbound_call_cost'])) / array_sum($arr['inbound_call_cost']) * 100, 5); ?>%</td>
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