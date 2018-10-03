<ul class="breadcrumb">
    <li><?php __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?= $this->webroot?>clients"><?php __('Finance') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?= $this->webroot?>clients">
        <?php __('Overall Mutual Balance') ?>[<?php echo $client_name ?>]</a></li>
</ul>


<div class="heading-buttons">
    <h4 class="heading"><?php __('Overall Mutual Balance') ?>[<?php echo $client_name ?>]</h4>

    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>


<div class="innerLR">

    <div class="widget widget-heading-simple widget-body-white">
        <div class="widget-body">
            <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
                <thead>
                    <tr>
                        <th colspan="5"><?php __('Beginning Balance on')?> <?php echo isset($begin_time) ? $begin_time : ''; ?> 00:00:00 <?php __('is')?> <?php echo $begin_balance; ?></th>
                        <th colspan="7"><?php __('Ending Balance on')?> <?php echo $end_time; ?> 23:59:59 <?php __('is')?> <?php echo $end_balance; ?></th>
                    </tr>
                    <tr>
                        <th></th>
                        <th colspan="4"><?php __('Ingress')?></th>
                        <th colspan="4"><?php __('Egress')?></th>
                        <th></th>
                        <th></th>
                    </tr>
                    <tr>
                        <td><?php __('Date')?></td>
                        <td><?php __('Invoice Sent')?></td>
                        <td><?php __('Payment Received')?></td>
                        <td><?php __('Credit Note Sent')?></td>
                        <td><?php __('Debit Note Sent')?></td>
                        <td><?php __('Invoice Received')?></td>
                        <td><?php __('Payment Sent')?></td>
                        <td><?php __('Credit Note Received')?></td>
                        <td><?php __('Debit Note Received')?></td>
                        <td><?php __('Balance')?></td>
                        <td><?php __('Action')?></td>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($financehistories as $financehistory): ?>
                        <tr>
                             <td><?php echo isset($financehistory['FinanceHistory']['date']) ? $financehistory['FinanceHistory']['date'] : '';  ?></td>
                            <td><?php echo isset($financehistory['FinanceHistory']['invoice_set']) ? $financehistory['FinanceHistory']['invoice_set'] : '';  ?></td>
                            <td><?php echo isset($financehistory['FinanceHistory']['payment_received']) ? $financehistory['FinanceHistory']['payment_received'] : '';  ?></td>
                            <td><?php echo isset($financehistory['FinanceHistory']['credit_note_sent']) ? $financehistory['FinanceHistory']['credit_note_sent'] : '';  ?></td>
                            <td><?php echo isset($financehistory['FinanceHistory']['debit_note_sent']) ? $financehistory['FinanceHistory']['debit_note_sent'] : '';  ?></td>
                            <td><?php echo isset($financehistory['FinanceHistory']['invoice_received']) ? $financehistory['FinanceHistory']['invoice_received'] : '';  ?></td>
                            <td><?php echo isset($financehistory['FinanceHistory']['payment_sent']) ? $financehistory['FinanceHistory']['payment_sent'] : '';  ?></td>
                            <td><?php echo isset($financehistory['FinanceHistory']['credit_note_received']) ? $financehistory['FinanceHistory']['credit_note_received'] : '';  ?></td>
                            <td><?php echo isset($financehistory['FinanceHistory']['debit_note_received']) ? $financehistory['FinanceHistory']['debit_note_received'] : '';  ?></td>
                            <td>
                                <?php if ($_SESSION['role_menu']['Payment_Invoice']['reset_balance'] == 1): ?>
                                    <a control="<?php echo isset($financehistory['FinanceHistory']['id']) ? $financehistory['FinanceHistory']['id'] : 0; ?>" href="###" class="synchronize" title="Synchronize with Actual Balance">
                                        <i class="icon-refresh"></i>
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (count($type_sum)): ?>
                        <tr>
                            <td><?php __('Total')?>:</td>
                            <td><?php echo isset($type_sum['invoice_set']) ? $type_sum['invoice_set']: '' ?></td>
                            <td><?php echo isset($type_sum['payment_received']) ? $type_sum['payment_received'] : '' ?></td>
                            <td><?php echo isset($type_sum['credit_note_sent']) ? $type_sum['credit_note_sent'] : '' ?></td>
                            <td><?php echo isset($type_sum['debit_note_sent']) ? $type_sum['debit_note_sent'] : '' ?></td>
                            <td><?php echo isset($type_sum['invoice_received']) ? $type_sum['invoice_received'] : '' ?></td>
                            <td><?php echo isset($type_sum['payment_sent']) ? $type_sum['payment_sent']: '' ?></td>
                            <td><?php echo isset($type_sum['credit_note_received']) ? $type_sum['credit_note_received'] : '' ?></td>
                            <td><?php echo isset($type_sum['debit_note_received']) ? $type_sum['debit_note_received'] : '' ?></td>
                            <td></td>
                            <td></td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            <?php if (count($financehistories) >= 100)
            {
                ?>
                <a href="<?php echo $this->webroot; ?>clients/get_mutual_ingress_egress_detail/<?php echo $this->params['pass'][0] ?>?export=1" title="Download" class="input in-submit btn btn-primary">
                    <i></i> <?php __('Download'); ?>
                </a>
<?php } ?>
            <div class="center">
                <form method="post" action="<?php echo $this->webroot; ?>clients/reset_balance/<?php echo $client_id; ?>">
                    <input type="hidden" name="balance" value="<?php echo isset($balance) ? $balance : ''; ?>" />
                    <input type="hidden" name="begin_time" value="<?php echo isset($begin_time) ? $begin_time : ''; ?>" />
                    <input type="hidden" name="description" value="<?php echo isset($description) ? $description : ''; ?>" />
                    <input type="submit" value="<?php __('Confirm')?>" class="input in-submit btn btn-primary" />

                </form>
            </div>
        </div>
    </div>
</div>

<div id="dd"> </div> 
<link rel="stylesheet" type="text/css" href="<?php echo $this->webroot ?>easyui/themes/default/easyui.css">
<link rel="stylesheet" type="text/css" href="<?php echo $this->webroot ?>easyui/themes/icon.css">
<script type="text/javascript" src="<?php echo $this->webroot ?>easyui/jquery.easyui.min.js"></script>

<script>
    var $dd = $('#dd');
    var $massadd = $('#massadd');
    var $regenerate = $('#regenerate');
    var $synchronize = $('.synchronize');

    $regenerate.click(function() {
        $.post("<?php echo $this->webroot ?>finances/regenerate/<?php echo $client_id; ?>/1", function(data) {
            jGrowl_to_notyfy('Succeeded!', {theme: 'jmsg-success'});
            window.setTimeout("window.location.reload()", 3000);
        }, 'json');
    });


    $(function() {

        $synchronize.click(function() {
            var $this = $(this);
            var control = $this.attr('control');

            $dd.dialogui({
                title: 'Synchronize',
                width: 300,
                height: 200,
                closed: false,
                cache: false,
                resizable: true,
                href: '<?php echo $this->webroot ?>finances/synchronize/<?php echo base64_encode($client_id);  ?>/' + control,
                modal: true,
                buttons: [{
                        text: 'Save',
                        handler: function() {
                            $('#synchronize_form').submit();
                        }
                    }, {
                        text: 'Close',
                        handler: function() {
                            $dd.dialogui('close');
                        }
                    }]
            });

            $dd.dialogui('refresh', '<?php echo $this->webroot ?>finances/synchronize/<?php echo base64_encode($client_id); ?>/' + control);
        });


        $massadd.click(function() {

            var $delete = $('.delete');
            var $payment_panel_received = null;
            var $payment_panel_sent = null;
            var $invoice_panel = null;
            var $massadd_panel = null;
            var $payment_type = $('.payment_type');
            var $back_url = null;
            var $myform = null;

            $dd.dialogui({
                title: 'Mass Add',
                width: 960,
                height: 600,
                closed: false,
                cache: false,
                resizable: true,
                href: '<?php echo $this->webroot ?>finances/mass_add/<?php echo $client_id; ?>',
                                modal: true,
                                toolbar: [{
                                        text: 'Add Payment Received',
                                        iconCls: 'icon-add',
                                        handler: function() {
                                            $payment_panel_received.clone().appendTo($massadd_panel);
                                        }
                                    }, '-', {
                                        text: 'Add Payment Sent',
                                        iconCls: 'icon-add',
                                        handler: function() {
                                            $payment_panel_sent.clone().appendTo($massadd_panel);
                                        }
                                    }, '-', {
                                        text: 'Add Incoming Invoice',
                                        iconCls: 'icon-add',
                                        handler: function() {
                                            $invoice_panel.clone().appendTo($massadd_panel);
                                        }
                                    }],
                                buttons: [{
                                        text: 'Save',
                                        handler: function() {
                                            $('#myform').submit();
                                        }
                                    }, {
                                        text: 'Close',
                                        handler: function() {
                                            $dd.dialogui('close');
                                        }
                                    }],
                                onLoad: function() {
                                    $massadd_panel = $('#massadd_panel');
                                    $payment_panel_received = $('#payment_panel_received').remove();
                                    $payment_panel_sent = $('#payment_panel_sent').remove();
                                    $invoice_panel = $('#invoice_panel').remove();
                                    $myform = $('#myform');
                                    $back_url = $("#back_url").val("<?php echo $this->params['url']['url']; ?>");

                                    $payment_type.live('change', function() {
                                        var $this = $(this);

                                        if ($this.val() == 0) {
                                            $this.parent().next().hide();
                                        } else {
                                            $this.parent().next().show();
                                        }
                                    });

                                    $delete.live('click', function() {
                                        $(this).parents('ul').remove();
                                    });
                                }
                            });

                            $dd.dialogui('refresh', '<?php echo $this->webroot ?>finances/mass_add/<?php echo $client_id; ?>');
                                    });
                                });
</script>