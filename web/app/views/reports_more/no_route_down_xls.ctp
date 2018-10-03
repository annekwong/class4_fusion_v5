<table style="color:#4B9100;">
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
        <th><?php __('No Credit')?></th>
        <th><?php __('Trunk Not Found')?></th>
        <th><?php __('Not Routing')?></th>
        <th><?php __('No Capacity')?></th>
    </tr>
    </thead>
    <tbody>
    <?php
    $total_call = 0;
    $total_no_credit = 0;
    $total_trunk_not_found = 0;
    $total_no_route = 0;
    $total_no_capacity = 0;
    ?>
    <?php foreach ($data as $item): ?>
        <?php
        $total_call += $item[0]['total_call'];
        $total_no_credit += $item[0]['no_credit'];
        $total_trunk_not_found += $item[0]['trunk_not_found'];
        $total_no_route += $item[0]['no_route'];
        $total_no_capacity += $item[0]['no_capacity'];
        ?>
        <tr>
            <?php foreach (array_keys($show_fields) as $key): ?>
                <td style="color:#6694E3;"><?php echo $item[0][$key]; ?></td>
            <?php endforeach; ?>
            <td><?php echo isset($client_info[$item[0]['client_id']]) ? $client_info[$item[0]['client_id']] : '--'; ?></td>
            <td><?php echo  isset($trunk_info[$item[0]['trunk_id']]) ? $trunk_info[$item[0]['trunk_id']] : '--'; ?></td>
            <td><?php echo $item[0]['total_call']; ?></td>
            <td><?php echo $item[0]['no_credit']; ?></td>
            <td><?php echo $item[0]['trunk_not_found']; ?></td>
            <td><?php echo $item[0]['no_route']; ?></td>
            <td><?php echo $item[0]['no_capacity']; ?></td>
        </tr>
    <?php endforeach; ?>
    <tr>
        <td><?php __('Total'); ?>:</td>
        <?php for($i = 0;$i < count($show_fields)+1; $i++): ?>
            <td></td>
        <?php endfor; ?>
        <td><?php echo $total_call; ?></td>
        <td><?php echo $total_no_credit; ?></td>
        <td><?php echo $total_trunk_not_found; ?></td>
        <td><?php echo $total_no_route; ?></td>
        <td><?php echo $total_no_capacity; ?></td>
    </tr>
    </tbody>
</table>