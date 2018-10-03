<?php
$subjectHeaders = array(
    'trunk_name', 'effective_date'
);
$contentHeaders = array(
    'company_name', 'allowed_ip', 'prefix', 'switch_ip', 'download_deadline', 'trunk_name', 'rate_filename',
    'billing_type', 'rounding_digits', 'cps_limit', 'channel_limit', 'IP_info_table'
);
?>


<ul class="breadcrumb">
    <li><?php __('You are here'); ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?= $this->webroot?>product_management/index">
            <?php __('Management') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?= $this->webroot?>product_management/index">
            <?php __('Product') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Send Rate'); ?></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __("Product [%s]",false , $product_name); ?></h4>

</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
    <?php echo $this->element('xback',array('backUrl' => 'product_management/index')); ?>
</div>
<div class="clearfix"></div>
<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-body">
            <form id="send_rate_from" method="post" action="<?php echo $this->webroot; ?>product_management/send_rate_record/<?php echo $product_id?>" >
                <input type="hidden" name="rate_table_id" value="<?php echo $rate_table_id; ?>" />
                <input type="hidden" name="product_id" id="product_id" value="<?php echo $product_id; ?>" />
                <input type="hidden" name="is_private" id="is_private" value="<?php echo $is_private; ?>" />
                <div class="widget">
                    <div class="widget-head"><h4 class="heading"><?php __('Send Rate') ?></h4></div>
                    <div class="widget-body">
                        <table class="form table dynamicTable tableTools table-bordered  table-white">
                            <colgroup>
                            </colgroup>
                            <tr>
                                <td style="width:18%;" class="right"><?php __('Rate deck format') ?></td>
                                <td colspan="2">
                                    <select name="format">
                                        <option value="1" <?php //if ($preservedData['format'] == 1) {echo 'selected="selected"';} ?> ><?php __('CSV') ?></option>
                                        <option value="2" <?php //if ($preservedData['format'] == 2) {echo 'selected="selected"';} ?> ><?php __('XLS') ?></option>
                                    </select>
                                </td>
                            </tr>
                            <!--tr>
                                <td class="align_right"><?php __('Product') ?></td>
                                <td colspan="2">
                                    <?php echo $form->input('product_id',array('type' => 'select','div' => false,'label' => false,'options' => $product_list,'selected' => $product_id)); ?>
                                </td>
                            </tr-->
                            <tr>
                                <td class="right"><?php __('Zipped') ?></td>
                                <td colspan="2">
                                    <input type="checkbox" name="zipped" <?php if ($preservedData['zipped'] == 't') {echo 'checked';} ?>/>
                                </td>
                            </tr>
                            <tr>
                                <td class="right"><?php __('Effective Date') ?></td>
                                <td colspan="2">
                                    <input class="input in-text wdate " value="<?php echo (isset($preservedData['start_effective_date']) && $preservedData['start_effective_date']) ? $preservedData['start_effective_date'] :  date('Y-m-d'); ?>" type="text" readonly="" onfocus="WdatePicker({dateFmt: 'yyyy-MM-dd'});" name="start_effective_date">
                                </td>
                            </tr>
                            <tr>
                                <td class="align_right"><?php __('Email Template') ?></td>
                                <td colspan="2">
                                    <?php echo $form->input('email_template',array('type' => 'select','div' => false,'label' => false,
                                        'options' => $rate_email_template,'class' => 'email_template')); ?>
                                    <a href="#myModalViewRateTemplate" class="view_rate_template" data-toggle="modal" title="<?php __('View'); ?>">
                                        <i class="icon-edit"></i>
                                    </a>
                                    <a href="#myModalAddRateTemplate" class="add_rate_template" data-toggle="modal" title="<?php __('Create New'); ?>">
                                        <i class="icon-plus"></i>
                                    </a>
                                    <a class="refreshEmailTemplate" href="javascript:void(0)" title="Refresh"><i class="icon-refresh"></i></a>
                                </td>
                            </tr>
                            <tr>
                                <td class="align_right"><?php __('Get Type') ?></td>
                                <td colspan="2">
                                    <?php
                                    $send_type_arr = array(
                                        0 => __('Send to carriers using this rate table',true),
                                        1 => __('Specify my own recipients',true),
                                    );
                                    echo $form->input('send_type',array('type' => 'select','label' => false,
                                        'div' => false,'options' => $send_type_arr,'id' => 'SendType','name' => 'send_type')); ?>
                                    <?php
                                    $select_carrier_type = array(
                                        0 => __('Active Trunk Only',true),
                                        1 => __('All',true),
                                        2 => __('Inactive Trunk Only',true),
                                    );
                                    echo $form->input('',array('type' => 'select','label' => false,
                                        'div' => false,'options' => $select_carrier_type,'id' => 'carrier_type','class' => 'carrier_info_btn')); ?>
                                    <?php echo $form->input('send_specify_email',array('label' => false, 'style' => 'display:none;width:60%;max-width:60%',
                                        'div' => false,'id' => 'sendSpecifyEmail','class' => 'validate[required,custom[email_chars]]','name' => 'send_specify_email')); ?>
                                </td>
                            </tr>
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
                            <tr class="DownloadDeadlineTr" style="display:none;">
                                <td class="right"><?php __('Download Deadline') ?></td>
                                <td colspan="2">
                                    <input class="input in-text wdate validate[required]" value="" type="text" readonly="" onfocus="WdatePicker({dateFmt: 'yyyy-MM-dd',minDate:'<?php echo date('Y-m-d'); ?>'});" name="download_deadline">
                                </td>
                            </tr>
                            <tr class="DownloadDeadlineTr" style="display:none;">
                                <td style="text-align: center;" rowspan="2">
                                    Actions to take when rate is not downloaded before the deadline
                                </td>
                                <td colspan="2">
                                    <?php __('Send Reminder Email') ?> <input id="is_email_alert" type="checkbox" name="is_email_alert" checked/>
                                </td>
                            </tr>
                            <tr class="DownloadDeadlineTr" style=" display:none;">
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
                                        <span class="btn btn-block btn-default" style="width:140px;">{<?php echo $item; ?>}</span>
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

                        </table>
                    </div>
                </div>
                <?php $have_div = isset($div) ? $div : true; ?>
                <?php if ($have_div): ?>
                <div class="form-buttons center separator">
                    <?php endif; ?>
                    <input class="btn btn-primary input in-submit" <?php if(isset($submit_id)): ?>id="<?php echo $submit_id; ?>"<?php endif; ?> type="submit"  value="<?php echo 'Submit'; ?>">
                    <input type="reset" value="<?php echo 'Reset'; ?>" class="btn btn-inverse" <?php if(isset($reset_id)): ?>id="<?php echo $reset_id; ?>"<?php endif; ?>>
                    <input style="margin:0 0 0 5px;" class="input btn btn-primary" id="saveTemplateChanges" value="<?php echo __('Save Changes') ?>" style="margin-left: 20px;">
                    <?php if ($have_div): ?>
                </div>
            <?php endif; ?>

                <a href="#myModalMailInfo" data-toggle="modal" class="hide myModalMailInfo"></a>
                <div id="myModalMailInfo" class="modal hide" style="width:1000px; margin-left:-500px;">
                    <div class="modal-header" style=" border-bottom: 2px solid #000; padding-bottom: 10px;">
                        <button data-dismiss="modal" class="close" type="button">&times;</button>
                        <h3><?php __('Carriers using this Product'); ?></h3>
                    </div>
                    <select id="status" style=" margin: 10px 0 0 10px;">
                        <option value=1><?php __('All') ?></option>
                        <option value=0 ><?php __('Enabled') ?></option>
                        <option value=2><?php __('Disabled') ?></option>
                    </select>
                    <div class="modal-body carrier_table_list">

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
<div id="myModalAddRateTemplate" class="modal hide" style="min-width: 1000px; margin-left: -500px;">
    <div class="modal-header">
        <button data-dismiss="modal" class="close" type="button">&times;</button>
        <h3><?php __('Add Rate Email Template'); ?></h3>
    </div>
    <div class="separator"></div>
    <div class="modal-body">
    </div>
    <div class="modal-footer">
        <input type="hidden" class="btn_class" />
        <input type="button" class="btn btn-primary sub" value="<?php __('Submit'); ?>">
        <a href="javascript:void(0)" data-dismiss="modal" class="btn btn-default"><?php __('Close'); ?></a>
    </div>

</div>

<div id="myModalViewRateTemplate" class="modal hide" style="min-width: 800px;">
    <div class="modal-header">
        <button data-dismiss="modal" class="close" type="button">&times;</button>
        <h3><?php __('Edit Rate Email Template'); ?></h3>
    </div>
    <div class="separator"></div>
    <div class="modal-body">
    </div>
    <div class="modal-footer">
        <input type="hidden" class="rate_template_id" />
        <input type="hidden" class="btn_class" />
        <input type="button" class="btn btn-primary sub" value="<?php __('Submit'); ?>">
        <a href="javascript:void(0)" data-dismiss="modal" class="btn btn-default"><?php __('Close'); ?></a>
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
        $('#send_rate_from').on('reset', function(){
            var $this_textarea_id = $(this).find('textarea').attr('id');
            var editor = CKEDITOR.instances[$this_textarea_id];
            editor.setData('');
        })

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
                            content: CKEDITOR.instances['rate_content'].getData() ? CKEDITOR.instances['rate_content'].getData() :  $('#rate_content').val()
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

        $(".in-submit").click(function(e){
            e.preventDefault();
            $(".selectedOption").find('option').attr('selected',true);
            if($("#send_rate_from").validationEngine('validate') == false){
                return false;
            }
            if ($("#SendType").val() == 0){
                $("a.myModalMailInfo").click();
                return ;
            }else{
                $("#send_rate_from").submit();
            }
        });

        $("#myModalMailInfo").find('.sub_button').click(function(){
            var flg = true;
            var $checked_size = $(".client_checkbox:checked").size();
            $(".email_input").each(function(){
                if ($(this).validationEngine('validate')){
                    flg = false;
                    return false;
                }
            });
            if ($checked_size == 0){
                jGrowl_to_notyfy('<?php __('Select at least one client'); ?>', {theme: 'jmsg-error'});
                flg = false;
                return false;
            }
            if (flg == false){
                return false;
            }
            $("#send_rate_from").submit();

        });


        $(".email_template").live('change',function(){
            var template_id = $(this).find("option:selected").val();
            $rate_from.val('default');
            $email_subject.val('');
            CKEDITOR.instances['rate_content'].setData('');
            $email_cc.val('');
            if(template_id != 0){
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
                            $(".ms2side__div").remove();
                            $("#headers option").removeAttr('selected');
                            if(data.headers){
                                var header_arr = data.headers.split(',');
                                $.each(header_arr,function(n,header_key) {
                                    $("#headers").find("option[value='"+header_key+"']").attr('selected',true);
                                });
                            }
                            $("#headers").multiselect2side('refresh');
                            m2s_lang();
                        }
                    }
                });
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

</script>



<script type="text/javascript" src="<?php echo $this->webroot; ?>js/jquery.base64.min.js"></script>
<script type="text/javascript">
    function getRateClientUserInfo(type){
        var $carrier_type = type ? type : $("#carrier_type").val();
        var $product_id = $("#product_id").val();
        var $is_private = $("#is_private").val();

        $.ajax({
            url: '<?php echo $this->webroot; ?>product_management/get_rate_client_user_info',
            data: {
                'product_id': $product_id,
                'carrier_type': $carrier_type,
                'is_private' : $is_private
            },
            method: "POST",
            success: function (response) {
                $(".carrier_table_list").html(response);
            }
        });
    }

    function refresh_rate_email_template( btn_class,default_selected,update_all ){
        $.ajax({
            'url': '<?php echo $this->webroot; ?>routestrategys/ajax_rate_email_template',
            'type': 'GET',
            'dataType': 'json',
            'success': function(data) {
                $("#email_template").empty();
                $("#email_template").data('value',btn_class);
                $.each(data, function(index, item) {
                    console.log('Default selected', default_selected)
                    if (default_selected !== undefined && default_selected && item[0]['id'] == default_selected.trim() ){
                        $("#email_template").append('<option selected value="' + item[0]['id'] + '">' + item[0]['name'] + '</option>');
                    }else{
                        $("#email_template").append('<option value="' + item[0]['id'] + '">' + item[0]['name'] + '</option>');
                    }
                });
            }
        });
    }

    $(function() {

        getRateClientUserInfo();

        $("#carrier_type").change(function () {
            getRateClientUserInfo();
        });

        $('select#status').on('change', function(){
            let type = $(this).val();
            getRateClientUserInfo(type);
        })

        $("#SendType").change(function(){
            var $send_type = $(this).val();
            if ($send_type == 1){
                $(".carrier_info_btn").hide();
                $("#sendSpecifyEmail").show();
                $(".specify_info_btn").show();
                $(".DownloadDeadlineTr").hide();
            }else{
                $(".carrier_info_btn").show();
                $("#sendSpecifyEmail").hide();
                $(".specify_info_btn").hide();
                if ($('#DownloadMethod').val() == 2){
                    $(".DownloadDeadlineTr").show();
                }
            }
        }).trigger('change');


        $(".refreshEmailTemplate").live('click',function(){
            var time = new Date().getTime();
            var btn_class = 'refreshRateTemplate'+time;
            $(this).addClass(btn_class);
            refresh_rate_email_template(btn_class,0,1);
        });

        $(".view_rate_template").live('click',function(){
            var rateTemplateId = $(this).siblings('select').val();
            var time = new Date().getTime();
            var btn_class = 'viewRateTemplate'+time;
            $(this).addClass(btn_class);
            $("#myModalViewRateTemplate").find('.rate_template_id').val(rateTemplateId);
            $("#myModalViewRateTemplate").find('.btn_class').val(btn_class);
            $("#myModalViewRateTemplate").find('.modal-body').load("<?php echo $this->webroot; ?>rate_email_template/add_template/"+$.base64.encode(rateTemplateId)+"?is_ajax=1");
        });

        $("#myModalViewRateTemplate").find('.sub').click(function(){
            var $this = $(this);
            var is_validate = $("#myModalViewRateTemplate").find('form').validationEngine('validate');
            if ( !is_validate ){
                return false;
            }
//            var $thisTemplate = $("#myModalViewRateTemplate").find('.rate_template_id');
            var rateTemplateId = $("#myModalViewRateTemplate").find('.rate_template_id').val();
            var btn_class = $("#myModalViewRateTemplate").find('.btn_class').val();
            var add_content_id = $("#myModalViewRateTemplate").find('.mail_content').attr('id');
            var this_content = CKEDITOR.instances[add_content_id].getData();
            $("#myModalViewRateTemplate").find('.mail_content').val(this_content);
            $.ajax({
                url: "<?php echo $this->webroot ?>rate_email_template/add_template/"+$.base64.encode(rateTemplateId)+"?is_ajax=1",
                type: 'post',
                dataType: 'text',
                data: $("#myModalViewRateTemplate").find('form').serialize(),
                success: function(data) {
                    if (data != 0)
                    {
                        $this.next().click();
                        refresh_rate_email_template(btn_class,data,1);
                        jGrowl_to_notyfy('<?php __('Edit success'); ?>', {theme: 'jmsg-success'});
                    }
                    else
                        jGrowl_to_notyfy('<?php __('Edit failed'); ?>', {theme: 'jmsg-error'});
                }
            });
        });

        $(".add_rate_template").live('click',function(){
            var rateTemplateId = $(this).siblings('select').val();
            var time = new Date().getTime();
            var btn_class = 'addRateTemplate'+time;
            $(this).addClass(btn_class);
            $("#myModalAddRateTemplate").find('.btn_class').val(btn_class);
            $("#myModalAddRateTemplate").find('.modal-body').load("<?php echo $this->webroot; ?>rate_email_template/add_template?is_ajax=1");
        });


        $("#myModalAddRateTemplate").find('.sub').click(function(){
            var $this = $(this);
            var is_validate = $("#myModalAddRateTemplate").find('form').validationEngine('validate');
            if ( !is_validate ){
                return false;
            }
            var btn_class = $("#myModalAddRateTemplate").find('.btn_class').val();
            var add_content_id = $("#myModalAddRateTemplate").find('.mail_content').attr('id');
            var this_content = CKEDITOR.instances[add_content_id].getData();
            $("#myModalAddRateTemplate").find('.mail_content').val(this_content);
            $.ajax({
                url: "<?php echo $this->webroot ?>rate_email_template/add_template?is_ajax=1",
                type: 'post',
                dataType: 'text',
                data: $("#myModalAddRateTemplate").find('form').serialize(),
                success: function(data) {
                    if (data != 0)
                    {
                        $this.next().click();
                        refresh_rate_email_template(btn_class,data,1);
                        jGrowl_to_notyfy('<?php __('Create success'); ?>', {theme: 'jmsg-success'});
                    }
                    else
                        jGrowl_to_notyfy('<?php __('Create failed'); ?>', {theme: 'jmsg-error'});
                }
            });
        });


    });

</script>
