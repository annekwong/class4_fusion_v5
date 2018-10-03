<?php echo $this->element("users/last_title")?>
<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-head">
<?php echo $this->element('tabs',Array('tabs'=>Array( __('System Users',true)=>Array('url'=>'users/index','active'=>true, 'icon' => 'list'), __('Carrier Users',true)=>Array('url'=>'users/show_carrier', 'icon' => 'parents'), __('Online Users',true)=>Array('url'=>'users/show_online', 'icon' => 'user'), __('Never Login Users',true)=>Array('url'=>'users/view', 'icon' => 'girl'), __('Ever Login Users',true)=>Array('url'=>'users/last_login', 'icon' => 'magic'))))?>

        </div>
        <div class="widget-body">
<?php echo $this->element('users/search')?>
<?php echo $this->element('users/list')?>
</div>
<?php return ?>
    </div>
</div>