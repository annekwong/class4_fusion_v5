<div class="overflow_x">
    <?php if ($_SESSION['role_menu']['Routing']['gatewaygroups:view']['model_w'])
    { ?><div style="margin-top:20px;margin-bottom: 20px;"><a id="addHost" class="btn btn-primary btn-icon glyphicons circle_plus" onclick="return false;" href="#"><i></i><?php __('Add Host')?> </a></div>
<?php } ?>
    <table class="footable table table-striped tableTools table-bordered  table-white table-primary default footable-loaded table-condensed"  id="host_table">
        <thead>
            <tr>
                <th class="baidiv" ><span rel="helptip" class="helptip" id="ht-100002" title="Name of an account in JeraSoft yht system (for statistics and reports)"><?php echo __('ip', true); ?></span><!-- <span class="tooltip" id="ht-100002-tooltip"</span>--></th>

                <th class="baidiv"><span rel="helptip" class="helptip" id="ht-100003" title="Gateway IP-adress. You can specify multiple adresses by dividing them with "><?php echo __('Netmask', true); ?></span><!-- <span class="tooltip" id="ht-100003-tooltip">--></span></th>

                <th class="baidiv" ><span rel="helptip" class="helptip" id="ht-100004" title="Technical prefix, that is used to identify users, when multiple clients use same gateway"><?php echo __('port', true); ?></span><!-- <span class="tooltip" id="ht-100004-tooltip"></span>--></th>

                <th  style="display:none;"><span rel="helptip" class="helptip" id="ht-100004" title="Require Register"><?php echo __('Require Register', true); ?></span></th>
<?php if ($_SESSION['role_menu']['Routing']['gatewaygroups:view']['model_w'])
{ ?>
    <?php if (isset($is_egress) and $is_egress): ?>
                        <th class="heidiv"><span rel="helptip" class="helptip" id="ht-100004" title="Require Authenication"><?php echo __('Require Authenication', true); ?></span></th>
                        <th class="heidiv" ><span rel="helptip" class="helptip" id="ht-100004" title="Register Server IP"><?php echo __('Register Server IP', true); ?></span></th>
                        <th class="heidiv"><span rel="helptip" class="helptip" id="ht-100004" title="Register Server Port"><?php echo __('Register Server Port', true); ?></span></th>
                        <th class="heidiv"><span rel="helptip" class="helptip" id="ht-100004" title="Expires"><?php echo __('Expires', true); ?></span></th>
                        <th class="baidiv"><span rel="helptip" class="helptip" id="ht-100004" title="Authentication Option Active"><?php echo __('Option Active', true); ?></span></th>
                        <th class="baidiv"><span rel="helptip" class="helptip" id="ht-100004" title="Authentication Option Retry"><?php echo __('Option Retry', true); ?></span></th>
                        <th class="baidiv"><span rel="helptip" class="helptip" id="ht-100004" title="Authentication Option Interval"><?php echo __('Option Interval', true); ?></span></th>
    <?php endif; ?>
                    <th class="heidiv"><span rel="helptip" class="helptip" id="ht-100004" title="Authentication Username"><?php echo __('Username', true); ?></span></th>
                    <th class="heidiv"><span rel="helptip" class="helptip" id="ht-100004" title="Authentication Password"><?php echo __('Password', true); ?></span></th>


                    <th class="heidiv">
                        <?php __('Registered')?>
                    </th>
                    <th class="heidiv">
                        <?php __('Expires Time')?>
                    </th>
                    <th class="last baidiv"><?php echo __('action', true); ?></th>
            <?php } ?>
            </tr>
        </thead>
        <tbody class="rows" id="rows-ip">
            <?php
            if (isset($ips))
            {
                for ($i = 0; $i < count($ips['ip']); $i++)
                {
                    ?>
                    <tr>
                        <td class="value">
                            <input class="baidiv" type="text" style="width: 100px;" onkeyup="value = value.replace(/[^\w\.\/]/ig, '')" name="accounts[ip][]" id="ip" value="<?php echo $ips['ip'][$i]; ?>">
                            <span class="heidiv"><?php echo $ips['ip'][$i]; ?></span>
                        </td>
                        <?php if ($$hel->isIngress(@$type))
                        { ?>
                            <td>
            <?php echo $xform->input('need_register.', Array('class' => 'baidiv', 'value' => $ips['need_register'][$i], 'name' => 'accounts[need_register][]', 'options' => array('32' => 32, '31' => 31, '30' => 30, '29' => 29, '28' => 28, '27' => 27, '26' => 26, '25' => 25, '24' => 24), 'style' => 'width:100px')) ?>
                                <span class="heidiv"><?php $ips['need_register'][$i]; ?></span>
                            </td>
                    <?php } ?>
                        <td class="value"><input type="text" name="accounts[port][]" id="port" maxlength="16" value="<?php echo $ips['need_register'][$i]; ?>"></td>

                        <td style="width: 55px;" class="value last"><a href="#" title="delete" rel="delete" onclick="$(this).closest('tr').remove();"> <i class="icon-remove"></i> </a></td>
                    </tr>
                <?php } ?>
            <?php } ?>


            <?php if (isset($hosts) && is_array($hosts))
            { ?>
                <?php foreach ($hosts as $key => $host)
                { ?>
                    <?php
                    if (array_keys_value($host, '0.ip'))
                    {
                        $ip = array_keys_value($host, '0.ip');
                    }
                    else
                    {
                        $ip = array_keys_value($host, '0.fqdn');
                    }
                    // var_dump($host);
                    ?>
                        <?php $ip = split("/", $ip) ?>
                    <tr>
                        <td class="value baidiv">
                            <input class="nohei" type="text" onkeyup="value = value.replace(/[^\w\.\/]/ig, '')" style="width: 100px;" name="accounts[ip][]" class="host_ip" id="ip" value="<?php echo array_keys_value($ip, 0) ?>">
                            <span class="heidiv"><?php echo array_keys_value($ip, 0) ?></span>
                        </td>
                        <?php if ($$hel->isIngress(@$type))
                        { ?>
                            <td class="baidiv">
            <?php echo $xform->input('need_register.', Array('class' => 'nohei', 'value' => array_keys_value($ip, 1), 'name' => 'accounts[need_register][]', 'options' => array('32' => 32, '31' => 31, '30' => 30, '29' => 29, '28' => 28, '27' => 27, '26' => 26, '25' => 25, '24' => 24), 'style' => 'width:100px')) ?>
                                <span class="heidiv"><?php echo array_keys_value($ip, 1) ?></span>
                            </td>
                            <?php } ?>
                        <td class="value baidiv">
                            <input type="text" name="accounts[port][]"  class="host_port nohei"  id="port" maxlength="16" value="<?php echo array_keys_value($host, '0.port') ?>">
                            <span class="heidiv"><?php echo array_keys_value($host, '0.port') ?></span>
                        </td>
                        <td class="value" style="display:none;"><input type="checkbox" name="accounts[require_register][]" style="width:70px;" <?php if (array_keys_value($host, '0.need_register') == 1) echo 'checked="checked"' ?>  maxlength="16"></td>
        <?php if (isset($is_egress) and $is_egress): ?>
                            <td class="value heidiv"><input type="checkbox" name="accounts[require_auth][]" style="width:100px;" <?php if (array_keys_value($host, '0.request_auth') == 1) echo 'checked="checked"' ?> value="<?php echo $key; ?>"  maxlength="16"></td>
                            <td class="value heidiv"><input type="text" name="accounts[register_server_ip][]" style="width:100px;" value="<?php echo array_keys_value($host, '0.register_server_ip') ?>"></td>
                            <td class="value heidiv"><input type="text" name="accounts[register_server_port][]" style="width:80px;"  maxlength="16"  value="<?php echo array_keys_value($host, '0.register_server_port') ?>"></td>
                            <td class="value heidiv"><input type="text" name="accounts[expires][]" style="width:80px;"  maxlength="16"  value="<?php echo array_keys_value($host, '0.expires') ?>"></td>
                            <td class="value baidiv">
            <?php
            //var_dump($host[0]['option_active']);
            ?>
                                <input type="checkbox" name="accounts[option_active][]" style="width:100px;" <?php if (array_keys_value($host, '0.option_active') == 1) echo 'checked="checked"  '; ?>  value="<?php echo $key; ?>" maxlength="16">
                            </td>
                            <td class="value baidiv"><input type="text" name="accounts[option_retry][]" style="width:100px;" value="<?php echo array_keys_value($host, '0.option_retry') ?>"></td>
                            <td class="value baidiv"><input type="text" name="accounts[option_interval][]" style="width:80px;"  maxlength="16"  value="<?php echo array_keys_value($host, '0.option_interval') ?>"></td>
                            <?php endif; ?>
                        <td class="value heidiv"><input type="text" name="accounts[username][]" style="width:100px;"  maxlength="16" value="<?php echo array_keys_value($host, '0.username') ?>"></td>
                        <td class="value heidiv"><input type="password" name="accounts[password][]" style="width:100px;"  maxlength="16" value="<?php echo array_keys_value($host, '0.password') ?>"></td>



                        <td class="heidiv">
        <?php
        if (array_keys_value($host, '0.registered') == 1):
            ?>
                                <a><i class="icon-check"></i></a>
                    <?php else: ?>
                                <a><i class="icon-check-empty"></i></a>
                        <?php endif; ?>
                        </td>
                        <?php if(array_keys_value($host, '0.expires_time')): ?>
                        <td class="value heidiv"><?php echo date('Y-m-d H:i:s',array_keys_value($host, '0.expires_time')); ?></td>
                        <?php else: ?>
                        <td class="value heidiv"></td>
                        <?php endif; ?>
                        <?php if ($_SESSION['role_menu']['Routing']['gatewaygroups:view']['model_w'])
                        { ?>
                            <td class="last baidiv">
                                <input type="hidden" name ="accounts[resource_ip_id][]" value="<?php echo array_keys_value($host, '0.resource_ip_id') ?>" />
                                <a title="Capacity" href="<?php echo $this->webroot ?>gatewaygroups/add_host_time/<?php echo array_keys_value($host, '0.resource_ip_id') ?>/<?php echo array_keys_value($host, '0.fqdn') ?>"><i class="icon-signal"></i></a>
                                <a href="###" title="delete" rel="delete" class="delete_host"> <i class="icon-remove"></i> </a></td><?php } ?>
                    </tr>
    <?php } ?>
                <?php } ?>
            <tr id="mb" style="">
                <td class="value baidiv"><input class="nohei add_ip" type="text" onkeyup="value = value.replace(/[^\w\.\/]/ig, '')" style="width: 100px;" class="host_ip" name="accounts[ip][]" id="ip"></td>

                <td class="baidiv"><?php echo $xform->input('need_register.', Array('class' => 'nohei add_netmark', 'name' => 'accounts[need_register][]', 'options' => array('32' => 32, '31' => 31, '30' => 30, '29' => 29, '28' => 28, '27' => 27, '26' => 26, '25' => 25, '24' => 24), 'style' => 'width:100px')) ?></td>

                <td class="value baidiv"><input class="nohei add_port" type="text" name="accounts[port][]" id="port" maxlength="16"></td>
                <td class="value    " style="display:none;"><input type="checkbox" name="accounts[require_register][]" style="width:70px;"  maxlength="16"></td>
                <?php if (isset($is_egress) and $is_egress): ?>
                    <td class="value heidiv"><input type="checkbox" name="accounts[require_auth][]" style="width:100px;"  maxlength="16"></td>
                    <td class="value heidiv"><input type="text" name="accounts[register_server_ip][]" style="width:100px;"></td>
                    <td class="value heidiv"><input type="text" name="accounts[register_server_port][]" style="width:80px;"  maxlength="16"></td>
                    <td class="value heidiv"><input type="text" name="accounts[expires][]" style="width:80px;" value="3600"  maxlength="16"></td>
                    <td class="value baidiv"><input type="checkbox" name="accounts[option_active][]" style="width:100px;"  maxlength="16"></td>
                    <td class="value baidiv"><input type="text" name="accounts[option_retry][]" style="width:100px;"></td>
                    <td class="value baidiv"><input type="text" name="accounts[option_interval][]" style="width:80px;"  maxlength="16"></td>
<?php endif; ?>
                <td class="value heidiv"><input type="text" name="accounts[username][]" style="width:100px;"  maxlength="16"></td>
                <td class="value heidiv"><input type="password" name="accounts[password][]" style="width:100px;"  maxlength="16"></td>

                
                <td class="heidiv"></td>
                <td class="heidiv"></td>
<?php if ($_SESSION['role_menu']['Routing']['gatewaygroups:view']['model_w'])
{ ?> 
                    <td style="width: 55px; text-align:center;" class="last baidiv"><a href="#" title="delete" rel="delete" onclick="$(this).closest('tr').remove();"> <i class="icon-remove"></i> </a></td>
<?php } ?>
            </tr>
        </tbody>
    </table>
    <script type="text/javascript">

        function  check_host() {
            //alert($("#host_authorize").val());
            if ($("#host_authorize").val() == 1) {
                //$(".baidiv").show();
                //$(".heidiv").hide();
                $('.nohei').show();
                $(".heidiv").hide();
            } else if ($("#host_authorize").val() == 2) {
                //$(".baidiv").hide();
                $('.nohei').hide();
                $(".heidiv").show();
            }
        }

        jQuery(document).ready(function() {



            check_host();



            jQuery('.delete_host').live('click', function() {
                var $this = $(this);
                var $val_el = $this.siblings('input:hidden');
                var ip_id = $val_el.val();
                $.ajax({
                    'type': 'POST',
                    'dataType': 'text',
                    'url': '<?php echo $this->webroot ?>prresource/gatewaygroups/delete_ip_id',
                    'data': {'ip_id': ip_id},
                    'success': function(data) {
                        $this.parent().parent().remove();
                        jGrowl_to_notyfy("<?php __('Succeeded'); ?>", {theme: 'jmsg-success'});
                    }
                });
            });


            var mb = jQuery('#mb').remove();
            jQuery('#addHost').click(function() {
                mb.clone(true).appendTo('#host_table tbody');
                check_host();
                return false;
            });

            //从数据库添加ip信息
            var fun;
            <?php
                if(!empty($registration_ip)):
                $i=1;
                    foreach($registration_ip as $val): ?>
                        function func<?php echo $i?>(){
                            $('#addHost').click();
                            $('.add_ip:last').val('<?php echo $val[0]["ip"]?>');
                            $('.add_port:last').val('<?php echo $val[0]["port"]?>');
                            $('.add_netmark:last').val('<?php echo $val[0]["netmark"]?>');
                        }
            fun = func<?php echo $i++?>;
            setTimeout(fun,50);



            <?php endforeach;endif;?>

            jQuery('input[id=port]').xkeyvalidate({type: 'Num'});
            jQuery('form[id^=Gatewaygroup]').submit(function() {
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

                if ($("#host_authorize").val() == 1) {
                    jQuery('input[id=port]').each(function() {
                        if (jQuery(this).val() != '' && isNaN(jQuery(this).val())) {
                            jQuery(this).jGrowlError('The field Port must be numberic only');
                            re = false;
                        }
                    });
                    jQuery('input[id=ip]').each(function() {

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
                    });
                }
                var arr = new Array();
                jQuery('#host_table tr').each(function() {
                    for (var i in arr) {
                        if (arr[i].ip)
                        {
                            if (jQuery(this).find('input.host_ip').val() == arr[i].ip && jQuery(this).find('input.host_port').val() == arr[i].port) {
                                jQuery.jGrowlError('IP Address ' + arr[i].ip + " must be unique!");
                                re = false;
                                return;
                            }
                        }
                    }
                    if (jQuery(this).find('input.host_ip').val() != '') {
                        arr.push({ip: jQuery(this).find('input.host_ip').val(), port: jQuery(this).find('input.host_port').val()});
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
                return re;
            });
        });
    </script>
    <div class="separator"></div>
</div>
