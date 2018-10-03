<style type="text/css">
    select.in-select {margin-bottom: 0px;}
    .button_group {text-align: center;}
    .overflow_x{overflow-x:auto; margin-bottom: 10px;}
</style>

<ul class="breadcrumb">
    <li><?php __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Tools') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Auto Rate Upload', true); ?></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php __('Auto Rate Upload') ?></h4>

    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>
<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-body">
            <form  id="myform" method="post" action="<?php echo $this->webroot ?>clientrates/import/<?php echo $rate_table_id; ?>">
                <input type="hidden" name="is_auto"  value="1"/>
                <input type="hidden" name="detail_id"  value="<?php echo $detail_id; ?>"/>
                <table class="center">
                    <tr>
                        <td align="right">
                            <label><?php __('Effective Date Format') ?>:</label>
                        </td>
                        <td align="left" style="padding-left:10px;">
                            <select name="date_format">
                                <option value="mm/dd/yyyy" <?php
                                if (!strcmp("mm/dd/yyyy", $date_format))
                                {
                                    ?>selected="selected"<?php } ?>>mm/dd/yyyy</option>
                                <option value="yyyy-mm-dd" <?php
                                if (!strcmp("yyyy-mm-dd", $date_format))
                                {
                                    ?>selected="selected"<?php } ?>>yyyy-mm-dd</option>
                                <option value="dd-mm-yyyy" <?php
                                if (!strcmp("dd-mm-yyyy", $date_format))
                                {
                                    ?>selected="selected"<?php } ?>>dd-mm-yyyy</option>
                                <option value="dd/mm/yyyy" <?php
                                if (!strcmp("dd/mm/yyyy", $date_format))
                                {
                                    ?>selected="selected"<?php } ?>>dd/mm/yyyy</option>
                                <option value="yyyy/mm/dd" <?php
                                if (!strcmp("yyyy/mm/dd", $date_format))
                                {
                                    ?>selected="selected"<?php } ?>>yyyy/mm/dd</option>
                            </select>
                        </td>
                    </tr>
                    <tr id="set_default_date" <?php if (isset($effective_date_flg))
                                        {
                                    ?>style="display:none;"<?php } ?>>
                        <td align="right">
                            <label><?php __('Set Default Effective Date') ?>:</label>
                        </td>
                        <td align="left" style="padding-left:10px;">
                            <input type="hidden" name="is_effective_date" id="is_effective_date" />
                            <input type="text" class="validate[required] in-text width220" name="effetive_date" value="<?php $rate_option['RateManagementOption']['effective_date_default'] ?>" onfocus="WdatePicker({dateFmt: 'yyyy-MM-dd HH:mm:ss'});" />
                        </td>
                    </tr>
                    <tr id="set_default_min_time" <?php if (isset($min_time_flg))
                    {
                        ?>style="display:none;"<?php } ?>>
                        <td align="right">
                            <label><?php __('Set Default Min Time') ?>:</label>
                        </td>
                        <td align="left" style="padding-left:10px;">
                            <input type="hidden" name="is_min_time" id="is_min_time" />
                            <input type="text" class="validate[required,custom[integer]] in-text width220" name="min_time" value="<?php $rate_option['RateManagementOption']['min_time_default'] ?>" />
                        </td>
                    </tr>
                    <tr id="set_default_interval" <?php if (isset($interval_flg))
                    {
                        ?>style="display:none;"<?php } ?>>
                        <td align="right">
                            <label><?php __('Set Default Interval') ?>:</label>
                        </td>
                        <td align="left" style="padding-left:10px;">
                            <input type="hidden" name="is_interval" id="is_interval" />
                            <input type="text" class="validate[required,custom[integer]] in-text width220" name="interval" value="<?php $rate_option['RateManagementOption']['interval_default'] ?>" />
                        </td>
                    </tr>
                </table>
                <div class="overflow_x">
                    <input type="hidden" name="date_check" id="date_check" />
                    <input type="hidden" name="cmd" value="<?php echo $cmd ?>">
                    <input type="hidden" name="end_effective_date" value="<?php echo $end_effective_date ?>">
                    <input type="hidden" name="abspath" value="<?php echo $abspath; ?>">
                    <input type="hidden" name="is_ocn_lata" value="<?php echo $is_ocn_lata; ?>">
                    <input type="hidden" name="rates_file_cmd" value="<?php echo $rates_file_cmd; ?>">
                    <input type="hidden" name="with_header" value="<?php echo $with_header; ?>">
                    <input type="hidden" name="code_name_match" value="<?php echo $code_name_match; ?>">
                    <table  class="list footable table table-striped tableTools table-bordered  table-white table-primary">
                        <thead>
                            <?php
                            $headers = array_shift($table);
                            foreach ($headers as $header):
                                ?>
                            <th>
                                <select class="columns" name="columns[]">
                                    <?php
                                    foreach ($columns as $column):
                                        $header = strtolower($header);
                                        ?>
                                        <option value="<?php echo $column; ?>" <?php if ($header == $column) echo 'selected="selected"'; ?>><?php echo $column; ?></option>
    <?php endforeach; ?>
                                    <option value="" <?php if ($header == '') echo 'selected="selected"'; ?>><?php __('ignore') ?></option>
                                    <option value="unkown" <?php if ($header != '' && !in_array($header, $columns)) echo 'selected="selected"'; ?>><?php __('unkown') ?></option>
                                </select>
                            </th>
    <?php
endforeach;
?>
                        </thead>


                        <tbody id="data_tbody">
                                <?php foreach ($table as $row): ?>
                                <tr>
                                <?php foreach ($row as $field): ?>
                                        <td><?php echo $field; ?></td>
    <?php endforeach; ?>
                                </tr>
<?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </form>
            <div class="button_group">
                <input type="button" id="form_submit" value="<?php __('Submit') ?>" class="input in-submit btn btn-primary">
            </div>
        </div>
    </div>
</div>


<script type="text/javascript">
    $("#form_submit").click(function () {

        $("#myform").submit();

    });

    $(function () {

        $(".columns").change(function () {
            $("#set_default_date").show();
            $("#set_default_interval").show();
            $("#set_default_min_time").show();
            $("#is_min_time").val('1');
            $("#is_interval").val('1');
            $("#is_effective_date").val('1');
            $("option").each(function () {
                var selected_value = $(this).val();
                var selected = $(this).attr('selected');
                if (selected && selected_value == 'effective_date')
                {
                    $("#is_effective_date").val(' ');
                    $("#set_default_date").hide();
                }
                if (selected && selected_value == 'interval')
                {
                    $("#is_interval").val(' ');
                    $("#set_default_interval").hide();
                }
                if (selected && selected_value == 'min_time')
                {
                    $("#is_min_time").val(' ');
                    $("#set_default_min_time").hide();
                }
            });
        });


        var $myform = $('#myform');
        var $columns = $('.columns');
        $myform.validationEngine();
        $myform.submit(function () {

            $("select").each(function () {
                var $select = $(this);
                if ($select.children("option:selected").val() === 'effective_date')
                {
                    var $effe_date = $select;
                    var $index = $effe_date.parent().index();
                    var i = 0;
                    $("#data_tbody tr").each(function () {
                        if (i == 5)
                        {
                            return false;
                        }
                        var datetime = $(this).children().eq($index).html();
                        if ($(this).children().size() <= 1)
                            return true;
                        if ($("#date_check").val())
                        {
                            $("#date_check").val($("#date_check").val() + ',' + datetime);
                        } else {
                            $("#date_check").val(datetime);
                        }

<?php
$date_format = str_replace('mm', 'm', $date_format);
$date_format = str_replace('yyyy', 'Y', $date_format);
$php_date_format = str_replace('dd', 'd', $date_format);
?>


                        i++;
                    });
                }
            });
            var flag = true;
            var effective_selected = false;
            var rate_selected = false;
            $columns.each(function () {
                var val = $(this).val();
                if (val == 'unkown')
                {
                    $.jGrowl("There is unkown field!", {theme: 'jmsg-error'});
                    flag = false;
                    return;
                }
                if (val == 'effective_date')
                    effective_selected = true;

                //if (val == 'rate')
                //rate_selected = true;
            });


//            if (!effective_selected && $('input[name=is_ocn_lata]').val() != '1')
//            {
//                $.jGrowl("You have not selected the field of effective!", {theme: 'jmsg-error'});
//                flag = false;
//            }


            //if (!rate_selected)
            //{
            //   $.jGrowl("You have not selected the field of rate!",{theme:'jmsg-error'});
            //    flag = false;
//}
            return flag;
        });
    });
</script>