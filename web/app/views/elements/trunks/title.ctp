<div id="title">
  <h1><?php __('Routing')?>&gt;&gt;<?php echo __('All Trunk',true);?></h1>
	<?php echo $this->element("search")?>
  <ul id="title-menu">
      
			<li>
				<a class="link_btn" href="<?php echo $this->webroot?>uploads/egress"><img width="10" height="5" src="<?php echo $this->webroot?>images/import.png" alt=""/><?php echo __('import',true);?></a>
			</li>
			<li>
				<a class="link_btn" href="<?php echo $this->webroot?>downloads/egress"><img width="10" height="5" src="<?php echo $this->webroot?>images/export.png" alt=""/><?php echo __('export',true);?></a>
			</li>
			<li>
				<a class="link_btn" href="<?php echo $this->webroot?>gatewaygroups/add_resouce_egress">
       <img width="16" height="16" src="<?php echo $this->webroot?>images/add.png" alt=""/><?php echo __('Add trunk',true);?>      </a>
     </li>
     		
          		     <li>
     	<a class="link_btn" onclick="deleteSelected('container','<?php echo $this->webroot?>gatewaygroups/del_selected?type=view_egress');" href="javascript:void(0)" rel="popup">
        <i class="icon-remove"></i> <?php echo __('Delete Selected',true);?>      </a>
      
     </li>
   </ul>
</div>