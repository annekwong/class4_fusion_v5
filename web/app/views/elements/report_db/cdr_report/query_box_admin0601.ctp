<fieldset class="query-box" style=" clear:both;overflow:hidden;margin-top:10px;">
    <h4 style="display: inline-block;" class="heading glyphicons search"><i></i> <?php __('search') ?></h4>
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
    //if($rate_type=='spam'){$url='/cdrreports_db/summary_reports/spam/';}else{$url='/cdrreports_db/summary_reports/';}
    echo $form->create('Cdr', array('type' => 'get', 'url' => $url, 'id' => 'report_form',
        'onsubmit' => "if ($('#query-output').val() == 'web') loading();"));
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
        if (!($this->params['controller'] == 'cdrreports_db' && $this->params['action'] == 'sip_packet'))
        {
            ?>

            <?php
            echo $this->element('report/form_period', array('group_time' => false, 'gettype' => '<select id="query-output"
                                        onchange="repaintOutput();" name="query[output]"
                                        class="input in-select">
                    <option value="web">Web</option>
                    <option value="csv">Excel CSV</option>
                    <!-- <option value="xls">Excel XLS</option> -->
                    <option value="email">Email when done</option>
                  </select>'
            ))
            ?>

            <?php
        }
        else
        {
            ?>

            <?php
            echo $this->element('report/form_period', array('group_time' => false, 'gettype' => '<select id="query-output"
                                        onchange="repaintOutput();" name="query[output]"
                                        class="input in-select">
                    <option value="web">Web</option>
                  </select>'
            ))
            ?>

            <?php
        }
        ?>


        </tbody>
    </table>
    <div id="advance_panel" class="widget widget-heading-simple widget-body-gray">
        <table class="form" style="width:100%">
            <tbody>
            <tr class="period-block" style="height:20px; line-height:20px;">
                <td colspan="2" style="text-align:center; font-size:14px;"><b><?php echo __('Inbound', true); ?></b></td>
                <td class="in-out_bound">&nbsp;</td>
                <?php
                if ($outbound_report)
                {
                    ?>
                    <td colspan="2" style="text-align:center;font-size:14px;"><b><?php echo __('Outbound', true); ?></b></td>
                    <td class="in-out_bound">&nbsp;</td>
                <?php } ?>
                <td>&nbsp;</td>
                <td></td>
            </tr>
            <tr> <?php echo $this->element('search_report/orig_carrier_select'); ?>
                <td class="in-out_bound">&nbsp;</td>
                <?php
                if ($outbound_report)
                {
                    ?>
                    <?php echo $this->element('search_report/term_carrier_select'); ?>
                    <td class="in-out_bound">&nbsp;</td>
                <?php } ?>
                <td  valign="top" rowspan="9" colspan="2"
                     style="padding-left: 10px;width:25%;">

                    <?php
                    if (!($this->params['controller'] == 'cdrreports_db' && $this->params['action'] == 'sip_packet'))
                    {
                        ?>

                        <div align="left"><?php echo __('Show Fields', true); ?>:</div>
                        <?php
                        echo $form->select('Cdr.field', $cdr_field, $show_field_array, array('id' => 'query-fields', 'style' => 'width:100%; height: 250px;', 'name' => 'query[fields]', 'type' => 'select', 'multiple' => true), false);
                        ?>
                        <?php
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <td class="align_right padding-r10"><?php echo __('Ingress', true); ?></td>
                <td><?php
                    echo $form->input('ingress_alias', array('options' => $ingress, 'label' => false, 'div' => false, 'type' => 'select', 'onchange' => 'getTechPrefix(this);'));
                    ?><?php echo $this->element('search_report/ss_clear_input_select'); ?></td>
                <td class="in-out_bound">&nbsp;</td>
                <?php
                if ($outbound_report)
                {
                    ?>
                    <td class="align_right padding-r10"><?php echo __('Egress', true); ?></td>
                    <td><?php echo $form->input('egress_alias', array('options' => $egress, 'label' => false, 'empty' => '', 'div' => false, 'type' => 'select')); ?><?php echo $this->element('search_report/ss_clear_input_select'); ?></td>
                    <td class="in-out_bound">&nbsp;</td>
                <?php } ?>

            </tr>
            <tr>

                <td class="align_right padding-r10"><?php __('Tech Prefix') ?></td>
                <td>
                    <select name ="route_prefix" id="CdrRoutePrefix">
                        <option value="">
                            <?php __('All') ?>
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
                <td class="in-out_bound">&nbsp;</td>
                <?php
                if ($outbound_report)
                {
                    ?>
                    <td class="align_right padding-r10"><span rel="helptip" class="helptip" id="ht-100002"><?php echo __('Interval second', true); ?></span> <span class="tooltip" id="ht-100002-tooltip">Duration interval in seconds</span></td>
                    <td><input type="text" id="query-interval_from"
                               class="in-digits input in-text" style="width: 88px;" value=""
                               name="query[interval_from]">
                        &mdash;
                        <input type="text" id="query-interval_to" class="in-digits input in-text"
                               style="width: 87px;" value="" name="query[interval_to]"><?php echo $this->element('search_report/ss_clear_input_select'); ?></td>
                    <td class="in-out_bound">&nbsp;</td>
                <?php } ?>
            </tr>
            <tr>

                <?php echo $this->element('search_report/search_orig_country') ?>
                <td class="in-out_bound">&nbsp;</td>
                <?php
                if ($outbound_report)
                {
                    ?>
                    <?php echo $this->element('search_report/search_term_country') ?>
                    <td class="in-out_bound">&nbsp;</td>
                <?php } ?>

            <tr>
                <?php echo $this->element('search_report/search_orig_code_name'); ?> <td class="in-out_bound">&nbsp;</td>
                <?php
                if ($outbound_report)
                {
                    ?>
                    <?php echo $this->element('search_report/search_term_code_name'); ?> <td class="in-out_bound">&nbsp;</td>
                <?php } ?>
            </tr>
            <tr>
                <?php echo $this->element('search_report/search_orig_code'); ?><td class="in-out_bound">&nbsp;</td>
                <?php
                if ($outbound_report)
                {
                    ?>
                    <?php echo $this->element('search_report/search_term_code'); ?> <td class="in-out_bound">&nbsp;</td>
                <?php } ?>
            </tr>

            <tr>
                <td class="align_right padding-r10"><?php echo __('Response to ingress', true); ?></td>
                <td><select id="query-res_status_ingress" style="width: 168px;"
                            onchange="$('#query-disconnect_cause_ingress').val($('#query-res_status_ingress').val());"
                            name="query[res_status_ingress]" class="input in-select">
                        <?php foreach ($appCommon->get_response() as $response_key => $response): ?>
                            <option value="<?php echo $response_key; ?>"><?php echo $response; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <input type="text" id="query-disconnect_cause_ingress"
                           style="width: 35px;" value="" name="query[disconnect_cause_ingress]"
                           class="input in-text"><?php echo $this->element('search_report/ss_clear_input_select'); ?></td>
                <td class="in-out_bound">&nbsp;</td>
                <?php
                if ($outbound_report)
                {
                    ?>
                    <td class="align_right padding-r10"><?php echo __('Response from egress', true); ?></td>
                    <td><select id="query-res_status" style="width: 168px;"
                                onchange="$('#query-disconnect_cause').val($('#query-res_status').val());"
                                name="query[res_status]" class="input in-select">
                            <?php foreach ($appCommon->get_response() as $response_key => $response): ?>
                                <option value="<?php echo $response_key; ?>"><?php echo $response; ?></option>
                            <?php endforeach; ?>
                        </select>
                        <input type="text" id="query-disconnect_cause"
                               style="width: 35px;" value="" name="query[disconnect_cause]"
                               class="input in-text"><?php echo $this->element('search_report/ss_clear_input_select'); ?></td>
                    <td class="in-out_bound">&nbsp;</td>
                <?php } ?>

            </tr>
            <tr>
                <td class="align_right padding-r10"><?php echo __('Duration', true); ?></td>
                <td><select id="query-duration" name="query[duration]"
                            class="input in-select">
                        <option value="" selected="selected">all</option>
                        <option value="nonzero">non-zero</option>
                        <option value="zero">zero</option>
                    </select><?php echo $this->element('search_report/ss_clear_input_select'); ?></td><td class="in-out_bound">&nbsp;</td>
                <?php
                if ($outbound_report)
                {
                    ?>
                    <td class="align_right padding-r10"><?php echo __('Cost', true); ?></td>
                    <td><select id="query-cost" name="query[cost]"
                                class="input in-select">
                            <option value="">all</option>
                            <option value="nonzero">non-zero</option>
                            <option value="zero">zero</option>
                        </select><?php echo $this->element('search_report/ss_clear_input_select'); ?></td>
                    <td class="in-out_bound">&nbsp;</td>
                <?php } ?>
            </tr>
            <tr>

                <td class="align_right padding-r10"><?php echo __('Release Cause', true); ?></td>
                <td><?php
                    $type = $appCdr->show_release_cause();
                    echo $form->input('cdr_release_cause', array('options' => $type, 'name' => 'cdr_release_cause', 'label' => false, 'div' => false, 'type' => 'select'));
                    ?><?php echo $this->element('search_report/ss_clear_input_select'); ?></td>
                <td class="in-out_bound">&nbsp;</td>
                <?php
                if ($outbound_report)
                {
                    ?>
                    <td class="align_right padding-r10"><?php if (count($server) > 1): ?><?php echo __('Class4-server', true); ?><?php endif; ?></td>
                    <td><?php if (count($server) > 1): ?>
                            <?php echo $form->input('server_ip', array('options' => $server, 'empty' => '', 'label' => false, 'div' => false, 'type' => 'select')); ?>
                        <?php endif; ?>
                    </td>
                    <td class="in-out_bound">&nbsp;</td>
                <?php } ?>

                <!--
                                    <td class="label"><span rel="helptip" class="helptip" id="ht-100001">TERM <?php __('codedecks') ?></span>
                                    <span class="tooltip" id="ht-100001-tooltip"> <b>Use pre-assigned</b>
                                    &mdash; means usage of code decks assigned to each pulled client or
                                    rate table. <br>
                                    <br>
                                    If you will <b>specify</b> a code deck, all code names will be
                                    rewritten using names from selected code deck, so all data will be
                                    unified by code names. </span>:</td>
                                    
                                    
                                    
                                    <td class="value">
                    <?php echo $form->input('code_deck', array('options' => $code_deck, 'empty' => '', 'label' => false, 'div' => false, 'type' => 'select')); ?>
                </td>
                    -->

            </tr>
            <tr>
                <td class="align_right padding-r10"><?php echo __('ani', true); ?> </td>
                <td class="">

                    <input type="text" id="query-src_number" value="" name="query[src_number]" class="input">
                    <?php echo $this->element('search_report/ss_clear_input_select'); ?>
                </td>
                <td class="in-out_bound">&nbsp;</td>
                <?php
                if ($outbound_report)
                {
                    ?>
                    <td class="align_right padding-r10"><?php echo __('ani', true); ?></td>
                    <td class="value"><input type="text" id="query-term_src_number" value=""
                                             name="query[term_src_number]" class="input">

                        <?php echo $this->element('search_report/ss_clear_input_select'); ?></td><td class="in-out_bound">&nbsp;</td>
                <?php } ?>
                <td><?php __('currency') ?></td>
                <td id="client_cell">
                    <select id="currency" name="currency">
                        <option></option>
                        <?php foreach ($currency as $cur): ?>
                            <option value="<?php echo $cur[0]['currency_id']; ?>" <?php if (isset($_GET['currency']) && $_GET['currency'] == $cur[0]['currency_id']) echo 'selected' ?>><?php echo $cur[0]['code']; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <?php echo $this->element('search_report/ss_clear_input_select'); ?></td>


            </tr>

            <tr>
                <td class="align_right padding-r10"><?php echo __('dnis', true); ?> </td>
                <td>
                    <select class="width120" name="query[orig_dnis_match]">
                        <option value="0"><?php __('With Prefix'); ?></option>
                        <option value="1" <?php if ($appCommon->_get("query.orig_dnis_match")): ?>selected<?php endif; ?>><?php __('Without Prefix'); ?></option>
                    </select>
                    <input type="text" id="query-dst_number" value=""
                           name="query[dst_number]" class="input"><?php echo $this->element('search_report/ss_clear_input_select'); ?></td>
                <td class="in-out_bound">&nbsp;</td>
                <?php
                if ($outbound_report)
                {
                    ?>
                    <td class="align_right padding-r10"><?php echo __('dnis', true); ?></td>
                    <td class="value">
                        <select class="width120" name="query[term_dnis_match]">
                            <option value="0"><?php __('With Prefix'); ?></option>
                            <option value="1" <?php if ($appCommon->_get("query.orig_ani_match")): ?>selected<?php endif; ?>><?php __('Without Prefix'); ?></option>
                        </select>
                        <input type="text" id="query-term_dst_number" value=""
                                             name="query[term_dst_number]" class="input">
                        <?php echo $this->element('search_report/ss_clear_input_select'); ?></td><td class="in-out_bound">&nbsp;</td>
                <?php } ?>
                <td class="align_right padding-r10"><?php echo __('type') ?></td>
                <td><?php
                    $type = array('' => __('all', true), 'orig' => __('origination', true), 'term' => __('termination', true));
                    echo $form->input('report_type', array('options' => $type, 'label' => false, 'div' => false, 'type' => 'select'));
                    ?></td>
            </tr>
            <tr>
                <td class="align_right padding-r10"><?php echo __('Orig Call ID', true); ?></td>
                <td class="value"><input type="text" id="query-orig_call_id"
                                         name="query[orig_call_id]" class="input">
                    <?php echo $this->element('search_report/ss_clear_input_select'); ?></td><td class="in-out_bound">&nbsp;</td>
                <?php
                if ($outbound_report)
                {
                    ?>
                    <td class="align_right padding-r10"><?php echo __('Term Call ID', true); ?> </td>
                    <td class="value"><input type="text" id="query-term_call_id" value=""
                                             name="query[term_call_id]" class="input"><?php echo $this->element('search_report/ss_clear_input_select'); ?></td>
                    <td class="in-out_bound">&nbsp;</td>
                    <?php
                }
                ?>
                <td class="align_right padding-r10"><?php echo __('Show', true); ?> </td>
                <td>
                    <?php
                    $option = array('0' => __('Show All', true), '1' => __('Authorized IPs only', true));
                    echo $form->input('is_hide_unauthorized_ip', array('options' => $option, 'label' => false, 'div' => false, 'type' => 'select','value'=>$is_hide_unauthorized_ip));
                    ?>
                </td>

            </tr>
            <!--<tr>
                                  <td class="label"><?php echo __('Release Cause', true); ?>:</td>
                                  <td class="value">
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
                    <td class="value"><label for="query-output_subgroups"><span
                                id="ht-100146"><?php echo __('Invalid Ingress IP', true); ?></span></label></td>
                    <td class="in-out_bound">&nbsp;</td>
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
                    <td class="value"><label for="query-output_subtotals"><span
                                id="ht-100147"><?php echo __('No Product Found', true); ?></span></label></td>
                    <td class="in-out_bound">&nbsp;</td>
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
                    <td class="value"><label for="query-output_subtotals"><span
                                id="ht-100147"><?php echo __('No Code Found', true); ?></span></label></td>
                </tr>

            <?php } ?>



            </tbody>
        </table>
    </div>
</fieldset>

<?php echo $form->end(); ?>
<a href="#MyModalSendMail" data-toggle="modal" class="MyModalSendMail"></a>
<div id="MyModalSendMail" class="modal hide">
    <div class="modal-header">
        <button data-dismiss="modal" class="close" type="button">&times;</button>
        <h3><?php __('CDR Email When Done'); ?></h3>
    </div>
    <div class="modal-body">
        <br />
        <div class="widget-body ">
            <?php __('Email Address'); ?>:
            <input type="text" class="input in-text in-input validate[required,custom[email]] email_input" />
        </div>
    </div>
    <div class="modal-footer">
        <input type="button" class="btn btn-primary submit_btn" value="<?php __('Submit'); ?>">
        <a href="javascript:void(0)" data-dismiss="modal" class="btn btn-default"><?php __('Close'); ?></a>
    </div>
</div>


<link rel="stylesheet" type="text/css" href="<?php echo $this->webroot ?>easyui/themes/default/easyui.css">
<script type="text/javascript" src="<?php echo $this->webroot ?>easyui/jquery.easyui.min.js"></script>
<script src="<?php echo $this->webroot ?>js/jquery.jgrowl.js" type="text/javascript"></script>
<scirpt type="text/javascript" src="<?php echo $this->webroot ?>js/jquery.center.js"></scirpt>

<script type="text/javascript">
    function getTechPrefix(obj) {
        $("#CdrRoutePrefix").empty();
        $("#CdrRoutePrefix").append("<option value=''>All</option>");
        if ($(obj).val() != '0') {
            $.post("<?php echo $this->webroot ?>cdrreports_db/getTechPerfix", {ingId: $(obj).val()},
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

        $('#report_form').find(':submit').click(function() {
            if ($('#query-output').val() == 'email') {
                $("a.MyModalSendMail").click();
                return false;
            }
        });
        $('#MyModalSendMail').find('.submit_btn').click(function(){
            var mail_input = $('#MyModalSendMail').find('.email_input');
            if (mail_input.validationEngine('validate') == false)
            {
                $("#real_send_mail_address").val(mail_input.val());
                $("#report_form").submit();
            }
        });
    });
</script>