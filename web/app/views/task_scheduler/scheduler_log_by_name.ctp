<ul class="breadcrumb">
    <li><?php __('You are here'); ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Log') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Scheduler Log', true); ?></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Scheduler Log', true); ?></h4>
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
        <a class="btn btn-primary btn-icon glyphicons no-js roundabout" href="<?php echo $this->webroot; ?>task_scheduler/scheduler_log?<?php echo $$hel->getParams('getUrl') ?>"><i></i><?php __('General') ?></a>
</div>
<div class="clearfix"></div>
<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="clearfix"></div>

        <div class="widget-body">
            <div class="filter-bar">

                <form action="" method="get">
                    <!-- Filter -->

                    <!-- // Filter END -->
                    <!-- Filter -->

                    <!-- // Filter END -->
                    <div>
                        <label><?php __('Start Time')?>:</label>
                        <input id="start_date" class="input in-text wdate " value="<?php
                        if (isset($get_data['start_time_start']))
                        {
                            echo $get_data['start_time_start'];
                        }
                        ?>" type="text" readonly="" onfocus="WdatePicker({dateFmt: 'yyyy-MM-dd HH:mm:ss'});" name="start_time_start">
                        -- 
                        <input id="end_date" class="wdate input in-text" type="text" value="<?php
                               if (isset($get_data['start_time_end']))
                               {
                                   echo $get_data['start_time_end'];
                               }
                               ?>" readonly="" onfocus="WdatePicker({dateFmt: 'yyyy-MM-dd HH:mm:ss'});" name="start_time_end">
                    </div>
                    <!-- Filter -->

                    <div>
                        <label><?php __('End Time')?>:</label>
                        <input id="start_date" class="input in-text wdate " value="<?php
                               if (isset($get_data['end_time_start']))
                               {
                                   echo $get_data['end_time_start'];
                               }
                               ?>" type="text" readonly="" onfocus="WdatePicker({dateFmt: 'yyyy-MM-dd HH:mm:ss'});" name="end_time_start">
                        -- 
                        <input id="end_date" class="wdate input in-text" type="text" value="<?php
                               if (isset($get_data['end_time_end']))
                               {
                                   echo $get_data['end_time_end'];
                               }
                               ?>" readonly="" onfocus="WdatePicker({dateFmt: 'yyyy-MM-dd HH:mm:ss'});" name="end_time_end">
                    </div>

                    <div>
                        <button name="submit" class="btn query_btn"><?php __('Query')?></button>
                    </div>
                    <!-- // Filter END -->


                </form>
            </div>
<?php
if (count($data) == 0)
{
    ?>
                <br />
                <h2 class="msg center"><?php echo __('no_data_found') ?></h2>
                <table class="list" style="display:none;">
                    <thead>
                        <tr>
                            <th><?php echo $appCommon->show_order('script_name', __('Name', true)) ?></th>
                            <th><?php echo $appCommon->show_order('start_time', __('Start Time', true)) ?></th>
                            <th><?php echo $appCommon->show_order('end_time', __('End Time', true)) ?></th>
                        </tr>
                    </thead>

                    <tbody>

                    </tbody>
                </table>
    <?php
}
else
{
    ?>
                <div class="clearfix"></div>
                <fieldset>
                    <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
                        <thead>
                            <tr>
                                <th><?php __('Name')?></th>
                                <th><?php __('Start Time')?></th>
                                <th><?php __('End Time')?></th>
                            </tr>
                        </thead>

                        <tbody>
    <?php
    foreach ($data as $item)
    {
        ?>
                                <tr>
                                    <td>
                                        <a href="<?php echo $this->webroot; ?>task_scheduler/scheduler_log?<?php echo $$hel->getParams('getUrl') ?>&script_name=<?php echo $item[0]['script_name']; ?>">
                                            <?php echo $item[0]['script_name']; ?>
                                        </a>
                                    </td>
                                    <td><?php echo $item[0]['start_time']; ?></td>
                                    <td><?php echo $item[0]['end_time']; ?></td>
                                </tr>
    <?php } ?>
                        </tbody>
                    </table>
                </fieldset>
<?php } ?>
        </div>
    </div>
</div>
</div>


