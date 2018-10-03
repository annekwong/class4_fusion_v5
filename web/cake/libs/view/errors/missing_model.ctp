<div class="container">
	<?php echo $model; ?>
	<!-- Box -->
	<div class="hero-unit well">
		<h1 class="padding-none">
			<?php
			$logo_path = APP . 'webroot' . DS . 'upload' . DS . 'images' . DS . 'logo.png';

			if (file_exists($logo_path)) {
				$logo = $this->webroot . 'upload/images/logo.png';
			} else {
				$logo = $this->webroot . 'images/logo.png';
			}
			?>
			<img src="<?php echo $logo ?>" alt=""/>
			Ouch! <span>404 error</span></h1>
		<hr class="separator" />
		<!-- Row -->
		<div class="row-fluid">

			<!-- Column -->
			<div class="span6">
				<div class="center">
					<p>It seems the page you are looking for is not here anymore. The page might have moved to another address or just removed by our staff.</p>
				</div>
			</div>
			<!-- // Column END -->

			<!-- Column -->
			<div class="span6">
				<div class="center">
					<?php if($session->read('sst_user_id')): ?>
						<p><?php __('Is this a serious error') ?>?<a href="#myModal_support" data-toggle="modal"><?php __('Let us know') ?></a></p>
					<?php endif; ?>
					<div class="row-fluid">
						<div class="span6">
							<a href="<?php echo $this->webroot; ?>homes/landing_page" class="btn btn-icon-stacked btn-block btn-success glyphicons user_add"><i></i><span>Go back to</span><span class="strong"><?php __('Landing Page'); ?></span></a>
						</div>
						<div class="span6">
							<a href="https://support.denovolab.com" target="_blank" class="btn btn-icon-stacked btn-block btn-danger glyphicons circle_question_mark"><i></i><span>Browse through our</span><span class="strong">Support Centre</span></a>
						</div>
					</div>
				</div>
			</div>
			<!-- // Column END -->

		</div>
		<!-- // Row END -->

	</div>
	<!-- // Box END -->

</div>
<script type="text/javascript">
	$(function(){
		$('.navbar').hide();
	})
</script>