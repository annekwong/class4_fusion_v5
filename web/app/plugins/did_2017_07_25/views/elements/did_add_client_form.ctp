<form id="myform_client" method="post">
    <table class="table dynamicTable tableTools table-bordered  table-white form">
        <col width="21%">
        <col width="79%">
        <tbody>
            <tr>
                <td class="align_right"><?php __('Client Name') ?> </td>
                <td>
                    <input type="text" id="vendor_name" class="validate[required]"  name="name">
                </td>
            </tr>
            <tr>
                <td class="align_right"><?php __('Company') ?> </td>
                <td>
                    <input type="text" name="company">
                </td>
            </tr>
            <tr>
                <td class="align_right"><?php __('Login Username') ?> </td>
                <td>
                    <input type="text" name="login_username" class="validate[custom[onlyLetterNumberLineSpace]]" autocomplete="off">
                </td>
            </tr>
            <tr>
                <td class="align_right"><?php __('Login Password'); ?> </td>
                <td>
                    <input type="password" name="login_password" autocomplete="off">
                </td>
            </tr>
<!--            --><?php //if (!$is_ajax): ?>
<!--                <tr>-->
<!--                    <td class="right">--><?php //__('Pricing Rule') ?><!--</td>-->
<!--                    <td>-->
<!--                        <select name="pricing_rule" id="pricing_rule">-->
<!--                            <option></option>-->
<!--                            --><?php
//                            foreach ($routing_rules as $key => $item):
//                                ?>
<!--                                <option value="--><?php //echo $key; ?><!--">--><?php //echo $item; ?><!--</option>-->
<!--                            --><?php //endforeach; ?>
<!--                        </select>-->
<!--                        <a href="#myModal_pricing_rule" class="add_pricing_rule" data-toggle="modal">-->
<!--                            <i class="icon-plus"></i>-->
<!--                        </a>-->
<!--                    </td>-->
<!--                </tr>-->
<!--            --><?php //else: ?>
<!--                <tr class="hidden"><td><input type="hidden" name="pricing_rule" value="0"  /></td><td></td></tr>-->
<!--            --><?php //endif; ?>
            <tr>
                <td class="align_right">Main Email</td>
                <td>
                    <input type="text" name="email" class="validate[custom[email]]">
                </td>
            </tr>
            <tr>
                <td class="align_right">NOC Email</td>
                <td>
                    <input type="text" name="noc_email" class="validate[custom[email]]">
                </td>
            </tr>
            <tr>
                <td class="align_right">Billing Email</td>
                <td>
                    <input type="text" name="billing_email" class="validate[custom[email]]">
                </td>
            </tr>
<!--            <tr>-->
<!--                <td class="align_right">Billing Rule</td>-->
<!--                <td>-->
<!--                    <select name="pricing_rule" class="validate[required]">-->
<!--                        --><?php //foreach ($routing_rules as $key => $routing_rule): ?>
<!--                            <option value="--><?php //echo $key; ?><!--">--><?php //echo $routing_rule; ?><!--</option>-->
<!--                        --><?php //endforeach; ?>
<!--                    </select>-->
<!--                </td>-->
<!--            </tr>-->
            <tr>
                <td class="align_right">Address</td>
                <td>
                    <textarea name="address" id="" cols="30" rows="5" maxlength="500"></textarea>
                </td>
            </tr>
            <tr>
                <td class="align_right padding-r20"><?php echo __('mode') ?></td>
                <td>
                    <?php
                    $st = array('1' => __('Prepaid', true), '2' => __('postpaid', true));
                    echo $form->input('mode', array('id' => 'mode', 'options' => $st, 'label' => false, 'div' => false, 'type' => 'select', 'class' => 'input in-text in-select'))
                    ?>
                </td>
            </tr>
            <tr>
                <td class="align_right padding-r20"><span id="credit_text"><?php echo __('allowedcredit') ?></span></td>
                <td style="text-align: left;">
                                <span id="unlimited_panel">
                                    <?php __('Unlimited')?>
                                    <?php echo $form->input('unlimited_credit', array('id' => 'unlimited_credit','class' => 'in-decimal input in-checkbox', 'label' => false, 'div' => false, 'type' => 'checkbox')) ?>
                                </span>
                    <?php echo $form->input('allowed_credit', array('id' => 'allowed_credit', 'label' => false, 'value' => '0.000', 'div' => false, 'type' => 'text', 'class' => 'validate[min[0],custom[number]]', 'maxlength' => '30', 'style' => 'width: 100px; display: inline-block;')) ?>
                    <span class='money' style="display:inline-block">USD</span>
                </td>
            </tr>
            <tr>
                <td class="right"><?php __('Enable Billing By Port') ?></td>
                <td>
                    <select name="billing_port_type">
                        <option value="0">No</option>
                        <option value="2">By Port Limit</option>
                    </select>
                </td>
            </tr>
            <tr class="hidden">
                <td class="right"><?php __('Charge / Port / Month') ?></td>
                <td>
                    <input type="text" name="price_per_max_channel" class="validate[custom[number]]">
                </td>
            </tr>
            <tr>
                <td class="right"><?php __('IP Addresses') ?></td>
                <td>
                    <input type="text" id="ip_orig" name="ip_addresses[]" class="validate[custom[ipv4]]">
                    <input type="text" name="ip_port[]"  class="width40 validate[custom[integer]]" maxlength="5" >
                    <a href="javascript:void(0)" id="add_ip">
                        <i class="icon-plus"></i>
                    </a>
                </td>
            </tr>
            <tr style="display:none;">
                <td></td>
                <td>
                    <input type="text" name="ip_addresses[]">
                    <input type="text" name="ip_port[]"  class="width40" maxlength="5" >
                    <a href="javascript:void(0)" class="ip_delete">
                        <i class="icon-remove"></i>
                    </a>
                </td>
            </tr>
            <tr>
                <td class="align_right"><?php __('Call Limit') ?> </td>
                <td>
                    <input class="validate[custom[integer]]" type="text" name="call_limit">
                </td>
            </tr>
            <tr>
                <td class="align_right padding-r10">Enable T38</td>
                <td>
                <select name ="t_38">
                    <option value="true">True</option>
                    <option value="false">False</option>
                </select>
            </tr>
            <tr>
                <td class="align_right"><?php __('Media Type') ?> </td>
                <td>
                    <select name="media_type">
                        <option value="2"><?php __('Bypass Media') ?></option>
                        <option value="1"><?php __('Proxy Media') ?></option>
                        <option value="0"><?php __('Transcoding media') ?></option>
                    </select>
                </td>
            </tr>
<!--                            <tr>
                <td class="align_right"><?php __('T.38') ?> </td>
                <td>
                    <input type="checkbox" name="t_38">
                </td>
            </tr>
            <tr>
                <td class="align_right"><?php __('RFC 2833') ?> </td>
                <td>
                    <input type="checkbox" name="rfc2833">
                </td>
            </tr>-->
            <tr>
                <td class="align_right"><?php __('Auto Invoicing') ?> </td>
                <td>
                    <input type="checkbox" name="auto_invoicing">
                </td>
            </tr>

<!--            <tr>-->
<!--                <td class="align_right">--><?php //__('Carrier Self-Service Portal Permission') ?><!-- </td>-->
<!--                <td class="value">-->
<!--                    --><?php //echo $this->element('portal/add_permission_div',array('have_did' => 1)); ?>
<!--                </td>-->
<!--            </tr>-->

            <?php if (!$is_ajax): ?>
<!--                <tr>-->
<!--                    <td class="right">--><?php //__('DID Search'); ?><!--:</td>-->
<!--                    <td>-->
<!--                        <input type="text" id="did_search_input" class="validate[custom[integer]]" />-->
<!--                        <i class="icon-spinner icon-spin icon-large hide" id="loading_i"></i>-->
<!--                        <a href="javascript:void(0)" id="did_search" title="search"><i class="icon-search"></i></a>-->
<!--                    </td>-->
<!--                </tr>-->
<!--                <tr>-->
<!--                    <td class="right">--><?php //__('DID'); ?><!--:</td>-->
<!--                    <td>-->
<!--                        <select multiple id="pre-selected-options" class="multiselect" name="did_assign[]">-->
<!--                        </select>-->
<!--                    </td>-->
<!--                </tr>-->
<!--            <tr>-->
<!--                <td rowspan="2">-->
<!--                    <a onclick="return false;" href="#" class="btn btn-primary btn-icon glyphicons circle_plus">-->
<!--                        --><?php //echo __('add', true); ?><!-- --><?php //__('Action') ?><!--<i></i></a>-->
<!--                </td>-->
<!--            </tr>-->
<!--            <tr>-->
<!--                <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary"   id="list_table" >-->
<!--                    <thead>-->
<!--                    <tr>-->
<!--                        <th width="12%">--><?php //__('timeprofile') ?><!--</th>-->
<!--                        <th width="8%">--><?php //__('Target') ?><!--</th>-->
<!--                        <th width="8%">--><?php //__('code') ?><!--</th>-->
<!--                        <th width="8%">--><?php //__('action') ?><!--</th>-->
<!--                        <th width="8%">--><?php //__('Chars to Add') ?><!--</th>-->
<!--                        <th width="8%">--><?php //__('Num of chars to Del') ?><!--</th>-->
<!--                        <th width="8%">--><?php //__('numbertype') ?><!--</th>-->
<!--                        <th width="8%">--><?php //__('numberlength') ?><!--</th>-->
<!--                        <th width="8%" class="last">&nbsp;</th>-->
<!--                    </tr>-->
<!--                    </thead>-->
<!--                    <tbody class="rows" id="rows-ip">-->
<!--                    <tr class="row-" id="row-" style="">-->
<!--                    </tr>-->
<!--                    <tr  style="display:none" id="tpl-ip" class="  row-2">-->
<!--                    </tr>-->
<!--                    </tbody>-->
<!--                </table>-->
<!--            </tr>-->
<!---->
            <tr style="text-align:center;">
                <td colspan="2" class="button-groups center input in-submit">
                    <input style="position: relative; top: 55px;" type="submit" id="subbtn" class="btn btn-primary" value="<?php __('Submit') ?>">
                </td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
</form>
<div id="myModal_pricing_rule" class="modal hide">
    <div class="modal-header">
        <button data-dismiss="modal" class="close" type="button">&times;</button>
        <h3><?php __('Create New Pricing Rule'); ?></h3>
    </div>
    <div class="modal-body">
    </div>
    <div class="modal-footer">
        <input type="button" class="btn btn-primary sub" value="<?php __('Submit'); ?>">
        <a href="javascript:void(0)" data-dismiss="modal" class="btn btn-default close_btn"><?php __('Close'); ?></a>
    </div>
</div>
<!--
<script type="text/javascript">
    $(function(){
        $('#pre-selected-options').multiSelect({
            selectableHeader: "<div class='custom-header'><?php __('Searched Items'); ?></div>",
            selectionHeader: "<div class='custom-header'><?php __('Selected Items'); ?></div>"
        });
        var ajax_get_did = function(){
            var did = $("#did_search_input").val();
            var did_length = did.length;
            if($("#did_search_input").validationEngine('validate'))
                return false;
            if (did_length > 1)
            {
                var selected_arr = new Array();
                $(".ms-selectable").eq(0).find(".ms-elem-selectable").each(function(){
                    if($(this).is(":visible")){
                        var not_selected = $(this).find('span').eq(0).html();
                        $('#pre-selected-options').children().each(function(){
                            if($(this).val() == not_selected){
                                $(this).remove();
                                return false;
                            }
                        });
                    }else{
                        selected_arr.push($(this).find('span').eq(0).html());
                    }
                });
                $.ajax({
                    'url': '<?php echo $this->webroot ?>jurisdictionprefixs/ajax_get_unAssignment_did',
                    'type': 'POST',
                    'dataType': 'json',
                    'data': {'did': did},
                    'beforeSend': function(XMLHttpRequest) {
                        $('#loading_i').show();//显示等待消息
                        $("#did_search").hide();
                    },
                    'success': function(data) {
                        var has_data = false;
                        $.each(data, function(index, item) {
                            if(selected_arr.indexOf(item.number) < 0){
                                $('#pre-selected-options').prepend('<option value="'+item.number+'">'+item.number+'</option>');
                                has_data = true;
                            }
                        });
                        if(has_data){
                            $('#pre-selected-options').find(".no_data_found").remove();
                        }
                        console.log('select children size: ' + $('.ms-selectable').find("li:visible").size());
                        if($('#pre-selected-options').children().size() == 0){
                            $('#pre-selected-options').prepend('<option class="no_data_found" value="0" disabled><?php __('no data found'); ?></option>');
                        }
                        $('#pre-selected-options').multiSelect('refresh');
                        $('#loading_i').hide();//显示等待消息
                        $("#did_search").show();
                    }
                });
            }
        }
        $("#did_search").bind('click',ajax_get_did);
    });
</script>
-->
<script>
    function refresh_billing_rule(opt,selected) {
        $.ajax({
            'url': '<?php echo $this->webroot; ?>did/wizard/get_billing_rule',
            'type': 'GET',
            'dataType': 'json',
            'success': function(data) {
                opt.empty();
                $.each(data, function(index, item) {
                    if(selected == item[0]['id']){
                        opt.append('<option value="' + item[0]['id'] + '" selected="selected">' + item[0]['name'] + '</option>');
                    }else{
                        opt.append('<option value="' + item[0]['id'] + '">' + item[0]['name'] + '</option>');
                    }
                });
            }
        });
    }

    function clearPassword(){
        $('input[name="login_username"]').val('');
        $('input[name="login_password"]').val('');
    }

    $(function() {
        var $add_ip = $('#add_ip');
        var $ip_delete = $('.ip_delete');
        var $myform_client = $('#myform_client');
        var $vendor_name = $('#vendor_name');

        $add_ip.click(function() {
            var $this = $(this);
            var $parent = $this.parents('tr');
            var $clone = $parent.next().clone();
            $parent.next().after($clone);
            $clone.show();
        });

        $ip_delete.live('click', function() {
            $(this).parents('tr').remove();
        });
        $myform_client.submit(function() {
            // check if exists client name
            var name = $vendor_name.val();
            if (!name)
            {
                jQuery.jGrowlError("Name can not be empty!");
                return false;
            }
            var flag = true;

            var name_data = jQuery.ajaxData("<?php echo $this->webroot; ?>clients/check_name/" + name);
            name_data = name_data.replace(/\n|\r|\t/g, "");
            if (name_data == 'false') {
                jQuery.jGrowlError(name + " is already in use!");
                flag = false;
            }

            return true;
        });

        jQuery('#mode').change(function() {
            if (jQuery(this).val() == '2') {
                jQuery('#allowed_credit').parent().parent().show();
                jQuery('#unlimited_panel').show();
                $('#credit_text').text('Allowed Credit');
            } else {
//                jQuery('#ClientAllowedCredit').val(0).next().hide();
                jQuery('#unlimited_panel').hide();
                jQuery('#unlimited_credit').attr('checked', false);
                jQuery("#allowed_credit").show();
                jQuery(".money").show();
                $('#credit_text').text('Test Credit');
            }
        }).trigger('change');

        jQuery("#unlimited_credit").click(function() {
            var checked = jQuery(this).attr('checked');
            jQuery("#allowed_credit").show();
            jQuery(".money").show();
            if (checked)
            {
                jQuery("#allowed_credit").hide();
                jQuery(".money").hide();
            }

        });


        setTimeout('clearPassword();',500);

        $("select[name=billing_port_type]").change(function () {
            if($(this).val() == 0) {
                $(this).parent().parent().next().css({
                    visibility: 'hidden',
                    display: 'none'
                });
            } else {
                $(this).parent().parent().next().css({
                    visibility: 'visible',
                    display: 'table-row'
                });
            }
        });

        $("#myModal_pricing_rule").on('shown',function(){
            $(this).find('.modal-body').load('<?php echo $this->webroot ?>did/wizard/ajax_add_billing_rule');
        });
        $("#myModal_pricing_rule").find('.sub').click(function(){
            $.ajax({
                url: "<?php echo $this->webroot ?>did/billing_rule/plan_edit_panel",
                type: 'post',
                dataType: 'text',
                data: $('#form1').serialize(),
                success: function(data) {
                    if(data){
                        refresh_billing_rule($("#pricing_rule"),data);
                        jGrowl_to_notyfy('<?php __('Create succeed!'); ?>', {theme: 'jmsg-success'});
                        $("#myModal_pricing_rule").find('.close').click();
                    }else{
                        jGrowl_to_notyfy('<?php __('Create failed!'); ?>', {theme: 'jmsg-error'});
                    }
                }
            });
        });

    });

</script>