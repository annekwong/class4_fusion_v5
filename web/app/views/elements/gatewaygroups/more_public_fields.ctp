<tr>
    <td class="align_right padding-r10"><?php __('Use RPID as Caller ID') ?></td>
    <td>
        <?php
        isset($post['Gatewaygroup']['rate_use_rpid']) && $post['Gatewaygroup']['rate_use_rpid'] ? $au = 'true' : $au = 'false';
        echo $form->input('rate_use_rpid', array('options' => array('true' => __('True', true), 'false' => __('False', true)), 'label' => false, 'class' => 'select', 'div' => false, 'type' => 'select', 'selected' => $au));
        ?>
    </td>
</tr>