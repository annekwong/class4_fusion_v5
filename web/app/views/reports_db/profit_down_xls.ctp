<table class="list" style="color:#4B9100;">
    <thead>
    <tr>
        <?php foreach($show_fields as $field): ?>
            <td rowspan="2"><?php echo $replace_fields[$field]; ?></td>
        <?php endforeach; ?>
        <td colspan="2">Call Duration</td>
        <td colspan="2">Profit</td>
        <td colspan="2">Calls</td>
        <th rowspan="2"><?php __('Ingress Cost') ?></th>
        <th rowspan="2"><?php __('Egress Cost') ?></th>
        <th rowspan="2"><?php __('NPR Count') ?></th>
        <th rowspan="2"><?php __('NPR') ?></th>
    </tr>
    <tr>
        <td>min</td>
        <td>%</td>
        <td>USA</td>
        <td>%</td>
        <td>Total</td>
        <td>Not Zero</td>
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
        $arr['bill_time'][$i] = $item[0]['bill_time'];
        $arr['npr_count'][$i] = $item[0]['npr_count'];
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
            <td><?php echo round($arr['bill_time'][$i] / 60, 2);?></td>
            <td><?php echo array_sum($arr['duration']) == 0 ? 0 : round($arr['duration'][$i] / array_sum($arr['duration']) * 100, 2); ?>%</td>
            <td><?php echo number_format($arr['inbound_call_cost'][$i] - $arr['outbound_call_cost'][$i], 5); ?></td>
            <td><?php echo number_format($arr['inbound_call_cost'][$i] == 0 ? 0 : ($arr['inbound_call_cost'][$i] - $arr['outbound_call_cost'][$i]) / $arr['outbound_call_cost'][$i] * 100, 2);?>%</td>
            <td><?php echo round($arr['total_calls'][$i]);?></td>
            <td><?php echo round($arr['not_zero_calls'][$i]);?></td>
            <td><?php echo round($arr['inbound_call_cost'][$i], 5);?></td>
            <td><?php echo round($arr['outbound_call_cost'][$i], 5);?></td>
            <td><?php echo number_format($arr['npr_count'][$i]); ?></td>
            <td><?php echo number_format($arr['total_calls'][$i] == 0 ? 0 : $arr['npr_count'][$i] / $arr['total_calls'][$i] * 100, 2); ?>%</td>
        </tr>
        <?php
        $i++;
    endforeach;
    ?>
    <?php
    $count_group = count($show_fields);
    if($count_group && count($data)):
        ?>
        <tr style="color:#000;">
            <td colspan="<?php echo $count_group; ?>">Total:</td>
            <td><?php echo round(array_sum($arr['duration']) / 60, 2);?></td>
            <td>100%</td>
            <td><?php echo number_format(array_sum($arr['inbound_call_cost']) - array_sum($arr['outbound_call_cost']), 5); ?></td>
            <td><?php echo number_format(array_sum($arr['inbound_call_cost']) == 0 ? 0 : (array_sum($arr['inbound_call_cost']) - array_sum($arr['outbound_call_cost'])) / array_sum($arr['outbound_call_cost']) * 100, 2);?>%</td>
            <td><?php echo round(array_sum($arr['total_calls']));?></td>
            <td><?php echo round(array_sum($arr['not_zero_calls']));?></td>
            <td><?php echo round(array_sum($arr['inbound_call_cost']), 5);?></td>
            <td><?php echo round(array_sum($arr['outbound_call_cost']), 5);?></td>
            <td><?php echo number_format(array_sum($arr['npr_count'])); ?></td>
            <td><?php echo number_format(array_sum($arr['total_calls']) == 0 ? 0 : array_sum($arr['npr_count']) / array_sum($arr['total_calls']) * 100, 2); ?>%</td>
        </tr>
        <?php
    endif;
    ?>
    </tbody>
</table>