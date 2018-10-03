<style>
    .list1 td{ line-height:2;}
</style>
<ul class="breadcrumb">
    <li><?php __('You are here')?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Exchange Manage') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Edit Transaction Fee') ?></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Edit Transaction Fee') ?></h4>
        
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>
<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-body">
            <div id="container">
                <form method="post" id="addForm">
                    <input type="hidden" value="<?php
                    if (!empty($transaction_fee_items)) {
                        echo $transaction_fee_items[0][0]['transaction_fee_id'];
                    }
                    ?>" id="transaction_fee_id" name="transaction_fee_id" >
                    <table class="form list1 table dynamicTable tableTools table-bordered  table-white">
                        <tr>
                            <td width="50%" style="text-align:right;">
                                <?php __('Use Fee')?>:
                            </td>
                            <td width="50%"><input value="<?php
                    if (!empty($transaction_fee_items)) {
                        echo $transaction_fee_items[0][0]['use_fee'];
                    }
                    ?>" id="use_fee" type="text" name="use_fee" style="width:220px;"></td>
                        </tr>

                        <tr>
                            <td width="50%" style="text-align:right;">
                                <?php __('Transaction Fee')?>:</td>
                            <td width="50%">
                                <select id="trans_id" name="trans_id" style="width:220px;">
                                        <?php
                                        foreach ($service_charge_items as $service_charge_item) {
                                            if ($transaction_fee_items[0][0]['trans_id'] == $service_charge_item[0]['id']) {
                                                ?>
                                            <option selected value="<?php echo $service_charge_item[0]['id']; ?>">
                                            <?php echo $service_charge_item[0]['min_rate'] ?>-<?php echo $service_charge_item[0]['max_rate'] ?>
                                            </option>
                                                <?php
                                            } else {
                                                ?>
                                            <option value="<?php echo $service_charge_item[0]['id']; ?>">
                                            <?php echo $service_charge_item[0]['min_rate'] ?>-<?php echo $service_charge_item[0]['max_rate'] ?>
                                            </option>
        <?php
    }
}
?>
                                </select>
                            </td>
                        </tr>

                        <tr>
                            <td colspan="2" class="center">
                                <br/>
                                <input type="submit" value="<?php __('submit')?>" class="btn btn-primary">
                                <a href="<?php echo $this->webroot; ?>paymentterms/edit_transaction_item/<?php echo $id; ?>"><input type="button" value="<?php __('reset')?>" class="btn btn-default"></a>
                            </td>
                        </tr>
                    </table>
                </form>

            </div>
        </div>
    </div>
</div>



<form method="post" id="addForm">
    <input type="hidden" value="<?php
if (!empty($transaction_fee_items)) {
    echo $transaction_fee_items[0][0]['transaction_fee_id'];
}
?>" id="transaction_fee_id" name="transaction_fee_id" >
    <table class="list1">
        <tr>
            <td >
                <?php __('Use Fee')?>:<input value="<?php
                if (!empty($transaction_fee_items)) {
                    echo $transaction_fee_items[0][0]['use_fee'];
                }
?>" id="use_fee" type="text" name="use_fee" style="width:220px;"></td>
        </tr>

        <tr>
            <td >
                <?php __('Transaction Fee')?>:
                <select id="trans_id" name="trans_id" style="width:220px;">
                    <?php
                    foreach ($service_charge_items as $service_charge_item) {
                        if ($transaction_fee_items[0][0]['trans_id'] == $service_charge_item[0]['id']) {
                            ?>
                            <option selected value="<?php echo $service_charge_item[0]['id']; ?>">
                            <?php echo $service_charge_item[0]['min_rate'] ?>-<?php echo $service_charge_item[0]['max_rate'] ?>
                            </option>
                            <?php
                        } else {
                            ?>
                            <option value="<?php echo $service_charge_item[0]['id']; ?>">
        <?php echo $service_charge_item[0]['min_rate'] ?>-<?php echo $service_charge_item[0]['max_rate'] ?>
                            </option>
        <?php
    }
}
?>
                </select>
            </td>
        </tr>

        <tr>
            <td>
                <br/>
                <input type="submit" value="<?php __('submit')?>" class="in-submit">
                <a href="<?php echo $this->webroot; ?>paymentterms/edit_transaction_item/<?php echo $id; ?>"><input type="button" value="<?php __('reset')?>" class="in-submit"></a>
            </td>
        </tr>
    </table>
</form>
</div>

<script>
    $(function() {
        $("#addForm").submit(function() {
            var transaction_fee_id = $("#transaction_fee_id").val();
            var trans_id = $("#trans_id").val();
            var use_fee = $("#use_fee").val();


            if (use_fee == '') {
                jGrowl_to_notyfy("This Transaction Fee Item can not be empty!", {theme: 'jmsg-error'});
                return false;
            }

            if (use_fee > 100) {
                jGrowl_to_notyfy("This Use Fee can not is greater than 100!", {theme: 'jmsg-error'});
                return false;
            }

            var flag = false;
            $.ajax({
                'url': '<?php echo $this->webroot ?>paymentterms/check_transaction_item1/' + trans_id + "/" + transaction_fee_id + "/" + "<?php echo $transaction_fee_items[0][0]['id'] ?>",
                'type': 'POST',
                'dataType': 'text',
                'data': {},
                'async': false,
                'success': function(data) {
                    if (data == 'no') {
                        flag = false;
                        jGrowl_to_notyfy("This Transaction Fee Item already exists!", {theme: 'jmsg-error'});
                    } else if (data == 'yes') {
                        flag = true;
                    }
                }
            });

            return flag;
        });
    });
</script>



