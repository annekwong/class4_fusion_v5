<ul class="tabs">
    <li <?php if($active == 'ingress') echo 'class="active"'; ?>>
        <a href="<?php echo $this->webroot ?>did/orders/ingress_trunk" class="glyphicons left_arrow">
            <?php __('Orig. Service')?>
            <i></i>
        </a>
    </li>
    <li <?php if($active == 'egress') echo 'class="active"'; ?>>
        <a href="<?php echo $this->webroot ?>did/orders/egress_trunk" class="glyphicons right_arrow">
            <i></i>
            <?php __('Term. Service')?>
        </a>
    </li>
</ul>