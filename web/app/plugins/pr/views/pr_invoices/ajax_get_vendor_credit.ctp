
<table class="table">
    <tr>
        <td class="align_right padding-r20"><?php __('Credit Amount'); ?>:</td>
        <td>
            <?php echo $form->input('credit_value',array('div' => false,'label' => false,
                'class' => 'validate[required,custom[number],max['.$this->data['VendorInvoiceDispute']['dispute'].']] credit_value','value' => $this->data['VendorInvoiceDispute']['credit'])); ?>
        </td>
    </tr>
    <tr>
        <td class="align_right padding-r20"><?php __('Credit Note'); ?>:</td>
        <td>
            <?php echo $form->input('credit_note',array('div' => false,'label' => false,'type' => 'textarea',
                'value' => $this->data['VendorInvoiceDispute']['credit_note'],'class' =>'credit_note')); ?>
        </td>
    </tr>
</table>
