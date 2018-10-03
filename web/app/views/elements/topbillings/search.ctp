<fieldset class="query-box">
	<legend><?php __('search')?></legend>
<form action="<?php echo $this->webroot?>clientmutualsettlements/summary_reports/" method="post">
<table class="form" style="width: 1160px;">
	<col style="width: 80px;">
	<col style="width: 235px;">
	<tbody>
		<tr class="period-block">
    <td class="label">时间:</td>
    <td class="value" colspan="5">
    <table class="in-date"><tbody>
    <tr>
    		<td>
    			<table class="in-date">
						<tbody>
							<tr>
								<td style="padding-right: 15px;">
									<?php $options=Array('custom'=>'自定义','curDay'=>'今天','prevDay'=>'昨天','curWeek'=>'本周','prevWeek'=>'上周','curMonth'=>'本月','prevMonth'=>'上月','curYear'=>'今年','prevYear'=>'去年')?>
									<?php echo $form->input('date',Array('type'=>'select','div'=>false,'label'=>false,'options'=>$options,'class'=>"select in-select",'width'=>'150px'))?>		
								</td>
    						<td>
    								<?php echo $form->input('start_date',Array('style'=>'width:180px','class'=>'wdate in-text input in-input','onfocus'=>"WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})",'readonly'=>"readonly",'div'=>false,'label'=>false))?>
    						</td>
    						<td></td>
							</tr>
						</tbody>
					</table>
    		</td>
    		<td>&mdash;</td>
    		<td>
    			<table class="in-date">
						<tbody>
							<tr>
    						<td>
    								<?php echo $form->input('stop_date',Array('style'=>'width:180px','class'=>"wdate in-text input in-input",'onfocus'=>"WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})",'readonly'=>"readonly",'div'=>false,'label'=>false))?>
    						</td>
    						<td></td>
							</tr>
						</tbody>
					</table>
    		</td>
     
     <td style="padding: 0pt 10px;">in</td>
     <td><select class="input in-select select" name="query[tz]" style="width: 100px;" id="query-tz">
     <option value="-1200">GMT -12:00</option>
     <option value="-1100">GMT -11:00</option>
     <option value="-1000">GMT -10:00</option>
     <option value="-0900">GMT -09:00</option>
     <option value="-0800">GMT -08:00</option>
     <option value="-0700">GMT -07:00</option>
     <option value="-0600">GMT -06:00</option>
     <option value="-0500">GMT -05:00</option>
     <option value="-0400">GMT -04:00</option>
     <option value="-0300">GMT -03:00</option>
     <option value="-0200">GMT -02:00</option>
     <option value="-0100">GMT -01:00</option>
     <option value="+0000">GMT +00:00</option>
     <option value="+0100">GMT +01:00</option>
     <option value="+0200" selected="selected">GMT +02:00</option>
     <option value="+0300">GMT +03:00</option>
     <option value="+0330">GMT +03:30</option><option value="+0400">GMT +04:00</option><option value="+0500">GMT +05:00</option><option value="+0600">GMT +06:00</option><option value="+0700">GMT +07:00</option><option value="+0800">GMT +08:00</option><option value="+0900">GMT +09:00</option><option value="+1000">GMT +10:00</option><option value="+1100">GMT +11:00</option><option value="+1200">GMT +12:00</option></select></td>
    
    </tr></tbody></table>

</td>
    <td class="buttons"><input type="submit" class="input in-submit" value="<?php echo __('Search',true);?>"></td>
</tr>









<tr>
    <td class="label"> 运营商 </td>
    <td class="value" id="client_cell">
        <input type="text" class="input in-text in-input" name="query[id_clients_name]" value="" readonly="1" style="width: 83%;" onclick="showClients()" id="query-id_clients_name">        
        
        <img width="9" height="9" src="<?php echo $this->webroot?>images/search-small.png" class="img-button" onclick="showClients()">
        <img width="9" height="9" src="<?php echo $this->webroot?>images/delete-small.png" class="img-button" onclick="ss_clear('client', _ss_ids_client)">
    </td><!--
    
    <td class="label"> 代理商  </td>
    <td id="client_cell" class="value">
        <input type="text" id="query-id_resellers_name" onclick="showRsellers()" style="width: 63%;" readonly="1" value="" name="query[id_resellers_name]" class="input in-text">        
        
        <img width="9" height="9" onclick="showRsellers()" class="img-button" src="<?php echo $this->webroot?>images/search-small.png">
        <img width="9" height="9" onclick="ss_cllocationear('reseller', _ss_ids_reseller)" class="img-button" src="<?php echo $this->webroot?>images/delete-small.png">
    </td>
    
        --><td class="label">主叫货币:</td>
    <td class="value">
 		<select id="CdrCurrency" name="data[Cdr][currency]" class="select in-select">
<option value=""></option>
<option value="1"><?php __('RMB')?></option>
</select>    </td>
     <td class="label"> 交易类型  </td>
    <td class="value" id="client_cell">
<select id="CdrType" name="data[Cdr][type]" class="select in-select">
<option value=""></option>
<option value="0"><?php __('Outgoing invoice')?></option>
<option value="1"><?php __('Invoices incoming')?></option>
<option value="3">充值</option>
</select>    </td>
</tr>









<tr class="output-block">
    <td class="label"><span>显示方式:</span></td>
    <td class="value">
    <select class="input in-select select" name="query[output]" onchange="repaintOutput();" id="query-output">
    <option value="web" selected="selected"><?php __('Web')?></option><option value="csv"><?php __('Excel CSV')?></option></select></td>
    <td class="label" colspan="4"><div></div></td>
</tr>


</tbody></table>
</form></fieldset>