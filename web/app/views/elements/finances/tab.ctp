<ul class="tabs">
    <li <?php if(!strcmp($active, 'rec_payment')){ ?>class="active"<?php } ?>>
        <a class="glyphicons no-js inbox_in" href="<?php echo $this->webroot; ?>finances/finances_mass_add/<?php echo $encode_client_id; ?>/rec_payment">
            <i></i><?php __('Received Payment')?>
        </a>
    </li>
    <li <?php if(!strcmp($active, 'sent_payment')){ ?>class="active"<?php } ?>>
        <a class="glyphicons no-js inbox_out" href="<?php echo $this->webroot; ?>finances/finances_mass_add/<?php echo $encode_client_id; ?>/sent_payment">
            <i></i><?php __('Sent Payment')?>
        </a>
    </li>
    <li <?php if(!strcmp($active, 'vendor_invoice')){ ?>class="active"<?php } ?>>
        <a class="glyphicons no-js vector_path_all" href="<?php echo $this->webroot; ?>finances/finances_mass_add/<?php echo $encode_client_id; ?>/vendor_invoice">
            <i></i><?php __('Vendor Invoice')?>
        </a>
    </li>
</ul>