<style>
    .mail_template_subject,.mail_template_cc{
        max-width: none;
        width: 98%;
    }
</style>
<ul class="breadcrumb">
    <li>
        <a href="<?php echo $this->webroot ?>mailtmps/mail">
        <?php __('Configuration') ?>
        </a>
        <li class="divider"><i class="icon-caret-right"></i></li>

         <li>
         <a href="<?php echo $this->webroot ?>mailtmps/mail">
                         <?php __('Mail template') ?>
                     </a>
                     </li>
    </li>
</ul>

<!--<div class="heading-buttons">-->
<!--    <h4 class="heading">--><?php //echo __('configmailtmp'); ?><!--</h4>-->
<!--    <div class="buttons pull-right">-->
<!--    </div>-->
<!--    <div class="clearfix"></div>-->
<!--</div>-->
<div class="separator bottom"></div>

<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <?php
        if(isset($is_necessary))
            echo $this->element("currs/step", array('now' => '3'));
        ?>
        <div class="widget-body">
            <form method="post" id="mailtmp_form">
                <?php echo $form->input('id',array('type' => 'hidden','label' => false,
                    'div' => false)); ?>
                <div id="templates">
                    <?php foreach($mail_template_arr as $mail_template): ?>
                        <div class="widget" data-toggle="collapse-widget" data-collapse-closed="true" >
                            <div class="widget-head"><h4 class="heading"><?php echo $mail_template['title']; ?></h4></div>
                            <div class="widget-body">
                                <table class="form table dynamicTable tableTools table-bordered  table-white">
                                    <colgroup>
                                        <col width="10%">
                                        <col width="80%">
                                        <col width="10%">
                                    </colgroup>
                                    <tbody>
                                    <tr>
                                        <td class="align_right"><?php __('From email') ?> </td>
                                        <td colspan="2">
                                            <?php echo $form->input($mail_template['from_email'],array('type' => 'select','label' => false,
                                                'div' => false,'options' => $mail_senders,'class' => 'mail_template_from')); ?>
                                        </td>
                                    </tr>
                                    <?php if($mail_template['cc']): ?>
                                        <tr>
                                            <td class="align_right"><?php __('Cc') ?></td>
                                            <td colspan="2">
                                                <?php echo $form->input($mail_template['cc'],array('type' => 'text','label' => false,
                                                    'div' => false,'class' => 'validate[custom[email]] mail_template_cc')); ?>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                    <tr>
                                        <td class="align_right"><?php echo __('subject') ?> </td>
                                        <?php if (count($mail_template['header_tags'])): ?>
                                        <td>
                                            <?php echo $form->input($mail_template['subject'],array('type' => 'text','label' => false,
                                                'div' => false,'class' => 'mail_template_subject')); ?>
                                        </td>
                                        <td class="header_tags">
                                            <h4><i class="icon-tags"></i><?php __('Tags'); ?>:</h4>
                                            <?php foreach($mail_template['header_tags'] as $tag): ?>
                                                <span class="btn btn-block btn-default">{<?php echo $tag; ?>}</span>
                                            <?php endforeach; ?>
                                        </td>
                                        <?php else: ?>
                                        <td colspan="2">
                                            <?php echo $form->input($mail_template['subject'],array('type' => 'text','label' => false,
                                                'div' => false,'class' => 'mail_template_subject')); ?>
                                        </td>
                                        <?php endif; ?>
                                    </tr>
                                    <tr>
                                        <td class="align_right"><?php echo __('content') ?></td>
                                        <td>
                                            <?php echo $form->input($mail_template['content'],array('type' => 'textarea','label' => false,
                                                'div' => false,'class' => 'mail_template_content ' . $mail_template['title'])); ?>
                                            <!--                                            <textarea class="input in-textarea welcom_content" name="welcom_content" style="height: 100px; font-family: monospace; font-size: 12px;width:800px;" id="welcom_content">--><?php //echo !empty($tmp[0][0]['welcom_content']) ? $tmp[0][0]['welcom_content'] : ''; ?><!--</textarea>-->
                                        </td>
                                        <td class="mail_content_tags">
                                            <h4><i class="icon-tags"></i><?php __('Tags'); ?>:</h4>
                                            <?php foreach($mail_template['tags'] as $tag): ?>
                                                <span class="btn btn-block btn-default">{<?php echo $tag; ?>}</span>
                                            <?php endforeach; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="center">
                                            <input type="button" value="Submit" class="btn btn-primary single_sub" data-value="<?php echo $mail_template['content']; ?>" />
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
        </div>
    </div>
</div>
<?php if ($_SESSION['role_menu']['Configuration']['mailtmps']['model_w']): ?>
    <div id="form_footer" class="button-groups center">
        <!--        <input type="button" id="testtest"  value="testtest"/>-->
<!--        <input class="input in-submit btn btn-primary" value="--><?php //echo __('submit') ?><!--" type="submit">-->
<!--        <input class="input in-button btn btn-inverse" value="--><?php //echo __('reset') ?><!--" id="reset_form" type="reset"   style="margin-left: 20px;">-->
    </div>
<?php endif; ?>
<div class="separator"></div>
</form>
<script type="text/javascript" src="<?php echo $this->webroot ?>js/ckeditor_full/ckeditor.js"></script>
<script type="text/javascript">
    $(function(){
        $("#mailtmp_form").on('reset', function(){
            for (instance in CKEDITOR.instances){
                CKEDITOR.instances[instance].setData($("#"+instance).val());
                CKEDITOR.instances[instance].updateElement();
            }
        });


        $(".single_sub").click(function(){
            var $this = $(this);
            var content = CKEDITOR.instances[$(this).data('value')].getData();
            $this.closest('table').find(".mail_template_content").val(content);
	    var templateName = $this.first().parents('table').find('.mail_template_subject').val();
            $.ajax({
                url: "<?php echo $this->webroot ?>mailtmps/ajax_save_single_template",
                type: 'post',
                dataType: 'text',
                data: $this.closest('table').find("[class*=mail_template]").serialize(),
                success: function(data) {
                    if (data != 0){
                        jGrowl_to_notyfy('The Email Template [' + templateName + '] is saved successfully!', {theme: 'jmsg-success'});
                    }else {
                        jGrowl_to_notyfy('<?php __('Save failed'); ?>', {theme: 'jmsg-error'});
                    }
                }
            });
        });


        $(".collapse-toggle").live('click',function(){
            var $this_textarea = $(this).parent().next().find('textarea');
            var $this_textarea_id = $this_textarea.attr('id');
            var $this_textarea_is_display = $this_textarea.css('display');
            if($this_textarea_is_display != 'none'){
                let config = {
                    baseFloatZIndex: '100000'
                };

                if ($this_textarea.hasClass('Invoice')) {
                    config.toolbar = [
                        { name: 'document', groups: [ 'mode', 'document', 'doctools' ], items: ['-', 'Save', 'NewPage', 'Preview', 'Print', '-', 'Templates' ] },
                        { name: 'clipboard', groups: [ 'clipboard', 'undo' ], items: [ 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo' ] },
                        { name: 'editing', groups: [ 'find', 'selection', 'spellchecker' ], items: [ 'Find', 'Replace', '-', 'SelectAll', '-', 'Scayt' ] },
                        { name: 'forms', items: [ 'Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton', 'HiddenField' ] },
                        '/',
                        { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ], items: [ 'Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'CopyFormatting', 'RemoveFormat' ] },
                        { name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ], items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', 'CreateDiv', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl', 'Language' ] },
                        { name: 'links', items: [ 'Link', 'Unlink', 'Anchor' ] },
                        { name: 'insert', items: [ 'Image', 'Flash', 'Table', 'HorizontalRule', 'Smiley', 'SpecialChar', 'PageBreak', 'Iframe' ] },
                        '/',
                        { name: 'styles', items: [ 'Format' ] },
                        { name: 'tools', items: [ 'Maximize', 'ShowBlocks' ] },
                        { name: 'others', items: [ '-' ] },
                        { name: 'about', items: [ 'About' ] }
                    ];
                }
                CKEDITOR.replace($this_textarea_id, config);
            }
        });

        $('.mail_content_tags').find('span').click(function(){
            var $tag_value = $(this).html();
            var $this_textarea_id = $(this).closest('tr').find('textarea').attr('id');
            var editor = CKEDITOR.instances[$this_textarea_id];
            editor.insertHtml( $tag_value );
        });

         $('.header_tags').find('span').click(function(){
            var $tag_value = $(this).html();
            var $input = $(this).closest('tr').find('.mail_template_subject');
            var newHtml = $input.val() + $tag_value;
            $input.val( newHtml );
        });

    });
    function focus_template(focus_obj,msg,type)
    {
        var a = focus_obj.closest('.widget').find('.in').size();
        if(a == 0){
            focus_obj.closest('.widget').find('.collapse-toggle').click();
        }
        focus_obj.focus();
        jGrowl_to_notyfy("The Email Template [" + msg + "] is not completed!", {theme: 'jmsg-error'});
    }

    $("#mailtmp_form").submit(function () {
//    $("#testtest").click(function(){
        var flg = true;
        $("#templates").find('.widget').each(function(){
            var subject = $(this).find('input[class*=subject]');
            var $this_textarea = $(this).find('textarea');
            if($this_textarea.css('display') != 'none'){
                var content = $this_textarea.val();
            }else{
                var $this_textarea_id = $this_textarea.attr('id');
                var content = CKEDITOR.instances[$this_textarea_id].getData();
            }
            var template_name = $(this).find('h4').html().split(': ')[1];
            if (!subject.val() || !content){
                focus_template(subject,template_name,'error');
                flg = false;
                return false;
            }
        });
        if(!$('#mailtmp_form').validationEngine('validate')){
            return false;
        }
        return flg;
    });
</script>




<!-- 如果验证没通过  将用户输入的表单信息重新显示 -->
<?php
$backform = $session->read('backform'); //用户刚刚输入的表单数据
if (!empty($backform))
{
    $session->del('backform'); //清除错误信息
//将用户刚刚输入的数据显示到页面上
    $d = array_keys($backform);
    foreach ($d as $k)
    {
        ?>
        <script type="text/javascript">
            //<![CDATA[
            document.getElementById("<?php echo $k ?>").value = "<?php echo $backform[$k] ?>";
            //]]>
        </script>
        <?php
    }
    ?>
    <script>$('.whide').hide();
        if ($('#calc_profit_date_type').val() == 2) {
            $('#combide').show();
            $('#calc_profit_day').show();
        }
        if ($('#calc_profit_date_type').val() == 3) {
            $('#calc_profit_week').show();
        }</script>
<?php } ?>

<script>
    //necessary界面
<!--    --><?php //if(isset($is_necessary)){echo '$("div.navbar").hide();';}?>
</script>
