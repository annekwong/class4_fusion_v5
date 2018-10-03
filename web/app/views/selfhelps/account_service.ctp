
    <script type="text/javascript">
    			function add_family_num(){
        	var pname = document.getElementById("pname").value;
        	var sid = document.getElementById("c_service_id").value;
        	jQuery.post('<?php echo $this->webroot?>selfhelps/family_num',{sid:sid,num:pname},function(data){
        		 var p = {theme:'jmsg-success',beforeClose:function(){location.reload();},life:100};
  		       var  tmp = data.split("|");
  		       if (tmp[1].trim() == 'false') p = {theme:'jmsg-alert',life:100};
  		       jGrowl_to_notyfy(tmp[0],p);
            			});
        			}
    </script>
<div id="title">
  <h1><?php echo __('account')?>&gt;&gt;
    <?php echo __('serviceinfo')?>
  </h1>
</div>
<div id="cover"></div>
<div id="container">

<dl id="addfamilynum" class="tooltip-styled" style="display:none;position:absolute;left:40%;top:30%;z-idnex:99;width:300px;height:100px;">
<div style="text-align:center;width:100%;height:25px;font-size: 16px;"><?php echo __('addfamilynum')?></div>
	<div style="margin-top:10px;"><?php echo __('familynum')?>:<input class="input in-text" id="pname"/>
	</div>
	<input id="c_service_id" style="display:none"/>
	<div style="margin-top:10px; margin-left:25%;width:150px;height:auto;">
		<input type="button" onclick="add_family_num();" value="<?php echo __('submit')?>" class="input in-button">
		<input type="button" onclick="closeCover('addfamilynum');" value="<?php echo __('cancel')?>" class="input in-button">
	</div>
</dl>


		<div style="margin-left:20px;float:left;width:300px;font-size: 1.1em; line-height: 1.3;" class="msg">
			<div style="width:auto;display:inline;text-align:center;padding-top:8px;" class="title"><?php echo __('usingservice')?></div>
			<table class="list">
			<thead>
				<tr>
				<td><?php echo __('service_name')?></td>
				<td><?php echo __('billing_type')?></td>
				<td><?php echo __('fy')?></td>
				<td><?php echo __('action')?></td>
			</tr>
			</thead>
			<tbody>
				<?php
					$loop = count($yes);
					for ($i = 0;$i<$loop;$i++) { 
				?>
				
				 <tr>
				    <td><?php echo $yes[$i][0]['service_name']?></td>
				    <td><?php echo __('money')?>/<?php 
				    									if ($yes[$i][0]['billing_type']==1)echo __('days');
															else if ($yes[$i][0]['billing_type']==2) echo __('week'); 
															else echo __('months');?>
						 </td>
						 <td style="color:green"><?php echo $yes[$i][0]['cost']?></td>
				    <td class="value value2">
				    				<a title="<?php echo __('cancelservice')?>" onclick="return confirm('<?php echo __('cancelserviceconfirm')?>?')" 
				    						href="?service=2&s_id=<?php echo $yes[$i][0]['service_id']?>">
				    							<img src="<?php echo $this->webroot?>images/no.png"/>
				    				</a>
				    </td>
					</tr>
				<?php
					} 
				?>
			
			</tbody></table>
		</div>




	<div style="width:300px;height:auto;font-size: 1.1em; line-height: 1.3;" class="msg">
		<div style="display:inline;text-align:center;padding-top:8px;" class="title"><?php echo __('cancommanded')?></div>
		<table class="list">
		<thead>
			<tr>
				<td><?php echo __('service_name')?></td>
				<td><?php echo __('billing_type')?></td>
				<td><?php echo __('fy')?></td>
				<td><?php echo __('action')?></td>
			</tr>
		</thead>
		<tbody>
			<?php
				$loop = count($no);
				for ($i = 0;$i<$loop;$i++) { 
			?>
				<tr>
				    <td><?php echo $no[$i][0]['service_name']?></td>
				    <td><?php echo __('money')?>/<?php 
				    									if ($no[$i][0]['billing_type']==1)echo __('days');
															else if ($no[$i][0]['billing_type']==2) echo __('week'); 
															else echo __('months');?>
						 </td>
						 <td style="color:green"><?php echo $no[$i][0]['cost']?></td>
				    <td class="value value2">
				    			<?php if ($no[$i][0]['type'] != 6) {?>
				    		<a title="<?php echo __('command')?>" href="?service=1&s_id=<?php echo $no[$i][0]['service_id']?>" onclick="return confirm('<?php echo __('surecommand')?>?')"><img src="<?php echo $this->webroot?>images/status_closed.gif"/></a>
				    		<?php } else {?>
				    		<a title="<?php echo __('command')?>" href="javascript:void(0)" onclick="$('#c_service_id').val(<?php echo $no[$i][0]['service_id']?>);cover('addfamilynum');"><img src="<?php echo $this->webroot?>images/status_closed.gif"/></a>
				    		<?php }?>
				    	</td>
				</tr>
			<?php
				} 
			?>
		
		</tbody></table>
		</div>
  

</div>

<div id="livemargins_control" style="position: absolute; display: none; z-index: 9999;"><img width="77" height="5" style="position: absolute; left: -77px; top: -5px;" src="chrome://livemargins/skin/monitor-background-horizontal.png">	<img style="position: absolute; left: 0pt; top: -5px;" src="chrome://livemargins/skin/monitor-background-vertical.png">	<img style="position: absolute; left: 1px; top: 0pt; opacity: 0.5; cursor: pointer;" onmouseout="this.style.opacity=0.5" onmouseover="this.style.opacity=1" src="chrome://livemargins/skin/monitor-play-button.png" id="monitor-play-button"></div>