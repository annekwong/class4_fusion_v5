
<div id="cover"></div>
<div id="title">
  <h1><?php echo __('systemc')?>&gt;&gt;
    	<?php echo __('ivrmanager')?>
  </h1>
</div>

<div id="container">

<div id="uploadivr"  style="display:none;height: auto;z-index:99;position: absolute;left:30%;top: 20%;" class="form_panel_upload">
			    <form action="<?php echo $this->webroot?>ivrs/upload_ivr" method="post" enctype="multipart/form-data" id="productFile">
			    <input type="hidden" value="" name="type" id="uploadType"/>	
			    <span class="wordFont1 marginSpan1"><?php echo __('selectfile')?>:</span>
			    <div style="height:60px;" class="up_panel_upload">
			    <input style="margin-top:10px;" type="file" <?php echo __('upload',true);?> size="45" class="input in-text" id="browse" name="browse">
			    </div>
			    <div class="form_panel_button_upload">
			    			<input type="submit" class="input in-button" value="<?php echo __('upload')?>"/>
			    			<input type="button" onclick="closeCover('uploadivr')" style="margin-bottom:6px;" class="input in-button" value="<?php echo __('cancel')?>"/>
			    </div>  
			    </form>
		    </div>
		    
		    
		    
<table class="list">
	<col style="width: 30%;">
	<col style="width: 35%;">
	<col style="width: 35%;">
	<thead>
		<tr>
		<td><?php __('ID')?></td>
		<td><?php echo __('description')?></td>
   <td><?php echo __('action')?></td>
		</tr>
	</thead>
	<tbody id="rec_strategy">
		<?php 
			$mydata =$ivrs;
			$loop = count($mydata); 
			for ($i=0;$i<$loop;$i++) {?>
				<tr class="row-1">
				<td class="in-decimal" style="text-align:center"><?php echo $mydata[$i][0]['ivr_id']?></td>
		    <td style="font-weight: bold;"><?php echo $mydata[$i][0]['description']?></td>
		    <td>
		    			<a title="<?php echo __('sq')?>" href="javascript:void(0)" onclick="document.getElementById('uploadType').value='<?php echo $mydata[$i][0]['type']?>';cover('uploadivr');" style="float:left;margin-left:40%;"><img  src="<?php echo $this->webroot?>images/ico_upload.gif" alt="<?php echo __('sq')?>"></a>
		    			<?php if (!empty($mydata[$i][0]['filename'])) {?>
		    					<a title="<?php echo __('xz')?>" href="<?php echo $this->webroot?>ivrs/download_ivr?filename=<?php echo $mydata[$i][0]['filename']?>" style="float:left;margin-left:25px;"><img  src="<?php echo $this->webroot?>images/ico_download.gif" alt="<?php echo __('xz')?>"/></a>
		    			<?php } else {?>
		    					<span style="color:green">您尚未上传该语音.</span>(支持格式:wav)
		    			<?php }?>
		    </td>
				</tr>
		<?php }?>		
	</tbody>
	<tbody>
</tbody>
</table>
</div>
<div id="livemargins_control" style="position: absolute; display: none; z-index: 9999;"><img width="77" height="5" style="position: absolute; left: -77px; top: -5px;" src="chrome://livemargins/skin/monitor-background-horizontal.png">	<img style="position: absolute; left: 0pt; top: -5px;" src="chrome://livemargins/skin/monitor-background-vertical.png">	<img style="position: absolute; left: 1px; top: 0pt; opacity: 0.5; cursor: pointer;" onmouseout="this.style.opacity=0.5" onmouseover="this.style.opacity=1" src="chrome://livemargins/skin/monitor-play-button.png" id="monitor-play-button"></div>
