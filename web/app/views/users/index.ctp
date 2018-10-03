<?php echo $this->element("users/title") ?>

<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-head">
            <?php echo $this->element('tabs',
                Array('tabs' => Array(
                    __('System Users', true) => Array('url' => 'users/index', 'icon' => 'list', 'active' => true),
                    __('Carrier Users', true) => Array('url' => 'users/show_carrier', 'icon' => 'parents'),
                    __('Online Users', true) => Array('url' => 'users/show_online', 'icon' => 'user'),
                    __('Non-Active Users', true) => Array('url' => 'users/view', 'icon' => 'girl'),
                    __('Agent List', true) => Array('url' => 'users/show_agent', 'icon' => 'list')
                ))) ?>
        </div>
        <div class="widget-body">
            <div class="filter-bar">
                <form method="get">
                    <!-- Filter -->
                    <div>
                        <label><?php __('Search') ?>:</label>
                        <input type="text" name="search" value="Search" title="<?php __('Search') ?>" class="in-search default-value input in-text defaultText in-input" id="search-_q">
                    </div>
                    <!-- // Filter END -->
                    <!-- Filter -->
                    <div>
                        <button name="submit" class="btn query_btn"><?php __('Query') ?></button>
                    </div>
                    <!-- // Filter END -->
                </form>
            </div>

            <?php echo $this->element('users/search') ?>
            <?php echo $this->element('users/list') ?>
        </div>
    </div>
</div>
