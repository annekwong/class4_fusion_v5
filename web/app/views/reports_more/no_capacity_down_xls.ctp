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
        <th colspan="2"><?php __('Carrier Limit')?></th>
        <th colspan="2"><?php __('Trunk Limit')?></th>
        <th rowspan="2"><?php __('Call Attempt')?></th>
        <th colspan="4"><?php __('Failure Cause')?></th>
    </tr>
    <tr>
        <th><?php __('Call Limit')?></th>
        <th><?php __('CPS Limit')?></th>
        <th><?php __('Call Limit')?></th>
        <th><?php __('CPS Limit')?></th>
        <th><?php __('Carrier Call Limit')?></th>
        <th><?php __('Carrier CPS Limit')?></th>
        <th><?php __('Trunk Call Limit')?></th>
        <th><?php __('Trunk CPS Limit')?></th>
    </tr>
    </thead>
    <tbody>
    <?php
    $total_call = 0;
    $total_carrier_call_limit = 0;
    $total_carrier_cps_limit = 0;
    $total_trunk_call_limit = 0;
    $total_trunk_cps_limit = 0;
    ?>
    <?php foreach ($data as $item): ?>
        <?php
        $total_call += $item[0]['total_call'];
        $total_carrier_call_limit += $item[0]['carrier_call_limit'];
        $total_carrier_cps_limit += $item[0]['carrier_cps_limit'];
        $total_trunk_call_limit += $item[0]['trunk_call_limit'];
        $total_trunk_cps_limit += $item[0]['trunk_cps_limit'];
        ?>
        <tr>
            <?php foreach (array_keys($show_fields) as $key): ?>
                <td style="color:#6694E3;"><?php echo $item[0][$key]; ?></td>
            <?php endforeach; ?>
            <td><?php echo isset($client_limit[$item[0]['client_id']]) ? $client_limit[$item[0]['client_id']]['name'] : '--'; ?></td>
            <td><?php echo  isset($trunk_limit[$item[0]['trunk_id']]) ? $trunk_limit[$item[0]['trunk_id']]['name'] : '--'; ?></td>
            <td><?php echo  isset($client_limit[$item[0]['client_id']]) ? $client_limit[$item[0]['client_id']]['call_limit'] : '--'; ?></td>
            <td><?php echo  isset($client_limit[$item[0]['client_id']]) ? $client_limit[$item[0]['client_id']]['cps_limit'] : '--'; ?></td>
            <td><?php echo  isset($trunk_limit[$item[0]['trunk_id']]) ? $trunk_limit[$item[0]['trunk_id']]['call_limit'] : '--'; ?></td>
            <td><?php echo  isset($trunk_limit[$item[0]['trunk_id']]) ? $trunk_limit[$item[0]['trunk_id']]['cps_limit'] : '--'; ?></td>
            <td><?php echo $item[0]['total_call']; ?></td>
            <td><?php echo $item[0]['carrier_call_limit']; ?></td>
            <td><?php echo $item[0]['carrier_cps_limit']; ?></td>
            <td><?php echo $item[0]['trunk_call_limit']; ?></td>
            <td><?php echo $item[0]['trunk_cps_limit']; ?></td>
        </tr>
    <?php endforeach; ?>
    <tr>
        <td><?php __('Total'); ?>:</td>
        <?php for($i = 0;$i < count($show_fields)+1; $i++): ?>
            <td></td>
        <?php endfor; ?>
        <td>--</td>
        <td>--</td>
        <td>--</td>
        <td>--</td>
        <td><?php echo $total_call; ?></td>
        <td><?php echo $total_carrier_call_limit; ?></td>
        <td><?php echo $total_carrier_cps_limit; ?></td>
        <td><?php echo $total_trunk_call_limit; ?></td>
        <td><?php echo $total_trunk_cps_limit; ?></td>
    </tr>
    </tbody>
</table>