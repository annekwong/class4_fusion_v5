<style type="text/css">
    .in-text, .in-password, .in-textarea, .value select, .value textarea, .value .in-text, .value .in-password, .value .in-textarea, .value .in-select{ width:250px;}
    select, textarea, input[type="text"]{margin-bottom: 0;}
    th .btn-primary,th .btn-primary:hover{background: #7FAF00;}
</style>
<ul class="breadcrumb">
    <li><?php echo __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Origination') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Wizard') ?></li>
</ul>

<div class="heading-buttons">
    <h1><?php echo __('Wizard') ?></h1>

</div>
<div class="separator bottom"></div>
<div class="clearfix"></div>
<div class="innerLR">
    <div class="widget widget-tabs widget-tabs-double widget-body-white">
        <div class="widget-head">
            <ul>
                <li class="active">
                    <a class="glyphicons paperclip step" id="step1"  data-toggle="tab" href="#tab1-2">
                        <i></i>
                        <span class="strong"><?php __('Step 1') ?></span>
                        <span><?php __('Define Vendor') ?></span>
                    </a>
                </li>
                <li>
                    <a class="glyphicons no-js projector step" id="step2" hit="" data-toggle="tab" href="#tab2-2" >
                        <i></i>
                        <span class="strong"><?php __('Step 2') ?></span>
                        <span><?php __('Define Client') ?></span>
                    </a>
                </li>
                <li>
                    <a class="glyphicons no-js tag step" id="step3"  hit=""   data-toggle="tab" href="#tab3-2">
                        <i></i>
                        <span class="strong"><?php __('Step 3') ?></span>
                        <span><?php __('Define DID') ?></span>
                    </a>
                </li>

                <li>
                    <a class="glyphicons no-js tint step" id="step4" data-toggle="tab" href="#tab4-2">
                        <i></i>
                        <span class="strong"><?php __('Step 4') ?></span>
                        <span><?php __('Define Vendor Billing Rule') ?></span>
                    </a>
                </li>
                <li>
                    <a class="glyphicons no-js tint step" id="step5" data-toggle="tab" href="#tab5-2">
                        <i></i>
                        <span class="strong"><?php __('Step 5') ?></span>
                        <span><?php __('Define Client Billing Rule') ?></span>
                    </a>
                </li>
            </ul>    
        </div>
        <div class="widget-body">
            <form method="post" id="wizard_form" >
                <div class="tab-content">


                    <!--step 1-->
                    <div id="tab1-2" class="tab-pane active" style="height:100%">
                        <table class="form table dynamicTable tableTools table-bordered  table-white">
                            <col width="40%">
                            <col width="60%">
                            <tr>
                                <td class="align_right"><?php __('Vendor') ?>:</td>
                                <td>
                                    <select name="vendor" id="vendors" class="validate[required]">
                                        <?php foreach ($did_vendors as $vendor_id => $vendor_name): ?>
                                            <option value="<?php echo $vendor_id ?>"><?php echo $vendor_name ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <a href="javascript:void(0)" title="<?php __('Create New') ?>" id="add_vendor"><i class="icon-plus"></i></a>
                                    <a id="vendors_refresh" href="javascript:void(0)" title="<?php __('Refresh') ?>"><i class="icon-refresh"></i></a>
                                </td>
                            </tr>
                        </table>
                        <div class="center separator">
                            <a value="next" id="next1" data-toggle="tab" step="#step2" href=""  class="input in-submit btn btn-primary"><?php __('Next') ?></a>
                        </div>
                    </div>


                    <!--step 2-->
                    <div id="tab2-2" class="tab-pane">
                        <table class="form table dynamicTable tableTools table-bordered  table-white">
                            <col width="40%">
                            <col width="60%">
                            <tr>
                                <td class="align_right"><?php __('Clients') ?>:</td>
                                <td>
                                    <select name="clients" id="clients" class="validate[required]">
                                        <?php foreach ($did_clients as $client_id => $client_name): ?>
                                            <option value="<?php echo $client_id ?>"><?php echo $client_name ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <a href="javascript:void(0)" title="<?php __('Create New') ?>" id="add_client"><i class="icon-plus"></i></a>
                                    <a id="clients_refresh" href="javascript:void(0)"><i class="icon-refresh"></i></a>
                                </td>
                            </tr>
                        </table>
                        <div class="center separator">
                            <a step="#step2" href=""  data-toggle="tab" value="next"  id="previous2" class=" btn primary"><?php __('Previous') ?></a>
                            <a value="next" id="next2" data-toggle="tab" step="#step3" href=""  class="input in-submit btn btn-primary"><?php __('Next') ?></a>
                            <!--<input type="submit" value="Finish" id="finish" class="input in-submit btn btn-primary" style="display: none;"  />-->
                        </div>
                    </div>  


                    <!--step 3-->
                    <div id="tab3-2" class="tab-pane">
                        <table class="form table dynamicTable tableTools table-bordered  table-white">
                            <col width="40%">
                            <col width="60%">
                            <tr>
                                <td class="align_right"><?php __('DID') ?>:</td>
                                <td>
                                    <select id="did_type" name="did_type">
                                        <option value="1"><?php __('Specify DID') ?></option>
                                        <option value="2"><?php __('Upload DID File') ?></option>
                                    </select>
                                </td>
                            </tr>

                            <tr id="did_type1" class="did_type">
                                <td class="align_right"><?php __('Specify DID(Max 1000)') ?>:</td>
                                <td>
                                    <textarea class="validate[required]" id="specify_did" name="did_value" wrap="hard" maxlength="1000" rows="5" cols="30" style="resize:none;"></textarea>
                                </td>
                            </tr>

                            <tr id="did_type2" style="display:none;" class="did_type">
                                <td class="align_right"><?php __('Upload DID File') ?>:</td>
                                <td>
                                    <a id="upload_did" href="javascript:void(0)"><?php __('Upload DID File Link') ?></a>
                                </td>
                            </tr>
                        </table>
                        <div class="center separator">
                            <a step="#step3" href=""  data-toggle="tab" value="next"  id="previous3" class=" btn primary"><?php __('Previous') ?></a>
                            <a value="next" id="next3" data-toggle="tab" step="#step4" href=""  class="input in-submit btn btn-primary"><?php __('Next') ?></a>
                            <!--<input type="submit" value="Finish" id="finish" class="input in-submit btn btn-primary" style="display: none;"  />-->
                        </div>
                    </div>    


                    <!--step 4-->
                    <div id="tab4-2" class="tab-pane">
                        <table class="form table dynamicTable tableTools table-bordered  table-white">
                            <col width="40%">
                            <col width="60%">
                            <tr>
                                <td class="align_right"><?php __('Vendor Billing Rule') ?>:</td>
                                <td>
                                    <select name="vendor_billing_rule" id="vendor_billing_rule" class="validate[required]">
                                        <?php foreach ($did_billing_rule as $billing_rule_id => $billing_rule_name): ?>
                                            <option value="<?php echo $billing_rule_id ?>"><?php echo $billing_rule_name ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <a title="Add billing rule" href="javascript:void(0)" class="add_billing_rule"><i class="icon-plus"></i></a>
                                    <a title="Refresh" id="v_billing_rule_refresh" href="javascript:void(0)"><i class="icon-refresh"></i></a>
                                </td>
                            </tr>
                        </table>
                        <div class="center separator">
                            <a step="#step4" href=""  data-toggle="tab" value="next"  id="previous4" class=" btn primary"><?php __('Previous') ?></a>
                            <a value="next" id="next4" data-toggle="tab" step="#step5" href=""  class="input in-submit btn btn-primary"><?php __('Next') ?></a>
                            <!--<input type="submit" value="Finish" id="finish" class="input in-submit btn btn-primary" style="display: none;"  />-->
                        </div>
                    </div> 

                    <!--step 5-->
                    <div id="tab5-2" class="tab-pane">
                        <table class="form table dynamicTable tableTools table-bordered  table-white">
                            <col width="40%">
                            <col width="60%">
                            <tr>
                                <td class="align_right"><?php __('Client Billing Rule') ?>:</td>
                                <td>
                                    <select name="client_billing_rule" id="client_billing_rule" class="validate[required]">
                                        <?php foreach ($did_billing_rule as $billing_rule_id => $billing_rule_name): ?>
                                            <option value="<?php echo $billing_rule_id ?>"><?php echo $billing_rule_name ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <a title="Add billing rule" href="javascript:void(0)" class="add_billing_rule"><i class="icon-plus"></i></a>
                                    <a title="Refresh" id="c_billing_rule_refresh" href="javascript:void(0)"><i class="icon-refresh"></i></a>
                                </td>
                            </tr>
                        </table>
                        <div class="center separator">
                            <a step="#step5" href=""  data-toggle="tab" value="next"  id="previous5" class=" btn primary"><?php __('Previous') ?></a>
                            <input type="submit" value="<?php __('Finish') ?>" id="finish" class="input in-submit btn btn-primary" />
                        </div>

                    </div>


            </form>

        </div>

    </div>
</div>
<input type="hidden" name="AlertRules[id]" value="1" />
<input type="hidden" id="step_" value="1" />



</div>

<script type="text/javascript">

    $vendors = $("#vendors");
    $clients = $("#clients");
    $v_billing_rule = $("#vendor_billing_rule");
    $c_billing_rule = $("#client_billing_rule");
    $(function() {

        $("#wizard_form").submit(function() {
            var did_type = $("#did_type").val();
            if (did_type == 1)
            {
                if (!$("#specify_did").val())
                {
                    $("#step3").click();
                    return false;
                }
            }
            return true;
        });

        $("#did_type").change(function() {
            var type = $(this).val();
            $(".did_type").hide();
            if (type == 2)
            {
                $("#did_type2").show();
            }
            else
            {
                $("#did_type1").show();
            }
        });

        $("#vendors_refresh").click(function() {
            refresh_vendors();
        });

        $("#clients_refresh").click(function() {
            refresh_clients();
        });

        $("#v_billing_rule_refresh").click(function() {
            refresh_billing_rule($v_billing_rule);
        });

        $("#c_billing_rule_refresh").click(function() {
            refresh_billing_rule($c_billing_rule);
        });

        $("#next1").click(function() {
            $("#step2").click();
        });
        $("#next2").click(function() {
            $("#step3").click();
        });
        $("#next3").click(function() {
            var did_type = $("#did_type").val();
            if (did_type == 1)
            {
                if (!$("#specify_did").validationEngine('validate'))
                {
                    $("#step4").click();
                }
            }
            else
            {
                $("#step4").click();
            }
//            
        });
        $("#next4").click(function() {
            if (!$("#vendor_billing_rule").validationEngine('validate'))
            {
                $("#step5").click();
            }
        });

        $("#previous2").click(function() {
            $("#step1").click();
        });
        $("#previous3").click(function() {
            $("#step2").click();
        });
        $("#previous4").click(function() {
            $("#step3").click();
        });
        $("#previous5").click(function() {
            $("#step4").click();
        });

        $("#upload_did").click(function() {
            var vendor_id = $("#vendors").val();
            window.open("<?php echo $this->webroot; ?>did/did_reposs/upload/?id=" + vendor_id);
        });


        $("#step4").click(function(){
            if($("#vendor_billing_rule").children().size() == 0)
            {
                $("#vendor_billing_rule").next().trigger('click');
            }
        });
        $("#step5").click(function(){
            if($("#client_billing_rule").children().size() == 0)
            {
                $("#client_billing_rule").next().trigger('click');
            }
        });
        $(".add_billing_rule").click(function() {
            $opt = $(this).siblings().eq(0);
            if (!$('#dd').length) {
                $(document.body).append("<div id='dd'></div>");
            }
            var $dd = $('#dd');
            $dd.load('<?php echo $this->webroot ?>did/wizard/ajax_add_billing_rule',
                    {},
                    function(responseText, textStatus, XMLHttpRequest) {
                        $dd.dialog({
                            'title': "<?php __("Create New Billing Rule"); ?>",
                            'width': '850px',
                            buttons: [{
                                    text: 'Submit',
                                    class: 'btn btn-primary',
                                    click: function() {
                                        $.ajax({
                                            url: "<?php echo $this->webroot ?>did/billing_rule/plan_edit_panel",
                                            type: 'post',
                                            dataType: 'text',
                                            data: $('#form1').serialize(),
                                            success: function(data) {
                                                if(data){
                                                    refresh_billing_rule($opt,data);
                                                    jGrowl_to_notyfy('<?php __('Create succeed!'); ?>', {theme: 'jmsg-success'});
                                                    $("#myModal_pricing_rule").find('.close').click();
                                                    $dd.dialog('close');
                                                }else{
                                                    jGrowl_to_notyfy('<?php __('Create failed!'); ?>', {theme: 'jmsg-error'});
                                                }
                                            }
                                        });
                                    }
                                }]
                        });
                    }
            );
        });


        $("#add_vendor").click(function() {
            if (!$('#dd_vendor').length) {
                $(document.body).append("<div id='dd_vendor'></div>");
            }
            var $dd_vendor = $('#dd_vendor');
            $dd_vendor.load('<?php echo $this->webroot ?>did/vendors/ajax_add_vendor',
                    {},
                    function(responseText, textStatus, XMLHttpRequest) {
                        $dd_vendor.dialog({
                            'title': "<?php __("Create New Vendor"); ?>",
                            'width': '850px',
                            buttons: [{
                                    text: 'Submit',
                                    class: 'btn btn-primary',
                                    click: function() {
                                        $.ajax({
                                            url: "<?php echo $this->webroot ?>did/vendors/add/1",
                                            type: 'post',
                                            dataType: 'text',
                                            data: $('#myform').serialize(),
                                            success: function(data) {
                                                if (data == 1)
                                                {
                                                    refresh_vendors();
                                                }
                                                else
                                                {
                                                    jGrowl_to_notyfy('<?php __('Create failed'); ?>', {theme: 'jmsg-error'});
                                                }
                                                $dd_vendor.dialog("close");
                                            }
                                        });
                                    }
                                }]
                        });
                    }
            );
        });


        $("#add_client").click(function() {
            if (!$('#dd_client').length) {
                $(document.body).append("<div id='dd_client'></div>");
            }
            var $dd_client = $('#dd_client');
            $dd_client.load('<?php echo $this->webroot ?>did/clients/ajax_add_client',
                    {},
                    function(responseText, textStatus, XMLHttpRequest) {
                        $dd_client.dialog({
                            'title': "<?php __("Create New Client"); ?>",
                            'width': '850px',
                            buttons: [{
                                    text: 'Submit',
                                    class: 'btn btn-primary',
                                    click: function() {
                                        $.ajax({
                                            url: "<?php echo $this->webroot ?>did/clients/add/1",
                                            type: 'post',
                                            dataType: 'text',
                                            data: $('#myform_client').serialize(),
                                            success: function(data) {
                                                if (data == 1)
                                                {
                                                    refresh_clients();
                                                }
                                                else
                                                {
                                                    jGrowl_to_notyfy('<?php __('Create failed'); ?>', {theme: 'jmsg-error'});
                                                }
                                                $dd_client.dialog("close");
                                            }
                                        });
                                    }
                                }]
                        });
                    }
            );
        });

    });

    function refresh_vendors() {
        $.ajax({
            'url': '<?php echo $this->webroot; ?>did/wizard/get_vendors',
            'type': 'GET',
            'dataType': 'json',
            'success': function(data) {
                $vendors.empty();
                $.each(data, function(index, item) {
                    $vendors.append('<option value="' + item[0]['resource_id'] + '">' + item[0]['alias'] + '</option>');
                });
            }
        });
    }

    function refresh_clients() {
        $.ajax({
            'url': '<?php echo $this->webroot; ?>did/wizard/get_clients',
            'type': 'GET',
            'dataType': 'json',
            'success': function(data) {
                $clients.empty();
                $.each(data, function(index, item) {
                    $clients.append('<option value="' + item[0]['resource_id'] + '">' + item[0]['alias'] + '</option>');
                });
            }
        });
    }


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

</script>