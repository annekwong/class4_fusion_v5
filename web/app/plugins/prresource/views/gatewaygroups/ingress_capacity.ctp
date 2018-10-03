

<ul class="breadcrumb">
    <li><?php __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li>
        <?php __('Egress') ?>
        <?php
        if (isset($trunk_name))
        {
            echo "[" . $trunk_name . "]";
        }
        ?>
    </li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Ingress Capacity') ?></li>
</ul>


<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Ingress Capacity') ?>[<?php echo $trunk_name; ?>]</h4>

</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
    <a class="btn btn-primary btn-icon glyphicons circle_plus" id="add" href="###"><i></i><?php __('Create New') ?></a>
    <a class="btn btn-primary btn-icon glyphicons remove" id="del_all" onclick="deleteAll('<?php echo $this->webroot; ?>prresource/gatewaygroups/delete_all_ingress_capacities/<?php echo base64_encode($resource_id); ?>');" href="javascript:void(0)"><i></i> <?php __('Delete All') ?></a>
    <a class="btn btn-primary btn-icon glyphicons remove"  onclick="deleteSelected('staticbid', '<?php echo $this->webroot ?>prresource/gatewaygroups/delete_selected_ingress_capacities/<?php echo base64_encode($resource_id); ?>', 'Ingress Capacity');"  href="javascript:void(0)"><i></i> <?php __('Delete Seleted') ?></a>
</div>
<div class="clearfix"></div>
<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-head">
            <?php echo $this->element('egress_tab', array('active_tab' => 'ingress_capacity')); ?>
        </div>
        <div class="widget-body">
            <div class="clearfix"></div>
            <?php
            if (count($mydata) == 0)
            {
                ?>
                <h2 class="msg center"><?php echo __('no_data_found') ?></h2>
                <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary" style="display:none">
                    <thead>
                    <tr>
                        <th></th>
                        <th>Ingress Trunk</th>
                        <th>Max CPS</th>
                        <th>Max CAP</th>
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
                        <th></th>
                        <th>Ingress Trunk</th>
                        <th>Max CPS</th>
                        <th>Max CAP</th>
                        <th><?php echo __('Action'); ?></th>
                    </tr>
                    </thead>
                    <tbody id="staticbid">
                    <?php foreach ($mydata as $item) : ?>
                        <tr>
                            <td><input type="checkbox"  value="<?php echo $item[0]['id']; ?>" /></td>
                            <td><?php echo $item[0]['alias']; ?></td>
                            <td><?php echo $item[0]['max_cps']; ?></td>
                            <td><?php echo $item[0]['max_cap']; ?></td>
                            <td>
                                <a title="<?php __('edit') ?>" class="edit" href="javascript:void(0)" proid="<?php echo base64_encode($item[0]['id']); ?>">
                                    <i class="icon-edit"></i>
                                </a>
                                <a title="<?php __('del') ?>" onclick="return myconfirm('<?php __('sure to delete'); ?>', this);" class="delete" href="<?php echo $this->webroot ?>prresource/gatewaygroups/delete_ingress_capacity/<?php echo base64_encode($resource_id); ?>/<?php echo base64_encode($item[0]['id']); ?>">
                                    <i class="icon-remove"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>


                <div class="clearfix"></div>


            <?php } ?>

        </div>
    </div>
</div>
<script type="text/javascript">
    $(function() {
        jQuery('a#add').click(function() {
            jQuery(".msg").hide();
            jQuery("table.list").show();
            jQuery("#staticbid").trAdd({
                action: '<?php echo $this->webroot ?>prresource/gatewaygroups/add_ingress_capacity/<?php echo $resource_id; ?>',
                ajax: '<?php echo $this->webroot ?>prresource/gatewaygroups/add_ingress_capacity/<?php echo $resource_id; ?>',
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
            jQuery(this).parent().parent().trAdd({
                action: '<?php echo $this->webroot ?>prresource/gatewaygroups/edit_ingress_capacity/<?php echo $resource_id; ?>/' + $(this).attr('proid'),
                ajax: '<?php echo $this->webroot ?>prresource/gatewaygroups/edit_ingress_capacity/<?php echo $resource_id; ?>/' + $(this).attr('proid'),
                saveType: 'edit',
                callback: function() {

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

        jQuery("a.copy").click(function() {
            var $this = $(this);
            if (!$('#dd').length) {
                $(document.body).append("<div id='dd'></div>");
            }
            var $dd = $('#dd');
            var $form = null;
            $dd.load('<?php echo $this->webroot; ?>prresource/gatewaygroups/ajax_get_trunk_static_info/<?php echo $resource_id; ?>/' + $this.attr('proid'),
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
