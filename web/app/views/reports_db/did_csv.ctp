<?php foreach($show_fields as $field): ?>'<?php echo $replace_fields[$field]; ?>',<?php endforeach; ?>'Time(min)',,'Usage Charge(USA)','Total Cost','Avg Rate','Calls',,<?php echo "\r\n"; ?>
<?php for($i=0;$i<count($show_fields);$i++):?>,<?php endfor; ?>Total Duration,Total Billable Time,,,,Total Calls,Not Zero,<?php echo "\r\n"; ?>
<?php
$i = 0;
$arr = array();
foreach($data as $item):
$arr['duration'][$i] = $item[0]['duration'];
$arr['bill_time'][$i] = $item[0]['bill_time'];
$arr['call_cost'][$i] = $item[0]['call_cost'];                    
$arr['total_calls'][$i] = $item[0]['total_calls'];
$arr['not_zero_calls'][$i] = $item[0]['not_zero_calls'];
$arr['success_calls'][$i] = $item[0]['success_calls'];
$arr['did'][$i] = $item[0]['did'];
?>
<?php foreach(array_keys($show_fields) as $key): ?>
'<?php echo $item[0][$key]; ?>',<?php endforeach; ?>'<?php echo $arr['did']; ?>','<?php echo number_format($arr['duration'][$i] / 60, 2); ?>','<?php echo number_format($arr['bill_time'][$i] / 60, 2); ?>','<?php echo number_format($arr['call_cost'][$i], 5); ?>','><?php echo number_format($arr['bill_time'][$i] == 0 ? 0 : $arr['call_cost'][$i] / ($arr['bill_time'][$i] / 60), 5); ?>','<?php echo number_format($arr['total_calls'][$i]); ?>','<?php echo number_format($arr['not_zero_calls'][$i]); ?>',<?php echo "\r\n"; ?>
<?php 
$i++;
endforeach; 
?>

<?php
$count_group = count($show_fields);
if($count_group && count($data)):
?>
'Total:',<?php for($col = 1;$col<$count_group;$col++) echo ","  ?>,'<?php echo number_format(array_sum($arr['duration']) / 60, 2); ?>','<?php echo number_format(array_sum($arr['bill_time']) / 60, 2); ?>','<?php echo number_format(array_sum($arr['call_cost']), 5); ?>','<?php echo number_format(array_sum($arr['bill_time']) == 0 ? 0 : array_sum($arr['call_cost']) / (array_sum($arr['bill_time']) / 60), 5); ?>','<?php echo number_format(array_sum($arr['total_calls'])); ?>','<?php echo number_format(array_sum($arr['not_zero_calls'])); ?>',<?php echo "\r\n"; ?>
<?php
endif;
?>
