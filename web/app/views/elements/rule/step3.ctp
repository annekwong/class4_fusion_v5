<?php
$tags = array('code', 'rule_name', 'limit_table', 'rule_setup', 'block_list', 'total_calls', 'sample_start_time', 'sample_end_time');
?>
<table class="form table tableTools table-bordered  table-white">
    <tbody>
    <col width="25%">
    <col widh="75%">
    <tr>
        <td class="right">
            <?php __('Active')?>:
        </td>
        <td >
            <input type="checkbox" name="AlertRules[active]" value="1" id="active" class="border_no" <?php if($post_data['active']) echo "checked='checked'"; ?>>
        </td>
    </tr>
    <tr>
        <td class="right">
            <?php __('Block')?>:
        </td>
        <td >
            <!--<select name="AlertRules[step3_type]" id="step3_type">
                    <option value="1" <?php /*if($post_data['step3_type'] == 1){echo "selected='selected'";} */?>><?php /*__('Send Trouble Ticket Email')*/?></option>
                    <option value="2" <?php /*if($post_data['step3_type'] == 2){echo "selected='selected'";} */?>><?php /*__('Trunks')*/?> </option>
                </select>-->
            <input type="checkbox" name="AlertRules[is_block]" value="1" id="is_block" class="border_no" <?php if($post_data['is_block']) echo "checked='checked'"; ?>>
        </td>
    </tr>
    <tr class="block-check hidden">
        <td class="right">
            <?php __('Enable Auto-unblock')?>:
        </td>
        <td >
            <select name="AlertRules[auto_enable]" id="">
                <option value="0" <?php if($post_data['auto_enable'] == 0) echo 'selected'; ?> >No</option>
                <option value="1" <?php if($post_data['auto_enable'] == 1) echo 'selected'; ?> >Yes</option>
            </select>
        </td>
    </tr>
    <tr class="block-check hidden">
        <td class="right">
            <?php __('Unblock After')?> (mins):
        </td>
        <td >
            <input type="text" name="AlertRules[unblock_after_min]" class="border_no validate[custom[number]]" value="<?php if(isset($post_data['unblock_after_min'])) echo $post_data['unblock_after_min']; ?>">
        </td>
    </tr>
    <tr>
        <td class="right">
            <?php __('Send Email')?>:
        </td>
        <td >
            <input type="checkbox" name="AlertRules[is_email]" value="1" id="is_email" class="border_no" <?php if($post_data['is_email']) echo "checked='checked'"; ?>>
        </td>
    </tr>
    </tbody>
</table>
<table class="form table dynamicTable tableTools table-bordered  table-white" id="step3_type1">
    <tbody>
    <tr>
        <td width="25%" style="text-align: right;"><?php __('Send To')?>:</td>
        <td colspan="2">
            <select name="AlertRules[trouble_ticket_sent_to]">
                <option value="1" <?php if($post_data['trouble_ticket_sent_to'] == 1){echo "selected='selected'";} ?>><?php __('Your Own NOC Email')?></option>
                <option value="2" <?php if($post_data['trouble_ticket_sent_to'] == 2){echo "selected='selected'";} ?>><?php __('Partner’s NOC Email')?></option>
                <option value="3" <?php if($post_data['trouble_ticket_sent_to'] == 3){echo "selected='selected'";} ?>><?php __('Both')?></option>
            </select>
        </td>
    </tr>
    <tr>
        <td width="25%" style="text-align: right;"><?php __('From email')?>:</td>
        <td colspan="2">
            <select name="AlertRules[trouble_ticket_sent_from]">
                <option value="0">Default</option>
                <?php foreach ($mail_senders as $mail_sender): ?>
                    <option value="<?php echo $mail_sender[0]['id'] ?>" <?php if($post_data['trouble_ticket_sent_from'] == $mail_sender[0]['id']){echo "selected='selected'";} ?>><?php echo $mail_sender[0]['email'] ?></option>
                <?php endforeach; ?>
            </select>
        </td>
    </tr>
    <tr>
        <td width="25%" style="text-align: right;"><?php echo __('subject') ?>:</td>
        <td colspan="2">
            <input type="text" class="input in-input validate[required]" style="width: 220px;" name="AlertRules[trouble_ticket_subject]" value="<?php echo $post_data['trouble_ticket_subject']; ?>"  id="trouble_ticket_subject" />
            <p style="font-weight: 100;color: #666;margin: 0;font-family:Helvetica Neue,Helvetica,Arial,sans-serif;">
                {rule_name} {sample_start_time} {sample_end_time}            </p>

        </td>
    </tr>
    <tr>
        <td width="25%" style="text-align: right;"><?php echo __('content') ?>:</td>
        <td ><textarea class="input in-textarea trouble_ticket_content validate[required]" name="AlertRules[trouble_ticket_content]" style="height: 100px; font-family: monospace; font-size: 12px;width:800px;" id="trouble_ticket_content"><?php echo!empty($post_data['trouble_ticket_content']) ? $post_data['trouble_ticket_content'] : ''; ?></textarea>
        </td>
        <td class="mail_content_tags">
            <h4><i class="icon-tags"></i><?php __('Tags'); ?>:</h4>
            <span class="btn btn-block btn-default">{monitoring_result}</span>
            <?php foreach($tags as $tag): ?>
                <span class="btn btn-block btn-default">{<?php echo $tag; ?>}</span>
            <?php endforeach; ?>
        </td>
    </tr>


    </tbody>
</table>

<!--<table class="form table dynamicTable tableTools table-bordered  table-white" id="step3_type2">
    <tbody>
        <tr>
            <td width="25%" style="text-align: right;"><?php /*__('Disable Scope')*/?>:</td>
            <td>
                <select name="AlertRules[disable_scope]">
                    <option value="1" <?php /*if ($post_data['disable_scope'] == 1) echo 'selected="selected"' */?>><?php /*__('Disable Entire Trunk')*/?></option>
                    <option value="2" <?php /*if ($post_data['disable_scope'] == 2) echo 'selected="selected"' */?>><?php /*__('Disable Specific Code')*/?></option>
                    <option value="3" <?php /*if ($post_data['disable_scope'] == 3) echo 'selected="selected"' */?>><?php /*__('Disable Specific Code Name')*/?></option>
                </select>
            </td>
        </tr> 
        <tr>
            <th width="25%" style="text-align: right;"><?php /*echo __('Auto-enable') */?>:</th>
            <th >
                <select name="AlertRules[auto_enable_type]" id="auto_enable_type">
                    <option value="1" <?php /*if ($post_data['auto_enable_type'] == 1) echo 'selected="selected"' */?> >Never</option>
                    <option value="2" <?php /*if ($post_data['auto_enable_type'] == 2) echo 'selected="selected"' */?> >After</option>
                </select>
                <span id="auto_enable_span"<?php /*if ($post_data['auto_enable_type'] == 1){echo "class='hidden'" ; } */?> >
                <input class="validate[required,custom[number]]"  name="AlertRules[auto_enable]" value="<?php /*echo $post_data['auto_enable'] */?>"  id="auto_enable" />min
                </span>
            </th>
        </tr>


    </tbody>
</table>-->
<div class="center">
    <a step="#step3" href data-toggle="tab" value="next"  id="previous4" class=" btn primary"><?php __('Previous')?></a>
    <input type="submit" value="Finish" id="finish" class="input in-submit btn btn-primary" />
    <!--<input type="submit" value="Finish" id="finish" class="input in-submit btn btn-primary" style="display: none;"  />-->
</div>
<!--<script type="text/javascript" src="--><?php //echo $this->webroot; ?><!--tiny_mce/tiny_mce.js"></script>-->
<script type="text/javascript" src="<?php echo $this->webroot ?>js/ckeditor_full/ckeditor.js"></script>

<script type="text/javascript">

    //    var toolbars = "styleselect,formatselect,fontselect,fontsizeselect,mybutton,bold,italic,underline,separator,strikethrough,justifyleft,justifycenter,justifyright, justifyfull,bullist,numlist,undo,redo,link,unlink";

    /* tinyMCE.init({
     mode: "textareas",
     theme: "advanced",
     editor_selector: "trouble_ticket_content",
     theme_advanced_buttons1: toolbars
     });
     */

    $(function() {
        $("#next3").click(function(){

            $("#step4").click();
        });
        $("#step4").click(function(){
            if ($("#rule_form").validationEngine('validate'))
            {

                return true;
            }
            return false;
        });
        $("#previous4").click(function() {
            $("#step3").click();
        });
        $("#finish").click(function() {

            if ($("#rule_form").validationEngine('validate'))
            {
                $("#step_").val(4);
                if (confirm("Do you want to activate the rule now ?")) {
                    $("#rule_form").find('#active').prop('checked', true);
                }else{
                    $("#rule_form").find('#active').prop('checked', false);
                }
                return true;
            }

            return false;
        });
        var value_init = $("#step3_type").val();
        if (value_init == 1)
        {
            $("#step3_type2").addClass('hidden');
        }
        else
        {
            $("#step3_type1").addClass('hidden');
        }

        if($("#is_email")[0].checked) {
            $("#step3_type1").removeClass('hidden');
            $("#step3_type2").addClass('hidden');
        } else {
            $("#step3_type2").removeClass('hidden');
            $("#step3_type1").addClass('hidden');
        }
        $("#is_email").click(function() {
            var value = this.checked;
            if (value == 1)
            {
                $("#step3_type1").removeClass('hidden');
                $("#step3_type2").addClass('hidden');
            }
            else
            {
                $("#step3_type2").removeClass('hidden');
                $("#step3_type1").addClass('hidden');
            }
        });

        $("#auto_enable_type").change(function() {
            var value = $(this).val();
            if (value == 2)
            {
                $("#auto_enable_span").removeClass('hidden');
            }
            else
            {
                $("#auto_enable_span").addClass('hidden');
            }
        });

    });

    CKEDITOR.replace('trouble_ticket_content');

    $('.mail_content_tags').find('span').click(function(){
        var $tag_value = $(this).html();
        var $this_textarea_id = $(this).closest('tr').find('textarea').attr('id');
        var editor = CKEDITOR.instances[$this_textarea_id];
        editor.insertHtml( $tag_value );
    });

    $("#is_block").change(function () {
        if($(this).is(":checked")) {
            $(".block-check").removeClass('hidden');
        } else {

            $(".block-check").addClass('hidden');
        }
    });

    <?php if($post_data['is_block']) {?>
    $(".block-check").removeClass('hidden');
    <?php } ?>

</script>
