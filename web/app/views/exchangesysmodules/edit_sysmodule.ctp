<div id="title">
  <h1><?php __('Exchange Manage')?>&gt;&gt;<?php echo __('Edit Module',true);?></h1>
  <ul id="title-menu">
    <li> <a class="link_back" href="<?php echo $this->webroot?>exchangesysmodules/view_sysmodule"> <img width="16" height="16"  alt="" src="<?php echo $this->webroot?>images/icon_back_white.png"/> &nbsp;<?php echo __('goback')?> </a> </li>
  </ul>
</div>
<?php //****************************************************页面主体?>
<div class="container">
<?php $id=array_keys_value($this->params,'pass.0')?>
<?php echo $form->create ('Exchangesysmodule', array ('action' => 'add_sysmodule' ));?>

  <table class="form">
    <tbody>
      <tr>
        <td class="label label2"><?php echo __('Module Name',true);?>:</td>
        <td class="value value2"> 
		<?php echo $form->input('module_name', array('label'=>false ,'div'=>false,'type'=>'text','class'=>'input in-text','maxLength'=>'256'));?></td>
      </tr>
      <tr>
        <td class="label label2"><?php echo __('Order Number',true);?>:</td>
        <td class="value value2"><?php echo $form->input('order_num',
 		array('label'=>false ,'div'=>false,'type'=>'text','class'=>'input in-text'));?></td>
      </tr>
      
      <tr>
        <td class="label label2"><?php echo __('Type',true);?>:</td>
        <td class="value value2"><?php echo $form->input('type',
 		array('options'=>array('0'=>'Exchange','1'=>'Agent','2'=>'Partition'),'label'=>false ,'div'=>false,'type'=>'select','select'=>'true','class'=>'in-select'));?>
        </td>
      </tr>
    </tbody>
  </table>
<?php  if ($_SESSION['role_menu']['Exchange Manage']['exchangesysmodules:sysmodules']['model_w']) {?>
  <div id="form_footer">
  <input type="hidden" name="data[Exchangesysmodule][id]" value="<?php echo $id;?>" />
  		
    <input type="submit" value="<?php echo __('submit')?>" />
  </div>
  <?php }?>
  <?php echo $form->end();?> </div>
