<ul class="tabs">
    <li      <?php if ($active_tab == 'base') {
    echo "class='active'";
} ?>   >
        <a class="glyphicons no-js circle_info"  href="<?php echo $this->webroot ?>prresource/gatewaygroups/edit_resouce_ingress/<?php echo array_keys_value($this->params, 'pass.0') ?>">
            <i></i><?php __('System Information') ?>
        </a>
    </li>

    <li      <?php if ($active_tab == 'lrn_action') {
    echo "class='active'";
} ?>  >
        <a class="glyphicons no-js magic" href="<?php echo $this->webroot ?>prresource/gatewaygroups/add_lrn_action/<?php echo array_keys_value($this->params, 'pass.0') ?>">
            <i></i><?php echo __('LRN Action', true); ?>
        </a>
    </li>
    <li   <?php if ($active_tab == 'action') {
    echo "class='active'";
} ?> >
        <a class="glyphicons no-js train" href="<?php echo $this->webroot ?>prresource/gatewaygroups/add_direction/<?php echo array_keys_value($this->params, 'pass.0') ?>/ingress/">
            <i></i><?php __('Action') ?>
        </a>        
    </li>
    <li  <?php if ($active_tab == 'mapping') {
    echo "class='active'";
} ?>>
        <a class="glyphicons no-js google_maps" href="<?php echo $this->webroot ?>prresource/gatewaygroups/add_translation_time/<?php echo array_keys_value($this->params, 'pass.0') ?>/ingress">
            <i></i><?php __('DigitMapping') ?>
        </a>
    </li>
    <li <?php if ($active_tab == 'dis_code') {
    echo "class='active'";
} ?> >
        <a  class="glyphicons no-js link"    href="<?php echo $this->webroot ?>fsconfigs/config_info/<?php echo array_keys_value($this->params, 'pass.0') ?>/ingress/">
            <i></i><?php __('Disconnect Code')?>
        </a>
    </li>
    <li  <?php if ($active_tab == 'rule') {
    echo "class='active'";
} ?>>
        <a class="glyphicons no-js train" href="<?php echo $this->webroot ?>prresource/gatewaygroups/add_rule/<?php echo array_keys_value($this->params, 'pass.0') ?>/ingress">
            <i></i><?php echo __('Fail-over Rule', true); ?>
        </a>        
    </li>
    <li  <?php if ($active_tab == 'pass_trusk') {
    echo "class='active'";
} ?>>
        <a  class="glyphicons no-js eye_close" href="<?php echo $this->webroot ?>prresource/gatewaygroups/pass_trusk/<?php echo array_keys_value($this->params, 'pass.0') ?>/ingress?<?php echo $appCommon->get_request_str() ?>">
            <i></i><?php echo __('PASS', true); ?>
        </a>        
    </li>
    <li  <?php if ($active_tab == 'replace_action') {
    echo "class='active'";
} ?>>
        <a  class="glyphicons no-js iphone_exchange" href="<?php echo $this->webroot ?>prresource/gatewaygroups/replace_action/<?php echo array_keys_value($this->params, 'pass.0') ?>/ingress?<?php echo $appCommon->get_request_str() ?>">
            <i></i> <?php echo __('Replace Action', true); ?>
        </a>        
    </li>
</ul>