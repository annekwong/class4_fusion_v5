<ul>
    <li <?php if($active == 'term_service_buy') echo 'class="active"'; ?>>
        <a href="<?php echo $this->webroot ?>cdrreports/summary_reports/buy" class="glyphicons list">
            <?php __('Term. Service (Buy)')?>
            <i></i>
        </a>
    </li>
<?php if (Configure::read('did.enable')): ?>
    <li <?php if($active == 'orig_service_buy') echo 'class="active"'; ?>>
        <a href="<?php echo $this->webroot ?>did/orders/report/buy" class="glyphicons list">
            <?php __('Orig. Service (Buy)')?>
            <i></i>
        </a>
    </li>
<?php endif; ?>
    <li <?php if($active == 'term_service_sell') echo 'class="active"'; ?>>
        <a href="<?php echo $this->webroot ?>cdrreports/summary_reports/sell" class="glyphicons list">
            <?php __('Term. Service (Sell)')?>
            <i></i>
        </a>
    </li>
<?php if (Configure::read('did.enable')): ?>
    <li <?php if($active == 'orig_service_sell') echo 'class="active"'; ?>>
        <a href="<?php echo $this->webroot ?>did/orders/report/sell" class="glyphicons list">
            <?php __('Orig. Service (Sell)')?>
            <i></i>
        </a>
    </li>
<?php endif; ?>
    <li <?php if($active == 'export_log2') echo 'class="active"'; ?>>
            <a href="<?php echo $this->webroot; ?>cdrreports/export_log" class="glyphicons book_open">
                <i></i>
                <?php __('CDR Export Log')?>
            </a>
    </li>
    <li <?php if($active == 'export_log') echo 'class="active"'; ?>>
        <a href="<?php echo $this->webroot; ?>cdrreports/mail_send_log" class="glyphicons no-js e-mail">
            <?php __('Mail CDR Log')?>
            <i></i>
        </a>
    </li>
</ul>