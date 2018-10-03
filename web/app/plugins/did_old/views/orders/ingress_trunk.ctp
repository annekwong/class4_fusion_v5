<ul class="breadcrumb">
    <li><?php __('You are here')?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Management') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('DID Trunk') ?></li>
</ul>

<div class="heading-buttons">
    <h1><?php echo __('DID Trunk') ?></h1>
    <div class="buttons pull-right">

        <a id="add" class="btn btn-primary btn-icon glyphicons circle_plus" href="###"><i></i> <?php __('Create New')?></a>

    </div>
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>
<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-head">
                <?php echo $this->element("did_trunk_tab", array('active' => 'ingress')) ?>
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
                                <th><?php __('Mask')?></th>
                                <th><?php __('Prefix')?>:</th>
                                <th><?php __('Action')?></th>
                            </tr>
                        </thead>

                        <tbody>

                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="separator bottom row-fluid">
                        <div class="pagination pagination-large pagination-right margin-none">
                            <?php echo $this->element('page'); ?>
                        </div> 
                    </div>
                    <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
                        <thead>
                            <tr>
                                <th><?php __('Name')?></th>
                                <th><?php __('IP')?></th>
                                <th><?php __('Mask')?></th>
                                <th><?php __('Prefix')?>:</th>
                                <th><?php __('Action')?></th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php foreach ($data as $item): ?>
                                <tr>
                                    <td><?php echo $item['Resource']['alias']; ?></td>
                                    <?php
                                    $trunk_ip_info = explode('/', $item['ResourceIp']['ip']);
                                    ?>
                                    <td><?php echo $trunk_ip_info[0]; ?></td>
                                    <td><?php echo isset($trunk_ip_info[1]) ? $trunk_ip_info[1] : ""; ?></td>
                                    <td><?php echo $item['ResourceDirection']['digits']; ?></td>
                                    <td>
                                        <a title="Edit" class="edit_item" href="###" control="<?php echo $item['Resource']['resource_id'] ?>" >
                                            <i class="icon-edit"></i>
                                        </a>

                                        <a title="Delete" class="delete" href='<?php echo $this->webroot; ?>did/orders/delete_trunk/<?php echo $item['Resource']['resource_id'] ?>'>
                                            <i class="icon-remove"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <div class="separator bottom row-fluid">
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
                action: "<?php echo $this->webroot ?>did/orders/trunk_panel/0/ingress",
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
                action: '<?php echo $this->webroot ?>did/orders/trunk_panel/' + jQuery(this).attr('control')+"/ingress",
                ajax: '<?php echo $this->webroot ?>did/orders/trunk_panel/' + jQuery(this).attr('control'),
                saveType: 'edit'
            });
        });
    });
</script>