<div class="widget widget-tabs widget-body-white">
    <div class="widget-head ">
        <?php echo $this->element('us_reports/tab', array('active' => $active)) ?>
    </div>
    <div class="separator bottom"></div>
    <?php echo $this->element('us_reports/select_conditions', array('function' => 'add_export_log/'.$active)) ?>
    <div class="widget-body">


        <div class="filter-bar">
            <form method="get">
                <div>
                    <label><?php __('Tiggered Time') ?>:</label>
                    <input id="start_date" class="input in-text wdate " value="<?php
                    if (isset($get_data['time']))
                    {
                        echo $get_data['time'];
                    }
                    ?>" type="text" readonly="" onfocus="WdatePicker({dateFmt: 'yyyy-MM-dd HH:mm:ss'});" name="time">
                    ~
                    <input id="end_date" class="input in-text wdate " value="<?php
                    if (isset($get_data['end_time']))
                    {
                        echo $get_data['end_time'];
                    }
                    ?>" type="text" readonly="" onfocus="WdatePicker({dateFmt: 'yyyy-MM-dd HH:mm:ss'});" name="end_time">

                </div>


                <div>
                    <button name="submit" class="btn query_btn"><?php __('Query') ?></button>
                </div>
                <!-- // Filter END -->

            </form>
        </div>

        <?php if (empty($this->data)): ?>
            <div class="msg center">
                <br />
                <h2><?php echo __('no_data_found') ?></h2></div>
        <?php else: ?>

            <table  class="scroll_table list nowrap with-fields footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
                <thead>
                <tr>
                    <th><?php __('Job ID') ?></th>
                    <th><?php __('Tiggered Time') ?></th>
                    <?php if(!in_array($active,array('far','flr'))): ?>
                        <th><?php __('Rate Table') ?></th>
                    <?php endif; ?>
                    <th><?php __('Routing Plan') ?></th>
                    <?php if(isset($has_trunk)): ?>
                        <th><?php __('Egress Trunk') ?></th>
                    <?php endif; ?>
                    <?php if(in_array($active,array('far','flr'))): ?>
                        <th><?php __('Ingress Trunk') ?></th>
                    <?php endif; ?>
                    <th><?php __('Start Time') ?></th>
                    <th><?php __('End Time') ?></th>
                    <th><?php __('Status') ?></th>
                    <?php if(!isset($has_trunk)): ?>
                        <th><?php __('Number of Rows') ?></th>
                    <?php endif; ?>
                    <th><?php __('File Size') ?></th>
                    <th><?php __('Action') ?></th>
                </tr>
                </thead>

                <tbody>
                <?php foreach ($this->data as $item): ?>
                    <tr>
                        <td>#<?php echo $item['UsExportLog']['pid']; ?></td>
                        <td id="bbb"><?php echo $item['UsExportLog']['create_time']; ?></td>
                        <?php if(!in_array($active,array('far','flr'))): ?>
                            <td><?php echo $item['RateTable']['name']; ?></td>
                        <?php endif; ?>
                        <td><?php echo $item['RoutePlan']['name']; ?></td>
                        <?php if(isset($has_trunk)): ?>
                            <td><?php echo $item['Resource']['alias']; ?></td>
                        <?php endif; ?>
                        <?php if(in_array($active,array('far','flr'))): ?>
                            <td><?php echo $item['Resource']['alias']; ?></td>
                        <?php endif; ?>
                        <td><?php echo $item['UsExportLog']['start_time']; ?></td>
                        <td><?php echo $item['UsExportLog']['end_time']; ?></td>
                        <td>
                            <?php echo $status[$item['UsExportLog']['status']]; ?>
                        </td>
                        <?php if(!isset($has_trunk)): ?>
                            <td><?php echo $item['UsExportLog']['num_of_row']; ?></td>
                        <?php endif; ?>
                        <td>
                            <?php if ($item['UsExportLog']['status'] == 4): ?>
                                <?php
                                echo $appCommon->to_readable_size(@filesize(realpath(ROOT . '/../download/us_report_download/' . $item['UsExportLog']['file_name'] . '.bz2')));
                                ?>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($item['UsExportLog']['status'] == 4): ?>
                                <a title="Download" target="_blank" href="<?php echo $this->webroot ?>us_domestic_traffic/export_log_down?key=<?php echo base64_encode($item['UsExportLog']['id']); ?>">
                                    <i class="icon-download-alt"></i>
                                </a>
                            <?php elseif($item['UsExportLog']['status'] < 0): ?>
                                <a target="_blank" title="Error File"  href="<?php echo $this->webroot ?>us_domestic_traffic/export_download_error?key=<?php echo base64_encode($item['UsExportLog']['id']); ?>">
                                    <i class="icon-download"></i>
                                </a>
                            <?php else: ?>
                                <a title="Kill Job" href="<?php echo $this->webroot ?>us_domestic_traffic/export_log_kill?key=<?php echo base64_encode($item['UsExportLog']['id']); ?>">
                                    <i class="icon-remove-circle"></i>
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
        <?php endif; ?>
    </div>
</div>