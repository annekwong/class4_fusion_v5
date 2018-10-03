<ul class="breadcrumb">
    <li><?php echo __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Statistics') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Summary Report') ?></li>
</ul>
<?php

$user_id = $_SESSION['sst_user_id'];
$res = $cdr_db->query("select * from users where user_id = {$user_id} ");

?>
<div class="heading-buttons">
    <h4 class="heading"><?php __('Summary Report')?></h4>
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>

<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-head">
            <ul>
                <li <?php if ($type == 1) echo 'class="active"'; ?>>
                    <a href="<?php echo $this->webroot; ?>reports_db/summary/1"class="glyphicons left_arrow">
                        <i></i>
                        <?php __('Origination')?>
                    </a>
                </li>

                <li>
                    <a href="<?php echo $this->webroot; ?>reports_db/cascade_summary/1"class="glyphicons left_arrow">
                        <i></i>
                        <?php __('Origination Cascade Report')?>
                    </a>
                </li>

                <?php
                if($res[0][0]['all_termination'] == 't'){
                    ?>
                    <li <?php if ($type == 2) echo 'class="active"'; ?>>
                        <a href="<?php echo $this->webroot; ?>reports_db/summary/2"class="glyphicons right_arrow">
                            <i></i>
                            <?php __('Termination')?>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo $this->webroot; ?>reports_db/cascade_summary/2"class="glyphicons right_arrow">
                            <i></i>
                            <?php __('Termination Cascade Report')?>
                        </a>
                    </li>
                    <?php
                }


                ?>
            </ul>
        </div>
        <div class="widget-body">

            <h1 style="font-size:14px;"><?php __('Report Period')?> <?php echo $start_date ?> — <?php echo $end_date ?></h1>
            <?php if (empty($data)): ?>
                <?php if ($show_nodata): ?><div class="center msg"><h2><?php  echo __('no_data_found') ?></h2></div><?php endif; ?>
            <?php else: ?>
                <div class="overflow_x" style="max-height: 450px">
                    <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary" style="color:#4B9100;">
                        <thead>

                        <tr>
                            <!--group by 的字段-->
                            <?php foreach ($show_fields as $field): ?>
                                <th rowspan="2"><?php echo $replace_fields[$field]; ?></th>
                            <?php endforeach; ?>
                            <!--//group by 的字段-->

                            <th  rowspan="2"><?php __('ASR')?></th>
                            <th  rowspan="2"><?php __('ACD')?></th>
                            <th  rowspan="2"><?php __('PDD')?></th>
                            <!--                            <th  rowspan="2">--><?php //__('NER')?><!--</th>-->

                            <!--ingress-->
                            <?php if ($type == '1'): ?>
                                <th  rowspan="2"><?php __('NPR Count')?></th>
                                <th  rowspan="2"><?php __('NPR')?></th>
                                <th  rowspan="2"><?php __('NRF Count')?></th>
                                <th  rowspan="2"><?php __('NRF')?></th>
                            <?php endif; ?>
                            <th  rowspan="2"><?php __('SD Count')?></th>
                            <th  rowspan="2"><?php __('SDP')?></th>
                            <th  rowspan="2"><?php __('Revenue')?></th>
                            <th  rowspan="2"><?php __('Profit')?></th>
                            <th  rowspan="2"><?php __('Margin')?></th>
                            <th  rowspan="2"><?php __('PP Min')?></th>
                            <th  rowspan="2"><?php __('PP K Calls')?></th>
                            <th  rowspan="2"><?php __('PPKA')?></th>


                            <!--//ingress-->
                            <!--egress-->

                            <!--//egress-->
                            <th  rowspan="2"><?php __('Limited')?></th>
                            <th colspan="2"><?php __('Time')?></th>

                            <th  rowspan="2"><?php __('Total Cost')?></th>

                            <!--show_inter_intra-->
                            <!--                                --><?php //if (isset($_GET['show_inter_intra'])): ?>
                            <th  rowspan="2"><?php __('Inter Cost')?></th>
                            <th  rowspan="2"><?php __('Intra Cost')?></th>
                            <th  rowspan="2"><?php __('Local Cost')?></th>
                            <th  rowspan="2"><?php __('IJ Cost')?></th>
                            <!--                                --><?php //endif; ?>
                            <!--//show_inter_intra-->
                            <!--rate_display_as-->
                            <?php if (isset($_GET['rate_display_as']) && $_GET['rate_display_as'] == 1): ?>
                                <th  rowspan="2"><?php __('Actual Rate')?></th>
                            <?php else: ?>
                                <th  rowspan="2"><?php __('Avg Rate')?></th>
                            <?php endif; ?>
                            <!--//rate_display_as-->
                            <th colspan="3"><?php __('Calls')?></th>


                        </tr>
                        <tr>
                            <th><?php __('Actual')?></th>
                            <th><?php __('Billable')?></th>

                            <th><?php __('Total')?></th>
                            <th><?php __('Not Zero')?></th>
                            <th><?php __('Busy')?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <!-- 计算数据                       -->
                        <?php
                        $i = 0;
                        $sum_duration = 0;
                        $sum_bill_time = 0;
                        $sum_call_cost = 0;
                        $sum_cancel_calls = 0;
                        $sum_total_calls = 0;
                        $sum_inter_cost = 0;
                        $sum_intra_cost = 0;
                        $sum_local_cost = 0;
                        $sum_ij_cost = 0;
                        $sum_not_zero_calls = 0;
                        $sum_total_final_calls = 0;
                        $sum_success_calls = 0;
                        $sum_busy_calls = 0;
                        $sum_pdd = 0;
                        $sum_q850_cause_count = 0;

                        $sum_npr_value = 0;
                        $sum_actual_rate = 0;

                        $sum_egress_cost = 0;

                        $sum_call_limit = 0;
                        $sum_cps_limit = 0;

                        $sum_sd_count = 0;
                        $sum_ner_count = 0;
                        $sum_nrf_count = 0;



                        $arr = array();
                        foreach ($data as $item):
                            if ($type == 2 && isset($item[0]['egress_id']) && !$item[0]['egress_id']){
                                continue;
                            }
                            if ($type == 1 && isset($item[0]['ingress_id']) && !$item[0]['ingress_id']){
                                continue;
                            }
                            if (isset($item[0]['ingress_id']) && $item[0]['ingress_id']){
                                if (!in_array($item[0]['ingress_id'],$appResource->format_ingress_options($ingress_trunks))){
                                    continue;
                                }
                            }
                            if (isset($item[0]['egress_id']) && $item[0]['egress_id']){
                                if (!in_array($item[0]['egress_id'],$appResource->format_ingress_options($egress_trunks))){
                                    continue;
                                }
                            }

                            $duration = $item[0]['duration'];
                            $bill_time = $item[0]['bill_time'];
                            $call_cost = $item[0]['call_cost'];
                            $cancel_calls = $item[0]['cancel_calls'];
                            $total_calls = $item[0]['total_calls'];
                            $inter_cost = $item[0]['inter_cost'];
                            $intra_cost = $item[0]['intra_cost'];
                            $local_cost = isset($item[0]['local_cost']) ? $item[0]['local_cost'] : 0;
                            $ij_cost = isset($item[0]['ij_cost']) ? $item[0]['ij_cost'] : 0;
//                            $local_cost = 0;
//                            $ij_cost = 0;
                            $not_zero_calls = $item[0]['not_zero_calls'];
                            $success_calls = $item[0]['success_calls'];
                            $busy_calls = $item[0]['busy_calls'];
                            $pdd = $item[0]['pdd'];
                            $q850_cause_count = 0;
                            $call_limit = $item[0]['call_limit'];
                            $cps_limit = $item[0]['cps_limit'];
                            $sd_count = $item[0]['sd_count'];
//                            $ner_count = $item[0]['ner_count'];
                            $npr_value = $item[0]['npr_value'];
                            $total_final_calls = $item[0]['total_final_calls'];





                            $sum_duration += $duration;
                            $sum_bill_time += $bill_time;
                            $sum_call_cost += $call_cost;
                            $sum_cancel_calls += $cancel_calls;
                            $sum_total_calls += $total_calls;
                            $sum_inter_cost += $inter_cost;
                            $sum_intra_cost += $intra_cost;
                            $sum_local_cost += $local_cost;
                            $sum_ij_cost += $ij_cost;
                            $sum_not_zero_calls += $not_zero_calls;
                            $sum_total_final_calls += $total_final_calls;
                            $sum_success_calls += $success_calls;
                            $sum_busy_calls += $busy_calls;
                            $sum_pdd += $pdd;
                            $sum_q850_cause_count += $q850_cause_count;
                            $sum_call_limit += $call_limit;
                            $sum_cps_limit += $cps_limit;
                            $sum_sd_count += $sd_count;
//                            $sum_ner_count += $ner_count;


                            //group by 的字段
                            foreach (array_keys($show_fields) as $key){
                                $arr[$key][$i] = $item[0][$key];
                            }

                            //公共数据
                            $arr['asr'][$i] = $total_calls == 0 ? 0 : $not_zero_calls / $total_calls * 100;
                            $_asr = $busy_calls + $cancel_calls + $not_zero_calls;
//                            $arr['asr'][$i] = $_asr == 0 ? 0 : $not_zero_calls / $_asr * 100;
                            $arr['acd'][$i] = $not_zero_calls == 0 ? 0 : $duration / $not_zero_calls / 60;
//                            $arr['aloc'][$i] = $arr['asr'][$i] * $arr['acd'][$i];
                            $arr['pdd'][$i] = $total_final_calls == 0 ? 0 : $pdd / $total_final_calls;
//                            $arr['ner'][$i] = $total_calls == 0 ? 0 : $ner_count / $total_calls * 100;
                            $arr['duration'][$i] = $duration / 60;
                            $arr['bill_time'][$i] = $bill_time / 60;
                            $arr['call_cost'][$i] = $call_cost;
                            $arr['total_calls'][$i] = $total_calls;
                            $arr['not_zero_calls'][$i] = $not_zero_calls;
                            $arr['success_calls'][$i] = $success_calls;
                            $arr['busy_calls'][$i] = $busy_calls;

                            $arr['profit'][$i] = $call_cost;
                            $arr['limited'][$i] = $cps_limit == 0 ? 0 :$call_limit / $cps_limit;

                            $arr['sd_count'][$i] = $sd_count;
                            $arr['sdp'][$i] = $not_zero_calls == 0 ? 0 : $sd_count / $not_zero_calls * 100;



                            //ingress
//                            if ($type == 1){
                            $egress_cost = isset($item[0]['egress_cost']) ? $item[0]['egress_cost'] : null;
                            $nrf_count = isset($item[0]['nrf_count']) ? $item[0]['nrf_count'] : null;
                            $sum_npr_value += $npr_value;
                            $sum_egress_cost += $egress_cost;
                            $sum_nrf_count += $nrf_count;

                            $arr['npr_count'][$i] =$npr_value;
                            $arr['npr'][$i] = $total_calls == 0 ? 0 : $npr_value / $total_calls * 100;
                            $arr['nrf_count'][$i] =$nrf_count;
                            $arr['nrf'][$i] = $total_calls == 0 ? 0 : $nrf_count / $total_calls * 100;


                            $arr['revenue'][$i] = $call_cost;
                            $arr['profit'][$i] = $call_cost - $egress_cost ;
                            $arr['margin'][$i] = $call_cost == 0 ? 0 : ($arr['profit'][$i] / $call_cost) * 100;
                            $arr['pp_min'][$i] = $bill_time == 0 ? 0 : $arr['profit'][$i] / ($bill_time / 60);
                            $arr['pp_k_calls'][$i] = $not_zero_calls == 0 ? 0 : $arr['profit'][$i] / $not_zero_calls  * 1000;
                            $arr['ppka'][$i] = $total_calls == 0 ? 0 : $arr['profit'][$i] / $total_calls  * 1000;
//                            }

                            //egress

                            //show_inter_intra
                            $arr['inter_cost'][$i] = $inter_cost;
                            $arr['intra_cost'][$i] = $intra_cost;
                            $arr['local_cost'][$i] = $local_cost;
                            $arr['ij_cost'][$i] = $ij_cost;


                            //rate_display_as
                            if (isset($_GET['rate_display_as']) && $_GET['rate_display_as'] == 1)
                            {
                                $arr['rate'][$i] = $item[0]['actual_rate'];
                                $sum_actual_rate += $item[0]['actual_rate'];
                            } else {
                                $arr['rate'][$i] = $bill_time == 0 ? 0 : $call_cost / ($bill_time / 60);
                            }


                            $i++;
                        endforeach;
                        ?>
                        <!-- //计算数据                       -->
                        <!-- 显示数据                       -->
                        <?php for($ik=0;$ik<$i;$ik++): ?>
                            <tr>
                                <!--group by 的字段-->
                                <?php foreach (array_keys($show_fields) as $key): ?>
                                    <td style="color:#6694E3;">
                                        <?php
                                        if (in_array($key,array('ingress_client_id','egress_client_id','ingress_id','egress_id'))){
                                            echo $arr[$key][$ik] ? $arr[$key][$ik] : '~';
                                        }else{
                                            echo $arr[$key][$ik];
                                        } ?>
                                    </td>
                                <?php endforeach; ?>
                                <!--//group by 的字段-->

                                <td><?php echo number_format($arr['asr'][$ik], 2); ?>%</td>
                                <td><?php echo number_format($arr['acd'][$ik], 2); ?></td>
                                <td><?php echo number_format($arr['pdd'][$ik], 2); ?></td>
                                <!--                                <td>--><?php //echo number_format($arr['ner'][$ik], 2); ?><!--%</td>-->
                                <!--ingress-->
                                <?php if ($type == '1'): ?>
                                    <td><?php echo number_format($arr['npr_count'][$ik], 0); ?></td>
                                    <td><?php echo number_format($arr['npr'][$ik], 2); ?>%</td>
                                    <td><?php echo number_format($arr['nrf_count'][$ik], 0); ?></td>
                                    <td><?php echo number_format($arr['nrf'][$ik], 2); ?>%</td>
                                <?php endif; ?>
                                <td><?php echo number_format($arr['sd_count'][$ik], 0); ?></td>
                                <td><?php echo number_format($arr['sdp'][$ik], 2); ?>%</td>
                                <td><?php echo number_format($arr['revenue'][$ik], 2); ?></td>
                                <td><?php echo number_format($arr['profit'][$ik], 2); ?></td>
                                <td><?php echo number_format($arr['margin'][$ik], 2); ?>%</td>
                                <td><?php echo number_format($arr['pp_min'][$ik], 6); ?></td>
                                <td><?php echo number_format($arr['pp_k_calls'][$ik], 6); ?></td>
                                <td><?php echo number_format($arr['ppka'][$ik], 6); ?></td>
                                <!--//ingress-->
                                <td><?php echo number_format($arr['limited'][$ik], 2); ?></td>

                                <td><?php echo number_format($arr['duration'][$ik], 2); ?></td>
                                <td><?php echo number_format($arr['bill_time'][$ik], 2); ?></td>

                                <td><?php echo number_format($arr['call_cost'][$ik], 5); ?></td>

                                <!--                                    --><?php //if (isset($_GET['show_inter_intra'])): ?>
                                <td><?php echo number_format($arr['inter_cost'][$ik], 5); ?></td>
                                <td><?php echo number_format($arr['intra_cost'][$ik], 5); ?></td>
                                <td><?php echo number_format($arr['local_cost'][$ik], 5); ?></td>
                                <td><?php echo number_format($arr['ij_cost'][$ik], 5); ?></td>
                                <!--                                    --><?php //endif; ?>

                                <td><?php echo number_format($arr['rate'][$ik],4) ?></td>

                                <td><?php echo number_format($arr['total_calls'][$ik]); ?></td>
                                <td><?php echo number_format($arr['not_zero_calls'][$ik]); ?></td>
                                <td><?php echo number_format($arr['busy_calls'][$ik]); ?></td>
                            </tr>
                        <?php endfor; ?>
                        <!-- //显示数据                       -->
                        <!-- 显示count数据                       -->

                        </tbody>
                        <?php
                        $count_group = count($show_fields);
                        if ($count_group && count($data)):
                            //公共数据
                            $total_abr = $sum_total_calls == 0 ? 0 : $sum_not_zero_calls / $sum_total_calls * 100;
//                            $sum__asr = $sum_busy_calls + $sum_cancel_calls + $sum_not_zero_calls;
                            $total_asr = $sum_total_calls == 0 ? 0 : $sum_not_zero_calls / $sum_total_calls * 100;
                            $total_acd = $sum_not_zero_calls == 0 ? 0 : $sum_duration / $sum_not_zero_calls / 60;
                            $total_aloc = $total_asr * $total_acd;
                            $total_pdd = $sum_total_final_calls == 0 ? 0 : $sum_pdd / $sum_total_final_calls;
                            $total_ner = $sum_total_calls == 0 ? 0 : $sum_ner_count / $sum_total_calls * 100;
                            $total_duration = $sum_duration / 60;
                            $total_bill_time = $sum_bill_time / 60;
                            $total_call_cost = $sum_call_cost;
                            $total_total_calls = $sum_total_calls;
                            $total_not_zero_calls = $sum_not_zero_calls;
                            $total_success_calls = $sum_success_calls;
                            $total_busy_calls = $sum_busy_calls;

                            $total_limited = $sum_cps_limit == 0 ? 0 : $sum_call_limit / $sum_cps_limit;
                            $total_sd_count = $sum_sd_count;
                            $total_sdp = $sum_not_zero_calls == 0 ? 0 : ($sum_sd_count / $sum_not_zero_calls) * 100;


                            //ingress
//                            if ($type == 1){
                            $total_npr_count = $sum_npr_value;
                            $total_npr = $sum_total_calls == 0 ? 0 : $sum_npr_value / $sum_total_calls * 100;
                            $total_nrf_count = $sum_nrf_count;
                            $total_nrf = $sum_total_calls == 0 ? 0 : $sum_nrf_count / $sum_total_calls * 100;
                            $total_revenue = $sum_call_cost;
                            $total_profit = $sum_call_cost - $sum_egress_cost ;
                            $total_margin = $sum_call_cost == 0 ? 0 : ($total_profit / $sum_call_cost) * 100;
                            $total_pp_min = $sum_bill_time == 0 ? 0 : $total_profit / ($sum_bill_time / 60);
                            $total_pp_k_calls = $sum_not_zero_calls == 0 ? 0 : $total_profit / $sum_not_zero_calls * 1000;
                            $total_ppka = $sum_total_calls == 0 ? 0 : $total_profit / $sum_total_calls * 1000;
//                            }

                            //egress

                            //show_inter_intra
                            $total_inter_cost = $sum_inter_cost;
                            $total_intra_cost = $sum_intra_cost;
                            $total_local_cost = $sum_local_cost;
                            $total_ij_cost = $sum_ij_cost;

                            //rate_display_as
                            if (isset($_GET['rate_display_as']) && $_GET['rate_display_as'] == 1)
                            {
                                $total_rate = $sum_actual_rate;
                            } else {
                                $total_rate = $sum_bill_time == 0 ? 0 : $sum_call_cost / ($sum_bill_time / 60);
                            }
                            ?>
                            <tfoot>
                            <tr style="color:#000;">
                                <td >Total:</td>
                                <?php
                                $count  = count($show_fields);
                                echo str_repeat('<td ></td>',$count-1);
                                ?>
                                <td><?php echo number_format($total_asr, 2); ?>%</td>
                                <td><?php echo number_format($total_acd, 2); ?></td>
                                <td><?php echo number_format($total_pdd, 2); ?></td>
                                <!--                                <td>--><?php //echo number_format($total_ner, 2); ?><!--%</td>-->

                                <?php if ($type == '1'): ?>
                                    <td><?php echo number_format($total_npr_count, 0); ?></td>
                                    <td><?php echo number_format($total_npr, 2); ?>%</td>
                                    <td><?php echo number_format($total_nrf_count, 0); ?></td>
                                    <td><?php echo number_format($total_nrf, 2); ?>%</td>
                                <?php endif; ?>
                                <td><?php echo number_format($total_sd_count, 0); ?></td>
                                <td><?php echo number_format($total_sdp, 2); ?>%</td>
                                <td><?php echo number_format($total_revenue, 2); ?></td>
                                <td><?php echo number_format($total_profit, 2); ?></td>
                                <td><?php echo number_format($total_margin, 2); ?>%</td>
                                <td><?php echo number_format($total_pp_min, 6); ?></td>
                                <td><?php echo number_format($total_pp_k_calls, 6); ?></td>
                                <td><?php echo number_format($total_ppka, 6); ?></td>
                                <td><?php echo number_format($total_limited, 2); ?></td>


                                <td><?php echo number_format($total_duration, 2); ?></td>
                                <td><?php echo number_format($total_bill_time, 2); ?></td>

                                <td><?php echo number_format($total_call_cost, 5); ?></td>

                                <!--                                        --><?php //if (isset($_GET['show_inter_intra'])): ?>
                                <td><?php echo number_format($total_inter_cost, 5); ?></td>
                                <td><?php echo number_format($total_intra_cost, 5); ?></td>
                                <td><?php echo number_format($total_local_cost, 5); ?></td>
                                <td><?php echo number_format($total_ij_cost, 5); ?></td>
                                <!--                                        --><?php //endif; ?>

                                <td><?php echo number_format($total_rate,4) ?></td>

                                <td><?php echo number_format($total_total_calls); ?></td>
                                <td><?php echo number_format($total_not_zero_calls); ?></td>
                                <td><?php echo number_format($total_busy_calls); ?></td>
                            </tr>
                            </tfoot>
                        <?php endif; ?>
                        <!-- //显示count数据                       -->

                    </table>
                    <div class="separator"></div>
                </div>
            <?php endif; ?>
            <?php echo $form->create('Cdr', array('class'=>'scheduled_report_form','type' => 'get', 'url' => "/reports_db/summary/{$type}", 'onsubmit' => "if($('select[name=show_type]').val() == 0) loading();")); ?>
            <input type="hidden" name="last_group_fields_count" value="<?php echo count($show_fields); ?>" />
            <?php echo $this->element('report_db/cdr_report/select_fieldset',array('is_summary' => true)); ?>
            <?php echo $form->end(); ?>

        </div>
    </div>
</div>
<script type="text/javascript">
    setTimeout(function(){
        // make common selected fields
        $('.ColVis_collection > button').on('click', function(){
            let index = $(this).index();
            let checked = $(this).find('input[type="checkbox"]').is(':checked');
            $('#query-fields').find('option:eq('+index+')').attr('selected', checked);
        })
    }, 1000);

    $.last_running_function = function(){
        var selected_arr = <?php echo json_encode($select_show_fields);?>;
        selected_arr = eval(selected_arr);
        $('.ColVis_radio').find('input[checked="checked"]').click();
        $("#query-fields").val('');
        var button = $('.ColVis_collection button');

        $.each(selected_arr,function(i,v){
            var is_checked = $(this).find(":checkbox").is(":checked");
            $("#query-fields").find('option').eq(v).attr('selected','selected');
            $('.ColVis_radio').find('input[type="checkbox"]').eq(v).click();

            if($('.dynamicTable').parent().hasClass('overflow_x')){

                var pwidth = $('.dynamicTable').parent().width();
                var twidth = $('.dynamicTable').width();
                if((twidth - pwidth) > 10){
                    $('.dynamicTable').width(pwidth);
                }
            }

        });
    }

    $('.scheduled_report_form').find('#formquery').on('click', function(e){
        e.preventDefault();
        var m_selected_cols = [];
        $('.ColVis_radio').find('input').each(function(i,v){
            if($(this).is(':checked')){
                m_selected_cols[i] = 1;
            }else{
                m_selected_cols[i] = 0;
            }
        });
        m_selected_cols = m_selected_cols;
        var new_in = "<input type='hidden' name='m_selected_cols' value='"+m_selected_cols+"' />";
        $('.scheduled_report_form').append(new_in);
        $('.scheduled_report_form').submit();
    });

    $(document).ready(function(){
        $(window).resize(function() {
           if(screen.width < window.innerWidth){
               $('table.list').width('100%')
           }
        });
    })

</script>
