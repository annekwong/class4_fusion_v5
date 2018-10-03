<!--导入所有reoprt页面的input和select样式文件-->
<?php echo $this->element('magic_css_three');?>

<div id="title"> <h1> <?php __('ChargesReport')?></h1> </div>
<div id="container">
<?php 
 $size=count($client_org);
if($size==0){?>
<div class="msg"><?php echo __('no_data_found',true);?></div>
<?php }else{?>
<ul id="stats-extra"  style="font-weight: bolder;font-size: 1.1em;color: #6694E3">
    <li id="stats-period">
    <span rel="helptip" class="helptip" id="ht-100012"><?php __('RealPeriod')?></span>
    <!--<span class="tooltip" id="ht-100012-tooltip">Period for which statistics exists in database</span>: 
    --><span><?php  echo $start;?></span> &mdash; <span><?php echo $end?></span></li>  
      <li id="stats-time"><?php __('QueryTime')?>: 2.2691<?php __('sec')?></li></ul>
      
      <!-- ***************************************************************************************************************************************************** -->
  <!-- ****************************************subgroups******************************************* -->
        <!-- ***************************************************************************************************************************************************** -->
<?php if(!empty($show_subgroups)){
//子统计字段
if(!empty($show_subtotals)){
 	$sub_total_six_seconds=0;
	$sub_total_minutes=0;
	$sub_total_bill_time=0;
 	$sub_total_cost=0;
	
}
	
	
	
	$pre_field='';
	$curr_field='';
	$next_field='';
	$field_name=$group_by_field_arr[0];
	$curr_field=$client_org[0][0][$field_name];
	$pre_field=$curr_field;

	

	//条件字段
	$t1='';
	$t2='';
	$t3='';
	$t4='';
	$t5='';
	$t6='';
		$t7='';
			$t8='';
				$t9='';
$for_td1='';
$for_td2='';
$for_td3='';
$for_td4='';
$for_td5='';
$for_td6='';
$for_td7='';
$for_td8='';
$for_td9='';

 			
if(!empty($reseller_name)){
	$t1= " <td rel='8' width='10%'>&nbsp;&nbsp;&nbsp;".__('reseller_name',true)."&nbsp;&nbsp;&nbsp;</td>";
  $for_td1= " <td  class='in-decimal'  style='text-align:center;color:#6694E3;'>".$reseller_name ."</td>";
}
if(!empty($client_name)){
	$t2= " <td rel='8' width='10%'>&nbsp;&nbsp;&nbsp;".__('client_name',true)."&nbsp;&nbsp;&nbsp;</td>";
  $for_td2= " <td  class='in-decimal'  style='text-align:center;color:#6694E3;'>".$client_name ."</td>";
}
if(!empty($server_ip)){
	$t3= " <td rel='8' width='10%'>&nbsp;&nbsp;&nbsp;".__('server_ip',true)."&nbsp;&nbsp;&nbsp;</td>";
  $for_td3= " <td  class='in-decimal'  style='text-align:center;color:#6694E3;'>".$server_ip ."</td>";
}
if(!empty($egress_post)){
	$t4= " <td rel='8' width='10%'>&nbsp;&nbsp;&nbsp;".__('egress_post',true)."&nbsp;&nbsp;&nbsp;</td>";
  $for_td4= " <td  class='in-decimal'  style='text-align:center;color:#6694E3;'>".$egress_post ."</td>";
}

if(!empty($code_deck_name)){
	$t5= " <td rel='8' width='10%'>&nbsp;&nbsp;&nbsp;".__('code_deck_name',true)."&nbsp;&nbsp;&nbsp;</td>";
  $for_td5= " <td  class='in-decimal'  style='text-align:center;color:#6694E3;'>".$code_deck_name ."</td>";
}

if(!empty($code_name)){
	$t6= " <td rel='8' width='10%'>&nbsp;&nbsp;&nbsp;".__('code_name',true)."&nbsp;&nbsp;&nbsp;</td>";
  $for_td6= " <td  class='in-decimal'  style='text-align:center;color:#6694E3;'>".$code_name ."</td>";
}

if(!empty($code)){
	$t7= " <td rel='8' width='10%'>&nbsp;&nbsp;&nbsp;".__('code',true)."&nbsp;&nbsp;&nbsp;</td>";
  $for_td7= " <td  class='in-decimal'  style='text-align:center;color:#6694E3;'>".$code ."</td>";
}
if(!empty($currency_post)){
	$t8= " <td rel='8' width='10%'>&nbsp;&nbsp;&nbsp; ".__('currency_post',true)."&nbsp;&nbsp;&nbsp;</td>";
$for_td8= " <td  class='in-decimal'  style='text-align:center;color:#6694E3;'>".$currency_post ."</td>";
}
if(!empty($rate_name)){
	$t9= " <td rel='8' width='10%'>&nbsp;&nbsp;&nbsp; ".__('RateTable',true)."&nbsp;&nbsp;&nbsp;</td>";
	$for_td9= " <td  class='in-decimal'  style='text-align:center;color:#6694E3;'>".$rate_name ."</td>";
} 



	
	//分组字段头
$groupby_th='';
$groupby_td_v='';//分组字段值
$groupby_subth='';//自统计字段
 	$c=count($group_by_field_arr);
 	for ($ii=1;$ii<$c;$ii++){
 		$groupby_th=$groupby_th. "<td rel='8'  width='10%'>&nbsp;&nbsp;&nbsp;&nbsp; ".__($group_by_field_arr[$ii],true)."  &nbsp;&nbsp;</td>";
 		if(!empty($show_subtotals)){
 			$tmp_str='';
 			if($ii==$c-1){
 				$tmp_str="子统计";
 			}
 			$groupby_subth=$groupby_subth. "<td rel='8'  width='10%'>&nbsp;&nbsp;&nbsp;&nbsp; ".$tmp_str."  &nbsp;&nbsp;</td>";
 			
 		}
 	}
 	
 $head_div=" <div class='group-title'  >".  __($field_name,true).": <span>".$curr_field."</span><br></div>";

$table_head="<table class='list nowrap with-fields'  ><thead><tr>$t1 $t2   $t3   $t4  $t5  $t6 $t7  $t8  $t9  $groupby_th
              <td width='10%' rel='0' >&nbsp;6秒数&nbsp;</td>
           <td width='6%' rel='1' ><span  class='helptip' id='ht-100001'>通话分钟数</span><span class='tooltip' id='ht-100001-tooltip'></span></td>
            <td width='6%' rel='2' class='cset-1'>&nbsp;计费分钟数 &nbsp;</td>
             <td width='6%' rel='2' class='cset-1'>&nbsp;  计费金额 &nbsp;</td>
            </tr></thead><tbody class='orig-calls'>" ;
$for_tr='';//循环输出的tr
$sub_tr='';//子统计的tr
 $table_footer="</tbody></table>";
 
 
 //输出头
 echo $head_div,$table_head;
 
 //循环输出表格
   $size=count($client_org);
   for ($i=0;$i<$size;$i++){
   	$groupby_td_v='';//重置分组字段td
   	//生成分组字段td
    	for ($ii=1;$ii<$c;$ii++){
 		$f=$group_by_field_arr[$ii];
 $field=$client_org[$i][0][$f];


 if(trim($field)==''){
 $groupby_td_v=$groupby_td_v. "<td  class='in-decimal'  style='text-align:center;color:#6694E3;'><strong  style='color:red;'>".__('Unknown',true)."</strong></td>";
 } else{	$groupby_td_v=$groupby_td_v. " <td  class='in-decimal'  style='text-align:center;color:#6694E3;'>".$field ."</td>";}
 	
    	
    	}
  
 

 	
 	//循环的表格
 	$for_tr="	<tr class=' row-2'   style='color: #4B9100'>$for_td1 $for_td2  $for_td3  $for_td4  $for_td5 $for_td6  $for_td7  $for_td8 $for_td9  $groupby_td_v
   <td class='in-decimal'><strong>".number_format($client_org[$i][0]['total_six_seconds'],0) .
 	 " </strong></td> <td class='in-decimal'><strong>" . number_format($client_org[$i][0]['total_minutes'], 0)." </strong></td>
   <td class='in-decimal'>". number_format($client_org[$i][0]['total_bill_time'], 0)."</td>
   <td class='in-decimal'>". number_format($client_org[$i][0]['total_cost'], 0)."</td>
   </tr>";
if($i>0){
	$field_name=$group_by_field_arr[0];
	$curr_field=$client_org[$i][0][$field_name];//上一个分组值
	$pre_field=$client_org[$i-1][0][$field_name];//当前分组值
 	 }
   	 
if($curr_field!=$pre_field){
   	 	 	  	//只要当前和前一个不相等  就生成子统计tr
 	if(!empty($show_subtotals)){
	$sub_tr="	<tr class='subtotals row-2'   style='color: #4B9100'>$for_td1 $for_td2  $for_td3  $for_td4  $for_td5 $for_td6  $groupby_subth
    <td class='in-decimal'><strong>".number_format($sub_total_six_seconds,0) .
 	 " </strong></td> <td class='in-decimal'><strong>".number_format($sub_total_minutes, 0)." </strong></td>
   <td class='in-decimal'>". number_format($sub_total_bill_time, 0)."</td>
   <td class='in-decimal'>". number_format($sub_total_cost, 0)."</td></tr>";
 	}
   	 	
   	 	
   	 	echo  $sub_tr;
 	$sub_total_six_seconds=0;
	$sub_total_minutes=0;
	$sub_total_bill_time=0;
 	$sub_total_cost=0;
   	  echo  $table_footer;
   	   $head_div=" <div class='group-title'  >".  __($field_name,true).": <span>".$curr_field."</span><br></div>";
   	   echo $head_div,$table_head;
   	 	 echo $for_tr;
   	 	 	if(!empty($show_subtotals)){
  $sub_total_six_seconds=$sub_total_six_seconds+$client_org[$i][0]['total_six_seconds'];
	$sub_total_minutes=$sub_total_minutes+$client_org[$i][0]['total_minutes'];
	$sub_total_bill_time=$sub_total_bill_time+$client_org[$i][0]['total_bill_time'];
 	$sub_total_cost=$sub_total_cost+$client_org[$i][0]['total_cost'];
   	 	 	}
   	 }else{
   	 	//如果当前和上一个相等就统计一次
   	 	 	if(!empty($show_subtotals)){
  $sub_total_six_seconds=$sub_total_six_seconds+$client_org[$i][0]['total_six_seconds'];
	$sub_total_minutes=$sub_total_minutes+$client_org[$i][0]['total_minutes'];
	$sub_total_bill_time=$sub_total_bill_time+$client_org[$i][0]['total_bill_time'];
 	$sub_total_cost=$sub_total_cost+$client_org[$i][0]['total_cost'];
   	 	 	}
 echo $for_tr;
  	 }
      	 	 if($i==$size-1){
   	 	  	if(!empty($show_subtotals)){
	$sub_tr="	<tr class='subtotals row-2'   style='color: #4B9100'>$for_td1 $for_td2  $for_td3  $for_td4  $for_td5 $for_td6  $groupby_subth
    <td class='in-decimal'><strong>".number_format($sub_total_six_seconds,0) .
 	 " </strong></td> <td class='in-decimal'><strong>".number_format($sub_total_minutes, 0)." </strong></td>
   <td class='in-decimal'>". number_format($sub_total_bill_time, 0)."</td>
   <td class='in-decimal'>". number_format($sub_total_cost, 0)."</td></tr>";
 	}
   	 		echo  $sub_tr;
   	 	 	 echo  $table_footer;
   	 	 }
   }


}?>


















<?php if(!empty($show_comm)){?>

      <!-- ***************************************************************************************************************************************************** -->
  <!-- ****************************************普通输出******************************************* -->
        <!-- ***************************************************************************************************************************************************** -->
<table class="list nowrap with-fields"  style="width: 80%">
<thead>
<tr>
          
 <?php if(!empty($reseller_name)){echo " <td  class='in-decimal' width='10%' style='text-align:center;color:#6694E3;'>".__('reseller_name',true) ."</td>";}?>
 <?php if(!empty($client_name)){echo " <td  class='in-decimal' width='10%' style='text-align:center;color:#6694E3;'>".__('client_name',true) ."</td>";}?>
  <?php if(!empty($server_ip)){echo " <td  class='in-decimal' width='10%' style='text-align:center;color:#6694E3;'>".__('server_ip',true) ."</td>";}?>
  <?php if(!empty($egress_post)){echo " <td  class='in-decimal' width='10%'  style='text-align:center;color:#6694E3;'>".__('egress_post',true) ."</td>";}?>
    <?php if(!empty($code_deck_name)){echo " <td  class='in-decimal' width='10%' style='text-align:center;color:#6694E3;'>".__('code_deck_name',true) ."</td>";}?>
   <?php if(!empty($code_name)){echo " <td  class='in-decimal' width='10%' style='text-align:center;color:#6694E3;'>".__('code_name',true) ."</td>";}?>
  <?php if(!empty($code)){echo " <td  class='in-decimal' width='10%' style='text-align:center;color:#6694E3;'>".__('code',true) ."</td>";}?>
 <?php if(!empty($currency_post)){echo " <td  class='in-decimal'width='10%'  style='text-align:center;color:#6694E3;'>".__('currency_post',true) ."</td>";}?>
   <?php if(!empty($rate_name)){echo " <td  class='in-decimal' width='10%' style='text-align:center;color:#6694E3;'>".__('rate_name',true) ."</td>";}?>

 <?php

 //输出分组的字段

 if(!empty($group_by_field_arr)){
 	 
 	$c=count($group_by_field_arr);

 	for ($i=0;$i<$c;$i++){
 		echo " <td rel='8'  width='10%'>&nbsp;&nbsp;&nbsp;&nbsp; ".__($group_by_field_arr[$i],true)."  &nbsp;&nbsp;</td>";
 	}
 	
 }?>
 

 


           <td width="10%" rel="0" >&nbsp;<?php __('six_seconds')?> &nbsp;</td>
           <td width="6%" rel="1" ><span  class="helptip" id="ht-100001"><?php __('duration')?></span><span class="tooltip" id="ht-100001-tooltip">
        <?php __('avgratehelp')?></span></td>

       
     
            <td width="6%" rel="2" class="cset-1">&nbsp; <?php __('bill_time')?>&nbsp;</td>
              <td width="6%" rel="2" class="cset-1">&nbsp; <?php __('bill_amount')?>&nbsp;</td>
           

           
        </tr>
    </thead>
    
  <tbody class="orig-calls">

            
            
            
            <?php   $size=count($client_org);     for ($i=0;$i<$size;$i++){?>
         <tr class=" row-2"   style="color: #4B9100">
            
       
 <?php if(!empty($reseller_name)){echo " <td  class='in-decimal'  style='text-align:center;color:#6694E3;'>".$reseller_name ."</td>";}?>
 <?php if(!empty($client_name)){echo " <td  class='in-decimal'  style='text-align:center;color:#6694E3;'>".$client_name ."</td>";}?>
  <?php if(!empty($server_ip)){echo " <td  class='in-decimal'  style='text-align:center;color:#6694E3;'>".$server_ip ."</td>";}?>
  <?php if(!empty($egress_post)){echo " <td  class='in-decimal'  style='text-align:center;color:#6694E3;'>".$egress_post ."</td>";}?>
    <?php if(!empty($code_deck_name)){echo " <td  class='in-decimal'  style='text-align:center;color:#6694E3;'>".$code_deck_name ."</td>";}?>
   <?php if(!empty($code_name)){echo " <td  class='in-decimal'  style='text-align:center;color:#6694E3;'>".$code_name ."</td>";}?>
  <?php if(!empty($code)){echo " <td  class='in-decimal'  style='text-align:center;color:#6694E3;'>".$code ."</td>";}?>
 <?php if(!empty($currency_post)){echo " <td  class='in-decimal'  style='text-align:center;color:#6694E3;'>".$currency_post ."</td>";}?>
   <?php if(!empty($rate_name)){echo " <td  class='in-decimal'  style='text-align:center;color:#6694E3;'>".$rate_name ."</td>";}?>
 <?php

 //输出分组的字段
 if(!empty($group_by_field_arr)){
 	$c=count($group_by_field_arr);
 	for ($ii=0;$ii<$c;$ii++){
 		$f=$group_by_field_arr[$ii];
 $field=$client_org[$i][0][$f];
 if(trim($field)==''){
 echo "<td  class='in-decimal'  style='text-align:center;color:#6694E3;'><strong  style='color:red;'>".__('Unknown',true)."</strong></td>";
 } else{	echo " <td  class='in-decimal'  style='text-align:center;color:#6694E3;'>".$field ."</td>";}
 	}
 	
 	
 }?>

   <td class="in-decimal"><strong><?php  echo  number_format($client_org[$i][0]['total_six_seconds'], 0); ?> </strong></td>
   <td class="in-decimal"><strong><?php  echo  number_format($client_org[$i][0]['total_minutes'], 0); ?> </strong></td>
   <td class="in-decimal"><?php  echo  number_format($client_org[$i][0]['total_bill_time'], 0); ?></td>
 <td class="in-decimal"><?php  echo  number_format($client_org[$i][0]['total_cost'], 3); ?></td>
    </tr>
            
            
            
            <?php }?>
 </tbody>
 </table>
     
      <?php }}?>
  
     
     
     
     
     
     


 
 
 
 
 
 
 
 
 
 
 <?php //***********************报表查询参数*********************?>
<fieldset class="query-box"><legend><?php __('search')?></legend>

<script type="text/javascript">

//设置每个字段所对应的隐藏域
var _ss_ids_client = {'id_clients': 'query-id_clients', 'id_clients_name': 'query-id_clients_name', 'account': 'query-account', 'tz': 'query-tz', 'id_currencies': 'query-id_currencies'};
var _ss_ids_reseller = {'id_resellers': 'query-id_resellers', 'id_resellers_name': 'query-id_resellers_name', 'account': 'query-account', 'tz': 'query-tz', 'id_currencies': 'query-id_currencies'};
var _ss_ids_card = {'id_cards': 'query-id_cards', 'id_cards_name': 'query-id_cards_name', 
		'account': 'query-account', 'tz': 'query-tz', 'id_currencies': 'query-id_currencies'};

var _ss_ids_rate = {'id_rates': 'query-id_rates', 'id_rates_name': 'query-id_rates_name',	'account': 'query-account', 'tz': 'query-tz', 'id_currencies': 'query-id_currencies'};


var _ss_ids_code_name = {'code': 'query-code','code_name': 'query-code_name', 'id_code_decks': 'query-id_code_decks'};
var _ss_ids_code = {'code': 'query-code', 'id_code_decks': 'query-id_code_decks'};

</script>
<?php echo $form->create ('Cdr', array ('url' => '/egressanalysis/summary_reports/' ,'onsubmit'=>"if ($('#query-output').val() == 'web') loading();"));?>
<input   type="hidden"   value="searchkey"    name="searchkey"/>
<input class="input in-hidden" name="query[process]" value="1" id="query-process" type="hidden">
<input class="input in-hidden" name="query[order_by]" value="" id="query-order_by" type="hidden">

<input class="input in-hidden" name="query[id_clients]" value="" id="query-id_clients" type="hidden">
<input class="input in-hidden" name="query[id_resellers]" value="" id="query-id_resellers" type="hidden">
<input class="input in-hidden" name="query[id_cards]" value="" id="query-id_cards" type="hidden">
<input class="input in-hidden" name="query[account]" value="" id="query-account" type="hidden">
<input class="input in-hidden" name="query[id_rates]" value="" id="query-id_rates" type="hidden">
<table style="width: 1160px;" class="form">
<col style="width: 80px;">
<col style="width: 235px;">

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
     
     <td>
     
     
      		<?php

$r=array(''=>__('alltime',true),   'YYYY-MM-DD  HH24:MI:SS'=>__('bysec',true), 'YYYY-MM-DD  HH24:MI:00'=>__('bymin',true),   'YYYY-MM-DD  HH24:00:00'=>__('byhours',true),    'YYYY-MM-DD'=>__('byday',true),    'YYYY-MM'=>__('bymonth',true),    'YYYY'=>__('byyear',true) ); 		 		
if(!empty($_POST)){
	if(isset($_POST['group_by_date'])){
		$s=$_POST['group_by_date'];
		
	}else{
		$s='';
	}
}else{
	
	$s='';
}
echo $form->input('group_by_date',
 		array('options'=>$r,'label'=>false ,'id'=>'query-group_by_date','style'=>'width: 130px;','name'=>'group_by_date',
 		'div'=>false,'type'=>'select','selected'=>$s));?>
     
     </td>
    </tr></tbody></table>

</td>
    <td class="buttons"><input type="submit" value="Query" class="input in-submit"></td>
</tr>









<tr>
    <td class="label"> <?php  __('Carriers')?>  </td>
    <td id="client_cell" class="value">
        <input type="text" id="query-id_clients_name" onclick="showClients()" style="width: 83%;" readonly="1" value="" name="query[id_clients_name]" class="input in-text">        
        
        <img width="9" height="9" onclick="showClients()" class="img-button" src="<?php echo $this->webroot?>images/search-small.png">
        <img width="9" height="9" onclick="ss_clear('client', _ss_ids_client)" class="img-button" src="<?php echo $this->webroot?>images/delete-small.png">
    </td>
    
    <td class="label"><?php echo __('code_name')?>:</td>
    <td class="value">
        <input type="text" id="query-code_name" onclick="ss_code(undefined, _ss_ids_code_name)" style="width: 83%;" value="" name="query[code_name]" class="input in-text">       
         <img width="9" height="9" onclick="ss_code(undefined, _ss_ids_code_name)" class="img-button" src="<?php echo $this->webroot?>images/search-small.png">
          <img width="9" height="9" onclick="ss_clear('card', _ss_ids_code_name)" class="img-button" src="<?php echo $this->webroot?>images/delete-small.png">
    </td>
    
    <td class="label"><?php __('code')?>:</td>
    <td class="value">
        <input type="text" id="query-code" onclick="ss_code(undefined, _ss_ids_code)" style="width: 85%;" value="" name="query[code]" class="input in-text">        
        <img width="9" height="9" onclick="ss_code(undefined, _ss_ids_code)" class="img-button" src="<?php echo $this->webroot?>images/search-small.png">
         <img width="9" height="9" onclick="ss_clear('card', _ss_ids_code)" class="img-button" src="<?php echo $this->webroot?>images/delete-small.png">
    </td>
</tr>


<tr>
    <td class="label"> <?php __('egress')?></td>
    <td class="value">
 		<?php echo $form->input('egress_alias',
 		array('options'=>$egress,'empty'=>'  ','label'=>false ,'div'=>false,'type'=>'select'));?>
		
    </td>
    <td class="label"><?php __('RateTable')?></td>
    <td class="value">
        <input type="text" id="query-id_rates_name" onclick="ss_rate(_ss_ids_rate)" style="width: 83%;" value="" name="query[rate_name]" class="input in-text">        
        <img width="9" height="9" onclick="ss_rate(_ss_ids_rate)" class="img-button" src="<?php echo $this->webroot?>images/search-small.png">
         <img width="9" height="9" onclick="ss_clear('card', _ss_ids_rate)" class="img-button" src="<?php echo $this->webroot?>images/delete-small.png">
    </td>
    
        <td class="label"><?php __('currency')?></td>
    <td class="value">
 		<?php echo $form->input('currency',	array('options'=>$currency,'empty'=>'   ','label'=>false ,'div'=>false,'type'=>'select'));?>
    </td>
    </tr>


<tr>




    <td class="label"> <?php echo __('class4-server',true);?>:</td>
    <td class="value">
 		<?php echo $form->input('server_ip',
 		array('options'=>$server,'empty'=>'    ','label'=>false ,'div'=>false,'type'=>'select'));?>
		
    </td>
    
    <td class="label"> <?php __('codedecks')?><span class="tooltip" id="ht-100001-tooltip"><b><?php echo __('Use pre-assigned',true);?></b> &mdash; means usage of code decks assigned to each pulled client or rate table.<br><br>If you will <b>specify</b> a code deck, all code names will be rewritten using names from selected code deck, so all data will be unified by code names.</span>:</td>
    <td class="value">
  		<?php echo $form->input('code_deck',	array('options'=>$code_deck,'empty'=>'  ','label'=>false ,'div'=>false,'type'=>'select'));?>
    </td>
        <td class="label"></td>
    <td class="value"> </td>
    </tr>



<tr class="output-block">
    <td class="label"><span><?php __('output')?></span></td>
    <td class="value">
    <select id="query-output" onchange="repaintOutput();" name="query[output]" class="input in-select">
    <option selected="selected" value="web"><?php __('web')?></option><option value="csv">Excel CSV</option></select></td>
    <td colspan="4" class="label"><div></div></td>
</tr>

<tr>
    <td class="label"><?php echo __('Group By',true);?> #1:</td>
    <td class="value">
    
    	<?php
$groupby=array('client_name'=>__('Carriers',true)   ,'egress_alias'=>__('egress',true)   ,'termination_source_host_name'=>'class4-server',
'term_code'=>__('code',true),'term_code_name'=>__('code_name',true),
'term_country'=>__('country',true),   'rate_table_name'=>__('RateTable',true));
    	echo $form->input('group_by1',
 		array('name'=>'group_by[0]','options'=>$groupby,'empty'=>'  ','label'=>false ,'div'=>false,'type'=>'select'));?>
   
    </td>
    
    <td class="label"><?php echo __('Group By',true);?> #2:</td>
    <td class="value">
    
        	<?php

    	echo $form->input('group_by2',
 		array('name'=>'group_by[1]','options'=>$groupby,'empty'=>'  ','label'=>false ,'div'=>false,'type'=>'select'));?>

    </td>
    
    <td class="label"><?php echo __('Group By',true);?> #3:</td>
    <td class="value">
            	<?php

    	echo $form->input('group_by3',
 		array('name'=>'group_by[2]','options'=>$groupby,'empty'=>'  ','label'=>false ,'div'=>false,'type'=>'select'));?>
    </td>
</tr>


<tr>
    <td class="label"><?php echo __('Group By',true);?> #4:</td>
    <td class="value">
    
    	<?php

    	echo $form->input('group_by4',
 		array('name'=>'group_by[3]','options'=>$groupby,'empty'=>'  '      ,   'label'=>false ,'div'=>false,'type'=>'select'));?>
   
    </td>
    
    <td class="label"><?php echo __('Group By',true);?> #5:</td>
    <td class="value">
    
        	<?php

    	echo $form->input('group_by5',
 		array('name'=>'group_by[4]','options'=>$groupby,'empty'=>'  ','label'=>false ,'div'=>false,'type'=>'select'));?>

    </td>
    
    <td class="label"><?php echo __('Group By',true);?> #6:</td>
    <td class="value">
            	<?php

    	echo $form->input('group_by6',
 		array('name'=>'group_by[5]','options'=>$groupby,'empty'=>'  ','label'=>false ,'div'=>false,'type'=>'select'));?>
    </td>
</tr>

<tr id="output-sub">
    <td class="label">&nbsp;</td>
    <td class="value">&nbsp;</td>
    
    <td class="label"></td>
    <td class="value">
        <input type="checkbox"   <?php
         if(isset($_POST['query']['output_subgroups'])){
         	
        	if(!empty($_POST['query']['output_subgroups'])){ 
        		echo "checked='checked'  value='true'"; 
        	}else{
        			echo "value='false'";
        		}
         
         }else{
         
         echo "value='false'";
         }?>
        onclick="$(this).attr('checked')==true?$(this).attr('value','true'):$(this).attr('value','false');"
        id="query-output_subgroups" value="false" name="query[output_subgroups]" class="input in-checkbox">        <label for="query-output_subgroups"><span rel="helptip" class="helptip" id="ht-100146"><?php echo __('Show subgroups',true);?></span><span class="tooltip" id="ht-100146-tooltip">Show data grouped by separate tables if used more than 2 "Group By" options. Any sort options will be applied after all "Group By" fields settings are applied.</span></label>
    </td>
    
    <td class="label"></td>
    <td class="value">
        <input type="checkbox" id="query-output_subtotals"
        
        <?php
         if(isset($_POST['query']['output_subtotals'])){
         	
        	if(!empty($_POST['query']['output_subtotals'])){ 
        		echo "checked='checked'  value='true'"; 
        	}else{
        			echo "value='false'";
        		}
         
         }else{
         
         echo "value='false'";
         }?>
        
         name="query[output_subtotals]" class="input in-checkbox">        <label for="query-output_subtotals"><span rel="helptip" class="helptip" id="ht-100147"><?php echo __('Show subtotals',true);?></span><span class="tooltip" id="ht-100147-tooltip">Show subtotals for groups of rows with same first grouping column. Any sort options will be applied after all "Group By" fields settings are applied.</span></label>
    </td>
</tr>
</tbody></table>
<?php echo $form->end();?>
</fieldset>
 
 
 
  <div class="group-title bottom">
 <img width="16" height="16" src="<?php echo $this->webroot?>images/charts.png"> 
 <a onclick="$('#charts_holder').toggle();return false;" href="#">View Charts »</a>
 </div>
 

 <div style="display: none;" id="charts_holder">
        
        
         <?php //****总价格报表1************?>

<div id="chart_9be11_div" class="amChart">
<div id="chart_9be11_div_inner" class="amChartInner">

<script type="text/javascript" src="<?php echo $this->webroot?>amcolumn/swfobject.js"></script>
	<div id="flashcontent">
		<strong>You need to upgrade your Flash Player</strong>
	</div>

	<script type="text/javascript">
		// <![CDATA[		
		var so = new SWFObject("<?php  echo  $this->webroot?>amcolumn/amcolumn.swf", "amcolumn", "100%", "300", "8", "#000000");
		so.addVariable("path", "<?php echo  $this->webroot?>amcolumn/");

		so.addVariable("chart_settings", encodeURIComponent("<settings><width>100%</width><height>300</height><preloader_on_reload>1</preloader_on_reload><redraw>1</redraw><digits_after_decimal>2</digits_after_decimal><background><alpha>100</alpha><border_alpha>20</border_alpha></background><grid><category><dashed>1</dashed></category><value><dashed>1</dashed></value></grid><axes><category><width>1</width><color>E7E7E7</color></category><value><width>1</width><color>E7E7E7</color></value></axes><values><value><min>0</min></value></values><depth>25</depth><column><width>85</width><balloon_text>{title}: {value} EUR</balloon_text><grow_time>0</grow_time></column><balloon><text_size>15</text_size><corner_radius>6</corner_radius><max_width>400</max_width><text_color>000000</text_color></balloon><labels><label><text>Total Cost, EUR</text><x>20</x><y>20</y><text_size>20</text_size></label></labels><graphs><graph gid='0'><color>EB690C</color></graph></graphs></settings>"));
		so.addVariable("chart_data",encodeURIComponent("<chart><series><value xid='0'>origination</value><value xid='1'>termination</value></series><graphs><graph gid='0' title='Total Cost'><value xid='0'>88888.0</value><value xid='1'>755665.28</value></graph></graphs></chart>"));


		
		so.addVariable("settings_file", encodeURIComponent("<?php  echo  $this->webroot?>amcolumn/amcolumn_settings_cost.xml"));
		so.addVariable("data_file", encodeURIComponent("<?php  echo  $this->webroot?>amcolumn/amcolumn_data_cost.xml"));
		so.addVariable("preloader_color", "#ffffff");
		so.write("flashcontent");
		// ]]>
	</script>
<!-- end of amcolumn script -->




</div>
</div>




 <?php //****每分钟收费报表2***********?>

<div id="chart_d8ee4_div" class="amChart">
<div id="chart_d8ee4_div_inner" class="amChartInner">
<!-- saved from url=(0013)about:internet -->
<!-- amcolumn script-->

	<div id="flashcontent1">
		<strong></strong>
	</div>

	<script type="text/javascript">
		// <![CDATA[		
		var so = new SWFObject("<?php  echo  $this->webroot?>amcolumn/amcolumn.swf", "amcolumn", "100%", "300", "8", "#000000");
		so.addVariable("path", "<?php echo  $this->webroot?>amcolumn/");


		so.addVariable("chart_settings", encodeURIComponent("<settings><width>100%</width><height>300</height><preloader_on_reload>1</preloader_on_reload><redraw>1</redraw><digits_after_decimal>2</digits_after_decimal><background><alpha>100</alpha><border_alpha>20</border_alpha></background><grid><category><dashed>1</dashed></category><value><dashed>1</dashed></value></grid><axes><category><width>1</width><color>E7E7E7</color></category><value><width>1</width><color>E7E7E7</color></value></axes><values><value><min>0</min></value></values><depth>25</depth><column><width>85</width><balloon_text>{title}: {value} min</balloon_text><grow_time>0</grow_time><type>3d column</type></column><balloon><text_size>15</text_size><corner_radius>6</corner_radius><max_width>400</max_width><text_color>000000</text_color></balloon><labels><label><text>Time (Total/Billed) origination</text><x>20</x><y>20</y><text_size>20</text_size></label></labels><graphs><graph gid='0'><color>EB690C</color></graph><graph gid='0'><color>EB690C</color></graph><graph gid='1'><color>F1C900</color></graph></graphs></settings>"));
		so.addVariable("chart_data",encodeURIComponent("<chart><series><value xid='0'>origination</value><value xid='1'>termination</value></series><graphs><graph gid='0' title='Billed Time'><value xid='0'>79330.22</value><value xid='1'>272519.48</value></graph><graph gid='1' title='Total Time'><value xid='0'>79330.22</value><value xid='1'>272686.4</value></graph></graphs></chart>"));
		
		//so.addVariable("settings_file", encodeURIComponent("<?php  echo  $this->webroot?>amcolumn/amcolumn_settings_time.xml"));
		//so.addVariable("data_file", encodeURIComponent("<?php  echo  $this->webroot?>amcolumn/amcolumn_data_time.xml"));
		so.addVariable("preloader_color", "#ffffff");
		so.write("flashcontent1");
		// ]]>
	</script>
<!-- end of amcolumn script -->

</div>
</div>

                    
                    
                     <?php //****asr   报表3***********?>             
<div id="chart_a4ecd_div" class="amChart">
<div id="chart_a4ecd_div_inner" class="amChartInner">

	<div id="flashcontent3">
		<strong></strong>
	</div>

	<script type="text/javascript">
		// <![CDATA[		
		var so = new SWFObject("<?php  echo  $this->webroot?>amcolumn/amcolumn.swf", "amcolumn", "100%", "300", "8", "#000000");
		so.addVariable("path", "<?php echo  $this->webroot?>amcolumn/");

		so.addVariable("chart_settings", encodeURIComponent("<settings><width>100%</width><height>300</height><preloader_on_reload>1</preloader_on_reload><redraw>1</redraw><digits_after_decimal>2</digits_after_decimal><background><alpha>100</alpha><border_alpha>20</border_alpha></background><grid><category><dashed>1</dashed></category><value><dashed>1</dashed></value></grid><axes><category><width>1</width><color>E7E7E7</color></category><value><width>1</width><color>E7E7E7</color></value></axes><values><value><min>0</min></value></values><depth>25</depth><column><width>85</width><balloon_text>{title}: {value} %</balloon_text><grow_time>0</grow_time><type>3d column</type></column><balloon><text_size>15</text_size><corner_radius>6</corner_radius><max_width>400</max_width><text_color>000000</text_color></balloon><labels><label><text>ASR, %</text><x>20</x><y>20</y><text_size>20</text_size></label></labels><graphs><graph gid='0'><color>EB690C</color></graph><graph gid='0'><color>EB690C</color></graph><graph gid='1'><color>F1C900</color></graph><graph gid='0'><color>EB690C</color></graph><graph gid='1'><color>F1C900</color></graph></graphs></settings>"));
		so.addVariable("chart_data",encodeURIComponent("<chart><series><value xid='0'>origination</value><value xid='1'>termination</value></series><graphs><graph gid='0' title='ASR Cur'><value xid='0'>20.38</value><value xid='1'>43.85</value></graph><graph gid='1' title='ASR Std'><value xid='0'>66.4</value><value xid='1'>85</value></graph></graphs></chart>"));
		//	so.addVariable("settings_file", encodeURIComponent("<?php  echo  $this->webroot?>amcolumn/amcolumn_settings_asr.xml"));
		//so.addVariable("data_file", encodeURIComponent("<?php  echo  $this->webroot?>amcolumn/amcolumn_data_asr.xml"));
		so.addVariable("preloader_color", "#ffffff");
		so.write("flashcontent3");
		// ]]>
	</script>
</div>
</div>

                              
                              
                              
                                <?php //****acd报表4***********?>       
                              
<div id="chart_8671f_div" class="amChart">
<div id="chart_8671f_div_inner" class="amChartInner">

	<div id="flashcontent4">
		<strong></strong>
	</div>

	<script type="text/javascript">
		// <![CDATA[		
		var so = new SWFObject("<?php  echo  $this->webroot?>amcolumn/amcolumn.swf", "amcolumn", "100%", "300", "8", "#000000");
		so.addVariable("path", "<?php echo  $this->webroot?>amcolumn/");


		so.addVariable("chart_settings", encodeURIComponent("<settings><width>100%</width><height>300</height><preloader_on_reload>1</preloader_on_reload><redraw>1</redraw><digits_after_decimal>2</digits_after_decimal><background><alpha>100</alpha><border_alpha>20</border_alpha></background><grid><category><dashed>1</dashed></category><value><dashed>1</dashed></value></grid><axes><category><width>1</width><color>E7E7E7</color></category><value><width>1</width><color>E7E7E7</color></value></axes><values><value><min>0</min></value></values><depth>25</depth><column><width>85</width><balloon_text>{title}: {value} min</balloon_text><grow_time>0</grow_time><type>3d column</type></column><balloon><text_size>15</text_size><corner_radius>6</corner_radius><max_width>400</max_width><text_color>000000</text_color></balloon><labels><label><text>ACD, min</text><x>20</x><y>20</y><text_size>20</text_size></label></labels><graphs><graph gid='0'><color>EB690C</color></graph><graph gid='0'><color>EB690C</color></graph><graph gid='1'><color>F1C900</color></graph><graph gid='0'><color>EB690C</color></graph><graph gid='1'><color>F1C900</color></graph><graph gid='0'><color>EB690C</color></graph><graph gid='1'><color>F1C900</color></graph></graphs></settings>"));
		so.addVariable("chart_data",encodeURIComponent("<chart><series><value xid='0'>origination</value><value xid='1'>termination</value></series><graphs><graph gid='0' title='ACD Cur'><value xid='0'>3.19</value><value xid='1'>1.56</value></graph><graph gid='1' title='ACD Std'><value xid='0'>1.38</value><value xid='1'>0.91</value></graph></graphs></chart>"));


		//so.addVariable("settings_file", encodeURIComponent("<?php  echo  $this->webroot?>amcolumn/amcolumn_settings_acd.xml"));

		//so.addVariable("data_file", encodeURIComponent("<?php  echo  $this->webroot?>amcolumn/amcolumn_data_acd.xml"));
		so.addVariable("preloader_color", "#ffffff");
		so.write("flashcontent4");
		// ]]>
	</script>


</div>
</div>

                                                  
                                                  
                                                  
            <div style="width: 100%;">
       <div style="width: 33%; float: left;">
                                                
  <?php //****打进的call报表5***********?>      
                                               
<div id="chart_4c649_div" class="amChart">
<div id="chart_4c649_div_inner" class="amChartInner">
	<div id="flashcontent5">
		<strong></strong>
	</div>

	<script type="text/javascript">
		// <![CDATA[		
		var so = new SWFObject("<?php  echo  $this->webroot?>amcolumn/ampie.swf", "amcolumn", "100%", "400", "8", "#000000");
		so.addVariable("path", "<?php echo  $this->webroot?>amcolumn/");

		so.addVariable("chart_settings", encodeURIComponent("<settings><width>100%</width><background><alpha>100</alpha><border_alpha>20</border_alpha></background><balloon><show>{title}: {value} ({percents}%)</show><text_color>000000</text_color><text_size>15</text_size><max_width>400</max_width><corner_radius>6</corner_radius></balloon><legend><align>center</align></legend><pie><inner_radius>40</inner_radius><height>20</height><angle>20</angle><outline_alpha>50</outline_alpha><alpha>80</alpha><hover_brightness>30</hover_brightness><gradient_ratio>-50,0,0,-50</gradient_ratio></pie><animation><start_radius>0%</start_radius><pull_out_time>1.5</pull_out_time><pull_out_effect>strong</pull_out_effect><pull_out_radius>25%</pull_out_radius></animation><data_labels><show>{title}: {percents}%</show></data_labels><labels><label lid='0'><text>Calls Count orig</text><x>20</x><y>20</y><text_size>20</text_size></label></labels></settings>"));
		so.addVariable("chart_data",encodeURIComponent("<pie><slice title='Calls Success'>57437</slice><slice title='Calls Busy'>2936</slice><slice title='Calls No Channel'>35606</slice><slice title='Calls Error'>26130</slice></pie>"));

		
	//	so.addVariable("settings_file", encodeURIComponent("<?php  echo  $this->webroot?>amcolumn/amcolumn_settings_origcall.xml"));

		//so.addVariable("data_file", encodeURIComponent("<?php  echo  $this->webroot?>amcolumn/amcolumn_data_origcall.xml"));
		so.addVariable("preloader_color", "#ffffff");
		so.write("flashcontent5");
		// ]]>
	</script>
</div>
</div>

</div>
   <div style="width: 33%; float: left;">
                                                                
                                                                
 <?php //****打出的call统计报表6***********?>      
                                                            
<div id="chart_10224_div" class="amChart">
<div id="chart_10224_div_inner" class="amChartInner">

	<div id="flashcontent6">
		<strong></strong>
	</div>

	<script type="text/javascript">
		// <![CDATA[		
		var so = new SWFObject("<?php  echo  $this->webroot?>amcolumn/ampie.swf", "amcolumn", "100%", "400", "8", "#000000");
		so.addVariable("path", "<?php echo  $this->webroot?>amcolumn/");
		so.addVariable("chart_settings", encodeURIComponent("<settings><width>100%</width><background><alpha>100</alpha><border_alpha>20</border_alpha></background><balloon><show>{title}: {value} ({percents}%)</show><text_color>000000</text_color><text_size>15</text_size><max_width>400</max_width><corner_radius>6</corner_radius></balloon><legend><align>center</align></legend><pie><inner_radius>40</inner_radius><height>20</height><angle>20</angle><outline_alpha>50</outline_alpha><alpha>80</alpha><hover_brightness>30</hover_brightness><gradient_ratio>-50,0,0,-50</gradient_ratio></pie><animation><start_radius>0%</start_radius><pull_out_time>1.5</pull_out_time><pull_out_effect>strong</pull_out_effect><pull_out_radius>25%</pull_out_radius></animation><data_labels><show>{title}: {percents}%</show></data_labels><labels><label lid='0'><text>Calls Count term</text><x>20</x><y>20</y><text_size>20</text_size></label></labels></settings>"));
		so.addVariable("chart_data",encodeURIComponent("<pie><slice title='Calls Success'>298482</slice><slice title='Calls Busy'>8312</slice><slice title='Calls No Channel'>48439</slice><slice title='Calls Error'>44371</slice></pie>"));



		//so.addVariable("settings_file", encodeURIComponent("<?php  echo  $this->webroot?>amcolumn/amcolumn_settings_termcall.xml"));
		//so.addVariable("data_file", encodeURIComponent("<?php  echo  $this->webroot?>amcolumn/amcolumn_data_termcall.xml"));
		so.addVariable("preloader_color", "#ffffff");
		so.write("flashcontent6");
		// ]]>
	</script>

</div>
</div>

</div>
                                    </div>
                            <div style="width: 100%;">
                                                <div style="width: 34%; float: left;">
                                                
   <?php //****打进打出一起统计报表7***********?>      
                     
<div id="chart_666e4_div" class="amChart">
<div id="chart_666e4_div_inner" class="amChartInner">
	<div id="flashcontent7">
		<strong></strong>
	</div>

	<script type="text/javascript">
		// <![CDATA[		
		var so = new SWFObject("<?php  echo  $this->webroot?>amcolumn/amradar.swf", "amradar", "100%", "400", "8", "#000000");
		so.addVariable("path", "<?php echo  $this->webroot?>amcolumn/");
	
	// so.addVariable("chart_settings", encodeURIComponent("<settings>...</settings>"));  


		so.addVariable("chart_settings", encodeURIComponent("<settings><height>300</height><background><alpha>100</alpha><border_alpha>20</border_alpha></background><balloon><show>{title}: {value}</show><text_size>15</text_size><corner_radius>6</corner_radius><max_width>400</max_width><text_color>000000</text_color></balloon><legend><align>center</align></legend><labels><label><text></text><x>20</x><y>20</y><text_size>20</text_size></label></labels></settings>"));
		so.addVariable("chart_data",encodeURIComponent("<chart><axes><axis xid='0'>Calls Total</axis><axis xid='1'>Calls Not Zero</axis><axis xid='2'>Calls Success</axis><axis xid='3'>Calls Busy</axis><axis xid='4'>Calls No Channel</axis><axis xid='5'>Calls Error</axis></axes><graphs><graph color='333333' line_width='2' bullet='round' gid='0' title='origination'><value xid='0'>122109</value><value xid='1'>24885</value><value xid='2'>57437</value><value xid='3'>2936</value><value xid='4'>35606</value><value xid='5'>26130</value></graph><graph color='99CC00' line_width='2' bullet='round' gid='1' title='termination'><value xid='0'>399604</value><value xid='1'>175232</value><value xid='2'>298482</value><value xid='3'>8312</value><value xid='4'>48439</value><value xid='5'>44371</value></graph></graphs></chart>"));


		//so.addVariable("settings_file", encodeURIComponent("<?php  echo  $this->webroot?>amcolumn/amcolumn_settings_totalcall.xml"));
		//so.addVariable("data_file", encodeURIComponent("<?php  echo  $this->webroot?>amcolumn/amcolumn_data_totalcall.xml"));
		so.addVariable("preloader_color", "#ffffff");
		so.write("flashcontent7");
		// ]]>
	</script>
</div>
</div>

</div>
                                    </div>
                </div>
  <?php //*******************************flash报表*****end********************************?>
 
 
 
 
 






<script type="text/javascript">
//<![CDATA[
tz = $('#query-tz').val();
function showCards ()
{
    ss_ids_custom['card'] = _ss_ids_card;
   // val = $('#query-client_type').val();//客户类型
    //tz = $('#query-tz').val();

    winOpen('<?php echo $this->webroot?>clients/ss_card?type=2&types=8', 500, 530);

}
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

    
<script type="text/javascript" src="<?php echo $this->webroot?>js/My97DatePicker/WdatePicker.js"></script>