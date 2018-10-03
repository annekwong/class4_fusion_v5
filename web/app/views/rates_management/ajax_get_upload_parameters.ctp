<input type="hidden" name="data[id]" value="<?php $appCommon->_isset($data['RateManagementOption']['id']); ?>" />
<table class="table table-bordered">
    <col width="40%">
    <col width="60%">
    <tr>
        <td class="align_right padding-r10"><?php __('For rate record with the same code and effective date is found') ?></td>
        <td>
            <input type="radio" name="data[dup_method]" class="dup_method" value="1" <?php if($appCommon->_isset($data['RateManagementOption']['dup_method'],true) == 1): ?>checked="checked"<?php endif; ?> /> <?php __('Delete Existing Records')?>
            <br />
            <input type="radio" name="data[dup_method]" class="dup_method" value="2" <?php if($appCommon->_isset($data['RateManagementOption']['dup_method'],true) == 2): ?>checked="checked"<?php endif; ?> /> <?php __('End-Date Existing Records')?>
            <br />
            <input type="radio" name="data[dup_method]" class="dup_method" value="0" <?php if($appCommon->_isset($data['RateManagementOption']['dup_method'],true) == 0): ?>checked="checked"<?php endif; ?> /> <?php __('End-Date All Records')?>
        </td>
    </tr>
    <tr id="end_date_time_exists" class="hide">
        <td class="align_right padding-r10"><?php __('End Date Time')?></td>
        <td>
            <input class="in-text validate[required]" type="text" id="end_date" name="data[dup_end_date]" value="" onfocus="WdatePicker({dateFmt: 'yyyy-MM-dd HH:mm:ss'});" />
            <select name="data[dup_end_date_tz]" class="input in-select" style="width: 110px;">
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
            <input class="in-text validate[required]" type="text" id="end_date_all" name="data[dup_end_date_all]" value="<?php echo date("Y-m-d 23:59:59"); ?>" onfocus="WdatePicker({dateFmt: 'yyyy-MM-dd HH:mm:ss'});" />
            <select name="data[dup_end_date_all_tz]" class="input in-select" style="width: 110px;">
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
    <tr id="start_from_line" <?php if(!$appCommon->_isset($data['RateManagementOption']['with_header'],true)): ?>class="hide" <?php endif; ?>>
        <td class="align_right padding-r10"><?php __('Starting From Line') ?></td>
        <td>
            <input type="text" name="data[start_from_line]" class="validate[required,custom[integer]] width220" value="<?php $appCommon->_isset($data['RateManagementOption']['start_from_line']) ?>" />
        </td>
    </tr>
    <tr>
        <td class="align_right padding-r10"><?php __('Code Name Matching') ?></td>
        <td>
            <select name="data[code_name_match]" style="width: auto;" >
                <option value="1" <?php if($appCommon->_isset($data['RateManagementOption']['code_name_match'],true) == 1): ?>selected="selected"<?php endif; ?> ><?php __('Re-populate Country and Code Name with Selected Code Deck')?></option>
                <option value="2" <?php if($appCommon->_isset($data['RateManagementOption']['code_name_match'],true) == 2): ?>selected="selected"<?php endif; ?>><?php __('Re-populate Country and Code Name with Selected Code Deck if not available')?></option>
            </select>
        </td>
    </tr>
    <tr class="no_template hide">
        <td class="align_right padding-r10">
            <label><?php __('Check Effective Date Criteria')?>:</label>
        </td>
        <td align="left" style="padding-left:10px;">
            <input type="checkbox" class="check_effective" name="check_effective" /><br />
        </td>
    </tr>

    <tr class="no_template check_effective_flg hide">
        <td class="align_right padding-r10" rowspan="2">
            <label><?php __('Minimum Effective Date Requirement for')?>:</label>
        </td>
        <td align="left" style="padding-left:10px;">
            <?php __('Rate Increase'); ?>:
            <input type="text" class="validate[required,custom[integer]] width15" maxlength="2" name="rate_increase_days" />
            <?php __('days'); ?>
        </td>
    </tr>
    <tr class="no_template check_effective_flg hide">
        <td align="left" style="padding-left:10px;">
            <?php __('New Code'); ?>:
            <input type="text" class="validate[required,custom[integer]] width15" maxlength="2" name="new_code_days" />
            <?php __('days'); ?>
        </td>
    </tr>

    <tr class="no_template check_effective_flg hide">
        <td class="align_right padding-r10">
            <!--                            <td class="align_right padding-r10" rowspan="2">-->
            <label><?php __('Action to take if requirement not match')?>:</label>
        </td>
        <td align="left" style="padding-left:10px;">
            <?php __('Reject Rate Upload'); ?>:
            <select name="reject_rate">
                <option value="0"><?php __('No'); ?></option>
                <option value='1'><?php __('Yes'); ?></option>
            </select>
            <input type="hidden" name="send_error_email_to" value="0"/>
        </td>
    </tr>
</table>
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

        $(".check_effective").click(function(){
            check_effective($(this));
        });
    });
    function check_effective(obj){
        var checked = obj.is(":checked");
        if(checked){
            $(".check_effective_flg").show();
        }else{
            $(".check_effective_flg").hide();
        }
    }

</script>