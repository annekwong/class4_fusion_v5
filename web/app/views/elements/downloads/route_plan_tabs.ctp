<ul class="tabs">
    <li ><a href="<?php echo $this->webroot?>routestrategys/routes_list/<?php echo $id;?>"><img width="16" height="16" src="<?php echo $this->webroot?>images/menuIcon.gif"><?php echo __('List',true);?></a></li>
    <li><a href="<?php echo $this->webroot?>uploads/route_plan/<?php echo $id;?>"><img width="16" height="16" src="<?php echo $this->webroot ?>images/import.png"> <?php echo __('import',true);?></a></li> 
    <li  class="active"><a href="<?php echo $this->webroot?>downloads/route_plan/<?php echo $id;?>"><img width="16" height="16" src="<?php echo $this->webroot ?>images/export.png"> <?php echo __('export',true);?></a></li>   
</ul>