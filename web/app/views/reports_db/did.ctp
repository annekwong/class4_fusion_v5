<div id="title">
    <h1><?php __('Statistics')?>&gt;&gt;<?php  __('DID Report')?></h1>
</div>

<div id="container">
    <ul class="tabs">
        <li <?php if($type == 1) echo 'class="active"'; ?>>
            <a href="<?php echo $this->webroot; ?>reports_db/did/1">
                <img height="16" width="16" src="<?php echo $this->webroot; ?>images/list.png" />
                Origination
            </a>
        </li>
        <li <?php if($type == 2) echo 'class="active"'; ?>>
            <a href="<?php echo $this->webroot; ?>reports_db/did/2">
                <img height="16" width="16" src="<?php echo $this->webroot; ?>images/menuIcon.gif" />
                Termination
            </a>
        </li>
    </ul>
    <?php if($show_nodata): ?><h1 style="font-size:14px;">Report Period <?php echo $start_date ?> — <?php echo $end_date ?></h1><?php endif; ?>
    <?php if(empty($data)): ?>
    <?php if($show_nodata): ?><div class="msg"><?php  echo __('no_data_found') ?></div><?php endif; ?>
    <?php else: ?>
    <table class="list" style="color:#4B9100;">
        <thead>
            <tr>
                <?php foreach($show_fields as $field): ?>
                <td><?php echo $replace_fields[$field]; ?></td>
                <?php endforeach; ?>
                <td colspan="2">Time(min)</td>
                <td>Usage Charge(USA)</td>
                <td>Total Cost</td>
                <td>Avg Rate</td>
                <td colspan="2">Calls</td>
            </tr>
            <tr>
                <?php for($i=0;$i<count($show_fields);$i++):?>
                <td>&nbsp;</td>
                <?php endfor; ?>
                <td>Total Duration</td>
                <td>Total Billable Time</td>
                <td></td>
                <td></td>
                <td></td>
                <td>Total Calls</td>
                <td>Not Zero</td>
        </thead>
        <tbody>
            <?php
                $i = 0;
                $arr = array();
                foreach($data as $item):
                    $arr['duration'][$i] = $item[0]['duration'];
                    $arr['bill_time'][$i] = $item[0]['bill_time'];
                    $arr['call_cost'][$i] = $item[0]['call_cost'];                    
                    $arr['total_calls'][$i] = $item[0]['total_calls'];
                    $arr['not_zero_calls'][$i] = $item[0]['not_zero_calls'];
                    $arr['success_calls'][$i] = $item[0]['success_calls'];
                    $arr['did'][$i] = $item[0]['did'];
            ?>
            <tr>
                <?php foreach(array_keys($show_fields) as $key): ?>
                <td style="color:#6694E3;"><?php echo $item[0][$key]; ?></td>
                <?php endforeach; ?>
                <td><?php echo $arr['did']; ?></td>
                <td><?php echo number_format($arr['duration'][$i] / 60, 2); ?></td>
                <td><?php echo number_format($arr['bill_time'][$i] / 60, 2); ?></td> 
                <td><?php echo number_format($arr['call_cost'][$i], 5); ?></td>
                <td><?php echo number_format($arr['bill_time'][$i] == 0 ? 0 : $arr['call_cost'][$i] / ($arr['bill_time'][$i] / 60), 5); ?></td>
                <td><?php echo number_format($arr['total_calls'][$i]); ?></td> 
                <td><?php echo number_format($arr['not_zero_calls'][$i]); ?></td>
            </tr>
            <?php 
                $i++;
                endforeach; 
            ?>
            
            <?php
                $count_group = count($show_fields);
                if($count_group && count($data)):
            ?>
            <tr style="color:#000;">
                <td colspan="<?php echo $count_group; ?>">Total:</td>
                <td></td>
                <td><?php echo number_format(array_sum($arr['duration']) / 60, 2); ?></td>
                <td><?php echo number_format(array_sum($arr['bill_time']) / 60, 2); ?></td>
                <td><?php echo number_format(array_sum($arr['call_cost']), 5); ?></td>
                <td><?php echo number_format(array_sum($arr['bill_time']) == 0 ? 0 : array_sum($arr['call_cost']) / (array_sum($arr['bill_time']) / 60), 5); ?></td>
                <td><?php echo number_format(array_sum($arr['total_calls'])); ?></td> 
                <td><?php echo number_format(array_sum($arr['not_zero_calls'])); ?></td>
            </tr>
            <?php
                endif;
            ?>
        </tbody>
    </table>
    <?php endif; ?>
<?php echo $form->create ('Cdr', array ('type'=>'get','url' => "/reports_db/did/{$type}" ,'onsubmit'=>"if($('select[name=show_type]').val() == 0) loading();"));?>
<fieldset class="query-box" style="clear:both;overflow:hidden;margin-top:10px;">
    <div class="search_title">
        <img src="<?php echo $this->webroot ?>images/search_title_icon.png">
        Search
    </div>
    <?php echo $this->element('search_report/search_js');?> <?php echo $this->element('search_report/search_hide_input');?>
    <table class="form" style="width:100%">
        <?php echo $this->element('report_db/form_period',array('group_time'=>true, 'gettype'=>'<select style="width:120px;" name="show_type">
            <option value="0">Web</option>
            <option value="1">CSV</option>
            <option value="2">XLS</option>
        </select>'))?>
        <tr class="period-block" style="height:20px; line-height:20px;">
            <td colspan="2" style="text-align:center; font-size:14px;"><b><?php echo __('Inbound',true);?></b></td>
            <td class="in-out_bound">&nbsp;</td>
            <td colspan="2" style="text-align:center;font-size:14px;"><b><?php echo __('Outbound',true);?></b></td>
            <td class="in-out_bound">&nbsp;</td>
            <td colspan="2">&nbsp;</td>
        </tr>
        <tr>
            <td class="label">Carriers:</td>
            <td class="value">
                <select style="width:120px;" name="ingress_client_id">
                    <option></option>
                    <?php foreach($ingress_clients as $ingress_client): ?>
                    <option value="<?php echo $ingress_client[0]['client_id'] ?>" <?php echo $common->set_get_select('ingress_client_id', $ingress_client[0]['client_id']) ?>><?php echo $ingress_client[0]['name'] ?></option>
                    <?php endforeach; ?>
                </select>
                <?php echo $this->element('search_report/ss_clear_input_select');?>
            </td>
            <td class="in-out_bound label">&nbsp;</td>
            <td class="label">Carriers:</td>
            <td class="value">
                <select style="width:120px;" name="egress_client_id">
                    <option></option>
                    <?php foreach($egress_clients as $egress_client): ?>
                    <option value="<?php echo $egress_client[0]['client_id'] ?>" <?php echo $common->set_get_select('egress_client_id', $egress_client[0]['client_id']) ?>><?php echo $egress_client[0]['name'] ?></option>
                    <?php endforeach; ?>
                </select>
                <?php echo $this->element('search_report/ss_clear_input_select');?>
            </td>
            <td class="in-out_bound label">&nbsp;</td>
            
            <td class="label"></td>
            <td class="value">
            </td>
        </tr>
        <tr>
            <td class="label">Ingress Trunk:</td>
            <td class="value">
                <select style="width:120px;" name="ingress_id" onchange="getTechPrefix(this);">
                     <?php if(empty($_GET['ingress_id'])) { ?>
                        <option selected=""></option>
                    <?php }else{ ?>
                        <option></option>
                    <?php  } ?>
                    <?php foreach($ingress_trunks as $ingress_trunk): 
                       
                         if($_GET['ingress_id'] == $ingress_trunk[0]['resource_id']){
                    ?>
                        <option selected value="<?php echo $ingress_trunk[0]['resource_id'] ?>" <?php echo $common->set_get_select('ingress_id', $ingress_trunk[0]['resource_id']) ?>><?php echo $ingress_trunk[0]['alias'] ?></option>
                    <?php    
                        }else{
                    ?>
                          <option value="<?php echo $ingress_trunk[0]['resource_id'] ?>" <?php echo $common->set_get_select('ingress_id', $ingress_trunk[0]['resource_id']) ?>><?php echo $ingress_trunk[0]['alias'] ?></option>
                    <?php
                        }
                    ?>
                    
                    
                    <?php endforeach; ?>
                </select>
                <?php echo $this->element('search_report/ss_clear_input_select');?>
            </td>
            <td class="in-out_bound label">&nbsp;</td>
            <td class="label">Egress Trunk:</td>
            <td class="value">
                <select style="width:120px;" name="egress_id">
                    <option></option>
                    <?php foreach($egress_trunks as $egress_trunk): ?>
                        <option value="<?php echo $egress_trunk[0]['resource_id'] ?>" <?php echo $common->set_get_select('egress_id', $egress_trunk[0]['resource_id']) ?>><?php echo $egress_trunk[0]['alias'] ?></option>
                    <?php endforeach; ?>
                </select>
                <?php echo $this->element('search_report/ss_clear_input_select');?>
            </td>
            <td rowspan="4" class="in-out_bound label">&nbsp;</td>
            <td rowspan="4" class="label"><!--Group By:--></td>
            <td rowspan="4" colspan="2">
                <!--
                <select multiple="multiple" name="group_select[]" style="height:170px;">
                    <option value="ingress_client_id" <?php echo $common->set_get_select('group_select', 'ingress_client_id'); ?>>ORIG Carrier</option>
                    <option value="ingress_id" <?php echo $common->set_get_select('group_select', 'ingress_id'); ?>>Ingress Trunk</option>
                    <option value="orig_country" <?php echo $common->set_get_select('group_select', 'ingress_country'); ?>>ORIG Country</option>
                    <option value="orig_code_name" <?php echo $common->set_get_select('group_select', 'ingress_code_name'); ?>>ORIG Code Name</option>
                    <option value="orig_code" <?php echo $common->set_get_select('group_select', 'orig_code'); ?>>ORIG Code</option>
                    <option value="" selected="selected"></option>
                    <option value="egress_client_id" <?php echo $common->set_get_select('group_select', 'egress_client_id'); ?>>TERM Carrier</option>
                    <option value="egress_id" <?php echo $common->set_get_select('group_select', 'egress_id'); ?>>Egress Trunk</option>
                    <option value="term_country" <?php echo $common->set_get_select('group_select', 'egress_country'); ?>>TERM Country</option>
                    <option value="term_code_name" <?php echo $common->set_get_select('group_select', 'egress_code_name'); ?>>TERM Code Name</option>
                    <option value="term_code" <?php echo $common->set_get_select('group_select', 'term_code'); ?>>TERM Code</option>
                </select>
                -->
            </td>
        </tr>
        <tr>
            <td class="label">Rate Type</td>
            <td class="value">
                <select name="orig_rate_type" style="width:120px;">
                    <option value="0" <?php echo $common->set_get_select('orig_rate_type', 0); ?>>All</option>
                    <option value="1" <?php echo $common->set_get_select('orig_rate_type', 1); ?>>A-Z</option>
                    <option value="2" <?php echo $common->set_get_select('orig_rate_type', 2); ?>>US</option>
                    <option value="3" <?php echo $common->set_get_select('orig_rate_type', 3); ?>>OCN-LATA</option>
                </select>
                <?php echo $this->element('search_report/ss_clear_input_select');?>
            </td>
            <td class="in-out_bound label">&nbsp;</td>
            <td class="label">Rate Type</td>
            <td class="value">
                <select name="term_rate_type" style="width:120px;">
                    <option value="0" <?php echo $common->set_get_select('term_rate_type', 0); ?>>All</option>
                    <option value="1" <?php echo $common->set_get_select('term_rate_type', 1); ?>>A-Z</option>
                    <option value="2" <?php echo $common->set_get_select('term_rate_type', 2); ?>>US</option>
                    <option value="3" <?php echo $common->set_get_select('term_rate_type', 3); ?>>OCN-LATA</option>
                </select>
                <?php echo $this->element('search_report/ss_clear_input_select');?>
            </td>
            <td class="in-out_bound label">&nbsp;</td>
        </tr>
        <tr>
            <td class="label">Rate Table</td>
            <td class="value">
                <select style="width:120px;" id="ingress_rate_table" name="ingress_rate_table">
                    <option value="all">
                        All
                    </option>
                    <?php
                            if(!empty($ingress_options['rate_tables'])){
                                foreach($ingress_options['rate_tables'] as $te){
                                    if(isset($_GET['ingress_rate_table']) && $_GET['ingress_rate_table'] == $te[0]['rate_table_id']){
                                        echo "<option selected value='".$te[0]['rate_table_id']."'>".$te[0]['rate_table_name']."</option>";
                                    }else{
                                        echo "<option value='".$te[0]['rate_table_id']."'>".$te[0]['rate_table_name']."</option>";
                                    }
                                }
                            }else {
                                foreach ($rate_tables as $rate_table)
                                {
                                    $checked = '';
                                    if (isset($_GET['ingress_rate_table']) && $_GET['ingress_rate_table'] == $rate_table[0]['id'])
                                        $checked = 'selected';
                                    echo "<option value='".$rate_table[0]['id']."' $checked>".$rate_table[0]['name']."</option>";
                                }
                            }

                    ?>   
                </select>
                <?php echo $this->element('search_report/ss_clear_input_select');?>
            </td>
            <td class="in-out_bound label">&nbsp;</td>
            <td class="label"></td>
            <td class="value">
            </td>
            <td class="in-out_bound label">&nbsp;</td>
            
            <td class="label"></td>
            <td class="value">
            </td>
        </tr>
        
        <tr>
            <td class="label">Routing Plan:</td>
            <td class="value">
                <select style="width:120px;" id="ingress_routing_plan" name="ingress_routing_plan">
                    <option value="all">
                        All
                    </option>
                    <?php
                            if(!empty($ingress_options['routing_plans'])){
                                
                                
                                foreach($ingress_options['routing_plans'] as $te){
                                    if(isset($_GET['ingress_routing_plan']) && $_GET['ingress_routing_plan'] == $te[0]['route_strategy_id']){
                                        echo "<option selected value='".$te[0]['route_strategy_id']."'>".$te[0]['route_strategy_name']."</option>";
                                    }else{
                                        echo "<option value='".$te[0]['route_strategy_id']."'>".$te[0]['route_strategy_name']."</option>";
                                    }
                                }
                            } else {
                                foreach ($routing_plans as $routing_plan)
                                {
                                    $checked = '';
                                    if (isset($_GET['ingress_routing_plan']) && $_GET['ingress_routing_plan'] == $routing_plan[0]['id'])
                                        $checked = 'selected';
                                    echo "<option value='".$routing_plan[0]['id']."' $checked>".$routing_plan[0]['name']."</option>";
                                }
                            }

                    ?>   
                </select>
                <?php echo $this->element('search_report/ss_clear_input_select');?>
            </td>
            <td class="in-out_bound label">&nbsp;</td>
            <td class="label"></td>
            <td class="value">
            </td>
            <td class="in-out_bound label">&nbsp;</td>
            
            <td class="label"></td>
            <td class="value">
            </td>
        </tr>
    </table>
    <div class="group_by">
        Group By
    </div>
    <table class="form" style="width:100%">
        <tr>
            <td class="label">Group By #1:</td>
            <td class="value">
                <select name="group_select[]" style="width:140px;">
                    <option value="" <?php echo $common->set_get_select_mul('group_select', '', 0, TRUE); ?>></option>
                    <option value="ingress_ip" <?php echo $common->set_get_select_mul('group_select', 'ingress_ip', 0); ?>>Ingress IP</option>
                    <option value="egress_ip" <?php echo $common->set_get_select_mul('group_select', 'egress_ip', 0); ?>>Egress IP</option>
                    <option value="ingress_client_id" <?php echo $common->set_get_select_mul('group_select', 'ingress_client_id', 0); ?>>ingress Carrier</option>
                    <option value="ingress_id" <?php echo $common->set_get_select_mul('group_select', 'ingress_id', 0); ?>>Ingress Trunk</option>
<!--                    <option value="ingress_country" <?php echo $common->set_get_select_mul('group_select', 'ingress_country', 0); ?>>ingress Country</option>
                    <option value="ingress_code_name" <?php echo $common->set_get_select_mul('group_select', 'ingress_code_name', 0); ?>>ingress Code Name</option>
                    <option value="ingress_code" <?php echo $common->set_get_select_mul('group_select', 'ingress_code', 0); ?>>ingress Code</option>
                    <option value="ingress_rate" <?php echo $common->set_get_select_mul('group_select', 'ingress_rate', 0); ?>>ingress Rate</option>-->
                    <option value="egress_client_id" <?php echo $common->set_get_select_mul('group_select', 'egress_client_id', 0); ?>>egress Carrier</option>
                    <option value="egress_id" <?php echo $common->set_get_select_mul('group_select', 'egress_id', 0); ?>>egress Trunk</option>
<!--                    <option value="egress_country" <?php echo $common->set_get_select_mul('group_select', 'egress_country', 0); ?>>egress Country</option>
                    <option value="egress_code_name" <?php echo $common->set_get_select_mul('group_select', 'egress_code_name', 0); ?>>egress Code Name</option>
                    <option value="egress_code" <?php echo $common->set_get_select_mul('group_select', 'egress_code', 0); ?>>egress Code</option>-->
                </select>
            </td>
            <td class="label">Group By #2:</td>
            <td class="value">
                <select name="group_select[]" style="width:140px;">
                    <option value="" <?php echo $common->set_get_select_mul('group_select', '', 1, TRUE); ?>></option>
                    <option value="ingress_ip" <?php echo $common->set_get_select_mul('group_select', 'ingress_ip', 1); ?>>Ingress IP</option>
                    <option value="egress_ip" <?php echo $common->set_get_select_mul('group_select', 'egress_ip', 1); ?>>Egress IP</option>
                    <option value="ingress_client_id" <?php echo $common->set_get_select_mul('group_select', 'ingress_client_id', 1); ?>>ingress Carrier</option>
                    <option value="ingress_id" <?php echo $common->set_get_select_mul('group_select', 'ingress_id', 1); ?>>Ingress Trunk</option>
<!--                    <option value="ingress_country" <?php echo $common->set_get_select_mul('group_select', 'ingress_country', 1); ?>>ingress Country</option>
                    <option value="ingress_code_name" <?php echo $common->set_get_select_mul('group_select', 'ingress_code_name', 1); ?>>ingress Code Name</option>
                    <option value="ingress_code" <?php echo $common->set_get_select_mul('group_select', 'ingress_code', 1); ?>>ingress Code</option>
                    <option value="ingress_rate" <?php echo $common->set_get_select_mul('group_select', 'ingress_rate', 1); ?>>ingress Rate</option>-->
                    <option value="egress_client_id" <?php echo $common->set_get_select_mul('group_select', 'egress_client_id', 1); ?>>egress Carrier</option>
                    <option value="egress_id" <?php echo $common->set_get_select_mul('group_select', 'egress_id', 1); ?>>Egress Trunk</option>
<!--                    <option value="egress_country" <?php echo $common->set_get_select_mul('group_select', 'egress_country', 1); ?>>egress Country</option>
                    <option value="egress_code_name" <?php echo $common->set_get_select_mul('group_select', 'egress_code_name', 1); ?>>egress Code Name</option>
                    <option value="egress_code" <?php echo $common->set_get_select_mul('group_select', 'egress_code', 1); ?>>egress Code</option>-->
                </select>
            </td>
            <td class="label">Group By #3:</td>
            <td class="value">
                <select name="group_select[]" style="width:140px;">
                    <option value="" <?php echo $common->set_get_select_mul('group_select', '', 2, TRUE); ?>></option>
                    <option value="ingress_ip" <?php echo $common->set_get_select_mul('group_select', 'ingress_ip', 2); ?>>Ingress IP</option>
                    <option value="egress_ip" <?php echo $common->set_get_select_mul('group_select', 'egress_ip', 2); ?>>Egress IP</option>
                    <option value="ingress_client_id" <?php echo $common->set_get_select_mul('group_select', 'ingress_client_id', 2); ?>>ingress Carrier</option>
                    <option value="ingress_id" <?php echo $common->set_get_select_mul('group_select', 'ingress_id', 2); ?>>Ingress Trunk</option>
<!--                    <option value="ingress_country" <?php echo $common->set_get_select_mul('group_select', 'ingress_country', 2); ?>>ingress Country</option>
                    <option value="ingress_code_name" <?php echo $common->set_get_select_mul('group_select', 'ingress_code_name', 2); ?>>ingress Code Name</option>
                    <option value="ingress_code" <?php echo $common->set_get_select_mul('group_select', 'ingress_code', 2); ?>>ingress Code</option>
                    <option value="ingress_rate" <?php echo $common->set_get_select_mul('group_select', 'ingress_rate', 2); ?>>ingress Rate</option>-->
                    <option value="egress_client_id" <?php echo $common->set_get_select_mul('group_select', 'egress_client_id', 2); ?>>egress Carrier</option>
                    <option value="egress_id" <?php echo $common->set_get_select_mul('group_select', 'egress_id', 2); ?>>Egress Trunk</option>
<!--                    <option value="egress_country" <?php echo $common->set_get_select_mul('group_select', 'egress_country', 2); ?>>egress Country</option>
                    <option value="egress_code_name" <?php echo $common->set_get_select_mul('group_select', 'egress_code_name', 2); ?>>egress Code Name</option>
                    <option value="egress_code" <?php echo $common->set_get_select_mul('group_select', 'egress_code', 2); ?>>egress Code</option>-->
                </select>
            </td>
        </tr>
        <tr>
            <td class="label">Group By #4:</td>
            <td class="value">
                <select name="group_select[]" style="width:140px;">
                    <option value="" <?php echo $common->set_get_select_mul('group_select', '', 3, TRUE); ?>></option>
                    <option value="ingress_ip" <?php echo $common->set_get_select_mul('group_select', 'ingress_ip', 3); ?>>Ingress IP</option>
                    <option value="egress_ip" <?php echo $common->set_get_select_mul('group_select', 'egress_ip', 3); ?>>Egress IP</option>
                    <option value="ingress_client_id" <?php echo $common->set_get_select_mul('group_select', 'ingress_client_id', 3); ?>>ingress Carrier</option>
                    <option value="ingress_id" <?php echo $common->set_get_select_mul('group_select', 'ingress_id', 3); ?>>Ingress Trunk</option>
<!--                    <option value="ingress_country" <?php echo $common->set_get_select_mul('group_select', 'ingress_country', 3); ?>>ingress Country</option>
                    <option value="ingress_code_name" <?php echo $common->set_get_select_mul('group_select', 'ingress_code_name', 3); ?>>ingress Code Name</option>
                    <option value="ingress_code" <?php echo $common->set_get_select_mul('group_select', 'ingress_code', 3); ?>>ingress Code</option>
                    <option value="ingress_rate" <?php echo $common->set_get_select_mul('group_select', 'ingress_rate', 3); ?>>ingress Rate</option>-->
                    <option value="egress_client_id" <?php echo $common->set_get_select_mul('group_select', 'egress_client_id', 3); ?>>egress Carrier</option>
                    <option value="egress_id" <?php echo $common->set_get_select_mul('group_select', 'egress_id', 3); ?>>Egress Trunk</option>
<!--                    <option value="egress_country" <?php echo $common->set_get_select_mul('group_select', 'egress_country', 3); ?>>egress Country</option>
                    <option value="egress_code_name" <?php echo $common->set_get_select_mul('group_select', 'egress_code_name', 3); ?>>egress Code Name</option>
                    <option value="egress_code" <?php echo $common->set_get_select_mul('group_select', 'egress_code', 3); ?>>egress Code</option>-->
                </select>
            </td>
            <td class="label">Group By #5:</td>
            <td class="value">
                <select name="group_select[]" style="width:140px;">
                    <option value="" <?php echo $common->set_get_select_mul('group_select', '', 4, TRUE); ?>></option>
                    <option value="ingress_ip" <?php echo $common->set_get_select_mul('group_select', 'ingress_ip', 4); ?>>Ingress IP</option>
                    <option value="egress_ip" <?php echo $common->set_get_select_mul('group_select', 'egress_ip', 4); ?>>Egress IP</option>
                    <option value="ingress_client_id" <?php echo $common->set_get_select_mul('group_select', 'ingress_client_id', 4); ?>>ingress Carrier</option>
                    <option value="ingress_id" <?php echo $common->set_get_select_mul('group_select', 'ingress_id', 4); ?>>Ingress Trunk</option>
<!--                    <option value="ingress_country" <?php echo $common->set_get_select_mul('group_select', 'ingress_country', 4); ?>>ingress Country</option>
                    <option value="ingress_code_name" <?php echo $common->set_get_select_mul('group_select', 'ingress_code_name', 4); ?>>ingress Code Name</option>
                    <option value="ingress_code" <?php echo $common->set_get_select_mul('group_select', 'ingress_code', 4); ?>>ingress Code</option>
                    <option value="ingress_rate" <?php echo $common->set_get_select_mul('group_select', 'ingress_rate', 4); ?>>ingress Rate</option>-->
                    <option value="egress_client_id" <?php echo $common->set_get_select_mul('group_select', 'egress_client_id', 4); ?>>egress Carrier</option>
                    <option value="egress_id" <?php echo $common->set_get_select_mul('group_select', 'egress_id', 4); ?>>Egress Trunk</option>
<!--                    <option value="egress_country" <?php echo $common->set_get_select_mul('group_select', 'egress_country', 4); ?>>egress Country</option>
                    <option value="egress_code_name" <?php echo $common->set_get_select_mul('group_select', 'egress_code_name', 4); ?>>egress Code Name</option>
                    <option value="egress_code" <?php echo $common->set_get_select_mul('group_select', 'egress_code', 4); ?>>egress Code</option>-->
                </select>
            </td>
            <td class="label">Group By #6:</td>
            <td class="value">
                <select name="group_select[]" style="width:140px;">
                    <option value="" <?php echo $common->set_get_select_mul('group_select', '', 5, TRUE); ?>></option>
                    <option value="ingress_ip" <?php echo $common->set_get_select_mul('group_select', 'ingress_ip', 5); ?>>Ingress IP</option>
                    <option value="egress_ip" <?php echo $common->set_get_select_mul('group_select', 'egress_ip', 5); ?>>Egress IP</option>
                    <option value="ingress_client_id" <?php echo $common->set_get_select_mul('group_select', 'ingress_client_id', 5); ?>>ingress Carrier</option>
                    <option value="ingress_id" <?php echo $common->set_get_select_mul('group_select', 'ingress_id', 5); ?>>Ingress Trunk</option>
<!--                    <option value="ingress_country" <?php echo $common->set_get_select_mul('group_select', 'ingress_country', 5); ?>>ingress Country</option>
                    <option value="ingress_code_name" <?php echo $common->set_get_select_mul('group_select', 'ingress_code_name', 5); ?>>ingress Code Name</option>
                    <option value="ingress_code" <?php echo $common->set_get_select_mul('group_select', 'ingress_code', 5); ?>>ingress Code</option>
                    <option value="ingress_rate" <?php echo $common->set_get_select_mul('group_select', 'ingress_rate', 5); ?>>ingress Rate</option>-->
                    <option value="egress_client_id" <?php echo $common->set_get_select_mul('group_select', 'egress_client_id', 5); ?>>egress Carrier</option>
                    <option value="egress_id" <?php echo $common->set_get_select_mul('group_select', 'egress_id', 5); ?>>Egress Trunk</option>
<!--                    <option value="egress_country" <?php echo $common->set_get_select_mul('group_select', 'egress_country', 5); ?>>egress Country</option>
                    <option value="egress_code_name" <?php echo $common->set_get_select_mul('group_select', 'egress_code_name', 5); ?>>egress Code Name</option>
                    <option value="egress_code" <?php echo $common->set_get_select_mul('group_select', 'egress_code', 5); ?>>egress Code</option>-->
                </select>
            </td>
        </tr>
    </table>
</fieldset>
<?php echo $form->end();?>

</div>
<script type="text/javascript">
    
    var $routeprefix = $("#CdrRoutePrefix");
    var $ingress_rate_table = $('#ingress_rate_table');
    var $ingress_routing_plan = $('#ingress_routing_plan');
    
    function getTechPrefix(obj){
         var $this = $(obj);
         var val = $this.val();
         $routeprefix.empty();
         $ingress_rate_table.empty();
         $ingress_routing_plan.empty();
         $routeprefix.append("<option value='all'>All</option>");
         $ingress_rate_table.append("<option value='all'>All</option>");
         $ingress_routing_plan.append("<option value='all'>All</option>");
         if(val != '0'){
           
            $.post("<?php echo $this->webroot?>cdrreports_db/getTechPerfix", {ingId:val}, 
                function(data){
                $.each(data.prefixes,
                    function (index,content){
                       $routeprefix.append("<option value='"+content[0]['tech_prefix']+"'>"+content[0]['tech_prefix']+"</option>");
                    }
                );
                     $.each(data.rate_tables,
                    function (index,content){
                       $ingress_rate_table.append("<option value='"+content[0]['rate_table_id']+"'>"+content[0]['rate_table_name']+"</option>");
                    }
                );
                     $.each(data.routing_plans,
                    function (index,content){
                       $ingress_routing_plan.append("<option value='"+content[0]['route_strategy_id']+"'>"+content[0]['route_strategy_name']+"</option>");
                    }
                );
            }, 'json');
            
        }
        
    }
    
    function clear_prefix(obj) {
        var $this = $(obj);
        $(obj).prev().find('option:first').attr('selected', true);
    }
</script>
