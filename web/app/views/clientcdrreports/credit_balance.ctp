<style>
#container table tr {height:20px; line-leight:20px; font-size:14px;}
.msg { color:#333;}
.msg th { width:160px; font-weight:bold;}
</style>
<div id="title"><h1><?php echo __('Credit Balance',true);?></h1></div>
<div id="container">
	<div style="font-size: 12px; line-height: 16px;" class="msg">
		<table style="width:300px;margin:0px auto;">
			<tr><th><?php echo __('Buy Net Trading Activity',true);?></th><td><?php echo $appClientcdrreports->show_number(number_format($buy,2))?></td></tr>
			<tr><th><?php echo __('Sell Net Trading Activity',true);?></th><td><?php echo $appClientcdrreports->show_number(number_format($sell,2))  ?></td></tr>
			<tr><th><?php echo __('Amount Due',true);?></th><td><?php echo $appClientcdrreports->show_number(number_format($invoice,2))  ?></td></tr>
			<tr><th><?php echo __('Credit Limit',true);?></th><td><?php echo $appClientcdrreports->show_number(number_format($credit,2))  ?></td></tr>
			<tr><td colspan="2"><hr /></td></tr>
			<tr><th><?php echo __('Credit Available',true);?></th><td><?php echo $appClientcdrreports->show_number(number_format($availbale,2))  ?></td></tr>
		</table>
	</div>
</div>
