<ul class="breadcrumb">
    <li><?php __('You are here'); ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Monitoring') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Loop Detection') ?></li>
</ul>


<div class="heading-buttons">
    <h4><?php __('Loop Detection') ?></h4>
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
    <a class="btn btn-primary btn-icon glyphicons circle_plus" href="<?php echo $this->webroot; ?>loop_detection/save"><i></i><?php __('Create New')?></a>
</div>
<div class="clearfix"></div>


<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">

        <div class="widget-head">
            <?php echo $this->element('loop_detection/tab', array('current_page' => 0)); ?>
        </div>

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
                    <th><?php echo $appCommon->show_order('rule_name', __('Rule Name', true)) ?></th>
                    <th><?php echo $appCommon->show_order('counter_time', __('Counter Time(s)', true)) ?></th>
                    <th><?php echo $appCommon->show_order('number', __('Number', true)) ?></th>
                    <th><?php echo $appCommon->show_order('block_time', __('Block Time(s)', true)) ?></th>
                    <th><?php __('Trunk Count')?></th>
                    <th><?php __('Action')?></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($this->data as $item): ?>
                    <tr>
                        <td><?php echo $item['LoopDetection']['rule_name']; ?></td>
                        <td><?php echo $item['LoopDetection']['counter_time']; ?></td>
                        <td><?php echo $item['LoopDetection']['number']; ?></td>
                        <td><?php echo $item['LoopDetection']['block_time']; ?></td>
                        <td><?php echo count($item['LoopDetectionDetail']); ?></td>
                        <td>
                            <a title="<?php __('edit'); ?>" href="<?php echo $this->webroot; ?>loop_detection/save/<?php echo base64_encode($item['LoopDetection']['id']); ?>">
                                <i class="icon-edit"></i>
                            </a>
                            <a title="<?php __('delete'); ?>" onclick="return myconfirm('<?php __('sure to delete'); ?>',this);" href="<?php echo $this->webroot; ?>loop_detection/delete_rule/<?php echo base64_encode($item['LoopDetection']['id']); ?>">
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
        </div>
    </div>
</div>
