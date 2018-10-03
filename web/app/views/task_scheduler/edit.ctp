<div class="product_list">
    <form method="post" action="<?php echo $this->webroot ?>task_scheduler/edit/<?php echo $taskScheduler['TaskScheduler']['id'] ?>" id="myform">
        <table class="list table table-condensed">
            <tr>
                <td><?php __('Name')?>:</td>
                <td>
                    <?php echo $taskScheduler['TaskScheduler']['name'] ?>
                </td>
            </tr>
            <tr>
                <td><?php __('Last Run')?>:</td>
                <td>
                    <?php echo $taskScheduler['TaskScheduler']['last_run'] ?>
                </td>
            </tr>
            <tr>
                <td><?php __('Active')?>:</td>
                <td>
                    <input type="checkbox" name="data[TaskScheduler][active]" <?php if($taskScheduler['TaskScheduler']['active']) echo 'checked="checked"'; ?> />
                </td>
            </tr>
            <tr>
                <td colspan="2"><?php __('Run at')?></td>
            </tr>
            <tr>
                <td>
                    <select class="input in-select" name="data[TaskScheduler][minute_type]">
                        <option value="0" <?php if ($taskScheduler['TaskScheduler']['minute_type'] == 0) echo 'selected="selected"' ?>><?php __('every')?></option>
                        <option value="1" <?php if ($taskScheduler['TaskScheduler']['minute_type'] == 1) echo 'selected="selected"' ?>><?php __('once')?></option>
                    </select>
                </td>
                <td>
                    <input type="text" name="data[TaskScheduler][minute]" class="in-decimal input in-text" style="width:100px;" value="<?php echo $taskScheduler['TaskScheduler']['minute'] ?>" /><label><?php __('minute(s)')?></label>
                </td>
            </tr>
            <tr>
                <td>
                    <select class="input in-select" name="data[TaskScheduler][hour_type]">
                        <option value="0" <?php if ($taskScheduler['TaskScheduler']['hour_type'] == 0) echo 'selected="selected"' ?>><?php __('every')?></option>
                        <option value="1" <?php if ($taskScheduler['TaskScheduler']['hour_type'] == 1) echo 'selected="selected"' ?>><?php __('once')?></option>
                    </select>
                </td>
                <td>
                    <input type="text" name="data[TaskScheduler][hour]" class="in-decimal input in-text" style="width:100px;"  value="<?php echo $taskScheduler['TaskScheduler']['hour'] ?>"  /><label><?php __('hour(s)')?></label>
                </td>
            </tr>
            <tr>
                <td>
                    <select class="input in-select" name="data[TaskScheduler][day_type]">
                        <option value="0" <?php if ($taskScheduler['TaskScheduler']['day_type'] == 0) echo 'selected="selected"' ?>><?php __('every')?></option>
                        <option value="1" <?php if ($taskScheduler['TaskScheduler']['day_type'] == 1) echo 'selected="selected"' ?>><?php __('once')?></option>
                    </select>
                </td>
                <td>
                    <input type="text" name="data[TaskScheduler][day]" class="in-decimal input in-text" style="width:100px;"  value="<?php echo $taskScheduler['TaskScheduler']['day'] ?>"  /><label><?php __('day(s)')?></label>
                </td>
            </tr>
            <tr>
                <td><?php __('Day of Week')?>:</td>
                <td>
                    <select class="input in-select" name="data[TaskScheduler][week]">
                        <option value="" <?php if ($taskScheduler['TaskScheduler']['week'] === NULL) echo 'selected="selected"' ?>><?php __('all')?></option>
                        <option value="1" <?php if ($taskScheduler['TaskScheduler']['week'] === 1) echo 'selected="selected"' ?>><?php __('monday')?></option>
                        <option value="2" <?php if ($taskScheduler['TaskScheduler']['week'] === 2) echo 'selected="selected"' ?>><?php __('tuesday')?></option>
                        <option value="3" <?php if ($taskScheduler['TaskScheduler']['week'] === 3) echo 'selected="selected"' ?>><?php __('Wednesday')?></option>
                        <option value="4" <?php if ($taskScheduler['TaskScheduler']['week'] === 4) echo 'selected="selected"' ?>><?php __('thursday')?></option>
                        <option value="5" <?php if ($taskScheduler['TaskScheduler']['week'] === 5) echo 'selected="selected"' ?>><?php __('friday')?></option>
                        <option value="6" <?php if ($taskScheduler['TaskScheduler']['week'] === 6) echo 'selected="selected"' ?>><?php __('Saturday')?></option>
                        <option value="7" <?php if ($taskScheduler['TaskScheduler']['week'] === 7) echo 'selected="selected"' ?>><?php __('sunday')?></option>
                    </select>
                </td>
            </tr>
        </table>
    </form>
</div>