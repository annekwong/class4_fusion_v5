<?php if(empty($data)): ?>
<?php else: ?>
<?php
$days = array();
$startdate=strtotime($start);
$enddate=strtotime($end); 
$day=round(($enddate-$startdate)/3600/24) ;
$dt_begin = new DateTime($start);
for($i=0;$i<$day;$i++) {
if($i > 0) {
$dt_begin->modify('+1 days');
}
array_push($days, $dt_begin->format('Y-m-d'));
}
?>
"<?php __('Client Name'); ?>",<?php foreach($days as $item): ?>"<?php echo $item; ?>",<?php endforeach; ?>"<?php echo __('Total(Min)') ?>",<?php echo "\r\n" ?>
<?php foreach($data as $item): ?><?php $item_total = 0; ?>"<?php echo $item[array_rand($item)]['client_name'] ?>",<?php foreach($days as $day_item) { if(array_key_exists($day_item, $item)) { echo '"' . round($item[$day_item]['total_time'] / 60, 2) . '",';$item_total += $item[$day_item]['total_time'] ;} else {echo '"0",';}}?>"<?php echo round($item_total / 60, 2); ?>",<?php echo "\r\n"; ?><?php endforeach; ?>
"<?php __('Total(Min)') ?>",<?php $total_item_total = 0; ?><?php foreach($days as $day_item) : ?>"<?php $total_item = 0; foreach($data as $item): if(array_key_exists($day_item, $item)) { $total_item += $item[$day_item]['total_time']; } endforeach; $total_item_total += $total_item;echo round($total_item / 60, 2);?>",<?php endforeach;?>"<?php echo round($total_item_total / 60, 2); ?>",<?php endif; ?>