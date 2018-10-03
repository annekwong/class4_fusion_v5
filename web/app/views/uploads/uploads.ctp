<style type="text/css">
    DIV.ProgressBar { width: 100px; padding: 0; border: 1px solid black; margin-right: 1em; height:.75em; margin-left:1em; display:-moz-inline-stack; display:inline-block; zoom:1; *display:inline; }
    DIV.ProgressBar DIV { background-color: Green; font-size: 1pt; height:100%; float:left; }
    SPAN.asyncUploader OBJECT { position: relative; top: 5px; left: 10px; }
    SPAN.asyncUploader {display:block;margin-bottom:10px;}
    #analysis {padding-left:20px; text-decoration:underline;color:red;cursor:pointer;}
</style>

<?php //pre($this->params['url']['url']);?>
<ul class="breadcrumb">
    <li><?php __('You are here')?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot . $this->params['url']['url'] ?>"><?php
        if($this->params['url']['url'] == 'uploads/jur_country'){
             echo __('Switch');
        }else{
             echo isset($module) ? $module : Inflector::humanize($appUploads->show_upload_title($this->params['action']));
        }
        ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot . $this->params['url']['url'] ?>">
        <?php
            if($this->params['url']['url'] == 'uploads/jur_country'){
                echo __('US Jurisdiction');
            }else{
                echo isset($action) ? Inflector::humanize($action) : Inflector::humanize(__($this->params['action'], true));
            }
        ?>
        <font class="editname"><?php echo @empty($name[0][0]['name']) || $name[0][0]['name'] == '' ? '' : "[" . $name[0][0]['name'] . "]" ?></font></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot . $this->params['url']['url'] ?>">
        <?php __('Import')?></a></li>
    <?php if (!strcmp($action, "Block list"))
    { ?>
        <li class="divider"><i class="icon-caret-right"></i></li>
        <li><a href="<?php echo $this->webroot . $this->params['url']['url'] ?>">
            <?php __('Complete Field Upload')?></a></li>
    <?php } ?>
</ul>
<div class="heading-buttons">
    <h4 class="heading"><?php __('Import')?></h4>

</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
    <?php if (isset($back_url) && !empty($back_url)): ?>
        <a class="btn btn-icon glyphicons btn-inverse circle_arrow_left" href="<?php echo $back_url; ?>"><i></i> <?php __('Back')?></a>
    <?php endif; ?>
</div>
<div class="clearfix"></div>
<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-head">
            <?php echo $this->element('uploads/' . $this->params['action'] . '_tabs') ?>

        </div>
        <div class="widget-body">


            <?php if (isset($exception_msg) && $exception_msg) : ?>
                <?php echo $this->element('common/exception_msg'); ?>
            <?php endif; ?>
            <?php if (isset($action) && !strcmp($action, "Block list"))
            { ?>
                <ul class="">
                    <li class="glyphicons">
                        <a class="btn btn-primary disabled"><?php __('Complete Field Upload')?></a>
                    </li>
                    <li class="glyphicons">
                        <a class="btn btn-primary active" href="<?php echo $this->webroot; ?>blocklists/upload_number"><?php __('Upload just ANI / DNIS')?></a>
                    </li>
                </ul>
            <?php } ?>
            <form id="improt_form" action="" method="POST" enctype="multipart/form-data">
                <div  id="static_div"   style="text-align: left; width: 530px;">
                    <table class="cols table dynamicTable tableTools table-bordered  table-white" style="width: 252px; margin: 0px auto;"  >
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
                                    <td style="text-align:left;"><a target="_blank" href="<?php echo $this->webroot ?>uploads/download_error_file/<?php echo $statistics['log_id'] ?>"><?php echo __('download', true); ?></a></td>
                                </tr>
                            <?php endif; ?>
                            <tr><td>&nbsp;</td><td></td></tr>
                            <tr><td>&nbsp;</td><td></td></tr>
                        <?php endif; ?>

                    </table>
                </div>
                <table class="cols table dynamicTable tableTools table-bordered  table-white">
                    <colgroup>
                        <col width="40%">
                        <col width="60%">
                    </colgroup>
                    <tr>
                        <td style="text-align:right;padding-right:4px;"><?php echo __('Import File', true); ?>:</td>
                        <td style="text-align:left;">
                            <input type="file" id="myfile" name="file" />
                            <span id="analysis" style="display:block;">

                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align:right;padding-right:4px;"><?php echo __('Method', true); ?>:</td>
                        <td style="text-align:left;" class="form-inline">
                            <input type="radio" name="duplicate_type" value="ignore" id="duplicate_type_ignore" />
                            <label for="duplicate_type_ignore"><?php echo __('Ignore', true); ?></label><!--			  
                            <input type="radio" name="duplicate_type" value="overwrite" id="duplicate_type_overwrite"/>
                            <label for="duplicate_type_overwrite">Overwrite</label>			  
                            --><input type="radio" name="duplicate_type" value="delete" id="duplicate_type_delete"     checked="checked"/>
                            <label for="duplicate_type_delete"><?php echo __('delete', true); ?></label>
                        </td>
                    </tr>
                    <?php if (isset($action) && !strcmp($action, "Block list"))
                    { ?>
                        <tr>
                            <td style="text-align:right;padding-right:4px;"><?php echo __('Empty ANI', true); ?>:</td>
                            <td style="text-align:left;" class="form-inline">
                                <select name='block_list_ani_empty'>
                                    <option value='1'><?php __('True')?></option>
                                    <option value='0'><?php __('False')?></option>
                                </select>
                            </td>
                        </tr>
                    <?php } ?>
                    <tr><td style="text-align:right;padding-right:4px;"><?php __('Example')?>:</td><td align="left"><a href="<?php echo $this->webroot ?>example/<?php echo $example_file ?>.csv" target="_blank" title=""><?php __('show')?></a></td></tr>
                    <tr>
                        <td colspan="2" style="text-align:right;padding-right:4px;"></td><!--
                        <td style="text-align:left;">
                                <input type="checkbox" name="with_headers" checked="checked"/>
                                <span>With headers row</span>
                </td>
                        --></tr><!--
                        <tr>
                                <td style="text-align:right;padding-right:4px;"></td>
                                <td style="text-align:left;">
                                        <input type="checkbox" name="rollback_on_error"/>
                                        <span>Rollback on error</span>
                        </td>
                        </tr>
                    -->
                    <?php //if ($_SESSION['role_menu']['Switch']['jurisdictionprefixs']['model_x'] || $_SESSION['role_menu']['Switch']['rates']['model_w'] || $_SESSION['role_menu']['Switch']['codedecks']['model_w'])
                    //{
                    //    ?>
                        <tr class="center">
                            <td colspan="2" class="submit center"><?php echo $form->submit( __('Upload',true), array('class' => 'btn btn-primary', 'id' => 'upload_submit')) ?></td>
                        </tr>
                </table>
            </form>
        </div>
    </div>
</div>
<?php if (!empty($statistics['log_id']))
{
    ?>
    <script  type="text/javascript">
        (function(div_id, status) {
            var _div_id = $(div_id);
            var _status = 0;
            var _timeoutHander = null;


            var test = function() {
                _timeoutHander = setTimeout(doStartCap, 2000);
            }
            var doStartCap = function() {

                $.post('<?php echo $this->webroot ?>uploads/get_upload_log?id=<?php echo $statistics['log_id']; ?>', {},
                    function(data) {
                        var s = data.substring(0, 2);
                        //if(/\d/.test(s)){
                        _div_id.html(data.substring(2));
                        if (s == 6) {
                            clearTimeout(_timeoutHander);

                        }
                        //}
                        _timeoutHander = setTimeout(doStartCap, 2000);
                    }
                );

            }


            jQuery(document).ready(doStartCap);


        })('#static_div', '#upload_status');
    </script>

<?php } ?>

<script src="<?php echo $this->webroot; ?>ajaxupload/swfupload.js"></script>
<script src="<?php echo $this->webroot; ?>ajaxupload/jquery-asyncUpload-0.1.js"></script>


<script type="text/javascript">
    $(function() {

        $("#improt_form").submit(function() {
            var file = $("#myfile_completedMessage").html();
            if (!file)
            {
                jQuery.jGrowlError('You should select a file!');
                return false;
            } else {
                return true;
            }

        });

        $('#custom_date').hide();
        $('#is_custom_enddate').click(function() {
            if ($(this).attr('checked')) {
                $('#custom_date').show();
            } else {
                $('#custom_date').hide();
            }
        });

        $("#myfile").makeAsyncUploader({
            upload_url: '<?php echo $this->webroot ?>uploads/async_upload',
            flash_url: '<?php echo $this->webroot; ?>ajaxupload/swfupload.swf',
            button_image_url: '<?php echo $this->webroot; ?>ajaxupload/blankButton.png',
            post_params: {"PHPSESSID": "<?php echo session_id(); ?>"},
            file_size_limit: '1024 MB',
            upload_success_handler: function(file, response) {
                console.log(file, response);
                var container = $('#content');
                $("#analysis").empty();
                $("input[name$=_filename]", container).val(file.name);
                //$("input[name$=_guid]", container).val(response).after('<span id="analysis"><a target="_blank" href="<?php echo $this->webroot; ?>uploads/analysis_file/<?php echo $type; ?>/' + response +'">After the analysis of the results</a></span>');
                $("input[name$=_guid]", container).val(response);
                $("#analysis").html('<a target="_blank" href="<?php echo $this->webroot; ?>uploads/analysis_file/<?php echo $type; ?>/' + response + '">Show and modify</a>');
                $("span[id$=_completedMessage]", container).html("Uploaded <b>{0}</b> ({1} KB)"
                        .replace("{0}", file.name)
                        .replace("{1}", (file.size / 1024).toFixed(3))
                );
            }
        });
    });
</script>

<?php if(isset($type) && $type == 8): ?>
<script>
    $(document).ready(function () {
       console.log('ss');
    });
</script>
<?php endif; ?>
