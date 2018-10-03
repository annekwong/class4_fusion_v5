<ul class="breadcrumb">
    <li><?php __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Finance') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Upload Vendor Invoice') ?></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Upload Vendor Invoice') ?></h4>
    <div class="buttons pull-right">
    </div>
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
    <?php echo $this->element('xback',array('backUrl' => 'pr/pr_invoices/view')); ?>
</div>
<div class="clearfix"></div>
<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-body">
            <form id="form1" class="form-inline" action="<?php echo $this->webroot?>uploads/analysis_file_head" method="POST" enctype="multipart/form-data">
                <div id="static_div" style="text-align: left; width: 530px;">
                    <table class="cols" style="width: 252px; margin: 0px auto;"></table>
                </div>
                <table class="cols table dynamicTable tableTools table-bordered  table-white" style="margin:0px auto;">
                    <colgroup>
                        <col width="37%">
                        <col width="63%">
                    </colgroup>
                    <tbody>
                        <tr>
                            <td style="text-align:right;padding-right:4px;" class="first"><?php echo __('Import File', true); ?>:</td>
                            <td style="text-align:left;" class="last">
                                <input id="myfile" type="file" name="file">
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align:right;padding-right:4px;" class="first"><?php echo __('Records with Duplicated ID', true); ?>:</td>
                            <td style="text-align:left;" class="last">
                                <input type="hidden" id="show_type" name="show_type" value="17" />
                                <input type="hidden" name="upload_type" value="<?php echo $url_upload_type; ?>" />
                                <input type="radio" name="duplicate_type" value="ignore" id="duplicate_type_ignore" class="">
                                <label for="duplicate_type_ignore"><?php echo __('Ignore', true); ?></label>			  
<!--					<input type="radio" name="duplicate_type" value="overwrite" id="duplicate_type_overwrite">
                                <label for="duplicate_type_overwrite"><?php echo __('Overwrite', true); ?></label>			  -->
                                <input type="radio" name="duplicate_type" value="delete" id="duplicate_type_delete" checked="checked">
                                <label for="duplicate_type_delete"><?php echo __('Overwrite', true); ?></label>
                            </td>
                        </tr>
<!--                        <tr>-->
<!--                            <td style="text-align:right;padding-right:4px;" class="first"></td>-->
<!--                            <td style="text-align:left;" class="last"   align="center"><span id="analysis_myfile" class="analysis" style="display:block;"></span></td>-->
<!--                        </tr>-->

                        <tr >
                            <td style="text-align:right;padding-right:4px;" class="first" >
                                <?php __("Include Header") ?>:
                            </td>
                            <td style="text-align:left;" class="last">
                                <input checked="checked" type="checkbox" name="with_header" class="border_no" id="include_header"><br>

                            </td>
                        </tr>
                        <tr id="from_line" style="display: none;">
                            <td style="text-align:right;padding-right:4px;" class="first">
                                <?php __("Starting From Line") ?>:
                            </td>
                            <td style="text-align:left;" class="last">
                                <select name="from_line">
                                    <?php for($i=1;$i<=20;$i++): ?>
                                    <option value="<?php echo $i?>"><?php echo $i?></option>
                                    <?php endfor;?>
                                </select>
                            </td>
                        </tr>
                        <tr id="date_format">
                            <td style="text-align:right;padding-right:4px;" class="first">
                                <?php __("Date Format") ?>:
                            </td>
                            <td style="text-align:left;" class="last">
                                <select name="date_format">
                                    <option value="mm/dd/yyyy">mm/dd/yyyy</option>
                                    <option value="yyyy-mm-dd">yyyy-mm-dd</option>
                                    <option value="dd-mm-yyyy">dd-mm-yyyy</option>
                                    <option value="dd/mm/yyyy">dd/mm/yyyy</option>
                                    <option value="yyyy/mm/dd">yyyy/mm/dd</option>
                                </select>
                            </td>
                        </tr>
                        <tr><td style="text-align:right;padding-right:4px;"><?php __('Example')?>:</td><td align="left"><a href="<?php echo $this->webroot ?>example/invoice.csv" target="_blank" id="show_example" title=""><?php __('show')?></a></td></tr>
                        <tr>
                            <td colspan="2" style="text-align:right;padding-right:4px;" class="first last"></td>
                        </tr>
                        <tr>
                            <td colspan="2" class="first last"><div class="submit center"><input type="submit" value="<?php echo __('upload', true); ?>" class="input in-submit btn btn-primary"></div></td>
                        </tr>	
                    </tbody>
                </table>
            </form>
        </div>
    </div>
</div>
<script src="<?php echo $this->webroot; ?>ajaxupload/swfupload.js"></script>
<script src="<?php echo $this->webroot; ?>ajaxupload/jquery-asyncUpload-0.1.js"></script>
<script type="text/javascript">
    $(function() {

        $("#myfile").makeAsyncUploader({
            upload_url: '<?php echo $this->webroot ?>uploads/async_upload',
            flash_url: '<?php echo $this->webroot; ?>ajaxupload/swfupload.swf',
            button_image_url: '<?php echo $this->webroot; ?>ajaxupload/blankButton.png',
            post_params: {"PHPSESSID": "<?php echo session_id(); ?>"},
            file_size_limit: '1024 MB',
            upload_success_handler: function(file, response) {
                response = $.trim(response);
                var flg = "myfile";
                var show_type = $("#show_type").val();
                var container = $('#content');
                $("#analysis").empty();
                $("input[name=" + flg + "_filename]", container).val(file.name);

                $("input[name=" + flg + "_guid]", container).val(response);
                $("input[name=flg]", container).val(flg);
//                $("#analysis_" + flg).html('<a id="analysis_a" target="_blank" href="<?php //echo $this->webroot; ?>//uploads/analysis_file/' + show_type + '/' + response + '">Show and modify</a>');
                //var href = '<?php echo $this->webroot; ?>uploads/analysis_file_head/' + show_type + '/' + response;alert('['+response);alert(href);
                //$("#form1").attr('href', href);
                $("span[id=" + flg + "_completedMessage]", container).html("Uploaded <b>{0}</b> ({1} KB)"
                        .replace("{0}", file.name)
                        .replace("{1}", (file.size / 1024).toFixed(3))
                        );
            }
        });
        $("#form1").submit(function() {
            var file = $("#myfile_completedMessage").html();
            if (!file)
            {
                jQuery.jGrowlError('You should select a file!');
                return false;
            } else {
                return true;
            }

        });
//        $("input[name='upload_type']").click(function() {
//            var flg = $(this).val();
//            var type = '';
//            $("#show_type").val('16');
//            var show_type = '16';
//            type = 'payment.csv';
//            var response = $("input[name=myfile_guid]").val();
//
//            switch (flg)
//            {
//                case '3' :
//                    type = 'invoice.csv';
//                    $("#show_type").val('17');
//                    show_type = '17';
//                    break;
//            }
//            //$("#analysis_a").attr('href', "<?php //echo $this->webroot; ?>//uploads/analysis_file/"+show_type+"/" + response);
//            var href = "<?php //echo $this->webroot ?>//example/" + type;
//            $("#show_example").attr('href', href);
//        });
        $("#include_header").click(function(){
            var is_from_line = $('#from_line');
            //var is_date_format = $('#date_format');
            if ($("#include_header").attr("checked")){
                is_from_line.show();
                //is_date_format.show();
            } else {
                is_from_line.hide();
                //is_date_format.hide();
            }
        }).trigger('click');
    });
</script>
