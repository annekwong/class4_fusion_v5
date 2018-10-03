    <table class="list  footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
        <thead>
            <tr>
                <?php foreach($show_fields as $field): ?>
                <th><?php echo $replace_fields[$field]; ?></th>
                <?php endforeach; ?>
                <th colspan="2"><?php __('Call Duration') ?></th>
                            <th colspan="2"><?php __('Profit') ?></th>
                            <th colspan="3"><?php __('Calls') ?></th>
                            <th><?php __('Ingress Cost') ?></th>
                            <th><?php __('Egress Cost') ?></th>
            </tr>
            <tr>
                <?php for($i=0;$i<count($show_fields);$i++):?>
                <th>&nbsp;</th>
                <?php endfor; ?>
                <th><?php __('min') ?></th>
                <th>%</th>
                <th><?php __('USA') ?></th>
                <th>%</th>
                <th><?php __('Total') ?></th>
                <th><?php __('Not Zero') ?></th>
                <th><?php __('Success') ?></th>
                <th></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php 
                $i = 0;
                $arr = array();
                foreach($data as $item):
                    $arr['inbound_call_cost'][$i] = $item[0]['inbound_call_cost'];
                    $arr['outbound_call_cost'][$i] = $item[0]['outbound_call_cost'];
                    $arr['duration'][$i] = $item[0]['duration'];
                    $arr['total_calls'][$i] = $item[0]['total_calls'];
                    $arr['not_zero_calls'][$i] = $item[0]['not_zero_calls'];
                    $arr['success_calls'][$i] = $item[0]['success_calls'];
                    $arr['bill_time'][$i] = $item[0]['bill_time'];
                    $i++;
                endforeach; 
                $i = 0;
                foreach ($data as $item):
            ?>
            <tr>
                <?php foreach(array_keys($show_fields) as $key): ?>
                <td><?php echo $item[0][$key]; ?></td>
                <?php endforeach; ?>
<!--                <td><?php echo round($arr['duration'][$i] / 60, 2);?></td>-->
                <td><?php echo round($item[0]['bill_time'] / 60, 2); ?></td>
                <td><?php echo array_sum($arr['duration']) == 0 ? 0 : round($arr['duration'][$i] /array_sum($arr['duration'])* 100, 2)  ;?>%</td>
                <td><?php echo number_format($arr['inbound_call_cost'][$i] - $arr['outbound_call_cost'][$i], 5);?></td>
                <td><?php echo number_format($arr['inbound_call_cost'][$i] == 0 ? 0 : ($arr['inbound_call_cost'][$i] - $arr['outbound_call_cost'][$i]) / $arr['inbound_call_cost'][$i] * 100, 5);?>%</td>
                <td><?php echo round($arr['total_calls'][$i]);?></td>
                <td><?php echo round($arr['not_zero_calls'][$i]);?></td>
                <td><?php echo round($arr['success_calls'][$i]);?></td>
                <td><?php echo round($arr['inbound_call_cost'][$i], 5);?></td>
                <td><?php echo round($arr['outbound_call_cost'][$i], 5);?></td>
            </tr>
            <?php 
                $i++;
                endforeach; 
            ?>
            <?php
                $count_group = count($show_fields);
                if($count_group && count($data)):
            ?>
            <tr>
                <td colspan="<?php echo $count_group; ?>">Total:</td>
<!--                <td><?php echo round(array_sum($arr['duration']) / 60, 2);?></td>-->
                <td><?php echo round(array_sum($arr['bill_time']) / 60, 2);?></td>
                <td>100%</td>
                <td><?php echo number_format(array_sum($arr['inbound_call_cost']) - array_sum($arr['outbound_call_cost']), 5);?></td>
                <td><?php echo number_format(array_sum($arr['inbound_call_cost']) == 0 ? 0 : (array_sum($arr['inbound_call_cost']) - array_sum($arr['outbound_call_cost'])) / array_sum($arr['inbound_call_cost']) * 100, 5);?>%</td>
                <td><?php echo round(array_sum($arr['total_calls']));?></td>
                <td><?php echo round(array_sum($arr['not_zero_calls']));?></td>
                <td><?php echo round(array_sum($arr['success_calls']));?></td>
                <td><?php echo round(array_sum($arr['inbound_call_cost']), 5);?></td>
                <td><?php echo round(array_sum($arr['outbound_call_cost']), 5);?></td>
            </tr>
            <?php
                endif;
            ?>
        </tbody>
    </table>
