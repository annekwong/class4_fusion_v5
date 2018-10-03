   <script type="text/javascript" src="<?php echo $this->webroot?>js/My97DatePicker/WdatePicker.js"></script>
    <script type="text/javascript">
    //选择代理商或者客户或者卡  由子页面调用
			function choose(tr){
					document.getElementById('search_card').value = tr.cells[1].innerHTML.trim();
					document.body.removeChild(document.getElementById("infodivv"));
					closeCover('cover_tmp');
				}

    </script>

<div id="cover"></div>
<div id="title">
  <h1>
    <?php echo __('account')?>&gt;&gt;
    	<?php echo __('reocnum')?>
  </h1>
  <ul id="title-search">
  		<li style="display: list-item;" id="title-search-adv" onclick="advSearchToggle();" title="   »" class=""></li>
  </ul>
</div>

<div id="cover"></div>
<div id="cover_tmp"></div>
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
				<label style="padding-top:3px;"><?php echo __('account')?>:</label>
				<input style="width:120px;height:20px;" readonly onfocus="cover('cover_tmp');loadPage('<?php echo $this->webroot?>cdrs/choose_cards',500,400);" name="search_card" id="search_card"/>
			</td>
			<td style="float:left;">
					<label style="padding-top:3px;"><?php echo __('timeprofile')?>:</label>
					<input style="height:20px;" name="search_st" id="search_st" readonly class="wdate input in-text"/>
					--
					<input style="height:20px;" name="search_et" id="search_et" readonly class="wdate input in-text"/>
			</td>
			
    <td class="buttons" style="float:left;padding-top:18px;"><input type="submit" value="<?php echo __('search')?>" class="input in-submit"></td>
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
		<td><?php echo __('id',true);?></td>
		<td><?php echo __('account')?></td>
		<td><?php echo __('reocnum')?></td>
		<td><?php echo __('reged')?></td>
		<td><?php echo __('recommendtime')?></td>
		</tr>
	</thead>
	<tbody id="rec_strategy">
		<?php 
			$mydata =$p->getDataArray();
			$loop = count($mydata); 
			for ($i=0;$i<$loop;$i++) {?>
				<tr class="row-1">
				<td class="in-decimal" style="text-align:center"><?php echo $mydata[$i][0]['recommend_record_id']?></td>
		    <td><?php echo empty($mydata[$i][0]['account'])?__('accountnotexists',true):$mydata[$i][0]['account'];?></td>
		    <td style="font-weight: bold;"><?php echo $mydata[$i][0]['recommend_number']?></td>
		    <td><?php echo $mydata[$i][0]['reg']==true?__('yes',true):__('no',true);?></td>
		    <td><?php echo $mydata[$i][0]['recommend_time']?></td>
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