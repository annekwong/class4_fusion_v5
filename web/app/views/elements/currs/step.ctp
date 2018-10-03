<div class="widget-head">
    <ul>
        <li <?php echo $now == '1' ? 'class="active"' : ''?>>
            <a class="glyphicons paperclip step" id="step1" data-value="1" href="<?php echo $this->webroot?>necessary_configuration/currs">
                <i></i>
                <span class="strong"><?php __('Step 1') ?></span>
                <span><?php __('Currency'); ?></span>
            </a>
        </li>
        <li <?php echo $now == '2' ? 'class="active"' : ''?>>
            <a class="glyphicons no-js paperclip step" id="step2" data-value="2" href="<?php echo $this->webroot ?>necessary_configuration/codes_deck/<?php echo $a_z_code_deck_id . '/' . $user_id; ?>">
                <i></i>
                <span class="strong"><?php __('Step 2') ?></span>
                <span><?php __('Code Deck') ?></span>
            </a>
        </li>
        <li <?php echo $now == '3' ? 'class="active"' : ''?>>
            <a class="glyphicons no-js paperclip step" id="step3" data-value="3" href="<?php echo $this->webroot ?>necessary_configuration/mailtmp/<?php echo $user_id; ?>">
                <i></i>
                <span class="strong"><?php __('Step 3') ?></span>
                <span><?php __('Mail Template ') ?></span>
            </a>
        </li>

        <li <?php echo $now == '4' ? 'class="active"' : ''?>>
            <a class="glyphicons no-js paperclip step" id="step4" data-value="4" href="<?php echo $this->webroot ?>necessary_configuration/setup_billing/<?php echo $user_id; ?>">
                <i></i>
                <span class="strong"><?php __('Step 4') ?></span>
                <span><?php __('Billing') ?></span>
            </a>
        </li>

        <li <?php echo $now == '5' ? 'class="active"' : ''?>>
            <a class="glyphicons no-js paperclip step" id="step5" data-value="5" href="<?php echo $this->webroot ?>necessary_configuration/setup_mail_config/<?php echo $user_id; ?>">
                <i></i>
                <span class="strong"><?php __('Step 5') ?></span>
                <span><?php __('Mail Config') ?></span>
            </a>
        </li>

        <li <?php echo $now == '6' ? 'class="active"' : ''?>>
            <a class="glyphicons no-js paperclip step" id="step6" data-value="6" href="<?php echo $this->webroot ?>necessary_configuration/setup_payment_term/<?php echo $user_id; ?>">
                <i></i>
                <span class="strong"><?php __('Step 6') ?></span>
                <span><?php __('Payment Term') ?></span>
            </a>
        </li>

        <li <?php echo $now == '7' ? 'class="active"' : ''?>>
            <a class="glyphicons no-js paperclip step" id="step7" data-value="7" href="<?php echo $this->webroot ?>necessary_configuration/setup_voip_gateway/<?php echo $user_id; ?>">
                <i></i>
                <span class="strong"><?php __('Step 7') ?></span>
                <span><?php __('Voip Gateway') ?></span>
            </a>
        </li>

        <!--<li <?php /*echo $now == '8' ? 'class="active"' : ''*/?>>
            <a class="glyphicons no-js paperclip step" id="step8" data-value="8" href="<?php /*echo $this->webroot */?>necessary_configuration/setup_lrn/<?php /*echo $user_id; */?>">
                <i></i>
                <span class="strong"><?php /*__('Step 8') */?></span>
                <span><?php /*__('Set LRN') */?></span>
            </a>
        </li>-->

        <li <?php echo $now == '9' ? 'class="active"' : ''?>>
            <a class="glyphicons no-js paperclip step" id="step9" data-value="9" href="<?php echo $this->webroot ?>necessary_configuration/setup_jurisdiction/<?php echo $user_id; ?>">
                <i></i>
                <span class="strong"><?php __('Step 9') ?></span>
                <span><?php __('Jurisdiction') ?></span>
            </a>
        </li>

        <li>
            <a class="glyphicons no-js paperclip step" id="stepf" data-value="f" href="<?php echo $this->webroot ?>homes/init_login_url/<?php echo $user_id; ?>/1">
                <i></i>
                <span class="strong"><?php __('Check And Finish') ?></span>
<!--                <span>--><?php //__('Set Jurisdiction') ?><!--</span>-->
            </a>
        </li>

    </ul>
</div>


<script>
    $(function(){
        /*$('.widget-head li a:gt(1)').prop('disabled',true);
        $('.widget-head li a:gt(1)').css('cursor','not-allowed');





        $('.widget-head li a').on('click',function(){





            var a_active = $('.widget-head li.active a').data('value');

            if($(this).index('.widget-head li a') < a_active - 1){
                return true;
            }
            var flag = false;
            $("#tab"+a_active+ " [class *= 'validate']").each(function(){
                if ($(this).validationEngine('validate'))
                {

                    flag = true;
                    return false;
                }
            });
            if(flag) return false;

            var now = $(this).data('value');
            $('.widget-head li a:eq('+now+')').prop('disabled',false);
            $('.widget-head li a:eq('+now+')').css('cursor','pointer');
        });*/

        /*$('.next').click(function () {

            var a_active = $('.widget-head li.active a').data('value');


            var next = a_active + 1;
            $('#step' + next).click();
        });

        $('.prev').click(function () {
            var prev = $('.widget-head li.active a').data('value') - 1;
            $('#step' + prev).click();
        });*/
        <?php /*else: */?>/*
            $('.widget-head li a').prop('disabled',true);
            $('.widget-head li a').css('cursor','not-allowed');
            $('.widget-head li a').unbind();

        */<?php /*endif;*/?>
    })
</script>
<style>
    .widget.widget-tabs > .widget-head ul li a i:before {
        font-size: 12px;
    }
    .widget.widget-tabs > .widget-head ul li a.glyphicons{
        font-size: 12px;
        padding-left: 22px;
        padding-right: 5px;
    }
    .widget .widget-head > .glyphicons i:before, .widget .widget-head ul .glyphicons i:before{
        font-size: 12px;
        width: 20px;
        left: 2px;
    }
    
    /*@media screen and (min-width: 1511px) {*/
        /*.widget.widget-tabs > .widget-head{*/
            /*height: 35px;*/
        /*}*/
    /*}*/
    @media (max-width: 1505px) {
        .widget.widget-tabs > .widget-head{
            height: 70px;
        }
    }
</style>
