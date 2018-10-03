<tr>
    <td>
        <select name="rate_table_id[]" class="rate_table_id">
            <?php foreach ($rate_table as $rate_table_id => $rate_table_name): ?>
                <option value="<?php echo $rate_table_id ?>"><?php echo $rate_table_name ?></option>
            <?php endforeach; ?>
        </select>
    </td>
    <?php if ($_GET['is_us']): ?>
        <td>
            <input class=" input in-text width80 validate[required]" type="text"   onfocus="WdatePicker({dateFmt: 'yyyy-MM-dd', readOnly: true, minDate: '<?php echo date("Y-m-d"); ?>'});" name="effective_date_new[]">
        </td>
        <td>
            <select name="end_date_method[]" >
                <?php foreach ($end_date_method as $end_date_method_id => $end_date_method_name): ?>
                    <option value="<?php echo $end_date_method_id ?>"><?php echo $end_date_method_name ?></option>
                <?php endforeach; ?>
            </select>
            <input class="input in-text width80" type="text"  onfocus="WdatePicker({dateFmt: 'yyyy-MM-dd', readOnly: true, minDate: '<?php echo date("Y-m-d"); ?>'});" name="end_date[]">
        </td>
    <?php else: ?>
        <td>
            <input class=" input in-text width80 validate[required]" type="text"   onfocus="WdatePicker({dateFmt: 'yyyy-MM-dd', readOnly: true, minDate: '<?php echo date("Y-m-d"); ?>'});" name="effective_date_new[]">
        </td>
        <td>
            <input class="input in-text width80 validate[required]" type="text" onfocus="WdatePicker({dateFmt: 'yyyy-MM-dd', readOnly: true, minDate: '<?php echo date("Y-m-d"); ?>'});" name="effective_date_increase[]">
        </td>
        <td>
            <input class="input in-text width80 validate[required]" type="text" onfocus="WdatePicker({dateFmt: 'yyyy-MM-dd', readOnly: true, minDate: '<?php echo date("Y-m-d"); ?>'});" name="effective_date_decrease[]">
        </td>
        <td>
            <select name="end_date_method[]" >
                <?php foreach ($end_date_method as $end_date_method_id => $end_date_method_name): ?>
                    <option value="<?php echo $end_date_method_id ?>"><?php echo $end_date_method_name ?></option>
                <?php endforeach; ?>
            </select>
        </td>
        <td>
            <input class="input in-text width80" type="text"  onfocus="WdatePicker({dateFmt: 'yyyy-MM-dd', readOnly: true, minDate: '<?php echo date("Y-m-d"); ?>'});" name="end_date[]">
        </td>
    <?php endif; ?>
    <td>
        <a title="<?php  __('Remove'); ?>" href="javascript:void(0)" onclick="$(this).closest('tr').remove();">
            <i class="icon-remove"></i>
        </a>
    </td>
</tr>