<ul class="breadcrumb">
    <li><?php __('Switch') ?></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php __('Jurisdiction'); ?></h4>

</div>
<div class="separator bottom"></div>
<div class="clearfix"></div>

<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <?php echo $this->element("currs/step", array('now' => '9')); ?>
        <div class="widget-body uniformjs collapse in">

            <table class="form table table-condensed dynamicTable tableTools table-bordered">
                <colgroup>
                    <col width="30%">
                    <col width="70%">
                </colgroup>
                <tr>
                    <td class="right"><?php __('Import Type'); ?>:</td>
                    <td>
                        <label class="radio">
                            <div class="radio">
                                <input class="radio import_type" checked="checked" type="radio" value="1" name="import_type" id="import_type1">
                            </div>
                            <?php __('Custom File'); ?>
                        </label>
                        <?php if($upload_file): ?>
                        <label class="radio">
                            <div id="uniform-undefined" class="radio">
                                <input class="radio import_type" type="radio" value="2" name="import_type">
                            </div>
                            <?php __('Use LERG Provided by DENOVOLAB'); ?>
                        </label>
                        <?php endif; ?>
                    </td>
                </tr>
            </table>
            <form action="<?php echo $this->webroot; ?>necessary_configuration/jurisdiction_default" method="post" id="myform">
                <input type="hidden" name="upload_default_file" value="<?php echo $upload_file; ?>" />
                <div class="row-fluid hide widget-body" id="default_content">
                    <div class="span1 offset4">
                        <a href="javascript:void(0)" onclick="$('#myform').submit();"  class=" btn btn-primary"><?php __('Submit') ?></a>
                    </div>
                </div>
            </form>
            <form id="improt_form" action="<?php echo $this->webroot; ?>necessary_configuration/setup_jurisdiction/<?php echo $user_id; ?>" method="POST" enctype="multipart/form-data">

                <div class="row-fluid widget-body" id="custom_content">
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
                    <div class="span8 offset3">
                        <table class="cols margin-none">
                            <tr>
                                <td style="text-align:right;padding-right:4px;"><?php echo __('Import File', true); ?>:</td>
                                <td style="text-align:left;">
                                    <input type="file" id="myfile" name="file" />
                                </td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>
                                    <span id="analysis" style="display:block;">
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align:right;padding-right:4px;"><?php echo __('Method', true); ?>:</td>
                                <td style="text-align:left;" class="form-inline">
                                    <input type="radio" name="duplicate_type" value="ignore" id="duplicate_type_ignore" />
                                    <label for="duplicate_type_ignore"><?php echo __('Ignore', true); ?></label><input type="radio" name="duplicate_type" value="delete" id="duplicate_type_delete"     checked="checked"/>
                                    <label for="duplicate_type_delete"><?php echo __('delete', true); ?></label>
                                </td>
                            </tr>
                            <tr><td align="right"><?php __('Example') ?>:</td><td align="left"><a href="<?php echo $this->webroot ?>example/<?php echo $example_file ?>.csv" target="_blank" title="Show example file"><?php __('show') ?></a></td></tr>
                        </table>
                    </div>
                    <div class="span4">
                    </div>
                    <div class="span7">
                        <a href="javascript:void(0)" onclick="$('#improt_form').submit();"  class=" btn btn-primary"><?php __('Submit') ?></a>
                    </div>
                </div>
            </form>
        </div>
        <div class="center">
            <a id="next_step" href="<?php echo $this->webroot ?>homes/init_login_url/<?php echo $user_id; ?>/1"  class=" btn primary next hide"><?php __('Next') ?></a>
        </div>
        <div class="clearfix"></div>
    </div>
</div>
<script src="<?php echo $this->webroot; ?>ajaxupload/swfupload.js"></script>
<script src="<?php echo $this->webroot; ?>ajaxupload/jquery-asyncUpload-0.1.js"></script>
<?php if (!empty($statistics['log_id'])): ?>
    <script  type="text/javascript">
                            $(function () {
                                $("#import_type1").click();
                            });
                            (function (div_id, status) {
                                var _div_id = $(div_id);
                                var _status = 0;
                                var _timeoutHander = null;
                                var test = function () {
                                    _timeoutHander = setTimeout(doStartCap, 2000);
                                }
                                var doStartCap = function () {
                                    $.post('<?php echo $this->webroot ?>uploads/get_upload_log?id=<?php echo $statistics['log_id']; ?>', {},
                                                        function (data) {
                                                            var s = data.substring(0, 6);
                                                            //if(/\d/.test(s)){
                                                            _div_id.html(data.substring(2));
                                                            if (s == 2) {
                                                                clearTimeout(_timeoutHander);
                                                                $("#next_step").show();
                                                                bootbox.confirm('<?php __('Whether to enter the next setting'); ?>?', function (result) {
                                                                    if (result)
                                                                    {
                                                                        window.location.href = "<?php echo $this->webroot ?>homes/init_login_url/<?php echo $user_id; ?>/1";
                                                                    }
                                                                });
                                                            }
                                                            else {
                                                                _timeoutHander = setTimeout(doStartCap, 2000);
                                                            }
                                                            //}
                                                        }
                                                );

                                            }


                                            jQuery(document).ready(doStartCap);


                                        })('#static_div', '#upload_status');
    </script>
<?php endif; ?>
<script type="text/javascript">
    $(function () {
//        $("div.navbar").hide();
        $.gritter.add({
            title: '<?php __('Setup Jurisdiction'); ?>',
            text: '<?php __('You may use our default Jurisdiction file, or you may upload your own.'); ?>',
            sticky: true
        });


        $("#improt_form").submit(function () {
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
        $('#is_custom_enddate').click(function () {
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
            upload_success_handler: function (file, response) {
                var container = $('#content');
                $("#analysis").empty();
                $("input[name$=_filename]", container).val(file.name);
                $("input[name$=_guid]", container).val(response);
                $("#analysis").html('<a target="_blank" href="<?php echo $this->webroot; ?>uploads/analysis_file/5/' + response + '">Show and modify</a>');
                $("span[id$=_completedMessage]", container).html("Uploaded <b>{0}</b> ({1} KB)"
                        .replace("{0}", file.name)
                        .replace("{1}", (file.size / 1024).toFixed(3))
                        );
            }
        });
    });

    $(".import_type").click(function () {
        var type = $(this).val();
        if (type == 1)
        {
            $("#custom_content").show();
            $("#default_content").hide();
        }
        else
        {
            $("#custom_content").hide();
            $("#default_content").show();
        }
    });
</script>
