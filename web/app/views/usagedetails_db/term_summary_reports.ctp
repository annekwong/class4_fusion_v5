<!--导入所有reoprt页面的input和select样式文件-->
<style>
    #stats-period{display: inline-block}
</style>
<?php echo $this->element('magic_css'); ?>
<ul class="breadcrumb">
    <li><?php echo __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>usagedetails_db/orig_summary_reports">
            <?php __('Statistics') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>usagedetails_db/orig_summary_reports">
            <?php echo __('Usage Detail Report') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>usagedetails_db/term_summary_reports">
            <?php echo __('Term Report') ?></a></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Term Report') ?></h4>
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>

<div id="container">

    <div class="innerLR">

        <div class="widget widget-tabs widget-body-white">

            <div class="widget-head">
                <ul class="tabs">
                    <li><a href="<?php echo $this->webroot ?>usagedetails_db/orig_summary_reports"  class="glyphicons left_arrow"><i></i>Origination</a></li>
                    <li class='active'><a href="<?php echo $this->webroot ?>usagedetails_db/term_summary_reports"  class="glyphicons right_arrow"><i></i>Termination</a>  </li>
                    <li><a href="<?php echo $this->webroot ?>usagedetails_db/daily_orig_summary"  class="glyphicons left_arrow"><i></i>Daily Origination</a></li>
                    <li><a href="<?php echo $this->webroot ?>usagedetails_db/daily_term_summary"  class="glyphicons right_arrow"><i></i>Daily Termination</a>  </li>
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
                            <h2 class="msg center"><?php  echo __('no_data_found') ?></h2><?php endif; ?>
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
                                            echo "<th>" . $replace_fields[$value] . "</th>";
                                        }
                                        else
                                        {
                                            echo "<th>" . $value . "</th>";
                                        }
                                    endforeach;
                                    ?>
                                    <th><?php __('Not Zero Calls') ?></th>
                                    <th><?php __('Total(Min)') ?></th>
                                    <th colspan="2">Calls < 30s</th>
                                    <th colspan="2">Calls < 6s</th>
                                    <?php foreach ($days as $item): ?>
                                        <th colspan="5">
                                            <?php echo $item; ?>
                                        </th>
                                    <?php endforeach; ?>
                                </tr>
                                <tr>
                                    <?php
                                    foreach ($filed_arr as $value):
                                        echo "<th></th>";
                                    endforeach;
                                    ?>
                                    <th></th>
                                    <th></th>
                                    <th><?php __(''); ?></th>
                                    <th><?php __('%'); ?></th>
                                    <th><?php __(''); ?></th>
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
                                                $totalArray[$day_item]['npr_count'] += $item['years'][$day_item]['npr_count'];
                                                $totalArray[$day_item]['total_calls'] += $item['years'][$day_item]['total_calls'];
                                                ?>
                                                <td><?php echo number_format($item['years'][$day_item]['bill_time'] / 60, 2); ?></td>
                                                <td><?php echo $item['years'][$day_item]['total_calls'] == 0 ? 0 : number_format($item['years'][$day_item]['not_zero_calls'] / $item['years'][$day_item]['total_calls'] * 100, 2); ?></td>
                                                <td><?php echo $item['years'][$day_item]['not_zero_calls'] == 0 ? 0 : number_format($item['years'][$day_item]['total_time'] / $item['years'][$day_item]['not_zero_calls'] / 60, 5); ?></td>
                                                <td><?php echo number_format($item['years'][$day_item]['npr_count']); ?></td>
                                                <td><?php echo number_format($item['years'][$day_item]['total_calls'] == 0 ? 0 : $item['years'][$day_item]['npr_count'] / $item['years'][$day_item]['total_calls'] * 100, 2); ?>%</td>

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
                        <?php echo $this->element('search_report/search_js'); ?><?php echo $form->create('Cdr', array('type' => 'get', 'url' => '/usagedetails_db/term_summary_reports/', 'onsubmit' => "if ($('#query-output').val() == 'web') loading();")); ?>  <?php echo $this->element('search_report/search_hide_input'); ?>
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
                                    <td class="align_right padding-r10">Carriers:</td>
                                    <td>
                                        <select class="client_options_egress" name="egress_client_id">
                                            <option></option>
                                            <?php foreach ($egress_clients as $egress_client): ?>
                                                <option value="<?php echo $egress_client[0]['client_id'] ?>" <?php echo $common->set_get_select('egress_client_id', $egress_client[0]['client_id']) ?>><?php echo $egress_client[0]['name'] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <?php echo $this->element('search_report/ss_clear_input_select'); ?>
                                    </td>
                                    <td class="align_right padding-r10">Egress Trunk:</td>
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
                                </tr>


                                <tr>
                                    <td class="align_right padding-r10">Country:</td>
                                    <td>
                                        <input type="text" class="width220"  name="term_country" value="<?php echo $common->set_get_value('term_country') ?>" />
                                        <?php echo $this->element('search_report/ss_clear_input_select'); ?>
                                    </td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td class="align_right padding-r10">Code Name:</td>
                                    <td>
                                        <input type="text" class="width220" name="term_code_name" value="<?php echo $common->set_get_value('term_code_name') ?>" />
                                        <?php echo $this->element('search_report/ss_clear_input_select'); ?>
                                    </td>
                                    <td class="align_right padding-r10">Code:</td>
                                    <td>
                                        <input type="text" class="width220" name="term_code" value="<?php echo $common->set_get_value('term_code') ?>" />
                                        <?php echo $this->element('search_report/ss_clear_input_select'); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="align_right padding-r10">Jurisdiction Type</td>
                                    <td>
                                        <select name="egress_jur_type">
                                            <option value="" <?php echo $common->set_get_select('egress_jur_type', ''); ?>>All</option>
                                            <option value="0" <?php echo $common->set_get_select('egress_jur_type', 0); ?>>A-Z</option>
                                            <option value="1" <?php echo $common->set_get_select('egress_jur_type', 1); ?>>US NON-JD</option>
                                            <option value="2" <?php echo $common->set_get_select('egress_jur_type', 2); ?>>US JD</option>
                                            <option value="3" <?php echo $common->set_get_select('egress_jur_type', 3); ?>>OCN-LATA-JD</option>
                                            <option value="4" <?php echo $common->set_get_select('egress_jur_type', 4); ?>>OCN-LATA-NON-JD</option>
                                        </select>
                                        <?php echo $this->element('search_report/ss_clear_input_select'); ?>
                                    </td>
                                    <td class="align_right padding-r10">Switch Server</td>
                                    <td>
                                        <?php echo $form->input('egress_profile_ip', array('options' => $servers, 'style' => 'width:220px;', 'selected' => isset($_GET['egress_profile_ip']) ? $_GET['egress_profile_ip'] : "", 'empty' => '', 'label' => false, 'div' => false, 'type' => 'select')); ?>
                                        <?php echo $this->element('search_report/ss_clear_input_select'); ?>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                            <?php if ($report_group)
                            {
                                ?>
                                <p class="separator text-center"><i class="icon-table icon-3x"></i></p>
                                <table class="form" style="width:100%">
                                    <tr>
                                        <td>Group By #1:</td>
                                        <td>
                                            <select name="group_select[]" style="width:140px;">

                                                <option value="egress_client_id" <?php echo $common->set_get_select_mul('group_select', 'egress_client_id', 0); ?>>egress Carrier</option>

                                            </select>
                                        </td>
                                        <td>Group By #2:</td>
                                        <td>
                                            <select name="group_select[]" style="width:140px;">

                                                <option value="egress_id" <?php echo $common->set_get_select_mul('group_select', 'egress_id', 1); ?>>Egress Trunk</option>

                                            </select>
                                        </td>
                                        <td>Group By #3:</td>
                                        <td>
                                            <select name="group_select[]" style="width:140px;">
                                                <option value="" <?php echo $common->set_get_select_mul('group_select', '', 2, TRUE); ?>></option>
                                                <option value="ingress_client_id" <?php echo $common->set_get_select_mul('group_select', 'ingress_client_id', 2); ?>>ingress Carrier</option>
                                                <option value="ingress_id" <?php echo $common->set_get_select_mul('group_select', 'ingress_id', 2); ?>>Ingress Trunk</option>
                                                <?php if (Configure::read('statistics.group_all')): ?>
                                                    <option value="ingress_country" <?php echo $common->set_get_select_mul('group_select', 'ingress_country', 2); ?>>ingress Country</option>
                                                    <option value="ingress_code_name" <?php echo $common->set_get_select_mul('group_select', 'ingress_code_name', 2); ?>>ingress Code Name</option>
                                                    <option value="ingress_code" <?php echo $common->set_get_select_mul('group_select', 'ingress_code', 2); ?>>ingress Code</option>
                                                    <option value="ingress_rate" <?php echo $common->set_get_select_mul('group_select', 'ingress_rate', 2); ?>>ingress Rate</option>
                                                <?php endif; ?>

                                                <?php if (Configure::read('statistics.group_all')): ?>
                                                    <option value="egress_country" <?php echo $common->set_get_select_mul('group_select', 'egress_country', 2); ?>>egress Country</option>
                                                    <option value="egress_code_name" <?php echo $common->set_get_select_mul('group_select', 'egress_code_name', 2); ?>>egress Code Name</option>
                                                    <option value="egress_code" <?php echo $common->set_get_select_mul('group_select', 'egress_code', 2); ?>>egress Code</option>
                                                <?php endif; ?>
                                                <!--                    <option value="origination_destination_host_name" <?php echo $common->set_get_select_mul('group_select', 'origination_destination_host_name', 2); ?>>Orig Server</option>
    <option value="termination_source_host_name" <?php echo $common->set_get_select_mul('group_select', 'termination_source_host_name', 2); ?>>Term Server</option>-->
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Group By #4:</td>
                                        <td>
                                            <select name="group_select[]" style="width:140px;">
                                                <option value="" <?php echo $common->set_get_select_mul('group_select', '', 3, TRUE); ?>></option>
                                                <option value="ingress_client_id" <?php echo $common->set_get_select_mul('group_select', 'ingress_client_id', 3); ?>>ingress Carrier</option>
                                                <option value="ingress_id" <?php echo $common->set_get_select_mul('group_select', 'ingress_id', 3); ?>>Ingress Trunk</option>
                                                <?php if (Configure::read('statistics.group_all')): ?>
                                                    <option value="ingress_country" <?php echo $common->set_get_select_mul('group_select', 'ingress_country', 3); ?>>ingress Country</option>
                                                    <option value="ingress_code_name" <?php echo $common->set_get_select_mul('group_select', 'ingress_code_name', 3); ?>>ingress Code Name</option>
                                                    <option value="ingress_code" <?php echo $common->set_get_select_mul('group_select', 'ingress_code', 3); ?>>ingress Code</option>
                                                    <option value="ingress_rate" <?php echo $common->set_get_select_mul('group_select', 'ingress_rate', 3); ?>>ingress Rate</option>
                                                <?php endif; ?>

                                                <?php if (Configure::read('statistics.group_all')): ?>
                                                    <option value="egress_country" <?php echo $common->set_get_select_mul('group_select', 'egress_country', 3); ?>>egress Country</option>
                                                    <option value="egress_code_name" <?php echo $common->set_get_select_mul('group_select', 'egress_code_name', 3); ?>>egress Code Name</option>
                                                    <option value="egress_code" <?php echo $common->set_get_select_mul('group_select', 'egress_code', 3); ?>>egress Code</option>
                                                <?php endif; ?>
                                                <!--                    <option value="origination_destination_host_name" <?php echo $common->set_get_select_mul('group_select', 'origination_destination_host_name', 3); ?>>Orig Server</option>
    <option value="termination_source_host_name" <?php echo $common->set_get_select_mul('group_select', 'termination_source_host_name', 3); ?>>Term Server</option>-->
                                            </select>
                                        </td>
                                        <td>Group By #5:</td>
                                        <td>
                                            <select name="group_select[]" style="width:140px;">
                                                <option value="" <?php echo $common->set_get_select_mul('group_select', '', 4, TRUE); ?>></option>
                                                <option value="ingress_client_id" <?php echo $common->set_get_select_mul('group_select', 'ingress_client_id', 4); ?>>ingress Carrier</option>
                                                <option value="ingress_id" <?php echo $common->set_get_select_mul('group_select', 'ingress_id', 4); ?>>Ingress Trunk</option>
                                                <?php if (Configure::read('statistics.group_all')): ?>
                                                    <option value="ingress_country" <?php echo $common->set_get_select_mul('group_select', 'ingress_country', 4); ?>>ingress Country</option>
                                                    <option value="ingress_code_name" <?php echo $common->set_get_select_mul('group_select', 'ingress_code_name', 4); ?>>ingress Code Name</option>
                                                    <option value="ingress_code" <?php echo $common->set_get_select_mul('group_select', 'ingress_code', 4); ?>>ingress Code</option>
                                                    <option value="ingress_rate" <?php echo $common->set_get_select_mul('group_select', 'ingress_rate', 4); ?>>ingress Rate</option>
                                                <?php endif; ?>

                                                <?php if (Configure::read('statistics.group_all')): ?>
                                                    <option value="egress_country" <?php echo $common->set_get_select_mul('group_select', 'egress_country', 4); ?>>egress Country</option>
                                                    <option value="egress_code_name" <?php echo $common->set_get_select_mul('group_select', 'egress_code_name', 4); ?>>egress Code Name</option>
                                                    <option value="egress_code" <?php echo $common->set_get_select_mul('group_select', 'egress_code', 4); ?>>egress Code</option>
                                                <?php endif; ?>
                                                <!--                    <option value="origination_destination_host_name" <?php echo $common->set_get_select_mul('group_select', 'origination_destination_host_name', 4); ?>>Orig Server</option>
    <option value="termination_source_host_name" <?php echo $common->set_get_select_mul('group_select', 'termination_source_host_name', 4); ?>>Term Server</option>-->
                                            </select>
                                        </td>
                                        <td>Group By #6:</td>
                                        <td>
                                            <select name="group_select[]" style="width:140px;">
                                                <option value="" <?php echo $common->set_get_select_mul('group_select', '', 5, TRUE); ?>></option>
                                                <option value="ingress_client_id" <?php echo $common->set_get_select_mul('group_select', 'ingress_client_id', 5); ?>>ingress Carrier</option>
                                                <option value="ingress_id" <?php echo $common->set_get_select_mul('group_select', 'ingress_id', 5); ?>>Ingress Trunk</option>
                                                <?php if (Configure::read('statistics.group_all')): ?>
                                                    <option value="ingress_country" <?php echo $common->set_get_select_mul('group_select', 'ingress_country', 5); ?>>ingress Country</option>
                                                    <option value="ingress_code_name" <?php echo $common->set_get_select_mul('group_select', 'ingress_code_name', 5); ?>>ingress Code Name</option>
                                                    <option value="ingress_code" <?php echo $common->set_get_select_mul('group_select', 'ingress_code', 5); ?>>ingress Code</option>
                                                    <option value="ingress_rate" <?php echo $common->set_get_select_mul('group_select', 'ingress_rate', 5); ?>>ingress Rate</option>
                                                <?php endif; ?>

                                                <?php if (Configure::read('statistics.group_all')): ?>
                                                    <option value="egress_country" <?php echo $common->set_get_select_mul('group_select', 'egress_country', 5); ?>>egress Country</option>
                                                    <option value="egress_code_name" <?php echo $common->set_get_select_mul('group_select', 'egress_code_name', 5); ?>>egress Code Name</option>
                                                    <option value="egress_code" <?php echo $common->set_get_select_mul('group_select', 'egress_code', 5); ?>>egress Code</option>
                                                <?php endif; ?>
                                                <!--                    <option value="origination_destination_host_name" <?php echo $common->set_get_select_mul('group_select', 'origination_destination_host_name', 5); ?>>Orig Server</option>
    <option value="termination_source_host_name" <?php echo $common->set_get_select_mul('group_select', 'termination_source_host_name', 5); ?>>Term Server</option>-->
                                            </select>
                                        </td>
                                    </tr>
                                </table>
                            <?php  } ?>
                        </div>
                    </fieldset>

                    <?php echo $this->element('search_report/search_js_show'); ?>
                </div>
            </div>

            <script type="text/javascript">
                $(function() {



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
            </script>