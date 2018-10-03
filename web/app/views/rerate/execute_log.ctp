<ul class="breadcrumb">
    <li><?php __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>rerate/create_task">
        <?php echo __('Tool', true); ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>rerate/create_task">
        <?php __('Re-rate'); ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>rerate/execute_log">
        <?php __('Re-rate CDR Download Log'); ?></a></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo $page_name; ?></h4>
    <div class="buttons pull-right">

    </div>
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>


<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-head">
            <?php echo $this->element('re_rate/tabs',array('active' => 'execute_log')); ?>
        </div>
        <div class="widget-body">
            <?php if (!count($this->data)): ?>
                <div class="msg center"><br /><h2><?php echo __('no_data_found', true); ?></h2></div>
            <?php else: ?>
                <div class="overflow_x separator">
                    <table class="footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
                        <thead>
                        <tr>
                            <th><?php __('Job ID'); ?></th>
                            <th><?php __('Execute Type'); ?></th>
                            <th><?php echo $appCommon->show_order('create_on', __('Created On', true)) ?></th>
                            <th><?php echo $appCommon->show_order('create_by', __('Created By', true)) ?></th>
                            <th><?php __('Period'); ?></th>
                            <th><?php __('Process'); ?></th>
                            <th><?php __('Status'); ?></th>
                            <th><?php __('Action'); ?></th>
                        </tr>
                        </thead>

                        <tbody>
                        <?php foreach ($this->data as $item): ?>
                            <tr>
                                <td>#<?php echo $item['RerateReportExecLog']['id']; ?></td>
                                <td><?php echo $exec_type[intval($item['RerateReportExecLog']['exec_type'])]; ?></td>
                                <td><?php echo $item['RerateReportExecLog']['create_on']; ?></td>
                                <td><?php echo $item['RerateReportExecLog']['create_by']; ?></td>
                                <td>
                                    <small>
                                        <?php echo $item['RerateReportExecLog']['start_time']; ?>
                                        <br />
                                        <?php echo $item['RerateReportExecLog']['finish_time']; ?>
                                    </small>
                                </td>
                                <td>
                                    <?php echo intval($item['RerateReportExecLog']['success_files_count']); ?>
                                    &nbsp;&nbsp;/&nbsp;&nbsp;
                                    <?php echo intval($item['RerateReportExecLog']['total_files_count']); ?>
                                </td>
                                <td><?php echo $status[intval($item['RerateReportExecLog']['status'])]; ?></td>
                                <td>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                    <div class="separator"></div>
                </div>
                <div class="row-fluid separator">
                    <div class="pagination pagination-large pagination-right margin-none">
                        <?php echo $this->element('xpage'); ?>
                    </div>
                </div>
            <?php endif; ?>
            <div class="clearfix"></div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(function () {
    });
</script>