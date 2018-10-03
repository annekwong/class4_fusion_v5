
<div id="title">
  <h1><?php echo __('supplement')?>&gt;&gt;
    	<?php echo __('Fast money channel')?>
  </h1>
</div>
<div id="container">
	<center>
			<form action="<?php echo $this->webroot?>nnbills/send" method="post">
					<div style="margin-top:10px;">
						<?php echo __('Recharge amount')?>:<input class="input in-text" id="amount" name="amount"/>
						<input type="submit" value="<?php echo __('submit')?>"/>
					</div>
			</form>
	</center>
</div>
