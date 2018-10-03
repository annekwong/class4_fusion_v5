<table class="form table dynamicTable tableTools table-bordered  table-white">
    <tbody>
        <tr>
            <td  style="text-align: right;"><?php __('Monitor Data By')?>: </td>
            <td >
                <select name="AlertRules[monitor_by]" id="acd" class="step2_select">
                    <option value="0" <?php echo $post_data['monitor_by'] == '0' ? 'selected' : '' ?>><?php __('Trunk')?></option>
                    <option value="1" <?php echo $post_data['monitor_by'] == '1' ? 'selected' : '' ?>><?php __('Trunk And DNIS')?></option>
                    <option value="2" <?php echo $post_data['monitor_by'] == '2' ? 'selected' : '' ?>><?php __('Trunk And ANI')?></option>
                    <option value="3" <?php echo $post_data['monitor_by'] == '3' ? 'selected' : '' ?>><?php __('DNIS')?></option>
                    <option value="4" <?php echo $post_data['monitor_by'] == '4' ? 'selected' : '' ?>><?php __('ANI')?></option>
                    <option value="5" <?php echo $post_data['monitor_by'] == '5' ? 'selected' : '' ?>><?php __('Trunk And Destination')?></option>
                    <option value="6" <?php echo $post_data['monitor_by'] == '6' ? 'selected' : '' ?>><?php __('Trunk And Country')?></option>
                    <option value="7" <?php echo $post_data['monitor_by'] == '7' ? 'selected' : '' ?>><?php __('Trunk And Code')?></option>
                </select>
            </td>
        </tr>
        <tr>
            <td  style="text-align: right;"><?php __('ACD')?>: </td>
            <td >
                <select name="AlertRules[acd]" id="acd" class="step2_select">
                    <option value="1"<?php
                    if ($post_data['acd'] == 1)
                    {
                        ?> selected="selected" <?php } ?>><?php __('Ignore')?></option>
                    <option value=">"<?php
                    if ($post_data['acd'] == ">")
                    {
                        ?> selected="selected" <?php } ?>>></option>
                    <option value="<"<?php
                    if ($post_data['acd'] == "<")
                    {
                        ?> selected="selected" <?php } ?>><</option>
                    <option value="="<?php
                    if ($post_data['acd'] == "=")
                    {
                        ?> selected="selected" <?php } ?>>=</option>

                </select>
                <span id="acd_value" class="conditionValue <?php
                if ($post_data['acd'] == 1 || empty($post_data['acd']))
                {
                    echo "hidden";
                }
                ?>" >
                    <input type="text"  value="<?php echo $post_data['acd_value'] ?>"  class="validate[required,custom[number]]" name="AlertRules[acd_value]" /><b class="padding-lr-5">s</b>
                </span>
            </td>
        </tr>
        <tr>
            <td  style="text-align: right;"><?php __('ASR')?>: </td>
            <td >
                <select name="AlertRules[asr]" id="asr" class="step2_select">
                    <option value="1"<?php
                    if ($post_data['asr'] == 1)
                    {
                        ?> selected="selected" <?php } ?>><?php __('Ignore')?></option>
                    <option value=">"<?php
                    if ($post_data['asr'] == ">")
                    {
                        ?> selected="selected" <?php } ?>>></option>
                    <option value="<"<?php
                    if ($post_data['asr'] == "<")
                    {
                        ?> selected="selected" <?php } ?>><</option>
                    <option value="="<?php
                    if ($post_data['asr'] == "=")
                    {
                        ?> selected="selected" <?php } ?>>=</option>

                </select>
                <span id="asr_value" class="conditionValue <?php
                if ($post_data['asr'] == 1 || empty($post_data['asr']))
                {
                    echo "hidden";
                }
                ?>">
                    <input type="text" name="AlertRules[asr_value]"  value="<?php echo $post_data['asr_value'] ?>" class="validate[required,custom[number]]" /><b class="padding-lr-5">%</b>
                </span>
            </td>
        </tr>
        <tr>
            <td  style="text-align: right;"><?php __('SDP')?>: </td>
            <td >
                <select name="AlertRules[sdp_sign]" id="sdp_sign" >
                    <option value="0" <?php if ($post_data['sdp_sign'] == '0') {?> selected="selected" <?php } ?>>
                    <?php __('Ignore')?>
                    </option>
                    <option value="1"<?php
                    if ($post_data['sdp_sign'] == "1")
                    {
                        ?> selected="selected" <?php } ?>>=</option>
                    <option value="2"<?php
                    if ($post_data['sdp_sign'] == "2")
                    {
                        ?> selected="selected" <?php } ?>><</option>
                    <option value="3"<?php
                    if ($post_data['sdp_sign'] == "3")
                    {
                        ?> selected="selected" <?php } ?>>></option>

                </select>

                </span>
                <span id="sdp_value" class="conditionValue <?php
                if ($post_data['sdp_value'] == 0 || empty($post_data['sdp_value']))
                {
                    echo "hidden";
                }
                ?>">
                    <input type="text" name="AlertRules[sdp_value]"  value="<?php echo $post_data['sdp_value'] ?>" class="validate[required,custom[number]]" /><b class="padding-lr-5">%</b>
                </span>
                <span id="sdp_type" class="conditionValue <?php
                if ($post_data['sdp_value'] == 0 || empty($post_data['sdp_value']))
                {
                    echo "hidden";
                }
                ?>">
                <select name="AlertRules[sdp_type]">
                    <option value="0" <?php if($post_data["sdp_type"] == 0) echo 'selected="selected"'; ?>>None</option>
                    <option value="1" <?php if($post_data["sdp_type"] == 1) echo 'selected="selected"'; ?>>6sec</option>
                    <option value="2" <?php if($post_data["sdp_type"] == 2) echo 'selected="selected"'; ?>>12sec</option>
                    <option value="3" <?php if($post_data["sdp_type"] == 3) echo 'selected="selected"'; ?>>18sec</option>
                    <option value="4" <?php if($post_data["sdp_type"] == 4) echo 'selected="selected"'; ?>>24sec</option>
                    <option value="5" <?php if($post_data["sdp_type"] == 5) echo 'selected="selected"'; ?>>30sec</option>
                </select>
            </td>
        </tr>
        <!--tr>
            <td  style="text-align: right;"><?php __('ABR')?>: </td>
            <td >
                <select name="AlertRules[abr]" id="abr" class="step2_select">
                    <option value="1"<?php
                    if ($post_data['abr'] == 1)
                    {
                        ?> selected="selected" <?php } ?>><?php __('Ignore')?></option>
                    <option value=">"<?php
                    if ($post_data['abr'] == ">")
                    {
                        ?> selected="selected" <?php } ?>>></option>
                    <option value="<"<?php
                    if ($post_data['abr'] == "<")
                    {
                        ?> selected="selected" <?php } ?>><</option>
                    <option value="="<?php
                    if ($post_data['abr'] == "=")
                    {
                        ?> selected="selected" <?php } ?>>=</option>

                </select>
                <span id="abr_value" <?php
                if ($post_data['abr'] == 1 || empty($post_data['abr']))
                {
                    echo "class='hidden'";
                }
                ?>>
                    <input type="text" name="AlertRules[abr_value]"  value="<?php echo $post_data['abr_value'] ?>" class="validate[required,custom[number]]" />%
                </span>
            </td>
        </tr-->


        <tr>
            <td  style="text-align: right;"><?php __('PDD')?>: </td>
            <td >
                <select name="AlertRules[pdd]" id="pdd" class="step2_select">
                    <option value="1"<?php
                    if ($post_data['pdd'] == 1)
                    {
                        ?> selected="selected" <?php } ?>><?php __('Ignore')?></option>
                    <option value=">"<?php
                    if ($post_data['pdd'] == ">")
                    {
                        ?> selected="selected" <?php } ?>>></option>
                    <option value="<"<?php
                    if ($post_data['pdd'] == "<")
                    {
                        ?> selected="selected" <?php } ?>><</option>
                    <option value="="<?php
                    if ($post_data['pdd'] == "=")
                    {
                        ?> selected="selected" <?php } ?>>=</option>

                </select>
                <span id="pdd_value" class="conditionValue <?php
                if ($post_data['pdd'] == 1 || empty($post_data['pdd']))
                {
                    echo "hidden";
                }
                ?>">
                    <input type="text" name="AlertRules[pdd_value]"  value="<?php echo $post_data['pdd_value'] ?>" class="validate[required,custom[number]]" /><b class="padding-lr-5">s</b>
                </span>
            </td>
        </tr>

        <tr>
            <td  style="text-align: right;"><?php __('Profitability')?>: </td>
            <td >
                <select name="AlertRules[profitability]" id="profitability" class="step2_select">
                    <option value="1"<?php
                    if ($post_data['profitability'] == 1)
                    {
                        ?> selected="selected" <?php } ?>><?php __('Ignore')?></option>
                    <option value=">"<?php
                    if ($post_data['profitability'] == ">")
                    {
                        ?> selected="selected" <?php } ?>>></option>
                    <option value="<"<?php
                    if ($post_data['profitability'] == "<")
                    {
                        ?> selected="selected" <?php } ?>><</option>
                    <option value="="<?php
                    if ($post_data['profitability'] == "=")
                    {
                        ?> selected="selected" <?php } ?>>=</option>

                </select>
                <span id="profitability_value" class="conditionValue <?php
                if ($post_data['profitability'] == 1 || empty($post_data['profitability']))
                {
                    echo "hidden";
                }
                ?>">
                    <input type="text" name="AlertRules[profitability_value]"  value="<?php echo $post_data['profitability_value'] ?>" class="validate[required,custom[number]]" /><b class="padding-lr-5">%</b>
                </span>
            </td>
        </tr>

        <tr>
            <td  style="text-align: right;"><?php __('Revenue')?>: </td>
            <td >
                <select name="AlertRules[revenue]" id="revenue" class="step2_select">
                    <option value="1"<?php
                    if ($post_data['revenue'] == 1)
                    {
                        ?> selected="selected" <?php } ?>><?php __('Ignore')?></option>
                    <option value=">"<?php
                    if ($post_data['revenue'] == ">")
                    {
                        ?> selected="selected" <?php } ?>>></option>
                    <option value="<"<?php
                    if ($post_data['revenue'] == "<")
                    {
                        ?> selected="selected" <?php } ?>><</option>
                    <option value="="<?php
                    if ($post_data['revenue'] == "=")
                    {
                        ?> selected="selected" <?php } ?>>=</option>

                </select>
                <span id="revenue_value" class="conditionValue <?php
                if ($post_data['revenue'] == 1 || empty($post_data['revenue']))
                {
                    echo "hidden";
                }
                ?>">
                    <input type="text" name="AlertRules[revenue_value]"  value="<?php echo $post_data['revenue_value']; ?>" class="validate[required,custom[number]]" /><b class="padding-lr-5">$</b>
                </span>
            </td>
        </tr>

        <tr>
            <td  style="text-align: right;"><?php __('Min Call Attempt')?>: </td>
            <td >
                <input type="text" name="AlertRules[min_call_attempt]" value="<?php echo $post_data['min_call_attempt'] ?>" class="validate[required,custom[number]]" style="width: 196px;" />
            </td>
        </tr>

    </tbody>
</table>
<div class="center">
    <a step="#step1" href=""  data-toggle="tab" value="next"  id="previous2" class=" btn primary"><?php __('Previous')?></a>
    <a value="next" id="next2" data-toggle="tab" href=""  class="input in-submit btn btn-primary"><?php __('Next')?></a>
    <!--<input type="submit" value="Finish" id="finish" class="input in-submit btn btn-primary" style="display: none;"  />-->
</div>

<style>
    .padding-lr-5 {
        padding: 0px 5px;
    }
</style>

<script type="text/javascript">
    $(function() {
        $('#sdp_sign').on('change', function(){
            if($(this).val() == '0'){
                $('#sdp_type').addClass('hidden');
                $('#sdp_value').addClass('hidden');
            }else{
                $('#sdp_type').removeClass('hidden');
                $('#sdp_value').removeClass('hidden');
            }
        })
        $("#next2").click(function() {
            if($('input[name = "AlertRules[min_call_attempt]"]').val() === "0"){
               jGrowl_to_notyfy('Minimum attempt can not be zero', {theme: 'jmsg-error'});
               return false;
            }

            var conditionSelected = false;

            $(".conditionValue").each(function (key, item) {
                if (!$(item).hasClass('hidden')) {
                    conditionSelected = true;
                    return;
                }
            });

            if (!conditionSelected) {
                jGrowl_to_notyfy('At least one condition required!', {theme: 'jmsg-error'});
                return false;
            }

            $("#step3").click();
        });
        $("#previous2").click(function() {
            $("#step1").click();
        });
        $("#step3").click(function() {
            var flg = $("#step_").val();
            if(flg > 3)
            {
                $("#step_").val(3);
                return true;
            }
            if ($("#rule_form").validationEngine('validate'))
            {
                $("#step4").css('cursor', 'pointer').unbind('click', die);
                $("#step_").val(4);
                return true;
            }
            return false;
        });


        $(".step2_select").change(function() {
            var value = $(this).val();
            if (value == 1)
            {
                $(this).next().addClass('hidden');
            }
            else
            {
                $(this).next().removeClass('hidden');
            }
        });
    });

</script>
