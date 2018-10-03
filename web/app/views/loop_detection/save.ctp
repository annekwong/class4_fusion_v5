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
    <li><?php __('Monitoring') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo $this->pageTitle; ?></li>
</ul>

<div class="heading-buttons">
</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
    <a class="btn btn-icon btn-inverse glyphicons circle_arrow_left" href="<?php echo $this->webroot; ?>loop_detection/index">
        <i></i><?php __('Back') ?>
    </a>
</div>
<div class="clearfix"></div>
<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="clearfix"></div>

        <div class="widget-body">
            <form action="" method="post" id="myform">
                <?php echo $form->input('id',array('type' => 'hidden','label' => false,'div' => false,'id' => 'ruleId')); ?>
                <div class="widget">
                    <div class="widget-head">
                        <h4 class="heading"><?php __('Basic Info'); ?></h4>
                    </div>
                    <div class="widget-body">
                        <table class="form table dynamicTable tableTools table-bordered  table-white">
                            <colgroup>
                                <col width="30%">
                                <col width="70%">
                            </colgroup>
                            <tr>
                                <td class="align_right padding-r10"><?php __('Rule Name'); ?></td>
                                <td>
                                    <?php echo $form->input('rule_name',array('type' => 'text','class' => 'validate[required,custom[onlyLetterNumberLineSpace],ajax[ajaxLoopDetectionRule]]','label' => false,'div' => false)); ?>
                                </td>
                            </tr>
                            <tr>
                                <td width="15%" class="align_right padding-r10"><?php __('Ingress Trunk')?>:</td>
                                <td>
                                    <select name="ingress_trunk[]"  class="validate[required]" style="width:250px;height:300px;" multiple="multiple" id="IngressMultiple">
                                        <?php foreach($ingress_group as $client_name=>$ingress_info): ?>
                                            <optgroup label="<?php echo $client_name; ?>">
                                                <?php foreach($ingress_info as $ingress_id=>$ingress_name): ?>
                                                    <option value="<?php echo $ingress_id ?>" <?php if (in_array($ingress_id,$ingress_arr)): ?> selected="selected"<?php endif; ?>>
                                                        <?php echo $ingress_name ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </optgroup>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="widget">
                    <div class="widget-head">
                        <h4 class="heading"><?php __('Detection Criteria'); ?></h4>
                    </div>
                    <div class="widget-body">
                        <table class="form table dynamicTable tableTools table-bordered  table-white">
                            <tr>
                                <td class="center">
                                    <?php __('The same ANI and DNIS occurs for more than'); ?>
                                    &nbsp;&nbsp;
                                    <?php echo $form->input('number',array('type' => 'text','class' => 'width40 validate[required,custom[integer]]','label' => false,'div' => false)); ?>
                                    &nbsp;&nbsp;
                                    <?php __('times'); ?>&nbsp;&nbsp;<?php __('within'); ?>
                                    &nbsp;&nbsp;
                                    <?php echo $form->input('counter_time',array('type' => 'text','class' => 'width40 validate[required,custom[integer]]','label' => false,'div' => false)); ?> s.
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="widget">
                    <div class="widget-head">
                        <h4 class="heading"><?php __('Action'); ?></h4>
                    </div>
                    <div class="widget-body">
                        <table class="form table dynamicTable tableTools table-bordered  table-white">
                            <tr>
                                <td class="center">
                                    <?php __('Block this ANI and DNIS combination for'); ?>
                                    &nbsp;&nbsp;
                                    <?php echo $form->input('block_time',array('type' => 'text','class' => 'width40 validate[required,custom[integer]]','label' => false,'div' => false)); ?> s.
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <?php echo $this->element('common/submit_div'); ?>

            </form>
        </div>
    </div>
</div>
<script type="text/javascript">

    $(function() {
        $.extend($.validationEngineLanguage.allRules,{ "ajaxLoopDetectionRule": {
            "url": "<?php echo $this->webroot; ?>loop_detection/ajax_check_rule_name",
            "extraData": "rule_id=" + $("#ruleId").val(),
            "alertText": "* <?php __('rule name already exists'); ?>",
            "alertTextLoad": "* <?php __('Validating, please wait'); ?>"}
        });

        $('#IngressMultiple').multiSelect();

        $("#myform").on('reset', function(){
            setTimeout(function(){
                $('#IngressMultiple').multiSelect('refresh');
            });
        });

        function moveMessage(element) {
            $(element).css('left', '300px');
            $(element).children().first().html("* Select the Ingress Trunk<br>");
        }

        $("#myform").submit(function () {

            setTimeout(function () {
                moveMessage($('#IngressMultiple').prev());
            }, 2000);
        });
</script>



