<ul class="breadcrumb">
    <li><?php __('You are here'); ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Routing') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Carrier')?> [<?php echo $client_name ?>]</li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Edit Egress')?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('SIP Profile')?> [<?php echo $res['Gatewaygroup']['alias']; ?>]</li>
</ul>



<div class="heading-buttons">
    <h4 class="heading">
        <?php __('SIP Profile')?> [<?php echo $res['Gatewaygroup']['alias']; ?>]
    </h4>
</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
    <a class="btn btn-primary btn-icon glyphicons circle_plus" id="add" href="###"><i></i><?php __('Create New') ?></a>
</div>
<div class="clearfix"></div>

<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-head">
            <?php echo $this->element('egress_tab', array('active_tab' => 'sip_profile')); ?>

        </div>
        <div class="widget-body">
            <div class="clearfix"></div>
            <?php
            if (count($switch_profiles) == 0)
            {
                ?>
                <h2 class="msg center"><?php echo __('no_data_found') ?></h2>
                <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary" style="display:none">
                    <thead>
                    <tr>
<!--                        <th></th>-->
                        <th>Switch Name</th>
                        <th>Ingress Trunk</th>
                        <th>Binding IP</th>
                        <th><?php echo __('Action'); ?></th>
                    </tr>
                    </thead>
                    <tbody  id="staticbid">
                    </tbody>
                </table>
                <?php
            }
            else
            {
            ?>
            <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
                <thead>
                <tr>
<!--                    <th></th>-->
                     <th>Switch Name</th>
                     <th>Ingress Trunk</th>
                     <th>Binding IP</th>
                     <th><?php echo __('Action'); ?></th>
                </tr>
                </thead>
                <tbody id="staticbid">
                    <?php
                    foreach ($switch_profiles as $flg_key=>$switch_profile) {
                        foreach ($switch_profile['egress_profiles'] as $egress_profile) {
                            ?>
                            <tr>
                                <td class="server_name"><?php echo $switch_profile['name']; ?></td>
                                <td class="ingress_trunk">
                                    <?php echo isset($egress_profile['Gatewaygroup']['alias']) ? $egress_profile['Gatewaygroup']['alias'] : ''; ?>
                                </td>
                                <td>
                                     <?php echo isset($egress_profile["SwitchProfile"]["sip_ip"]) ? $egress_profile["SwitchProfile"]["sip_ip"] : ''; ?>
                                </td>
                                <td id="actions">
                                    <a title="<?php __('edit') ?>" class="edit" href="javascript:void(0)" proid="<?php echo base64_encode($egress_profile['EgressProfile']['id']); ?>">
                                        <i class="icon-edit"></i>
                                    </a>
                                    <a title="<?php __('del') ?>" onclick="return myconfirm('<?php __('sure to delete'); ?>', this);" class="delete" href="<?php echo $this->webroot ?>prresource/gatewaygroups/delete_sip_profile/<?php echo $resource_id; ?>/<?php echo base64_encode($egress_profile['EgressProfile']['id']); ?>">
                                        <i class="icon-remove"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php }
                    }?>
                    </tbody>
                </table>
            <?php } ?>
            <div class="clearfix"></div>
        </div>
    </div>
</div>


<script type="text/javascript">
    $(function() {
        jQuery('a#add').click(function() {
            jQuery(".msg").hide();
            jQuery("table.list").show();
            jQuery("#staticbid").trAdd({
                action: '<?php echo $this->webroot ?>prresource/gatewaygroups/sip_add/<?php echo $resource_id; ?>',
                ajax: '<?php echo $this->webroot ?>prresource/gatewaygroups/sip_add/<?php echo $resource_id; ?>',
                saveType: 'add',
                insertNumber: 'first',
                removeCallback: function() {
                    if (jQuery('table.list tr').size() == 1) {
                        jQuery('table.list').hide();
                        $('.msg').show();
                    }
                },
                onsubmit: function(options)
                {
                    var prefix = $("#prefix").val();
                    if (prefix)
                    {
                        if (/[^a-zA-Z0-9,]/.test(prefix)) {
                            jGrowl_to_notyfy('<?php __('prefix format'); ?>', {theme: 'jmsg-error'});
                            return false;
                        }
                    }
                    return true;

                }
            });
        });


        jQuery('a.edit').click(function() {
            var ingress_trunk = $(this).closest('tr').find('.ingress_trunk').text().trim(), self = this;
            jQuery(this).parent().parent().trAdd({
                action: '<?php echo $this->webroot ?>prresource/gatewaygroups/edit_sip/<?php echo $resource_id; ?>/' + $(this).attr('proid'),
                ajax: '<?php echo $this->webroot ?>prresource/gatewaygroups/edit_sip/<?php echo $resource_id; ?>/' + $(this).attr('proid'),
                saveType: 'edit',
                callback: function() {
                    if(ingress_trunk == 'all'){
                        $('select[name="ingress_id"] option:eq(0)').attr('selected', 'selected');
                    }
                },
                onsubmit: function(options) {
                    return true;
                }
            });
        });

        jQuery("a.copy").click(function() {
            var $this = $(this);
            if (!$('#dd').length) {
                $(document.body).append("<div id='dd'></div>");
            }
            var $dd = $('#dd');
            var $form = null;
            $dd.load('<?php echo $this->webroot; ?>prresource/gatewaygroups/ajax_get_trunk_static_info/' + $this.attr('proid'),
                {},
                function(responseText, textStatus, XMLHttpRequest) {
                    $dd.dialog({
                        'width': '450px',
                        'height': 200,
                        'buttons': [{text: "Submit", "class": "btn btn-primary", click: function() {
                            $("#add_form").submit();
                        }}, {text: "Cancel", "class": "btn btn-inverse", click: function() {
                            $(this).dialog("close");
                        }}]
                    });
                }
            );
        });

    });

</script>