<div class="dialog_form">
        <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
            <thead>
                <tr>
                    <th><?php __('Payment ID')?></th>
                    <th><?php __('Received Time')?></th>
                    <th><?php __('Amount')?></th>
                    <th><?php __('Remain Amount')?></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    foreach ($payments as $payment): 
                        $remain_amount = $payment[0]['amount'] - $payment[0]['used_amount'];
                        if ($remain_amount > 0):
                 ?>
                <tr>
                    <td><?php echo $payment[0]['client_payment_id'] ?></td>
                    <td><?php echo $payment[0]['receiving_time'] ?></td>
                    <td><?php echo number_format($payment[0]['amount'], 5); ?></td>
                    <td><?php echo $remain_amount ?></td>
                    <td><input type="checkbox" name="payment_ids[]" value="<?php echo $payment[0]['client_payment_id']; ?>" /></td>
                </tr>
                <?php
                        endif;
                    endforeach; 
                 ?>
            </tbody>
        </table>
</div>