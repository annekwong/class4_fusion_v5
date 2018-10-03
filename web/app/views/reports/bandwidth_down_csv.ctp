<?php foreach($show_fields as $field): ?>"<?php echo $replace_fields[$field]; ?>",<?php endforeach; ?>"Calls","Incoming Bandwidth","Outgoing Bandwidth",echo "\r\n";
<?php
$i = 0;
$arr = array();
foreach($data as $item):
    $arr['calls'][$i] = $item[0]['calls'];
    $arr['incoming_bandwidth'][$i] = $item[0]['incoming_bandwidth'];
    $arr['outgoing_bandwidth'][$i] = $item[0]['outgoing_bandwidth'];

?><?php foreach(array_keys($show_fields) as $key): ?>"<?php echo $item[0][$key]; ?>",<?php endforeach; ?>"<?php echo number_format($arr['calls'][$i]); ?>", "<?php echo number_format($arr['incoming_bandwidth'][$i]); ?>","<?php echo number_format($arr['outgoing_bandwidth'][$i]); ?>",echo "\r\n";
<?php 
$i++;
endforeach; 
?>

<?php
$count_group = count($show_fields);
if($count_group && count($data)):
?>
"Total:",<?php for($col = 1;$col<$count_group;$col++) echo ","  ?>"<?php echo number_format(array_sum($arr['calls'])); ?>","<?php echo number_format(array_sum($arr['incoming_bandwidth'])); ?>","<?php echo number_format(array_sum($arr['outgoing_bandwidth'])); ?>",echo "\r\n";
<?php
endif;
?>