<?php echo $this->element("mutual_statements/title")?>
<div id="container">
<?php echo $this->element("mutual_statements/search")?>
<?php
    $data =$p->getDataArray();
?>
<?php if (empty($data)) {?>
<?php echo $this->element('listEmpty')?>
<?php } else {?>
    <div id="toppage"></div>
    <table class="list">
        <thead>
            <tr>
                <td><?php __('Invoice No.(Invoice Period)'); ?></td>
                <td><?php __('Carrier'); ?></td>
                <td><?php __('Direction'); ?></td>
                <td><?php __('Invoice Date'); ?></td>
                <td><?php __('Due Date'); ?></td>
                <td><?php __('Invoice Amount'); ?></td>
                <td><?php __('Paid Amount'); ?></td>
                <td><?php __('Credit Note'); ?></td>
                <td><?php __('Debit Note'); ?></td>
                <td><?php __('Remaining Amount'); ?></td>
            </tr>
        </thead>
        
        <tbody>
            <?php foreach($data as $item): ?>
            <tr>
                <td><?php printf("%s<br />(%s ~ %s)", $item[0]['invoice_number'], $item[0]['invoice_start'], $item[0]['invoice_end']); ?></td>
                <td><?php echo $item[0]['carrier_name']; ?></td>
                <td><?php echo $item[0]['type'] == '1' ? 'Received' : 'Sent'; ?></td>
                <td><?php echo $item[0]['invoice_time']; ?></td>
                <td><?php echo $item[0]['due_date']; ?></td>
                <td><?php echo $item[0]['total_amount']; ?></td>
                <td><?php echo $item[0]['pay_amount']; ?></td>
                <td><?php echo $item[0]['credit_note']; ?></td>
                <td><?php echo $item[0]['debit_note']; ?></td>
                <td>
                    <?php
                        echo round($item[0]['total_amount'] - $item[0]['credit_note']  + $item[0]['debit_note'] - $item[0]['payment'], 2);
                    ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div id="tmppage"> <?php echo $this->element('page');?> </div>
<?php }?>
</div>

<script type="text/javascript">
$(function() {
    $('#export_csv').click(function(event) {
        $('#is_download').val(1);     
        $('#sub-btn').click();
    });    
});
</script>