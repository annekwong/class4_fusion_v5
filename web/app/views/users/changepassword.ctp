<style>
    select, textarea, input[type="text"], input[type="password"]{margin-bottom: 0;}
</style>
<ul class="breadcrumb">
    <li><?php __('You are here'); ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Configuration') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('ChangePassword') ?></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('ChangePassword') ?></h4>
    <div class="buttons pull-right">

    </div>
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>


<div class="innerLR">

    <div class="widget widget-heading-simple widget-body-white">
        <div class="widget-body">
            <!-- DYNAMIC -->
            <?php echo $form->create('User', array('action' => 'changepassword')); ?>

            <table class="form table dynamicTable tableTools table-bordered  table-white">
                <tbody>
                    <tr>
                        <td class="align_right width50"><?php echo __('oldpassword') ?>:</td>
                        <td>
                            <?php echo $form->input('old', array('label' => false, 'div' => false, 'type' => 'password', 'class' => 'input in-password')); ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="align_right"><?php echo __('newpassword') ?>:</td>
                        <td>
                            <?php echo $form->input('new', array('label' => false, 'div' => false, 'type' => 'password', 'class' => 'input in-password')); ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="align_right"><?php echo __('confirmpassword') ?>:</td>
                        <td>

                            <?php echo $form->input('retype', array('label' => false, 'div' => false, 'type' => 'password', 'class' => 'input in-password')); ?>

                        </td>
                    </tr>
                </tbody></table>
            <div class="form-buttons button-groups center separator">
                <input type="submit" value="<?php __('submit') ?>" class="input in-submit btn btn-primary">
            </div>
            <!-- DYNAMIC -->
        </div>
    </div>
</div>