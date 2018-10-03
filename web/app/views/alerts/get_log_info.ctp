<div class="dialog_form">

    <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
        <thead>
            <tr>
                <th class="footable-first-column expand" data-class="expand"><?php __('Carrier')?></th>
                <th><?php __('Trunk')?></th>
                <th data-hide="phone,tablet" style="display: table-cell;"><?php __('Destination')?></th>
                <th data-hide="phone,tablet" style="display: table-cell;"><?php __('Action Executed')?></th>
                <th data-hide="phone,tablet" class="footable-last-column"  style="display: table-cell;"><?php __('Time')?></th>
            </tr>
        </thead>

        <tbody>
            <?php foreach($result as $item): ?>
            <tr>
                <td class="footable-first-column expand" data-class="expand"><?php echo $item[0]['carrier'] ?></td>
                <td><?php echo $item[0]['trunk'] ?></td>
                <td data-hide="phone,tablet" style="display: table-cell;">
                <?php 
                if(strlen($item[0]['destination']) > 10)
                    echo "<a href='###' full='" .$item[0]['destination'] ."' title='Show All' class='view_code_name'>" . substr($item[0]['destination'], 0 ,10) . "..." . "</a>";
                else
                    echo $item[0]['destination'];
                ?>
                </td>
                <td data-hide="phone,tablet"  style="display: table-cell;">
                <?php 
                    if ($item[0]['event_type'] == 8) {
                        echo 'Email to ' .  $item[0]['email_addr'];
                    }
                ?>
                </td>
                <td data-hide="phone,tablet" class="footable-last-column"  style="display: table-cell;"><?php echo $item[0]['time'] ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    
</div>