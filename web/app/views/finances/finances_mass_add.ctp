<style>
    .formError{
        z-index: 9999;
    }
</style>
<ul class="breadcrumb">
    <li><?php __('You are here')?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Finance') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Past Due Notification Log') ?></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Past Due Notification Log') ?></h4>

</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
    <a id="add" class="btn btn-primary btn-icon glyphicons circle_plus" href="javascript:void(0)">
        <i></i>
        <?php __('Create New'); ?>
    </a>
	<a class="btn btn-icon btn-inverse glyphicons circle_arrow_left" href="<?php echo $this->webroot; ?>finances/get_mutual_ingress_egress_detail/<?php echo $encode_client_id; ?>"><i></i><?php __('Back')?></a>    
</div>
<div class="clearfix"></div>
<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-head">
            <?php echo $this->element('finances/tab',array('encode_client_id' => $encode_client_id,'active' => $mass_type)); ?>
        </div>
        <div class="widget-body">
<!--            <form id="myform" action="--><?php //echo $this->webroot ?><!--finances/quickadd" method="post" class="form-inline">-->
            <form action="" method="post">
                <input type="hidden" name="back_url" value="<?php echo $this->webroot ?>finances/get_mutual_ingress_egress_detail/<?php echo $encode_client_id; ?>" />
                <table class="table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
                    <?php switch($mass_type):
                        case 'rec_payment': ?>
                            <thead>
                            <tr>
                                <th><?php __('Payment Type'); ?></th>
                                <th><?php __('Received At'); ?></th>
                                <th><?php __('Amount'); ?></th>
                                <th><?php __('Action'); ?></th>
                            </tr>
                            </thead>
                            <tbody id="data_table">
                            <tr>
                                <td>
                                    <input type="hidden" name="payment_receiveds[]" value="1" />
                                    <select name="payment_received_types[]">
                                        <option value="0"><?php __('Prepayment'); ?></option>
                                        <option value="1"><?php __('Invoice Payment'); ?></option>
                                    </select>
                                </td>
                                <td>
                                    <input class="input in-text in-input validate[required]" type="text" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',lang:'en'})" name="payment_received_dates[]">
                                </td>
                                <td>
                                    <input class="input in-text in-input validate[required,custom[number]]" type="text"  name="payment_received_amounts[]">
                                </td>
                                <td></td>
                            </tr>

                            <tr class="table_tr">
                                <td>
                                    <input type="hidden" name="payment_receiveds[]" value="1" />
                                    <select name="payment_received_types[]">
                                        <option value="0"><?php __('Prepayment'); ?></option>
                                        <option value="1"><?php __('Invoice Payment'); ?></option>
                                    </select>
                                </td>
                                <td>
                                    <input class="input in-text in-input validate[required]" type="text" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',lang:'en'})" name="payment_received_dates[]">
                                </td>
                                <td>
                                    <input class="input in-text in-input validate[required,custom[number]]" type="text"  name="payment_received_amounts[]">
                                </td>
                                <td>
                                    <a title="Remove" href="javascript:void(0)" class="remove_a">
                                        <i class="icon-remove"></i>
                                    </a>
                                </td>
                            </tr>
                            </tbody>
                            <?php break; ?>
                        <?php case 'sent_payment': ?>
                            <thead>
                            <tr>
                                <th><?php __('Payment Type'); ?></th>
                                <th><?php __('Received At'); ?></th>
                                <th><?php __('Amount'); ?></th>
                                <th><?php __('Action'); ?></th>
                            </tr>
                            </thead>
                            <tbody id="data_table">
                            <tr>
                                <td>
                                    <input type="hidden" name="payment_sents[]" value="1" />
                                    <select name="payment_sent_types[]">
                                        <option value="0"><?php __('Prepayment'); ?></option>
                                        <option value="1"><?php __('Invoice Payment'); ?></option>
                                    </select>
                                </td>
                                <td>
                                    <input class="input in-text in-input validate[required]" type="text" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',lang:'en'})" name="payment_sent_dates[]">
                                </td>
                                <td>
                                    <input class="input in-text in-input validate[required,custom[number]]" type="text"  name="payment_sent_amounts[]">
                                </td>
                                <td>
                                </td>
                            </tr>

                            <tr class="table_tr">
                                <td>
                                    <input type="hidden" name="payment_sents[]" value="1" />
                                    <select name="payment_sent_types[]">
                                        <option value="0"><?php __('Prepayment'); ?></option>
                                        <option value="1"><?php __('Invoice Payment'); ?></option>
                                    </select>
                                </td>
                                <td>
                                    <input class="input in-text in-input validate[required]" type="text" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',lang:'en'})" name="payment_sent_dates[]">
                                </td>
                                <td>
                                    <input class="input in-text in-input validate[required,custom[number]]" type="text"  name="payment_sent_amounts[]">
                                </td>
                                <td>
                                    <a title="Remove" href="javascript:void(0)" class="remove_a">
                                        <i class="icon-remove"></i>
                                    </a>
                                </td>
                            </tr>
                            </tbody>
                            <?php break; ?>
                        <?php case 'vendor_invoice': ?>
                            <thead>
                            <tr>
                                <th colspan="3"><?php __('Invoice Period'); ?></th>
                                <th rowspan="2"><?php __('Invoice Date'); ?></th>
                                <th rowspan="2"><?php __('Due Date'); ?></th>
                                <th rowspan="2"><?php __('Amount'); ?></th>
                                <th rowspan="2"><?php __('Action'); ?></th>
                            </tr>
                            <tr>
                                <th><?php __('Start'); ?></th>
                                <th><?php __('End'); ?></th>
                                <th><?php __('GMT'); ?></th>
                            </tr>
                            </thead>
                            <tbody id="data_table">
                            <tr>
                                <td>
                                    <input type="hidden" value="1" name="incoming_invoices[]" />
                                    <input type="text" readonly="readonly" class="input in-text in-input start_date_time validate[required]" name="incoming_invoice_periods[]" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})"  value="<?php echo date("Y-m-d 00:00:00"); ?>" />
                                </td>
                                <td>
                                    <input type="text" readonly="readonly" class="input in-text in-input end_date_time validate[required]" name="incoming_invoice_tos[]" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})" value="<?php echo date("Y-m-d 23:59:59"); ?>"/>
                                </td>
                                <td>
                                    <select class="input in-select select width120" name="incoming_invoice_timezones[]" >
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
                                </td>
                                <td>
                                    <input type="text" readonly="readonly" class="input in-text in-input validate[required]" name="incoming_invoice_dates[]" onclick="WdatePicker({dateFmt:'yyyy-MM-dd',lang:'en'})"  />
                                </td>
                                <td>
                                    <input type="text" readonly="readonly" class="input in-text in-input validate[required]" name="incoming_invoice_due_dates[]" onclick="WdatePicker({dateFmt:'yyyy-MM-dd',lang:'en'})" />
                                </td>
                                <td>
                                    <input type="text" class="input in-text in-input validate[required,custom[number]]" name="incoming_invoice_amounts[]" />
                                </td>
                                <td>
                                </td>
                            </tr>
                            <tr class="table_tr">
                                <td>
                                    <input type="hidden" value="1" name="incoming_invoices[]" />
                                    <input type="text" readonly="readonly" class="input in-text in-input start_date_time validate[required]" name="incoming_invoice_periods[]" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})"  value="<?php echo date("Y-m-d 00:00:00"); ?>" />
                                </td>
                                <td>
                                    <input type="text" readonly="readonly" class="input in-text in-input end_date_time validate[required]" name="incoming_invoice_tos[]" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})" value="<?php echo date("Y-m-d 23:59:59"); ?>"/>
                                </td>
                                <td>
                                    <select class="input in-select select width120" name="incoming_invoice_timezones[]" >
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
                                </td>
                                <td>
                                    <input type="text" readonly="readonly" class="input in-text in-input validate[required]" name="incoming_invoice_dates[]" onclick="WdatePicker({dateFmt:'yyyy-MM-dd',lang:'en'})"  />
                                </td>
                                <td>
                                    <input type="text" readonly="readonly" class="input in-text in-input validate[required]" name="incoming_invoice_due_dates[]" onclick="WdatePicker({dateFmt:'yyyy-MM-dd',lang:'en'})" />
                                </td>
                                <td>
                                    <input type="text" class="input in-text in-input validate[required,custom[number]]" name="incoming_invoice_amounts[]" />
                                </td>
                                <td>
                                    <a title="Remove" href="javascript:void(0)" class="remove_a">
                                        <i class="icon-remove"></i>
                                    </a>
                                </td>
                            </tr>
                            </tbody>
                            <?php break; ?>
                        <?php endswitch; ?>
                </table>
                <div class="separator"></div>
                <div class="center">
                    <input type="submit" class='btn btn-primary' value="Submit"/>
                    <input type="reset" value="Revert" class="btn btn-default">
		</div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(function(){
        var table_tr = jQuery('.table_tr').eq(0).remove();
        $("#add").click(function(){
            table_tr.clone(true).prependTo('#data_table');
        });
        $(".remove_a").live('click',function(){
            $(this).closest('tr').remove();
        });


        $(".start_date_time").click(function(){
            var maxDate = $(this).parent().next().children().eq(0).val();
        });
    });
</script>