
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
    <a href="<?php echo $this->webroot ?>did/orig_invoice/view/1" class="link_back btn btn-icon btn-inverse glyphicons circle_arrow_left">
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
                                            </tbody>
                                            </table>

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
                                </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </table>
                <table style="margin-top: 10px;">
                    <tbody>
                    <tr>
                        <td colspan="4" id="tag_td">
                          <label><?php echo __('Carriers', true); ?></label>
                         <select name="client_id" class="select2">
<!--                             <option value="">--><?php //__('All')?><!--</option>-->
                             <?php foreach ($clients as $client_id => $name): ?>
                                 <option <?php if (isset($_GET['query']['client']) && $_GET['query']['client'] == $client_id) echo 'selected="selected"'; ?> value="<?php echo $client_id ?>"><?php echo $name ?></option>
                             <?php endforeach; ?>
                         </select>
                        </td>
                    </tr>

                    </tbody>
                </table>


            <div id="form_footer" class="buttons-group center">
                <input type="hidden" value=""  id="submit_flg"   />
                <input type="hidden" value=""  id="void_id" name="void_id"  />
                <input type="submit" value="Submit" class="input in-submit btn btn-primary">
                <input type="reset" value="Reset" onclick="reset_choose_carrier();" class="input in-button btn btn-default">
            </div>
            <?php echo $form->end(); ?>
        </fieldset>
        </div>
    </div>
</div>

<script>
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

        jQuery.ajax({
            type: "POST",
            url: '<?php echo $this->webroot; ?>pr/pr_invoices/checkOverlapInvoices',
            data: dataJson,
            success: function (result) {
                showMessages_new("[" + result + "]");

                let decodedResult = $.parseJSON(result);

                if(decodedResult.code == 201) {
                    location.href = "<?php echo $this->webroot; ?>did/orig_invoice/view/1";
                }

            }
        });
    });
</script>
