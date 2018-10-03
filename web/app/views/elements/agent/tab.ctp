<ul class="tabs">
    <li <?php if($active == 'list') echo 'class="active"'; ?>>
        <a class="glyphicons no-js list" href="<?php echo $this->webroot ?>agent/index">
            <i></i>
            <?php __('Agent List')?>
        </a>
    </li>
    <li <?php if($active == 'detail') echo 'class="active"'; ?>>
        <a class="glyphicons no-js book_open" href="<?php echo $this->webroot ?>agent/agent_client">
            <i></i>
            <?php __('Client Assignment')?>
        </a>
    </li>
</ul>