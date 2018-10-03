<?php
$subjectHeaders = array(
    'trunk_name', 'effective_date'
);
$contentHeaders = array(
    'company_name', 'allowed_ip', 'prefix', 'switch_ip', 'download_deadline', 'trunk_name', 'rate_filename',
    'billing_type', 'rounding_digits', 'cps_limit', 'channel_limit', 'IP_info_table', 'download_link'
    );
?>
<style>
    input{width: 220px;}
    select,textarea, input[type="text"]{margin-bottom: 0}
    .width25{width: 25px;}
    .width80{width: 80px;}
    .ms2side__div{
        background: transparent url('<?php echo $this->webroot; ?>common/theme/scripts/plugins/forms/multiselect/img/switch.png') no-repeat 178px 80px;
    }
</style>
<?php if ( !$appCommon->_get('is_ajax') ): ?>
<ul class="breadcrumb"> 
    <li><?php __('You are here'); ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>rate_email_template/email_template">
        <?php __('Template') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot . $this->params['url']['url'] ?>">
        <?php __('Rate Email Template') ?></a></li>
</ul>


<div class="heading-buttons">
    <h4 class="heading"><?php __('Rate Email Template') ?></h4>

</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
    <a class="btn btn-icon btn-inverse glyphicons circle_arrow_left" href="<?php echo $this->webroot; ?>rate_email_template/index"><i></i><?php __('Back') ?></a>
</div>
<div class="clearfix"></div>
<?php endif; ?>
<?php $id_flag = time(); ?>
<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">

        <div class="widget-body">
            <form method="post" id="myform">
                <table class="form table table-condensed dynamicTable tableTools table-bordered ">
                    <colgroup>
                        <col width="25%">
                        <col width="65%">
                        <col width="10%">
                    </colgroup>
                    <tr>
                        <th width="25%" style="text-align: right;"><?php echo __('Template Name') ?>:</th>
                        <td colspan="2">
                            <input type="text" class="input in-input validate[required]" style="width: 220px;" id="template_name" name="name" value="<?php echo isset($data['RateEmailTemplate']['name']) ? $data['RateEmailTemplate']['name'] : "" ?>"  />
                        </td>
                    </tr>
                    <tr>
                        <td class="right"><?php __('Download method') ?></td>
                        <td colspan="2">
                            <?php
                            $download_method = array(
                                1 => __('Send as attachment',true),
                                2 => __('Send as link',true),
                            );
                            $download_method_value = isset($data['RateEmailTemplate']['download_method']) ? $data['RateEmailTemplate']['download_method'] : 1;
                            echo $form->input('download_method',array('type' => 'select','label' => false,
                                'div' => false,'options' => $download_method,'selected' => $download_method_value)); ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="right"><?php __('Headers') ?></td>
                        <td colspan="2">
                            <?php echo $form->input('headers',array('type' => 'select','multiple' => true,'label' => false,'id' => 'headers_'.$id_flag,
                                'div' => false,'options' => $schema,'selected' => $default_fields,'class'=>'validate[required]')); ?>
                        </td>
                    </tr>
                    <tr>
                        <th width="25%" style="text-align: right;"><?php __('From email') ?>:</th>
                        <td colspan="2">
                            <select name="email_from">
                                <option value="default">Default</option>
                                <?php foreach ($mail_senders as $mail_sender): ?>
                                    <option value="<?php echo $mail_sender[0]['id'] ?>" <?php if (isset($data['RateEmailTemplate']['email_from']) && !strcmp($data['RateEmailTemplate']['email_from'], $mail_sender[0]['id'])): ?>selected="selected"<?php endif; ?>>
                                        <?php echo $mail_sender[0]['email'] ?>
                                    </option>                                        
                                <?php endforeach; ?>
                            </select>
                        </td>
                    </tr> 
                    <tr>
                        <th width="25%" style="text-align: right;"><?php echo __('Cc') ?>:</th>
                        <td colspan="2" >
                            <input type="text" class="input in-input validate[custom[email]]" style="width: 220px;" name="email_cc" value="<?php echo isset($data['RateEmailTemplate']['email_cc']) ? $data['RateEmailTemplate']['email_cc'] : "" ?>"  />
                        </td>
                    </tr>
                    <tr>
                        <th width="25%" style="text-align: right;"><?php echo __('subject') ?>:</th>
                        <td colspan="2" >
                            <input type="text" class="input in-input validate[required]" style="width: 90%;max-width: none;" name="subject" value="<?php echo isset($data['RateEmailTemplate']['subject']) ? $data['RateEmailTemplate']['subject'] : "" ?>"  />
                        </td>
                        <td class="mail_subject_tags">
                             <h4><i class="icon-tags"></i>Tags:</h4>
                             <?php
                             foreach ($subjectHeaders as $item) {
                             ?>
                                 <span class="btn btn-block btn-default" style="width:140px;">{<?php echo $item; ?>}</span>
                             <?php
                             }
                             ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="right"><?php echo __('content') ?>:</td>
                        <td  colspan="2" >
                            <textarea class="input in-textarea mail_content validate[required] " id="mail_content_<?php echo $id_flag; ?>" name="content" ><?php echo isset($data['RateEmailTemplate']['content']) ? $data['RateEmailTemplate']['content'] : "" ?></textarea>
                        </td>
                        <td class="mail_content_tags">
                             <h4><i class="icon-tags"></i><?php __('Tags'); ?>:</h4>
                             <?php
                             foreach ($contentHeaders as $item) {
                             ?>
                                 <span class="btn btn-block btn-default<?php if($item == 'download_deadline') echo ' download_deadline_tag';?>">{<?php echo $item; ?>}</span>
                             <?php
                             }
                             ?>
                         </td>
                    </tr>

                </table>
                <?php if ( !$appCommon->_get('is_ajax') ): ?>
                <table style="width: 100%;" >
                    <tr>
                        <td class="buttons-group center">
                            <input type="submit"  value="<?php __('Submit') ?>" class="btn btn-primary"/>
                            <input type="reset"  value="<?php __('Revert') ?>" class="btn btn-default" />
                        </td>
                    </tr>
                </table>
                <?php endif; ?>
            </form>    
        </div>
    </div>
</div>

<script type="text/javascript" src="<?php echo $this->webroot ?>js/ckeditor_full/ckeditor.js"></script>
<script type="text/javascript" src="<?php echo $this->webroot; ?>tiny_mce/tiny_mce.js"></script>
<link href="<?php echo $this->webroot; ?>js/multiselect2side/jquery.multiselect2side.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $this->webroot; ?>js/multiselect2side/jquery.multiselect2side.js"></script>
<script type="text/javascript">
    $(function() {
        CKEDITOR.replace('mail_content_<?php echo $id_flag; ?>');
//        $("#headers1").multiSelect({
//            selectableHeader: "<div class='custom-header'><?php //__('Selectable items'); ?>//</div>",
//            selectionHeader: "<div class='custom-header'><?php //__('Selection items'); ?>//</div>"
//        });
        $("#headers_<?php echo $id_flag; ?>").multiselect2side({
            labelTop: '<?php __('Top'); ?>',
            labelBottom: '<?php __('Bottom'); ?>',
            labelUp: '<?php __('Up'); ?>',
            labelDown: '<?php __('Down'); ?>',
            labelSort:'<?php __('Sort'); ?>',
            labelsx: '<?php __('Selectable items'); ?>',
            labeldx: '<?php __('Selection items'); ?>',

        });
        $("#myform").submit(function() {

            var name = $("#template_name").val();
            if(!name){
                return false;
            }
            var id = "<?php echo isset($this->params['pass'][0]) ? $this->params['pass'][0] : ""; ?>";
            $.ajax({
                'url': '<?php echo $this->webroot ?>rate_email_template/judge_template_name/' + name + '/' + id,
                'type': 'POST',
                'async': false,
                'dataType': 'json',
                'data': {'is_ajax': '1'},
                'success': function(data) {
                    if (data)
                    {
                        jGrowl_to_notyfy('<?php __('Template name already is use!'); ?>', {theme: 'jmsg-error'});
                        return false;
                    }
                }
            });

            for (instance in CKEDITOR.instances)
                content = CKEDITOR.instances[instance].getData();
            if (!content)
            {
                jGrowl_to_notyfy('<?php __('Content is required'); ?>', {theme: 'jmsg-error'});
                return false;
            }
            $(".selectedOption").find('option').attr('selected',true);
            return true;
        });

        $('.mail_content_tags').find('span').click(function(){
            var $tag_value = $(this).html();
            var $this_textarea_id = $(this).closest('tr').find('textarea').attr('id');
            var editor = CKEDITOR.instances[$this_textarea_id];
            editor.insertHtml( $tag_value );
        });

        $('.mail_subject_tags').find('span').click(function(){
            var $tag_value = $(this).html();
            var $subj_input = $(this).closest('tr').find('input');
            $subj_input.val( $subj_input.val() + $tag_value );
        });

    });

</script>




