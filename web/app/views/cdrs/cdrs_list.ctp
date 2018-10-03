
    <title>Statistics / CDRs List :: yht</title>
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <script type="text/javascript" src="<?php echo $this->webroot?>js/My97DatePicker/WdatePicker.js"></script>
    <script language="JavaScript" type="text/javascript">
    					//选择代理商或者客户或者卡  由子页面调用
    				function choose(tr){
        			document.getElementById('query-id_clients_name').value = tr.cells[1].innerHTML.trim();
        			document.body.removeChild(document.getElementById("infodivv"));
        			closeCover('cover_tmp');
        				}

    				function cdr_search(){
    					

    					var output = document.getElementById('query-output').value;//输出格式
    					if(output=='web'){
    						loading();
        					}

    					
        		var st = document.getElementById('query-start_date-wDt').value + " " + document.getElementById('query-start_time-wDt').value;
        		var et = document.getElementById('query-stop_date-wDt').value+" "+document.getElementById('query-stop_time-wDt').value;
        		var rc = document.getElementById('query-id_clients_name').value;
        		var ct = document.getElementById('query-client_type').value;
        		var rate = document.getElementById('query-id_voip_hosts').value;
        		var type = document.getElementById('query-call_origin').value;
        		var result = document.getElementById('query-res_status').value;
        		var causing = document.getElementById('query-disconnect_cause').value;
        		var cost = document.getElementById('query-cost').value;
        		var dst = document.getElementById('query-dst_number').value;
        		var src = document.getElementById('query-src_number').value;
        		var status = document.getElementById('query-status').value;
        		var durations = document.getElementById('query-duration').value;
        		var caller_id = document.getElementById('query-call_id').value;
        		var query_st = document.getElementById("query-dur-st").value;
        		var query_et = document.getElementById("query-dur-et").value;

        		var url = "<?php echo $this->webroot?>cdrs/cdrs_list?search=t&st="+st
        									+"&et="+et+"&ct="+ct+"&rc="+rc+"&rt="+rate
        									+"&tp="+type+"&rs="+result+"&cus="+causing
        									+"&cost="+cost+"&dst="+dst+"&src="+src
        									+"&stat="+status+"&dura="+durations
        									+"&cid="+caller_id
        									+"&output="+output+"&dust="+query_st+"&duet="+query_et;

        		//需要显示的量位
        		var showfields = "";
        		$('#query-fields :selected').each(function(){
        			showfields += this.value+",";
            				});
        		showfields = showfields.substring(0,showfields.lastIndexOf(","));
							location = url+"&showf="+showfields;
        				}
    </script>
<div id="cover"></div>
<div id="cover_tmp"></div>
<div id="title">
            <h1>
<?php echo __('tool')?>&gt;&gt;
        <?php echo __('cdrsearch')?>
                        </h1>
    </div>
<div id="container">
    <!-- DYNAMIC -->
<script type="text/javascript">
var _ss_ids_client = {'id_clients': 'query-id_clients', 'id_clients_name': 'query-id_clients_name', 'account': 'query-account', 'tz': 'query-tz', 'id_currencies': 'query-id_currencies'};
var _ss_ids_code = {'code': 'query-code', 'id_code_decks': 'query-id_code_decks'};
var _ss_ids_code_name = {'code_name': 'query-code_name', 'id_code_decks': 'query-id_code_decks'};
</script>
<?php if (!empty($hasData) && (count($hasData) > 0)) {?>
		<div id="toppage"></div>
		<table class="list">
			<!--  <col style="width: 10%;">
			<col style="width: 15%;">
			<col style="width: 9%;">
			<col style="width: 9%;">
			<col style="width: 9%;">
			<col style="width: 9%;">
			<col style="width: 9%;">
			<col style="width: 10%;">
			<col style="width: 7%;">
			<col style="width: 8%;">
			<col style="width: 8%;">
			<col style="width: 10%;">-->
			<thead>
				<tr>
				<?php for ($f=0;$f<count($fields);$f++){?>
		    	<td style="text-align:center;"><a href="javascript:void(0)" onclick="my_sort('<?php echo $fields[$f];?>','asc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<?php echo $fields[$f]?>&nbsp;<a href="javascript:void(0)" onclick="my_sort('<?php echo $fields[$f];?>','desc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
		   <?php }?>
		    		<!--
		    	<td><a href="javascript:void(0)" onclick="my_sort('st','asc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<?php echo __('startdate')?>&nbsp;<a href="javascript:void(0)" onclick="my_sort('st','desc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
		    <td><a href="javascript:void(0)" onclick="my_sort('account','asc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<?php echo __('account')?>&nbsp;<a href="javascript:void(0)" onclick="my_sort('account','desc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
		    <td><a href="javascript:void(0)" onclick="my_sort('dst','asc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<?php echo __('dnis')?>&nbsp;<a href="javascript:void(0)" onclick="my_sort('dst','desc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
		    <td><a href="javascript:void(0)" onclick="my_sort('durations','asc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<?php echo __('durations')?>&nbsp;<a href="javascript:void(0)" onclick="my_sort('durations','desc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
		    <td><a href="javascript:void(0)" onclick="my_sort('rate','asc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<?php echo __('Rates')?>&nbsp;<a href="javascript:void(0)" onclick="my_sort('rate','desc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
		    <td><a href="javascript:void(0)" onclick="my_sort('curr','asc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<?php echo __('currency')?>&nbsp;<a href="javascript:void(0)" onclick="my_sort('curr','desc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
		    <td><a href="javascript:void(0)" onclick="my_sort('result','asc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<?php echo __('result')?>&nbsp;<a href="javascript:void(0)" onclick="my_sort('result','desc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
		    <td><a href="javascript:void(0)" onclick="my_sort('origination_codec_list','asc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<?php echo __('ingress_codec')?>&nbsp;<a href="javascript:void(0)" onclick="my_sort('origination_codec_list','desc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
		    <td><a href="javascript:void(0)" onclick="my_sort('termination_codec_list','asc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<?php echo __('egress_codec')?>&nbsp;<a href="javascript:void(0)" onclick="my_sort('termination_codec_list','desc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
		    <td><a href="javascript:void(0)" onclick="my_sort('fs_ip','asc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<?php echo __('fs_ip')?>&nbsp;<a href="javascript:void(0)" onclick="my_sort('fs_ip','desc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
		    	-->
				</tr>
			</thead>
			<tbody id="producttab">
				<?php 
					$mydata =$p->getDataArray();
					$loop = count($mydata); 
					for ($i=0;$i<$loop;$i++) {?>
						<tr class="row-1">
						<?php for ($f=0;$f<count($fields);$f++){?>
							<td style="text-align:center;"><?php echo $mydata[$i][0]["$fields[$f]"]?></td>
						<?php }?>
				   		 <!--
				    <td><?php echo $mydata[$i][0]['tp'] == 0?__('origna',true):__('terms',true)?></td>
				    <td><?php if (!empty($mydata[$i][0]['st'])) echo date('Y-m-d H:i:s',$mydata[$i][0]['st'])?></td>
				    <td style="font-weight: bold;"><?php echo $mydata[$i][0]['account']?></td>
				    <td><?php echo $mydata[$i][0]['dst']?></td>
				    <td><?php echo $mydata[$i][0]['durations']?></td>
				    <td><?php echo $mydata[$i][0]['rate'] > 0?"<span style='color:green'>".$mydata[$i][0]['rate']."</span>":"<span style='color:red'>".$mydata[$i][0]['rate']."</span>"?></td>
				    <td style="font-weight: bold;"><?php echo $mydata[$i][0]['curr']?></td>
				    <td><?php echo $mydata[$i][0]['result'] == 1?__('success',true):__('error',true)?></td>
				    <td><?php echo $mydata[$i][0]['origination_codec_list']?></td>
				    <td><?php echo $mydata[$i][0]['termination_codec_list']?></td>
				    <td><?php echo $mydata[$i][0]['fs_ip']?></td>
				   	 -->
						</tr>
				<?php }?>		
			</tbody>
			<tbody>
		</tbody>
		</table>
		<div id="tmppage">
		<?php echo $this->element('page');?>
		</div>
<?php } else if(!empty($search)) {?>
	<div class="msg"><?php echo __('nodata')?></div>
<?php }?>

<fieldset class="query-box"><legend><?php echo __('cdrsearch')?></legend>
<input class="input in-hidden" name="query[process]" value="1" id="query-process" type="hidden"><input class="input in-hidden" name="query[id_clients]" value="" id="query-id_clients" type="hidden"><input class="input in-hidden" name="query[account]" value="" id="query-account" type="hidden"><table class="form" style="width: 960px;">
<col style="width: 80px;">
<col style="width: 240px;">
<col style="width: 70px;">
<col style="width: 210px;">
<col style="width: 70px;">
<col style="width: 130px;">
<col style="width: 140px;">
<tbody><tr class="period-block">
    <td class="label"><?php echo __('cdrperiod')?>:</td>
    <td class="value" colspan="5"><table  class="in-date"><tbody><tr>
    <td style="padding-right:15px;">
    		<select class="input in-select" name="query[smartPeriod]" onchange="setPeriod(this.value)" id="query-smartPeriod">
    				<option value="curDay"><?php echo __('today')?></option>
    				<option value="prevDay"><?php echo __('yesterday')?></option>
    				<option value="curWeek"><?php echo __('currentweek')?></option>
    				<option value="prevWeek"><?php echo __('previousweek')?></option>
    				<option value="curMonth"><?php echo __('currentmonth')?></option>
    				<option value="prevMonth"><?php echo __('previousmonth')?></option>
    				<option value="curYear"><?php echo __('currentyear')?></option>
    				<option value="prevYear"><?php echo __('previousyear')?></option>
    				<option value="custom"><?php echo __('custom')?></option>
    		</select>
    	</td>
    <td><table class="in-date">
<tbody><tr>
    <td><input name="query[start_date]" value="<?php echo $nt?>" onkeydown="setPeriod('custom')" onchange="setPeriod('custom')" class="in-date input in-text" id="query-start_date-wDt" type="text"></td>
</tr>
</tbody></table>
    </td>
    <td><input class="input in-text" name="query[start_time]" value="00:00:00" style="width: 60px;" onkeydown="setPeriod('custom')" onchange="setPeriod('custom')" id="query-start_time-wDt" type="text"></td>
    <td>—</td>
    <td><table class="in-date">
<tbody><tr>
    <td><input name="query[stop_date]"  value="<?php echo $nt?>" onkeydown="setPeriod('custom')" onchange="setPeriod('custom')" class="in-date input in-text" id="query-stop_date-wDt" type="text"></td>
</tr>
</tbody></table>
    </td>
    <td><input class="input in-text" name="query[stop_time]" value="23:59:59" style="width: 60px;" onkeydown="setPeriod('custom')" onchange="setPeriod('custom')" id="query-stop_time-wDt" type="text"></td>
    <td class="buttons"><input class="input in-submit" onclick="cdr_search();" value="<?php echo __('submit')?>" type="button"></td>
    </tr></tbody></table>
</td>

<td valign="top" rowspan="10" style="padding-left: 10px;" class="label">
   <div align="left"><?php echo __('showfields')?>:</div>
   <select id="query-fields" style="width: 99%; height: 210px;" multiple="multiple" class="input in-select">
   	<option value="account" selected><?php echo __('account')?></option>
   	<option value="call_duration" selected><?php echo __('durations')?></option>
   	<option value="rate" selected><?php echo __('rate')?></option>
   	<option value="bill_result" selected><?php echo __('result')?></option>
   	<option value="release_cause"><?php echo __('release_cause')?></option>
   	<option value="start_time_of_date"><?php echo __('start_time_of_date')?></option>
   	<option value="answer_time_of_date"><?php echo __('answer_time_of_date')?></option>
   	<option value="release_tod"><?php echo __('release_tod')?></option>
   	<option value="release_cause_from_protocol_stack"><?php echo __('release_cause_from_protocol_stack')?></option>
   	<option value="binary_value_of_release_cause_from_protocol_stack"><?php echo __('binary_value_of_release_cause_from_protocol_stack')?></option>
   	<option value="trunk_id_origination"><?php echo __('trunk_id_origination')?></option>
   	<option value="origination_source_number"><?php echo __('origination_source_number')?></option>
   	<option value="origination_source_host_name"><?php echo __('origination_source_host_name')?></option>
   	<option value="origination_destination_number"><?php echo __('origination_destination_number')?></option>
   	<option value="trunk_id_termination"><?php echo __('trunk_id_termination')?></option>
   	<option value="termination_source_number"><?php echo __('termination_source_number')?></option>
   	<option value="termination_destination_number"><?php echo __('termination_destination_number')?></option>
   	<option value="termination_destination_host_name"><?php echo __('termination_destination_host_name')?></option>
   	<option value="pdd"><?php echo __('pdd')?></option>
   	<option value="ring_time"><?php echo __('ring_time')?></option>
   	<option value="call_type"><?php echo __('call_type')?></option>
   	<option value="bill_time"><?php echo __('bill_time')?></option>
   	<option value="cost"><?php echo __('callcost')?></option>
   	<option value="conf_id"><?php echo __('conf_id')?></option>
   	<option value="session_id"><?php echo __('session_id')?></option>
   	<option value="cdr_id"><?php echo __('cdr_id')?></option>
   </select>
</td>
    
</tr>
<?php $login_type = $session->read('login_type');if ($login_type == 1) {?><tr>
    <td class="label"><select class="input in-select" name="query[client_type]" style="width: 90%;"  id="query-client_type"><option value="0"><?php echo __('client')?></option><option value="2"><?php echo __('Reseller')?></option><option value="1"><?php echo __('cardnumber')?></option></select></td>
    <td class="value" id="client_cell" style="float:left;">
        <input class="input in-text" name="query[id_clients_name]" value="<?php echo isset($extra)?$extra:'';?>" readonly="1" style="width: 83%;" onclick="showClients()" id="query-id_clients_name" type="text"><img width="9" height="9" onclick="ss_clear('client', _ss_ids_client)" class="img-button" src="<?php echo $this->webroot?>images/delete-small.png">
    </td>
    
</tr>
<tr>
    <td class="label"><?php echo __('Rates')?>:</td>
    <td class="value"><select class="input in-select" name="query[id_voip_hosts]" id="query-id_voip_hosts">
    	<option value=""><?php echo __('all')?></option>
    	<?php 
    		$loop = count($rates);
    		for ($i = 0;$i<$loop;$i++) {
    		?>
    			<option value="<?php echo $rates[$i][0]['rate_table_id']?>"><?php echo $rates[$i][0]['name']?></option>
   	<?php
    			} 
    		?></select>
    
    <td class="label"><?php echo __('type')?>:</td>
    <td class="value"><select class="input in-select" name="query[call_origin]" id="query-call_origin"><option value=""><?php echo __('all')?></option><option value="orig"><?php echo __('origination')?></option><option value="term"><?php echo __('termination')?></option></select></td>
</tr>
<?php } ?>
<tr>
    <td class="label"><?php echo __('resultcode')?>:</td>
    <td class="value" style="float:left;"><select class="input in-select" name="query[res_status]" style="width: 100px;" id="query-res_status"><option value=""><?php echo __('all')?></option><option value="success"><?php echo __('success')?></option><option value="busy"><?php echo __('busy')?></option><option value="nochannel"><?php echo __('nochannel')?></option><option value="error"><?php echo __('error')?></option></select> <input class="input in-text" name="query[disconnect_cause]" value="" style="width: 53px;" id="query-disconnect_cause" type="text"></td>
    
    <td class="label"><?php echo __('cost')?>:</td>
    <td class="value"><select class="input in-select" name="query[cost]" id="query-cost"><option value=""><?php echo __('all')?></option><option value="nonzero"><?php echo __('nonzero')?></option><option value="zero"><?php echo __('zero')?></option></select></td>
    
    <td class="label"><?php echo __('duration')?>:</td>
    <td class="value"><select class="input in-select" name="query[duration]" onchange="if (this.value=='cus')$('#duration-cell').show();else $('#duration-cell').hide();" id="query-duration"><option value=""><?php echo __('all')?></option><option value="nonzero" selected="selected"><?php echo __('nonzero')?></option><option value="zero"><?php echo __('zero')?></option><option value='cus'><?php echo __('custom')?></option></select></td>
    <td class="value" id="duration-cell" style="display:none"><input style="width:50px;height:20px;" name="query[dur_st]" id="query-dur-st"/>--<input style="width:50px;height:20px;" name="query[dur_et]" id="query-dur-et"/></td>
</tr>
<tr>
    <td class="label"><?php echo __('dstnumber')?>:</td>
    <td class="value"><input class="input in-text" name="query[dst_number]" value="" id="query-dst_number" type="text"></td>
    
    <td class="label"><?php echo __('callid')?>:</td>
    <td class="value"><input class="input in-text" name="query[call_id]" value="" id="query-call_id" type="text"></td>
    
    <td class="label"><?php echo __('cdrid')?>:</td>
    <td class="value"><input class="input in-text" name="query[id_cdrs]" value="" id="query-id_cdrs" type="text"></td>
</tr>
<tr>
    <td class="label"><?php echo __('srcnumber')?>:</td>
    <td class="value"><input class="input in-text" name="query[src_number]" value="" id="query-src_number" type="text"></td>
    
    <td class="label"><?php echo __('status')?>:</td>
    <td class="value"><select class="input in-select" name="query[status]" id="query-status"><option value=""><?php echo __('all')?></option><option value="suc"><?php echo __('billsucc')?></option><option value="rerate"><?php echo __('billfail')?></option></select></td>
    
    <td class="label"></td>
    <td class="value"></td>
</tr>

<tr class="output-block">
    <td class="label"><span><?php echo __('output')?>:</span></td>
    <td class="value"><select class="input in-select" name="query[output]" onchange="repaintOutput();" id="query-output"><option value="web">Web</option><option value="csv">Excel CSV</option></select></td>
    <td class="label" colspan="4"><div></div></td>
</tr>

</tbody></table>
</fieldset>
<script type="text/javascript">
//<![CDATA[
tz = $('#query-tz').val();
function showClients ()
{
    ss_ids_custom['client'] = _ss_ids_client;
    val = $('#query-client_type').val();
    tz = $('#query-tz').val(); 
    var url = null;
    if (val == "1")
        url = "<?php echo $this->webroot?>cdrs/choose_cards";
    else if (val == "0") 
    			url = "<?php echo $this->webroot?>cdrs/choose_clients";
    else if (val == "2")
    			url = "<?php echo $this->webroot?>cdrs/choose_resellers";

    cover('cover_tmp');
		 loadPage(url,500,400);
}

function repaintOutput() {
    if ($('#query-output').val() == 'delayed') {
        $('#output-xls').hide();
        $('#output-delayed').show();
    } else if ($('#query-output').val() == 'xls') {
        $('#output-xls').show();
        $('#output-delayed').hide();
    } else {
        $('#output-xls').hide();
        $('#output-delayed').hide();
    }
}

repaintOutput();

function updateUnknownClients()
{
    if ($('#query-unknown_clients').attr('checked')) {
        $('#query-id_clients_name').attr('disabled', 1).addClass('disabled');
    } else {
        $('#query-id_clients_name').attr('disabled', 0).removeClass('disabled');
    }
}
updateUnknownClients();

function updateUnknownCodes()
{
    if ($('#query-unknown_codes').attr('checked')) {
        $('#query-code_name').attr('disabled', 1).addClass('disabled');
        $('#query-code').attr('disabled', 1).addClass('disabled');
    } else {
        $('#query-code_name').attr('disabled', 0).removeClass('disabled');
        $('#query-code').attr('disabled', 0).removeClass('disabled');
    }
}
updateUnknownCodes();
//]]>
</script>
    <!-- DYNAMIC -->
</div>
