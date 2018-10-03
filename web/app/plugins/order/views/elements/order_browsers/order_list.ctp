<?php if(empty($orders)):?>
	<?php echo $this->element('common/no_result')?>
<?php else:?>
<div style="clear:both;"></div>
<table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary" style="margin-top:5px;">
	<thead>
		<tr>
			<th><?php echo __('username',true);?></th>
			<th><?php echo $appOrder->show_order('id',__('Order #',true))?></th>
            <th></th>
			<th><?php echo $appOrder->show_order('country',__('country',true))?></th>			
			<th><?php echo $appOrder->show_order('rate',__('rate',true))?></th>		
			<?php if($is_private):?><th><?php echo __('commit',true);?></th><?php endif;?>
			<?php if($is_private):?><th><?php echo __('commit minutes',true);?></th><?php endif;?>
			<th><?php echo __('status',true);?></th>
			<?php echo $appOrderBrowsers->order_list_col('asr',$appOrder->show_order('asr','ASR'),true)?>
				<?php echo $appOrderBrowsers->order_list_col('acd',$appOrder->show_order('acd','ACD'),true)?>
			
		
			<?php echo $appOrderBrowsers->order_list_col('cli','CLI',false)?>
			<?php echo $appOrderBrowsers->order_list_col('g729','G729',false)?>
			<?php echo $appOrderBrowsers->order_list_col('fax','FAX',false)?>
			<?php echo $appOrderBrowsers->order_list_col('create_time',$appOrder->show_order('create_time','Start Time'),true)?>
			<!-- <?php echo $appOrderBrowsers->order_list_col('update_time',$appOrder->show_order('update_time'),true)?> -->	
            <?php echo $appOrderBrowsers->order_list_col('end_date',$appOrder->show_order('end_date','End Date'),true)?>	
			<?php if($is_private):?><?php echo $appOrderBrowsers->order_list_col('expire_time',$appOrder->show_order('expire_time'),true)?><?php endif;?>
			<th style="text-align:center"><?php echo __('Response',true);?></th>	
           <?php  if ($_SESSION['role_menu']['Exchange Manage']['user_trunks']['model_w']) {?>
            <th  class="last"><?php echo __('action',true);?></th>	
            <?php }?>	
		</tr>
	</thead>
	<tbody>	
	<?php $index = 1?>
	<?php foreach($orders as $order):?>		
		<?php if(isset($is_private) && $is_private == true):?>
		<tr rel="tooltip" id="order_<?php echo array_keys_value($order,"0.id")?>">
		<?php else:?>
		<?php if(!($_SESSION['login_type'] == 1) && (int)array_keys_value($order,"0.client_id") == (int)$_SESSION['sst_client_id']):?>
		<tr rel="tooltip" id="order_<?php echo array_keys_value($order,"0.id")?>" style="font-weight:bold;">
		<?php else:?>
		<tr rel="tooltip" id="order_<?php echo array_keys_value($order,"0.id")?>">
		<?php endif;?>
		<?php endif;?>
		<td><?php echo $order_user[array_keys_value($order,"0.client_id")];?></td>
		<td><?php echo array_keys_value($order,"0.id")?></td>
        <td><a href="#"><img src="<?php echo $this->webroot;?>images/download.png" /></a></td>
		<td><?php echo array_keys_value($order,"0.country")?></td>
		<td><?php echo $appOrderBrowsers->format_price(array_keys_value($order,"0.rate"))?></td>
		<?php if($is_private):?><td><?php echo $appOrderBrowsers->format_radio(array(false=>"No",true=>"Yes"),array_keys_value($order,"0.is_commit"))?></td><?php endif;?>
		<?php if($is_private):?><td><?php echo $appOrderBrowsers->format_order_commit_minutes($order)?></td><?php endif;?>
		<td><?php echo $appOrderBrowsers->order_status(array_keys_value($order,"0.status"))?></td>
		<?php 
		$acd = array_keys_value($order,"0.acd");
		$asr = array_keys_value($order,"0.asr");
		if(empty($acd)){$acd = '-';	}else{$acd = number_format($acd,2);}
		if(empty($asr)){$asr = '-';	}else{$asr = number_format($asr,2).'%';}
		?>
			<?php echo $appOrderBrowsers->order_list_col('asr',$asr,true)?>
		<?php echo $appOrderBrowsers->order_list_col('acd',$acd,true)?>
	
		<?php echo $appOrderBrowsers->order_list_col('cli',array_keys_value($order,"0.cli")==1?'true':'false',false)?>
		<?php echo $appOrderBrowsers->order_list_col('g729',array_keys_value($order,"0.g729")==1?'true':'false',false)?>
		<?php echo $appOrderBrowsers->order_list_col('fax',array_keys_value($order,"0.fax")==1?'true':'false',false)?>
		<?php echo $appOrderBrowsers->order_list_col('create_time',$appOrder->format_time(array_keys_value($order,"0.create_time")),true)?>
		<!-- <?php echo $appOrderBrowsers->order_list_col('update_time',$appOrder->format_time(array_keys_value($order,"0.update_time")),true)?> -->
        <?php echo $appOrderBrowsers->order_list_col('end_date',$appOrder->format_time(array_keys_value($order,"0.end_date")),true)?>
		<?php if($is_private):?>
			<?php echo $appOrderBrowsers->order_list_col('expire_time',$appOrder->format_time($appOrderBrowsers->format_order_expire_time($order)))?>
		<?php endif;?>
		<td style="text-align:center">
		<?php 
		if (1)
		{
			echo $order[0]['contract_num'];
		}
		else
		{
		?>
		<?php if(isset($is_private) && $is_private == true):?>
			<?php if($do_action == 'buy'){?>
				<?php echo $appOrderBrowsers->private_buy_order_list_send_response(array_keys_value($order,"0"))?>
			<?php }else{?>
				<?php echo $appOrderBrowsers->private_sell_order_list_send_response(array_keys_value($order,"0"))?>
			<?php }?>
		<?php else:?>
			<?php if($_SESSION['login_type'] == 3){
				if((int)array_keys_value($order,"0.client_id") != (int)$_SESSION['sst_client_id']){
					if($do_action == 'buy'){
						if(array_keys_value($order,"0.deal")){
							echo 'sold';
						}else{
							echo $appOrderBrowsers->buy_order_list_send_response(array_keys_value($order,"0"));
						}
					}else{
						if(array_keys_value($order,"0.deal")){
							echo 'Bought';
						}else{
							echo $appOrderBrowsers->sell_order_list_send_response(array_keys_value($order,"0"));
						}
					}
				}
			}?>
		<?php endif;
		}?>
		</td>
       <?php  if ($_SESSION['role_menu']['Exchange Manage']['user_trunks']['model_w']) {?>
        <td class="last">
      <a title="View the order detail" href="javascript:void(0)" onclick="window.location.href='<?php echo $this->webroot;?>order/order_browsers/<?php if($do_action=='buy')echo 'buy' ;else echo 'sell'?>/order_detail/<?php echo array_keys_value($order,"0.id")?>';event.stopPropagation();">
      
       
        		<b><?php echo __('More',true);?></b>
       	 	</a>
         <?php if ($do_action=='sell'){?>
        <?php if (array_keys_value($order,"0.is_select")=='1'||array_keys_value($order,"0.is_select")=='0'){?>
        	<a title="Are you sure to select" href="javascript:void(0)" onclick="isselect(this,'<?php echo array_keys_value($order,"0.id");?>','select','<?php echo array_keys_value($order,"0.is_select")?>');event.stopPropagation();">
        		<img src="<?php echo $this->webroot?>images/status_notclosed.gif" title=" Click to select">
       	 	</a>
            <?php }else{?>
            <a title="Are you sure to un-elect" href="javascript:void(0)" onclick="unselect(this,'<?php echo array_keys_value($order,"0.id");?>','select','<?php echo array_keys_value($order,"0.is_select")?>');event.stopPropagation();">
            	<img static="0" src="<?php echo $this->webroot?>images/status_closed.gif" title=" Click to un-select">
            </a>
            <?php }?>
            <?php }?>
            
            
        	<?php if (array_keys_value($order,"0.active")=='1'){?>
        	<a title="Are you sure to inactive" href="javascript:void(0)" onclick="inactive(this,'<?php echo array_keys_value($order,"0.id");?>','active');event.stopPropagation();">
        		<img src="<?php echo $this->webroot?>images/flag-1.png" title=" Click to inactive">
       	 	</a>
             <?php }else{?>
            <a title="Are you sure to active" href="javascript:void(0)" onclick="active(this,'<?php echo array_keys_value($order,"0.id");?>','active');event.stopPropagation();">
            	<img static="0" src="<?php echo $this->webroot?>images/flag-0.png" title=" Click to active">
            </a>
             <?php }?>
             
             <?php if (array_keys_value($order,"0.purged")=='1'){?>
            <a title="Are you sure to unpurged" href="javascript:void(0)" onclick="inactive(this,'<?php echo array_keys_value($order,"0.id");?>','purged');event.stopPropagation();">
        		<img src="<?php echo $this->webroot?>images/status_notclosed.gif" title=" Click to unpurged">
       	 	</a>
            <?php }else{?>
            <a title="Are you sure to purged" href="javascript:void(0)" onclick="active(this,'<?php echo array_keys_value($order,"0.id");?>','purged');event.stopPropagation();">
            	<img static="0" src="<?php echo $this->webroot?>images/status_closed.gif" title=" Click to purged">
            </a>
            <?php }?>	
        </td>
        <?php }?>	
	</tr>
	<?php endforeach;?>
	</tbody>
</table>
<?php if(true):?>
	<?php echo $this->element("common/order_code_tooltip")?>
<?php endif;?>
<?php endif;?>
<script type="text/javascript">
//启用Reseller
function active(obj,order_id,action_type){
	var tip;
	if(action_type=='purged'){
		tip=confirm("<?php echo __('Are you sure to purged?') ?>");
	}else if(action_type=='active'){
		tip=confirm("<?php echo __('Are you sure to active?') ?>");
	}
	if (tip) {
		jQuery.get("<?php echo $this->webroot?>order/order_browsers/changeaction?order_type=<?php echo $do_action;?>&action_type="+action_type+"&action=t&id="+order_id,function(data){
			if (data.trim() != null) {
				
				if(action_type=='purged'){
					obj.getElementsByTagName('img')[0].src = "<?php echo $this->webroot?>images/flag-1.png";
					obj.title ="Change to unpurged";
				}else if(action_type=='active'){
					obj.getElementsByTagName('img')[0].src = "<?php echo $this->webroot?>images/status_closed.gif";
					obj.title ="Change to active";
				}
				obj.onclick = function(){inactive(this,order_id,action_type);};
				window.location.reload();
				if(action_type=='purged'){
					jGrowl_to_notyfy("Change to purged success",{theme:'jmsg-success'});
				}else if(action_type=='active'){
					jGrowl_to_notyfy("Change to active success",{theme:'jmsg-success'});
				}
				
			} else {
				if(action_type=='purged'){
					jGrowl_to_notyfy("Change to purged fail",{theme:'jmsg-alert'});
				}else if(action_type=='active'){
					jGrowl_to_notyfy("Change to active fail",{theme:'jmsg-alert'});
				}
				
			}
		});
	}
}
function inactive(obj,order_id,action_type){
	var tip;
	if(action_type=='purged'){
		tip=confirm("<?php echo __('Are you sure to unpurged?') ?>");
	}else if(action_type=='active'){
		tip=confirm("<?php echo __('Are you sure to Inactive?') ?>");
	}
	if (tip) {
		jQuery.get("<?php echo $this->webroot?>order/order_browsers/changeaction?order_type=<?php echo $do_action;?>&action_type="+action_type+"&action=f&id="+order_id,function(data){
			if (data.trim() != null) {
				
				
				if(action_type=='purged'){
					obj.getElementsByTagName('img')[0].src = "<?php echo $this->webroot?>images/status_notclosed.gif";
					obj.title ="Change to purged";
				}else if(action_type=='active'){
					obj.getElementsByTagName('img')[0].src = "<?php echo $this->webroot?>images/flag-0.png";
					obj.title ="Change to inactive";
				}
				
				obj.onclick = function(){active(this,order_id,action_type);};
				window.location.reload();
				if(action_type=='purged'){
					jGrowl_to_notyfy("Change to unpurged success",{theme:'jmsg-success'});
				}else if(action_type=='active'){
					jGrowl_to_notyfy("Change to inactive success",{theme:'jmsg-success'});
				}
				
			} else {
				if(action_type=='purged'){
					jGrowl_to_notyfy("Change to unpurged fail",{theme:'jmsg-alert'});
				}else if(action_type=='active'){
					jGrowl_to_notyfy("Change to inactive fail",{theme:'jmsg-alert'});
				}
				
			}
		});
	}
}

function isselect(obj,order_id,action_type,select_val){
	var tip;
	if(action_type=='select'){
		tip=confirm("<?php echo __('Are you sure to select?') ?>");
	}
	if (tip) {
		jQuery.get("<?php echo $this->webroot?>order/order_browsers/changeaction?order_type=<?php echo $do_action;?>&action_type="+action_type+"&id="+order_id+"&select_val=2",function(data){
			if (data.trim() != null) {
				obj.getElementsByTagName('img')[0].src = "<?php echo $this->webroot?>images/status_closed.gif";
				obj.title ="Change to select";
				obj.onclick = function(){inactive(this,order_id,action_type);};
				window.location.reload();
				jGrowl_to_notyfy("Change to select success",{theme:'jmsg-success'});
				
			} else {
				jGrowl_to_notyfy("Change to select fail",{theme:'jmsg-alert'});
			}
		});
	}
}
function unselect(obj,order_id,action_type,select_val){
	var tip;
	if(action_type=='select'){
		tip=confirm("<?php echo __('Are you sure to Unselect the selected?') ?>");
	}
	if(tip){
		url="<?php echo $this->webroot?>order/order_browsers/changeaction?order_type=<?php echo $do_action;?>&action_type="+action_type+"&id="+order_id+"&select_val=1";
		jQuery.get(url,function(data){
			if (data.trim() != null) {
				obj.getElementsByTagName('img')[0].src = "<?php echo $this->webroot?>images/status_notclosed.gif";
				obj.title ="Change to unselect";
				obj.onclick = function(){active(this,order_id,action_type);};
				window.location.reload();
				jGrowl_to_notyfy("Change to unselect success",{theme:'jmsg-success'});
				
			} else {
				jGrowl_to_notyfy("Change to unselect fail",{theme:'jmsg-alert'});
			}
		});
	}

}
</script>