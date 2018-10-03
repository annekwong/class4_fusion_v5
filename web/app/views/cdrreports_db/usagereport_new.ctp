<ul class="breadcrumb">
    <li><?php echo __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>reports_db/usagereport">
            <?php __('Real Time Statistics') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot . $this->params['url']['url'] ?>">
            <?php echo __('Usage Report New') ?></a></li>
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
                    <a href="<?php echo $this->webroot; ?>cdrreports_db/usagereport_new/1" class="glyphicons left_arrow">
                        <i></i>
                        <?php __('Origination')?>
                    </a>
                </li>
                <?php
                if($res[0][0]['all_termination'] == 't'){
                    ?>
                    <li <?php if ($type == 2) echo 'class="active"'; ?>>
                        <a href="<?php echo $this->webroot; ?>cdrreports_db/usagereport_new/2"  class="glyphicons right_arrow">
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
                        <th><?php __('Total Calls'); ?></th>
                        <th>Percentage of calls (%)</th>
                        <th><?php __('Duration(min)') ?></th>
                        <th>Percent of duration (%)</th>
                    </tr>
                    </thead>
                    <tbody>
                     <tr>
                         <td><?php echo $data['count']; ?></td>
                         <td><?php echo $data['count'] == 0 ? 0 : round($data['count'] / $data['count'] * 100, 2); ?></td>
                         <td><?php echo $data['duration'] ? round($data['duration'] / 60, 2) : 0; ?></td>
                         <td><?php echo $data['duration'] == 0 ? 0 : round($data['duration'] / $data['duration'] * 100, 2); ?></td>
                     </tr>
                    </tbody>
                </table>
            <?php endif; ?>
            <?php echo $this->element('report_db/query_box_newcdr', array('fields' => false)); ?>
<!--            --><?php //echo $form->create('Cdr', array('type' => 'get', 'url' => "/reports_db/usagereport/{$type}", 'onsubmit' => "if($('select[name=show_type]').val() == 0) loading();")); ?>
<!--            --><?php //echo $this->element('report_db/cdr_report/select_fieldset'); ?>
<!--            --><?php //echo $form->end(); ?>

        </div>
    </div>
</div>