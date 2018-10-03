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
            <td class="right"><?php __('Enable Portal Limit Per DID') ?></td>
            <td>
                <select name="enablePortLimit" id="enablePortLimit">
                    <option value="0">No</option>
                    <option value="1">Yes</option>
                </select>
            </td>
        </tr>
        <tr id="portLimit" style="display: none;">
            <td class="align_right"><?php __('Call Limit') ?> </td>
            <td>
                <input class="validate[custom[integer], min[1], max[1000]]" type="text" name="call_limit">
            </td>
        </tr>
        <tr>
            <td class="right"><?php __('Charge / Port / Month') ?></td>
            <td>
                <input type="text" name="price_per_max_channel" class="validate[custom[number]]">
            </td>
        </tr>
        <tr>
            <td class="right"><?php __('Multi-Host Routing Strategy') ?></td>
            <td>
                <select name="res_strategy" id="">
                    <option value="1">Top-Down</option>
                    <option value="2">Round-Robin</option>
                </select>
            </td>
        </tr>
        <tr>
            <td class="right"><?php __('RPID') ?></td>
            <td>
                <select name="rpid" id="">
                    <option value="0">False</option>
                    <option value="1">True</option>
                </select>
            </td>
        </tr>
        <tr>
            <td class="right"><?php __('PAID') ?></td>
            <td>
                <select name="paid" id="">
                    <option value="0">False</option>
                    <option value="1">True</option>
                </select>
            </td>
        </tr>
        <tr>
            <td class="right"><?php __('Tech Prefix') ?></td>
            <td>
                <input type="text" name="tech_prefix" class="validate[custom[number]]">
            </td>
        </tr>
        <tr>
            <td class="right"><?php __('IP Addresses') ?></td>
            <td>
                <input type="text" id="ip_orig" name="ip_addresses[]" class="validate[custom[ip_hostname]]">/
                <select name="ip_mask[]" style="width: 60px;">
                    <?php for($i = 32; $i >= 24; $i--): ?>
                        <option value="<?php echo $i ?>"><?php echo $i ?></option>
                    <?php endfor; ?>
                </select>
                <input type="text" name="ip_port[]"  class="width40 validate[custom[integer]]" maxlength="5" >
                <a href="javascript:void(0)" id="add_ip">
                    <i class="icon-plus"></i>
                </a>
            </td>
        </tr>
        <tr style="display:none;">
            <td></td>
            <td>
                <input type="text" name="ip_addresses[]">/
                <select name="ip_mask[]" style="width: 60px;">
                    <?php for($i = 32; $i >= 24; $i--): ?>
                        <option value="<?php echo $i ?>"><?php echo $i ?></option>
                    <?php endfor; ?>
                </select>
                <input type="text" name="ip_port[]"  class="width40" maxlength="5" >
                <a href="javascript:void(0)" class="ip_delete">
                    <i class="icon-remove"></i>
                </a>
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
        <tr>
            <td class="align_right padding-r20"><?php echo __('Allowed Port Per DID') ?></td>
            <td>
                <input type="text" name="amount_per_port" class="validate[custom[integer]]">
            </td>
        </tr>
        <tr>
            <td class="align_right padding-r20"><?php echo __('Include Tax') ?></td>
            <td>
                <input type="checkbox" name="include_tax" id="includeTax">
            </td>
        </tr>
        <tr id="tax">
            <td class="align_right padding-r20"></td>
            <td>
                <div class="row margin-left-15">
                    <div class="inline-block">
                        <label for="">Tax name:</label>
                        <input type="text" name="tax_name[]">
                    </div>
                    <div class="inline-block">
                        <label for="">Value:</label>
                        <input type="text" name="tax_percent[]" class="validate[custom[number]]"> <span class="post-input-symbol">%</span>
                    </div>
                    <div class="inline-block margin-left-15">
                        <a id="addTax">
                            <i class="icon-plus"></i>
                        </a>
                    </div>
                </div>
                <div class="row margin-left-15" style="display: none;">
                    <div class="inline-block">
                        <label for="">Tax name:</label>
                        <input type="text" name="tax_name[]">
                    </div>
                    <div class="inline-block">
                        <label for="">Value:</label>
                        <input type="text" name="tax_percent[]" class="validate[custom[number]]"> <span class="post-input-symbol">%</span>
                    </div>
                    <div class="inline-block margin-left-15">
                        <a class="removeTax">
                            <i class="icon-remove"></i>
                        </a>
                    </div>
                </div>
            </td>
        </tr>
        <tr>
            <td class="align_right padding-r20"><?php echo __('Round Up') ?></td>
            <td>
                <select name="rate_rounding">
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                </select>
            </td>
        </tr>
        <tr>
            <td class="align_right"><?php __('Auto Invoicing') ?> </td>
            <td>
                <input type="checkbox" name="auto_invoicing">
            </td>
        </tr>
        <tr class="expandAutoInvoicing" id="paymentTermRow">
            <td class="align_right padding-r20"><?php echo __('Payment Term') ?></td>
            <td>
                <select name="payment_term_id" id="payment_term_id">
                    <option value=""> </option>
                    <?php foreach($payment_terms as $id =>$term): ?>
                        <option value="<?php echo $id;?>"><?php echo $term?></option>
                    <?php endforeach; ?>
            </td>
        </tr>
        <tr class="expandAutoInvoicing">
            <td class="align_right padding-r20"><?php echo __('No Invoice For Zero Traffic') ?></td>
            <td>
                <select name="invoice_zero" id="invoice_zero">
                    <option value="1" selected>Yes</option>
                    <option value="0">No</option>

            </td>
        </tr>
        <tr class="expandAutoInvoicing">
            <td></td>
            <td>
                <div>
                    <h4>Please select what need to include to invoice</h4>
                    <div class="select-block">
                        <div>
                            <input type="checkbox" name="did_invoice_include[]" value="account_summary"> Account Summary
                        </div>
                        <div>
                            <input type="checkbox" name="did_invoice_include[]" value="transaction_summary_analysis"> Transaction Summary Analysis
                        </div>
                        <div>
                            <input type="checkbox" name="did_invoice_include[]" value="auth_code_800_summary"> Authorization Code (800) Summary
                        </div>
                        <div>
                            <input type="checkbox" name="did_invoice_include[]" value="all_area_codes_summary"> All Area codes Summary
                        </div>
                        <div>
                            <input type="checkbox" name="did_invoice_include[]" value="orig_lata_summary"> Origination Lata Summary
                        </div>
                    </div>
                </div>
            </td>
        </tr>
        <tr class="expandAutoInvoicing">
            <td class="align_right padding-r20"><?php echo __('Send Auto Invoice') ?></td>
            <td>
                <input type="checkbox" name="email_invoice">
            </td>
        </tr>

        <?php if (!$is_ajax): ?>
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

<script>

    $('#includeTax').on('change', function () {
        let displayTax = $(this).is(':checked');

        if (displayTax) {
            $('#tax').show();
        } else {
            $('#tax').hide();
        }
    }).trigger('change');

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
        var $addTax = $('#addTax');
        var $removeTax = $('.removeTax');

        $add_ip.click(function() {
            var $this = $(this);
            var $parent = $this.parents('tr');
            var $clone = $parent.next().clone();
            $parent.next().after($clone);
            $clone.show();
        });

        $addTax.click(function() {
            var $this = $(this);
            var $parent = $this.parents('div.row');
            var $clone = $parent.next().clone();
            $parent.next().after($clone);
            $clone.show();
        });

        $ip_delete.live('click', function() {
            $(this).parents('tr').remove();
        });

        $removeTax.live('click', function() {
            $(this).parents('div.row').remove();
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
                $('#paymentTermRow').show();
                jQuery('#allowed_credit').parent().parent().show();
                jQuery('#unlimited_panel').show();
                $('#credit_text').text('Allowed Credit');
            } else {
                $('#paymentTermRow').hide();
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

        $('input[name="auto_invoicing"]').on('change', function(){
            if($(this).is(':checked')){
                $('tr.expandAutoInvoicing').show();
            }else{
                $('tr.expandAutoInvoicing').hide();
            }
            $('#mode').trigger('change');
        }).trigger('change');

        $("#enablePortLimit").change(function () {
            if ($(this).val() == 1) {
                $("#portLimit").show();
            } else {
                $("#portLimit").hide();
            }
        });

    });

</script>