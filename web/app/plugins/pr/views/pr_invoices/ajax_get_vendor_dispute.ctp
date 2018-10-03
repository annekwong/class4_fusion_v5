<?php if (!count($this->data)): ?>
    <div class="msg center">
        <br />
        <h2>
            <?php echo __('no data found', true); ?>
        </h2>
    </div>
<?php else: ?>
    <table class="table">
        <tr>
            <th>
                <a href="javascript:void(0)" onclick="$('a.show_dispute').click();">
                    <i class="icon-plus icon-minus" style="margin-right: 20px"></i>
                </a>
            </th>
            <th><?php __('Sent On')?></th>
            <th><?php __('Sent By')?></th>
            <th><?php __('Disputed Amount')?></th>
            <th><?php __('Credit Amount')?></th>
            <th><?php __('Credit Note')?></th>
            <th><?php __('action')?></th>
        </tr>
        <?php foreach($this->data as $item): ?>
            <tr>
                <td>--</td>
                <td><?php echo $item['VendorInvoiceDispute']['create_on'] ?></td>
                <td><?php echo $item['VendorInvoiceDispute']['create_by'] ?></td>
                <td><?php echo $item['VendorInvoiceDispute']['dispute'] ?></td>
                <td class="credit_value"><?php echo $item['VendorInvoiceDispute']['credit'] ?></td>
                <td class="credit_note"><?php echo $item['VendorInvoiceDispute']['credit_note'] ?></td>
                <td>
                    <a href="#MyModal_submitCredit" class="submit_credit"
                       data-vendor="<?php echo $item['VendorInvoiceDispute']['vendor_invoice_id'] ?>"
                       data-value="<?php echo $item['VendorInvoiceDispute']['id'] ?>" data-toggle="modal"
                       title="<?php __('Submit Credit Note'); ?>" >
                        <i class="icon-plus-sign"></i></a>
                </td>
            </tr>
        <?php endforeach;?>
    </table>
<?php endif;?>