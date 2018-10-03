<?php
    foreach ($show_fields as $field){
        echo $replace_fields[$field] . ',';
    }
    __('Call Attempt') ; echo ',';
    __('Succ. Call') ; echo ',';
    __('Duration') ; echo ',';
    __('Client Avg Rate') ; echo ',';
    __('Vendor Avg Rate') ; echo ',';
    __('Client Cost') ; echo ',';
    __('Vendor Cost') ; echo ',';
    __('Profit') ; echo ',';
    __('ASR') ; echo ',';
    __('ACD(min)') ; echo ',';
    __('PDD') ;echo "\n";

    $i = 0;
    $arr = array();
    foreach ($data as $item){
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

        foreach (array_keys($show_fields) as $key){
             echo $item[0][$key]. ',';
        }
        echo $arr['total_calls'][$i] . ',' ;
        echo $arr['success_calls'][$i] . ',' ;
        echo number_format($arr['duration'][$i] / 60, 2) . ',' ;
        echo number_format($arr['client_bill_time'][$i] == 0 ? 0 : $arr['client_call_cost'][$i] / ($arr['client_bill_time'][$i] / 60), 5) . ',' ;
        echo number_format($arr['vendor_bill_time'][$i] == 0 ? 0 : $arr['vendor_call_cost'][$i] / ($arr['vendor_bill_time'][$i] / 60), 5) . ',' ;
        echo number_format($arr['client_call_cost'][$i],5) . ',' ;
        echo number_format($arr['vendor_call_cost'][$i],5) . ',' ;
        echo number_format($arr['vendor_call_cost'][$i] - $arr['client_call_cost'][$i], 5). ',' ;
        echo ($arr['vendor_busy_calls'][$i] + $arr['vendor_cancel_calls'][$i] + $arr['not_zero_calls'][$i]) == 0 ? 0 : round($arr['not_zero_calls'][$i] / ($arr['vendor_busy_calls'][$i] + $arr['vendor_cancel_calls'][$i] + $arr['not_zero_calls'][$i]) * 100, 2) . '%' . ',' ;
        echo round($arr['not_zero_calls'][$i] == 0 ? 0 : $arr['duration'][$i] / $arr['not_zero_calls'][$i] / 60, 2) . ',' ;
        echo $arr['pdd'][$i];echo "\n";
        $i++;
    }



$count_group = count($show_fields);
if ($count_group && count($data)){
    __('Total') ; echo str_repeat(",",$count_group);
    echo array_sum($arr['total_calls']). ',';
    echo array_sum($arr['success_calls']). ',';
    echo number_format(array_sum($arr['duration']) / 60, 2). ',';
    echo number_format(array_sum($arr['client_bill_time']) == 0 ? 0 : array_sum($arr['client_call_cost']) / (array_sum($arr['client_bill_time']) / 60), 5). ',';
    echo number_format(array_sum($arr['vendor_bill_time']) == 0 ? 0 : array_sum($arr['vendor_call_cost']) / (array_sum($arr['vendor_bill_time']) / 60), 5). ',';
    echo number_format(array_sum($arr['client_call_cost']), 5). ',';
    echo number_format(array_sum($arr['vendor_call_cost']), 5). ',';
    echo number_format(array_sum($arr['vendor_call_cost']) - array_sum($arr['client_call_cost']), 5). ',';
    echo (array_sum($arr['vendor_busy_calls']) + array_sum($arr['vendor_cancel_calls']) + array_sum($arr['not_zero_calls'])) == 0 ? 0 : round(array_sum($arr['not_zero_calls']) / (array_sum($arr['vendor_busy_calls']) + array_sum($arr['vendor_cancel_calls']) + array_sum($arr['not_zero_calls'])) * 100, 2) . '%'. ',';
    echo round(array_sum($arr['not_zero_calls']) == 0 ? 0 : array_sum($arr['duration']) / array_sum($arr['not_zero_calls']) / 60, 2). ',';
    echo array_sum($arr['pdd']);
}


?>