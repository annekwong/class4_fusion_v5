<ul class="breadcrumb">
    <li><?php __('You are here')?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Agent Portal') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Products') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('View Rates') ?></li>
</ul>
<div class="heading-buttons">
    <h4 class="heading"><?php __('Rates') ?>[ <?php echo $table_name; ?> ]</h4>
    <div class="buttons pull-right">
    </div>
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
    <a class="link_back btn btn-default btn-icon btn-inverse glyphicons circle_arrow_left" href="<?php echo $this->webroot; ?>agent_portal/products">
        <i></i><?php __('Back'); ?></a>
</div>
<div class="clearfix"></div>

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
                                <th><?php echo __('Code', true); ?></th>
                                <?php if($jur_type == 2) : ?>
                                <th><?php echo __('Inter Rate', true); ?></th>
                                <th><?php echo __('Intra Rate', true); ?></th>
                                <th><?php echo __('IJ Rate', true); ?></th>
                                <?php else : ?>
                                <th><?php echo __('Rate', true); ?></th>
                                <?php endif; ?>
                                <th><?php echo __('Effective Date', true); ?></th>
                                <th><?php echo __('Min Time', true); ?></th>
                                <th><?php echo __('Interval', true); ?></th>
                            <tr>
                        </thead>

                        <tbody>
                            <?php foreach ($rate_tables as $rate_table): ?>

                                <tr>
                                    <td><?=$rate_table[0]['code']     ?></td>
                                    <td><?php echo round($rate_table[0]['rate'],6); ?></td>
                                    <?php if($jur_type == '1'): ?>
                                        <td><?php echo round($rate_table[0]['intra_rate'],6); ?></td>
                                        <td><?php echo round($rate_table[0]['inter_rate'],6); ?></td>
                                        <td><?php echo round($rate_table[0]['local_rate'],6); ?></td>
                                    <?php endif; ?>
                                    <td><?=$rate_table[0]['effective_date']    ?></td>
                                    <td><?=$rate_table[0]['min_time']    ?></td>
                                    <td><?=$rate_table[0]['interval']    ?></td>
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