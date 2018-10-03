<ul class="breadcrumb">
    <li><?php __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Management', true); ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo "Product"; ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo isset($product_id) && !empty($product_id) ? "Edit" : "Add"; ?></li>
</ul>
<div class="heading-buttons">
    <h4 class="heading"><?php echo isset($product_id) && !empty($product_id) ? "Product/Edit" : "Product/Add"; ?></h4>
</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
    <a href="<?php echo $this->webroot ?>product_management/index" class="link_back btn btn-icon btn-inverse glyphicons circle_arrow_left"><i></i><?php echo __('goback', true); ?></a>
</div>
<div class="clearfix"></div>
<div class="innerLR">

    <div class="widget widget-heading-simple widget-body-white">
        <div class="widget-body">
            <?php echo $form->create('ProductRouteRateTable',array('method' => 'post','url' => array('controller' => 'product_management','action'=>'add_edit'))); ?>
            <input type="hidden" name="product_id" value="<?php echo $product_id; ?>" id="product_id" />
            <table class="form footable table dynamicTable tableTools table-bordered  table-white default footable-loaded">
                <colgroup>
                    <col width="37%">
                    <col width="63%">
                </colgroup>
                <tr>
                    <td class="align_right padding-r10"><?php __('Product Name'); ?>*:</td>
                    <td>
                        <?php echo $xform->input('product_name',array('maxlength'=>256,'class'=>'width220 validate[required,custom[onlyLetterNumberLineSpace]]'))?>
                    </td>
                </tr>
                <tr>
                    <td class="align_right padding-r10"><?php __('Routing Plan'); ?>:</td>
                    <td>
                        <?php echo $xform->input('route_strategy_id',array('type'=>'select','options' => $route_plan, 'selected' => $selectedRouteStrategy))?>
                        <a href="#myModalAddRoutePlan" data-toggle="modal" title="<?php __('Create New'); ?>">
                            <i class="icon-plus"></i>
                        </a>
                        <a href="javascript:void(0)" id="refreshRoutePlan" title="<?php __('Refresh') ?>">
                            <i class="icon-refresh"></i>
                        </a>
                    </td>
                </tr>
                <tr>
                    <td class="align_right padding-r10">
                        <label><?php __('Rate Table')?>:</label>
                    </td>
                    <td>
                        <?php echo $xform->input('rate_table_id',array('type'=>'select','options' => $rate_table, 'selected' => $selectedRateTable))?>
                        <a href="#myModalAddRateTable" data-toggle="modal" title="<?php __('Create New'); ?>">
                            <i class="icon-plus"></i>
                        </a>
                        <a href="javascript:void(0)" id="refresh_ratetable" title="<?php __('Refresh') ?>">
                            <i class="icon-refresh"></i>
                        </a>
                    </td>
                </tr>
                <tr>
                    <td class="align_right padding-r10">
                        <label><?php __('Description')?>:</label>
                    </td>
                    <td>
                        <?php echo $xform->input('description',array('type'=>'textarea'))?>
                    </td>
                </tr>
                <tr>
                    <td class="align_right padding-r10">
                        <label><?php __('Tech Prefix')?>*:</label>
                    </td>
                    <td>
                        <?php echo $xform->input('tech_prefix',array('type'=> 'text','class'=>'width220 validate[required,custom[code1]]'))?>
                    </td>
                </tr>
                <tr>
                    <td class="align_right padding-r10">
                        <label><?php __('Set Private')?>:</label>
                    </td>
                    <td>
                        <?php echo $xform->input('is_private',array('type'=> 'checkbox','id' => 'is_private'))?>
                    </td>
                </tr>
                <tr class="assign">
                    <td class="align_right padding-r10">
                        <label><?php __('Assigned to Carriers')?>:</label>
                    </td>
                    <td>
                        <?php echo $xform->input('assign_ids',array('type'=> 'select','multiple'=>'multiple','options' => $carriers, 'class'=>"multiselect",'id'=>'assign_ids'))?>
                    </td>
                </tr>
                <tr class="assign">
                    <td class="align_right padding-r10">
                        <label><?php __('Assigned to Agent')?>:</label>
                    </td>
                    <td>
                        <?php echo $xform->input('assign_agent_ids',array('type'=> 'select','multiple'=>'multiple','options' => $agents, 'class'=>"multiselect",'id'=>'assign_agent_ids'))?>
                    </td>
                </tr>
            </table>

            <?php echo $this->element('common/submit_div'); ?>
            <?php echo $form->end(); ?>
        </div>
    </div>
</div>

<div id="myModalAddRateTable" class="modal hide">
    <div class="modal-header">
        <button data-dismiss="modal" class="close" type="button">&times;</button>
        <h3><?php __('Add Rate Table'); ?></h3>
    </div>
    <div class="separator"></div>
    <div class="modal-body">
    </div>
    <div class="modal-footer">
        <input type="button" class="btn btn-primary sub" value="<?php __('Submit'); ?>">
        <a href="javascript:void(0)" data-dismiss="modal" class="btn btn-default"><?php __('Close'); ?></a>
    </div>

</div>

<div id="myModalAddRoutePlan" class="modal hide">
    <div class="modal-header">
        <button data-dismiss="modal" class="close" type="button">&times;</button>
        <h3><?php __('Add Routing Plan'); ?></h3>
    </div>
    <div class="separator"></div>
    <div class="widget-body">
        <table class="table tableTools table-bordered  table-white">
            <tr>
                <td style="text-align:right;">
                    <?php __('Routing Plan Name'); ?>:
                </td>
                <td>
                    <input type="text"  name="routing_plan_name" class="validate[required] width220 routing_plan_name"/>
                </td>
            </tr>
        </table>
    </div>
    <div class="modal-footer">
        <input type="button" class="btn btn-primary sub" value="<?php __('Submit'); ?>">
        <a href="javascript:void(0)" data-dismiss="modal" class="btn btn-default"><?php __('Close'); ?></a>
        <a class="add_route hide" href="#myModal_AddRoute" data-toggle="modal"><i></i> <?php __('Create New') ?></a>
    </div>

</div>


<!-- Add Route start -->
<div id="myModal_AddRoute" class="modal hide">
    <div class="modal-header">
        <button data-dismiss="modal" class="close" type="button">&times;</button>
        <h3><?php __('Add Route'); ?></h3>
    </div>
    <div class="widget-body">
    </div>
    <div class="modal-footer">
        <input type="hidden" class="dy_route_name" />
        <input type="hidden" class="st_route_name" />
        <input type="hidden" class="intra_route_name" />
        <input type="hidden" class="inter_route_name" />
        <input type="hidden" class="routestrategys" />
        <input type="button" id="add_route_submit" class="btn btn-primary" value="<?php __('Submit'); ?>">
        <a href="javascript:void(0)" id="add_route_close" data-dismiss="modal" class="btn btn-default"><?php __('Close'); ?></a>
    </div>

</div>
<!-- Add Route end -->


<link href="<?php echo $this->webroot?>common/theme/scripts/plugins/forms/multiselect/css/multi-select.css" rel="stylesheet" />
<script src="<?php echo $this->webroot?>common/theme/scripts/plugins/forms/multiselect/js/jquery.multi-select.js"></script>
<script type="text/javascript" src="<?php echo $this->webroot; ?>js/jquery.base64.min.js"></script>
<script type="text/javascript">
    var $rate_table = $("#ProductRouteRateTableRateTableId");
    var $route_plan = $("#ProductRouteRateTableRouteStrategyId");
    function refresh_rate_table(default_selected)
    {
        default_selected = default_selected.trim();
        $.ajax({
            'url': '<?php echo $this->webroot; ?>product_management/ajax_get_rate_table',
            'type': 'GET',
            'dataType': 'json',
            'success': function(data) {
                $rate_table.empty();
                $.each(data, function(index, item) {
                    if ( item[0]['rate_table_id'] == default_selected ){
                        $rate_table.append('<option selected value="' + item[0]['rate_table_id'] + '">' + item[0]['name'] + '</option>');
                    }else{
                        $rate_table.append('<option value="' + item[0]['rate_table_id'] + '">' + item[0]['name'] + '</option>');
                    }

                });
            }
        });
    }
    function refresh_route_plan(default_selected)
    {
        $.ajax({
            'url': '<?php echo $this->webroot; ?>product_management/ajax_get_route_plan',
            'type': 'GET',
            'dataType': 'json',
            'success': function(data) {
                $route_plan.empty();
                $.each(data, function(index, item) {
                    if ( item[0]['route_strategy_id'] == default_selected ){
                        $route_plan.append('<option selected value="' + item[0]['route_strategy_id'] + '">' + item[0]['name'] + '</option>');
                    }else{
                        $route_plan.append('<option value="' + item[0]['route_strategy_id'] + '">' + item[0]['name'] + '</option>');
                    }

                });
            }
        });
    }

    $(function(){
        $('#assign_ids').multiSelect({
              selectableHeader: "<div class='custom-header'>All Carriers</div>",
              selectionHeader: "<div class='custom-header'>Selected Carriers</div>",
        });
        $('#assign_agent_ids').multiSelect({
              selectableHeader: "<div class='custom-header'>All Agents</div>",
              selectionHeader: "<div class='custom-header'>Selected Agents</div>",
        });
        $("#refresh_ratetable").click(function(){
            refresh_rate_table(0);
        });

        $("#myModalAddRateTable").on('shown',function(){
            $(this).find('.modal-body').load("<?php echo $this->webroot; ?>rates/create_ratetable?is_ajax=1");
        });

        $("#myModalAddRateTable").find('.sub').click(function(){
            var $this = $(this);
            var is_validate = $("#myform").validationEngine('validate');
            if ( !is_validate ){
                return false;
            }

            $.ajax({
                url: "<?php echo $this->webroot ?>rates/create_ratetable?is_ajax=1",
                type: 'post',
                dataType: 'text',
                data: $('#myform').serialize(),
                success: function(data) {
                    $this.next().click();
                    if (data != 0)
                    {
                        rate_table_id = data;
                        bootbox.dialog("<?php echo __('Rate table create successfully!'); ?>", [
                            {
                                "label": "Cancel",
                                'class': 'btn-cancel',
                                "callback": function () {
                                }
                            }, {
                                "label": "<?php echo ucwords(__('import data',true)); ?>",
                                "class": "btn-primary",
                                "callback": function () {
                                    var href = "<?php echo $this->webroot; ?>clientrates/import/" + $.base64.encode(rate_table_id);
                                    window.open(href);
                                }
                            }, {
                                "label": "<?php echo ucwords(__('add data',true)); ?>",
                                "class": "btn-primary",
                                "callback": function () {
                                    var href = "<?php echo $this->webroot; ?>clientrates/view/" + $.base64.encode(rate_table_id);
                                    window.open(href);
                                }
                            }]);
                        $('#dd').dialog('close');

                        refresh_rate_table(data);

                        jGrowl_to_notyfy('<?php __('Create success'); ?>', {theme: 'jmsg-success'});
                    }
                    else
                        jGrowl_to_notyfy('<?php __('Create failed'); ?>', {theme: 'jmsg-error'});
                }
            });
        });

        $("#refreshRoutePlan").click(function(){
            refresh_route_plan(0);
        });

        $("#myModalAddRoutePlan").find('.sub').click(function(){
            var $this = $(this);
            var is_validate = $("#myModalAddRoutePlan").find(".routing_plan_name").validationEngine('validate');
            if ( is_validate ){
                return false;
            }
            var params = {
                name: $("#myModalAddRoutePlan").find(".routing_plan_name").val()
            };
            jQuery.post('<?php echo $this->webroot ?>routestrategys/add', params, function (data) {
                var tmp = data.split("|");
                var p = {theme: 'jmsg-success', life: 100};
                if (tmp[1].trim() == 'false') {
                    p = {theme: 'jmsg-error', life: 500};
                    jGrowl_to_notyfy(tmp[0], p);
                }
                else {
                    $this.next().click();
                    var route_plan_id = $.base64.decode(tmp[2].trim());
                    refresh_route_plan(route_plan_id);
                    console.log(tmp[2].trim());
                    $("#myModal_AddRoute").find('.routestrategys').val(tmp[2].trim());
                    $this.next().next().click();
                    jGrowl_to_notyfy(tmp[0], p);

                }
            });
        });

        $("#myModal_AddRoute").on('shown',function(){
            var routestrategys = $(this).find('.routestrategys').val();
            console.log(routestrategys);
            $(this).find(".widget-body").load('<?php echo $this->webroot ?>routestrategys/save_route_panel?routestrategys=' + routestrategys);
        });

        $("#add_route_submit").click(function() {
            var $this = $(this);
            var RoutestrategysRouteTypeFlg = $("#RoutestrategysRouteType").find('option:selected').attr('flg');
            var RoutestrategysDynamicRouteId = $("#RoutestrategysDynamicRouteId").val();
            var RoutestrategysStaticRouteId = $("#RoutestrategysStaticRouteId").val();
            var RoutestrategysIntraStaticRouteId = $("#RoutestrategysIntraStaticRouteId").val();
            var RoutestrategysInterStaticRouteId = $("#RoutestrategysInterStaticRouteId").val();
            if(RoutestrategysRouteTypeFlg != 2 && RoutestrategysRouteTypeFlg != 3 && !RoutestrategysDynamicRouteId){
                jGrowl_to_notyfy('<?php __('Dynamic Routing is required'); ?>', {theme: 'jmsg-error'});
                return false;
            }
            if((RoutestrategysRouteTypeFlg == 2 || RoutestrategysRouteTypeFlg == 4 || RoutestrategysRouteTypeFlg == 6) && !RoutestrategysStaticRouteId){
                jGrowl_to_notyfy('<?php __('Static Routing is required'); ?>', {theme: 'jmsg-error'});
                return false;
            }
            if((RoutestrategysRouteTypeFlg == 3 || RoutestrategysRouteTypeFlg == 5 || RoutestrategysRouteTypeFlg == 7) && !RoutestrategysIntraStaticRouteId){
                jGrowl_to_notyfy('<?php __('Intra Static Routing is required'); ?>', {theme: 'jmsg-error'});
                return false;
            }
            if((RoutestrategysRouteTypeFlg == 3 || RoutestrategysRouteTypeFlg == 5 || RoutestrategysRouteTypeFlg == 7) && !RoutestrategysInterStaticRouteId){
                jGrowl_to_notyfy('<?php __('Inter Static Routing is required'); ?>', {theme: 'jmsg-error'});
                return false;
            }

            var flg = $("#RoutestrategysSaveRoutePanelForm").validationEngine('validate');
            if(!flg){
                return false;
            }

            var RoutestrategysAniMinLength = $("#RoutestrategysAniMinLength").val();
            var RoutestrategysAniMaxLength = $("#RoutestrategysAniMaxLength").val();
            var RoutestrategysDigitsMinLength = $("#RoutestrategysDigitsMinLength").val();
            var RoutestrategysDigitsMaxLength = $("#RoutestrategysDigitsMaxLength").val();
            if(RoutestrategysAniMinLength && RoutestrategysAniMaxLength) {
                if (parseInt(RoutestrategysAniMinLength) > parseInt(RoutestrategysAniMaxLength)) {
                    jGrowl_to_notyfy('<?php printf(__('[%s]must Greater than[%s]', true), __('ANI Max Length',true),__('ANI Min Length',true)); ?>', {theme: 'jmsg-error'});
                    return false;
                }
            }
            if(RoutestrategysDigitsMinLength && RoutestrategysDigitsMaxLength) {
                if (parseInt(RoutestrategysDigitsMinLength) > parseInt(RoutestrategysDigitsMaxLength)) {
                    jGrowl_to_notyfy('<?php printf(__('[%s]must Greater than[%s]', true), __('DNIS Max Length',true),__('DNIS Min Length',true)); ?>', {theme: 'jmsg-error'});
                    return false;
                }
            }

//            $("#RoutestrategysSaveRoutePanelForm").submit();
            $.ajax({
                url: "<?php echo $this->webroot ?>routestrategys/save_route_panel?is_ajax=1",
                type: 'post',
                dataType: 'text',
                data: $('#RoutestrategysSaveRoutePanelForm').serialize(),
                success: function(data) {
                    console.log(data);
                    if (data != 0)
                    {
                        $this.next().click();
                        jGrowl_to_notyfy('<?php __('Create Route success'); ?>', {theme: 'jmsg-success'});
                    }
                    else
                        jGrowl_to_notyfy('<?php __('Create Route failed'); ?>', {theme: 'jmsg-error'});
                }
            });

        });



        $('#assign_ids, #assign_agent_ids').multiSelect({
            selectableHeader: "<div class='custom-header'>Optional Selection</div>",
            selectionHeader: "<div class='custom-header'>Selected Selection</div>"
//            selectableFooter: "<div class='custom-header custom-footer'>Selectable footer</div>",
//            selectionFooter: "<div class='custom-header custom-footer'>Selection footer</div>"
        });

        $('#is_private').change(function(){
            if($(this).is(':checked')){
                $('.assign').show();
            } else {
                $('.assign').hide();
            }
        }).trigger('change');
    })
</script>




