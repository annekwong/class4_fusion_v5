<?php
foreach ($show_fields as $field){
    echo $replace_fields[$field];echo ',';
}
__('Ingress Carrier');echo ',';
__('Ingress Trunk');echo ',';
__('Call Attempt');echo ',';
__('Failure Cause');echo "\n";
for($i=0;$i<count($show_fields);$i++){
    echo ',';
}
__('No Capacity');echo ',';
__('No Profitable Route');echo ',';
__('Code Block');echo ',';
__('Trunk Block');echo "\n";
$total_call = 0;
$total_no_capacity = 0;
$total_no_profitable_route = 0;
$total_code_block = 0;
$total_egress_trunk_block = 0;
foreach ($data as $item){
    $total_call += $item[0]['total_call'];
    $total_no_capacity += $item[0]['no_capacity'];
    $total_no_profitable_route += $item[0]['no_profitable_route'];
    $total_code_block += $item[0]['code_block'];
    $total_egress_trunk_block += $item[0]['egress_trunk_block'];
    foreach (array_keys($show_fields) as $key){ echo $item[0][$key];}
    echo isset($client_info[$item[0]['client_id']]) ? $client_info[$item[0]['client_id']] : '--'; echo ',';
    echo  isset($trunk_info[$item[0]['trunk_id']]) ? $trunk_info[$item[0]['trunk_id']] : '--'; echo ',';
    echo $item[0]['total_call'];echo ',';
    echo $item[0]['no_capacity']; echo ',';
    echo $item[0]['no_profitable_route']; echo ',';
    echo $item[0]['code_block']; echo ',';
    echo $item[0]['egress_trunk_block'];echo "\n";
}
__('Total');echo ',';for($i=0;$i<count($show_fields)+1;$i++){echo ",";}
echo $total_call;echo ',';
echo $total_no_capacity;echo ',';
echo $total_code_block;echo ',';
echo $total_egress_trunk_block;echo "\n";