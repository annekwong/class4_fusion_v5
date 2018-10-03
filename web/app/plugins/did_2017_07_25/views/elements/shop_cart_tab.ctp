<ul class="tabs">
    <li <?php if ($active == 'single') echo 'class="active"' ?>>
        <a href="<?php echo $this->webroot; ?>did/orders/shopping_cart">
            <?php __('Single')?>
        </a>
    </li>
    <li <?php if ($active == 'multiples') echo 'class="active"' ?>>
        <a href="<?php echo $this->webroot; ?>did/orders/shopping_cart_mutiples">
            <?php __('Multiples')?>
        </a>
    </li>
</ul>