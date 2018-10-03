
    <style type="text/css">.textRight{text-align:right;}.width50{width:50px;}</style>
    <script type="text/javascript">
    		function add(){
    			var columns = [
    	        			   {hidden:true},
    	      	{className:'input in-text width90'},
    	       {tag:'select',className:'in-select',options:[{k:'1',v:getMessage('msgall')},{k:'2',v:getMessage('getpass')},{k:'3',v:getMessage('querybalance')},{k:'4',v:getMessage('msgregister')},{k:'5',v:getMessage('ordinarym')}]},
    	       {className:'input in-text width90 textRight'},
    	       {className:'input in-text textRight width50',defaultV:'1'},
    	       {tag:'a',innerHTML:"<a style='float:left;margin-left:50px;' href='javascript:void(0)' onclick='save(this.parentNode.parentNode);'><img src='<?php echo $this->webroot?>images/menuIcon_004.gif' /></a><a style='float:left;margin-left:20px;' href='javascript:void(0)' onclick='this.parentNode.parentNode.parentNode.removeChild(this.parentNode.parentNode)'><i class='icon-remove'></i></a>"}
    	   		        	];
    	      createRow('msgchargetab',columns);
        		}

    		function save(tr){
        		var params = {
            charge_name :tr.cells[1].getElementsByTagName('input')[0].value,
            msg_type :tr.cells[2].getElementsByTagName('select')[0].value,
            msg_rate :tr.cells[3].getElementsByTagName('input')[0].value,
            free_count :tr.cells[4].getElementsByTagName('input')[0].value
                			};

        		if (validate_charge(params))return;

        		jQuery.post('<?php echo $this->webroot?>msgcharges/add_charge',params,function(data){
            		var p = {theme:'jmsg-success',beforeClose:function(){location.reload();},life:100};
            		var  tmp = data.split("|");
            		if (tmp[1].trim() == 'false') p = {theme:'jmsg-alert',life:100};
            		jGrowl_to_notyfy(tmp[0],p);
            						});
        		}

    		function edit(currRow){
    			var columns = [
          		 				  {},
    	      	{className:'input in-text width90'},
    	       {tag:'select',className:'in-select',selected:currRow.cells[2].innerHTML.trim(),options:[{k:'1',v:getMessage('msgall')},{k:'2',v:getMessage('getpass')},{k:'3',v:getMessage('querybalance')},{k:'4',v:getMessage('msgregister')},{k:'5',v:getMessage('ordinarym')}]},
    	       {className:'input in-text width90'},
    	       {className:'input in-text width90 width50'},
        	    					{}
         		        		];
          		editRow(currRow,columns);

          		var btn = currRow.cells[5].getElementsByTagName("a")[0];
          		if (btn){
             var cancel = currRow.cells[5].getElementsByTagName("a")[1].cloneNode(true);
             cancel.title = "取消";
             cancel.getElementsByTagName("img")[0].src="<?php echo $this->webroot?>images/rerating_queue.png";
             cancel.onclick = function(){location.reload();}
          			currRow.cells[5].appendChild(cancel);
          			
          			btn.getElementsByTagName("img")[0].src="<?php echo $this->webroot?>images/menuIcon_004.gif";
          			btn.onclick=function(){
          				var params = {
              		            charge_name :currRow.cells[1].getElementsByTagName('input')[0].value,
              		            msg_type :currRow.cells[2].getElementsByTagName('select')[0].value,
              		            msg_rate :currRow.cells[3].getElementsByTagName('input')[0].value,
              		            free_count :currRow.cells[4].getElementsByTagName('input')[0].value,
              		            id :currRow.cells[0].innerHTML.trim()
              		                			};

              			if (validate_charge(params))return;

              			jQuery.post('<?php echo $this->webroot?>msgcharges/edit_charge',params,function(data){
                  			if(location.toString().indexOf("edit_id")){
                  				var p = {theme:'jmsg-success',beforeClose:function(){location=location.toString()},life:100};
                      			}else{
                      				var p = {theme:'jmsg-success',beforeClose:function(){location=location.toString()+"?edit_id="+params.id},life:100};
                          			}
                    
                    		var  tmp = data.split("|");
                    		if (tmp[1].trim() == 'false') p = {theme:'jmsg-alert',life:100};
                    		jGrowl_to_notyfy(tmp[0],p);
                    						});
              						};
          					}
        		}

    		function validate_charge(data){
    			var has_error = false;
				var error_msg = [];
				var amount_reg = /^[0-9]+(\.[0-9]{1,3})?$/;
						if (!data.charge_name){has_error = true;error_msg.push(getMessage('enternamep'));}

						if (!data.msg_rate){has_error=true;error_msg.push(getMessage('msgratenull'))}
						else if (!amount_reg.test(data.msg_rate)){has_error=true;error_msg.push(getMessage('msgrateformat'))}

						if (!data.free_count){has_error=true;error_msg.push(getMessage('enterfreecount'))}
						else if (!/^\d+$/.test(data.free_count)){has_error=true;error_msg.push(getMessage('free_countformat'))}
					        		//循环打出提示信息
									if (has_error == true)
										for(var i = 0;i<error_msg.length;i++)
											jGrowl_to_notyfy(error_msg[i],{theme:'jmsg-alert'});
									
									return has_error;
        		}

        		function getMessage(key){
            		var msg = {
            				msgall:"<?php echo __('msgall')?>",
            				getpass:"<?php echo __('getpass')?>",
            				enternamep:"<?php echo __('enternamep')?>",
            				msgrateformat:"<?php echo __('msgrateformat')?>",
            				msgratenull:"<?php echo __('msgratenull')?>",
            				enterfreecount:"<?php echo __('enterfreecount')?>",
            				free_countformat:"<?php echo __('free_countformat')?>",
            				querybalance : "<?php echo __('querybalance')?>",
            				msgregister:"<?php echo __('msgregister')?>",
            				ordinarym :"<?php echo __('ordinarym')?>"
                    		};
            		return msg[key];
            		}
    </script>
<div id="cover"></div> 
<div id="title">
  <h1><?php echo __('systemc')?>&gt;&gt;
    <?php echo __('msgrate')?>      
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
    			<a class="link_back" href="<?php echo $this->webroot?>msgcharges/msg_charge_list">
    				<img width="16" height="16"  alt="" src="<?php echo $this->webroot?>images/icon_back_white.png">
    				&nbsp;<?php echo __('gobackall')?>
    			</a>
    		</li>
        <?php }?>
    <li>
    		<a class="link_btn" href="javascript:void(0)" onclick="add();">
    			<img width="16" height="16" alt="" src="<?php echo $this->webroot?>images/add.png"> <?php echo __('createnew')?>
    		</a>
    </li>
  </ul>
</div>

<div id="container">
<div id="toppage"></div>
	
<table class="list">
	<col style="width: 4%;">
	<col style="width: 15%;">
	<col style="width: 15%;">
	<col style="width: 15%;">
	<col style="width: 15%;">
	<col style="width: 12%;">
	<thead>
		<tr>
    <td>
    		<a href="javascript:void(0)" onclick="my_sort('msg_charges_id','asc')">
    				<img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png">
    		</a><?php echo __('id',true);?>
    		<a href="javascript:void(0)" onclick="my_sort('msg_charges_id','desc')">
    			<img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png">
    		</a>
    	</td>
    	
    	<td>
    		<a href="javascript:void(0)" onclick="my_sort('charge_name','asc')">
    				<img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png">
    		</a>&nbsp;<?php echo __('charge_name')?>&nbsp;
    		<a href="javascript:void(0)" onclick="my_sort('charge_name','desc')">
    			<img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png">
    		</a>
    	</td>
    	
    	
    	<td>
    		<a href="javascript:void(0)" onclick="my_sort('msg_type','asc')">
    				<img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png">
    		</a>&nbsp;<?php echo __('type')?>&nbsp;
    		<a href="javascript:void(0)" onclick="my_sort('msg_type','desc')">
    			<img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png">
    		</a>
    	</td>
    	
    	
    		<td>
    		<a href="javascript:void(0)" onclick="my_sort('msg_rate','asc')">
    				<img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png">
    		</a>&nbsp;<?php echo __('msg_rate')?>&nbsp;
    		<a href="javascript:void(0)" onclick="my_sort('msg_rate','desc')">
    			<img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png">
    		</a>
    	</td>
    	
    	<td>
    		<a href="javascript:void(0)" onclick="my_sort('free_count','asc')">
    				<img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png">
    		</a>&nbsp;<?php echo __('free_count')?>&nbsp;
    		<a href="javascript:void(0)" onclick="my_sort('free_count','desc')">
    			<img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png">
    		</a>
    	</td>
    	
    <td class="last"><?php echo __('action')?></td>
		</tr>
	</thead>
	<tbody id="msgchargetab">
		<?php 
			$mydata =$p->getDataArray();
			$loop = count($mydata); 
			for ($i=0;$i<$loop;$i++) {?>
				<tr class="row-1">
		    <td class="in-decimal"><?php echo $mydata[$i][0]['msg_charges_id']?></td>
		    <td style="font-weight: bold;">
		    		<?php echo $mydata[$i][0]['charge_name']?>
		    </td>
		    
		    <td style="font-weight: bold;">
		    		<?php
		    			if ($mydata[$i][0]['msg_type'] == 1) echo __('msgall');
		    			else if ($mydata[$i][0]['msg_type'] == 2) echo __('getpass');
		    			else if ($mydata[$i][0]['msg_type'] == 3) echo __('querybalance');
		    			else if ($mydata[$i][0]['msg_type'] == 4) echo __('msgregister');
		    			else if ($mydata[$i][0]['msg_type'] == 5) echo __('ordinarym');
		    		?>
		    </td>
		    
		    <td style="color:green"><?php echo $mydata[$i][0]['msg_rate']?></td>
		    <td style="color:red"><?php echo $mydata[$i][0]['free_count']?></td>
		    <td >
		    		<a  title="<?php echo __('edit')?>"style="float:left;margin-left:50px;" href="javascript:void(0)" onclick="edit(this.parentNode.parentNode);">
		    			<i class="icon-edit"></i>
		    		</a>
		    		<a title="<?php echo __('del')?>" style="float:left;margin-left:20px;" href="javascript:void(0)" onclick="delConfirm(this,'<?php echo $this->webroot?>msgcharges/del_charge/<?php echo $mydata[$i][0]['msg_charges_id']?>');">
		    			<i class="icon-remove"></i>
		    		</a>
		    </td>
				</tr>
		<?php }?>
	</tbody>
	<tbody>
</tbody>
</table>
<?php $d = $p->getDataArray();if (count($d) == 0) {?>
<div class="msg"><?php echo __('no_data_found')?></div>
<?php }?>
	<div id="tmppage">
<?php echo $this->element('page');?>
</div>
</div>
<div>
</div>
<div id="livemargins_control" style="position: absolute; display: none; z-index: 9999;"><img width="77" height="5" style="position: absolute; left: -77px; top: -5px;" src="chrome://livemargins/skin/monitor-background-horizontal.png">	<img style="position: absolute; left: 0pt; top: -5px;" src="chrome://livemargins/skin/monitor-background-vertical.png">	<img style="position: absolute; left: 1px; top: 0pt; opacity: 0.5; cursor: pointer;" onmouseout="this.style.opacity=0.5" onmouseover="this.style.opacity=1" src="chrome://livemargins/skin/monitor-play-button.png" id="monitor-play-button"></div>
