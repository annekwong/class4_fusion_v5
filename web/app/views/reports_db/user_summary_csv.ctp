<?php

$fields = array(
    'ASR',
    'ACD',
    'PDD',
    'Total Duration',
    'Total Billable Time',
    'Usage Charge(USA)',
    'Avg Rate',
    'Total Calls',
    'Not Zero Calls',
    'Busy Calls'
);



if(!empty ($show_fields)) {
    $replaced = [];
    foreach($show_fields as $init_field){
        $replaced[] = $replace_fields[$init_field];
    }
    if(!empty($replaced)){
       echo implode(',', $replaced) . ',';
    }else{
       echo implode(',', $show_fields) . ',';
    }
}

echo implode(',', $fields) . "\n";

$i = 0;
$arr = array();
foreach ($data as $item) {
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
    if (isset($_GET['rate_display_as']) && $_GET['rate_display_as'] == 1) {
        $arr['actual_rate'][$i] = $item[0]['actual_rate'];
    }

    foreach (array_keys($show_fields) as $key) {
        echo $item[0][$key] . ',';
    }

    echo round($arr['total_calls'][$i] == 0 ? 0 : $arr['not_zero_calls'][$i] / $arr['total_calls'][$i] * 100, 2) . '%,';

    echo round($arr['not_zero_calls'][$i] == 0 ? 0 : $arr['duration'][$i] / $arr['not_zero_calls'][$i] / 60, 2) . ',';

    echo ($total_final_calls == 0 ? 0 : number_format($arr['pdd'][$i] / $total_final_calls, 2)) ;

//    if ($login_type != 3) {
//        echo round($arr['not_zero_calls'][$i] == 0 ? 0 : $arr['pdd'][$i] / $arr['not_zero_calls'][$i]) . ',';
//    }
    echo number_format($arr['duration'][$i] / 60, 2) . ',';
    echo number_format($arr['bill_time'][$i] / 60, 2) . ',';
    echo number_format($arr['call_cost'][$i], 5) . ',';

    if ($login_type != 3) {
        if ($type == '1') {
            if ($cr_flg) {
                echo number_format($arr['call_cost'][$i] + $arr['lnp_cost'][$i], 5) . ',';
            }
        } else {
            echo number_format($arr['call_cost'][$i], 5) . ',';
        }

    }

//    if ($cr_flg) {
    if (isset($_GET['show_inter_intra'])) {
        echo number_format($arr['inter_cost'][$i], 5) . ',';
        echo number_format($arr['intra_cost'][$i], 5) . ',';
    }
    if (isset($_GET['rate_display_as']) && $_GET['rate_display_as'] == 1) {
        echo number_format($arr['actual_rate'][$i], 5) . ',';
    }
    echo number_format($arr['bill_time'][$i] == 0 ? 0 : $arr['call_cost'][$i] / ($arr['bill_time'][$i] / 60), 5) . ',';

//    }
    echo number_format($arr['total_calls'][$i], 2, '.', '') . ',';
    echo number_format($arr['not_zero_calls'][$i], 2, '.', '') . ',';
    echo number_format($arr['busy_calls'][$i], 2, '.', '') . "\n";

    $i++;
}

$count_group = count($show_fields);
if ($count_group && count($data)) {
    echo "Total:,";
    for($i=0; $i<$count_group -1 ; $i ++ ){
      echo ",";
    }

    echo round(array_sum($arr['total_calls']) == 0 ? 0 : array_sum($arr['not_zero_calls']) / array_sum($arr['total_calls']) * 100, 2) . '%,';

    echo round(array_sum($arr['not_zero_calls']) == 0 ? 0 : array_sum($arr['duration']) / array_sum($arr['not_zero_calls']) / 60, 2) . ',';
    echo number_format($sum_total_final_calls == 0 ? 0 : array_sum($arr['pdd']) / $sum_total_final_calls, 2);
    echo number_format(array_sum($arr['duration']) / 60, 2) . ',';
    echo number_format(array_sum($arr['bill_time']) / 60, 2) . ',';
    echo number_format(array_sum($arr['call_cost']), 5) . ',';

    if ($login_type != 3) {
        if ($type == '1') {
            if ($cr_flg) {
                echo number_format(array_sum($arr['call_cost']) + array_sum($arr['lnp_cost']), 5) . ',';
            }
        } else {
            echo number_format(array_sum($arr['call_cost']), 5) . ',';
        }
    }

//    if ($cr_flg) {
    if (isset($_GET['show_inter_intra'])) {
        echo number_format(array_sum($arr['inter_cost']), 5) . ',';
        echo number_format(array_sum($arr['intra_cost']), 5) . ',';
    }

    if (isset($_GET['rate_display_as']) && $_GET['rate_display_as'] == 1) {
        echo array_sum($arr['actual_rate'], 5) . ',';
    } else {
        echo number_format(array_sum($arr['bill_time']) == 0 ? 0 : array_sum($arr['call_cost']) / (array_sum($arr['bill_time']) / 60), 5) . ',';
    }
//    }

    echo number_format(array_sum($arr['total_calls']), 2, '.', '') . ',';
    echo number_format(array_sum($arr['not_zero_calls']), 2, '.', '') . ',';
    echo number_format(array_sum($arr['busy_calls']), 2, '.', '') . "\n";
}
