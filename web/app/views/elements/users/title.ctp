<?php $write = $_SESSION['role_menu']['Configuration']['users']['model_w']; ?>

<?php
/*
<div id="title">
  <h1><?php __('Configuration')?>&gt;&gt;<?php echo __('usermanager')?>

  </h1>
	<?php echo $this->element('search')?>
	<ul id="title-menu">
	<?php  if ($_SESSION['role_menu']['Configuration']['users']['model_w']) {?>
<!--			<li>
			<a class="link_btn" href="<?php echo $this->webroot?>users/add_carrier_user">
				<img width="16" height="16" src="<?php echo $this->webroot?>images/add.png" alt=""/><?php echo __('Create Carrrier User',true);?>
			</a>
      	</li>
	-->

		<li>
			<a  class="link_btn"href="<?php echo $this->webroot?>users/add">
				<img width="16" height="16" src="<?php echo $this->webroot?>images/add.png" alt=""/><?php echo __('Create Users',true);?>
			</a>
      	</li>
        <?php }?>
		<?php if($this->params['hasGet']){?>
    	<li>
    		<a class="link_back" href="<?php echo $this->webroot?>roles/view">
    			<img width="16" height="16"  alt="" src="<?php echo $this->webroot?>images/icon_back_white.png">&nbsp;<?php echo __('gobackall')?>
    		</a>
    	</li>
    <?php }?>
	</ul>
</div>
*/ ?>

<ul class="breadcrumb">
    <li><?php __('You are here'); ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>users/index">
        <?php __('Configuration') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>users/index">
    <?php echo __('usermanager', true); ?></a></li>
</ul>


<div class="heading-buttons">
    <h4 class="heading"><?php echo __('usermanager', true); ?></h4>

</div>
<?php if ($write) { ?>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
        <a class="btn btn-primary btn-icon glyphicons circle_plus" href="<?php echo $this->webroot?>users/add"><i></i> <?php __('Create New')?></a>
    </div>
<?php }?>
    <div class="clearfix"></div>