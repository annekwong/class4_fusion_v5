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
    <li><a href="<?php echo $this->webroot ?>prresource/gatewaygroups/view_egress">
            <?php __('Routing') ?></a></li>
    <?php if (isset($_GET['query']['id_clients'])): ?>
        <li class="divider"><i class="icon-caret-right"></i></li>
        <li><a href="<?php echo $this->webroot ?>prresource/gatewaygroups/view_ingress">
            <?php echo __('Carrier') . ' [' . $c[$_GET['query']['id_clients']] . '] ';?></li>
    <?php endif;?>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>prresource/gatewaygroups/view_ingress">
            <?php __('Ingress Trunk') ?></a></li>
    <li><a href="<?php echo $this->webroot ?>prresource/gatewaygroups/view_ingress">
            <?php __('Send Rate') ?></a></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php
        $str ='';
        foreach($rate_table_names as $key => $rate_table_name) $str .= $rate_table_name.',';
        $str = trim($str, ',');
        echo __("Send Rate[%s]",false , $str); ?>
    </h4>

</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
    <a class="btn btn-icon btn-inverse glyphicons circle_arrow_left" href="javascript:history.back(-1);"><i></i><?php __('Back') ?></a>
</div>
<div class="clearfix"></div>
<div class="innerLR">

    <div class="widget widget-heading-simple widget-body-white">
        <div class="widget-body">
            <form method="post" id="send_rate_from" action="<?php echo $this->webroot; ?>prresource/gatewaygroups/send_rate_records/" >
                <?php foreach ($rate_table_ids as $rate_table_id) {?>
                    <input type="hidden" name="rate_table_ids[]" value="<?php echo $rate_table_id; ?>" />
                <?php } ?>
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
                                <option value="1" selected="selected" ><?php __('CSV') ?></option>
                                <option value="2" ><?php __('XLS') ?></option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td class="right"><?php __('Zipped') ?></td>
                        <td colspan="2">
                            <input type="checkbox" name="zipped" />
                        </td>
                    </tr>
                    <tr>
                        <td class="right"><?php __('Effective Date') ?></td>
                        <td colspan="2">
                            <input class="input in-text wdate " value="" type="text" readonly="" onfocus="WdatePicker({dateFmt: 'yyyy-MM-dd'});" name="start_effective_date">
                        </td>
                    </tr>
                    <?php if(!empty($rate_email_template)): ?>
                        <tr>
                            <td class="right"><?php __('Rate Email Template') ?></td>
                            <td colspan="2">
                                <?php echo $xform->input('email_template',array('name' => 'email_template','type'=>'select',
                                    'class' =>'email_template','options' => $rate_email_template,'default' => 'save_temporary')); ?>
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
                                'div' => false,'options' => $download_method,'id' => 'DownloadMethod','name'=>'download_method') ); ?>
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
                        <td class="right"><?php __('Mail Alert Carrier') ?></td>
                        <td colspan="2">
                            <input id="is_email_alert" type="checkbox" name="is_email_alert" checked/>

                        </td>
                    </tr>
                    <tr class="DownloadDeadlineTr">
                        <td class="right"><?php __('Disable Ingress Trunk') ?></td>
                        <td colspan="2">
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
                            <input type="text" name="data[email_cc]" id="email_cc" class="width220" />
                        </td>
                    </tr>

                    <tr>
                        <td class="right"><?php echo __('subject') ?></td>
                        <td><input class="input validate[required]" type="text" name="data[subject]"   id="email_subject" value="" style="width: 90%;max-width: none;" /></td>
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
                            <textarea class="input in-textarea rate_content validate[required]" name="data[content]" style="height: 100px; font-family: monospace; font-size: 12px;width:800px;" id="rate_content"></textarea>
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
                    <a href="#myModalMailInfo" data-toggle="modal" class="hide myModalMailInfo"></a>
                </div>
                <div id="myModalMailInfo" class="modal hide" style="width:1000px; margin-left:-500px;">
                    <div class="modal-header">
                        <button data-dismiss="modal" class="close" type="button">&times;</button>
                        <h3><?php __('Carriers using this rate table'); ?></h3>
                    </div>
                    <div class="modal-body">
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
                            <?php foreach ($rate_table_ids as $id): ?>
                                <?php foreach ($use_infos[$id] as $use_info_item): ?>
                                    <tr>
                                        <td><input type="checkbox" checked value="<?php echo $use_info_item[0]['resource_id']; ?>" name="resource_id[<?=$id?>][]" /></td>
                                        <td><?php echo $use_info_item[0]['name']; ?></td>
                                        <td><?php echo $use_info_item[0]['alias']; ?></td>
                                        <td><?php echo $use_info_item[0]['tech_prefix']; ?></td>
                                        <td><?php echo $use_info_item[0]['active']; ?></td>
                                      <td>
                                          <input type="hidden" value="<?php echo $use_info_item[0]['client_id']; ?>" name="client_info[client_id][<?=$id?>][]" />
                                          <input type="hidden" value="<?php echo $use_info_item[0]['resource_id'].'::'.$use_info_item[0]['rate_email'] ?>" class="client_checkbox" name="client_emails[<?=$id?>][]" />
                                          <?php echo $form->input('email',array('label' => false, 'style' => 'width:90%;max-width:90%',
                                              'div' => false,'class' => 'validate[custom[email_chars]] email_input',
                                              'value' => $use_info_item[0]['send_mail'],'name' => 'client_info[rate_email]['.$id.'][]')); ?>
                                      </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <input type="button" class="btn btn-primary sub_button" value="<?php __('Submit'); ?>">
                        <a href="javascript:void(0)" data-dismiss="modal" class="btn btn-inverse"><?php __('Close'); ?></a>
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
    $(function() {

        $("#SendType").change(function(){
            var select_value = $(this).val();
            if (select_value == 1){
                $("#sendSpecifyEmail").show();
            }else{
                $("#sendSpecifyEmail").hide();
            }
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
                        }
                    }
                });
            }
        });

//        $("#headers").multiSelect({
//            selectableHeader: "<div class='custom-header'><?php //__('Selectable items'); ?>//</div>",
//            selectionHeader: "<div class='custom-header'><?php //__('Selection items'); ?>//</div>"
//        });

        $("#headers").multiselect2side({
            labelTop: '<?php __('Top'); ?>',
            labelBottom: '<?php __('Bottom'); ?>',
            labelUp: '<?php __('Up'); ?>',
            labelDown: '<?php __('Down'); ?>',
            labelSort:'<?php __('Sort'); ?>',
            labelsx: '<?php __('Selectable items'); ?>',
            labeldx: '<?php __('Selection items'); ?>',

        });

        $("#DownloadMethod").change(function(){
            var select_value = $(this).val();
            if (select_value == 1){
                $(".DownloadDeadlineTr").hide();

            }else{
                $(".DownloadDeadlineTr").show();

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