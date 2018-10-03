<style>
.list tbody .row-active td {
	background:none;
}
.list tbody .row-hover td {
	background:none;
}
</style>
<div id="title">
	<h1><span><?php echo Inflector::humanize($do_action)?></span><?php __('All Confirmed Orders')?></h1>
</div>
<div class="container">
<?php if (isset ( $exception_msg ) && $exception_msg) :	?>
	<?php	echo $this->element ( 'common/exception_msg' );?>		
<?php else : ?>
	<div id="toppage"></div>	
	<?php echo $this->element ( "order_contracts/manage_order_contract_list",array('contracts' => $p->getDataArray()) )?>
	<div id="tmppage">
	<?php echo $this->element('page');?>
	</div>
<?php endif;?>
</div>