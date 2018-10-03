<form>    
    <table class="form table dynamicTable tableTools table-bordered  table-white" style="margin-top: 40px">
        <tr>
            <td class="align_right padding-r10">
                <?php __('Report Name'); ?>
            </td>
            <td class="left">
                <input type="text" name="scheduled_report_report_name" class="validate[required]" value="<?php echo $report_name; ?>" />
            </td>
        </tr>
        <tr>
            <td class="align_right padding-r10"><?php __('Deliver To'); ?></td>
            <td>
                <input type="text" class="scheduled_report_email_to validate[required,custom[email]]" name="email_to[]">
                <a href="javascript:void(0)" id="add_email">
                    <i class="icon-plus"></i>
                </a>
            </td>
        </tr>
        <tr style="display:none;">
            <td></td>
            <td>
                <input type="text" class="scheduled_report_email_to validate[required,custom[email]]" name="email_to[]" >
                <a href="javascript:void(0)" class="email_delete">
                    <i class="icon-remove"></i>
                </a>
            </td>
        </tr>
        <tr>
            <td class="align_right padding-r10">
                <?php __('Subject'); ?>
            </td>
            <td class="left">
                <input type="text" name="scheduled_report_subject" id="scheduled_report_subject" class="validate[required]" />
            </td>
        </tr>
        <tr>
            <th class="right">
                <?php __('Frequency'); ?>
            </th>
            <td colspan="3">
                <select name="scheduled_report_frequency_type" id="scheduled_report_frequency_type">
                    <option value="1" selected="selected"><?php __('Daily')?></option>
                    <option value="2"><?php __('Weekly')?></option>
                    <option value="3"><?php __('Monthly')?></option>
                </select>
            </td>
        </tr>
        <tr>
            <th class="right">
            </th>
            <td colspan="3">

                <span id="monthly" style="display:none;">
                    <select name="scheduled_report_month" id="scheduled_report_month" style="width: 100px;">
                        <?php for ($i = 1; $i <= 31; $i++)
                        { ?>
                            <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
<?php } ?>
                    </select>
                </span>
                <span id="weekly" style="display:none;">
                    <select name="scheduled_report_week" id="scheduled_report_week" style="width: 100px;">
                        <option value="1">Mon</option>
                        <option value="2">Tue</option>
                        <option value="3">Wed</option>
                        <option value="4">Thu</option>
                        <option value="5">Fri</option>
                        <option value="6">Sat</option>
                        <option value="7">Sun</option>
                    </select>
                </span>
                <span id="daily">
                    <select name="scheduled_report_time" id="scheduled_report_time" style="width: 100px;">
                        <?php for ($i = 0; $i < 24; $i++)
                        { ?>
                            <option value="<?php echo $i; ?>"><?php echo $i; ?>:00</option>
<?php } ?>
                    </select>
                </span>
            </td>
        </tr>
        <tr>
            <th class="right">
                <?php __('Interval'); ?>
            </th>
            <td colspan="3">
                <input type="text" name="scheduled_report_interval" id="scheduled_report_interval" value="" class="validate[required,custom[integer]]" />(h)
            </td>
        </tr>

    </table>
</form>
<script type="text/javascript">
    $(function() {
        $add_email = $("#add_email");
        $email_delete = $(".email_delete");
        $add_email.click(function() {
            var $this = $(this);
            var $parent = $this.parents('tr');
            var $clone = $parent.next().clone();
            $parent.after($clone);
            $clone.show();
        });
        $email_delete.live('click', function() {
            $(this).parents('tr').remove();
        });
        $("#scheduled_report_frequency_type").change(function() {

        var type = $(this).val();
                $("#monthly").hide();
                $("#weekly").hide();
                switch (type) {
        case '1':

                break;
                case '2':
                $("#weekly").show();
                break;
                case '3':
                $("#monthly").show();
                break;
                defalut: break;
        };
    });
    });
</script>