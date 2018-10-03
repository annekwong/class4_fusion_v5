<style type="text/css">
    .subject{
        max-width: none;
        width: 90%;
    }
    .ms-container ul.ms-list{
        width: 280px;
    }
    .ms-container{
        background: transparent url('<?php echo $this->webroot; ?>common/theme/scripts/plugins/forms/multiselect/img/switch.png') no-repeat 290px 80px;
    }
</style>
<ul class="breadcrumb">
    <li>
        <a href="<?php echo $this->webroot ?>alerts/invalid_number"><?php __('You are here'); ?>
        </a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot . $this->params['url']['url'] ?>">
        <?php __('Monitoring') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li>
        <?php if (isset($this->data['rule_name'])): ?>
            <?php echo __('Edit Rule', true); echo "[".$this->data['rule_name']."]"; ?>
        <?php else: ?>
            <?php echo __('Add Rule', true); ?>
        <?php endif; ?>
    </li>
</ul>

<div class="heading-buttons">
    <h4 class="heading">
        <?php if (isset($this->data['rule_name'])): ?>
            <?php echo __('Edit Rule', true); echo "[".$this->data['rule_name']."]"; ?>
        <?php else: ?>
            <?php echo __('Add Rule', true); ?>
        <?php endif; ?>
    </h4>

</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
    <?php echo $this->element('xback', Array('backUrl' => 'alerts/invalid_number')) ?>
</div>
<div class="clearfix"></div>
<div class="innerLR">
    <div class="widget widget-tabs widget-body-white">
        <div class="widget-body">
            <form method="post" id="rule_form" >
                <?php echo $form->input('id',array('type' => 'hidden')) ?>
                <div class="widget">
                    <div class="widget-head">
                        <h4 class="heading"><?php __('Criteria for Invalid Number'); ?>:</h4>
                    </div>
                    <div class="widget-body">
                        <table class="form table tableTools table-bordered  table-white">
                            <colgroup>
                                <col width="40%">
                                <col width="60%">
                            </colgroup>
                            <tbody>
                            <tr>
                                <td class="right"><?php __('Monitoring Rule Name')?>: </td>
                                <td>
                                    <?php echo $form->input('rule_name',array('type' => 'text','label' => false,
                                        'div' => false,'class' => 'validate[required,custom[onlyLetterNumberLine]]')) ?>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="widget">
                    <div class="widget-head">
                        <h4 class="heading"><?php __('Bad DNIS Criteria'); ?></h4>
                    </div>
                    <div class="widget-body">
                        <table class="form table tableTools table-bordered  table-white">
                            <colgroup>
                                <col width="40%">
                                <col width="60%">
                            </colgroup>
                            <tbody>
                            <tr>
                                <td class="right" rowspan="2"><?php __('Ingress')?> :</td>
                                <td>
                                    <?php echo $form->input('dnis_check_all',array('type' => 'checkbox','class' => 'dnis_check_all')) ?>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <?php echo $form->input('dnis_ingress',array('type' => 'select','label' => false,
                                        'class' => 'dnis_ingress_select validate[required]','div' => false,
                                        'multiple' => true,'options' => $ingress_trunk,'style' => 'height:200px;')) ?>
                                </td>
                            </tr>
                            <tr>
                                <td class="right"><?php __('Check Cycle')?> :</td>
                                <td>
                                    <?php __('Every'); ?>&nbsp;
                                    <?php echo $form->input('dnis_check_cycle',array('type' => 'select','label' => false,
                                        'div' => false,'options' => $check_cycle,'class' => 'width80')) ?>
                                    &nbsp;<?php __('minutes'); ?>
                                </td>
                            </tr>
                            <tr>
                                <td class="right"><?php __('Minimum Threshold')?> :</td>
                                <td>
                                    <?php echo $form->input('dnis_threshold',array('type' => 'text','label' => false,
                                        'div' => false,'class' => 'validate[required,custom[integer]]')) ?>
                                </td>
                            </tr>
                            <tr>
                                <td class="right">
                                    <span>
                                    <?php echo $form->input('dnis_limit_percent',array('type' => 'text','label' => false,
                                        'div' => false,'class' => 'validate[required,custom[integer],max[100],min[1]] width25','maxlength' => 3)) ?>%
                                        <?php __('has the following return codes'); ?>:
                                        </span>
                                </td>
                                <td>
                                    <?php echo $form->input('dnis_return_codes',array('type' => 'select','label' => false,
                                        'class' => 'validate[required] retrun_codes','div' => false,
                                        'multiple' => true,'options' => $return_codes,'style' => 'height:125px;')) ?>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="widget">
                    <div class="widget-head">
                        <h4 class="heading"><?php __('Action Taken'); ?></h4>
                    </div>
                    <div class="widget-body">
                        <table class="form table tableTools table-bordered  table-white">
                            <colgroup>
                                <col width="40%">
                                <col width="60%">
                            </colgroup>
                            <tbody>
                            <tr>
                                <td class="right"><?php __('Block')?> :</td>
                                <td>
                                    <?php echo $form->input('block',array('type' => 'select','label' => false,
                                        'div' => false,'options' => array('No','Yes'),'class' => 'is_block')) ?>
                                </td>
                            </tr>
                            <!--
                            <tr>
                                <td class="right"><?php __('Unblock')?> :</td>
                                <td>
                                    <?php echo $form->input('unblock',array('type' => 'select','label' => false,
                                        'div' => false,'options' => array('Never','After'),'class' => 'unblock_select')) ?>
                                    <span>
                                    <?php echo $form->input('unblock_min',array('type' => 'text','label' => false,
                                        'div' => false,'class' => 'validate[required,custom[integer] width25','maxlength' => 3)) ?>
                                    <?php __('min(s)'); ?>
                                    </span>
                                </td>
                            </tr>
                            -->
                            <tr>
                                <td class="right"><?php __('Send Email')?> :</td>
                                <td>
                                    <?php echo $form->input('send_email',array('type' => 'select','label' => false,
                                        'div' => false,'options' => array('No','Yes'),'class' => 'send_email')) ?>
                                </td>
                            </tr>
                            <tr class="send_mail_tr">
                                <td class="right"><?php __('Email To')?> :</td>
                                <td>
                                    <?php echo $form->input('email_to',array('type' => 'select','label' => false,
                                        'div' => false,'options' => array(__('Your Own NOC',true),__('Partner’s NOC',true),__('Both',true)))) ?>
                                </td>
                            </tr>
                            <tr class="send_mail_tr">
                                <td class="right"><?php __('Notification Subject')?> :</td>
                                <td>
                                    <?php echo $form->input('subject',array('type' => 'text','label' => false,
                                        'div' => false,'class' => 'validate[required] subject')) ?>
                                </td>
                            </tr>
                            <tr class="send_mail_tr">
                                <td class="right"><?php __('Notification Content')?> :</td>
                                <td>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <table class="form table tableTools table-bordered  table-white">
                            <tr class="send_mail_tr">
                                <td>
                                    <?php echo $form->input('content',array('type' => 'textarea','label' => false,
                                        'div' => false,'id' => 'mail_content')) ?>
                                </td>
                                <td class="mail_content_tags">
                                    <h4><i class="icon-tags"></i><?php __('Tags'); ?>:</h4>
                                    <?php foreach($mailTemplateTags as $tag): ?>
                                        <span class="btn btn-block btn-default">{<?php echo $tag; ?>}</span>
                                    <?php endforeach; ?>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="center">
                    <input type="submit" value="<?php __('Submit')?>" class="btn btn-primary"/>
                    <input type="reset"  value="<?php __('Revert')?>" class="btn btn-default" />
                </div>

            </form>
        </div>
    </div>
</div>

<script type="text/javascript" src="<?php echo $this->webroot ?>js/ckeditor_full/ckeditor.js"></script>
<script type="text/javascript">


    $(function(){
        var ani_ingress_select_mult = $(".ani_ingress_select").multiSelect({
            selectableOptgroup: true,
            buttonWidth: 400
        });
        var dnis_ingress_select_mult = $(".dnis_ingress_select").multiSelect({
            selectableOptgroup: true,
            buttonWidth: 400
        });

        $(".retrun_codes").multiSelect({
            selectableOptgroup: true,
            buttonWidth: 400
        });

        /*$("select[multiple]").multiSelect({
            selectableOptgroup: true,
            buttonWidth: 400
        });*/



        var ani_checked = $(".ani_check_all").is(":checked");
        if (ani_checked == true){
            $(".ani_ingress_select").children().attr('selected','selected');
            ani_ingress_select_mult.multiSelect("select_all");
        }
        $(".ani_check_all").click(function(){
            var checked = $(this).is(":checked");
            if (checked == true){
                ani_ingress_select_mult.multiSelect("select_all");
            }
        });

        var dnis_checked = $(".dnis_check_all").is(":checked");
        if (dnis_checked == true){
            $(".dnis_ingress_select").children().attr('selected','selected');
            dnis_ingress_select_mult.multiSelect("select_all");
        }
        $(".dnis_check_all").click(function(){
            var checked = $(this).is(":checked");
            if (checked == true){
                dnis_ingress_select_mult.multiSelect("select_all");
            }
        });

//        $(".is_block").change(function(){
//            var $this = $(this);
//            var $value = $this.val();
//            if ($value == 1){
//                $this.closest('tr').next().show();
//            }else{
//                $this.closest('tr').next().hide();
//            }
//        }).trigger('change');

        $(".unblock_select").change(function(){
            var $this = $(this);
            var $value = $this.val();
            if ($value == 1){
                $this.next('span').show();
            }else{
                $this.next('span').hide();
            }
        }).trigger('change');

        $(".send_email").change(function(){
            var $this = $(this);
            var $value = $this.val();
            if ($value == 1){
                $('.send_mail_tr').show();
            }else{
                $('.send_mail_tr').hide();
            }
        }).trigger('change');
    });

    CKEDITOR.replace('mail_content');

    $('.mail_content_tags').find('span').click(function(){
        var $tag_value = $(this).html();
        var $this_textarea_id = $(this).closest('tr').find('textarea').attr('id');
        var editor = CKEDITOR.instances[$this_textarea_id];
        editor.insertHtml( $tag_value );
    });
</script>
