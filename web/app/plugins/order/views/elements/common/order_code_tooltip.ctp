<style>		
	#tooltip dd { float:left; display:inline-block;width:50px;}
	#tooltip dt { float:left;clear:both; display:inline-block;width:120px;font-weight:bold;}
</style>
<?php foreach($order_codes as $k => $order_code):?>
<?php if(!empty($order_code)):?>
<dl id="order_<?php echo $k ?>-tooltip" class="tooltip"   >
<?php	
	$order_code_regions = array_map(create_function('$d','return array_keys_value($d,"OrderCode.code_name");'),$order_code);
	$order_code_regions = array_unique($order_code_regions);
	$k=0;
	foreach($order_code_regions as $order_code_region){
		$k++;
		echo "<dt style='font-weight:bold; '>$order_code_region</dt>";
		$codes = array_filter($order_code,create_function('$d','return array_keys_value($d,"OrderCode.code_name") == "'.$order_code_region.'";'));
		$i=0;	
		echo "<dd>";
		foreach($codes as $code){
				echo "<span  style='margin-right: 5px; margin-left: 5px;'>".array_keys_value($code,"OrderCode.code")."</span>";
				if($i!=0&&($i%6==0)){
					echo "</dd><dd>";
				}								
			$i++;	}

		
		}
?>
</dl>
<?php endif;?>
<?php endforeach;?>