<?php echo $this->element("users/current_title") ?>
<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-head">
            <?php echo $this->element('tabs',
                Array('tabs' => Array(
                    __('System Users', true) => Array('url' => 'users/index', 'icon' => 'list'),
                    __('Carrier Users', true) => Array('url' => 'users/show_carrier', 'icon' => 'parents'),
                    __('Online Users', true) => Array('url' => 'users/show_online', 'icon' => 'user', 'active' => true),
                    __('Non-Active Users', true) => Array('url' => 'users/view', 'icon' => 'girl'),
                    __('Agent List', true) => Array('url' => 'users/show_agent', 'icon' => 'list')
                ))) ?>
        </div>
        <div class="widget-body">
            <?php echo $this->element('users/search') ?>
            <?php echo $this->element('users/list', Array('n_last_login_time' => true)) ?>
        </div>
    </div>
</div>