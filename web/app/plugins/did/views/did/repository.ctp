<?php $data = $p->getDataArray(); ?>
<style>
    table input {
        width:100px;
    }
    .parentFormundefined{
        z-index: 9999;
    }
    #container select{width: 100px;}

    table.table-black {
        border: 1px solid #ccc;
    }

    table.table-black thead tr th {
        color: #000;
        background: rgb(127, 175, 0) !important;
    }
    input.no-input-style{
        border: none;
        cursor: default;
    }
    .require_auth input {
        margin: 0 10px 0 3px;
    }
    .hidden{
        display:none !important;
    }

</style>
<ul class="breadcrumb">
    <li><?php __('You are here'); ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>did/repository"><?php __('Origination') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <?php if (isset($_GET['ingress_id'])): ?>
        <li>Vendor [<?php echo $ingresses[$_GET['ingress_id']] ?>]</li>
        <li class="divider"><i class="icon-caret-right"></i></li>
    <?php endif ?>
    <li><a href="<?php echo $this->webroot ?>did/repository">
        <?php echo __('DID Repository', true); ?></a></li>
     <?php if(isset($_GET['orig_client_name']) && $_GET['orig_client_name']): ?>
          <li class="divider"><i class="icon-caret-right"></i></li>
          <li> <a href="<?php echo $this->webroot ?>did/repository">
          <?php echo  $_GET['orig_client_name']; ?>
          </a>
          </li>
     <?php endif; ?>
</ul>

<div class="heading-buttons">
    <?php if(isset($_GET['orig_client_name']) && $_GET['orig_client_name']): ?>
    <h4 class="heading"><?php echo __('DID', true); ?>[ <?php echo  $_GET['orig_client_name']; ?> ]</h4>
    <?php else: ?>
    <h4 class="heading"><?php echo __('DID Repository', true); ?></h4>
    <?php endif; ?>
</div>
<div class="separator bottom"></div>
<div class="newpadding">
    <div class="buttons pull-right">

        <a class="link_btn btn btn-primary btn-icon glyphicons circle_plus"
           id="add"  href="javascript:void(0)" >
            <i></i>
            <?php __('Create New') ?>
        </a>
    </div>
    <div class="buttons pull-right" style="margin:0 10px 0 0">
        <a class="list-export btn btn-primary btn-icon glyphicons file_import" href="<?php echo $this->webroot; ?>did/did/upload"><i></i><?php echo __('Upload') ?></a>
    </div>
    <div class="buttons pull-right">
        <form id="export_form" method="post" target="_blank" style="margin:0 10px 0 0">
            <input type="hidden" name="export_csv" value="1">
            <a class="list-export btn btn-primary btn-icon glyphicons file_export" id="export_csv">
                <i></i><?php __('Export'); ?>
            </a>
        </form>
    </div>
    <div class="buttons pull-right" style="margin:0 10px 0 0">
        <a rel="popup" id="delete_selected" class="link_btn btn btn-primary btn-icon glyphicons remove" href="javascript:void(0)">
            <i></i><?php __('Delete Selected') ?>
        </a>
    </div>
    <div class="buttons pull-right" style="margin:0 10px 0 0">
        <a class="btn btn-primary btn-icon glyphicons circle_plus" id="mass_assign_btn" href="#myModal_DidAssign" data-toggle="modal">
            <i></i> <?php __('Mass Assign'); ?>
        </a>
    </div>
<!--    <div class="buttons pull-right" style="margin:0 10px 0 0">-->
<!--        <a  class="link_btn btn btn-primary btn-icon glyphicons remove" href="--><?php //echo $this->webroot ?><!--did/did_reposs/delete_uploaded">-->
<!--            <i></i>--><?php //__('Delete Uploaded') ?>
<!--        </a>-->
<!--    </div>-->
    <div class="clearfix"></div>
</div>
<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-body">
            <div class="filter-bar">
                <form id="like_form" method="get">
                    <input type="hidden" id="is_export" name="is_export" value="0">
                    <div>
                        <label><?php echo __('Vendor', true); ?>:</label>
                        <select name="ingress_id">
                            <option value=""><?php __('All') ?></option>
                            <?php
                            unset($ingresses[""]);
                            foreach ($ingresses as $key => $ingress)
                            {
                                ?>
                                <option <?php if (isset($_GET['ingress_id']) && $_GET['ingress_id'] == $key) echo 'selected="selected"'; ?> value="<?php echo $key; ?>"><?php echo $ingress ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <!-- // Filter END -->
                    <!-- Filter -->
                    <div>
                        <label><?php echo __('Client', true); ?>:</label>
                        <select name="client_id">
                            <option value=""><?php __('All') ?></option>
                            <?php foreach ($carriers as $key => $carrier): ?>
                                <?php if($carrier): ?>
                                    <option <?php if (isset($_GET['client_id']) && $_GET['client_id'] == $key) echo 'selected="selected"'; ?> value="<?php echo $key ?>"><?php echo $carrier ?></option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <!-- // Filter END -->
                    <!-- Filter -->
                    <div>
                        <label><?php echo __('Code', true); ?>:</label>
                        <td>
                            <input type="text" name="number" value="<?php echo $common->set_get_value('number') ?>" />
                        </td>
                    </div>
                    <div>
                        <label><?php echo __('Show', true); ?>:</label>
                        <select name="show">
                            <option value="" <?php echo $common->set_get_select('show', '', true) ?>><?php __('All') ?></option>
                            <option value="1" <?php echo $common->set_get_select('show', 1) ?>><?php __('Assigned') ?></option>
                            <option value="2" <?php echo $common->set_get_select('show', 2) ?>><?php __('Unassigned') ?></option>
                        </select>
                    </div>
                    <!-- // Filter END -->

                    <!-- Filter -->
                    <div>
                        <input type="hidden" name="search" value="1" />
                        <button name="submit" class="btn query_btn"><?php __('Query') ?></button>
                    </div>
                    <!-- // Filter END -->
                </form>
                <!-- // Filter END -->
            </div>
            <div class="clearfix"></div>
            <div class="widget-body">
                <?php
                if (empty($data))
                {
                    ?>
                    <h2 class="msg center"><?php echo __('no_data_found') ?></h2>
                    <table id="repository" class="list footable table table-striped tableTools table-bordered  table-white table-primary" style="overflow-x: hidden;display: none;">
                        <thead>
                          <tr>
                              <th>
                                  <input type="checkbox" id="selectAll">
                              </th>
                              <th rowspan="2"><?php echo $appCommon->show_order('did', 'DID'); ?></th>
                              <th><?php echo $appCommon->show_order('vendor_name', 'DID Vendor'); ?></th>
                              <th><?php echo $appCommon->show_order('vendor_rule', 'Vendor Billing Rule'); ?></th>
                              <th><?php echo $appCommon->show_order('client_name', 'DID Client'); ?></th>
                              <th><?php echo $appCommon->show_order('client_rule', 'Client Billing Rule'); ?></th>
                              <th><?php __('Enable For Clients') ?></th>
                              <th><?php __('Assigned Date') ?></th>
                              <th><?php __('End Date') ?></th>
                              <th><?php __('Action') ?></th>
                          </tr>
                          </thead>

                        <tbody>

                        </tbody>
                    </table>
                    <?php
                }
                else
                {
                    ?>
                    <div class="clearfix"></div>
                    <div class="overflow_x">
                        <table id="repository" class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary" style="overflow: auto;overflow-x: hidden">
                            <thead>
                            <tr>
                                <th>
                                    <input type="checkbox" id="selectAll">
                                </th>
                                <th><?php echo $appCommon->show_order('did', 'DID'); ?></th>
                                <th><?php echo $appCommon->show_order('vendor_name', 'DID Vendor'); ?></th>
                                <th><?php echo $appCommon->show_order('vendor_rule', 'Vendor Billing Rule'); ?></th>
                                <th><?php echo $appCommon->show_order('client_name', 'DID Client'); ?></th>
                                <th><?php echo $appCommon->show_order('client_rule', 'Client Billing Rule'); ?></th>
                                <th><?php __('Enable For Clients') ?></th>
                                <th><?php __('Assigned Date') ?></th>
                                <th><?php __('End Date') ?></th>
                                <th><?php __('Action') ?></th>
                            </tr>
                            </thead>

                            <tbody>
                            <?php
                            foreach ($data as $item)
                            {
                                ?>
                                <tr>
                                    <td>
                                        <input type="checkbox" class="multi_select" value="<?php echo $item[0]['id']; ?>">
                                    </td>
                                    <td><?php echo $item[0]['did']; ?></td>
                                    <td><?php echo isset($item[0]['vendor_name']) ? $item[0]['vendor_name']: (isset($ingresses[$item[0]['vendor_id']]) ? $ingresses[$item[0]['vendor_id']] : ''); ?></td>
                                    <td><?php echo $item[0]['vendor_rule']; ?></td>
                                    <td class="client_name"><?php echo $item[0]['client_name'] ?></td>
                                    <td><?php echo $item[0]['client_rule']; ?></td>
                                    <td>
                                        <?php
                                        if (!empty($item[0]['client_id'])) {
                                            echo '--';
                                        } else {
                                            echo $item[0]['enable_for_clients'] ? 'Yes' : 'No';
                                        }
                                        ?>
                                    </td>
                                    <td class="assigned_time_td"><?php echo $item[0]['start_date']; ?></td>
                                    <td class="assigned_time_td"><?php echo $item[0]['end_date']; ?></td>
                                    <td>
                                        <a title="<?php __('View Actions') ?>" class="expand" href="javascript:void(0)" control="<?php echo $item[0]['id'] ?>" >
                                            <i class="icon-align-justify"></i>
                                        </a>
                                        <a title="<?php __('Edit') ?>" class="edit_item" href="javascript:void(0)" control="<?php echo $item[0]['id'] ?>" >
                                            <i class="icon-edit"></i>
                                        </a>
                                        <a title="<?php __('Delete') ?>" onclick="return myconfirm('Are you sure to delete the number[<?php echo $item[0]['did'] ?>] ?', this);" class="delete" href='<?php echo $this->webroot; ?>did/did/delete_did/<?php echo base64_encode($item[0]['id']); ?>/<?php echo isset($this->params['pass'][0]) ? $this->params['pass'][0] : ''; ?>'>
                                            <i class="icon-remove"></i>
                                        </a>
                                    </td>
                                </tr>
                                <tr style="height:auto">
                                    <td colspan="10">
                                        <div class="jsp_resourceNew_style_2" style="padding:5px;display: none;">
                                            <table class="list sub-table footable table table-striped tableTools table-bordered table-primary table-black">
                                                <thead>
                                                    <tr>
                                                        <th colspan="4">ANI</th>
                                                        <th colspan="4">DNIS</th>
                                                    </tr>
                                                     <tr>
                                                        <th><?php __('Action')?></th>
                                                        <th><?php __('Prefix')?></th>
                                                        <th><?php __('Num of Digits')?></th>
                                                        <th><?php __('New Number')?></th>
                                                        <th><?php __('Action')?></th>
                                                        <th><?php __('Prefix')?></th>
                                                        <th><?php __('Num of Digits')?></th>
                                                        <th><?php __('New Number')?></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr class="expanded-data">
                                                       <td>
                                                        <select data-group="1" class="width120 actions" name = "ani[actions]">
                                                            <?php foreach ($actions as $key => $action) :?>
                                                                <option value="<?php echo $key;?>">
                                                                     <?php echo $action;?>
                                                                </option>
                                                            <?php endforeach;?>
                                                        </select>
                                                        </td>
                                                        <td>
                                                            <input data-group="1" data-action="1" class="digits input in-text" type="text" check="MyNum" maxlength="10" value="" name="ani[digits]" disabled="disabled">
                                                        </td>
                                                        <td>
                                                        <select  data-group="1" data-action="3" class="width120 deldigits" name = "ani[deldigits]" disabled="disabled">
                                                            <?php foreach ($del_digits as $key => $del_digit) :?>
                                                                <option value="<?php echo $key;?>">
                                                                     <?php echo $del_digit;?>
                                                                </option>
                                                            <?php endforeach;?>
                                                        </select>
                                                        </td>
                                                        <td>
                                                            <input  data-group="1" data-action="2" type="text" name="ani[ani]" value="" class="ani" disabled="disabled">
                                                        </td>
                                                         <td>
                                                        <select  data-group="2" class="width120 actions" name = "dnis[actions]">
                                                            <?php foreach ($actions as $key => $action) :?>
                                                                <option value="<?php echo $key;?>">
                                                                     <?php echo $action;?>
                                                                </option>
                                                            <?php endforeach;?>
                                                        </select>
                                                        </td>
                                                        <td>
                                                            <input data-group="2" data-action="1" class="digits input in-text" type="text" check="MyNum" maxlength="10" value="" name="dnis[digits]" disabled="disabled">
                                                        </td>
                                                        <td>
                                                        <select  data-group="2" data-action="3" class="width120 deldigits" name = "dnis[deldigits]" disabled="disabled">
                                                            <?php foreach ($del_digits as $key => $del_digit) :?>
                                                                <option value="<?php echo $key;?>">
                                                                     <?php echo $del_digit;?>
                                                                </option>
                                                            <?php endforeach;?>
                                                        </select>
                                                        </td>
                                                        <td>
                                                             <input data-group="2" data-action="2" type="text" name="dnis[dnis]" value="" class="dnis[dnis]" disabled="disabled">
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </td>
                                    <td class="hidden"></td>
                                    <td class="hidden"></td>
                                    <td class="hidden"></td>
                                    <td class="hidden"></td>
                                    <td class="hidden"></td>
                                    <td class="hidden"></td>
                                    <td class="hidden"></td>
                                    <td class="hidden"></td>
                                    <td class="hidden"></td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                        <div class="row-fluid separator">
                            <div class="pagination pagination-large pagination-right margin-none">
                                <?php echo $this->element('page'); ?>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<!-- did assign start -->
<form action="<?php echo $this->webroot?>did/did/mass_assign" method="post" id="myModal_DidAssign_form">
    <div id="myModal_DidAssign" class="modal hide">
        <div class="modal-header">
            <button data-dismiss="modal" class="close" type="button">&times;</button>
            <h3><?php __('Client DID Assignment'); ?></h3>
        </div>
        <div class="modal-body">
            <input type="hidden" name="selected_did" id="selected_did" />
            <table class="table dynamicTable tableTools table-bordered  table-white">
                  <tr>
                        <td class="align_right"><?php __('Assign to')?>:</td>
                        <td>
                            <select name="egress_id" id="egress_id">
                                <?php foreach ($egresses as $key => $egress): ?>
                                    <option value="<?php echo $key; ?>">
                                        <?php echo $egress ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
        <!--                    <a title="Create New Client" class="create_new_client" href="#myModal_CreateClient" data-toggle="modal">-->
        <!--                        <i class="icon-plus"></i>-->
        <!--                    </a>-->
                        </td>
                 </tr>
                 <tr>
                     <td class="align_right"><?php __('Client Billing Rule') ?>:</td>
                     <td>
                         <select name="client_billing_rule" id="client_billing_rule" class="validate[required]">
                             <?php foreach ($billingRules as $billing_rule_id => $billing_rule_name): ?>
                                 <option value="<?php echo $billing_rule_id ?>"><?php echo $billing_rule_name ?></option>
                             <?php endforeach; ?>
                         </select>
                     </td>
                 </tr>
            </table>
        </div>
        <div class="modal-footer">
            <input type="button" id="did_assign_submit" class="btn btn-primary" value="<?php __('Submit'); ?>">
            <a href="javascript:void(0)" id="did_assign_close" data-dismiss="modal" class="btn btn-default"><?php __('Close'); ?></a>
        </div>

    </div>
</form>

<script type="text/javascript">

    function expand(element, callback = function() {} ) {

        if (!$('#trAdd').length) {
            let href = $(element).data('href');
            let $this = $(element);
            let trDetail = $this.parent().parent().next();
            let isExpanded = trDetail.find('.jsp_resourceNew_style_2').hasClass('expanded');
            $(element).closest('tr').removeClass('create_details').addClass('show_details');

            $('.jsp_resourceNew_style_2.expanded').removeClass('expanded').slideUp('slow');

            if (isExpanded == true) {
                $(element).closest('tr').removeClass('show_details');
                return true;
            }

            trDetail.find('.jsp_resourceNew_style_2').addClass('expanded').slideDown('slow');
        }

        callback();
    }

    jQuery(function() {

        // actions
        var tbl = $('#repository'), action = '', group = '';
        $('.actions').on('change', function(){
            action = $(this).val();
            group = $(this).attr('data-group');
            let parentTr = $(this).parent().parent();

            parentTr.find('*[data-group="' + group + '"]').each(function (key, item) {
                if ($(item).data('action') !== undefined) {
                    $(item).prop('disabled', true)
                }
            });

            parentTr.find('*[data-action="' + action + '"][data-group="' + group + '"]').prop('disabled', false);

            if(action){
                if(!tbl.find('trAdd').length){
                    $(this).closest('tr').find('.edit_item').click();
                }
            }

        });
        jQuery('input[check=MyNum]').xkeyvalidate({type: 'MyNum'}).attr('maxLength', '10');


        jQuery('#add').click(function() {
            $('.msg').hide();
            $('table.list').show();

            jQuery('table#repository').trAdd({
                ajax: "<?php echo $this->webroot ?>did/did/action_edit_panel",
                ajaxData: "<?php echo $this->params['getUrl'] ?>",
                action: "<?php echo $this->webroot ?>did/did/action_edit_panel",
                insertNumber: 'first',
                callback:function(){
                    var trAdd = $('.add_edit');

                    trAdd.find('select.actions').on('change', function(){
                        if($(this).val()){
                              trAdd.find('select.actions[data-group!="'+$(this).attr("data-group")+'"]').val('');
                              trAdd.find('*[data-action]').attr('disabled','disabled').val('');
                              trAdd.find('[data-group="'+$(this).attr("data-group")+'"][data-action="'+$(this).val()+'"]').removeAttr('disabled');
                        }
                    });
                },
                removeCallback: function() {
                    if (jQuery('table.list tr').size() == 1) {
                        jQuery('table.list').hide();
                        $('.msg').show();
                    }
                },
                onsubmit: function(options)
                {
                    var validate_flg = $("#trAdd").find('input[class*=validate]').validationEngine('validate');
                    if(validate_flg){
                        return false;
                    }
                    var ingress_id = parseInt($("#trAdd").find('select[name="data[vendor_id]"]').val());
                    if (!ingress_id)
                    {
                        jGrowl_to_notyfy("<?php __("You need to add the vendor"); ?>", {theme: 'jmsg-error'});
                        return false;
                    }
//                     var client_id = parseInt($("#trAdd").find('select[name="data[client_id]"]').val());
//                    if (!client_id)
//                    {
//                        jGrowl_to_notyfy("<?php //__("You need to add the client"); ?>//", {theme: 'jmsg-error'});
//                        return false;
//                    }
                    var number = $("#trAdd").find('input[name="data[code]"]').val();
//                    var is_exists = jQuery.ajaxData("<?php //echo $this->webroot ?>//did/did_reposs/chech_num/" + number);
//                    if (is_exists.indexOf("true") != -1)
//                    {
//                        jGrowl_to_notyfy("The number [" + number + "] already exists!", {theme: 'jmsg-error'});
//                        return false;
//                    }
                    return true;
                }
            });
            jQuery(this).parent().parent().show();
        });

        jQuery('a.edit_item').click(function() {
            expand(this);
            let self = this;
            let client_name = $(this).closest('tr').find('.client_name').text();
            jQuery(this).parent().parent().trAdd({
                action: '<?php echo $this->webroot ?>did/did/action_edit_panel/<?php echo isset($this->params['pass'][0]) ? $this->params['pass'][0] : '0'; ?>/' + jQuery(this).attr('control'),
                ajax: '<?php echo $this->webroot ?>did/did/action_edit_panel/<?php echo isset($this->params['pass'][0]) ? $this->params['pass'][0] : '0'; ?>/' + jQuery(this).attr('control'),
                saveType: 'edit',
                callback:function() {
                    let trAdd = $('.add_edit');
                    let ani = $('#ani').val();
                    let dnis = $('#dnis').val();
                    let aniAction = $('#ani').data('action');
                    let dnisAction = $('#dnis').data('action');

                    if (aniAction == 1) {
                        $("input[name='ani[digits]']").val(ani);
                    } else if (aniAction == 2) {
                        $("input[name='ani[ani]']").val(ani);
                    } else {
                        $("select[name='ani[deldigits]']").val(ani);
                    }

                    if (dnisAction == 1) {
                        $("input[name='dnis[digits]']").val(dnis);
                    } else if (dnisAction == 2) {
                        $("input[name='dnis[dnis]']").val(dnis);
                    } else {
                        $("select[name='dnis[deldigits]']").val(dnis);
                    }

                    if (ani.toString().length == 0) {
                        aniAction = 0;
                    }
                    if (dnis.toString().length == 0) {
                        dnisAction = 0;
                    }

                    $("select[name='ani[actions]']").val(aniAction);
                    $("select[name='dnis[actions]']").val(dnisAction);


                    trAdd.find('select.actions').on('change', function () {
                        if ($(this).val()) {
                            trAdd.find('select.actions[data-group!="' + $(this).attr("data-group") + '"]').val('');
                            trAdd.find('*[data-action]').attr('disabled', 'disabled').val('');
                            trAdd.find('[data-group="' + $(this).attr("data-group") + '"][data-action="' + $(this).val() + '"]').removeAttr('disabled');
                        }
                    });
                    if (action && group) {
                        trAdd.find('select.actions[data-group="' + group + '"]').val(action).change();
                    }
                    $('.actions').change();
                    if (client_name) {
                        $('select#client_id option').each(function (i, v) {
                            if ($(v).text() == client_name) {
                                $(v).attr('selected', 'selected')
                            }
                        })
                    }

                },
                onsubmit: function(options)
                {
                    var ingress_id = parseInt($("#trAdd").find('select[name="data[vendor_id]"]').val());
                    if (!ingress_id)
                    {
                        jGrowl_to_notyfy("<?php __("You need to add the vendor"); ?>", {theme: 'jmsg-error'});
                        return false;
                    }

                    let storage = $("#trAdd").children().get(0);

                    $(".jsp_resourceNew_style_2.expanded .expanded-data").find('td').each(function (key, item) {
                        let children = $(item).children().get(0);

                        $(storage).append("<input type='hidden' name='" + children.name + "' value='" + $(children).val() + "'/>");
                    });

                    let new_client_name = $("#trAdd").find('#client_id option:selected').text();
                    if(new_client_name && client_name && new_client_name != client_name){
                        console.log(client_name);
                        console.log(new_client_name);
                        $(storage).append("<input type='hidden' name='client_change_flg' value= 1 />");

                    }

                    return true;
                }
            });
        });

        var $selectAll = $('#selectAll');
        var $multi_select = $('.multi_select');
        var $delete_selected = $('#delete_selected');
        var $export_csv = $('#export_csv');
        var $export_form = $('#export_form');

        $selectAll.change(function() {
            $multi_select.attr('checked', $(this).is(':checked'));
        });


        $export_csv.click(function() {
            $export_form.submit();
        });


        $delete_selected.click(function() {
            var selected = new Array();
            $multi_select.each(function() {
                var $this = $(this);
                if ($this.is(':checked')) {
                    selected.push($this.val());
                }
            });
            if (!selected.length) {
                jGrowl_to_notyfy("You did not select any item!", {theme: 'jmsg-error'});
            }
            else
            {
                bootbox.confirm('Are you sure?', function(result) {
                    if (result)
                    {
                        $.ajax({
                            'url': '<?php echo $this->webroot; ?>did/did/multiple_delete',
                            'type': 'POST',
                            'dataType': 'json',
                            'data': {'selected[]': selected},
                            'success': function(data) {
                                if (data.status == 1)
                                {
                                    jGrowl_to_notyfy("The numbers you selected is deleted successfully!", {theme: 'jmsg-success'});
                                    $multi_select.each(function () {
                                        var $this = $(this);
                                        if ($this.is(':checked')) {
                                            $this.closest('tr').remove();
                                        }
                                    });
                                }
                                else{
                                    jGrowl_to_notyfy("The numbers you selected is deleted failed!", {theme: 'jmsg-error'});
                                }
//                                window.setTimeout("window.location.reload();", 3000);
                            }
                        });
                    }
                })
            }
        });
        <?php if (!count($data) && !isset($_GET['search'])): ?>
        $("#add").click();
        <?php endif; ?>
    });
</script>

<script>
    $(function(){
        $("#mass_assign_btn").click(function(){
            var $mass_assign_checked = $('.multi_select:checked');
            if ($mass_assign_checked.size() == 0){
                jGrowl_to_notyfy('<?php __('Nothing is selected'); ?>', {theme: 'jmsg-error'});
                return false;
            }
        });

        $('#did_assign_submit').click(function() {
//            var client_id = parseInt($("#myModal_DidAssign").find('#egress_id').val());
//            if (!client_id)
//            {
//                jGrowl_to_notyfy("<?php //__("You need to add the client"); ?>//", {theme: 'jmsg-error'});
//                return false;
//            }
            var selected = new Array();
            $('.multi_select').each(function() {
                var $this = $(this);
                if ($this.is(':checked')) {
                    selected.push($this.val());
                }
            });
            var selected_str = selected.join(',');
            $('#selected_did').val(selected_str);

            $('#myModal_DidAssign_form').submit();
        });

        $('#add').on('click', function(){
            $('#save').attr('title', 'Save');
        });

    })
</script>

<script>
    $(function () {
        $('a.expand').click(function () {
            $(this).closest('tr').find('.edit_item').click();
            $('table#repository').resize();
        });
    });
</script>