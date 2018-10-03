<ul class="breadcrumb">
    <li><?php __('You are here'); ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li>
        <a href="<?php echo $this->webroot ?>systemparams/login_page_content">
            <?php __('Configuration') ?>
        </a>
    </li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>systemparams/login_page_content">
            <?php echo __('Login Page Setting'); ?></a></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Login Page Setting'); ?></h4>
    <div class="buttons pull-right">
    </div>
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>

<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-body">
            <form method="post" id="mailtmp_form">

                <table class="table table-bordered">
                    <tbody>
                    <tr>
                        <td class="right"><?php __('Change Background Image') ?> <br><strong>(Only jpeg image) <br>The image size should be less than 1 mb</strong></td>
                        <td><span id="image_background"><img width="120px" height="45px" src="<?php echo $background.'?d='.time(); ?>" /></span></td>
                        <td style="text-align: center;">
                            <!--                                <form enctype="multipart/form-data" action="--><?php //echo $this->webroot; ?><!--systemparams/change_logo" method="post">-->
                            <input id="background_img" type="file" name="background_img"  />&nbsp;&nbsp;
                            <input type="hidden" id="is_change_background" name="is_change_background" />
                            <a onclick="myconfirm('Are you sure to delete background image?', this);return false;" href="<?=$this->webroot?>systemparams/remove_login_backround_image">
                                <i class="icon-remove"></i>
                            </a>
                            <!--                                </form>-->
                        </td>
                    </tr>
                    <tr>
                        <td class="right"><?php __('Fit image to the screen') ?> </td>
                        <td colspan="2">
                            <input type="checkbox" <?php if ($login_fit_image) { echo 'checked="checked"'; } ?> id="login_fit_image" name="login_fit_image" />
                        </td>
                    </tr>
                    <tr>
                        <td class="right">Use captcha</td>
                        <td colspan="2">
                            <input type="hidden" id="login_captcha" name="login_captcha" />
                            <input type="checkbox" <?php if ($login_captcha !=='false') { echo 'checked="checked"'; } ?> id="login_captcha" name="login_captcha" />
                        </td>
                    </tr>
                    <tr>
                        <td class="right">Enable Self-Registration</td>
                        <td colspan="2">
                            <input type="hidden" id="allow_registration" name="allow_registration" />
                            <input type="checkbox" <?php if ($allow_registration !=='false') { echo 'checked="checked"'; } ?> id="allow_registration" name="allow_registration" />
                        </td>
                    </tr>
                    </tbody>
                </table>
                <?php echo $form->input('login_page_content',array('type' => 'textarea','label' => false,
                    'div' => false,'class' => 'login_page_content','value' => isset($login_page_content) ? $login_page_content : '')); ?>


                <?php if ($_SESSION['role_menu']['Configuration']['mailtmps']['model_w']): ?>
                    <div id="form_footer" class="button-groups center separator">
                        <!--        <input type="button" id="testtest"  value="testtest"/>-->
                        <input class="input in-submit btn btn-primary" value="<?php echo __('submit') ?>" type="submit">
                        <input class="input in-button btn btn-inverse" value="<?php echo __('reset') ?>" id="reset_form" type="reset"   style="margin-left: 20px;">
                    </div>
                <?php endif; ?>
            </form>
        </div>
    </div>
</div>

<div class="separator"></div>
</form>
<script type="text/javascript" src="<?php echo $this->webroot ?>js/ckeditor_full/ckeditor.js"></script>
<script src="<?php echo $this->webroot; ?>ajaxupload/swfupload.js"></script>
<script src="<?php echo $this->webroot; ?>ajaxupload/jquery-asyncUpload-0.1.js"></script>
<script type="text/javascript">
    $(function(){

        CKEDITOR.replace('login_page_content',
            {
                height:'500px',
                resize_maxWidth:'1100px',
                'resize_maxHeight':'530px',
                'smallToolbar': 'toolbar'
            }
        );
        var old_img_url = $("#image_background img").attr('src');
        $("#mailtmp_form").on('reset', function(){
            for (instance in CKEDITOR.instances){
                CKEDITOR.instances[instance].setData($(".login_page_content").val());
                CKEDITOR.instances[instance].updateElement();
            }
            var img_html = "<img src='"+old_img_url+"' width='120px' height='45px' />";
            $("#image_background").html(img_html);
            $("#is_change_background").val(0);
        });

        function getPos(el) {
            for (var lx=0, ly=0;
                 el != null;
                 lx += el.offsetLeft, ly += el.offsetTop, el = el.offsetParent);
            return {x: lx,y: ly};
        }

        $("#background_img").makeAsyncUploader({
            upload_url: '<?php echo $this->webroot ?>uploads/async_upload_img/background_tmp',
            flash_url: '<?php echo $this->webroot; ?>ajaxupload/swfupload.swf',
            button_image_url: '<?php echo $this->webroot; ?>ajaxupload/blankButton.png',
            post_params: {"PHPSESSID": "<?php echo session_id(); ?>"},
            file_size_limit: '1024 MB',
            file_types: "*.png;*.jpg;*.bmp;*.jpeg",
            file_types_description: "Only img file",
            button_text: "<font face='Arial' size='13pt'>Change</font>",
            button_text_left_padding: '30',
            upload_success_handler: function(file, response) {
                var container = $('#content');
//                $("input[name$=_filename]", container).val(file.name);
//                $("input[name=background_img_guid]", container).val('background');
                $("#is_change_background").val(1);
                var tmp_img_path = "<?php echo $this->webroot . 'upload' . DS . 'images'.DS.'tmp'; ?>/background_tmp.png?d=" + new Date();
                var img_html = "<img src='"+tmp_img_path+"' width='120px' height='45px' />";
                $("#image_background").html(img_html);
            }
        });


    });

</script>
