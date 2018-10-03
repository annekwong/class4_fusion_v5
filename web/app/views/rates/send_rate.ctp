<?php
$subjectHeaders = array(
    'trunk_name', 'effective_date'
);
$contentHeaders = array(
    'company_name', 'allowed_ip', 'prefix', 'switch_ip', 'download_deadline', 'trunk_name', 'rate_filename',
    'billing_type', 'rounding_digits', 'cps_limit', 'channel_limit', 'IP_info_table', 'download_link'
);
?>

<ul class="breadcrumb">
    <li><?php __('You are here'); ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Switch') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Rate Table'); ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Send Rate'); ?></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __("Rate Table [%s]",false , $rate_table_name); ?></h4>

</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
    <a class="btn btn-icon btn-inverse glyphicons circle_arrow_left" href="javascript:history.back(-1);"><i></i><?php __('Back') ?></a>
</div>
<div class="clearfix"></div>
<div class="innerLR">

    <div class="widget widget-heading-simple widget-body-white">
        <div class="widget-body">
            <form method="post" id="send_rate_from" action="<?php echo $this->webroot; ?>rates/send_rate_record/<?php echo $ereturn_url?>" >
                <input type="hidden" name="rate_table_id" value="<?php echo $rate_table_id; ?>" />
                <table class="form table dynamicTable tableTools table-bordered  table-white">
                    <colgroup>
                        <col width="25%">
                        <col width="65%">
                        <col width="10%">
                    </colgroup>
                    <tbody>
                    <tr>
                        <td class="right"><?php __('Rate deck format') ?></td>
                        <td colspan="2">
                            <select name="format">
                                <option value="1" <?php if ($preservedData['format'] == 1) {echo 'selected="selected"';} ?> ><?php __('CSV') ?></option>
                                <option value="2" <?php if ($preservedData['format'] == 2) {echo 'selected="selected"';} ?> ><?php __('XLS') ?></option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td class="right"><?php __('Zipped') ?></td>
                        <td colspan="2">
                            <input type="checkbox" name="zipped" <?php if ($preservedData['zipped'] == 't') {echo 'checked';} ?>/>
                        </td>
                    </tr>
                    <tr>
                        <td class="right"><?php __('Effective Date') ?></td>
                        <td colspan="2">
                            <input class="input in-text wdate " value="<?php echo $preservedData['start_effective_date']; ?>" type="text" readonly="" onfocus="WdatePicker({dateFmt: 'yyyy-MM-dd'});" name="start_effective_date">
                        </td>
                    </tr>
                    <?php if(!empty($rate_email_template)): ?>
                        <tr>
                            <td class="right"><?php __('Rate Email Template') ?></td>
                            <td colspan="2">
                                <?php echo $xform->input('email_template',array('name' => 'email_template','type'=>'select',
                                    'class' =>'email_template','options' => $rate_email_template,'default' => $preservedData['start_effective_date'] != '' ? $preservedData['start_effective_date'] : 'save_temporary')); ?>
                                <a href="javascript:void(0)" title="<?php __('notify of send rate by template'); ?>"><i class="icon-question-sign"></i></a>
                            </td>
                        </tr>
                    <?php endif; ?>
                    <tr>
                        <td class="right"><?php __('Download method') ?></td>
                        <td colspan="2">
                            <?php
                            $download_method = array(
                                1 => __('Send as attachment',true),
                                2 => __('Send as link',true),
                            );
                            echo $form->input('download_method',array('type' => 'select','label' => false,
                                'div' => false,'options' => $download_method,'id' => 'DownloadMethod')); ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="right"><?php __('Send Type') ?></td>
                        <td colspan="2">
                            <?php
                            $send_type_arr = array(
                                0 => __('Send to carriers using this rate table',true),
                                1 => __('Specify my own recipients',true),
                            );
                            echo $form->input('send_type',array('type' => 'select','label' => false,
                                'div' => false,'options' => $send_type_arr,'id' => 'SendType','name' => 'send_type')); ?>
                            <?php echo $form->input('send_specify_email',array('label' => false, 'style' => 'display:none;width:60%;max-width:60%',
                                'div' => false,'id' => 'sendSpecifyEmail','class' => 'validate[required,custom[email_chars]]','name' => 'send_specify_email')); ?>
                        </td>
                    </tr>
                    <tr class="DownloadDeadlineTr">
                        <td class="right"><?php __('Download Deadline') ?></td>
                        <td colspan="2">
                            <input class="input in-text wdate validate[required]" value="" type="text" readonly="" onfocus="WdatePicker({dateFmt: 'yyyy-MM-dd',minDate:'<?php echo date('Y-m-d'); ?>'});" name="download_deadline">
                        </td>
                    </tr>
                    <tr class="DownloadDeadlineTr">
                        <td style="text-align: center;" rowspan="2">
                            Actions to take when rate is not downloaded before the deadline
                        </td>
                        <td >
                            <?php __('Send Reminder Email') ?> <input id="is_email_alert" type="checkbox" name="is_email_alert" checked/>
                        </td>
                    </tr>
                    <tr class="DownloadDeadlineTr">
                        <td >
                            <?php __('Disable The Trunk') ?>
                            <input id="is_disable" type="checkbox" name="is_disable" />
                        </td>
                    </tr>
                    <tr>
                        <td class="right"><?php __('Headers') ?></td>
                        <td colspan="2">
                            <?php echo $form->input('headers',array('type' => 'select','multiple' => true,'label' => false,
                                'div' => false,'options' => $schema,'selected' => $default_fields,'class'=>'validate[required]')); ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="right"><?php __('From email') ?></td>
                        <td colspan="2">
                            <select name="data[email_from]" id="rate_from">
                                <option value="default"><?php __('Default') ?></option>
                                <?php foreach ($mail_senders as $mail_sender): ?>
                                    <option value="<?php echo $mail_sender[0]['id'] ?>"><?php echo $mail_sender[0]['email'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td class="right"><?php __('Email CC') ?></td>
                        <td colspan="2">
                            <input type="text" name="data[email_cc]" id="email_cc" class="width220"
                                   value="<?php echo $preservedData['email_cc']; ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td class="right"><?php echo __('subject') ?></td>
                        <td><input class="input validate[required]" type="text" name="data[subject]"   id="email_subject" value="<?php echo $preservedData['subject']; ?>" style="width: 90%;max-width: none;" /></td>
                        <td class="mail_subject_tags">
                            <h4><i class="icon-tags"></i>Tags:</h4>
                            <?php
                            foreach ($subjectHeaders as $item) {
                                ?>
                                <span class="btn btn-block btn-default">{<?php echo $item; ?>}</span>
                                <?php
                            }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="right"><?php echo __('content') ?></td>
                        <td >
                            <textarea class="input in-textarea rate_content validate[required]" name="data[content]" style="height: 100px; font-family: monospace; font-size: 12px;width:800px;" id="rate_content"><?php echo $preservedData['content']; ?></textarea>
                        </td>
                        <td class="mail_content_tags">
                            <h4><i class="icon-tags"></i><?php __('Tags'); ?>:</h4>
                            <?php
                            foreach ($contentHeaders as $item) {
                                ?>
                                <span class="btn btn-block btn-default<?php if($item == 'download_deadline') echo ' download_deadline_tag';?>">{<?php echo $item; ?>}</span>
                                <?php
                            }
                            ?>
                        </td>
                        </td>
                    </tr>
                    </tbody>
                </table>

                <div id="form_footer" class="button-groups center separator">

                    <input class="input in-submit btn btn-primary" id="from_submit" value="<?php echo __('submit') ?>" type="button">

                    <input class="input in-button btn btn-inverse" value="<?php echo __('reset') ?>" type="reset"   style="margin-left: 20px;">
                    <input class="input in-button btn btn-primary" id="saveTemplateChanges" value="<?php echo __('Save Changes') ?>" style="margin-left: 20px;">
                    <input class="input in-button btn btn-primary" id="saveTemplate" value="<?php echo __('Save as New Template') ?>" style="margin-left: 20px;">
                    <a href="#myModalMailInfo" data-toggle="modal" class="hide myModalMailInfo"></a>
                </div>
                <div id="myModalMailInfo" class="modal hide" style="width:1000px; margin-left:-500px;">
                    <div class="modal-header" style=" border-bottom: 2px solid #000; padding-bottom: 10px;">
                        <button data-dismiss="modal" class="close" type="button">&times;</button>
                        <h3><?php __('Carriers using this rate table'); ?></h3>
                    </div>
                    <div class="modal-body">
                        <select id="status">
                            <option value=""><?php __('All') ?></option>
                            <option value="true" ><?php __('Enabled') ?></option>
                            <option value="false"><?php __('Disabled') ?></option>
                        </select>
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th><input type="checkbox" checked onclick="checkAll(this,'use_info_tboby');" /></th>
                                <th><?php __('Carrier Name'); ?></th>
                                <th><?php __('Trunk Name'); ?></th>
                                <th><?php __('Prefix'); ?></th>
                                <th><?php __('Enabled'); ?></th>
                                <th><?php __('Email'); ?></th>
                            </tr>
                            </thead>
                            <tbody id="use_info_tboby">
                            <?php foreach ($use_info as $use_info_item): ?>
                                <tr>
                                    <td><input type="checkbox" checked value="<?php echo $use_info_item[0]['resource_id']; ?>" name="resource_id[]" /></td>
                                    <td><?php echo $use_info_item[0]['name']; ?></td>
                                    <td><?php echo $use_info_item[0]['alias']; ?></td>
                                    <td><?php echo $use_info_item[0]['tech_prefix']; ?></td>
                                    <td><?php echo $use_info_item[0]['active']; ?></td>
                                    <td>
                                        <input type="hidden" value="<?php echo $use_info_item[0]['client_id']; ?>" name="client_info[client_id][]" />
                                        <input type="hidden" value="<?php echo $use_info_item[0]['resource_id'].'::'.$use_info_item[0]['rate_email'] ?>" class="client_checkbox" name="client_emails[]" />
                                        <?php echo $form->input('email',array('label' => false, 'style' => 'width:90%;max-width:90%',
                                            'div' => false,'class' => 'validate[custom[email_chars]] email_input',
                                            'value' => $use_info_item[0]['send_mail'],'name' => 'client_info[rate_email][]')); ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <input type="button" class="btn btn-primary sub_button" value="<?php echo 'Submit'; ?>">
                        <a href="javascript:void(0)" data-dismiss="modal" class="btn btn-inverse"><?php echo 'Reset'; ?></a>
                    </div>

                </div>

            </form>

        </div>
    </div>
</div>
<script type="text/javascript" src="<?php echo $this->webroot ?>js/ckeditor_full/ckeditor.js"></script>
<link href="<?php echo $this->webroot; ?>js/multiselect2side/jquery.multiselect2side.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $this->webroot; ?>js/multiselect2side/jquery.multiselect2side.js"></script>
<script type="text/javascript" >
    CKEDITOR.replace('rate_content');
    var $rate_from = $("#rate_from");
    var $email_subject = $("#email_subject");
    var $rate_content = $("#rate_content");
    var $email_cc = $("#email_cc");

    function m2s_lang() {
        $(".ms2side__div").remove();
        $("#headers").multiselect2side({
            labelTop: '<?php __('Top'); ?>',
            labelBottom: '<?php __('Bottom'); ?>',
            labelUp: '<?php __('Up'); ?>',
            labelDown: '<?php __('Down'); ?>',
            labelSort:'<?php __('Sort'); ?>',
            labelsx: '<?php __('Selectable items'); ?>',
            labeldx: '<?php __('Selection items'); ?>',

        });
    }

    $(function() {
        $('select#status').select2({
            minimumResultsForSearch: Infinity
        });

        $('select#status').on('change', function(){
            let status = $(this).val();
            let rate_table_id = '<?php echo $rate_table_id;?>';
            filterResource(rate_table_id, status);
        })

        function filterResource(rate_table_id, status){
            $.ajax({
                url: '<?php echo $this->webroot; ?>rates/filterResource/' + rate_table_id + '/'+ status,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if(response.status){
                        let tbody = $('#use_info_tboby');
                        let row = "";
                        let tbody_html = "";
                        tbody.html('');
                        $.each(response.data, function (i, obj) {
                            row = "";
                            row += "<td><input type='checkbox' checked value='" +obj[0]['resource_id']+ "' name='resource_id[]' /></td>";
                            row += "<td>" +obj[0]['name'] + "</td>";
                            row += "<td>" +obj[0]['alias'] + "</td>";
                            row += "<td>" +obj[0]['tech_prefix'] + "</td>";
                            row += "<td>" +obj[0]['active'] + "</td>";
                            row += "<td><input type='hidden' value='" +obj[0]['client_id']+ "' name='client_info[client_id][]' />";
                            row += "<input type='hidden' value='" +obj[0]['resource_id']+"::"+ obj[0]['rate_email']+ "' class='client_checkbox' name='client_emails[]' />";
                            row += "<input type='text' style = 'width:90%;max-width:90%' value='" +obj[0]['send_mail']+ "' class = 'validate[custom[email_chars]] email_input' name='client_info[rate_email][]' /></td>";
                            tbody_html+="<tr>" + row + "</tr>";
                        });
                        tbody.html(tbody_html);
                    }
                }
            });
        }

        $('#send_rate_from').on('reset', function(){
            var $this_textarea_id = $(this).find('textarea').attr('id');
            var editor = CKEDITOR.instances[$this_textarea_id];
            editor.setData('');
        })


        $("#SendType").change(function(){
            var select_value = $(this).val();
            if (select_value == 1){
                $("#sendSpecifyEmail").show();
                $(".DownloadDeadlineTr").hide();
            }else{
                $("#sendSpecifyEmail").val('').hide();
                if ($('#DownloadMethod').val() == 2){
                    $(".DownloadDeadlineTr").show();
                }
            }
        }).trigger('change');

        $("#saveTemplate").click(function(){

            bootbox.prompt("Enter new template name?", function(result) {
                if (result === null) {

                } else if (result === '') {
                    bootbox.alert('Name can not be empty!');
                } else {
                    $.post("<?php echo $this->webroot; ?>rates/save_new_template",
                        {
                            download_method: $('#DownloadMethod').val(),
                            headers: $('#datams2side__dx\\[\\]').val(),
                            email_from: $('#rate_from').val(),
                            email_cc: $('#email_cc').val(),
                            subject: $('#email_subject').val(),
                            content: $('#rate_content').val(),
                            name: result
                        }
                    ).success(function() { bootbox.alert('Template saved successfully!') })
                }
            });

        });

        $("#saveTemplateChanges").click(function(){
            let temp_id = $('#email_template').val();
            let temp_name = $('#email_template option:selected').text();
            bootbox.confirm("Save Changes For Template ["+temp_name+"].", function(result) {
                if(result){
                    $.post("<?php echo $this->webroot; ?>rates/save_template_changes",
                        {
                            id: temp_id,
                            download_method: $('#DownloadMethod').val(),
                            headers: $('#datams2side__dx\\[\\]').val(),
                            email_from: $('#rate_from').val(),
                            email_cc: $('#email_cc').val(),
                            subject: $('#email_subject').val(),
                            content: CKEDITOR.instances['rate_content'].getData() ? CKEDITOR.instances['rate_content'].getData() :  $('#rate_content').val(),
                            name: result
                        },
                        function(result) {
                            if(result.status){
                                jGrowl_to_notyfy('Template saved successfully!', {theme: 'jmsg-success'});
                            }else{
                                jGrowl_to_notyfy('Template save failed!', {theme: 'jmsg-error'});
                            }

                        },
                        'json'
                    )
                }
            });

        });

        $("#from_submit").click(function(){
            $(".selectedOption").find('option').attr('selected',true);
            if($("#send_rate_from").validationEngine('validate') == false){
                return false;
            }
            if ($("#SendType").val() == 0){
                $("a.myModalMailInfo").click();
            }else{
                $("#send_rate_from").submit();
            }
        });

        $("#myModalMailInfo").find('.sub_button').click(function(){
            var flg = true;
            $(".email_input").each(function(){
                if ($(this).validationEngine('validate')){
                    flg = false;
                    return false;
                }
            });
            if (flg == false){
                return false;
            }
            $("#send_rate_from").submit();

        });

        $("#send_rate_from").submit(function(){
            if ($('#DownloadMethod').val() == 2) {
                let content = CKEDITOR.instances['rate_content'].getData();

                if (content.indexOf('{download_link}') < 0) {
                    jGrowl_to_notyfy('Please add tag {download_link} into email content.', {theme: 'jmsg-error'});
                    return false;
                }
            }
        });


        $(".email_template").live('change',function(){
            var template_id = $(this).find("option:selected").val();
            $rate_from.val('default');
            $email_subject.val('');
            CKEDITOR.instances['rate_content'].setData('');
            $email_cc.val('');
            if(template_id != 'save_temporary'){
                $('#saveTemplateChanges').show();
                $.ajax({
                    url: '<?php echo $this->webroot; ?>rates/ajax_get_rate_email_template/' + template_id,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        if(data){
                            $rate_from.val(data.email_from);
                            $email_subject.val(data.subject);
                            CKEDITOR.instances['rate_content'].setData(data.content);
                            $email_cc.val(data.email_cc);

                            $('#DownloadMethod').val(data.download_method).trigger('change');

                            $("#headers option").removeAttr('selected');
                            if(data.headers){
                                var header_arr = data.headers.split(',');
                                $.each(header_arr,function(n,header_key) {
                                    $("#headers").find("option[value='"+header_key+"']").attr('selected',true);
                                });
                            }
                            m2s_lang();
                            // $("#headers").multiselect2side('refresh');
                            // m2s_lang();
                        }
                    }
                });
            }else{
                $('#saveTemplateChanges').hide();
            }
        });

//        $("#headers").multiSelect({
//            selectableHeader: "<div class='custom-header'><?php //__('Selectable items'); ?>//</div>",
//            selectionHeader: "<div class='custom-header'><?php //__('Selection items'); ?>//</div>"
//        });

        $("#DownloadMethod").change(function(){
            var select_value = $(this).val();
            if (select_value == 1){
                $(".DownloadDeadlineTr").hide();
                $(".download_deadline_tag").hide();

            }else if($('#SendType').val() != 1){
                $(".DownloadDeadlineTr").show();
                $(".download_deadline_tag").show();

            }

        }).trigger('change');

        $('.mail_content_tags').find('span').click(function(){
            var $tag_value = $(this).html();
            var $this_textarea_id = $(this).closest('tr').find('textarea').attr('id');
            var editor = CKEDITOR.instances[$this_textarea_id];
            editor.insertHtml( $tag_value );
        });
        $('.mail_subject_tags').find('span').click(function(){
            var $tag_value = $(this).html();
            var $subj_input = $(this).closest('tr').find('input');
            $subj_input.val( $subj_input.val() + $tag_value );
        });

        $(".email_template").change();

    });

    function switch_alert(){
        var check_value = $('#is_email_alert').is(':checked');
        if (!check_value){
            $("#email_alert_date").hide();
        }else{
            $("#email_alert_date").show();
        }
    }

    $(document).ready(function () {
        m2s_lang();
    })

</script>