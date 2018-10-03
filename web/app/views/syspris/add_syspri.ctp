<style>
    input{width: 220px;}
</style>
<?php $module_id=array_keys_value($this->params,'pass.0')?>

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

            <?php echo $form->create ('Syspri', array ('action' => 'add_syspri' ));?>

            <table class="form table dynamicTable tableTools table-bordered  table-white">
                <colgroup>
                    <col width="40%">
                    <col width="60%">
                </colgroup>
                <tbody>
                <tr>
                    <td class="align_right"><?php echo __('Parent Module',true);?> </td>
                    <td class="value value2">
                        <?php echo $form->input('flag', array('options'=>$modules, 'name'=>'data[Syspri][module_id]','label'=>false ,'div'=>false,'type'=>'select', 'value'=>base64_decode(array_keys_value($this->params,'pass.0'))));?>
                    </td>
                </tr>
                <tr>
                    <td class="align_right"><?php echo __('Privilege Name',true);?>* </td>
                    <td class="value value2"><?php echo $form->input('pri_name',
                            array('label'=>false ,'div'=>false,'type'=>'text','class'=>'input in-text validate[required]','maxLength'=>'256'));?></td>
                </tr>
                <tr>
                    <td class="align_right"><?php echo __('Privilege List Value',true);?>* </td>
                    <td class="value value2"><?php echo $form->input('pri_val',
                            array('label'=>false ,'div'=>false,'type'=>'text','class'=>'input in-text validate[required]','maxLength'=>'100'));?></td>
                </tr>
                <tr>
                    <td class="align_right"><?php echo __('Flag',true);?> </td>
                    <td class="value value2">
                        <?php echo $form->input('flag', array('options'=>(array('t'=> __('True',true),'f'=> __('False',true))),'name'=>'data[Syspri][flag]','label'=>false ,'div'=>false,'type'=>'select'));?>
                    </td>
                </tr>
                <tr>
                    <td class="align_right"><?php echo __('Module List Url',true);?>* </td>
                    <td class="value value2">
                        <?php echo $form->input('pri_url',
                            array('label'=>false ,'div'=>false,'type'=>'text','class'=>'input in-text validate[required]','maxLength'=>'200'));?>

                    </td>
                </tr>
                </tbody>
            </table>
            </fieldset>
            <div id="form_footer" class="button-groups center separator"><!--
  <input type="hidden" name="data[Syspri][module_id]" value="<?php echo $module_id;?>" />
--><input type="hidden" name="data[Syspri][id]" value="" />
<!--                <input type="submit" class="btn btn-primary" value="--><?php //echo __('submit')?><!--" />-->
                <?php echo $this->element('common/submit_div',array('div' => false)); ?>
            </div>
            <?php echo $form->end();?> </div>
    </div>
</div>
