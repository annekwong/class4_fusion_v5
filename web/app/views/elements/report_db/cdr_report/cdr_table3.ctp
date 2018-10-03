<style type="text/css">
    .form .value, .list-form .value{text-align:left;}
</style>

<?php $mydata = $p->getDataArray();
$loop = count($mydata);
if ($loop == 0)
{
    ?>
        <div class="widget">
            <div class="widget-head">
                <h4 class="heading"><?php __('Re-rate Method')?>:</h4>
            </div>
            <div class="widget-body">
                <table class="form table tableTools table-bordered  table-white">
                    <colgroup>
                        <col width="40%">
                        <col width="60%">
                    </colgroup>
                    <thead></thead>
                    <tbody>
                    <tr>
                        <td class="right"><?php __('Type'); ?>:</td>
                        <td>
                            <select name="rerate_type">
                                <option value="1" <?php echo isset($_GET['rerate_type']) && $_GET['rerate_type'] == "1" ? 'selected' : '' ?>><?php __('Origination')?></option>
                                <option value="2" <?php echo isset($_GET['rerate_type']) && $_GET['rerate_type'] == "2" ? 'selected' : '' ?>><?php __('Termination')?></option>
                                <option value="3" <?php echo isset($_GET['rerate_type']) && $_GET['rerate_type'] == "3" ? 'selected' : '' ?>><?php __('Both')?></option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td class="right"><?php echo __('Rate Table', true); ?>:</td>
                        <td ><input   type="hidden"  value='query'  name="action_type" id="action_type"/>
                            <?php
                            echo $form->input('rerate_rate_table', array('options' => $all_rate_table, 'name' => 'rerate_rate_table', 'empty' => '', 'label' => false, 'div' => false, 'type' => 'select', 'selected' => isset($_GET['rerate_rate_table']) ? $_GET['rerate_rate_table'] : ''));
                            ?></td>
                    </tr>
                    <tr>
                        <td class="right"><?php echo __('Rerating Time', true); ?>:</td>
                        <td>
                            <input type="text" name="rerate_time" onfocus="WdatePicker({dateFmt: 'yyyy-MM-dd HH:mm:ss'});"  style="width:220px;" value="<?php echo isset($_GET['rerate_time']) ? $_GET['rerate_time'] : '' ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td class="right"><?php __('Use Same LRN number'); ?></td>
                        <td>
                            <select name="same_lrn_number">
                                <option value="true" <?php echo isset($_GET['same_lrn_number']) && $_GET['same_lrn_number'] == "true" ? 'selected' : '' ?>><?php __('True')?></option>
                                <option value="false" <?php echo isset($_GET['same_lrn_number']) && $_GET['same_lrn_number'] == "false" ? 'selected' : '' ?>><?php __('False')?></option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td class="right"><?php __('Use Same Jurisdiction'); ?></td>
                        <td>
                            <select name="same_jur">
                                <option value="true" <?php echo isset($_GET['same_jur']) && $_GET['same_jur'] == "true" ? 'selected' : '' ?>><?php __('True')?></option>
                                <option value="false" <?php echo isset($_GET['same_jur']) && $_GET['same_jur'] == "false" ? 'selected' : '' ?>><?php __('False')?></option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <?php if ($_SESSION['role_menu']['Tools']['cdrreports_db:rerating']['model_w'] && $_SESSION['role_menu']['Tools']['cdrreports_db:rerating']['model_x'])
                        { ?>
                            <td colspan="12" class="buttons-group center">
                                <input type="button" onclick="check_action(this.value)"  class="input in-submit btn btn-primary" value="<?php __('Process')?>">
                                <a href="<?php echo $this->webroot; ?>cdrreports/rerating_list" class="input in-submit btn btn-default" style="color:#FFFFFF; font-weight: normal;"><?php __('Relating List')?></a>
                            </td>
                        <?php }
                        else
                        { ?><td></td><?php } ?>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
<?php }
else
{ ?>
    <div class="widget">
        <div class="widget-head">
            <h4 class="heading"><?php __('Re-rate Method')?>:</h4>
        </div>
        <div class="widget-body">
            <table class="form table tableTools table-bordered  table-white">
                <colgroup>
                    <col width="40%">
                    <col width="60%">
                </colgroup>
                <thead></thead>
                <tbody>
                <tr>
                    <td class="right"><?php __('Type'); ?>:</td>
                    <td>
                        <select name="rerate_type">
                            <option value="1" <?php echo isset($_GET['rerate_type']) && $_GET['rerate_type'] == "1" ? 'selected' : '' ?>><?php __('Origination')?></option>
                            <option value="2" <?php echo isset($_GET['rerate_type']) && $_GET['rerate_type'] == "2" ? 'selected' : '' ?>><?php __('Termination')?></option>
                            <option value="3" <?php echo isset($_GET['rerate_type']) && $_GET['rerate_type'] == "3" ? 'selected' : '' ?>><?php __('Both')?></option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td class="right"><?php echo __('Rate Table', true); ?>:</td>
                    <td ><input   type="hidden"  value='query'  name="action_type" id="action_type"/>
                        <?php
                        echo $form->input('rerate_rate_table', array('options' => $all_rate_table, 'name' => 'rerate_rate_table', 'empty' => '', 'label' => false, 'div' => false, 'type' => 'select', 'selected' => isset($_GET['rerate_rate_table']) ? $_GET['rerate_rate_table'] : ''));
                        ?></td>
                </tr>
                <tr>
                    <td class="right"><?php echo __('Rerating Time', true); ?>:</td>
                    <td>
                        <input type="text" name="rerate_time" onfocus="WdatePicker({dateFmt: 'yyyy-MM-dd HH:mm:ss'});"  style="width:220px;" value="<?php echo isset($_GET['rerate_time']) ? $_GET['rerate_time'] : '' ?>" />
                    </td>
                </tr>
                <tr>
                    <td class="right"><?php __('Use Same LRN number'); ?></td>
                    <td>
                        <select name="same_lrn_number">
                            <option value="true" <?php echo isset($_GET['same_lrn_number']) && $_GET['same_lrn_number'] == "true" ? 'selected' : '' ?>><?php __('True')?></option>
                            <option value="false" <?php echo isset($_GET['same_lrn_number']) && $_GET['same_lrn_number'] == "false" ? 'selected' : '' ?>><?php __('False')?></option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td class="right"><?php __('Use Same Jurisdiction'); ?></td>
                    <td>
                        <select name="same_jur">
                            <option value="true" <?php echo isset($_GET['same_jur']) && $_GET['same_jur'] == "true" ? 'selected' : '' ?>><?php __('True')?></option>
                            <option value="false" <?php echo isset($_GET['same_jur']) && $_GET['same_jur'] == "false" ? 'selected' : '' ?>><?php __('False')?></option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <?php if ($_SESSION['role_menu']['Tools']['cdrreports:rerating']['model_w'] && $_SESSION['role_menu']['Tools']['cdrreports:rerating']['model_x'])
                    { ?>
                        <td colspan="12" class="buttons-group center">
                            <input type="button" onclick="check_action(this.value)"  class="input in-submit btn btn-primary" value="<?php __('Process')?>">
                            <a href="<?php echo $this->webroot; ?>cdrreports/rerating_list" class="input in-submit btn btn-default" style="color:#FFFFFF; font-weight: normal;"><?php __('Relating List')?></a>
                        </td>
                    <?php }
                    else
                    { ?><td></td><?php } ?>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="scroll_div overflow_x" style="margin-top: 30px; max-height:450px;">
        <table class="scroll_table list nowrap with-fields footable table table-striped tableTools table-bordered  table-white table-primary">
            <thead>
            <tr>
                <?php
                $c = count($show_field_array);
                //$currency_code='';
                for ($ii = 0; $ii < $c; $ii++)
                {
                    $order_href = $appCommon->show_order($show_field_array[$ii], $appCdr->format_cdr_field($show_field_array[$ii]));

                    /*
                      if($show_field_array[$ii]=='ingress_client_cost'||$show_field_array[$ii]=='egress_cost'||$show_field_array[$ii]=='egress_rate'||$show_field_array[$ii]=='ingress_client_rate'){
                      $currency_code=$appCommon->show_sys_curr();
                      }else{
                      $currency_code='';
                      } *
                     */
                    if ($this->params['controller'] == 'cdrreports_db' && $this->params['action'] == 'sip_packet'){
                        if($show_field_array[$ii] == 'id'){
                            continue;
                        }
                    }
                    echo "<th rel='8'>&nbsp;&nbsp; " . $order_href . "  &nbsp;&nbsp;</th>";
                }
                ?>


            </tr>
            </thead>
            <tbody>
            <?php
            $show_release_cause_arr = $appCdr->show_release_cause();
            for ($i = 0; $i < $loop; $i++)
            {
                ?>
                <tr style="color: #4B9100">
                    <?php
                    for ($ii = 0; $ii < $c; $ii++)
                    {
                        $f = $show_field_array[$ii];

                        if ($this->params['controller'] == 'cdrreports_db' && $this->params['action'] == 'sip_packet'){
                            if($f == 'id'){
                                continue;
                            }
                        }

                        if($f == 'commission'){
                            $field = $appCommon->currency_rate_conversion($mydata[$i][0][$f]);
                        }else if($f == 'ingress_client_cost'){
                            $field = $appCommon->currency_rate_conversion($mydata[$i][0][$f]);
                        }else if ($f == 'ingress_client_cost' || $f == 'egress_cost' || $f == 'ingress_client_rate' || $f == 'egress_rate')
                        {
                            $field = $appCommon->currency_rate_conversion($mydata[$i][0][$f]);
                        }elseif ($f == 'egress_erro_string'){
                            // echo $mydata[$i][0][$f].'<br />';
                            $field = $appCommon->convert_error($mydata[$i][0][$f]);
                        }elseif ($f == 'egress_dnis_type'){
                            $field = $appCommon->convert_dnis_type($mydata[$i][0][$f]);
                        }elseif ($f == 'ingress_dnis_type'){
                            $field = $appCommon->convert_dnis_type($mydata[$i][0][$f]);
                        }elseif ($f == 'id'){
                            $status = $appCommon->check_sip_capture_exists($mydata[$i][0]['id']);
                            $fields_arr = array();
                            if ($status['ingress']){
                                array_push($fields_arr, '<a title="View Ingress" target="_blank"  href="' . $this->webroot . 'cdrreports_db/cdr_capture/' . $mydata[$i][0]['id'] . '/ingress"><img src="' . $this->webroot . 'images/view.png"/></a>');
                                array_push($fields_arr, '<a title="Down Ingress" target="_blank"  href="' . $this->webroot . 'cdrreports_db/down_sippcap/' . $mydata[$i][0]['id'] . '/ingress"><img src="' . $this->webroot . 'images/export.png"/></a>');

                                if ($status['ingress_rtp']){
                                    array_push($fields_arr, '<a title="Down Ingress RTP" target="_blank"  href="' . $this->webroot . 'cdrreports_db/down_rtpwav/' . $mydata[$i][0]['id'] . '/ingress"><img src="' . $this->webroot . 'images/wav.png"/></a>');
                                }
                            }
                            if ($status['egress']){
                                array_push($fields_arr, '<a title="View Egress" target="_blank"  href="' . $this->webroot . 'cdrreports_db/cdr_capture/' . $mydata[$i][0]['id'] . '/egress"><img src="' . $this->webroot . 'images/view.png"/></a>');
                                array_push($fields_arr, '<a title="Down Egress" target="_blank"  href="' . $this->webroot . 'cdrreports_db/down_sippcap/' . $mydata[$i][0]['id'] . '/egress"><img src="' . $this->webroot . 'images/export.png"/></a>');

                                if ($status['egress_rpt']){
                                    array_push($fields_arr, '<a title="Down Egress RTP" target="_blank"  href="' . $this->webroot . 'cdrreports_db/down_rtpwav/' . $mydata[$i][0]['id'] . '/egress"><img src="' . $this->webroot . 'images/wav.png"/></a>');
                                }
                            }
                            $field = implode('', $fields_arr);
                        }else{
                            if($f == 'release_cause'){
                                $field = $appCommon->cutomer_cdr_field_db($f, $mydata[$i][0][$f]);
                                $field = $show_release_cause_arr[$field];
                            } else {
                                $field = $appCommon->cutomer_cdr_field_db($f, $mydata[$i][0][$f]);
                            }

                        }
                        if (trim($field) == ''){
                            echo "<td  class='in-decimal'  style='text-align:center;color:#6694E3;'><strong  style='color:red;'>" . __('Unknown', true) . "</strong></td>";
                        }
                        else{

                            if ($this->params['controller'] == 'cdrreports_db' && $this->params['action'] == 'sip_packet'){
                                if($f == 'origination_call_id'){
                                    echo "<td  class='in-decimal'  style='text-align:center;color:#6694E3;white-space:nowrap;overflow:hidden; width:auto;'>";
                                    echo $field."&nbsp;";
                                    echo " <a target='_bank' href='".$this->webroot."cdrreports_db/get_sip/".base64_encode($mydata[$i][0]['id'])."/1?origination_call_id=".base64_encode($field)."&time=".urlencode($mydata[$i][0]['time'])."' >SIP</a>&nbsp;";                                                 echo " <a target='_bank' href='".$this->webroot."cdrreports_db/get_sip/".base64_encode($mydata[$i][0]['id'])."/2?origination_call_id=".base64_encode($field)."&time=".urlencode($mydata[$i][0]['time'])."' >RTP</a>&nbsp;";
                                    echo "</td>";
                                }else if($f == 'termination_call_id'){
                                    echo "<td  class='in-decimal'  style='text-align:center;color:#6694E3;white-space:nowrap;overflow:hidden; width:auto;'>";
                                    echo $field."&nbsp;";
                                    echo "<a target='_bank' href='".$this->webroot."cdrreports_db/get_sip/".base64_encode($mydata[$i][0]['id'])."/1/egress?termination_call_id=".base64_encode($field)."&time=".urlencode($mydata[$i][0]['time'])."' >SIP</a>&nbsp;";
                                    echo "<a target='_bank' href='".$this->webroot."cdrreports_db/get_sip/".base64_encode($mydata[$i][0]['id'])."/2/egress?termination_call_id=".base64_encode($field)."&time=".urlencode($mydata[$i][0]['time'])."' >RTP</a>&nbsp;";
                                    echo "</td>";
                                }else{
                                    echo " <td  class='in-decimal'  style='text-align:center;color:#6694E3;white-space:nowrap;overflow:hidden; width:auto;'>" . $field . "</td>";
                                }
                            }else{
                                echo " <td  class='in-decimal'  style='text-align:center;color:#6694E3;white-space:nowrap;overflow:hidden; width:auto;'>" . $field . "</td>";
                            }
                        }
                    }
                    ?>
                </tr>
            <?php } ?>
            </tbody>
        </table>
        <div class="separator"></div>
    </div>


    <?php if ($loop >= 100 || (isset($_GET['page']) && $_GET['page'] > 0)): ?>
    <div class="separator"></div>
    <div class="row-fluid">
        <div class="pagination pagination-large pagination-right margin-none">
            <?php echo $this->element('page'); ?>
        </div>
    </div>

<?php endif ?>
<?php  } ?>