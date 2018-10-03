<div id="title">
	<h1><span><?php echo __('upload',true);?></span><?php echo Inflector::humanize($this->params['action'])?></h1>
	<form method="GET" action="">
	<ul id="title-menu">
		<?php if(isset($back_url) && !empty($back_url)):?>
		<li><a href="<?php echo $back_url?>">		
		<img heigh="16" width="16" src ="<?php echo $this->webroot?>images/rerating_queue.png" ?></img>
		&nbsp; <?php echo __('goback',true);?></a></li>
		<?php endif;?>	
	</ul>
	<ul id="title-search">		
	</ul>
	</form>
</div>
<div id="container">
<?php echo $this->element('uploads/'.$this->params['action'].'_tabs')?>
   
<?php if (isset ( $exception_msg ) && $exception_msg) :	?>
	<?php	echo $this->element ( 'common/exception_msg' );?>		
<?php endif;?>
<form action="" method="POST" enctype="multipart/form-data">
<div  id="static_div"   style="text-align: left; width: 530px;">
<table class="cols" style="width: 252px; margin: 0px auto;"  >
	<?php if (isset ( $statistics ) && $statistics) :	?>
		<caption><?php echo __('Upload Statistics',true);?>    
		
		<span style="color: red;;font-size:11px;"> </span>
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
	</div>
	<table class="cols" style="width:700px;margin:0px auto;">
	<tr>
		<td style="text-align:right;padding-right:4px;"><?php echo __('Import File',true);?>:</td>
		<td style="text-align:left;"><input type="file" name="file" /></td>
	</tr>
	<tr>
		<td style="text-align:right;padding-right:4px;"><?php echo __('Duplicate',true);?>:</td>
		<td style="text-align:left;">
			<input type="radio" name="duplicate_type" value="ignore" id="duplicate_type_ignore" checked="checked"/>
			<label for="duplicate_type_ignore"><?php echo __('Ignore',true);?></label>			  
			<input type="radio" name="duplicate_type" value="overwrite" id="duplicate_type_overwrite"/>
			<label for="duplicate_type_overwrite"><?php echo __('Overwrite',true);?></label>			  
			<input type="radio" name="duplicate_type" value="delete" id="duplicate_type_delete" />
			<label for="duplicate_type_delete"><?php echo __('delete',true);?></label>
		</td>
	</tr>
	<tr>
  		<td style="text-align:right;padding-right:4px;"></td><!--
		<td style="text-align:left;">
			<input type="checkbox" name="with_headers" checked="checked"/>
			<span>With headers row</span>
    	</td>
	--></tr><!--
	<tr>
  		<td style="text-align:right;padding-right:4px;"></td>
		<td style="text-align:left;">
			<input type="checkbox" name="rollback_on_error"/>
			<span>Rollback on error</span>
    	</td>
	</tr>
	-->
    <?php  if ($_SESSION['role_menu']['Switch']['jurisdictionprefixs']['model_x']) {?>
    <tr>
  		<td colspan="2"><?php echo $form->submit('upload')?></td>
	</tr>
    <?php }?>
</table>
</form>
</div>

<?php

if(!empty($statistics['log_id'])){?>
<script  type="text/javascript">
	(function(div_id,status){
  var _div_id=$(div_id);
  var _status=0;
  var _timeoutHander = null;

 
  var  test=function (){
	  _timeoutHander = setTimeout(doStartCap,2000);
	 } 
	var doStartCap = function (){

		$.post('<?php echo $this->webroot?>uploads/get_upload_log?id=<?php echo $statistics['log_id'];?>',{},
			function(data){
			var s=data.substring(0,1);
			if(/\d/.test(s)){
				_div_id.html(data.substring(1));
					if(s==6){
					clearTimeout(_timeoutHander);

					}
				}
			 _timeoutHander = setTimeout(doStartCap,2000);

			}
		);
		
	}


	jQuery(document).ready(doStartCap);     

	
})('#static_div','#upload_status');


</script>

<?php }?>