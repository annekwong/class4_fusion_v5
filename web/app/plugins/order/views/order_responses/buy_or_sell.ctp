<style>
.list tbody .row-active td {
	background:none;
}
.list tbody .row-hover td {
background:none;
}
</style>
<div id="title">
	<h1><?php echo Inflector::humanize($do_action) == "Buy" ? "Sell" : "Buy" ?> &gt;&gt;<?php echo __('Order ID',true);?># <?php echo array_keys_value($order,"0.id")?></h1>
</div>
<div class="container">
<?php if (isset ( $exception_msg ) && $exception_msg) :	?>
	<?php	echo $this->element ( 'common/exception_msg' );?>		
<?php else : ?>			
	<?php echo $this->element ( "order_responses/order_table" )?>
	<?php echo $this->element ( "order_responses/order_code" )?>
	<div style="height:9px;"></div>
	<?php echo $this->element ( "order_responses/order_response_form" )?>	
<?php endif;?>
</div>
