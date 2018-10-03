<ul class="breadcrumb">
    <li><?php __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Monitoring') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Fraud Detection') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Execution Log Detail') ?></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php __('Matched Conditions') ?></h4>
    <div class="buttons pull-right">
    </div>
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
    <a class="link_back btn btn-icon btn-inverse glyphicons circle_arrow_left" href="<?php echo $this->webroot; ?>detections/fraud_detection_log">
        <i></i><?php __('Back')?>
    </a>
</div>
<div class="clearfix"></div>
<div class="innerLR">

    <div class="widget widget-heading-simple widget-body-white">
        <div class="widget-body">
            <?php if (empty($this->data)): ?>
                <h2 class="msg center"><br/><?php echo __('no_data_found', true); ?></h2>
            <?php else: ?>
                <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">

                  <thead>
                      <tr>
                          <th rowspan="3"><?php __('Block Type') ?> </th>
                          <th rowspan="3"><?php __('Trunks') ?> </th>
                          <th colspan="<?php echo count($block_type_arr) * 2; ?>"><?php __('Condition')?></th>
                          <th colspan="3"><?php __('Action')?></th>
                      </tr>
                      <tr>
                          <?php foreach($block_type_arr as $bock_type):?>
                               <th colspan="2"><?php echo ucwords($bock_type);?></th>
                          <?php endforeach;?>

                          <th ><?php __('Block'); ?></th>
                          <th colspan="2"><?php __('Email'); ?></th>
                      </tr>
                      <tr>
                          <?php foreach($block_type_arr as $bock_type):?>
                              <th><?php __('Limit Value')?></th>
                              <th><?php __('Actual Value')?></th>
                          <?php endforeach;?>

                          <th><?php __('Ingress'); ?></th>
                          <th><?php __('Partner'); ?></th>
                          <th><?php __('Admin'); ?></th>
                      </tr>
                  </thead>
                    <tbody>
                    <?php foreach ($this->data as $data_item): ?>
                        <tr>
                            <td><?php echo isset($block_type_arr[$data_item['FraudDetectionLogDetail']['block_type']]) ? $block_type_arr[$data_item['FraudDetectionLogDetail']['block_type']] : "--"; ?> </td>
                            <td><?php echo $data_item['Resource']['alias']; ?></td>
                              <?php foreach($block_type_arr as $block_type_key => $bock_type):?>
                                <?php if($block_type_key == $data_item['FraudDetectionLogDetail']['block_type']):?>
                                    <td><?php echo $data_item['FraudDetectionLogDetail']['limit_value']; ?></td>
                                    <td><?php echo number_format($data_item['FraudDetectionLogDetail']['actual_value'],2); ?></td>
                                <?php else:?>
                                   <td><?php echo $data_item['FraudDetection'][$limit_values_fields[$block_type_key]]; ?></td>
                                   <td></td>
                                <?php endif;?>
                            <?php endforeach;?>

                            <td><?php echo $data_item['Resource']['alias']; ?></td>
                            <td><?php echo $data_item['FraudDetection']['email_to'] == 2 || $data_item['FraudDetection']['email_to'] == 1 ? 'Yes' : 'No' ?></td>
                            <td><?php echo $data_item['FraudDetection']['email_to'] == 2 || $data_item['FraudDetection']['email_to'] == 0 ? 'Yes' : 'No' ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
                <div class="row-fluid separator">
                    <div class="pagination pagination-large pagination-right margin-none">
                        <?php echo $this->element('xpage'); ?>
                    </div>
                </div>
            <?php endif; ?>
            <div class="clearfix"></div>
        </div>
    </div>
</div>