<style>
    ul#notyfy_container_top {
        z-index: 1000000;
    }
</style>
<ul class="breadcrumb">
    <li><?php __('You are here'); ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?= $this->webroot ?>product_management/index">
            <?php __('Management') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?= $this->webroot ?>product_management/index">
            <?php __('Product') ?></a></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php __('Product') ?></h4>
    <div class="buttons pull-right">
    </div>
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
    <a class="btn btn-primary btn-icon glyphicons circle_plus" id="add"
       href="<?php echo $this->webroot ?>product_management/add_edit">
        <i></i> <?php __('Create New') ?></a>
</div>
<div class="clearfix"></div>

<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">

        <div class="widget-body">
            <?php
            $count = count($this->data);
            if (!$count):
                ?>
                <h2 class="msg center"><br/><?php __('No data found'); ?></h2>
                <table
                    class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary default footable-loaded hide">
                    <thead>
                    <tr>
                        <th><?php __('Product Name'); ?></th>
                        <th><?php __('Routing Plan'); ?></th>
                        <th><?php __('Rate Table'); ?></th>
                        <th><?php __('Description'); ?></th>
                        <th><?php __('Tech Prefix'); ?></th>
                        <th><?php __('Update On'); ?></th>
                        <th><?php __('Update By'); ?></th>
                        <th><?php __('Action'); ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            <?php else: ?>
                <table
                    class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary default footable-loaded">
                    <thead>
                    <tr>
                        <th><?php echo $appCommon->show_order('ProductRouteRateTable.product_name', __('Product Name', true)) ?></th>
                        <th><?php __('Routing Plan'); ?></th>
                        <th><?php __('Rate Table'); ?></th>
                        <th><?php __('Description'); ?></th>
                        <th><?php __('Tech Prefix'); ?></th>
                        <th><?php echo $appCommon->show_order('ProductRouteRateTable.update_on', __('Update On', true)) ?></th>
                        <th><?php __('Update By'); ?></th>
                        <th><?php __('Limits'); ?></th>
                        <th><?php __('Action'); ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($this->data as $items) {
                        ?>
                        <tr>
                            <td><?php echo $items['ProductRouteRateTable']['product_name'] ?></td>
                            <td><?php echo $items['RouteStrategy']['name'] ?></td>
                            <td><?php echo $items['RateTable']['name'] ?></td>
                            <td><?php echo $items['ProductRouteRateTable']['description'] ?></td>
                            <td><?php echo $items['ProductRouteRateTable']['tech_prefix'] ?></td>
                            <td><?php echo $items['ProductRouteRateTable']['update_on'] ?></td>
                            <td><?php echo $items['ProductRouteRateTable']['update_by'] ?></td>
                            <td><?php echo $items['ProductRouteRateTable']['is_private'] ? 'private' : 'public' ?></td>
                            <td>
                                <a href="<?php echo $this->webroot; ?>product_management/send_rate?product=<?php echo base64_encode($items['ProductRouteRateTable']['id']); ?>"
                                   title="<?php __('send'); ?>">
                                    <i class="icon-envelope"></i>
                                </a>
                                <?php if (isset($items['ProductRouteRateTable']['rate_table_id']) && !empty($items['ProductRouteRateTable']['rate_table_id'])): ?>
                                    <a href="<?php echo $this->webroot; ?>clientrates/view/<?php echo base64_encode($items['ProductRouteRateTable']['rate_table_id']); ?>"
                                       title="<?php __('Edit Rate'); ?>">
                                        <i class="icon-edit"></i>
                                    </a>
                                    <a href="<?php echo $this->webroot; ?>clientrates/import/<?php echo base64_encode($items['ProductRouteRateTable']['rate_table_id']); ?>"
                                       title="<?php __('Upload Rate'); ?>">
                                        <i class="icon-upload"></i>
                                    </a>
                                <?php endif; ?>
                                <?php if (isset($items['ProductRouteRateTable']['route_strategy_id']) && !empty($items['ProductRouteRateTable']['route_strategy_id'])): ?>
                                    <a href="<?php echo $this->webroot; ?>routestrategys/routes_list/<?php echo base64_encode($items['ProductRouteRateTable']['route_strategy_id']); ?>"
                                       title="<?php __('Modify Routing Plan'); ?>">
                                        <i class="icon-wrench"></i>
                                    </a>
                                <?php endif; ?>
                                <a class="edit_item" product_id="<?php echo $items['ProductRouteRateTable']['id'] ?>"
                                   href="<?php echo $this->webroot . 'product_management/add_edit/' . $items['ProductRouteRateTable']['id'] ?>"
                                   title="<?php __('edit'); ?>">
                                    <i class="icon-edit"></i>
                                </a>
                                <a href="#myModalAssignProduct"
                                   product_name="<?php echo $items['ProductRouteRateTable']['product_name']; ?>"
                                   product_id="<?php echo $items['ProductRouteRateTable']['id'] ?>" data-toggle="modal"
                                   title="<?php __('Assign Product'); ?>">
                                    <i class="icon-plus"></i>
                                </a>
                                <a onclick="return myconfirm('<?php __('sure to delete'); ?>',this)"
                                   href="<?php echo $this->webroot; ?>product_management/delete_product/<?php echo base64_encode($items['ProductRouteRateTable']['id']); ?>"
                                   title="<?php __('Delete'); ?>">
                                    <i class="icon-remove"></i>
                                </a>
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
                <div class="separator">
                    <div class="pagination pagination-large pagination-right margin-none">
                        <?php echo $this->element('xpage'); ?>
                    </div>
                </div>

            <?php endif; ?>
        </div>


        <div id="myModalAssignProduct" class="modal hide" style="position:fixed;top:50px;">
            <div class="modal-header">
                <button data-dismiss="modal" class="close" type="button">&times;</button>
                <h3><?php __('Assign Product'); ?></h3>
            </div>
            <div class="widget-body">
                <form id="myform">
                    <table class="table tableTools table-bordered  table-white">
                        <tr>
                            <td style="text-align:right;">
                                <?php __('Carrier'); ?>:
                            </td>
                            <td>
                                <input type="hidden" name="product_id" class="product_id" value=""/>
                                <select name="client_id" id="client_id" class="select2">
                                    <option value=""></option>
                                    <?php foreach ($carriers as $id => $carrier): ?>
                                        <option value="<?php echo $id; ?>">
                                            <?php echo $carrier; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align:right;">
                                <?php __('Trunk Name'); ?>:
                            </td>
                            <td>
                                <input type="text" name="trunk_name" class="trunk_name width220" value=""/>
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align:right;">
                                <?php __('Copy IP From'); ?>:
                            </td>
                            <td>
<!--                                <a href="javascript:void(0)" title="Copy" class="copy_from"-->
<!--                                   style="float: right; margin-right: 170px;">-->
<!--                                    <i class="icon-move"></i>-->
<!--                                </a>-->
                                <select name="copy_ip" id="copy_ip" class="select2">
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align:right;">
                                <?php __('IP'); ?>:
                            </td>
                            <td class="ip_td">
                                <a href="javascript:void(0)" class="new_ip"
                                   style="float: right; margin-right: 115px;"><i class="icon-plus"></i></a>
                                <div>
                                    <input type="text" name="ip[]" class="ip width220" value=""/>
                                    <input type="text" name="port[]" class="port" style="width:40px;" value=""/>
                                </div>
                            </td>
                        </tr>
                    </table>
                </form>
            </div>
            <div class="modal-footer">
                <input type="button" class="btn btn-primary sub" value="<?php __('Submit'); ?>">
                <a href="javascript:void(0)" data-dismiss="modal" class="btn btn-default"><?php __('Close'); ?></a>
            </div>

        </div>
        <div class="hidden remove_icon">
            <a href="javascript:void(0)" class="remove_line" style="float: right; margin-right: 115px;">
                <i class="icon-remove"></i>
            </a>
        </div>


        <div class="clearfix"></div>
    </div>
</div>
<script type="text/javascript">
    $(function () {
        $('a[href="#myModalAssignProduct"]').on('click', function () {
            let product_id = $(this).attr('product_id');
            let product_name = $(this).attr('product_name');
            empty_modal();
            $('.select2').select2();
            $('#myModalAssignProduct').find('.product_id').val(product_id);
            $('#myModalAssignProduct').find('h3').text('Assign Product' + '[ ' + product_name + ' ]');
            $('.new_ip').off().on('click', function () {
                let new_ip = $(this).closest('td').find('div').first().clone();
                $(new_ip).prepend($('.remove_icon').html()).end().find('input').first().val('').end().find('input').last().val('');
                $(this).closest('td').append($(new_ip));
                $('.remove_line').on('click', function () {
                    $(this).closest('div').remove();
                })
            });
        });

        $('#copy_ip').on('change', function () {
            $('input.ip').val($(this).val())
        });

        $('#client_id').on('change', function () {
            let client_id = $('#client_id').val();
            if (client_id) {
                $.ajax({
                    url: "<?php echo $this->webroot ?>clients/getClientIPs/" + client_id,
                    dataType: 'json',
                    success: function (response) {
                        if (response.status) {
                            let ip_list = "";
                            $.each(response.data, function (ip, resource) {
                                ip_list += '<option value="' + ip + '">' + resource + '</option>'
                            })
                            $('#copy_ip').html(ip_list).select2().trigger('change');
                        }
                    }
                });
            } else {
                $('#copy_ip option').remove().select2();
            }
        });

        $('.copy_from').on('click', function () {
            let copied_ip = $('#copy_ip').val();

            if (copied_ip) {
                let copied_ip_clone = $('.ip_td').find('div').first().clone();
                copied_ip_clone.prepend($('.remove_icon').html()).end().find('input').first().val(copied_ip);
                $('.ip_td').append(copied_ip_clone);
                copied_ip_clone.find('input').last().val('');
                $('.remove_line').on('click', function () {
                    $(this).closest('div').remove();
                })
            }
        });

        $("#myModalAssignProduct").find('.sub').click(function () {
            let $this = $(this);
            let validate = validate_form();
            if (validate) {
                $.ajax({
                    url: "<?php echo $this->webroot ?>product_management/assign",
                    type: 'post',
                    dataType: 'json',
                    data: $('#myform').serialize(),
                    success: function (response) {
                        if (response.status) {
                            jGrowl_to_notyfy(response.msg, {theme: 'jmsg-success'});
                        } else {
                            jGrowl_to_notyfy(response.msg, {theme: 'jmsg-error'});
                        }
                        $("#myModalAssignProduct").modal('hide');
                    }
                });
            }
        });

        function validate_form() {
            if (!$('#client_id').val().trim()) {
                jGrowl_to_notyfy('<?php __('Carrier cannot be empty!'); ?>', {theme: 'jmsg-error'});
                return false;
            }
            if (!$('.trunk_name').val().trim()) {
                jGrowl_to_notyfy('<?php __('Trunk name cannot be empty!!'); ?>', {theme: 'jmsg-error'});
                return false;
            }

            $('.ip_td').find('div .ip').each(function (i, v) {
                /*if($(v).val().trim() && !$(v).next().val().trim()){
                 $(v).next().focus();
                 jGrowl_to_notyfy('<?php __('IP Port cannot be empty!!'); ?>', {theme: 'jmsg-error'});
                 return false;
                 }*/
                if ($(v).val().trim() && !$(v).val().trim().toString().match(/^(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/)) {
                    jQuery(this).jGrowlError('Wrong IP Format!', {theme: 'jmsg-error'});
                    return false;
                }
            })

            return true;
        }

        function empty_modal() {
            $('#client_id').val('');
            $('.trunk_name').val('');
            $('.ip').val('');
            $('#copy_ip option').remove().select2();
            $('.ip_td').find('div').not(':first').remove();
        }

        /*$('#add').click(function() {
         $('.msg').hide();
         $('table.list').show();
         $('table.list tbody').trAdd({
         ajax: "<?php echo $this->webroot ?>product_management/product_edit_panel",
         action: "<?php echo $this->webroot ?>product_management/save_product",
         removeCallback: function() {
         if ($('table.list tr').size() == 1) {
         $('.msg').show();
         $('table.list').hide();
         }
         },
         onsubmit: function() {
         var flg_TechPrefix = $("#ProductRouteRateTableTechPrefix").validationEngine('validate');
         var flg_product_name = $("#ProductRouteRateTableProductName").validationEngine('validate');
         if(flg_product_name || flg_TechPrefix){
         return false;
         }else{
         var product_name = $("#ProductRouteRateTableProductName").val();
         var prefix = $("#ProductRouteRateTableTechPrefix").val();
         var flg_exist_name = true;
         $.ajax({
         type: "POST",
         async: false,
         url: "<?php echo $this->webroot; ?>product_management/ajax_check_product_name",
         data: "product_name=" + product_name + "&product_id=" + '' + "&prefix=" + prefix,
         success: function(msg) {
         if (msg != 0)
         {
         jGrowl_to_notyfy(msg, {theme: 'jmsg-error'});
         flg_exist_name = false;
         }
         }
         });
         return flg_exist_name;
         }
         }
         });
         $(this).parent().parent().show();
         });

         $('a.edit_item').click(function() {
         var product_id = $(this).attr('product_id');
         $(this).parent().parent().trAdd({
         ajax: "<?php echo $this->webroot ?>product_management/product_edit_panel/"+product_id,
         action: "<?php echo $this->webroot ?>product_management/save_product/"+ product_id,
         saveType: 'edit',
         onsubmit: function() {
         var flg_TechPrefix = $("#ProductRouteRateTableTechPrefix").validationEngine('validate');
         var flg_product_name = $("#ProductRouteRateTableProductName").validationEngine('validate');
         if(flg_product_name || flg_TechPrefix){
         return false;
         }else{
         var product_name = $("#ProductRouteRateTableProductName").val();
         var prefix = $("#ProductRouteRateTableTechPrefix").val();
         var flg_exist_name = true;
         $.ajax({
         type: "POST",
         async: false,
         url: "<?php echo $this->webroot; ?>product_management/ajax_check_product_name",
         data: "product_name=" + product_name + "&product_id=" + product_id + "&prefix=" + prefix,
         success: function(msg) {
         if (msg!= 0)
         {
         jGrowl_to_notyfy(msg, {theme: 'jmsg-error'});
         flg_exist_name = false;
         }
         }
         });
         return flg_exist_name;
         }
         }
         });
         });*/
    });
</script>
