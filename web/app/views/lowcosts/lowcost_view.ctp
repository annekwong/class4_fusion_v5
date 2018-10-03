
    <style type="text/css">.height20{height:20px;}.textRight{text-align:right;} .width110{width:120px;} .width60{width:80px;}</style>
    <script type="text/javascript" src="<?php echo $this->webroot?>js/My97DatePicker/WdatePicker.js"></script>
    <script type="text/javascript">
    function add(){
				var columns = [
				 {hidden:true},
				 {hidden:true},
				 {className:'width60 input in-text height20'},
				 {className:'textRight width60 input in-text  height20'},
				 {className:'textRight width60 input in-text  height20'},
				 {className:'textRight width60 input in-text  height20'},
				 {className:'textRight width60 input in-text  height20'},
				 {tag:'input',readonly:true,className:'marginTop9 wdate width110 in-text',ownevents:{onfocus:function(){WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'});}}},
    			{tag:'input',readonly:true,className:'marginTop9 wdate width110 in-text',ownevents:{onfocus:function(){WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'});}}},
				 {innerHTML:"<a style='float:left;margin-left:25px;' href='javascript:void(0)' onclick='save(this.parentNode.parentNode);'><img src='<?php echo $this->webroot?>images/menuIcon_004.gif' /></a><a  class='marginTop9' style='float:left;margin-left:15px;' href='javascript:void(0)' onclick='this.parentNode.parentNode.parentNode.removeChild(this.parentNode.parentNode)'><i class='icon-remove'></i></a>"},
			        			];
		
				createRow("producttab",columns);
			}

			function save(tr){
				var params = {
			             strategy_name :tr.cells[2].getElementsByTagName('input')[0].value,
			             low_cost :tr.cells[3].getElementsByTagName('input')[0].value,
			             gift_point :tr.cells[4].getElementsByTagName('input')[0].value,
			             gift_amount :tr.cells[5].getElementsByTagName('input')[0].value,
			             gift_money :tr.cells[6].getElementsByTagName('input')[0].value,
			             start_time :tr.cells[7].getElementsByTagName('input')[0].value,
			             end_time :tr.cells[8].getElementsByTagName('input')[0].value
			                			};

			        		if (validate_strategys(params))return;

			    					jQuery.post('<?php echo $this->webroot?>lowcosts/add_lowcost',params,function(data){
			    						var p = {theme:'jmsg-success',beforeClose:function(){location.reload();},life:100};
			 					       var  tmp = data.split("|");
			 					       if (tmp[1].trim() == 'false') p = {theme:'jmsg-alert',life:500};
			 					       	jGrowl_to_notyfy(tmp[0],p);
			        					});
			}

			function edit(tr){
				var columns = [
								 {hidden:true},
								 {hidden:true},
								 {className:'width60 input in-text height20'},
								 {className:'textRight width60 input in-text  height20'},
								 {className:'textRight width60 input in-text  height20'},
								 {className:'textRight width60 input in-text  height20'},
								 {className:'textRight width60 input in-text  height20'},
								 {tag:'input',defalutV:tr.cells[7].innerHTML.trim(),readonly:true,className:'marginTop9 wdate width110 in-text',ownevents:{onfocus:function(){WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'});}}},
		 			 				{tag:'input',defalutV:tr.cells[8].innerHTML.trim(),readonly:true,className:'marginTop9 wdate width110 in-text',ownevents:{onfocus:function(){WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'});}}},
								 {}
							        			];
						
					editRow(tr,columns);

					var btn = tr.cells[9].getElementsByTagName("a")[0];
	        		if (btn){
	        			var cancel = tr.cells[9].getElementsByTagName("a")[1].cloneNode(true);
	    	             cancel.title = "取消";
	    	             cancel.getElementsByTagName("img")[0].src="<?php echo $this->webroot?>images/rerating_queue.png";
	    	             cancel.onclick = function(){location.reload();}
	    	          			tr.cells[9].appendChild(cancel);
	    	          			
	        			btn.getElementsByTagName("img")[0].src="<?php echo $this->webroot?>images/menuIcon_004.gif";
	        			btn.onclick = function(){
	        				var params = {
	        						strategy_name :tr.cells[2].getElementsByTagName('input')[0].value,
		       			     low_cost :tr.cells[3].getElementsByTagName('input')[0].value,
		       			     gift_point :tr.cells[4].getElementsByTagName('input')[0].value,
		       			     gift_amount :tr.cells[5].getElementsByTagName('input')[0].value,
		       			     gift_money :tr.cells[6].getElementsByTagName('input')[0].value,
			       			  	start_time :tr.cells[7].getElementsByTagName('input')[0].value,
					           end_time :tr.cells[8].getElementsByTagName('input')[0].value,
		       			     id :tr.cells[1].innerHTML.trim()
	       			                	};

		            if (validate_strategys(params))return;

	       			   jQuery.post('<?php echo $this->webroot?>lowcosts/update_lowcost',params,function(data){
	       			    	var p = {theme:'jmsg-success',beforeClose:function(){location=location.toString()+"?edit_id="+params.id;},life:100};
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
					var amount_reg = /^[0-9]+(\.[0-9]{1,3})?$/;
					if (!data.strategy_name){has_error = true;error_msg.push(getMessage('enterstrname'));}
		
					if (!data.low_cost){
   						has_error=true;error_msg.push(getMessage('enterlowcost'));
    				} else {
   					 if (!amount_reg.test(data.low_cost)){
				    		has_error = true;error_msg.push(getMessage('lowcostformat'));
				    		}
        				}

//    				if (!data.gift_point && !data.gift_amount && !data.gift_money){
//    					has_error=true;error_msg.push(getMessage('allnull'));
//        	} else {
            if (data.gift_point){
               if (!/^\d+$/.test(data.gift_point)){
           				has_error=true;error_msg.push(getMessage('pointinteger'));
                   						}
                				}

            if (data.gift_amount){
                if (!amount_reg.test(data.gift_amount)){
            				has_error=true;error_msg.push(getMessage('giftamoundformat'));
                    						}
                 				}

            if (data.gift_money){
                if (!amount_reg.test(data.gift_money)){
            				has_error=true;error_msg.push(getMessage('giftmoneyformat'));
                    						}
                 				}
        				//}

	        		//循环打出提示信息
					if (has_error == true)
						for(var i = 0;i<error_msg.length;i++)
							jGrowl_to_notyfy(error_msg[i],{theme:'jmsg-alert'});
					
					return has_error;
				}

			function getMessage(key){
		var msg = {
			enterstrname :"<?php echo __('enterstrname')?>",
			enterlowcost :"<?php echo __('enterlowcost')?>",
			lowcostformat :"<?php echo __('lowcostformat')?>",
			allnull:"<?php echo __('allnull')?>",
			pointinteger:"<?php echo __('pointinteger')?>",
			giftmoneyformat:"<?php echo __('giftmoneyformat')?>"
        			};
				return msg[key];
				}
    </script>
<div id="cover"></div>
<div id="title">
  <h1><?php echo __('account')?>&gt;&gt;
    	<?php echo __('lowcoststrategy')?>
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
    			<a class="link_back" href="<?php echo $this->webroot?>lowcosts/lowcost_view">
    				<img width="16" height="16"  alt="" src="<?php echo $this->webroot?>images/icon_back_white.png">
    				&nbsp;<?php echo __('goback')?>
    			</a>
    		</li>
        <?php }?>
    <li><a class="link_btn" href="javascript:void(0)" onclick="add();"><img width="16" height="16" alt="" src="<?php echo $this->webroot?>images/add.png"> <?php echo __('createnew')?></a></li>
    <li><a class="link_btn" rel="popup" href="javascript:void(0)" onclick="deleteAll('<?php echo $this->webroot?>clientgroups/del_client_group/all');" ><i class="icon-remove"></i> <?php echo __('deleteall')?></a></li>
    <li><a class="link_btn" rel="popup" href="javascript:void(0)" onclick="deleteSelected('producttab','<?php echo $this->webroot?>clientgroups/del_client_group/selected');"><i class="icon-remove"></i> <?php echo __('deleteselected')?></a></li>
  </ul>
</div>
<div id="container">
<div id="toppage"></div>
<table class="list">
<col style="width: 4%;">
	<col style="width: 5%;">
	<col style="width: 10%;">
	<col style="width: 7%;">
	<col style="width: 7%;">
	<col style="width: 7%;">
	<col style="width: 15%;">
	<col style="width: 15%;">
	<col style="width: 15%;">
	<thead>
		<tr>
		<td><input type="checkbox" onclick="checkAllOrNot(this,'producttab');" value=""/></td>
    <td><a href="javascript:void(0)" onclick="my_sort('low_cost_strategy_id','asc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<?php echo __('id',true);?>&nbsp;<a href="javascript:void(0)" onclick="my_sort('low_cost_strategy_id','desc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
    <td><a href="javascript:void(0)" onclick="my_sort('strategy_name','asc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<?php echo __('strategy_name')?>&nbsp;<a href="javascript:void(0)" onclick="my_sort('strategy_name','desc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
    <td><a href="javascript:void(0)" onclick="my_sort('low_cost','asc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<?php echo __('low_cost')?>&nbsp;<a href="javascript:void(0)" onclick="my_sort('low_cost','desc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
    <td><a href="javascript:void(0)" onclick="my_sort('gift_point','asc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<?php echo __('gift_point')?>&nbsp;<a href="javascript:void(0)" onclick="my_sort('gift_point','desc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
    <td><a href="javascript:void(0)" onclick="my_sort('gift_amount','asc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<?php echo __('gift_amount')?>&nbsp;<a href="javascript:void(0)" onclick="my_sort('gift_amount','desc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
    <td><a href="javascript:void(0)" onclick="my_sort('gift_money','asc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<?php echo __('gift_money')?>&nbsp;<a href="javascript:void(0)" onclick="my_sort('gift_money','desc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
    <td><a href="javascript:void(0)" onclick="my_sort('start_time','asc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<?php echo __('start_time',true);?>&nbsp;<a href="javascript:void(0)" onclick="my_sort('start_time','desc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
    <td><a href="javascript:void(0)" onclick="my_sort('end_time','asc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<?php echo __('end_time',true);?>&nbsp;<a href="javascript:void(0)" onclick="my_sort('end_time','desc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
    <td class="last"><?php echo __('action')?></td>
		</tr>
	</thead>
	<tbody id="producttab">
		<?php 
			$mydata =$p->getDataArray();
			$loop = count($mydata); 
			for ($i=0;$i<$loop;$i++) {?>
				<tr class="row-1">
				<td style="text-align:center"><input type="checkbox" value="<?php echo $mydata[$i][0]['low_cost_strategy_id']?>"/></td>
		    <td><?php echo $mydata[$i][0]['low_cost_strategy_id']?></td>
		    <td style="font-weight: bold;"><?php echo $mydata[$i][0]['strategy_name']?></td>
		    <td style="color:green;"><?php echo $mydata[$i][0]['low_cost']?></td>
		    <td style="color:red;"><?php echo $mydata[$i][0]['gift_point']?></td>
		    <td style="color:green;"><?php echo $mydata[$i][0]['gift_amount']?></td>
		    <td style="color:green;"><?php echo $mydata[$i][0]['gift_money']?></td>
		    <td><?php echo $mydata[$i][0]['start_time']?></td>
		    <td><?php echo $mydata[$i][0]['end_time']?></td>
		    <td >
		    		<a title="<?php echo __('edit')?>" style="float:left;margin-left:25px;" href="javascript:void(0)" onclick="edit(this.parentNode.parentNode);">
		    			<i class="icon-edit"></i>
		    		</a>
		    		<a title="<?php echo __('del')?>" style="float:left;margin-left:15px;" href="javascript:void(0)" onclick="delConfirm(this,'<?php echo $this->webroot?>lowcosts/del_lowcost/<?php echo $mydata[$i][0]['low_cost_strategy_id']?>');">
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