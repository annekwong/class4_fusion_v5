<style type="text/css">
    .cb_select{width:200px;}
    .table-white .period-block td,.table-white{border:0;}
</style>
<fieldset class="query-box" style=" clear:both;overflow:hidden;margin-top:10px;">
    <h4 style="display: inline-block;" class="heading glyphicons search"><i></i> <?php __('search')?></h4>
    <div title="Advance" class="pull-right">
        <a href="###" class="btn" id="advance_btn">
            <i class="icon-long-arrow-down"></i> 
        </a>
    </div>
    <div class="clearfix"></div>
  <?php echo $this->element('search_report/search_js');?>
  
  <table class="form footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary" style="width: 100%">
    <tbody>
      <?php echo $this->element('report/form_period',array('group_time'=>false, 'gettype'=>'<select id="query-output"
				onchange="repaintOutput();" name="query[output]"
				class="input in-select">
            <option value="web">Web</option>
            <option value="csv">Excel CSV</option>
            <option value="xls">Excel XLS</option>
          </select>'))?>
    </tbody>
  </table>
    <div id="advance_panel" class="widget widget-heading-simple widget-body-gray" style="display: none;">
        <table class="form" style="width:100%">
            <tbody>
            <tr class="period-block" style="height:20px; line-height:20px;">
              <td colspan="2" style="text-align:center; font-size:14px;"><b><?php echo __('Inbound',true);?></b></td>
              <td>&nbsp;</td>
              <td colspan="2" style="text-align:center;font-size:14px;"><b><?php echo __('Outbound',true);?></b></td>
              <td>&nbsp;</td>
              <td >&nbsp;</td>
              <td ></td>
            </tr>
            <tr> <?php echo $this->element('search_report/orig_carrier_select');?><td>&nbsp;</td><?php echo $this->element('search_report/term_carrier_select');?><td>&nbsp;</td>
              <td  valign="top" rowspan="8" colspan="2"
                                      style="padding-left:10px; width:25%;"><div align="left"><?php echo __('Show Fields',true);?></div>
                <?php

                                                      echo $form->select('Cdr.field', $cdr_field , $show_field_array,array('id'=>'query-fields',  'style'=>'width:100%; height: 300px;', 'name'=>'query[fields]','type' => 'select', 'multiple' => true),false);
                                              ?></td>
            </tr>
            <tr>
                <td class="align_right padding-r10"><?php echo __('Ingress',true);?></td>
              <td ><?php 
                                                                              echo $form->input('ingress_alias',
                                                                              array('options'=>$ingress,'empty'=>'','label'=>false ,'div'=>false,'type'=>'select'));
                                                               ?><?php echo $this->element('search_report/ss_clear_input_select');?></td>
              <td>&nbsp;</td>
              <td class="align_right padding-r10"><?php echo __('Egress',true);?></td>
              <td ><?php 
                                                                      echo $form->input('egress_alias',
                                                                      array('options'=>$egress,'empty'=>'','label'=>false ,'div'=>false,'type'=>'select'));
                                                              ?><?php echo $this->element('search_report/ss_clear_input_select');?></td>
                                  <td>&nbsp;</td>
            </tr>
            <tr>
             <?php echo $this->element('search_report/search_orig_country'); ?>
             <td>&nbsp;</td>
                    <?php echo $this->element('search_report/search_term_country'); ?>
                <td>&nbsp;</td>

                      </tr>

              <tr>
              <?php echo $this->element('search_report/search_orig_code_name'); ?>
              <td>&nbsp;</td>
              <?php echo $this->element('search_report/search_term_code_name'); ?>
              <td>&nbsp;</td>
              </tr>
              <tr>
              <?php echo $this->element('search_report/search_orig_code'); ?>
              <td>&nbsp;</td>
              <?php echo $this->element('search_report/search_term_code'); ?>
              <td>&nbsp;</td>
              </tr>

            <tr>
              <td class="align_right padding-r10"><?php echo __('Origination Host',true);?></td>
              <td ><?php 
                                                                      echo $form->input('orig_host',
                                                                      array('options'=>$all_host,'empty'=>'','name'=>'orig_host','label'=>false ,'div'=>false,'type'=>'select'));
                                                              ?><?php echo $this->element('search_report/ss_clear_input_select');?></td>
              <td>&nbsp;</td>
              <td class="align_right padding-r10"><?php echo __('Termination Host',true);?></td>
              <td ><?php 
                                                                      echo $form->input('term_host',
                                                                      array('options'=>$all_host,'name'=>'term_host','empty'=>'','label'=>false ,'div'=>false,'type'=>'select'));
                                                              ?><?php echo $this->element('search_report/ss_clear_input_select');?></td>
                                  <td>&nbsp;</td>
            </tr>
            <tr><?php echo  $this->element('search_report/search_orig_rate')?>
            <td>&nbsp;</td>
                <?php echo  $this->element('search_report/search_term_rate')?><td>&nbsp;</td>
            </tr>

            <tr>
              <td class="align_right padding-r10"><?php echo __('Response to ingress',true);?></td>
              <td ><select id="query-res_status_ingress"
                                      onchange="$('#query-disconnect_cause_ingress').val($('#query-res_status_ingress').val());"
                                      name="query[res_status_ingress]" class="input in-select" style="width:168px;">
                  <option value=""><?php __('all')?></option>
                        <option value="200"><?php __('success')?></option>
                        <option value="300"><?php __('multiple')?></option>
                        <option value="301"><?php __('moved permanently')?></option>
                        <option value="302"><?php __('moved temporaily')?></option>
                        <option value="305"><?php __('use proxy')?></option>
                        <option value="380"><?php __('alternative service')?></option>
                        <option value="400"><?php __('bad request')?></option>
                        <option value="401"><?php __('unauthorized')?></option>
                        <option value="402"><?php __('payment required')?></option>
                        <option value="403"><?php __('forbidden')?></option>
                        <option value="404"><?php __('not found')?></option>
                        <option value="405"><?php __('method no allowed')?></option>
                        <option value="406"><?php __('not acceptable')?></option>
                        <option value="407"><?php __('proxy authentication required')?></option>
                        <option value="408"><?php __('request timeout')?></option>
                        <option value="410"><?php __('gone')?></option>
                        <option value="413"><?php __('request entity too large')?></option>
                        <option value="414"><?php __('request-url too long')?></option>
                        <option value="415"><?php __('unsupported media type')?></option>
                        <option value="416"><?php __('unsupported url scheme')?></option>
                        <option value="420"><?php __('bad extension')?></option>
                        <option value="421"><?php __('extension required')?></option>
                        <option value="423"><?php __('interval too brief')?></option>
                        <option value="480"><?php __('temporarily unavailable')?></option>
                        <option value="481"><?php __('call/transaction does not exist')?></option>
                        <option value="482"><?php __('loop detected')?></option>
                        <option value="483"><?php __('too many hops')?></option>
                        <option value="484"><?php __('address incomplete')?></option>
                        <option value="485"><?php __('ambiguous')?></option>
                        <option value="486"><?php __('busy here')?></option>
                        <option value="487"><?php __('request terminated')?></option>
                        <option value="488"><?php __('not acceptable here')?></option>
                        <option value="491"><?php __('request pending')?></option>
                        <option value="493"><?php __('undecipherable')?></option>
                        <option value="500"><?php __('server internal error')?></option>
                        <option value="501"><?php __('not implemented')?></option>
                        <option value="502"><?php __('bad gateway')?></option>
                        <option value="503"><?php __('service unavailable')?></option>
                        <option value="504"><?php __('server time-out')?> </option>
                        <option value="505"><?php __('version not supported')?> </option>
                        <option value="513"><?php __('message too large')?> </option>
                        <option value="600"><?php __('busy everywhere')?> </option>
                        <option value="603"><?php __('decline')?> </option>
                        <option value="604"><?php __('does not exist anywhere')?> </option>
                        <option value="606"><?php __('not acceptable')?> </option>
                </select>
                <input type="text" id="query-disconnect_cause_ingress"
                                      style="width: 35px;" value="" name="query[disconnect_cause_ingress]"
                                      class="input in-text"><?php echo $this->element('search_report/ss_clear_input_select');?></td>
              <td>&nbsp;</td>
              <td class="align_right padding-r10"><?php echo __('Response from egress',true);?></td>
              <td ><select id="query-res_status"
                                      onchange="$('#query-disconnect_cause').val($('#query-res_status').val());"
                                      name="query[res_status]" class="input in-select" style="width:168px;">
                  <option value=""><?php __('all')?></option>
                        <option value="200"><?php __('success')?></option>
                        <option value="300"><?php __('multiple')?></option>
                        <option value="301"><?php __('moved permanently')?></option>
                        <option value="302"><?php __('moved temporaily')?></option>
                        <option value="305"><?php __('use proxy')?></option>
                        <option value="380"><?php __('alternative service')?></option>
                        <option value="400"><?php __('bad request')?></option>
                        <option value="401"><?php __('unauthorized')?></option>
                        <option value="402"><?php __('payment required')?></option>
                        <option value="403"><?php __('forbidden')?></option>
                        <option value="404"><?php __('not found')?></option>
                        <option value="405"><?php __('method no allowed')?></option>
                        <option value="406"><?php __('not acceptable')?></option>
                        <option value="407"><?php __('proxy authentication required')?></option>
                        <option value="408"><?php __('request timeout')?></option>
                        <option value="410"><?php __('gone')?></option>
                        <option value="413"><?php __('request entity too large')?></option>
                        <option value="414"><?php __('request-url too long')?></option>
                        <option value="415"><?php __('unsupported media type')?></option>
                        <option value="416"><?php __('unsupported url scheme')?></option>
                        <option value="420"><?php __('bad extension')?></option>
                        <option value="421"><?php __('extension required')?></option>
                        <option value="423"><?php __('interval too brief')?></option>
                        <option value="480"><?php __('temporarily unavailable')?></option>
                        <option value="481"><?php __('call/transaction does not exist')?></option>
                        <option value="482"><?php __('loop detected')?></option>
                        <option value="483"><?php __('too many hops')?></option>
                        <option value="484"><?php __('address incomplete')?></option>
                        <option value="485"><?php __('ambiguous')?></option>
                        <option value="486"><?php __('busy here')?></option>
                        <option value="487"><?php __('request terminated')?></option>
                        <option value="488"><?php __('not acceptable here')?></option>
                        <option value="491"><?php __('request pending')?></option>
                        <option value="493"><?php __('undecipherable')?></option>
                        <option value="500"><?php __('server internal error')?></option>
                        <option value="501"><?php __('not implemented')?></option>
                        <option value="502"><?php __('bad gateway')?></option>
                        <option value="503"><?php __('service unavailable')?></option>
                        <option value="504"><?php __('server time-out')?> </option>
                        <option value="505"><?php __('version not supported')?> </option>
                        <option value="513"><?php __('message too large')?> </option>
                        <option value="600"><?php __('busy everywhere')?> </option>
                        <option value="603"><?php __('decline')?> </option>
                        <option value="604"><?php __('does not exist anywhere')?> </option>
                        <option value="606"><?php __('not acceptable')?> </option>
                </select>
                <input type="text" id="query-disconnect_cause"
                                      style="width: 35px;" value="" name="query[disconnect_cause]"
                                      class="input in-text"><?php echo $this->element('search_report/ss_clear_input_select');?></td>
                      <td>&nbsp;</td>
            </tr>
            <tr>
              <td class="align_right padding-r10"> <?php echo __('Ingress Cost',true);?></td>
              <td ><select id="query-cost" name="query[cost]"
                                      class="input in-select">
                  <option value=""><?php __('all')?></option>
                  <option value="nonzero"><?php __('non-zero')?></option>
                  <option value="zero"><?php __('zero')?></option>
                </select><?php echo $this->element('search_report/ss_clear_input_select');?></td>
             <td>&nbsp;</td>
              <td class="align_right padding-r10"><?php echo __('Egress Cost',true);?></td>
              <td ><select id="query-cost_term" name="query[cost_term]"
                                      class="input in-select">
                  <option value=""><?php __('all')?></option>
                  <option value="nonzero"><?php __('non-zero')?></option>
                  <option value="zero"><?php __('zero')?></option>
                </select><?php echo $this->element('search_report/ss_clear_input_select');?></td><td>&nbsp;</td>
                <td class="align_right padding-r10"><?php echo __('Duration',true);?></td>
              <td ><select id="query-duration" name="query[duration]"
                                      class="input in-select">
                  <option value="" selected="selected"><?php __('all')?></option>
                  <option value="nonzero"><?php __('non-zero')?></option>
                  <option value="zero"><?php __('zero')?></option>
                </select></td>
            </tr>

            <tr>
                <td class="align_right padding-r10"><?php echo __('ani',true);?></td>
                <td ><input type="text" id="query-src_number" value=""
                                      name="query[src_number]" style="width:220px;" class="input in-text"><?php echo $this->element('search_report/ss_clear_input_select');?></td>
                <td>&nbsp;</td>
                <td class="align_right padding-r10"><?php echo __('ani',true);?></td>
              <td ><input type="text" id="query-term_src_number" value=""
                                      name="query[term_src_number]" style="width:220px;" class="input in-text">
                  <td>&nbsp;</td>
              <td colspan="2"></td>
            </tr>

            <tr>
              <td class="align_right padding-r10"><?php echo __('dnis',true);?>
                <select name="query[dnis_type]" style="width:80px;">
                  <option value=""><?php __('Start with')?></option>
                  <option value="1" <?php echo !empty($_REQUEST['query']['dnis_type'])?'selected':''; ?> ><?php __('Not Start with')?></option>
                </select>
                 </td>
              <td ><input type="text" id="query-dst_number" value=""
                          name="query[dst_number]" class="input in-text" style="width:220px;"><?php echo $this->element('search_report/ss_clear_input_select');?></td>
              <td>&nbsp;</td>
              <td class="align_right padding-r10"><?php echo __('dnis',true);?></td>
              <td ><input type="text" id="query-term_dst_number" value=""
                                      name="query[term_dst_number]" class="input in-text" style="width:220px;"><?php echo $this->element('search_report/ss_clear_input_select');?></td>
                      <td>&nbsp;</td>
                      <td ><span></span></td>
              <td ></td>

            </tr>
            <!--
            <tr>
              <td colspan="8"><table style="width: 100%;">
                  <tr  class="period-block">
                    <td   style=" width:100px;text-align: left;"><span  style="color: #568ABC; font-size: 1.15em;font-weight: bold;"><?php echo __('Suppress Filter',true);?> </span></td>
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
    </div>

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
                'url'      : '<?php echo $this->webroot; ?>cdrreports/get_ingress_host_by_client_id',
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
                'url'      : '<?php echo $this->webroot; ?>cdrreports/get__ingress_rate_table_by_client',
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
                'url'      : '<?php echo $this->webroot; ?>cdrreports/get_egress_host_by_client_id',
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
                'url'      : '<?php echo $this->webroot; ?>cdrreports/get__egress_rate_table_by_client',
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
                'url'      : '<?php echo $this->webroot; ?>cdrreports/get_ingress_host_by_ingress_id',
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
                'url'      : '<?php echo $this->webroot; ?>cdrreports/get__egress_rate_table_by_egress',
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
                'url'      : '<?php echo $this->webroot; ?>cdrreports/get_ingress_host_by_ingress_id',
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
                'url'      : '<?php echo $this->webroot; ?>cdrreports/get__ingress_rate_table_by_ingress',
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
