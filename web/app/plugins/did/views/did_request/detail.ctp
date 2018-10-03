<ul class="breadcrumb">
    <li><?php echo __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Trunk Management') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Request Report Detail') ?></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading">Request Report Detail</h4>
    <div class="buttons pull-right">
        <a href="<?php echo $this->webroot ?>did/did_request/index/<?php echo $type ?>" class="btn btn-icon btn-inverse glyphicons circle_arrow_left">
            <i></i><?php __('Back')?>
        </a>
    </div>
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>

<div class="innerLR">
    <div class="widget widget-tabs widget-body-white">
        <div class="widget-body">

            <div class="clearfix"></div>
            <div id="container">

                <?php
                if (empty($this->data)):
                    ?>
                <div class="msg center"><h3><?php echo __('no_data_found', true); ?></h3></div>

                    <table class="list" style="display:none;">
                        <thead>
                            <tr>
                                <th><?php __('Request')?></th>
                                <th><?php __('DID')?></th>
                                <th><?php __('Status')?></th>
                                <th><?php __('Date Assigned')?></th>
                                <th><?php __('Country')?></th>
                                <th><?php __('Rate Center')?></th>
                                <th><?php __('State')?></th>
                                <th><?php __('LATA')?></th>
                                <th><?php __('Trunk')?></th>
                                <th><?php __('Action')?></th>
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
                                <th><?php __('Request')?></th>
                                <th><?php __('DID')?></th>
                                <th><?php __('Status')?></th>
                                <th><?php __('Date Assigned')?></th>
                                <th><?php __('Country')?></th>
                                <th><?php __('Rate Center')?></th>
                                <th><?php __('State')?></th>
                                <th><?php __('LATA')?></th>
                                <th><?php __('Trunk')?></th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php foreach ($this->data as $item): ?>
                                <tr>
                                    <td><?php echo $item['DidRequest']['id']; ?></td>
                                    <td><?php echo $item['DidRequestDetail']['number']; ?></td>
                                    <td><?php echo $status[$item['DidRequestDetail']['status']]; ?></td>
                                    <td><?php echo $item['DidRequestDetail']['assigned_time']; ?></td>
                                    <td><?php echo $item['DidRespoitory']['country']; ?></td>
                                    <td><?php echo $item['DidRespoitory']['rate_center']; ?></td>
                                    <td><?php echo $item['DidRespoitory']['state']; ?></td>
                                    <td><?php echo $item['DidRespoitory']['lata']; ?></td>
                                    <td><?php echo $item['Resource']['alias']; ?></td>
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
        </div>
    </div>
</div>
