<ul class="tabs">
        <li <?php if ($active=='list') echo  'class="active"' ?>>
            <a href="<?php echo $this->webroot ?>lrn_settings/items/<?php echo $group_id; ?>">
                <img width="16" height="16" src="<?php echo $this->webroot; ?>images/menuIcon.gif"><?php __('List')?> 
            </a>
        </li>
        <li <?php if ($active=='import') echo  'class="active"' ?>>
            <a href="<?php echo $this->webroot ?>lrn_settings/upload_items/<?php echo $group_id; ?>">
                <img width="16" height="16" src="<?php echo $this->webroot; ?>images/import.png"><?php __('Import')?>
            </a>
        </li>
</ul>