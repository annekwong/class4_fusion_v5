<ul class="tabs">
    <li <?php if($active == 'orders') echo 'class="active"'; ?>>
        <a href="<?php echo $this->webroot ?>did/orders">
            <?php __('DID Search')?>			
        </a>
    </li>
    <li <?php if($active == 'listing') echo 'class="active"'; ?>>
        <a href="<?php echo $this->webroot ?>did/did_assign/listing">
            <?php __('DID Listing')?>			
        </a>
    </li>
    <li <?php if($active == 'trunk') echo 'class="active"'; ?>>
        <a href="<?php echo $this->webroot ?>did/orders/trunk">
            <?php __('DID Trunk')?>			
        </a>
    </li>
    <li <?php if($active == 'report') echo 'class="active"'; ?>>
        <a href="<?php echo $this->webroot ?>did/orders/report">
            <?php __('DID Report')?>			
        </a>
    </li>
</ul>