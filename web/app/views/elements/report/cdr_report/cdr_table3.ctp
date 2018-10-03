<style type="text/css">
    .form .value, .list-form .value{text-align:left;}
</style>

<?php $mydata = $p->getDataArray();
$loop = count($mydata);
if ($loop == 0)
{
    ?>
    <div>

        <table class="list table table-condensed">
            <tbody>

                <tr>
                    <td>
                        <?php __('Re-rate Method')?> 
                    </td>
                    <td class="align_right">
                        <?php echo __('Type', true); ?>:
                    </td>
                    <td>
                        <select name="rerate_type" class="input-medium">
                            <option value="1" <?php echo isset($_GET['rerate_type']) && $_GET['rerate_type'] == "1" ? 'selected' : '' ?>><?php __('Origination')?></option>
                            <option value="2" <?php echo isset($_GET['rerate_type']) && $_GET['rerate_type'] == "2" ? 'selected' : '' ?>><?php __('Termination')?></option>
                            <option value="3" <?php echo isset($_GET['rerate_type']) && $_GET['rerate_type'] == "3" ? 'selected' : '' ?>><?php __('Both')?></option>
                        </select> 
                    </td>
                    <td><?php echo __('Rate Table', true); ?>:</td>
                    <td ><input   type="hidden"  value='query'  name="action_type" id="action_type"/>
                        <?php
                        echo $form->input('rerate_rate_table', array('options' => $all_rate_table, 'name' => 'rerate_rate_table', 'empty' => '', 'label' => false, 'div' => false, 'class' => 'input-medium', 'type' => 'select', 'selected' => isset($_GET['rerate_rate_table']) ? $_GET['rerate_rate_table'] : ''));
                        ?></td>
                    <td><?php echo __('Rerating Time', true); ?>:</td>
                    <td><input type="text" name="rerate_time" onfocus="WdatePicker({dateFmt: 'yyyy-MM-dd HH:mm:ss'});"  style="width:120px;" value="<?php echo isset($_GET['rerate_time']) ? $_GET['rerate_time'] : '' ?>" /></td>
                    <td ><?php __('Use Same LRN number'); ?></td>
                    <td>
                        <select name="same_lrn_number" class="input-small">
                            <option value="true" <?php echo isset($_GET['same_lrn_number']) && $_GET['same_lrn_number'] == "true" ? 'selected' : '' ?>><?php __('True')?></option>
                            <option value="false" <?php echo isset($_GET['same_lrn_number']) && $_GET['same_lrn_number'] == "false" ? 'selected' : '' ?>><?php __('False')?></option>
                        </select>
                    </td>
                    <td><?php __('Use Same Jurisdiction'); ?></td>
                    <td>
                        <select name="same_jur" class="input-small">
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
    <center>
    <!--<div class="msg"><?php echo __('no_data_found', true); ?></div>-->
    </center>
<?php }
else
{ ?>
    <div>

        <table style="width: 100%;" class="footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
            <tbody>

                <tr>
                    <td>
                        <?php __('Re-rate Method')?> 
                    </td>
                    <td>
    <?php echo __('Type', true); ?>:
                    </td>
                    <td>
                        <select name="rerate_type">
                            <option value="1" <?php echo isset($_GET['rerate_type']) && $_GET['rerate_type'] == "1" ? 'selected' : '' ?>><?php __('Origination')?></option>
                            <option value="2" <?php echo isset($_GET['rerate_type']) && $_GET['rerate_type'] == "2" ? 'selected' : '' ?>><?php __('Termination')?></option>
                            <option value="3" <?php echo isset($_GET['rerate_type']) && $_GET['rerate_type'] == "3" ? 'selected' : '' ?>><?php __('Both')?></option>
                        </select> 
                    </td>
                    <td><?php echo __('Rate Table', true); ?>:</td>
                    <td ><input   type="hidden"  value='query'  name="action_type" id="action_type"/>
    <?php
    echo $form->input('rerate_rate_table', array('options' => $all_rate_table, 'name' => 'rerate_rate_table', 'empty' => '', 'label' => false, 'div' => false, 'type' => 'select', 'selected' => isset($_GET['rerate_rate_table']) ? $_GET['rerate_rate_table'] : ''));
    ?></td>
                    <td><?php echo __('Rerating Time', true); ?>:</td>
                    <td><input type="text" name="rerate_time" onfocus="WdatePicker({dateFmt: 'yyyy-MM-dd HH:mm:ss'});"  style="width:120px;" value="<?php echo isset($_GET['rerate_time']) ? $_GET['rerate_time'] : '' ?>" /></td>
                    <td style="width:130px;"><?php __('Use Same LRN number'); ?></td>
                    <td>
                        <select name="same_lrn_number" style="width:60px;">
                            <option value="true" <?php echo isset($_GET['same_lrn_number']) && $_GET['same_lrn_number'] == "true" ? 'selected' : '' ?>>True</option>
                            <option value="false" <?php echo isset($_GET['same_lrn_number']) && $_GET['same_lrn_number'] == "false" ? 'selected' : '' ?>>False</option>
                        </select>
                    </td>
                    <td><?php __('Use Same Jurisdiction'); ?></td>
                    <td>
                        <select name="same_jur" style="width:60px;">
                            <option value="true" <?php echo isset($_GET['same_jur']) && $_GET['same_jur'] == "true" ? 'selected' : '' ?>><?php __('True')?></option>
                            <option value="false" <?php echo isset($_GET['same_jur']) && $_GET['same_jur'] == "false" ? 'selected' : '' ?>><?php __('False')?></option>
                        </select>
                    </td>
    <?php if ($_SESSION['role_menu']['Tools']['cdrreports:rerating']['model_w'] && $_SESSION['role_menu']['Tools']['cdrreports:rerating']['model_x'])
    { ?>
                        <td>
                            <input type="button" onclick="check_action(this.value)"  class="input in-submit" value="Process">
                            <a href="<?php echo $this->webroot; ?>cdrreports/rerating_list" class="input in-submit" style="color:#FFFFFF; font-weight: normal;"><?php __('Relating List')?></a>
                        </td>
    <?php }
    else
    { ?><td></td><?php } ?>
                </tr>
            </tbody>
        </table>
    </div>
    <div id="toppage"></div>
    <div style="width:100%;" class="overflow_x">
        <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary" style="width: 100%">
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
                        echo "<th rel='8'>&nbsp;&nbsp; " . $order_href . "  &nbsp;&nbsp;</th>";
                    }
                    if ("on" == trim($sip_capture_status))
                    {
                        echo '<th>' . _('SIP Capture') . '</th>';
                    }
                    ?>
                </tr>
            </thead>
            <tbody>
                    <?php for ($i = 0; $i < $loop; $i++)
                    { ?>
                    <tr style="color: #4B9100">
                        <?php
                        for ($ii = 0; $ii < $c; $ii++)
                        {
                            $f = $show_field_array[$ii];
                            if ($f == 'ingress_client_cost' || $f == 'egress_cost' || $f == 'ingress_client_rate' || $f == 'egress_rate')
                            {
                                $field = $appCommon->currency_rate_conversion($mydata[$i][0][$f]);
                            }
                            elseif ($f == 'egress_erro_string')
                            {
                                // echo $mydata[$i][0][$f].'<br />';
                                $field = $appCommon->convert_error($mydata[$i][0][$f]);
                            }
                            elseif ($f == 'egress_dnis_type')
                            {
                                $field = $appCommon->convert_dnis_type($mydata[$i][0][$f]);
                            }
                            elseif ($f == 'ingress_dnis_type')
                            {
                                $field = $appCommon->convert_dnis_type($mydata[$i][0][$f]);
                            }
                            else
                            {
                                //echo 'A'.'<br />';
                                $field = $appCommon->cutomer_cdr_field($f, $mydata[$i][0][$f]);
                            }
                            if (trim($field) == '')
                            {
                                echo "<td  class='in-decimal'  style='text-align:center;color:#6694E3;'><strong  style='color:red;'>" . __('Unknown', true) . "</strong></td>";
                            }
                            else
                            {
                                echo " <td  class='in-decimal'  style='text-align:center;color:#6694E3;white-space:nowrap;overflow:hidden; width:auto;'>" . $field . "</td>";
                            }
                        }
                        if ("on" == trim($sip_capture_status))
                        {
                            echo "<td><a href=\"{$this->webroot}cdrreports/cdr_capture/{$mydata[$i][0]['id']}\">View</a></td>";
                        }
                        ?>
                    </tr>
    <?php } ?>
            </tbody>
        </table>
    </div>
    <div class="bottom row-fluid">
        <div class="pagination pagination-large pagination-right margin-none">
    <?php echo $this->element('page'); ?>
        </div> 
    </div>
<?php
}?>