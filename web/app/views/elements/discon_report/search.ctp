
<fieldset class="query-box" style=" clear:both;overflow:hidden;margin-top:10px;">
    <h4 class="heading glyphicons search"><i></i> <?php __('Search')?></h4>
    <?php echo $this->element('search_report/search_js'); ?>
    <?php
    if ($rate_type == 'org')
    {
        $url = '/disconnectreports/summary_reports/org/';
    }
    else
    {
        $url = '/disconnectreports/summary_reports/term/';
    }
    echo $form->create('Cdr', array('type' => 'get', 'url' => $url, 'onsubmit' => "if ($('#query-output').val() == 'web') loading();"));
    ?>
    <?php echo $this->element('search_report/search_hide_input'); ?>
    <table  class="form" style="width:100%;">
        <tbody>
            <tr class="period-block" >
                <td><?php __('time') ?>
                    :</td>
                <td colspan="8" style="width:auto;"><table class="in-date">
                        <tbody>
                            <tr>
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
                                <td><input  type="text" style="width: 80px;"  id="query-start_date-wDt" class="wdate in-text input" onChange="setPeriod('custom')"   readonly="readonly"  onkeydown="setPeriod('custom')" value="" name="start_date"  ></td>
                                <td><input type="text" id="query-start_time-wDt" onChange="setPeriod('custom')" onKeyDown="setPeriod('custom')"	readonly="readonly" style="width: 60px;" value="00:00:00" name="start_time" class="input in-text">
                                    &nbsp;&nbsp;&mdash;&nbsp;&nbsp;
                                    <input type="text" id="query-stop_date-wDt" class="wdate in-text input"  style="width: 80px;"    onchange="setPeriod('custom')"
                                           readonly="readonly" 
                                           onkeydown="setPeriod('custom')" value="" name="stop_date"></td>
                                <td><input type="text" id="query-stop_time-wDt" onChange="setPeriod('custom')"
                                           readonly="readonly" 
                                           onkeydown="setPeriod('custom')" style="width: 60px;" value="23:59:59" name="stop_time" class="input in-text">
                                    &nbsp;&nbsp;in&nbsp;&nbsp;
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
                                    echo $form->input('group_by_date', array('options' => $r, 'label' => false, 'id' => 'query-group_by_date', 'style' => 'width: 100px;', 'name' => 'group_by_date',
                                        'div' => false, 'type' => 'select', 'selected' => $s));
                                    ?></td>
                                <td>

                                    <select id="query-output" onChange="repaintOutput();" name="query[output]" class="input in-select">
                                        <option selected="selected" value="web">Web</option>
                                        <?php
                                        if ($_SESSION['role_menu']['Statistics']['disconnectreports']['model_x'])
                                        {
                                            ?>
                                            <option value="csv">Excel CSV</option>
                                            <option value="xls">Excel XLS</option>
                                        <?php } ?>
                                        <!--<option value="delayed">Delayed CSV</option>
                                        -->
                                    </select>

                                </td>
                            </tr>
                            <tr>
                                <td colspan="6" style="text-align: right;"><button type="submit" id="formquery" data-toggle="btn-loading" data-loading-text="Now searching ..." class="btn btn-primary btn-large"><i class="icon-search"></i>
                                        <?php __('Start Searching')?></button></td>
                            </tr>
                        </tbody>
                    </table></td>
            </tr>

            <?php
            if ($rate_type == 'org')
            {
                ?>
                <tr class="period-block" style="height:20px; line-height:20px;">
                    <td colspan="2" style="text-align:center; font-size:14px;"><b><?php echo __('Inbound', true); ?></b></td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td></td>
                    <td style="width:3px;">&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>

                <tr> 


                    <?php echo $this->element('search_report/orig_carrier_select'); ?>
                    <td>&nbsp;</td>
                    <td> <?php //echo __('class4-server', true); ?></td>
                    <td><?php
                        //echo $form->input('server_ip', array('options' => $server, 'empty' => '   ', 'label' => false, 'div' => false, 'type' => 'select'));
                        ?>
                        <?php //echo $this->element('search_report/ss_clear_input_select'); ?></td>
                    <td style="width:3px;">&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>

                    <td><?php echo __('Ingress Trunk', TRUE) ?>:
                    <td><?php
                        echo $form->input('ingress_alias', array('options' => $ingress, 'label' => false, 'div' => false, 'type' => 'select', 'onchange' => 'getTechPrefix(this);'));
                        ?>
                        <?php echo $this->element('search_report/ss_clear_input_select'); ?></td>
                    <td>&nbsp;</td>

                    <td><?php __('Response to ingress')?></td>
                    <td>
                        <select id="query-res_status_ingress" class="input in-select" name="query[res_status_ingress]" onchange="$('#query-disconnect_cause_ingress').val($('#query-res_status_ingress').val());">
                            <option value=""><?php __('all')?></option>
                            <option value="200"><?php __('success')?></option>
                            <option value="300"><?php __('multiple')?></option>
                            <option value="301"><?php __('moved permanently')?></option>
                            <option value="302"><?php __('moved temporaily')?></option>
                            <option value="305"><?php __('use proxy')?></option>
                            <option value="380"><?php __('alternative service')?></option>
                            <option value="400"><?php __('bad request')?></option>
                            <option value="401"><?php __('unauthorized')?></option>
                            <option value="402"><?php __('payment required')?></option>
                            <option value="403"><?php __('forbidden')?></option>
                            <option value="404"><?php __('not found')?></option>
                            <option value="405"><?php __('method no allowed')?></option>
                            <option value="406"><?php __('not acceptable')?></option>
                            <option value="407"><?php __('proxy authentication required')?></option>
                            <option value="408"><?php __('request timeout')?></option>
                            <option value="410"><?php __('gone')?></option>
                            <option value="413"><?php __('request entity too large')?></option>
                            <option value="414"><?php __('request-url too long')?></option>
                            <option value="415"><?php __('unsupported media type')?></option>
                            <option value="416"><?php __('unsupported url scheme')?></option>
                            <option value="420"><?php __('bad extension')?></option>
                            <option value="421"><?php __('extension required')?></option>
                            <option value="423"><?php __('interval too brief')?></option>
                            <option value="480"><?php __('temporarily unavailable')?></option>
                            <option value="481"><?php __('call/transaction does not exist')?></option>
                            <option value="482"><?php __('loop detected')?></option>
                            <option value="483"><?php __('too many hops')?></option>
                            <option value="484"><?php __('address incomplete')?></option>
                            <option value="485"><?php __('ambiguous')?></option>
                            <option value="486"><?php __('busy here')?></option>
                            <option value="487"><?php __('request terminated')?></option>
                            <option value="488"><?php __('not acceptable here')?></option>
                            <option value="491"><?php __('request pending')?></option>
                            <option value="493"><?php __('undecipherable')?></option>
                            <option value="500"><?php __('server internal error')?></option>
                            <option value="501"><?php __('not implemented')?></option>
                            <option value="502"><?php __('bad gateway')?></option>
                            <option value="503"><?php __('service unavailable')?></option>
                            <option value="504"><?php __('server time-out')?> </option>
                            <option value="505"><?php __('version not supported')?> </option>
                            <option value="513"><?php __('message too large')?> </option>
                            <option value="600"><?php __('busy everywhere')?> </option>
                            <option value="603"><?php __('decline')?> </option>
                            <option value="604"><?php __('all')?> </option>
                            <option value="606"><?php __('not acceptable')?> </option>
                        </select>
                        <input id="query-disconnect_cause_ingress" class="input in-text" type="text" name="query[disconnect_cause_ingress]" value="" style="width: 35px;">
                        <a onclick="ss_clear_input_select(this);" href="javascript:void(0)">
                            <i class="icon-remove"></i>
                        </a>
                    </td>
                    <td style="width:3px;">&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>

                <tr>

                    <td lass="label"><?php __('Tech Prefix')?></td>
                    <td>
                        <select name ="ingress_prefix" id="CdrRoutePrefix">
                            <option value="">
                                <?php __('All')?>
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
                    <td>&nbsp;</td>


                </tr>


                <tr> 

                    <?php echo $this->element('search_report/search_orig_country'); ?>
                    <td>&nbsp;</td>
                    <td></td>
                    <td></td>
                    <td></td>
    <!--                    <td><?php echo __('dnis', true); ?> :</td>
                    <td><input type="text" id="query-dst_number" value="" name="query[dst_number]" class="input in-text">
                    <?php echo $this->element('search_report/ss_clear_input_select'); ?></td>-->
                    <td style="width:3px;">&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>

                    <?php echo $this->element('search_report/search_orig_code_name'); ?>
                    <td>&nbsp;</td>
                    <td></td>
                    <td></td>
                    <td></td>
    <!--                    <td><?php echo __('ani', true); ?>:</td>
                    <td><input type="text" id="query-src_number" value="" name="query[src_number]" class="input in-text">
                    <?php echo $this->element('search_report/ss_clear_input_select'); ?></td>-->
                    <td style="width:3px;">&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                <tr> 
                    <?php echo $this->element('search_report/search_orig_code'); ?>
                    <td>&nbsp;</td>

                    <td></td>
                    <td></td>
                    <td style="width:3px;">&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>

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
                    <td><?php __('Rate Type')?></td>
                    <td>
                        <select name="term_rate_type">
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
                </tr>
                <tr>
                    <td><?php __('Rate Type')?></td>
                    <td>
                        <select style="width:120px;" id="ingress_rate_table" name="ingress_rate_table">
                            <option value="all">
                                <?php __('All')?>
                            </option>
                            <?php
                            if (!empty($_GET['ingress_rate_table']))
                            {
                                foreach ($rate_tables as $te)
                                {
                                    if ($_GET['ingress_rate_table'] == $te[0]['id'])
                                    {
                                        echo "<option selected value='" . $te[0]['id'] . "'>" . $te[0]['name'] . "</option>";
                                    }
                                    else
                                    {
                                        echo "<option value='" . $te[0]['id'] . "'>" . $te[0]['name'] . "</option>";
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
                    <td><?php __('Release Cause')?>:</td>
                    <td>
                        <?php
                        $type = $appCdr->show_release_cause();
                        echo $form->input('cdr_release_cause', array('options' => $type, 'name' => 'cdr_release_cause', 'label' => false, 'div' => false, 'type' => 'select'));
                        ?>
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
                            if (!empty($_GET['ingress_routing_plan']))
                            {


                                foreach ($routing_plans as $routing_plan)
                                {
                                    if ($_GET['ingress_routing_plan'] == $routing_plan[0]['id'])
                                    {
                                        echo "<option selected='selected' value='" . $routing_plan[0]['id'] . "'>" . $routing_plan[0]['name'] . "</option>";
                                    }
                                    else
                                    {
                                        echo "<option value='" . $routing_plan[0]['id'] . "'>" . $routing_plan[0]['name'] . "</option>";
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
                    <td>
                    </td>
                    <td>&nbsp;</td>

                    <td></td>
                    <td>
                    </td>
                </tr>


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
                <?php
            }if ($rate_type == 'term')
            {
                ?>

                <tr class="period-block" style="height:20px; line-height:20px;">
                    <td colspan="2" style="text-align:center;font-size:14px;"><b><?php echo __('Outbound', true); ?></b></td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td></td>
                    <td style="width:3px;">&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>

                <tr> 
                    <?php echo $this->element('search_report/term_carrier_select'); ?>
                    <td>&nbsp;</td>
                    <td> <?php //echo __('class4-server', true); ?></td>
                    <td><?php
                        //echo $form->input('server_ip', array('options' => $server, 'empty' => '   ', 'label' => false, 'div' => false, 'type' => 'select'));
                        ?>
                        <?php //echo $this->element('search_report/ss_clear_input_select'); ?></td>
                    <td style="width:3px;">&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td><?php __('egress') ?></td>
                    <td><?php echo $form->input('egress_alias', array('options' => $egress, 'empty' => '  ', 'label' => false, 'div' => false, 'type' => 'select'));
                        ?><?php echo $this->element('search_report/ss_clear_input_select'); ?></td>
                    <td>&nbsp;</td>
                    <td><?php __('output') ?></td>
                    <td><select id="query-output" onChange="repaintOutput();" name="query[output]" class="input in-select">
                            <option selected="selected" value="web">
                                <?php __('web') ?>
                            </option>
                            <?php
                            if ($_SESSION['role_menu']['Statistics']['disconnectreports']['model_x'])
                            {
                                ?>
                                <option value="csv"><?php __('Excel CSV')?></option>
                                <option value="xls"><?php __('Excel XLS')?></option>
                            <?php } ?>
                            <!--<option value="delayed">Delayed CSV</option>
                            -->
                        </select></td>
                    <td style="width:3px;">&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                <tr> 
                    <?php echo $this->element('search_report/search_term_country'); ?>
                    <td>&nbsp;</td>
                    <td></td>
                    <td></td>
                    <td></td>
    <!--                    <td><?php echo __('dnis', true); ?> :</td>
                    <td><input type="text" id="query-dst_number" value="" name="query[dst_number]" class="input in-text">
                    <?php echo $this->element('search_report/ss_clear_input_select'); ?></td>-->
                    <td style="width:3px;">&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <?php echo $this->element('search_report/search_term_code_name'); ?>
                    <td>&nbsp;</td>
                    <td></td>
                    <td></td>
                    <td></td>
    <!--                    <td><?php echo __('ani', true); ?>:</td>
                    <td><input type="text" id="query-src_number" value="" name="query[src_number]" class="input in-text">
                    <?php echo $this->element('search_report/ss_clear_input_select'); ?></td>-->
                    <td style="width:3px;">&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <?php echo $this->element('search_report/search_term_code'); ?>
                    <td>&nbsp;</td>
                    <td></td>
                    <td></td>
                    <td style="width:3px;">&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>





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

            <?php //echo $this->element('report/group_by');   ?>
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
                        <option value="ingress_rate" <?php echo $common->set_get_select_mul('group_select', 'ingress_rate', 0); ?>><?php __('ingress Rate')?></option>
                    <?php endif; ?>
                    <option value="egress_client_id" <?php echo $common->set_get_select_mul('group_select', 'egress_client_id', 0); ?>><?php __('egress Carrier')?></option>
                    <option value="egress_id" <?php echo $common->set_get_select_mul('group_select', 'egress_id', 0); ?>><?php __('egress Trunk')?></option>
                    <?php if (Configure::read('statistics.group_all')): ?>
                        <option value="egress_country" <?php echo $common->set_get_select_mul('group_select', 'egress_country', 0); ?>><?php __('egress Country')?></option>
                        <option value="egress_code_name" <?php echo $common->set_get_select_mul('group_select', 'egress_code_name', 0); ?>><?php __('egress Code Name')?></option>
                        <option value="egress_code" <?php echo $common->set_get_select_mul('group_select', 'egress_code', 0); ?>><?php __('egress Code')?></option>
                    <?php endif; ?>
<!--                    <option value="origination_destination_host_name" <?php echo $common->set_get_select_mul('group_select', 'origination_destination_host_name', 0); ?>>Orig Server</option>
        <option value="termination_source_host_name" <?php echo $common->set_get_select_mul('group_select', 'termination_source_host_name', 0); ?>>Term Server</option>-->
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
<!--                    <option value="origination_destination_host_name" <?php echo $common->set_get_select_mul('group_select', 'origination_destination_host_name', 1); ?>>Orig Server</option>
        <option value="termination_source_host_name" <?php echo $common->set_get_select_mul('group_select', 'termination_source_host_name', 1); ?>>Term Server</option>-->
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
<!--                    <option value="origination_destination_host_name" <?php echo $common->set_get_select_mul('group_select', 'origination_destination_host_name', 3); ?>>Orig Server</option>
        <option value="termination_source_host_name" <?php echo $common->set_get_select_mul('group_select', 'termination_source_host_name', 3); ?>>Term Server</option>-->
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
<!--                    <option value="origination_destination_host_name" <?php echo $common->set_get_select_mul('group_select', 'origination_destination_host_name', 4); ?>>Orig Server</option>
        <option value="termination_source_host_name" <?php echo $common->set_get_select_mul('group_select', 'termination_source_host_name', 4); ?>>Term Server</option>-->
                </select>
            </td>
            <td><?php __('Group By')?> #6:</td>
            <td>
                <select name="group_select[]" style="width:140px;">
                    <option value="" <?php echo $common->set_get_select_mul('group_select', '', 1, TRUE); ?>></option>
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
<!--                    <option value="origination_destination_host_name" <?php echo $common->set_get_select_mul('group_select', 'origination_destination_host_name', 5); ?>>Orig Server</option>
        <option value="termination_source_host_name" <?php echo $common->set_get_select_mul('group_select', 'termination_source_host_name', 5); ?>>Term Server</option>-->
                </select>
            </td>
        </tr>
    </table>
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
</script>
