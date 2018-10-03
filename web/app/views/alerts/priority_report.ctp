<script src="<?php echo $this->webroot ?>js/ajaxTable.js" type="text/javascript"></script>

<ul class="breadcrumb">
    <li><?php __('You are here'); ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Monitoring') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Priority Trunk') ?></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php __('Priority Trunk') ?></h4>
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>

<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-head">
            <?php
            $event_type = array_keys_value($this->params, 'pass.0');
            $element_arr = Array('Disabled Ingress Trunk' => Array('url' => 'alerts/report/1?res_type=1', 'icon' => 'left_arrow'), 'Disabled Egress Trunk' => Array('url' => 'alerts/report/1?res_type=2', 'icon' => 'right_arrow'));
            $element_arr['Problem Ingress Trunk'] = Array('url' => 'alerts/problem_report/1', 'icon' => 'unshare');
            $element_arr['Problem Egress Trunk'] = Array('url' => 'alerts/problem_report/2', 'icon' => 'share');
            $element_arr['Priority Trunk'] = Array('url' => 'alerts/priority_report', 'active' => true, 'icon' => 'retweet_2');
            $element_arr['Alternative Route Trunk'] = Array('url' => 'alerts/alternative_route_report', 'icon' => 'spade');
            $element_arr['No Destination'] = Array('url' => 'alerts/no_destination_report', 'icon' => 'pin');
            echo $this->element('tabs', array('tabs' => $element_arr));
            ?>
        </div>
        <div class="widget-body">

            <?php
            $mydata = $p->getDataArray();
            $loop = count($mydata);
            if (empty($mydata)) {
                ?>
                <h2 class="msg center"><?php echo __('no_data_found', true); ?></h2>
            <?php } else {
                ?>
                <div class="clearfix"></div>
                <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
                    <thead>
                        <tr>
                            <th ><?php echo __('Rule', true); ?></th>
                            <th > <?php echo __('Trunk', true); ?>  </th>
                            <th > <?php echo __('Code'); ?>  </th>
                            <th > <?php echo __('Set Time'); ?>  </th>
                            <th > <?php echo __('Reset Time'); ?>  </th>
                            <th > <?php echo __('Old Priority'); ?>  </th>
                            <th > <?php echo __('New Priority'); ?>  </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        for ($i = 0; $i < $loop; $i++) {
                            ?>
                            <tr class="row-1">

                                <td > <?php echo $mydata[$i][0]['rule_name']; ?>  </td>
                                <td > <?php echo $mydata[$i][0]['resource']; ?>  </td>
                                <td > <?php echo $mydata[$i][0]['code']; ?>  </td>	
                                <td > <?php echo $mydata[$i][0]['event_time']; ?>  </td>
                                <td > <?php echo $mydata[$i][0]['enabled_time']; ?>  </td>
                                <td > <?php echo $mydata[$i][0]['old_priority']; ?>  </td>
                                <td > <?php echo $mydata[$i][0]['new_priority']; ?>  </td>

                            </tr>
    <?php } ?>
                    </tbody>
                </table>
            </div>
            <div>
                <div class="row-fluid">
                <div class="pagination pagination-large pagination-right margin-none">
                    <?php echo $this->element('page'); ?>
                </div> 
            </div>
            <div class="clearfix"></div>

<?php } ?>
        </div>
    </div>
</div>