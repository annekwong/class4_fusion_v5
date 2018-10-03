<ul class="tabs">
    <li  <?php if ($active_tab == 'global') {
    echo "class='active'";
} ?>>
        <a href="<?php echo $this->webroot ?>monitorsreports/globalstats"   class="glyphicons stats">
            <i></i><?php echo __('globlestatus') ?>
        </a>
    </li>
<!--    <li  <?php if ($active_tab == 'product') {
    echo "class='active'";
} ?> >
        <a href="<?php echo $this->webroot ?>monitorsreports/productstats" class="glyphicons cars">
            <i></i><?php echo __('routestatus') ?>
        </a>  
    </li>-->
    <li  <?php if ($active_tab == 'ingress') {
    echo "class='active'";
} ?>>
        <a href="<?php echo $this->webroot ?>monitorsreports/carrier/ingress"   class="glyphicons left_arrow">
            <i></i><?php echo __('Ingress Carriers', true); ?>
        </a>
    </li>
    <li   <?php if ($active_tab == 'egress') {
    echo "class='active'";
} ?>>
        <a href="<?php echo $this->webroot ?>monitorsreports/carrier/egress"   class="glyphicons right_arrow">
            <i></i><?php echo __('Egress Carriers', true); ?>
        </a>
    </li>
</ul>