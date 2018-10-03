<ul class="sub_panel">
    <li <?php if($active == 'browse') echo 'class="active"'; ?>>
        <a href="<?php echo $this->webroot ?>did/orders/browse">
            <?php __('Browse')?>
        </a>
    </li>
    <li <?php if($active == 'trunk') echo 'class="active"'; ?>>
        <a href="<?php echo $this->webroot ?>did/orders/trunk">
            <?php __('Trunk')?>
        </a>
    </li>
    <li <?php if($active == 'report') echo 'class="active"'; ?>>
        <a href="<?php echo $this->webroot ?>did/orders/report">
            <?php __('Report')?>
        </a>
    </li>
</ul>