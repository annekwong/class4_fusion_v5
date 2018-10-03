<ul class="tabs">
    <li <?php if($active == 'list') echo 'class="active"'; ?>>
        <a class="glyphicons no-js list" href="<?php echo $this->webroot ?>detections/bad_number_detection">
            <i></i>
            <?php __('Detection Rules')?>
        </a>
    </li>
<?php /*    <li <?php if($active == 'log') echo 'class="active"'; ?>>
        <a class="glyphicons no-js book_open" href="<?php echo $this->webroot ?>detections/bad_number_detection_list">
            <i></i>
            <?php __('Block List')?>
        </a>
    </li> */ ?>
</ul>