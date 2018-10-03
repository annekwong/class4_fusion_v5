<?php foreach($show_fields as $field): ?><?php echo $replace_fields[$field]; ?>,<?php endforeach; ?>Inbound,,,Outbound,,,Profit,,Total Duration(min),ASR,ACD(min),PDD(ms),Calls,,,,<?php echo "\n"; ?>
<?php for($i=0;$i<count($show_fields);$i++):?>,<?php endfor; ?>Billed Time,Cost(USA),Avg Rate(USA),Biiled Time,Cost(USA),Avg Rate(USA),(USA),%,,,,,Total,Not Zero,Success,Busy Calls,<?php echo "\n"; ?>
<?php 
    $i = 0;
    $arr = array();
    foreach($data as $item):
        $arr["inbound_bill_time"][$i] = $item[0]["inbound_bill_time"];
        $arr["inbound_call_cost"][$i] = $item[0]["inbound_call_cost"];
        $arr["outbound_bill_time"][$i] = $item[0]["outbound_bill_time"];
        $arr["outbound_call_cost"][$i] = $item[0]["outbound_call_cost"];
        $arr["duration"][$i] = $item[0]["duration"];
        $arr["total_calls"][$i] = $item[0]["total_calls"];
        $arr["not_zero_calls"][$i] = $item[0]["not_zero_calls"];
        $arr["success_calls"][$i] = $item[0]["success_calls"];
        $arr["busy_calls"][$i] = $item[0]["busy_calls"];
        $arr["pdd"][$i] = $item[0]["pdd"];
?>
<?php foreach(array_keys($show_fields) as $key): ?><?php echo $item[0][$key]; ?>,<?php endforeach; ?>"<?php echo round($arr["inbound_bill_time"][$i] / 60, 2); ?>","<?php echo round($arr["inbound_call_cost"][$i], 5);?>","<?php echo number_format($arr["inbound_bill_time"][$i] == 0 ? 0 : $arr["inbound_call_cost"][$i] / ($arr["inbound_bill_time"][$i] / 60), 5); ?>","<?php echo round($arr["outbound_bill_time"][$i] / 60, 2); ?>","<?php echo round($arr["outbound_call_cost"][$i], 5);?>","<?php echo number_format($arr["outbound_bill_time"][$i] == 0 ? 0 : $arr["outbound_call_cost"][$i] / ($arr["outbound_bill_time"][$i] / 60), 5); ?>","<?php echo number_format($arr["inbound_call_cost"][$i] - $arr["outbound_call_cost"][$i], 5);?>","<?php echo number_format($arr["inbound_call_cost"][$i] == 0 ? 0 : ($arr["inbound_call_cost"][$i] - $arr["outbound_call_cost"][$i]) / $arr["inbound_call_cost"][$i] * 100, 5);?>%","<?php echo round($arr["duration"][$i] / 60, 2);?>","<?php echo number_format($arr["total_calls"][$i] == 0 ? 0 : $arr["not_zero_calls"][$i] / $arr["total_calls"][$i] * 100, 2); ?>%","<?php echo number_format($arr["not_zero_calls"][$i] == 0 ? 0 : $arr["duration"][$i] / $arr["not_zero_calls"][$i] / 60, 2); ?>","<?php echo number_format($arr["not_zero_calls"][$i] == 0 ? 0 : $arr["pdd"][$i] / $arr["not_zero_calls"][$i]); ?>","<?php echo round($arr["total_calls"][$i]);?>","<?php echo round($arr["not_zero_calls"][$i]);?>","<?php echo round($arr["success_calls"][$i]);?>","<?php echo round($arr["busy_calls"][$i]);?>",<?php echo "\n"; ?>
<?php 
    $i++;
    endforeach; 
?>
<?php
    $count_group = count($show_fields);
    if($count_group && count($data)):
?>
Total,<?php for($col = 1;$col<$count_group;$col++) echo ","  ?>"<?php echo round(array_sum($arr["inbound_bill_time"]) / 60, 2); ?>","<?php echo round(array_sum($arr["inbound_call_cost"]), 5);?>","<?php echo number_format(array_sum($arr["inbound_bill_time"]) == 0 ? 0 : array_sum($arr["inbound_call_cost"]) / (array_sum($arr["inbound_bill_time"]) / 60), 5); ?>","<?php echo round(array_sum($arr["outbound_bill_time"])/ 60, 2); ?>","<?php echo round(array_sum($arr["outbound_call_cost"]), 5);?>","<?php echo number_format(array_sum($arr["outbound_bill_time"]) == 0 ? 0 : array_sum($arr["outbound_call_cost"]) / (array_sum($arr["outbound_bill_time"]) / 60), 5); ?>","<?php echo number_format(array_sum($arr["inbound_call_cost"]) - array_sum($arr["outbound_call_cost"]), 5);?>","<?php echo number_format(array_sum($arr["inbound_call_cost"]) == 0 ? 0 : (array_sum($arr["inbound_call_cost"]) - array_sum($arr["outbound_call_cost"])) / array_sum($arr["inbound_call_cost"]) * 100, 5);?>%","<?php echo round(array_sum($arr["duration"]) / 60, 2);?>","<?php echo number_format(array_sum($arr["total_calls"]) == 0 ? 0 : array_sum($arr["not_zero_calls"]) / array_sum($arr["total_calls"]) * 100, 2); ?>%","<?php echo number_format(array_sum($arr["not_zero_calls"]) == 0 ? 0 : array_sum($arr["duration"]) / array_sum($arr["not_zero_calls"]) / 60, 2); ?>","<?php echo number_format(array_sum($arr["not_zero_calls"]) == 0 ? 0 : array_sum($arr["pdd"]) / array_sum($arr["not_zero_calls"])); ?>","<?php echo round(array_sum($arr["total_calls"]));?>","<?php echo round(array_sum($arr["not_zero_calls"]));?>","<?php echo round(array_sum($arr["success_calls"]));?>","<?php echo round(array_sum($arr["busy_calls"]));?>",
<?php 
    endif;
?>