<style type="text/css">
    DIV.ProgressBar { width: 100px; padding: 0; border: 1px solid black; margin-right: 1em; height:.75em; margin-left:1em; display:-moz-inline-stack; display:inline-block; zoom:1; *display:inline; }
    DIV.ProgressBar DIV { background-color: Green; font-size: 1pt; height:100%; float:left; }
    SPAN.asyncUploader OBJECT { position: relative; top: 5px; left: 10px; }
    SPAN.asyncUploader {display:block;margin-bottom:10px;}
    #analysis {padding-left:20px; text-decoration:underline;color:red;cursor:pointer;}
</style>


<ul class="breadcrumb">
    <li><?php echo __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Monitoring') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('BlackHole IP') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Upload') ?></li>
</ul>
<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Upload') ?></h4>

</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
    <a  class="btn btn-icon btn-inverse glyphicons circle_arrow_left" href="<?php echo $this->webroot ?>did/did"><i></i>
        <?php __('Back')?>
    </a>
</div>
<div class="clearfix"></div>
<div class="innerLR">
    <div class="widget widget-tabs widget-body-white">
        <div class="widget-body">
            <div class="clearfix"></div>
            <div id="container">
                <form id="myform" method="post"  enctype="multipart/form-data">
                    <table class="table dynamicTable tableTools table-bordered  table-white form">
                        <tbody>

                        <tr>
                            <th style="text-align:right;"><?php __('Duplicate Handling'); ?></th>
                            <td>
                                <select name="duplicate_type">
                                    <option value="delete"><?php __('Overwrite')?></option>
                                    <option value="ignore"><?php __('Ignore')?></option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th style="text-align:right;"><?php __('Upload File'); ?></th>
                            <td>
                                <input type="file" name="file" id="myfile" />
                                <span id="analysis" style="display:block;">

                                    </span>
                            </td>
                        </tr>
                        <tr>
                            <th style="text-align:right;"><?php __('Example'); ?></th>
                            <td>
                                <a target="_blank" href="<?php echo $this->webroot; ?>example/black_hole.csv"><?php __('show')?></a>
                            </td>
                        </tr>
                        <tr style="text-align:center;">
                            <td colspan="2" class="button-groups center input in-submit">
                                <input type="submit" id="subbtn" class="btn btn-primary" value="<?php __('Submit')?>">
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


<script type="text/javascript">
    $(function() {

        $("#myfile").makeAsyncUploader({
            upload_url: '<?php echo $this->webroot ?>uploads/async_upload',
            flash_url: '<?php echo $this->webroot; ?>ajaxupload/swfupload.swf',
            button_image_url: '<?php echo $this->webroot; ?>ajaxupload/blankButton.png',
            post_params: {"PHPSESSID": "<?php echo session_id(); ?>"},
            file_size_limit: '1024 MB',
            upload_success_handler: function(file, response) {
                $("#analysis").empty();
                $("input[name$=_filename]", container).val(file.name);
                $("input[name$=_guid]", container).val(response);
//                $("#analysis").html('<a target="_blank" href="<?php //echo $this->webroot; ?>//uploads/analysis_file/0/' + response + '">Show and modify</a>');
                $("span[id$=_completedMessage]", container).html("Uploaded <b>{0}</b> ({1} KB)"
                    .replace("{0}", file.name)
                    .replace("{1}", (file.size / 1024).toFixed(3))
                );
            }
        });
    });
</script>
