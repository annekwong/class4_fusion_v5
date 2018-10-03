<ul class="breadcrumb">
    <li><?php __('You are here'); ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Statistics') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Scheduled Report') ?></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php __('Scheduled Report') ?></h4>
    <div class="buttons pull-right">

    </div>
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>
<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-body">
            <?php
            $count = count($data);
            if (!$count):
                ?>
                <div>
                    <br />
                    <h2 class="msg center"><?php __('no data found'); ?></h2>
                </div>
            <?php else: ?>
                <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary default footable-loaded">
                    <thead>
                        <tr>
                            <th><?php __('Report Name'); ?></th>
                            <th><?php __('Frequency'); ?></th>
                            <th><?php __('Day of Week/Mon'); ?></th>
                            <th><?php __('Time of Day'); ?></th>
                            <th><?php __('Interval(h)'); ?></th>
                            <th><?php __('Action'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($data as $items)
                        {
                            ?>
                            <tr>
                                <td><?php echo $items['ScheduledReport']['report_name'] ?></td>
                                <td><?php echo isset($frequency_type[$items['ScheduledReport']['frequency_type']]) ? $frequency_type[$items['ScheduledReport']['frequency_type']] : ""; ?></td>
                                <td>
                                    <?php
                                    if ($items['ScheduledReport']['frequency_type'] == 2)
                                    {
                                        echo isset($week_arr[$items['ScheduledReport']['day_of_week']]) ? $week_arr[$items['ScheduledReport']['day_of_week']] : "";
                                    }
                                    elseif ($items['ScheduledReport']['frequency_type'] == 3)
                                    {
                                        echo $items['ScheduledReport']['day_of_months'];
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?php
                                    $time_of_day = (int) $items['ScheduledReport']['time_of_day'];
                                    if($time_of_day <  23 &&  $time_of_day >= 0)
                                    {
                                        echo $time_of_day.":00";
                                    }
                                    ?>
                                </td>
                                <td><?php echo $items['ScheduledReport']['interval'] ?></td>
                                <td>
                                    <?php if($items['ScheduledReport']['action']): ?>
                                    <a title="<?php __('Inactive'); ?>" onclick="return myconfirm('<?php __('Inactive'); ?>',this);" href="<?php echo $this->webroot; ?>scheduled_report/disable/<?php echo base64_encode($items['ScheduledReport']['id']) ?>">
                                        <i class="icon-check"></i>
                                    </a>
                                    <?php else: ?>
                                    <a title="<?php __('Active'); ?>"  onclick="return myconfirm('<?php __('Active'); ?>',this);" href="<?php echo $this->webroot; ?>scheduled_report/eable/<?php echo base64_encode($items['ScheduledReport']['id']) ?>">
                                        <i class="icon-unchecked"></i>
                                    </a>
                                    <?php endif; ?>
                                    <a title="<?php __('Delete'); ?>"  onclick="return myconfirm('<?php __('sure to delete'); ?>',this);" href="<?php echo $this->webroot; ?>scheduled_report/delete/<?php echo base64_encode($items['ScheduledReport']['id']) ?>">
                                        <i class="icon-remove"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>



        <div class="clearfix"></div>
    </div>
</div>
</div>
