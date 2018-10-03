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
    <div id="advance_panel" class="widget widget-heading-simple widget-body-gray" style="display: none;">
        <table class="form" style="width:100%">
            <tbody>
            <tr> <?php echo $this->element('search_report/orig_carrier_select'); ?>
                <td class="in-out_bound">&nbsp;</td>
                <td  valign="top" rowspan="9" colspan="2"
                     style="padding-left: 10px;width:25%;">

                    <?php
                    if (!($this->params['controller'] == 'cdrreports_db' && ($this->params['action'] == 'sip_packet' || $this->params['action'] == 'summary_reports')))
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
            </tr>
            <tr>

                <?php echo $this->element('search_report/search_orig_country') ?>
                <td class="in-out_bound">&nbsp;</td>

            <tr>
                <?php echo $this->element('search_report/search_orig_code_name'); ?> <td class="in-out_bound">&nbsp;</td>
            </tr>
            <tr>
                <?php echo $this->element('search_report/search_orig_code'); ?><td class="in-out_bound">&nbsp;</td>
            </tr>

            <tr>
                <td class="align_right padding-r10"><?php echo __('Response to ingress', true); ?></td>
                <td><select id="query-res_status_ingress" style="width: 168px;"
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
                           class="input in-text"><?php echo $this->element('search_report/ss_clear_input_select'); ?></td>
                <td class="in-out_bound">&nbsp;</td>

            </tr>
            <tr>
                <td class="align_right padding-r10"><?php echo __('Duration', true); ?></td>
                <td><select id="query-duration" name="query[duration]"
                            class="input in-select">
                        <option value="" selected="selected">all</option>
                        <option value="nonzero">non-zero</option>
                        <option value="zero">zero</option>
                    </select><?php echo $this->element('search_report/ss_clear_input_select'); ?></td><td class="in-out_bound">&nbsp;</td>
            </tr>
            <tr>

                <td class="align_right padding-r10"><?php echo __('Release Cause', true); ?></td>
                <td><?php
                    $type = $appCdr->show_release_cause();
                    echo $form->input('cdr_release_cause', array('options' => $type, 'name' => 'cdr_release_cause', 'label' => false, 'div' => false, 'type' => 'select'));
                    ?><?php echo $this->element('search_report/ss_clear_input_select'); ?></td>
                <td class="in-out_bound">&nbsp;</td>
            </tr>
            <tr>
                <td class="align_right padding-r10"><?php echo __('ani', true); ?> </td>
                <td class="value"><input type="text" id="query-src_number" value=""
                                         name="query[src_number]" class="input"><?php echo $this->element('search_report/ss_clear_input_select'); ?></td>
                <td class="in-out_bound">&nbsp;</td>
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
                <td><input type="text" id="query-dst_number" value=""
                           name="query[dst_number]" class="input"><?php echo $this->element('search_report/ss_clear_input_select'); ?></td>
                <td class="in-out_bound">&nbsp;</td>
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
                <td class="align_right padding-r10"><?php echo __('Show', true); ?> </td>
                <td>
                    <?php
                    $option = array('0' => __('Show All', true), '1' => __('Authorized IPs only', true));
                    echo $form->input('is_hide_unauthorized_ip', array('options' => $option, 'label' => false, 'div' => false, 'type' => 'select','value'=>$is_hide_unauthorized_ip));
                    ?>
                </td>

            </tr>
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