<ul class="breadcrumb">
    <li><?php __('You are here'); ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>agent/management">
        <?php __('Agent') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot . $this->params['url']['url'] ?>">
        <?php echo $this->pageTitle; ?></a></li>
</ul>


<div class="heading-buttons">
    <h4 class="heading"><?php echo $this->pageTitle; ?></h4>

</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
    <a class="btn btn-icon btn-inverse glyphicons circle_arrow_left" href="<?php echo $this->webroot; ?>agent/index"><i></i><?php __('Back')?></a>
</div>
<div class="clearfix"></div>

<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">

        <div class="widget-body">
            <div class="widget">
                <div class="widget-head"><h4 class="heading"><?php __('Basic Info') ?></h4></div>
                <div class="widget-body">
                    <form method="post" id="myform">
                        <table class="form table table-condensed dynamicTable tableTools table-bordered ">
                            <colgroup>
                                <col width="40%">
                                <col width="60%">
                            </colgroup>
                            <tr>
                                <td class="align_right padding-r20"><?php echo __('Agent Name') ?>* </td>
                                <td>
                                    <?php echo $form->input('agent_name', array('label' => false, 'div' => false, 'type' => 'text',
                                        'maxLength' => '100', 'class' => 'width220 validate[required,custom[onlyLetterNumberLineSpace],funcCall[notEqualAdmin]]')) ?>
                                </td>
                            </tr>
                            <tr>
                                <td class="align_right padding-r20"><?php echo __('status') ?> </td>
                                <td>
                                    <?php
                                    $st = array('true' => __('Active', true), 'false' => __('Inactive', true));
                                    echo $form->input('status', array('options' => $st, 'label' => false, 'div' => false,
                                        'type' => 'select', 'class' => 'input in-text in-select'))
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td class="align_right padding-r20"><?php echo __('E-mail', true); ?> </td>
                                <td> <?php echo $form->input('email', array('label' => false, 'div' => false,
                                        'maxLength' => '100', 'class' => 'width220 validate[custom[email]]')) ?></td>
                            </tr>

                            <tr>
                                <td class="align_right padding-r20"><?php echo __('Method Type') ?> </td>
                                <td>
                                    <?php
                                    echo $form->input('method_type', array('options' => $method_type, 'label' => false, 'div' => false,
                                        'type' => 'select', 'class' => 'input in-text in-select'))
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td class="align_right padding-r20"><?php echo __('Commission', true); ?> </td>
                                <td> <?php echo $form->input('commission', array('label' => false, 'div' => false,
                                        'maxLength' => '5', 'class' => 'width220 validate[custom[number],max[100],min[0]]')) ?>
                                    %
                                </td>
                            </tr>
                            <tr>
                                <td class="align_right padding-r20"><?php echo __('Frequency Type') ?> </td>
                                <td>
                                    <?php
                                    echo $form->input('frequency_type', array('options' => $frequency_type, 'label' => false,
                                        'div' => false,'type' => 'select', 'class' => 'input in-text in-select'))
                                    ?>
                                </td>
                            </tr>
<!--                            --><?php //if(!isset($encode_agent_id)): ?>
                            <tr>
                                <td class="align_right"><?php __('Login Username')?> </td>
                                <td>
                                    <?php echo $form->input('user_id', array('label' => false,'div' => false,
                                        'type' => 'hidden')) ?>
                                    <?php
                                    $user_id = isset($this->data['user_id']) ? $this->data['user_id'] : '';
                                    echo $form->input('login_username', array('label' => false, 'div' => false,'autocomplete' => 'off',
                                        'id' => 'user_id_'.$user_id,
                                        'class' => 'width220 validate[custom[onlyLetterNumberLine],funcCall[notEqualAdmin],ajax[ajaxCheckUser]]')) ?>
                                </td>
                            </tr>
                            <tr>
                                <td class="align_right"><?php __('Login Password')?> </td>
                                <td>
                                    <?php echo $form->input('login_password', array('label' => false,'div' => false,
                                        'class' => 'width220','onfocus' => "this.type='password'")) ?>
                                </td>
                            </tr>
<!--                            --><?php //endif; ?>
                            <tr>
                                <td class="align_right"><?php __('Portal Edit Permission')?> </td>
                                <td>
                                    <?php echo $form->input('edit_permission', array('label' => false,'div' => false,
                                        'class' => 'width220','type' => 'checkbox')) ?>
                                </td>
                            </tr>

                        </table>
                        <div class="separator"></div>
                        <div class="center">
                            <input type="submit" value="<?php __('Submit')?>" class="btn btn-primary"/>
                            <input type="reset"  value="<?php __('Revert')?>" class="btn btn-default" />
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">

    $(function() {
        var agent_id = '<?php echo isset($this->data["agent_name"]) ? $this->data["agent_name"] : ""?>';
        if(agent_id){
            $('#agent_name').attr('readonly', 'readonly');
        }
    });
</script>

