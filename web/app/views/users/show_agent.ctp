<ul class="breadcrumb">
    <li><?php __('You are here'); ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>agent/management">
        <?php __('Agent') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>agent/management">
        <?php echo $this->pageTitle; ?></a></li>
</ul>


<div class="heading-buttons">
    <h4 class="heading"><?php echo $this->pageTitle; ?></h4>

</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
    <a class="btn btn-primary btn-icon glyphicons circle_plus" href="<?php echo $this->webroot; ?>agent/save_agent"><i></i><?php __('Create New')?></a>
</div>
<div class="clearfix"></div>

<div class="innerLR">
    <div class="widget widget-tabs widget-body-white">
        <div class="widget-head">
            <?php echo $this->element('tabs', Array('tabs' => Array(__('System Users', true) => Array('url' => 'users/index', 'icon' => 'list'), __('Carrier Users', true) => Array('url' => 'users/show_carrier', 'icon' => 'parents'), __('Online Users', true) => Array('url' => 'users/show_online', 'icon' => 'user'), __('Non-Active Users', true) => Array('url' => 'users/view', 'icon' => 'girl'), __('Agent List', true) => Array('url' => 'users/show_agent', 'icon' => 'list',
                            'active' => true) ))) ?>
        </div>
        <div class="widget-body">
            <div class="filter-bar">
                <form action="" method="get">
                    <div>
                        <label><?php __('Agent Name') ?>:</label>
                        <input type="text" name="agent_name" value="<?php echo $appCommon->_get('agent_name'); ?>" />
                    </div>
                    <div>
                        <button name="submit" class="btn query_btn"><?php __('Query') ?></button>
                    </div>
                </form>
            </div>
            <div class="clearfix"></div>
            <?php if (!count($this->data)): ?>
                <div class="msg center"><br /><h2><?php echo __('no_data_found', true); ?></h2></div>
            <?php else: ?>
                <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
                    <thead>
                    <tr>
                        <th><?php echo $appCommon->show_order('agent_name', __('Agent Name', true)) ?></th>
                        <th><?php echo $appCommon->show_order('email', __('Agent Email', true)) ?></th>
                        <th><?php echo $appCommon->show_order('commission', __('Commission', true)) ?></th>
                        <th><?php echo $appCommon->show_order('method_type', __('Method', true)) ?></th>
                        <th><?php echo $appCommon->show_order('frequency_type', __('Frequency', true)) ?></th>
                        <th><?php __('Last Updated') ?></th>
                        <th><?php __('Update By'); ?></th>
                        <th><?php __('Action'); ?></th>
                    </tr>
                    </thead>

                    <tbody>
                    <?php foreach ($this->data as $item): ?>
                        <tr>
                            <td><?php echo $item['Agent']['agent_name']; ?></td>
                            <td><?php echo $item['Agent']['email']; ?></td>
                            <td><?php echo $item['Agent']['commission']; ?>%</td>
                            <td><?php echo $method_type[$item['Agent']['method_type']]; ?></td>
                            <td><?php echo $frequency_type[$item['Agent']['frequency_type']]; ?></td>
                            <td><?php echo $item['Agent']['update_on']; ?></td>
                            <td><?php echo $item['Agent']['update_by']; ?></td>
                            <td>
                                <a title="<?php __('edit') ?>" href="<?php echo $this->webroot; ?>agent/save_agent/<?php echo base64_encode($item['Agent']['agent_id']) ?>" >
                                    <i class="icon-edit"></i>
                                </a>
                                <?php if ($item['Agent']['status']): ?>
                                    <a title="<?php __('inactive')?>" onclick="myconfirm('<?php __('Are you sure to Inactive?') ?>', this);return false;" href="<?php echo $this->webroot ?>agent/dis_able/<?php echo base64_encode($item['Agent']['agent_id']) ?>"><i class="icon-check"></i></a>
                                <?php else: ?>
                                    <a title="<?php __('active')?>" onclick="myconfirm('<?php __('Are you sure to active?') ?>?', this);return false;" href="<?php echo $this->webroot ?>agent/active/<?php echo base64_encode($item['Agent']['agent_id']) ?>"><i class="icon-check-empty"></i></a>
                                <?php endif; ?>
                                <a title="<?php __('Manage Clients') ?>" href="<?php echo $this->webroot; ?>agent/agent_client/<?php echo base64_encode($item['Agent']['agent_id']) ?>" >
                                    <i class="icon-list-alt"></i>
                                </a>
                                <a title="Delete" onclick="return myconfirm('<?php __('sure to delete') ?>', this)"
                                   class="delete" href='<?php echo $this->webroot ?>agent/delete_agent/<?php echo base64_encode($item['Agent']['agent_id']) ?>'>
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
