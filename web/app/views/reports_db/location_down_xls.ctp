    <table class="list" style="color:#4B9100;">
        <thead>
            <tr>
                <?php foreach($show_fields as $field): ?>
                <td><?php echo $replace_fields[$field]; ?></td>
                <?php endforeach; ?>
                <td>Duration</td>
                <td>Call Count</td>
                <td>Revenue</td>
                <td>Cost</td>
                <td>Profit</td>
                <td>Profit(%)</td>
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
            ?>
            <tr>
                <?php foreach(array_keys($show_fields) as $key): ?>
                <td style="color:#6694E3;"><?php echo $item[0][$key]; ?></td>
                <?php endforeach; ?>
                <td><?php echo round($arr['duration'][$i] / 60, 2);?></td>
                <td><?php echo round($arr['total_calls'][$i]);?></td>
                <td><?php echo round($arr['inbound_call_cost'][$i], 5);?></td>
                <td><?php echo round($arr['outbound_call_cost'][$i], 5);?></td>
                <td><?php echo number_format($arr['inbound_call_cost'][$i] - $arr['outbound_call_cost'][$i], 5);?></td>
                <td><?php echo number_format($arr['inbound_call_cost'][$i] == 0 ? 0 : ($arr['inbound_call_cost'][$i] - $arr['outbound_call_cost'][$i]) / $arr['inbound_call_cost'][$i] * 100, 5);?>%</td>
                
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
                <td><?php echo round(array_sum($arr['total_calls']));?></td>
                <td><?php echo round(array_sum($arr['inbound_call_cost']), 5);?></td>
                <td><?php echo round(array_sum($arr['outbound_call_cost']), 5);?></td>
                <td><?php echo number_format(array_sum($arr['inbound_call_cost']) - array_sum($arr['outbound_call_cost']), 5);?></td>
                <td><?php echo number_format(array_sum($arr['inbound_call_cost']) == 0 ? 0 : (array_sum($arr['inbound_call_cost']) - array_sum($arr['outbound_call_cost'])) / array_sum($arr['inbound_call_cost']) * 100, 5);?>%</td>
            </tr>
            <?php
                endif;
            ?>
        </tbody>
    </table>
