<ul class="breadcrumb">
    <li><?php __('You are here'); ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>import_rule/view">
        <?php __('Tools') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot . $this->params['url']['url'] ?>">
        <?php echo $this->pageTitle; ?></a></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo $this->pageTitle; ?></h4>
    <div class="buttons pull-right">
    </div>
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
    <a class="btn btn-primary btn-icon glyphicons circle_plus" id="add" href="<?php echo $this->webroot?>import_rule/save_rule">
        <i></i> <?php __('Create New') ?></a>
</div>
<div class="clearfix"></div>

<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">

        <div class="widget-body">
            <?php
            $count = count($this->data);
            if (!$count):
                ?>
                <h2 class="msg center"><br /><?php __('No data found'); ?></h2>
                <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary default footable-loaded hide">
                    <thead>
                    <tr>
                        <th><?php __('Rule Name'); ?></th>
                        <th><?php __('Egress Trunk'); ?></th>
                        <th><?php __('Rate Table'); ?></th>
                        <th><?php __('Created On'); ?></th>
                        <th><?php __('Last Modified'); ?></th>
                        <th><?php __('Modified By'); ?></th>
                        <th><?php __('Last Received'); ?></th>
                        <th><?php __('Blocked'); ?></th>
                        <th><?php __('Active'); ?></th>
                        <th><?php __('Action'); ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            <?php else: ?>
                <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary default footable-loaded">
                    <thead>
                    <tr>
                        <th><?php __('Rule Name'); ?></th>
                        <th><?php __('Egress Trunk'); ?></th>
                        <th><?php __('Rate Table'); ?></th>
                        <th><?php __('Created On'); ?></th>
                        <th><?php __('Last Modified'); ?></th>
                        <th><?php __('Modified By'); ?></th>
                        <th><?php __('Last Received'); ?></th>
                        <th><?php __('Blocked'); ?></th>
                        <th><?php __('Active'); ?></th>
                        <th><?php __('Action'); ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($this->data as $items)
                    {
                        ?>
                        <tr>
                            <td><?php echo $items['RateSendRules']['rule_name'] ?></td>
                            <td><?php echo $items['Resource']['alias'] ?></td>
                            <td><?php echo $items['RateTable']['name'] ?></td>
                            <td><?php echo $items['RateSendRules']['create_time'] ?></td>
                            <td><?php echo $items['RateSendRules']['update_at'] ?></td>
                            <td><?php echo $items['RateSendRules']['update_by'] ?></td>
                            <td></td>
                            <td><?php echo $items['RateSendRules']['blocked'] ? 'Yes' : 'No' ?></td>
                            <td><?php echo $items['RateSendRules']['active'] ? 'Yes' : 'No' ?></td>
                            <td>
                                <?php if ($items['RateSendRules']['active']): ?>
                                    <a title="<?php __('Deactivate') ?>" onclick="return myconfirm('<?php __('Are you sure to deactivate it?'); ?>',this);"
                                       href="<?php echo $this->webroot; ?>import_rule/disable_rule/<?php echo base64_encode($items['RateSendRules']['id']) ?>" >
                                        <i class="icon-check"></i>
                                    </a>
                                <?php else: ?>
                                    <a title="<?php __('Activate') ?>"  onclick="return myconfirm('<?php __('Are you sure to activate it?'); ?>',this);"
                                       href="<?php echo $this->webroot; ?>import_rule/enable_rule/<?php echo base64_encode($items['RateSendRules']['id']) ?>" >
                                        <i class="icon-unchecked"></i>
                                    </a>
                                <?php endif; ?>
                                <a class="edit_item" href="<?php echo $this->webroot . 'import_rule/save_rule/' . base64_encode($items['RateSendRules']['id']) ?>" title="<?php __('edit'); ?>">
                                    <i class="icon-edit"></i>
                                </a>
                                <a onclick="return myconfirm('<?php __('sure to delete'); ?>',this)"
                                   href="<?php echo $this->webroot; ?>import_rule/delete/<?php echo base64_encode($items['RateSendRules']['id']); ?>" title="<?php __('Delete'); ?>">
                                    <i class="icon-remove"></i>
                                </a>
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
                <div class="separator">
                    <div class="pagination pagination-large pagination-right margin-none">
                        <?php echo $this->element('xpage'); ?>
                    </div>
                </div>

            <?php endif; ?>
        </div>



        <div class="clearfix"></div>
    </div>
</div>
