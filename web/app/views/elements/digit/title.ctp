<script type="text/javascript" src="<?php echo $this->webroot ?>js/jquery_002.js"></script>
<script type="text/javascript" src="<?php echo $this->webroot ?>js/jquery.js"></script>
<script type="text/javascript" src="<?php echo $this->webroot ?>js/bb-functions.js"></script>
<script type="text/javascript" src="<?php echo $this->webroot ?>js/bb-interface.js"></script>

<ul class="breadcrumb">
    <li><?php __('You are here')?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>digits/view"><?php __('Routing') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>digits/view">
        <?php echo __('Digital Maps') ?></a></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Digital Maps') ?></h4>
    
</div>
<div class="separator bottom"></div>
<?php $d = $p->getDataArray();?>
<div class="buttons pull-right newpadding">
        <?php if ($_SESSION['role_menu']['Routing']['digits']['model_w']) { ?>
        <a id="add" class="btn btn-primary btn-icon glyphicons circle_plus" href="javascript:void(0)"><i></i> <?php __('Create New')?></a>
        <?php  } ?>

    <?php if (count($d) > 0): ?>
        <?php if ($_SESSION['role_menu']['Routing']['digits']['model_w'])
        {
            ?> <a class="link_btn btn btn-primary btn-icon glyphicons remove" rel="popup" href="javascript:void(0)" onclick="deleteAll('<?php echo $this->webroot ?>digits/del_all_details');" ><i></i> <?php echo __('deleteall') ?></a>
            <a class="link_btn btn btn-primary btn-icon glyphicons remove" rel="popup" href="javascript:void(0)" onclick="deleteSelected('list_table', '<?php echo $this->webroot ?>digits/del_selected_details', 'Digital translation');"><i></i> <?php echo __('deleteselected') ?></a>
        <?php } ?>
    <?php endif; ?>

</div>
    <div class="clearfix"></div>