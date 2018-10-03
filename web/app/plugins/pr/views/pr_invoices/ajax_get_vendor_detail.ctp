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
            <th><?php __('Date')?></th>
            <th><?php __('Destination')?></th>
            <th><?php __('Minutes')?></th>
            <th><?php __('Non Zero')?></th>
            <th><?php __('Rate')?></th>
            <th><?php __('Total')?></th>
        </tr>
        <?php foreach($this->data as $item): ?>
            <tr>
                <td><?php echo $item['VendorInvoiceDetail']['report_date'] ?></td>
                <td><?php echo $item['VendorInvoiceDetail']['code_name'] ?></td>
                <td><?php echo $item['VendorInvoiceDetail']['mins'] ?></td>
                <td><?php echo $item['VendorInvoiceDetail']['non_zero_calls'] ?></td>
                <td><?php echo $item['VendorInvoiceDetail']['rate'] ?></td>
                <td><?php echo $item['VendorInvoiceDetail']['total_cost']; ?></td>
            </tr>
        <?php endforeach;?>
    </table>
<?php endif;?>