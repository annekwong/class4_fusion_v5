<form id="myform" action="<?php echo $this->webroot ?>finances/quickadd" method="post" class="form-inline">  
<input type="hidden" name="back_url" id="back_url" value="" />
<input type="hidden" name="client_id" value="<?php echo $client_id ?>" />
<div id="massadd_panel">
    <ul id="payment_panel_received" class="inline">
        <li>
            <h3>
                <a class="delete" href="###" title="Delete">
                    <i class="icon-remove"></i>
                </a>
                <span class="label">
                <?php __('Payment Received')?>
                </span>
            </h3>
            <input type="hidden" name="payment_receiveds[]" value="1" />
        </li>
        <li>
            <span><?php __('Payment Type')?></span>
            <select class="input in-select select payment_type" name="payment_received_types[]" style="width:100px;" >
                <option value="0"><?php __('Prepayment')?></option>
                <option value="1"><?php __('Invoice Payment')?></option>
            </select>
        </li>
        <li style="display:none;">
            <span><?php __('Invoice Number')?></span>
            <select class="input in-select select" name="payment_received_numbers[]" style="width:100px;">
                <?php foreach($incomings as $incoming): ?>
                <option value="<?php echo $incoming[0]['invoice_id']; ?>"><?php echo $incoming[0]['invoice_number']; ?></option>
                <?php endforeach; ?>
            </select>
        </li>
        <li>
            <span><?php __('Received At')?></span>
            <input type="text" class="input in-text in-input" name="payment_received_dates[]" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',lang:'en'})" style="width:100px;" />
        </li>
        <li>
            <span><?php __('Amount')?></span>
            <input type="text" class="input in-text in-input" name="payment_received_amounts[]" style="width:100px;" />
        </li>
    </ul>
    
    <ul id="payment_panel_sent" class="inline">
        <li>
            <h3>
                <a class="delete" href="###" title="Delete">
                    <i class="icon-remove"></i>
                </a>
                <span class="label">
                <?php __('Payment Sent')?>
                </span>
            </h3>
            <input type="hidden" name="payment_sents[]" value="1" />
        </li>
        <li>
            <span><?php __('Payment Type')?></span>
            <select class="input in-select select payment_type" name="payment_sent_types[]" style="width:100px;" >
                <option value="0"><?php __('Prepayment')?></option>
                <option value="1"><?php __('Invoice Payment')?></option>
            </select>
        </li>
        <li style="display:none;">
            <span><?php __('Invoice Number')?></span>
            <select class="input in-select select" name="payment_sent_numbers[]" style="width:100px;">
                <?php foreach($outgoings as $outgoing): ?>
                <option value="<?php echo $outgoing[0]['invoice_id']; ?>"><?php echo $outgoing[0]['invoice_number']; ?></option>
                <?php endforeach; ?>
            </select>
        </li>
        <li>
            <span><?php __('Received At')?></span>
            <input type="text" class="input in-text in-input" name="payment_sent_dates[]" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',lang:'en'})" style="width:100px;" />
        </li>
        <li>
            <span><?php __('Amount')?></span>
            <input type="text" class="input in-text in-input" name="payment_sent_amounts[]" style="width:100px;" />
        </li>
    </ul>
    
    <ul id="invoice_panel" class="inline">
        <li>
            <h3>
                <a class="delete" href="###" title="Delete">
                    <i class="icon-remove"></i>
                </a>
                <span class="label">
                <?php __('Incoming Invoice')?>
                </span>
            </h3>
            <input type="hidden" name="incoming_invoices[]" value="1" />
        </li>
        <li>
            <span><?php __('Invoice Period')?></span>
            <input type="text" class="input in-text in-input" name="incoming_invoice_periods[]" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',lang:'en'})" value="<?php echo date("Y-m-d 00:00:00"); ?>" style="width:100px;" />
        </li>
        <li>
            <span><?php __('To')?></span>
            <input type="text" class="input in-text in-input" name="incoming_invoice_tos[]" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',lang:'en'})" value="<?php echo date("Y-m-d 23:59:59"); ?>" style="width:100px;" />
        </li>
        <li>
            <span><?php __('GMT')?></span>
            <select class="input in-select select" name="incoming_invoice_timezones[]" style="width:100px;" >
                <option value="-1200">GMT -12:00</option>
                <option value="-1100">GMT -11:00</option>
                <option value="-1000">GMT -10:00</option>
                <option value="-0900">GMT -09:00</option>
                <option value="-0800">GMT -08:00</option>
                <option value="-0700">GMT -07:00</option>
                <option value="-0600">GMT -06:00</option>
                <option value="-0500">GMT -05:00</option>
                <option value="-0400">GMT -04:00</option>
                <option value="-0300">GMT -03:00</option>
                <option value="-0200">GMT -02:00</option>
                <option value="-0100">GMT -01:00</option>
                <option value="+0000" selected="selected">GMT +00:00</option>
                <option value="+0100">GMT +01:00</option>
                <option value="+0200">GMT +02:00</option>
                <option value="+0300">GMT +03:00</option>
                <option value="+0330">GMT +03:30</option>
                <option value="+0400">GMT +04:00</option>
                <option value="+0500">GMT +05:00</option>
                <option value="+0600">GMT +06:00</option>
                <option value="+0700">GMT +07:00</option>
                <option value="+0800">GMT +08:00</option>
                <option value="+0900">GMT +09:00</option>
                <option value="+1000">GMT +10:00</option>
                <option value="+1100">GMT +11:00</option>
                <option value="+1200">GMT +12:00</option>
            </select>
        </li>
        <li style="display:block;height:20px;"></li>
        <li>
            <span><?php __('Invoice Date')?></span>
            <input type="text" class="input in-text in-input" name="incoming_invoice_dates[]" onclick="WdatePicker({dateFmt:'yyyy-MM-dd',lang:'en'})"  style="width:100px;" />
        </li>
        <li>
            <span><?php __('Due Date')?></span>
            <input type="text" class="input in-text in-input" name="incoming_invoice_due_dates[]" onclick="WdatePicker({dateFmt:'yyyy-MM-dd',lang:'en'})"  style="width:100px;" />
        </li>
        <li>
            <span><?php __('Amount')?></span>
            <input type="text" class="input in-text in-input" name="incoming_invoice_amounts[]" style="width:100px;" />
        </li>
    </ul>
</div>
</form>

