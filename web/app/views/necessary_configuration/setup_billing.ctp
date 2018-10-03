<style>
    input{width: 220px;}
</style>
<ul class="breadcrumb">
    <li><?php __('You are here'); ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Configuration') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Setup'); ?></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Setup'); ?></h4>
    <div class="buttons pull-right">
    </div>
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>

<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <?php echo $this->element("currs/step", array('now' => '4')); ?>
        <div class="widget-body">
            <form class="form-horizontal" method="post" action="">
                <div class="row-fluid widget-body">
                    <div class="span4">
                    </div>
                    <div class="span8">

                        <div class="control-group">
                            <label class="control-label" for="paypal_account"><?php __('Paypal Account') ?>:</label>
                            <div class="controls">
                                <input type="text" id="paypal_account" name="paypal_account" placeholder="Paypal ID" class="validate[required]" value="<?php echo $data[0][0]['paypal_account']; ?>">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="stripe_account"><?php __('Stripe Account') ?>:</label>
                            <div class="controls">
                                <input type="text" id="stripe_account" name="stripe_account" placeholder="strip ID" class="validate[required]" value="<?php echo $data[0][0]['stripe_account']; ?>">
                            </div>
                        </div>
                    </div>
                </div>
                <div id="form_footer" class="button-groups center">
                    <input class="input in-submit btn btn-primary" value="<?php echo __('submit') ?>" type="submit">
                    <input class="input in-button btn btn-default" value="<?php echo __('reset') ?>" type="reset"   style="margin-left: 20px;">
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(function () {
        $("div.navbar").hide();
        $.gritter.add({
            title: '<?php __('Setup Billing'); ?>',
            text: '<?php __('Your system up and running, you need to set the Billing.'); ?>',
            sticky: true
        });
    });
</script>