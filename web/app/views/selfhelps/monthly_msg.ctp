
<div id="container">
		<?php
			if (isset($cmd)){ 
		?>
				<div class="msg"><?php echo __('monthlyalready')?></div>
		<?php 
			} else {
		?>
				<div class="msg">
					<?php echo __('nomonthlymsg')?>
					<a href="<?php echo $this->webroot?>selfhelps/monthly_msg/cmd" onclick="return confirm('您确定要申请吗?')">申请</a>
				</div>
		<?php
			} 
		?>
</div>	
