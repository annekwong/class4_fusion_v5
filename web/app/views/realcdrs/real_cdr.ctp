
    <script type="text/javascript" src="<?php echo $this->webroot?>js/My97DatePicker/WdatePicker.js"></script>
<div id="cover"></div>
<div id="title">
  <h1>
 
    	<?php echo __('Active Call Report ')?>
  </h1>
  <ul id="title-search">
    <li onclick="advSearchToggle();" id="title-search-adv" style="display: list-item;"></li>
  </ul>
  <ul id="title-menu">
    <li>
    			<a  class="link_back"href="javascript:void(0)" onclick="location.reload();">
    				<img width="16" height="16"  alt="" src="<?php echo $this->webroot?>images/icon_back_white.png">
    				&nbsp;<?php echo __('refresh')?>
    			</a>
    		</li>
  </ul>
</div>
<div id="container">
<ul class="tabs">



  <li    class="active"><a href="<?php echo $this->webroot?>realcdrs/real_cdr"><img width="16" height="16" src="<?php echo $this->webroot?>images/bNotes.gif">在线通话</a></li>
    <li><a href="<?php echo $this->webroot?>conferences/conferences_list"><img width="16" height="16" src="<?php echo $this->webroot?>images/bNotes.gif">在线会议</a></li>
      <li><a href="<?php echo $this->webroot?>audiotests/audio_test"><img width="16" height="16" src="<?php echo $this->webroot?>images/bNotes.gif">在线网关</a></li>
        <li><a href="<?php echo $this->webroot?>locations/reg_account"><img width="16" height="16" src="<?php echo $this->webroot?>images/bNotes.gif">在线话机</a></li>
           <li ><a href="<?php echo $this->webroot?>realcdrs/callback_cdr"><img width="16" height="16" src="<?php echo $this->webroot?>images/bNotes.gif">在线回拨电话</a></li>
       </ul>
<?php $d = $p->getDataArray();if (count($d) == 0) {?>
<div class="msg"><?php echo __('no_data_found')?></div>
<?php } else {?>
<fieldset class="title-block" id="advsearch" style="display: none;width:100%;margin-left:1px;">
<form method="post">
	<input type="hidden" name="search" value="true"/>
	<fieldset class="title-block"   style="margin-left:0px;width: 100%;">
		<table>
				<tbody>
				<tr>
				    <td>
				    		<label><?php echo __('start_time',true);?>:</label><input id="st" name="st" class="input in-text wdate" style="width:120px;height:20px;" readonly onfocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})"/>
				  		</td>
				  		<td>
				    		<label><?php echo __('end_time',true);?>:</label><input id="et" name="et" class="input in-text wdate" style="width:120px;height:20px;" readonly onfocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})"/>
				  		</td>
				  		<td>
				    		<label><?php echo __('dstnumber')?>:</label><input id="dst" name="dst" class="input in-text" style="width:100px;height:20px;"/>
				  		</td>
				  		<td>
				    		<label><?php echo __('srcnumber')?>:</label><input id="src" name="src" class="input in-text" style="width:100px;height:20px;"/>
				  		</td>
				  		<td>
				    		<label><?php echo __('dur',true)?>(<?php echo __('second',true)?>):</label><input id="dur" name="dur" class="input in-text" style="width:60px;height:20px;"/>
				  		</td>
				  		<td>
				    		<label><?php echo __('ingress')?>:</label><select id="ing" name="ing" class="in-select input" style="width:100px;height:20px;">
				    					<option value=""><?php echo __('select')?></option>
				    					<?php
				    							$loop = count($ingress);
				    							for ($i = 0;$i<$loop;$i++) { 
				    					?>
				    								<option value="<?php echo $ingress[$i][0]['resource_id']?>"><?php echo $ingress[$i][0]['name']?></option>
				    				<?php 
				    								}
				    					?>
				    		</select>
				  		</td>
				  		<td>
				    		<label><?php echo __('egress')?>:</label><select id="eg" name="eg" class="in-select input" style="width:100px;height:20px;">
				    					<option value=""><?php echo __('select')?></option>
				    					<?php
				    							$loop = count($egress);
				    							for ($i = 0;$i<$loop;$i++) { 
				    					?>
				    								<option value="<?php echo $egress[$i][0]['resource_id']?>"><?php echo $egress[$i][0]['name']?></option>
				    				<?php 
				    								}
				    					?>
				    		</select>
				  		</td>
				  		<td>
				    		<label><?php echo __('Reseller')?>:</label><select id="res" name="res" class="in-select input" style="width:100px;height:20px;">
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
				    		<label><?php echo __('client')?>:</label><select id="cli" name="cli" class="in-select input" style="width:100px;height:20px;">
				    					<option value=""><?php echo __('select')?></option>
				    					<?php
				    							$loop = count($client);
				    							for ($i = 0;$i<$loop;$i++) { 
				    					?>
				    								<option value="<?php echo $client[$i][0]['client_id']?>"><?php echo $client[$i][0]['name']?></option>
				    				<?php 
				    								}
				    					?>
				    		</select>
				  		</td>
				  		
				  		<td>
				    		<label><?php echo __('account')?>:</label><select id="acc" name="acc" class="in-select input" style="width:100px;height:20px;">
				    					<option value=""><?php echo __('select')?></option>
				    					<?php
				    							$loop = count($accounts);
				    							for ($i = 0;$i<$loop;$i++) { 
				    					?>
				    								<option value="<?php echo $accounts[$i][0]['card_id']?>"><?php echo $accounts[$i][0]['card_number']?></option>
				    				<?php 
				    								}
				    					?>
				    		</select>
				  		</td>
				  		<td><label><input style="margin-top:13px;" type="submit" class="button in-submit" value="<?php echo __('submit')?>"/></label></td>
				</tr>
				</tbody></table>
		</fieldset>
</form>
</fieldset>
<div id="toppage"></div>
<table class="list">
	<col style="width: 5%;">
	<col style="width: 6%;">
	<col style="width: 10%;">
	<col style="width: 18%;">
	<col style="width: 7%;">
	<col style="width: 6%;">
	<col style="width: 5%;">
	<col style="width: 5%;">
	<col style="width: 5%;">
	<col style="width: 9%;">
	<col style="width: 8%;">
	<col style="width: 12%;">
	<thead>
		<tr>
    <td><a href="javascript:void(0)" onclick="my_sort('ani','asc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<?php echo __('srcnumber')?>&nbsp;<a href="javascript:void(0)" onclick="my_sort('ani','desc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
    <td><a href="javascript:void(0)" onclick="my_sort('dnis','asc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<?php echo __('dstnumber')?>&nbsp;<a href="javascript:void(0)" onclick="my_sort('dnis','desc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
    <td><a href="javascript:void(0)" onclick="my_sort('ani_address','asc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<?php echo __('ani_address')?>&nbsp;<a href="javascript:void(0)" onclick="my_sort('ani_address','desc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
    <td><a href="javascript:void(0)" onclick="my_sort('start_time','asc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<?php echo __('start_time',true);?>&nbsp;<a href="javascript:void(0)" onclick="my_sort('start_time','desc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
    <td><a href="javascript:void(0)" onclick="my_sort('ingress','asc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<?php echo __('ingress')?>&nbsp;<a href="javascript:void(0)" onclick="my_sort('ingress','desc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
    <td><a href="javascript:void(0)" onclick="my_sort('ingress_codec','asc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<?php echo __('ingress_codec')?>&nbsp;<a href="javascript:void(0)" onclick="my_sort('ingress_codec','desc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
    <td><a href="javascript:void(0)" onclick="my_sort('egress','asc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<?php echo __('egress')?>&nbsp;<a href="javascript:void(0)" onclick="my_sort('egress','desc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
    <td><a href="javascript:void(0)" onclick="my_sort('egress_codec','asc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<?php echo __('egress_codec')?>&nbsp;<a href="javascript:void(0)" onclick="my_sort('egress_codec','desc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
    <td><a href="javascript:void(0)" onclick="my_sort('reseller','asc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<?php echo __('Reseller')?>&nbsp;<a href="javascript:void(0)" onclick="my_sort('reseller','desc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
    <td><a href="javascript:void(0)" onclick="my_sort('client','asc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<?php echo __('client')?>&nbsp;<a href="javascript:void(0)" onclick="my_sort('client','desc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
    <td><a href="javascript:void(0)" onclick="my_sort('server_ip','asc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<?php echo __('fs_ip')?>&nbsp;<a href="javascript:void(0)" onclick="my_sort('server_ip','desc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
    <td class="last"><?php echo __('action')?></td>
		</tr>
	</thead>
	<tbody id="realcdrs">
		<?php 
				$mydata =$p->getDataArray();
				$loop = count($mydata); 
				for ($i=0;$i<$loop;$i++) {?>
					<tr class="row-1" rel="tooltip" id="i_<?php echo $loop-$i?>">
			    <td><?php echo $mydata[$i][0]['ani']?></td>
			    <td><?php echo $mydata[$i][0]['dnis']?></td>
			    <td><?php echo $mydata[$i][0]['ani_address']?></td>
			    <td style="float:left;"><?php echo $mydata[$i][0]['start_time']?></td>
			    <td><?php echo $mydata[$i][0]['ingress']?></td>
			    <td><?php echo $mydata[$i][0]['ingress_codec']?></td>
			    <td><?php echo $mydata[$i][0]['egress']?></td>
			    <td><?php echo $mydata[$i][0]['egress_codec']?></td>
			    <td><?php echo $mydata[$i][0]['reseller']?></td>
			    <td><?php echo $mydata[$i][0]['client']?></td>
			    <td><?php echo $mydata[$i][0]['server_ip']?></td>
			    <td>
			    		<a style="float:left;margin-left:38%" href="<?php echo $this->webroot?>realcdrs/kill_member/<?php echo $mydata[$i][0]['uuid_a']?>" onclick="return confirm('您确定要挂断该电话?');">
			    			<img src="<?php echo $this->webroot?>images/menuIcon_017.gif" />
			    		</a>
			    </td>
					</tr>
					
					
						<dl id="i_<?php echo $loop-$i?>-tooltip" class="tooltip">
    <dl><?php echo  __('account')?>:</dl>
    <dd><?php echo $mydata[$i][0]['account']?></dd>
    
    <dl><?php echo __('caller_packets')?>:</dl>
    <dd><?php echo $mydata[$i][0]['caller_packets'].__('g',true)."(".$mydata[$i][0]['caller_bytes'].")".__('bytes',true)?></dd>
    
    <dl><?php echo __('callee_packets')?>:</dl>
    <dd><?php echo $mydata[$i][0]['callee_packets'].__('g',true)."(".$mydata[$i][0]['callee_bytes'].")".__('bytes',true)?></dd>
	</dl>
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
<!-- 高级搜索条件 -->
<?php
			if (!empty($searchform)) {
				//将用户刚刚输入的数据显示到页面上
				$d = array_keys($searchform);
			 foreach($d as $k) { ?>
						<script>if (document.getElementById("<?php echo $k?>"))document.getElementById("<?php echo $k?>").value = "<?php echo $searchform[$k]?>";</script>
<?php }?>
		<script>document.getElementById("advsearch").style.display="block";</script>
<?php }?>
