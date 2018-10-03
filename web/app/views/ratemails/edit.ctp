<style type="text/css">
.form .value, .list-form .value {
    width: 350px;
    text-align:left;
    padding-left:10px;
}
.form .label, .list-form .label {
    width: 150px;
}
</style>

<div id="title">
    <h1><?php echo __('Configuration',true);?>&gt;&gt;<?php echo __('Rate Amendment Template',true);?> </h1>
</div>

<div class="container">
    <form id="myform" name="myform" method="post" action="<?php echo $this->webroot ?>ratemails/edit/<?php print($this->params['pass'][0]); ?>">
        <table class="list list-form">
            <thead>
                <tr>
                    <td colspan="3"><?php __('Rate Amendment Template Setting'); ?></td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="label"><?php echo __('Name',true);?>:</td>
                    <td class="value"><input type="text" name="name" style="width:200px;" value="<?php echo $template['Ratemail']['name']; ?>" /></td>
                    <td class="help"><?php echo __('Give a name for this template',true);?></td>
                </tr>
                <tr>
                    <td class="label"><?php echo __("Sender's Email",true);?>:</td>
                    <td class="value"><input type="text" name="from_address" style="width:200px;" value="<?php echo $template['Ratemail']['from_address']; ?>" /></td>
                    <td class="help"><?php echo __("The sender's email address",true);?></td>
                </tr>
                <tr>
                    <td class="label"><?php echo __("Sender's Password",true);?>:</td>
                    <td class="value"><input type="password" name="mail_password" style="width:200px;" value="<?php echo $template['Ratemail']['mail_password']; ?>" /></td>
                    <td class="help"><?php echo __("The sender's email password",true);?></td>
                </tr>
                <tr>
                    <td class="label"><?php echo __("Sender's Name",true);?>:</td>
                    <td class="value"><input type="text" name="from_name" style="width:200px;" value="<?php echo $template['Ratemail']['from_name']; ?>" /></td>
                    <td class="help"><?php echo __("The sender's name",true);?></td>
                </tr>
                <tr>
                    <td class="label"><?php echo __('Subject',true);?>:</td>
                    <td class="value"><input type="text" name="subject" style="width:300px;" value="<?php echo $template['Ratemail']['subject']; ?>" /></td>
                    <td class="help"><?php echo __('To give a title to this template',true);?></td>
                </tr>
                <tr>
                    <td class="label"><?php echo __('Content',true);?>:</td>
                    <td class="value"><textarea name="content" id="content" style="width:300px;height:200px;"><?php echo $template['Ratemail']['content']; ?></textarea></td>
                    <td class="help"><?php echo __('To give a content to this template.',true);?>You can also put"<span id="tech-prefix" style="cursor:pointer;color:red;">{tech-prefix}</span>" into box.</td>
                </tr>
                <tr>
                    <td colspan="3"><input type="submit" value="<?php echo __('submit',true);?>" />&nbsp;<input type="reset" value="Revert" class="input in-submit" /> </td>
                </tr>
            </tbody>
        </table>
    </form>
</div>

<script type="text/javascript" src="<?php echo $this->webroot ?>js/jquery.textareapos.js" refer="refer"></script>
<script type="text/javascript" refer="refer">
$(function() {
    $('#tech-prefix').click(function() {
        var index = $('#content').getCurPos();
        var value = $('#content').val();
        var f_text = value.substring(0, index);
        var b_text = value.substring(index);
        var value = f_text + $(this).text() + b_text;
        $('#content').val(value);
    });
});
</script>