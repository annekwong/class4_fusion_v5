<ul class="tabs">
    <li><a class="glyphicons list"  href="<?php echo $this->webroot ?>digits/translation_details/<?php echo base64_encode($id)?>"><i></i> <?php echo __('List',true);?></a></li>

    <li class="active"><a  class="glyphicons upload" href="<?php echo $this->webroot ?>uploads/digit_translation/<?php echo $id?>"><i></i> <?php echo __('Import',true);?></a></li> 
    <li ><a class="glyphicons download" href="<?php echo $this->webroot ?>down/digit_mapping_down/<?php echo $id?>"><i></i> <?php echo __('Export',true);?></a></li>   
</ul>