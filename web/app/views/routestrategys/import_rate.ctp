<div id="title"><h1><?php __('Uploadroutingstrategies')?></h1>
</div>
<div class="container">
<ul class="tabs">
  <li>
  	<a href="<?php echo $this->webroot ?>routestrategys/routes_list/<?php echo $rate_table_id;?>">
  		<img width="16" height="16" src="<?php echo $this->webroot?>images/list.png"> <?php __('Routingstrategieslist')?>
  	</a>
  </li>
  <li class="active" >
  	<a href="<?php echo $this->webroot ?>rates/import_rate/<?php echo $rate_table_id?>">
  		<img width="16" height="16" src="<?php echo $this->webroot?>images/import.png"><?php __('upload')?>
  	</a>
  </li>
</ul>
  <form method="post"   enctype="multipart/form-data" action="<?php echo $this->webroot?>routestrategys/upload_code2/">
		<input type="hidden" id="id_code_decks" value="<?php echo $rate_table_id?>" name="upload_table_id" class="input in-hidden">
		<input type="hidden" id="id_code_decks" value="route" name="upload_real_table" class="input in-hidden">
		<input type="hidden" id="code_name" value="<?php echo $code_name?>" name="code_name" class="input in-hidden">
		<input type="hidden"  value="tmp_route" name="upload_table" class="input in-hidden">
			<table class="form" style="margin-left:15%;">
				<tbody>
					<tr>
					   <td class="label label2"><?php __('Strategyname')?>:</td>
					   <td class="value value2">
					    		<input type="text" style="float:left;width:300px;"  readonly="readonly" id="code" value="<?php echo $code_name;?>" name="code" class="input in-text">
					   </td>
					</tr>
					<tr>
					    <td class="label label2"><?php __("PleaseselectyouruploadCSVfiles")?>:</td>
					    <td class="value value2">
					    		<INPUT TYPE = "hidden" NAME = "UploadAction" VALUE = "1">
					 				<INPUT TYPE = "hidden" NAME = "MAX_FILE_SIZE" VALUE ="1000000000000">
									<input type="file" size="38"  name="file"     class="input in-file">
					    </td>
					</tr>
					<tr><td class="label label2"></td>
					    <td class="value value2"  style="text-align: left;">
				<input type="radio" name="upload_param" value="1"   checked="" title="<?php __('ThisoptionwillreadCSVfileeachlineoftheinsertintothedatabaseifthenumberalreadyexistswillcoverthedatabaseoriginallysomerecords')?>" class="input in-radio"/>
<span><?php __('cover')?></span>
					  
					  <input type="radio" name="upload_param" value="2" title="<?php __('SVfileanddatabaseduplicaterecordwillbeignored')?>" style="margin-left: 10px;" class="input in-radio">
					  <span><?php __('Ignorerepeat')?></span>
					  
					  	  <input type="radio" name="upload_param" value="3" title="<?php __('CSVfileanddatabaseduplicaterecordwillbeignored')?>" style="margin-left: 10px;" class="input in-radio">
					  <span><?php __('Deleteduplicate')?></span>
					  
					  		  	  <input type="radio" name="upload_param" value="4" title="<?php __('CSVfileanddatabaseduplicaterecordwillbeignored')?>" style="margin-left: 10px;" class="input in-radio">
					  <span><?php __('Wrongreturnanerrormessage')?></span>
					    </td>
					</tr>
				</tbody>
			</table>

			<div id="footer">
		  	<input type="submit" value="<?php echo __('submit')?>" class="input in-submit">
		  	<input type="reset" value="<?php echo __('reset')?>" class="input in-button">
			</div>
			</form>
</div>