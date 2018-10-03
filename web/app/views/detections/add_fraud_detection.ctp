<style>
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
    <li><a href="<?php echo $this->webroot ?>detections/fraud_detection"><?php __('Monitoring') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot  . $this->params['url']['url'] ?>">
        <?php __('Add Fraud Detection') ?></a></li>
</ul>


<div class="heading-buttons">
    <h4 class="heading"><?php __('Add Fraud Detection') ?></h4>

</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
    <a class="btn btn-icon btn-inverse glyphicons circle_arrow_left" href="<?php echo $this->webroot; ?>detections/fraud_detection"><i></i><?php __('Back')?></a>
</div>
<div class="clearfix"></div>

<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">

        <div class="widget-body">
            <form method="post" id="myform">
                <table class="form table table-condensed dynamicTable tableTools table-bordered ">
                    <colgroup>
                        <col width="30%">
                        <col width="70%">
                    </colgroup>

                    <tr style="display: none;">
                        <td class="right"><?php __('Active'); ?> </td>
                        <td>
                            <?php echo $form->input('active',array('type'=>'checkbox','label'=> false,'div'=>false)); ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="right"><?php __('Rule Name'); ?> </td>
                        <td>
                            <?php echo $form->input('rule_name',array('type'=>'text','label'=> false,'div'=>false,
                                'class' =>'rule_name validate[required,custom[onlyLetterNumberLine]]')); ?>
                        </td>
                    </tr>
                    <tr id="egress_trunks_tr">
                        <td class="right">
                            <?php __('Ingress Trunk List'); ?>
                        </td>
                        <td>
                            <select multiple="multiple" id="columns_select" name="ingress_trunks[]" class="width220 validate[required] select_mul" >
                                <?php foreach($ingresses_info as $client_name=>$ingress_info): ?>
                                    <optgroup label="<?php echo $client_name; ?>">
                                        <?php foreach($ingress_info as $ingress_id=>$ingress_name): ?>
                                            <option value="<?php echo $ingress_id ?>" <?php if (in_array($ingress_id,$selected_ingress)): ?> selected="selected"<?php endif; ?>>
                                                <?php echo $ingress_name ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </optgroup>
                                <?php endforeach; ?>
                            </select>
                        </td>
                    </tr>

                    <tr>
                         <td class="right"> <?php __('Select All')?> :</td>
                         <td >
                          <?php echo $form->input('select_all',array('type'=> 'checkbox','label'=>false,'div'=>false)); ?>
                        </td>
                    </tr>

                    <tr>
                        <td class="right" rowspan="4"><?php __('Fraud Checking Criteria'); ?> </td>
                        <td>
                            <span class="width120" style="display:inline-block;"><?php __('1 hour Minute'); ?></span>
                            <span>>=</span>
                            <?php $default = 1000; if(isset($this->params['pass'][0])){$default = '';} ?>
                            <?php echo $form->input('hourly_minute',array('type'=> 'text','label'=>false,'div'=>false,
                                'class' =>'validate[custom[onlyNumber]] width80','maxlength' => 10,'default'=> $default)); ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span class="width120" style="display:inline-block;"><?php __('1 hour Revenue'); ?></span>
                            <span>>=</span>
                            <?php $default = 100; if(isset($this->params['pass'][0])){$default = '';} ?>
                            <?php echo $form->input('hourly_revenue',array('type'=> 'text','label'=>false,'div'=>false,
                                'class' =>'validate[custom[onlyNumber]] width80','maxlength' => 10,'default'=> $default)); ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span class="width120" style="display:inline-block;"><?php __('24 hours Minute'); ?></span>
                            <span>>=</span>
                            <?php $default = 10000; if(isset($this->params['pass'][0])){$default = '';} ?>
                            <?php echo $form->input('daily_minute',array('type'=> 'text','label'=>false,'div'=>false,
                                'class' =>'validate[custom[onlyNumber]] width80','maxlength' => 10,'default'=> $default)); ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span class="width120" style="display:inline-block;"><?php __('24 hours Revenue'); ?></span>
                            <span>>=</span>
                            <?php $default = 1000; if(isset($this->params['pass'][0])){$default = '';} ?>
                            <?php echo $form->input('daily_revenue',array('type'=> 'text','label'=>false,'div'=>false,
                                'class' =>'validate[custom[onlyNumber]] width80','maxlength' => 10,'default'=> $default)); ?>
                        </td>
                    </tr>

                    <tr>
                        <td class="right"><?php __('Block'); ?></td>
                        <td>
                            <?php echo $form->input('is_block',array('type'=>'checkbox','label'=>false,'div'=>false)); ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="right"><?php __('Send Email'); ?></td>
                        <td>
                            <?php echo $form->input('is_send_mail',array('type'=>'checkbox','label'=>false,'div'=>false,
                                )); ?>
                        </td>
                    </tr>

                    <tr class="email_tmp">
                        <td class="right"><?php __('Email to')?> </td>
                        <td>
                            <?php echo $form->input('email_to',array('type'=>'radio','label'=>false,'div'=>false,
                                'options'=>$email_to_arr)); ?>
                        </td>
                    </tr>
                    <tr class="email_tmp">
                        <td class="right"><?php __('From email') ?> </td>
                        <td>
                            <select name="mail_template[fraud_detection_from]">
                                <option><?php __('Default') ?></option>
                                <?php foreach ($mail_senders as $mail_sender): ?>
                                    <option value="<?php echo $mail_sender[0]['id'] ?>" <?php if ($mail_sender[0]['id'] == $tmp[0][0]['fraud_detection_from']) echo 'selected="selected"' ?>><?php echo $mail_sender[0]['email'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                    </tr>
                    <tr class="email_tmp">
                        <td class="right"><?php echo __('Notification Subject') ?> </td>
                        <td><input type="text" class="validate[required]" name="mail_template[fraud_detection_subject]"
                                   value="<?php echo isset($tmp[0][0]['fraud_detection_subject']) ? $tmp[0][0]['fraud_detection_subject'] : ''; ?>" style="max-width: none; width: 90%;" />
                        </td>
                    </tr>
                    <tr class="email_tmp">
                        <td class="right"><?php echo __('Notification Content') ?> </td>
                        <td></td>
                    </tr>
                    <tr class="email_tmp">
                        <td colspan="2">
                            <textarea class="validate[required]" name="mail_template[fraud_detection_content]" id="fraud_detection_content">
                                <?php echo isset($tmp[0][0]['fraud_detection_content']) ? $tmp[0][0]['fraud_detection_content'] : ''; ?>
                            </textarea>
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
<script type="text/javascript" src="<?php echo $this->webroot ?>js/ckeditor_full/ckeditor.js"></script>
<script type="text/javascript">
    $(function() {
        let selected_all = '<?php echo $selected_all; ?>';

        $("#columns_select").multiSelect({
            selectableOptgroup: true,
            buttonWidth: 400,
            afterSelect: function(values){
                var all_options = $('#columns_select option').size();
                var selected_options = $('#columns_select option:selected').size();
                if(selected_options == all_options){
                    $('#select_all').attr('checked','checked');
                }
            },
            afterDeselect: function(values){
                $('#select_all').removeAttr('checked');
            },
        });

        $('#select_all').on('click', function(){
           if($(this).is(':checked')){
               $("#columns_select").multiSelect('select_all');
           }else{
               $("#columns_select").multiSelect('deselect_all');
           }
        });
        if(selected_all){
            $('#select_all').click();
        }
        send_mail_show($("#is_send_mail"));
        $("#myform").submit(function(){
            var columns_options = $("#columns_select option");
            var columns_size = $('#columns_select option:selected').size();//$("#columns option").size();
            if(columns_size == 0){
                jGrowl_to_notyfy('<?php __('Ingress Trunk can not be null'); ?>', {theme: 'jmsg-error'});
                return false;
            }
            var name = $(this).find('.rule_name').val();
            var id = '<?php echo isset($this->params['pass'][0]) ? base64_decode($this->params['pass'][0]) : ''; ?>';
            var flg = true;
            $.ajax({
                'url': '<?php echo $this->webroot . "detections/judge_fraud_rule_name_unique" ?>',
                'type': 'post',
                'async': false,
                'dataType': 'json',
                'data': {'rule_id': id,'rule_name':name},
                'success': function(data) {
                    if (data != 0) {
                        var tmp = '<?php __('The rule name [%s] is already in use!'); ?>';
                        var msg = tmp.replace('%s',name);
                        jGrowl_to_notyfy(msg, {theme: 'jmsg-error'});
                        flg = false;
                    }
                }
            });
//            columns_options.attr('selected','selected');
            return flg;
        });
        var column_select_values = $("#columns_select").val();
        $("#myform").on('reset',function(){
            $("#columns_select").val(column_select_values);
            $("#columns_select").multiSelect("refresh");
        })
        CKEDITOR.replace('fraud_detection_content');

        $("#is_send_mail").click(function(){
            send_mail_show($(this));
        });

    });

    function send_mail_show(opt)
    {
        var is_checked = opt.is(":checked");
        $(".email_tmp").hide();
        if(is_checked){
            $(".email_tmp").show();
        }
    }
</script>
