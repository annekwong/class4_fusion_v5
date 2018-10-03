<style type="text/css">
    #ignore {
        float:left;
    }
    #ignore li {
        padding:3px;
        padding-left:40px;
        float:left;
    }
    .form .label2{ width:40%;}
    .form tr{ height:30px; line-height:30px;}
</style>

<ul class="breadcrumb">
<?php if(isset($_GET['viewtype']) && $_GET['viewtype'] == 'client'):?>
    <li><?php __('You are here'); ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Management') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Carrier'); if(isset($client_name) && $client_name): echo '['.$client_name.']'?><?php endif;?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Add Ingress Trunk') ?></li>
<?php else:?>
    <li><?php __('You are here'); ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Routing') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Add Ingress Trunk') ?></li>
<?php endif;?>
</ul>


<div class="heading-buttons">
    <h4 class="heading"><?php __('Add Ingress Trunk') ?></h4>

</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
    <?php if ($_SESSION['login_type'] != 3) { ?>
        <?php if(isset($_SESSION['role_menu']['Template']['template']['model_w']) && $_SESSION['role_menu']['Template']['template']['model_w'] && $have_template): ?>
            <?php if(isset($portal_client_id)): ?>
                <a class="link_btn btn btn-primary btn-icon glyphicons circle_plus" href="<?php echo $this->webroot ?>template/add_resource_by_template/0/<?php echo $portal_client_id . '/' . 'portal'; ?>">
                    <i></i> <?php __('Create new by Template')?>
                </a>
            <?php else: ?>
                <a class="link_btn btn btn-primary btn-icon glyphicons circle_plus" href="<?php echo $this->webroot ?>template/add_resource_by_template/0">
                    <i></i> <?php __('Create new by Template')?>
                </a>
            <?php endif;?>
        <?php endif; ?>
    <?php } ?>
    <?php if(isset($portal_client_id)): ?>
        <a href="<?php echo $this->webroot ?>clients/view_ingress" class="link_back btn btn-icon btn-inverse glyphicons circle_arrow_left"><i></i><?php echo __('goback', true); ?></a>
    <?php else: ?>
        <a href="<?php echo $this->webroot ?>prresource/gatewaygroups/view_egress?<?php echo $$hel->getParams('getUrl') ?>" class="link_back btn btn-icon btn-inverse glyphicons circle_arrow_left"><i></i><?php echo __('goback', true); ?></a>
    <?php endif;?>
</div>
<div class="clearfix"></div>
<div class="innerLR">

    <div class="widget widget-heading-simple widget-body-white">
        <div class="widget-body">
            <!--
              <ul class="tabs">
                <li  class="active"> <a> <img width="16" height="16" src="<?php echo $this->webroot ?>images/list.png"/>
            <?php __('Route Strategy') ?>
                  </a> </li>
              </ul>
            -->
            <?php
            if(isset($portal_client_id)) {
                $action = "add_resouce_ingress/{$portal_client_id}";
            } else {
                $action = "add_resouce_ingress";
            }
            echo $form->create('Gatewaygroup', array('action' => $action)); ?> <?php echo $form->input('ingress', array('label' => false, 'value' => 'true', 'div' => false, 'type' => 'hidden')); ?> <?php echo $form->input('egress', array('label' => false, 'value' => 'false', 'div' => false, 'type' => 'hidden')); ?>
            <!-- COLUMN 1 -->
            <input type="hidden" name="t38" value="true">
            <?php echo $form->input('back_url', array('label' => false,'div' => false, 'type' => 'hidden', 'value' => $back_url)); ?>

            <?php //**********系统信息**************?>
            <?php //if(isset($portal_client_id)): ?>
            <table class="form footable table table-striped dynamicTable tableTools table-bordered  table-white default footable-loaded">
                <colgroup><col width="37%"><col width="63%">

                </colgroup>
                <fieldset>
                    <tr>
                        <td class="align_right padding-r10"><?php echo __('Ingress Name', true); ?></td>
                        <td><?php echo $form->input('alias', array('class' => 'width220 validate[required,custom[onlyLetterNumberLineSpace],minSize[2]]', 'id' => 'alias', 'label' => false, 'div' => false, 'type' => 'text', 'maxlength' => '100')); ?></td>
                    </tr>

                    <?php
                    if (isset($_GET['viewtype']) && ($_GET['viewtype'] == 'client' || $_GET['viewtype'] == 'wizard'))
                    {
                        ?>
                        <tr style="display:none">
                            <td></td>
                            <td><?php
                                echo $form->input('client_id', array('options' => $c, 'selected' => array_keys_value($_GET, 'query.id_clients'), 'label' => false, 'class' => 'select', 'div' => false, 'type' => 'select'));
                                ?></td>
                        </tr>
                        <?php
                    }
                    else
                    {
                        ?>
                        <tr>
                            <td class="align_right padding-r10"><?php echo __('client') ?> </td>
                            <td>
                                <?php
                                if(isset($portal_client_id)){
                                    echo $portal_client_name;
                                    echo $form->input('client_id', array('label' => false,'div' => false, 'type' => 'hidden', 'value' => $portal_client_id));
                                    echo $form->input('portal_carrier', array('label' => false,'div' => false, 'type' => 'hidden', 'value' => true));
                                } else
                                    echo $form->input('client_id', array('options' => $c, 'empty' => '', 'label' => false, 'class' => 'select', 'div' => false, 'type' => 'select'));
                                ?>
                            </td>
                        </tr>
                    <?php } ?>
                    <?php if ($is_enable_type): ?>
                        <tr>
                            <td class="align_right padding-r10"><?php __('Type'); ?></td>
                            <td>
                                <?php
                                echo $form->input('trunk_type', array('options' => array(1 => 'Class4', 2 => 'Exchange'), 'label' => false, 'class' => 'select', 'div' => false, 'type' => 'select', 'selected' => 1));
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
                        <td><?php
                            if (Configure::read('project_name') == 'partition')
                            {
                                $t = array('2' => 'Bypass Media', '1' => 'Proxy Media');
//                                            $t = array('0' => 'Transcoding Media', '2' => 'Bypass Media', '1' => 'Proxy Media');
                            }
                            else
                            {
                                $t = array( '1' => 'Proxy Media ', '2' => 'Bypass Media');
//                                            $t = array('0' => 'Proxy Media + Transcoding', '1' => 'Proxy Media ', '2' => 'Bypass Media');
                            }
                            echo $form->input('media_type', array('id' => 'media_type', 'options' => $t, 'label' => false, 'class' => 'select', 'div' => false, 'type' => 'select','value'=>2));
                            ?></td>
                    </tr>

                    <tr id="rtp_timeout" style="display:none">
                        <td class="align_right padding-r10"><?php echo __('RTP Timeout', true); ?></td>
                        <td align="left">
                            <?php echo $form->input('media_timeout', array('class' => 'width220 validate[custom[onlyNumberSp]]', 'id' => 'media_timeout', 'label' => false, 'div' => false, 'type' => 'text', 'maxlength' => '8')); ?>&nbsp;s
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
                        <td><?php echo $form->input('capacity', array('class' => 'width220 validate[custom[onlyNumberSp]]', 'id' => 'totalCall', 'label' => false, 'div' => false, 'type' => 'text', 'maxlength' => '8')); ?>
                            <span id="max_call_limit"></span></td>
                    </tr>
                    <tr>
                        <td class="align_right padding-r10"><?php __('cps') ?></td>
                        <td><?php echo $form->input('cps_limit', array('class' => 'width220 validate[custom[onlyNumberSp]]', 'id' => 'totalCPS', 'label' => false, 'div' => false, 'type' => 'text', 'maxlength' => '8')); ?>
                            <span id="max_cps_limit"></span></td>
                    </tr>
                    <!--                                <tr>-->
                    <!--                                    <td class="align_right padding-r10">--><?php //__('proto') ?><!--</td>-->
                    <!--                                    <td>--><?php //echo $form->input('proto', array('label' => false, 'div' => false, 'type' => 'select', 'options' => Array(Resource::RESOURCE_PROTO_ALL => 'All', Resource::RESOURCE_PROTO_SIP => 'SIP', Resource::RESOURCE_PROTO_PROTO => 'H323'), 'selected' => Resource::RESOURCE_PROTO_SIP)); ?><!--</td>-->
                    <!--                                </tr>-->

                    <tr>
                        <td class="align_right padding-r10"><?php __('pddtimeout') ?></td>
                        <td>
                            <?php echo $form->input('wait_ringtime180', array('class' => 'width220 validate[min[1000],max[60000],custom[onlyNumberSp]]', 'id' => 'wait_ringtime180', 'label' => false, 'div' => false, 'type' => 'text', 'maxlength' => '5', 'value' => $default_timeout['ingress_pdd_timeout'])); ?>
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
                            echo $form->input('active', array('options' => array('true' => 'True', 'false' => 'False'), 'label' => false, 'class' => 'select', 'div' => false, 'type' => 'select', 'selected' => $au));
                            ?></td>
                    </tr>
<!--                    <tr>-->
<!--                        <td class="align_right padding-r10">--><?php //__('T38') ?><!-- </td>-->
<!--                        <td>-->
<!--                            --><?php
//                            $t38 = 'true';
//                            echo $form->input('t38', array('options' => array('true' => 'Enable', 'false' => 'Disable'), 'label' => false, 'class' => 'select', 'div' => false, 'type' => 'select', 'selected' => $t38));
//                            ?><!--</td>-->
<!--                    </tr>-->
                    <!--                                <tr>-->
                    <!--                                    <td class="align_right padding-r10">--><?php //__('Dipping Rate') ?><!--</td>-->
                    <!--                                    <td>-->
                    <!--                                        --><?php //echo $form->input('lnp_dipping_rate', array('class' => 'width220 validate[custom[number]]', 'id' => 'lnp_dipping_rate', 'label' => false, 'div' => false, 'type' => 'text', 'value' => '0', 'maxlength' => '10')); ?>
                    <!--                                    </td>-->
                    <!--                                </tr>-->
                    <!--                        <tr>
                                    <td><?php __('RFC 2833'); ?></td>
                                    <td>
                                <?php
                    $rfc2833 = 'true';
                    echo $form->input('rfc_2833', array('options' => array('true' => 'True', 'false' => 'False'), 'label' => false, 'class' => 'select', 'div' => false, 'type' => 'select', 'selected' => $rfc2833));
                    ?>
                                    </td>
                                </tr>-->
                    <!--                                <tr>-->
                    <!--                                    <td class="align_right padding-r10">--><?php //__('User Dipping From'); ?><!--</td>-->
                    <!--                                    <td>-->
                    <!--                                        --><?php
                    //                                        $lnp_dipping = 'false';
                    //                                        echo $form->input('lnp_dipping', array('options' => array('false' => 'LRN Server', 'true' => 'Client SIP Header'), 'label' => false, 'class' => 'select', 'div' => false, 'type' => 'select', 'selected' => $lnp_dipping));
                    //                                        ?>
                    <!--                                    </td>-->
                    <!--                                </tr>-->
                    <tr>
                        <td class="align_right padding-r10"><?php __('Min Duration'); ?></td>
                        <td>
                            <?php echo $form->input('delay_bye_second', array('class' => 'width220 validate[custom[onlyNumberSp]]', 'id' => 'delay_bye_second', 'label' => false, 'div' => false, 'type' => 'text', 'maxlength' => '5')); ?>&nbsp;s
                        </td>
                    </tr>
                    <!--
                                <tr>
                                    <td><?php __('Delay Bye Limit'); ?></td>
                                    <td>
                                <?php echo $form->input('delay_bye_limit', array('id' => 'delay_bye_limit', 'label' => false, 'div' => false, 'type' => 'text')); ?>
                                    </td>
                                </tr>
                                -->
                    <tr>
                        <td class="align_right padding-r10"><?php __('Ignore Early NOSDP'); ?></td>
                        <td>
                            <?php
                            $nosdp = 0;
                            echo $form->input('ignore_early_nosdp', array('options' => array('False', 'True'), 'label' => false, 'class' => 'select', 'div' => false, 'type' => 'select', 'selected' => $nosdp));
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="align_right padding-r10"><?php __('Ring Timer'); ?></td>
                        <td>
                            <?php echo $form->input('ring_timeout', array('class' => 'width220 validate[min[1],max[60],custom[onlyNumberSp]]', 'id' => 'ring_timeout', 'label' => false, 'div' => false, 'type' => 'text', 'maxlength' => '5', 'value' => $default_timeout['ring_timeout'])); ?>s
                        </td>
                    </tr>
                    <tr>
                        <td class="align_right padding-r10"><?php __('Max Duration'); ?></td>
                        <td>
                            <?php echo $form->input('max_duration', array('class' => 'width220 validate[custom[onlyNumberSp]]', 'id' => 'max_duration', 'label' => false, 'div' => false, 'type' => 'text', 'maxlength' => '5', 'value' => $default_timeout['call_timeout'])); ?>&nbsp;s
                        </td>
                    </tr>
                    <tr>
                        <td class="align_right padding-r10"><?php __('Enable Global 404 Blocking'); ?></td>
                        <td>
                            <input type="checkbox" id="enable404_blocking">
                            <div class="hidden" id="div_block_404">
                                <?php echo $form->input('block_404_number_time', array('class' => 'width220 hidden validate[custom[onlyNumber]]', 'id' => 'block_404_number_time', 'label' => false, 'div' => false, 'type' => 'text', 'value' => 0)); ?>&nbsp;days
                            </div>
                        </td>
                    </tr>
                    <!--
                    <tr>
                        <td class="align_right padding-r10"><?php __('DTMF INFO'); ?></td>
                        <td>
                            <?php echo $form->input('info', array('options' => array('Disable', 'Enable'), 'label' => false, 'div' => false)); ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="align_right padding-r10"><?php __('DTMF RFC2833'); ?></td>
                        <td>
                            <?php echo $form->input('rfc2833', array('options' => array('Disable', 'Enable'), 'label' => false, 'div' => false)); ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="align_right padding-r10"><?php __('DTMF INBAND'); ?></td>
                        <td>
                            <?php echo $form->input('inband', array('options' => array('Disable', 'Enable'), 'label' => false, 'div' => false)); ?>
                        </td>
                    </tr>
                    -->
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

                <?php //***************************************费率设置************************************************************    ?>
                <tr>
                    <td colspan="2" class="value"><div class="cb_select" style="height:30px; line-height:30px;text-align: left; border:none;">


                            <div  style="display:none;">
                                <?php echo $form->checkbox('lnp', array('checked' => 'false', 'style' => 'margin-left: 40px;')) ?>
                                <label for="cp_modules-c_invoices">LRN</label>
                                <?php echo $form->checkbox('lrn_block', array('checked' => 'false', 'style' => 'margin-left: 40px;')) ?>
                                <label for="cp_modules-c_stats_summary">Block LRN</label>
                                <?php echo $form->checkbox('dnis_only', array('checked' => 'checked', 'style' => 'margin-left: 40px;')) ?>
                                <label for="cp_modules-c_stats_summary">DNIS Only</label>
                            </div>
                        </div></td>
                </tr>


                <fieldset style="display:none;">
                    <legend>
                        <?php __('rateTable') ?>
                    </legend>


                    <tr style="display:none;">
                        <td class="align_right padding-r10"><?php __('rateTable') ?>
                        </td>
                        <td><?php echo $form->input('rate_table_id', array('options' => $rate, 'empty' => '  ', 'label' => false, 'class' => 'select', 'div' => false, 'type' => 'select', 'style' => 'width:210px')); ?>

                        </td>
                    </tr>

                </fieldset>
                <fieldset style="display:none;">
                    <legend><?php echo __('Route Plan', true); ?></legend>

                    <tr style="display:none;">
                        <td class="align_right padding-r10"><?php echo __('Route Plan', true); ?></td>
                        <td><?php echo $form->input('route_strategy_id', array('options' => $route_policy, 'empty' => '  ', 'label' => false, 'div' => false, 'type' => 'select')); ?></td>
                    </tr>

                </fieldset>

                <fieldset>
                    <?php echo $this->element("gatewaygroups/more_public_fields") ?>
                </fieldset>

                <fieldset>
                    <?php echo $this->element("gatewaygroups/cid_blocking") ?>
                </fieldset>

                <fieldset>
                    <tr>
                        <td class="align_right padding-r10">

                            <?php __('codecs') ?>
                        </td>
                        <td>
                            <table class="form">
                                <tr>
                                    <td><?php echo $form->input('select1', array('id' => 'select1', 'options' => $d, 'multiple' => true, 'style' => 'width: 200px; height: 250px;', 'label' => false, 'class' => 'select', 'div' => false, 'type' => 'select')); ?></td>
                                    <td><input class="btn"  style="width: 60px; height: 30px; margin-left: 0px;"    onclick="DoAdd();"  type="button"  value="<?php __('add') ?>"  />
                                        <br/>
                                        <br/>
                                        <input class="btn"  type="button"   style="width: 60px; height: 30px; margin-left: 0px;"  onclick="DoDel();"   value="<?php __('delete') ?>"  /></td>
                                    <td><?php echo $form->input('select2', array('id' => 'select2', 'options' => '', 'multiple' => true, 'style' => 'width: 200px; height: 250px;', 'label' => false, 'class' => 'select', 'div' => false, 'type' => 'select')); ?></td>
                                    <td><input class="btn"  style="width: 60px; height: 30px; margin-left: 0px;"    onclick="moveOption('select2', 'up');"  type="button"  value="<?php __('up') ?>"  />
                                        <br/>
                                        <br/>
                                        <input class="btn"  type="button"   style="width: 60px; height: 30px; margin-left: 0px;"  onclick="moveOption('select2', 'down');"   value="<?php __('Down') ?>"  /></td>
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
                    </td>
                </tr>
            </table>
            <?php //endif; ?>

            <?php if(isset($portal_client_id)): ?>
            <table class="form footable table table-striped dynamicTable tableTools table-bordered  table-white default footable-loaded">
                <colgroup><col width="37%"><col width="63%">

                </colgroup>
                <fieldset>
                    <tr>
                        <td class="align_right padding-r10"><?php __('Authorized'); ?></td>
                        <td>
                            <?php
                            //var_dump($hosts[0]);
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
                        </td>
                    </tr>
                </fieldset>
            </table>
            <?php endif; ?>

            <?php echo $this->element("gatewaygroups/host_edit") ?>
            <?php echo $this->element("gatewaygroups/resource_prefix") ?>
            <?php
            if ($$hel->_get('viewtype') == 'wizard')
            {
                ?>
                <div id="form_footer" class="buttons-group center">
                    <input type="submit" class="input in-submit btn btn-primary"   onclick="seleted_codes();
                        jQuery('#GatewaygroupAddResouceIngressForm').attr('action', '?nextType=egress&<?php echo $$hel->getParams('getUrl') ?>')" value="<?php echo __('Next Egress') ?>" style="width:80px" />
                    <input type="submit"  class="input in-submit btn btn-default"  onclick="seleted_codes();
                        jQuery('#GatewaygroupAddResouceIngressForm').attr('action', '?nextType=ingress&<?php echo $$hel->getParams('getUrl') ?>')" value="<?php echo __('Next Ingress') ?>" style="width:80px"/>
                    <input type="button"  value="<?php echo __('End') ?>" class="input in-submit  btn btn-default" onclick="location = '<?php echo $this->webroot ?>clients/index?filter_id=<?php echo $$hel->_get('query.id_clients') ?>'"/>
                </div>
                <?php
            }
            else
            {
                ?>
                <div id="form_footer" class="buttons-group center">
                    <input type="submit" id ="submit_form" class="input in-submit btn btn-primary" name="submit" value="<?php echo __('submit') ?>" />
                    <input type="reset"  value="<?php echo __('cancel', true); ?>" class="input in-submit  btn btn-default" />
                </div>
            <?php } ?>
            <?php echo $form->end(); ?> </div>
    </div>
</div>

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

<script type="text/javascript" src="<?php echo $this->webroot ?>js/gateway.js"></script>
<script type="text/javascript">
    jQuery(document).ready(
        function() {
            $('#GatewaygroupEditResouceIngressForm').validationEngine();

            $("#media_type").change(function() {
                var value = $(this).val();
                $("#rtp_timeout").hide();
                if (value == 1)
                {
                    $("#rtp_timeout").show();
                }
            });
            var did_billing_method_tr = $('#did_billing_method_tr');
            var did_rate_table_tr = $('#did_rate_table_tr');
            var did_amount_per_port_tr = $('#did_amount_per_port_tr');
            jQuery('#totalCall,#totalCPS').xkeyvalidate({type: 'Num'});

            jQuery('#GatewaygroupTrunkType2').change(function() {
                if ($(this).val() == '0')
                {
                    did_billing_method_tr.hide();
                    did_rate_table_tr.show();
                    $('#did_amount_per_port_tr').hide();
                    $('#add_resource_prefix').show();
                    $('#resource_table').show();
                }
                else
                {
                    did_billing_method_tr.show();
                    jQuery('#GatewaygroupBillingMethod').change();
                    did_rate_table_tr.hide();
                    $('#add_resource_prefix').hide();
                    $('#resource_table').hide();
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
                if (jQuery('#GatewaygroupTrunkType2').val() == '1')
                {
                    did_rate_table_tr.hide();
                }
            });

            $("#GatewaygroupClientId").change(function() {
                var client_id = $(this).val();

                $.get("<?php echo $this->webroot;?>prresource/gatewaygroups/getClientProducts/" + client_id, function (response) {
                    let options = "<option value>By Rate and Route Plan</option>";
                    let products = JSON.parse(response);
                    console.log(products);

                    for (key in products) {
                        options += "<option value='" + key + "'>" + products[key]['product_name'] + "</option>"
                    }

                    $('select.product_id').html(options)
                });

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



            var $transaction_fee_panel = $('#transaction_fee_panel');

            $('#GatewaygroupTrunkType').change(function() {
                if ($(this).val() == 2)
                {
                    $transaction_fee_panel.show();
                }
                else
                {
                    $transaction_fee_panel.hide();
                }
            }).trigger('change');

            jQuery('#submit_form').click(function() {
                console.log($("input[name='accounts[reg_srv_ip][]']").val());
                <?php if(!isset($portal_client_id)): ?>
                if($('#host_authorize').val() == 2 && $('.ip-host-dd').val() == 'ip' && !$("input[name='accounts[reg_srv_ip][]']").val().toString().match(/^(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/)){
                    jQuery(this).jGrowlError('Wrong IP Format!', {theme: 'jmsg-error'});
                    return false;
                } else if($('#host_authorize').val() == 0 && $('.ip-host-dd').val() == 'ip' && !$("input[name='accounts[ip][]']").val().toString().match(/^(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/)){
                    jQuery(this).jGrowlError('Wrong IP Format!', {theme: 'jmsg-error'});
                    return false;
                }
        
                var re = true;
                if (jQuery('#alias').val() == '') {
//                    jQuery('#alias').addClass('input in-input in-text');
//                    jQuery(this).jGrowlError('The field ingress name cannot be NULL.');
//                    re = false;

                } else if (/[^0-9A-Za-z-\_ \|\=\.\-\s]/.test(jQuery("#alias").val())) {
                    jQuery('#alias').addClass('input in-input in-text');
                    jQuery(this).jGrowlError('Ingress Name,allowed characters: a-z,A-Z,0-9,-,_,space, maximum of 16 characters in length!');
                    re = false;
                }

                if (jQuery('#totalCall').val() != '') {
                    if (/\D/.test(jQuery('#totalCall'.val()))) {
                        jQuery(this).addClass('input in-input in-text');
                        jQuery(this).jGrowlError('Call limit, must be whole number! ');
                        re = false;
                    }
                }
                if (parseInt(jQuery('#wait_ringtime180').val()) < 1000 || parseInt(jQuery('#wait_ringtime180').val()) > 60000) {
                    jQuery(this).addClass('invalid');
                    jQuery(this).jGrowlError('PDD Timeout must a number less than 60000 and greater than 1000!');
                    re = false;
                }
                if (jQuery('#totalCPS').val() != '') {
                    if (/\D/.test(jQuery('#totalCPS').val())) {
                        jQuery(this).addClass('input in-input in-text');
                        jQuery(this).jGrowlError('CPS Limit, must be whole number!');
                        re = false;
                    }

                }

                if (jQuery('#GatewaygroupClientId').val() == '') {
                    jQuery(this).addClass('');
                    jQuery(this).jGrowlError('Please Select Carriers !');
                    re = false;
                }

                if (jQuery('#ring_timeout').val() == '' || jQuery('#ring_timeout').val() < 1 || jQuery('#ring_timeout').val() > 60) {
                    jQuery('#ring_timeout').addClass('invalid');
                    jGrowl_to_notyfy('Ring Timer cant not be greater than 60 or less than 1!', {theme: 'jmsg-error'});
                    re = false;
                }
                return re;
                <?php endif; ?>
            });

        });

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

        $('#GatewaygroupEditResouceIngressForm').submit(function() {
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

        let showArray = [{
           'visibility' : 'visible',
            'display' : 'block'
        }];

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
