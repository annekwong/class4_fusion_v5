<ul class="breadcrumb">
    <li><?php __('You are here'); ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Monitoring') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Loop Detection') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Log') ?></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php __('Log') ?></h4>
    
</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
        <a href="<?php echo $this->webroot; ?>loop_detection/logging" class="link_back_new btn btn-icon btn-inverse glyphicons circle_arrow_left">
            <i></i>&nbsp;<?php __('Back'); ?></a> 
    </div>
    <div class="clearfix"></div>
<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-head">
            <?php echo $this->element('loop_detection/tab', array('current_page' => 1)); ?>
        </div>
        <div class="widget-body">
            <?php
            if (empty($this->data)):
                ?>
                <div class="msg"><?php echo __('no_data_found', true); ?></div>
            <?php else: ?>
            <div class="clearfix"></div>
                <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
                    <thead>
                    <th><?php __('ANI'); ?></th>
                    <th><?php __('DNIS'); ?></th>
                    <th><?php __('Occurrence Count'); ?></th>
                    <th><?php __('Interval Starts ON'); ?></th>
                    <th><?php __('Interval Ends ON'); ?></th>
                    </thead>
                    <tbody>
                        <?php foreach ($this->data as $item): ?>
                            <tr>
                                <td><?php echo $item['LoopDetectionLogDetail']['ani']; ?></td>
                                <td><?php echo $item['LoopDetectionLogDetail']['dnis']; ?></td>
                                <td><?php echo $item['LoopDetectionLogDetail']['occurrence_count']; ?></td>
                                <td><?php echo $item['LoopDetectionLogDetail']['interval_starts_on']; ?></td>
                                <td><?php echo $item['LoopDetectionLogDetail']['interval_end_on']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
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