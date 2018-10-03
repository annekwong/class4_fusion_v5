
    <script type="text/javascript">
    			function edit(status,name,last_run){
        	document.getElementById("ht-100001").innerHTML = name;
        	document.getElementById("lastrt").innerHTML = last_run;
        	status==1?document.getElementById("active").checked=true:document.getElementById("active").checked = false;
        			}
    			function save(){
        	var params = {
           active : document.getElementById("active").checked==true?'true':'false',
           once_or_every : document.getElementById("min_type").value,
           run_interval : document.getElementById("min_value").value,
           run_type : document.getElementById("way").value,
           id : document.getElementById("id").value
                		};

    				if (!params.run_interval) {
        		jGrowl_to_notyfy('Please fill in the execution time interval',{theme:'jmsg-alert'});
        		return;
        	} else {
           if (!/^\d+$/.test(params.run_interval)){
        	   jGrowl_to_notyfy('Only for integer execution time interval',{theme:'jmsg-alert'});
        	   return;
               				 }
            			}

        	jQuery.post('<?php echo $this->webroot?>schedulers/edit',params,function(data){
           if (data.trim()=='true'){
             jGrowl_to_notyfy('Operation is successful',{theme:'jmsg-success',life:100,beforeClose:function(){location.reload();}});
           } else {
             jGrowl_to_notyfy('Operation failed, please try again later',{theme:'jmsg-alert'});
                    		}
            			});
        			}
    </script>
<div id="title">
  <h1><?php __('System')?>&gt;&gt;
    <?php __('TaskSchedulers')?>
  </h1>
</div>
<div id="cover"></div>
<div id="container">
<?php if (count($schedulers) == 0) {?>
<div class="msg"><?php echo __('no_data_found')?></div>
<?php } else {?>
<dl id="editForm" class="tooltip-styled" style="display:none;position:absolute;left:35%;top:25%;z-idnex:99;width:400px;height:auto;">
<input type="hidden" id="id" value="18" name="id" class="input in-hidden">
<table class="form">
<tbody><tr>
    <td class="label label2"><?php echo __('name')?>:</td>
    <td class="value value2"><span  class="helptip" id="ht-100001"></span><span class="tooltip" id="ht-100001-tooltip"><?php __('XML-RPC Server serves Core API calls to yht')?></span></td>
</tr>
<tr>
    <td class="label label2"><?php echo __('lastrun')?>:</td>
    <td class="value value2" id="lastrt"></td>
</tr>
<tr>
    <td class="label label2">&nbsp;</td>
    <td class="value value2"><input type="checkbox" id="active" name="active" class="input in-checkbox"> <label for="active"><?php echo __('status')?></label></td>
</tr>
</tbody></table>

<fieldset><legend><?php echo __('runat')?></legend>
<table class="form">
<tbody><tr>
    <td class="value value2">
        <select id="min_type" style="margin-left:30px;width:60px;float:left;" name="min_type" class="input in-select">
        	<option value="2"><?php __('every')?></option>
        	<option value="1"><?php __('once')?></option>
        </select>
        <input type="text" id="min_value" style="float:left; width:200px;height:20px;" class="in-decimal input in-text" value="10" name="min_value">
        <select style="width:60px;float:left;" id="way" >
        				<option value="0"><?php __('minutes')?></option>
        				<option value="1"><?php __('hours')?></option>
        				<option value="2"><?php __('days')?></option>
        </select> 
            </td>
</tr>
</tbody></table>
</fieldset>

<div style="text-align:center;">
    <input onclick="closeCover('editForm');" type="button" value="<?php echo __('cancel')?>" class="input in-submit">
    <input type="button" onclick="save();" value="<?php echo __('submit')?>" class="input in-submit">
</div>
</dl>

<table class="list">
	<col style="width: 20%;">
	<col style="width: 20%;">
	<col style="width: 20%;">
	<col style="width: 20%;">
	<col style="width: 20%;">
	<thead>
		<tr>
		<td><?php echo __('status')?></td>
		<td><?php echo __('name')?></td>
		<td><?php echo __('runat')?></td>
		<td><?php echo __('lastrun')?></td>
    <td><?php echo __('action')?></td>
		</tr>
	</thead>
	<tbody id="producttab">
		<?php 
			$mydata =$schedulers;
			$loop = count($mydata); 
			for ($i=0;$i<$loop;$i++) {?>
				<tr class="row-1">
		    <td>
		    	<?php if ($mydata[$i][0]['active'] == true) {?>
		    			<a href="<?php echo $this->webroot?>schedulers/status/<?php echo $mydata[$i][0]['id']?>/false" title="禁用"><img src="<?php echo $this->webroot?>images/flag-1.png" /></a>
		    	<?php } else {?>
		    			<a href="<?php echo $this->webroot?>schedulers/status/<?php echo $mydata[$i][0]['id']?>/true" href="" title="启用"><img src="<?php echo $this->webroot?>images/flag-0.png" /></a>
		    	<?php }?>
		    </td>
		    <td>
		    	<?php echo $mydata[$i][0]['name']?>
		    </td>
		    <td>
		    		<?php
		    			 if ($mydata[$i][0]['run_type'] == 0) { echo "Every {$mydata[$i][0]['run_interval']} minutes";}
		    			 else if ($mydata[$i][0]['run_type'] == 1){ echo "Every {$mydata[$i][0]['run_interval']} hours";}
		    			 else { echo "Every {$mydata[$i][0]['run_interval']} days";}
		    			 if ($mydata[$i][0]['once_or_every'] == 1){echo "(once)";}
		    			 else {echo "(every)";}
		    			?>
		    </td>
		    <td><?php echo $mydata[$i][0]['last_runtime']?></td>
		    <td>
		    		<a title="<?php echo __('edit')?>" style="float:left;margin-left:50%;" href="javascript:void(0)" onclick="cover('editForm');edit('<?php echo $mydata[$i][0]['active']?>','<?php echo $mydata[$i][0]['name']?>','<?php echo $mydata[$i][0]['last_runtime']?>');$('#min_type').val(<?php echo $mydata[$i][0]['once_or_every']?>);$('#min_value').val(<?php echo $mydata[$i][0]['run_interval']?>);$('#way').val(<?php echo $mydata[$i][0]['run_type']?>);$('#id').val(<?php echo $mydata[$i][0]['id']?>)">
		    			<i class="icon-edit"></i>
		    		</a>
		    </td>
				</tr>
		<?php }?>		
	</tbody>
	<tbody>
</tbody>
</table>
<?php }?>
</div>
<div id="livemargins_control" style="position: absolute; display: none; z-index: 9999;"><img width="77" height="5" style="position: absolute; left: -77px; top: -5px;" src="chrome://livemargins/skin/monitor-background-horizontal.png">	<img style="position: absolute; left: 0pt; top: -5px;" src="chrome://livemargins/skin/monitor-background-vertical.png">	<img style="position: absolute; left: 1px; top: 0pt; opacity: 0.5; cursor: pointer;" onmouseout="this.style.opacity=0.5" onmouseover="this.style.opacity=1" src="chrome://livemargins/skin/monitor-play-button.png" id="monitor-play-button"></div>