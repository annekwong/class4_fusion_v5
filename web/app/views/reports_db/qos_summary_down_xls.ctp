
<table class="list" style="color:#4B9100;">
    <thead>
        <tr>
            <?php foreach($show_fields as $field): ?>
            <th rowspan="2"><?php echo $replace_fields[$field]; ?></th>
            <?php endforeach; ?>
            <th rowspan="2">ASR</th>
            <th rowspan="2">ACD(min)</th>
            <th rowspan="2">PDD(ms)</th>
            <th colspan="1">Time(min)</td>
            <th colspan="4">Calls</th>
        </tr>
        <tr>
<!--            --><?php //for($i=0;$i<count($show_fields);$i++):?>
<!--            <td>&nbsp;</td>-->
<!--            --><?php //endfor; ?>
<!--            <td></td>-->
<!--            <td></td>-->
<!--            <td></td>-->
<!--            <td></td>-->
<!--            <td></td>-->
            <td>Total Billable Time</td>
            <td>Total Calls</td>
            <td>Not Zero</td>
            <td>Busy Calls</td>
    </thead>
    <tbody>
        <?php
            $i = 0;
            $arr = array();
            foreach($data as $item):
                $arr['duration'][$i] = $item[0]['duration'];
                $arr['bill_time'][$i] = $item[0]['bill_time'];
                $arr['cancel_calls'][$i] = $item[0]['cancel_calls'];
                $arr['total_calls'][$i] = $item[0]['total_calls'];
                $arr['not_zero_calls'][$i] = $item[0]['not_zero_calls'];
                $arr['busy_calls'][$i] = $item[0]['busy_calls'];
                $arr['pdd'][$i] = $item[0]['pdd'];

        ?>
        <tr>
            <?php foreach(array_keys($show_fields) as $key): ?>
            <td style="color:#6694E3;"><?php echo $item[0][$key]; ?></td>
            <?php endforeach; ?>
            <td><?php echo round($arr['total_calls'][$i] == 0 ? 0 : $arr['not_zero_calls'][$i] / $arr['total_calls'][$i] * 100, 2); ?>%</td>
            <td><?php echo round($arr['not_zero_calls'][$i] == 0 ? 0 : $arr['duration'][$i] / $arr['not_zero_calls'][$i] / 60, 2); ?></td>
            <td><?php echo round($arr['not_zero_calls'][$i] == 0 ? 0 : $arr['pdd'][$i] / $arr['not_zero_calls'][$i]); ?></td>
            <td><?php echo number_format($arr['bill_time'][$i] / 60, 2); ?></td> 

            <td><?php echo number_format($arr['total_calls'][$i]); ?></td> 
            <td><?php echo number_format($arr['not_zero_calls'][$i]); ?></td>
            <td><?php echo number_format($arr['busy_calls'][$i]); ?></td>

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
            <td><?php echo round(array_sum($arr['total_calls']) == 0 ? 0 : array_sum($arr['not_zero_calls']) / array_sum($arr['total_calls']) * 100, 2); ?>%</td>
            <td><?php echo round(array_sum($arr['not_zero_calls']) == 0 ? 0 : array_sum($arr['duration']) / array_sum($arr['not_zero_calls']) / 60, 2); ?></td>
            <td><?php echo round(((array_sum($arr['busy_calls']) + array_sum($arr['cancel_calls']) +array_sum($arr['not_zero_calls'])) == 0  ? 0 :array_sum($arr['not_zero_calls']) / (array_sum($arr['busy_calls']) + array_sum($arr['cancel_calls']) +array_sum($arr['not_zero_calls']))) * (array_sum($arr['not_zero_calls']) == 0 ? 0 : array_sum($arr['duration']) / array_sum($arr['not_zero_calls']) / 60),2); ?></td>
            <td><?php echo number_format(array_sum($arr['bill_time']) / 60, 2); ?></td>
            <td><?php echo number_format(array_sum($arr['total_calls'])); ?></td> 
            <td><?php echo number_format(array_sum($arr['not_zero_calls'])); ?></td>
            <td><?php echo number_format(array_sum($arr['busy_calls'])); ?></td>
        </tr>
        <?php
            endif;
        ?>
    </tbody>
</table>
