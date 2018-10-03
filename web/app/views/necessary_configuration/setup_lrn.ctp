<ul class="breadcrumb">
    <li><?php __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Configuration') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Setup[LRN]') ?></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php __('LRN Group Setting') ?></h4>

</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
</div>
<div class="clearfix"></div>

<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <?php echo $this->element("currs/step", array('now' => '8')); ?>
        <div class="widget-body">

            <div class="clearfix"></div>
            <div id="container">
                <form method="post" action="">
                    <table class="footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary" id="mylist">
                        <thead>
                            <tr>
                                <th></th>
                                <th><?php __('Name'); ?></th>
                                <th><?php __('Strategy'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td></td>
                                <td>
                                    <input type="text" name="lrn[name]" class="validate[required]" />
                                </td>
                                <td>
                                    <?php echo $form->input('rule', array('name' => 'lrn[rule]', 'label' => false, 'div' => false, 'type' => 'select', 'options' => $strategies)); ?>
                                </td>
                            </tr>
                            <tr style="height:auto">
                                <td colspan="4">
                                    <div style="padding:5px;"> 
                                        <table class="footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
                                            <tr>
                                                <td>#</td>
                                                <td><?php __('IP') ?></td>
                                                <td><?php __('Port') ?></td>
                                                <td><?php __('Timeout') ?></td>
                                                <td><?php __('Retry') ?></td>
                                                <td><?php __('Option') ?></td>
                                                <td><?php __('Option interval') ?></td>
                                                <td><?php __('Dynamic Timeout') ?></td>
                                                <td><?php __('Filter Timeout') ?></td>
                                            </tr>
                                            <tr>
                                                <td>1</td>
                                                <td>
                                                    <input type="text" name="lrnitem[0][ip]" class="validate[required,custom[ipv4]]" />
                                                </td>
                                                <td>
                                                    <input type="text" name="lrnitem[0][port]" class="validate[required,custom[integer]]" />
                                                </td>
                                                <td>
                                                    <input type="text" name="lrnitem[0][timeout]" class="validate[custom[integer]]" />
                                                </td>
                                                <td>
                                                    <input type="text" name="lrnitem[0][retry]" class="validate[custom[integer]]"/>
                                                </td>
                                                <td>
                                                    <?php echo $form->input('option', array('class' => 'option1', 'name' => 'lrnitem[0][option]', 'label' => false, 'div' => false, 'type' => 'checkbox')); ?>
                                                </td>
                                                <td>
                                                    <input type="text" name="lrnitem[0][option_interval]" disabled="disabled" class="option1_trgg validate[custom[integer]]"  />
                                                </td>
                                                <td>
                                                    <?php echo $form->input('dynamic_timeout1', array('class' => 'option1_trgg', 'name' => 'lrnitem[0][dynamic_timeout]', 'label' => false, 'div' => false, 'type' => 'checkbox', 'disabled' => true)); ?>
                                                </td>
                                                <td>
                                                    <?php echo $form->input('filter_timeout1', array('class' => 'option1_trgg', 'name' => 'lrnitem[0][filter_timeout]', 'label' => false, 'div' => false, 'type' => 'checkbox', 'disabled' => true)); ?>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>2</td>
                                                <td>
                                                    <input type="text" name="lrnitem[1][ip]" class="validate[required,custom[ipv4]]" />
                                                </td>
                                                <td>
                                                    <input type="text" name="lrnitem[1][port]" class="validate[required,custom[integer]]" />
                                                </td>
                                                <td>
                                                    <input type="text" name="lrnitem[1][timeout]" class="validate[custom[integer]]" />
                                                </td>
                                                <td>
                                                    <input type="text" name="lrnitem[1][retry]" class="validate[custom[integer]]"/>
                                                </td>
                                                <td>
                                                    <?php echo $form->input('option', array('class' => 'option2', 'name' => 'lrnitem[1][option]', 'label' => false, 'div' => false, 'type' => 'checkbox')); ?>
                                                </td>
                                                <td>
                                                    <input type="text" name="lrnitem[1][option_interval]" disabled="disabled" class="option2_trgg validate[custom[integer]]"  />
                                                </td>
                                                <td>
                                                    <?php echo $form->input('dynamic_timeout2', array('class' => 'option2_trgg', 'name' => 'lrnitem[1][dynamic_timeout]', 'label' => false, 'div' => false, 'type' => 'checkbox', 'disabled' => true)); ?>
                                                </td>
                                                <td>
                                                    <?php echo $form->input('filter_timeout2', array('class' => 'option2_trgg', 'name' => 'lrnitem[1][filter_timeout]', 'label' => false, 'div' => false, 'type' => 'checkbox', 'disabled' => true)); ?>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div id="form_footer" class="widget-body center">
                        <input class="input in-submit btn btn-primary" value="<?php echo __('submit') ?>" type="submit">
                        <input class="input in-button btn btn-default" value="<?php echo __('reset') ?>" type="reset"   style="margin-left: 20px;">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(function () {
        $("div.navbar").hide();
        $.gritter.add({
            title: '<?php __('Setup LRN'); ?>',
            text: '<?php __('Your system up and running, you need to set the LRN.'); ?>',
            sticky: true
        });
        $(".option1").click(function () {
            var checked = $(this).attr('checked');
            var disabled = true;
            if (checked == 'checked')
            {
                disabled = false;
            }
            $(".option1_trgg").attr('disabled', disabled);
        });
        $(".option2").click(function () {
            var checked = $(this).attr('checked');
            var disabled = true;
            if (checked == 'checked')
            {
                disabled = false;
            }
            $(".option2_trgg").attr('disabled', disabled);
        });
    });
</script>