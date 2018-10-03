<style type="text/css">
	#container label {width:80px;display:block;float:left;}
	#down_panel {width:80%; margin:0 auto;}
	#option_panel {float:left; width:40%;}
	#field_panel {float:left;margin-left:100px; width:50%;}
	.buttons {text-align:center;}
</style>

<ul class="breadcrumb">
	<li><?php echo __('You are here') ?></li>
	<?php foreach ($header as $header_item): ?>
		<li class="divider"><i class="icon-caret-right"></i></li>
		<li><?php __($header_item) ?></li>
	<?php endforeach; ?>
</ul>


<div class="heading-buttons">
	<h4 class="heading">Export</h4>
	<div class="clearfix"></div>
</div>
<div class="separator bottom"></div>

<div class="innerLR">

	<div class="widget widget-tabs widget-body-white">
		<div class="widget-head">

			<ul>
				<li><a href="<?php echo $this->webroot ?>rate_generation/view_rate_result/<?php echo $this->params['pass'][0]; ?>" class="glyphicons list"><i></i> <?php echo __('List', true); ?></a></li>
				<li class="active"><a class="glyphicons download" href="<?php echo $this->webroot?>down/rate_generation_result/<?php echo $this->params['pass'][0]; ?>"><i></i> <?php echo __('Export',true);?></a></li>
			</ul>

		</div>
		<div class="widget-body">
			<div id="down_panel">
				<form name="myform" method="post">
					<div id="option_panel">
						<fieldset>
							<legend><?php __('Format Options')?></legend>
							<p>
								<label><?php __('Data Format')?>:</label>
								<select name="data_format">
									<option value="0"><?php __('CSV')?></option>
									<option value="1"><?php __('XLS')?></option>
								</select>
							</p>
							<p>
								<label>&nbsp;</label>
								<input type="checkbox" name="with_header" checked="checked" /><?php __('With headers row')?>
							</p>
							<p>
								<label><?php __('Header Text')?>:</label>
								<textarea rows="3" cols="10" name="header_text" style="width:220px;"></textarea>
							</p>
							<p>
								<label><?php __('Footer Text')?>:</label>
								<textarea rows="3" cols="10" name="footer_text" style="width:220px;"></textarea>
							</p>

						</fieldset>
					</div>
					<div  id="field_panel">
						<fieldset>
							<legend><?php __('Columns')?></legend>
							<?php
							$size = count($fields);
							for ($i = 0; $i < $size; $i++):
								?>
								<p>
									<label><?php __('Column')?>#<?php echo $i+1; ?>:</label>
									<select name="fields[]">
										<option></option>
										<?php for ($j = 0; $j < $size; $j++): ?>
											<option <?php echo $j == $i ? 'selected' : '' ?>><?php echo $fields[$j]; ?></option>
										<?php endfor; ?>
									</select>
								</p>
							<?php endfor; ?>
						</fieldset>
					</div>
					<br style="clear:both;" />
					<div class="buttons"><input class="btn btn-primary" type="submit" value="<?php __('Download')?>" /></div>
				</form>
			</div>
		</div>
	</div>
</div>