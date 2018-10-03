
<?php
$enableActionColumn = $systemParams['enable_client_download_rate'] == 'true' || $systemParams['enable_client_delete_trunk'] == 'true' || $systemParams['enable_client_disable_trunk'] == 'true';
?>

<script src="<?php echo $this->webroot?>js/ajaxTable.js" type="text/javascript"></script>
<?php $w = $session->read('writable');?>
<ul class="breadcrumb">
    <li><?php __('You are here'); ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <?php if($_SESSION['login_type'] == 3):?>
        <li><?php __('Client Portal') ?></li>
    <?php else:?>
        <li><?php __('Management') ?></li>
    <?php endif;?>

    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Ingress Trunk') ?></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php __('Ingress Trunk') ?></h4>

</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
    <?php
    $login_type = $_SESSION['login_type'];
    if (Configure::read('portal.add_ingress')):
        $_SESSION['role_menu']['Routing']['gatewaygroups:view']['model_r'] = true;
        $_SESSION['role_menu']['Routing']['gatewaygroups:view']['model_w'] = true;
        $_SESSION['role_menu']['Template']['template']['model_w'] = true;
        ?>
        <?php
        if ((Configure::read('portal.add_ingress') && $login_type == 3) ||  (((isset($_SESSION['new_user']) && $_SESSION['new_user'] == false) || !isset($_SESSION['new_user'])) && $login_type != 3)):
            ?>
            <a class="btn btn-primary btn-icon glyphicons circle_plus" href="<?php echo $this->webroot ?>prresource/gatewaygroups/add_resouce_ingress/<?php echo $sst_client_id;?>"><i></i> <?php __('Create New')?></a>
        <?php endif;?>
    <?php endif; ?>
</div>
<div class="clearfix"></div>


<div class="innerLR">

    <div class="widget widget-body-white">
        <!--		<div class="widget-head">-->
        <!--			<ul class="tabs">-->
        <!--				<li class="active">-->
        <!--					<a class="glyphicons left_arrow"  href="--><?php //echo $this->webroot ?><!--clients/view_ingress">-->
        <!--						<i></i>-->
        <!--						--><?php //__('ingress') ?>
        <!--					</a>-->
        <!--				</li>-->
        <!--				<li>-->
        <!--					<a class="glyphicons right_arrow"  href="--><?php //echo $this->webroot ?><!--clients/view_egress" >-->
        <!--						<i></i> --><?php //__('egress') ?>
        <!--					</a>-->
        <!--				</li>-->
        <!--			</ul>-->
        <!--		</div>-->
        <div class="widget-body">

            <?php $d = $p->getDataArray();?>
            <?php if (count($d) == 0) :?>
                <div class="msg center">
                    <h2><?php echo __('no_data_found')?></h2>
                </div>
            <?php else: ?>
                <div class="overflow_x">
                    <table id="mytable" class="list table-hover footable table table-striped  tableTools table-bordered  table-white table-primary">
                        <thead>
                        <tr>
                            <?php if($w){?>
                                <th class="footable-first-column expand" data-class="expand"  ><input type="checkbox" onclick="checkAll(this,'mytable');" value=""/></th>
                            <?php }?>
                            <th>	<?php echo $appCommon->show_order('client_name', __('Ingress Name',true))?> </th>
                            <th>
                                <?php echo __('host_ip')?>&nbsp;
                            </th>
                            <th>	<?php echo  __('Product Name',true)?> </th>
                            <th>	<?php echo  __('Tech-Prefix',true)?> </th>
                            <th>	<?php echo $appCommon->show_order('capacity', __('Call limit',true))?> </th>
                            <th>	<?php echo $appCommon->show_order('cps_limit', __('CPS Limit',true))?> </th>
                            <?php if ($enableActionColumn): ?>
                                <th  data-hide="phone,tablet"  style="display: table-cell;" class="footable-last-column" style="width:10%"><?php echo __('action')?></th>
                            <?php endif; ?>
                        </tr>
                        </thead>
                        <tbody>
                        <?php 	for ($i=0;$i<count($d);$i++) {?>

                            <?php

                            $productNames = array();

                            foreach ($d[$i] as $item) {
                                if (isset($item['ProductRouteRateTable']['product_name']) && !empty($item['ProductRouteRateTable']['product_name'])) {
                                    array_push($productNames, $item['ProductRouteRateTable']['product_name']);
                                }
                            }

                            ?>
                            <tr style="<?php if($d[$i][0]['active'] == 0) echo 'background:#ccc;';?>">
                                <?php if($w){?>
                                    <td  class="footable-first-column expand" data-class="expand"   style="text-align:center"><input type="checkbox" value="<?php echo $d[$i][0]['resource_id']?>"/></td>
                                <?php }?>
                                <td><?php echo $d[$i][0]['alias']?></td>
                                <td>
                                    <a data-toggle="modal" href="#myModal_ip<?php echo $i; ?>" title="IP List">
                                        <i class="icon-list"></i>
                                    </a>
                                </td>

                                <td  align="center"><?php  echo isset($d[$i][0]['prefix']) ? $d[$i][0]['prefix'] : '';?></td>
                                <td  align="center"><?php  echo implode(',', $productNames);?></td>
                                <td  align="center"><?php  if(empty($d[$i][0]['capacity'])) {echo "Unlimited";}else{echo  $d[$i][0]['capacity']; }?></td>
                                <td ><?php  if(empty($d[$i][0]['cps_limit'])) {echo "Unlimited";}else{echo  $d[$i][0]['cps_limit']; }?></td>
                                <?php if ($enableActionColumn): ?>
                                    <td data-hide="phone,tablet" class="footable-last-column"  style="display: table-cell;">
                                        <div  class="action_icons">
                                            <!--										<a title="Product List" href="--><?php //echo $this->webroot; ?><!--clients/product_list/--><?php //echo base64_encode($d[$i][0]['resource_id'])?><!--">-->
                                            <!--											<i class="icon-list"></i>-->
                                            <!--										</a>-->
                                            <?php if ($systemParams['enable_client_disable_trunk'] == 'true'): ?>
                                                <?php if($d[$i][0]['active']==1){?>
                                                    <a onclick="return myconfirm('Are you sure you would like to inactivate the selected Ingress Trunk [<?php echo $d[$i][0]['alias']?>] ?', this);"
                                                       href="<?php echo $this->webroot?>clients/dis_able_resource/<?php echo base64_encode($d[$i][0]['resource_id'])?>/view_ingress?<?php echo $this->params['getUrl']?>" title="<?php echo __('Inactive')?>">
                                                        <i class="icon-check"></i>
                                                    </a>
                                                <?php }else{?>
                                                    <a  onclick="return myconfirm('Are you sure you would like to activate the selected Ingress Trunk [<?php echo $d[$i][0]['alias']?>] ?', this);"
                                                        href="<?php echo $this->webroot?>clients/active_resource/<?php echo base64_encode($d[$i][0]['resource_id'])?>/view_ingress?<?php echo $this->params['getUrl']?>" title="<?php echo __('Active')?>">
                                                        <i class="icon-check-empty"></i>
                                                    </a>
                                                <?php }?>
                                            <?php endif; ?>

                                            <?php if ($systemParams['enable_client_download_rate'] == 'true'): ?>
                                                <a target="_blank" href="<?php echo $this->webroot?>clients/download_rate/<?php echo base64_encode($d[$i][0]['rate_table_id']);?>" title="<?php __('Download Rate'); ?>"><i class="icon-download"></i></a>
                                            <?php endif; ?>
                                            <?php if ($systemParams['enable_client_delete_trunk'] == 'true'): ?>
                                                <a  onclick="return myconfirm('Are you sure to delete Ingress Trunk [<?php echo $d[$i][0]['alias']?>]?', this);" href="<?php echo $this->webroot?>clients/del_resource/<?php echo base64_encode($d[$i][0]['resource_id'])?>/view_ingress?<?php echo $this->params['getUrl']?>" title="<?php echo __('del')?>">
                                                    <i class="icon-remove"></i>
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                <?php endif; ?>
                            </tr>
                        <?php }?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php 	for ($i=0;$i<count($d);$i++) :?>
    <div id="myModal_ip<?php echo $i; ?>" class="modal hide">
        <div class="modal-header">
            <button data-dismiss="modal" class="close" type="button">&times;</button>
            <h3><?php echo __('Ingress',true)."[".$d[$i][0]['alias']."] IP"; ?></h3>
        </div>
        <div class="modal-body">
            <?php if(isset($resource_ip_arr[$i]['resource_ip'][0][0]['need_register']) && $resource_ip_arr[$i]['resource_ip'][0][0]['need_register'] == 1): ?>
                <?php if($change_ip): ?>
                    <div class="buttons pull-right newpadding">
                        <a table_type="1" tbody_id = "tbody<?php echo $i; ?>" class="link_btn btn btn-primary btn-icon glyphicons circle_plus ip_add_btn" href="javascript:void(0)">
                            <i></i>
                            <?php __('Create new'); ?>
                        </a>
                    </div>
                <?php endif; ?>

                <table class="table table-bordered table-primary" resource_id = "<?php echo $d[$i][0]['resource_id']; ?>">
                    <thead>
                    <tr>
                        <th><?php __('Username'); ?></th>
                        <th><?php __('Password'); ?></th>
                        <?php if($change_ip): ?>
                            <th><?php __('Action'); ?></th>
                        <?php endif; ?>
                    </tr>
                    </thead>
                    <tbody id="tbody<?php echo $i; ?>">
                    <?php foreach ($resource_ip_arr[$i]['resource_ip'] as $key =>$resource_ip_item): ?>
                        <tr>
                            <td><?php echo $resource_ip_item[0]['username']; ?></td>
                            <td>******</td>
                            <?php if($change_ip): ?>
                                <td>
                                    <a class="sip_edit_btn" re_ip_id="<?php echo $resource_ip_item[0]['resource_ip_id']; ?>" title="save" href="javascript:void(0)"><i class="icon-edit"></i></a>
                                    <a class="delete_btn" re_ip_id="<?php echo $resource_ip_item[0]['resource_ip_id']; ?>" href="javascript:void(0)" title="Delete">
                                        <i class="icon-remove"></i>
                                    </a>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <?php if($change_ip): ?>
                    <div class="buttons pull-right newpadding">
                        <a  table_type="2" tbody_id = "tbody<?php echo $i; ?>" class="link_btn btn btn-primary btn-icon glyphicons circle_plus ip_add_btn" href="javascript:void(0)">
                            <i></i>
                            <?php __('Create new'); ?>
                        </a>
                    </div>
                <?php endif; ?>
                <table class="table table-bordered table-primary" resource_id = "<?php echo $d[$i][0]['resource_id']; ?>">
                    <thead>
                    <tr>
                        <th><?php __('IP'); ?></th>
                        <th><?php __('Port'); ?></th>
                        <th><?php __('CPS'); ?></th>
                        <?php if($change_ip): ?>
                            <th><?php __('Action'); ?></th>
                        <?php endif; ?>
                    </tr>
                    </thead>
                    <tbody id="tbody<?php echo $i; ?>">
                    <?php foreach ($resource_ip_arr[$i]['resource_ip'] as $key =>$resource_ip_item): ?>
                        <tr>
                            <td><?php echo $resource_ip_item[0]['ip']; ?></td>
                            <td><?php echo $resource_ip_item[0]['port']; ?></td>
                            <td><?php echo $resource_ip_item[0]['cps']; ?></td>
                            <?php if($change_ip): ?>
                                <td>
                                    <a class="ip_edit_btn" re_ip_id="<?php echo $resource_ip_item[0]['resource_ip_id']; ?>" title="save" href="javascript:void(0)"><i class="icon-edit"></i></a>
                                    <a class="delete_btn" re_ip_id="<?php echo $resource_ip_item[0]['resource_ip_id']; ?>" href="javascript:void(0)" title="Delete">
                                        <i class="icon-remove"></i>
                                    </a>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
<?php endfor; ?>
<table class="hide">
    <tr class="sip_tr">
        <td><input type="text"  name="username" class="username validate[required]" /></td>
        <td><input type="password" class="pass_word " /></td>
        <td>
            <a class="sip_save_btn" re_ip_id="" title="save" href="javascript:void(0)"><i class="icon-save"></i></a>
            <a onclick="$(this).closest('tr').remove();" href="javascript:void(0)" title="Cancel">
                <i class="icon-remove"></i>
            </a>
        </td>
    </tr>
    <tr class="ip_tr">
        <td><input type="text" name="ip"  class="width120 ip validate[required,custom[ipv4]]" /></td>
        <td><input type="text" name="port" class="width50 port validate[required,custom[integer]]" /></td>
        <td></td>
        <td>
            <a class="ip_save_btn" re_ip_id="" title="Save" href="javascript:void(0)"><i class="icon-save"></i></a>
            <a onclick="$(this).closest('tr').remove();" href="javascript:void(0)" title="Delete">
                <i class="icon-remove"></i>
            </a>
        </td>
    </tr>

    <tr class="sip_tr_edit">
        <td><input type="text" name="username" class="username" /></td>
        <td><input type="password" class="pass_word" /></td>
        <td>
            <a class="sip_save_btn" re_ip_id="" title="save" href="javascript:void(0)"><i class="icon-save"></i></a>
            <a class="save_cancel" href="javascript:void(0)" title="Delete">
                <i class="icon-remove"></i>
            </a>
        </td>
    </tr>
    <tr class="ip_tr_edit">
        <td><input type="text" name="ip"  class="width120 ip validate[required,custom[ipv4]]" /></td>
        <td><input type="text" name="port" class="width50 port validate[required,custom[integer]]" /></td>
        <td></td>
        <td>
            <a class="ip_save_btn" re_ip_id="" title="Save" href="javascript:void(0)"><i class="icon-save"></i></a>
            <a class="save_cancel" href="javascript:void(0)" title="Cancel">
                <i class="icon-remove"></i>
            </a>
        </td>
    </tr>
</table>
<script type="text/javascript">
    $(function(){
        var sip_tr = $(".sip_tr").eq(0).remove();
        var ip_tr = $(".ip_tr").eq(0).remove();
        var sip_tr_edit = $(".sip_tr_edit").eq(0).remove();
        var ip_tr_edit = $(".ip_tr_edit").eq(0).remove();
        $(".ip_add_btn").click(function(){
            var $tbody_id = $(this).attr('tbody_id');
            var $table_type = $(this).attr('table_type');
            if($table_type == 1)
                sip_tr.clone(true).prependTo("#"+$tbody_id);
            else
                ip_tr.clone(true).prependTo("#"+$tbody_id);
        });

        $(".sip_save_btn").live('click',function(){
            var user_name = $(this).closest('tr').children().eq(0).children().eq(0).val();
            var password = $(this).closest('tr').children().eq(1).children().eq(0).val();
            var clear_tr = $(this).closest('tr').next();
            var resource_id = $(this).closest('table').attr('resource_id');
            var re_ip_id = $(this).attr('re_ip_id');
            $(this).closest('tr').find('.username').validationEngine('validate');
            if(!re_ip_id)
            {
                $(this).closest('tr').find('.pass_word').addClass('validate[required,minSize[6]]');
                $(this).closest('tr').find('.pass_word').validationEngine('validate');
            }
            else
            {
                if(password)
                {
                    $(this).closest('tr').find('.pass_word').addClass('validate[minSize[6]]');
                    $(this).closest('tr').find('.pass_word').validationEngine('validate');
                }
            }
            if(!user_name)
                return false;
            if(!re_ip_id && ! password)
                return false;
            if(password.length < 6 && password.length > 0)
                return false;
            var $this = $(this);
            $.ajax({
                'url': '<?php echo $this->webroot ?>clients/ajax_save_resource_ip',
                'type': 'POST',
                'dataType': 'json',
                'data': {'user_name': user_name, 'password': password,'type':'sip',resource_id:resource_id,re_ip_id:re_ip_id},
                'success': function(data) {
                    if (!data.flg) {
                        var msg = data.msg;
                        jGrowl_to_notyfy(msg,{theme:'jmsg-error'});
                    } else {
                        var clone_result = clear_tr.clone(true);
                        clone_result.children().eq(0).html(user_name);
                        clone_result.find('a').eq(0).attr('re_ip_id',data.re_ip_id);
                        clone_result.find('a').eq(1).attr('re_ip_id',data.re_ip_id);
                        $this.closest('tr').before(clone_result);
                        clone_result.show();
                        $this.closest('tr').remove();
                        jGrowl_to_notyfy('<?php __('SIP is created successfully!'); ?>',{theme:'jmsg-success'});
                    }

                }
            });
        });

        $(".delete_btn").live('click',function(){
            var re_ip_id = $(this).attr('re_ip_id');
            var $this = $(this);
            var $this_div = $this.parent().parent().parent().parent().parent().parent();
            $this_div.hide();
            bootbox.confirm('<?php __('sure to delete'); ?>', function(result) {
                if(result) {
                    $.ajax({
                        'url': '<?php echo $this->webroot ?>clients/ajax_delete_resource_ip',
                        'type': 'POST',
                        'dataType': 'json',
                        'data': {'re_ip_id': re_ip_id},
                        'success': function(data) {
                            if(data.flg)
                            {
                                jGrowl_to_notyfy('<?php __('succeed'); ?>',{theme:'jmsg-success'});
                                $this.closest('tr').remove();
                            }
                            else
                                jGrowl_to_notyfy('<?php __('failed'); ?>',{theme:'jmsg-error'});
                            $this_div.show();
                        }
                    });
                }
                else
                {
                    $this_div.show();
                }
            });

        });

        $(".sip_edit_btn").live('click',function(){
            var re_ip_id = $(this).attr('re_ip_id');
            var $this = $(this);
            var hide_tr = $this.closest('tr');
            var user_name = hide_tr.children().eq(0).html();
            var closest_tbody = $this.closest('tbody');
            sip_tr_edit.children().eq(0).find('input').val(user_name);
            sip_tr_edit.find('a').eq(0).attr('re_ip_id',re_ip_id);
            hide_tr.before(sip_tr_edit.clone(true));
            hide_tr.hide();
        });

        $(".save_cancel").live('click',function(){
            $(this).closest('tr').next().show();
            $(this).closest('tr').remove();
        });




        $(".ip_save_btn").live('click',function(){
            var ip = $(this).closest('tr').children().eq(0).children().eq(0).val();
            var port = $(this).closest('tr').children().eq(1).children().eq(0).val();
            var clear_tr = $(this).closest('tr').next();
            var resource_id = $(this).closest('table').attr('resource_id');
            var re_ip_id = $(this).attr('re_ip_id');
            var flg1 = $(this).closest('tr').find('.ip').validationEngine('validate');
            var flg2 = $(this).closest('tr').find('.port').validationEngine('validate');
            if (flg1 || flg2)
                return false;
            var $this = $(this);
            $.ajax({
                'url': '<?php echo $this->webroot ?>clients/ajax_save_resource_ip',
                'type': 'POST',
                'dataType': 'json',
                'data': {'ip': ip, 'port': port,'type':'ip',resource_id:resource_id,re_ip_id:re_ip_id},
                'success': function(data) {
                    if (!data.flg) {
                        var msg = data.msg;
                        jGrowl_to_notyfy(msg,{theme:'jmsg-error'});
                    } else {
                        var clone_result = clear_tr.clone(true);
                        clone_result.children().eq(0).html(ip);
                        clone_result.children().eq(1).html(port);
                        clone_result.find('a').eq(0).attr('re_ip_id',data.re_ip_id);
                        clone_result.find('a').eq(1).attr('re_ip_id',data.re_ip_id);
                        $this.closest('tr').before(clone_result);
                        clone_result.show();
                        $this.closest('tr').remove();
                        jGrowl_to_notyfy('IP is created successfully!',{theme:'jmsg-success'});
                    }

                }
            });
        });


        $(".ip_edit_btn").live('click',function(){
            var re_ip_id = $(this).attr('re_ip_id');
            var $this = $(this);
            var hide_tr = $this.closest('tr');
            var ip = hide_tr.children().eq(0).html();
            var port = hide_tr.children().eq(1).html();
            var closest_tbody = $this.closest('tbody');
            ip_tr_edit.children().eq(0).find('input').val(ip);
            ip_tr_edit.children().eq(1).find('input').val(port);
            ip_tr_edit.find('a').eq(0).attr('re_ip_id',re_ip_id);
            hide_tr.before(ip_tr_edit.clone(true));
            hide_tr.hide();
        });

    })
</script>
