<?php echo  $statistics['status']?> 
 <table class="cols" style="width: 252px; margin: 0px auto;"  >
	<?php if (isset ( $statistics ) && $statistics) :	?>
		<caption><?php echo __('Upload Statistics',true);?>  
		<span  style='color: red;font-size:11px;'>
		<?php echo  $appImportExportLog->display_status($statistics['status'],$statistics['error_file'],$statistics['db_error_file']);?>
		</span>
</caption>
		<?php foreach(array('success','failure','duplicate') as $col):?>
			<?php if(isset($statistics[$col])):?>
				<tr>
					<td style="text-align:right;padding-right:4px;"><?php echo Inflector::humanize($col)?>:</td>
					<td style="text-align:left;color:red;"><?php echo $statistics[$col]?></td>
				</tr>
			<?php endif;?>
		<?php endforeach;?>
		<?php if(	isset($statistics['failure']) && $statistics['failure'] > 0 && 
					isset($statistics['log_id']) && $statistics['log_id'] > 0
					 ):?>
		<?php if(isset($statistics['error_file']) && !empty($statistics['error_file'])){?>
			<tr>
				<td style="text-align:right;padding-right:4px;"><?php echo Inflector::humanize("error_file")?>:</td>
                                <td style="text-align:left;"><a target="_blank" href="<?php echo $this->webroot?>uploads/download_error_file/<?php echo base64_encode($statistics['log_id']);?>"><?php echo __('download',true);?> </a></td>
			</tr>
			<?php }?>
			
					<?php if(isset($statistics['db_error_file']) && !empty($statistics['db_error_file'])){?>
			<tr>
				<td style="text-align:right;padding-right:4px;"><?php echo Inflector::humanize("db_error_file")?>:</td>
				<td style="text-align:left;"><a target="_blank" href="<?php echo $this->webroot?>uploads/download_db_error_file/<?php echo $statistics['log_id']?>"><?php echo __('download',true);?> </a></td>
			</tr>
			<?php }?>
			
		<?php endif;?>
		<tr><td>&nbsp;  <input type="hidden"  id="upload_status"  value="<?php echo  $statistics['status']?>"></td><td></td></tr>
		<tr><td>&nbsp;</td><td></td></tr>
	<?php endif;?>
	
	</table>
	
