<ul class="tabs">
    <li><a href="<?php echo $this->webroot ?>products/route_info/<?php echo $id?>" class="glyphicons list" ><i></i> <?php echo __('List',true);?></a></li>
    <li ><a href="<?php echo $this->webroot ?>uploads/static_route/<?php echo $id?>"   class="glyphicons upload"><i></i> <?php echo __('Import',true);?></a></li> 
    <li  class="active"><a href="<?php echo $this->webroot ?>downloads/product_item/<?php echo $id?>"  class="glyphicons download"><i></i> <?php echo __('Export',true);?></a></li>   
</ul>