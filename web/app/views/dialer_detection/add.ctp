<style>
    input{width: 220px;}
    select,textarea, input[type="text"]{margin-bottom: 0}
</style>
<ul class="breadcrumb">
    <li><?php __('You are here'); ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>dialer_detection/index"><?php __('Monitoring') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot . $this->params['url']['url'] ?>"><?php __('Dialer Detection') ?></a></li>
</ul>


<div class="heading-buttons">
    <h4 class="heading"><?php __('Dialer Detection') ?></h4>

</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
    <a class="btn btn-icon btn-inverse glyphicons circle_arrow_left" href="<?php echo $this->webroot; ?>dialer_detection/index"><i></i><?php __('Back')?></a>
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

                    <tr>
                        <td class="right"><?php __('Active'); ?> </td>
                        <td>
                            <input type="checkbox" name="action" <?php
                            if (isset($data) && $data['DialerDetection']['action'])
                            {
                            ?>checked="checked"<?php } ?> />
                        </td>
                    </tr>
                    <tr>
                        <td class="right"><?php __('Rule Name'); ?> </td>
                        <td>
                            <input type="text" name="name" <?php
                            if (isset($data['DialerDetection']['name']))
                            {
                            ?>value="<?php echo $data['DialerDetection']['name']; ?>"<?php } ?>
                                   class="validate[required,custom[onlyLetterNumberLine]]" />
                        </td>
                    </tr>

                    <tr>
                        <td class="right"><?php __('Ingress Trunk'); ?> </td>
                        <td>
                            <select name="trunk[]"  multiple="multiple"  id="trunk">
                                <option value="all" ><?php __('ALL')?></option>
                                <?php
                                foreach ($ingress as $ingress_id =>$ingress_alias)
                                {
                                    ?>
                                    <option value="<?php echo $ingress_id; ?>" <?php
                                    if (isset($data) && in_array($ingress_id, explode(',', $data['DialerDetection']['trunk'])))
                                    {
                                    ?>selected="selected"<?php } ?> ><?php echo $ingress_alias ?></option>
                                <?php }
                                ?>
                            </select>
                        </td>
                    </tr>

                    <tr>
                        <td class="right"><?php __('Criteria'); ?> </td>
                        <td>
                            <?php __('# of Occurrence of an ANI >=')?>
                            <input type="text" name="ani_scope" class="validate[custom[onlyNumberSp]] width120" <?php
                            if (isset($data))
                            {
                            ?>value="<?php echo!empty($data['DialerDetection']['ani_scope']) ? $data['DialerDetection']['ani_scope'] : ''; ?>" <?php } ?> />
                            <?php __('within')?>
                            <input type="text" name="ani_within_mins"  <?php
                            if (isset($data))
                            {
                            ?>value="<?php echo!empty($data['DialerDetection']['ani_within_mins']) ? $data['DialerDetection']['ani_within_mins'] : ''; ?>"  <?php } ?>  class="validate[min[2],custom[onlyNumberSp]] width120" maxlength="4"/><?php __('mins')?>
                        </td>
                    </tr>

                    <tr>
                        <td class="right"><?php __('Send Email'); ?></td>
                        <td>
                            <select name="send_email" id="send_email_flg" >
                                <option value="0"><?php __('No'); ?></option>
                                <option value="1" <?php if (isset($data['DialerDetection']['send_email']) && $data['DialerDetection']['send_email']){ echo "selected"; } ?>><?php __('Yes'); ?></option>
                            </select>
                        </td>
                    </tr>

                    <tr class="email_tmp">
                        <td class="right"><?php __('Email to')?> </td>
                        <td>
                            <input type="radio" name="sent_type" value="0" <?php if ((isset($data['DialerDetection']['sent_type']) && $data['DialerDetection']['sent_type'] == 0) || !isset($data['DialerDetection']['sent_type'])) echo 'checked="checked"'; ?> /><?php __('Your Own NOC')?> &nbsp;&nbsp;
                            <input type="radio" name="sent_type" value="1" <?php if (isset($data['DialerDetection']['sent_type']) && $data['DialerDetection']['sent_type'] == 1) echo 'checked="checked"'; ?> /><?php __('Partner’s NOC')?> &nbsp;&nbsp;
                            <input type="radio" name="sent_type" value="2" <?php if (isset($data['DialerDetection']['sent_type']) && $data['DialerDetection']['sent_type'] == 2) echo 'checked="checked"'; ?> /><?php __('Both')?> &nbsp;&nbsp;
                        </td>
                    </tr>

                    <tr class="email_tmp">
                        <td class="right"><?php echo __('Notification Subject') ?> </td>
                        <td><input type="text" class="input in-input validate[required]" name="dialer_detection_subject" value="<?php echo!empty($data['DialerDetection']['dialer_detection_subject']) ? $data['DialerDetection']['dialer_detection_subject'] : ''; ?>"  id="dialer_detection_subject" />
                        </td>
                    </tr>
                    <tr class="email_tmp">
                        <td class="right"><?php echo __('Notification Content') ?> </td>
                        <td></td>
                    </tr>
                    <tr class="email_tmp">
<!--                        <td class="right">--><?php //echo __('Notification Content') ?><!-- </td>-->
                        <td colspan="2"><textarea class="input dialer_detection_mail validate[required]" name="dialer_detection_content"  id="dialer_detection_content"><?php echo!empty($data['DialerDetection']['dialer_detection_content']) ? $data['DialerDetection']['dialer_detection_content'] : ''; ?></textarea>
                        </td>
                    </tr>

                    <tr>
                        <td class="right"><?php __('Block ANI'); ?></td>
                        <td>
                            <select name="block_ani" id="block_ani_flg" >
                                <option value="0"><?php __('No'); ?></option>
                                <option value="1" <?php if (isset($data['DialerDetection']['block_ani']) && $data['DialerDetection']['block_ani']){ echo "selected"; } ?>>
                                    <?php __('Yes'); ?>
                                </option>
                            </select>
                        </td>
                    </tr>

                    <tr class="block_ani_item">
                        <td class="right">
                            <?php __('Unblock ANI')?>:
                        </td>
                        <td>
                            <select name="unblock_ani_type" id="unblock_ani_type" >
                                <option value="0"<?php if ((isset($data['DialerDetection']['unblock_ani_type']) && $data['DialerDetection']['unblock_ani_type'] == 0) || !isset($data['DialerDetection']['unblock_ani_type'])) echo 'selected="selected"'; ?> ><?php __('Never')?></option>
                                <option value="1"<?php if (isset($data['DialerDetection']['unblock_ani_type']) && $data['DialerDetection']['unblock_ani_type'] == 1) echo 'selected="selected"'; ?>><?php __('After')?></option>
                            </select>
                            <?php
                            $unblock_ani_type = "";
                            if ((isset($data['DialerDetection']['unblock_ani_type']) && $data['DialerDetection']['unblock_ani_type'] == 0) || !isset($data['DialerDetection']['unblock_ani_type']))
                            {
                                $unblock_ani_type = "style='display:none;'";
                            }
                            ?>
                            <input class="unblock_ani_mins" <?php echo $unblock_ani_type; ?> type="text" name="unblock_ani_mins" <?php
                            if (isset($data))
                            {
                            ?>value="<?php echo!empty($data['DialerDetection']['unblock_ani_mins']) ? $data['DialerDetection']['unblock_ani_mins'] : ''; ?>" <?php } ?> class="validate[required,custom[onlyNumberSp]]" />
                            <span class="unblock_ani_mins" <?php echo $unblock_ani_type; ?>><?php __('min(s)')?></span>
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
        $("#myform").submit(function(){
            var name = $(this).find('input[name=name]').val();
            var id = '<?php echo isset($this->params['pass'][0]) ? base64_decode($this->params['pass'][0]) : ''; ?>';
            var flg = true;
            $.ajax({
                'url': '<?= $this->webroot . "dialer_detection/ajax_judge_rule_name" ?>',
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
            return flg;
        });

        CKEDITOR.replace('dialer_detection_content');
        $("#unblock_ani_type").change(function() {
            var unblock_ani_type_value = $(this).val();
            $(".unblock_ani_mins").hide();
            if (unblock_ani_type_value == 1)
            {
                $(".unblock_ani_mins").show();
            }
        });

        $("#send_email_flg").change(function(){
            var send_email_flg = $(this).val();
            $(".email_tmp").hide();
            if(send_email_flg == 1)
            {
                $(".email_tmp").show();
            }
        }).trigger('change');


        $("#block_ani_flg").change(function(){
            var block_ani_flg = $(this).val();
            $(".block_ani_item").hide();
            if(block_ani_flg == 1)
            {
                $(".block_ani_item").show();
            }
        }).trigger('change');


    });
</script>
