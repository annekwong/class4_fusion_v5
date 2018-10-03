<?php foreach($show_fields as $field): ?>"<?php echo $replace_fields[$field]; ?>",<?php endforeach; ?>"Duration","Call Count","Revenue","Cost","Profit","Profit(%)",
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
<?php foreach(array_keys($show_fields) as $key): ?>"<?php echo $item[0][$key]; ?>",<?php endforeach; ?>"<?php echo round($arr['duration'][$i] / 60, 2);?>","<?php echo round($arr['total_calls'][$i]);?>","<?php echo round($arr['inbound_call_cost'][$i], 5);?>","<?php echo round($arr['outbound_call_cost'][$i], 5);?>","<?php echo number_format($arr['inbound_call_cost'][$i] - $arr['outbound_call_cost'][$i], 5);?>","<?php echo number_format($arr['inbound_call_cost'][$i] == 0 ? 0 : ($arr['inbound_call_cost'][$i] - $arr['outbound_call_cost'][$i]) / $arr['inbound_call_cost'][$i] * 100, 5);?>%",
<?php 
$i++;
endforeach; 
?>
<?php
$count_group = count($show_fields);
if($count_group && count($data)):
?>
Total,<?php for($col = 1;$col<$count_group;$col++) echo ","  ?>""<?php echo round(array_sum($arr['duration']) / 60, 2);?>","<?php echo round(array_sum($arr['total_calls']));?>","<?php echo round(array_sum($arr['inbound_call_cost']), 5);?>","<?php echo round(array_sum($arr['outbound_call_cost']), 5);?>","<?php echo number_format(array_sum($arr['inbound_call_cost']) - array_sum($arr['outbound_call_cost']), 5);?>","<?php echo number_format(array_sum($arr['inbound_call_cost']) == 0 ? 0 : (array_sum($arr['inbound_call_cost']) - array_sum($arr['outbound_call_cost'])) / array_sum($arr['inbound_call_cost']) * 100, 5);?>%",
<?php endif; ?>
