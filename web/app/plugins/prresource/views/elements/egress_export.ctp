<style type="text/css">
#down_panel label {width:80px;display:block;float:left;}
#down_panel {width:80%; margin:0 auto;}
#option_panel {float:left; width:40%;}
#field_panel {float:left;margin-left:100px; width:50%;}
.buttons {text-align:center;}
</style>

<div id="down_panel">
    <form name="myform" method="post" target="_blank" action="<?php echo $this->webroot ?>down/egress">
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
					<label><?php __('Column')?> #<?php echo $i+1; ?>:</label>
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
        <div class="buttons"><input type="submit" class="btn btn-primary" value="<?php __('Download')?>" /></div>
		<br style="clear:both;" />	
		</form>
	</div>