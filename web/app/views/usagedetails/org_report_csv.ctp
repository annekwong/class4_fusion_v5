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
"<?php __('Client Name') ?>","<?php __('Orig Code Name') ?>","<?php __('Total(Min)') ?>",,,,,<?php foreach($days as $item): ?><?php echo $item ?>,,,<?php endforeach; ?><?php echo "\r\n" ?>
"","","","<?php __('% Calls < 30s'); ?>","<?php __('min count < 30s'); ?>","<?php __('% Calls < 6s'); ?>","<?php __('min count < 6s'); ?>",<?php for($i=0;$i<$day;$i++) { ?>"<?php __('Billed Time (min)') ?>","<?php __('ASR (%)') ?>","<?php __('ACD (mins) (min)') ?>",<?php }; ?><?php echo "\r\n" ?>
<?php 
foreach($data as $item):
?>
"<?php echo $item['client_name']; ?>","<?php echo $item['orig_code_name']; ?>","<?php echo number_format($item['total_time'] / 60, 2); ?>","<?php echo $item['calls_30']; ?>","<?php echo number_format($item['time_30'] /60, 2); ?>","<?php echo $item['calls_6']; ?>","<?php echo number_format($item['time_6'] / 60, 2); ?>",<?php foreach($days as $day_item):  ?><?php  if(array_key_exists($day_item, $item['years'])): ?>"<?php echo number_format($item['years'][$day_item]['bill_time'] / 60, 2); ?>","<?php echo $item['years'][$day_item]['total_calls'] == 0 ? 0 : number_format($item['years'][$day_item]['not_zero_calls']/$item['years'][$day_item]['total_calls'], 5); ?>","<?php echo $item['years'][$day_item]['not_zero_calls'] == 0 ? 0 : number_format($item['years'][$day_item]['total_time']/$item['years'][$day_item]['not_zero_calls'] / 60, 5); ?>",<?php else: ?>,,,<?php endif ?><?php endforeach; ?><?php echo "\r\n" ?>
<?php
endforeach;
?>
<?php endif; ?>