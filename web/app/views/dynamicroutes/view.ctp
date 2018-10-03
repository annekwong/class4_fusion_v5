<style>
.hidden{display:none !important;}
</style>
<script src="<?php echo $this->webroot ?>js/ajaxTable.js" type="text/javascript"></script>
<?php $d = $p->getDataArray(); ?>

<ul class="breadcrumb">
    <li><?php __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>dynamicroutes/view"><?php __('Routing') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>dynamicroutes/view">
        <?php echo __('Dynamic Routing') ?></a></li>
</ul>


<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Dynamic Routing') ?></h4>

</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
     <?php if ($_SESSION['role_menu']['Routing']['dynamicroutes']['model_w']): ?>
        <a href="#myModal_massedit" data-toggle="modal" class="btn btn-primary btn-icon glyphicons circle_plus"><i></i> <?php echo __('Mass Edit') ?></a>
    <?php endif; ?>
    <?php
    if ($_SESSION['role_menu']['Routing']['dynamicroutes']['model_w'])
    {
        ?>
        <?php echo $this->element("createnew", Array('url' => 'dynamicroutes/add')) ?>
    <?php } ?>
    <?php if (count($d) > 0): ?>
        <a class="link_btn btn btn-primary btn-icon glyphicons remove" href="###" class="link_btn" id="delete_selected" rel="popup"><i></i> <?php echo __('Delete Selected') ?></a>
    <?php endif; ?>
    <?php
    if (isset($edit_return))
    {
        ?>
        <a class="btn btn-icon btn-inverse glyphicons circle_arrow_left" href="<?php echo $this->webroot ?>dynamicroutes/view"><i></i> <?php echo __('Back') ?> </a>
    <?php } ?>
</div>
<div class="clearfix"></div>
<div class="innerLR">

    <div class="widget widget-heading-simple widget-body-white">
        <div class="widget-body">
            <div class="filter-bar">
                <form method="get">
                    <!-- Filter -->
                    <div>
                        <label><?php __('Search') ?>:</label>
                        <input type="text" id="search-_q" class="in-search default-value input in-text defaultText" title="<?php echo __('search') ?>"
                               value="<?php
                               if (isset($_POST['search']))
                               {
                                   echo $_POST['search'];
                               }
                               else
                               {
                                   echo '';
                               }
                               ?>"  onclick="this.value = ''" name="search">
                    </div>
                    <!-- // Filter END -->
                    <!-- Filter -->
                    <div>
                        <label><?php __('Routing Rule') ?>:</label>
                        <?php
                        $arr1 = array('4' => __('routerule1', true), '5' => __('routerule2', true), '6' => __('routerule3', true));
                        echo $form->input('routing_rule', array('options' => $arr1, 'name' => 'routing_rule', 'empty' => '', 'label' => false,
                            'div' => false, 'type' => 'select', 'value' => $routing_rule));
                        ?>
                        <script type="text/javascript">
                            jQuery(document).ready(function() {
                                jQuery('#routing_rule').val('<?php echo $routing_rule ?>')
                            });
                        </script>
                    </div>
                    <!-- // Filter END -->
                    <!-- Filter -->
                    <div>
                        <button name="submit" class="btn query_btn"><?php __('Query') ?></button>
                    </div>
                    <!-- // Filter END -->
                </form>
            </div>
            <div class="clearfix"></div>


            <?php
            if (count($d) == 0)
            {
                ?>
                <h2 class="msg center"><?php echo __('no_data_found') ?></h2>
                <table class="routelist footable table table-striped tableTools table-bordered  table-white table-primary" style="display:none">
                    <thead>
                    <tr>
                        <th><input type="checkbox" id="selectall" /></th>
                        <th><?php __('List') ?></th>
                        <!--	    			<th> <?php echo $appCommon->show_order('dynamic_route_id', __('ID', true)) ?></th>-->
                        <th><?php echo $appCommon->show_order('name', __('Name'), true) ?></th>
                        <th><?php echo $appCommon->show_order('routing_rule', __('Routing Rule', true)) ?></th>
                        <th><?php echo $appCommon->show_order('time_profile_id', __('Time Profile', true)) ?></th>
                        <th><?php echo $appCommon->show_order('use_count', __('Usage Count', true)) ?></th>
                        <th><?php echo $appCommon->show_order('lcr_flag', __('QoS Cycle', true)) ?></th>
                        <th><?php echo __('Update At', true); ?></th>
                        <th><?php echo __('Update By', true); ?></th>
                        <?php
                        if ($_SESSION['role_menu']['Routing']['dynamicroutes']['model_w'])
                        {
                            ?>
                            <th><?php __('Action') ?></th>
                        <?php } ?>
                    </tr>
                    </thead>
                </table>
            <?php
            }
            else
            {
            ?>
            <div id="toppage"></div>
            <div style="height:0px"></div>
            <?php //*********************表格�?************************************    ?>
            <div>
                <table class="routelist footable table dynamicTable table-striped tableTools table-bordered  table-white table-primary">
                    <thead>
                    <tr>
                        <th><input type="checkbox" id="selectall" /></th>
                        <th><?php __('List') ?></th>
                        <!--	    			<th> <?php echo $appCommon->show_order('dynamic_route_id', __('ID', true)) ?></th>-->
                        <th><?php echo $appCommon->show_order('name', __('Name', true)) ?></th>
                        <th><?php echo $appCommon->show_order('routing_rule', __('Routing Rule', true)) ?></th>
                        <th><?php echo $appCommon->show_order('time_profile_id', __('Time Profile', true)) ?></th>
                        <th><?php echo $appCommon->show_order('use_count', __('Usage Count', true)) ?></th>
                        <th><?php echo $appCommon->show_order('lcr_flag', __('QoS Cycle', true)) ?></th>
                        <th><?php echo __('Update At', true); ?></th>
                        <th><?php echo __('Update By', true); ?></th>
                        <?php
                        if ($_SESSION['role_menu']['Routing']['dynamicroutes']['model_w'])
                        {
                            ?>
                            <th><?php __('Action') ?></th>
                        <?php } ?>
                    </tr>
                    </thead>
                    <?php //*********************表格�?************************************  ?>
                    <?php //*********************循环输出的动态部�?************************************   ?>
                    <?php
                    $mydata = $p->getDataArray();
                    $loop = count($mydata);
                    for ($i = 0; $i < $loop; $i++)
                    {
                        ?>
                        <tbody id="resInfo<?php echo $i ?>">
                        <tr class="row-<?php echo $i % 2 + 1; ?>">
                            <td><input control="<?php echo $mydata[$i][0]['dynamic_route_id'] ?>" type="checkbox" /></td>
                            <td  align="center"  style="font-weight: bold;"><img   id="image<?php echo $i; ?>"  onclick="pull('<?php echo $this->webroot ?>', this,<?php echo $i; ?>)"    class=" jsp_resourceNew_style_1"  src="<?php echo $this->webroot ?>images/+.gif" title="<?php __('findegress') ?>"/></td >
                            <!--		    		<td  align="center">
                                    <?php echo $mydata[$i][0]['dynamic_route_id'] ?>
                                                          </td>-->
                            <td  align="center"  style="font-weight: bold;">
                                <?php echo $mydata[$i][0]['name']; ?>
                            </td >
                            <td align="center">
                                <?php
                                if ($mydata[$i][0]['routing_rule'] == 4)
                                {
                                    echo __('routerule1');
                                    ?>
                                <?php
                                }if ($mydata[$i][0]['routing_rule'] == 5)
                                {
                                    echo __('routerule2');
                                    ?>
                                <?php
                                }if ($mydata[$i][0]['routing_rule'] == 6)
                                {
                                    $mydata[$i][0]['lcr_flag'] = 'empty';
                                    echo __('routerule3');
                                    ?>
                                <?php } ?></td>
                            <td><?php echo $mydata[$i][0]['time_profile_id'] ?>
                                <?php
                                if (empty($mydata[$i][0]['time_profile_id']))
                                {
                                    echo '';
                                }
                                ?></td>
                            <td><a  href="<?php echo $this->webroot ?>routestrategys/dynamic_strategy_list/<?php echo base64_encode($mydata[$i][0]['dynamic_route_id']); ?>"  target="blank"> <?php echo$mydata[$i][0]['use_count'] ?></a></td>
                            <td><?php echo isset($mydata[$i][0]['lcr_flag']) ? $mydata[$i][0]['lcr_flag'] : ''; ?></td>
                            <td><?php echo $mydata[$i][0]['update_at']; ?></td>
                            <td><?php echo $mydata[$i][0]['update_by']; ?></td>
                            <?php
                            if ($_SESSION['role_menu']['Routing']['dynamicroutes']['model_w'])
                            {
                                ?>
                                <td style="text-align: center;">
                                    <a title="<?php __('QoS Parameters') ?>" href="<?php echo $this->webroot ?>dynamicroutes/qos/<?php echo base64_encode($mydata[$i][0]['dynamic_route_id']) ?>">
                                        <i class="icon-sun"></i>
                                    </a>
                                    <a title="<?php __('Trunk Priority') ?>" href="<?php echo $this->webroot ?>dynamicroutes/priority/<?php echo base64_encode($mydata[$i][0]['dynamic_route_id']) ?>">
                                        <i class="icon-moon"></i>
                                    </a>
                                    <a title="<?php __('Override') ?>" href="<?php echo $this->webroot ?>dynamicroutes/override/<?php echo base64_encode($mydata[$i][0]['dynamic_route_id']) ?>">
                                        <i class="icon-bullseye"></i>
                                    </a>
                                     <a title="Edit" id='<?php echo $mydata[$i][0]['dynamic_route_id'] ?>'  href="<?php echo $this->webroot ?>dynamicroutes/edit/<?php echo base64_encode($mydata[$i][0]['dynamic_route_id']) ?>"  title="<?php __('edit') ?>">
                                        <i class="icon-edit"></i>
                                    </a>
                                    <a onClick="return myconfirm('Are you sure to delete the Dynamic Routing[<?php echo $mydata[$i][0]['name'] ?>]?', this);" href="<?php echo $this->webroot ?>dynamicroutes/del/<?php echo base64_encode($mydata[$i][0]['dynamic_route_id']) ?>/<?php echo $mydata[$i][0]['name'] ?>" title="<?php echo __('delete') ?>">
                                        <i class="icon-remove"></i>
                                    </a>
                                </td>
                            <?php } ?>
                        </tr>
                        <tr>
                            <td colspan=10>

                                <div id="ipInfo<?php echo $i ?>" class=" jsp_resourceNew_style_2" style="padding:5px;display:none;">
                                    <table class="footable table table-striped sub_table tableTools table-bordered  table-white">
                                        <thead>
                                        <tr>
                                            <th><?php echo __('ID', true); ?></th>
                                            <th><?php echo __('Carriers', true); ?></th>
                                            <th class="sort_trunk"><span style="float: left"><?php echo __('Egress Trunk Name', true); ?></span> <span class="sorting_asc switch_ico" style="float: right;width:20px;height:20px;"> </span></th>
                                            <th><?php echo __('CPS Limit', true); ?></th>
                                            <th><?php echo __('Call Limit', true); ?></th>
                                            <?php
                                            if ($_SESSION['role_menu']['Routing']['dynamicroutes']['model_w'])
                                            {
                                                ?>
                                                <th><?php echo __('Active', true); ?></th>
                                            <?php } ?>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        if (!empty($mydata[$i][0]['slist']))
                                        {
                                            ?>
                                            <?php
                                            foreach ($mydata[$i][0]['slist'] as $list)
                                            {
                                                ?>
                                                <tr>
                                                    <td><?php echo $list[0]['resource_id'] ?></td>
                                                    <td><?php echo $list[0]['name'] ?></td>
                                                    <td><?php echo $list[0]['alias'] ?></td>
                                                    <td><?php echo $list[0]['cps_limit'] ?></td>
                                                    <td><?php echo $list[0]['capacity'] ?></td>
                                                    <?php if ($_SESSION['role_menu']['Routing']['dynamicroutes']['model_w']): ?>
                                                        <td>
                                                            <a title="<?php __('delete'); ?>"  onclick="myconfirm('<?php __('sure to delete'); ?>',this); return false;" href="<?php echo $this->webroot; ?>dynamicroutes/delete_item/<?php echo base64_encode($list[0]['id']); ?>">
                                                                <i class="icon-remove"></i>
                                                            </a>
                                                        </td>
                                                        <!--
                                                        <td>
                                                            <a style="<?php
                                                            if ($list[0]['active'] != 1)
                                                            {
                                                                echo 'display:none';
                                                            }
                                                            ?> "
                                                               onclick="return active(this, '<?php echo $this->webroot ?>gatewaygroups/dis_able/<?php echo base64_encode($list[0]['resource_id']) ?>/view_egress')"
                                                               href='#' title="<?php echo __('disable') ?>" trunk_name = "<?php echo $list[0]['alias'] ?>"> <i class="icon-check"></i> </a>
                                                            <a style="<?php
                                                            if ($list[0]['active'] == 1)
                                                            {
                                                                echo 'display:none';
                                                            }
                                                            ?> "
                                                               onclick="return disable(this, '<?php echo $this->webroot ?>gatewaygroups/active/<?php echo base64_encode($list[0]['resource_id']) ?>/view_egress')"
                                                               href='#' title="<?php echo __('disable') ?>" trunk_name = "<?php echo $list[0]['alias'] ?>"> <i class="icon-check-empty"></i> </a>
                                                        </td>
                                                        -->
                                                    <?php endif; ?>
                                                </tr>
                                            <?php } ?>
                                        <?php } ?>
                                        </tbody>

                                    </table>
                                </div>

                            </td>
                            <td class="hidden"></td>
                            <td class="hidden"></td>
                            <td class="hidden"></td>
                            <td class="hidden"></td>
                            <td class="hidden"></td>
                            <td class="hidden"></td>
                            <td class="hidden"></td>
                            <td class="hidden"></td>
                            <td class="hidden"></td>
                        </tr>
                        </tbody>
                    <?php } ?>
                </table>
            </div>
            <?php //*****************************************循环输出的动态部�?************************************    ?>
            <div style="height:10px"></div>
            <div class="row-fluid">
                <div class="pagination pagination-large pagination-right margin-none">
                    <?php echo $this->element('page'); ?>
                </div>
            </div>
            <div class="clearfix"></div>
            <?php echo $this->element('dynamicroutes/massedit'); ?> </div>

        <?php } ?>
    </div>

    <script type="text/javascript">
        jQuery(document).ready(function() {
            /*$('input[name="data[Dynamicroute][name]"]').live('keyup',function(){
             var name = $(this).val();

             $('#hide_aaaa').val(name);
             alert(name);
             //                var hide_time_profile_id = $('#DynamicrouteTimeProfileId').val();
             //                $('#hide_time_profile_id').val(hide_time_profile_id);
             });*/

            jQuery('#add').click(function() {
                jQuery('table.routelist').show();
                jQuery('.msg').hide();
                jQuery('table.routelist').trAdd({
                    ajax: '<?php echo $this->webroot ?>dynamicroutes/js_save',
                    action: '<?php echo $this->webroot ?>dynamicroutes/add',
                    tag: '.add',
                    id: '0',
                    insertNumber: 'first',
                    callback: function(options) {

                        jQuery('#' + options.log).find('img[id^=image]').click();
                        $.sort_select('select#DynamicrouteTimeProfileId');
                        $.sort_select('#DynamicrouteRoutingRule');
                        jQuery('select[id^=Carriers]').each(
                            function() {
                                var temp = jQuery(this).parent().parent().find('select[id^=resource_id]').val();
                                jQuery(this).change();
                                jQuery(this).parent().parent().find('select[id^=resource_id]').val(temp);
                            }
                        );
                    },
                    onsubmit: trAdd.onsubmit,
                    removeCallback: function() {
                        if (jQuery('table.routelist tbody').size() == 0) {
                            jQuery('table.routelist').hide();
                            jQuery(".msg").show();
                        }
                    }
                });
                jQuery('#delete').attr('title','Delete');
                return false;
            });
            jQuery('a[title=Edit]').each(function() {
                jQuery(this).click(function() {
                    id = jQuery(this).attr('id');
                    jQuery(this).parent().parent().parent().trAdd({
                        ajax: '<?php echo $this->webroot ?>dynamicroutes/js_save_edit/' + id,
                        action: '<?php echo $this->webroot ?>dynamicroutes/edit/' + id,
                        tag: '.add',
                        id: id,
                        saveType: 'edit',
                        callback: function(options) {
                            jQuery('#' + options.log).find('img[id^=image]').click();
                            $.sort_select('select#DynamicrouteTimeProfileId');
                            $.sort_select('#DynamicrouteRoutingRule');
                            jQuery('select[id^=Carriers]').each(
                                function() {
                                    //var temp=jQuery(this).parent().parent().find('select[id^=resource_id]').val();
                                    //jQuery(this).change();
                                    //jQuery(this).parent().parent().find('select[id^=resource_id]').val(temp);
                                }
                            );
                        },
                        onsubmit: trAdd.onsubmit
                    });
                    return false;
                });
            });

            $(".route_rule").live('change',function(){
                var this_value = $(this).val();
                $(this).closest("tr").find('.lcr_flag').attr('disabled',false);
                if(this_value == 6){
                    $(this).closest("tr").find('.lcr_flag').attr('disabled',true);
                }
            });

            $('#delete_selected').click(function() {
                var ids = [];
                $('table.routelist tbody input:checkbox:checked').each(function() {
                    ids.push(parseInt($(this).attr('control')));
                });
                if(!ids.length){
                    jGrowl_to_notyfy("No row selected, you must select a row.", {theme: 'jmsg-error'});
                    return false;
                }
                bootbox.confirm("Are you sure do this?", function(result) {
                    if (result)
                    {


                        $.ajax({
                            'url': '<?php echo $this->webroot; ?>dynamicroutes/delete_selected',
                            'type': 'POST',
                            'dataType': 'json',
                            'data': {'ids[]': ids},
                            'success': function(data) {
                                if(data.status == 0){
                                    jGrowl_to_notyfy("Your options are deleted failed", {theme: 'jmsg-error'});
                                }else{
                                    jGrowl_to_notyfy("Your options are deleted succesfully", {theme: 'jmsg-success'});
                                    if(data.notify_flg){
                                        var url = '<?php echo $this->webroot; ?>logging/index/' + data.log_id + '/dynamicroutes-view';
                                        window.setTimeout(function() {window.location.href = url},1000);
                                    }else{
                                        $('table.routelist tbody input:checkbox:checked').each(function() {
                                            $(this).closest('tr').remove();
                                        });
                                    }
                                }



                            }
                        });
                    }
                });




            });


            $('#selectall').change(function() {
                $('table.routelist tbody input:checkbox').prop('checked', $(this).prop("checked"));
            });
        });
    </script>
    <script type="text/javascript">
        function active(obj, url) {
//            alert(url);return false;
            var name = $(obj).attr('trunk_name');
            bootbox.confirm("Are you sure disable " + name + "?", function(result) {
                if (result) {
                    jQuery.get(url,
                        function(data) {
                            if (data)
                            {
                                jQuery(obj).hide();
                                jQuery(obj).parent().find('a:nth-child(2)').show();
                                jGrowl_to_notyfy('disable success!', {theme: 'jmsg-success'});
                            }
                            else
                            {
                                jGrowl_to_notyfy('disable failed!', {theme: 'jmsg-error'});
                            }


                        }
                    );
                }
                else
                {
                    return false;
                }
            });
        }
        function disable(obj, url) {
            var name = $(obj).attr('trunk_name');
            bootbox.confirm("Are you sure active " + name + "?", function(result) {
                if (result) {
                    jQuery.get(url,
                        function(data) {
                            if (data)
                            {
                                jQuery(obj).hide();
                                jQuery(obj).parent().find('a:nth-child(1)').show();
                                jGrowl_to_notyfy('active success!', {theme: 'jmsg-success'});
                            }
                            else
                            {
                                jGrowl_to_notyfy('active failed!', {theme: 'jmsg-error'});
                            }
                        }
                    );
                }
                else
                {
                    return false;
                }
            });
        }
        var trAdd = {
            onsubmit: function(options) {
                var xform = jQuery('#' + options.log);
                var re = true;
                if (xform.find('#DynamicrouteName').val() == '') {
                    jQuery.jGrowlError('The field Name cannot be NULL.');
                    xform.find('#DynamicrouteName').addClass('invalid');
                    re = false;
                } else {
                    if (!/^(\w|\-|\_|\ )*$/.test(xform.find('#DynamicrouteName').val())) {
                        jQuery.jGrowlError('Name, allowed characters: a-z,A-Z,0-9,-,_,space, maximum of 100 characters!');
                        return false;
                    }
                }
                if (xform.find('#egressSelect').val() == '') {
                    jQuery.jGrowlError('Egress can not be null!');
                    xform.find('#egressSelect').addClass('invalid');
                    re = false;
                }
                var arr = Array();
                if (xform.find('select[id=egressSelect]').size() == 0) {
                    jQuery.jGrowlError("<?php __('routenameexist') ?>");
                    re = false;
                }
                xform.find('select[id=egressSelect]').each(
                    function() {
                        for (var i in arr) {
                            if (arr[i] == jQuery(this).val()) {
                                jQuery.jGrowlError('egress is repeat');
                                re = false;
                                return;
                            }
                        }
                        arr.push(jQuery(this).val());
                    }
                );

                var name = xform.find('#DynamicrouteName').val();
                var data = jQuery.ajaxData("<?php echo $this->webroot ?>dynamicroutes/checkName/" + options.id + "?name=" + name);
                if (data == 'false') {
                    jQuery.jGrowlError(name + ' is already in use! ');
                    re = false;
                }


                return re;
            }
        }
        function callBack(options) {
            var div = options.div;
            div.html('');
            div.append('<div style="height:35px;font-weight:bold;font-size:20px;text-align:right"><input type="button" value="Order">&nbsp;&nbsp;<a href="#" onclick="return false;"><img onclick="jQuery(this).parent().parent().parent().remove();return false;" src="<?php echo $this->webroot ?>images/delete.png" title="Close"/></a></div>');
            div.append('<table>');
            div.find('table').append('<tr style="height:25px"><td><img class="up" src="<?php echo $this->webroot ?>images/list-sort-asc.png"/><img class="down" src="<?php echo $this->webroot ?>images/list-sort-desc.png"/></td><td><input type="checkbox" name="id" value="dynamic_route_id"/>&nbsp;&nbsp;</td><td style="text-align:left"><span style="font-size:16px;font-weight:bold">Id</span>&nbsp;&nbsp;</td><td>&nbsp;&nbsp;<select name="id_type"><option value="desc">Desc</option><option value="asc">Asc</option></select></td></tr>');
            div.find('table').append('<tr style="height:25px"><td><img class="up" src="<?php echo $this->webroot ?>images/list-sort-asc.png"/><img class="down" src="<?php echo $this->webroot ?>images/list-sort-desc.png"/></td><td><input type="checkbox" name="name" value="name"/>&nbsp;&nbsp;</td><td style="text-align:left"><span style="font-size:16px;font-weight:bold">Name</span>&nbsp;&nbsp;</td><td>&nbsp;&nbsp;<select name="name_type"><option value="desc">Desc</option><option value="asc">Asc</option></select></td></div>');
            div.find('table').append('<tr style="height:25px"><td><img class="up" src="<?php echo $this->webroot ?>images/list-sort-asc.png"/><img class="down" src="<?php echo $this->webroot ?>images/list-sort-desc.png"/></td><td><input type="checkbox" name="routing_rule" value="routing_rule"/>&nbsp;&nbsp;</td><td style="text-align:left"><span style="font-size:16px;font-weight:bold">Routing rule</span>&nbsp;&nbsp;</td><td>&nbsp;&nbsp;<select  name="routing_rule_type"><option value="desc">Desc</option><option value="asc">Asc</option></select></td></tr>');
            div.find('table').append('<tr style="height:25px"><td><img class="up" src="<?php echo $this->webroot ?>images/list-sort-asc.png"/><img class="down" src="<?php echo $this->webroot ?>images/list-sort-desc.png"/></td><td><input type="checkbox" name="time_profile" value="time_profile"/>&nbsp;&nbsp;</td><td style="text-align:left"><span style="font-size:16px;font-weight:bold">Time Profile</span>&nbsp;&nbsp;</td><td>&nbsp;&nbsp;<select  name="time_profile_type"><option value="desc">Desc</option><option value="asc">Asc</option></select></td></tr>');
            div.find('table').append('<tr style="height:25px"><td><img class="up" src="<?php echo $this->webroot ?>images/list-sort-asc.png"/><img class="down" src="<?php echo $this->webroot ?>images/list-sort-desc.png"/></td><td><input type="checkbox" name="usage_count" value="usage_count"/>&nbsp;&nbsp;</td><td style="text-align:left"><span style="font-size:16px;font-weight:bold">Usage Count</span>&nbsp;&nbsp;</td><td>&nbsp;&nbsp;<select name="usage_count_type"><option value="desc">Desc</option><option value="asc">Asc</option></select></td></tr>');
            div.find('table img.up').click(function() {
                jQuery(this).parent().parent().prev().before(jQuery(this).parent().parent());
                div.find('table tr img').show();
                div.find('table tr:nth-child(1) img.up').hide();
                div.find('table tr:last img.down').hide();
            });
            div.find('table img.down').click(function() {
                jQuery(this).parent().parent().next().after(jQuery(this).parent().parent());
                div.find('table tr img').show();
                div.find('table tr:nth-child(1) img.up').hide();
                div.find('table tr:last img.down').hide();
            });
            div.find('table tr:nth-child(1) img.up').hide();
            div.find('table tr:last img.down').hide();
            jQuery(div).find('input[type=button]').click(function() {
                temp = Array();
                div.find('table tr').each(
                    function() {
                        if (jQuery(this).find('input').attr('checked')) {
                            temp.push(jQuery(this).find('input').val() + '-' + jQuery(this).find('select').val());
                        }
                    }
                );
                location = "?order_by=" + temp.join(';') + "&search=<?php echo array_keys_value($this->params, 'url.search') ?>";
            });
        }

    </script>

    <script type="text/javascript">
        function change_trunk(obj){
            var value = obj.val();
            var data = jQuery.ajaxData({'async': false, 'url': '<?php echo $this->webroot ?>trunks/ajax_options?filter_id=' + value + '&type=egress&trunk_type2=0'});
            data = eval(data);
            var temp1 = obj.parent().parent().find('select').eq(1).val();

            obj.parent().parent().find('select').eq(1).html('');
            jQuery('<option>').appendTo(obj.parent().parent().find('select').eq(1));
            for (var i in data) {
                var temp = data[i];
                jQuery('<option>').html(temp.alias).val(temp.resource_id).appendTo(obj.parent().parent().find('select').eq(1));
            }
            obj.parent().parent().find('select').eq(1).val(temp1);
        }


        jQuery(function() {
            var cloned = '';
            $('.client_options').live('change', function() {
                var $this = $(this);
                change_trunk($this);
            });

            $('#additem').live('click', function() {
                cloned = $('tr#cloned').last().clone(true) ;
                cloned.appendTo('#tblwa').find('option[value=""]').attr('selected', true).end().find('option[value=0]').attr('selected', true);
            });

            $('#delete_all').live('click', function() {
              cloned = $('#cloned').clone(true);
              $('#tblwa tr#cloned').remove();
            });

            $('#add_all').live('click', function() {
                var $tblwa = $('#tblwa');

                $.ajax({
                    'async': false,
                    'url': '<?php echo $this->webroot; ?>dynamicroutes/get_all_egress',
                    'type': 'POST',
                    'dataType': 'json',
                    'beforeSend': function(){
                        var img = "<span id='span_fresh' style='display:inline-block;width:32px;height:32px;'><img src='<?php echo $this->webroot?>images/check_waiting.gif' /></span>";
                        $(img).appendTo($tblwa.find('tr:eq(0) td'));
                        $('#tblwa').find('tr[id="cloned"]:gt(0)').remove();
                    },
                    'success': function(data)
                    {
                        var rows = new Array();
                        var news = '';
                        // limitted ajax requests in change_trunk()
                        var limit = 50;
                        limit = data.length < limit ? data.length : limit;

                        for(var i = 0;i <= limit;i++) {
                            var key = i;
                            var value = data[i];
                            var newel = $('#cloned').clone(true);
                            $('.client_options option[value="' + value[0]['client_id'] + '"]', newel).attr('selected', true);

                            change_trunk(newel.find('.client_options'));

                            $('#egressSelect option[value="' + value[0]['resource_id'] + '"]', newel).attr('selected', true);
                            $tblwa.append(newel);
                        };

                        $('#cloned').remove();
                        $('#span_fresh').remove();
                    }
                });
            });

            <?php if (!count($d) && (!isset($_GET['search']) || !isset($_GET['routing_rule']))): ?>
            $("#add").click();
            <?php endif; ?>
        });
    </script>

    <script src="<?php echo $this->webroot ?>js/jquery.sortElements.js"></script>
    <script>
        $(function(){
            $.sort_select('select[name="timeprofile"]');

            var table = $('.sub_table');//table的id
            table.each(function(){
                var this_table = $(this);
                this_table.find('.sort_trunk')//要排序的headerid
                    .each(function(){
                        var th = $(this),
                            thIndex = th.index(),
                            inverse = false;

                        th.click(function(){
                            this_table.find('td').filter(function(){
                                return $(this).index() === thIndex;
                            }).sortElements(function(a, b){
                                return $.text([a]) > $.text([b]) ?
                                    inverse ? -1 : 1
                                    : inverse ? 1 : -1;
                            }, function(){
                                return this.parentNode;
                            });
                            if(inverse){
                                th.find('.switch_ico').removeClass('sorting_asc').addClass('sorting_desc');
                            } else {
                                th.find('.switch_ico').removeClass('sorting_desc').addClass('sorting_asc');
                            }
                            inverse = !inverse;

                        }).trigger('click');
                    });
            });


        })
        //<?php echo base64_encode($mydata[$i][0]['dynamic_route_id']) ?>
    </script>
    <style>
        .sub_table thead th{
            color: #7c7c7c;
            font-size: 13px;
        }
    </style>
