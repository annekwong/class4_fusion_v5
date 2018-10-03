<script src="<?php echo $this->webroot ?>js/ajaxTable.js" type="text/javascript"></script>

<ul class="breadcrumb">
    <li><?php __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?= $this->webroot?>systemparams/ftp_log"><?php __('Log') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?= $this->webroot?>systemparams/ftp_log"><?php echo __('Auto FTP Log') ?></a></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Auto FTP Log') ?></h4>

    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>

<div class="innerLR">

    <div class="widget widget-tabs widget-heading-simple widget-body-white">

        <div class="widget-head">
            <ul>
                <li><a class="glyphicons right_arrow" href="###" onclick="javascript:(window.location.href= $(this).attr('url'));" url="/systemparams/ftp_log"><i></i> Manual Log</a></li>
                <li class="active"><a class="glyphicons left_arrow" href="###" onclick="javascript:(window.location.href= $(this).attr('url'));" url="/systemparams/ftp_cdr_log"><i></i> Auto Log</a></li>
            </ul>
        </div>

        <div class="widget-body">
            <div class="filter-bar">
                <form method="get">
                    <!-- Filter -->
                    <div>
                        <label><?php __('FTP Start Time') ?>:</label>
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
            <?php
            if (empty($this->data)):
                ?>
                <h2 class="msg center"><br /><?php echo __('no_data_found', true); ?></h2>
            <?php else: ?>
                <div class="clearfix"></div>
                <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
                    <thead>
                    <tr>
                        <th colspan="2"><?php __('FTP Time') ?></th>
                        <th colspan="2"><?php __('Contains Data') ?></th>
                        <th rowspan="2"><?php __('Status') ?></th>
                    </tr>
                    <tr>
                        <th><?php echo $appCommon->show_order('FtpCdrLog.ftp_start_time', __('Start', true)) ?></th>
                        <th><?php echo $appCommon->show_order('FtpCdrLog.ftp_end_time', __('End', true)) ?></th>
                        <th><?php echo $appCommon->show_order('FtpCdrLog.cdr_start_time', __('Start', true)) ?></th>
                        <th><?php echo $appCommon->show_order('FtpCdrLog.cdr_end_time', __('End', true)) ?></th>
                    </tr>
                    </thead>
                    <?php
                    $count = count($this->data);
                    for ($i = 0; $i < $count; $i++):
                        ?>
                        <tbody id="resInfo<?php echo $i ?>">
                        <tr class="row-<?php echo $i % 2 + 1; ?>">
                            <td><?php echo $this->data[$i]['FtpCdrLog']['ftp_start_time']; ?></td>
                            <td><?php echo $this->data[$i]['FtpCdrLog']['ftp_end_time']; ?></td>
                            <td><?php echo $this->data[$i]['FtpCdrLog']['cdr_start_time']; ?></td>
                            <td><?php echo $this->data[$i]['FtpCdrLog']['cdr_end_time']; ?></td>
                            <td><?php echo $status[$this->data[$i]['FtpCdrLog']['status']]; ?></td>
                        </tr>
                        </tbody>
                    <?php endfor; ?>
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