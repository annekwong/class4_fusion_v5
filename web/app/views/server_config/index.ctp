<style>
    #add_sub {
        width: 12.5px;
        height: 12px;
        padding: 0;
        display: inline-block;
        border-radius: 6px;
        cursor: default;
    }
    #add_sub.btn-primary.glyphicons i:before {
        opacity: 0.8;
        text-shadow: none;
        width: 12px;
        height: 12px;
        padding: 0;
        font-size: 12px;
        margin: 0;
    }
    .row-active {
        background: #fff !important;
    }

    table tbody tr:active {
        background: #fff !important;
    }
    #add_sub .fa-plus{

    }
    .hidden{display:none !important}
</style>

<ul class="breadcrumb">
    <li><?php echo __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>server_config">
            <?php __('Switch') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>server_config">
            <?php echo __('VoIP Gateway') ?></a></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php __('VoIP Gateway')?></h4>

</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
    <a class="btn btn-primary btn-icon glyphicons restart" href="<?php echo $this->webroot ?>switch_profiler/reload"><i></i> <?php __('Reload') ?></a>
    <?php if (isset($_SESSION['role_menu']['Switch']['server_config']) && $_SESSION['role_menu']['Switch']['server_config']['model_w'])
    { ?>
        <a class="btn btn-primary btn-icon glyphicons circle_plus link_btn" id="add" href="###"><i></i> <?php __('Create New') ?></a>
    <?php } ?>
</div>
<div class="clearfix"></div>

<div class="innerLR">

    <div class="widget widget-heading-simple widget-body-white">
        <div class="widget-body overflow_x">
            <?php
            if (empty($this->data)):
                ?>
                <h2 class="msg center"><?php  echo __('no_data_found') ?></h2>
                <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary" style="display:none;">
                    <thead>
                    <tr>
                        <th><?php __('Name')?></th>
<!--                        <th>--><?php //__('UUID')?><!--</th>-->
                        <th><?php __('CLI IP')?></th>
                        <th><?php __('CLI Port')?></th>
                        <!--                            <th>--><?php //__('Active Call IP')?><!--</th>-->
                        <!--                            <th>--><?php //__('Active Call Port')?><!--</th>-->
                        <!--th><?php __('PAID Replace IP')?></th-->
                        <!--                            <th><?php __('Sip Capture IP')?></th>
                            <th><?php __('Sip Capture Port')?></th>
                            <th><?php __('Sip Capture Path')?></th>-->
                        <th><?php __('Status')?></th>
                        <th><?php __('Action')?></th>
                    </tr>
                    </thead>

                    <tbody>

                    </tbody>
                </table>
            <?php else: ?>
                <div class="clearfix"></div>
                <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
                    <thead>
                    <tr>
                        <th><?php __('Name')?></th>
<!--                        <th>--><?php //__('UUID')?><!--</th>-->
                        <th><?php __('CLI IP')?></th>
                        <th><?php __('CLI Port')?></th>
                        <!--                            <th>--><?php //__('Active Call IP')?><!--</th>-->
                        <!--                            <th>--><?php //__('Active Call Port')?><!--</th>-->
                        <!--th><?php __('PAID Replace IP')?></th-->
                        <!--                            <th><?php __('Sip Capture IP')?></th>
                            <th><?php __('Sip Capture Port')?></th>
                            <th><?php __('Sip Capture Path')?></th>-->
                        <th><?php __('Status')?></th>
                        <th><?php __('Action')?></th>
                    </tr>
                    </thead>

                    <tbody>
                    <?php foreach ($this->data as $i=>$item): ?>
                        <tr class="row-<?php echo $i % 2 + 1; ?>" data-id="<?php echo $item['ServerConfig']['id'] ?>">
                            <td><?php echo $item['ServerConfig']['name']; ?></td>
<!--                            <td>--><?php //echo $item['ServerConfig']['uuid']; ?><!--</td>-->
                            <td><?php echo $item['ServerConfig']['lan_ip']; ?></td>
                            <td><?php echo $item['ServerConfig']['lan_port']; ?></td>
                            <!--                                <td>--><?php //echo $item['ServerConfig']['active_call_ip']; ?><!--</td>-->
                            <!--                                <td>--><?php //echo $item['ServerConfig']['active_call_port']; ?><!--</td>-->
                            <!--td><?php echo $item['ServerConfig']['paid_replace_ip'] ? "Yes" : 'No'; ?></td-->
                            <!--                                <td><?php echo $item['ServerConfig']['sip_capture_ip']; ?></td>
                                <td><?php echo $item['ServerConfig']['sip_capture_port']; ?></td>
                                <td><?php echo $item['ServerConfig']['sip_capture_path']; ?></td>-->
                            <td>
                                <?php if($item['ServerConfig']['active']): ?>
                                    <?php __('Connected'); ?>
                                <?php else: ?>
                                    <?php __('Error'); ?>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a class="expand" title="<?php __('SIP Profile')?>" href="javascript:void(0);" data-href="<?php echo $this->webroot ?>switch_profiler/index/<?php echo $item['ServerConfig']['id'] ?>">
                                    <i class="icon-bullhorn"></i>
                                </a>
                                <a title="<?php __('Edit')?>" class="edit_item" href="###" control="<?php echo $item['ServerConfig']['id'] ?>" >
                                    <i class="icon-edit"></i>
                                </a>
                                <a title="<?php __('Add Switch Host')?>" id="add_sub" class="add_sub btn btn-primary btn-icon glyphicons circle_plus" href="javascript:void(0);">
                                    <i class="fa fa-plus" aria-hidden="true"></i>
                                </a>
                                <a title="Delete" onclick="return myconfirm('<?php __('sure to delete') ?>', this)"
                                   class="delete" href="<?php echo $this->webroot ?>server_config/delete/<?php echo base64_encode($item['ServerConfig']['id']) ?>" >
                                    <i class="icon-remove"></i>
                                </a>

                            </td>
                        </tr>
                        <tr style="height:auto">
                            <td colspan="5">
                                <div class="jsp_resourceNew_style_2" style="padding:5px;display: none;">
                                </div>
                            </td>
                            <td class="hidden"></td>
                            <td class="hidden"></td>
                            <td class="hidden"></td>
                            <td class="hidden"></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
                <div class="row-fluid separator">
                    <div class="pagination pagination-large pagination-right margin-none">
                        <?php echo $this->element('xpage'); ?>
                    </div>
                </div>
                <div class="clearfix"></div>
            <?php endif; ?>
        </div>
    </div>
</div>
<script>

    function editSubItem(el) {

        let serverId = $(el).parent().parent().parent().parent().parent().parent().parent().prev().data('id');

        jQuery(el).parent().parent().trAdd({
            action: '<?php echo $this->webroot ?>switch_profiler/action_edit_panel/' + serverId + '/' + jQuery(el).attr('control'),
            ajax: '<?php echo $this->webroot ?>switch_profiler/action_edit_panel/' + serverId + '/' + jQuery(el).attr('control'),
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
    }

    function checkint(input, name){
        var re = /^[0-9]+$/; //判断字符串是否为数字 //判断正整数 /^[1-9]+[0-9]*]*$/
        if (!re.test(input))
        {
            jGrowl_to_notyfy(name + " must be an integer.", {theme: 'jmsg-error'});
            return false;
        }
        return true;
    }

    function checknull(input, name){
        //var re = /^[0-9]+$/; //判断字符串是否为数字 //判断正整数 /^[1-9]+[0-9]*]*$/
        if (!input)
        {
            jGrowl_to_notyfy(name + " is not be null.", {theme: 'jmsg-error'});
            return false;
        }
        return true;
    }

    function checkip(input, name){

        var re = /^((([01]?[0-9]{1,2})|(2[0-4][0-9])|(25[0-5]))[.]){3}(([0-1]?[0-9]{1,2})|(2[0-4][0-9])|(25[0-5]))$/; //判断ip
        if (!re.test(input))
        {
            jGrowl_to_notyfy(name + " should be IP address.", {theme: 'jmsg-error'});
            return false;
        }
        return true;
    }


    jQuery(function() {
        jQuery('#add').click(function() {
            $('.msg').hide();
            $('table.list').show();
            jQuery('table.list tbody').trAdd({
                ajax: "<?php echo $this->webroot ?>server_config/action_edit_panel",
                action: "<?php echo $this->webroot ?>server_config/action_edit_panel",
                onsubmit: function(options) {
                    var name = $("#name").val();
                    var lan_ip = $("#lan_ip").val();
                    var lan_port = $("#lan_port").val();
                    if(!name){
                        jGrowl_to_notyfy("Name is not be null.", {theme: 'jmsg-error'});
                        return false;
                    }
                    if (!checkint(lan_port, 'CLI Port')){
                        return false;
                    }

                    if (!checkip(lan_ip, 'CLI IP')){
                        return false;
                    }
                    return true;
                },
                removeCallback: function() {
                    if (jQuery('table.list tr').size() == 1) {
                        jQuery('table.list').hide();
                    }
                },
                insertNumber: 'first'
            });
            jQuery(this).parent().parent().show();
        });

        jQuery('a.edit_item').click(function() {

            jQuery(this).parent().parent().trAdd({
                action: '<?php echo $this->webroot ?>server_config/action_edit_panel/' + jQuery(this).attr('control'),
                ajax: '<?php echo $this->webroot ?>server_config/action_edit_panel/' + jQuery(this).attr('control'),
                saveType: 'edit',
                onsubmit: function(options) {
                    var lan_ip = $("#lan_ip").val();
                    var lan_port = $("#lan_port").val();
                    var sip_capture_ip = $("#sip_capture_ip").val();
                    var sip_capture_port = $("#sip_capture_port").val();

//                        var active_call_ip = $("#active_call_ip").val();
//                        var active_call_port = $("#active_call_port").val();

                    if (!checkint(lan_port, 'CLI Port'))
                    {
                        return false;
                    }

                    if (!checkip(lan_ip, 'CLI IP'))
                    {
                        return false;
                    }

//                        if (!checkint(active_call_port, 'Active Call Port'))
//                        {
//                            return false;
//                        }
//                     //alert(active_call_ip);
//                        if (!checkip(active_call_ip, 'Active Call IP'))
//                        {
//                            return false;
//                        }

                    if (sip_capture_ip)
                    {
                        if (!checkip(sip_capture_ip, 'Sip Capture IP'))
                        {
                            return false;
                        }
                    }

                    if (sip_capture_port)
                    {
                        if (!checkint(sip_capture_port, 'Sip Capture Port'))
                        {
                            return false;
                        }
                    }
                    return true;
                }
            });
        });

        $('a.expand').click(function () {
            let href = $(this).data('href');
            let $this = $(this);
            let trDetail = $this.parent().parent().next();
            let isExpanded = trDetail.find('.jsp_resourceNew_style_2').hasClass('expanded');
            $(this).closest('tr').removeClass('create_details').addClass('show_details');

            $('.jsp_resourceNew_style_2.expanded').removeClass('expanded').slideUp('slow', function () {
                $(this).html('');
            });

            if(isExpanded == true) {
                $(this).closest('tr').removeClass('show_details');
                return true;
            }
            $.post(href, {
                ajax: true
            }, function (response) {

                trDetail.find('.jsp_resourceNew_style_2').addClass('expanded').html(response).slideDown('slow');
            });
        });

        jQuery('.add_sub').click(function () {
            let href = $(this).closest('tr').find('.expand').data('href');
            let $this = $(this);
            let trDetail = $this.closest('tr').next();
            let isExpanded = trDetail.find('.jsp_resourceNew_style_2').hasClass('expanded');
            $(this).closest('tr').removeClass('show_details').addClass('create_details');


            $('.jsp_resourceNew_style_2.expanded').removeClass('expanded').slideUp('slow', function () {
                $(this).html('');
            });
            if(isExpanded == true) {
                $(this).closest('tr').removeClass('create_details');
                return true;
            }
            $.post(href, {
                ajax: true
            }, function (response) {
                trDetail.find('.jsp_resourceNew_style_2').addClass('expanded').html(response).slideDown('slow');
                // add row
                let serverId = $this.closest('tr').data('id');

                $('.msg').hide();
                $('table.list').show();
                jQuery('table.sub-table tbody').trAdd({
                    ajax: "<?php echo $this->webroot ?>switch_profiler/action_edit_panel/" + serverId,
                    action: "<?php echo $this->webroot ?>switch_profiler/action_edit_panel/" + serverId + "/0/true",
                    line: 2,
                    insertNumber : 'first',
                    onsubmit: function (options) {
                        var profile_name = $("#profile_name").val();
                        var sip_ip = $("#sip_ip").val();
                        var sip_port = $("#sip_port").val();
                        //  var proxy_ip = $("#proxy_ip").val();
                        //     var proxy_port = $("#proxy_port").val();
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
                $this.parent().parent().show();

            });



        });

    });

    $(document).on('DOMNodeInserted', function(){
        $('#save').attr('title', 'Save');
    });

    $(document).ready(function () {
        $('table>tbody>tr').click(function () {
            $(this).css('background', '#fff');
            $(this).removeClass('row-active');
        });
    });

</script>
