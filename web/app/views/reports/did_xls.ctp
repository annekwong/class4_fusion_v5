<table class="list" style="color:#4B9100;" border="1">
    <thead>
    <tr>
        <?php foreach ($show_fields as $field): ?>
            <th><?php echo $replace_fields[$field]; ?></th>
        <?php endforeach; ?>
        <th><?php __('Call Attempt') ?></th>
        <th><?php __('Succ. Call') ?></th>
        <th><?php __('Duration') ?></th>
        <th><?php __('Client Avg Rate') ?></th>
        <th><?php __('Vendor Avg Rate') ?></th>
        <th><?php __('Client Cost') ?></th>
        <th><?php __('Vendor Cost') ?></th>
        <th><?php __('Profit') ?></th>
        <th><?php __('ASR') ?></th>
        <th><?php __('ACD(min)')?></th>
        <th><?php __('PDD') ?></th>
    </tr>
    </thead>
    <tbody>
    <?php
    $i = 0;
    $arr = array();
    foreach ($data as $item):
        $arr['duration'][$i] = $item[0]['duration'];
        $arr['client_bill_time'][$i] = $item[0]['egress_bill_time'];
        $arr['vendor_bill_time'][$i] = $item[0]['ingress_bill_time'];
        $arr['client_call_cost'][$i] = $item[0]['egress_call_cost'];
        $arr['vendor_call_cost'][$i] = $item[0]['ingress_call_cost'];
        $arr['total_calls'][$i] = $item[0]['ingress_total_calls'];
        $arr['not_zero_calls'][$i] = $item[0]['not_zero_calls'];
        $arr['success_calls'][$i] = $item[0]['ingress_success_calls'];
        $arr['vendor_busy_calls'][$i] = $item[0]['ingress_busy_calls'];
        $arr['client_busy_calls'][$i] = $item[0]['egress_busy_calls'];
        $arr['vendor_cancel_calls'][$i] = $item[0]['ingress_cancel_calls'];
        $arr['client_cancel_calls'][$i] = $item[0]['egress_cancel_calls'];
        $arr['pdd'][$i] = $item[0]['pdd'];
        ?>
        <tr>
            <?php foreach (array_keys($show_fields) as $key): ?>
                <td style="color:#6694E3;"><?php echo $item[0][$key]; ?></td>
            <?php endforeach; ?>
            <td><?php echo $arr['total_calls'][$i]; ?></td>
            <td><?php echo $arr['success_calls'][$i]; ?></td>
            <td><?php echo number_format($arr['duration'][$i] / 60, 2); ?></td>
            <td><?php echo number_format($arr['client_bill_time'][$i] == 0 ? 0 : $arr['client_call_cost'][$i] / ($arr['client_bill_time'][$i] / 60), 5); ?></td>
            <td><?php echo number_format($arr['vendor_bill_time'][$i] == 0 ? 0 : $arr['vendor_call_cost'][$i] / ($arr['vendor_bill_time'][$i] / 60), 5); ?></td>
            <td><?php echo number_format($arr['client_call_cost'][$i],5); ?></td>
            <td><?php echo number_format($arr['vendor_call_cost'][$i],5); ?></td>
            <td><?php echo number_format($arr['vendor_call_cost'][$i] - $arr['client_call_cost'][$i], 5); ?></td>
            <td><?php echo ($arr['vendor_busy_calls'][$i] + $arr['vendor_cancel_calls'][$i] + $arr['not_zero_calls'][$i]) == 0 ? 0 : round($arr['not_zero_calls'][$i] / ($arr['vendor_busy_calls'][$i] + $arr['vendor_cancel_calls'][$i] + $arr['not_zero_calls'][$i]) * 100, 2) ?>%</td>
            <td><?php echo round($arr['not_zero_calls'][$i] == 0 ? 0 : $arr['duration'][$i] / $arr['not_zero_calls'][$i] / 60, 2); ?></td>
            <td><?php echo $arr['pdd'][$i]; ?></td>
        </tr>
        <?php
        $i++;
    endforeach;
    ?>
    <?php
    $count_group = count($show_fields);
    if ($count_group && count($data)):
        ?>
        <tr style="color:#000000;">
            <td colspan="<?php echo $count_group; ?>"><?php __('Total')?></td>
            <td><?php echo array_sum($arr['total_calls']); ?></td>
            <td><?php echo array_sum($arr['success_calls']); ?></td>
            <td><?php echo number_format(array_sum($arr['duration']) / 60, 2); ?></td>
            <td><?php echo number_format(array_sum($arr['client_bill_time']) == 0 ? 0 : array_sum($arr['client_call_cost']) / (array_sum($arr['client_bill_time']) / 60), 5); ?></td>
            <td><?php echo number_format(array_sum($arr['vendor_bill_time']) == 0 ? 0 : array_sum($arr['vendor_call_cost']) / (array_sum($arr['vendor_bill_time']) / 60), 5); ?></td>
            <td><?php echo number_format(array_sum($arr['client_call_cost']), 5); ?></td>
            <td><?php echo number_format(array_sum($arr['vendor_call_cost']), 5); ?></td>
            <td><?php echo number_format(array_sum($arr['vendor_call_cost']) - array_sum($arr['client_call_cost']), 5); ?></td>
            <td><?php echo (array_sum($arr['vendor_busy_calls']) + array_sum($arr['vendor_cancel_calls']) + array_sum($arr['not_zero_calls'])) == 0 ? 0 : round(array_sum($arr['not_zero_calls']) / (array_sum($arr['vendor_busy_calls']) + array_sum($arr['vendor_cancel_calls']) + array_sum($arr['not_zero_calls'])) * 100, 2) ?>%</td>
            <td><?php echo round(array_sum($arr['not_zero_calls']) == 0 ? 0 : array_sum($arr['duration']) / array_sum($arr['not_zero_calls']) / 60, 2); ?></td>
            <td><?php echo array_sum($arr['pdd']); ?></td>
        </tr>
    <?php
    endif;
    ?>
    </tbody>
</table>