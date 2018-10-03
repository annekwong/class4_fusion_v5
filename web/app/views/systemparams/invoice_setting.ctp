<style type="text/css">
    .tags {cursor:pointer;color:red;}
    .table th, .table td{padding: 8px 20px;}

    select, textarea, input[type="text"] {
        margin-bottom: 0;
    }

    th .btn-primary, th .btn-primary:hover {
        background: #7FAF00;
    }
    .ms-container ul.ms-list{
        width: 280px;
    }
    .ms-container{
        background: transparent url('<?php echo $this->webroot; ?>common/theme/scripts/plugins/forms/multiselect/img/switch.png') no-repeat 290px 80px;
    }
</style>

<ul class="breadcrumb">
    <li><?php __('You are here'); ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>systemparams/invoice_setting">
        <?php __('Configuration') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>systemparams/invoice_setting">
        <?php echo __('Invoice Setting'); ?></a></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Invoice Setting'); ?></h4>
    <div class="buttons pull-right">
    </div>
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>


<div class="innerLR">

    <div class="widget widget-heading-simple widget-body-white">
        <div class="widget-body">
            <form method="post" enctype="multipart/form-data">
                <div class="widget">
                    <div class="widget-head"><h4 class="heading"><?php __('You may specify how your Invoice should be generated')?></h4></div>
                    <div class="widget-body">
                        <table class="table table-bordered">
                            <tbody>
                            <tr>
                                <td class="align_right"><?php __('Invoice Logo')?> </td>
                                <td>
                                    <span id="invoice_log_img"><img src="<?php echo $logo; ?>" width="120px" height="45px" /></span>
                                    <input id="invoice_log" type="file" name="logoimg" />
                                    <br>
                                    Web Path: <a href="<?php echo $logo; ?>"><?php echo FULL_BASE_URL . $logo; ?></a> <br>
                                    Root Path: <?php echo $root_path; ?>
                                </td>
                            </tr>
                            <tr>
                                <td class="align_right"><?php __('Invoice Number Convention')?> </td>
                                <td>
                                    <input type="text" class="validate[custom[onlyNumberSp]]" name="invoice_name" style="width:220px;" value="<?php echo $data[0][0]['invoice_name'] ?>" />
                                </td>
                            </tr>
                            <tr>
                                <td class="align_right"><?php __('Decimal Place')?> </td>
                                <td>
                                    <input type="text" class="validate[required,custom[integer]]" maxlength="1" name="invoice_decimal_digits" style="width:220px;" value="<?php echo $data[0][0]['invoice_decimal_digits'] ?>" />
                                </td>
                            </tr>
                            <tr>
                                <td class="align_right"><?php __('Mail Send Mode')?> </td>
                                <td>
                                    <select name="send_mode">
                                        <option value="0" <?php if ($data[0][0]['invoice_send_mode'] == 0) echo 'selected="seleced"' ?>><?php __('Attachment')?></option>
                                        <option value="1" <?php if ($data[0][0]['invoice_send_mode'] == 1) echo 'selected="seleced"' ?>><?php __('Link')?></option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="align_right"><?php __('Overlap Invoice Protection')?> </td>
                                <td>
                                    <input type="checkbox" name="overlap_invoice_protection" <?php if ($data[0][0]['overlap_invoice_protection']) echo 'checked="checked"'; ?> />
                                </td>
                            </tr>
                            <tr>
                                <td class="align_right"><?php __('Billing Details Location')?> </td>
                                <td>
                                    <select name="tpl_number">
                                        <option value="2" <?php  echo $data[0][0]['tpl_number'] == 2 ? 'selected':'' ?>><?php __('middle')?></option>
                                        <option value="0" <?php  echo $data[0][0]['tpl_number'] == 0 ? 'selected':'' ?>><?php __('bottom')?></option>
                                        <option value="1" <?php  echo $data[0][0]['tpl_number'] == 1 ? 'selected':'' ?>><?php __('top')?></option>
                                    </select>
                                </td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td class="align_right"><?php __('Billing Details')?> </td>
                                <td>
                                    <textarea class="validate[required]" style="height:150px;width:450px;" id="pdf_tpl" name="pdf_tpl" wrap="virtual"><?php echo $data[0][0]['pdf_tpl'] ?></textarea>
                                </td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td class="align_right"><?php __('Company Info Location')?> </td>
                                <td>
                                    <select name="company_info_location">
                                        <option value="0" <?php  echo $data[0][0]['company_info_location'] == 0 ? 'selected':'' ?>><?php __('top')?></option>
                                        <option value="1" <?php  echo $data[0][0]['company_info_location'] == 1 ? 'selected':'' ?>><?php __('left')?></option>
                                    </select>
                                </td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td class="align_right"><?php __('Company Info')?> </td>
                                <td>
                                    <textarea class="validate[required]" style="height:150px;width:450px;" id="company_info" name="company_info" wrap="virtual"><?php echo $data[0][0]['company_info'] ?></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td class="align_right"><?php __('Invoice CDR Fields')?></td>
                                <td>
                                    <?php
                                    $send_cdr_fields = explode(',', $data[0][0]['send_cdr_fields']);
                                    ?>
                                    <select name="cdr_fields[]" multiple="multiple" class="multiselect" id="invoice_cdr_fields">
                                        <?php unset($cdr_fields['callduration_in_ms']); ?>
                                        <?php foreach($cdr_fields as $cdr_field_key=>$cdr_field): ?>
                                            <option value="<?php echo $cdr_field_key; ?>" <?php if (in_array($cdr_field_key, $send_cdr_fields)) echo 'selected="selected"' ?>><?php echo $cdr_field ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                            </tr>
                            </tbody>
                            <tfoot>
                            <tr>
                                <td colspan="2" class="button-groups center">
                                    <input type="submit" value="<?php __('Submit')?>" class="btn btn-primary" />
                                    <input type="reset" value="<?php __('Revert') ?>" class="btn btn-inverse" />
                                </td>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

            </form>
        </div>

    </div>
</div>
<script src="<?php echo $this->webroot; ?>ajaxupload/swfupload.js"></script>
<script src="<?php echo $this->webroot; ?>ajaxupload/jquery-asyncUpload-0.1.js"></script>
<script type="text/javascript" src="<?php echo $this->webroot ?>js/ckeditor_full/ckeditor.js"></script>
<script type="text/javascript">
    $(function(){
        CKEDITOR.replace("pdf_tpl");
        CKEDITOR.replace("company_info");
        $('#invoice_cdr_fields').multiSelect({
            selectableHeader: "<div class='custom-header'>Optional Selection</div>",
            selectionHeader: "<div class='custom-header'>Selected Selection</div>"
        });

        $("#invoice_log").makeAsyncUploader({
            upload_url: '<?php echo $this->webroot ?>uploads/async_upload_img/ilogo_tmp',
            flash_url: '<?php echo $this->webroot; ?>ajaxupload/swfupload.swf',
            button_image_url: '<?php echo $this->webroot; ?>ajaxupload/blankButton.png',
            post_params: {"PHPSESSID": "<?php echo session_id(); ?>"},
            file_size_limit: '1024 MB',
            file_types: "*.png;*.jpg;*.bmp;*.jpeg",
            file_types_description: "Only img file",
            button_text: "<font face='Arial' size='13pt'>&nbsp;&nbsp;Change</font>",
            upload_success_handler: function(file, response) {
                var container = $('#content');
//                $("input[name$=_filename]", container).val(file.name);
                $("input[name$=_guid]", container).val(1);
                var tmp_img_path = "<?php echo $this->webroot . 'upload' . DS . 'images'.DS.'tmp'; ?>/ilogo_tmp.png?d=" + new Date();
                var img_html = "<img src='"+tmp_img_path+"' width='120px' height='45px' />";
                $("#invoice_log_img").html(img_html);
            }
        });

        $("input[type=reset]").closest('form').on('reset', function(){
            setTimeout(function(){
                $('#invoice_cdr_fields').multiSelect('refresh');
            });
            for (instance in CKEDITOR.instances){
                CKEDITOR.instances[instance].setData($("#"+instance).val());
                var test = CKEDITOR.instances[instance].updateElement();
            }
        });
    });
</script>
