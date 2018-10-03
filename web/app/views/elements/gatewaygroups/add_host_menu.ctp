<ul class="tabs">
	<li>
	<?php if($_GET['gress']=='egress'){?>
    	<a href="<?php echo $this->webroot ?>gatewaygroups/edit_resouce_egress?<?php echo $this->params['getUrl'] ?>">
    		<img width="16" height="16" src="<?php echo $this->webroot ?>images/list.png"/><?php __('System Information')?>
    	</a>
    	<?php }else{?>
    	
    	    	<a href="<?php echo $this->webroot ?>gatewaygroups/edit_resouce_ingress?<?php echo $this->params['getUrl'] ?>">
    		<img width="16" height="16" src="<?php echo $this->webroot ?>images/list.png"/><?php __('System Information')?>
    	</a>
    	
    	
    	<?php }?>
	</li>
	<li  class="active">
		<a href="<?php echo $this->webroot?>gatewaygroups/add_host?<?php echo $this->params['getUrl'] ?>"> 
			<img width="16" height="16" src="<?php echo $this->webroot?>images/menuIcon.gif"/><?php __('HOST')?>
		</a>
	</li>
    <li>
    	<a href="<?php echo $this->webroot?>gatewaygroups/add_direction?<?php echo $this->params['getUrl'] ?>">
    		<img width="16" height="16" src="<?php echo $this->webroot?>images/bDR.gif"/><?php __('Action')?>
    	</a>
    </li>
    

    	<?php if($_GET['gress']=='ingress'){?>
    <li>
    	<a href="<?php echo $this->webroot?>gatewaygroups/add_translation_time?<?php echo $this->params['getUrl'] ?>">
    		<img width="16" height="16" src="<?php echo $this->webroot?>images/bDR.gif"/><?php __('DigitMapping')?>
    	</a>
    </li>
<?php }?>

</ul>