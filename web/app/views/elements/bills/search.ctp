<fieldset style="margin: 0pt 0pt 10px; width: 100%; display: block;" id="advsearch" class="title-block">
    <form method="get">
        <input type="hidden" value="1" name="adv_search"/>
        <table class="table table-condensed">
            <tbody>
                <tr>
                    <td>
                        <label><?php echo __('Filter Paid Type', true); ?>:</label>
                        <?php echo $xform->search('paid_type', array('options' => Array('1' => __('Partially Paid  Bills',true), '2' => __('Unpaid Bills',true)), 'empty' => __('All Unpaid & Partially Bills',true), 'selected' => array_keys_value($this->params, 'url.paid_type'))) ?>
                    </td> 	
                    <td>
                        <label><?php __('Carrier'); ?>:</label>
                        <?php echo $xform->search('carrier', array('options' => $appProduct->_get_select_options($ClientList, 'Client', 'client_id', 'name'), 'empty' => '', 'selected' => array_keys_value($this->params, 'url.carrier'))) ?>
                    </td>
                    <td>
                        <label><?php __('Type'); ?>:</label>
                        <?php echo $xform->search('direction', array('options' => Array('1' => __('Invoice Received',true), '2' => __('Invoice Sent',true)), 'empty' => '', 'selected' => array_keys_value($this->params, 'url.direction'))) ?>
                    </td>
                    <td>
                        <label><?php echo __('Invoice Date', true); ?>:</label>
                        <input class="input in-text wdate in-input" name="invoice_start_date" style="width: 100px;" id="invoice_start_date" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'});" readonly="readonly" type="text" value="<?php echo array_keys_value($this->params, 'url.invoice_start_date') ?>">
                        --
                        <input class="wdate input in-text in-input" name="invoice_end_date" style="width: 100px;" id="invoice_end_date" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'});" readonly="readonly" type="text" value="<?php echo array_keys_value($this->params, 'url.invoice_end_date') ?>">
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <td>
                        <label><?php echo __('Due Date', true); ?>:</label>
                        <input class="input in-text wdate in-input" name="due_start_date" style="width: 120px;" id="due_start_date" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'});" readonly="readonly" type="text" value="<?php echo array_keys_value($this->params, 'url.due_start_date') ?>">
                        --
                        <input class="wdate input in-text in-input" name="due_end_date" style="width: 120px;" id="due_end_date" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'});" readonly="readonly" type="text" value="<?php echo array_keys_value($this->params, 'url.due_end_date') ?>">
                    </td>
                    <td>
                        <label><?php echo __('Amt', true); ?>:</label>
                        <input class="input in-text in-input" name="start_amt" style="width: 100px;" id="due_start_date" type="text" value="<?php echo array_keys_value($this->params, 'url.start_amt') ?>">
                        --
                        <input class="input in-text in-input" name="end_amt" style="width: 100px;" id="due_end_date" type="text" value="<?php echo array_keys_value($this->params, 'url.end_amt') ?>">
                    </td>
                    <td>
                        <label><?php echo __('Past Due for', true); ?>:</label>
                        <input class="input in-text in-input" name="start_past_due" style="width: 100px;" id="start_past_due" type="text" value="<?php echo array_keys_value($this->params, 'url.start_past_due') ?>">
                         --
                        <input class="input in-text in-input" name="end_past_due" style="width: 100px;" id="end_past_due" type="text" value="<?php echo array_keys_value($this->params, 'url.end_past_due') ?>">
                        <?php __('days')?>
                    </td>

                    <td>
                        <label><?php echo __('Unpaid Amount', true); ?>:</label>
                        <input class="input in-text in-input" name="start_unpaid_amt" style="width: 100px;" id="start_unpaid_amt" type="text" value="<?php echo array_keys_value($this->params, 'url.start_unpaid_amt') ?>">
                         --
                        <input class="input in-text in-input" name="end_unpaid_amt" style="width: 100px;" id="end_unpaid_amt" type="text" value="<?php echo array_keys_value($this->params, 'url.end_unpaid_amt') ?>">
                    </td>
                    <td><input type="submit" class="input in-submit btn btn-primary" value="<?php echo __('submit', true); ?>"/></td>
                </tr>
            </tbody>
        </table>
    </form>
</fieldset>