<ul class="breadcrumb">
    <li><?php __('You are here'); ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Routing') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li>Carrier [<?php echo $client_name ?>]</li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li>Edit <?php echo ucfirst($type); ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li> <?php echo $this->element('title_name', array('name' => $name)); ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Upload')?></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php __('Upload Replace Action')?></h4>
    <div class="buttons pull-right">
    </div>
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>
<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-body">
            <form id="form1" class="form-inline" action="<?php echo $this->webroot ?>uploads/replace_action" method="POST" enctype="multipart/form-data">
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
                        <tr>
                            <td style="text-align:right;padding-right:4px;" class="first"><?php echo __('Duplicate', true); ?>:</td>
                            <td style="text-align:left;" class="last">
                                <input type="radio" name="duplicate_type" value="ignore" id="duplicate_type_ignore" class="">
                                <label for="duplicate_type_ignore"><?php echo __('Ignore', true); ?></label>			  
<!--					<input type="radio" name="duplicate_type" value="overwrite" id="duplicate_type_overwrite">
                                <label for="duplicate_type_overwrite"><?php echo __('Overwrite', true); ?></label>			  -->
                                <input type="radio" name="duplicate_type" value="delete" id="duplicate_type_delete" checked="checked">
                                <label for="duplicate_type_delete"><?php echo __('delete', true); ?></label>
                            </td>
                        </tr>
                        <tr><td colspan="2"  align="center"><span id="analysis_myfile" class="analysis" style="display:block;"></span></td></tr>
                        <tr><td align="right"><?php __('Example')?>:</td><td align="left"><a href="<?php echo $this->webroot ?>example/replace_action.csv" target="_blank" id="show_example" title="Show example file"><?php __('show')?></a></td></tr>
                        <tr>
                            <td style="text-align:right;padding-right:4px;" class="first last"></td>
                        </tr>
                        <tr>
                    <input type="hidden" name="resource_id" value="<?php echo $this->params ['pass'] [0]; ?>" />
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
        var flg = "myfile";
        var show_type = '19';
        $("#myfile").makeAsyncUploader({
            upload_url: '<?php echo $this->webroot ?>uploads/async_upload',
            flash_url: '<?php echo $this->webroot; ?>ajaxupload/swfupload.swf',
            button_image_url: '<?php echo $this->webroot; ?>ajaxupload/blankButton.png',
            post_params: {"PHPSESSID": "<?php echo session_id(); ?>"},
            file_size_limit: '1024 MB',
            upload_success_handler: function(file, response) {
                var container = $('#content');
                $("#analysis").empty();
                $("input[name=" + flg + "_filename]", container).val(file.name);

                $("input[name=" + flg + "_guid]", container).val(response);
                $("input[name=flg]", container).val(flg);
                $("#analysis_" + flg).html('<a target="_blank" href="<?php echo $this->webroot; ?>uploads/analysis_file/' + show_type + '/' + response + '">Show and modify</a>');
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

    });
</script>
