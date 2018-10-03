<ul class="breadcrumb">
    <li><?php echo __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>reports_db/location">
        <?php __('Statistics') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>reports_db/location">
        <?php echo __('Location Report') ?></a></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Location Report') ?></h4>
    <div class="clearfix"> </div>
</div>
<div class="separator bottom"></div>

<div class="innerLR">

    <div class="widget widget-heading-simple widget-body-white">
        <div class="widget-body">
            <?php if ($show_nodata): ?><h1 style="font-size:14px;"><?php __('Report Period')?> <?php echo $start_date ?> — <?php echo $end_date ?></h1><?php endif; ?>
            <?php if (empty($data)): ?>
                <?php if ($show_nodata): ?><div class="msg center">
                    <h2><?php  echo __('no_data_found') ?></h2>
                    </div><?php endif; ?>
            <?php else: ?>
                <div id="overflow_x">
                    <table class="list  footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
                        <thead>
                        <tr>
                            <?php foreach ($show_fields as $field): ?>
                                <th><?php echo $replace_fields[$field]; ?></th>
                            <?php endforeach; ?>
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
                        $arr = array();
                        foreach ($data as $item):
                            if (isset($type) && $type == 2 && isset($item[0]['egress_id']) && !$item[0]['egress_id']){
                                continue;
                            }
                            if (isset($type) && $type == 1 && isset($item[0]['ingress_id']) && !$item[0]['ingress_id']){
                                continue;
                            }
                            $arr['inbound_call_cost'][$i] = $item[0]['inbound_call_cost'];
                            $arr['outbound_call_cost'][$i] = $item[0]['outbound_call_cost'];
                            $arr['duration'][$i] = $item[0]['duration'];
                            $arr['total_calls'][$i] = $item[0]['total_calls'];
                            ?>
                            <tr>
                                <?php foreach (array_keys($show_fields) as $key): ?>
                                    <td style="color:#6694E3;"><?php echo $item[0][$key]; ?></td>
                                <?php endforeach; ?>
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
                        <?php
                        $count_group = count($show_fields);
                        if ($count_group && count($data)):
                            ?>
                            <tfoot>
                            <tr style="color:#000;">
                                <td><?php echo __('Total') ?>:</td>
                                <?php for ($i = 0; $i < count($show_fields) -1; $i++): ?>
                                    <th>&nbsp;</th>
                                <?php endfor; ?>
                                <td><?php echo round(array_sum($arr['duration']) / 60, 2); ?></td>
                                <td><?php echo round(array_sum($arr['total_calls'])); ?></td>
                                <td><?php echo round(array_sum($arr['inbound_call_cost']), 5); ?></td>
                                <td><?php echo round(array_sum($arr['outbound_call_cost']), 5); ?></td>
                                <td><?php echo number_format(array_sum($arr['inbound_call_cost']) - array_sum($arr['outbound_call_cost']), 5); ?></td>
                                <td><?php echo number_format(array_sum($arr['inbound_call_cost']) == 0 ? 0 : (array_sum($arr['inbound_call_cost']) - array_sum($arr['outbound_call_cost'])) / array_sum($arr['inbound_call_cost']) * 100, 5); ?>%</td>
                            </tr>
                            </tfoot>
                            <?php
                        endif;
                        ?>
                    </table>
                </div>
            <?php endif; ?>
            <?php echo $form->create('Cdr', array('type' => 'get', 'url' => "/reports_db/location", 'onsubmit' => "if($('select[name=show_type]').val() == 0) loading(); submitCustomize();")); ?>
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


