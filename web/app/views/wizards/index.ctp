<style>
    input[type="text"]{width: 220px;}
</style>
<ul class="breadcrumb">
    <li><?php __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?= $this->webroot?>wizards/index"><?php __('Management') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?= $this->webroot?>wizards/index"><?php echo __('Wizard'); ?></a></li>
</ul>


<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Wizard'); ?></h4>
    <div class="buttons pull-right">

    </div>
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>



<div class="innerLR">
    <div class="wizard">
        <div class="widget widget-tabs widget-tabs-double widget-body-white">
            <div class="widget-head">
                <ul>
                    <li class="active">
                        <a class="glyphicons user step" id="step1"  data-toggle="tab" href="#tab1-2">
                            <i></i>
                            <span class="strong"><?php __('Step 1'); ?></span>
                            <span><?php __('Define Carrier'); ?></span>
                        </a>
                    </li>
                    <li>
                        <a class="glyphicons no-js cogwheels step" id="step2" hit="" data-toggle="tab" href="#tab2-2" >
                            <i></i>
                            <span class="strong"><?php __('Step 2'); ?></span>
                            <span><?php __('Define IP'); ?></span>
                        </a>
                    </li>
                    <li>
                        <a class="glyphicons no-js router step" id="step3" data-toggle="tab" href="#tab3-2">
                            <i></i>
                            <span class="strong"><?php __('Step 3'); ?></span>
                            <span><?php __('Define Route'); ?></span>
                        </a>
                    </li>
                    <li>
                        <a class="glyphicons no-js old_man step" id="step4" data-toggle="tab" href="#tab4-2">
                            <i></i>
                            <span class="strong"><?php __('Step 4'); ?></span>
                            <span><?php __('Define User Portal'); ?></span>
                        </a>
                    </li>
                    <?php if($carrier_template || $ingress_template || $egress_template): ?>
                        <li>
                            <a class="glyphicons no-js tag step" id="step5"  hit=""   data-toggle="tab" href="#tab5-2">
                                <i></i>
                                <span class="strong"><?php __('Step 5'); ?></span>
                                <span><?php __('Define Template'); ?></span>
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
            <div class="widget-body">
                <form method="post" id="myform" >
                    <div class="tab-content">
                        <div id="tab1-2" class="tab-pane active">
                            <table class="table dynamicTable tableTools table-bordered  table-white">
                                <tr>
                                    <td class="align_right padding-r20">
                                        <?php __('Carrier Name') ?>
                                    </td>
                                    <td>
                                        <select name="client_type" id="client_type">
                                            <option value="0" selected="selected"><?php __('New Carrier') ?></option>
                                            <option value="1"><?php __('Existing Carrier') ?></option>
                                        </select>
                                    </td>
                                    <td>
                                        <input id="client_name" type="text" name="client_name" class="validate[required,custom[onlyLetterNumberLineSpace]]" />
                                        <select id="client" name="client" style="display:none;">
                                            <?php foreach ($clients as $client): ?>
                                                <option value="<?php echo $client[0]['client_id'] ?>"><?php echo $client[0]['name'] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </td>
                                </tr>

                                <tr id="credit_limit_tr">
                                    <td class="align_right padding-r20"><?php echo __('mode') ?></td>
                                    <td>
                                        <?php
                                        $st = array('1' => __('Prepaid', true), '2' => __('postpaid', true));
                                        echo $form->input('mode', array('options' => $st, 'label' => false, 'div' => false, 'type' => 'select', 'class' => 'input in-text in-select'))
                                        ?>
                                    </td>
                                    <td>
                                        <span class="padding-r20" id="credit_type_flg"><?php echo __('allowedcredit') ?>:</span>
                                        <span id="unlimited_panel">
                                        <?php __('Unlimited')?>
                                        <?php echo $form->input('unlimited_credit', array('class' => 'in-decimal input in-checkbox', 'label' => false, 'div' => false, 'type' => 'checkbox')) ?>
                                        </span>
                                        <?php echo $form->input('allowed_credit', array('label' => false, 'value' => '0.000', 'div' => false, 'type' => 'text', 'class' => 'validate[min[0],custom[number]]', 'maxlength' => '30', 'style' => 'width: 100px; display: inline-block;')) ?>
                                        <span class='money' style="display:inline-block"><?php echo $default_currency; ?></span>
                                    </td>
                                </tr>

                                <tr>
                                    <td class="align_right padding-r20">
                                        <?php __('Trunk Name') ?>
                                    </td>
                                    <td>
                                        <select name="trunk_type" id="trunk_type">
                                            <option value="0"><?php __('Ingress') ?></option>
                                            <option value="1"><?php __('Egress') ?></option>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" name="trunk_name" id="trunk_name" class="validate[required,custom[onlyLetterNumberLineSpace]]" />
                                    </td>
                                </tr>

                                <tr>
                                    <td class="align_right padding-r20"><?php __('CPS Limit') ?></td>
                                    <td>
                                        <input type="text" name="data[cps_limit]" id="cps_limit" class="validate[custom[onlyNumber]]" />
                                    </td>
                                    <td>
                                        <span  class="padding-r20"><?php __('Call Limit') ?></span>
                                        <input type="text" name="data[call_limit]" id="call_limit" class="validate[custom[onlyNumber]]" />
                                    </td>
                                </tr>
                            </table>
                            <div class="separator"></div>
                            <div class="center">
                                <a value="next" data-toggle="tab" onclick="$('#step2').click()" step="#step2" href="javascript:void(0)"  class="input in-submit btn btn-primary"><?php __('Next') ?></a>
                            </div>
                        </div>

                        <div id="tab2-2" class="tab-pane">
                            <div class="overflow_x">
                                <div style="margin-bottom: 20px;">
                                    <a id="add_ip_port" title="<?php __('Add New') ?>" class="btn btn-primary btn-icon glyphicons circle_plus" href="javascript:void(0)" onclick="return false;">
                                        <i class="icon-plus"></i>
                                        <?php __('Add New IP') ?>
                                    </a>
                                </div>
                            </div>
                            <table class="table dynamicTable tableTools table-bordered  table-white table-primary" id="ip_table">
                                <thead>
                                <tr>
                                    <th><?php __('IP'); ?></th>
                                    <th><?php __('Port'); ?></th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody id="ip_tbody">
                                <tr>
                                    <td>
                                        <input type="text" name="ips[]" class="validate[custom[ipv4]]" />
                                    </td>
                                    <td>
                                        <input type="text" name="ports[]" class="validate[custom[onlyNumber]]" maxlength="5" />
                                    </td>
                                    <td>
                                        <a title="delete" href="javascript:void(0)" class="delete_ip_port">
                                            <i class="icon-remove"></i>
                                        </a>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                            <div class="separator"></div>
                            <div class="center">
                                <a step="#step1" href=""  data-toggle="tab" value="Previous" onclick="$('#step1').click()"  id="previous1" class=" btn primary"><?php __('Previous')?></a>
                                <a value="next" data-toggle="tab" onclick="$('#step3').click()" step="#step3" href="javascript:void(0)"  class="input in-submit btn btn-primary"><?php __('Next') ?></a>
                            </div>
                        </div>

                        <div id="tab3-2" class="tab-pane">
                            <table class="table dynamicTable tableTools table-bordered  table-white table-primary">
                                <tr id="host_routing_tr">
                                    <td><?php __('Host Routing'); ?></td>
                                    <td>
                                        <select name="host_routing">
                                            <option value="2"><?php __('Round Robin') ?></option>
                                            <option value="1"><?php __('Top Down') ?></option>
                                        </select>
                                    </td>
                                    <td colspan="2"></td>
                                </tr>

                                <tr id="rate_table_tr">
                                    <td>
                                        <?php __('Rate Table'); ?>
                                    </td>
                                    <td>
                                        <select id="rate_table" name="rate_table">

                                        </select>
                                        <a href="<?php echo $this->webroot ?>rates/create_ratetable" target="_blank" id="add_ratetable" title="<?php __('Add New') ?>...">
                                            <i class="icon-plus"></i>
                                        </a>
                                        <a href="###" target="_blank" id="refresh_ratetable" title="<?php __('Refresh') ?>...">
                                            <i class="icon-refresh"></i>
                                        </a>
                                    </td>
                                    <td colspan="2"></td>
                                </tr>

                                <tr id="routing_type_tr">
                                    <td><?php __('Routing'); ?></td>
                                    <td>
                                        <select name="routing_type" id="routing_type">
                                            <option value="0"><?php __('Static Routing') ?></option>
                                            <option value="1"><?php __('Dynamic Routing') ?></option>
                                        </select>
                                    </td>
                                    <td colspan="2"></td>
                                </tr>

                                <tr id="egress_trunks_tr">
                                    <td>
                                        <?php __('Egress Trunk List'); ?>
                                        <a href="<?php echo $this->webroot ?>prresource/gatewaygroups/add_resouce_egress" target="_blank" id="add_egress_trunk" title="<?php __('Add New') ?>...">
                                            <i class="icon-plus"></i>
                                        </a>
                                        <a href="###" target="_blank" id="refresh_egress_trunk" title="<?php __('Refresh') ?>...">
                                            <i class="icon-refresh"></i>
                                        </a>
                                    </td>
                                    <td>
                                        <select style="float:left;display:block;height:200px;" multiple="multiple" name="egress_trunks_wait" id="egress_trunks_wait" class="select_mul">

                                        </select>
                                        <p style="float:left;margin:60px 30px 0 30px;">
                                        <input  style="width: 60px; height: 30px; margin-left: 0px;"    onclick="DoAdd();"  type="button"  value="<?php __('add') ?>"class="input in-submit btn"  />
                                        <br/><br/>
                                        <input  type="button"   style="width: 60px; height: 30px; margin-left: 0px;"  onclick="DoDel();"   value="<?php __('delete') ?>" class="input in-submit btn" />
                                        </p>
<!--                                        <p style="float:left;margin:60px 0 0 30px;">-->
<!--                                            <a href="###" onclick="DoAdd();"><i class="icon-arrow-right"></i></a>-->
<!---->
<!--                                            <br>-->
<!--                                            <br>-->
<!--                                            <a href="###" onclick="DoDel();"><i class="icon-arrow-left"></i></a>-->
<!--                                        </p>-->
<!--                                    </td>-->
<!--                                    <td>-->
                                        <select style="float:left;display:block;height:200px;" multiple="multiple" name="egress_trunks[]" id="egress_trunks" class="select_mul">

                                        </select>
                                        <p style="float:left;margin:60px 0 0 30px;">
                                            <input class="input in-submit btn"  style="width: 60px; height: 30px; margin-left: 0px;"    onclick="moveOption('select2', 'up');"  type="button"  value="<?php __('up') ?>"  />
                                            <br/><br/>
                                            <input  type="button" class="input in-submit btn"  style="width: 60px; height: 30px; margin-left: 0px;"  onclick="moveOption('select2', 'down');"   value="<?php __('Down') ?>"  />
<!--                                            <a href="###" onclick="moveOption('egress_trunks', 'up');"><i class="icon-arrow-up"></i></a>-->
<!--                                            <br>-->
<!--                                            <br>-->
<!--                                            <a href="###" onclick="moveOption('egress_trunks', 'down');"><i class="icon-arrow-down"></i></a>-->
                                        </p>
                                    </td>
                                    <td></td>
                                </tr>
                                <!--                                <tr style="text-align:center;">
                                        <td colspan="4" class="button-groups center">
                                            <input type="button" id="subbtn" class="btn btn-primary" value="<?php __('Submit') ?>">
                                        </td>
                                    </tr>-->
                            </table>
                            <div class="separator"></div>
                            <div class="center">
                                <a step="#step2" href=""  data-toggle="tab" value="Previous" onclick="$('#step2').click()"   class=" btn primary"><?php __('Previous')?></a>
                                <a value="next" data-toggle="tab" onclick="$('#step4').click()" step="#step4" href="javascript:void(0)"  class="input in-submit btn btn-primary"><?php __('Next') ?></a>
                            </div>
                        </div>

                        <div id="tab4-2" class="tab-pane">
                            <?php echo $this->element('wizards/define_portal'); ?>
                            <div class="separator"></div>
                            <div class="center">
                                <a step="#step3" href=""  data-toggle="tab" value="Previous" onclick="$('#step3').click()"   class=" btn primary"><?php __('Previous')?></a>
                                <a value="next" data-toggle="tab" onclick="$('#step5').click()" step="#step5" href="javascript:void(0)"  class="input in-submit btn btn-primary step_template_trigger"><?php __('Next') ?></a>
                            </div>
                        </div>

                        <div id="tab5-2" class="tab-pane">
                            <table class="table dynamicTable tableTools table-bordered  table-white table-primary">
                                <colgroup>
                                    <col width="40%">
                                    <col width="60%">
                                </colgroup>
                                <?php if($carrier_template): ?>
                                    <tr>
                                        <td class="align_right padding-r10">
                                            <?php __('Deploy Carrier Template'); ?>
                                        </td>
                                        <td>
                                            <?php echo $form->input('deploy_carrier', array( 'label' => false, 'div' => false,
                                                'type' => 'checkbox','class' => 'deploy_btn','checked' => true,'name' => 'deploy_carrier')) ?>
                                        </td>
                                    </tr>
                                    <tr class="carrier_template_tr">
                                        <td class="align_right padding-r10">
                                            <?php __('Carrier Template'); ?>
                                        </td>
                                        <td>
                                            <?php echo $form->input('carrier_template_id', array( 'label' => false, 'div' => false,
                                                'type' => 'select','options' => $carrier_template)) ?>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                                <?php if($ingress_template): ?>
                                    <tr class="ingress_template_btn">
                                        <td class="align_right padding-r10">
                                            <?php __('Deploy Ingress Template'); ?>
                                        </td>
                                        <td>
                                            <?php echo $form->input('deploy_ingress', array( 'label' => false, 'div' => false,
                                                'type' => 'checkbox','class' => 'deploy_btn','checked' => true,'name' => 'deploy_ingress')) ?>
                                        </td>
                                    </tr>
                                    <tr class="ingress_template_tr ingress_template_btn">
                                        <td class="align_right padding-r10">
                                            <?php __('Ingress Template'); ?>
                                        </td>
                                        <td>
                                            <?php echo $form->input('ingress_template', array( 'label' => false, 'div' => false,
                                                'type' => 'select','options' => $ingress_template,'name' => 'ingress_template')) ?>
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <tr class="ingress_template_btn">
                                        <td colspan="2">
                                        </td>
                                    </tr>
                                <?php endif; ?>


                                <?php if($egress_template): ?>

                                <tr class="egress_template_btn hide">
                                    <td class="align_right padding-r10">
                                        <?php __('Deploy Egress Template'); ?>
                                    </td>
                                    <td>
                                        <?php echo $form->input('deploy_egress', array( 'label' => false, 'div' => false,
                                            'type' => 'checkbox','class' => 'deploy_btn','checked' => true,'name' => 'deploy_egress')) ?>
                                    </td>
                                </tr>
                                <tr class="egress_template_tr egress_template_btn hide">
                                    <td class="align_right padding-r10">
                                        <?php __('Egress Template'); ?>
                                    </td>
                                    <td>
                                        <?php echo $form->input('egress_template', array( 'label' => false, 'div' => false,
                                            'type' => 'select','options' => $egress_template,'name' => 'egress_template')) ?>
                                    </td>
                                </tr>
                                <?php else: ?>
                                    <tr class="egress_template_btn">
                                        <td colspan="2">
                                        </td>
                                    </tr>
                                <?php endif; ?>

                            </table>
                            <div class="separator"></div>
                            <div class="center">
                                <a step="#step4" href=""  data-toggle="tab" value="Previous" onclick="$('#step4').click()"   class=" btn primary"><?php __('Previous')?></a>
                                <input type="submit" id="subbtn" class="btn btn-primary" value="<?php __('Submit') ?>">
                            </div>
                        </div>

                    </div>


                </form>

            </div>

        </div>
    </div>
    <input type="hidden" name="AlertRules[id]" value="1" />
    <input type="hidden" id="step_" value="1" />



</div>

<script type="text/javascript" src="<?php echo $this->webroot ?>js/codecs.js"></script>
<script type="text/javascript">
    $(function() {
        /* variables  */
        var $rate_table = $('#rate_table');
        var $egress_trunks_wait = $('#egress_trunks_wait');
        var $credit_limit_tr = $('#credit_limit_tr');
        var $client_name = $('#client_name');
        var $client = $('#client');
        var $refresh_ratetable = $('#refresh_ratetable');
        var $refresh_egress_trunk = $('#refresh_egress_trunk');
        var $routing_type_tr = $('#routing_type_tr');
        var $trunk_type = $('#trunk_type');
        var $egress_trunks_tr = $('#egress_trunks_tr');
        var $rate_table_tr = $('#rate_table_tr');
        var $ip_table = $('#ip_table');
        var $ip_tbody = $("#ip_tbody");
        var $host_routing_tr = $('#host_routing_tr');
        var $myform = $('#myform');
        var $client_type = $('#client_type');
        var $trunk_name = $('#trunk_name');
        var $subbtn = $('#subbtn');
        var $credit_limit = $('#credit_limit');

        var ip_table_tr = $('tr:eq(1)', $ip_table).remove();

        $(".deploy_btn").click(function(){
            var checked = $(this).is(":checked");
            var next_tr = $(this).parent().parent().next();
            if(checked){
                next_tr.show();
            }else{
                next_tr.hide();
            }
        });

        $('#step5').on('click', function(){
             if(!$('#login').val().trim() || !$('#password').val().trim()){
                 jGrowl_to_notyfy('<?php __("Username or Password cannot be empty!"); ?>', {theme: 'jmsg-error'});
                 return false;
             }
        })
        $client_type.change(function() {
            var val = $(this).val();
            if (val == 0) {
                $client_name.show();
                $credit_limit_tr.show();
                $("#mode").change();
                credit_unlimited_change($("#unlimited_credit"));
                $client.hide();
            } else {
                $client_name.hide();
                $credit_limit_tr.hide();
                $client.show();
            }
        });

        $client_type.change();

        $('#add_ip_port').click(function() {
            ip_table_tr.clone(true).appendTo($ip_tbody);
        });

        $("#ip_tbody").on("click", ".delete_ip_port", function() {
          if ($('tr', $ip_tbody).size() >= 1) {
              $(this).parent().parent().remove();
          }
        });

        $('#routing_type').change(function() {
            var val = $(this).val();
            if (val == 0) {
                $host_routing_tr.show();
            } else {
                $host_routing_tr.hide();
            }
        });

        $trunk_type.change(function() {
            var val = $(this).val();
            if (val == 0) {
                // ingress
                $egress_trunks_tr.show();
                $routing_type_tr.show();
                $rate_table_tr.show();
                $(".ingress_template_btn").show();
                $(".egress_template_btn").hide();
            } else {
                // egress
                $egress_trunks_tr.hide();
                $routing_type_tr.hide();
                $rate_table_tr.show();
                $host_routing_tr.hide();
                $(".ingress_template_btn").hide();
                $(".egress_template_btn").show();
            }
        });

        $trunk_type.change();


        $refresh_ratetable.click(function() {
            refresh_ratetable();
            return false;
        });

        $refresh_egress_trunk.click(function() {
            refresh_egress_trunk();
            return false;
        });

        $myform.submit(function(){
            $("#step1").click();
            var flag = false;
            $("#tab1-2").find("input[class*=validate]").each(function(){
                var step1_flg = $(this).validationEngine('validate');
                if(step1_flg){
                    flag = true;
                }
            });
            if(flag) return false;


            var step2_size = $("#tab2-2").find("input[class*=validate]").size();
            if(step2_size > 0){
                $("#step2").click();
                $("#tab2-2").find("input[class*=validate]").each(function(){
                    var step2_flg = $(this).validationEngine('validate');
                    if(step2_flg){
                        flag = true;
                    }
                });

            }
            if(flag) return false;

            $("#step4").click();
            $("#tab4-2").find("input[class*=validate]").each(function(){
                var step4_flg = $(this).validationEngine('validate');
                if(step4_flg){
                    flag = true;
                }
            });

            if(flag) return false;

            var trunk_name = $trunk_name.val();
            var client_name = '';
            var client_id = '';
            if ($client_type.val() == '0') {
                client_name = $client_name.val();

            }else{
                client_id = $client.val();
            }
            var login_name = $("#login").val();

            $.ajax({
                'url': "<?php echo $this->webroot ?>wizards/ajax_check",
                'async': false,
                'type': 'POST',
                'dataType': 'text',
                'data': {'trunk_name': trunk_name, 'client_name': client_name,'login_name':login_name,'client_id':client_id},
                'success': function(data) {
                    var result = parseInt(data);
                    if (result == 1) {
                        jGrowl_to_notyfy('<?php __('The Trunk"s name already exists!'); ?>', {theme: 'jmsg-error'});
                        flag = true;
                    } else if (result == 2) {
                        jGrowl_to_notyfy("<?php __("The Carrier's name already exists!"); ?>", {theme: 'jmsg-error'});
                        flag = true;
                    } else if (result == 3) {
                        jGrowl_to_notyfy('<?php __("The user name already exists!"); ?>', {theme: 'jmsg-error'});
                        $("#step4").click();
                        flag = true;
                    }
                }
            });
            if(flag) return false;


            return true;

        });
  /**      $myform.submit(function() {
            var client_name = '';
            if ($client_type.val() == '0') {
                client_name = $client_name.val();

            }
            var trunk_name = $trunk_name.val();

            var credit_limt = $credit_limit.val();

            if ($.trim(trunk_name) == '') {
                jGrowl_to_notyfy("Trunk's name can not empty!", {theme: 'jmsg-error'});
                $("#step1").click();
                return false;
            }


            if (jQuery('#cps_limit').val() != '') {
                if (/\D+|\./.test(jQuery('#cps_limit').val())) {
                    jQuery('#cps_limit').addClass('invalid');
                    jGrowl_to_notyfy('CPS Limit must contain numeric characters only!', {theme: 'jmsg-error'});
                    $("#step1").click();
                    return false;
                }
            }
            if (jQuery('#call_limit').val() != '') {
                if (/\D+|\./.test(jQuery('#call_limit').val())) {
                    jQuery('#call_limit').addClass('invalid');
                    jGrowl_to_notyfy('Call Limit must contain numeric characters only!', {theme: 'jmsg-error'});
                    $("#step1").click();
                    return false;
                }
            }
            $.ajax({
                'url': "<?php echo $this->webroot ?>wizards/check_exists",
                'async': false,
                'type': 'POST',
                'dataType': 'text',
                'data': {'trunk_name': trunk_name, 'client_name': client_name},
                'success': function(data) {
                    var result = parseInt(data);
                    if (result == 1) {
                        jGrowl_to_notyfy("The Trunk's name already exists!", {theme: 'jmsg-alert'});
                        $("#step1").click();
                        return false;
                    } else if (result == 2) {
                        jGrowl_to_notyfy("The Carrier's name already exists!", {theme: 'jmsg-alert'});
                        $("#step1").click();
                        return false;
                    } else {
                        return true;
                    }
                }
            });
            return false;
        });
**/
        $("#mode").change(function(){
            var mode_type = $(this).val();
            if(mode_type == 2){
                $("#credit_type_flg").html('<?php __('allowedcredit') ?>:');
                $("#unlimited_panel").show();
            }else{
                $("#credit_type_flg").html('<?php __('Test Credit') ?>:');
                $("#unlimited_panel").hide();
            }
        }).trigger('change');

        $("#unlimited_credit").click(function(){
            credit_unlimited_change($(this));
        });

        refresh_ratetable();
        refresh_egress_trunk();

        function credit_unlimited_change(obj)
        {
            var checked = $(obj).is(":checked");
            if(checked){
                $("#allowed_credit").hide();
                $(".money").hide();
            }else{
                $("#allowed_credit").show();
                $(".money").show();
            }
        }

        function refresh_ratetable() {
            $.ajax({
                'url': '<?php echo $this->webroot; ?>wizards/get_ratetable',
                'type': 'GET',
                'dataType': 'json',
                'success': function(data) {
                    $rate_table.empty();
                    $.each(data, function(index, item) {
                        $rate_table.append('<option value="' + item[0]['rate_table_id'] + '">' + item[0]['name'] + '</option>');
                    });
                }
            });
        }

        function refresh_egress_trunk() {
            $.ajax({
                'url': '<?php echo $this->webroot; ?>wizards/get_egress',
                'type': 'GET',
                'dataType': 'json',
                'success': function(data) {
                    $egress_trunks_wait.empty();
                    $.each(data, function(index, item) {
                        $egress_trunks_wait.append('<option value="' + item[0]['resource_id'] + '">' + item[0]['alias'] + '</option>');
                    });
                }
            });
        }

    });
</script>