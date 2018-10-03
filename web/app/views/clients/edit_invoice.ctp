<style>
    input{width: 220px;}
</style>
<ul class="breadcrumb">
    <li><?php __('You are here')?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>pr/pr_invoices/carrier_invoice">
        <?php __('Finance') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>pr/pr_invoices/carrier_invoice">
        <?php echo __('Auto Invoice Management') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>pr/pr_invoices/carrier_invoice">
        <?php echo __('Edit Invoice') ?></a></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading">Edit Invoice</h4>

</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
    <a class="btn btn-icon btn-inverse glyphicons circle_arrow_left" href="<?php echo $this->webroot; ?>pr/pr_invoices/carrier_invoice">
        <i></i>
        Back
    </a>
</div>
<div class="clearfix"></div>
<div class="innerLR">
     <div class="widget" >
         <div class="widget-body">
            <?php echo $form->create('Client', array('action' => "edit_invoice/{$this->params['pass'][0]}", 'id' => 'ClientEdit')); ?>
             <legend>
                 <?php
                 empty($post['Client']['auto_invoicing']) ? $au = 'false' : $au = 'checked';
                 echo $form->checkbox('auto_invoicing', array('id' => 'ClientAutoInvoicing', 'checked' => $au))
                 ?>
                 <span for="autoinvoice_enabled"> <?php echo __('Auto Invoice') ?></span>
             </legend>
             <table class="form footable table table-striped  tableTools table-bordered  table-white table-primary default footable-loaded">
                 <thead></thead>
                 <tr>
                     <td class="align_right padding-r20"><?php echo __('paymentterm') ?> </td>
                     <td>
                         <?php echo $form->input('payment_term_id', array('options' => $paymentTerm, 'label' => false, 'div' => false, 'type' => 'select', 'selected' => $post['Client']['payment_term_id']));
                         ?>
                     </td>
                     <td class="align_right padding-r20"><?php echo __('Invoice Format') ?> </td>
                     <td>
                         <?php
                         $route_type = array('1' => 'PDF', '2' => 'Word');
                         echo $form->input('invoice_format', array('options' => $route_type, 'label' => false, 'div' => false, 'type' => 'select', 'selected' => $post['Client']['invoice_format']));
                         ?>
                     </td>
                 </tr>
                 <tr>
                     <td class="align_right padding-r20"><?php echo __('Time zone') ?> </td>
                     <td>
                         <?php
                         $zone_arr = array();
                         for ($i = -12; $i <= 12; $i++)
                         {
                             $zone_str = $i < 0 ? sprintf("-%02d:00", 0 - $i) : sprintf("+%02d:00", $i);
                             $zone_arr[$zone_str] = 'GMT ' . $zone_str;
                         }
                         echo $form->input('invoice_zone', array('options' => $zone_arr, 'label' => false, 'div' => false, 'type' => 'select', 'selected' => empty($post['Client']['invoice_zone']) ? '+00:00' : $post['Client']['invoice_zone']));
                         ?>
                     </td>
                 </tr>
                 <tr>
                     <td class="align_right padding-r20"><?php echo __('No Invoice for Zero Traffic') ?> </td>
                     <td>
                         <?php
                         echo $form->input('invoice_zero', array('options' => array(1 => 'Yes', 0 => 'No'), 'label' => false, 'div' => false, 'type' => 'select', 'selected' => $post['Client']['invoice_zero'] ? 1 : 0));
                         ?>
                     </td>
                     <td class="align_right padding-r20"><?php echo __('CDR Compress Format') ?> </td>
                     <td>
                         <?php
                         $route_type = array('3' => 'zip', '4' => 'tar.gz');
                         echo $form->input('cdr_list_format', array('options' => $route_type, 'label' => false, 'div' => false, 'type' => 'select', 'selected' => $post['Client']['cdr_list_format']));
                         ?>
                     </td>
                 </tr>
                 <tr>
                     <td class="align_right padding-r20"><?php __('Rate Decimal Place'); ?> </td>
                     <td>
                         <?php
                         $decimal_places = range(1, 10);
                         $decimal_places = array_combine($decimal_places, $decimal_places);
                         ?>
                         <?php
                         echo $form->input('decimal_place', array('options' => $decimal_places,
                             'style' => '', 'value' => $post['Client']['decimal_place'], 'label' => false, 'div' => false))
                         ?>
                     </td>
                     <td class="align_right padding-r20"><?php __('Auto Send Invoice'); ?> </td>
                     <td>
                         <?php empty($post['Client']['email_invoice']) ? $au = 'false' : $au = 'checked'; ?>
                         <?php echo $form->checkbox('email_invoice', array('checked' => $au)); ?>
                     </td>
                 </tr>
                 <tr>
                     <td class="align_right padding-r20"><?php __('Send invoice as link in email'); ?> </td>
                     <td>
                         <?php empty($post['Client']['is_send_as_link']) ? $au = 'false' : $au = 'checked'; ?>
                         <?php echo $form->checkbox('is_send_as_link', array('checked' => $au)); ?>
                     </td>
                     <td class="align_right padding-r20"><?php  ?> </td>
                     <td>
                     </td>
                 </tr>
                 <tr>
                     <td class="align_right padding-r20"><?php __('Include Account Detail'); ?> </td>
                     <td>
                         <?php empty($post['Client']['is_invoice_account_summary']) ? $au = 'false' : $au = 'checked'; ?>
                         <?php echo $form->checkbox('is_invoice_account_summary', array('checked' => $au)); ?>
                         <?php
                         echo $form->input('invoice_use_balance_type', array('options' => array(0 => 'use actual balance', 1 => 'use mutual balance'), 'label' => false, 'div' => false, 'type' => 'select', 'selected' => $post['Client']['invoice_use_balance_type'], 'style' => 'width:120px;'));
                         ?>
                     </td>
                     <td class="align_right padding-r20"><?php __('Show Daily Usage'); ?> </td>
                     <td>
                         <?php empty($post['Client']['is_show_daily_usage']) ? $au = 'false' : $au = 'checked'; ?>
                         <?php echo $form->checkbox('is_show_daily_usage', array('checked' => $au)); ?>
                     </td>
                 </tr>
                 <tr>
                     <td class="align_right padding-r20"><?php __('Short Duration Call Surcharge detail'); ?> </td>
                     <td>
                         <?php empty($post['Client']['is_short_duration_call_surcharge_detail']) ? $au = 'false' : $au = 'checked'; ?>
                         <?php echo $form->checkbox('is_short_duration_call_surcharge_detail', array('checked' => $au)); ?>
                     </td>
                     <td class="align_right padding-r20"><?php __('Include Summary of Payments'); ?> </td>
                     <td>
                         <?php empty($post['Client']['invoice_include_payment']) ? $au = 'false' : $au = 'checked'; ?>
                         <?php echo $form->checkbox('invoice_include_payment', array('checked' => $au)); ?>
                     </td>
                 </tr>
                 <tr>
                     <td class="align_right padding-r20"><?php __('Show Detail by Trunk'); ?></td>
                     <td>
                         <?php empty($post['Client']['is_show_detail_trunk']) ? $au = 'false' : $au = 'checked'; ?>
                         <?php echo $form->checkbox('is_show_detail_trunk', array('checked' => $au)); ?>
                     </td>
                     <td class="align_right padding-r20"><?php __('Show Total by Trunk'); ?></td>
                     <td>
                         <?php empty($post['Client']['is_show_total_trunk']) ? $au = 'false' : $au = 'checked'; ?>
                         <?php echo $form->checkbox('is_show_total_trunk', array('checked' => $au)); ?>
                     </td>
                 </tr>
                 <tr>
                     <td class="align_right padding-r20"><?php __('Show Code Summary (top 10)'); ?></td>
                     <td>
                         <?php empty($post['Client']['is_show_code_100']) ? $au = 'false' : $au = 'checked'; ?>
                         <?php echo $form->checkbox('is_show_code_100', array('checked' => $au)); ?>
                     </td>
                     <td class="align_right padding-r20"><?php __('Show Traffic Analysis by Code Name'); ?></td>
                     <td>
                         <?php empty($post['Client']['is_show_code_name']) ? $au = 'false' : $au = 'checked'; ?>
                         <?php echo $form->checkbox('is_show_code_name', array('checked' => $au)); ?>
                     </td>
                 </tr>
                 <tr>
                     <td class="align_right padding-r20"><?php __('Show Traffic Analysis by Country'); ?></td>
                     <td>
                         <?php empty($post['Client']['is_show_country']) ? $au = 'false' : $au = 'checked'; ?>
                         <?php echo $form->checkbox('is_show_country', array('checked' => $au)); ?>
                     </td>
                     <td class="align_right padding-r20"><?php __('Show Calls By Date Summary'); ?></td>
                     <td>
                         <?php empty($post['Client']['is_show_by_date']) ? $au = 'false' : $au = 'checked'; ?>
                         <?php echo $form->checkbox('is_show_by_date', array('checked' => $au)); ?>
                     </td>
                 </tr>
                 <tr>
                     <td class="align_right padding-r20">
                         <?php echo __('Include CDR link in email') ?>
                     </td>
                     <td>
                         <?php
                         empty($post['Client']['attach_cdrs_list']) ? $au = 'false' : $au = 'checked';
                         echo $form->checkbox('attach_cdrs_list', array('checked' => $au))
                         ?>
                     </td>
                     <td class="align_right padding-r20">
                         <?php echo __('Show Detail by Code Name') ?>
                     </td>
                     <td>
                         <?php
                         empty($post['Client']['invoice_show_details']) ? $au = 'false' : $au = 'checked';
                         echo $form->checkbox('invoice_show_details', array('checked' => $au));
                         ?>
                     </td>
                 </tr>
                 <tr>
                     <td class="align_right padding-r20">
                         <?php echo __('Show Jurisdictional Detail') ?>
                     </td>
                     <td>
                         <?php
                         empty($post['Client']['invoice_jurisdictional_detail']) ? $au = 'false' : $au = 'checked';
                         echo $form->checkbox('invoice_jurisdictional_detail', array('checked' => $au))
                         ?>
                     </td>
                     <td class="align_right padding-r20"><?php __('Break Down by Rate Table'); ?> </td>
                     <td>
                         <?php
                         if (empty($post['Client']['is_breakdown_by_rate_table']))
                         {
                             $au = 'false';
                             $select_display = "none";
                         }
                         else
                         {
                             $au = 'checked';
                             $select_display = "block";
                         }
                         ?>
                         <?php echo $form->checkbox('is_breakdown_by_rate_table', array('checked' => $au)); ?>
                         <?php
                         echo $form->input('breakdown_by_rate_table', array('options' => array(1 => 'Breakdown A-Z Rate Table by Destination', 2 => 'Breakdown US Rate Table by Jurisdiction'), 'label' => false, 'div' => false, 'type' => 'select', 'selected' => $post['Client']['breakdown_by_rate_table'], 'style' => 'width:120px;display:' . $select_display . ';'));
                         ?>
                     </td>
                 </tr>
                 <!--tr>
                     <td style="display: none;" class="align_right padding-r20"><?php __('Require Vendor Invoice'); ?> </td>
                     <td>
                         <?php
                         if (empty($post['Client']['is_vendor_invoice']))
                         {
                             $au = 'false';
                             $select_display = "none";
                         }
                         else
                         {
                             $au = 'checked';
                             $select_display = "block";
                         }
                         ?>
                         <?php echo $form->checkbox('is_vendor_invoice', array('checked' => $au)); ?>
                     </td>
                     <td class="align_right padding-r20"><?php __('Vendor'); ?>&nbsp;<?php echo __('paymentterm') ?> </td>
                     <td>
                         <?php echo $form->input('vendor_payment_term_id', array('options' => $paymentTerm, 'label' => false,
                             'div' => false, 'type' => 'select', 'selected' => $post['Client']['vendor_payment_term_id']));
                         ?>
                     </td>
                 </tr-->
                 <tr>
                     <td colspan="4" class="center">
                         <input type="hidden" value="<?php echo $post['Client']['client_id']; ?>" name="data[client_id]" />
                         <input type="submit" value="Submit" class="input in-submit btn btn-primary">
                         <input class="input in-button btn btn-default" type="reset" style="margin-left: 20px;" value="Revert">
                     </td>
                 </tr>
             </table>
            <?php echo $form->end(); ?>
         </div>
     </div>
</div>



<script type="text/javascript">
    //特殊表单验证（只能为数字（Float））
    jQuery(document).ready(
        function () {

            jQuery("#ClientAutoInvoicing").click(function () {
                var checked = jQuery(this).attr('checked');
                var parent_is_close = jQuery(this).parent().parent().parent().attr('data-collapse-closed');
                if (checked == "checked" && parent_is_close == "true")
                {
                    jQuery(this).parent().next().click();
                }
                else if (checked != "checked" && parent_is_close == 'false')
                {
                    jQuery(this).parent().next().click();
                }
            });
            jQuery("#ClientIsPanelaccess").click(function () {
                var checked = jQuery(this).attr('checked');
                var parent_is_close = jQuery(this).parent().parent().parent().attr('data-collapse-closed');
                if (checked == "checked" && parent_is_close == "true")
                {
                    jQuery(this).parent().next().click();
                }
                else if (checked != "checked" && parent_is_close == 'false')
                {
                    jQuery(this).parent().next().click();
                }
            });
            jQuery("#ClientIsDailyBalanceNotification").click(function () {
                var checked = jQuery(this).attr('checked');
                var parent_is_close = jQuery(this).parent().parent().parent().attr('data-collapse-closed');
                if (checked == "checked" && parent_is_close == "true")
                {
                    jQuery(this).parent().next().click();
                }
                else if (checked != "checked" && parent_is_close == 'false')
                {
                    jQuery(this).parent().next().click();
                }
            });

            jQuery('#ClientAllowedCredit,#ClientNotifyAdminBalance').xkeyvalidate({type: 'Ip'});
            //jQuery('#ClientProfitMargin').xkeyvalidate({type:'Num'})
            //	jQuery('#ClientEmail,#ClientNocEmail,#ClientBillingEmail,#ClientRateEmail').xkeyvalidate({type:'Email'});
            jQuery('#ClientName,#ClientCompany').xkeyvalidate({type: 'strNum'});
            jQuery('#ClientTaxId').xkeyvalidate({type: 'Num'});
            if ($('#ClientIsDailyBalanceNotification').attr('checked')) {
                $('#is_daily_balance_panel').show();
                $("#ClientNotifyClientBalance").removeAttr('disabled');
            } else {
                $("#ClientNotifyClientBalance").attr('disabled', 'disabled');
                $('#is_daily_balance_panel').hide();
            }
            $("#ClientIsBreakdownByRateTable").click(function () {
                if ($(this).attr('checked') == 'checked')
                {
                    $("#ClientBreakdownByRateTable").show();
                }
                else
                {
                    $("#ClientBreakdownByRateTable").hide();
                }
            });
            //jQuery('#ClientIsDailyBalanceNotification').disabled({id: '#ClientNotifyClientBalance,#ClientNotifyAdminBalance'});
        }
    );
</script>
<script type="text/javascript">
    jQuery(document).ready(function () {

        jQuery('#ClientPaymentTermId').change(function () {
            var $this = $(this);
            var payment_term_id = $(this).val();
            $.ajax({
                'url': '<?php echo $this->webroot ?>clients/get_payment_term_type',
                'type': 'POST',
                'dataType': 'json',
                'data': {'payment_term_id': payment_term_id},
                'success': function (data) {
                    if (!!data[0] && data[0][0]['type'] == 1) {
                        $this.parent().parent().next().show();
                    } else {
                        $this.parent().parent().next().hide();
                    }
                }
            });
        }).trigger('change');


        jQuery('#ClientIsDailyBalanceNotification').click(function () {
            if ($(this).attr('checked')) {
                $('#is_daily_balance_panel').show();
                $("#ClientNotifyClientBalance").removeAttr('disabled');
            } else {
                $("#ClientNotifyClientBalance").attr('disabled', 'disabled');
                $('#is_daily_balance_panel').hide();
            }
        });

        check_welcom = false;

        jQuery('#ClientEdit').submit(function () {
            te = true;
            if (!$('#dd').length) {
                $(document.body).append("<div id='dd'></div>");
            }
            var $dd = $('#dd');
            if ($("#ClientAutoInvoicing").attr('checked'))
            {
                var data = jQuery.ajaxData({
                    'url': "<?php echo $this->webroot ?>mailtmps/ajax_judge_invoice_mailtmp",
                    'type': 'POST',
                    'dataType': 'json',
                });
                if (data.flg == 1) //invoice mailtmp信息不全
                {
                    jGrowl_to_notyfy('If you do not fill complete mail content will cause automatic emailing of invoice to fail', {theme: 'jmsg-error'});
                    $dd.load('<?php echo $this->webroot ?>mailtmps/ajax_get_invoice_mailtmp',
                        {},
                        function (responseText, textStatus, XMLHttpRequest) {
                            $dd.dialog({
                                'width': '850px',
                                buttons: [{
                                    text: 'Submit',
                                    class: 'btn btn-primary',
                                    click: function () {
                                        $.ajax({
                                            url: "<?php echo $this->webroot ?>mailtmps/ajax_save_invoice_mailtmp",
                                            type: 'post',
                                            dataType: 'text',
                                            data: $('#post_invoice').serialize(),
                                            success: function (data) {
                                                if (data == 1)
                                                {
                                                    jGrowl_to_notyfy('save failed!', {theme: 'jmsg-error'});
                                                }
                                                else
                                                {
                                                    jGrowl_to_notyfy('save succeed!', {theme: 'jmsg-success'});
                                                }
                                                $dd.dialog("close");
                                            }
                                        });
                                    }
                                }]
                            });
                        }
                    );
                    te = false;
                }
            }

            return te;
        });

        $('#ClientIsInvoiceAccountSummary').change(function () {
            if ($(this).is(':checked')) {
                $('#ClientInvoiceUseBalanceType').show();
            } else {
                $('#ClientInvoiceUseBalanceType').hide();
            }
        }).trigger("change");

        jQuery('#ClientIncludeTax').change(function () {
            if (jQuery(this).attr('checked'))
            {
                $('#tax_area').show();
            }
            else
            {
                $('#tax_area').hide();
            }
        });

    });
</script>