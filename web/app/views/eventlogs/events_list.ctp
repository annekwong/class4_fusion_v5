<div id="title">
  <h1><?php __('Configuration')?>&gt;&gt;
    	<?php echo __('events')?>
  </h1>
    
  <ul id="title-menu">
                  <li><a class="link_btn" href="<?php echo $this->webroot?>eventlogs/events_list"><img width="16" height="16" alt="" src="<?php echo $this->webroot?>images/list.png"><?php echo __('allevents')?></a></li>
                 <li><a class="link_btn" href="<?php echo $this->webroot?>eventlogs/events_list?type=3"><img width="16" height="16" alt="" src="<?php echo $this->webroot?>images/iconError.gif"> <?php echo __('error')?></a></li>
                  <li><a  class="link_btn"href="<?php echo $this->webroot?>eventlogs/events_list?type=1"><img width="16" height="16" alt="" src="<?php echo $this->webroot?>images/alert.png"><?php echo __('alert')?></a></li>
                  <li><a class="link_btn" href="<?php echo $this->webroot?>eventlogs/events_list?type=2"><img src="<?php echo $this->webroot?>images/info-big.png" height="17"/><?php echo __('messa')?></a></li>
               <li><a class="link_btn" rel="popup" href="javascript:void(0)" onclick="deleteAll('<?php echo $this->webroot?>events/del_event/all');" ><i class="icon-remove"></i> <?php echo __('deleteall')?></a></li>
    								<li><a class="link-btn" rel="popup" href="javascript:void(0)" onclick="deleteSelected('producttab','<?php echo $this->webroot?>events/del_event/selected');"><i class="icon-remove"></i> <?php echo __('deleteselected')?></a></li>
        </ul>
</div>
<div id="container">
<?php $d = $p->getDataArray();if (count($d) == 0) {?>
<div class="msg"><?php echo __('no_data_found')?></div>
<?php } else {?>
<div id="toppage"></div>
<table class="list">
	<col style="width: 5%;">
	<col style="width: 10%;">
	<col style="width: 18%;">
	<col style="width: 18%;">
	<col style="width: 20%;">
	<col style="width: 25%;">
	<col style="width: 4%;">
	<thead>
		<tr>
		<td><input type="checkbox" onclick="checkAllOrNot(this,'producttab');" value=""/></td>
    <td><a href="javascript:void(0)" onclick="my_sort('event_log_id','asc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;ID&nbsp;<a href="javascript:void(0)" onclick="my_sort('event_log_id','desc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
    <td><a href="javascript:void(0)" onclick="my_sort('type','asc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<?php echo __('type')?>&nbsp;<a href="javascript:void(0)" onclick="my_sort('type','desc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
    <td><a href="javascript:void(0)" onclick="my_sort('sender','asc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<?php echo __('scriptname')?>&nbsp;<a href="javascript:void(0)" onclick="my_sort('sender','desc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
    <td><a href="javascript:void(0)" onclick="my_sort('action_date','asc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<?php echo __('action_date')?>&nbsp;<a href="javascript:void(0)" onclick="my_sort('action_date','desc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
    <td><a href="javascript:void(0)" onclick="my_sort('message','asc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<?php echo __('message')?>&nbsp;<a href="javascript:void(0)" onclick="my_sort('message','desc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
    <td class="last" style="text-align:center;"><?php echo __('action')?></td>
		</tr>
	</thead>
	<tbody id="producttab">
		<?php 
			$mydata =$p->getDataArray();
			$loop = count($mydata); 
			for ($i=0;$i<$loop;$i++) {?>
				<tr class="row-1">
				<td style="text-align:center"><input type="checkbox" value="<?php echo $mydata[$i][0]['event_log_id']?>"/></td>
		    <td><?php echo $mydata[$i][0]['event_log_id']?></td>
		    <td>
		    		<?php if ($mydata[$i][0]['type'] == 1) {?>
		    				<img src="<?php echo $this->webroot?>images/alert.png"/>
		    		<?php echo __('alert');} else if ($mydata[$i][0]['type'] == 2) {?>
		    				<img src="<?php echo $this->webroot?>images/info-big.png" height="17"/>
		    		<?php echo __('messa');} else {?>
		    		<img src="<?php echo $this->webroot?>images/iconError.gif" height="17"/>
		    		<?php echo __('error');}?>
		    </td>
		    <td style="font-weight: bold;"><?php echo $mydata[$i][0]['sender']?></td>
		    <td><?php echo $mydata[$i][0]['action_date']?></td>
		    <td><?php echo $mydata[$i][0]['message']?></td>
		    <td class="last"><a href="javascript:void(0)" onclick="delConfirm(this,'<?php echo $this->webroot?>eventlogs/del_event/<?php echo $mydata[$i][0]['event_log_id'];?>');"><i class="icon-remove"></i></a></td>
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