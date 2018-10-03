<style type="text/css">
    a:hover {
        text-decoration: none;
    }

    .footable-first-column{
        text-align: center !important;
    }

    #selectAll{
        position: relative;
        left: 3px;
    }
</style>
<script type="text/javascript">
    //提示消息居中显示
    jQuery.jGrowl.defaults.position = 'top-center';
</script>


<ul class="breadcrumb">
    <li><?php __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <?php if(strcmp($name[0][0]['name'],'ORIGINATION_STATIC_ROUTE') == 0): ?>
        <li><a href="<?php echo $this->webroot ?>products/route_info"><?php __('Origination ') ?></a></li>
        <li class="divider"><i class="icon-caret-right"></i></li>
        <li><a href="<?php echo $this->webroot ?>products/route_info"><?php __('DID Routing') ?></a></li>
    <?php else: ?>
    <li><a href="<?php echo $this->webroot ?>products/route_info"><?php __('Routing') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>products/route_info">
        <?php echo __('Static Route') ?> <?php echo empty($name[0][0]['name']) || $name[0][0]['name'] == '' ? '' : '[' . $name[0][0]['name'] . ']' ?></a></li>
    <?php endif; ?>
</ul>


<div class="heading-buttons">
    <?php if(strcmp($name[0][0]['name'],'ORIGINATION_STATIC_ROUTE') == 0): ?>
    <h4 class="heading"><?php echo __('DID Routing') ?></h4>
    <?php else: ?>
    <h4 class="heading"><?php echo __('Static Route') ?></h4>
    <?php endif; ?>

</div>
<div class="separator bottom"></div>
<?php if(strcmp($name[0][0]['name'],'ORIGINATION_STATIC_ROUTE')): ?>
    <div class="buttons pull-right newpadding">
        <?php
        if (isset($edit_return))
        {
            ?>
            <a class="link_back btn btn-icon btn-inverse glyphicons circle_arrow_left" href="<?php echo $this->webroot ?>products/route_info/<?php echo $id ?>"><i></i><?php echo __('gobackall') ?> </a>
        <?php } ?>

        <?php
        if ($_SESSION['role_menu']['Routing']['products']['model_w'])
        {
            ?>
            <a class="link_btn btn btn-primary btn-icon glyphicons circle_plus" id="add" href="javascript:void(0)"><i></i><?php echo __('createnew') ?></a>
            <a class="link_btn btn btn-primary btn-icon glyphicons remove" rel="popup" href="javascript:void(0)" onclick="deleteAll('<?php echo $this->webroot ?>products/delall?id=' +<?php echo $id ?>);" ><i></i><?php echo __('deleteall') ?></a>
            <a class="link_btn btn btn-primary btn-icon glyphicons remove" rel="popup" href="javascript:void(0)" onclick="ex_deleteSelected('routetab', '<?php echo $this->webroot ?>products/delselected?id=<?php echo $id ?>', 'static route');"><i></i><?php echo __('deleteselected') ?></a>
        <?php } ?>
        <a class="link_back btn btn-icon btn-inverse glyphicons circle_arrow_left" href="<?php echo $this->webroot ?>products/product_list"><i></i><?php echo __('goback') ?> </a>
    </div>
<?php endif; ?>
<div class="clearfix"></div>
<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">

        <?php
        if ($_SESSION['role_menu']['Routing']['products']['model_x'] && strcmp($name[0][0]['name'],'ORIGINATION_STATIC_ROUTE'))
        {
        ?>
        <div class="widget-head">
            <ul>
                <li class="active"><a href="<?php echo $this->webroot ?>products/route_info/<?php echo $id ?>" class="glyphicons list"><i></i> <?php echo __('List', true); ?></a></li>
                <li ><a href="<?php echo $this->webroot ?>uploads/static_route/<?php echo $id ?>" class="glyphicons upload"><i></i> <?php echo __('Import', true); ?></a></li>
                <li  ><a href="<?php echo $this->webroot ?>down/static_route/<?php echo $id ?>" class="glyphicons download"><i></i> <?php echo __('Export', true); ?></a></li>

            </ul>
        </div>
        <?php } ?>
        <div class="widget-body">
            <div class="filter-bar">
                <form method="get">
                    <!-- Filter -->
                    <div>
                        <label><?php __('Search'); ?>:</label>
                        <input type="text" id="search-_q" class="in-search default-value input in-text defaultText" title="<?php echo __('prefixsearch') ?>" value="<?php if (!empty($search)) echo $search; ?>" name="search">
                    </div>
                    <div>
                        <button name="submit" class="btn query_btn"><?php __('Query'); ?></button>
                    </div>
                </form>
            </div>
            <div class="clearfix"></div>
            <?php
            $d = $p->getDataArray();
            if (count($d) == 0)
            {
                ?>


                <h2 class="msg center"><?php echo __('no_data_found') ?></h2>
                <table id="mytable"  class="list footable table table-striped tableTools table-bordered  table-white table-primary" id="fdf" style="display:none">
                 <thead>
                <?php if(strcmp($name[0][0]['name'],'ORIGINATION_STATIC_ROUTE') == 0): ?>
                    <tr>
                        <th><?php echo $appCommon->show_order('digits', __('DID', true));?></th>
<!--                        <th colspan="4">--><?php //echo __('Vendor Trunk', true); ?><!--</th>-->
                        <th colspan="4"><?php echo __('Client Trunk', true); ?></th>
                        <th><?php echo __('Update At', true); ?></th>
                        <th><?php echo __('Update By', true); ?></th>
                        <th><?php echo __('action') ?></th>
                    </tr>
                <?php else: ?>
                    <tr>
                        <th><input id="selectAll" type="checkbox" onclick="checkAllOrNot(this, 'routetab');" value=""/></th>
                        <!--<th><?php echo $appCommon->show_order('item_id', __('ID', true)) ?></th>-->
                        <th><?php echo ($code_type == 0) ? $appCommon->show_order('digits', __('Code', true)) : "Code Name"; ?>
                        </th>
                        <!--<th><?php echo $appCommon->show_order('route_type', __('Type', true)) ?></th>-->
                        <th><?php echo $appCommon->show_order('strategy', __('Strategy', true)) ?></th>
                        <th><?php echo $appCommon->show_order('time_profile', __('Time Profile', true)) ?></th>
                        <th colspan="8"><?php echo __('Trunk List', true); ?></th>
                        <th><?php echo __('Update At', true); ?></th>
                        <th><?php echo __('action') ?></th>
                    </tr>
                <?php endif; ?>
                    </thead>
                    <tbody id="routetab">
                    </tbody>
                </table>
            <?php
            }
            else
            {
                ?>


                <div id="cover"></div>
                <div id="uploadroute"  style="display:none;height: auto;z-index:99;position: absolute;left:30%;top: 20%;" class="form_panel_upload">
                    <form action="<?php echo $this->webroot ?>products/upload/<?php echo $id ?>" method="post" enctype="multipart/form-data" id="productFile">
                        <span class="wordFont1 marginSpan1"><?php echo __('selectfile') ?>:</span>
                        <div style="height: 100px;" class="up_panel_upload">
                            <input style="margin-top:10px;" type="file" value="Upload" size="45" class="input in-text" id="browse" name="browse">
                            <div style="margin-top:20px;">
                                <input type="radio" title="This action takes each record from the csv and adds it to the table, if the prefix already exists then it will be replaced with the one contained in the table !" checked="" value="1" name="handleStyle">
                                <span><?php echo __('overwrite') ?></span>
                                <input style="margin-left:10px;" type="radio" title="This action will remove all matching prefixes from the table !" value="2" name="handleStyle">
                                <span><?php echo __('remove') ?></span>
                                <input style="margin-left:10px;" type="radio" title="This action will fresh prefixes from the table !" value="3" name="handleStyle">
                                <span><?php echo __('clearrefresh') ?></span>
                                <input style="margin-left:10px;" type="checkbox" checked onclick="if (this.value == 'false')
                                                this.value = 'true';
                                            else
                                                this.value = 'false';
                                            document.getElementById('isRoll').value = this.value;">
                                <input type="hidden" value="true" name="isRoll" id="isRoll"/>
                                <span><?php echo __('rollbackonfail') ?> </span> </div>
                        </div>
                        <div class="form_panel_button_upload"> <span style="float:left"> <?php echo __('downloadtempfile') ?><a href="<?php echo $this->webroot ?>products/downloadtemplate" style="color:red"><?php echo __('clickhere') ?></a></span>
                            <input type="submit" class="input in-button" value="<?php echo __('upload') ?>"/>
                            <input type="button" onclick="closeCover('uploadroute')" style="margin-bottom:6px;" class="input in-button" value="<?php echo __('cancel') ?>"/>
                        </div>
                    </form>
                </div>
                <div id="uploadroute_error"  style="display:none;height: auto;z-index:99;position: absolute;left:30%;top: 20%;" class="form_panel_upload"> <span class="wordFont1 marginSpan1"><span style="color:red" id="affectrows"></span>&nbsp;&nbsp;<?php echo __('erroroccured') ?>:</span>
                    <div style="height: auto;text-align:left;" id="route_upload_errorMsg" class="up_panel_upload"></div>
                    <div class="form_panel_button_upload"> <span style="float:left"><?php echo __('downloadtempfile') ?> .<a href="<?php echo $this->webroot ?>products/downloadtemplate" style="color:red"><?php echo __('clickhere') ?></a></span>
                        <input type="button" onclick="closeCover('uploadroute_error')" style="margin-bottom:6px;" class="input in-button" value="<?php echo __('close') ?>"/>
                    </div>
                </div>
                <table id="mytable" class="list footable table table-striped tableTools table-bordered  table-white table-primary">
                    <thead>
                    <?php if(strcmp($name[0][0]['name'],'ORIGINATION_STATIC_ROUTE') == 0): ?>
                        <tr>
                            <th><?php echo $appCommon->show_order('digits', __('DID', true));?></th>
<!--                            <th>--><?php //echo __('Vendor Trunk', true); ?><!--</th>-->
                            <th><?php echo __('Client Trunk', true); ?></th>
                            <th><?php echo __('Update At', true); ?></th>
                            <!--th><?php echo __('Update By', true); ?></th-->
                            <th><?php echo __('action') ?></th>
                        </tr>
                    <?php else: ?>
                        <tr>
                            <th><input id="selectAll" type="checkbox" onclick="checkAllOrNot(this, 'routetab');" value=""/></th>
                            <!--<th><?php echo $appCommon->show_order('item_id', __('ID', true)) ?></th>-->
                            <th><?php echo ($code_type == 0) ? $appCommon->show_order('digits', __('Code', true)) : "Code Name"; ?>
                            </th>
                            <!--<th><?php echo $appCommon->show_order('route_type', __('Type', true)) ?></th>-->
                            <th><?php echo $appCommon->show_order('strategy', __('Strategy', true)) ?></th>
                            <th><?php echo $appCommon->show_order('time_profile', __('Time Profile', true)) ?></th>
                            <th colspan="8"><?php echo __('Trunk List', true); ?></th>
                            <th><?php echo __('Update At', true); ?></th>
                            <th><?php echo __('action') ?></th>
                        </tr>
                    <?php endif; ?>
                    </thead>
                    <tbody id="routetab">

                <?php if(strcmp($name[0][0]['name'],'ORIGINATION_STATIC_ROUTE') == 0): ?>
                    <?php
                    $mydata = $p->getDataArray();
                    //var_dump($mydata);
                    $loop = count($mydata);
                    for ($i = 0; $i < $loop; $i++)
                    {
                        ?>
                        <tr class="row-1">
                            <td><?php echo $mydata[$i][0]['digits']; ?></td>
<!--                            <td>-->
<!--                                --><?php
//                                $trunk_list_str = str_replace('"', '', trim($mydata[$i][0]['ingress_alias'], '{}'));
//                                if (strlen($trunk_list_str) > 20)
//                                    $trunk_show = substr($trunk_list_str,0,20)."...";
//                                else
//                                    $trunk_show = $trunk_list_str;
//                                $trunk_list_title = str_replace(',', '<br />', $trunk_list_str);
//                                ?>
<!--                                <a href="#myModal_trunkList" class="trunk_list" data-toggle="modal" mydata="--><?php //echo $trunk_list_title; ?><!--">-->
<!--                                    --><?php //echo $trunk_show; ?>
<!--                                </a>-->
<!--                            </td>-->
                            <td>
                                <?php
                                $trunk_list_str = isset($mydata[$i][0]['egress_alias']) ? str_replace('"', '', trim($mydata[$i][0]['egress_alias'], '{}')) : '';
                                if (strlen($trunk_list_str) > 20)
                                    $trunk_show = substr($trunk_list_str,0,20)."...";
                                else
                                    $trunk_show = $trunk_list_str;
                                $trunk_list_title = str_replace(',', '<br />', $trunk_list_str);
                                ?>
                                <a href="#myModal_trunkList" class="trunk_list" data-toggle="modal" mydata="<?php echo $trunk_list_title; ?>">
                                    <?php echo $trunk_show; ?>
                                </a>
                            </td>
                            <td><?php echo $mydata[$i][0]['update_at'] ?></td>
                            <!--td><?php echo $mydata[$i][0]['update_by'] ?></td-->
                            <td class="last">





                                <!--a class="qos_edit" item="<?php echo $mydata[$i][0]['item_id'] ?>" href="#myModal_qos_parameters" data-toggle="modal" title="QoS parameters">
                                    <i class="icon-adjust"></i>
                                </a-->

                                <a title="<?php echo __('Delete') ?>" href="javascript:void(0)" onclick="ex_delConfirm(this, '<?php echo $this->webroot ?>products/del/<?php echo $mydata[$i][0]['item_id']; ?>/<?php echo $id ?>', 'static route  <?php echo $code_type == 0 ? $mydata[$i][0]['digits'] : $mydata[$i][0]['code_name'] ?>');">
                                    <i class="icon-remove"></i>
                                </a>
                            </td>
                        </tr>
                    <?php } ?>
                <?php else: ?>
                    <?php
                    $mydata = $p->getDataArray();
                    //var_dump($mydata);
                    $loop = count($mydata);
                    for ($i = 0; $i < $loop; $i++)
                    {
                        ?>
                        <tr class="row-1">
                            <td style="text-align:center"><input class="select chkitem"  type="checkbox" value="<?php echo $mydata[$i][0]['item_id'] ?>"/></td>
                            <!--<td class="in-decimal"><?php echo $mydata[$i][0]['item_id'] ?></td>-->
                            <td>
                                <?php
                                if ($code_type == 0)
                                {
                                    echo $mydata[$i][0]['digits'];
                                }
                                else
                                {
                                    echo $mydata[$i][0]['code_name'];
                                }
                                ?>
                            </td>
                            <td><?php echo _filter_array(Array(1 => 'Top-Down', 0 => 'By Percentage', 2 => 'Round-Robin'), $mydata[$i][0]['strategy']) ?></td>
                            <td><?php echo $mydata[$i][0]['time_profile'] ?></td>
                            <td colspan="8">
                                <?php
                                $trunk_list_str = isset($mydata[$i][0]['egress_alias']) ? str_replace('"', '', trim($mydata[$i][0]['egress_alias'], '{}')) : '';
                                if (strlen($trunk_list_str) > 20)
                                    $trunk_show = substr($trunk_list_str,0,20)."...";
                                else
                                    $trunk_show = $trunk_list_str;
                                $trunk_list_title = str_replace(',', '<br />', $trunk_list_str);
                                ?>
                                <a href="#myModal_trunkList" class="trunk_list" data-toggle="modal" mydata="<?php echo $trunk_list_title; ?>">
                                    <?php echo $trunk_show; ?>
                                </a>
                            </td>
                            <td><?php echo $mydata[$i][0]['update_at'] ?></td>
                            <!--td><?php echo $mydata[$i][0]['update_by'] ?></td-->
                            <?php
                            if ($_SESSION['role_menu']['Routing']['products']['model_w'] && strcmp($name[0][0]['name'],'ORIGINATION_STATIC_ROUTE'))
                            {
                                ?>
                                    <td class="last">
                                        <!--a class="qos_edit" item="<?php echo $mydata[$i][0]['item_id'] ?>" href="#myModal_qos_parameters" data-toggle="modal" title="QoS parameters">
                                            <i class="icon-adjust"></i>
                                        </a-->
                                        <a title="Edit"  href="###"><i class="icon-edit"></i> </a>
                                        <?php
                                if ($code_type == 0)
                                {
                                    ?>
                                    <a title="<?php echo __('Delete') ?>" href="javascript:void(0)" onclick="ex_delConfirm(this, '<?php echo $this->webroot ?>products/del/<?php echo $mydata[$i][0]['item_id']; ?>/<?php echo $id ?>', 'static route  <?php echo $code_type == 0 ? $mydata[$i][0]['digits'] : $mydata[$i][0]['code_name'] ?>');"><i class="icon-remove"></i> </a></td>
                                <?php
                                }
                                else
                                {
                                    ?>
                                <a title="<?php echo __('Delete') ?>" href="javascript:void(0)" onclick="ex_delConfirm(this, '<?php echo $this->webroot ?>products/del_code_name/<?php echo $mydata[$i][0]['item_id']; ?>/<?php echo $id ?>', 'static route  <?php echo $code_type == 0 ? $mydata[$i][0]['digits'] : $mydata[$i][0]['code_name'] ?>');"> <i class='icon-remove'></i> </a></td>
                                <?php
                                }
                                ?>

                            <?php } ?>
                        </tr>
                    <?php } ?>
                <?php endif; ?>
                    </tbody>
                </table>

                <div class="separator"></div>
                <div class="row-fluid">
                    <div class="pagination pagination-large pagination-right margin-none">
                        <?php echo $this->element('page'); ?>
                    </div>
                </div>
                <div class="clearfix"></div>

                <div style="clear:both; height:10px;"></div>
                <?php if ($_SESSION['role_menu']['Routing']['products']['model_w'] && strcmp($name[0][0]['name'],'ORIGINATION_STATIC_ROUTE')): ?>
                <fieldset id="b-me">
                    <legend> <a href="#" onclick="$('#b-me').hide();
                                $('#b-me-full').show();
                                return false;"> <span id="ht-100007"  ><?php __('Mass Edit') ?> »</span> </a> </legend>
                </fieldset>
            <?php  endif; ?>
                <form action="<?php echo $this->webroot ?>products/<?php echo $code_type == 0 ? 'updateselect' : 'updateselectCodeName' ?>/<?php echo array_keys_value($this->params, 'pass.0') ?>" method="post" id="actionForm">
                    <input type="hidden" class="input in-hidden" name="select_id" value="" id="id"/>
                    <input type="hidden" class="input in-hidden" name="stage" value="preview" id="stage_param"/>
                    <fieldset id="b-me-full" style="display: none;">
                        <legend> <a href="#" id="manyup" onclick="$('#b-me').show();
                                    $('#b-me-full').hide();
                                    return false;"><?php __('Mass Edit Action') ?>:</a>
                            <select class="input-xlarge" name="action" id="action">
                                <option value="update"><?php __('Update current Static Route Table') ?></option>
                                <option value="delete"><?php __('Delete found Static Route Table') ?></option>
                            </select>
                        </legend>
                        <div style="display: block;" id="actionPanelEdit">
                            <table cellspacing="0" cellpadding="0" border="0" class="mylist table table-condensed">
                                <tbody>
                                <tr>
                                    <td style=""><label class="help-inline"> <span id="ht-100008" class="helptip" rel="helptip"><?php echo __('Strategy', true); ?></span> <span id="ht-100008-tooltip" class="tooltip"></span>: </label>
                                        <select class="input in-select select" name="route_strategy_options" id="route_strategy_options">
                                            <option value="none"><?php __('preserve') ?></option>
                                            <option value="set"><?php __('set to') ?></option>
                                        </select></td>
                                    <td style=""><select class="input in-select select" name="strategy" id="strategy">
                                            <option value="1">» <?php __('Top-Down') ?></option>
                                            <option value="0">» <?php __('By Percentage') ?> </option>
                                            <option value="2">» <?php __('Round Robin') ?></option>
                                        </select></td>
                                    <td style=""><label class="help-inline"> <span id="ht-100009" class="helptip" rel="helptip"><?php echo __('Time Profile', true); ?></span>: </label>
                                        <select class="input in-select select" name="route_time_profile_options" id="route_time_profile_options">
                                            <option value="none"><?php __('preserve') ?></option>
                                            <option value="set"><?php __('set to') ?></option>
                                        </select></td>
                                    <td style="">

                                        <?php echo $form->input('time_profile', Array('div' => false, 'name' => 'time_profile', 'label' => false, 'options' => $appProduct->_get_select_options($TimeProfile, 'TimeProfile', 'time_profile_id', 'name'))) ?></td>
                                </tr>
                                </tbody>
                            </table>
                            <?php
                            $trunks = $appProduct->_get_select_options($Resource, 'Resource', 'resource_id', 'alias');
                            //ksort($trunks);
                            ?>
                            <table class="table table-condensed">
                                <tr style="height:0px">
                                    <td colspan=15><div style="padding:5px;display:block">
                                            <div style="float:left;">
                                                <input type="button" class="btn" value="Add" id="addbtn1" />
                                            </div>
                                            <table class="mylist table table-condensed" id="tbl1">
                                                <thead>
                                                <tr>
                                                    <td><?php echo __('Trunk Number', true); ?></td>
                                                    <td><?php echo __('Trunk', true); ?></td>
                                                    <td><?php echo __('Percentage', true); ?></td>
                                                    <td><?php echo __('action', true); ?></td>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr class="row-1">
                                                    <td style="width:19%">1</td>
                                                    <td style="width:27%"><?php echo $form->input('trunk', Array('div' => false, 'style' => 'width:200px', 'label' => false, 'name' => "trunk[]", 'options' => $trunks)) ?></td>
                                                    <td style="width:27%"><input type="text" id="percentage" name="percent[]" /></td>
                                                    <td style="width:10%"><i class="icon-remove"  onclick="$(this).closest('tr').remove();" ></i></td>
                                                </tr>
                                                </tbody>
                                            </table>
                                            <?php
                                            if ($_SESSION['role_menu']['Routing']['products']['model_w'])
                                            {
                                                ?><input id="uptbtn" class="btn btn-primary" type="button" value="<?php echo __('submit', true); ?>" /><?php } ?>
                                        </div></td>
                                </tr>
                            </table>
                            <script type="text/javascript">
                                jQuery('#actionPanelEdit #strategy').change(function() {
                                    if (jQuery(this).val() == '0') {
                                        jQuery('#actionPanelEdit').find('input[id^=percentage]').show().val('');
                                    } else {
                                        jQuery('#actionPanelEdit').find('input[id^=percentage]').hide();
                                    }
                                });
                            </script>
                        </div>
                    </fieldset>
                </form>
            <?php } ?>
        </div>

    </div>
</div>
<div id="dd">&nbsp;</div>

<div id="myModal_trunkList" class="modal hide">
    <div class="modal-header">
        <button data-dismiss="modal" class="close" type="button">&times;</button>
        <h3><?php __('Trunk List'); ?></h3>
    </div>
    <div class="modal-body">
    </div>
    <div class="modal-footer">
        <a href="javascript:void(0)" id="support_close" data-dismiss="modal" class="btn btn-default"><?php __('Close'); ?></a>
    </div>

</div>

<form method="post" action="<?php echo $this->webroot; ?>">
<div id="myModal_qos_parameters" class="modal hide">
    <div class="modal-header">
        <button data-dismiss="modal" class="close" type="button">&times;</button>
        <h3><?php __('Trunk Priority'); ?></h3>
    </div>
    <div class="modal-body">
    </div>
    <div class="modal-footer">
        <input type="submit" class="btn btn-primary" value="<?php __('Submit'); ?>">
        <a href="javascript:void(0)"  data-dismiss="modal" class="btn btn-default btn-close"><?php __('Close'); ?></a>
    </div>
</div>
</form>

<script type="text/javascript">
    $(document).ready(function() {

        $(".trunk_list").click(function(){
            var trunk_info = $(this).attr('mydata');
            $("#myModal_trunkList").find(".modal-body").html(trunk_info);
        });

        $('#addbtn1').click(function() {
            var row = $('#tbl1 tbody tr:last-child').clone();
            var num = $('td:first-child', row).text();
            $('td:first-child', row).text(++num);
            $('#tbl1 tbody').append(row);
        });


        $('#uptbtn').click(function() {

            var $checkbox = $('.chkitem:checked');
            var arr = new Array();
            $checkbox.each(function(index) {
                //if($(this).attr('checked')==true) {
                //var tmp = $(this).parent().siblings('td.in-decimal').text();
                var tmp = $(this).val();
                arr.push(tmp);
                //}
                if (arr.length == 0) {
                    return false;
                }
            });
            var sid = arr.join(',');
            $('#actionForm input[name=select_id]').val(sid);
            if (jQuery('#strategy').val() == 0) {
                var percentage = 0;
                jQuery('input[id^=percentage]').each(function() {
                    var value = jQuery(this).val();
                    if (value == '') {
                        value = 0;
                    }
                    percentage += parseInt(value);
                });
                if (parseInt(percentage) != 100) {
                    jQuery.jGrowlError('The sum of all percentage must be equal to 100');
                    return false;
                }
            }

            var num = $checkbox.size();
            var msg = "You must select at least one record that you want to modify.";
            if (num == 0) {
                jGrowl_to_notyfy("You must select at least one record that you want to modify!", {theme: 'jmsg-error'});
                return false;
            }
            $('#actionForm').submit();
        });

    });


</script>
<script type="text/javascript">
    var code_type = '<?php echo $code_type ?>';
    var onsubmit = function(options) {
        if (!options.log)
            return true;
        var validate_flg = $("#trAdd").find("input[class*=validate]").validationEngine('validate');
      //  if(validate_flg){
      //      return false;
     //   }
        var re = true;
        var digits = jQuery('#' + options.log).find('#digits').val();
        var time_profile = jQuery('#' + options.log).find('#time_profile_id').val();
        var strategy = jQuery('#' + options.log).find('#strategy').val();
        var carrier = $("#Carriers1").val();
        if (carrier == '0')
        {
            jQuery.jGrowlError('Carrier can not be empty!');
            re = false;
        } else {
            var trunk = $("#Carriers1").parent().next().children().eq(0).val();

            if (!trunk)
            {
                jQuery.jGrowlError('Trunk can not be empty!');
                re = false;
            }
        }




        if (strategy == "0")
        {
            //var $percentages = jQuery('#'+options.log).find('#percentage[]');
            var $percentages = $("input[name='percent[]']");
            //alert($percentages);
            var flag = false;
            $percentages.each(function(index, item) {
                if (isNaN($(this).val()))
                {
                    flag = true;
                    return true;
                }
            });
            if (flag)
            {
                jQuery.jGrowlError('Percentage must be whole number!');
            }
        }

        if (!options.route_info_id) {
            options.route_info_id = '';
        }
        if (digits == "") {
            digits = "empty";
        }
        var data = jQuery.ajaxData("<?php echo $this->webroot ?>products/check_route_info_name/" + options.route_info_id + "?name=" + digits + "&product_id=<?php echo array_keys_value($this->params, 'pass.0') ?>");
        //if(digits==''){
        //	jQuery.jGrowlError('Prefix, cannot be null！');
        //	re=false;
        //}
        if (code_type == 0 && digits != 'empty') {
            if (!parseInt(digits)) {
                jQuery.jGrowlError('Code, must be whole number!');
                re = false;
            }
        }




        //时间段是否有交集
        var time_profile_diff = $.ajaxData("<?php echo $this->webroot ?>products/check_time_profile/" + digits + '/' + time_profile + "/<?php echo array_keys_value($this->params, 'pass.0') ?>/" + options.route_info_id);


        if (time_profile_diff == "not" && data.indexOf('false') != -1) {
            jQuery.jGrowlError(digits + ' use the same time profile');
            re = false;
        }



        var temp_arr = Array();
        jQuery('select[id^=Trunk]').each(function() {

            if (jQuery(this).val() == '') {
                return;
            }
            for (var i in temp_arr) {
                if (jQuery(this).val() == temp_arr[i]) {
                    jQuery.jGrowlError("Trunk " + temp_arr[i] + ' is repeat!');
                    re = false;
                    return;
                }
            }
            temp_arr.push(jQuery(this).val());
        });
        if (jQuery('#strategy').val() == 0) {
            var percentage = 0;
            jQuery('input[id^=percentage]').each(function() {
                var value = jQuery(this).val();
                if (value == '') {
                    value = 0;
                }
                percentage += parseInt(value);
            });
            if (parseInt(percentage) != 100) {
                jQuery.jGrowlError('The sum of all percentage must be equal to 100');
                re = false;
            }
        }

        return re;
    }
    jQuery('a[title=Edit]').click(function() {
        var route_info_id = jQuery(this).parent().parent().find('input:nth-child(1)').val();
        var code_name = jQuery(this).parent().parent().get(0).cells[1].innerHTML;
        var trunk_type2 = "<?php
            if (!strcmp($name[0][0]['name'], "ORIGINATION_STATIC_ROUTE"))
            {
                echo "1";
            }
            else
            {
                echo "0";
            }
            ?>";
        jQuery(this).parent().parent().trAdd({
            ajax: (code_type == 0) ? "<?php echo $this->webroot ?>products/js_save_prefix/" + trunk_type2 + "/" + route_info_id : "<?php echo $this->webroot ?>products/js_save_code_name/<?php echo $id; ?>/" + $.trim(code_name),
            action: (code_type == 0) ? "<?php echo $this->webroot ?>products/edit_route/" + route_info_id + "/<?php echo array_keys_value($this->params, 'pass.0') ?>" : "<?php echo $this->webroot ?>products/edit_route_code_name/" + $.trim(code_name) + "/<?php echo array_keys_value($this->params, 'pass.0') ?>",
            tag: '.cloned',
            callback: function() {
                strategy(jQuery('#strategy'));
            },
            onsubmit: onsubmit,
            saveType: 'edit',
            route_info_id: route_info_id
        });
        return false;
    });


    jQuery('#add').click(function() {
        var trunk_type2 = "<?php
            if (!strcmp($name[0][0]['name'], "ORIGINATION_STATIC_ROUTE"))
            {
                echo "1";
            }
            else
            {
                echo "0";
            }
            ?>";
        jQuery('#mytable').show();
        jQuery(".msg").hide();
        jQuery('#mytable').trAdd({
            ajax: (code_type == 0) ? "<?php echo $this->webroot ?>products/js_save_prefix/" + trunk_type2 : "<?php echo $this->webroot ?>products/js_save_code_name/<?php echo $id; ?>",
            action: (code_type == 0) ? "<?php echo $this->webroot ?>products/add_route/<?php echo array_keys_value($this->params, 'pass.0') ?>" : "<?php echo $this->webroot ?>products/add_route_code_name/<?php echo array_keys_value($this->params, 'pass.0') ?>",
            tag: '.cloned',
            onsubmit: onsubmit,
            insertNumber: 'first',
            callback: function() {
                strategy(jQuery('#strategy'));
                jQuery('#mytable').resize();
            },
            removeCallback: function() {
                if (jQuery('table.list tr').size() < 2) {
                    jQuery(".msg").show();
                    jQuery('#mytable').hide();
                }
            }
        });
        return false;
    });
</script>
<script type="text/javascript">
    jQuery(document).ready(function() {
        jQuery('#selectAll').selectAll('.select');
    });
    jQuery(document).ready(function() {
        jQuery('#route_strategy_options').change(function() {
            if (jQuery(this).val() == 'none') {
                jQuery('#strategy').hide().val('').change();
            } else {
                jQuery('#strategy').show();
            }
        }).change();
        jQuery('#route_time_profile_options').change(function() {
            if (jQuery(this).val() == 'none') {
                jQuery('#time_profile').hide().val('');
            } else {
                jQuery('#time_profile').show();
            }
        }).change();

        jQuery('input[id^=percentage]').xkeyvalidate({type: 'Int'});
    });
</script>

<!-- 上传文件 如果有错误信息则显示 -->
<?php
$upload_error = $session->read('upload_route_error');
if (!empty($upload_error))
{
    $session->del('upload_route_error');
    $affectRows = $session->read('upload_commited_rows');
    $session->del('upload_commited_rows');
    ?>
    <script language=JavaScript>
        //提交的行数
        document.getElementById("affectrows").innerHTML = "<?php echo $affectRows ?>";
        //错误信息
        var errormsg = eval("<?php echo $upload_error ?>");
        var loop = errormsg.length;
        var msg = "";
        for (var i = 1; i <= loop; i++) {
            msg += errormsg[i - 1].row + "<?php echo __('row') ?>" + " : " + errormsg[i - 1].name + errormsg[i - 1].msg + "&nbsp;&nbsp;&nbsp;&nbsp;";
            if (i % 2 == 0) {
                msg += "<br/>";
            }

            if (i == loop) {
                msg += "<p>&nbsp;&nbsp;<p/>";
            }
            document.getElementById('route_upload_errorMsg').innerHTML = msg;
        }
        cover('uploadroute_error');
    </script>
<?php } ?>

<link rel="stylesheet" type="text/css" href="<?php echo $this->webroot; ?>easyui/themes/default/easyui.css">
<!--<link rel="stylesheet" type="text/css" href="<?php echo $this->webroot; ?>easyui/themes/icon.css">-->
<script type="text/javascript" src="<?php echo $this->webroot; ?>easyui/jquery.easyui.min.js"></script>

<script>
    function checkint(input, name)
    {
        var re = /^[0-9]+$/; //判断字符串是否为数字 //判断正整数 /^[1-9]+[0-9]*]*$/
        if (!re.test(input))
        {
            jGrowl_to_notyfy(name + " must be numberic.", {theme: 'jmsg-error'});
            return false;
        }
        return true;
    }

    function checklarge(input, max, name)
    {
        if (parseInt(input) > parseInt(max)) {
            jGrowl_to_notyfy(name + " can not greater than " + max + " .", {theme: 'jmsg-error'});
            return false;
        }
        return true;
    }

    function checkless(input, min, name) {
        if (parseInt(input) < parseInt(min)) {
            jGrowl_to_notyfy(name + " can not less than " + min + " .", {theme: 'jmsg-error'});
            return false;
        }
        return true;
    }

    $(function() {

        // extend validatebox alpha
        $.extend($.fn.validatebox.defaults.rules, {
            number: {
                validator: function(value) {
                    return /^[0-9].*$/.test(value);
                },
                message: 'Field must contain only numbers.'
            }
        });

        $('.qos_edit').click(function() {
            $("#myModal_qos_parameters").find('.modal-body').html('');
            var item_id = $(this).attr('item');
            var href = '<?php echo $this->webroot ?>products/qos/' + item_id + '/<?php echo $this->params['pass'][0] ?>';
            $("#myModal_qos_parameters").find('.modal-body').load(href);
            $("#myModal_qos_parameters").closest('form').attr('action',href);

//            $('#dd').dialogui({
//                title: 'Trunk Priority',
//                width: 400,
//                height: 300,
//                closed: false,
//                cache: false,
//                href: '<?php //echo $this->webroot ?>//products/qos/' + item_id + '/<?php //echo $this->params['pass'][0] ?>//',
//                modal: true,
//                onBeforeOpen: function() {
//                    $('#dd').dialogui('refresh');
//                },
//                onLoad: function() {
//
//                    var $dialog_form = $('form', '.dialog_form');
//
//                    $dialog_form.submit(function() {
//
//                        var min_asr = $("input[name=min_asr]", $dialog_form).val();
//                        var max_asr = $("input[name=max_asr]", $dialog_form).val();
//                        var min_abr = $("input[name=min_abr]", $dialog_form).val();
//                        var max_abr = $("input[name=max_abr]", $dialog_form).val();
//                        var min_acd = $("input[name=min_acd]", $dialog_form).val();
//                        var max_acd = $("input[name=max_acd]", $dialog_form).val();
//                        var min_pdd = $("input[name=min_pdd]", $dialog_form).val();
//                        var max_pdd = $("input[name=max_pdd]", $dialog_form).val();
//                        var min_aloc = $("input[name=min_aloc]", $dialog_form).val();
//                        var max_aloc = $("input[name=max_aloc]", $dialog_form).val();
//                        var max_price = $("input[name=max_price]", $dialog_form).val();
//
//
//
//                        if (min_asr != '')
//                        {
//                            if (!checkint(min_asr, "Min ASR")) {
//                                return false;
//                            }
//                            if (!checklarge(min_asr, 100, "Min ASR")) {
//                                return false;
//                            }
//                        }
//                        if (max_asr != '')
//                        {
//                            if (!checkint(max_asr, "Max ASR")) {
//                                return false;
//                            }
//                            if (!checklarge(max_asr, 100, "MAX ASR")) {
//                                return false;
//                            }
//                        }
//                        if (min_abr != '')
//                        {
//                            if (!checkint(min_abr, "Min ABR")) {
//                                return false;
//                            }
//                            if (!checklarge(min_abr, 100, "Min ABR")) {
//                                return false;
//                            }
//                        }
//                        if (max_abr != '')
//                        {
//                            if (!checkint(max_abr, "Max ABR")) {
//                                return false;
//                            }
//                            if (!checklarge(max_abr, 100, "Max ABR")) {
//                                return false;
//                            }
//                        }
//                        if (min_acd != '')
//                        {
//                            if (!checkint(min_acd, "Min ACD")) {
//                                return false;
//                            }
//                        }
//                        if (max_acd != '')
//                        {
//                            if (!checkint(max_acd, "Max ACD")) {
//                                return false;
//                            }
//                        }
//                        if (min_pdd != '')
//                        {
//                            if (!checkint(min_pdd, "Min PDD")) {
//                                return false;
//                            }
//                        }
//                        if (max_pdd != '')
//                        {
//                            if (!checkint(max_pdd, "Max PDD")) {
//                                return false;
//                            }
//                        }
//                        if (min_aloc != '')
//                        {
//                            if (!checkint(min_aloc, "Min ALOC")) {
//                                return false;
//                            }
//                        }
//                        if (max_aloc != '')
//                        {
//                            if (!checkint(max_aloc, "Max ALOC")) {
//                                return false;
//                            }
//                        }
//                        if (max_price != '')
//                        {
//                            if (!checkint(max_price, "Max Price")) {
//                                return false;
//                            }
//                        }
//
//
//                    });
//
//                }
//            });
//            return false;

        });






    });
</script>

<script type="text/javascript">
    function strategy(obj, val) {
        var value = jQuery(obj).val();
        if (val) {
            value = val;
        }
        if (value == 1 || value == 2) {
            jQuery('.dd').hide().val('');
        }
        if (value == 0) {
            jQuery('.dd').show();
        }
    }
    function client(obj) {
        value = jQuery(obj).val();
                var trunk_type2 = "<?php
        if (!strcmp($name[0][0]['name'], "ORIGINATION_STATIC_ROUTE"))
        {
            echo "1";
        }
        else
        {
            echo "0";
        }
        ?>";
        var data = jQuery.ajaxData('<?php echo $this->webroot ?>trunks/ajax_options?filter_id=' + value + '&type=egress&trunk_type2=' + trunk_type2);
        data = eval(data);
        var temp = jQuery(obj).parent().parent().find('select').eq(1).val();
        jQuery(obj).parent().parent().find('select').eq(1).html('');
        jQuery('<option>').appendTo(jQuery(obj).parent().parent().find('select').eq(1));
        for (var i in data) {
            var temp = data[i];
            jQuery('<option>').html(temp.alias).val(temp.resource_id).appendTo(jQuery(obj).parent().parent().find('select').eq(1));
        }
        jQuery(obj).parent().parent().find('select').eq(1).val(temp);

    }
</script>
<script type="text/javascript">

    $(document).ready(function() {
        $('#addbtn').live('click', function() {
            var $tr = $('#tbl tbody tr:last-child');
            var row = $tr.clone(true);
            if ($tr.hasClass('row-1')) {
                row.removeClass('row-1').addClass('row-2');
            } else {
                row.removeClass('row-2').addClass('row-1');
            }
            var num = $('td:first-child', row).text();
            $('td:first-child', row).text(++num);
            $('#tbl tbody').append(row);

        });
    });

    function trunk_number(){
        $('#tbl tr:gt(0)').find('td:eq(0)').each(function(i){

            $(this).html(i+1);
        })
    }
    $(document).ready(function() {
        $('.changeup').live('click', function() {
            var $pr = $(this).parent().parent().prev();
            var $tr = $(this).parent().parent().insertBefore($pr);
            trunk_number();
        });
        $('.changedown').live('click', function() {
            var $pr = $(this).parent().parent().next();
            var $tr = $(this).parent().parent().insertAfter($pr);
            trunk_number();
        });
        $('.delete_tr').live('click', function() {
            if($('#tbl tr').length > 2){

                $(this).closest('tr').remove();
                trunk_number();
            }

            /*var $pr = $(this).parent().parent().next();
            var $tr = $(this).parent().parent().insertAfter($pr);*/
        });

    });

    function get_rate(obj) {
        var val = $(obj).val();
        var prefix = $("input[name=digits]").val();
        //if(val != '') {
        var data = jQuery.ajaxData('<?php echo $this->webroot ?>products/get_rate/' + val + '/' + prefix);
//        if(isNaN(data)) data = 0;

        var strJSON = data;//得到的JSON
        var data_arr = eval("(" + strJSON + ")");//转换后的JSON对象

        $(obj).parent().next().text(new Number(data_arr.rate).toFixed(5));
        var cps_limit = data_arr.cps_limit;
        var capacity = data_arr.capacity;
        $(obj).parent().next().next().html(cps_limit);
        $(obj).parent().next().next().next().html(capacity);
    }

    $(function() {
        <?php if (!count($d) && !isset($_GET['search'])): ?>
        $("#add").click();
        <?php endif; ?>

        $('table th').each(function(){
            if($(this).text() == "Trunk List"){
                $(this).css('width','0');
            }
        });
    });

</script>
