<?php if (empty($this->data)) { ?>
    <?php echo $this->element('listEmpty') ?>
<?php } else { ?>
    <?php if ($_SESSION['role_menu']['Management']['clients']['model_w']) { ?>

        <fieldset style="display:block ;border-top:1px solid #B5C7D8" static>
            <legend style="color: rgb(125, 133, 142); font-size: 1.1em; font-weight: bold;"> <?php echo __('Hide Inactive Carrier', true); ?>
                <input type="checkbox" title="Static" id="show_static" style="display:inline;" <?php
                if ($$hel->_get('filter_status') === "true") {
                    echo "checked";
                }
                ?> />
            </legend>
        </fieldset>
    <?php } ?>
    <?php echo $this->element("xpage") ?>
    <div style="width:100%;">
        <table class="list">
            <thead>
                <tr> 
                  <!--        <td><?php echo $appCommon->show_order('client_id', __('ID', true)) ?></td>-->
                    <td><?php echo $appCommon->show_order('name', __('Name', true)); ?></td>

                    <td colspan="3"><?php echo __('Mutual Balance', true); ?></td>
                    <td colspan="3"><?php echo __('Available Balance', true); ?></td>
                    <td><?php echo $appCommon->show_order('allowed_credit', __('Credit Limit', true)) ?></td>
                    <td><?php echo $appCommon->show_order('egress_count', __('egress', true)); ?></td>
                    <td><?php echo $appCommon->show_order('ingress_count', __('ingress', true)); ?></td>
                    <td><?php echo $appCommon->show_order('update_at', __('Update At', true)); ?></td>
                    <td><?php echo $appCommon->show_order('update_by', __('Update By', true)); ?></td>
                    <td class="last"><?php echo __('action') ?></td>

                </tr>
                <tr>
                    <td></td>

                    <td><?php echo __('Ingress', true); ?></td>
                    <td><?php echo __('Egress', true); ?></td>
                    <td><?php echo __('Total', true); ?></td>

                    <td><?php echo __('Ingress', true); ?></td>
                    <td><?php echo __('Egress', true); ?></td>
                    <td><?php echo __('Balance', true); ?></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td class="last"></td>

                </tr>
            </thead>

                <?php foreach ($this->data as $list) { ?>
                <tr> 
                  <!--		<td  align="center">
        <?php echo $list['Client']['client_id'] ?>	
                                    </td>-->
                    <td  align="center"><a  href="<?php echo $this->webroot ?>clients/edit/<?php echo $list['Client']['client_id'] ?>" style="width:100%;display:block;"> <?php echo $list['Client']['name'] ?> </a></td>

                    <td><?php $my_pi_1 = number_format(array_keys_value($list, 'Payment.balance_1'), 3);
        echo $my_pi_1 < 0 ? '(' . str_replace('-', '', $my_pi_1) . ')' : $my_pi_1;
        ?></td>
                    <td><?php $my_pi_2 = number_format(array_keys_value($list, 'Payment.balance_2'), 3);
                echo $my_pi_2 < 0 ? '(' . str_replace('-', '', $my_pi_2) . ')' : $my_pi_2;
                ?></td>
                    <td><?php $my_pi = number_format(array_keys_value($list, 'Payment.balance_1') - array_keys_value($list, 'Payment.balance_2') + array_keys_value($list, '0.offset') - array_keys_value($list, '0.credit'), 3);
                echo $my_pi < 0 ? '(' . str_replace('-', '', $my_pi) . ')' : $my_pi;
        ?></td>
                    <td><?php $my_pi = number_format($list['ClientBalance']['ingress_balance'], 3);
                echo $my_pi < 0 ? '(' . str_replace('-', '', $my_pi) . ')' : $my_pi;
                ?></td>
                    <td><?php $my_pi = number_format($list['ClientBalance']['egress_balance'], 3);
                echo $my_pi < 0 ? '(' . str_replace('-', '', $my_pi) . ')' : $my_pi;
                ?></td>
                    <td ><?php $my_pi = number_format($list['ClientBalance']['balance'], 3);
                echo $my_pi < 0 ? '(' . str_replace('-', '', $my_pi) . ')' : $my_pi;
                ?></td>
                    <td><?php $my_pi = number_format($list['Client']['allowed_credit'], 3);
                echo $my_pi < 0 ? str_replace('-', '', $my_pi) : $my_pi;
                ?></td>

                    <td><a class='egress_count' style="width:100%;display:block;" href='<?php echo $this->webroot ?>prresource/gatewaygroups/view_egress?query[id_clients]=<?php echo $list['Client']['client_id'] ?>&viewtype=client'><?php echo array_keys_value($list, 'Client.egress_count') ?></a></td>
                    <td><a class='ingress_count' style="width:100%;display:block;" href='<?php echo $this->webroot ?>prresource/gatewaygroups/view_ingress?query[id_clients]=<?php echo $list['Client']['client_id'] ?>&viewtype=client'><?php echo array_keys_value($list, 'Client.ingress_count') ?></a></td>
                    <td><?php echo array_keys_value($list, 'Client.update_at') ?></td>
                    <td><?php echo array_keys_value($list, 'Client.update_by') ?></td>
                    <td class="last">
                        <div  class="action_icons" style="width:220px;">

                            <?php if ($_SESSION['role_menu']['Management']['clients']['model_w']) { ?>
                                <a title='Reset Balance' class="reset" control="<?php echo $list['Client']['client_id'] ?>" href="###"> <img width="16" height="16" src="<?php echo $this->webroot ?>images/balanceOperations.gif"> </a>
                            <?php } ?>

                            <a title='<?php __('viewcdrlist') ?>' href="<?php echo $this->webroot ?>cdrreports/summary_reports/client/<?php echo $list['Client']['client_id'] ?>"> <img width="16" height="16" src="<?php echo $this->webroot ?>images/bDrPlans.png"> </a>

        <?php if ($_SESSION['role_menu']['Management']['clients']['model_w']) { ?>
                                <a  title='<?php __('refills') ?>' href="<?php echo $this->webroot ?>resclis/create_payment?client_id=<?php echo $list['Client']['client_id'] ?>&type=0"> <img width="16" height="16" src="<?php echo $this->webroot ?>images/m_refill.gif"> </a>
                            <?php } ?>

                            <?php if ($_SESSION['role_menu']['Management']['clients']['model_w']) { ?>
                                <a title='Send Rate' href="<?php echo $this->webroot ?>ratemails/sendrate/<?php echo $list['Client']['client_id'] ?>"> <img width="16" height="16" src="<?php echo $this->webroot ?>images/send_rate.png"> </a>
                            <?php } ?>

                            <?php if ($_SESSION['role_menu']['Management']['clients']['model_w']) { ?>
                                <a  style="display:block;"  href="<?php echo $this->webroot ?>homes/auth_user?client_id=<?php echo $list['Client']['client_id'] ?>&lang=<?php echo $lang ?>"> <img width="16" height="16" src="<?php echo $this->webroot ?>images/bLogins.gif"> </a>

                                <a   href='#'  rel="update_password"> <img  title=" Change password" src="<?php echo $this->webroot ?>images/menuIcon_014.gif"> </a>
            <?php if ($list['Client']['status'] == 1) { ?>
                                    <a   onclick="return confirm('Are you sure to inactive the selected <?php echo $list['Client']['name'] ?>?')"   href="<?php echo $this->webroot ?>clients/dis_able/<?php echo $list['Client']['client_id'] ?>?<?php echo $$hel->getParams('getUrl') ?>" > <img  title=" <?php echo 'Click to inactive'; ?>" src="<?php echo $this->webroot ?>images/flag-1.png"> </a>
            <?php } else { ?>
                                    <a  onclick="return confirm('Are you sure to active the selected <?php echo $list['Client']['name'] ?>?')" href="<?php echo $this->webroot ?>clients/active/<?php echo $list['Client']['client_id'] ?>?<?php echo $$hel->getParams('getUrl') ?>"  > <img  title=" <?php echo 'Click to active'; ?>" src="<?php echo $this->webroot ?>images/flag-0.png" static="0" > </a>
                    <?php } ?>
                                <a title="<?php echo __('edit') ?>"  href="<?php echo $this->webroot ?>clients/edit/<?php echo $list['Client']['client_id'] ?>"> <i class="icon-edit"></i> </a> <a title="<?php echo __('del') ?>" client_name='<?php echo $list['Client']['name'] ?>' client_id='<?php echo $list['Client']['client_id'] ?>	' onClick="delete_client(this);
                                                                return false;
                                                                return confirm('Are you sure to delete , carriers <?php echo $list['Client']['name'] ?> ?');" href="<?php echo $this->webroot ?>clients/del/<?php echo $list['Client']['client_id'] ?>"> <i class="icon-remove"></i> </a> 
        <?php } ?>
                            <input  type="hidden"  name="hidden_client_id"    value="<?php echo $list['Client']['client_id'] ?>"/>
                        </div>
                    </td>

                </tr>
    <?php } ?>
        </table>
    </div>
    <div id="resetdiv" style="position:absolute;width:400px;height:50px;background:#fff;line-height:50px;text-align:center;left:500px;top:300px;display:none;">
        <label>Reset Banlance:</label>
        <input type="text" name="balance" />
        <input type="button" value="<?php echo __('submit', true); ?>" />
    </div>
    <?php echo $this->element("xpage") ?>
<?php } ?>
<script type="text/javascript">
            var options;
            var del_id;
            var del_name;
            function delete_client(obj) {
            del_id = jQuery(obj).attr('client_id');
                    del_name = jQuery(obj).attr('client_name');
                    options = jQuery.xshow({
                    width: '350px',
                            left: '30%',
                            html: '<div style="font-size:14px">When the carrier is removed, all the relevant CDRs will be deleted.Please choose one of the following options:</div>' +
                            '<div>' +
                            '<br/>' +
                            '<div style="font-size:12px"><a href="#" onclick="ajax_del_client(1,this);return false;">1. Confirm to delete without exporting CDRs</a></div>' +
                            '<br/>' +
                            '<div style="font-size:12px"><a href="#" onclick="ajax_del_client(2,this);return false;">2. Export all CDRs before deleting  </a></div>' +
                            '<br/>' +
                            '</div>'
                    });
            }
    function ajax_del_client(type, obj) {
    jQuery(obj).parent().parent().parent().html('<span style="font-size:16px;color:red">Is deleted, please wait. . . .</span>');
            if (type == 1) {
    var url = "<?php echo $this->webroot ?>clients/ajax_del/" + del_id + "/true";
            var data = jQuery.ajaxData(url);
            if (data) {
    jQuery.jGrowlSuccess('The Carrier ' + del_name + ' is deleted successfully');
            window.location.href = '<?php echo $this->webroot ?>logging/index/' + data + '/clients-index');
    } else {
    jQuery.jGrowlError('delete fail!');
    }
    }
    if (type == 2) {
    var url = "<?php echo $this->webroot ?>clients/ajax_del/" + del_id;
            var data = jQuery.ajaxData(url);
            if (data) {
    jQuery.jGrowlSuccess('The Carrier ' + del_name + ' is deleted successfully');
            window.open('<?php echo $this->webroot ?>clients/down_client_cdr/' + del_id);
            window.location.href = '<?php echo $this->webroot ?>logging/index/' + data + '/clients-index');
    } else {
    jQuery.jGrowlError('delete fail!');
    }
    }
    jQuery('#list_div').html(jQuery.ajaxData());
            jQuery.closexshow(options);
    }
    $(function() {
    $('.reset').click(function() {
    var client_id = $(this).attr('control');
            $('#resetdiv').show();
            $('#resetdiv').find('input[type=button]').click(function() {
    $(this).unbind('click');
            var balance = $('#resetdiv').find('input[type=text]').val();
            if (balance == '') {
    $('#resetdiv').hide();
            return;
    }
    $.ajax(
    {
    url: '<?php echo $this->webroot ?>clients/set_balance/' + client_id + '/' + balance,
            type: 'GET',
            dataType: 'text',
            success: function(data) {
            $('#resetdiv').hide();
                    window.location.reload();
            }
    }
    );
    });
    });
    });
</script> 
