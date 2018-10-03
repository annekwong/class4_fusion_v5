<?php
if (empty($this->data))
{
    ?>
    <?php echo $this->element('listEmpty') ?>
<?php
}
else
{
    ?>
    <div>
        <div class="clearfix"></div>
        <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
            <thead>
            <tr>
                <?php
                if ($_SESSION['role_menu']['Configuration']['users']['model_w'])
                {
                    ?>
                    <th><?php echo __('Active') ?></th>
                <?php } ?>
                <th><?php echo $appCommon->show_order('name', __('username', true)) ?> </th>
                <th><?php echo $appCommon->show_order('user_type', __('usertype', true)) ?> </th>
                <th> <?php echo __('Role Name', true); ?> </th>
                <?php
                if (!isset($n_last_login_time) || !$n_last_login_time)
                {
                    ?>
                    <th><?php echo $appCommon->show_order('last_login_time', __('last_modified', true)) ?> </th>
                <?php } ?>
                <?php
                if ($_SESSION['role_menu']['Configuration']['users']['model_w'])
                {
                    ?><th  class="last"><?php echo __('action') ?></th>
                <?php } ?>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach ($this->data as $list)
            {
                if ($list['User']['active']) {
//                    continue;
                }
                ?>
                <tr>
                    <?php
                    if ($_SESSION['role_menu']['Configuration']['users']['model_w'])
                    {
                        ?>
                        <td>

                            <?php
                            if ($list['User']['active'])
                            {
                                ?>
                                <a style="width:80%;display:block" href="javascript:void(0)" onclick="inactive(this, '<?php echo $list['User']['user_id'] ?>', '<?php echo $list['User']['name'] ?>');">
                                    <i class="icon-check"></i>
                                </a>
                            <?php
                            }
                            else
                            {
                                ?>
                                <a style="width:80%;display:block" href="javascript:void(0)" onclick="active(this, '<?php echo $list['User']['user_id'] ?>', '<?php echo $list['User']['name'] ?>');">
                                    <i class="icon-unchecked"></i>
                                </a>
                            <?php } ?>

                        </td>
                    <?php } ?>
                    <td>
                        <a style="width:80%;display:block" title="" href="<?php echo $this->webroot ?>users/add/<?php echo base64_encode($list['User']['user_id']) ?>">
                            <?php echo array_keys_value($list, 'User.name') ?>
                        </a>
                    </td>

                    <td>
                        <?php echo $appUsers->user_type($list) ?>
                    </td>
                    <td>
                        <?php echo empty($role[$list['User']['role_id']]) ? '' : $role[$list['User']['role_id']]; ?>
                    </td>
                    <?php
                    if (!isset($n_last_login_time) || !$n_last_login_time)
                    {
                        ?>
                        <td><?php echo array_keys_value($list, 'User.last_login_time') ?></td>
                    <?php } ?>
                    <?php
                    if ($_SESSION['role_menu']['Configuration']['users']['model_w'])
                    {
                        ?>
                        <td class="last">
                            <a title="<?php __('Edit') ?>" href="<?php echo $this->webroot ?>users/add/<?php echo base64_encode($list['User']['user_id']) ?>">
                                <i class="icon-edit"></i>
                            </a>
                            <?php
                            $name = array_keys_value($list, 'User.name');
                            if (strcmp($name, 'admin'))
                            {
                                ?>
                                <a title="Reset Password" href="#myModal_change_single_pwd" class="change_user_pwd" user_id="<?php echo base64_encode($list['User']['user_id']); ?>" data-toggle="modal">
                                    <i class="icon-key"></i>
                                </a>
                                <a  title="<?php echo __('del') ?>"  onclick="return myconfirm('Are you sure to delete it? ', this);" href="<?php echo $this->webroot ?>users/del/<?php echo base64_encode($list['User']['user_id']) ?>/<?php echo $list['User']['name'] ?>">
                                    <i class="icon-remove"></i>
                                </a>
                            <?php } ?>
                        </td>
                    <?php } ?>
                </tr>
            <?php } ?>
            </tbody></table>
    </div>
    <div class="row-fluid separator">
        <div class="pagination pagination-large pagination-right margin-none">
            <?php echo $this->element('xpage'); ?>
        </div>
    </div>
    <div class="custom_container"></div>
    <div class="clearfix"></div>
    <div id="myModal_change_single_pwd" class="modal hide">
        <div class="modal-header">
            <button data-dismiss="modal" class="close" type="button">&times;</button>
            <h3><?php __('Reset Password'); ?></h3>
        </div>
        <div class="separator"></div>
        <div class="modal-body">
            <table class="table table-bordered">
                <tr>
                    <td class="align_right"><?php __('New Password')?> </td>
                    <td>
                        <input class="input in-text" name="new_pwd"  id="user_new_pwd" type="password" >
                        <input type="hidden" id="user_change_pwd_id"  />
                    </td>
                </tr>
            </table>
        </div>
        <div class="modal-footer">
            <input type="button" id="change_single_pwd_submit" class="btn btn-primary" value="<?php __('Submit'); ?>">
            <a href="javascript:void(0)" id="change_single_pwd_close" data-dismiss="modal" class="btn btn-default"><?php __('Close'); ?></a>
        </div>

    </div>
<?php } ?>
<script type="text/javascript">
    $(".change_user_pwd").click(function(){
        var UserId = $(this).attr('user_id');
        $("#user_change_pwd_id").val(UserId);
    });

    $("#change_single_pwd_submit").click(function(){
        var UserNew = $("#user_new_pwd").val();
        var UserId = $("#user_change_pwd_id").val();
	if (UserNew.trim() == '') {
		showMessages_new([{'field':'','code':'101','msg':'New Password field should not be empty!'}]);
		return false;
	}

        $.ajax({
            'url': '<?php echo $this->webroot ?>users/ajax_change_password',
            'type': 'POST',
            'dataType': 'json',
            'data': {'UserNew': UserNew, 'user_id': UserId},
            'beforeSend': function(XMLHttpRequest) {
                $('#change_single_pwd_submit').before('<i class="icon-spinner icon-spin icon-large" id="loading_i_changePassword"></i>');//显示等待消息
                $("#change_single_pwd_submit").val('loading...').attr('disabled','true');
            },
            'success': function(data) {
                var msg = data.msg;
                var flg = data.flg;
                $("#change_password_submit").val('Submit').removeAttr('disabled');
                $("#loading_i_changePassword").remove();
                if(flg){
                    jGrowl_to_notyfy(msg, {theme: 'jmsg-success'});
                    $("#change_single_pwd_close").click();
                }
                else{
                    jGrowl_to_notyfy(msg, {theme: 'jmsg-error'});
                }


            }
        });
    });

    //启用Reseller
    function active(obj, user_id, name) {
        bootbox.confirm("Are you sure to activate it?", function(result) {
            if (result) {
                jQuery.get("<?php echo $this->webroot ?>users/activeornot?status=true&id=" + user_id, function(data) {
                    if (data.trim() == 'true') {
                        obj.getElementsByTagName('i')[0].className = "icon-check";
                        obj.title = "";
                        $(obj).attr('onclick', '').unbind("click").click(function() {
                            inactive(this, user_id, name);
                        });
                        jGrowl_to_notyfy("The User [" + name + "] is activated successfully!", {theme: 'jmsg-success'});
                        //notyfy({
                           // text: "The User [" + name + "] <?php __('is activated successfully!') ?>",
                           // type: 'success' // alert|error|success|information|warning|primary|confirm
                        //});
                    } else {
                        jGrowl_to_notyfy(name + " is actived unsuccessfully", {theme: 'jmsg-error'});
                        //notyfy({
                         //  text: name + " <?php __('is actived unsuccessfully') ?>",
                          //  type: 'error' // alert|error|success|information|warning|primary|confirm
                        //});
                    }
                });
            }
        });
    }

    function inactive(obj, user_id, name) {
        bootbox.confirm("Are you sure to deactivate it?", function(result) {
            if (result) {
                jQuery.get("<?php echo $this->webroot ?>users/activeornot?status=false&id=" + user_id, function(data) {
                    if (data.trim() == 'true') {
                        obj.getElementsByTagName('i')[0].className = "icon-unchecked";
                        obj.title = "";
                        $(obj).attr('onclick', '').unbind("click").click(function() {
                            active(this, user_id, name);
                        });
                        jGrowl_to_notyfy("The User [" + name + "] <?php __('is deactivated successfully!') ?>", {theme: 'jmsg-success'});
//                        notyfy({
//                            text:  name + " <?php __('is inactived unsuccessfully') ?>",
//                            type: 'success' // alert|error|success|information|warning|primary|confirm
//                        });
                    } else {
                        notyfy({
                            text: name + " <?php __('is inactived unsuccessfully') ?>",
                            type: 'error' // alert|error|success|information|warning|primary|confirm
                        });
                    }
                });
            }
        });
    }
</script>
