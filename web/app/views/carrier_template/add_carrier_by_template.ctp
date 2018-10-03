<ul class="breadcrumb">
    <li>You are here</li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Management') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Client') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Create New By Template') ?></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php __('Client') ?></h4>

</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
    <a class="btn btn-icon btn-inverse glyphicons circle_arrow_left"
       href="<?php echo $this->webroot ?>clients/add"><i></i> <?php __('Back') ?></a>
</div>
<div class="clearfix"></div>
<style type="text/css">
    .form .value, .list-form .value {
        text-align: left;
    }

    input {
        width: 220px;
    }

    fieldset {
        border: 1px solid #eee;
        padding: 10px;
    }

    .bodright20 {
        margin-right: 20px;
    }

    table {
        width: 100%;
    }

    .pull-right {
        text-align: center;
    }
</style>
<div class="innerLR">

    <div class="widget widget-heading-simple widget-body-white">
        <div class="widget-body">

            <?php echo $form->create('Client', array('action' => 'add', 'url' => '/carrier_template/add_carrier_by_template/', 'id' => 'ClientForm')); ?>
            <div class="widget">
                <div class="widget-head"><h4 class="heading"><?php __('Basic Info') ?></h4></div>
                <div class="widget-body">
                    <table
                        class="form footable table table-striped  tableTools table-bordered  table-white table-primary default footable-loaded">
                        <tr>
                            <td class="align_right padding-r20" colspan="2"
                                width="50%"><?php echo __('Carrier Template') ?> </td>
                            <td colspan="2">
                                <?php echo $form->input('template_id', array('options' => $template_arr, 'label' => false, 'div' => false, 'type' => 'select', 'class' => 'input in-text in-select')) ?>
                            </td>

                        </tr>
                        <tr>
                            <td class="align_right padding-r20" colspan="2"><?php echo __('Carrier Name') ?> </td>
                            <td colspan="2">
                                <?php echo $form->input('name', array('label' => false, 'div' => false, 'type' => 'text', 'maxLength' => '500', 'class' => 'validate[required,custom[onlyLetterNumberLineSpace],funcCall[notEqualAdmin]]')) ?>
                            </td>

                        </tr>
                        <tr>
                            <td class="align_right padding-r20" colspan="2"><?php echo __('status') ?> </td>
                            <td colspan="2">
                                <?php
                                $st = array('true' => __('Active', true), 'false' => __('Inactive', true));
                                echo $form->input('status', array('options' => $st, 'label' => false, 'div' => false, 'type' => 'select', 'class' => 'input in-text in-select'))
                                ?>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="widget" data-toggle="collapse-widget" data-collapse-closed="true">
                <div class="widget-head"><h4 class="heading"><?php __('Company Info') ?></h4></div>
                <div class="widget-body">
                    <table
                        class="form footable table table-striped  tableTools table-bordered  table-white table-primary default footable-loaded">

                        <tr>
                            <td class="align_right padding-r20"><?php echo __('Company Name') ?> </td>
                            <td>
                                <?php echo $form->input('company', array('maxlength' => 256, 'label' => false, 'div' => false, 'class' => 'input in-text in-input validate[maxSize[200],custom[onlyLetterNumberLineSpace]]')) ?>
                            </td>
                            <td class="align_right padding-r20"><?php echo __('address') ?> </td>
                            <td>
                                <?php echo $form->input('address', array('label' => false, 'div' => false, 'rows' => '5', 'maxlength' => '500', 'type' => 'textarea', 'class' => 'input in-text in-input')) ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="align_right padding-r20"><?php echo __('Main e-mail', true); ?> </td>
                            <td> <?php echo $form->input('email', array('label' => false, 'div' => false, 'maxLength' => '100', 'class' => 'validate[custom[email]]')) ?></td>
                            <td class="align_right padding-r20"><?php echo __('NOC e-mail', true); ?> </td>

                            <td> <?php echo $form->input('noc_email', array('label' => false, 'div' => false, 'maxLength' => '100', 'class' => 'validate[custom[email]]')) ?></td>
                        </tr>
                        <tr>
                            <td class="align_right padding-r20"><?php echo __('Billing e-mail', true); ?> </td>
                            <td> <?php echo $form->input('billing_email', array('label' => false, 'div' => false, 'maxLength' => '100', 'class' => 'validate[custom[email]]')) ?></td>
                            <td class="align_right padding-r20"><?php echo __('Rates e-mail', true); ?> </td>
                            <td> <?php echo $form->input('rate_email', array('label' => false, 'div' => false, 'maxLength' => '100', 'class' => 'validate[custom[email]]')) ?></td>
                        </tr>
                        <tr>
                            <td class="align_right padding-r20"><?php echo __('Rate Delivery Email', true); ?> </td>
                            <td> <?php echo $form->input('rate_delivery_email', array('label' => false, 'div' => false, 'maxLength' => '100', 'class' => 'validate[custom[email]]')) ?></td>
                            <td class="align_right padding-r20"><?php echo __('Tax ID', true); ?> </td>
                            <td> <?php echo $form->input('tax_id', array('label' => false, 'div' => false, 'class' => 'validate[custom[phone]]')) ?></td>
                        </tr>
                        <tr>
                            <td class="align_right padding-r20"><?php echo __('Account Details', true); ?> </td>
                            <td> <?php echo $form->input('details', array('label' => false, 'div' => false, 'rows' => '5', 'class' => 'input in-text in-input')) ?></td>
                        </tr>


                    </table>
                </div>
            </div>

            <div class="widget" data-toggle="collapse-widget" data-collapse-closed="true">
                <div class="widget-head"><h4 class="heading">
                        <?php echo $form->checkbox('is_panelaccess'); ?>
                        <?php __('Carrier Self-Service Portal') ?>
                    </h4>
                    <input type="hidden" name="is_send_welcom_letter" id="is_send_welcom_letter" value=""/>
                </div>
                <div class="widget-body">

                    <table
                        class="form footable table table-striped  tableTools table-bordered  table-white table-primary default footable-loaded">
                        <tr>
                            <td class="align_right padding-r20"><?php echo __('User Name', true) ?> </td>
                            <td>
                                <?php echo $form->input('login', array('label' => false, 'div' => false, 'class' => 'validate[required,custom[onlyLetterNumberLine],custom[HeadLetterNumberLine],funcCall[notEqualAdmin]]', 'type' => 'text', 'maxLength' => '256')) ?>
                            </td>
                            <td class="align_right padding-r20"><?php echo __('New Password', true) ?> </td>
                            <td> <?php echo $form->input('password', array('label' => false, 'div' => false, 'type' => 'password', 'maxLength' => '16', 'class' => 'validate[required,custom[onlyLetterNumber],minSize[6]]')); ?></td>
                        </tr>
                        <tr>
                            <td class="align_right padding-r20"><?php echo __('Permission') ?> </td>
                            <td class="value">
                                <?php echo $this->element('portal/add_permission_div'); ?>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <div id="form_footer" class="bottom-buttons">
                <div style="margin: 0;float:none;" class="buttons pull-right">
                    <input type="submit" class="btn btn-primary" value="<?php __('Save') ?>">
                    <input type="reset"  class="btn btn-default" value="<?php __('Revert') ?>">
                </div>
                <div class="clearfix"></div>
            </div>
            <?php echo $form->end(); ?>
        </div>
    </div>
</div>


<script type="text/javascript">
    function notEqualAdmin(field, rules, i, options)
    {
        if (field.val() == "admin") {
            // this allows the use of i18 for the error msgs
            return 'This field can not be admin!';
        }
    }



    $(function(){
        checked_welcom = false;

        $('#ClientIsPanelaccess').change(function() {
            if (!jQuery('#ClientIsPanelaccess').attr('checked')) {
                jQuery('#ClientLogin,#ClientPassword').attr('disabled', true).val('');
            } else {
                jQuery('#ClientLogin,#ClientPassword').attr('disabled', false);
            }
            if (!jQuery('#ClientIsPanelaccess').attr('checked')) {
                jQuery('#ClientIsPanelAccountsummary').attr({'disabled': true});
                jQuery('#ClientIsPanelRatetable').attr({'disabled': true});
                jQuery('#ClientIsPanelTrunks').attr({'disabled': true});
                jQuery('#ClientIsPanelProducts').attr({'disabled': true});
                jQuery('#ClientIsPanelBalance').attr({'disabled': true});
                jQuery('#ClientIsPanelPaymenthistory').attr({'disabled': true});
                jQuery('#ClientIsPanelOnlinepayment').attr({'disabled': true});
                jQuery('#ClientIsPanelInvoices').attr({'disabled': true});
                jQuery('#ClientIsPanelCdrslist').attr({'disabled': true});
                jQuery('#ClientIsPanelSummaryreport').attr({'disabled': true});
                jQuery('#ClientIsPanelSippacket').attr({'disabled': true});
            } else {
                jQuery('#ClientIsPanelAccountsummary').attr({'disabled': false});
                jQuery('#ClientIsPanelRatetable').attr({'disabled': false});
                jQuery('#ClientIsPanelTrunks').attr({'disabled': false});
                jQuery('#ClientIsPanelProducts').attr({'disabled': false});
                jQuery('#ClientIsPanelBalance').attr({'disabled': false});
                jQuery('#ClientIsPanelPaymenthistory').attr({'disabled': false});
                jQuery('#ClientIsPanelOnlinepayment').attr({'disabled': false});
                jQuery('#ClientIsPanelInvoices').attr({'disabled': false});
                jQuery('#ClientIsPanelCdrslist').attr({'disabled': false});
                jQuery('#ClientIsPanelSummaryreport').attr({'disabled': false});
                jQuery('#ClientIsPanelSippacket').attr({'disabled': false});

            }
        }).trigger('change');
        $('#ClientForm').submit(function(){
            if(!$('#ClientForm').validationEngine('validate')){
                $("[class='widget-body collapse']").parent().find('span.collapse-toggle').click();
                return false;
            }

            te = true;

            if(te && !checked_welcom){
                if(jQuery('#ClientIsPanelaccess').attr('checked')){
                    var login = jQuery('#ClientLogin').val();
                    te = false;
                    bootbox.confirm('Send welcom letter to the user ['+login+'] ?', function(result) {
                        if(result) {
                            $('#is_send_welcom_letter').val('1');
                            checked_welcom = true;
                            jQuery('#ClientForm').submit();
                        }else{
                            $('#is_send_welcom_letter').val('');
                            checked_welcom = true;
                            jQuery('#ClientForm').submit();
                        }

                    });

                }
            }

            return te;
        })
    })
</script>
<script type="text/javascript">
    $(function(){
        $('span.collapse-toggle').live("click",function() {
            $(this).toggle(
                function () {
                    $(this).parent().next().css('overflow', 'visible');
                },
                function () {
                    $(this).parent().next().css('overflow', 'hidden');
                }
            ).trigger('click');
        })
    });
</script>