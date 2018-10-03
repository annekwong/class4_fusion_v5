<ul class="tabs">
    <li <?php if($active == 'process') echo 'class="active"'; ?>>
        <a href="<?php echo $this->webroot ?>rate_managements/index">
            <?php __('Unprocessed/Processed Decks')?>
        </a>
    </li>
    <li <?php if($active == 'unrecognized') echo 'class="active"'; ?>>
        <a href="<?php echo $this->webroot ?>rate_managements/unrecognized">
            <?php __('Unrecognized Decks')?>
        </a>
    </li>
</ul>