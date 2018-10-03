<style type="text/css">

    #trAdd input{
        width: 50px;
    }
</style>
<script src="<?php echo $this->webroot ?>js/ajaxTable.js" type="text/javascript"></script>


<ul class="breadcrumb">
    <li><?php __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Configuration') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('LRN Group Setting') ?></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php __('LRN Group Setting') ?></h4>

</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
    <a id="add" class="link_btn btn btn-primary btn-icon glyphicons circle_plus" href="javascript:void(0);"><i></i>
        <?php __('Create New') ?>
    </a>
</div>
<div class="clearfix"></div>



<div class="innerLR">

    <div class="widget widget-heading-simple widget-body-white">
        <div class="widget-body">

            <div class="clearfix"></div>

            <?php
            if (empty($this->data)):
                ?>
                <div class="msg center"><br /><h2><?php echo __('no_data_found', true); ?></h2></div>
                <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary" id="mylist" style="display:none;">

                    <thead>
                        <tr>
                            <th></th>
                            <th><?php __('Name'); ?></th>
                            <th><?php __('Strategy'); ?></th>
                            <th><?php __('Action'); ?></th>
                        </tr>
                    </thead>

                    <tbody>

                    </tbody>
                </table>
            <?php else: ?>
                <div id="container">
                    <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary" id="mylist">
                        <thead>
                            <tr>
                                <th></th>
                                <th><?php echo $appCommon->show_order('name', __('Name', true)) ?></th>
                                <th><?php __('Strategy'); ?></th>
                                <th><?php __('Action'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $count = count($this->data);
                            for ($i = 0; $i < $count; $i++):
                                ?>
                                <tr class="row-<?php echo $i % 2 + 1; ?>">
                                    <td>
                                        <img id="image<?php echo $i; ?>"  onclick="pull('<?php echo $this->webroot ?>', this,<?php echo $i; ?>)"  class="jsp_resourceNew_style_1"  src="<?php echo $this->webroot ?>images/+.gif" title="<?php __('View All') ?>"/>
                                    </td>
                                    <td><?php echo $this->data[$i]['LrnSetting']['name']; ?></td>
                                    <td><?php echo $strategies[(int) $this->data[$i]['LrnSetting']['rule']]; ?></td>
                                    <td>


                                        <a title="<?php __('IPs') ?>" href="<?php echo $this->webroot; ?>lrn_settings/items/<?php echo base64_encode($this->data[$i]['LrnSetting']['id']) ?>">
                                            <i class="icon-list-alt"></i>
                                        </a>
                                        <?php
                                        if ($_SESSION['role_menu']['Configuration']['lrn_settings']['model_w'])
                                        {
                                            ?>
                                            <?php
                                            if ($this->data[$i]['LrnSetting']['active'] == '1'):
                                                ?>
                                                <a title="<?php __("Inactive"); ?>" href="<?php echo $this->webroot; ?>lrn_settings/change_group_status/<?php echo base64_encode($this->data[$i]['LrnSetting']['id']) ?>/0">
                                                    <i class="icon-check"></i>
                                                </a>
                                            <?php else: ?>
                                                <a title="<?php __("Active"); ?>" href="<?php echo $this->webroot; ?>lrn_settings/change_group_status/<?php echo base64_encode($this->data[$i]['LrnSetting']['id']) ?>/1">
                                                    <i class="icon-unchecked"></i>
                                                </a>
                                            <?php endif; ?>
                                            <a title="<?php __('Edit') ?>" class="edit_item" href="###" control="<?php echo $this->data[$i]['LrnSetting']['id'] ?>" >
                                                <i class="icon-edit"></i>
                                            </a>
                                            <a title="<?php __('Delete') ?>" onclick="return myconfirm('Are you sure?', this);" class="delete" href='<?php echo $this->webroot; ?>lrn_settings/delete_group/<?php echo base64_encode($this->data[$i]['LrnSetting']['id']) ?>'>
                                                <i class='icon-remove'></i>
                                            </a>
                                        <?php } ?>
                                    </td>
                                </tr>
                                <tr style="height:auto">
                                    <td colspan="4">
                                        <div id="ipInfo<?php echo $i ?>" class=" jsp_resourceNew_style_2" style="padding:5px;display: none;"> 
                                            <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
                                                <tr>
                                                    <td>#</td>
                                                    <td><?php __('IP') ?></td>
                                                    <td><?php __('Port') ?></td>
                                                    <td><?php __('Timeout') ?></td>
                                                    <td><?php __('Retry') ?></td>
                                                    <td><?php __('Dynamic Timeout') ?></td>
                                                    <td><?php __('Filter Timeout') ?></td>
                                                    <td><?php __('Option') ?></td>
                                                    <td><?php __('Option interval') ?></td>
                                                </tr>
                                                <?php
                                                $j = 1;
                                                foreach ($this->data[$i]['Item'] as $item):
                                                    ?>
                                                    <tr>
                                                        <td>#<?php echo $j ?></td>
                                                        <td><?php echo $item['ip']; ?></td>
                                                        <td><?php echo $item['port']; ?></td>
                                                        <td><?php echo $item['timeout']; ?></td>
                                                        <td><?php echo $item['retry']; ?></td>
                                                        <td><?php echo $item['dynamic_timeout']; ?></td>
                                                        <td><?php echo $item['filter_timeout']; ?></td>
                                                        <td><?php echo $item['option']; ?></td>
                                                        <td><?php echo $item['option_interval']; ?></td>
                                                    </tr>
                                                    <?php
                                                    $j++;
                                                endforeach;
                                                ?>
                                            </table>
                                        </div>
                                    </td>
                                </tr>
                            <?php endfor; ?>
                        </tbody>
                    </table>
                    <div id="container">
                        <div class="bottom row-fluid">
                            <div class="pagination pagination-large pagination-right margin-none">
                                <?php echo $this->element('xpage'); ?>
                            </div> 
                        </div>
                    <?php endif; ?>
                </div>

                <script type="text/javascript">


                    jQuery(function() {
                        jQuery('#add').click(function() {
                            $('.msg').hide();
                            $('#mylist').show();
                            jQuery('#mylist > tbody').trAdd({
                                ajax: "<?php echo $this->webroot ?>lrn_settings/edit_group_panel",
                                action: "<?php echo $this->webroot ?>lrn_settings/edit_group_panel",
                                removeCallback: function() {
                                    if (jQuery('table.list tr').size() == 1) {
                                        jQuery('table.list').hide();
                                        $('.msg').show();
                                    }
                                },
                                insertNumber: 'first'
                            });
                            jQuery(this).parent().parent().show();
                        });

                        jQuery('a.edit_item').click(function() {
                            jQuery(this).parent().parent().trAdd({
                                action: '<?php echo $this->webroot ?>lrn_settings/edit_group_panel/' + jQuery(this).attr('control'),
                                ajax: '<?php echo $this->webroot ?>lrn_settings/edit_group_panel/' + jQuery(this).attr('control'),
                                saveType: 'edit',
                                insertNumber: 'first'
                            });
                        });
                    });

                    $(function() {
<?php if (empty($this->data)): ?>
                            $('#add').trigger("click");
<?php endif; ?>
                    });
                </script>
            </div>
        </div>
    </div>