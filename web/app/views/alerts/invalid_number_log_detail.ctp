<ul class="breadcrumb">
    <li><?php __('You are here'); ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Monitoring') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Invalid Number Detection Log Detail') ?></li>
</ul>


<div class="heading-buttons">
    <h4 class="heading"><?php __('Invalid Number Detection Log Detail') ?></h4>

</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
</div>
<div class="clearfix"></div>

<div class="innerLR">
    <div class="widget widget-tabs widget-body-white">
        <div class="widget-head">
            <?php if ($this->params['pass'][0] == 1): ?>
                <?php echo $this->element("invalid_number_detection/tab",array('active' => 'ani_exec')); ?>
            <?php else: ?>
                <?php echo $this->element("invalid_number_detection/tab",array('active' => 'dnis_exec')); ?>
            <?php endif; ?>
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
            <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
                <thead>
                <tr>
                    <?php if ($this->params['pass'][0] == 1): ?>
                        <th rowspan="2"><?php echo $appCommon->show_order('InvalidDetectionLogDetail.number', __('Invalid ANI', true)) ?></th>
                    <?php else: ?>
                        <th rowspan="2"><?php echo $appCommon->show_order('InvalidDetectionLogDetail.number', __('Invalid DNIS', true)) ?></th>
                    <?php endif; ?>
                    <th rowspan="2"><?php echo $appCommon->show_order('Resource.alias', __('Ingress', true)) ?></th>
                    <th rowspan="2"><?php echo $appCommon->show_order('InvalidDetectionLogDetail.start_time', __('Cycle Begin', true)) ?></th>
                    <th rowspan="2"><?php echo $appCommon->show_order('InvalidDetectionLogDetail.end_time', __('Cycle End', true)) ?></th>
                    <th rowspan="2"><?php echo $appCommon->show_order('InvalidDetectionLogDetail.total_call', __('#of Attempt', true)) ?></th>
                    <th colspan="4"><?php __('Retrun Cause'); ?></th>
                    <th rowspan="2"><?php __('Action'); ?></th>
                </tr>
                <tr>
                    <th><?php echo $appCommon->show_order('InvalidDetectionLogDetail.count404', __('[404]', true)) ?></th>
                    <th><?php echo $appCommon->show_order('InvalidDetectionLogDetail.count503', __('[503]', true)) ?></th>
                    <th><?php echo $appCommon->show_order('InvalidDetectionLogDetail.count200', __('[200]', true)) ?></th>
                    <th><?php echo $appCommon->show_order('InvalidDetectionLogDetail.others_call', __('Other', true)) ?></th>
                </tr>
                </thead>

                <tbody>
                <?php foreach ($this->data as $item): ?>
                    <tr>
                        <td><?php echo $item['InvalidDetectionLogDetail']['number']; ?></td>
                        <td><?php echo $item['Resource']['alias']; ?></td>
                        <td><?php echo $item['InvalidDetectionLogDetail']['start_time']; ?></td>
                        <td><?php echo $item['InvalidDetectionLogDetail']['end_time']; ?></td>
                        <td><?php echo $item['InvalidDetectionLogDetail']['total_call']; ?></td>
                        <td><?php echo $item['InvalidDetectionLogDetail']['count404']; ?></td>
                        <td><?php echo $item['InvalidDetectionLogDetail']['count503']; ?></td>
                        <td><?php echo $item['InvalidDetectionLogDetail']['count200']; ?></td>
                        <td><?php echo $item['InvalidDetectionLogDetail']['others_call']; ?></td>
                        <td>
                            <a title="<?php __('Block'); ?>" onclick="myconfirm('<?php __('sure to block'); ?>',this);return false;" href="<?php echo $this->webroot; ?>alerts/invalid_number_block/<?php echo $this->params['pass'][0] ?>?log=<?php echo base64_encode($item['InvalidDetectionLogDetail']['id']) ?>">
                                <i class="icon-microphone-off"></i>
                            </a>
                            <a title="<?php __('UnBlock'); ?>" onclick="myconfirm('<?php __('sure to unblock'); ?>',this);return false;" href="<?php echo $this->webroot; ?>alerts/invalid_number_unblock/<?php echo $this->params['pass'][0] ?>?log=<?php echo base64_encode($item['InvalidDetectionLogDetail']['id']) ?>">
                                <i class="icon-microphone"></i>
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
        </div>
    </div>
</div>

<script type="text/javascript">
    $(function() {

    });
</script>
