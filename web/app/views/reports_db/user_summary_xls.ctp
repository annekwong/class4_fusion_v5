<?php
$login_type = $_SESSION['login_type'];
if ($_SESSION['login_type'] == 3 && isset($_SESSION['role_menu']['Payment_Invoice']['view_cost_and_rate'])) {
    $cr_flg = true;
} else {
//                $cr_flg = false;
    $cr_flg = true;
}?>

<table class="list footable table table-striped tableTools table-bordered  table-white table-primary" style="color:#4B9100;">
    <thead>
    <tr>
        <?php foreach ($show_fields as $field): ?>
            <th><?php echo $replace_fields[$field]; ?></th>
        <?php endforeach; ?>
        <!--                        <th>ABR</th>-->
        <th>ASR</th>
        <th>ACD(min)</th>
        <?php if($login_type !=3){?>
            <th>ALOC</th>
        <?php }?>
        <th>PDD(ms)</th>
        <th colspan="2">Time(min)</th>
        <th>Usage Charge(USA)</th>
        <?php if ($cr_flg) { ?>

            <?php if($login_type !=3){?>
                <th>Total Cost</th>
            <?php }?>

            <?php if (isset($_GET['show_inter_intra'])): ?>
                <th>Inter Cost</th>
                <th>Intra Cost</th>
            <?php endif; ?>
            <?php if (isset($_GET['rate_display_as']) && $_GET['rate_display_as'] == 1): ?>
                <th>Actual Rate</th>
            <?php else: ?>
                <th>Avg Rate</th>
            <?php endif; ?>
        <?php } ?>
        <?php if ($type == '1'): ?>
            <th colspan="4">Calls</th>
        <?php else: ?>
            <th colspan="3">Calls</th>
        <?php endif; ?>
    </tr>
    <tr>
        <?php for ($i = 0; $i < count($show_fields); $i++): ?>
            <th>&nbsp;</th>
        <?php endfor; ?>
        <!--                        <th></th>-->
        <th></th>
        <th></th>
        <th></th>
        <?php if($login_type !=3){?>
            <th></th>
        <?php }?>
        <th>Total Duration</th>
        <th>Total Billable Time</th>
        <th></th>
        <?php if ($cr_flg) { ?>
            <?php if($login_type !=3){?>
                <th></th>
            <?php }?>
            <?php if (isset($_GET['show_inter_intra'])): ?>
                <th></th>
                <th></th>
            <?php endif; ?>
            <?php if (isset($_GET['rate_display_as']) && $_GET['rate_display_as'] == 1): ?>
                <th></th>
            <?php else: ?>
                <th></th>
            <?php endif; ?>
        <?php } ?>
        <th>Total Calls</th>
        <th>Not Zero</th>
        <?php if($login_type !=3){?>
            <th>Success Calls</th>
        <?php }?>
        <th>Busy Calls</th>
    </thead>
    <tbody>
    <?php
    $i = 0;
    $arr = array();
    $sum_total_final_calls = 0;
    foreach ($data as $item):
        $arr['duration'][$i] = $item[0]['duration'];
        $arr['bill_time'][$i] = $item[0]['bill_time'];
        $arr['call_cost'][$i] = $item[0]['call_cost'];
        $arr['cancel_calls'][$i] = $item[0]['cancel_calls'];
        if ($type == 1):
            $arr['lnp_cost'][$i] = $item[0]['lnp_cost'];
            $arr['lrn_calls'][$i] = $item[0]['lrn_calls'];
        endif;
        $arr['total_calls'][$i] = $item[0]['total_calls'];
        $arr['inter_cost'][$i] = $item[0]['inter_cost'];
        $arr['intra_cost'][$i] = $item[0]['intra_cost'];
        $arr['not_zero_calls'][$i] = $item[0]['not_zero_calls'];
        $arr['success_calls'][$i] = $item[0]['success_calls'];
        $arr['busy_calls'][$i] = $item[0]['busy_calls'];
        $arr['pdd'][$i] = $item[0]['pdd'];
        $total_final_calls = $item[0]['total_final_calls'];
        $sum_total_final_calls += $total_final_calls;
        if (isset($_GET['rate_display_as']) && $_GET['rate_display_as'] == 1)
        {
            $arr['actual_rate'][$i] = $item[0]['actual_rate'];
        }
        ?>
        <tr>
            <?php foreach (array_keys($show_fields) as $key): ?>
                <td style="color:#6694E3;"><?php echo $item[0][$key]; ?></td>
            <?php endforeach; ?>
            <td><?php echo round($arr['total_calls'][$i] == 0 ? 0 : $arr['not_zero_calls'][$i] / $arr['total_calls'][$i] * 100, 2); ?>%</td>
            <!--                            <td>--><?php //echo ($arr['busy_calls'][$i] + $arr['cancel_calls'][$i] + $arr['not_zero_calls'][$i]) == 0 ? 0 : round($arr['not_zero_calls'][$i] / ($arr['busy_calls'][$i] + $arr['cancel_calls'][$i] + $arr['not_zero_calls'][$i]) * 100, 2) ?><!--%</td>-->
            <td><?php echo round($arr['not_zero_calls'][$i] == 0 ? 0 : $arr['duration'][$i] / $arr['not_zero_calls'][$i] / 60, 2); ?></td>
            <td>
                <?php
                                echo ($total_final_calls == 0 ? 0 : number_format($arr['pdd'][$i] / $total_final_calls, 2)) ;
                ?>


            </td>
            <?php if($login_type !=3){?>
                <td><?php echo round($arr['not_zero_calls'][$i] == 0 ? 0 : $arr['pdd'][$i] / $arr['not_zero_calls'][$i]); ?></td>
            <?php }?>
            <td><?php echo number_format($arr['duration'][$i] / 60, 2); ?></td>
            <td><?php echo number_format($arr['bill_time'][$i] / 60, 2); ?></td>
            <td><?php echo number_format($arr['call_cost'][$i], 5); ?></td>

            <?php if($login_type !=3){ ?>
                <?php if ($type == '1'): ?>
                    <?php if ($cr_flg) { ?>
                        <td><?php echo number_format($arr['call_cost'][$i] + $arr['lnp_cost'][$i], 5); ?></td>
                    <?php } ?>
                <?php else: ?>
                    <td><?php echo number_format($arr['call_cost'][$i], 5); ?></td>
                <?php endif; ?>
            <?php } ?>

            <?php if ($cr_flg) { ?>
                <?php if (isset($_GET['show_inter_intra'])): ?>
                    <td><?php echo number_format($arr['inter_cost'][$i], 5); ?></td>
                    <td><?php echo number_format($arr['intra_cost'][$i], 5); ?></td>
                <?php endif; ?>
                <?php if (isset($_GET['rate_display_as']) && $_GET['rate_display_as'] == 1): ?>
                    <td><?php echo $arr['actual_rate'][$i] ?></td>
                <?php else: ?>
                    <td><?php echo number_format($arr['bill_time'][$i] == 0 ? 0 : $arr['call_cost'][$i] / ($arr['bill_time'][$i] / 60), 5); ?></td>
                <?php endif; ?>
            <?php } ?>
            <td><?php echo number_format($arr['total_calls'][$i]); ?></td>
            <td><?php echo number_format($arr['not_zero_calls'][$i]); ?></td>
            <?php if($login_type !=3){?>
                <td><?php echo number_format($arr['success_calls'][$i]); ?></td>
            <?php }?>
            <td><?php echo number_format($arr['busy_calls'][$i]); ?></td>
        </tr>
        <?php
        $i++;
    endforeach;
    ?>

    <?php
    $count_group = count($show_fields);
    if ($count_group && count($data)):
        ?>
        <tr style="color:#000;">
            <td colspan="<?php echo $count_group; ?>">Total:</td>
            <td><?php echo round(array_sum($arr['total_calls']) == 0 ? 0 : array_sum($arr['not_zero_calls']) / array_sum($arr['total_calls']) * 100, 2); ?>%</td>
            <!--                            <td>--><?php //echo (array_sum($arr['busy_calls']) + array_sum($arr['cancel_calls']) + array_sum($arr['not_zero_calls'])) == 0 ? 0 : round(array_sum($arr['not_zero_calls']) / (array_sum($arr['busy_calls']) + array_sum($arr['cancel_calls']) + array_sum($arr['not_zero_calls'])) * 100, 2) ?><!--%</td>-->
            <td><?php echo round(array_sum($arr['not_zero_calls']) == 0 ? 0 : array_sum($arr['duration']) / array_sum($arr['not_zero_calls']) / 60, 2); ?></td>
            <td><?php echo number_format($sum_total_final_calls == 0 ? 0 : array_sum($arr['pdd']) / $sum_total_final_calls, 2); ?></td>
            <?php if ($login_type != 3) {?>
                <td><?php echo round(array_sum($arr['not_zero_calls']) == 0 ? 0 : array_sum($arr['pdd']) / array_sum($arr['not_zero_calls'])); ?></td>
            <?php } ?>
            <td><?php echo number_format(array_sum($arr['duration']) / 60, 2); ?></td>
            <td><?php echo number_format(array_sum($arr['bill_time']) / 60, 2); ?></td>
            <td><?php echo number_format(array_sum($arr['call_cost']), 5); ?></td>

            <?php if($login_type !=3){ ?>
                <?php if ($type == '1'): ?>
                    <?php if ($cr_flg) { ?>
                        <td><?php echo number_format(array_sum($arr['call_cost']) + array_sum($arr['lnp_cost']), 5); ?></td>
                    <?php } ?>
                <?php else: ?>
                    <td><?php echo number_format(array_sum($arr['call_cost']), 5); ?></td>
                <?php endif; ?>
            <?php } ?>

            <?php if ($cr_flg) { ?>
                <?php if (isset($_GET['show_inter_intra'])): ?>
                    <td><?php echo number_format(array_sum($arr['inter_cost']), 5); ?></td>
                    <td><?php echo number_format(array_sum($arr['intra_cost']), 5); ?></td>
                <?php endif; ?>
                <?php if (isset($_GET['rate_display_as']) && $_GET['rate_display_as'] == 1): ?>
                    <td><?php echo array_sum($arr['actual_rate']); ?></td>
                <?php else: ?>
                    <td><?php echo number_format(array_sum($arr['bill_time']) == 0 ? 0 : array_sum($arr['call_cost']) / (array_sum($arr['bill_time']) / 60), 5); ?></td>
                <?php endif; ?>
            <?php } ?>

            <td><?php echo number_format(array_sum($arr['total_calls'])); ?></td>
            <td><?php echo number_format(array_sum($arr['not_zero_calls'])); ?></td>
            <?php if($login_type !=3){?>
                <td><?php echo number_format(array_sum($arr['success_calls'])); ?></td>
            <?php }?>
            <td><?php echo number_format(array_sum($arr['busy_calls'])); ?></td>
            <?php if ($type == '1'  && $login_type !=3): ?>
                <td><?php echo number_format(array_sum($arr['lrn_calls'])); ?></td>
            <?php endif; ?>
        </tr>
        <?php
    endif;
    ?>
    </tbody>
</table>