<table>
<tr>
<td>
<form action="" method="GET">
<div align="left">
	<?php echo $xform->search('filter_country',Array('empty'=>'Filter country','options'=>$appOrderManages->format_country_options(ClassRegistry::init('Code')->find_countries()),'style'=>'width:215px'))?>&nbsp;&nbsp;&nbsp;&nbsp;
	<?php echo $appOrderManages->filter_asr()?>&nbsp;&nbsp;&nbsp;&nbsp;
	<?php echo $appOrderManages->filter_acd()?>&nbsp;&nbsp;&nbsp;&nbsp;
	<?php echo $form->submit('',array('label'=>false,'div'=>false,'class'=>"input in-submit"))?>
</div>
</form>
</td>
<td>
	<div id="optional_col" style="text-align:right;padding:2px">
				<span>
					<a style="line-height:0px"  href="<?php echo Router::url(array('plugin'=>'order','controller'=>'order_places','action'=>'buy'))?>" >
						<img width="16" height="16" alt="" src="<?php echo $this->webroot ?>images/add.png"><?php __('Create New')?>
					</a>
				</span>
			</div>
</td>
</tr>
</table>
<?php if(empty($orders)):?>
<?php echo $this->element('common/no_result')?>
<?php else:?>
<?php
	if($do_action == 'buy'){
		$m = new BuyOrder();
	}else{
		$m = new SellOrder();
	}
?>
<table class="list">
	<thead>
		<tr>
			<td><?php echo $appOrder->show_order('id',__('Order #',true))?></td>
			<td><?php echo $appOrder->show_order('country',__('country',true))?></td>			
			<td><?php echo $appOrder->show_order('rate',__('rate',true))?></td>	
			<td>private/public</td>	
			<?php echo $appOrderManages->order_list_col('is_commit','commit',true)?>
			<?php echo $appOrderManages->order_list_col('commit_minutes','commit minutes',true)?>
			<td>status</td>
			<?php echo $appOrderManages->order_list_col('asr',$appOrder->show_order('asr','ASR'),true)?>
			<?php echo $appOrderManages->order_list_col('acd',$appOrder->show_order('acd','ACD'),true)?>
			
			<?php echo $appOrderManages->order_list_col('cli','CLI',false)?>
			<?php echo $appOrderManages->order_list_col('g729','G729',false)?>
			<?php echo $appOrderManages->order_list_col('fax','FAX',false)?>
			<?php echo $appOrderManages->order_list_col('create_time',$appOrder->show_order('create_time',__('Start Time',true)),true,Array('rel'=>'style="width:100px"'))?>
			<?php echo $appOrderManages->order_list_col('update_time',$appOrder->show_order('update_time',__('Update Time',true)),true,Array('rel'=>'style="width:100px"'))?>			
			<?php echo $appOrderManages->order_list_col('expire_time',$appOrder->show_order('expire_time',__('Expire Time',true)),true,Array('rel'=>'style="width:100px"'))?>
			<td><?php echo __('Confirmed',true);?></td>
			<td> <?php echo __('By Placed',true);?></td>
			<td style="text-align:center"><td><?php echo __('Operation',true);?></td></td>		
		</tr>
	</thead>
	<tbody>
	<?php ?>	
	<?php foreach($orders as $order):?>	
	<tr rel="tooltip" id="order_<?php echo array_keys_value($order,"0.id")?>">
		<td><?php echo array_keys_value($order,"0.id")?></td>	
		<td><?php echo array_keys_value($order,"0.country")?></td>
		<td><?php echo $appOrderManages->format_price(array_keys_value($order,"0.rate"))?></td>
		<td><?php echo $appOrderManages->format_radio(array(false=>"Public",true=>"Private"),array_keys_value($order,"0.is_private"))?></td>
		<?php 
			if($m->is_private($order)){
				$t =  $appOrderManages->format_radio(array(false=>"No",true=>"Yes"),array_keys_value($order,"0.is_commit"));
			}else{
				$t =  '-';
			}
			echo $appOrderManages->order_list_col('is_commit',$t,true);
		?>
		<?php 
			if($m->is_private($order)){
				$t = $appOrderManages->format_order_commit_minutes($order);
			}else{
				$t =  '-';
			}
			echo $appOrderManages->order_list_col('commit_minutes',$t,true);
		?>		
		<td><?php echo $appOrderManages->order_status(array_keys_value($order,"0.status"))?></td>
		<?php
			$acd = array_keys_value($order,"0.acd");
			$asr = array_keys_value($order,"0.asr");
			if(empty($acd)){$acd = '-'; }else{$acd = number_format($acd,2);}
			if(empty($asr)){$asr = '-'; }else{$asr = number_format($asr,2).'%';}
		?>
			<?php echo $appOrderManages->order_list_col('asr',$asr,true)?>
		<?php echo $appOrderManages->order_list_col('acd',$acd,true)?>
	
		<?php echo $appOrderManages->order_list_col('cli',array_keys_value($order,"0.cli"),false)?>
		<?php echo $appOrderManages->order_list_col('g729',array_keys_value($order,"0.g729"),false)?>
		<?php echo $appOrderManages->order_list_col('fax',array_keys_value($order,"0.fax"),false)?>
		<?php echo $appOrderManages->order_list_col('create_time',$appOrder->format_time(array_keys_value($order,"0.create_time")),true)?>
		<?php echo $appOrderManages->order_list_col('update_time',$appOrder->format_time(array_keys_value($order,"0.update_time")),true)?>		
		<?php 
			if($m->is_private($order)||true){
				$t =  $appOrder->format_time($appOrderManages->format_order_expire_time($order));
			}else{
				$t =  '-';
			}
			echo $appOrderManages->order_list_col('expire_time',$t,true);
		?>
		<?php if($do_action == 'buy'):?>
			<td><?php echo $appOrderManages->buy_order_list_response(array_keys_value($order,"0"))?></td>
			
				<td><?php echo $order[0]['user_name']?></td>
			<td style="text-align:center">
				<a target="_blank" href="<?php echo Router::url(array('plugin'=>'order','controller'=>'order_places','action'=>'buy','id'=>array_keys_value($order,"0.id")))?>"><img src="<?php echo $this->webroot ?>images/edit.png"/></a>&nbsp;
				<?php echo $appOrderManages->buy_order_list_operation(array_keys_value($order,"0"))?>
			</td>
		<?php else:?>
			<td><?php echo $appOrderManages->sell_order_list_response(array_keys_value($order,"0"))?></td>
			<td><?php echo $order[0]['user_name']?></td>
			<td style="text-align:center">
				<a target="_blank" href="<?php echo Router::url(array('plugin'=>'order','controller'=>'order_places','action'=>'sell','id'=>array_keys_value($order,"0.id")))?>"><img src="<?php echo $this->webroot ?>images/edit.png"/></a>&nbsp;
				<?php echo $appOrderManages->sell_order_list_operation(array_keys_value($order,"0"))?>
			</td>
		<?php endif;?>
	</tr>
	<?php endforeach;?>
	</tbody>
</table>
<?php if(true):?>
	<?php echo $this->element("common/order_code_tooltip")?>
<?php endif;?>
<?php endif;?>
