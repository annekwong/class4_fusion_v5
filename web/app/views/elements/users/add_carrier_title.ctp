
<ul class="breadcrumb">
    <li><?php __('You are here'); ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>users/index"><?php __('Configuration') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot . $this->params['url']['url'] ?>">
    	<?php echo __('Create Carrier User', true); ?></a></li>
</ul>


<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Create Carrier User', true); ?></h4>
    
</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
        <?php echo $this->element('xback',Array('backUrl'=>'users/index'))?>
    </div>
    <div class="clearfix"></div>
