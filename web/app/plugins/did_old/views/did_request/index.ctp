<ul class="breadcrumb">
    <li><?php echo __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Trunk Management') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Request Report') ?></li>
</ul>

<div class="heading-buttons">
    <h1><?php echo __('Request Report') ?></h1>
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>

<div class="innerLR">
    <div class="widget widget-tabs widget-body-white">
        <div class="widget-head">
            <ul>
                <li <?php if (!strcmp($current, 'active')) { ?> class="active"  <?php } ?>><a href="<?php echo $this->webroot; ?>did/did_request/index/active" class="glyphicons left_arrow"><i></i><?php __('Billing Rule'); ?></a></li>
                <li <?php if (!strcmp($current, 'complete')) { ?> class="active"  <?php } ?>><a href="<?php echo $this->webroot; ?>did/did_request/index/complete" class="glyphicons right_arrow"><i></i><?php __('Special Code'); ?></a></li>
            </ul>
        </div>
        <div class="widget-body">

            <div class="clearfix"></div>
            <div id="container">

                <?php
                if (empty($this->data)):
                    ?>
                    <div class="msg center">
                        <br />
                        <h3>
                            <?php echo __('no_data_found', true); ?>
                        </h3>
                    </div>

                    <table class="list" style="display:none;">
                        <thead>
                            <tr>
                                <td><?php __('Request')?></td>
                                <td><?php __('User Name')?></td>
                                <td><?php __('Order Date')?></td>
                                <td><?php __('Action')?></td>
                            </tr>
                        </thead>

                        <tbody>

                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="separator bottom row-fluid">
                        <div class="pagination pagination-large pagination-right margin-none">
                            <?php echo $this->element('xpage'); ?>
                        </div> 
                    </div>
                    <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary" id="key_list" >
                        <thead>
                            <tr>
                                <td><?php __('Request')?></td>
                                <td><?php __('User Name')?></td>
                                <td><?php __('Order Date')?></td>
                                <td><?php __('Action')?></td>
                            </tr>
                        </thead>

                        <tbody>
                            <?php foreach ($this->data as $item): ?>
                                <tr>
                                    <td><?php echo $item['DidRequest']['id']; ?></td>
                                    <td><?php echo $item['User']['name']; ?></td>
                                    <td><?php echo $item['DidRequest']['created_time']; ?></td>
                                    <td>
                                        <?php if ($_SESSION['login_type'] == 1 && $current == 'active'): ?>
                                            <a href="<?php echo $this->webroot ?>did/did_request/assign/<?php echo $item['DidRequest']['id']; ?>/<?php echo $current; ?>" title="Confirm">
                                                <i class="icon-wrench"></i>
                                            </a>
                                        <?php endif; ?>
                                        <a href="<?php echo $this->webroot ?>did/did_request/email/<?php echo $item['DidRequest']['id']; ?>/<?php echo $current; ?>" title="Email Me">
                                            <i class="icon-envelope"></i>
                                        </a>
                                        <a href="<?php echo $this->webroot ?>did/did_request/detail/<?php echo $item['DidRequest']['id']; ?>/<?php echo $current; ?>" title="View Details">
                                            <i class="icon-list-alt"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <div class="separator bottom row-fluid">
                        <div class="pagination pagination-large pagination-right margin-none">
                            <?php echo $this->element('xpage'); ?>
                        </div> 
                    </div>
                <?php endif; ?>
            </div>

