<ul class="breadcrumb">
    <li><?php __('You are here'); ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Configuration') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Carrier Group') ?></li>
</ul>
<?php $write = $_SESSION['role_menu']['Configuration']['carrier_group']['model_w']; ?>


<div class="heading-buttons">
    <h4><?php __('Carrier Group') ?></h4>
    <div class="clearfix"></div>
</div>
<?php if ($write) { ?>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
    <a class="btn btn-primary btn-icon glyphicons circle_plus" href="<?php echo $this->webroot; ?>carrier_group/save"><i></i><?php __('Create New')?></a>
</div>
<?php }?>
<div class="clearfix"></div>


<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">

        <div class="widget-body">
            <!--
            <div class="filter-bar">
                <form method="get">
                    <div>
                        <label><?php __('Trunk Name')?>:</label>
                        <input type="text" id="search-_q" class="in-search input in-text defaultText" title="<?php echo __('namesearch') ?>" value="<?php if (!empty($search)) echo $search; ?>" name="search">
                    </div>
                    <div>
                        <button name="submit" class="btn query_btn"><?php __('Query')?></button>
                    </div>

                </form>
            </div>
        -->
            <div class="clearfix"></div>
            <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
                <thead>
                <tr>
                    <th><?php echo $appCommon->show_order('group_name', __('Group Name', true)) ?></th>
                    <th><?php __('Carrier Count')?></th>
                    <th><?php __('Action')?></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($this->data as $item): ?>
                    <tr>
                        <td><?php echo $item['CarrierGroup']['group_name']; ?></td>
                        <td>
                            <a title="<?php __('Carrier List'); ?>" href="<?php echo $this->webroot; ?>carrier_group/view_carrier_list/<?php echo base64_encode($item['CarrierGroup']['group_id']); ?>">
                                <?php echo $item[0]['use_total']; ?>
                            </a>
                        </td>
                        <td>
                            <?php if($write) { ?>
                            <a title="<?php __('edit'); ?>" href="<?php echo $this->webroot; ?>carrier_group/save/<?php echo base64_encode($item['CarrierGroup']['group_id']); ?>">
                                <i class="icon-edit"></i>
                            </a>
                            <?php }?>
                            <a title="<?php __('Carrier List'); ?>" href="<?php echo $this->webroot; ?>carrier_group/view_carrier_list/<?php echo base64_encode($item['CarrierGroup']['group_id']); ?>">
                                <i class="icon-list"></i>
                            </a>
                            <?php if($write) { ?>
                            <a title="<?php __('delete'); ?>" onclick="return myconfirm('<?php __('sure to delete'); ?>',this);" href="<?php echo $this->webroot; ?>carrier_group/delete_group/<?php echo base64_encode($item['CarrierGroup']['group_id']); ?>">
                                <i class="icon-remove"></i>
                            </a>
                            <?php }?>
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
        </div>
    </div>
</div>
