<form method="post" action="<?php $this->webroot; ?>ftp_trigger/<?php echo base64_encode($ftp_info[0][0]['id']); ?>">
    <input type="hidden" name="is_post" value="1" />
    <table class="list list-form table dynamicTable tableTools table-bordered  table-white">
        <tbody>
            <tr>
                <td class="align_right width40"><?php __('Start Date') ?></td>
                <td>
                    <input type="text" name="start_time" onclick="WdatePicker({dateFmt: 'yyyy-MM-dd HH:00:00'});" value="<?php echo date('Y-m-d 00:00:00'); ?>" style="width:220px;"/>
                </td>
            </tr>
            <tr>
                <td class="align_right width40"><?php __('End Date') ?></td>
                <td>
                    <input type="text" name="end_time" onclick="WdatePicker({dateFmt: 'yyyy-MM-dd HH:59:59'});" value="<?php echo date('Y-m-d 23:59:59'); ?>" style="width:220px;"/>
                </td>
            </tr>
<!--            <tr>-->
<!--                <td class="align_right width40">--><?php //__('Time zone') ?><!--</td>-->
<!--                <td>-->
<!--                    <select id="query-tz" name="gmt" class="input in-select select">-->
<!--                        <option value="-1200">GMT -12:00</option>-->
<!--                        <option value="-1100">GMT -11:00</option>-->
<!--                        <option value="-1000">GMT -10:00</option>-->
<!--                        <option value="-0900">GMT -09:00</option>-->
<!--                        <option value="-0800">GMT -08:00</option>-->
<!--                        <option value="-0700">GMT -07:00</option>-->
<!--                        <option value="-0600">GMT -06:00</option>-->
<!--                        <option value="-0500">GMT -05:00</option>-->
<!--                        <option value="-0400">GMT -04:00</option>-->
<!--                        <option value="-0300">GMT -03:00</option>-->
<!--                        <option value="-0200">GMT -02:00</option>-->
<!--                        <option value="-0100">GMT -01:00</option>-->
<!--                        <option value="+0000" selected>GMT +00:00</option>-->
<!--                        <option value="+0100">GMT +01:00</option>-->
<!--                        <option value="+0200">GMT +02:00</option>-->
<!--                        <option value="+0300">GMT +03:00</option>-->
<!--                        <option value="+0330">GMT +03:30</option>-->
<!--                        <option value="+0400">GMT +04:00</option>-->
<!--                        <option value="+0500">GMT +05:00</option>-->
<!--                        <option value="+0600">GMT +06:00</option>-->
<!--                        <option value="+0700">GMT +07:00</option>-->
<!--                        <option value="+0800">GMT +08:00</option>-->
<!--                        <option value="+0900">GMT +09:00</option>-->
<!--                        <option value="+1000">GMT +10:00</option>-->
<!--                        <option value="+1100">GMT +11:00</option>-->
<!--                        <option value="+1200">GMT +12:00</option>-->
<!--                    </select>-->
<!--                </td>-->
<!--            </tr>-->
<!--            --><?php //if (strcmp($ftp_info[0][0]['frequency'], 4)): ?>
<!--                <tr id="file_breakdown">-->
<!--                    <td class="align_right width40">-->
<!--                        --><?php //__('File Breakdown'); ?>
<!--                    </td>-->
<!--                    <td>-->
<!--                        <select name="file_breakdown">-->
<!--                            <option value="0">--><?php //__('As one big file') ?><!--</option>-->
<!--                            <option value="1">--><?php //__('As hourly file') ?><!--</option>-->
<!--                            <option value="2">--><?php //__('As daily file') ?><!--</option>-->
<!--                        </select>-->
<!--                    </td>-->
<!--                </tr>-->
<!--            --><?php //endif; ?>
        </tbody>
    </table>
</form>
