<ul class="breadcrumb">
    <li><?php __('You are here'); ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Tools') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Auto Rate Upload', true); ?></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Auto Rate Upload', true); ?></h4>
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
        <a id="add" class="btn btn-primary btn-icon glyphicons circle_plus" href="<?php echo $this->webroot; ?>rates_management/add_rate_handler">
            <i></i> <?php __('Create New') ?></a>
</div>
<div class="clearfix"></div>
<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="clearfix"></div>
        <div class="widget-head">
            <ul>
                <li>
                    <a class="glyphicons book_open" href="<?php echo $this->webroot; ?>rates_management/index">
                        <i></i>
                        <?php __('Rate Management') ?>
                    </a>
                </li>
                <li class="active">
                    <a class="glyphicons book_open" href="<?php echo $this->webroot; ?>rates_management/rate_handler_list">
                        <i></i>
                        <?php __('Rate Handler List') ?>
                    </a>
                </li>
            </ul>
        </div>
        <div class="widget-body">
            <?php
            if (count($data) == 0)
            {
                ?>
                <br />
                <h2 class="msg center"><?php echo __('no_data_found') ?></h2>
                <?php
            }
            else
            {
                ?>
                <div class="clearfix"></div>
                <table class="list footable table table-striped table-bordered table-white table-primary">
                    <thead>
                        <tr>
                            <th><?php __('Name') ?></th>
                            <th><?php __('Rate Delivery From') ?></th>
                            <th><?php __('Rate Delivery To') ?></th>
                            <th><?php __('Sys Alias') ?></th>
                            <th><?php __('Rate Table') ?></th>
                            <th><?php __('Action') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data as $item): ?>
                            <tr>
                                <td><?php echo $item['RateHandler']['name'] ?></td>
                                <td><?php echo $item['RateHandler']['rate_delivery_from'] ?></td>
                                <td><?php echo $item['RateHandler']['rate_delivery_to'] ?></td>
                                <td></td>
                                <td><?php echo $item['RateHandler']['rate_table_name'] ?></td>
                                <td>
                                    <a  href="<?php echo $this->webroot; ?>rates_management/edit_rate_handler/<?php echo base64_encode($item['RateHandler']['id']) ?>">
                                        <i class="icon-edit"></i>
                                    </a>
                                    <a onclick="return myconfirm('Are you sure to delete this?',this);" href="<?php echo $this->webroot; ?>rates_management/delete_rate_handler/<?php echo base64_encode($item['RateHandler']['id']) ?>">
                                        <i class="icon-remove"></i> 
                                    </a>
                                </td>
                            </tr>

                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php } ?>
        </div>
    </div>
</div>


