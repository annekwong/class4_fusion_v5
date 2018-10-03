<table class="list">
    <thead>
        <tr>
            <td colspan="6">Beginning Balance on <?php echo $start_time; ?> 00:00:00 is <?php echo $begin_balance; ?></td>
            <td colspan="7">Ending Balance on <?php if(isset($_GET['end_time']) && ($_GET['end_time'] < date('Y-m-d'))){echo $end_time." 23:59:59";}else{ echo date('Y-m-d H:i:s');} ?> 23:59:59 is <?php echo $end_balance; ?></td>
        </tr>
        <tr>
            <td></td>
            <td colspan="4"><?php __('Ingress')?></td>
            <td colspan="4"><?php __('Egress')?></td>
            <td></td>
        </tr>
        <tr>
            <td><?php __('Date')?>Date</td>
            <td><?php __('Payment Received')?></td>
            <td><?php __('Credit Note Sent')?></td>
            <td><?php __('Debit Note Sent')?></td>
            <td><?php __('Unbilled Incoming Traffic')?></td>
            <!--td><?php __('Short Charges')?></td-->
            <td><?php __('Reset')?></td>
            <td><?php __('Payment Sent')?></td>
            <td><?php __('Credit Note Received')?></td>
            <td><?php __('Debit Note Received')?></td>
            <td><?php __('Unbilled Outgoing Traffic')?></td>
            <td><?php __('Reset')?></td>
            <td><?php __('Balance')?></td>
        </tr>
    </thead>    

       <tbody>
        <?php foreach($financehistories as $financehistory): ?>
            <tr>
                <td><?php echo $financehistory['FinanceHistoryActual']['date'];  ?></td>
                <td><?php echo $financehistory['FinanceHistoryActual']['payment_received'] ?: '0.00000';  ?></td>
                <td><?php echo $financehistory['FinanceHistoryActual']['credit_note_sent'] ?: '0.00000';  ?></td>
                <td><?php echo $financehistory['FinanceHistoryActual']['debit_note_sent'] ?: '0.00000';  ?></td>
                <td><?php echo $financehistory['FinanceHistoryActual']['unbilled_incoming_traffic'] ?: '0.00000';  ?></td>
                <!--td><?php echo $financehistory['FinanceHistoryActual']['short_charges'] ?: '0.00000';  ?></td-->
                <td><?php echo $financehistory['FinanceHistoryActual']['payment_sent'] ?: '0.00000';  ?></td>
                <td><?php echo $financehistory['FinanceHistoryActual']['credit_note_received'] ?: '0.00000';  ?></td>
                <td><?php echo $financehistory['FinanceHistoryActual']['debit_note_received'] ?: '0.00000';  ?></td>
                <td><?php echo $financehistory['FinanceHistoryActual']['unbilled_outgoing_traffic'] ?: '0.00000';  ?></td>
                <td><?php echo $financehistory['FinanceHistoryActual']['actual_balance'];  ?></td>
            </tr>

            <?php endforeach; ?>
            <?php if (count($type_sum)): ?>
            <tr>
                <td><?php __('Total')?>:</td>
                <td><?php echo number_format($type_sum['payment_received'], 5) ?></td>
                <td><?php echo number_format($type_sum['credit_note_sent'], 5) ?></td>
                <td><?php echo number_format($type_sum['debit_note_sent'], 5) ?></td>
                <td><?php echo number_format($type_sum['unbilled_incoming_traffic'], 5) ?></td>
                <td><?php echo number_format($type_sum['payment_sent'], 5) ?></td>
                <td><?php echo number_format($type_sum['credit_note_received'], 5) ?></td>
                <td><?php echo number_format($type_sum['debit_note_received'], 5) ?></td>
                <td><?php echo number_format($type_sum['unbilled_outgoing_traffic'], 5) ?></td>
                <!--td><?php echo number_format($type_sum['short_charges'], 5) ?></td-->
                <td><?php echo ($end_balance) ? $end_balance : number_format($end_balance, 5); ?></td>
            </tr>
            <?php endif; ?>
    </tbody>
</table>