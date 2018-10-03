<script src="<?php echo $this->webroot?>js/ajaxTable.js" type="text/javascript"></script>
<div id="cover"></div> 
	<div id="title">
    <h1><?php __('Configuration')?>&gt;&gt;<?php echo __('importlog');  ?></h1>
		 <ul id="title-search">
        <li>
        	<form   id="like_form"  action=""  method="get">
        		<input type="text" id="search-_q" class="in-search default-value input in-text defaultText" title="<?php echo __('namesearch')?>" value="<?php if (!empty($search)) echo $search;?>" name="search">
        	</form>
        </li>
        <li title="<?php echo __('advancedsearch')?> »" onclick="advSearchToggle();" id="title-search-adv" style="display: list-item;"></li>
    </ul>
  </div>
<div id="container">
	<?php $d = $p->getDataArray();if (count($d) == 0) {?>
		<div class="msg"><?php echo __('no_data_found')?></div>
	<?php } else {?>
<fieldset class="title-block" id="advsearch"  style="margin-left:1px;width: 100%;">
	<form action="" method="get">
	<input name="advsearch" type="hidden"/>
<table   style="width: 850px;">
<tbody>
<tr>
    <td><label   ><?php __('timeprofile')?>:</label>
			<input type="text" readonly onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'});" id="start_date" style="width:120px;" value="" name="start_date" class="input in-text wdate">
    		--
    		<input type="text" readonly onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'});" id="end_date" style="width:120px;" value="" name="end_date" class="wdate input in-text">
    </td>
    
    
    
    <td class="label" style="width:10%"> <?php  __('Carriers')?>  </td>
    <td id="client_cell" class="value" style="width:20%">
        <input type="text" id="query-id_clients_name" onclick="showClients()" style="width: 73%;" readonly="1" value="" name="query[id_clients_name]" class="input in-text">        
        <img width="9" height="9" onclick="showClients()" class="img-button" src="<?php echo $this->webroot?>images/search-small.png">
        <img width="9" height="9" onclick="ss_clear('client', _ss_ids_client)" class="img-button" src="<?php echo $this->webroot?>images/delete-small.png">
    </td>
    <td class="buttons" style="padding-top:0px"><input type="submit" value="<?php echo __('submit')?>" class="input in-submit"></td>
</tr>
</tbody></table>
</form></fieldset>

	<?php //*********************查询条件********************************?>
<dl id="addproduct" class="tooltip-styled" style="display:none;position:absolute;left:40%;top:50%;z-idnex:99;width:500px;height:200px;">
	<dd style="text-align:center;width:100%;height:25px;font-size: 16px;"><?php  __('deletedateupload')?></dd>
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
<table class="list">
<col width="4%">
<col width="6%">
<col width="6%">
<col width="8%">
<col width="8%">
<col width="6%">
<col width="10%">
<col width="8%">
<col width="6%">
<col width="4%">
<col width="4%">
<thead>
<tr>
    <td > &nbsp;<?php echo __('id',true);?></td>
    <td >&nbsp;<?php echo __('resellertype')?>&nbsp; </td>
    <td ><?php echo __('Carrier')?></td>
    <td><?php echo __('user')?></td>
    <td><?php echo __('importstartdate')?></td>
    <td><?php echo __('uploadparams')?></td>
    <td><?php echo __('status')?></td>
    <td><?php echo __("check_upload_file")?></td>
    <td><?php echo __('downloadoldfile')?></td>
    <td><?php echo __('downloaderrfile')?></td>
    <td><?php echo __('againuploadfile')?></td>
</tr>
</thead>
<tbody>
	<?php 
					$mydata =$p->getDataArray();
					$loop = count($mydata); 
					for ($i=0;$i<$loop;$i++) {?>
<tr class="row-1">
    <td align="center"><?php echo $mydata[$i][0]['id']?></td>
    <td align="center"><?php echo $mydata[$i][0]['objectives']?></td>
    <td align="center"><?php echo $mydata[$i][0]['client_name']?></td>
    <td align="center"><?php echo $mydata[$i][0]['u_name']?></td>
    <td align="center"><?php echo $mydata[$i][0]['uploadtime']?></td>
  	 <td align="center">
  	 		<?php 
  	 			if( $mydata[$i][0]['upload_param']==1){echo __("delete_duplicate");}
  	 			if( $mydata[$i][0]['upload_param']==2){echo __('ignore_duplicate');}
					if( $mydata[$i][0]['upload_param']==3){echo __("delete_duplicate");}
					if( $mydata[$i][0]['upload_param']==4){echo __('return_error_info');}
     			?>
     </td>
     <td align="center">
     <?php 
     		if( $mydata[$i][0]['status']==0){echo __("ImportSuccessful")."<br/><b style='color:red'>["; echo __("not_checked")."]</b>";}
					if( $mydata[$i][0]['status']==7){echo __("ImportSuccessful");}
    			if( $mydata[$i][0]['status']==2){echo  __('ImportFail');}
    			if( $mydata[$i][0]['status']==3){echo  __('ImportSuccessful');}
					if( $mydata[$i][0]['status']==1){echo  __('Fileistoolarge');}
     			?>
     </td>
    <td align="center">
     <?php if( $mydata[$i][0]['status']==0){?>
     		<a href="<?php echo $this->webroot?>importlogs/check_data/<?php  echo $mydata[$i][0]['id']?>"><b><?php echo __("checking")?></b></a>
     <?php }?>
     <?php if ($mydata[$i][0]['status']==8){?>
     		<b style='color:blue'><?php echo __("check_pass")?></b>
     <?php } ?>
     <?php if ($mydata[$i][0]['status']==5){?>
     		<b style='color:blue'><?php echo __("check_pass")?></b>
     <?php } ?>
    </td>
    <td align="center">
     <?php if( $mydata[$i][0]['status']!=7){?>
        <a href="<?php echo $this->webroot?>importlogs/again_download2/<?php echo $mydata[$i][0]['id']?>">
        <img width="16" height="16" src="<?php echo $this->webroot?>images/download.png"></a>
     <?php  } else{ echo "<b style='color:red'>[".__("file_deleted")."]</b>" ;}?>
    </td>
    <td align="center"></td>
    <td align="center"></td>
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


	
	<script type="text/javascript" src="<?php echo $this->webroot?>js/My97DatePicker/WdatePicker.js"></script>
	
	<script  type="text/javascript">
	var _ss_ids_client = {'id_clients': 'query-id_clients', 'id_clients_name': 'query-id_clients_name',  'id_currencies': 'query-id_currencies'};

	function showClients ()
	{
	    ss_ids_custom['client'] = _ss_ids_client;
	    winOpen('<?php echo $this->webroot?>clients/ss_client?types=2&type=0', 500, 530);

	}
	
	</script>