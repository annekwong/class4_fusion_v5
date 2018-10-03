<div class="overflow_x">
    <?php if ($_SESSION['role_menu']['Routing']['gatewaygroups:view']['model_w']) { ?>
        <div style="margin-top:20px;margin-bottom: 20px;"><a id="addHost"
                                                             class="btn btn-primary btn-icon glyphicons circle_plus"
                                                             onclick="return false;"
                                                             href="#"><i></i><?php __('Add Host') ?> </a></div>
    <?php } ?>
    <table
        class="footable table table-striped tableTools table-bordered  table-white table-primary default footable-loaded table-condensed"
        id="host_table">
        <thead>
        <tr>
            <th class="baidiv nohei"><span rel="helptip" class="helptip" id="ht-100002"
                                           title="Name of an account in JeraSoft yht system (for statistics and reports)"><?php echo __('Type', true); ?></span>
                <!-- <span class="tooltip" id="ht-100002-tooltip"</span>--></th>

            <th class="baidiv nohei">Host</th>

            <th class="baidiv nohei"><span rel="helptip" class="helptip" id="ht-100004"
                                           title="Technical prefix, that is used to identify users, when multiple clients use same gateway"><?php echo __('port', true); ?></span>
                <!-- <span class="tooltip" id="ht-100004-tooltip"></span>--></th>

            <th style="display:none;"><span rel="helptip" class="helptip" id="ht-100004"
                                            title="Require Register"><?php echo __('Require Register', true); ?></span>
            </th>
            <?php if ($_SESSION['role_menu']['Routing']['gatewaygroups:view']['model_w']) { ?>
                <!--                --><?php //if (isset($is_egress) and $is_egress):
                ?>
                <!--                <th class="heidiv"><span rel="helptip" class="helptip" id="ht-100004" title="Require Authenication">--><?php //echo __('Require Authenication', true);
                ?><!--</span></th>-->
                <th class="heidiv1"><span rel="helptip" class="helptip" id="ht-100004"
                                          title="Register Server IP"><?php echo __('IP', true); ?></span></th>
                <th class="heidiv1"><span rel="helptip" class="helptip" id="ht-100004"
                                          title="Register Server Port"><?php echo __('Port', true); ?></span></th>
                <th class="heidiv1"><span rel="helptip" class="helptip" id="ht-100004"
                                          title="Expires"><?php echo __('Expires', true); ?></span></th>
                <!--
                <th class="baidiv"><span rel="helptip" class="helptip" id="ht-100004" title="Authentication Option Active"><?php echo __('Option Active', true); ?></span></th>
                <th class="baidiv"><span rel="helptip" class="helptip" id="ht-100004" title="Authentication Option Retry"><?php echo __('Option Retry', true); ?></span></th>
                <th class="baidiv"><span rel="helptip" class="helptip" id="ht-100004" title="Authentication Option Interval"><?php echo __('Option Interval', true); ?></span></th>
                -->
                <!--            --><?php //endif;
                ?>
                <th class="heidiv heidiv1"><span rel="helptip" class="helptip" id="ht-100004"
                                                 title="Authentication Username"><?php echo __('Username', true); ?></span>
                </th>
                <th class="heidiv heidiv1"><span rel="helptip" class="helptip" id="ht-100004"
                                                 title="Authentication Password"><?php echo __('Password', true); ?></span>
                </th>


                <th class="heidiv heidiv1">
                    <?php __('Registered') ?>
                </th>
                <!--                <th class="heidiv">-->
                <!--                    --><?php //__('Expires Time')
                ?>
                <!--                </th>-->
                <th class="heidiv1">
                    <?php __('SIP Profile') ?>
                </th>
                <?php if (isset($is_egress) and $is_egress): ?>
                    <th class="last baidiv"><?php echo __('SIP Option', true); ?></th>
                <?php endif; ?>
                <th class="last baidiv"><?php echo __('action', true); ?></th>
            <?php } ?>
        </tr>
        </thead>
        <tbody class="rows" id="rows-ip">
        <?php
        if (isset($ips) && isset($ips['ip'])) {
            for ($i = 0; $i < count($ips['ip']); $i++) {
                ?>
                <tr>
                    <td class="value baidiv nohei">
                        <select class="ip-host-dd">
                            <option value="ip">IP Address</option>
                            <option value="host">Hostname</option>
                        </select>
                    </td>
                    <td class="value nohei">
                        <input class="baidiv" type="text" style="width: 100%;"
                               onkeyup="value = value.replace(/[^\w\.\/]/ig, '')" name="accounts[ip][]" id="ip"
                               value="<?php echo $ips['ip'][$i]; ?>">
                        <span class="heidiv"><?php echo $ips['ip'][$i]; ?></span>
                    </td>
                    <td class="value nohei"><input type="text" name="accounts[port][]" id="port" maxlength="16"
                                                   value="<?php echo $ips['need_register'][$i]; ?>"></td>

                    <td style="width: 55px;" class="value last"><a title="delete" rel="delete"
                                                                   onclick="$(this).closest('tr').remove();"> <i
                                class="icon-remove"></i> </a></td>
                </tr>
            <?php } ?>
        <?php } ?>


        <?php if (isset($hosts) && is_array($hosts)) { ?>
            <?php foreach ($hosts as $key => $host) { ?>
                <?php if (array_keys_value($host, '0.ip')) {
                    $ip = array_keys_value($host, '0.ip');
                } else {
                    $ip = array_keys_value($host, '0.fqdn');
                }
                // var_dump($host);
                ?>
                <?php $ip = split("/", $ip) ?>
                <tr class="switch<?php echo strval(array_keys_value($host, '0.reg_type')); ?>">
                    <td class="value baidiv nohei">
                        <select class="ip-host-dd">
                            <option value="ip">IP Address</option>
                            <option value="host">Hostname</option>
                        </select>
                    </td>
                    <td class="value baidiv nohei">
                        <input class="nohei" type="text" onkeyup="value = value.replace(/[^\w\.\/]/ig, '')"
                               style="width:180px;" name="accounts[ip][]" class="host_ip" id="ip"
                               value="<?php echo array_keys_value($ip, 0) ?>">
                        <span class="heidiv heidiv1"><?php echo array_keys_value($ip, 0) ?></span>
                        <span style="font-size: 16px;"> / </span>
                        <select onkeyup="value = value.replace(/[^\w\.\/]/ig, '')" style="width: 50px;"
                                name="accounts[mask][]" class="host_ip_mask" id="mask" value="">
                            <option
                                value="32" <?php echo (isset($host[0]['mask']) && $host[0]['mask'] == "32") ? 'selected' : '' ?>>
                                32
                            </option>
                            <option
                                value="31" <?php echo (isset($host[0]['mask']) && $host[0]['mask'] == "31") ? 'selected' : '' ?>>
                                31
                            </option>
                            <option
                                value="30" <?php echo (isset($host[0]['mask']) && $host[0]['mask'] == "30") ? 'selected' : '' ?>>
                                30
                            </option>
                            <option
                                value="29" <?php echo (isset($host[0]['mask']) && $host[0]['mask'] == "29") ? 'selected' : '' ?>>
                                29
                            </option>
                            <option
                                value="28" <?php echo (isset($host[0]['mask']) && $host[0]['mask'] == "28") ? 'selected' : '' ?>>
                                28
                            </option>
                            <option
                                value="27" <?php echo (isset($host[0]['mask']) && $host[0]['mask'] == "27") ? 'selected' : '' ?>>
                                27
                            </option>
                            <option
                                value="26" <?php echo (isset($host[0]['mask']) && $host[0]['mask'] == "26") ? 'selected' : '' ?>>
                                26
                            </option>
                            <option
                                value="25" <?php echo (isset($host[0]['mask']) && $host[0]['mask'] == "25") ? 'selected' : '' ?>>
                                25
                            </option>
                            <option
                                value="24" <?php echo (isset($host[0]['mask']) && $host[0]['mask'] == "24") ? 'selected' : '' ?>>
                                24
                            </option>
                        </select>
                    </td>
                    <td class="value baidiv nohei">
                        <input type="text" name="accounts[port][]" class="host_port nohei" id="port" maxlength="16"
                               value="<?php echo array_keys_value($host, '0.port') ?>">
                        <span class="heidiv heidiv1"><?php echo array_keys_value($host, '0.port') ?></span>
                    </td>
                    <?php if (isset($is_egress) and $is_egress): ?>
                        <td class="value baidiv nohei">
                            <input type="text" name="accounts[options_ping_inv][]"
                                   class="options_ping_inv onlyNumberSp nohei validate[custom[integer],min[0],max[10]]" id="port" maxlength="16"
                                   value="<?php echo array_keys_value($host, '0.options_ping_inv') ?>">
                            <span
                                class="heidiv heidiv1"><?php echo array_keys_value($host, '0.options_ping_inv') ?></span>
                        </td>
                    <?php endif; ?>
                    <!--                    <td class="value" style="display:none;"><input type="checkbox" name="accounts[require_register][]" style="width:70px;" -->
                    <?php //if (array_keys_value($host, '0.need_register') == 1) echo 'checked="checked"' ?><!--  maxlength="16"></td>-->
                    <!--                    --><?php //if (isset($is_egress) and $is_egress): ?>
                    <td class="value heidiv1"><input type="text" class="reg_srv_ip" name="accounts[reg_srv_ip][]"
                                                     style="width:100px;"
                                                     value="<?php echo array_keys_value($host, '0.reg_srv_ip') ?>"></td>
                    <td class="value heidiv1"><input type="text" class="reg_srv_port" name="accounts[reg_srv_port][]"
                                                     style="width:80px;" maxlength="16"
                                                     value="<?php echo array_keys_value($host, '0.reg_srv_port') ?>">
                    </td>
                    <td class="value heidiv1"><input type="text" name="accounts[expires][]" style="width:80px;"
                                                     maxlength="16" class="expires"
                                                     value="<?php echo array_keys_value($host, '0.expires') ?>"></td>
                    <!--
                        <td class="value baidiv">
                            <?php
                    //var_dump($host[0]['option_active']);
                    ?>
                            <input type="checkbox" name="accounts[option_active][]" style="width:100px;" <?php if (array_keys_value($host, '0.option_active') == 1) echo 'checked="checked"  '; ?>  value="<?php echo $key; ?>" maxlength="16">
                        </td>
                        <td class="value baidiv"><input type="text" name="accounts[option_retry][]" style="width:100px;" value="<?php echo array_keys_value($host, '0.option_retry') ?>"></td>
                        <td class="value baidiv"><input type="text" name="accounts[option_interval][]" style="width:80px;"  maxlength="16"  value="<?php echo array_keys_value($host, '0.option_interval') ?>"></td>
                        -->
                    <!--                    --><?php //endif; ?>
                    <td class="value heidiv heidiv1">
                        <span><?php echo array_keys_value($host, '0.username') ?></span>
                        <input type="hidden" name="accounts[username][]" class="host_username" style="width:100px;"
                               maxlength="16" value="<?php echo array_keys_value($host, '0.username') ?>">
                        <!--                        <input type="text" style="display:none;" />-->
                    </td>
                    <td class="value heidiv heidiv1"><input type="password" class="host_password"
                                                            name="accounts[password][]" style="width:100px;"
                                                            maxlength="40"
                                                            value="<?php echo array_keys_value($host, '0.password') ?>">
                    </td>


                    <td class="heidiv heidiv1">
                        <?php if (array_keys_value($host, '0.reg_status') == 0): ?>
                            <?php __('un-register'); ?>
                        <?php elseif (array_keys_value($host, '0.reg_status') == 1): ?>
                            <?php __('registered'); ?>
                        <?php else: ?>
                            <?php __('register failed'); ?>
                        <?php endif; ?>
                    </td>
                    <!--                    --><?php //if(array_keys_value($host, '0.expires_time')): ?>
                    <!--                        <td class="value heidiv">-->
                    <?php //echo date('Y-m-d H:i:s',array_keys_value($host, '0.expires_time')); ?><!--</td>-->
                    <!--                    --><?php //else: ?>
                    <!--                        <td class="value heidiv"></td>-->
                    <!--                    --><?php //endif; ?>
                    <td class="value heidiv1">
                        <select name="accounts[profile_id][]" class="width120 profile_id">
                            <?php foreach ($switch_sip_profiles as $sip_profile_id => $sip_profile_name): ?>
                                <option value="<?php echo $sip_profile_id ?>"
                                    <?php if (array_keys_value($host, '0.profile_id') == $sip_profile_id): ?> selected="selected" <?php endif; ?>><?php echo $sip_profile_name; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                    <?php if ($_SESSION['role_menu']['Routing']['gatewaygroups:view']['model_w']) { ?>
                        <td class="last baidiv">
                        <input type="hidden" name="accounts[resource_ip_id][]"
                               value="<?php echo array_keys_value($host, '0.resource_ip_id') ?>"/>
                        <a title="Capacity"
                           href="<?php echo $this->webroot ?>gatewaygroups/add_host_time/<?php echo array_keys_value($host, '0.resource_ip_id') ?>/<?php echo array_keys_value($host, '0.fqdn') ?>"><i
                                class="icon-signal"></i></a>
                        <a title="delete" rel="delete" class="delete_host"> <i class="icon-remove"></i> </a>
                        </td><?php } ?>
                </tr>
            <?php } ?>
        <?php } ?>
        <tr id="mb" style="">
            <td class="value baidiv nohei">
                <select class="ip-host-dd">
                    <option value="ip">IP Address</option>
                    <option value="host">Hostname</option>
                </select>
            </td>
            <td class="value baidiv nohei">
                <input class="nohei" type="text" onkeyup="value = value.replace(/[^\w\.\/]/ig, '')" style="width: 100%;"
                       class="host_ip" name="accounts[ip][]" id="ip">
                <span style="font-size: 16px;"> / </span>
                <select onkeyup="value = value.replace(/[^\w\.\/]/ig, '')" style="width: 50px;" name="accounts[mask][]"
                        class="host_ip_mask" id="mask" value="">
                    <option value="32">32</option>
                    <option value="31">31</option>
                    <option value="30">30</option>
                    <option value="29">29</option>
                    <option value="28">28</option>
                    <option value="27">27</option>
                    <option value="26">26</option>
                    <option value="25">25</option>
                    <option value="24">24</option>
                </select>
            </td>
            <td class="value baidiv nohei"><input class="nohei" type="text" name="accounts[port][]" class="host_port"
                                                  id="port" maxlength="16"></td>
            <?php if (isset($is_egress) and $is_egress): ?>
                <td class="value baidiv nohei"><input class="nohei onlyNumber validate[custom[integer],min[0],max[10]]" type="text"
                                                      name="accounts[options_ping_inv][]"
                                                      id="options_ping_inv" maxlength="16"></td>
            <?php endif; ?>
            <!--            <td class="value    " style="display:none;"><input type="checkbox" name="accounts[require_register][]" style="width:70px;"  maxlength="16"></td>-->
            <!--            --><?php //if (isset($is_egress) and $is_egress): ?>
            <td class="value heidiv1"><input type="text" class="reg_srv_ip" name="accounts[reg_srv_ip][]"
                                             style="width:100px;"></td>
            <td class="value heidiv1"><input type="text" class="reg_srv_port" name="accounts[reg_srv_port][]"
                                             style="width:80px;" maxlength="16"></td>
            <td class="value heidiv1"><input type="text" name="accounts[expires][]" class="expires" style="width:80px;"
                                             value="3600" maxlength="16"></td>
            <!--
            <td class="value baidiv"><input type="checkbox" name="accounts[option_active][]" style="width:100px;"  maxlength="16"></td>
            <td class="value baidiv"><input type="text" name="accounts[option_retry][]" style="width:100px;"></td>
            <td class="value baidiv"><input type="text" name="accounts[option_interval][]" style="width:80px;"  maxlength="16"></td>
            -->
            <!--            --><?php //endif; ?>
            <td class="value heidiv heidiv1"><input type="text" class="host_username" name="accounts[username][]"
                                                    style="width:100px;" maxlength="40"></td>
            <td class="value heidiv heidiv1"><input type="password" class="host_password" name="accounts[password][]"
                                                    style="width:100px;" maxlength="40"></td>


            <!--            <td class="heidiv"></td>-->
            <td class="heidiv heidiv1"></td>

            <td class="value heidiv1">
                <select name="accounts[profile_id][]" class="width120 profile_id">
                    <?php foreach ($switch_sip_profiles as $sip_profile_id => $sip_profile_name): ?>
                        <option value="<?php echo $sip_profile_id ?>">
                            <?php echo $sip_profile_name; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </td>
            <?php if ($_SESSION['role_menu']['Routing']['gatewaygroups:view']['model_w']) { ?>
                <td style="width: 55px; text-align:center;" class="last baidiv"><a title="delete" rel="delete"
                                                                                   onclick="$(this).closest('tr').remove();">
                        <i class="icon-remove"></i> </a></td>
            <?php } ?>
        </tr>
        </tbody>
    </table>
    <script type="text/javascript">

        function ValidURL(str) {
            var regex = /(http|https):\/\/(\w+:{0,1}\w*)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%!\-\/]))?/;
            if (!regex.test(str)) {
                //   alert("Please enter valid URL.");
                return false;
            } else {
                return true;
            }
        }

        function check_host() {
            //alert($("#host_authorize").val());
            $("#addHost").html("<i></i><?php __('Add Host'); ?>");
            $(".heidiv").hide();
            $(".heidiv1").hide();
            $('.nohei').show();

            $(".switch1").hide();
            $(".switch2").hide();
            $(".switch0").show();
            if ($("#host_authorize").val() == 0) {
                //$(".baidiv").show();
                //$(".heidiv").hide();

                $(".heidiv").hide();
                $(".heidiv1").hide();
                $('.nohei').show();

                $(".switch1").hide();
                $(".switch2").hide();
                $(".switch0").show();
            } else if ($("#host_authorize").val() == 1) {
                //$(".baidiv").hide();
                $('.nohei').hide();

                $(".heidiv1").hide();
                $(".heidiv").show();
                $(".switch0").hide();
                $(".switch1").show();
                $(".switch2").hide();
                $("#addHost").html("<i></i><?php __('Add SIP User'); ?>");
            } else if ($("#host_authorize").val() == 2) {
                $('.nohei').hide();
                $(".heidiv").hide();
                $(".heidiv1").show();

                $(".switch0").hide();
                $(".switch1").hide();
                $(".switch2").show();
                $("#addHost").html("<i></i>Add Gateway Info");
            }
        }

        jQuery(document).ready(function () {


            $('.ip-host-dd').live('change', function () {
                var $input = $(this).parent().find('input');
                var currentVal = $(this).val();
                $input.val('');
                if (currentVal == 'ip') {
                    $input.attr('name', 'accounts[ip][]').attr('id', 'ip');
                    $(this).parents('tr').find('input#port').attr('disabled', false);//.show();
                } else if (currentVal == 'host') {
                    $input.attr('name', 'accounts[host][]').attr('id', 'host');
                    $(this).parents('tr').find('input#port').attr('disabled', true);//.hide();
                }
            });

            check_host();


            jQuery('.delete_host').live('click', function () {
                var $this = $(this);
                var $val_el = $this.siblings('input:hidden');
                var ip_id = $val_el.val();
                $.ajax({
                    'type': 'POST',
                    'dataType': 'text',
                    'url': '<?php echo $this->webroot ?>prresource/gatewaygroups/delete_ip_id',
                    'data': {'ip_id': ip_id},
                    'success': function (data) {
                        $this.parent().parent().remove();
                        jGrowl_to_notyfy("<?php __('Succeeded'); ?>", {theme: 'jmsg-success'});
                    }
                });
            });


            var mb = jQuery('#mb').remove();
            jQuery('#addHost').click(function () {
                mb.clone(true).removeAttr('id').appendTo('#host_table tbody');
                document.dispatchEvent(new CustomEvent('updatePortValidation'))
                check_host();
                return false;
            });
            jQuery('input[id=port]').xkeyvalidate({type: 'Num'});
            jQuery('form[id^=Gatewaygroup],#myform').submit(function () {
                var re = true;
                if (jQuery('input[id=alias]:last').val() == '') {
                    jQuery('input[id=alias]:last').jGrowlError('The field Name must be required');
                    re = false;
                }

                if (parseInt(jQuery('#wait_ringtime180').val()) != 0) {
                    if (parseInt(jQuery('#wait_ringtime180').val()) < 1000 || parseInt(jQuery('#wait_ringtime180').val()) > 60000) {
                        jQuery(this).addClass('invalid');
                        jQuery(this).jGrowlError('PDD Timeout must a number less than 60000 and greater than 1000!');
                        re = false;
                    }
                }

                if ($("#host_authorize").val() == 0) {
                    jQuery('input[id=port]').each(function () {
                        if ($(this).is(':visible')) {
                            if (jQuery(this).val() != '' && isNaN(jQuery(this).val())) {
                                jQuery(this).jGrowlError('The field Port must be numberic only');
                                re = false;
                            }
                        }

                    });
                    jQuery('input[id=options_ping_inv]').each(function () {
                        if ($(this).is(':visible')) {
                            if (jQuery(this).val() != '' && isNaN(jQuery(this).val())) {
                                jQuery(this).jGrowlError('The field SIP Option must be numeric only');
                                re = false;
                            }
                        }

                    });
                    jQuery('input[id=host]').each(function () {
                        var val = $(this).val();
                        if (!ValidURL(val)) {
                            jQuery(this).jGrowlError('Invalid URL.');
                            re = false;
                        }
                    });
                    /**
                     jQuery('input[id=ip]').each(function() {
                        if($(this).is(':visible')) {
                            var arr = jQuery(this).val().split('.');

                            for (var i = 0; i < arr.length; i++) {
                                if (isNaN(arr[i]) || arr[i] > 255 || ((arr.length - 1) != 3)) {
                                    jQuery(this).jGrowlError('Invalid IP Address.');
                                    re = false;
                                    break;
                                }
                            }

                            /*
                             if(jQuery(this).val()!=''||!jQuery(this).val()){
                             if(!/^(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])$/.test(jQuery(this).val())){
                             jQuery(this).jGrowlError('IPs/FQDN must be a valid format ');
                             re = false;
                             }
                             }
                            if (jQuery(this).val().indexOf('.') == -1 || jQuery(this).val().indexOf('/') != -1) {
                                jQuery(this).jGrowlError('Invalid IP Address.');
                                re = false;
                            }
                        }
                    });

                     **/
                } else if ($("#host_authorize").val() == 2) {
                    $('.expires').each(function () {
                        var val = $(this).val();
                        if (val == '') val = 0;
                        val = parseInt(val);
                        if (val < 30 || val > 3600) {
                            jQuery(this).jGrowlError('Field Expires must be Greater than or equal to  30 , less than or equal to  3600.');
                            re = false;
                        }
                    });

                    jQuery('.reg_srv_port').each(function () {
                        if ($(this).is(':visible')) {
                            if (jQuery(this).val() != '' && isNaN(jQuery(this).val())) {
                                jQuery(this).jGrowlError('The field Port must be numberic only');
                                re = false;
                            }
                        }

                    });

                    jQuery('.reg_srv_ip').each(function () {
                        if ($(this).is(':visible')) {
                            var arr = jQuery(this).val().split('.');

                            for (var i = 0; i < arr.length; i++) {
                                if (isNaN(arr[i]) || arr[i] > 255 || ((arr.length - 1) != 3)) {
                                    jQuery(this).jGrowlError('Invalid IP Address.');
                                    re = false;
                                    break;
                                }
                            }

                            /*
                             if(jQuery(this).val()!=''||!jQuery(this).val()){
                             if(!/^(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])$/.test(jQuery(this).val())){
                             jQuery(this).jGrowlError('IPs/FQDN must be a valid format ');
                             re = false;
                             }
                             }
                             */
                            if (jQuery(this).val().indexOf('.') == -1 || jQuery(this).val().indexOf('/') != -1) {
                                jQuery(this).jGrowlError('Invalid IP Address.');
                                re = false;
                            }
                        }
                    });

                    jQuery('.host_username').each(function () {
                        if ($(this).is(':visible')) {
                            if (jQuery(this).val() == '') {
                                jQuery(this).jGrowlError('The field Username is required');
                                re = false;
                            }
                        }

                    });

                    jQuery('.host_password').each(function () {
                        if ($(this).is(':visible')) {
                            if (jQuery(this).val() == '') {
                                jQuery(this).jGrowlError('The field Password is required');
                                re = false;
                            }
                        }

                    });

                    jQuery('.profile_id').each(function () {
                        if ($(this).is(':visible')) {
                            if (jQuery(this).val() == '') {
                                jQuery(this).jGrowlError('The field SIP Profile is required');
                                re = false;
                            }
                        }

                    });

                } else {

                    jQuery('.host_username').each(function () {
                        if ($(this).is(':visible')) {
                            if (jQuery(this).val() == '') {
                                jQuery(this).jGrowlError('The field Username is required');
                                re = false;
                            }
                        }
                    });

                    jQuery('.host_password').each(function () {
                        if ($(this).is(':visible')) {
                            if (jQuery(this).val() == '') {
                                jQuery(this).jGrowlError('The field Password is required');
                                re = false;
                            }
                        }

                    });
                }

                var arr = new Array();
                jQuery('#host_table tr').each(function () {
                    for (var i in arr) {
                        if (arr[i].ip) {
                            if (jQuery(this).find('input.host_ip').val() == arr[i].ip && jQuery(this).find('input.host_port').val() == arr[i].port) {
                                jQuery.jGrowlError('IP Address ' + arr[i].ip + " must be unique!");
                                re = false;
                                return;
                            }
                        }
                    }
                    if (jQuery(this).find('input.host_ip').val() != '') {
                        arr.push({
                            ip: jQuery(this).find('input.host_ip').val(),
                            port: jQuery(this).find('input.host_port').val()
                        });
                    }
                });
//                if (re) {
//                    var arr = Array();
//                    jQuery('#host_table tr').each(function() {
//                        if (jQuery(this).find('#ip').size() > 0) {
//                            arr.push(jQuery(this).find('#ip').val() + '/' + jQuery(this).find('#GatewaygroupNeedRegister').val());
//                        }
//                    });
//                    arr = arr.join(',');
//                    var data = jQuery.ajaxData("<?php //echo $this->webroot ?>//ajaxvalidates/ip4r/noDomain?ip=" + arr);
//                    data = '[' + data + ']';
//                    data = eval(data);
//                    data = data[0];
//                    for (var i in data) {
//                        if (data[i] == false) {
//                            var eq = parseInt(i) + 1;
//                            jQuery('#host_table tr').eq(eq).find('#ip,#GatewaygroupNeedRegister').jGrowlError(jQuery('#host_table tr').eq(eq).find('#ip').val() + '/' + jQuery('#host_table tr').eq(eq).find('#GatewaygroupNeedRegister').val() + ' is not ip!');
//                            re = false;
//                        }
//                    }
//                }

                if (re) {
                    var reg_type = <?php $a = isset($host) ? array_keys_value($host, '0.reg_type') : null;echo $a === null ? 0 : strval($a) ?>;
                    var reg_type1 = $('#host_authorize').val();

                    if (reg_type != reg_type1) {
                        $('.switch' + reg_type).remove();
                    }
                }

                return re;
            });
        });
    </script>
    <div class="separator"></div>
</div>
