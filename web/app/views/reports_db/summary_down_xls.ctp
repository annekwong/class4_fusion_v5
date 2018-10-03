<?php

$fields = $default_select_fields;
$selected = $default_select_show_fields;

$tmpRes = array();

foreach ($fields as $key => $value) {
    if (in_array($key, $selected)) {
        array_push($tmpRes, $value);
    }
}

$fields = $tmpRes;
?>

<table>
    <thead>
    <tr>
        <?php foreach ($show_fields as $field): ?>
            <th><?php echo  isset($replace_fields[$field]) ? $replace_fields[$field] : $field; ?></th>
        <?php endforeach; ?>

        <?php foreach ($fields as $field): ?>
            <th><?php echo $field; ?></th>
        <?php endforeach; ?>
    </tr>
    </thead>
    <tbody>
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
    $sum_success_calls = 0;
    $sum_busy_calls = 0;
    $sum_pdd = 0;
    $sum_q850_cause_count = 0;
    $sum_total_final_calls = 0;

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


        $duration = isset($item[0]['duration']) ? $item[0]['duration'] : null;
        $bill_time = isset($item[0]['bill_time'])?$item[0]['bill_time'] : null;
        $call_cost = isset($item[0]['call_cost']) ? $item[0]['call_cost'] : null;
        $cancel_calls = isset($item[0]['cancel_calls']) ? $item[0]['cancel_calls'] : null;
        $total_calls = isset($item[0]['total_calls']) ? $item[0]['total_calls'] : null;
        $inter_cost = isset($item[0]['inter_cost']) ? $item[0]['inter_cost'] : null;
        $intra_cost = isset($item[0]['intra_cost']) ? $item[0]['intra_cost'] : null;
        $local_cost = isset($item[0]['local_cost']) ? $item[0]['local_cost'] : null;
        $ij_cost = isset($item[0]['ij_cost']) ? $item[0]['ij_cost'] : null;
        $not_zero_calls = isset($item[0]['not_zero_calls']) ? $item[0]['not_zero_calls'] : null;
        $success_calls = isset($item[0]['success_calls']) ? $item[0]['success_calls'] : null;
        $busy_calls = isset($item[0]['busy_calls']) ? $item[0]['busy_calls'] : null;
        $pdd = isset($item[0]['pdd']) ? $item[0]['pdd'] : null;
        $call_limit = isset($item[0]['call_limit']) ? $item[0]['call_limit'] : null;
        $cps_limit = isset($item[0]['cps_limit']) ? $item[0]['cps_limit'] : null;
        $sd_count = isset($item[0]['sd_count']) ? $item[0]['sd_count'] : null;
        $npr_value = isset($item[0]['npr_value']) ? $item[0]['npr_value'] : null;
        $total_final_calls = isset($item[0]['total_final_calls']) ? $item[0]['total_final_calls'] : null;
        $q850_cause_count = 0;




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
        $sum_success_calls += $success_calls;
        $sum_busy_calls += $busy_calls;
        $sum_pdd += $pdd;
        $sum_q850_cause_count += $q850_cause_count;
        $sum_call_limit += $call_limit;
        $sum_cps_limit += $cps_limit;
        $sum_sd_count += $sd_count;
        $sum_total_final_calls += $total_final_calls;
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
//    if ($type == 1){
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
        $arr['pp_k_calls'][$i] = $total_calls == 0 ? 0 : $arr['profit'][$i] / $not_zero_calls  * 1000;
        $arr['ppka'][$i] = $total_calls == 0 ? 0 : $arr['profit'][$i] / $total_calls  * 1000;
//    }

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
    <?php for($ik=0;$ik<$i;$ik++): ?>
        <?php
        $resultArray = array(
            number_format($arr['asr'][$ik], 2, '.', '')."%",
            number_format($arr['acd'][$ik], 2, '.', ''),
            number_format($arr['pdd'][$ik], 2, '.', ''),
        );

    if ($type == '1') {
        $resultArray[] = number_format($arr['npr_count'][$ik], 0, '.', '');
        $resultArray[] = number_format($arr['npr'][$ik], 2, '.', '').'%';
        $resultArray[] = number_format($arr['nrf_count'][$ik], 0, '.', '');
        $resultArray[] = number_format($arr['nrf'][$ik], 2, '.', '').'%';
    }
        $resultArray[] = number_format($arr['sd_count'][$ik], 2, '.', '');
        $resultArray[] = number_format($arr['sdp'][$ik], 2, '.', '').'%';
        $resultArray[] = number_format($arr['revenue'][$ik], 2, '.', '');
        $resultArray[] = number_format($arr['profit'][$ik], 2, '.', '');
        $resultArray[] = number_format(abs($arr['margin'][$ik]), 2, '.', '').'%';
        $resultArray[] = number_format(abs($arr['pp_min'][$ik]), 6, '.', '');
        $resultArray[] = number_format(abs($arr['pp_k_calls'][$ik]), 6, '.', '');
        $resultArray[] = number_format(abs($arr['ppka'][$ik] ), 6, '.', '');
        $resultArray[] = number_format($arr['limited'][$ik], 2, '.', '');
        $resultArray[] = number_format($arr['duration'][$ik], 2, '.', '');
        $resultArray[] = number_format($arr['bill_time'][$ik], 2, '.', '');
        $resultArray[] = number_format($arr['call_cost'][$ik], 5, '.', '');
        $resultArray[] = number_format($arr['inter_cost'][$ik], 5, '.', '');
        $resultArray[] = number_format($arr['intra_cost'][$ik], 5, '.', '');
        $resultArray[] = number_format($arr['local_cost'][$ik], 5, '.', '');
        $resultArray[] = number_format($arr['ij_cost'][$ik], 5, '.', '');
        $resultArray[] = number_format($arr['rate'][$ik], 4, '.', '');
        $resultArray[] = number_format($arr['total_calls'][$ik], 0, '.', '');
        $resultArray[] = number_format($arr['not_zero_calls'][$ik], 0, '.', '');
        $resultArray[] = number_format($arr['busy_calls'][$ik], 0, '.', '');

        $tmpRes = array();
        for ($j = 0; $j < count($resultArray); $j++) {
            if (in_array($j, $selected)) {
                array_push($tmpRes, $resultArray[$j]);
            }
        }

        $resultArray = $tmpRes;

        ?>

        <tr>
            <?php
            foreach (array_keys($show_fields) as $key): ?>
                <?php
                if (in_array($key,array('group_time', 'ingress_client_id','egress_client_id','ingress_id','egress_id'))){
                    ?>
                    <td><?php echo $arr[$key][$ik] ?  trim($arr[$key][$ik]) : ' '; ?> </td>
                    <?php
                }else{
                    ?>
                    <td><?php echo trim($arr[$key][$ik]) ?> </td>
                    <?php
                } ?>
            <?php endforeach; ?>
            <?php foreach ($resultArray as $item): ?>
                <td><?php echo $item; ?></td>
            <?php endforeach; ?>
        </tr>

    <?php endfor; ?>
    <?php
    $count_group = count($show_fields);
    if ($count_group && count($data)) {
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
//    if ($type == 1) {
        $total_npr_count = $sum_npr_value;
        $total_npr = $sum_total_calls == 0 ? 0 : $sum_npr_value / $sum_total_calls * 100;
        $total_nrf_count = $sum_nrf_count;
        $total_nrf = $sum_total_calls == 0 ? 0 : $sum_nrf_count / $sum_total_calls * 100;
        $total_revenue = $sum_call_cost;
        $total_profit = $sum_call_cost - $sum_egress_cost;
        $total_margin = $sum_call_cost == 0 ? 0 : ($total_profit / $sum_call_cost) * 100;
        $total_pp_min = $sum_bill_time == 0 ? 0 : $total_profit / ($sum_bill_time / 60);
       // $total_pp_k_calls = $sum_total_calls == 0 ? 0 : $total_profit / $sum_total_calls * 1000;
        $total_pp_k_calls = $sum_not_zero_calls == 0 ? 0 : $total_profit / $sum_not_zero_calls * 1000;
         $total_ppka = $sum_total_calls == 0 ? 0 : $total_profit / $sum_total_calls * 1000;
//    }

//egress

//show_inter_intra
        $total_inter_cost = $sum_inter_cost;
        $total_intra_cost = $sum_intra_cost;
        $total_local_cost = $sum_local_cost;
        $total_ij_cost = $sum_ij_cost;

//rate_display_as
        if (isset($_GET['rate_display_as']) && $_GET['rate_display_as'] == 1) {
            $total_rate = $sum_actual_rate;
        } else {
            $total_rate = $sum_bill_time == 0 ? 0 : $sum_call_cost / ($sum_bill_time / 60);
        }
        $tmpRes = array('Total');
        foreach (array_keys($show_fields) as $key):
            if (in_array($key,array('ingress_client_id','egress_client_id','ingress_id','egress_id'))){
                $tmpRes[] = ' ';
            }
        endforeach;

        array_pop($tmpRes);

        $resultArray = array(
            'Total',
            number_format($total_asr, 2, '.', '')."%",
            number_format($total_acd, 2, '.', ''),
            number_format($total_pdd, 2, '.', '')
        );

    if ($type == '1') {
        $resultArray[] = number_format($total_npr_count, 0, '.', '');
        $resultArray[] = number_format($total_npr, 2, '.', '').'%';
        $resultArray[] = number_format($total_nrf_count, 0, '.', '');
        $resultArray[] = number_format($total_nrf, 2, '.', '').'%';
    }
      $resultArray[] = number_format($total_sd_count, 0, '.', '');
      $resultArray[] = number_format($total_sdp, 2, '.', '').'%';
      $resultArray[] = number_format($total_revenue, 2, '.', '');
      $resultArray[] = number_format($total_profit, 2, '.', '');
      $resultArray[] = number_format($total_margin, 2, '.', '').'%';
      $resultArray[] = number_format($total_pp_min, 6, '.', '');
      $resultArray[] = number_format($total_pp_k_calls, 6, '.', '');
      $resultArray[] = number_format($total_ppka, 6, '.', '');

        $resultArray[] = number_format($total_limited, 2, '.', '');
        $resultArray[] = number_format($total_duration, 2, '.', '');
        $resultArray[] = number_format($total_bill_time, 2, '.', '');
        $resultArray[] = number_format($total_call_cost, 5, '.', '');
        $resultArray[] = number_format($total_inter_cost, 5, '.', '');
        $resultArray[] = number_format($total_intra_cost, 5, '.', '');
        $resultArray[] = number_format($total_local_cost, 5, '.', '');
        $resultArray[] = number_format($total_ij_cost, 5, '.', '');
        $resultArray[] = number_format($total_rate, 4, '.', '');
        $resultArray[] = number_format($total_total_calls, 2, '.', '');
        $resultArray[] = number_format($total_not_zero_calls, 2, '.', '');
        $resultArray[] = number_format($total_busy_calls, 2, '.', '');

        // fields by show/hide order
        if (!empty($fields_visibility)) {
            foreach($fields_visibility as $key => $show) {
                if($key && !$show) {
                    unset($resultArray[$key]);
                }else{
                    $resultArray[$key] = str_replace(',', '.', $resultArray[$key]);
                }
            }
        }


        for ($j = 0; $j < count($resultArray); $j++) {
           if (in_array($j, $selected)) {
               array_push($tmpRes, $resultArray[$j+1]);
           }
        }
        $resultArray = $tmpRes;

    }
    ?>
    <?php if ($resultArray && $resultArray[0] == 'Total'): ?>
        <tr>
            <?php foreach ($resultArray as $item): ?>
                <td><?php echo $item; ?></td>
            <?php endforeach; ?>
        </tr>
    <?php endif; ?>
    </tbody>
</table>


