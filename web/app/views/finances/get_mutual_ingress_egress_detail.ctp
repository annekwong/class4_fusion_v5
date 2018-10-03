<ul class="breadcrumb">
    <li><?php __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Finance') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Overall Mutual Balance')?>[<?php echo $client_name ?>]</li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php __('Overall Mutual Balance')?>[<?php echo $client_name ?>]</h4>

</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
    <!--        <a id="massadd" class="btn btn-primary btn-icon glyphicons circle_plus" href="###"><i></i> --><?php //__('Mass Add') ?><!--</a>-->
    <?php if ($session->read('login_type') == 1): ?>
        <a class="btn btn-primary btn-icon glyphicons circle_plus" href="<?php echo $this->webroot; ?>finances/finances_mass_add/<?php echo $this->params['pass'][0] ?>"><i></i> <?php __('Mass Add') ?></a>
        <?php if (isset($_SESSION['role_menu']['Payment_Invoice']['reset_balance']) && $_SESSION['role_menu']['Payment_Invoice']['reset_balance']== 1): ?>
            <a class="btn btn-primary btn-icon glyphicons screenshot" id="regenerate" href="javascript:void(0)">
                <i><?php __('Regenerate') ?></i>
            </a>
        <?php endif; ?>
    <?php endif; ?>
    <?php if(!isset($_GET['non_zero']) || $_GET['non_zero'] != 1):?>
    <a href="<?php echo $this->webroot; ?>finances/get_mutual_ingress_egress_detail/<?php echo $this->params['pass'][0] ?>?non_zero=1" class="btn btn-primary btn-icon glyphicons filter">
        <i></i> <?php __('Non Zero Only'); ?>
    </a>
    <?php else:?>
        <a href="<?php echo $this->webroot; ?>finances/get_mutual_ingress_egress_detail/<?php echo $this->params['pass'][0] ?>?non_zero=0"  class="btn btn-primary btn-icon glyphicons filter">
            <i></i> <?php __('All Datas'); ?>
        </a>
    <?php endif;?>
    <a target="_blank" href="<?php echo $this->webroot; ?>finances/get_mutual_ingress_egress_detail/<?php echo $this->params['pass'][0] ?>?export=1" class="btn btn-primary btn-icon glyphicons file_export">
        <i></i> <?php __('Export'); ?>
    </a>
    <a class="btn btn-icon btn-inverse glyphicons circle_arrow_left" href="<?php echo $this->webroot; ?>mutual_statements/summary_reports">
        <i></i> <?php __('Back'); ?>
    </a>
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
                <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
                    <thead>
                    <tr>
                        <th rowspan="2"><?php __('Date')?></th>
                        <th colspan="4"><?php __('Ingress')?></th>
                        <th colspan="4"><?php __('Egress')?></th>
                        <th rowspan="2"><?php __('Balance')?></th>
                        <?php if ($session->read('login_type') == 1): ?>
                            <th rowspan="2"><?php __('Action')?></th>
                        <?php endif; ?>
                    </tr>
                    <tr>
                        <th><?php __('Invoice Sent')?></th>
                        <th><?php __('Payment Received')?></th>
                        <th><?php __('Credit Note Sent')?></th>
                        <th><?php __('Debit Note Sent')?></th>
                        <th><?php __('Invoice Received')?></th>
                        <th><?php __('Payment Sent')?></th>
                        <th><?php __('Credit Note Received')?></th>
                        <th><?php __('Debit Note Received')?></th>
                    </tr>
                    </thead>

                    <tbody>
                    <?php foreach($financehistories as $financehistory): ?>
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
                                <?php echo (isset($financehistory['FinanceHistory']['mutual_balance']) && $financehistory['FinanceHistory']['mutual_balance']) ? $financehistory['FinanceHistory']['mutual_balance'] : number_format(0, 5); ?>
                            </td>
                            <?php if ($session->read('login_type') == 1): ?>
                                <td>
                                    <?php if($_SESSION['role_menu']['Payment_Invoice']['reset_balance'] == 1): ?>
                                        <a control="<?php echo isset($financehistory['FinanceHistory']['id']) ? $financehistory['FinanceHistory']['id'] : 0;  ?>" href="javascript:void(0);" class="synchronize" title="<?php __('Synchronize with Actual Balance')?>">
                                            <i class="icon-refresh"></i>
                                        </a>
                                    <?php endif; ?>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (count($type_sum)): ?>
                        <tr>
                            <td><?php __('Total')?>:</td>
                            <td><?php echo $type_sum['invoice_set'] ?></td>
                            <td><?php echo $type_sum['payment_received'] ?></td>
                            <td><?php echo $type_sum['credit_note_sent'] ?></td>
                            <td><?php echo $type_sum['debit_note_sent'] ?></td>
                            <td><?php echo $type_sum['invoice_received'] ?></td>
                            <td><?php echo $type_sum['payment_sent'] ?></td>
                            <td><?php echo $type_sum['credit_note_received'] ?></td>
                            <td><?php echo $type_sum['debit_note_received'] ?></td>
                            <td></td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
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
    var $synchronize = $('.synchronize');

    $regenerate.click(function() {
        $.post("<?php echo $this->webroot?>finances/regenerate/<?php echo $client_id; ?>/1", function(data) {
            jGrowl_to_notyfy('<?php __('successfully'); ?>!',{theme:'jmsg-success'});
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
                href: '<?php echo $this->webroot?>finances/synchronize/<?php echo base64_encode($client_id); ?>/' + control,
                modal: true,
                buttons:[{
                    text:'Save',
                    handler:function(){
                        $('#synchronize_form').submit();
                    }
                },{
                    text:'Close',
                    handler:function(){
                        $dd.dialogui('close');
                    }
                }]
            });

            $dd.dialogui('refresh', '<?php echo $this->webroot?>finances/synchronize/<?php echo base64_encode($client_id); ?>/' + control);
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
                        $('#myform').submit();
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

            $dd.dialogui('refresh', '<?php echo $this->webroot?>finances/mass_add/<?php echo base64_encode($client_id); ?>');
        });
    });
</script>