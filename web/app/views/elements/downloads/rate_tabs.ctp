<?php if(!isset($action)){ $action = 'rates';} ?>
<ul class="<?php echo $action; ?>">
    <li <?php if($action == 'view'): ?>class="active"<?php endif; ?>><a href="<?php echo $this->webroot ?>clientrates/view/<?php echo base64_encode($table_id) ?>" class="glyphicons justify"><i></i> <?php echo __('Rates',true);?></a></li>
    <?php if ($jur_type == 3 || $jur_type == 4): ?>
    <li <?php if($action == ''): ?>class="active"<?php endif; ?>><a href="<?php echo $this->webroot?>clientrates/view/<?php echo base64_encode($table_id)?>/<?php echo $currency?>/npan" class="glyphicons notes_2"><i></i><?php echo __('NPANXX Rate',true);?> </a></li>
    <?php endif; ?>
<!--    <li --><?php //if($action == 'simulate'): ?><!-- class="active" --><?php //endif; ?><!-- ><a href="--><?php //echo $this->webroot ?><!--clientrates/simulate/--><?php //echo base64_encode($table_id) ?><!--" class="glyphicons nails"><i></i> --><?php //echo __('Simulate',true);?><!--</a></li>-->
    <li <?php if($action == 'import'): ?>class="active"<?php endif; ?>><a href="<?php echo $this->webroot ?>clientrates/import/<?php echo base64_encode($table_id) ?>" class="glyphicons upload"><i></i> <?php echo __('Import',true);?></a></li>
    <?php if (isset($_SESSION['role_menu']['Switch']['downloads']['model_w']) && $_SESSION['role_menu']['Switch']['downloads']['model_w']): ?>
    <li <?php if($action == 'rates'): ?>class="active"<?php endif; ?>><a href="<?php echo $this->webroot ?>downloads/rate/<?php echo base64_encode($table_id)?>" class="glyphicons download"><i></i> <?php echo __('Export',true);?></a></li>
    <?php endif; ?>
    <?php if (isset($_SESSION['role_menu']['Tools']['rates_management']['model_w']) && $_SESSION['role_menu']['Tools']['rates_management']['model_w']): ?>
    <!--li <?php if($action == 'auto'): ?>class="active"<?php endif; ?>><a href="<?php echo $this->webroot ?>rates_management/upload_configuration/<?php echo base64_encode($table_id); ?>"  class="glyphicons cogwheels">
            <i></i>
            <?php echo __('Automate Rate Update', true); ?>
        </a>
    </li-->
    <?php endif; ?>
</ul>