<style>
    .parentFormundefined{
        z-index: 9999;
    }
</style>
<ul class="breadcrumb">
    <li><?php __('You are here'); ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>mail_sender">
        <?php __('Configuration') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>mail_sender">
        <?php echo __('Email Sender'); ?></a></li>
</ul>
<?php $write = $_SESSION['role_menu']['Configuration']['mail_sender']['model_w']; ?>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Email Sender'); ?></h4>
</div>
<?php if ($write) { ?>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
    <a class="btn btn-primary btn-icon glyphicons circle_plus" id="add" href="###"><i></i> <?php __('Create new'); ?></a>
</div>
<?php }?>
<div class="clearfix"></div>
<div class="innerLR">

    <div class="widget widget-heading-simple widget-body-white">
        <div class="widget-body">

            <?php if (!count($this->data)): ?>
                <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary" style="display:none;">
                    <thead>
                        <tr>
                            <th><?php __('Name') ?></th>
                            <th><?php __('Mail Server') ?></th>
                            <th><?php __('Mail Port') ?></th>
                            <th><?php __('Username') ?></th>
                            <th><?php __('Password') ?></th>
                            <th><?php __('Authentication') ?></th>
                            <th><?php __('Secure') ?></th>
                            <th><?php __('Email Address') ?></th>
                            <th><?php __('Last Modified On') ?></th>
                            <th><?php __('Modified By') ?></th>
                            <th><?php __('Action') ?></th>
                        </tr>
                    </thead>

                    <tbody>

                    </tbody>
                </table>

            <?php else: ?>
                <div class="clearfix"></div>
                <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
                    <thead>
                        <tr>
                            <th><?php __('Name') ?></th>
                            <th><?php __('Mail Server') ?></th>
                            <th><?php __('Mail Port') ?></th>
                            <th><?php __('Username') ?></th>
                            <th><?php __('Password') ?></th>
                            <th><?php __('Authentication') ?></th>
                            <th><?php __('Secure') ?></th>
                            <th><?php __('Email Address') ?></th>
                            <th><?php __('Last Modified On') ?></th>
                            <th><?php __('Modified By') ?></th>
                            <th><?php __('Action') ?></th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($this->data as $item): ?>
                            <tr>
                                <td><?php echo $item['MailSender']['name']; ?></td>
                                <td><?php echo $item['MailSender']['smtp_host']; ?></td>
                                <td><?php echo $item['MailSender']['smtp_port']; ?></td>
                                <td><?php echo $item['MailSender']['username']; ?></td>
                                <td>*</td>
                                <td><?php echo $item['MailSender']['loginemail']; ?></td>
                                <td><?php echo $secures[$item['MailSender']['secure']]; ?></td>
                                <td><?php echo $item['MailSender']['email']; ?></td>
                                <td><?php echo $item['MailSender']['last_modified_on']; ?></td>
                                <td><?php echo $item['MailSender']['modified_by']; ?></td>
                                <td>
                                    <a title="<?php __('Test') ?>" class="test_mail" href="###" data-id="<?php echo $item['MailSender']['id'] ?>" >
                                        <i class="icon-envelope"></i>
                                    </a>
                                    <?php if ($write) { ?>
                                    <a title="<?php __('Edit') ?>" class="edit_item" href="###" control="<?php echo $item['MailSender']['id'] ?>" >
                                        <i class="icon-edit"></i>
                                    </a>
                                    <?php }?>
                                    <?php if ($write) { ?>
                                    <a title="<?php __('Delete') ?>" onclick="return myconfirm('Are you sure to delete it?', this)" class="delete" href='<?php echo $this->webroot; ?>mail_sender/delete/<?php echo base64_encode($item['MailSender']['id']) ?>'>
                                        <i class="icon-remove"></i>
                                    </a>
                                    <?php }?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <div class="row-fluid separator">
                    <div class="pagination pagination-large pagination-right margin-none">
                        <?php echo $this->element('xpage'); ?>
                    </div> 
                </div>
                <div class="clearfix"></div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    jQuery(function() {

        /**
         * Taken from http://stackoverflow.com/questions/46155/validate-email-address-in-javascript
         * @param email
         * @returns {boolean}
         */
        function validateEmail(email) {
            var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            return re.test(email);
        }

        $(".test_mail").click(function() {
            let confId = $(this).data('id');
            bootbox.prompt("Input your email",
                function (result) {
                    if (!validateEmail(result)) {
                        jQuery.jGrowl('Email is not correct!', {theme: 'jmsg-error'});
                    } else {
                        $.post('<?php echo $this->webroot; ?>mail_sender/sendTestEmail',
                            {
                                'serverId': confId,
                                'email': result
                            },
                            function (data) {
                                if(data == 1) {
                                    jQuery.jGrowl('Test letter is sent to your email!', {theme: 'jmsg-success'});
                                } else {
                                    jQuery.jGrowl(data, {theme: 'jmsg-error'});
                                }
                            }
                        );
                    }
                }
            );
        });

        jQuery('#add').click(function() {
            $('.msg').hide();
            $('table.list').show();
            jQuery('table.list tbody').trAdd({
                ajax: "<?php echo $this->webroot ?>mail_sender/modify_panel",
                action: "<?php echo $this->webroot ?>mail_sender/modify_panel",
                insertNumber: 'first',
                removeCallback: function() {
                    if (jQuery('table.list tr').size() == 1) {
                        jQuery('table.list').hide();
                    }
                },
                onsubmit: function() {
                    var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                    var host = $('#MailSenderSmtpHost').validationEngine('validate');
                    var port = $('#MailSenderSmtpPort').validationEngine('validate');
                    var email = $('#MailSenderEmail').validationEngine('validate');
                    if(!re.test($('#MailSenderEmail').val())){
                        $('#MailSenderEmail').before('<div class="MailSenderEmailformError parentFormundefined formError" style="opacity: 0.87; position: absolute; top: 45px; left: 830px; margin-top: -40px;"><div class="formErrorContent">* Invalid email address<br></div><div class="formErrorArrow"><div class="line10"><!-- --></div><div class="line9"><!-- --></div><div class="line8"><!-- --></div><div class="line7"><!-- --></div><div class="line6"><!-- --></div><div class="line5"><!-- --></div><div class="line4"><!-- --></div><div class="line3"><!-- --></div><div class="line2"><!-- --></div><div class="line1"><!-- --></div></div></div>');

                        return false;
                    }
                    var name = $('#MailSenderName').validationEngine('validate');
                    if (host || port || email || name)
                    {
                        return false;
                    }
                    return true;
                }
            });
            jQuery(this).parent().parent().show();
            jQuery('table.list').width(jQuery('table.list').outerWidth()).trigger('resize');

        });

        jQuery('a.edit_item').click(function() {
            var self = this;
            jQuery(this).parent().parent().trAdd({
                action: '<?php echo $this->webroot ?>mail_sender/modify_panel/' + jQuery(this).attr('control'),
                ajax: '<?php echo $this->webroot ?>mail_sender/modify_panel/' + jQuery(this).attr('control'),
                saveType: 'edit',
                onsubmit: function() {
                    var host = $('#MailSenderSmtpHost').validationEngine('validate');
                    var port = $('#MailSenderSmtpPort').validationEngine('validate');
                    var email = $('#MailSenderEmail').validationEngine('validate');
                    var name = $('#MailSenderName').validationEngine('validate');

                    if (host || port || email || name)
                    {
                        return false;
                    }
                    return true;
                }
            });
           // resize table
           jQuery(self).closest('table').width(jQuery(self).closest('table').outerWidth()).trigger('resize');
        });


<?php if (!count($this->data)): ?>
            $("#add").click();
<?php endif; ?>
    });
</script>
