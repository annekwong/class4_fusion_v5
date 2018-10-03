<style type="text/css">
    #multiple {display:none;}
</style>


<ul class="breadcrumb">
    <li><?php echo __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Origination') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('LNP Request') ?></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php __('Trunk Management>>LNP Request')?></h4>
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>

<div class="innerLR">
    <div class="widget widget-tabs widget-body-white">
        <div class="widget-head">
            <ul>
                <li class="active" ><a href="<?php echo $this->webroot; ?>did/lnp_request" class="glyphicons left_arrow"><i></i><?php __('Log'); ?></a></li>
                <li><a href="<?php echo $this->webroot; ?>did/lnp_request/push" class="glyphicons right_arrow"><i></i><?php __('Submit'); ?></a></li>
            </ul>
        </div>
        <div class="widget-body">

            <div class="clearfix"></div>
            <div id="container">

                <?php
                if (empty($this->data)):
                    ?>
                    <div class="msg"><?php echo __('no_data_found', true); ?></div>
                    <table class="list" style="display:none;">

                        <thead>
                            <tr>
                                <th><?php __('Name')?></th>
                                <th><?php __('DID Price')?></th>
                                <th><?php __('Channel Price')?></th>
                                <th><?php __('MIN Price')?></th>
                                <th><?php __('Billed Channels')?></th>
                                <th><?php __('Action')?></th>
                            </tr>
                        </thead>

                        <tbody>

                        </tbody>
                    </table>
                <?php else: ?>
                    <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary" id="key_list" >
                        <thead>
                            <tr>
                                <th><?php __('Request Date')?></th>
                                <th><?php __('Request')?> #</th>
                                <th><?php __('Number of DIDs')?></th>
                                <th><?php __('Status')?></th>
                                <th><?php __('Action')?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($this->data as $item): ?>
                                <tr>
                                    <td><?php echo $item['LnpRequest']['request_date']; ?></td>
                                    <td><?php echo $item['LnpRequest']['id']; ?></td>
                                    <td><?php echo $item['LnpRequest']['count']; ?></td>
                                    <td><?php echo $status[$item['LnpRequest']['status']]; ?></td>
                                    <td>
                                        <?php if ($_SESSION['login_type'] == 1): ?>
                                            <a href="<?php echo $this->webroot ?>did/lnp_request/assign/<?php echo $item['LnpRequest']['id']; ?>" title="Confirm">
                                                <i class="icon-wrench"></i>
                                            </a>
                                        <?php endif; ?>
                                        <a href="<?php echo $this->webroot ?>did/lnp_request/get_file/<?php echo $item['LnpRequest']['id']; ?>" title="Get File">
                                            <i class="icon-download-alt"></i>
                                        </a>
                                        <a href="<?php echo $this->webroot ?>did/lnp_request/detail/<?php echo $item['LnpRequest']['id']; ?>" title="View Details">
                                            <i class="icon-list-alt"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <div class="bottom row-fluid">
                        <div class="pagination pagination-large pagination-right margin-none">
                            <?php echo $this->element('xpage'); ?>
                        </div> 
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>



