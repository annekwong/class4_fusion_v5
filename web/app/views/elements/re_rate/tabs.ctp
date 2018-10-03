<ul class="tabs">
    <li <?php if($active == 'task') echo 'class="active"'; ?>>
        <a class="glyphicons cogwheel" href="<?php echo $this->webroot; ?>rerate/create_task">
            <i></i>
            <?php __('Create Re-Rate Task'); ?>
        </a>
    </li>
    <li <?php if($active == 'task_log') echo 'class="active"'; ?>>
        <a class="glyphicons list" href="<?php echo $this->webroot; ?>rerate/re_rate_log">
            <i></i>
            <?php __('Re-Rate History'); ?>
        </a>
    </li>
    <li <?php if($active == 'download_log') echo 'class="active"'; ?>>
        <a class="glyphicons list" href="<?php echo $this->webroot; ?>rerate/cdr_download_log">
            <i></i>
            <?php __('Re-Rate CDR Download Log'); ?>
        </a>
    </li>
    <li <?php if($active == 'execute_log') echo 'class="active"'; ?>>
        <a class="glyphicons list" href="<?php echo $this->webroot; ?>rerate/execute_log">
            <i></i>
            <?php __('Re-Rate Execute Log'); ?>
        </a>
    </li>
</ul>