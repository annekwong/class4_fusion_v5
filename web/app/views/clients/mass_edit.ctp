<style type="text/css">
    .form .value, .list-form .value{ text-align:left;}
    input{width: 220px;}
    fieldset{border:1px solid #eee;padding:10px;}
    .bodright20{margin-right: 20px;}
    table{width:100%;}
    .pull-right{text-align: center;}
    .money{dispaly:inline-block}
</style>
<?php echo $this->element("clients/title_edit") ?>
<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <?php if($_SESSION['login_type'] != 2): ?>
            <div class="widget-head">
                <h4 class="heading">Basic Info</h4>
            </div>
        <?php endif; ?>

        <div class="widget-body">
                <?php echo $form->create('Client', array('action' => "mass_edit/{$this->params['pass'][0]}", 'id' => 'ClientEdit')); ?>
            <input type="hidden" name="order_user_id" value="" />

            <?php echo $form->input('client_id', array('label' => false, 'div' => false, 'type' => 'hidden', 'value' => '')) ?>
            <input type="hidden" value="" name="client_id"/>

            <div class="widget">
                <div class="widget-head"><h4 class="heading"><?php __('Basic Info') ?></h4></div>
                <div class="widget-body">
                    <table class="form footable table table-striped  tableTools table-bordered  table-white table-primary default footable-loaded">
                        <tr>
                            <td class="align_right padding-r20" style="display: none;"><?php echo __('Name') ?></td>
                            <td style="display: none;">
                                <?php echo $form->input('name', array('label' => false, 'div' => false, 'class' => 'validate[required,custom[onlyLetterNumberLineSpace],funcCall[notEqualAdmin]]', 'type' => 'text', 'maxLength' => 500, 'value' => '')) ?>
                            </td>
                            <td class="align_right padding-r20"><?php echo __('status') ?></td>
                            <td>
                                <?php
                                $st = array('true' => __('Active', true), 'false' => __('Inactive', true));

                                $tmp_s = 'false';
                                echo $form->input('status', array('options' => $st, 'label' => false, 'div' => false, 'type' => 'select', 'selected' => $tmp_s))
                                ?>

                            </td>
                        </tr>
                        <tr>
                            <td class="align_right padding-r20"><span id="ht-100001" class="helptip" rel="helptip"><?php echo __('mode') ?></span><span id="ht-100001-tooltip" class="tooltip" style="z-index: auto;">
                                    If Prepaid selected - this client`s Balance+Credit value will be checked on RADIUS authorization, if Postpaid selected -
                                    RADIUS authorization check is disabled</span></td>

                            <td>
                                <?php
                                $st = array('1' => __('Prepaid', true), '2' => __('postpaid', true));
                                echo $form->input('mode', array('options' => $st, 'label' => false, 'div' => false, 'type' => 'select', 'selected' => '2'))
                                ?>
                            </td>
                            <?php if($_SESSION['login_type'] == 2): ?>
                                <td></td>
                                <td></td>
                            <?php else: ?>
                                <td class="align_right padding-r20"><?php echo __('currency') ?></td>
                                <td>
                                    <?php echo $form->input('currency_id', array('options' => $currency, 'label' => false, 'div' => false, 'type' => 'select', 'class' => '', 'selected' => false));
                                    ?>

                                </td>
                            <?php endif; ?>

                        </tr>
                        <?php
                        $project_name = Configure::read('project_name');
                        if ($project_name == 'exchange')
                        {
                            ?>
                            <tr>
                                <td class="align_right padding-r20"><?php echo __('Service Charge', true); ?></td>
                                <td>
                                    <?php echo $form->input('service_charge_id', array('options' => $service_charge, 'label' => false, 'div' => false, 'selected' => '', 'type' => 'select')); ?>
                                </td>
                            </tr>
                        <?php } ?>

                        <?php if ($_SESSION['login_type'] == 2 || isset($_SESSION['role_menu']['Payment_Invoice']['modify_credit_limit']) && $_SESSION['role_menu']['Payment_Invoice']['modify_credit_limit'] == 1): ?>
                            <tr>
                                <td class="align_right padding-r20"><span id="ht-100002" class="helptip" rel="helptip"><?php echo __('allowedcredit') ?></span></td>
                                <td style="text-align: left;">
                                    <span id="unlimited_panel">
                                        <?php __('Unlimited') ?>
                                        <?php echo $form->input('unlimited_credit', array('class' => 'in-decimal input in-checkbox', 'label' => false, 'div' => false, 'type' => 'checkbox', 'checked' => 'false')) ?>
                                    </span>
                                    <?php
                                        $credit_flg = 'inline-block';
                                    ?>
                                    <?php
                                    echo $form->input('allowed_credit', array('label' => false, 'div' => false, 'type' => 'text',
                                        'value' => round(0, 5), 'class' => 'validate[min[0],custom[number]]', 'maxlength' => '26', 'style' => "width:100px;display:{$credit_flg}"));
                                    ?>
                                    <span class="money" style="display:<?php echo $credit_flg; ?>"></span>


                                </td>
                                <td class="align_right padding-r20"><?php echo __('Offset Balance') ?></td>
                                <td>
                                    <?php echo $form->input('offset_balance', array('class' => 'in-decimal input in-checkbox', 'label' => false, 'type' => 'checkbox', 'checked' => 'false')) ?>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php
                            echo $form->input('allowed_credit', array('label' => false, 'div' => false, 'type' => 'hidden',
                                'value' => round(0, 5),
                                'class' => 'in-decimal input in-text', 'maxlength' => '26', 'style' => 'width:100px'));
                            ?>
                        <?php endif; ?>
                        <?php if ($_SESSION['login_type'] == 2 || $_SESSION['role_menu']['Payment_Invoice']['modify_min_profit'] == 1): ?>
                        <?php endif; ?>
                        <tr>
                            <td class="align_right padding-r20"><?php echo __('CPS') ?></td>
                            <td>
                                <?php echo $form->input('cps_limit', array('label' => false, 'div' => false, 'type' => 'text', 'maxLength' => 16, 'value' => '')) ?>
                            </td>
                            <td class="align_right padding-r20"><?php echo __('Call limit') ?></td>
                            <td>
                                <?php echo $form->input('call_limit', array('label' => false, 'div' => false, 'type' => 'text', 'maxLength' => 16, 'value' => '')) ?>
                            </td>
                        </tr>

                    </table>
                </div>
            </div>

            <div class="widget" data-toggle="collapse-widget" data-collapse-closed="true">
                <div class="widget-head"><h4 class="heading"><?php __('Basic Setting') ?></h4></div>
                <div class="widget-body">
                    <table class="form footable table table-striped  tableTools table-bordered  table-white table-primary default footable-loaded">
                        <thead></thead>
                        <tr>
                            <td class="align_right padding-r20" width="50%"><?php echo __('Send Trunk Update') ?> </td>
                            <td>
                                <?php
                                $au = 'false';
                                echo $form->checkbox('is_send_trunk_update', array('checked' => $au));
                                ?>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>


            <div class="widget" data-toggle="collapse-widget" data-collapse-closed="true">
                <div class="widget-head"><h4 class="heading"><?php __('Fraud Detection') ?></h4></div>
                <div class="widget-body">
                    <table class="form footable table table-striped  tableTools table-bordered  table-white table-primary default footable-loaded">
                        <tr>
                            <td class="align_right padding-r20"><?php __('Daily limit'); ?></td>
                            <td  style="text-align: left;">
                                <input type="text" name="data[Client][daily_limit]" class='validate[custom[onlyNumberSp]]' 
                                	value="" />
                            </td>
                            <td class="align_right padding-r20"><?php __('Hourly limit'); ?></td>
                            <td>
                                <input type="text" name="data[Client][hourly_limit]" class='validate[custom[onlyNumberSp]]' 
                                	value="" />
                                <!--<input type="text" name="" class='validate[custom[onlyNumberSp]]' />-->
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="widget" data-toggle="collapse-widget" data-collapse-closed="true">
                <div class="widget-head"><h4 class="heading"><?php __('Automatic Report') ?></h4></div>
                <div class="widget-body">
                    <table class="form footable table table-striped  tableTools table-bordered  table-white table-primary default footable-loaded">
                        <tr>
                            <td class="align_right padding-r20"><?php __('Period') ?> </td>
                            <td>
                                <?php
                                $send_periods = array(
                                    -15 => '15M',
                                    -30 => '30M',
                                    1 => '1H',
                                    2 => '2H',
                                    4 => '4H',
                                    6 => '6H',
                                    8 => '8H',
                                    12 => '12H',
                                    24 => '24H',
                                );

                                echo $form->input('auto_summary_period', array('options' => $send_periods, 'label' => false, 'div' => false, 'type' => 'select', 'selected' => '0' ));
                                ?>
                            </td>
                            <td class="align_right padding-r20"><?php __('Send Hour') ?> </td>
                            <td>
                                <?php
                                $send_times = array();
                                for ($i = 0; $i <= 23; $i++)
                                {
                                    $send_times[$i] = $i . ":00";
                                }
                                echo $form->input('auto_summary_hour', array('options' => $send_times, 'label' => false, 'div' => false, 'type' => 'select', 'selected' => '0'));
                                ?>
                            </td>
                        </tr>

                        <tr>
                            <td class="align_right padding-r20"><?php __('Time zone') ?> </td>
                            <td style="text-align:left;">
                                <?php
                                $azone_arr = array();
                                for ($i = -12; $i <= 12; $i++)
                                {
                                    $zone_str = $i < 0 ? sprintf("-%02d:00", 0 - $i) : sprintf("+%02d:00", $i);
                                    $azone_arr[$zone_str] = 'GMT ' . $zone_str;
                                }
                                echo $form->input('auto_send_zone', array('options' => $azone_arr, 'label' => false, 'div' => false, 'type' => 'select', 'selected' =>  '+00:00' ));
                                ?>
                            </td>
                            <td class="align_right padding-r20"><?php __('Include CDR') ?> </td>
                            <td>
                                <?php echo $form->input('auto_summary_include_cdr', array('class' => 'in-decimal input in-checkbox', 'label' => false, 'div' => false, 'type' => 'checkbox', 'checked' => '')) ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="align_right padding-r20"><?php __('Recipient') ?> </td>
                            <td>
                                <?php
                                echo $form->input('auto_daily_balance_recipient', array('options' => array(__("Partner's Billing Contact", true), __("Owner's Billing Contact", true), __('Both', true)), 'label' => false, 'div' => false, 'type' => 'select', 'selected' => ''));
                                ?>
                            </td>
                            <td class="align_right padding-r20"><?php __('Send Daily Usage Summary') ?> </td>
                            <td  style="text-align: left;">
                                <?php
                                $au = 'false';
                                ?>
                                <?php echo $form->input('is_auto_summary', array('class' => 'in-decimal input in-checkbox', 'label' => false, 'div' => false, 'type' => 'checkbox', 'checked' => $au)) ?>
                                <br />
                                <?php __('Non-Zero Only') ?>:
                                <?php
                                $au = 'false';
                                ?>
                                <?php echo $form->input('auto_summary_not_zero', array('class' => 'in-decimal input in-checkbox', 'label' => false, 'div' => false, 'type' => 'checkbox', 'checked' => $au)) ?>
                                <br />
                                Group By:
                                <?php
                                echo $form->input('auto_summary_group_by', array('options' => array(__('By Country', TRUE), __('By Code Name', TRUE), __('By Code', TRUE)), 'label' => false, 'div' => false, 'type' => 'select', 'selected' => ''));
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="align_right padding-r20"><?php __('Daily Balance Summary') ?> </td>
                            <td  style="text-align: left;">
                                <?php
                               $au = 'false';
                                ?>
                                <?php echo $form->input('is_auto_balance', array('class' => 'in-decimal input in-checkbox', 'label' => false, 'div' => false, 'type' => 'checkbox', 'checked' => $au)) ?>
                                &nbsp;
                                <?php __('Number of days'); ?>:
                                <input type="text" name="data[Client][numer_of_days_balance]" style="width:30px;" 
                                 value="" />
                            </td>
                            <td class="align_right padding-r20"><?php __('Daily CDR Generation') ?> </td>
                            <td><input type="checkbox" name="data[Client][daily_cdr_generation]" id="daily_cdr_generation"   /></td>
                        </tr>
                        <tr class="daily_cdr_generation_panel">
                            <td class="align_right padding-r20"><?php __('GMT') ?> </td>
                            <td>
                                <?php
                                $azone_arr = array();
                                for ($i = -12; $i <= 12; $i++)
                                {
                                    $zone_str = $i < 0 ? sprintf("-%02d:00", 0 - $i) : sprintf("+%02d:00", $i);
                                    $azone_arr[$zone_str] = 'GMT ' . $zone_str;
                                }
                                echo $form->input('daily_cdr_generation_zone', array('options' => $azone_arr, 'label' => false, 'div' => false, 'type' => 'select', 'selected' => ''));
                                ?>
                            </td>
                            <td class="align_right padding-r20"><?php __('CDR Type') ?> </td>
                            <td>
                                <?php
                                echo $form->input('daily_cdr_generation_type', array('options' => array('Incoming Calls', 'Outgoing Calls', 'Both'), 'label' => false, 'div' => false, 'type' => 'select', 'selected' => ''));
                                ?>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>



            <div class="widget" data-toggle="collapse-widget" data-collapse-closed="true">
                <div class="widget-head"><h4 class="heading"><?php __('Short Call Charge') ?></h4></div>
                <div class="widget-body">
                    <div>
                        <?php __('If') ?> <?php echo $form->input('scc_percent', array('label' => false, 'style' => 'width:25px;', 'div' => false, 'value' =>'', 'class' => 'validate[max[100],custom[onlyNumberSp]]')) ?> %
                        <?php __('overall invoice minute is below or equal to') ?> <?php echo $form->input('scc_bellow', array('options' => array('6' => '6', '12' => '12', '18' => '18', '24' => '24', '30' => '30'), 'label' => false, 'div' => false, 'type' => 'select', 'selected' => $post['Client']['scc_bellow'], 'style' => 'width:auto;')) ?>
                        <?php __('second') ?>, <br><?php __('then an additional charge of') ?> <?php echo $form->input('scc_charge', array('label' => false, 'style' => 'width:70px;', 'div' => false, 'class' => 'validate[custom[number]]', 'value' => number_format(0, 6))) ?> <?php __('will be applied to each call') ?>
                        <?php echo $form->input('scc_type', array('options' => array('0' => __('meeting the short duration defined above', true), '1' => __('that exceed the defined percentage', true)), 'label' => false, 'div' => false, 'type' => 'select', 'selected' => '', 'style' => 'width:auto;')); ?>
                    </div>
                </div>
            </div>




            <div class="widget" data-toggle="collapse-widget" data-collapse-closed="true">
                <div class="widget-head">
                    <h4 class="heading">
                        <?php
                        $au = 'false';
                        echo $form->checkbox('auto_invoicing', array('checked' => $au));
                        ?> <?php __('Auto Invoice') ?>
                    </h4>
                </div>
                <div class="widget-body">
                    <table class="form footable table table-striped  tableTools table-bordered  table-white table-primary default footable-loaded">
                        <thead></thead>
                        <tr>
                            <td class="align_right padding-r20"><?php echo __('paymentterm') ?> </td>

                            <td>
                                <?php echo $form->input('payment_term_id', array('options' => $paymentTerm, 'label' => false, 'div' => false, 'type' => 'select', 'selected' => ''));
                                ?>

                            </td>
                            <td class="align_right padding-r20"><?php __('Starting From') ?></td>
                            <td>
                                <?php
                                echo $form->input('invoice_start_from', array('onfocus' => "WdatePicker({dateFmt:'yyyy-MM-dd'});", 'value' => '', 'class' => "input in-text wdate", 'readonly' => true, 'label' => false, 'div' => false, 'type' => 'text'));
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="align_right padding-r20"><?php echo __('Invoice Format') ?> </td>

                            <td>
                                <?php
                                $route_type = array('1' => 'PDF', '2' => 'Word'); //,'2'=>'Excel','3'=>'HTML');
                                echo $form->input('invoice_format', array('options' => $route_type, 'label' => false, 'div' => false, 'type' => 'select', 'selected' =>''));
                                ?>

                            </td>
                        </tr>
                        <tr>
                            <td class="align_right padding-r20"><?php echo __('Time zone') ?> </td>

                            <td>
                                <?php
                                $zone_arr = array();
                                for ($i = -12; $i <= 12; $i++)
                                {
                                    $zone_str = $i < 0 ? sprintf("-%02d:00", 0 - $i) : sprintf("+%02d:00", $i);
                                    $zone_arr[$zone_str] = 'GMT ' . $zone_str;
                                }
                                echo $form->input('invoice_zone', array('options' => $zone_arr, 'label' => false, 'div' => false, 'type' => 'select', 'selected' => '+00:00'));
                                ?>

                            </td>
                        </tr>
                        <tr>
                            <td class="align_right padding-r20"><?php echo __('No Invoice for Zero Traffic') ?> </td>

                            <td>
                                <?php
                                echo $form->input('invoice_zero', array('options' => array(1 => 'Yes', 0 => 'No'), 'label' => false, 'div' => false, 'type' => 'select', 'selected' => 0));
                                ?>

                            </td>
                            <td class="align_right padding-r20"><?php echo __('CDR Compress Format') ?> </td>
                            <td>
                                <?php
                                //$route_type=array('1'=>'Excel XLS','2'=>'CSV');
                                $route_type = array('3' => 'zip', '4' => 'tar.gz');
                                echo $form->input('cdr_list_format', array('options' => $route_type, 'label' => false, 'div' => false, 'type' => 'select', 'selected' => ''));
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="align_right padding-r20"><?php __('Rate Decimal Place'); ?> </td>
                            <td>
                                <?php
                                $decimal_places = range(1, 10);
                                $decimal_places = array_combine($decimal_places, $decimal_places);
                                ?>
                                <?php
                                echo $form->input('decimal_place', array('options' => $decimal_places,
                                    'style' => '', 'value' => '', 'label' => false, 'div' => false))
                                ?>
                            </td>
                            <td class="align_right padding-r20"><?php __('Rate Value'); ?> </td>
                            <td>
                                <?php
                                echo $form->input('rate_value', array('options' => array(__('Average', true), __('Actual', true)),
                                    'style' => '', 'value' => '', 'label' => false, 'div' => false))
                                ?>
                            </td>
                        </tr>



                        <tr>
                            <td class="align_right padding-r20"><?php __('Auto Send Invoice'); ?> </td>
                            <td>
                                <?php $au = 'false'; ?>
                                <?php echo $form->checkbox('email_invoice', array('checked' => $au)); ?>
                            </td>
                            <td class="align_right padding-r20"><?php __('Include Available Credit'); ?> </td>
                            <td>
                                <?php $au = 'false'; ?>
                                <?php echo $form->checkbox('include_available_credit', array('checked' => $au)); ?>
                            </td>
                        </tr>

                        <tr>
                            <td class="align_right padding-r20"><?php __('Send invoice as link in email'); ?> </td>
                            <td>
                                <?php $au = 'false'; ?>
                                <?php echo $form->checkbox('is_send_as_link', array('checked' => $au)); ?>
                            </td>
                            <td class="align_right padding-r20"><?php  ?> </td>
                            <td>
                            </td>
                        </tr>
                        <tr>
                            <td class="align_right padding-r20"><?php __('Include Account Detail'); ?> </td>
                            <td>
                                <?php $au = 'false'; ?>
                                <?php echo $form->checkbox('is_invoice_account_summary', array('checked' => $au)); ?>
                                <?php
                                echo $form->input('invoice_use_balance_type', array('options' => array(0 => 'use actual balance', 1 => 'use mutual balance'), 'label' => false, 'div' => false, 'type' => 'select', 
                                	'selected' => '', 'style' => 'width:120px;'));
                                ?>
                            </td>
                            <td class="align_right padding-r20"><?php __('Show Daily Usage'); ?> </td>
                            <td>
                                <?php $au = 'false'; ?>
                                <?php echo $form->checkbox('is_show_daily_usage', array('checked' => $au)); ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="align_right padding-r20"><?php __('Short Duration Call Surcharge detail'); ?> </td>
                            <td>
                                <?php echo $form->checkbox('is_short_duration_call_surcharge_detail', array('checked' => '')); ?>
                            </td>
                            <td class="align_right padding-r20"><?php __('Include Summary of Payments'); ?> </td>
                            <td>
                                <?php echo $form->checkbox('invoice_include_payment', array('checked' => false)); ?>
                            </td>
                        </tr>


                        <tr>
                            <td class="align_right padding-r20"><?php __('Show Detail by Trunk'); ?></td>
                            <td>
                                <?php echo $form->checkbox('is_show_detail_trunk', array('checked' => false)); ?>
                            </td>
                            <td class="align_right padding-r20"><?php __('Show Total by Trunk'); ?></td>
                            <td>
                                <?php echo $form->checkbox('is_show_total_trunk', array('checked' => false)); ?>
                            </td>
                        </tr>


                        <tr>
                            <td class="align_right padding-r20"><?php __('Show Code Summary (top 10)'); ?></td>
                            <td>
                                <?php echo $form->checkbox('is_show_code_100', array('checked' => false)); ?>
                            </td>
                            <td class="align_right padding-r20"><?php __('Show Traffic Analysis by Code Name'); ?></td>
                            <td>
                                <?php echo $form->checkbox('is_show_code_name', array('checked' => false)); ?>
                            </td>
                        </tr>


                        <tr>
                            <td class="align_right padding-r20"><?php __('Show Traffic Analysis by Country'); ?></td>
                            <td>
                                <?php echo $form->checkbox('is_show_country', array('checked' => false)); ?>
                            </td>
                            <td class="align_right padding-r20"><?php __('Show Calls By Date Summary'); ?></td>
                            <td>
                                <?php echo $form->checkbox('is_show_by_date', array('checked' => false)); ?>
                            </td>
                        </tr>


                        <tr>
                            <td class="align_right padding-r20">
                                <?php echo __('Include CDR link in email') ?>
                            </td>
                            <td>
                                <?php
                                echo $form->checkbox('attach_cdrs_list', array('checked' => false));
                                ?>
                            </td>
                            <td class="align_right padding-r20">
                                <?php echo __('Show Detail by Code Name') ?>
                            </td>
                            <td>
                                <?php
                                echo $form->checkbox('invoice_show_details', array('checked' => false));
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="align_right padding-r20">
                                <?php echo __('Show Jurisdictional Detail') ?>
                            </td>
                            <td>
                                <?php
                                echo $form->checkbox('invoice_jurisdictional_detail', array('checked' => false));
                                ?>
                            </td>
                        </tr>


                        <tr>
                            <td class="align_right padding-r20"><?php __('Usage Detail Fields'); ?> </td>
                            <td>
                                <?php
                                $usage_detail_fields = false;
                                ?>
                                <select name="data[Client][usage_detail_fields][]" multiple="multiple">
                                    <!--<option value="code_name">Code Name</option>-->
                                    <option value="completed_calls" <?php if (array_search('completed_calls', $usage_detail_fields) !== false) echo 'selected="selected"'; ?>><?php __('Completed Calls') ?></option>
                                    <option value="interstate_minute" <?php if (array_search('interstate_minute', $usage_detail_fields) !== false) echo 'selected="selected"'; ?>><?php __('Interstate Minute') ?></option>
                                    <option value="intrastate_minute" <?php if (array_search('intrastate_minute', $usage_detail_fields) !== false) echo 'selected="selected"'; ?>><?php __('Intrastate Minute') ?></option>
                                    <option value="indeterminate_minute" <?php if (array_search('indeterminate_minute', $usage_detail_fields) !== false) echo 'selected="selected"'; ?>><?php __('Indeterminate Minute') ?></option>
                                    <option value="total_minutes" <?php if (array_search('total_minutes', $usage_detail_fields) !== false) echo 'selected="selected"'; ?>><?php __('Total Minutes') ?></option>
                                    <option value="total_charges" <?php if (array_search('total_charges', $usage_detail_fields) !== false) echo 'selected="selected"'; ?>><?php __('Total Charges') ?></option>
                                </select>
                            </td>
                            <td class="align_right padding-r20"><?php __('Break Down by Rate Table'); ?> </td>
                            <td>
                                <?php
                                    $au = 'false';
                                    $select_display = "none";
                                ?>
                                <?php echo $form->checkbox('is_breakdown_by_rate_table', array('checked' => $au)); ?>
                                <?php
                                echo $form->input('breakdown_by_rate_table', array('options' => array(1 => 'Breakdown A-Z Rate Table by Destination', 2 => 'Breakdown US Rate Table by Jurisdiction'), 'label' => false, 'div' => false, 'type' => 'select', 'selected' => false, 'style' => 'width:120px;display:' . $select_display . ';'));
                                ?>
                            </td>
                        </tr>



                        <tr>
                            <td style="display: none;" class="align_right padding-r20"><?php __('Require Vendor Invoice'); ?> </td>
                            <td>
                                <?php
                                    $au = 'false';
                                    $select_display = "none";
                                ?>
                                <?php echo $form->checkbox('is_vendor_invoice', array('checked' => $au)); ?>
                            </td>
                            <td class="align_right padding-r20"><?php __('Vendor'); ?>&nbsp;<?php echo __('paymentterm') ?> </td>
                            <td>
                                <?php echo $form->input('vendor_payment_term_id', array('options' => $paymentTerm, 'label' => false,
                                    'div' => false, 'type' => 'select', 'selected' => false));
                                ?>

                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="clearfix"></div>
            <div class="widget" data-toggle="collapse-widget" data-collapse-closed="true">
                <div class="widget-head"><h4 class="heading">
                        <?php echo $form->checkbox('is_panelaccess', array('checked' => false)) ?>
                        <?php __('Carrier Self-Service Portal') ?>
                    </h4>
                    <input type="hidden" name="is_send_welcom_letter" id="is_send_welcom_letter" value=""/>
                </div>
                <div class="widget-body">
                    <table class="form footable table table-striped  tableTools table-bordered  table-white table-primary default footable-loaded">

                        <tr>
                            <td class="align_right padding-r20"><?php echo __('Username') ?> </td>
                            <td>
                                <?php echo $form->input('login', array('label' => false, 'class' => 'validate[required]', 'maxLength' => '256', 'div' => false, 'type' => 'text', 'value' => '')) ?>
                            </td>
                            <td class="align_right padding-r20"><?php echo __('Password') ?> </td>
                            <td>
                                <?php echo $form->input('password', array('label' => false, 'div' => false, 'type' => 'password', 'maxLength' => '16', 'value' => '')); ?></td>
                        </tr>
                        <tr>
                            <td class="align_right padding-r20"><?php echo __('Permission') ?> </td>
                            <td class="value" colspan="3">
                                <?php echo $this->element('portal/add_permission_div',array('check_data' => [])); ?>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>


            <?php echo $this->element('clients/low_balance_notification_div'); ?>


            <?php
            if(($_SESSION['login_type'] == 2 && $_SESSION['sst_agent_info']['Agent']['edit_permission']) || $_SESSION['role_menu']['Management']['clients']['model_w'])
            {
                ?>
                <div id="form_footer" class="heading-button">
                    <div style="margin: 0;float: none;" class="buttons pull-right">
                        <input type="submit" class="btn btn-primary" value="<?php __('Submit') ?>">
                        <input type="reset"  class="btn btn-default" value="<?php __('Revert') ?>">
                    </div>
                    <div class="clearfix"></div>
                </div>
            <?php } ?>
            <?php echo $form->end(); ?>
        </div>
    </div>
</div>
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
    });
</script>
<script type="text/javascript">
    //特殊表单验证（只能为数字（Float））
    jQuery(document).ready(
        function () {

            jQuery("#ClientAutoInvoicing").click(function () {
                var checked = jQuery(this).attr('checked');
                var parent_is_close = jQuery(this).parent().parent().parent().attr('data-collapse-closed');
                if (checked == "checked" && parent_is_close == "true")
                {
                    jQuery(this).parent().next().click();
                }
                else if (checked != "checked" && parent_is_close == 'false')
                {
                    jQuery(this).parent().next().click();
                }
            });
            jQuery("#ClientIsPanelaccess").click(function () {
                var checked = jQuery(this).attr('checked');
                var parent_is_close = jQuery(this).parent().parent().parent().attr('data-collapse-closed');
                if (checked == "checked" && parent_is_close == "true")
                {
                    jQuery(this).parent().next().click();
                }
                else if (checked != "checked" && parent_is_close == 'false')
                {
                    jQuery(this).parent().next().click();
                }
            });
            jQuery("#ClientIsDailyBalanceNotification").click(function () {
                var checked = jQuery(this).attr('checked');
                var parent_is_close = jQuery(this).parent().parent().parent().attr('data-collapse-closed');
                if (checked == "checked" && parent_is_close == "true")
                {
                    jQuery(this).parent().next().click();
                }
                else if (checked != "checked" && parent_is_close == 'false')
                {
                    jQuery(this).parent().next().click();
                }
            });

            jQuery('#ClientAllowedCredit,#ClientNotifyAdminBalance').xkeyvalidate({type: 'Ip'});
            //jQuery('#ClientProfitMargin').xkeyvalidate({type:'Num'})
            //	jQuery('#ClientEmail,#ClientNocEmail,#ClientBillingEmail,#ClientRateEmail').xkeyvalidate({type:'Email'});
            // jQuery('#ClientName,#ClientCompany').xkeyvalidate({type: 'strNum'});
            jQuery('#ClientTaxId').xkeyvalidate({type: 'Num'});
            if ($('#ClientIsDailyBalanceNotification').attr('checked')) {
                $('#is_daily_balance_panel').show();
                $("#ClientNotifyClientBalance").removeAttr('disabled');
            } else {
                $("#ClientNotifyClientBalance").attr('disabled', 'disabled');
                $('#is_daily_balance_panel').hide();
            }
            $("#ClientIsBreakdownByRateTable").click(function () {
                if ($(this).attr('checked') == 'checked')
                {
                    $("#ClientBreakdownByRateTable").show();
                }
                else
                {
                    $("#ClientBreakdownByRateTable").hide();
                }
            });
            //jQuery('#ClientIsDailyBalanceNotification').disabled({id: '#ClientNotifyClientBalance,#ClientNotifyAdminBalance'});
        }
    );
</script>
<script type="text/javascript">
    jQuery(document).ready(function () {
        jQuery('#ClientPaymentTermId').change(function () {
            var $this = $(this);
            var payment_term_id = $(this).val();
            $.ajax({
                'url': '<?php echo $this->webroot ?>clients/get_payment_term_type',
                'type': 'POST',
                'dataType': 'json',
                'data': {'payment_term_id': payment_term_id},
                'success': function (data) {
                    if (!!data[0] && data[0][0]['type'] == 1) {
                        $this.parent().parent().next().show();
                    } else {
                        $this.parent().parent().next().hide();
                    }
                }
            });
        }).trigger('change');


        jQuery('#ClientIsDailyBalanceNotification').click(function () {
            if ($(this).attr('checked')) {
                $('#is_daily_balance_panel').show();
                $("#ClientNotifyClientBalance").removeAttr('disabled');
            } else {
                $("#ClientNotifyClientBalance").attr('disabled', 'disabled');
                $('#is_daily_balance_panel').hide();
            }
        });

        check_welcom = false;

        jQuery('#ClientEdit').submit(function () {
            te = true;
            if (!$('#dd').length) {
                $(document.body).append("<div id='dd'></div>");
            }
            var $dd = $('#dd');
            if ($("#ClientAutoInvoicing").attr('checked'))
            {
                var data = jQuery.ajaxData({
                    'url': "<?php echo $this->webroot ?>mailtmps/ajax_judge_invoice_mailtmp",
                    'type': 'POST',
                    'dataType': 'json',
                });
                if (data.flg == 1) //invoice mailtmp信息不全
                {
                    jGrowl_to_notyfy('If you do not fill complete mail content will cause automatic emailing of invoice to fail', {theme: 'jmsg-error'});
                    $dd.load('<?php echo $this->webroot ?>mailtmps/ajax_get_invoice_mailtmp',
                        {},
                        function (responseText, textStatus, XMLHttpRequest) {
                            $dd.dialog({
                                'width': '850px',
                                buttons: [{
                                    text: 'Submit',
                                    class: 'btn btn-primary',
                                    click: function () {
                                        $.ajax({
                                            url: "<?php echo $this->webroot ?>mailtmps/ajax_save_invoice_mailtmp",
                                            type: 'post',
                                            dataType: 'text',
                                            data: $('#post_invoice').serialize(),
                                            success: function (data) {
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

            if (jQuery('#ClientIsPanelaccess').attr('checked')) {
                var data = jQuery.ajaxData({'url': "<?php echo $this->webroot ?>clients/check_login/<?php echo array_keys_value($this->params, 'pass.0') ?>?login=" + jQuery('#ClientLogin').val(), 'type': 'POST'});
                if (data.indexOf('false') !== -1) {
                    jQuery.jGrowlError("login name is repeat!");
                    te = false;
                }
            }
            return te;
        });
    });
</script>
<script type="text/javascript">
    <!--
    //ClientPaymentTermId,#ClientInvoiceFormat,#ClientAttachCdrsList,#ClientCdrListFormat,#ClientLastInvoiced
    jQuery(document).ready(function () {

        jQuery("#ClientUnlimitedCredit").click(function () {
            var unlimit = jQuery(this).attr('checked');
            jQuery("#ClientAllowedCredit").show();
            jQuery(".money").show();
            if (unlimit == 'checked')
            {
                jQuery("#ClientAllowedCredit").hide();
                jQuery(".money").hide();
            }
        });

        jQuery('#ClientMode').change(function () {
            if (jQuery(this).val() == '2') {
                jQuery('#ClientAllowedCredit').parent().parent().show();
                jQuery('#unlimited_panel').show();
            } else {
                jQuery('#ClientAllowedCredit').val(0).parent().parent().hide();
                jQuery('#unlimited_panel').hide();
                jQuery('#ClientUnlimitedCredit').attr('checked', false);
                jQuery("#ClientAllowedCredit").show();
                jQuery(".money").show();
            }
        });


        $('#ClientAutoSummaryPeriod').change(function () {
            var $this = $(this);
            if ($this.val() == '24')
                $this.parents('tr').next().show();
            else
                $this.parents('tr').next().hide();
        }).trigger("change");

        $('#ClientIsInvoiceAccountSummary').change(function () {
            if ($(this).is(':checked')) {
                $('#ClientInvoiceUseBalanceType').show();
            } else {
                $('#ClientInvoiceUseBalanceType').hide();
            }
        }).trigger("change");

        jQuery('#ClientIncludeTax').change(function () {
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
        jQuery('#include_payment_history').change(function () {
            if ($(this).val() == '0') {
                $('#include_payment_history_days_panel').hide();
            } else {
                $('#include_payment_history_days_panel').show();
            }
        });
        jQuery('#include_payment_history').change();
        jQuery('#ClientMode').change();
        jQuery('#daily_cdr_generation').change(function () {
            if ($(this).attr('checked')) {
                $('.daily_cdr_generation_panel').show();
            } else {
                $('.daily_cdr_generation_panel').hide();
            }
        }).trigger('change');
        jQuery('#ClientAutoInvoicing').change(function () {
            checkCB();
        });
        jQuery('#ClientIsPanelaccess').change(function () {
            checkCB();
        });
        checkCB();
        jQuery('#ClientCurrencyId').change(
            function () {
                jQuery('span.money').html(jQuery(this).find('option:selected').html());
            }
        ).change();
    });
    //-->
</script>
