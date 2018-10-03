<ul class="breadcrumb">
    <li><?php echo __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Statistics') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Location Report') ?></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Location Report') ?></h4>
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>

<div class="innerLR">

    <div class="widget widget-heading-simple widget-body-white">
        <div class="widget-body">
            <?php if ($show_nodata): ?><h1 style="font-size:14px;"><?php __('Report Period')?> <?php echo $start_date ?> — <?php echo $end_date ?></h1><?php endif; ?>
            <?php if (empty($data)): ?>
                <?php if ($show_nodata): ?><h2 class="msg center"><?php  echo __('no_data_found') ?></h2><?php endif; ?>
            <?php else: ?>
                <div id="overflow_x" style="max-height:500px;">
                    <table class="list  footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
                        <thead>
                            <tr>
                                <?php foreach ($show_fields as $field): ?>
                                    <th><?php echo $replace_fields[$field]; ?></th>
                                <?php endforeach; ?>
                                <th><?php echo __('Duration') ?></th>
                                <th><?php echo __('Call Count') ?></th>
                                <th><?php echo __('Revenue') ?></th>
                                <th><?php echo __('Cost') ?></th>
                                <th><?php echo __('Profit') ?></th>
                                <th><?php echo __('Profit(%)') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 0;
                            $arr = array();
                            foreach ($data as $item):
                                $arr['inbound_call_cost'][$i] = $item[0]['ingress_call_cost'];
                                $arr['outbound_call_cost'][$i] = $item[0]['egress_call_cost'];
                                $arr['duration'][$i] = $item[0]['duration'];
                                $arr['total_calls'][$i] = $item[0]['egress_total_calls'];
                                ?>
                                <tr>
                                    <?php foreach (array_keys($show_fields) as $key): ?>
                                        <td style="color:#6694E3;"><?php echo $item[0][$key]; ?></td>
                                    <?php endforeach; ?>
                                    <td><?php echo round($arr['duration'][$i] / 60, 2); ?></td>
                                    <td><?php echo round($arr['total_calls'][$i]); ?></td>
                                    <td><?php echo round($arr['inbound_call_cost'][$i], 5); ?></td>
                                    <td><?php echo round($arr['outbound_call_cost'][$i], 5); ?></td>
                                    <td><?php echo number_format($arr['inbound_call_cost'][$i] - $arr['outbound_call_cost'][$i], 5); ?></td>
                                    <td><?php echo number_format($arr['inbound_call_cost'][$i] == 0 ? 0 : ($arr['inbound_call_cost'][$i] - $arr['outbound_call_cost'][$i]) / $arr['inbound_call_cost'][$i] * 100, 5); ?>%</td>

                                </tr>
                                <?php
                                $i++;
                            endforeach;
                            ?>
                            <?php
                            $count_group = count($show_fields);
                            if ($count_group && count($data)):
                                ?>
                                <tr style="color:#000;">
                                    <td colspan="<?php echo $count_group; ?>"><?php echo __('Total') ?>:</td>
                                    <td><?php echo round(array_sum($arr['duration']) / 60, 2); ?></td>
                                    <td><?php echo round(array_sum($arr['total_calls'])); ?></td>
                                    <td><?php echo round(array_sum($arr['inbound_call_cost']), 5); ?></td>
                                    <td><?php echo round(array_sum($arr['outbound_call_cost']), 5); ?></td>
                                    <td><?php echo number_format(array_sum($arr['inbound_call_cost']) - array_sum($arr['outbound_call_cost']), 5); ?></td>
                                    <td><?php echo number_format(array_sum($arr['inbound_call_cost']) == 0 ? 0 : (array_sum($arr['inbound_call_cost']) - array_sum($arr['outbound_call_cost'])) / array_sum($arr['inbound_call_cost']) * 100, 5); ?>%</td>
                                </tr>
                                <?php
                            endif;
                            ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
            <?php echo $form->create('Cdr', array('type' => 'get', 'url' => "/reports/location", 'onsubmit' => "if($('select[name=show_type]').val() == 0) loading();")); ?>
            <fieldset class="query-box" style="clear:botd;overflow:hidden;margin-top:10px;">
                <h4 class="heading glyphicons search"><i></i> <?php echo __('Search') ?></h4>
                <?php echo $this->element('search_report/search_js'); ?> <?php echo $this->element('search_report/search_hide_input'); ?>
                <table class="form" style="width:100%">
                    <col width="20%">
                    <?php echo $this->element('report/form_period', array('group_time' => true, 'gettype' => '<select style="width:120px;" name="show_type">
            <option value="0">Web</option>
            <option value="1">CSV</option>
            <option value="2">XLS</option>
        </select>')) ?>
                    <tr class="period-block" style="height:20px; line-height:20px;">
                        <td colspan="2" style="text-align:center; font-size:14px;"><b><?php echo __('Inbound', true); ?></b></td>
                        <td>&nbsp;</td>
                        <td colspan="2" style="text-align:center;font-size:14px;"><b><?php echo __('Outbound', true); ?></b></td>
                        <td>&nbsp;</td>
                        <td colspan="2">&nbsp;</td>
                    </tr>
                    <tr>
                        <td><?php __('Carriers')?>:</td>
                        <td>
                            <select style="width:120px;" class="client_options_ingress"  name="ingress_client_id">
                                <option></option>
                                <?php foreach ($ingress_clients as $ingress_client): ?>
                                    <option value="<?php echo $ingress_client[0]['client_id'] ?>" <?php echo $common->set_get_select('ingress_client_id', $ingress_client[0]['client_id']) ?>><?php echo $ingress_client[0]['name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                            <?php echo $this->element('search_report/ss_clear_input_select'); ?>
                        </td>
                        <td>&nbsp;</td>
                        <td><?php __('Carriers')?>:</td>
                        <td>
                            <select style="width:120px;" class="client_options_egress" name="egress_client_id">
                                <option></option>
                                <?php foreach ($egress_clients as $egress_client): ?>
                                    <option value="<?php echo $egress_client[0]['client_id'] ?>" <?php echo $common->set_get_select('egress_client_id', $egress_client[0]['client_id']) ?>><?php echo $egress_client[0]['name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                            <?php echo $this->element('search_report/ss_clear_input_select'); ?>
                        </td>
                        <td>&nbsp;</td>

                        <td></td>
                        <td>
                        </td>
                        <!--
                        <td>Switch IP:</td>
                        <td>
                            <select style="width:120px;">
                                <option></option>
                        <?php foreach ($switch_ips as $switch_ip): ?>
                                            <option value="<?php echo $switch_ip[0]['ip'] ?>"><?php echo $switch_ip[0]['ip'] ?></option>
                        <?php endforeach; ?>
                            </select>
                        <?php echo $this->element('search_report/ss_clear_input_select'); ?>
                        </td>
                        -->
                    </tr>
                    <tr>
                        <td><?php __('Ingress Trunk')?>:</td>
                        <td>
                            <select class="trunk_options_ingress" name="ingress_id" onchange="getTechPrefix(this);">
                                <option></option> 
                                <?php
                                foreach ($ingress as $ing)
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
                        <td><?php __('Egress Trunk')?>:</td>
                        <td>
                            <select class="trunk_options_egress" name="egress_id">
                                <option value=""></option> 
                                <?php
                                foreach ($egress as $eg)
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
                    </tr>

                    <tr>

                        <td lass="label"><?php __('Tech Prefix')?></td>
                        <td>
                            <select name ="route_prefix" id="CdrRoutePrefix" style="width:120px;">
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
                            <?php echo $this->element('search_report/ss_clear_input_select'); ?>
                        </td>
                        <td>&nbsp;</td>


                    </tr>


                    <tr>
                        <td><?php __('Country')?>:</td>
                        <td>
                            <input type="text" style="width:120px;" name="orig_country" value="<?php echo $common->set_get_value('orig_country') ?>" />
                            <?php echo $this->element('search_report/ss_clear_input_select'); ?>
                        </td>
                        <td>&nbsp;</td>
                        <td><?php __('Country')?>:</td>
                        <td>
                            <input type="text"  style="width:120px;" name="term_country" value="<?php echo $common->set_get_value('term_country') ?>" />
                            <?php echo $this->element('search_report/ss_clear_input_select'); ?>
                        </td>
                    </tr>
                    <tr>
                        <td><?php __('Code Name')?>:</td>
                        <td>
                            <input type="text" style="width:120px;" name="orig_code_name" value="<?php echo $common->set_get_value('orig_code_name') ?>" />
                            <?php echo $this->element('search_report/ss_clear_input_select'); ?>
                        </td>
                        <td>&nbsp;</td>
                        <td><?php __('Code Name')?>:</td>
                        <td>
                            <input type="text" style="width:120px;" name="term_code_name" value="<?php echo $common->set_get_value('term_code_name') ?>" />
                            <?php echo $this->element('search_report/ss_clear_input_select'); ?>
                        </td>
                    </tr>
                    <tr>
                        <td><?php __('Code')?>:</td>
                        <td>
                            <input type="text" style="width:120px;" name="orig_code" value="<?php echo $common->set_get_value('orig_code') ?>" />
                            <?php echo $this->element('search_report/ss_clear_input_select'); ?>
                        </td>
                        <td>&nbsp;</td>
                        <td><?php __('Code')?>:</td>
                        <td>
                            <input type="text" style="width:120px;" name="term_code" value="<?php echo $common->set_get_value('term_code') ?>" />
                            <?php echo $this->element('search_report/ss_clear_input_select'); ?>
                        </td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td><?php __('Rate Type')?></td>
                        <td>
                            <select name="orig_rate_type" style="width:120px;">
                                <option value="0" <?php echo $common->set_get_select('orig_rate_type', 0); ?>><?php __('All')?></option>
                                <option value="1" <?php echo $common->set_get_select('orig_rate_type', 1); ?>><?php __('A-Z')?></option>
                                <option value="2" <?php echo $common->set_get_select('orig_rate_type', 2); ?>><?php __('US')?></option>
                                <option value="3" <?php echo $common->set_get_select('orig_rate_type', 3); ?>><?php __('OCN-LATA')?></option>
                            </select>
                            <?php echo $this->element('search_report/ss_clear_input_select'); ?>
                        </td>
                        <td>&nbsp;</td>
                        <td><?php __('Rate Type')?></td>
                        <td>
                            <select name="term_rate_type" style="width:120px;">
                                <option value="0" <?php echo $common->set_get_select('term_rate_type', 0); ?>><?php __('All')?></option>
                                <option value="1" <?php echo $common->set_get_select('orig_rate_type', 1); ?>><?php __('A-Z')?></option>
                                <option value="2" <?php echo $common->set_get_select('orig_rate_type', 2); ?>><?php __('US')?></option>
                                <option value="3" <?php echo $common->set_get_select('orig_rate_type', 3); ?>><?php __('OCN-LATA')?></option>
                            </select>
                            <?php echo $this->element('search_report/ss_clear_input_select'); ?>
                        </td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td><?php __('Rate Type')?></td>
                        <td>
                            <select style="width:120px;" id="ingress_rate_table" name="ingress_rate_table">
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
                        <td>&nbsp;</td>
                        <td></td>
                        <td>
                        </td>
                        <td>&nbsp;</td>

                        <td></td>
                        <td>
                        </td>
                    </tr>

                    <tr>
                        <td><?php __('Routing Plan')?>:</td>
                        <td>
                            <select style="width:120px;" id="ingress_routing_plan" name="ingress_routing_plan">
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
                        <td>
                        </td>
                        <td>&nbsp;</td>

                        <td></td>
                        <td>
                        </td>
                    </tr>
                </table>
                <p class="separator text-center"><i class="icon-table icon-3x"></i></p>
                <table class="form" style="width:100%">
                    <tr>
                       <td><?php __('Group By')?> #1:</td>
            <td>
                <select name="group_select[]" style="width:140px;">
                    <option value="" <?php echo $common->set_get_select_mul('group_select', '', 0, TRUE); ?>></option>
                    <option value="ingress_client_id" <?php echo $common->set_get_select_mul('group_select', 'ingress_client_id', 0); ?>><?php __('ingress Carrier')?></option>
                    <option value="ingress_id" <?php echo $common->set_get_select_mul('group_select', 'ingress_id', 0); ?>><?php __('Ingress Trunk')?></option>
                    <?php if (Configure::read('statistics.group_all')): ?>
                    <option value="ingress_country" <?php echo $common->set_get_select_mul('group_select', 'ingress_country', 0); ?>><?php __('ingress Country')?></option>
                    <option value="ingress_code_name" <?php echo $common->set_get_select_mul('group_select', 'ingress_code_name', 0); ?>><?php __('ingress Code Name')?></option>
                    <option value="ingress_code" <?php echo $common->set_get_select_mul('group_select', 'ingress_code', 0); ?>><?php __('ingress Code')?></option>
                    <option value="ingress_rate" <?php echo $common->set_get_select_mul('group_select', 'ingress_rate', 0); ?>><?php __('ingress Rate')?></option>
                    <?php endif; ?>
                    <option value="egress_client_id" <?php echo $common->set_get_select_mul('group_select', 'egress_client_id', 0); ?>><?php __('egress Carrier')?></option>
                    <option value="egress_id" <?php echo $common->set_get_select_mul('group_select', 'egress_id', 0); ?>><?php __('egress Trunk')?></option>
                    <?php if (Configure::read('statistics.group_all')): ?>
                    <option value="egress_country" <?php echo $common->set_get_select_mul('group_select', 'egress_country', 0); ?>><?php __('egress Country')?></option>
                    <option value="egress_code_name" <?php echo $common->set_get_select_mul('group_select', 'egress_code_name', 0); ?>><?php __('egress Code Name')?></option>
                    <option value="egress_code" <?php echo $common->set_get_select_mul('group_select', 'egress_code', 0); ?>><?php __('egress Code')?></option>
                    <?php endif; ?>
                </select>
            </td>
            <td><?php __('Group By')?> #2:</td>
            <td>
                <select name="group_select[]" style="width:140px;">
                    <option value="" <?php echo $common->set_get_select_mul('group_select', '', 1, TRUE); ?>></option>
                    <option value="ingress_client_id" <?php echo $common->set_get_select_mul('group_select', 'ingress_client_id', 1); ?>><?php __('ingress Carrier')?></option>
                    <option value="ingress_id" <?php echo $common->set_get_select_mul('group_select', 'ingress_id', 1); ?>><?php __('Ingress Trunk')?></option>
                    <?php if (Configure::read('statistics.group_all')): ?>
                    <option value="ingress_country" <?php echo $common->set_get_select_mul('group_select', 'ingress_country', 1); ?>><?php __('ingress Country')?></option>
                    <option value="ingress_code_name" <?php echo $common->set_get_select_mul('group_select', 'ingress_code_name', 1); ?>><?php __('ingress Code Name')?></option>
                    <option value="ingress_code" <?php echo $common->set_get_select_mul('group_select', 'ingress_code', 1); ?>><?php __('ingress Code')?></option>
                    <option value="ingress_rate" <?php echo $common->set_get_select_mul('group_select', 'ingress_rate', 1); ?>><?php __('ingress Rate')?></option>
                    <?php endif; ?>
                    <option value="egress_client_id" <?php echo $common->set_get_select_mul('group_select', 'egress_client_id', 1); ?>><?php __('egress Carrier')?></option>
                    <option value="egress_id" <?php echo $common->set_get_select_mul('group_select', 'egress_id', 1); ?>><?php __('egress Trunk')?></option>
                    <?php if (Configure::read('statistics.group_all')): ?>
                    <option value="egress_country" <?php echo $common->set_get_select_mul('group_select', 'egress_country', 1); ?>><?php __('egress Country')?></option>
                    <option value="egress_code_name" <?php echo $common->set_get_select_mul('group_select', 'egress_code_name', 1); ?>><?php __('egress Code Name')?></option>
                    <option value="egress_code" <?php echo $common->set_get_select_mul('group_select', 'egress_code', 1); ?>><?php __('egress Code')?></option>
                     <?php endif; ?>
                </select>
            </td>
            <td><?php __('Group By')?> #3:</td>
            <td>
                <select name="group_select[]" style="width:140px;">
                    <option value="" <?php echo $common->set_get_select_mul('group_select', '', 2, TRUE); ?>></option>
                    <option value="ingress_client_id" <?php echo $common->set_get_select_mul('group_select', 'ingress_client_id', 2); ?>><?php __('ingress Carrier')?></option>
                    <option value="ingress_id" <?php echo $common->set_get_select_mul('group_select', 'ingress_id', 2); ?>><?php __('Ingress Trunk')?></option>
                    <?php if (Configure::read('statistics.group_all')): ?>
                    <option value="ingress_country" <?php echo $common->set_get_select_mul('group_select', 'ingress_country', 2); ?>><?php __('ingress Country')?></option>
                    <option value="ingress_code_name" <?php echo $common->set_get_select_mul('group_select', 'ingress_code_name', 2); ?>><?php __('ingress Code Name')?></option>
                    <option value="ingress_code" <?php echo $common->set_get_select_mul('group_select', 'ingress_code', 2); ?>><?php __('ingress Code')?></option>
                    <option value="ingress_rate" <?php echo $common->set_get_select_mul('group_select', 'ingress_rate', 2); ?>><?php __('ingress Rate')?></option>
                    <?php endif; ?>
                    <option value="egress_client_id" <?php echo $common->set_get_select_mul('group_select', 'egress_client_id', 2); ?>><?php __('egress Carrier')?></option>
                    <option value="egress_id" <?php echo $common->set_get_select_mul('group_select', 'egress_id', 2); ?>><?php __('egress Trunk')?></option>
                    <?php if (Configure::read('statistics.group_all')): ?>
                    <option value="egress_country" <?php echo $common->set_get_select_mul('group_select', 'egress_country', 2); ?>><?php __('egress Country')?></option>
                    <option value="egress_code_name" <?php echo $common->set_get_select_mul('group_select', 'egress_code_name', 2); ?>><?php __('egress Code Name')?></option>
                    <option value="egress_code" <?php echo $common->set_get_select_mul('group_select', 'egress_code', 2); ?>><?php __('egress Code')?></option>
                    <?php endif; ?>
                </select>
            </td>
        </tr>
        <tr>
            <td><?php __('Group By')?> #4:</td>
            <td>
                <select name="group_select[]" style="width:140px;">
                    <option value="" <?php echo $common->set_get_select_mul('group_select', '', 3, TRUE); ?>></option>
                    <option value="ingress_client_id" <?php echo $common->set_get_select_mul('group_select', 'ingress_client_id', 3); ?>><?php __('ingress Carrier')?></option>
                    <option value="ingress_id" <?php echo $common->set_get_select_mul('group_select', 'ingress_id', 3); ?>><?php __('Ingress Trunk')?></option>
                    <?php if (Configure::read('statistics.group_all')): ?>
                    <option value="ingress_country" <?php echo $common->set_get_select_mul('group_select', 'ingress_country', 3); ?>><?php __('ingress Country')?></option>
                    <option value="ingress_code_name" <?php echo $common->set_get_select_mul('group_select', 'ingress_code_name', 3); ?>><?php __('ingress Code Name')?></option>
                    <option value="ingress_code" <?php echo $common->set_get_select_mul('group_select', 'ingress_code', 3); ?>><?php __('ingress Code')?></option>
                    <option value="ingress_rate" <?php echo $common->set_get_select_mul('group_select', 'ingress_rate', 3); ?>><?php __('ingress Rate')?></option>
                    <?php endif; ?>
                    <option value="egress_client_id" <?php echo $common->set_get_select_mul('group_select', 'egress_client_id', 3); ?>><?php __('egress Carrier')?></option>
                    <option value="egress_id" <?php echo $common->set_get_select_mul('group_select', 'egress_id', 3); ?>><?php __('egress Trunk')?></option>
                    <?php if (Configure::read('statistics.group_all')): ?>
                    <option value="egress_country" <?php echo $common->set_get_select_mul('group_select', 'egress_country', 3); ?>><?php __('egress Country')?></option>
                    <option value="egress_code_name" <?php echo $common->set_get_select_mul('group_select', 'egress_code_name', 3); ?>><?php __('egress Code Name')?></option>
                    <option value="egress_code" <?php echo $common->set_get_select_mul('group_select', 'egress_code', 3); ?>><?php __('egress Code')?></option>
                    <?php endif; ?>
                </select>
            </td>
            <td><?php __('Group By')?> #5:</td>
            <td>
                <select name="group_select[]" style="width:140px;">
                    <option value="" <?php echo $common->set_get_select_mul('group_select', '', 4, TRUE); ?>></option>
                    <option value="ingress_client_id" <?php echo $common->set_get_select_mul('group_select', 'ingress_client_id', 4); ?>><?php __('ingress Carrier')?></option>
                    <option value="ingress_id" <?php echo $common->set_get_select_mul('group_select', 'ingress_id', 4); ?>><?php __('Ingress Trunk')?></option>
                    <?php if (Configure::read('statistics.group_all')): ?>
                    <option value="ingress_country" <?php echo $common->set_get_select_mul('group_select', 'ingress_country', 4); ?>><?php __('ingress Country')?></option>
                    <option value="ingress_code_name" <?php echo $common->set_get_select_mul('group_select', 'ingress_code_name', 4); ?>><?php __('ingress Code Name')?></option>
                    <option value="ingress_code" <?php echo $common->set_get_select_mul('group_select', 'ingress_code', 4); ?>><?php __('ingress Code')?></option>
                    <option value="ingress_rate" <?php echo $common->set_get_select_mul('group_select', 'ingress_rate', 4); ?>><?php __('ingress Rate')?></option>
                    <?php endif; ?>
                    <option value="egress_client_id" <?php echo $common->set_get_select_mul('group_select', 'egress_client_id', 4); ?>><?php __('egress Carrier')?></option>
                    <option value="egress_id" <?php echo $common->set_get_select_mul('group_select', 'egress_id', 4); ?>><?php __('egress Trunk')?></option>
                    <?php if (Configure::read('statistics.group_all')): ?>
                    <option value="egress_country" <?php echo $common->set_get_select_mul('group_select', 'egress_country', 4); ?>><?php __('egress Country')?></option>
                    <option value="egress_code_name" <?php echo $common->set_get_select_mul('group_select', 'egress_code_name', 4); ?>><?php __('egress Code Name')?></option>
                    <option value="egress_code" <?php echo $common->set_get_select_mul('group_select', 'egress_code', 4); ?>><?php __('egress Code')?></option>
                    <?php endif; ?>
                </select>
            </td>
            <td><?php __('Group By')?> #6:</td>
            <td>
                <select name="group_select[]" style="width:140px;">
                    <option value="" <?php echo $common->set_get_select_mul('group_select', '', 5, TRUE); ?>></option>
                    <option value="ingress_client_id" <?php echo $common->set_get_select_mul('group_select', 'ingress_client_id', 5); ?>><?php __('ingress Carrier')?></option>
                    <option value="ingress_id" <?php echo $common->set_get_select_mul('group_select', 'ingress_id', 5); ?>><?php __('Ingress Trunk')?></option>
                    <?php if (Configure::read('statistics.group_all')): ?>
                    <option value="ingress_country" <?php echo $common->set_get_select_mul('group_select', 'ingress_country', 5); ?>><?php __('ingress Country')?></option>
                    <option value="ingress_code_name" <?php echo $common->set_get_select_mul('group_select', 'ingress_code_name', 5); ?>><?php __('ingress Code Name')?></option>
                    <option value="ingress_code" <?php echo $common->set_get_select_mul('group_select', 'ingress_code', 5); ?>><?php __('ingress Code')?></option>
                    <option value="ingress_rate" <?php echo $common->set_get_select_mul('group_select', 'ingress_rate', 5); ?>><?php __('ingress Rate')?></option>
                    <?php endif; ?>
                    <option value="egress_client_id" <?php echo $common->set_get_select_mul('group_select', 'egress_client_id', 5); ?>><?php __('egress Carrier')?></option>
                    <option value="egress_id" <?php echo $common->set_get_select_mul('group_select', 'egress_id', 5); ?>><?php __('egress Trunk')?></option>
                    <?php if (Configure::read('statistics.group_all')): ?>
                    <option value="egress_country" <?php echo $common->set_get_select_mul('group_select', 'egress_country', 5); ?>><?php __('egress Country')?></option>
                    <option value="egress_code_name" <?php echo $common->set_get_select_mul('group_select', 'egress_code_name', 5); ?>><?php __('egress Code Name')?></option>
                    <option value="egress_code" <?php echo $common->set_get_select_mul('group_select', 'egress_code', 5); ?>><?php __('egress Code')?></option>
                    <?php endif; ?>
                </select>
            </td>
                    </tr>
                </table>
            </fieldset>
            <?php echo $form->end(); ?>
        </div>
        <?php if (isset($send) && !empty($send)): ?>
            <div><?php echo $send; ?></div>
        <?php endif; ?>
    </div>
</div>

<script type="text/javascript">

    var $routeprefix = $("#CdrRoutePrefix");
    var $ingress_rate_table = $('#ingress_rate_table');
    var $ingress_routing_plan = $('#ingress_routing_plan');
    $(function() {

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

            $.post("<?php echo $this->webroot ?>cdrreports/getTechPerfix", {ingId: val},
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
