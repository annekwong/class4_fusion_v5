<ul class="tabs">
    <li <?php if($active == 'term_service_buy') echo 'class="active"'; ?>>
        <a href="<?php echo $this->webroot ?>cdrreports_db/summary_reports/buy">
            <?php __('Ingress CDR') ?>
        </a>
    </li>
<?php if (Configure::read('did.enable')): ?>
<!--    <li <?php if($active == 'orig_service_buy') echo 'class="active"'; ?>>
        <a href="<?php echo $this->webroot ?>did/orders/report/buy">
            Orig. Service (Buy)
        </a>
    </li>-->
<?php endif; ?>
    <!--
    <li <?php if($active == 'term_service_sell') echo 'class="active"'; ?>>
        <a href="<?php echo $this->webroot ?>cdrreports_db/summary_reports/sell">
             <?php __('Egress CDR') ?>
        </a>
    </li>
    -->
<?php if (Configure::read('did.enable')): ?>
<!--    <li <?php if($active == 'orig_service_sell') echo 'class="active"'; ?>>
        <a href="<?php echo $this->webroot ?>did/orders/report/sell">
            Orig. Service (Sell)
        </a>
    </li>-->
<?php endif; ?>
    <li <?php if($active == 'export_log2') echo 'class="active"'; ?>>
        <a href="<?php echo $this->webroot; ?>cdrreports_db/export_log" class="glyphicons book_open">
                <i></i>
                <?php __('CDR Export Log') ?>
            </a>
        </li>
</ul>