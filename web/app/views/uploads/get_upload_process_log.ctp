
					<?php $list=$appImportExportLog->display_upload_tip();
									if(!empty($list)){
					?>
<table   style="width: 352px; height: 100px;">
									<tbody>

				<tr>
					<td style="text-align: left; padding-right: 4px;"> <?php echo __('Upload Object',true);?></td>
					<td style="text-align: left; "><?php echo __('Process',true);?>  %</td>
					<td style="text-align: center; padding-right: 4px;"><?php echo __('Process level',true);?></td>
				</tr>				
									
			<?php 
					foreach ($list as $key=>$value){
					?>				
					<tr>
					<td style="text-align: left; padding-right: 4px;"> <?php echo $value['ImportExportLog']['obj']?></td>
					<td style="text-align: left; color: red;">
					
					<div class="bar"><div style=" color: red;font-size: 0.6em; width: <?php echo $appImportExportLog->display_status__per($value['ImportExportLog']['status'])?>;"><?php echo $appImportExportLog->display_status__per($value['ImportExportLog']['status'])?>&nbsp;</div></div>
					
					
					</td>
					<td style="text-align: right; padding-right: 4px;color: #FF6D06"><?php echo $appImportExportLog->display_status($value['ImportExportLog']['status'],$value['ImportExportLog']['error_file_path'],$value['ImportExportLog']['db_error_file_path'])?></td>
				</tr>
				<?php }?>

	
									<tr><td>&nbsp;  <input type="hidden" value="6" id="upload_status"></td><td></td></tr>
		<tr><td>&nbsp;</td><td></td></tr>
		
	</tbody>
	
	</table>
	<?php }else{  echo __('No events upload');}?>