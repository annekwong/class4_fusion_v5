

<ul class="breadcrumb">
    <li><?php __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Configuration') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Upload ANI') ?></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Upload ANI') ?></h4>

    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>
<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-body">
            <form method="post" id="myform" style="text-align:center;">
                <!--                <div class="input-append">
                                    <div class="uneditable-input span3"><i class="icon-file fileupload-exists"></i> 
                                        <span class="fileupload-preview"></span>
                                    </div>
                                    <span class="btn btn-default btn-file">
                                        <span class="fileupload-new">Select file</span>
                                        <span class="fileupload-exists">Change</span>
                                        <input type="file" id="myfile" class="margin-none" name="file">
                                    </span>
                                    <a href="#" class="btn fileupload-exists" data-dismiss="fileupload">Remove</a>
                                </div>-->
                <div id="static_div" style="text-align: left; width: 530px;">
                    <table class="cols" style="width: 252px; margin: 0px auto;"></table>
                </div>
                <table class="cols" style="width:700px;margin:0px auto;">
                    <tbody>
                        <tr>
                            <td style="text-align:right;padding-right:4px;" class="first"><?php echo __('Import File', true); ?>:</td>
                            <td style="text-align:left;" class="last">
                                <input id="myfile" type="file" name="file">
                            </td>
                        </tr>
                        
                        <tr><td align="right"><?php __('Example')?>:</td><td align="left"><a href="<?php echo $this->webroot ?>example/ani.csv" target="_blank" id="show_example" title="Show example file"><?php __('show')?></a></td></tr>
                        <tr>
                            <td style="text-align:right;padding-right:4px;" class="first last"></td>
                        </tr>
                        <tr>
                            <td colspan="2" class="first last"><div class="submit center"><input type="submit" value="<?php echo __('upload', true); ?>" class="input in-submit btn btn-primary"></div></td>
                        </tr>
                </table>

            </form>
        </div>
    </div>
</div>

<script src="<?php echo $this->webroot; ?>ajaxupload/swfupload.js"></script>
<script src="<?php echo $this->webroot; ?>ajaxupload/jquery-asyncUpload-0.1.js"></script>

<script>
    $(function() {
        $("#myfile").makeAsyncUploader({
            upload_url: '<?php echo $this->webroot ?>uploads/async_upload',
            flash_url: '<?php echo $this->webroot; ?>ajaxupload/swfupload.swf',
            button_image_url: '<?php echo $this->webroot; ?>ajaxupload/blankButton.png',
            file_size_limit: '1024 MB',
            post_params: {"PHPSESSID": "<?php echo session_id(); ?>"},
            upload_success_handler: function(file, response) {
                $("input[name$=_filename]", container).val(file.name);
                $("input[name$=_guid]", container).val(response);
                $("span[id$=_completedMessage]", container).html("Uploaded <b>{0}</b> ({1} KB)"
                        .replace("{0}", file.name)
                        .replace("{1}", (file.size / 1024).toFixed(3))
                        );
            }
        });

        $('#myform').submit(function() {


            if ($('input[name=myfile_guid]').val() == '')
            {
                $.jGrowl("You must upload file first!", {theme: 'jmsg-error'});
                return false;
            }

        });
    });
</script>
