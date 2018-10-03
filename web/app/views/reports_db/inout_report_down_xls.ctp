<table class="list">
    <thead>
    <tr>
        <?php foreach($show_fields as $field): ?>
            <td><?php echo $replace_fields[$field]; ?></td>
        <?php endforeach; ?>
        <td colspan="3">Inbound</td>
        <td colspan="3">Outbound</td>
        <td colspan="2">Profit</td>
        <td>Total Duration(min)</td>
        <td>ASR</td>
        <td>ACD(min)</td>
        <td>PDD(ms)</td>
        <td>NPR Count</td>
        <td>NPR</td>
        <td colspan="4">Calls</td>
    </tr>
    <tr>
        <?php for($i=0;$i<count($show_fields);$i++):?>
            <td>&nbsp;</td>
        <?php endfor; ?>
        <td>Billed Time</td>
        <td>Cost(USA)</td>
        <td>Avg Rate(USA)</td>
        <td>Biiled Time</td>
        <td>Cost(USA)</td>
        <td>Avg Rate(USA)</td>
        <td>(USA)</td>
        <td>%</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td>Total</td>
        <td>Not Zero</td>
        <td>Success</td>
        <td>Busy Calls</td>
    </tr>
    </thead>
    <tbody>
    <?php
    $i = 0;
    $arr = array();
    foreach($data as $item):
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
            <?php foreach(array_keys($show_fields) as $key): ?>
                <td><?php echo $item[0][$key]; ?></td>
            <?php endforeach; ?>
            <td><?php echo round($arr['inbound_bill_time'][$i] / 60, 2); ?></td>
            <td><?php echo round($arr['inbound_call_cost'][$i], 5);?></td>
            <td><?php echo number_format($arr['inbound_bill_time'][$i] == 0 ? 0 : $arr['inbound_call_cost'][$i] / ($arr['inbound_bill_time'][$i] / 60), 5); ?></td>
            <td><?php echo round($arr['outbound_bill_time'][$i] / 60, 2); ?></td>
            <td><?php echo round($arr['outbound_call_cost'][$i], 5);?></td>
            <td><?php echo number_format($arr['outbound_bill_time'][$i] == 0 ? 0 : $arr['outbound_call_cost'][$i] / ($arr['outbound_bill_time'][$i] / 60), 5); ?></td>
            <td><?php echo number_format($arr['inbound_call_cost'][$i] - $arr['outbound_call_cost'][$i], 5);?></td>
            <td><?php echo number_format($arr['inbound_call_cost'][$i] == 0 ? 0 : ($arr['inbound_call_cost'][$i] - $arr['outbound_call_cost'][$i]) / $arr['inbound_call_cost'][$i] * 100, 5);?>%</td>
            <td><?php echo round($arr['duration'][$i] / 60, 2);?></td>
            <td><?php echo number_format($arr['total_calls'][$i] == 0 ? 0 : $arr['not_zero_calls'][$i] / $arr['total_calls'][$i] * 100, 2); ?>%</td>
            <td><?php echo number_format($arr['not_zero_calls'][$i] == 0 ? 0 : $arr['duration'][$i] / $arr['not_zero_calls'][$i] / 60, 2); ?></td>
            <td><?php echo number_format($arr['not_zero_calls'][$i] == 0 ? 0 : $arr['pdd'][$i] / $arr['not_zero_calls'][$i]); ?></td>
            <td><?php echo number_format($arr['npr_count'][$i]); ?></td>
            <td><?php echo number_format($arr['total_calls'][$i] == 0 ? 0 : $arr['npr_count'][$i] / $arr['total_calls'][$i] * 100, 2); ?>%</td>
            <td><?php echo round($arr['total_calls'][$i]);?></td>
            <td><?php echo round($arr['not_zero_calls'][$i]);?></td>
            <td><?php echo round($arr['success_calls'][$i]);?></td>
            <td><?php echo round($arr['busy_calls'][$i]);?></td>
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
            <td><?php echo round(array_sum($arr['inbound_bill_time']) / 60, 2); ?></td>
            <td><?php echo round(array_sum($arr['inbound_call_cost']), 5);?></td>
            <td><?php echo number_format(array_sum($arr['inbound_bill_time']) == 0 ? 0 : array_sum($arr['inbound_call_cost']) / (array_sum($arr['inbound_bill_time']) / 60), 5); ?></td>
            <td><?php echo round(array_sum($arr['outbound_bill_time'])/ 60, 2); ?></td>
            <td><?php echo round(array_sum($arr['outbound_call_cost']), 5);?></td>
            <td><?php echo number_format(array_sum($arr['outbound_bill_time']) == 0 ? 0 : array_sum($arr['outbound_call_cost']) / (array_sum($arr['outbound_bill_time']) / 60), 5); ?></td>
            <td><?php echo number_format(array_sum($arr['inbound_call_cost']) - array_sum($arr['outbound_call_cost']), 5);?></td>
            <td><?php echo number_format(array_sum($arr['inbound_call_cost']) == 0 ? 0 : (array_sum($arr['inbound_call_cost']) - array_sum($arr['outbound_call_cost'])) / array_sum($arr['inbound_call_cost']) * 100, 5);?>%</td>
            <td><?php echo round(array_sum($arr['duration']) / 60, 2);?></td>
            <td><?php echo number_format(array_sum($arr['total_calls']) == 0 ? 0 : array_sum($arr['not_zero_calls']) / array_sum($arr['total_calls']) * 100, 2); ?>%</td>
            <td><?php echo number_format(array_sum($arr['not_zero_calls']) == 0 ? 0 : array_sum($arr['duration']) / array_sum($arr['not_zero_calls']) / 60, 2); ?></td>
            <td><?php echo number_format(array_sum($arr['not_zero_calls']) == 0 ? 0 : array_sum($arr['pdd']) / array_sum($arr['not_zero_calls'])); ?></td>
            <td><?php echo number_format(array_sum($arr['npr_count'])); ?></td>
            <td><?php echo number_format(array_sum($arr['total_calls']) == 0 ? 0 : array_sum($arr['npr_count']) / array_sum($arr['total_calls']) * 100, 2); ?>%</td>
            <td><?php echo round(array_sum($arr['total_calls']));?></td>
            <td><?php echo round(array_sum($arr['not_zero_calls']));?></td>
            <td><?php echo round(array_sum($arr['success_calls']));?></td>
            <td><?php echo round(array_sum($arr['busy_calls']));?></td>
        </tr>
        <?php
    endif;
    ?>
    </tbody>
</table>