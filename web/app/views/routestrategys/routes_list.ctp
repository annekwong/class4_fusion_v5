<style type="text/css">
    .height20 {
        height:20px;
    }
    .width80 {
        width:80px;
    }
    .in-text {
        width:80px;
    }
</style>

<ul class="breadcrumb">
    <li><?php __('You are here'); ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Routing') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Routing Plan') ?><font  class="editname" title="Name">
            <?php echo empty($rs_name[0][0]['name']) || $rs_name[0][0]['name'] == '' ? '' : "[" . $rs_name[0][0]['name'] . "]"; ?>
        </font></li>
</ul>

<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
    <?php
    if ($_SESSION['role_menu']['Routing']['routestrategys']['model_w'])
    {
        ?>
        <a class="btn btn-primary btn-icon glyphicons circle_plus" id="add_route" href="#myModal_AddRoute" data-toggle="modal"><i></i> <?php __('Create New') ?></a>
        <a class="link_btn delete_selected btn btn-primary btn-icon glyphicons remove" onclick="deleteAll('<?php echo $this->webroot ?>routestrategys/del_route/all/<?php echo $id ?>');" href="###"><i></i> <?php echo __('Delete All') ?></a>
        <a class="link_btn delete_selected btn btn-primary btn-icon glyphicons remove" onclick="deleteSelected('rec_strategy', '<?php echo $this->webroot ?>routestrategys/del_route/selected/<?php echo $id ?>');" href="###"><i></i> <?php echo __('Delete Selected') ?></a>
    <?php } ?>
    <a class="btn btn-icon btn-inverse glyphicons circle_arrow_left" href="<?php echo $this->webroot ?>routestrategys/strategy_list"><i></i> <?php __('Back'); ?></a>
</div>
<div class="clearfix"></div>
<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-head">
            <?php echo $this->element('uploads/route_plan_tabs',array('active' => 'list')) ?>
        </div>

        <div class="widget-body">

            <form method="get">
                <div class="filter-bar">
                    <!-- Filter -->
                    <div>
                        <label><?php __('Search') ?>:</label>
                        <input type="text" id="search-_q" class="in-search default-value input defaultText" title="<?php echo __('namesearch') ?>" value="<?php if (!empty($search)) echo $search; ?>" name="search">
                    </div>
                    <!-- // Filter END -->

                    <!-- Filter -->
                    <div>
                        <button name="submit" class="btn query_btn"><?php __('Query') ?></button>
                    </div>
                    <!-- // Filter END -->
                </div>
                <div class="clearfix"></div>
            </form>

            <div class="clearfix"></div>
            <?php $d = $p->getDataArray(); ?>
            <?php if (count($d) == 0): ?>
                <h2 class="msg center"  id="msg_div"><?php echo __('no_data_found') ?></h2>
                <div  id="list_div"  style="display: none;" class="overflow_x">
                    <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
                        <thead>
                        <tr>
                            <th rowspan="2" class="footable-first-column expand" data-class="expand"><input type="checkbox" onClick="checkAllChecks(this, 'rec_strategy');" value=""/></th>
                            <th rowspan="2"><?php echo $appCommon->show_order('route_id', __('ID', true)) ?></th>
                            <th colspan="2"><?php __('Match Prefix'); ?></th>
                            <th rowspan="2"><?php echo $appCommon->show_order('route_type', __('Route Type', true)) ?></th>
                            <th colspan="4" ><?php __('Routing'); ?></th>
                            <th colspan="2">
                                <?php __('ANI Length') ?>
                            </th>
                            <th colspan="2">
                                <?php __('DNIS Length') ?>
                            </th>
                            <th rowspan="2" data-hide="phone,tablet"  style="display: table-cell;"><?php echo __('Update At', true) ?></th>
                            <?php if ($_SESSION['role_menu']['Routing']['routestrategys']['model_w']): ?>
                                <th rowspan="2" data-hide="phone,tablet"  style="display: table-cell;"><?php echo __('Update By', true) ?></th>
                                <th rowspan="2" data-hide="phone,tablet" class="footable-last-column"  style="display: table-cell;"><?php echo __('Action') ?></th>
                            <?php else: ?>
                                <th rowspan="2" data-hide="phone,tablet" class="footable-last-column" style="display: table-cell;"><?php echo __('Update By', true) ?></th>
                            <?php endif; ?>
                        </tr>
                        <tr>
                            <th><?php echo $appCommon->show_order('ani_prefix', __('ANI', true)) ?></th>
                            <th><?php echo $appCommon->show_order('digits', __('DNIS', true)) ?></th>
                            <th><?php echo $appCommon->show_order('static_route', __('Static', true)) ?></th>
                            <th><?php echo $appCommon->show_order('dynamic_route', __('Dynamic', true)) ?></th>
                            <th ><?php echo $appCommon->show_order('intra_static_route_id', __('Intra', true)) ?></th>
                            <th><?php echo $appCommon->show_order('inter_static_route_id', __('Inter', true)) ?></th>
                            <th>
                                <?php echo $appCommon->show_order('ani_min_length', __('Min', true)) ?>
                            </th>
                            <th>
                                <?php echo $appCommon->show_order('ani_max_length', __('Max', true)) ?>
                            </th>
                            <th>
                                <?php echo $appCommon->show_order('digits_min_length', __('Min', true)) ?>
                            </th>
                            <th>
                                <?php echo $appCommon->show_order('digits_max_length', __('Max', true)) ?>
                            </th>
                        </tr>
                        </thead>
                        <tbody id="rec_strategy">

                        </tbody>
                    </table>
                    <div class="separator"></div>
                </div>
            <?php else: ?>
                <h2 class="msg center"  id="msg_div" style="display: none;"><?php __('no_data_found') ?></h2>
                <div   id="list_div" class="overflow_x">
                    <div class="clearfix"></div>
                    <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
                        <thead>
                        <tr>
                            <th rowspan="2" class="footable-first-column expand" data-class="expand"><input type="checkbox" onClick="checkAllChecks(this, 'rec_strategy');" value=""/></th>
                            <th rowspan="2"><?php echo $appCommon->show_order('route_id', __('ID', true)) ?></th>
                            <th colspan="2"><?php __('Match Prefix'); ?></th>
                            <th rowspan="2"><?php echo $appCommon->show_order('route_type', __('Route Type', true)) ?></th>
                            <th colspan="4" ><?php __('Routing'); ?></th>
                            <th colspan="2">
                                <?php __('ANI Length') ?>
                            </th>
                            <th colspan="2">
                                <?php __('DNIS Length') ?>
                            </th>
                            <th rowspan="2" data-hide="phone,tablet"  style="display: table-cell;"><?php echo __('Update At', true) ?></th>
                            <?php if ($_SESSION['role_menu']['Routing']['routestrategys']['model_w']): ?>
                                <th rowspan="2" data-hide="phone,tablet"  style="display: table-cell;"><?php echo __('Update By', true) ?></th>
                                <th rowspan="2" data-hide="phone,tablet" class="footable-last-column"  style="display: table-cell;"><?php echo __('Action') ?></th>
                            <?php else: ?>
                                <th rowspan="2" data-hide="phone,tablet" class="footable-last-column" style="display: table-cell;"><?php echo __('Update By', true) ?></th>
                            <?php endif; ?>
                        </tr>
                        <tr>
                            <th><?php echo $appCommon->show_order('ani_prefix', __('ANI', true)) ?></th>
                            <th><?php echo $appCommon->show_order('digits', __('DNIS', true)) ?></th>
                            <th><?php echo $appCommon->show_order('static_route', __('Static', true)) ?></th>
                            <th><?php echo $appCommon->show_order('dynamic_route', __('Dynamic', true)) ?></th>
                            <th ><?php echo $appCommon->show_order('intra_static_route_id', __('Intra', true)) ?></th>
                            <th><?php echo $appCommon->show_order('inter_static_route_id', __('Inter', true)) ?></th>
                            <th>
                                <?php echo $appCommon->show_order('ani_min_length', __('Min', true)) ?>
                            </th>
                            <th>
                                <?php echo $appCommon->show_order('ani_max_length', __('Max', true)) ?>
                            </th>
                            <th>
                                <?php echo $appCommon->show_order('digits_min_length', __('Min', true)) ?>
                            </th>
                            <th>
                                <?php echo $appCommon->show_order('digits_max_length', __('Max', true)) ?>
                            </th>
                        </tr>
                        </thead>
                        <tbody id="rec_strategy">
                        <?php
                        $mydata = $p->getDataArray();
                        $loop = count($mydata);
                        for ($i = 0; $i < $loop; $i++)
                        {
                            ?>
                            <tr data-class="expand" class="row-1">
                                <td class="footable-first-column expandrow-1" style="text-align:center"><input type="checkbox" value="<?php echo $mydata[$i][0]['route_id'] ?>"/></td>
                                <td><?php echo $mydata[$i][0]['route_id'] ?></td>
                                <td><?php echo $mydata[$i][0]['ani_prefix'] ?></td>
                                <td><?php echo $mydata[$i][0]['digits'] ?></td>
                                <td>
                                    <?php if(isset($route_type[$mydata[$i][0]['route_type_flg']]))
                                    {
                                        echo $route_type[$mydata[$i][0]['route_type_flg']];
                                    }else
                                    {
                                        if ($mydata[$i][0]['route_type'] == 1)
                                        {
                                            echo __('dyroute', true);
                                        }
                                        else if ($mydata[$i][0]['route_type'] == 2)
                                        {
                                            echo __('staroute', true);
                                        }
                                        else if ($mydata[$i][0]['route_type'] == 3)
                                        {
                                            echo __('stfirst', true);
                                        }
                                        else
                                        {
                                            echo __('dyfirst', true);
                                        }
                                    } ?>
                                </td>
                                <?php if ($mydata[$i][0]['route_type_flg']): ?>
                                    <td class="st_route_name" itemvalue="<?php echo $mydata[$i][0]['static_route_id'] ?>">
                                        <?php if(in_array($mydata[$i][0]['route_type_flg'],array(2,4,5,6))): ?>
                                            <?php echo $mydata[$i][0]['static_route'] ?>
                                        <?php endif; ?>
                                    </td>
                                    <td class="dy_route_name" itemvalue="<?php echo $mydata[$i][0]['dynamic_route_id'] ?>">
                                        <?php if(in_array($mydata[$i][0]['route_type_flg'],array(1,4,5,6,7))): ?>
                                            <?php echo $mydata[$i][0]['dynamic_route'] ?>
                                        <?php endif; ?>
                                    </td>
                                    <td class="intra_route_name" itemvalue="<?php echo $mydata[$i][0]['intra_static_route_id'] ?>">
                                        <?php if(in_array($mydata[$i][0]['route_type_flg'],array(3,5,7))): ?>
                                            <?php echo $mydata[$i][0]['intra_static_route'] ?>
                                        <?php endif; ?>
                                    </td>
                                    <td class="inter_route_name" itemvalue="<?php echo $mydata[$i][0]['inter_static_route_id'] ?>">
                                        <?php if(in_array($mydata[$i][0]['route_type_flg'],array(3,5,7))): ?>
                                            <?php echo $mydata[$i][0]['inter_static_route'] ?>
                                        <?php endif; ?>
                                    </td>
                                <?php else: ?>
                                    <td class="st_route_name" itemvalue="<?php echo $mydata[$i][0]['static_route_id'] ?>">
                                        <?php if ($mydata[$i][0]['route_type'] != 1): ?>
                                            <?php echo $mydata[$i][0]['static_route'] ?>
                                        <?php endif; ?>
                                    </td>
                                    <td class="dy_route_name" itemvalue="<?php echo $mydata[$i][0]['dynamic_route_id'] ?>">
                                        <?php if ($mydata[$i][0]['route_type'] != 2): ?>
                                            <?php echo $mydata[$i][0]['dynamic_route'] ?>
                                        <?php endif; ?>
                                    </td>
                                    <td class="intra_route_name" itemvalue="<?php echo $mydata[$i][0]['intra_static_route_id'] ?>">
                                        <?php if ($mydata[$i][0]['route_type'] != 1): ?>
                                            <?php echo $mydata[$i][0]['intra_static_route'] ?>
                                        <?php endif; ?>
                                    </td>
                                    <td class="inter_route_name" itemvalue="<?php echo $mydata[$i][0]['inter_static_route_id'] ?>">
                                        <?php if ($mydata[$i][0]['route_type'] != 1): ?>
                                            <?php echo $mydata[$i][0]['inter_static_route'] ?>
                                        <?php endif; ?>
                                    </td>
                                <?php endif; ?>
                                <td><?php echo $mydata[$i][0]['ani_min_length'] ?></td>
                                <td><?php echo $mydata[$i][0]['ani_max_length'] ?></td>
                                <td><?php echo $mydata[$i][0]['digits_min_length'] ?></td>
                                <td><?php echo $mydata[$i][0]['digits_max_length'] ?></td>
                                <td><?php echo $mydata[$i][0]['update_at'] ?></td>

                                <?php if ($_SESSION['role_menu']['Routing']['routestrategys']['model_w']): ?>
                                    <td><?php echo $mydata[$i][0]['update_by'] ?></td>
                                    <td class="footable-last-column" style="width:auto">
                                        <a title="<?php __('edit') ?>" route_id="<?php echo $mydata[$i][0]['route_id']; ?>" href="#myModal_AddRoute" class="edit_route" data-toggle="modal" >
                                            <i class="icon-edit"></i>
                                        </a>
                                        <a title="<?php echo __('del') ?>"  href="javascript:void(0)" onClick="ex_delConfirm(this, '<?php echo $this->webroot ?>routestrategys/del_route/<?php echo $mydata[$i][0]['route_id'] ?>/<?php echo $id ?>', 'routing <?php echo $mydata[$i][0]['digits'] ?>');"> <i class="icon-remove"></i> </a>
                                    </td>
                                <?php else: ?>
                                    <td class="footable-last-column"><?php echo $mydata[$i][0]['update_by'] ?></td>
                                <?php endif; ?>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                    <div class="separator"></div>
                </div>
                <div class="row-fluid">
                    <div class="pagination pagination-large pagination-right margin-none">
                        <?php echo $this->element('page'); ?>
                    </div>
                </div>
            <?php endif; ?>
            <div class="clearfix"></div>
        </div>
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
        <input type="hidden" class="dy_route_name" dy_route_id=""/>
        <input type="hidden" class="st_route_name" st_route_id=""/>
        <input type="hidden" class="intra_route_name" />
        <input type="hidden" class="inter_route_name" />
        <input type="button" id="add_route_submit" class="btn btn-primary" value="<?php __('Submit'); ?>">
        <a href="javascript:void(0)" id="add_route_close" data-dismiss="modal" class="btn btn-default"><?php __('Close'); ?></a>
    </div>

</div>
<!-- Add Route end -->
<script type="text/javascript">

    function checkAllChecks(obj, tabid) {
        if($(obj).attr('checked') == 'checked'){
            $('#rec_strategy input[type="checkbox"]').prop('checked',true);
        }else{
            $('#rec_strategy input[type="checkbox"]').prop('checked',false);
        }
    }
    function judge_num(value,msg)
    {
        if (/\D/.test(value)) {
            jGrowl_to_notyfy(msg + ',<?php __('Numbers only'); ?>', {theme: 'jmsg-error'});
            return false;
        }
        return true;
    }
    $(function(){



        $("#add_route").click(function(){
            $("#myModal_AddRoute").find(".modal-header h3").text('Add Route');
            $("#myModal_AddRoute").find(".widget-body").load('<?php echo $this->webroot ?>routestrategys/save_route_panel?routestrategys=<?php echo $this->params['pass'][0] ?>');
        });

        $(".edit_route").click(function(){
            var route_id = $(this).attr('route_id');
            $("#myModal_AddRoute").find(".widget-body").load('<?php echo $this->webroot ?>routestrategys/save_route_panel/'+ route_id +'?routestrategys=<?php echo $this->params['pass'][0] ?>');
            var $st_route_name = $(this).closest('tr').find(".st_route_name").html();
            var $st_route_id = $(this).closest('tr').find(".st_route_name").attr('itemvalue');
            var $dy_route_name = $(this).closest('tr').find(".dy_route_name").html();
            var $dy_route_id = $(this).closest('tr').find(".dy_route_name").attr('itemvalue');
            var $intra_route_name = $(this).closest('tr').find(".intra_route_name").html();
            var $inter_route_name = $(this).closest('tr').find(".inter_route_name").html();
            $("#myModal_AddRoute").find(".st_route_name").val($st_route_id);
            $("#myModal_AddRoute").find(".dy_route_name").val($dy_route_id);

            $("#myModal_AddRoute").find(".intra_route_name").val($intra_route_name);
            $("#myModal_AddRoute").find(".inter_route_name").val($inter_route_name);
            $("#myModal_AddRoute").find(".st_route_name").attr('title', $st_route_name);
            $("#myModal_AddRoute").find(".dy_route_name").attr('title', $dy_route_name);
            $("#myModal_AddRoute").find(".modal-header h3").text('Edit Route');
        });

        $("#add_route_submit").click(function() {
            var do_submit = true;
            var RoutestrategysRouteTypeFlg = $("#RoutestrategysRouteType").find('option:selected').attr('flg');
            var RoutestrategysIntraStaticRouteId = $("#RoutestrategysIntraStaticRouteId").val();
            var RoutestrategysInterStaticRouteId = $("#RoutestrategysInterStaticRouteId").val();
            if(!$("#RoutestrategysDynamicRouteId").val() && RoutestrategysRouteTypeFlg != 2 && RoutestrategysRouteTypeFlg != 3){
                $dy_route_val = $("#myModal_AddRoute").find(".dy_route_name").val();
                $dy_route_text = $("#myModal_AddRoute").find(".dy_route_name").attr('title');
                $("#RoutestrategysDynamicRouteId option").attr('value', $dy_route_val).text($dy_route_text);
            }
            if (!$("#RoutestrategysStaticRouteId").val() && (RoutestrategysRouteTypeFlg == 2 || RoutestrategysRouteTypeFlg == 4 || RoutestrategysRouteTypeFlg == 6)) {
                $st_route_val = $("#myModal_AddRoute").find(".st_route_name").val();
                $st_route_text = $("#myModal_AddRoute").find(".st_route_name").attr('title');
                $("#RoutestrategysStaticRouteId option").attr('value', $st_route_val).text($st_route_text);
            }

            var RoutestrategysDynamicRouteId = $("#RoutestrategysDynamicRouteId").val();
            var RoutestrategysStaticRouteId = $("#RoutestrategysStaticRouteId").val();

            if(RoutestrategysRouteTypeFlg != 2 && RoutestrategysRouteTypeFlg != 3 && !RoutestrategysDynamicRouteId){
                jGrowl_to_notyfy('<?php __('Dynamic Routing is required'); ?>', {theme: 'jmsg-error'});
                do_submit = false;
            }
            if((RoutestrategysRouteTypeFlg == 2 || RoutestrategysRouteTypeFlg == 4 || RoutestrategysRouteTypeFlg == 6) && !RoutestrategysStaticRouteId){
                jGrowl_to_notyfy('<?php __('Static Routing is required'); ?>', {theme: 'jmsg-error'});
                do_submit = false;
            }
            if((RoutestrategysRouteTypeFlg == 3 || RoutestrategysRouteTypeFlg == 5 || RoutestrategysRouteTypeFlg == 7) && !RoutestrategysIntraStaticRouteId){
                jGrowl_to_notyfy('<?php __('Intra Static Routing is required'); ?>', {theme: 'jmsg-error'});
                do_submit = false;
            }
            if((RoutestrategysRouteTypeFlg == 3 || RoutestrategysRouteTypeFlg == 5 || RoutestrategysRouteTypeFlg == 7) && !RoutestrategysInterStaticRouteId){
                jGrowl_to_notyfy('<?php __('Inter Static Routing is required'); ?>', {theme: 'jmsg-error'});
                do_submit = false;
            }

            var flg = $("#RoutestrategysSaveRoutePanelForm").validationEngine('validate');
            if(!flg){
                do_submit = false;
            }

            var RoutestrategysAniMinLength = $("#RoutestrategysAniMinLength").val();
            var RoutestrategysAniMaxLength = $("#RoutestrategysAniMaxLength").val();
            var RoutestrategysDigitsMinLength = $("#RoutestrategysDigitsMinLength").val();
            var RoutestrategysDigitsMaxLength = $("#RoutestrategysDigitsMaxLength").val();
            if(RoutestrategysAniMinLength && RoutestrategysAniMaxLength) {
                if (parseInt(RoutestrategysAniMinLength) > parseInt(RoutestrategysAniMaxLength)) {
                    jGrowl_to_notyfy('<?php printf(__('[%s]must Greater than[%s]', true), __('ANI Max Length',true),__('ANI Min Length',true)); ?>', {theme: 'jmsg-error'});
                    do_submit = false;
                }
            }
            if(RoutestrategysDigitsMinLength && RoutestrategysDigitsMaxLength) {
                if (parseInt(RoutestrategysDigitsMinLength) > parseInt(RoutestrategysDigitsMaxLength)) {
                    jGrowl_to_notyfy('<?php printf(__('[%s]must Greater than[%s]', true), __('DNIS Max Length',true),__('DNIS Min Length',true)); ?>', {theme: 'jmsg-error'});
                    do_submit = false;
                }
            }


            if (do_submit) {
                $("#RoutestrategysSaveRoutePanelForm").submit();
            }

        });
    })
</script>
