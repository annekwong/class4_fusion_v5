<div id="cover"></div>
<div id="title">
  <h1><?php echo __('tool')?>&gt;&gt;
    	<?php echo __('regphone')?>
  </h1>
  <ul id="title-search">
  		<li>
	    	<form>
	    		<input type="text" id="search-_q" class="in-search default-value input in-text defaultText" title="<?php echo __('namesearch')?>" value="<?php if (!empty($search)) echo $search;?>" name="search">
	    	</form>
    </li>
  </ul>
</div>

<div id="cover"></div>
<div id="container">
<?php $d = $p->getDataArray();if (count($d) == 0) {?>
<div class="msg"><?php echo __('no_data_found')?></div>
<?php } else {?>
<div id="toppage"></div>
<table class="list">
	<col style="width: 20%;">
	<col style="width: 20%;">
	<col style="width: 20%;">
	<col style="width: 20%;">
	<col style="width: 20%;">
	<thead>
		<tr>
		<td><?php echo __('id',true);?></td>
		<td><?php echo __('account')?></td>
		<td><?php echo __('user_agent')?></td>
		<td><?php echo __('socket')?></td>
		<td><?php echo __('last_modified')?></td>
		</tr>
	</thead>
	<tbody id="rec_strategy">
		<?php 
			$mydata =$p->getDataArray();
			$loop = count($mydata); 
			for ($i=0;$i<$loop;$i++) {?>
				<tr class="row-1">
				<td class="in-decimal" style="text-align:center"><?php echo $mydata[$i][0]['id']?></td>
		    <td style="font-weight: bold;"><?php echo $mydata[$i][0]['username']?></td>
		    <td><?php echo $mydata[$i][0]['user_agent']?></td>
		    <td><?php echo $mydata[$i][0]['socket']?></td>
		    <td><?php echo $mydata[$i][0]['last_modified']?></td>
				</tr>
		<?php }?>		
	</tbody>
</table>
<div id="tmppage">
<?php echo $this->element('page');?>
</div>
<?php }?>
</div>
<div id="livemargins_control" style="position: absolute; display: none; z-index: 9999;"><img width="77" height="5" style="position: absolute; left: -77px; top: -5px;" src="chrome://livemargins/skin/monitor-background-horizontal.png">	<img style="position: absolute; left: 0pt; top: -5px;" src="chrome://livemargins/skin/monitor-background-vertical.png">	<img style="position: absolute; left: 1px; top: 0pt; opacity: 0.5; cursor: pointer;" onmouseout="this.style.opacity=0.5" onmouseover="this.style.opacity=1" src="chrome://livemargins/skin/monitor-play-button.png" id="monitor-play-button"></div>