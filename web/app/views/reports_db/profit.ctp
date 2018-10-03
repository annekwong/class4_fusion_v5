<ul class="breadcrumb">
    <li><?php __('You are here')?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>reports_db/profit">
            <?php __('Statistics') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot . $this->params['url']['url'] ?>">
            <?php echo __('Profitability Analysis') ?></a></li>
</ul>
<?php

$user_id = $_SESSION['sst_user_id'];
$res = $cdr_db->query("select * from users where user_id = {$user_id} ");

?>
<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Profitability Analysis') ?></h4>
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>

<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-head">
            <ul class="tabs">
                <li <?php if ($type == 1) echo 'class="active"'; ?>>
                    <a href="<?php echo $this->webroot; ?>reports_db/profit/1"  class="glyphicons left_arrow">
                        <i></i>
                        <?php __('Origination')?>
                    </a>
                </li>

                <?php
                if($res[0][0]['all_termination'] == 't'){
                    ?>
                    <li <?php if ($type == 2) echo 'class="active"'; ?>>
                        <a href="<?php echo $this->webroot; ?>reports_db/profit/2"  class="glyphicons right_arrow">
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
                <?php if ($show_nodata): ?><h2 class="msg center"><?php  echo __('no_data_found') ?></h2><?php endif; ?>
            <?php else: ?>
                <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary" style="color:#4B9100;">
                    <thead>
                    <tr>
                        <?php
                        foreach ($show_fields as $field_key => $field):
                            if(strpos($field,'delete') === 0){unset($show_fields[$field_key]);continue; }
                            ?>
                            <th rowspan="2"><?php echo $replace_fields[$field]; ?></th>
                        <?php endforeach; ?>
                        <th colspan="2"><?php __('Call Duration') ?></th>
                        <th colspan="2"><?php __('Profit') ?></th>
                        <th colspan="2"><?php __('Calls') ?></th>
                        <th rowspan="2"><?php __('Ingress Cost') ?></th>
                        <th rowspan="2"><?php __('Egress Cost') ?></th>
                        <th rowspan="2"><?php __('NPR Count') ?></th>
                        <th rowspan="2"><?php __('NPR') ?></th>
                    </tr>
                    <tr>
                        <th><?php __('min') ?></th>
                        <th>%</th>
                        <th><?php __('USA') ?></th>
                        <th>%</th>
                        <th><?php __('Total') ?></th>
                        <th><?php __('Not Zero') ?></th>
                    </tr>
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
                        $arr['inbound_call_cost'][$i] = $item[0]['inbound_call_cost'];
                        $arr['outbound_call_cost'][$i] = $item[0]['outbound_call_cost'];
                        $arr['duration'][$i] = $item[0]['duration'];
                        $arr['total_calls'][$i] = $item[0]['total_calls'];
                        $arr['not_zero_calls'][$i] = $item[0]['not_zero_calls'];
                        $arr['bill_time'][$i] = $item[0]['bill_time'];
                        $arr['npr_count'][$i] = $item[0]['npr_count'];
                        $i++;
                    endforeach;
                    $i = 0;
                    foreach ($data as $item):
                        ?>
                        <tr>
                            <?php foreach (array_keys($show_fields) as $key): ?>
                                <td style="color:#6694E3;">
                                    <?php
                                    if (in_array($key,array('ingress_client_id','egress_client_id','ingress_id','egress_id')) && !$item[0][$key]){
                                        echo $item[0]['delete_'.$key] ? $item[0]['delete_'.$key] : '~';
                                    }else{
                                        echo $item[0][$key];
                                    } ?>
                                </td>
                            <?php endforeach; ?>
                            <!--                <td><?php echo round($arr['duration'][$i] / 60, 2); ?></td>-->
                            <td><?php echo round($item[0]['bill_time'] / 60, 2); ?></td>
                            <td><?php echo array_sum($arr['duration']) == 0 ? 0 : round($arr['duration'][$i] / array_sum($arr['duration']) * 100, 2); ?>%</td>
                            <td><?php echo number_format($arr['inbound_call_cost'][$i] - $arr['outbound_call_cost'][$i], 5); ?></td>
                            <td><?php echo $arr['outbound_call_cost'][$i] == 0 ? '-' : number_format(($arr['inbound_call_cost'][$i] - $arr['outbound_call_cost'][$i]) / $arr['outbound_call_cost'][$i] * 100, 5) . '%'; ?></td>
                            <td><?php echo round($arr['total_calls'][$i]); ?></td>
                            <td><?php echo round($arr['not_zero_calls'][$i]); ?></td>
                            <td><?php echo round($arr['inbound_call_cost'][$i], 5); ?></td>
                            <td><?php echo round($arr['outbound_call_cost'][$i], 5); ?></td>
                            <td><?php echo number_format($arr['npr_count'][$i]); ?></td>
                            <td><?php echo number_format($arr['total_calls'][$i] == 0 ? 0 : $arr['npr_count'][$i] / $arr['total_calls'][$i] * 100, 2); ?>%</td>
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
                        <tr style="color:#000000;">
                            <td>Total:</td>
                            <?php for ($i = 0; $i < count($show_fields) -1; $i++): ?>
                                <td>&nbsp;</td>
                            <?php endfor; ?>
                            <!--                <td><?php echo round(array_sum($arr['duration']) / 60, 2); ?></td>-->
                            <td><?php echo round(array_sum($arr['bill_time']) / 60, 2); ?></td>
                            <td>100%</td>
                            <td><?php echo number_format(array_sum($arr['inbound_call_cost']) - array_sum($arr['outbound_call_cost']), 5); ?></td>
                            <td><?php echo array_sum($arr['outbound_call_cost']) == 0 ? '-' : number_format((array_sum($arr['inbound_call_cost']) - array_sum($arr['outbound_call_cost'])) / array_sum($arr['outbound_call_cost']) * 100, 5) . '%'; ?></td>
                            <td><?php echo round(array_sum($arr['total_calls'])); ?></td>
                            <td><?php echo round(array_sum($arr['not_zero_calls'])); ?></td>
                            <td><?php echo round(array_sum($arr['inbound_call_cost']), 5); ?></td>
                            <td><?php echo round(array_sum($arr['outbound_call_cost']), 5); ?></td>
                            <td><?php echo number_format(array_sum($arr['npr_count'])); ?></td>
                            <td><?php echo number_format(array_sum($arr['total_calls']) == 0 ? 0 : array_sum($arr['npr_count']) / array_sum($arr['total_calls']) * 100, 2); ?>%</td>
                        </tr>
                        </tfoot>
                    <?php
                    endif;
                    ?>
                </table>
            <?php endif; ?>
            <?php echo $form->create('Cdr', array('class'=>'scheduled_report_form','type' => 'get', 'url' => "/reports_db/profit/" . $type, 'onsubmit' => "if($('select[name=show_type]').val() == 0) loading(); submitCustomize();")); ?>
            <?php echo $this->element('report_db/cdr_report/select_fieldset'); ?>
            <?php echo $form->end(); ?>
        </div>
    </div>
</div>

<script>

    function submitCustomize(){
        $('#show_hide_columns').attr('value','1');
    }

</script>