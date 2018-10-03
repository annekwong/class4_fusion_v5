<style>
    input{width: 220px;}
</style>
<ul class="breadcrumb">
    <li><?php __('Switch') ?></li>
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
        <?php echo $this->element("currs/step", array('now' => '7')); ?>
        <div class="widget-body">
            <form class="form-horizontal" method="post" action="">
                <div class="row-fluid widget-body">
                    <div class="span4">
                    </div>
                    <div class="span8">

                        <div class="control-group">
                            <label class="control-label" for="name"><?php __('Name') ?>:</label>
                            <div class="controls">
                                <?php echo $form->input('name', array('label' => false, 'div' => false, 'type' => 'text', 'class' => 'validate[required]')); ?>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="lan_ip"><?php __('Info IP') ?>:</label>
                            <div class="controls">
                                <?php echo $form->input('lan_ip', array('label' => false, 'div' => false, 'type' => 'text', 'class' => 'validate[required,custom[ipv4]]')); ?>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="lan_port"><?php __('Info Port') ?>:</label>
                            <div class="controls">
                                <?php echo $form->input('lan_port', array('label' => false, 'div' => false, 'type' => 'text', 'class' => 'validate[required,custom[integer]]')); ?>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="active_call_ip"><?php __('Active Call IP')?>:</label>
                            <div class="controls">
                                <?php echo $form->input('active_call_ip', array('label' => false, 'div' => false, 'type' => 'text', 'class' => 'validate[custom[ipv4]]')); ?>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="active_call_port"><?php __('Active Call Port')?>:</label>
                            <div class="controls">
                                <?php echo $form->input('active_call_port', array('label' => false, 'div' => false, 'type' => 'text', 'class' => 'validate[custom[integer]]')); ?>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="paid_replace_ip"><?php __('PAID Replace IP')?>:</label>
                            <div class="controls">
                                <?php echo $form->input('paid_replace_ip', array('label' => false, 'div' => false, 'type' => 'checkbox')); ?>
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
            title: '<?php __('Setup VOIP Gateway'); ?>',
            text: '<?php __('Your system up and running, you need to set the Gateway IP.'); ?>',
            sticky: true
        });
    });
</script>
