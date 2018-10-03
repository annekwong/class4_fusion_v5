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
        <?php echo __('Edit Client') . " [" .$post['Client']['name'] . "]" ?></a></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading">Client</h4>
    
</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
    <?php if($_SESSION['login_type'] == 2): ?>
        <a class="btn btn-icon btn-inverse glyphicons circle_arrow_left" href="<?php echo $this->webroot?>agent_portal/client_list"><i></i> <?php __('Back')?></a>
    <?php else: ?>
        <a class="btn btn-icon btn-inverse glyphicons circle_arrow_left" href="<?php echo $this->webroot?>clients/index"><i></i> <?php __('Back')?></a>
    <?php endif; ?>
    </div>
    <div class="clearfix"></div>

