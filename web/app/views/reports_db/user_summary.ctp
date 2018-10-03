<ul class="breadcrumb">
    <li><?php echo __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
     <?php if($_SESSION['login_type'] == 3):?>
        <li><?php __('Client Portal') ?></li>
    <?php else:?>
        <li><?php __('Statistics') ?></li>
    <?php endif;?>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Summary Report') ?></li>
</ul>
<div class="heading-buttons">
    <h4 class="heading">Summary Report</h4>
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>
<div class="innerLR">
    <?php $login_type = $_SESSION['login_type'];?>

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-head">
            <h4 class="heading">User Summary Report</h4>
            <!--             <ul>
                <li <?php if ($type == 1) echo 'class="active"'; ?>>
                    <a href="<?php echo $this->webroot; ?>reports_db/user_summary/1"class="glyphicons left_arrow">
                        <i></i>
                        <?php __('Origination') ?>
                    </a>
                </li>

                <?php
            if ($all_termination)
            {
                ?>
                    <li <?php if ($type == 2) echo 'class="active"'; ?>>
                        <a href="<?php echo $this->webroot; ?>reports_db/user_summary/2"class="glyphicons right_arrow">
                            <i></i>
                            <?php __('Termination') ?>
                        </a>
                    </li>
                    <?php
            }
            ?>
            </ul> -->
        </div>


        <div class="widget-body">


            <?php echo $form->create('Cdr', array('type' => 'get', 'url' => "/reports_db/user_summary/{$type}", 'onsubmit' => "if($('select[name=show_type]').val() == 0) loading();")); ?>
            <fieldset class="query-box" style="clear:both;overflow:hidden;margin-top:10px;">
                <div class="search_title">
                    <img src="<?php echo $this->webroot ?>images/search_title_icon.png">
                    Search
                </div>
                <?php echo $this->element('search_report/search_js'); ?> <?php echo $this->element('search_report/search_hide_input'); ?>
                <input type="hidden" name="show_type" value="web">
                <table class="form" style="width:100%">

                    <tr class="period-block">
                        <td style="width:80px; text-align:right;"><?php __('time') ?>
                            :</td>
                        <td style="width:auto;"><table class="in-date" style="">
                                <tbody>
                                <tr>
                                    <td style="width:100px; text-align: left;">
                                        <?php
                                        $r = array('custom' => __('custom', true), 'curDay' => __('today', true), 'prevDay' => __('yesterday', true), 'curWeek' => __('currentweek', true), 'prevWeek' => __('previousweek', true), 'curMonth' => __('currentmonth', true), 'prevMonth' => __('previousmonth', true), 'curYear' => __('currentyear', true), 'prevYear' => __('previousyear', true));
                                        if (!empty($_POST))
                                        {
                                            if (isset($_POST['smartPeriod']))
                                            {
                                                $s = $_POST['smartPeriod'];
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
                                            'onchange' => 'setPeriod(this.value)', 'id' => 'query-smartPeriod', 'name' => 'smartPeriod', 'style' => 'width:80px;', 'div' => false, 'type' => 'select', 'selected' => $s));
                                        ?>
                                    </td>
                                    <td><input type="text" id="query-start_date-wDt"
                                               class="in-text input" onchange="setPeriod('custom')"
                                               readonly="readonly" onkeydown="setPeriod('custom')" value=""
                                               name="start_date" style="width: 80px;" >&nbsp;<input type="text" id="query-start_time-wDt"
                                                                                                    onchange="setPeriod('custom')" onkeydown="setPeriod('custom')"
                                                                                                    readonly="readonly" style="width: 60px;" value="00:00:01"
                                                                                                    name="start_time" class="input in-text"></td><td style="width:auto;">&mdash;</td><td><input type="text" id="query-stop_date-wDt"
                                                                                                                                                                                                class="in-text input" onchange="setPeriod('custom')"
                                                                                                                                                                                                readonly="readonly" onkeydown="setPeriod('custom')" value=""
                                                                                                                                                                                                name="stop_date" style="width: 80px;">&nbsp;<input type="text" id="query-stop_time-wDt"
                                                                                                                                                                                                                                                   onchange="setPeriod('custom')" readonly="readonly"
                                                                                                                                                                                                                                                   onkeydown="setPeriod('custom')" style="width: 60px;"
                                                                                                                                                                                                                                                   value="23:59:59" name="stop_time" class="input in-text"></td><td>in</td><td><select id="query-tz"
                                                                                                                                                                                                                                                                                                                                       style="width: 80px;" name="query[tz]" class="input in-select">
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
                                            <option value="+0200">GMT +02:00</option>
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
                                    <?php
                                    if ('usagedetails' != $this->params['controller'])
                                    {
                                        ?>
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
                                            echo $form->input('group_by_date', array('options' => $r, 'label' => false, 'id' => 'query-group_by_date', 'style' => 'width: 80px;', 'name' => 'group_by_date',
                                                'div' => false, 'type' => 'select', 'selected' => $s));
                                            ?></td>
                                    <?php } ?>
                                    <?php if($type == 1):?>
                                        <td>Ingress Trunk:</td>
                                        <td class="value">
                                            <select style="width:120px;" name="ingress_id" onchange="getTechPrefix(this);">
                                                <?php
                                                if (empty($_GET['ingress_id']))
                                                {
                                                    ?>
                                                    <option selected="" value=""> <?php echo __("All", true)?></option>
                                                    <?php
                                                }
                                                else
                                                {
                                                    ?>
                                                    <option  value=""><?php echo __("All", true)?></option>
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
                                    <?php else:?>
                                        <td>Egress Trunk:</td>
                                        <td class="value">
                                            <select style="width:120px;" name="egress_id" <!--onchange="getTechPrefix(this);"-->>
                                            <?php
                                            if (empty($_GET['egress_id']))
                                            {
                                                ?>
                                                <option selected=""><?php echo __("All", true)?></option>
                                                <?php
                                            }
                                            else
                                            {
                                                ?>
                                                <option><?php echo __("All", true)?></option>
                                            <?php } ?>
                                            <?php
                                            foreach ($egress_trunks as $egress_trunk):

                                                if ($_GET['egress_id'] == $egress_trunk[0]['resource_id'])
                                                {
                                                    ?>
                                                    <option selected value="<?php echo $egress_trunk[0]['resource_id'] ?>" <?php echo $common->set_get_select('egress_id', $egress_trunk[0]['resource_id']) ?>><?php echo $egress_trunk[0]['alias'] ?></option>
                                                    <?php
                                                }
                                                else
                                                {
                                                    ?>
                                                    <option value="<?php echo $egress_trunk[0]['resource_id'] ?>" <?php echo $common->set_get_select('egress_id', $egress_trunk[0]['resource_id']) ?>><?php echo $egress_trunk[0]['alias'] ?></option>
                                                    <?php
                                                }
                                                ?>


                                            <?php endforeach; ?>
                                            </select>
                                            <?php echo $this->element('search_report/ss_clear_input_select'); ?>
                                        </td>
                                    <?php endif; ?>
                                    <td>
                                        Output:
                                    </td>
                                    <td>
                                        <select style="width:120px;" name="output_query" id="output_query">
                                            <option value="web">Web</option>
                                            <option value="xls">Excel XLS</option>
                                            <option value="csv">Excel CSV</option>
                                        </select>
                                    </td>
                                    <td>

                                        <input type="submit" value="<?php echo __('query', true); ?>" id="formquery"  	class="btn btn-primary">

                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </table>
                <table class="form" style="width: 100%">
                    <colgroup>
                        <col width="40%">
                        <col width="60%">
                    </colgroup>
                    <tbody>
                    <!-- <tr> -->
                    <?php if($type == 1): ?>
                        <!--                             <td class="align_right padding-r10"><?php __('Code')?></td>
                            <td class="value">
                                <input type="text" class="width220" name="orig_code" value="<?php echo $common->set_get_value('orig_code') ?>" />
                                <?php echo $this->element('search_report/ss_clear_input_select'); ?>
                            </td> -->
                    <?php else: ?>
                        <!--                             <td class="align_right padding-r10"><?php __('Code')?></td>
                            <td class="value">
                                <input type="text" class="width220" name="term_code" value="<?php echo $common->set_get_value('term_code') ?>" />
                                <?php echo $this->element('search_report/ss_clear_input_select'); ?>
                            </td> -->
                    <?php  endif; ?>
                    <!-- </tr> -->
                    <?php if ($type == 1 ): ?>
                        <!--                    <tr>

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
                    </tr> -->
                    <?php endif; ?>
                    </tbody>
                </table>
                <?php
                /*if ($report_group)
                {
                    ?>
                    <p class="separator text-center"><i class="icon-table icon-3x"></i></p>
                    <table class="form" style="width:100%">
                        <tr>
                            <td class="align_right padding-r10"><?php __('Group By') ?> #1</td>
                            <td class="value">
                                <select name="group_select[]" style="width:160px;">
                                    <option value="" <?php echo $common->set_get_select_mul('group_select', '', 0, TRUE); ?>></option>
                                    <?php if ($type == 1): ?>
                                        <option value="ingress_country" <?php echo $common->set_get_select_mul('group_select', 'ingress_country', 0); ?>><?php __('ingress Country') ?></option>
                                        <option value="ingress_code_name" <?php echo $common->set_get_select_mul('group_select', 'ingress_code_name', 0); ?>><?php __('ingress Code Name') ?></option>
                                    <?php else: ?>
                                        <option value="egress_country" <?php echo $common->set_get_select_mul('group_select', 'egress_country', 0); ?>><?php __('egress Country') ?></option>
                                        <option value="egress_code_name" <?php echo $common->set_get_select_mul('group_select', 'egress_code_name', 0); ?>><?php __('egress Code Name') ?></option>
                                    <?php endif; ?>
                                    <?php if($login_type == 3){?>
                                        <option value="ingress_id" <?php echo $common->set_get_select_mul('group_select', 'ingress_id', 1); ?>><?php __('Trunk name')?></option>
                                    <?php }?>
                                </select>
                            </td>
                            <td class="align_right padding-r10"><?php __('Group By') ?> #2</td>
                            <td class="value">
                                <select name="group_select[]" style="width:160px;">
                                    <option value="" <?php echo $common->set_get_select_mul('group_select', '', 1, TRUE); ?>></option>
                                    <?php if ($type == 1): ?>
                                        <option value="ingress_country" <?php echo $common->set_get_select_mul('group_select', 'ingress_country', 1); ?>><?php __('ingress Country') ?></option>
                                        <option value="ingress_code_name" <?php echo $common->set_get_select_mul('group_select', 'ingress_code_name', 1); ?>><?php __('ingress Code Name') ?></option>
                                    <?php else: ?>
                                        <option value="egress_country" <?php echo $common->set_get_select_mul('group_select', 'egress_country', 1); ?>><?php __('egress Country') ?></option>
                                        <option value="egress_code_name" <?php echo $common->set_get_select_mul('group_select', 'egress_code_name', 1); ?>><?php __('egress Code Name') ?></option>
                                    <?php endif; ?>
                                    <?php if($login_type == 3){?>
                                        <option value="ingress_id" <?php echo $common->set_get_select_mul('group_select', 'ingress_id', 1); ?>><?php __('Trunk name')?></option>
                                    <?php }?>
                                </select>
                            </td>
                        </tr>
                    </table>
                <?php }*/ ?>
            </fieldset>
            <?php echo $form->end(); ?>
            <div><hr /></div>

            <?php
            if ($_SESSION['login_type'] == 3 && isset($_SESSION['role_menu']['Payment_Invoice']['view_cost_and_rate'])) {
                $cr_flg = true;
            } else {
//                $cr_flg = false;
                $cr_flg = true;
            }?>
            <?php if ($show_nodata): ?><h1 style="font-size:14px;">Report Period <?php echo $start_date ?> — <?php echo $end_date ?></h1><?php endif; ?>
            <?php if (empty($data)): ?>
                <?php if ($show_nodata): ?><div class="msg center">
                    <h2><?php echo __('no data found') ?></h2>
                    </div><?php endif; ?>
            <?php else: ?>
            <div class="overflow_x">
                <table class="list footable table table-striped tableTools table-bordered  table-white table-primary" style="color:#4B9100;">
                    <thead>
                    <tr>
                        <?php foreach ($show_fields as $field): ?>
                            <th><?php echo $replace_fields[$field]; ?></th>
                        <?php endforeach; ?>
                        <!--                        <th>ABR</th>-->
                        <th>ASR</th>
                        <th>ACD(min)</th>
                        <?php if($login_type !=3){?>
                            <th>ALOC</th>
                        <?php }?>
                        <th>PDD(ms)</th>
                        <th colspan="2">Time(min)</th>
                        <th>Usage Charge(USA)</th>
                        <?php if ($type == '1'): ?>
                            <!--                            <th>LRN Charge</th>-->
                        <?php endif; ?>
                        <?php if ($cr_flg) { ?>

                            <?php if($login_type !=3){?>
                                <th>Total Cost</th>
                            <?php }?>

                            <?php if (isset($_GET['show_inter_intra'])): ?>
                                <th>Inter Cost</th>
                                <th>Intra Cost</th>
                            <?php endif; ?>
                            <?php if (isset($_GET['rate_display_as']) && $_GET['rate_display_as'] == 1): ?>
                                <th>Actual Rate</th>
                            <?php else: ?>
                                <th>Avg Rate</th>
                            <?php endif; ?>
                        <?php } ?>
                        <?php if ($type == '1'): ?>
                            <th colspan="5">Calls</th>
                        <?php else: ?>
                            <th colspan="4">Calls</th>
                        <?php endif; ?>
                    </tr>
                    <tr>
                        <?php for ($i = 0; $i < count($show_fields); $i++): ?>
                            <th>&nbsp;</th>
                        <?php endfor; ?>
                        <!--                        <th></th>-->
                        <th></th>
                        <th></th>
                        <th></th>
                        <?php if($login_type !=3){?>
                            <th></th>
                        <?php }?>
                        <th>Total Duration</th>
                        <th>Total Billable Time</th>
                        <th></th>
                        <th></th>
                        <?php if ($cr_flg) { ?>
                            <?php if($login_type !=3){?>
                                <th></th>
                            <?php }?>
                            <?php if (isset($_GET['show_inter_intra'])): ?>
                                <th></th>
                                <th></th>
                            <?php endif; ?>
                            <?php if ($type == '1'): ?>
                                <!--                                <th></th>-->
                            <?php endif; ?>
                        <?php } ?>
                        <th>Total Calls</th>
                        <th>Not Zero</th>
                        <?php if($login_type !=3){?>
                            <th>Success Calls</th>
                        <?php }?>
                        <th>Busy Calls</th>
                        <?php if ($type == '1' && $login_type !=3): ?>
                            <th>LRN Calls</th>
                        <?php endif; ?>
                    </thead>
                    <tbody>
                    <?php
                    $i = 0;
                    $arr = array();
                    $sum_total_final_calls = 0;
                    $sum_not_zero_calls = 0;
                    foreach ($data as $item):
                        $arr['duration'][$i] = $item[0]['duration'];
                        $arr['bill_time'][$i] = $item[0]['bill_time'];
                        $arr['call_cost'][$i] = $item[0]['call_cost'];
                        $arr['cancel_calls'][$i] = $item[0]['cancel_calls'];
                        if ($type == 1):
                            $arr['lnp_cost'][$i] = $item[0]['lnp_cost'];
                            $arr['lrn_calls'][$i] = $item[0]['lrn_calls'];
                        endif;
                        $arr['total_calls'][$i] = $item[0]['total_calls'];
                        $arr['inter_cost'][$i] = $item[0]['inter_cost'];
                        $arr['intra_cost'][$i] = $item[0]['intra_cost'];
                        $arr['not_zero_calls'][$i] = $item[0]['not_zero_calls'];
                        $arr['success_calls'][$i] = $item[0]['success_calls'];
                        $arr['busy_calls'][$i] = $item[0]['busy_calls'];
                        $arr['pdd'][$i] = $item[0]['pdd'];
                        $total_final_calls = $item[0]['total_final_calls'];
                        $sum_total_final_calls += $total_final_calls;
                        if (isset($_GET['rate_display_as']) && $_GET['rate_display_as'] == 1)
                        {
                            $arr['actual_rate'][$i] = $item[0]['actual_rate'];
                        }
                        ?>
                        <tr>
                            <?php foreach (array_keys($show_fields) as $key): ?>
                                <td style="color:#6694E3;"><?php echo $item[0][$key]; ?></td>
                            <?php endforeach; ?>
                            <td><?php echo round($arr['total_calls'][$i] == 0 ? 0 : $arr['not_zero_calls'][$i] / $arr['total_calls'][$i] * 100, 2); ?>%</td>
                            <!--                            <td>--><?php //echo ($arr['busy_calls'][$i] + $arr['cancel_calls'][$i] + $arr['not_zero_calls'][$i]) == 0 ? 0 : round($arr['not_zero_calls'][$i] / ($arr['busy_calls'][$i] + $arr['cancel_calls'][$i] + $arr['not_zero_calls'][$i]) * 100, 2) ?><!--%</td>-->
                            <td><?php echo round($arr['not_zero_calls'][$i] == 0 ? 0 : $arr['duration'][$i] / $arr['not_zero_calls'][$i] / 60, 2); ?></td>
                            <td>
                                <?php
                                echo ($total_final_calls == 0 ? 0 : number_format($arr['pdd'][$i] / $total_final_calls, 2)) ;
                                ?>
                            </td>
                            <?php if($login_type !=3){?>
                                <td><?php echo round($arr['not_zero_calls'][$i] == 0 ? 0 : $arr['pdd'][$i] / $arr['not_zero_calls'][$i]); ?></td>
                            <?php }?>
                            <td><?php echo number_format($arr['duration'][$i] / 60, 2); ?></td>
                            <td><?php echo number_format($arr['bill_time'][$i] / 60, 2); ?></td>
                            <td><?php echo number_format($arr['call_cost'][$i], 5); ?></td>

                            <?php if($login_type !=3){ ?>
                                <?php if ($type == '1'): ?>
                                    <?php if ($cr_flg) { ?>
                                        <td><?php echo number_format($arr['call_cost'][$i] + $arr['lnp_cost'][$i], 5); ?></td>
                                    <?php } ?>
                                <?php else: ?>
                                    <td><?php echo number_format($arr['call_cost'][$i], 5); ?></td>
                                <?php endif; ?>
                            <?php } ?>

                            <?php if ($cr_flg) { ?>
                                <?php if (isset($_GET['show_inter_intra'])): ?>
                                    <td><?php echo number_format($arr['inter_cost'][$i], 5); ?></td>
                                    <td><?php echo number_format($arr['intra_cost'][$i], 5); ?></td>
                                <?php endif; ?>
                                <?php if (isset($_GET['rate_display_as']) && $_GET['rate_display_as'] == 1): ?>
                                    <td><?php echo $arr['actual_rate'][$i] ?></td>
                                <?php else: ?>
                                    <td><?php echo number_format($arr['bill_time'][$i] == 0 ? 0 : $arr['call_cost'][$i] / ($arr['bill_time'][$i] / 60), 5); ?></td>
                                <?php endif; ?>
                            <?php } ?>
                            <td><?php echo number_format($arr['total_calls'][$i]); ?></td>
                            <td><?php echo number_format($arr['not_zero_calls'][$i]); ?></td>
                            <?php if($login_type !=3){?>
                                <td><?php echo number_format($arr['success_calls'][$i]); ?></td>
                            <?php }?>
                            <td><?php echo number_format($arr['busy_calls'][$i]); ?></td>
                            <?php if ($type == '1'  && $login_type !=3): ?>
                                <td><?php echo number_format($arr['lrn_calls'][$i]); ?></td>
                            <?php endif; ?>
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
                            <td colspan="<?php echo $count_group; ?>">Total:</td>
                            <td><?php echo round(array_sum($arr['total_calls']) == 0 ? 0 : array_sum($arr['not_zero_calls']) / array_sum($arr['total_calls']) * 100, 2); ?>%</td>
                            <!--                            <td>--><?php //echo (array_sum($arr['busy_calls']) + array_sum($arr['cancel_calls']) + array_sum($arr['not_zero_calls'])) == 0 ? 0 : round(array_sum($arr['not_zero_calls']) / (array_sum($arr['busy_calls']) + array_sum($arr['cancel_calls']) + array_sum($arr['not_zero_calls'])) * 100, 2) ?><!--%</td>-->
                            <td><?php echo round(array_sum($arr['not_zero_calls']) == 0 ? 0 : array_sum($arr['duration']) / array_sum($arr['not_zero_calls']) / 60, 2); ?></td>
                            <td><?php echo number_format($sum_total_final_calls == 0 ? 0 : array_sum($arr['pdd']) / $sum_total_final_calls, 2);?></td>
                            <?php if ($login_type != 3) {?>
                                <td><?php echo round(array_sum($arr['not_zero_calls']) == 0 ? 0 : array_sum($arr['pdd']) / array_sum($arr['not_zero_calls'])); ?></td>
                            <?php } ?>
                            <td><?php echo number_format(array_sum($arr['duration']) / 60, 2); ?></td>
                            <td><?php echo number_format(array_sum($arr['bill_time']) / 60, 2); ?></td>
                            <td><?php echo number_format(array_sum($arr['call_cost']), 5); ?></td>

                            <?php if($login_type !=3){ ?>
                                <?php if ($type == '1'): ?>
                                    <?php if ($cr_flg) { ?>
                                        <td><?php echo number_format(array_sum($arr['call_cost']) + array_sum($arr['lnp_cost']), 5); ?></td>
                                    <?php } ?>
                                <?php else: ?>
                                    <td><?php echo number_format(array_sum($arr['call_cost']), 5); ?></td>
                                <?php endif; ?>
                            <?php } ?>

                            <?php if ($cr_flg) { ?>
                                <?php if (isset($_GET['show_inter_intra'])): ?>
                                    <td><?php echo number_format(array_sum($arr['inter_cost']), 5); ?></td>
                                    <td><?php echo number_format(array_sum($arr['intra_cost']), 5); ?></td>
                                <?php endif; ?>
                                <?php if (isset($_GET['rate_display_as']) && $_GET['rate_display_as'] == 1): ?>
                                    <td><?php echo array_sum($arr['actual_rate']); ?></td>
                                <?php else: ?>
                                    <td><?php echo number_format(array_sum($arr['bill_time']) == 0 ? 0 : array_sum($arr['call_cost']) / (array_sum($arr['bill_time']) / 60), 5); ?></td>
                                <?php endif; ?>
                            <?php } ?>

                            <td><?php echo number_format(array_sum($arr['total_calls'])); ?></td>
                            <td><?php echo number_format(array_sum($arr['not_zero_calls'])); ?></td>
                            <?php if($login_type !=3){?>
                                <td><?php echo number_format(array_sum($arr['success_calls'])); ?></td>
                            <?php }?>
                            <td><?php echo number_format(array_sum($arr['busy_calls'])); ?></td>
                            <?php if ($type == '1'  && $login_type !=3): ?>
                                <td><?php echo number_format(array_sum($arr['lrn_calls'])); ?></td>
                            <?php endif; ?>
                        </tr>
                        <?php
                    endif;
                    ?>
                    </tbody>
                </table>
                <div class="separator"></div>
            </div>
        </div>
        <?php endif; ?>

    </div>

</div>
<script type="text/javascript">

    var $routeprefix = $("#CdrRoutePrefix");
    var $ingress_rate_table = $('#ingress_rate_table');
    var $ingress_routing_plan = $('#ingress_routing_plan');
    var group_select1 = '<?php echo isset($_GET['group_select']['0']) ? $_GET['group_select']['0'] : '';?>';
    var group_select2 = '<?php echo isset($_GET['group_select']['1']) ? $_GET['group_select']['1'] : '';?>';
    if(group_select1){
         $('select[name="group_select[]"]:eq(0)').val(group_select1);
    }
    if(group_select2){
         $('select[name="group_select[]"]:eq(1)').val(group_select2);
    }

    function getTechPrefix(obj) {
        var $this = $(obj);
        var val = $this.val();
        $routeprefix.empty();
//        $ingress_rate_table.empty();
//        $ingress_routing_plan.empty();
        $routeprefix.append("<option value='all'>All</option>");
//        $ingress_rate_table.append("<option value='all'>All</option>");
//        $ingress_routing_plan.append("<option value='all'>All</option>");
        if (val != '0') {

            $.post("<?php echo $this->webroot ?>cdrreports_db/getTechPerfix", {ingId: val},
                function(data) {
                    $.each(data.prefixes,
                        function(index, content) {
                            $routeprefix.append("<option value='" + content[0]['tech_prefix'] + "'>" + content[0]['tech_prefix'] + "</option>");
                        }
                    );
//                    $.each(data.rate_tables,
//                        function(index, content) {
//                            $ingress_rate_table.append("<option value='" + content[0]['rate_table_id'] + "'>" + content[0]['rate_table_name'] + "</option>");
//                        }
//                    );
//                    $.each(data.routing_plans,
//                        function(index, content) {
//                            $ingress_routing_plan.append("<option value='" + content[0]['route_strategy_id'] + "'>" + content[0]['route_strategy_name'] + "</option>");
//                        }
//                    );
                }, 'json');

        }

    }

    function clear_prefix(obj) {
        var $this = $(obj);
        $(obj).prev().find('option:first').attr('selected', true);
    }
</script>