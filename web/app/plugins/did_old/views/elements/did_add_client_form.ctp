<form id="myform_client" method="post">
    <table class="table dynamicTable tableTools table-bordered  table-white form">
        <col width="40%">
        <col width="60%">
        <tbody>
            <tr>
                <td class="align_right"><?php __('Client Name') ?> </td>
                <td>
                    <input type="text" id="vendor_name" name="name">
                </td>
            </tr>
            <tr>
                <td class="align_right"><?php __('Login Username') ?> </td>
                <td>
                    <input type="text" name="login_username" autocomplete="off">
                </td>
            </tr>
            <tr>
                <td class="align_right"><?php __('Login Password'); ?> </td>
                <td>
                    <input type="password" name="login_password" autocomplete="off">
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
            <?php if (!$is_ajax || $type): ?>
                <tr>
                    <td class="right"><?php __('Pricing Rule') ?></td>
                    <td>
                        <select name="pricing_rule">
                            <?php
                            foreach ($routing_rules as $key => $item):
                                ?>
                                <option value="<?php echo $key; ?>"><?php echo $item; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                </tr>
            <?php else: ?>
                <tr class="hidden"><td><input type="hidden" name="pricing_rule" value="0"  /></td><td></td></tr>
            <?php endif; ?>
            <tr>
                <td class="align_right"><?php __('IP Addresses') ?> </td>
                <td>
                    <input type="text" name="ip_addresses[]" style="width:220px;">
                    <a href="###" id="add_ip">
                        <i class="icon-plus"></i>
                    </a>
                </td>
            </tr>
            <tr style="display:none;">
                <td></td>
                <td>
                    <input type="text" name="ip_addresses[]" style="width:220px;">
                    <a href="###" class="ip_delete">
                        <i class="icon-remove"></i>
                    </a>
                </td>
            </tr>
            <tr>
                <td class="align_right"><?php __('Call Limit') ?> </td>
                <td>
                    <input type="text" name="call_limit">
                </td>
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

            <tr style="text-align:center;">
                <td colspan="5" class="button-groups center input in-submit">
                    <input type="submit" id="subbtn" class="btn btn-primary" value="<?php __('Submit') ?>">
                </td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
</form>

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
            $parent.after($clone);
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

    });

</script>