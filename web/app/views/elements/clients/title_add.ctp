<ul class="breadcrumb">
    <li>You are here</li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>clients/index">
        <?php __('Management') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>clients/index">
        <?php echo __('Client') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot . $this->params['url']['url'] ?>">
        <?php echo __('Create New') ?></a></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php __('Client')?></h4>
    
</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
    <?php if(isset($_SESSION['role_menu']['Template']['carrier_template']['model_w']) && $_SESSION['role_menu']['Template']['carrier_template']['model_w'] && $have_template): ?>
        <a class="link_btn btn btn-primary btn-icon glyphicons circle_plus" href="<?php echo $this->webroot ?>carrier_template/add_carrier_by_template">
            <i></i> <?php __('Create new by Template')?>
        </a>
    <?php endif; ?>
        <a class="btn btn-icon btn-inverse glyphicons circle_arrow_left" href="<?php echo $this->webroot?>clients/index"><i></i> <?php __('Back')?></a>
    </div>
    <div class="clearfix"></div>
