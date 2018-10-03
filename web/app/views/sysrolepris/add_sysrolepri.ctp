<style type="text/css">
    .role_module li{ float:left; width:48%; margin-left:15px; vertical-align:top;list-style: none;}
    .cb_select{ height:auto;}
    /*Table 隔行换色效果代码*/
    .cb_select {
        background: white;
    }
    .cb_select div{
        background: white; /*#F5F5F5;*/
    }
    .cb_select div.alt {
        background: #fff; /*#E8F3FD;*/  /*给Table的偶数行加上背景色*/
        height:20px;
        line-height:20px;
    }
    .cb_select div.over{
        background: #DBDBDB;  /*鼠标高亮行的背景色*/
    }
    /*//Table 隔行换色效果代码*/
</style>
<script type="text/javascript">
    /*Table 隔行换色效果代码*/
    jQuery(document).ready(function() {
        $(".cb_select div.alt").mouseover(function() {
            $(this).addClass("over");
        })
            .mouseout(function() {
                $(this).removeClass("over");
            });
    });

    /*//Table 隔行换色效果代码*/
</script>
<?php
if (0)
{
    ?>
    <?php
    echo $this->element('common/exception_msg');

    if (!array_keys_value($role_name, '0.0.role_name') == '')
    {
        $role_name = "[" . array_keys_value($role_name, '0.0.role_name') . "]";
    }

    ?>
    <?php
}
else
{
    ?>
    <?php
    if (isset($this->data['Sysrole']['role_name']))
    {
        $name_role = "[" . $this->data['Sysrole']['role_name'] . "]";
    }
    else
    {

        $name_role = '';
    }
    if ($role_name[0][0]['role_name'] != '')
    {
        echo $this->element('layout/header', array('h1' => __('Configuration', true), 'h1_span' => ' Edit Role: <font class=\'editname\' title=\'Name\' >[' . $role_name[0][0]['role_name'] . ']</font>', 'back_url' => 'sysrolepris/view_sysrolepri'));
    }
    else
    {
        echo $this->element('layout/header', array('h1' => __('Configuration', true), 'h1_span' => __('Add Role', true), 'back_url' => 'sysrolepris/view_sysrolepri'));
    }
    ?>

    <div class="innerLR">

        <div class="widget widget-heading-simple widget-body-white">
            <div class="widget-body">
                <?php echo $form->create('Sysrolepri', array('action' => 'add_sysrolepri', 'name' => 'add_sysrolepri', 'class' => "form-inline")); ?>

                <div class="role_module">
                    <div class="widget">
                        <div class="widget-head"><h4 class="heading"> <?php __('Basic') ?> &nbsp;</h4></div>
                        <div class="widget-body">
                            <?php //**********系统信息**************   ?>
                            <table class="form table table-condensed">
                                <tr>
                                    <td><div class="cb_select" style="height:32px; line-height:32px; border:0px;">	  <input id="SysrolepriRoleId" class="input in-text in-input" type="hidden" value="<?php echo base64_decode(array_keys_value($this->params, 'pass.0')) ?>" name="data[Sysrole][role_id]">

                                            <div><?php echo __('Role Name', true); ?>*: <?php //echo $form->input('role_name',array('label'=>false,'div'=>false,'type'=>'text','maxLength'=>'100','class'=>'input in-text'))      ?>

                                                <input id="SysrolepriRoleName" class="input in-text in-input validate[required,custom[onlyLetterNumberLineSpace]]" type="text" value="<?php echo $role_name[0][0]['role_name'] ?>" maxlength="256" name="data[Sysrole][role_name]" style="width:180px;">

                                                <?php echo __('View All Carriers', TRUE); ?>
                                                <input type="checkbox" name="data[Sysrole][view_all]" <?php if ($role_name[0][0]['view_all']) echo 'checked="checked"'; ?> />

                                            </div>
                                        </div></td>

                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="widget">
                        <div class="widget-head"><h4 class="heading"><?php __('Finance Permissions') ?> &nbsp;</h4></div>
                        <div class="widget-body">
                            <table class="form table table-condensed">
                                <tbody><tr>
                                    <td colspan="2">

                                        <div>
                                                <span style="float:left;">
                                                    <input type="checkbox" onclick='module_check_all(this, "Invoice_all");' name="invoice_payment" value="1" id="Management_all" class="border_no input in-checkbox"> 
                                                    <label for="invoice_payment"><?php __('All') ?></label>
                                                </span>
                                        </div>

                                        <div class="cb_select input">
                                            <div class="alt" style="clear:both;">
                                                    <span style="float:left;">
                                                        <input type="checkbox" class="Invoice_all_syspri border_no input in-checkbox" <?php if ($role_name[0][0]['delete_invoice']) echo 'checked="checked"'; ?>
                                                               name="data[Sysrole][delete_invoice]" id="delete_invoice">
                                                        <label for="delete_invoice"><?php __('Allow to delete invoice') ?></label>
                                                    </span>
                                            </div>
                                            <div class="alt" style="clear:both;">
                                                    <span style="float:left;">
                                                        <input type="checkbox" class="Invoice_all_syspri border_no input in-checkbox" <?php if ($role_name[0][0]['delete_payment']) echo 'checked="checked"'; ?>
                                                               name="data[Sysrole][delete_payment]" id="delete_payment">
                                                        <label for="delete_payment"><?php __('Allow to delete payment') ?></label>
                                                    </span>
                                            </div>
                                            <div class="alt" style="clear:both;">
                                                    <span style="float:left;">
                                                        <input type="checkbox" class="Invoice_all_syspri border_no input in-checkbox" <?php if ($role_name[0][0]['delete_credit_note']) echo 'checked="checked"'; ?>
                                                               name="data[Sysrole][delete_credit_note]" id="delete_creditnote">
                                                        <label for="delete_creditnote"><?php __('Allow to delete credit note') ?></label>
                                                    </span>
                                            </div>
                                            <div class="alt" style="clear:both;">
                                                    <span style="float:left;">
                                                        <input type="checkbox" class="Invoice_all_syspri border_no input in-checkbox" <?php if ($role_name[0][0]['delete_debit_note']) echo 'checked="checked"'; ?>
                                                               name="data[Sysrole][delete_debit_note]" id="delete_debitnote">
                                                        <label for="delete_debitnote"><?php __('Allow to delete debit note') ?></label>
                                                    </span>
                                            </div>
                                            <div class="alt" style="clear:both;">
                                                    <span style="float:left;">
                                                        <input type="checkbox" class="Invoice_all_syspri border_no input in-checkbox" <?php if ($role_name[0][0]['reset_balance']) echo 'checked="checked"'; ?>
                                                               name="data[Sysrole][reset_balance]" id="reset_balance">
                                                        <label for="reset_balance"><?php __('Allow to reset balance') ?></label>
                                                    </span>
                                            </div>
                                            <div class="alt" style="clear:both;">
                                                    <span style="float:left;">
                                                        <input type="checkbox" class="Invoice_all_syspri border_no input in-checkbox" <?php if ($role_name[0][0]['modify_credit_limit']) echo 'checked="checked"'; ?>
                                                               name="data[Sysrole][modify_credit_limit]" id="modify_credit_limit">
                                                        <label for="modify_credit_limit"><?php __('Allow to modify credit limit') ?></label>
                                                    </span>
                                            </div>
                                            <div class="alt" style="clear:both;">
                                                    <span style="float:left;">
                                                        <input type="checkbox" class="Invoice_all_syspri border_no input in-checkbox" <?php if ($role_name[0][0]['modify_min_profit']) echo 'checked="checked"'; ?>
                                                               name="data[Sysrole][modify_min_profit]" id="modify_min_profit">
                                                        <label for="modify_min_profit"><?php __('Allow to modify Min.Profitability') ?></label>
                                                    </span>
                                            </div>
                                            <div class="alt" style="clear:both;">
                                                    <span style="float:left;">
                                                        <input type="checkbox" class="Invoice_all_syspri border_no input in-checkbox" <?php if ($role_name[0][0]['view_cost_and_rate']) echo 'checked="checked"'; ?>
                                                               name="data[Sysrole][view_cost_and_rate]" id="view_cost_and_rate">
                                                        <label for="view_cost_and_rate"><?php __('Allow to view cost and rate in reports') ?></label>
                                                    </span>
                                            </div>
                                        </div>
                        </div>
                        </td>
                        </tr>
                        </tbody></table>
                    </div>
                </div>
                <?php
                $role_menu = $sysmodule;
                //pr($role_menu);
                if (!empty($role_menu))
                {
                    foreach ($role_menu as $k => $v)
                    {
                        $id_modulename = str_replace(" ", '_', $k);
                        ?>
                        <div class="widget" data-toggle="collapse-widget" data-collapse-closed="true">
                            <div class="widget-head"><h4 class="heading"><?php echo __($k); ?> &nbsp;</h4></div>
                            <div class="widget-body">
                                <table class="form table table-condensed">
                                    <tr>
                                        <td colspan="2">

                                            <div>
                                                    <span style="float:left;">
                                                        <input id="<?php echo $id_modulename . '_all' ?>" type="checkbox" value="1" name="<?php echo $id_modulename; ?>_all" onclick='module_check_all(this, "<?php echo $id_modulename; ?>_all");'/> 
                                                        <label for="<?php echo $id_modulename . '_all' ?>"><?php __('All') ?></label>
                                                    </span>
                                                    <span style="float:right;">

                                                        <input type="checkbox" name="<?php echo $id_modulename . '_all_model_w' ?>" value="1" id="<?php echo $id_modulename; ?>_all_model_w" disabled="disabled" onclick='module_check_w(this, "<?php echo $id_modulename; ?>_all");'/>
                                                        <label for="<?php echo $id_modulename; ?>_all_model_w"><?php echo __('All_W', true); ?></label>&nbsp;
                                                        <?php if(!strcmp($k,'Tools')): ?>
                                                        <input type="checkbox" name="<?php echo $id_modulename . '_all_model_x' ?>" value="1" id="<?php echo $id_modulename; ?>_all_model_x" disabled="disabled"onclick='module_check_x(this, "<?php echo $id_modulename; ?>_all");'/>
                                                        <label for="<?php echo $id_modulename; ?>_all_model_x"><?php echo __('All_E', true); ?></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                                                <?php endif; ?>
                                            </div>

                                            <div class="cb_select input">

                                                <?php
                                                //var_dump($sysrolepri);
                                                foreach ($v as $k1 => $v1)
                                                {
                                                   if($v1['flag'] && $v1['pri_url']):
                                                    ?>
                                                    <div style="clear:both;" class="alt">
                                                        <?php
                                                        //echo $form->checkbox($v1['pri_name'])
                                                        $id_priname = str_replace(":", '_', $v1['pri_name']);
                                                        ?>
                                                        <span style="float:left;">
                                                                <input id="<?php echo $id_priname ?>" type="checkbox" value="1" name="data[Sysrolepri][<?php echo $v1['pri_name'] ?>]" <?php if (!empty($sysrolepri[$v1['pri_name']])) echo 'checked="checked"'; ?>  onclick='module_check(this, "<?php echo $id_priname ?>");' data-parent = "<?php echo $id_modulename; ?>_all_syspri" class="<?php echo $id_modulename; ?>_all_syspri"/>
                                                                <label for="<?php echo $id_priname ?>"><?php __($v1['pri_val']); ?></label>
                                                            </span>

                                                            <span style="float:right;">
                                                                <input type="checkbox" name="data[Sysrolepri][<?php echo $v1['pri_name'] ?>][model_r]" value="1" <?php
                                                                if (!empty($sysrolepri[$v1['pri_name']]) && $sysrolepri[$v1['pri_name']]['model_r'] == true)
                                                                {
                                                                    ?>
                                                                    checked="checked" class="module_check ischecked <?php echo $id_modulename . '_all_r' ?>"
                                                                    <?php
                                                                }
                                                                else
                                                                {
                                                                    ?>
                                                                    class="module_check nochecked <?php echo $id_modulename . '_all_r' ?>"
                                                                <?php } ?>   id="<?php echo $id_priname ?>_model_r"/>
                                                                <?php echo __('Read', true); ?>&nbsp;
                                                                <input type="checkbox" name="data[Sysrolepri][<?php echo $v1['pri_name'] ?>][model_w]" value="1" <?php
                                                                if (!empty($sysrolepri[$v1['pri_name']]) && $sysrolepri[$v1['pri_name']]['model_w'] == true)
                                                                {
                                                                    ?> checked="checked" class="module_check  <?php echo $id_modulename . '_all_w' ?>"<?php
                                                                }
                                                                else
                                                                {
                                                                    ?>class="module_check <?php echo $id_modulename . '_all_w' ?>"
                                                                <?php } ?> id="<?php echo $id_priname ?>_model_w"/>
                                                                <?php echo __('Write', true); ?>&nbsp;
                                                                <?php if(!strcmp($k,'Tools')): ?>
                                                                <input type="checkbox" name="data[Sysrolepri][<?php echo $v1['pri_name'] ?>][model_x]" value="1" <?php
                                                                if (!empty($sysrolepri[$v1['pri_name']]) && $sysrolepri[$v1['pri_name']]['model_x'] == true)
                                                                {
                                                                ?>checked="checked" class="module_check  <?php echo $id_modulename . '_all_x' ?>" <?php
                                                                       }
                                                                       else
                                                                       {
                                                                       ?>class="module_check <?php echo $id_modulename . '_all_x' ?>"
                                                                       <?php } ?>id="<?php echo $id_priname ?>_model_x"/>
                                                                <?php echo __('Execute', true); ?> </span>
                                                    <?php endif; ?>
                                                    </div>
                                                <?php endif; ?>
                                                <?php } ?>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <?php
                    }
                }
                ?>
            </div>

            <?php
            if ($_SESSION['role_menu']['Configuration']['sysrolepris']['model_w'])
            {
                ?>

                <div id="form_footer" class="button-groups center" style="clear:both;">
                    <input type="submit" id="submit" value="<?php echo __('submit') ?>" class="input in-submit btn btn-primary" />
                    <input type="reset" id="reset_all"  value="<?php echo __('reset') ?>"  class="input in-submit btn btn-default" />
                </div>
            <?php } ?>
        </div>

        <?php echo $form->end(); ?>
        <script type="text/javascript">

            //特殊表单验证（只能为数字（Float））
            /*jQuery(document).ready(
             function(){
             jQuery('#ClientName,#ClientLogin').xkeyvalidate({type:'strNum'});
             jQuery('#ClientAllowedCredit,#ClientNotifyClientBalance,#ClientNotifyAdminBalance').xkeyvalidate({type:'Ip'});
             jQuery('#ClientProfitMargin').xkeyvalidate({type:'Num'});
             jQuery('input[maxLength=32]').xkeyvalidate({type:'Email'});
             jQuery('#ClientTaxId').xkeyvalidate({type:'Num'});
             }
             );*/
        </script>
        <script type="text/javascript">
            jQuery('#ClientLowBalanceNotice').disabled({id: '#ClientNotifyClientBalance,#ClientNotifyAdminBalance'});
        </script>
        <script type="text/javascript">
            jQuery(document).ready(function() {


                $("#reset_all").click(function() {

                    $(":checkbox").removeAttr('checked');

                });

            });
            jQuery(document).ready(function() {
                $(".nochecked").attr("disabled", "disabled");
                $(".nochecked").siblings().attr("disabled", "disabled");
                $($(".ischecked")).each(function() {
                    if ($(".ischecked").attr('checked')) {
                        $(".ischecked").removeAttr('disabled');
                        $(".ischecked").siblings().removeAttr('disabled');
                    }
                });

            });

            function module_check(obj, obj_name) {
                var $this = $(obj);
                var model_r = $("#" + obj_name + "_model_r");
                var model_w = $("#" + obj_name + "_model_w");
                var model_x = $("#" + obj_name + "_model_x");
                var check = $(obj).attr('checked');
                var obj_all = $("#" + $this.attr('data-parent').replace("_syspri", ""));
                var all_items = $("." + $this.attr('data-parent'));
                var all_checked_items = $("." + $this.attr('data-parent') + ":checked");
                if (check) {

                    model_r.removeAttr('disabled').attr('checked', 'checked').click(function() {
                        return false
                    });
                    if (all_items.length == all_checked_items.length) {
                        obj_all.attr('checked', 'checked')
                    }
                    model_w.removeAttr('disabled');
                    model_x.removeAttr('disabled');

                } else {
                    model_r.removeAttr('checked').attr("disabled", "disabled");
                    model_w.removeAttr('checked').attr("disabled", "disabled");
                    model_x.removeAttr('checked').attr("disabled", "disabled");
                    obj_all.removeAttr('checked');
                }
            }
            function module_check_all(obj, obj_sysmodule) {
                var $this = $(obj);
                var all_model_w = $("#" + obj_sysmodule + "_model_w");
                var all_model_x = $("#" + obj_sysmodule + "_model_x");

                var all_r = $("." + obj_sysmodule + "_r");
                var all_w = $("." + obj_sysmodule + "_w");
                var all_x = $("." + obj_sysmodule + "_x");


                var obj_all = $("." + obj_sysmodule + "_syspri");
                var check = $(obj).attr('checked');
                if (check) {
                    obj_all.attr('checked', 'checked');
                    all_model_w.removeAttr('disabled');
                    all_model_x.removeAttr('disabled');

                    all_r.attr('checked', 'checked').removeAttr('disabled').click(function() {
                        return false
                    });
                    all_w.removeAttr('disabled');
                    all_x.removeAttr('disabled');
                } else {
                    obj_all.removeAttr('checked', 'checked');
                    all_model_w.attr("disabled", "disabled");
                    all_model_x.attr("disabled", "disabled");

                    all_r.removeAttr('checked').attr("disabled", "disabled");
                    all_w.removeAttr('checked').attr("disabled", "disabled");
                    all_x.removeAttr('checked').attr("disabled", "disabled");
                }
            }

            function module_check_w(obj, obj_sysmodule) {
                var $this = $(obj);

                var all_w = $("." + obj_sysmodule + "_w");

                var check = $(obj).attr('checked');
                if (check) {
                    all_w.removeAttr('disabled').attr('checked', 'checked');
                } else {
                    all_w.removeAttr('checked');
                }
            }
            function module_check_x(obj, obj_sysmodule) {
                var $this = $(obj);

                var all_x = $("." + obj_sysmodule + "_x");

                var check = $(obj).attr('checked');
                if (check) {
                    all_x.removeAttr('disabled').attr('checked', 'checked');
                } else {
                    all_x.removeAttr('checked');
                }
            }

        </script>

    </div>
    </div>
    </div>
<?php } ?>