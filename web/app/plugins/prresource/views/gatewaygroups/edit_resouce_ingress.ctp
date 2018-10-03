<style type="text/css">
    .form .label2{ width:40%;}
    .form tr{ height:30px; line-height:30px;}
    table input {width:100px;}
    .ui-dialog{border:1px solid #7FAF00;}
</style>
<?php echo $this->element("gatewaygroups_edit_resouce_ingress/js") ?>
<?php echo $this->element("gatewaygroups_edit_resouce_ingress/title") ?>
<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-head">
            <?php echo $this->element("ingress_tab", array('active_tab' => 'base')) ?>
        </div>
        <div class="widget-body">
            <?php echo $form->create('Gatewaygroup', array('id' => 'myform', 'action' => "edit_resouce_ingress/" . base64_encode($post['Gatewaygroup']['resource_id']), 'onsubmit' => 'return checkform();')); ?>
            <?php echo $form->input('back_url', array('label' => false,'div' => false, 'type' => 'hidden', 'value' => isset($back_url) ? $back_url : '')); ?>
            <?php echo $form->input('resource_id', array('id' => 'alias', 'label' => false, 'value' => $post['Gatewaygroup']['resource_id'], 'div' => false, 'type' => 'hidden', 'maxlength' => '8')); ?>
            <?php echo $form->input('ingress', array('label' => false, 'value' => 'true', 'div' => false, 'type' => 'hidden')); ?>
            <?php echo $form->input('egress', array('label' => false, 'value' => 'false', 'div' => false, 'type' => 'hidden')); ?>
            <input type="hidden" value="<?php echo $post['Gatewaygroup']['resource_id']; ?>" name="resource_id"/>
            <input type="hidden" value="<?php echo $post['Gatewaygroup']['resource_id']; ?>" name="inputRId"/>
            <?php echo $this->element("gatewaygroups_edit_resouce_ingress/fieldset1") ?>
            <fieldset style="display:none;"><legend><?php __('rateTable') ?></legend>

                <tr style="display:none;">
                    <td class="align_right padding-r10"><?php __('rateTable') ?></td>
                    <td>
                        <?php
                        echo $form->input('rate_table_id', array('options' => $rate_tables, 'selected' => $post['Gatewaygroup']['rate_table_id'], 'empty' => '  ', 'label' => false, 'class' => 'select', 'div' => false, 'type' => 'select'));
                        ?>
                    </td>
                </tr>

            </fieldset>
            <?php if ($$hel->isIngress())
            { ?>
                <fieldset style="display:none;"><legend><?php echo __('Routing Plan', true); ?>:</legend>

                    <tr style="display:none;">
                        <td class="align_right padding-r10"><?php echo __('Routing Plan', true); ?>:</td>
                        <td>

                            <?php
                            if (!isset($post))
                            {
                                $post = Array();
                            }
                            ?>
                            <?php echo $xform->input('route_strategy_id', Array('options' => $route_policy, 'empty' => '', 'selected' => array_keys_value($post, 'Gatewaygroup.route_strategy_id'))) ?>



                        </td>
                    </tr>

                </fieldset>
            <?php } ?>

            <?php echo $this->element("gatewaygroups_edit_resouce_ingress/fieldset2") ?>
            <?php echo $this->element("gatewaygroups_edit_resouce_ingress/fieldset3") ?>



            <?php if ($_SESSION['role_menu']['Routing']['gatewaygroups:view']['model_w'])
            { ?>	<div id="form_footer" class="buttons-group center">
                <input type="submit" class="btn btn-primary"  value="<?php echo __('submit') ?>" onclick="seleted_codes();" />

                <input type="reset"   value="<?php echo __('reset') ?>"   class="input in-submit btn btn-default"/>
                </div><?php } ?>
            <?php echo $form->end(); ?>
        </div>
    </div>
</div>
<!-----------Add Rate Table----------->
<div id="pop-div" class="pop-div" style="display:none;">
    <div class="pop-thead">
        <span></span>
        <span class="float_right"><a href="javascript:closeDiv('pop-div')" id="pop-close" class="pop-close">&nbsp;</a></span>
    </div>
    <div class="pop-content" id="pop-content"></div>
</div>
<div id="pop-clarity" class="pop-clarity" style="display:none;"></div>
</div>


<script type="text/javascript" src="<?php echo $this->webroot ?>js/gateway.js"></script>
<script type="text/javascript">
    jQuery(document).ready(function() {
        jQuery('#totalCall,#totalCPS').xkeyvalidate({type: 'Num'});
        var did_billing_method_tr = $('#did_billing_method_tr');
        var did_rate_table_tr = $('#did_rate_table_tr');
        var did_amount_per_port_tr = $('#did_amount_per_port_tr');


        jQuery('#GatewaygroupBillingMethod').change(function() {
            if ($(this).val() == '0')
            {
                did_rate_table_tr.show();
                did_amount_per_port_tr.hide();
            }
            else
            {
                did_rate_table_tr.hide();
                did_amount_per_port_tr.show();
            }
            if (jQuery('#GatewaygroupTrunkType2').val() == '1')
            {
                did_rate_table_tr.hide();
            }
        });


        jQuery('#GatewaygroupTrunkType2').change(function() {
            if ($(this).val() == '0')
            {
                did_billing_method_tr.hide();
                did_rate_table_tr.hide();
                $('#did_amount_per_port_tr').hide();
                $('#add_resource_prefix').show();
                $('#resource_table').show();
            }
            else
            {
                did_billing_method_tr.show();
                jQuery('#GatewaygroupBillingMethod').trigger('change');
                //did_rate_table_tr.show();
                //$('#did_amount_per_port_tr').show();
                $('#add_resource_prefix').hide();
                $('#resource_table').hide();
            }
        }).trigger('change');


        var $transaction_fee_panel = $('#transaction_fee_panel');

        $('#GatewaygroupTrunkType').change(function() {
            if ($(this).val() == 2)
            {
                $transaction_fee_panel.show();
            }
            else
            {
                $transaction_fee_panel.hide();
            }
        }).trigger('change');

        /*
         jQuery('#GatewaygroupLnp').click(function(){
         if(jQuery('#GatewaygroupLrnBlock').attr('checked')&&jQuery('#GatewaygroupLnp').attr('checked')){
         jQuery('#GatewaygroupDnisOnly').attr('checked',false);
         jQuery('#GatewaygroupDnisOnly').attr('disabled',true);
         }else{
         jQuery('#GatewaygroupDnisOnly').attr('disabled',false);
         }
         });
         jQuery('#GatewaygroupLrnBlock').click(function(){
         if(jQuery('#GatewaygroupLrnBlock').attr('checked')&&jQuery('#GatewaygroupLnp').attr('checked')){
         jQuery('#GatewaygroupDnisOnly').attr('checked',false);
         jQuery('#GatewaygroupDnisOnly').attr('disabled',true);
         }else{
         jQuery('#GatewaygroupDnisOnly').attr('disabled',false);
         }
         });
         */
    });


    function checkform() {
        if($('#host_authorize').val() == 0 && $('.ip-host-dd').val() == 'ip' && !$('#ip').val().toString().match(/\b\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}\b/)){
            jQuery(this).jGrowlError('Wrong IP Format!', {theme: 'jmsg-error'});
            return false;
        }

        $("input[id='tech_prefix']").each(function(i, e)
        {
            if (!/^[\*|\.|\-|_|#|\+|\w]*$/.test($(e).val()))
            {
                jQuery(this).jGrowlError('Tech prefix, invalide format!', {theme: 'jmsg-error'});
                return false;
            }
        });

//        var arr = new Array();
//        $('#resource_table').find("input[id='tech_prefix']").each(function() {
//            arr.push($(this).val());
//        });
//        var arr2 = $.uniqueArray(arr);
//        if (arr.length != arr2.length) {
//            $('#resource_table').find("input[id='tech_prefix']").each(function() {
//                return false;
//            });
//            jGrowl_to_notyfy('Tech Prefix  Happen  Repeat.', {theme: 'jmsg-error'});
//            return false;
//        }

        if (jQuery('#wait_ringtime180').val() != '') {
            if (!/\d+|\./.test(jQuery('#wait_ringtime180').val())) {
                jQuery('#wait_ringtime180').addClass('invalid');
                jGrowl_to_notyfy('PDD Timeout must contain numeric characters only!', {theme: 'jmsg-error'});
                if($("#timeout_setting_div").find('.in').size() == 0){
                    $("#timeout_setting_div").find('.collapse-toggle').click();
                }
                return false;
            }
        }
        if (jQuery('#delay_bye_second').val() != '') {
            if (!/\d+|\./.test(jQuery('#delay_bye_second').val())) {
                jQuery('#delay_bye_second').addClass('invalid');
                jGrowl_to_notyfy('Min Duration must contain numeric characters only!', {theme: 'jmsg-error'});
                if($("#timeout_setting_div").find('.in').size() == 0){
                    $("#timeout_setting_div").find('.collapse-toggle').click();
                }
                return false;
            }
        }
        if (jQuery('#max_duration').val() != '') {
            if (!/\d+|\./.test(jQuery('#max_duration').val())) {
                jQuery('#max_duration').addClass('invalid');
                jGrowl_to_notyfy('Max Duration must contain numeric characters only!', {theme: 'jmsg-error'});
                if($("#timeout_setting_div").find('.in').size() == 0){
                    $("#timeout_setting_div").find('.collapse-toggle').click();
                }
                return false;
            }
        }
        if (jQuery('#ring_timeout').val() == '' || jQuery('#ring_timeout').val() < 1 || jQuery('#ring_timeout').val() > 60) {
            jQuery('#ring_timeout').addClass('invalid');
            jGrowl_to_notyfy('Ring Timer cant not be greater than 60 or less than 1!', {theme: 'jmsg-error'});
            if($("#timeout_setting_div").find('.in').size() == 0){
                $("#timeout_setting_div").find('.collapse-toggle').click();
            }
            return false;
        }

        return true;
    }

    /*
     function cb_checke(){
     if(jQuery('#GatewaygroupDnisOnly').attr('checked')){
     jQuery('#GatewaygroupLnp').attr('disabled',true);
     //jQuery('#GatewaygroupLrnBlock').attr('disabled',true);

     jQuery('#GatewaygroupLrnBlock').attr('checked',false);
     jQuery('#GatewaygroupLnp').attr('checked',false);
     }else{
     jQuery('#GatewaygroupLnp').attr('disabled',false);
     // jQuery('#GatewaygroupLrnBlock').attr('disabled',false);
     }
     }
     jQuery('#GatewaygroupDnisOnly').click(function(){
     cb_checke();
     });
     cb_checke();
     */

    $(function() {

        $("#GatewaygroupClientId").change(function() {
            var client_id = $(this).val();
            $.ajax({
                type: "POST",
                url: "<?php echo $this->webroot; ?>clients/ajax_get_limit",
                dataType: 'json',
                data: "id=" + client_id,
                success: function(msg) {
                    var call_limit = msg.call_limit;
                    var cps_limit = msg.cps_limit;
                    $("#max_call_limit").html(call_limit);
                    $("#max_cps_limit").html(cps_limit);

                    $("#totalCall").attr('class', "validate[max[" + call_limit + "],custom[onlyNumberSp]]");
                    $("#totalCPS").attr('class', "validate[max[" + cps_limit + "],custom[onlyNumberSp]]");

                }
            });
        });


        var $rate_profile_control = $('.rate_profile_control');

        $('#GatewaygroupRateProfile').change(function() {
            var $this = $(this);
            var val = $this.val();
            if (val == 0)
            {
                $rate_profile_control.hide();
            }
            else
            {
                $rate_profile_control.show();
            }
        }).trigger('change');

    });
</script>