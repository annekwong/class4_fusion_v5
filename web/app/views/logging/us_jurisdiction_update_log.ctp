<ul class="breadcrumb">
    <li><?php __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Log', true); ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('US Jurisdiction Update Log') ?></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('US Jurisdiction Update Log', true); ?></h4>
    <div class="buttons pull-right">

    </div>
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>


<div class="innerLR">

    <div class="widget widget-heading-simple widget-body-white">
        <div class="filter-bar">
            <form action="" method="get">
                <div>
                    <label><?php __('Tigger Time') ?>:</label>
                    <input id="start_date" class="input in-text wdate " value="<?php
                    if (isset($get_data['time_start']))
                    {
                        echo $get_data['time_start'];
                    }
                    ?>" type="text" readonly=""  name="time_start">
                    -- 
                    <input id="end_date" class="wdate input in-text" type="text" value="<?php
                    if (isset($get_data['time_end']))
                    {
                        echo $get_data['time_end'];
                    }
                    ?>" readonly=""  name="time_end">
                </div>
                <div>
                    <button name="submit" class="btn query_btn"><?php __('Query') ?></button>
                </div>
                <!-- // Filter END -->


            </form>
        </div>
        <div class="widget-body">
            <div class="clearfix"></div>
            <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
                <thead>
                    <tr>
                        <th><?php echo $appCommon->show_order('JurisdictionUpdateLog.tigger_time', __('Tigger Time', true)) ?></th>
                        <th><?php echo $appCommon->show_order('JurisdictionUpdateLog.is_new_file', __('New File Available', true)) ?></th>
                        <th><?php echo $appCommon->show_order('ImportExportLogs.time', __('Upload Time', true)) ?></th>
                        <th><?php echo $appCommon->show_order('ImportExportLogs.finished_time', __('Finished Time', true)) ?></th>
                        <th><?php echo $appCommon->show_order('ImportExportLogs.success_numbers', __('Record Counts', true)) ?></th>
                        <th><?php __('Status'); ?></th>
                        <th><?php __('Action'); ?></th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($this->data as $item): ?>
                        <tr>
                            <td><?php echo $item['JurisdictionUpdateLog']['tigger_time']; ?></td>
                            <td><?php echo $item['JurisdictionUpdateLog']['is_new_file'] ? 'YES' : 'NO'; ?></td>
                            <td><?php echo $item['ImportExportLogs']['time']; ?></td>
                            <td><?php echo $item['ImportExportLogs']['finished_time']; ?></td>
                            <td><?php echo $item['ImportExportLogs']['success_numbers']; ?></td>
                            <td><?php echo $status[$item['JurisdictionUpdateLog']['status']]; ?></td>
                            <td>
                                <?php if ($item['JurisdictionUpdateLog']['import_log_id']): ?>
                                    <a title="<?php __('download'); ?>" href="<?php echo $this->webroot ?>uploads/download_original_file/<?php echo base64_encode($item['JurisdictionUpdateLog']['import_log_id']) ?>">
                                        <i class="icon-download"></i>
                                    </a>
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
        </div>
    </div>
</div>

<script type="text/javascript">
    $(function () {
        $("#start_date").focus(function () {
            var max_date = $("#end_date").val();
            WdatePicker({dateFmt: 'yyyy-MM-dd HH:mm:ss', maxDate: max_date});
        });
        $("#end_date").focus(function () {
            var min_date = $("#start_date").val();
            WdatePicker({dateFmt: 'yyyy-MM-dd HH:mm:ss', minDate: min_date});
        });
    });
</script>