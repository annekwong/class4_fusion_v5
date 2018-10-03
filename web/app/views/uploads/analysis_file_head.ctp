<style type="text/css">
    select.in-select {margin-bottom: 0px;}
    .button_group {text-align: center;}
    .overflow_x{overflow-x:auto; margin-bottom: 10px;}
</style>

<ul class="breadcrumb">
    <li><?php __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('File Format') ?></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php __('File Format') ?></h4>

    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>
<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-body">
            <div class="overflow_x">
                <form method="post" id="form1">
                    <input type="hidden" name="show_type" value="<?php echo $show_type ?>"/>
                    <input type="hidden" name="myfile_guid" value="<?php echo $myfile_guid ?>"/>
                    <input type="hidden" name="with_header" value="<?php echo $with_header ?>"/>
                    <table class="center">
                        <tr>
                            <td align="right">
                                <label><?php __('Effective Date Format')?>:</label>
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
                    </table>
                    <table  class="list footable table table-striped tableTools table-bordered  table-white table-primary">
                        <thead>
                            <?php
                        if($with_header):
                            $headers = array_shift($table);
                        else:
                            $hcnt = isset($table[0]) ? $table[0] : '';
                            $hcnt = count($hcnt);
                            $headers = array_fill(0,$hcnt,'');
                        endif;
                            foreach ($headers as $header):
                                ?>
                            <th>
                                <select name="columns[]">
                                    <?php foreach ($columns as $column): ?>
                                        <option <?php echo 'value="'.$column.'"'; if (strtolower($header) == strtolower($column)) echo 'selected="selected"'; ?>><?php echo $column; ?></option>
                                    <?php endforeach; ?>
                                    <option value="" <?php if ($header == '') echo 'selected="selected"'; ?>><?php __('ignore') ?></option>
                                    <option value="<?php echo $header; ?>" <?php if ($header != '' && !in_array($header, $columns)) echo 'selected="selected"'; ?>><?php __('unkown') ?></option>
                                </select>
                            </th>
                            <?php
                            endforeach;
                        ?>
                        </thead>


                        <tbody>
                            <?php foreach ($table as $row): ?>
                                <tr>
                                    <?php foreach ($row as $field): ?>
                                        <td><?php echo $field; ?></td>
                                    <?php endforeach; ?>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                </form>
            </div>
            <div class="button_group">
                <input type="button" id="form_submit" value="<?php __('Submit')?>" class="input in-submit btn btn-primary">
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" >

$(function(){
    
    $("#form_submit").click(function(){
        
        $("#form1").submit();
        
    });



    
});
</script>
