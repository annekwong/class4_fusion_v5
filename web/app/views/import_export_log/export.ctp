<ul class="breadcrumb">
    <li><?php __('You are here')?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?= $this->webroot?>import_export_log/export"><?php echo __('Log', true); ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?= $this->webroot?>import_export_log/export"><?php echo __('Export Log') ?></a></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Export Log', true); ?></h4>
    <div class="buttons pull-right">

    </div>
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>

<div class="innerLR">

    <div class="widget widget-heading-simple widget-body-white">
        <div class="widget-body">
            <div class="filter-bar">
                <form method="get">
                    <!-- Filter -->
                    <div>
                        <label><?php __('Search')?>:</label>
                        <input type="text" name="search" value=""  class="in-search default-value input in-text defaultText in-input" >
                    </div>
                    <!-- // Filter END -->
                    <!-- Filter -->
                    <div>
                        <label><?php __('Download Time')?>:</label>
                        <input id="start_date" class="input in-text wdate " value="<?php if (isset($get_data['time_start']))
{
    echo $get_data['time_start'];
} ?>" type="text" readonly="" onfocus="WdatePicker({dateFmt: 'yyyy-MM-dd HH:mm:ss'});" name="time_start">
                        -- 
                        <input id="end_date" class="wdate input in-text" type="text" value="<?php if (isset($get_data['time_end']))
{
    echo $get_data['time_end'];
} ?>" readonly="" onfocus="WdatePicker({dateFmt: 'yyyy-MM-dd HH:mm:ss'});" name="time_end">
                    </div>


                    <div>
                        <button name="submit" class="btn query_btn"><?php __('Query')?></button>
                    </div>
                    <!-- // Filter END -->
                    <div>
                        <label><?php __('Refresh Every')?>:</label>
                        <select id="changetime" class="input in-select select">
                            <option value="180">3 <?php __('minutes')?></option>
                            <option value="300">5 <?php __('minutes')?></option>
                            <option value="800">15 <?php __('minutes')?>    </option>
                        </select>
                    </div>
                </form>
            </div>



<?php if (empty($logs)): ?>
    <?php echo $this->element('common/no_result') ?>
<?php else: ?>
                <div class="clearfix"></div>
            <div class="overflow_x">
                <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
                    <thead>
                        <tr>
                            <th><?php echo $appCommon->show_order('id', __('ID',true)); ?></th>
                            <th><?php echo __('User', true); ?></th>
                            <th><?php echo __('Object', true); ?></th>
                            <th><?php echo __('status', true); ?></th>
                            <th><?php echo $appCommon->show_order('time', __('Download Time',true)) ?></th>
                            <th><?php echo __('File', true); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                                <?php $m = new ImportExportLog; ?>
                                <?php foreach ($logs as $log): ?>
                            <tr id="log_<?php echo $log['ImportExportLog']['id'] ?>">
                                <td><?php echo $log['ImportExportLog']['id'] ?></td>
                                <td><?php echo $log['User']['name'] ?></td>

                                <td><?php echo $appImportExportLog->format_object($log['ImportExportLog']['obj'], $log['ImportExportLog']['foreign_name'])
                            // $log['ImportExportLog']['obj']
                                    ?>
                                </td>
                                <td><?php echo $appImportExportLog->display_export_status($log['ImportExportLog']['status'], $log['ImportExportLog']['error_file_path'], $log['ImportExportLog']['db_error_file_path']) ?></td>
                                <td><?php echo $log['ImportExportLog']['time'] ?></td>
        <?php if (file_exists($dbpath . DS . $log['ImportExportLog']['file_path'])): ?>
                                    <td>
                                        <a title="<?php __('Download')?>" href="<?php echo $this->webroot ?>cdrreports/get_file/<?php echo base64_encode($dbpath . DS . $log['ImportExportLog']['file_path']) ?>">
                                            <i class="icon-download"></i>
                                        </a>
                                    </td>
        <?php else: ?>
                                    <td>-</td>
                            <?php endif; ?>
                            </tr>
    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
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
<script type="text/javascript">
    $(function() {
        $(".fakeloader").remove();

        var interv = null;

        $('#changetime').change(function() {
            if (interv)
                window.clearInterval(interv);
            var time = $(this).val() * 1000;
            interv = window.setInterval("loading();window.location.reload()", time);
        });

        $('#changetime').change();

    });
</script>