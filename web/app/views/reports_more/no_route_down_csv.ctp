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
 echo ','; echo ','; echo ',';
__('No Credit');echo ',';
__('Trunk Not Found');echo ',';
__('Not Routing');echo ',';
__('No Capacity');echo "\n";
$total_call = 0;
$total_no_credit = 0;
$total_trunk_not_found = 0;
$total_no_route = 0;
$total_no_capacity = 0;
foreach ($data as $item){
    $total_call += $item[0]['total_call'];
    $total_no_credit += $item[0]['no_credit'];
    $total_trunk_not_found += $item[0]['trunk_not_found'];
    $total_no_route += $item[0]['no_route'];
    $total_no_capacity += $item[0]['no_capacity'];
    foreach (array_keys($show_fields) as $key){ echo $item[0][$key];echo ',';}
    echo isset($client_info[$item[0]['client_id']]) ? $client_info[$item[0]['client_id']] : '--'; echo ',';
    echo  isset($trunk_info[$item[0]['trunk_id']]) ? $trunk_info[$item[0]['trunk_id']] : '--'; echo ',';
    echo $item[0]['total_call']; echo ',';
    echo $item[0]['no_credit']; echo ',';
    echo $item[0]['trunk_not_found']; echo ',';
    echo $item[0]['no_route']; echo ',';
    echo $item[0]['no_capacity']; echo "\n";
}
__('Total');echo ',';for($i=0;$i<count($show_fields)+1;$i++){echo ",";}
echo $total_call;echo ',';
echo $total_no_credit;echo ',';
echo $total_trunk_not_found;echo ',';
echo $total_no_route;echo ',';
echo $total_no_capacity;echo "\n";