<style type="text/css">
    .form .value, .list-form .value{ text-align:left;}
    input{width: 220px;}
    fieldset{border:1px solid #eee;padding:10px;}
    .bodright20{margin-right: 20px;}
    table{width:100%;}
    .pull-right{text-align: center;}
    /*.money{dispaly:inline-block}*/
    table.white-background tbody tr, table.white-background tbody tr td {
        background: #fff;
    }

    table.white-background tbody tr.tr-green, table.white-background tbody tr.tr-green td {
        background: #e9ffaf;
    }
    .table-bordered tbody:first-child tr:first-child td {
           border-top: 1px solid #e9ffaf;
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
.tr-hidden{display:none;}
</style>
<?php echo $this->element("clients/title_edit") ?>
<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <?php if($_SESSION['login_type'] != 2): ?>
            <div class="widget-head">
                <ul>
                    <li class="active"><a href="<?php echo $this->webroot ?>clients/edit/<?php echo base64_encode($post['Client']['client_id']) ?>" class="glyphicons coffe_cup"><i></i><?php __('Basic Info') ?></a></li>
                    <li><a href="<?php echo $this->webroot ?>prresource/gatewaygroups/view_egress?query[id_clients]=<?php echo $post['Client']['client_id'] ?>&viewtype=client" class="glyphicons share_alt"><i></i><?php echo __('Egress'); ?></a></li>
                    <li><a href="<?php echo $this->webroot ?>prresource/gatewaygroups/view_ingress?query[id_clients]=<?php echo $post['Client']['client_id'] ?>&viewtype=client"  class="glyphicons share_alt"><i></i><?php echo __('Ingress'); ?></a></li>
                </ul>
            </div>
        <?php endif; ?>

        <div class="widget-body">

            <?php if($_SESSION['login_type'] == 2): ?>
                <?php echo $form->create('Client', array('url' => $this->webroot ."agent_portal/edit_client/{$this->params['pass'][0]}", 'id' => 'ClientEdit')); ?>
            <?php else: ?>
                <?php echo $form->create('Client', array('action' => "edit/{$this->params['pass'][0]}", 'id' => 'ClientEdit')); ?>
            <?php endif; ?>
            <input type="hidden" name="order_user_id" value="<?php if (isset($_GET['order_user_id']) && !empty($_GET['order_user_id'])) echo $_GET['order_user_id']; ?>" />

            <?php echo $form->input('client_id', array('label' => false, 'div' => false, 'type' => 'hidden', 'value' => $post['Client']['client_id'])) ?>
            <input type="hidden" value="<?php echo $post['Client']['client_id'] ?>" name="client_id"/>

            <div class="widget">
                <div class="widget-head"><h4 class="heading"><?php __('Basic Info') ?></h4></div>
                <div class="widget-body">
                    <table class="form footable table table-striped  tableTools table-bordered  table-white table-primary default footable-loaded">
                        <tr>
                            <td class="align_right padding-r20"><?php echo __('Name') ?></td>
                            <td>
                                <?php echo $form->input('name', array('label' => false, 'div' => false, 'class' => 'validate[required,custom[onlyLetterNumberLineSpace],funcCall[notEqualAdmin]]', 'type' => 'text', 'maxLength' => 500, 'value' => $post['Client']['name'])) ?>
                            </td>
                            <td class="align_right padding-r20"><?php echo __('status') ?></td>
                            <td>
                                <?php
                                $st = array('true' => __('Active', true), 'false' => __('Inactive', true));

                                empty($post['Client']['status']) ? $tmp_s = 'false' : $tmp_s = 'true';
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
                                echo $form->input('mode', array('options' => $st, 'label' => false, 'div' => false, 'type' => 'select', 'selected' => $post['Client']['mode']))
                                ?>
                            </td>
                            <?php if($_SESSION['login_type'] == 2): ?>
                                <td></td>
                                <td></td>
                            <?php else: ?>
                                <td class="align_right padding-r20"><?php echo __('currency') ?></td>
                                <td>
                                    <?php echo $form->input('currency_id', array('options' => $currency, 'label' => false, 'div' => false, 'type' => 'select', 'class' => '', 'selected' => $post['Client']['currency_id']));
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
                                    <?php echo $form->input('service_charge_id', array('options' => $service_charge, 'label' => false, 'div' => false, 'selected' => $post['Client']['service_charge_id'], 'type' => 'select')); ?>
                                </td>
                            </tr>
                        <?php } ?>
                        <tr <?php if ($post['Client']['mode'] == 1) echo "style='display: none;'";  ?>>
                            <td class="align_right padding-r20"><span id="ht-100002" class="helptip" rel="helptip"><?php echo __('allowedcredit') ?></span> </td>

                            <td style="text-align: left;">
                                <?php
                                $unlimDisplay = 'inline-block';
                                if(isset($this->data['Client']['unlimited_credit']) && $this->data['Client']['unlimited_credit'] == 1) {
                                    $unlimDisplay = 'none';
                                } ?>
                                <span id="unlimited_panel">
                                    <?php __('Unlimited')?>
                                    <?php echo $form->input('unlimited_credit', array('class' => 'in-decimal input in-checkbox', 'label' => false, 'div' => false, 'type' => 'checkbox')) ?>
                                </span>
                                <?php echo $form->input('allowed_credit', array('label' => false, 'value' => abs($this->data['Client']['allowed_credit']), 'div' => false, 'type' => 'text', 'class' => 'validate[min[0],custom[number]]', 'maxlength' => '30', 'style' => 'width: 100px; display:' . $unlimDisplay)) ?>
                                <span class='money'></span>
                            </td>
                            <!--                        </tr>
                                                    <tr>-->
                            <td class="align_right padding-r20"><span id="ht-100002" class="helptip" rel="helptip"><?php echo __('lowprofit') ?></span> </td>
                            <td style="text-align: left;">
                                <?php echo $form->input('profit_margin', array('label' => false, 'value' => $post['Client']['profit_margin'] ?: 0, 'div' => false, 'type' => 'text', 'class' => 'in-decimal input in-text', 'maxlength' => '6', 'style' => 'width:43%')) ?>
                                <?php echo $xform->input('profit_type', array('options' => Array(1 => __('Percentage', true), 2 => __('Value', true)), 'style' => 'width:43%')) ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="align_right padding-r20"><?php echo __('CPS') ?></td>
                            <td>
                                <?php echo $form->input('cps_limit', array('label' => false, 'div' => false, 'type' => 'text', 'maxLength' => 16, 'value' => $post['Client']['cps_limit'])) ?>
                            </td>
                            <td class="align_right padding-r20"><?php echo __('Call limit') ?></td>
                            <td>
                                <?php echo $form->input('call_limit', array('label' => false, 'div' => false, 'type' => 'text', 'maxLength' => 16, 'value' => $post['Client']['call_limit'])) ?>
                            </td>
                        </tr>

                    </table>
                </div>
            </div>



            <?php //*******************************系统信息设置*****************************?>
            <div class="widget" data-toggle="collapse-widget" data-collapse-closed="true">
                <div class="widget-head"><h4 class="heading"><?php __('Company Info') ?></h4></div>
                <div class="widget-body">
                    <table class="form footable table table-striped  tableTools table-bordered  table-white table-primary default footable-loaded">

                        <tr>
                            <td class="align_right padding-r20"><?php echo __('Company Name') ?></td>
                            <td>
                                <?php echo $form->input('company', array('class' => 'validate[maxSize[200],custom[onlyLetterNumberLineSpace]]', 'label' => false, 'div' => false, 'value' => $post['Client']['company'], 'maxLength' => 256)) ?>
                            </td>
                            <td class="align_right padding-r20"><?php echo __('address') ?></td>
                            <td>
                                <?php echo $form->input('address', array('label' => false, 'div' => false, 'type' => 'textarea', 'maxlength' => '300', 'value' => $post['Client']['address'])) ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="align_right padding-r20"><?php echo __('Main e-mail', true); ?> </td>

                            <td> <?php echo $form->input('email', array('label' => false, 'div' => false, 'class' => 'validate[required,custom[email_chars]]', 'value' => $post['Client']['email'])) ?></td>
                            <td class="align_right padding-r20"><?php echo __('NOC e-mail', true); ?> </td>

                            <td> <?php echo $form->input('noc_email', array('label' => false, 'div' => false, 'class' => 'validate[custom[email_chars]]', 'value' => $post['Client']['noc_email'])) ?></td>
                        </tr>
                        <tr>
                            <td class="align_right padding-r20"><?php echo __('Billing e-mail', true); ?> </td>

                            <td> <?php echo $form->input('billing_email', array('label' => false, 'div' => false, 'class' => 'validate[custom[email_chars]]', 'value' => $post['Client']['billing_email'])) ?></td>
                            <td class="align_right padding-r20"><?php echo __('Rates e-mail', true); ?> </td>

                            <td> <?php echo $form->input('rate_email', array('label' => false, 'div' => false, 'class' => 'validate[custom[email_chars]]', 'value' => $post['Client']['rate_email'])) ?></td>
                        </tr>
                        <tr>
                            <td class="align_right padding-r20"><?php echo __('Rate Delivery Email', true); ?> </td>
                            <td> <?php echo $form->input('rate_delivery_email', array('label' => false, 'div' => false, 'maxLength' => '100', 'class' => 'validate[custom[email_chars]]', 'value' => $post['Client']['rate_delivery_email'])) ?></td>
                            <td class="align_right padding-r20"><?php echo __('Tax ID', true); ?>: </td>
                            <td> <?php echo $form->input('tax_id', array('label' => false, 'div' => false, 'value' => $post['Client']['tax_id'], 'class' => 'validate[custom[phone]]')) ?></td>
                        </tr>
                        <tr>
                            <td class="align_right padding-r20"><?php echo __('Account Details', true); ?> </td>

                            <td> <?php echo $form->input('details', array('label' => false, 'div' => false, 'value' => $post['Client']['details'], 'rows' => '5')) ?></td>

                        </tr>

                    </table>
                </div>
            </div>

            <div class="widget" data-toggle="collapse-widget" data-collapse-closed="true">
                <div class="widget-head"><h4 class="heading"><?php __('Client Alert') ?></h4></div>
                <div class="widget-body">
                    <table class="client_alert form footable white-background table table-striped  table-borderedtableTools table-bordered  table-white table-primary default footable-loaded">
                        <thead></thead>
                        <tr >
                            <td class="align_right padding-r20" colspan="3" style="font-weight: bold; text-align:center;" >
                                <?php echo __('Daily Alerts', true); ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="align_right padding-r20">
                                <input type="checkbox" name="data[Client][daily_cdr_generation]" id="daily_cdr_generation"  <?php if ($post['Client']['daily_cdr_generation'] == 't') echo 'checked' ?> />
                            </td>
                            <td style="font-weight:normal;">
                                <?php __('Enable Daily CDR Delivery') ?>
                            </td>
                            <td rowspan="2" class="daily_alert_on <?php if (!$post['Client']['daily_cdr_generation'] && !$post['Client']['is_auto_balance'] && !$post['Client']['is_auto_summary']) echo 'tr-hidden'; ?>">
                                <span>Recipient:</span>
                                <?php
                                echo $form->input('auto_daily_balance_recipient', array('options' => array(__("Partner's Billing Contact", true), __("Owner's Billing Contact", true), __('Both', true)), 'label' => false, 'div' => false, 'type' => 'select', 'selected' => $post['Client']['auto_daily_balance_recipient']));
                                ?>
                                <br>
                                <span>Send Daily Alert on</span>
                                <?php
                                $send_times = array();
                                for ($i = 0; $i <= 23; $i++)
                                {
                                    $send_times[$i] = $i . ":00";
                                }
                                echo $form->input('auto_summary_hour', array('options' => $send_times, 'label' => false, 'div' => false, 'type' => 'select', 'selected' => empty($post['Client']['auto_summary_hour']) ? '0' : $post['Client']['auto_summary_hour']));
                                ?>

                                <span>GMT</span>
                                <?php
                                $azone_arr = array();
                                for ($i = -12; $i <= 12; $i++)
                                {
                                    $zone_str = $i < 0 ? sprintf("-%02d:00", 0 - $i) : sprintf("+%02d:00", $i);
                                    $azone_arr[$zone_str] = 'GMT ' . $zone_str;
                                }
                                echo $form->input('auto_send_zone', array('options' => $azone_arr, 'label' => false, 'div' => false, 'type' => 'select', 'selected' => empty($post['Client']['auto_send_zone']) ? '+00:00' : $post['Client']['auto_send_zone']));
                                ?>

                            </td>
                        </tr>
                        <tr >
                         <td class="align_right padding-r20">
                            <?php
                            empty($post['Client']['is_auto_balance']) ? $au = 'false' : $au = 'checked';
                            echo $form->input('is_auto_balance', array('class' => 'in-decimal input in-checkbox', 'label' => false, 'div' => false, 'type' => 'checkbox', 'checked' => $au));
                            ?>
                        </td>
                        <td  style="text-align: left;">
                            <?php __('Daily Balance Summary') ?>
                        </td>
                        <tr >
                               <td class="align_right padding-r20">
                                   <?php
                                   empty($post['Client']['is_auto_summary']) ? $au = 'false' : $au = 'checked';
                                   echo $form->input('is_auto_summary', array('class' => ' in-decimal input in-checkbox', 'label' => false, 'div' => false, 'type' => 'checkbox', 'checked' => $au));
                                   ?>
                               </td>
                               <td  style="text-align: left;">
                                   <?php __('Enable Daily Usage Summary') ?>
                               </td>
                              <td class="daily_usage_summary <?php if (isset($post['Client']['is_auto_summary']) && $post['Client']['is_auto_summary'] != 't') echo 'tr-hidden'; ?>">
                                <?php __('Non-Zero Only') ?>:
                                <?php
                                empty($post['Client']['auto_summary_not_zero']) ? $au = 'false' : $au = 'checked';
                                echo $form->input('auto_summary_not_zero', array('class' => 'in-decimal input in-checkbox', 'label' => false, 'div' => false, 'type' => 'checkbox', 'checked' => $au));
                                ?>
                                <br />
                                Group By:
                                <?php
                                echo $form->input('auto_summary_group_by', array('options' => array(__('By Country', TRUE), __('By Code Name', TRUE), __('By Code', TRUE)), 'label' => false, 'div' => false, 'type' => 'select', 'selected' => $post['Client']['auto_summary_group_by']));
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
                                empty($post['Client']['is_send_trunk_update']) ? $au = 'false' : $au = 'checked';
                                echo $form->checkbox('is_send_trunk_update', array('checked' => $au));
                                ?>
                            </td>
                            <td colspan="2">
                                <?php echo __('Enable Trunk Update Alert') ?> </td>
                            </td>
                        </tr>
                         <tr >
                            <td class="align_right padding-r20" width="10%">
                                <?php
                                empty($post['Client']['enable_payment_alert']) ? $au = 'false' : $au = 'checked';
                                echo $form->checkbox('enable_payment_alert', array('checked' => $au));
                                ?>
                            </td>
                            <td colspan="2">
                                <?php echo __('Payment Received Alert') ?> </td>
                            </td>
                        </tr>
                        <tr >
                            <td class="align_right padding-r20" width="10%">
                                <?php
                                $checked = isset($post['Client']['zero_balance_notice']) ? $post['Client']['zero_balance_notice'] : 'false';
                                echo $form->input('is_daily_zero_balance_notification', array('class' => 'in-decimal input in-checkbox',
                                    'label' => false, 'div' => false, 'type' => 'checkbox','name' => 'data[Client][zero_balance_notice]','checked' => $checked)) ?>
                            </td>
                            <td colspan="2">
                                <?php echo __('Zero Balance Notification') ?>
                        </tr>
                        <tr >
                            <td class="align_right padding-r20" width="10%">
                                <?php
                                $checked = $this->data['Client']['is_daily_balance_notification'] == 't' ? 'checked' : 'false';
                                echo $form->input('is_daily_balance_notification', array('class' => 'toggle-next in-decimal input in-checkbox',
                                    'label' => false, 'div' => false, 'type' => 'checkbox','name' => 'data[Client][is_daily_balance_notification]','checked' => $checked)) ?>
                            </td>
                            <td>
                                <?php echo __('Low Balance Notification') ?>
                            </td>
                            <td class="low_balance_config_table" class="<?php if ($checked != 'checked') echo 'tr-hidden'; ?>">
                                <?php $value_type_checked = isset($this->data['low_balance']['value_type']) ? $this->data['low_balance']['value_type'] : 0; ?>
                                <input type="radio" name="data[low_balance][value_type]" class="lowBalanceValueType" value="0" <?php if($value_type_checked == 0): ?>checked<?php endif; ?> >
                                <?php __('Send low balance alert when the balance is less than or equal to'); ?>
                                <?php
                                $value = isset($this->data['low_balance']['actual_notify_balance']) ? $this->data['low_balance']['actual_notify_balance'] : '';
                                echo $form->input('actual_notify_balance', array('class' => 'validate[custom[number]] width80',
                                    'maxlength' => '10', 'label' => false, 'div' => false, 'type' => 'text','value' => $value,
                                    'name' => 'data[low_balance][actual_notify_balance]')) ?>
                                <?php __('USD') ?>
                                <br />
                                <input type="radio" name="data[low_balance][value_type]" class="lowBalanceValueType" value="1" <?php if($value_type_checked == 1): ?>checked<?php endif; ?>>
                                <?php __('Send low balance alert when the credit remaining is less than or equal to'); ?>
                                <?php
                                $value = isset($this->data['low_balance']['percentage_notify_balance']) ? $this->data['low_balance']['percentage_notify_balance'] : '';
                                echo $form->input('percentage_notify_balance', array('class' => 'validate[custom[number]] width80',
                                    'maxlength' => '10', 'label' => false, 'div' => false, 'type' => 'text','value' => $value,
                                    'name' => 'data[low_balance][percentage_notify_balance]')) ?>%
                                <?php __('of credit limit') ?>
                                <br />
                                <?php __('Notification should be sent'); ?>
                                <?php
                                $selected = isset($this->data['low_balance']['send_time_type']) ? $this->data['low_balance']['send_time_type'] : 0;
                                echo $form->input('', array('options' => array(0 => __('Daily', true), 1 => __('Hourly', true)),
                                    'label' => false, 'style' => '', 'div' => false,'selected' => $selected,
                                    'type' => 'select', 'class' => 'input in-text in-select width80','id' => 'lowBalanceSendType',
                                    'name' => 'data[low_balance][send_time_type]')); ?>
                                <div class="daily_send_time_div">
                                    <?php __('at') ?>
                                    <select name="data[low_balance][daily_send_time]" class="input in-select width80">
                                        <?php
                                        $selected = isset($this->data['low_balance']['daily_send_time']) ? $this->data['low_balance']['daily_send_time'] : 0;
                                        foreach ($appCommon->get_hour_time_arr() as $hour_key => $hour_show): ?>
                                            <option value="<?php echo $hour_key; ?>" <?php if($selected == $hour_key): ?>selected<?php endif; ?>><?php echo $hour_show; ?></option>
                                        <?php endforeach; ?>
                                    </select>&nbsp;GMT
                                </div>
                                <br />
                                <?php __('Notification should be sent for'); ?>
                                <?php
                                $value = isset($this->data['low_balance']['duplicate_days']) ? $this->data['low_balance']['duplicate_days'] : '';
                                echo $form->input('low_balance', array('class' => 'validate[custom[integer]] width25',
                                    'maxlength' => '2', 'label' => false, 'div' => false, 'type' => 'text','value' => $value,
                                    'name' => 'data[low_balance][duplicate_days]')) ?>
                                <?php __('days') ?>
                                <?php __('to') ?>
                                <?php $selected = isset($this->data['low_balance']['send_to']) ? $this->data['low_balance']['send_to'] : 0; ?>
                                <select name="data[low_balance][send_to]" id="lowBalanceSendTo">
                                    <option value="0"><?php __("Owner's Billing Contact") ?></option>
                                    <option value="1" <?php if($selected == 1): ?>selected<?php endif; ?>><?php __("Partner's Billing Contact") ?></option>
                                    <option value="2" <?php if($selected == 2): ?>selected<?php endif; ?>><?php __('Both') ?></option>
                                </select>
                        </td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="widget" data-toggle="collapse-widget" data-collapse-closed="true">
                <div class="widget-head"><h4 class="heading"><?php __('Short Call Charge') ?></h4></div>
                <div class="widget-body">
                    <div>
                        <?php __('If') ?> <?php echo $form->input('scc_percent', array('label' => false, 'style' => 'width:25px;', 'div' => false, 'value' => $post['Client']['scc_percent'], 'class' => 'validate[max[100],custom[onlyNumberSp]]')) ?> %
                        <?php __('overall invoice minute is below or equal to') ?> <?php echo $form->input('scc_bellow', array('options' => array('6' => '6', '12' => '12', '18' => '18', '24' => '24', '30' => '30'), 'label' => false, 'div' => false, 'type' => 'select', 'selected' => $post['Client']['scc_bellow'], 'style' => 'width:auto;')) ?>
                        <?php __('second') ?>, <br><?php __('then an additional charge of') ?> <?php echo $form->input('scc_charge', array('label' => false, 'style' => 'width:70px;', 'div' => false, 'class' => 'validate[custom[number]]', 'value' => number_format($post['Client']['scc_charge'], 6))) ?> <?php __('will be applied to each call') ?>
                        <?php echo $form->input('scc_type', array('options' => array('0' => __('meeting the short duration defined above', true), '1' => __('that exceed the defined percentage', true)), 'label' => false, 'div' => false, 'type' => 'select', 'selected' => $post['Client']['scc_type'], 'style' => 'width:auto;')); ?>
                    </div>
                </div>
            </div>

            <div class="widget" data-toggle="collapse-widget" data-collapse-closed="true">
                <div class="widget-head">
                    <h4 class="heading">
                        <?php
                        empty($post['Client']['auto_invoicing']) ? $au = 'false' : $au = 'checked';
                        echo $form->checkbox('auto_invoicing', array('checked' => $au));
                        ?> <?php __('Auto Invoice') ?>
                    </h4>
                </div>
                <div class="widget-body">
                    <table class="form footable table table-striped  tableTools table-bordered  table-white table-primary default footable-loaded">
                        <thead></thead>
                        <tr>
                            <td class="align_right padding-r20"><?php echo __('Include Tax in Invoice', true); ?> </td>
                            <td>
                                <?php echo $form->input('include_tax', array('label' => false, 'div' => false, 'class' => 'input in-text in-input', 'checked' => $post['Client']['include_tax'] ? true : false)) ?>
                                <span id="tax_area" style="display:none;">
                                                <?php echo $form->input('tax', array('label' => false, 'div' => false, 'class' => 'input in-text in-input', 'style' => 'width:30px;', 'value' => $post['Client']['tax'])) ?>
                                    %
                                            </span>
                            </td>
                            <td class="align_right padding-r20"><?php echo __('paymentterm') ?> </td>
                            <td>
                                <?php echo $form->input('payment_term_id', array('options' => $paymentTerm, 'label' => false, 'div' => false, 'type' => 'select', 'selected' => $post['Client']['payment_term_id']));
                                ?>
                            </td>

                        </tr>
                        <tr>
                            <td class="align_right padding-r20"><?php echo __('Invoice Format') ?> </td>
                            <td>
                                <?php
                                $route_type = array('1' => 'PDF', '2' => 'Word');
                                echo $form->input('invoice_format', array('options' => $route_type, 'label' => false, 'div' => false, 'type' => 'select', 'selected' => $post['Client']['invoice_format']));
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
                                echo $form->input('invoice_zone', array('options' => $zone_arr, 'label' => false, 'div' => false, 'type' => 'select', 'selected' => empty($post['Client']['invoice_zone']) ? '+00:00' : $post['Client']['invoice_zone']));
                                ?>
                                
                                Hour

                                <?php
                                $zone_arr = array();
                                for ($i = 0; $i <= 23; $i++)
                                {
                                    $zone_arr[$i] = $i;
                                }
                                echo $form->input('auto_invoice_hour', array('options' => $zone_arr, 'label' => false, 'div' => false, 'type' => 'select', 'selected' => $post['Client']['auto_invoice_hour']));
                                ?>
                            </td>
                            <td class="align_right padding-r20"><?php echo __('No Invoice for Zero Traffic') ?> </td>
                            <td>
                                <?php
                                echo $form->input('invoice_zero', array('options' => array(1 => 'Yes', 0 => 'No'), 'label' => false, 'div' => false, 'type' => 'select', 'selected' => $post['Client']['invoice_zero'] ? 1 : 0));
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="align_right padding-r20">
                                <?php echo __('Include CDR link in email') ?>
                            </td>
                            <td>
                                <?php
                                empty($post['Client']['attach_cdrs_list']) ? $au = 'false' : $au = 'checked';
                                echo $form->checkbox('attach_cdrs_list', array('checked' => $au))
                                ?>
                            </td>
                             <td class="align_right padding-r20"><?php echo __('CDR Compress Format') ?> </td>
                            <td>
                                <?php
                                $route_type = array('3' => 'zip', '4' => 'tar.gz');
                                echo $form->input('cdr_list_format', array('options' => $route_type, 'label' => false, 'div' => false, 'type' => 'select', 'selected' => $post['Client']['cdr_list_format']));
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="align_right padding-r20">
                              <?php echo __('Show Detail by Code Name') ?>
                            </td>
                            <td>
                              <?php
                              empty($post['Client']['invoice_show_details']) ? $au = 'false' : $au = 'checked';
                              echo $form->checkbox('invoice_show_details', array('checked' => $au));
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
                                    'style' => '', 'value' => $post['Client']['decimal_place'], 'label' => false, 'div' => false))
                                ?>
                            </td>
                            <td class="align_right padding-r20"><?php __('Auto Send Invoice'); ?> </td>
                            <td>
                                <?php empty($post['Client']['email_invoice']) ? $au = 'false' : $au = 'checked'; ?>
                                <?php echo $form->checkbox('email_invoice', array('checked' => $au)); ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="align_right padding-r20"><?php __('Send invoice as link in email'); ?> </td>
                            <td>
                                <?php empty($post['Client']['is_send_as_link']) ? $au = 'false' : $au = 'checked'; ?>
                                <?php echo $form->checkbox('is_send_as_link', array('checked' => $au)); ?>
                            </td>
                            <td class="align_right padding-r20"><?php  ?> </td>
                            <td>
                            </td>
                        </tr>
                        <tr>
                            <td class="align_right padding-r20"><?php __('Include Account Detail'); ?> </td>
                            <td>
                                <?php empty($post['Client']['is_invoice_account_summary']) ? $au = 'false' : $au = 'checked'; ?>
                                <?php echo $form->checkbox('is_invoice_account_summary', array('checked' => $au)); ?>
                                <?php
                                echo $form->input('invoice_use_balance_type', array('options' => array(0 => 'use actual balance', 1 => 'use mutual balance'), 'label' => false, 'div' => false, 'type' => 'select', 'selected' => $post['Client']['invoice_use_balance_type'], 'style' => 'width:120px;'));
                                ?>
                            </td>
                            <td class="align_right padding-r20"><?php __('Show Daily Usage'); ?> </td>
                            <td>
                                <?php empty($post['Client']['is_show_daily_usage']) ? $au = 'false' : $au = 'checked'; ?>
                                <?php echo $form->checkbox('is_show_daily_usage', array('checked' => $au)); ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="align_right padding-r20"><?php __('Short Duration Call Surcharge detail'); ?> </td>
                            <td>
                                <?php empty($post['Client']['is_short_duration_call_surcharge_detail']) ? $au = 'false' : $au = 'checked'; ?>
                                <?php echo $form->checkbox('is_short_duration_call_surcharge_detail', array('checked' => $au)); ?>
                            </td>
                            <td class="align_right padding-r20"><?php __('Include Summary of Payments'); ?> </td>
                            <td>
                                <?php empty($post['Client']['invoice_include_payment']) ? $au = 'false' : $au = 'checked'; ?>
                                <?php echo $form->checkbox('invoice_include_payment', array('checked' => $au)); ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="align_right padding-r20"><?php __('Show Detail by Trunk'); ?></td>
                            <td>
                                <?php empty($post['Client']['is_show_detail_trunk']) ? $au = 'false' : $au = 'checked'; ?>
                                <?php echo $form->checkbox('is_show_detail_trunk', array('checked' => $au)); ?>
                            </td>
                            <td class="align_right padding-r20"><?php __('Show Total by Trunk'); ?></td>
                            <td>
                                <?php empty($post['Client']['is_show_total_trunk']) ? $au = 'false' : $au = 'checked'; ?>
                                <?php echo $form->checkbox('is_show_total_trunk', array('checked' => $au)); ?>
                            </td>
                        </tr>
                        <!--tr>
                            <td class="align_right padding-r20"><?php __('Show Code Summary (top 10)'); ?></td>
                            <td>
                                <?php empty($post['Client']['is_show_code_100']) ? $au = 'false' : $au = 'checked'; ?>
                                <?php echo $form->checkbox('is_show_code_100', array('checked' => $au)); ?>
                            </td>

                            <td class="align_right padding-r20"><?php __('Break Down by Rate Table'); ?> </td>
                            <td>
                                <?php
                                if (empty($post['Client']['is_breakdown_by_rate_table']))
                                {
                                    $au = 'false';
                                    $select_display = "none";
                                }
                                else
                                {
                                    $au = 'checked';
                                    $select_display = "block";
                                }
                                ?>
                                <?php echo $form->checkbox('is_breakdown_by_rate_table', array('checked' => $au)); ?>
                                <?php
                                echo $form->input('breakdown_by_rate_table', array('options' => array(1 => 'Breakdown A-Z Rate Table by Destination', 2 => 'Breakdown US Rate Table by Jurisdiction'), 'label' => false, 'div' => false, 'type' => 'select', 'selected' => $post['Client']['breakdown_by_rate_table'], 'style' => 'width:120px;display:' . $select_display . ';'));
                                ?>
                            </td>
                        </tr-->
                        <!--tr>
                            <td class="align_right padding-r20"><?php __('Show Traffic Analysis by Code Name'); ?></td>
                            <td>
                                <?php empty($post['Client']['is_show_code_name']) ? $au = 'false' : $au = 'checked'; ?>
                                <?php echo $form->checkbox('is_show_code_name', array('checked' => $au)); ?>
                            </td>
                            <td class="align_right padding-r20"><?php __('Show Calls By Date Summary'); ?></td>
                            <td>
                                <?php empty($post['Client']['is_show_by_date']) ? $au = 'false' : $au = 'checked'; ?>
                                <?php echo $form->checkbox('is_show_by_date', array('checked' => $au)); ?>
                            </td>
                        </tr-->

                        <tr>
                            <td class="align_right padding-r20">
                                <?php echo __('Show Jurisdictional Detail') ?>
                            </td>
                            <td>
                                <?php
                                empty($post['Client']['invoice_jurisdictional_detail']) ? $au = 'false' : $au = 'checked';
                                echo $form->checkbox('invoice_jurisdictional_detail', array('checked' => $au))
                                ?>
                            </td>
                            <td class="align_right padding-r20"><?php __('Show Traffic Analysis by Country'); ?></td>
                            <td>
                                <?php empty($post['Client']['is_show_country']) ? $au = 'false' : $au = 'checked'; ?>
                                <?php echo $form->checkbox('is_show_country', array('checked' => $au)); ?>
                            </td>
                        </tr>
                        <!--tr>
                            <td style="display: none;" class="align_right padding-r20"><?php __('Require Vendor Invoice'); ?> </td>
                            <td>
                                <?php
                                if (empty($post['Client']['is_vendor_invoice']))
                                {
                                    $au = 'false';
                                    $select_display = "none";
                                }
                                else
                                {
                                    $au = 'checked';
                                    $select_display = "block";
                                }
                                ?>
                                <?php echo $form->checkbox('is_vendor_invoice', array('checked' => $au)); ?>
                            </td>
                            <td class="align_right padding-r20"><?php __('Vendor'); ?>&nbsp;<?php echo __('paymentterm') ?> </td>
                            <td>
                                <?php echo $form->input('vendor_payment_term_id', array('options' => $paymentTerm, 'label' => false,
                                    'div' => false, 'type' => 'select', 'selected' => $post['Client']['vendor_payment_term_id']));
                                ?>
                            </td>
                        </tr-->
                    </table>
                </div>
            </div>

            <div class="clearfix"></div>
            <div class="widget" data-toggle="collapse-widget" data-collapse-closed="true">
                <div class="widget-head"><h4 class="heading">
                        <?php echo $form->checkbox('is_panelaccess', array('checked' => $post['Client']['is_panelaccess'])) ?>
                        <?php __('Carrier Self-Service Portal') ?>
                    </h4>
                    <input type="hidden" name="is_send_welcom_letter" id="is_send_welcom_letter" value=""/>
                </div>
                <div class="widget-body">
                    <table class="form footable table table-striped  tableTools table-bordered  table-white table-primary default footable-loaded">

                        <tr>
                            <td class="align_right padding-r20"><?php echo __('Username') ?> </td>
                            <td>
                                <?php echo $form->input('login', array('label' => false, 'class' => 'validate[required]', 'maxLength' => '256', 'div' => false, 'type' => 'text', 'value' => $post['Client']['login'])) ?>
                            </td>
                            <td class="align_right padding-r20"><?php echo __('Password') ?> </td>
                            <td>
                                <?php echo $form->input('password', array('label' => false, 'div' => false, 'type' => 'password', 'maxLength' => '16', 'value' => $post['Client']['password'])); ?></td>
                        </tr>
                        <tr>
                            <td class="align_right padding-r20"><?php echo __('Permission') ?> </td>
                            <td class="value" colspan="3">
                                <?php echo $this->element('portal/add_permission_div',array('check_data' => $post['Client'])); ?>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

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
            $('#ClientIsAutoBalance').on('click', function(){
                if ($(this).is(':checked')) {
                    $('#ClientAutoSummaryIncludeCdr').closest('tr').show();
                } else {
                    $('#ClientAutoSummaryIncludeCdr').closest('tr').hide();
                }
            });

             jQuery("#ClientIsAutoSummary").click(function () {
               if ($(this).is(':checked')) {
                   $('.daily_usage_summary').show();
               } else {
                   $('.daily_usage_summary').hide();
               }
            });

            $("#ClientIsAutoSummary, #ClientIsAutoBalance, #daily_cdr_generation").click(function () {
              let daily_checked = $('#ClientIsAutoSummary').is(':checked') ||  $('#ClientIsAutoBalance').is(':checked') || $('#daily_cdr_generation').is(':checked');
              if (daily_checked){
                  $('.daily_alert_on').show();
              }else{
                  $('.daily_alert_on').hide();
              }
            });
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


            jQuery('#ClientAllowedCredit,#ClientNotifyAdminBalance').xkeyvalidate({type: 'Ip'});
            //jQuery('#ClientProfitMargin').xkeyvalidate({type:'Num'})
            //	jQuery('#ClientEmail,#ClientNocEmail,#ClientBillingEmail,#ClientRateEmail').xkeyvalidate({type:'Email'});
            jQuery('#ClientName,#ClientCompany').xkeyvalidate({type: 'strNum'});
            jQuery('#ClientTaxId').xkeyvalidate({type: 'Num'});

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
                   $.ajax({
                       url: '<?php echo $this->webroot ?>mailtmps/ajax_get_invoice_mailtmp',
                       dataType: 'json',
                       success: function (result) {
                           if (result.status)
                           {
                                $dd.html(result.data);
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
                       }
                   });

                    /*$dd.load('<?php echo $this->webroot ?>mailtmps/ajax_get_invoice_mailtmp',
                        {},
                        function (responseText, textStatus, XMLHttpRequest) {debugger;
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
                    );*/
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

//            if(te && !check_welcom){
//                if(jQuery('#ClientIsPanelaccess').attr('checked')){
//                    var login = jQuery('#ClientLogin').val();
//                    te = false;
//                    bootbox.confirm('Send welcom letter to the user ['+login+'] ?', function(result) {
//                        if(result) {
//                            $('#is_send_welcom_letter').val('1');
//                            check_welcom = true;
//                            jQuery('#ClientEdit').submit();
//                        }else{
//                            $('#is_send_welcom_letter').val('');
//                            check_welcom = true;
//                            jQuery('#ClientEdit').submit();
//                        }
//
//                    });
//
//                }
//            }

            return te;
        });
    });
</script>
<script type="text/javascript">

    function unlimitedClick_event() {
        var unlimit = jQuery("#ClientUnlimitedCredit").is(':checked');
        jQuery("#ClientAllowedCredit").show();
        jQuery(".money").show();
        if (jQuery("#ClientUnlimitedCredit").is(':checked'))
        {
            jQuery("#ClientAllowedCredit").hide();
            jQuery(".money").hide();
        }
    }

    jQuery(document).ready(function () {

        jQuery("#ClientUnlimitedCredit").click(unlimitedClick_event);

        jQuery('#ClientMode').change(function () {
            if (jQuery(this).val() == '2') {
                jQuery('#ClientAllowedCredit').parent().parent().show();
                jQuery('#unlimited_panel').show();
            } else {
                jQuery('#ClientAllowedCredit').val(0).parent().parent().hide();
                jQuery('#unlimited_panel').hide();
                jQuery('#ClientUnlimitedCredit').attr('checked', false);
                jQuery("#ClientAllowedCredit").show();
//                jQuery(".money").show();
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

        $('#ClientAttachCdrsList').change(function () {
            if ($(this).is(':checked')) {
                 $("#ClientCdrListFormat").removeAttr('disabled');
            } else {
                 $("#ClientCdrListFormat").attr('disabled',true);
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
//        jQuery('#ClientMode').change();
        jQuery('#daily_cdr_generation').change(function () {
            if ($(this).attr('checked')) {
                $('.daily_cdr_generation_panel').show();
            } else {
                $('.daily_cdr_generation_panel').hide();
            }
        }).trigger('change');
        jQuery('#ClientAutoInvoicing').change(function () {
            checkCB();
            $('#ClientAttachCdrsList').change(function () {
                if ($(this).is(':checked')) {
                     $("#ClientCdrListFormat").removeAttr('disabled');
                } else {
                     $("#ClientCdrListFormat").attr('disabled',true);
                }
            }).trigger("change");
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

<script type="text/javascript">

    function changeLowBalanceType(have_percentage){
        if ($('#ClientIsDailyBalanceNotification').attr('checked')){
            if (have_percentage){
               // $(".lowBalanceValueType[value='1']").removeAttr('disabled');
                $("#ClientPercentageNotifyBalance").removeAttr('disabled');
            }else{
                //$(".lowBalanceValueType[value='1']").attr('disabled',true);
                $(".lowBalanceValueType[value='0']").attr('checked',true);
                //$("#ClientPercentageNotifyBalance").attr('disabled',true);
            }
        }
    }

    function ClientModeChange(){
        var client_mode = $("#ClientMode").val();
        if (client_mode == 1){
            changeLowBalanceType();
        }else{
            var client_credit = $("#ClientAllowedCredit").val();
            var unlimited_credit = $("#ClientUnlimitedCredit").is(":checked");
            if (parseFloat(client_credit) <= 0 || unlimited_credit){
                changeLowBalanceType();
            }else{
                changeLowBalanceType(1);
            }
        }
    }

    function isDailyBalanceNotification(){
        if ($("#ClientIsDailyBalanceNotification").attr('checked')) {
            $('.low_balance_config_table').show().find('input,select').removeAttr('disabled');
            $('.low_balance_config_table').prev().attr('colspan', 1);
        } else {
            $('.low_balance_config_table').hide().find('input,select').attr('disabled',true);
            $('.low_balance_config_table').prev().attr('colspan', 2);
        }
    }

    function lowBalanceSendTypeChange(){
        var send_type = $("#lowBalanceSendType").val();
        if (send_type == 0){
            $(".daily_send_time_div").show();
        }else{
            $(".daily_send_time_div").hide();
        }
    }

    isDailyBalanceNotification();
    lowBalanceSendTypeChange();

    $(function(){


//        $("#ClientMode").change(ClientModeChange);


        $('#ClientIsDailyBalanceNotification').click(function(){
            isDailyBalanceNotification();
            if ($(this).attr('checked')) {
                ClientModeChange();
            }
        });

//        unlimited click
        $("#ClientUnlimitedCredit").click(ClientModeChange);

//        credit input
        $("#ClientAllowedCredit").blur(ClientModeChange);

        $("#lowBalanceSendType").change(lowBalanceSendTypeChange);

        $("#lowBalanceSendTo").change(function(){
            var send_to = $(this).val();
            var have_mail = $("#ClientBillingEmail").val() + $("#ClientEmail").val();
            if (send_to != 0 && have_mail == ''){
                jGrowl_to_notyfy("<?php __('Notice'); ?>:<?php __("Partner's Billing and main mail is not configured"); ?>", {theme: 'jmsg-error'});
            }
        });
        $(".form>tbody>tr:eq(2)").show();

    });

    $(document).ready(function () {
        unlimitedClick_event();
    });

</script>
<script>
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

    $(document).ready(function () {
        if($("#ClientMode").val() == 1) {
            $("#ClientMode").parent().parent().next().hide();
        }
    });
</script>