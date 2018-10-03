
    <style type="text/css">
				.width90{width:80px;}
			</style>
    	<script language="JavaScript" type="text/javascript">
    				function add(){
        			var columns = [
        			 {hidden:true},
        			 {className:'width90 input in-text'},
        			 {className:'width90 input in-text'},
        			 {innerHTML:"<a style='float:left;margin-left:45%;' href='javascript:void(0)' onclick='save_code(this.parentNode.parentNode);'><img src='<?php echo $this->webroot?>images/menuIcon_004.gif' /></a><a  class='marginTop9' style='float:left;margin-left:20px;' href='javascript:void(0)' onclick='this.parentNode.parentNode.parentNode.removeChild(this.parentNode.parentNode)'><i class='icon-remove'></i></a>"},
       		        			];

        			createRow("rec_strategy",columns);
        				}

    				function save_code(tr){
        		var params = {
        				limit :tr.cells[1].getElementsByTagName('input')[0].value,
        				reaplace :tr.cells[2].getElementsByTagName('input')[0].value
                			};

    					jQuery.post('<?php echo $this->webroot?>msglimits/add_limit',params,function(data){
    						var p = {theme:'jmsg-success',beforeClose:function(){location.reload();},life:100};
 					       var  tmp = data.split("|");
 					       if (tmp[1].trim() == 'false') p = {theme:'jmsg-alert',life:500};
 					       	jGrowl_to_notyfy(tmp[0],p);
        					});
        				}

    				function edit_code(currRow){
    					var columns = [
  				        			 {},
  				        			 {className:'width90 input in-text'},
  				        			 {className:'width90 input in-text'},
  				        			 {}
  				       		        			];

        		editRow(currRow,columns);

        		var btn = currRow.cells[3].getElementsByTagName("a")[0];
        		if (btn){
        			var cancel = currRow.cells[3].getElementsByTagName("a")[1].cloneNode(true);
      	             cancel.title = "取消";
      	             cancel.getElementsByTagName("img")[0].src="<?php echo $this->webroot?>images/rerating_queue.png";
      	             cancel.onclick = function(){location.reload();}
      	          			currRow.cells[3].appendChild(cancel);

      	          			
        			btn.getElementsByTagName("img")[0].src="<?php echo $this->webroot?>images/menuIcon_004.gif";
        			btn.onclick = function(){
        				var params = {
                				limit :currRow.cells[1].getElementsByTagName('input')[0].value,
                				reaplace :currRow.cells[2].getElementsByTagName('input')[0].value,
                				id :currRow.cells[0].innerHTML.trim()
                        			};

       			   jQuery.post('<?php echo $this->webroot?>msglimits/update_limit',params,function(data){
       			    	var p = {theme:'jmsg-success',beforeClose:function(){location=location.toString()+"?edit_id="+params.id;},life:100};
       			 			var  tmp = data.split("|");
       			 			if (tmp[1].trim() == 'false') p = {theme:'jmsg-alert',life:500};
       			 				jGrowl_to_notyfy(tmp[0],p);
       			        			});
                				}	
        					}
        				}
    	</script>
<div id="cover"></div>
<div id="title">
  <h1><?php echo __('systemc')?>&gt;&gt;
    	<?php echo __('msglimitrule')?>
  </h1>
    
  <ul id="title-menu">
  <?php if (isset($edit_return)) {?>
        <li>
    			<a class="link_back" href="<?php echo $this->webroot?>msglimits/limit_list">
    				<img width="16" height="16"  alt="" src="<?php echo $this->webroot?>images/icon_back_white.png">
    				&nbsp;<?php echo __('gobackall')?>
    			</a>
    		</li>
        <?php }?>
    <li><a class="link_back" rel="popup" href="javascript:void(0)" onclick="add();"><img width="16" height="16" alt="" src="<?php echo $this->webroot?>images/add.png"> <?php echo __('createnew')?></a></li>
  </ul>
</div>

<div id="container">
<div id="toppage"></div>

<table class="list">
	<col style="width: 25%;">
	<col style="width: 25%;">
	<col style="width: 25%;">
	<col style="width: 25%;">
	<thead>
		<tr>
			<td><?php echo __('id',true);?></td>
			<td><?php echo __('limitchar')?></td>
			<td><?php echo __('replacechar')?></td>
    <td class="last"><?php echo __('action')?></td>
		</tr>
	</thead>
	<tbody id="rec_strategy">
		<?php 
			$mydata =$limits;
			$loop = count($mydata); 
			for ($i=0;$i<$loop;$i++) {?>
				<tr class="row-1">
				<td class="in-decimal" style="text-align:center"><?php echo $mydata[$i][0]['msg_limit_id']?></td>
		    <td style="font-weight: bold;"><?php echo $mydata[$i][0]['limit_char']?></td>
		    <td><?php echo $mydata[$i][0]['replace_char']?></td>
		    <td>
		    		<a title="<?php echo __('edit')?>" style="float:left;margin-left:45%;" href="javascript:void(0)" onclick="edit_code(this.parentNode.parentNode)">
		    			<i class="icon-edit"></i>
		    		</a>
		    		<a title="<?php echo __('del')?>" style="float:left;margin-left:20px;" href="javascript:void(0)" onclick="delConfirm(this,'<?php echo $this->webroot?>msglimits/del_limit/<?php echo $mydata[$i][0]['msg_limit_id']?>');">
		    			<i class="icon-remove"></i>
		    		</a>
		    </td>
				</tr>
		<?php }?>		
	</tbody>
	<tbody>
</tbody>
</table>
</div>
<div id="livemargins_control" style="position: absolute; display: none; z-index: 9999;"><img width="77" height="5" style="position: absolute; left: -77px; top: -5px;" src="chrome://livemargins/skin/monitor-background-horizontal.png">	<img style="position: absolute; left: 0pt; top: -5px;" src="chrome://livemargins/skin/monitor-background-vertical.png">	<img style="position: absolute; left: 1px; top: 0pt; opacity: 0.5; cursor: pointer;" onmouseout="this.style.opacity=0.5" onmouseover="this.style.opacity=1" src="chrome://livemargins/skin/monitor-play-button.png" id="monitor-play-button"></div>