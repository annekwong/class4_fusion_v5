<ul class="breadcrumb">
    <li><?php __('You are here')?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Log') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Killed Log') ?></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php __('Killed Log') ?></h4>
    <div class="buttons pull-right">

    </div>
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>
<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-head">
            <ul>
                <li>
                    <a class="glyphicons list" href="<?php echo $this->webroot; ?>logs/sql_log">
                        <i></i>
                        <?php __('List'); ?>
                    </a>
                </li>
                <li class="active">
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
                        <label><?php __('Killed Time'); ?></label>
                        <input id="k_start_datetime" class="input in-text wdate " value="<?php
                        if (isset($get_data['k_start_datetime']))
                        {
                            echo $get_data['k_start_datetime'];
                        }
                        ?>" type="text" readonly="" onfocus="WdatePicker({dateFmt: 'yyyy-MM-dd HH:mm:ss'});" name="k_start_datetime">
                        -- 
                        <input id="k_end_datetime" class="wdate input in-text" type="text" value="<?php
                        if (isset($get_data['k_end_datetime']))
                        {
                            echo $get_data['k_end_datetime'];
                        }
                        ?>" readonly="" onfocus="WdatePicker({dateFmt: 'yyyy-MM-dd HH:mm:ss'});" name="k_end_datetime">
                    </div>

                    <div>
                        <label><?php __('Start Time'); ?></label>
                        <input id="start_datetime" class="input in-text wdate " value="<?php
                        if (isset($get_data['start_datetime']))
                        {
                            echo $get_data['start_datetime'];
                        }
                        ?>" type="text" readonly="" onfocus="WdatePicker({dateFmt: 'yyyy-MM-dd HH:mm:ss'});" name="start_datetime">
                        -- 
                        <input id="end_datetime" class="wdate input in-text" type="text" value="<?php
                        if (isset($get_data['end_datetime']))
                        {
                            echo $get_data['end_datetime'];
                        }
                        ?>" readonly="" onfocus="WdatePicker({dateFmt: 'yyyy-MM-dd HH:mm:ss'});" name="end_datetime">
                    </div>
                    <!-- Filter -->



                    <div>
                        <button name="submit" class="btn query_btn"><?php __('Query')?></button>
                    </div>
                    <!-- // Filter END -->


                </form>
            </div>
            <?php
            $count = count($this->data);
            if (!$count):
                ?>
                <div>
                    <br />
                    <h2 class="msg center"><?php __('no_data_found'); ?></h2>
                </div>
            <?php else: ?>
                <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary default footable-loaded">
                    <thead>
                        <tr>
                            <th><?php __('SQL'); ?></th>
                            <th><?php echo $appCommon->show_order('start_time', __('Start Time', true)) ?></th>
                            <th><?php echo $appCommon->show_order('kill_time', __('Killed Time', true)) ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($this->data as $items)
                        {
                            ?>
                            <tr>

                                <td style="word-break: break-all; word-wrap:break-word;">
                                    <a href="javascript:void(0)" data-layout="center" data-type="primary" data-toggle="notyfy"
                                       data-value="<?php echo $items['KillPgSqlLog']['query'] ?>"><?php echo $appCommon->sub_string($items['KillPgSqlLog']['query'],100); ?></a></td>
                                <td><?php echo $items['KillPgSqlLog']['start_time'] ?></td>
                                <td><?php echo $items['KillPgSqlLog']['kill_time'] ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
                    <div class="pagination pagination-large pagination-right margin-none">
                        <?php echo $this->element('xpage'); ?>
                    </div> 
            <?php endif; ?>
        </div>



        <div class="clearfix"></div>
    </div>
</div>
</div>
<script type="text/javascript" src="<?php echo $this->webroot ?>js/custom_notyfy.js"></script>
