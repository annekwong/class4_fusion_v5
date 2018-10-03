<script src="<?php echo $this->webroot ?>js/ajaxTable.js" type="text/javascript"></script>

<ul class="breadcrumb">
    <li><?php __('You are here'); ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Monitoring') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('No Alternative Route Report') ?></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('No Alternative Route Report') ?></h4>
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
            $element_arr['Priority Trunk'] = Array('url' => 'alerts/priority_report', 'icon' => 'retweet_2');
            $element_arr['No Alternative Trunk Route'] = Array('url' => 'alerts/alternative_route_report', 'active' => true, 'icon' => 'spade');
            $element_arr['No Egress Trunk Route'] = Array('url' => 'alerts/no_destination_report', 'icon' => 'pin');
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
                
                <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
                   
                    <thead>
                        <tr>

                            <th ><?php echo __('name', true); ?> </th>
                            <th ><?php echo __('code', true); ?> </th>
                            <th > <?php echo __('Type', true); ?>  </th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php
//var_dump($name_join_arr['route']);
                        for ($i = 0; $i < $loop; $i++) {
                            ?>
                            <tr class="row-1">

                                <td><?php echo $mydata[$i][0]['name']; ?>			
                                </td>
   
                                </td>	
                                <td><?php echo $mydata[$i][0]['digits'] ?>
                                </td>	
                                <td class="last">
                                    <?php echo $mydata[$i][0]['type'] ?>
                                </td>		
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