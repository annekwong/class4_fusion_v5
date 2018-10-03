<?php foreach($show_fields as $field): ?>"<?php echo $replace_fields[$field]; ?>",<?php endforeach; ?>"<?php __('CDR Count'); ?>","<?php __('Duration') ?>(min)",<?php echo "\r\n"; ?>
<?php
$total_cdr = 0;
$total_duration = 0;
foreach($data as $item) { 
$total_cdr += $item[0]['cdr_count'];
$total_duration += $item[0]['duration'];
}
?>
<?php foreach($data as $item): ?><?php if(isset($item[0]['cdr_count'])  && isset($item[0]['duration'])): ?><?php foreach(array_keys($show_fields) as $key): ?>"<?php echo $item[0][$key]; ?>",<?php endforeach; ?>"<?php echo $item[0]['cdr_count'] ?>","<?php echo round($item[0]['duration'] / 60, 2) ?>",<?php echo "\r\n"; ?><?php endif; ?><?php endforeach; ?>
<?php
$count_group = count($show_fields);
if($count_group && count($data)):
?>
"Total:",<?php for($col = 1;$col<$count_group;$col++) echo ","  ?>"<?php echo $total_cdr ?>","<?php echo round($total_duration / 60, 2) ?>",<?php endif;?>

