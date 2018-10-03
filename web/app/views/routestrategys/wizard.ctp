<ul class="breadcrumb">
    <li><?php __('You are here')?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Routing', true); ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Routing Wizard') ?></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Routing Wizard',true);?></h4>
    <div class="buttons pull-right">

    </div>
    <div class="clearfix"></div>
</div>

<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
    <a id="add" class="btn btn-primary btn-icon glyphicons circle_plus" href="<?php echo $this->webroot?>routestrategys/add_wizard"><i></i> Create New</a>
</div>
<div class="clearfix"></div>

<div class="innerLR">

    <div class="widget widget-heading-simple widget-body-white">
        <div class="widget-body">
            <div class="clearfix"></div>
            <?php if (!count($this->data)): ?>
                <div class="msg center">
                    <br />
                    <h2>
                        <?php echo __('no data found', true); ?>
                    </h2>
                </div>
            <?php else: ?>
                <div class="overflow_x">
                    <table class=" colVis list footable table table-striped tableTools table-bordered  table-white table-primary">
                        <thead>
                        <tr>
                            <th><?php echo $appCommon->show_order('client.name', __('Carrier Name', true)) ?></th>
                            <th><?php echo $appCommon->show_order('resource.alias', __('Trunk Name', true)) ?></th>

                            <th><?php  __('Hosts') ?></th>
                            <th><?php __('Vendors') ?></th>
                            <th><?php __('Prefix')?></th>
                            <th><?php __('Rate template')?></th>
                            <th><?php __('Rate Table')?></th>
                            <th><?php __('Rate Sent On')?></th>
                            <th><?php echo $appCommon->show_order('RoutingWizard.create_time', __('Update Time',true)) ?></th>
                            <th><?php __('Action')?></th>
                        </tr>
                        </thead>

                        <tbody>
                        <?php foreach($this->data as $item): ?>
                            <tr class="main_tr main_item_<?php echo $item[0]['resource_id']?>">
                                <td><?php echo $item['client']['name']; ?></td>
                                <td><?php echo $item['resource']['alias']; ?></td>

                                <td class="main_ips">
                                    <a href="#" onclick="return false;">
                                        <?php echo count($item[0]['resource_ips']); ?>
                                    </a>

                                </td>
                                <td class="main_vendors">
                                    <a href="#" onclick="return false;">
                                        <?php echo count($item[0]['vendors']); ?>
                                    </a>
                                </td>
                                <td><?php echo $item['resource_prefix']['tech_prefix']; ?></td>
                                <td><?php echo $item['rate_generation_template']['name']; ?></td>
                                <td><?php echo $item['rate_table']['name']; ?></td>
                                <td><?php echo $item[0]['send_rate_on']; ?></td>
                                <td><?php echo $item[0]['create_time']; ?></td>
                                <td>
                                    <a href="<?php echo $this->webroot . 'routestrategys/add_wizard/' .$item[0]['id'] ?>" title="Edit">
                                        <i class="icon-edit"></i>
                                    </a>
                                    <a href="<?php echo $this->webroot . 'routestrategys/del_wizard/' .$item[0]['id'] ?>" title="Delete">
                                        <i class="icon-remove"></i>
                                    </a>
                                    <a target="_blank" href="<?php echo $this->webroot . 'clientrates/view/' .base64_encode($item[0]['virtual_rate_table_id']) ?>" title="View Rates"> <i class="icon-align-justify"></i> </a>
                                    <a target="_blank" href="<?php echo $this->webroot . 'rates/send_rate/' .$item[0]['virtual_rate_table_id'] . '/' . $item[0]['id'] ?>" title="Send"> <i class="icon-envelope"></i> </a>
                                </td>
                            </tr>
                            <tr class="ips_tr ips_item_<?php echo $item[0]['resource_id']?>" style="display: none" data-value="<?php echo $item[0]['id']?>">
                                <td colspan="10">
                                    <table class="list footable table table-striped tableTools table-bordered  table-white table-primary ips_table">
                                        <col width="50%"/>
                                        <col width="50%"/>
                                        <thead>
                                        <tr>
                                            <th>Ip</th>
                                            <th>Port</th>
                                            <th>
                                                <a title="add" style="color: white" href="javascript:void(0)" class="add_ip_port">
                                                    <i class="icon-plus"></i>
                                                </a>
                                            </th>
                                        </tr>

                                        </thead>
                                        <tbody class="ips_tbody">
                                        <tr class="clone" style="display: none">
                                            <td>
                                                <input style="margin-bottom: 0px;height: 16px" type="text" name="ip" class="ip validate[required,custom[ipv4]]"/>
                                            </td>
                                            <td>
                                                <input style="margin-bottom: 0px;height: 16px" type="text" name="port" class="port validate[required,custom[onlyNumber]]"
                                                       maxlength="5"/>
                                            </td>
                                            <td>
                                                <a title="save" href="javascript:void(0)" class="save_ip_port" data-resource_id="<?php echo $item[0]['resource_id']?>">
                                                    <i class="icon-save"></i>
                                                </a>
                                                <a title="delete" href="javascript:void(0)" class="delete_ip_port">
                                                    <i class="icon-remove"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <?php foreach($item[0]['resource_ips'] as $rv): ?>
                                            <tr class="ips_del_<?php echo $rv[0]['resource_ip_id']?>">
                                                <td><?php echo $rv[0]['ip']?></td>
                                                <td><?php echo $rv[0]['port']?></td>
                                                <td>
                                                    <a title="delete" href="javascript:void(0)" class="delete_ip_port" data-resource_id="<?php echo $item[0]['resource_id']?>" data-resource_ip_id="<?php echo $rv[0]['resource_ip_id']?>">
                                                        <i class="icon-remove"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </td>


                            </tr>
                            <tr class="venders_tr" style="display: none">
                                <td colspan="10">
                                    <table class="list footable table table-striped tableTools table-bordered  table-white table-primary">
                                        <col width="50%"/>
                                        <col width="50%"/>
                                        <thead>
                                        <tr>
                                            <th>Client name</th>
                                            <th>Resource name</th>
                                        </tr>

                                        </thead>
                                        <tbody>
                                        <?php foreach($item[0]['vendors'] as $rv): ?>
                                            <tr>
                                                <td><?php echo $rv[0]['client_name']?></td>
                                                <td><?php echo $rv[0]['vendor_name']?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </td>

                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="row-fluid">
                    <div class="pagination pagination-large pagination-right margin-none">
                        <?php echo $this->element('xpage'); ?>
                    </div>
                </div>
            <?php endif ?>
            <div class="clearfix"></div>


            <fieldset class="query-box" style=" clear:both;overflow:hidden;margin-top:10px;">
                <h4 class="heading glyphicons search"><i></i> <?php __('Search')?></h4>
                <div style="margin:0px auto; text-align:center;">
                    <form method="get" name="myform">
                        <?php __('Time')?>:
                        <input type="text" value="<?php echo $start_time ?>" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',lang:'en'})" style="width:120px;" name="start_time" class="input in-text in-input">
                        ~
                        <input type="text" value="<?php echo $end_time; ?>" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',lang:'en'})" style="width:120px;" name="end_time" class="input in-text in-input">
                        <input type="submit" value="<?php __('Submit')?>" class="input in-submit btn btn-primary margin-bottom10">
                    </form>
                </div>
            </fieldset>

        </div>
    </div>
</div>

<script>
    <?php if($check_url): ?>
    var el = document.createElement("a");
    document.body.appendChild(el);
    url = '<?php echo $check_url?>';
    el.href = url; //url 是你得到的连接
    el.target = '_blank'; //指定在新窗口打开
    el.click();
    document.body.removeChild(el);
    <?php endif; ?>

    $(function(){
        $('.main_ips > a').click(function(){
            $(this).parents('.main_tr').next().toggle();
            $(this).parents('.main_tr').next().next().hide();
        });
        $('.main_vendors > a').click(function(){
            $(this).parents('.main_tr').next().next().toggle();
            $(this).parents('.main_tr').next().hide();
        });

        $('.main_tr > td:not(.main_ips,.main_vendors)').click(function(){
            $(this).parents('.main_tr').next().hide();
            $(this).parents('.main_tr').next().next().hide();
        });

        $('.add_ip_port').click(function(){
            var tbody = $(this).parents('.ips_table').find('.ips_tbody');
            if(tbody.find('.clone').length >= 2){
                showMessages_new("[{'field':'','code':'101','msg':'Please save and then add'}]");
                return false;
            }
            tbody.find('.clone:first').clone(true).appendTo(tbody);
            tbody.find('.clone:last').show();
        });
        $('.delete_ip_port').live('click', function() {
            var resource_ip_id = $(this).data('resource_ip_id');
            var resource_id = $(this).data('resource_id');
            if(resource_ip_id){
                $.post(
                    '<?php echo $this->webroot?>routestrategys/del_ip',{'resource_ip_id':resource_ip_id},
                    function(data){
                        if(data == 'true'){
                            showMessages_new("[{'field':'','code':'201','msg':'Delete Success'}]");


                            var main_item_str = ".main_item_" + resource_id;
                            var ips_item_str = ".ips_item_" + resource_id;
                            var ips_del_str = ".ips_del_" + resource_ip_id;

                            var main_ips_a = $(main_item_str).find('.main_ips a');
                            var main_ips_av = main_ips_a.html();
                            main_ips_a.html(parseInt(main_ips_av)-1);

                            $(ips_del_str).remove();


                        } else {
                            showMessages_new("[{'field':'','code':'101','msg':'Delete Fail'}]");
                        }

                    },
                    'JSON'
                );
            }


            $(this).parent().parent().remove();
        });

        $('.save_ip_port').live('click', function(){
            var $this = $(this);
            var tr = $(this).parent().parent();
            var ip = tr.find('.ip');
            var port = tr.find('.port');
            var ipv = ip.val();
            var portv = port.val();
            var resource_id = $(this).data('resource_id');

            var ips_tr = tr.parents('.ips_tr');
            //var ips_trv = ips_tr.data('value');

            var main_item_str = ".main_item_" + resource_id;
            var ips_item_str = ".ips_item_" + resource_id;

            var main_ips_a = $(main_item_str).find('.main_ips a');
            var main_ips_av = main_ips_a.html();
            var ips_tbody = $(ips_item_str).find('.ips_tbody');





            var vip = tr.find('.ip').validationEngine('validate');
            var vport = tr.find('.port').validationEngine('validate');
            if(vip || vport){
                return false;
            }

            $.post(
                '<?php echo $this->webroot?>routestrategys/save_ip',{'ip':ipv,'port':portv,'resource_id':resource_id},
                function(data){
                    if(data == 'true'){
                        showMessages_new("[{'field':'','code':'201','msg':'Add success'}]");
                        ip.replaceWith(ipv);
                        port.replaceWith(portv);
                        tr.removeClass('clone');

                        $this.remove();





                        main_ips_a.html(parseInt(main_ips_av)+1);
                        ips_tbody.each(function(){
                            console.log($(this));
                            var tt = tr.clone(true);
                            $(this).append(tt);
                            console.log($(this),tr);
                        });
                        tr.remove();

                    } else {
                        showMessages_new("[{'field':'','code':'101','msg':'"+data+"'}]");
                    }

                },
                'JSON'
            )
        });

    })

</script>
<style>
    .table-primary .table-primary {
        border-color: #efefef;
        border-top: none !important;
    }
    .table-primary .table-primary thead th {
        border-color: #ccc !important;
        background-color: #7FAF00 !important;
        color: #ffffff !important;
        font-size: 14px !important;
    }
    .table-primary .table-primary tbody td {
        color: #7c7c7c !important;
        background: #fff !important;
    }
    .table-primary .table-primary tbody td.important {
        color: #7faf00 !important;
        font-weight: 600 !important;
    }
    .table-primary .table-primary tbody td.actions {
        padding-right: 1px !important;
    }

    #add_ip_port:hover{
        color: #7c7c7c !important;
    }

    /*.table-primary .table-primary.table-bordered tbody td {*/
    /*border-color: #7c7c7c;*/
    /*border-top-color: #7c7c7c;*/
    /*border-right-color: #7c7c7c;*/
    /*border-bottom-color: #7c7c7c;*/
    /*border-left-color: #7c7c7c;*/
    /*border-width: 1px;*/
    /*}*/
</style>
