<script src="<?php echo $this->webroot ?>js/ajaxTable.js" type="text/javascript"></script>

<ul class="breadcrumb">
    <li><?php echo __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Configuration') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('LRN Group Setting') ?></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('LRN Group Setting', true); ?>&nbsp;[<?php echo ( $lrn_group['LrnSetting']['name']) ?>]</h4>

    
</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
    <a class="btn btn-icon btn-inverse glyphicons circle_arrow_left" href="<?php echo $this->webroot ?>lrn_settings">
        <i></i>
        <?php __('Back')?>
    </a>
</div>
<div class="clearfix"></div>
<div class="innerLR">
    <div class="widget widget-tabs widget-body-white">
        <div class="widget-head">
            <ul>
                <li><a href="<?php echo $this->webroot; ?>lrn_settings/items/<?php echo base64_encode($lrn_group['LrnSetting']['id']); ?>" class="glyphicons left_arrow"><i></i><?php __('List'); ?></a></li>
                <li class="active"><a href="<?php echo $this->webroot; ?>lrn_settings/upload_items/<?php echo base64_encode($lrn_group['LrnSetting']['id']); ?>" class="glyphicons right_arrow"><i></i><?php __('Special Code'); ?></a></li>
            </ul>
        </div>
        <div class="widget-body">
            <div class="container">
                <div class="clearfix"></div>
                <form method="post" id="myform" action="<?php echo $this->webroot ?>lrn_settings/upload_items/<?php echo base64_encode($lrn_group['LrnSetting']['id']); ?>">
                    <div  id="static_div"   style="text-align: left; width: 530px;">
                        <table class="cols" style="width: 252px; margin: 0px auto;"  >
                            <?php if (isset($statistics) && $statistics) : ?>
                                <caption><?php echo __('Upload Statistics', true); ?>    

                                    <span style="color: red;;font-size:11px;"> </span>
                                </caption>
                                <?php foreach (array('success', 'failure', 'duplicate') as $col): ?>
                                    <?php if (isset($statistics[$col])): ?>
                                        <tr>
                                            <td style="text-align:right;padding-right:4px;"><?php echo Inflector::humanize($col) ?>:</td>
                                            <td style="text-align:left;color:red;"><?php echo $statistics[$col] ?></td>
                                        </tr>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                                <?php
                                if (isset($statistics['failure']) && $statistics['failure'] > 0 &&
                                        isset($statistics['error_file']) && !empty($statistics['error_file']) &&
                                        isset($statistics['log_id']) && $statistics['log_id'] > 0
                                ):
                                    ?>
                                    <tr>
                                        <td style="text-align:right;padding-right:4px;"><?php echo Inflector::humanize("error_file") ?>:</td>
                                        <td style="text-align:left;"><a href="<?php echo $this->webroot ?>uploads/download_error_file/<?php echo $statistics['log_id'] ?>"><?php echo __('download', true); ?></a></td>
                                    </tr>
                                <?php endif; ?>
                                <tr><td>&nbsp;</td><td></td></tr>
                                <tr><td>&nbsp;</td><td></td></tr>
                            <?php endif; ?>

                        </table>
                    </div>   
                    <table class="cols" style="margin:0px auto;">
                        <tbody>
                            <tr>
                                <td style="text-align:right;padding-right:20px;"><?php echo __('Import File', true); ?>:</td>
                                <td style="text-align:left;padding:5px 0;">
                                    <input type="file" id="myfile" name="file" />
                                    <span id="analysis" style="display:block;">

                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td align="right">
                                    <label><?php __('File With Header')?>:</label>
                                </td>
                                <td align="left" style="padding-left:20px;">
                                    <input type="checkbox" name="with_header" checked="checked" /><br />

                                </td>
                            </tr>
                            <tr>
                                <td align="right">
                                    <?php __('Example File Format Available')?>&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?php echo $example ?>" target="_blank" title="click to download"><?php __('here')?></a>&nbsp;&nbsp;&nbsp;
                                </td>
                                <td align="left">
                                    <input type="submit" id="import_btn" value="<?php __('Upload')?>" class="input in-submit btn btn-primary" />
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </form>
            </div>
        </div>
    </div>
</div>


<script src="<?php echo $this->webroot; ?>ajaxupload/swfupload.js"></script>
<script src="<?php echo $this->webroot; ?>ajaxupload/jquery-asyncUpload-0.1.js"></script>

<script>
    $(function() {
        var container = $(".container");
        $("#myfile").makeAsyncUploader({
            upload_url: '<?php echo $this->webroot ?>uploads/async_upload',
            flash_url: '<?php echo $this->webroot; ?>ajaxupload/swfupload.swf',
            button_image_url: '<?php echo $this->webroot; ?>ajaxupload/blankButton.png',
            file_size_limit: '1024 MB',
            post_params: {"PHPSESSID": "<?php echo session_id(); ?>"},
            upload_success_handler: function(file, response) {
                $("#analysis").empty();
                $("input[name$=_filename]", container).val(file.name);
                $("input[name$=_guid]", container).val(response);
                $("#analysis").html('<a target="_blank" href="<?php echo $this->webroot; ?>uploads/analysis_file/20/' + response + '">Show and modify</a>');
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
