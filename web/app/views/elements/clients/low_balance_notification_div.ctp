<div class="widget" data-toggle="collapse-widget" data-collapse-closed="true">
    <div class="widget-head">
        <h4 class="heading">
            <?php
            $checked = isset($this->data['low_balance']['is_notify']) ? $this->data['low_balance']['is_notify'] : 'false';
            echo $form->input('is_daily_balance_notification', array('class' => 'in-decimal input in-checkbox',
                'label' => false, 'div' => false, 'type' => 'checkbox','name' => 'data[low_balance][is_notify]','checked' => $checked)) ?>
            <?php echo __('Alert and Notification') ?>
        </h4>
    </div>
    <div class="widget-body">
        <table class="form footable table table-striped tableTools table-bordered  table-white default footable-loaded low_balance_config_table">
            <colgroup>
                <col width="40%">
                <col width="60%">
            </colgroup>
            <thead></thead>
            <?php $value_type_checked = isset($this->data['low_balance']['value_type']) ? $this->data['low_balance']['value_type'] : 0; ?>
            <tr>
                <td class="align_right padding-r20" width="50%"><?php echo __('Send Trunk Update Notification') ?> </td>
                <td>
                    <?php echo $form->input('is_send_trunk_update', array('label' => false, 'div' => false,'type'=>'checkbox')) ?>
                </td>
            </tr>
            <tr class="">
                <td colspan="2">
                    <div class="row">
                        <div class="actual_span percentage_span center span3 offset6">
                            <input type="radio" name="data[low_balance][value_type]" class="lowBalanceValueType" value="0" <?php if($value_type_checked == 0): ?>checked<?php endif; ?> >
                            <?php __('Send low balance alert when the balance is less than or equal to'); ?>
                            <?php
                            $value = isset($this->data['low_balance']['actual_notify_balance']) ? $this->data['low_balance']['actual_notify_balance'] : '';
                            echo $form->input('actual_notify_balance', array('class' => 'validate[required,custom[number]] width80',
                                'maxlength' => '10', 'label' => false, 'div' => false, 'type' => 'text','value' => $value,
                                'name' => 'data[low_balance][actual_notify_balance]')) ?>
                            <?php __('USD') ?>
                        </div>
                    </div>
                </td>
            </tr>

            <tr>
                <td colspan="2">
                    <div class="row">
                        <div class="actual_span center span3 offset6">
                            <input type="radio" name="data[low_balance][value_type]" class="lowBalanceValueType" value="1" <?php if($value_type_checked == 1): ?>checked<?php endif; ?>>
                            <?php __('Send low balance alert when the credit remaining is less than or equal to'); ?>
                            <?php
                            $value = isset($this->data['low_balance']['percentage_notify_balance']) ? $this->data['low_balance']['percentage_notify_balance'] : '';
                            echo $form->input('percentage_notify_balance', array('class' => 'validate[required,custom[number]] width80',
                                'maxlength' => '10', 'label' => false, 'div' => false, 'type' => 'text','value' => $value,
                                'name' => 'data[low_balance][percentage_notify_balance]')) ?>%
                            <?php __('of credit limit') ?>
                        </div>
                    </div>
                </td>
            </tr>

            <tr>
                <td colspan="2">
                    <div class="row">
                        <div class="span3 offset6">
                            <?php __('Notification should be sent'); ?>
                            <?php
                            $selected = isset($this->data['low_balance']['send_time_type']) ? $this->data['low_balance']['send_time_type'] : 0;
                            echo $form->input('', array('options' => array(0 => __('Daily', true), 1 => __('Hourly', true)),
                                'label' => false, 'style' => '', 'div' => false,'selected' => $selected,
                                'type' => 'select', 'class' => 'input in-text in-select width80','id' => 'lowBalanceSendType',
                                'name' => 'data[low_balance][send_time_type]')); ?>
                        </div>
                        <div class="span5 daily_send_time_div"><?php __('at') ?>
                            <select name="data[low_balance][daily_send_time]" class="input in-select width80">
                                <?php
                                $selected = isset($this->data['low_balance']['daily_send_time']) ? $this->data['low_balance']['daily_send_time'] : 0;
                                foreach ($appCommon->get_hour_time_arr() as $hour_key => $hour_show): ?>
                                    <option value="<?php echo $hour_key; ?>" <?php if($selected == $hour_key): ?>selected<?php endif; ?>><?php echo $hour_show; ?></option>
                                <?php endforeach; ?>
                            </select>&nbsp;GMT
                        </div>
                    </div>
                </td>
            </tr>

            <tr>
                <td colspan="2">
                    <div class="row">
                        <div class="span3 offset6">
                            <?php __('Notification should be sent for'); ?>
                            <?php
                            $value = isset($this->data['low_balance']['duplicate_days']) ? $this->data['low_balance']['duplicate_days'] : '';
                            echo $form->input('low_balance', array('class' => 'validate[required,custom[integer]] width25',
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
                            <br />
                            <?php __('Disable ingress trunks after'); ?>
                            <?php
                            $value = isset($this->data['low_balance']['disable_trunks_days']) ? $this->data['low_balance']['disable_trunks_days'] : '';
                            echo $form->input('low_balance', array('class' => 'validate[required,custom[integer]] width25',
                                'maxlength' => '2', 'label' => false, 'div' => false, 'type' => 'text','value' => $value,
                                'name' => 'data[low_balance][disable_trunks_days]')) ?>
                            <?php __('days') ?>
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <div class="row">
                            <div class="span3 offset6">
                                <?php echo __('Time') ?>
                                <?php echo $form->input('daily_balance_send_time', array('label' => false, 'div' => false, 'type' => "text", 'onfocus' => "WdatePicker({dateFmt:'HH:mm'});")) ?>
                                <?php echo __('GMT') ?>
                                <?php
                                $azone_arr = array();
                                for ($i = -12; $i <= 12; $i++)
                                {
                                    $zone_str = $i < 0 ? sprintf("-%02d:00", 0 - $i) : sprintf("+%02d:00", $i);
                                    $azone_arr[$zone_str] = 'GMT ' . $zone_str;
                                }
                                echo $form->input('daily_balance_send_time_zone', array('options' => $azone_arr, 'label' => false, 'div' => false, 'type' => 'select'));
                                ?>
                            </div>
                        </div>
                    </td>
                </tr>
        </table>
    </div>
</div>
<script type="text/javascript">

    function changeLowBalanceType(have_percentage){
        if ($('#ClientIsDailyBalanceNotification').attr('checked')){
            if (have_percentage){
                $(".lowBalanceValueType[value='1']").removeAttr('disabled');
                $("#ClientPercentageNotifyBalance").removeAttr('disabled');
            }else{
                $(".lowBalanceValueType[value='1']").attr('disabled',true);
                $(".lowBalanceValueType[value='0']").attr('checked',true);
                $("#ClientPercentageNotifyBalance").attr('disabled',true);
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
            $('.low_balance_config_table').find('input,select').removeAttr('disabled');
        } else {
            $('.low_balance_config_table').find('input,select').attr('disabled',true);
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


        $("#ClientMode").change(ClientModeChange);


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
    })
</script>