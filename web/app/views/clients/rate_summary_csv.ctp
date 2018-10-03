<?php __('Carrier Name') ?>,<?php __('Ingress Trunk Name') ?>,<?php __('Prefix') ?>,<?php __('Rate Table Name') ?>,<?php __('Rate Email') ?><?php echo "\n"; ?>
<?php foreach ($this->data as $item): ?>
<?php echo $item['Client']['name']; ?>,<?php echo $item['Resource']['alias']; ?>,<?php echo $item['ResourcePrefix']['tech_prefix']; ?>,<?php echo $item['RateTable']['name']; ?>,<?php echo $item['Client']['rate_email']; ?><?php echo "\n"; ?>
<?php endforeach; ?>