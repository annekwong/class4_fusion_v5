
    <script type="text/javascript" src="<?php echo $this->webroot?>js/My97DatePicker/WdatePicker.js"></script>
    
    <script>
    				function buck_update(){
        			var value = document.getElementById("buck_card_value").value;
        			var res = document.getElementById("buck_reseller").value;
        			var buck_eff = document.getElementById("buck_eff").value;
        			var buck_used = document.getElementById("buck_used").value;
        			var buck_expire_date = document.getElementById("buck_expire_date").value;
        			
        			var cards = document.getElementsByName("buck_card");
        			var selected_cards = '';
        			var loop = cards.length;
        			for (var i = 0;i<loop;i++) {
            	var c = cards[i];
            	if (c.checked == true) {
            		selected_cards += c.value+",";
                					}
            					}
								if (selected_cards && selected_cards.length >= 1){
									selected_cards = selected_cards.substring(0,selected_cards.lastIndexOf(","));
								}
								
								var post_data = {
																			card_ids:selected_cards,
																			ed:buck_expire_date,
																			res:res,
																			value:value,
																			buck_eff:buck_eff,
																			buck_used:buck_used
																		};

								jQuery.post("<?php echo $this->webroot?>refillpools/buck_update",post_data,function(data){
									var tmp = data.split("|");
									var p = {theme:'jmsg-success',life:100,beforeClose:function(){location.reload()}};
									if (tmp[1].trim() == 'false') {
										p = {theme:'jmsg-alert',life:3000};
									} else {
										closeCover('buckupdate');
									}
									jGrowl_to_notyfy(tmp[0],p);
								});
        				}

    				function effective(pool_id){
    					var cards = document.getElementsByName("ef_card");
            			var selected_cards = '';
            			var loop = cards.length;
            			for (var i = 0;i<loop;i++) {
                	var c = cards[i];
                	if (c.checked == true) {
                		selected_cards += c.value+",";
                    					}
                					}
    								if (selected_cards && selected_cards.length >= 1){
    									selected_cards = selected_cards.substring(0,selected_cards.lastIndexOf(","));
    								}

    								var res = document.getElementById("eff_reseller").value;

    							jQuery.post("<?php echo $this->webroot?>refillpools/effective",{card_ids:selected_cards,res:res,pool_id:pool_id},function(data){
    									var tmp = data.split("|");
    									var p = {theme:'jmsg-success',life:100,beforeClose:function(){location.reload()}};
    									if (tmp[1].trim() == 'false') {
    										p = {theme:'jmsg-alert',life:3000};
    									} else {
    										closeCover('effective_card');
    									}
    									jGrowl_to_notyfy(tmp[0],p);
    								});
        				}


    				function effective_one(pool_id){
    					var res = document.getElementById("eff_reseller_one").value;

    					var card_id = document.getElementById("card_one").value;

						jQuery.post("<?php echo $this->webroot?>refillpools/effective",{card_ids:card_id,res:res,pool_id:pool_id},function(data){
								var tmp = data.split("|");
								var p = {theme:'jmsg-success',life:100,beforeClose:function(){location.reload()}};
								if (tmp[1].trim() == 'false') {
									p = {theme:'jmsg-alert',life:3000};
								} else {
									closeCover('effective_card');
								}
								jGrowl_to_notyfy(tmp[0],p);
							});
        				}
    </script>

<div id="cover"></div>
<div id="title">
  <h1><?php echo __('retail')?>&gt;&gt;
    	<?php echo __('viewcard')?>
  </h1>
  <ul id="title-search">
    <li>
	    	<form>
	    		<input type="text" id="search-_q" class="in-search default-value input in-text defaultText" title="<?php echo __('numbersearch')?>" value="<?php if (!empty($search)) echo $search;?>" name="search">
	    	</form>
    </li>
    <li onclick="advSearchToggle();" id="title-search-adv" style="display: list-item;"></li>
  </ul>
    
  <ul id="title-menu">
  <?php if (isset($edit_return)) {?>
        <li>
    			<a class="link_back" href="<?php echo $this->webroot?>refillpools/cards_list/<?php echo $credit_card_series_id?>">
    				<img width="16" height="16"  alt="" src="<?php echo $this->webroot?>images/icon_back_white.png">
    				&nbsp;<?php echo __('gobackall')?>
    			</a>
    		</li>
        <?php }?>
    <li><a class="link_btn" href="<?php echo $this->webroot?>refillpools/add_card/<?php echo $credit_card_series_id?>"><img width="16" height="16" alt="" src="<?php echo $this->webroot?>images/add.png"> <?php echo __('createnew')?></a></li>
    <li>
    			<a class="link_btn" href="javascript:void(0)" onclick="cover('buckupdate')">
    				<img width="16" height="16"  alt="" src="<?php echo $this->webroot?>images/menuEventsConfig.gif">
    				&nbsp;<?php echo __('buckupdate')?>
    			</a>
    		</li>
    		
    		    <li>
    			<a class="link_btn" href="javascript:void(0)" onclick="cover('effective_card')">
    				<img width="16" height="16"  alt="" src="<?php echo $this->webroot?>images/flag-1.png">
    				&nbsp;<?php echo __('effectrefi')?>
    			</a>
    		</li>
    		<li><a class="link_btn" rel="popup" href="javascript:void(0)" onclick="deleteAll('<?php echo $this->webroot?>refillpools/del_card/all/<?php echo $credit_card_series_id?>');" ><i class="icon-remove"></i> <?php echo __('deleteall')?></a></li>
    <li><a rel="popup" href="javascript:void(0)" onclick="deleteSelected('producttab','<?php echo $this->webroot?>refillpools/del_card/selected/<?php echo $credit_card_series_id?>');"><i class="icon-remove"></i> <?php echo __('deleteselected')?></a></li>
    		
    <li>
    			<a class="link_back" href="<?php echo $this->webroot?>refillpools/pools_list">
    				<img width="16" height="16"  alt="" src="<?php echo $this->webroot?>images/icon_back_white.png">
    				&nbsp;<?php echo __('goback')?>
    			</a>
    		</li>
  </ul>
</div>
<div id="container">

<dl id="buckupdate" class="tooltip-styled" style="display:none;position:absolute;left:40%;top:30%;z-idnex:99;width:300px;height:auto;">
<div style="text-align:center;width:100%;height:25px;font-size: 16px;"><?php echo __('buckupdate',true)?></div>
			<fieldset><legend><span id="ht-100007"><?php echo __('choosebuck',true)?>---<input type="checkbox" onclick="selected_all_cards(this,'buckupdate_cards')"/><?php echo __('allorno')?></span></legend>
    <div id="buckupdate_cards" class="cb_select input" style="text-align:left;height: 100px;">
    <?php
				$mydata =$p->getDataArray();
				$loop = count($mydata); 
				for ($i=0;$i<$loop;$i++) {
					if (empty($mydata[$i][0]['used_date'])) {
			?> 
        <div><input class="input in-checkbox" name="buck_card" value="<?php echo $mydata[$i][0]['credit_card_id']?>" type="checkbox"> <label for="voip_hosts-28"><?php echo $mydata[$i][0]['card_number']?></label></div>
     <?php 
				}}
			?>
    </div>
</fieldset>
	<!--  <div style="margin-top:10px;"><?php echo __('Rates')?>:<select id="rateid" name="rateid" style="float:right">
    			<option value=""><?php echo __('select')?></option>
    					<?php
    								$loop = count($rates);
    								for ($i = 0;$i<$loop;$i++) { 
    						?>
    										<option value="<?php echo $rates[$i][0]['rate_table_id']?>"><?php echo $rates[$i][0]['name']?></option>
    						<?php }?>
    			</select>
	</div>-->
	
	<div style="margin-top:10px;"><?php echo __('cardvalue')?>:
	<input type="text" style="height:20px;float:right;width:165px;" id="buck_card_value"  name="buck_card_value" class="input in-text">
	</div>
	
	
	<div style="margin-top:10px;"><?php echo __('Reseller')?>:<select id="buck_reseller" name="buck_reseller" style="float:right">
    			<option value=""><?php echo __('select')?></option>
    					<?php
    								$loop = count($reseller);
    								for ($i = 0;$i<$loop;$i++) { 
    						?>
    										<option value="<?php echo $reseller[$i][0]['reseller_id']?>"><?php echo $reseller[$i][0]['name']?></option>
    						<?php }?>
    			</select>
	</div>
	
	<div style="margin-top:10px;"><?php echo __('expiredate')?>:
	<input type="text" class="Wdate" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'});" readonly style="height:20px;float:right;width:165px;" id="buck_expire_date"  name="buck_expire_date" class="input in-text">
	</div>
	
	<div style="margin-top:10px;"><?php echo __('iseffectived')?>:<select id="buck_eff" name="buck_eff" style="float:right">
    			<option value=""><?php echo __('select')?></option>
    			<option value="y"><?php echo __('yes')?></option>
    			<option value="n"><?php echo __('no')?></option>
    			</select>
	</div>
	
	<div style="margin-top:10px;"><?php echo __('isused')?>:<select id="buck_used" name="buck_used" style="float:right">
    			<option value=""><?php echo __('select')?></option>
    			<option value="y"><?php echo __('yes')?></option>
    			<option value="n"><?php echo __('no')?></option>
    			</select>
	</div>
	
	<div style="margin-top:10px; margin-left:26%;width:150px;height:auto;">
		<input type="button" onclick="buck_update();" value="<?php echo __('submit')?>" class="input in-button">
		<input type="button" onclick="closeCover('buckupdate');" value="<?php echo __('cancel')?>" class="input in-button">
	</div>
</dl>




<dl id="effective_card" class="tooltip-styled" style="display:none;position:absolute;left:40%;top:30%;z-idnex:99;width:300px;height:auto;">
<div style="text-align:center;width:100%;height:25px;font-size: 16px;"><?php echo __('effective_card')?></div>
			<fieldset><legend><span id="ht-100007"><?php echo __('chooseeffective')?>---<input type="checkbox" onclick="selected_all_cards(this,'eff_cards')"/><?php echo __('allorno')?></span></legend>
    <div id="eff_cards" class="cb_select input" style="text-align:left;height: 100px;">
    <?php
				$mydata =$p->getDataArray();
				$loop = count($mydata); 
				for ($i=0;$i<$loop;$i++) {
					if (empty($mydata[$i][0]['effective_date'])) {
			?> 
        <div><input class="input in-checkbox" name="ef_card" value="<?php echo $mydata[$i][0]['credit_card_id']?>" type="checkbox"> <label for="voip_hosts-28"><?php echo $mydata[$i][0]['card_number']?></label></div>
     <?php 
				}}
			?>
    </div>
    
    <div style="margin-top:5px;">
    			<span><?php echo __('Reseller')?></span>:<select id="eff_reseller" name="eff_reseller" style="float:right">
    					<?php
    								$loop = count($reseller);
    								for ($i = 0;$i<$loop;$i++) { 
    						?>
    										<option value="<?php echo $reseller[$i][0]['reseller_id']?>"><?php echo $reseller[$i][0]['name']?></option>
    						<?php }?>
    			</select>
    </div>
</fieldset>
	<div style="margin-top:10px; margin-left:26%;width:150px;height:auto;">
		<input type="button" onclick="effective('<?php echo $credit_card_series_id?>');" value="<?php echo __('submit')?>" class="input in-button">
		<input type="button" onclick="closeCover('effective_card');" value="<?php echo __('cancel')?>" class="input in-button">
	</div>
</dl>

<dl id="effective_card_one" class="tooltip-styled" style="display:none;position:absolute;left:40%;top:30%;z-idnex:99;width:300px;height:auto;">
			<div style="text-align:center;width:100%;height:25px;font-size: 16px;"><?php echo __('effective_card')?></div>
			<div style="margin-top:5px;">
    			<span><?php echo __('Reseller')?></span>:<select id="eff_reseller_one" name="eff_reseller_one" style="float:right">
    					<?php
    								$loop = count($reseller);
    								for ($i = 0;$i<$loop;$i++) { 
    						?>
    										<option value="<?php echo $reseller[$i][0]['reseller_id']?>"><?php echo $reseller[$i][0]['name']?></option>
    						<?php }?>
    			</select>
    			<input style="display:none" id="card_one"/>
    </div>
    
    <div style="margin-top:10px; margin-left:26%;width:150px;height:auto;">
				<input type="button" onclick="effective_one('<?php echo $credit_card_series_id?>');" value="<?php echo __('submit')?>" class="input in-button">
				<input type="button" onclick="closeCover('effective_card_one');" value="<?php echo __('cancel')?>" class="input in-button">
			</div>
</dl>


<fieldset class="title-block" id="advsearch" style="display: none;width:100%;margin-left:1px;">
<form method="post">
<table>
<tbody><tr>
    <!--  <td>
    			<label style="float:left;padding-top:6px;"><?php echo __('Rates')?>:</label>
    			<select id="rate_id" name="rate_id" style="float:left;width:60px">
    			<option value=""><?php echo __('select')?></option>
    					<?php
    								$loop = count($rates);
    								for ($i = 0;$i<$loop;$i++) { 
    						?>
    										<option value="<?php echo $rates[$i][0]['rate_table_id']?>"><?php echo $rates[$i][0]['name']?></option>
    						<?php }?>
    			</select>
    </td>-->
    
    <td>
    			<label style="float:left;padding-top:6px;"><?php echo __('Reseller')?>:</label>
    			<select id="reseller_id" name="reseller_id" style="float:left;width:60px">
    			<option value=""><?php echo __('select')?></option>
    					<?php
    								$loop = count($reseller);
    								for ($i = 0;$i<$loop;$i++) { 
    						?>
    										<option value="<?php echo $reseller[$i][0]['reseller_id']?>"><?php echo $reseller[$i][0]['name']?></option>
    						<?php }?>
    			</select>
    </td>
    
    <td>
    			<label style="float:left;padding-top:6px;"><?php echo __('cardvalue')."(".__('f',true).")"?>:</label>
    			<input type="text"  style="height:20px;float:left;width:70px;" id="card_value_s"  name="card_value_s" class="input in-text">
    </td>
    <td>
    		<label style="float:left;padding-top:6px;"><?php echo __('cardvalue')."(".__('t',true).")"?>:</label>
    		<input type="text"  style="height:20px;float:left;width:70px;" id="card_value_e"  name="card_value_e" class="input in-text">
    	</td>
    	
    <td>
    			<label style="float:left;padding-top:6px;"><?php echo __('createstart')?>:</label>
    			<input type="text" class="Wdate" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'});" readonly style="height:20px;float:left;width:70px;" id="create_date_s"  name="create_date_s" class="input in-text">
    </td>
    <td>
    		<label style="float:left;padding-top:6px;"><?php echo __('createend')?>:</label>
    		<input type="text" class="Wdate" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'});" readonly style="height:20px;float:left;width:70px;" id="create_date_e"  name="create_date_e" class="input in-text">
    	</td>
    	
    	<td>
    			<label style="float:left;padding-top:6px;"><?php echo __('createstart')?>:</label>
    			<input type="text" class="Wdate" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'});" readonly style="height:20px;float:left;width:70px;" id="effective_date_s"  name="effective_date_s" class="input in-text">
    </td>
    <td>
    		<label style="float:left;padding-top:6px;"><?php echo __('createend')?>:</label>
    		<input type="text" class="Wdate" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'});" readonly style="height:20px;float:left;width:70px;" id="effective_date_e"  name="effective_date_e" class="input in-text">
    	</td>
    	
    	
    	<td>
    			<label style="float:left;padding-top:6px;"><?php echo __('usedstartd')?>:</label>
    			<input type="text" class="Wdate" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'});" readonly style="height:20px;float:left;width:70px;" id="used_date_s"  name="used_date_s" class="input in-text">
    </td>
    <td>
    		<label style="float:left;padding-top:6px;"><?php echo __('usedendd')?>:</label>
    		<input type="text" class="Wdate" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'});" readonly style="height:20px;float:left;width:70px;" id="used_date_e"  name="used_date_e" class="input in-text">
    	</td>
    	
    	<td>
    		<label style="float:left;padding-top:6px;"><?php echo __('iseffectived')?>:</label>
    		<select id="iseff" name="iseff" style="width:60px;float:left;">	
    					<option value="" selected><?php echo __('select')?></option>
    				<option value="y"><?php echo __('yes')?></option>
    				<option value="n"><?php echo __('no')?></option>
    		</select>
    	</td>
    <td class="buttons"><input type="submit" value="<?php echo __('search')?>" class="input in-submit"></td>
</tr>
</tbody></table>
</form>
</fieldset>

<div id="toppage"></div>
		<?php $d = $p->getDataArray();if (count($d) == 0) {?>
<div class="msg"><?php echo __('no_data_found')?></div>
<?php } else {?>
<table class="list">
<col style="width: 4%;">
	<col style="width: 6%;">
	<col style="width: 10%;">
	<col style="width: 10%;">
	<col style="width: 10%;">
	<col style="width: 10%;">
<!--  	<col style="width: 7%;">
	<col style="width: 7%;">
	<col style="width: 7%;">-->
	<col style="width: 15%;">
	<col style="width: 8%;">
	<col style="width: 8%;">
	<col style="width: 10%;">
	<thead>
		<tr>
		<td><input type="checkbox" onclick="checkAllOrNot(this,'producttab');" value=""/></td>
    <td><a href="javascript:void(0)" onclick="my_sort('credit_card_id','asc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<?php echo __('id',true);?>&nbsp;<a href="javascript:void(0)" onclick="my_sort('credit_card_id','desc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
    <td><a href="javascript:void(0)" onclick="my_sort('card_number','asc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<?php echo __('cardnumber')?>&nbsp;<a href="javascript:void(0)" onclick="my_sort('card_number','desc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
    <td><a href="javascript:void(0)" onclick="my_sort('pin','asc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<?php echo __('pin')?>&nbsp;<a href="javascript:void(0)" onclick="my_sort('pin','desc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
    <td><a href="javascript:void(0)" onclick="my_sort('series','asc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<?php echo __('refillpollname')?>&nbsp;<a href="javascript:void(0)" onclick="my_sort('series','desc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
    <td><a href="javascript:void(0)" onclick="my_sort('value','asc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<?php echo __('cardvalue')?>&nbsp;<a href="javascript:void(0)" onclick="my_sort('value','desc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
    <td><a href="javascript:void(0)" onclick="my_sort('used_date','asc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<?php echo __('useddate')?>&nbsp;<a href="javascript:void(0)" onclick="my_sort('used_date','desc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
    <td><a href="javascript:void(0)" onclick="my_sort('reseller','asc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<?php echo __('Reseller')?>&nbsp;<a href="javascript:void(0)" onclick="my_sort('reseller','desc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
    <td><?php echo __('status')?></td>
    <td class="last"><?php echo __('action')?></td>
		</tr>
	</thead>
	<tbody id="producttab">
		<?php 
			$mydata =$p->getDataArray();
			$loop = count($mydata); 
			for ($i=0;$i<$loop;$i++) {?>
				<tr class="row-1" rel="tooltip" id="i_<?php echo $loop-$i?>">
				<td style="text-align:center"><input type="checkbox" value="<?php echo $mydata[$i][0]['credit_card_id']?>"/></td>
		    <td><?php echo $mydata[$i][0]['credit_card_id']?></td>
		    <td style="font-weight: bold;"><?php echo $mydata[$i][0]['card_number']?></td>
		    <td><?php echo $mydata[$i][0]['pin']?></td>
		    <td><?php echo $mydata[$i][0]['series']?></td>
		    <td><?php echo $mydata[$i][0]['value']?></td>
		    <td><?php echo $mydata[$i][0]['used_date']?></td>
		    <td><?php echo $mydata[$i][0]['reseller']?></td>
		    <td><?php 
		    				if (!empty($mydata[$i][0]['used_date']))echo __('used',true);
		    				else if (!empty($mydata[$i][0]['effective_date'])&&(!empty($mydata[$i][0]['expire_date']))&&strtotime($mydata[$i][0]['expire_date']) < time()+6*060*60)echo __('expired',true);
		    				else if (empty($mydata[$i][0]['effective_date']) &&( strtotime($mydata[$i][0]['expire_date']) > time()+6*060*60||empty($mydata[$i][0]['expire_date']))){
		    					 echo __('noteffectived',true);
		    					 echo "&nbsp;&nbsp;&nbsp;<a href='javascript:void(0)' onclick='document.getElementById(\"card_one\").value=".$mydata[$i][0]['credit_card_id'].";cover(\"effective_card_one\")'>".__('activeed',true)."</a>";
		    				}
		    				else echo __('effectived',true);
		    				?>
		    </td>
		    <td >
		    		<a title="<?php echo __('edit')?>" style="float:left;margin-left:35px;" href="<?php echo $this->webroot?>refillpools/edit_card/<?php echo $mydata[$i][0]['credit_card_id']?>">
		    			<i class="icon-edit"></i>
		    		</a>
		    		<a title="<?php echo __('del')?>" style="float:left;margin-left:10px;" href="javascript:void(0)" onclick="delConfirm(this,'<?php echo $this->webroot?>refillpools/del_card/<?php echo $mydata[$i][0]['credit_card_id']?>/<?php echo $credit_card_series_id?>');">
		    			<i class="icon-remove"></i>
		    		</a>
		    </td>
				</tr>
				
				<dl id="i_<?php echo $loop-$i?>-tooltip" class="tooltip">
    <dl><?php echo  __('createdate')?>:</dl>
    <dd><?php echo $mydata[$i][0]['create_date']?></dd>
    
    <dl><?php echo __('expiredate')?>:</dl>
    <dd><?php echo $mydata[$i][0]['expire_date']?></dd>
    
    <dl><?php echo __('effectivedate')?>:</dl>
    <dd><?php echo $mydata[$i][0]['effective_date']?></dd>
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
						<script>document.getElementById("<?php echo $k?>").value = "<?php echo $searchform[$k]?>";</script>
<?php }?>
				<script>document.getElementById("advsearch").style.display="block";</script>
<?php }?>