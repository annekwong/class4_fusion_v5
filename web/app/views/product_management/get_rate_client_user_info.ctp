<table class="table table-bordered table-striped table-white">
    <thead>
    <tr>
        <td class="center">
            <input type="checkbox" onclick="checkAll(this,'use_info_tboby');" checked/>
        </td>
        <!--th><?php __('Client ID'); ?></th-->
        <th><?php __('Client Name'); ?></th>
        <th><?php __('Trunk Name'); ?></th>
        <th><?php __('Enabled'); ?></th>
        <th><?php __('Email'); ?></th>
    </tr>
    </thead>
    <tbody id="use_info_tboby">
    <?php foreach ($client_list as $client_item): ?>
    <tr>
        <td class="center">
            <input type="checkbox" value="<?php echo $client_item[0]['resource_id'] ?>" class="client_checkbox" name="resources[]" checked />
            <input type="hidden" value="<?php echo $client_item[0]['resource_id'].'::'.$client_item[0]['rate_email'] ?>" class="client_checkbox" name="client_emails[]" checked/>
        </td>
        <!--td><?php echo $client_item[0]['client_id'] ?></td-->
        <td><?php echo $client_item[0]['name'] ?></td>
        <td><?php echo $client_item[0]['alias'] ?></td>
        <td><?php echo $client_item[0]['active'] ? 'Yes' : 'No' ?></td>
        <td><?php echo $client_item[0]['rate_email'] ?></td>
    </tr>
    <?php endforeach; ?>
    </tbody>
</table>