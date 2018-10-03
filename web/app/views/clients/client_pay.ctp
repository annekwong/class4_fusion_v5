<ul class="breadcrumb">
    <li><?php __('You are here')?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
     <?php if($_SESSION['login_type'] == 3):?>
        <li><?php __('Client Portal') ?></li>
    <?php else:?>
        <li><?php __('Billing') ?></li>
    <?php endif;?>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Online Payment') ?></li>
</ul>
<div class="heading-buttons">
    <h4 class="heading"><?php __('Online Payment') ?></h4>
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>
<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-head">
            <ul class="tabs">
                <?php if(isset($pay_type_arr[1])): ?>
                    <li class="active">
                        <a id="paypal" class="glyphicons usd" href="javascript:void(0)">
                            <?php __('Paypal') ?>
                            <i></i>
                        </a>
                    </li>
                <?php endif; ?>
                <?php if(isset($pay_type_arr[2])): ?>
                    <?php if(isset($pay_type_arr[1])): ?>
                        <li>
                    <?php else: ?>
                        <li class="active">
                    <?php endif; ?>
                    <a id="stripe" class="glyphicons usd" href="javascript:void(0)">
                        <?php __('Stripe') ?>
                        <i></i>
                    </a>
                    </li>
                <?php endif; ?>
                <li>
                    <a target="_blank" class="glyphicons book_open" href="<?php echo $this->webroot; ?>payment_history">
                        <?php __('Auto Payment Log')?>
                        <i></i>
                    </a>
                </li>

            </ul>
        </div>
        <div class="widget-body">
            <div id="container">
                <form id="myform" action="<?php echo $this->webroot; ?>clients/client_pay_do<?php if(isset($clientId) && !empty($clientId)) echo '/' . $clientId; ?>" method="post">

                    <input type="hidden" id="platform" name="platform" value="0" />
                    <table style="display:none;" id="paypal_table" class="table tableTools table-bordered " border="0" cellspacing="0" cellpadding="0">
                        <tbody>
                        <tr>
                            <td style="text-align: right;"><?php __('Payment Amount:')?></td>
                            <td>
                                <input type="text" name="chargetotal2" />
                                <input type="hidden" name="invoice_id" value="<?php echo isset($invoiceID) ? $invoiceID : '';?>" />
                            </td>
                        </tr>
                        <!--tr>
                            <td style="text-align: right;"><?php __('Service Charge: ($ USD)')?></td>
                            <td>
                                <?=$paypal_service_charge?>
                            </td>
                        </tr-->
                        </tbody>
                        <tfoot>
                        <tr>
                            <td colspan="2" style="text-align: center;">
                                <input type="submit" value="<?php __('Submit')?>" class="btn btn-primary" />
                            </td>
                        </tr>
                        </tfoot>
                    </table>

                    <!--                    <table style="display:none;" id="credit_card_table" class="table dynamicTable tableTools table-bordered "  border="0" cellspacing="0" cellpadding="0">-->
                    <!--                        <tbody>-->
                    <!--                        <tr>-->
                    <!--                            <th colspan="2">--><?php //__('Credit Card Information')?><!--</th>-->
                    <!--                        </tr>-->
                    <!--                        <tr>-->
                    <!--                            <td>--><?php //__('Credit Card Type')?><!--:</td>-->
                    <!--                            <td>-->
                    <!--                                <input type="radio" name="credit_card_type" checked="checked" value="0" />--><?php //__('Visa')?><!-- <input type="radio" name="credit_card_type" value="1" />--><?php //__('MasterCard')?><!-- <input type="radio" name="credit_card_type" value="2" />--><?php //__('American Express')?><!-- <input type="radio" name="credit_card_type" value="3" />--><?php //__('Discover')?>
                    <!--                            </td>-->
                    <!--                        </tr>-->
                    <!--                        <tr>-->
                    <!--                            <td>--><?php //__('Credit Card Account')?><!--:</td>-->
                    <!--                            <td>-->
                    <!--                                <input type="text" name="cardnumber" />-->
                    <!--                            </td>-->
                    <!--                        </tr>-->
                    <!--                        <tr>-->
                    <!--                            <td>--><?php //__('Credit Card Code')?><!--:</td>-->
                    <!--                            <td>-->
                    <!--                                <input type="text" name="cvmvalue" />-->
                    <!--                            </td>-->
                    <!--                        </tr>-->
                    <!--                        <tr>-->
                    <!--                            <td>--><?php //__('Credit Card Expiration Date')?><!--:</td>-->
                    <!--                            <td>-->
                    <!--                                --><?php //__('Month')?>
                    <!--                                <select name="cardexpmonth">-->
                    <!--                                    <option value="01" selected="1">01</option>-->
                    <!--                                    <option value="02">02</option>-->
                    <!--                                    <option value="03">03</option>-->
                    <!--                                    <option value="04">04</option>-->
                    <!--                                    <option value="05">05</option>-->
                    <!--                                    <option value="06">06</option>-->
                    <!--                                    <option value="07">07</option>-->
                    <!--                                    <option value="08">08</option>-->
                    <!--                                    <option value="09">09</option>-->
                    <!--                                    <option value="10">10</option>-->
                    <!--                                    <option value="11">11</option>-->
                    <!--                                    <option value="12">12</option>-->
                    <!--                                </select>-->
                    <!--                                --><?php //__('Year')?>
                    <!--                                <select name="cardexpyear">-->
                    <!--                                    --><?php //for ($i = 0; $i <= 99; $i++): ?>
                    <!--                                        <option>--><?php //printf("%02d", $i); ?><!--</option>-->
                    <!--                                    --><?php //endfor; ?>
                    <!--                                </select>-->
                    <!--                            </td>-->
                    <!--                        </tr>-->
                    <!--                        <tr>-->
                    <!--                            <td>--><?php //__('Payment Amount: ($ USD)')?><!--</td>-->
                    <!--                            <td>-->
                    <!--                                <input type="text" name="chargetotal1" />-->
                    <!--                            </td>-->
                    <!--                        </tr>-->
                    <!--                        <tr>-->
                    <!--                            <th colspan="2">--><?php //__('Credit Card Billing Address')?><!--</th>-->
                    <!--                        </tr>-->
                    <!--                        <tr>-->
                    <!--                            <td>--><?php //__('Street Address 1')?><!--:</td>-->
                    <!--                            <td>-->
                    <!--                                <input type="text" name="address1" />-->
                    <!--                            </td>-->
                    <!--                        </tr>-->
                    <!--                        <tr>-->
                    <!--                            <td>--><?php //__('Street Address 2')?><!--:</td>-->
                    <!--                            <td>-->
                    <!--                                <input type="text" name="address2" />-->
                    <!--                            </td>-->
                    <!--                        </tr>-->
                    <!--                        <tr>-->
                    <!--                            <td>--><?php //__('City')?><!--:</td>-->
                    <!--                            <td>-->
                    <!--                                <input type="text" name="city" />-->
                    <!--                            </td>-->
                    <!--                        </tr>-->
                    <!--                        <tr>-->
                    <!--                            <td>-->
                    <!--                                <table style="font-size:inherit;color:#2D3238;">-->
                    <!--                                    <tr>-->
                    <!--                                        <td>--><?php //__('State/Province')?><!--:</td>-->
                    <!--                                        <td>-->
                    <!--                                            <input type="text" name="state_province" />-->
                    <!--                                        </td>-->
                    <!--                                    </tr>-->
                    <!--                                </table>-->
                    <!--                            </td>-->
                    <!--                            <td>-->
                    <!--                                <table style="font-size:inherit;color:#2D3238;">-->
                    <!--                                    <tr>-->
                    <!--                                        <td>--><?php //__('Zip/Postal Code')?><!--:</td>-->
                    <!--                                        <td>-->
                    <!--                                            <input type="text" name="zip_code" />-->
                    <!--                                        </td>-->
                    <!--                                    </tr>-->
                    <!--                                </table>-->
                    <!--                            </td>-->
                    <!--                        </tr>-->
                    <!--                        <tr>-->
                    <!--                            <td>--><?php //__('Country')?><!--:</td>-->
                    <!--                            <td>-->
                    <!--                                <input type="text" name="country" />-->
                    <!--                            </td>-->
                    <!--                        </tr>-->
                    <!--                        </tbody>-->
                    <!--                        <tfoot>-->
                    <!--                        <tr>-->
                    <!--                            <td colspan="2" style="text-align: center;">-->
                    <!--                                <input type="submit" value="Submit" class="btn btn-primary" />-->
                    <!--                            </td>-->
                    <!--                        </tr>-->
                    <!--                        </tfoot>-->
                    <!--                    </table>-->

                    <table style="display:none;" id="stripe_table" class="table tableTools table-bordered " border="0" cellspacing="0" cellpadding="0">
                        <tbody>
                        <tr>
                            <td class="right"><?php __('Payment Amount:')?></td>
                            <td>
                                <input  name="monthly_fee" class="validate[required,custom[positiveNumber]]" id="monthly_fee" type="text" />
                            </td>
                        </tr>
                        <!--tr>
                            <td style="text-align: right;"><?php __('Service Charge: ($ USD)')?></td>
                            <td>
                                <?=$stripe_service_charge?>
                            </td>
                        </tr-->
                        <tr>
                            <td class="right"><?php __('Card Number')?>:</td>
                            <td>
                                <input  name="card_num" id="card-number" placeholder="1234-5678-9000-0000"
                                        type="text" autocomplete="off"   class="card-number validate[required]" />
                            </td>
                        </tr>
                        <tr>
                            <td class="right"><?php __('Expiration (MM/YYYY)')?> :</td>
                            <td>
                                <input type="text" name="cardexpmonth"  class="width15 validate[required] expiration_mouth"  />/
                                <input type="text" size="4" name="cardexpyear" class="validate[required] expiration_year"  />
                            </td>
                        </tr>
                        <tr>
                            <td class="right"><?php __('CVC'); ?>:</td>
                            <td>
                                <input name="cvc" id="card_cvc"  type="text" size="3" autocomplete="off"
                                       class="card-cvc validate[required]"  />
                            </td>
                        </tr>
                        </tbody>
                        <tfoot>
                        <tr>
                            <td colspan="2" style="text-align: center;">
                                <input type="submit" value="Submit" id="redB" class="btn btn-primary" />
                            </td>
                        </tr>
                        </tfoot>
                    </table>


                </form>


            </div>
        </div>
    </div>
</div>
<!-- InputMask Plugin -->
<script src="<?php echo $this->webroot; ?>common/theme/scripts/plugins/forms/jquery-inputmask/dist/jquery.inputmask.bundle.min.js"></script>
<script type="text/javascript">
    $(function(){
        $("#card-number").inputmask({"mask": "9999-9999-9999-9999"});
        $("#card_cvc").inputmask({"mask": "999"});
        $(".expiration_mouth").inputmask("m",{ "placeholder": "mm" });
        $(".expiration_year").inputmask("y",{ "placeholder": "yyyy" });
    })
</script>


<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
<script type="text/javascript">
    // this identifies your website in the createToken call below
    Stripe.setPublishableKey('<?php echo $scripe_key; ?>');

    function stripeResponseHandler(status, response) {
        console.log(response);
        if (response.error) {
            // re-enable the submit button
            $('#redB').removeAttr("disabled");
            $('#redB').attr("class", "btn btn-primary");
            // show the errors on the form
            jGrowl_to_notyfy(response.error.message, {theme: 'jmsg-error'});
        } else {
            // token contains id, last4, and card type
            var token = response['id'];
            // insert the token into the form so it gets submitted to the server
            $("#myform").append("<input type='hidden' name='stripeToken' value='" + token + "' />");
            // and submit
            $("#myform").get(0).submit();
        }
    }

    $(document).ready(function() {
        $('#redB').removeAttr("disabled");
        $('#redB').attr("class", "btn btn-primary");
    });
</script>


<script>
    $(function() {
        var $paypal = $('#paypal');
        var $credit_card = $('#credit_card');
        var $stripe = $('#stripe');

        var $paypal_table = $('#paypal_table');
        var $credit_card_table = $('#credit_card_table');
        var $stripe_table = $('#stripe_table');

        var $platform = $('#platform');

        $paypal.click(function() {
            if (!$paypal.parent().hasClass('active'))
            {
                $paypal.parent().addClass('active');
            }

            $stripe.parent().removeClass('active');
            $stripe_table.hide();
            $credit_card.parent().removeClass('active');
            $paypal_table.show();
            $credit_card_table.hide();
            $platform.val(0);
            $('#redB').attr("class", "btn btn-primary");
        });

        $credit_card.click(function() {
            if (!$credit_card.parent().hasClass('active'))
            {
                $credit_card.parent().addClass('active');
            }
            $paypal.parent().removeClass('active');
            $paypal_table.hide();

            $stripe.parent().removeClass('active');
            $stripe_table.hide();

            $credit_card_table.show();
            $platform.val(1);
            $('#redB').attr("class", "btn btn-primary");
        });

        $stripe.click(function() {
            if (!$stripe.parent().hasClass('active'))
            {
                $stripe.parent().addClass('active');
            }
            $paypal.parent().removeClass('active');
            $paypal_table.hide();
            $credit_card.parent().removeClass('active');
            $credit_card_table.hide();

            $stripe_table.show();
            $platform.val(2);
            $('#redB').attr("class", "btn btn-primary");
        });

        <?php if($pay_type == 1): ?>
            <?php if(isset($pay_type_arr[1])): ?>
                $paypal.click();
            <?php elseif(isset($pay_type_arr[2])): ?>
                $stripe.click();
            <?php else: ?>
                //window.location.href = "<?php $this->webroot?>payment_history";
            <?php endif;  ?>
        <?php elseif ($pay_type == 2): ?>
            $credit_card.click();
        <?php elseif($pay_type == 3): ?>
            <?php if(isset($pay_type_arr[1])): ?>
                $stripe.click();

            <?php elseif(isset($pay_type_arr[2])): ?>
                $paypal.click();
            <?php else: ?>
                //window.location.href = "<?php $this->webroot?>payment_history";
            <?php endif;  ?>
        <?php endif;  ?>



        $(".method:eq(0)").trigger('click');

        $('#myform').submit(function() {
            var flag = true;

            if ($('#platform').val() == 1)
            {
                if ($('input[name=cardnumber]').val() == '')
                {
                    jGrowl_to_notyfy('Card Number cant not be empty!', {theme: 'jmsg-error'});
                    return false;
                }
                if ($('input[name=cardexpmonth]').val() == '')
                {
                    jGrowl_to_notyfy('Card Expire Month cant not be empty!', {theme: 'jmsg-error'});
                    return false;
                }
                if ($('input[name=cardexpyear]').val() == '')
                {
                    jGrowl_to_notyfy('Card Expire Year cant not be empty!', {theme: 'jmsg-error'});
                    return false;
                }

                if (isNaN($('input[name=chargetotal1]').val()) || $('input[name=chargetotal1]').val() == '')
                {
                    jGrowl_to_notyfy('Amount is invalid!', {theme: 'jmsg-error'});
                    return false;
                }
            }if ($('#platform').val() == 2){
                var test = $("#expiration").val();
//                console.log(test);
//                return false;
//                if(!$("#myform").validationEngine('validate'))
//                    return false;
                $('#redB').attr("disabled", "disabled");
                $('#redB').attr("class", "");
                // createToken returns immediately - the supplied callback submits the form if there are no errors
                Stripe.createToken({
                    number: $('.card-number').val(),
                    cvc: $('.card-cvc').val(),
                    exp_month: $('.expiration_mouth').val(),
                    exp_year: $('.expiration_year').val()
                }, stripeResponseHandler);
                return false; // submit from callback


                //return true;

            } else {
                if (isNaN($('input[name=chargetotal2]').val()) || $('input[name=chargetotal2]').val() == '')
                {
                    jGrowl_to_notyfy('Amount is invalid!', {theme: 'jmsg-error'});
                    flag = false;
                }
            }






            return flag;
        });
    });
</script>