    <style>
				a:hover:{text-decoration: none;}
			</style>
    <script type="text/javascript">
				    //提示消息居中显示
					jQuery.jGrowl.defaults.position = 'top-center';
    </script>
<div id="title">
  <h1>
    <?php echo __('manage')?> &gt;&gt;
    <?php echo __('Static Route Table')?>
  </h1>
    <ul id="title-menu">
     <?php if (isset($edit_return)) {?>
        <li>
    			<a class="link_back" href="<?php echo $this->webroot?>products/route_info/<?php echo $id?>">
    				<img width="16" height="16"  alt="" src="<?php echo $this->webroot?>images/icon_back_white.png">
    				&nbsp;<?php echo __('goback')?>
    			</a>
    		</li>
        <?php }?>
	  	 <li>
	  	 		<a class="link_back" href="<?php echo $this->webroot?>products/product_list">
	  	 			<img width="10" height="5" alt="" src="<?php echo $this->webroot?>images/icon_back_white.png">
	  	 			<?php echo __('goback')?>
	  	 		</a>
	  	 </li>
			<li><a class="link_btn" href="<?php echo $this->webroot?>uploads/static_route/<?php echo $id?>">
			<img width="16" height="16" src="<?php echo $this->webroot?>images/import.png"> <?php echo __('upload')?></a></li>
			<li><a class="link_btn" href="<?php echo $this->webroot?>downloads/product_item/<?php echo $id?>"><img width="10" height="5" alt="" src="<?php echo $this->webroot?>images/export.png"><?php __('download')?></a></li>
     <li><a class="link_btn" id="add" href="<?php echo $this->webroot?>products/add_route/<?php echo $id?>"><img width="16" height="16" alt="" src="<?php echo $this->webroot?>images/add.png"> <?php echo __('createnew')?></a></li>
     <li><a class="link_btn" rel="popup" href="javascript:void(0)" onclick="deleteAll('<?php echo $this->webroot?>products/delall?id='+<?php echo $id?>);" ><i class="icon-remove"></i> <?php echo __('deleteall')?></a></li>
     <li><a class="link_btn" rel="popup" href="javascript:void(0)" onclick="deleteSelected('routetab','<?php echo $this->webroot?>products/delselected?id=<?php echo $id?>');"><i class="icon-remove"></i> <?php echo __('deleteselected')?></a></li>
     <li>
		    	<form>
		    		<input type="text" id="search-_q" class="in-search default-value input in-text defaultText" title="<?php echo __('prefixsearch')?>" value="<?php if (!empty($search)) echo $search;?>" name="search">
		    	</form>
		  </li>
  </ul>
</div>
<div id="container">
		<ul class="tabs">
		    <li class="active"><a href="<?php echo $this->webroot ?>products/route_info/<?php echo $id?>"><img width="16" height="16" src="<?php echo $this->webroot ?>images/menuIcon.gif"> List</a></li>
		    <li ><a href="<?php echo $this->webroot ?>uploads/static_route/<?php echo $id?>"><img width="16" height="16" src="<?php echo $this->webroot ?>images/import.png"> <?php echo __('import',true);?></a></li> 
		    <li  ><a href="<?php echo $this->webroot ?>downloads/product_item/<?php echo $id?>"><img width="16" height="16" src="<?php echo $this->webroot ?>images/export.png"> <?php echo __('export',true);?></a></li>   
		</ul>
		<?php $d = $p->getDataArray();if (count($d) == 0) {?>
		<div class="msg"><?php echo __('no_data_found')?></div>
		<?php } else {?>
					<div id="toppage"></div>
					<div id="cover"></div>
		    <div id="uploadroute"  style="display:none;height: auto;z-index:99;position: absolute;left:30%;top: 20%;" class="form_panel_upload">
			    <form action="<?php echo $this->webroot?>products/upload/<?php echo $id?>" method="post" enctype="multipart/form-data" id="productFile">	
			    <span class="wordFont1 marginSpan1"><?php echo __('selectfile')?>:</span>
			    <div style="height: 100px;" class="up_panel_upload">
			    <input style="margin-top:10px;" type="file" value="Upload" size="45" class="input in-text" id="browse" name="browse">
			    <div style="margin-top:20px;">
			    			<input type="radio" title="This action takes each record from the csv and adds it to the table, if the prefix already exists then it will be replaced with the one contained in the table !" checked="" value="1" name="handleStyle">
					    <span><?php echo __('overwrite')?></span>
					    <input style="margin-left:10px;" type="radio" title="This action will remove all matching prefixes from the table !" value="2" name="handleStyle">
					    <span><?php echo __('remove')?></span>
					    <input style="margin-left:10px;" type="radio" title="This action will fresh prefixes from the table !" value="3" name="handleStyle">
					    <span><?php echo __('clearrefresh')?></span>
			       <input style="margin-left:10px;" type="checkbox" checked onclick="if(this.value=='false')this.value='true';else this.value='false';document.getElementById('isRoll').value=this.value;">
			       <input type="hidden" value="true" name="isRoll" id="isRoll"/>
			       <span><?php echo __('rollbackonfail')?> </span>   
			    </div>   
			    </div>
			    <div class="form_panel_button_upload">
			    			<span style="float:left"> <?php echo __('downloadtempfile')?><a href="<?php echo $this->webroot?>products/downloadtemplate" style="color:red"><?php echo __('clickhere')?></a></span>
			    			<input type="submit" class="input in-button" value="<?php echo __('upload')?>"/>
			    			<input type="button" onclick="closeCover('uploadroute')" style="margin-bottom:6px;" class="input in-button" value="<?php echo __('cancel')?>"/>
			    </div>  
			    </form>
		    </div>
		    <div id="uploadroute_error"  style="display:none;height: auto;z-index:99;position: absolute;left:30%;top: 20%;" class="form_panel_upload">
			    <span class="wordFont1 marginSpan1"><span style="color:red" id="affectrows"></span>&nbsp;&nbsp;<?php echo __('erroroccured')?>:</span>
			    <div style="height: auto;text-align:left;" id="route_upload_errorMsg" class="up_panel_upload"></div>    
			    <div class="form_panel_button_upload">
			    			<span style="float:left"><?php echo __('downloadtempfile')?> .<a href="<?php echo $this->webroot?>products/downloadtemplate" style="color:red"><?php echo __('clickhere')?></a></span>
			    			<input type="button" onclick="closeCover('uploadroute_error')" style="margin-bottom:6px;" class="input in-button" value="<?php echo __('close')?>"/>
			    </div>  
		    </div>
					<table class="list">
						<col style="width: 2%;">
						<col style="width: 7%;">
						<col style="width: 7%;">
						<col style="width: 7%;">
						<col style="width: 6%;">
						<col style="width: 6%;">
						<col style="width: 6%;">
						<col style="width: 6%;">
						<col style="width: 6%;">
						<col style="width: 6%;">
						<col style="width: 6%;">
						<col style="width: 6%;">
						<col style="width: 7%;">
						<col style="width: 8%;">
						<thead>
							<tr>
								<td><input id="selectAll" type="checkbox" onclick="checkAllOrNot(this,'routetab');" value=""/></td>
								  	<td><?php echo $appCommon->show_order('item_id',__('ID',true))?></td>
								  	<td><?php echo $appCommon->show_order('digits',__('Prefix',true))?></td>
								  	<td><?php echo $appCommon->show_order('strategy',__('Strategy',true))?></td>
								  	<td><?php echo $appCommon->show_order('time_profile',__('Time Profile',true))?></td>
								  	<td><?php echo $appCommon->show_order('route1',__('Trunk1',true))?></td>
								  	<td><?php echo $appCommon->show_order('route2',__('Trunk2',true))?></td>
								  	<td><?php echo $appCommon->show_order('route3',__('Trunk3',true))?></td>
						  			<td><?php echo $appCommon->show_order('route4',__('Trunk4',true))?></td>
					  				<td><?php echo $appCommon->show_order('route5',__('Trunk5',true))?></td>
					  				<td><?php echo $appCommon->show_order('route6',__('Trunk6',true))?></td>
					  				<td><?php echo $appCommon->show_order('route6',__('Trunk7',true))?></td>
					  				<td><?php echo $appCommon->show_order('route8',__('Trunk8',true))?></td>
					    <td class="last"><?php echo __('action')?></td>
							</tr>
						</thead>
						<tbody id="routetab">
							<?php
								$mydata = $p->getDataArray();
								$loop = count($mydata);
								for ($i = 0;$i<$loop;$i++) { 
							?>
								<tr class="row-1">
									<td style="text-align:center">
										<input class="select" type="checkbox" value="<?php echo $mydata[$i][0]['item_id']?>"/>
									</td>
									<td class="in-decimal"><?php echo $mydata[$i][0]['item_id']?></td>
									<td><?php echo $mydata[$i][0]['digits']?></td>
									<td>
											<?php 
												if ($mydata[$i][0]['strategy'] == 1) echo 'Top-Down';
												else if ($mydata[$i][0]['strategy'] == 0) echo 'By Percentage';
												else if ($mydata[$i][0]['strategy'] == 2) echo 'Round-Robin';
											?>
									</td>
									<td><?php echo $mydata[$i][0]['time_profile']?></td>
									<td><?php echo $mydata[$i][0]['route1']?></td>
									<td><?php echo $mydata[$i][0]['route2']?></td>
									<td><?php echo $mydata[$i][0]['route3']?></td>
									<td><?php echo $mydata[$i][0]['route4']?></td>
									<td><?php echo $mydata[$i][0]['route5']?></td>
									<td><?php echo $mydata[$i][0]['route6']?></td>
									<td><?php echo $mydata[$i][0]['route7']?></td>
									<td><?php echo $mydata[$i][0]['route8']?></td>
									<td>
											<a title="<?php echo __('edit')?>" style="float:left;margin-left:15px;" href="<?php echo $this->webroot?>products/edit_route/<?php echo $mydata[$i][0]['item_id']?>/<?php echo $id?>">
							    			<i class="icon-edit"></i>
							    		</a>
							    		<a title="<?php echo __('del')?>" style="float:left;margin-left:15px;" href="javascript:void(0)" onclick="delConfirm(this,'<?php echo $this->webroot?>products/del/<?php echo $mydata[$i][0]['item_id'];?>/<?php echo $id?>');">
							    			<i class="icon-remove"></i>
							    		</a>
									</td>
								</tr>
							<?php }?>
						</tbody>
					</table>
					<div id="tmppage">
					<?php echo $this->element('page');?>
					</div>
					<fieldset id="b-me">
				    <legend>
				    		<a href="#" onclick="$('#b-me').hide();$('#b-me-full').show();return false;">
				    			<span id="ht-100007"  >Mass Edit »</span>
				    		</a>
				    	</legend>
					</fieldset>
					<form action="<?php echo $this->webroot?>clientrates/updateAll/<?php echo array_keys_value($this->params,'pass.0')?>/massEdit/1/10" method="get" id="actionForm">
						<input type="hidden" class="input in-hidden" name="id" value="227" id="id"/>
						<input type="hidden" class="input in-hidden" name="stage" value="preview" id="stage_param"/>
						<fieldset id="b-me-full" style="display: none;">
						<legend>
							<a href="#" onclick="$('#b-me').show();$('#b-me-full').hide();return false;">Mass Edit Action:</a>
								<select class="input in-select select" name="action" id="action">
									<option value="update">update current Static Route Table</option>
									<option value="delete">delete found Static Route Table</option>
								</select>
						</legend>
						<div style="display: block;" id="actionPanelEdit">
						  <table cellspacing="0" cellpadding="0" border="0" class="form">
						    <tbody>
						    	 <tr>
						        <td class="label" style="width:18%;min-width:204px;">
						        	<label>
						        		<span id="ht-100008" class="helptip" rel="helptip">Strategy</span>
						        		<span id="ht-100008-tooltip" class="tooltip"></span>:
						        	</label> 
						        	<select class="input in-select select" name="route_strategy_options" id="route_strategy_options">
						        		<option value="none">preserve</option>
						        		<option value="set">set to</option>
						        	</select>
						        </td>
						        <td class="value" style="width:15%">
						        		<select class="input in-select select" name="strategy" id="strategy">
								    				<option value="1">» Top-Down</option>
								    				<option value="0">» By Percentage </option>
								    				<option value="2">» Round Robin</option>
								    		</select>
						        </td>
						        <td colspan=4 style="text-align:left;">
						        		<?php echo __('percentage',true);?>
						        		<?php echo $form->input('percentage1',Array('div'=>false,'style'=>'width:30px;display:none','label'=>false))?>
						        		<?php echo $form->input('percentage2',Array('div'=>false,'style'=>'width:30px;display:none','label'=>false))?>
						        		<?php echo $form->input('percentage3',Array('div'=>false,'style'=>'width:30px;display:none','label'=>false))?>
						        		<?php echo $form->input('percentage4',Array('div'=>false,'style'=>'width:30px;display:none','label'=>false))?>
						        		<?php echo $form->input('percentage5',Array('div'=>false,'style'=>'width:30px;display:none','label'=>false))?>
						        		<?php echo $form->input('percentage6',Array('div'=>false,'style'=>'width:30px;display:none','label'=>false))?>
						        		<?php echo $form->input('percentage7',Array('div'=>false,'style'=>'width:30px;display:none','label'=>false))?>
						        		<?php echo $form->input('percentage8',Array('div'=>false,'style'=>'width:30px;display:none','label'=>false))?>
						        </td>
						      </tr>
						      <tr>
						        <td class="label" style="width:15%">
						        	<label>
						        		<span id="ht-100009" class="helptip" rel="helptip"><?php echo __('Time Profile',true);?></span>:
						        	</label>
						        	<select class="input in-select select" name="route_time_profile_options" id="route_time_profile_options">
						        		<option value="none">preserve</option>
						        		<option value="set">set to</option>
						        	</select>
						        </td>
						        <td class="value" style="width:15%">
						        		<?php echo $form->input('time_profile',Array('div'=>false,'style'=>'width:80px','name'=>'time_profile','label'=>false,'options'=>$appProduct->_get_select_options($TimeProfile,'TimeProfile','time_profile_id','name')))?>
						        </td>
						        <td class="label" style="width:15%">
						        	<label>
						        		<span id="ht-100010" class="helptip" rel="helptip"><?php echo __('Trunk1',true);?></span>:
						        	</label>
						         <select class="input in-select select" name="route_trunk1_options" id="route_trunk1_options">
						         	<option value="none">preserve</option>
						         	<option value="set">set to</option>
						         </select>
						        </td>
						        <td class="value" style="width:15%">
								        <?php echo $form->input('trunk1',Array('div'=>false,'style'=>'width:80px','label'=>false,'options'=>$appProduct->_get_select_options($Resource,'Resource','resource_id','alias')))?>
						    			</td>
						        <td class="label" style="width:15%">
						        	<label>
						        		<span id="ht-100011" class="helptip" rel="helptip"><?php echo __('Trunk2',true);?></span>:
						        	</label> 
						        	<select class="input in-select select" name="route_trunk2_options" id="route_trunk2_options">
						        		<option value="none">preserve</option>
						        		<option value="set">set to</option>
						        	</select>
						        </td>
						        <td class="value" style="width:15%">
						        	  <?php echo $form->input('trunk2',Array('div'=>false,'label'=>false,'style'=>'width:80px','options'=>$appProduct->_get_select_options($Resource,'Resource','resource_id','alias')))?>
						        </td>
						        <td rowspan="3" class="buttons" style="width:15%">
						            <input type="button" class="input in-submit" onclick="Preview()" id="action_preview" value="Preview"/>
						            <br/>
						            <input type="submit" class="input in-button in-submit" id="action_process" onclick="return actionProcess();" value="Process"/>
						        </td>
						     </tr>
						     <tr>
						        <td class="label">
						        	<label>
						        		<span id="ht-100012" class="helptip" rel="helptip"><?php echo __('Trunk3',true);?></span>:
						        	</label>
						        	<select class="input in-select select" name="route_trunk3_options" id="route_trunk3_options">
						        		<option value="none">preserve</option>
						        		<option value="set">set to</option>
						        	</select>
						        </td>
						        <td class="value">
						        	  <?php echo $form->input('trunk3',Array('div'=>false,'style'=>'width:80px','label'=>false,'options'=>$appProduct->_get_select_options($Resource,'Resource','resource_id','alias')))?>
						        </td>
						        <td class="label">
						        	<label>
						        		<span id="ht-100013" class="helptip" rel="helptip"><?php echo __('Trunk4',true);?></span>:
						        	</label> 
						        	<select class="input in-select select" name="route_trunk4_options" id="route_trunk4_options">
						        		<option value="none">preserve</option>
						        		<option value="set">set to</option>
						        	</select>
						        </td>
						        <td class="value">
							          <?php echo $form->input('trunk4',Array('div'=>false,'style'=>'width:80px','label'=>false,'options'=>$appProduct->_get_select_options($Resource,'Resource','resource_id','alias')))?>
											</td>
						        <td class="label">
						        	<label>
						        		<span id="ht-100014" class="helptip" rel="helptip"><?php echo __('Trunk5',true);?></span>:
						        	</label>
						        	<select class="input in-select select" name="route_trunk5_options" id="route_trunk5_options">
						        		<option value="none">preserve</option>
						        		<option value="set">set to</option>
						        	</select>
						        </td>
						        <td class="value">
						        	  <?php echo $form->input('trunk5',Array('div'=>false,'style'=>'width:80px','label'=>false,'options'=>$appProduct->_get_select_options($Resource,'Resource','resource_id','alias')))?>
						        </td>      
						     </tr>
						     <tr>     
						        <td class="label">
						        	<label>
						        		<span id="ht-100015" class="helptip" rel="helptip"><?php echo __('Trunk6',true);?></span>:
						        	</label> 
						        	<select class="input in-select select" name="route_trunk6_options" id="route_trunk6_options">
						        		<option value="none">preserve</option>
						        		<option value="set">set to</option>
						        	</select>
						        </td>
						        <td class="value">
						        	  <?php echo $form->input('trunk6',Array('div'=>false,'style'=>'width:80px','label'=>false,'options'=>$appProduct->_get_select_options($Resource,'Resource','resource_id','alias')))?>
						        </td>
						        <td class="label">
						        	<label>
						        		<span id="ht-100016" class="helptip" rel="helptip"><?php echo __('Trunk7',true);?></span>:
						        	</label>
						        	<select class="input in-select select" name="route_trunk7_options" id="route_trunk7_options">
						        		<option value="none">preserve</option>
						        		<option value="set">set to</option>
						        	</select>
						        </td>
						        <td class="value">
						          <?php echo $form->input('trunk7',Array('div'=>false,'style'=>'width:80px','label'=>false,'options'=>$appProduct->_get_select_options($Resource,'Resource','resource_id','alias')))?>
						        </td>
						        <td class="label">
						        	<label>
						        		<span id="ht-100014" class="helptip" rel="helptip"><?php echo __('Trunk8',true);?></span>:
						        	</label>
						        	<select class="input in-select select" name="route_trunk8_options" id="route_trunk8_options">
						        		<option value="none">preserve</option>
						        		<option value="set">set to</option>
						        	</select>
						        </td>
						        <td class="value">
						          <?php echo $form->input('trunk8',Array('div'=>false,'label'=>false,'style'=>'width:80px','options'=>$appProduct->_get_select_options($Resource,'Resource','resource_id','alias')))?>
						        </td> 
						     </tr>
						    </tbody>
					   </table>
					   <script type="text/javascript">
					   jQuery('#actionPanelEdit #strategy').change(function(){
								if(jQuery(this).val()=='0'){
									jQuery('#actionPanelEdit').find('input[id^=percentage]').show().val('');
								}else{
									jQuery('#actionPanelEdit').find('input[id^=percentage]').hide();
								}
						   });
					   </script>
						</div>
						</fieldset>
						</form>
					<?php }?>
					</div>
<script type="text/javascript">
function Preview(){
	var action=jQuery('#actionForm').attr('action');
	action=action+='?type=view&<?php echo $this->params['getUrl']?>';
	jQuery('#actionForm').attr('aa','1111111111');
	alert(jQuery('#actionForm').attr('action'));
	jQuery('#actionForm').submit();
}
</script>
<script type="text/javascript">
jQuery(document).ready(function(){
		jQuery('#selectAll').selectAll('.select');
});
jQuery(document).ready(function(){
		jQuery('#route_strategy_options').change(function(){
				if(jQuery(this).val()=='none'){
					jQuery('#strategy').hide().val('').change();
				}else{
					jQuery('#strategy').show();
				}
		}).change();
		jQuery('#route_time_profile_options').change(function(){
				if(jQuery(this).val()=='none'){
					jQuery('#time_profile').hide().val('');
				}else{
					jQuery('#time_profile').show();
				}
		}).change();
		jQuery('#route_trunk1_options').change(function(){
				if(jQuery(this).val()=='none'){
					jQuery('#trunk1').hide().val('');
				}else{
					jQuery('#trunk1').show();
				}
		}).change();
		jQuery('#route_trunk2_options').change(function(){
			if(jQuery(this).val()=='none'){
				jQuery('#trunk2').hide().val('');
			}else{
				jQuery('#trunk2').show();
			}
		}).change();
		jQuery('#route_trunk3_options').change(function(){
				if(jQuery(this).val()=='none'){
					jQuery('#trunk3').hide().val('');
				}else{
					jQuery('#trunk3').show();
				}
		}).change();
		jQuery('#route_trunk4_options').change(function(){
				if(jQuery(this).val()=='none'){
					jQuery('#trunk4').hide().val('');
				}else{
					jQuery('#trunk4').show();
				}
		}).change();
		jQuery('#route_trunk5_options').change(function(){
				if(jQuery(this).val()=='none'){
					jQuery('#trunk5').hide().val('');
				}else{
					jQuery('#trunk5').show();
				}
		}).change();
		jQuery('#route_trunk6_options').change(function(){
				if(jQuery(this).val()=='none'){
					jQuery('#trunk6').hide().val('');
				}else{
					jQuery('#trunk6').show();
				}
		}).change();
		jQuery('#route_trunk7_options').change(function(){
				if(jQuery(this).val()=='none'){
					jQuery('#trunk7').hide().val('');
				}else{
					jQuery('#trunk7').show();
				}
		}).change();
		jQuery('#route_trunk8_options').change(function(){
			if(jQuery(this).val()=='none'){
				jQuery('#trunk8').hide().val('');
			}else{
				jQuery('#trunk8').show();
			}
		}).change();
		jQuery('input[id^=percentage]').xkeyvalidate({type:'Int'});
		jQuery('#actionForm').submit(function(){
			var percentagesum=0;
			var re=true;
			jQuery('input[id^=percentage]').each(function(){
				percentagesum+=Number(jQuery(this).val());
			});
			if(percentagesum!=100 && jQuery('#strategy').val()=='0' && jQuery('#strategy').val()!=0){
				jQuery.jGrowlError("The sum of all percentage must be equal to 100");
				re=false;
			}
			return false;
			return re;
		});
		jQuery('#adds').click(function(){
			jQuery('table.list').trAdd({
				ajax:'<?php echo $this->webroot?>products/static_js_save',
				action:''
			});
			return false;
		});
});
</script>

<!-- 上传文件 如果有错误信息则显示 -->
<?php 
		$upload_error = $session->read('upload_route_error');
		if (!empty($upload_error)) {
			$session->del('upload_route_error');
			$affectRows = $session->read('upload_commited_rows');
			$session->del('upload_commited_rows');
?>
		<script language=JavaScript>
		//提交的行数
			document.getElementById("affectrows").innerHTML = "<?php echo $affectRows?>";
			//错误信息
			var errormsg = eval("<?php echo $upload_error?>");
			var loop = errormsg.length;
			var msg = "";
			for (var i = 1;i<=loop; i++) {
				msg += errormsg[i-1].row+"<?php echo __('row')?>"+" : "+errormsg[i-1].name+errormsg[i-1].msg+"&nbsp;&nbsp;&nbsp;&nbsp;";
				if (i % 2 == 0) {
					msg += "<br/>";
				}

				if (i == loop) {
					msg += "<p>&nbsp;&nbsp;<p/>";
				}
				document.getElementById('route_upload_errorMsg').innerHTML = msg;
			}
			cover('uploadroute_error');
		</script>
<?php }?>
