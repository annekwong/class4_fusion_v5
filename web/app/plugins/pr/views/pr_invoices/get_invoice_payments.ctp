<div style="padding:10px;">
    <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
        <thead>
            <tr>
                <th><?php __('Payment ID')?></th>
                <th><?php __('Payment Amount')?></th>
                <th><?php __('Paid Amount')?></th>
                <th><?php __('Unpaid Amount')?></th>
                <th><?php __('Payment On')?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($payments as $payment): ?>
            <tr>
                <td>#<?php echo $payment[0]['client_payment_id'] ?></td>
                <td><?php echo $payment[0]['amount'] ?></td>
                <td><?php echo $payment[0]['paid_amount'] ?></td>
                <td><?php echo $total_amount -= $payment[0]['paid_amount']; ?></td>
                <td><?php echo $payment[0]['payment_time'] ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>