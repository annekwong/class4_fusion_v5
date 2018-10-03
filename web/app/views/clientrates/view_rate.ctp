<ul class="breadcrumb">
    <li><?php __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Management') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Rate Table') ?></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php __('Rate Table') ?></h4>

    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>
<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-body">

            <div id="container">


                <?php
                if (!empty($p)):
                    $rate_tables = $p->getDataArray();
                    ?>

                    <table id="mytable" class="list footable table table-striped tableTools table-bordered  table-white table-primary">
                        <thead>
                            <tr>
                                <!--<th><?php echo __('name', true); ?></th>
                                <th>Product</th>
                                <th><?php echo __('Code Deck', true); ?></th>
                                <th><?php echo __('Currency', true); ?></th>
                                <th>Type</th>-->
                                <th><?php echo __('Prefix', true); ?></th>
                                <th><?php echo __('Product Name', true); ?></th>
                                <th><?php echo __('Rate Table Name', true); ?></th>
                                <th><?php echo __('Last Sent', true); ?></th>
                                <th><?php echo __('Last Update', true); ?></th>
                                <th><?php echo __('Action', true); ?></th>
                            <tr>
                        </thead>

                        <tbody>
                            <?php foreach ($rate_tables as $rate_table): ?>

                                <tr>
                                    <td><?=$rate_table[0]['tech_prefix']     ?></td>
                                    <td><?=$rate_table[0]['product_name']    ?></td>
                                    <td><?=$rate_table[0]['rate_table_name'] ?></td>
                                    <td><?=$rate_table[0]['last_sent']       ?></td>
                                    <td><?=$rate_table[0]['last_update']     ?></td>
                                    <td data-hide="phone,tablet" class="footable-last-column center"  style="display: table-cell;">
                                        <a title="<?php echo __('View Rates') ?>" style="float:left;margin-left:5px;"
                                           href="<?php echo $this->webroot ?>clientrates/view_rate_detail/<?php echo base64_encode($rate_table[0]['rate_table_id']) ?>?get_back_url=<?php echo base64_encode($this->params['getUrl']) ?>">
                                            <i class="icon-align-justify"></i>
                                        </a>
                                        <?php if($rate_table[0]['file']): ?>
                                            <a target="_blank" href="<?php echo $this->webroot; ?>rates/download_send_rate_file?flg=<?php echo base64_encode($rate_table[0]['rate_send_log_id']) ?>">
                                                <i class="icon-file-text"></i>
                                            </a>
                                        <?php  endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                    <div class="separator row-fluid">
                        <div class="pagination pagination-large pagination-right margin-none">
                            <?php echo $this->element('page'); ?>
                        </div> 
                    </div>  
                <?php else: ?>
                    <h2 style="text-align:center"><?php echo __('no_data_found', true); ?></h2>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>