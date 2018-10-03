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
    <li><a href="<?php echo $this->webroot ?>traffic_alert/index"><?php __('Monitoring') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot . $this->params['url']['url'] ?>">
        <?php echo __('Edit Traffic Alert Rule', true); ?></a></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Edit Traffic Alert Rule', true); ?></h4>
</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
    <a class="btn btn-icon btn-inverse glyphicons circle_arrow_left" href="<?php echo $this->webroot; ?>traffic_alert/index">
        <i></i><?php __('Back') ?>
    </a>
</div>
<div class="clearfix"></div>
<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="clearfix"></div>

        <div class="widget-body">
            <form action="<?php echo $this->webroot; ?>traffic_alert/add_save_rule_post" method="post" id="myform">
                <input type="hidden" name="id" value="<?php echo $rule['TrafficAlert']['id']; ?>" />
                <table class="form table dynamicTable tableTools table-bordered  table-white">
                    <colgroup>
                        <col width="30%">
                        <col width="70%">
                    </colgroup>
                    <tr>
                        <td class="align_right padding-r10"><?php __('Active'); ?></td>
                        <td>
                            <input type="checkbox" name="active" <?php if($rule['TrafficAlert']['active']): ?>checked='checked'<?php endif; ?> />
                        </td>
                    </tr>
                    <tr>
                        <td width="15%" class="align_right padding-r10"><?php __('Carrier')?>:</td>
                        <td>
                            <select name="carriers[]"  class="validate[required]" style="width:250px;height:300px;" multiple="multiple" id="carrierMultiple">
                                <?php foreach ($carriers as $client_id => $name): ?>
                                    <option value="<?php echo $client_id; ?>" <?php if (in_array($client_id, $carriers_arr)): ?>selected="selected"<?php endif; ?> ><?php echo $name; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td width="15%" class="align_right padding-r10"><?php __('Destination') ?>:</td>
                        <td>
                            <select name="destination[]"  class="validate[required]" style="width:250px;height:300px;" multiple="multiple" id="destinationMultiple">
                                <?php foreach ($code_names as $code_name): ?>
                                    <option value="<?php echo $code_name[0]['name']; ?>" <?php if (in_array($code_name[0]['name'], $destination)): ?>selected="selected"<?php endif; ?> ><?php echo $code_name[0]['name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                    </tr>
<!--                    <tr>
                        <td width="15%" class="align_right padding-r10"><?php __('Destination') ?>:</td>
                        <td>
                            <select name="destination[]"  class="validate[required]" style="width:250px;height:300px;" multiple="multiple">
                                <?php foreach ($code_names as $code_name): ?>
                                    <option value="<?php echo $code_name[0]['name']; ?>" <?php if (in_array($code_name[0]['name'], $destination)): ?>selected="selected"<?php endif; ?> ><?php echo $code_name[0]['name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                    </tr>-->
                    <tr>
                        <td colspan="2">
                            <?php __('Send Email to') ?> 
                            <input type="text" class="validate[required,custom[email]]" name ="email_to" value="<?php echo $rule['TrafficAlert']['email'] ?>" />
                            <?php __('when number of new call attempts in the hour is greater than')?>
                            <input type="text" class="validate[required,custom[integer]]" name ="greater_than_num" value="<?php echo $rule['TrafficAlert']['greater_hour'] ?>"  />
                            <?php __('and call attempt in the previous hours is less than')?>
                            <input type="text" class="validate[required,custom[integer]]" name ="less_than_num" value="<?php echo $rule['TrafficAlert']['less_hour'] ?>"  /> .
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
                                <option <?php if (empty($rule['TrafficAlert']['mail_from'])) echo 'selected="selected"' ?>><?php __('Default')?></option>
                                <?php foreach ($mail_senders as $mail_sender): ?>
                                    <option value="<?php echo $mail_sender[0]['id'] ?>" <?php if ($mail_sender[0]['id'] == $rule['TrafficAlert']['mail_from']) echo 'selected="selected"' ?>><?php echo $mail_sender[0]['email'] ?></option>                                        
                                <?php endforeach; ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td class="align_right"><?php echo __('subject') ?> </td>
                        <td>
                            <input class="input in-text validate[required] mail_template_subject" name="mail_subject" value="<?php echo !empty($rule['TrafficAlert']['mail_subject']) ? $rule['TrafficAlert']['mail_subject'] : ''; ?>" id="mail_subject" type="text" >
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="align_right"><?php echo __('content') ?> </td>
                        <td><textarea class="input in-textarea" name="mail_content" style="height: 100px; font-family: monospace; font-size: 12px;width:800px;" id="mail_content"><?php echo!empty($rule['TrafficAlert']['mail_content']) ? $rule['TrafficAlert']['mail_content'] : ''; ?></textarea></td>
                    </tr>
                    <tr>
                        <td colspan="4" class="center">
                            <input  type="submit" value="<?php echo __('submit') ?>" class="input in-submit btn btn-primary">
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
            if ($("#mail_content").val())
            {
                return true;
            }
            else
            {
                jGrowl_to_notyfy('Mail content can not be null!', {theme: 'jmsg-error'});
                return false;
            }
        });
    });
</script>



