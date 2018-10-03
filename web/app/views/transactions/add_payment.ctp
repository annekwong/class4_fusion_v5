<ul class="breadcrumb">
    <li><?php __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Finance') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Add Payment') ?></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Add Payment') ?></h4>

</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
    <a href="<?php echo $this->webroot; ?>transactions/payment/<?php echo $type == 'received' ? 'incoming' : 'outgoing' ?>" class="link_back_new btn btn-icon btn-inverse glyphicons circle_arrow_left">
        <i></i>
        <?php __('Back'); ?>
    </a>
</div>
<div class="clearfix"></div>
<div class="innerLR">

    <div class="widget widget-heading-simple widget-body-white">
        <div class="widget-body">
            <form action="<?php echo $this->webroot; ?>transactions/add_payment/<?php echo $type ?>" method="post" id="myform">
                <table class="form table dynamicTable tableTools table-bordered  table-white">
                    <colgroup>
                        <col width="40%">
                        <col width="60%">
                    </colgroup>
                    <tr>
                        <td class="right"><?php __('Carrier') ?></td>
                        <td>
                            <select id="client_id" name="client_id">
                                <?php foreach ($carriers as $carrier): ?>
                                    <option has_invoice="<?php echo $carrier[0]['has_invoice'] ?>" mode="<?php echo $carrier['Client']['mode'] ?>" value="<?php echo $carrier['Client']['client_id'] ?>"><?php echo $carrier['Client']['name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td class="right"><?php __('Received On') ?></td>
                        <td>
                            <input type="text" class="width220" name="received_at" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})" value="<?php echo date("Y-m-d H:i:s"); ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td class="right"><?php __('Amount') ?>*</td>
                        <td>
                            <input type="text" id="amount" name="amount" maxlength="15" class="width220 validate[required,custom[number]]" />
                        </td>
                    </tr>
                    <tr>
                        <td class="right"><?php __('Note') ?></td>
                        <td>
                            <input type="text" name="note" class="width220 validate[maxSize[48]]" />
                        </td>
                    </tr>
                    <tr id="type_tr">
                        <td class="right"><?php __('Type') ?></td>
                        <td>
                            <select id="payment_type" name="type">
                                <option value="0"><?php __('Prepayment') ?></option>
                                <option value="1"><?php __('Invoice Payment') ?></option>
                            </select>
                        </td>
                    </tr>
                </table>
                <div id="invoice_head" class="widget-head">
                    <h4 class="heading"><?php __('Associate Payment to These Invoice(s)'); ?>:</h4>
                </div>
                <table id="invoice_table" class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
                    <thead>
                    <tr>
                        <th><?php __('Invoice Number') ?></th>
                        <th><?php __('Invoice Period') ?></th>
                        <th><?php __('Due Date') ?></th>
                        <th><?php __('Invoice Amount') ?></th>
                        <th><?php __('Unpaid Amount') ?></th>
                        <th><?php __('Pay Amount') ?></th>
                        <th>
                            <a id="add_invoice" class="btn" href="###">
                                <i></i><?php __('Add Invoice') ?>
                            </a>
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <input type="hidden" class="remain_amount" name="remain_amount" value="0">
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td><span class="remain_amount"></span></td>
                        <td></td>
                    </tr>
                    </tbody>
                </table>
                <div class="separator"></div>
                <?php echo $this->element('common/submit_div',array('div' => true)); ?>
            </form>
        </div>
    </div>
</div>

<script>
    $(function() {
        var $add_invoice = $('#add_invoice');
        var $amount      = $('#amount');
        var $client_id   = $('#client_id');
        var invoices     = new Array();
        var $invoice_table = $('#invoice_table tbody');
        var $invoice_paid = $('.invoice_paid');
        var $remain_amount = $('.remain_amount');
        var $delete_invoice = $('.delete_invoice');
        var $myform = $('#myform');
        var $payment_type = $('#payment_type');


        function refresh_amount()
        {
            var am_val = $amount.val();
//            var am_val = $amount.val().replaceAll(',','');
            am_val = am_val.replace(/,/g,'');
            var total_amount = Number(am_val);
            var $invoice_amounts = $('.invoice_paid');
            var paid_amount = 0;
            $.each($invoice_amounts, function(index, item) {
                paid_amount += Number($(this).val());
            });
            var remain_amount = total_amount - paid_amount;
            if (remain_amount < 0 && $payment_type.val() == '1') {
                jGrowl_to_notyfy("The Remain Amount must be greater or equal than 0!",{theme:'jmsg-error'});
            }
            if(!remain_amount)
            {
                remain_amount = "";
            }
            $remain_amount.val(remain_amount).text(remain_amount);
        }

        function invoice_amount_change(event)
        {
            var $this = $(this);
            var amount = Number($this.val());
            if ((amount < 0 || isNaN(amount)) && $payment_type.val() == '1') {
                jGrowl_to_notyfy("The Invoice Amount must be greater than 0!",{theme:'jmsg-error'});
                $this.val(1);
            }
            var $tr = $this.parents('tr');
            var invoice_amount = Number($('td:eq(1)', $tr).text());
            var invoice_pay    = Number($('td:eq(2)', $tr).text());
            var should_pay     = invoice_amount - invoice_pay;
            if (amount > should_pay) {
                jGrowl_to_notyfy("The Invoice Amount can not be greater than should pay!",{theme:'jmsg-error'});
                $this.val(should_pay);
                $this.focus();
            }

            refresh_amount();
        }

        $add_invoice.click(function() {
            var $this = $(this);
            $this.css('visibility', 'hidden');
            $.ajax({
                'url'     : '<?php echo $this->webroot ?>transactions/get_one_invoice',
                'type'    : 'POST',
                'dataType': 'json',
                'data'    : {'invoices[]' : invoices, 'client_id' : $client_id.val(), 'type' : "<?php echo $type ?>"},
                'success' : function(data) {
                    $.each(data, function(index, item) {
                        invoices.push(item[0]['invoice_number']);
                        var $tr = $('<tr />');
                        $tr.append('<input type="hidden" class="invoice_number" name="invoice_number[]" value="' + item[0]['invoice_number'] + '">');
                        $tr.append('<td>' + item[0]['invoice_number'] + '</td>');
                        $tr.append('<td>' + item[0]['invoice_start'] + '~' +  item[0]['invoice_end'] + '</td>');
                        $tr.append('<td>' + item[0]['due_date'] + '</td>');
                        $tr.append('<td>' + item[0]['total_amount'] + '</td>');
                        $tr.append('<td>' + (item[0]['total_amount'] - item[0]['pay_amount']) + '</td>');
                        $tr.append('<td><input class="invoice_paid input in-text in-input" type="text" name="invoice_paid[]" /></td>');
                        $tr.append("<td><a number='"+item[0]['invoice_number']+"' class='delete_invoice' href='###'><i class='icon-remove'></i></a></td>");
                        $invoice_table.prepend($tr);
                        $amount.change();
                    });
                }
            });
            $this.css('visibility', 'visible');
        });
        $invoice_paid.live('blur', invoice_amount_change);
        $amount.bind('blur', refresh_amount);

        function delete_invoice(event) {
            var $this = $(this);
            var invoice_number = $this.attr('number');
            for (var i = 0; i < invoices.length; i++) {
                if (invoices[i] == invoice_number) {
                    invoices.splice(i, 1);
                }
            }
            $this.parents('tr').remove();
            refresh_amount();
            return false;
        }

        $delete_invoice.live('click', delete_invoice);

        $myform.submit(function() {
            var am_val = $amount.val();
//            var am_val = $amount.val().replaceAll(',','');
            am_val = am_val.replace(/,/g,'');
            //alert(am_val);
            var total_amount = Number(am_val);
            var remain_amount = Number($remain_amount.val());
            var invoice_type = $payment_type.val();
            if (total_amount < 0 && invoice_type == '1') {
                jGrowl_to_notyfy("The Invoice Amount can not be less than 0!",{theme:'jmsg-error'});
                return false;
            }
            if (remain_amount < 0 && invoice_type == '1') {
                jGrowl_to_notyfy("The Remain Amount can not be less than 0!",{theme:'jmsg-error'});
                return false;
            }

        });

        function refresh()
        {
            invoices = new Array();
            var type = $payment_type.val();
            var has_invoice = $("#client_id option:selected").attr('has_invoice');
            $('tr:not(:last)', $invoice_table).remove();
            if (type == 0 || has_invoice == 0) {
                $("#invoice_table").hide();
                $("#invoice_head").hide();
            } else {
                $("#invoice_table").show();
                $("#invoice_head").show();
            }
        }

        function refresh_client()
        {
            var mode = $("#client_id option:selected").attr('mode');
            var has_invoice = $("#client_id option:selected").attr('has_invoice');
            if (has_invoice == 0) {
                $("#payment_type").val(0).attr('disabled', 'disabled');
            } else {
                $("#payment_type").removeAttr('disabled');
            }

           refresh();
        }

        $client_id.bind('change', refresh_client).trigger('change');
        $payment_type.bind('change', refresh).trigger('change');

        $amount.bind('change', function() {
            //var am_val = $amount.val().replace(',','');
            //var val = $(this).val().replaceAll(',','');
            var val = $(this).val();
            val = val.replace(/,/g,'');
            if (val <= 0) {
                $('.invoice_paid').attr('disabled', true);
            } else {
                $('.invoice_paid').removeAttr('disabled');
            }
        }).trigger('change');

    });
</script>
