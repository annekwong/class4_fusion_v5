
    <style type="text/css">.height20{height:20px;}.width60{width:80px;}.widthfull{width:100%;}</style>
    <script type="text/javascript">
    			function add(){
    				var columns = [
  								 {className:'width60 input in-text height20'},
  								 {className:'width60 input in-text  height20'},
  								 {className:'widthfull input in-text  height20'},
  								 {hidden:true},
  								 {innerHTML:"<a style='float:left;margin-left:35px;' href='javascript:void(0)' onclick='save(this.parentNode.parentNode);'><img src='<?php echo $this->webroot?>images/menuIcon_004.gif' /></a><a  class='marginTop9' style='float:left;margin-left:10px;' href='javascript:void(0)' onclick='this.parentNode.parentNode.parentNode.removeChild(this.parentNode.parentNode)'><i class='icon-remove'></i></a>"},
  							        			];
  						
  								createRow("rec_strategy",columns);
        			}

    			function save(tr){
        	var params = {
           username : tr.cells[0].getElementsByTagName("input")[0].value,
           pw : tr.cells[1].getElementsByTagName("input")[0].value,
           r_url : tr.cells[2].getElementsByTagName("input")[0].value
                		};
        	if (validate(params)){return;};
        	jQuery.post('<?php echo $this->webroot?>msginterfaces/add',params,function(data){
							var p = {theme:'jmsg-success',beforeClose:function(){location.reload();},life:100};
					   var  tmp = data.split("|");
					   if (tmp[1].trim() == 'false') p = {theme:'jmsg-alert',life:500};
					   jGrowl_to_notyfy(tmp[0],p);
						});
        			}


    			function edit(tr){
    				var columns = [
    								  {className:'width60 input in-text height20'},
  								 {className:'width60 input in-text  height20'},
  								 {className:'widthfull input in-text  height20'},
  								 {hidden:true},
    								 {}
    							        			];
    						
    					editRow(tr,columns);

    					var btn = tr.cells[4].getElementsByTagName("a")[1];
    	        		if (btn){
    	        			btn.getElementsByTagName("img")[0].src="<?php echo $this->webroot?>images/menuIcon_004.gif";
    	        			btn.onclick = function(){
    	        				var params = {
 	        				           username : tr.cells[0].getElementsByTagName("input")[0].value,
 	        				           pw : tr.cells[1].getElementsByTagName("input")[0].value,
 	        				           r_url : tr.cells[2].getElementsByTagName("input")[0].value,
 	        				           id : tr.cells[5].innerHTML.trim()
 	        				                		};

		                		if (validate(params)){return;};

    	       			   jQuery.post('<?php echo $this->webroot?>msginterfaces/update',params,function(data){
    	       			    	var p = {theme:'jmsg-success',beforeClose:function(){location.reload();},life:100};
    	       			 			var  tmp = data.split("|");
    	       			 			if (tmp[1].trim() == 'false') p = {theme:'jmsg-alert',life:500};
    	       			 				jGrowl_to_notyfy(tmp[0],p);
    	       			        			});
    	                				}	
    	        					}
    			}
    			
    			function validate(params){
    				var url_reg = /^(http|https):\/\/([\w-]+\.)+[\w-]+(\/[\w- .\/?%&=]*)?$/;
						 var has_error=false;
    	     if (!params.r_url){
                   has_error = true;
                   jGrowl_to_notyfy('请输入接口地址',{theme:'jmsg-alert'});
          } else {
           if (!url_reg.test(params.r_url)){
            has_error = true;
            jGrowl_to_notyfy('请输入有效的http或者https地址',{theme:'jmsg-alert'});
                      	     }
          }
             return has_error;
        			}
    </script>
<div id="cover"></div>
<div id="title">
  <h1><?php echo __('systemc')?>&gt;&gt;
    软件升级
  </h1>
  
  <ul id="title-menu">
    <li>
    		<a class="link_btn" href="javascript:void(0)" onclick="add();">
    			<img width="16" height="16" alt="" src="<?php echo $this->webroot?>images/add.png"> <?php echo __('createnew')?>
    		</a>
    </li>
  </ul>
</div>

<div id="container">
<table class="list">
	<col style="width: 10%;">
	<col style="width: 10%;">
	<col style="width: 65%;">
	<col style="width: 5%;">
	<col style="width: 10%;">
	<thead>
		<tr>
		<td><?php echo __('username')?></td>
		<td><?php echo __('password')?></td>
   <td><?php echo __('address')?></td>
   <td><?php echo __('status')?></td>
   <td><?php echo __('action')?></td>
		</tr>
	</thead>
	<tbody id="rec_strategy">
		<?php 
			$mydata =$msginterfaces;
			$loop = count($mydata); 
			for ($i=0;$i<$loop;$i++) {?>
				<tr class="row-1">
				<td class="in-decimal" style="text-align:center"><?php echo $mydata[$i][0]['username']?></td>
		    <td style="font-weight: bold;"><?php echo $mydata[$i][0]['pw']?></td>
		    <td><?php echo $mydata[$i][0]['url']?></td>
		    <td><?php echo $mydata[$i][0]['status']==true?'正在使用':'未使用'?></td>
		    <td>
		    <?php if ($mydata[$i][0]['status']==false) {?>
		    		<a title="使用"  style="float:left;margin-left:10px;" href="<?php echo $this->webroot?>msginterfaces/active/<?php echo $mydata[$i][0]['msg_interface_id']?>" onclick="return confirm('启用该接口将会禁用其他接口,是否继续?')"><img src="<?php echo $this->webroot?>images/status_closed.gif"/></a>
		    		<?php }?>
		    		<a title="<?php echo __('edit')?>" style="float:left;margin-left:10px;" href="javascript:void(0)" onclick="edit(this.parentNode.parentNode);">
		    			<i class="icon-edit"></i>
		    		</a>
		    		<a title="<?php echo __('del')?>" style="float:left;margin-left:10px;" href="javascript:void(0)" onclick="delConfirm(this,'<?php echo $this->webroot?>msginterfaces/del_interface/<?php echo $mydata[$i][0]['msg_interface_id']?>');">
		    			<i class="icon-remove"></i>
		    		</a>
		    </td>
		    <td style="display:none"><?php echo $mydata[$i][0]['msg_interface_id']?></td>
				</tr>
		<?php }?>		
	</tbody>
	<tbody>
</tbody>
</table>
</div>
<div id="livemargins_control" style="position: absolute; display: none; z-index: 9999;"><img width="77" height="5" style="position: absolute; left: -77px; top: -5px;" src="chrome://livemargins/skin/monitor-background-horizontal.png">	<img style="position: absolute; left: 0pt; top: -5px;" src="chrome://livemargins/skin/monitor-background-vertical.png">	<img style="position: absolute; left: 1px; top: 0pt; opacity: 0.5; cursor: pointer;" onmouseout="this.style.opacity=0.5" onmouseover="this.style.opacity=1" src="chrome://livemargins/skin/monitor-play-button.png" id="monitor-play-button"></div>