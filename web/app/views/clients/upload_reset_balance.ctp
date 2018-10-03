<style type="text/css">
    DIV.ProgressBar { width: 100px; padding: 0; border: 1px solid black; margin-right: 1em; height:.75em; margin-left:1em; display:-moz-inline-stack; display:inline-block; zoom:1; *display:inline; }
    DIV.ProgressBar DIV { background-color: Green; font-size: 1pt; height:100%; float:left; }
    SPAN.asyncUploader OBJECT { position: relative; top: -5px; left: 10px; }
  
</style>

<div id="title">
    <h1><?php __('Configuration') ?> &gt;&gt;<?php echo __('LRN Setting') ?></h1>
</div>

<div id="container">
    <form method="post" id="myform">
     <div id="upload_log">
            
     </div>   
    <table class="cols" style="width:700px;margin:0px auto;">
        <tbody>
            <tr>
                <td style="text-align:right;padding-right:4px;"><?php echo __('Import File', true); ?>:</td>
                <td style="text-align:left;padding:5px 0;">
                    <input type="file" id="myfile" name="file" />
                    <span id="analysis" style="display:block;">

                    </span>
                </td>
            </tr>
            <tr>
                <td align="right">
                    <label><?php __('Type')?>:</label>
                </td>
                <td align="left" style="padding-left:10px;">
                    <select name="balance_type">
                        <option value="0"><?php __('Actual')?></option>
                        <option value="1"><?php __('Mutual')?></option>
                    </select>
                </td>
            </tr>
            <tr>
                <td align="right">
            		<label><?php __('File With Header')?>:</label>
            	</td>
                <td align="left" style="padding-left:10px;">
                    <input type="checkbox" name="with_header" checked="checked" /><br />
                    
                </td>
            </tr>
            <tr>
                <td align="right">
                    <?php __('Example File Format Available')?><a href="<?php echo $example ?>" target="_blank" title="click to download"><?php __('here')?></a>&nbsp;&nbsp;&nbsp;
                </td>
                <td align="left">
                    <input type="submit" id="import_btn" value="<?php __("Upload")?>" class="input in-submit" />
                </td>
            </tr>
        </tbody>
    </table>
    </form>
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
                    //$("input[name$=_guid]", container).val(response).after('<span id="analysis"><a target="_blank" href="<?php echo $this->webroot; ?>uploads/analysis_file/<?php echo $type; ?>/' + response +'">After the analysis of the results</a></span>');
                    $("input[name$=_guid]", container).val(response);
                    $("#analysis").html('<a target="_blank" href="<?php echo $this->webroot; ?>uploads/analysis_file/<?php echo $type; ?>/' + response +'">Show and modify</a>');
                    $("span[id$=_completedMessage]", container).html("Uploaded <b>{0}</b> ({1} KB)"
                                .replace("{0}", file.name)
                                .replace("{1}", (file.size / 1024).toFixed(3))
                            );
                }
        });
        
        <?php if (isset($upload_id)): ?>
        
  
        
        window.setInterval(function() {
            $.ajax({
                'url' : '<?php echo $this->webroot ?>uploads/get_upload_log?id=<?php echo $upload_id; ?>',
                'type' : 'POST',
                'dataType' : 'text',
                'success' : function(data)
                {
                    $('#upload_log').html(data.substr(2));
                }
            });
        } , 2000);
        
        <?php endif; ?>
    });
</script>
