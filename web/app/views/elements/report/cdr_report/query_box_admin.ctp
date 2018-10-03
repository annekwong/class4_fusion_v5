<fieldset class="query-box" style=" clear:both;overflow:hidden;margin-top:10px;">
    <h4 style="display: inline-block;" class="heading glyphicons search"><i></i> <?php __('search')?></h4>
    <div title="Advance" class="pull-right">
        <a href="###" class="btn" id="advance_btn">
            <i class="icon-long-arrow-down"></i> 
        </a>
    </div>
    <div class="clearfix"></div>
    <?php echo $this->element('search_report/search_js'); ?>
    <?php echo $this->element('search_report/search_js_show'); ?>
    <?php
    $url = "/" . $this->params['url']['url'];
//if($rate_type=='spam'){$url='/cdrreports/summary_reports/spam/';}else{$url='/cdrreports/summary_reports/';}
    echo $form->create('Cdr', array('type' => 'get', 'url' => $url, 'id' => 'report_form',
        'onsubmit' => ""));
    ?>
    <?php echo $appCommon->show_page_hidden(); ?> <?php echo $this->element('search_report/search_hide_input'); ?>
    <input type="hidden" name="open_callmonitor" value="<?php echo isset($_GET['open_callmonitor']) and $_GET['open_callmonitor'] == 1 ? 1 : 0; ?>">
    <input type="hidden" name="min_start_date" value="<?php echo isset($_GET['min_start_date']) ? $_GET['min_start_date'] : 0; ?>">
    <input type="hidden" name="min_start_time" value="<?php echo isset($_GET['min_start_time']) ? $_GET['min_start_time'] : 0; ?>">
    <input type="hidden" name="max_stop_date" value="<?php echo isset($_GET['max_stop_date']) ? $_GET['max_stop_date'] : 0; ?>">
    <input type="hidden" name="max_stop_time" value="<?php echo isset($_GET['max_stop_time']) ? $_GET['max_stop_time'] : 0; ?>">
    <input type="hidden" id="real_send_mail_address" name="send_mail_address" />
    <table class="form" style="width: 100%">
        <tbody>
            <?php
            echo $this->element('report/form_period', array('group_time' => false, 'gettype' => '<select id="query-output"
				onchange="repaintOutput();" name="query[output]"
				class="input in-select">
            <option value="web">Web</option>
            <option value="csv">Excel CSV</option>
            <option value="xls">Excel XLS</option>
            
          </select>'
            ))
            ?>
        </tbody>
    </table>
    <div id="advance_panel" class="widget widget-heading-simple widget-body-gray" style="display: none;">
        <table class="form" style="width:100%">
            <tbody>
            <!--<option value="noemail">Download in Backend</option>-->
            <tr>
                <td class="align_right padding-r10"><?php if (count($report_server)): ?><?php echo __('Report-server', true); ?><?php endif; ?></td>
                <td><?php if (count($report_server)): ?>
                        <?php //echo $form->input('server_ip', array('options' => $report_server, 'empty' => '',  'default' =>'192.168.1.107', 'label' => false, 'div' => false, 'type' => 'select')); ?>
                        <select name="report_ip">
                            <?php
                            foreach ($report_server as $key=>$report_server_item)
                            {
                                ?>
                                <option value="<?php echo $report_server_item ?>" <?php
                                if (isset($_GET['report_ip']) && !strcmp($_GET['report_ip'], $report_server_item))
                                {
                                    ?> selected="selected"<?php } ?> ><?php echo $key ?></option> 
                        <?php } ?>  
                        </select>
<?php endif; ?>
                </td>
                <td>&nbsp;</td>
                <td><?php //if (count($server) > 1):   ?><?php //echo __('Class4-server', true);   ?><?php //endif;   ?></td>
                <td><?php //if (count($server) > 1): ?>
                    <?php //echo $form->input('server_ip', array('options' => $server, 'empty' => '', 'label' => false, 'div' => false, 'type' => 'select'));    ?>
<?php //endif;      ?>
                </td>
                <td>&nbsp;</td>
                </td>


            </tr>
            <tr class="period-block" style="height:20px; line-height:20px;">
                <td colspan="2" style="text-align:center; font-size:14px;"><b><?php echo __('Inbound', true); ?></b></td>
                <td>&nbsp;</td>
                <td colspan="2" style="text-align:center;font-size:14px;"><b><?php echo __('Outbound', true); ?></b></td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td></td>
            </tr>
            <tr> <?php echo $this->element('search_report/orig_carrier_select'); ?>
                <td>&nbsp;</td>
<?php echo $this->element('search_report/term_carrier_select'); ?>
                <td>&nbsp;</td>
                <td  valign="top" rowspan="9" colspan="2"
                     style="padding-left: 10px;width:25%;"><div align="left"><?php echo __('Show Fields', true); ?>:</div>
                         <?php
                    echo $form->select('Cdr.field', $cdr_field, $show_field_array, array('id' => 'query-fields', 'style' => 'width:100%; height: 350px;', 'name' => 'query[fields]', 'type' => 'select', 'multiple' => true), false);
                    ?></td>
            </tr>
            <tr>
                <td class="align_right padding-r10"><?php echo __('Ingress', true); ?></td>
                <td><?php
                    echo $form->input('ingress_alias', array('options' => $ingress, 'label' => false, 'div' => false, 'type' => 'select', 'onchange' => 'getTechPrefix(this);'));
                    ?><?php echo $this->element('search_report/ss_clear_input_select'); ?></td>
                <td>&nbsp;</td>
                <td class="align_right padding-r10"><?php echo __('Egress', true); ?></td>
                <td><?php echo $form->input('egress_alias', array('options' => $egress, 'label' => false, 'empty' => '', 'div' => false, 'type' => 'select')); ?><?php echo $this->element('search_report/ss_clear_input_select'); ?></td>
                <td>&nbsp;</td>

            </tr>
            <tr>

                <td class="align_right padding-r10"><?php __('Tech Prefix')?></td>
                <td>
                    <select name ="route_prefix" id="CdrRoutePrefix">
                        <option value="">
                            <?php __('All')?>
                        </option>
                        <?php
                        if (!empty($tech_perfix))
                        {
                            foreach ($tech_perfix as $te)
                            {
                                if ($_GET['route_prefix'] == $te[0]['tech_prefix'])
                                {
                                    echo "<option selected value='" . $te[0]['tech_prefix'] . "'>" . $te[0]['tech_prefix'] . "</option>";
                                }
                                else
                                {
                                    echo "<option value='" . $te[0]['tech_prefix'] . "'>" . $te[0]['tech_prefix'] . "</option>";
                                }
                            }
                        }
                        ?>   
                    </select>


                </td>
                <td>&nbsp;</td>

                <td class="align_right padding-r10"><span rel="helptip" class="helptip" id="ht-100002"><?php echo __('Interval second', true); ?></span> <span class="tooltip" id="ht-100002-tooltip">Duration interval in seconds</span></td>
                <td><input type="text" id="query-interval_from"
                           class="in-digits input in-text" style="width: 87px;" value=""
                           name="query[interval_from]">
                    &mdash;
                    <input type="text"
                           id="query-interval_to" class="in-digits input in-text"
                           style="width: 87px;" value="" name="query[interval_to]"><?php echo $this->element('search_report/ss_clear_input_select'); ?></td>
                <td>&nbsp;</td>
            </tr>
            <tr> 

                <?php echo $this->element('search_report/search_orig_country') ?>
                <td>&nbsp;</td>
<?php echo $this->element('search_report/search_term_country') ?>
                <td>&nbsp;</td>

            <tr>
                <?php echo $this->element('search_report/search_orig_code_name'); ?> <td>&nbsp;</td>
                <?php echo $this->element('search_report/search_term_code_name'); ?> <td>&nbsp;</td></tr>
            <tr>
                <?php echo $this->element('search_report/search_orig_code'); ?><td>&nbsp;</td>
<?php echo $this->element('search_report/search_term_code'); ?> <td>&nbsp;</td></tr>

            <tr>
                <td class="align_right padding-r10"><?php echo __('Response to ingress', true); ?></td>
                <td><select id="query-res_status_ingress"
                            onchange="$('#query-disconnect_cause_ingress').val($('#query-res_status_ingress').val());"
                            name="query[res_status_ingress]" class="input in-select" style="width: 168px;">
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
                           class="input in-text"><?php echo $this->element('search_report/ss_clear_input_select'); ?></td>
                <td>&nbsp;</td>
                <td class="align_right padding-r10"><?php echo __('Response from egress', true); ?></td>
                <td><select id="query-res_status"
                            onchange="$('#query-disconnect_cause').val($('#query-res_status').val());"
                            name="query[res_status]" class="input in-select" style="width: 168px;">
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
                           class="input in-text"><?php echo $this->element('search_report/ss_clear_input_select'); ?></td>
                <td>&nbsp;</td>

            </tr>
            <tr>
                <td class="align_right padding-r10"><?php echo __('Duration', true); ?></td>
                <td><select id="query-duration" name="query[duration]"
                            class="input in-select">
                        <option value="" selected="selected"><?php __('all')?></option>
                        <option value="nonzero"><?php __('non-zero')?></option>
                        <option value="zero"><?php __('zero')?></option>
                    </select><?php echo $this->element('search_report/ss_clear_input_select'); ?></td><td>&nbsp;</td>        
                <td class="align_right padding-r10"><?php echo __('Cost', true); ?></td>
                <td><select id="query-cost" name="query[cost]"
                            class="input in-select">
                        <option value=""><?php __('all')?></option>
                        <option value="nonzero"><?php __('non-zero')?></option>
                        <option value="zero"><?php __('zero')?></option>
                    </select><?php echo $this->element('search_report/ss_clear_input_select'); ?></td>
                <td>&nbsp;</td>        
            </tr>
            <tr>

                <td class="align_right padding-r10"><?php echo __('Release Cause', true); ?></td>
                <td><?php
                    $type = $appCdr->show_release_cause();
                    echo $form->input('cdr_release_cause', array('options' => $type, 'name' => 'cdr_release_cause', 'label' => false, 'div' => false, 'type' => 'select'));
                    ?><?php echo $this->element('search_report/ss_clear_input_select'); ?></td>
                <td>&nbsp;</td>
                <td><?php //if (count($server) > 1):   ?><?php //echo __('Class4-server', true);   ?><?php //endif;   ?></td>
                <td><?php //if (count($server) > 1): ?>
                    <?php //echo $form->input('server_ip', array('options' => $server, 'empty' => '', 'label' => false, 'div' => false, 'type' => 'select'));    ?>
<?php //endif;      ?>
                </td>
                <td>&nbsp;</td>

                <!--  
                                <td><span rel="helptip" class="helptip" id="ht-100001">TERM <?php __('codedecks') ?></span>
                                <span class="tooltip" id="ht-100001-tooltip"> <b>Use pre-assigned</b>
                                &mdash; means usage of code decks assigned to each pulled client or
                                rate table. <br>
                                <br>
                                If you will <b>specify</b> a code deck, all code names will be
                                rewritten using names from selected code deck, so all data will be
                                unified by code names. </span>:</td>
                                
                                
                                
                                <td>
<?php echo $form->input('code_deck', array('options' => $code_deck, 'empty' => '', 'label' => false, 'div' => false, 'type' => 'select')); ?>
            </td>
                --> 

            </tr>
            <tr>
                <td class="align_right padding-r10"><?php echo __('ANI', true); ?> </td>
                <td><input type="text" id="query-src_number" value=""
                           name="query[src_number]" class="input in-text" style="width: 220px;"><?php echo $this->element('search_report/ss_clear_input_select'); ?></td>
                <td>&nbsp;</td>
                <td class="align_right padding-r10"><?php echo __('ANI', true); ?></td>
                <td><input type="text" id="query-term_src_number" value=""
                           name="query[term_src_number]" class="input in-text" style="width: 220px;">

<?php echo $this->element('search_report/ss_clear_input_select'); ?></td><td>&nbsp;</td>
<!--                <td><?php __('Currency') ?></td>-->
<!--                <td id="client_cell">
                    <select id="currency" name="currency">
                        <option></option>
                <?php foreach ($currency as $cur): ?>
                                                <option value="<?php echo $cur[0]['currency_id']; ?>" <?php if (isset($_GET['currency']) && $_GET['currency'] == $cur[0]['currency_id']) echo 'selected' ?>><?php echo $cur[0]['code']; ?></option>
                <?php endforeach; ?>
                    </select>
<?php echo $this->element('search_report/ss_clear_input_select'); ?></td>-->


            </tr>

            <tr>
                <td class="align_right padding-r10"><?php echo __('DNIS', true); ?> </td>
                <td><input type="text" id="query-dst_number" value=""
                           name="query[dst_number]" class="input in-text" style="width: 220px;"><?php echo $this->element('search_report/ss_clear_input_select'); ?></td>
                <td>&nbsp;</td>
                <td class="align_right padding-r10"><?php echo __('DNIS', true); ?></td>
                <td><input type="text" id="query-term_dst_number" value=""
                           name="query[term_dst_number]" class="input in-text" style="width: 220px;">
<?php echo $this->element('search_report/ss_clear_input_select'); ?></td><td>&nbsp;</td>
    <!--                <td><?php echo __('Type') ?>:</td>
                <td><?php
                $type = array('' => __('all', true), 'orig' => __('origination', true), 'term' => __('termination', true));
                echo $form->input('report_type', array('options' => $type, 'label' => false, 'div' => false, 'type' => 'select'));
                ?></td>       -->
            </tr>
    <!--            <tr>
                <td><?php echo __('Orig Call ID', true); ?>:</td>
                <td><input type="text" id="query-orig_call_id" 
                           name="query[orig_call_id]" class="input in-text">
<?php echo $this->element('search_report/ss_clear_input_select'); ?></td><td>&nbsp;</td>
                <td><?php echo __('Term Call ID', true); ?> :</td>
                <td><input type="text" id="query-term_call_id" value=""
                           name="query[term_call_id]" class="input in-text"><?php echo $this->element('search_report/ss_clear_input_select'); ?></td>
                <td>&nbsp;</td>
    
            <?php
            if ($_SESSION['role_menu']['Statistics']['cdrreports']['model_x'])
            {
                ?>
                                        <td></td>
                                        <td></td>
<?php } ?>
    
            </tr>-->
            <!--<tr>
                              <td><?php echo __('Release Cause', true); ?>:</td>
                              <td>
            <?php
            $type = $appCdr->show_release_cause();
            echo $form->input('cdr_release_cause', array('options' => $type, 'name' => 'cdr_release_cause', 'label' => false, 'div' => false, 'type' => 'select'));
            ?>
                                  
                                  
                                  </td>
                              
      
            </tr>-->
            <?php
            if ($rate_type == 'spam')
            {
                ?>
                <tr>
                    <td><input type="checkbox"
                        <?php
                        if (isset($_GET['invalid_ingress_ip']))
                        {
                            echo "checked='checked'";
                        }
                        ?>
                               class="input in-checkbox" name="invalid_ingress_ip" value="false"
                               id="invalid_ingress_ip"
                               onclick="$(this).attr('checked') == true ? $(this).attr('value', 'true') : $(this).attr('value', 'false');"></td>
                    <td><label for="query-output_subgroups"><span
                                id="ht-100146"><?php echo __('Invalid Ingress IP', true); ?></span></label></td>
                    <td>&nbsp;</td>
                    <td><input type="checkbox"
                        <?php
                        if (isset($_GET['no_product_found']))
                        {
                            echo "checked='checked'";
                        }
                        ?>
                               onclick="$(this).attr('checked') == true ? $(this).attr('value', 'true') : $(this).attr('value', 'false');"
                               class="input in-checkbox" name="no_product_found" value="false"
                               id="no_product_found"></td>
                    <td><label for="query-output_subtotals"><span
                                id="ht-100147"><?php echo __('No Product Found', true); ?></span></label></td>
                    <td>&nbsp;</td>
                    <td><input type="checkbox"
                        <?php
                        if (isset($_GET['no_code_found']))
                        {
                            echo "checked='checked'";
                        }
                        ?>
                               onclick="$(this).attr('checked') == true ? $(this).attr('value', 'true') : $(this).attr('value', 'false');"
                               class="input in-checkbox" name="no_code_found" value="false"
                               id="no_code_found"></td>
                    <td><label for="query-output_subtotals"><span
                                id="ht-100147"><?php echo __('No Code Found', true); ?></span></label></td>
                </tr>

<?php } ?>



        </tbody>
    </table>
    </div>
</fieldset>

<?php echo $form->end(); ?>

<div id="pop-div" closed="true" class="easyui-dialog" title="CDR Email When Done" style="display: none;" >
    <!--     data-options="iconCls:'icon-save',resizable:true,modal:true">-->
    <div class="product_list">
        <label><?php __('Email Address')?>:</label>
        <input type="text" class="input in-text in-input" id="send_email" />
    </div>
</div> 

<script src="<?php echo $this->webroot ?>js/jquery.jgrowl.js" type="text/javascript"></script>

<script type="text/javascript">
                               function getTechPrefix(obj) {
                                   $("#CdrRoutePrefix").empty();
                                   $("#CdrRoutePrefix").append("<option value=''>All</option>");
                                   if ($(obj).val() != '0') {
                                       $.post("<?php echo $this->webroot ?>cdrreports/getTechPerfix", {ingId: $(obj).val()},
                                       function(data) {
                                           $.each(data['prefixes'],
                                                   function(index, content) {
                                                       $("#CdrRoutePrefix").append("<option value='" + content[0]['tech_prefix'] + "'>" + content[0]['tech_prefix'] + "</option>");
                                                   }
                                           );
                                       }, 'json');

                                   }
                               }

                               $(function() {
                                   $("#report_form").submit(function() {

                                       if ($('#query-output').val() == 'web')
                                           loading();
                                       if ($('#query-output').val() == 'email') {
                                       var email = $('#real_send_mail_address').val();
                                               if (email)
                                       {
                                       return true;
                                       }
                                       $.ajax({
                                       'url': '<?php echo $this->webroot ?>cdrreports/check_email',
                                               'type': 'GET',
                                               'dataType': 'json',
                                               'async': false,
                                               'success': function(data) {
                                                   $('#send_email').val(data.email);
                                                   $('#pop-div').dialog({buttons: [{text: "Submit", "class": "btn btn-primary", click: function() {
                                                                   var val = $('#send_email').val();
                                                                   if (val != '')
                                                                   {
                                                                       $('#real_send_mail_address').val(val);
                                                                       $('#report_form').submit();
                                                                   }
                                                               }}]
                                                   });
                                                   return false;
                                                   }
                                           
                                       });
                                               return false;
                                       }
                                       return true;


                                   });


                               });

</script>