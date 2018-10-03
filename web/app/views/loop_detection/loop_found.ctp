<style>
    #stats-period{display: inline-block}
    input[type="text"]{width: 220px;}
</style>
<?php echo $this->element('magic_css');?>
<ul class="breadcrumb">
    <li><?php __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Statistics') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Loop Found') ?></li>
</ul>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
    <a class="btn btn-icon glyphicons btn-inverse circle_arrow_left" onclick="history.go(-1);">
        <i></i>
        <?php __('Back') ?>
    </a>
</div>
<div class="clearfix"></div>
<div class="innerLR">
    <div class="widget widget-tabs widget-body-white">
        <div class="widget-head">
            <?php echo $this->element('loop_detection/tab', array('current_page' => 1)); ?>
        </div>
        <div class="widget-body">
            <div  id="refresh_div">
                <?php
                if (isset ( $exception_msg ) && $exception_msg) :	?>
                    <?php	echo $this->element ( 'common/exception_msg' );?>
                <?php endif;?>
                <?php if($show_nodata): ?>
                    <?php echo $this->element('report_db/real_period')?>
                <?php endif; ?>
                <?php echo $this->element('report_db/cdr_report/cdr_table')?>
            </div>
            <!--生成图片报表-->
            <?php //echo $this->element("report/image_report")?>
            <!--//生成图片报表-->
            <fieldset class="query-box" style=" clear:both;overflow:hidden;margin-top:10px;">
                <h4 class="heading glyphicons search" style="display: inline-block;"><i></i> Search</h4>
                <div class="pull-right" title="Advance">
                    <a id="advance_btn" class="btn" href="javascript:void(0)">
                        <i class="icon-long-arrow-down"></i>
                    </a>
                </div>
                <div class="clearfix"></div>
                <?php
                $url="/".$this->params['url']['url'];
                echo $form->create ('Cdr', array ('type'=>'get','url' => $url ,'id'=>'report_form', 'onsubmit'=>"if ($('#query-output').val() == 'web') loading();"));?>
                <table class="form" style="width: 100%">
                    <tbody>
                    <?php echo $this->element('report/form_period',array('group_time'=>false, 'gettype'=>'<select id="query-output" name="query[output]" class="input in-select"><option value="web">Web</option></select>'))?>
                    </tbody>
                </table>
                <div id="advance_panel" class="widget widget-heading-simple widget-body-gray">
                    <div class="separator"></div>
                    <div class="row">
                        <div class="span6 offset2">
                            <td class="align_right padding-r10"><?php __('Ingress'); ?></td>
                            <td><?php
                                echo $form->input('ingress_alias', array('options' => $ingress, 'label' => false, 'div' => false,
                                    'type' => 'select','class' => 'use-select2'));
                                ?><?php echo $this->element('search_report/ss_clear_input_select'); ?></td>
                            <td class="in-out_bound">&nbsp;</td>
                        </div>
                        <div class="span6">
                            <div align="left"><?php echo __('Show Fields', true); ?>:</div>
                            <?php
                            unset($cdr_field['callduration_in_ms']);
                            unset($cdr_field['egress_code_acd']);
                            unset($cdr_field['egress_code_asr']);
                            unset($cdr_field['lnp_dipping_cost']);
                            $cdr_field['origination_source_number'] = 'ORIG SRC Number';
                            $cdr_field['first_release_dialogue'] = 'ORIG/Term Release';
                            $cdr_field['orig_call_duration'] = 'ORIG Call Duration';
                            $cdr_field['orig_code'] = 'ORIG Code';
                            $cdr_field['orig_code_name'] = 'ORIG Code Name';
                            $cdr_field['orig_country'] = 'ORIG Country';
                            $cdr_field['orig_delay_second'] = 'ORIG Delay Second';
                            $cdr_field['origination_remote_payload_ip_address'] = 'ORIG Media IP ANI';
                            $cdr_field['origination_remote_payload_udp_address'] = 'ORIG Media Port ANI';
                            $cdr_field['origination_call_id'] = 'ORIG Call ID';
                            $cdr_field['origination_local_payload_ip_address'] = 'ORIG Local Payload IP';
                            $cdr_field['origination_local_payload_udp_address'] = 'ORIG Local Payload Port';
                            $cdr_field['origination_destination_host_name'] = 'ORIG Profile IP';
                            $cdr_field['origination_profile_port'] = 'ORIG Profile Port';
                            $cdr_field['binary_value_of_release_cause_from_protocol_stack'] = 'Response To Ingress';
                            $cdr_field['termination_codec_list'] = 'Term Codecs';
                            $cdr_field['termination_destination_number'] = 'Term DST Number';
                            $cdr_field['termination_destination_host_name'] = 'Term IP';
                            $cdr_field['termination_source_number'] = 'Term SRC Number';
                            $cdr_field['termination_remote_payload_ip_address'] = 'Term Media IP';
                            $cdr_field['termination_call_id'] = 'Term Call ID';
                            $cdr_field['termination_local_payload_ip_address'] = 'Term Local Payload IP';
                            $cdr_field['termination_local_payload_udp_address'] = 'Term Local Payload Port';
                            $cdr_field['termination_source_host_name'] = 'Term Profile IP';
                            $cdr_field['termination_profile_port'] = 'Term Profile Port';
                            $cdr_field['paid_user'] = 'Paid User';
                            $cdr_field['q850_cause_string'] = 'Q850 Cause';
                            $cdr_field['q850_cause'] = 'Q850 Cause Code';
                            $cdr_field['rpid_user'] = 'RPID User';
                            
                            echo $form->select('Cdr.field', $cdr_field, $show_field_array, array('id' => 'query-fields', 'style' => 'width:100%; height: 250px;', 'name' => 'query[fields]', 'type' => 'select', 'multiple' => true), false);
                            ?>
                        </div>
                    </div>

                </div>
            </fieldset>
            <?php echo $form->end();?>
        </div>
    </div>
</div>
<script>
    $(function(){

        $('#query-fields').live('click',function(){
            $.cookie('select_all_columns',1, { path: "/"});
        })


    })
</script>