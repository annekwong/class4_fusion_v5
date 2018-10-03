<ul class="breadcrumb">
    <li><?php echo __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>reports_db/usagereport">
        <?php __('Statistics') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot . $this->params['url']['url'] ?>">
        <?php echo __('Usage Report') ?></a></li>
</ul>
<?php

$user_id = $_SESSION['sst_user_id'];
$res = $cdr_db->query("select * from users where user_id = {$user_id} ");

?>
<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Usage Report') ?></h4>
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>

<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-head">
            <ul class="tabs">
                <li <?php if ($type == 1) echo 'class="active"'; ?>>
                    <a href="<?php echo $this->webroot; ?>reports_db/usagereport/1" class="glyphicons left_arrow">
                        <i></i>
                        <?php __('Origination')?>
                    </a>
                </li>
                <?php
                if($res[0][0]['all_termination'] == 't'){
                    ?>
                    <li <?php if ($type == 2) echo 'class="active"'; ?>>
                        <a href="<?php echo $this->webroot; ?>reports_db/usagereport/2"  class="glyphicons right_arrow">
                            <i></i>
                            <?php __('Termination')?>
                        </a>
                    </li>
                    <?php
                }
                ?>
            </ul>
        </div>
        <div class="widget-body">

            <?php if ($show_nodata): ?><h1 style="font-size:14px;"><?php __('Report Period')?> <?php echo $start_date ?> — <?php echo $end_date ?></h1><?php endif; ?>
            <?php if (empty($data)): ?>
                <?php if ($show_nodata): ?><div class="msg"><?php  echo __('no_data_found') ?></div><?php endif; ?>
            <?php else: ?>
                <table class="list footable table dynamicTable table-striped tableTools table-bordered  table-white table-primary">
                    <thead>
                    <tr>
                        <?php foreach ($show_fields as $field): ?>
                            <th><?php echo $replace_fields[$field]; ?></th>
                        <?php endforeach; ?>
                        <th><?php __('Total Calls'); ?></th>
                        <th>Percentage of calls (%)</th>
                        <th><?php __('Duration(min)') ?></th>
                        <th>Percent of duration (%)</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $total_cdr = 0;
                    $total_duration = 0;
//                    die(var_dump($data));
                    foreach ($data as $item)
                    {
                        $total_cdr += $item[0]['cdr_count'];
                        $total_duration += $item[0]['duration'];
                    }
                    ?>
                    <?php foreach ($data as $item): ?>
                        <?php if (isset($item[0]['cdr_count']) && isset($item[0]['duration'])): ?>
                            <tr>
                                <?php foreach (array_keys($show_fields) as $key): ?>
                                    <td style="color:#6694E3;"><?php echo $item[0][$key]; ?></td>
                                <?php endforeach; ?>
                                <td><?php echo $item[0]['cdr_count'] ?></td>
                                <td>
                                    <div class="bar">
                                        <?php $cdr_per = $total_cdr == 0 ? 0 : round($item[0]['cdr_count'] / $total_cdr * 100, 2) ?>
                                        <!--<div style="font-size:1.2em;text-align:center;width: <?php echo $cdr_per; ?>%;">-->
                                        <div>
                                            <?php echo $cdr_per; ?>%&nbsp;
                                        </div>
                                    </div>
                                </td>
                                <td><?php echo round($item[0]['duration'] / 60, 2) ?></td>
                                <td>
                                    <div class="bar">
                                        <?php $dur_per = $total_duration == 0 ? 0 : round($item[0]['duration'] / $total_duration * 100, 2) ?>
                                        <!--<div style="font-size:1.2em;text-align:center;width: <?php echo $dur_per; ?>%;">-->
                                        <div>
                                            <?php echo $dur_per; ?>%&nbsp;
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    <?php endforeach; ?>
                    </tbody>
                    <?php
                    $count_group = count($show_fields);
                    if ($count_group && count($data)):
                        ?>
                        <tfoot>
                        <tr style="color:#000;">

                            <td><?php echo __('Total') ?>:</td>
                            <?php for ($i = 0; $i < count($show_fields) -1; $i++): ?>
                                <th>&nbsp;</th>
                            <?php endfor; ?>
                            <td><?php echo $total_cdr ?></td>
                            <td>
                            </td>
                            <td><?php echo round($total_duration / 60, 2) ?></td>
                            <td>
                            </td>
                        </tr>
                        </tfoot>
                        <?php
                    endif;
                    ?>

                </table>
            <?php endif; ?>
            <?php echo $form->create('Cdr', array('type' => 'get', 'url' => "/reports_db/usagereport/{$type}", 'onsubmit' => "if($('select[name=show_type]').val() == 0) loading();")); ?>
            <?php echo $this->element('report_db/cdr_report/select_fieldset'); ?>
            <?php echo $form->end(); ?>

        </div>
    </div>
</div>