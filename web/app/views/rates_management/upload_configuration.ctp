<style type="text/css">
    .form_input {float:left;width:220px;}

    .container ul{
        padding-left:20px;
    }
    .container ul li {
        padding:3px;
    }
    select,input[type="text"]{margin: 5px 0;}
    .table-condensed{border-left: 1px solid #EBEBEB;border-bottom: 1px solid #EBEBEB;}
    .table-condensed td{border-right:1px solid #EBEBEB;}
    fieldset{border:1px solid #ebebeb;padding:10px;margin-bottom:15px}
</style>
<ul class="breadcrumb">
    <li><?php __('You are here'); ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Switch') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Rate Table', true); ?>[<?php echo $name; ?>]</li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Auto Rate Upload', true); ?></h4>
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
</div>
<div class="clearfix"></div>
<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-head">
            <?php echo $this->element('downloads/rate_tabs',array('action' => 'auto')) ?>
        </div>
        <div class="clearfix"></div>
        <div class="widget-body">
            <form id="myForm" action="<?php echo $this->webroot; ?>rates_management/upload_configuration/<?php echo base64_encode($table_id); ?>" method="post">
                <input type="hidden" name="data[id]" value="<?php $appCommon->_isset($data['RateManagementOption']['id']) ?>" />
                <input type="hidden" name="data[rate_table_id]" value="<?php echo $table_id ?>" />
                <div class="widget" >
                    <div class="widget-head"><h4 class="heading"><?php __('Match Criteria for incoming rate update') ?></h4></div>
                    <div class="widget-body">
                        <table class="table table-bordered">
                            <col width="40%">
                            <col width="60%">
                            <tr>
                                <td class="align_right padding-r10"><?php __('Email Received From') ?></td>
                                <td>
                                    <input type="text" name="data[email_received_from]" class="validate[required,custom[email]] width220" value="<?php $appCommon->_isset($data['RateManagementOption']['email_received_from']) ?>" />
                                </td>
                            </tr>
                            <tr>
                                <td class="align_right padding-r10"><?php __('Subject Line Keyword') ?></td>
                                <td>
                                    <input type="text" name="data[subject_keyword]" class="width220" value="<?php $appCommon->_isset($data['RateManagementOption']['subject_keyword']) ?>" />
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="widget" >
                    <div class="widget-head"><h4 class="heading"><?php __('Processing Method'); ?></h4></div>
                    <div class="widget-body">
                        <table class="table table-bordered">
                            <col width="40%">
                            <col width="60%">
                            <tr>
                                <td class="align_right padding-r10"><?php __('Filename Keyword') ?></td>
                                <td>
                                    <input type="text" name="data[filename_keyword]" class="width220" value="<?php $appCommon->_isset($data['RateManagementOption']['filename_keyword']) ?>" />
                                </td>
                            </tr>
                            <tr>
                                <td class="align_right padding-r10"><?php __('Starting From Line') ?></td>
                                <td>
                                    <input type="text" name="data[start_from_line]" class="validate[custom[integer]] width220" value="<?php $appCommon->_isset($data['RateManagementOption']['start_from_line']) ?>" />
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="widget" >
                    <div class="widget-head"><h4 class="heading"><?php __('Fields'); ?></h4></div>
                    <div class="widget-body">
                        <table class="table table-bordered">
                            <tr>
                                <td class="align_right padding-r10" style="width:40%">
                                    <select id="columns_select" multiple="multiple" style="width:200px;height:300px;">
                                        <?php foreach($unselected_headers as $unselected_header): ?>
                                            <?php foreach($unselected_header as $field_key => $field): ?>
                                            <option value="<?php echo $field_key ?>"><?php echo $field ?></option>
                                            <?php endforeach; ?>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                                <td style="width:80px;">
                                    <input type="button" class="input in-submit in-button btn" value="<?php __('Add')?>" onclick="DoAdd();" style="width: 70px;margin-left: 0px;">
                                    <br><br>
                                    <input type="button" class="input in-submit in-button btn" value="<?php __('Delete')?>" onclick="DoDel();" style="margin-left: 0px;">
                                </td>
                                <td style="width:200px;">
                                    <select id="columns" name="data[fields_default_arr][]" multiple="multiple" style="width:200px;height:300px;" class="validate[required]">
                                        <?php foreach($select_headers as $select_header): ?>
                                            <?php foreach($select_header as $field_key => $field): ?>
                                            <option value="<?php echo $field_key ?>"><?php echo $field; ?></option>
                                            <?php endforeach; ?>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                                <td>
                                    <input type="button" value="<?php __('up')?>" onclick="moveOption('select2','up');" style="width: 65px; margin-left: 0px;" class="input in-submit in-button btn">
                                    <br><br>
                                    <input type="button" value="<?php __('Down')?>" onclick="moveOption('select2','down');" style="margin-left: 0px;" class="input in-submit in-button btn">
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="widget" >
                    <div class="widget-head"><h4 class="heading"><?php __('Default Fields'); ?></h4></div>
                    <div class="widget-body">
                        <table class="table table-bordered">
                            <col width="40%">
                            <col width="60%">
                            <tr>
                                <td class="align_right padding-r10"><?php __('Effective Date Format') ?></td>
                                <td>
                                    <select name="data[effective_date_format]" >
                                        <?php foreach ($effective_date_formats as $effective_date_format): ?>
                                            <option value="<?php echo $effective_date_format; ?>" <?php if($appCommon->_isset($data['RateManagementOption']['effective_date_format'],true) == $effective_date_format): ?>selected="selected"<?php endif; ?>><?php echo $effective_date_format; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="align_right padding-r10"><?php __('Effective Date') ?></td>
                                <td>
                                    <input type="text" name="data[effective_date_default]" class="width220" onclick="WdatePicker({dateFmt: 'yyyy-MM-dd'})" value="<?php $appCommon->_isset($data['RateManagementOption']['effective_date_default']) ?>" />
                                </td>
                            </tr>
                            <tr>
                                <td class="align_right padding-r10"><?php __('End Date') ?></td>
                                <td>
                                    <input type="text" name="data[end_date_default]" class="width220" onclick="WdatePicker({dateFmt: 'yyyy-MM-dd'})" value="<?php $appCommon->_isset($data['RateManagementOption']['end_date_default']) ?>" />
                                </td>
                            </tr>
                            <tr>
                                <td class="align_right padding-r10"><?php __('Min Time') ?></td>
                                <td>
                                    <input type="text" name="data[min_time_default]" class="validate[custom[integer]] width220" value="<?php $appCommon->_isset($data['RateManagementOption']['min_time_default']) ?>" />
                                </td>
                            </tr>
                            <tr>
                                <td class="align_right padding-r10"><?php __('Interval') ?></td>
                                <td>
                                    <input type="text" name="data[interval_default]" class="validate[custom[integer]] width220" value="<?php $appCommon->_isset($data['RateManagementOption']['interval_default']) ?>" />
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <!--
                <div class="widget" >
                    <div class="widget-head"><h4 class="heading"><?php __('Rejection Rules'); ?></h4></div>
                    <div class="widget-body">
                        <table class="table table-bordered">
                            <col width="40%">
                            <col width="60%">
                            <tr>
                                <td class="align_right padding-r10">
                                    <select name="data[increase_handle_type]" >
                                        <option value="1" <?php if($appCommon->_isset($data['RateManagementOption']['increase_handle_type'],true) == 1): ?>selected="selected"<?php endif; ?>><?php __('Reject Specific Rate Record'); ?></option>
                                        <option value="2"<?php if($appCommon->_isset($data['RateManagementOption']['increase_handle_type'],true) == 2): ?>selected="selected"<?php endif; ?>><?php __('Entire Rate Notice'); ?></option>
                                    </select>
                                </td>
                                <td>
                                    <?php printf(__('if increase is effective within less than %s days', true), "<input type='text' name='data[increase_rule_time]' style='width:15px;' maxlength='2' class='validate[required,custom[integer]]' value='".$appCommon->_isset($data['RateManagementOption']['increase_rule_time'],true)."' />") ?>
                                </td>
                            </tr>
                            <tr>
                                <td class="align_right padding-r10">
                                    <select name="data[newcode_handle_type]" >
                                        <option value="1" <?php if($appCommon->_isset($data['RateManagementOption']['newcode_handle_type'],true) == 1): ?>selected="selected"<?php endif; ?>><?php __('Reject Specific Rate Record'); ?></option>
                                        <option value="2"<?php if($appCommon->_isset($data['RateManagementOption']['newcode_handle_type'],true) == 2): ?>selected="selected"<?php endif; ?>><?php __('Entire Rate Notice'); ?></option>
                                    </select>
                                </td>
                                <td>
                                    <?php printf(__('if new code is effective within less than %s days', true), "<input type='text' name='data[newcode_rule_time]' style='width:15px;' maxlength='2' class='validate[required,custom[integer]]' value='".$appCommon->_isset($data['RateManagementOption']['newcode_rule_time'],true)."' />") ?>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                -->
                <div class="widget" >
                    <div class="widget-head"><h4 class="heading"><?php __('Email Notification'); ?></h4></div>
                    <div class="widget-body">
                        <table class="table table-bordered">
                            <col width="40%">
                            <col width="60%">
                            <tr>
                                <td class="align_right padding-r10"><?php __('Send Success Notification to') ?></td>
                                <td>
                                    <?php echo $form->input('success_notification_to',array('type'=> 'radio','options' => $notify_arr,'label'=>false,'div'=>false,'value' =>$appCommon->_isset($data['RateManagementOption']['success_notification_to'],true))); ?>
                                </td>
                            </tr>
                            <tr>
                                <td class="align_right padding-r10"><?php __('Send Failure Notification to') ?></td>
                                <td>
                                    <?php echo $form->input('failure_notification_to',array('type'=> 'radio','options' => $notify_arr,'label'=>false,'div'=>false,'value' =>$appCommon->_isset($data['RateManagementOption']['failure_notification_to'],true))); ?>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="widget" >
                    <div class="widget-head"><h4 class="heading"><?php __('Other parameters'); ?></h4></div>
                    <div class="widget-body">
                        <table class="table table-bordered">
                            <col width="40%">
                            <col width="60%">
                            <tr>
                                <td class="align_right padding-r10"><?php __('For rate record with the same code and effective date is found') ?></td>
                                <td>
                                    <?php echo $form->input('dup_method',array('class'=>'dup_method','type'=> 'radio','options' => $same_code_effective_arr,'label'=>false,'div'=>false,'value' =>$appCommon->_isset($data['RateManagementOption']['dup_method'],true))); ?>
                                </td>
                            </tr>
                            <tr id="end_date_time_exists" class="hide">
                                <td class="align_right padding-r10"><?php __('End Date Time')?></td>
                                <td>
                                    <input class="in-text" type="text" id="end_date" name="data[dup_end_date]" value="" onfocus="WdatePicker({dateFmt: 'yyyy-MM-dd HH:mm:ss'});" />
                                    <select name="data[dup_end_date_tz]" class="input in-select">
                                        <option value="-1200">GMT -12:00</option>
                                        <option value="-1100">GMT -11:00</option>
                                        <option value="-1000">GMT -10:00</option>
                                        <option value="-0900">GMT -09:00</option>
                                        <option value="-0800">GMT -08:00</option>
                                        <option value="-0700">GMT -07:00</option>
                                        <option value="-0600">GMT -06:00</option>
                                        <option value="-0500">GMT -05:00</option>
                                        <option value="-0400">GMT -04:00</option>
                                        <option value="-0300">GMT -03:00</option>
                                        <option value="-0200">GMT -02:00</option>
                                        <option value="-0100">GMT -01:00</option>
                                        <option selected="selected" value="+0000">GMT +00:00</option>
                                        <option value="+0100">GMT +01:00</option>
                                        <option value="+0200">GMT +02:00</option>
                                        <option value="+0300">GMT +03:00</option>
                                        <option value="+0330">GMT +03:30</option>
                                        <option value="+0400">GMT +04:00</option>
                                        <option value="+0500">GMT +05:00</option>
                                        <option value="+0600">GMT +06:00</option>
                                        <option value="+0700">GMT +07:00</option>
                                        <option value="+0800">GMT +08:00</option>
                                        <option value="+0900">GMT +09:00</option>
                                        <option value="+1000">GMT +10:00</option>
                                        <option value="+1100">GMT +11:00</option>
                                        <option value="+1200">GMT +12:00</option>
                                    </select>
                                </td>
                            </tr>

                            <tr id="end_date_time_all" class="hide">
                                <td class="align_right padding-r10"><?php __('End Date Time')?></td>
                                <td>
                                    <input class="in-text" type="text" id="end_date_all" name="data[dup_end_date_all]" value="<?php echo date("Y-m-d 23:59:59"); ?>" onfocus="WdatePicker({dateFmt: 'yyyy-MM-dd HH:mm:ss'});" />
                                    <select name="data[dup_end_date_all_tz]" class="input in-select">
                                        <option value="-1200">GMT -12:00</option>
                                        <option value="-1100">GMT -11:00</option>
                                        <option value="-1000">GMT -10:00</option>
                                        <option value="-0900">GMT -09:00</option>
                                        <option value="-0800">GMT -08:00</option>
                                        <option value="-0700">GMT -07:00</option>
                                        <option value="-0600">GMT -06:00</option>
                                        <option value="-0500">GMT -05:00</option>
                                        <option value="-0400">GMT -04:00</option>
                                        <option value="-0300">GMT -03:00</option>
                                        <option value="-0200">GMT -02:00</option>
                                        <option value="-0100">GMT -01:00</option>
                                        <option selected="selected" value="+0000">GMT +00:00</option>
                                        <option value="+0100">GMT +01:00</option>
                                        <option value="+0200">GMT +02:00</option>
                                        <option value="+0300">GMT +03:00</option>
                                        <option value="+0330">GMT +03:30</option>
                                        <option value="+0400">GMT +04:00</option>
                                        <option value="+0500">GMT +05:00</option>
                                        <option value="+0600">GMT +06:00</option>
                                        <option value="+0700">GMT +07:00</option>
                                        <option value="+0800">GMT +08:00</option>
                                        <option value="+0900">GMT +09:00</option>
                                        <option value="+1000">GMT +10:00</option>
                                        <option value="+1100">GMT +11:00</option>
                                        <option value="+1200">GMT +12:00</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="align_right padding-r10"><?php __('File With Header') ?></td>
                                <td>
                                    <?php echo $form->input('with_header',array('type'=>'checkbox','label' => false,'div'=>false,'checked'=>$appCommon->_isset($data['RateManagementOption']['with_header'],true))); ?>
                                </td>
                            </tr>
                            <tr>
                                <td class="align_right padding-r10"><?php __('Code Name Matching') ?></td>
                                <td>
                                    <select name="data[code_name_match]"  >
                                        <option value="1" <?php if($appCommon->_isset($data['RateManagementOption']['code_name_match'],true) == 1): ?>selected="selected"<?php endif; ?> ><?php __('Re-populate Country and Code Name with Selected Code Deck')?></option>
                                        <option value="2" <?php if($appCommon->_isset($data['RateManagementOption']['code_name_match'],true) == 2): ?>selected="selected"<?php endif; ?>><?php __('Re-populate Country and Code Name with Selected Code Deck if not available')?></option>
                                    </select>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="row-fluid center">
                    <input class="input in-submit btn btn-primary" type="submit" value="<?php __('Submit') ?>">
                    <input class="input in-submit btn btn-default" type="reset" value="<?php __('Revert') ?>">
                </div>

            </form>
        </div>
    </div>
</div>
<script type="text/javascript" src="<?php echo $this->webroot ?>js/fields_sendrate.js"></script>
<script type="text/javascript">
    var $end_date_time_exists = $('#end_date_time_exists');
    var $end_date_time_all = $('#end_date_time_all');
    $(function(){
        var $dup_method = "<?php $appCommon->_isset($data['RateManagementOption']['dup_method']); ?>";
        if ($dup_method == 2)
        {
            $("#end_date_time_exists").show();
        }
        else if($dup_method == 0)
        {
            $("#end_date_time_all").show();
        }


        $("#myForm").submit(function(){
            $("#columns").children().attr('selected','selected');
        });


        $('.dup_method').change(function() {
            var method = $(this).val();
            if (method == '1') {
                $end_date_time_exists.hide();
                $end_date_time_all.hide();
            } else if (method == '2') {
                $end_date_time_exists.show();
                $end_date_time_all.hide();
            } else {
                $end_date_time_exists.hide();
                $end_date_time_all.show();
            }
        });
    });


</script>