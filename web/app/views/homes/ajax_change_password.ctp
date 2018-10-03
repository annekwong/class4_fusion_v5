<table class="table table-bordered">
    <tr>
        <td class="align_right width50"><?php echo __('oldpassword') ?>:</td>
        <td>
            <?php echo $form->input('old', array('label' => false, 'div' => false, 'type' => 'password',
                'class' => 'input in-password validate[required]','id' =>'UserOld')); ?>
        </td>
    </tr>
    <tr>
        <td class="align_right"><?php echo __('newpassword') ?>:</td>
        <td>
            <?php echo $form->input('new', array('label' => false, 'div' => false, 'type' => 'password',
                'class' => 'input in-password validate[required]','id' => 'UserNew')); ?>
        </td>
    </tr>
    <tr>
        <td class="align_right"><?php echo __('confirmpassword') ?>:</td>
        <td>

            <?php echo $form->input('retype', array('label' => false, 'div' => false, 'type' => 'password',
                'class' => 'input in-password validate[required]','id' => 'UserRetype')); ?>
        </td>
    </tr>
</table>
