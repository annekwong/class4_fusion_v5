<ul class="breadcrumb">
    <li><?php __('You are here')?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?= $this->webroot?>import_export_log/export"><?php echo __('Log', true); ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?= $this->webroot?>import_export_log/export"><?php echo __('Rate Bot Import Logs') ?></a></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Rate Bot Import Logs', true); ?></h4>
    <div class="buttons pull-right">

    </div>
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>

<div class="innerLR">

    <div class="widget widget-heading-simple widget-body-white">
        <div class="widget-body">

            <?php if (empty($logs)): ?>
                <?php echo $this->element('common/no_result') ?>
            <?php else: ?>
                <div class="clearfix"></div>
                <div class="overflow_x">
                    <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
                        <thead>
                        <tr>
                            <th><?php echo $appCommon->show_order('rule_name', __('Rule Name',true)); ?></th>
                            <th><?php echo __('Time and Date', true); ?></th>
                            <th><?php echo __('Status', true); ?></th>
                            <th><?php echo __('Mail sent to Vendor', true); ?></th>
                            <th><?php echo __('Action',true); ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($logs as $log): ?>
                            <tr id="log_<?php echo $log['RateBotImportLogs']['id'] ?>">
                                <td><?php echo $log['RateBotImportLogs']['rule_name'] ?></td>
                                <td><?php echo $log['RateBotImportLogs']['start_time'] ?></td>
                                <td><?php echo $statuses[$log['RateBotImportLogs']['status']] ?></td>
                                <td><?php echo $log['RateBotImportLogs']['mail_vendor'] ?></td>
                                <td>
                                    <?php if (strlen($log['RateBotImportLogs']['error_msg']) > 0): ?>
                                        <a href="javascript:void(0)" title="<?php echo $log['RateBotImportLogs']['error_msg'] ?>">
                                            <i class="icon-info"></i>
                                        </a>
                                    <?php endif ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="row-fluid">
                    <div class="pagination pagination-large pagination-right margin-none">
                        <?php echo $this->element('xpage'); ?>
                    </div>
                </div>
                <div class="clearfix"></div>
            <?php endif; ?>
        </div>
    </div>
</div>