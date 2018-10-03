
        <tr>
            <td class="right">
                <?php __('Execution Schedule')?> 
            </td>
            <td>
                <select name="AlertRules[execution_schedule]" id="step4_type">
                    <option value="">Never</option>
                    <option value="1" <?php if ($post_data['execution_schedule'] == 1) echo 'selected="selected"' ?>><?php __('Every Specific Minutes')?></option>
                    <option value="2" <?php if ($post_data['execution_schedule'] == 2) echo 'selected="selected"' ?>><?php __('Daily')?></option>
                    <option value="3" <?php if ($post_data['execution_schedule'] == 3) echo 'selected="selected"' ?>><?php __('Weekly')?></option>
                </select>
            </td>
        </tr>
        <tr id="step4_type1" class="step4_type <?php if($post_data['execution_schedule'] != 1){echo "hidden";} ?>">
            <td class="right">
                <?php __('Run every')?>
            </td>
            <td>
                <input type="text" name="AlertRules[specific_minutes]" value="<?php echo $post_data['specific_minutes'] ?>" style="width:220px;" />
                min
            </td>
        </tr>
        <tr id="step4_type2" class="step4_type <?php if($post_data['execution_schedule'] != 2){echo "hidden";} ?>">
            <td class="right">
                <?php __('Run on')?>
            </td>
            <td>
                <select name="AlertRules[daily_time]" id="step4_type2_time">
                    <option value="">00:00</option>
                    <option value="1" <?php if ($post_data['daily_time'] == 1) echo 'selected="selected"' ?>>01:00</option>
                    <option value="2" <?php if ($post_data['daily_time'] == 2) echo 'selected="selected"' ?>>02:00</option>
                    <option value="3" <?php if ($post_data['daily_time'] == 3) echo 'selected="selected"' ?>>03:00</option>
                    <option value="4" <?php if ($post_data['daily_time'] == 4) echo 'selected="selected"' ?>>04:00</option>
                    <option value="5" <?php if ($post_data['daily_time'] == 5) echo 'selected="selected"' ?>>05:00</option>
                    <option value="6" <?php if ($post_data['daily_time'] == 6) echo 'selected="selected"' ?>>06:00</option>
                    <option value="7" <?php if ($post_data['daily_time'] == 7) echo 'selected="selected"' ?>>07:00</option>
                    <option value="8" <?php if ($post_data['daily_time'] == 8) echo 'selected="selected"' ?>>08:00</option>
                    <option value="9" <?php if ($post_data['daily_time'] == 9) echo 'selected="selected"' ?>>09:00</option>
                    <option value="10" <?php if ($post_data['daily_time'] == 10) echo 'selected="selected"' ?>>10:00</option>
                    <option value="11" <?php if ($post_data['daily_time'] == 11) echo 'selected="selected"' ?>>11:00</option>
                    <option value="12" <?php if ($post_data['daily_time'] == 12) echo 'selected="selected"' ?>>12:00</option>
                    <option value="13" <?php if ($post_data['daily_time'] == 13) echo 'selected="selected"' ?>>13:00</option>
                    <option value="14" <?php if ($post_data['daily_time'] == 14) echo 'selected="selected"' ?>>14:00</option>
                    <option value="15" <?php if ($post_data['daily_time'] == 15) echo 'selected="selected"' ?>>15:00</option>
                    <option value="16" <?php if ($post_data['daily_time'] == 16) echo 'selected="selected"' ?>>16:00</option>
                    <option value="17" <?php if ($post_data['daily_time'] == 17) echo 'selected="selected"' ?>>17:00</option>
                    <option value="18" <?php if ($post_data['daily_time'] == 18) echo 'selected="selected"' ?>>18:00</option>
                    <option value="19" <?php if ($post_data['daily_time'] == 19) echo 'selected="selected"' ?>>19:00</option>
                    <option value="20" <?php if ($post_data['daily_time'] == 20) echo 'selected="selected"' ?>>20:00</option>
                    <option value="21" <?php if ($post_data['daily_time'] == 21) echo 'selected="selected"' ?>>21:00</option>
                    <option value="22" <?php if ($post_data['daily_time'] == 22) echo 'selected="selected"' ?>>22:00</option>
                    <option value="23" <?php if ($post_data['daily_time'] == 23) echo 'selected="selected"' ?>>23:00</option>
                </select>
                <?php __('of every day')?>
            </td>
        </tr>

        <tr id="step4_type3" class="step4_type <?php if($post_data['execution_schedule'] != 3){echo "hidden";} ?>">
            <td class="right">
                <?php __('Run on')?>
            </td>
            <td>
                <select name="AlertRules[weekly_time]" id="step4_type3_time">
                    <option value="">00:00</option>
                    <option value="1" <?php if ($post_data['weekly_time'] == 1) echo 'selected="selected"' ?>>01:00</option>
                    <option value="2" <?php if ($post_data['weekly_time'] == 2) echo 'selected="selected"' ?>>02:00</option>
                    <option value="3" <?php if ($post_data['weekly_time'] == 3) echo 'selected="selected"' ?>>03:00</option>
                    <option value="4" <?php if ($post_data['weekly_time'] == 4) echo 'selected="selected"' ?>>04:00</option>
                    <option value="5" <?php if ($post_data['weekly_time'] == 5) echo 'selected="selected"' ?>>05:00</option>
                    <option value="6" <?php if ($post_data['weekly_time'] == 6) echo 'selected="selected"' ?>>06:00</option>
                    <option value="7" <?php if ($post_data['weekly_time'] == 7) echo 'selected="selected"' ?>>07:00</option>
                    <option value="8" <?php if ($post_data['weekly_time'] == 8) echo 'selected="selected"' ?>>08:00</option>
                    <option value="9" <?php if ($post_data['weekly_time'] == 9) echo 'selected="selected"' ?>>09:00</option>
                    <option value="10" <?php if ($post_data['weekly_time'] == 10) echo 'selected="selected"' ?>>10:00</option>
                    <option value="11" <?php if ($post_data['weekly_time'] == 11) echo 'selected="selected"' ?>>11:00</option>
                    <option value="12" <?php if ($post_data['weekly_time'] == 12) echo 'selected="selected"' ?>>12:00</option>
                    <option value="13" <?php if ($post_data['weekly_time'] == 13) echo 'selected="selected"' ?>>13:00</option>
                    <option value="14" <?php if ($post_data['weekly_time'] == 14) echo 'selected="selected"' ?>>14:00</option>
                    <option value="15" <?php if ($post_data['weekly_time'] == 15) echo 'selected="selected"' ?>>15:00</option>
                    <option value="16" <?php if ($post_data['weekly_time'] == 16) echo 'selected="selected"' ?>>16:00</option>
                    <option value="17" <?php if ($post_data['weekly_time'] == 17) echo 'selected="selected"' ?>>17:00</option>
                    <option value="18" <?php if ($post_data['weekly_time'] == 18) echo 'selected="selected"' ?>>18:00</option>
                    <option value="19" <?php if ($post_data['weekly_time'] == 19) echo 'selected="selected"' ?>>19:00</option>
                    <option value="20" <?php if ($post_data['weekly_time'] == 20) echo 'selected="selected"' ?>>20:00</option>
                    <option value="21" <?php if ($post_data['weekly_time'] == 21) echo 'selected="selected"' ?>>21:00</option>
                    <option value="22" <?php if ($post_data['weekly_time'] == 22) echo 'selected="selected"' ?>>22:00</option>
                    <option value="23" <?php if ($post_data['weekly_time'] == 23) echo 'selected="selected"' ?>>23:00</option>
                </select>
                <?php __('of every')?>
                <select name="AlertRules[weekly_value]" id="step4_type3_week">
                    <option value="">Sunday</option>
                    <option value="1" <?php if ($post_data['weekly_value'] == 1) echo 'selected="selected"' ?>><?php __('Monday')?></option>
                    <option value="2" <?php if ($post_data['weekly_value'] == 2) echo 'selected="selected"' ?>><?php __('Tuesday')?></option>
                    <option value="3" <?php if ($post_data['weekly_value'] == 3) echo 'selected="selected"' ?>><?php __('Wednesday')?></option>
                    <option value="4" <?php if ($post_data['weekly_value'] == 4) echo 'selected="selected"' ?>><?php __('Thursday')?></option>
                    <option value="5" <?php if ($post_data['weekly_value'] == 5) echo 'selected="selected"' ?>><?php __('Friday')?></option>
                    <option value="6" <?php if ($post_data['weekly_value'] == 6) echo 'selected="selected"' ?>><?php __('Saturday')?></option>
                </select>
            </td>
        </tr>
        <tr>
            <td class="right">
                <?php __('Sample Size')?>
            </td>
            <td>
                <input type="text" name="AlertRules[sample_size]" value="<?php echo $post_data['sample_size']; ?>" class="validate[required,custom[number]]" style="width:220px;" /><?php __('minutes')?>
            </td>
        </tr>
    </tbody>
</table>
<div class="center separator">
<!--    <a step="#step2" href=""  data-toggle="tab" value="next"  id="previous4" class=" btn primary">--><?php //__('Previous')?><!--</a>-->
    <input type="submit" value="Finish" id="finish" class="input in-submit btn btn-primary" />
</div>
