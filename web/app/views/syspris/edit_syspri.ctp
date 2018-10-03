<style>
    input{width: 220px;}
</style>
<?php
$module_id=array_keys_value($this->params,'pass.0');
$id = array_keys_value($this->params,'pass.1');
?>

<ul class="breadcrumb">
    <li><?php __('You are here'); ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Configuration') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Modules', true); ?></li>
</ul>


<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Modules', true); ?></h4>

</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
    <a class="btn btn-icon btn-inverse glyphicons circle_arrow_left" href="<?php echo $this->webroot?>syspris/view_syspri/<?php  echo $module_id;?>"><i></i> <?php __('Back'); ?></a>
</div>
<div class="clearfix"></div>
<?php //****************************************************页面主体?>

<div class="innerLR">

    <div class="widget widget-heading-simple widget-body-white">
        <div class="widget-body">

            <?php echo $form->create ('Syspri', array ('id' => 'Syspri', 'action' => 'edit_syspri/'.  array_keys_value($this->params,'pass.0')));?>

            <table class="form table dynamicTable tableTools table-bordered  table-white">
                <colgroup>
                    <col width="40%">
                    <col width="60%">
                </colgroup>
                <tbody>
                <tr>
                    <td class="align_right"><?php echo __('Parent Module',true);?> </td>
                    <td class="value value2">
                        <?php echo $form->input('flag', array('options'=>$modules, 'name'=>'data[Syspri][module_id]','label'=>false ,'div'=>false,'type'=>'select', 'value'=> base64_decode(array_keys_value($this->params,'pass.0'))));?>
                    </td>
                </tr>
                <tr>
                    <td class="align_right"><?php echo __('Internal Alias',true);?> </td>
                    <td class="value value2"><?php echo $form->input('pri_name',
                            array('label'=>false ,'div'=>false,'type'=>'text','class'=>'input in-text validate[required]','maxLength'=>'256'));?></td>
                </tr>
                <tr>
                    <td class="align_right"><?php echo __('Display Name',true);?> </td>
                    <td class="value value2"><?php echo $form->input('pri_val',
                            array('label'=>false ,'div'=>false,'type'=>'text','class'=>'input in-text validate[required]','maxLength'=>'100'));?></td>
                </tr>
                <tr>
                    <td class="align_right"><?php echo __('Enabled',true);?> </td>
                    <td class="value value2">
                        <?php echo $form->input('flag', array('options'=>(array('1'=> __('True',true),'0'=> __('False',true))),'name'=>'data[Syspri][flag]','label'=>false ,'div'=>false,'type'=>'select'));?>
                    </td>
                </tr>
                <tr>
                    <td class="align_right"><?php echo __('Module List URL',true);?> </td>
                    <td class="value value2">
                        <?php echo $form->input('pri_url',
                            array('label'=>false ,'div'=>false,'type'=>'text','class'=>'input in-text validate[require]','maxLength'=>'200'));?>

                    </td>
                </tr>
                </tbody>
            </table>
            </fieldset>
            <?php  if ($_SESSION['role_menu']['Configuration']['syspris']['model_w']) {?>
                <div id="form_footer" class="button-groups center separator">
                    <input type="hidden" name="data[Syspri][id]" value="<?php echo $id;?>" />
                    <input type="submit" class="btn btn-primary" value="<?php echo __('submit')?>" />
                    <input type="reset"  value="<?php __('Reset')?>" class="btn btn-default" />
                </div>
            <?php }?>
            <?php echo $form->end();?> </div>
    </div>
</div>