<?php $show_label = isset($label) ? $label :  __('Created Time',true); ?>
<?php if (isset($datetime)): ?>
<div>
    <label><?php echo $show_label; ?>:</label>
    <input id="log_query_date_start" class="input in-text wdate width140" value="<?php echo $appCommon->_get('query_time_start') ?>" type="text" readonly=""
           onfocus="WdatePicker({dateFmt: 'yyyy-MM-dd HH:mm:ss',maxDate:'#F{$dp.$D(\'log_query_date_end\')}'});" name="query_time_start">
    --
    <input id="log_query_date_end" class="wdate input in-text width140" type="text" value="<?php echo $appCommon->_get('query_time_end') ?>" readonly=""
           onfocus="WdatePicker({dateFmt: 'yyyy-MM-dd HH:mm:ss',minDate:'#F{$dp.$D(\'log_query_date_start\')}'});"  name="query_time_end">
</div>
<?php else: ?>
    <div>
        <label><?php echo $show_label; ?>:</label>
        <input id="log_query_date_start" class="input in-text wdate width80" value="<?php echo $appCommon->_get('query_time_start') ?>" type="text" readonly=""
               onfocus="WdatePicker({dateFmt: 'yyyy-MM-dd',maxDate:'#F{$dp.$D(\'log_query_date_end\')}'});" name="query_time_start">
        --
        <input id="log_query_date_end" class="wdate input in-text width80" type="text" value="<?php echo $appCommon->_get('query_time_end') ?>" readonly=""
               onfocus="WdatePicker({dateFmt: 'yyyy-MM-dd',minDate:'#F{$dp.$D(\'log_query_date_start\')}'});"  name="query_time_end">
    </div>
<?php endif; ?>
