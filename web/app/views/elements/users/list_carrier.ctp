<?php if (empty($this->data)) {?>
<?php echo $this->element('listEmpty')?>
<?php } else {?>
<div>
            <div class="clearfix"></div>
<table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
<thead>
<tr>
<?php  if ($_SESSION['role_menu']['Configuration']['users']['model_w']) {?>
<th><?php echo __('status')?></th>
<?php }?>
<th><?php echo $appCommon->show_order('name',__('username',true))?> </th>
<th><?php echo $appCommon->show_order( 'name',__('Carrier Name',true))?> </th>
<?php if(!isset($n_last_login_time) || !$n_last_login_time){?>
<th><?php echo $appCommon->show_order('last_login_time',__('last_modified',true))?> </th>
 <?php }?>
<?php  if ($_SESSION['role_menu']['Configuration']['users']['model_w']) {?><th  class="last"><?php echo __('action')?></th>
<?php }?>
</tr>
</thead>
<tbody>
<?php foreach ($this->data as $list){?>
	<tr>
    <?php  if ($_SESSION['role_menu']['Configuration']['users']['model_w']) {?>
		<td>
        
			<?php if ($list['User']['active']){?>
                   <a style="width:80%;display:block" href="javascript:void(0)" onclick="inactive(this,'<?php echo $list['User']['user_id']?>','<?php echo $list['User']['name'] ?>');"> 
                       <i class="icon-check"></i>
                   </a>
        <?php }else{?>
					<a style="width:80%;display:block" href="javascript:void(0)" onclick="active(this,'<?php echo $list['User']['user_id']?>','<?php echo $list['User']['name']?>');"> 
						 <i class="icon-unchecked"></i>
					</a>
			<?php }?>
            
		</td>
        <?php }?>
		<td>
		<a style="width:80%;display:block" title="" href="<?php echo $this->webroot?>users/add_carrier_user/<?php echo $list['User']['user_id']?>">
			<?php echo array_keys_value($list,'User.name')?>
			</a>
			</td>
			
		<td>
			<?php echo array_keys_value($list,'Client.name')?>
		</td>
		
		<?php if(!isset($n_last_login_time) || !$n_last_login_time){?>
		<td><?php echo array_keys_value($list,'User.last_login_time')?></td>
		<?php }?>
        <?php  if ($_SESSION['role_menu']['Configuration']['users']['model_w']) {?>
		<td class="last">
			<a title="<?php echo __('Edit') ?>" href="<?php echo $this->webroot?>users/add_carrier_user/<?php echo $list['User']['user_id']?>">
				 <i class="icon-edit"></i>
			</a>
                    <a  title="<?php echo __('del')?>"  onclick="return myconfirm('Are you sure to delete it?',this);" href="<?php echo $this->webroot?>users/del/<?php echo base64_encode($list['User']['user_id']); ?>/<?php echo $list['User']['name']?>">
				 <i class="icon-remove"></i>
			</a>
   </td>
   <?php }?>
	</tr>
<?php }?>
</tbody></table>
            <div class="row-fluid">
                <div class="pagination pagination-large pagination-right margin-none">
                    <?php echo $this->element('xpage'); ?>
                </div> 
            </div>
            <div class="clearfix"></div>
</div>
<?php }?>
<script type="text/javascript">
//启用Reseller
function active(obj,user_id,name){
	if (confirm("Are you sure to activate it?")) {
		jQuery.get("<?php echo $this->webroot?>users/activeornot?status=true&id="+user_id,function(data){
			if (data.trim() == 'true') {
//				obj.getElementsByTagName('img')[0].src = "<?php echo $this->webroot?>images/flag-1.png";
//				obj.title = "<?php echo __('disable')?>";
				$(obj).find('i').removeClass('icon-check').addClass('icon-unchecked');
				//obj.getElementsByTagName('i')[0].class = "icon-unchecked";
				obj.onclick = function(){inactive(this,user_id,name);};
				$(obj).children('i').removeClass('icon-unchecked').addClass('icon-check');
				jGrowl_to_notyfy("The User [" + name+" <?php echo __('] is actived successfully!')?>",{theme:'jmsg-success'});
			} else {
				jGrowl_to_notyfy("The User [" + name+" <?php echo __('] is actived unsuccessfully!')?>",{theme:'jmsg-error'});
			}
		});
	}
}

function inactive(obj,user_id,name){
	if (confirm("Are you sure to deactivate it?")) {
		jQuery.get("<?php echo $this->webroot?>users/activeornot?status=false&id="+user_id,function(data){
			if (data.trim() == 'true') {
//				obj.getElementsByTagName('img')[0].src = "<?php echo $this->webroot?>images/flag-0.png";
//				obj.title = "<?php echo __('active')?>";
				$(obj).find('i').removeClass('icon-unchecked').addClass('icon-check');
				//obj.getElementsByTagName('i')[0].class = "icon-check";
				obj.onclick = function(){active(this,user_id,name);};
				$(obj).children('i').removeClass('icon-check').addClass('icon-unchecked');
				jGrowl_to_notyfy("The User [" + name+" <?php echo __('] is disabled successfully!')?>",{theme:'jmsg-success'});
			} else {
				jGrowl_to_notyfy("The User [" + name+" <?php echo __('] is actived unsuccessfully!')?>",{theme:'jmsg-error'});
			}
		});
	}
}
</script>
