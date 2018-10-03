<style>
.list tbody .row-active td {
	background: none;
}

.list tbody .row-hover td {
	background: none;
}
</style>
<div id="title">
	<h1><?php __('My')?> <?php echo Inflector::humanize($do_action)?> <?php __('Order Status (Select Country)')?></h1>
</div>
<div class="container">
<?php if (isset ( $exception_msg ) && $exception_msg) :	?>
	<?php	echo $this->element ( 'common/exception_msg' );?>		
<?php else : ?>		

	<?php echo $this->element ( "order_my_orders/order_list");?>		
<?php endif;?>
</div>