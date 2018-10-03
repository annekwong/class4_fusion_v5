<style type="text/css">
    select.in-select {margin-bottom: 0px;}
    .button_group {text-align: center;}
</style>

<div id="title">
    <h1>
         <?php __('File Format')?>
    </h1>
</div>

<div id="container">
    
    <form method="post" action="<?php echo $this->webroot ?>clients/upload_reset_balance"> 
        <input type="hidden" name="abspath" value="<?php echo $abspath; ?>">
        <input type="hidden" name="type" value="<?php echo $type ?>">
    <table class="list">
        <thead>
            <?php
                $headers = array_shift($table);
                foreach($headers as $header):
            ?>
            <th>
                <select name="columns[]">
                    <?php foreach($columns as $column): 
                        $header = trim(strtolower($header));
                        ?>
                    <option <?php if ($header == $column) echo 'selected="selected"'; ?>><?php echo $column; ?></option>
                    <?php endforeach; ?>
                    <option value="" <?php if ($header == '') echo 'selected="selected"'; ?>><?php __('ignore')?></option>
                    <option value="<?php echo $header; ?>" <?php if ($header != '' && !in_array($header, $columns)) echo 'selected="selected"'; ?>><?php __('unkown')?></option>
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