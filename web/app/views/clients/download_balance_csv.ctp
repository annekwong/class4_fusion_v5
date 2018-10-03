<?php __('name'); ?>,<?php __('mutual_ingress_balance'); ?>,<?php __('mutual_egress_balance'); ?>,<?php __('total_mutual_balance'); ?>,<?php __('actual_ingress_balance'); ?>,<?php __('actual_egress_balance'); ?>,<?php __('total_actual_balance'); ?><?php echo "\n"; ?>
<?php foreach ($data as $item)
{
    echo $item['name'].','.$item['mutual_ingress_balance'].','.$item['mutual_egress_balance'].','.$item['mutual_total_balance'].','.$item['actual_ingress_balance'].','.$item['actual_egress_balance'].','.$item['actual_total_balance']."\n";
}
?>
