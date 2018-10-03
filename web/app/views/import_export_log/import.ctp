
<ul class="breadcrumb">
    <li><?php __('You are here')?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?= $this->webroot?>import_export_log/import"><?php echo __('Log', true); ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?= $this->webroot?>import_export_log/import"><?php echo __('Import Log') ?></a></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Import Log', true); ?></h4>
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
                        <input type="text" name="search" value="" class="in-search default-value input in-text defaultText in-input" >
                    </div>
                    <!-- // Filter END -->
                    <!-- Filter -->
                    <div>
                        <label><?php __('Time')?>:</label>
                        <input class="input in-text wdate " value="<?php
                        if (isset($get_data['time']))
                        {
                            echo $get_data['time'];
                        }
                        ?>" type="text" readonly="" onfocus="WdatePicker({dateFmt: 'yyyy-MM-dd HH:mm:ss'});" name="time">
                        
                    </div>

                    <div>
                        <button name="submit" class="btn query_btn"><?php __('Query')?></button>
                    </div>
                    <!-- // Filter END -->
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
                            <!--<td rowspan='2'>
    <?php echo $appCommon->show_order('id', __('ID', true)) ?>
                            </th>-->
                            <th rowspan='2' class="footable-first-column expand"><?php echo __('User', true); ?></th>
                            <th rowspan='2'><?php echo __('Object', true); ?></th>
                            <th rowspan='2'><?php echo __('status', true); ?></th>

                            <th colspan="3"><?php echo __('Records', true); ?></th>



                            <th rowspan='2'><?php echo __('Method', true); ?></th>
                            <th rowspan='2'>
    <?php echo $appCommon->show_order('time', __('Upload Time', true)) ?>
                            </th>
                            <th rowspan='2'><?php echo $appCommon->show_order('finished_time', __('Finished Time', true)) ?></th><!--
                            <th>Rollback On Error</th>
                            <th>With Headers</th>
                            -->

                            <th rowspan='2'><?php echo __('File Name', true); ?></th>
    <?php if ($_SESSION['role_menu']['Log']['import_export_log:import']['model_x'])
    {
        ?>
                                <th rowspan='2'><?php echo __('Upload File', true); ?></th>

                                <th rowspan='2'><?php echo __('Error File', true); ?></th>
                                <th rowspan='2' data-hide="phone,tablet"  style="display: table-cell;"><?php echo __('DB Error File', true); ?></th>

                                <th rowspan='2' data-hide="phone,tablet"  style="display: table-cell;"><?php echo __('Execute Again', true); ?></th>
    <?php } ?>
                            <th rowspan='2' data-hide="phone,tablet" class="footable-last-column"  style="display: table-cell;"><?php echo __('Action', true); ?></th>
                        </tr>

                        <tr>
                            <th><?php echo __('Succ', true); ?></th>
                            <th><?php echo __('Fail', true); ?></th>
                            <th><?php echo __('Dup', true); ?></th>
                        </tr>
                    </thead>
                    <tbody>
    <?php $m = new ImportExportLog; ?>
    <?php foreach ($logs as $log): ?>
                            <tr id="log_<?php echo $log['ImportExportLog']['id'] ?>">
                                <!--<td><?php echo $log['ImportExportLog']['id'] ?></td>-->
                                <td class="footable-first-column expand"><?php echo $log['User']['name'] ?></td>
                                <td><?php echo $appImportExportLog->format_object($log['ImportExportLog']['obj'], $log['ImportExportLog']['foreign_name']) ?></td>
                                <td><?php echo $appImportExportLog->display_status($log['ImportExportLog']['status'], $log['ImportExportLog']['error_file_path'], $log['ImportExportLog']['db_error_file_path']) ?></td>

                                <td><?php echo $log['ImportExportLog']['success_numbers'] ?></td>
                                <td><?php echo $log['ImportExportLog']['error_row'] ?></td>
                                <td><?php echo $log['ImportExportLog']['duplicate_numbers'] ?></td>


                                <td><?php echo $log['ImportExportLog']['duplicate_type'] ?></td>
                                <td><?php echo $log['ImportExportLog']['time'] ?></td>
                                <td><?php echo $log['ImportExportLog']['finished_time'] ?></td>
                                <!--<td><?php echo array_keys_value($log, 'ImportExportLog.ext_attributes.rollback_on_error') ? 'on' : 'off' ?></td>
                                <td><?php echo array_keys_value($log, 'ImportExportLog.ext_attributes.with_headers') ? 'on' : 'off' ?></td>
                                -->
                                <td>
                                    <?php
                                    $file_name_length = strlen($log['ImportExportLog']['myfile_filename']);
                                    if ($file_name_length == 0)
                                        $file_name_show = "N/A";
                                    elseif($file_name_length > 20)
                                        $file_name_show = substr($log['ImportExportLog']['myfile_filename'],0,20)."...";
                                    else
                                        $file_name_show = $log['ImportExportLog']['myfile_filename'];
                                    ?>
                                    <span class="file_name_tip" title="<?php echo $log['ImportExportLog']['myfile_filename']; ?>"><?php echo $file_name_show; ?></span>
                                </td>
                                <?php if ($_SESSION['role_menu']['Log']['import_export_log:import']['model_x'])
                                {
                                    ?>
                                    <?php if (!empty($log['ImportExportLog']['file_path'])): ?>
                                        <td><a target="_blank" title="<?php echo __('Download', true); ?>" href="<?php echo $this->webroot ?>uploads/download_original_file/<?php echo base64_encode($log['ImportExportLog']['id']) ?>"><i class="icon-download"></i></a></td>
                                    <?php else: ?>
                                        <td></td>
                                    <?php endif; ?>


                                    <?php if ((!empty($log['ImportExportLog']['error_file_path']) && $log['ImportExportLog']['status'] < 0) || $log['ImportExportLog']['error_row']): ?>
                                        <td><a target="_blank" title="<?php echo __('Download', true); ?>" href="<?php echo $this->webroot ?>uploads/download_error_file/<?php echo base64_encode($log['ImportExportLog']['id']) ?>"><i class="icon-download"></i></a></td>
                                    <?php else: ?>
                                        <td></td>
                                    <?php endif; ?>


            <?php if (!empty($log['ImportExportLog']['db_error_file_path'])): ?>
                                        <td data-hide="phone,tablet"  style="display: table-cell;"><a target="_blank" title="<?php echo __('download', true); ?>" href="<?php echo $this->webroot ?>uploads/download_db_error_file/<?php echo base64_encode($log['ImportExportLog']['id']) ?>"><i class="icon-download"></i></a></td>
                                    <?php else: ?>
                                        <td data-hide="phone,tablet"  style="display: table-cell;"></td>
                                    <?php endif; ?>



                                        <?php if (!empty($log['ImportExportLog']['error_file_path']) && !$m->is_processing($log)): ?>
                                        <td data-hide="phone,tablet"  style="display: table-cell;"><a title="<?php echo __('Execute', true); ?>" href="<?php echo $this->webroot ?>uploads/reprocess/<?php echo base64_encode($log['ImportExportLog']['id']) ?>" target="blank"><i class="icon-play"></i></a></td>
                                        <?php else: ?>
                                        <td data-hide="phone,tablet"  style="display: table-cell;"></td>
            <?php endif; ?>
                                    <?php } ?>

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

        $('.file_name_tip').qtip({
            style: {
                classes: 'qtip-shadow qtip-tipsy'
            }
        });

        $(".delete").click(function() {

            var href = $(this).attr('url');
            bootbox.confirm('Are you sure do this?', function(result) {
                if (result)
                {
                    window.location.href = href;
                }

            });


        });


    });

</script>
