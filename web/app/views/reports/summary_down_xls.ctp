   <table class="list" style="color:#4B9100;">
        <thead>
            <tr>
                <?php foreach($show_fields as $field): ?>
                <td><?php echo $replace_fields[$field]; ?></td>
                <?php endforeach; ?>
                <td><?php __('ABR')?></td>
                <td><?php __('ASR')?></td>
                <td><?php __('ACD(min)')?></td>
                <td><?php __('ALOC')?></td>
                <td><?php __('PDD(ms)')?></td>
                <td colspan="2"><?php __('Time(min)')?></td>
                <td><?php __('Usage Charge(USA)')?></td>
                <?php if($type == '1'): ?>
                <td><?php __('LRN Charge')?></td>
                <?php endif; ?>
                <td><?php __('Total Cost')?></td>
                <?php if(isset($_GET['show_inter_intra'])): ?>
                <td><?php __('Inter Cost')?></td>
                <td><?php __('Intra Cost')?></td>
                <?php endif; ?>
                <td><?php __('Avg Rate')?></td>
                <?php if($type == '1'): ?>
                <td colspan="5"><?php __('Calls')?></td>
                <?php else: ?>
                <td colspan="4"><?php __('Calls')?></td>
                <?php endif; ?>
            </tr>
            <tr>
                <?php for($i=0;$i<count($show_fields);$i++):?>
                <td>&nbsp;</td>
                <?php endfor; ?>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td><?php __('Total Duration')?></td>
                <td><?php __('Total Billable Time')?></td>
                <td></td>
                <td></td>
                <td></td>
                <?php if(isset($_GET['show_inter_intra'])): ?>
                <td></td>
                <td></td>
                <?php endif; ?>
                <?php if($type == '1'): ?>
                <td></td>
                <?php endif; ?>
                <td><?php __('Total Calls')?></td>
                <td><?php __('Not Zero')?></td>
                <td><?php __('Success Calls')?></td>
                <td><?php __('Busy Calls')?></td>
                <?php if($type == '1'): ?>
                <td><?php __('LRN Calls')?></td>
                <?php endif; ?>
        </thead>
        <tbody>
            <?php
                $i = 0;
                $arr = array();
                foreach($data as $item):
                    $arr['duration'][$i] = $item[0]['duration'];
                    $arr['bill_time'][$i] = $item[0]['bill_time'];
                    $arr['call_cost'][$i] = $item[0]['call_cost'];                    
                    $arr['cancel_calls'][$i] = $item[0]['cancel_calls'];
                    if($type == 1):
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
                   
            ?>
            <tr>
                <?php foreach(array_keys($show_fields) as $key): ?>
                <td style="color:#6694E3;"><?php echo $item[0][$key]; ?></td>
                <?php endforeach; ?>
                <td><?php echo round($arr['total_calls'][$i] == 0 ? 0 : $arr['not_zero_calls'][$i] / $arr['total_calls'][$i] * 100, 2); ?>%</td>
                <td><?php echo ($arr['busy_calls'][$i] + $arr['cancel_calls'][$i] +$arr['not_zero_calls'][$i]) == 0  ? 0 :round($arr['not_zero_calls'][$i] / ($arr['busy_calls'][$i] + $arr['cancel_calls'][$i] +$arr['not_zero_calls'][$i])*100 ,2)?>%</td>
                <td><?php echo round($arr['not_zero_calls'][$i] == 0 ? 0 : $arr['duration'][$i] / $arr['not_zero_calls'][$i] / 60, 2); ?></td>
                <td>
                    <?php
                        echo round((($arr['busy_calls'][$i] + $arr['cancel_calls'][$i] +$arr['not_zero_calls'][$i]) == 0  ? 0 :$arr['not_zero_calls'][$i] / ($arr['busy_calls'][$i] + $arr['cancel_calls'][$i] +$arr['not_zero_calls'][$i])) * ($arr['not_zero_calls'][$i] == 0 ? 0 : $arr['duration'][$i] / $arr['not_zero_calls'][$i] / 60),2);
                    ?>
                    
                    
                </td>
                <td><?php echo round($arr['not_zero_calls'][$i] == 0 ? 0 : $arr['pdd'][$i] / $arr['not_zero_calls'][$i]); ?></td>
                <td><?php echo number_format($arr['duration'][$i] / 60, 2); ?></td>
                <td><?php echo number_format($arr['bill_time'][$i] / 60, 2); ?></td> 
                <td><?php echo number_format($arr['call_cost'][$i], 5); ?></td>
                <?php if($type == '1'): ?>
                <td><?php echo number_format($arr['lnp_cost'][$i], 5); ?></td>
                <td><?php echo number_format($arr['call_cost'][$i] + $arr['lnp_cost'][$i], 5); ?></td>
                <?php else: ?>
                <td><?php echo number_format($arr['call_cost'][$i], 5); ?></td>
                <?php endif; ?>
                <?php if(isset($_GET['show_inter_intra'])): ?>
                <td><?php echo number_format($arr['inter_cost'][$i], 5); ?></td>
                <td><?php echo number_format($arr['intra_cost'][$i], 5); ?></td>
                <?php endif; ?>
                <td><?php echo number_format($arr['bill_time'][$i] == 0 ? 0 : $arr['call_cost'][$i] / ($arr['bill_time'][$i] / 60), 5); ?></td>
                <td><?php echo number_format($arr['total_calls'][$i]); ?></td> 
                <td><?php echo number_format($arr['not_zero_calls'][$i]); ?></td>
                <td><?php echo number_format($arr['success_calls'][$i]); ?></td>
                <td><?php echo number_format($arr['busy_calls'][$i]); ?></td>
                <?php if($type == '1'): ?>
                <td><?php echo number_format($arr['lrn_calls'][$i]); ?></td>
                <?php endif; ?>
            </tr>
            <?php 
                $i++;
                endforeach; 
            ?>
            
            <?php
                $count_group = count($show_fields);
                if($count_group && count($data)):
            ?>
            <tr style="color:#000;">
                <td colspan="<?php echo $count_group; ?>">Total:</td>
                <td><?php echo round(array_sum($arr['total_calls']) == 0 ? 0 : array_sum($arr['not_zero_calls']) / array_sum($arr['total_calls']) * 100, 2); ?>%</td>
                <td><?php echo (array_sum($arr['busy_calls']) + array_sum($arr['cancel_calls']) +array_sum($arr['not_zero_calls'])) == 0  ? 0 :round(array_sum($arr['not_zero_calls']) / (array_sum($arr['busy_calls']) + array_sum($arr['cancel_calls']) +array_sum($arr['not_zero_calls']))*100 ,2)?>%</td>
                <td><?php echo round(array_sum($arr['not_zero_calls']) == 0 ? 0 : array_sum($arr['duration']) / array_sum($arr['not_zero_calls']) / 60, 2); ?></td>
                <td><?php echo round(((array_sum($arr['busy_calls']) + array_sum($arr['cancel_calls']) +array_sum($arr['not_zero_calls'])) == 0  ? 0 :array_sum($arr['not_zero_calls']) / (array_sum($arr['busy_calls']) + array_sum($arr['cancel_calls']) +array_sum($arr['not_zero_calls']))) * (array_sum($arr['not_zero_calls']) == 0 ? 0 : array_sum($arr['duration']) / array_sum($arr['not_zero_calls']) / 60),2); ?></td>
                <td><?php echo round(array_sum($arr['not_zero_calls']) == 0 ? 0 : array_sum($arr['pdd']) / array_sum($arr['not_zero_calls'])); ?></td>
                <td><?php echo number_format(array_sum($arr['duration']) / 60, 2); ?></td>
                <td><?php echo number_format(array_sum($arr['bill_time']) / 60, 2); ?></td>
                <td><?php echo number_format(array_sum($arr['call_cost']), 5); ?></td>
                <?php if($type == '1'): ?>
                <td><?php echo number_format(array_sum($arr['lnp_cost']), 5); ?></td>
                <td><?php echo number_format(array_sum($arr['call_cost']) + array_sum($arr['lnp_cost']), 5); ?></td>
                <?php else: ?>
                <td><?php echo number_format(array_sum($arr['call_cost']), 5); ?></td>
                <?php endif; ?>
                <?php if(isset($_GET['show_inter_intra'])): ?>
                <td><?php echo number_format(array_sum($arr['inter_cost']), 5); ?></td>
                <td><?php echo number_format(array_sum($arr['intra_cost']), 5); ?></td>
                <?php endif; ?>
                <td><?php echo number_format(array_sum($arr['bill_time']) == 0 ? 0 : array_sum($arr['call_cost']) / (array_sum($arr['bill_time']) / 60), 5); ?></td>
                <td><?php echo number_format(array_sum($arr['total_calls'])); ?></td> 
                <td><?php echo number_format(array_sum($arr['not_zero_calls'])); ?></td>
                <td><?php echo number_format(array_sum($arr['success_calls'])); ?></td>
                <td><?php echo number_format(array_sum($arr['busy_calls'])); ?></td>
                <?php if($type == '1'): ?>
                <td><?php echo number_format(array_sum($arr['lrn_calls'])); ?></td>
                <?php endif; ?>
            </tr>
            <?php
                endif;
            ?>
        </tbody>
    </table>