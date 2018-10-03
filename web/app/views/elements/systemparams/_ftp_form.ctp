<form id="myform" method="post">
    <div class="widget">
        <div class="widget-head"><h4 class="heading"><?php __('Job Basic') ?></h4></div>
        <div class="widget-body">
            <table class="form table dynamicTable tableTools table-bordered  table-white">
                <colgroup>
                    <col width="40%">
                    <col width="60%">
                </colgroup>
                <tr>
                    <td class="align_right"><?php __('Alias') ?>*</td>
                    <td>
                        <input type="text" class="validate[required,custom[onlyLetterNumberLine]]" name="alias" value="<?php echo isset($data[0][0]['alias']) ? $data[0][0]['alias'] : '' ?>" />
                    </td>
                </tr>
                <tr>
                    <td class="align_right"><?php __('Frequency') ?></td>
                    <td>
                        <select id="frequency" name="frequency">
                            <option value="1" <?php echo $data[0][0]['frequency'] == 1 ? 'selected="selected"' : '' ?>><?php __('Daily') ?></option>
                            <option value="2" <?php echo $data[0][0]['frequency'] == 2 ? 'selected="selected"' : '' ?>><?php __('Weekly') ?></option>
                            <option value="3" <?php echo $data[0][0]['frequency'] == 3 ? 'selected="selected"' : '' ?>><?php __('Every') ?></option>
<!--                            <option value="4" --><?php //echo $data[0][0]['frequency'] == 4 ? 'selected="selected"' : '' ?><!-->--><?php //__('Minutely') ?><!--</option>-->
                        </select>
                        <select id="every_day" name="every_day">
                            <option value="0">Sunday</option>
                            <option value="1" <?php if ($data[0][0]['every_day'] == 1) echo 'selected="selected"' ?>><?php __('Monday')?></option>
                            <option value="2" <?php if ($data[0][0]['every_day'] == 2) echo 'selected="selected"' ?>><?php __('Tuesday')?></option>
                            <option value="3" <?php if ($data[0][0]['every_day'] == 3) echo 'selected="selected"' ?>><?php __('Wednesday')?></option>
                            <option value="4" <?php if ($data[0][0]['every_day'] == 4) echo 'selected="selected"' ?>><?php __('Thursday')?></option>
                            <option value="5" <?php if ($data[0][0]['every_day'] == 5) echo 'selected="selected"' ?>><?php __('Friday')?></option>
                            <option value="6" <?php if ($data[0][0]['every_day'] == 6) echo 'selected="selected"' ?>><?php __('Saturday')?></option>
                        </select>
                        <select id="every_hours" name="every_hours">
                            <option value="1" <?php echo $data[0][0]['every_hours'] == 1 ? 'selected="selected"' : '' ?>>1 <?php __('Hour') ?></option>
                            <option value="2" <?php echo $data[0][0]['every_hours'] == 2 ? 'selected="selected"' : '' ?>>2 <?php __('Hour') ?></option>
                            <option value="3" <?php echo $data[0][0]['every_hours'] == 3 ? 'selected="selected"' : '' ?>>3 <?php __('Hour') ?></option>
                            <option value="4" <?php echo $data[0][0]['every_hours'] == 4 ? 'selected="selected"' : '' ?>>4 <?php __('Hour') ?></option>
                            <option value="6" <?php echo $data[0][0]['every_hours'] == 6 ? 'selected="selected"' : '' ?>>6 <?php __('Hour') ?></option>
                            <option value="8" <?php echo $data[0][0]['every_hours'] == 8 ? 'selected="selected"' : '' ?>>8 <?php __('Hour') ?></option>
                            <option value="12" <?php echo $data[0][0]['every_hours'] == 12 ? 'selected="selected"' : '' ?>>12 <?php __('Hour') ?></option>
                        </select>
                        <select id="every_minutes" name="every_minutes">
                            <option value="15" <?php echo $data[0][0]['every_minutes'] == 15 ? 'selected="selected"' : '' ?>>15 <?php __(' Minutes') ?></option>
                            <option value="30" <?php echo $data[0][0]['every_minutes'] == 30 ? 'selected="selected"' : '' ?>>30 <?php __('Minutes') ?></option>
                        </select>
                    </td>
                </tr>
                <tr id="execute_on_tr">
                    <td class="align_right"><?php __('FTP Execute On') ?></td>
                    <td>
                        <input type="text" name="time" onfocus="WdatePicker({dateFmt: 'HH:00'});" value="<?php echo isset($data[0][0]['time']) ? $data[0][0]['time'] : '00:00' ?>" />
                    </td>
                </tr>
                <tr>
                    <td class="align_right"><?php __('Maximum Lines per File'); ?>*</td>
                    <td>
                        <input type="text" name="max_lines" class="validate[required,custom[onlyNumberSp]]" value="<?php echo isset($data[0][0]['max_lines']) ? $data[0][0]['max_lines'] : '10000' ?>" />
                    </td>
                </tr>
                <tr id="file_breakdown">
                    <td class="align_right"><?php __('File Breakdown'); ?></td>
                    <td>
                        <select name="file_breakdown">
                            <option value="0" <?php echo $data[0][0]['file_breakdown'] == 0 ? 'selected="selected"' : '' ?>><?php __('As one big file') ?></option>
                            <option value="1" <?php echo $data[0][0]['file_breakdown'] == 1 ? 'selected="selected"' : '' ?>><?php __('As hourly file') ?></option>
                            <option value="2" <?php echo $data[0][0]['file_breakdown'] == 2 ? 'selected="selected"' : '' ?>><?php __('As daily file') ?></option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td class="align_right"><?php __('Include Headers'); ?></td>
                    <td>
                        <select name="contain_headers">
                            <option value="true" <?php if ($data[0][0]['contain_headers']) echo 'selected="selected"' ?>><?php __('Yes') ?></option>
                            <option value="false" <?php if (!$data[0][0]['contain_headers']) echo 'selected="selected"' ?>><?php __('No') ?></option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td class="align_right"><?php __('Compression'); ?></td>
                    <td>
                        <select name="file_type">
                            <option value="1" <?php if ($data[0][0]['file_type'] == 1) echo 'selected="selected"' ?>><?php __('gz') ?></option>
                            <option value="2" <?php if ($data[0][0]['file_type'] == 2) echo 'selected="selected"' ?>><?php __('tar.gz') ?></option>
                            <option value="3" <?php if ($data[0][0]['file_type'] == 3) echo 'selected="selected"' ?>><?php __('tar.bz2') ?></option>
                            <option value="4" <?php if ($data[0][0]['file_type'] == 4) echo 'selected="selected"' ?>><?php __('zip') ?></option>
                        </select>
                    </td>
                </tr>
            </table>
        </div>
    </div>


    <div class="widget">
        <div class="widget-head"><h4 class="heading"><?php __('Filter Criteria') ?></h4></div>
        <div class="widget-body">
            <table class="form table dynamicTable tableTools table-bordered  table-white">
                <colgroup>
                    <col width="40%">
                    <col width="60%">
                </colgroup>
                <tr>
                    <td class="align_right"><?php __('Duration') ?></td>
                    <td>
                        <select name="duration">
                            <option value="NULL" <?php if ($data[0][0]['duration'] === 'NULL') echo 'selected="selected"'; ?>><?php __('All') ?></option>
                            <option value="1" <?php if ($data[0][0]['duration'] === '1') echo 'selected="selected"'; ?>><?php __('Non-zero') ?></option>
                            <option value="0" <?php if ($data[0][0]['duration'] === '0') echo 'selected="selected"'; ?>><?php __('Zero') ?></option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td class="align_right"><?php __('Originating Release Cause'); ?></td>
                    <td>
                        <?php
                        $release_cause = $appCommon->get_ftp_fields();

                        echo $form->input('ingress_release_cause',array('type' => 'select','style' => 'width:100%;height:200px;',
                            'label' => false,'div' => false,'options' => $release_cause,
                            'multiple' => true,'id' => 'ingress_release_cause','name' => 'ingress_release_cause')); ?>
                        <!--
                        <select id="ingress_release_cause" name="ingress_release_cause[]" multiple="multiple" style="width:100%;height:200px;">
                            <option value=""><?php __('all') ?></option>
                            <option value="200"><?php __('success') ?></option>
                            <option value="300"><?php __('multiple') ?></option>
                            <option value="301"><?php __('moved permanently') ?></option>
                            <option value="302"><?php __('moved temporarily') ?></option>
                            <option value="305"><?php __('use proxy') ?></option>
                            <option value="380"><?php __('alternative service') ?></option>
                            <option value="400"><?php __('bad request') ?></option>
                            <option value="401"><?php __('unauthorized') ?></option>
                            <option value="402"><?php __('payment required') ?></option>
                            <option value="403"><?php __('forbidden') ?></option>
                            <option value="404"><?php __('not found') ?></option>
                            <option value="405"><?php __('method not allowed') ?></option>
                            <option value="406"><?php __('not acceptable') ?></option>
                            <option value="407"><?php __('proxy authentication required') ?></option>
                            <option value="408"><?php __('request timeout') ?></option>
                            <option value="410"><?php __('gone') ?></option>
                            <option value="413"><?php __('request entity too large') ?></option>
                            <option value="414"><?php __('request-url too long') ?></option>
                            <option value="415"><?php __('unsupported media type') ?></option>
                            <option value="416"><?php __('unsupported url scheme') ?></option>
                            <option value="420"><?php __('bad extension') ?></option>
                            <option value="421"><?php __('extension required') ?></option>
                            <option value="423"><?php __('interval too brief') ?></option>
                            <option value="480"><?php __('temporarily unavailable') ?></option>
                            <option value="481"><?php __('call/transaction does not exist') ?></option>
                            <option value="482"><?php __('loop detected') ?></option>
                            <option value="483"><?php __('too many hops') ?></option>
                            <option value="484"><?php __('address incomplete') ?></option>
                            <option value="485"><?php __('ambiguous') ?></option>
                            <option value="486"><?php __('busy here') ?></option>
                            <option value="487"><?php __('request terminated') ?></option>
                            <option value="488"><?php __('not acceptable here') ?></option>
                            <option value="491"><?php __('request pending') ?></option>
                            <option value="493"><?php __('indecipherable') ?></option>
                            <option value="500"><?php __('server internal error') ?></option>
                            <option value="501"><?php __('not implemented') ?></option>
                            <option value="502"><?php __('bad gateway') ?></option>
                            <option value="503"><?php __('service unavailable') ?></option>
                            <option value="504"><?php __('server time-out') ?> </option>
                            <option value="505"><?php __('version not supported') ?> </option>
                            <option value="513"><?php __('message too large') ?> </option>
                            <option value="600"><?php __('busy everywhere') ?> </option>
                            <option value="603"><?php __('decline') ?> </option>
                            <option value="604"><?php __('does not exist anywhere') ?> </option>
                            <option value="606"><?php __('not acceptable') ?> </option>
                        </select>
                        -->
                    </td>
                </tr>
                <tr>
                    <td class="align_right"><?php __('Terminating Release Cause'); ?></td>
                    <td>
                        <?php echo $form->input('egress_release_cause',array('type' => 'select','style' => 'width:100%;height:200px;',
                            'label' => false,'div' => false,'options' => $release_cause,
                            'multiple' => true,'id' => 'egress_release_cause','name' => 'egress_release_cause')); ?>
                        <!--
                        <select id="egress_release_cause" name="egress_release_cause[]" multiple="multiple" style="width:100%;height:200px;">
                            <option value=""><?php __('all') ?></option>
                            <option value="200"><?php __('success') ?></option>
                            <option value="300"><?php __('multiple') ?></option>
                            <option value="301"><?php __('moved permanently') ?></option>
                            <option value="302"><?php __('moved temporaily') ?></option>
                            <option value="305"><?php __('use proxy') ?></option>
                            <option value="380"><?php __('alternative service') ?></option>
                            <option value="400"><?php __('bad request') ?></option>
                            <option value="401"><?php __('unauthorized') ?></option>
                            <option value="402"><?php __('payment required') ?></option>
                            <option value="403"><?php __('forbidden') ?></option>
                            <option value="404"><?php __('not found') ?></option>
                            <option value="405"><?php __('method no allowed') ?></option>
                            <option value="406"><?php __('not acceptable') ?></option>
                            <option value="407"><?php __('proxy authentication required') ?></option>
                            <option value="408"><?php __('request timeout') ?></option>
                            <option value="410"><?php __('gone') ?></option>
                            <option value="413"><?php __('request entity too large') ?></option>
                            <option value="414"><?php __('request-url too long') ?></option>
                            <option value="415"><?php __('unsupported media type') ?></option>
                            <option value="416"><?php __('unsupported url scheme') ?></option>
                            <option value="420"><?php __('bad extension') ?></option>
                            <option value="421"><?php __('extension required') ?></option>
                            <option value="423"><?php __('interval too brief') ?></option>
                            <option value="480"><?php __('temporarily unavailable') ?></option>
                            <option value="481"><?php __('call/transaction does not exist') ?></option>
                            <option value="482"><?php __('loop detected') ?></option>
                            <option value="483"><?php __('too many hops') ?></option>
                            <option value="484"><?php __('address incomplete') ?></option>
                            <option value="485"><?php __('ambiguous') ?></option>
                            <option value="486"><?php __('busy here') ?></option>
                            <option value="487"><?php __('request terminated') ?></option>
                            <option value="488"><?php __('not acceptable here') ?></option>
                            <option value="491"><?php __('request pending') ?></option>
                            <option value="493"><?php __('undecipherable') ?></option>
                            <option value="500"><?php __('server internal error') ?></option>
                            <option value="501"><?php __('not implemented') ?></option>
                            <option value="502"><?php __('bad gateway') ?></option>
                            <option value="503"><?php __('service unavailable') ?></option>
                            <option value="504"><?php __('server time-out') ?> </option>
                            <option value="505"><?php __('version not supported') ?> </option>
                            <option value="513"><?php __('message too large') ?> </option>
                            <option value="600"><?php __('busy everywhere') ?> </option>
                            <option value="603"><?php __('decline') ?> </option>
                            <option value="604"><?php __('does not exist anywhere') ?> </option>
                            <option value="606"><?php __('not acceptable') ?> </option>
                        </select>
                        -->
                    </td>
                </tr>
            </table>
        </div>
    </div>


    <div class="widget">
        <div class="widget-head"><h4 class="heading"><?php __('FTP Setting') ?></h4></div>
        <div class="widget-body">
            <table class="form table dynamicTable tableTools table-bordered  table-white">
                <colgroup>
                    <col width="40%">
                    <col width="60%">
                </colgroup>
                <tr>
                    <td class="align_right"><?php __('Server Address') ?>*</td>
                    <td>
                        <input type="text" name="server_ip" class="validate[required,custom[ftp]]" value="<?php echo isset($data[0][0]['server_ip']) ? $data[0][0]['server_ip'] : '' ?>" />
                    </td>
                </tr>
                <tr>
                    <td class="align_right"><?php __('Server Port') ?>*</td>
                    <td>
                        <input type="text" name="server_port" class="validate[required,custom[onlyNumberSp], max[65536]" value="<?php echo isset($data[0][0]['server_port']) ? $data[0][0]['server_port'] : '' ?>" />
                    </td>
                </tr>
                <tr>
                    <td class="align_right"><?php __('Server Directory') ?></td>
                    <td>
                        <input type="text" class="validate[required,custom[path]]" name="server_dir" value="<?php echo isset($data[0][0]['server_dir']) ? $data[0][0]['server_dir'] : '/' ?>" />
                    </td>
                </tr>
                <tr>
                    <td class="align_right"><?php __('User Name') ?>*</td>
                    <td>
                        <input type="text" name="username" class="validate[required,custom[onlyLetterNumberLine]]" value="<?php echo isset($data[0][0]['username']) ? $data[0][0]['username'] : '' ?>" />
                    </td>
                </tr>
                <tr>
                    <td class="align_right"><?php __('Password') ?>*</td>
                    <td>
                        <input  class="validate[required]" type="text" name="password" value="<?php echo isset($data[0][0]['password']) ? $data[0][0]['password'] : '' ?>" />
                    </td>
                </tr>
            </table>
        </div>
    </div>


    <div class="widget">
        <div class="widget-head"><h4 class="heading"><?php __('Fields') ?></h4></div>
        <div class="widget-body">
            <table class="form table dynamicTable tableTools table-bordered  table-white">
                <colgroup>
                    <col width="40%">
                    <col width="60%">
                </colgroup>
                <tr>
                    <td colspan="4">
                        <table class="">
                            <tr>
                                <td class="align_right">
                                <div style="width:107px;">
                                    <?php __('Fields'); ?>*
                                </div>
                                </td>
                                <td>
                                    <select id="columns_select" multiple="multiple" style=" width: 400px;height: 300px;margin-right: 32px;">
                                        <?php foreach ($back_selects as $key => $back_select): ?>
                                            <?php if($back_select != 'Call_duration_in_ms'): ?>
                                                <option value="<?php echo $key ?>"><?php echo str_replace ('Termination', 'Term', str_replace ('Origination', 'Orig', $back_select)); ?></option>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                                <td class="value value4">
                                    <div style="margin: 0 20px;">
                                        <input type="button" class="input in-submit in-button btn" value="<?php __('Add') ?>" onclick="DoAdd();" style="width: 70px;margin-left: 0px;">
                                        <br><br>
                                        <input type="button" class="input in-submit in-button btn" value="<?php __('Delete') ?>" onclick="DoDel();" style="margin-left: 0px;">
                                    </div>
                                </td>
                                <td>

                                    <select id="columns" name="fields[]" multiple="multiple" style="width: 398px; height: 300px;">
                                        <?php
                                        foreach ($default_fields as $key => $field):
                                            if (!empty($default_fields)):
                                                ?>
                                                <option value="<?php echo $key ?>"><?php echo $field; ?></option>
                                                <?php
                                            endif;
                                        endforeach;
                                        ?>
                                    </select>
                                </td>
                                <td class="value value4">
                                    <input type="button" value="<?php __('up') ?>" onclick="moveOption('select2', 'up');" style="width: 65px; margin-left: 0px;" class="input in-submit in-button btn">
                                    <br><br>
                                    <input type="button" value="<?php __('Down') ?>" onclick="moveOption('select2', 'down');" style="margin-left: 0px;" class="input in-submit in-button btn">
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <tr>
                    <td colspan="2">
                        <table>
                            <tr>
                                <td class="align_right"><?php __('Originating Trunk'); ?>*</td>
                                <td>
                                    <select id="originating_trunk" style="width:400px; height:200px;" name="ingresses[]" multiple="multiple" class="validate[required]">
                                        <?php
                                        $ingress_arr = explode(',', $data[0][0]['ingresses']);
                                        ?>
                                        <?php foreach ($ingresses as $ingress): ?>
                                            <option value="<?php echo $ingress[0]['resource_id']; ?>" <?php if (in_array($ingress[0]['resource_id'], $ingress_arr) || $data[0][0]['ingresses_all']) echo 'selected="selected"'; ?>><?php echo $ingress[0]['alias']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <input id="originating_trunk_all" type="checkbox" name="ingresses_all" <?php if ($data[0][0]['ingresses_all']) echo 'checked="checked"'; ?> /><?php __('All') ?>
                                </td>
                                <td class="align_right"><?php __('Terminating Trunk'); ?>*</td>
                                <td>
                                    <select id="terminating_trunk" style="width:400px; height:200px;" name="egresses[]" multiple="multiple" class="validate[required]">
                                        <?php
                                        $egress_arr = explode(',', $data[0][0]['egresses']);
                                        ?>
                                        <?php foreach ($egresses as $egress): ?>
                                            <option value="<?php echo $egress[0]['resource_id']; ?>" <?php if (in_array($egress[0]['resource_id'], $egress_arr) || $data[0][0]['egresses_all']) echo 'selected="selected"'; ?>><?php echo $egress[0]['alias']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <input id="terminating_trunk_all" type="checkbox" name="egresses_all" <?php if ($data[0][0]['egresses_all']) echo 'checked="checked"'; ?> />All
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

            </table>
        </div>
    </div>
<!--    <table class="list list-form table table-condensed">
        <tbody>
            <tr>
                <td class="align_right"><?php __('Alias') ?></td>
                <td>
                    <input type="text" class="validate[required,custom[onlyLetterNumberLine]]" name="alias" value="<?php echo isset($data[0][0]['alias']) ? $data[0][0]['alias'] : '' ?>" />
                </td>
            </tr>
            <tr>
                <td class="align_right"><?php __('Server IP') ?></td>
                <td>
                    <input type="text" name="server_ip" class="validate[required,custom[ipv4]]" value="<?php echo isset($data[0][0]['server_ip']) ? $data[0][0]['server_ip'] : '' ?>" />
                </td>
            </tr>
            <tr>
                <td class="align_right"><?php __('Server Port') ?></td>
                <td>
                    <input type="text" name="server_port" class="validate[required,custom[onlyNumberSp]]" value="<?php echo isset($data[0][0]['server_port']) ? $data[0][0]['server_port'] : '' ?>" />
                </td>
            </tr>
            <tr>
                <td class="align_right"><?php __('Server Directory') ?></td>
                <td>
                    <input type="text" name="server_dir" value="<?php echo isset($data[0][0]['server_dir']) ? $data[0][0]['server_dir'] : '' ?>" />
                </td>
            </tr>
            <tr>
                <td class="align_right"><?php __('User Name') ?></td>
                <td>
                    <input type="text" name="username" class="validate[required,custom[onlyLetterNumberLine]]" value="<?php echo isset($data[0][0]['username']) ? $data[0][0]['username'] : '' ?>" />
                </td>
            </tr>
            <tr>
                <td class="align_right"><?php __('Password') ?></td>
                <td>
                    <input  class="validate[required]" type="text" name="password" value="<?php echo isset($data[0][0]['password']) ? $data[0][0]['password'] : '' ?>" />
                </td>
            </tr>
            <tr>
                <td class="align_right"><?php __('Frequency') ?></td>
                <td>
                    <select id="frequency" name="frequency">
                        <option value="1" <?php echo $data[0][0]['frequency'] == 1 ? 'selected="selected"' : '' ?>><?php __('Daily') ?></option>
                        <option value="2" <?php echo $data[0][0]['frequency'] == 2 ? 'selected="selected"' : '' ?>><?php __('Weekly') ?></option>
                        <option value="3" <?php echo $data[0][0]['frequency'] == 3 ? 'selected="selected"' : '' ?>><?php __('Hourly') ?></option>
                        <option value="4" <?php echo $data[0][0]['frequency'] == 4 ? 'selected="selected"' : '' ?>><?php __('Minutely') ?></option>
                    </select>
                    <select id="every_hours" name="every_hours">
                        <option value="1" <?php echo $data[0][0]['every_hours'] == 1 ? 'selected="selected"' : '' ?>>1 <?php __('Hour') ?></option>
                        <option value="2" <?php echo $data[0][0]['every_hours'] == 2 ? 'selected="selected"' : '' ?>>2 <?php __('Hour') ?></option>
                        <option value="3" <?php echo $data[0][0]['every_hours'] == 3 ? 'selected="selected"' : '' ?>>3 <?php __('Hour') ?></option>
                        <option value="4" <?php echo $data[0][0]['every_hours'] == 4 ? 'selected="selected"' : '' ?>>4 <?php __('Hour') ?></option>
                        <option value="6" <?php echo $data[0][0]['every_hours'] == 6 ? 'selected="selected"' : '' ?>>6 <?php __('Hour') ?></option>
                        <option value="8" <?php echo $data[0][0]['every_hours'] == 8 ? 'selected="selected"' : '' ?>>8 <?php __('Hour') ?></option>
                        <option value="12" <?php echo $data[0][0]['every_hours'] == 12 ? 'selected="selected"' : '' ?>>12 <?php __('Hour') ?></option>
                    </select>
                    <select id="every_minutes" name="every_minutes">
                        <option value="15" <?php echo $data[0][0]['every_minutes'] == 15 ? 'selected="selected"' : '' ?>>15 <?php __(' Minutes') ?></option>
                        <option value="30" <?php echo $data[0][0]['every_minutes'] == 30 ? 'selected="selected"' : '' ?>>30 <?php __('Minutes') ?></option>
                    </select>
                </td>
            </tr>
            <tr id="execute_on_tr">
                <td class="align_right"><?php __('FTP Execute on') ?></td>
                <td>
                    <input type="text" name="time" onfocus="WdatePicker({dateFmt: 'HH:00'});" value="<?php echo isset($data[0][0]['time']) ? $data[0][0]['time'] : '00:00' ?>" />
                </td>
            </tr>
            <tr>
                <td class="align_right"><?php __('Maximum lines per file') ?>*</td>
                <td>
                    <input type="text" name="max_lines" class="validate[required,custom[onlyNumberSp]]" value="<?php echo isset($data[0][0]['max_lines']) ? $data[0][0]['max_lines'] : '10000' ?>" />
                </td>
            </tr>
            <tr id="file_breakdown">
                <td class="align_right"><?php __('File Breakdown'); ?></td>
                <td>
                    <select name="file_breakdown">
                        <option value="0" <?php echo $data[0][0]['file_breakdown'] == 0 ? 'selected="selected"' : '' ?>><?php __('As one big file') ?></option>
                        <option value="1" <?php echo $data[0][0]['file_breakdown'] == 1 ? 'selected="selected"' : '' ?>><?php __('As hourly file') ?></option>
                        <option value="2" <?php echo $data[0][0]['file_breakdown'] == 2 ? 'selected="selected"' : '' ?>><?php __('As daily file') ?></option>
                    </select>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <table class="list">
                        <tr>
                            <td class="align_right"><?php __('Originating Trunk'); ?></td>
                            <td>
                                <select id="originating_trunk" style="width:400px; height:200px;" name="ingresses[]" multiple="multiple" class="validate[required]">
    <?php
    $ingress_arr = explode(',', $data[0][0]['ingresses']);
    ?>
    <?php foreach ($ingresses as $ingress): ?>
                                                        <option value="<?php echo $ingress[0]['resource_id']; ?>" <?php if (in_array($ingress[0]['resource_id'], $ingress_arr) || $data[0][0]['ingresses_all']) echo 'selected="selected"'; ?>><?php echo $ingress[0]['alias']; ?></option>
    <?php endforeach; ?>
                                </select>
                                <input id="originating_trunk_all" type="checkbox" name="ingresses_all" <?php if ($data[0][0]['ingresses_all']) echo 'checked="checked"'; ?> /><?php __('All') ?>
                            </td>
                            <td class="align_right"><?php __('Terminating Trunk'); ?></td>
                            <td>
                                <select id="terminating_trunk" style="width:400px; height:200px;" name="egresses[]" multiple="multiple" class="validate[required]">
    <?php
    $egress_arr = explode(',', $data[0][0]['egresses']);
    ?>
    <?php foreach ($egresses as $egress): ?>
                                                        <option value="<?php echo $egress[0]['resource_id']; ?>" <?php if (in_array($egress[0]['resource_id'], $egress_arr) || $data[0][0]['egresses_all']) echo 'selected="selected"'; ?>><?php echo $egress[0]['alias']; ?></option>
    <?php endforeach; ?>
                                </select>
                                <input id="terminating_trunk_all" type="checkbox" name="egresses_all" <?php if ($data[0][0]['egresses_all']) echo 'checked="checked"'; ?> />All
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr>
                <td colspan="2">
                    <table class="list table table-condensed">
                        <tr>
                            <td class="align_right"><?php __('Fields'); ?></td>
                            <td>
                                <select id="columns_select" multiple="multiple" style="width:200px;height:300px;">
    <?php //foreach ($back_selects as $key => $back_select): ?>
                                                        <option value="<?php //echo $key ?>"><?php //echo $back_select ?></option>
    <?php //endforeach; ?>
                                </select>
                            </td>
                            <td class="value value4">
                                <input type="button" class="input in-submit in-button btn" value="<?php __('Add') ?>" onclick="DoAdd();" style="width: 70px;margin-left: 0px;">
                                <br><br>
                                <input type="button" class="input in-submit in-button btn" value="<?php __('Delete') ?>" onclick="DoDel();" style="margin-left: 0px;">
                            </td>
                            <td>

                                <select id="columns" name="fields[]" multiple="multiple" style="width:200px;height:300px;" class="validate[required]">
    <?php
    foreach ($default_fields as $key => $field):
        if (!empty($default_fields)):
            ?>
                                                                            <option value="<?php echo $key ?>"><?php echo $field; ?></option>
            <?php
        endif;
    endforeach;
    ?>
                                </select>
                            </td>
                            <td class="value value4">
                                <input type="button" value="<?php __('up') ?>" onclick="moveOption('select2', 'up');" style="width: 65px; margin-left: 0px;" class="input in-submit in-button btn">
                                <br><br>
                                <input type="button" value="<?php __('Down') ?>" onclick="moveOption('select2', 'down');" style="margin-left: 0px;" class="input in-submit in-button btn">
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr>
                <td class="align_right"><?php __('Include Headers'); ?></td>
                <td>
                    <select name="contain_headers">
                        <option value="true" <?php if ($data[0][0]['contain_headers']) echo 'selected="selected"' ?>><?php __('Yes') ?></option>
                        <option value="false" <?php if (!$data[0][0]['contain_headers']) echo 'selected="selected"' ?>><?php __('No') ?></option>
                    </select>
                </td>
            </tr>

            <tr>
                <td class="align_right"><?php __('Headers') ?></td>
                <td>
                    <textarea name="headers" style="width:600px;"><?php echo $data[0][0]['headers'] ?></textarea>
                </td>
            </tr>

    <?php
    /*
      <tr>
      <td colspan="2">
      <table class="list">
      <tr>
      <td class="align_right"><?php __('Originating Carrier');  ?></td>
      <td>
      <select style="width:400px; height:200px;" name="ingress_carriers[]" multiple="multiple">
      <?php
      $ingress_carrier_arr = explode(',', $data[0][0]['ingress_carriers']);
      ?>
      <?php foreach($ingress_carriers as $ingress_carrer): ?>
      <option value="<?php echo $ingress_carrer[0]['client_id']; ?>" <?php if(in_array($ingress_carrer[0]['client_id'], $ingress_carrier_arr)) echo 'selected="selected"'; ?>><?php echo $ingress_carrer[0]['name']; ?></option>
      <?php endforeach; ?>
      </select>
      <input type="checkbox" name="ingress_carriers_all" <?php if ($data[0][0]['ingress_carriers_all']) echo 'checked="checked"'; ?> />All
      </td>
      <td class="align_right"><?php __('Terminating Carrier');  ?></td>
      <td>
      <select style="width:400px; height:200px;" name="egress_carriers[]" multiple="multiple">
      <?php
      $egress_carrier_arr = explode(',', $data[0][0]['egress_carriers']);
      ?>
      <?php foreach($egress_carriers as $egress_carrer): ?>
      <option value="<?php echo $egress_carrer[0]['client_id']; ?>" <?php if(in_array($egress_carrer[0]['client_id'], $egress_carrier_arr)) echo 'selected="selected"'; ?>><?php echo $egress_carrer[0]['name']; ?></option>
      <?php endforeach; ?>
      </select>
      <input type="checkbox" name="egress_carriers_all" <?php if ($data[0][0]['egress_carriers_all']) echo 'checked="checked"'; ?> />All
      </td>
      </tr>
      </table>
      </td>
      </tr>
     */
    ?>
            <tr>
                <td class="align_right"><?php __('Duration') ?></td>
                <td>
                    <select name="duration">
                        <option value="0" <?php if ($data[0][0]['duration'] == 0) echo 'selected="selected"'; ?>><?php __('All') ?></option>
                        <option value="1" <?php if ($data[0][0]['duration'] == 1) echo 'selected="selected"'; ?>><?php __('Non-zero') ?></option>
                        <option value="2" <?php if ($data[0][0]['duration'] == 2) echo 'selected="selected"'; ?>><?php __('Zero') ?></option>
                    </select>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <table class="list table table-condensed">
                        <tr>
                            <td class="align_right"><?php __('Originating Release Cause'); ?></td>
                            <td>
                                <select id="ingress_release_cause" name="ingress_release_cause[]" multiple="multiple" style="width:100%;height:200px;">
                                    <option value=""><?php __('all') ?></option>
                                    <option value="200"><?php __('success') ?></option>
                                    <option value="300"><?php __('multiple') ?></option>
                                    <option value="301"><?php __('moved permanently') ?></option>
                                    <option value="302"><?php __('moved temporaily') ?></option>
                                    <option value="305"><?php __('use proxy') ?></option>
                                    <option value="380"><?php __('alternative service') ?></option>
                                    <option value="400"><?php __('bad request') ?></option>
                                    <option value="401"><?php __('unauthorized') ?></option>
                                    <option value="402"><?php __('payment required') ?></option>
                                    <option value="403"><?php __('forbidden') ?></option>
                                    <option value="404"><?php __('not found') ?></option>
                                    <option value="405"><?php __('method no allowed') ?></option>
                                    <option value="406"><?php __('not acceptable') ?></option>
                                    <option value="407"><?php __('proxy authentication required') ?></option>
                                    <option value="408"><?php __('request timeout') ?></option>
                                    <option value="410"><?php __('gone') ?></option>
                                    <option value="413"><?php __('request entity too large') ?></option>
                                    <option value="414"><?php __('request-url too long') ?></option>
                                    <option value="415"><?php __('unsupported media type') ?></option>
                                    <option value="416"><?php __('unsupported url scheme') ?></option>
                                    <option value="420"><?php __('bad extension') ?></option>
                                    <option value="421"><?php __('extension required') ?></option>
                                    <option value="423"><?php __('interval too brief') ?></option>
                                    <option value="480"><?php __('temporarily unavailable') ?></option>
                                    <option value="481"><?php __('call/transaction does not exist') ?></option>
                                    <option value="482"><?php __('loop detected') ?></option>
                                    <option value="483"><?php __('too many hops') ?></option>
                                    <option value="484"><?php __('address incomplete') ?></option>
                                    <option value="485"><?php __('ambiguous') ?></option>
                                    <option value="486"><?php __('busy here') ?></option>
                                    <option value="487"><?php __('request terminated') ?></option>
                                    <option value="488"><?php __('not acceptable here') ?></option>
                                    <option value="491"><?php __('request pending') ?></option>
                                    <option value="493"><?php __('undecipherable') ?></option>
                                    <option value="500"><?php __('server internal error') ?></option>
                                    <option value="501"><?php __('not implemented') ?></option>
                                    <option value="502"><?php __('bad gateway') ?></option>
                                    <option value="503"><?php __('service unavailable') ?></option>
                                    <option value="504"><?php __('server time-out') ?> </option>
                                    <option value="505"><?php __('version not supported') ?> </option>
                                    <option value="513"><?php __('message too large') ?> </option>
                                    <option value="600"><?php __('busy everywhere') ?> </option>
                                    <option value="603"><?php __('decline') ?> </option>
                                    <option value="604"><?php __('does not exist anywhere') ?> </option>
                                    <option value="606"><?php __('not acceptable') ?> </option>
                                </select>
                            </td>
                            <td class="align_right"><?php __('Terminating Release Cause'); ?></td>
                            <td>
                                <select id="egress_release_cause" name="egress_release_cause[]" multiple="multiple" style="width:100%;height:200px;">
                                    <option value=""><?php __('all') ?></option>
                                    <option value="200"><?php __('success') ?></option>
                                    <option value="300"><?php __('multiple') ?></option>
                                    <option value="301"><?php __('moved permanently') ?></option>
                                    <option value="302"><?php __('moved temporaily') ?></option>
                                    <option value="305"><?php __('use proxy') ?></option>
                                    <option value="380"><?php __('alternative service') ?></option>
                                    <option value="400"><?php __('bad request') ?></option>
                                    <option value="401"><?php __('unauthorized') ?></option>
                                    <option value="402"><?php __('payment required') ?></option>
                                    <option value="403"><?php __('forbidden') ?></option>
                                    <option value="404"><?php __('not found') ?></option>
                                    <option value="405"><?php __('method no allowed') ?></option>
                                    <option value="406"><?php __('not acceptable') ?></option>
                                    <option value="407"><?php __('proxy authentication required') ?></option>
                                    <option value="408"><?php __('request timeout') ?></option>
                                    <option value="410"><?php __('gone') ?></option>
                                    <option value="413"><?php __('request entity too large') ?></option>
                                    <option value="414"><?php __('request-url too long') ?></option>
                                    <option value="415"><?php __('unsupported media type') ?></option>
                                    <option value="416"><?php __('unsupported url scheme') ?></option>
                                    <option value="420"><?php __('bad extension') ?></option>
                                    <option value="421"><?php __('extension required') ?></option>
                                    <option value="423"><?php __('interval too brief') ?></option>
                                    <option value="480"><?php __('temporarily unavailable') ?></option>
                                    <option value="481"><?php __('call/transaction does not exist') ?></option>
                                    <option value="482"><?php __('loop detected') ?></option>
                                    <option value="483"><?php __('too many hops') ?></option>
                                    <option value="484"><?php __('address incomplete') ?></option>
                                    <option value="485"><?php __('ambiguous') ?></option>
                                    <option value="486"><?php __('busy here') ?></option>
                                    <option value="487"><?php __('request terminated') ?></option>
                                    <option value="488"><?php __('not acceptable here') ?></option>
                                    <option value="491"><?php __('request pending') ?></option>
                                    <option value="493"><?php __('undecipherable') ?></option>
                                    <option value="500"><?php __('server internal error') ?></option>
                                    <option value="501"><?php __('not implemented') ?></option>
                                    <option value="502"><?php __('bad gateway') ?></option>
                                    <option value="503"><?php __('service unavailable') ?></option>
                                    <option value="504"><?php __('server time-out') ?> </option>
                                    <option value="505"><?php __('version not supported') ?> </option>
                                    <option value="513"><?php __('message too large') ?> </option>
                                    <option value="600"><?php __('busy everywhere') ?> </option>
                                    <option value="603"><?php __('decline') ?> </option>
                                    <option value="604"><?php __('does not exist anywhere') ?> </option>
                                    <option value="606"><?php __('not acceptable') ?> </option>
                                </select>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td class="align_right"><?php __('File Type'); ?></td>
                <td>
                    <select name="file_type">
                        <option value="1" <?php if ($data[0][0]['file_type'] == 1) echo 'selected="selected"' ?>><?php __('gz') ?></option>
                        <option value="2" <?php if ($data[0][0]['file_type'] == 2) echo 'selected="selected"' ?>><?php __('tar.gz') ?></option>
                        <option value="3" <?php if ($data[0][0]['file_type'] == 3) echo 'selected="selected"' ?>><?php __('tar.bz2') ?></option>
                    </select>
                </td>
            </tr>
        </tbody>-->
    <table class="list list-form table table-condensed">
        <tfoot>
        <td colspan="2" class="button-groups center">
            <input type="submit" value="<?php __('Submit') ?>" class="btn btn-primary" />
            <input type="reset" value="<?php __('Revert') ?>" class="btn btn-inverse" />
        </td>
        </tfoot>
    </table>
</form>

<script type="text/javascript">
    $(function() {
        $("#originating_trunk_all").click(function() {
                if ($(this).is(':checked')) {
                    $("#originating_trunk").children().attr('selected', 'selected');
                } else {
                    $("#originating_trunk").children().removeAttr('selected');
                }
        });
        $("#terminating_trunk_all").click(function() {
            if ($(this).is(':checked')) {
                $("#terminating_trunk").children().attr('selected', 'selected');
            } else {
                $("#terminating_trunk").children().removeAttr('selected');
            }

        });

        $("#ingress_release_cause option:first, #egress_release_cause option:first").click(function() {
            $(this).closest('select').children().attr('selected', 'selected');
        });

        $('#myform').submit(function() {
            $('#columns option').attr('selected', true);
        });

<?php
$ingress_release_causes = explode(',', $data[0][0]['ingress_release_cause']);
$egress_release_causes = explode(',', $data[0][0]['egress_release_cause']);
foreach ($ingress_release_causes as $ingress_release_cause):
    ?>
            $('#ingress_release_cause option[value="<?php echo $ingress_release_cause; ?>"]').attr('selected', true);
    <?php
endforeach;
foreach ($egress_release_causes as $egress_release_cause):
    ?>
            $('#egress_release_cause option[value="<?php echo $egress_release_cause; ?>"]').attr('selected', true);
    <?php
endforeach;
?>
    });
</script>