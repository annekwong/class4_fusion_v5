<?php if(empty($data)): ?>
<?php else: ?>
"<?php __('Report Time'); ?>","<?php echo __('Ingress Channels(Max)'); ?>",<?php echo "\r\n"; ?>
<?php foreach($data as $item): ?>"<?php echo $item[0]['report_time']; ?>","<?php echo $item[0]['max']; ?>",<?php echo "\r\n"; ?><?php endforeach; ?>
<?php endif; ?>