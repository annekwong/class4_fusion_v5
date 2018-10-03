<style>
    .tr-hidden {
        display: none;
    }

    table.white-background tbody tr, table.white-background tbody tr td {
        background: #fff;
    }

    table.white-background tbody tr.tr-green, table.white-background tbody tr.tr-green td {
        background: #e9ffaf;
    }
    table.client_alert td {
        border: 1px solid #e9ffaf  !important;
    }
    table.client_alert tbody tr:nth-child(odd) td{
        background: #e9ffaf !important;
    }
    table.client_alert tbody tr:nth-child(even) td{
        background: #ffffff !important;
    }
</style>
<ul class="breadcrumb">
    <li>You are here</li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>carrier_template">
        <?php echo __('Template') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>carrier_template">
        <?php echo __('Carrier Template') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>carrier_template/add">
        <?php echo __('Create New') ?></a></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php __('Create New')?></h4>

</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
    <a class="btn btn-icon btn-inverse glyphicons circle_arrow_left" href="<?php echo $this->webroot?>carrier_template/index"><i></i> <?php __('Back')?></a>
</div>
<div class="clearfix"></div>

<style type="text/css">
    .form .value, .list-form .value{ text-align:left;}
    input{width: 220px;}
    fieldset{border:1px solid #eee;padding:10px;}
    .bodright20{margin-right: 20px;}
    table{width:100%;}
    .pull-right{text-align: center;}
    .table-bordered tbody:first-child tr:first-child td {
        border-top: 1px solid #e9ffaf;
    }
</style>
<div class="innerLR">

    <div class="widget widget-heading-simple widget-body-white">
        <div class="widget-body">

            <?php echo $form->create('Client', array('action' => 'add', 'url' => '/carrier_template/add', 'id' => 'ClientForm')); ?>

            <div class="widget">
                <div class="widget-head"><h4 class="heading"><?php __('Basic Info') ?></h4></div>
                <div class="widget-body">
                    <table class="form footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary default footable-loaded">
                        <!--                        <col width="40%"/>
                                                <col width="60%"/>-->

                        <tr>
                            <td class="align_right padding-r20"><?php echo __('Name') ?> </td>
                            <td >
                                <?php echo $form->input('template_name', array('label' => false, 'div' => false, 'type' => 'text', 'maxLength' => '500', 'class' => 'validate[required,custom[onlyLetterNumberLineSpace],funcCall[notEqualAdmin]]')) ?>
                            </td>
                            <td class="align_right padding-r20"><?php echo __('Send Trunk Update') ?> </td>
                            <td>
                                <?php
                                echo $form->checkbox('is_send_trunk_update', array('checked' => 'checked'));
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="align_right padding-r20"><span id="ht-100001" class="helptip" rel="helptip"><?php echo __('mode') ?></span></td>
                            <td>
                                <?php
                                $st = array('1' => __('Prepaid', true), '2' => __('postpaid', true));
                                echo $form->input('mode', array('options' => $st, 'label' => false, 'div' => false, 'type' => 'select', 'class' => 'input in-text in-select'))
                                ?>
                            </td>

                            <td class="align_right padding-r20"><?php echo __('currency') ?> </td>
                            <td>
                                <?php
                                $currency_default = isset($default_currency) ? $default_currency : "";
                                ?>

                                <?php echo $form->input('currency_id', array('options' => $currency, 'label' => false, 'div' => false, 'type' => 'select', 'class' => '', 'value' => $currency_default)); ?>
                            </td>
                        </tr>

                        <tr>
                            <td class="align_right padding-r20"><span id="ht-100002" class="helptip" rel="helptip"><?php echo __('allowedcredit') ?></span> </td>
                            <td style="text-align: left;">
                                <span id="unlimited_panel">
                                    <?php __('Unlimited')?>
                                    <?php echo $form->input('unlimited_credit', array('class' => 'in-decimal input in-checkbox', 'label' => false, 'div' => false, 'type' => 'checkbox')) ?>
                                </span>
                                <?php echo $form->input('allowed_credit', array('label' => false, 'value' => '0', 'div' => false, 'type' => 'text', 'class' => 'validate[min[0],custom[number]]', 'maxlength' => '30', 'style' => 'width: 100px; display: inline-block;')) ?>
                                <span class='money' style="display:inline-block"></span>
                            </td>


                            <td class="align_right padding-r20"><span id="ht-100002" class="helptip" rel="helptip"><?php echo __('lowprofit') ?></span> </td>
                            <td style="text-align: left;">
                                <?php echo $form->input('profit_margin', array('label' => false, 'value' => '0', 'div' => false, 'type' => 'text', 'class' => 'in-decimal input in-text', 'maxlength' => '6', 'style' => 'width:43%')) ?>
                                <?php echo $xform->input('profit_type', array('options' => Array(1 => __('Percentage', true), 2 => __('Value', true)), 'style' => 'width:43%')) ?>
                            </td>
                        </tr>
                        <!--
                        <tr>
                            <td ><span id="ht-100002" class="helptip" rel="helptip"><?php echo __('lowprofit') ?></span>:</td>
                            <td style="text-align: left;">
                        <?php echo $form->input('profit_margin', array('label' => false, 'value' => '0', 'div' => false, 'type' => 'text', 'class' => 'in-decimal input in-text', 'maxlength' => '6', 'style' => 'width:33%')) ?>
                        <?php echo $xform->input('profit_type', array('options' => Array(1 => __('Percentage', true), 2 => __('Value', true)), 'style' => 'width:33%')) ?>
                            </td>
                        </tr>
                        -->
                        <tr>
                            <td class="align_right padding-r20"><?php echo __('CPS') ?> </td>
                            <td>
                                <?php echo $form->input('cps_limit', array('label' => false, 'div' => false, 'type' => 'text', 'maxLength' => '100', 'class' => 'input in-text validate[custom[onlyNumberSp]]')) ?>
                            </td>
                            <!--                        </tr>
                                                    <tr>-->
                            <td class="align_right padding-r20"><?php echo __('Call limit') ?> </td>
                            <td>
                                <?php echo $form->input('call_limit', array('label' => false, 'div' => false, 'type' => 'text', 'maxLength' => '100', 'class' => 'input in-text validate[custom[onlyNumberSp]]')) ?>
                            </td>
                        </tr>

                    </table>
                </div>
            </div>


<!--            <div class="widget" data-toggle="collapse-widget" data-collapse-closed="true">-->
<!--                <div class="widget-head"><h4 class="heading">--><?php //__('Fraud Detection') ?><!--</h4></div>-->
<!--                <div class="widget-body">-->
<!--                    <table class="form footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary default footable-loaded">-->
<!--                        <tr>-->
<!--                            <td class="align_right padding-r20">--><?php //__('Daily limit'); ?><!--</td>-->
<!--                            <td  style="text-align: left;">-->
<!--                                <input type="text" name="data[Client][daily_limit]" class='validate[custom[onlyNumberSp]]' />-->
<!--                            </td>-->
<!--                            <td class="align_right padding-r20">--><?php //__('Hourly limit'); ?><!--</td>-->
<!--                            <td>-->
<!--                                <input type="text" name="data[Client][hourly_limit]" class='validate[custom[onlyNumberSp]]' />-->
<!--                            </td>-->
<!--                        </tr>-->
<!--                    </table>-->
<!--                </div>-->
<!--            </div>-->

            <div class="widget" data-toggle="collapse-widget" data-collapse-closed="true">
                <div class="widget-head"><h4 class="heading"><?php __('Automatic Report') ?></h4></div>
                <div class="widget-body">
                     <table class="client_alert white-background form footable table table-striped  tableTools table-bordered  table-white table-primary default footable-loaded">
                    <thead></thead>
                     <tr >
                        <td class="align_right padding-r20" colspan="3" style="font-weight: bold; text-align:center;" >
                            <?php echo __('Daily Alerts', true); ?>
                        </td>
                     </tr>
                    <tr>
                        <td class="align_right padding-r20">
                                                     <?php
                            empty($this->data['Client']['daily_cdr_generation']) ? $au = 'false' : $au = 'checked';
                            echo $form->input('daily_cdr_generation',
                            array(
                              'label'=>false,
                              'div'=>false,
                              'type'=>'checkbox'
                            )); ?>
                        </td>
                        <td>
                            <?php __('Enable Daily CDR Delivery') ?>
                        </td>
                         <td rowspan="2" class="daily_alert_on tr-hidden">
                              <span>Recipient:</span>
                                <?php
                                echo $form->input('auto_daily_balance_recipient', array('options' => array(__("Partner's Billing Contact", true), __("Owner's Billing Contact", true), __('Both', true)), 'label' => false, 'div' => false, 'type' => 'select'));
                                ?>
                                <br>
                                <span>Send Daily Alert on</span>
                                <?php
                                $send_times = array();
                                for ($i = 0; $i <= 23; $i++)
                                {
                                    $send_times[$i] = $i . ":00";
                                }
                                echo $form->input('auto_summary_hour', array('options' => $send_times, 'label' => false, 'div' => false, 'type' => 'select'));
                                ?>
                                <span>GMT</span>
                                <?php
                                $azone_arr = array();
                                for ($i = -12; $i <= 12; $i++)
                                {
                                    $zone_str = $i < 0 ? sprintf("-%02d:00", 0 - $i) : sprintf("+%02d:00", $i);
                                    $azone_arr[$zone_str] = 'GMT ' . $zone_str;
                                }
                                echo $form->input('auto_send_zone', array('options' => $azone_arr, 'label' => false, 'div' => false, 'type' => 'select'));
                                ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="align_right padding-r20">
                            <?php
                            echo $form->input('is_auto_balance', array('class' => 'in-decimal input in-checkbox', 'label' => false, 'div' => false, 'type' => 'checkbox'));
                            ?>
                        </td>
                        <td  style="text-align: left;">
                            <?php __('Daily Balance Summary') ?>
                        </td>
                    </tr>
                     <tr>
                       <td class="align_right padding-r20">
                           <?php
                           echo $form->input('is_auto_summary', array('class' => 'in-decimal input in-checkbox', 'label' => false, 'div' => false, 'type' => 'checkbox'));
                           ?>
                       </td>
                       <td  style="text-align: left;">
                           <?php __('Enable Daily Usage Summary') ?>
                       </td>
                       <td  class="daily_usage_summary tr-hidden">
                           <?php __('Non-Zero Only') ?>:
                           <?php
                           echo $form->input('auto_summary_not_zero', array('class' => 'in-decimal input in-checkbox', 'label' => false, 'div' => false, 'type' => 'checkbox', 'checked'=> 'checked'));
                           ?>
                           <br />
                           Group By:
                           <?php
                           echo $form->input('auto_summary_group_by', array('options' => array(__('By Country', TRUE), __('By Code Name', TRUE), __('By Code', TRUE)), 'label' => false, 'div' => false, 'type' => 'select'));
                           ?>
                       </td>
                   </tr>
                    <tr >
                       <td class="align_right padding-r20" colspan="3" style="font-weight: bold; text-align:center;" >
                           <?php echo __('Real Time Notification', true); ?>
                       </td>
                    </tr>
                    <tr >
                        <td class="align_right padding-r20" width="10%">
                            <?php
                            echo $form->checkbox('is_send_trunk_update');
                            ?>
                        </td>
                        <td colspan='2'>
                            <?php echo __('Enable Trunk Update Alert') ?> </td>
                        </td>
                    </tr>
                    <tr >
                        <td class="align_right padding-r20" width="10%">
                            <?php
                            echo $form->checkbox('enable_payment_alert');
                            ?>
                        </td>
                        <td  colspan='2'>
                            <?php echo __('Payment Received Alert') ?> </td>
                        </td>
                    </tr>
                    <!--tr>
                        <td class="align_right padding-r20" width="10%">
                            <?php
                            echo $form->input('is_daily_balance_notification', array('class' => 'toggle-next in-decimal input in-checkbox',
                                'label' => false, 'div' => false, 'type' => 'checkbox','name' => 'data[Client][is_daily_balance_notification]')) ?>
                        </td>
                        <td>
                            <?php echo __('Low Balance Notification') ?>
                        </td>
                        <td class="low_balance_config_table tr-hidden">
                            <input type="radio" name="data[low_balance][value_type]" id="lowBalanceValueType1" class="lowBalanceValueType pull-left" value="0" >
                            <label for="lowBalanceValueType1">
                            <?php __('Send low balance alert when the balance is less than or equal to'); ?>
                            </label>
                            <?php
                            echo $form->input('actual_notify_balance', array('class' => 'validate[custom[number]] width80',
                                'maxlength' => '10', 'label' => false, 'div' => false, 'type' => 'text',
                                'name' => 'data[low_balance][actual_notify_balance]')) ?>
                            <?php __('USD') ?>
                            <br />
                            <input type="radio" name="data[low_balance][value_type]" id="lowBalanceValueType2" class="lowBalanceValueType pull-left" value="1">
                            <label for="lowBalanceValueType2">
                            <?php __('Send low balance alert when the credit remaining is less than or equal to'); ?>
                            </label>
                            <?php
                            echo $form->input('percentage_notify_balance', array('class' => 'validate[custom[number]] width80',
                                'maxlength' => '10', 'label' => false, 'div' => false, 'type' => 'text',
                                'name' => 'data[low_balance][percentage_notify_balance]')) ?>%
                            <?php __('of credit limit') ?>
                            <br />
                            <?php __('Notification should be sent'); ?>
                            <?php
                            echo $form->input('', array('options' => array(0 => __('Daily', true), 1 => __('Hourly', true)),
                                'label' => false, 'style' => '', 'div' => false,
                                'type' => 'select', 'class' => 'input in-text in-select width80','id' => 'lowBalanceSendType',
                                'name' => 'data[low_balance][send_time_type]')); ?>
                            <div class="daily_send_time_div">
                                <?php __('at') ?>
                                <select name="data[low_balance][daily_send_time]" class="input in-select width80">
                                    <?php
                                    foreach ($appCommon->get_hour_time_arr() as $hour_key => $hour_show): ?>
                                        <option value="<?php echo $hour_key; ?>"><?php echo $hour_show; ?></option>
                                    <?php endforeach; ?>
                                </select>&nbsp;GMT
                            </div>
                            <br />
                            <?php __('Notification should be sent for'); ?>
                            <?php
                            echo $form->input('low_balance', array('class' => 'validate[custom[integer]] width25',
                                'maxlength' => '2', 'label' => false, 'div' => false, 'type' => 'text',
                                'name' => 'data[low_balance][duplicate_days]', 'value' => '1')) ?>
                            <?php __('days') ?>
                            <?php __('to') ?>
                            <select name="data[low_balance][send_to]" id="lowBalanceSendTo">
                                <option value="0"><?php __("Owner's Billing Contact") ?></option>
                                <option value="1"><?php __("Partner's Billing Contact") ?></option>
                                <option value="2"><?php __('Both') ?></option>
                            </select>
                        </td>
                    </tr-->
                </table>
                </div>
            </div>

            <div class="widget" data-toggle="collapse-widget" data-collapse-closed="true">
                <div class="widget-head"><h4 class="heading"><?php __('Short Call Charge') ?></h4></div>
                <div class="widget-body">
                    <div>
                        If <?php echo $form->input('scc_percent', array('label' => false, 'style' => 'width:25px;', 'div' => false, 'class' => 'validate[max[100],custom[number]]')) ?> % <?php __('overall invoice minute is below or equal to') ?> <?php echo $form->input('scc_bellow', array('options' => array('6' => '6', '12' => '12', '18' => '18', '24' => '24', '30' => '30'), 'label' => false, 'div' => false, 'type' => 'select', 'selected' => isset($post['Client']['scc_bellow']) ? $post['Client']['scc_bellow']: '', 'style' => 'width:auto;')) ?> <?php __('second') ?>, <br><?php __('then an additional charge of') ?>
                        <?php echo $form->input('scc_charge', array('label' => false, 'style' => 'width:40px;', 'div' => false, 'class' => 'validate[custom[onlyNumberSp]]')) ?> <?php __('will be applied to each call') ?>
                        <?php echo $form->input('scc_type', array('options' => array('0' => __('meeting the short duration defined above', true), '1' => __('that exceed the defined percentage', true)), 'label' => false, 'div' => false, 'type' => 'select', 'style' => 'width:auto;')); ?>
                        .
                    </div>
                </div>
            </div>
             <?php //************************paymentsetting********************************** ?>
            <div class="widget" data-toggle="collapse-widget" data-collapse-closed="true">
                <div class="widget-head">
                    <h4 class="heading">
                        <?php echo $form->checkbox('auto_invoicing', array('checked' => false)) ?> <?php __('Auto Invoice') ?>
                    </h4>
                </div>
                <div class="widget-body">
                    <table id="auto_invoice_table" class="form footable table table-striped  tableTools table-bordered  table-white table-primary default footable-loaded">
                        <thead></thead>
                        <tr>
                            <td class="align_right padding-r20"><?php echo __('Include Tax In Invoice', true); ?> </td>
                            <td>
                                <?php echo $form->input('include_tax', array('label' => false, 'div' => false, 'class' => 'input in-text in-input', 'disabled' => 'disabled')) ?>
                                <span id="tax_area" style="display:none;">
                                                <?php echo $form->input('tax', array('label' => false, 'div' => false, 'class' => 'validate[custom[onlyNumberSp]]', 'style' => 'width:30px;')) ?>
                                    %
                                            </span>
                            </td>
                            <td class="align_right padding-r20"><?php echo __('paymentterm', true) ?> </td>
                            <td>
                                <?php echo $form->input('payment_term_id', array('options' => $paymentTerm, 'label' => false, 'div' => false, 'type' => 'select')); ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="align_right padding-r20"><?php __('Starting From') ?></td>
                            <td>
                                <?php
                                echo $form->input('invoice_start_from', array('onfocus' => "WdatePicker({dateFmt:'yyyy-MM-dd'});", 'class' => "input in-text", 'readonly' => true, 'label' => false, 'div' => false, 'type' => 'text'));
                                ?>
                            </td>
                            <?php /*<td class="align_right padding-r20"><?php echo __('invoice type', true) ?> </td>

                            <td>
                                <?php echo $form->input('auto_invoice_type', array('options' => array('client', 'vendor', 'both'), 'label' => false, 'div' => false, 'type' => 'select'));
                                ?>

                            </td>*/ ?>
                        </tr>
                        <tr>
                            <td class="align_right padding-r20"><?php echo __('Invoice Format', true) ?> </td>
                            <td><?php
                                $route_type = array('1' => 'PDF', '2' => 'Word'); //,'2'=>'Excel','3'=>'HTML');
                                echo $form->input('invoice_format', array('options' => $route_type, 'label' => false, 'div' => false, 'type' => 'select'));
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="align_right padding-r20"><?php echo __('Time Zone', true) ?> </td>

                            <td>
                                <?php
                                $zone_arr = array();
                                for ($i = -12; $i <= 12; $i++)
                                {
                                    $zone_str = $i < 0 ? sprintf("-%02d:00", 0 - $i) : sprintf("+%02d:00", $i);
                                    $zone_arr[$zone_str] = 'GMT ' . $zone_str;
                                }
                                echo $form->input('invoice_zone', array('options' => $zone_arr, 'label' => false, 'div' => false, 'type' => 'select', 'selected' => $gmt));
                                ?>

                            </td>
                            <td class="align_right padding-r20"><?php echo __('No Invoice For Zero Traffic') ?> </td>

                            <td>
                                <?php
                                echo $form->input('invoice_zero', array('options' => array(1 => 'Yes', 0 => 'No'), 'label' => false, 'div' => false, 'type' => 'select', 'selected' => 1));
                                ?>

                            </td>
                        </tr>
                        <tr>
                            <td class="align_right padding-r20"><?php echo __('CDR Compress Format', true) ?> </td>
                            <td>
                                <?php
                                $route_type = array('3' => 'zip', '4' => 'tar.gz');
                                echo $form->input('cdr_list_format', array('options' => $route_type, 'label' => false, 'div' => false, 'type' => 'select'));
                                ?>
                            </td>
                            <td class="align_right padding-r20"><?php __('Rate Decimal Place'); ?> </td>
                            <td>
                                <?php
                                $decimal_places = range(1, 10);
                                $decimal_places = array_combine($decimal_places, $decimal_places);
                                ?>
                                <?php
                                echo $form->input('decimal_place', array('options' => $decimal_places,
                                    'style' => 'width:80px;', 'value' => 5, 'label' => false, 'div' => false))
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="align_right padding-r20"><?php __('Rate Value'); ?></td>
                            <td>
                                <?php
                                echo $form->input('rate_value', array('options' => array(__('Average', true), __('Actual', true)),
                                    'style' => 'width:80px;', 'value' => 0, 'label' => false, 'div' => false))
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="align_right padding-r20"><?php __('Auto Send Invoice'); ?> </td>
                            <td>
                                <?php echo $form->checkbox('is_email_invoice'); ?>
                                <?php
                                echo $form->input('email_invoice', array('options' => $sendemailTerm, 'label' => false, 'div' => false, 'type' => 'select', 'style' => 'width:120px;display:none;'));
                                ?>
                            </td>
                            <td class="align_right padding-r20"><?php __('Show Account Summary'); ?> </td>
                            <td>
                                <?php empty($post['Client']['include_available_credit']) ? $au = 'false' : $au = 'checked'; ?>
                                <?php echo $form->checkbox('include_available_credit', array('checked' => $au)); ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="align_right padding-r20"><?php __('Send Invoice As Link In Email'); ?> </td>
                            <td>
                                <?php empty($post['Client']['is_send_as_link']) ? $au = 'false' : $au = 'checked'; ?>
                                <?php echo $form->checkbox('is_send_as_link', array('checked' => $au)); ?>
                            </td>
                            <td class="align_right padding-r20"><?php  ?> </td>
                            <td>
                            </td>
                        </tr>
                        <tr>
                            <td class="align_right padding-r20"><?php __('Daily Usage with US Jurisdictional Breakdown'); ?> </td>
                            <td>
                                <?php echo $form->checkbox('is_show_daily_usage'); ?>
                            </td>
                        </tr>

                        <tr>
                            <td class="align_right padding-r20"><?php __('Short Duration Call Surcharge Detail'); ?> </td>
                            <td>
                                <?php echo $form->checkbox('is_short_duration_call_surcharge_detail'); ?>
                            </td>
                            <td class="align_right padding-r20"><?php __('Include Summary of Payments'); ?> </td>
                            <td>
                                <?php echo $form->checkbox('invoice_include_payment'); ?>
                            </td>
                        </tr>


                        <tr>
                            <td class="align_right padding-r20"><?php __('Show Detail By Trunk'); ?></td>
                            <td>
                                <?php echo $form->checkbox('is_show_detail_trunk'); ?>
                            </td>
                            <td class="align_right padding-r20"><?php __('Show Total By Trunk'); ?></td>
                            <td>
                                <?php echo $form->checkbox('is_show_total_trunk'); ?>
                            </td>
                        </tr>



                        <tr>
                            <td class="align_right padding-r20"><?php __('Show Code Summary (Top 10)'); ?></td>
                            <td>
                                <?php echo $form->checkbox('is_show_code_100'); ?>
                            </td>
                            <td class="align_right padding-r20"><?php __('Show Traffic Analysis by Code Name'); ?></td>
                            <td>
                                <?php echo $form->checkbox('is_show_code_name'); ?>
                            </td>
                        </tr>


                        <tr>
                            <td class="align_right padding-r20"><?php __('Show Traffic Analysis By Country'); ?></td>
                            <td>
                                <?php echo $form->checkbox('is_show_country'); ?>
                            </td>
                        </tr>


                        <tr>
                            <td class="align_right padding-r20"><?php echo __('Include CDR Link In Email') ?></td>
                            <td><?php echo $form->checkbox('attach_cdrs_list') ?></td>
                            <td class="align_right padding-r20"><?php echo __('Show Detail By Code Name') ?></td>
                            <td><?php echo $form->checkbox('invoice_show_details') ?></td>
                        </tr>
                        <tr>
                            <td class="align_right padding-r20"><?php echo __('Show Jurisdictional Detail') ?></td>
                            <td><?php echo $form->checkbox('invoice_jurisdictional_detail') ?></td>
                        </tr>

                        <tr>
                            <td class="align_right padding-r20"><?php __('Usage Detail Fields'); ?> </td>
                            <td>
                                <select name="data[Client][usage_detail_fields][]" multiple="multiple">
                                    <option value="completed_calls"  selected="selected"><?php __('Completed Calls') ?></option>
                                    <option value="interstate_minute"><?php __('Interstate Minute') ?></option>
                                    <option value="intrastate_minute"><?php __('Intrastate Minute') ?></option>
                                    <option value="indeterminate_minute"><?php __('Indeterminate Minute') ?></option>
                                    <option value="total_minutes" selected="selected"><?php __('Total Minutes') ?></option>
                                    <option value="total_charges" selected="selected"><?php __('Total Charges') ?></option>
                                </select>
                            </td>
                            <td class="align_right padding-r20"><?php __('Break Down By Rate Table'); ?> </td>
                            <td>
                                <?php echo $form->checkbox('is_breakdown_by_rate_table'); ?>
                                <?php
                                echo $form->input('breakdown_by_rate_table', array('options' => array(1 => 'Breakdown A-Z Rate Table by Destination', 2 => 'Breakdown US Rate Table by Jurisdiction'), 'label' => false, 'div' => false, 'type' => 'select', 'selected' => 0, 'style' => 'width:120px;display:none;'));
                                ?>
                            </td>
                        </tr>

                    </table>
                </div>
            </div>
            <div class="clearfix"></div>

            <?php //************************client panel********************************** ?>

            <!--div class="widget" data-toggle="collapse-widget" data-collapse-closed="true">
                <div class="widget-head">
                    <h4 class="heading">
                        <?php echo $form->input('is_daily_balance_notification', array('class' => 'in-decimal input in-checkbox', 'label' => false, 'div' => false, 'type' => 'checkbox')) ?>
                        <?php echo __('Low Balance Notification') ?><span id="ht-100012-tooltip" class="tooltip"><?php __('Send notification when current balance + credit limit is lower than specified threshold. Leave field empty to disable notification.') ?></span>
                    </h4>
                </div>
                <div class="widget-body">
                    <table class="form footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary default footable-loaded">
                        <tr>
                            <td class="align_right padding-r20"><?php echo __('Low Balance Threshold', true) ?> </td>
                            <td  style="text-align: left;">
                                <?php echo $form->input('notify_client_balance', array('class' => 'validate[required,custom[number]]', 'maxlength' => '30', 'label' => false, 'div' => false, 'type' => 'text', 'style' => 'width:25px')) ?>
                            </td>
                            <td class="align_right padding-r20"><?php echo __('USD', true) ?> </td>
                            <td  style="text-align: left;">

                                <?php echo $form->input('notify_client_balance_type', array('options' => array(0 => __('Actual Balance', true), 1 => __('Percentage', true)), 'label' => false, 'style' => '', 'div' => false, 'type' => 'select', 'class' => 'input in-text in-select', 'selected' => 0)); ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="align_right padding-r20"><?php echo __('Time') ?></td>
                            <td>
                                <?php echo $form->input('daily_balance_send_time', array('label' => false, 'div' => false, 'type' => "text", 'onfocus' => "WdatePicker({dateFmt:'HH:mm'});")) ?>
                            </td>
                            <td class="align_right padding-r20"><?php echo __('GMT') ?></td>
                            <td>
                                <?php
                                $azone_arr = array();
                                for ($i = -12; $i <= 12; $i++)
                                {
                                    $zone_str = $i < 0 ? sprintf("-%02d:00", 0 - $i) : sprintf("+%02d:00", $i);
                                    $azone_arr[$zone_str] = 'GMT ' . $zone_str;
                                }
                                echo $form->input('daily_balance_send_time_zone', array('options' => $azone_arr, 'label' => false, 'div' => false, 'type' => 'select'));
                                ?>
                            </td>
                        </tr>
                        <tr>

                            <td class="align_right padding-r20"></td>
                            <td  style="text-align: left;">
                                <div id="is_daily_balance_panel" style="display:none;">
                                    <?php __('Number of days'); ?>:
                                    <input type="text" name="data[Client][daily_balance_notification]" class='validate[custom[onlyNumberSp]]' />
                                    <br />
                                    <?php __('Recipient'); ?>:
                                    <select name="data[Client][daily_balance_recipient]">
                                        <option value="0"><?php __("Partner's Billing Contact") ?></option>
                                        <option value="1"><?php __("Owner's Billing Contact") ?></option>
                                        <option value="2"><?php __('Both') ?></option>
                                    </select>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
            </div-->



            <?php //************************balancenotice**********************************     ?>


            <div id="form_footer" class="bottom-buttons">
                <div style="margin: 0;float:none;" class="buttons pull-right">
                    <input type="submit" class="btn btn-primary" value="<?php __('Save') ?>">
                    <input type="reset"  class="btn btn-default" value="<?php __('Revert') ?>">
                </div>
                <div class="clearfix"></div>
            </div>
            <?php echo $form->end(); ?>
        </div>
    </div>
</div>
<script type="text/javascript">
    //特殊表单验证（只能为数字（Float））
    jQuery(document).ready(
        function() {

            // jQuery('#ClientName,#ClientLogin').xkeyvalidate({type:'strNum'});
            jQuery('#ClientAllowedCredit,#ClientNotifyAdminBalance').xkeyvalidate({type: 'Ip'});
            // jQuery('#ClientProfitMargin').xkeyvalidate({type:'Num'});
            //jQuery('input[maxLength=32]').xkeyvalidate({type:'Email'});
            jQuery('#ClientTaxId').xkeyvalidate({type: 'Num'});

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

            $("#ClientIsEmailInvoice").click(function() {
                if ($(this).attr('checked') == 'checked')
                {
                    $("#ClientEmailInvoice").show();
                }
                else
                {
                    $("#ClientEmailInvoice").hide();
                }
            });
        }
    );
</script>
<script>
    function notEqualAdmin(field, rules, i, options)
    {
        if (field.val() == "admin") {
            // this allows the use of i18 for the error msgs
            return 'This field can not be admin!';
        }
    }

</script>
<script type="text/javascript">
    jQuery('#ClientIsDailyBalanceNotification').disabled({id: '#ClientNotifyClientBalance,#ClientNotifyAdminBalance'});
</script>
<script type="text/javascript">
    jQuery(document).ready(function() {

        jQuery("#ClientIsAutoSummary").click(function () {
           if ($(this).is(':checked')) {
               $('.daily_usage_summary').show();
           } else {
               $('.daily_usage_summary').hide();
           }
        });
        $("#ClientIsAutoSummary, #ClientIsAutoBalance, #ClientDailyCdrGeneration").click(function () {
              let daily_checked = $('#ClientIsAutoSummary').is(':checked') ||  $('#ClientIsAutoBalance').is(':checked') || $('#ClientDailyCdrGeneration').is(':checked');
              if (daily_checked){
                  $('.daily_alert_on').show();
              }else{
                  $('.daily_alert_on').hide();
              }
        });
        $("#ClientIsDailyBalanceNotification").click(function() {
            if ($(this).attr('checked')) {
                $('.low_balance_config_table').show().find('input,select').removeAttr('disabled');
                $('.low_balance_config_table').prev().attr('colspan', 1);
            } else {
                $('.low_balance_config_table').hide().find('input,select').attr('disabled',true);
                $('.low_balance_config_table').prev().attr('colspan', 2);
            }
        });
        jQuery('#ClientForm').submit(function() {
            te = true;
            if (jQuery('#ClientTemplateName').val() == '') {
                jQuery('#ClientTemplateName').addClass('invalid');
                jGrowl_to_notyfy('Template name is required', {theme: 'jmsg-error'});
                te = false;
            }

            if (/\-/.test(jQuery('#ClientAllowedCredit').val())) {
                jQuery('#ClientAllowedCredit').addClass('invalid');
                jGrowl_to_notyfy('Allowed Credit cannot be a negative number!', {theme: 'jmsg-error'});
                te = false;
            }

            if (/[^0-9A-Za-z-\_\.\-\=\| \s]+/.test(jQuery('#ClientTemplateName').val())) {
                jQuery('#ClientTemplateName').addClass('invalid');
                jGrowl_to_notyfy('Name,allowed characters: a-z,A-Z,0-9,-,_,space,|, maximum of 16 characters in length!', {theme: 'jmsg-error'});
                te = false;
            }
            if (isNaN(jQuery('#ClientAllowedCredit').val())) {
                jQuery('#ClientAllowedCredit').addClass('invalid');
                jGrowl_to_notyfy(' Allowed Credit mast number', {theme: 'jmsg-error'});
                te = false;
            }
            if (jQuery('#ClientLogin').val() == '' && jQuery('#ClientIsPanelaccess').attr('checked')) {
                jQuery('#ClientLogin').addClass('invalid');
                jGrowl_to_notyfy('User Name is required', {theme: 'jmsg-error'});
                te = false;
            }
            if (jQuery('#ClientPassword').val() == '' && jQuery('#ClientIsPanelaccess').attr('checked')) {
                jQuery('#ClientPassword').addClass('invalid');
                jGrowl_to_notyfy('New Password is required', {theme: 'jmsg-error'});
                te = false;
            }
            if (jQuery('#ClientLogin').val() == 'admin') {
                jQuery('#ClientLogin').jGrowlError(' login name must not as admin');
                te = false;
            }
            /*
             if(isNaN(jQuery('#ClientProfitMargin').val())){
             jQuery('#ClientProfitMargin').addClass('invalid');
             jGrowl_to_notyfy(' Profit Margin Credit mast number',{theme:'jmsg-error'});
             te = false;
             }
             */

            if (jQuery('#ClientCurrencyId').val() == null) {
                jQuery('#ClientCurrencyId').addClass('invalid');
                jGrowl_to_notyfy('Currency must be created first!', {theme: 'jmsg-error'});
                te = false;
            }

            if (isNaN(jQuery('#ClientNotifyClientBalance').val()) && $('#ClientIsDailyBalanceNotification').attr('checked')) {
                jQuery('#ClientNotifyClientBalance').addClass('invalid');
                jGrowl_to_notyfy('Notify Carriers, must contain numeric characters only!', {theme: 'jmsg-error'});
                te = false;
            }
            /*
             if(isNaN(jQuery('#ClientNotifyAdminBalance').val())){
             jQuery('#ClientNotifyAdminBalance').addClass('invalid');
             jGrowl_to_notyfy('Notify admin: Notify Admin, must contain numeric characters only!',{theme:'jmsg-error'});
             te = false;
             }
             */
            if (!/^-?[0-9]+%?$/.test(jQuery('#ClientNotifyClientBalance').val()) && $('#ClientIsDailyBalanceNotification').attr('checked')) {
                jQuery('#ClientNotifyClientBalance').addClass('invalid');
                jGrowl_to_notyfy('Notify client must contain numeric characters only', {theme: 'jmsg-error'});
                te = false;
            }
            /*
             if(!jQuery.xkeyvalidate('#ClientNotifyAdminBalance',{type:'Ip'})||/^-{2,}[0-9]+/.test(jQuery('#ClientNotifyAdminBalance').val())){
             jQuery('#ClientNotifyAdminBalance').addClass('invalid');
             jGrowl_to_notyfy('Notify admin must contain numeric characters only',{theme:'jmsg-error'});
             te=false;
             }*/

            if (!isNaN(jQuery('#ClientAllowedCredit').val())) {
                if (!jQuery.xkeyvalidate('#ClientAllowedCredit', {type: 'Ip'}) || /^-{2,}[0-9]+/.test(jQuery('#ClientAllowedCredit').val())) {
                    jQuery('#ClientAllowedCredit').addClass('invalid');
                    jGrowl_to_notyfy('Notify client must contain numeric characters only', {theme: 'jmsg-error'});
                    te = false;
                }
            }
            /*
             if(!isNaN(jQuery('#ClientProfitMargin').val())){
             if(!jQuery.xkeyvalidate('#ClientProfitMargin',{type:'Ip'})||/^-{2,}[0-9]+/.test(jQuery('#ClientProfitMargin').val())){
             jQuery('#ClientProfitMargin').addClass('invalid');
             jGrowl_to_notyfy('Min. Profitability must contain numeric characters only',{theme:'jmsg-error'});
             te=false;
             }
             }
             */
            if (jQuery('#ClientSccBellow').val() != '') {
                if (!/\d+|\./.test(jQuery('#ClientSccBellow').val())) {
                    jQuery('#ClientSccBellow').addClass('invalid');
                    jGrowl_to_notyfy('Bellow, must contain numeric characters only!', {theme: 'jmsg-error'});
                    te = false;
                }
            }


            if (jQuery('#ClientSccPercent').val() != '') {
                if (!/\d+|\./.test(jQuery('#ClientSccPercent').val())) {
                    jQuery('#ClientSccPercent').addClass('invalid');
                    jGrowl_to_notyfy('Percent, must contain numeric characters only!', {theme: 'jmsg-error'});
                    te = false;
                }
            }



            if (jQuery('#ClientSccPercent').val() > 100) {
                jGrowl_to_notyfy('Percent, shuld not bigger than 100!', {theme: 'jmsg-error'});
                te = false;
            }


            if (jQuery('#ClientIncludeTax').attr('checked'))
            {
                if (!/\d+|\./.test(jQuery('#ClientTax').val())) {
                    jQuery('#ClientTax').addClass('invalid');
                    jGrowl_to_notyfy('ClientTax must contain numeric characters only!', {theme: 'jmsg-error'});
                    te = false;
                }
            }




            if (jQuery('#ClientSccCharge').val() != '') {
                if (!/\d+|\./.test(jQuery('#ClientSccCharge').val())) {
                    jQuery('#ClientSccCharge').addClass('invalid');
                    jGrowl_to_notyfy('Add Charge, must contain numeric characters only!', {theme: 'jmsg-error'});
                    te = false;
                }
            }

            if (jQuery('#ht-100012').attr('checked')) {
                if (jQuery('#ClientNotifyClientBalance').val() != '') {

                    /*
                     if(! /^\D+\.?\D+$/.test(jQuery('#ClientNotifyAdminBalance').val())){
                     jGrowl_to_notyfy('Notify admin: Notify Admin, must contain numeric characters only!',{theme:'jmsg-error'});
                     te= false;
                     }
                     */


                    if (!/^\D+\.?\D+$/.test(jQuery('#ClientNotifyClientBalance').val())) {
                        jGrowl_to_notyfy('Notify client Balance must contain numeric characters only!', {theme: 'jmsg-error'});
                        te = false;
                    }
                }
                if (Number(jQuery('#ClientNotifyClientBalance').val()) > 100 && $('#ClientNotifyClientBalanceType').val() == '1') {
                    jQuery('#ClientNotifyClientBalance').addClass('invalid');
                    jGrowl_to_notyfy('Notify client Balance must not greater than 100', {theme: 'jmsg-error'});
                    te = false;
                }
            }





//            if (/\D/.test(jQuery('#ClientTaxId').val())) {
//                jQuery('#ClientTaxId').addClass('invalid');
//                jGrowl_to_notyfy('Tax must nuber', {theme: 'jmsg-error'});
//                te = false;
//            }

            var name_data = jQuery.ajaxData("<?php echo $this->webroot ?>carrier_template/check_name/" + jQuery('#ClientName').val());
            name_data = name_data.replace(/\n|\r|\t/g, "");
            if (name_data == 'false') {
                jQuery.jGrowlError("The Client [" + jQuery('#ClientName').val() + "] already exists!");
                te = false;

            }


            if (!$('#dd').length) {
                $(document.body).append("<div id='dd'></div>");
            }
            var $dd = $('#dd');
            if ($("#ClientAutoInvoicing").attr('checked'))
            {
                var data = jQuery.ajaxData({
                    'url': "<?php echo $this->webroot ?>mailtmps/ajax_judge_invoice_mailtmp",
                    'type': 'POST',
                    'dataType': 'json'
                });
                if (data.flg == 1) //invoice mailtmp信息不全
                {
                    jGrowl_to_notyfy('If you do not fill complete mail content will cause automatic emailing of invoice to fail', {theme: 'jmsg-error'});
                    $dd.load('<?php echo $this->webroot ?>mailtmps/ajax_get_invoice_mailtmp',
                        {},
                        function(responseText, textStatus, XMLHttpRequest) {
                            $dd.dialog({
                                'width': '850px',
                                buttons: [{
                                    text: 'Submit',
                                    class: 'btn btn-primary',
                                    click: function() {
                                        $.ajax({
                                            url: "<?php echo $this->webroot ?>mailtmps/ajax_save_invoice_mailtmp",
                                            type: 'post',
                                            dataType: 'text',
                                            data: $('#post_invoice').serialize(),
                                            success: function(data) {
                                                if (data == 1)
                                                {
                                                    jGrowl_to_notyfy('save failed!', {theme: 'jmsg-error'});
                                                }
                                                else
                                                {
                                                    jGrowl_to_notyfy('save succeed!', {theme: 'jmsg-success'});
                                                }
                                                $dd.dialog("close");
                                            }
                                        });
                                    }
                                }]
                            });
                        }
                    );
                    te = false;
                }
            }
            return te;
        });
    });
</script>
<script type="text/javascript">
    jQuery(document).ready(function() {

        jQuery('#ClientIsDailyBalanceNotification').click(function() {
            if ($(this).attr('checked')) {
                $('#is_daily_balance_panel').show();
            } else {
                $('#is_daily_balance_panel').hide();
            }
        });

        jQuery('#daily_cdr_generation').change(function() {
            if ($(this).attr('checked')) {
                $('.daily_cdr_generation_panel').show();
            } else {
                $('.daily_cdr_generation_panel').hide();
            }
        }).trigger('change');

        jQuery('#include_payment_history').change(function() {
            if ($(this).val() == '0') {
                $('#include_payment_history_days_panel').hide();
            } else {
                $('#include_payment_history_days_panel').show();
            }
        });

        jQuery('#include_payment_history').change();

        jQuery('#ClientAutoInvoicing').change(function() {
            checkCB();
            //jQuery('#ClientInvoiceShowDetails, #ClientInvoiceJurisdictionalDetail').attr('checked', $(this).attr('checked'));
            if ($(this).attr('checked')) {
                $("#auto_invoice_table tbody tr td input").prop('disabled', false);
                $("#auto_invoice_table tbody tr td select").prop('disabled', false);
                jQuery('#ClientInvoiceShowDetails, #ClientInvoiceJurisdictionalDetail').attr('checked', true).val('1');
                jQuery("#ClientInvoiceZone").val("<?php echo $gmt; ?>");
            } else {
                $("#auto_invoice_table tbody tr td input").prop('disabled', true);
                $("#auto_invoice_table tbody tr td select").prop('disabled', true);
                jQuery('#ClientInvoiceShowDetails, #ClientInvoiceJurisdictionalDetail').val('1').removeAttr('checked');
            }
        });
        jQuery('#ClientIsPanelaccess').change(function() {
            checkCB();
        });
        checkCB();
        jQuery('#ClientLogin').attr('value', '');
        jQuery('#ClientPassword').attr('value', '');
    });
    jQuery(document).ready(function() {
        jQuery('#ClientPaymentTermId').change(function() {
            var $this = $(this);
            var payment_term_id = $(this).val();
            $.ajax({
                'url': '<?php echo $this->webroot ?>carrier_template/get_payment_term_type',
                'type': 'POST',
                'dataType': 'json',
                'data': {'payment_term_id': payment_term_id},
                'success': function(data) {
                    if (!!data[0] && data[0][0]['type'] == 1) {
                        $this.parent().parent().next().show();
                    } else {
                        $this.parent().parent().next().hide();
                    }
                }
            });
        }).trigger('change');


        jQuery('#ClientMode').change(function() {
            if (jQuery(this).val() == '2') {
                jQuery('#ClientAllowedCredit').parent().parent().show();
                jQuery('#unlimited_panel').show();
                $('#ht-100002').text('Allowed Credit');
            } else {
//                jQuery('#ClientAllowedCredit').val(0).next().hide();
                jQuery('#unlimited_panel').hide();
                jQuery('#ClientUnlimitedCredit').attr('checked', false);
                jQuery("#ClientAllowedCredit").show();
                jQuery(".money").show();
                $('#ht-100002').text('Test Credit');
            }
        });

        jQuery("#ClientUnlimitedCredit").click(function() {
            var checked = jQuery(this).attr('checked');
            jQuery("#ClientAllowedCredit").show();
            jQuery(".money").show();
            if (checked)
            {
                jQuery("#ClientAllowedCredit").hide();
                jQuery(".money").hide();
            }

        });

        $('#ClientIsInvoiceAccountSummary').change(function() {
            if ($(this).is(':checked')) {
                $('#ClientInvoiceUseBalanceType').show();
            } else {
                $('#ClientInvoiceUseBalanceType').hide();
            }
        }).trigger("change");

        $('#ClientAutoSummaryPeriod').change(function() {
            var $this = $(this);
            if ($this.val() == '24')
                $this.parents('tr').next().show();
            else
                $this.parents('tr').next().hide();
        }).trigger("change");

        jQuery('#ClientIncludeTax').change(function() {
            if (jQuery(this).attr('checked'))
            {
                $('#tax_area').show();
            }
            else
            {
                $('#tax_area').hide();
            }
        });
        jQuery('#ClientIncludeTax').change();

        jQuery('#ClientMode').change();
        jQuery('#ClientCurrencyId').change(
            function() {
                jQuery('span.money').html(jQuery(this).find('option:selected').html());
            }
        ).change();
    });

</script>
<script type="text/javascript">
    $(function(){
        $('span.collapse-toggle').live("click",function() {
            $(this).toggle(
                function () {
                    $(this).parent().next().css('overflow', 'visible');
                },
                function () {
                    $(this).parent().next().css('overflow', 'hidden');
                }
            ).trigger('click');
        })

        $(function () {
            jQuery("input[type=checkbox].toggle-next").click(function () {
                let subItem = jQuery(this).parent().parent().next();
                if(jQuery(this).is(':checked') == true) {
                    subItem.show('300');

                } else {
                    subItem.hide('300');
                }
            });
        });
    });
</script>



