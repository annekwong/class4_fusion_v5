	<ul class="tabs">
	
	 <?php if($active_tab=='ingress'){?>
		<li   class='active'>
			<a href="#">
				<img width="16" height="16" src="<?php echo $this->webroot?>images/bDR.gif"><?php echo __('Ingress Trunk',true);?>
			</a>
		</li>
		
		<?php }else{?>
		<li   class='active'>
			<a href="#">
				<img width="16" height="16" src="<?php echo $this->webroot?>images/bDR.gif"><?php echo __('Egress Trunk',true);?>
			</a>
		</li>
		<?php }?>
	</ul>