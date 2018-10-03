
    <style type="text/css">
				.width90{width:80px;}
				.width110{width:120px;}
				.height16{height:20px;}
				.width100{width:100px;}
				.textRight{text-align:right;}
				.marginTop9{margin-top:5px;};
			</style>
    	<script language="JavaScript" type="text/javascript">
    				function add(){
        			var columns = [
        			 {hidden:true},
        			 {hidden:true},
        			 {className:'marginTop9 height16 width90 input in-text'},
        			 {className:'marginTop9 height16 width90 input in-text'},
        			 {className:'marginTop9 height16 width90 input in-text'},
        			 {className:'marginTop9 height16 width90 input in-text'},
        			 {className:'marginTop9 height16 width90 input in-text'},
        			 {className:'marginTop9 height16 width90 input in-text'},
        			 {className:'marginTop9 height16 width90 input in-text'},
        			 {innerHTML:"<a title='保存' class='marginTop9' style='float:left;margin-left:38px;' href='javascript:void(0)' onclick='save_code(this.parentNode.parentNode);'><img src='<?php echo $this->webroot?>images/menuIcon_004.gif' /></a><a title='删除'  class='marginTop9' style='float:left;margin-left:10px;' href='javascript:void(0)' onclick='this.parentNode.parentNode.parentNode.removeChild(this.parentNode.parentNode)'><i class='icon-remove'></i></a>"},
       		        			];

        			createRow("loc_codes",columns);
        				}

    				function save_code(tr){
        		var params = {
             country_code:tr.cells[2].getElementsByTagName('input')[0].value,
             country:tr.cells[3].getElementsByTagName('input')[0].value,
             state_code:tr.cells[4].getElementsByTagName('input')[0].value,
             state:tr.cells[5].getElementsByTagName('input')[0].value,
             city_code:tr.cells[6].getElementsByTagName('input')[0].value,
             city:tr.cells[7].getElementsByTagName('input')[0].value,
             number:tr.cells[8].getElementsByTagName('input')[0].value
                			};

        		if (validate_location(params))return;

    					jQuery.post('<?php echo $this->webroot?>locationcodes/add_location',params,function(data){
    						var p = {theme:'jmsg-success',beforeClose:function(){location.reload();},life:100};
 					       var  tmp = data.split("|");
 					       if (tmp[1].trim() == 'false') p = {theme:'jmsg-alert',life:500};
 					       	jGrowl_to_notyfy(tmp[0],p);
        					});
        				}

    				function edit_code(currRow){
        		var columns = [
        		               {},{},
        		 {className:' height16 width90 input in-text'},
      	    {className:' height16 width90 input in-text'},
      	    {className:' height16 width90 input in-text'},
      	    {className:' height16 width90 input in-text'},
      	    {className:' height16 width90 input in-text'},
      	    {className:' height16 width90 input in-text'},
        	  {className:' height16 width90 input in-text'},
      	    				  {}
       		        		];
        		editRow(currRow,columns);

        		var btn = currRow.cells[9].getElementsByTagName("a")[0];
        		if (btn){
        			var cancel = currRow.cells[9].getElementsByTagName("a")[1].cloneNode(true);
                    cancel.title = "取消";
                    cancel.getElementsByTagName("img")[0].src="<?php echo $this->webroot?>images/rerating_queue.png";
                    cancel.onclick = function(){location.reload();}
                 			currRow.cells[9].appendChild(cancel);
        			btn.getElementsByTagName("img")[0].src="<?php echo $this->webroot?>images/menuIcon_004.gif";
        			btn.onclick = function(){
        				var params = {
       			      country_code:currRow.cells[2].getElementsByTagName('input')[0].value,
       			      country:currRow.cells[3].getElementsByTagName('input')[0].value,
       			      state_code:currRow.cells[4].getElementsByTagName('input')[0].value,
       			      state:currRow.cells[5].getElementsByTagName('input')[0].value,
       			      city_code:currRow.cells[6].getElementsByTagName('input')[0].value,
       			      city:currRow.cells[7].getElementsByTagName('input')[0].value,
          			   number:currRow.cells[8].getElementsByTagName('input')[0].value,
       			      location_id:currRow.cells[1].innerHTML
       			                	};

	            if (validate_location(params))return;

       			   jQuery.post('<?php echo $this->webroot?>locationcodes/edit_location',params,function(data){
       			    	var p = {theme:'jmsg-success',beforeClose:function(){location=location.toString()+"?edit_id="+params.location_id},life:100};
       			 			var  tmp = data.split("|");
       			 			if (tmp[1].trim() == 'false') p = {theme:'jmsg-alert',life:500};
       			 				jGrowl_to_notyfy(tmp[0],p);
       			        			});
                				}	
        					}
        				}

    				//添加和修改时的数据验证
    				function validate_location(data){
    					var has_error = false;
							var error_msg = [];
        		if (!data.country_code){has_error = true;error_msg.push(getMessage('countrycodenull'));}
        		if (!data.country){has_error = true;error_msg.push(getMessage('countrynull'));}
        		if (!data.state_code){has_error = true;error_msg.push(getMessage('statecodenull'));}
        		if (!data.state){has_error = true;error_msg.push(getMessage('statenull'));}
        		if (!data.city_code){has_error = true;error_msg.push(getMessage('citycodenull'));}
        		if (!data.city){has_error = true;error_msg.push(getMessage('citynull'));}
        		if (!data.number){has_error = true;error_msg.push(getMessage('numbernull'));}
        		else if (!/^[0-9a-zA-Z]+$/.test(data.number)){has_error = true;error_msg.push(getMessage('numberformat'));}

			        		//循环打出提示信息
							if (has_error == true)
								for(var i = 0;i<error_msg.length;i++)
									jGrowl_to_notyfy(error_msg[i],{theme:'jmsg-alert'});
							
							return has_error;
        				}

    				function getMessage(key){
        		var msg = {
        			countrycodenull:"<?php echo __('countrycodenull')?>",
        			countrynull:"<?php echo __('countrynull')?>",
        			statecodenull:"<?php echo __('statecodenull')?>",
        			statenull:"<?php echo __('statenull')?>",
        			citycodenull:"<?php echo __('citycodenull')?>",
        			citynull:"<?php echo __('citynull')?>",
        			numbernull:"<?php echo __('numbernull')?>",
        			numberformat:"<?php echo __('numberformat')?>"
                			};
    					return msg[key];
    					}
    	</script>
<div id="cover"></div>
<div id="title">
  <h1>
    	<?php __('Location')?>
   <!--  <a title="add to smartbar" href="/admin/_view/sbAdd?link=/rate_tables/list">
   	<img width="10" height="10" alt="+" src="<?php echo $this->webroot?>images/qb-plus.png" />
   </a>-->
  </h1>
  <ul id="title-search">
    <li>
	    	<form>
	    		<input type="text" id="search-_q" class="in-search default-value input in-text defaultText" title="<?php echo __('namesearch')?>" value="<?php if (!empty($search)) echo $search;?>" name="search">
	    	</form>
    </li>
    <!--  <li title="advanced search »" onclick="advSearchToggle();" id="title-search-adv" style="display: list-item;"></li>-->
  </ul>
    
  <ul id="title-menu">
  <?php if (isset($edit_return)) {?>
        <li>
    			<a class="link_back" href="<?php echo $this->webroot?>locationcodes/location_codes">
    				<img width="16" height="16"  alt="" src="<?php echo $this->webroot?>images/icon_back_white.png">
    				&nbsp;<?php echo __('goback')?>
    			</a>
    		</li>
        <?php }?>
    <li><a class="link_btn" rel="popup" href="javascript:void(0)" onclick="add();"><img width="16" height="16" alt="" src="<?php echo $this->webroot?>images/add.png"> <?php echo __('createnew')?></a></li>
    <li><a class="link_btn" rel="popup" href="javascript:void(0)" onclick="deleteAll('<?php echo $this->webroot?>locationcodes/del_location/all');" ><i class="icon-remove"></i> <?php echo __('deleteall')?></a></li>
    <li><a class="link_btn" rel="popup" href="javascript:void(0)" onclick="deleteSelected('loc_codes','<?php echo $this->webroot?>locationcodes/del_location/selected');"><i class="icon-remove"></i> <?php echo __('deleteselected')?></a></li>
  </ul>
</div>

<div id="container">
<div id="toppage"></div>

<table class="list">
<col style="width: 4%;">
	<col style="width: 4%;">
	<col style="width: 11%;">
	<col style="width: 11%;">
	<col style="width: 11%;">
	<col style="width: 11%;">
	<col style="width: 12%;">
	<col style="width: 12%;">
	<col style="width: 12%;">
	<thead>
		<tr>
		<td><input type="checkbox" onclick="checkAllOrNot(this,'loc_codes');" value=""/></td>
			<td><a href="javascript:void(0)" onclick="my_sort('location_id','asc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<?php echo __('id',true);?><a href="javascript:void(0)" onclick="my_sort('location_id','desc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
    <td><a href="javascript:void(0)" onclick="my_sort('country_code','asc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<?php echo __('country_code')?>&nbsp;<a href="javascript:void(0)" onclick="my_sort('country_code','desc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
    <td><a href="javascript:void(0)" onclick="my_sort('country','asc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<?php echo __('country')?>&nbsp;<a href="javascript:void(0)" onclick="my_sort('country','desc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
    <td><a href="javascript:void(0)" onclick="my_sort('state_code','asc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<?php echo __('state_code')?>&nbsp;<a href="javascript:void(0)" onclick="my_sort('state_code','desc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
    <td><a href="javascript:void(0)" onclick="my_sort('state','asc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<?php echo __('state')?>&nbsp;<a href="javascript:void(0)" onclick="my_sort('state','desc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
    <td><a href="javascript:void(0)" onclick="my_sort('city_code','asc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<?php echo __('city_code')?>&nbsp;<a href="javascript:void(0)" onclick="my_sort('city_code','desc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
    <td><a href="javascript:void(0)" onclick="my_sort('city','asc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<?php echo __('city')?>&nbsp;<a href="javascript:void(0)" onclick="my_sort('city','desc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
    <td><a href="javascript:void(0)" onclick="my_sort('number','asc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<?php echo __('qh')?>&nbsp;<a href="javascript:void(0)" onclick="my_sort('number','desc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
    <td class="last"><?php echo __('action')?></td>
		</tr>
	</thead>
	<tbody id="loc_codes">
		<?php 
			$mydata =$p->getDataArray();
			$loop = count($mydata); 
			for ($i=0;$i<$loop;$i++) {?>
				<tr class="row-1">
				<td style="text-align:center"><input type="checkbox" value="<?php echo $mydata[$i][0]['location_id']?>"/></td>
				<td class="in-decimal" style="text-align:center"><?php echo $mydata[$i][0]['location_id']?></td>
		    <td style="font-weight: bold;"><?php echo $mydata[$i][0]['country_code']?></td>
		    <td><?php echo $mydata[$i][0]['country']?></td>
		    <td style="font-weight: bold;"><?php echo $mydata[$i][0]['state_code']?></td>
		    <td><?php echo $mydata[$i][0]['state']?></td>
		    <td style="font-weight: bold;"><?php echo $mydata[$i][0]['city_code']?></td>
		    <td><?php echo $mydata[$i][0]['city']?></td>
		    <td><?php echo $mydata[$i][0]['number']?></td>
		    <td >
		    		<a title="<?php echo __('edit')?>" style="float:left;margin-left:38px;" href="javascript:void(0)" onclick="edit_code(this.parentNode.parentNode)">
		    			<i class="icon-edit"></i>
		    		</a>
		    		<a title="<?php echo __('del')?>" style="float:left;margin-left:20px;" href="javascript:void(0)" onclick="delConfirm(this,'<?php echo $this->webroot?>locationcodes/del_location/<?php echo $mydata[$i][0]['location_id']?>');">
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
<?php $d = $p->getDataArray();if (count($d) == 0) {?>
<div class="msg"><?php echo __('no_data_found')?></div>
<?php }?>
</div>
<div id="livemargins_control" style="position: absolute; display: none; z-index: 9999;"><img width="77" height="5" style="position: absolute; left: -77px; top: -5px;" src="chrome://livemargins/skin/monitor-background-horizontal.png">	<img style="position: absolute; left: 0pt; top: -5px;" src="chrome://livemargins/skin/monitor-background-vertical.png">	<img style="position: absolute; left: 1px; top: 0pt; opacity: 0.5; cursor: pointer;" onmouseout="this.style.opacity=0.5" onmouseover="this.style.opacity=1" src="chrome://livemargins/skin/monitor-play-button.png" id="monitor-play-button"></div>