<table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
    <thead>
    <tr>
        <th><?php echo __('Carrier Name', true) ?></th>
        <th><?php echo __('Created On', true) ?></th>
        <th><?php echo __('Create By', true) ?></th>
        <th><?php echo __('Last Update On', true)?></th>
    </tr>
    </thead>

    <tbody>
    <?php foreach ($data as $data_item): ?>
        <tr>
            <td>
            <?php if($_SESSION['role_menu']['Template']['carrier_template']['model_w']): ?>
                <a href="<?php echo $this->webroot ?>clients/edit/<?php echo base64_encode($data_item[0]['client_id'])?>"><?php echo $data_item[0]['name']; ?></a>
            <?php else: ?>
                <?php echo $data_item[0]['name']; ?>
            <?php endif;?>
            </td>
            <td><?php echo $data_item[0]['create_time']; ?></td>
            <td><?php echo $data_item[0]['update_by']; ?></td>
            <td><?php echo $data_item[0]['update_at']; ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>