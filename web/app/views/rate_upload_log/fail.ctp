<div id="title">
    <h1><?php echo __('Log',true);?>&gt;&gt;<?php echo __('Rate Upload Fail Log',true);?></h1>
</div>

<div id="container">
    
    <ul class="tabs">
        <li>
            <a href="<?php echo $this->webroot ?>rate_upload_log/success">
                <img width="16" height="16" src="<?php echo $this->webroot ?>images/success.png">
                <?php __('Success')?>
            </a>
        </li>
        <li class="active">
            <a href="<?php echo $this->webroot ?>rate_upload_log/fail">
                <img width="16" height="16" src="<?php echo $this->webroot ?>images/fail.png">
                <?php __('Fail')?>
            </a>
        </li>
    </ul>
    
    <?php
        if(empty($this->data)): 
    ?>
    <div class="msg"><?php echo __('no_data_found',true);?></div>
    <table class="list" style="display:none;">
        <thead>
            <tr>
                <td><?php __('File Name')?></td>
                <td><?php __('Received Time')?></td>
                <td><?php __('Imported Time')?></td>
                <td><?php __('Record Loaded')?></td>
            </tr>
        </thead>
        
        <tbody>
         
        </tbody>
    </table>
    <?php else: ?>
    <?php echo $this->element("xpage")?>
    <table class="list">
        <thead>
            <tr>
                <td><?php __('File Name')?></td>
                <td><?php __('Received Time')?></td>
                <td><?php __('Imported Time')?></td>
                <td><?php __('Record Loaded')?></td>
            </tr>
        </thead>
        
        <tbody>
            <?php foreach($this->data as $item): ?>
            <tr>
                <td><?php echo $item['RateUploadFailLog']['file_name']; ?></td>
                <td><?php echo $item['RateUploadFailLog']['received_time']; ?></td>
                <td><?php echo $item['RateUploadFailLog']['imported_time']; ?></td>
                <td><?php echo $item['RateUploadFailLog']['record_loaded']; ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php echo $this->element("xpage")?>
    <?php endif; ?>
</div>
    
