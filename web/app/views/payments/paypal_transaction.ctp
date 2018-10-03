<ul class="breadcrumb">
    <li><?php __('You are here')?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Finance') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Paypal Transaction') ?></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php __('Paypal Transaction')?></h4>
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>
<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        
        <div class="widget-body">
            <div class="filter-bar">
                <form method="get">
                    <!-- Filter -->
                    <div>
                        <label><?php __('Carrier')?>:</label>
                        <select name="carrier" id="carrier_id" >
                            <option value=""></option>
                            <?php foreach ($client_arr as $value) { ?>
                            <option value="<?php  echo $value[0]['client_id']; ?>"><?php  echo $value[0]['name']; ?></option>
                                <?php } ?>
                        </select>
                    </div>
                    <div>
                        <label><?php __('Date')?>:</label>
                        <input type="text" name="start_date" onfocus="WdatePicker({dateFmt: 'yyyy-MM-dd'});" readonly="" value="<?php echo $start_date; ?>" />
                        -<input type="text" name="end_date" onfocus="WdatePicker({dateFmt: 'yyyy-MM-dd'});" readonly="" value="<?php echo $end_date; ?>" />
                    </div>
                    <div>
                        <label><?php __('Payment ID')?>:</label>
                        <input type="text" name="payment_id"  value="<?php echo $payment_id; ?>"  />
                    </div>
                    <div>
                        <button name="submit" class="btn query_btn"><?php __('Query')?></button>
                    </div>
            </div>
            <div class="clearfix"></div>
            <div class="separator bottom row-fluid">
                <div class="pagination pagination-large pagination-right margin-none">
                    <?php echo $this->element('page'); ?>
                </div> 
            </div>
                <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary" id="list_id">
                    <thead>
                        <tr>
                            <th><?php echo $appCommon->show_order('name', __('Carrier', true)) ?></th>
                            <th><?php echo $appCommon->show_order('date', __('Date', true)) ?></th>
                            <th><?php echo $appCommon->show_order('paypal_id', __('Paypal ID', true)) ?></th>
                            <th><?php echo $appCommon->show_order('amount', __('Amount', true)) ?></th>
                            <th><?php echo __('Action') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data as $value){ ?>
                        <tr>
                            <td><?php echo $value[0]['name']; ?></td>
                            <td><?php echo $value[0]['date']; ?></td>
                            <td><?php echo $value[0]['paypal_id']; ?></td>
                            <td><?php echo $value[0]['amount']; ?></td>
                            <td></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>


            <div class="separator bottom row-fluid">
                <div class="pagination pagination-large pagination-right margin-none">
                    <?php echo $this->element('page'); ?>
                </div> 
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
</div>
<script type="text/javascript" >

$(function(){
    
    $("#carrier_id").val('<?php echo $client_id; ?>');
    
})
</script>