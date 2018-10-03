<style type="text/css">
    .role_module li{ float:left; width:48%; margin-left:15px; vertical-align:top;}
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
<?php if (0) { ?>
    <?php
    echo $this->element('common/exception_msg');

    if (!array_keys_value($role_name, '0.0.role_name') == '') {
        $role_name = "[" . array_keys_value($role_name, '0.0.role_name') . "]";
    }
    ?>
<?php } else { ?>
    <?php
//pr($this->data);
    if (isset($this->data['Exchangesysrole']['role_name'])) {
        $name_role = "[" . $this->data['Exchangesysrole']['role_name'] . "]";
    } else {

        $name_role = '';
    }
    if ($role_name[0][0]['role_name'] != '') {
        echo $this->element('layout/header', array('h1' => 'Exchange Manage', 'h1_span' => ' Edit Role <font class=\'editname\' title=\'Name\' >[' . $role_name[0][0]['role_name'] . ']</font>  :', 'back_url' => 'exchangesysrolepris/view_sysrolepri'));
    } else {
        echo $this->element('layout/header', array('h1' => 'Exchange Manage', 'h1_span' => ' Add Role ', 'back_url' => 'exchangesysrolepris/view_sysrolepri'));
    }
    ?>

    <div class="innerLR">

        <div class="widget widget-heading-simple widget-body-white">
            <?php echo $form->create('Exchangesysrolepri', array('action' => 'add_sysrolepri/' . $type, 'name' => 'add_sysrolepri')); ?>

            <div class="role_module">
                <ul>
                    <li>
                        <fieldset>
                            <legend> Basic &nbsp;</legend>
                            <?php //**********系统信息**************?>
                            <table class="form table table-condensed">
                                <tr>
                                    <td><div class="cb_select" style="height:32px; line-height:32px; border:0px;">	  <input id="SysrolepriRoleId" class="input in-text in-input" type="hidden" value="<?php echo array_keys_value($this->params, 'pass.0') ?>" name="data[Sysrole][role_id]">

                                            <div><?php echo __('Role Name', true); ?>: <?php //echo $form->input('role_name',array('label'=>false,'div'=>false,'type'=>'text','maxLength'=>'100','class'=>'input in-text'))  ?>

                                                <input id="SysrolepriRoleName" class="input in-text in-input" type="text" value="<?php echo $role_name[0][0]['role_name'] ?>" maxlength="256" name="data[Sysrole][role_name]" style="width:180px;">

                                                <?php echo __('View All Carriers', TRUE); ?>
                                                <input type="checkbox" name="data[Sysrole][view_all]" <?php if ($role_name[0][0]['view_all']) echo 'checked="checked"'; ?> />

                                            </div>
                                        </div></td>

                                </tr>
                            </table>
                        </fieldset>
                    </li>

                    <li>
                        <fieldset>
                            <legend> Finance &nbsp;</legend>

                            <table class="form table table-condensed">
                                <tbody><tr>
                                    <td class="value" colspan="2">

                                        <div style=" clear:both; color: #30353A;height: 20px; line-height:20px;overflow: auto;padding: 3px 2px 3px 6px;text-align: left;">
                                                <span style="float:left;">
                                                    <input type="checkbox" onclick='module_check_all(this, "Invoice_all");' name="invoice_payment" value="1" id="Management_all" class="border_no input in-checkbox"> 
                                                    <label for="invoice_payment">All</label>
                                                </span>
                                        </div>

                                        <div class="cb_select input">
                                            <div class="alt" style="clear:both;">
                                                    <span style="float:left;">
                                                        <input type="checkbox" class="Invoice_all_syspri border_no input in-checkbox" <?php if ($role_name[0][0]['delete_invoice']) echo 'checked="checked"'; ?>
                                                               name="data[Exchangesysrole][delete_invoice]" id="delete_invoice">
                                                        <label for="delete_invoice">Allow to delete invoice</label>
                                                    </span>
                                            </div>
                                            <div class="alt" style="clear:both;">
                                                    <span style="float:left;">
                                                        <input type="checkbox" class="Invoice_all_syspri border_no input in-checkbox" <?php if ($role_name[0][0]['delete_payment']) echo 'checked="checked"'; ?>
                                                               name="data[Exchangesysrole][delete_payment]" id="delete_payment">
                                                        <label for="delete_payment">Allow to delete payment</label>
                                                    </span>
                                            </div>
                                            <div class="alt" style="clear:both;">
                                                    <span style="float:left;">
                                                        <input type="checkbox" class="Invoice_all_syspri border_no input in-checkbox" <?php if ($role_name[0][0]['delete_credit_note']) echo 'checked="checked"'; ?>
                                                               name="data[Exchangesysrole][delete_credit_note]" id="delete_creditnote">
                                                        <label for="delete_creditnote">Allow to delete credit note</label>
                                                    </span>
                                            </div>
                                            <div class="alt" style="clear:both;">
                                                    <span style="float:left;">
                                                        <input type="checkbox" class="Invoice_all_syspri border_no input in-checkbox" <?php if ($role_name[0][0]['delete_debit_note']) echo 'checked="checked"'; ?>
                                                               name="data[Exchangesysrole][delete_debit_note]" id="delete_debitnote">
                                                        <label for="delete_debitnote">Allow to delete debit note</label>
                                                    </span>
                                            </div>
                                            <div class="alt" style="clear:both;">
                                                    <span style="float:left;">
                                                        <input type="checkbox" class="Invoice_all_syspri border_no input in-checkbox" <?php if ($role_name[0][0]['reset_balance']) echo 'checked="checked"'; ?>
                                                               name="data[Exchangesysrole][reset_balance]" id="reset_balance">
                                                        <label for="reset_balance">Allow to reset balance</label>
                                                    </span>
                                            </div>
                                            <div class="alt" style="clear:both;">
                                                    <span style="float:left;">
                                                        <input type="checkbox" class="Invoice_all_syspri border_no input in-checkbox" <?php if ($role_name[0][0]['modify_credit_limit']) echo 'checked="checked"'; ?>
                                                               name="data[Exchangesysrole][modify_credit_limit]" id="modify_credit_limit">
                                                        <label for="modify_credit_limit">Allow to modify credit limit</label>
                                                    </span>
                                            </div>
                                            <div class="alt" style="clear:both;">
                                                    <span style="float:left;">
                                                        <input type="checkbox" class="Invoice_all_syspri border_no input in-checkbox" <?php if ($role_name[0][0]['modify_min_profit']) echo 'checked="checked"'; ?>
                                                               name="data[Exchangesysrole][modify_min_profit]" id="modify_min_profit">
                                                        <label for="modify_min_profit">Allow to modify Min.Profitability</label>
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
            </fieldset>

            </li>

            <?php
            $role_menu = $sysmodule; //var_dump($role_menu);
            //pr($role_menu);
            if (!empty($role_menu)) {
                foreach ($role_menu as $k => $v) {
                    $id_modulename = str_replace(" ", '_', $k);
                    ?>
                    <li>
                        <fieldset>
                            <legend> <?php echo __($k); ?> &nbsp;</legend>

                            <table class="form table table-condensed">
                                <tr>
                                    <td colspan="2" class="value">

                                        <div style=" clear:both; color: #30353A;height: 20px; line-height:20px;overflow: auto;padding: 3px 2px 3px 6px;text-align: left;">
                                                    <span style="float:left;">
                                                        <input id="<?php echo $id_modulename . '_all' ?>" type="checkbox" value="1" name="<?php echo $id_modulename; ?>_all" onclick='module_check_all(this, "<?php echo $id_modulename; ?>_all");'/> 
                                                        <label for="<?php echo $id_modulename . '_all' ?>">All</label>
                                                    </span>
                                                    <span style="float:right;">

                                                        <input type="checkbox" name="<?php echo $id_modulename . '_all_model_w' ?>" value="1" id="<?php echo $id_modulename; ?>_all_model_w" disabled="disabled" onclick='module_check_w(this, "<?php echo $id_modulename; ?>_all");'/>
                                                        <label for="<?php echo $id_modulename; ?>_all_model_w"><?php echo __('All_W', true); ?></label>&nbsp;
                                                        <input type="checkbox" name="<?php echo $id_modulename . '_all_model_x' ?>" value="1" id="<?php echo $id_modulename; ?>_all_model_x" disabled="disabled"onclick='module_check_x(this, "<?php echo $id_modulename; ?>_all");'/>
                                                        <label for="<?php echo $id_modulename; ?>_all_model_x"><?php echo __('All_E', true); ?></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>

                                        </div>

                                        <div class="cb_select input">

                                            <?php
                                            //var_dump($sysrolepri);
                                            foreach ($v as $k1 => $v1) {
                                                ?>
                                                <div style="clear:both;" class="alt">
                                                    <?php
                                                    //echo $form->checkbox($v1['pri_name'])
                                                    $id_priname = str_replace(":", '_', $v1['pri_name']);
                                                    ?>
                                                    <span style="float:left;">
                                                                <input id="<?php echo $id_priname ?>" type="checkbox" value="<?php echo $v1['id'] ?>" name="data[Exchangesysrolepri][<?php echo $v1['pri_name'] ?>][pri_id]" <?php if (!empty($sysrolepri[$v1['pri_name']])) echo 'checked="checked"'; ?>  onclick='module_check(this, "<?php echo $id_priname ?>");' class="<?php echo $id_modulename; ?>_all_syspri"/>
                                                                <label for="<?php echo $id_priname ?>"><?php __($v1['pri_val']); ?></label>
                                                            </span>

                                                            <span style="float:right;">
                                                                <input type="checkbox" name="data[Exchangesysrolepri][<?php echo $v1['pri_name'] ?>][model_r]" value="1" <?php if (!empty($sysrolepri[$v1['pri_name']]) && $sysrolepri[$v1['pri_name']]['model_r'] == true) { ?>
                                                                    checked="checked" class="module_check ischecked <?php echo $id_modulename . '_all_r' ?>"
                                                                <?php } else { ?>
                                                                    class="module_check nochecked <?php echo $id_modulename . '_all_r' ?>"
                                                                <?php } ?>   id="<?php echo $id_priname ?>_model_r"/>
                                                                <?php echo __('Read', true); ?>&nbsp;
                                                                <input type="checkbox" name="data[Exchangesysrolepri][<?php echo $v1['pri_name'] ?>][model_w]" value="1" <?php if (!empty($sysrolepri[$v1['pri_name']]) && $sysrolepri[$v1['pri_name']]['model_w'] == true) { ?> checked="checked" class="module_check  <?php echo $id_modulename . '_all_w' ?>"<?php } else { ?>class="module_check <?php echo $id_modulename . '_all_w' ?>"
                                                                <?php } ?> id="<?php echo $id_priname ?>_model_w"/>
                                                                <?php echo __('Write', true); ?>&nbsp;
                                                                <input type="checkbox" name="data[Exchangesysrolepri][<?php echo $v1['pri_name'] ?>][model_x]" value="1" <?php if (!empty($sysrolepri[$v1['pri_name']]) && $sysrolepri[$v1['pri_name']]['model_x'] == true) { ?>checked="checked" class="module_check  <?php echo $id_modulename . '_all_x' ?>" <?php } else { ?>class="module_check <?php echo $id_modulename . '_all_x' ?>"
                                                                       <?php } ?>id="<?php echo $id_priname ?>_model_x"/>
                                                                <?php echo __('Execute', true); ?> </span>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </fieldset>

                    </li>
                    <?php
                }
            }
            ?>
            </ul>
        </div>
    </div>


    <?php if ($_SESSION['role_menu']['Exchange Manage']['exchangesysrolepris:sysrolepris']['model_w']) { ?>

        <div id="form_footer" class="button-groups center" style="clear:both;">
            <input type="submit" id="submit" value="<?php echo __('submit') ?>" class="input in-submit btn btn-primary" />
            <input type="reset" id="reset_all"  value="<?php echo __('reset') ?>"  class="input in-submit btn btn-default" />
        </div>
    <?php } ?>
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


            $("#reset_all").click(function(){

                $(":checkbox").removeAttr('checked');

            });
            jQuery('#RoleAddRoleForm').submit(function() {
                te = true;
                if (/[^0-9A-Za-z-\_\.]{1,100}/.test(jQuery('#RoleRoleName').val()) || jQuery('#RoleRoleName').val().length > 100) {
                    jQuery('#RoleRoleName').addClass('invalid');
                    jGrowl_to_notyfy('Role name, allowed characters: a-z,A-Z,0-9,-,_,space, maximum  of 16 characters!', {theme: 'jmsg-error'});
                    te = false;
                }
                if (jQuery('#RoleRoleName').val() == '') {
                    jQuery('#RoleRoleName').addClass('invalid');
                    jGrowl_to_notyfy('The field name cannot be NULL.', {theme: 'jmsg-error'});
                    te = false;
                }

                return te;
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
            if (check) {

                model_r.removeAttr('disabled').attr('checked', 'checked').click(function() {
                    return false
                });
                model_w.removeAttr('disabled');
                model_x.removeAttr('disabled');

            } else {
                model_r.removeAttr('checked').attr("disabled", "disabled");
                model_w.removeAttr('checked').attr("disabled", "disabled");
                model_x.removeAttr('checked').attr("disabled", "disabled");

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

        jQuery(document).ready(function() {
            $("#submit").click(function() {
                if ($("#SysrolepriRoleName").val() != '') {
                    if (!/^[0-9a-zA-Z_\-]{1,16}$/.test($("#SysrolepriRoleName").val())) {
                        jGrowl_to_notyfy('Role name, allowed characters: a-z,A-Z,0-9,-,_,space, maximum  of 16 characters!', {theme: 'jmsg-error'});
                        return false;
                    }
                } else {
                    jGrowl_to_notyfy('The field name cannot be NULL.', {theme: 'jmsg-error'});
                    return false;
                }
            });

        });
    </script>

    </div>
<?php } ?>