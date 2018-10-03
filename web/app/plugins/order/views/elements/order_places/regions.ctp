<?php $regions = $appOrderPlaces->get_regions_from_codes($codes)?>
<?php $index = 1?>

<style>
#regions li { list-style:none;}
</style>
<table style="_zoom:1;overfollow:hidden;">
	<?php foreach ($regions as $region):?>
		<tr style="margin-bottom:10px;">	
			<td style="text-align:left;width:50%;">				
				<input style="width:15px" type="checkbox" rel="<?php echo $index?>" name="data[Order][Regions][]" value="<?php echo $region?>" id="region_<?php echo $index?>" <?php echo $appOrderPlaces->region_checked_by_order_code($region,$order_codes) ? 'checked=\"checked\"' : ''?> <?php echo $is_update ? 'disabled="disable"' : ''?>/>
				<label for="region_<?php echo $index?>"><?php echo $region?></label>
			</td>
			<td id="region_code_<?php echo $index?>" style="text-align:left;width:50%;">
				<?php if(false):?>
				<ul style="display:inline-table;width:100%">			
				<?php foreach($appOrderPlaces->filter_codes_from_codes_by_region($codes,$region) as $code):?>
					<li style="float:left;width:25%;">
						<input style="width:15px" type="checkbox" name="data[OrderCode][<?php echo $index?>][Codes][]" value="<?php echo array_keys_value($code,"0.code_id")?>" id="code_<?php echo array_keys_value($code,"0.code_id")?>" <?php echo $appOrderPlaces->code_checked_by_order_code($code,$order_codes) ? 'checked=\"checked\"' : ''?>/>
						<label for="code_<?php echo array_keys_value($code,"0.code_id")?>"><?php echo array_keys_value($code,"0.code")?></label>
					</li>
				<?php endforeach;?>
				</ul>
				<?php endif;?>				
				<?php $region_codes = array_map(create_function('$d','return array_keys_value($d,"0.code");'),$appOrderPlaces->filter_codes_from_codes_by_region($codes,$region));?>
				<?php echo join(', ',$region_codes)?>			
			</td>
		</tr>
		<?php $index++;?>
	<?php endforeach;?>
</table>
