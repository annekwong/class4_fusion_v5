<style type="text/css">
.list td.in-decimal {
text-align:center;
}
.value input, .value select, .value textarea, .value .in-text, .value .in-password, .value .in-textarea, .value .in-select {
-moz-box-sizing:border-box;
width:100px;;
}
.list {
font-size:1em;
margin:0 auto 20px;
width:799px;;
}
#container .form {
margin:0 auto;
width:750px;
}
</style>
<div id="title">
  <h1><?php __('Statistics')?>&gt;&gt;<?php echo __('mismatchreport');?></h1>
</div>
<div id="container">
<ul id="stats-extra"   style="font-weight: bolder;font-size: 1.1em;color: #6694E3;height:13px">
    <li id="stats-period">
    <span rel="helptip" class="helptip" id="ht-100012"><?php echo __('RealPeriod')?></span>
    <span class="tooltip" id="ht-100012-tooltip"><?php __('Period for which statistics exists in database')?></span>: 
    <span><?php  echo $start;?></span> &mdash; <span><?php echo $end?></span>
    </li>  
      <li id="stats-time" ><?php __('QueryTime')?>: <?php echo $quey_time?> ms</li>
</ul>
    <!-- DYNAREA -->
    <fieldset rel="advsearch-open" class="title-block" id="advsearch" style="display: block;">
    
    <form  id="carriers_form"  method="post"  onsubmit="if ($('#query-output').val() == 'web'){ loading();}if ($('#query-output').val() == 'csv'){$("form:first").submit(); }">
<input   type="hidden"   value="searchkey"    name="searchkey"/>
<input type="hidden" id="query-process" value="1" name="query[process]" class="input in-hidden">


<table style="width: 800px;" class="form">
<col style="width: 80px;">
<col style="width: 6000px;">
<tbody>

<tr>
    <td class="label"  style="width: 2500px;"><?php echo __('time')?>:</td>
    <td class="value"><table class="in-date"><tbody><tr>
    <td style="padding-right: 15px;">
    
 		<?php
$r=array('custom'=>__('custom',true),    'curDay'=>__('today',true),    'prevDay'=>__('yesterday',true),    'curWeek'=>__('currentweek',true),    'prevWeek'=>__('previousweek',true),   'curMonth'=>__('currentmonth',true),
   'prevMonth'=>__('previousmonth',true),   'curYear'=>__('currentyear',true)    ,'prevYear'=>__('previousyear',true)  ); 	
if(!empty($_POST)){
	if(isset($_POST['smartPeriod'])){
		$s=$_POST['smartPeriod'];
		
	}else{
		$s='curDay';
	}
}else{
	
	$s='curDay';
}
echo $form->input('smartPeriod',
 		array('options'=>$r,'label'=>false ,
 		'onchange'=>'setPeriod(this.value)','id'=>'query-smartPeriod','style'=>'width: 130px;','name'=>'smartPeriod',
 		'div'=>false,'type'=>'select','selected'=>$s));?>
    </td>
    <td>
    
    <table class="in-date">
<tbody><tr>
    <td>  <input type="text" id="query-start_date-wDt" class="wdate in-text input" onchange="setPeriod('custom')"   readonly="readonly"  onkeydown="setPeriod('custom')"
         value="" name="start_date"  style="margin-left: 0px; width: 156px;">
         </td>
</tr>
</tbody></table>

    </td>
    <td><input type="text" id="query-start_time-wDt" onchange="setPeriod('custom')" onkeydown="setPeriod('custom')"
    	readonly="readonly"      style="width: 60px;" value="" name="start_time" class="input in-text"></td>
    <td>&mdash;</td>
    <td><table class="in-date">
<tbody><tr>
    <td>
    
<input type="text" id="query-stop_date-wDt" class="wdate in-text input"  style="width: 120px;"    onchange="setPeriod('custom')"
    readonly="readonly"  
     onkeydown="setPeriod('custom')" value="" name="stop_date">
     
     </td>
          <td style="padding: 0pt 10px;">in</td>
     <td><select id="query-tz" style="width: 100px;" name="query[tz]" class="input in-select">
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
     <option  value="+0200">GMT +02:00</option>
     <option value="+0300">GMT +03:00</option>
     <option value="+0330">GMT +03:30</option><option value="+0400">GMT +04:00</option><option value="+0500">GMT +05:00</option><option value="+0600">GMT +06:00</option><option value="+0700">GMT +07:00</option><option value="+0800">GMT +08:00</option><option value="+0900">GMT +09:00</option><option value="+1000">GMT +10:00</option><option value="+1100">GMT +11:00</option><option value="+1200">GMT +12:00</option></select></td>
     
</tr>
</tbody></table>

    </td>
  <td><input type="text" id="query-stop_time-wDt" onchange="setPeriod('custom')"
    readonly="readonly" 
     onkeydown="setPeriod('custom')" style="width: 60px;" value="23:59:59" name="stop_time" class="input in-text"></td>
    </tr></tbody></table>

</td>
    <td style="padding-top: 2px;" class="buttons value"><input type="submit" value="<?php echo __('query',true);?>" class="input in-submit"></td>

</tr>
</tbody></table>
</form>
</fieldset>

<div>
    <fieldset><legend><?php echo __('Calls with unknown carriers',true);?></legend>
    <table class="list list-form">
    <thead>
    <tr>
        <td width="25%"></td>
        <td width="25%"><?php echo __('origination')?></td>
        <td width="25%"><?php echo __('termination')?></td>
        <td width="25%" class="last"><?php echo __('all')?></td>
    </tr>
    </thead>
    <tbody>
    <tr class="row-1">
        <td class="label"><?php echo __('totalcalls')?>:</td>
        <td class="in-decimal"><?php echo   number_format($client_org[0][0]['org2_call'],0)?></td>
        <td class="in-decimal"><?php echo   number_format($client_org[0][0]['term2_call'],0)?></td>
        <td class="in-decimal last"><?php echo   number_format($client_org[0][0]['org2_call']+$client_org[0][0]['term2_call'],0)?></td>
    </tr>
    <tr class="row-2">
        <td class="label"><?php echo __('nonzero')?>:</td>
        <td class="in-decimal"><?php echo   number_format($client_org[0][0]['org2_notzero_call'],0)?></td>
        <td class="in-decimal"><?php echo   number_format($client_org[0][0]['term2_notzero_call'],0)?></td>
        <td class="in-decimal last"><?php echo   number_format($client_org[0][0]['org2_notzero_call']+$client_org[0][0]['term2_notzero_call'],0)?></td>
    </tr>
    <tr class="row-1">
        <td class="label"><?php echo __('durations')?>:</td>
        <td class="in-decimal"><?php echo   number_format($client_org[0][0]['org2_call_duration'],2)?> min</td>
        <td class="in-decimal"><?php echo   number_format($client_org[0][0]['term2_call_duration'],2)?> min</td>
        <td class="in-decimal last"><?php echo   number_format($client_org[0][0]['org2_call_duration']+$client_org[0][0]['term2_call_duration'],2)?> min</td>
    </tr>
        <tr class="totals row-2">
        <td colspan="4" class="last">
            <form method="post"   onsubmit="sub_form();">
            <input type="hidden" name="searchkey" value="searchkey" class="input in-hidden">
            <input type="hidden" id="query-process" value="1" name="query[process]" class="input in-hidden">            <input type="hidden" id="query-start_date" value="2000-08-30" name="query[start_date]" class="input in-hidden">            <input type="hidden" id="query-start_time" value="00:00:00" name="query[start_time]" class="input in-hidden">            <input type="hidden" id="query-stop_date" value="2010-08-31" name="query[stop_date]" class="input in-hidden">            <input type="hidden" id="query-stop_time" value="23:59:59" name="query[stop_time]" class="input in-hidden">            <input type="hidden" id="query-tz" value="+0300" name="query[tz]" class="input in-hidden">            <input type="hidden" id="query-status" value="" name="query[status]" class="input in-hidden">            <input type="hidden" id="query-unknown_clients" value="1" name="query[unknown_clients]" class="input in-hidden">            <input type="hidden" id="query-fields" value="call_origin" name="query[fields][]" class="input in-hidden">            <input type="hidden" id="query-fields" value="call_time" name="query[fields][]" class="input in-hidden">            <input type="hidden" id="query-fields" value="gw_ip" name="query[fields][]" class="input in-hidden">            <input type="hidden" id="query-fields" value="gw_name" name="query[fields][]" class="input in-hidden">            <input type="hidden" id="query-fields" value="session_time" name="query[fields][]" class="input in-hidden">            <input type="hidden" id="query-fields" value="src_number_bill" name="query[fields][]" class="input in-hidden">            <input type="hidden" id="query-fields" value="dst_number_in" name="query[fields][]" class="input in-hidden">            <input type="hidden" id="query-fields" value="dst_number_bill" name="query[fields][]" class="input in-hidden">            <input type="hidden" id="query-fields" value="dst_number_out" name="query[fields][]" class="input in-hidden">            <input type="hidden" id="query-fields" value="call_id" name="query[fields][]" class="input in-hidden">            
            <table class="form">
            <tbody><tr>
                <td class="label"  style="text-align: left;"><?php __('type')?>:</td>
                <td class="value"><select id="query-call_origin" name="query[call_origin]" class="input in-select"  style="width: 121px;"><option value="">all</option><option value="orig">origination</option><option value="term">termination</option></select></td>
                <td class="label"><?php echo __('durations')?>:</td>
                <td class="value"><select id="query-duration"  style="width: 99px;" name="query[duration]" class="input in-select">
                <option value=""  selected="selected">all</option><option  value="nonzero">non-zero</option><option value="zero">zero</option></select></td>
                <td class="label"><?php echo __('Output',true);?>:</td>
                <td class="value"><select id="query-output"  style="width: 99px;" name="query[output]" class="input in-select"><option selected="selected" value="web">Web</option><option value="csv">Excel CSV</option></select></td>
               
                <td class="buttons"><input type="submit" value="<?php __('download')?>" class="input in-submit"></td>
            </tr>
            </tbody></table>
            
            </form>
        </td>
    </tr>
        </tbody>
        
        
        <tbody>
    </tbody></table>
    </fieldset>
    
   <fieldset><legend><?php echo __('noratefound')?></legend>
    <table class="list list-form">
    <thead>
    <tr>
        <td width="25%"></td>
        <td width="25%"><?php echo __('origination')?></td>
        <td width="25%"><?php echo __('termination')?></td>
        <td width="25%" class="last"><?php echo __('all')?></td>
    </tr>
    </thead>
    <tbody>
    <tr class="row-1">
        <td class="label"><?php echo __('totalcalls')?>:</td>
        <td class="in-decimal"><?php echo   number_format($client_org[0][0]['org3_call'],0)?></td>
        <td class="in-decimal"><?php echo   number_format($client_org[0][0]['term3_call'],0)?></td>
        <td class="in-decimal last"><?php echo   number_format($client_org[0][0]['org3_call']+$client_org[0][0]['term3_call'],0)?></td>
    </tr>
    <tr class="row-2">
        <td class="label"><?php echo __('nonzero')?>:</td>
        <td class="in-decimal"><?php echo   number_format($client_org[0][0]['org3_notzero_call'],0)?></td>
        <td class="in-decimal"><?php echo   number_format($client_org[0][0]['term3_notzero_call'],0)?></td>
        <td class="in-decimal last"><?php echo   number_format($client_org[0][0]['org3_notzero_call']+$client_org[0][0]['term3_notzero_call'],0)?></td>
    </tr>
    <tr class="row-1">
        <td class="label"><?php echo __('durations')?>:</td>
        <td class="in-decimal"><?php echo   number_format($client_org[0][0]['org3_call_duration'],2)?> min</td>
        <td class="in-decimal"><?php echo   number_format($client_org[0][0]['term3_call_duration'],2)?> min</td>
        <td class="in-decimal last"><?php echo   number_format($client_org[0][0]['org3_call_duration']+$client_org[0][0]['term3_call_duration'],2)?> min</td>
    </tr>
        <tr class="totals row-2">
        <td colspan="4" class="last">
            <form method="post"   onsubmit="sub_form();">
            <input type="hidden" name="searchkey" value="searchkey" class="input in-hidden">
            <input type="hidden" id="query-process" value="1" name="query[process]" class="input in-hidden">           
             <input type="hidden" id="query-start_date" value="2000-08-30" name="query[start_date]" class="input in-hidden">      
                   <input type="hidden" id="query-start_time" value="00:00:00" name="query[start_time]" class="input in-hidden">    
                           <input type="hidden" id="query-stop_date" value="2010-08-31" name="query[stop_date]" class="input in-hidden">      
                                 <input type="hidden" id="query-stop_time" value="23:59:59" name="query[stop_time]" class="input in-hidden">            <input type="hidden" id="query-tz" value="+0300" name="query[tz]" class="input in-hidden">            <input type="hidden" id="query-status" value="" name="query[status]" class="input in-hidden">            <input type="hidden" id="query-unknown_clients" value="1" name="query[unknown_clients]" class="input in-hidden">            <input type="hidden" id="query-fields" value="call_origin" name="query[fields][]" class="input in-hidden">            <input type="hidden" id="query-fields" value="call_time" name="query[fields][]" class="input in-hidden">            <input type="hidden" id="query-fields" value="gw_ip" name="query[fields][]" class="input in-hidden">            <input type="hidden" id="query-fields" value="gw_name" name="query[fields][]" class="input in-hidden">            <input type="hidden" id="query-fields" value="session_time" name="query[fields][]" class="input in-hidden">            <input type="hidden" id="query-fields" value="src_number_bill" name="query[fields][]" class="input in-hidden">            <input type="hidden" id="query-fields" value="dst_number_in" name="query[fields][]" class="input in-hidden">            <input type="hidden" id="query-fields" value="dst_number_bill" name="query[fields][]" class="input in-hidden">            <input type="hidden" id="query-fields" value="dst_number_out" name="query[fields][]" class="input in-hidden">            <input type="hidden" id="query-fields" value="call_id" name="query[fields][]" class="input in-hidden">            
            <table class="form">
            <tbody><tr>
                <td class="label"  style="text-align: left;"><?php __('type')?>:</td>
                <td class="value"><select id="query-call_origin" name="query[call_origin]" class="input in-select"  style="width: 121px;"><option value=""><?php __('all')?></option><option value="orig"><?php __('origination')?></option><option value="term"><?php __('termination')?></option></select></td>
                <td class="label"><?php echo __('durations')?>:</td>
                <td class="value"><select id="query-duration"  style="width: 99px;" name="query[duration]" class="input in-select">
                <option value=""   selected="selected"><?php __('all')?></option><option value="nonzero"><?php __('non-zero')?></option>
                <option value="zero"><?php __('zero')?></option></select></td>
                <td class="label"><?php echo __('Output',true);?>:</td>
                <td class="value"><select id="query-output2"  style="width: 99px;" name="query[output]" class="input in-select"><option selected="selected" value="web"><?php __('Web')?></option><option value="csv"><?php __('Excel CSV')?></option></select></td>
               
                <td class="buttons"><input type="submit" value="<?php __('download')?>" class="input in-submit"></td>
            </tr>
            </tbody></table>
            
            </form>
        </td>
    </tr>
        </tbody>
        
        
        <tbody>
    </tbody></table>
    </fieldset>
    
    
<fieldset><legend><?php echo __('noratefound1')?></legend>
    <table class="list list-form">
    <thead>
    <tr>
        <td width="25%"></td>
        <td width="25%"><?php echo __('origination')?></td>
        <td width="25%"><?php echo __('termination')?></td>
        <td width="25%" class="last"><?php echo __('all')?></td>
    </tr>
    </thead>
   <tbody>
    <tr class="row-1">
        <td class="label"><?php echo __('totalcalls')?>:</td>
        <td class="in-decimal"><?php echo   number_format($client_org[0][0]['org4_call'],0)?></td>
        <td class="in-decimal"><?php echo   number_format($client_org[0][0]['term4_call'],0)?></td>
        <td class="in-decimal last"><?php echo   number_format($client_org[0][0]['org4_call']+$client_org[0][0]['term4_call'],0)?></td>
    </tr>
    <tr class="row-2">
        <td class="label"><?php echo __('nonzero')?>:</td>
        <td class="in-decimal"><?php echo   number_format($client_org[0][0]['org4_notzero_call'],0)?></td>
        <td class="in-decimal"><?php echo   number_format($client_org[0][0]['term4_notzero_call'],0)?></td>
        <td class="in-decimal last"><?php echo   number_format($client_org[0][0]['org4_notzero_call']+$client_org[0][0]['term4_notzero_call'],0)?></td>
    </tr>
    <tr class="row-1">
        <td class="label"><?php echo __('durations')?>:</td>
        <td class="in-decimal"><?php echo   number_format($client_org[0][0]['org4_call_duration'],0)?> min</td>
        <td class="in-decimal"><?php echo   number_format($client_org[0][0]['term4_call_duration'],0)?> min</td>
        <td class="in-decimal last"><?php echo   number_format($client_org[0][0]['org4_call_duration']+$client_org[0][0]['term4_call_duration'],2)?> min</td>
    </tr>
        <tr class="totals row-2">
        <td colspan="4" class="last">
            <form method="post"  onsubmit="sub_form();">
            <input type="hidden" name="searchkey" value="searchkey" class="input in-hidden">
            <input type="hidden" id="query-process" value="1" name="query[process]" class="input in-hidden">            <input type="hidden" id="query-start_date" value="2000-08-30" name="query[start_date]" class="input in-hidden">            <input type="hidden" id="query-start_time" value="00:00:00" name="query[start_time]" class="input in-hidden">            <input type="hidden" id="query-stop_date" value="2010-08-31" name="query[stop_date]" class="input in-hidden">            <input type="hidden" id="query-stop_time" value="23:59:59" name="query[stop_time]" class="input in-hidden">            <input type="hidden" id="query-tz" value="+0300" name="query[tz]" class="input in-hidden">            <input type="hidden" id="query-status" value="" name="query[status]" class="input in-hidden">            <input type="hidden" id="query-unknown_clients" value="1" name="query[unknown_clients]" class="input in-hidden">            <input type="hidden" id="query-fields" value="call_origin" name="query[fields][]" class="input in-hidden">            <input type="hidden" id="query-fields" value="call_time" name="query[fields][]" class="input in-hidden">            <input type="hidden" id="query-fields" value="gw_ip" name="query[fields][]" class="input in-hidden">            <input type="hidden" id="query-fields" value="gw_name" name="query[fields][]" class="input in-hidden">            <input type="hidden" id="query-fields" value="session_time" name="query[fields][]" class="input in-hidden">            <input type="hidden" id="query-fields" value="src_number_bill" name="query[fields][]" class="input in-hidden">            <input type="hidden" id="query-fields" value="dst_number_in" name="query[fields][]" class="input in-hidden">            <input type="hidden" id="query-fields" value="dst_number_bill" name="query[fields][]" class="input in-hidden">            <input type="hidden" id="query-fields" value="dst_number_out" name="query[fields][]" class="input in-hidden">            <input type="hidden" id="query-fields" value="call_id" name="query[fields][]" class="input in-hidden">            
            <table class="form">
            <tbody><tr>
                <td class="label"  style="text-align: left;"><?php __('type')?>:</td>
                <td class="value"><select id="query-call_origin" name="query[call_origin]" class="input in-select"  style="width: 121px;"><option value=""><?php __('all')?></option><option value="orig"><?php __('origination')?></option><option value="term"><?php __('termination')?></option></select></td>
                <td class="label"><?php echo __('durations')?>:</td>
                <td class="value"><select id="query-duration"  style="width: 99px;" name="query[duration]" class="input in-select">
                <option  selected="selected" value=""><?php __('all')?></option><option  value="nonzero"><?php __('non-zero')?></option><option value="zero"><?php __('zero')?></option></select></td>
                <td class="label"><?php echo __('Output',true);?>:</td>
                <td class="value">
                <select id="query-output3"  style="width: 99px;" name="query[output]"   class="input in-select"><option selected="selected" value="web"><?php __('Web')?></option><option value="csv"><?php __('Excel CSV')?></option></select></td>
               
                <td class="buttons"><input type="submit" value="<?php __('download')?>" class="input in-submit"></td>
            </tr>
            </tbody></table>
            
            </form>
        </td>
    </tr>
        </tbody>
        
        
        <tbody>
    </tbody></table>
    </fieldset>
    
    
    </div>



</div>
<div>

</div>

    
<script type="text/javascript" src="<?php echo $this->webroot?>js/My97DatePicker/WdatePicker.js"></script>
<script type="text/javascript">
function sub_form(){
	$("form::first").submit( function () {
		if ($('#query-output').val() == 'web') {
			loading();
			 return true;
			}
		if ($('#query-output').val() == 'csv') {
			$("form::first").attr("action","<?php echo $this->webroot?>cdrreports/summary_reports/mismatch/unknowncarriers/");
			 return true;
			}
		 
		} );


	$("form::second").submit( function () {
		if ($('#query-output2').val() == 'web') {
			loading();
			 return true;
			}
		if ($('#query-output2').val() == 'csv') {
			$("form::second").attr("action","<?php echo $this->webroot?>cdrreports/summary_reports/mismatch/unknownratetable/");
			 return true;
			}
		 
		} );


	$("form::third").submit( function () {
		if ($('#query-output3').val() == 'web') {
			loading();
			 return true;
			}
		if ($('#query-output3').val() == 'csv') {
			$("form::third").attr("action","<?php echo $this->webroot?>cdrreports/summary_reports/mismatch/unknownrate/");
			 return true;
			}
		 
		} );
}

</script>