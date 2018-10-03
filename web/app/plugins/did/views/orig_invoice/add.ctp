<style>
    table.formTable tr td {
        padding: 5px;
    }
</style>
<ul class="breadcrumb">
    <li><?php __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('DID') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Origination Invoice') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Add') ?></li>
</ul>
<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Create Invoice', true); ?></h4>
</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
    <a href="<?php echo $this->webroot ?>did/orig_invoice/view/1"
       class="link_back btn btn-icon btn-inverse glyphicons circle_arrow_left">
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
                <table>
                    <tr class="period-block">
                        <td><?php __('time') ?>:</td>
                        <td colspan="5" class="value">
                            <table class="in-date">
                                <tbody>
                                <tr>
                                    <td>
                                        <table class="in-date">
                                            <tbody>
                                            <tr>
                                                <td style="padding-right: 15px;">

                                                    <?php
                                                    $r = array('custom' => __('custom', true), 'curDay' => __('today', true), 'prevDay' => __('yesterday', true), 'curWeek' => __('currentweek', true), 'prevWeek' => __('previousweek', true), 'curMonth' => __('currentmonth', true),
                                                        'prevMonth' => __('previousmonth', true), 'curYear' => __('currentyear', true), 'prevYear' => __('previousyear', true));
                                                    if (!empty($_POST)) {
                                                        if (isset($_POST['smartPeriod'])) {
                                                            $s = $_POST['smartPeriod'];
                                                        } else {
                                                            $s = 'curDay';
                                                        }
                                                    } else {

                                                        $s = 'curDay';
                                                    }
                                                    echo $form->input('smartPeriod', array('options' => $r, 'label' => false,
                                                        'onchange' => 'setPeriod(this.value)', 'id' => 'query-smartPeriod', 'style' => 'width: 130px;', 'name' => 'smartPeriod',
                                                        'div' => false, 'type' => 'select', 'selected' => $s));
                                                    ?>

                                                </td>
                                                <td><input type="text" id="query-start_date-wDt"
                                                           class="wdate in-text input"
                                                           onchange="changeAction(); setPeriod('custom')"
                                                           readonly="readonly" onkeydown="setPeriod('custom')"
                                                           value="" name="start_date"
                                                           style="margin-left: 0px; width: 156px;"></td>
                                                <td></td>
                                            </tr>
                                            </tbody>
                                        </table>

                                    </td>
                                    <td style="display: none;"><input type="text" id="query-start_time-wDt" onchange="setPeriod('custom')"
                                               onkeydown="setPeriod('custom')"
                                               readonly="readonly"
                                               style="width: 60px;" value="00:00:00" name="start_time"
                                               class="input in-text"></td>
                                    <td>&mdash;</td>
                                    <td>
                                        <table class="in-date">
                                            <tbody>
                                            <tr>
                                                <td><input type="text" id="query-stop_date-wDt"
                                                           class="wdate in-text input" style="width: 120px;"
                                                           onchange="changeAction(); setPeriod('custom')"
                                                           readonly="readonly"
                                                           onkeydown="setPeriod('custom')" value="" name="end_date">
                                                </td>
                                                <td></td>
                                            </tr>
                                            </tbody>
                                        </table>

                                    </td>
                                    <td style="display: none;"><input type="text" id="query-stop_time-wDt" onchange="setPeriod('custom')"
                                               readonly="readonly"
                                               onkeydown="setPeriod('custom')" style="width: 60px;" value="23:59:59"
                                               name="stop_time" class="input in-text"></td>
                                </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </table>
                <table class="formTable" style="margin-top: 10px;">
                    <tbody>
                    <tr>
                        <td id="tag_td">
                            <label><?php echo __('Carriers', true); ?></label>
                        </td>
                        <td>
                            <select name="client_id" class="select2" id="clientList">
                                <!--                             <option value="">--><?php //__('All')?><!--</option>-->
                                <?php foreach ($clients as $client_id => $name): ?>
                                    <option <?php if (isset($_GET['query']['client']) && $_GET['query']['client'] == $client_id) echo 'selected="selected"'; ?>
                                        value="<?php echo $client_id ?>"><?php echo $name ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        <td id="tag_td">
                            <label><?php echo __('Grace period', true); ?></label>
                        </td>
                        <td>
                            <input type="number" id="due" name="due" min="0" class="validate[required,custom[integer]]">
                        </td>
                    </tr>
                    <tr>
                        <td colspan="5">
                            <div>
                                <h4>Please select what need to include to invoice</h4>
                                <div class="select-block">
                                    <div>
                                        <input type="checkbox" name="did_invoice_include[]" id="account_summary"
                                               value="account_summary"> Account Summary
                                    </div>
                                    <div>
                                        <input type="checkbox" name="did_invoice_include[]"
                                               id="transaction_summary_analysis" value="transaction_summary_analysis">
                                        Transaction Summary Analysis
                                    </div>
                                    <div>
                                        <input type="checkbox" name="did_invoice_include[]" id="auth_code_800_summary"
                                               value="auth_code_800_summary"> Authorization Code (800) Summary
                                    </div>
                                    <div>
                                        <input type="checkbox" name="did_invoice_include[]" id="all_area_codes_summary"
                                               value="all_area_codes_summary"> All Area codes Summary
                                    </div>
                                    <div>
                                        <input type="checkbox" name="did_invoice_include[]" id="orig_lata_summary"
                                               value="orig_lata_summary"> Origination Lata Summary
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    </tbody>
                </table>


                <div id="form_footer" class="buttons-group center">
                    <input type="hidden" value="" id="submit_flg"/>
                    <input type="hidden" value="" id="void_id" name="void_id"/>
                    <input type="submit" value="Submit" class="input in-submit btn btn-primary">
                    <input type="reset" value="Reset" onclick="reset_choose_carrier();"
                           class="input in-button btn btn-default">
                </div>
                <?php echo $form->end(); ?>
            </fieldset>
        </div>
    </div>
</div>

<script>
    var frequencyType = null;
    var subFrequencyType = null;
    var listOfDays = {
        '0': 'Sunday',
        '1': 'Monday',
        '2': 'Tuesday',
        '3': 'Wednesday',
        '4': 'Thursday',
        '5': 'Friday',
        '6': 'Saturday'
    };
    var dateResult = true;
    var errorMessage = "";

    function changeAction() {
//        dateResult = true;
//        var start = $('#query-start_date-wDt').val();
//        var end = $('#query-stop_date-wDt').val();
//
//        if (frequencyType) {
//            if (frequencyType == 1 || frequencyType == 2 || frequencyType == 4) {
//                if (start != subFrequencyType) {
//                    dateResult = false;
//                    errorMessage = "[{code: 101, msg: '[Start Date] Day of month should be " + subFrequencyType + "'}]"
//                    showMessages_new(errorMessage);
//                }
//                if (end != subFrequencyType) {
//                    dateResult = false;
//                    errorMessage = "[{code: 101, msg: '[End Date] Day of month should be " + subFrequencyType + "'}]"
//                    showMessages_new(errorMessage);
//                }
//            } else if (frequencyType == 3) {
//                let tmpStartDate = new Date(start);
//                let tmpEndDate = new Date(end);
//
//                if (tmpStartDate.getDay() != subFrequencyType) {
//                    dateResult = false;
//                    errorMessage = "[{code: 101, msg: '[Start Date] Day of week should be " + listOfDays[subFrequencyType] + "'}]"
//                    showMessages_new(errorMessage);
//                }
//                if (tmpEndDate.getDay() != subFrequencyType) {
//                    dateResult = false;
//                    errorMessage = "[{code: 101, msg: '[End Date] Day of week should be " + listOfDays[subFrequencyType] + "'}]"
//                    showMessages_new(errorMessage);
//                }
//            }
//        }
    }

    $('#clientList').change(function () {
        $('input[name="did_invoice_include[]"]').prop('checked', false);

        $.get("<?php echo $this->webroot ?>did/orig_invoice/getClientPaymentTerm/" + $(this).val(), function (response) {
            var responseData = JSON.parse(response);

            if (responseData.grace_days) {
                $('#due').val(responseData.due);
            }

            if (responseData.did_invoice_include && responseData.did_invoice_include.length) {
                let includeArray = responseData.did_invoice_include.split(',');

                for (var item of includeArray) {
                    $('#' + item).prop('checked', true);
                }
            }

            frequencyType = responseData.type ? responseData.type : null;
            subFrequencyType = responseData.days ? responseData.days : null;

        });
    }).trigger('change');

    $("#form1").submit(function (e) {
        e.preventDefault();
//        changeAction();
        if (!dateResult) {
            showMessages_new(errorMessage);
        }

        let validateResult = $(this).validationEngine('validate');

        if (!validateResult) {
            return false;
        }
        let startDate = $("input[name=start_date]").val();
        let endDate = $("input[name=end_date]").val();
//        let startTime = $("input[name=start_time]").val();
//        let endTime = $("input[name=stop_time]").val();
        let start = startDate + " 00:00:00";
        let end = endDate + " 23:59:59";
        $("input[name=start_date]").val(start);
        $("input[name=end_date]").val(end);

        let dataJson = $(this).serializeObject();

        $("input[name=start_date]").val(startDate);
        $("input[name=end_date]").val(endDate);

        jQuery.ajax({
            type: "POST",
            url: '<?php echo $this->webroot; ?>did/orig_invoice/checkOverlapInvoices',
            data: dataJson,
            success: function (result) {
                showMessages_new("[" + result + "]");

                let decodedResult = $.parseJSON(result);

                if (decodedResult.code == 201) {
                    location.href = "<?php echo $this->webroot; ?>did/orig_invoice/view/1";
                }

            }
        });
    });
</script>
