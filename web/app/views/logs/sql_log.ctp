<ul class="breadcrumb">
    <li><?php __('You are here')?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?= $this->webroot?>logs/sql_log"><?php __('Log') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?= $this->webroot?>logs/sql_log"><?php  __('SQL Log') ?></a></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php  __('SQL Log') ?></h4>
    <div class="buttons pull-right">
    </div>
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>
<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-head">
            <ul>
                <li class="active">
                    <a class="glyphicons list" href="<?php echo $this->webroot; ?>logs/sql_log">
                        <i></i>
                        <?php __('List'); ?>
                    </a>
                </li>
                <li>
                    <a class="glyphicons book_open" href="<?php echo $this->webroot; ?>logs/kill_log">
                        <i></i>
                        <?php __('Killed Log'); ?>
                    </a>
                </li>
            </ul>
        </div>
        <div class="widget-body">

            <div class="filter-bar">

                <form action="" method="get">

                    <div>
                        <label><?php __('Time')?>:</label>
                        <input id="start_date" class="input in-text wdate " value="<?php
                        if (isset($get_data['time_start']))
                        {
                            echo $get_data['time_start'];
                        }
                        ?>" type="text" readonly="" onfocus="WdatePicker({dateFmt: 'yyyy-MM-dd HH:mm:ss'});" name="time_start">
                        -- 
                        <input id="end_date" class="wdate input in-text" type="text" value="<?php
                               if (isset($get_data['time_end']))
                               {
                                   echo $get_data['time_end'];
                               }
                               ?>" readonly="" onfocus="WdatePicker({dateFmt: 'yyyy-MM-dd HH:mm:ss'});" name="time_end">
                    </div>
                    <!-- Filter -->



                    <div>
                        <button name="submit" class="btn query_btn"><?php __('Query')?></button>
                    </div>
                    <!-- // Filter END -->


                </form>
            </div>
            <div style="overflow-x: auto;width:100%">
            <table class="list footable table table-striped tableTools table-bordered  table-white table-primary default footable-loaded">
                <thead>
                    <tr>
                        <th><?php echo $appCommon->show_order('pid', __('Pid', true)) ?></th>
                        <th><?php echo $appCommon->show_order('query', __('SQL', true)) ?></th>
                        <th><?php echo $appCommon->show_order('query_start', __('Start Time', true)) ?></th>
                        <th><?php __('Action')?></th>
                    </tr>
                </thead>
                <tbody>
<?php
foreach ($sql_data as $items)
{
    ?>
                        <tr>
                            <td><?php echo $items[0]['pid'] ?></td>
                            <td style="word-break: break-all; word-wrap:break-word;">
                                <a href="javascript:void(0)" data-layout="center" data-type="primary" data-toggle="notyfy" data-value="<?php echo $items[0]['query'] ?>"><?php echo $appCommon->sub_string($items[0]['query'],100); ?></a>
                            </td>
                            <td><?php echo $items[0]['query_start'] ?></td>
                            <td>
                                <a title="<?php __('Kill Job')?>" onclick="return myconfirm('Are you sure to kill the job?', this);" href="<?php echo $this->webroot; ?>logs/kill_job/<?php echo base64_encode($items[0]['pid']); ?>"><i class="icon-remove"></i></a>
                            </td>
                        </tr>
<?php } ?>
                </tbody>
            </table>
            </div>
            </div>



        <div class="clearfix"></div>
    </div>
</div>
</div>
<script type="text/javascript" src="<?php echo $this->webroot ?>js/custom_notyfy.js"></script>