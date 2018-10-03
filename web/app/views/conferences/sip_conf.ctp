
<div id="title">
  <h1><?php echo __('tool')?>&gt;&gt;
    	<?php echo __('confm')?>
  </h1>
</div>
<div id="container">
<table class="list">
	<col style="width: 50%;">
	<col style="width: 50%;">
	<thead>
		<tr>
		<td><?php echo __('phone')?></td>
		<td><?php echo __('start_time',true);?></td>
		</tr>
	</thead>
	<tbody id="producttab">
		<?php 
			$mydata =$conf;
			$loop = count($mydata); 
			for ($i=0;$i<$loop;$i++) {?>
				<tr class="row-1">
		    <td style="font-weight: bold;">
		    			<?php if ($mydata[$i][0]['uuid_b'] == $mydata[$i][0]['uuid_a']) {?>
		    						<img src="<?php echo $this->webroot?>images/menuIcon.gif" title="<?php echo __('confowner')?>"/>
		    			<?php }?>
		    		<?php echo $mydata[$i][0]['dnis']?>
		    </td>
		    <td><?php echo $mydata[$i][0]['ans_time_a']?></td>
				</tr>
		<?php }?>		
	</tbody>
	<tbody>
</tbody>
</table>
</div>
<div id="livemargins_control" style="position: absolute; display: none; z-index: 9999;"><img width="77" height="5" style="position: absolute; left: -77px; top: -5px;" src="chrome://livemargins/skin/monitor-background-horizontal.png">	<img style="position: absolute; left: 0pt; top: -5px;" src="chrome://livemargins/skin/monitor-background-vertical.png">	<img style="position: absolute; left: 1px; top: 0pt; opacity: 0.5; cursor: pointer;" onmouseout="this.style.opacity=0.5" onmouseover="this.style.opacity=1" src="chrome://livemargins/skin/monitor-play-button.png" id="monitor-play-button"></div>