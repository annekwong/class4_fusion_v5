<table class="table  table-bordered  table-white">
    <thead></thead>
    <tr>
        <td class="align_right padding-r20"><?php echo __('User Name', true) ?> </td>
        <td>
            <?php echo $form->input('login', array('label' => false, 'div' => false, 'class' => 'validate[required]',
                'type' => 'text', 'maxLength' => '256','autocomplete' => 'off')) ?>
            <input  style="display: none;"/>
        </td>
        <td class="align_right padding-r20"><?php echo __('New Password', true) ?> </td>
        <td> <?php echo $form->input('password', array('label' => false, 'div' => false, 'type' => 'password', 'maxLength' => '16')); ?></td>
    </tr>
    <tr>
        <td class="align_right padding-r20"><?php echo __('Permission') ?> </td>
        <td class="value" colspan="3">
            <?php echo $this->element('portal/add_permission_div'); ?>
        </td>
    </tr>
</table>