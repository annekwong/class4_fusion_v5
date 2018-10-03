<style>
    table.table tr td input.btn-primary{margin-top:25px}
</style>
<?php echo $this->element("bills/title")?>
<div class="innerLR">

    <div class="widget widget-heading-simple widget-body-white">
        <div class="widget-body">
            <div class="filter-bar">
                <form method="get">
                    <!-- Filter -->
                    <div>
                        <label><?php __('Search')?>:</label>
                        <input type="text" name="search" value="Search" title="<?php __('Search')?>" class="in-search default-value input in-text defaultText in-input" id="search-_q">
                    </div>
                    <!-- // Filter END -->
                    <!-- Filter -->
                    <div>
                        <button name="submit" class="btn query_btn"><?php __('Query')?></button>
                    </div>
                    <!-- // Filter END -->
                </form>
            </div>
            

<?php
    $data =$p->getDataArray();
?>
<?php if (empty($data)) {?>
<?php echo $this->element('listEmpty')?>
<?php } else {?>
            <div class="clearfix"></div>
    <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
        <thead>
            <tr>
                <th><?php echo $appCommon->show_order('invoice_number', __('Invoice No.(Invoice Period)', true)) ?></th>
                <th><?php echo $appCommon->show_order('carrier_name', __('Carrier', true)) ?></th>
                <th><?php __('Direction'); ?></th>
                <th><?php echo $appCommon->show_order('invoice_time', __('Invoice Date', true)) ?></th>
                <th><?php echo $appCommon->show_order('due_date', __('Due Date', true)) ?></th>
                <th><?php echo $appCommon->show_order('total_amount', __('Invoice Amount', true)) ?></th>
                <th><?php echo $appCommon->show_order('pay_amount', __('Paid Amount', true)) ?></th>
                <th><?php echo $appCommon->show_order('credit_note', __('Credit Note', true)) ?></th>
                <th><?php echo $appCommon->show_order('debit_note', __('Debit Note', true)) ?></th>
                <th><?php __('Remaining Amount'); ?></th>
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
                <td><?php echo number_format($item[0]['total_amount'], 2); ?></td>
                <td><?php echo number_format($item[0]['pay_amount'], 2); ?></td>
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
    <div class="row-fluid">
                <div class="pagination pagination-large pagination-right margin-none">
                    <?php echo $this->element('page'); ?>
                </div> 
            </div>
            <div class="clearfix"></div>
<?php }?>

<fieldset style=" clear:both;overflow:hidden;margin-top:10px;" class="query-box">
  <h4 class="heading glyphicons search"><i></i> <?php __('Search')?></h4>
<?php echo $this->element("bills/search")?>
</fieldset>

</div>
    </div>
</div>