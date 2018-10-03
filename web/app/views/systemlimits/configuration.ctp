<style>
    input[type="text"]{margin-bottom: 0;}
</style>
<ul class="breadcrumb">
    <li><?php __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>systemlimits/configuration">
        <?php __('Switch') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>systemlimits/configuration">
        <?php echo __('Capacity') ?></a></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php __('Switch Capacity') ?></h4>

    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>
<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-body">
            <table class="footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
                <thead>
                    <tr>
                        <th><?php __('Switch Name') ?></th>
                        <th><?php __('CLI IP') ?></th>
                        <th><?php __('CLI Port') ?></th>
                        <th><?php __('License Cap Limit') ?></th>
                        <th><?php __('Self-Defined Cap Limit') ?></th>
                        <th><?php __('License CPS Limit') ?></th>
                        <th><?php __('Self-Defined CPS Limit') ?></th>
                        <th><?php __('Expiration Date') ?></th>
                        <th><?php __('Status') ?></th>
                        <th><?php __('Action') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if($is_back_run): foreach ($sip_infos as $sip_info): ?>
                        <tr>
                            <td><?php echo $sip_info['switch_name']; ?></td>
                            <td><?php echo $sip_info['sip_ip']; ?></td>
                            <td><?php echo $sip_info['sip_port']; ?></td>
                            <td><?php echo isset($sip_info['lic_cap_limit']) ? $sip_info['lic_cap_limit'] : ""; ?></td>
                            <td>
                                <input type="text" value="<?php echo isset($sip_info['sys_cap_limit']) ? $sip_info['sys_cap_limit'] : ""; ?>">
                            </td>
                            <td><?php echo isset($sip_info['lic_cps_limit']) ? $sip_info['lic_cps_limit'] : ""; ?></td>
                            <td><input type="text" value="<?php echo isset($sip_info['sys_cps_limit']) ? $sip_info['sys_cps_limit'] : ""; ?>"></td>
                            <td><?php echo $sip_info['expire'] ? date('Y-m-d H:i:sO',$sip_info['expire']) : ""; ?></td>
                            <td><?php $sip_info['expire'] ? __('Connected') :  __('Error'); ?></td>
                            <td>
                                <a title="<?php __('Save')?>" class="save" href="###">
                                    <i class="icon-save"></i>
                                </a>
<!--                                <a title="--><?php //__('Initialize')?><!--" class="reload" href="###">-->
<!--                                    <i class="icon-refresh"></i>-->
<!--                                </a>-->
                                <input type="hidden" value="<?php echo isset($sip_info['sys_cap_limit']) ? $sip_info['sys_cap_limit'] : ""; ?>">
                                <input type="hidden" value="<?php echo isset($sip_info['sys_cps_limit']) ? $sip_info['sys_cps_limit'] : ""; ?>">
                            </td>
                        </tr>
                    <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    jQuery(document).ready(function($) {
        $('a.save').click(function() {
            var $this = $(this);
            var $tr = $this.parents("tr");
            var switch_name = $("td:eq(0)", $tr).text();
            var sip_ip = $("td:eq(1)", $tr).text();
            var sip_port = $("td:eq(2)", $tr).text();
            var cap_limit = $("td:eq(4)", $tr).find('input').val();
            var cps_limit = $("td:eq(6)", $tr).find('input').val();
            var old_cap_limit = $("td:eq(7)", $tr).find('input:eq(0)').val();
            var old_cps_limit = $("td:eq(7)", $tr).find('input:eq(1)').val();
            $.ajax({
                url: "<?php echo $this->webroot ?>systemlimits/ajax_update.json",
                data: {ingressC: cap_limit, ingressP: cps_limit,
                    sip_ip: sip_ip, sip_port: sip_port, old_cps_limit: old_cps_limit,
                    old_cap_limit: old_cap_limit, switch_name: switch_name},
                type: 'POST',
                async: true,
                success: function(text) {

                    if (text == 1) {
                        showMessages("[{'field':'','code':'201','msg': 'The system limit of  [" + switch_name + "]  is modified successfully!'}]");
                        $("td:eq(7)", $tr).find('input:eq(0)').val(cap_limit);
                        $("td:eq(7)", $tr).find('input:eq(1)').val(cps_limit);
                    } else {
                        showMessages("[{'field':'#ingrLimit','code':'101','msg':'" + text + "'}]");
                    }
                }
            });
        });

//        $('a.reload').click(function() {
//            var $this = $(this);
//            var $tr = $this.parents("tr");
//            var switch_name = $("td:eq(0)", $tr).text();
//            var sip_ip = $("td:eq(1)", $tr).text();
//            var sip_port = $("td:eq(2)", $tr).text();
//            var max_call_limit = $("td:eq(3)", $tr).text();
//            var max_cps_limit = $("td:eq(5)", $tr).text();
//            var old_call_limit = $("td:eq(7)", $tr).find('input:eq(0)').val();
//            var old_cps_limit = $("td:eq(7)", $tr).find('input:eq(1)').val();
//            $.ajax({
//                url: "<?php //echo $this->webroot ?>//systemlimits/reload",
//                data: {sip_ip: sip_ip, sip_port: sip_port,max_call_limit:max_call_limit,max_cps_limit:max_cps_limit,old_call_limit:old_call_limit,old_cps_limit:old_cps_limit,switch_name:switch_name},
//                type: 'POST',
//                async: true,
//                success: function(text) {
//
//                    if (text == 1) {
//                        showMessages("[{'field':'','code':'201','msg': 'The system limit of  [" + switch_name + "]  is reloaded successfully!'}]");
//                        $("td:eq(4)", $tr).find('input').val(max_call_limit);
//                        $("td:eq(6)", $tr).find('input').val(max_cps_limit);
//                    }
//                },
//                error: function(XmlHttpRequest) {
//                    showMessages("[{'field':'#ingrLimit','code':'101','msg':'" + XmlHttpRequest.responseText + "'}]");
//                }
//            });
//        });
    });
</script>