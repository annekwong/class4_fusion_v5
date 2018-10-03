<ul class="tabs">
    <li     <?php if ($active_tab == 'base') {
    echo "class='active'";
} ?> >
        <a class="glyphicons no-js circle_info" href="<?php echo $this->webroot ?>prresource/gatewaygroups/edit_resouce_egress/<?php echo array_keys_value($this->params, 'pass.0') ?>?<?php echo $appCommon->get_request_str() ?>">
            <i></i><?php __('System Information') ?>
        </a>
    </li>

     <li     <?php if ($active_tab == 'limit') {
        echo "class='active'";
    } ?> >
            <a class="glyphicons no-js circle_info" href="<?php echo $this->webroot ?>prresource/gatewaygroups/edit_egress_ingress_limit/<?php echo array_keys_value($this->params, 'pass.0') ?>?<?php echo $appCommon->get_request_str() ?>">
                <i></i><?php __('Limit') ?>
            </a>
        </li>


    <li  <?php if ($active_tab == 'action') {
    echo "class='active'";
} ?>>
        <a class="glyphicons no-js train" href="<?php echo $this->webroot ?>prresource/gatewaygroups/add_direction/<?php echo array_keys_value($this->params, 'pass.0') ?>?<?php echo $appCommon->get_request_str() ?>">
            <i></i><?php __('Action') ?>
        </a>
    </li>

    <li  <?php if ($active_tab == 'rule') {
    echo "class='active'";
} ?>>
        <a class="glyphicons no-js ruller"  href="<?php echo $this->webroot ?>prresource/gatewaygroups/add_rule/<?php echo array_keys_value($this->params, 'pass.0') ?>/egress?<?php echo $appCommon->get_request_str() ?>">
            <i></i><?php echo __('Fail-over Rule',true);?>
        </a>
    </li>
    <!--
    <li  <?php if ($active_tab == 'autorate') {
    echo "class='active'";
} ?>>
        <a  class="glyphicons no-js usd"  href="<?php echo $this->webroot ?>prresource/gatewaygroups/automatic_rate_processing/<?php echo array_keys_value($this->params, 'pass.0') ?>/egress?<?php echo $appCommon->get_request_str() ?>">
            <i></i><?php echo __('Automatic Rate Processing',true);?>
        </a>
    </li>
    -->
    <li  <?php if ($active_tab == 'pass_trusk') {
    echo "class='active'";
} ?>>
        <a  class="glyphicons no-js eye_close" href="<?php echo $this->webroot ?>prresource/gatewaygroups/pass_trusk/<?php echo array_keys_value($this->params, 'pass.0') ?>/egress?<?php echo $appCommon->get_request_str() ?>">
            <i></i><?php echo __('PASS',true);?>
        </a>
    </li>
    <li  <?php if ($active_tab == 'billing') {
        echo "class='active'";
    } ?>>
        <a  class="glyphicons no-js notes" href="<?php echo $this->webroot ?>prresource/gatewaygroups/billing/<?php echo array_keys_value($this->params, 'pass.0') ?>/egress?<?php echo $appCommon->get_request_str() ?>">
            <i></i><?php echo __('Billing',true);?>
        </a>
    </li>
    <li  <?php if ($active_tab == 'sip_profile') {
    echo "class='active'";
} ?>>
        <a class="glyphicons no-js unshare" href="<?php echo $this->webroot ?>prresource/gatewaygroups/sip_profile/<?php echo array_keys_value($this->params, 'pass.0') ?>/egress?<?php echo $appCommon->get_request_str() ?>">
            <i></i><?php echo __('SIP Profile',true);?>
        </a>
    </li>
    <li  <?php if ($active_tab == 'replace_action') {
    echo "class='active'";
} ?>>
        <a  class="glyphicons no-js eyedropper" href="<?php echo $this->webroot ?>prresource/gatewaygroups/replace_action/<?php echo array_keys_value($this->params, 'pass.0') ?>/egress?<?php echo $appCommon->get_request_str() ?>">
            <i></i><?php echo __('Replace Action',true);?>
        </a>
    </li>
    <li  <?php if ($active_tab == 'dynamic_routing') {
    echo "class='active'";
} ?>>
        <a  class="glyphicons no-js router" href="<?php echo $this->webroot ?>prresource/gatewaygroups/dynamicroutes/<?php echo array_keys_value($this->params, 'pass.0') ?>/egress?<?php echo $appCommon->get_request_str() ?>">
            <i></i><?php echo __('Dynamic Routing',true);?>
        </a>
    </li>
    <li  <?php if ($active_tab == 'staticroutes') {
    echo "class='active'";
} ?>>
        <a  class="glyphicons no-js router" href="<?php echo $this->webroot ?>prresource/gatewaygroups/staticroutes/<?php echo array_keys_value($this->params, 'pass.0') ?>/egress?<?php echo $appCommon->get_request_str() ?>">
            <i></i><?php echo __('Static Routing',true);?>
        </a>
    </li>
    <li <?php if ($active_tab == 'ingress_capacity') echo "class='active'"; ?>>
        <a  class="glyphicons no-js fire" href="<?php echo $this->webroot ?>prresource/gatewaygroups/ingress_capacity/<?php echo array_keys_value($this->params, 'pass.0') ?>/egress?<?php echo $appCommon->get_request_str() ?>">
            <i></i><?php echo __('Ingress Capacity',true);?>
        </a>
    </li>


</ul>