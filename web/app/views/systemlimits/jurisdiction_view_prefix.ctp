<script src="<?php echo $this->webroot?>js/ajaxTable.js" type="text/javascript"></script>
<div id="cover"></div> 
<div id="title">
       <h1> <?php __('Chargingzoneprefixset')?> </h1>
        

    
<ul id="title-search">
        <li>
        <form method="get" action="">
        <input type="text" name="search" onclick="this.value=''" value="" title="Query..." class="in-search default-value input in-text defaultText" id="search-_q">
        </form>
        </li>
        
        <li style="display: list-item;" id="title-search-adv" onclick="advSearchToggle();" title="Advanced Search »" class="opened"></li>
    </ul>
    
        <ul id="title-menu">

<li><a class="link_btn" href="<?php echo $this->webroot?>systemlimits/import_rate/<?php echo $_SESSION['jurisdiction_id']?>"><img width="16" height="16" src="<?php echo $this->webroot?>images/import.png"> <?php __('Upload')?></a></li>

<li><a c class="link_btn" href="<?php echo $this->webroot?>systemlimits/download_rate/<?php echo $_SESSION['jurisdiction_id']?>"><img width="10" height="5" alt="" src="<?php echo $this->webroot?>images/export.png"><?php __('Download')?></a></li>
     
   <li><a  class="link_btn" href="<?php echo $this->webroot?>systemlimits/add_jurisdiction_prefix"    style="width: 185px;">
       <img width="16" height="16" alt="" src="<?php echo $this->webroot?>images/add.png"><?php __('addChargingzoneprefixset')?> </a></li>
           		<li>
    			<a class="link_back" href="<?php echo $this->webroot?>systemlimits/jurisdiction_view">
    				<img width="16" height="16" src="<?php echo $this->webroot?>images/icon_back_white.png" alt="">
    				&nbsp;<?php __('back')?>		</a>
    		</li>
</ul>
        

    </div>

<div id="container">


<fieldset style="width: 100%; display: none;" id="advsearch" class="title-block">
	 <form method="get" action="">
<table style="width: 125px;">
<tbody>
<tr>
    <td><label> <?php __('Prefix')?>:</label>
    
     <input type="text" id="name" value="" name="name" class="input in-text">
  </td>

    



  <td class="buttons"><input type="submit" class="input in-submit" value="<?php __('Search')?>"></td>
</tr>

</tbody></table>
</form></fieldset>


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
<?php $d = $p->getDataArray();if (count($d) == 0) {?>
<div class="msg"><?php echo __('no_data_found')?></div>
<?php } else {?>

		<div id="toppage"></div>
<table class="list">
<col width="12%">
<col width="12%">
<col width="12%">
<col width="12%">

<thead>
<tr>

 
    <td ><?php echo __('id',true);?></td>
    <td ><?php __('name')?></td>
     <td ><?php __('prefix')?></td>
    <td  class="last"><?php echo __('action')?></td>
    
  
</tr>
 
</thead>
<tbody id="producttab">




		<?php 	$mydata =$p->getDataArray();	$loop = count($mydata); for ($i=0;$i<$loop;$i++) {?>
<tr class="row-1">

<td><?php  echo $mydata[$i][0]['id'];?></td>
<td><?php  echo $mydata[$i][0]['alias'];?></td>

<td><?php  echo $mydata[$i][0]['prefix'];?></td>


      <td class="last">
 
  <a style="float: left; margin-left: 40px;" href="<?php echo $this->webroot?>systemlimits/add_jurisdiction_prefix/<?php echo  $mydata[$i][0]['id'];?>"
   title="<?php __('Edit')?>">
      <i class="icon-edit"></i></a>
          <a   title="<?php echo __('del')?>" onclick="return confirm('<?php echo __('confirmdel')?>');" 
    
     href="<?php echo $this->webroot?>systemlimits/delete_jurisdiction_prefix/<?php echo $mydata[$i][0]['id']?>/">
     <i class="icon-remove"></i></a>
     
     
      
      </td>
  


                
  

</tr>


	<?php }?>

</tbody><tbody>
</tbody></table>


	<div id="tmppage"><?php echo $this->element('page');?></div>
</div>
<div>

<?php }?>
</div>

<div id="livemargins_control" style="position: absolute; display: none; z-index: 9999;"><img width="77" height="5" style="position: absolute; left: -77px; top: -5px;" src="chrome://livemargins/skin/monitor-background-horizontal.png">	
<img style="position: absolute; left: 0pt; top: -5px;" src="chrome://livemargins/skin/monitor-background-vertical.png">
	<img style="position: absolute; left: 1px; top: 0pt; opacity: 0.5; cursor: pointer;" onmouseout="this.style.opacity=0.5" onmouseover="this.style.opacity=1" src="chrome://livemargins/skin/monitor-play-button.png" id="monitor-play-button"></div>