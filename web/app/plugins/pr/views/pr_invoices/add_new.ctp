<link rel="stylesheet" href="<?php echo $this->webroot;?>js/jschosen/chosen.css">
<style type="text/css">
    .form .value, .list-form .value{text-align:left;}
    span.tag {
        background: none repeat scroll 0 0 #6B9B20;
        border-color: #389ABE;
        border-radius: 3px 3px 3px 3px;
        color: #FFFFFF;
        line-height: normal;
        padding: 4px;
        text-shadow: none;
        display: block;
        float: left;
        font-family: helvetica;
        font-size: 13px;
        margin-bottom: 5px;
        margin-right: 5px;
        text-decoration: none;
    }
    #dd a i.icon-stop  {
        color: #E5412D;
    }
    #dd a i.icon-stop:hover  {
        color: rgb(51, 51, 51);
    }
	.window-shadow {
		opacity: 0;
	}
</style>
<ul class="breadcrumb">
    <li><?php __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Finance') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('CreatingInvoice') ?></li>
</ul>


<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Invoices', true); ?></h4>

</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
    <a href="<?php echo $this->webroot ?>pr/pr_invoices/view/1" class="link_back btn btn-icon btn-inverse glyphicons circle_arrow_left">
        <i></i>
        &nbsp;<?php echo __('goback', true); ?>
    </a>
</div>
<div class="clearfix"></div>

<div class="innerLR">

    <div class="widget widget-heading-simple widget-body-white">
        <div class="widget-body">
            <?php echo $form->create('Invoice', array('url' => $invoiceUrl . '/ManualInvoiceTest', 'name' => 'form1', 'id' => 'form1', 'class' => 'form-inline')); ?>
            <input class="input in-hidden" name="query[id_clients]" value="" id="query-id_clients" type="hidden">
            <fieldset style="margin-top: 15px;">
                    <span  id="output-block" style="float:left;">
<!--                        &nbsp; --><?php //echo __('Output in', true); ?><!-- &nbsp;-->
<!--                        <select id="output" style="width: 80px;" name="output_type" class="input in-select">-->
<!--                            <option value="0">PDF</option>-->
<!--                            <option value="1">Word</option>-->
<!--                            <option value="2">HTML</option></select>                &nbsp;-->
                    </span>
                <table style="dispaly:inline-block;float:left;">
                    <tr class="period-block">
                        <td><?php __('time') ?>:</td>
                        <td colspan="5" class="value">
                            <table class="in-date"><tbody>
                                <tr>
                                    <td>
                                        <table class="in-date">
                                            <tbody>
                                            <tr>
                                                <td style="padding-right: 15px;">

                                                    <?php
                                                    $r = array('custom' => __('custom', true), 'curDay' => __('today', true), 'prevDay' => __('yesterday', true), 'curWeek' => __('currentweek', true), 'prevWeek' => __('previousweek', true), 'curMonth' => __('currentmonth', true),
                                                        'prevMonth' => __('previousmonth', true), 'curYear' => __('currentyear', true), 'prevYear' => __('previousyear', true));
                                                    if (!empty($_POST))
                                                    {
                                                        if (isset($_POST['smartPeriod']))
                                                        {
                                                            $s = $_POST['smartPeriod'];
                                                        }
                                                        else
                                                        {
                                                            $s = 'curDay';
                                                        }
                                                    }
                                                    else
                                                    {

                                                        $s = 'curDay';
                                                    }
                                                    echo $form->input('smartPeriod', array('options' => $r, 'label' => false,
                                                        'onchange' => 'setPeriod(this.value)', 'id' => 'query-smartPeriod', 'style' => 'width: 130px;', 'name' => 'smartPeriod',
                                                        'div' => false, 'type' => 'select', 'selected' => $s));
                                                    ?>

                                                </td>
                                                <td><input type="text" id="query-start_date-wDt" class="wdate in-text input" onchange="setPeriod('custom')"   readonly="readonly"  onkeydown="setPeriod('custom')"
                                                           value="" name="start_date"  style="margin-left: 0px; width: 156px;"></td>
                                                <td></td>
                                            </tr>
                                            </tbody></table>

                                    </td>
                                    <td><input type="text" id="query-start_time-wDt" onchange="setPeriod('custom')" onkeydown="setPeriod('custom')"
                                               readonly="readonly"
                                               style="width: 60px;" value="00:00:00" name="start_time" class="input in-text"></td>
                                    <td>&mdash;</td>
                                    <td><table class="in-date">
                                            <tbody><tr>
                                                <td><input type="text" id="query-stop_date-wDt" class="wdate in-text input"  style="width: 120px;"    onchange="setPeriod('custom')"
                                                           readonly="readonly"
                                                           onkeydown="setPeriod('custom')" value="" name="end_date"></td>
                                                <td></td>
                                            </tr>
                                            </tbody></table>

                                    </td>
                                    <td><input type="text" id="query-stop_time-wDt" onchange="setPeriod('custom')"
                                               readonly="readonly"
                                               onkeydown="setPeriod('custom')" style="width: 60px;" value="23:59:59" name="stop_time" class="input in-text"></td>


                                    <td style="padding: 0pt 10px;"><?php echo __('in', true); ?></td>
                                    <td><select class="input in-select" name="query[tz]" style="width: 100px;" id="query-tz">
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
                                            <option value="+0000"   selected="selected">GMT +00:00</option>
                                            <option value="+0100">GMT +01:00</option>
                                            <option value="+0200" >GMT +02:00</option>
                                            <option value="+0300">GMT +03:00</option>
                                            <option value="+0400">GMT +04:00</option><option value="+0500">GMT +05:00</option><option value="+0600">GMT +06:00</option><option value="+0700">GMT +07:00</option><option value="+0800">GMT +08:00</option><option value="+0900">GMT +09:00</option><option value="+1000">GMT +10:00</option><option value="+1100">GMT +11:00</option><option value="+1200">GMT +12:00</option></select></td>

                                </tr></tbody></table>
                        </td>
                    </tr>
                </table>
                <table class="form table dynamicTable tableTools table-bordered  table-white">
                    <tbody>
                    <tr>
                        <td colspan="4" id="tag_td">
<!--                            <input type="hidden" id="carriers" name="client_id" value="">-->
                        </td>
                    </tr>
                    <tr>
                        <td class="right"> <?php __('Carriers') ?>  </td>
                        <td id="client_cell" class="value">
                            <select data-placeholder="Choose a client..." name="client_id" class="chosen-select" multiple style="width:350px;">
                                <option value=""></option>
                                <?php foreach ($clients as $client): ?>
                                    <option value="<?php echo $client['Client']['client_id']; ?>"><?php echo $client['Client']['name']; ?></option>
                                <?php endforeach; ?>
                            </select>
<!--                            <input type="button" class="btn" id="choose_client" value="Choose...">-->
                        </td>
                        <td class="right"><?php echo __('Invoice Date', true); ?> </td>
                        <td class="value value4">
                            <input type="text" id="invoice_time" value="<?php echo date("Y-m-d"); ?>" readonly="readonly" class="input in-text wdate" onfocus="WdatePicker({dateFmt: 'yyyy-MM-dd'});" name="invoice_date" realvalue="">
                        </td>
                    </tr>
                    <tr>
                        <!--                <td><?php __('InvoiceNo') ?>:</td>
                        <td class="value value4"><input type="text" id="invoice_number" value="" name="invoice_number" class="input in-text"> <small class="note">(empty = auto)</small></td>-->
                        <td class="right"><?php echo __('Payment', true); ?><span rel="helptip" class="helptip" id="ht-100001"> Due Date</span><span class="tooltip" id="ht-100001-tooltip"><?php __('A number of days, when invoice is expected to be paid')?></span> </td>
                        <td class="value value4">
                            <input type="text" id="due_date" value="<?php echo date("Y-m-d"); ?>" readonly="readonly" class="input in-text wdate" onfocus="WdatePicker({dateFmt: 'yyyy-MM-dd'});" name="invoice_due_date" realvalue="">
                        </td>
                        <td class="right" style="border-bottom: 1px solid #EFEFEF;"><?php __('Rate Value') ?> </td>
                        <td style="border-bottom: 1px solid #EFEFEF;">
                            <select name="rate_value" style="width:100px;">
                                <option value="0" selected><?php __('Average')?></option>
                                <option value="1"><?php __('Actual')?></option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td class="right" style="border-bottom: 1px solid #EFEFEF;"><?php __('Rate Decimal Place') ?> </td>
                        <td style="border-bottom: 1px solid #EFEFEF; border-right: 1px solid #EFEFEF;">
                            <select name="decimal_place" style="width:80px;">
                                <?php for ($i = 0; $i <= 10; $i++): ?>
                                    <option <?php if ($i == 5) echo 'selected="selected"'; ?>><?php echo $i; ?></option>
                                <?php endfor; ?>
                            </select>
                        </td>
                    </tr>
                    <tr>

                        <td class="right"><?php __('Other Options')?> </td>
                        <td>
                            <?php
                            $a_type = 0;
                            switch ($type)
                            {
                                case '1':
                                    $a_type = 0;
                                    break;
                                case '3':
                                    $a_type = 1;
                                    break;
                                case '5':
                                    $a_type = 2;
                                    break;
                            }
                            ?>
                            <input type="hidden" name="type" value="<?php echo $a_type; ?>" />
                            <ul>
                                <li>
                                    <input type="checkbox" name="dayusage" />
                                    <?php __('Daily Usage with US Jurisdictional Breakdown')?>
                                </li>
                                <li>
                                    <input type="checkbox" name="is_invoice_usage_detail" />
                                    <?php __('Invoice Usage Detail')?>
                                </li>
                                <li>
                                    <input type="checkbox" name="ingress_prefix" />
                                    <?php __('Ingress Prefix')?>
                                </li>
                                <li>
                                    <input type="checkbox" name="is_short_duration_call_surcharge_detail" />
                                    <?php __('Short Duration Call Surcharge')?>
                                </li>
                                <li>
                                    <input type="checkbox" name="summary_of_payments" />
                                    <?php __('Include Summary of Payments')?>
                                </li>

                                <li>
                                    <input type="checkbox" name="detail_by_trunk" />
                                    <?php __('Show Detail by Trunk')?>
                                </li>
                                <li>
                                    <input type="checkbox" name="show_code_summery" />
                                    <?php __('Show Code Summary')?>
                                </li>
                                <li>
                                    <input type="checkbox" name="show_trafic_code_name" />
                                    <?php __('Show Total By Code Name')?>
                                </li>

                                <li>
                                    <input type="checkbox" name="show_trafic_country" />
                                    <?php __('Show Total by Country')?>
                                </li>
                                <li>
                                    <input type="checkbox" name="show_calls_date" />
                                    <?php __('Show Calls Date')?>
                                </li>
                                <li>
                                    <input type="checkbox" name="include_origination_billing" />
                                    <?php __('Include Origination Billing')?>
                                </li>
                            </ul>
                        </td>
                    </tr>
                    </tbody>
                </table>




                <div style="" id="t-generate">
                    <table class="form">
                        <col style="width: 14%;">
                        <col style="width: 86%;">
                        <tbody>
                        <tr>
                            <td></td>
                            <td class="value">
                                <div style="padding: 0pt 0pt 15px 60px; overflow: hidden; display: none;" id="columns">
                                    <div style="width: 100px; float: left; margin-right: 10px;">
                                        <input type="checkbox" checked="checked" id="fields-account" value="account" name="fields[]" class="input in-checkbox">                <label for="fields-account"><?php echo __('Account', true); ?></label>
                                    </div>
                                    <div style="width: 100px; float: left; margin-right: 10px;">
                                        <input type="checkbox" id="fields-code_country" value="code_country" name="fields[]" class="input in-checkbox">                <label for="fields-code_country"><?php echo __('Country', true); ?></label>
                                    </div>
                                    <div style="width: 100px; float: left; margin-right: 10px;">
                                        <input type="checkbox" id="fields-code_name" value="code_name" name="fields[]" class="input in-checkbox">                <label for="fields-code_name"><?php echo __('Destination', true); ?></label>
                                    </div>
                                    <div style="width: 100px; float: left; margin-right: 10px;">
                                        <input type="checkbox" id="fields-code" value="code" name="fields[]" class="input in-checkbox">                <label for="fields-code"><?php echo __('Codes', true); ?></label>
                                    </div>
                                    <div style="clear: both;"></div>            <div style="width: 100px; float: left; margin-right: 10px;">
                                        <input type="checkbox" id="fields-rate" value="rate" name="fields[]" class="input in-checkbox">                <label for="fields-rate"><?php echo __('Rate', true); ?></label>
                                    </div>
                                    <div style="width: 100px; float: left; margin-right: 10px;">
                                        <input type="checkbox" id="fields-calls" value="calls" name="fields[]" class="input in-checkbox">                <label for="fields-calls"><?php echo __('Calls', true); ?></label>
                                    </div>
                                    <div style="width: 100px; float: left; margin-right: 10px;">
                                        <input type="checkbox" id="fields-minutes" value="minutes" name="fields[]" class="input in-checkbox">                <label for="fields-minutes"><?php echo __('Minutes', true); ?></label>
                                    </div>
                                    <div style="width: 100px; float: left; margin-right: 10px;">
                                        <input type="checkbox" id="fields-cost" value="cost" name="fields[]" class="input in-checkbox">                <label for="fields-cost"><?php echo __('Cost', true); ?></label>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        </tbody><tbody id="products_block">
                        <tr>
                            <td></td>
                            <td class="value"></td>
                        </tr>

                        </tbody>

                    </table>

                </div>
            </fieldset>

            <div id="form_footer" class="buttons-group center">
                <input type="hidden" value=""  id="submit_flg"   />
                <input type="hidden" value=""  id="void_id" name="void_id"  />
                <input type="submit" value="Submit" class="input in-submit btn btn-primary">
                <input type="reset" value="Reset" onclick="reset_choose_carrier();" class="input in-button btn btn-default">
            </div>
            <?php echo $form->end(); ?>
            <div id="dd"> </div>
            <link rel="stylesheet" type="text/css" href="<?php echo $this->webroot ?>easyui/themes/default/easyui.css">
            <link rel="stylesheet" type="text/css" href="<?php echo $this->webroot ?>easyui/themes/icon.css">
            <script type="text/javascript" src="<?php echo $this->webroot ?>easyui/jquery.easyui.min.js"></script>
            <script type="text/javascript" src="<?php echo $this->webroot ?>js/jquery.autocomplete.js"></script>
            <script type="text/javascript">
                //&lt;![CDATA[
                var _ss_ids_client = {'id_clients': 'query-id_clients', 'id_clients_name': 'query-id_clients_name'};
                function showClients()
                {
                    ss_ids_custom['client'] = _ss_ids_client;
                    winOpen('<?php echo $this->webroot ?>clients/ss_client?types=2&type=0', 500, 530);
                }
                var lastId = 0;
                function addItem(row)
                {
                    lastId++;
                    if (!row || !row['id']) {
                        row = {};
                    }

                    // fix row values
                    for (k in row) {
                        if (row[k] == null)
                            row[k] = '';
                    }

                    // prepare row
                    var tRow = $('#tpl-entries').clone().appendTo($('#entries'));
                    tRow.attr('id', 'row-' + lastId).show();
                    // set names / values
                    tRow.find('input,select').each(function() {
                        var name = $(this).attr('name').substring(1).replace('%n', lastId);
                        var field = name.substring(name.lastIndexOf('[') + 1, name.length - 1);
                        $(this).attr('id', field + '-' + lastId);
                        $(this).attr('name', name);
                        $(this).val(row[field]);
                    });
                    // remove of the row
                    tRow.find('a[rel=delete]').click(function() {
                        $(this).closest('tr').remove();
                        return false;
                    });
                    // styles
                    initForms(tRow);
                    initList();
                    $('#add_positions').show();
                }

                /**
                 * Watch for type of invoice generation
                 */


                function updateDirection() {
                    if ($('#direction').val() == 'in') {
                        $('#products_block').hide();
                    } else {
                        $('#products_block').show();
                    }
                }
                updateDirection();
                function updateCurrency() {

                    var currency = $('#id_currencies option:selected').text();
                    $('#total_stats_cur').html(currency);
                    $('#total_other_cur').html(currency);
                }
                updateCurrency();
                function updateCdrOutput() {
                    if ($('#cdr_generate').attr('checked')) {
                        $('#cdr_output_row').show();
                    } else {
                        $('#cdr_output_row').hide();
                    }
                }
                updateCdrOutput();
            </script></div>
    </div>
</div>

<script src="<?php echo $this->webroot;?>js/jschosen/chosen.jquery.js" type="text/javascript"></script>

<script type="text/javascript">
    var config = {
        '.chosen-select'           : {},
        '.chosen-select-deselect'  : {allow_single_deselect:true},
        '.chosen-select-no-single' : {disable_search_threshold:10},
        '.chosen-select-no-results': {no_results_text:'Oops, nothing found!'},
        '.chosen-select-width'     : {width:"95%"}
    }
    for (var selector in config) {
        $(selector).chosen(config[selector]);
    }
</script>

<script type="text/javascript">

    $.fn.serializeObject = function()
    {
        var o = {};
        var a = this.serializeArray();
        $.each(a, function() {
            if (o[this.name] !== undefined) {
                if (!o[this.name].push) {
                    o[this.name] = [o[this.name]];
                }
                o[this.name].push(this.value || '');
            } else {
                o[this.name] = this.value || '';
            }
        });
        return o;
    };
    var carrers =  $('select[name="client_id"]').chosen().val();
    function reset_choose_carrier(){
        $("#tag_td").find('a').click();
        $('select[name="client_id"]').val(carrers).trigger('chosen:updated');
        setInterval(function(){
            $('select[name="client_id"]').closest('td').find('.search-field input').width(100);
        });
    }
    $(document).ready(function() {
        $("#ClientIsBreakdownByRateTable").click(function() {
            if ($(this).attr('checked') == 'checked')
            {
                $("#ClientBreakdownByRateTable").show();
            }
            else
            {
                $("#ClientBreakdownByRateTable").hide();
            }
        });

        $(".void").live('click', function() {
            var name = $(this).val();
            var client_id = $("#void_id").val();
            $("#void_id").val(client_id + "," + name);
        });
        $("#form1").submit(function(e) {
            e.preventDefault();
            let startDate = $("input[name=start_date]").val();
            let startTime = $("input[name=start_time]").val();
            let endDate = $("input[name=end_date]").val();
            let endTime = $("input[name=stop_time]").val();
            let start = startDate + " " + startTime;
            let end = endDate + " " + endTime;
            $("input[name=start_date]").val(start);
            $("input[name=end_date]").val(end);

            let dataJson = $(this).serializeObject();

            $("input[name=start_date]").val(startDate);
            $("input[name=end_date]").val(endDate);

//            let formAction = $(this).attr('action');

            jQuery.ajax({
                type: "POST",
                url: '<?php echo $this->webroot; ?>pr/pr_invoices/checkOverlapInvoices',
                data: dataJson,
                success: function (result) {
                    showMessages_new("[" + result + "]");

                    let decodedResult = $.parseJSON(result);

                    if(decodedResult.code == 201) {
                        location.href = "<?php echo $this->webroot; ?>pr/pr_invoices/view/1";
                    }

                }
            });

//            writeLog(1, formAction, dataJson);

//            $.ajax(formAction,{
//                type: "POST",
//                data: dataJson,
//                success: function (data) {
//                    showMessages_new("[{'field':'','code':'201','msg':'Successfully!'}]");
//                },
//                complete: function(xhr, textStatus) {
//                    console.log(xhr.status);
//                    writeLog(2, formAction, {
//                        status: xhr.status,
//                        text: textStatus
//                    });
//
//                    if(xhr.status == 200) {
//                        showMessages_new("[{'field':'','code':'201','msg':'Successfully!'}]");
//                        location.href = "<?php //echo $this->webroot; ?>//pr/pr_invoices/view/1";
//                    } else {
//                        showMessages_new("[{'field':'','code':'101','msg':'Failed!'}]");
//                    }
//                }
//            });
//
//            if (!$('#dd').length) {
//                $(document.body).append("<div id='dd'></div>");
//            }
//            var $dd = $('#dd');
//            var data = jQuery.ajaxData({
//                'url': "<?php //echo $this->webroot ?>//mailtmps/ajax_judge_invoice_mailtmp",
//                'type': 'POST',
//                'dataType': 'json',
//            });
//            if (data.flg == 1) //invoice mailtmp信息不全
//            {
//                jGrowl_to_notyfy('If you do not fill completely mail content will cause automatical emailing of invoice to fail', {theme: 'jmsg-error'});
//                $dd.load('<?php //echo $this->webroot ?>//mailtmps/ajax_get_invoice_mailtmp',
//                    {},
//                    function(responseText, textStatus, XMLHttpRequest) {
//                        $dd.dialog({
//                            'width': '850px',
//                            buttons: [{
//                                text: 'Submit',
//                                class: 'btn btn-primary',
//                                click: function() {
//                                    $.ajax({
//                                        url: "<?php //echo $this->webroot ?>//mailtmps/ajax_save_invoice_mailtmp",
//                                        type: 'post',
//                                        dataType: 'text',
//                                        data: $('#post_invoice').serialize(),
//                                        success: function(data) {
//                                            if (data == 1)
//                                            {
//                                                jGrowl_to_notyfy('save failed!', {theme: 'jmsg-error'});
//                                            }
//                                            else
//                                            {
//                                                jGrowl_to_notyfy('The Invoice is created successfully!', {theme: 'jmsg-success'});
//                                            }
//                                            $dd.dialog("close");
//                                        }
//                                    });
//                                }
//                            }, {text: 'Cancel', class: 'btn btn-default', click: function(){ $dd.dialog('close'); }}
//			]
//                        });
//                    }
//                );
//                return false;
//            }

        });

        $("#query-id_clients_name").autocomplete({
            url: '<?php echo $this->webroot ?>clients/getManualClient1',
            sortFunction: function(a, b, filter) {
                var f = filter.toLowerCase();
                var fl = f.length;
                var a1 = a.value.toLowerCase().substring(0, fl) == f ? '0' : '1';
                var a1 = a1 + String(a.data[0]).toLowerCase();
                var b1 = b.value.toLowerCase().substring(0, fl) == f ? '0' : '1';
                var b1 = b1 + String(b.data[0]).toLowerCase();
                if (a1 > b1) {
                    return 1;
                }
                if (a1 < b1) {
                    return -1;
                }
                return 0;
            },
            showResult: function(value, data) {
                return '<span style="color:lack">' + value + '</span>';
            }
        });
        var $choose_client = $('#choose_client');
        var $dd = $('#dd');
        var $tag_td = $('#tag_td');
        var $assign_client = null;
        var $carriers = $('#carriers');
        var carriers = new Array();
        var $tags = $('span.tag a');
        $choose_client.click(function() {

            var $search_name = null;
            $dd.dialogui({
                title: 'Carriers',
                width: 960,
                height: 600,
                closed: false,
                cache: false,
                resizable: true,
                href: '<?php echo $this->webroot ?>pr/pr_invoices/show_carriers',
                modal: true,
                buttons: [{
                    text: 'Close',
                    handler: function() {
                        $dd.dialogui('close');
                    }
                }],
                onLoad: function() {
			$('#inverse').text('Reset');
                    $search_name = $('#search_name');
                    $assign_client = $('.assign_client');
                    $search_name.bind('keypress', function(event) {
                        if (event.keyCode == "13")
                        {
                            $dd.dialogui('refresh', '<?php echo $this->webroot ?>pr/pr_invoices/show_carriers/' + $search_name.val());
                        }
                    });
                    $(".tag a").each(function() {
                        var checked_id = $(this).attr('id');
                        $("input[client_id=" + checked_id + "]").attr('checked', true);
                    });
                    $assign_client.click(function() {
                        var $this = $(this);
                        var carrier_ids = $("#carriers").val();
                        if ($this.attr('checked')) {
                            carriers.push($this.attr('client_id'));
                            if (carrier_ids)
                            {
                                $("#carriers").val($("#carriers").val() + "," + $this.attr('client_id'));
                            } else
                            {
                                $("#carriers").val($this.attr('client_id'));
                            }
                            $tag_td.append('<span class="tag"><span>' + $this.attr('client_name') + '  </span><a id="' + $this.attr('client_id') + '" client_id="' + $this.attr('client_id') + '" href="###">x</a></span>');
                        } else {
                            var id = $this.attr('client_id');

                            var ids = remove(carrier_ids, id);
                            $("#carriers").val(ids);

                            $("#" + id).trigger("click");
                        }

                    });
                    $('#select_all').click(function() {

                        $(".tag").remove();
                        $("input[name='check_single']").each(function() {

                            $(this).removeAttr("checked");
                            $(this).trigger("click");
                        });
                    });
                    $('#inverse').click(function() {

                        $(".tag").remove();
                        $("input[name='check_single']").each(function() {

                            $(this).removeAttr("checked");
                        });
                    });
                }
            });
            $dd.dialogui('refresh', '<?php echo $this->webroot ?>pr/pr_invoices/show_carriers');
        });
        $tags.live('click', function() {
            var $this = $(this);
            var client_id = $this.attr('client_id');
            for (var i = 0; i < carriers.length; i++)
            {
                if (client_id == carriers[i])
                {
                    carriers.splice(i, 1);
                    break;
                }
            }

            $this.parent().remove();
        });
        $('#ClientIsInvoiceAccountSummary').change(function() {
            if ($(this).is(':checked')) {
                $('#ClientInvoiceUseBalanceType').show();
            } else {
                $('#ClientInvoiceUseBalanceType').hide();
            }
        }).trigger("change");
        $("#form1").submit(function() {
            $carriers.val(carriers.join(','));
            return true;
        });
    });
    function remove(a, b) {
        if (a == b)
        {
            return '';
        }
        var _b = b + ',', idx = a.indexOf(b), idx2 = a.indexOf(_b);
        if (idx == idx2)
            a = a.replace(_b, '');
        return a;
    }

</script>