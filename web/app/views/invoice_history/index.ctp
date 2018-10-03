
<ul class="breadcrumb">
    <li><?php __('You are here'); ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>invoice_history">
        <?php __('Finance') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>invoice_history">
        <?php echo __('Carrier Invoice History', true); ?></a></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Carrier Invoice History', true); ?></h4>
    
</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
<!--        <a class="link_btn btn btn-icon btn-inverse glyphicons bomb" href="--><?php //echo $this->webroot ?><!--invoice_history/trigger">-->
<!--            <i></i>&nbsp;--><?php //echo __('Trigger', true); ?>
<!--        </a>-->
    </div>
<!--    <div class="clearfix"></div>-->

<div class="innerLR">

    <div class="widget widget-heading-simple widget-body-white">
        <div class="filter-bar">
            <form method="get" id="myform1">
                <input type="hidden" name="filter_client_type" value="1">
                <!-- Filter -->
                <div>
                    <label><?php __('Search')?>:</label>
                    <input type="text" id="search-_q" class="in-search default-value input in-text defaultText in-input" title="<?php __('Search')?>" value="Search" name="search">
                </div>
                <!-- // Filter END -->
                <!-- Filter -->
                <div>
                    <button name="submit" class="btn query_btn"><?php __('Query')?></button>
                </div>
                <!-- // Filter END -->
            </form>
        </div>
        <div class="widget-body">

            <?php
            $data = $p->getDataArray();
            ?>
            <?php if (empty($data)): ?>
                <h2 class="msg center"><?php  echo __('no_data_found') ?></h2>
            <?php else: ?>
                <div class="clearfix"></div>
                <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
                    <thead>
                        <tr>
                            <th><?php echo $appCommon->show_order('name', __('Name', true)) ?></th>
                            <th><?php __('Payment Terms')?></th>
                            <th><?php __('Type')?></th>
                            <th><?php __('Last Invoice Amount')?></th>
                            <th><?php __('Last Billing Period')?></th>
                            <th><?php __('Last Invoice On')?></th>
                            <th><?php __('action') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data as $item): ?>
                            <tr>
                                <td><?php echo $item[0]['name'] ?></td>
                                <td><?php echo $item[0]['payment_term'] ?></td>
                                <td><?php echo isset($item[0]['type']) ? $types[$item[0]['type']] : '' ?></td>
                                <td>
                                <?php if($item[0]['last_invoice_for']): ?>
                                <?php echo isset($item[0]['last_invoice_amount']) ? number_format($item[0]['last_invoice_amount'], 2) : '' ?>
                                 <?php endif; ?>
                                </td>
                                <td><?php echo (isset($item[0]['invoice_start']) && isset($item[0]['invoice_end'])) ? $item[0]['invoice_start'] . ' - ' . $item[0]['invoice_end'] : '' ?></td>
                                <td><?php echo $item[0]['last_invoice_for'] ?></td>
                                <td>
                                    <?php if($item[0]['last_invoice_for']): ?>
                                    <?php if(isset($item[0]['invoice_number'])): ?>
                                        <a title="<?php echo __('Download Latest Invoice') ?>" target="_blank"
                                           href="<?php echo $this->webroot . 'pr/pr_invoices/createpdf_invoice/' . $item[0]['invoice_number'] ?>" >
                                            <i class="icon-file-text"></i>
                                        </a>
                                    <?php endif; ?>
                                    <a target="_blank" href="<?php echo $this->webroot ?>invoice_history/view/<?php echo $item[0]['client_id'] ?>" title="<?php __('View'); ?>">
                                        <i class="icon-list-alt"></i>
                                    </a>
                                    <?php endif; ?>
                                    <a title="<?php __('Edit Auto-Invoice Config') ?>" href="<?php echo $this->webroot; ?>clients/edit/<?php echo base64_encode($item[0]['client_id']); ?>" >
                                        <i class="icon-edit"></i>
                                    </a>
                                    <a title="Stop Auto-Invoice" onclick="return myconfirm('<?php __("Are you sure to stop auto invoice ?"); ?>',this);" href="<?php echo $this->webroot; ?>invoice_history/stop_auto_invoice/<?php echo base64_encode($item[0]['client_id']);?>/<?php echo base64_encode($item[0]['name']);?>" >
                                        <i class="icon-stop"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <div class="row-fluid separator">
                    <div class="pagination pagination-large pagination-right margin-none">
                        <?php echo $this->element('page'); ?>
                    </div> 
                </div>
                <div class="clearfix"></div>
            <?php endif; ?>
        </div>
    </div>
</div>