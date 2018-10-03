
    <script type="text/javascript" src="<?php echo $this->webroot?>js/My97DatePicker/WdatePicker.js"></script>
    
    <script type="text/javascript">
    //选择代理商或者客户或者卡  由子页面调用
	function choose(tr){
	document.getElementById('acc').value = tr.cells[1].innerHTML.trim();
	document.body.removeChild(document.getElementById("infodivv"));
	closeCover('cover_tmp');
		}
    </script>

<div id="cover"></div>
<div id="cover_tmp"></div>  
<div id="title">
  <h1><?php echo __('manage')?>&gt;&gt;
   <?php echo __('disconnect')?>
  </h1>
</div>

<div id="container">
<fieldset class="title-block" id="advsearch" style="display:block;width:100%;margin-left:1px;">
<form method="post">
<table>
<tbody><tr>
    <td>
    			<label style="float:left;padding-top:4px;"><?php echo __('Reseller')?>:</label>
    			<select id="res_id" name="res_id" style="float:left;width:150px;">
    			<option value=""><?php echo __('select')?></option>
    			<?php
							for ($i=0;$i<count($r_reseller);$i++){ 
						?>
								<option value="<?php echo $r_reseller[$i][0]['reseller_id']?>">
									<?php
										$space = "";
										for ($j=0;$j<$r_reseller[$i][0]['spaces'];$j++) {
											 	$space .= "&nbsp;&nbsp;";
										}
										if ($i==0){
											echo "{$r_reseller[$i][0]['name']}";
										} else {
											echo "&nbsp;&nbsp;".$space."↳".$r_reseller[$i][0]['name'];
										}
									?>
								</option>
							<?php
								} 
							?>
    			</select>
    </td>
    
    <td>
    			<label><?php echo __('account')?></label>
    			<input id="acc" name="acc" readonly onfocus="loadPage('<?php echo $this->webroot?>cdrs/choose_cards',500,400);"/>
    			<img src="<?php echo $this->webroot?>images/delete-small.png" onclick="$('#acc').val('');"/>
    </td>
    
    <td>
    			<label style="float:left;padding-top:4px;"><?php echo __('ingress')?>:</label>
    			<select id="ingress" name="ingress" style="float:left;width:150px;">
    			<option value=""><?php echo __('select')?></option>
    			<?php
    					$loop = count($ingress);
    					for ($i = 0;$i<$loop;$i++) { 
    				?>
    						<option value="<?php echo $ingress[$i][0]['alias']?>"><?php echo $ingress[$i][0]['name']?></option>
    			<?php
    						} 
    				?>
    			</select>
    </td>
    
    <td>
    			<label style="float:left;padding-top:4px;"><?php echo __('egress')?>:</label>
    			<select id="egress" name="egress" style="float:left;width:150px;">
    			<option value=""><?php echo __('select')?></option>
    			<?php
    					$loop = count($egress);
    					for ($i = 0;$i<$loop;$i++) { 
    				?>
    						<option value="<?php echo $egress[$i][0]['alias']?>"><?php echo $egress[$i][0]['name']?></option>
    			<?php
    						} 
    				?>
    			</select>
    </td>
    
    <td>
    			<label style="float:left;padding-top:4px;"><?php echo __('ani')?>:</label>
    			<input type="text" style="float:left;width:150px;" id="ani"  name="ani" class="input in-text">
    </td>
    
    <td>
    			<label style="float:left;padding-top:4px;"><?php echo __('dnis')?>:</label>
    			<input type="text" style="float:left;width:150px;" id="dnis"  name="dnis" class="input in-text">
    </td>
</tr>
<tr>
		<td>
    			<label style="float:left;padding-top:4px;"><?php echo __('src')?>:</label>
    			<input type="text" style="float:left;width:150px;" id="src"  name="src" class="input in-text">
    </td>
    
    <td>
    			<label style="float:left;padding-top:4px;"><?php echo __('dst')?>:</label>
    			<input type="text" style="float:left;width:150px;" id="dst"  name="dst" class="input in-text">
    </td>
    
    <td>
    			<label style="float:left;padding-top:4px;"><?php echo __('duration')."(".__('minutes',true).")"?>:</label>
    			<input type="text" style="float:left;width:150px;" id="sdura"  name="sdura" class="input in-text">
    </td>
    
    <td>
    			<label style="float:left;padding-top:4px;"><?php echo __('duration')."(".__('minutes',true).")"?>:</label>
    			<input type="text" style="float:left;width:150px;" id="edura"  name="edura" class="input in-text">
    </td>
    
    <td>
    			<label style="float:left;padding-top:4px;"><?php echo __('start_time',true);?>:</label>
    			<input type="text" class="Wdate" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'});" readonly style="height:20px;float:left;width:150px;" id="st"  name="st" class="input in-text">
    </td>
    <td>
    		<label style="float:left;padding-top:6px;"><?php echo __('end_time',true);?>:</label>
    		<input type="text" class="Wdate" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'});" readonly style="height:20px;float:left;width:150px;" id="et"  name="et" class="input in-text">
    	</td>
    	
    <td class="buttons"><input type="submit" value="<?php echo __('search')?>" class="input in-submit"></td>
</tr>
</tbody></table>
</form>
</fieldset>
    <!-- DYNAREA -->
                <div class="group-title">
    </div>
<table class="list">
<thead>
    <tr>
        <td width="10%"><?php echo __('egress')?></td>
        <td width="10%">IP</td>
        <td width="10%"><?php echo __('sipcode')?></td>
        <td width="10%"><?php echo __('ofcalls')?></td>
        <td width="10%">%</td>
        <td width="30%">&nbsp;</td>
        <td width="40%" class="last"><?php echo __('releasecause')?></td>
    </tr>
</thead>
<tbody>
	<?php
		$loop = count($result);
		$total = 0;
		for ($i = 0;$i<$loop;$i++) { 
			$total += $result[$i][0]['nums'];
		}
		for ($i = 0;$i<$loop;$i++) { 
	?>
			<tr class="row-1">
		    <td align="center"><b><?php echo $result[$i][0]['trunk']?></b></td>
		    <td class="in-decimal"><?php echo $result[$i][0]['host']?></td>
		    <td class="in-decimal"><?php echo $result[$i][0]['sipcode']?></td>
		    <td class="in-decimal" style="color:red"><?php echo $result[$i][0]['nums']?></td>
		    <td class="in-decimal"><?php echo "<span style='color:red'>".number_format($result[$i][0]['nums']/$total*100,2)."</span>"?></td>
		    <td><div class="bar"><div style="width: <?php echo number_format($result[$i][0]['nums']/$total*100,2)?>%;"><?php echo number_format($result[$i][0]['nums']/$total*100,2)?>%&nbsp;</div></div></td>
		    <td class="last"><?php echo $result[$i][0]['release_cause']?></td>
			</tr>
	<?php
		} 
	?>
</tbody>
</table>
    <!-- DYNAREA -->
  <!-- 高级搜索条件 -->
<?php
			if (!empty($searchform)) {
				//将用户刚刚输入的数据显示到页面上
				$d = array_keys($searchform);
			 foreach($d as $k) { ?>
						<script>document.getElementById("<?php echo $k?>").value = "<?php echo $searchform[$k]?>";</script>
<?php }}?>
</div>
<div id="livemargins_control" style="position: absolute; display: none; z-index: 9999;"><img width="77" height="5" style="position: absolute; left: -77px; top: -5px;" src="chrome://livemargins/skin/monitor-background-horizontal.png">	<img style="position: absolute; left: 0pt; top: -5px;" src="chrome://livemargins/skin/monitor-background-vertical.png">	<img style="position: absolute; left: 1px; top: 0pt; opacity: 0.5; cursor: pointer;" onmouseout="this.style.opacity=0.5" onmouseover="this.style.opacity=1" src="chrome://livemargins/skin/monitor-play-button.png" id="monitor-play-button"></div>