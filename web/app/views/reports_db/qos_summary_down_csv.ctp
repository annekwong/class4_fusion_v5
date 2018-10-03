<?php

$layout = array();

foreach ($show_fields as $field) {
    $layout[] = $replace_fields[$field];
}

$layout[] = "ASR";
$layout[] = "ACD(min)";
$layout[] = "PDD(ms)";
$layout[] = "Total Billable Time";
$layout[] = "Total Calls";
$layout[] = "Not Zero";
$layout[] = "Busy Calls";

echo implode(',', $layout) . "\n";


$i = 0;
$arr = array();
foreach ($data as $item) {
    $layout = array();
    if ($type == 2 && isset($item[0]['egress_id']) && !$item[0]['egress_id']) {
        continue;
    }
    if ($type == 1 && isset($item[0]['ingress_id']) && !$item[0]['ingress_id']) {
        continue;
    }
    $arr['duration'][$i] = $item[0]['duration'];
    $arr['bill_time'][$i] = $item[0]['bill_time'];
    $arr['cancel_calls'][$i] = $item[0]['cancel_calls'];
    $arr['total_calls'][$i] = $item[0]['total_calls'];
    $arr['not_zero_calls'][$i] = $item[0]['not_zero_calls'];
    $arr['busy_calls'][$i] = $item[0]['busy_calls'];
    $arr['pdd'][$i] = $item[0]['pdd'];

    foreach (array_keys($show_fields) as $key) {
        $layout[] = $item[0][$key];
    }

    $layout[] = round($arr['total_calls'][$i] == 0 ? 0 : $arr['not_zero_calls'][$i] / $arr['total_calls'][$i] * 100, 2).'%';
    $layout[] = round($arr['not_zero_calls'][$i] == 0 ? 0 : $arr['duration'][$i] / $arr['not_zero_calls'][$i] / 60, 2);
    $layout[] = round($arr['not_zero_calls'][$i] == 0 ? 0 : $arr['pdd'][$i] / $arr['not_zero_calls'][$i]);
    $layout[] = number_format($arr['bill_time'][$i] / 60, 2, '.', '');
    $layout[] = round($arr['total_calls'][$i]);
    $layout[] = round($arr['not_zero_calls'][$i]);
    $layout[] = round($arr['busy_calls'][$i]);
    $i++;

    echo implode(',', $layout) . "\n";
}

$count_group = count($show_fields);
if ($count_group && count($data)) {
    $layout = array();

    $layout[] = "Total";
    for ($i = 0; $i < count($show_fields) -1; $i++):
        $layout[] = "";
    endfor;
    $layout[] = round(array_sum($arr['total_calls']) == 0 ? 0 : array_sum($arr['not_zero_calls']) / array_sum($arr['total_calls']) * 100, 2).'%';
    $layout[] = round(array_sum($arr['not_zero_calls']) == 0 ? 0 : array_sum($arr['duration']) / array_sum($arr['not_zero_calls']) / 60, 2);
    $layout[] = round(array_sum($arr['not_zero_calls']) == 0 ? 0 : array_sum($arr['pdd']) / array_sum($arr['not_zero_calls']));
    $layout[] = number_format(array_sum($arr['bill_time']) / 60, 2, '.', '');
    $layout[] = round(array_sum($arr['total_calls']));
    $layout[] = round(array_sum($arr['not_zero_calls']));
    $layout[] = round(array_sum($arr['busy_calls']));
    echo implode(',', $layout);
}

