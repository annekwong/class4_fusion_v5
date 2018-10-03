<ul class="breadcrumb">
    <li><?php __('You are here'); ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>alerts/invalid_number">
        <?php __('Monitoring') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>alerts/invalid_number_block_log">
        <?php __('Invalid Number Detection Block Log') ?></a></li>
</ul>


<div class="heading-buttons">
    <h4 class="heading"><?php __('Invalid Number Detection Block Log') ?></h4>

</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
</div>
<div class="clearfix"></div>

<div class="innerLR">
    <div class="widget widget-tabs widget-body-white">
        <div class="widget-head">
            <?php echo $this->element("invalid_number_detection/tab",array('active' => 'block_log')); ?>
        </div>
        <div class="widget-body">
            <!--
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
            -->
            <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
                <thead>
                <tr>
                    <th><?php echo $appCommon->show_order('InvalidDetection.rule_name', __('Rule Name', true)) ?></th>
                    <th><?php echo $appCommon->show_order('InvalidDetectionBlockLog.created_on', __('Modify On', true)) ?></th>
                    <th><?php echo $appCommon->show_order('InvalidDetectionBlockLog.block_type', __('Block Type', true)) ?></th>
                    <th><?php echo $appCommon->show_order('InvalidDetectionBlockLog.number_type', __('Type', true)) ?></th>
                    <th><?php echo $appCommon->show_order('InvalidDetectionBlockLog.number', __('Number', true)) ?></th>
                    <th><?php echo $appCommon->show_order('Resource.alias', __('Ingress', true)) ?></th>
<!--                    <th>--><?php //__('Action'); ?><!--</th>-->
                </tr>
                </thead>

                <tbody>
                <?php foreach ($this->data as $item): ?>
                    <tr>
                        <td><?php echo $item['InvalidDetection']['rule_name']; ?></td>
                        <td><?php echo $item['InvalidDetectionBlockLog']['created_on']; ?></td>
                        <td><?php if($item['InvalidDetectionBlockLog']['block_type'] == 1){ __('Block');}else{ __('unBlock'); } ?></td>
                        <td><?php if($item['InvalidDetectionBlockLog']['number_type'] == 1){ __('ANI');}else{ __('DNIS'); } ?></td>
                        <td><?php echo $item['InvalidDetectionBlockLog']['number']; ?></td>
                        <td><?php echo $item['Resource']['alias']; ?></td>
                        <!--
                        <td>

                            <a title="<?php __('Block'); ?>" onclick="myconfirm('<?php __('sure to block'); ?>',this);return false;" href="<?php echo $this->webroot; ?>alerts/invalid_number_block/<?php echo $this->params['pass'][0] ?>?log=<?php echo base64_encode($item['InvalidDetectionLogDetail']['id']) ?>">
                                <i class="icon-microphone-off"></i>
                            </a>
                            <a title="<?php __('UnBlock'); ?>" onclick="myconfirm('<?php __('sure to unblock'); ?>',this);return false;" href="<?php echo $this->webroot; ?>alerts/invalid_number_unblock/<?php echo $this->params['pass'][0] ?>?log=<?php echo base64_encode($item['InvalidDetectionLogDetail']['id']) ?>">
                                <i class="icon-microphone"></i>
                            </a>
                        </td> -->
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
