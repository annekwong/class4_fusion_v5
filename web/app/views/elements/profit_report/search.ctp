
<fieldset class="query-box" style=" clear:both;overflow:hidden;margin-top:10px;">
  <div class="search_title"><img src="<?php
echo $this->webroot?>images/search_title_icon.png" />
    <?php __('search')?>
  </div>
  <script type="text/javascript">
//设置每个字段所对应的隐藏域
var _ss_ids_client = {'id_clients': 'query-id_clients', 'id_clients_name': 'query-id_clients_name'};
var _ss_ids_client_term = {'id_clients': 'query-id_clients_term', 'id_clients_name': 'query-id_clients_name_term'};
var _ss_ids_rate = {'id_rates': 'query-id_rates', 'id_rates_name': 'query-id_rates_name'};
var _ss_ids_code_name = {'code_name': 'query-code_name'};
var _ss_ids_code = {'code': 'query-code', 'id_code_decks': 'query-id_code_decks'};
</script>
  <?php 
$url='/profitreports/summary_reports/';
echo $form->create ('Cdr', array ('type'=>'get','url' => $url ,'onsubmit'=>"if ($('#query-output').val() == 'web') loading();"));?>
  <input   type="hidden"   value="searchkey"    name="searchkey"/>
  <input class="input in-hidden" name="query[process]" value="1" id="query-process" type="hidden">
  <input class="input in-hidden" name="query[order_by]" value="" id="query-order_by" type="hidden">
  <input class="input in-hidden" name="query[id_resellers]" value="" id="query-id_resellers" type="hidden">
  <input class="input in-hidden" name="query[account]" value="" id="query-account" type="hidden">
  <input class="input in-hidden" name="query[id_rates]" value="" id="query-id_rates" type="hidden">
  <input class="input in-hidden" name="query[id_clients]" value="" id="query-id_clients" type="hidden">
  <input class="input in-hidden" name="query[id_clients_term]" value="" id="query-id_clients_term"	type="hidden">
  <table style="width:100%;" class="form">

    <tbody>
      <tr class="period-block">
        <td class="label" style="width:80px;"><?php __('time')?>
          :</td>
        <td colspan="8"><table class="in-date">
            <tbody>
              <tr>
                <td style="padding-right: 15px;"><?php
					 		$r=array('custom'=>__('custom',true),    'curDay'=>__('today',true),    'prevDay'=>__('yesterday',true),    'curWeek'=>__('currentweek',true),    'prevWeek'=>__('previousweek',true),   'curMonth'=>__('currentmonth',true),
					   'prevMonth'=>__('previousmonth',true),   'curYear'=>__('currentyear',true)    ,'prevYear'=>__('previousyear',true)  ); 	
							if(!empty($_GET)){
								if(isset($_GET['smartPeriod'])){
									$s=$_GET['smartPeriod'];
								}else{
									$s='curDay';
								}
							}else{
								$s='curDay';
							}
							echo $form->input('smartPeriod',
							 		array('options'=>$r,'label'=>false ,
							 		'onchange'=>'setPeriod(this.value)','id'=>'query-smartPeriod','style'=>'width: 100px;','name'=>'smartPeriod',
							 		'div'=>false,'type'=>'select','selected'=>$s));?></td>
                <td><input type="text" id="query-start_date-wDt" class="wdate in-text input" onchange="setPeriod('custom')"   readonly="readonly"  onkeydown="setPeriod('custom')"
		         value="" name="start_date"  style="margin-left: 0px; width: 80px;"></td>
                <td><input type="text" id="query-start_time-wDt" onchange="setPeriod('custom')" onkeydown="setPeriod('custom')"
    	readonly="readonly" 
         style="width: 60px;" value="00:00:00" name="start_time" class="input in-text">
                  &nbsp;&nbsp;&mdash;&nbsp;&nbsp;
                  <input type="text" id="query-stop_date-wDt" class="wdate in-text input"  style="width: 80px;"    onchange="setPeriod('custom')"
    readonly="readonly" 
     onkeydown="setPeriod('custom')" value="" name="stop_date"></td>
                <td><input type="text" id="query-stop_time-wDt" onchange="setPeriod('custom')"
    readonly="readonly" 
     onkeydown="setPeriod('custom')" style="width: 60px;" value="23:59:59" name="stop_time" class="input in-text">
                  &nbsp;&nbsp;in&nbsp;&nbsp;
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
                  </select></td>
                <td><?php
$r=array(''=>__('alltime',true),   'YYYY-MM-DD  HH24:00:00'=>__('byhours',true),    'YYYY-MM-DD'=>__('byday',true),    'YYYY-MM'=>__('bymonth',true),    'YYYY'=>__('byyear',true) ); 		 		
if(!empty($_GET)){
	if(isset($_GET['group_by_date'])){
		$s=$_GET['group_by_date'];
		
	}else{
		$s='';
	}
}else{
	
	$s='';
}
echo $form->input('group_by_date',
 		array('options'=>$r,'label'=>false ,'id'=>'query-group_by_date','style'=>'width: 80px;','name'=>'group_by_date',
 		'div'=>false,'type'=>'select','selected'=>$s));?></td>
                <td class="buttons">
                    <select id="query-output" onchange="repaintOutput();" name="query[output]" class="input in-select">
            <option selected="selected" value="web">
            web
            </option>
             <option value="csv"><?php __('Excel CSV')?></option>
            <option value="xls"><?php __('Excel XLS')?></option>
          </select>
                    <input type="submit" value="Query" class="input in-submit">
                
                </td>
              </tr>
            </tbody>
          </table></td>
      </tr>
      
      
      <tr class="period-block" style="height:20px; line-height:20px;">
        <td colspan="2" style="text-align:center; font-size:14px;"><b><?php __('Inbound')?></b></td>
        <td class="in-out_bound">&nbsp;</td>
        <td colspan="2" style="text-align:center;font-size:14px;"><b><?php __('Outbound')?></b></td>
        <td class="in-out_bound">&nbsp;</td>
        <td class="label">&nbsp;</td>
        <td class="value"></td>
      </tr>
      
      <tr> 
	  <?php echo $this->element('search_report/orig_carrier_select'); ?>
        <td class="in-out_bound">&nbsp;</td>
        <?php echo $this->element('search_report/term_carrier_select'); ?>
        <td class="in-out_bound">&nbsp;</td>
		
		
        <td class="label"> Class4-server:</td>
        <td class="value"><?php 
 					echo $form->input('server_ip',
 					array('options'=>$server,'empty'=>'   ','label'=>false ,'div'=>false,'type'=>'select'));
 				?><?php echo $this->element('search_report/ss_clear_input_select');?></td>
      </tr>
      
      <tr> 
	  <td class="label "><?php __('ingress')?>
          <span class="tooltip" id="ht-100013-tooltip"><b><?php __('Use pre-assigned')?></b> &mdash; <?php __('means usage of code decks assigned to each pulled client or rate table')?>.<br>
          <br>
          <?php __('If you will')?> <b><?php __('specify')?></b> <?php __('a code deck, all code names will be rewritten using names from selected code deck, so all data will be unified by code names')?>.</span>:</td>
        <td class="value "><?php 
   		echo $form->input('ingress_alias',
 		array('options'=>$ingress,'empty'=>'  ','label'=>false ,'div'=>false,'type'=>'select'));?><?php echo $this->element('search_report/ss_clear_input_select');?></td>
        <td class="in-out_bound">&nbsp;</td>
        <td class="label "><?php __('egress')?></td>
        <td class="value "><?php echo $form->input('egress_alias',
 		array('options'=>$egress,'empty'=>'  ','label'=>false ,'div'=>false,'type'=>'select'));?><?php echo $this->element('search_report/ss_clear_input_select');?></td>
        <td class="in-out_bound">&nbsp;</td>
        
		<td class="label">
         
          </td>
        <td class="value "></td>   
      </tr>
      
      
      <tr> 
	  <?php echo $this->element('search_report/search_orig_country'); ?>
        <td class="in-out_bound">&nbsp;</td>
        <?php echo $this->element('search_report/search_term_country'); ?>
        <td class="in-out_bound">&nbsp;</td>

 		<td class="label "><?php __('dstnumber')?></td>
        <td class="value "><input type="text" id="query-dst_number" value="" name="query[dst_number]" class="input in-text"><?php echo $this->element('search_report/ss_clear_input_select');?></td>
		 
        </tr>
        <tr>
        <?php echo $this->element('search_report/search_orig_code_name'); ?>
        <td class="in-out_bound">&nbsp;</td>
        <?php echo $this->element('search_report/search_term_code_name'); ?>
        <td class="in-out_bound">&nbsp;</td>
        
        <td class="label "><?php __('srcnumber')?>
          <span class="tooltip" id="ht-100013-tooltip"><b><?php __('Use pre-assigned')?></b> &mdash; <?php __('means usage of code decks assigned to each pulled client or rate table')?>.<br>
          <br>
          <?php __('If you will')?> <b><?php __('specify')?></b> <?php __('a code deck, all code names will be rewritten using names from selected code deck, so all data will be unified by code names')?>.</span>:</td>
        <td class="value "><input type="text" id="query-src_number" value="" name="query[src_number]" class="input in-text"><?php echo $this->element('search_report/ss_clear_input_select');?></td>
        
      </tr>
      
      <tr>
        <?php echo $this->element('search_report/search_orig_code'); ?>
        <td class="in-out_bound">&nbsp;</td>
        <?php echo $this->element('search_report/search_term_code'); ?>
        <td class="in-out_bound">&nbsp;</td>
        
        <td ><span rel="helptip" class="helptip" id="ht-100002"><?php __('Interval  second')?></span><span class="tooltip" id="ht-100002-tooltip"><?php __('Duration interval in seconds')?></span>:</td>
        <td class="value "><input type="text" id="query-interval_from" class="in-digits input in-text" style="width: 53px;" value="" name="query[interval_from]">
          &mdash;
          <input type="text" id="query-interval_to" class="in-digits input in-text" style="width: 54px;" value="" name="query[interval_to]"><?php echo $this->element('search_report/ss_clear_input_select');?></td>
      </tr>

      
      
      <!--<tr>



            <td class="label">
            
 		<?php __('currency')?>
            
            
            </td>
    <td class="value ">
 		<?php echo $form->input('currency',	array('options'=>$currency,'empty'=>'   ','label'=>false ,'div'=>false,'type'=>'select'));?>
    </td>
    

    </tr>
    -->
     

     
      <?php echo $this->element('report/group_by');?>
    </tbody>
  </table>
  <?php echo $form->end();?>
</fieldset>
