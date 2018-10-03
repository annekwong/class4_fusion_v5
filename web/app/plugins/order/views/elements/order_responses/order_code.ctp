<?php if(!empty($order_code)):?>
<style>
#codes li { list-style:none;}
</style>
<center>
	<div style="_zoom:1;overfollow:hidden;width:50%" id="codes">
	<?php 
	$order_code_regions = array_map(create_function('$d','return array_keys_value($d,"OrderCode.code_name");'),$order_code);
	$order_code_regions = array_unique($order_code_regions);
	foreach($order_code_regions as $order_code_region):
	?>
		<div style="float:left;width:25%;"><?php echo $order_code_region?></div>
		<div style="float:left;width:75%;">
			<ul style="display:inline-table;width:100%">
	<?php 
		$codes = array_filter($order_code,create_function('$d','return array_keys_value($d,"OrderCode.code_name") == "'.$order_code_region.'";'));
			foreach($codes as $code){
				echo "<li style=\"float:left;width:14%;\">".array_keys_value($code,"OrderCode.code")."</li>";								
			}
	?>
			</ul>
		</div>
		<?php endforeach;?>
		<div style="clear:both">
	</div>
</center>
<?php endif;?>
