<style>
    /*select, input[type="text"], input[type="password"]{width: 220px;margin-bottom: 0;}*/
</style>
    <ul class="breadcrumb">
        <li><?php echo __('You are here') ?></li>
        <li class="divider"><i class="icon-caret-right"></i></li>
        <li><a href="<?php echo $this->webroot ?>did/vendors"><?php __('Origination') ?></a></li>
        <li class="divider"><i class="icon-caret-right"></i></li>
        <li><a href="<?php echo $this->webroot ?>did/vendors/add"><?php echo __('Add Vendor') ?></a></li>
    </ul>
    <div class="heading-buttons">
        <h4 class="heading"><?php echo __('Add Vendor') ?></h4>

    </div>
    <div class="separator bottom"></div>
    <div class="buttons pull-right newpadding">
        <a  class="btn btn-icon btn-inverse glyphicons circle_arrow_left" href="<?php echo $this->webroot ?>did/vendors"><i></i>
            <?php echo __('Back') ?>
        </a>
    </div>
    <div class="clearfix"></div>
    <div class="innerLR">
        <div class="widget widget-tabs widget-body-white">
            <div class="widget-body">
                <div class="clearfix"></div>
                <div id="container">
                <?php echo $this->element('did_add_vendor_form',array('is_ajax'=> false)); ?>
                </div>
            </div>
        </div>
    </div>






