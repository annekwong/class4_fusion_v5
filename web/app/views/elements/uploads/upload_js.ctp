<table class="cols" style="width: 481px;"  >
	<?php if (isset ( $statistics ) && $statistics) :	?>
		<caption><?php echo __('Upload Statistics',true);?></caption>
		<?php foreach(array('success','failure','duplicate') as $col):?>
			<?php if(isset($statistics[$col])):?>
				<tr>
					<td style="text-align:right;padding-right:4px;"><?php echo Inflector::humanize($col)?>:</td>
					<td style="text-align:left;color:red;"><?php echo $statistics[$col]?></td>
				</tr>
			<?php endif;?>
		<?php endforeach;?>
		<?php if(	isset($statistics['failure']) && $statistics['failure'] > 0 && 
					isset($statistics['error_file']) && !empty($statistics['error_file']) &&
					isset($statistics['log_id']) && $statistics['log_id'] > 0
					 ):?>
			<tr>
				<td style="text-align:right;padding-right:4px;"><?php echo Inflector::humanize("error_file")?>:</td>
				<td style="text-align:left;"><a href="<?php echo $this->webroot?>uploads/download_error_file/<?php echo $statistics['log_id']?>"><?php echo __('download',true);?></a></td>
			</tr>
		<?php endif;?>
		<tr><td>&nbsp;</td><td></td></tr>
		<tr><td>&nbsp;</td><td></td></tr>
	<?php endif;?>
	
	</table>