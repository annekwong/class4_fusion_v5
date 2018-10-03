<?php foreach($show_fields as $field): ?><?php echo $replace_fields[$field]; ?>,<?php endforeach; ?>ABR,ASR,ACD(min),ALOC,PDD(ms),Time(min),Calls,,,,<?php echo "\n"; ?>
<?php for($i=0;$i<count($show_fields);$i++):?>,<?php endfor; ?>,,,,Total Billable Time,Total Calls,Not Zero,Success Calls,Busy Calls<?php echo "\n"; ?>
<?php
    $i = 0;
    $arr = array();
    foreach($data as $item):
        $arr['duration'][$i] = $item[0]['duration'];
        $arr['bill_time'][$i] = $item[0]['bill_time'];
        $arr['cancel_calls'][$i] = $item[0]['cancel_calls'];
        $arr['total_calls'][$i] = $item[0]['total_calls'];
        $arr['not_zero_calls'][$i] = $item[0]['not_zero_calls'];
        $arr['success_calls'][$i] = $item[0]['success_calls'];
        $arr['busy_calls'][$i] = $item[0]['busy_calls'];
        $arr['pdd'][$i] = $item[0]['pdd'];

?>
<?php foreach(array_keys($show_fields) as $key): ?><?php echo $item[0][$key]; ?>,<?php endforeach; ?>"<?php echo round($arr['total_calls'][$i] == 0 ? 0 : $arr['not_zero_calls'][$i] / $arr['total_calls'][$i] * 100, 2); ?>%","<?php echo ($arr['busy_calls'][$i] + $arr['cancel_calls'][$i] +$arr['not_zero_calls'][$i]) == 0  ? 0 :round($arr['not_zero_calls'][$i] / ($arr['busy_calls'][$i] + $arr['cancel_calls'][$i] +$arr['not_zero_calls'][$i])*100 ,2)?>%","<?php echo round($arr['not_zero_calls'][$i] == 0 ? 0 : $arr['duration'][$i] / $arr['not_zero_calls'][$i] / 60, 2); ?>","<?php echo round((($arr['busy_calls'][$i] + $arr['cancel_calls'][$i] +$arr['not_zero_calls'][$i]) == 0  ? 0 :$arr['not_zero_calls'][$i] / ($arr['busy_calls'][$i] + $arr['cancel_calls'][$i] +$arr['not_zero_calls'][$i])) * ($arr['not_zero_calls'][$i] == 0 ? 0 : $arr['duration'][$i] / $arr['not_zero_calls'][$i] / 60),2);  ?>","<?php echo round($arr['not_zero_calls'][$i] == 0 ? 0 : $arr['pdd'][$i] / $arr['not_zero_calls'][$i]); ?>","<?php echo number_format($arr['bill_time'][$i] / 60, 2); ?>","<?php echo number_format($arr['total_calls'][$i]); ?>","<?php echo number_format($arr['not_zero_calls'][$i]); ?>","<?php echo number_format($arr['success_calls'][$i]); ?>","<?php echo number_format($arr['busy_calls'][$i]); ?>"<?php echo "\n"; ?><?php $i++; endforeach; ?>

<?php
    $count_group = count($show_fields);
    if($count_group && count($data)):
?>
Total,<?php for($col = 1;$col<$count_group;$col++) echo ","  ?>"<?php echo round(array_sum($arr['total_calls']) == 0 ? 0 : array_sum($arr['not_zero_calls']) / array_sum($arr['total_calls']) * 100, 2); ?>%","<?php echo (array_sum($arr['busy_calls']) + array_sum($arr['cancel_calls']) +array_sum($arr['not_zero_calls'])) == 0  ? 0 :round(array_sum($arr['not_zero_calls']) / (array_sum($arr['busy_calls']) + array_sum($arr['cancel_calls']) +array_sum($arr['not_zero_calls']))*100 ,2)?>%","<?php echo round(array_sum($arr['not_zero_calls']) == 0 ? 0 : array_sum($arr['duration']) / array_sum($arr['not_zero_calls']) / 60, 2); ?>","<?php echo round(((array_sum($arr['busy_calls']) + array_sum($arr['cancel_calls']) +array_sum($arr['not_zero_calls'])) == 0  ? 0 :array_sum($arr['not_zero_calls']) / (array_sum($arr['busy_calls']) + array_sum($arr['cancel_calls']) +array_sum($arr['not_zero_calls']))) * (array_sum($arr['not_zero_calls']) == 0 ? 0 : array_sum($arr['duration']) / array_sum($arr['not_zero_calls']) / 60),2); ?>","<?php echo round(array_sum($arr['not_zero_calls']) == 0 ? 0 : array_sum($arr['pdd']) / array_sum($arr['not_zero_calls'])); ?>","<?php echo number_format(array_sum($arr['bill_time']) / 60, 2); ?>","<?php echo number_format(array_sum($arr['total_calls'])); ?>","<?php echo number_format(array_sum($arr['not_zero_calls'])); ?>","<?php echo number_format(array_sum($arr['success_calls'])); ?>","<?php echo number_format(array_sum($arr['busy_calls'])); ?>",<?php echo "\n"; ?>
<?php
    endif;
?>
