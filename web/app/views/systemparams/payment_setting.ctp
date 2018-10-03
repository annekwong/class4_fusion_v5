<style>
    input{
        width: 250px;
        max-width: 250px;
    }
    select, textarea, input[type="text"]{margin-bottom: 0}
</style>
<ul class="breadcrumb">
    <li><?php __('You are here'); ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>systemparams/payment_setting">
        <?php __('Configuration') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>systemparams/payment_setting">
        <?php echo __('Payment Setting'); ?></a></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Payment Setting'); ?></h4>
    <div class="buttons pull-right">
    </div>
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>

<div class="innerLR">

    <div class="widget widget-heading-simple widget-body-white">
        <div class="widget-body">
            <form method="post" action="" >
                <table class="list table dynamicTable tableTools table-bordered  table-white">
                    <colgroup>
                        <col width="30%">
                        <col width="40%">
                        <col width="30%">
                    </colgroup>
                    <tbody>
                        <tr>
                            <td class="right"><?php __('Stripe Secret Key') ?> </td>
                            <td colspan="2"><input type="text" id="stripe_account"  value="<?php echo $tmp[0][0]['stripe_account']  ?>"  name="stripe_account" class="input in-text"></td>
                        </tr>
                        <tr>
                            <td class="right"><?php __('Stripe Publishable Key') ?> </td>
                            <td colspan="2"><input type="text" id="stripe_public_account"  value="<?php echo $tmp[0][0]['stripe_public_account']  ?>"  name="stripe_public_account" class="input in-text"></td>
                        </tr>
                        <tr>
                            <td class="right"><?php __('Stripe Service Charge') ?> </td>
                            <td colspan="2"><input type="text" id="stripe_service_charge"  value="<?php echo $tmp[0][0]['stripe_service_charge']  ?>"  name="stripe_service_charge" class="input in-text validate[required,custom[number]]"></td>
                        </tr>
                        <tr>
                            <td class="right"><?php __('Paypal Account') ?> </td>
                            <td colspan="2">
                                <input type="text" id="paypal_account"  value="<?php echo $tmp[0][0]['paypal_account']  ?>"  name="paypal_account" class="input in-text">
                                <input type="checkbox" id="paypal_test_mode" name="paypal_test_mode" class="input in-text" <?php echo $tmp[0][0]['paypal_test_mode'] ? 'checked' : '';  ?>>
                                Enable PayPal Test Mode
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: right;"><?php __('Charge Type: ')?></td>
                            <td colspan="2">
                                <select name="charge_type">
                                    <option value="0" <?php echo $tmp[0][0]['charge_type'] =='0' ? 'selected': ''?> >Charge Actual Amount</option>
                                    <option value="1" <?php echo $tmp[0][0]['charge_type'] =='1' ? 'selected': ''?> >Create Actual Received Amount</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td class="right"><?php __('Paypal Service Charge') ?> </td>
                            <td colspan="2"><input type="text" id="paypal_service_charge"  value="<?php echo $tmp[0][0]['paypal_service_charge']  ?>"  name="paypal_service_charge" class="input in-text validate[required,custom[number]]"></td>
                        </tr>
                        <tr>
                            <td style="text-align: right;"><?php __('Enable Payment Confirmation') ?></td>
                            <td colspan="2"><input type="checkbox" name="daily_payment_confirmation" id="daily_payment" <?php if ($tmp[0][0]['daily_payment_confirmation'])
{ ?>checked="checked" <?php } ?> /></td>
                        </tr>
                        <tr>
                            <td style="text-align: right;"><?php __('Email Addresses') ?> </td>
                            <td colspan="2"><input type="text" class="validate[required,custom[email]]" name="daily_payment_email" id="daily_email" value="<?php echo $tmp[0][0]['daily_payment_email']; ?>" <?php if (!$tmp[0][0]['daily_payment_confirmation'])
{ ?> disabled="disabled" <?php } ?> /></td>
                        </tr>
                        <tr>
                            <td style="text-align: right;"><?php __('Send Email Notification to Client') ?> </td>
                            <td colspan="2"><input type="checkbox" name="notify_carrier" id="notify_carrier" <?php if ($tmp[0][0]['notify_carrier'])
{ ?>checked="checked" <?php } ?> /></td>
                        </tr>
                        <tr>
                            <td style="text-align: right;"><?php __('Carbon Copy To') ?> </td>
                            <td colspan="2"><input type="text" class="validate[required,custom[email]]" name="notify_carrier_cc" id="notify_carrier_cc" value="<?php echo $tmp[0][0]['notify_carrier_cc']; ?>" <?php if (!$tmp[0][0]['notify_carrier'])
{ ?>disabled="disabled"  <?php } ?> /></td>
                        </tr>

                    </tbody>
                </table>

                <div id="form_footer" class="button-groups center widget-body">

                    <input class="input in-submit btn btn-primary" value="<?php echo __('submit') ?>" type="submit">

                    <input class="input in-button btn btn-default" value="<?php echo __('reset') ?>" type="reset"   style="margin-left: 20px;">

                </div>
            </form>

        </div>
    </div>
</div>

<script type="text/javascript" >

    $(function() {

        $("#daily_payment").click(function() {
            var daily_payment = $(this).attr('checked');
            $("#daily_email").attr('disabled', 'disabled');
            if (daily_payment == 'checked')
            {
                $("#daily_email").removeAttr('disabled');
            }
        });


        $("#notify_carrier").click(function() {

            var notify_carrier = $(this).attr('checked');
            $("#notify_carrier_cc").attr('disabled', 'disabled');
            if (notify_carrier == 'checked')
            {
                $("#notify_carrier_cc").removeAttr('disabled');
            }

        });


    })

</script>