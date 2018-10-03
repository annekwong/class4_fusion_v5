<div id="title" >
	<h1><?php  __('Finance')?>&gt;&gt;<?php echo __("Unpaid Bills Summary")?></h1>
		<?php echo $this->element("search")?> 
</div>
<div id="container">
<fieldset style="width: 100%; display: none" id="advsearch" class="title-block">
	<form method="get">
	<input type="hidden" value="1" name="adv_search">
	<table style="width: auto;">
		<tbody>
		<tr>
		    <td>
		    		<?php echo $form->input('filter_paid_type',Array('type'=>'select',
		    				'div'=>false,
						 	'options'=>array('1' => 'All Unpaid & Partially Bills','2' => 'Unpaid Bills','3' => 'Partially Paid Bills'),
						 	'value'=>array_keys_value($this->params,'url.filter_paid_type'),
							'class'=>"input in-select" ,
							'name'=>'filter_paid_type'
								)
								 );
						?>
						<?php echo $appCommon->filter_date_range('invoice_time')?>
				 </td>
		    <td class="buttons"><input type="submit" class="input in-submit" value="<?php echo __('submit',true);?>"></td>
		</tr>
		</tbody>
	</table>
</form>
</fieldset>
<table class="list">
<?php $data = $p->getDataArray();?>
<?php if(empty($data)):?>
<div class="msg"><?php echo __('no_data_found',true);?></div>
<?php else:?>
<thead>
	<tr><td><?php echo __('Due date',true);?></td><td><?php echo __('Customer',true);?></td><td><?php echo __('Amount',true);?></td><td><?php echo __('Balance due',true);?></td></tr>
</thead>
<tbody>
<?php foreach($data as $item):?>
	<tr>
		<td><?php echo $item[0]['due_date']?></td>
		<td><?php echo $item[0]['client']?></td>
		<td><?php echo $item[0]['total'] +  $item[0]['pay_amount']?></td>
		<td><?php echo $item[0]['total']?></td>
	</tr>
<?php endforeach;?>
<?php endif;?>
</tbody>
</table>
</div>
