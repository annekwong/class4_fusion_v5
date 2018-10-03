<ul class="breadcrumb">
    <li><?php __('You are here')?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Trunk Management') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('DID Listing') ?></li>
</ul>

<div class="heading-buttons">
    <h1><?php echo __('DID Listing') ?></h1>

    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>
<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-body">

            <div id="container">
                <?php //echo $this->element("did_client_tab", array('active' => 'listing'))?>
                <?php
                if (empty($this->data)):
                    ?>
                    <div class="msg center">
                        <br />
                        <h3><?php echo __('no_data_found', true); ?>
                        </h3>
                    </div>
                    <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary" style="display:none;">
                        <thead>
                            <tr>
                                <th><?php __('DID')?></th>
                                <th><?php __('Egress Trunk')?></th>
                                <th><?php __('Created Time')?></th>
                                <th><?php __('Assigned Time')?></th>
                                <th><?php __('Action')?></th>
                            </tr>
                        </thead>

                        <tbody>

                        </tbody>
                    </table>
                <?php else: ?>
                    <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
                        <thead>
                            <tr>
                                <th><?php __('DID')?></th>
                                <th><?php __('Egress Trunk')?></th>
                                <th><?php __('Created Time')?></th>
                                <th><?php __('Assigned Time')?></th>
                                <!--<th>Action</th>-->
                            </tr>
                        </thead>

                        <tbody>
                            <?php foreach ($this->data as $item): ?>
                                <tr>
                                    <td><?php echo $item['DidAssign']['number']; ?></td>
                                    <td><?php echo $egresses[$item['DidAssign']['egress_id']]; ?></td>
                                    <td><?php echo $item['DidAssign']['created_time']; ?></td>
                                    <td><?php echo $item['DidAssign']['assigned_time']; ?></td>
                                    <!--
                                    <td>
                                    <?php if ($item['DidAssign']['status'] == 0): ?>
                                            <a href="<?php echo $this->webroot ?>did/did_assign/change_status/<?php echo $item['DidAssign']['number']; ?>/1"> 
                                                <img src="<?php echo $this->webroot ?>images/flag-0.png" title="Active">
                                            </a>
                                    <?php elseif ($item['DidAssign']['status'] == 1): ?>
                                            <a href="<?php echo $this->webroot ?>did/did_assign/change_status/<?php echo $item['DidAssign']['number']; ?>/0"> 
                                                <img src="<?php echo $this->webroot ?>images/flag-1.png" title="Unactive">
                                            </a>
                                    <?php endif; ?>
                                    </td>
                                    -->
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <div class="separator row-fluid">
                        <div class="pagination pagination-large pagination-right margin-none">
                            <?php echo $this->element('xpage'); ?>
                        </div> 
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

