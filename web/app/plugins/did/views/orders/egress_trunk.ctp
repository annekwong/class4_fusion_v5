<ul class="breadcrumb">
    <li><?php __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Management') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('DID Trunk') ?></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('DID Trunk') ?></h4>
    <div class="buttons pull-right">

        <a id="add" class="btn btn-primary btn-icon glyphicons circle_plus" href="###"><i></i> <?php __('Create New')?></a>

    </div>
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>
<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-head">
            <?php echo $this->element("did_trunk_tab", array('active' => 'egress')) ?>
        </div>
        <div class="widget-body">

            <div id="container">
                <?php //echo $this->element("did_client_tab", array('active' => 'trunk'))?>

                <!-- Sub Menu Tab -->
                <?php
                $data = $p->getDataArray();
                if (empty($data)):
                    ?>
                    <div class="msg center">
                        <br />
                        <h3><?php echo __('no_data_found', true); ?></h3>
                    </div>
                    <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary" style="display:none;">

                        <thead>
                            <tr>
                                <th><?php __('Name')?></th>
                                <th><?php __('IP')?></th>
                                <th><?php __('Port')?></th>
                                <th><?php __('Prefix')?>:</th>
                                <th><?php __('Action')?></th>
                            </tr>
                        </thead>

                        <tbody>

                        </tbody>
                    </table>
                <?php else: ?>
                    <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
                        <thead>
                            <tr>
                                <th><?php __('Name')?></th>
                                <th><?php __('IP')?></th>
                                <th><?php __('Port')?></th>
                                <th><?php __('Prefix')?>:</th>
                                <th><?php __('Action')?></th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php foreach ($data as $item): ?>
                                <tr>
                                    <td><?php echo $item['Resource']['alias']; ?></td>
                                    <td><?php echo $item['ResourceIp']['ip']; ?></td>
                                    <td><?php echo $item['ResourceIp']['port']; ?></td>
                                    <td><?php echo $item['ResourceDirection']['digits']; ?></td>
                                    <td>
                                        <a title="<?php __('Edit')?>" class="edit_item" href="###" control="<?php echo $item['Resource']['resource_id'] ?>" >
                                            <i class="icon-edit"></i>
                                        </a>

                                        <a title="<?php __('Delete')?>" class="delete" href='<?php echo $this->webroot; ?>did/orders/delete_trunk/<?php echo $item['Resource']['resource_id'] ?>'>
                                            <i class="icon-remove"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <div class="separator row-fluid">
                        <div class="pagination pagination-large pagination-right margin-none">
                            <?php echo $this->element('page'); ?>
                        </div> 
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

            <script>
                jQuery(function() {
                    jQuery('#add').click(function() {
                        $('.msg').hide();
                        $('table.list').show();
                        jQuery('table.list tbody').trAdd({
                            ajax: "<?php echo $this->webroot ?>did/orders/trunk_panel",
                            action: "<?php echo $this->webroot ?>did/orders/trunk_panel/0/egress",
                            removeCallback: function() {
                                if (jQuery('table.list tr').size() == 1) {
                                    jQuery('table.list').hide();
                                }
                            }
                        });
                        jQuery(this).parent().parent().show();
                    });

                    jQuery('a.edit_item').click(function() {
                        jQuery(this).parent().parent().trAdd({
                            action: '<?php echo $this->webroot ?>did/orders/trunk_panel/' + jQuery(this).attr('control')+"/egress",
                            ajax: '<?php echo $this->webroot ?>did/orders/trunk_panel/' + jQuery(this).attr('control'),
                            saveType: 'edit'
                        });
                    });
                });
            </script>
