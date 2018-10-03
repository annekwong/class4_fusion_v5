<style type="text/css">
	.cb_select{width:200px;}
</style>
<fieldset class="query-box" style=" clear:both;overflow:hidden;margin-top:10px;">
  <div class="search_title"><img src="<?php
echo $this->webroot?>images/search_title_icon.png" />
    <?php __('search')?>
  </div>
  <?php echo $this->element('search_report/search_js');?>
  
  <table class="form" style="width: 100%">
    <tbody>
    
      
      <?php echo $this->element('report/form_period',array('group_time'=>false, 'gettype'=>'<select id="query-output"
				onchange="repaintOutput();" name="query[output]"
				class="input in-select">
            <option value="web">Web</option>
            <option value="csv">Excel CSV</option>
            <option value="xls">Excel XLS</option>
          </select>'))?>
      <tr class="period-block" style="height:20px; line-height:20px;">
        <td colspan="2" style="text-align:center; font-size:14px;"><b><?php echo __('Inbound',true);?></b></td>
        <td class="in-out_bound">&nbsp;</td>
        <td colspan="2" style="text-align:center;font-size:14px;"><b><?php echo __('Outbound',true);?></b></td>
        <td class="in-out_bound">&nbsp;</td>
        <td class="label">&nbsp;</td>
        <td class="value"></td>
      </tr>
      <tr> <?php echo $this->element('search_report/orig_carrier_select');?><td class="in-out_bound">&nbsp;</td><?php echo $this->element('search_report/term_carrier_select');?><td class="in-out_bound">&nbsp;</td>
        <td  valign="top" rowspan="8" colspan="2"
				style="padding-left:10px; width:25%;"><div align="left"><?php echo __('Show Fields',true);?>:</div>
          <?php

						echo $form->select('Cdr.field', $cdr_field , $show_field_array,array('id'=>'query-fields',  'style'=>'width:100%; height: 300px;', 'name'=>'query[fields]','type' => 'select', 'multiple' => true),false);
					?></td>
      </tr>
      <tr>
        <td class="label"><?php echo __('Ingress',true);?>:</td>
        <td class="value"><?php 
									echo $form->input('ingress_alias',
									array('options'=>$ingress,'empty'=>'','label'=>false ,'div'=>false,'type'=>'select'));
							 ?><?php echo $this->element('search_report/ss_clear_input_select');?></td>
        <td class="in-out_bound">&nbsp;</td>
        <td class="label"><?php echo __('Egress',true);?>:</td>
        <td class="value"><?php 
					 			echo $form->input('egress_alias',
					 			array('options'=>$egress,'empty'=>'','label'=>false ,'div'=>false,'type'=>'select'));
					 		?><?php echo $this->element('search_report/ss_clear_input_select');?></td>
                            <td class="in-out_bound">&nbsp;</td>
      </tr>
      <tr>
       <?php echo $this->element('search_report/search_orig_country'); ?>
       <td class="in-out_bound">&nbsp;</td>
	      <?php echo $this->element('search_report/search_term_country'); ?>
          <td class="in-out_bound">&nbsp;</td>
         
		</tr>
        
        <tr>
        <?php echo $this->element('search_report/search_orig_code_name'); ?>
        <td class="in-out_bound">&nbsp;</td>
        <?php echo $this->element('search_report/search_term_code_name'); ?>
        <td class="in-out_bound">&nbsp;</td>
        </tr>
        <tr>
        <?php echo $this->element('search_report/search_orig_code'); ?>
        <td class="in-out_bound">&nbsp;</td>
        <?php echo $this->element('search_report/search_term_code'); ?>
        <td class="in-out_bound">&nbsp;</td>
        </tr>
        
      <tr>
        <td class="label"><?php echo __('Origination Host',true);?></b></td>
        <td class="value"><?php 
					 			echo $form->input('orig_host',
					 			array('options'=>$all_host,'empty'=>'','name'=>'orig_host','label'=>false ,'div'=>false,'type'=>'select'));
					 		?><?php echo $this->element('search_report/ss_clear_input_select');?></td>
        <td class="in-out_bound">&nbsp;</td>
        <td class="label"><?php echo __('Termination Host',true);?></b></td>
        <td class="value"><?php 
					 			echo $form->input('term_host',
					 			array('options'=>$all_host,'name'=>'term_host','empty'=>'','label'=>false ,'div'=>false,'type'=>'select'));
					 		?><?php echo $this->element('search_report/ss_clear_input_select');?></td>
                            <td class="in-out_bound">&nbsp;</td>
      </tr>
      <tr><?php echo  $this->element('search_report/search_orig_rate')?>
      <td class="in-out_bound">&nbsp;</td>
	  <?php echo  $this->element('search_report/search_term_rate')?><td class="in-out_bound">&nbsp;</td>
      </tr>
      
      <tr>
        <td class="label"><?php echo __('Response to ingress',true);?></td>
        <td class="value"><select id="query-res_status_ingress"
				onchange="$('#query-disconnect_cause_ingress').val($('#query-res_status_ingress').val());"
				name="query[res_status_ingress]" class="input in-select">
            <option value="">all</option>
            <option value="200">success</option>
            <option value="300">multiple</option>
            <option value="301">moved permanently</option>
            <option value="302">moved temporaily</option>
            <option value="305">use proxy</option>
            <option value="380">alternative service</option>
            <option value="400">bad request</option>
            <option value="401">unauthorized</option>
            <option value="402">payment required</option>
            <option value="403">forbidden</option>
            <option value="404">not found</option>
            <option value="405">method no allowed</option>
            <option value="406">not acceptable</option>
            <option value="407">proxy authentication required</option>
            <option value="408">request timeout</option>
            <option value="410">gone</option>
            <option value="413">request entity too large</option>
            <option value="414">request-url too long</option>
            <option value="415">unsupported media type</option>
            <option value="416">unsupported url scheme</option>
            <option value="420">bad extension</option>
            <option value="421">extension required</option>
            <option value="423">interval too brief</option>
            <option value="480">temporarily unavailable</option>
            <option value="481">call/transaction does not exist</option>
            <option value="482">loop detected</option>
            <option value="483">too many hops</option>
            <option value="484">address incomplete</option>
            <option value="485">ambiguous</option>
            <option value="486">busy here</option>
            <option value="487">request terminated</option>
            <option value="488">not acceptable here</option>
            <option value="491">request pending</option>
            <option value="493">undecipherable</option>
            <option value="500">server internal error</option>
            <option value="501">not implemented</option>
            <option value="502">bad gateway</option>
            <option value="503">service unavailable</option>
            <option value="504">server time-out </option>
            <option value="505">version not supported </option>
            <option value="513">message too large </option>
            <option value="600">busy everywhere </option>
            <option value="603">decline </option>
            <option value="604">does not exist anywhere </option>
            <option value="606">not acceptable </option>
          </select>
          <input type="text" id="query-disconnect_cause_ingress"
				style="width: 35px;" value="" name="query[disconnect_cause_ingress]"
				class="input in-text"><?php echo $this->element('search_report/ss_clear_input_select');?></td>
        <td class="in-out_bound">&nbsp;</td>
        <td class="label"><?php echo __('Response from egress',true);?></td>
        <td class="value"><select id="query-res_status"
				onchange="$('#query-disconnect_cause').val($('#query-res_status').val());"
				name="query[res_status]" class="input in-select">
            <option value="">all</option>
            <option value="200">success</option>
            <option value="300">multiple</option>
            <option value="301">moved permanently</option>
            <option value="302">moved temporaily</option>
            <option value="305">use proxy</option>
            <option value="380">alternative service</option>
            <option value="400">bad request</option>
            <option value="401">unauthorized</option>
            <option value="402">payment required</option>
            <option value="403">forbidden</option>
            <option value="404">not found</option>
            <option value="405">method no allowed</option>
            <option value="406">not acceptable</option>
            <option value="407">proxy authentication required</option>
            <option value="408">request timeout</option>
            <option value="410">gone</option>
            <option value="413">request entity too large</option>
            <option value="414">request-url too long</option>
            <option value="415">unsupported media type</option>
            <option value="416">unsupported url scheme</option>
            <option value="420">bad extension</option>
            <option value="421">extension required</option>
            <option value="423">interval too brief</option>
            <option value="480">temporarily unavailable</option>
            <option value="481">call/transaction does not exist</option>
            <option value="482">loop detected</option>
            <option value="483">too many hops</option>
            <option value="484">address incomplete</option>
            <option value="485">ambiguous</option>
            <option value="486">busy here</option>
            <option value="487">request terminated</option>
            <option value="488">not acceptable here</option>
            <option value="491">request pending</option>
            <option value="493">undecipherable</option>
            <option value="500">server internal error</option>
            <option value="501">not implemented</option>
            <option value="502">bad gateway</option>
            <option value="503">service unavailable</option>
            <option value="504">server time-out </option>
            <option value="505">version not supported </option>
            <option value="513">message too large </option>
            <option value="600">busy everywhere </option>
            <option value="603">decline </option>
            <option value="604">does not exist anywhere </option>
            <option value="606">not acceptable </option>
          </select>
          <input type="text" id="query-disconnect_cause"
				style="width: 35px;" value="" name="query[disconnect_cause]"
				class="input in-text"><?php echo $this->element('search_report/ss_clear_input_select');?></td>
                <td class="in-out_bound">&nbsp;</td>
      </tr>
      <tr>
        <td class="label"> <?php echo __('Ingress Cost',true);?>:</td>
        <td class="value"><select id="query-cost" name="query[cost]"
				class="input in-select">
            <option value="">all</option>
            <option value="nonzero">non-zero</option>
            <option value="zero">zero</option>
          </select><?php echo $this->element('search_report/ss_clear_input_select');?></td>
       <td class="in-out_bound">&nbsp;</td>
        <td class="label"><?php echo __('Egress Cost',true);?>:</td>
        <td class="value"><select id="query-cost_term" name="query[cost_term]"
				class="input in-select">
            <option value="">all</option>
            <option value="nonzero">non-zero</option>
            <option value="zero">zero</option>
          </select><?php echo $this->element('search_report/ss_clear_input_select');?></td><td class="in-out_bound">&nbsp;</td>
          <td class="label"><?php echo __('Duration',true);?>:</td>
        <td class="value"><select id="query-duration" name="query[duration]"
				class="input in-select">
            <option value="" selected="selected">all</option>
            <option value="nonzero">non-zero</option>
            <option value="zero">zero</option>
          </select></td>
      </tr>
      
      <tr>
          <td class="label"><?php echo __('ani',true);?>:</td>
          <td class="value"><input type="text" id="query-src_number" value=""
				name="query[src_number]" class="input in-text"><?php echo $this->element('search_report/ss_clear_input_select');?></td>
          <td class="in-out_bound">&nbsp;</td>
          <td class="label"><?php echo __('ani',true);?>:</td>
        <td class="value"><input type="text" id="query-term_src_number" value=""
				name="query[term_src_number]" class="input in-text">
            <td class="in-out_bound">&nbsp;</td>
        <td colspan="2"></td>
      </tr>
      
      <tr>
        <td class="label"><?php echo __('dnis',true);?>
          <select name="query[dnis_type]" style="width:80px;">
            <option value="">Start with</option>
            <option value="1" <?php echo !empty($_REQUEST['query']['dnis_type'])?'selected':''; ?> >Not Start with</option>
          </select>
          : </td>
        <td class="value"><input type="text" id="query-dst_number" value=""
				name="query[dst_number]" class="input in-text"><?php echo $this->element('search_report/ss_clear_input_select');?></td>
        <td class="in-out_bound">&nbsp;</td>
        <td class="label"><?php echo __('dnis',true);?>:</td>
        <td class="value"><input type="text" id="query-term_dst_number" value=""
				name="query[term_dst_number]" class="input in-text"><?php echo $this->element('search_report/ss_clear_input_select');?></td>
                <td class="in-out_bound">&nbsp;</td>
                <td class="label"><span></span></td>
        <td class="value"></td>
        
      </tr>
      <!--
      <tr>
        <td colspan="8"><table style="width: 100%;">
            <tr  class="period-block">
              <td class="label"  style=" width:100px;text-align: left;"><span  style="color: #568ABC; font-size: 1.15em;font-weight: bold;"><?php echo __('Suppress Filter',true);?> </span></td>
              <td style="width:auto;"  colspan="7"></td>
              
            </tr>
            <tr>
            <td >&nbsp;</td>
              <td><?php echo $this->element("search_report/checkbox_orig_rate_table")?></td>
              <td >&nbsp;</td>
              <td ><?php echo $this->element("search_report/checkbox_term_rate_table")?></td>
              <td >&nbsp;</td>
              <td><?php echo $this->element("search_report/checkbox_ingress")?></td>
              
             </tr>
             <tr> <td >&nbsp;</td>
              <td><?php echo $this->element("search_report/checkbox_egress")?></td>
                <td >&nbsp;</td>
              <td><?php echo $this->element("search_report/checkbox_orig_host")?></td>
             <td >&nbsp;</td>
              <td><?php echo $this->element("search_report/checkbox_term_host")?></td>
            </tr>
          </table>
         </td>
      </tr>
      -->
    </tbody>
  </table>
  

<script type="text/javascript">
function check_action(value){
        if(value=='Process'){
            $('#action_type').val('Process');
            if($('#CdrRerateRateTable').val() == '')
            {
                jQuery('#CdrRerateRateTable').addClass('invalid');
                jGrowl_to_notyfy('You must be select a Rate Table!',{theme:'jmsg-error'});
                return false;
            }
           
        }
        jQuery('#report_form').submit();			
}	

$(function() {
    $('#CdrOrigCarrierSelect').change(function() {
        var client_id = $(this).val();
        var $CdrOrigHost = $('#CdrOrigHost');
        var $rate_table = $('#query-id_rates_name');
        if(client_id != '')
        {
            $.ajax({
                'url'      : '<?php echo $this->webroot; ?>cdrreports_db/get_ingress_host_by_client_id',
                'type'     : 'POST',
                'dataType' : 'json',
                'data'     : {'client_id' : client_id},
                'success'  : function(data) {
                    $CdrOrigHost.empty();
                    $CdrOrigHost.append('<option></option>');
                    $.each(data, function(index, value) {
                        $CdrOrigHost.append('<option>' + value[0]['ip'] +'</option>');
                    });
                }
            });
            
            $.ajax({
                'url'      : '<?php echo $this->webroot; ?>cdrreports_db/get__ingress_rate_table_by_client',
                'type'     : 'POST',
                'dataType' : 'json',
                'data'     : {'client_id' : client_id},
                'success'  : function(data) {
                    var $new_select = $('<select id="query-id_rates_name" class="input in-select select" name="query[rate_name]"></select>');
                    $rate_table.replaceWith($new_select);
                    $new_select.append('<option></option>');
                    $.each(data, function(index, value) {
                        $new_select.append('<option value="' + value[0]['rate_table_id'] +'">' + value[0]['name'] +'</option>');
                    });
                }
            });
            
        } else {
            $rate_table.replaceWith('<input id="query-id_rates_name" class="input in-text ac_input in-input" type="text" name="query[rate_name]" />');
        }
    });
    
    $('#CdrTermCarrierSelect').change(function() {
        var client_id = $(this).val();
        var $CdrTermHost = $('#CdrTermHost');
        var $rate_table = $('#query-id_rates_name_term');
        if(client_id != '')
        {
            $.ajax({
                'url'      : '<?php echo $this->webroot; ?>cdrreports_db/get_egress_host_by_client_id',
                'type'     : 'POST',
                'dataType' : 'json',
                'data'     : {'client_id' : client_id},
                'success'  : function(data) {
                    $CdrTermHost.empty();
                    $CdrTermHost.append('<option></option>');
                    $.each(data, function(index, value) {
                        $CdrTermHost.append('<option>' + value[0]['ip'] +'</option>');
                    });
                }
            });
            
            $.ajax({
                'url'      : '<?php echo $this->webroot; ?>cdrreports_db/get__egress_rate_table_by_client',
                'type'     : 'POST',
                'dataType' : 'json',
                'data'     : {'client_id' : client_id},
                'success'  : function(data) {
                    var $new_select = $('<select id="query-id_rates_name_term" class="input in-select select" name="query[rate_name_term]"></select>');
                    $rate_table.replaceWith($new_select);
                    $new_select.append('<option></option>');
                    $.each(data, function(index, value) {
                        $new_select.append('<option value="' + value[0]['rate_table_id'] +'">' + value[0]['name'] +'</option>');
                    });
                }
            });
        } else {
            $rate_table.replaceWith('<input id="query-id_rates_name_term" class="input in-text ac_input in-input" type="text" name="query[rate_name_term]" />');
        }
    });
    
    
    $('#CdrEgressAlias').change(function() {
        var ingress_id = $(this).val();
        var $CdrTermHost = $('#CdrTermHost');
        var $rate_table = $('#query-id_rates_name_term');
        if(ingress_id != '')
        {
            $.ajax({
                'url'      : '<?php echo $this->webroot; ?>cdrreports_db/get_ingress_host_by_ingress_id',
                'type'     : 'POST',
                'dataType' : 'json',
                'data'     : {'ingress_id' : ingress_id},
                'success'  : function(data) {
                    $CdrTermHost.empty();
                    $CdrTermHost.append('<option></option>');
                    $.each(data, function(index, value) {
                        $CdrTermHost.append('<option>' + value[0]['ip'] +'</option>');
                    });
                }
            });
            
            $.ajax({
                'url'      : '<?php echo $this->webroot; ?>cdrreports_db/get__egress_rate_table_by_egress',
                'type'     : 'POST',
                'dataType' : 'json',
                'data'     : {'ingress_id' : ingress_id},
                'success'  : function(data) {
                    var $new_select = $('<select id="query-id_rates_name_term" class="input in-select select" name="query[rate_name_term]"></select>');
                    $rate_table.replaceWith($new_select);
                    $new_select.append('<option></option>');
                    $.each(data, function(index, value) {
                        $new_select.append('<option value="' + value[0]['rate_table_id'] +'">' + value[0]['name'] +'</option>');
                    });
                }
            });
        } else {
            $rate_table.replaceWith('<input id="query-id_rates_name_term" class="input in-text ac_input in-input" type="text" name="query[rate_name_term]" />');
        }
    });
    
    
    $('#CdrIngressAlias').change(function() {
        var ingress_id = $(this).val();
        var $CdrOrigHost = $('#CdrOrigHost');
        var $rate_table = $('#query-id_rates_name');
        if(ingress_id != '')
        {
            $.ajax({
                'url'      : '<?php echo $this->webroot; ?>cdrreports_db/get_ingress_host_by_ingress_id',
                'type'     : 'POST',
                'dataType' : 'json',
                'data'     : {'ingress_id' : ingress_id},
                'success'  : function(data) {
                    $CdrOrigHost.empty();
                    $CdrOrigHost.append('<option></option>');
                    $.each(data, function(index, value) {
                        $CdrOrigHost.append('<option>' + value[0]['ip'] +'</option>');
                    });
                }
            });
            
            $.ajax({
                'url'      : '<?php echo $this->webroot; ?>cdrreports_db/get__ingress_rate_table_by_ingress',
                'type'     : 'POST',
                'dataType' : 'json',
                'data'     : {'ingress_id' : ingress_id},
                'success'  : function(data) {
                    var $new_select = $('<select id="query-id_rates_name" class="input in-select select" name="query[rate_name]"></select>');
                    $rate_table.replaceWith($new_select);
                    $new_select.append('<option></option>');
                    $.each(data, function(index, value) {
                        $new_select.append('<option value="' + value[0]['rate_table_id'] +'">' + value[0]['name'] +'</option>');
                    });
                }
            });
        } else {
            $rate_table.replaceWith('<input id="query-id_rates_name" class="input in-text ac_input in-input" type="text" name="query[rate_name]" />');
        }
    });
});
</script> 
