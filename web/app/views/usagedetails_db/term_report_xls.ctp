<?php
$days = array();
$startdate = strtotime($start);
$enddate = strtotime($end);
$day = round(($enddate - $startdate) / 3600 / 24);
$dt_begin = new DateTime($start);
for ($i = 0; $i < $day; $i++)
{
    if ($i > 0)
    {
        $dt_begin->modify('+1 days');
    }
    array_push($days, $dt_begin->format('Y-m-d'));
}
?>
<div class="overflow_x">
    <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">

        <thead>
        <tr>
            <?php
            foreach ($filed_arr as $value):
                if (isset($replace_fields[$value]))
                {
                    echo "<th rowspan=\"2\">" . $replace_fields[$value] . "</th>";
                }
                else
                {
                    echo "<th rowspan=\"2\">" . $value . "</th>";
                }
            endforeach;
            ?>
            <th rowspan="2"><?php __('Not Zero Calls') ?></th>
            <th rowspan="2"><?php __('Total(Min)') ?></th>
            <th colspan="2">Calls < 30s</th>
            <th colspan="2"><?php echo $appCommon->show_order('call_6s', __('Calls <= 6s', true)) ?></th>
            <?php foreach ($days as $item): ?>
                <th colspan="5">
                    <?php echo $item; ?>
                </th>
            <?php endforeach; ?>
        </tr>
        <tr>
            <?php
            //                                foreach ($filed_arr as $value):
            //                                    echo "<th></th>";
            //                                endforeach;
            ?>
            <!--                                <th></th>-->
            <!--                                <th></th>-->
            <th><?php __('Count'); ?></th>
            <th><?php __('%'); ?></th>
            <th><?php __('Count'); ?></th>
            <th><?php __('%'); ?></th>
            <?php
            for ($i = 0; $i < $day; $i++)
            {
                ?>
                <th><?php __('Billed Time (min)') ?></th>
                <th><?php __('ASR (%)') ?></th>
                <th><?php __('ACD (min)') ?></th>
                <th><?php __('NPR Count') ?></th>
                <th><?php __('NPR') ?></th>
            <?php } ?>
        </tr>
        </thead>

        <tbody>
        <?php
        $totalArray = array(
            'not_zero_calls' => 0,
            'total_time' => 0,
            'calls_30' => 0,
            'calls_6' => 0,
            'bill_time' => 0,
            'asr' => 0,
            'acd' => 0,
            'npr_count' => 0
        );

        foreach ($days as $day_item) {
            $totalArray[$day_item] = array(
                'bill_time' => 0,
                'asr' => 0,
                'acd' => 0,
                'npr_count' => 0
            );
        }

        $total_time_total = 0;
        $calls_3_total = 0;
        $time_3_total = 0;
        $calls_6_total = 0;
        $time_6_total = 0;
        $years_total = array();
        foreach ($days as $day)
        {
            $years_total[$day] = 0;
        }
        foreach ($data as $key => $item):
            $total_time_total += $item['total_time'];
            $calls_3_total += $item['calls_30'] + $item['calls_6'];
            $calls_6_total += $item['calls_6'];

            $totalArray['not_zero_calls'] += $item['not_zero_calls'];
            $totalArray['total_time'] += $item['total_time'];
            $totalArray['calls_30'] += $item['calls_30'];
            $totalArray['calls_6'] += $item['calls_6'];

            ?>
            <tr>
                <?php
                foreach ($filed_arr as $value):
                    if (isset($item[$value]))
                    {
                        echo "<td>" . $item[$value] . "</td>";
                    }
                    else
                    {
                        echo "<td></td>";
                    }
                endforeach;
                ?>
                <td><?php echo $item['not_zero_calls']; ?></td>
                <td><?php echo number_format($item['total_time'] / 60, 2); ?></td>
                <td><?php echo $item['calls_30']; ?></td>
                <td><?php echo $item['not_zero_calls'] == 0 ? 0 : number_format(($item['calls_30']) / $item['not_zero_calls'] * 100, 2); ?></td>
                <!--                <td><?php echo number_format($item['time_30'] / 60, 2); ?></td>-->
                <td><?php echo $item['calls_6']; ?></td>
                <td><?php echo $item['calls_6'] == 0 ? 0 : number_format($item['calls_6'] / $item['not_zero_calls'] * 100, 2); ?></td>
                <!--                <td><?php echo number_format($item['time_6'] / 60, 2); ?></td>-->
                <?php
                foreach ($days as $day_item){?>
                    <?php
                    if (array_key_exists($day_item, $item['years'])):
                        $totalArray[$day_item]['bill_time'] += $item['years'][$day_item]['bill_time'];
                        $totalArray[$day_item]['asr'] += $item['years'][$day_item]['total_calls'] == 0 ? 0 : $item['years'][$day_item]['not_zero_calls'] / $item['years'][$day_item]['total_calls'] * 100;
                        $totalArray[$day_item]['acd'] += $item['years'][$day_item]['not_zero_calls'] == 0 ? 0 : $item['years'][$day_item]['total_time'] / $item['years'][$day_item]['not_zero_calls'] / 60;
                        $totalArray[$day_item]['npr_count'] += $item['years'][$day_item]['npr_count'];
                        $totalArray[$day_item]['total_calls'] += $item['years'][$day_item]['total_calls'];
                        ?>
                        <td><?php echo number_format($item['years'][$day_item]['bill_time'] / 60, 2); ?></td>
                        <td><?php echo $item['years'][$day_item]['total_calls'] == 0 ? 0 : number_format($item['years'][$day_item]['not_zero_calls'] / $item['years'][$day_item]['total_calls'] * 100, 2); ?></td>
                        <td><?php echo $item['years'][$day_item]['not_zero_calls'] == 0 ? 0 : number_format($item['years'][$day_item]['total_time'] / $item['years'][$day_item]['not_zero_calls'] / 60, 5); ?></td>
                        <td><?php echo number_format($item['years'][$day_item]['npr_count']); ?></td>
                        <td><?php echo number_format($item['years'][$day_item]['total_calls'] == 0 ? 0 : $item['years'][$day_item]['npr_count'] / $item['years'][$day_item]['total_calls'] * 100, 2); ?>%</td>
                    <?php else: ?>
                        <td>0</td><td>0</td><td>0</td><td>0</td><td>0</td>
                    <?php endif ?>
                <?php } ?>
            </tr>
            <?php
        endforeach;
        ?>

        <tr>
            <td colspan="<?php echo count($filed_arr);?>">Total:</td>
            <td><?php echo $totalArray['not_zero_calls']; ?></td>
            <td><?php echo number_format($totalArray['total_time'] / 60, 2); ?></td>
            <td><?php echo $totalArray['calls_30']; ?></td>
            <td><?php echo $totalArray['not_zero_calls'] == 0 ? 0 : number_format(($totalArray['calls_30']) / $totalArray['not_zero_calls'] * 100, 2); ?></td>
            <td><?php echo $totalArray['calls_6']; ?></td>
            <td><?php echo $totalArray['calls_6'] == 0 ? 0 : number_format($totalArray['calls_6'] / $totalArray['not_zero_calls'] * 100, 2); ?></td>
            <?php foreach ($days as $day_item): ?>
                <?php
                if (array_key_exists($day_item, $totalArray)):
                    ?>
                    <td><?php echo number_format($totalArray[$day_item]['bill_time'] / 60, 2); ?></td>
                    <td><?php echo number_format($totalArray[$day_item]['asr'], 2); ?></td>
                    <td><?php echo number_format($totalArray[$day_item]['acd'], 5); ?></td>
                    <td><?php echo number_format($totalArray[$day_item]['npr_count']); ?></td>
                    <td><?php echo number_format($totalArray[$day_item]['total_calls'] == 0 ? 0 : $totalArray[$day_item]['npr_count'] / $totalArray[$day_item]['total_calls'] * 100, 2); ?>%</td>
                <?php else: ?>
                    <td>0</td><td>0</td><td>0</td><td>0</td><td>0</td>
                <?php endif ?>
            <?php endforeach; ?>
        </tr>
        </tbody>



    </table>