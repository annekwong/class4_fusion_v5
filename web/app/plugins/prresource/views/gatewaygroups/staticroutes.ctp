

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
    <li><?php echo __('Static Routing') ?></li>
</ul>


<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Static Routing') ?>[<?php echo $trunk_name; ?>]</h4>

</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
    <a class="btn btn-primary btn-icon glyphicons circle_plus" id="add" href="###"><i></i><?php __('Create New') ?></a>
    <a class="btn btn-primary btn-icon glyphicons remove" id="del_all" onclick="deleteAll('<?php echo $this->webroot; ?>prresource/gatewaygroups/delete_all_trunk_static/<?php echo base64_encode($resource_id); ?>');" href="javascript:void(0)"><i></i> <?php __('Delete All') ?></a>
    <a class="btn btn-primary btn-icon glyphicons remove"  onclick="deleteSelected('staticbid', '<?php echo $this->webroot ?>prresource/gatewaygroups/delete_selected_trunk_static/<?php echo base64_encode($resource_id); ?>', 'Static Route');"  href="javascript:void(0)"><i></i> <?php __('Delete Seleted') ?></a>
</div>
<div class="clearfix"></div>
<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-head">
            <?php echo $this->element('egress_tab', array('active_tab' => 'staticroutes')); ?>
        </div>
        <div class="widget-body">
            <div class="filter-bar">
                <form method="get">
                    <!-- Filter -->
                    <div>
                        <label><?php __('Static Route Name') ?>:</label>
                        <input type="text" id="search-_q" class="in-search input in-text" title="<?php echo __('search') ?>" 
                               value="<?php
                               if (isset($_GET['static_route_name']))
                               {
                                   echo $_GET['static_route_name'];
                               }
                               else
                               {
                                   echo '';
                               }
                               ?>"  name="static_route_name">
                    </div>
                    <!-- // Filter END -->
                    <!-- Filter -->
                    <div>
                        <label><?php __('Prefix') ?>:</label>
                        <input type="text" name="prefix" value="<?php
                        if (isset($_GET['prefix']))
                        {
                            echo $_GET['prefix'];
                        }
                        ?>" />
                    </div>

                    <div>
                        <button name="submit" class="btn query_btn"><?php __('Query') ?></button>
                    </div>
                    <!-- // Filter END -->
                </form>
            </div>

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
                            <th><?php echo $appCommon->show_order('product.name', __('Static Route Name', true)) ?></th>
                            <th><?php echo __('Prefix'); ?></th>
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
                            <th><?php echo $appCommon->show_order('product.name', __('Static Route Name', true)) ?></th>
                            <th><?php __('Prefix'); ?></th>
                            <th><?php __('Action'); ?></th>
                        </tr>
                    </thead>
                    <tbody id="staticbid">
                        <?php foreach ($mydata as $item) : ?>
                            <tr>
                                <td><input type="checkbox"  value="<?php echo $item['product_id']; ?>" /></td>
                                <td><?php echo $item['name']; ?></td>
                                <td><?php echo $item['digits']; ?></td>
                                <td>
                                    <a title="<?php __('copy') ?>" href="javascript:void(0)" class="copy" proid="<?php echo $item['product_id']; ?>">
                                        <i class="icon-copy"></i>
                                    </a>
                                    <a title="<?php __('edit') ?>" class="edit" href="javascript:void(0)" proid="<?php echo $item['product_id']; ?>">
                                        <i class="icon-edit"></i>
                                    </a>
                                    <a title="<?php __('del') ?>" onclick="return myconfirm('<?php __('sure to delete'); ?>', this);" class="delete" href="<?php echo $this->webroot ?>prresource/gatewaygroups/delete_trunk_static/<?php echo base64_encode($resource_id); ?>/<?php echo base64_encode($item['product_id']); ?>">
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
                action: '<?php echo $this->webroot ?>prresource/gatewaygroups/add_trunk_static/<?php echo $resource_id; ?>',
                                ajax: '<?php echo $this->webroot ?>prresource/gatewaygroups/add_trunk_static/<?php echo $resource_id; ?>',
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
                                                action: '<?php echo $this->webroot ?>prresource/gatewaygroups/edit_trunk_static/<?php echo $resource_id; ?>/' + $(this).attr('proid'),
                                                ajax: '<?php echo $this->webroot ?>prresource/gatewaygroups/edit_trunk_static/<?php echo $resource_id; ?>/' + $(this).attr('proid'),
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

