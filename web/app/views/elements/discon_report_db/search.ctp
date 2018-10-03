
<fieldset class="query-box" style=" clear:both;overflow:hidden;margin-top:10px;">
    <h4 class="heading glyphicons search" style="display: inline-block;"><i></i> <?php __('Search') ?></h4>
    <div class="pull-right" title="Advance">
        <a id="advance_btn" class="btn" href="javascript:void(0)">
            <i class="icon-long-arrow-down"></i> 
        </a>
    </div>
    <div class="clearfix"></div>
    <?php echo $this->element('search_report/search_js'); ?>
    <?php
    $pass_1 = isset($this->params['pass'][1]) ? 1 : "";
    if ($rate_type == 'org')
    {
        $url = '/disconnectreports_db/summary_reports/org/' . $pass_1;
    }
    else
    {
        $url = '/disconnectreports_db/summary_reports/term/' . $pass_1;
    }
    echo $form->create('Cdr', array('type' => 'get', 'url' => $url, 'class' => 'scheduled_report_form'));
    ?>
    <?php echo $this->element('search_report/search_hide_input'); ?>
    <table  class="form" width="100%">
        <tr class="period-block" >
            <td colspan="8" style="width:auto;">
                <table class="in-date">
                    <tbody>
                        <tr>
                            <td class="align_right padding-r10"><?php __('time') ?></td>
                            <td style="padding-right: 15px;"><?php
                                $r = array('custom' => __('custom', true), 'curDay' => __('today', true), 'prevDay' => __('yesterday', true), 'curWeek' => __('currentweek', true), 'prevWeek' => __('previousweek', true), 'curMonth' => __('currentmonth', true),
                                    'prevMonth' => __('previousmonth', true), 'curYear' => __('currentyear', true), 'prevYear' => __('previousyear', true));
                                if (!empty($_GET))
                                {
                                    if (isset($_GET['smartPeriod']))
                                    {
                                        $s = $_GET['smartPeriod'];
                                    }
                                    else
                                    {
                                        $s = 'curDay';
                                    }
                                }
                                else
                                {
                                    $s = 'curDay';
                                }
                                echo $form->input('smartPeriod', array('options' => $r, 'label' => false,
                                    'onchange' => 'setPeriod(this.value)', 'id' => 'query-smartPeriod', 'style' => 'width:100px;', 'name' => 'smartPeriod',
                                    'div' => false, 'type' => 'select', 'selected' => $s));
                                ?></td>
                            <td ><input  type="text" style="width: 80px;"  id="query-start_date-wDt" class="wdate in-text input" onChange="setPeriod('custom')"   readonly="readonly"  onkeydown="setPeriod('custom')" value="" name="start_date"  ></td>
                            <td><input type="text" id="query-start_time-wDt" onChange="setPeriod('custom')" onKeyDown="setPeriod('custom')"	readonly="readonly" style="width: 80px;" value="00:00:00" name="start_time" class="input in-text">
                                &nbsp;&nbsp;&mdash;&nbsp;&nbsp;
                                <input type="text" id="query-stop_date-wDt" class="wdate in-text input"  style="width: 80px;"    onchange="setPeriod('custom')"
                                       readonly="readonly" 
                                       onkeydown="setPeriod('custom')" value="" name="stop_date"></td>
                            <td><input type="text" id="query-stop_time-wDt" onChange="setPeriod('custom')"
                                       readonly="readonly" 
                                       onkeydown="setPeriod('custom')" style="width: 80px;" value="23:59:59" name="stop_time" class="input in-text">
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;in&nbsp;&nbsp;&nbsp;&nbsp;
                                <select id="query-tz" style="width: 120px;" name="query[tz]" class="input in-select">
                                    <option value="-1200">GMT -12:00</option>
                                    <option value="-1100">GMT -11:00</option>
                                    <option value="-1000">GMT -10:00</option>
                                    <option value="-0900">GMT -09:00</option>
                                    <option value="-0800">GMT -08:00</option>
                                    <option value="-0700">GMT -07:00</option>
                                    <option value="-0600">GMT -06:00</option>
                                    <option value="-0500">GMT -05:00</option>
                                    <option value="-0400">GMT -04:00</option>
                                    <option value="-0300">GMT -03:00</option>
                                    <option value="-0200">GMT -02:00</option>
                                    <option value="-0100">GMT -01:00</option>
                                    <option value="+0000">GMT +00:00</option>
                                    <option value="+0100">GMT +01:00</option>
                                    <option selected="selected" value="+0200">GMT +02:00</option>
                                    <option value="+0300">GMT +03:00</option>
                                    <option value="+0330">GMT +03:30</option>
                                    <option value="+0400">GMT +04:00</option>
                                    <option value="+0500">GMT +05:00</option>
                                    <option value="+0600">GMT +06:00</option>
                                    <option value="+0700">GMT +07:00</option>
                                    <option value="+0800">GMT +08:00</option>
                                    <option value="+0900">GMT +09:00</option>
                                    <option value="+1000">GMT +10:00</option>
                                    <option value="+1100">GMT +11:00</option>
                                    <option value="+1200">GMT +12:00</option>
                                </select></td>
                            <?php if (!$pass_1): ?>
                                <td><?php
                                    $r = array('' => __('alltime', true), 'YYYY-MM-DD  HH24:00:00' => __('byhours', true), 'YYYY-MM-DD' => __('byday', true), 'YYYY-MM' => __('bymonth', true), 'YYYY' => __('byyear', true));
                                    if (!empty($_GET))
                                    {
                                        if (isset($_GET['group_by_date']))
                                        {
                                            $s = $_GET['group_by_date'];
                                        }
                                        else
                                        {
                                            $s = '';
                                        }
                                    }
                                    else
                                    {

                                        $s = '';
                                    }
                                    echo $form->input('group_by_date', array('options' => $r, 'label' => false, 'id' => 'query-group_by_date', 'style' => 'width: 120px;', 'name' => 'group_by_date',
                                        'div' => false, 'type' => 'select', 'selected' => $s));
                                    ?></td>
                            <?php endif; ?>
                            <td>

                                <select id="query-output" onChange="repaintOutput();" name="query[output]" class="input in-select">
                                    <option selected="selected" value="web">
                                        <?php __('web') ?>
                                    </option>
                                    <?php
                                    if ($_SESSION['role_menu']['Statistics']['disconnectreports_db']['model_x'])
                                    {
                                        ?>
                                        <option value="csv"><?php __('Excel CSV') ?></option>
                                        <option value="xls"><?php __('Excel XLS') ?></option>
                                    <?php } ?>
                                    <!--<option value="delayed">Delayed CSV</option>
                            --->
                                </select>
                                <input type="submit" value="<?php echo __('query', true); ?>" class="btn btn-primary margin-bottom10">
                            </td>

                            <?php
                            if (!$pass_1)
//                                echo $this->element('scheduled_report/index');
                            ?>

                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
    </table>
    <div id="advance_panel" class="widget widget-heading-simple widget-body-gray">
        <table class="form" style="width:100%">
            <?php
            if ($rate_type == 'org')
            {
                ?>
                <tr class="period-block" style="height:20px; line-height:20px;">
                    <td colspan="2" style="text-align:center; font-size:14px;"><b><?php echo __('Inbound', true); ?></b></td>
                    <td class="in-out_bound">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                    <td>&nbsp;</td>
                    <td class="value"></td>
                    <td style="width:3px;">&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>

                <tr> 


                    <?php echo $this->element('search_report/orig_carrier_select'); ?>
                    <td class="in-out_bound">&nbsp;</td>
                    <td class="align_right padding-r10"> <?php echo __('class4-server', true); ?></td>
                    <td><?php
                        echo $form->input('server_ip', array('options' => $server, 'empty' => '   ', 'label' => false, 'div' => false, 'type' => 'select'));
                        ?>
                        <?php echo $this->element('search_report/ss_clear_input_select'); ?></td>
                    <td style="width:3px;">&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>

                    <td class="align_right padding-r10"><?php __('ingress') ?></td>
                    <td><?php
                        echo $form->input('ingress_alias', array('options' => $ingress, 'label' => false, 'div' => false, 'type' => 'select', 'onchange' => 'getTechPrefix(this);'));
                        ?>
                        <?php echo $this->element('search_report/ss_clear_input_select'); ?></td>
                    <td class="in-out_bound">&nbsp;</td>

                    <td></td>
                    <td class="value"></td>
                    <td style="width:3px;">&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>

                <tr>

                    <td class="align_right padding-r10"><?php __('Tech Prefix') ?></td>
                    <td>
                        <select name ="route_prefix" id="CdrRoutePrefix">
                            <option value="">
                                All
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

                    <?php echo $this->element('search_report/search_orig_country'); ?>
                    <td class="in-out_bound">&nbsp;</td>

                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
<!--                    <td class="align_right padding-r10">--><?php //echo __('dnis', true); ?><!-- </td>-->
<!--                    <td><input type="text" id="query-dst_number" value="" name="query[dst_number]" class="input" style="width:220px;">-->
<!--                        --><?php //echo $this->element('search_report/ss_clear_input_select'); ?><!--</td>-->
                    <td style="width:3px;">&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>

                    <?php echo $this->element('search_report/search_orig_code_name'); ?>
                    <td class="in-out_bound">&nbsp;</td>

                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
<!--                    <td class="align_right padding-r10">--><?php //echo __('ani', true); ?><!--</td>-->
<!--                    <td><input type="text" id="query-src_number" value="" name="query[src_number]" class="input" style="width:220px">-->
<!--                        --><?php //echo $this->element('search_report/ss_clear_input_select'); ?><!--</td>-->
                    <td style="width:3px;">&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                <?php if (Configure::read('statistics.have_code_rate')): ?>
                <tr> 
                    <?php echo $this->element('search_report/search_orig_code'); ?>
                    <td class="in-out_bound">&nbsp;</td>

                    <td></td>
                    <td class="value"></td>
                    <td style="width:3px;">&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
            <?php endif; ?>

                <!--tr>
                    <td class="align_right padding-r10"><?php __('Rate Type') ?></td>
                    <td>
                        <select name="orig_rate_type">
                            <option value="0" <?php echo $common->set_get_select('orig_rate_type', 0); ?>><?php __('All') ?></option>
                            <option value="1" <?php echo $common->set_get_select('orig_rate_type', 1); ?>><?php __('A-Z') ?></option>
                            <option value="2" <?php echo $common->set_get_select('orig_rate_type', 2); ?>><?php __('US') ?></option>
                            <option value="3" <?php echo $common->set_get_select('orig_rate_type', 3); ?>><?php __('OCN-LATA') ?></option>
                        </select>
                        <?php echo $this->element('search_report/ss_clear_input_select'); ?>
                    </td>
                    <td>&nbsp;</td>
                    <td class="align_right padding-r10"><?php __('Rate Type') ?></td>
                    <td>
                        <select name="term_rate_type">
                            <option value="0" <?php echo $common->set_get_select('term_rate_type', 0); ?>><?php __('All') ?></option>
                            <option value="1" <?php echo $common->set_get_select('term_rate_type', 1); ?>><?php __('A-Z') ?></option>
                            <option value="2" <?php echo $common->set_get_select('term_rate_type', 2); ?>><?php __('US') ?></option>
                            <option value="3" <?php echo $common->set_get_select('term_rate_type', 3); ?>><?php __('OCN-LATA') ?></option>
                        </select>
                        <?php echo $this->element('search_report/ss_clear_input_select'); ?>
                    </td>
                    <td>&nbsp;</td>
                </tr-->
                <tr>
                    <td class="align_right padding-r10"><?php __('Rate Table') ?></td>
                    <td>
                        <select id="ingress_rate_table" name="ingress_rate_table">
                            <option value="all">
                                <?php __('All') ?>
                            </option>
                            <?php
                            if (!empty($ingress_options['rate_tables']))
                            {
                                foreach ($ingress_options['rate_tables'] as $te)
                                {
                                    if ($_GET['ingress_rate_table'] == $te[0]['rate_table_id'])
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
                                    echo "<option value='" . $rate_table[0]['id'] . "'>" . $rate_table[0]['name'] . "</option>";
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

                <tr>
                    <td class="align_right padding-r10"><?php __('Routing Plan') ?></td>
                    <td>
                        <select id="ingress_routing_plan" name="ingress_routing_plan">
                            <option value="all">
                                <?php __('All') ?>
                            </option>
                            <?php
                            if (!empty($ingress_options['routing_plans']))
                            {


                                foreach ($ingress_options['routing_plans'] as $te)
                                {
                                    if ($_GET['ingress_routing_plan'] == $te[0]['route_strategy_id'])
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
                                    echo "<option value='" . $routing_plan[0]['id'] . "'>" . $routing_plan[0]['name'] . "</option>";
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

                <!--
                <tr>
          
                  
              <td> <?php __('codedecks') ?><span class="tooltip" id="ht-100001-tooltip"><b>Use pre-assigned</b> &mdash; means usage of code decks assigned to each pulled client or rate table.<br><br>If you will <b>specify</b> a code deck, all code names will be rewritten using names from selected code deck, so all data will be unified by code names.</span>:</td>
              <td class="value">
                <?php echo $form->input('code_deck', array('options' => $code_deck, 'empty' => '  ', 'label' => false, 'div' => false, 'type' => 'select')); ?>
              </td>
                </tr>
                -->
                <!--
                 <tr> 
                   
                <?php
                if ($rate_type == 'term')
                {
                    ?>
                                                               <td><?php __('egress') ?></td>
                                                               <td class="value"><?php echo $form->input('egress_alias', array('options' => $egress, 'empty' => '  ', 'label' => false, 'div' => false, 'type' => 'select'));
                    ?></td>
                <?php } ?>
                <?php
                if ($rate_type == 'org')
                {
                    ?>
                                                               <td><?php __('ingress') ?>
                                                                 <span class="tooltip" id="ht-100013-tooltip"><b>Use pre-assigned</b> &mdash; means usage of code decks assigned to each pulled client or rate table.<br>
                                                                 <br>
                                                                 If you will <b>specify</b> a code deck, all code names will be rewritten using names from selected code deck, so all data will be unified by code names.</span>:</td>
                                                               <td class="value"><?php
                    echo $form->input('ingress_alias', array('options' => $ingress, 'empty' => '  ', 'label' => false, 'div' => false, 'type' => 'select'));
                    ?></td>
                <?php } ?>
                    
                   
                 </tr>
                -->
                <!--
                <tr> 
                  
                  <td><span rel="helptip" class="helptip" id="ht-100002">Interval (sec)</span><span class="tooltip" id="ht-100002-tooltip">Duration interval in seconds</span>:</td>
                  <td class="value"><input type="text" id="query-interval_from" class="in-digits input in-text" style="width: 53px;" value="" name="query[interval_from]">
                    &mdash;
                    <input type="text" id="query-interval_to" class="in-digits input in-text" style="width: 54px;" value="" name="query[interval_to]"></td>
                </tr>
                -->
                <?php
            }if ($rate_type == 'term')
            {
                ?>

                <tr class="period-block" style="height:20px; line-height:20px;">
                    <td colspan="2" style="text-align:center;font-size:14px;"><b><?php echo __('Outbound', true); ?></b></td>
                    <td class="in-out_bound">&nbsp;</td>
                    <td>&nbsp;</td>
                    <td class="value"></td>
                    <td style="width:3px;">&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>

                <tr> 
                    <?php echo $this->element('search_report/term_carrier_select'); ?>
                    <td class="in-out_bound">&nbsp;</td>
                    <td class="align_right padding-r10"> <?php echo __('class4-server', true); ?></td>
                    <td><?php
                        echo $form->input('server_ip', array('options' => $server, 'empty' => '   ', 'label' => false, 'div' => false, 'type' => 'select'));
                        ?>
                        <?php echo $this->element('search_report/ss_clear_input_select'); ?></td>
                    <td style="width:3px;">&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td class="align_right padding-r10"><?php __('egress') ?></td>
                    <td><?php echo $form->input('egress_alias', array('options' => $egress, 'empty' => '  ', 'label' => false, 'div' => false, 'type' => 'select'));
                        ?><?php echo $this->element('search_report/ss_clear_input_select'); ?></td>
                    <td class="in-out_bound">&nbsp;</td>
                    <td class="align_right padding-r10"></td>
                    <td></td>
                    <td style="width:3px;">&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                <tr> 
                    <?php echo $this->element('search_report/search_term_country'); ?>
                    <td class="in-out_bound">&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
<!--                    <td class="align_right padding-r10">--><?php //echo __('dnis', true); ?><!-- </td>-->
<!--                    <td><input type="text" id="query-dst_number" value="" name="query[dst_number]" class="input width220">-->
<!--                        --><?php //echo $this->element('search_report/ss_clear_input_select'); ?><!--</td>-->
                    <td style="width:3px;">&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <?php echo $this->element('search_report/search_term_code_name'); ?>
                    <td class="in-out_bound">&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
<!--                    <td class="align_right padding-r10">--><?php //echo __('ani', true); ?><!--</td>-->
<!--                    <td><input type="text" id="query-src_number" value="" name="query[src_number]" class="input width220">-->
<!--                        --><?php //echo $this->element('search_report/ss_clear_input_select'); ?><!--</td>-->
                    <td style="width:3px;">&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                <!--
                <tr>
                    <?php //echo $this->element('search_report/search_term_code'); ?>
                    <td class="in-out_bound">&nbsp;</td>
                    <td></td>
                    <td></td>
                    <td style="width:3px;">&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                -->





                <!--
                <tr>
                 
                  
              <td> <?php __('codedecks') ?><span class="tooltip" id="ht-100001-tooltip"><b>Use pre-assigned</b> &mdash; means usage of code decks assigned to each pulled client or rate table.<br><br>If you will <b>specify</b> a code deck, all code names will be rewritten using names from selected code deck, so all data will be unified by code names.</span>:</td>
              <td>
                <?php echo $form->input('code_deck', array('options' => $code_deck, 'empty' => '  ', 'label' => false, 'div' => false, 'type' => 'select')); ?>
              </td>
                </tr>
                --> 
                <!--
                <tr> 
                  
                <?php
                if ($rate_type == 'term')
                {
                    ?>
                                                              <td><?php __('egress') ?></td>
                                                              <td><?php echo $form->input('egress_alias', array('options' => $egress, 'empty' => '  ', 'label' => false, 'div' => false, 'type' => 'select'));
                    ?></td>
                <?php } ?>
                <?php
                if ($rate_type == 'org')
                {
                    ?>
                                                              <td><?php __('ingress') ?>
                                                                <span class="tooltip" id="ht-100013-tooltip"><b>Use pre-assigned</b> &mdash; means usage of code decks assigned to each pulled client or rate table.<br>
                                                                <br>
                                                                If you will <b>specify</b> a code deck, all code names will be rewritten using names from selected code deck, so all data will be unified by code names.</span>:</td>
                                                              <td><?php
                    echo $form->input('ingress_alias', array('options' => $ingress, 'empty' => '  ', 'label' => false, 'div' => false, 'type' => 'select'));
                    ?></td>
                <?php } ?>
                </tr>
                -->
                <!--
                <tr> 
                  
                  <td><span rel="helptip" class="helptip" id="ht-100002">Interval (sec)</span><span class="tooltip" id="ht-100002-tooltip">Duration interval in seconds</span>:</td>
                  <td><input type="text" id="query-interval_from" class="in-digits input in-text" style="width: 53px;" value="" name="query[interval_from]">
                    &mdash;
                    <input type="text" id="query-interval_to" class="in-digits input in-text" style="width: 54px;" value="" name="query[interval_to]"></td>
                </tr>
                --> 
            <?php } ?>

            <?php
            if (!$pass_1)
                echo $this->element('report/group_by');
            else
            {
                if ($rate_type == 'term')
                    echo $form->input('group_by1', array('name' => 'group_by[0]', 'empty' => '', 'label' => false, 'div' => false, 'type' => 'hidden', 'value' => 'term_client_name'));
                elseif ($rate_type == 'org')
                    echo $form->input('group_by1', array('name' => 'group_by[0]', 'empty' => '', 'label' => false, 'div' => false, 'type' => 'hidden', 'value' => 'orig_client_name'));
            }
            ?>
        </table>
    </div>
    <?php echo $form->end(); ?>
</fieldset>


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

    $(function() {
        $("#query-output").change(function () {
            if ($(this).val() == 'web') {
                $('.scheduled_report_form').removeAttr('target');
            } else {
                $('.scheduled_report_form').attr('target', '_blank');
            }
        });
    });
</script>

