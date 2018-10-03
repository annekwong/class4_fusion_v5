<script src="<?php echo $this->webroot ?>js/ajaxTable.js" type="text/javascript"></script>


<ul class="breadcrumb">
    <li><?php echo __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Configuration') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('LRN Group Setting') ?></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('LRN Group Setting', true); ?>&nbsp;[<?php echo ( $lrn_group['LrnSetting']['name']) ?>]</h4>
    
</div>
<div class="separator bottom"></div>
<?php if ($_SESSION['role_menu']['Configuration']['lrn_settings']['model_w'])
    {
        ?>
        <div class="buttons pull-right newpadding">
            <a id="add" class="btn btn-primary btn-icon glyphicons circle_plus" href="javascript:void(0);" ><i></i>
                <?php __('Create New')?>
            </a>
        </div>
<?php } ?>
    <div class="buttons pull-right">
        <a class="btn btn-icon btn-inverse glyphicons circle_arrow_left" href="<?php echo $this->webroot ?>lrn_settings">
            <i></i>
            <?php __('Back')?>
        </a>
    </div>
    <div class="clearfix"></div>
<div class="innerLR">
    <div class="widget widget-tabs widget-body-white">
        <!--<div class="widget-head">
            <ul>
                <li class="active"><a href="<?php echo $this->webroot; ?>lrn_settings/items/<?php echo base64_encode($lrn_group['LrnSetting']['id']); ?>" class="glyphicons left_arrow"><i></i><?php __('List'); ?></a></li>
                <li><a href="<?php echo $this->webroot; ?>lrn_settings/upload_items/<?php echo base64_encode($lrn_group['LrnSetting']['id']); ?>" class="glyphicons right_arrow"><i></i><?php __('Special Code'); ?></a></li>
            </ul>
        </div>-->
        <div class="widget-body">

            <div class="clearfix"></div>
            <div id="container">

                <?php
                if (empty($this->data)):
                    ?>
                    <div class="msg center">
                        <br /> 
                        <h2><?php echo __('no_data_found', true); ?></h2>
                    </div>
                    <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary" style="display:none;" id="mylist" >

                        <thead>
                            <tr>
                                <th><?php __('IP')?></th>
                                <th><?php __('Port')?></th>
                                <th><?php __('Timeout')?></th>
                                <th><?php __('Retry')?></th>
                                <th><?php __('Option')?></th>
                                <th><?php __('Option interval')?></th>
                                <th><?php __('Dynamic Timeout')?></th>
                                <th><?php __('Filter Timeout')?></th>
                                <th><?php __('Connection Availability')?></th>
                                <th><?php __('Action')?></th>
                            </tr>
                        </thead>

                        <tbody>

                        </tbody>
                    </table>
<?php else: ?>
                    <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary"  id="mylist">
                        <thead>
                            <tr>
                                <th><?php __('IP')?></th>
                                <th><?php __('Port')?></th>
                                <th><?php __('Timeout')?></th>
                                <th><?php __('Retry')?></th>
                                <th><?php __('Option')?></th>
                                <th><?php __('Option interval')?></th>
                                <th><?php __('Dynamic Timeout')?></th>
                                <th><?php __('Filter Timeout')?></th>
                                <th><?php __('Connection Availability')?></th>
                                <th><?php __('Action')?></th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                            foreach ($this->data as $item):
                                ?>
                                <tr>
                                    <td><?php echo $item['LrnItem']['ip']; ?></td>
                                    <td><?php echo $item['LrnItem']['port']; ?></td>
                                    <td><?php echo $item['LrnItem']['timeout']; ?></td>
                                    <td><?php echo $item['LrnItem']['retry']; ?></td>
                                    <td><?php echo (int) $item['LrnItem']['option'] == 1 ? 'Yes' : 'No'; ?></td>
                                    <td><?php echo $item['LrnItem']['option_interval']; ?></td>
                                    <td><?php echo (int) $item['LrnItem']['option'] == 1 ? (int) $item['LrnItem']['dynamic_timeout'] == 1 ? 'Yes' : 'No'  : '-'; ?></td>
                                    <td><?php echo (int) $item['LrnItem']['option'] == 1 ? (int) $item['LrnItem']['filter_timeout'] == 1 ? 'Yes' : 'No'  : '-'; ?></td>
                                    <td><?php echo (int) $item['LrnItem']['is_connection'] == 1 ? 'Yes' : 'No'; ?></td>
                                    <td>
                                        <a title="<?php __("Log"); ?>" href="<?php echo $this->webroot; ?>lrn_settings/item_logs/<?php echo base64_encode($item['LrnItem']['id']) ?>">
                                            <i class="icon-list-alt"></i>
                                        </a>
                                        <?php if ($_SESSION['role_menu']['Configuration']['lrn_settings']['model_w'])
                                        {
                                            ?>
                                            <?php
                                            if ($item['LrnItem']['active'] == '1'):
                                                ?>
                                                <a title="<?php __("Inactive"); ?>" href="<?php echo $this->webroot; ?>lrn_settings/change_item_status/<?php echo base64_encode($item['LrnItem']['id']) ?>/0">
                                                    <i class="icon-check"></i> 
                                                </a>
            <?php else: ?>
                                                <a title="<?php __("Active"); ?>" href="<?php echo $this->webroot; ?>lrn_settings/change_item_status/<?php echo base64_encode($item['LrnItem']['id']) ?>/1">
                                                    <i class="icon-unchecked"></i> 
                                                </a>
            <?php endif; ?>
                                            <a title="Edit" class="edit_item" href="###" control="<?php echo $item['LrnItem']['id'] ?>" >
                                                <i class="icon-edit"></i>
                                            </a>
                                            <a title="Delete" onclick="return myconfirm('Are you sure?', this);" class="delete" href='<?php echo $this->webroot; ?>lrn_settings/delete_item/<?php echo base64_encode($item['LrnItem']['id']) ?>'>
                                                <i class='icon-remove'></i>
                                            </a>
                                <?php } ?>
                                    </td>
                                </tr>
    <?php endforeach; ?>
                        </tbody>
                    </table>
                    <div class="bottom row-fluid">
                        <div class="pagination pagination-large pagination-right margin-none">
                    <?php echo $this->element('xpage'); ?>
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
            $('#mylist').show();
            jQuery('#mylist tbody').trAdd({
                                ajax: "<?php echo $this->webroot ?>lrn_settings/edit_item_panel/<?php echo $lrn_group['LrnSetting']['id'] ?>",
                                action: "<?php echo $this->webroot ?>lrn_settings/edit_item_panel/<?php echo $lrn_group['LrnSetting']['id'] ?>",
                                                removeCallback: function() {
                                                    if (jQuery('#mylist tr').size() == 0) {
                                                        jQuery('#mylist').hide();
                                                    } 
                                                },
                                                
                                                callback: function() {
                                                    $('.option_master').trigger("change");
                                                }
                                            });
                                            
                                            jQuery(this).parent().parent().show();
                                        });

                                        jQuery('a.edit_item').click(function() {
                                            jQuery(this).parent().parent().trAdd({
                                                action: '<?php echo $this->webroot ?>lrn_settings/edit_item_panel/<?php echo $lrn_group['LrnSetting']['id'] ?>/' + jQuery(this).attr('control'),
                                                ajax: '<?php echo $this->webroot ?>lrn_settings/edit_item_panel/<?php echo $lrn_group['LrnSetting']['id'] ?>/' + jQuery(this).attr('control'),
                                                saveType: 'edit',
                                                callback: function() {
                                                    $('.option_master').trigger("change");
                                                }
                                            });
                                        });


                                        $('.option_master').live('change', function() {
                                            var $this = $(this);
                                            if ($this.is(':checked')) {
                                                $('.option_items').attr('disabled', false);
                                            } else {
                                                $('.option_items').attr('disabled', true);
                                            }
                                        });


                                        $('.option_items').live('change', function() {
                                            var $this = $(this);
                                            var name = $(this).attr('name');
                                            if (name == 'data[LrnItem][dynamic_timeout]')
                                            {
                                                $this.parent().next().find('.option_items').attr('checked', false);
                                            }
                                            else
                                            {
                                                $this.parent().prev().find('.option_items').attr('checked', false);
                                            }
                                        });
                                    });
</script>