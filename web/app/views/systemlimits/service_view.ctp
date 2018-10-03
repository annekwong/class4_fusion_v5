<script src="<?php echo $this->webroot?>js/ajaxTable.js" type="text/javascript"></script>
<div id="cover"></div> 
<div id="title">
       <h1> 游戏和铃声下载设置 </h1>
        

    
<ul id="title-search"></ul>
    
        <ul id="title-menu">

<li><a     href="<?php echo $this->webroot?>systemlimits/service_download"    style="width: 185px;">
       <img width="16" height="16" alt="" src="<?php echo $this->webroot?>images/add.png">添加游戏和铃声下载设置 </a></li>
     
   

</ul>
        

    </div>

<div id="container">





<dl id="addproduct" class="tooltip-styled" style="display:none;position:absolute;left:35%;top:25%;z-idnex:99;width:500px;height:200px;">
<div style="text-align:center;width:100%;height:25px;font-size: 16px;"><?php echo __('addfeedback')?></div>
	<div style="margin-top:10px;">
	
	<textarea id="ComplainContent" class="input in-text in-textarea" style="float: left; width: 400px; height: 94px;" rows="6" cols="30" name="data[Complain][content]"></textarea>

	</div>
	<div style="text-align:center">
			<input checked type="radio" class="ss" name="status" value="1"/><?php echo __('open')?>&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="radio" name="status" class="ss" value="2"/><?php echo __('closed')?>&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="radio" name="status" class="ss" value="3"/><?php echo __('resolved')?>&nbsp;&nbsp;&nbsp;&nbsp;
	</div>
	<div style="margin-top:10px; margin-left:25%;width:150px;height:auto;">
		<input type="button" onclick="addfeedback('ComplainContent','<?php echo $this->webroot?>complainfeedbacks/add/<?php echo $id?>',<?php echo $status?>);" value="<?php echo __('submit')?>" class="input in-button">
		<input type="button" onclick="closeCover('addproduct');" value="<?php echo __('cancel')?>" class="input in-button">
	</div>
</dl>


<dl id="viewmessage" class="tooltip-styled" style="display:none;position:absolute;left:35%;top:25%;z-idnex:99;width:500px;height:200px;">
<div style="text-align:center;width:100%;height:25px;font-size: 16px;"><?php echo __('viewmessage')?><a style="float:right;" href="javascript:void(0)" onclick="closeCover('viewmessage');" title="<?php echo __('close')?>"><i class="icon-remove"></i></a></div>
	<div style="margin-top:10px;">
	
	<textarea id="CompleteContent" class="input in-text in-textarea" style="float: left; width: 400px; height: 94px;" rows="6" cols="30" ></textarea>

	</div>
</dl>

		<div id="toppage"></div>
		<?php if (count($post) == 0) {?>
<div class="msg"><?php echo __('no_data_found')?></div>
<?php } else {?>
<table class="list">
<col width="13%">

<col width="12%">
<col width="12%">
<col width="12%">
<col width="12%">

<thead>
<tr>

  <td><input type="checkbox" onclick="checkAllOrNot(this,'producttab');" value=""/></td>
    <td ><?php echo __('id',true);?></td>
    <td >名字</td>
     <td ><?php echo __('url')?></td>
    <td  class="last"><?php echo __('action')?></td>
    
  
</tr>
 
</thead>
<tbody id="producttab">




	<?php 
					$mydata =$post;
					$loop = count($post); 
					for ($i=0;$i<$loop;$i++) {?>
<tr class="row-1">
<td style="text-align:center"><input type="checkbox" value="<?php echo $mydata[$i][0]['service_download_id']?>"/></td>
<td><?php  echo $mydata[$i][0]['service_download_id'];?></td>
<td><?php  echo $mydata[$i][0]['name'];?></td>

<td><?php  echo $mydata[$i][0]['url'];?></td>


      <td class="last">
 
  <a style="float: left; margin-left: 40px;" href="<?php echo $this->webroot?>systemlimits/service_download/<?php echo  $mydata[$i][0]['service_download_id'];?>" title="修改">
      <i class="icon-edit"></i></a>
          <a   title="<?php echo __('del')?>" onclick="return confirm('<?php echo __('confirmdel')?>');" 
    
     href="<?php echo $this->webroot?>systemlimits/delete_service/<?php echo $mydata[$i][0]['service_download_id']?>/">
     <i class="icon-remove"></i></a>     
      </td>
</tr>


	<?php }?>

</tbody><tbody>
</tbody></table>
</div>
<div>

<?php }?>
</div>

<div id="livemargins_control" style="position: absolute; display: none; z-index: 9999;"><img width="77" height="5" style="position: absolute; left: -77px; top: -5px;" src="chrome://livemargins/skin/monitor-background-horizontal.png">	
<img style="position: absolute; left: 0pt; top: -5px;" src="chrome://livemargins/skin/monitor-background-vertical.png">
	<img style="position: absolute; left: 1px; top: 0pt; opacity: 0.5; cursor: pointer;" onmouseout="this.style.opacity=0.5" onmouseover="this.style.opacity=1" src="chrome://livemargins/skin/monitor-play-button.png" id="monitor-play-button"></div>