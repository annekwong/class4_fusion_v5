<ul class="tabs">
    <li <?php if($active == 'basic') echo 'class="active"'; ?>>
        <a class="glyphicons no-js cogwheel" href="<?php echo $this->webroot ?>systemparams/view">
            <i></i>
            <?php __('System Setting')?>
        </a>
    </li>
    <li <?php if($active == 'advance') echo 'class="active"'; ?>>
        <a class="glyphicons no-js cogwheels" href="<?php echo $this->webroot ?>systemparams/advance">
            <i></i>
            <?php __('Advance')?>
        </a>
    </li>
</ul>