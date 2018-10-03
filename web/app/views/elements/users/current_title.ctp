

<ul class="breadcrumb">
    <li><?php __('You are here'); ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li>
        <a href="<?php echo $this->webroot ?>users/index">
        <?php __('Configuration') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>users/index">
    <?php echo __('usermanager', true); ?></a></li>
</ul>


<div class="heading-buttons">
    <h4 class="heading"><?php echo __('usermanager', true); ?></h4>
    
</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
        <?php  if ($_SESSION['role_menu']['Configuration']['users']['model_w'] && $have_add) {?>
        <a class="btn btn-primary btn-icon glyphicons circle_plus" href="<?php echo $this->webroot?>users/add_carrier_user"><i></i> <?php __('Create New')?></a>
        <?php }?>
        <?php if($this->params['hasGet']){?>
        <?php echo $this->element('xback',Array('backUrl'=>'users/view'))?>
        <?php } ?>
    </div>
    <div class="clearfix"></div>
