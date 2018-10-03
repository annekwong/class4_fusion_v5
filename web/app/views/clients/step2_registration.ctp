<ul class="breadcrumb">
    <li>You are here</li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Management') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Registration') ?> [<?php echo $client_name ?>]</li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Add Ingress Trunk') ?></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Add Ingress Trunk') ?></h4>
    <div class="buttons pull-right">
        <a class="btn btn-icon btn-inverse glyphicons circle_arrow_left" href="<?php echo $this->webroot ?>prresource/gatewaygroups/view_egress?<?php echo $$hel->getParams('getUrl') ?>"><i></i> Back</a>
    </div>
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>


<div class="innerLR">

    <div class="widget widget-heading-simple widget-body-white">
        <div class="widget-body">

            <?php $p_url = implode('/',$this->params['pass']);echo $form->create('Clients', array('id' => 'myform', 'action' => "step2_registration/$p_url")); ?> <?php echo $form->input('ingress', array('label' => false, 'value' => 'true', 'div' => false, 'type' => 'hidden')); ?> <?php echo $form->input('egress', array('label' => false, 'value' => 'false', 'div' => false, 'type' => 'hidden')); ?>
            <input type="hidden" name="is_finished" id="is_finished" value="0" />
            <strong><?php echo __('Ingress Name: ',true);?></strong>
            <p><?php echo $form->input('ingress_name', array('name' => 'resource[name]', 'type' => 'text',
                'label' => false, 'div' => false, 'default' => $client_name, 'class' => 'input in-text product_ingress_name validate[required,custom[onlyLetterNumberLineSpace]]')) ?></p>
            <!--
            <div class="row-fluid">

                <div class="span3">
                    <strong><?php echo __('Ingress Name', true); ?></strong>
                    <p class="muted"></p>
                </div>

                <div class="span9">
                    <?php echo $form->input('alias', array('id' => 'alias', 'label' => false, 'div' => false, 'type' => 'text', 'maxlength' => '256','class'=>'validate[required]')); ?>
                    <div class="separator"></div>
                </div>


            </div>
            -->
            <?php echo $this->element("clients/resource_prefix_registration") ?>
            <?php if(!$is_template):?>
            <p class="separator text-center"><i class="icon-table icon-3x"></i></p>
            <!-- Column -->
            <div class="span3">
                <strong><?php __('Authorized'); ?></strong>
                <p class="muted"></p>
            </div>
            <!-- // Column END -->

            <!-- Column -->
            <div >
                <select name='reg_type' id='host_authorize' onchange='check_host();'>
                    <option value="1">Authorized by IP Only</option>
                    <option value="2">Authorized by SIP Registration </option>
                </select>
                <div class="separator"></div>
            </div>
            <!-- // Column END -->
            <?php endif;?>

            <?php echo $this->element("gatewaygroups/host_edit_registration"); ?>





            <?php
            if ($$hel->_get('viewtype') == 'wizard')
            {
                ?>
                <div id="form_footer">
                    <input type="submit"    onclick="seleted_codes();
                            jQuery('#GatewaygroupAddResouceIngressForm').attr('action', '?nextType=egress&<?php echo $$hel->getParams('getUrl') ?>')" value="<?php echo __('Next Egress') ?>" style="width:80px" />
                    <input type="submit"    onclick="seleted_codes();
                            jQuery('#GatewaygroupAddResouceIngressForm').attr('action', '?nextType=ingress&<?php echo $$hel->getParams('getUrl') ?>')" value="<?php echo __('Next Ingress') ?>" style="width:80px"/>
                    <input type="button"  value="<?php echo __('End') ?>" class="input in-submit" onclick="location = '<?php echo $this->webroot ?>clients/index?filter_id=<?php echo $$hel->_get('query.id_clients') ?>'"/>
                </div>
                <?php
            }
            else
            {
                ?>

                <div id="form_footer"  class="buttons pull-right">
                    <input type="submit" class="btn btn-primary" id ="submit_form" value="<?php echo __('Add Ingress Trunk') ?>" />
                    <input type="button" class="btn btn-primary" id="back" value="<?php echo __('Finish') ?>" />
                </div>
                <div class="clearfix"></div>
            <?php } ?>
            <?php echo $form->end(); ?> 

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



        <!-----------Add dynamic----------->
        <div id="pop-div-dynamic" class="pop-div" style="display:none;">
            <div class="pop-thead">
                <span></span>
                <span class="float_right"><a href="javascript:closeDiv2('pop-div-dynamic')" id="pop-close-dynamic" class="pop-close">&nbsp;</a></span>
            </div>
            <div class="pop-content" id="pop-content-dynamic"></div>
        </div>
        <div id="pop-clarity-dynamic" class="pop-clarity" style="display:none;"></div>

<!--        <script type="text/javascript" src="--><?php //echo $this->webroot ?><!--js/gateway.js"></script>-->
        <!--
        <script type="text/javascript">

                 jQuery(document).ready(
                    function() {
                        jQuery('#totalCall,#totalCPS').xkeyvalidate({type: 'Num'});
                        jQuery("form[id^=ClientsAddingress]").submit(function() {
                            var re = true;
                            if (jQuery('#alias').val() == '') {
                                jQuery('#alias').addClass('invalid');
                                jQuery(this).jGrowlError('The field Egress Name cannot be NULL.');
                                return false;

                            } else if (/[^0-9A-Za-z-\_\s]/.test(jQuery("#alias").val())) {
                                jQuery('#alias').addClass('invalid');
                                jQuery(this).jGrowlError('Ingress Name, allowed characters: a-z,A-Z,0-9,-,_,space, maximum  of 256 characters in length!');
                                return false;
                            }

                            if (jQuery('#totalCall').val() != '') {
                                if (/\D/.test(jQuery('#totalCall'.val()))) {
                                    jQuery(this).addClass('invalid');
                                    jQuery(this).jGrowlError('Call limit, must be whole number! ');
                                    return false;
                                }
                            }

                            if (jQuery('#totalCPS').val() != '') {
                                if (/\D/.test(jQuery('#totalCPS').val())) {
                                    jQuery(this).addClass('invalid');
                                    jQuery(this).jGrowlError('CPS Limit, must be whole number!');
                                    return false;
                                }

                            }
                            if (jQuery('#ip:visible').val() != '' || !jQuery('#ip:visible').val()) {
                                if (!/^(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])$/.test(jQuery('#ip:visible').val())) {

                                    jQuery(this).addClass('invalid');
                                    jQuery(this).jGrowlError('IPs/FQDN must be a valid format ');
                                    return false;

                                }
                            }
                            if (jQuery('#GatewaygroupClientId').val() == '') {
                                jQuery(this).addClass('invalid');
                                jQuery(this).jGrowlError('Please Select Carriers !');
                                return false;
                            }

                            return re;
                        })
                    });





        </script>
        -->
        <script type="text/javascript">
            $(function() {
            $('#myform').validationEngine();
                        $('#back').click(function() {
                            $('#is_finished').val('1');
                            $('#myform').submit();
                        });
                    });
            $('#myform').submit(
                function(){
                    $('.route_strategy_id').attr('disabled',false);
                    $('.rate_table_id').attr('disabled',false);
                    $('.tech_prefix').attr('disabled',false);
                }
            );
        </script>