<?php echo $form->create('Routestrategys',array('action'=>'save_route_panel'))?>
<?php echo $xform->input('route_id',array('type'=>'hidden'))?>
<?php echo $xform->input('route_type_flg',array('type'=>'hidden'))?>
<?php echo $xform->input('route_strategy_id',array('type'=>'hidden','value'=>$route_strategy_id))?>
<table class="footable table tableTools table-bordered  table-white  footable-loaded">
    <thead></thead>
    <tr>
        <td class="right"><?php __('ANI Prefix'); ?></td>
        <td><?php echo $xform->input('ani_prefix',array('maxlength'=>256, 'class'=>'width220','type'=>'text'))?></td>
    </tr>
    <tr>
        <td class="right"><?php __('DNIS Prefix'); ?></td>
        <td><?php echo $xform->input('digits',array('maxlength'=>256, 'class'=>'width220','type'=>'text'))?></td>
    </tr>
    <tr>
        <td class="right"><?php __('Route Type'); ?></td>
        <td>
            <select id="RoutestrategysRouteType" name="data[Routestrategys][route_type]">
                <?php foreach ($route_type as $key => $item): ?>
                    <option flg="<?php echo $item['flg']; ?>"
                        <?php if(!strcmp($item['flg'],$this->data['Routestrategys']['route_type_flg'])): ?>
                            selected="selected"
                        <?php endif; ?>
                            value="<?php echo $item['value']; ?>">
                        <?php echo $key; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </td>
    </tr>
    <tr>
        <td class="right"><?php __('staroute'); ?></td>
        <td>
            <?php echo $form->input('static_route_id',array('type'=> 'select','empty' => '','options' => array(),
                'class'=>'js-example-basic-multiple','label' => false,'div' => false))?>
        </td>
    </tr>
    <tr>
        <td class="right"><?php __('dyroute'); ?></td>
        <td><?php echo $form->input('dynamic_route_id',array('type'=> 'select','empty' => '','options' => array(),
                'class'=>'js-example-basic-multiple','label' => false,'div' => false))?>
        </td>
    </tr>
    <tr>
        <td class="right"><?php __('Intra Static Route'); ?></td>
        <td><?php echo $form->input('intra_static_route_id',array('type'=> 'select','empty' => '','options' => array(),
                'class'=>'js-example-basic-multiple','label' => false,'div' => false))?></td>
    </tr>
    <tr>
        <td class="right"><?php __('Inter Static Route'); ?></td>
        <td><?php echo $form->input('inter_static_route_id',array('type'=> 'select','empty' => '','options' => array(),
                'class'=>'js-example-basic-multiple','label' => false,'div' => false))?></td>
    </tr>

    <!--
    <tr>
        <td class="right"><?php __('staroute'); ?></td>
        <td>
            <?php echo $form->input('static_route_id',array('type'=> 'hidden','empty' => '',
                'class'=>'js-example-basic-multiple','label' => false,'div' => false))?>
        </td>
    </tr>
    <tr>
        <td class="right"><?php __('dyroute'); ?></td>
        <td><?php echo $form->input('dynamic_route_id',array('type'=> 'hidden','empty' => '',
                'class'=>'js-example-basic-multiple','label' => false,'div' => false))?>
        </td>
    </tr>
    <tr>
        <td class="right"><?php __('Intra Static Route'); ?></td>
        <td><?php echo $form->input('intra_static_route_id',array('type'=> 'hidden','empty' => '',
                'class'=>'js-example-basic-multiple','label' => false,'div' => false))?></td>
    </tr>
    <tr>
        <td class="right"><?php __('Inter Static Route'); ?></td>
        <td><?php echo $form->input('inter_static_route_id',array('type'=> 'hidden','empty' => '',
                'class'=>'js-example-basic-multiple','label' => false,'div' => false))?></td>
    </tr>
    -->
    <tr>
        <td class="right"><?php __('ANI Min Length'); ?></td>
        <td><?php echo $xform->input('ani_min_length',array('class'=>'width220 validate[custom[onlyNumber]]'))?></td>
    </tr>
    <tr>
        <td class="right"><?php __('ANI Max Length'); ?></td>
        <td><?php echo $xform->input('ani_max_length',array('class'=>'width220 validate[custom[onlyNumber]]'))?></td>
    </tr>

    <tr>
        <td class="right"><?php __('DNIS Min Length'); ?></td>
        <td><?php echo $xform->input('digits_min_length',array('class'=>'width220 validate[custom[onlyNumber]]'))?></td>
    </tr>
    <tr>
        <td class="right"><?php __('DNIS Max Length'); ?></td>
        <td><?php echo $xform->input('digits_max_length',array('class'=>'width220 validate[custom[onlyNumber]]'))?></td>
    </tr>
</table>
<?php echo $form->end()?>

<script type="text/javascript">
    $(function(){
//        $("#RoutestrategysStaticRouteId").select2();
//        $("#RoutestrategysDynamicRouteId").select2();
//        $("#RoutestrategysIntraStaticRouteId").select2();
//        $("#RoutestrategysInterStaticRouteId").select2();
//
//        $('#myModal_AddRoute').on('hide.bs.modal', function () {
//            // 执行一些动作...
//            $("#RoutestrategysStaticRouteId").select2('close');
//            $("#RoutestrategysDynamicRouteId").select2('close');
//            $("#RoutestrategysIntraStaticRouteId").select2('close');
//            $("#RoutestrategysInterStaticRouteId").select2('close');
//        });

        $("#RoutestrategysRouteType").change(function(){
            var $dy_route = $("#RoutestrategysDynamicRouteId").closest('tr');
            var $st_route = $("#RoutestrategysStaticRouteId").closest('tr');
            var $intra_route = $("#RoutestrategysIntraStaticRouteId").closest('tr');
            var $inter_route = $("#RoutestrategysInterStaticRouteId").closest('tr');
            /*var $dy_route_self = $("#RoutestrategysDynamicRouteId");
             var $st_route_self = $("#RoutestrategysStaticRouteId");
             var $intra_route_self = $("#RoutestrategysIntraStaticRouteId");
             var $inter_route_self = $("#RoutestrategysInterStaticRouteId");*/
            var flg = $(this).find(":selected").attr('flg');
            flg = parseInt(flg);
            $("#RoutestrategysRouteTypeFlg").val(flg);
            switch (flg){
                case 1 :
                    $dy_route.show();
                    $st_route.hide();
                    $intra_route.hide();
                    $inter_route.hide();

                    break;
                case 2:
                    $dy_route.hide();
                    $st_route.show();
                    $intra_route.hide();
                    $inter_route.hide();
                    break;
                case 3:
                    $dy_route.hide();
                    $st_route.hide();
                    $intra_route.show();
                    $inter_route.show();
                    break;
                case 4:
                case 6:
                    $dy_route.show();
                    $st_route.show();
                    $intra_route.hide();
                    $inter_route.hide();
                    break;
                case 5:
                case 7:
                    $dy_route.show();
                    $st_route.hide();
                    $intra_route.show();
                    $inter_route.show();
                    break;
            };
        }).trigger('change');
        $("#RoutestrategysStaticRouteId").select2({
            minimumInputLength: 0,
            initSelection: function (element, callback) {   // 初始化时设置默认值
                var $st_route_name = $("#myModal_AddRoute").find(".st_route_name").val();
                var data = [{id: element.val(), text: $st_route_name}];
                callback({id: element.val(), text: $st_route_name});
            },
            id : function(item) {
                return item.id;
            },
            templateSelection: function (item) { return item.text || item.id; },
            templateResult: function (item) { return item.text; },
            ajax : {
                url      : "<?php echo $this->webroot; ?>routestrategys/ajax_get_static_route",
                dataType : "json",
                quietMillis:500,
                data: function (params) {
                    return {
                        search: params.term, // search term
                        page: params.page
                    };
                },
                processResults: function (data,params) {
                    params.page = params.page || 1;
                    var myResults = [];
                    $.each(data.result, function (index, item) {
                        myResults.push({
                            'id': item.Product.product_id,
                            'text': item.Product.name
                        });
                    });
                    return {
                        results: myResults,
                        pagination: {
                            more: (params.page * data.limit) < data.total_count
                        }
                    };
                },
                escapeMarkup : function (m) { return m; }
            }
        });
        $("#RoutestrategysIntraStaticRouteId").select2({
            minimumInputLength: 0,
            initSelection: function (element, callback) {   // 初始化时设置默认值
                var $intra_route_name = $("#myModal_AddRoute").find(".intra_route_name").val();
                var data = [{id: element.val(), text: $intra_route_name}];
                callback({id: element.val(), text: $intra_route_name});
            },
            id : function(item) {
                return item.id;
            },
            templateSelection: function (item) { return item.text || item.id; },
            templateResult: function (item) { return item.text; },
            ajax : {
                url      : "<?php echo $this->webroot; ?>routestrategys/ajax_get_static_route",
                dataType : "json",
                quietMillis:500,
                data: function (params) {
                    return {
                        search: params.term, // search term
                        page: params.page
                    };
                },
                processResults: function (data,params) {
                    params.page = params.page || 1;
                    var myResults = [];
                    $.each(data.result, function (index, item) {
                        myResults.push({
                            'id': item.Product.product_id,
                            'text': item.Product.name
                        });
                    });
                    return {
                        results: myResults,
                        pagination: {
                            more: (params.page * data.limit) < data.total_count
                        }
                    };
                },
                escapeMarkup : function (m) { return m; }
            }
        });
        $("#RoutestrategysInterStaticRouteId").select2({
            minimumInputLength: 0,
            initSelection: function (element, callback) {   // 初始化时设置默认值
                var $inter_route_name = $("#myModal_AddRoute").find(".inter_route_name").val();
                var data = [{id: element.val(), text: $inter_route_name}];
                callback({id: element.val(), text: $inter_route_name});
            },
            id : function(item) {
                return item.id;
            },
            templateSelection: function (item) { return item.text || item.id; },
            templateResult: function (item) { return item.text; },
            ajax : {
                url      : "<?php echo $this->webroot; ?>routestrategys/ajax_get_static_route",
                dataType : "json",
                quietMillis:500,
                data: function (params) {
                    return {
                        search: params.term, // search term
                        page: params.page
                    };
                },
                processResults: function (data,params) {
                    params.page = params.page || 1;
                    var myResults = [];
                    $.each(data.result, function (index, item) {
                        myResults.push({
                            'id': item.Product.product_id,
                            'text': item.Product.name
                        });
                    });
                    return {
                        results: myResults,
                        pagination: {
                            more: (params.page * data.limit) < data.total_count
                        }
                    };
                },
                escapeMarkup : function (m) { return m; }
            }
        });

        $("#RoutestrategysDynamicRouteId").select2({
            minimumInputLength: 0,
            initSelection: function (element, callback) {   // 初始化时设置默认值
                var $dy_route_name = $("#myModal_AddRoute").find(".dy_route_name").attr('title');
                var data = [{id: element.val(), text: $dy_route_name}];
                callback({id: element.val(), text: $dy_route_name});
            },
            id : function(item) {
                return item.id;
            },
            templateSelection: function (item) { return item.text || item.id; },
            templateResult: function (item) { return item.text; },
            ajax : {
                url      : "<?php echo $this->webroot; ?>routestrategys/ajax_get_dynamic_route",
                dataType : "json",
                quietMillis:500,
                data: function (params) {
                    return {
                        search: params.term, // search term
                        page: params.page
                    };
                },
                processResults: function (data,params) {
                    params.page = params.page || 1;
                    var myResults = [];
                    $.each(data.result, function (index, item) {
                        myResults.push({
                            'id': item.DynamicRoute.dynamic_route_id,
                            'text': item.DynamicRoute.name
                        });
                    });
                    return {
                        results: myResults,
                        pagination: {
                            more: (params.page * data.limit) < data.total_count
                        }
                    };
                },
                escapeMarkup : function (m) { return m; }
            }
        });

    })
</script>
<style>
    .select2-container{
        width: 220px;
    }

</style>