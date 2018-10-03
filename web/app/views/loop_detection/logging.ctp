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
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>


<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-head">
            <?php echo $this->element('loop_detection/tab', array('current_page' => 1)); ?>
        </div>
        <div class="widget-body">
            <?php
            if (empty($this->data)):
                ?>
                <h2 class="msg center"><?php echo __('no_data_found', true); ?></h2>
            <?php else: ?>
                <div class="clearfix"></div>
                <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
                    <thead>
                    <th><?php __('Execution Time'); ?></th>
                    <th><?php __('End Time'); ?></th>
                    <th><?php __('Loop Count'); ?></th>
                    </thead>
                    <tbody>
                        <?php foreach ($this->data as $item): ?>
                            <tr>
                                <td><?php echo $item['LoopDetectionLog']['execution_time']; ?></td>
                                <td><?php echo $item['LoopDetectionLog']['end_time']; ?></td>
                                <td>
                                    <?php if(count($item['LoopDetectionLogDetail'])){ ?>
                                    <a href="<?php echo $this->webroot; ?>loop_detection/logging_detail/<?php echo $item['LoopDetectionLog']['id'] ?>">
                                        <?php echo count($item['LoopDetectionLogDetail']); ?></a>
                                    <?php }else{ echo count($item['LoopDetectionLogDetail']); } ?>
                                </td>
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