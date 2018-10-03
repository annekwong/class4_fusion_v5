
<fieldset class="query-box" style=" clear:both;overflow:hidden;margin-top:10px;">
  <div class="search_title"><img src="<?php
echo $this->webroot?>images/search_title_icon.png" />
    <?php __('search')?>
  </div>
  <?php echo $this->element('search_report/search_js');?><?php echo $this->element('search_report/search_js_show');?>
  <?php 
if($rate_type=='org'){$url='/ratereports/summary_reports/org/';}else{$url='/ratereports/summary_reports/term/';}
echo $form->create ('Cdr', array ('type'=>'get','url' => $url ,'onsubmit'=>"if ($('#query-output').val() == 'web') loading();"));?>
  <?php echo $this->element('search_report/search_hide_input');?>
  <table style="width:100%;" class="form">
    <col style="width: 80px;">
    <col style="width: 235px;">
    <tbody>
      <tr class="period-block">
        <td class="label" style="text-align:right; width:80px;"><?php __('time')?>
          :</td>
        <td colspan="8" style="width:auto;"><table class="in-date">
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
							 		'onchange'=>'setPeriod(this.value)','id'=>'query-smartPeriod','style'=>'width: 80px;','name'=>'smartPeriod',
							 		'div'=>false,'type'=>'select','selected'=>$s));?></td>
                <td><input type="text" id="query-start_date-wDt" class="wdate in-text input" onchange="setPeriod('custom')"   readonly="readonly"  onkeydown="setPeriod('custom')"
		         value="" name="start_date"  style="width: 80px;"></td>
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
                <td class="buttons"><input type="submit" value="Query" class="input in-submit"></td>
              </tr>
            </tbody>
          </table></td>
      </tr>
     
      <tr class="period-block" style="height:20px; line-height:20px;">
        <td colspan="2" style="text-align:center; font-size:14px;"><b><?php echo __('Inbound',true);?></b></td>
        <td class="in-out_bound">&nbsp;</td>
        <td colspan="2" style="text-align:center;font-size:14px;"><b><?php echo __('Outbound',true);?></b></td>
        <td class="in-out_bound">&nbsp;</td>
        <td class="label">&nbsp;</td>
        <td class="value"></td>
      </tr>
      <tr> 
	  <?php echo $this->element('search_report/orig_carrier_select'); ?>
        <td class="in-out_bound">&nbsp;</td>
        <?php echo $this->element('search_report/term_carrier_select'); ?>
        <td class="in-out_bound">&nbsp;</td>
		
		
        <td class="label"> <?php echo __('Class4-server',true);?>:</td>
        <td class="value "><?php echo $form->input('server_ip',
 		array('options'=>$server,'empty'=>'    ','label'=>false ,'div'=>false,'type'=>'select'));?><?php echo $this->element('search_report/ss_clear_input_select');?></td>
      </tr>
      
       <tr> 
	  <td class="label "><?php __('ingress')?>
          <span class="tooltip" id="ht-100013-tooltip"><b>Use pre-assigned</b> &mdash; means usage of code decks assigned to each pulled client or rate table.<br>
          <br>
          If you will <b>specify</b> a code deck, all code names will be rewritten using names from selected code deck, so all data will be unified by code names.</span>:</td>
        <td class="value "><?php 
   		echo $form->input('ingress_alias',
 		array('options'=>$ingress,'label'=>false ,'div'=>false,'type'=>'select','onchange'=>'getTechPrefix(this);'));?><?php echo $this->element('search_report/ss_clear_input_select');?></td>
         <td class="in-out_bound">&nbsp;</td>
         <td class="label "><?php __('egress')?></td>
        <td class="value "><?php echo $form->input('egress_alias',
 		array('options'=>$egress,'empty'=>'  ','label'=>false ,'div'=>false,'type'=>'select'));?><?php echo $this->element('search_report/ss_clear_input_select');?></td>

        <td class="in-out_bound">&nbsp;</td>
        <td class="label">
          <?php __('output')?>
          :</td>
        <td class="value "><select id="query-output" onchange="repaintOutput();" name="query[output]" class="input in-select">
            <option selected="selected" value="web">
            <?php __('web')?>
            </option>
             <?php  if ($_SESSION['role_menu']['Statistics']['ratereports']['model_x']) {?>
             <option value="csv"><?php __('Excel CSV')?></option>
            <option value="xls"><?php __('Excel XLS')?></option>
            <?php }?>
            <!--<option value="delayed">Delayed CSV</option>
    -->
          </select></td>
      </tr>
      
      
      
      
      <tr>
          
        <td lass="label"><?php __('Tech Prefix')?></td>
          <td class="value">
              <select name ="route_prefix" id="CdrRoutePrefix">
                   <option value="">
                       <?php __('All')?>
                   </option>
                   <?php
                        if(!empty($tech_perfix)){
                            foreach($tech_perfix as $te){
                                if($_GET['route_prefix'] == $te[0]['tech_prefix']){
                                    echo "<option selected value='".$te[0]['tech_prefix']."'>".$te[0]['tech_prefix']."</option>";
                                }else{
                                    echo "<option value='".$te[0]['tech_prefix']."'>".$te[0]['tech_prefix']."</option>";
                                }
                            }
                        }
                       
                   ?>   
              </select>
              
                    
          </td>
          <td class="in-out_bound">&nbsp;</td>
           
           
      </tr>
      
      <tr> 
	  <?php echo $this->element('search_report/search_orig_country'); ?>
        <td class="in-out_bound">&nbsp;</td>
        <?php echo $this->element('search_report/search_term_country'); ?>
        <td class="in-out_bound">&nbsp;</td>
		
		<td class="label "><?php __('RateTable')?></td>
        <td class="value "><input type="text" id="query-id_rates_name"value="" name="query[rate_name]" class="input in-text">
        <!--
          <img width="9" height="9" onclick="ss_rate(_ss_ids_rate)" class="img-button" src="<?php echo $this->webroot?>images/search-small.png"> 
          --><img width="9" height="9" onclick="ss_clear('card', _ss_ids_rate)" class="img-button" src="<?php echo $this->webroot?>images/delete-small.png"></td>
       
      </tr>
      
       <tr> 
	  <?php echo $this->element('search_report/search_orig_code_name'); ?>
        <td class="in-out_bound">&nbsp;</td>
        <?php echo $this->element('search_report/search_term_code_name'); ?>
        <td class="in-out_bound">&nbsp;</td>

        <td class="label "><?php __('dstnumber')?></td>
        <td class="value "><input type="text" id="query-dst_number" value="" name="query[dst_code]" class="input in-text"><?php echo $this->element('search_report/ss_clear_input_select');?></td>
      </tr>
      
       <tr> 
	  <?php echo $this->element('search_report/search_orig_code'); ?>
        <td class="in-out_bound">&nbsp;</td>
        <?php echo $this->element('search_report/search_term_code'); ?>
        <td class="in-out_bound">&nbsp;</td>

		<td class="label "><?php __('srcnumber')?>
          <span class="tooltip" id="ht-100013-tooltip"><b><?php __('Use pre-assigned')?></b> &mdash; <?php __('means usage of code decks assigned to each pulled client or rate table')?>.<br>
          <br>
          <?php __('If you will')?> <b><?php __('specify')?></b> <?php __('a code deck, all code names will be rewritten using names from selected code deck, so all data will be unified by code names')?>.</span>:</td>
        <td class="value "><input type="text" id="query-src_number" value="" name="query[src_code]" class="input in-text"><?php echo $this->element('search_report/ss_clear_input_select');?></td>
       
      </tr>
      

    
      <tr>
        <!--<td ><span rel="helptip" class="helptip" id="ht-100002">Interval  second</span><span class="tooltip" id="ht-100002-tooltip">Duration interval in seconds</span>:</td>
        <td class="value "><input type="text" id="query-interval_from" class="in-digits input in-text" style="width: 53px;" value="" name="query[interval_from]">
          &mdash;
          <input type="text" id="query-interval_to" class="in-digits input in-text" style="width: 54px;" value="" name="query[interval_to]"></td>
        -->
        
      </tr>

      <?php echo $this->element('report/group_by');?>
    </tbody>
  </table>
  <?php echo $form->end();?>
</fieldset>



<script type="text/javascript">
    function getTechPrefix(obj){
        $("#CdrRoutePrefix").empty();
        $("#CdrRoutePrefix").append("<option value=''>All</option>");
        if($(obj).val() != '0'){
            $.post("<?php echo $this->webroot?>cdrreports/getTechPerfix", {ingId:$(obj).val()}, 
            function(data){
                
                $.each(eval(data),
                    function (index,content){
                       $("#CdrRoutePrefix").append("<option value='"+content[0]['tech_prefix']+"'>"+content[0]['tech_prefix']+"</option>");
                    }
                );
            });
            
        }
        
        
        
    }
</script>
