<style>
    table.dynamicTable tableTools table-bordered {border-top: 1px solid #ebebeb}
    label{line-height: 30px;}
    select,input[type="text"]{width: 220px;margin:5px 0;}
</style>
<ul class="breadcrumb">
    <li><?php __('You are here')?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Statistics') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('LCR Report') ?></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('LRN Report') ?></h4>
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>
<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-head">
            <ul class="tabs">
                <li class='active'>
                    <a href="<?php echo $this->webroot ?>lcr_report/search" class="glyphicons search">
                        <?php __('Search')?>
                        <i></i>
                    </a>
                </li>
                <li>
                    <a href="<?php echo $this->webroot ?>lcr_report/index" class="glyphicons list">
                        <?php __('List')?>
                        <i></i>
                    </a>
                </li>
            </ul>
        </div>
        <div class="widget-body">
            <form class="lcr_report" method="post" >
                <table class="form footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
                    <tr>
                        <td class="right">
                            <label><?php __('Start Time')?></label>
                        </td>
                        <td>
                            <input type="text" name="start_time" value="<?php echo date("Y-m-d 00:00:00"); ?>" onclick="WdatePicker({dateFmt: 'yyyy-MM-dd HH:mm:ss'});">
                        </td>
                        <td class="right"><label><?php __('End Time')?></label></td>
                        <td><input type="text" name="end_time" value="<?php echo date("Y-m-d 23:59:59"); ?>" onclick="WdatePicker({dateFmt: 'yyyy-MM-dd HH:mm:ss'});"></td>
                    </tr>
                    <tr>
                        <td class="right"><label><?php __('Rate Table')?></label></td>
                        <td>
                            <select id="rate_table_id" name="rate_table_id">
                                <?php foreach ($rate_tables as $rate_table): ?>
                                    <option value="<?php echo $rate_table[0]['rate_table_id'] ?>"><?php echo $rate_table[0]['name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        <td class="right"><label><?php __('Routing Plan')?></label></td>
                        <td>
                            <select id="routing_plan" name="routing_plan_id"></select>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4" class="center"><input class="btn btn-primary" type="submit" value="<?php __('Submit')?>"></td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
</div>

<script>
    jQuery(document).ready(function($) {
        var $rate_table_id = $("#rate_table_id");
        var $routing_plan = $("#routing_plan");

        $rate_table_id.change(function() {
            $.ajax({
                url: '<?php echo $this->webroot ?>lcr_report/get_routing_plan/' + $rate_table_id.val(),
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    var options = new Array();
                    $.each(data, function(index, item) {
                        options.push('<option value="' + item['resource_id'] + '">' + item['name'] + '</option>')
                    });

                    var options_content = options.join('');
                    $routing_plan.html(options_content);
                }
            });
        }).trigger("change");
    });
</script>