<ul class="breadcrumb">
    <li><?php __('You are here'); ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?= $this->webroot?>black_hole/ip_list"><?php __('Monitoring') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?= $this->webroot?>black_hole/ip_list"><?php echo $this->pageTitle; ?></a></li>
</ul>


<div class="heading-buttons">
    <h4 class="heading"><?php echo $this->pageTitle; ?></h4>

</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
    <a class="btn btn-primary btn-icon glyphicons circle_plus" data-toggle="modal" href="<?php echo $this->webroot; ?>black_hole/upload"><i></i><?php __('Upload')?></a>
    <a id="add" class="btn btn-primary btn-icon glyphicons circle_plus" href="javascript:void(0)"><i></i> <?php __('Create New')?></a>
    <a class="btn btn-primary btn-icon glyphicons circle_plus" data-toggle="modal" id="add_by_netmask" href="#myModalCreateNetMask"><i></i><?php __('Create New By NetMask')?></a>
   <?php if (count($this->data)): ?>
        <a class="btn btn-primary btn-icon glyphicons circle_plus" data-toggle="modal" id="delete_by_netmask" href="#myModalCreateNetMask"><i></i><?php __('Delete By NetMask')?></a>
        <a class="btn btn-primary btn-icon glyphicons remove" rel="popup" href="javascript:void(0)" onclick="deleteSelected('block_ip', '<?php echo $this->webroot ?>black_hole/delete_selected', 'Black Hole IPs');"><i></i> <?php echo __('Delete Selected') ?></a>
    <?php endif;?>
</div>
<div class="clearfix"></div>

<div class="innerLR">
    <div class="widget widget-tabs widget-body-white">
        <div class="widget-body">
            <div class="filter-bar">
                <form action="" method="get">
                    <div>
                        <label><?php __('Search') ?>:</label>
                        <input type="text" name="search_char" value="<?php echo $appCommon->_get('search_char'); ?>" />
                    </div>
                    <div>
                        <button name="submit" class="btn query_btn"><?php __('Query') ?></button>
                    </div>
                </form>
            </div>
            <div class="clearfix"></div>
            <?php if (!count($this->data)): ?>
                <table style="display:none" class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
                    <thead>
                    <tr>
                        <th class="footable-first-column expand" data-class="expand">
                        <?php if ($_SESSION['login_type'] == '1'){ ?>
                            <input id="selectAll" class="select" type="checkbox" onclick="checkAllOrNot(this, 'block_ip');" value=""/>
                        <?php } ?></th>
                        <th><?php echo $appCommon->show_order('ip', __('IP', true)) ?></th>
                        <th><?php __('Owner') ?></th>
                        <th><?php __('Auto Block') ?></th>
                        <th><?php echo $appCommon->show_order('create_time', __('Created On', true)) ?></th>
                         <th><?php __('Created By') ?></th>
                        <th><?php __('Action'); ?></th>
                    </tr>
                    </thead>
                    <tbody  id="block_ip">
                    </tbody>
                </table>
                <div class="msg center"><br /><h2><?php echo __('no_data_found', true); ?></h2></div>
            <?php else: ?>
                <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
                    <thead>
                    <tr>
                        <th class="footable-first-column expand" data-class="expand">
                        <?php if ($_SESSION['login_type'] == '1'){ ?>
                            <input id="selectAll" class="select" type="checkbox" onclick="checkAllOrNot(this, 'block_ip');" value=""/>
                        <?php } ?></th>
                        <th><?php echo $appCommon->show_order('ip', __('IP', true)) ?></th>
                        <th><?php __('Owner') ?></th>
                        <th><?php __('Auto Block') ?></th>
                        <th><?php echo $appCommon->show_order('create_time', __('Created On', true)) ?></th>
                         <th><?php __('Created By') ?></th>
                        <th><?php __('Action'); ?></th>
                    </tr>
                    </thead>

                    <tbody  id="block_ip">
                    <?php foreach ($this->data as $item): ?>
                        <tr>
                            <td class="footable-first-column expand" data-class="expand">
                            <?php if ($_SESSION['login_type'] == '1') { ?>
                                <input class="select" type="checkbox" value="<?php echo $item['SpamTrafficIp']['ip'] ?>"/>
                            <?php } ?></td>
                            <td><?php echo $item['SpamTrafficIp']['ip']; ?></td>
                            <td><?php echo $item['SpamTrafficIp']['brief']; ?></td>
                            <td>
                            <?php if(isset($item['SpamTrafficIp']['auto_block']) && $item['SpamTrafficIp']['auto_block']): ?>
                                <?php __('True'); ?>
                            <?php else:?>
                                <?php __('False'); ?>
                            <?php endif;?>
                            </td>
                            <td><?php echo $item['SpamTrafficIp']['create_time']; ?></td>
                            <td><?php echo isset($item['SpamTrafficIp']['created_by'])?$item['SpamTrafficIp']['created_by']:'admin'; ?></td>
                            <td>
                                <a title="Delete" onclick="return myconfirm('<?php __('sure to delete') ?>', this)"
                                   class="delete" href='<?php echo $this->webroot ?>black_hole/delete_ip/<?php echo base64_encode($item['SpamTrafficIp']['ip']) ?>'>
                                    <i class="icon-remove"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
                <div class="row-fluid separator">
                    <div class="pagination pagination-large pagination-right margin-none">
                        <?php echo $this->element('xpage'); ?>
                    </div>
                </div>
                <div class="clearfix"></div>
            <?php endif; ?>
        </div>
    </div>
</div>

<div id="myModalCreateNetMask" class="modal hide">
    <div class="modal-header">
        <button data-dismiss="modal" class="close" type="button">&times;</button>
        <h3><?php __('Create New By NetMask'); ?></h3>
    </div>
    <div class="separator"></div>
    <form method="post" action="<?php echo $this->webroot ?>black_hole/save_ip_by_netmask">
        <div class="widget-body">
            <table class="table table-bordered">
                <tr>
                    <td class="align_right"><?php echo __('IP')?> </td>
                    <td>
                        <input class="input in-text validate[required,custom[ipv4]]" name="netMask_ip" type="text" >/
                        <input class="input in-text width25 validate[required,custom[integer],min[0],max[31]]" name="netMask" maxlength="2" type="text" />
                    </td>
                </tr>
                <!--                <tr>-->
                <!--                    <td class="align_right">--><?php //echo __('IP List')?><!-- </td>-->
                <!--                    <td class="ip_list">-->
                <!--                    </td>-->
                <!--                </tr>-->
                <tr class="content">
                    <td class="align_right"><?php echo __('Hostname')?> </td>
                    <td><textarea class="input in-textarea" style="width: 100%;" class="detail" name="detail"></textarea></td>
                </tr>
            </table>
        </div>
        <div class="modal-footer">
            <input type="submit" class="btn btn-primary sub" value="<?php __('Submit'); ?>">
            <a href="javascript:void(0)"  data-dismiss="modal" class="btn btn-default close-btn"><?php __('Close'); ?></a>
        </div>
    </form>
</div>
<script type="text/javascript">
    $(function() {
        if(jQuery('.select:checked').length){
            $('a.remove').show();
        }else{
            $('a.remove').hide();
        }
        jQuery('.select').on('click', function(){
            if(jQuery('.select:checked').length){
                $('a.remove').show();
            }else{
                $('a.remove').hide();
            }
        })
        jQuery('#selectAll').selectAll('.select');
        jQuery('#add').on('click', function(){
                jQuery('table.list').show();
                jQuery('table.list tbody ').trAdd({
                    ajax:"<?php echo $this->webroot?>black_hole/js_save",
                    action:"<?php echo $this->webroot?>black_hole/save_ip",
                    'insertNumber' : 'first',
                    removeCallback: function () {
                        if (jQuery('table.list tr').size() == 1) {
                            jQuery('table.list').hide();
                            $('.msg').show();
                        }
                    },
                    onsubmit: function (options) {
                        let ip = $('#CodedeckIp').val();
                        $.ajax({
                            url: '<?php echo $this->webroot; ?>black_hole/ajax_check_ip',
                            type: 'POST',
                            data:{'ip':ip},
                            dataType: 'json',
                            success: function(response) {
                                if(!response.status){
                                    jGrowl_to_notyfy(response.msg, {theme: 'jmsg-error'});
                                    return false;
                                }

                            }
                        });
                        return true;
                    }

                });

        });



        $("#add_by_netmask").click(function(){
            $("#myModalCreateNetMask").find('form').attr('action','<?php echo $this->webroot ?>black_hole/save_ip_by_netmask');
            $("#myModalCreateNetMask").find('h3').html('<?php __('Create New By NetMask'); ?>');
            $("#myModalCreateNetMask").find('.content').show();
        });

        $("#delete_by_netmask").click(function(){
            $("#myModalCreateNetMask").find('form').attr('action','<?php echo $this->webroot ?>black_hole/delete_ip_by_netmask');
            $("#myModalCreateNetMask").find('h3').html('<?php __('Delete By NetMask'); ?>');
            $("#myModalCreateNetMask").find('.content').hide();
        });


        $("#myModalCreateNetMask").find('.sub').click(function(){
            if($("#myModalCreateNetMask").find("input[name='netMask_ip']").validationEngine('validate')){
                return;
            }
            if($("#myModalCreateNetMask").find("input[name='netMask']").validationEngine('validate')){
                return;
            }
//            var ip = $("#myModalCreateNetMask").find("input[name='netMask_ip']").val();
//            var netMask = $("#myModalCreateNetMask").find("input[name='netMask']").val();
//            $.ajax({
//                url: '<?php //echo $this->webroot; ?>//black_hole/get_ip_by_netmask',
//                'type': 'POST',
//                dataType:'json',
//                data:{'ip':ip,'netMask':netMask},
//                'success': function(data) {
//                    $("#myModalCreateNetMask").find('.ip_list').html(data.msg);
//                },
//            });
        });



        setTimeout(function(){
          $('.ColVis_collection .ColVis_radio input').each(function(index, val){
                     if(!$(this).is(':checked')){
                         $(this).click();
                     }
                })
        }, 1000)
    });
</script>
