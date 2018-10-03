
    <script type="text/javascript" src="<?php echo $this->webroot?>js/My97DatePicker/WdatePicker.js"></script>
<div id="cover"></div>
  <div id="title"><h1>
<?php echo __('account')?>&gt;&gt;
    	<?php echo __('payment')?>
  </h1></div>
<div id="container">
<fieldset class="title-block" id="advsearch" style="display:block;width:100%;margin-left:1px;">
<form method="post">
<table>
<tbody>
	<tr>
			<td>
				<label style="padding-top:3px;"><?php echo __('refillamount')?></label>
				<input style="width:60px;height:20px;" id="search_amount_s" name="search_amount_s"/>
				--
				<input style="width:60px;height:20px;" id="search_amount_e" name="search_amount_e"/>
			</td>
			<td>
				<label style="padding-top:3px;"><?php echo __('timeprofile')?></label>
				<input readonly class="wdate input in-text" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})" style="width:120px;height:20px;" id="search_time_s" name="search_time_s"/>
				--
				<input readonly class="wdate input in-text" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})" style="width:120px;height:20px;" id="search_time_e" name="search_time_e"/>
			</td>
			<td>
				<label style="paddint-top:3px;"><?php echo __('refillresult')?></label>
				<select style="width:60px;height:20px;" id="search_result" name="search_result">
					<option value=""><?php echo __('select')?></option>
					<option value="true"><?php echo __('success')?></option>
					<option value="false"><?php echo __('failed')?></option>
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
		}}
?>
</fieldset>
<div id="toppage"></div>
<table class="list">
	<col  style="width: 6%">
	<col style="width: 18%;">
	<col style="width: 18%;">
	<col style="width: 18%;">
	<col style="width: 20%;">
	<col style="width: 20%;">
	<thead>
		<tr>
		 <td><a href="javascript:void(0)" onclick="my_sort('account_payment_id','asc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<?php echo __('id',true);?>&nbsp;<a href="javascript:void(0)" onclick="my_sort('account_payment_id','desc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
    <td><a href="javascript:void(0)" onclick="my_sort('payment_time','asc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<?php echo __('payment_time')?>&nbsp;<a href="javascript:void(0)" onclick="my_sort('payment_time','desc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
    <td><a href="javascript:void(0)" onclick="my_sort('amount','asc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<?php echo __('amount')?>&nbsp;<a href="javascript:void(0)" onclick="my_sort('amount','desc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
    <td><?php echo __('payment_method')?></td>
    <td><?php echo __('result')?></td>
    <td class="last"><?php echo __('cause')?></td>
		</tr>
	</thead>
	<tbody id="servicetab">
		<?php 
			$mydata =$p->getDataArray();
			$loop = count($mydata); 
			for ($i=0;$i<$loop;$i++) {?>
				<tr class="row-1">
				 <td><?php echo $mydata[$i][0]['account_payment_id']?></td>
		    <td><?php echo $mydata[$i][0]['payment_time']?></td>
		    <td style="color:green"><?php echo $mydata[$i][0]['amount']?></td>
		    <td>
		    			<?php
			if ($mydata[$i][0]['platform_trace']=="SZX"){echo "神州行";}
		    					 else if ($mydata[$i][0]['platform_trace']=="DX"){echo "电信";}
		    					 else if ($mydata[$i][0]['platform_trace']=="LT"){echo "联通";}
		    					 else if ($mydata[$i][0]['platform_trace']=="QB"){echo "Q币";}
		    					 else {echo $mydata[$i][0]['platform_trace'];}
		    				?>
		    </td>
		    <td><?php echo $mydata[$i][0]['result']==1?__('success',true):__('failed',true)?></td>
		    <td><?php echo $mydata[$i][0]['cause']?></td>
				</tr>
		<?php }?>		
	</tbody>
	<tbody>
</tbody>
</table>
<div id="tmppage">
<?php echo $this->element('page');?>
</div>
</div>
<div id="livemargins_control" style="position: absolute; display: none; z-index: 9999;"><img width="77" height="5" style="position: absolute; left: -77px; top: -5px;" src="chrome://livemargins/skin/monitor-background-horizontal.png">	<img style="position: absolute; left: 0pt; top: -5px;" src="chrome://livemargins/skin/monitor-background-vertical.png">	<img style="position: absolute; left: 1px; top: 0pt; opacity: 0.5; cursor: pointer;" onmouseout="this.style.opacity=0.5" onmouseover="this.style.opacity=1" src="chrome://livemargins/skin/monitor-play-button.png" id="monitor-play-button"></div>