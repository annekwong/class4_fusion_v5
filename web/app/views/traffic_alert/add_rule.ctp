<style type="text/css">
    .ms-container ul.ms-list{
        width: 280px;
    }
    .ms-container{
        background: transparent url('<?php echo $this->webroot; ?>common/theme/scripts/plugins/forms/multiselect/img/switch.png') no-repeat 290px 80px;
    }
    input.pointer_input{
        cursor: pointer;
    }
    .mail_template_subject,.mail_template_cc{
        max-width: none;
        width: 90%;
    }
</style>
<ul class="breadcrumb">
    <li><?php __('You are here'); ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>traffic_alert/index">
        <?php __('Monitoring') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>traffic_alert/add_rule">
        <?php echo __('Add Traffic Alert Rule', true); ?></a></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Add Traffic Alert Rule', true); ?></h4>
</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
<!--    <a class="btn btn-icon btn-inverse glyphicons circle_arrow_left" href="--><?php //echo $this->webroot; ?><!--traffic_alert/index">-->
<!--        <i></i>--><?php //__('Back') ?>
<!--    </a>-->
    <?php echo $this->element('xback',array('backUrl' => 'traffic_alert/index')); ?>
</div>
<div class="clearfix"></div>
<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="clearfix"></div>

        <div class="widget-body">
            <form action="<?php echo $this->webroot; ?>traffic_alert/add_save_rule_post" method="post" id="myform">
                <input type="hidden" name="active" value="1" />
                <table class="form table dynamicTable tableTools table-bordered  table-white">
                    <colgroup>
                        <col width="30%">
                        <col width="70%">
                    </colgroup>
                    <tr>
                        <td width="15%" class="align_right padding-r10"><?php __('Carrier') ?>:</td>
                        <td>
                            <a href='#' id='select-all'>Select All</a> /
                            <a href='#' id='deselect-all'>Deselect All</a>
                            <select name="carriers[]"  class="validate[required]" style="width:250px;height:300px;" multiple="multiple" id="carrierMultiple">
                                <?php foreach ($carriers as $client_id => $name): ?>
                                    <option value="<?php echo $client_id; ?>"><?php echo $name; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>

                    </tr>
                    <tr>
                        <td width="15%" class="align_right padding-r10"><?php __('Destination') ?>:</td>
                        <td>
                            <a href='#' id='select-all-dst'>Select All</a> /
                            <a href='#' id='deselect-all-dst'>Deselect All</a>
                            <select name="destination[]"  class="validate[required]" style="width:250px;height:300px;" multiple="multiple" id="destinationMultiple">
                                <?php foreach ($code_names as $code_name): ?>
                                    <option value="<?php echo $code_name[0]['name']; ?>"><?php echo $code_name[0]['name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align:right;">
                            <?php __('Email to') ?>
                            <input type="text" class="validate[required,custom[email]]" name ="email_to" />
                        </td>
                        <td>
                            when call count increases from zero to over
                            <input type="text" class="validate[required,custom[integer]]" name ="greater_than_num" />
                            in an hour.
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align:right;">
                            <?php __('Email to') ?>
                            <input type="text" class="validate[required,custom[email]]" name ="email_to" />
                        </td>
                        <td>
                            when call count increases by
                           <input type="text" class="validate[required,custom[integer]]" name ="less_than_num" />
                           % in an hour.
                        </td>
                    </tr>
                </table>
                <table class="form table dynamicTable tableTools table-bordered  table-white">
                    <colgroup>
                        <col width="20%">
                        <col width="80%">
                    </colgroup>
                    <tr>
                        <td class="align_right"><?php __('From email')?> </td>
                        <td>
                            <select name="mail_from">
                                <option value=""><?php __('Default')?></option>
                                <?php foreach ($mail_senders as $mail_sender): ?>
                                    <option value="<?php echo $mail_sender[0]['id'] ?>" ><?php echo $mail_sender[0]['email'] ?></option>                                        
                                <?php endforeach; ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td class="align_right"><?php echo __('subject') ?> </td>
                        <td>
                            <input class="input in-text validate[required] mail_template_subject" name="mail_subject" value="" id="mail_subject" type="text" >
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="align_right"><?php echo __('content') ?> </td>
                        <td><textarea class="input in-textarea validate[required]" name="mail_content" style="height: 100px; font-family: monospace; font-size: 12px;width:800px;" id="mail_content"></textarea></td>
                        <td class="mail_content_tags">
                            <h4><i class="icon-tags"></i><?php __('Tags'); ?>:</h4>
                            <?php
                            $tags = array('carrier_name', 'destination', 'current_time', 'current_call_attempt', 'last_hour_call_attempt');
                            foreach($tags as $tag): ?>
                                <span class="btn btn-block btn-default">{<?php echo $tag; ?>}</span>
                            <?php endforeach; ?>
                        </td>
                    </tr>

                    <tr>
                        <td colspan="2" class="center">
                            <input  type="submit" value="<?php echo __('submit') ?>" class="input in-submit btn btn-primary">
                            <input  type="reset" value="<?php echo __('Revert') ?>" class="input in-submit btn btn-primary">
                        </td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript" src="<?php echo $this->webroot ?>js/ckeditor_full/ckeditor.js"></script>
<script type="text/javascript">
    $(function() {

        $('select[multiple]').multiSelect();

        $('.mail_content_tags').find('span').click(function(){
            var $tag_value = $(this).html();
            var $this_textarea_id = $(this).closest('tr').find('textarea').attr('id');
            var editor = CKEDITOR.instances[$this_textarea_id];
            editor.insertHtml( $tag_value );
        });

        $('#select-all').click(function(){
            $('#carrierMultiple').multiSelect('select_all');
            return false;
        });
        $('#deselect-all').click(function(){
            $('#carrierMultiple').multiSelect('deselect_all');
            return false;
        });

        $('#select-all-dst').click(function(){
            $('#destinationMultiple').multiSelect('select_all');
            return false;
        });
        $('#deselect-all-dst').click(function(){
            $('#destinationMultiple').multiSelect('deselect_all');
            return false;
        });

        CKEDITOR.replace('mail_content');

        $("#myform").on('reset', function(){
            for (instance in CKEDITOR.instances){
                CKEDITOR.instances[instance].setData($("#"+instance).val());
                CKEDITOR.instances[instance].updateElement();
            }
            setTimeout(function(){
                $('#destinationMultiple').multiSelect('refresh');
                $('#carrierMultiple').multiSelect('refresh');
            });
        });
        $("#myform").submit(function() {
            $("#mail_content").val(CKEDITOR.instances['mail_content'].getData());
            if (!$("#mail_content").val())
            {
                jGrowl_to_notyfy('Mail content can not be null!', {theme: 'jmsg-error'});
                return false;
            }
            if (!$("#carrierMultiple").val())
            {
                jGrowl_to_notyfy('Carrier can not be null!', {theme: 'jmsg-error'});
                return false;
            }

        });
    });
</script>



