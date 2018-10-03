
<div id="cover"></div>
  <div id="title"><h1>
<?php echo __('systemc')?>&gt;&gt;
    	<?php echo __('servicesrequest')?>
  </h1>
  <ul id="title-menu">
  <?php if (isset($extralSearch)) {?>
    		<li>
    			<a class="link_back" href="<?php echo $backurl?>">
    				<img width="16" height="16"  alt="" src="<?php echo $this->webroot?>images/icon_back_white.png">
    				&nbsp;<?php echo __('goback')?>
    			</a>
    		</li>
    		
    		<li>
    		<?php if (isset($res)) {?>
    			<a class="link_back" href="javascript:void(0)" onclick="deleteSelected('servicetab','<?php echo $this->webroot?>servicerequests/agree_request/selected<?php echo isset($extralSearch)? "/$extralSearch":'';?>/null/<?php echo $res?>')">
    				<img width="16" height="16"  alt="" src="<?php echo $this->webroot?>images/status_closed.gif">
    				&nbsp;<?php echo __('approveselected')?>
    			</a>
    		<?php } else {?>
    			<a class="link_back" href="javascript:void(0)" onclick="deleteSelected('servicetab','<?php echo $this->webroot?>servicerequests/agree_request/selected<?php echo isset($extralSearch)? "/$extralSearch":'';?><?php echo isset($series)?"/$series":""?>')">
    				<img width="16" height="16"  alt="" src="<?php echo $this->webroot?>images/status_closed.gif">
    				&nbsp;<?php echo __('approveselected')?>
    			</a>
    		</li>
  <?php }?>
 <?php }?>
    		</ul>
  </div>
<div id="container">
<?php $d = $p->getDataArray();if (count($d) == 0) {?>
<div class="msg"><?php echo __('no_data_found')?></div>
<?php } else {?>
<div id="toppage"></div>
<table class="list">
	<col style="width: 5%;">
	<col style="width: 20%;">
	<col style="width: 25%;">
	<col style="width: 25%;">
	<col style="width: 25%;">
	<thead>
		<tr>
			<td><input type="checkbox" onclick="checkAllOrNot(this,'servicetab');" value=""/></td>
    <td><a href="javascript:void(0)" onclick="my_sort('card','asc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<?php echo __('card_number')?>&nbsp;<a href="javascript:void(0)" onclick="my_sort('card','desc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
    <td><a href="javascript:void(0)" onclick="my_sort('service','asc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<?php echo __('service_name')?>&nbsp;<a href="javascript:void(0)" onclick="my_sort('service','desc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
    <td><a href="javascript:void(0)" onclick="my_sort('request_time','asc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<?php echo __('request_time')?>&nbsp;<a href="javascript:void(0)" onclick="my_sort('request_time','desc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
    <td class="last"><?php echo __('action')?></td>
		</tr>
	</thead>
	<tbody id="servicetab">
		<?php 
			$mydata =$p->getDataArray();
			$loop = count($mydata); 
			for ($i=0;$i<$loop;$i++) {?>
				<tr class="row-1">
					<td style="text-align:center"><input type="checkbox" value="<?php echo $mydata[$i][0]['id']?>"/></td>
		    <td><?php echo $mydata[$i][0]['card']?></td>
		    <td style="font-weight: bold;"><?php echo $mydata[$i][0]['service']?></td>
		    <td style="color:green"><?php echo $mydata[$i][0]['request_time']?></td>
		    <td >
		    <?php if (isset($res)) {?>
		    		<a style="text-align:center;" title="<?php echo __('distributepermission')?>" href="<?php echo $this->webroot?>servicerequests/agree_request/<?php echo $mydata[$i][0]['id']?><?php echo isset($extralSearch)? "/$extralSearch":'';?>/null/<?php echo $res?>">
		    				<img src="<?php echo $this->webroot?>images/status_closed.gif"/>
		    		</a>
		    <?php } else {?>
		    		<a style="text-align:center;" title="<?php echo __('distributepermission')?>" href="<?php echo $this->webroot?>servicerequests/agree_request/<?php echo $mydata[$i][0]['id']?><?php echo isset($extralSearch)? "/$extralSearch":'';?><?php echo isset($series)?"/$series":""?>">
		    				<img src="<?php echo $this->webroot?>images/status_closed.gif"/>
		    		</a>
		    	<?php }?>
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
<?php }?>
</div>
<div id="livemargins_control" style="position: absolute; display: none; z-index: 9999;"><img width="77" height="5" style="position: absolute; left: -77px; top: -5px;" src="chrome://livemargins/skin/monitor-background-horizontal.png">	<img style="position: absolute; left: 0pt; top: -5px;" src="chrome://livemargins/skin/monitor-background-vertical.png">	<img style="position: absolute; left: 1px; top: 0pt; opacity: 0.5; cursor: pointer;" onmouseout="this.style.opacity=0.5" onmouseover="this.style.opacity=1" src="chrome://livemargins/skin/monitor-play-button.png" id="monitor-play-button"></div>