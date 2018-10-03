<ul class="breadcrumb">
    <li><?php __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Finance') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Overall Actual Balance')?>[<?php echo $client_name ?>]</li>
</ul>


<div class="heading-buttons">
    <h4><?php __('Overall Actual Balance')?>[<?php echo $client_name ?>]</h4>

</div>
<div class="separator bottom"></div>

<form id="exportForm" action="<?php echo $this->webroot; ?>finances/get_actual_ingress_egress_detail/<?php echo $this->params['pass'][0] ?>" method="GET">
    <input type="hidden" name="export" value="1">
    <input type="hidden" name="start_time" value="<?php echo $start_time ?>">
    <input type="hidden" name="end_time" value="<?php echo $end_time ?>">
</form>
<div class="buttons pull-right newpadding">
    <a class="btn btn-primary btn-icon glyphicons circle_plus" id="show-non-zeros" href="javascript:void(0)">
        <i><?php __('Show Non Zero') ?></i>
    </a>
    <?php if ($session->read('login_type') == 1): ?>
        <a id="massadd" class="btn btn-primary btn-icon glyphicons circle_plus" href="javascript:void(0)"><i></i> <?php __('Mass Add') ?></a>
        <?php if ($_SESSION['role_menu']['Payment_Invoice']['reset_balance'] == 1): ?>
            <a class="btn btn-primary btn-icon glyphicons screenshot" id="regenerate" href="javascript:void(0)">
                <i><?php __('Regenerate') ?></i>
            </a>
        <?php endif; ?>

        <a id="exportBtn" href="javascript:void(0)" title="" class="btn btn-primary btn-icon glyphicons file_export">
            <i></i> <?php __('Export'); ?>
        </a>
        <a class="btn btn-icon btn-inverse glyphicons circle_arrow_left" href="<?php echo $this->webroot; ?>clients/index">
            <i></i> <?php __('Back'); ?>
        </a>
    <?php endif;//elseif ($session->read('login_type') == 2): ?>
    <!-- <a target="_blank" href="<?php echo $this->webroot; ?>finances/get_actual_ingress_egress_detail/?export=1&start_time=<?php echo $start_time ?>&end_time=<?php echo $end_time ?>" title="Export" class="btn btn-primary btn-icon glyphicons file_export">
            <i></i> <?php// __('Export'); ?>
        </a>
        <a class="btn btn-icon btn-inverse glyphicons circle_arrow_left" href="<?php echo $this->webroot; ?>agent_portal/client_list">
            <i></i> <?php// __('Back'); ?>
        </a> -->
    <?php //endif; ?>
</div>
<div class="clearfix"></div>


<div class="innerLR">

    <div class="widget widget-heading-simple widget-body-white">
        <div class="widget-body">
            <table class="list footable table table-striped tableTools table-bordered  table-white table-primary">
                <thead>
                <tr>
                    <th><?php __('Beginning Balance on')?> <?php echo $start_time; ?> 00:00:00 <?php __('is')?> <?php echo ($begin_balance) ? $begin_balance : number_format($begin_balance, 5); ?></th>
                    <th><?php __('Ending Balance on')?> <?php if(isset($_GET['end_time']) && ($_GET['end_time'] < date('Y-m-d'))){echo $end_time." 23:59:59";}else{ echo date('Y-m-d H:i:s');} ?> <?php __('is')?> <?php echo ($end_balance) ? $end_balance : number_format($end_balance, 5); ?></th>
                </tr>
                </thead>
            </table>
            <div class="separator"></div>
            <div class="overflow_x">
                <table class="details-table list footable table table-striped tableTools table-bordered  table-white table-primary">
                    <thead>
                    <tr>
                        <th rowspan="2"><?php __('Date')?></th>
                        <th colspan="2"><?php __('Payment')?></th>
                        <th colspan="2"><?php __('Credit')?></th>
                        <th colspan="2"><?php __('Traffic')?></th>
                        <!--th rowspan="2"><?php __('Short Charges')?></th-->
                        <th rowspan="2"><?php __('Balance')?></th>
                    </tr>
                    <tr>
                        <th><?php __('Sent')?></th>
                        <th><?php __('Received')?></th>
                        <th><?php __('Sent')?></th>
                        <th><?php __('Received')?></th>
                        <th><?php __('Sent')?></th>
                        <th><?php __('Received')?></th>
                    </tr>
                    </thead>

                    <tbody>
                    <?php
                    foreach($financehistories as $financehistory):
                        $tmpfinancehistory = array(
                            'FinanceHistoryActual' => array(
                                'payment_received' => !isset($financehistory['FinanceHistoryActual']['payment_received']) ? 0 : $financehistory['FinanceHistoryActual']['payment_received'],
                                'credit_note_sent' => !isset($financehistory['FinanceHistoryActual']['credit_note_sent']) ? 0 : $financehistory['FinanceHistoryActual']['credit_note_sent'],
                                'debit_note_sent' => !isset($financehistory['FinanceHistoryActual']['debit_note_sent']) ? 0 : $financehistory['FinanceHistoryActual']['debit_note_sent'],
                                'unbilled_incoming_traffic' => !isset($financehistory['FinanceHistoryActual']['unbilled_incoming_traffic']) ? 0 : $financehistory['FinanceHistoryActual']['unbilled_incoming_traffic'],
                                'short_charges' => !isset($financehistory['FinanceHistoryActual']['short_charges']) ? 0 : $financehistory['FinanceHistoryActual']['short_charges'],
                                'payment_sent' => !isset($financehistory['FinanceHistoryActual']['payment_sent']) ? 0 : $financehistory['FinanceHistoryActual']['payment_sent'],
                                'credit_note_received' => !isset($financehistory['FinanceHistoryActual']['credit_note_received']) ? 0 : $financehistory['FinanceHistoryActual']['credit_note_received'],
                                'debit_note_received' => !isset($financehistory['FinanceHistoryActual']['debit_note_received']) ? 0 : $financehistory['FinanceHistoryActual']['debit_note_received'],
                                'unbilled_outgoing_traffic' => !isset($financehistory['FinanceHistoryActual']['unbilled_outgoing_traffic']) ? 0 : $financehistory['FinanceHistoryActual']['unbilled_outgoing_traffic'],
                                'actual_balance' => !isset($financehistory['FinanceHistoryActual']['actual_balance']) ? 0 : $financehistory['FinanceHistoryActual']['actual_balance'],
                                'date' => !isset($financehistory['FinanceHistoryActual']['date']) ? 0 : $financehistory['FinanceHistoryActual']['date']
                            )
                        );

                        $financehistory = $tmpfinancehistory;

                        $sum = $financehistory['FinanceHistoryActual']['payment_received'] +
                            $financehistory['FinanceHistoryActual']['credit_note_sent'] +
                          //  $financehistory['FinanceHistoryActual']['debit_note_sent'] +
                            $financehistory['FinanceHistoryActual']['unbilled_incoming_traffic'] +
                            $financehistory['FinanceHistoryActual']['short_charges'] +
                            $financehistory['FinanceHistoryActual']['payment_sent'] +
                            $financehistory['FinanceHistoryActual']['credit_note_received'] +
                           // $financehistory['FinanceHistoryActual']['debit_note_received'] +
                            $financehistory['FinanceHistoryActual']['unbilled_outgoing_traffic'];
                        $trClass = $sum == 0 ? 'zero-row' : '';
                        ?>
                        <tr class="<?= $trClass ?>">
                            <td><?php echo isset($financehistory['FinanceHistoryActual']['date']) ? $financehistory['FinanceHistoryActual']['date'] : '';  ?></td>
                            <td><?php echo isset($financehistory['FinanceHistoryActual']['payment_sent']) ? number_format($financehistory['FinanceHistoryActual']['payment_sent'], 5) : '';  ?></td>
                            <td><?php echo isset($financehistory['FinanceHistoryActual']['payment_received']) ? number_format($financehistory['FinanceHistoryActual']['payment_received'], 5) : '';  ?></td>
                            <td><?php echo isset($financehistory['FinanceHistoryActual']['credit_note_sent']) ? number_format($financehistory['FinanceHistoryActual']['credit_note_sent'], 5) : '';  ?></td>
                            <td><?php echo isset($financehistory['FinanceHistoryActual']['credit_note_received']) ? number_format($financehistory['FinanceHistoryActual']['credit_note_received'], 5) : '';  ?></td>
                            <td><?php echo isset($financehistory['FinanceHistoryActual']['unbilled_outgoing_traffic']) ? number_format($financehistory['FinanceHistoryActual']['unbilled_outgoing_traffic'], 5) : '' ;  ?></td>
                            <td><?php echo isset($financehistory['FinanceHistoryActual']['unbilled_incoming_traffic']) ? number_format($financehistory['FinanceHistoryActual']['unbilled_incoming_traffic'], 5) : '';  ?></td>
                            <!--td><?php echo isset($financehistory['FinanceHistoryActual']['short_charges']) ? number_format($financehistory['FinanceHistoryActual']['short_charges'], 5) : '';  ?></td-->
                            <td>
                                <?php echo isset($financehistory['FinanceHistoryActual']['actual_balance']) && $financehistory['FinanceHistoryActual']['actual_balance'] ? number_format($financehistory['FinanceHistoryActual']['actual_balance'], 5) : number_format(0, 5); ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (count($type_sum)): ?>
                        <tr>
                            <td><?php __('Total')?>:</td>
                            <td><?php echo number_format($type_sum['payment_sent'], 5) ?></td>
                            <td><?php echo number_format($type_sum['payment_received'], 5) ?></td>
                            <td><?php echo number_format($type_sum['credit_note_sent'], 5) ?></td>
                            <td><?php echo number_format($type_sum['credit_note_received'], 5) ?></td>
                            <td><?php echo number_format($type_sum['unbilled_outgoing_traffic'], 5) ?></td>
                            <td><?php echo number_format($type_sum['unbilled_incoming_traffic'], 5) ?></td>
                            <!--td><?php echo number_format($type_sum['short_charges'], 5) ?></td-->
                            <td><?php echo ($end_balance) ? $end_balance : number_format($end_balance, 5); ?></td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
                <div class="separator"></div>
            </div>

            <fieldset style=" clear:both;overflow:hidden;margin-top:10px;" class="query-box">
                <h4 class="heading glyphicons search"><i></i> <?php __('Search')?></h4>
                <div style="margin:0px auto; text-align:center;">
                    <form name="myform" method="get">
                        <?php __('Period')?>:
                        <input type="text" class="input in-text in-input" name="start_time" style="width:120px;" onclick="WdatePicker({dateFmt:'yyyy-MM-dd',lang:'en'})" value="<?php echo $start_time; ?>">
                        ~
                        <input type="text" class="input in-text in-input" name="end_time" style="width:120px;" onclick="WdatePicker({dateFmt:'yyyy-MM-dd',lang:'en'})" value="<?php echo $end_time; ?>">
                        <input type="submit" class="input in-submit btn btn-primary margin-bottom10" value="<?php __('Submit')?>">
                    </form>
                </div>
            </fieldset>
        </div>
    </div>
</div>

<div id="dd"> </div>
<link rel="stylesheet" type="text/css" href="<?php echo $this->webroot?>easyui/themes/default/easyui.css">
<link rel="stylesheet" type="text/css" href="<?php echo $this->webroot?>easyui/themes/icon.css">
<script type="text/javascript" src="<?php echo $this->webroot?>easyui/jquery.easyui.min.js"></script>

<script>
    var $dd = $('#dd');
    var $massadd = $('#massadd');
    var $regenerate = $('#regenerate');
    var $regenerate_reset = $('#regenerate_reset');

    $regenerate.click(function() {
        $.post("<?php echo $this->webroot?>finances/regenerate/<?php echo $client_id; ?>/2", function(data) {
            jGrowl_to_notyfy('Succeeded!',{theme:'jmsg-success'});
            window.setTimeout("window.location.reload()", 3000);
        }, 'json');
    });

    $regenerate_reset.click(function() {
        $.post("<?php echo $this->webroot?>finances/regenerate_reset/<?php echo $client_id; ?>/2", function(data) {
            jGrowl_to_notyfy('Succeeded!',{theme:'jmsg-success'});
            window.setTimeout("window.location.reload()", 3000);
        }, 'json');
    });

    $('#show-non-zeros').click(function(){
        if ($(this).html().trim() == '<i>Show Non Zero</i>') {
            $('.zero-row').fadeOut(500);
            $(this).html('<i>View All</i>');
        } else {
            $('.zero-row').fadeIn(500);
            $(this).html('<i>Show Non Zero</i>');
        }

    });

    $(function() {
        $('#show-non-zeros').click();

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
                href: '<?php echo $this->webroot?>finances/mass_add/<?php echo $client_id; ?>',
                modal: true,
                toolbar: [{
                    text:'Add Payment Received',
                    iconCls:'icon-add',
                    handler:function(){
                        $payment_panel_received.clone().appendTo($massadd_panel);
                    }
                }, '-',{
                    text:'Add Payment Sent',
                    iconCls:'icon-add',
                    handler:function(){
                        $payment_panel_sent.clone().appendTo($massadd_panel);
                    }
                }, '-',{
                    text:'Add Incoming Invoice',
                    iconCls:'icon-add',
                    handler:function(){
                        $invoice_panel.clone().appendTo($massadd_panel);
                    }
                }],
                buttons:[{
                    text:'Save',
                    handler:function(){
                        $myform.submit();
                    }
                },{
                    text:'Close',
                    handler:function(){
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

            $dd.dialogui('refresh', '<?php echo $this->webroot?>finances/mass_add/<?php echo $client_id; ?>');
        });

        $("#exportBtn").click(function () {
            $(".fakeloader").remove();
            $("#exportForm").submit();
        });
    });
</script>