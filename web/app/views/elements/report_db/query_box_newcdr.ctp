<fieldset class="query-box" style=" clear:both;overflow:hidden;margin-top:10px;">
    <h4 style="display: inline-block;" class="heading glyphicons search"><i></i> <?php __('search') ?></h4>
    <div class="clearfix"></div>
    <?php
    $url = "/" . $this->params['url']['url'];

    echo $form->create('Cdr', array('type' => 'get', 'url' => $url, 'id' => 'report_form',
        'onsubmit' => "if ($('#query-output').val() == 'web') loading();"));
    ?>

    <table class="form" style="width: 100%">
        <tbody>
        <?php
        echo $this->element('report/form_period', array(
            'group_time' => false,
            'gettype' => '  <select id="query-output" name="query[output]" class="input in-select">
                                            <option value="web">Web</option>
                                            <option value="csv">CSV</option>
                                        </select>'
        ));
        ?>
        </tbody>
    </table>
    <div id="advance_panel" class="widget widget-heading-simple widget-body-gray">
        <table class="form" style="width:100%">
            <tbody>

            <?php if($fields == true): ?>
            <tr>
                <td colspan="6"></td>
                <td  valign="top" rowspan="9" colspan="2"
                     style="padding-left: 10px;width:25%;">

                    <?php
                    if (!($this->params['controller'] == 'cdrreports_db' && $this->params['action'] == 'sip_packet'))
                    {
                        ?>

                        <div align="left"><?php echo __('Show Fields', true); ?>:</div>
<!--                        <select name="query[fields][]" id="query-fields" style="width:100%; height: 250px;" multiple="multiple">-->
<!--                            --><?php //foreach ($cdr_field as $key => $item) { ?>
<!--                                <option value="--><?php //echo $key; ?><!--">--><?php //echo $item; ?><!--</option>-->
<!--                            --><?php //} ?>
<!--                        </select>-->

                        <?php
                        echo $form->select('Cdr.field', $cdr_field, $report_fields, array('id' => 'query-fields', 'style' => 'width:100%; height: 250px;', 'name' => 'query[fields]', 'type' => 'select', 'multiple' => true, 'selected' => $report_fields), false);
                        ?>
                        <button type="button" class="btn btn-primary" style="display:block; width: 100%;" onclick="saveFields('<?php echo $this->webroot; ?>', 'summary_reports')">Save fields as default</button>
                        <?php
                    }
                    ?>
<!--                    <a class="btn btn-primary" style="width: 100%;" onclick="saveFields();">Save fields as default</a>-->
                </td>
            </tr>
            <?php endif; ?>

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
                <?php echo $this->element('search_report/search_orig_country') ?>
                <td class="in-out_bound" colspan="3">&nbsp</td>
            </tr>

            <tr>
                <td class="align_right padding-r10">DNIS</td>
                <td>
                    <select class="width120" id="query-orig_dnis_match" name="query[orig_dnis_match]">
                        <option></option>
                        <option value="0"><?php __('With Prefix'); ?></option>
                        <option value="1" <?php if ($appCommon->_get("query.orig_dnis_match")): ?>selected<?php endif; ?>><?php __('Without Prefix'); ?></option>
                    </select>
                    <input type="text" name="orig_src_number" id = "orig_src_number" value="<?php if(isset($getData['orig_src_number']) && !empty($getData['orig_src_number'])) echo $getData['orig_src_number'];?>">
                </td>
                <td class="in-out_bound">&nbsp;</td>
                <td class="align_right padding-r10">ANI</td>
                <td>
                    <input type="text" name="query[src_number]" id="query-src_number" value="<?php if(isset($getData['src_number']) && !empty($getData['src_number'])) echo $getData['src_number'];?>">
                    <a href="javascript:void(0)" onclick="ss_clear_input_select(this);">
                        <i class="icon-remove"></i>
                    </a>
                </td>
            </tr>

            <tr>
                <td class="align_right padding-r10">Is Final Call</td>
                <td>
                    <!--                                <input type="checkbox" id="is_final_call" name="is_final_call" --><?php //if(isset($getData['is_final_call']) && !empty($getData['is_final_call']) && $getData['is_final_call'] == 'on') echo 'checked';?><!-- >-->
                    <select name="is_final_call" id="is_final_call">
                        <option value="0">All</option>
                        <option value="1" <?php if(isset($getData['is_final_call']) && $getData['is_final_call'] == 1) echo 'selected'; ?> >Final Call</option>
                    </select>
                </td>
                <td class="in-out_bound">&nbsp;</td>
                <td class="align_right padding-r10">Duration</td>
                <td>
                    <select name="is_zero_call" id="is_zero_call">
                        <option value="0">All</option>
                        <option value="1" <?php if(isset($getData['is_zero_call']) && $getData['is_zero_call'] == 1) echo 'selected'; ?>>Non zero</option>
                        <option value="2" <?php if(isset($getData['is_zero_call']) && $getData['is_zero_call'] == 2) echo 'selected'; ?> >Zero</option>
                    </select>
                    <!--                                <input type="checkbox" name="is_zero_call" --><?php //if(isset($getData['is_zero_call']) && !empty($getData['is_zero_call']) && $getData['is_zero_call'] == 'on') echo 'checked';?><!-- >-->
                </td>
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
            </tbody>
        </table>
    </div>
    <?php echo $form->end(); ?>
</fieldset>
<script>
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
</script>
