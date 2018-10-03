<style type="text/css">
    #note_window {
        border:1px solid #ccc;
        border-radius: 15px;
        background:#fff;
        max-width:500px;
        max-height: 200px;
        width:500px;
        height:200px;
        display:none;
        top: 200px !important;
        z-index:1001;
    }

    #note_window p {
        padding:10px;
    }

    #note_window h1 {
        text-align:right;
        padding-right:20px;
        paddign-top:10px;
    }
    .list .jsp_resourceNew_style_2 tbody td {font-size: 12px;}
    .list .jsp_resourceNew_style_2 tbody td:hover {font-size: 12px;}

    #myform input, #myform select{
        margin-bottom: 0px;
    }

</style>

<?php
    echo $this->element('magic_css_three'); 
    if (!isset($this->params['pass'][0])){
     $this->params['pass'][0] = 'incoming';
    }
?>

<ul class="breadcrumb">
    <li><?php __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>transactions/payment">
        <?php __('Finance') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>transactions/<?= $this->params['pass'][0] ?>">
        <?php echo __('Payment') ?></a></li>
</ul>

<?php

?>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Payment Listing') ?></h4>

</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
    <?php if($_SESSION['login_type'] == 1): ?>
        <?php if (isset($_SESSION['role_menu']['Finance']['transactions:payment']['model_w']) && $_SESSION['role_menu']['Finance']['transactions:payment']['model_w']): ?>
            <a class="btn btn-primary btn-icon glyphicons circle_plus" id="add" href="<?php echo $this->webroot ?>transactions/add_payment/<?php echo $type == 'incoming' ? 'received' : 'sent'; ?>"><i></i> <?php __('Create New')?></a>
        <?php endif; ?>
    <?php endif; ?>
    <a  id="export_excel_btn" class="list-export btn btn-primary btn-icon glyphicons file_export">
        &nbsp;<i></i><?php __('Export'); ?>
    </a>
    <?php if (isset($extraSearch))
    { ?>
        <a class="link_back btn btn-icon btn-inverse glyphicons circle_arrow_left" href="<?php echo $backurl ?>" ><i></i><?php echo __('goback', true); ?> </a>
    <?php } ?>
</div>
<div class="clearfix"></div>
<?php $action = isset($_SESSION['sst_statis_smslog']) ? $_SESSION['sst_statis_smslog'] : '';
$w = isset($action['writable']) ? $action['writable'] : '';
?>

<div class="innerLR">

    <div class="widget widget-tabs widget-body-white" aaa="<?php echo $this->params['controller'];  ?>">
        <?php if(strcmp($this->params['controller'],'payment_history')): ?>
        <div class="widget-head">
            <ul>
                <li <?php if (!isset($this->params['pass'][0]) || $this->params['pass'][0] == 'incoming') echo 'class="active"'; ?>>
                    <a class="glyphicons no-js left_arrow" href="<?php echo $this->webroot; ?>transactions/payment/incoming">
                        <i></i><?php echo __('Received', true); ?>
                    </a>
                </li>
                <li <?php if (isset($this->params['pass'][0]) && $this->params['pass'][0] == 'outgoing') echo 'class="active"'; ?>>
                    <a class="glyphicons no-js left_arrow" href="<?php echo $this->webroot; ?>transactions/payment/outgoing">
                        <i></i><?php echo __('Sent', true); ?>
                    </a>
                </li>
                <?php if($_SESSION['login_type'] != 2): ?>
<!--                    <li --><?php //if (isset($this->params['pass'][0]) && $this->params['pass'][0] == 'upload') echo 'class="active"'; ?><!-- >-->
<!--                        <a class="glyphicons no-js right_arrow" href="--><?php //echo $this->webroot; ?><!--transactions/payment/upload">-->
<!--                            <i></i>--><?php //echo __('Upload', true); ?>
<!--                        </a>-->
<!--                    </li>-->
                    <li>
                        <a class="glyphicons no-js book_open" href="<?php echo $this->webroot; ?>payment_history">
                            <i></i><?php echo __('Auto Payment Log', true); ?>
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
        <?php endif; ?>
        <div class="widget-body">
            <fieldset style=" clear:both;overflow:hidden;margin-top:0px;margin-bottom:6px;height: 40px;" class="query-box">

                <div style="margin:5px auto 5px; text-align:center;" >
                    <form name="myform" method="get" id="myform" >
                        <input type="hidden" id="is_export" name="is_export" value="0" />
                        <input type="hidden" id="is_get" name="is_get" value="1" />
                        <?php echo __('Carrier', true); ?>:
                        <select name="client_id" class="input-small">
                            <option value=""><?php __('All')?></option>
                            <?php foreach ($clients as $client): ?>
                                <option value="<?php echo $client[0]['client_id'] ?>" <?php if (isset($_GET['client_id']) && $_GET['client_id'] == $client[0]['client_id'])
                                {
                                    echo "selected=\"selected\"";
                                } ?>><?php echo $client[0]['name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                        <?php __('From')?>:
                        <input type="text" name="start"  class="input-small" onclick="WdatePicker({dateFmt: 'yyyy-MM-dd', lang: 'en'})" value="<?php echo isset($_GET['start']) ? $_GET['start'] : date("Y-m-d"); ?>" />
                        <?php __('To')?>:
                        <input type="text" name="end" class="input-small" onclick="WdatePicker({dateFmt: 'yyyy-MM-dd', lang: 'en'})" value="<?php echo isset($_GET['start']) ? $_GET['end'] : date("Y-m-d"); ?>" />
                        <?php __('GMT')?>:
                        <select name="gmt" id='gmt' class="input-small">
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
                            <option value="+0000" selected="selected" >GMT +00:00</option>
                            <option value="+0100">GMT +01:00</option>
                            <option value="+0200">GMT +02:00</option>
                            <option value="+0300">GMT +03:00</option>
                            <option value="+0330">GMT +03:30</option><option value="+0400">GMT +04:00</option><option value="+0500">GMT +05:00</option><option value="+0600">GMT +06:00</option><option value="+0700">GMT +07:00</option><option value="+0800">GMT +08:00</option><option value="+0900">GMT +09:00</option><option value="+1000">GMT +10:00</option><option value="+1100">GMT +11:00</option><option value="+1200">GMT +12:00</option>
                        </select>


                        <?php echo __('Amount', true); ?>:
                        <input type="text" class="input-small" name="amount_a" value="<?php echo $amount_a ?>" />
                        <?php __('To')?>:
                        <input type="text" class="input-small" name="amount_b" value="<?php echo $amount_b ?>" />
                        <input id="myform_submit" type="submit" class="btn btn-primary" value="<?php echo __('submit', true); ?>" />
                    </form>
                </div>
            </fieldset>
            <?php if($is_get):?>
                <?php
                $data = $p->getDataArray();
                $i = 1;
                ?>
                <?php if (count($data) == 0): ?>
                    <center>
                        <h2 class="msg center"><?php __('No Payment Record for the period of')?>
                            <?php echo isset($_GET['start']) ? $_GET['start'] : date("Y-m-d", strtotime("-1 day")); ?> - <?php echo isset($_GET['start']) ? $_GET['end'] : date("Y-m-d"); ?>
                        </h2>
                    </center>
                <?php else: ?>
                    <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
                        <thead>
                        <tr>
                            <th></th>
                            <th><?php echo $appCommon->show_order('client_payment_id', __('Payment ID', true)) ?></th>
                            <th><?php echo $appCommon->show_order('payment_time', __('Entered Time', true)) ?></th>
                            <th><?php echo $appCommon->show_order('receiving_time', __('Received On', true)) ?></th>
                            <th><?php echo $appCommon->show_order('client', __('Carrier', true)) ?></th>
                            <th><?php echo $appCommon->show_order('amount', __('Amount', true)) ?></th>
                            <th><?php echo __('Type', true); ?></th>
                            <th><?php echo __('Update_by', true); ?></th>
                            <th><?php echo __('action', true); ?></th>
                        </tr>
                        </thead>
                        <tbody id="resInfo0"></tbody>
                        <tfoot></tfoot>
                        <?php foreach ($data as $item): ?>
                            <tbody id="resInfo<?php echo $i ?>">
                            <tr class="row-<?php echo $i % 2 + 1; ?>">
                                <td>
                                    <img id="image<?php echo $i; ?>"  onclick="pull('<?php echo $this->webroot ?>', this,<?php echo $i; ?>)"
                                    <?php if(isset($item[0]['payment_type']) && $item[0]['payment_type'] == '4'){?>
                                    class="jsp_resourceNew_style_1"
                                    <?php }else{?>
                                    class="no-event"
                                    <?php }?>
                                     src="<?php echo $this->webroot ?>images/+.gif" title="<?php __('View All') ?>"/>
                                </td>
                                <td>#<?php echo $item[0]['client_payment_id']; ?></td>
                                <td><?php echo substr_replace($item[0]['payment_time'], "", 19, -3); ?></td>
                                <td><?php echo isset($item[0]['receiving_time']) ? date('Y-m-d H:i:s', strtotime($item[0]['receiving_time'])) : ''; ?></td>
                                <td><?php echo $item[0]['client']; ?></td>
                                <td><?php echo number_format($item[0]['amount'], 2); ?></td>
                                <td><?php echo isset($item[0]['sel_payment_type']) ? $item[0]['sel_payment_type'] : '' ; ?></td>
                                <td><?php echo isset($item[0]['update_by']) && !empty($item[0]['update_by']) ? $item[0]['update_by'] : "paypal"; ?></td>
                                <td>
                                    <a title="Email To Carrier" href="<?php echo $this->webroot; ?>transactions/notify_carrier/<?php echo $item[0]['client_payment_id']; ?>/<?php echo $this->params['pass'][0] ?>">
                                        <i class="icon-envelope"></i>
                                    </a>
                                    <a title="Show Note" href="javascript:void(0)" class="note" control="<?php echo $item[0]['client_payment_id']; ?>" title="<?php __('Note'); ?>">
                                        <i class="icon-pencil"></i>
                                    </a>
                                    <?php if($_SESSION['login_type'] != 2): ?>
                                        <?php if ($_SESSION['role_menu']['Payment_Invoice']['delete_payment'] == 1): ?>
                                            <a title="Delete" href="<?php echo $this->webroot; ?>transactions/delete_payment/<?php echo $item[0]['client_payment_id']; ?>/?<?php echo $this->params['getUrl'] ?>" onclick="return myconfirm('<?php __('sure to delete'); ?>',this);">
                                                <i class="icon-remove"></i>
                                            </a>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </td>
                            </tr>

                            </tbody>
                            <tfoot>
                             <tr style="height:auto">
                                <td colspan="9">
                                    <div id="ipInfo<?php echo $i ?>" class="jsp_resourceNew_style_2" style="padding:5px;display:none;">
                                        <table class="table table-condensed">
                                            <tr>
                                                <td><?php __('Invoice Number')?></td>
                                                <td><?php __('Amount')?></td>
                                                <td><?php __('Invoice Period')?></td>
                                                <td><?php __('Due Date')?></td>
                                                <td><?php __('Total Paid Amount')?></td>
                                                <td><?php __('Due Amount')?></td>
                                                <td><?php __('Current Payment Paid Amount')?></td>
                                            </tr>
                                            <?php foreach ($item[0]['invoices'] as $invoice): ?>
                                                <tr>
                                                    <?php if ($invoice[0]['invoice_number'] == null): ?>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td>
                                                            <?php echo $invoice[0]['amount'] ?>
                                                            <?php if($_SESSION['login_type'] != 2): ?>
                                                                <a href="###" class="payment_to_invoice" amount="<?php echo $invoice[0]['amount'] ?>" payment="<?php echo $invoice[0]['id'] ?>" client_id="<?php echo $item[0]['client_id']; ?>" title="Payment To Invoice">
                                                                    <img src="<?php echo $this->webroot ?>images/add.png">
                                                                </a>
                                                            <?php endif; ?>
                                                        </td>
                                                    <?php else: ?>
                                                        <td><?php echo $invoice[0]['invoice_number'] ?></td>
                                                        <td><?php echo $invoice[0]['total_amount'] ?></td>
                                                        <td><?php echo $invoice[0]['invoice_start'] ?>~<?php echo $invoice[0]['invoice_end'] ?></td>
                                                        <td><?php echo $invoice[0]['due_date'] ?></td>
                                                        <td><?php echo $invoice[0]['pay_amount'] ?></td>
                                                        <td><?php echo $invoice[0]['total_amount'] - $invoice[0]['pay_amount'] ?></td>
                                                        <td><?php echo $invoice[0]['amount'] ?></td>
                                                    <?php endif; ?>
                                                </tr>
                                            <?php endforeach; ?>
                                        </table>
                                    </div>
                                </td>
                             </tr>
                            </tfoot>
                            <?php
                            $i++;
                        endforeach;
                        ?>
                    </table>

                    <div class="row-fluid">
                        <div class="pagination pagination-large pagination-right margin-none">
                            <?php echo $this->element('page'); ?>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                <?php endif; ?>
            <?php endif ?>
        </div>
    </div>
</div>

<div id="note_window">
    <h1>
        <a href="###" id="note_window_close">
            <i class='icon-remove'></i>
        </a>
    </h1>
    <p>

    </p>
</div>
<div id="dd"> </div>

<script src="<?php echo $this->webroot ?>js/ajaxTable.js" type="text/javascript"></script>
<scirpt type="text/javascript" src="<?php $this->webroot ?>js/jquery.center.js"></scirpt>
<link rel="stylesheet" type="text/css" href="<?php echo $this->webroot ?>easyui/themes/default/easyui.css">
<!--<link rel="stylesheet" type="text/css" href="<?php echo $this->webroot ?>easyui/themes/icon.css">-->
<script type="text/javascript" src="<?php echo $this->webroot ?>easyui/jquery.easyui.min.js"></script>
<script>
    $(function() {

        setTimeout(function(){
          $('.ColVis_collection .ColVis_radio input').each(function(index, val){
                     if(!$(this).is(':checked')){
                         $(this).click();
                     }
                })
        }, 1000)

        var $note_window = $('#note_window');
        var $note_window_close = $('#note_window_close');
        var $payment_to_invoice = $('.payment_to_invoice');
        var $dd = $('#dd');

        $("#myform_submit").click(function(){
            $("#is_export").val(0);
            $("#myform").removeAttr('target');
        });

        $('.note').click(function() {
            var payment_id = ($(this).attr('control'));

            $.ajax({
                'url': '<?php echo $this->webroot ?>transactions/get_note',
                'type': 'POST',
                'dataType': 'text',
                'data': {'id': payment_id},
                'success': function(data) {
                    $('p', $note_window).text(data);
                    $note_window.center().fadeIn();
                }
            });

            return false;

        });



        $payment_to_invoice.click(function() {
            var invoices = new Array();
            var $this = $(this);
            var payment_invoice_id = $this.attr('payment');
            var client_id = $this.attr('client_id');
            var $invoice_table = null;
            var total_amount = Number($this.attr('amount'));
            var $invoice_form = null;
            var $delete_invoice = null;

            $dd.dialogui({
                title: 'Payment To Invoice',
                width: 960,
                height: 600,
                closed: false,
                cache: false,
                resizable: true,
                href: '<?php echo $this->webroot ?>transactions/payment_to_invoice/' + payment_invoice_id + '/' + client_id + '/<?php echo $type == 'incoming' ? 'received' : 'sent'; ?>',
                modal: true,
                toolbar: [{
                    text: 'Add Invoice',
                    iconCls: 'icon-add',
                    handler: function() {
                        var $this = $(this);
                        $this.css('visibility', 'hidden');
                        $.ajax({
                            'url': '<?php echo $this->webroot ?>transactions/get_one_invoice',
                            'type': 'POST',
                            'dataType': 'json',
                            'data': {'invoices[]': invoices, 'client_id': client_id, 'type': "<?php echo $type == 'incoming' ? 'received' : 'sent'; ?>"},
                            'success': function(data) {
                                $.each(data, function(index, item) {
                                    invoices.push(item[0]['invoice_number']);
                                    var $tr = $('<tr />');
                                    $tr.append('<input type="hidden" class="invoice_number" name="invoice_number[]" value="' + item[0]['invoice_number'] + '">');
                                    $tr.append('<td>' + item[0]['invoice_number'] + '</td>');
                                    $tr.append('<td>' + item[0]['total_amount'] + '</td>');
                                    $tr.append('<td>' + item[0]['pay_amount'] + '</td>');
                                    $tr.append('<td>' + item[0]['invoice_start'] + '~' + item[0]['invoice_end'] + '</td>');
                                    $tr.append('<td><input class="invoice_paid input in-text in-input" type="text" name="invoice_paid[]" /></td>');
                                    $tr.append("<td><a number='" + item[0]['invoice_number'] + "' class='" + $delete_invoice + "' href='###'><i class='icon-remove'></i></a></td>");
                                    $invoice_table.prepend($tr);
                                });
                                $this.css('visibility', 'visible');
                            }
                        });

                    }
                }],
                buttons: [{
                    text: 'Submit',
                    handler: function() {
                        var $invoice_paid = $('.invoice_paid');
                        var paid_amount = 0;
                        $.each($invoice_paid, function(index, item) {
                            paid_amount += Number($(this).val());
                        });
                        var remain_amount = total_amount - paid_amount;

                        if (remain_amount < 0) {
                            jGrowl_to_notyfy("The Remain Amount must be greater or equal than 0!", {theme: 'jmsg-error'});
                            return false;
                        }

                        $invoice_form.submit();
                    }
                }, {
                    text: 'Close',
                    handler: function() {
                        $dd.dialogui('close');
                    }
                }],
                onLoad: function() {
                    $invoice_table = $("#invoice_list tbody");
                    $delete_invoice = $('.delete_invoice');
                    $delete_invoice.live('click', function() {
                        $(this).parents("tr").remove();
                    });
                    $invoice_form = $('#invoice_form');
                }
            });

            $dd.dialogui('refresh', '<?php echo $this->webroot ?>transactions/payment_to_invoice/' + payment_invoice_id + '/' + client_id + '/<?php echo $type == 'incoming' ? 'received' : 'sent'; ?>');
        });




        $note_window_close.click(function() {
            $note_window.hide('slow');
        });



        <?php
        if (isset($_GET['gmt']))
            echo "$('#gmt').val('{$_GET['gmt']}');";
        ?>
        $('#export_excel_btn').click(function() {
            $('#is_export').val('1');
            $('#myform').attr('target','_blank').submit();
        });
    });
</script>