
<div id="title">
  <h1>
    <?php echo __('tool')?>&gt;&gt;
    	<?php echo __('confm')?>
  </h1>
  <ul id="title-menu">
    		<li>
    			<a class="link_back" href="<?php echo $this->webroot?>conferences/conferences_list">
    				<img width="16" height="16"  alt="" src="<?php echo $this->webroot?>images/icon_back_white.png">
    				&nbsp;<?php echo __('goback')?>
    			</a>
    		</li>
  		</ul>
</div>
<div id="container">
<table class="list">
<col style="width: 35%;">
	<col style="width: 35%;">
	<col style="width: 30%;">
	<thead>
		<tr>
		<td><?php echo __('phone')?></td>
		<td><?php echo __('start_time',true);?></td>
    <td class="last"><?php echo __('action')?></td>
		</tr>
	</thead>
	<tbody id="producttab">
		<?php 
			$mydata =$members;
			$loop = count($mydata); 
			for ($i=0;$i<$loop;$i++) {?>
				<tr class="row-1">
		    <td style="font-weight: bold;">
		    			<?php if ($mydata[$i][0]['uuid_b'] == $uuid_a) {?>
		    						<img src="<?php echo $this->webroot?>images/menuIcon.gif" title="<?php echo __('confowner')?>"/>
		    			<?php }?>
		    		<?php echo $mydata[$i][0]['dnis']?>
		    </td>
		    <td><?php echo $mydata[$i][0]['ans_time_a']?></td>
		    <td>
		    		<a title="<?php echo __('stopconf')?>" onclick="return confirm('确定剔除该成员?')" style="float:left;margin-left:45%;" href="<?php echo $this->webroot?>conferences/kill_member/<?php echo $mydata[$i][0]['uuid_b']?>/<?php echo $mydata[$i][0]['conf_id']?>/<?php echo $uuid_a?>" >
		    			<img src="<?php echo $this->webroot?>images/menuIcon_017.gif">
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