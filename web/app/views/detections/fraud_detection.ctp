<ul class="breadcrumb">
    <li><?php __('You are here'); ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?= $this->webroot?>detections/fraud_detection"><?php __('Monitoring') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?= $this->webroot?>detections/fraud_detection"><?php __('Fraud Detection') ?></a></li>
</ul>


<div class="heading-buttons">
    <h4 class="heading"><?php __('Fraud Detection') ?></h4>

</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
    <a class="btn btn-primary btn-icon glyphicons circle_plus" href="<?php echo $this->webroot; ?>detections/add_fraud_detection"><i></i><?php __('Create New')?></a>
</div>
<div class="clearfix"></div>

<div class="innerLR">
    <div class="widget widget-tabs widget-body-white">
        <div class="widget-head">
            <?php echo $this->element("fraud_detection/tab",array('active' => 'list')); ?>
        </div>
        <div class="widget-body">
            <div class="filter-bar">
                <form action="" method="get">
                    <div>
                        <label><?php __('Rule Name') ?>:</label>
                        <input type="text" name="rule_name" value="<?php echo $appCommon->_get('rule_name'); ?>" />
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
                        <th><?php echo $appCommon->show_order('rule_name', __('Rule Name', true)) ?></th>
                        <th><?php echo $appCommon->show_order('hourly_minute', __('1 hour Minute', true)) ?></th>
                        <th><?php echo $appCommon->show_order('hourly_revenue', __('1 hour Revenue', true)) ?></th>
                        <th><?php echo $appCommon->show_order('daily_minute', __('24 hours Minute', true)) ?></th>
                        <th><?php echo $appCommon->show_order('daily_revenue', __('24 hours Revenue', true)) ?></th>
                        <th><?php __('Block'); ?></th>
                        <th><?php __('Send email'); ?></th>
                        <th><?php __('Active'); ?></th>
                        <th><?php echo $appCommon->show_order('update_on', __('Last Update', true)) ?></th>
                        <th><?php echo $appCommon->show_order('update_by', __('Update By', true)) ?></th>
                        <th><?php __('Action'); ?></th>
                    </tr>
                    </thead>

                    <tbody>
                    <?php foreach ($this->data as $item): ?>
                        <tr>
                            <td><?php echo $item['FraudDetection']['rule_name']; ?></td>
                            <td><?php echo $item['FraudDetection']['hourly_minute']; ?></td>
                            <td><?php echo $item['FraudDetection']['hourly_revenue']; ?></td>
                            <td><?php echo $item['FraudDetection']['daily_minute']; ?></td>
                            <td><?php echo $item['FraudDetection']['daily_revenue']; ?></td>
                            <td><?php echo $item['FraudDetection']['is_block'] ? 'Yes' : 'No'; ?></td>
                            <td><?php echo $item['FraudDetection']['is_send_mail'] ? 'Yes' : 'No'; ?></td>
                            <td><?php echo $item['FraudDetection']['active'] ? 'Yes' : 'No'; ?></td>
                            <td><?php echo $item['FraudDetection']['update_on']; ?></td>
                            <td><?php echo $item['FraudDetection']['update_by']; ?></td>
                            <td>
                                <?php if ($item['FraudDetection']['active']): ?>
                                    <a title="<?php __('Inactive') ?>" onclick="return myconfirm('Are you sure to deactivate it?',this);"
                                       href="<?php echo $this->webroot; ?>detections/disable_fraud/<?php echo base64_encode($item['FraudDetection']['id']) ?>" >
                                        <i class="icon-check"></i>
                                    </a>
                                <?php else: ?>
                                    <a title="<?php __('Active') ?>"  onclick="return myconfirm('Are you sure to activate it?',this);"
                                       href="<?php echo $this->webroot; ?>detections/enable_fraud/<?php echo base64_encode($item['FraudDetection']['id']) ?>" >
                                        <i class="icon-unchecked"></i>
                                    </a>
                                <?php endif; ?>
                                <a title="<?php __('edit') ?>" href="<?php echo $this->webroot; ?>detections/add_fraud_detection/<?php echo base64_encode($item['FraudDetection']['id']) ?>" >
                                    <i class="icon-edit"></i>
                                </a>
                                <a title="Delete" onclick="return myconfirm('<?php __('sure to delete') ?>', this)" class="delete" href='<?php echo $this->webroot ?>detections/delete_fraud_detection/<?php echo base64_encode($item['FraudDetection']['id']) ?>'>
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

<script type="text/javascript">
    $(function() {

    });

    $(document).on('DOMNodeInserted', function(){
        $('thead .sorting').attr('title', 'Sort');
        $('tbody a[title="Active"]').attr('title', 'Activate');
    });
</script>
