<style type="text/css">
    select.in-select {margin-bottom: 0px;}
    .button_group {text-align: center;}
</style>

<div id="title">
    <h1> <?php __('Rate Management') ?>&gt;&gt;<?php echo __('Upload',true);?>
</div>

<div id="container">
    
    <form method="post" action="###"> 
        <input type="hidden" name="abspath" value="<?php echo $rate_file; ?>">
        <?php
        $egress_cnt = count($egresses);
        if ($egress_cnt > 1): ?>
        <label><?php __('Egress Trunk')?></label>
        <select name="egress_id">
            <?php foreach($egresses as $egress): ?>
            <option value="<?php echo $egress[0]['resource_id'] ?>"><?php echo $egress[0]['alias'] ?></option>
            <?php endforeach; ?>
        </select>
        <?php elseif ($egress_cnt == 1): ?>
        <input type="hidden" name="egress_id" value="<?php echo $egresses[0][0]['resource_id'] ?>">
        <?php else: ?>
        <h1><?php __('You must create a egress trunk first')?>.</h1>
        <?php endif; ?>
        <!--
        <label>Email Nonfiction When Done</label>
        <input type="checkbox" id="email_done" name="email_done">
        <span id="notify">
        <label>Success Rate Notice</label>
        <select name="success_notice">
            <option value="0">Email to Client</option>
            <option value="1">Email to Rate Admin</option>
            <option value="2">Email to Both</option>
        </select>
        <label>Failed Rate Notice</label>
        <select name="failed_notice">
            <option value="0">Email to Client</option>
            <option value="1">Email to Rate Admin</option>
            <option value="2">Email to Both</option>
        </select>
        </span>
        -->
    <table class="list">
        <thead>
            <?php
                $headers = array_shift($table);
                foreach($headers as $header):
            ?>
            <th>
                <select name="columns[]">
                    <?php 
                    foreach($columns as $column): 
                      $header = strtolower($header);  
                    ?>
                    <option <?php if ($header == $column) echo 'selected="selected"'; ?>><?php echo $column; ?></option>
                    <?php endforeach; ?>
                    <option value="" <?php if ($header == '') echo 'selected="selected"'; ?>>ignore</option>
                    <option value="<?php echo $header; ?>" <?php if ($header != '' && !in_array($header, $columns)) echo 'selected="selected"'; ?>>unkown</option>
                </select>
            </th>
            <?php
                endforeach;
            ?>
        </thead>
        
        
        <tbody>
            <?php foreach($table as $row): ?>
            <tr>
            <?php foreach($row as $field): ?>
                <td><?php echo $field; ?></td>
            <?php endforeach;?>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="button_group">
        <input type="submit" value="<?php __('Submit')?>" />
        
        <input type="reset" value="<?php __('Revert')?>" />
    </div>
    </form>
</div>

<script>
$(function() {
    var $email_done = $('#email_done');
    var $notify     = $('#notify');
    
    $email_done.change(function() {
        if ($(this).attr('checked')) {
            $notify.show();
        } else {
            $notify.hide();
        }
    });
});    
</script>