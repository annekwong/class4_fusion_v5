	<ul class="tabs">
		<li  <?php if($active_tab=='ingress'){ echo "class='active'";  }?>>
			<a href="<?php echo $this->webroot?>monitorsreports/carrier2/ingress">
				<img width="16" height="16" src="<?php echo $this->webroot?>images/bDR.gif"><?php echo __('Ingress Carriers',true);?>
			</a>
		</li>
		<li   <?php if($active_tab=='egress'){ echo "class='active'";  }?>>
			<a href="<?php echo $this->webroot?>monitorsreports/carrier2/egress">
				<img width="16" height="16" src="<?php echo $this->webroot?>images/bDR.gif"><?php echo __('Egress Carriers',true);?>
			</a>
		</li>
	</ul>