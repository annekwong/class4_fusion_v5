<ul class="breadcrumb">
    <li><?php __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Monitoring') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <?php if (empty($this->data)): ?>
        <li><?php __('Rule') ?></li>
    <?php else: ?>
        <li><?php echo "Rule[" . $this->data[0]['AlertRules']['rule_name'] . "]"; ?></li>
    <?php endif; ?>

    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Alert Rules Log Detail') ?></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Matched Condition') ?></h4>

</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
    <a class="link_back btn btn-primary btn-icon btn-inverse glyphicons circle_arrow_left" href="<?php echo $this->webroot; ?>alerts/alert_rules_log">
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
                <div class="overflow_x">
                    <?php foreach ($this->data as $data_item):
                        $condition_fields = ['limit_asr', 'limit_acd', 'limit_pdd', 'limit_revenue', 'limit_profitability'];
                        $minus_count = 0;
                        foreach($condition_fields as $field){
                            if($data_item[0][$field] ==1){
                                $minus_count += 2;
                            }
                        }
                        ?>
                        <table class="list footable table table-striped tableTools table-bordered  table-white table-primary">

                            <thead>
                            <tr>
                                <?php foreach($data_item[0]['monitor_by'] as $col => $val):?>
                                    <th rowspan="3"><?php echo $col; ?></th>
                                <?php endforeach;?>
                                <th rowspan="3"><?php __('Call Attempt')?></th>
                                <th colspan="<?php echo 12 //- $minus_count;?>"><?php __('Condition')?></th>
                                <th colspan="8"><?php __('Action')?></th>
                            </tr>
                            <tr>
                                <?php //if($data_item[0]['limit_asr'] != 1 ):?>
                                <th colspan="2"><?php __('ASR')?></th>
                                <?php //endif;?>

                                <?php //if($data_item[0]['limit_acd'] != 1 ):?>
                                <th colspan="2"><?php __('ACD')?></th>
                                <?php //endif;?>

                                <th colspan="2"><?php __('SDP')?></th>

                                <?php //if($data_item[0]['limit_pdd'] != 1 ):?>
                                <th colspan="2"><?php __('PDD')?></th>
                                <?php //endif;?>

                                <?php //if($data_item[0]['limit_revenue'] != 1 ):?>
                                <th colspan="2"><?php __('Revenue')?></th>
                                <?php //endif;?>

                                <?php //if($data_item[0]['limit_profitability'] != 1 ):?>
                                <th colspan="2"><?php __('Profitability')?></th>
                                <?php //endif;?>

                                <th colspan="6"><?php __('Block'); ?></th>
                                <th colspan="2"><?php __('Email'); ?></th>
                            </tr>
                            <tr>
                                <?php //if($data_item[0]['limit_asr'] != 1 ):?>
                                <th><?php __('Limit Value')?></th>
                                <th><?php __('Actual Value')?></th>
                                <?php //endif;?>

                                <?php //if($data_item[0]['limit_acd'] != 1 ):?>
                                <th><?php __('Limit Value')?></th>
                                <th><?php __('Actual Value')?></th>
                                <?php //endif;?>

                                <th><?php __('Limit Value')?></th>
                                <th><?php __('Actual Value')?></th>

                                <?php //if($data_item[0]['limit_pdd'] != 1 ):?>
                                <th><?php __('Limit Value')?></th>
                                <th><?php __('Actual Value')?></th>
                                <?php //endif;?>

                                <?php //if($data_item[0]['limit_revenue'] != 1 ):?>
                                <th><?php __('Limit Value')?></th>
                                <th><?php __('Actual Value')?></th>
                                <?php //endif;?>

                                <?php //if($data_item[0]['limit_profitability'] != 1 ):?>
                                <th><?php __('Limit Value')?></th>
                                <th><?php __('Actual Value')?></th>
                                <?php //endif;?>

                                <th><?php __('Ingress Trunk'); ?></th>
                                <th><?php __('Egress Trunk'); ?></th>
                                <th><?php __('ANI'); ?></th>
                                <th><?php __('DNIS'); ?></th>
                                <th><?php __('Code'); ?></th>
                                <th><?php __('Unblock'); ?></th>

                                <th><?php __('Partner'); ?></th>
                                <th><?php __('Admin'); ?></th>
                            </tr>
                            </thead>
                            <tbody>

                            <tr>
                                <?php foreach($data_item[0]['monitor_by'] as $col => $val):?>
                                    <td ><?php echo $val; ?></td>
                                <?php endforeach;?>
                                <td><?php echo isset($data_item[0]['call_attempt']) ? $data_item[0]['call_attempt'] : '';?></td>

                                <?php //if($data_item[0]['limit_asr'] != 1 ):?>
                                <td><?php echo $data_item[0]['limit_asr'] == 1 ? 'ignore' : $data_item[0]['limit_asr'] . ' ' . $data_item[0]['limit_asr_value'] . '%'; ?></td>
                                <td><?php echo $data_item[0]['asr'] . '%' ?></td>
                                <?php //endif;?>

                                <?php //if($data_item[0]['limit_acd'] != 1 ):?>
                                <td><?php echo $data_item[0]['limit_acd'] == 1 ? 'ignore' : $data_item[0]['limit_acd'] . ' ' . $data_item[0]['limit_acd_value'] . 's';  ?></td>
                                <td><?php echo $data_item[0]['acd'] . 's'; ?></td>
                                <?php //endif;?>

                                <td><?php echo $data_item['AlertRules']['sdp_value'] == 0 ? 'ignore' : $sdp_sign[$data_item['AlertRules']['sdp_sign']] . ' ' . $data_item['AlertRules']['sdp_value'] . '%';  ?></td>
                                <td><?php echo $data_item['AlertRulesLogDetail']['sdp_value'] . '%'; ?></td>

                                <?php //if($data_item[0]['limit_pdd'] != 1 ):?>
                                <td><?php echo $data_item[0]['limit_pdd'] == 1 ? 'ignore' : $data_item[0]['limit_pdd'] . ' ' . $data_item[0]['limit_pdd_value'] . 's';  ?></td>
                                <td><?php echo $data_item[0]['pdd'] . 's'; ?></td>
                                <?php //endif;?>

                                <?php //if($data_item[0]['limit_revenue'] != 1 ):?>
                                <td><?php echo $data_item[0]['limit_revenue'] == 1 ? 'ignore' : $data_item[0]['limit_revenue'] . ' $' . number_format($data_item[0]['limit_revenue_value'],2);  ?></td>
                                <td><?php echo '$' . number_format($data_item[0]['revenue'],4); ?></td>
                                <?php //endif;?>

                                <?php //if($data_item[0]['limit_profitability'] != 1 ):?>
                                <td><?php echo $data_item[0]['limit_profitability'] == 1 ? 'ignore' : $data_item[0]['limit_profitability'] . ' ' . $data_item[0]['limit_profitability_value'] . '%';  ?></td>
                                <td><?php echo number_format($data_item[0]['profitability'],4) . '%'; ?></td>
                                <?php //endif;?>

                                <td>
                                    <?php if($data_item[0]['trunk_type'] == 1):?>
                                        <?php echo isset($data_item['AlertRules']['res_id']) ? $data_item['Resource']['alias'] : 'All'; ?>
                                    <?php endif;?>
                                </td>
                                <td>
                                    <?php if($data_item[0]['trunk_type'] == 2):?>
                                        <?php echo isset($data_item['AlertRules']['res_id']) ? $data_item['Resource']['alias'] : 'All'; ?>
                                    <?php endif;?>
                                </td>
                                <td><?php echo isset($monitor_by['ANI']) ? $monitor_by['ANI'] : '';?></td>
                                <td><?php echo $data_item[0]['routing_digits']; ?></td>
                                <td><?php echo $data_item[0]['code']; ?></td>
                                <td><?php
                                    $min = isset($data_item['AlertRules']['unblock_after_min']) ? $data_item['AlertRules']['unblock_after_min'] : 0;
                                    echo 'After '.$min.' min';
                                    ?></td>

                                <td><?php echo $data_item[0]['email_type'] == 2 ? 'Yes' : 'No' ?></td>
                                <td><?php echo $data_item[0]['email_type'] == 1 ? 'Yes' : 'No' ?></td>

                            </tr>

                            </tbody>
                        </table>
                    <?php endforeach; ?>
                </div>

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