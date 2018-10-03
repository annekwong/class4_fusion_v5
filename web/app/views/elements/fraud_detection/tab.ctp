<ul class="tabs">
    <li <?php if($active == 'list') echo 'class="active"'; ?>>
        <a class="glyphicons no-js list" href="<?php echo $this->webroot ?>detections/fraud_detection">
            <i></i>
            <?php __('Fraud Detection')?>
        </a>
    </li>
    <li <?php if($active == 'log') echo 'class="active"'; ?>>
        <a class="glyphicons no-js book_open" href="<?php echo $this->webroot ?>detections/fraud_detection_log">
            <i></i>
            <?php __('Execution Log')?>
        </a>
    </li>
</ul>