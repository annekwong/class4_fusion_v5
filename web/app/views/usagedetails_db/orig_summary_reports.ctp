<!--导入所有reoprt页面的input和select样式文件-->
<style>
    #stats-period{display: inline-block}
</style>
<?php echo $this->element('magic_css'); ?>
<?php

$user_id = $_SESSION['sst_user_id'];
$res = $cdr_db->query("select * from users where user_id = {$user_id} ");

?>
<ul class="breadcrumb">
    <li><?php echo __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>usagedetails_db/orig_summary_reports">
            <?php __('Statistics') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>usagedetails_db/orig_summary_reports">
            <?php echo __('Usage Detail Report') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>usagedetails_db/orig_summary_reports">
            <?php echo __('Orig Report') ?></a></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Orig Report') ?></h4>
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>

<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">

        <div class="widget-head">
            <ul class="tabs">
                <li class='active'><a href="<?php echo $this->webroot ?>usagedetails_db/orig_summary_reports"  class="glyphicons left_arrow"><i></i><?php __('Origination')?></a></li>
                <?php
                if($res[0][0]['all_termination'] == 't'){
                    ?>
                    <li><a href="<?php echo $this->webroot ?>usagedetails_db/term_summary_reports"  class="glyphicons right_arrow"><i></i><?php __('Termination')?></a>  </li>
                    <?php
                }
                ?>
                <li><a href="<?php echo $this->webroot ?>usagedetails_db/daily_orig_summary"  class="glyphicons left_arrow"><i></i><?php __('Daily Origination')?></a></li>

                <?php
                if($res[0][0]['all_termination'] == 't'){
                    ?>
                    <li><a href="<?php echo $this->webroot ?>usagedetails_db/daily_term_summary"  class="glyphicons right_arrow"><i></i><?php __('Daily Termination')?></a>  </li>
                    <?php
                }
                ?>
            </ul>
        </div>
        <div class="widget-body">
            <?php if ($show_nodata): ?>
                <?php echo $this->element('report_db/real_period') ?>
            <?php endif; ?>

            <!-- ****************************************普通输出******************************************* -->
            <div class="table_container">
                <?php if (empty($data)): ?>
                    <?php if ($show_nodata): ?>
                        <div class="msg center">
                            <h2><?php  echo __('no_data_found') ?></h2>
                        </div>
                    <?php endif; ?>
                <?php else: ?>


                    <?php
                    $days = array();
                    $startdate = strtotime($start);
                    $enddate = strtotime($end);
                    $day = round(($enddate - $startdate) / 3600 / 24);
                    $dt_begin = new DateTime($start);
                    for ($i = 0; $i < $day; $i++)
                    {
                        if ($i > 0)
                        {
                            $dt_begin->modify('+1 days');
                        }
                        array_push($days, $dt_begin->format('Y-m-d'));
                    }
                    ?>
                    <div class="overflow_x">
                        <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">

                            <thead>
                            <tr>
                                <?php
                                foreach ($filed_arr as $value):
                                    if (isset($replace_fields[$value]))
                                    {
                                        echo "<th rowspan=\"2\">" . $replace_fields[$value] . "</th>";
                                    }
                                    else
                                    {
                                        echo "<th rowspan=\"2\">" . $value . "</th>";
                                    }
                                endforeach;
                                ?>
                                <th rowspan="2"><?php __('Not Zero Calls') ?></th>
                                <th rowspan="2"><?php __('Total(Min)') ?></th>
                                <th colspan="2">Calls < 30s</th>
                                <th colspan="2"><?php echo $appCommon->show_order('call_6s', __('Calls <= 6s', true)) ?></th>
                                <?php foreach ($days as $item): ?>
                                    <th colspan="5">
                                        <?php echo $item; ?>
                                    </th>
                                <?php endforeach; ?>
                            </tr>
                            <tr>
                                <?php
                                //                                foreach ($filed_arr as $value):
                                //                                    echo "<th></th>";
                                //                                endforeach;
                                ?>
                                <!--                                <th></th>-->
                                <!--                                <th></th>-->
                                <th><?php __('Count'); ?></th>
                                <th><?php __('%'); ?></th>
                                <th><?php __('Count'); ?></th>
                                <th><?php __('%'); ?></th>
                                <?php
                                for ($i = 0; $i < $day; $i++)
                                {
                                    ?>
                                    <th><?php __('Billed Time (min)') ?></th>
                                    <th><?php __('ASR (%)') ?></th>
                                    <th><?php __('ACD (min)') ?></th>
                                    <th><?php __('NPR Count') ?></th>
                                    <th><?php __('NPR') ?></th>
                                <?php } ?>
                            </tr>
                            </thead>

                            <tbody>
                            <?php
                            $totalArray = array(
                                'not_zero_calls' => 0,
                                'total_calls' => 0,
                                'total_time' => 0,
                                'calls_30' => 0,
                                'calls_6' => 0,
                                'bill_time' => 0,
                                'asr' => 0,
                                'acd' => 0,
                                'npr_count' => 0,
                                'npr' => 0
                            );

                            foreach ($days as $day_item) {
                                $totalArray[$day_item] = array(
                                    'bill_time' => 0,
                                    'asr' => 0,
                                    'acd' => 0,
                                    'npr_count' => 0,
                                    'total_calls' => 0
                                );
                            }

                            $total_time_total = 0;
                            $calls_3_total = 0;
                            $time_3_total = 0;
                            $calls_6_total = 0;
                            $time_6_total = 0;
                            $years_total = array();
                            foreach ($days as $day)
                            {
                                $years_total[$day] = 0;
                            }
                            foreach ($data as $key => $item):
                                $total_time_total += $item['total_time'];
                                $calls_3_total += $item['calls_30'] + $item['calls_6'];
                                $calls_6_total += $item['calls_6'];

                                $totalArray['not_zero_calls'] += $item['not_zero_calls'];
                                $totalArray['total_time'] += $item['total_time'];
                                $totalArray['calls_30'] += $item['calls_30'];
                                $totalArray['calls_6'] += $item['calls_6'];

                                ?>
                                <tr>
                                    <?php
                                    foreach ($filed_arr as $value):
                                        if (isset($item[$value]))
                                        {
                                            echo "<td>" . $item[$value] . "</td>";
                                        }
                                        else
                                        {
                                            echo "<td></td>";
                                        }
                                    endforeach;
                                    ?>
                                    <td><?php echo $item['not_zero_calls']; ?></td>
                                    <td><?php echo number_format($item['total_time'] / 60, 2); ?></td>
                                    <td><?php echo $item['calls_30']; ?></td>
                                    <td><?php echo $item['not_zero_calls'] == 0 ? 0 : number_format(($item['calls_30']) / $item['not_zero_calls'] * 100, 2); ?></td>
                                    <!--                <td><?php echo number_format($item['time_30'] / 60, 2); ?></td>-->
                                    <td><?php echo $item['calls_6']; ?></td>
                                    <td><?php echo $item['calls_6'] == 0 ? 0 : number_format($item['calls_6'] / $item['not_zero_calls'] * 100, 2); ?></td>
                                    <!--                <td><?php echo number_format($item['time_6'] / 60, 2); ?></td>-->
                                    <?php
                                    foreach ($days as $day_item){?>
                                        <?php
                                        if (array_key_exists($day_item, $item['years'])):
                                            $totalArray[$day_item]['bill_time'] += $item['years'][$day_item]['bill_time'];
                                            $totalArray[$day_item]['asr'] += $item['years'][$day_item]['total_calls'] == 0 ? 0 : $item['years'][$day_item]['not_zero_calls'] / $item['years'][$day_item]['total_calls'] * 100;
                                            $totalArray[$day_item]['acd'] += $item['years'][$day_item]['not_zero_calls'] == 0 ? 0 : $item['years'][$day_item]['total_time'] / $item['years'][$day_item]['not_zero_calls'] / 60;
                                            $totalArray[$day_item]['npr_count'] += isset($item['years'][$day_item]['npr_count']) ? $item['years'][$day_item]['npr_count'] : 0;
                                            $totalArray[$day_item]['total_calls'] += isset($item['years'][$day_item]['total_calls']) ? $item['years'][$day_item]['total_calls'] : 0;
                                            ?>
                                            <td><?php echo number_format($item['years'][$day_item]['bill_time'] / 60, 2); ?></td>
                                            <td><?php echo $item['years'][$day_item]['total_calls'] == 0 ? 0 : number_format($item['years'][$day_item]['not_zero_calls'] / $item['years'][$day_item]['total_calls'] * 100, 2); ?></td>
                                            <td><?php echo $item['years'][$day_item]['not_zero_calls'] == 0 ? 0 : number_format($item['years'][$day_item]['total_time'] / $item['years'][$day_item]['not_zero_calls'] / 60, 5); ?></td>
                                            <td><?php echo isset($item['years'][$day_item]['npr_count']) ? number_format($item['years'][$day_item]['npr_count']) : ''; ?></td>
                                            <td><?php echo isset($item['years'][$day_item]['total_calls']) && isset( $item['years'][$day_item]['npr_count']) ? number_format($item['years'][$day_item]['total_calls'] == 0 ? 0 : $item['years'][$day_item]['npr_count'] / $item['years'][$day_item]['total_calls'] * 100, 2) : 0; ?>%</td>

                                        <?php else: ?>
                                            <td>0</td><td>0</td><td>0</td>
                                        <?php endif ?>
                                    <?php } ?>
                                </tr>
                                <?php
                            endforeach;
                            ?>

                            <tr>
                                <td colspan="<?php echo count($filed_arr);?>">Total:</td>
                                <td><?php echo $totalArray['not_zero_calls']; ?></td>
                                <td><?php echo number_format($totalArray['total_time'] / 60, 2); ?></td>
                                <td><?php echo $totalArray['calls_30']; ?></td>
                                <td><?php echo $totalArray['not_zero_calls'] == 0 ? 0 : number_format(($totalArray['calls_30']) / $totalArray['not_zero_calls'] * 100, 2); ?></td>
                                <td><?php echo $totalArray['calls_6']; ?></td>
                                <td><?php echo $totalArray['calls_6'] == 0 ? 0 : number_format($totalArray['calls_6'] / $totalArray['not_zero_calls'] * 100, 2); ?></td>
                                <?php foreach ($days as $day_item): ?>
                                    <?php
                                    if (array_key_exists($day_item, $totalArray)):
                                        ?>
                                        <td><?php echo number_format($totalArray[$day_item]['bill_time'] / 60, 2); ?></td>
                                        <td><?php echo number_format($totalArray[$day_item]['asr'], 2); ?></td>
                                        <td><?php echo number_format($totalArray[$day_item]['acd'], 5); ?></td>
                                        <td><?php echo number_format($totalArray[$day_item]['npr_count']); ?></td>
                                        <td><?php echo number_format($totalArray[$day_item]['total_calls'] == 0 ? 0 : $totalArray[$day_item]['npr_count'] / $totalArray[$day_item]['total_calls'] * 100, 2); ?>%</td>
                                    <?php else: ?>
                                        <td>0</td><td>0</td><td>0</td>
                                    <?php endif ?>
                                <?php endforeach; ?>
                            </tr>
                            </tbody>



                        </table>
                    </div>
                <?php endif; ?>

                <fieldset class="query-box" style=" clear:both;overflow:hidden;margin-top:10px;">
                    <h4 class="heading glyphicons search" style="display: inline-block;"><i></i> Search</h4>
                    <div class="pull-right" title="Advance">
                        <a id="advance_btn" class="btn" href="###">
                            <i class="icon-long-arrow-down"></i>
                        </a>
                    </div>
                    <div class="clearfix"></div>
                    <?php echo $this->element('search_report/search_js'); ?><?php echo $form->create('Cdr', array('type' => 'get', 'url' => '/usagedetails_db/orig_summary_reports/', 'onsubmit' => "if ($('#query-output').val() == 'web') loading();")); ?>  <?php echo $this->element('search_report/search_hide_input'); ?>
                    <table class="form" style="width:100%">
                        <?php echo $this->element('report_db/form_period', array('group_time' => true, 'gettype' => '<select style="width:120px;" name="show_type">
            <option value="0">Web</option>
            <option value="1">CSV</option>
            <option value="2">XLS</option>
        </select>')) ?>
                    </table>
                    <div id="advance_panel" class="widget widget-heading-simple widget-body-gray">
                        <table class="form" style="width:100%">
                            <tbody>
                            <tr>
                                <td class="align_right padding-r10"><?php __('Carriers')?>:</td>
                                <td>
                                    <select class="client_options_ingress" name="ingress_client_id">
                                        <option></option>
                                        <?php foreach ($ingress_clients as $ingress_client): ?>
                                            <option value="<?php echo $ingress_client[0]['client_id'] ?>" <?php echo $common->set_get_select('ingress_client_id', $ingress_client[0]['client_id']) ?>><?php echo $ingress_client[0]['name'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <?php echo $this->element('search_report/ss_clear_input_select'); ?>
                                </td>
                                <td class="align_right padding-r10"><?php __('Ingress Trunk')?>:</td>
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
                            </tr>


                            <tr>

                                <td class="align_right padding-r10"><?php __('Tech Prefix')?></td>
                                <td>
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
                                    <?php echo $this->element('search_report/ss_clear_input_select'); ?>
                                </td>
                                <td class="align_right padding-r10"><?php __('Country')?>:</td>
                                <td>
                                    <input type="text" class="width220"  name="orig_country" value="<?php echo $common->set_get_value('orig_country') ?>" />
                                    <?php echo $this->element('search_report/ss_clear_input_select'); ?>
                                </td>

                            </tr>
                            <tr>
                                <td class="align_right padding-r10"><?php __('Code Name')?>:</td>
                                <td>
                                    <input type="text" class="width220" name="orig_code_name" value="<?php echo $common->set_get_value('orig_code_name') ?>" />
                                    <?php echo $this->element('search_report/ss_clear_input_select'); ?>
                                </td>
                                <?php if (Configure::read('statistics.have_code_rate')): ?>
                                    <td class="align_right padding-r10"><?php __('Code')?>:</td>
                                    <td>
                                        <input type="text" class="width220"  name="orig_code" value="<?php echo $common->set_get_value('orig_code') ?>" />
                                        <?php echo $this->element('search_report/ss_clear_input_select'); ?>
                                    </td>
                                <?php else: ?>
                                    <td></td>
                                    <td></td>
                                <?php endif; ?>
                            </tr>
                            <tr>
                                <td class="align_right padding-r10"><?php __('Jurisdiction Type')?></td>
                                <td>
                                    <select name="ingress_jur_type">
                                        <option value="" <?php echo $common->set_get_select('ingress_jur_type', ''); ?>><?php __('All')?></option>
                                        <option value="0" <?php echo $common->set_get_select('ingress_jur_type', 0); ?>><?php __('A-Z')?></option>
                                        <option value="1" <?php echo $common->set_get_select('ingress_jur_type', 1); ?>><?php __('US NON-JD')?></option>
                                        <option value="2" <?php echo $common->set_get_select('ingress_jur_type', 2); ?>><?php __('US JD')?></option>
                                        <option value="3" <?php echo $common->set_get_select('ingress_jur_type', 3); ?>><?php __('OCN-LATA-JD')?></option>
                                        <option value="4" <?php echo $common->set_get_select('ingress_jur_type', 4); ?>><?php __('OCN-LATA-NON-JD')?></option>
                                    </select>
                                    <?php echo $this->element('search_report/ss_clear_input_select'); ?>
                                </td>
                                <td class="align_right padding-r10"><?php __('Rate Table')?></td>
                                <td>
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
                            </tr>

                            <tr>
                                <td class="align_right padding-r10"><?php __('Routing Plan')?>:</td>
                                <td>
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
                                <td class="align_right padding-r10"><?php __('Switch Server')?></td>
                                <td>
                                    <?php echo $form->input('ingress_profile_ip', array('options' => $servers, 'style' => 'width:220px;', 'empty' => '', 'label' => false, 'div' => false, 'type' => 'select', 'selected' => isset($_GET['egress_profile_ip']) ? $_GET['egress_profile_ip'] : "")); ?>
                                    <?php echo $this->element('search_report/ss_clear_input_select'); ?>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <?php if ($report_group)
                        { ?>
                            <p class="separator text-center"><i class="icon-table icon-3x"></i></p>

                            <table class="form" style="width:100%">
                                <tr>
                                    <td><?php __('Group By')?> #1:</td>
                                    <td>
                                        <select name="group_select[]" style="width:140px;">
                                            <option value="ingress_client_id" <?php echo $common->set_get_select_mul('group_select', 'ingress_client_id', 0); ?>><?php __('ingress Carrier')?></option>
                                        </select>
                                    </td>
                                    <td><?php __('Group By')?> #2:</td>
                                    <td>
                                        <select name="group_select[]" style="width:140px;">
                                            <option value="ingress_id" <?php echo $common->set_get_select_mul('group_select', 'ingress_id', 1); ?>><?php __('Ingress Trunk')?></option>
                                        </select>
                                    </td>
                                    <td><?php __('Group By')?> #3:</td>
                                    <td>
                                        <select name="group_select[]" style="width:140px;">
                                            <option value="" <?php echo $common->set_get_select_mul('group_select', '', 2, TRUE); ?>></option>

                                            <?php if (Configure::read('statistics.group_all')): ?>
                                                <option value="ingress_country" <?php echo $common->set_get_select_mul('group_select', 'ingress_country', 2); ?>><?php __('ingress Country')?></option>
                                                <option value="ingress_code_name" <?php echo $common->set_get_select_mul('group_select', 'ingress_code_name', 2); ?>><?php __('ingress Code Name')?></option>
                                                <?php if (Configure::read('statistics.have_code_rate')): ?>
                                                    <option value="ingress_code" <?php echo $common->set_get_select_mul('group_select', 'ingress_code', 2); ?>><?php __('ingress Code')?></option>
                                                <?php endif; ?><?php endif; ?>
                                            <option value="egress_client_id" <?php echo $common->set_get_select_mul('group_select', 'egress_client_id', 2); ?>><?php __('egress Carrier')?></option>
                                            <option value="egress_id" <?php echo $common->set_get_select_mul('group_select', 'egress_id', 2); ?>><?php __('Egress Trunk')?></option>
                                            <?php if (Configure::read('statistics.group_all')): ?>
                                                <option value="egress_country" <?php echo $common->set_get_select_mul('group_select', 'egress_country', 2); ?>><?php __('egress Country')?></option>
                                                <option value="egress_code_name" <?php echo $common->set_get_select_mul('group_select', 'egress_code_name', 2); ?>><?php __('egress Code Name')?></option>
                                                <?php if (Configure::read('statistics.have_code_rate')): ?>
                                                    <option value="egress_code" <?php echo $common->set_get_select_mul('group_select', 'egress_code', 2); ?>><?php __('egress Code')?></option>
                                                <?php endif; ?><?php endif; ?>
                                            <!--                    <option value="origination_destination_host_name" <?php echo $common->set_get_select_mul('group_select', 'origination_destination_host_name', 2); ?>>Orig Server</option>
    <option value="termination_source_host_name" <?php echo $common->set_get_select_mul('group_select', 'termination_source_host_name', 2); ?>>Term Server</option>-->
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td><?php __('Group By')?> #4:</td>
                                    <td>
                                        <select name="group_select[]" style="width:140px;">
                                            <option value="" <?php echo $common->set_get_select_mul('group_select', '', 3, TRUE); ?>></option>

                                            <?php if (Configure::read('statistics.group_all')): ?>
                                                <option value="ingress_country" <?php echo $common->set_get_select_mul('group_select', 'ingress_country', 3); ?>><?php __('ingress Country')?></option>
                                                <option value="ingress_code_name" <?php echo $common->set_get_select_mul('group_select', 'ingress_code_name', 3); ?>><?php __('ingress Code Name')?></option>
                                                <?php if (Configure::read('statistics.have_code_rate')): ?>
                                                    <option value="ingress_code" <?php echo $common->set_get_select_mul('group_select', 'ingress_code', 3); ?>><?php __('ingress Code')?></option>
                                                <?php endif; ?><?php endif; ?>
                                            <option value="egress_client_id" <?php echo $common->set_get_select_mul('group_select', 'egress_client_id', 3); ?>><?php __('egress Carrier')?></option>
                                            <option value="egress_id" <?php echo $common->set_get_select_mul('group_select', 'egress_id', 3); ?>><?php __('Egress Trunk')?></option>
                                            <?php if (Configure::read('statistics.group_all')): ?>
                                                <option value="egress_country" <?php echo $common->set_get_select_mul('group_select', 'egress_country', 3); ?>><?php __('egress Country')?></option>
                                                <option value="egress_code_name" <?php echo $common->set_get_select_mul('group_select', 'egress_code_name', 3); ?>><?php __('egress Code Name')?></option>
                                                <?php if (Configure::read('statistics.have_code_rate')): ?>
                                                    <option value="egress_code" <?php echo $common->set_get_select_mul('group_select', 'egress_code', 3); ?>><?php __('egress Code')?></option>
                                                <?php endif; ?><?php endif; ?>
                                            <!--                    <option value="origination_destination_host_name" <?php echo $common->set_get_select_mul('group_select', 'origination_destination_host_name', 3); ?>>Orig Server</option>
    <option value="termination_source_host_name" <?php echo $common->set_get_select_mul('group_select', 'termination_source_host_name', 3); ?>>Term Server</option>-->
                                        </select>
                                    </td>
                                    <td><?php __('Group By')?> #5:</td>
                                    <td>
                                        <select name="group_select[]" style="width:140px;">
                                            <option value="" <?php echo $common->set_get_select_mul('group_select', '', 4, TRUE); ?>></option>

                                            <?php if (Configure::read('statistics.group_all')): ?>
                                                <option value="ingress_country" <?php echo $common->set_get_select_mul('group_select', 'ingress_country', 4); ?>><?php __('ingress Country')?></option>
                                                <option value="ingress_code_name" <?php echo $common->set_get_select_mul('group_select', 'ingress_code_name', 4); ?>><?php __('ingress Code Name')?></option>
                                                <?php if (Configure::read('statistics.have_code_rate')): ?>
                                                    <option value="ingress_code" <?php echo $common->set_get_select_mul('group_select', 'ingress_code', 4); ?>><?php __('ingress Code')?></option>
                                                <?php endif; ?><?php endif; ?>
                                            <option value="egress_client_id" <?php echo $common->set_get_select_mul('group_select', 'egress_client_id', 4); ?>><?php __('egress Carrier')?></option>
                                            <option value="egress_id" <?php echo $common->set_get_select_mul('group_select', 'egress_id', 4); ?>><?php __('Egress Trunk')?></option>
                                            <?php if (Configure::read('statistics.group_all')): ?>
                                                <option value="egress_country" <?php echo $common->set_get_select_mul('group_select', 'egress_country', 4); ?>><?php __('egress Country')?></option>
                                                <option value="egress_code_name" <?php echo $common->set_get_select_mul('group_select', 'egress_code_name', 4); ?>><?php __('egress Code Name')?></option>
                                                <?php if (Configure::read('statistics.have_code_rate')): ?>
                                                    <option value="egress_code" <?php echo $common->set_get_select_mul('group_select', 'egress_code', 4); ?>><?php __('egress Code')?></option>
                                                <?php endif; ?><?php endif; ?>
                                            <!--                    <option value="origination_destination_host_name" <?php echo $common->set_get_select_mul('group_select', 'origination_destination_host_name', 4); ?>>Orig Server</option>
    <option value="termination_source_host_name" <?php echo $common->set_get_select_mul('group_select', 'termination_source_host_name', 4); ?>>Term Server</option>-->
                                        </select>
                                    </td>
                                    <td><?php __('Group By')?> #6:</td>
                                    <td>
                                        <select name="group_select[]" style="width:140px;">
                                            <option value="" <?php echo $common->set_get_select_mul('group_select', '', 5, TRUE); ?>></option>

                                            <?php if (Configure::read('statistics.group_all')): ?>
                                                <option value="ingress_country" <?php echo $common->set_get_select_mul('group_select', 'ingress_country', 5); ?>><?php __('ingress Country')?></option>
                                                <option value="ingress_code_name" <?php echo $common->set_get_select_mul('group_select', 'ingress_code_name', 5); ?>><?php __('ingress Code Name')?></option>
                                                <?php if (Configure::read('statistics.have_code_rate')): ?>
                                                    <option value="ingress_code" <?php echo $common->set_get_select_mul('group_select', 'ingress_code', 5); ?>><?php __('ingress Code')?></option>
                                                <?php endif; ?>    <?php endif; ?>
                                            <option value="egress_client_id" <?php echo $common->set_get_select_mul('group_select', 'egress_client_id', 5); ?>><?php __('egress Carrier')?></option>
                                            <option value="egress_id" <?php echo $common->set_get_select_mul('group_select', 'egress_id', 5); ?>><?php __('Egress Trunk')?></option>
                                            <?php if (Configure::read('statistics.group_all')): ?>
                                                <option value="egress_country" <?php echo $common->set_get_select_mul('group_select', 'egress_country', 5); ?>><?php __('egress Country')?></option>
                                                <option value="egress_code_name" <?php echo $common->set_get_select_mul('group_select', 'egress_code_name', 5); ?>><?php __('egress Code Name')?></option>
                                                <?php if (Configure::read('statistics.have_code_rate')): ?>
                                                    <option value="egress_code" <?php echo $common->set_get_select_mul('group_select', 'egress_code', 5); ?>><?php __('egress Code')?></option>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                            <!--                    <option value="origination_destination_host_name" <?php echo $common->set_get_select_mul('group_select', 'origination_destination_host_name', 5); ?>>Orig Server</option>
    <option value="termination_source_host_name" <?php echo $common->set_get_select_mul('group_select', 'termination_source_host_name', 5); ?>>Term Server</option>-->
                                        </select>
                                    </td>
                                </tr>
                            </table>
                        <?php } ?>
                    </div>
                </fieldset>

                <?php echo $this->element('search_report/search_js_show'); ?>
            </div>
        </div>

        <script type="text/javascript">

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
            });

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