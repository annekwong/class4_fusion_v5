
    <script type="text/javascript" src="<?php echo $this->webroot?>js/My97DatePicker/WdatePicker.js"></script>

<div id="title">
  <h1><?php echo __('tool')?>&gt;&gt;
    	<?php echo __('historyconf')?>
  </h1>
  <ul id="title-search">
    <li style="display: list-item;" id="title-search-adv" onclick="advSearchToggle();" title="   »" class=""></li>
  </ul>
</div>
<div id="container">
<fieldset class="title-block" id="advsearch" style="display:none;width:100%;margin-left:1px;">
<form method="post">
<table>
<tbody>
	<tr>
			<td>
				<label style="padding-top:3px;"><?php echo __('start_time',true);?>:</label>
				<input class="Wdate input in-text" id="search_st" name="search_st" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})" readonly />
			</td>
			
			<td>
				<label style="padding-top:3px;"><?php echo __('end_time',true);?>:</label>
				<input class="Wdate input in-text"  id="search_et" name="search_et" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})" readonly />
			</td>
			
			<td>
				<label style="padding-top:3px;"><?php echo __('memnum')?>:</label>	
				<input id="search_num" name="search_num"/>
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
<table class="list">
<col style="width: 20%;">
	<col style="width: 20%;">
	<col style="width: 20%;">
	<col style="width: 20%;">
	<col style="width: 20%;">
	<thead>
		<tr>
		<td><?php echo __('confid')?></td>
		<td><?php echo __('memnum')?></td>
		<td><?php echo __('account')?></td>
		<td><?php echo __('Reseller')?></td>
    <td class="last"><?php echo __('start_time',true);?></td>
		</tr>
	</thead>
	<tbody id="producttab">
		<?php 
			$mydata =$p->getDataArray();
			$loop = count($mydata); 
			for ($i=0;$i<$loop;$i++) {?>
				<tr class="row-1">
					<td><?php echo $mydata[$i][0]['conf_id']?></td>
					<td><?php echo $mydata[$i][0]['termination_destination_number']?></td>
					<td><?php echo $mydata[$i][0]['account']?></td>
					<td><?php echo $mydata[$i][0]['Reseller']?></td>
					<td><?php echo $mydata[$i][0]['answer_time_of_date']?></td>
				</tr>
		<?php }?>		
	</tbody>
	<tbody>
</tbody>
</table>
</div>
<div id="livemargins_control" style="position: absolute; display: none; z-index: 9999;"><img width="77" height="5" style="position: absolute; left: -77px; top: -5px;" src="chrome://livemargins/skin/monitor-background-horizontal.png">	<img style="position: absolute; left: 0pt; top: -5px;" src="chrome://livemargins/skin/monitor-background-vertical.png">	<img style="position: absolute; left: 1px; top: 0pt; opacity: 0.5; cursor: pointer;" onmouseout="this.style.opacity=0.5" onmouseover="this.style.opacity=1" src="chrome://livemargins/skin/monitor-play-button.png" id="monitor-play-button"></div>