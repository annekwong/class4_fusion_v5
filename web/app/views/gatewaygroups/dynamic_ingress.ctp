<?php $mydata=$p->getDataArray()?>
<table>
	<tr>
		<td><?php echo __('id',true);?></td>
		<td><?php echo __('Carriers',true);?></td>
		<td><?php echo __('name',true);?></td>
		<td><?php echo __('Call limit',true);?></td>
		<td><?php echo __('CPS Limit',true);?></td>
		<?php  if ($_SESSION['role_menu']['Routing']['dynamicroutes']['model_w']) {?><td><?php __('Active')?></td><?php }?>
	</tr>
	<?php if(!empty($mydata)){?>
	<?php foreach($mydata as $list){?>
	<tr>
		<td><?php echo $list[0]['resource_id']?></td>
		<td><?php echo $list[0]['client_name']?></td>
		<td><?php echo $list[0]['alias']?></td>
		<td><?php echo array_keys_value_empty($list,'0.capacity','Unlimited')?></td>
		<td><?php echo array_keys_value_empty($list,'0.cps_limit','Unlimited')?></td>
		<?php  if ($_SESSION['role_menu']['Routing']['dynamicroutes']['model_w']) {?><td>
				<?php if($list[0]['active']==1){?>
			    <a onclick="return confirm('<?php echo __('confirmdisablegate')?>');"  
				     href="<?php echo $this->webroot?>gatewaygroups/dis_able/<?php echo $list[0]['resource_id']?>/view_egress" title="<?php echo __('disable')?>">
			    	 	<img  title="<?php echo __('wangtodisable')?>" src="<?php echo $this->webroot?>images/flag-1.png">
			  	 </a>
				<?php }else{?>
					 <a  onclick="return confirm('<?php echo __('confirmactivegate')?>');"  
							href="<?php echo $this->webroot?>gatewaygroups/active/<?php echo $list[0]['resource_id']?>/view_egress" title="<?php echo __('disable')?>">
							<img  title="<?php echo __('wangtoactive')?>" src="<?php echo $this->webroot?>images/flag-0.png">
						</a>
				<?php }?>
		</td>
        <?php }?>
	</tr>
	<?php }?>
	<?php }?>
</table>