<style>
     /*select, textarea, input[type="text"],input[type="password"]{margin-bottom: 0;width: 220px;}*/
    input{margin-bottom: 0;width: 220px;}
</style>
<ul class="breadcrumb">
    <li><?php echo __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>did/clients"><?php __('Origination') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot . $this->params['url']['url'] ?>"><?php echo __('Edit Clients') ?></a></li>
</ul>
<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Edit Clients') ?></h4>
    
</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
        <a  class="btn btn-icon btn-inverse glyphicons circle_arrow_left" href="<?php echo $this->webroot ?>did/clients/index"><i></i>
            <?php __('Back')?>
        </a>
    </div>
    <div class="clearfix"></div>
<div class="innerLR">
    <div class="widget widget-tabs widget-body-white">
        <div class="widget-body">
            <div class="clearfix"></div>
            <div id="container">
                <form id="myform" method="post">
                    <table class="table dynamicTable tableTools table-bordered  table-white form">
                        <col width="21%">
                        <col width="79%">
                        <tbody>
                            <tr>
                                <td class="align_right"><?php __('Client Name')?> </td>
                                <td>
                                    <input type="text" id="vendor_name" name="name" value="<?php echo $client['name'] ?>">
                                </td>
                            </tr>
                            <tr>
                                <td class="align_right"><?php __('Company') ?> </td>
                                <td>
                                    <input type="text" name="company" value="<?php echo $client['company'] ?>">
                                </td>
                            </tr>
                            <tr>
                                <td class="align_right"><?php __('Login Username')?> </td>
                                <td>
                                    <input type="text" class="validate[custom[onlyLetterNumberLineSpace]]" name="login_username" value="<?php echo $client['login_username'] ?>" autocomplete="off">
                                    <input type="hidden" value="<?php echo $client['name'] ?>" id="present_vendor_name" />
                                </td>
                            </tr>
                            <tr>
                                <td class="align_right"><?php __('New Password')?> </td>
                                <td>
                                    <input type="text" onfocus="this.type='password'" name="login_password" autocomplete="off">
                                </td>
                            </tr>
                            <tr>
                                <td class="align_right">Main Email</td>
                                <td>
                                    <input type="text" name="email" class="validate[custom[email]]" value="<?php echo $client['email'] ?>">
                                </td>
                            </tr>
                            <tr>
                                <td class="align_right">NOC Email</td>
                                <td>
                                    <input type="text" name="noc_email" class="validate[custom[email]]" value="<?php echo $client['noc_email'] ?>">
                                </td>
                            </tr>
                            <tr>
                                <td class="align_right">Billing Email</td>
                                <td>
                                    <input type="text" name="billing_email" class="validate[custom[email]]" value="<?php echo $client['billing_email'] ?>">
                                </td>
                            </tr>
                            <tr>
                                <td class="align_right">Address</td>
                                <td>
                                    <textarea name="address" id="" cols="30" rows="5" maxlength="500"><?php echo $client['address'] ?></textarea>
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
                                        <option value="0" <?php if($client['billing_port_type'] == 0) echo 'selected'; ?> >No</option>
                                        <option value="2" <?php if($client['billing_port_type'] == 2) echo 'selected'; ?> >By Port Limit</option>
                                    </select>
                                </td>
                            </tr>
                            <tr class="<?php if($client['billing_port_type'] == 0) echo 'hidden'; ?>">
                                <td class="right"><?php __('Charge / Port / Month') ?></td>
                                <td>
                                    <input type="text" name="price_per_max_channel" value="<?php echo $client['price_per_max_channel']; ?>" class="validate[custom[number]]">
                                </td>
                            </tr>
                            <tr>
                                <td class="right"><?php __('IP Addresses')?></td>
                                <td>
                                    <input type="text" id="ip_orig" name="ip_addresses[]" value="<?php echo isset($client['resource_ips'][0]) ? $client['resource_ips'][0] : ''; ?>">
                                    <input type="text" name="ip_port[]" class="width40" maxlength="5" value="<?php echo isset($client['resource_ports'][0]) ? $client['resource_ports'][0] : ''; ?>">
                                    <a href="javascript:void(0)" id="add_ip">
                                        <i class="icon-plus"></i>
                                    </a>
                                </td>
                            </tr>
                            <tr style="display:none;">
                                <td></td>
                                <td>
                                    <input type="text" name="ip_addresses[]">
                                    <input type="text" name="ip_port[]" class="width40" maxlength="5">
                                    <a href="javascript:void(0)" class="ip_delete">
                                        <i class="icon-remove"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php
                            $ip_cnt = count($client['resource_ips']);
                            if ($ip_cnt > 1):
                                ?>
                                <?php for ($i = 1; $i < $ip_cnt; $i++): ?>
                                <tr>
                                    <td></td>
                                    <td>
                                        <input type="text" name="ip_addresses[]" value="<?php echo $client['resource_ips'][$i] ?>">
                                        <input type="text" name="ip_port[]" class="width40" maxlength="5" value="<?php echo $client['resource_ports'][$i] ?>">
                                        <a href="javascript:void(0)" class="ip_delete">
                                            <i class="icon-remove"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endfor; ?>
                            <?php endif; ?>
                            <tr>
                                <td class="align_right"><?php __('Call Limit')?> </td>
                                <td>
                                    <input type="text" name="call_limit"  value="<?php echo $client['call_limit'] ?>">
                                </td>
                            </tr>
                            <tr>
                                <td class="align_right padding-r10">Enable T38</td>
                                <td>
                                <select name ="t38">
                                    <option value="true" <?php echo isset($client['t38']) && $client['t38'] ? 'selected': ''; ?>>True</option>
                                    <option value="false" <?php echo isset($client['t38']) && !$client['t38'] ? 'selected': ''; ?>>False</option>
                                </select>
                            </tr>
                            <tr>
                                <td class="align_right"><?php __('Media Type')?> </td>
                                <td>
                                    <select name="media_type">
                                        <option value="2" <?php if ($client['media_type'] == 2) echo 'selected="selected"' ?>><?php __('Bypass Media')?></option>
                                        <option value="1" <?php if ($client['media_type'] == 1) echo 'selected="selected"' ?>><?php __('Proxy Media')?></option>
                                        <option value="0" <?php if ($client['media_type'] == 0) echo 'selected="selected"' ?>><?php __('Transcoding media')?></option>
                                    </select>
                                </td>
                            </tr>
<!--                            <tr>
                                <td class="align_right"><?php __('T.38')?> </td>
                                <td>
                                    <input type="checkbox" name="t_38" <?php if ($client['t_38']) echo 'checked="checked"' ?>>
                                </td>
                            </tr>
                            <tr>
                                <td class="align_right"><?php __('RFC 2833')?> </td>
                                <td>
                                    <input type="checkbox" name="rfc2833" <?php if ($client['rfc2833']) echo 'checked="checked"' ?>>
                                </td>
                            </tr>-->
                            <tr>
                                <td class="align_right"><?php __('Auto Invoicing')?> </td>
                                <td>
                                    <input type="checkbox" name="auto_invoicing" <?php if ($client['auto_invoicing']) echo 'checked="checked"' ?>>
                                </td>
                            </tr>


<!--                            <tr>-->
<!--                                <td class="align_right">--><?php //__('Carrier Self-Service Portal Permission') ?><!-- </td>-->
<!--                                <td class="value">-->
<!--                                    --><?php //echo $this->element('portal/add_permission_div',array('have_did' => 1,'check_data' => $client_permission)); ?>
<!--                                </td>-->
<!--                            </tr>-->

<!--                            <tr>-->
<!--                                <td class="right">--><?php //__('DID Search'); ?><!--:</td>-->
<!--                                <td>-->
<!--                                    <input type="text" id="did_search_input" class="validate[custom[integer]]" />-->
<!--                                    <i class="icon-spinner icon-spin icon-large hide" id="loading_i"></i>-->
<!--                                    <a href="javascript:void(0)" id="did_search" title="search"><i class="icon-search"></i></a>-->
<!--                                </td>-->
<!--                            </tr>-->
<!--                            <tr>-->
<!--                                <td class="right">--><?php //__('DID'); ?><!--:</td>-->
<!--                                <td>-->
<!--                                    <select multiple id="pre-selected-options" class="multiselect validate[required]" name="did_assign[]">-->
<!--                                    </select>-->
<!--                                </td>-->
<!--                            </tr>-->

                            <tr style="text-align:center;">
                                <td colspan="5" class="button-groups center input in-submit">
                                    <input type="submit" id="subbtn" class="btn btn-primary" value="<?php __('Submit')?>">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </form>
            </div>
        </div>
    </div>
</div>
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
<script>
//    function clearPassword(){
//        $('input[name="login_password"]').attr('type','text');
//    }


    $(function() {
        var $add_ip = $('#add_ip');
        var $ip_delete = $('.ip_delete');
        var $myform = $('#myform');
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

        $myform.submit(function() {
            // check if exists client name
            var name = $vendor_name.val();
            if (!name)
            {
                jQuery.jGrowlError("Name can not be empty!");
                return false;
            }
            var present_vendor_name = $("#present_vendor_name").val();
            if (present_vendor_name !== name)
            {
                var name_data = jQuery.ajaxData("<?php echo $this->webroot; ?>clients/check_name/" + name);
                name_data = name_data.replace(/\n|\r|\t/g, "");
                if (name_data == 'false') {
                    jQuery.jGrowlError(name + " is already in use!");
                    return false;
                }
            }
            return true;
        });

        jQuery('#mode').val("<?php echo $client['mode'] ?>");


        jQuery('#mode').change(function() {
            if (jQuery(this).val() == '2') {
                jQuery('#allowed_credit').parent().parent().show();
                jQuery('#unlimited_panel').show();
                $('#credit_text').text('Allowed Credit');
            } else {
                $('#credit_text').closest('tr').hide();
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

        <?php if($client['mode'] == 2 && $client['unlimited_credit'] == 1):?>
        jQuery("#unlimited_credit").click();
        <?php endif; ?>

        jQuery("#allowed_credit").val("<?php echo number_format(abs($client['allowed_credit']), 5); ?>");





        //setTimeout('clearPassword();',500);
    });
</script>



