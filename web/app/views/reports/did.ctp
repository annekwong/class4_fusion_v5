<ul class="breadcrumb">
    <li><?php __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>reports/did">
        <?php __('Statistics') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>reports/did">
        <?php echo __('DID Report') ?></a></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('DID Report') ?></h4>
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>

<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-body">

            <?php if ($show_nodata): ?><h1 style="font-size:14px;">Report Period: <?php echo $start_date ?> — <?php echo $end_date ?></h1><?php endif; ?>
            <?php if (empty($data)): ?>
                <?php if ($show_nodata): ?><h2 class="msg center"><?php  echo __('no_data_found') ?></h2><?php endif; ?>
            <?php else: ?>
                <table class="list footable table table-striped tableTools table-bordered  table-white table-primary">
                    <thead>
                    <tr>
                        <?php foreach ($show_fields as $field): ?>
                            <th><?php echo $replace_fields[$field]; ?></th>
                        <?php endforeach; ?>
                        <th><?php __('Call Attempt') ?></th>
                        <th><?php __('Succ. Call') ?></th>
                        <th><?php __('Duration') ?></th>
                        <th><?php __('Client Avg Rate') ?></th>
                        <th><?php __('Vendor Avg Rate') ?></th>
                        <th><?php __('Client Cost') ?></th>
                        <th><?php __('Vendor Cost') ?></th>
                        <th><?php __('Profit') ?></th>
                        <th><?php __('ASR') ?></th>
                        <th><?php __('ACD(min)')?></th>
                        <th><?php __('PDD') ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $i = 0;
                    $arr = array();
                    foreach ($data as $item):
                        $arr['duration'][$i] = $item[0]['duration'];
                        $arr['client_bill_time'][$i] = $item[0]['egress_bill_time'];
                        $arr['vendor_bill_time'][$i] = $item[0]['ingress_bill_time'];
                        $arr['client_call_cost'][$i] = $item[0]['egress_call_cost'];
                        $arr['vendor_call_cost'][$i] = $item[0]['ingress_call_cost'];
                        $arr['total_calls'][$i] = $item[0]['ingress_total_calls'];
                        $arr['not_zero_calls'][$i] = $item[0]['not_zero_calls'];
                        $arr['success_calls'][$i] = $item[0]['ingress_success_calls'];
                        $arr['vendor_busy_calls'][$i] = $item[0]['ingress_busy_calls'];
                        $arr['client_busy_calls'][$i] = $item[0]['egress_busy_calls'];
                        $arr['vendor_cancel_calls'][$i] = $item[0]['ingress_cancel_calls'];
                        $arr['client_cancel_calls'][$i] = $item[0]['egress_cancel_calls'];
                        $arr['pdd'][$i] = $item[0]['pdd'];
                        ?>
                        <tr>
                            <?php foreach (array_keys($show_fields) as $key): ?>
                                <td style="color:#6694E3;"><?php echo $item[0][$key]; ?></td>
                            <?php endforeach; ?>
                            <td><?php echo $arr['total_calls'][$i]; ?></td>
                            <td><?php echo $arr['success_calls'][$i]; ?></td>
                            <td><?php echo number_format($arr['duration'][$i] / 60, 2); ?></td>
                            <td><?php echo number_format($arr['client_bill_time'][$i] == 0 ? 0 : $arr['client_call_cost'][$i] / ($arr['client_bill_time'][$i] / 60), 5); ?></td>
                            <td><?php echo number_format($arr['vendor_bill_time'][$i] == 0 ? 0 : $arr['vendor_call_cost'][$i] / ($arr['vendor_bill_time'][$i] / 60), 5); ?></td>
                            <td><?php echo number_format($arr['client_call_cost'][$i],5); ?></td>
                            <td><?php echo number_format($arr['vendor_call_cost'][$i],5); ?></td>
                            <td><?php echo number_format($arr['vendor_call_cost'][$i] - $arr['client_call_cost'][$i], 5); ?></td>
                            <td><?php echo ($arr['vendor_busy_calls'][$i] + $arr['vendor_cancel_calls'][$i] + $arr['not_zero_calls'][$i]) == 0 ? 0 : round($arr['not_zero_calls'][$i] / ($arr['vendor_busy_calls'][$i] + $arr['vendor_cancel_calls'][$i] + $arr['not_zero_calls'][$i]) * 100, 2) ?>%</td>
                            <td><?php echo round($arr['not_zero_calls'][$i] == 0 ? 0 : $arr['duration'][$i] / $arr['not_zero_calls'][$i] / 60, 2); ?></td>
                            <td><?php echo $arr['pdd'][$i]; ?></td>
                        </tr>
                        <?php
                        $i++;
                    endforeach;
                    ?>
                    <?php
                    $count_group = count($show_fields);
                    if ($count_group && count($data)):
                        ?>
                        <tr style="color:#000000;">
                            <td colspan="<?php echo $count_group; ?>"><?php __('Total')?></td>
                            <td><?php echo array_sum($arr['total_calls']); ?></td>
                            <td><?php echo array_sum($arr['success_calls']); ?></td>
                            <td><?php echo number_format(array_sum($arr['duration']) / 60, 2); ?></td>
                            <td><?php echo number_format(array_sum($arr['client_bill_time']) == 0 ? 0 : array_sum($arr['client_call_cost']) / (array_sum($arr['client_bill_time']) / 60), 5); ?></td>
                            <td><?php echo number_format(array_sum($arr['vendor_bill_time']) == 0 ? 0 : array_sum($arr['vendor_call_cost']) / (array_sum($arr['vendor_bill_time']) / 60), 5); ?></td>
                            <td><?php echo number_format(array_sum($arr['client_call_cost']), 5); ?></td>
                            <td><?php echo number_format(array_sum($arr['vendor_call_cost']), 5); ?></td>
                            <td><?php echo number_format(array_sum($arr['vendor_call_cost']) - array_sum($arr['client_call_cost']), 5); ?></td>
                            <td><?php echo (array_sum($arr['vendor_busy_calls']) + array_sum($arr['vendor_cancel_calls']) + array_sum($arr['not_zero_calls'])) == 0 ? 0 : round(array_sum($arr['not_zero_calls']) / (array_sum($arr['vendor_busy_calls']) + array_sum($arr['vendor_cancel_calls']) + array_sum($arr['not_zero_calls'])) * 100, 2) ?>%</td>
                            <td><?php echo round(array_sum($arr['not_zero_calls']) == 0 ? 0 : array_sum($arr['duration']) / array_sum($arr['not_zero_calls']) / 60, 2); ?></td>
                            <td><?php echo array_sum($arr['pdd']); ?></td>
                        </tr>
                    <?php
                    endif;
                    ?>
                    </tbody>
                </table>
            <?php endif; ?>
            <?php echo $form->create('Cdr', array('type' => 'get', 'id' => 'did_search', 'url' => "/reports/did", 'onsubmit' => "if($('select[name=show_type]').val() == 0) loading();")); ?>
            <fieldset class="query-box" style="clear:both;overflow:hidden;margin-top:10px;">
                <h4 class="heading glyphicons search" style="display: inline-block;"><i></i> <?php __('Search')?></h4>
                <div class="pull-right" title="Advance">
                    <a id="advance_btn" class="btn" href="javascript:void(0)">
                        <i class="icon-long-arrow-down"></i>
                    </a>
                </div>
                <?php echo $this->element('search_report/search_js'); ?> <?php echo $this->element('search_report/search_hide_input'); ?>
                <table class="form" style="width:100%">
                    <?php echo $this->element('report/form_period', array('group_time' => true, 'gettype' => '<select style="width:120px;" name="show_type">
            <option value="0">Web</option>
            <option value="1">CSV</option>
            <option value="2">XLS</option>
        </select>')) ?>
                </table>
                <div class="separator"></div>
                <div id="advance_panel" class="widget widget-heading-simple widget-body-gray">
                    <div class="row-fluid">
                        <div class="span3 offset1">
                            <span class="padding-r10"><?php __('DID')?></span>
                            <input type="text" class="validate[custom[integer]]" name="did" value="<?php echo $common->_get('did'); ?>" />
                        </div>
                        <div class="span4">
                            <span class="padding-r10"><?php __('Origination Vendor')?></span>
                            <?php echo $form->input('ingress_id',array('type'=>'select','options' => $vendors,'value'=> $common->_get('ingress_id'),'label' => false,'div'=> false)); ?>
                        </div>
                        <div class="span4">
                            <span class="padding-r10"><?php __('Origination Client')?></span>
                            <?php echo $form->input('egress_id',array('type'=>'select','options' => $clients,'value'=> $common->_get('egress_id'),'label' => false,'div'=> false)); ?>
                        </div>
                    </div>
                    <div class="separator"></div>
                    <?php if($report_group){ ?>
                        <p class="separator text-center"><i class="icon-table icon-3x"></i></p>
                        <table class="form" style="width:100%">
                            <tr>
                                <td class="align_right padding-r10"><?php __('Group By')?> #1</td>
                                <td>
                                    <select name="group_select[]" style="width:160px;">
                                        <option value="" <?php echo $common->set_get_select_mul('group_select', '', 0, TRUE); ?>></option>
                                        <option value="did" <?php echo $common->set_get_select_mul('group_select', 'did', 0); ?>><?php __('DID')?></option>
                                        <option value="ingress_id" <?php echo $common->set_get_select_mul('group_select', 'ingress_id', 0); ?>><?php __('Vendor')?></option>
                                        <option value="egress_id" <?php echo $common->set_get_select_mul('group_select', 'egress_id', 0); ?>><?php __('Client')?></option>
                                    </select>
                                </td>
                                <td class="align_right padding-r10"><?php __('Group By')?> #2</td>
                                <td>
                                    <select name="group_select[]" style="width:160px;">
                                        <option value="" <?php echo $common->set_get_select_mul('group_select', '', 1, TRUE); ?>></option>
                                        <option value="did" <?php echo $common->set_get_select_mul('group_select', 'did', 1); ?>><?php __('DID')?></option>
                                        <option value="ingress_id" <?php echo $common->set_get_select_mul('group_select', 'ingress_id', 1); ?>><?php __('Vendor')?></option>
                                        <option value="egress_id" <?php echo $common->set_get_select_mul('group_select', 'egress_id', 1); ?>><?php __('Client')?></option>
                                    </select>
                                </td>
                                <td class="align_right padding-r10"><?php __('Group By')?> #3</td>
                                <td>
                                    <select name="group_select[]" style="width:160px;">
                                        <option value="" <?php echo $common->set_get_select_mul('group_select', '', 2, TRUE); ?>></option>
                                        <option value="did" <?php echo $common->set_get_select_mul('group_select', 'did', 2); ?>><?php __('DID')?></option>
                                        <option value="ingress_id" <?php echo $common->set_get_select_mul('group_select', 'ingress_id', 2); ?>><?php __('Vendor')?></option>
                                        <option value="egress_id" <?php echo $common->set_get_select_mul('group_select', 'egress_id', 2); ?>><?php __('Client')?></option>
                                    </select>
                                </td>
                            </tr>
                        </table>
                    <?php } ?>
                </div>
            </fieldset>
            <?php echo $form->end(); ?>

        </div>
    </div>
</div>

<script type="text/javascript">

    $(function() {
        $.limit_time();
        $('select[name="show_type"]').change(function(){
            if($(this).val() == '0'){

                $('#did_search').attr('target','_self');
            } else {
                $('#did_search').attr('target','_blank');
            }
        }).trigger('change');
    })

</script>
