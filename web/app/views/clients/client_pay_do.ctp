<ul class="breadcrumb">
    <li><?php __('You are here')?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Billing') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Online Payment') ?></li>
</ul>
<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-body">
            <center><h3>Please wait...</h3></center>

            <?php
                $action = $paypalTestMode ? 'https://www.sandbox.paypal.com/cgi-bin/webscr' : 'https://www.paypal.com/cgi-bin/webscr';
            ?>

            <form id="myform" action="<?php echo $action; ?>" <?php if (Configure::read('payline.is_new_window')) echo 'target="_blank"'; ?> method="post">
                <input type="hidden" name="cmd" value="_xclick" />
                <input type="hidden" name="business" value="<?php echo $business; ?>" />
                <input type="hidden" name="item_name" value="Online Payment" />
                <input type="hidden" name="invoice" value="<?php echo $invoice ?>" />
                <input type="hidden" name="amount" value="<?php echo number_format($amount, 2, '.', '') ?>" />
                <input type="hidden" name="currency_code" value="USD" />
                <input type="hidden" name="charset" value="utf-8" />
                <input type="hidden" name="lc" value="US" />
                <input type="hidden" name="notify_url" value="<?php echo $domain . $this->webroot; ?>clients/notify" />
                <input type="hidden" name="return" value="<?php echo $domain . $this->webroot; ?>/payment_history" />
                <input type="hidden" name="cancel_return" value="<?php echo $domain . $this->webroot; ?>/payment_history" />
<!--                <input type="hidden" src="https://www.paypal.com/en_US/i/btn/btn_buynow_LG.gif" border="0" name="submit" alt="--><?php //__('Wait a moment, please')?><!--.." />-->
            </form>


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

                $(function() {
                    $(document).ready(function () {
                        let form = $('#myform');
                        let dataJson = $(form).serializeObject();
                        let formAction = $(form).attr('action');

                        $.ajax("<?php echo $this->webroot;?>clients/writeLog",{
                            type: "POST",
                            data: {
                                url: formAction,
                                data: dataJson
                            }
                        });

                        form.submit();
                    });
                });
            </script>

        </div>
    </div>
</div>