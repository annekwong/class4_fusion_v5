<ul class="tabs">
    <li <?php if(!strcmp($active, 'index')){ ?>class="active"<?php } ?>>
        <a class="glyphicons no-js paperclip" href="<?php echo $this->webroot; ?>dialer_detection/index">
            <i></i><?php __('Dialer Detection')?>			
        </a>
    </li>
    <li <?php if(!strcmp($active, 'execution')){ ?>class="active"<?php } ?>>
        <a class="glyphicons no-js tint" href="<?php echo $this->webroot; ?>dialer_detection/execution_log">
            <i></i><?php __('Execution Log')?>			
        </a>
    </li>
    <li <?php if(!strcmp($active, 'ani_blocking')){ ?>class="active"<?php } ?>>
        <a class="glyphicons no-js vector_path_all" href="<?php echo $this->webroot; ?>dialer_detection/ani_blocking_log">
            <i></i><?php __('ANI Blocking Log')?>			
        </a>
    </li>
</ul>