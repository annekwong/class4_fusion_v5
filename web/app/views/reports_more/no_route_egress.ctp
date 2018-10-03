<ul class="breadcrumb">
    <li><?php echo __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Statistics') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo $this->pageTitle; ?></li>
</ul>
<div class="heading-buttons">
    <h4 class="heading"><?php __('Summary Report')?></h4>
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>

<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-head">
            <ul>
                <li <?php if ($type == 0) echo 'class="active"'; ?>>
                    <a href="<?php echo $this->webroot; ?>reports_more/no_route/0"class="glyphicons left_arrow">
                        <i></i>
                        <?php __('Origination')?>
                    </a>
                </li>

                <?php if($all_termination): ?>
                    <li <?php if ($type == 1) echo 'class="active"'; ?>>
                        <a href="<?php echo $this->webroot; ?>reports_more/no_route/1"class="glyphicons right_arrow">
                            <i></i>
                            <?php __('Termination')?>
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
        <div class="widget-body">
            <?php if($show_nodata): ?>
                <h1 style="font-size:14px;"><?php __('Report Period')?> <?php echo $start_date ?> — <?php echo $end_date ?></h1>
            <?php endif; ?>
            <?php if(empty($data)): ?>
                <?php if($show_nodata): ?>
                    <div class="msg center"><h2><?php  echo __('no_data_found') ?></h2></div>
                <?php endif; ?>
            <?php else: ?>
                <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary" style="color:#4B9100;">
                    <thead>

                    <tr>
                        <!--group by 的字段-->
                        <?php foreach ($show_fields as $field): ?>
                            <th  rowspan="2"><?php echo $replace_fields[$field]; ?></th>
                        <?php endforeach; ?>
                        <!--//group by 的字段-->
                        <?php if($type): ?>
                            <th  rowspan="2"><?php __('Egress Carrier')?></th>
                            <th  rowspan="2"><?php __('Egress Trunk')?></th>
                        <?php else: ?>
                            <th  rowspan="2"><?php __('Ingress Carrier')?></th>
                            <th  rowspan="2"><?php __('Ingress Trunk')?></th>
                        <?php endif; ?>
                        <th rowspan="2"><?php __('Call Attempt')?></th>
                        <th colspan="4"><?php __('Failure Cause')?></th>
                    </tr>
                    <tr>
                        <th><?php __('No Capacity')?></th>
                        <th><?php __('No Profitable Route')?></th>
                        <th><?php __('Code Block')?></th>
                        <th><?php __('Trunk Block')?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $total_call = 0;
                    $total_no_capacity = 0;
                    $total_no_profitable_route = 0;
                    $total_code_block = 0;
                    $total_egress_trunk_block = 0;
                    ?>
                    <?php foreach ($data as $item): ?>
                        <?php
                        $total_call += $item[0]['total_call'];
                        $total_no_capacity += $item[0]['no_capacity'];
                        $total_no_profitable_route += $item[0]['no_profitable_route'];
                        $total_code_block += $item[0]['code_block'];
                        $total_egress_trunk_block += $item[0]['egress_trunk_block'];
                        ?>
                        <tr>
                            <?php foreach (array_keys($show_fields) as $key): ?>
                                <td style="color:#6694E3;"><?php echo $item[0][$key]; ?></td>
                            <?php endforeach; ?>
                            <td><?php echo isset($client_info[$item[0]['client_id']]) ? $client_info[$item[0]['client_id']] : '--'; ?></td>
                            <td><?php echo  isset($trunk_info[$item[0]['trunk_id']]) ? $trunk_info[$item[0]['trunk_id']] : '--'; ?></td>
                            <td><?php echo $item[0]['total_call']; ?></td>
                            <td><?php echo $item[0]['no_capacity']; ?></td>
                            <td><?php echo $item[0]['no_profitable_route']; ?></td>
                            <td><?php echo $item[0]['code_block']; ?></td>
                            <td><?php echo $item[0]['egress_trunk_block']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <tr>
                        <td><?php __('Total'); ?>:</td>
                        <?php for($i = 0;$i < count($show_fields)+1; $i++): ?>
                            <td></td>
                        <?php endfor; ?>
                        <td><?php echo $total_call; ?></td>
                        <td><?php echo $total_no_capacity; ?></td>
                        <td><?php echo $total_no_profitable_route; ?></td>
                        <td><?php echo $total_code_block; ?></td>
                        <td><?php echo $total_egress_trunk_block; ?></td>
                    </tr>
                    </tbody>
                </table>
                <div class="separator"></div>
            <?php endif; ?>
            <?php echo $form->create('Cdr', array('type' => 'get', 'url' => "/reports_more/no_route/{$type}", 'onsubmit' => "if($('select[name=show_type]').val() == 0) loading();")); ?>
            <?php echo $this->element('reports_more/form_period'); ?>
            <?php echo $form->end(); ?>

        </div>
    </div>
</div>
<script type="text/javascript">

</script>
