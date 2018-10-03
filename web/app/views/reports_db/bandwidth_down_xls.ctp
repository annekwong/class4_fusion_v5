<table class="list" style="color:#4B9100;">
    <thead>
        <tr>
            <?php foreach($show_fields as $field): ?>
            <td><?php echo $replace_fields[$field]; ?></td>
            <?php endforeach; ?>
            <td>Calls</td>
            <td>Incoming Bandwidth</td>
            <td>Outgoing Bandwidth</td>
        </tr>
    </thead>
    <tbody>
        <?php
            $i = 0;
            $arr = array();
            foreach($data as $item):
                $arr['calls'][$i] = $item[0]['calls'];
                $arr['incoming_bandwidth'][$i] = $item[0]['incoming_bandwidth'];
                $arr['outgoing_bandwidth'][$i] = $item[0]['outgoing_bandwidth'];

        ?>
        <tr>
            <?php foreach(array_keys($show_fields) as $key): ?>
            <td style="color:#6694E3;"><?php echo $item[0][$key]; ?></td>
            <?php endforeach; ?>
            <td><?php echo number_format($arr['calls'][$i]); ?></td> 
            <td><?php echo number_format($arr['incoming_bandwidth'][$i]); ?></td>
            <td><?php echo number_format($arr['outgoing_bandwidth'][$i]); ?></td>
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
            <td><?php echo number_format(array_sum($arr['calls'])); ?></td>
            <td><?php echo number_format(array_sum($arr['incoming_bandwidth'])); ?></td>
            <td><?php echo number_format(array_sum($arr['outgoing_bandwidth'])); ?></td>
        </tr>
        <?php
            endif;
        ?>
    </tbody>
</table>