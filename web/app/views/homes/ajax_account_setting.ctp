
<table class="table table-bordered">
    <tr>
        <td class="align_right width50"><?php echo __('Main e-mail') ?>:</td>
        <td>
             <?php echo $form->input('email', array('id' => 'email', 'label' => false, 'div' => false, 'class' => 'validate[custom[email]]', 'value' => $client['email'])) ?>
        </td>
    </tr>
    <tr>
        <td class="align_right width50"><?php echo __('NOC e-mail') ?>:</td>
        <td>
                                <?php echo $form->input('noc_email', array('label' => false, 'div' => false, 'class' => 'validate[custom[email]]', 'value' => $client['noc_email'])) ?>
        </td>
    </tr>
    <tr>
        <td class="align_right width50"><?php echo __('Billing e-mail') ?>:</td>
        <td>
                                <?php echo $form->input('billing_email', array('label' => false, 'div' => false, 'class' => 'validate[custom[email]]', 'value' => $client['billing_email'])) ?>
        </td>
    </tr>
    <tr>
        <td class="align_right width50"><?php echo __('Rates e-mail') ?>:</td>
        <td>
                                    <?php echo $form->input('rate_email', array('label' => false, 'div' => false, 'class' => 'validate[custom[email]]', 'value' => $client['rate_email'])) ?>
        </td>
    </tr>
    <tr>
        <td class="align_right width50"><?php echo __('Rate Delivery e-mail') ?>:</td>
        <td>
                              <?php echo $form->input('rate_delivery_email', array('label' => false, 'div' => false, 'maxLength' => '100', 'class' => 'validate[custom[email]]', 'value' => $client['rate_delivery_email'])) ?>
        </td>
    </tr>

</table>
