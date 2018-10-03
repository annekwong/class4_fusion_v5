<?php if(empty($o_orders)):?>
	<?php echo $this->element('common/no_result')?>
<?php else:?>
<div id="toppage"></div>
<div id="optional_col" style="text-align:right;float:right;width:50%;">
	<span><input type="checkbox" value="acd" id="optional_col_acd" <?php echo $appOrderResponses->show_order_list_col_show('acd',true) ? 'checked' : '';?> ><label for="optional_col_acd"><?php __('ACD')?></label></span>
	<span><input type="checkbox" value="asr" id="optional_col_asr" <?php echo $appOrderResponses->show_order_list_col_show('asr',true) ? 'checked' : '';?>><label for="optional_col_asr"><?php __('ASR')?></label></span>
	<span><input type="checkbox" value="cli" id="optional_col_cli" <?php echo $appOrderResponses->show_order_list_col_show('cli',false) ? 'checked' : '';?>><label for="optional_col_cli"><?php __('CLI')?></label></span>
	<span><input type="checkbox" value="g729" id="optional_col_g729" <?php echo $appOrderResponses->show_order_list_col_show('g729',false) ? 'checked' : '';?>><label for="optional_col_g729"><?php __('G729')?></label></span>
	<span><input type="checkbox" value="fax" id="optional_col_fax" <?php echo $appOrderResponses->show_order_list_col_show('fax',false) ? 'checked' : '';?>><label for="optional_col_fax"><?php __('FAX')?></label></span>
	<span><input type="checkbox" value="create_time" id="optional_col_create_time" <?php echo $appOrderResponses->show_order_list_col_show('create_time',true) ? 'checked' : '';?> ><label for="optional_col_create_time"><?php echo __('create_time',true);?></label></span>
	<span><input type="checkbox" value="update_time" id="optional_col_update_time" <?php echo $appOrderResponses->show_order_list_col_show('update_time',true) ? 'checked' : '';?>><label for="optional_col_update_time"><?php echo __('update_time',true);?></label></span>
</div>
<table class="list">
	<thead>
		<tr>
			<td>country</td>			
			<td><?php echo __('rate',true);?></td>		
			<td>commit</td>
			<td>commit minutes</td>
			<td>status</td>
			<?php echo $appOrderResponses->order_list_col('acd','ACD',true)?>
			<?php echo $appOrderResponses->order_list_col('asr','ASR',true)?>
			<?php echo $appOrderResponses->order_list_col('cli','CLI',false)?>
			<?php echo $appOrderResponses->order_list_col('g729','G729',false)?>
			<?php echo $appOrderResponses->order_list_col('fax','FAX',false)?>
			<?php echo $appOrderResponses->order_list_col('create_time','create time',true)?>
			<?php echo $appOrderResponses->order_list_col('update_time','update time',true)?>
			<td>expire time</td>
			<td style="text-align:center">Response</td>		
		</tr>
	</thead>
	<tbody>	
	<?php foreach($o_orders as $o_order):?>	
	<tr rel="tooltip" id="order_<?php echo array_keys_value($o_order,"0.id")?>">
		<td><?php echo array_keys_value($o_order,"0.country")?></td>
		<td><?php echo $appOrderResponses->format_price(array_keys_value($o_order,"0.rate"))?></td>
		<td><?php echo $appOrderResponses->format_radio(array(false=>"No",true=>"Yes"),array_keys_value($o_order,"0.is_commit"))?></td>
		<td><?php echo $appOrderResponses->format_order_commit_minutes($o_order)?></td>
		<td><?php echo $appOrderResponses->order_status(array_keys_value($o_order,"0.status"))?></td>
		<?php
			$acd = array_keys_value($o_order,"0.acd");
			$asr = array_keys_value($o_order,"0.asr");
			if(empty($acd)){$acd = '-'; }else{$acd = number_format($acd,2);}
			if(empty($asr)){$asr = '-'; }else{$asr = number_format($asr,2).'%';}
		?>
		<?php echo $appOrderResponses->order_list_col('acd',$acd,true)?>
		<?php echo $appOrderResponses->order_list_col('asr',$asr,true)?>
		<?php echo $appOrderResponses->order_list_col('cli',array_keys_value($o_order,"0.cli"),false)?>
		<?php echo $appOrderResponses->order_list_col('g729',array_keys_value($o_order,"0.g729"),false)?>
		<?php echo $appOrderResponses->order_list_col('fax',array_keys_value($o_order,"0.fax"),false)?>
		<?php echo $appOrder->format_time($appOrderResponses->order_list_col('create_time',array_keys_value($o_order,"0.create_time"),true))?>
		<?php echo $appOrder->format_time($appOrderResponses->order_list_col('update_time',array_keys_value($o_order,"0.update_time"),true))?>
		<td><?php echo $appOrder->format_time($appOrderResponses->format_order_expire_time($o_order))?></td>
		<td style="text-align:center">
			<?php if($do_action == 'buy'):?>
				<?php echo $appOrderResponses->private_buy_operation($order,$o_order)?>
			<?php else:?>
				<?php echo $appOrderResponses->private_sell_operation($order,$o_order)?>
			<?php endif;?>
		</td>		
	</tr>
	<?php endforeach;?>
	</tbody>
</table>
	<?php if(true):?>
		<?php echo $this->element("common/order_code_tooltip")?>
	<?php endif;?>
<?php endif;?>
	
<div id="tmppage">
	<?php echo $this->element('page');?>
</div>
