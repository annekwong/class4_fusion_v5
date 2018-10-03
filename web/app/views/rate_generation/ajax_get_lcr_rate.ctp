<table class="footable table table-striped tableTools table-bordered  table-white table-primary">
    <thead>
    <tr>
        <th><?php __('Rate Type'); ?></th>
        <?php for($i = 0; $i< $lcr_digit; $i++):?>
            <th><?php echo 'LCR'.($i+1); ?></th>
        <?php endfor;?>

    </tr>
    </thead>
    <?php foreach ($return_arr as $key => $item): ?>
        <tr>
            <td><?php echo ucwords($key); ?></td>
            <?php
            $data_count = 0;
            foreach ($item as $item_key => $item_data):
                if($data_count < $lcr_digit):
                $data_count ++;
                ?>
                <td>
                    <small>
                        <?php echo isset($item_data[0]) && $item_data[0] ? $item_data[0] : '-'; ?><br />
                        <?php echo isset($item_data[1]) && $item_data[1] ? $item_data[1] : '-'; ?>
                    </small>
                </td>
            <?php endif; ?>
            <?php endforeach; ?>
            <?php for ($data_count; $data_count < $lcr_digit; $data_count ++): ?>
                <td>-</td>
            <?php endfor; ?>
        </tr>
    <?php endforeach; ?>
</table>