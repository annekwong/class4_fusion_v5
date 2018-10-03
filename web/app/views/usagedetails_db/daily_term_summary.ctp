<!--导入所有reoprt页面的input和select样式文件-->
<style>
    #stats-period{display: inline-block}
</style>
<?php echo $this->element('magic_css'); ?>
<ul class="breadcrumb">
    <li><?php echo __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>usagedetails_db/orig_summary_reports">
        <?php __('Statistics') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>usagedetails_db/orig_summary_reports">
        <?php echo __('Usage Detail Report') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>usagedetails_db/daily_term_summary">
        <?php echo __('Daily Termination Report') ?></a></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Daily Termination Report') ?></h4>
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>

<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">

        <div class="widget-head">
            <ul class="tabs">
                <li><a href="<?php echo $this->webroot ?>usagedetails_db/orig_summary_reports"  class="glyphicons left_arrow"><i></i>Origination</a></li>
                <li><a href="<?php echo $this->webroot ?>usagedetails_db/term_summary_reports"  class="glyphicons right_arrow"><i></i>Termination</a>  </li>
                <li><a href="<?php echo $this->webroot ?>usagedetails_db/daily_orig_summary"  class="glyphicons left_arrow"><i></i>Daily Origination</a></li>
                <li class='active'><a href="<?php echo $this->webroot ?>usagedetails_db/daily_term_summary"  class="glyphicons right_arrow"><i></i>Daily Termination</a>  </li>
            </ul>
        </div>
        <div class="widget-body">
            <?php if ($show_nodata): ?>
                <?php echo $this->element('report_db/real_period') ?>
            <?php endif; ?>
            <!-- ****************************************普通输出******************************************* -->
            <div class="table_container">
                <?php if (empty($data)): ?>
                    <?php if ($show_nodata): ?>
                        <h2 class="msg center"><?php echo __('no_data_found') ?></h2>
                    <?php endif; ?>
                <?php else: ?>
                    <?php
                    $days = array();
                    $startdate = strtotime($start);
                    $enddate = strtotime($end);
                    $day = round(($enddate - $startdate) / 3600 / 24);
                    $dt_begin = new DateTime($start);
                    for ($i = 0; $i < $day; $i++)
                    {
                        if ($i > 0)
                        {
                            $dt_begin->modify('+1 days');
                        }
                        array_push($days, $dt_begin->format('Y-m-d'));
                    }
                    ?>
                    <div class="overflow_x">
                    <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
                        <thead>
                            <tr>
                                <th <?php if(count($days) > 0) echo "rowspan='2'" ?>><?php __('Client Name'); ?></th>
                                <?php foreach ($days as $item): ?>
                                    <th colspan="2"><?php echo $item; ?></th>
    <?php endforeach; ?>
                                <th <?php if(count($days) > 0) echo "rowspan='2'" ?>><?php echo __('Total Duration(Min)') ?></th>
                                <th <?php if(count($days) > 0) echo "rowspan='2'" ?>><?php echo __('Total Billable Time(Min)') ?></th>
                            </tr>
                            <?php if(count($days) > 0): ?>
                            <tr>
<!--                                <th>--><?php //__('Client Name'); ?><!--</th>-->
                                <?php foreach ($days as $item): ?>
                                    <th><?php __('Duration') ?></th>
                                    <th><?php __('Billable Time'); ?></th>
    <?php endforeach; ?>
<!--                                <th></th>-->
<!--                                <th></th>-->
                            </tr>
                            <?php endif; ?>
                        </thead>
                        <tbody>
                            <?php foreach ($data as $item): ?>
                                <?php
                                $item_total = 0;
                                $item_total_bill_time = 0;
                                ?>
                                <tr>
                                    <td><?php echo $item[array_rand($item)]['client_name'] ?></td>

                                    <?php
                                    foreach ($days as $day_item)
                                    {
                                        if (array_key_exists($day_item, $item))
                                        {
                                            echo '<td>' . round($item[$day_item]['total_time'] / 60, 2) . '</td>';
                                            echo '<td>' . round($item[$day_item]['bill_time'] / 60, 2) . '</td>';
                                            $item_total += $item[$day_item]['total_time'];
                                            $item_total_bill_time += $item[$day_item]['bill_time'];
                                        }
                                        else
                                        {
                                            echo '<td>0</td>';
                                            echo '<td>0</td>';
                                        }
                                    }
                                    ?>

                                    <td><?php echo round($item_total / 60, 2); ?></td>
                                    <td><?php echo round($item_total_bill_time / 60, 2); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            <tr>
                                <td><?php __('Total(Min)') ?></td>
                                    <?php $total_item_total = 0; ?>
                                    <?php $total_item_total_bill_time = 0; ?>
                                    <?php foreach ($days as $day_item) : ?>
                                    <td>
                                        <?php
                                        $total_item = 0;
                                        $total_item_bill_time = 0;
                                        foreach ($data as $item):
                                            if (array_key_exists($day_item, $item))
                                            {
                                                $total_item += $item[$day_item]['total_time'];
                                                $total_item_bill_time += $item[$day_item]['bill_time'];
                                            }
                                        endforeach;
                                        $total_item_total += $total_item;
                                        $total_item_total_bill_time += $total_item_bill_time;
                                        echo round($total_item / 60, 2);
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        echo round($total_item_bill_time / 60, 2);
                                        ?>
                                    </td>
    <?php endforeach; ?>
                                <td>
    <?php echo round($total_item_total / 60, 2); ?>
                                </td>
                                <td>
                    <?php echo round($total_item_total_bill_time / 60, 2); ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                        <?php endif; ?>

                <fieldset class="query-box" style=" clear:both;overflow:hidden;margin-top:10px;">
                    <h4 class="heading glyphicons search"><i></i> <?php __('search') ?></h4>
                        <?php echo $this->element('search_report/search_js'); ?><?php echo $form->create('Cdr', array('type' => 'get', 'url' => '/usagedetails_db/daily_term_summary/', 'onsubmit' => "if ($('#query-output').val() == 'web') loading();")); ?>  <?php echo $this->element('search_report/search_hide_input'); ?>

                    <table class="form" style="width:100%">
<?php echo $this->element('report_db/form_period', array('group_time' => true, 'gettype' => '<select style="width:120px;" name="show_type">
            <option value="0">Web</option>
            <option value="1">CSV</option>
            <option value="2">XLS</option>
        </select>')) ?>
                    </table>
                </fieldset>

<?php echo $this->element('search_report/search_js_show'); ?> 
            </div>
        </div>

        