<ul class="tabs">
    <li <?php if($active == 'basic_1') echo 'class="active"'; ?>>
        <a class="glyphicons no-js paperclip" href="<?php echo $this->webroot; ?>alerts/invalid_number/1">
            <i></i><?php __('ANI Invalid Number Detection') ?>
        </a>
    </li>
    <li <?php if($active == 'ani_exec') echo 'class="active"'; ?>>
        <a class="glyphicons no-js book_open" href="<?php echo $this->webroot; ?>alerts/invalid_number_exec_log/1">
            <i></i><?php __('ANI Executed Log') ?>
        </a>
    </li>
    <li <?php if($active == 'basic_2') echo 'class="active"'; ?>>
        <a class="glyphicons no-js paperclip" href="<?php echo $this->webroot; ?>alerts/invalid_number/2">
            <i></i><?php __('DNIS Invalid Number Detection') ?>
        </a>
    </li>
    <li <?php if($active == 'dnis_exec') echo 'class="active"'; ?>>
        <a class="glyphicons no-js book_open" href="<?php echo $this->webroot; ?>alerts/invalid_number_exec_log/2">
            <i></i><?php __('DNIS Executed Log') ?>
        </a>
    </li>
    <li <?php if($active == 'block_log') echo 'class="active"'; ?>>
        <a class="glyphicons no-js list" href="<?php echo $this->webroot; ?>alerts/invalid_number_block_log">
            <i></i><?php __('Block Log') ?>
        </a>
    </li>
</ul>