<table class="list" style="color:#4B9100;">
<thead>
    <tr>
        <?php foreach($show_fields as $field): ?>
        <td><?php echo $replace_fields[$field]; ?></td>
        <?php endforeach; ?>
        <td colspan="2">Time(min)</td>
        <td>Usage Charge(USA)</td>
        <td>Total Cost</td>
        <td>Avg Rate</td>
        <td colspan="2">Calls</td>
    </tr>
    <tr>
        <?php for($i=0;$i<count($show_fields);$i++):?>
        <td>&nbsp;</td>
        <?php endfor; ?>
        <td>Total Duration</td>
        <td>Total Billable Time</td>
        <td></td>
        <td></td>
        <td></td>
        <td>Total Calls</td>
        <td>Not Zero</td>
</thead>
<tbody>
    <?php
        $i = 0;
        $arr = array();
        foreach($data as $item):
            $arr['duration'][$i] = $item[0]['duration'];
            $arr['bill_time'][$i] = $item[0]['bill_time'];
            $arr['call_cost'][$i] = $item[0]['call_cost'];                    
            $arr['total_calls'][$i] = $item[0]['total_calls'];
            $arr['not_zero_calls'][$i] = $item[0]['not_zero_calls'];
            $arr['success_calls'][$i] = $item[0]['success_calls'];
            $arr['did'][$i] = $item[0]['did'];
    ?>
    <tr>
        <?php foreach(array_keys($show_fields) as $key): ?>
        <td style="color:#6694E3;"><?php echo $item[0][$key]; ?></td>
        <?php endforeach; ?>
        <td><?php echo $arr['did']; ?></td>
        <td><?php echo number_format($arr['duration'][$i] / 60, 2); ?></td>
        <td><?php echo number_format($arr['bill_time'][$i] / 60, 2); ?></td> 
        <td><?php echo number_format($arr['call_cost'][$i], 5); ?></td>
        <td><?php echo number_format($arr['bill_time'][$i] == 0 ? 0 : $arr['call_cost'][$i] / ($arr['bill_time'][$i] / 60), 5); ?></td>
        <td><?php echo number_format($arr['total_calls'][$i]); ?></td> 
        <td><?php echo number_format($arr['not_zero_calls'][$i]); ?></td>
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
        <td></td>
        <td><?php echo number_format(array_sum($arr['duration']) / 60, 2); ?></td>
        <td><?php echo number_format(array_sum($arr['bill_time']) / 60, 2); ?></td>
        <td><?php echo number_format(array_sum($arr['call_cost']), 5); ?></td>
        <td><?php echo number_format(array_sum($arr['bill_time']) == 0 ? 0 : array_sum($arr['call_cost']) / (array_sum($arr['bill_time']) / 60), 5); ?></td>
        <td><?php echo number_format(array_sum($arr['total_calls'])); ?></td> 
        <td><?php echo number_format(array_sum($arr['not_zero_calls'])); ?></td>
    </tr>
    <?php
        endif;
    ?>
</tbody>
</table>