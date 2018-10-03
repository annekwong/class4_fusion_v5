<script type="text/javascript" src="<?php echo $this->webroot?>js/jquery_002.js"></script>

<ul class="breadcrumb">
    <li><?php echo __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>jurisdictionprefixs/view">
        <?php __('Switch') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>jurisdictionprefixs/view">
        <?php echo __('Jurisdiction') ?></a></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Jurisdiction') ?></h4>
    
</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
        <?php  if ($_SESSION['role_menu']['Switch']['jurisdictionprefixs']['model_w']) { ?>
            <?php echo $this->element("createnew",Array('url'=>'javascript:addItem()','jsAdd'=>true))?>
            <a class="btn btn-primary btn-icon glyphicons remove"onclick="deleteAll('<?php echo $this->webroot ?>jurisdictionprefixs/del_all_jur');" href="javascript:void(0)" rel="popup">
                <i></i> <?php __('Delete All')?>
            </a>
            <a class="btn btn-primary btn-icon glyphicons remove" onclick="deleteSelected('tabid','<?php echo $this->webroot ?>jurisdictionprefixs/del_selected_jur','jurisdiction');" href="javascript:void(0)" rel="popup">
                <i></i> <?php echo __('Delete Selected',true);?>
            </a>
        <?php } ?>
    </div>
    <div class="clearfix"></div>