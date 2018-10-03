<table class="list">
    <thead>
        <tr>
             <?php foreach($show_fields as $field): ?>
             <td><?php echo $replace_fields[$field]; ?></td>
             <?php endforeach; ?>
             <td><?php __('CDR Count'); ?></td>
             <td></td>
             <td><?php __('Duration') ?>(min)</td>
             <td></td>
        </tr>
    </thead>    
    <tbody>
        <?php
            $total_cdr = 0;
            $total_duration = 0;
            foreach($data as $item) { 
               $total_cdr += $item[0]['cdr_count'];
               $total_duration += $item[0]['duration'];
            }
        ?>
        <?php foreach($data as $item): ?>
        <?php if(isset($item[0]['cdr_count']) && isset($item[0]['duration'])): ?>
        <tr>
            <?php foreach(array_keys($show_fields) as $key): ?>
                <td style="color:#6694E3;"><?php echo $item[0][$key]; ?></td>
            <?php endforeach; ?>
            <td><?php echo $item[0]['cdr_count'] ?></td>
            <td>
                <div class="bar">
                        <?php $cdr_per = $total_cdr == 0 ? 0 : round($item[0]['cdr_count'] / $total_cdr * 100, 2) ?>
                        <div style="font-size:1.2em;text-align:center;width: <?php echo $cdr_per; ?>%;">
                                <?php echo $cdr_per; ?>%&nbsp;
                        </div>
                </div>
            </td>
            <td><?php echo round($item[0]['duration'] / 60, 2) ?></td>
            <td>
                <div class="bar">
                        <?php $dur_per = $total_duration == 0 ? 0 :  round($item[0]['duration'] / $total_duration * 100, 2) ?>
                        <div style="font-size:1.2em;text-align:center;width: <?php echo $dur_per; ?>%;">
                                <?php echo $dur_per; ?>%&nbsp;
                        </div>
                </div>
            </td>
        </tr>
        <?php endif; ?>
        <?php endforeach; ?>
        <?php
            $count_group = count($show_fields);
            if($count_group && count($data)):
        ?>
        <tr style="color:#000;">
            <td colspan="<?php echo $count_group; ?>">Total:</td>
            <td><?php echo $total_cdr ?></td>
            <td>
            </td>
            <td><?php echo round($total_duration / 60, 2) ?></td>
            <td>
            </td>
        <?php
            endif;
        ?>
    </tbody>

</table>
