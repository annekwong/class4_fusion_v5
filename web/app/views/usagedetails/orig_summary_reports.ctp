<!--导入所有reoprt页面的input和select样式文件-->
<?php echo $this->element('magic_css'); ?>

<ul class="breadcrumb">
    <li><?php echo __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Statistics') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Usage Detail Report') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Orig Report') ?></li>
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
                <li class='active'><a href="<?php echo $this->webroot ?>usagedetails/orig_summary_reports"  class="glyphicons left_arrow"><i></i><?php __('Origination')?></a></li>
                <li><a href="<?php echo $this->webroot ?>usagedetails/term_summary_reports"  class="glyphicons right_arrow"><i></i><?php __('Termination')?></a>  </li>
                <li><a href="<?php echo $this->webroot ?>usagedetails/daily_orig_summary"  class="glyphicons left_arrow"><i></i><?php __('Daily Origination')?></a></li>
                <li><a href="<?php echo $this->webroot ?>usagedetails/daily_term_summary"  class="glyphicons right_arrow"><i></i><?php __('Daily Termination')?></a>  </li>
            </ul>
        </div>
        <div class="widget-body">


            <?php if ($show_nodata): ?>
                <?php echo $this->element('report/real_period') ?>
            <?php endif; ?>

            <!-- ****************************************普通输出******************************************* -->
            <div class="rows" style="overflow-x:auto;">
                <?php if (empty($data)): ?>
                    <?php if ($show_nodata): ?>
                        <h2 class="msg center"><?php  echo __('no_data_found') ?></h2>
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

                    <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">

                        <thead>
                            <tr>
                                <th><?php __('Clinet Name') ?></th>
                                <?php
                                $count = 0;
                                foreach ($group_select_arr as $group_item)
                                {

                                    if (isset($replace_fields[$group_item]) && strcmp('ingress_client_id', $group_item))
                                    {
                                        $count++;
                                        ?>
                                        <th><?php __($replace_fields[$group_item]) ?></th>
                                    <?php
                                    }
                                }
                                ?>
                                <th><?php __('Not Zero Calls') ?></th>
                                <th><?php __('Total(Min)') ?></th>
                                <th colspan="2">Calls < 30s</th>
                                <th colspan="2">Calls < 6s</th>
                                    <?php foreach ($days as $item): ?>
                                    <th colspan="3">
                                    <?php echo $item; ?>
                                    </th>
    <?php endforeach; ?>
                            </tr>
                            <tr>
                                <th></th>
                                <?php for($j = $count; $j>0; $j--){ ?>
                                <th></th>
                                <?php } ?>
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
    <?php } ?>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
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
                                $calls_3_total += $item['calls_30'];
                                //$time_3_total += $item['time_30'];
                                //$time_6_total += $item['time_6'];
                                $calls_6_total += $item['calls_6'];
                                ?>
                                <tr>
                                    <td><?php echo $item['client_name']; ?></td>
                                    <?php foreach ($group_select_arr as $group_item)
                                {

                                    if (isset($replace_fields[$group_item]) && strcmp('ingress_client_id', $group_item))
                                    {
                                        $count++;
                                        ?>
                                        <td><?php echo $item[$group_item]; ?></td>
                                    <?php
                                    }
                                }
                                ?>
                                    <td><?php echo $item['not_zero_calls']; ?></td>
                                    <td><?php echo number_format($item['total_time'] / 60, 2); ?></td>
                                    <td><?php echo $item['calls_30']; ?></td>
                                    <td><?php echo $item['not_zero_calls'] == 0 ? 0 : number_format($item['calls_30'] / $item['not_zero_calls'] * 100, 2); ?></td>
                    <!--                <td><?php echo number_format($item['time_30'] / 60, 2); ?></td>-->
                                    <td><?php echo $item['calls_6']; ?></td>
                                    <td><?php echo $item['calls_6'] == 0 ? 0 : number_format($item['calls_6'] / $item['not_zero_calls'] * 100, 2); ?></td>
                    <!--                <td><?php echo number_format($item['time_6'] / 60, 2); ?></td>-->
                                    <?php foreach ($days as $day_item): ?>
                                        <?php
                                        if (array_key_exists($day_item, $item['years'])):
                                            ?>
                                            <td><?php echo number_format($item['years'][$day_item]['bill_time'] / 60, 2); ?></td>
                                            <td><?php echo $item['years'][$day_item]['total_calls'] == 0 ? 0 : number_format($item['years'][$day_item]['not_zero_calls'] / $item['years'][$day_item]['total_calls'] * 100, 2); ?></td>
                                            <td><?php echo $item['years'][$day_item]['not_zero_calls'] == 0 ? 0 : number_format($item['years'][$day_item]['total_time'] / $item['years'][$day_item]['not_zero_calls'] / 60, 5); ?></td>
                                        <?php else: ?>
                                            <td>0</td><td>0</td><td>0</td>
                                    <?php endif ?>
                                <?php endforeach; ?>
                                </tr>
                                <?php
                            endforeach;
                            ?>


                        </tbody>



                    </table>

<?php endif; ?>
            </div>

            <fieldset class="query-box" style=" clear:both;overflow:hidden;margin-top:10px;">

<?php echo $this->element('search_report/search_js'); ?><?php echo $form->create('Cdr', array('type' => 'get', 'url' => '/usagedetails/orig_summary_reports/', 'onsubmit' => "if ($('#query-output').val() == 'web') loading();")); ?>  <?php echo $this->element('search_report/search_hide_input'); ?>

                <table class="form" style="width:100%">
                    <tbody>
                        <?php echo $this->element('report/form_period', array('group_time' => true, 'gettype' => '<select id="query-output"  name="show_type" class="input in-select">
            <option value="0">Web</option>
            <option value="1">CSV</option>
            <option value="2">XLS</option>
          </select>')) ?>
                        <!--
                        <tr>
                          <td class="label"><?php echo __('asr', true); ?>:</td>
                          <td class="value">
                              <input type="text" id="query-asr_from"
                                      class="in-digits input in-text" style="width: 65px;" value="<?php echo!empty($_GET['query']['asr_from']) ? $_GET['query']['asr_from'] : ''; ?>"
                                      name="query[asr_from]">
                                &mdash;
                                <input type="text"
                                      id="query-asr_to" class="in-digits input in-text"
                                      style="width: 65px;" value="<?php echo!empty($_GET['query']['asr_to']) ? $_GET['query']['asr_to'] : ''; ?>" name="query[asr_to]">
                               &nbsp;(%)&nbsp;       
<?php echo $this->element('search_report/ss_clear_input_select'); ?>
                          </td>
                           
                           <td class="label"><?php echo __('acd', true); ?>:</td>
                          <td class="value">
                              <input type="text" id="query-acd_from"
                                      class="in-digits input in-text" style="width: 65px;" value="<?php echo!empty($_GET['query']['acd_from']) ? $_GET['query']['acd_from'] : ''; ?>"
                                      name="query[acd_from]">
                                &mdash;
                                <input type="text"
                                      id="query-acd_to" class="in-digits input in-text"
                                      style="width: 65px;" value="<?php echo!empty($_GET['query']['acd_to']) ? $_GET['query']['acd_to'] : ''; ?>" name="query[acd_to]">
                                &nbsp;(s)&nbsp;
<?php echo $this->element('search_report/ss_clear_input_select'); ?>
                          </td>      
                                  
                          <td class="label" align="right"></td>
                          <td class="value" align="left"></td>
                  
                          
                          </tr>
                        -->
<!--                        <tr class="period-block">
                            <td colspan="2" style="text-align:center;"><b><?php echo __('Inbound', true); ?></b></td>
                            <td>&nbsp;</td>
                            <td colspan="2" style="text-align:center;"><b><?php echo __('Outbound', true); ?></b></td>
                            <td>&nbsp;</td>
                            <td colspan="2">&nbsp;</td>
                        </tr>-->
                        <tr>
                            <td><?php __('Carriers')?>:</td>
                            <td>
                                <select class="client_options_ingress" name="ingress_client_id">
                                    <option></option>
                                    <?php foreach ($ingress_clients as $ingress_client): ?>
                                        <option value="<?php echo $ingress_client[0]['client_id'] ?>" <?php echo $common->set_get_select('ingress_client_id', $ingress_client[0]['client_id']) ?>><?php echo $ingress_client[0]['name'] ?></option>
                                <?php endforeach; ?>
                                </select>
<?php echo $this->element('search_report/ss_clear_input_select'); ?>
                            </td>
                            <td>&nbsp;</td>
<!--                            <td>Carriers:</td>
                            <td>
                                <select class="client_options_egress" name="egress_client_id">
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
                            </td>-->
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
<!--                            <td>Egress Trunk:</td>
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
                            <td rowspan="4">Group By:</td>
                            <td rowspan="4" colspan="2">
                                
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
                                
                            </td>-->
                        </tr>


                        <tr>

                            <td><?php __('Tech Prefix')?></td>
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
                            <td>&nbsp;</td>


                        </tr>
                        <tr>
                            <td><?php __('Country')?>:</td>
                            <td>
                                <input type="text" name="orig_country" value="<?php echo $common->set_get_value('orig_country') ?>" />
<?php echo $this->element('search_report/ss_clear_input_select'); ?>
                            </td>
                            <td>&nbsp;</td>
<!--                            <td>Country:</td>
                            <td>
                                <input type="text"  name="term_country" value="<?php echo $common->set_get_value('term_country') ?>" />
<?php echo $this->element('search_report/ss_clear_input_select'); ?>
                            </td>-->
                        </tr>
                        <tr>
                            <td><?php __('Code Name')?>:</td>
                            <td>
                                <input type="text" name="orig_code_name" value="<?php echo $common->set_get_value('orig_code_name') ?>" />
<?php echo $this->element('search_report/ss_clear_input_select'); ?>
                            </td>
                            <td>&nbsp;</td>
<!--                            <td>Code Name:</td>
                            <td>
                                <input type="text" name="term_code_name" value="<?php echo $common->set_get_value('term_code_name') ?>" />
<?php echo $this->element('search_report/ss_clear_input_select'); ?>
                            </td>-->
                        </tr>
                        <tr>
                            <td><?php __('Code')?>:</td>
                            <td>
                                <input type="text" name="orig_code" value="<?php echo $common->set_get_value('orig_code') ?>" />
<?php echo $this->element('search_report/ss_clear_input_select'); ?>
                            </td>
                            <td>&nbsp;</td>
<!--                            <td>Code:</td>
                            <td>
                                <input type="text" name="term_code" value="<?php echo $common->set_get_value('term_code') ?>" />
<?php echo $this->element('search_report/ss_clear_input_select'); ?>
                            </td>
                            <td>&nbsp;</td>-->
                        </tr>
    <!--                    <tr>
                            <td>Show Inter/Intra Cost</td>
                            <td>
                                <input type="checkbox" name="show_inter_intra" <?php if (isset($_GET['show_inter_intra'])) echo 'checked="checked"'; ?> />
                            </td>
                            <td>&nbsp;</td>
                            <td></td>
                            <td></td>
                            <td>&nbsp;</td>
                        </tr>-->
                        <tr>
                            <td><?php __('Rate Type')?></td>
                            <td>
                                <select name="orig_rate_type">
                                    <option value="0" <?php echo $common->set_get_select('orig_rate_type', 0); ?>><?php __('All')?></option>
                                    <option value="1" <?php echo $common->set_get_select('orig_rate_type', 1); ?>><?php __('INTER')?></option>
                                    <option value="2" <?php echo $common->set_get_select('orig_rate_type', 2); ?>><?php __('INTRA')?></option>
                                    <option value="3" <?php echo $common->set_get_select('orig_rate_type', 3); ?>><?php __('OTHER')?></option>
                                    <option value="4" <?php echo $common->set_get_select('orig_rate_type', 4); ?>><?php __('ERROR')?></option>
                                    <option value="5" <?php echo $common->set_get_select('orig_rate_type', 5); ?>><?php __('LOCAL')?></option>
                                </select>
<?php echo $this->element('search_report/ss_clear_input_select'); ?>
                            </td>
                            <td>&nbsp;</td>
<!--                            <td>Rate Type</td>
                            <td>
                                <select name="term_rate_type">
                                    <option value="0" <?php echo $common->set_get_select('term_rate_type', 0); ?>>All</option>
                                    <option value="1" <?php echo $common->set_get_select('term_rate_type', 1); ?>>INTER</option>
                                    <option value="2" <?php echo $common->set_get_select('term_rate_type', 2); ?>>INTRA</option>
                                    <option value="3" <?php echo $common->set_get_select('term_rate_type', 3); ?>>OTHER</option>
                                    <option value="4" <?php echo $common->set_get_select('term_rate_type', 4); ?>>ERROR</option>
                                    <option value="5" <?php echo $common->set_get_select('term_rate_type', 5); ?>>LOCAL</option>
                                </select>
<?php echo $this->element('search_report/ss_clear_input_select'); ?>
                            </td>
                            <td>&nbsp;</td>-->
                        </tr>
                        <tr>
                            <td><?php __('Rate Table')?></td>
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
                            <td>&nbsp;</td>
<!--                            <td>Switch Server</td>
                            <td>
                            <?php if (count($servers) > 1): ?>
                                <?php echo $form->input('egress_profile_ip', array('options' => $servers, 'style' => 'width:120px;', 'selected' => isset($_GET['egress_profile_ip']) ? $_GET['egress_profile_ip'] : "", 'empty' => '', 'label' => false, 'div' => false, 'type' => 'select')); ?>
    <?php echo $this->element('search_report/ss_clear_input_select'); ?>
<?php endif; ?>
                            </td>
                            <td>&nbsp;</td>

                            <td></td>
                            <td>
                            </td>-->
                        </tr>

                        <tr>
                            <td><?php __('Jurisdiction Type')?></td>
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
                            <td>&nbsp;</td>
<!--                            <td>Jurisdiction Type</td>
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
                            <td>&nbsp;</td>-->
                        </tr>

                        <tr>
                            <td><?php __('Routing Plan')?>:</td>
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
                            <td>&nbsp;</td>

                            <td></td>
                            <td>
                            </td>
                            <td>&nbsp;</td>
                            <td></td>
                            <td></td>
                            <td>&nbsp;</td>

                            <td>
                            </td>
                            <td>&nbsp;</td>

                            <td></td>
                            <td>
                            </td>
                        </tr>
                        <tr>
                            <td><?php __('Switch Server')?></td>
                            <td>
                                <?php if (count($servers) > 1): ?>
                                    <?php echo $form->input('ingress_profile_ip', array('options' => $servers, 'style' => 'width:120px;', 'empty' => '', 'label' => false, 'div' => false, 'type' => 'select', 'selected' => isset($_GET['egress_profile_ip']) ? $_GET['egress_profile_ip'] : "")); ?>
    <?php echo $this->element('search_report/ss_clear_input_select'); ?>
<?php endif; ?>
                            </td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>&nbsp;</td>

                            <td></td>
                            <td>
                            </td>
                        </tr>
                    </tbody>
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
                                <?php endif; ?>
                                <option value="egress_client_id" <?php echo $common->set_get_select_mul('group_select', 'egress_client_id', 0); ?>><?php __('egress Carrier')?></option>
                                <option value="egress_id" <?php echo $common->set_get_select_mul('group_select', 'egress_id', 0); ?>><?php __('egress Trunk')?></option>
<?php if (Configure::read('statistics.group_all')): ?>
                                    <option value="egress_country" <?php echo $common->set_get_select_mul('group_select', 'egress_country', 0); ?>><?php __('egress Country')?></option>
                                    <option value="egress_code_name" <?php echo $common->set_get_select_mul('group_select', 'egress_code_name', 0); ?>><?php __('egress Code Name')?></option>
                                    <option value="egress_code" <?php echo $common->set_get_select_mul('group_select', 'egress_code', 0); ?>><?php __('egress Code')?></option>
<?php endif; ?>
<!--                    <option value="origination_destination_host_name" <?php echo $common->set_get_select_mul('group_select', 'origination_destination_host_name', 0); ?>><?php __('Orig Server')?></option>
<option value="termination_source_host_name" <?php echo $common->set_get_select_mul('group_select', 'termination_source_host_name', 0); ?>><?php __('Term Server')?></option>-->
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
                                <?php endif; ?>
                                <option value="egress_client_id" <?php echo $common->set_get_select_mul('group_select', 'egress_client_id', 1); ?>><?php __('egress Carrier')?></option>
                                <option value="egress_id" <?php echo $common->set_get_select_mul('group_select', 'egress_id', 1); ?>><?php __('Egress Trunk')?></option>
<?php if (Configure::read('statistics.group_all')): ?>
                                    <option value="egress_country" <?php echo $common->set_get_select_mul('group_select', 'egress_country', 1); ?>><?php __('egress Country')?></option>
                                    <option value="egress_code_name" <?php echo $common->set_get_select_mul('group_select', 'egress_code_name', 1); ?>><?php __('egress Code Name')?></option>
                                    <option value="egress_code" <?php echo $common->set_get_select_mul('group_select', 'egress_code', 1); ?>><?php __('egress Code')?></option>
<?php endif; ?>
<!--                    <option value="origination_destination_host_name" <?php echo $common->set_get_select_mul('group_select', 'origination_destination_host_name', 1); ?>><?php __('Orig Server')?></option>
<option value="termination_source_host_name" <?php echo $common->set_get_select_mul('group_select', 'termination_source_host_name', 1); ?>><?php __('Term Server')?></option>-->
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
                                <?php endif; ?>
                                <option value="egress_client_id" <?php echo $common->set_get_select_mul('group_select', 'egress_client_id', 2); ?>><?php __('egress Carrier')?></option>
                                <option value="egress_id" <?php echo $common->set_get_select_mul('group_select', 'egress_id', 2); ?>><?php __('egress Trunk')?></option>
<?php if (Configure::read('statistics.group_all')): ?>
                                    <option value="egress_country" <?php echo $common->set_get_select_mul('group_select', 'egress_country', 2); ?>><?php __('egress Country')?></option>
                                    <option value="egress_code_name" <?php echo $common->set_get_select_mul('group_select', 'egress_code_name', 2); ?>><?php __('egress Code Name')?></option>
                                    <option value="egress_code" <?php echo $common->set_get_select_mul('group_select', 'egress_code', 2); ?>><?php __('egress Code')?></option>
<?php endif; ?>
<!--                    <option value="origination_destination_host_name" <?php echo $common->set_get_select_mul('group_select', 'origination_destination_host_name', 2); ?>><?php __('Orig Server')?></option>
<option value="termination_source_host_name" <?php echo $common->set_get_select_mul('group_select', 'termination_source_host_name', 2); ?>><?php __('Term Server')?></option>-->
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
                                <?php endif; ?>
                                <option value="egress_client_id" <?php echo $common->set_get_select_mul('group_select', 'egress_client_id', 3); ?>><?php __('egress Carrier')?></option>
                                <option value="egress_id" <?php echo $common->set_get_select_mul('group_select', 'egress_id', 3); ?>><?php __('egress Trunk')?></option>
<?php if (Configure::read('statistics.group_all')): ?>
                                    <option value="egress_country" <?php echo $common->set_get_select_mul('group_select', 'egress_country', 3); ?>><?php __('egress Country')?></option>
                                    <option value="egress_code_name" <?php echo $common->set_get_select_mul('group_select', 'egress_code_name', 3); ?>><?php __('egress Code Name')?></option>
                                    <option value="egress_code" <?php echo $common->set_get_select_mul('group_select', 'egress_code', 3); ?>><?php __('egress Code')?></option>
<?php endif; ?>
<!--                    <option value="origination_destination_host_name" <?php echo $common->set_get_select_mul('group_select', 'origination_destination_host_name', 3); ?>><?php __('Orig Server')?></option>
<option value="termination_source_host_name" <?php echo $common->set_get_select_mul('group_select', 'termination_source_host_name', 3); ?>><?php __('Term Server')?></option>-->
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
                                <?php endif; ?>
                                <option value="egress_client_id" <?php echo $common->set_get_select_mul('group_select', 'egress_client_id', 4); ?>><?php __('egress Carrier')?></option>
                                <option value="egress_id" <?php echo $common->set_get_select_mul('group_select', 'egress_id', 4); ?>><?php __('egress Trunk')?></option>
<?php if (Configure::read('statistics.group_all')): ?>
                                    <option value="egress_country" <?php echo $common->set_get_select_mul('group_select', 'egress_country', 4); ?>><?php __('egress Country')?></option>
                                    <option value="egress_code_name" <?php echo $common->set_get_select_mul('group_select', 'egress_code_name', 4); ?>><?php __('egress Code Name')?></option>
                                    <option value="egress_code" <?php echo $common->set_get_select_mul('group_select', 'egress_code', 4); ?>><?php __('egress Code')?></option>
<?php endif; ?>
<!--                    <option value="origination_destination_host_name" <?php echo $common->set_get_select_mul('group_select', 'origination_destination_host_name', 4); ?>><?php __('Orig Server')?></option>
<option value="termination_source_host_name" <?php echo $common->set_get_select_mul('group_select', 'termination_source_host_name', 4); ?>><?php __('Term Server')?></option>-->
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
                                <?php endif; ?>
                                <option value="egress_client_id" <?php echo $common->set_get_select_mul('group_select', 'egress_client_id', 5); ?>><?php __('egress Carrier')?></option>
                                <option value="egress_id" <?php echo $common->set_get_select_mul('group_select', 'egress_id', 5); ?>><?php __('egress Trunk')?></option>
<?php if (Configure::read('statistics.group_all')): ?>
                                    <option value="egress_country" <?php echo $common->set_get_select_mul('group_select', 'egress_country', 5); ?>><?php __('egress Country')?></option>
                                    <option value="egress_code_name" <?php echo $common->set_get_select_mul('group_select', 'egress_code_name', 5); ?>><?php __('egress Code Name')?></option>
                                    <option value="egress_code" <?php echo $common->set_get_select_mul('group_select', 'egress_code', 5); ?>><?php __('egress Code')?></option>
<?php endif; ?>
<!--                    <option value="origination_destination_host_name" <?php echo $common->set_get_select_mul('group_select', 'origination_destination_host_name', 5); ?>><?php __('Orig Server')?></option>
<option value="termination_source_host_name" <?php echo $common->set_get_select_mul('group_select', 'termination_source_host_name', 5); ?>><?php __('Term Server')?></option>-->
                            </select>
                        </td>
                    </tr>
                </table>

            </fieldset>

<?php echo $this->element('search_report/search_js_show'); ?> 
        </div>
        <?php if(isset($send) && !empty($send)): ?>
    <div><?php echo $send; ?></div>
    <?php endif; ?>
    </div>

</div>

<script type="text/javascript">
    $(function() {
        $('table.list tbody tr').each(function() {
            var $this = $(this);
            var count = 0;
            $('td:gt(1)', $this).each(function() {
                count += parseFloat($(this).text());
            });
            if (count == 0)
                $this.remove();
        });
    });
</script>

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