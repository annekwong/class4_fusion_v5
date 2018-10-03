<style>
     select, textarea, input[type="text"],input[type="password"]{margin-bottom: 0;width: 220px;}
</style>
<ul class="breadcrumb">
    <li><?php echo __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Origination') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Add Clients') ?></li>
</ul>
<div class="heading-buttons">
    <h1><?php echo __('Add Clients') ?></h1>
    
</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
        <a  class="btn btn-icon btn-inverse glyphicons circle_arrow_left" href="<?php echo $this->webroot ?>did/clients/index"><i></i>
            <?php __('Back')?>
        </a>
    </div>
    <div class="clearfix"></div>
<div class="innerLR">
    <div class="widget widget-tabs widget-body-white">
        <div class="widget-body">
            <div class="clearfix"></div>
            <div id="container">
                <?php echo $this->element('did_add_client_form',array('is_ajax'=>false)); ?>
            </div>
        </div>
    </div>
</div>

            