<style>
.tab-content{
        margin: 0 0 0 50px;
}
#myTab li a{cursor:pointer;}
</style>
<ul class="breadcrumb">
    <li><?php __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>template/resource">
        <?php echo __('Template', true); ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot . $this->params['url']['url'] ?>">
        <?php echo $page_name; ?></a></li>
</ul>
<div class="heading-buttons">
    <h4 class="heading"><?php echo $page_name; ?></h4>
</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
    <a href="<?php echo $this->webroot ?>template/resource/<?php echo $type; ?>" class="link_back btn btn-icon btn-inverse glyphicons circle_arrow_left"><i></i><?php echo __('goback', true); ?></a>
</div>
<div class="clearfix"></div>
<div class="innerLR ">

    <div class="widget widget-heading-simple widget-tabs widget-body-white">
        <div class="widget-head">
            <?php echo $this->element('template/resource_tab', array('active_tab' => 'base')); ?>
        </div>
       <form action="" method="post" id="myForm">
       <div style=" margin: 50px 0 0 0;">
           <table class="form footable table dynamicTable tableTools table-white default footable-loaded">
               <colgroup>
                   <col width="37%">
                   <col width="63%">
               </colgroup>
               <tr>
                   <td class="align_right padding-r10"><?php echo __('Template Name', true); ?></td>
                   <td>
                       <?php echo $form->input('name', array('class' => 'width220 validate[required]',
                           'label' => false, 'div' => false, 'type' => 'text',
                           'value' => isset($template_info['ResourceTemplate']['name']) ? $template_info['ResourceTemplate']['name'] : ""
                       )); ?>
                       <input type="hidden" value="<?php echo isset($template_info['ResourceTemplate']['resource_template_id']) ? $template_info['ResourceTemplate']['resource_template_id'] : ""; ?>" id="template_id" name="template_id" />
                   </td>
               </tr>
           </table>
       </div>

        <div class="tab-content" style=" margin: 0 0 20px 0;">
                <div class="tab-pane active" id="base">
                        <table class="form footable table table-striped dynamicTable tableTools table-bordered  table-white default footable-loaded">
                            <colgroup>
                                <col width="37%">
                                <col width="63%">
                            </colgroup>
                            <tr>
                                <td class="align_right padding-r10"><?php echo __('Media Type', true); ?></td>
                                <td>
                                    <?php
                                    //                                    $t = array('0' => __('Transcoding Media',true), '2' => __('Bypass Media',true), '1' => __('Proxy Media',true));
                                    $t = array('2' => __('Bypass Media',true), '1' => __('Proxy Media',true));
                                    echo $form->input('media_type', array('id'=>'media_type','options' => $t,
                                        'label' => false, 'class' => 'select', 'div' => false, 'type' => 'select',
                                        'value' => isset($template_info['ResourceTemplate']['media_type']) ? $template_info['ResourceTemplate']['media_type'] : 2));
                                    ?>
                                </td>
                            </tr>

                            <tr id="rtp_timeout" style="display:none;">
                                <td class="align_right padding-r10"><?php echo __('RTP Timeout', true); ?></td>
                                <td align="left">
                                    <?php echo $form->input('media_timeout', array('class' => 'width220 validate[custom[onlyNumberSp]]',
                                        'id' => 'media_timeout', 'label' => false, 'div' => false, 'type' => 'text', 'maxlength' => '8',
                                        'value' => isset($template_info['ResourceTemplate']['media_timeout']) ? $template_info['ResourceTemplate']['media_timeout'] : ""
                                    )); ?>&nbsp;<?php __('s')?>
                                </td>
                            </tr>


                            <tr style="display:none;">
                                <td class="align_right padding-r10"><?php __('Ignore Early media') ?></td>
                                <td>
                                    <?php
                                    $ignore_arr = array(0 => 'NONE', 1 => '180 and 183', 2 => '180 only', 3 => '183 only');
                                    echo $form->input('ignore_early_media', array('options' => $ignore_arr,
                                        'label' => false, 'class' => 'select', 'div' => false, 'type' => 'select',
                                        'value' => isset($template_info['ResourceTemplate']['ignore_early_media']) ? $template_info['ResourceTemplate']['ignore_early_media'] : ""
                                    ));
                                    ?>
                                </td>
                            </tr>

                            <tr>
                                <td class="align_right padding-r10"><?php __('pddtimeout') ?></td>
                                <td>
                                    <?php echo $form->input('wait_ringtime180', array('class' => 'width220 validate[min[1000],max[60000],custom[onlyNumberSp]]',
                                        'id' => 'wait_ringtime180', 'label' => false, 'div' => false, 'type' => 'text',
                                        'maxlength' => '5',
                                        'value' => isset($template_info['ResourceTemplate']['wait_ringtime180']) ? $template_info['ResourceTemplate']['wait_ringtime180'] : $default_timeout['egress_pdd_timeout']
                                    )); ?> <?php __('ms')?>
                                </td>
                            </tr>

                            <tr>
                                <td class="align_right padding-r10"><?php __('HostStrategy') ?></td>
                                <td>
                                    <?php
                                    $t = array('1' => __('top-down',true), '2' => __('round-robin',true));
                                    echo $form->input('res_strategy', array('options' => $t, 'label' => false,
                                        'class' => 'select', 'div' => false, 'type' => 'select',
                                        'value' => isset($template_info['ResourceTemplate']['res_strategy']) ? $template_info['ResourceTemplate']['res_strategy'] : ""
                                    ));
                                    ?>
                                </td>
                            </tr>
                            <?php if ($type): ?>
                                <tr>
                                    <td class="align_right padding-r10"><?php __('Pass Dipping Info'); ?></td>
                                    <td>
                                        <?php
                                        $lnp_dipping = 'false';
                                        echo $form->input('lnp_dipping', array('options' => array('true' => __('Add Header',true), 'false' => __('Not Add Header',true)),
                                            'label' => false, 'class' => 'select', 'div' => false, 'type' => 'select',
                                            'selected' => isset($template_info['ResourceTemplate']['lnp_dipping']) ? $template_info['ResourceTemplate']['lnp_dipping'] : $lnp_dipping
                                        ));
                                        ?>
                                    </td>
                                </tr>
                            <?php endif; ?>
                            <tr>
                                <td class="align_right padding-r10"><?php __('Min Duration'); ?></td>
                                <td>
                                    <?php echo $form->input('delay_bye_second', array('class' => 'width220 validate[custom[onlyNumberSp]]',
                                        'id' => 'delay_bye_second', 'label' => false, 'div' => false, 'type' => 'text',
                                        'value' => isset($template_info['ResourceTemplate']['delay_bye_second']) ? $template_info['ResourceTemplate']['delay_bye_second'] : ""
                                    )); ?>&nbsp;<?php __('s')?>
                                </td>
                            </tr>
                            <tr>
                                <td class="align_right padding-r10"><?php __('Max Duration'); ?></td>
                                <td>
                                    <?php echo $form->input('max_duration', array('class' => 'width220 validate[custom[integer]]',
                                        'id' => 'max_duration', 'label' => false, 'div' => false, 'type' => 'text',
                                        'value' => isset($template_info['ResourceTemplate']['max_duration']) ? $template_info['ResourceTemplate']['max_duration'] : $default_timeout['call_timeout']
                                    )); ?>&nbsp;<?php __('s')?>
                                </td>
                            </tr>
                            <?php if ($is_did_enable): ?>
                                <!--<tr>
                                    <td class="align_right padding-r10"><?php /*__('Type'); */?></td>
                                    <td>
                                        <?php
                                /*                                        echo $form->input('trunk_type2', array('options' => array(0 => 'Termination Traffic', 1 => 'DID Traffic'),
                                                                            'label' => false, 'class' => 'select', 'div' => false, 'type' => 'select',
                                                                            'selected' => isset($template_info['ResourceTemplate']['trunk_type2']) ? $template_info['ResourceTemplate']['trunk_type2'] : 0
                                                                        ));
                                                                        */?>
                                    </td>
                                </tr>
                                <tr id="did_billing_rule_tr">
                                    <td class="align_right padding-r10"><?php /*__('Orig. Billing Rule'); */?></td>
                                    <td>
                                        <?php
                                /*                                        echo $form->input('billing_rule', array('options' => $routing_rules,
                                                                            'empty' => '  ', 'label' => false, 'class' => 'select', 'div' => false, 'type' => 'select',
                                                                            'selected' => isset($template_info['ResourceTemplate']['billing_rule']) ? $template_info['ResourceTemplate']['billing_rule'] : ""
                                                                        ));
                                                                        */?>
                                    </td>
                                </tr>
                                <tr id="did_billing_method_tr">
                                    <td class="align_right padding-r10"><?php /*__('Billing Method'); */?></td>
                                    <td>
                                        <?php
                                /*                                        echo $form->input('billing_method', array('options' => array(0 => __('By Minute',true), 1 => __('By Port',true)),
                                                                            'label' => false, 'class' => 'select', 'div' => false, 'type' => 'select',
                                                                            'selected' => isset($template_info['ResourceTemplate']['billing_method']) ? $template_info['ResourceTemplate']['billing_method'] : 0
                                                                        ));
                                                                        */?>
                                    </td>
                                </tr>-->
                            <?php endif; ?>
                            <tr id="did_rate_table_tr">
                                <td class="align_right padding-r10"><?php __('T.38'); ?></td>
                                <td>
<!--                                    --><?php //var_dump($template_info); ?>
                                    <select name="data[t38]" class="select" id="GatewaygroupT38">
                                        <option value="true" <?php if(isset($template_info['ResourceTemplate']['t38']) && $template_info['ResourceTemplate']['t38'] == true) echo 'selected';?>>Enable</option>
                                        <option value="false" <?php if(isset($template_info['ResourceTemplate']['t38']) && $template_info['ResourceTemplate']['t38'] == false) echo 'selected';?>>Disable</option>
                                    </select>
                                </td>
                            </tr>
                            <tr id="did_rate_table_tr">
                                <td class="align_right padding-r10"><?php __('Rate Table'); ?></td>
                                <td>
                                    <?php
                                    echo $form->input('rate_table_id', array('options' => $rate,
                                        'empty' => '  ', 'label' => false, 'class' => 'select', 'div' => false, 'type' => 'select',
                                        'selected' => isset($template_info['ResourceTemplate']['rate_table_id']) ? $template_info['ResourceTemplate']['rate_table_id'] : ''
                                    ));
                                    ?>
                                </td>
                            </tr>
                            <?php if ($type): ?>
                                <tr>
                                    <td class="align_right padding-r10"><?php __('Random ANI GROUP'); ?></td>
                                    <td>
                                        <?php
                                        echo $form->input('random_table_id', array('options' => $random_table,
                                            'empty' => '  ', 'label' => false, 'class' => 'select', 'div' => false, 'type' => 'select',
                                            'selected' => isset($template_info['ResourceTemplate']['random_table_id']) ? $template_info['ResourceTemplate']['random_table_id'] : ''
                                        ));
                                        ?>
                                    </td>
                                </tr>
                            <?php endif; ?>
                            <!--
                            <?php if ($is_did_enable): ?>
                                <tr id="did_amount_per_port_tr">
                                    <td class="align_right padding-r10"><?php __('Per Port Amount'); ?></td>
                                    <td>
                                        <?php echo $form->input('amount_per_port', array('class' => 'validate[custom[number]]',
                                'id' => 'amount_per_port', 'label' => false, 'div' => false, 'type' => 'text',
                                'value' => isset($template_info['ResourceTemplate']['amount_per_port']) ? $template_info['ResourceTemplate']['amount_per_port'] : ''
                            )); ?>
                                    </td>
                                </tr>
                            <?php endif; ?>
                            -->
                            <!--                            <tr>-->
                            <!--                                <td class="align_right padding-r10">--><?php //__('Re-invite'); ?><!--</td>-->
                            <!--                                <td>-->
                            <!--                                    --><?php
                            //                                    echo $form->input('re_invite', array('options' => array( __('False',true), __('True',true)),
                            //                                        'label' => false, 'class' => 'select', 'div' => false, 'type' => 'select',
                            //                                        'selected' => isset($template_info['ResourceTemplate']['re_invite']) ? $template_info['ResourceTemplate']['re_invite'] : ''
                            //                                    ));
                            //                                    ?>
                            <!--                                </td>-->
                            <!--                            </tr>-->
                            <!--                            <tr>-->
                            <!--                                <td class="align_right padding-r10">--><?php //echo __('Re-invite interval', true); ?><!-- </td>-->
                            <!--                                <td>-->
                            <!--                                    --><?php //echo $form->input('re_invite_interval', array('class' => 'width220 validate[min[5],max[60],custom[onlyNumberSp]]',
                            //                                        'label' => false, 'div' => false, 'type' => 'text', 'maxlength' => '2',
                            //                                        'value' => isset($template_info['ResourceTemplate']['re_invite_interval']) ? $template_info['ResourceTemplate']['re_invite_interval'] : ''
                            //                                    )); ?><!-- --><?php //__('s')?>
                            <!--                                </td>-->
                            <!--                            </tr>-->
                            <!--
                            <tr>
                                <td class="align_right padding-r10"><?php __('DTMF INFO'); ?></td>
                                <td>
                                    <?php echo $form->input('info', array('options' => array( __('Disable',true), __('Enable',true)),
                                'label' => false, 'div' => false,
                                'selected' => isset($template_info['ResourceTemplate']['info']) ? $template_info['ResourceTemplate']['info'] : ''
                            )); ?>
                                </td>
                            </tr>
                            <tr>
                                <td class="align_right padding-r10"><?php __('DTMF RFC2833'); ?></td>
                                <td>
                                    <?php echo $form->input('rfc2833', array('options' => array( __('Disable',true), __('Enable',true)),
                                'label' => false, 'div' => false,
                                'selected' => isset($template_info['ResourceTemplate']['rfc2833']) ? $template_info['ResourceTemplate']['rfc2833'] : ''
                            )); ?>
                                </td>
                            </tr>
                            <tr>
                                <td class="align_right padding-r10"><?php __('DTMF INBAND'); ?></td>
                                <td>
                                    <?php echo $form->input('inband', array('options' => array( __('Disable',true), __('Enable',true)),
                                'label' => false, 'div' => false,
                                'selected' => isset($template_info['ResourceTemplate']['inband']) ? $template_info['ResourceTemplate']['inband'] : ''
                            )); ?>
                                </td>
                            </tr>
                            -->
                            <tr>
                                <td class="align_right padding-r10"><?php __('Ring Timer'); ?></td>
                                <td>
                                    <?php echo $form->input('ring_timeout', array('class' => 'width220 validate[min[1],max[60],custom[onlyNumberSp]]',
                                        'id' => 'ring_timeout', 'label' => false, 'div' => false, 'type' => 'text', 'maxlength' => '5',
                                        'value' => isset($template_info['ResourceTemplate']['ring_timeout']) ? $template_info['ResourceTemplate']['ring_timeout'] : $default_timeout['ring_timeout']
                                    )); ?> s
                                </td>
                            </tr>
                            <?php if(!$type): ?>
                                <tr>
                                    <td class="align_right padding-r10"><?php __('Ignore Early media') ?></td>
                                    <td>
                                        <?php
                                        $ignore_arr = array(0 => 'NONE', 1 => '180 and 183', 2 => '180 only', 3 => '183 only');
                                        echo $form->input('ignore_ring_early_media', array('options' => $ignore_arr,
                                            'label' => false, 'class' => 'select', 'div' => false, 'type' => 'select',
                                            'selected' => isset($template_info['ResourceTemplate']['ignore_ring_early_media']) ? $template_info['ResourceTemplate']['ignore_ring_early_media'] : 0));
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="align_right padding-r10"><?php __('Ignore Early NOSDP'); ?></td>
                                    <td>
                                        <?php
                                        $nosdp = 0;
                                        echo $form->input('ignore_early_nosdp', array('options' => array('False', 'True'),
                                            'label' => false, 'class' => 'select', 'div' => false, 'type' => 'select',
                                            'selected' => isset($template_info['ResourceTemplate']['ignore_early_nosdp']) ? $template_info['ResourceTemplate']['ignore_early_nosdp'] : 0));
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="align_right padding-r10"><?php __('Bill By'); ?></td>
                                    <td>
                                        <?php
                                        echo $form->input('bill_by', array('options' => array(0 => 'DNIS', 1 => 'LRN',
                                            2 => 'LRN BLOCK', 3 => 'LRN Block Higher', 4 => 'Follow Rate Deck'),
                                            'label' => false, 'class' => 'select', 'div' => false, 'type' => 'select',
                                            'selected' => isset($template_info['ResourceTemplate']['bill_by']) ? $template_info['ResourceTemplate']['bill_by'] : 4));
                                        ?>
                                    </td>
                                </tr>
                            <?php endif; ?>
                            <tr>
                                <td class="align_right padding-r10"><?php __('codecs') ?></td>
                                <td>
                                    <table class="form">
                                        <tr>
                                            <td>
                                                <?php echo $form->input('select1', array('id' => 'select1', 'options' => $nousecodes, 'multiple' => true, 'style' => 'width: 200px; height: 250px;', 'label' => false, 'class' => 'select', 'div' => false, 'type' => 'select')); ?>
                                            </td>
                                            <td>
                                                <input  style="width: 60px; height: 30px; margin-left: 0px;" onclick="DoAdd();"  type="button"  value="<?php __('add') ?>"  class="input in-submit btn"/>
                                                <br/><br/>
                                                <input  type="button" style="width: 60px; height: 30px; margin-left: 0px;" onclick="DoDel();" value="<?php __('delete') ?>" class="input in-submit btn" />
                                            </td>
                                            <td>
                                                <?php echo $form->input('select2', array('id' => 'select2', 'options' => $usecodes, 'multiple' => true, 'style' => 'width: 200px; height: 250px;', 'label' => false, 'class' => 'select', 'div' => false, 'type' => 'select')); ?>
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
                        </table>
                </div>

                <div class="tab-pane" id="action">
                        <a href="javascript:void(0)" id="add_action" class="btn btn-primary btn-icon glyphicons circle_plus">
                            <?php echo __('add', true); ?>
                            <i></i>
                        </a>

                        <div class="overflow_x separator">
                            <table class="footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary"   id="list_table" >
                                <thead>
                                <tr>
                                    <th><?php __('timeprofile') ?></th>
                                    <th><?php __('Target') ?></th>
                                    <th><?php __('code') ?></th>
                                    <th><?php __('action') ?></th>
                                    <th><?php __('Chars to Add') ?></th>
                                    <th><?php __('Num of chars to Del') ?></th>
                                    <th><?php __('numbertype') ?></th>
                                    <th><?php __('numberlength') ?></th>
                                    <th><?php __('Action') ?></th>
                                </tr>
                                </thead>
                                <tbody id="action_tbody">
                                <?php foreach ($direction_arr as $direction_key => $direction): ?>
                                    <tr>
                                        <td>
                                            <?php echo $form->input('accounts_time_profile_id', array('options' => $timepro, 'name' => "accounts[$direction_key][time_profile_id]", 'selected' => '',
                                                'label' => false, 'class' => 'select width120 accounts_time_profile_id validate[required]',
                                                'selected' => $direction['ResourceDirectionTemplate']['time_profile_id'],
                                                'div' => false, 'type' => 'select','id' => false));  ?>
                                        </td>
                                        <td>
                                            <?php echo $form->input('accounts_type', array('options' => array('0' => __('ani', true), '1' => __('dnis', true)),
                                                'name' => "accounts[$direction_key][type]", 'selected' => '','id' => false,
                                                'label' => false, 'class' => 'select width120 accounts_type', 'div' => false,
                                                'type' => 'select','selected' => $direction['ResourceDirectionTemplate']['type']));  ?>
                                        </td>
                                        <td>
                                            <?php echo $form->input('accounts_dnis',array('name' => "accounts[$direction_key][dnis]",'type' => 'text',
                                                'id' =>false,'value' => $direction['ResourceDirectionTemplate']['dnis'],
                                                'label' => false, 'class' => 'select accounts_dnis validate[custom[code]]', 'div' => false
                                            )); ?>
                                        </td>
                                        <td>
                                            <?php
                                            $t = array('1' => __('AddPrefix', true), '3' => __('DelPrefix', true), '2' => __('Addsuffix', true), '4' => __('Delsuffix', true));
                                            echo $form->input('accounts_action', array('options' => $t, 'name' => "accounts[$direction_key][action]", 'selected' => '',
                                                'id' => false,
                                                'label' => false, 'class' => 'select width120 accounts_action', 'div' => false,
                                                'selected' => $direction['ResourceDirectionTemplate']['action'],'type' => 'select')); ?>
                                        </td>
                                        <td>
                                            <?php if($direction['ResourceDirectionTemplate']['action'] == 3 || $direction['ResourceDirectionTemplate']['action'] == 4){
                                                $digit_item = '';
                                                $deldigits = $direction['ResourceDirectionTemplate']['digits'];
                                            }else{
                                                $digit_item = $direction['ResourceDirectionTemplate']['digits'];
                                                $deldigits = '';
                                            } ?>
                                            <?php echo $form->input('accounts_digits',array('name' => "accounts[$direction_key][digits]",
                                                'type' => 'text','label' => false, 'class' => 'select accounts_digits validate[custom[code]]',
                                                'value' => $digit_item,
                                                'div' => false,'id' => false)); ?>
                                        </td>
                                        <td>
                                            <?php echo $form->input('accounts_deldigits', Array('name' => "accounts[$direction_key][deldigits]", 'disabled' => 'disabled',
                                                'options' => Array('' => '', 1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5, 6 => 6, 7 => 7, 8 => 8, 9 => 9, 10 => 10, 11 => 11, 12 => 12, 13 => 13, 14 => 14, 15 => 15, 16 => 16, 17 => 17, 18 => 18, 19 => 19, 20 => 20),
                                                'label' => false, 'class' => 'select width120 accounts_deldigits', 'div' => false,'id' =>false,'selected' => $deldigits
                                            )); ?>
                                        </td>
                                        <td>
                                            <?php
                                            $t = array('0' => 'all', '1' => '>', '2' => '=', '3' => '<');
                                            echo $form->input('accounts_number_type', array('options' => $t, 'name' => "accounts[$direction_key][number_type]",
                                                'label' => false, 'class' => 'accounts_number_type width120', 'div' => false, 'type' => 'select',
                                                'id' =>false,'selected' =>$direction['ResourceDirectionTemplate']['number_type']));
                                            ?>
                                        </td>
                                        <td>
                                            <?php echo $form->input('accounts_number_length',array('name' => "accounts[$direction_key][number_length]",
                                                'type' => 'text','label' => false, 'class' => 'accounts_number_length select width120 validate[required,custom[integer]]', 'div' => false,
                                                'disabled' =>'disabled','id' => false,'value' =>$direction['ResourceDirectionTemplate']['number_length'])); ?>
                                        </td>
                                        <td>
                                            <a href="javascript:void(0)" title="Up" class="sortup  icon-long-arrow-up">
                                                <i></i>
                                            </a>
                                            <a href="javascript:void(0)" title="Down" class="sortdown icon-long-arrow-down">
                                                <i></i>
                                            </a>
                                            <a rel="delete" href="javascript:void(0)" onclick="$(this).closest('tr').remove();">
                                                <i class="icon-remove"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                <tr class="action_tr">
                                    <td>
                                        <?php echo $form->input('accounts_time_profile_id', array('options' => $timepro, 'name' => "accounts[%n][time_profile_id]", 'selected' => '',
                                            'label' => false, 'class' => 'select width120 accounts_time_profile_id validate[required]', 'div' => false, 'type' => 'select','id' => false));  ?>
                                    </td>
                                    <td>
                                        <?php echo $form->input('accounts_type', array('options' => array('0' => __('ani', true), '1' => __('dnis', true)),
                                            'name' => "accounts[%n][type]", 'selected' => '','id' => false,
                                            'label' => false, 'class' => 'select width120 accounts_type', 'div' => false, 'type' => 'select'));  ?>
                                    </td>
                                    <td>
                                        <?php echo $form->input('accounts_dnis',array('name' => 'accounts[%n][dnis]','type' => 'text',
                                            'id' =>false,
                                            'label' => false, 'class' => 'select accounts_dnis validate[custom[code]]', 'div' => false)); ?>
                                    </td>
                                    <td>
                                        <?php
                                        $t = array('1' => __('AddPrefix', true), '3' => __('DelPrefix', true), '2' => __('Addsuffix', true), '4' => __('Delsuffix', true));
                                        echo $form->input('accounts_action', array('options' => $t, 'name' => "accounts[%n][action]", 'selected' => '',
                                            'onchange' => 'PrefixChange(this)','id' => false,
                                            'label' => false, 'class' => 'select width120 accounts_action', 'div' => false, 'type' => 'select')); ?>
                                    </td>
                                    <td>
                                        <?php echo $form->input('accounts_digits',array('name' => 'accounts[%n][digits]',
                                            'type' => 'text','label' => false, 'class' => 'select accounts_digits validate[custom[code]]', 'div' => false,'id' => false)); ?>
                                    </td>
                                    <td>
                                        <?php echo $form->input('accounts_deldigits', Array('name' => "accounts[%n][deldigits]", 'disabled' => 'disabled',
                                            'options' => Array('' => '', 1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5, 6 => 6, 7 => 7, 8 => 8, 9 => 9, 10 => 10, 11 => 11, 12 => 12, 13 => 13, 14 => 14, 15 => 15, 16 => 16, 17 => 17, 18 => 18, 19 => 19, 20 => 20),
                                            'label' => false, 'class' => 'select width120 accounts_deldigits', 'div' => false,'id' =>false
                                        )); ?>
                                    </td>
                                    <td>
                                        <?php
                                        $t = array('0' => 'all', '1' => '>', '2' => '=', '3' => '<');
                                        echo $form->input('accounts_number_type', array('options' => $t, 'name' => "accounts[%n][number_type]",
                                            'label' => false, 'class' => 'accounts_number_type width120', 'div' => false, 'type' => 'select','id' =>false));
                                        ?>
                                    </td>
                                    <td>
                                        <?php echo $form->input('accounts_number_length',array('name' => 'accounts[%n][number_length]',
                                            'type' => 'text','label' => false, 'class' => 'accounts_number_length select width120 validate[required,custom[integer]]', 'div' => false,
                                            'disabled' =>'disabled','id' => false)); ?>
                                    </td>
                                    <td>
                                        <a href="javascript:void(0)" title="Up" class="sortup  icon-long-arrow-up">
                                            <i></i>
                                        </a>
                                        <a href="javascript:void(0)" title="Down" class="sortdown icon-long-arrow-down">
                                            <i></i>
                                        </a>
                                        <a rel="delete" href="javascript:void(0)" onclick="$(this).closest('tr').remove();">
                                            <i class="icon-remove"></i>
                                        </a>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                     </div>
                </div>
                <div class="tab-pane" id="rule">
                     <a href="javascript:void(0)" id="add_fail_over_rule" class="btn btn-primary btn-icon glyphicons circle_plus">
                        <?php echo __('add', true); ?>
                        <i></i>
                    </a>

                    <div class="overflow_x separator">
                        <table class="footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary"   id="list_table" >
                            <thead>
                            <tr>
                                <th><?php echo __('Route Type'); ?></th>
                                <th> <?php echo __('Code'); ?></th>
                                <th><?php echo __('Response'); ?></th>
                                <th><?php __('Action') ?></th>
                            </tr>
                            </thead>
                            <tbody id="fail_over_rule_tbody">
                            <?php foreach ($fail_over_rule_arr as $fail_over_rule_key => $fail_over_rule): ?>
                                <tr>
                                    <td>
                                        <?php
                                        if ($type)
                                            $t = array('1' => 'Fail to Next Host', '2' => 'Fail to Next Trunk', '3' => 'Stop');
                                        else
                                            $t = array('3' => 'Stop');
                                        echo $form->input('failOver_route_type', array('options' => $t, 'name' => "failOver[$fail_over_rule_key][route_type]", 'selected' => '',
                                            'label' => false, 'class' => 'select failOver_route_type',
                                            'selected' => $fail_over_rule['ResourceNextRouteRuleTemplate']['route_type'],
                                            'div' => false, 'type' => 'select','id' => false));  ?>
                                    </td>
                                    <td>
                                        <?php echo $form->input('failOver_reponse_code', array(
                                            'name' => "failOver[$fail_over_rule_key][reponse_code]",'id' => false,
                                            'label' => false, 'class' => 'select width120 failOver_reponse_code validate[required,custom[integer]]', 'div' => false,
                                            'type' => 'text','value' => $fail_over_rule['ResourceNextRouteRuleTemplate']['reponse_code']));  ?>
                                    </td>
                                    <td class="failOver_return_td">
                                        <?php echo $form->input('failOver_return_code',array('name' => "failOver[$fail_over_rule_key][return_code]",'type' => 'text',
                                            'id' =>false,'value' => $fail_over_rule['ResourceNextRouteRuleTemplate']['return_code'],
                                            'label' => false, 'class' => 'select failOver_return_code', 'div' => false
                                        )); ?>
                                        <?php echo $form->input('failOver_return_string',array('name' => "failOver[$fail_over_rule_key][return_string]",'type' => 'text',
                                            'id' =>false,'value' => $fail_over_rule['ResourceNextRouteRuleTemplate']['return_string'],
                                            'label' => false, 'class' => 'select failOver_return_string', 'div' => false
                                        )); ?>
                                    </td>
                                    <td>
                                        <a href="javascript:void(0)" title="Up" class="sortup  icon-long-arrow-up">
                                            <i></i>
                                        </a>
                                        <a href="javascript:void(0)" title="Down" class="sortdown icon-long-arrow-down">
                                            <i></i>
                                        </a>
                                        <a rel="delete" href="javascript:void(0)" onclick="$(this).closest('tr').remove();">
                                            <i class="icon-remove"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            <tr class="fail_over_rule_tr">
                                <td>
                                    <?php
                                    if ($type)
                                        $t = array('1' => 'Fail to Next Host', '2' => 'Fail to Next Trunk', '3' => 'Stop');
                                    else
                                        $t = array('3' => 'Stop');
                                    echo $form->input('failOver_route_type', array('options' => $t, 'name' => "failOver[%n][route_type]", 'selected' => '',
                                        'label' => false, 'class' => 'select failOver_route_type',
                                        'div' => false, 'type' => 'select','id' => false));  ?>
                                </td>
                                <td>
                                    <?php echo $form->input('failOver_reponse_code', array(
                                        'name' => "failOver[%n][reponse_code]",'id' => false,
                                        'label' => false, 'class' => 'select width120 failOver_reponse_code validate[required,custom[integer]]', 'div' => false,
                                        'type' => 'text'));  ?>
                                </td>
                                <td class="failOver_return_td">
                                    <?php echo $form->input('failOver_return_code',array('name' => "failOver[%n][return_code]",'type' => 'text',
                                        'id' =>false,
                                        'label' => false, 'class' => 'select failOver_return_code', 'div' => false
                                    )); ?>
                                    <?php echo $form->input('failOver_return_string',array('name' => "failOver[%n][return_string]",'type' => 'text',
                                        'id' =>false,
                                        'label' => false, 'class' => 'select failOver_return_string', 'div' => false
                                    )); ?>
                                </td>
                                <td>
                                    <a rel="delete" href="javascript:void(0)" onclick="$(this).closest('tr').remove();">
                                        <i class="icon-remove"></i>
                                    </a>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="tab-pane" id="pass_trusk">
                    <div id="support_panel" style="text-align:center;">
                        <?php if (!$type): ?>
                            <?php
                            $options = array(
                                'Never',
                                'Pass_Through',
                                'Always',
                            );
                            ?>
                            <table class="form footable table table-striped dynamicTable tableTools table-bordered  table-white default footable-loaded">
                                <colgroup>
                                    <col width="37%">
                                    <col width="63%">
                                </colgroup>
                                <tr>
                                    <td class="align_right padding-r10">
                                        <label title="Remote-Party-ID"><?php __('RPID')?></label>
                                    </td>
                                    <td>
                                        <?php echo $form->input('rpid',array('label'=>false ,'div'=>false,'type'=>'select', 'options'=>$options,
                                            'selected' => isset($template_info['ResourceTemplate']['rpid']) ? $template_info['ResourceTemplate']['rpid'] : ''
                                        ));?>
                                    </td>
                                </tr>

                                <tr class="rpid_control">
                                    <td class="align_right padding-r10"><?php __('RPID Screen'); ?></td>
                                    <td>
                                        <?php echo $form->input('rpid_screen', array('options' => array(__('None', true), __('No', true), __('Yes', true), __('Proxy', true)),
                                            'label' => false, 'div' => false,
                                            'selected' => isset($template_info['ResourceTemplate']['rpid_screen']) ? $template_info['ResourceTemplate']['rpid_screen'] : 0
                                        )); ?>
                                    </td>
                                </tr>
                                <tr class="rpid_control">
                                    <td class="align_right padding-r10"><?php __('RPID Party'); ?></td>
                                    <td>
                                        <?php echo $form->input('rpid_party', array('options' => array(__('None', true), __('Calling', true), __('Called', true), __('Proxy', true)),
                                            'label' => false, 'div' => false,
                                            'selected' => isset($template_info['ResourceTemplate']['rpid_party']) ? $template_info['ResourceTemplate']['rpid_party'] : 0
                                        )); ?>
                                    </td>
                                </tr>
                                <tr class="rpid_control">
                                    <td class="align_right padding-r10"><?php __('RPID Id Type'); ?></td>
                                    <td>
                                        <?php echo $form->input('rpid_id_type', array('options' => array(__('None', true), __('Subscriber', true), __('User', true), __('Term', true), __('Proxy', true)),
                                            'label' => false, 'div' => false,
                                            'selected' => isset($template_info['ResourceTemplate']['rpid_id_type']) ? $template_info['ResourceTemplate']['rpid_id_type'] : 0
                                        )); ?>
                                    </td>
                                </tr>
                                <tr class="rpid_control">
                                    <td class="align_right padding-r10"><?php __('RPID Privacy'); ?></td>
                                    <td>
                                        <?php echo $form->input('rpid_privacy', array('options' => array(__('None', true), __('Full', true), __('Name', true), __('Url', true), __('OFF', true), __('Ipaddr', true), __('Proxy', true)),
                                            'label' => false, 'div' => false,
                                            'selected' => isset($template_info['ResourceTemplate']['rpid_privacy']) ? $template_info['ResourceTemplate']['rpid_privacy'] : 0
                                        )); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="align_right padding-r10">
                                        <label title="P-Asserted-Identity"><?php __('PAID')?></label>
                                    </td>
                                    <td>
                                        <?php echo $form->input('paid',array('label'=>false ,'div'=>false,'type'=>'select', 'options'=>$options,
                                            'selected' => isset($template_info['ResourceTemplate']['paid']) ? $template_info['ResourceTemplate']['paid'] : ''
                                        ));?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="align_right padding-r10">
                                        <label title="isup-oli"><?php __('OLI')?></label>
                                    </td>
                                    <td>
                                        <?php echo $form->input('oli',array('label'=>false ,'div'=>false,'type'=>'select', 'options'=>$options,
                                            'selected' => isset($template_info['ResourceTemplate']['oli']) ? $template_info['ResourceTemplate']['oli'] : ''
                                        ));?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="align_right padding-r10">
                                        <label title="P-Charge-Info"><?php __('PCI')?></label>
                                    </td>
                                    <td>
                                        <?php echo $form->input('pci',array('label'=>false ,'div'=>false,'type'=>'select', 'options'=>$options,
                                            'selected' => isset($template_info['ResourceTemplate']['pci']) ? $template_info['ResourceTemplate']['pci'] : ''
                                        ));?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="align_right padding-r10">
                                        <label title="Privacy"><?php __('PRIV')?></label>
                                    </td>
                                    <td>
                                        <?php echo $form->input('priv',array('label'=>false ,'div'=>false,'type'=>'select', 'options'=>$options,
                                            'selected' => isset($template_info['ResourceTemplate']['priv']) ? $template_info['ResourceTemplate']['priv'] : ''
                                        ));?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="align_right padding-r10">
                                        <label title="Diversion"><?php __('DIV')?></label>
                                    </td>
                                    <td>
                                        <?php echo $form->input('div',array('label'=>false ,'div'=>false,'type'=>'select', 'options'=>$options,
                                            'selected' => isset($template_info['ResourceTemplate']['div']) ? $template_info['ResourceTemplate']['div'] : ''
                                        ));?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="align_right padding-r10"><?php __('Display Name'); ?></td>
                                    <td>
                                        <?php echo $form->input('display_name', array('options' => array('False', 'True'), 'label' => false, 'div' => false,
                                            'selected' => isset($template_info['ResourceTemplate']['display_name']) ? $template_info['ResourceTemplate']['display_name'] : ''
                                        )); ?>
                                    </td>
                                </tr>
                            </table>
                            <script type="text/javascript">

                                $(function(){
                                    var $rpid_control = $('.rpid_control');

                                    $('#paid').change(function() {
                                        var $this = $(this);
                                        var val = $this.val();
                                        if (val == 0)
                                            $rpid_control.hide();
                                        else
                                            $rpid_control.show();
                                    }).trigger('change');
                                });

                            </script>

                        <?php else: ?>

                            <table class="form footable table table-striped dynamicTable tableTools table-bordered  table-white default footable-loaded">
                                <colgroup>
                                    <col width="37%">
                                    <col width="63%">
                                </colgroup>
                                <tr>
                                    <td class="align_right padding-r10">
                                        <label title="P-Asserted-Identity"><?php __('Enable PAID')?></label>
                                    </td>
                                    <td>
                                        <?php echo $form->input('paid',array('label'=>false ,'div'=>false,'type'=>'checkbox',
                                            'checked'=> isset($template_info['ResourceTemplate']['paid']) ? (bool)$template_info['ResourceTemplate']['paid'] : false
                                        ));?>
                                    </td>
                                </tr>


                                <tr class="rpid_control">
                                    <td class="align_right padding-r10"><?php __('RPID Screen'); ?></td>
                                    <td>
                                        <?php echo $form->input('rpid_screen', array('options' => array(__('None', true), __('No', true), __('Yes', true), __('Proxy', true)),
                                            'label' => false, 'div' => false,
                                            'selected' => isset($template_info['ResourceTemplate']['rpid_screen']) ? $template_info['ResourceTemplate']['rpid_screen'] : 0
                                        )); ?>
                                    </td>
                                </tr>
                                <tr class="rpid_control">
                                    <td class="align_right padding-r10"><?php __('RPID Party'); ?></td>
                                    <td>
                                        <?php echo $form->input('rpid_party', array('options' => array(__('None', true), __('Calling', true), __('Called', true), __('Proxy', true)),
                                            'label' => false, 'div' => false,
                                            'selected' => isset($template_info['ResourceTemplate']['rpid_party']) ? $template_info['ResourceTemplate']['rpid_party'] : 0
                                        )); ?>
                                    </td>
                                </tr>
                                <tr class="rpid_control">
                                    <td class="align_right padding-r10"><?php __('RPID Id Type'); ?></td>
                                    <td>
                                        <?php echo $form->input('rpid_id_type', array('options' => array(__('None', true), __('Subscriber', true), __('User', true), __('Term', true), __('Proxy', true)),
                                            'label' => false, 'div' => false,
                                            'selected' => isset($template_info['ResourceTemplate']['rpid_id_type']) ? $template_info['ResourceTemplate']['rpid_id_type'] : 0
                                        )); ?>
                                    </td>
                                </tr>
                                <tr class="rpid_control">
                                    <td class="align_right padding-r10"><?php __('RPID Privacy'); ?></td>
                                    <td>
                                        <?php echo $form->input('rpid_privacy', array('options' => array(__('None', true), __('Full', true), __('Name', true), __('Url', true), __('OFF', true), __('Ipaddr', true), __('Proxy', true)),
                                            'label' => false, 'div' => false,
                                            'selected' => isset($template_info['ResourceTemplate']['rpid_privacy']) ? $template_info['ResourceTemplate']['rpid_privacy'] : 0
                                        )); ?>
                                    </td>
                                </tr>

                                <tr>
                                    <td class="align_right padding-r10">
                                        <label title="isup-oli"><?php __('Enable OLI')?></label>
                                    </td>
                                    <td>
                                        <?php echo $form->input('oli',array('label'=>false ,'div'=>false,'type'=>'checkbox',
                                            'checked'=> isset($template_info['ResourceTemplate']['oli']) ? (bool)$template_info['ResourceTemplate']['oli'] : false
                                        ));?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="align_right padding-r10">
                                        <label title="P-Charge-Info"><?php __('Enable PCI')?></label>
                                    </td>
                                    <td>
                                        <?php echo $form->input('pci',array('label'=>false ,'div'=>false,'type'=>'checkbox',
                                            'checked'=> isset($template_info['ResourceTemplate']['pci']) ? (bool)$template_info['ResourceTemplate']['pci'] : false
                                        ));?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="align_right padding-r10">
                                        <label title="Privacy"><?php __('Enable PRIV')?></label>
                                    </td>
                                    <td>
                                        <?php echo $form->input('priv',array('label'=>false ,'div'=>false,'type'=>'checkbox',
                                            'checked'=> isset($template_info['ResourceTemplate']['priv']) ? (bool)$template_info['ResourceTemplate']['priv'] : false
                                        ));?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="align_right padding-r10">
                                        <label title="Diversion"><?php __('Enable DIV')?></label>
                                    </td>
                                    <td>
                                        <?php echo $form->input('div',array('label'=>false ,'div'=>false,'type'=>'checkbox',
                                            'checked'=> isset($template_info['ResourceTemplate']['div']) ? (bool)$template_info['ResourceTemplate']['div'] : false
                                        ));?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="align_right padding-r10"><?php __('Display Name'); ?></td>
                                    <td>
                                        <?php echo $form->input('display_name', array('label'=>false ,'div'=>false,'type'=>'checkbox',
                                            'checked'=> isset($template_info['ResourceTemplate']['display_name']) ? (bool)$template_info['ResourceTemplate']['display_name'] : false
                                        )); ?>
                                    </td>
                                </tr>
                            </table>
                            <script type="text/javascript">
                                var $rpid_control = $('.rpid_control');
                                if ($('#paid').is(':checked') === false)
                                    $rpid_control.hide();
                                else
                                    $rpid_control.show();


                                $(function(){
                                    $('#paid').click(function() {
                                        var $this = $(this);
                                        var val = $this.is(':checked');
                                        if (val === false)
                                            $rpid_control.hide();
                                        else
                                            $rpid_control.show();
                                    });
                                });

                            </script>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="tab-pane active" id="billing">
                    <table class="form footable table table-striped dynamicTable tableTools table-bordered  table-white default footable-loaded">
                        <colgroup>
                            <col width="37%">
                            <col width="63%">
                        </colgroup>
                        <tr>
                            <td class="align_right padding-r10"><?php __('Rounding'); ?></td>
                            <td>
                                <?php echo $form->input('rate_rounding', array('options' => array(__('Up', true), __('Down', true)),
                                    'label' => false, 'div' => false,
                                    'selected' => isset($template_info['ResourceTemplate']['rate_rounding']) ? $template_info['ResourceTemplate']['rate_rounding'] : 0
                                )); ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="align_right padding-r10"><?php __('Rounding Decimal Places'); ?></td>
                            <td>
                                <?php echo $form->input('rate_decimal', array('class' => 'width220 validate[custom[onlyNumberSp]]',
                                    'label' => false, 'div' => false, 'type' => 'text', 'maxlength' => '5',
                                    'value' => isset($template_info['ResourceTemplate']['rate_decimal']) ? $template_info['ResourceTemplate']['rate_decimal'] : ''
                                )); ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="align_right padding-r10"><?php __('Rate Profile'); ?></td>
                            <td>
                                <?php
                                echo $form->input('rate_profile', array('options' => array(__('False', true), __('True', true)),
                                    'label' => false, 'class' => 'select', 'div' => false, 'type' => 'select',
                                    'selected' => isset($template_info['ResourceTemplate']['rate_profile']) ? $template_info['ResourceTemplate']['rate_profile'] : ''
                                ));
                                ?>
                            </td>
                        </tr>
                        <tr class="rate_profile_control">
                            <td class="align_right padding-r10"><?php __('USA'); ?></td>
                            <td>
                                <?php
                                echo $form->input('us_route', array('options' => array('Other', 'Intra', 'Inter', 'Highest'),
                                    'label' => false, 'class' => 'select', 'div' => false, 'type' => 'select',
                                    'selected' => isset($template_info['ResourceTemplate']['us_route']) ? $template_info['ResourceTemplate']['us_route'] : ''
                                ));
                                ?>
                            </td>
                        </tr>
                        <tr class="rate_profile_control">
                            <td class="align_right padding-r10"><?php __('US Territories'); ?></td>
                            <td>
                                <?php
                                echo $form->input('us_other', array('options' => array('Other', 'Intra', 'Inter', 'Highest'),
                                    'label' => false, 'class' => 'select', 'div' => false, 'type' => 'select',
                                    'selected' => isset($template_info['ResourceTemplate']['us_other']) ? $template_info['ResourceTemplate']['us_other'] : ''
                                ));
                                ?>
                            </td>
                        </tr>
                        <tr class="rate_profile_control">
                            <td class="align_right padding-r10"><?php __('Canada'); ?></td>
                            <td>
                                <?php
                                echo $form->input('canada_route', array('options' => array('Other', 'Intra', 'Inter', 'Highest'),
                                    'label' => false, 'class' => 'select', 'div' => false, 'type' => 'select',
                                    'selected' => isset($template_info['ResourceTemplate']['canada_route']) ? $template_info['ResourceTemplate']['canada_route'] : ''
                                ));
                                ?>
                            </td>
                        </tr>
                        <tr class="rate_profile_control">
                            <td class="align_right padding-r10"><?php __('Non USA/Canada Territories'); ?></td>
                            <td>
                                <?php
                                echo $form->input('canada_other', array('options' => array('Other', 'Intra', 'Inter', 'Highest'),
                                    'label' => false, 'class' => 'select', 'div' => false, 'type' => 'select',
                                    'selected' => isset($template_info['ResourceTemplate']['canada_other']) ? $template_info['ResourceTemplate']['canada_other'] : ''
                                ));
                                ?>
                            </td>
                        </tr>
                        <tr class="rate_profile_control">
                            <td class="align_right padding-r10"><?php __('International'); ?></td>
                            <td>
                                <?php
                                echo $form->input('intl_route', array('options' => array('Other', 'Intra', 'Inter', 'Highest'),
                                    'label' => false, 'class' => 'select', 'div' => false, 'type' => 'select',
                                    'selected' => isset($template_info['ResourceTemplate']['intl_route']) ? $template_info['ResourceTemplate']['intl_route'] : ''
                                ));
                                ?>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="tab-pane active" id="replace_action">
                    <a href="javascript:void(0)" id="add_replaceAction" class="btn btn-primary btn-icon glyphicons circle_plus">
                        <?php echo __('add', true); ?>
                        <i></i>
                    </a>

                    <div class="overflow_x separator">
                        <table class="footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary"   id="list_table" >
                            <thead>
                            <tr>
                                <th rowspan="2"><?php __('Type')?></th>
                                <th rowspan="2"><?php __('Change to ANI')?></th>
                                <th colspan="3"><?php __('Match Criteria')?></th>
                                <th rowspan="2"><?php __('Change to DNIS')?></th>
                                <th colspan="3"><?php __('Match Criteria')?></th>
                                <th rowspan="2"><?php __('Action')?></th>
                            </tr>
                            <tr>
                                <th><?php __('ANI Prefix')?></th>
                                <th><?php __('ANI Min Length')?></th>
                                <th><?php __('ANI Max Length')?></th>
                                <th><?php __('DNIS Prefix')?></th>
                                <th><?php __('DNIS Min Length')?></th>
                                <th><?php __('DNIS Max Length')?></th>
                            </tr>
                            </thead>
                            <tbody id="replaceAction_tbody">
                            <?php foreach ($replace_action_arr as $replace_action_key => $replace_action): ?>
                                <tr>
                                    <td>
                                        <?php
                                        $type_arr = array('ANI','DNIS','Both');
                                        echo $form->input('replaceAction_type', array('options' => $type_arr, 'name' => "replaceAction[$replace_action_key][type]",
                                            'label' => false, 'class' => 'select replaceAction_type',
                                            'div' => false, 'type' => 'select','id' => false,
                                            'selected' => $replace_action['ResourceReplaceActionTemplate']['type']
                                        ));  ?>
                                    </td>
                                    <td>
                                        <?php echo $form->input('replaceAction_ani', array(
                                            'name' => "replaceAction[$replace_action_key][ani]",'id' => false, 'div' => false,
                                            'label' => false, 'class' => 'select width120 replaceAction_ani',
                                            'type' => 'text','value' => $replace_action['ResourceReplaceActionTemplate']['ani']));  ?>
                                    </td>
                                    <td>
                                        <?php echo $form->input('replaceAction_ani_prefix', array(
                                            'name' => "replaceAction[$replace_action_key][ani_prefix]",'id' => false, 'div' => false,
                                            'label' => false, 'class' => 'select width120 replaceAction_ani_prefix',
                                            'type' => 'text','value' => $replace_action['ResourceReplaceActionTemplate']['ani_prefix']));  ?>
                                    </td>
                                    <td>
                                        <?php echo $form->input('replaceAction_ani_min_length', array(
                                            'name' => "replaceAction[$replace_action_key][ani_min_length]",'id' => false, 'div' => false,
                                            'label' => false, 'class' => 'select width120 replaceAction_ani_min_length validate[custom[integer]]',
                                            'type' => 'text','value' => $replace_action['ResourceReplaceActionTemplate']['ani_min_length']));  ?>
                                    </td>
                                    <td>
                                        <?php echo $form->input('replaceAction_ani_max_length', array(
                                            'name' => "replaceAction[$replace_action_key][ani_max_length]",'id' => false, 'div' => false,
                                            'label' => false, 'class' => 'select width120 replaceAction_ani_max_length validate[custom[integer]]',
                                            'type' => 'text','value' => $replace_action['ResourceReplaceActionTemplate']['ani_max_length']));  ?>
                                    </td>


                                    <td>
                                        <?php echo $form->input('replaceAction_dnis', array(
                                            'name' => "replaceAction[$replace_action_key][dnis]",'id' => false, 'div' => false,
                                            'label' => false, 'class' => 'select width120 replaceAction_dnis',
                                            'type' => 'text','value' => $replace_action['ResourceReplaceActionTemplate']['dnis']));  ?>
                                    </td>
                                    <td>
                                        <?php echo $form->input('replaceAction_dnis_prefix', array(
                                            'name' => "replaceAction[$replace_action_key][dnis_prefix]",'id' => false, 'div' => false,
                                            'label' => false, 'class' => 'select width120 replaceAction_dnis_prefix',
                                            'type' => 'text','value' => $replace_action['ResourceReplaceActionTemplate']['dnis_prefix']));  ?>
                                    </td>
                                    <td>
                                        <?php echo $form->input('replaceAction_dnis_min_length', array(
                                            'name' => "replaceAction[$replace_action_key][dnis_min_length]",'id' => false, 'div' => false,
                                            'label' => false, 'class' => 'select width120 replaceAction_dnis_min_length validate[custom[integer]]',
                                            'type' => 'text','value' => $replace_action['ResourceReplaceActionTemplate']['dnis_min_length']));  ?>
                                    </td>
                                    <td>
                                        <?php echo $form->input('replaceAction_dnis_max_length', array(
                                            'name' => "replaceAction[$replace_action_key][dnis_max_length]",'id' => false, 'div' => false,
                                            'label' => false, 'class' => 'select width120 replaceAction_dnis_max_length validate[custom[integer]]',
                                            'type' => 'text','value' => $replace_action['ResourceReplaceActionTemplate']['dnis_max_length']));  ?>
                                    </td>
                                    <td>
                                        <a rel="delete" href="javascript:void(0)" onclick="$(this).closest('tr').remove();">
                                            <i class="icon-remove"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            <tr class="replaceAction_tr">
                                <td>
                                    <?php
                                    $type_arr = array('ANI','DNIS','Both');
                                    echo $form->input('replaceAction_type', array('onchange' => 'blockByChange(this)', 'options' => $type_arr, 'name' => "replaceAction[%n][type]",
                                        'label' => false, 'class' => 'select replaceAction_type',
                                        'div' => false, 'type' => 'select','id' => false));  ?>
                                </td>
                                <td>
                                    <?php echo $form->input('replaceAction_ani', array(
                                        'name' => "replaceAction[%n][ani]",'id' => false, 'div' => false,
                                        'label' => false, 'class' => 'select width120 replaceAction_ani',
                                        'type' => 'text'));  ?>
                                </td>
                                <td>
                                    <?php echo $form->input('replaceAction_ani_prefix', array(
                                        'name' => "replaceAction[%n][ani_prefix]",'id' => false, 'div' => false,
                                        'label' => false, 'class' => 'select width120 replaceAction_ani_prefix',
                                        'type' => 'text'));  ?>
                                </td>
                                <td>
                                    <?php echo $form->input('replaceAction_ani_min_length', array(
                                        'name' => "replaceAction[%n][ani_min_length]",'id' => false, 'div' => false,
                                        'label' => false, 'class' => 'select width120 replaceAction_ani_min_length validate[custom[integer]]',
                                        'type' => 'text'));  ?>
                                </td>
                                <td>
                                    <?php echo $form->input('replaceAction_ani_max_length', array(
                                        'name' => "replaceAction[%n][ani_max_length]",'id' => false, 'div' => false,
                                        'label' => false, 'class' => 'select width120 replaceAction_ani_max_length validate[custom[integer]]',
                                        'type' => 'text'));  ?>
                                </td>

                                <td>
                                    <?php echo $form->input('replaceAction_dnis', array(
                                        'name' => "replaceAction[%n][dnis]",'id' => false, 'div' => false,
                                        'label' => false, 'class' => 'select width120 replaceAction_dnis',
                                        'type' => 'text'));  ?>
                                </td>
                                <td>
                                    <?php echo $form->input('replaceAction_dnis_prefix', array(
                                        'name' => "replaceAction[%n][dnis_prefix]",'id' => false, 'div' => false,
                                        'label' => false, 'class' => 'select width120 replaceAction_dnis_prefix',
                                        'type' => 'text'));  ?>
                                </td>
                                <td>
                                    <?php echo $form->input('replaceAction_dnis_min_length', array(
                                        'name' => "replaceAction[%n][dnis_min_length]",'id' => false, 'div' => false,
                                        'label' => false, 'class' => 'select width120 replaceAction_dnis_min_length validate[custom[integer]]',
                                        'type' => 'text'));  ?>
                                </td>
                                <td>
                                    <?php echo $form->input('replaceAction_dnis_max_length', array(
                                        'name' => "replaceAction[%n][dnis_max_length]",'id' => false, 'div' => false,
                                        'label' => false, 'class' => 'select width120 replaceAction_dnis_max_length validate[custom[integer]]',
                                        'type' => 'text'));  ?>
                                </td>
                                <td>
                                    <a rel="delete" href="javascript:void(0)" onclick="$(this).closest('tr').remove();">
                                        <i class="icon-remove"></i>
                                    </a>
                                </td>
                            </tr>
                            </tbody>
                        </table>
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
        <div class="form-buttons center"  style=" margin: 0 0 50px 0;">
            <input class="btn btn-primary input in-submit" type="submit"  value="Submit">
            <input class="btn btn-primary input in-submit" type="reset"  value="Revert">
        </div>
     </form>
</div>
<script type="text/javascript" src="<?php echo $this->webroot ?>js/jquery.livequery.js"></script>
<script type="text/javascript" src="<?php echo $this->webroot ?>js/gateway.js"></script>

<script>

    function changeStatememntAni(state, parent) {
        var ani = $(parent).next().children();
        var ani_prefix = $(parent).next().next().children();
        var ani_min_length = $(parent).next().next().next().children();
        var ani_max_length = $(parent).next().next().next().next().children();
        if(state == false) {
            $(ani).removeProp('disabled');
            $(ani_prefix).removeProp('disabled');
            $(ani_min_length).removeProp('disabled');
            $(ani_max_length).removeProp('disabled');
        } else {
            $(ani).prop('disabled', true);
            $(ani_prefix).prop('disabled', true);
            $(ani_min_length).prop('disabled', true);
            $(ani_max_length).prop('disabled', true);
        }
    }

    function changeStatememntDnis(state, parent) {
        var dnis = $(parent).next().next().next().next().next().children();
        var dnis_prefix = $(parent).next().next().next().next().next().next().children();
        var dnis_min_length = $(parent).next().next().next().next().next().next().next().children();
        var dnis_max_length = $(parent).next().next().next().next().next().next().next().next().children();
        if(state == false) {
            $(dnis).removeProp('disabled');
            $(dnis_prefix).removeProp('disabled');
            $(dnis_min_length).removeProp('disabled');
            $(dnis_max_length).removeProp('disabled');
        } else {
            $(dnis).prop('disabled', true);
            $(dnis_prefix).prop('disabled', true);
            $(dnis_min_length).prop('disabled', true);
            $(dnis_max_length).prop('disabled', true);
        }
    }

    function blockByChange(el) {
        var selectedValue = $("select[name='replaceAction[0][type]']").val();
        var parent = $(el).parent();
        if(selectedValue == 0) {
            changeStatememntAni(false, parent);
            changeStatememntDnis(true, parent);
        } else if(selectedValue == 1) {
            changeStatememntAni(true, parent);
            changeStatememntDnis(false, parent);
        } else {
            changeStatememntAni(false, parent);
            changeStatememntDnis(false, parent);
        }
    }
</script>

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

            jQuery('#totalCall,#totalCPS').xkeyvalidate({type: 'Num'});
            jQuery('#alias').xkeyvalidate({type: 'strNum'});
            jQuery('#submit_form').click(function() {
                var pa = "/[^0-9A-Za-z-\_\s]+/";
                var re = true;
                if (jQuery('#alias').val() == '') {
                    jQuery(this).addClass('invalid');
                    jQuery(this).jGrowlError('Egress Name, is required!');
                    return false;

                } else if (/[^0-9A-Za-z-\_ \|\=\.\-\s]/.test(jQuery("#alias").val()) || jQuery("#alias").val().length > 100) {
                    jQuery(this).addClass('invalid');
                    jQuery(this).jGrowlError('Egress Name,allowed characters: a-z,A-Z,0-9,-,_,space, maximum of 100 characters in length!');
                    return false;

                }

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

                if (jQuery('#ring_timeout').val() == '' || jQuery('#ring_timeout').val() < 1 || jQuery('#ring_timeout').val() > 60) {
                    jQuery('#ring_timeout').addClass('invalid');
                    jGrowl_to_notyfy('Ring Timer cant not be greater than 60 or less than 1!', {theme: 'jmsg-error'});
                    return false;
                }


                return re;

            });

        }

    );

    $(function() {
        var did_billing_method_tr = $('#did_billing_method_tr');
        var did_billing_rule_tr = $('#did_billing_rule_tr');
        var did_rate_table_tr = $('#did_rate_table_tr');
        var did_amount_per_port_tr = $('#did_amount_per_port_tr');

        jQuery('#trunk_type2').change(function() {
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
                jQuery('#billing_method').change();
            }
        }).trigger('change');

        jQuery('#billing_method').change(function() {
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
            if(jQuery('#trunk_type2').val() == '1')
            {
                did_rate_table_tr.hide();
            }
        });

    });
</script>

<!--      action-->
<script type="text/javascript">

    function PrefixChange(obj)
    {
        var action_value = $(obj).val();
        if(action_value == 3 || action_value == 4){
            $(obj).closest('tr').find('.accounts_digits').eq(0).attr('disabled',true).val('');
            $(obj).closest('tr').find('.accounts_deldigits').eq(0).attr('disabled',false);
        }
        else{
            $(obj).closest('tr').find('.accounts_digits').eq(0).attr('disabled',false);
            $(obj).closest('tr').find('.accounts_deldigits').eq(0).attr('disabled',true);
        }
    }

    $(function(){

        $('.sortup').live('click',function() {
            var $tr = $(this).parent().parent();
            if ($tr.prevAll().length == 0) {
                showMessages_new("[{'field':'','code':'101','msg':'Sorry,could not  move! '}]");
            } else {
                $tr.insertBefore($tr.prev());
            }
        });

        $('.sortdown').live('click',function() {
            var $tr = $(this).parent().parent();
            if ($tr.nextAll().length == 0) {
                showMessages_new("[{'field':'','code':'101','msg':'Sorry,could not  move! '}]");
            } else {
                $tr.insertAfter($tr.next());
            }
        });

        var action_tr = $(".action_tr").eq(0).remove();
        $("#add_action").click(function(){
            var tr_size = $("#action_tbody > tr").size();
            var tmp_action_tr = action_tr.clone(true);
            var a = tmp_action_tr.html().replace(/%n/g, tr_size);
            tmp_action_tr.html(a);
            tmp_action_tr.clone(true).prependTo('#action_tbody');
        });
        $(".accounts_number_type").live('change',function(){
            var type_value = $(this).val();
            $(this).closest('tr').find('.accounts_number_length').eq(0).attr('disabled',false);
            if(type_value == 0)
                $(this).closest('tr').find('.accounts_number_length').eq(0).attr('disabled',true).val('');
        }).trigger('change');

        $(".accounts_action").live('change',function(){
            PrefixChange(this);
        }).trigger('change');
    });
</script>
<!--action end-->


<!-- fail over -->
<script type="text/javascript">
    $(function(){
        $(".failOver_route_type").live('change',function(){
            var $this = $(this);
            var this_val = $(this).val();
            $this.closest('tr').find('.failOver_return_td').children().hide();
            if(this_val == 3)
                $this.closest('tr').find('.failOver_return_td').children().show();
        }).trigger('change');
        var fail_over_rule_tr = $(".fail_over_rule_tr").eq(0).remove();
        $("#add_fail_over_rule").click(function(){
            var tr_size = $("#fail_over_rule_tbody > tr").size();
            var tmp_fail_over_rule_tr = fail_over_rule_tr.clone(true);
            var a = tmp_fail_over_rule_tr.html().replace(/%n/g, tr_size);
            tmp_fail_over_rule_tr.html(a);
            tmp_fail_over_rule_tr.clone(true).prependTo('#fail_over_rule_tbody');
        });
    });
</script>
<!-- fail over end -->


<!--billing-->
<script type="text/javascript">
    $(function() {
        var $rate_profile_control = $('.rate_profile_control');
        $('#rate_profile').change(function() {
            var $this = $(this);
            var val = $this.val();
            if (val == 0)
                $rate_profile_control.hide();
            else
                $rate_profile_control.show();
        }).trigger('change');
    });
</script>
<!--billing end-->

<!-- replace action -->
<script type="text/javascript">
    $(function(){

        var replaceAction_tr = $(".replaceAction_tr").eq(0).remove();
        $("#add_replaceAction").click(function(){
            var tr_size = $("#replaceAction_tbody > tr").size();
            var tmp_replaceAction_tr = replaceAction_tr.clone(true);
            var a = tmp_replaceAction_tr.html().replace(/%n/g, tr_size);
            tmp_replaceAction_tr.html(a);
            tmp_replaceAction_tr.clone(true).prependTo('#replaceAction_tbody');
            blockByChange($("select[name='replaceAction[0][type]']"));
        });
    });
</script>
<!-- replace action end -->
<script type="text/javascript">

    $(function(){
        $("#myForm").submit(function(){
            var flg = true;
            if(!$("#myForm").validationEngine('validate'))
            {
                $("div[data-collapse-closed=true]").each(function(i){
                    $(this).find(".collapse-toggle").eq(0).click();
                });
                return false;
            }

//            判断名字不重复
            var template_name = $("#name").val();
            var template_id = $("#template_id").val();
            $.ajax({
                'url': '<?php echo $this->webroot ?>template/judge_template_name_unique',
                'type': 'POST',
                'async': false,
                'dataType': 'json',
                'data': {'template_name': template_name,'template_id':template_id},
                'success': function (data) {
                    if(data == 1)
                    {
                        jGrowl_to_notyfy('<?php __('Template name'); ?>['+template_name+ ']<?php __('already exists'); ?>', {theme: 'jmsg-error'});
                        flg = false;
                    }
                }
            });

            if(!flg)
                return false;
            var failOver_reponse_code_arr = new Array();
            $(".failOver_reponse_code").each(function(i){
                var this_val = $(this).val();
                failOver_reponse_code_arr.push(this_val);
            });
            var failOver_reponse_code_unique_arr = $.uniqueArray(failOver_reponse_code_arr);
            if(failOver_reponse_code_arr.length != failOver_reponse_code_unique_arr.length)
            {
                if($("#fail_over_rule_widget").attr('data-collapse-closed') =='true')
                    $("#fail_over_rule_widget").find('.collapse-toggle').click();
                jGrowl_to_notyfy('<?php __('Fail-over Rule Reponse Code  happen  repeat',true) ?>', {theme: 'jmsg-error'});
                return false;
            }

            $(".replaceAction_ani_min_length").each(function(i){
                var ani_min_val = $(this).val();
                var ani_max_val = $(this).closest('tr').find(".replaceAction_ani_max_length").eq(0).val();
                if(parseInt(ani_min_val) > parseInt(ani_max_val)){
                    if($("#replace_action_widget").attr('data-collapse-closed') =='true')
                        $("#replace_action_widget").find('.collapse-toggle').click();
                    jGrowl_to_notyfy('<?php printf(__('[%s]must Greater than[%s]', true), "ANI Max Length","ANI Min Length"); ?>', {theme: 'jmsg-error'});
                    flg = false;
                    return false;
                }
            });

            $(".replaceAction_dnis_min_length").each(function(i){
                var dnis_min_val = $(this).val();
                var dnis_max_val = $(this).closest('tr').find(".replaceAction_dnis_max_length").eq(0).val();
                if(parseInt(dnis_min_val) > parseInt(dnis_max_val)){
                    if($("#replace_action_widget").attr('data-collapse-closed') =='true')
                        $("#replace_action_widget").find('.collapse-toggle').click();
                    jGrowl_to_notyfy('<?php printf(__('[%s]must Greater than[%s]', true), "DNIS Max Length","DNIS Min Length"); ?>', {theme: 'jmsg-error'});
                    flg = false;
                    return false;
                }
            });

            $("#select2 option").prop('selected', true);

            return flg;
        });
    });
</script>
