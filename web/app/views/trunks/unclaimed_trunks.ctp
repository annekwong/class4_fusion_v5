<ul class="breadcrumb">
    <li><?php __('You are here'); ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?= $this->webroot?>trunks/unclaimed_trunks"><?php __('Management') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?= $this->webroot?>trunks/unclaimed_trunks"><?php __('Unclaimed Trunks') ?></a></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php __('Unclaimed Trunks') ?></h4>
    <div class="buttons pull-right">

    </div>
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>
<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">

        <div class="widget-body">
            <?php
            $count = count($this->data);
            if (!$count):
                ?>
                <div>
                    <br />
                    <h2 class="msg center"><?php __('No Unclaimed Trunks'); ?></h2>
                </div>
            <?php else: ?>
                <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary default footable-loaded">
                    <thead>
                    <tr>
                        <th><?php __('Trunk'); ?></th>
                        <th><?php __('Last Updated'); ?></th>
                        <th><?php __('Rate Table'); ?></th>
                        <th><?php __('Action'); ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($this->data as $items)
                    {
                        ?>
                        <tr>
                            <td><?php echo $items['Resource']['alias'] ?></td>
                            <td><?php echo $items['Resource']['update_at'] ?></td>
                            <td><?php echo $items['RateTable']['name'] ?></td>
                            <td>
                                <a onclick="return myconfirm('<?php __('sure to delete'); ?>',this)"
                                   href="<?php echo $this->webroot; ?>trunks/delete_trunk/<?php echo base64_encode($items['Resource']['resource_id']); ?>" title="<?php __('Delete'); ?>">
                                    <i class="icon-remove"></i>
                                </a>
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
                <div class="separator">
                    <div class="pagination pagination-large pagination-right margin-none">
                        <?php echo $this->element('xpage'); ?>
                    </div>
                </div>

            <?php endif; ?>
        </div>



        <div class="clearfix"></div>
    </div>
</div>
</div>
