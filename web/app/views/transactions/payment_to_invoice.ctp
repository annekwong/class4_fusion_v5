<div style="padding:10px;">
<form id="invoice_form" method="post" action="<?php echo $this->webroot ?>transactions/payment_to_invoice/<?php echo $payment_invoice_id; ?>/<?php echo $client_id; ?>/<?php echo $type; ?>">
<table id="invoice_list" class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
    <thead>
        <tr>
                <th><?php __('Invoice Number')?></th>
                <th><?php __('Invoice Amount')?></th>
                <th><?php __('Pay Amount')?></th>
                <th><?php __('Period')?></th>
                <th><?php __('Given Amount')?></th>
                <th><?php __('Action')?></th>
            </tr>
    </thead>
    <tbody>
        
    </tbody>
</table>
</form>    
</div>