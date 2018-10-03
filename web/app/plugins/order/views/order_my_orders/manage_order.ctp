<div id="title">
	<h1><?php __('Browser ALL')?> <?php echo Inflector::humanize($do_action)?> <?php __('Order')?></h1>	
</div>
<div class="container" id="order_list">
	<div id="toppage"></div>
	<div>
		<div id="filter_div" style="float:left;width:50%;">
		<form action="" method="POST">
			<?php echo $appOrderBrowsers->filter_country()?>&nbsp;&nbsp;&nbsp;&nbsp;
			<?php echo $form->submit('',array('label'=>false,'div'=>false))?>
		</form>
		</div>		
	</div>
	<?php echo $this->element("order_my_orders/manage_order_list",array('order_responses' => $p->getDataArray()))?>
	<div id="tmppage">
	<?php echo $this->element('page');?>
	</div>
</div>
