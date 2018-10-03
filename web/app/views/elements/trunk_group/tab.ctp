<ul class="tabs">
    <li  <?php if ($current_page == 1) echo ' class="active"';?>>
        <a href="<?php echo $this->webroot ?>trunk_group/index"  class="glyphicons left_arrow">
            <i></i>
            <?php __('Egress Trunk Group')?>
        </a>
    </li>
    <li <?php if ($current_page == 0) echo ' class="active"';?>>
        <a href="<?php echo $this->webroot ?>trunk_group/index/0" class="glyphicons right_arrow">
            <i></i>
            <?php __('Ingress Trunk Group')?>
        </a>
    </li>
</ul>