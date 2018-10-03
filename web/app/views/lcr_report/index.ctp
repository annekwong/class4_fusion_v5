
<ul class="breadcrumb">
    <li><?php __('You are here')?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Statistics') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('LCR Report') ?></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('LRN Report') ?></h4>
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>
<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-head">
            <ul class="tabs">
                <li>
                    <a href="<?php echo $this->webroot ?>lcr_report/search" class="glyphicons search">
                        <?php __('Search')?>
                        <i></i>
                    </a>
                </li>
                <li class='active'>
                    <a href="<?php echo $this->webroot ?>lcr_report/index" class="glyphicons list">
                        <?php __('List')?>
                        <i></i>
                    </a>
                </li>
            </ul>
        </div>
        <div class="widget-body">
            <?php
            if (empty($this->data)):
                ?>
                <div class="msg"><?php echo __('no_data_found', true); ?></div>
                <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary" style="display:none;">

                    <thead>
                        <tr>
                            <th><?php __('Start Time')?></th>
                            <th><?php __('End Time')?></th>
                            <th><?php __('Status')?></th>
                            <th><?php __('Action')?></th>
                        </tr>
                    </thead>

                    <tbody>

                    </tbody>
                </table>
            <?php else: ?>
                <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
                    <thead>
                        <tr>
                            <th><?php __('Start Time')?></th>
                            <th><?php __('End Time')?></th>
                            <th><?php __('Status')?></th>
                            <th><?php __('Action')?></th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($this->data as $item): ?>
                            <tr>
                                <td><?php echo $item['LcrReport']['start_time']; ?></td>
                                <td><?php echo $item['LcrReport']['end_time']; ?></td>

                                <td><?php echo $item['LcrReport']['status'] == 1 ? 'Done' : 'In progress'; ?></td>
                                <td>
                                    <?php if ($item['LcrReport']['status'] == 1) : ?>
                                    <a title="<?php __('Download')?>" href='<?php echo $this->webroot ?>lcr_report/download/<?php echo $item['LcrReport']['id']; ?>'>
                                            <i class="icon-file-text"></i>
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>

                        <?php endforeach; ?>
                    </tbody>
                </table>
                <div class="bottom row-fluid">
                    <div class="pagination pagination-large pagination-right margin-none">
                        <?php echo $this->element('xpage'); ?>
                    </div> 
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>