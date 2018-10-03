
    <script type="text/javascript" src="<?php echo $this->webroot?>js/My97DatePicker/WdatePicker.js"></script>
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
        			 {className:'marginTop9 width90 input in-text'},
        			 {tag:'select',options:[{k:'0',v:getMessage('recommending')},{k:'1',v:getMessage('afterrefill')}],className:'marginTop9 width90 input in-select',
            			 	ownevents:{
          			 					onchange:
              			 				function(){
        				 								var byp = this.parentNode.parentNode.cells[8];
          			 								if (this.value==1){
              			 						byp.getElementsByTagName("input")[0].disabled = false;
              			 					} else {
              			 						byp.getElementsByTagName("input")[0].checked = false;
              			 						byp.getElementsByTagName("input")[0].disabled = true;
              			 						$('#by_payment').text('基础金');
                  			 										}
              			 								}
												}
									},
        			 {className:'textRight marginTop9 width90 input in-text'},
        			 {className:'textRight marginTop9 width90 input in-text'},
        			 {className:'textRight marginTop9 width90 input in-text'},
        			 {tag:'input',readonly:true,className:'marginTop9 wdate width110 in-text',ownevents:{onfocus:function(){WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'});}}},
        			 {tag:'input',readonly:true,className:'marginTop9 wdate width110 in-text',ownevents:{onfocus:function(){WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'});}}},
        			 {innerHTML:"<input class='marginTop9' disabled type='checkbox' onclick=\" if (this.checked)$('#by_payment').text('%');else $('#by_payment').text('基础金'); \"/>"},
        			 {innerHTML:"<a class='marginTop9' style='float:left;margin-left:35px;' href='javascript:void(0)' onclick='save_code(this.parentNode.parentNode);'><img src='<?php echo $this->webroot?>images/menuIcon_004.gif' /></a><a  class='marginTop9' style='float:left;margin-left:20px;' href='javascript:void(0)' onclick='this.parentNode.parentNode.parentNode.removeChild(this.parentNode.parentNode)'><i class='icon-remove'></i></a>"},
       		        			];

        			createRow("rec_strategy",columns);
        				}

    				function save_code(tr){
        		var params = {
             strategy_name :tr.cells[1].getElementsByTagName('input')[0].value,
             gift_type :tr.cells[2].getElementsByTagName('select')[0].value,
             basic_amount :tr.cells[3].getElementsByTagName('input')[0].value,
             gift_amount :tr.cells[4].getElementsByTagName('input')[0].value,
             gift_point :tr.cells[5].getElementsByTagName('input')[0].value,
             start_time :tr.cells[6].getElementsByTagName('input')[0].value,
             end_time :tr.cells[7].getElementsByTagName('input')[0].value,
             by_first_payment : tr.cells[8].getElementsByTagName('input')[0].checked==true?'true':'false'
                			};

        		if (validate_strategys(params))return;

    					jQuery.post('<?php echo $this->webroot?>recommends/add',params,function(data){
    						var p = {theme:'jmsg-success',beforeClose:function(){location.reload();},life:100};
 					       var  tmp = data.split("|");
 					       if (tmp[1].trim() == 'false') p = {theme:'jmsg-alert',life:500};
 					       	jGrowl_to_notyfy(tmp[0],p);
        					});
        				}

    				function edit_code(currRow){
        		var columns = [
        		 				{},
        		 {className:' width90 input in-text'},
      	    {tag:'select',options:[{k:'0',v:getMessage('recommending')},{k:'1',v:getMessage('afterrefill')}],selected:currRow.cells[2].innerHTML.trim(),className:' width90 input in-text',
			     				ownevents:{
				 								onchange:
							 					function(){
							 								var byp = this.parentNode.parentNode.cells[8];
						 								if (this.value==1){
							 						byp.getElementsByTagName("input")[0].disabled = false;
							 					} else {
							 						byp.getElementsByTagName("input")[0].checked = false;
							 						byp.getElementsByTagName("input")[0].disabled = true;
							 						$('#by_payment').text('基础金');
								 										}
							 								}
													}
     	      	    			},
      	    {className:'textRight width90 input in-text'},
      	    {className:'textRight width90 input in-text'},
      	    {className:'textRight width90 input in-text'},
        	  {tag:'input',defalutV:currRow.cells[6].innerHTML.trim(),readonly:true,className:'marginTop9 wdate width110 in-text',ownevents:{onfocus:function(){WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'});}}},
 			 				{tag:'input',defalutV:currRow.cells[7].innerHTML.trim(),readonly:true,className:'marginTop9 wdate width110 in-text',ownevents:{onfocus:function(){WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'});}}},
      	    					{},{}
       		        		];
        		editRow(currRow,columns);

        		currRow.cells[8].getElementsByTagName("input")[0].disabled = false;

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
       			             strategy_name :currRow.cells[1].getElementsByTagName('input')[0].value,
       			             gift_type :currRow.cells[2].getElementsByTagName('select')[0].value,
       			             basic_amount :currRow.cells[3].getElementsByTagName('input')[0].value,
       			             gift_amount :currRow.cells[4].getElementsByTagName('input')[0].value,
       			             gift_point :currRow.cells[5].getElementsByTagName('input')[0].value,
          			          start_time :currRow.cells[6].getElementsByTagName('input')[0].value,
            		         end_time :currRow.cells[7].getElementsByTagName('input')[0].value,
            		         by_first_payment : currRow.cells[8].getElementsByTagName('input')[0].checked==true?'true':'false',
       			             id :currRow.cells[0].innerHTML.trim()
       			                			};

	            if (validate_strategys(params))return;

       			   jQuery.post('<?php echo $this->webroot?>recommends/update',params,function(data){
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
							var amount_reg = /^[0-9]+(\.[0-9]{1,3})?$/;
        		if (!data.strategy_name){has_error = true;error_msg.push(getMessage('enterstrname'));}
        		
        		if (data.basic_amount){
            if (!amount_reg.test(data.basic_amount)){
            		has_error = true;error_msg.push(getMessage('basicamountincorrect'));}
            				}
        		if (data.gift_amount){
        			if (!amount_reg.test(data.gift_amount)){
            		has_error = true;error_msg.push(getMessage('giftamountincorrect'));}
            				}
        		if (data.gift_point){
        			if (!/^\d+$/.test(data.gift_point)){
            		has_error = true;error_msg.push(getMessage('gfitpointfomart'));}
            				}

			        		//循环打出提示信息
							if (has_error == true)
								for(var i = 0;i<error_msg.length;i++)
									jGrowl_to_notyfy(error_msg[i],{theme:'jmsg-alert'});
							
							return has_error;
        				}

    				function getMessage(key){
        		var msg = {
        			enterstrname:"<?php echo __('enterstrname')?>",
        			gfitpointfomart:"<?php echo __('gfitpointfomart')?>",
        			giftamountincorrect:"<?php echo __('giftamountincorrect')?>",
        			basicamountincorrect:"<?php echo __('basicamountincorrect')?>",
        			recommending:"<?php echo __('recommending')?>",
        			afterrefill:"<?php echo __('afterrefill')?>"
                			};
    					return msg[key];
    					}
    	</script>
<div id="cover"></div>
<div id="title">
  <h1><?php echo __('systemc')?>&gt;&gt;
    	<?php echo __('recommendstrategy')?>
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
    			<a class="link_back" href="<?php echo $this->webroot?>recommends/reco_list">
    				<img width="16" height="16"  alt="" src="<?php echo $this->webroot?>images/icon_back_white.png">
    				&nbsp;<?php echo __('gobackall')?>
    			</a>
    		</li>
        <?php }?>
    <li><a class="link_btn" rel="popup" href="javascript:void(0)" onclick="add();"><img width="16" height="16" alt="" src="<?php echo $this->webroot?>images/add.png"> <?php echo __('createnew')?></a></li>
  </ul>
</div>

<div id="container">
<div id="toppage"></div>

<table class="list">
	<col style="width: 4%;">
	<col style="width: 8%;">
	<col style="width: 6%;">
	<col style="width: 6%;">
	<col style="width: 6%;">
	<col style="width: 6%;">
	<col style="width: 13%;">
	<col style="width: 13%;">
	<col style="width: 4%;">
	<col style="width: 10%;">
	<thead>
		<tr>
			<td><a href="javascript:void(0)" onclick="my_sort('recommend_strategy_id','asc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<?php echo __('id',true);?><a href="javascript:void(0)" onclick="my_sort('recommend_strategy_id','desc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
    <td><a href="javascript:void(0)" onclick="my_sort('strategy_name','asc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<?php echo __('strategy_name')?>&nbsp;<a href="javascript:void(0)" onclick="my_sort('strategy_name','desc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
    <td><a href="javascript:void(0)" onclick="my_sort('gift_type','asc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<?php echo __('gift_type')?>&nbsp;<a href="javascript:void(0)" onclick="my_sort('gift_type','desc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
    <td id="by_payment"><a href="javascript:void(0)" onclick="my_sort('basic_amount','asc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<?php echo __('basic_amount')?>&nbsp;<a href="javascript:void(0)" onclick="my_sort('basic_amount','desc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
    <td><a href="javascript:void(0)" onclick="my_sort('gift_amount','asc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<?php echo __('gift_amount')?>&nbsp;<a href="javascript:void(0)" onclick="my_sort('gift_amount','desc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
    <td><a href="javascript:void(0)" onclick="my_sort('gift_point','asc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<?php echo __('gift_point')?>&nbsp;<a href="javascript:void(0)" onclick="my_sort('gift_point','desc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
    <td><a href="javascript:void(0)" onclick="my_sort('start_time','asc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<?php echo __('start_time',true);?>&nbsp;<a href="javascript:void(0)" onclick="my_sort('start_time','desc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
    <td><a href="javascript:void(0)" onclick="my_sort('end_time','asc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<?php echo __('end_time',true);?>&nbsp;<a href="javascript:void(0)" onclick="my_sort('end_time','desc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
    <td><?php echo __('firstprofit')?></td>
    <td class="last"><?php echo __('action')?></td>
		</tr>
	</thead>
	<tbody id="rec_strategy">
		<?php 
			$mydata =$p->getDataArray();
			$loop = count($mydata); 
			for ($i=0;$i<$loop;$i++) {?>
				<tr class="row-1">
				<td class="in-decimal" style="text-align:center"><?php echo $mydata[$i][0]['recommend_strategy_id']?></td>
		    <td style="font-weight: bold;"><?php echo $mydata[$i][0]['strategy_name']?></td>
		    <td><?php echo $mydata[$i][0]['gift_type']==0?__('recommending',true):__('afterrefill',true)?></td>
		    <td style="color:green"><?php echo $mydata[$i][0]['basic_amount']?><?php if ($mydata[$i][0]['by_first_payment']==true)echo "<span style='color:red;'>&nbsp;&nbsp;%</span>";?></td>
		    <td style="color:green"><?php echo $mydata[$i][0]['gift_amount']?></td>
		    <td style="color:green"><?php echo $mydata[$i][0]['gift_point']?></td>
		    <td><?php echo $mydata[$i][0]['start_time']?></td>
		    <td><?php echo $mydata[$i][0]['end_time']?></td>
		    <td>
		    		<?php if ($mydata[$i][0]['by_first_payment'] == true) {?>
		    				<input type="checkbox" checked disabled onclick=" if (this.checked)$('#by_payment').text('%');else $('#by_payment').text('基础金');"/>
		    		<?php } else {?>
		    				<input type="checkbox" disabled onclick=" if (this.checked)$('#by_payment').text('%');else $('#by_payment').text('基础金');"/>
		    		<?php }?>
		    </td>
		    <td>
		    		<a title="<?php echo __('edit')?>" style="float:left;margin-left:35px;" href="javascript:void(0)" onclick="edit_code(this.parentNode.parentNode)">
		    			<i class="icon-edit"></i>
		    		</a>
		    		<a title="<?php echo __('del')?>" style="float:left;margin-left:20px;" href="javascript:void(0)" onclick="delConfirm(this,'<?php echo $this->webroot?>recommends/del_rec/<?php echo $mydata[$i][0]['recommend_strategy_id']?>');">
		    			<i class="icon-remove"></i>
		    		</a>
		    </td>
		    <td style="display:none"><?php echo $mydata[$i][0]['gift_type'];?></td>
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