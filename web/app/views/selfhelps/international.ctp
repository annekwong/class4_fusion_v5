
<div id="container">
		<?php
			if ($status == 2){ 
		?>
				<div class="msg"><?php echo __('caninternaldd')?></div>
		<?php 
			} else if ($status == 3){
		?>
				<div class="msg">
					<?php echo __('caninternalddno')?>
					<a href="<?php echo $this->webroot?>selfhelps/cmd_international" onclick="return confirm('您确定要申请吗?')">申请</a>
				</div>
		<?php
			} else {
		?>
			<div class="msg"><?php echo __('caninternalddno')?><span style="color:red">申请中</span></div>
		<?php
			} 
		?>
</div>	