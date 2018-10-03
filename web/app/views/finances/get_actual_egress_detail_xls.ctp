
    <table class="list">
            <tr>
                <td colspan="3"><?php __('Beginning Balance on')?> <?php echo $start_time; ?> 00:00:00 <?php __('is')?> <?php echo $begin_balance; ?></td>
                <td colspan="3"><?php __('Ending Balance on')?> <?php echo $end_time; ?> 23:59:59 <?php __('is')?> <?php echo $end_balance; ?></td>
            </tr>
            <tr>
                <td><?php __('Date')?></td>
                <td><?php __('Payment Sent')?></td>
                <td><?php __('Credit Note Received')?></td>
                <td><?php __('Debit Note Received')?></td>
                <td><?php __('Unbilled Outgoing Traffic')?></td>
                <td><?php __('Balance')?></td>
            </tr>
        
            <?php foreach($financehistories as $financehistory): ?>
            <tr>
                <td><?php echo $financehistory['FinanceHistoryActual']['date'];  ?></td>
                <td><?php echo $financehistory['FinanceHistoryActual']['payment_sent'];  ?></td>
                <td><?php echo $financehistory['FinanceHistoryActual']['credit_note_received'];  ?></td>
                <td><?php echo $financehistory['FinanceHistoryActual']['debit_note_received'];  ?></td>
                <td><?php echo $financehistory['FinanceHistoryActual']['unbilled_outgoing_traffic'];  ?></td>
                <td><?php echo $financehistory['FinanceHistoryActual']['actual_egress_balance'];  ?></td>
            </tr>
            <?php endforeach; ?>
    </table>