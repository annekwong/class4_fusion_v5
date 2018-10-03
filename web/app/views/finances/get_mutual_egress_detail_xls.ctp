
    <table class="list">
            <tr>
                <td colspan="3"><?php __('Beginning Balance on')?> <?php echo $start_time; ?> 00:00:00 <?php __('is')?> <?php echo $begin_balance; ?></td>
                <td colspan="3"><?php __('Ending Balance on')?> <?php echo $end_time; ?> 23:59:59 <?php __('is')?> <?php echo $end_balance; ?></td>
            </tr>
            <tr>
                <td><?php __('Date')?>Date</td>
                <td><?php __('Invoice Received')?></td>
                <td><?php __('Payment Sent')?></td>
                <td><?php __('Credit Note Received')?></td>
                <td><?php __('Debit Note Received')?></td>
                <td><?php __('Balance')?></td>
            </tr>
        
            <?php foreach($financehistories as $financehistory): ?>
            <tr>
                <td><?php echo $financehistory['FinanceHistory']['date'];  ?></td>
                <td><?php echo $financehistory['FinanceHistory']['invoice_received'];  ?></td>
                <td><?php echo $financehistory['FinanceHistory']['payment_sent'];  ?></td>
                <td><?php echo $financehistory['FinanceHistory']['credit_note_received'];  ?></td>
                <td><?php echo $financehistory['FinanceHistory']['debit_note_received'];  ?></td>
                <td><?php echo $financehistory['FinanceHistory']['mutual_egress_balance'];  ?></td>
            </tr>
            <?php endforeach; ?>
    </table>
