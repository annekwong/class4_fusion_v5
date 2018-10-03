<div id="templates">
    <legend><span id="ht-100011"><?php echo __('invoiceemailtemp') ?></span>»</legend>
    <form action="" id="post_invoice" >
        <div id="mail_invoice" >
            <table class="form table dynamicTable tableTools table-bordered  table-white">
                <colgroup>
                    <col width="10%">
                    <col width="90%">
                </colgroup>

                <tbody>
                    <tr>

                        <td class="align_right"><?php __('From email')?> </td>
                        <td>
                            <select name="invoice_from">
                                <option <?php if (empty($tmp[0][0]['invoice_from'])) echo 'selected="selected"' ?>><?php __('Default')?></option>
                                <?php foreach ($mail_senders as $mail_sender): ?>
                                    <option value="<?php echo $mail_sender[0]['id'] ?>" <?php if ($mail_sender[0]['id'] == $tmp[0][0]['invoice_from']) echo 'selected="selected"' ?>><?php echo $mail_sender[0]['email'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td class="align_right"><?php __('Cc')?> </td>
                        <td>
                            <input type="text" name="invoice_cc" id="invoice_cc" value="<?php echo!empty($tmp[0][0]['invoice_cc']) ? $tmp[0][0]['invoice_cc'] : ''; ?>">
                        </td>
                    </tr>
                    <tr>
                        <td class="align_right"><?php echo __('subject') ?> </td>
                        <td>
                            <input class="input in-text" name="invoice_subject" value="<?php echo!empty($tmp[0][0]['invoice_subject']) ? $tmp[0][0]['invoice_subject'] : ''; ?>" id="invoice_subject" type="text" >
                            <p><?php __("{switch_alias},{company_name},{invoice_number}, {cdr_url}, {invoice_link},{start_date} and {end_date} can't change")?>.</p>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="align_right"><?php echo __('content') ?> </td>
                        <td><textarea class="input in-textarea wysihtml5" name="invoice_content" style="height: 100px; font-family: monospace; font-size: 12px;width:800px;" id="invoice_content"><?php echo!empty($tmp[0][0]['invoice_content']) ? $tmp[0][0]['invoice_content'] : ''; ?></textarea></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </form>
</div>
