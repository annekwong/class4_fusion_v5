<ul class="breadcrumb">
    <li><?php __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Management') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Client') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Create New') ?></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php __('Client') ?></h4>
    <div class="buttons pull-right">
        <a class="btn btn-icon btn-inverse glyphicons circle_arrow_left" href="<?php echo $this->webroot?>clients/index"><i></i> <?php __('Back') ?></a>
    </div>
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>

<div class="innerLR">

    <div class="widget widget-heading-simple widget-body-white">
        <div class="widget-body center">

            <?php if($_SESSION['role_menu']['Template']['template']['model_w'] && $have_e_template): ?>

    <input type="button" style="max-width: 300px;" id="egress_template" class="btn btn-primary" value="<?php echo __('Add Egress Trunk by Template',true);?>" />
            <?php endif; ?>
    <input type="button" id="egress" class="btn btn-primary" value="<?php echo __('Add Egress Trunk',true);?>" />

            <?php if($_SESSION['role_menu']['Template']['template']['model_w'] && $have_in_template): ?>

    <input type="button" style="max-width: 300px;" id="ingress_template" class="btn btn-primary" value="<?php echo __('Add Ingress Trunk by Template',true);?>" />
            <?php endif; ?>
    <input type="button" id="ingress" class="btn btn-primary" value="<?php echo __('Add Ingress Trunk',true);?>" />

    <input type="button" id="cancel" class="btn btn-primary" value="<?php echo __('Finish',true);?>" />

        </div>
    </div>
</div>
<script type="text/javascript">
jQuery(function($) {
    $('#ingress').click();
    $('#egress').click(function() {
        window.location.href = "<?php echo $this->webroot ?>clients/addegress/<?php echo $client_id ?>/<?php echo isset($registration_id) ? $registration_id : '' ?>";
    });
    $('#egress_template').click(function() {
        window.location.href = "<?php echo $this->webroot ?>template/add_resource_by_template/1/<?php echo $client_id ?>";
    });
    $('#ingress').click(function() {
        window.location.href = "<?php echo $this->webroot ?>clients/addingress/<?php echo $client_id ?>/<?php echo isset($registration_id) ? $registration_id : '' ?>";
    });
    $('#ingress_template').click(function() {
        window.location.href = "<?php echo $this->webroot ?>template/add_resource_by_template/0/<?php echo $client_id ?>";
    });
    $('#cancel').click(function() {
        window.location.href = "<?php echo $this->webroot ?>clients/index";
    });
});
</script>