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
    <li><?php __('You are here'); ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>prresource/gatewaygroups/view_egress">
        <?php __('Management') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li>
        <?php if (isset($_GET['query']['id_clients'])): ?>
            <?php echo __('Carrier'); ?>
            <a  class="text-primary" href="<?php echo $this->webroot ?>prresource/gatewaygroups/view_egress?query[id_clients]=<?php echo $_GET['query']['id_clients'] ?>&viewtype=client">
                <?php echo ' [' . $c[$_GET['query']['id_clients']] . '] ';?>
            </a>
        <?php else: ?>
        <a  class="text-primary" href="<?php echo $this->webroot ?>prresource/gatewaygroups/view_egress?query[id_clients]=<?php echo array_keys_value($post, 'Gatewaygroup.client_id') ?>&viewtype=client">
            <?php echo __('Carrier', true); ?> [<?php print($c[array_keys_value($post, 'Gatewaygroup.client_id')]); ?>]
        </a>
        <?php endif; ?>
    </li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot . $this->params['url']['url'] ?>">
        <?php echo __('edit', true); ?> <?php __('Egress') ?> <font  class="editname" title="Name">   <?php echo empty($post['Gatewaygroup']['alias']) || $post['Gatewaygroup']['alias'] == '' ? '' : "[" . $post['Gatewaygroup']['alias'] . "]" ?> </font></a></li>

</ul>

<div class="heading-buttons">
    <h4 class="heading">
        <?php if (isset($_GET['query']['id_clients'])): ?><?php
            echo __('Carrier');
            echo ' [' . $c[$_GET['query']['id_clients']] . '] ';
            ?><?php else: ?><?php echo __('Carrier', true); ?> [<?php print($c[array_keys_value($post, 'Gatewaygroup.client_id')]); ?>]<?php endif; ?>
        &gt;&gt;<?php echo __('edit', true); ?> <?php __('Egress') ?> <font  class="editname" title="Name">   <?php echo empty($post['Gatewaygroup']['alias']) || $post['Gatewaygroup']['alias'] == '' ? '' : "[" . $post['Gatewaygroup']['alias'] . "]" ?> </font>

    </h4>

</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
    <a class="link_back btn btn-icon btn-inverse glyphicons circle_arrow_left" href='#'  onclick="history.go(-1);
            return false;"><i></i> <?php __('Back') ?></a>
</div>
<div class="clearfix"></div>
<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-head">
            <?php echo $this->element('egress_tab', array('active_tab' => 'base')); ?>
        </div>
        <div class="widget-body">
            <?php echo $form->create('Gatewaygroup', array('action' => 'edit_resouce_egress')); ?>
            <?php echo $form->input('back_url', array('label' => false,'div' => false, 'type' => 'hidden', 'value' => isset($back_url) ? $back_url : '')); ?>
            <?php echo $form->input('resource_id', array('id' => 'alias', 'label' => false, 'value' => $post['Gatewaygroup']['resource_id'], 'div' => false, 'type' => 'hidden', 'maxlength' => '6')); ?>
            <?php echo $form->input('ingress', array('label' => false, 'value' => 'false', 'div' => false, 'type' => 'hidden')); ?>
            <?php echo $form->input('egress', array('label' => false, 'value' => 'true', 'div' => false, 'type' => 'hidden')); ?>
            <input type="hidden" value="<?php echo $post['Gatewaygroup']['resource_id']; ?>" name="resource_id"/>


            <div class="widget">
                <div class="widget-head"><h4 class="heading"><?php __('Basic Info') ?></h4></div>
                <div class="widget-body">
                    <!-- COLUMN 1 -->
                    <?php //**********系统信息**************   ?>
                    <table class="form footable table table-striped dynamicTable tableTools table-bordered  table-white default footable-loaded">
                        <colgroup><col width="37%"><col width="63%">

                        </colgroup>
                        <fieldset>
                            <tr>
                                <td class="align_right padding-r10"><?php echo __('Egress Name', true); ?> </td>
                                <td>
                                    <?php echo $form->input('alias', array('class' => 'width220 validate[required,custom[onlyLetterNumberLineSpace]]', 'id' => 'alias', 'label' => false, 'div' => false, 'value' => $post['Gatewaygroup']['alias'], 'type' => 'text', 'maxlength' => '100')); ?>
                                </td>
                            </tr>


                            <?php
                            if (isset($_GET['viewtype']) && $_GET['viewtype'] == 'client')
                            {
                                ?>
                                <tr style="display:none;">
                                    <td class="align_right padding-r10"><?php __('Carrier') ?></td>
                                    <td style="height:40px; line-height:40px;">
                                        <?php
                                        echo $c[$post['Gatewaygroup']['client_id']];
                                        ?>
                                        <input type="hidden" name="data[Gatewaygroup][client_id]" value="<?php echo $post['Gatewaygroup']['client_id']; ?>">
                                    </td>
                                </tr>
                            <?php
                            }
                            else
                            {
                                ?>
                                <tr style="">
                                    <td class="align_right padding-r10"><?php __('Carrier') ?></td>
                                    <!--
                                    <td style="height:40px; line-height:40px;">
                                        <?php echo $c[$post['Gatewaygroup']['client_id']]; ?>
                                        <input type="hidden" name="data[Gatewaygroup][client_id]" value="<?php echo $post['Gatewaygroup']['client_id']; ?>">
                                    </td>
                                    -->
                                    <td><?php echo $form->input('client_id', array('options' => $c, 'empty' => '', 'label' => false, 'class' => 'select', 'div' => false, 'type' => 'select', 'selected' => $post['Gatewaygroup']['client_id'])); ?></td>
                                </tr>
                            <?php } ?>
                            <tr>
                                <td class="align_right padding-r10"><?php echo __('Media Type', true); ?></td>
                                <td>
                                    <?php
                                    if (Configure::read('project_name') == 'partition')
                                    {
                                        $t = array('2' => __('Bypass Media', true), '1' => __('Proxy Media', true));
//                                        $t = array('0' => __('Transcoding Media', true), '2' => __('Bypass Media', true), '1' => __('Proxy Media', true));
                                    }
                                    else
                                    {
                                        $t = array( '1' => __('Proxy Media '), '2' => __('Bypass Media'));
//                                        $t = array('0' => __('Proxy Media + Transcoding'), '1' => __('Proxy Media '), '2' => __('Bypass Media'));
                                    }

                                    echo $form->input('media_type', array('id' => 'media_type', 'options' => $t, 'label' => false, 'class' => 'select', 'selected' => $post['Gatewaygroup']['media_type'], 'div' => false, 'type' => 'select'));
                                    ?>

                                </td>

                            </tr>

                            <tr id="rtp_timeout" <?php if ($post['Gatewaygroup']['media_type'] != 1): ?> style="display:none;"<?php endif; ?>>
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
                           
                            <!--                                <tr>-->
                            <!--                                    <td class="align_right padding-r10">--><?php //__('proto') ?><!--</td>-->
                            <!--                                    <td>-->
                            <!--                                        --><?php //echo $form->input('proto', array('label' => false, 'value' => $post['Gatewaygroup']['proto'], 'div' => false, 'type' => 'select', 'options' => Array(Resource::RESOURCE_PROTO_ALL => __('ALL', true), Resource::RESOURCE_PROTO_SIP => __('SIP', true), Resource::RESOURCE_PROTO_PROTO => __('H323', true)))); ?>
                            <!--                                    </td>-->
                            <!--                                </tr>-->

                            <tr>
                                <td class="align_right padding-r10"><?php __('status') ?></td>
                                <td>
                                    <?php
                                    $post['Gatewaygroup']['active'] == '1' ? $au = 'true' : $au = 'false';
                                    echo $form->input('active', array('options' => array('true' => __('Active', true), 'false' => 'Inactive'), 'label' => false, 'class' => 'select', 'div' => false, 'type' => 'select', 'selected' => $au));
                                    ?></td>
                            </tr>
                             <tr>
                                <td class="align_right padding-r10">Enable T38</td>
                                <td>
                                    <?php
                                        empty($post['Gatewaygroup']['t38'])?$au='false':$au='true';
                                        echo $form->input('t38', array('options' => array('true' => __('True',true), 'false' => __('False',true)), 'label' => false, 'class' => 'select', 'div' => false, 'type' => 'select', 'selected' => $au));
                                    ?>
                                    </td>
                            </tr>

                            <fieldset>
                                <?php echo $this->element("gatewaygroups/cid_blocking") ?>
                            </fieldset>

                            <!--
                                                        <tr>
                                                            <td><?php __('rateTable') ?></td>
                                                            <td>
                                <?php
                            echo $form->input('rate_table_id', array('options' => $rate,
                                'empty' => '  ', 'label' => false, 'class' => 'select', 'selected' => $post['Gatewaygroup']['rate_table_id'], 'div' => false, 'type' => 'select'));
                            ?>
                                <?php
                            if ($_SESSION['role_menu']['Routing']['gatewaygroups:view']['model_w'])
                            {
                                ?>
                                                                                                <img id="addratetable" style="cursor:pointer;" src="<?php echo $this->webroot ?>images/add.png" onclick="showDiv('pop-div','500','200','<?php echo $this->webroot ?>clients/addratetable');" />
                                <?php } ?>
                                                            </td>
                                                        </tr>
                                -->
                            <tr style="line-height:1;">
                                <td class="align_right padding-r10"><?php __('HostStrategy') ?></td>
                                <td>

                                    <?php
                                    $post['Gatewaygroup']['res_strategy'] == '1' ? $res_strategy = '1' : $res_strategy = '2';

                                    echo $form->input('res_strategy', array('options' => array('1' => __('top-down', true), '2' => __('round-robin', true)), 'label' => false, 'class' => 'select', 'div' => false, 'type' => 'select', 'selected' => $res_strategy));
                                    ?>
                                </td>
                            </tr>
                            <!--                        <tr>
                                    <td><?php __('RFC 2833'); ?></td>
                                    <td>
                                <?php
                            $post['Gatewaygroup']['rfc_2833'] == 't' ? $rfc2833 = 'true' : $rfc2833 = 'false';
                            echo $form->input('rfc_2833', array('options' => array('true' => 'True', 'false' => 'False'), 'label' => false, 'class' => 'select', 'div' => false, 'type' => 'select', 'selected' => $rfc2833));
                            ?>
                                    </td>
                                </tr>-->





                            <!--
                                <tr>
                                    <td><?php __('LRN/DNIS BLOCK'); ?></td>
                                    <td>
                                <?php
                            $post['Gatewaygroup']['lrn_block'] == 't' ? $lrn_block = 'true' : $lrn_block = 'false';
                            echo $form->input('lrn_block', array('options' => array('true' => 'True', 'false' => 'False'), 'label' => false, 'class' => 'select', 'div' => false, 'type' => 'select', 'selected' => $lrn_block));
                            ?>
                                    </td>
                                </tr>-->
                            <!--
                                <tr>
                                    <td><?php __('Switch Profile'); ?></td>
                                    <td>
                                <?php
                            echo $form->input('switch_profile_id', array('options' => $switch_profiles,
                                'empty' => '  ', 'label' => false, 'class' => 'select', 'selected' => $post['Gatewaygroup']['switch_profile_id'], 'div' => false, 'type' => 'select'));
                            ?>
                                    </td>
                                </tr>
                                -->

                            <?php if ($is_did_enable): ?>
                                <tr>
                                    <td class="align_right padding-r10"><?php __('Type'); ?></td>
                                    <td>
                                        <?php
                                        echo $form->input('trunk_type2', array('options' => array(0 => 'Termination Traffic', 1 => 'DID Traffic'), 'label' => false, 'class' => 'select', 'div' => false, 'type' => 'select', 'selected' => $post['Gatewaygroup']['trunk_type2']));
                                        ?>
                                    </td>
                                </tr>
                                <tr id="did_billing_rule_tr">
                                    <td class="align_right padding-r10"><?php __('Orig. Billing Rule'); ?></td>
                                    <td>
                                        <?php
                                        echo $form->input('billing_rule', array('options' => $routing_rules, 'selected' => $post['Gatewaygroup']['billing_rule'],
                                            'empty' => '  ', 'label' => false, 'class' => 'select', 'div' => false, 'type' => 'select'));
                                        ?>
                                    </td>
                                </tr>
                                <tr id="did_billing_method_tr">
                                    <td class="align_right padding-r10"><?php __('Billing Method'); ?></td>
                                    <td>
                                        <?php
                                        echo $form->input('billing_method', array('options' => array(0 => __('By Minute', true), 1 => __('By Port', true)), 'label' => false, 'class' => 'select', 'div' => false, 'type' => 'select', 'selected' => $post['Gatewaygroup']['billing_method']));
                                        ?>
                                    </td>
                                </tr>
                            <?php endif; ?>
                            <tr id="did_rate_table_tr">
                                <td class="align_right padding-r10"><?php __('Rate Table'); ?></td>
                                <td>
                                    <?php
                                    echo $form->input('rate_table_id', array('options' => $rate_tables,
                                        'empty' => '  ', 'label' => false, 'class' => 'select', 'selected' => $post['Gatewaygroup']['rate_table_id'], 'div' => false, 'type' => 'select'));
                                    ?>
                                    <?php
                                    if ($_SESSION['role_menu']['Routing']['gatewaygroups:view']['model_w'])
                                    {
                                        ?>
                                        <a>
                                            <i class="icon-plus"  onclick="showDiv('pop-div', '700', '300', '<?php echo $this->webroot ?>clients/addratetable');" ></i>
                                        </a>
                                        <!--<img id="addratetable" style="cursor:pointer;" src="<?php echo $this->webroot ?>images/add.png" onclick="showDiv('pop-div', '700', '300', '<?php echo $this->webroot ?>clients/addratetable');" />-->
                                    <?php } ?>
                                </td>
                            </tr>

                            <tr>
                                <td class="align_right padding-r10"><?php __('Pass LRN to Header'); ?></td>
                                <td>
                                    <?php
                                    $post['Gatewaygroup']['lnp_dipping'] == 't' ? $lnp_dipping = 'true' : $lnp_dipping = 'false';
                                    echo $form->input('lnp_dipping', array('options' => array('true' => __('Yes', true), 'false' => __('No', true)), 'label' => false, 'class' => 'select', 'div' => false, 'type' => 'select', 'selected' => $lnp_dipping));
                                    ?>
                                </td>
                            </tr>





                            <!--                        <tr>
                                <td><?php __('Ring Timer'); ?></td>
                                <td>
                                <?php echo $form->input('ring_timeout', array('class' => 'validate[min[1],max[60],custom[onlyNumberSp]]', 'id' => 'ring_timeout', 'label' => false, 'div' => false, 'type' => 'text', 'maxlength' => '5', 'value' => $post['Gatewaygroup']['ring_timeout'])); ?> s
                                </td>
                            </tr>
                            <tr>
                                <td><?php __('Rate Profile'); ?></td>
                                <td>
                                <?php
                            echo $form->input('rate_profile', array('options' => array('False', 'True'), 'label' => false, 'class' => 'select', 'div' => false, 'type' => 'select', 'selected' => $post['Gatewaygroup']['rate_profile']));
                            ?>
                                </td>
                            </tr>
                            <tr class="rate_profile_control">
                                <td><?php __('USA'); ?></td>
                                <td>
                                <?php
                            echo $form->input('us_route', array('options' => array('Other', 'Intra', 'Inter', 'Highest'), 'label' => false, 'class' => 'select', 'div' => false, 'type' => 'select', 'selected' => $post['Gatewaygroup']['us_route']));
                            ?>
                                </td>
                            </tr>
                            <tr class="rate_profile_control">
                                <td><?php __('US Territories'); ?></td>
                                <td>
                                <?php
                            echo $form->input('us_other', array('options' => array('Other', 'Intra', 'Inter', 'Highest'), 'label' => false, 'class' => 'select', 'div' => false, 'type' => 'select', 'selected' => $post['Gatewaygroup']['us_other']));
                            ?>
                                </td>
                            </tr>
                            <tr class="rate_profile_control">
                                <td><?php __('Canada'); ?></td>
                                <td>
                                <?php
                            echo $form->input('canada_route', array('options' => array('Other', 'Intra', 'Inter', 'Highest'), 'label' => false, 'class' => 'select', 'div' => false, 'type' => 'select', 'selected' => $post['Gatewaygroup']['canada_route']));
                            ?>
                                </td>
                            </tr>
                            <tr class="rate_profile_control">
                                <td><?php __('Non USA/Canada Territories'); ?></td>
                                <td>
                                <?php
                            echo $form->input('canada_other', array('options' => array('Other', 'Intra', 'Inter', 'Highest'), 'label' => false, 'class' => 'select', 'div' => false, 'type' => 'select', 'selected' => $post['Gatewaygroup']['canada_other']));
                            ?>
                                </td>
                            </tr>
                            <tr class="rate_profile_control">
                                <td><?php __('International'); ?></td>
                                <td>
                                <?php
                            echo $form->input('intl_route', array('options' => array('Other', 'Intra', 'Inter', 'Highest'), 'label' => false, 'class' => 'select', 'div' => false, 'type' => 'select', 'selected' => $post['Gatewaygroup']['intl_route']));
                            ?>
                                </td>
                            </tr>
                            <tr>
                                <td><?php __('Rounding Decimal Places'); ?></td>
                                <td>
                                <?php echo $form->input('rate_decimal', array('label' => false, 'div' => false, 'type' => 'text', 'maxlength' => '5', 'value' => $post['Gatewaygroup']['rate_decimal'])); ?>
                                </td>
                            </tr>
                            <tr>
                                <td><?php __('Rounding'); ?></td>
                                <td>
                                <?php echo $form->input('rate_rounding', array('options' => array('Up', 'Down'), 'label' => false, 'div' => false, 'value' => $post['Gatewaygroup']['rate_rounding'])); ?>
                                </td>
                            </tr>
                             <tr>
                                <td><?php __('LRN Prefix'); ?></td>
                                <td>
                                <?php echo $form->input('lrn_prefix', array('options' => array('False', 'True'), 'label' => false, 'div' => false, 'value' => $post['Gatewaygroup']['lrn_prefix'])); ?>
                                </td>
                            </tr>-->

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
                        <?php //***************************************费率设置************************************************************      ?>
                        <!-- <table class="form">
                            <col style="width:37%;"/><col style="width:62%;"/>
                            <tr>
                                <td colspan="2" class="value">

                                    <div class="cb_select" style="height:30px; line-height:30px;text-align: left;border:none;">
                                        <div>


                                                 <?php
                        empty($post['Gatewaygroup']['lnp']) ? $au = 'false' : $au = 'checked';
                        echo $form->checkbox('lnp', array('checked' => $au, 'style' => 'margin-left: 40px;'))
                        ?>
                                                                                          <label for="cp_modules-c_invoices">LRN</label>

                                            <?php
                        empty($post['Gatewaygroup']['lrn_block']) ? $au = 'false' : $au = 'checked';
                        echo $form->checkbox('lrn_block', array('checked' => $au, 'style' => 'margin-left: 40px;'))
                        ?>
                                                                           <label for="cp_modules-c_stats_summary">Block LRN</label>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </table> -->
                        <!-- / COLUMN 1 --><!-- COLUMN 2 -->
                        <script type="text/javascript" src="<?php echo $this->webroot ?>js/jquery.livequery.js"></script>
                        <script type="text/javascript">
                            jQuery(function($) {
                                $('#addratetable').live('click', function() {
                                    $(this).prev().addClass('clicked');
                                    //window.open('<?php echo $this->webroot ?>clients/addratetable', 'addratetable', 'height=800,width=1000,top=0,left=0,toolbar=no,menubar=no,scrollbars=no, resizable=no,location=no, status=no');
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

                        <?php echo $this->element("gatewaygroups/more_public_fields") ?>


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
                                        <option value="1"><?php __('Accept egress trunk to register') ?> </option>
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

                    <?php echo $this->element("gatewaygroups/host_edit") ?>


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
                                                                                                <td><?php
                        echo $switch_profile['name']
                        ?>
                                                                                                    <input type="hidden" name="server_names[]" value="<?php echo $switch_profile['name'] ?>" />
                                                                                                </td>
                                                                                                <td>
                                                                                                    <select name="profiles[]">
                                                                                                        <option></option>
                            <?php foreach ($profiles as $profile): ?>
                                                                                                                                        <option value="<?php echo $profile[0] ?>" <?php if (in_array($profile[0], $sip_profiles)) echo 'selected="selected"' ?>><?php echo $profile[1] ?></option>
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
                </div>
            </div>


            <div class="widget" data-toggle="collapse-widget" data-collapse-closed="true" id="timeout_setting_div">
                <div class="widget-head"><h4 class="heading"><?php __('Timeout Settings') ?></h4></div>
                <div class="widget-body">
                    <table class="form footable table table-striped dynamicTable tableTools table-bordered  table-white default footable-loaded">
                        <colgroup><col width="37%"><col width="63%">

                        </colgroup>
                        <tr>
                            <td class="align_right padding-r10"><?php __('pddtimeout') ?></td>
                            <td>
                                <?php echo $form->input('wait_ringtime180', array('class' => "width220", 'id' => 'wait_ringtime180', 'label' => false, 'div' => false, 'value' => $post['Gatewaygroup']['wait_ringtime180'], 'type' => 'text', 'maxlength' => '220')); ?>&nbsp;&nbsp;<?php __('ms') ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="align_right padding-r10"><?php __('Min Duration'); ?></td>
                            <td>
                                <?php echo $form->input('delay_bye_second', array('class' => 'width220 validate[custom[onlyNumberSp]]', 'id' => 'delay_bye_second', 'label' => false, 'div' => false, 'type' => 'text', 'maxlength' => '5', 'value' => $post['Gatewaygroup']['delay_bye_second'])); ?>&nbsp;<?php __('s') ?>
                            </td>
                        </tr>
                        <!--
                                <tr>
                                    <td><?php __('Delay Bye Limit'); ?></td>
                                    <td>
                                <?php echo $form->input('delay_bye_limit', array('id' => 'delay_bye_limit', 'label' => false, 'div' => false, 'type' => 'text', 'maxlength' => '5', 'value' => $post['Gatewaygroup']['delay_bye_limit'])); ?>
                                    </td>
                                </tr>
                                -->
                        <tr>
                            <td class="align_right padding-r10"><?php __('Max Duration'); ?></td>
                            <td>
                                <?php echo $form->input('max_duration', array('class' => 'width220 validate[custom[onlyNumberSp]]', 'id' => 'max_duration', 'label' => false, 'div' => false, 'type' => 'text', 'maxlength' => '5', 'value' => $post['Gatewaygroup']['max_duration'])); ?>&nbsp;<?php __('s') ?>
                            </td>
                        </tr>

                        <tr>
                            <td class="align_right padding-r10"><?php __('Ring Timer'); ?></td>
                            <td>
                                <?php echo $form->input('ring_timeout', array('class' => 'width220 validate[min[1],max[60],custom[onlyNumberSp]]', 'id' => 'ring_timeout', 'label' => false, 'div' => false, 'type' => 'text', 'maxlength' => '220', 'value' => $post['Gatewaygroup']['ring_timeout'])); ?> <?php __('s') ?>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>


<!--            <div class="widget" data-toggle="collapse-widget" data-collapse-closed="true">-->
<!--                <div class="widget-head"><h4 class="heading">--><?php //__('Re-invite Settings') ?><!--</h4></div>-->
<!--                <div class="widget-body">-->
<!--                    <table class="form footable table table-striped dynamicTable tableTools table-bordered  table-white default footable-loaded">-->
<!--                        <colgroup><col width="37%"><col width="63%">-->
<!---->
<!--                        </colgroup>-->
<!--                        <tr>-->
<!--                            <td class="align_right padding-r10">--><?php //__('Re-invite'); ?><!--</td>-->
<!--                            <td>-->
<!--                                --><?php
//                                echo $form->input('re_invite', array('options' => array(__('False', true), __('True', true)), 'label' => false, 'class' => 'select', 'div' => false, 'type' => 'select', 'selected' => $post['Gatewaygroup']['re_invite']));
//                                ?>
<!--                            </td>-->
<!--                        </tr>-->
<!--                        <tr>-->
<!--                            <td class="align_right padding-r10">--><?php //echo __('Re-invite interval', true); ?><!-- </td>-->
<!--                            <td>-->
<!--                                --><?php //echo $form->input('re_invite_interval', array('class' => 'width220 validate[min[5],max[60],custom[onlyNumberSp]]', 'label' => false, 'div' => false, 'value' => $post['Gatewaygroup']['re_invite_interval'], 'type' => 'text', 'maxlength' => '2')); ?><!-- --><?php //__('s') ?>
<!--                            </td>-->
<!--                        </tr>-->
<!---->
<!--                    </table>-->
<!--                </div>-->
<!--            </div>-->





<!--

            <div class="widget" data-toggle="collapse-widget" data-collapse-closed="true">
                <div class="widget-head"><h4 class="heading"><?php __('DTMF Settings') ?></h4></div>
                <div class="widget-body">
                    <table class="form footable table table-striped dynamicTable tableTools table-bordered  table-white default footable-loaded">
                        <colgroup><col width="37%"><col width="63%">

                        </colgroup>
                        <fieldset>
                            <tr>
                                <td class="align_right padding-r10"><?php __('DTMF INFO'); ?></td>
                                <td>
                                    <?php $info = isset($post['Gatewaygroup']['info']) ? $post['Gatewaygroup']['info'] : "0"; ?>
                                    <?php echo $form->input('info', array('options' => array(__('Disable', true), __('Enable', true)), 'label' => false, 'div' => false, 'value' => $info)); ?>
                                </td>
                            </tr>
                            <tr>
                                <td class="align_right padding-r10"><?php __('DTMF RFC2833'); ?></td>
                                <td>
                                    <?php $rfc2833 = isset($post['Gatewaygroup']['rfc2833']) ? $post['Gatewaygroup']['rfc2833'] : "0"; ?>
                                    <?php echo $form->input('rfc2833', array('options' => array(__('Disable', true), __('Enable', true)), 'label' => false, 'div' => false, 'value' => $rfc2833)); ?>
                                </td>
                            </tr>
                            <tr>
                                <td class="align_right padding-r10"><?php __('DTMF INBAND'); ?></td>
                                <td>
                                    <?php $inband = isset($post['Gatewaygroup']['inband']) ? $post['Gatewaygroup']['inband'] : "0"; ?>
                                    <?php echo $form->input('inband', array('options' => array(__('Disable', true), __('Enable', true)), 'label' => false, 'div' => false, 'value' => $inband)); ?>
                                </td>
                            </tr>
                        </fieldset>
                    </table>
                </div>
            </div>

-->

            <div class="widget" data-toggle="collapse-widget" data-collapse-closed="true">
                <div class="widget-head"><h4 class="heading"><?php __('Codecs Settings') ?></h4></div>
                <div class="widget-body">
                    <table class="form footable table table-striped dynamicTable tableTools table-bordered  table-white default footable-loaded">
                        <colgroup><col width="37%"><col width="63%">

                        </colgroup>
                        <fieldset>
                            <tr>
                                <td class="align_right padding-r10"><?php __('Codecs') ?></td>
                                <td>
                                    <table class="form">
                                        <tr>
                                            <td>
                                                <?php
                                                echo $form->input('select1', array('id' => 'select1', 'options' => $nousecodes, 'multiple' => true,
                                                    'style' => 'width: 200px; height: 200px;',
                                                    'label' => false, 'class' => 'select', 'div' => false, 'type' => 'select'));
                                                ?>
                                            </td>
                                            <td>
                                                <input  style="width: 60px; height: 30px; margin-left: 0px;"    onclick="DoAdd();"  type="button"  value="<?php __('add') ?>"class="input in-submit btn"  />
                                                <br/><br/>
                                                <input  type="button"   style="width: 60px; height: 30px; margin-left: 0px;"  onclick="DoDel();"   value="<?php __('delete') ?>" class="input in-submit btn" />
                                            </td>
                                            <td>
                                                <?php
                                                echo $form->input('select2', array('id' => 'select2', 'options' => $usecodes, 'multiple' => true,
                                                    'style' => 'width: 200px; height: 200px;',
                                                    'label' => false, 'class' => 'select', 'div' => false, 'type' => 'select'));
                                                ?>
                                            </td>
                                            <td>
                                                <input class="input in-submit btn"  style="width: 60px; height: 30px; margin-left: 0px;"    onclick="moveOption('select2', 'up');"  type="button"  value="<?php __('up') ?>"  />
                                                <br/><br/>
                                                <input  type="button" class="input in-submit btn"  style="width: 60px; height: 30px; margin-left: 0px;"  onclick="moveOption('select2', 'down');"   value="<?php __('Down') ?>"  />
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>

                        </fieldset>


                    </table>
                </div>
            </div>








            <?php //echo $this->element("gatewaygroups/editegsd");     ?>

            <?php
            if ($_SESSION['role_menu']['Routing']['gatewaygroups:view']['model_w'])
            {
                ?> <div id="form_footer" class="center buttons">
                    <input type="submit"   onclick="seleted_codes();" class="btn btn-primary" value="<?php echo __('submit') ?>" />

                    <input type="reset" class="input in-submit btn btn-default" value="<?php echo __('reset', true); ?>" />
                </div><?php } ?>
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
                                jQuery('#totalCall,#totalCPS').xkeyvalidate({type: 'Num'});
                            }

                    );

                    $(function() {

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
                                    $("#max_call_limit").html(call_limit);
                                    $("#max_cps_limit").html(cps_limit);

                                    $("#totalCall").attr('class', "validate[max[" + call_limit + "],custom[onlyNumberSp]]");
                                    $("#totalCPS").attr('class', "validate[max[" + cps_limit + "],custom[onlyNumberSp]]");

                                }
                            });
                        });


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
                            if($('#host_authorize').val() == 0 && $('.ip-host-dd').val() == 'ip' && !$('#ip').val().toString().match(/\b\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}\b/)){
                                jQuery(this).jGrowlError('Wrong IP Format!', {theme: 'jmsg-error'});
                                return false;
                            }

                            if (jQuery('#wait_ringtime180').val() != '') {
                                if (!/\d+|\./.test(jQuery('#wait_ringtime180').val())) {
                                    jQuery('#wait_ringtime180').addClass('invalid');
                                    jGrowl_to_notyfy('PDD Timeout must contain numeric characters only!', {theme: 'jmsg-error'});
                                    if($("#timeout_setting_div").find('.in').size() == 0){
                                        $("#timeout_setting_div").find('.collapse-toggle').click();
                                    }
                                    return false;
                                }
                            }
                            if (jQuery('#delay_bye_second').val() != '') {
                                if (!/\d+|\./.test(jQuery('#delay_bye_second').val())) {
                                    jQuery('#delay_bye_second').addClass('invalid');
                                    jGrowl_to_notyfy('Min Duration must contain numeric characters only!', {theme: 'jmsg-error'});
                                    if($("#timeout_setting_div").find('.in').size() == 0){
                                        $("#timeout_setting_div").find('.collapse-toggle').click();
                                    }
                                    return false;
                                }
                            }
                            if (jQuery('#max_duration').val() != '') {
                                if (!/\d+|\./.test(jQuery('#max_duration').val())) {
                                    jQuery('#max_duration').addClass('invalid');
                                    jGrowl_to_notyfy('Max Duration must contain numeric characters only!', {theme: 'jmsg-error'});
                                    if($("#timeout_setting_div").find('.in').size() == 0){
                                        $("#timeout_setting_div").find('.collapse-toggle').click();
                                    }
                                    return false;
                                }
                            }

                            if (jQuery('#ring_timeout').val() == '' || jQuery('#ring_timeout').val() < 1 || jQuery('#ring_timeout').val() > 60) {
                                jQuery('#ring_timeout').addClass('invalid');
                                jGrowl_to_notyfy('Ring Timer cant not be greater than 60 or less than 1!', {theme: 'jmsg-error'});
                                if($("#timeout_setting_div").find('.in').size() == 0){
                                    $("#timeout_setting_div").find('.collapse-toggle').click();
                                }
                                return false;
                            }

                            return true;
                        });


                        var did_billing_method_tr = $('#did_billing_method_tr');
                        var did_billing_rule_tr = $('#did_billing_rule_tr');
                        var did_rate_table_tr = $('#did_rate_table_tr');
                        var did_amount_per_port_tr = $('#did_amount_per_port_tr');


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

                        jQuery('#GatewaygroupTrunkType2').parent().parent().hide();



                    });
</script>

