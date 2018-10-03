<ul class="breadcrumb">
    <li><?php echo __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>reports_more/capacity">
        <?php __('Statistics') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot . $this->params['url']['url'] ?>">
        <?php echo $this->pageTitle; ?></a></li>
</ul>
<div class="heading-buttons">
    <h4 class="heading"><?php __('No Capacity Report')?></h4>
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>

<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-head">
            <ul>
                <li <?php if ($type == 0) echo 'class="active"'; ?>>
                    <a href="<?php echo $this->webroot; ?>reports_more/capacity/0"class="glyphicons left_arrow">
                        <i></i>
                        <?php __('Origination')?>
                    </a>
                </li>

                <?php if($all_termination): ?>
                    <li <?php if ($type == 1) echo 'class="active"'; ?>>
                        <a href="<?php echo $this->webroot; ?>reports_more/capacity/1"class="glyphicons right_arrow">
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
                        <th colspan="2"><?php __('Carrier Limit')?></th>
                        <th colspan="2"><?php __('Trunk Limit')?></th>
                        <th rowspan="2"><?php __('Call Attempt')?></th>
                        <th colspan="4"><?php __('Failure Cause')?></th>
                    </tr>
                    <tr>
                        <th><?php __('Call Limit')?></th>
                        <th><?php __('CPS Limit')?></th>
                        <th><?php __('Call Limit')?></th>
                        <th><?php __('CPS Limit')?></th>
                        <th><?php __('Carrier Call Limit')?></th>
                        <th><?php __('Carrier CPS Limit')?></th>
                        <th><?php __('Trunk Call Limit')?></th>
                        <th><?php __('Trunk CPS Limit')?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $total_call = 0;
                    $total_carrier_call_limit = 0;
                    $total_carrier_cps_limit = 0;
                    $total_trunk_call_limit = 0;
                    $total_trunk_cps_limit = 0;
                    ?>
                    <?php foreach ($data as $item): ?>
                        <?php
                        $total_call += $item[0]['total_call'];
                        $total_carrier_call_limit += $item[0]['carrier_call_limit'];
                        $total_carrier_cps_limit += $item[0]['carrier_cps_limit'];
                        $total_trunk_call_limit += $item[0]['trunk_call_limit'];
                        $total_trunk_cps_limit += $item[0]['trunk_cps_limit'];
                        ?>
                        <tr>
                            <?php foreach (array_keys($show_fields) as $key): ?>
                                <td style="color:#6694E3;"><?php echo $item[0][$key]; ?></td>
                            <?php endforeach; ?>
                            <td><?php echo isset($client_limit[$item[0]['client_id']]) ? $client_limit[$item[0]['client_id']]['name'] : '~'; ?></td>
                            <td><?php echo  isset($trunk_limit[$item[0]['trunk_id']]) ? $trunk_limit[$item[0]['trunk_id']]['name'] : '~'; ?></td>
                            <td><?php echo  isset($client_limit[$item[0]['client_id']]) ? $client_limit[$item[0]['client_id']]['call_limit'] : '~'; ?></td>
                            <td><?php echo  isset($client_limit[$item[0]['client_id']]) ? $client_limit[$item[0]['client_id']]['cps_limit'] : '~'; ?></td>
                            <td><?php echo  isset($trunk_limit[$item[0]['trunk_id']]) ? $trunk_limit[$item[0]['trunk_id']]['call_limit'] : '~'; ?></td>
                            <td><?php echo  isset($trunk_limit[$item[0]['trunk_id']]) ? $trunk_limit[$item[0]['trunk_id']]['cps_limit'] : '~'; ?></td>
                            <td><?php echo $item[0]['total_call']; ?></td>
                            <td><?php echo $item[0]['carrier_call_limit']; ?></td>
                            <td><?php echo $item[0]['carrier_cps_limit']; ?></td>
                            <td><?php echo $item[0]['trunk_call_limit']; ?></td>
                            <td><?php echo $item[0]['trunk_cps_limit']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                    <tr>
                        <td><?php __('Total'); ?>:</td>
                        <?php for($i = 0;$i < count($show_fields)+1; $i++): ?>
                            <td></td>
                        <?php endfor; ?>
                        <td>--</td>
                        <td>--</td>
                        <td>--</td>
                        <td>--</td>
                        <td><?php echo $total_call; ?></td>
                        <td><?php echo $total_carrier_call_limit; ?></td>
                        <td><?php echo $total_carrier_cps_limit; ?></td>
                        <td><?php echo $total_trunk_call_limit; ?></td>
                        <td><?php echo $total_trunk_cps_limit; ?></td>
                    </tr>
                    </tfoot>
                </table>
                <div class="separator"></div>
            <?php endif; ?>
            <?php echo $form->create('Cdr', array('type' => 'get', 'url' => "/reports_more/capacity/{$type}", 'onsubmit' => "if($('select[name=show_type]').val() == 0) loading();")); ?>
            <?php echo $this->element('reports_more/form_period'); ?>
            <?php echo $form->end(); ?>

        </div>
    </div>
</div>
<script type="text/javascript">

</script>
