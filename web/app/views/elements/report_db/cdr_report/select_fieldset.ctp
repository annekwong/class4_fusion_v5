<?php $is_summary = (isset($is_summary) && $is_summary);
if ($_SESSION['role_menu']['Payment_Invoice']['view_cost_and_rate'] == 1) {
    $cr_flag = true;
} else {
    $cr_flag = false;
}
?>
<fieldset class="query-box" style="clear:both;overflow:hidden;margin-top:10px;">
    <h4 class="heading glyphicons search" style="display: inline-block;"><i></i> Search</h4>
    <div class="pull-right" title="Advance">
        <a id="advance_btn" class="btn" href="javascript:void(0)">
            <i class="icon-long-arrow-down"></i>
        </a>
    </div>
    <div class="clearfix"></div>
    <?php echo $this->element('search_report/search_js'); ?> <?php echo $this->element('search_report/search_hide_input'); ?>
    <table class="form">
        <?php echo $this->element('report_db/form_period', array('group_time' => true, 'gettype' => '<select style="width:120px;" name="show_type">
            <option value="0">Web</option>
            <option value="1">CSV</option>
            <option value="2">XLS</option>
        </select>')) ?>
    </table>
    <div id="advance_panel" class="widget widget-heading-simple widget-body-gray">
        <table class="form" style="width:100%">
            <tr class="period-block" style="height:20px; line-height:20px;">
                <td colspan="2" style="text-align:center; font-size:14px;"><b><?php echo __('Inbound', true); ?></b></td>
                <td class="in-out_bound">&nbsp;</td>
                <?php if($outbound_report){ ?>
                    <td colspan="2" style="text-align:center;font-size:14px;"><b><?php echo __('Outbound', true); ?></b></td>
                    <td class="in-out_bound">&nbsp;</td>
                    <td colspan="2">&nbsp;</td>
                <?php } ?>
            </tr>
            <tr>
                <td class="align_right padding-r10"><?php __('Carriers')?></td>
                <td class="value">
                    <select name="ingress_client_id" class="client_options_ingress">
                        <option></option>
                        <?php foreach ($ingress_clients as $ingress_client_id => $ingress_client_name): ?>
                            <option value="<?php echo $ingress_client_id ?>" <?php echo $common->set_get_select('ingress_client_id', $ingress_client_id) ?>><?php echo $ingress_client_name ?></option>
                        <?php endforeach; ?>
                    </select>
                    <?php echo $this->element('search_report/ss_clear_input_select'); ?>
                </td>
                <td>&nbsp;</td>
                <?php if($outbound_report){ ?>
                    <td class="align_right padding-r10"><?php __('Carriers')?></td>
                    <td class="value">
                        <select class="client_options_egress" name="egress_client_id">
                            <option></option>
                            <?php foreach ($egress_clients as $egress_client_id => $egress_client_name): ?>
                                <option value="<?php echo $egress_client_id ?>" <?php echo $common->set_get_select('egress_client_id', $egress_client_id) ?>><?php echo $egress_client_name ?></option>
                            <?php endforeach; ?>
                        </select>
                        <?php echo $this->element('search_report/ss_clear_input_select'); ?>
                    </td>
                    <td>&nbsp;</td>

                    <td></td>
                    <td class="value">
                    </td>
                <?php } ?>
                <?php if ($is_summary): ?>
                    <td  valign="top" rowspan="9" colspan="2" style="padding-left: 10px;width:25%;">

                        <div align="left"><?php echo __('Show Fields', true); ?>:</div>
                        <?php
                        echo $form->select('Cdr.field', $select_fields, $select_show_fields, array('id' => 'query-fields', 'style' => 'width:100%; height: 250px;', 'name' => 'query[fields]', 'type' => 'select', 'multiple' => true), false);
                        ?>
                    </td>
                <?php endif; ?>
            </tr>
            <tr>
                <td class="align_right padding-r10"><?php __('Ingress Trunk')?></td>
                <td class="value">
                    <select class="trunk_options_ingress" name="ingress_id" onchange="getTechPrefix(this);">
                        <option></option>
                        <?php
                        foreach ($ingress_trunks as $ing)
                        {
                            ?>
                            <option value="<?php echo $ing[0]['resource_id']; ?>" <?php
                            if (isset($_GET['ingress_id']) && !strcmp($_GET['ingress_id'], $ing[0]['resource_id']))
                            {
                                echo "selected='selected'";
                            }
                            ?>><?php echo $ing[0]['alias']; ?></option>
                        <?php } ?>
                    </select>
                    <?php echo $this->element('search_report/ss_clear_input_select'); ?>
                </td>
                <td>&nbsp;</td>
                <?php if($outbound_report){ ?>
                    <td class="align_right padding-r10"><?php __('Egress Trunk')?></td>
                    <td class="value">
                        <select class="trunk_options_egress" name="egress_id">
                            <option></option>
                            <?php
                            foreach ($egress_trunks as $eg)
                            {
                                ?>
                                <option value="<?php echo $eg[0]['resource_id']; ?>" <?php
                                if (isset($_GET['egress_id']) && !strcmp($_GET['egress_id'], $eg[0]['resource_id']))
                                {
                                    echo "selected='selected'";
                                }
                                ?>><?php echo $eg[0]['alias']; ?></option>
                            <?php } ?>
                        </select>
                        <?php echo $this->element('search_report/ss_clear_input_select'); ?>
                    </td>
                    <td rowspan="4">&nbsp;</td>
                    <td rowspan="4"><!--Group By:--></td>
                <?php } ?>
            </tr>


            <tr>

                <td class="align_right padding-r10"><?php __('Tech Prefix')?></td>
                <td class="value">
                    <select name ="route_prefix" id="CdrRoutePrefix">
                        <option value="all">
                            <?php __('All')?>
                        </option>
                        <?php
                        if (!empty($ingress_options['prefixes']))
                        {
                            foreach ($ingress_options['prefixes'] as $te)
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
                    <a onclick="clear_prefix(this);" href="javascript:void(0)">
                        <i class="icon-remove"></i>
                    </a>
                </td>
                <td class="in-out_bound">&nbsp;</td>


            </tr>
            <tr>
                <td class="align_right padding-r10"><?php __('Country')?></td>
                <td class="value">
                    <input type="text" class="width220" name="orig_country" value="<?php echo $common->set_get_value('orig_country') ?>" />
                    <?php echo $this->element('search_report/ss_clear_input_select'); ?>
                </td>
                <td>&nbsp;</td>
                <?php if($outbound_report){ ?>
                    <td class="align_right padding-r10"><?php __('Country')?></td>
                    <td class="value">
                        <input type="text"  class="width220" name="term_country" value="<?php echo $common->set_get_value('term_country') ?>" />
                        <?php echo $this->element('search_report/ss_clear_input_select'); ?>
                    </td>
                <?php } ?>
            </tr>
            <tr>
                <td class="align_right padding-r10"><?php __('Code Name')?></td>
                <td class="value">
                    <input type="text" class="width220" name="orig_code_name" value="<?php echo $common->set_get_value('orig_code_name') ?>" />
                    <?php echo $this->element('search_report/ss_clear_input_select'); ?>
                </td>
                <td>&nbsp;</td>
                <?php if($outbound_report){ ?>
                    <td class="align_right padding-r10"><?php __('Code Name')?></td>
                    <td class="value">
                        <input type="text" class="width220" name="term_code_name" value="<?php echo $common->set_get_value('term_code_name') ?>" />
                        <?php echo $this->element('search_report/ss_clear_input_select'); ?>
                    </td>
                <?php } ?>
            </tr>
            <?php if (Configure::read('statistics.have_code_rate')): ?>
                <tr>
                    <td class="align_right padding-r10"><?php __('Code')?></td>
                    <td class="value">
                        <input type="text" class="width220" name="orig_code" value="<?php echo $common->set_get_value('orig_code') ?>" />
                        <?php echo $this->element('search_report/ss_clear_input_select'); ?>
                    </td>
                    <td>&nbsp;</td>
                    <?php if($outbound_report){ ?>
                        <td class="align_right padding-r10"><?php __('Code')?></td>
                        <td class="value">
                            <input type="text" class="width220" name="term_code" value="<?php echo $common->set_get_value('term_code') ?>" />
                            <?php echo $this->element('search_report/ss_clear_input_select'); ?>
                        </td>
                        <td class="in-out_bound">&nbsp;</td>
                    <?php  } ?>
                </tr>
            <?php endif; ?>
            <!--<tr>
                        <td class="align_right padding-r10"><?php /*__('Show Inter/Intra Cost')*/?></td>
                        <td class="value">
                            <input type="checkbox" class="margin-bottom10" name="show_inter_intra" <?php /*if (isset($_GET['show_inter_intra'])) echo 'checked="checked"'; */?> />
                        </td>
                        <td>&nbsp;</td>
                        <td></td>
                        <td class="value"></td>
                        <td>&nbsp;</td>
                    </tr>-->
            <?php if ($cr_flag) { ?>
                <!--tr>
                <td class="align_right padding-r10"><?php __('Rate Type')?></td>
                <td class="value">
                    <select name="orig_rate_type">
                        <option value="0" <?php echo $common->set_get_select('orig_rate_type', 0); ?>><?php __('All')?></option>
                        <option value="1" <?php echo $common->set_get_select('orig_rate_type', 1); ?>><?php __('A-Z')?></option>
                        <option value="2" <?php echo $common->set_get_select('orig_rate_type', 2); ?>><?php __('US')?></option>
                        <option value="3" <?php echo $common->set_get_select('orig_rate_type', 3); ?>><?php __('OCN-LATA')?></option>
                    </select>
                    <?php echo $this->element('search_report/ss_clear_input_select'); ?>
                </td>
                <td>&nbsp;</td>
                <?php if($outbound_report){ ?>
                    <td class="align_right padding-r10"><?php __('Rate Type')?></td>
                    <td class="value">
                        <select name="term_rate_type">
                            <option value="0" <?php echo $common->set_get_select('term_rate_type', 0); ?>><?php __('All')?></option>
                            <option value="1" <?php echo $common->set_get_select('term_rate_type', 1); ?>><?php __('A-Z')?></option>
                            <option value="2" <?php echo $common->set_get_select('term_rate_type', 2); ?>><?php __('US')?></option>
                            <option value="3" <?php echo $common->set_get_select('term_rate_type', 3); ?>><?php __('OCN-LATA')?></option>
                        </select>
                        <?php echo $this->element('search_report/ss_clear_input_select'); ?>
                    </td>
                    <td>&nbsp;</td>
                <?php } ?>
            </tr-->
            <?php } ?>
            <tr>
                <?php if ($cr_flag) { ?>
                    <td class="align_right padding-r10"><?php __('Rate Table')?></td>
                    <td class="value">
                        <select id="ingress_rate_table" name="ingress_rate_table">
                            <option value="all">
                                <?php __('All')?>
                            </option>
                            <?php
                            if (!empty($ingress_options['rate_tables']))
                            {
                                foreach ($ingress_options['rate_tables'] as $te)
                                {
                                    if (isset($_GET['ingress_rate_table']) && $_GET['ingress_rate_table'] == $te[0]['rate_table_id'])
                                    {
                                        echo "<option selected value='" . $te[0]['rate_table_id'] . "'>" . $te[0]['rate_table_name'] . "</option>";
                                    }
                                    else
                                    {
                                        echo "<option value='" . $te[0]['rate_table_id'] . "'>" . $te[0]['rate_table_name'] . "</option>";
                                    }
                                }
                            }
                            else
                            {
                                foreach ($rate_tables as $rate_table)
                                {
                                    $checked = '';
                                    if (isset($_GET['ingress_rate_table']) && $_GET['ingress_rate_table'] == $rate_table[0]['id'])
                                        $checked = 'selected';
                                    echo "<option value='" . $rate_table[0]['id'] . "' $checked>" . $rate_table[0]['name'] . "</option>";
                                }
                            }
                            ?>
                        </select>
                        <?php echo $this->element('search_report/ss_clear_input_select'); ?>
                    </td>
                <?php } else { ?>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                <?php } ?>
                <td>&nbsp;</td>
                <?php if ($is_summary): ?>

                    <?php if($outbound_report){ ?>
                        <td class="align_right padding-r10"><?php __('Switch Server')?></td>
                        <td class="value">
                            <?php echo $form->input('server_ip', array('options' => $servers, 'style' => 'width:220px;', 'empty' => '', 'label' => false, 'div' => false, 'type' => 'select')); ?>
                            <?php echo $this->element('search_report/ss_clear_input_select'); ?>
                        </td>
                        <td>&nbsp;</td>

                        <td></td>
                        <td class="value">
                        </td>
                    <?php } ?>
                <?php endif; ?>
            </tr>

            <tr>
                <td class="align_right padding-r10"><?php __('Routing Plan')?></td>
                <td class="value">
                    <select id="ingress_routing_plan" name="ingress_routing_plan">
                        <option value="all">
                            <?php __('All')?>
                        </option>
                        <?php
                        if (!empty($ingress_options['routing_plans']))
                        {


                            foreach ($ingress_options['routing_plans'] as $te)
                            {
                                if (isset($_GET['ingress_routing_plan']) && $_GET['ingress_routing_plan'] == $te[0]['route_strategy_id'])
                                {
                                    echo "<option selected value='" . $te[0]['route_strategy_id'] . "'>" . $te[0]['route_strategy_name'] . "</option>";
                                }
                                else
                                {
                                    echo "<option value='" . $te[0]['route_strategy_id'] . "'>" . $te[0]['route_strategy_name'] . "</option>";
                                }
                            }
                        }
                        else
                        {
                            foreach ($routing_plans as $routing_plan)
                            {
                                $checked = '';
                                if (isset($_GET['ingress_routing_plan']) && $_GET['ingress_routing_plan'] == $routing_plan[0]['id'])
                                    $checked = 'selected';
                                echo "<option value='" . $routing_plan[0]['id'] . "' $checked>" . $routing_plan[0]['name'] . "</option>";
                            }
                        }
                        ?>
                    </select>
                    <?php echo $this->element('search_report/ss_clear_input_select'); ?>
                </td>
                <td>&nbsp;</td>
                <td></td>
                <td class="value">
                </td>
                <td>&nbsp;</td>

                <td></td>
                <td class="value">
                </td>
            </tr>
            <?php if ($is_summary): ?>
                <?php if ($cr_flag) { ?>
                    <tr>
                        <td class="align_right padding-r10"><?php __('Rate display as')?></td>
                        <td class="value">
                            <select id="ingress_routing_plan" name="rate_display_as">
                                <option value="0" <?php echo $common->set_get_select('rate_display_as', 0); ?>><?php __('Average')?></option>
                                <option value="1" <?php echo $common->set_get_select('rate_display_as', 1); ?>><?php __('Actual')?></option>
                            </select>
                            <?php echo $this->element('search_report/ss_clear_input_select'); ?>
                        </td>
                        <td>&nbsp;</td>
                        <td></td>
                        <td class="value">
                        </td>
                        <td>&nbsp;</td>

                        <td></td>
                        <td class="value">
                        </td>
                    </tr>
                <?php } ?>
            <?php endif; ?>
        </table>
        <?php if($report_group){ ?>
            <?php echo $this->element('report_db/cdr_report/group_by'); ?>
        <?php } ?>
    </div>
</fieldset>

<script type="text/javascript">

    var $routeprefix = $("#CdrRoutePrefix");
    var $ingress_rate_table = $('#ingress_rate_table');
    var $ingress_routing_plan = $('#ingress_routing_plan');

    $(function() {
        $('a#advance_btn').click(function(){
            $('#show_hide_columns').attr('value','1');
        });

        $('.client_options_ingress').live('change', function() {
            var $this = $(this);
            value = $this.val();
            var data = jQuery.ajaxData({'async': false, 'url': '<?php echo $this->webroot ?>trunks/ajax_options?filter_id=' + value + '&type=ingress&trunk_type2=0'});
            data = eval(data);
            var temp1 = $('.trunk_options_ingress').val();

            $('.trunk_options_ingress').html('');
            jQuery('<option>').appendTo($('.trunk_options_ingress'));
            for (var i in data) {
                var temp = data[i];
                jQuery('<option>').html(temp.alias).val(temp.resource_id).appendTo($('.trunk_options_ingress'));
            }
            $('.trunk_options_ingress').val(temp1);
        });

        $('.client_options_egress').live('change', function() {
            var $this = $(this);
            value = $this.val();
            var data = jQuery.ajaxData({'async': false, 'url': '<?php echo $this->webroot ?>trunks/ajax_options?filter_id=' + value + '&type=egress&trunk_type2=0'});
            data = eval(data);
            var temp1 = $('.trunk_options_ingress').val();

            $('.trunk_options_egress').html('');
            jQuery('<option>').appendTo($('.trunk_options_egress'));
            for (var i in data) {
                var temp = data[i];
                jQuery('<option>').html(temp.alias).val(temp.resource_id).appendTo($('.trunk_options_egress'));
            }
            $('.trunk_options_egress').val(temp1);
        });


    })

    function getTechPrefix(obj) {
        var $this = $(obj);
        var val = $this.val();
        $routeprefix.empty();
        $ingress_rate_table.empty();
        $ingress_routing_plan.empty();
        $routeprefix.append("<option value='all'>All</option>");
        $ingress_rate_table.append("<option value='all'>All</option>");
        $ingress_routing_plan.append("<option value='all'>All</option>");
        if (val != '0') {

            $.post("<?php echo $this->webroot ?>cdrreports_db/getTechPerfix", {ingId: val},
                function(data) {
                    $.each(data.prefixes,
                        function(index, content) {
                            $routeprefix.append("<option value='" + content[0]['tech_prefix'] + "'>" + content[0]['tech_prefix'] + "</option>");
                        }
                    );
                    $.each(data.rate_tables,
                        function(index, content) {
                            $ingress_rate_table.append("<option value='" + content[0]['rate_table_id'] + "'>" + content[0]['rate_table_name'] + "</option>");
                        }
                    );
                    $.each(data.routing_plans,
                        function(index, content) {
                            $ingress_routing_plan.append("<option value='" + content[0]['route_strategy_id'] + "'>" + content[0]['route_strategy_name'] + "</option>");
                        }
                    );
                }, 'json');

        }

    }

    function clear_prefix(obj) {
        var $this = $(obj);
        $(obj).prev().find('option:first').attr('selected', true);
    }

</script>