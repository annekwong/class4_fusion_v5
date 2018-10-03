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
</style>

<?php if(!isset($w)){ $w = $session->read('writable');}?>
<?php
$mydata =$p->getDataArray();	$loop = count($mydata);
if (count($loop) == 0) {?>
    <h2 class="msg center"><?php echo __('no_data_found')?></h2>
<?php } else {?>

    <div class="clearfix"></div>
    <?php //*********************鏌ヨ鏉′欢********************************?>
    <?php //*********************琛ㄦ牸澶?************************************?>
    <div class="overflow_x">
        <table id="mytable" class="list table-hover footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
            <thead>
            <tr>
                <?php if($w){?>
                    <th class="footable-first-column expand" data-class="expand"  ><input type="checkbox" onclick="checkAll(this,'mytable');" value=""/></th>
                <?php }?>
                <th>
                    <?php echo __('host_ip')?>&nbsp;
                </th>
                <th>	<?php echo $appCommon->show_order('resource_id', __('Egress ID',true))?> </th>
                <th>	<?php echo $appCommon->show_order('alias', __('Egress Name',true))?> </th>
                <!--    		<th>	<?php echo $appCommon->show_order('resource_id','ID')?> </th>-->
                <th>	<?php echo $appCommon->show_order('name', __('Carriers',true))?> </th>
                <th>	<?php echo $appCommon->show_order('capacity', __('Call limit',true))?> </th>
                <th>	<?php echo $appCommon->show_order('cps_limit', __('CPS Limit',true))?> </th>

                <!--                <th>	<?php echo $appCommon->show_order('ip_cnt','Trunk Count')?> </th>-->
                <th data-hide="phone,tablet"  style="display: table-cell;"><?php __('Usage Count'); ?></th>
                <th data-hide="phone,tablet"  style="display: table-cell;"><?php __('rateTable')?></th>
                <th  data-hide="phone,tablet"  style="display: table-cell;"><?php __('proto')?></th>
                <th  data-hide="phone,tablet"  style="display: table-cell;"><?php __('pddtimeout')?></th>
                <th  data-hide="phone,tablet"  style="display: table-cell;"><?php __('Update At')?></th>
                <th  data-hide="phone,tablet"  style="display: table-cell;"><?php __('Update By')?></th>
                <?php  if ($_SESSION['role_menu']['Routing']['gatewaygroups:view']['model_w']) {?>
                    <?php if($w){?>
                        <th  data-hide="phone,tablet"  style="display: table-cell;" class="footable-last-column" style="width:10%"><?php echo __('action')?></th>
                    <?php }?>
                <?php }?>
            </tr>
            </thead>
            <tbody>
            <?php 	for ($i=0;$i<$loop;$i++) {?>

                <tr id="resource_tr_<?php echo $i?>" style="<?php if($mydata[$i][0]['active'] == 0) echo 'background:#ccc;';?>">
                    <?php if($w){?>
                        <td  class="footable-first-column expand" data-class="expand"   style="text-align:center"><input type="checkbox" value="<?php echo $mydata[$i][0]['resource_id']?>"/></td>
                    <?php }?>

                    <td  align="center"  style="font-weight: bold;">
                        <img   id="image<?php echo $i; ?>"  		onclick="show_resource_ip(this,'<?php echo $mydata[$i][0]['resource_id']?>','<?php echo $i?>');"    class=" jsp_resourceNew_style_1"  src="<?php echo $this->webroot?>images/+.gif"   title="<?php echo __('viewip')?>"/>
                    </td>
                    <td><?php echo $mydata[$i][0]['resource_id']?></td>
                    <td  align="center">

                        <a  style="width:80%;display:block" href="<?php echo $this->webroot?>prresource/gatewaygroups/edit_resouce_egress/<?php echo base64_encode($mydata[$i][0]['resource_id'])?>?back_get_url=<?php echo $this->params['getUrl'] ?><?php  echo isset($_GET['query']['id_clients']) ? '&query[id_clients]='.$_GET['query']['id_clients'] : '' ?>"  class="link_width" title="<?php echo __('edit')?>">
                            <?php echo $mydata[$i][0]['alias']?>
                        </a>


                    </td>
                    <!--		  			 <td  align="center">
                                                                        <?php echo $mydata[$i][0]['resource_id']?>	
                                                 </td>-->
                    <td  align="center">
                        <a  style="width:90%;display:block" href="###" onclick="javascript:(window.location.href=$(this).attr('url'));" url="<?php echo $this->webroot?>clients/edit/<?php echo base64_encode($mydata[$i][0]['client_id'])?>" class="link_width" title="<?php echo __('edit')?>">
                            <?php echo $mydata[$i][0]['client_name']?>
                        </a>
                    </td>
                    <td  align="center"><?php  if(empty($mydata[$i][0]['capacity'])) {echo "Unlimited";}else{echo  $mydata[$i][0]['capacity']; }?></td>
                    <td ><?php  if(empty($mydata[$i][0]['cps_limit'])) {echo "Unlimited";}else{echo  $mydata[$i][0]['cps_limit']; }?></td>

                    <!--                <td align="center"><?php echo array_keys_value_empty($mydata,$i.'.0.ip_cnt',0)?></td>-->
                    <td align="center">
                        <a href="###" onclick="javascript:(window.location.href=$(this).attr('url'));" url="<?php echo $this->webroot ?>dynamicroutes/view?resource_id=<?php echo $mydata[$i][0]['resource_id']?>" title="Dynamic Usage Count">
                            <?php echo array_keys_value_empty($mydata,$i.'.0.dynamic_count',0)?>
                        </a>
                        /
                        <a href="###" onclick="javascript:(window.location.href=$(this).attr('url'));" url="<?php echo $this->webroot ?>products/product_list?resource_id=<?php echo $mydata[$i][0]['resource_id']?>" title="Product Usage Count">
                            <?php echo array_keys_value_empty($mydata,$i.'.0.static_count',0)?>
                        </a>
                    </td>

                    <td data-hide="phone,tablet"  style="display: table-cell;"><a style="width:90%;display:block" href="<?php echo $this->webroot?>clientrates/view/<?php echo base64_encode($mydata[$i][0]['rate_table_id']); ?>" class="link_width"><?php echo $mydata[$i][0]['rate_table_name']?></a></td>
                    <td data-hide="phone,tablet"  style="display: table-cell;"><?php echo $appGetewaygroup->proto($mydata[$i])?></td>
                    <td  data-hide="phone,tablet"  style="display: table-cell;"><?php echo $mydata[$i][0]['wait_ringtime180']?></td>
                    <td data-hide="phone,tablet"  style="display: table-cell;"><?php echo $mydata[$i][0]['update_at']?></td>
                    <td data-hide="phone,tablet"  style="display: table-cell;"><?php echo $mydata[$i][0]['update_by']?></td>
                    <?php  if ($_SESSION['role_menu']['Routing']['gatewaygroups:view']['model_w']) {?>

                        <td data-hide="phone,tablet" class="footable-last-column"  style="display: table-cell;">
                            <div  class="action_icons">
                                <a title="<?php __('Send Interop'); ?>" onclick="myconfirm('<?php __('sure to do that'); ?>?',this);return false;"
                                   href="<?php echo $this->webroot ?>prresource/gatewaygroups/send_interop/<?php echo base64_encode($mydata[$i][0]['resource_id']) ?>/view_egress">
                                    <i class="icon-envelope"></i>
                                </a>
                                <a href="#myModal_egress_template" title="Save as Template" class="add_template" data-toggle="modal" resource="<?php echo $mydata[$i][0]['resource_id']?>">
                                    <i class="icon-bookmark-empty"></i>
                                </a>
                                <?php if($mydata[$i][0]['active']==1){?>
                                    <a onclick="return myconfirm('Are you sure you would like to inactive the selected <?php echo $mydata[$i][0]['alias']?>!', this);"
                                       href="<?php echo $this->webroot?>gatewaygroups/dis_able/<?php echo base64_encode($mydata[$i][0]['resource_id'])?>/view_egress/<?php echo $this->params['getUrl']?>" title="<?php echo __('Inactive')?>">
                                        <i class="icon-check"></i>
                                    </a>
                                <?php }else{?>
                                <a  onclick="return myconfirm('Are you sure you would like to active the selected <?php echo $mydata[$i][0]['alias']?>!', this);"
                                    href="<?php echo $this->webroot?>gatewaygroups/active/<?php echo base64_encode($mydata[$i][0]['resource_id'])?>/view_egress/<?php echo $this->params['getUrl']?>" title="<?php echo __('Active')?>">
                                    <i class="icon-check-empty"></i><?php }?>
                                </a>


                                <a  href="<?php echo $this->webroot?>prresource/gatewaygroups/edit_resouce_egress/<?php echo base64_encode($mydata[$i][0]['resource_id'])?>?back_get_url=<?php echo $this->params['getUrl'] ?>&<?php echo $this->params['getUrl']?>"  title="<?php echo __('edit')?>">
                                    <i class="icon-edit"></i>
                                </a>
                                <a  onclick="return myconfirm('Are you sure to delete ,egress trunk <?php echo $mydata[$i][0]['alias']?>?', this);" href="<?php echo $this->webroot?>prresource/gatewaygroups/del/<?php echo base64_encode($mydata[$i][0]['resource_id'])?>/view_egress?<?php echo $this->params['getUrl']?>" title="<?php echo __('del')?>">
                                    <i class="icon-remove"></i>
                                </a>
                            </div>
                        </td>
                    <?php }?>
                </tr>
                <!--
            <tr style="height:0px;border:0px;">
                <td colspan='20'>
                    <div id="ipInfo<?php echo $i?>" class=" jsp_resourceNew_style_2" style="display:none;">
                        <table class='table table-condensed'>
                            <tr>
                                <td>
                                    <div id="ipTable<?php echo $i?>" class=" jsp_resourceNew_style_3"></div>
                                    <script type="text/javascript">
                                        createTable('<?php echo $this->webroot?>',<?php echo $mydata[$i][0]['resource_id']?>,<?php echo $i?>);
                                    </script>
                                </td>
                            </tr>
                        </table>
                    </div>
                </td>
            </tr>
            				-->
            <?php }?></tbody>
        </table>
    </div>
    <div class="row-fluid">

        <div class="pagination pagination-large pagination-right margin-none pull-right">

            <?php echo $this->element('page'); ?>

        </div>


        <div class="clearfix"></div>
    </div>
    <div class="clearfix"></div>
<?php }?>

<!--    add template-->
<form action="<?php echo $this->webroot; ?>template/add_resource_template_by_trunk" id="add_template_form"  method="post">
    <input type="hidden" value="1" name="trunk_type" />
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

        $("#add_template_form").submit(function(){
            var flg = true;
            var template_name = $(this).find("input[name='template_name']").eq(0).val();
            var template_id = '';
            $.ajax({
                'url': '<?php echo $this->webroot ?>template/judge_template_name_unique',
                'type': 'POST',
                'async': false,
                'dataType': 'json',
                'data': {'template_name': template_name,'template_id':template_id},
                'success': function (data) {
                    if(data)
                    {
                        jGrowl_to_notyfy('<?php __('Template name'); ?>['+template_name+ ']<?php __('already exists'); ?>', {theme: 'jmsg-error'});
                        flg = false;
                    }
                }
            });
            return flg;
        });

    })
</script>
    