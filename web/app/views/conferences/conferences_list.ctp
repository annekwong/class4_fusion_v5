
<div id="title">
  <h1><?php echo __('tool')?>&gt;&gt;
    	<?php echo __('realconf')?>
  </h1>
  <ul id="title-search">
    <li style="display: list-item;" id="title-search-adv" onclick="advSearchToggle();" title="   »" class=""></li>
  </ul>
  <ul id="title-menu">
    <li>
    			<a class="link_back" href="javascript:void(0)" onclick="location.reload();">
    				<img width="16" height="16"  alt="" src="<?php echo $this->webroot?>images/icon_back_white.png">
    				&nbsp;<?php echo __('refresh')?>
    			</a>
    		</li>
  </ul>
</div>
<div id="container">
<?php $d = $p->getDataArray();if (count($d) == 0) {?>
<div class="msg"><?php echo __('no_data_found')?></div>
<?php } else {?>
<fieldset class="title-block" id="advsearch" style="display:none;width:100%;margin-left:1px;">
<form method="post">
<table>
<tbody>
	<tr>
			<td>
				<label style="padding-top:3px;"><?php echo __('Reseller')?>:</label>
				<select id="search_res" name="search_res">
						<option value=""><?php echo __('select')?></option>
						<?php
							for ($i=0;$i<count($res_s);$i++) { 
						?>
								<option value="<?php echo $res_s[$i][0]['reseller_id']?>"><?php echo $res_s[$i][0]['name']?></option>
						<?php
							} 
						?>
				</select>
			</td>
			
			<td>
				<label style="padding-top:3px;"><?php echo __('account')?>:</label>
				<select id="search_acc" name="search_acc">
						<option value=""><?php echo __('select')?></option>
						<?php
							for ($i=0;$i<count($acc_s);$i++) { 
						?>
								<option value="<?php echo $acc_s[$i][0]['card_id']?>"><?php echo $acc_s[$i][0]['card_number']?></option>
						<?php
							} 
						?>
				</select>
			</td>
			
    <td class="buttons"><input type="submit" value="<?php echo __('search')?>" class="input in-submit"></td>
</tr>
</tbody></table>
</form>
<?php
	if (!empty($searchForm)) {
		$d = array_keys($searchForm);
		foreach($d as $k) {?>
			<script type="text/javascript">
				if (document.getElementById("<?php echo $k?>")){
					document.getElementById("<?php echo $k?>").value = "<?php echo $searchForm[$k]?>";
				}
			</script>
<?php 
		}
?>
<script type="text/javascript">document.getElementById("advsearch").style.display='block';</script>
<?php }?>
</fieldset>
<div id="toppage"></div>
<table class="list">
<col style="width: 20%;">
	<col style="width: 20%;">
	<col style="width: 20%;">
	<col style="width: 20%;">
	<col style="width: 20%;">
	<thead>
		<tr>
		<td><?php echo __('account')?></td>
		<td><?php echo __('Reseller')?></td>
		<td><?php echo __('start_time',true);?></td>
		<td><?php echo __('confnums')?></td>
    <td class="last"><?php echo __('action')?></td>
		</tr>
	</thead>
	<tbody id="producttab">
		<?php 
			$mydata =$p->getDataArray();
			$loop = count($mydata); 
			for ($i=0;$i<$loop;$i++) {?>
				<tr class="row-1">
		    <td style="font-weight: bold;"><?php echo $mydata[$i][0]['account']?></td>
		    <td><?php echo $mydata[$i][0]['reseller']?></td>
		    <td><?php echo $mydata[$i][0]['ans_time_a']?></td>
		    <td><a href="<?php echo $this->webroot?>conferences/conf_member_list/<?php echo $mydata[$i][0]['conf_id']?>/<?php echo $mydata[$i][0]['uuid_a']?>"><?php echo $mydata[$i][0]['nums']?></a></td>
		    <td>
		    		<a title="<?php echo __('stopconf')?>" style="float:left;margin-left:45%;" href="<?php echo $this->webroot?>conferences/stop_conf/<?php echo $mydata[$i][0]['uuid_a']?>" onclick="return confirm('确定结束该会议?')" >
		    			<img src="<?php echo $this->webroot?>images/menuIcon_017.gif">
		    		</a>
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