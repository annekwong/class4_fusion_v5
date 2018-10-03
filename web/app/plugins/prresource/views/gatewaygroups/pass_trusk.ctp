<ul class="breadcrumb">
    <li><?php __('You are here'); ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Management') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Carrier')?> [<?php echo $client_name ?>]</li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Edit')?> <?php echo  ($type == 'ingress') ? "Ingress" : "Egress" ; ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('PASS')?> [<?php echo $res['Gatewaygroup']['alias']; ?>]</li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php __('PASS')?> [<?php echo $res['Gatewaygroup']['alias']; ?>]</h4>
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>

<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-head">

            <?php if ($type == 'ingress'): ?>
                <?php echo  $this->element('ingress_tab',array('active_tab'=>'pass_trusk'));?>
            <?php else: ?>
                <?php echo  $this->element('egress_tab',array('active_tab'=>'pass_trusk'));?>
            <?php endif; ?>
        </div>
        <div class="widget-body">

            <?php echo $form->create ('Gatewaygroup', array ('url' => '/' . $this->params['url']['url']));?>

            <div id="support_panel" style="text-align:center;">


                <?php if ($type == 'ingress'): ?>

                    <?php

                    $options = array(
                        'Never',
                        'Pass_Through',
                        'Always',
                    );

                    ?>
                <table class="form footable table table-striped dynamicTable tableTools table-bordered  table-white default footable-loaded">
                    <colgroup><col width="37%"><col width="63%">

                    </colgroup>

                    <tr>
                        <td class="align_right padding-r10">
                            <label title="Remote-Party-ID"><?php __('RPID')?></label>
                        </td>
                        <td>
                            <?php echo $form->input('rpid',array('label'=>false ,'div'=>false,'type'=>'select', 'value'=>$res['Gatewaygroup']['rpid'], 'options'=>$options));?>
                        </td>
                    </tr>

                    <tr class="rpid_control">
                        <td class="align_right padding-r10"><?php __('RPID Screen'); ?></td>
                        <td>
                            <?php echo $form->input('rpid_screen', array('options' => array('None', 'No', 'Yes', 'Proxy'), 'label' => false, 'div' => false, 'value' => $res['Gatewaygroup']['rpid_screen'])); ?>
                        </td>
                    </tr>
                    <tr class="rpid_control">
                        <td class="align_right padding-r10"><?php __('RPID Party'); ?></td>
                        <td>
                            <?php echo $form->input('rpid_party', array('options' => array('None', 'Calling', 'Called', 'Proxy'), 'label' => false, 'div' => false, 'value' => $res['Gatewaygroup']['rpid_party'])); ?>
                        </td>
                    </tr>
                    <tr class="rpid_control">
                        <td class="align_right padding-r10"><?php __('RPID Id Type'); ?></td>
                        <td>
                            <?php echo $form->input('rpid_id_type', array('options' => array('None', 'Subscriber', 'User', 'Term', 'Proxy'), 'label' => false, 'div' => false, 'value' => $res['Gatewaygroup']['rpid_id_type'])); ?>
                        </td>
                    </tr>
                    <tr class="rpid_control">
                        <td class="align_right padding-r10"><?php __('RPID Privacy'); ?></td>
                        <td>
                            <?php echo $form->input('rpid_privacy', array('options' => array('None', 'Full', 'Name', 'Url', 'OFF', 'Ipaddr', 'Proxy'), 'label' => false, 'div' => false, 'value' => $res['Gatewaygroup']['rpid_privacy'])); ?>
                        </td>
                    </tr>



                    <tr>
                        <td class="align_right padding-r10">
                            <label title="P-Asserted-Identity"><?php __('PAID')?></label>
                        </td>
                        <td>
                            <?php echo $form->input('paid',array('label'=>false ,'div'=>false,'type'=>'select', 'value'=>$res['Gatewaygroup']['paid'], 'options'=>$options));?>
                        </td>
                    </tr>
                    <tr>
                        <td class="align_right padding-r10">
                            <label title="isup-oli"><?php __('OLI')?></label>
                        </td>
                        <td>
                            <?php echo $form->input('oli',array('label'=>false ,'div'=>false,'type'=>'select', 'value'=>$res['Gatewaygroup']['oli'], 'options'=>$options));?>
                        </td>
                    </tr>
                    <tr>
                        <td class="align_right padding-r10">
                            <label title="P-Charge-Info"><?php __('PCI')?></label>
                        </td>
                        <td>
                            <?php echo $form->input('pci',array('label'=>false ,'div'=>false,'type'=>'select', 'value'=>$res['Gatewaygroup']['pci'], 'options'=>$options));?>
                        </td>
                    </tr>
                    <tr>
                        <td class="align_right padding-r10">
                            <label title="Privacy"><?php __('PRIV')?></label>
                        </td>
                        <td>
                            <?php echo $form->input('priv',array('label'=>false ,'div'=>false,'type'=>'select', 'value'=>$res['Gatewaygroup']['priv'], 'options'=>$options));?>
                        </td>
                    </tr>
                    <tr>
                        <td class="align_right padding-r10">
                            <label title="Diversion"><?php __('DIV')?></label>
                        </td>
                        <td>
                            <?php echo $form->input('div',array('label'=>false ,'div'=>false,'type'=>'select', 'value'=>$res['Gatewaygroup']['div'], 'options'=>$options));?>
                        </td>
                    </tr>
                    <tr>
                        <td class="align_right padding-r10"><?php __('Display Name'); ?></td>
                        <td>
                            <?php echo $form->input('display_name', array('options' => array('False', 'True'), 'label' => false, 'div' => false, 'value' => $res['Gatewaygroup']['display_name'])); ?>
                        </td>
                    </tr>
                    </table>
                    <script type="text/javascript">



                        $(function(){
                            var $rpid_control = $('.rpid_control');

                            $('#GatewaygroupRpid').change(function() {
                                var $this = $(this);
                                var val = $this.val();
                                if (val == 0)
                                {
                                    $rpid_control.hide();
                                }
                                else
                                {
                                    $rpid_control.show();
                                }
                            }).trigger('change');
                        });

                    </script>

                <?php else: ?>

                    <table class="form footable table table-striped dynamicTable tableTools table-bordered  table-white default footable-loaded">
                        <colgroup><col width="37%"><col width="63%">

                        </colgroup>



                        <tr>
                            <td class="align_right padding-r10">
                                <label title="P-Asserted-Identity"><?php __('RPID')?></label>
                            </td>
                            <td>
                                <?php
                                $tmp_arr = array(0 => "Never",1 => "Pass Through", 3 => "Updating Caller Number");
                                echo $form->input('rpid',array('label'=>false ,'div'=>false,'type'=>'select', 'options' =>$tmp_arr,  'selected'=>$res['Gatewaygroup']['rpid']));

                                ?>
                            </td>
                        </tr>
                        <!--<tr class="rpid_control">
                            <td class="align_right padding-r10"><?php /*__('RPID Screen'); */?></td>
                            <td>
                                <?php /*echo $form->input('rpid_screen', array('options' => array(__('None', true), __('No', true), __('Yes', true), __('Proxy', true)), 'label' => false, 'div' => false, 'value' => $res['Gatewaygroup']['rpid_screen'])); */?>
                            </td>
                        </tr>
                        <tr class="rpid_control">
                            <td class="align_right padding-r10"><?php /*__('RPID Party'); */?></td>
                            <td>
                                <?php /*echo $form->input('rpid_party', array('options' => array(__('None', true), __('Calling', true), __('Called', true), __('Proxy', true)), 'label' => false, 'div' => false, 'value' => $res['Gatewaygroup']['rpid_party'])); */?>
                            </td>
                        </tr>
                        <tr class="rpid_control">
                            <td class="align_right padding-r10"><?php /*__('RPID Id Type'); */?></td>
                            <td>
                                <?php /*echo $form->input('rpid_id_type', array('options' => array(__('None', true), __('Subscriber', true), __('User', true), __('Term', true), __('Proxy', true)), 'label' => false, 'div' => false, 'value' => $res['Gatewaygroup']['rpid_id_type'])); */?>
                            </td>
                        </tr>
                        <tr class="rpid_control">
                            <td class="align_right padding-r10"><?php /*__('RPID Privacy'); */?></td>
                            <td>
                                <?php /*echo $form->input('rpid_privacy', array('options' => array(__('None', true), __('Full', true), __('Name', true), __('Url', true), __('OFF', true), __('Ipaddr', true), __('Proxy', true)), 'label' => false, 'div' => false, 'value' => $res['Gatewaygroup']['rpid_privacy'])); */?>
                            </td>
                        </tr>-->

                        <tr>
                            <td class="align_right padding-r10">
                                <label title="P-Asserted-Identity"><?php __('Enable PAID')?></label>
                            </td>
                            <td>
                                <?php echo $form->input('paid',array('label'=>false ,'div'=>false,'type'=>'checkbox', 'checked'=>$res['Gatewaygroup']['paid']? true : false));?>
                            </td>
                        </tr>

                        <tr>
                            <td class="align_right padding-r10">
                                <label title="isup-oli"><?php __('Enable OLI')?></label>
                            </td>
                            <td>
                                <?php echo $form->input('oli',array('label'=>false ,'div'=>false,'type'=>'checkbox', 'checked'=>$res['Gatewaygroup']['oli']? true : false));?>
                            </td>
                        </tr>
                        <tr>
                            <td class="align_right padding-r10">
                                <label title="P-Charge-Info"><?php __('Enable PCI')?></label>
                            </td>
                            <td>
                                <?php echo $form->input('pci',array('label'=>false ,'div'=>false,'type'=>'checkbox', 'checked'=>$res['Gatewaygroup']['pci']? true : false));?>
                            </td>
                        </tr>
                        <tr>
                            <td class="align_right padding-r10">
                                <label title="Privacy"><?php __('Enable PRIV')?></label>
                            </td>
                            <td>
                                <?php echo $form->input('priv',array('label'=>false ,'div'=>false,'type'=>'checkbox', 'checked'=>$res['Gatewaygroup']['priv']? true : false));?>
                            </td>
                        </tr>
                        <tr>
                            <td class="align_right padding-r10">
                                <label title="Diversion"><?php __('Enable DIV')?></label>
                            </td>
                            <td>
                                <?php echo $form->input('div',array('label'=>false ,'div'=>false,'type'=>'checkbox', 'checked'=>$res['Gatewaygroup']['div']? true : false));?>
                            </td>
                        </tr>
                        <tr>
                            <td class="align_right padding-r10"><?php __('Display Name'); ?></td>
                            <td>
                                <?php echo $form->input('display_name', array('label'=>false ,'div'=>false,'type'=>'checkbox',  'checked'=> $res['Gatewaygroup']['display_name']? true : false)); ?>
                            </td>
                        </tr>




                    </table>
                    <script type="text/javascript">

                        /*if ($('#GatewaygroupRpid').is(':checked') === false)
                        {
                            $rpid_control.hide();
                        }
                        else
                        {
                            $rpid_control.show();
                        }*/


                        $(function(){
                            var $rpid_control = $('.rpid_control');
                            $('#GatewaygroupRpid').change(function() {
                                var $this = $(this);
                                var val = $this.val();
                                if (val == 0)
                                {
                                    $rpid_control.hide();
                                }
                                else
                                {
                                    $rpid_control.show();
                                }
                            }).trigger('change');
                        });

                    </script>

                <?php endif; ?>
                <br />
                <br />
                <br />

                <div class="button-groups">
                    <input type="submit" class="btn btn-primary" value="<?php __('Submit')?>" />
                </div>
            </div>
            <?php echo $form->end(); ?>
        </div>
    </div>
</div>

