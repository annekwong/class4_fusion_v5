<script src="<?php echo $this->webroot ?>js/ajaxTable.js" type="text/javascript"></script>

<?php
$mydata = $p->getDataArray();
$loop = count($mydata);
?>

<ul class="breadcrumb">
    <li><?php __('You are here')?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Monitoring') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>dialer_detection/index">
        <?php echo __('Dialer Detection') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>dialer_detection/ani_blocking_log">
        <?php echo __('Ani Blocking Log') ?></a></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Ani Blocking Log') ?></h4>
    <div class="buttons pull-right">

    </div>
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>
<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">

        <div class="widget-head">
            <?php echo $this->element('dialer_detection/tabs'); ?>
        </div>
        <div class="widget-body">
            <div class="filter-bar">
                <form method="get">
                    <div>
                        <label><?php __('Rule Name') ?></label>
                        <select name="rule_name">
                            <?php foreach ($ruleNames as $r) { $r = $r[0]; ?>
                                <option value="<?php echo $r['rule_name']; ?>" 
                                <?php if (isset($get_data['rule_name']) && $get_data['rule_name'] == $r['rule_name']) echo "selected='selected'"; ?> >
                                <?php echo $r['rule_name']; ?>
                                </option>
                            <?php } ?>
                        </select>
                        <!-- <input type="text" name="rule_name" value="<?php echo isset($get_data['rule_name']) ? $get_data['rule_name'] : ""; ?>"/> -->
                    </div>
                    <div>
                        <label><?php __('Type') ?></label>
                        <select name="log_type" style="width: 80px;" >    
                            <option value=""><?php __('All') ?></option>
                            <?php foreach ($type_arr as $type_value => $type_item): ?>
                            <option value="<?php echo $type_value ?>"<?php if(isset($get_data['log_type']) && $get_data['log_type'] === $type_value): ?>selected="selected"<?php endif; ?>><?php echo $type_item ?></option>
                            <?php endforeach; ?>
                        </select>
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

            <?php
            if (empty($mydata))
            {
                ?>
                <h2 class="msg center"><br /><?php echo __('no_data_found', true); ?></h2>
                <?php
            }
            else
            {
                ?>
                <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">

                    <thead>
                        <tr>
                            <th><?php __('Type'); ?></th>
                            <th><?php echo $appCommon->show_order('rule_name', __('Rule Name', true)) ?></th>
                            <th><?php echo $appCommon->show_order('start_time', __('Time', true)) ?></th>
                            <th><?php __('ANI'); ?></th>
                        </tr>

                    </thead>
                    <tbody>

                        <?php
                        foreach ($mydata as $data_item)
                        {
                            ?>
                            <tr>
                                <td><?php echo isset($type_arr[$data_item[0]['type']]) ? $type_arr[$data_item[0]['type']] : $data_item[0]['type']; ?></td>
                                <td>
                                    <?php echo $data_item[0]['rule_name']; ?> 
                                </td>
                                <td><?php echo $data_item[0]['start_time']; ?> </td>
                                <td><?php echo $data_item[0]['ani']; ?> </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <div class="row-fluid">
                    <div class="pagination pagination-large pagination-right margin-none">
                        <?php echo $this->element('page'); ?>
                    </div> 
                </div>
            <?php } ?>



            <div class="clearfix"></div>
        </div>
    </div>
</div>

