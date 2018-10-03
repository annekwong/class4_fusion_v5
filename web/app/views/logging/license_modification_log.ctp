<ul class="breadcrumb">
    <li><?php __('You are here'); ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?= $this->webroot?>logging/license_modification_log"><?php __('Log') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?= $this->webroot?>logging/license_modification_log"><?php echo __('License Modification Log', true); ?></a></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('License Modification Log', true); ?></h4>
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>
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
                        <label><?php __('Time')?>:</label>
                        <input id="start_date" class="input in-text wdate " value="<?php if (isset($get_data['time_start']))
{
    echo $get_data['time_start'];
} ?>" type="text" readonly="" onfocus="WdatePicker({dateFmt: 'yyyy-MM-dd HH:mm:ss'});" name="time_start">
                        -- 
                        <input id="end_date" class="wdate input in-text" type="text" value="<?php if (isset($get_data['time_end']))
{
    echo $get_data['time_end'];
} ?>" readonly="" onfocus="WdatePicker({dateFmt: 'yyyy-MM-dd HH:mm:ss'});" name="time_end">
                    </div>
                    <!-- Filter -->

                    <div>
                        <label><?php __('Type')?>:</label>
                        <select  name="status" class="in-select select" >
                            <option value=""></option>
                            <?php foreach ($type_arr as $key=> $value): ?>
                            <option value="<?php echo $key; ?>" <?php if(isset($get_data['status']) && !strcmp($get_data['status'], $key)): ?>selected="selected"<?php endif; ?>>
                                <?php echo $value; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div>
                        <button name="submit" class="btn query_btn"><?php __('Query')?></button>
                    </div>
                    <!-- // Filter END -->


                </form>
            </div>
<?php
$data = $p->getDataArray();
?>
<?php if (count($data) == 0)
{
    ?>
                <br />
                <h2 class="msg center"><?php echo __('no_data_found') ?></h2>
                <table class="list" style="display:none;">
                    <thead>
                        <tr>
                            <th><?php echo $appCommon->show_order('modify_on', __('Modify on', true)) ?></th>
                            <th><?php echo $appCommon->show_order('switch_name', __('Switch Name', true)) ?></th>
                            <th><?php echo $appCommon->show_order('old_value', __('Old Value', true)) ?></th>
                            <th><?php echo $appCommon->show_order('new_value', __('New Value', true)) ?></th>
                            <th><?php echo $appCommon->show_order('modify_by', __('Modify by', true)) ?></th>
                            <th><?php echo $appCommon->show_order('type', __('Type', true)) ?></th>
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
                                <th><?php echo $appCommon->show_order('modify_on', __('Modify on', true)) ?></th>
                                <th><?php echo $appCommon->show_order('switch_name', __('Switch Name', true)) ?></th>
                                <th><?php echo $appCommon->show_order('old_value', __('Old Value', true)) ?></th>
                                <th><?php echo $appCommon->show_order('new_value', __('New Value', true)) ?></th>
                                <th><?php echo $appCommon->show_order('modify_by', __('Modify by', true)) ?></th>
                                <th><?php echo $appCommon->show_order('type', __('Type', true)) ?></th>
                            </tr>
                        </thead>

                        <tbody>
                                    <?php foreach ($data as $item)
                                    {
                                        ?>
                                <tr>
                                    <td><?php echo $item[0]['modify_on']; ?></td>
                                    <td><?php echo $item[0]['switch_name']; ?></td>
                                    <td><?php echo $item[0]['old_value']; ?></td>
                                    <td><?php echo $item[0]['new_value']; ?></td>
                                    <td><?php echo $item[0]['modify_by']; ?></td>
                                    <td>
                                        <?php echo isset($type_arr[$item[0]['type']]) ? $type_arr[$item[0]['type']] : ""; ?>
                                    </td>

                                </tr>
    <?php } ?>
                        </tbody>
                    </table>
                    <div class="row-fluid">
                        <div class="pagination pagination-large pagination-right margin-none">
    <?php echo $this->element('page'); ?>
                        </div> 
                    </div>
                </fieldset>
<?php } ?>
        </div>
    </div>
</div>
</div>


