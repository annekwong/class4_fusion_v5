<?php
    $data =$p->getDataArray();
?>
"<?php __('Invoice No.(Invoice Period)'); ?>","<?php __('Carrier'); ?>","<?php __('Direction'); ?>","<?php __('Invoice Date'); ?>","<?php __('Due Date'); ?>","<?php __('Invoice Amount'); ?>","<?php __('Paid Amount'); ?>","<?php __('Credit Note'); ?>","<?php __('Debit Note'); ?>","<?php __('Remaining Amount'); ?>",<?php echo "\r\n"; ?>
<?php foreach($data as $item): ?>
"<?php printf("%s  (%s ~ %s)", $item[0]['invoice_number'], $item[0]['invoice_start'], $item[0]['invoice_end']); ?>","<?php echo $item[0]['carrier_name']; ?>","<?php echo $item[0]['type'] == '1' ? 'Received' : 'Sent'; ?>","<?php echo $item[0]['invoice_time']; ?>","<?php echo $item[0]['due_date']; ?>","<?php echo $item[0]['total_amount']; ?>","<?php echo $item[0]['pay_amount']; ?>","<?php echo $item[0]['credit_note']; ?>","<?php echo $item[0]['debit_note']; ?>","<?php  echo round($item[0]['total_amount'] - $item[0]['credit_note']  + $item[0]['debit_note'] - $item[0]['payment'], 2);?>",<?php echo "\r\n"; ?>
<?php endforeach; ?>
