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
              <td><?php __('Client Name'); ?></td>
              <?php foreach($days as $item): ?>
              <td><?php echo $item; ?></td>
              <?php endforeach; ?>
              <td><?php echo __('Total(Min)') ?></td>
          </tr>
      </thead>
      <tbody>
          <?php foreach($data as $item): ?>
          <?php
            $item_total = 0;
          ?>
          <tr>
              <td><?php echo $item[array_rand($item)]['client_name'] ?></td>
              
              <?php
                foreach($days as $day_item) {
                  if(array_key_exists($day_item, $item))
                  {
                    echo '<td>' . round($item[$day_item]['total_time'] / 60, 2) . '</td>';
                    $item_total += $item[$day_item]['total_time'] ;
                  } else {
                    echo '<td>0</td>';
                  }
                }
              ?>
              
              <td><?php echo round($item_total / 60, 2); ?></td>
          </tr>
          <?php endforeach; ?>
          <tr>
              <td><?php __('Total(Min)') ?></td>
              <?php $total_item_total = 0; ?>
              <?php foreach($days as $day_item) : ?>
              <td>
                  <?php
                  $total_item = 0;
                  foreach($data as $item):
                      if(array_key_exists($day_item, $item)) {
                        $total_item += $item[$day_item]['total_time'];
                      } 
                  endforeach; 
                  $total_item_total += $total_item;
                  echo round($total_item / 60, 2);
                  ?>
              </td>
              <?php endforeach;?>
              <td>
                  <?php echo round($total_item_total / 60, 2); ?>
              </td>
          </tr>
      </tbody>
  </table>
    
  <?php endif; ?>
