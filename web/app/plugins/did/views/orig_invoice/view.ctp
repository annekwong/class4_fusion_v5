
<ul class="breadcrumb">
    <li><?php __('You are here'); ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>did/orig_invoice/view">
            <?php if($_SESSION['login_type'] == 3):?>
                <?php __('Client Portal') ?>
            <?php else:?>
            <?php __('DID') ?></a></li>
    <?php endif;?>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>did/orig_invoice/view">
            <?php echo __('Origination Invoice', true); ?></a></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Origination Invoice', true); ?></h4>
</div>
<div class="clearfix"></div>
<div class="separator bottom"></div>
<div class="buttons pull-right" style="padding:0 15px 10px 0;">
    <?php
    if (isset($_SESSION['role_menu']['Finance']['pr_invoices']['model_w']) && $_SESSION['role_menu']['Finance']['pr_invoices']['model_w'])
    {
        ?>
        <?php
        if ($create_type == '1')
        {
            ?>
            <a class="link_btn btn btn-primary btn-icon glyphicons circle_plus" href="<?php echo $this->webroot ?>did/orig_invoice/add/<?php echo $create_type; ?>"><i></i><?php __('Create New')?> </a>
            <?php
        }
    }
    ?>
</div>
<div class="clearfix"></div>
<div class="separator bottom"></div>
<div class="innerLR">
    <div class="widget widget-tabs widget-body-white">
        <?php if ($_SESSION['login_type'] == '1'):?>
            <div class="widget-head">
                <ul>
                    <li <?php
                    if ($create_type == '0')
                    {
                        echo "class='active'";
                    }
                    ?> ><a class="glyphicons no-js left_arrow" href="<?php echo $this->webroot ?>did/orig_invoice/view/0"><i></i><?php echo __('Auto Invoice') ?></a></li>
                    <li <?php
                    if ($create_type == '1')
                    {
                        echo "class='active'";
                    }
                    ?> ><a class="glyphicons no-js right_arrow" href="<?php echo $this->webroot ?>did/orig_invoice/view/1"><i></i><?php echo __('Manual Invoice') ?></a></li>
                </ul>
            </div>
        <?php endif; ?>
        <div class="widget-body">
            <div class="filter-bar">
                <form action="" method="get" id="search_panel"  >
                    <div>
                        <label><?php echo __('Carriers', true); ?></label>
                        <select name="query[client]" class="select2">
                            <option value=""><?php __('All')?></option>
                            <?php foreach ($clients as $client_id => $name): ?>
                                <option <?php if (isset($_GET['query']['client']) && $_GET['query']['client'] == $client_id) echo 'selected="selected"'; ?> value="<?php echo $client_id ?>"><?php echo $name ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label><?php echo __('Invoice Date', true); ?>:</label>
                        <input type="text" class="input in-text wdate" name="invoice_start" value="<?php echo isset($start_time) ? $start_time:  ''; ?>" id="start_date" onfocus="WdatePicker({dateFmt: 'yyyy-MM-dd HH:mm:ss'});" readonly="">
                        <input type="text" class="wdate input in-text" name="invoice_end"  value="<?php echo isset($end_time) ? $end_time : ''; ?>" id="end_date" onfocus="WdatePicker({dateFmt: 'yyyy-MM-dd HH:mm:ss'});" readonly="">
                    </div>
                    <div>
                        <button  class="btn query_btn" id="search_button"><?php __('Query')?></button>
                    </div>
                </form>
            </div>
            <div class="clearfix"></div>
            <?php if (count($this->data) > 0): ?>
                <table class="list footable table table-striped tableTools table-bordered  table-white table-primary dynamicTable">
                    <thead>
                    <tr>
                        <th><?php __('Invoice Number') ?></th>
                        <th><?php __('Period') ?></th>
                        <th><?php __('Client Name') ?></th>
                        <th><?php __('Invoice Amount') ?></th>
                        <th class="last"><?php __('Action')?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($this->data as $item): ?>
                        <tr>
                            <td><?php echo $item['OrigInvoice']['invoice_number'] ?></td>
                            <td><?php echo $item['OrigInvoice']['invoice_start'] ?><br><?php echo $item['OrigInvoice']['invoice_end'] ?></td>
                            <td><?php echo $item['Client']['name'] ?></td>
                            <td><?php echo $item['OrigInvoice']['total_amount'] ?></td>
                            <td>
                                <?php if ($item['OrigInvoice']['state'] != -1): ?>
                                    <a title="<?php __('re-generate')?>" class="re_generate" href="<?php echo $this->webroot ?>did/orig_invoice/regenerate/<?php echo $item['OrigInvoice']['invoice_id'] . '/' . $create_type; ?>" >
                                        <i class="icon-play-circle"></i>
                                    </a>

                                    <?php if ($item['OrigInvoice']['state'] == 1 || $item['OrigInvoice']['state'] == 9): ?>
                                        <a class="send_invoice_mail" href="javascript:void(0)" data-invoice-id="<?php echo $item['OrigInvoice']['invoice_id']; ?>" data-invoice-type="<?php echo $create_type; ?>" title="<?php echo $item['OrigInvoice']['state'] == 9 ? 'Resend' : 'Sent'?>">
                                            <i class="<?php echo $item['OrigInvoice']['state'] == 9 ? 'icon-share-alt' : 'icon-share'; ?>"></i>
                                        </a>
                                    <?php endif; ?>

                                    <a href="<?php echo $this->webroot ?>did/orig_invoice/void_invoice/<?php echo $item['OrigInvoice']['invoice_id']; ?>/<?php echo $this->params['pass'][0]; ?>" title="<?php __('Void')?>">
                                        <i class="icon-stop"></i>
                                    </a>
                                    <?php if (!empty($item['OrigInvoice']['pdf_path'])): ?>
                                        <a title="<?php echo __('download') ?>" href="<?php echo $this->webroot . 'did/orig_invoice/createpdf_invoice/' . $item['OrigInvoice']['invoice_number'] ?>" >
                                            <i class="icon-file-text"></i>
                                        </a>
                                    <?php endif; ?>
                                <?php endif; ?>

                                <?php if (isset($_SESSION['role_menu']['Payment_Invoice']['delete_invoice']) && $_SESSION['role_menu']['Payment_Invoice']['delete_invoice'] == 1): ?>
                                    <a title="<?php __('Delete')?>" onclick="myconfirm('<?php __('sure to delete') ?>',this);return false;" href="<?php echo $this->webroot ?>did/orig_invoice/delete_invoice/<?php echo $item['OrigInvoice']['invoice_id']; ?>/<?php echo $create_type ?>">
                                        <i class="icon-remove"></i>
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>

            <?php else: ?>

            <?php endif; ?>
            <?php if(count($this->data)): ?>
            <div class="separator row-fluid">
                <div class="pagination pagination-large pagination-right margin-none">
                    <?php echo $this->element('xpage'); ?>
                </div>
            </div>
            <?php endif; ?>

        </div>
    </div>
</div>
<script type="text/javascript">
    $(function () {
        var $send_invoice_mail = $('.send_invoice_mail');
        $send_invoice_mail.click(function() {
            var $this = $(this);
            if (!$('#dd').length) {
                $(document.body).append("<div id='dd'></div>");
            }
            var $dd = $('#dd');
            var $form = null;
            $dd.load('<?php echo $this->webroot; ?>did/orig_invoice/send_invoice_mail/' + $this.attr('data-invoice-id') + '/' + $this.attr('data-invoice-id'),
                {},
                function(responseText, textStatus, XMLHttpRequest) {
                    $dd.dialog({
                        'title': 'Resend Invoice',
                        'width': '450px',
                        'height': 200,
                        'buttons': [{text: "Submit", "class": "btn btn-primary", click: function() {
                            $form = $("#send_email_id");
                            $form.submit();
                        }}, {text: "Cancel", "class": "btn btn-inverse", click: function() {
                            $(this).dialog("close");
                        }}],
                        'create': function(event, ui) {
                            $form = $('form', $dd);
                            $form.validationEngine();
                        }
                    });
                }
            );
            return false;
        });
    });

</script >
