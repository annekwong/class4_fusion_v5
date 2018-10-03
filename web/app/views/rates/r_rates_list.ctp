
    <style type="text/css">
				.width90{width:60px;}
				.width110{width:90px;}
				.height16{height:15px;}
				.width100{width:100px;}
				.width120{width:135px;}
				.textRight{text-align:right;}
				.marginTop9{margin-top:9px;};
			</style>
			<script type="text/javascript" src="<?php echo $this->webroot?>js/My97DatePicker/WdatePicker.js"></script>
			<script language="JavaScript" type="text/javascript">
					function add_rate(){
						var t = getMessage('times');
						var tmp_id = getMessage('tmp_id');
						var now_time = getMessage('now_time');
						var columns = [
	    		        			   {hidden:true},
	    		        			   {hidden:true},
	    		        			   {tag:'input',hidden:true,innerHTML:"<input class='marginTop9 textRight height16 width90 in-text'/><a href='javascript:void(0)' onclick='chooseCode(this);' style='width:20px;'><img src='<?php echo $this->webroot?>images/search-small.png'/></a>"},
	    		        			   {tag:'input',hidden:true,innerHTML:'<?php echo $code_deck?>' > 0?"<input class='marginTop9 textRight height16 width90 in-text'/>":"<input class='marginTop9 textRight height16 width90 in-text' /><a href='javascript:void(0)' onclick='chooseCode(this);' ><img src='<?php echo $this->webroot?>images/search-small.png'/></a>"},
	    		        			   {tag:'input',defaultV:'0.000',className:'marginTop9 textRight height16 width90 in-text'},
	    		        			   {tag:'input',defaultV:'0.000',className:'marginTop9 textRight height16 width90 in-text'},
	    		        			   {tag:'input',defaultV:now_time,className:'marginTop9 wdate width110 in-text',ownevents:{onfocus:function(){WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'});}}},
	    		        			   {tag:'input',className:'marginTop9 wdate width110 in-text',ownevents:{onfocus:function(){WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'});}}},
	    		        			   {tag:'input',defaultV:'0',className:'marginTop9 textRight height16 width90 in-text'},
	    		        			   {tag:'input',defaultV:'1',className:'marginTop9 textRight height16 width90 in-text'},
	    		        			   
	    		        			   {tag:'select',className:'marginTop9  width90 in-select',options:eval(t)},

	    		        			   
	    		        			   {tag:'input',defaultV:'60',className:'marginTop9 textRight height16 width90 in-text'},
	    		        			   {tag:'input',defaultV:'0',className:'marginTop9 textRight height16 width90 in-text'},
	    		        			   {tag:'input',defaultV:'0.000',className:'marginTop9 textRight height16 width90 in-text'},
	    		        			   {tag:'input',defaultV:'0.000',className:'marginTop9 textRight height16 width90 in-text'},
	    		        			   {tag:'a',innerHTML:"<a href='javascript:void(0)' onclick='save_rate(this.parentNode.parentNode,"+tmp_id+");'><img src='<?php echo $this->webroot?>images/menuIcon_004.gif' /></a><a style='margin-left:10px;' href='javascript:void(0)' onclick='this.parentNode.parentNode.parentNode.removeChild(this.parentNode.parentNode)'><i class='icon-remove'></i></a>"}
	    		   		       								];
	    		  createRow('ratetab',columns);
					}

					function show_changed_rate(e,obj,selected){
						var srcO;
						if (e != null){
							var even = e || window.event;
							even.target||even.srcElement;
						} else {
							srcO = obj;
						}
						
						var expire_rate = document.getElementById("expire_rate");
						expire_rate.style.left = srcO.parentNode.offsetLeft+"px";
						expire_rate.style.top = (getTop(srcO)-30)+"px";

						if (selected) expire_rate.value = selected;

						if (srcO.value.length > 0)
							$(expire_rate).show(500);
						else 
							$(expire_rate).hide(500);
					}

					function getTop(e){  
						 var offset=e.offsetTop;  
						 if(e.offsetParent!=null) offset+=getTop(e.offsetParent);  
						 	return offset;  
					}  


					/*
					*验证数据格式
					*/
					function validate_rate(data){
						var amount_reg = /^[0-9]+(\.[0-9]{1,9})?$/;
						var integer_reg = /^[+]?[0-9]*\.?[0-9]+$/;
						var has_error = false;
						var error_msg = [];
					//	if (!data.code){has_error = true;error_msg.push(getMessage('entercode'));}
						
						if (!data.rate){has_error = true;error_msg.push(getMessage('enterrate'))}
						else if (!amount_reg.test(data.rate)){has_error = true;error_msg.push(getMessage('rateformat'))}

						if (data.rate){
         if(!amount_reg.test(data.rate)){
      		  has_error = true;error_msg.push('Please fill Rate field correctly (only  digits allowed).  ');
	
                           }
					

							}
						
						if (!data.setup_fee){has_error = true;error_msg.push(getMessage('entersetupfee'))}
						else if (!amount_reg.test(data.setup_fee)){has_error = true;error_msg.push(getMessage('setupfeeformat'))}

						if (!data.effective_date){has_error = true;error_msg.push(getMessage('chooseeffective'))}

						if (!data.min_date){has_error = true;error_msg.push(getMessage('entermintime'))}
						else if (!integer_reg.test(data.min_date)){has_error = true;error_msg.push(getMessage('mintimeformat'))}

						if (!data.interval){has_error = true;error_msg.push(getMessage('intervalnull'))}
						else if (isNaN(data.interval)){has_error = true;error_msg.push(getMessage('intervalformat'))}
						else if (data.interval <= 0){has_error = true;error_msg.push(getMessage('intervalzero'));}

						if (!data.seconds){has_error = true;error_msg.push(getMessage('timenull'))}
						else if (isNaN(data.seconds)){has_error = true;error_msg.push(getMessage('timeformat'))}
						else if (data.seconds <= 0){has_error = true;error_msg.push(getMessage('secondszero'));}

						if (!data.grace_time){has_error = true;error_msg.push(getMessage('gracetimenull'))}
						else if (!integer_reg.test(data.grace_time)){has_error = true;error_msg.push(getMessage('gracetimeformat'))}



						 
			
						
						//循环打出提示信息
						if (has_error == true)
							for(var i = 0;i<error_msg.length;i++)
								jGrowl_to_notyfy(error_msg[i],{theme:'jmsg-alert'});
						
						return has_error;	
					}

					/*
					*保存费率
					*/
					function save_rate(tr,tmp_id){
						var code = tr.cells[2].getElementsByTagName('input')[0].value;
						var codename = tr.cells[3].getElementsByTagName('input')[0].value;
						var rate = tr.cells[4].getElementsByTagName('input')[0].value;
						var setup_fee = tr.cells[5].getElementsByTagName('input')[0].value;
						var effective_date = tr.cells[6].getElementsByTagName('input')[0].value;
						var end_date = tr.cells[7].getElementsByTagName('input')[0].value;
						var min_date = tr.cells[8].getElementsByTagName('input')[0].value;
						var interval = tr.cells[9].getElementsByTagName('input')[0].value;
						var time_profile = tr.cells[10].getElementsByTagName('select')[0].value;
						var seconds = tr.cells[11].getElementsByTagName('input')[0].value;
						var grace_time = tr.cells[12].getElementsByTagName('input')[0].value;
						var intra_rate = tr.cells[13].getElementsByTagName('input')[0].value;
						var inter_rate = tr.cells[14].getElementsByTagName('input')[0].value;
						
						//var expire_rate = document.getElementById("expire_rate").value;
						
						var params = {
								code:code,
								codename:codename,
								rate:rate,
								setup_fee:setup_fee,
								effective_date:effective_date,
								end_date:end_date,
								min_date:min_date,
								interval:interval,
								time_profile:time_profile,
								seconds:seconds,
								tmp_id:tmp_id,
								grace_time:grace_time,
								intra_rate:intra_rate,
								inter_rate:inter_rate
							
								//expire_rate :expire_rate
						};

						if (validate_rate(params,true))return;

						jQuery.post('<?php echo $this->webroot?>rates/add_rate',params,function(data){
							var p = {theme:'jmsg-success',beforeClose:function(){location.reload();},life:100};
		       var  tmp = data.split("|");
		       if (tmp[1].trim() == 'false') p = {theme:'jmsg-alert',life:100};
		       jGrowl_to_notyfy(tmp[0],p);
						});
					}

					var td;
					var td1;
					function chooseCode(source){
						td = source.parentNode.parentNode.cells[2].getElementsByTagName('input')[0];
						td1 = source.parentNode.parentNode.cells[3].getElementsByTagName('input')[0];
						var code_deck = getMessage('codedeck');
						if (!code_deck)
							jGrowl_to_notyfy(getMessage('nocodedeck'),{theme:'jmsg-alert'});
						else {
							cover('cover_tmp');
							loadPage('<?php echo $this->webroot?>rates/choose_codes/'+code_deck,500,400);
						}
					}

					function edit_rate(tr){
					
						var inp = document.createElement('input');
						inp.className="textRight height16 width90 in-text";
						var code = tr.cells[2];
					
						var od = code.innerHTML.trim();

						code.innerHTML = "<input class='height16 width90 in-text' style='float:left'  value='"+od+"'/><a href='javascript:void(0)' onclick='chooseCode(this);' style='padding-top:5px;width:10px;float:left;'><img src='<?php echo $this->webroot?>images/search-small.png'/></a>";


						var codename = tr.cells[3];
						var odd = codename.innerHTML.trim();
						if ("<?php echo $code_deck?>">0){
							codename.innerHTML = "<input value='"+odd+"' class='height16 width90 in-text' style='float:left'/><a href='javascript:void(0)' onclick='chooseCode(this);' style='padding-top:5px;width:10px;float:left;'><img src='<?php echo $this->webroot?>images/search-small.png'/></a>";
						} else {
							codename.innerHTML = "<input value='"+odd+"' class='height16 width90 in-text' style='float:left'/>";
						}
						
						var rate = tr.cells[4];
						var rate_inp = inp.cloneNode(true);
						rate_inp.value = rate.innerHTML.trim();
						rate.innerHTML = "";
						rate.appendChild(rate_inp);

						var setup_fee_inp = inp.cloneNode(true);
						var setup_fee = tr.cells[5];
						setup_fee_inp.value = setup_fee.innerHTML.trim();
						setup_fee.innerHTML = "";
						setup_fee.appendChild(setup_fee_inp);


						var effective_inp = inp.cloneNode(true);
						effective_inp.className = " wdate width110 in-text";
						effective_inp.readOnly = true;
						effective_inp.onfocus = function(){WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'});};
						var effective_date = tr.cells[6];
						effective_inp.value = effective_date.innerHTML.trim();
						effective_date.innerHTML = "";
						effective_date.appendChild(effective_inp);


						var end_inp = effective_inp.cloneNode(true);
						var end_date = tr.cells[7];
						end_inp.onfocus = function(){WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'});};
						end_inp.value = end_date.innerHTML.trim();
						end_date.innerHTML = "";
						end_date.appendChild(end_inp);
						

						var min_inp = inp.cloneNode(true);
						var min_date = tr.cells[8];
						min_inp.value = min_date.innerHTML.trim();
						min_date.innerHTML = "";
						min_date.appendChild(min_inp);

						var interval_inp = inp.cloneNode(true);
						var interval = tr.cells[9];
						interval_inp.value = interval.innerHTML.trim();
						interval.innerHTML = "";
						interval.appendChild(interval_inp);

						var s = document.createElement('select');
						var sd = eval(getMessage('times'));
						var l = sd.length;
						var time_profile = tr.cells[10];
						var op = document.createElement('option');
						for (var i = 0;i<l;i++) {
							nop = op.cloneNode(true);
							nop.value = sd[i][0].time_profile_id;
							nop.innerHTML = sd[i][0].name;
							if (sd[i][0].time_profile_id == tr.cells[10].innerHTML.trim()){
								nop.selected = true;
							}
							s.appendChild(nop);
						}

		
						s.className = " height16 width90 in-select";
						time_profile.innerHTML = "";
						time_profile.appendChild(s);
						
						var seconds_inp = inp.cloneNode(true);
						var seconds = tr.cells[11];
						seconds_inp.value = seconds.innerHTML.trim();
						seconds.innerHTML = "";
						seconds.appendChild(seconds_inp);

						var grace_inp = inp.cloneNode(true);
						var grace_time = tr.cells[12];
						grace_inp.value = grace_time.innerHTML.trim();
						grace_time.innerHTML = "";
						grace_time.appendChild(grace_inp);


						//费率类型
						var intra_rate_inp = inp.cloneNode(true);
						var intra_rate = tr.cells[13];
						intra_rate_inp.value = intra_rate.innerHTML.trim();
						intra_rate.innerHTML = "";
						intra_rate.appendChild(intra_rate_inp);

	
						var inter_rate_inp = inp.cloneNode(true);
						var inter_rate = tr.cells[14];
						inter_rate_inp.value = inter_rate.innerHTML.trim();
						inter_rate.innerHTML = "";
						inter_rate.appendChild(inter_rate_inp);

						
						tr.cells[15].getElementsByTagName("img")[0].src="<?php echo $this->webroot?>images/menuIcon_004.gif";
				   tr.cells[15].getElementsByTagName("a")[0].onclick = function(){
					   var params = {
								code:code.getElementsByTagName("input")[0].value,
								rate:rate_inp.value,
								codename:codename.getElementsByTagName('input')[0].value,
								setup_fee:setup_fee_inp.value,
								effective_date:effective_inp.value,
								end_date:end_inp.value,
								min_date:min_inp.value,
								interval:interval_inp.value,
								time_profile:s.value,
								seconds:seconds_inp.value,
								grace_time:grace_inp.value,
								intra_rate:intra_rate_inp.value,
								inter_rate:inter_rate_inp.value,
							
								id:tr.cells[1].innerHTML.trim()
							};
							if (validate_rate(params))return;

							jQuery.post('<?php echo $this->webroot?>rates/update_rate',params,function(data){
								var p = {theme:'jmsg-success',beforeClose:function(){location.reload();},life:100};
			       var  tmp = data.split("|");
			       if (tmp[1].trim() == 'false') p = {theme:'jmsg-alert',life:100};
			       jGrowl_to_notyfy(tmp[0],p);
							});
				        }
					}

					function generate(code_deck,tab_id){
						var params = {
								code:'123',
								rate:document.getElementById('gen_rate').value,
								setup_fee:document.getElementById('gen_setup_fee').value,
								effective_date:document.getElementById('gen_effective_date').value,
								end_date:document.getElementById('gen_end_date').value,
								min_date:document.getElementById('gen_min_time').value,
								interval:document.getElementById('gen_interval').value,
								time_profile:document.getElementById('gen_time_profile').value,
								seconds:document.getElementById('gen_seconds').value,
								code_deck:code_deck,
								tmp_id:tab_id,
								grace_time:document.getElementById('gen_grace_time').value
						};

						if (validate_rate(params))return;

						jQuery.post('<?php echo $this->webroot?>rates/generate_by_codedeck',params,function(data){
							var p = {theme:'jmsg-success',beforeClose:function(){location.reload();},life:100};
						  var  tmp = data.split("|");
						  if (tmp[1].trim() == 'false') p = {theme:'jmsg-alert',life:100};
						  jGrowl_to_notyfy(tmp[0],p);
						});
					}

					function simulatebilling(tab_id){
						var tab = document.getElementById('simu_result');
						for(var i=tab.rows.length-1;i>=0;i--){
							tab.deleteRow(i);
						}
						if (!tab_id) {
							jGrowl_to_notyfy(getMessage('nocodedeck'),{theme:'jmsg-alert'});
							return;
						}
						var simu_date = document.getElementById("simu_date").value;
						var simu_num = document.getElementById("simu_num").value;
						var simu_durations = document.getElementById("simu_durations").value;
						if (!simu_num){return};
						if (!simu_durations)return;
						if (!/^[-+]?[0-9]*\.?[0-9]+$/.test(simu_durations))return;

						var params = {
								date:simu_date,
								number:simu_num,
								durations:simu_durations,
								tab_id:tab_id
						};						

						jGrowl_to_notyfy(getMessage('calculating'),{theme:'jmsg-success',life:100});
						jQuery.post('<?php echo $this->webroot?>rates/simulated',params,function(data){
							if (data.trim() == "{}")
								jGrowl_to_notyfy(getMessage('nocodeorinactive'),{theme:'jmsg-alert'});
							else {
								var result = eval("("+data.trim()+")");
								var tr = document.createElement("tr");
								tr.className = 'row-2';
								for (var i=1;i<=4;i++) {
									var cel = document.createElement("td");
									cel.style.textAlign="center";
									switch(i){
										case 1:
											cel.innerHTML = result.code;
											break;
										case 2:
											cel.innerHTML = "<span style='color:green'>"+result.cost+"</span>";
											break;
										case 3:
											cel.innerHTML = "<span style='color:green'>"+result.rate+"</span>";
											break;
										case 4:
											cel.innerHTML = simu_durations;
											break;
									}
									tr.appendChild(cel);
								}

								tab.appendChild(tr);
							}
						});
					}

					/*
					*错误提示消息
					*/
					function getMessage(key){
						var msg = {
									entercode:"<?php echo __('entercode')?>",
									enterrate:"<?php echo __('enterrate')?>",
									rateformat:"<?php echo __('rateformat')?>",
									entersetupfee:"<?php echo __('entersetupfee')?>",
									setupfeeformat:"<?php echo __('setupfeeformat')?>",
									chooseeffective:"<?php echo __('chooseeffective')?>",
									entermintime:"<?php echo __('entermintime')?>",
									mintimeformat:"<?php echo __('mintimeformat')?>",
									gracetimenull:"<?php echo __('gracetimenull')?>",
									gift_percentagesnull:"<?php echo __('gift_percentagesnull')?>",
									basic_percentagesnull:"<?php echo __('basic_percentagesnull')?>",
									basic_percentagesformat:"<?php echo __('basic_percentagesformat')?>",
									gift_percentagesformat:"<?php echo __('gift_percentagesformat')?>",
									gracetimeformat:"<?php echo __('gracetimeformat')?>",
									basic_gift_format:"<?php echo __('basic_gift_format')?>",
									timenull:"<?php echo __('timenull')?>",
									timeformat:"<?php echo __('timeformat')?>",
									nocodedeck:"<?php echo __('nocodedeck')?>",
									intervalnull:"<?php echo __('intervalnull')?>",
									intervalformat:"<?php echo __('intervalformat')?>",
									calculating:"<?php echo __('calculating')?>",
									nocodeorinactive:"<?php echo __('nocodeorinactive')?>",
									times:"<?php echo $times?>",
									tmp_id:"<?php echo $table_id?>",
									now_time : "<?php echo $now ?>",
									intervalzero:"<?php echo __('intervalzero')?>",
									secondszero:"<?php echo __('secondszero')?>",
									codedeck:"<?php echo $code_deck?>"
						};
						return msg[key];
					}
			</script>

<div id="cover"></div>
<div id="cover_tmp"></div>  
<div id="title">
  <h1>
    <span><?php echo __('manage')?></span>
   <?php echo __('Rates')?>
   
  </h1>
  <?php if (isset($extraClient)) {?>
<script type="text/javascript">
$('#topmenu').hide();
$('#header').hide();
function closeWithoutLoad(){parent.closeCover('cover_tmp');parent.document.body.removeChild(parent.document.getElementById('infodivv'));}
</script>
    <h2><a id="closeA" style="float:left;margin-left:20px;" href="javascript:void(0)" onclick="closeWithoutLoad();">
		    			<i class="icon-remove"></i>
		    		</a></h2>
<?php }?>
   
  <ul id="title-search">
    <li>
	    	<form>
	    		<input type="text" id="search-_q" class="in-search default-value input in-text defaultText" title="<?php echo __('prefixsearch')?>" value="<?php if (!empty($search)) echo $search;?>" name="search">
	    	</form>
    </li>
    <li style="display: list-item;" id="title-search-adv" onclick="advSearchToggle();" title="   »" class=""></li>
  </ul>
    
    
    
    <form   id="download_form" action="<?php echo $this->webroot?>rates/download_rate/<?php echo $rate_table_id?>"  method="post">
    
    </form>
      <?php if (!isset($extraClient)) {?>
  <ul id="title-menu">
    <li><a class="link_back" href="<?php echo $this->webroot?>rates/rates_list"><img width="10" height="5" src="<?php echo $this->webroot?>images/icon_back_white.png" alt=""><?php echo __('goback')?></a></li>    
   
    <?php if($_SESSION['login_type']=='1'){?>
    
    <li><a class="link_btn" href="javascript:void(0)" onclick="add_rate();"><img width="16" height="16" alt="" src="<?php echo $this->webroot?>images/add.png"> <?php echo __('createnew')?></a></li>
    
  <li><a class="link_btn"href="<?php echo $this->webroot?>rates/import_rate/<?php echo $rate_table_id?>"       ><img width="16" height="16" src="<?php echo $this->webroot?>images/import.png"> <?php __("upload")?></a></li>
  <li><a  class="link_btn"href="<?php echo $this->webroot?>rates/download_rate/<?php echo $rate_table_id?>"       ><img width="10" height="5" src="<?php echo $this->webroot?>images/export.png" alt=""><?php __('download')?></a></li> 
    <!--
    <li><a href="javascript:void(0)" onclick="if ('<?php echo $code_deck?>' > 0){cover('generate');}else{jGrowl_to_notyfy('没有匹配的号码组',{theme:'jmsg-alert'});}"><img width="16" height="16" alt="" src="<?php echo $this->webroot?>images/status_closed.gif"> <?php echo __('accordingcodedeck')?></a></li>
   
    --><li><a class="link_btn" href="javascript:void(0)" onclick="cover('simulated');"><img width="16" height="16" alt="" src="<?php echo $this->webroot?>images/simulate.gif"> <?php echo __('simulatedbilling')?></a></li>
   <!--  <li><a rel="popup" href="javascript:void(0)" onclick="deleteAll('<?php echo $this->webroot?>rates/delete_all');" ><i class="icon-remove"></i> <?php echo __('deleteall')?></a></li>
    <li><a rel="popup" href="javascript:void(0)" onclick="deleteSelected('ratetab','<?php echo $this->webroot?>rates/delete_selected');"><i class="icon-remove"></i> <?php echo __('deleteselected')?></a></li>-->
  </ul><?php }}?>
</div>


<dl id="generate" class="tooltip-styled" style="display:none;position:absolute;left:40%;top:30%;z-idnex:99;width:auto;height:auto;">
<div style="text-align:center;width:100%;height:25px;font-size: 16px;"><?php echo __('accordingcodedeck')?></div>
	<table class="form">
			<tr>
				<td class="label"><?php echo __('Rates')?>:</td>
				<td class="value1"><input class="width110 input in-text" id="gen_rate" value=""/></td>
				<td class="label"><?php echo __('setup_fee')?>:</td>
				<td class="value1"><input class="width110 input in-text" id="gen_setup_fee" value=""/></td>
			</tr>
			<tr>
				<td class="label"><?php echo __('effective_date')?>:</td>
				<td class="value1"><input class="wdate width110 input in-text" id="gen_effective_date" value="<?php echo $now?>" readonly onfocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})"/></td>
				<td class="label"><?php echo __('end_date')?>:</td>
				<td class="value1"><input class="wdate width110 input in-text" id="gen_end_date" value="<?php echo $now?>" readonly onfocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})"/></td>
			</tr>
			<tr>
				<td class="label"><?php echo __('min_time')?>:</td>
				<td class="value1"><input class="width110 input in-text" id="gen_min_time" value=""/></td>
				<td class="label"><?php echo __('interval')?>:</td>
				<td class="value1"><input class="width110 input in-text" id="gen_interval" value=""/></td>
			</tr>
			<tr>
				<td class="label"><?php echo __('seconds')?>:</td>
				<td class="value1"><input class="width110 input in-text" id="gen_seconds" value=""/></td>
				<td class="label"><?php echo __('grace_time')?>:</td>
				<td class="value1"><input class="width110 input in-text" id="gen_grace_time" value=""/></td>
			</tr>
			<tr>
				<td class="label"><?php echo __('time_profile_id')?>:</td>
				<td class="value1">
						<select class="width110 input in-select" id="gen_time_profile">
								<?php
									$loop = count($timeswithoutencode);
									for ($i = 0;$i<$loop;$i++) { 
								?>
										<option value="<?php echo $timeswithoutencode[$i][0]['time_profile_id']?>"><?php echo $timeswithoutencode[$i][0]['name']?></option>
								<?php
									} 
								?>
						</select>
				</td>
				<td class="label"></td>
				<td class="value1"></td>
			</tr>
	</table>
	<div style="margin-top:10px; margin-left:35%;width:150px;height:auto;">
		<input type="button" onclick="generate('<?php echo $code_deck?>','<?php echo $table_id?>');" value="<?php echo __('submit')?>" class="input in-button">
		<input type="button" onclick="closeCover('generate');" value="<?php echo __('cancel')?>" class="input in-button">
	</div>
</dl>

<dl id="simulated" class="tooltip-styled" style="display:none;position:absolute;left:40%;top:30%;z-idnex:99;width:auto;height:auto;">
<div style="text-align:center;width:100%;height:25px;font-size: 16px;"><?php echo __('simulatedbilling')?></div>
<div style="width:100%">
		<?php echo __('date')?>:<input id="simu_date" class="wdate width110 in-text input" value="<?php echo $now?>" readonly onfocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'});"/>
		<?php echo __('code')?>:<input id="simu_num" value="" class="in-text input width90"/>
		<?php echo __('durations')?>:<input id="simu_durations" value="60" class="textRight in-text input" style="width:50px;"/><?php echo __('second')?>
		<table class="list" style="margin-top:15px;">
			<col style="width: 25%;">
			<col style="width: 25%;">
			<col style="width: 25%;">
			<col style="width: 25%;">
			<thead>
					<tr>
							<td><?php echo __('prefix')?></td>
							<td><?php echo __('cost')?></td>
							<td><?php echo __('Rates')?></td>
							<td><?php echo __('billedtime')?></td>
					</tr>
			</thead>
			<tbody id="simu_result"></tbody>
		</table>
</div>

<div style="margin-top:10px; margin-left:35%;width:150px;height:auto;">
		<input type="button" onclick="simulatebilling('<?php echo $table_id?>');" value="<?php echo __('submit')?>" class="input in-button">
		<input type="button" onclick="closeCover('simulated');" value="<?php echo __('cancel')?>" class="input in-button">
</div>
</dl>

<div id="container">



<fieldset class="title-block" id="advsearch" style="display:none;width:100%;margin-left:1px;">
<form method="post">
<input type='hidden' name="advsearcht" value="true"/>

<table style="width: 1200px;">
<tbody>
	<tr>
			<td>
				<label style="padding-top:3px;"><?php echo __('Rates')?>:</label>
				<input id="startrate" name="startrate" style="width:60px;height:20px;text-align:right;"/>
				－
				<input id="endrate" name="endrate" style="width:60px;height:20px;text-align:right;"/>
			</td>
			
			<td>
				<label style="padding-top:3px;"><?php echo __('setup_fee')?>:</label>
				<input id="startsetupfee" name="startsetupfee" style="width:60px;height:20px;text-align:right;"/>
				－
				<input id="endsetupfee" name="endsetupfee" style="width:60px;height:20px;text-align:right;"/>
			</td>
		
			<td>
				<label style="padding-top:3px;"><?php echo __('min_time')?>:</label>
				<input id="startmint" name="startmint" style="width:60px;height:20px;text-align:right;"/>
				－
				<input id="endmint" name="endmint" style="width:60px;height:20px;text-align:right;"/>
			</td>
			
			<td>
				<label style="padding-top:3px;"><?php echo __('interval')?>:</label>
				<input id="startinterv" name="startinterv" style="width:60px;height:20px;text-align:right;"/>
				－
				<input id="endinterv" name="endinterv" style="width:60px;height:20px;text-align:right;"/>
			</td>
			
			<td>
				<label style="padding-top:3px;"><?php echo __('grace_time')?>:</label>
				<input id="startgrace" name="startgrace" style="width:60px;height:20px;text-align:right;"/>
				－
				<input id="endgrace" name="endgrace" style="width:60px;height:20px;text-align:right;"/>
			</td>
			
			<td>
				<label style="padding-top:3px;"><?php echo __('timeprofile')?>:</label>
				<select style="width:120px;height:20px;" id="searchtf" name="searchtf">
					<option value=""><?php echo __('select')?></option>
					<?php
						$loop = count($timeswithoutencode);
						for ($i=0;$i<$loop;$i++) { 
					?>
							<option value="<?php echo $timeswithoutencode[$i][0]['time_profile_id']?>"><?php echo $timeswithoutencode[$i][0]['name']?></option>
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
<input id="tmpid" value="-1" style="display:none"/>
<div id="cover"></div>
<div id="toppage"></div>
<table class="list">
	<col style="width: 3%;">
	<col style="width: 4%;">
	<col style="width: 8%;">
	<col style="width: 6%;">
	<col style="width: 5%;">
	<col style="width: 5%;">
	<col style="width: 5%;">
	<col style="width: 5%;">
	<col style="width: 5%;">
	<col style="width: 5%;">
	<col style="width: 5%;">
	<col style="width: 5%;">
	<col style="width: 5%;">
		<col style="width: 5%;">
	<col style="width: 5%;">
 <?php if($_SESSION['login_type']=='1'){?>
	<col style="width: 8%;">
<?php }?>
	<thead>
		<tr>
			<td><input type="checkbox" onclick="checkAllOrNot(this,'ratetab');" value=""/></td>
		 <td><a href="javascript:void(0)" onclick="my_sort('rate_id','asc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<?php echo __('id',true);?>&nbsp;<a href="javascript:void(0)" onclick="my_sort('rate_id','desc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
    <td><a href="javascript:void(0)" onclick="my_sort('code','asc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<?php echo __('prefix')?>&nbsp;<a href="javascript:void(0)" onclick="my_sort('code','desc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
    <td><a href="javascript:void(0)" onclick="my_sort('code_name','asc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<?php echo __('codenames')?>&nbsp;<a href="javascript:void(0)" onclick="my_sort('code_name','desc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
    <td><a href="javascript:void(0)" onclick="my_sort('rate','asc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<?php echo __('Rates')?>&nbsp;<a href="javascript:void(0)" onclick="my_sort('rate','desc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
    <td><a href="javascript:void(0)" onclick="my_sort('setup_fee','asc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<?php echo __('setup_fee')?>&nbsp;<a href="javascript:void(0)" onclick="my_sort('setup_fee','desc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
    <td><a href="javascript:void(0)" onclick="my_sort('effective_date','asc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<?php echo __('effective_date')?>&nbsp;<a href="javascript:void(0)" onclick="my_sort('effective_date','desc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
    <td><a href="javascript:void(0)" onclick="my_sort('end_date','asc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<?php echo __('end_date')?>&nbsp;<a href="javascript:void(0)" onclick="my_sort('end_date','desc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
    <td><a href="javascript:void(0)" onclick="my_sort('min_time','asc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<?php echo __('min_time')?>&nbsp;<a href="javascript:void(0)" onclick="my_sort('min_time','desc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
    <td><a href="javascript:void(0)" onclick="my_sort('interval','asc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<?php echo __('interval')?>&nbsp;<a href="javascript:void(0)" onclick="my_sort('interval','desc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
    <td><a href="javascript:void(0)" onclick="my_sort('tf','asc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<?php echo __('time_profile_id')?>&nbsp;<a href="javascript:void(0)" onclick="my_sort('tf','desc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
    <td><a href="javascript:void(0)" onclick="my_sort('seconds','asc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<?php echo __('seconds')?>&nbsp;<a href="javascript:void(0)" onclick="my_sort('seconds','desc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
    <td><a href="javascript:void(0)" onclick="my_sort('grace_time','asc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<?php echo __('grace_time')?>&nbsp;<a href="javascript:void(0)" onclick="my_sort('grace_time','desc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
 
  <td class="last"><?php echo __('intra_rate',true);?></td>
  <td class="last"><?php echo __('inter_rate',true);?></td>
 <?php if($_SESSION['login_type']=='1'){?>
    <td class="last"><?php echo __('action')?></td>
    <?php }?>
		</tr>
	</thead>
	<tbody id="ratetab">
		<?php 
			$mydata =$p->getDataArray();
			$loop = count($mydata); 
			for ($i=0;$i<$loop;$i++) {?>
				<tr class="row-1">
					<td style="text-align:center"><input type="checkbox" value="<?php echo $mydata[$i][0]['rate_id']?>"/></td>
		    <td class="in-decimal" style="text-align:center"><?php echo $mydata[$i][0]['rate_id']?></td>
		    <td style="font-weight: bold;"><?php echo $mydata[$i][0]['code']?></td>
		    <td><?php echo $mydata[$i][0]['code_name']?></td>
		    <td style="color:green"><?php echo   number_format($mydata[$i][0]['rate'],6)?></td>
		    <td style="color:green"><?php echo  number_format($mydata[$i][0]['setup_fee'],6)?></td>
		    <td style="text-align:left;"><?php echo $mydata[$i][0]['effective_date'];?></td>
		    <td style="text-align:left;"><?php  echo $mydata[$i][0]['end_date']?></td>
		    <td><?php echo $mydata[$i][0]['min_time']?></td>
		    <td><?php echo $mydata[$i][0]['interval']?></td>
		    <td><?php echo $mydata[$i][0]['tf']?></td>
		    <td><?php echo $mydata[$i][0]['seconds']?></td>
		    <td><?php echo $mydata[$i][0]['grace_time']?></td>
	      <td><?php echo $mydata[$i][0]['intra_rate']?></td>
	       <td><?php echo $mydata[$i][0]['inter_rate']?></td>

		     <?php if($_SESSION['login_type']=='1'){?>
		    <td>
		    
		    		<a style="float:left;margin-left: 30px;" href="javascript:void(0)" onclick="edit_rate(this.parentNode.parentNode);">
		    			<i class="icon-edit"></i>
		    		</a>
		    		<a style="float:left;margin-left:9px;" href="javascript:void(0)" onclick="delConfirm(this,'<?php echo $this->webroot?>rates/del_rate/<?php echo $mydata[$i][0]['rate_id']?>/<?php echo $mydata[$i][0]['rate_table_id']?>');">
		    			<i class="icon-remove"></i>
		    		</a>
		    		
		    </td>
		    <?php }?>
		    	<td style="display:none"><?php echo $mydata[$i][0]['time_profile_id']?></td>
				</tr>
				<script>document.getElementById("tmpid").value += ",<?php echo $mydata[$i][0]['rate_id']?>"</script>
		<?php }?>
		</tbody>
</table>
<div id="tmppage">
<?php echo $this->element('page');?>
</div>
<?php $d = $p->getDataArray();if (count($d) == 0) {?>
<div class="msg"><?php echo __('no_data_found')?></div>
<?php }?>
<script type="text/javascript">
function updateForm_submit(){
	var ratetab = document.getElementById("ratetab");
	var chx = ratetab.getElementsByTagName("input");
	var v = "-1";
	for (var i = 0;i<chx.length;i++){
		var c = chx[i];
		if (c.type=='checkbox' && c.checked == true){
			v += ","+c.value;
		}
	}
	if (v != "-1"){
		document.getElementById("ids").value = v;
	}
}
</script>
<form method="post" id="updateForm">
<input type="hidden" name="updateForm" value="true"/>
<input type="hidden" name="ids" id="ids"/>
<input type="hidden" name="type" id="type"/>
<input type="hidden" name="submitids" id="submitids"/>
<script type="text/javascript">document.getElementById("ids").value = document.getElementById("tmpid").value;</script>
		<fieldset id="b-me-full"><legend><?php echo __('buckupdate')?>:</legend>
<div id="actionPanelEdit" style="display: block;">
    <table class="form-list">
    <tbody>
    		<tr>
        <td class="label">
        		<label>
        			<!--  <span rel="helptip" class="helptip" id="ht-100008">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo __('Rates')?></span>
        			<span class="tooltip" id="ht-100008-tooltip">Price per 1 minute of call</span>:-->
        			<span id="ht-100008">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo __('Rates')?></span>
        		</label>
        		<select onchange="change_sel(this,'rate_per_min_value');" style="width:100px;height:18px;"  style="width:120px;" id="rate_per_min_action" name="rate_per_min_action" class="input in-select">
        			<option value=""><?php echo __('preserve')?></option>
        			<option value="set"><?php echo __('setto')?></option>
        			<option value="inc"><?php echo __('incfor')?></option>
        			<option value="dec"><?php echo __('decfor')?></option>
        			<option value="perin"><?php echo __('persinc')?></option>
        			<option value="perde"><?php echo __('persdec')?></option>
        		</select>
        </td>
        <td class="value"><input type="text" id="rate_per_min_value" class="in-decimal input in-text" value="0.000" name="rate_per_min_value" style="display: none;height:18px;float:left;"></td>
        
        <td class="label">
        	<label>
        		<!--  <span rel="helptip" class="helptip" id="ht-100009"><?php echo __('min_time')?></span>
        		<span class="tooltip" id="ht-100009-tooltip">Minimal time of call that will be tarificated (seconds). For example, if total call time was 20 seconds, and Min Time is 30, then client will pay for 30 seconds of call</span>:-->
        		<span  id="ht-100009"><?php echo __('min_time')?></span>
        	</label>
        	<select onchange="change_sel(this,'min_time_value');" style="width:100px;height:18px;"  id="min_time_action" name="min_time_action" class="input in-select">
        		<option value=""><?php echo __('preserve')?></option>
        		<option value="set"><?php echo __('setto')?></option>
        	</select>
        </td>
        <td class="value"><input type="text" id="min_time_value" class="in-decimal input in-text" value="1" name="min_time_value"  style="display: none;height:18px;float:left;"></td>
        
        <td class="label">
        	<label>
        		<!--  <span rel="helptip" class="helptip" id="ht-100010"><?php echo __('start_time',true);?></span>
        		<span class="tooltip" id="ht-100010-tooltip">Rate start date, before this date the rate will not be used</span>:-->
        		<span id="ht-100010"><?php echo __('start_time',true);?></span>
        	</label>
        	<select style="width:100px;height:18px;" onchange="change_sel(this,'effective_from_value-wDt');"    id="effective_from_action" name="effective_from_action" class="input in-select">
        		<option value=""><?php echo __('preserve')?></option>
        		<option value="set"><?php echo __('setto')?></option>
        	</select>
        </td>
        
        <td class="value">
        	<div id="effective_from_value">
        		<table class="in-date">
								<tbody>
									<tr>
    								<td>
    									<input type="text" style="height:18px;width:150px;display:none" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'});" readonly id="effective_from_value-wDt" class="in-datetime input in-text wdate" value="" name="effective_from_value">
    								</td>
									</tr>
								</tbody>
							</table>
    				</div>
    			</td>
        <td class="buttons" rowspan="3">
            <input type="button" onclick="submitForm('preview');" value="<?php echo __('preview')?>" id="action_preview" class="input in-submit">
            <br> <br> 
            <input type="button" onclick="submitForm('apply');" value="<?php echo __('apply')?>" id="action_process" class="input in-button">
        </td>
    </tr>
    
    <tr>
        <td class="label">
        	<label>
        		<!--  <span rel="helptip" class="helptip" id="ht-100011"><?php echo __('setup_fee')?></span>
        		<span class="tooltip" id="ht-100011-tooltip">Fee, that applied when time of call is greater than 0</span>:-->
        		<span id="ht-100011"><?php echo __('setup_fee')?></span>
        	</label>
        	<select onchange="change_sel(this,'pay_setup_value');" style="width:100px;height:18px;"  id="pay_setup_action" name="pay_setup_action" class="input in-select">
        		<option value=""><?php echo __('preserve')?></option>
        		<option value="set"><?php echo __('setto')?></option>
        		<option value="inc"><?php echo __('incfor')?></option>
        		<option value="dec"><?php echo __('decfor')?></option>
        		<option value="perin"><?php echo __('persinc')?></option>
        			<option value="perde"><?php echo __('persdec')?></option>
        	</select>
        </td>
        <td class="value"><input type="text" id="pay_setup_value" class="in-decimal input in-text" value="0.000" name="pay_setup_value"  style="display: none;height:18px;float:left;"></td>
        
        <td class="label">
        	<label>
        		<!--  <span rel="helptip" class="helptip" id="ht-100012"><?php echo __('interval')?></span>
        		<span class="tooltip" id="ht-100012-tooltip">Tarification interval (seconds). This parameter is used, when Min Time time expires</span>:-->
        		<span id="ht-100012"><?php echo __('interval')?></span>
        	</label>
        	<select onchange="change_sel(this,'pay_interval_value');" style="width:100px;height:18px;"  id="pay_interval_action" name="pay_interval_action" class="input in-select">
        		<option value=""><?php echo __('preserve')?></option>
        		<option value="set"><?php echo __('setto')?></option>
        	</select>
        </td>
        <td class="value"><input type="text" id="pay_interval_value" class="in-decimal input in-text" value="1" name="pay_interval_value"  style="display: none;height:18px;float:left;"></td>
        
        <td class="label">
        	<label>
        		<!--  <span rel="helptip" class="helptip" id="ht-100013"><?php echo __('end_time',true);?></span>
        		<span class="tooltip" id="ht-100013-tooltip">Rate end date, after this date the rate will not be used</span>:-->
        		<span  id="ht-100013"><?php echo __('end_time',true);?></span>
        	</label>
        	<select onchange="change_sel(this,'end_date_value-wDt');" style="width:100px;height:18px;"  id="end_date_action" name="end_date_action" class="input in-select">
        		<option value=""><?php echo __('preserve')?></option>
        		<option value="set"><?php echo __('setto')?></option>
        	</select>
        </td>
        <td class="value">
        	<div id="end_date_value">
        		<table class="in-date">
								<tbody>
									<tr>
    								<td><input type="text" readonly onfocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'});" style="height:18px;width:150px;display:none" id="end_date_value-wDt" class="wdate in-datetime input in-text" value="" name="end_date_value"></td>
									</tr>
								</tbody>
							</table>
    				</div>
    			</td>
    		</tr>
    		<tr>
        <td class="label">
        		<label>
        			<!--  <span rel="helptip" class="helptip" id="ht-100014"><?php echo __('grace_time')?></span>
        			<span class="tooltip" id="ht-100014-tooltip">Time interval (seconds), below which calls are not tarificated</span>:-->
        			<span  id="ht-100014"><?php echo __('grace_time')?></span>
        		</label>
        		<select onchange="change_sel(this,'grace_time_value');" style="width:100px;height:18px;"  id="grace_time_action" name="grace_time_action" class="input in-select">
        			<option value=""><?php echo __('preserve')?></option>
        		<option value="set"><?php echo __('setto')?></option>
        		</select>
        	</td>
        <td class="value"><input type="text" id="grace_time_value" class="in-decimal input in-text" value="0" name="grace_time_value" style="display: none;height:18px;float:left;"></td>
                   
        <!--  <td class="label">
        	<label>
        		<span rel="helptip" class="helptip" id="ht-100015">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo __('timeprofile')?></span>
        		<span class="tooltip" id="ht-100015-tooltip">Which time profile will be used for current rate</span>:
        	</label>
        	<select onchange="change_sel(this,'id_time_profiles_value');" style="width:100px;height:18px;"  id="id_time_profiles_action" name="id_time_profiles_action" class="input in-select">
        		<option value=""><?php echo __('preserve')?></option>
        		<option value="set"><?php echo __('setto')?></option>
        	</select>
        </td>
        <td class="value">
        	<select id="id_time_profiles_value" name="id_time_profiles_value" class="input in-select" style="width:100px;height:18px;display: none;">
        	</select>
        </td>-->
    </tr>
    </tbody>
  </table>
</div>
</fieldset>
</form>

<?php if (!empty($previewRates)) {?>
	<table class="list" id="previewTable">
	<col style="width: 6%;">
	<col style="width: 10%;">
	<col style="width: 7%;">
	<col style="width: 7%;">
	<col style="width: 16%;">
	<col style="width: 16%;">
	<col style="width: 8%;">
	<col style="width: 8%;">
	<col style="width: 9%;">
	<col style="width: 7%;">
	<col style="width: 7%;">
	<col style="width: 9%;">
	<thead>
		<tr>
		 <td><a href="javascript:void(0)" onclick="my_sort('rate_id','asc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<?php echo __('id',true);?>&nbsp;<a href="javascript:void(0)" onclick="my_sort('rate_id','desc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
    <td><a href="javascript:void(0)" onclick="my_sort('code','asc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<?php echo __('prefix')?>&nbsp;<a href="javascript:void(0)" onclick="my_sort('code','desc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
    <td><a href="javascript:void(0)" onclick="my_sort('rate','asc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<?php echo __('Rates')?>&nbsp;<a href="javascript:void(0)" onclick="my_sort('rate','desc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
    <td><a href="javascript:void(0)" onclick="my_sort('setup_fee','asc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<?php echo __('setup_fee')?>&nbsp;<a href="javascript:void(0)" onclick="my_sort('setup_fee','desc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
    <td><a href="javascript:void(0)" onclick="my_sort('effective_date','asc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<?php echo __('effective_date')?>&nbsp;<a href="javascript:void(0)" onclick="my_sort('effective_date','desc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
    <td><a href="javascript:void(0)" onclick="my_sort('end_date','asc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<?php echo __('end_date')?>&nbsp;<a href="javascript:void(0)" onclick="my_sort('end_date','desc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
    <td><a href="javascript:void(0)" onclick="my_sort('min_time','asc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<?php echo __('min_time')?>&nbsp;<a href="javascript:void(0)" onclick="my_sort('min_time','desc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
    <td><a href="javascript:void(0)" onclick="my_sort('interval','asc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<?php echo __('interval')?>&nbsp;<a href="javascript:void(0)" onclick="my_sort('interval','desc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
    <td><a href="javascript:void(0)" onclick="my_sort('tf','asc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<?php echo __('time_profile_id')?>&nbsp;<a href="javascript:void(0)" onclick="my_sort('tf','desc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
    <td><a href="javascript:void(0)" onclick="my_sort('seconds','asc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<?php echo __('seconds')?>&nbsp;<a href="javascript:void(0)" onclick="my_sort('seconds','desc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
    <td><a href="javascript:void(0)" onclick="my_sort('grace_time','asc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<?php echo __('grace_time')?>&nbsp;<a href="javascript:void(0)" onclick="my_sort('grace_time','desc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
		</tr>
	</thead>
	<tbody id="ratetab">
		<?php 
			$mydata =$previewRates;
			$loop = count($mydata); 
			for ($i=0;$i<$loop;$i++) {?>
				<tr class="row-1">
		    <td class="in-decimal" style="text-align:center"><?php echo $mydata[$i][0]['rate_id']?></td>
		    <td style="font-weight: bold;"><?php echo $mydata[$i][0]['code']?></td>
		    <td style="color:green"><?php echo number_format($mydata[$i][0]['rate'],6)?></td>
		    <td style="color:green"><?php echo number_format($mydata[$i][0]['setup_fee'],3)?></td>
		    <td><?php echo $mydata[$i][0]['effective_date']?></td>
		    <td><?php echo $mydata[$i][0]['end_date']?></td>
		    <td><?php echo $mydata[$i][0]['min_time']?></td>
		    <td><?php echo $mydata[$i][0]['interval']?></td>
		    <td><?php echo $mydata[$i][0]['tf']?></td>
		    <td><?php echo $mydata[$i][0]['seconds']?></td>
		    <td><?php echo $mydata[$i][0]['grace_time']?></td>
		    	<td style="display:none"><?php echo $mydata[$i][0]['time_profile_id']?></td>
				</tr>
				<script>document.getElementById("tmpid").value += ",<?php echo $mydata[$i][0]['rate_id']?>"</script>
		<?php }?>
		</tbody>
</table>
<?php }?>

<?php
	if (!empty($previewForm)) {
		$d = array_keys($previewForm);
		foreach($d as $k) {?>
			<script type="text/javascript">
				if (document.getElementById("<?php echo $k?>").tagName != 'input'){
					document.getElementById("<?php echo $k?>").value = "<?php echo $previewForm[$k]?>";
				}
			</script>
<?php 
		}
?>
	<script type="text/javascript">
		var ids = "<?php echo $previewForm['submitids']?>".split(",");
		for (var i = 0;i<ids.length;i++) {
			if (document.getElementById(ids[i])) {
				document.getElementById(ids[i]).style.display = "";
			}
		}
	</script>
<?php 
	}?>
<script type="text/javascript">
	function change_sel(obj,id){
		if (obj.value == "" || obj.value == "none"){
			$('#'+id).fadeOut();
		} else {
			$('#'+id).fadeIn();
		}
	}
	function submitForm(type){
		var arr = [
			'rate_per_min_value', 'pay_setup_value','grace_time_value','min_time_value','pay_interval_value'
		];
		var sids = document.getElementById("submitids");
		for (var i = 0;i<arr.length;i++) {
			if (document.getElementById(arr[i]).style.display != 'none'){
				sids.value += ","+arr[i];
			}
		}
		document.getElementById("type").value = type;
		updateForm_submit();
		document.getElementById("updateForm").submit();
	}
</script>
</div>
<div id="livemargins_control" style="position: absolute; display: none; z-index: 9999;"><img width="77" height="5" style="position: absolute; left: -77px; top: -5px;" src="chrome://livemargins/skin/monitor-background-horizontal.png">	<img style="position: absolute; left: 0pt; top: -5px;" src="chrome://livemargins/skin/monitor-background-vertical.png">	<img style="position: absolute; left: 1px; top: 0pt; opacity: 0.5; cursor: pointer;" onmouseout="this.style.opacity=0.5" onmouseover="this.style.opacity=1" src="chrome://livemargins/skin/monitor-play-button.png" id="monitor-play-button"></div>