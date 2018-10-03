
<ul class="tabs">
    <li class="active"><a class="glyphicons list" href="<?php echo $this->webroot ?>blocklists/index"><i></i> <?php echo __('List', true); ?></a></li>
    <?php if ($_SESSION['role_menu']['Routing']['blocklists']['model_w']) { ?>
        <li><a class="glyphicons upload" href="<?php echo $this->webroot ?>uploads/block_list"><i></i><?php echo __('Import', true); ?></a></li> 
        <li><a class="glyphicons download" href="<?php echo $this->webroot ?>down/block"><i></i> <?php echo __('Export', true); ?></a></li>  
    <?php } ?> 
</ul>
