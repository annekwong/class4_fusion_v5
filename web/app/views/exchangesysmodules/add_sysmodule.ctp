<style>
    .list1 td{ line-height:2;}
</style>
<ul class="breadcrumb">
    <li>You are here</li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Exchange Manage') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Add Module') ?></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading">Add Module</h4>
    <div class="buttons pull-right">
        <?php echo $this->element('xback', Array('backUrl' => 'sysmodules/view_sysmodule')) ?>
    </div>
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>
<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-body">
                <?php $id = array_keys_value($this->params, 'pass.0') ?>
                <?php echo $form->create('Exchangesysmodule', array('action' => 'add_sysmodule')); ?>
                <table class="form list1 table dynamicTable tableTools table-bordered  table-white">
                    <tbody>
                        <tr>
                            <td width="50%" style="text-align:right;"><?php echo __('Module Name', true); ?>:</td>
                            <td width="50%"><?php echo $form->input('module_name', array('label' => false, 'div' => false, 'type' => 'text', 'class' => 'input in-text', 'maxLength' => '256'));
                ?></td>
                        </tr>
                        <tr>
                            <td width="50%" style="text-align:right;"><?php echo __('Order Number', true); ?>:</td>
                            <td width="50%" ><?php echo $form->input('order_num', array('label' => false, 'div' => false, 'type' => 'text', 'class' => 'input in-text'));
                ?>
                            </td>
                        </tr>

                        <tr>
                            <td width="50%" style="text-align:right;"><?php echo __('Type', true); ?>:</td>
                            <td width="50%" ><?php echo $form->input('type', array('options' => array('0' => 'Exchange', '1' => 'Agent', '2' => 'Partition'), 'label' => false, 'div' => false, 'type' => 'select', 'select' => 'true', 'class' => 'in-select'));
                ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
                </fieldset>
                <div id="form_footer" class="center">
                    <input type="hidden" name="data[Exchangesysmodule][id]" value="<?php echo $id; ?>" />
                    <input type="submit" value="<?php echo __('submit') ?>" class="btn btn-primary" />
                </div>
                <?php echo $form->end(); ?>     
        </div>
    </div>
</div>




