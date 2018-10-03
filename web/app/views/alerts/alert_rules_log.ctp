<ul class="breadcrumb">
    <li><?php __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Monitoring') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Rule') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Alert Rules Log') ?></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Rule') ?></h4>

</div>
<div class="separator bottom"></div>
<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-head">

            <ul class="tabs">
                <li>
                    <a class="glyphicons no-js paperclip" href="<?php echo $this->webroot; ?>alerts/rules">
                        <i></i><?php __('Rule') ?>
                    </a>
                </li>
                <!--<li>
                    <a class="glyphicons no-js tint" href="<?php /*echo $this->webroot; */?>alerts/block_log">
                        <i></i><?php /*__('Block') */?>
                    </a>
                </li>
                <li>
                    <a class="glyphicons no-js vector_path_all" href="<?php /*echo $this->webroot; */?>alerts/block_trouble_ticket">
                        <i></i><?php /*__('Trouble Tickets') */?>
                    </a>
                </li>-->
                <li class="active">
                    <a class="glyphicons book_open" href="<?php echo $this->webroot; ?>alerts/alert_rules_log">
                        <i></i><?php __('Alert Rules Log') ?>
                    </a>
                </li>
            </ul>
        </div>
        <div class="widget-body">
            <div class="filter-bar">
                <form method="get">
                    <div>
                        <label><?php __('Rule Name') ?></label>
                        <select name="rule_name">
                            <?php if (isset($ruleNames) && !empty($ruleNames)) : ?>
                                <option value=""><?php echo __('All', true);?></option>
                                <?php foreach ($ruleNames as $rn) : ?>
                                    <option value="<?= $rn['rule_name'] ?>"
                                        <?php if (isset($_GET['rule_name']) && $_GET['rule_name'] == $rn['rule_name']) echo "selected"; ?> 
                                    ><?= $rn['rule_name'] ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                        <!-- <input type="text" name="rule_name" value="<?php echo isset($_GET['rule_name']) ? $_GET['rule_name'] : ""; ?>"/> -->
                    </div>
                    <div>
                        <label><?php __('Start Time')?>:</label>
                        <input id="start_time" class="input in-text wdate " value="<?php
                        if (isset($start_time))
                        {
                            echo $start_time;
                        }
                        ?>" type="text" readonly="" onfocus="WdatePicker({dateFmt: 'yyyy-MM-dd HH:mm:ss'});" name="start_time">
                        --
                        <input id="end_time" class="wdate input in-text" type="text" value="<?php
                        if (isset($end_time))
                        {
                            echo $end_time;
                        }
                        ?>" readonly="" onfocus="WdatePicker({dateFmt: 'yyyy-MM-dd HH:mm:ss'});" name="end_time">
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
                        <th><?php __('Rule Name') ?></th>
                        <th><?php echo $appCommon->show_order('create_on', __('Start Time', true)) ?></th>
                        <th><?php echo $appCommon->show_order('finish_time', __('Finish Time', true)) ?></th>
<!--                        <th>--><?php //__('Create by')?><!--</th>-->
                        <th><?php __('Status')?></th>
                        <th><?php __('Action')?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($this->data as $data_item): ?>
                        <tr>
                            <td><?php echo $data_item['AlertRules']['rule_name']; ?></td>
                            <td><?php echo $data_item[0]['create_on']; ?></td>
                            <td><?php echo $data_item[0]['finish_time']; ?></td>
<!--                            <td>--><?php //echo isset($create_by_arr[$data_item['FraudDetectionLog']['create_by']]) ? $create_by_arr[$data_item['FraudDetectionLog']['create_by']] : "--"; ?><!-- </td>-->
                            <td><?php echo $data_item[0]['status'] ? "Over Limit" : "Normal"; ?> </td>
                            <td>
                                <?php if($data_item[0]['status']): ?>
                                <a title="<?php __('Show Detail'); ?>" href="<?php echo $this->webroot; ?>alerts/alert_rules_log_detail/<?php echo base64_encode($data_item[0]['id']); ?>">
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