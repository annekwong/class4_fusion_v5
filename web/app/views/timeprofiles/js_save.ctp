<?php $form->create('Timeprofile') ?>
<script type="text/javascript">
    function timeChange() {
        var startTime = $("#TimeprofileStartTime").val();
        var endTime = $("#TimeprofileEndTime").val();
        var startTimeArray = startTime.split(':');
        var endTimeArray = endTime.split(':');
        var isContinue = true;

        if(startTimeArray[0] > endTimeArray[0]) {
            isContinue = false;
        } else if(startTimeArray[0] == endTimeArray[0]) {
            if(startTimeArray[1] > endTimeArray[1]) {
                isContinue = false;
            }
        }

        if(isContinue == false) {
            $('#TimeprofileStartTime').val('00:00:00');
            $('#TimeprofileEndTime').val('23:59:59');
            jQuery.jGrowl("End time should be greater than start time!",{theme: "jmsg-error"});
        }
    }
</script>
<table>
    <tr>
<!--		<td>
            </td>-->
        <td><?php echo $xform->input('name', Array('maxLength' => 256, 'class' => 'input-small')) ?></td>
        <td>
            <?php echo $xform->input('type', Array('onchange' => 'typechange(this)', 'options' => Array('0' => __('all_time', true), '1' => __('weekly', true), '2' => __('daily', true)), 'class' => 'input-small')) ?>
            <script type="text/javascript">
                function typechange(obj) {
                    if (jQuery(obj).val() == 0) {
                        jQuery('#TimeprofileStartWeek,#TimeprofileEndWeek,#TimeprofileStartTime,#TimeprofileEndTime').hide();
                        jQuery("#TimeprofileTimeZone").hide();
                    }
                    if (jQuery(obj).val() == 2) {
                        jQuery('#TimeprofileStartTime,#TimeprofileEndTime,#TimeprofileTimeZone').show();
                        jQuery('#TimeprofileStartWeek,#TimeprofileEndWeek').hide().val('');
                        if($('#TimeprofileStartTime').val() == '')
                            $('#TimeprofileStartTime').val('00:00:00');
                        if($('#TimeprofileEndTime').val() == '')
                            $('#TimeprofileEndTime').val('23:59:59');
                    }
                    if (jQuery(obj).val() == 1) {
                        jQuery('#TimeprofileStartWeek,#TimeprofileEndWeek,#TimeprofileStartTime,#TimeprofileEndTime,#TimeprofileTimeZone').show();
                        if($('#TimeprofileStartTime').val() == '')
                            $('#TimeprofileStartTime').val('00:00:00');
                        if($('#TimeprofileEndTime').val() == '')
                            $('#TimeprofileEndTime').val('23:59:59');
                    }
                }
            </script>
        </td>
        <td>
            <?php echo $xform->input('start_week', Array('options' => Array('1' => __('monday', true), '2' => __('tuesday', true), '3' => __('Wednesday', true), '4' => __('Thursday', true), '5' => __('Friday', true), '6' => __('Saturday', true),'7' => __('sunday', true)), 'class' => 'input-small')) ?>
        </td>
        <td>
            <?php echo $xform->input('end_week', Array('options' => Array('1' => __('monday', true), '2' => __('tuesday', true), '3' => __('Wednesday', true), '4' => __('Thursday', true), '5' => __('Friday', true), '6' => __('Saturday', true),'7' => __('sunday', true)), 'class' => 'input-small')) ?>
        </td>
        <td>
            <?php echo $xform->input('start_time', Array('type' => 'text', 'class' => 'Wdate', 'onfocus' => "WdatePicker({onpicking:timeChange(), dateFmt:'HH:mm:00'});", 'realvalue' => '00:00:00', 'class' => 'input-small')) ?>
        </td>
        <td>
            <?php echo $xform->input('end_time', Array('type' => 'text', 'class' => 'Wdate', 'onfocus' => "WdatePicker({onpicking:timeChange(), dateFmt:'HH:mm:59'});", 'realvalue' => '00:00:00', 'class' => 'input-small')) ?>
        </td>
        <!--td>

            <?php
            $oldOptions = Array('-1200' => 'GMT -12:00', '-1100' => 'GMT -11:00',
                '-1000' => 'GMT -10:00', '-0900' => 'GMT -09:00',
                '-0800' => 'GMT -08:00', '-0700' => 'GMT -07:00', '-0600' => 'GMT -06:00',
                '-0500' => 'GMT -05:00', '-0400' => 'GMT -04:00', '-0300' => 'GMT -03:00',
                '-0200' => 'GMT -02:00', '-0100' => 'GMT -01:00', '+0000' => 'GMT +00:00',
                '+0100' => 'GMT +01:00', '+0200' => 'GMT +02:00', '+0300' => 'GMT +03:00',
                '+0400' => 'GMT +04:00', '+0500' => 'GMT +05:00', '+0600' => 'GMT +06:00',
                '+0700' => 'GMT +07:00', '+0800' => 'GMT +08:00', '+0900' => 'GMT +09:00',
                '+1000' => 'GMT +10:00', '+1100' => 'GMT +11:00', '+1200' => 'GMT +12:00',
            );
            $newOptions = Array('+0000' => 'GMT +00:00');
            echo $xform->input('time_zone', Array('readonly' => 'readonly', 'options' => $newOptions, 'class' => 'input-small'))
            ?>
        </td-->
        <td>
            <a id="save" href="#" title="Save">
                <i class="icon-save"></i>
            </a>
            <a id="delete" href="#" title="Deleted">
                <i class="icon-remove"></i>
            </a>	
        </td>
    </tr>
</table>
<?php echo $form->end() ?>

<script type="text/javascript">
    $(function() {
        $("#TimeprofileType").live('change', function() {
            $('#TimeprofileTimeZone').val('<?php echo $tz; ?>');
        });
    });
</script>