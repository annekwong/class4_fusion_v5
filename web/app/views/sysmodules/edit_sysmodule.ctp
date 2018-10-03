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
        <a class="btn btn-icon btn-inverse glyphicons circle_arrow_left" href="<?php echo $this->webroot?>sysmodules/view_sysmodule"><i></i> <?php __('Back'); ?></a>
    </div>
    <div class="clearfix"></div>
<div class="innerLR">

    <div class="widget widget-heading-simple widget-body-white ">
        <div class="widget-body">
<?php $id=array_keys_value($this->params,'pass.0')?>
<?php echo $form->create ('Sysmodule', array ('action' => 'add_sysmodule' ));?>

  <table class="form table dynamicTable tableTools table-bordered  table-white">
      <colgroup>
        <col width="40%">
        <col width="60%">
    </colgroup>
    <tbody>
      <tr>
        <td class="align_right"><?php echo __('Module Name',true);?> </td>
        <td class="value value2"> 
		<?php echo $form->input('module_name', array('label'=>false ,'div'=>false,'type'=>'text','class'=>'input in-text validate[required,custom[onlyLetterNumberLine]]','maxLength'=>'256'));?></td>
      </tr>
      <tr>
        <td class="align_right"><?php echo __('Order Number',true);?> </td>
        <td class="value value2"><?php echo $form->input('order_num',
 		array('label'=>false ,'div'=>false,'type'=>'text','class'=>'input in-text validate[required,custom[integer]]'));?></td>
      </tr>
    </tbody>
  </table>
<?php  if ($_SESSION['role_menu']['Configuration']['sysmodules']['model_w']) {?>
  <div id="form_footer" class="button-groups center">
  <input type="hidden" name="data[Sysmodule][id]" value="<?php echo $id;?>" />
  		
    <input type="submit" class="btn btn-primary"  value="<?php echo __('submit')?>" />
    <input type="reset"  value="<?php __('Reset')?>" class="btn btn-default" />
  </div>
  <?php }?>
  <?php echo $form->end();?> </div>
    </div>
</div>