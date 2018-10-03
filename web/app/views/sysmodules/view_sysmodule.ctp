<ul class="breadcrumb">
    <li><?php __('You are here'); ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>sysmodules/view_sysmodule">
      <?php __('Configuration')?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>sysmodules/view_sysmodule">
      <?php echo __('Modules',true);?></a></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Modules',true);?></h4>
    
</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
        <?php  if ($_SESSION['role_menu']['Configuration']['sysmodules']['model_w']) {?>
        <a class="btn btn-primary btn-icon glyphicons circle_plus" href="<?php echo $this->webroot?>sysmodules/add_sysmodule"><i></i> <?php __('Create new'); ?></a>
        <?php }?>
    </div>
    <div class="clearfix"></div>
<div class="innerLR">

    <div class="widget widget-heading-simple widget-body-white">
        <div class="widget-body">
  <?php 			$mydata =$p->getDataArray();
			$loop = count($mydata); 
			if(empty($mydata)){
			?>
  <h2 class="msg center"><?php echo __('no_data_found',true);?></h2>
  <?php }else{

?>
            <div class="clearfix"></div>
  <table class="list footable table table-striped tableTools table-bordered  table-white table-primary">

    <thead>
      <tr>
        <th ><?php echo __('Module Name',true);?></th>
        <th><?php echo __('action')?></th>
      </tr>
    </thead>
    <tbody>
      <?php
			for ($i=0;$i<$loop;$i++){
		?>
      <tr class="row-1">
          <td align="center"><a title="<?php echo __('View sub-module')?>" class="module_name"  href="<?php echo $this->webroot?>syspris/view_syspri/<?php echo base64_encode($mydata[$i][0]['id'])?>"><?php echo $mydata[$i][0]['module_name']?> </a></td>
        
        <td class="last">
		<?php  if ($_SESSION['role_menu']['Configuration']['sysmodules']['model_w']) {?>
            <a title="<?php echo __('editmodule')?>"  href="<?php echo $this->webroot?>sysmodules/edit_sysmodule/<?php echo base64_encode($mydata[$i][0]['id'])?>"> <i class="icon-edit"></i> </a>
          
          <a title="<?php echo __('del')?>" onClick="return myconfirm('Are you sure to delete it?',this);" href="<?php echo $this->webroot?>sysmodules/del/<?php echo base64_encode($mydata[$i][0]['id'])?>/<?php echo $mydata[$i][0]['module_name']?>"> <i class="icon-remove"></i> </a>
          <?php }?>
			<a title="<?php echo __('View Sub-module')?>"  href="<?php echo $this->webroot?>syspris/view_syspri/<?php echo base64_encode($mydata[$i][0]['id'])?>"> <i class="icon-list-alt"></i> </a>
          <?php if ($mydata[$i][0]["status"] == 1): ?>
              <a title="Deactivate The Module" class="inactivate-module" data-id="<?php echo $mydata[$i][0]['id'];?>" ><i class="icon-check"></i></a>
          <?php else: ?>
              <a title="Activate The Module" class="activate-module" data-id="<?php echo $mydata[$i][0]['id'];?>"><i class="icon-check-empty"></i></a>
          <?php endif; ?>
          </td>
      </tr>
      <?php }?>
    </tbody>
  </table>
            <!--
  <div class="row-fluid separator">
                <div class="pagination pagination-large pagination-right margin-none">
                    <?php echo $this->element('page'); ?>
                </div>
            </div>
            -->
            <div class="clearfix"></div>
</div>
<div>
  <?php }?>
</div>
    </div>
</div>

<script type="text/javascript">

$(function() {

           $('.activate-module, .inactivate-module').click(function(){
                var action = '';
                var moduleId = $(this).attr('data-id');
                var moduleName = $(this).closest('tr').find('.module_name').text();
                var $that = $(this);
                if ($that.hasClass('inactivate-module')) {
                    action = 'inactivate';
                } else {
                    action = 'activate';
                }
                var confirmMessage = 'Are you sure to ' + action + ' the module [' + moduleName + '] ?';
                bootbox.confirm(confirmMessage, function(result) {
                    if (result) {
                        $.ajax({
                            url: '<?php echo $this->webroot ?>sysmodules/' + action + 'Ajax',
                            type: 'post',
                            dataType: 'json',
                            data: {moduleId: moduleId},
                            success: function(res) {
                                if (res.success) {
                                    jGrowl_to_notyfy(res.message, {theme: res.theme});
                                    location.reload();
                                }
                            }

                        });
                    }
                });
           });
});

</script>