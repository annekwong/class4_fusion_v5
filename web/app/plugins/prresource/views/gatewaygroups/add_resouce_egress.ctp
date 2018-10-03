<style type="text/css">
    .form tr{ height:30px; line-height:30px;}
    .form .label2 {
        font-size: 12px;
        width: 40%;
    }
    table input {width:100px;}
    .ui-dialog{border:1px solid #7FAF00;}
</style>

<ul class="breadcrumb">
<?php if(isset($_GET['viewtype']) && $_GET['viewtype'] == 'client'):?>
    <li><?php __('You are here'); ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Management') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Carrier'); if(isset($client_name) && $client_name): echo '['.$client_name.']'?><?php endif;?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Add Egress Trunk') ?></li>
<?php else:?>
    <li><?php __('You are here'); ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Routing') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Add Egress Trunk') ?></li>
<?php endif;?>
</ul>


<div class="heading-buttons">
    <h4 class="heading"><?php __('Add Egress Trunk') ?></h4>

</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
    <?php if ($_SESSION['login_type'] != 3) { ?>
        <?php if(isset($_SESSION['role_menu']['Template']['template']['model_w']) && $_SESSION['role_menu']['Template']['template']['model_w'] && $have_template): ?>
            <?php if(isset($portal_client_id)): ?>
                <a class="link_btn btn btn-primary btn-icon glyphicons circle_plus" href="<?php echo $this->webroot ?>template/add_resource_by_template/1/<?php echo $portal_client_id . '/' . 'portal'; ?>">
                    <i></i> <?php __('Create new by Template')?>
                </a>
            <?php else: ?>
                <a class="link_btn btn btn-primary btn-icon glyphicons circle_plus" href="<?php echo $this->webroot ?>template/add_resource_by_template/1">
                    <i></i> <?php __('Create new by Template')?>
                </a>
            <?php endif;?>
        <?php endif; ?>
    <?php } ?>
    <?php if(isset($portal_client_id)): ?>
        <a href="<?php echo $this->webroot ?>clients/view_egress" class="link_back btn btn-icon btn-inverse glyphicons circle_arrow_left"><i></i><?php echo __('goback', true); ?></a>
    <?php else: ?>
        <a href="<?php echo $this->webroot ?>prresource/gatewaygroups/view_egress?<?php echo $$hel->getParams('getUrl') ?>" class="link_back btn btn-icon btn-inverse glyphicons circle_arrow_left"><i></i><?php echo __('goback', true); ?></a>
    <?php endif; ?>
</div>
<div class="clearfix"></div>
<div class="innerLR">

    <div class="widget widget-heading-simple widget-body-white">
        <div class="widget-body">
            <!--
                            <ul class="tabs">
              
                 <li  class="active"><a><img width="16" height="16" src="<?php echo $this->webroot ?>images/list.png"/>System Information</a></li>
               </ul>
            -->
            <?php echo $form->create('Gatewaygroup', array('action' => 'add_resouce_egress')); ?> <?php echo $form->input('ingress', array('label' => false, 'value' => 'false', 'div' => false, 'type' => 'hidden')); ?> <?php echo $form->input('egress', array('label' => false, 'value' => 'true', 'div' => false, 'type' => 'hidden')); ?>
            <input type="hidden" name="t38" value="true">
            <?php echo $form->input('back_url', array('label' => false,'div' => false, 'type' => 'hidden', 'value' => $back_url)); ?>

            <!-- COLUMN 1 -->
            <?php //**********系统信息**************?>
            <table class="form footable table table-striped dynamicTable tableTools table-bordered  table-white default footable-loaded">
                <colgroup><col width="37%"><col width="63%">

                </colgroup>
                <fieldset><!--<legend><?php __('Egress Trunk') ?></legend>-->

                    <tr>
                        <td class="align_right padding-r10"><?php echo __('Egress Name', true); ?></td>
                        <td>
                            <?php echo $form->input('alias', array('class' => 'width220 validate[required,custom[onlyLetterNumberLineSpace],minSize[2]]', 'id' => 'alias', 'label' => false, 'div' => false, 'type' => 'text', 'maxlength' => '100')); ?>
                        </td>
                    </tr>

                    <?php
                    if (isset($_GET['viewtype']) && ($_GET['viewtype'] == 'client' || $_GET['viewtype'] == 'wizard'))
                    {
                        ?>
                        <?php echo $form->input('client_id', array('label' => false, 'value' => array_keys_value($_GET, 'query.id_clients'), 'div' => false, 'type' => 'hidden')); ?>
                        <?php
                    }
                    else
                    {
                        ?>
                        <tr>
                            <td class="align_right padding-r10"><?php echo __('client') ?></td>
                            <td><?php
                                if(isset($portal_client_id)){
                                    echo $portal_client_name;
                                    echo $form->input('client_id', array('label' => false,'div' => false, 'type' => 'hidden', 'value' => $portal_client_id));
                                    echo $form->input('portal_carrier', array('label' => false,'div' => false, 'type' => 'hidden', 'value' => true));
                                } else
                                    echo $form->input('client_id', array('options' => $c, 'empty' => '', 'label' => false, 'class' => 'select', 'div' => false, 'type' => 'select', 'selected' => array_keys_value($_GET, 'query.id_clients')));
                                ?>
                            </td>
                        </tr>
                    <?php } ?>
                    <tr>
                        <td class="align_right padding-r10"><?php echo __('Media Type', true); ?></td>
                        <td>
                            <?php
                            if (Configure::read('project_name') == 'partition')
                            {
//                                            $t = array('0' => __('Transcoding Media',true), '2' => __('Bypass Media',true), '1' => __('Proxy Media',true));
                                $t = array('1' => 'Proxy Media','2' => 'Bypass Media');
                            }
                            else
                            {
                                $t = array('1' => __('Proxy Media',true), '2' => __('Bypass Media',true));
//                                            $t = array('0' => __('Proxy Media + Transcoding',true), '1' => __('Proxy Media',true), '2' => __('Bypass Media',true));
                            }
                            echo $form->input('media_type', array('id'=>'media_type','options' => $t, 'label' => false, 'class' => 'select', 'div' => false, 'type' => 'select','value'=>2));
                            ?>
                        </td>
                    </tr>

                    <tr id="rtp_timeout" style="display:none;">
                        <td class="align_right padding-r10"><?php echo __('RTP Timeout', true); ?></td>
                        <td align="left">
                            <?php echo $form->input('media_timeout', array('class' => 'width220 validate[custom[onlyNumberSp]]', 'id' => 'media_timeout', 'label' => false, 'div' => false, 'type' => 'text', 'maxlength' => '8')); ?>&nbsp;<?php __('s')?>
                        </td>
                    </tr>
                    <tr>
                        <td class="align_right padding-r10"><?php echo __('lowprofit') ?></td>
                        <td>
                            <?php echo $form->input('profit_margin', array('label' => false, 'value' => '0', 'div' => false, 'type' => 'text', 'class' => 'in-decimal input in-text validate[custom[number]]', 'maxlength' => '6', 'style' => 'width:100px')) ?>
                            <?php echo $xform->input('profit_type', array('options' => Array(1 => 'Percentage', 2 => 'Value'), 'style' => 'width:102px')) ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="align_right padding-r10"><?php __('calllimit') ?></td>
                        <td>
                            <?php echo $form->input('capacity', array('class' => 'width220 validate[custom[onlyNumberSp]]', 'id' => 'totalCall', 'label' => false, 'div' => false, 'type' => 'text', 'maxlength' => '8')); ?>
                            <span id="max_call_limit"></span>
                        </td>
                    </tr>
                    <tr>
                        <td class="align_right padding-r10"><?php __('cps') ?></td>
                        <td>
                            <?php echo $form->input('cps_limit', array('class' => 'width220 validate[custom[onlyNumberSp]]', 'id' => 'totalCPS', 'label' => false, 'div' => false, 'type' => 'text', 'maxlength' => '8')); ?>
                            <span id="max_cps_limit"></span>
                        </td>
                    </tr>


                    <!--                                <tr>-->
                    <!--                                    <td class="align_right padding-r10">--><?php //__('proto') ?><!--</td>-->
                    <!--                                    <td>-->
                    <!--                                        --><?php //echo $form->input('proto', array('label' => false, 'div' => false, 'type' => 'select', 'options' => Array(Resource::RESOURCE_PROTO_ALL => __('ALL',true), Resource::RESOURCE_PROTO_SIP => __('SIP',true), Resource::RESOURCE_PROTO_PROTO => __('H323',true)), 'selected' => Resource::RESOURCE_PROTO_SIP)); ?>
                    <!--                                    </td>-->
                    <!--                                </tr>-->
                    <tr style="display:none;">
                        <td class="align_right padding-r10"><?php __('Ignore Early media') ?></td>
                        <td>
                            <?php
                            $ignore_arr = array(0 => 'NONE', 1 => '180 and 183', 2 => '180 only', 3 => '183 only');
                            echo $form->input('ignore_ring_early_media', array('options' => $ignore_arr, 'label' => false, 'class' => 'select', 'div' => false, 'type' => 'select'));
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="align_right padding-r10"><?php __('active') ?></td>
                        <td>
                            <?php
                            $au = 'true';
                            echo $form->input('active', array('options' => array('true' => __('True',true), 'false' => __('False',true)), 'label' => false, 'class' => 'select', 'div' => false, 'type' => 'select', 'selected' => $au));
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="align_right padding-r10">Enable T38</td>
                        <td>
                           <?php
                            $t38 = 'true';
                            echo $form->input('t38', array('options' => array('true' => __('True',true), 'false' => __('False',true)), 'label' => false, 'class' => 'select', 'div' => false, 'type' => 'select', 'selected' => $t38));
                            ?></td>
                    </tr>
                    <tr>

                    <td class="align_right padding-r10"><?php __('pddtimeout') ?></td>
                    <td>
                        <?php echo $form->input('wait_ringtime180', array('class' => 'width220 validate[min[1000],max[60000],custom[onlyNumberSp]]', 'id' => 'wait_ringtime180', 'label' => false, 'div' => false, 'type' => 'text', 'maxlength' => '5', 'value' => $default_timeout['egress_pdd_timeout'])); ?> <?php __('ms')?>
                    </td>
                    </tr>
                    <!--
                                                        <tr>
                                                            <td><?php __('rateTable') ?>:</td>
                                                            <td>
                                <?php
                    echo $form->input('rate_table_id', array('options' => $rate,
                        'empty' => '  ', 'label' => false, 'class' => 'select', 'div' => false, 'type' => 'select'));
                    ?>
                                                                <img id="addratetable" style="cursor:pointer;" src="<?php echo $this->webroot ?>images/add.png"  onclick="showDiv('pop-div','500','200','<?php echo $this->webroot ?>clients/addratetable');" />
                                                            </td>
                                                        </tr>
                                -->
                    <tr style="line-height:1;">
                        <td class="align_right padding-r10"><?php __('HostStrategy') ?></td>
                        <td>
                            <?php
                            $t = array('1' => __('top-down',true), '2' => __('round-robin',true));
                            echo $form->input('res_strategy', array('options' => $t, 'label' => false, 'class' => 'select', 'div' => false, 'type' => 'select'));
                            ?>
                        </td>
                    </tr>
                    <!--                        <tr>
                                    <td><?php __('RFC 2833'); ?></td>
                                    <td>
                                <?php
                    $rfc2833 = 'true';
                    echo $form->input('rfc_2833', array('options' => array('true' => 'True', 'false' => 'False'), 'label' => false, 'class' => 'select', 'div' => false, 'type' => 'select', 'selected' => $rfc2833));
                    ?>
                                    </td>
                                </tr>-->
                    <tr>
                        <td class="align_right padding-r10"><?php __('Pass LRN to Header'); ?></td>
                        <td>
                            <?php
                            $lnp_dipping = 'false';
                            echo $form->input('lnp_dipping', array('options' => array('true' => __('Yes',true), 'false' => __('No',true)), 'label' => false, 'class' => 'select', 'div' => false, 'type' => 'select', 'selected' => $lnp_dipping));
                            ?>
                        </td>
                    </tr>

                    <tr>
                        <td class="align_right padding-r10"><?php __('Min Duration'); ?></td>
                        <td>
                            <?php echo $form->input('delay_bye_second', array('class' => 'width220 validate[custom[onlyNumberSp]]', 'id' => 'delay_bye_second', 'label' => false, 'div' => false, 'type' => 'text')); ?>&nbsp;<?php __('s')?>
                        </td>
                    </tr>
                    <!--
                                <tr>
                                    <td><?php __('Delay Bye Limit'); ?></td>
                                    <td>
                                <?php echo $form->input('delay_bye_limit', array('id' => 'delay_bye_limit', 'label' => false, 'div' => false, 'type' => 'text')); ?>s        
                                    </td>
                                </tr>
                                -->
                    <tr>
                        <td class="align_right padding-r10"><?php __('Max Duration'); ?></td>
                        <td>
                            <?php echo $form->input('max_duration', array('class' => 'width220 validate[custom[onlyNumberSp]]', 'id' => 'max_duration', 'label' => false, 'div' => false, 'type' => 'text', 'value' => $default_timeout['call_timeout'])); ?>&nbsp;<?php __('s')?>
                        </td>
                    </tr>
                    <?php if ($is_did_enable): ?>
                        <!--<tr>
                                        <td class="align_right padding-r10"><?php /*__('Type'); */?></td>
                                        <td>
                                            <?php
                        /*                                            echo $form->input('trunk_type2', array('options' => array(0 => 'Termination Traffic', 1 => 'DID Traffic'), 'label' => false, 'class' => 'select', 'div' => false, 'type' => 'select', 'selected' => 0));
                                                                    */?>
                                        </td>
                                    </tr>
                                    <tr id="did_billing_rule_tr">
                                        <td class="align_right padding-r10"><?php /*__('Orig. Billing Rule'); */?></td>
                                        <td>
                                            <?php
                        /*                                            echo $form->input('billing_rule', array('options' => $routing_rules,
                                                                        'empty' => '  ', 'label' => false, 'class' => 'select', 'div' => false, 'type' => 'select'));
                                                                    */?>
                                        </td>
                                    </tr>
                                    <tr id="did_billing_method_tr">
                                        <td class="align_right padding-r10"><?php /*__('Billing Method'); */?></td>
                                        <td>
                                            <?php
                        /*                                            echo $form->input('billing_method', array('options' => array(0 => __('By Minute',true), 1 => __('By Port',true)), 'label' => false, 'class' => 'select', 'div' => false, 'type' => 'select', 'selected' => 0));
                                                                    */?>
                                        </td>
                                    </tr>-->
                    <?php endif; ?>
                    <tr id="did_rate_table_tr">
                        <td class="align_right padding-r10"><?php __('Rate Table'); ?></td>
                        <td>
                            <?php
                            echo $form->input('rate_table_id', array('options' => $rate_tables,
                                'empty' => '  ', 'label' => false, 'class' => 'select', 'div' => false, 'type' => 'select'));
                            ?>
                            <a><i id="addratetable" style="cursor:pointer;" class="icon-plus" onclick="showDiv('pop-div', '700', '300', '<?php echo $this->webroot ?>clients/addratetable','<?php __('create_%s',false,array(__('rate_table',true))); ?>');" ></i></a>
                        </td>
                    </tr>
                    <!--                                <tr>-->
                    <!--                                    <td class="align_right padding-r10">--><?php //__('Re-invite'); ?><!--</td>-->
                    <!--                                    <td>-->
                    <!--                                        --><?php
                    //                                        echo $form->input('re_invite', array('options' => array( __('False',true), __('True',true)), 'label' => false, 'class' => 'select', 'div' => false, 'type' => 'select'));
                    //                                        ?><!--  -->
                    <!--                                    </td>-->
                    <!--                                </tr>    -->
                    <!--                                <tr>-->
                    <!--                                    <td class="align_right padding-r10">--><?php //echo __('Re-invite interval', true); ?><!-- </td>-->
                    <!--                                    <td>-->
                    <!--                                        --><?php //echo $form->input('re_invite_interval', array('class' => 'width220 validate[min[5],max[60],custom[onlyNumberSp]]', 'label' => false, 'div' => false, 'type' => 'text', 'maxlength' => '2')); ?><!-- --><?php //__('s')?>
                    <!--                                    </td>-->
                    <!--                                </tr>-->
                    <!--
                                <tr>
                                    <td><?php __('Ring Timer'); ?></td>
                                    <td>
                                <?php echo $form->input('ring_timeout', array('class' => 'validate[min[1],max[60],custom[onlyNumberSp]]', 'id' => 'ring_timeout', 'label' => false, 'div' => false, 'type' => 'text', 'maxlength' => '5', 'value' => $default_timeout['ring_timeout'])); ?>s      
                                    </td>
                                </tr>
                                <tr>
                                    <td><?php __('Rate Profile'); ?></td>
                                    <td>
                                <?php
                    echo $form->input('rate_profile', array('options' => array('False', 'True'), 'label' => false, 'class' => 'select', 'div' => false, 'type' => 'select', 'selected' => 0));
                    ?>
                                    </td>
                                </tr>
                                <tr class="rate_profile_control">
                                    <td><?php __('USA'); ?></td>
                                    <td>
                                <?php
                    echo $form->input('us_route', array('options' => array('Other', 'Intra', 'Inter', 'Highest'), 'label' => false, 'class' => 'select', 'div' => false, 'type' => 'select', 'selected' => 0));
                    ?>
                                    </td>
                                </tr>
                                <tr class="rate_profile_control">
                                    <td><?php __('US Territories'); ?></td>
                                    <td>
                                <?php
                    echo $form->input('us_other', array('options' => array('Other', 'Intra', 'Inter', 'Highest'), 'label' => false, 'class' => 'select', 'div' => false, 'type' => 'select', 'selected' => 0));
                    ?>
                                    </td>
                                </tr>
                                <tr class="rate_profile_control">
                                    <td><?php __('Canada'); ?></td>
                                    <td>
                                <?php
                    echo $form->input('canada_route', array('options' => array('Other', 'Intra', 'Inter', 'Highest'), 'label' => false, 'class' => 'select', 'div' => false, 'type' => 'select', 'selected' => 0));
                    ?>
                                    </td>
                                </tr>
                                <tr class="rate_profile_control">
                                    <td><?php __('Non USA/Canada Territories'); ?></td>
                                    <td>
                                <?php
                    echo $form->input('canada_other', array('options' => array('Other', 'Intra', 'Inter', 'Highest'), 'label' => false, 'class' => 'select', 'div' => false, 'type' => 'select', 'selected' => 0));
                    ?>
                                    </td>
                                </tr>
                                <tr class="rate_profile_control">
                                    <td><?php __('International'); ?></td>
                                    <td>
                                <?php
                    echo $form->input('intl_route', array('options' => array('Other', 'Intra', 'Inter', 'Highest'), 'label' => false, 'class' => 'select', 'div' => false, 'type' => 'select', 'selected' => 0));
                    ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td><?php __('Rounding Decimal Places'); ?></td>
                                    <td>
                                <?php echo $form->input('rate_decimal', array('label' => false, 'div' => false, 'type' => 'text', 'maxlength' => '5', 'value' => 6)); ?>     
                                    </td>
                                </tr>
                                <tr>
                                    <td><?php __('Rounding'); ?></td>
                                    <td>
                                <?php echo $form->input('rate_rounding', array('options' => array('Up', 'Down'), 'label' => false, 'div' => false, 'value' => 6)); ?>     
                                    </td>
                                </tr>
                                <tr>
                                    <td><?php __('LRN Prefix'); ?></td>
                                    <td>
                                <?php echo $form->input('lrn_prefix', array('options' => array('False', 'True'), 'label' => false, 'div' => false, 'value' => 1)); ?>
                                    </td>
                                </tr>
                    <tr>
                        <td class="align_right padding-r10"><?php __('DTMF INFO'); ?></td>
                        <td>
                            <?php echo $form->input('info', array('options' => array( __('Disable',true), __('Enable',true)), 'label' => false, 'div' => false)); ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="align_right padding-r10"><?php __('DTMF RFC2833'); ?></td>
                        <td>
                            <?php echo $form->input('rfc2833', array('options' => array( __('Disable',true), __('Enable',true)), 'label' => false, 'div' => false)); ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="align_right padding-r10"><?php __('DTMF INBAND'); ?></td>
                        <td>
                            <?php echo $form->input('inband', array('options' => array( __('Disable',true), __('Enable',true)), 'label' => false, 'div' => false)); ?>
                        </td>
                    </tr>
                    -->
                    <tr>
                        <td class="align_right padding-r10"><?php __('Ring Timer'); ?></td>
                        <td>
                            <?php echo $form->input('ring_timeout', array('class' => 'width220 validate[min[1],max[60],custom[onlyNumberSp]]', 'id' => 'ring_timeout', 'label' => false, 'div' => false, 'type' => 'text', 'maxlength' => '5', 'value' => $default_timeout['ring_timeout'])); ?> s
                        </td>
                    </tr>
                    <!--                                <tr>
                                    <td class="align_right padding-r10"><?php __('DTMF Type'); ?></td>
                                    <td>
                                <?php echo $form->input('dtmf_type', array('options' => array('All', 'Info', 'RFC2833'), 'label' => false, 'div' => false)); ?>     
                                    </td>
                                </tr>
                                <tr>
                                    <td class="align_right padding-r10"><?php __('DTMF Detection'); ?></td>
                                    <td>
                                <?php echo $form->input('dtmf_detect', array('options' => array('Auto', 'Predefined'), 'label' => false, 'div' => false)); ?>     
                                    </td>
                                </tr>-->



                </fieldset>
                <!--
                        <div id="support_panel">
                            <label title="Remote-Party-ID">RPID</label>
                        <?php echo $form->input('rpid', array('label' => false, 'div' => false, 'type' => 'checkbox')); ?>
                            <label title="P-Asserted-Identity">PAID</label>
                        <?php echo $form->input('paid', array('label' => false, 'div' => false, 'type' => 'checkbox')); ?>
                            <label title="isup-oli">OLI</label>
                        <?php echo $form->input('oli', array('label' => false, 'div' => false, 'type' => 'checkbox')); ?>
                            <label title="P-Charge-Info">PCI</label>
                        <?php echo $form->input('pci', array('label' => false, 'div' => false, 'type' => 'checkbox')); ?>
                            <label title="Privacy">PRIV</label>
                        <?php echo $form->input('priv', array('label' => false, 'div' => false, 'type' => 'checkbox')); ?>
                            <label title="Diversion">DIV</label>
<?php echo $form->input('div', array('label' => false, 'div' => false, 'type' => 'checkbox')); ?>
                        </div>
                        -->
                <?php //***************************************费率设置************************************************************    ?>

                <!-- <table class="form">
                            <col style="width:37%;"/>
                            <col style="width:62%;"/>
                            <tr>
                                <td colspan="2" class="value">
                                    <div class="cb_select" style="height:30px; line-height:30px;text-align: left; border:none;">
                                        <div>


                                            <?php echo $form->checkbox('lnp', array('style' => 'margin-left: 40px;')) ?>
                                                        <label for="cp_modules-c_invoices">LRN</label>
<?php echo $form->checkbox('lrn_block', array('style' => 'margin-left: 40px;')) ?>
                                                         <label for="cp_modules-c_stats_summary">Block LRN</label></div>
                                        </div>
                                </td>
                            </tr>
                        </table>-->

                <!-- COLUMN 2 -->
                <script type="text/javascript" src="<?php echo $this->webroot ?>js/jquery.livequery.js"></script>
                <script type="text/javascript">

                    jQuery(function($) {
                        $('#addratetable').live('click', function() {
                            $(this).prev().addClass('clicked');
                            // window.open('<?php echo $this->webroot ?>clients/addratetable', 'addratetable','height=800,width=1000,top=0,left=0,toolbar=no,menubar=no,scrollbars=no, resizable=no,location=no, status=no');
                        });
                    });

                    function test2(id) {
                        $('#GatewaygroupRateTableId').livequery(function() {
                            var $ratetable = $(this);
                            $.getJSON('<?php echo $this->webroot ?>clients/getratetable', function(data) {
                                $.each(data, function(idx, item) {
                                    var option = $("<option value='" + item['id'] + "'>" + item['name'] + "</option>");
                                    if ($ratetable.hasClass('clicked')) {
                                        if (item['id'] == id) {
                                            option.attr('selected', 'selected');
                                        }
                                    }
                                    $ratetable.append(option);
                                });
                                $ratetable.removeClass('clicked');
                            })
                        });
                    }

                    function test3(id) {
                        var $ratetable = $("#GatewaygroupRateTableId");
                        $.getJSON('<?php echo $this->webroot ?>clients/getratetable', function(data) {
                            $.each(data, function(idx, item) {
                                var option = $("<option value='" + item['id'] + "'>" + item['name'] + "</option>");
                                if (item['id'] == id) {
                                    option.attr('selected', 'selected');
                                }
                                $ratetable.append(option);
                            });
                        })
                    }
                </script>

                <fieldset>

                    <?php echo $this->element("gatewaygroups/more_public_fields") ?>
                </fieldset>

                <fieldset>
                    <?php echo $this->element("gatewaygroups/cid_blocking") ?>
                </fieldset>

                <fieldset>
                    <tr>
                        <td class="align_right padding-r10"><?php __('codecs') ?></td>
                        <td>
                            <table class="form">
                                <tr>
                                    <td>
                                        <?php echo $form->input('select1', array('id' => 'select1', 'options' => $d, 'multiple' => true, 'style' => 'width: 200px; height: 250px;', 'label' => false, 'class' => 'select', 'div' => false, 'type' => 'select')); ?>
                                    </td>
                                    <td>
                                        <input  style="width: 60px; height: 30px; margin-left: 0px;" onclick="DoAdd();"  type="button"  value="<?php __('add') ?>"  class="input in-submit btn"/>
                                        <br/><br/>
                                        <input  type="button" style="width: 60px; height: 30px; margin-left: 0px;" onclick="DoDel();" value="<?php __('delete') ?>" class="input in-submit btn" />
                                    </td>
                                    <td>
                                        <?php echo $form->input('select2', array('id' => 'select2', 'options' => '', 'multiple' => true, 'style' => 'width: 200px; height: 250px;', 'label' => false, 'class' => 'select', 'div' => false, 'type' => 'select')); ?>
                                    </td>
                                    <td>
                                        <input  style="width: 60px; height: 30px; margin-left: 0px;" onclick="moveOption('select2', 'up');"  type="button"  value="<?php __('up') ?>" class="input in-submit btn"  />
                                        <br/><br/>
                                        <input  type="button" style="width: 60px; height: 30px; margin-left: 0px;"  onclick="moveOption('select2', 'down');"   value="<?php __('Down') ?>"  class="input in-submit btn"/>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </fieldset>
                <tr>
                    <td class="align_right padding-r10"><?php __('Authorized'); ?></td>
                    <td>
                        <?php
                        //var_dump($hosts[0]);
                        ?>
                        <select name='reg_type' id='host_authorize' onchange='check_host();'>
                            <option value="0"><?php __('Authorized by IP Only') ?></option>
                            <?php if (isset($hosts) && array_keys_value($hosts[0], '0.reg_type') == 1): ?>
                                <option selected value="1"><?php __('Authorized by SIP Registration') ?> </option>
                            <?php else: ?>
                                <option value="1"><?php __('Authorized by SIP Registration') ?> </option>
                            <?php endif; ?>

                            <?php if (isset($hosts) && array_keys_value($hosts[0], '0.reg_type') == 2): ?>
                                <option selected value="2"><?php __('Register to gateway') ?> </option>
                            <?php else: ?>
                                <option value="2"><?php __('Register to gateway') ?> </option>
                            <?php endif; ?>
                        </select>
                    </td>
                </tr>
            </table>


            <!--
                        <fieldset>
                            <legend><?php __('SIP Profile') ?></legend>
                            <table class="list">
                                <thead>
                                    <tr>
                                        <th>VoIP Gateway Name</th>
                                        <th>SIP Profile Name</th>
                                    </tr>
                                </thead>
                                
                                <tbody>
                        <?php
            foreach ($switch_profiles as $switch_profile):
                $profiles = $switch_profile['profiles'];
                ?>
                                                    <tr>
                                                        <input type="hidden" name="$server_names[]" value="<?php echo $switch_profile['name'] ?>" />
                                                        <td>
    <?php echo $switch_profile['name'] ?>
                                                        </td>
                                                        <td>
                                                            <select name="profiles[]">
                                                                <option></option>
                            <?php foreach ($profiles as $profile): ?>
                                                                                <option value="<?php echo $profile[0] ?>"><?php echo $profile[1] ?></option>
    <?php endforeach; ?>
                                                            </select>
                                                        </td>
                                                    </tr>
                            <?php
            endforeach;
            ?>
                                </tbody>
                            </table>
                        </fieldset>
                        -->

            <?php echo $this->element("gatewaygroups/host_edit") ?>
            <?php //echo $this->element("gatewaygroups/add_to_route")   ?>
            <?php
            if ($$hel->_get('viewtype') == 'wizard')
            {
                ?>
                <div id="form_footer">
                    <input type="submit"    onclick="seleted_codes();
                        jQuery('#GatewaygroupAddResouceEgressForm').attr('action', '?nextType=egress&<?php echo $$hel->getParams('getUrl') ?>')" value="<?php echo __('Next Egress') ?>" style="width:80px" />
                    <input type="submit"    onclick="seleted_codes();
                        jQuery('#GatewaygroupAddResouceEgressForm').attr('action', '?nextType=ingress&<?php echo $$hel->getParams('getUrl') ?>')" value="<?php echo __('Next Ingress') ?>" style="width:80px"/>
                    <input type="button"  value="<?php echo __('End') ?>" class="input in-submit" onclick="location = '<?php echo $this->webroot ?>clients/index?filter_id=<?php echo $$hel->_get('query.id_clients') ?>'"/>
                </div>
                <?php
            }
            else
            {
                ?>
                <?php
                if ($_SESSION['role_menu']['Routing']['gatewaygroups:view']['model_w'])
                {
                    ?>
                    <div id="form_footer" class="center">
                        <input type="submit" id="submit_form" value="<?php echo __('submit') ?>" class="input in-submit btn btn-primary"/>
                        <input type="reset"  value="<?php echo __('Reset') ?>"  class="input in-submit btn btn-default"/>
                    </div>
                <?php } ?>
            <?php } ?>
            <?php echo $form->end(); ?>


            <!-----------Add Rate Table----------->
            <div id="pop-div" class="pop-div" style="display:none;">
                <div class="pop-thead">
                    <span></span>
                    <span class="float_right"><a href="javascript:closeDiv('pop-div')" id="pop-close" class="pop-close">&nbsp;</a></span>
                </div>
                <div class="pop-content" id="pop-content"></div>
            </div>
            <div id="pop-clarity" class="pop-clarity" style="display:none;"></div>
        </div>



    </div>
</div>

<script type="text/javascript" src="<?php echo $this->webroot ?>js/gateway.js"></script>
<script type="text/javascript">
    jQuery(document).ready(
        function() {

            $("#media_type").change(function() {
                var value = $(this).val();
                $("#rtp_timeout").hide();
                if (value == 1)
                {
                    $("#rtp_timeout").show();
                }
            });

            $("#GatewaygroupClientId").change(function() {
                var client_id = $(this).val();
                $.ajax({
                    type: "POST",
                    url: "<?php echo $this->webroot; ?>clients/ajax_get_limit",
                    dataType: 'json',
                    data: "id=" + client_id,
                    success: function(msg) {
                        var call_limit = msg.call_limit;
                        var cps_limit = msg.cps_limit;
                        if(cps_limit){

                            $("#max_cps_limit").html('Max:'+cps_limit);
                        } else {
                            $("#max_cps_limit").html(cps_limit);
                        }
                        if(call_limit) {

                            $("#max_call_limit").html('Max:'+call_limit);
                        } else {
                            $("#max_call_limit").html(call_limit);
                        }

                        $("#totalCall").attr('class', "validate[max[" + call_limit + "],custom[onlyNumberSp]]");
                        $("#totalCPS").attr('class', "validate[max[" + cps_limit + "],custom[onlyNumberSp]]");

                    }
                });
            });

            jQuery('#totalCall,#totalCPS').xkeyvalidate({type: 'Num'});
            jQuery('#alias').xkeyvalidate({type: 'strNum'});
            jQuery('#submit_form').click(function() {

                if($('#host_authorize').val() == 2 && $('.ip-host-dd').val() == 'ip' && !$("input[name='accounts[reg_srv_ip][]']").val().toString().match(/^(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/)){
                    jQuery(this).jGrowlError('Wrong IP Format!', {theme: 'jmsg-error'});
                    return false;
                } else if($('#host_authorize').val() == 0 && $('.ip-host-dd').val() == 'ip' && !$("input[name='accounts[ip][]']").val().toString().match(/^(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/)){
                    jQuery(this).jGrowlError('Wrong IP Format!', {theme: 'jmsg-error'});
                    return false;
                }


                var pa = "/[^0-9A-Za-z-\_\s]+/";
                var re = true;
                if (jQuery('#alias').val() == '') {

                } else if (/[^0-9A-Za-z-\_ \|\=\.\-\s]/.test(jQuery("#alias").val()) || jQuery("#alias").val().length > 100) {
                    jQuery(this).addClass('invalid');
                    jQuery(this).jGrowlError('Egress Name,allowed characters: a-z,A-Z,0-9,-,_,space, maximum of 100 characters in length!');
                    return false;

                }
                // if (jQuery('#GatewaygroupProfitMargin').val() && jQuery('#GatewaygroupProfitMargin').val() < '0') {
                //       jQuery('#GatewaygroupProfitMargin').addClass('invalid');
                //       jGrowl_to_notyfy('Min.Profitability can not be negative value!', {theme: 'jmsg-error'});
                //       return false;
                // }

                if (jQuery('#totalCall').val() != '') {
                    if (/\D/.test(jQuery('#totalCall'.val()))) {
                        jQuery(this).addClass('invalid');
                        jQuery(this).jGrowlError('Call limit, must be whole number! ');
                        return false;
                    }
                }
                if (parseInt(jQuery('#wait_ringtime180').val()) < 1000 || parseInt(jQuery('#wait_ringtime180').val()) > 60000) {
                    jQuery(this).addClass('invalid');
                    jQuery(this).jGrowlError('PDD Timeout must a number less than 60000 and greater than 1000!');
                    return false;
                }
                if (jQuery('#totalCPS').val() != '') {
                    if (/\D/.test(jQuery('#totalCPS').val())) {
                        jQuery(this).addClass('invalid');
                        jQuery(this).jGrowlError('CPS Limit, must be whole number!');
                        return false;
                    }

                }

                /*

                 if(jQuery('#ip:visible').val()!=''||!jQuery('#ip:visible').val()){

                 if(!/^([\w-]+\.)+((com)|(net)|(org)|(gov\.cn)|(info)|(cc)|(com\.cn)|(net\.cn)|(org\.cn)|(name)|(biz)|(tv)|(cn)|(mobi)|(name)|(sh)|(ac)|(io)|(tw)|(com\.tw)|(hk)|(com\.hk)|(ws)|(travel)|(us)|(tm)|(la)|(me\.uk)|(org\.uk)|(ltd\.uk)|(plc\.uk)|(in)|(eu)|(it)|(jp))$/.test(jQuery('#ip:visible').val()))
                 {

                 }
                 if(!/^(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])(\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])){3}$/.test(jQuery('#ip:visible').val())||

                 !/[a-zA-Z0-9][-a-zA-Z0-9]{0,62}(\.[a-zA-Z0-9][-a-zA-Z0-9]{0,62})+\.?/.test(jQuery('#ip:visible').val())

                 ){

                 jQuery(this).addClass('invalid');
                 jQuery(this).jGrowlError('IPs/FQDN must be a valid format ');
                 re = false;

                 }
                 if(jQuery('#port:visible').val()!=''||!jQuery('#port:visible').val()){
                 if(/\D/.test(jQuery('#port:visible').val())){
                 jQuery(this).addClass('invalid');
                 //	jQuery(this).jGrowlError('Port,must be whole number!');
                 //		re = false;
                 }


                 }

                 }
                 */

                if (jQuery('#ring_timeout').val() == '' || jQuery('#ring_timeout').val() < 1 || jQuery('#ring_timeout').val() > 60) {
                    jQuery('#ring_timeout').addClass('invalid');
                    jGrowl_to_notyfy('Ring Timer cant not be greater than 60 or less than 1!', {theme: 'jmsg-error'});
                    return false;
                }

                if (jQuery('#delay_bye_second').val() && jQuery('#delay_bye_second').val() == '0') {
                    jQuery('#delay_bye_second').addClass('invalid');
                    jGrowl_to_notyfy('Min Duration can not be 0!', {theme: 'jmsg-error'});
                    return false;
                }

                if (jQuery('#max_duration').val() && jQuery('#max_duration').val() == '0') {
                    jQuery('#max_duration').addClass('invalid');
                    jGrowl_to_notyfy('Max Duration can not be 0!', {theme: 'jmsg-error'});
                    return false;
                }


                return re;

            });

        }

    );

    $(function() {

        var $rate_profile_control = $('.rate_profile_control');

        $('#GatewaygroupRateProfile').change(function() {
            var $this = $(this);
            var val = $this.val();
            if (val == 0)
            {
                $rate_profile_control.hide();
            }
            else
            {
                $rate_profile_control.show();
            }
        }).trigger('change');

        $('#GatewaygroupEditResouceEgressForm').submit(function() {

            var flag = true;

            if (jQuery('#wait_ringtime180').val() != '') {
                if (!/\d+|\./.test(jQuery('#wait_ringtime180').val())) {
                    jQuery('#wait_ringtime180').addClass('invalid');
                    jGrowl_to_notyfy('PDD Timeout must contain numeric characters only!', {theme: 'jmsg-error'});
                    flag = false;
                }
            }
            if (jQuery('#delay_bye_second').val() != '') {
                if (!/\d+|\./.test(jQuery('#delay_bye_second').val())) {
                    jQuery('#delay_bye_second').addClass('invalid');
                    jGrowl_to_notyfy('Min Duration must contain numeric characters only!', {theme: 'jmsg-error'});
                    flag = false;
                }
            }
            if (jQuery('#max_duration').val() != '') {
                if (!/\d+|\./.test(jQuery('#max_duration').val())) {
                    jQuery('#max_duration').addClass('invalid');
                    jGrowl_to_notyfy('Max Duration must contain numeric characters only!', {theme: 'jmsg-error'});
                    flag = false;
                }
            }

            return flag;
        });

        var did_billing_method_tr = $('#did_billing_method_tr');
        var did_billing_rule_tr = $('#did_billing_rule_tr');
        var did_rate_table_tr = $('#did_rate_table_tr');
        var did_amount_per_port_tr = $('#did_amount_per_port_tr');

        jQuery('#GatewaygroupTrunkType2').change(function() {
            if ($(this).val() == '0')
            {
                did_billing_method_tr.hide();
                did_billing_rule_tr.hide();
                did_rate_table_tr.show();
                $('#did_amount_per_port_tr').hide();
            }
            else
            {
                did_billing_method_tr.show();
                did_billing_rule_tr.show();
                did_rate_table_tr.hide();
                jQuery('#GatewaygroupBillingMethod').change();
            }
        }).trigger('change');

        jQuery('#GatewaygroupBillingMethod').change(function() {
            if ($(this).val() == '0')
            {
                did_rate_table_tr.show();
                did_amount_per_port_tr.hide();
            }
            else
            {
                did_rate_table_tr.hide();
                did_amount_per_port_tr.show();
            }
            if(jQuery('#GatewaygroupTrunkType2').val() == '1')
            {
                did_rate_table_tr.hide();
            }
        });

    });
</script>
