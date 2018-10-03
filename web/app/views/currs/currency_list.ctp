    <script type="text/javascript">
    	//<![CDATA[
		//启用币率
		function active(obj,currency_id){
				jQuery.get("<?php echo $this->webroot?>currs/active_or_not?type=1&r_id="+currency_id,function(data){
					if (data.trim() == 'true') {
						obj.getElementsByTagName('img')[0].src = "<?php echo $this->webroot?>images/flag-1.png";
						obj.title = "<?php echo __('disable')?>";
						obj.onclick = function(){inactive(this,currency_id);};
						jGrowl_to_notyfy("<?php echo __('activesuccess')?>",{theme:'jmsg-success'});
					} else {
						jGrowl_to_notyfy("<?php echo __('activefailed')?>",{theme:'jmsg-alert'});
					}
				});
		}
		//禁用币率
		function inactive(obj,currency_id){
				jQuery.get("<?php echo $this->webroot?>currs/active_or_not?type=2&r_id="+currency_id,function(data){
					if (data.trim() == 'true') {
						obj.getElementsByTagName('img')[0].src = "<?php echo $this->webroot?>images/flag-0.png";
						obj.title = "<?php echo __('active')?>";
						obj.onclick = function(){active(this,currency_id);};
						jGrowl_to_notyfy("<?php echo __('inactivesuccess')?>",{theme:'jmsg-success'});
					} else {
						jGrowl_to_notyfy("<?php echo __('inactivefailed')?>",{theme:'jmsg-alert'});
					}
				});
		}

		function add_currency(){
			var c_code = document.getElementById("c_code").value;
			var c_active = document.getElementById("y");
			if (!c_active.checked) c_active = document.getElementById("n");

			jQuery.get("<?php echo $this->webroot?>currs/check_currency?code="+c_code,function(data){
				if (data.trim() == 'true') {
						location = "<?php echo $this->webroot?>currs/add_currency?code="+c_code+"&active="+c_active.value;
				}
				else 
						jGrowl_to_notyfy(data,{theme:'jmsg-alert'});
			});
		}

		function edit_currency(code,active,currency_id){
			cover('editcurrency');
			document.getElementById('e_code').value=code;
			if(active)document.getElementById("ey").checked = true;
			else document.getElementById("en").checked = true;
			document.getElementById("hidden_currenccy_id").value = currency_id;
		}

		function update_currency(){
			var c_code = document.getElementById("e_code").value;
			var c_active = document.getElementById("ey");
			if (!c_active.checked) c_active = document.getElementById("en");


			var curr_id = document.getElementById("hidden_currenccy_id").value;
			jQuery.get("<?php echo $this->webroot?>currs/check_currency?curr_id="+curr_id+"&code="+c_code,function(data){
				if (data.trim() == 'true') 
						location = "<?php echo $this->webroot?>currs/edit_currency?code="+c_code+"&active="+c_active.value+"&id="+curr_id;
				else 
						jGrowl_to_notyfy(data,{theme:'jmsg-alert'});
			});
		}
		function show_update(id){
			var url = "<?php echo $this->webroot?>currs/currency_update/"+id;
			jQuery.get(url,
					function(data){jQuery(document).xshow({width:'600px'}).append(data);}
			);
		
			//loadPage(url,900,400);
		} 
    </script>
<div id="cover"></div>
<div style="display:none;position: absolute; left: 40%; top: 30%; width: 300px; height: 120px; z-index: 99;" id="addcurrency">
<dl  class="tooltip-styled" style="width:100%;height: 100%">
<dd style="text-align: center; width: 100%; height: 25px; font-size: 16px;">
      <?php echo __('addcurrencyrate')?>
</dd>
	<dd style="margin-top: 10px;"><?php echo __('currencycode')?>:<input id="c_code" class="input in-text">
	</dd>
	
	<dd style="margin-top: 10px;"><?php echo __('active')?>:<span style="margin-left:23px; width: auto; display: inline;"><input type="radio" id="y" value="y" checked name="c_active"><?php echo __('yes')?><input type="radio" id="n" value="n" name="c_active"><?php echo __('no')?></span>
	</dd>
	
	<dd style="margin-top: 10px; margin-left: 23%;  height: auto;">
		<input type="button" style="width:60px; " class="input in-submit" value="<?php echo __('submit')?>" onclick="add_currency();">
		<input type="button" stytle="width:60px;" class="input in-submit" value="<?php echo __('cancel')?>" onclick="closeCover('addcurrency');">
	</dd>
</dl>
</div>
<div id="cover_tmp"></div>
<?php $w = $session->read('writable');?>
<div id="title">
  <h1>
    <span><?php __('System')?></span>
    	<?php echo __('currencyrate')?>
   <!--  <a title="add to smartbar" href="/admin/_view/sbAdd?link=/rate_tables/list">
   	<img width="10" height="10" alt="+" src="<?php echo $this->webroot?>images/qb-plus.png" />
   </a>-->
  </h1>
  <ul id="title-search">
    <li>
	    	<form>
	    		<input type="text" id="search-_q" class="in-search default-value input in-text defaultText" title="<?php echo __('namesearch')?>" value="<?php if (!empty($search)) echo $search;?>" name="search">
	    	</form>
    </li>
    <!--  <li title="advanced search »" onclick="advSearchToggle();" id="title-search-adv" style="display: list-item;"></li>-->
  </ul>
    
  <ul id="title-menu">
  <?php if (isset($edit_return)) {?>
        <li>
    			<a class="link_back" href="<?php echo $this->webroot?>currs/currency_list">
    				<img width="16" height="16"  alt="" src="<?php echo $this->webroot?>images/icon_back_white.png">
    				&nbsp;<?php echo __('gobackall')?>
    			</a>
    		</li>
        <?php }?>
    <?php if ($w == true) {?><li><a class="link_btn" href="javascript:void(0)" onclick="cover('addcurrency')"><img width="16" height="16" alt="" src="<?php echo $this->webroot?>images/add.png"> <?php echo __('createnew')?></a></li><?php }?>
    <li><a class="link_btn" rel="popup" href="javascript:void(0)" onclick="deleteAll('<?php echo $this->webroot?>currs/del_currency/all');" ><i class="icon-remove"></i> <?php echo __('deleteall')?></a></li>
    <li><a class="link_btn"rel="popup" href="javascript:void(0)" onclick="deleteSelected('producttab','<?php echo $this->webroot?>currs/del_currency/selected');"><i class="icon-remove"></i> <?php echo __('deleteselected')?></a></li>
  </ul>
</div>
<div id="container">
<div id="toppage"></div>
<dl style="display:none;position: absolute; left: 40%; top: 30%; width: 300px; height: 120px; z-index: 99;" class="tooltip-styled" id="editcurrency">
<dd style="text-align: center; width: 100%; height: 25px; font-size: 16px;"><?php echo __('addcurrencyrate')?></dd>
	<dd style="margin-top: 10px;"><?php echo __('currencycode')?>:<input id="e_code" class="input in-text" maxLength=6>
	</dd>
	<dd><input style="display:none" id="hidden_currenccy_id"/></dd>
	<dd style="margin-top: 10px;"><?php echo __('active')?>:<span style="margin-left:23px; display:inline;width:auto;"><input type="radio" id="ey" value="y"  checked name="c_active" ><?php echo __('yes')?><input type="radio" id="en" value="n" name="c_active"><?php echo __('no')?></span>
	</dd>
	
	<dd style="margin-top: 10px; margin-left: 23%; width: 160px; height: auto;">
		<input type="button" style="width:50px; display:inline;" class="input in-submit" value="<?php echo __('submit')?>" onclick="update_currency();" />
		<input type="button" style="width:50px; display:inline;"class="input in-submit" value="<?php echo __('cancel')?>" onclick="closeCover('editcurrency');" />
	</dd>
</dl>
<div>
<table class="list">
<col style="width: 4%;">
	<col style="width: 6%;">
	<col style="width: 11%;">
	<col style="width: 10%;">
	<col style="width: 14%;">
	<col style="width: 14%;">
	<col style="width: 10%;">
	<col style="width: 10%;">
	<col style="width: 13%;">
	<thead> 
		<tr>
		<td><input type="checkbox" onclick="checkAllOrNot(this,'producttab');" value=""/></td>
    <td>
        <?php echo $appCommon->show_order('currency_id',__('ID',true));?>
    </td>
    <td>
        <?php echo $appCommon->show_order('code',__('code',true));?>
    </td>
    <td>
        <?php echo $appCommon->show_order('rate',__('Rates',true));?>
    </td>
    <td>
        <?php echo $appCommon->show_order('last_modify',__('updateat',true));?>
    </td>
    <td>
       <?php echo $appCommon->show_order('usage', __('usage',true));?>
    </td>
    <td>
      <?php echo __('ofrates',true);?>
    </td>
    <td>
       <?php echo __('active');?>
    </td>
    <td class="last"><?php echo __('action')?></td>
		</tr>
	</thead>
	<tbody id="producttab">
		<?php 
			$mydata =$p->getDataArray();
			$loop = count($mydata); 
			for ($i=0;$i<$loop;$i++) {?>
				<tr class="row-1">
				<td style="text-align:center"><input type="checkbox" value="<?php echo $mydata[$i][0]['currency_id']?>"/></td>
		    <td><?php echo $mydata[$i][0]['currency_id']?></td>
		    <td style="font-weight: bold;"><?php echo $mydata[$i][0]['code']?></td>
		    <td style="color:green"><?php echo $mydata[$i][0]['rate']?></td>
		    <td><?php if (!empty($mydata[$i][0]['last_modify'])) echo date('Y-m-d H:i:s',strtotime($mydata[$i][0]['last_modify'])+6*60*60)?></td>
		    <td style="color:red"><?php echo $mydata[$i][0]['usage']?></td>
		    <td><a href="<?php echo $this->webroot?>rates/rates_list/<?php echo $mydata[$i][0]['currency_id']?>/currs/currency_list" style="width:100%;"><img style="float:left;margin-left:45px;" src="<?php echo $this->webroot?>images/bOrigTariffs.gif"/><span style="float:left"><?php echo $mydata[$i][0]['rates']?></span></a></td>
		    <td><?php if ($w == true) {?><?php if ($mydata[$i][0]['active'] == true) {?>
			    			<a title="<?php echo __('disable')?>"   href="javascript:void(0)" onclick="inactive(this,'<?php echo $mydata[$i][0]['currency_id']?>');">
			    			<img src="<?php echo $this->webroot?>images/flag-1.png" />
			    				
			    			</a>
		    			<?php } else {?>
		    				<a title="<?php echo __('active')?>"   href="javascript:void(0)" onclick="active(this,'<?php echo $mydata[$i][0]['currency_id']?>');">
			    				<img src="<?php echo $this->webroot?>images/flag-0.png" />
			    			</a>
		    			<?php }}?></td>
		    <td>
		    		<a title="<?php echo __('viewhis')?>" style="float:left;margin-left:20px;" href="javascript:void(0)" onclick="show_update('<?php echo $mydata[$i][0]['currency_id']?>');">
		    			<img src="<?php echo $this->webroot?>images/bRates.gif" />
		    		</a>
		    		<?php if ($w == true) {?><a title="<?php echo __('edit')?>" style="float:left;margin-left:10px;" href="javascript:void(0)" onclick="edit_currency('<?php echo $mydata[$i][0]['code']?>','<?php echo $mydata[$i][0]['active']?>','<?php echo $mydata[$i][0]['currency_id']?>');">
		    			<i class="icon-edit"></i>
		    		</a>
		    		<a  title="<?php echo __('del')?>" style="float:left;margin-left:10px;" href="javascript:void(0)" onclick="delConfirm(this,'<?php echo $this->webroot?>currs/del_currency/<?php echo $mydata[$i][0]['currency_id']?>');">
		    			<i class="icon-remove"></i>
		    		</a><?php }?>
		    </td>
				</tr>
		<?php }?>		
</tbody>
</table>
</div>
<div id="tmppage">
<?php echo $this->element('page');?>
</div>
<?php $d = $p->getDataArray();if (count($d) == 0) {?>
<div class="msg"><?php echo __('no_data_found')?></div>
<?php }?>
</div>
<div id="livemargins_control" style="position: absolute; display: none; z-index: 9999;"><img width="77" height="5" style="position: absolute; left: -77px; top: -5px;" src="chrome://livemargins/skin/monitor-background-horizontal.png">	<img style="position: absolute; left: 0pt; top: -5px;" src="chrome://livemargins/skin/monitor-background-vertical.png">	<img style="position: absolute; left: 1px; top: 0pt; opacity: 0.5; cursor: pointer;" onmouseout="this.style.opacity=0.5" onmouseover="this.style.opacity=1" src="chrome://livemargins/skin/monitor-play-button.png" id="monitor-play-button"></div>