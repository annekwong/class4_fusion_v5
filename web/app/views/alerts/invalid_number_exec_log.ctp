<ul class="breadcrumb">
    <li><?php __('You are here'); ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>alerts/invalid_number">
        <?php __('Monitoring') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot . $this->params['url']['url'] ?>">
        <?php __('Invalid Number Detection') ?></a></li>
</ul>


<div class="heading-buttons">
    <h4 class="heading"><?php __('Invalid Number Detection') ?></h4>

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
                    <?php echo $form->input(__('ingress',true), array('type' => 'select','name' => 'ingress',
                        'options' => $ingress_trunk,'value' =>$appCommon->_get('ingress') )); ?>
                    <?php echo $form->input(__('show',true), array('type' => 'select','name' => 'show_type','class' => 'width100',
                        'options' => array(__('all',true),__('non zero',true)),'default' => 1,'value' =>$appCommon->_get('show_type') )); ?>
                    <?php echo $this->element('common/log_query_datetime',array('label' => __('Executed On',true),'datetime' => 1)); ?>

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
                        <th><?php echo $appCommon->show_order('InvalidNumber.rule_name', __('Rule Name', true)) ?></th>
                        <th><?php echo $appCommon->show_order('Resource.alias', __('Ingress', true)) ?></th>
                        <th><?php echo $appCommon->show_order('InvalidDetectionLog.start_time', __('Executed On', true)) ?></th>
                        <th><?php echo $appCommon->show_order('InvalidDetectionLog.finished_time', __('Finished On', true)) ?></th>
                        <?php if ($this->params['pass'][0] == 1): ?>
                            <th><?php echo $appCommon->show_order('InvalidDetectionLog.total_num', __('#of ANI', true)) ?></th>
                            <th><?php echo $appCommon->show_order('InvalidDetectionLog.invalid_num', __('#of Invalid ANI', true)) ?></th>
                        <?php else: ?>
                            <th><?php echo $appCommon->show_order('InvalidDetectionLog.total_num', __('#of DNIS', true)) ?></th>
                            <th><?php echo $appCommon->show_order('InvalidDetectionLog.invalid_num', __('#of Invalid DNIS', true)) ?></th>
                        <?php endif; ?>
                    </tr>
                    </thead>

                    <tbody>
                    <?php foreach ($this->data as $item): ?>
                        <tr>
                            <td><?php echo $item['InvalidNumber']['rule_name']; ?></td>
                            <td><?php echo $item['Resource']['alias']; ?></td>
                            <td><?php echo $item['InvalidDetectionLog']['start_time']; ?></td>
                            <td><?php echo $item['InvalidDetectionLog']['finished_time']; ?></td>
                            <td><?php echo $item['InvalidDetectionLog']['total_num']; ?></td>
                            <td>
                                <?php if($item['InvalidDetectionLog']['invalid_num']): ?>
                                    <a href="<?php echo $this->webroot; ?>alerts/invalid_number_log_detail/<?php echo $this->params['pass'][0]; ?>?type=ani&log=<?php echo base64_encode($item['InvalidDetectionLog']['id']) ?>">
                                        <?php echo $item['InvalidDetectionLog']['invalid_num']; ?>
                                    </a>
                                <?php else: ?>
                                    0
                                <?php endif; ?>
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
</script>
