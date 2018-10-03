<h1 style="font-size:14px;">Report Period <?php echo $start_date ?> — <?php echo $end_date ?></h1>
<?php if (empty($data)): ?>
    <?php if ($show_nodata): ?><tr /><h2 class="msg center"><?php  echo __('no_data_found') ?></h2><?php endif; ?>
<?php else: ?>
    <div class="overflow_x" style="height: 500px">
        <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
            <thead>
                <tr>
                    <?php foreach ($show_fields as $field): ?>
                        <th><?php echo $replace_fields[$field]; ?></th>
                    <?php endforeach; ?>
                    <th><?php __('Release Cause')?></th>
                    <th><?php __('Count')?></th>
                    <th><?php __('Counts(%)')?></th>
                    <th><?php __('Description')?></th>
                </tr>
                <tr>
                    <?php
                    for ($i = 0; $i < count($show_fields); $i++):
                        ?>
                        <th>&nbsp;</th>
                    <?php endfor; ?>
                </tr>
            </thead>
            <tbody>
    <?php foreach ($data as $data_item): ?>
                    <tr>
                    <?php foreach (array_keys($show_fields) as $key): ?>
                            <td style="color:red;"><?php echo $item[0][$key]; ?></td>
                        <?php endforeach; ?>

                        <td><?php if (!strcmp($type, 'term'))
                {
                    echo $data_item[0]['release_cause_from'] =='NULL' ?  "other" :$data_item[0]['release_cause_from'];
                }
                else
                {
                     echo $data_item[0]['release_cause_to'] =='NULL'?  "other" :$data_item[0]['release_cause_to'];
                } ?></td>
                        <td><?php echo $data_item[0]['total_call']; ?></td>
                        <td><?php echo round($data_item[0]['total_call'] / $total_calls, 3); ?>%</td>
                        <td><?php echo isset($release_cause_list[$data_item[0]['release_cause_route']]) ? $release_cause_list[$data_item[0]['release_cause_route']] : "other"; ?></td>
                    </tr>
    <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif;