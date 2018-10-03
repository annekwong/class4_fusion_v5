<!--导入所有reoprt页面的input和select样式文件-->
<?php echo $this->element('magic_css_three');?>

<div id="title"> <h1>
<?php echo __('LCR Lists',true);?></h1> </div>

<div id="container">
<?php 
$mydata =$p->getDataArray();	$loop = count($mydata);

if($loop==0){?>
<div class="msg"><?php echo __('no_data_found',true);?></div>
<?php }else{?>
<ul id="stats-extra"  style="font-weight: bolder;font-size: 1.1em;color: #6694E3">
    <li id="stats-period">
    <span rel="helptip" class="helptip" id="ht-100012"><?php __('RealPeriod')?></span>
    <!--<span class="tooltip" id="ht-100012-tooltip">Period for which statistics exists in database</span>: 
    --><span><?php  echo $start;?></span> &mdash; <span><?php echo $end?></span></li>  
      <li id="stats-time"><?php __('QueryTime')?>: 2.2691 <?php __('sec')?></li></ul>
      



      <!-- ***************************************************************************************************************************************************** -->
  <!-- ****************************************普通输出******************************************* -->
        <!-- ***************************************************************************************************************************************************** -->
        <div id="toppage"></div>
<table class="list nowrap with-fields"  style="width: 100%">
<thead>
<tr>
<?php 

 	$c=count($show_field_array);
 	for ($ii=1;$ii<$c;$ii++){
 		echo  "<td rel='8'  width='10%'>&nbsp;&nbsp;&nbsp;&nbsp; ".__($show_field_array[$ii],true)."  &nbsp;&nbsp;</td>";

 	}
?>



  </tr>
    </thead>
 <tbody class="orig-calls">

		<?php 	 for ($i=0;$i<$loop;$i++) {
			 	?>
         <tr class=" row-2"   style="color: #4B9100">
         
         <?php 
         
    	for ($ii=1;$ii<$c;$ii++){
 		$f=$show_field_array[$ii];
 $field=$mydata[$i][0][$f];
 if(trim($field)==''){
echo  "<td  class='in-decimal'  style='text-align:center;color:#6694E3;'><strong  style='color:red;'>".__('Unknown',true)."</strong></td>";
 } else{	echo  " <td  class='in-decimal'  style='text-align:center;color:#6694E3;'>".$field ."</td>";}
 	
    	
    	}
         
         ?>

    </tr>
            
            
            
            <?php }?>
 </tbody>
 </table>
     			<div id="tmppage">
<?php echo $this->element('page');?>

</div>
      <?php }?>
  
     
     
     

 <?php //***********************报表查询参数*********************?>
<fieldset class="query-box"><legend><?php __('search')?></legend>

<script type="text/javascript">

//设置每个字段所对应的隐藏域
var _ss_ids_client = {'id_clients': 'query-id_clients', 'id_clients_name': 'query-id_clients_name', 'account': 'query-account', 'tz': 'query-tz', 'id_currencies': 'query-id_currencies'};

var _ss_ids_rate = {'id_rates': 'query-id_rates', 'id_rates_name': 'query-id_rates_name',	'account': 'query-account', 'tz': 'query-tz', 'id_currencies': 'query-id_currencies'};

var _ss_ids_card = {'id_cards': 'query-id_cards', 'id_cards_name': 'query-id_cards_name', 	'account': 'query-account', 'tz': 'query-tz', 'id_currencies': 'query-id_currencies'};

var _ss_ids_code_name = {'code_name': 'query-code_name'};
var _ss_ids_code = {'code': 'query-code', 'id_code_decks': 'query-id_code_decks'};

</script>
<?php echo $form->create ('Cdr', array ('url' => '/cdrreports/summary_reports/' ,'onsubmit'=>"if ($('#query-output').val() == 'web') loading();"));?>
<input   type="hidden"   value="searchkey"    name="searchkey"/>
<input class="input in-hidden" name="query[process]" value="1" id="query-process" type="hidden">
<input class="input in-hidden" name="query[order_by]" value="" id="query-order_by" type="hidden">

<input class="input in-hidden" name="query[id_clients]" value="" id="query-id_clients" type="hidden">
<input class="input in-hidden" name="query[id_resellers]" value="" id="query-id_resellers" type="hidden">
<input class="input in-hidden" name="query[account]" value="" id="query-account" type="hidden">
<input class="input in-hidden" name="query[id_rates]" value="" id="query-id_rates" type="hidden">
<input class="input in-hidden" name="query[id_cards]" value="" id="query-id_cards" type="hidden">
<table style="width: 1260px;" class="form list">
<col style="width: 80px;">
<col style="width: 135px;">
<col style="width: 50px;">
<col style="width: 135px;">
<col style="width: 70px;">
<col style="width: 130px;">
<col style="width: 140px;">
<tbody>

<tr class="period-block">
    <td class="label"><?php __('time')?>:</td>
    <td colspan="5" class="value">
    
    
    
    <table class="in-date"><tbody>
    <tr>

    <td>
    
    <table class="in-date">
<tbody>


<tr>
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
    <td><input type="text" id="query-start_date-wDt" class="wdate in-text input" onchange="setPeriod('custom')"   readonly="readonly"  onkeydown="setPeriod('custom')"
         value="" name="start_date"  style="margin-left: 0px; width: 156px;"></td>
    <td></td>
</tr>






</tbody></table>

    </td>
    <td><input type="text" id="query-start_time-wDt" onchange="setPeriod('custom')" onkeydown="setPeriod('custom')"
    	readonly="readonly" 
         style="width: 60px;" value="00:00:00" name="start_time" class="input in-text"></td>
    <td>&mdash;</td>
    <td><table class="in-date">
<tbody><tr>
    <td><input type="text" id="query-stop_date-wDt" class="wdate in-text input"  style="width: 120px;"    onchange="setPeriod('custom')"
    readonly="readonly" 
     onkeydown="setPeriod('custom')" value="" name="stop_date"></td>
    <td></td>
</tr>
</tbody></table>

    </td>
    <td><input type="text" id="query-stop_time-wDt" onchange="setPeriod('custom')"
    readonly="readonly" 
     onkeydown="setPeriod('custom')" style="width: 60px;" value="23:59:59" name="stop_time" class="input in-text"></td>
     
     <td style="padding: 0pt 10px;"><?php __('in')?></td>
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
     <option selected="selected" value="+0200">GMT +02:00</option>
     <option value="+0300">GMT +03:00</option>
     <option value="+0330">GMT +03:30</option><option value="+0400">GMT +04:00</option><option value="+0500">GMT +05:00</option><option value="+0600">GMT +06:00</option><option value="+0700">GMT +07:00</option><option value="+0800">GMT +08:00</option><option value="+0900">GMT +09:00</option><option value="+1000">GMT +10:00</option><option value="+1100">GMT +11:00</option><option value="+1200">GMT +12:00</option></select></td>
    
    </tr></tbody></table>

</td>
    <td class="buttons"><input type="submit" value="<?php echo __('query',true);?>" class="input in-submit"></td>
</tr>




<tr>
    <td class="label"> <?php __('Carriers')?>  </td>
    <td id="client_cell" class="value">
        <input type="text" id="query-id_clients_name" onclick="showClients()" style="width: 73%;" readonly="1" value="" name="query[id_clients_name]" class="input in-text">        
        
        <img width="9" height="9" onclick="showClients()" class="img-button" src="<?php echo $this->webroot?>images/search-small.png">
        <img width="9" height="9" onclick="ss_clear('client', _ss_ids_client)" class="img-button" src="<?php echo $this->webroot?>images/delete-small.png">
    </td>
        <td class="label"><?php echo __('code_name')?>:</td>
    <td class="value">
        <input type="text" id="query-code_name" onclick="ss_code(undefined, _ss_ids_code_name)" style="width: 73%;" value="" name="query[code_name]" class="input in-text">       
         <img width="9" height="9" onclick="ss_code(undefined, _ss_ids_code_name)" class="img-button" src="<?php echo $this->webroot?>images/search-small.png">
          <img width="9" height="9" onclick="ss_clear('card', _ss_ids_code_name)" class="img-button" src="<?php echo $this->webroot?>images/delete-small.png">
    </td>
    

    
    <td class="label"><?php __('code')?>:</td>
    <td class="value">
        <input type="text" id="query-code" onclick="ss_code(undefined, _ss_ids_code)" style="width: 73%;" value="" name="query[code]" class="input in-text">        
        <img width="9" height="9" onclick="ss_code(undefined, _ss_ids_code)" class="img-button" src="<?php echo $this->webroot?>images/search-small.png">
         <img width="9" height="9" onclick="ss_clear('card', _ss_ids_code)" class="img-button" src="<?php echo $this->webroot?>images/delete-small.png">
    </td>
    <td valign="top" rowspan="10" style="padding-left: 10px;" class="label">
        <div align="left"><?php echo __('Show Fields',true);?>:</div>
        <select id="query-fields" style="width: 99%; height: 210px;" multiple="1" name="query[fields][]" class="input in-select">
       
      
        <option selected="selected" value="time"><?php __('Call Date')?></option>
        <option value="client_id"><?php __('ID Client')?></option>
       <option value="client_name"> <?php __('Client')?></option>
        <option value="term_country"><?php __('Country')?></option>
        <option selected="selected" value="term_code_name"><?php __('Code Name')?></option>
        <option selected="selected" value="term_code"><?php __('Code')?></option>
       
        <option value="bill_minutes"><?php __('Billed Time')?></option>
        <option selected="selected" value="rate_table_name"><?php __('Rate')?></option>
        <option selected="selected" value="cost"><?php __('Cost')?></option>
       
        <option value="release_cause_from_protocol_stack"><?php __('Res Code')?></option>
        <option value="origination_call_id"><?php __('Call ID')?></option>
    
        <option value="origination_source_number"><?php __('SRC Number IN')?></option>
        <option value="origination_destination_number"><?php __('SRC Number OUT')?></option>
       
        <option value="termination_source_number"><?php __('DST Number IN')?></option>
        <option value="termination_destination_number"><?php __('DST Number OUT')?></option>
       
        <option value="start_time_of_date"><?php __('Setup Time')?></option>
        <option value="answer_time_of_date"><?php __('Connect Time')?></option>
        <option value="release_tod"><?php __('Disc Time')?></option>

        
        </select>    </td>
</tr>


<tr>
    
    <td class="label"> <?php echo __('class4-server',true);?>:</td>
    <td class="value">
 		<?php echo $form->input('server_ip',
 		array('options'=>$server,'empty'=>'','label'=>false ,'div'=>false,'type'=>'select'));?>
		
    </td>
    <td class="label"><span rel="helptip" class="helptip" id="ht-100001"><?php __('codedecks')?></span><span class="tooltip" id="ht-100001-tooltip"><b><?php __('Use pre-assigned')?></b> &mdash; <?php __('means usage of code decks assigned to each pulled client or rate table')?>.<br><br><?php __('If you will')?> <b><?php __('specify')?></b> <?php __('a code deck, all code names will be rewritten using names from selected code deck, so all data will be unified by code names')?>.</span>:</td>
    <td class="value">
  		<?php echo $form->input('code_deck',	array('options'=>$code_deck,'empty'=>'','label'=>false ,'div'=>false,'type'=>'select'));?>
    </td>
<td class="label"><?php __('currency')?> </td>
    <td id="client_cell" class="value">
		<?php echo $form->input('currency',	array('options'=>$currency,'empty'=>'','label'=>false ,'div'=>false,'type'=>'select'));?>
    </td>
    </tr>


<tr>
    <td class="label"> <?php echo __('egress',true);?>:</td>
    <td class="value">
 		<?php echo $form->input('egress_alias',
 		array('options'=>$egress,'empty'=>'','label'=>false ,'div'=>false,'type'=>'select'));?>
		
    </td>

            <td class="label"><?php echo __('ingress',true);?>:</td>
    <td class="value">
    		<?php 
   		echo $form->input('ingress_alias',
 		array('options'=>$ingress,'empty'=>'','label'=>false ,'div'=>false,'type'=>'select'));?>
 
    </td>
    
        <td class="label"><?php echo __('type')?>:</td>
    <td class="value">
 		<?php 
 		$type=array(''=>__('all',true),'orig'=>__('origination',true),'term'=>__('termination',true));
 		echo $form->input('report_type',
 		array('options'=>$type,'label'=>false ,'div'=>false,'type'=>'select'));?>
    </td>
    </tr>

<tr>
    <td class="label"><?php echo __('Result/Code',true);?>:</td>
    <td class="value"><select id="query-res_status" style="width: 100px;" name="query[res_status]" class="input in-select"><option value=""><?php __('all')?></option><option value="success"><?php __('success')?></option><option value="busy"><?php __('busy')?></option><option value="nochannel"><?php __('nochannel')?></option><option value="error"><?php __('error')?></option></select> <input type="text" id="query-disconnect_cause" style="width: 53px;" value="" name="query[disconnect_cause]" class="input in-text"></td>
    
    <td class="label"><?php echo __('Cost',true);?>:</td>
    <td class="value"><select id="query-cost" name="query[cost]" class="input in-select"><option value=""><?php __('all')?></option><option value="nonzero"><?php __('non-zero')?></option><option value="zero"><?php __('zero')?></option></select></td>
    
    <td class="label"><?php echo __('Duration',true);?>:</td>
    <td class="value"><select id="query-duration" name="query[duration]" class="input in-select">
    <option    value=""  selected="selected"><?php __('all')?></option>
    <option  value="nonzero"><?php __('non-zero')?></option><option value="zero"><?php __('zero')?></option></select></td>
</tr>
<tr>
    <td class="label"><?php echo __('dnis',true);?> :</td>
    <td class="value"><input type="text" id="query-dst_number" value="" name="query[dst_number]" class="input in-text"></td>
    
    <td class="label"><?php echo __('ani',true);?>:</td>
    <td class="value"><input type="text" id="query-src_number" value="" name="query[src_number]" class="input in-text"></td>
    
    <td class="label"><span rel="helptip" class="helptip" id="ht-100002"><?php echo __('Interval second',true);?></span><span class="tooltip" id="ht-100002-tooltip"><?php __('Duration interval in seconds')?></span>:</td>
    <td class="value">
        <input type="text" id="query-interval_from" class="in-digits input in-text" style="width: 53px;" value="" name="query[interval_from]"> 
        &mdash; 
        <input type="text" id="query-interval_to" class="in-digits input in-text" style="width: 54px;" value="" name="query[interval_to]">    </td>
</tr>



<tr class="output-block">
    <td class="label"><span><?php __('Output',true)?>:</span></td>
    <td class="value"><select id="query-output" onchange="repaintOutput();" name="query[output]" class="input in-select"><option value="web"><?php __('Web')?></option><option value="csv"><?php __('Excel CSV')?></option><option value="xls"><?php __('Excel XLS')?></option><option value="delayed"><?php __('Delayed CSV')?></option></select></td>
    <td colspan="4" class="label"><div></div></td>
</tr>







</tbody></table>
<?php echo $form->end();?>
</fieldset>


<div style="display: none;" id="charts_holder">
        

<script type="text/javascript">
//<![CDATA[

  function showCards ()
  {
      ss_ids_custom['card'] = _ss_ids_card;
     // val = $('#query-client_type').val();//客户类型
      //tz = $('#query-tz').val();

      winOpen('<?php echo $this->webroot?>clients/ss_card?type=2&types=8', 500, 530);

  }
tz = $('#query-tz').val();
function showClients ()
{
    ss_ids_custom['client'] = _ss_ids_client;
    winOpen('<?php echo $this->webroot?>clients/ss_client?types=2&type=0', 500, 530);

}

function showRsellers()
{
    ss_ids_custom['reseller'] = _ss_ids_reseller;
    winOpen('<?php echo $this->webroot?>resellers/ss_reseller?type=2&types=8', 500, 530);

}

function repaintOutput() {
    if ($('#query-output').val() == 'web') {
        $('#output-sub').show();
    } else {
        $('#output-sub').hide();
    }
}
repaintOutput();
//]]>
</script>

</div>
<div>

</div>

<div id="livemargins_control" style="position: absolute; display: none; z-index: 9999;"><img width="77" height="5" style="position: absolute; left: -77px; top: -5px;" src="chrome://livemargins/skin/monitor-background-horizontal.png">	
<img style="position: absolute; left: 0pt; top: -5px;" src="chrome://livemargins/skin/monitor-background-vertical.png">
	<img style="position: absolute; left: 1px; top: 0pt; opacity: 0.5; cursor: pointer;" onmouseout="this.style.opacity=0.5" onmouseover="this.style.opacity=1" src="chrome://livemargins/skin/monitor-play-button.png" id="monitor-play-button"></div>
	<script type="text/javascript" src="<?php echo $this->webroot?>js/My97DatePicker/WdatePicker.js"></script>