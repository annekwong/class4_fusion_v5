  <?php if(empty($data)): ?>
  <?php else: ?>
    
 <?php
    $days = array();
    $startdate=strtotime($start);
    $enddate=strtotime($end); 
    $day=round(($enddate-$startdate)/3600/24) ;
    $dt_begin = new DateTime($start);
    for($i=0;$i<$day;$i++) {
        if($i > 0) {
            $dt_begin->modify('+1 days');
        }
        array_push($days, $dt_begin->format('Y-m-d'));
    }
 ?>

    <table class="list">
        
        <thead>
            <tr>
                <td><?php __('Client Name') ?></td>
                <td><?php __('Term Code Name') ?></td>
                <td><?php __('Total(Min)') ?></td>
                <td colspan="4"></td>
                <?php foreach($days as $item): ?>
                <td colspan="3">
                   <?php echo $item; ?>
                </td>
                <?php endforeach; ?>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td><?php __('% Calls < 30s'); ?></td>
                <td><?php __('min count < 30s'); ?></td>
                <td><?php __('% Calls < 6s'); ?></td>
                <td><?php __('min count < 6s'); ?></td>
                <?php for($i=0;$i<$day;$i++) { ?>
                <td><?php __('Billed Time (min)') ?></td>
                <td><?php __('ASR (%)') ?></td>
                <td><?php __('ACD (mins) (min)') ?></td>
                <?php } ?>
            </tr>
        </thead>
        
        <tbody>
            <?php 
                foreach($data as $item):
            ?>
            <tr>
                <td><?php echo $item['client_name']; ?></td>
                <td><?php echo $item['term_code_name']; ?></td>
                <td><?php echo number_format($item['total_time'] / 60, 2); ?></td>
                <td><?php echo $item['calls_30']; ?></td>
                <td><?php echo number_format($item['time_30'] /60, 2); ?></td>
                <td><?php echo $item['calls_6']; ?></td>
                <td><?php echo number_format($item['time_6'] / 60, 2); ?></td>
                <?php foreach($days as $day_item):  ?>
                <?php  if(array_key_exists($day_item, $item['years'])): ?>
                <td><?php echo number_format($item['years'][$day_item]['bill_time'] / 60, 2); ?></td>
                <td><?php echo $item['years'][$day_item]['total_calls'] == 0 ? 0 : number_format($item['years'][$day_item]['not_zero_calls']/$item['years'][$day_item]['total_calls'], 5); ?></td>
                <td><?php echo $item['years'][$day_item]['not_zero_calls'] == 0 ? 0 : number_format($item['years'][$day_item]['total_time']/$item['years'][$day_item]['not_zero_calls'] / 60, 5); ?></td>
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
