<style>
	.value select.value input
{
	width:70%;
}
.value select, .value textarea, .value .in-text, .value .in-password, .value .in-textarea, .value .in-select{
	width:90%;
}
</style>
<div id="title"> <h1><?php echo __('Support',true);?>&gt;&gt;<?php echo __('Report',true);?></h1> </div>
<div id="container">
<ul class="tabs">
   <li <?php if($order_type=='buy'){  echo "class='active'";}?>>
   		<a href="<?php echo $this->webroot?>clientcdrreports/summary_reports/buy/">
   			<img width="16" height="16" src="<?php echo $this->webroot?>images/list.png"><?php echo __('Buy Report',true);?>
   		</a>
   </li>
   <li <?php if($order_type=='sell'){  echo "class='active'";}?>>
   		<a href="<?php echo $this->webroot?>clientcdrreports/summary_reports/sell/"> 
   			 <img width="16" height="16" src="<?php echo $this->webroot?>images/menuIcon.gif"><?php echo __('Sell Report',true);?>
   		</a>
   </li>
</ul>
<?php if (isset ( $exception_msg ) && $exception_msg) :	?>
	<?php	echo $this->element ( 'common/exception_msg' );?>		
<?php endif; ?>	
 <?php //***********************报表查询参数*********************?>
<fieldset class="query-box"><legend><?php __('search')?></legend>
 <?php echo $form->create ('Cdr', array ('type'=>'get','url' => '/clientcdrreports/summary_reports/','autocomplete'=>'off' ,'onsubmit'=>"if ($('#query-output').val() == 'web') loading();"));?>
<script type="text/javascript">
//设置每个字段所对应的隐藏域
var _ss_ids_client = {'id_clients': 'query-id_clients', 'id_clients_name': 'query-id_clients_name'};
var _ss_ids_rate = {'id_rates': 'query-id_rates', 'id_rates_name': 'query-id_rates_name',	'account': 'query-account', 'tz': 'query-tz', 'id_currencies': 'query-id_currencies'};
var _ss_ids_code_name = {'code_name': 'query-code_name'};
var _ss_ids_country = {'id_countrys': 'query-id_countrys'};
var _ss_ids_code = {'code': 'query-code', 'id_code_decks': 'query-id_code_decks'};
</script>
<input   type="hidden"   value="searchkey"    name="searchkey"/>
<input class="input in-hidden" name="query[process]" value="1" id="query-process" type="hidden">
<input class="input in-hidden" name="query[order_by]" value="" id="query-order_by" type="hidden">
<input class="input in-hidden" name="order_type" value="<?php echo  $order_type?>" id="order_type" type="hidden">
<input class="input in-hidden" name="query[id_clients]" value="" id="query-id_clients" type="hidden">
<input class="input in-hidden" name="query[id_resellers]" value="" id="query-id_resellers" type="hidden">
<input class="input in-hidden" name="query[account]" value="" id="query-account" type="hidden">
<input class="input in-hidden" name="query[id_rates]" value="" id="query-id_rates" type="hidden">
<input class="input in-hidden" name="query[id_cards]" value="" id="query-id_cards" type="hidden">
<table  class="form" style="width: 100%;"><tbody>
<tr class="period-block">
    <td class="label" style="width:10%"><?php __('time')?>:</td>
    	<td style="width:12%">
    	<?php 
	 			$r=array('custom'   	=>__('custom',true),
 						'curDay'   =>__('today',true),
 						'prevDay'		=>__('yesterday',true),
 						'curWeek'		=>__('currentweek',true),
 						'prevWeek'	=>__('previousweek',true),
 						'curMonth'	=>__('currentmonth',true),
 						'prevMonth'=>__('previousmonth',true),
 						'curYear'		=>__('currentyear',true),
 						'prevYear'	=>__('previousyear',true)  
	 			); 		
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
		 		'onchange'=>'setPeriod(this.value)','id'=>'query-smartPeriod','name'=>'smartPeriod',
		 		'div'=>false,'type'=>'select','selected'=>$s));
		 	?>
    	</td>
	    <td colspan="4" class="value">
			  <table class="in-date">
			   <tbody>
			    <tr>
			    		<td>
			    			<table class="in-date">
									<tbody>
										<tr>
								    <td><input type="text" id="query-start_date-wDt" class="wdate in-text input" onchange="setPeriod('custom')"   readonly="readonly"  onkeydown="setPeriod('custom')"
								         value="" name="start_date"  style="margin-left: 0px; width: 120px;"></td>
								    <td></td>
										</tr>
									</tbody>
								</table>
			    		</td>
			    		<td>
			    			<input type="text" id="query-start_time-wDt" onchange="setPeriod('custom')" onkeydown="setPeriod('custom')" readonly="readonly" style="width: 60px;" value="00:00:00" name="start_time" class="input in-text">
			    		</td>
			    		<td>&mdash;</td>
			    		<td>
			    			<table class="in-date">
									<tbody>
										<tr>
			    						<td>
			    							<input type="text" id="query-stop_date-wDt" class="wdate in-text input"  style="width: 120px;"    onchange="setPeriod('custom')" readonly="readonly" onkeydown="setPeriod('custom')" value="" name="stop_date">
			    						</td>
			    						<td></td>
										</tr>
									</tbody>
								</table>
			    		</td>
			    		<td>
			    			<input type="text" id="query-stop_time-wDt" onchange="setPeriod('custom')" readonly="readonly"  onkeydown="setPeriod('custom')" style="width: 60px;" value="23:59:59" name="stop_time" class="input in-text">
			    		</td>
			     	<td style="padding: 0pt 10px;">in</td>
			     	<td>
			     		<select id="query-tz" style="width: 100px;" name="query[tz]" class="input in-select">
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
						     <option value="+0330">GMT +03:30</option>
						     <option value="+0400">GMT +04:00</option>
						     <option value="+0500">GMT +05:00</option>
						     <option value="+0600">GMT +06:00</option>
						     <option value="+0700">GMT +07:00</option>
						     <option value="+0800">GMT +08:00</option>
						     <option value="+0900">GMT +09:00</option>
						     <option value="+1000">GMT +10:00</option>
						     <option value="+1100">GMT +11:00</option>
						     <option value="+1200">GMT +12:00</option>
						    </select>
						   </td>
			    		</tr>
			    	</tbody>
			    </table>
					</td>
	    		<td class="buttons"></td>
				</tr>
			</tbody>
		</table>
  <table style="height:10px"><tr><td></td></tr></table>
	<table  class="form" style="width: 100%;">
		<tbody>
			 <tr  style="height: 35px;">
		    <td class="label" style="width:10%"><?php echo __('country',true);?></td>
		    <td class="value" style="width:12%">
		       <input id="Country" type="text" name="query[country]"  value="<?php echo isset($search_value)?$search_value:''?>">
		    </td>
		    <td class="label" style="width:50px"><?php echo __('code_name')?>:</td>
		    <td class="value"  style="width:12%">
		        <input id="code_name"   name="query[code_name]" type="text" value="<?php echo isset($search_value)?$search_value:''?>">
		    </td>
		    <td class="label" style="width:50px"><?php __('code')?>:</td>
		    <td class="value"  style="width:12%">
							<input id="code" type="text" name="query[code]" value="<?php echo isset($search_value)?$search_value:''?>">
		    </td>
				 <td class="value" ><?php echo __('Cost',true);?>:<select  style="width:90px" id="query-cost" name="query[cost]" class="input in-select"><option value="">all</option><option value="nonzero">non-zero</option><option value="zero">zero</option></select></td>
	    </tr>
	    <tr  style="height: 35px;">
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
	    <td class="label">
	    			<span id="ht-100002"><?php echo __('Minutes',true);?></span>
	    	</td>
	    <td class="value">
	        <input type="text" id="query-interval_from"  style="width: 27%;" value="" name="query[interval_from]"> 
	        &mdash; 
	        <input type="text" id="query-interval_to" class="in-digits input in-text" style="width: 27%;" value="" name="query[interval_to]">    </td>
	    		<td></td>
	    </tr>
	    </tbody></table>
	<table style="height:10px"><tr><td></td></tr></table>
	<table  class="form" style="width:100%;" >
	<tbody>
	<tr class="output-block">
	    <td class="label" style="width:10%"><span><?php echo __('Save',true);?>:</span></td>
	    <td class="value" style="width:12%"><select id="query-output" onchange="repaintOutput();" name="query[output]"><option value="web">Web</option><option value="csv">Excel CSV</option><option value="xls">Excel XLS</option><option value="delayed">Delayed CSV</option></select></td>
	    	<td style="width:47%"><input type="submit" value="<?php echo __('query',true);?>" class="input in-submit"></td>
	    	<td></td>
	    <td class="label"><div></div></td>
	</tr>
	</tbody>
	</table>
<?php echo $form->end();?>
</fieldset>
<?php 
$mydata =$p->getDataArray();	$loop = count($mydata);
		if($loop==0){?>
		<div class="msg"  ><?php echo __('no_data_found',true);?></div>
		<?php }else{?>
		<div id="toppage"></div>
		<table style="width: 100%;" class="list nowrap with-fields">
			<thead>
				<tr>
					<td width="10%" rel="8">&nbsp;&nbsp;&nbsp;&nbsp; <?php echo __('Order Number',true);?>  &nbsp;&nbsp;</td>
					<td width="10%" rel="8">&nbsp;&nbsp;&nbsp;&nbsp; <?php echo __('Updated Time',true);?>  &nbsp;&nbsp;</td>
					<td width="10%" rel="8">&nbsp;&nbsp;&nbsp;&nbsp; <?php echo __('country',true);?>  &nbsp;&nbsp;</td>
					<td width="10%" rel="8">&nbsp;&nbsp;&nbsp;&nbsp; <?php echo __('code_name',true);?>  &nbsp;&nbsp;</td>
					<td width="10%" rel="8" class="last">&nbsp;&nbsp;&nbsp;&nbsp; <?php echo __('code',true);?>  &nbsp;&nbsp;</td>
					<td width="10%" rel="8" class="last">&nbsp;&nbsp;&nbsp;&nbsp; <?php echo __('Minutes',true);?>  &nbsp;&nbsp;</td>
					<td width="10%" rel="8" class="last">&nbsp;&nbsp;&nbsp;&nbsp;  <?php echo __('rate',true);?>  &nbsp;&nbsp;</td>
			  </tr>
		  </thead>
		 <tbody >
			<?php 	 for ($i=0;$i<$loop;$i++) {?>
			 <tr style="color: rgb(75, 145, 0);" class=" row-2 row-1">
					<td style="text-align: center; color: rgb(102, 148, 227);" class="in-decimal"><strong ><?php echo $mydata[$i][0]['order_id']?></strong></td>
					<td style="text-align: center; color: rgb(102, 148, 227);" class="in-decimal"><strong ><?php echo $mydata[$i][0]['update_time']?></strong></td>
					<td style="text-align: center; color: rgb(102, 148, 227);" class="in-decimal"><strong ><?php echo $mydata[$i][0]['country']?></strong></td>
					<td style="text-align: center; color: rgb(102, 148, 227);" class="in-decimal"><strong ><?php echo $mydata[$i][0]['code_name']?></strong></td>
					<td style="text-align: center; color: rgb(102, 148, 227);" class="in-decimal"><strong ><?php echo $mydata[$i][0]['code']?></strong></td>
					<td style="text-align: center; color: rgb(102, 148, 227);" class="in-decimal"><strong ><?php echo  number_format($mydata[$i][0]['org_bill_minute'],2)?></strong></td>
					<td style="text-align: center; color: rgb(102, 148, 227);" class="in-decimal last"><strong ><?php echo number_format($mydata[$i][0]['org_avg_rate'],5)?></strong></td>
		   </tr>
		  <?php }?>
		 </tbody>
		</table>
		<div id="tmppage"><?php echo $this->element('page');?></div>
      <?php }?>

<div style="display: none;" id="charts_holder">

</div>
<div>
</div>
<div id="livemargins_control" style="position: absolute; display: none; z-index: 9999;">
	<img width="77" height="5" style="position: absolute; left: -77px; top: -5px;" src="chrome://livemargins/skin/monitor-background-horizontal.png">	
	<img style="position: absolute; left: 0pt; top: -5px;" src="chrome://livemargins/skin/monitor-background-vertical.png">
	<img style="position: absolute; left: 1px; top: 0pt; opacity: 0.5; cursor: pointer;" onmouseout="this.style.opacity=0.5" onmouseover="this.style.opacity=1" src="chrome://livemargins/skin/monitor-play-button.png" id="monitor-play-button">
</div>
<script type="text/javascript" src="<?php echo $this->webroot?>js/My97DatePicker/WdatePicker.js"></script>
<script type="text/javascript">
	jQuery(document).ready(
		function(){
		//	var type=jQuery('#search_type').val();
			jQuery( '#Country').autocomplete({source:'<?php echo $this->webroot?>codedecks/ajax_options?type=country',width:'auto'});	
			jQuery( '#code_name').autocomplete({source:'<?php echo $this->webroot?>codedecks/ajax_options?type=code_name'});	
			jQuery( '#code').autocomplete({source:'<?php echo $this->webroot?>codedecks/ajax_options?type=code'});	
					
		}
	);
</script>
</div>

<script type="text/javascript">
function showCountrys ()
{
    ss_ids_custom['country'] = _ss_ids_country;
    winOpen('<?php echo $this->webroot?>codedecks/ss_country?types=2&type=0', 500, 530);

}
function showss_codes ()
{
    ss_ids_custom['code'] = _ss_ids_code;
    winOpen('<?php echo $this->webroot?>codedecks/ss_code?types=2&type=0', 500, 530);

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