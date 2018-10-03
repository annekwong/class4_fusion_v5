<table  style="color:#4B9100;">
    <thead>

    <tr>
        <!--group by 的字段-->
        <?php foreach ($show_fields as $field): ?>
            <th  rowspan="2"><?php echo $replace_fields[$field]; ?></th>
        <?php endforeach; ?>
        <!--//group by 的字段-->
        <?php if($type): ?>
            <th  rowspan="2"><?php __('Egress Carrier')?></th>
            <th  rowspan="2"><?php __('Egress Trunk')?></th>
        <?php else: ?>
            <th  rowspan="2"><?php __('Ingress Carrier')?></th>
            <th  rowspan="2"><?php __('Ingress Trunk')?></th>
        <?php endif; ?>
        <th rowspan="2"><?php __('Call Attempt')?></th>
        <th colspan="4"><?php __('Failure Cause')?></th>
    </tr>
    <tr>
        <th><?php __('No Capacity')?></th>
        <th><?php __('No Profitable Route')?></th>
        <th><?php __('Code Block')?></th>
        <th><?php __('Trunk Block')?></th>
    </tr>
    </thead>
    <tbody>
    <?php
    $total_call = 0;
    $total_no_capacity = 0;
    $total_no_profitable_route = 0;
    $total_code_block = 0;
    $total_egress_trunk_block = 0;
    ?>
    <?php foreach ($data as $item): ?>
        <?php
        $total_call += $item[0]['total_call'];
        $total_no_capacity += $item[0]['no_capacity'];
        $total_no_profitable_route += $item[0]['no_profitable_route'];
        $total_code_block += $item[0]['code_block'];
        $total_egress_trunk_block += $item[0]['egress_trunk_block'];
        ?>
        <tr>
            <?php foreach (array_keys($show_fields) as $key): ?>
                <td style="color:#6694E3;"><?php echo $item[0][$key]; ?></td>
            <?php endforeach; ?>
            <td><?php echo isset($client_info[$item[0]['client_id']]) ? $client_info[$item[0]['client_id']] : '--'; ?></td>
            <td><?php echo  isset($trunk_info[$item[0]['trunk_id']]) ? $trunk_info[$item[0]['trunk_id']] : '--'; ?></td>
            <td><?php echo $item[0]['total_call']; ?></td>
            <td><?php echo $item[0]['no_capacity']; ?></td>
            <td><?php echo $item[0]['no_profitable_route']; ?></td>
            <td><?php echo $item[0]['code_block']; ?></td>
            <td><?php echo $item[0]['egress_trunk_block']; ?></td>
        </tr>
    <?php endforeach; ?>
    <tr>
        <td><?php __('Total'); ?>:</td>
        <?php for($i = 0;$i < count($show_fields)+1; $i++): ?>
            <td></td>
        <?php endfor; ?>
        <td><?php echo $total_call; ?></td>
        <td><?php echo $total_no_capacity; ?></td>
        <td><?php echo $total_no_profitable_route; ?></td>
        <td><?php echo $total_code_block; ?></td>
        <td><?php echo $total_egress_trunk_block; ?></td>
    </tr>
    </tbody>
</table>