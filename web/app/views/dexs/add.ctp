
<ul class="breadcrumb">
    <li><?php __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Finance') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Domestic Exchange') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Add Exchange') ?></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading">Add Exchange</h4>
    
</div>

<?php //****************************************************页面主体?>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
        <a class="btn btn-default btn-icon glyphicons btn-inverse left_arrow" href="<?php echo $this->webroot ?>dexs/view">
            <i></i>
            Back
        </a>
    </div>
    <div class="clearfix"></div>
<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-body">
            <?php $id = array_keys_value($this->params, 'pass.0') ?>
            <form method="post" action="<?php echo $this->webroot; ?>dexs/add" id="DexAddForm">
                <table class="form footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
                    <tbody>
                        <tr>
                            <td><?php echo __('DEX Name', true); ?>:</td>
                            <td>
                                <input type="hidden" name="id" value="<?php if (!empty($p['id'])) echo $p['id']; ?>" >
                                <?php echo $form->input('dex_name', array('label' => false, 'div' => false, 'type' => 'text', 'value' => (empty($p['dex_name']) ? '' : $p['dex_name']), 'class' => 'input in-text validate[required,custom[onlyLetterNumberLine]]', 'maxLength' => '256'));
                                ?></td>
                        </tr>
                        <tr>
                            <td><?php echo __('Trunk', true); ?>:</td>
                            <td>
                                <?php echo $form->input('resource_alias', array('options' => $egress, 'name' => 'data[resource]', 'multiple' => 'multiple', 'label' => false, 'div' => false, 'type' => 'select', 'value' => '', 'style' => "width: 300px; height: 120px;")); ?>

                            </td>
                        </tr>
                        <tr>
                            <td><?php echo __('Prefix', true); ?>:</td>
                            <td><?php echo $form->input('dex_prefix', array('label' => false, 'div' => false, 'type' => 'text', 'value' => (empty($p['dex_prefix']) ? '' : $p['dex_prefix']), 'class' => 'input in-text validate[required,custom[onlyLetterNumberLine]]'));
                                ?></td>
                        </tr>
                    </tbody>
                </table>
                </fieldset>
                <div id="form_footer" class="center">
                    <input type="hidden" name="" value="" />
                    <input type="submit" value="<?php echo __('submit') ?>" class="btn btn-primary" />
                </div>
            </form>
        </div>
    </div>
</div>
