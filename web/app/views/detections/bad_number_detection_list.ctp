<ul class="breadcrumb">
    <li><?php __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Monitoring') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>detections/bad_number_detection">
        <?php __('Fraud Detection') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>detections/bad_number_detection_log">
        <?php __('Block List') ?></a></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php __('Block List') ?></h4>
    <div class="buttons pull-right">

    </div>
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>
<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-head">
            <?php echo $this->element("detection/bad_number_detection_tab",array('active' => 'log')); ?>
        </div>
        <div class="widget-body">
            <div class="filter-bar">
                <form method="get">
                    <div>
                        <label><?php __('Rule Name') ?></label>
                        <input type="text" name="rule_name" value="<?php echo isset($get_data['rule_name']) ? $get_data['rule_name'] : ""; ?>"/>
                    </div>
                    <div>
                        <label><?php __('Start Time')?>:</label>
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
                    <div>
                        <button name="submit" class="btn query_btn"><?php __('Query')?></button>
                    </div>
                    <!-- // Filter END -->
                </form>
            </div>

            <?php if (empty($this->data)): ?>
                <h2 class="msg center"><br/><?php echo __('no_data_found', true); ?></h2>
                <?php else: ?>
                <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">

                    <thead>
                    <tr>
                        <th><?php __('ANI/DNIS') ?></th>
                        <th><?php __('Number')?></th>
                        <th><?php __('Rule')?></th>
                        <th><?php __('Trunk Name')?></th>
                        <th><?php __('Trunk Group')?></th>
                        <th><?php __('Blocked On')?></th>
                        <th><?php __('Action')?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($this->data as $data_item): ?>
                        <tr>
                            <td><?php echo $data_item['FraudDetection']['rule_name']; ?></td>
                            <td><?php echo $data_item['FraudDetectionLog']['create_on']; ?></td>
                            <td><?php echo $data_item['FraudDetectionLog']['finish_time']; ?></td>
                            <td><?php echo isset($create_by_arr[$data_item['FraudDetectionLog']['create_by']]) ? $create_by_arr[$data_item['FraudDetectionLog']['create_by']] : "--"; ?> </td>
                            <td><?php echo isset($status_arr[$data_item['FraudDetectionLog']['status']]) ? $status_arr[$data_item['FraudDetectionLog']['status']] : "--"; ?> </td>
                            <td>
                                <?php if($data_item['FraudDetectionLog']['status']): ?>
                                <a title="<?php __('Show Detail'); ?>" href="<?php echo $this->webroot; ?>detections/fraud_detection_log_detail/<?php echo base64_encode($data_item['FraudDetectionLog']['id']); ?>">
                                    <i class="icon-list"></i>
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
            <div class="clearfix"></div>
        </div>
    </div>
</div>