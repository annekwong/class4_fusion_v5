<style>
.list tbody .row-active td {
	background:none;
}
.list tbody .row-hover td {
background:none;
}
</style>
<div id="title">
	<h1><span><?php if($do_action=='buy')echo 'Buy' ;else echo 'Sell'?></span><?php __('My Confirmed Orders')?></h1>
</div>
<div class="container">
<?php if (isset ( $exception_msg ) && $exception_msg) :	?>
	<?php	echo $this->element ( 'common/exception_msg' );?>		
<?php else : ?>	
	<?php echo $this->element ( "order_contracts/order_contract_list")?>
<?php endif;?>
</div>