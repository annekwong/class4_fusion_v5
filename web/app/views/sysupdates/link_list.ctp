
    <style type="text/css">
				.width90{width:80px;}
				.widthfull{width:100%;}
				.height16{height:20px;}
				.width100{width:100px;}
				.textRight{text-align:right;}
				.marginTop9{margin-top:5px;};
			</style>
    	<script language="JavaScript" type="text/javascript">
    				function add(){
        			var columns = [
        			 {hidden:true},
        			 {className:'marginTop9 width90 input in-text height16'},
        			 {innerHTML:$('#tmpres').html()},
        			 {innerHTML:$('#tmppc').html()},
        			 {className:'marginTop9 width90 input in-text height16'},
        			 {className:'marginTop9 input in-text widthfull height16'},
        			 {innerHTML:"<a class='marginTop9' style='float:left;margin-left:35px;' href='javascript:void(0)' onclick='save_code(this.parentNode.parentNode);'><img src='<?php echo $this->webroot?>images/menuIcon_004.gif' /></a><a  class='marginTop9' style='float:left;margin-left:20px;' href='javascript:void(0)' onclick='this.parentNode.parentNode.parentNode.removeChild(this.parentNode.parentNode)'><i class='icon-remove'></i></a>"},
       		        			];

        			createRow("rec_strategy",columns);
        				}

    				function save_code(tr){
        		var params = {
             soft_name :tr.cells[1].getElementsByTagName('input')[0].value,
             reseller_id :tr.cells[2].getElementsByTagName('select')[0].value,
             phone_type :tr.cells[3].getElementsByTagName('select')[0].value,
             soft_version :tr.cells[4].getElementsByTagName('input')[0].value,
             soft_link :tr.cells[5].getElementsByTagName('input')[0].value
                			};

        		if (validate_strategys(params))return;

    					jQuery.post('<?php echo $this->webroot?>sysupdates/add',params,function(data){
    						var p = {theme:'jmsg-success',beforeClose:function(){location.reload();},life:100};
 					       var  tmp = data.split("|");
 					       if (tmp[1].trim() == 'false') p = {theme:'jmsg-alert',life:500};
 					       	jGrowl_to_notyfy(tmp[0],p);
        					});
        				}

    				function edit_code(currRow){
    					var columns = [
  				        			 {hidden:true},
  				        			 {className:'marginTop9 width90 input in-text height16'},
  				        			 {},
  				        			 {},
  				        			 {className:'marginTop9 width90 input in-text height16'},
  				        			 {className:'marginTop9 input in-text widthfull height16'},
  				        			 {}
  				       		        			];
        		editRow(currRow,columns);

        		currRow.cells[2].innerHTML = $('#tmpres').html();

        		currRow.cells[3].innerHTML = $('#tmppc').html();

        		currRow.cells[2].getElementsByTagName("select")[0].value = currRow.cells[7].innerHTML.trim();
        		currRow.cells[3].getElementsByTagName("select")[0].value = currRow.cells[8].innerHTML.trim();

        		var btn = currRow.cells[6].getElementsByTagName("a")[0];
        		if (btn){
        			var cancel = currRow.cells[6].getElementsByTagName("a")[1].cloneNode(true);
      	             cancel.title = "取消";
      	             cancel.getElementsByTagName("img")[0].src="<?php echo $this->webroot?>images/rerating_queue.png";
      	             cancel.onclick = function(){location.reload();}
      	          			currRow.cells[6].appendChild(cancel);
      	          			
        			btn.getElementsByTagName("img")[0].src="<?php echo $this->webroot?>images/menuIcon_004.gif";
        			btn.onclick = function(){
        				var params = {
       			             soft_name :currRow.cells[1].getElementsByTagName('input')[0].value,
       			             reseller_id :currRow.cells[2].getElementsByTagName('select')[0].value,
       			             phone_type :currRow.cells[3].getElementsByTagName('select')[0].value,
       			             soft_version :currRow.cells[4].getElementsByTagName('input')[0].value,
       			             soft_link :currRow.cells[5].getElementsByTagName('input')[0].value,
       			             id :currRow.cells[0].innerHTML.trim()
       			                			};

	            if (validate_strategys(params))return;

       			   jQuery.post('<?php echo $this->webroot?>sysupdates/update',params,function(data){
       			    	var p = {theme:'jmsg-success',beforeClose:function(){location=location.pathname+"?edit_id="+params.id;},life:100};
       			 			var  tmp = data.split("|");
       			 			if (tmp[1].trim() == 'false') p = {theme:'jmsg-alert',life:500};
       			 				jGrowl_to_notyfy(tmp[0],p);
       			        			});
                				}	
        					}
        				}

    				//添加和修改时的数据验证
    				function validate_strategys(data){
    					var has_error = false;
							var error_msg = [];
        		if (!data.soft_name){has_error = true;error_msg.push(getMessage('entersoftname'));}
        		if (!data.soft_version){has_error = true;error_msg.push(getMessage('enterversion'));}
        		if (!data.soft_link){has_error = true;error_msg.push(getMessage('entersoft_link'));}
        		
			        		//循环打出提示信息
							if (has_error == true)
								for(var i = 0;i<error_msg.length;i++)
									jGrowl_to_notyfy(error_msg[i],{theme:'jmsg-alert'});
							
							return has_error;
        				}

    				function getMessage(key){
        		var msg = {
        				entersoftname:"<?php echo __('entersoftname')?>",
        				enterversion:"<?php echo __('enterversion')?>",
        				entersoft_link:"<?php echo __('entersoft_link')?>"
                			};
    					return msg[key];
    					}
    	</script>
<div id="tmppc" style="display:none;">

 		<?php echo $form->input('update_type',
 		array('options'=>$update_type,'label'=>false ,'div'=>false,'type'=>'select'));?>

</div>

<div id="tmpres" style="display:none;" class="height16">
<select  class="in-select input marginTop9" style="width:100px;height:20px;">
				    					<?php
							for ($i=0;$i<count($r_reseller);$i++){ 
						?>
								<option value="<?php echo $r_reseller[$i][0]['reseller_id']?>">
									<?php
										$space = "";
										for ($j=0;$j<$r_reseller[$i][0]['spaces'];$j++) {
											 	$space .= "&nbsp;&nbsp;";
										}
										if ($i==0){
											echo "{$r_reseller[$i][0]['name']}";
										} else {
											echo "&nbsp;&nbsp;".$space."↳".$r_reseller[$i][0]['name'];
										}
									?>
								</option>
							<?php
								} 
							?>
				    		</select>
</div>


<div id="title">
  <h1><?php echo __('systemc')?>&gt;&gt;
  </h1>
  <ul id="title-search">
    <li>
	    	<form>
	    		<input type="text" id="search-_q" class="in-search default-value input in-text defaultText" title="<?php echo __('namesearch')?>" value="<?php if (!empty($search)) echo $search;?>" name="search">
	    	</form>
    </li>
  </ul>
    
  <ul id="title-menu">
  <?php if (isset($edit_return)) {?>
        <li>
    			<a class="link_back" href="<?php echo $this->webroot?>sysupdates/link_list">
    				<img width="16" height="16"  alt="" src="<?php echo $this->webroot?>images/icon_back_white.png">
    				&nbsp;<?php echo __('gobackall')?>
    			</a>
    		</li>
        <?php }?>
    <li><a class="link_btn" rel="popup" href="javascript:void(0)" onclick="add();"><img width="16" height="16" alt="" src="<?php echo $this->webroot?>images/add.png"> <?php echo __('createnew')?></a></li>
  <li><a class="link_btn" rel="popup" href="javascript:void(0)" onclick="cover('viewmessage')">
  <img width="16" height="16" alt="" src="<?php echo $this->webroot?>images/add.png"> 添加终端类型</a></li>
  </ul>
</div>
<div id="cover" ></div>
<dl style="display:none;position: absolute; left: 40%; top: 30%; width: 300px; height: auto; z-index: 99;" class="tooltip-styled" id="viewmessage">
<div style="text-align: center; width: 100%; height: 25px; font-size: 16px;">添加终端类型</div>
    <div style="margin-top: 10px;">
    			<span>&nbsp;&nbsp;&nbsp;&nbsp;终端ID</span>:<input style="height: 20px; width: 200px; float: right;" name="update_id" id="update_id" class="input in-text">
    			<input style="display: none;" id="ip_id" class="input in-text">
    </div>
    
    <div style="margin-top: 20px;">
    			<span>&nbsp;&nbsp;&nbsp;&nbsp;终端名字</span>:<input style="height: 20px; width: 200px; float: right;" name="update_name" id="update_name" class="input in-text">
    </div>
    

	<div style="margin-top: 10px; margin-left: 26%; width: 150px; height: auto;">
		<input type="button" class="input in-button" value="<?php echo __('submit',true);?>" onclick="addsystem_update_types();">
		<input type="button" class="input in-button" value="<?php echo __('cancel',true);?>" onclick="closeCover('viewmessage');">
	</div>
</dl>



<div id="container">
<div id="toppage"></div>

<table class="list">
	<col style="width: 5%;">
	<col style="width: 12%;">
	<col style="width: 12%;">
	<col style="width: 7%;">
	<col style="width: 6%;">
	<col style="width: 35%;">
	<col style="width: 15%;">
	<thead>
		<tr>
			<td><a href="javascript:void(0)" onclick="my_sort('system_update_id','asc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<?php echo __('id',true);?><a href="javascript:void(0)" onclick="my_sort('system_update_id','desc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
    <td><a href="javascript:void(0)" onclick="my_sort('soft_name','asc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<?php echo __('soft_name')?>&nbsp;<a href="javascript:void(0)" onclick="my_sort('soft_name','desc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
    <td><a href="javascript:void(0)" onclick="my_sort('reseller','asc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<?php echo __('Reseller')?>&nbsp;<a href="javascript:void(0)" onclick="my_sort('reseller','desc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
    <td id="by_payment"><a href="javascript:void(0)" onclick="my_sort('phone_type','asc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<?php echo __('phone_type')?>&nbsp;<a href="javascript:void(0)" onclick="my_sort('phone_type','desc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
    <td><a href="javascript:void(0)" onclick="my_sort('soft_version','asc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<?php echo __('soft_version')?>&nbsp;<a href="javascript:void(0)" onclick="my_sort('soft_version','desc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
    <td><a href="javascript:void(0)" onclick="my_sort('soft_link','asc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<?php echo __('soft_link')?>&nbsp;<a href="javascript:void(0)" onclick="my_sort('soft_link','desc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
    <td class="last"><?php echo __('action')?></td>
		</tr>
	</thead>
	<tbody id="rec_strategy">
		<?php 
			$mydata =$p->getDataArray();
			$loop = count($mydata); 
			for ($i=0;$i<$loop;$i++) {?>
				<tr class="row-1">
				<td class="in-decimal" style="text-align:center"><?php echo $mydata[$i][0]['system_update_id']?></td>
		    <td style="font-weight: bold;"><?php echo $mydata[$i][0]['soft_name']?></td>
		    <td><?php echo $mydata[$i][0]['reseller']?></td>
		    <td><?php echo $mydata[$i][0]['phone_type']?></td>
		    <td><?php echo $mydata[$i][0]['soft_version']?></td>
		    <td><?php echo $mydata[$i][0]['soft_link']?></td>
		    <td>
		    		<a title="<?php echo __('edit')?>" style="float:left;margin-left:35px;" href="javascript:void(0)" onclick="edit_code(this.parentNode.parentNode)">
		    			<i class="icon-edit"></i>
		    		</a>
		    		<a title="<?php echo __('del')?>" style="float:left;margin-left:20px;" href="javascript:void(0)" onclick="delConfirm(this,'<?php echo $this->webroot?>recommends/del_rec/<?php echo $mydata[$i][0]['system_update_id']?>');">
		    			<i class="icon-remove"></i>
		    		</a>
		    </td>
		    <td style="display:none"><?php echo $mydata[$i][0]['reseller_id']?></td>
		    <td style="display:none"><?php echo $mydata[$i][0]['phone_type']?></td>
				</tr>
		<?php }?>		
	</tbody>
	<tbody>
</tbody>
</table>
<div id="tmppage">
<?php echo $this->element('page');?>
</div>
		<?php $d = $p->getDataArray();if (count($d) == 0) {?>
<div class="msg"><?php echo __('no_data_found')?></div>
<?php }?>
</div>
<div id="livemargins_control" style="position: absolute; display: none; z-index: 9999;"><img width="77" height="5" style="position: absolute; left: -77px; top: -5px;" src="chrome://livemargins/skin/monitor-background-horizontal.png">	<img style="position: absolute; left: 0pt; top: -5px;" src="chrome://livemargins/skin/monitor-background-vertical.png">	<img style="position: absolute; left: 1px; top: 0pt; opacity: 0.5; cursor: pointer;" onmouseout="this.style.opacity=0.5" onmouseover="this.style.opacity=1" src="chrome://livemargins/skin/monitor-play-button.png" id="monitor-play-button"></div>