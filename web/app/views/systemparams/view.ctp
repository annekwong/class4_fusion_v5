<style type="text/css">
    .list .help {
        color: #7D8084;
        font-size: 0.86em;
        padding-left: 5px;
        width: 48%;
    }

    p.grey {
        font-size: 12px;
        font-style: italic;
        color: #bbb;
    }

    input{width: 220px;}
    .list .label, .list .value {width:25%;}
    span.red {color:red;}

    input.width-100 {
        width: 100%;
        max-width: 99%;
    }

    table.table tbody tr td {
        width: 33.33%;
        max-width: 33.33%;
    }

</style>
<script type="text/javascript">

    $('#timezone2').val('<?php echo $post[0][0]['sys_timezone']; ?>');
    function postsysparameter() {

        var is_change_logo = $("#is_change_logo").val();
        var is_change_icon = $("#is_change_icon").val();
        var currency = $('#currency').val();
        var timezone = $('#timezone2').val();

        var mail_host = $('#mail_host').val();
        var mail_from = $('#mail_from').val();

        var yourpay_store_number = $('#yourpay_store_number').val();
        var paypal_account = $('#paypal_account').val();

        var ftp_username = $('#ftp_username').val();
        var ftp_pass = $('#ftp_pass').val();
        var system_admin_email = $('#system_admin_email').val();
        var dateFormat = $('#dateFormat').val();
        var datetimeFormat = $('#datetimeFormat').val();
        var csv_delimiter = $('#csv_delimiter').val();
        var invoices_tplNo = $('#invoices_tplNo').val();
        var invoices_lastNo = $('#invoices_lastNo').val();
        var invoices_fields = $('#invoices_fields').val();
        var invoices_delay = $('#invoices_delay').val();
        var invoices_separate = $('#invoices_separate').val();
        var invoices_cdr_fields = $('#invoices_cdr_fields').val();
        var dr_period = $('#dr_period').val();
        var radius_log_routes = $('#radius_log_routes').val();
        var realm = $('#realm').val();
        var workstation = $('#workstation').val();
        var is_preload = $("#is_preload").val();
        var ftp_email = $('#ftp_email').val();

        var qos_sample_period = $('#qos_sample_period').val();
        var minimal_call_attempt_required = $('#minimal_call_attempt_required').val();
        var low_call_attempt_handling = $('#low_call_attempt_handling').val();

        var lowBalance_period = $('#lowBalance_period').val();
        var events_deleteAfterDays = $('#events_deleteAfterDays').val();
        var stats_rotate_delay = $('#stats_rotate_delay').val();
        var rates_deleteAfterDays = $('#rates_deleteAfterDays').val();
        var cdrs_deleteAfterDays = $('#cdrs_deleteAfterDays').val();
        var logs_deleteAfterDays = $('#logs_deleteAfterDays').val();

        var backup_period = $('#backup_period').val();
        var backup_leave_last = $('#backup_leave_last').val();

        var smtp_secure = $('#smtp_secure').val();
        var report_count = $('#report_count').val();
        var smtphost = $('#smtphost').val();
        var smtpport = $('#smtpport').val();
        var emailusername = $('#emailusername').val();
        var emailpassword = $('#emailpassword').val();
        var fromemail = $('#fromemail').val();
        var emailname = $('#emailname').val();
        var loginemail = $('#loginemail').val();
        var test_email_to = $('#test_email_to').val();
        var switch_port = $('#switch_port').val();
        var switch_ip = $("#switch_ip").val();
        var pdf_tpl = $("#pdf_tpl").val();
        var finance_email = $("#finance_email").val();
        var noc_email = $("#noc_email").val();
        var withdraw_email = $("#withdraw_email").val();
//        var tpl_number = $("#tpl_number").val();
        var search_code_deck = $('#search_code_deck').val();
        var welcome_message = $('#welcome_message').val();
        var landing_page = $('#landing_page').val();
        var bar_color = $('#bar_color').val();
        var inactivity_timeout = $('#inactivity_timeout').val();
        var switch_alias = $('#switch_alias').val();
        var ingress_pdd_timeout = $('#ingress_pdd_timeout').val();
        var egress_pdd_timeout = $('#egress_pdd_timeout').val();
        var ring_timeout = $('#ring_timeout').val();
        var call_timeout = $('#call_timeout').val();

        var show_mutual_balance = $("#show_mutual_balance").val();
        var is_hide_unauthorized_ip = $("#is_hide_unauthorized_ip").val();
        var require_comment = $("#require_comment").val();
        var auto_rate_smtp = $("#auto_rate_smtp").val();
        var auto_rate_smtp_port = $("#auto_rate_smtp_port").val();
        var auto_rate_username = $("#auto_rate_username").val();
        var auto_rate_pwd = $("#auto_rate_pwd").val();
        var auto_rate_mail_ssl = $("#auto_rate_mail_ssl").val();
        var default_us_ij_rule = $("#default_us_ij_rule").val();

        var master_lrn_ip = $("#master_lrn_ip").val();
        var master_lrn_port = $("#master_lrn_port").val();
        var slave_lrn_ip = $("#slave_lrn_ip").val();
        var slave_lrn_port = $("#slave_lrn_port").val();
        var default_billing_decimal = $('#default_billing_decimal').val();
        var cdr_token = $('#cdr_token').val();
        var cdr_token_alias = $('#cdr_token_alias').val();
//        var login_page_content = CKEDITOR.instances['login_page_content'].getData();
        var base_url = $('#base_url').val();
        var cmd_debug = $('#cmd_debug').val();
        var api_pcap_url = $('#api_pcap_url').val();
        var api_invoice_url = $('#api_invoice_url').val();
        var api_import_url = $('#api_import_url').val();
        var enable_client_download_rate = $('#enable_client_download_rate').val();
        var enable_client_delete_trunk = $('#enable_client_delete_trunk').val();
        var enable_client_disable_trunk = $('#enable_client_disable_trunk').val();
        var $this_textarea = $("#signup_content");
        var email_address_fields = [system_admin_email, finance_email, noc_email, ftp_email];
        var email_error = false;
        if($this_textarea.css('display') != 'none'){
            var signup_content = $this_textarea.val();
        }else{
            var $this_textarea_id = $this_textarea.attr('id');
            var signup_content = CKEDITOR.instances[$this_textarea_id].getData();
        }

        if (default_billing_decimal == '' || default_billing_decimal < 0) {
            default_billing_decimal = 6; // 6 is default val
        }

        if (default_billing_decimal)
        {
            var reg = /^[0-9\ ]+$/;

            if (!reg.test(default_billing_decimal) || default_billing_decimal > 6)
            {
                jGrowl_to_notyfy('Default Billing Decimal is number only, less than or equal to 6!', {theme: 'jmsg-error'});
                return false;
            }
        }


        $.each(email_address_fields, function(i, email){
            if(email && email.match(/^(([^<>()[\]\\,;:\s@\"]+(\.[^<>()[\]\\,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))*/) == null) {
                jGrowl_to_notyfy('Invalid email address in Notification Setting!',{'theme':'jmsg-error'});
                email_error = true;
                return false;
            }
        })
        if(email_error){
            return false;
        }

        if (ingress_pdd_timeout == '') {
            jGrowl_to_notyfy('Ingress PDD Timeout is required!', {theme: 'jmsg-error'});
            return false;
        }
        if (egress_pdd_timeout == '') {
            jGrowl_to_notyfy('Egress PDD Timeout is required!', {theme: 'jmsg-error'});
            return false;
        }
        if (ring_timeout == '') {
            jGrowl_to_notyfy('Ring Timeout is required!', {theme: 'jmsg-error'});
            return false;
        }
        if (call_timeout == '') {
            jGrowl_to_notyfy('Call Timeout Timeout is required!', {theme: 'jmsg-error'});
            return false;
        }

        if (inactivity_timeout)
        {
            var reg = /^[0-9\ ]+$/;

            if (!reg.test(inactivity_timeout))
            {
                jGrowl_to_notyfy('Inactivity Timeout Numbers only!', {theme: 'jmsg-error'});
                return false;
            }
        }

        if($('#master_lrn_ip').val() && !$('#master_lrn_ip').val().toString().match(/\b\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}\b/)){
            jQuery(this).jGrowlError('Wrong Master LRN IP Format!', {theme: 'jmsg-error'});
            return false;
        }
        if($('#slave_lrn_ip').val() && !$('#slave_lrn_ip').val().toString().match(/\b\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}\b/)){
            jQuery(this).jGrowlError('Wrong Slave LRN IP Format!', {theme: 'jmsg-error'});
            return false;
        }

        $(".fakeloader").fakeLoader({
            timeToHide:0,
            bgColor:"rgba(25, 25, 25, .3)",
            spinner:"spinner2"
        });
        $.post("<?php echo $this->webroot ?>systemparams/ajax_update.json",
            {
                show_mutual_balance: show_mutual_balance,
                withdraw_email: withdraw_email,
                noc_email: noc_email,
                finance_email: finance_email,
                pdf_tpl: pdf_tpl,
//                tpl_number: tpl_number,
                switch_ip: switch_ip,
                switch_port: switch_port,
                system_admin_email: system_admin_email,
                loginemail: loginemail,
                emailname: emailname,
                test_email_to: test_email_to,
                fromemail: fromemail,
                emailpassword: emailpassword,
                emailusername: emailusername,
                smtpport: smtpport,
                smtphost: smtphost,
                currency: currency,
                timezone: timezone,
                mail_host: mail_host,
                mail_from: mail_from,
                report_count: report_count,
                ftp_username: ftp_username,
                ftp_pass: ftp_pass,
                dateFormat: dateFormat,
                datetimeFormat: datetimeFormat,
                csv_delimiter: csv_delimiter,
                invoices_tplNo: invoices_tplNo,
                invoices_lastNo: invoices_lastNo,
                invoices_fields: invoices_fields,
                invoices_delay: invoices_delay,
                invoices_separate: invoices_separate,
                smtp_secure: smtp_secure,
                invoices_cdr_fields: invoices_cdr_fields,
                dr_period: dr_period,
                radius_log_routes: radius_log_routes,
                lowBalance_period: lowBalance_period,
                events_deleteAfterDays: events_deleteAfterDays,
                stats_rotate_delay: stats_rotate_delay,
                events_deleteAfterDays:events_deleteAfterDays,
                rates_deleteAfterDays: rates_deleteAfterDays,
                search_code_deck: search_code_deck,
                cdrs_deleteAfterDays: cdrs_deleteAfterDays,
                logs_deleteAfterDays: logs_deleteAfterDays,
                backup_period: backup_period,
                realm: realm,
                workstation: workstation,
                backup_leave_last: backup_leave_last,
                qos_sample_period: qos_sample_period,
                minimal_call_attempt_required: minimal_call_attempt_required,
                low_call_attempt_handling: low_call_attempt_handling,
                welcome_message: welcome_message,
                landing_page: landing_page,
                bar_color: bar_color,
                inactivity_timeout: inactivity_timeout,
                is_preload: is_preload,
                yourpay_store_number: yourpay_store_number,
                paypal_account: paypal_account,
                switch_alias: switch_alias,
                ingress_pdd_timeout: ingress_pdd_timeout,
                egress_pdd_timeout: egress_pdd_timeout,
                ring_timeout: ring_timeout,
                ftp_email: ftp_email,
                call_timeout: call_timeout,
                is_hide_unauthorized_ip: is_hide_unauthorized_ip,
                require_comment: require_comment,
                auto_rate_smtp:auto_rate_smtp,
                auto_rate_smtp_port:auto_rate_smtp_port,
                auto_rate_username:auto_rate_username,
                auto_rate_pwd:auto_rate_pwd,
                auto_rate_mail_ssl:auto_rate_mail_ssl,
                is_change_logo:is_change_logo,
                is_change_icon:is_change_icon,
                default_us_ij_rule: default_us_ij_rule,
                master_lrn_ip:master_lrn_ip,
                master_lrn_port:master_lrn_port,
                slave_lrn_ip:slave_lrn_ip,
                slave_lrn_port:slave_lrn_port,
                default_billing_decimal:default_billing_decimal,
                cdr_token:cdr_token,
                cdr_token_alias: cdr_token_alias,
//                login_page_content: login_page_content,
                base_url: base_url,
                cmd_debug: cmd_debug,
                signup_content: signup_content,
                api_import_url: api_import_url,
                api_invoice_url: api_invoice_url,
                api_pcap_url: api_pcap_url,
                enable_client_download_rate: enable_client_download_rate,
                enable_client_delete_trunk: enable_client_delete_trunk,
                enable_client_disable_trunk: enable_client_disable_trunk
            },
            function (text) {
                if(text == '1'){
                    jGrowl_to_notyfy('<?php __('Systemparametershavebeenupdatedyouneedtorestartthesystemtotakeeffect'); ?>', {theme: 'jmsg-success'});
                }else if (text == 2){
                    jGrowl_to_notyfy('<?php __('Save LRN setting failed'); ?>', {theme: 'jmsg-error'});
                }else{
                    jGrowl_to_notyfy('<?php __('Edit failed'); ?>', {theme: 'jmsg-error'});
                }
                $(".fakeloader").hide();
            },
            'json');

        $.ajax({
            'url' : '<?php echo $this->webroot ?>systemparams/ajax_update_token',
            'type' : 'POST',
            'dataType' : 'json',
            'data' : $("#token_form").serialize(),
            'success' : function(data) {
                if (data.status == 0){
                    jGrowl_to_notyfy('<?php __('Save token failed'); ?>', {theme: 'jmsg-error'});
                }
            }
        });


        // window.setTimeout("window.location.reload();", 3000);


    }


</script>

<ul class="breadcrumb">
    <li><?php __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>systemparams/view"><?php __('Configuration') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>systemparams/view"><?php echo __('Syssetting') ?></a></li>
</ul>
<div class="heading-buttons">
    <h4 class="heading"><?php __('System Setting') ?></h4>
</div>
<div class="separator bottom"></div>
<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-head">
            <?php echo $this->element("advance_setting/tab",array('active' => 'basic')); ?>
        </div>
        <div class="widget-body">

            <div class="widget" data-collapse-closed="true" data-toggle="collapse-widget">
                <div class="widget-head"><h4 class="heading"><?php __('System Logo')?></h4></div>
                <div class="widget-body">
                    <table class="table table-bordered">
                        <tbody>
                        <tr>
                            <td class="right">
                                <?php __('Change Logo File') ?><br>
                                <p class="grey">(Only png, jpg, bmp or jpeg and 1024 MB)</p>
                            </td>
                            <td><span id="image_log"><img width="120px" height="45px" src="<?php echo $logo.'?d='.time(); ?>" /></span></td>
                            <td style="text-align: center;">
                                <!--                                <form enctype="multipart/form-data" action="--><?php //echo $this->webroot; ?><!--systemparams/change_logo" method="post">-->
                                <input id="logo_img" type="file" name="logoimg"  />&nbsp;&nbsp;
                                <input type="hidden" id="is_change_logo" />
                                <!--                                </form>-->
                            </td>
                        </tr>
                        <tr>
                            <td class="right">
                                <?php __('Change Favicon Icon') ?><br>
                                <p class="grey">(Only ico and 1024 MB)</p>
                            </td>
                            <td><span id="icon_img"><img width="32px" height="32px" src="<?php echo $favicon.'?d='.time(); ?>" /></span></td>
                            <td style="text-align: center;">
                                <!--                                <form enctype="multipart/form-data" action="--><?php //echo $this->webroot; ?><!--systemparams/change_icon" method="post">-->
                                <input id="icon_img_import" type="file" name="iconimg" style="" />&nbsp;&nbsp;
                                <input type="hidden" id="is_change_icon" />
                                <!--                                </form>-->
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!--div class="widget" data-collapse-closed="true" data-toggle="collapse-widget">
                <div class="widget-head"><h4 class="heading"><?php __('Registration Confirmation Content')?></h4></div>
                <div class="widget-body">
                    <table class="form table dynamicTable tableTools table-bordered  table-white">
                        <colgroup>
                            <col width="90%">
                            <col width="10%">
                        </colgroup>
                        <tbody>
                        <tr>
                            <td>
                                <?php
            echo $form->input('signup_content' ,array(
                'type' => 'textarea',
                'label' => false,
                'div' => false,
                'value' => $post[0][0]['signup_content'],
                'class' => 'mail_template_content'
            ));
            ?>
                            </td>
                            <td class="mail_content_tags">
                                <h4><i class="icon-tags"></i><?php __('Tags'); ?>:</h4>
                                <?php
            $tags = array('first_name');
            foreach($tags as $tag): ?>
                                    <span class="btn btn-block btn-default">{<?php echo $tag; ?>}</span>
                                <?php endforeach; ?>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div-->

            <div class="widget" data-collapse-closed="true" data-toggle="collapse-widget">
                <div class="widget-head"><h4 class="heading"><?php __('Operationsystem')?></h4></div>
                <div class="widget-body">
                    <table class="table table-bordered">
                        <tbody>
                        <tr>
                            <td class="right"><?php echo __('Base Domain URL') ?> </td>
                            <td>
                                <input type="text" id="base_url" value="<?php echo $post[0][0]['base_url'] ?>" name="base_url" class="input in-text" />
                            </td>
                            <td>

                            </td>
                        </tr>
                        <tr>
                            <td class="right"><?php echo __('Command Debug Log Path') ?> </td>
                            <td>
                                <input type="text" id="cmd_debug" value="" name="cmd_debug" class="input in-text" />
                            </td>
                            <td>

                            </td>
                        </tr>
                        <tr>
                            <td class="right"><?php echo __('Switch Alias') ?> </td>
                            <td>
                                <input type="text" id="switch_alias" value="<?php echo $post[0][0]['switch_alias'] ?>" name="switch_alias" class="input in-text" />
                            </td>
                            <td>

                            </td>
                        </tr>
                        <tr>
                            <td class="right"><?php echo __('Welcome Message') ?> </td>
                            <td>
                                <input type="text" id="welcome_message" value="<?php echo $post[0][0]['welcome_message'] ?>" name="welcome_message" class="input in-text" />
                            </td>
                            <td>

                            </td>
                        </tr>
                        <tr>
                            <td class="right"><?php echo __('System currency') ?> </td>
                            <td><?php
                                echo $form->input('currency', array('id' => 'currency', 'name' => 'currency', 'options' => $currency, 'selected' => $post[0][0]['sys_currency'], 'label' => false,
                                    'div' => false, 'type' => 'select'));
                                ?></td>
                            <td><?php echo __('Default System Currency', true); ?></td>
                        </tr>
                        <tr>
                            <td class="right"><?php __('Timezone') ?> </td>
                            <td><?php
                                $t = array(
                                    '-1200' => 'GMT -12:00',
                                    '-1100' => 'GMT -11:00',
                                    '-1000' => 'GMT -10:00',
                                    '-0900' => 'GMT -09:00',
                                    '-0800' => 'GMT -08:00',
                                    '-0700' => 'GMT -07:00',
                                    '-0600' => 'GMT -06:00',
                                    '-0500' => 'GMT -05:00',
                                    '-0400' => 'GMT -04:00',
                                    '-0300' => 'GMT -03:00',
                                    '-0200' => 'GMT -02:00',
                                    '-0100' => 'GMT -01:00',
                                    '+0000' => 'GMT +00:00',
                                    '+0100' => 'GMT +01:00',
                                    '+0200' => 'GMT +02:00',
                                    '+0300' => 'GMT +03:00',
                                    '+0400' => 'GMT +04:00',
                                    '+0500' => 'GMT +05:00',
                                    '+0600' => 'GMT +06:00',
                                    '+0700' => 'GMT +07:00',
                                    '+0800' => 'GMT +08:00',
                                    '+0900' => 'GMT +09:00',
                                    '+1000' => 'GMT +10:00',
                                    '+1100' => 'GMT +11:00',
                                    '+1200' => 'GMT +12:00',
                                );
                                echo $form->input('currency', array('id' => 'timezone2', 'name' => 'timezone2', 'options' => $t, 'selected' => $post[0][0]['sys_timezone'], 'label' => false,
                                    'div' => false, 'type' => 'select'));
                                ?></td>
                            <td><?php echo __('Default System Timezone Used For Billing And Reporting', true); ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="right"><?php echo __('Code Deck') ?> </td>
                            <td>
                                <select id="search_code_deck" class="select in-select" name="search_code_deck">
                                    <option value=""> </option>
                                    <?php for ($i = 0; $i < count($codecs_s); $i++)
                                    { ?>
                                        <option value="<?php echo $codecs_s[$i][0]['code_deck_id'] ?>"><?php echo $codecs_s[$i][0]['name'] ?></option>
                                    <?php } ?>
                                </select>
                            </td>
                            <td><?php echo __('Code Deck', true); ?></td>
                        </tr>
                        <tr>
                            <td class="right"><?php echo __('Pre-load data on display') ?> </td>
                            <td>
                                <select name="is_preload" id="is_preload">
                                    <option value="true" <?php if ($post[0][0]['is_preload'] == 1) echo 'selected="selected"'; ?>><?php __('True') ?></option>
                                    <option value="false" <?php if ($post[0][0]['is_preload'] == 0) echo 'selected="selected"'; ?>><?php __('False') ?></option>
                                </select>
                            </td>
                            <td></td>
                        </tr>
                        <tr>
                            <td class="right"><?php echo __('Different Report Count') ?> </td>
                            <td>
                                <select name="report_count" id="report_count">
                                    <option value="0" <?php if ($post[0][0]['report_count'] == 0) echo 'selected="selected"' ?>>1<?php __('hr') ?></option>
                                    <option value="1" <?php if ($post[0][0]['report_count'] == 1) echo 'selected="selected"' ?>>24<?php __('hr') ?></option>
                                </select>
                            </td>
                            <td></td>
                        </tr>
                        <tr>
                            <td class="right"><?php echo __('Landing Page') ?> </td>
                            <td>
                                <select name="landing_page" id="landing_page">
                                    <option value="0" <?php if ($post[0][0]['landing_page'] == 0) echo 'selected="selected"' ?>><?php __('QoSReport') ?></option>
                                    <option value="1" <?php if ($post[0][0]['landing_page'] == 1) echo 'selected="selected"' ?>><?php __('Summary Report') ?></option>
                                    <option value="2" <?php if ($post[0][0]['landing_page'] == 2) echo 'selected="selected"' ?>><?php __('Orig-Term Report') ?></option>
                                    <option value="3" <?php if ($post[0][0]['landing_page'] == 3) echo 'selected="selected"' ?>><?php __('Carrier Management') ?></option>
                                </select>
                            </td>
                            <td></td>
                        </tr>
                        <!--
                        <tr>
                          <td><?php echo __('Invoice Naming Conversion') ?>:</td>
                          <td>
                              <input type="text" name="invoice_name" id="invoice_name" value="<?php echo $post[0][0]['invoice_name'] ?>" />
                          </td>
                          <td></td>
                        </tr>
                        <tr>
                            <td><?php echo __('Title Bar Color') ?>:</td>
                            <td>
                                <input class="color" type="text" name="bar_color" id="bar_color" value="<?php echo $post[0][0]['bar_color'] ?>" />
                            </td>
                            <td>Default:6B9B20</td>
                        </tr>
                        -->
                        <tr>
                            <td class="right"><?php echo __('Inactivity Timeout') ?> </td>
                            <td>
                                <input type="text" name="inactivity_timeout" id="inactivity_timeout" value="<?php echo $post[0][0]['inactivity_timeout'] ?>" />
                            </td>
                            <td><?php __('Minute') ?></td>
                        </tr>
                        <tr>
                            <td class="right"><?php echo __('Show Mutual Balance') ?> </td>
                            <td>
                                <select name="show_mutual_balance" id="show_mutual_balance" >
                                    <option value="0" <?php if (!$post[0][0]['is_show_mutual_balance'])
                                    { ?>selected="selected"<?php } ?> ><?php __('False') ?></option>
                                    <option value="1" <?php if ($post[0][0]['is_show_mutual_balance'])
                                    { ?>selected="selected"<?php } ?> ><?php __('True') ?></option>
                                </select>
                            </td>
                            <td></td>
                        </tr>

                        <tr>
                            <td class="right"><?php echo __('Require Comment Logging After Update') ?> </td>
                            <td>
                                <select name="require_comment" id="require_comment" >
                                    <option value="0" <?php if (!$post[0][0]['require_comment'])
                                    { ?>selected="selected"<?php } ?> ><?php __('False') ?></option>
                                    <option value="1" <?php if ($post[0][0]['require_comment'])
                                    { ?>selected="selected"<?php } ?> ><?php __('True') ?></option>
                                </select>
                            </td>
                            <td></td>
                        </tr>

                        </tbody>
                    </table>
                </div>
            </div>

            <div class="widget" data-collapse-closed="true" data-toggle="collapse-widget">
                <div class="widget-head"><h4 class="heading"><?php __('System Default Timeout Setting')?></h4></div>
                <div class="widget-body">
                    <table class="table table-bordered">
                        <tbody>
                        <tr class="row-1">
                            <td class="right"><span class="red">*</span><?php __('Ingress PDD Timeout') ?> </td>
                            <td><input type="text" id="ingress_pdd_timeout" value="<?php echo $post[0][0]['ingress_pdd_timeout'] ?>" name="ingress_pdd_timeout" class="input in-text"></td>
                            <td><?php __('ms') ?></td>
                        </tr>
                        <tr class="row-2">
                            <td class="right"><span class="red">*</span><?php echo __('Egress PDD Timeout', true); ?> </td>
                            <td><input type="text" id="egress_pdd_timeout" value="<?php echo $post[0][0]['egress_pdd_timeout'] ?>" name="egress_pdd_timeout" class="input in-text"></td>
                            <td><?php __('ms') ?></td>
                        </tr>
                        <tr class="row-1">
                            <td class="right"><span class="red">*</span><?php __('Ring Timeout') ?> </td>
                            <td><input type="text" id="ring_timeout" value="<?php echo $post[0][0]['ring_timeout'] ?>" name="ring_timeout" class="input in-text"></td>
                            <td><?php __('s') ?></td>
                        </tr>
                        <tr class="row-2">
                            <td class="right"><span class="red">*</span><?php echo __('Call Timeout', true); ?> </td>
                            <td><input type="text" id="call_timeout" value="<?php echo $post[0][0]['call_timeout'] ?>" name="call_timeout" class="input in-text"></td>
                            <td><?php __('s') ?></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!--            <div class="widget" data-collapse-closed="true" data-toggle="collapse-widget">-->
            <!--                <div class="widget-head"><h4 class="heading">--><?php //__('Login Page Design')?><!--</h4></div>-->
            <!--                <div class="widget-body">-->
            <!--                    --><?php //echo $form->input('login_page_content',array('type' => 'textarea','label' => false,
            //                        'div' => false,'class' => 'login_page_content','value' => isset($login_page_content) ? $login_page_content : '')); ?>
            <!---->
            <!--                </div>-->
            <!--            </div>-->

            <div class="widget" data-collapse-closed="true" data-toggle="collapse-widget">
                <div class="widget-head"><h4 class="heading"><?php __('System E-mail Setting')?></h4></div>
                <div class="widget-body">
                    <table class="table table-bordered">
                        <tbody>
                        <tr class="row-1">
                            <td class="right"><span class="red">*</span><?php __('Mailserverhost') ?> </td>
                            <td><input type="text" id="smtphost" value="<?php echo $post[0][0]['smtphost'] ?>" name="smtphost" class="input in-text"></td>
                            <td><?php echo __('Hostname or IP Address Of SMTP Server', true); ?></td>
                        </tr>
                        <tr class="row-2">
                            <td class="right"><span class="red">*</span><?php echo __('SMTP Port', true); ?> </td>
                            <td><input type="text" id="smtpport" value="<?php echo $post[0][0]['smtpport'] ?>" name="smtpport" class="input in-text"></td>
                            <td><?php echo __('Port For the SMTP Server', true); ?></td>
                        </tr>
                        <tr class="row-1">
                            <td class="right"><span class="red">*</span><?php echo __('SMTP Username', true); ?> </td>
                            <td><input type="text" id="emailusername"   value="<?php echo $post[0][0]['emailusername'] ?>"  name="emailusername" class="input in-text"></td>
                            <td></td>
                        </tr>
                        <tr class="row-2">
                            <td class="right"><span class="red">*</span><?php echo __('SMTP Password', true); ?> </td>
                            <td><input type="password" id="emailpassword"     value="<?php echo $post[0][0]['emailpassword'] ?>"  name="emailpassword" class="input in-text"></td>
                            <td></td>
                        </tr>
                        <tr class="row-1">
                            <td class="right"><span class="red">*</span><?php echo __('SMTP Login Authentication', true); ?> </td>
                            <td><?php
                                echo $form->input('loginemail', array('id' => 'loginemail', 'name' => 'loginemail', 'options' => array('true' => __('true', true), 'false' => __('false', true)), 'selected' => array_keys_value($post, '0.0.loginemail'), 'label' => false,
                                    'div' => false, 'type' => 'select'));
                                ?></td>
                            <td></td>
                        </tr>
                        <tr class="row-2">
                            <td class="right"><span class="red">*</span><?php echo __('From Email', true); ?> </td>
                            <td><input type="text" id="fromemail"     value="<?php echo $post[0][0]['fromemail'] ?>"  name="fromemail" class="input in-text"></td>
                            <td></td>
                        </tr>
                        <tr class="row-1">
                            <td class="right"><span class="red">*</span><?php echo __('Email Sender Name', true); ?> </td>
                            <td><input type="text" id="emailname"  value="<?php echo $post[0][0]['emailname'] ?>"  name="emailname" class="input in-text"></td>
                            <td></td>
                        </tr>

                        <tr class="row-2">
                            <td class="right"><span class="red"></span><?php echo __('SMTP Secure', true); ?> </td>
                            <td>
                                <select name="smtp_secure" id="smtp_secure">
                                    <option value="0" <?php if ($post[0][0]['smtp_secure'] == 0) echo 'selected' ?>></option>
                                    <option value="1" <?php if ($post[0][0]['smtp_secure'] == 1) echo 'selected' ?>><?php __('TLS') ?></option>
                                    <option value="2" <?php if ($post[0][0]['smtp_secure'] == 2) echo 'selected' ?>><?php __('SSL') ?></option>
                                    <option value="3" <?php if ($post[0][0]['smtp_secure'] == 3) echo 'selected' ?>><?php __('NTLM') ?></option>
                                </select>
                            </td>

                            <?php if (stristr($post[0][0]['smtphost'], '@gmail.com') !== false): ?>
                                <td style="text-align:left;line-height:40px;">
                                    <p id="ntlm_panel">
                                        <?php __('Realm') ?> <input type="text" name="realm" id="realm" value="<?php echo $post[0][0]['realm'] ?>"  /> &nbsp; &nbsp; <?php __('workstation') ?> <input type="text" name="workstation" id="workstation" value="<?php echo $post[0][0]['workstation'] ?>" />
                                    </p>
                                </td>
                            <?php endif; ?>

                        </tr>

                        <tr class="row-1">
                            <td class="right"><span class="red">*</span><?php echo __('Send Test To', true); ?> </td>
                            <td>
                                <input type="text" id="test_email_to" name="test_email_to" />
                            </td>
                            <td><input type="button" class="btn btn-primary" value="<?php __('Test') ?>" id="testemail" /></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="widget" data-collapse-closed="true" data-toggle="collapse-widget">
                <div class="widget-head"><h4 class="heading"><?php __('Notification Setting')?></h4></div>
                <div class="widget-body">
                    <table class="table table-bordered">
                        <tbody>
                        <tr class="row-2">
                            <td class="right"><?php echo __('System Admin Email', true); ?> </td>
                            <td><input type="text" id="system_admin_email"     value="<?php echo $post[0][0]['system_admin_email'] ?>"  name="system_admin_email" class="input in-text"></td>
                            <td></td>
                        </tr>
                        <tr class="row-1">
                            <td class="right"><?php echo __('Finance Email', true); ?> </td>
                            <td><input type="text" id="finance_email"     value="<?php echo $post[0][0]['finance_email'] ?>"  name="finance_email" class="input in-text"></td>
                            <td></td>
                        </tr>
                        <tr class="row-2">
                            <td class="right"><?php echo __('NOC Email', true); ?> </td>
                            <td><input type="text" id="noc_email"     value="<?php echo $post[0][0]['noc_email'] ?>"  name="noc_email" class="input in-text"></td>
                            <td></td>
                        </tr>
                        <tr class="row-2">
                            <td class="right"><?php echo __('FTP Failure Notification Email', true); ?> </td>
                            <td><input type="text" id="ftp_email"     value="<?php echo $post[0][0]['ftp_email'] ?>"  name="ftp_email" class="input in-text"></td>
                            <td></td>
                        </tr>
                        <?php if (Configure::read('system.type') == 2): ?>
                            <tr class="row-2">
                                <td class="right"><?php echo __('Withdraw Email', true); ?> </td>
                                <td><input type="text" id="withdraw_email"     value="<?php echo $post[0][0]['withdraw_email'] ?>"  name="withdraw_email" class="input in-text"></td>
                                <td></td>
                            </tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="widget" data-collapse-closed="true" data-toggle="collapse-widget">
                <div class="widget-head"><h4 class="heading"><?php echo __('Default Billing Decimal', true); ?></h4></div>
                <div class="widget-body">
                    <table class="table table-bordered">
                        <tbody>
                        <tr class="row-2">
                            <td class="right"><?php echo __('Default Billing Decimal', true); ?></td>
                            <td>
                                <input type="text" id="default_billing_decimal" value="<?php echo $post[0][0]['default_billing_decimal']; ?>"  name="def" class="input in-text"></td>
                            <td></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>


            <div class="widget" data-collapse-closed="true" data-toggle="collapse-widget">
                <div class="widget-head"><h4 class="heading"><?php __('UI Configuration')?></h4></div>
                <div class="widget-body">
                    <table class="table table-bordered">
                        <tbody>
                        <tr class="row-1">
                            <td class="right"><?php __('Include Calls From Unauthorized IPs in CDR') ?> </td>
                            <td>
                                <select id="is_hide_unauthorized_ip" name="is_hide_unauthorized_ip">
                                    <option value="1" <?php if ($post[0][0]['is_hide_unauthorized_ip'])
                                    { ?>selected="selected"<?php } ?> ><?php __('False') ?></option>
                                    <option value="0" <?php if (!$post[0][0]['is_hide_unauthorized_ip'])
                                    { ?>selected="selected"<?php } ?> ><?php __('True') ?></option>
                                </select>
                            </td>
                            <td></td>
                        </tr>
                        <tr class="row-1">
                            <td class="right"><?php __('Default US IJ Rule') ?> </td>
                            <td>
                                <select id="default_us_ij_rule" name="default_us_ij_rule">
                                    <option value="0" <?php if ($post[0][0]['default_us_ij_rule'] == '0') echo 'selected' ?>><?php __('A-Z') ?></option>
                                    <option value="1" <?php if ($post[0][0]['default_us_ij_rule'] == '1') echo 'selected' ?>><?php __('US Non-JD') ?></option>
                                    <option value="2" <?php if ($post[0][0]['default_us_ij_rule'] == '2') echo 'selected' ?>><?php __('US JD') ?></option>
                                    <option value="3" <?php if ($post[0][0]['default_us_ij_rule'] == '3') echo 'selected' ?>><?php __('OCN-LATA-JD') ?></option>
                                    <option value="4" <?php if ($post[0][0]['default_us_ij_rule'] == '4') echo 'selected' ?>><?php __('OCN-LATA-NON-JD') ?></option>
                                </select>
                            </td>
                            <td></td>
                        </tr>
                        <tr class="row-1">
                            <td class="right"><?php __('Enable the client to download rate deck') ?> </td>
                            <td>
                                <select id="enable_client_download_rate" name="enable_client_download_rate">
                                    <option value="true" <?php if ($post[0][0]['enable_client_download_rate'] == 'true')
                                    { ?>selected="selected"<?php } ?> ><?php __('True') ?></option>
                                    <option value="false" <?php if ($post[0][0]['enable_client_download_rate'] == 'false')
                                    { ?>selected="selected"<?php } ?> ><?php __('False') ?></option>
                                </select>
                            </td>
                            <td></td>
                        </tr>
                        <tr class="row-1">
                            <td class="right"><?php __('Enable the client to delete ingress trunk') ?> </td>
                            <td>
                                <select id="enable_client_delete_trunk" name="enable_client_delete_trunk">
                                    <option value="true" <?php if ($post[0][0]['enable_client_delete_trunk'] == 'true')
                                    { ?>selected="selected"<?php } ?> ><?php __('True') ?></option>
                                    <option value="false" <?php if ($post[0][0]['enable_client_delete_trunk'] == 'false')
                                    { ?>selected="selected"<?php } ?> ><?php __('False') ?></option>
                                </select>
                            </td>
                            <td></td>
                        </tr>
                        <tr class="row-1">
                            <td class="right"><?php __('Enable the client to disable ingress trunk') ?> </td>
                            <td>
                                <select id="enable_client_disable_trunk" name="enable_client_disable_trunk">
                                    <option value="true" <?php if ($post[0][0]['enable_client_disable_trunk'] == 'true')
                                    { ?>selected="selected"<?php } ?> ><?php __('True') ?></option>
                                    <option value="false" <?php if ($post[0][0]['enable_client_disable_trunk'] == 'false')
                                    { ?>selected="selected"<?php } ?> ><?php __('False') ?></option>
                                </select>
                            </td>
                            <td></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="widget" data-collapse-closed="true" data-toggle="collapse-widget">
                <div class="widget-head"><h4 class="heading"><?php __('QoS Routing')?></h4></div>
                <div class="widget-body">
                    <table class="table table-bordered">
                        <tbody>
                        <tr class="row-1">
                            <td class="right"><?php __('Qos Sample Period') ?> </td>
                            <td>
                                <select id="qos_sample_period" name="qos_sample_period">
                                    <option value=""></option>
                                    <option value="0" <?php if ($post[0][0]['qos_sample_period'] == '0') echo 'selected' ?>>15 <?php __('min') ?></option>
                                    <option value="2" <?php if ($post[0][0]['qos_sample_period'] == '2') echo 'selected' ?>>1 <?php __('hr') ?></option>
                                    <option value="3" <?php if ($post[0][0]['qos_sample_period'] == '3') echo 'selected' ?>>1 <?php __('day') ?></option>
                                </select>
                            </td>
                            <td></td>
                        </tr>

                        <tr class="row-1">
                            <td class="right"><?php __('Minimal Call Attempt Required') ?> </td>
                            <td>
                                <input type="text" id="minimal_call_attempt_required" name="minimal_call_attempt_required" value="<?php echo $post[0][0]['minimal_call_attempt_required'] ?>" />
                            </td>
                            <td></td>
                        </tr>

                        <tr class="row-1">
                            <td class="right"><?php __('Low Call Attempt Handling') ?> </td>
                            <td>
                                <select name="low_call_attempt_handling" id="low_call_attempt_handling">
                                    <option value=""></option>
                                    <option value="0" <?php if ($post[0][0]['low_call_attempt_handling'] == '0') echo 'selected' ?>><?php __('Use Latest Value') ?></option>
                                    <option value="1" <?php if ($post[0][0]['low_call_attempt_handling'] == '1') echo 'selected' ?>><?php __('Set to') ?> &lt;<?php __('none') ?>&gt;</option>
                                </select>
                            </td>
                            <td></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>


            <!--div class="widget" data-collapse-closed="true" data-toggle="collapse-widget">
                <div class="widget-head"><h4 class="heading"><?php __('Automatic Rate Processing')?></h4></div>
                <div class="widget-body">
                    <table class="table table-bordered">
                        <tbody>
                        <tr class="row-2">
                            <td class="right"><?php __('SMTP Server'); ?> </td>
                            <td><input type="text" id="auto_rate_smtp"     value="<?php echo $post[0][0]['auto_rate_smtp'] ?>"  name="auto_rate_smtp" class="input in-text"></td>
                            <td></td>
                        </tr>
                        <tr class="row-2">
                            <td class="right"><?php __('SMTP Server Port'); ?> </td>
                            <td><input type="text" id="auto_rate_smtp_port"     value="<?php echo $post[0][0]['auto_rate_smtp_port'] ?>"  name="auto_rate_smtp_port" class="input in-text"></td>
                            <td></td>
                        </tr>
                        <tr class="row-2">
                            <td class="right"><?php __('Username'); ?> </td>
                            <td><input type="text" id="auto_rate_username"     value="<?php echo $post[0][0]['auto_rate_username'] ?>"  name="auto_rate_username" class="input in-text"></td>
                            <td></td>
                        </tr>
                        <tr class="row-2">
                            <td class="right"><?php __('Password'); ?> </td>
                            <td><input type="password" id="auto_rate_pwd"     value="<?php echo $post[0][0]['auto_rate_pwd'] ?>"  name="auto_rate_pwd" class="input in-text"></td>
                            <td></td>
                        </tr>
                        <tr class="row-2">
                            <td class="right"><?php __('SMTP Type'); ?> </td>
                            <td>
                                <select name="auto_rate_mail_ssl" id="auto_rate_mail_ssl" >
                                    <option value=""></option>
                                    <option value="1" <?php if($post[0][0]['auto_rate_mail_ssl'] == 1): ?>selected="selected"<?php endif; ?>><?php __('SSL'); ?></option>
                                    <option value='2' <?php if($post[0][0]['auto_rate_mail_ssl'] == 2): ?>selected="selected"<?php endif; ?>><?php __('TLS'); ?></option>
                                </select>
                            <td></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div-->


            <div class="widget" data-collapse-closed="true" data-toggle="collapse-widget">
                <div class="widget-head"><h4 class="heading"><?php __('LRN Setting')?></h4></div>
                <div class="widget-body">
                    <table class="table table-bordered">
                        <tbody>
                        <tr class="row-2">
                            <td class="right"><?php __('Master LRN IP'); ?> </td>
                            <td><input type="text" class="validate[required,custom[ip]]" id="master_lrn_ip"  value="<?php echo $lrn_data['C4Lrn']['srv1_ip'] ?>"  name="C4Lrn[srv1_ip]"></td>
                            <td></td>
                        </tr>
                        <tr class="row-2">
                            <td class="right"><?php __('Master LRN Port'); ?> </td>
                            <td><input type="text" id="master_lrn_port" value="<?php echo $lrn_data['C4Lrn']['srv1_port'] ?>"  name="C4Lrn[srv1_port]" ></td>
                            <td></td>
                        </tr>
                        <tr class="row-2">
                            <td class="right"><?php __('Slave LRN IP'); ?> </td>
                            <td><input type="text" class="validate[required,custom[ip]]" id="slave_lrn_ip"  value="<?php echo $lrn_data['C4Lrn']['srv2_ip'] ?>"  name="C4Lrn[srv2_ip]"></td>
                            <td></td>
                        </tr>
                        <tr class="row-2">
                            <td class="right"><?php __('Slave LRN Port'); ?> </td>
                            <td><input type="text" id="slave_lrn_port" value="<?php echo $lrn_data['C4Lrn']['srv2_port'] ?>"  name="C4Lrn[srv2_port]" ></td>
                            <td></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>


            <!--div class="widget" data-collapse-closed="true" data-toggle="collapse-widget">
                <div class="widget-head"><h4 class="heading"><?php __('Token Setting')?></h4></div>
                <div class="widget-body">
                    <form id="token_form"-->
            <!--                    <div class="widget">-->
            <!--                        --><?php //if (!empty($switches)): ?>
            <!--                            --><?php //foreach ($switches as $switch): ?>
            <!--                            <input type="hidden" name="switch_id[]" value="--><?php //echo $switch[0]['id']; ?><!--" class='switch_id' />-->
            <!--                            --><?php //endforeach; ?>
            <!--                        --><?php //endif; ?>
            <!---->
            <!--                        <div class="widget-head"><h4 class="heading">--><?php //__('PCAP Token Setting')?><!--</h4></div>-->
            <!--                        <div class="widget-body">-->
            <!--                            --><?php //if (empty($switches)): ?>
            <!--                                <div class="msg center"><br /><h2>--><?php //__('no_data_found'); ?><!--</h2></div>-->
            <!--                            --><?php //else: ?>
            <!--                                <table class="footable table table-striped tableTools table-bordered  table-white table-primary">-->
            <!--                                    <colgroup>-->
            <!--                                        <col width="20%">-->
            <!--                                        <col width="20%">-->
            <!--                                        <col width="10%">-->
            <!--                                        <col width="50%">-->
            <!--                                    </colgroup>-->
            <!--                                    <thead>-->
            <!--                                    <tr>-->
            <!--                                        <th>--><?php //__('Name'); ?><!--</th>-->
            <!--                                        <th>--><?php //__('IP'); ?><!--</th>-->
            <!--                                        <th>--><?php //__('Port'); ?><!--</th>-->
            <!--                                        <th>--><?php //__('Token'); ?><!--</th>-->
            <!--                                    </tr>-->
            <!--                                    </thead>-->
            <!--                                    --><?php //foreach ($switches as $switch): ?>
            <!--                                        <tr>-->
            <!--                                            <td>--><?php //echo $switch[0]['profile_name']; ?><!--</td>-->
            <!--                                            <td>--><?php //echo $switch[0]['sip_ip']; ?><!--</td>-->
            <!--                                            <td>--><?php //echo $switch[0]['sip_port']; ?><!--</td>-->
            <!--                                            <td>-->
            <!--                                                <textarea style="width:90%;max-width: 90%" name="pcap_token[]" class='pcap_token' >--><?php //echo $switch[0]['pcap_token']; ?><!--</textarea>-->
            <!--                                            </td>-->
            <!--                                        </tr>-->
            <!--                                    --><?php //endforeach; ?>
            <!--                                </table>-->
            <!--                            --><?php //endif; ?>
            <!--                        </div>-->
            <!--                    </div>-->

            <!--div class="widget">
                        <div class="widget-head"><h4 class="heading"><?php __('CDR Token Setting')?></h4></div>
                        <div class="widget-body">
                            <table class="footable table table-striped tableTools table-bordered  table-white table-primary">
                                <colgroup>
                                    <col width="20%">
                                    <col width="80%">
                                </colgroup>
                                <tr>
                                    <td><?php __('Alias'); ?></td>
                                    <td>
                                        <input type="text" id="cdr_token_alias" name="cdr_alias" value="<?php echo $post[0][0]['cdr_token_alias']; ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <td><?php __('Token'); ?></td>
                                    <td>
                                        <textarea style="width:90%;max-width: 90%" id="cdr_token" name="cdr_token" class='cdr_token' ><?php echo $post[0][0]['cdr_token']; ?></textarea>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    </form>
                </div>
            </div-->

            <!--
                <table class="list list-form">
                  <thead>
                    <tr>
                      <td colspan="3" class="last"><?php echo __('Sip Capture Setting', true); ?></td>
                    </tr>
                  </thead>
                  <tbody>
                      <tr class="row-1">
                        <td><?php __('Sip Capture Status') ?>
                          :</td>
                        <td><?php echo trim($sip_capture_status) == 'on' ? '<a class="input in-submit" style="display:block;height:15px;margin:0 auto;" href="' . $this->webroot . 'systemparams/set_capture/off">off</a>' : '<a class="input in-submit" style="display:block;height:15px;margin:0 auto;" href="' . $this->webroot . 'systemparams/set_capture/on">on</a>' ?></td>
                        <td><?php echo __('Current status:', true); ?>&nbsp;<span style="font-weight:bold;color:red;"><?php echo $sip_capture_status; ?></span></td>
                      </tr>

                      <tr class="row-1">
                        <td><?php __('RTP dump Status') ?>
                          :</td>
                        <td><?php echo trim($rtpdump_status) == 'on' ? '<a class="input in-submit" style="display:block;height:15px;margin:0 auto;" href="' . $this->webroot . 'systemparams/set_rptdump/off">off</a>' : '<a class="input in-submit" style="display:block;height:15px;margin:0 auto;" href="' . $this->webroot . 'systemparams/set_rptdump/on">on</a>' ?></td>
                        <td><?php echo __('Current status:', true); ?>&nbsp;<span style="font-weight:bold;color:red;"><?php echo $rtpdump_status; ?></span></td>
                      </tr>
                  </tbody>
                </table>
                -->
            <!--            <table class=" table dynamicTable tableTools table-bordered  table-white">-->
            <!--                <tr class="row-2">-->
            <!--                    <td class="right">--><?php //echo __('PDF Template Place', true) ?><!-- -> --><?php //echo __('Billing Details Location', true) ?><!-- </td>-->
            <!--                    <td>-->
            <!--                        --><?php
            //                        echo $form->input('tpl_number', array('id' => 'tpl_number', 'name' => 'tpl_number', 'options' => array('2' => __('middle', true), '0' => __('bottom', true), '1' => __('top', true)), 'selected' => array_keys_value($post, '0.0.tpl_number'), 'label' => false,
            //                            'div' => false, 'type' => 'select'));
            //                        ?>
            <!--                    </td>-->
            <!--                    <td>--><?php //echo __('Location of the billing details in the invoice', true); ?><!--</td>-->
            <!--                </tr>-->
            <!--                <tr class="row-2">-->
            <!--                    <td class="right">--><?php //echo __('PDF Template Info', true) ?><!-- -> --><?php //echo __('Billing Details', true) ?><!--</td>-->
            <!--                    <td><textarea class="input in-textarea" wrap="virtual" id="pdf_tpl" name="pdf_tpl" style="height:150px;width:450px;">--><?php //echo $post[0][0]['pdf_tpl'] ?><!--</textarea></td>-->
            <!--                    <td>--><?php //echo __('Billing information that you want to include in the invoice', true); ?><!--</td>-->
            <!--                </tr>-->
            <!--            </table>-->



            <?php if ($_SESSION['role_menu']['Configuration']['systemparams']['model_w'])
            { ?>
                <div class="form-buttons center">
                    <input type="button" value="<?php echo __('submit') ?>" onclick="javascript:postsysparameter();
                                return false;" class="btn btn-primary input in-submit">
                    <!--                    <input type="reset" value="Revert" class="btn btn-inverse">-->
                </div>
            <?php } ?>
            <!--  </form>-->
            <div id="loading"></div>
        </div>
    </div>
</div>

<script type="text/javascript" src="<?php echo $this->webroot ?>js/jscolor/jscolor.js"></script>
<script type="text/javascript" src="<?php echo $this->webroot ?>js/ckeditor_full/ckeditor.js"></script>
<script src="<?php echo $this->webroot; ?>ajaxupload/swfupload.js"></script>
<script src="<?php echo $this->webroot; ?>ajaxupload/jquery-asyncUpload-0.1.js"></script>
<script type="text/javascript">

    $(function () {
        let ports = {0:25, 1:587, 2:465};

        $("#icon_img_import").makeAsyncUploader({
            upload_url: '<?php echo $this->webroot ?>uploads/async_upload_img/favicon_tmp',
            flash_url: '<?php echo $this->webroot; ?>ajaxupload/swfupload.swf',
            button_image_url: '<?php echo $this->webroot; ?>ajaxupload/blankButton.png',
            post_params: {"PHPSESSID": "<?php echo session_id(); ?>"},
            file_size_limit: '1024 MB',
            file_types: "*.ico",
            file_types_description: "Only ico file",
            button_text: "<font face='Arial' size='13pt'>Change</span>",
            upload_success_handler: function(file, response) {
                var container = $('#content');
//                $("input[name$=_filename]", container).val(file.name);
                $("input[name=icon_img_import_guid]", container).val('favicon');
                $("#is_change_icon").val(1);
                var tmp_img_path = "<?php echo $this->webroot . 'upload' . DS . 'images'.DS.'tmp'; ?>/favicon_tmp.ico?d=" + new Date();
                var img_html = "<img src='"+tmp_img_path+"' width='32px' height='32px' />";
                $("#icon_img").html(img_html);
            }
        });
        $("#logo_img").makeAsyncUploader({
            upload_url: '<?php echo $this->webroot ?>uploads/async_upload_img/logo_tmp',
            flash_url: '<?php echo $this->webroot; ?>ajaxupload/swfupload.swf',
            button_image_url: '<?php echo $this->webroot; ?>ajaxupload/blankButton.png',
            post_params: {"PHPSESSID": "<?php echo session_id(); ?>"},
            file_size_limit: '1024 MB',
            file_types: "*.png;*.jpg;*.bmp;*.jpeg",
            file_types_description: "Only img file",
            button_text: "<font face='Arial' size='13pt'>Change</span>",
            upload_success_handler: function(file, response) {
                var container = $('#content');
//                $("input[name$=_filename]", container).val(file.name);
                $("input[name=logo_img_guid]", container).val('logo');
                $("#is_change_logo").val(1);
                var tmp_img_path = "<?php echo $this->webroot . 'upload' . DS . 'images'.DS.'tmp'; ?>/logo_tmp.png?d=" + new Date();
                var img_html = "<img src='"+tmp_img_path+"' width='120px' height='45px' />";
                $("#image_log").html(img_html);
            }
        });

        $('#smtp_secure').change(function () {
            if ($(this).val() == '3') {
                $('#ntlm_panel').show();
                $('#smtpport').val('').removeClass('no-event');
            } else {
                $('#smtpport').val(ports[$(this).val()]).addClass('no-event');
                $('#ntlm_panel').hide();
            }
        });



        $('#smtp_secure').change();

        $('#testemail').click(function () {
            var address = $('#test_email_to').val();
            $.ajax({
                url: '<?php echo $this->webroot ?>systemparams/testsmtp',
                type: 'POST',
                dataType: 'text',
                data: {email: address},
                beforeSend: function () {
                    $(".fakeloader").fakeLoader({
                        timeToHide:0,
                        bgColor:"rgba(25, 25, 25, .3)",
                        spinner:"spinner2"
                    });
                },
                success: function (data) {
                    $('.fakeloader').hide();
                    showMessages_new("[" + data + "]");
                }
            });
        });
    });
</script>

<script>
    $(".collapse-toggle").live('click',function(){
        var $this_textarea = $(this).parent().next().find('textarea');
        var $this_textarea_id = $this_textarea.attr('id');
        var $this_textarea_is_display = $this_textarea.css('display');
        if($this_textarea_is_display != 'none'){
            CKEDITOR.replace($this_textarea_id);
        }
    });

    $('.mail_content_tags').find('span').click(function(){
        var $tag_value = $(this).html();
        var $this_textarea_id = $(this).closest('tr').find('textarea').attr('id');
        var editor = CKEDITOR.instances[$this_textarea_id];
        editor.insertHtml( $tag_value );
    });
</script>
