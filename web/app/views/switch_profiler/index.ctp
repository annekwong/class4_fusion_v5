<style type="text/css">
    .list tbody tr span {margin:0 10px;}
    #sip_capture_ip{width: 160px;}
    #sip_capture_port{width: 160px;}
</style>
<ul class="breadcrumb">
    <li><?php echo __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Switch') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Sip Profile') ?>: [<?php echo $gateway_name; ?>]</li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php __('Sip Profile') ?></h4>

</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
    <a class="btn btn-primary btn-icon glyphicons restart" href="<?php echo $this->webroot ?>switch_profiler/reload"><i></i> <?php __('Reload') ?></a>
    <a id="add" class="btn btn-primary btn-icon glyphicons circle_plus" href="###"><i></i> <?php __('Create New') ?></a>
</div>
<div class="clearfix"></div>

<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <?php
        if($is_config){
            echo $this->element("currs/step", array('now' => '7'));
        }
        ?>
        <div class="widget-body">
            <?php
            if (empty($this->data)):
                ?>
                <h2 class="msg center"><?php echo __('no_data_found') ?></h2>
                <table class="list footable table table-striped tableTools table-bordered  table-white table-primary" style="display:none;">

                    <thead>
                    <tr>
                        <th><?php __('Profile Name') ?></th>
<!--                        <th>--><?php //__('Profile Status') ?><!--</th>-->
                        <th><?php __('SIP IP') ?></th>
                        <th><?php __('SIP Port') ?></th>
                        <!--th><?php __('Proxy IP') ?></th-->
                        <!--th><?php __('Proxy Port') ?></th-->
                        <th><?php __('Status') ?></th>
                        <th><?php __('CPS') ?></th>
                        <th><?php __('CAP') ?></th>
                        <th><?php __('Action') ?></th>
                    </tr>
                    </thead>

                    <tbody>

                    </tbody>
                </table>
            <?php else: ?>
                <div class="clearfix"></div>
                <table class="list footable table table-striped tableTools table-bordered  table-white table-primary">
                    <thead>
                    <tr>
                        <th><?php __('Profile Name') ?></th>
<!--                        <th>--><?php //__('Profile Status') ?><!--</th>-->
                        <th><?php __('SIP IP') ?></th>
                        <th><?php __('SIP Port') ?></th>
                        <!--th><?php __('Proxy IP') ?></th-->
                        <!--th><?php __('Proxy Port') ?></th-->
                        <th><?php __('Status') ?></th>
                        <th><?php __('CPS') ?></th>
                        <th><?php __('CAP') ?></th>
                        <th><?php __('Action') ?></th>
                    </tr>
                    </thead>

                    <tbody>
                    <?php foreach ($this->data as $item): ?>
                        <tr>
                            <td><?php echo $item['SwitchProfile']['profile_name']; ?></td>
<!--                            <td>--><?php //echo $status[$item['SwitchProfile']['profile_status']]; ?><!--</td>-->
                            <td><?php echo $item['SwitchProfile']['sip_ip']; ?></td>
                            <td><?php echo $item['SwitchProfile']['sip_port']; ?></td>

                            <!--td><?php echo $item['SwitchProfile']['proxy_ip']; ?></td-->
                            <!--td><?php echo $item['SwitchProfile']['proxy_port']; ?></td-->
                            <td><?php echo $item['SwitchProfile']['status']; ?></td>
                            <td><?php echo $item['SwitchProfile']['cps']; ?></td>
                            <td><?php echo $item['SwitchProfile']['cap']; ?></td>
                            <td>
                                <?php if ($item['SwitchProfile']['default_register']): ?>
                                    <i class="icon-check"></i>
                                <?php else: ?>
                                    <a title="<?php __('Start') ?>" href="<?php echo $this->webroot; ?>switch_profiler/set_default_register/<?php echo $server_id . '/' . $item['SwitchProfile']['id'] ?>">
                                        <i class="icon-unchecked"></i>
                                    </a>
                                <?php endif; ?>

                                <a title="<?php __('Edit') ?>" class="edit_item" href="###" control="<?php echo $item['SwitchProfile']['id'] ?>" >
                                    <i class="icon-edit"></i>
                                </a>

                                <a title="<?php __('Delete') ?>" class="delete" href="###" url='<?php echo $this->webroot; ?>switch_profiler/delete/<?php echo $server_id . '/' . $item['SwitchProfile']['id'] ?>'>
                                    <i class="icon-remove"></i>
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="15">
                                <span><?php __('Require Authenication') ?>:<?php echo $item['SwitchProfile']['auth_register'] ? 'Yes' : 'No'; ?></span>
                                <span><?php __('RPID') ?>:<?php echo $item['SwitchProfile']['support_rpid'] ? 'Yes' : 'No'; ?></span>
                                <span><?php __('OLI') ?>:<?php echo $item['SwitchProfile']['support_oli'] ? 'Yes' : 'No'; ?></span>
                                <span><?php __('PRIV') ?>:<?php echo $item['SwitchProfile']['support_priv'] ? 'Yes' : 'No'; ?></span>
                                <span><?php __('DIV') ?>:<?php echo $item['SwitchProfile']['support_div'] ? 'Yes' : 'No'; ?></span>
                                <span><?php __('PAID') ?>:<?php echo $item['SwitchProfile']['support_paid'] ? 'Yes' : 'No'; ?></span>
                                <span><?php __('PCI') ?>:<?php echo $item['SwitchProfile']['support_pci'] ? 'Yes' : 'No'; ?></span>
                                <span><?php __('X LRN') ?>:<?php echo $item['SwitchProfile']['support_x_lrn'] ? 'Yes' : 'No'; ?></span>
                                <span><?php __('X Header') ?>:<?php echo $item['SwitchProfile']['support_x_header'] ? 'Yes' : 'No'; ?></span>

                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
                <div class="row-fluid">
                    <div class="pagination pagination-large pagination-right margin-none">
                        <?php echo $this->element('xpage'); ?>
                    </div>
                </div>
                <div class="clearfix"></div>
                <?php if ($is_config): ?>
<!--                    <div class="center widget-body" id="next_step">-->
<!--                        <a href="--><?php //echo $this->webroot ?><!--homes/init_login_url/--><?php //echo $_SESSION['sst_user_id']; ?><!--/1"  class=" btn primary next">--><?php //__('Next') ?><!--</a>-->
<!--                    </div>-->
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(function () {
        <?php if ($is_config): ?>
        $("div.navbar").hide();
        $.gritter.add({
            title: '<?php printf(__('Setup VOIP Gateway[%s]:Sip Profile', true), $gateway_name); ?>',
            text: '<?php __('Your system up and running, you need to set the Sip Profile.'); ?>',
            sticky: true
        });
        <?php endif; ?>
    });
    function checkint(input, name)
    {
        var re = /^[0-9]+$/; //判断字符串是否为数字 //判断正整数 /^[1-9]+[0-9]*]*$/
        if (!re.test(input))
        {
            jGrowl_to_notyfy(name + " must be an integer.", {theme: 'jmsg-error'});
            return false;
        }
        return true;
    }
    function checknull(input, name)
    {
        //var re = /^[0-9]+$/; //判断字符串是否为数字 //判断正整数 /^[1-9]+[0-9]*]*$/
        if (!input)
        {
            jGrowl_to_notyfy(name + " is not be null.", {theme: 'jmsg-error'});
            return false;
        }
        return true;
    }

    function checkip(input, name)
    {
    console.log(name);
        var re = /^((([01]?[0-9]{1,2})|(2[0-4][0-9])|(25[0-5]))[.]){3}(([0-1]?[0-9]{1,2})|(2[0-4][0-9])|(25[0-5]))$/; //判断ip
        if (!re.test(input))
        {
            jGrowl_to_notyfy(name + " should be IP address!", {theme: 'jmsg-error'});
            return false;
        }
        return true;
    }



    jQuery(function () {

        jQuery('.delete').click(function () {
            var url = $(this).attr('url');
            bootbox.confirm('Are you sure to delete the item?', function (result) {
                if (result) {
                    window.location.href = url;
                }
            });
        });

        jQuery('#add').click(function () {
            $('.msg').hide();
            $('table.list').show();
            jQuery('table.list tbody').trAdd({
                ajax: "<?php echo $this->webroot ?>switch_profiler/action_edit_panel/<?php echo $server_id; ?>",
                action: "<?php echo $this->webroot ?>switch_profiler/action_edit_panel/<?php echo $server_id; ?>/0/<?php echo $is_config; ?>",
                line: 2,
                insertNumber : 'first',
                onsubmit: function (options) {
                    var profile_name = $("#profile_name").val();
                    var sip_ip = $("#sip_ip").val();
                    var sip_port = $("#sip_port").val();
                    //var proxy_ip = $("#proxy_ip").val();
                    //var proxy_port = $("#proxy_port").val();
                    var cps = $("#cps").val();
                    var cap = $("#cap").val();
                    // var report_ip = $("#report_ip").val();
                    if (!checknull(profile_name, 'Profile Name'))
                    {
                        return false;
                    }
                    if (!checkint(sip_port, 'Sip Port'))
                    {
                        return false;
                    }
                    if (!checkip(sip_ip, 'Sip IP'))
                    {
                        return false;
                    }
                    /* if (!checkip(report_ip, 'Report Ip'))
                     {
                     return false;
                     }*/
                    /*if (proxy_ip)
                    {
                        if (!checkip(proxy_ip, 'Proxy Ip'))
                        {
                            return false;
                        }
                    }

                    if (proxy_port)
                    {
                        if (!checkint(proxy_port, 'Proxy Port'))
                        {
                            return false;
                        }
                    }*/

                    if (cps)
                    {
                        if (!checkint(cps, 'CPS'))
                        {
                            return false;
                        }
                    }
                    if (cap)
                    {
                        if (!checkint(cap, 'CAP'))
                        {
                            return false;
                        }
                    }
                    return true;
                },
                removeCallback: function () {
                    if (jQuery('table.list tr').size() == 1) {
                        jQuery('table.list').hide();
                    }

                }
            });
            jQuery(this).parent().parent().show();
        });

        jQuery('a.edit_item').click(function () {
            jQuery(this).parent().parent().trAdd({
                action: '<?php echo $this->webroot ?>switch_profiler/action_edit_panel/<?php echo $server_id; ?>/' + jQuery(this).attr('control'),
                ajax: '<?php echo $this->webroot ?>switch_profiler/action_edit_panel/<?php echo $server_id; ?>/' + jQuery(this).attr('control'),
                line: 2,
                saveType: 'edit',
                onsubmit: function (options) {
                    var profile_name = $("#profile_name").val();
                    var sip_ip = $("#sip_ip").val();
                    var sip_port = $("#sip_port").val();
                    //var proxy_ip = $("#proxy_ip").val();
                    //var proxy_port = $("#proxy_port").val();
                    var cps = $("#cps").val();
                    var cap = $("#cap").val();
                    if (!checknull(profile_name, 'Profile Name'))
                    {
                        return false;
                    }
                    if (!checkint(sip_port, 'Sip Port'))
                    {
                        return false;
                    }

                    if (!checkip(sip_ip, 'Sip IP'))
                    {
                        return false;
                    }

                    /*if (proxy_ip)
                    {
                        if (!checkip(proxy_ip, 'Proxy Ip'))
                        {
                            return false;
                        }
                    }


                    if (proxy_port)
                    {
                        if (!checkint(proxy_port, 'Proxy Port'))
                        {
                            return false;
                        }
                    }*/

                    if (cps)
                    {
                        if (!checkint(cps, 'CPS'))
                        {
                            return false;
                        }
                    }
                    if (cap)
                    {
                        if (!checkint(cap, 'CAP'))
                        {
                            return false;
                        }
                    }

                    return true;
                }
            });
        });
    });

    $(document).on('DOMNodeInserted', function(){
        $('#save').attr('title', 'Save');
    });
</script>
