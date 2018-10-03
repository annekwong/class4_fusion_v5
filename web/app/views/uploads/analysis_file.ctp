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
                    <table  class="list footable table table-striped tableTools table-bordered  table-white table-primary">
                        <thead>
                            <?php
                            $headers = array_shift($table);
                            if(!empty($headers)):
                            foreach ($headers as $header):
                                ?>
                            <th>
                                <select name="columns[]">
                                    <?php foreach ($columns as $column): ?>
                                        <option <?php if (strtolower($header) == strtolower($column)) echo 'selected="selected"'; ?>><?php echo $column; ?></option>
                                    <?php endforeach; ?>
                                    <option value="" <?php if ($header == '') echo 'selected="selected"'; ?>><?php __('ignore') ?></option>
                                    <option value="<?php echo $header; ?>" <?php if ($header != '' && !in_array($header, $columns)) echo 'selected="selected"'; ?>><?php __('unkown') ?></option>
                                </select>
                            </th>
                            <?php
                            endforeach;
                            endif;
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
