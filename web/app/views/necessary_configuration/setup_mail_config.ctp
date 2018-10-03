<style>
    input{width: 220px;}
</style>
<ul class="breadcrumb">
    <li><?php __('Configuration') ?></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Setup'); ?></h4>
    <div class="buttons pull-right">
    </div>
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>

<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <?php echo $this->element("currs/step", array('now' => '5')); ?>
        <div class="widget-body">
            <form class="form-horizontal" method="post" action="">
                <div class="row-fluid widget-body">
                    <div class="span4">
                    </div>
                    <div class="span8">

                        <div class="control-group">
                            <label class="control-label" for="smtphost"><?php __('Mailserverhost') ?>:</label>
                            <div class="controls">
                                <?php echo $form->input('smtphost', array('value'=> array_keys_value($data, '0.0.smtphost'),'label' => false, 'div' => false, 'type' => 'text', 'class' => 'validate[required]')); ?>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="smtpport"><?php __('SMTP Port') ?>:</label>
                            <div class="controls">
                                <?php echo $form->input('smtpport', array('value'=> array_keys_value($data, '0.0.smtpport'),'label' => false, 'div' => false, 'type' => 'text', 'class' => 'validate[required,custom[integer]]')); ?>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="emailusername"><?php __('SMTP Username') ?>:</label>
                            <div class="controls">
                                <?php echo $form->input('emailusername', array('value'=> array_keys_value($data, '0.0.emailusername'),'label' => false, 'div' => false, 'type' => 'text', 'class' => 'validate[required]','autocomplete'=>'off')); ?>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="emailpassword"><?php __('SMTP Password') ?>:</label>
                            <div class="controls">
                                <?php echo $form->input('emailpassword', array('value'=> array_keys_value($data, '0.0.emailpassword'),'label' => false, 'div' => false, 'type' => 'password', 'class' => 'validate[required]')); ?>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="loginemail"><?php __('SMTP Login Authentication') ?>:</label>
                            <div class="controls">
                                <?php
                                echo $form->input('loginemail', array('options' => array('true' => __('true', true), 'false' => __('false', true)), 'selected' => array_keys_value($data, '0.0.loginemail'), 'label' => false,
                                    'div' => false, 'type' => 'select'));
                                ?>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="fromemail"><?php __('From Email') ?>:</label>
                            <div class="controls">
                                <?php echo $form->input('fromemail', array('value'=> array_keys_value($data, '0.0.fromemail'),'label' => false, 'div' => false, 'type' => 'text', 'class' => 'validate[required,custom[email]')); ?>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="emailname"><?php __('Email Sender Name') ?>:</label>
                            <div class="controls">
                                <?php echo $form->input('emailname', array('value'=> array_keys_value($data, '0.0.emailname'),'label' => false, 'div' => false, 'type' => 'text', 'class' => 'validate[required]')); ?>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="smtp_secure"><?php __('SMTP Secure') ?>:</label>
                            <div class="controls">
                                <?php
                                echo $form->input('smtp_secure', array( 'options' => $secure_arr, 'selected' => array_keys_value($data, '0.0.smtp_secure'), 'label' => false,
                                    'div' => false, 'type' => 'select'));
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="form_footer" class="button-groups center">
                    <input class="input in-submit btn btn-primary" value="<?php echo __('submit') ?>" type="submit">
                    <input class="input in-button btn btn-default" value="<?php echo __('reset') ?>" type="reset"   style="margin-left: 20px;">
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(function () {
//        $("div.navbar").hide();
        $.gritter.add({
            title: '<?php __('Setup Mail Config'); ?>',
            text: '<?php __('Your system up and running, you need to set the Mail Config.'); ?>',
            sticky: true
        });
    });
</script>