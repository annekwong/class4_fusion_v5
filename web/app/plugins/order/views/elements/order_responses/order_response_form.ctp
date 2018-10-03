<?php $prefix = Inflector::camelize($do_action);?>
<?php if(isset($has_deal) && $has_deal):?>
<br style="clear:both" />
<br />
<br />
	<center><h3 style="color:red">
	<?php if($prefix == 'Buy'):?>
		<?php __('You has sold to this order')?>
	<?php else:?>
		<?php __('You has bought this order')?>
	<?php endif;?>
	</h3></center>
<?php elseif(isset($not_enough_money) && $not_enough_money):?>
<br style="clear:both" />
<br />
<br />
	<center><h3 style="color:red"><?php __('Not Enough Money')?>.</h3></center>	
<?php else:?>
<?php $m = new BuyOrder(); ?>
<center>
	<form id="BuyOrderResponseAddForm" method="POST" action="" onsubmit="return onBuyOrderResponseSubmit()">		
		<table class="cols" style="width:50%;">
			<tr>
				<td>											
					<fieldset><legend><?php echo __('quantity',true);?></legend>
						<table class="form">			
							<tr>						
								<td><?php echo __('resource',true);?>:</td> 
								<td><?php echo $form->input("Contract.resource_id",array('options' => $appOrderResponses->format_resources_options($resources),'label'=>false,'div'=>false,'type' => "select"));?></td> 
							</tr>
							<?php if($m->is_commit($order) && $m->is_private($order)):?>
							<tr> 
								<td><?php echo __('commit minutes',true);?>:</td> 
								<td><?php echo $form->input("Contract.commit_minutes",array('label'=>false,'div'=>false ,'maxlength'=>12,'id'=>'commit_minutes'))?></td> 
							</tr>													
							<tr> 
								<td><?php echo __('Expire Time',true);?>:</td> 
								<td><?php echo $form->input("Contract.expire_time",array('id'=>'expire_time','value' => $appOrderResponses->format_wdatepicker_time(array_keys_value($this->data,$prefix."OrderResponse.expire_time")),'onfocus'=>"WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'});", 'class'=>"input in-text wdate",'readonly'=>true,'label'=>false ,'div'=>false,'type'=>'text'))?></td> 
							</tr>	
							<?php endif;?>							
						</table>
					</fieldset>
				</td>
			</tr>
		</table>
	<input type="submit" value="<?php echo $prefix == 'Buy' ? 'Sell' : 'Buy'?>" />
	</form>	
</center>
<script type="text/javascript">
var onBuyOrderResponseSubmit = function(){
	<?php if($m->is_private($order) && $m->is_commit($order)):?>
		if(App.Common.isBlank($('#commit_minutes').val())){
				alert("commit minutes is blank.");
				return false;
		}
		
		if(App.Common.isBlank($('#expire_time').val())){
			alert("Please choice expire Time.");
			return false;
		}
	<?php endif;?>
	return confirm('Are you sure?');
}
</script>
<?php endif;?>