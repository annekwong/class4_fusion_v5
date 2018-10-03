<div class="widget-head">
<ul class="tabs">
    <li <?php if ($active == 'form') echo ' class="active"' ?>>
        <a href="<?php echo $this->webroot ?>quickcdr" class="glyphicons left_arrow">
            <i></i><?php __('Simple CDR Export')?>
        </a>
    </li>
    <li <?php if ($active == 'log') echo ' class="active"' ?>>
        <a href="<?php echo $this->webroot ?>quickcdr/logging" class="glyphicons right_arrow">
             <i></i><?php __('Log')?>
        </a>
    </li>
</ul>
</div>