<?php foreach($show_fields as $field): ?><?php echo $replace_fields[$field]; ?>,<?php endforeach; ?>
ABR,ASR,ACD(min),ALOC,PDD(ms),Time(min),,Usage Charge(USA),<?php if($type == 1): ?>LRN Charge,<?php endif; ?>Total Cost,<?php if(isset($_GET['show_inter_intra'])): ?>Inter Cost,Intra Cost,<?php endif; ?>Avg Rate,Calls,,,,<?php if($type == 1): ?>,<?php endif; ?><?php echo "\n"; ?>
<?php for($i=0;$i<count($show_fields);$i++):?>,<?php endfor; ?>,,,,,Total Duration,Total Billable Time,,<?php if(isset($_GET['show_inter_intra'])): ?>,,<?php endif; ?>,,<?php if($type == 1): ?>,<?php endif; ?>Total Calls,Not Zero,Success Calls,Busy Calls,<?php if($type == 1): ?>LRN Calls,<?php endif; ?><?php echo "\n"; ?>
<?php
$i = 0;
$arr = array();
foreach($data as $item):
    $arr["duration"][$i] = $item[0]["duration"];
    $arr["bill_time"][$i] = $item[0]["bill_time"];
    $arr["call_cost"][$i] = $item[0]["call_cost"];             
    $arr["cancel_calls"][$i] = $item[0]["cancel_calls"];
    if($type == 1):
    $arr["lnp_cost"][$i] = $item[0]["lnp_cost"];
    $arr["lrn_calls"][$i] = $item[0]["lrn_calls"];
    endif;
    $arr["total_calls"][$i] = $item[0]["total_calls"];
    $arr["not_zero_calls"][$i] = $item[0]["not_zero_calls"];
    $arr["success_calls"][$i] = $item[0]["success_calls"];
    $arr["busy_calls"][$i] = $item[0]["busy_calls"];
    $arr["pdd"][$i] = $item[0]["pdd"];
    $arr['inter_cost'][$i] = $item[0]['inter_cost'];
    $arr['intra_cost'][$i] = $item[0]['intra_cost'];
?>
<?php foreach(array_keys($show_fields) as $key): ?><?php echo $item[0][$key]; ?>,<?php endforeach; ?>"<?php echo round($arr["total_calls"][$i] == 0 ? 0 : $arr["not_zero_calls"][$i] / $arr["total_calls"][$i] * 100, 2); ?>%","<?php echo ($arr["busy_calls"][$i] + $arr["cancel_calls"][$i] +$arr["not_zero_calls"][$i]) == 0  ? 0 :round($arr["not_zero_calls"][$i] / ($arr["busy_calls"][$i] + $arr["cancel_calls"][$i] +$arr["not_zero_calls"][$i])*100 ,2)?>%","<?php echo round($arr["not_zero_calls"][$i] == 0 ? 0 : $arr["duration"][$i] / $arr["not_zero_calls"][$i] / 60, 2); ?>","<?php echo round((($arr["busy_calls"][$i] + $arr["cancel_calls"][$i] +$arr["not_zero_calls"][$i]) == 0  ? 0 :$arr["not_zero_calls"][$i] / ($arr["busy_calls"][$i] + $arr["cancel_calls"][$i] +$arr["not_zero_calls"][$i])) * ($arr["not_zero_calls"][$i] == 0 ? 0 : $arr["duration"][$i] / $arr["not_zero_calls"][$i] / 60),2); ?>","<?php echo round($arr["not_zero_calls"][$i] == 0 ? 0 : $arr["pdd"][$i] / $arr["not_zero_calls"][$i]); ?>","<?php echo number_format($arr["duration"][$i] / 60, 2); ?>","<?php echo number_format($arr["bill_time"][$i] / 60, 2); ?>","<?php echo number_format($arr["call_cost"][$i], 5); ?>",<?php if($type == "1"): ?>"<?php echo number_format($arr["lnp_cost"][$i], 5); ?>","<?php echo number_format($arr["call_cost"][$i] + $arr["lnp_cost"][$i], 5); ?>",<?php else: ?>"<?php echo number_format($arr["call_cost"][$i], 5); ?>",<?php endif; ?><?php if(isset($_GET['show_inter_intra'])): ?>"<?php echo number_format($arr['inter_cost'][$i], 5); ?>","<?php echo number_format($arr['intra_cost'][$i], 5); ?>",<?php endif; ?>"<?php echo number_format($arr["bill_time"][$i] == 0 ? 0 : $arr["call_cost"][$i] / ($arr["bill_time"][$i] / 60), 5); ?>","<?php echo number_format($arr["total_calls"][$i]); ?>","<?php echo number_format($arr["not_zero_calls"][$i]); ?>","<?php echo number_format($arr["success_calls"][$i]); ?>","<?php echo number_format($arr["busy_calls"][$i]); ?>",<?php if($type == "1"): ?>"<?php echo number_format($arr["lrn_calls"][$i]); ?>",<?php endif; ?><?php echo "\n"; ?>
<?php 
$i++;
endforeach; 
?>
<?php
$count_group = count($show_fields);
if($count_group&&count($data)):
?>
Total,<?php for($col = 1;$col<$count_group;$col++) echo ","  ?>"<?php echo round(array_sum($arr["total_calls"]) == 0 ? 0 : array_sum($arr["not_zero_calls"]) / array_sum($arr["total_calls"]) * 100, 2); ?>%","<?php echo (array_sum($arr["busy_calls"]) + array_sum($arr["cancel_calls"]) +array_sum($arr["not_zero_calls"])) == 0  ? 0 :round(array_sum($arr["not_zero_calls"]) / (array_sum($arr["busy_calls"]) + array_sum($arr["cancel_calls"]) +array_sum($arr["not_zero_calls"]))*100 ,2)?>%","<?php echo round(array_sum($arr["not_zero_calls"]) == 0 ? 0 : array_sum($arr["duration"]) / array_sum($arr["not_zero_calls"]) / 60, 2); ?>","<?php echo round(((array_sum($arr["busy_calls"]) + array_sum($arr["cancel_calls"]) +array_sum($arr["not_zero_calls"])) == 0  ? 0 :array_sum($arr["not_zero_calls"]) / (array_sum($arr["busy_calls"]) + array_sum($arr["cancel_calls"]) +array_sum($arr["not_zero_calls"]))) * (array_sum($arr["not_zero_calls"]) == 0 ? 0 : array_sum($arr["duration"]) / array_sum($arr["not_zero_calls"]) / 60),2); ?>","<?php echo round(array_sum($arr["not_zero_calls"]) == 0 ? 0 : array_sum($arr["pdd"]) / array_sum($arr["not_zero_calls"])); ?>","<?php echo number_format(array_sum($arr["duration"]) / 60, 2); ?>","<?php echo number_format(array_sum($arr["bill_time"]) / 60, 2); ?>","<?php echo number_format(array_sum($arr["call_cost"]), 5); ?>",<?php if($type == "1"): ?>"<?php echo number_format(array_sum($arr["lnp_cost"]), 5); ?>","<?php echo number_format(array_sum($arr["call_cost"]) + array_sum($arr["lnp_cost"]), 5); ?>",<?php else: ?>"<?php echo number_format(array_sum($arr["call_cost"]), 5); ?>",<?php endif ?><?php if(isset($_GET['show_inter_intra'])): ?>"<?php echo number_format(array_sum($arr['inter_cost']), 5); ?>","<?php echo number_format(array_sum($arr['intra_cost']), 5); ?>",<?php endif; ?>"<?php echo number_format(array_sum($arr["bill_time"]) == 0 ? 0 : array_sum($arr["call_cost"]) / (array_sum($arr["bill_time"]) / 60), 5); ?>","<?php echo number_format(array_sum($arr["total_calls"])); ?>","<?php echo number_format(array_sum($arr["not_zero_calls"])); ?>","<?php echo number_format(array_sum($arr["success_calls"])); ?>","<?php echo number_format(array_sum($arr["busy_calls"])); ?>",<?php if($type == "1"): ?>"<?php echo number_format(array_sum($arr["lrn_calls"])); ?>",<?php endif; ?><?php echo "\n"; ?>
<?php
endif;
?>