
    <script type="text/javascript" src="<?php echo $this->webroot?>js/My97DatePicker/WdatePicker.js"></script>
    
    <script type="text/javascript">
    			function edit(tr,c,res,cli,acc){
        	$('#e_title').val(tr.cells[2].innerHTML.trim());
        	$('#e_end_time').val(tr.cells[5].innerHTML.trim());
        	$('#e_ads_content').html(c);
        	$('#e_to_reseller').val(res).attr('checked',res=='true'?true:false);
        	$('#e_to_res').val(res);
        	$('#e_to_client').val(cli).attr('checked',cli=='true'?true:false);
        	$('#e_to_cli').val(cli);
        	$('#e_to_account').val(acc).attr('checked',acc=='true'?true:false);
        	$('#e_to_acc').val(acc);
        	$('#edit_id').val(tr.cells[1].innerHTML.trim());
        			}
    </script>
<div id="title">
  <h1>
    <span><?php echo __('systemc',true);?></span>
    	<?php echo __('sysads',true);?>
  </h1>
  <ul id="title-menu">
    <li><a class="link_btn" rel="popup" href="javascript:void(0)" onclick="cover('add_ad')"><img width="16" height="16" alt="" src="<?php echo $this->webroot?>images/add.png"> <?php echo __('createnew')?></a></li>
    <li><a class="link_btn" rel="popup" href="javascript:void(0)" onclick="deleteAll('<?php echo $this->webroot?>ads/del_ad/all');" ><i class="icon-remove"></i> <?php echo __('deleteall')?></a></li>
    <li><a class="link_btn" rel="popup" href="javascript:void(0)" onclick="deleteSelected('producttab','<?php echo $this->webroot?>ads/del_ad/selected');"><i class="icon-remove"></i> <?php echo __('deleteselected')?></a></li>
  </ul>
</div>
<div id="container">
<div id="cover"></div>
<div id="toppage"></div>
<form method="post">
<dl id="add_ad" class="tooltip-styled" style="display:none;position:absolute;left:35%;top:25%;z-idnex:99;width:500px;height:auto;">
<div style="text-align:center;width:100%;height:25px;font-size: 16px;"><?php echo __('addad')?><a style="float:right;" href="javascript:void(0)" onclick="closeCover('add_ad');" title="<?php echo __('close')?>"><i class="icon-remove"></i></a></div>
	<table class="list-form">
		<tr>
			<td class="label"><?php echo __('title')?></td>
			<td class="value2"><input name="title"  style="width:160px;"/></td>
		</tr>
		<tr>
			<td class="label"><?php echo __('end_time')?></td>
			<td class="value2"><input name="end_time"  id="end_time" class="wdate" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})"  readonly style="width:180px;"/></td>
		</tr>
		<tr>
			<td class="label"><?php echo __('ads_content')?></td>
			<td class="value2">
				<textarea id="ads_content" name="ads_content" class="input in-text in-textarea" style="float: left; width: 400px; height: 94px;font-size:12px;" rows="6" cols="30" ></textarea>
			</td>
		</tr>
		<tr>
			<td class="label">&nbsp;</td>
			<td class="value" style="font-size:12px;">
				<input type="checkbox" checked name="to_reseller" value="true" onclick="if(this.value==='true'){$('#to_res').val('false');}else{$('#to_res').val('true');}"/>代理商可见&nbsp;&nbsp;
				<input type="hidden" name="to_reseller" id="to_res"/>
				
				<input type="checkbox" checked name="to_client" value="true" onclick="if(this.value==='true'){$('#to_cli').val('false');}else{$('#to_cli').val('true');}"/>路由伙伴可见&nbsp;&nbsp;
				<input type="hidden" name="to_client" id="to_cli"/>
				
				<input type="checkbox" checked name="to_account" value="true" onclick="if(this.value==='true'){$('#to_acc').val('false');}else{$('#to_acc').val('true');}"/>帐户可见&nbsp;&nbsp;
				<input type="hidden" name="to_account" id="to_acc"/>
			</td>
		</tr>
	</table>
	<div style="margin-top:10px;text-align:center">
		<input type="submit" value="<?php echo __('submit')?>"/>
		<input type="button" onclick="closeCover('add_ad')" value="<?php echo __('cancel')?>"/>
	</div>
</dl>


<dl id="viewmessage" class="tooltip-styled" style="display:none;position:absolute;left:35%;top:25%;z-idnex:99;width:500px;height:auto;">
<div style="text-align:center;width:100%;height:25px;font-size: 16px;"><?php echo __('viewdetail')?><a style="float:right;" href="javascript:void(0)" onclick="closeCover('viewmessage');" title="<?php echo __('close')?>"><i class="icon-remove"></i></a></div>
	<div style="margin-top:10px;">
	
	<textarea id="CompleteContent" class="input in-text in-textarea" style="float: left; width: 400px; height: 94px;" rows="6" cols="30" ></textarea>

	</div>
</dl>
</form>


<form method="post">
<input type="hidden" name="system_ads_id" id="edit_id"/>
<dl id="edit_ad" class="tooltip-styled" style="display:none;position:absolute;left:35%;top:25%;z-idnex:99;width:500px;height:auto;">
<div style="text-align:center;width:100%;height:25px;font-size: 16px;"><?php echo __('editad')?><a style="float:right;" href="javascript:void(0)" onclick="closeCover('add_ad');" title="<?php echo __('close')?>"><i class="icon-remove"></i></a></div>
	<table class="list-form">
		<tr>
			<td class="label"><?php echo __('title')?></td>
			<td class="value2"><input name="title" id="e_title"  style="width:160px;"/></td>
		</tr>
		<tr>
			<td class="label"><?php echo __('end_time')?></td>
			<td class="value2"><input name="end_time"  id="e_end_time" class="wdate" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})"  readonly style="width:180px;"/></td>
		</tr>
		<tr>
			<td class="label"><?php echo __('ads_content')?></td>
			<td class="value2">
				<textarea id="e_ads_content" name="ads_content" class="input in-text in-textarea" style="float: left; width: 400px; height: 94px;font-size:12px;" rows="6" cols="30" ></textarea>
			</td>
		</tr>
		<tr>
			<td class="label">&nbsp;</td>
			<td class="value" style="font-size:12px;">
				<input type="checkbox" checked name="to_reseller" id="e_to_reseller" value="true" onclick="if(this.value==='true'){$('#e_to_res').val('false');}else{$('#e_to_res').val('true');}"/>代理商可见&nbsp;&nbsp;
				<input type="hidden" name="to_reseller" id="e_to_res"/>
				
				<input type="checkbox" checked name="to_client" id="e_to_client" value="true" onclick="if(this.value==='true'){$('#e_to_cli').val('false');}else{$('#e_to_cli').val('true');}"/>路由伙伴可见&nbsp;&nbsp;
				<input type="hidden" name="to_client" id="e_to_cli"/>
				
				<input type="checkbox" checked name="to_account" id="e_to_account" value="true" onclick="if(this.value==='true'){$('#e_to_acc').val('false');}else{$('#e_to_acc').val('true');}"/>帐户可见&nbsp;&nbsp;
				<input type="hidden" name="to_account" id="e_to_acc"/>
			</td>
		</tr>
	</table>
	<div style="margin-top:10px;text-align:center">
		<input type="submit" value="<?php echo __('submit')?>"/>
		<input type="button" onclick="closeCover('edit_ad')" value="<?php echo __('cancel')?>"/>
	</div>
</dl>
</form>
<?php $d = $p->getDataArray();if (count($d) == 0) {?>
<div class="msg"><?php echo __('no_data_found')?></div>
<?php } else {?>
<table class="list">
	<col style="width: 5%;">
	<col style="width: 7%;">
	<col style="width: 15%;">
	<col style="width: 22%;">
	<col style="width: 20%;">
	<col style="width: 20%;">
	<col style="width: 16%;">
	<thead>
		<tr>
		<td><input type="checkbox" onclick="checkAllOrNot(this,'producttab');" value=""/></td>
    <td><a href="javascript:void(0)" onclick="my_sort('system_ads_id','asc');"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<?php echo __('id',true);?>&nbsp;<a href="javascript:void(0)" onclick="my_sort('system_ads_id','desc');"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
    <td><a href="javascript:void(0)" onclick="my_sort('title','asc');"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<?php echo __('title')?>&nbsp;<a href="javascript:void(0)" onclick="my_sort('title','desc');"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
    <td><a href="javascript:void(0)" onclick="my_sort('ads_content','asc');"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<?php echo __('ads_content')?>&nbsp;<a href="javascript:void(0)" onclick="my_sort('ads_content','desc');"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
    <td><a href="javascript:void(0)" onclick="my_sort('ads_time','asc');"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<?php echo __('ads_time')?>&nbsp;<a href="javascript:void(0)" onclick="my_sort('ads_time','desc');"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
    <td><a href="javascript:void(0)" onclick="my_sort('end_time','asc');"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<?php echo __('end_time')?>&nbsp;<a href="javascript:void(0)" onclick="my_sort('end_time','desc');"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
    <td class="last"><?php echo __('action')?></td>
		</tr>
	</thead>
	<tbody id="producttab">
		<?php 
			$mydata =$p->getDataArray();
			$loop = count($mydata); 
			for ($i=0;$i<$loop;$i++) {?>
				<tr class="row-1">
					<td style="text-align:center"><input type="checkbox" value="<?php echo $mydata[$i][0]['system_ads_id']?>"/></td>
		    <td class="in-decimal"  style="text-align:center"><?php echo $mydata[$i][0]['system_ads_id']?></td>
		    <td style="font-weight: bold;">
		    		<?php echo $mydata[$i][0]['title']?>
		    </td>
		    <td>
		    		<?php echo substr($mydata[$i][0]['ads_content'],0,50).'...'?>
		    		<a href="javascript:void(0)" onclick="cover('viewmessage');$('#CompleteContent').html('<?php echo $mydata[$i][0]['ads_content']?>')"><?php echo __('viewdetail')?></a>
		    </td>
		    <td align="center"><?php echo $mydata[$i][0]['ads_time']?></td>
		    <td align="center"><?php echo $mydata[$i][0]['end_time']?></td>
		    <td >
		    	<a title="<?php echo __('edit')?>" style="float:left;margin-left:35px;" href="javascript:void(0)" onclick="cover('edit_ad');edit(this.parentNode.parentNode,'<?php echo $mydata[$i][0]['ads_content'];?>','<?php echo $mydata[$i][0]['to_reseller']==true?'true':'false';?>','<?php echo $mydata[$i][0]['to_client']==true?'true':'false';?>','<?php echo $mydata[$i][0]['to_account']==true?'true':'false';?>');">
		    			<i class="icon-edit"></i>
		    		</a>
		    		<a title="<?php echo __('del')?>" style="float:left;margin-left:20px;" href="javascript:void(0)" onclick="delConfirm(this,'<?php echo $this->webroot?>ads/del_ad/<?php echo $mydata[$i][0]['system_ads_id']?>')">
		    			<i class="icon-remove"></i>
		    		</a>
		    </td>
				</tr>
		<?php }?>
		</tbody>
	<tbody>
</tbody>
</table>
<div id="tmppage">
<?php echo $this->element('page');?>
</div>
<?php }?>
</div>
<div id="livemargins_control" style="position: absolute; display: none; z-index: 9999;"><img width="77" height="5" style="position: absolute; left: -77px; top: -5px;" src="chrome://livemargins/skin/monitor-background-horizontal.png">	<img style="position: absolute; left: 0pt; top: -5px;" src="chrome://livemargins/skin/monitor-background-vertical.png">	<img style="position: absolute; left: 1px; top: 0pt; opacity: 0.5; cursor: pointer;" onmouseout="this.style.opacity=0.5" onmouseover="this.style.opacity=1" src="chrome://livemargins/skin/monitor-play-button.png" id="monitor-play-button"></div>