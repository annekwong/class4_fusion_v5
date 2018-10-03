<ul class="breadcrumb">
    <li><?php echo __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Statistics') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('QoS Summary') ?></li>
</ul>


<div class="heading-buttons">
    <h4 class="heading"><?php __('QoS Summary') ?></h4>
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>


<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-head">
            <ul class="tabs">
                <li <?php if ($type == 1) echo 'class="active"'; ?>>
                    <a href="<?php echo $this->webroot; ?>reports/qos_summary/1"  class="glyphicons left_arrow">
                        <i></i>
                        <?php __('Origination')?>
                    </a>
                </li>
                <li <?php if ($type == 2) echo 'class="active"'; ?>>
                    <a href="<?php echo $this->webroot; ?>reports/qos_summary/2"  class="glyphicons right_arrow">
                        <i></i>
                        <?php __('Termination')?>
                    </a>
                </li>
            </ul>
        </div>
        <div class="widget-body">
            <?php if ($show_nodata): ?><h1 style="font-size:14px;">Report Period <?php echo $start_date ?> — <?php echo $end_date ?></h1><?php endif; ?>
            <?php if (empty($data)): ?>
                <?php if ($show_nodata): ?><h2 class="msg center"><?php  echo __('no_data_found') ?></h2><?php endif; ?>
            <?php else: ?>
                <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
                    <thead>
                        <tr>
                            <?php foreach ($show_fields as $field): ?>
                                <th><?php echo $replace_fields[$field]; ?></th>
                            <?php endforeach; ?>
                            <th><?php __('ABR')?></th>
                            <th><?php __('ASR')?></th>
                            <th><?php __('ACD(min)')?></th>
                            <th><?php __('ALOC')?></th>
                            <th><?php __('PDD(ms)')?></th>
                            <th colspan="1"><?php __('Time(min)')?></th>
                            <th colspan="4"><?php __('Calls')?></th>
                        </tr>
                        <tr>
                            <?php for ($i = 0; $i < count($show_fields); $i++): ?>
                                <th>&nbsp;</th>
                            <?php endfor; ?>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th><?php __('Total Billable Time')?></th>
                            <th><?php __('Total Calls')?></th>
                            <th><?php __('Not Zero')?></th>
                            <th><?php __('Success Calls')?></th>
                            <th><?php __('Busy Calls')?></th>
                    </thead>
                    <tbody>
                        <?php
                        $i = 0;
                        $arr = array();
                        foreach ($data as $item):
                            $arr['duration'][$i] = $item[0]['duration'];
                            $arr['bill_time'][$i] = $type == 1 ? $item[0]['ingress_bill_time'] : $item[0]['egress_bill_time'];
                            $arr['cancel_calls'][$i] = $type == 1 ? $item[0]['ingress_cancel_calls'] : $item[0]['egress_cancel_calls'];
                            $arr['total_calls'][$i] = $type == 1 ? $item[0]['ingress_total_calls'] : $item[0]['egress_total_calls'];
                            $arr['not_zero_calls'][$i] = $item[0]['not_zero_calls'];
                            $arr['success_calls'][$i] = $type == 1 ? $item[0]['ingress_success_calls'] : $item[0]['egress_success_calls'];
                            $arr['busy_calls'][$i] = $type == 1 ? $item[0]['ingress_busy_calls'] : $item[0]['egress_busy_calls'];
                            $arr['pdd'][$i] = $item[0]['pdd'];
                            ?>
                            <tr>
                                <?php foreach (array_keys($show_fields) as $key): ?>
                                    <td style="color:#6694E3;"><?php echo $item[0][$key]; ?></td>
                                <?php endforeach; ?>
                                <td><?php echo round($arr['total_calls'][$i] == 0 ? 0 : $arr['not_zero_calls'][$i] / $arr['total_calls'][$i] * 100, 2); ?>%</td>
                                <td><?php echo ($arr['busy_calls'][$i] + $arr['cancel_calls'][$i] + $arr['not_zero_calls'][$i]) == 0 ? 0 : round($arr['not_zero_calls'][$i] / ($arr['busy_calls'][$i] + $arr['cancel_calls'][$i] + $arr['not_zero_calls'][$i]) * 100, 2) ?>%</td>
                                <td><?php echo round($arr['not_zero_calls'][$i] == 0 ? 0 : $arr['duration'][$i] / $arr['not_zero_calls'][$i] / 60, 2); ?></td>
                                <td>
                                    <?php
                                    echo round((($arr['busy_calls'][$i] + $arr['cancel_calls'][$i] + $arr['not_zero_calls'][$i]) == 0 ? 0 : $arr['not_zero_calls'][$i] / ($arr['busy_calls'][$i] + $arr['cancel_calls'][$i] + $arr['not_zero_calls'][$i])) * ($arr['not_zero_calls'][$i] == 0 ? 0 : $arr['duration'][$i] / $arr['not_zero_calls'][$i] / 60), 2);
                                    ?>


                                </td>
                                <td><?php echo round($arr['not_zero_calls'][$i] == 0 ? 0 : $arr['pdd'][$i] / $arr['not_zero_calls'][$i]); ?></td>
                                <td><?php echo number_format($arr['bill_time'][$i] / 60, 2); ?></td> 

                                <td><?php echo number_format($arr['total_calls'][$i]); ?></td> 
                                <td><?php echo number_format($arr['not_zero_calls'][$i]); ?></td>
                                <td><?php echo number_format($arr['success_calls'][$i]); ?></td>
                                <td><?php echo number_format($arr['busy_calls'][$i]); ?></td>

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
                                <td colspan="<?php echo $count_group; ?>"><?php __('Total')?>:</td>
                                <td><?php echo round(array_sum($arr['total_calls']) == 0 ? 0 : array_sum($arr['not_zero_calls']) / array_sum($arr['total_calls']) * 100, 2); ?>%</td>
                                <td><?php echo (array_sum($arr['busy_calls']) + array_sum($arr['cancel_calls']) + array_sum($arr['not_zero_calls'])) == 0 ? 0 : round(array_sum($arr['not_zero_calls']) / (array_sum($arr['busy_calls']) + array_sum($arr['cancel_calls']) + array_sum($arr['not_zero_calls'])) * 100, 2) ?>%</td>
                                <td><?php echo round(array_sum($arr['not_zero_calls']) == 0 ? 0 : array_sum($arr['duration']) / array_sum($arr['not_zero_calls']) / 60, 2); ?></td>
                                <td><?php echo round(((array_sum($arr['busy_calls']) + array_sum($arr['cancel_calls']) + array_sum($arr['not_zero_calls'])) == 0 ? 0 : array_sum($arr['not_zero_calls']) / (array_sum($arr['busy_calls']) + array_sum($arr['cancel_calls']) + array_sum($arr['not_zero_calls']))) * (array_sum($arr['not_zero_calls']) == 0 ? 0 : array_sum($arr['duration']) / array_sum($arr['not_zero_calls']) / 60), 2); ?></td>
                                <td><?php echo round(array_sum($arr['not_zero_calls']) == 0 ? 0 : array_sum($arr['pdd']) / array_sum($arr['not_zero_calls'])); ?></td>
                                <td><?php echo number_format(array_sum($arr['bill_time']) / 60, 2); ?></td>
                                <td><?php echo number_format(array_sum($arr['total_calls'])); ?></td> 
                                <td><?php echo number_format(array_sum($arr['not_zero_calls'])); ?></td>
                                <td><?php echo number_format(array_sum($arr['success_calls'])); ?></td>
                                <td><?php echo number_format(array_sum($arr['busy_calls'])); ?></td>
                            </tr>
                            <?php
                        endif;
                        ?>
                    </tbody>
                </table>
            <?php endif; ?>
            <?php echo $form->create('Cdr', array('type' => 'get', 'url' => "/reports/qos_summary/{$type}", 'onsubmit' => "if($('select[name=show_type]').val() == 0) loading();")); ?>
            <fieldset class="query-box" style="clear:both;overflow:hidden;margin-top:10px;">
                <h4 class="heading glyphicons search"><i></i> <?php __('Search')?></h4>
                <?php echo $this->element('search_report/search_js'); ?> <?php echo $this->element('search_report/search_hide_input'); ?>
                <table class="form" style="width:100%">
                    <?php echo $this->element('report/form_period', array('group_time' => true, 'gettype' => '<select style="width:120px;" name="show_type">
            <option value="0">Web</option>
            <option value="1">CSV</option>
            <option value="2">XLS</option>
        </select>')) ?>
                    <tr class="period-block" style="height:20px; line-height:20px;">
                        <td colspan="2" style="text-align:center; font-size:14px;"><b><?php echo __('Inbound', true); ?></b></td>
                        <td class="in-out_bound">&nbsp;</td>
                        <td colspan="2" style="text-align:center;font-size:14px;"><b><?php echo __('Outbound', true); ?></b></td>
                        <td class="in-out_bound">&nbsp;</td>
                        <td colspan="2">&nbsp;</td>
                    </tr>
                    <tr>
                        <td><?php __('Carriers')?>:</td>
                        <td>
                            <select style="width:120px;" name="ingress_client_id">
                                <option></option>
                                <?php foreach ($ingress_clients as $ingress_client): ?>
                                    <option value="<?php echo $ingress_client[0]['client_id'] ?>" <?php echo $common->set_get_select('ingress_client_id', $ingress_client[0]['client_id']) ?>><?php echo $ingress_client[0]['name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                            <?php echo $this->element('search_report/ss_clear_input_select'); ?>
                        </td>
                        <td>&nbsp;</td>
                        <td>:</td>
                        <td>
                            <select style="width:120px;" name="egress_client_id">
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
                    </tr>
                    <tr>
                        <td>:</td>
                        <td>
                            <select style="width:120px;" name="ingress_id" onchange="getTechPrefix(this);">
                                <?php if (empty($_GET['ingress_id']))
                                {
                                    ?>
                                    <option selected=""></option>
                                <?php
                                }
                                else
                                {
                                    ?>
                                    <option></option>
                                <?php } ?>
                                <?php
                                foreach ($ingress_trunks as $ingress_trunk):

                                    if ($_GET['ingress_id'] == $ingress_trunk[0]['resource_id'])
                                    {
                                        ?>
                                        <option selected value="<?php echo $ingress_trunk[0]['resource_id'] ?>" <?php echo $common->set_get_select('ingress_id', $ingress_trunk[0]['resource_id']) ?>><?php echo $ingress_trunk[0]['alias'] ?></option>
                                        <?php
                                    }
                                    else
                                    {
                                        ?>
                                        <option value="<?php echo $ingress_trunk[0]['resource_id'] ?>" <?php echo $common->set_get_select('ingress_id', $ingress_trunk[0]['resource_id']) ?>><?php echo $ingress_trunk[0]['alias'] ?></option>
        <?php
    }
    ?>


<?php endforeach; ?>
                            </select>
<?php echo $this->element('search_report/ss_clear_input_select'); ?>
                        </td>
                        <td>&nbsp;</td>
                        <td><?php __('Egress Trunk')?>:</td>
                        <td>
                            <select style="width:120px;" name="egress_id">
                                <option></option>
                            <?php foreach ($egress_trunks as $egress_trunk): ?>
                                    <option value="<?php echo $egress_trunk[0]['resource_id'] ?>" <?php echo $common->set_get_select('egress_id', $egress_trunk[0]['resource_id']) ?>><?php echo $egress_trunk[0]['alias'] ?></option>
<?php endforeach; ?>
                            </select>
<?php echo $this->element('search_report/ss_clear_input_select'); ?>
                        </td>
                        <td rowspan="4">&nbsp;</td>
                        <td rowspan="4"><!--Group By:--></td>
                        <td rowspan="4" colspan="2">
                            <!--
                            <select multiple="multiple" name="group_select[]" style="height:170px;">
                                <option value="ingress_client_id" <?php echo $common->set_get_select('group_select', 'ingress_client_id'); ?>>ORIG Carrier</option>
                                <option value="ingress_id" <?php echo $common->set_get_select('group_select', 'ingress_id'); ?>>Ingress Trunk</option>
                                <option value="orig_country" <?php echo $common->set_get_select('group_select', 'ingress_country'); ?>>ORIG Country</option>
                                <option value="orig_code_name" <?php echo $common->set_get_select('group_select', 'ingress_code_name'); ?>>ORIG Code Name</option>
                                <option value="orig_code" <?php echo $common->set_get_select('group_select', 'orig_code'); ?>>ORIG Code</option>
                                <option value="" selected="selected"></option>
                                <option value="egress_client_id" <?php echo $common->set_get_select('group_select', 'egress_client_id'); ?>>TERM Carrier</option>
                                <option value="egress_id" <?php echo $common->set_get_select('group_select', 'egress_id'); ?>>Egress Trunk</option>
                                <option value="term_country" <?php echo $common->set_get_select('group_select', 'egress_country'); ?>>TERM Country</option>
                                <option value="term_code_name" <?php echo $common->set_get_select('group_select', 'egress_code_name'); ?>>TERM Code Name</option>
                                <option value="term_code" <?php echo $common->set_get_select('group_select', 'term_code'); ?>>TERM Code</option>
                            </select>
                            -->
                        </td>
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
                            <a onclick="clear_prefix(this);" href="javascript:void(0)">
                                <img class="img-button" width="9" height="9" src="<?php echo $this->webroot ?>images/delete-small.png">
                            </a>
                        </td>
                        <td class="in-out_bound">&nbsp;</td>


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
                        <td class="in-out_bound">&nbsp;</td>
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
                                <option value="1" <?php echo $common->set_get_select('term_rate_type', 1); ?>><?php __('A-Z')?></option>
                                <option value="2" <?php echo $common->set_get_select('term_rate_type', 2); ?>><?php __('US')?></option>
                                <option value="3" <?php echo $common->set_get_select('term_rate_type', 3); ?>><?php __('OCN-LATA')?></option>
                            </select>
<?php echo $this->element('search_report/ss_clear_input_select'); ?>
                        </td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td><?php __('Rate Table')?></td>
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
                <div class="group_by">
                    <?php __('Group By')?>
                </div>
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
