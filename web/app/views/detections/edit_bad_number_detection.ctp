<style>
    input[type="text"]{width: 50px;}
</style>
<ul class="breadcrumb">
    <li><?php __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Management') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot?>detections/bad_number_detection"><?php echo __('Bad ANI / DNIS Detection'); ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Edit'); echo ' [' . $data[0][0]['name'] . ']';?></li>
</ul>


<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Wizard'); ?></h4>
    <div class="buttons pull-right">

    </div>
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>



<div class="innerLR">
    <div class="wizard">
        <div class="widget widget-tabs widget-tabs-double widget-body-white">
            <div class="widget-head">
                <ul>
                    <li class="active">
                        <a class="glyphicons no-js cogwheels step" id="step1" hit="" data-toggle="tab" href="#tab1" >
                            <i></i>
                            <span class="strong"><?php __('Step 1'); ?></span>
                            <span><?php __('Define Criteria'); ?></span>
                        </a>
                    </li>
                    <li>
                        <a class="glyphicons no-js router step" id="step2" data-toggle="tab" href="#tab2">
                            <i></i>
                            <span class="strong"><?php __('Step 2'); ?></span>
                            <span><?php __('Define Action'); ?></span>
                        </a>
                    </li>
                    <li>
                        <a class="glyphicons no-js tag step" id="step3"  hit=""   data-toggle="tab" href="#tab3">
                            <i></i>
                            <span class="strong"><?php __('Step 3'); ?></span>
                            <span><?php __('Execution Frequency'); ?></span>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="widget-body">
                <form method="post" id="myform" >
                    <div class="tab-content">
                        <div id="tab1" class="tab-pane active">
                            <table class="table dynamicTable tableTools table-bordered  table-white table-primary">
                                <tr id="target_tr">
                                    <td><?php __('Target'); ?></td>
                                    <td>
                                        <select name="target">
                                            <option value="0" <?php echo $data[0][0]['target']==0 ? 'selected' : '' ?>><?php __('ANI') ?></option>
                                            <option value="1" <?php echo $data[0][0]['target']==1 ? 'selected' : '' ?>><?php __('DNIS') ?></option>
                                        </select>
                                    </td>
                                </tr>
                                <tr id="valid_attempts_tr">
                                    <td>
                                        <?php __('IP'); ?>
                                    </td>
                                    <td>
                                        <input value="<?php echo $data[0][0]['ip']?>" type="text" onkeypress='return (event.charCode >= 48 && event.charCode <= 57) || event.charCode == 46' name="ip" id="ip" style="width: 100%" required>
                                    </td>
                                    <td colspan="2"></td>
                                </tr>
                                <tr id="valid_attempts_tr">
                                    <td>
                                        <?php __('Valid Ingress Call Attempt'); ?>
                                    </td>
                                    <td>
                                        >= <input value="<?php echo $data[0][0]['valid_attempts']?>" type="text" onkeypress='return event.charCode >= 48 && event.charCode <= 57' name="valid_attempts" id="valid_attempts" required>
                                    </td>
                                    <td colspan="2"></td>
                                </tr>
                                <tr id="p200_tr">
                                    <td>
                                        <label><input type="checkbox" name="p200c" id="p200c" <?php echo empty($data[0][0]['p200']) ? '' : 'checked'?>>
                                        <?php __('Percentage of 200 OK'); ?></label>
                                    </td>
                                    <td>
                                        <= <input value="<?php echo $data[0][0]['p200']?>" type="text" onkeypress='return event.charCode >= 48 && event.charCode <= 57' name="p200" id="p200">%
                                    </td>
                                    <td colspan="2"></td>
                                </tr>
                                <tr id="p404_tr">
                                    <td>
                                        <label><input type="checkbox" name="p404c" id="p404c" <?php echo empty($data[0][0]['p404']) ? '' : 'checked'?>>
                                        <?php __('Percentage of 404'); ?></label>
                                    </td>
                                    <td>
                                        >= <input value="<?php echo $data[0][0]['p404']?>" type="text" onkeypress='return event.charCode >= 48 && event.charCode <= 57' name="p404" id="p404">%
                                    </td>
                                    <td colspan="2"></td>
                                </tr>
                                <tr id="p503_tr">
                                    <td>
                                        <label><input type="checkbox" name="p503c" id="p503c" <?php echo empty($data[0][0]['p503']) ? '' : 'checked'?>>
                                        <?php __('Percentage of 503'); ?></label>
                                    </td>
                                    <td>
                                        >= <input value="<?php echo $data[0][0]['p503']?>" type="text" onkeypress='return event.charCode >= 48 && event.charCode <= 57' name="p503" id="p503">%
                                    </td>
                                    <td colspan="2"></td>
                                </tr>
                                <tr id="p480_tr">
                                    <td>
                                        <label><input type="checkbox" name="p480c" id="p480c" <?php echo empty($data[0][0]['p480']) ? '' : 'checked'?>>
                                        <?php __('Percentage of 480'); ?></label>
                                    </td>
                                    <td>
                                        >= <input value="<?php echo $data[0][0]['p480']?>" type="text" onkeypress='return event.charCode >= 48 && event.charCode <= 57' name="p480" id="p480">%
                                    </td>
                                    <td colspan="2"></td>
                                </tr>
                                <tr id="p486_tr">
                                    <td>
                                        <label><input type="checkbox" name="p486c" id="p486c" <?php echo empty($data[0][0]['p486']) ? '' : 'checked'?>>
                                        <?php __('Percentage of 486'); ?></label>
                                    </td>
                                    <td>
                                        >= <input value="<?php echo $data[0][0]['p486']?>" type="text" onkeypress='return event.charCode >= 48 && event.charCode <= 57' name="p486" id="p486">%
                                    </td>
                                    <td colspan="2"></td>
                                </tr>
                                <tr id="p487_tr">
                                    <td>
                                        <label><input type="checkbox" name="p487c" id="p487c" <?php echo empty($data[0][0]['p487']) ? '' : 'checked'?>>
                                        <?php __('Percentage of 487'); ?></label>
                                    </td>
                                    <td>
                                        >= <input value="<?php echo $data[0][0]['p487']?>" type="text" onkeypress='return event.charCode >= 48 && event.charCode <= 57' name="p487" id="p487">%
                                    </td>
                                    <td colspan="2"></td>
                                </tr>
                                <tr id="p45_tr">
                                    <td>
                                        <label><input type="checkbox" name="p45c" id="p45c" <?php echo empty($data[0][0]['p45']) ? '' : 'checked'?>>
                                        <?php __('Percentage of 4xx and 5xx'); ?></label>
                                    </td>
                                    <td>
                                        >= <input value="<?php echo $data[0][0]['p45']?>" type="text" onkeypress='return event.charCode >= 48 && event.charCode <= 57' name="p45" id="p45">%
                                    </td>
                                    <td colspan="2"></td>
                                </tr>
                                <tr id="asr_tr">
                                    <td>
                                        <label><input type="checkbox" name="asrc" id="asrc" <?php echo empty($data[0][0]['asr']) ? '' : 'checked'?>>
                                        <?php __('ASR'); ?></label>
                                    </td>
                                    <td>
                                        <= <input value="<?php echo $data[0][0]['asr']?>" type="text" onkeypress='return event.charCode >= 48 && event.charCode <= 57' name="asr" id="asr">%
                                    </td>
                                    <td colspan="2"></td>
                                </tr>
                                <tr id="acd_tr">
                                    <td>
                                        <label><input type="checkbox" name="acdc" id="acdc" <?php echo empty($data[0][0]['acd']) ? '' : 'checked'?>>
                                        <?php __('ACD'); ?></label>
                                    </td>
                                    <td>
                                        <= <input value="<?php echo $data[0][0]['acd']?>" type="text" onkeypress='return event.charCode >= 48 && event.charCode <= 57' name="acd" id="acd">s
                                    </td>
                                    <td colspan="2"></td>
                                </tr>
                            </table>
                            <div class="separator"></div>
                            <div class="center">
                                <a step="#step1" href=""  data-toggle="tab" value="Previous" onclick="$('#step1').click()"   class=" btn primary"><?php __('Previous')?></a>
                                <a value="next" data-toggle="tab" onclick="$('#step2').click()" step="#step3" href="javascript:void(0)"  class="input in-submit btn btn-primary"><?php __('Next') ?></a>
                            </div>
                        </div>

                        <div id="tab2" class="tab-pane">
                            <table class="table dynamicTable tableTools table-bordered  table-white table-primary">
                                <tr id="block_type_tr">
                                    <td><?php __('Block Type'); ?></td>
                                    <td>
                                        <select name="block_type" id="block_type">
                                            <option value="0" <?php echo $data[0][0]['block_type']==0 ? 'selected' : '' ?>><?php __('Global Block') ?></option>
                                            <option value="1" <?php echo $data[0][0]['block_type']==1 ? 'selected' : '' ?>><?php __('Block for Ingress') ?></option>
                                            <option value="2" <?php echo $data[0][0]['block_type']==2 ? 'selected' : '' ?>><?php __('Block for Egress') ?></option>
                                        </select>
                                    </td>
                                </tr>
                                <tr id="trunk_list_tr">
                                    <td><?php __('Trunk List'); ?></td>
                                    <td>
                                        <select multiple="multiple" name="trunk_list[]" id="trunk_list">
                                        </select>
                                        <!-- <select multiple="multiple" id="columns_select" name="ingress_trunks[]" class="width220 validate[required] select_mul" >
                                            <?php foreach($ingresses_info as $client_name=>$ingress_info): ?>
                                                <optgroup label="<?php echo $client_name; ?>">
                                                    <?php foreach($ingress_info as $ingress_id=>$ingress_name): ?>
                                                        <option value="<?php echo $ingress_id ?>" <?php if (in_array($ingress_id,$selected_ingress)): ?> selected="selected"<?php endif; ?>>
                                                            <?php echo $ingress_name ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </optgroup>
                                            <?php endforeach; ?>
                                        </select> -->
                                    </td>
                                </tr>
                            </table>
                            <div class="separator"></div>
                            <div class="center">
                                <a step="#step1" href=""  data-toggle="tab" value="Previous" onclick="$('#step1').click()"   class=" btn primary"><?php __('Previous')?></a>
                                <a value="next" data-toggle="tab" onclick="$('#step3').click()" step="#step2" href="javascript:void(0)"  class="input in-submit btn btn-primary"><?php __('Next') ?></a>
                            </div>
                        </div>

                        <div id="tab3" class="tab-pane">
                            <table class="table dynamicTable tableTools table-bordered  table-white table-primary">
                                <tr id="name_tr">
                                    <td>
                                        <?php __('Rule Name'); ?>
                                    </td>
                                    <td>
                                        <input value="<?php echo $data[0][0]['name']?>" type="text" name="name" id="name" style="width: 100%" required>
                                    </td>
                                    <td colspan="2"></td>
                                </tr>
                                <tr id="exec_every_tr">
                                    <td>
                                        <?php __('Execute Every'); ?>
                                    </td>
                                    <td>
                                        <input value="<?php echo $data[0][0]['exec_every']?>" type="text" onkeypress='return event.charCode >= 48 && event.charCode <= 57' name="exec_every" id="exec_every" required> Min
                                    </td>
                                    <td colspan="2"></td>
                                </tr>
                                <tr id="from_last_tr">
                                    <td>
                                        <?php __('Use data from last'); ?>
                                    </td>
                                    <td>
                                        <input value="<?php echo $data[0][0]['from_last']?>" type="text" onkeypress='return event.charCode >= 48 && event.charCode <= 57' name="from_last" id="from_last" required> Min
                                    </td>
                                    <td colspan="2"></td>
                                </tr>
                            </table>
                            <div class="separator"></div>
                            <div class="center">
                                <a step="#step2" href=""  data-toggle="tab" value="Previous" onclick="$('#step2').click()"   class=" btn primary"><?php __('Previous')?></a>
                                <input type="submit" id="subbtn" class="btn btn-primary" value="<?php __('Submit') ?>">
                            </div>
                        </div>

                    </div>


                </form>

            </div>

        </div>
    </div>



</div>
<script type="text/javascript">
    function in_array(value, array)
    {
        for(var i = 0; i < array.length; i++)
        {
            if(array[i] == value) return true;
        }
        return false;
    }
    $(function() {
        $('#target_tr select').change(function () {
            if ($('#target_tr select').val() == 0) {
                $('#valid_attempts_tr td').first().html('<?php __('Valid Ingress Call Attempt'); ?>');
            } else {
                $('#valid_attempts_tr td').first().html('<?php __('Valid Egress Call Attempt'); ?>');
            }
        });

        $('#p200c').change(function () {
            if (this.checked) {
                $('#p200').removeAttr('disabled');
            } else {
                $('#p200').attr('disabled','disabled');
            }
        });
        $('#p200c').change();

        $('#p404c').change(function () {
            if (this.checked) {
                $('#p404').removeAttr('disabled');
            } else {
                $('#p404').attr('disabled','disabled');
            }
        });
        $('#p404c').change();

        $('#p503c').change(function () {
            if (this.checked) {
                $('#p503').removeAttr('disabled');
            } else {
                $('#p503').attr('disabled','disabled');
            }
        });
        $('#p503c').change();

        $('#p480c').change(function () {
            if (this.checked) {
                $('#p480').removeAttr('disabled');
            } else {
                $('#p480').attr('disabled','disabled');
            }
        });
        $('#p480c').change();

        $('#p486c').change(function () {
            if (this.checked) {
                $('#p486').removeAttr('disabled');
            } else {
                $('#p486').attr('disabled','disabled');
            }
        });
        $('#p486c').change();

        $('#p487c').change(function () {
            if (this.checked) {
                $('#p487').removeAttr('disabled');
            } else {
                $('#p487').attr('disabled','disabled');
            }
        });
        $('#p487c').change();

        $('#p45c').change(function () {
            if (this.checked) {
                $('#p45').removeAttr('disabled');
            } else {
                $('#p45').attr('disabled','disabled');
            }
        });
        $('#p45c').change();

        $('#asrc').change(function () {
            if (this.checked) {
                $('#asr').removeAttr('disabled');
            } else {
                $('#asr').attr('disabled','disabled');
            }
        });
        $('#asrc').change();

        $('#acdc').change(function () {
            if (this.checked) {
                $('#acd').removeAttr('disabled');
            } else {
                $('#acd').attr('disabled','disabled');
            }
        });
        $('#acdc').change();

        $("#trunk_list").multiSelect({
            selectableOptgroup: true,
            buttonWidth: 400
        });

        var firstRun = true;

        $('#block_type').change(function () {
            var val = $(this).val();
            if (val == 0) {
                $('#trunk_list').html('');
                $("#trunk_list").multiSelect("destroy").multiSelect();
                $('#trunk_list_tr').hide();
            } else {
                $('#trunk_list_tr').show();
                var isIngress = val == 1 ? 1 : 0;
                var options = '';
                $.ajax({
                    url: '<?php echo $this->webroot?>detections/get_trunk_list',
                    method: 'post',
                    data: {
                        'is_ingress': isIngress
                    },
                    dataType: 'json',
                    success: function(res) {
                        $.each(res, function (i, trunk) {
                            var selected = '';
                            if (firstRun && in_array(trunk[0].resource_id, [<?php echo $data[0][0]['trunk_list']?>])) {
                                selected = ' selected';
                            }
                            options += '<option value="' + trunk[0].resource_id + '"' +selected + '>' + trunk[0].alias + '</option>';
//                            console.log(trunk);
                        });
                        if (firstRun) {
                            firstRun = false;
                        }
                        $('#trunk_list').html('');
                        $('#trunk_list').html(options);
                        $("#trunk_list").multiSelect("destroy").multiSelect();
                    }
                });
            }
        });
        $('#block_type').change();

    });
</script>