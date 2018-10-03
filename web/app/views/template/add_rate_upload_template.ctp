<style>
    .ms2side__options{
        width: 100px !important;
    }
</style>

<ul class="breadcrumb">
    <li><?php __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>template/rate_upload_template">
        <?php echo __('Template', true); ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot . $this->params['url']['url'] ?>">
        <?php echo $this->pageTitle; ?></a></li>
</ul>
<div class="heading-buttons">
    <h4 class="heading"><?php echo $this->pageTitle; ?></h4>
</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
    <a href="<?php echo $this->webroot ?>template/rate_upload_template" class="link_back btn btn-icon btn-inverse glyphicons circle_arrow_left"><i></i><?php echo __('goback', true); ?></a>
</div>
<div class="clearfix"></div>
<div class="innerLR">

    <div class="widget widget-heading-simple widget-body-white">
        <div class="widget-body">
            <?php echo $form->create('RateUploadTemplate',array('method' => 'post','url' => array('controller' => 'template','action'=>'add_rate_upload_template'))); ?>
            <input type="hidden" value="<?php echo $template_id; ?>" id="template_id" />
            <table class="form footable table dynamicTable tableTools table-bordered  table-white default footable-loaded">
                <colgroup>
                    <col width="37%">
                    <col width="63%">
                </colgroup>
                <tr>
                    <td class="align_right padding-r10"><?php __('Template Name'); ?>:</td>
                    <td>
                        <?php echo $form->input('name',array('type' => 'text','label' => false,'div' => false,
                            'class' => 'validate[required]'
                        )); ?>
                    </td>
                </tr>
                <tr>
                    <td class="align_right padding-r10"><?php __('For rate record with the same code and effective date is found'); ?>:</td>
                    <td id="dup_type_radio">
                        <?php echo $form->input('dup_method',$dup_method_input_arr); ?>
                    </td>
                </tr>
                <tr id="end_date_time_exists" class="hide">
                    <td class="align_right padding-r10">
                        <label><?php __('End Date Time')?>:</label>
                    </td>
                    <td align="left" style="padding-left:10px;">
                        <?php echo $form->input('end_date',array('type' => 'text','label' => false,'div' => false,
                            'onfocus' => "WdatePicker({dateFmt: 'yyyy-MM-dd 00:00:00'});",'readonly' => 'readonly',
                            'class' => 'wdate validate[required]',
                        )); ?>
                        <?php echo $form->input('end_date_gmt',$gmt_input_arr); ?>
                    </td>
                </tr>
                <tr id="end_date_time_all" class="hide">
                    <td class="align_right padding-r10">
                        <label><?php __('End Date Time')?>:</label>
                    </td>
                    <td align="left" style="padding-left:10px;">
                        <?php echo $form->input('end_date_all',array('type' => 'text','label' => false,'div' => false,
                            'onfocus' => "WdatePicker({dateFmt: 'yyyy-MM-dd 00:00:00'});",'readonly' => 'readonly',
                            'class' => 'wdate validate[required]',
                        )); ?>
                        <?php echo $form->input('end_date_all_gmt',$gmt_input_arr); ?>
                    </td>
                </tr>


                <tr>
                    <td class="align_right padding-r10">
                        <label><?php __('Effective Date Format')?>:</label>
                    </td>
                    <td align="left" style="padding-left:10px;">
                        <?php echo $form->input('effective_date_format',array('type' => 'select','label' => false,'div' => false,
                            'options' => $effective_arr,
                        )); ?>
                    </td>
                </tr>

                <tr>
                    <td class="align_right padding-r10">
                        <label><?php __('Has Code Deck')?>:</label>
                    </td>
                    <td align="left" style="padding-left:10px;">
                        <?php echo $form->input('has_code_deck',array('type' => 'checkbox','label' => false,'div' => false,
                        )); ?>
                    </td>
                </tr>

                <tr id="has_code_deck_tr">
                    <td class="align_right padding-r10">
                        <label><?php __('Code Name Matching')?>:</label>
                    </td>
                    <td align="left" style="padding-left:10px;">
                        <?php echo $form->input('code_name_match',array('type' => 'select','label' => false,'div' => false,
                            'options' => $code_name_match_arr,
                        )); ?>
                    </td>
                </tr>

                <tr>
                    <td class="align_right padding-r10">
                        <label><?php __('File With Header')?>:</label>
                    </td>
                    <td align="left" style="padding-left:10px;">
                        <?php echo $form->input('with_header',array('type' => 'checkbox','label' => false,'div' => false,
                        )); ?>
                    </td>
                </tr>


                <tr>
                    <td class="align_right padding-r10">
                        <?php __('Append Prefix')?>:
                    </td>
                    <td align="left" style="padding-left:10px;">
                        <?php echo $form->input('append_prefix',array('type' => 'checkbox','label' => false,
                            'div' => false,'id' => 'append_prefix')); ?>&nbsp;&nbsp;&nbsp;&nbsp;
                        <?php echo $form->input('append_prefix_value',array('type' => 'text','label' => false,
                            'div' => false,'class' => 'width80 validate[required,custom[onlyLetterNumber]]',
                            'style' => 'display: none;','maxlength' => 10,'id'=> 'append_prefix_value')); ?>
                    </td>
                </tr>

                <tr>
                    <td class="align_right padding-r10">
                        <?php __('File Headers')?>:
                    </td>
                    <td>
                        <?php echo $form->input('header_fields',array('type' => 'select','multiple' => true,'label' => false,
                            'id' => 'header_fields',
                            'div' => false,'options' => $fields,'selected' => $this->data['RateUploadTemplate']['header_fields'],'class'=>'validate[required]')); ?>
                    </td>
                    
                </tr>

                <tr id="set_default_date" >
                    <td class="align_right padding-r10"
                    <label><?php __('Set Default Effective Date') ?>:</label>
                    </td>
                    <td align="left" style="padding-left:10px;">
                        <?php echo $form->input('effective_date_default',array('type' => 'text','label' => false,'div' => false,
                            'class' => 'wdate validate[required] width220','readonly' => 'readonly',
                            'onfocus' => "WdatePicker({dateFmt: 'yyyy-MM-dd 00:00:00'});",
                        )); ?>
                    </td>
                </tr>
                <tr id="set_default_min_time" >
                    <td class="align_right padding-r10">
                        <label><?php __('Set Default Min Time') ?>:</label>
                    </td>
                    <td align="left" style="padding-left:10px;">
                        <?php echo $form->input('min_time_default',array('type' => 'text','label' => false,'div' => false,
                            'class' => 'validate[required,custom[integer]] width220'
                        )); ?>
                    </td>
                </tr>
                <tr id="set_default_interval" >
                    <td class="align_right padding-r10">
                        <label><?php __('Set Default Interval') ?>:</label>
                    </td>
                    <td align="left" style="padding-left:10px;">
                        <?php echo $form->input('interval_default',array('type' => 'text','label' => false,'div' => false,
                            'class' => 'validate[required,custom[integer]] width220'
                        )); ?>
                    </td>
                </tr>
                <tr class="no_template">
                    <td class="align_right padding-r10">
                        <label><?php __('Check Effective Date Criteria')?>:</label>
                    </td>
                    <td align="left" style="padding-left:10px;">
                        <?php echo $form->input('check_effective',array('type' => 'checkbox','label' => false,'div' => false,
                            'class' => 'check_effective'
                        )); ?>
                    </td>
                </tr>
                <tr class="no_template check_effective_flg">
                    <td class="align_right padding-r10" rowspan="2">
                        <label><?php __('Minimum Effective Date Requirement for')?>:</label>
                    </td>
                    <td align="left" style="padding-left:10px;">
                        <?php __('Rate Increase'); ?>:
                        <?php echo $form->input('rate_increase_days',array('type' => 'text','label' => false,'div' => false,
                            'class' => 'validate[required,custom[integer]] width15','maxlength' => 2
                        )); ?>
                        <?php __('days'); ?>
                    </td>
                </tr>
                <tr class="no_template check_effective_flg">
                    <td align="left" style="padding-left:10px;">
                        <?php __('New Code'); ?>:
                        <?php echo $form->input('new_code_days',array('type' => 'text','label' => false,'div' => false,
                            'class' => 'validate[required,custom[integer]] width15','maxlength' => 2
                        )); ?>
                        <?php __('days'); ?>
                    </td>
                </tr>

                <tr class="no_template check_effective_flg">
                    <td class="align_right padding-r10" rowspan="2">
                        <label><?php __('Action to take if requirement not match')?>:</label>
                    </td>
                    <td align="left" style="padding-left:10px;">
                        <?php __('Reject Rate Upload'); ?>:
                        <?php echo $form->input('reject_rate',array('type' => 'select','label' => false,'div' => false,
                            'options' => array('No','Yes')
                        )); ?>
                    </td>
                </tr>
                <!--                    <tr class="no_template check_effective_flg">-->
                <!--                        <td align="left" style="padding-left:10px;">-->
                <!--                            --><?php //__('Send Error Notification to'); ?><!--:-->
                <!--                            --><?php //echo $form->input('send_error_email_to',array('type' => 'select','label' => false,'div' => false,
                //                                'options' => array('None','Carrier Rate Contact','Switch’s Rate Contact')
                //                            )); ?>
                <!--                        </td>-->
                <!--                    </tr>-->

            </table>

            <div class="form-buttons center separator">
                <input class="btn btn-primary input in-submit" type="submit"  value="Submit">
                <input type="reset" value="<?php __('Reset') ?>" class="btn btn-inverse" />
            </div>
            <?php echo $form->end(); ?>
        </div>
    </div>
</div>
<script type="text/javascript" src="<?php echo $this->webroot ?>common/theme/scripts/plugins/forms/select2/select2.js"></script>
<link href="<?php echo $this->webroot; ?>js/multiselect2side/jquery.multiselect2side.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $this->webroot; ?>js/multiselect2side/jquery.multiselect2side.js"></script>
<script type="text/javascript">
    var $end_date_time_exists = $('#end_date_time_exists');
    var $end_date_time_all = $('#end_date_time_all');
    $(function(){

        var $dup_method = "<?php echo isset($this->data['RateUploadTemplate']['dup_method']) ? $this->data['RateUploadTemplate']['dup_method'] : 1; ?>";
        if ($dup_method == 2)
            $end_date_time_exists.show();
        else if($dup_method == 0)
            $end_date_time_all.show();

        var check_effective_flg = $(".check_effective").is(":checked");
        if(!check_effective_flg){
            $(".check_effective_flg").hide();
        }


//        var fields_arr = new Array();
//        <?php //foreach ($fields as $field): ?>
//        fields_arr.push("<?php //echo $field; ?>//");
//        <?php //endforeach; ?>
//        $("#header_fields").select2({tags:fields_arr});

        $("#header_fields").multiselect2side({
            labelTop: '<?php __('Top'); ?>',
            labelBottom: '<?php __('Bottom'); ?>',
            labelUp: '<?php __('Up'); ?>',
            labelDown: '<?php __('Down'); ?>',
            labelSort:'<?php __('Sort'); ?>',
            labelsx: '<?php __('Selectable items'); ?>',
            labeldx: '<?php __('Selection items'); ?>',
        });

        $("#header_fields").change(function(){
            var header_fields = $(this).val();
            if(header_fields.indexOf('effective_date') < 0)
                $("#set_default_date").show();
            else
                $("#set_default_date").hide();
            if(header_fields.indexOf('min_time') < 0)
                $("#set_default_min_time").show();
            else
                $("#set_default_min_time").hide();
            if(header_fields.indexOf('interval') < 0)
                $("#set_default_interval").show();
            else
                $("#set_default_interval").hide();
        }).trigger('change');


        $("#RateUploadTemplateHasCodeDeck").change(function(){
            var is_checked = $(this).is(":checked");
            if(is_checked)
                $("#has_code_deck_tr").show();
            else
                $("#has_code_deck_tr").hide();
        }).trigger('change');

        $("#RateUploadTemplateAddForm").submit(function(){
            var header_fields_val = $("#header_fields").val();
            if(!header_fields_val){
                jGrowl_to_notyfy('<?php __('File Headers is required'); ?>', {theme: 'jmsg-error'});
                return false;
            }
            var template_name = $("#RateUploadTemplateName").val();
            var template_id = $("#template_id").val();
            var flg = true;
            $.ajax({
                'url': '<?php echo $this->webroot ?>template/judge_rate_upload_template_name_unique',
                'type': 'POST',
                'async': false,
                'dataType': 'json',
                'data': {'template_name': template_name,'template_id':template_id},
                'success': function (data) {
                    if(data)
                    {
                        jGrowl_to_notyfy('<?php __('Template name'); ?>['+template_name+ ']<?php __('already exists'); ?>', {theme: 'jmsg-error'});
                        flg = false;
                    }
                }
            });
            return flg;

        });
        append_prefix_show();
        $("#append_prefix").click(function(){
            append_prefix_show();
        });

        $(".check_effective").click(function(){
            check_effective($(this));
        });
    });

    function append_prefix_show()
    {
        var checked = $("#append_prefix").is(":checked");
        if (checked){
            $("#append_prefix_value").show();
        }else{
            $("#append_prefix_value").hide();
        }
    }

    function dup_type_change(opt)
    {
        var $dup_method = $(opt).val();
        $end_date_time_exists.hide();
        $end_date_time_all.hide();
        if ($dup_method == 2)
            $end_date_time_exists.show();
        else if($dup_method == 0)
            $end_date_time_all.show();
    }

    function check_effective(obj){
        var checked = obj.is(":checked");
        if(checked){
            $(".check_effective_flg").show();
        }else{
            $(".check_effective_flg").hide();
        }
    }

</script>