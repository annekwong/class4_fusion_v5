<table>
  <tr>
    <td><?php echo __('id',true);?></td>
    <td><?php echo __('Carriers',true);?></td>
    <td><?php echo __('Egress Trunk Name',true);?></td>
    <?php  if ($_SESSION['role_menu']['Routing']['dynamicroutes']['model_w']) {?>
    <td><?php echo __('active',true);?></td>
    <?php }?>
  </tr>
  <?php if(!empty($p)){?>
  <?php foreach($p as $list){?>
  <tr>
    <td><?php echo $list[0]['resource_id']?></td>
    <td><?php echo $list[0]['name']?></td>
    <td><?php echo $list[0]['alias']?></td>
    <?php  if ($_SESSION['role_menu']['Routing']['dynamicroutes']['model_w']) {?>
    <td><a style="<?php if($list[0]['active']!=1){ echo 'display:none';}?> "
			    onclick="return active(this,'<?php echo $this->webroot?>gatewaygroups/dis_able/<?php echo $list[0]['resource_id']?>/view_egress')"
			    	href='#' title="<?php echo __('disable')?>"> <img  title="<?php echo __('wangtodisable')?>" src="<?php echo $this->webroot?>images/flag-1.png"> </a> <a style="<?php if($list[0]['active']==1){ echo 'display:none';} ?> "   
					 onclick="return disable(this,'<?php echo $this->webroot?>gatewaygroups/active/<?php echo $list[0]['resource_id']?>/view_egress')" 
					 href='#' title="<?php echo __('disable')?>"> <img  title="<?php echo __('wangtoactive')?>" src="<?php echo $this->webroot?>images/flag-0.png"> </a></td>
    <?php }?>
  </tr>
  <?php }?>
  <?php }?>
</table>
<script type="text/javascript">
function active(obj,url){
	if(confirm("<?php echo __('confirmactivegate')?>")){
		jQuery.get(url,
				function(data){
							jQuery(obj).hide();
							jQuery(obj).parent().find('a:nth-child(2)').show();
				
				}
		);
	}
	return false;
}
function disable(obj,url){
	if(confirm("<?php echo __('confirmactivegate')?>")){
		jQuery.get(url,
				function(data){
							jQuery(obj).hide();
							jQuery(obj).parent().find('a:nth-child(1)').show();

				}
		);
	}
	return false;
}
</script>