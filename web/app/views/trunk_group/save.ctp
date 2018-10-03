<style type="text/css">
    .ms-container ul.ms-list{
        width: 280px;
    }
    .ms-container{
        background: transparent url('<?php echo $this->webroot; ?>common/theme/scripts/plugins/forms/multiselect/img/switch.png') no-repeat 290px 80px;
    }
</style>
<ul class="breadcrumb">
    <li><?php __('You are here'); ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Configuration') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Save Trunk Group') ?></li>
</ul>

<div class="heading-buttons">
</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
    <a class="btn btn-icon btn-inverse glyphicons circle_arrow_left" href="<?php echo $this->webroot; ?>trunk_group/index">
        <i></i><?php __('Back') ?>
    </a>
</div>
<div class="clearfix"></div>
<div class="innerLR">

        <div class="clearfix"></div>

        <div class="widget-body">
            <form action="" method="post" id="myform">
                <?php echo $form->input('group_id',array('type' => 'hidden','label' => false,'div' => false,'id' => 'groupId')); ?>
                <?php echo $form->input('trunk_type',array('type' => 'hidden','label' => false,'div' => false)); ?>

                        <table class="form table dynamicTable tableTools table-bordered  table-white">
                            <colgroup>
                                <col width="30%">
                                <col width="70%">
                            </colgroup>
                            <tr>
                                <td class="align_right padding-r10"><?php __('Group Name'); ?>*</td>
                                <td>
                                    <?php echo $form->input('group_name',array('type' => 'text','class' => 'validate[required,custom[onlyLetterNumberLineSpace],ajax[ajaxGroupName]]','label' => false,'div' => false)); ?>
                                </td>
                            </tr>
                            <tr>
                                <td width="15%" class="align_right padding-r10"><?php __('Trunk')?>*:</td>
                                <td>
                                    <select name="trunk[]"  class="validate[required]" style="width:250px;height:300px;" multiple="multiple" id="TrunkMultiple">
                                        <?php foreach($trunk_group as $all_group_name=>$trunk_info): ?>
                                            <?php if (!$all_group_name){$all_group_name = __(' ',true);} ?>
                                            <optgroup label="<?php echo $all_group_name; ?>">
                                                <?php if (strcmp($all_group_name,$group_name)): ?>
                                                    <?php foreach($trunk_info as $trunk_id=>$trunk_name): ?>
                                                        <option value="<?php echo $trunk_id ?>">
                                                            <?php echo $trunk_name ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                <?php else: ?>
                                                    <?php foreach($trunk_info as $trunk_id=>$trunk_name): ?>
                                                        <option value="<?php echo $trunk_id ?>" selected="selected">
                                                            <?php echo $trunk_name ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </optgroup>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                            </tr>
                        </table>
                <?php echo $this->element('common/submit_div'); ?>

            </form>
        </div>
</div>
<script type="text/javascript">
    $(function() {
        $.extend($.validationEngineLanguage.allRules,{ "ajaxGroupName": {
            "url": "<?php echo $this->webroot; ?>trunk_group/ajax_check_group_name",
            "extraData": "group_id=" + $("#groupId").val(),
            "alertText": "* <?php __('group name already exists'); ?>",
            "alertTextLoad": "* <?php __('Validating, please wait'); ?>"}
        });

        $('#TrunkMultiple').multiSelect();

        $("#myform").on('reset', function(){
            setTimeout(function(){
                $('#TrunkMultiple').multiSelect('refresh');
            });
        });

        function moveMessage(element) {
            $(element).css('left', '330px');
            $(element).children().first().html("* Select atleast one trunk<br>");
        }

        $("#myform").submit(function () {
            setTimeout(function () {
                moveMessage($('#TrunkMultiple').prev());
            }, 2000);
        });

    });
</script>



