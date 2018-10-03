<ul class="breadcrumb">
    <li><?php __('You are here'); ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Routing') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Carrier')?> [<?php echo $client_name ?>]</li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Edit Egress')?>[<?php echo $resource_name; ?>]</li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li>
        <a href="<?php echo $this->webroot.$back_url; ?>" class="text-primary">
            <?php __('SIP Profile')?>
        </a>
    </li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('VoIP Gateway Name')?> [<?php echo $server_name; ?>]</li>
</ul>
<div class="heading-buttons">
    <h4 class="heading"><?php __('SIP Profile')?></h4>
</div>
<div class="clearfix"></div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
    <a class="btn btn-primary btn-icon glyphicons circle_plus" href="#addEgressProfile" data-toggle="modal">
        <i></i>
        <?php __('Create New'); ?>
    </a>
    <?php if (count($this->data) > 0) : ?>
        <a  class="btn btn-primary btn-icon glyphicons remove" relm="popup" href="javascript:void(0)" onclick="deleteAll('<?php echo $this->webroot ?>prresource/gatewaygroups/delete_egress_profile_all?<?php echo $this->params['getUrl']; ?>');"><i></i> <?php echo __('Delete All') ?></a>
        <a class="btn btn-primary btn-icon glyphicons remove" rel="popup" href="javascript:void(0)"
           onclick="deleteSelected('sip_profile_tbody', '<?php echo $this->webroot ?>prresource/gatewaygroups/delete_egress_profile_seleted?<?php echo $this->params['getUrl']; ?>', 'egress profile');">
            <i></i> <?php echo __('Delete Selected') ?></a>
    <?php endif; ?>
    <a class="link_back btn btn-icon btn-inverse glyphicons circle_arrow_left" href='<?php echo $this->webroot.$back_url; ?>'>
        <i></i>
        <?php __('Back') ?>
    </a>
</div>
<div class="clearfix"></div>
<div class="innerLR">
    <div class="widget widget-tabs widget-body-white">
        <div class="widget-head">
            <?php echo $this->element('egress_tab', array('active_tab' => 'sip_profile')); ?>

        </div>
        <div class="widget-body">
<!--        <div class="filter-bar">-->
<!--            <form action="" method="get">-->
<!--                <div>-->
<!--                    <label>--><?php //__('Template Name') ?><!--:</label>-->
<!--                </div>-->
<!--                <div>-->
<!--                    <button name="submit" class="btn query_btn">--><?php //__('Query') ?><!--</button>-->
<!--                </div>-->
<!--            </form>-->
<!--        </div>-->

            <div class="clearfix"></div>
            <?php if (!count($this->data)): ?>
                <div class="msg center"><br /><h2><?php echo __('no_data_found', true); ?></h2></div>
            <?php else: ?>
            <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
                <thead>
                <tr>
                    <th>
                        <input id="selectAll" class="select" type="checkbox" onclick="checkAllOrNot(this, 'sip_profile_tbody');">
                    </th>
                    <th><?php echo $appCommon->show_order('Resource.alias', __('Ingress Name', true)) ?></th>
                    <th><?php echo $appCommon->show_order('SwitchProfile.profile_name', __('SIP Profile Name', true)) ?></th>
                    <th><?php __('IP'); ?></th>
                    <th><?php __('Action'); ?></th>
                </tr>
                </thead>

                <tbody id="sip_profile_tbody">
                <?php foreach ($this->data as $item): ?>
                    <tr>
                        <td><input class="select" type="checkbox" value="<?php echo $item['EgressProfile']['id']; ?>" /></td>
                        <td><?php echo $item['Resource']['alias']; ?></td>
                        <td><?php echo $item['SwitchProfile']['profile_name']; ?></td>
                        <td><?php echo $item['SwitchProfile']['sip_ip']; ?></td>
                        <td>
                            <a title="Delete" class="delete" href='javascript:void(0)'>
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
<form action="<?php echo $this->webroot; ?>prresource/gatewaygroups/add_egress_profile" method="post">
    <input type="hidden" name="egress_id" value="<?php echo base64_decode($this->params['pass'][0]) ?>" />
    <input type="hidden" name="server_name" value="<?php echo $server_name;  ?>" />
<div id="addEgressProfile" class="modal hide">
    <div class="modal-header">
        <button data-dismiss="modal" class="close" type="button">&times;</button>
        <h3><?php __('Add Egress Profile'); ?></h3>
    </div>
    <div class="modal-body">
    </div>
    <div class="modal-footer">
        <input type="submit" class="btn btn-primary" value="<?php __('Submit'); ?>">
        <a href="javascript:void(0)" data-dismiss="modal" class="btn btn-default"><?php __('Close'); ?></a>
    </div>
</div>
</form>
<script type="text/javascript">
    $(function () {
        $("#addEgressProfile").on('shown',function(){
            var template_id = $(this).attr('template_id');
            $("#addEgressProfile").find('.modal-body').load("<?php echo $this->webroot; ?>prresource/gatewaygroups/add_egress_profile",
                {'egress_id':'<?php echo base64_decode($this->params['pass'][0]) ?>','server_name':'<?php echo $server_name;  ?>'});
        });

        $("#sip_profile_tbody").find('.delete').click(function(){
            var $this = $(this);
            var $profile_id = $this.closest('tr').find('.select').val();
//            console.log(profile_id);
            bootbox.confirm('<?php __('sure to delete'); ?>',function(result){
                if(result){
                    $.ajax({
                        type: "POST",
                        url: "<?php echo $this->webroot; ?>prresource/gatewaygroups/delete_egress_profile",
                        data: "profile_id="+$profile_id,
                        success: function(msg){
                            if (msg == 1){
                                jGrowl_to_notyfy('<?php __('Delete successfully'); ?>',{'theme':'jmsg-success'});
                                $this.closest('tr').remove();
                            }else{
                                jGrowl_to_notyfy('<?php __('Delete failed'); ?>',{'theme':'jmsg-error'});
                            }
                        }
                    });
                }
            });
        });

    });
</script>