<style type="text/css">
    .overflow_x{
        overflow-x: auto;
        margin-bottom: 10px;
    }
</style>
<style>
    .btn-group .dropdown-menu > li {
        clear: both;
        color: #333333;
        display: block;
        font-weight: normal;
        line-height: 20px;
        padding: 3px 20px;
        white-space: nowrap;
    }

    .btn-group .dropdown-menu > li:hover, .btn-group .dropdown-menu > li:focus {
        background-color: #f5f5f5;
        /*color: #fff;*/
        text-decoration: none;
        font-weight: bolder;
    }
    tbody tr td a, td img {
        cursor: pointer;
    }
</style>
<?php $w = $session->read('writable'); ?>

<?php //*********************  鏉′欢********************************?>
<!--
<fieldset class="title-block" id="advsearch"  style="width: 100%;clear:both;">
         <form  action=""  method="get">
<table>
<tbody>
<tr>
        <td id="client_cell" class="value" style="text-align:right;width:600px">
    <label><?php echo __('Carriers') ?>:</label>
    <input class="input in-hidden" name="query[id_clients]" value="" id="query-id_clients" type="hidden" style="width:120px">
    <input type="text" id="query-id_clients_name" onclick="showClients()" style="width:120px;" readonly="1" value="" name="query[id_clients_name]" class="input in-text">        
    <img width="25" height="25" onclick="showClients()" class="img-button" src="<?php echo $this->webroot ?>images/search-small.png">
    <img width="25" height="25" onclick="ss_clear('client', _ss_ids_client)" class="img-button" src="<?php echo $this->webroot ?>images/delete-small.png">
                &nbsp;&nbsp;&nbsp;&nbsp;
    <label>Ip:</label>
<?php echo $xform->search('filter_ip', Array("style" => "width:120px", 'value' => $$hel->_get('filter_ip'))) ?>
  </td>
  <td class="buttons" style="width:50px"><input type="submit" value="Search" class="input in-submit"></td>
</tr>
</tbody></table>
</form></fieldset>
-->
<?php
$mydata = $p->getDataArray();
if (count($mydata) == 0)
{
    ?>
    <h2 class="msg center"><?php echo __('no_data_found') ?></h2>
<?php }
else
{ ?>
    <div class="clearfix"></div>
    <?php //*********************鏌ヨ鏉′欢******************************** ?>
    <?php //*********************琛ㄦ牸澶?************************************ ?>
    <div class="overflow_x">
        <table class="list table-hover footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
            <thead>
            <tr>
                <th class="footable-first-column expand" data-class="expand"><input type="checkbox" onclick="checkAll(this, 'DataTables_Table_0');" value=""/></th>
                <th><?php echo __('host_ip') ?>&nbsp;</th>
                <th>	<?php echo $appCommon->show_order('ID', __('Ingress ID',true)) ?> </th>
                <th>	<?php echo $appCommon->show_order('alias', __('Ingress Name', true)) ?> </th>
                <!-- 	    		<th>	<?php echo $appCommon->show_order('resource_id', __('ID', true)) ?> </th>   -->
                <th>	<?php echo $appCommon->show_order('client_id', __('Carriers', true)) ?> </th>
                <th>	<?php echo $appCommon->show_order('capacity', __('Call limit', true)) ?> </th>
                <th>	<?php echo $appCommon->show_order('cps_limit', __('CPS Limit', true)) ?> </th>

                <th data-hide="phone,tablet"  style="display: table-cell;">	<?php echo $appCommon->show_order('profit_margin', __('Margin', true)) ?> </th>
                <!--
                <th data-hide="phone,tablet"  style="display: table-cell;"><?php echo __('Rate Table', true); ?> </th>
                <th data-hide="phone,tablet"  style="display: table-cell;"><?php echo __('Used By', true); ?> </th>
                <th data-hide="phone,tablet"  style="display: table-cell;"><?php __('Routing Plan') ?></th>
                <th data-hide="phone,tablet"  style="display: table-cell;"><?php __('proto') ?></th>
                -->
                <th data-hide="phone,tablet"  style="display: table-cell;"><?php __('pddtimeout') ?></th>
                <th data-hide="phone,tablet"  style="display: table-cell;"><?php echo __('Update At', true); ?></th>
                <th data-hide="phone,tablet"  style="display: table-cell;"><?php echo __('Update By', true); ?></th>
                <?php if($_SESSION['login_type'] != 2): ?>
                    <?php if ($_SESSION['role_menu']['Routing']['gatewaygroups:view']['model_w'])
                    { ?><th data-hide="phone,tablet" class="footable-last-column center"  style="display: table-cell;"><?php echo __('action') ?></th>
                    <?php } ?>
                <?php else: ?>
                    <?php if($_SESSION['sst_agent_info']['Agent']['edit_permission']): ?>
                        <th data-hide="phone,tablet" class="footable-last-column center"  style="display: table-cell;"><?php echo __('action') ?></th>
                    <?php endif; ?>
                <?php endif; ?>
            </tr>
            </thead>
            <tbody>
            <?php $loop = count($mydata);
            for ($i = 0; $i < $loop; $i++)
            { ?>

                <tr id="resource_tr_<?php echo $i?>" class="row-<?php echo $i % 2 + 1; ?>">
                    <td class="footable-first-column expand center" data-class="expand"><input type="checkbox" value="<?php echo $mydata[$i][0]['resource_id'] ?>"/></td>
                    <td  align="center"  style="font-weight: bold;"><img   id="image<?php echo $i; ?>"  	onclick="show_resource_ip(this,'<?php echo $mydata[$i][0]['resource_id']?>','<?php echo $i?>');"    class=" jsp_resourceNew_style_1"  src="<?php echo $this->webroot ?>images/+.gif"   title="<?php echo __('viewip') ?>"/></td >
                    <td><?php echo $mydata[$i][0]['resource_id'] ?></td>
                    <td  align="center">

                        <a style="width:90%;display:block" href="<?php echo $this->webroot ?>prresource/gatewaygroups/edit_resouce_ingress/<?php echo base64_encode($mydata[$i][0]['resource_id']) ?>?back_get_url=<?php echo $this->params['getUrl'] ?><?php echo isset($_GET['query']['id_clients']) ? '&query[id_clients]=' . $_GET['query']['id_clients'] : '' ?>"  title="<?php echo __('edit') ?>">
                            <?php echo $mydata[$i][0]['alias'] ?>
                        </a>

                    </td>
                    <!--		   </td>
                                                  <td  align="center">
        <?php echo $mydata[$i][0]['resource_id'] ?>	
                                            </td>-->
                    <td  align="center">
                        <?php if($_SESSION['login_type'] == 2): ?>
                            <a style="width:90%;display:block" href="<?php echo $this->webroot ?>agent_portal/edit_client/<?php echo base64_encode($mydata[$i][0]['client_id']) ?>"  title="<?php echo __('edit') ?>">
                                <?php echo $mydata[$i][0]['client_name'] ?>
                            </a>
                        <?php else: ?>
                            <a style="width:90%;display:block" href="<?php echo $this->webroot ?>clients/edit/<?php echo base64_encode($mydata[$i][0]['client_id']) ?>"  title="<?php echo __('edit') ?>">
                                <?php echo $mydata[$i][0]['client_name'] ?>
                            </a>
                        <?php endif; ?>
                    </td>
                    <td  align="center"><?php if (empty($mydata[$i][0]['capacity']))
                        {
                            echo "Unlimited";
                        }
                        else
                        {
                            echo $mydata[$i][0]['capacity'];
                        } ?></td>
                    <td ><?php if (empty($mydata[$i][0]['cps_limit']))
                        {
                            echo "Unlimited";
                        }
                        else
                        {
                            echo $mydata[$i][0]['cps_limit'];
                        } ?></td>

                    <td data-hide="phone,tablet"  style="display: table-cell;"><?php echo empty($mydata[$i][0]['profit_margin']) ? '' : $mydata[$i][0]['profit_margin'] . '%'; ?></td>
                    <!--
                    <td data-hide="phone,tablet"  style="display: table-cell;">
                        <?php if (key_exists($mydata[$i][0]['resource_id'], $rate_table))
                        { ?>
                            <?php if($_SESSION['login_type'] == 2): ?>
                            <?php echo $appCommon->sub_string($rate_table[$mydata[$i][0]['resource_id']]['name'][0]); ?>
                        <?php else: ?>
                            <a href="<?php echo $this->webroot; ?>rates/rates_list?id=<?php echo $rate_table[$mydata[$i][0]['resource_id']]['rate_table_id']; ?>">
                                <?php echo $appCommon->sub_string($rate_table[$mydata[$i][0]['resource_id']]['name'][0]);; ?></a>
                        <?php endif; ?>

                        <?php } ?>
                    </td>
                    <td data-hide="phone,tablet"  style="display: table-cell;" align="center"><?php if (empty($mydata[$i][0]['client_id']))
                        {
                            echo 0;
                        }
                        else
                        {
                            echo 1;
                        } ?></td>
                    <td data-hide="phone,tablet"  style="display: table-cell;"><?php echo $mydata[$i][0]['rs_cnt'] ?></td>

                    <td  data-hide="phone,tablet"  style="display: table-cell;"><?php echo $appGetewaygroup->proto($mydata[$i]) ?></td>
                    -->
                    <td  data-hide="phone,tablet"  style="display: table-cell;"><?php echo $mydata[$i][0]['wait_ringtime180'] ?></td>
                    <td  data-hide="phone,tablet"  style="display: table-cell;"><?php echo $mydata[$i][0]['update_at'] ?></td>
                    <td  data-hide="phone,tablet"  style="display: table-cell;"><?php echo $mydata[$i][0]['update_by'] ?></td>
                    <?php if($_SESSION['login_type'] != 2): ?>
                        <?php if ($_SESSION['role_menu']['Routing']['gatewaygroups:view']['model_w'])
                        { ?>
                            <td data-hide="phone,tablet" class="footable-last-column center"  style="display: table-cell;">
                                <div >
                                    <a title="<?php __('Send Interop'); ?>" onclick="myconfirm('<?php __('sure to do that'); ?>?',this);return false;"
                                       href="<?php echo $this->webroot ?>prresource/gatewaygroups/send_interop/<?php echo base64_encode($mydata[$i][0]['resource_id']) ?>/view_ingress">
                                        <i class="icon-envelope"></i>
                                    </a>


                                    <a href="<?php echo $this->webroot ?>prresource/gatewaygroups/send_rate/<?php echo base64_encode($mydata[$i][0]['resource_id']) ?>" title="<?php __('Send Rate')?>" class="send_rate" >
                                        <i class="icon-share"></i>
                                    </a>

                                    <a href="#myModal_egress_template" title="Save as Template" class="add_template" data-toggle="modal" resource="<?php echo $mydata[$i][0]['resource_id']?>">
                                        <i class="icon-bookmark-empty"></i>
                                    </a>
                                    <?php if ($mydata[$i][0]['active'] == 1): ?>
                                        <a class="inactive" msg="Are you sure you would like to inactive the selected <?php echo $mydata[$i][0]['alias'] ?>!" href="###"
                                           url="<?php echo $this->webroot ?>gatewaygroups/dis_able/<?php echo base64_encode($mydata[$i][0]['resource_id']) ?>/view_ingress/<?php echo $this->params['getUrl']?>?jump=no" title="<?php echo __('disable') ?>">
                                            <i class="icon-check"></i>
                                        </a>
                                    <?php else: ?>
                                        <a class="active_" msg ='Are you sure you would like to active the selected <?php echo $mydata[$i][0]['alias'] ?>!' href="###"
                                           url="<?php echo $this->webroot ?>gatewaygroups/active/<?php echo base64_encode($mydata[$i][0]['resource_id']) ?>/view_ingress/<?php echo $this->params['getUrl']?>?jump=no" title="<?php echo __('enable') ?>">
                                            <i class="icon-check-empty"></i>
                                        </a>
                                    <?php endif; ?>

                                    <a href="<?php echo $this->webroot ?>prresource/gatewaygroups/edit_resouce_ingress/<?php echo base64_encode($mydata[$i][0]['resource_id']); ?>?back_get_url=<?php echo $this->params['getUrl'] ?>&<?php echo $this->params['getUrl'] ?>"  title="<?php echo __('edit') ?>">
                                        <i class="icon-edit"></i> </a>
                                    <a class="delete" msg = 'Are you sure to delete , ingress trunk <?php echo $mydata[$i][0]['alias'] ?>?' href="###"
                                       url="<?php echo $this->webroot ?>prresource/gatewaygroups/del/<?php echo base64_encode($mydata[$i][0]['resource_id']) ?>/view_ingress?<?php echo $this->params['getUrl'] ?>" title="<?php echo __('del') ?>">
                                        <i class="icon-remove"></i>
                                    </a>
                                </div>
                            </td>
                        <?php } ?>
                    <?php else: ?>
                        <td data-hide="phone,tablet" class="footable-last-column center"  style="display: table-cell;">
                            <div >
                                <a title="<?php __('Send Interop'); ?>" onclick="myconfirm('<?php __('sure to do that'); ?>?',this);return false;"
                                   href="<?php echo $this->webroot ?>prresource/gatewaygroups/send_interop/<?php echo base64_encode($mydata[$i][0]['resource_id']) ?>/view_ingress">
                                    <i class="icon-envelope"></i>
                                </a>

                                <a href="###" title="<?php __('Send Rate')?>" class="send_rate" resource_id="<?php echo $mydata[$i][0]['resource_id']; ?>">
                                    <i class="icon-share"></i>
                                </a>

                                <?php if($_SESSION['sst_agent_info']['Agent']['edit_permission']): ?>
                                    <?php if ($mydata[$i][0]['active'] == 1): ?>
                                        <a class="inactive" msg="Are you sure you would like to inactive the selected <?php echo $mydata[$i][0]['alias'] ?>!" href="###"
                                           url="<?php echo $this->webroot ?>gatewaygroups/dis_able/<?php echo base64_encode($mydata[$i][0]['resource_id']) ?>/view_ingress?jump=no" title="<?php echo __('disable') ?>">
                                            <i class="icon-check"></i>
                                        </a>
                                    <?php else: ?>
                                        <a class="active_" msg ='Are you sure you would like to active the selected <?php echo $mydata[$i][0]['alias'] ?>!' href="###"
                                           url="<?php echo $this->webroot ?>gatewaygroups/active/<?php echo base64_encode($mydata[$i][0]['resource_id']) ?>/view_ingress?jump=no" title="<?php echo __('enable') ?>">
                                            <i class="icon-check-empty"></i>
                                        </a>
                                    <?php endif; ?>
                                <?php endif; ?>
                                <a href="<?php echo $this->webroot ?>prresource/gatewaygroups/edit_resouce_ingress/<?php echo base64_encode($mydata[$i][0]['resource_id']); ?>?back_get_url=<?php echo $this->params['getUrl'] ?>&<?php echo $this->params['getUrl'] ?>"  title="<?php echo __('edit') ?>">
                                    <i class="icon-edit"></i> </a>
                                <?php if($_SESSION['sst_agent_info']['Agent']['edit_permission']): ?>
                                    <a class="delete" msg = 'Are you sure to delete , ingress trunk <?php echo $mydata[$i][0]['alias'] ?>?' href="###"
                                       url="<?php echo $this->webroot ?>prresource/gatewaygroups/del/<?php echo base64_encode($mydata[$i][0]['resource_id']) ?>/view_ingress?<?php echo $this->params['getUrl'] ?>" title="<?php echo __('del') ?>">
                                        <i class="icon-remove"></i>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </td>

                    <?php endif; ?>
                </tr>
            <?php } ?>
            </tbody>

        </table>
    </div>
    <?php //*********************琛ㄦ牸澶?************************************ ?>

    <div class="pagination pagination-large pagination-right margin-none pull-right">

        <?php echo $this->element('page'); ?>

    </div>


    <div class="clearfix"></div>
    <?php
}?>

<!--    add template-->
<form action="<?php echo $this->webroot; ?>template/add_resource_template_by_trunk" id="add_template_form"  method="post">
    <input type="hidden" value="0" name="trunk_type" />
    <input type="hidden" value="" id="add_resource_id" name="resource_id" />
    <div id="myModal_egress_template" class="modal hide">
        <div class="modal-header">
            <button data-dismiss="modal" class="close" type="button">&times;</button>
            <h3><?php __('Save as Template'); ?></h3>
        </div>
        <div class="separator"></div>
        <div class="widget-body">
            <table class="table table-bordered">
                <tr>
                    <td class="align_right"><?php echo __('Template Name')?> </td>
                    <td>
                        <input class="width220 validate[required]" name="template_name"  type="text" >
                    </td>
                </tr>
            </table>
        </div>
        <div class="modal-footer">
            <input type="submit" class="btn btn-primary" value="<?php __('Submit'); ?>">
            <a href="javascript:void(0)"  data-dismiss="modal" class="btn btn-default"><?php __('Close'); ?></a>
        </div>

    </div>
</form>

<div id="dd"> </div>

<link rel="stylesheet" type="text/css" href="<?php echo $this->webroot ?>easyui/themes/default/easyui.css">
<!--<link rel="stylesheet" type="text/css" href="<?php echo $this->webroot ?>easyui/themes/icon.css">-->
<script type="text/javascript" src="<?php echo $this->webroot ?>easyui/jquery.easyui.min.js"></script>

<script type="text/javascript">
    function show_resource_ip(opt,resourceId,count)
    {
        if($("#ipInfo"+count).size() == 0)
        {
            var html = "<tr><td colspan='20'><div id='ipInfo"+count+"' class='jsp_resourceNew_style_2' style='display:none;'><table class='table'><tr><td><div id='ipTable"+count+"' class='jsp_resourceNew_style_3'></div></td></tr></table></div></td></tr>";
            $("#resource_tr_"+count).after(html);
            createTable('<?php echo $this->webroot?>',resourceId,count);
        }
        pull('<?php echo $this->webroot?>',opt,count);
    }
    $(function(){
        $(".add_template").click(function(){
            var resource_id = $(this).attr('resource');
            $("#add_resource_id").val(resource_id);
        });
        $(".delete").click(function(){
            var url = $(this).attr('url');
            var msg = $(this).attr('msg');
            bootbox.confirm(msg,function(result){
                if (result) {
                    window.location.href = url;
                }
            });
        });
        $(".active_").click(function(){
            var url = $(this).attr('url');
            var msg = $(this).attr('msg');
            bootbox.confirm(msg,function(result){
                if (result) {
                    window.location.href = url;
                }
            });
        });
        $(".inactive").click(function(){
            var url = $(this).attr('url');
            var msg = $(this).attr('msg');
            bootbox.confirm(msg,function(result){
                if (result) {
                    window.location.href = url;
                }
            });
        });

    });

</script>
        