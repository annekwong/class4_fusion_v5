<style type="text/css">
    #ignore {
        float: left;
    }
    #ignore li {
        padding: 3px;
        padding-left: 40px;
        float: left;
    }
    #block_404_number_time{
        width: 142px;
        margin: 0 10px 0 10px;
    }
    #enable404_blocking{
       float: left;
       margin-top: 7px;
    }
</style>
<div class="widget">
    <div class="widget-head"><h4 class="heading"><?php __('Basic Info') ?></h4></div>
    <div class="widget-body">

    <table class="form footable table table-striped dynamicTable tableTools table-bordered  table-white default footable-loaded">
        <colgroup><col width="37%"><col width="63%">

        </colgroup>
        <fieldset>
        <tr>
            <td class="align_right padding-r10"><?php echo __('Ingress Name', true); ?> </td>
            <td><?php echo $form->input('alias', array('id' => 'alias','class' => 'width220 validate[required,custom[onlyLetterNumberLineSpace]]', 'label' => false, 'value' => $post['Gatewaygroup']['alias'], 'div' => false, 'type' => 'text', 'maxlength' => '106', 'check' => 'strNum')); ?>
            </td>
        </tr>
        <?php if (isset($_GET['viewtype']) && $_GET['viewtype'] == 'client') { ?>
            <tr style="display: none">
                <td align="right"><?php __('Carrier') ?></td>
                <td style="height:40px; line-height:40px;"><?php echo $c[$post['Gatewaygroup']['client_id']]; ?>
                    <input type="hidden" name="data[Gatewaygroup][client_id]" value="<?php echo $limit_data['client_id']; ?>">
                </td>
            </tr>
        <?php } else { ?>
            <tr style="">
                <td class="align_right padding-r10"><?php __('Carrier') ?></td>
                <!--
                <td style="height:40px; line-height:40px;"><?php echo $c[$post['Gatewaygroup']['client_id']]; ?>
                    <input type="hidden" name="data[Gatewaygroup][client_id]" value="<?php echo $limit_data['client_id']; ?>">
                </td>
                -->
                <td><?php echo $form->input('client_id', array('options' => $c, 'empty' => '', 'label' => false, 'class' => 'select', 'div' => false, 'value' => $limit_data['client_id'], 'type' => 'select')); ?></td>
            </tr>
        <?php } ?>
        <?php if ($is_enable_type): ?>
            <tr>
                <td><?php __('Type'); ?></td>
                <td>
                    <?php
                    echo $form->input('trunk_type', array('options' => array(1 => 'Class4', 2 => 'Exchange'), 'label' => false, 'class' => 'select', 'div' => false, 'type' => 'select', 'selected' => $post['Gatewaygroup']['trunk_type']));
                    ?>
                </td>
            </tr>
            <tr id="transaction_fee_panel">
                <td class="align_right padding-r10"><?php __('Transaction Fee'); ?></td>
                <td>
                    <?php
                    echo $form->input('transaction_fee_id', array('options' => $transation_fees, 'label' => false, 'class' => 'select', 'div' => false, 'type' => 'select'));
                    ?>
                </td>
            </tr>
        <?php endif; ?>
        <tr>
            <td class="align_right padding-r10"><?php echo __('Media Type', true); ?></td>
            <td align="left"><?php
                if (Configure::read('project_name') == 'partition') {
                    $t = array('2' => 'Bypass Media', '1' => 'Proxy Media');
//                    $t = array('0' => 'Transcoding Media', '2' => 'Bypass Media', '1' => 'Proxy Media');
                } else {
                    $t = array('1' => 'Proxy Media ', '2' => 'Bypass Media');
//                    $t = array('0' => 'Proxy Media + Transcoding', '1' => 'Proxy Media ', '2' => 'Bypass Media');
                }
                echo $form->input('media_type', array('id'=>'media_type','options' => $t, 'label' => false, 'class' => 'select', 'selected' => $post['Gatewaygroup']['media_type'], 'div' => false, 'type' => 'select'));
                ?>
            </td>
        </tr>
        <tr id="rtp_timeout" <?php if($post['Gatewaygroup']['media_type'] != 1): ?> style="display:none;"<?php endif; ?>>
            <td class="align_right padding-r10"><?php echo __('RTP Timeout', true); ?></td>
            <td align="left">
                <?php echo $form->input('media_timeout', array('class' => 'width220 validate[custom[onlyNumberSp]]', 'id' => 'media_timeout', 'label' => false, 'div' => false, 'value' => $post['Gatewaygroup']['media_timeout'], 'type' => 'text', 'maxlength' => '8')); ?>&nbsp;s
            </td>
        </tr>
        <tr>
            <td class="align_right padding-r10"><?php echo __('lowprofit') ?></td>
            <td align="left">
                <?php echo $form->input('profit_margin', array('label' => false, 'value' => '0', 'div' => false, 'type' => 'text', 'class' => 'in-decimal input in-text validate[custom[number]]', 'maxlength' => '6', 'style' => 'width:100px', 'value' => $post['Gatewaygroup']['profit_margin'])) ?>
                <?php echo $xform->input('profit_type', array('options' => Array(1 => 'Percentage', 2 => 'Value'), 'style' => 'width:102px', 'value' => $post['Gatewaygroup']['profit_type'])) ?>
            </td>
        </tr>

        <!--        <tr>-->
<!--            <td class="align_right padding-r10">--><?php //__('proto') ?><!--</td>-->
<!--            <td align="left">--><?php //echo $form->input('proto', array('label' => false, 'value' => $post['Gatewaygroup']['proto'], 'div' => false, 'type' => 'select', 'options' => Array(Resource::RESOURCE_PROTO_ALL => 'All', Resource::RESOURCE_PROTO_SIP => 'SIP', Resource::RESOURCE_PROTO_PROTO => 'H323'))); ?>
<!--            </td>-->
<!--        </tr>-->



        <tr>
            <td class="align_right padding-r10"><?php __('Ignore Early media') ?></td>
            <td><?php
                $ignore_arr = array(0 => 'NONE', 1 => '180 and 183', 2 => '180 only', 3 => '183 only');
                echo $form->input('ignore_ring_early_media', array('options' => $ignore_arr, 'label' => false, 'class' => 'select', 'div' => false, 'type' => 'select', 'selected' => $post['Gatewaygroup']['ignore_ring_early_media']));
                ?>
            </td>
        </tr>
        <tr>
            <td class="align_right padding-r10"><?php __('active') ?></td>
            <td><?php
                $post['Gatewaygroup']['active'] == 't' ? $au = 'true' : $au = 'false';
                echo $form->input('active', array('options' => array('true' => 'True', 'false' => 'False'), 'label' => false, 'class' => 'select', 'div' => false, 'type' => 'select', 'selected' => $au));
                ?>
                <input type="hidden" name="[Gatewaygroup][t3']" value="true">
            </td>
        </tr>
        <tr>
            <td class="align_right padding-r10">Enable T38</td>
            <td><?php
                $post['Gatewaygroup']['t38'] == 't' ? $t38 = 'true' : $t38 = 'false';
                echo $form->input('t38', array('options' => array('true' => 'True', 'false' => 'False'), 'label' => false, 'class' => 'select', 'div' => false, 'type' => 'select', 'selected' => $t38));
                ?>
            </td>
        </tr>
        <fieldset>
            <?php echo $this->element("gatewaygroups/cid_blocking") ?>
        </fieldset>

<!--        <tr>-->
<!--            <td class="align_right padding-r10">--><?php //__('Dipping Rate') ?><!--</td>-->
<!--            <td>-->
<?php //echo $form->input('lnp_dipping_rate', array('class' => 'width220 validate[custom[number]]', 'id' => 'lnp_dipping_rate', 'label' => false, 'div' => false, 'value' => $post['Gatewaygroup']['lnp_dipping_rate'], 'type' => 'text', 'maxlength' => '10')); ?>
<!--            </td>-->
<!--        </tr>-->
        <!--		<tr>
                                <td><?php __('RFC 2833'); ?></td>
                                <td><?php
        $post['Gatewaygroup']['rfc_2833'] == 't' ? $rfc2833 = 'true' : $rfc2833 = 'false';
        echo $form->input('rfc_2833', array('options' => array('true' => 'True', 'false' => 'False'), 'label' => false, 'class' => 'select', 'div' => false, 'type' => 'select', 'selected' => $rfc2833));
        ?>
                                </td>
                        </tr>-->
<!--        <tr>-->
<!--            <td class="align_right padding-r10">--><?php //__('User Dipping From'); ?><!--</td>-->
<!--            <td>--><?php
//                $post['Gatewaygroup']['lnp_dipping'] == 't' ? $lnp_dipping = 'true' : $lnp_dipping = 'false';
//                echo $form->input('lnp_dipping', array('options' => array('false' => 'LRN Server', 'true' => 'Client SIP Header'), 'label' => false, 'class' => 'select', 'div' => false, 'type' => 'select', 'selected' => $lnp_dipping));
//                ?>
<!--            </td>-->
<!--        </tr>-->

        <!--
<tr>
<td><?php __('Delay Bye Limit'); ?></td>
<td>
<?php echo $form->input('delay_bye_limit', array('id' => 'delay_bye_limit', 'label' => false, 'div' => false, 'type' => 'text', 'maxlength' => '5', 'value' => $post['Gatewaygroup']['delay_bye_limit'])); ?>        
</td>
</tr>
        -->
        <tr>
            <td class="align_right padding-r10"><?php __('Ignore Early NOSDP'); ?></td>
            <td><?php
                echo $form->input('ignore_early_nosdp', array('options' => array('False', 'True'), 'label' => false, 'class' => 'select', 'div' => false, 'type' => 'select', 'selected' => $post['Gatewaygroup']['ignore_early_nosdp']));
                ?>
            </td>
        </tr>
        <tr>
            <td class="align_right padding-r10"><?php __('Enable Global 404 Blocking'); ?></td>
            <td>
                <input type="checkbox" id="enable404_blocking" <?php if(isset($post['Gatewaygroup']['block_404_number_time']) && $post['Gatewaygroup']['block_404_number_time'] > 0) echo 'checked'; ?>>
                <div <?php if(isset($post['Gatewaygroup']['block_404_number_time']) &&  $post['Gatewaygroup']['block_404_number_time'] == 0) echo 'class="hidden"'; ?> id="div_block_404">
                    <?php
                        $classBlock = (isset($post['Gatewaygroup']['block_404_number_time']) &&  $post['Gatewaygroup']['block_404_number_time'] == 0) ? "hidden" : '';
                    ?>
                    <?php
                    $val = isset($post['Gatewaygroup']['block_404_number_time']) ? ($post['Gatewaygroup']['block_404_number_time'] / 86400) : '';
                    echo $form->input('block_404_number_time', array('class' => 'width220 ' . $classBlock . ' validate[custom[onlyNumber]]', 'id' => 'block_404_number_time', 'label' => false, 'div' => false, 'type' => 'text', 'value' => $val)); ?>&nbsp;days
                </div>
            </td>
        </tr>





</fieldset>



<script type="text/javascript">
    $(function(){
       $("#media_type").change(function(){
           var value = $(this).val();
           $("#rtp_timeout").hide();
           if(value == 1)
           {
               $("#rtp_timeout").show();
           }
       });
    });
</script>







        <tr  style="display:none;">
            <td colspan="2" class="value">
                <div class="cb_select" style="height:30px; line-height:30px;text-align: left; border:none;">
                    <div>
                        <div style="display:none;">
                            <?php empty($post['Gatewaygroup']['lnp'])?$au='false':$au='checked'; echo $form->checkbox('lnp',array('checked'=>$au,'style'=>'margin-left:40px'))
                            ?>
                            <!--
            <label for="cp_modules-c_invoices"><?php echo __('lrn',true);?></label>
	       <?php empty($post['Gatewaygroup']['lrn_block'])?$au='false':$au='checked'; echo $form->checkbox('lrn_block',array('checked'=>$au,'style'=>'margin-left:40px'))
                            ?>-->
                            <label for="cp_modules-c_stats_summary"><?php echo __('Block LRN',true);?></label>
                            <?php empty($post['Gatewaygroup']['dnis_only'])?$au='false':$au='checked'; echo $form->checkbox('dnis_only',array('checked'=>$au,'style'=>'margin-left:40px'))
                            ?>
                            <label for="cp_modules-c_stats_summary"><?php echo __('DNIS Only',true);?></label>
                        </div>
                    </div>
                </div>
            </td>
        </tr>






        <fieldset>

            <!--
    <tr>
            <td><?php __('Block Higher'); ?></td>
            <td><?php
            $post['Gatewaygroup']['lrn_block'] == 't' ? $lrn_block = 'true' : $lrn_block = 'false';
            echo $form->input('lrn_block', array('options' => array('true' => 'True', 'false' => 'False'), 'label' => false, 'class' => 'select', 'div' => false, 'type' => 'select', 'selected' => $lrn_block));
            ?>
            </td>
    </tr>
    -->
<!--            <tr>-->
<!--                <td class="align_right padding-r10">--><?php //__('Bill By'); ?><!--</td>-->
<!--                <td>-->
<!--                    --><?php
//                    echo $form->input('bill_by', array('options' => array(0 => 'DNIS', 1 => 'LRN', 2 => 'LRN BLOCK', 3 => 'LRN Block Higher', 4 => 'Follow Rate Deck'), 'label' => false, 'class' => 'select', 'div' => false, 'type' => 'select', 'selected' => $post['Gatewaygroup']['bill_by']));
//                    ?>
<!--                </td>-->
<!--            </tr>-->
            <!--
<tr>
<td><?php __('Trunk Type'); ?></td>
<td>
    <?php
            echo $form->input('trunk_type', array('options' => array(1 => 'class4', 2 => 'exchange'), 'label' => false, 'class' => 'select', 'div' => false, 'type' => 'select', 'selected' => $post['Gatewaygroup']['trunk_type']));
            ?>
</td>
</tr>
    -->
            <?php if ($is_did_enable): ?>
                <tr style="display: none">
                    <td class="align_right padding-r10"><?php __('Type'); ?></td>
                    <td>
                        <?php
                        echo $form->input('trunk_type2', array('options' => array(0 => 'Termination Traffic', 1 => 'DID Traffic'), 'label' => false, 'class' => 'select', 'div' => false, 'type' => 'select', 'selected' => $post['Gatewaygroup']['trunk_type2']));
                        ?>
                    </td>
                </tr>
                <tr id="did_billing_method_tr">
                    <td class="align_right padding-r10"><?php __('Billing Method'); ?></td>
                    <td>
                        <?php
                        echo $form->input('billing_method', array('options' => array(0 => 'By Minute', 1 => 'By Port'), 'label' => false, 'class' => 'select', 'div' => false, 'type' => 'select', 'selected' => $post['Gatewaygroup']['billing_method']));
                        ?>
                    </td>
                </tr>
                <tr id="did_rate_table_tr">
                    <td class="align_right padding-r10"><?php __('Rate Table'); ?></td>
                    <td>
                        <?php
                        echo $form->input('rate_table', array('options' => $rate, 'label' => false, 'class' => 'select', 'div' => false, 'type' => 'select', 'selected' => $post['Gatewaygroup']['did_rate_table_id']));
                        ?>
                    </td>
                </tr>
                <tr id="did_amount_per_port_tr">
                    <td class="align_right padding-r10"><?php __('Per Port Amount'); ?></td>
                    <td>
                        <?php $amount_per_port = isset($post['Gatewaygroup']['amount_per_port']) ? $post['Gatewaygroup']['amount_per_port'] : "0"; ?>
                        <?php echo $form->input('amount_per_port', array('class' => 'validate[custom[number]]', 'id' => 'amount_per_port', 'label' => false, 'div' => false, 'type' => 'text', 'value' => isset($post['Gatewaygroup']['amount_per_port']) ? $post['Gatewaygroup']['amount_per_port'] : 0)); ?>
                    </td>
                </tr>
            <?php endif; ?>
            <!--<tr>
        <td class="align_right padding-r10"><?php __('Rate Profile'); ?></td>
        <td>
            <?php
            echo $form->input('rate_profile', array('options' => array('False', 'True'), 'label' => false, 'class' => 'select', 'div' => false, 'type' => 'select', 'selected' => $post['Gatewaygroup']['rate_profile']));
            ?>
        </td>
    </tr>
    <tr class="rate_profile_control">
        <td class="align_right padding-r10"><?php __('USA'); ?></td>
        <td>
            <?php
            echo $form->input('us_route', array('options' => array('Other', 'Intra', 'Inter', 'Highest'), 'label' => false, 'class' => 'select', 'div' => false, 'type' => 'select', 'selected' => $post['Gatewaygroup']['us_route']));
            ?>
        </td>
    </tr>
    <tr class="rate_profile_control">
        <td class="align_right padding-r10"><?php __('US Territories'); ?></td>
        <td>
            <?php
            echo $form->input('us_other', array('options' => array('Other', 'Intra', 'Inter', 'Highest'), 'label' => false, 'class' => 'select', 'div' => false, 'type' => 'select', 'selected' => $post['Gatewaygroup']['us_other']));
            ?>
        </td>
    </tr>
    <tr class="rate_profile_control">
        <td class="align_right padding-r10"><?php __('Canada'); ?></td>
        <td>
            <?php
            echo $form->input('canada_route', array('options' => array('Other', 'Intra', 'Inter', 'Highest'), 'label' => false, 'class' => 'select', 'div' => false, 'type' => 'select', 'selected' => $post['Gatewaygroup']['canada_route']));
            ?>
        </td>
    </tr>
    <tr class="rate_profile_control">
        <td class="align_right padding-r10"><?php __('Non USA/Canada Territories'); ?></td>
        <td>
            <?php
            echo $form->input('canada_other', array('options' => array('Other', 'Intra', 'Inter', 'Highest'), 'label' => false, 'class' => 'select', 'div' => false, 'type' => 'select', 'selected' => $post['Gatewaygroup']['canada_other']));
            ?>
        </td>
    </tr>
    <tr class="rate_profile_control">
        <td class="align_right padding-r10"><?php __('International'); ?></td>
        <td>
            <?php
            echo $form->input('intl_route', array('options' => array('Other', 'Intra', 'Inter', 'Highest'), 'label' => false, 'class' => 'select', 'div' => false, 'type' => 'select', 'selected' => $post['Gatewaygroup']['intl_route']));
            ?>
        </td>
    </tr>
    <tr>
        <td class="align_right padding-r10"><?php __('Rounding Decimal Places'); ?></td>
        <td>
            <?php echo $form->input('rate_decimal', array('class' => 'width220 validate[custom[onlyNumberSp]]', 'label' => false, 'div' => false, 'type' => 'text', 'maxlength' => '5', 'value' => $post['Gatewaygroup']['rate_decimal'])); ?>
        </td>
    </tr>
    <tr>
        <td class="align_right padding-r10"><?php __('Rounding'); ?></td>
        <td>
            <?php echo $form->input('rate_rounding', array('options' => array('Up', 'Down'), 'label' => false, 'div' => false, 'value' => $post['Gatewaygroup']['rate_rounding'])); ?>
        </td>
    </tr> -->


            <!--<tr>
        <td class="align_right padding-r10"><?php __('Rpid Screen'); ?></td>
        <td>
            <?php echo $form->input('rpid_screen', array('options' => array('None', 'No', 'Yes', 'Proxy'), 'label' => false, 'div' => false, 'value' => $post['Gatewaygroup']['rpid_screen'])); ?>
        </td>
    </tr>
    <tr>
        <td class="align_right padding-r10"><?php __('Rpid Party'); ?></td>
        <td>
            <?php echo $form->input('rpid_party', array('options' => array('None', 'Calling', 'Called', 'Proxy'), 'label' => false, 'div' => false, 'value' => $post['Gatewaygroup']['rpid_party'])); ?>
        </td>
    </tr>
    <tr>
        <td class="align_right padding-r10"><?php __('Rpid Id Type'); ?></td>
        <td>
            <?php echo $form->input('rpid_id_type', array('options' => array('None', 'Subscriber', 'User', 'Term', 'Proxy'), 'label' => false, 'div' => false, 'value' => $post['Gatewaygroup']['rpid_id_type'])); ?>
        </td>
    </tr>
    <tr>
        <td class="align_right padding-r10"><?php __('Rpid Privacy'); ?></td>
        <td>
            <?php echo $form->input('rpid_privacy', array('options' => array('None', 'Full', 'Name', 'Url', 'OFF', 'Ipaddr', 'Proxy'), 'label' => false, 'div' => false, 'value' => $post['Gatewaygroup']['rpid_privacy'])); ?>
        </td>
    </tr>
    <tr>
        <td class="align_right padding-r10"><?php __('Display Name'); ?></td>
        <td>
            <?php echo $form->input('display_name', array('options' => array('False', 'True'), 'label' => false, 'div' => false, 'value' => $post['Gatewaygroup']['display_name'])); ?>
        </td>
    </tr> -->
        </fieldset>
        <!--
<div id="support_panel">
                    <label title="Remote-Party-ID">RPID</label>
<?php echo $form->input('rpid', array('label' => false, 'div' => false, 'type' => 'checkbox', 'checked' => $post['Gatewaygroup']['rpid'] ? true : false)); ?>
                    <label title="P-Asserted-Identity">PAID</label>
<?php echo $form->input('paid', array('label' => false, 'div' => false, 'type' => 'checkbox', 'checked' => $post['Gatewaygroup']['paid'] ? true : false)); ?>
                    <label title="isup-oli">OLI</label>
<?php echo $form->input('oli', array('label' => false, 'div' => false, 'type' => 'checkbox', 'checked' => $post['Gatewaygroup']['oli'] ? true : false)); ?>
                    <label title="P-Charge-Info">PCI</label>
<?php echo $form->input('pci', array('label' => false, 'div' => false, 'type' => 'checkbox', 'checked' => $post['Gatewaygroup']['pci'] ? true : false)); ?>
                    <label title="Privacy">PRIV</label>
<?php echo $form->input('priv', array('label' => false, 'div' => false, 'type' => 'checkbox', 'checked' => $post['Gatewaygroup']['priv'] ? true : false)); ?>
                    <label title="Diversion">DIV</label>
<?php echo $form->input('div', array('label' => false, 'div' => false, 'type' => 'checkbox', 'checked' => $post['Gatewaygroup']['div'] ? true : false)); ?>
                </div>
-->
        <tr>
            <td class="align_right padding-r10"><?php __('Authorized'); ?></td>
            <td>
                <?php
//                var_dump($hosts[0]);
                ?>
                <select name='reg_type' id='host_authorize' onchange='check_host();'>
                    <option value="0"><?php __('Authorized by IP Only') ?></option>
                    <?php if (array_keys_value($hosts[0], '0.reg_type') == 1): ?>
                        <option selected value="1"><?php __('Authorized by SIP Registration') ?> </option>
                    <?php else: ?>
                        <option value="1"><?php __('Authorized by SIP Registration') ?> </option>
                    <?php endif; ?>

                    <?php if (array_keys_value($hosts[0], '0.reg_type') == 2): ?>
                        <option selected value="2"><?php __('Register to gateway') ?> </option>
                    <?php else: ?>
                        <option value="2"><?php __('Register to gateway') ?> </option>
                    <?php endif; ?>
                </select>
                </select>
            </td>
        </tr>
    </table>
    </div>
</div>
<?php echo $this->element("gatewaygroups/host_edit") ?>
<?php #************************************************   ?>
<?php echo $this->element("gatewaygroups/resource_prefix") ?>


<div class="widget" data-toggle="collapse-widget" data-collapse-closed="true"  id="timeout_setting_div">
    <div class="widget-head"><h4 class="heading"><?php __('Timeout Settings') ?></h4></div>
    <div class="widget-body">
        <table class="form footable table table-striped dynamicTable tableTools table-bordered  table-white default footable-loaded">
            <colgroup><col width="37%"><col width="63%">

            </colgroup>
            <tr>
                <td class="align_right padding-r10"><?php __('pddtimeout') ?></td>
                <td><?php echo $form->input('wait_ringtime180', array('class' => 'width220 validate[custom[onlyNumberSp]]', 'id' => 'wait_ringtime180', 'label' => false, 'div' => false, 'value' => $post['Gatewaygroup']['wait_ringtime180'], 'type' => 'text', 'maxlength' => '8')); ?>&nbsp;ms
                </td>
            </tr>
            <tr>
                <td class="align_right padding-r10"><?php __('Min Duration'); ?></td>
                <td><?php echo $form->input('delay_bye_second', array('class' => 'width220 validate[custom[onlyNumberSp]]', 'id' => 'delay_bye_second', 'label' => false, 'div' => false, 'type' => 'text', 'maxlength' => '5', 'value' => $post['Gatewaygroup']['delay_bye_second'])); ?>&nbsp;s
                </td>
            </tr>
            <tr>
                <td class="align_right padding-r10"><?php __('Max Duration'); ?></td>
                <td><?php echo $form->input('max_duration', array('class' => 'width220 validate[custom[onlyNumberSp]]', 'id' => 'max_duration', 'label' => false, 'div' => false, 'type' => 'text', 'maxlength' => '5', 'value' => $post['Gatewaygroup']['max_duration'])); ?>&nbsp;s
                </td>
            </tr>
            <tr>
                <td class="align_right padding-r10"><?php __('Ring Timer'); ?></td>
                <td>
                    <?php echo $form->input('ring_timeout', array('class' => 'width220 validate[min[1],max[60],custom[onlyNumberSp]]', 'id' => 'ring_timeout', 'label' => false, 'div' => false, 'type' => 'text', 'maxlength' => '5', 'value' => $post['Gatewaygroup']['ring_timeout'])); ?> s
                </td>
            </tr>
        </table>
    </div>
</div>



<!--<div class="widget" data-toggle="collapse-widget" data-collapse-closed="true">-->
<!--    <div class="widget-head"><h4 class="heading">--><?php //__('Re-invite Settings') ?><!--</h4></div>-->
<!--    <div class="widget-body">-->
<!--        <table class="form footable table table-striped dynamicTable tableTools table-bordered  table-white default footable-loaded">-->
<!--            <colgroup><col width="37%"><col width="63%">-->
<!---->
<!--            </colgroup>-->
<!--            <tr>-->
<!--                <td class="align_right padding-r10">--><?php //__('Re-invite'); ?><!--</td>-->
<!--                <td>-->
<!--                    --><?php
//                    echo $form->input('re_invite', array('options' => array('False', 'True'), 'label' => false, 'class' => 'select', 'div' => false, 'type' => 'select', 'selected' => $post['Gatewaygroup']['re_invite']));
//                    ?>
<!--                </td>-->
<!--            </tr>-->
<!--            <tr>-->
<!--                <td class="align_right padding-r10">--><?php //echo __('Re-invite interval', true); ?><!-- </td>-->
<!--                <td>-->
<!--                    --><?php //echo $form->input('re_invite_interval', array('class' => 'width220 validate[min[5],max[60],custom[onlyNumberSp]]', 'label' => false, 'div' => false, 'value' => $post['Gatewaygroup']['re_invite_interval'], 'type' => 'text', 'maxlength' => '2')); ?><!-- s-->
<!--                </td>-->
<!--            </tr>-->
<!---->
<!--        </table>-->
<!--    </div>-->
<!--</div>-->

<script>
    $(function () {
        $("#enable404_blocking").click(function () {
            if($(this).is(':checked')) {
                $("#div_block_404").removeClass('hidden');
                $("#div_block_404 > input").removeClass('hidden');
            } else {
                $("#div_block_404").addClass('hidden');
                $("#div_block_404 > input").addClass('hidden').val(0);
            }
        });
    });
</script>
