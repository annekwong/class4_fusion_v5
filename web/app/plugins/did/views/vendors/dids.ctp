<style>
    table input {
        width:100px;
    }
    .parentFormundefined{
        z-index: 9999;
    }
    #container select{width: 100px;}
</style>
<ul class="breadcrumb">
    <li><?php __('You are here'); ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>did/repository"><?php __('Origination') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>did/repository"><?php echo __('Vendors', true); ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>did/repository"><?php echo __('View DIDs', true); ?></a></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Vendor DIDs', true); ?></h4>

</div>
<div class="separator bottom"></div>
<div class="innerLR">
    <div class="widget widget-tabs widget-body-white">
        <div class="widget-body">
            <?php
            if (empty($data))
            {
                ?>
                <h2 class="msg center"><?php echo __('no_data_found') ?></h2>
                <table class="list footable table table-striped tableTools table-bordered  table-white table-primary" style="overflow-x: hidden;display: none;">
                    <thead>
                    <tr>
                        <th>
                            <input type="checkbox" id="selectAll">
                        </th>
                        <th><?php __('DID') ?></th>
                        <th><?php __('DID Vendor') ?></th>
                        <th><?php __('Vendor Billing Rule') ?></th>
                        <th><?php __('DID Client') ?></th>
                        <th><?php __('Client Billing Rule') ?></th>
                        <th><?php __('Created Time') ?></th>
                        <th><?php __('Assigned Time') ?></th>
                        <!--
                        <th><?php __('Country') ?></th>
                        <th><?php __('State') ?></th>
                       <th><?php __('City') ?></th>
                       -->
                        <th><?php __('Action') ?></th>
                    </tr>
                    </thead>

                    <tbody>

                    </tbody>
                </table>
                <?php
            }
            else
            {
                ?>
                <div class="clearfix"></div>
                <div class="overflow_x">
                    <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary" style="overflow: auto;overflow-x: hidden">
                        <thead>
                        <tr>
                            <th><?php __('DID') ?></th>
                            <th><?php __('Billing Rule') ?></th>
                        </tr>
                        </thead>

                        <tbody>
                        <?php
                        foreach ($data as $item)
                        {
                            ?>
                            <tr>
                                <td><?php echo $item[0]['code']; ?></td>
                                <td><?php echo $item[0]['vendor_rule']; ?></td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            <?php } ?>
        </div>
    </div>
</div>