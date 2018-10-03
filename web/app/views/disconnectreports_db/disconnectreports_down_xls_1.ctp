<table border="1" class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary"  style="width: 100%">
        <thead>
            <tr>
                <th><?php __('Carrier'); ?></th>
                <?php foreach ($release_cause_show as $release_cause_show_item): ?>
                    <th><?php echo $release_cause_show_item; ?>&nbsp;&nbsp;%</th>
                <?php endforeach; ?>
                <th><?php __('Others'); ?>&nbsp;&nbsp;%</th>
            </tr>
        </thead>
        <tbody >
            <?php foreach ($client_org['result'] as $carrier_name => $client_org_item): ?>
                <?php $client_org_item['all_count'] = (int) $client_org_item['all_count']; ?>
                <tr>
                    <td><?php echo $carrier_name; ?></td>
                    <?php foreach ($release_cause_show as $release_cause_show_item): ?>
                        <?php
                        $data_count = "0.000";
                        if (isset($client_org_item[$release_cause_show_item]) && $client_org_item['all_count'])
                        {
                            $client_org_item[$release_cause_show_item] = (int) $client_org_item[$release_cause_show_item];
                            $data_count = round($client_org_item[$release_cause_show_item] / $client_org_item['all_count'] * 100, 3);
                        }
                        ?>
                        <td><?php echo $data_count; ?></td>
                    <?php endforeach; ?>
                    <td><?php echo isset($client_org_item['Others']) && $client_org_item['all_count'] ? round($client_org_item['Others'] / $client_org_item['all_count'] * 100, 3) : "0.000"; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
        <thead>
            <tr>
                <th><?php __('Total'); ?></th>
                <?php foreach ($release_cause_show as $release_cause_show_item): ?>
                    <?php
                    $data_count = "0.000";
                    if (isset($client_org['total'][$release_cause_show_item]) && $client_org['total']['all_count'])
                    {
                        $client_org['total'][$release_cause_show_item] = (int) $client_org['total'][$release_cause_show_item];
                        $data_count = round($client_org['total'][$release_cause_show_item] / $client_org['total']['all_count'] * 100, 3);
                    }
                    ?>
                    <th><?php echo $data_count; ?></th>
                <?php endforeach; ?>
                <th><?php echo isset($client_org['total']['Others']) && $client_org['total']['all_count'] ? round($client_org['total']['Others'] / $client_org['total']['all_count'] * 100, 3) : "0.000"; ?></th>
            </tr>
        </thead>
    </table>