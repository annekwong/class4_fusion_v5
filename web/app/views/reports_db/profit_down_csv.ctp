<?php foreach($show_fields as $field): ?><?php echo $replace_fields[$field]; ?>,<?php endforeach; ?>
    Call Duration,,Profit,,Calls,,Ingress Cost,Egress Cost, NPR Count, NPR
<?php for($i=0;$i<count($show_fields);$i++):?>,<?php endfor; ?>"min","%","USA","%","Total","Not Zero"
<?php
$i = 0;
$arr = array();
foreach($data as $item):
    $arr['inbound_call_cost'][$i] = $item[0]['inbound_call_cost'];
    $arr['outbound_call_cost'][$i] = $item[0]['outbound_call_cost'];
    $arr['duration'][$i] = $item[0]['duration'];
    $arr['total_calls'][$i] = $item[0]['total_calls'];
    $arr['not_zero_calls'][$i] = $item[0]['not_zero_calls'];
    $arr['bill_time'][$i] = $item[0]['bill_time'];
    $arr['npr_count'][$i] = $item[0]['npr_count'];
    $i++;endforeach;
?>
<?php
$i = 0;
foreach($data as $item):
    foreach(array_keys($show_fields) as $key):
        if (in_array($key,array('ingress_client_id','egress_client_id','ingress_id','egress_id')) && !$item[0][$key]){
            echo $item[0]['delete_'.$key] ? $item[0]['delete_'.$key] : '~';
        }else{
            echo $item[0][$key];
        } ?>,<?php endforeach; ?>"<?php echo round($arr['bill_time'][$i] / 60, 2);?>","<?php echo array_sum($arr['duration']) == 0 ? 0 : round($arr['duration'][$i] / array_sum($arr['duration']) * 100, 2); ?>%","<?php echo number_format($arr['inbound_call_cost'][$i] - $arr['outbound_call_cost'][$i], 5); ?>","<?php echo number_format($arr['inbound_call_cost'][$i] == 0 ? 0 : ($arr['inbound_call_cost'][$i] - $arr['outbound_call_cost'][$i]) / $arr['outbound_call_cost'][$i] * 100, 2); ?>%","<?php echo round($arr['total_calls'][$i]);?>","<?php echo round($arr['not_zero_calls'][$i]);?>","<?php echo round($arr['inbound_call_cost'][$i], 5);?>","<?php echo round($arr['outbound_call_cost'][$i], 5);?>","<?php echo number_format($arr['npr_count'][$i]);?>","<?php echo number_format($arr['total_calls'][$i] == 0 ? 0 : $arr['npr_count'][$i] / $arr['total_calls'][$i] * 100, 2); ?>%"
    <?php $i++;endforeach; ?>
<?php
$count_group = count($show_fields);
$total_group_field = "";
foreach(array_keys($show_fields) as $key):
    $total_group_field .= ",";
endforeach;
$total_group_field =  rtrim($total_group_field,',');
if($count_group && count($data)):
    ?>
    Total:<?php for($i=0;$i<count($show_fields);$i++):?>,<?php endfor; ?><?php echo round(array_sum($arr['bill_time']) / 60, 2);?>,"100%","<?php echo number_format(array_sum($arr['inbound_call_cost']) - array_sum($arr['outbound_call_cost']), 5); ?>","<?php echo number_format(array_sum($arr['inbound_call_cost']) == 0 ? 0 : (array_sum($arr['inbound_call_cost']) - array_sum($arr['outbound_call_cost'])) / array_sum($arr['outbound_call_cost']) * 100, 2); ?>%","<?php echo round(array_sum($arr['total_calls']));?>","<?php echo round(array_sum($arr['not_zero_calls']));?>","<?php echo round(array_sum($arr['inbound_call_cost']), 5);?>","<?php echo round(array_sum($arr['outbound_call_cost']), 5);?>","<?php echo number_format(array_sum($arr['npr_count'])); ?>","<?php echo number_format(array_sum($arr['total_calls']) == 0 ? 0 : array_sum($arr['npr_count']) / array_sum($arr['total_calls']) * 100, 2); ?>%"<?php endif; ?>