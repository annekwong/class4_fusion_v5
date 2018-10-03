 <table >
    <thead>
    <tr>
        <th rowspan="2"><?php __('Date')?></th>
        <th colspan="4"><?php __('Ingress')?></th>
        <th colspan="4"><?php __('Egress')?></th>
        <th rowspan="2"><?php __('Balance')?></th>
    </tr>
    <tr>
        <th><?php __('Invoice Sent')?></th>
        <th><?php __('Payment Received')?></th>
        <th><?php __('Credit Note Sent')?></th>
        <th><?php __('Debit Note Sent')?></th>
        <th><?php __('Invoice Received')?></th>
        <th><?php __('Payment Sent')?></th>
        <th><?php __('Credit Note Received')?></th>
        <th><?php __('Debit Note Received')?></th>
    </tr>
    </thead>

    <tbody>
    <?php foreach($financehistories as $financehistory): ?>
        <tr>
            <td><?php echo isset($financehistory['FinanceHistory']['date']) ? $financehistory['FinanceHistory']['date'] : '';  ?></td>
            <td><?php echo isset($financehistory['FinanceHistory']['invoice_set']) ? $financehistory['FinanceHistory']['invoice_set'] : '';  ?></td>
            <td><?php echo isset($financehistory['FinanceHistory']['payment_received']) ? $financehistory['FinanceHistory']['payment_received'] : '';  ?></td>
            <td><?php echo isset($financehistory['FinanceHistory']['credit_note_sent']) ? $financehistory['FinanceHistory']['credit_note_sent'] : '';  ?></td>
            <td><?php echo isset($financehistory['FinanceHistory']['debit_note_sent']) ? $financehistory['FinanceHistory']['debit_note_sent'] : '';  ?></td>
            <td><?php echo isset($financehistory['FinanceHistory']['invoice_received']) ? $financehistory['FinanceHistory']['invoice_received'] : '';  ?></td>
            <td><?php echo isset($financehistory['FinanceHistory']['payment_sent']) ? $financehistory['FinanceHistory']['payment_sent'] : '';  ?></td>
            <td><?php echo isset($financehistory['FinanceHistory']['credit_note_received']) ? $financehistory['FinanceHistory']['credit_note_received'] : '';  ?></td>
            <td><?php echo isset($financehistory['FinanceHistory']['debit_note_received']) ? $financehistory['FinanceHistory']['debit_note_received'] : '';  ?></td>
            <td>
                <?php echo (isset($financehistory['FinanceHistory']['mutual_balance']) && $financehistory['FinanceHistory']['mutual_balance']) ? $financehistory['FinanceHistory']['mutual_balance'] : number_format(0, 5); ?>
            </td>
        </tr>
    <?php endforeach; ?>
    <?php if (count($type_sum)): ?>
        <tr>
            <td><?php __('Total')?>:</td>
            <td><?php echo $type_sum['invoice_set'] ?></td>
            <td><?php echo $type_sum['payment_received'] ?></td>
            <td><?php echo $type_sum['credit_note_sent'] ?></td>
            <td><?php echo $type_sum['debit_note_sent'] ?></td>
            <td><?php echo $type_sum['invoice_received'] ?></td>
            <td><?php echo $type_sum['payment_sent'] ?></td>
            <td><?php echo $type_sum['credit_note_received'] ?></td>
            <td><?php echo $type_sum['debit_note_received'] ?></td>
            <td></td>
        </tr>
    <?php endif; ?>
    </tbody>
</table>