<ul class="breadcrumb">
    <li><?php __('You are here')?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?= $this->webroot?>invoice_cdr_log"><?php __('Log') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?= $this->webroot?>invoice_cdr_log"><?php echo __('Invoice CDR Log') ?></a></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Invoice CDR Log') ?></h4>

    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>




<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-head">
            <ul class="tabs">
                <li <?php if ($type == 0) echo 'class="active"' ?>>
                    <a class="glyphicons no-js left_arrow" href="<?php echo $this->webroot ?>invoice_cdr_log/index/0">
                        <i></i><?php __('Ingress')?>			
                    </a>
                </li>
                <li <?php if ($type == 1) echo 'class="active"' ?>>
                    <a class="glyphicons no-js right_arrow" href="<?php echo $this->webroot ?>invoice_cdr_log/index/1">
                        <i></i><?php __('Egress')?>			
                    </a>
                </li>
            </ul>
        </div>
        <div class="clearfix"></div>
        
        <div class="widget-body">
            <div class="filter-bar">

            <form action="" method="get">
                <!-- Filter -->

                <!-- // Filter END -->
                <!-- Filter -->
                <div>
                    <label><?php __('Carrier')?>:</label>
                    <select  name="carrier_name" class="in-select select" >
                        <option value=""></option>
                        <?php foreach ($client as   $client_item)
                        {
                            ?>
                            <option value="<?php echo $client_item ?>" <?php if (isset($get_data['carrier_name']) && !strcmp($client_item, $get_data['carrier_name']))
                                {
                                ?>selected="selected"<?php } ?> ><?php echo $client_item ?></option>
<?php } ?>
                    </select>
                </div>
                <!-- // Filter END -->
                <div>
                    <label><?php __('Time')?>:</label>
                    <input id="start_date" class="input in-text wdate " value="<?php if (isset($get_data['time']))
{
    echo $get_data['time'];
} ?>" type="text" readonly="" onfocus="WdatePicker({dateFmt: 'yyyy-MM-dd HH:mm:ss'});" name="time">
                    
                </div>
                <!-- Filter -->

                

                <div>
                    <button name="submit" class="btn query_btn"><?php __('Query')?></button>
                </div>
                <!-- // Filter END -->


            </form>
        </div>
<?php if (empty($this->data)): ?>
                <h2 class="msg center"><br /><?php  echo __('no_data_found') ?></h2>
<?php else: ?>
                <div class="clearfix"></div>
                <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
                    <thead>
                        <tr>
                            <th><?php echo $appCommon->show_order('InvoiceCdrLog.start_time', __('Start Time', true)) ?></th>
                            <th><?php echo $appCommon->show_order('InvoiceCdrLog.end_time', __('End Time', true)) ?></th>
                            <th><?php echo $appCommon->show_order('InvoiceCdrLog.carrier_name', __('Carrier', true)) ?></th>
                            <th><?php __('Attachments')?></th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($this->data as $item): ?>
                            <tr>
                                <td><?php echo $item['InvoiceCdrLog']['start_time']; ?></td>
                                <td><?php echo $item['InvoiceCdrLog']['end_time']; ?></td>
                                <td><?php echo $item['InvoiceCdrLog']['carrier_name']; ?></td>
                                <td>
                                    <a href="<?php echo $this->webroot ?>pr/pr_invoices/cdr_download/<?php echo $item['InvoiceCdrLog']['type'] == 0 ? 'ingress' : 'egress' ?>/<?php echo urlencode(base64_encode($item['InvoiceCdrLog']['invoice_number'])); ?>">
                                        <?php __('Download')?>
                                    </a>
                                </td>
                            </tr>
    <?php endforeach; ?>
                    </tbody>
                </table>
                <div class="row-fluid">
                    <div class="pagination pagination-large pagination-right margin-none">
    <?php echo $this->element('xpage'); ?>
                    </div> 
                </div>
                <div class="clearfix"></div>
<?php endif; ?>
        </div>
    </div>
</div>

