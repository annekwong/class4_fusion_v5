<script src="<?php echo $this->webroot?>js/ajaxTable.js" type="text/javascript"></script>
<div id="cover"></div> 
	<div id="title">
  <h1><?php __('Configuration')?>&gt;&gt;<?php echo __('exportlog');?></h1>
	 <ul id="title-search">
    <li>
   		<form   id="like_form"  action=""  method="get">
        <input type="text" id="search-_q" class="in-search default-value input in-text defaultText" title="<?php echo __('namesearch')?>" value="<?php if (!empty($search)) echo $search;?>" name="search">
      </form>
    </li>
    <li title="<?php echo __('advancedsearch')?> »" onclick="advSearchToggle();" id="title-search-adv" style="display: list-item;"></li>
   </ul>
   <ul id="title-menu">
 		</ul>
</div>
<div id="container">
<fieldset class="title-block" id="advsearch"  style="margin-left:1px;width: 100%;">
	<form action="" method="get">
	<input name="advsearch" type="hidden"/>
<table   style="width:auto">
	<tbody>
		<tr>
		  <td><label   ><?php __('timeprofile')?>:</label>
						<input type="text" readonly onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'});" id="start_date" style="width:120px;" value="" name="start_date" class="input in-text wdate">
		    				--
		    		<input type="text" readonly onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'});" id="end_date" style="width:120px;" value="" name="end_date" class="wdate input in-text">
		  </td>
		  <td><label>&nbsp;</label>  <?php  __('Carriers')?> </td>
		  <td id="client_cell">
		    <label>&nbsp;</label>
		    <input type="text" id="query-id_resellers_name" onclick="showRsellers()" style="width: 150px;" readonly="1" value="" name="query[id_resellers_name]" class="input in-text">        
		    <input class="input in-hidden" name="query[id_resellers]" value="" id="query-id_resellers" type="hidden">
		    <img width="9" height="9" onclick="showRsellers()" class="img-button" src="<?php echo $this->webroot?>images/search-small.png">
		    <img width="9" height="9" onclick="ss_clear('reseller', _ss_ids_reseller)" class="img-button" src="<?php echo $this->webroot?>images/delete-small.png">
		  </td>
		  <td class="buttons"><input type="submit" value="<?php echo __('submit')?>" class="input in-submit"></td>
		</tr>
	</tbody>
</table>
</form></fieldset>
<dl id="addproduct" class="tooltip-styled" style="display:none;position:absolute;left:40%;top:50%;z-idnex:99;width:500px;height:200px;">
<dd style="text-align:center;width:100%;height:25px;font-size: 16px;"><?php  __('deletedatedownload')?></dd>
	<dd style="margin-top:10px;">
			<table class="form" style="width: 449px;">
				<tbody>

				 <tr>
    				<td class="label label2"><?php echo __('startdate')?>:</td>
    				<td class="value value2"><input type="text" readonly onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'});" id="start_date" style="float:left;width:200px;" value="" name="start_date" class="input in-text"></td>
				 </tr>
				 <tr>
    				<td class="label label2"><?php echo __('enddate')?>:</td>
    				<td class="value value2"><input type="text" readonly onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'});" id="end_date" style="float:left;width:200px;" value="" name="end_date" class="input in-text"></td>
				 </tr>
				</tbody>
			</table>
	</dd>
	<dd style="margin-top:10px; margin-left:25%;width:150px;height:auto;">
		<input type="button" onclick="deluploadbytime('ComplainContent','<?php echo $this->webroot?>importlogs/delbytime');" value="<?php echo __('submit')?>" class="input in-button">
		<input type="button" onclick="closeCover('addproduct');" value="<?php echo __('cancel')?>" class="input in-button">
	</dd>
</dl>
		<div id="toppage"></div>
		<?php $d = $p->getDataArray();if (count($d) == 0) {?>
<div class="msg"><?php echo __('no_data_found')?></div>
<?php } else {?>
<table class="list">
	<col width="4%">
	<col width="8%">
	<col width="8%">
	<col width="8%">
	<col width="8%">
	<col width="9%">
<thead>
<tr>
  <td ><?php echo __('id',true);?>&nbsp;</td>
    <td ><?php echo __('resellertype')?>&nbsp;</td>
    <td > <?php  __('Carriers')?></td>
    <td><?php echo __('user')?></td>
    <td><?php echo __('exportdate')?></td>
    <td><?php __("status")?></td>
</tr>
</thead>
<tbody>
	<?php $mydata =$p->getDataArray();$loop = count($mydata);for ($i=0;$i<$loop;$i++) {?>
	<tr class="row-1">
    <td align="center"><?php echo $mydata[$i][0]['id']?></td>
  	 <td align="center"><?php echo $mydata[$i][0]['objectives']?></td>
    <td align="center"><?php echo $mydata[$i][0]['client_name']?></td>
    <td align="center"><?php echo $mydata[$i][0]['u_name']?></td>
    <td align="center"><?php echo $mydata[$i][0]['downloadtime']?></td>
    <td align="center">
		    <?php
							if($mydata[$i][0]['status']==11){echo "Download Error";} 
							if($mydata[$i][0]['status']==14){echo "<b    style='color:red'>[file is not exist]</b>";}
							if($mydata[$i][0]['status']==12){echo "<b    style='color:green'><img width='16' height='16' alt='' src='<?php echo $this->webroot?>images/status_closed.gif'></b>";}
							if($mydata[$i][0]['status']==13){echo "file is not exist";}
					?>
			</td>
	</tr>
	<?php }?>
</tbody>
</table>
</div>
<div>
<div id="tmppage">
<?php echo $this->element('page');?>
</div>
<?php }?>
</div>
<div id="livemargins_control" style="position: absolute; display: none; z-index: 9999;"><img width="77" height="5" style="position: absolute; left: -77px; top: -5px;" src="chrome://livemargins/skin/monitor-background-horizontal.png">	
<img style="position: absolute; left: 0pt; top: -5px;" src="chrome://livemargins/skin/monitor-background-vertical.png">
	<img style="position: absolute; left: 1px; top: 0pt; opacity: 0.5; cursor: pointer;" onmouseout="this.style.opacity=0.5" onmouseover="this.style.opacity=1" src="chrome://livemargins/skin/monitor-play-button.png" id="monitor-play-button"></div>
	<script type="text/javascript" src="<?php echo $this->webroot?>js/My97DatePicker/WdatePicker.js"></script>
