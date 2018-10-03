<ul class="breadcrumb">
    <li><?php __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Statistics') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Consolidated CDR') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Egress Attempt') ?></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php __('Egress Attempt') ?></h4>
    <div class="buttons pull-right">
        <a class="btn btn-icon btn-inverse glyphicons circle_arrow_left" href="javascript:void(0);"onclick="history.back(-1)">
            <i></i>
            <?php __('Back') ?>
        </a>
    </div>
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>
<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">

        <div class="widget-body">
            <?php
            $mydata = $p->getDataArray();
            $loop = count($mydata);
            if (!$loop)
            {
                ?>
                <div class="center msg">
                    <h3><?php echo __('no_data_found') ?></h3>
                </div>
                <?php
            }
            else
            {
                ?>
                <div class="separator bottom row-fluid">
                    <div class="pagination pagination-large pagination-right margin-none">
                        <?php echo $this->element('page'); ?>
                    </div>
                </div>
                <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
                    <thead>
                        <tr>
                            <th><?php __('Call Duration')?></th>
                            <th><?php __('Egress Alias')?></th>
                            <th><?php __('Ingress Alias')?></th>
                            <th><?php __('ORIG DST Number')?></th>
                            <th><?php __('ORIG src Number')?></th>
                            <th><?php __('Orig Call Duration')?></th>
                            <th><?php __('Origination Profile IP')?></th>
                            <th><?php __('PDD(ms)')?></th>
                            <th><?php __('Response From Egress')?></th>
                            <th><?php __('Response TO Ingress')?></th>
                            <th><?php __('Time')?></th>
                            <th><?php __('Trunk Type')?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $trunk_type = array('1' => 'class4', '2' => 'exchange');
                        for ($i = 0; $i < $loop; $i++)
                        {
                            ?>
                            <tr>
                                <td><?php echo $mydata[$i][0]['call_duration']; ?></td>
                                <td><?php echo $mydata[$i][0]['trunk_id_termination']; ?></td>
                                <td><?php echo $mydata[$i][0]['trunk_id_origination']; ?></td>
                                <td><?php echo $mydata[$i][0]['origination_source_number']; ?></td>
                                <td><?php echo $mydata[$i][0]['origination_destination_number']; ?></td>
                                <td><?php echo $mydata[$i][0]['orig_call_duration']; ?></td>
                                <td><?php echo $mydata[$i][0]['origination_destination_host_name']; ?></td>
                                <td><?php echo $mydata[$i][0]['pdd']; ?></td>
                                <td><?php echo $mydata[$i][0]['release_cause_from_protocol_stack']; ?></td>
                                <td><?php echo $mydata[$i][0]['binary_value_of_release_cause_from_protocol_stack']; ?></td>
                                <td><?php echo $mydata[$i][0]['time']; ?></td>
                                <td><?php echo $trunk_type[$mydata[$i][0]['trunk_type']]; ?></td>
                            </tr>

                        <?php } ?>
                    </tbody>
                </table>
                <div class="separator bottom row-fluid">
                    <div class="pagination pagination-large pagination-right margin-none">
                        <?php echo $this->element('page'); ?>
                    </div>
                </div>
            <?php } ?>
            <div class="clearfix"></div>
        </div>

    </div>
</div>

