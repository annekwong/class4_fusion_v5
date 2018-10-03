<style type="text/css">
    #error_info {
        background:white;width:300px;height:200px;display:none;
        overflow:hide;word-wrap: break-word; padding:20px;
    }
    table.in-date tr td{border-top: 0;}
</style>

<ul class="breadcrumb">
    <li><?php __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?= $this->webroot?>scheduled_report/scheduled_report_log">
        <?php __('Log') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?= $this->webroot?>scheduled_report/scheduled_report_log">
        <?php echo __('Scheduled Report Log') ?></a></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Scheduled Report Log') ?></h4>
    <div class="buttons pull-right">

    </div>
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>

<div class="innerLR">

    <div class="widget widget-heading-simple widget-body-white">
        <div class="filter-bar">
                <form method="get">
                    <!-- Filter -->
                    <div>
                        <label><?php __('Search')?>:</label>
                        <input type="text" name="search" value="<?php echo isset($get_data['search']) ? $get_data['search'] : ""; ?>"  class="in-search default-value input in-text defaultText in-input" >
                    </div>
                    <!-- // Filter END -->
                    <!-- Filter -->
                    <div>
                        <label><?php __('Time')?>:</label>
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
                </form>
            </div>
        <div class="widget-body">
            <div class="clearfix"></div>
            <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
                <thead>
                    <tr>
                        <th><?php echo $appCommon->show_order('report_name', __('Report Name', true)) ?></th>
                        <th><?php echo $appCommon->show_order('execute_time', __('Time', true)) ?></th>
                        <th><?php echo $appCommon->show_order('email_to', __('Email To', true)) ?></th>
                        <th><?php __('File') ?></th>
                        <!--<th><?php __('Action') ?></th>-->
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($this->data as $item): ?>
                        <tr>
                            <td><?php echo $item['ScheduledReportLog']['report_name']; ?></td>
                            <td><?php echo $item['ScheduledReportLog']['execute_time']; ?></td>
                            <td><?php echo $item['ScheduledReportLog']['email_to']; ?></td>
                            <td>
                                <a class="cdr_download_link" href="<?php echo $this->webroot; ?>scheduled_report/download_file/<?php echo base64_encode($item['ScheduledReportLog']['id']); ?>" title="Download">
                                    <i class="icon-download-alt"></i>
                                </a>
                            </td>
                            <!--<td></td>-->
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php if(count($this->data)): ?>
            <div class="row-fluid">
                <div class="pagination pagination-large pagination-right margin-none">
                    <?php echo $this->element('xpage'); ?>
                </div> 
            </div>
            <?php endif;?>
            <div class="clearfix"></div>

        </div>
    </div>
</div>



