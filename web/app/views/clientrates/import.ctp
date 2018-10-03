
<script src="<?php echo $this->webroot; ?>ajaxupload/swfupload.js"></script>
<script src="<?php echo $this->webroot; ?>ajaxupload/jquery-asyncUpload-0.1.js"></script>
<style type="text/css">
    DIV.ProgressBar { width: 100px; padding: 0; border: 1px solid black; margin-right: 1em; height:.75em; margin-left:1em; display:-moz-inline-stack; display:inline-block; zoom:1; *display:inline; }
    DIV.ProgressBar DIV { background-color: Green; font-size: 1pt; height:100%; float:left; }
    SPAN.asyncUploader OBJECT { position: relative; top: 5px; left: 10px; }
    #info {
        margin:10px;
        width: 200px;
        padding:10px;
        margin:0 auto;
        display: none;
    }
    #info span,#info ul li a {
        color:red;
    }
    #analysis {padding-left:20px; text-decoration:underline;color:red;cursor:pointer;}
    #import_info span {color:red;padding-right:10px;}
    #end_date_time_exists, #end_date_time_all {display:none;}
    #myModal_errors .separator {
        padding: 5px 0;
        display: block;
        border-top: 1px solid #ccc;
    }
</style>

<ul class="breadcrumb">
    <li><?php __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Switch') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Editing Rates') ?> <font class="editname"> <?php echo empty($name[0][0]['name']) || $name[0][0]['name'] == '' ? '' : '[' . $name[0][0]['name'] . ']' ?> </font></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Import') ?></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Import') ?></h4>
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>


<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-head">
            <?php echo $this->element('downloads/rate_tabs',array('action' => 'import')) ?>
        </div>
        <div class="widget-body">
            <div id="import_panel" style="text-align:center;line-height: 30px; width: 100%; margin: 0 auto;">
                <form method="post" id="myform" action="<?php echo $this->webroot ?>clientrates/change_header/<?php echo$rate_table_id; ?>">
                    <table class="form footable table dynamicTable tableTools table-bordered  table-white default footable-loaded">
                        <tr>
                            <td class="align_right padding-r10">
                                <label><?php __('Import File')?>:</label></td>
                            <td align="left" style="padding-left:10px;">
                                <input type="file" id="myfile" name="myfile" />
                                <span id="analysis" style="display:block;">

                                </span>
                            </td>
                        </tr>
                        <tr class="merge_tr hide">
                            <td class="align_right padding-r10">
                                <label><?php __('Merge Country code and Destination Code')?>:</label>
                            </td>
                            <td><input type="checkbox" name="special_translate" /><br /></td>
                        </tr>
                        <tr class="merge_tr_son hide">
                            <td class="align_right padding-r10">
                                <label><?php __('Destination Code number of columns for a row')?>:</label>
                            </td>
                            <td><input type="text" name="area_code_col" class="validate[required,custom[integer]] width80" maxlength="2" /><br /></td>
                        </tr>
                        <tr class="merge_tr_son hide">
                            <td class="align_right padding-r10">
                                <label><?php __('Country Code number of columns for a row')?>:</label>
                            </td>
                            <td><input type="text" name="country_code_col" class="validate[custom[integer]] width80" maxlength="2"  /><br /></td>
                        </tr>
                        <?php if($rate_upload_templates): ?>
                            <tr>
                                <td class="align_right padding-r10">
                                    <label><?php __('Rate Upload Template')?>:</label>
                                </td>
                                <td align="left">
                                    <select id="rate_upload_template" name="rate_upload_template" >
                                        <option value=""><?php __('Specify New Upload Rule'); ?></option>
                                        <?php foreach($rate_upload_templates as $rate_upload_template_id => $rate_upload_template_name): ?>
                                            <option value="<?php echo $rate_upload_template_id; ?>">
                                                <?php echo $rate_upload_template_name; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>

                                    <a href="javascript:void(0)" title="<?php __('notify of rate upload by template'); ?>"><i class="icon-question-sign"></i></a>
                                </td>
                            </tr>
                        <?php endif; ?>
                        <tr class="no_template">
                            <td class="align_right padding-r10">
                                <label><?php __('For rate record with the same code and effective date is found')?>:</label>
                            </td>
                            <td align="left">
                                &nbsp;&nbsp;&nbsp;&nbsp;
                                <!--<input type="radio" name="method" value="0" checked="checked" /> Ignore-->
                                <input type="radio" name="method" value="1" checked="checked" /> <?php __('Delete Existing Records')?>
                                <input type="radio" name="method" value="0" /> <?php __('End-Date Existing Records')?>
                                <input type="radio" name="method" value="2" /> <?php __('End-Date All Records')?>
                            </td>
                        </tr>

                        <tr class="no_template" id="end_date_time_exists">
                            <td class="align_right padding-r10">
                                <label><?php __('End Date Time')?>:</label>
                            </td>
                            <td align="left" style="padding-left:10px;">
                                <input class="in-text" type="text" id="end_date" name="exist_end_date" value="<?php echo date("Y-m-d 23:59:59"); ?>" onfocus="WdatePicker({dateFmt: 'yyyy-MM-dd HH:mm:ss'});" />
                                <?php echo $form->input('exist_end_date_tz',array('type' => 'select','options' => $appCommon->get_timezone_arr(),'div' => false,'label' => false,
                                    'name' => 'exist_end_date_tz','selected' => '+0000')); ?>
                            </td>
                        </tr>

                        <tr class="no_template" id="end_date_time_all">
                            <td class="align_right padding-r10">
                                <label><?php __('End Date Time')?>:</label>
                            </td>
                            <td align="left" style="padding-left:10px;">
                                <input class="in-text" type="text" id="end_date_all" name="all_end_date" value="<?php echo date("Y-m-d 23:59:59"); ?>" onfocus="WdatePicker({dateFmt: 'yyyy-MM-dd HH:mm:ss'});" />
                                <?php echo $form->input('all_end_date_tz',array('type' => 'select','options' => $appCommon->get_timezone_arr(),'div' => false,'label' => false,
                                    'name' => 'all_end_date_tz','selected' => '+0000')); ?>
                            </td>
                        </tr>


                        <tr class="no_template">
                            <td class="align_right padding-r10">
                                <label><?php __('Effective Date Format')?>:</label>
                            </td>
                            <td align="left" style="padding-left:10px;">
                                <select name="effective_date_format">
                                    <option value="mm/dd/yyyy">mm/dd/yyyy</option>
                                    <option value="yyyy-mm-dd">yyyy-mm-dd</option>
                                    <option value="dd-mm-yyyy">dd-mm-yyyy</option>
                                    <option value="dd/mm/yyyy ">dd/mm/yyyy</option>
                                    <option value="yyyy/mm/dd">yyyy/mm/dd</option>
                                </select>
                            </td>
                        </tr>
                        <tr class="no_template">
                            <td class="align_right padding-r10">
                                <label><?php __('File With Header')?>:</label>
                            </td>
                            <td align="left" style="padding-left:10px;">
                                <input type="checkbox" name="with_header" checked="checked" /><br />

                            </td>
                        </tr>

                        <tr class="no_template">
                            <td class="align_right padding-r10">
                                <label><?php __('Append Prefix')?>:</label>
                            </td>
                            <td align="left" style="padding-left:10px;">
                                <input type="checkbox" name="append_prefix" />&nbsp;&nbsp;&nbsp;&nbsp;
                                <input type="text" name="append_prefix_value" maxlength="10" class="width80" style="display: none;" />
                            </td>
                        </tr>

                        <tr class="no_template">
                            <td class="align_right padding-r10">
                                <label><?php __('Default Min Time and Interval')?>:</label>
                            </td>
                            <td align="left" style="padding-left:10px;">
                                <input type="checkbox" name="default_min_time" />&nbsp;&nbsp;&nbsp;&nbsp;
                                <div style="display: none;">
                                    <label><?php __('Default Min Time')?>:</label>
                                    <input type="text" name="default_min_time_value" maxlength="10" class="width80" />
                                    <br>
                                    <label><?php __('Default Interval')?>:</label>
                                    <input type="text" name="default_interval_value" maxlength="10" class="width80" />
                                </div>
                            </td>
                        </tr>

                        <?php if ($code_deck_id): ?>
                            <tr class="no_template">
                                <td class="align_right padding-r10">
                                    <label><?php __('Code Name Matching')?>:</label>
                                </td>
                                <td align="left" style="padding-left:10px;">
                                    <select name="code_name_match" >
                                        <option value="0" ><?php __('None')?></option>
                                        <option value="1" ><?php __('Re-populate Country and Code Name with Selected Code Deck')?></option>
                                        <!--option value="2" ><?php __('Re-populate Country and Code Name with Selected Code Deck if not available')?></option-->
                                    </select>

                                </td>
                            </tr>
                        <?php endif; ?>
                        <!--
                        <tr class="no_template">
                            <td class="align_right padding-r10">
                                <label><?php __('Check Effective Date Criteria')?>:</label>
                            </td>
                            <td align="left" style="padding-left:10px;">
                                <input type="checkbox" class="check_effective" name="check_effective" checked="checked" /><br />
                            </td>
                        </tr>

                        <tr class="no_template check_effective_flg">
                            <td class="align_right padding-r10" rowspan="2">
                                <label><?php __('Minimum Effective Date Requirement for')?>:</label>
                            </td>
                            <td align="left" style="padding-left:10px;">
                                <?php __('Rate Increase'); ?>:
                                <input type="text" class="validate[required,custom[integer]] width15" maxlength="2" name="rate_increase_days" />
                                <?php __('days'); ?>
                            </td>
                        </tr>
                        <tr class="no_template check_effective_flg">
                            <td align="left" style="padding-left:10px;">
                                <?php __('New Code'); ?>:
                                <input type="text" class="validate[required,custom[integer]] width15" maxlength="2" name="new_code_days" />
                                <?php __('days'); ?>
                            </td>
                        </tr>

                        <tr class="no_template check_effective_flg">
                            <td class="align_right padding-r10">
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
                        -->
                        <!--                        <tr class="no_template check_effective_flg">-->
                        <!--                            <td align="left" style="padding-left:10px;">-->
                        <!--                                --><?php //__('Send Error Notification to'); ?><!--:-->
                        <!--                                <select name="send_error_email_to">-->
                        <!--                                    <option value="0">--><?php //__('None'); ?><!--</option>-->
                        <!--                                    <option value='1'>--><?php //__('Carrier Rate Contact'); ?><!--</option>-->
                        <!--                                    <option value='2'>--><?php //__('Switch’s Rate Contact'); ?><!--</option>-->
                        <!--                                </select>-->
                        <!--                            </td>-->
                        <!--                        </tr>-->
                        <!--
                        <tr>
                            <td class="align_right padding-r10">
                                <?php __('Background Job Count')?>
                            </td>
                            <td align="left">
                                <span class="badge" style="margin-left: 5px;">
                                    <?php echo $uploading_count ?>
                                </span>
                                <a href="<?php echo $this->webroot ?>clientrates/clean_queue/<?php echo $rate_table_id ?>" title="Clean all">
                                    <span class="icon-remove"></span>
                                </a>
                            </td>
                        </tr>
-->

                        <tr>
                            <td class="align_right padding-r10">
                                <?php __('Example File Format Available')?><a href="<?php echo $example ?>" target="_blank" title="click to download"><?php __('here')?></a>&nbsp;&nbsp;&nbsp;
                            </td>
                            <td align="left">
                                <input type="submit" id="import_btn" value="<?php __('Upload')?>" class="input in-submit btn btn-primary" />
                            </td>
                        </tr>
                </form>
                </table>
                <div id="import_info">

                </div>
            </div>
            <br />

        </div>
    </div>
</div>
</div>


<link rel="stylesheet" type="text/css" href="<?php echo $this->webroot ?>easyui/themes/default/easyui.css">
<script type="text/javascript" src="<?php echo $this->webroot ?>easyui/jquery.easyui.min.js"></script>
<script src="<?php echo $this->webroot ?>js/jquery.jgrowl.js" type="text/javascript"></script>
<script type="text/javascript">
    function check_effective(obj){
        var checked = obj.is(":checked");
        if(checked){
            $(".check_effective_flg").show();
        }else{
            $(".check_effective_flg").hide();
        }
    }
    $(function() {

        $(".merge_tr").find(":checkbox").click(function(){
            var checked = $(this).is(":checked");
            if (checked){
                $('.merge_tr_son').show();
            }else{
                $('.merge_tr_son').hide();
            }
        });


        <?php if($rate_upload_templates): ?>
        $("#rate_upload_template").change(function(){
            if($(this).val() != 0)
            {
                var post_action = "<?php echo $this->webroot; ?>clientrates/import_with_template/<?php echo $rate_table_id; ?>";
                $("#myform").attr('action',post_action);
                $(".no_template").hide();
            }
            else
            {
                var post_action = "<?php echo $this->webroot; ?>clientrates/change_header/<?php echo $rate_table_id; ?>";
                $(".no_template").show();
                check_effective($(".check_effective"));
                dup_effective_format($('input[name=method]:checked'));
                $("#myform").attr('action',post_action);
            }

        }).trigger('change');
        <?php endif; ?>

        $(".check_effective").click(function(){
            check_effective($(this));
        });

        var $import_info = $('#import_info');
        var $showdlg = $('#showdlg');
        var status_err = ["Error!", "Running...", "Insert...", "Update...", "Delete...", "Done!"];
        status_err['-1'] = "Waiting...";
        status_err['-2'] = "End Date..."
        var downlog_baseurl = "<?php echo $this->webroot ?>clientrates/down_import_log/";

        function checkStatus() {
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: "<?php echo $this->webroot ?>clientrates/checkstatus",
                data: {rate_table_id: "<?php echo $rate_table_id; ?>"},
                success: function(data, textStatus) {
                    if (data != '') {
                        //$('#import_panel').hide();
                        $('#info').show();
                        $('#status').text(status_err[data['status']]);

                        if (data['status'] == 0) {
                            $('#img_status').html('<img src="<?php echo $this->webroot ?>images/rate-error.png" title="Failure" />');
                        } else if (data['status'] == 5) {
                            $('#img_status').html('<img src="<?php echo $this->webroot ?>images/rate-success.png" title="Success" />');
                        } else {
                            $('#img_status').html('<img src="<?php echo $this->webroot ?>images/rate-progress.gif" title="In Progress" />');
                        }

                        $('#delete_queue').text(data['delete_queue']);
                        $('#update_queue').text(data['update_queue']);
                        $('#insert_queue').text(data['insert_queue']);
                        $('#error_counter').text(data['error_counter']);
                        $('#reimport_counter').text(data['reimport_counter']);
                        $('#error_log_file').attr('href', downlog_baseurl + data['id'] + '/' + 'error_log_file');
                        $('#reimport_log_file').attr('href', downlog_baseurl + data['id'] + '/' + 'reimport_log_file');
                        $('#import_time').text(data['time']);
                    }
                }
            });
        }

        function checkUploading()
        {
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: '<?php echo $this->webroot ?>clientrates/checkuploading',
                data: {rate_table_id: "<?php echo $rate_table_id; ?>"},
                success: function(data) {
                    $import_info.html('Waiting:<span>' + data.waiting + '</span>End Date:<span>' + data.ending_date + '</span>In Progress:<span><a href="###" id="showdlg">' + data.progressing + '</a></span>');
                }
            });
        }
        /*
         window.setInterval(checkStatus, 3000);
         window.setInterval(checkUploading, 3000);
         checkUploading();

         $('#dlg').window({
         width:300,
         height:300,
         modal:false,
         title: 'In Progress',
         closed: true
         });

         $showdlg.live('click', function() {
         $('#dlg').window('open');
         });
         */

        String.prototype.endsWith = function(s) {
            return this.length >= s.length && this.substr(this.length - s.length) == s;
        }

        $("#myfile").makeAsyncUploader({
            upload_url: '<?php echo $this->webroot ?>clientrates/upload',
            flash_url: '<?php echo $this->webroot; ?>ajaxupload/swfupload.swf',
            button_image_url: '<?php echo $this->webroot; ?>ajaxupload/blankButton.png',
            file_size_limit: '1024 MB',
            post_params: {"PHPSESSID": "<?php echo session_id(); ?>"},
            upload_success_handler: function(file, response) {
                response = JSON.parse(response);

                if (response.code == 201) {
                    $("#analysis").empty();
                    var container = $("#content");
                    $("input[name$=_filename]", container).val(file.name);
                    var file_type = file.name.replace(/.+\./, "");
                    if (file.name.endsWith('xlsx')) {
                        $('select[name=effective_date_format] option[value=yyyy-mm-dd]').attr('selected', true);
                    }
                    if (file_type == 'xls' || file_type == 'xlsx'){
                        $(".merge_tr").show();
                    }else{
                        $(".merge_tr").hide();
                        $(".merge_tr_son").hide();
                        $(".merge_tr").find(":checkbox").checked = false;
                    }
                    $("input[name$=_guid]", container).val(response.msg);
                    $("span[id$=_completedMessage]", container).html("Uploaded <b>{0}</b> ({1} KB)"
                        .replace("{0}", file.name)
                        .replace("{1}", Math.round(file.size / 1024))
                    );
                } else {
                    jGrowl_to_notyfy(response.msg, {theme: 'jmsg-error'});
                }
            }
        });

        var $end_date_time_exists = $('#end_date_time_exists');
        var $end_date_time_all = $('#end_date_time_all');
        var $default_value = $('#default_value');
        var $default_value_panel = $('#default_value_panel');
        $end_date_time_exists.hide();
        $end_date_time_all.hide();

        function dup_effective_format(obj){
            var method = obj.val();
            if (method == '1') {
                $('#end_date_time_exists').hide();
                $('#end_date_time_all').hide();
            } else if (method == '2') {
                $('#end_date_time_exists').show();
                $('#end_date_time_all').hide();
            } else {
                $('#end_date_time_exists').hide();
                $('#end_date_time_all').show();
            }
        }

        $default_value.change(function() {
            if ($(this).attr('checked')) {
                $default_value_panel.show();
            } else {
                $default_value_panel.hide();
            }
        }).trigger('change');

        $('input[name=method]').change(function() {
            dup_effective_format($(this));
//            var method = $(this).val();
//            if (method == '1') {
//                $end_date_time_exists.hide();
//                $end_date_time_all.hide();
//            } else if (method == '2') {
//                $end_date_time_exists.show();
//                $end_date_time_all.hide();
//            } else {
//                $end_date_time_exists.hide();
//                $end_date_time_all.show();
//            }
        });
        $("input[name='append_prefix']").click(function(){
            var checked = $(this).is(":checked");
            if (checked){
                $(this).next().show();
            }else{
                $(this).next().hide();
            }
        });
        $("input[name='default_min_time']").click(function(){
            var checked = $(this).is(":checked");
            if (checked){
                $(this).next().show();
            }else{
                $(this).next().hide();
            }
        });
        $('#myform').submit(function() {

            var time = $('#end_date_all').val();

            var method = $('input[name=method]:checked').val();

            if (method == '0' && time == '')
            {
                jGrowl_to_notyfy("End Date can not be empty!", {theme: 'jmsg-error'});
                return false;
            }

            if ($('input[name=myfile_guid]').val() == '')
            {
                jGrowl_to_notyfy("You must upload file first!", {theme: 'jmsg-error'});
                return false;
            }
            if ($("input[name='append_prefix']").is(":checked")){
                var re = /^[0-9A-Za-z]+$/;
                var append_prefix_value = $("input[name='append_prefix_value']").val();
                if (!re.test(append_prefix_value)){
                    jGrowl_to_notyfy("<?php __('prefix must be number or A-Z ,a-z'); ?>", {theme: 'jmsg-error'});
                    return false;
                }
            }
        });


    });
</script>