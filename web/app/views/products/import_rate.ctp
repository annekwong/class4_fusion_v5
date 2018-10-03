<div id="title">
  <h1><?php echo __('Upload Route Table',true);?></h1>
  <ul id="title-menu">
     <li>
  	 			<a class="link_back" href="<?php echo $this->webroot?>products/product_list">
  	 			<img width="10" height="5" src="<?php echo $this->webroot?>images/icon_back_white.png" alt="">
  	 			<?php echo __('goback',true);?>  	 		</a>
  	 </li>
		 <li><a class="link_btn" href="<?php echo $this->webroot?>products/import_rate/39"><img width="16" height="16" src="<?php echo $this->webroot?>images/import.png"> <?php echo __('upload',true);?></a></li>
		 <li><a class="link_btn" href="<?php echo $this->webroot?>products/download_rate/39"><img width="10" height="5" src="<?php echo $this->webroot?>images/export.png" alt=""><?php echo __('download',true);?></a></li>
    <li><a class="link_btn"href="<?php echo $this->webroot?>products/add_route/39"><img width="16" height="16" src="<?php echo $this->webroot?>images/add.png" alt=""><?php echo __('createnew',true);?></a></li>
  </ul>
</div>
<div class="container">
<ul class="tabs">
   <li><a href="<?php echo $this->webroot?>rates/r_rates_list/<?php echo $rate_table_id;?>"><img width="16" height="16" src="<?php echo $this->webroot?>images/list.png"> <?php echo __('Route',true);?></a></li>
   <li class="active" ><a href="<?php echo $this->webroot?>products/import_rate/<?php echo $rate_table_id?>"><img width="16" height="16" src="<?php echo $this->webroot?>images/import.png"> <?php __('upload')?></a>        </li>
</ul>
	<form method="post"   enctype="multipart/form-data" action="<?php echo  $this->webroot?>products/upload_code2/">
       		
       		
		<input type="hidden" id="id_code_decks" value="<?php echo $rate_table_id?>" name="upload_table_id" class="input in-hidden">
		<input type="hidden" id="id_code_decks" value="product_items" name="upload_real_table" class="input in-hidden">
			<input type="hidden" id="code_name" value="<?php echo $code_name?>" name="code_name" class="input in-hidden">
			
			
				
				
					<input type="hidden"  value="tmp_product_items" name="upload_table" class="input in-hidden">
			<table class="form" style="margin-left:15%;">
				<tbody>
					<tr>
					   <td class="label label2"><?php __('Route Table')?></td>
					   <td class="value value2">
					    		<input type="text" style="float:left;width:300px;"  readonly="readonly" id="code" value="<?php echo $code_name;?>" name="code" class="input in-text">
					   </td>
					</tr>
					<tr>
					    <td class="label label2"><?php echo __('Select file to import',true);?>:</td>
					    <td class="value value2">
							    <INPUT TYPE = "hidden" NAME = "UploadAction" VALUE = "1">
							 			<INPUT TYPE = "hidden" NAME = "MAX_FILE_SIZE" VALUE ="1000000000000">
										<input type="file" size="38"  name="file"     class="input in-file">
					    </td>
					</tr>
					<tr>
					    <td class="label label2"></td>
					    <td class="value value2"  style="text-align: left;">
								<input type="radio" name="upload_param" value="1"   checked=""  class="input in-radio"/>
								<span><?php __('overwrite')?></span>
					  
					  <input type="radio" name="upload_param" value="2" style="margin-left: 10px;" class="input in-radio">
					  <span><?php __('remove')?></span>
					  
					  	  <input type="radio" name="upload_param" value="3"  style="margin-left: 10px;" class="input in-radio">
					  <span><?php __('delete_duplicate')?></span>
					  
					  		  	  <input type="radio" name="upload_param" value="4" style="margin-left: 10px;" class="input in-radio">
					  <span><?php __('return_error_info')?></span>
					    </td>
					</tr>
				</tbody>
			</table>
			<div id="form_footer">
		  	<input type="submit" value="<?php echo __('submit')?>" class="input in-submit">
		  	<input type="reset" value="<?php echo __('reset')?>" class="input in-button">
			</div>
			</form>
</div>