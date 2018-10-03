<style>
    #container input {
        width:100px;
    }
</style>

<ul class="breadcrumb">
    <li>You are here</li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Management') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Agents Setting') ?></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading">Agents Setting</h4>
    <div class="buttons pull-right">
        <a id="add" class="link_btn btn btn-primary btn-icon glyphicons circle_plus" href="javascript:void(0);" onclick="add();"><i></i>
            Create New
        </a>
    </div>
</div>
<div class="separator bottom"></div>

<?php
$email_type = array(
    1 => 'Primary Email',
    2 => 'Technical Email',
    3 => 'Billing Email',
    4 => 'Rate Email',
);

$email_action = array(
    1 => 'Push List',
    2 => 'Target List',
    3 => 'Trouble Ticket',
    4 => 'Invoice',
    5 => 'Finance Alert',
    6 => 'Rate',
);
?>
<div class="innerLR">

    <div class="widget widget-heading-simple widget-body-white">
        <div class="widget-body">

            <div class="clearfix"></div>
            <div id="container">
                <?php
                $data = $p->getDataArray();
                ?>
                <div class="separator bottom row-fluid">
                    <div class="pagination pagination-large pagination-right margin-none">
                        <?php echo $this->element('page'); ?>
                    </div> 
                </div>
                <?php
                if (count($data) == 0) {
                    ?>
                    <div class="msg"><?php echo __('no_data_found') ?></div>
                    <?php
                } else {
                    ?>
                    <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary" id="key_list" >
                        <thead>
                            <tr>
                                <th>id</th>
                                <th>Email Type</th>
                                <th>Email Action</th>
                                <th class="last">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($data as $item): ?>
                                <tr>
                                    <td><?php echo $item[0]['id'] ?></td>
                                    <td><?php echo $email_type[$item[0]['email_type']] ?></td>
                                    <td><?php echo $email_action[$item[0]['email_action']] ?></td>
                                    <td class="last">
                                        <a title="edit" href="javascript:void(0);" onclick="edit_key(this, '<?php echo $item[0]['id'] ?>')" ><i class="icon-edit"></i></a>
                                        <a title="delete" href="<?php echo $this->webroot . 'agent/delete_agent_set/' . $item[0]['id']; ?>" onclick="return confirm('Are you sure?');" ><i class="icon-remove"></i></a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <div class="separator bottom row-fluid">
                        <div class="pagination pagination-large pagination-right margin-none">
                            <?php echo $this->element('page'); ?>
                        </div> 
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>


<script>

    var str_edit = '';

    function add() {

        if ($(".msg").length == 0 || $(".msg").css('display') == 'none') {
            $("#key_list").append("<tr>\n\
                <td></td>\n\
                <td>\n\
<select name='email_type' class = 'in-select' >\n\
<option value='1'>Primary Email</option>\n\
<option value='2'>Technical Email</option>\n\
<option value='3'>Billing Email</option>\n\
<option value='4'>Rate Email</option>\n\
</select>\n\
</td>\n\
                <td>\n\
<select name='email_action' class = 'in-select' >\n\
<option value='1'>Push List</option>\n\
<option value='2'>Target List</option>\n\
<option value='3'>Trouble Ticket</option>\n\
<option value='4'>Invoice</option>\n\
<option value='5'>Finance Alert</option>\n\
<option value='6'>Rate</option></select>\n\
</td>\n\
                <td><a title='save' href='javascript:void(0);' onclick='add_key(this)'><img src ='<?php echo $this->webroot . '/images/menuIcon_004.gif'; ?>'></a>\n\
                <a title='cancel' href='javascript:void(0);' onclick='del_add_key(this)'><i class='icon-remove'></i></a></td>\n\
            </tr>");
        } else {
            $(".msg").hide();
            $("#container").append("<table id=\"key_list\" cellspacing=\"0\" cellpadding=\"0\" class=\"list\">\n\
    <thead>\n\
        <tr>\n\
            <td>Id</td>\n\
            <td>Email Type</td>\n\
            <td>Email Action</td>\n\
            <td>Action</td>\n\
        </tr>\n\
    </thead>\n\
    <tbody>\n\
        <tr>\n\
            <td></td>\n\
            <td>\n\
<select name='email_type' class = 'in-select' >\n\
<option value='1'>Primary Email</option>\n\
<option value='2'>Technical Email</option>\n\
<option value='3'>Billing Email</option>\n\
<option value='4'>Rate Email</option>\n\
</select>\n\
</td>\n\
                <td>\n\
<select name='email_action' class = 'in-select' >\n\
<option value='1'>Push List</option>\n\
<option value='2'>Target List</option>\n\
<option value='3'>Trouble Ticket</option>\n\
<option value='4'>Invoice</option>\n\
<option value='5'>Finance Alert</option>\n\
<option value='6'>Rate</option></select>\n\
</td>\n\
<<td><a title='save' href='javascript:void(0);' onclick='add_key(this)'><img src ='<?php echo $this->webroot . '/images/menuIcon_004.gif'; ?>'></a>\n\
                            <a title='cancel' href='javascript:void(0);' onclick='del_add_key(this)'><i class='icon-remove'></i></a></td>\n\
            </tr>\n\
    </tbody>\n\
</table>");
        }

    }



    function add_key(obj) {
        var tr = $(obj).parent().parent();

        var email_type = tr.find("select[name=email_type]").val();
        var email_action = tr.find("select[name=email_action]").val();

        $.ajax({
            'url': "<?php echo $this->webroot . 'agent/add_agent_set'; ?>",
            'type': 'post',
            'data': {'email_type': email_type, 'email_action': email_action},
            'dataType': 'json',
            'async': false,
            'success': function(data) {
                if (data['status'] == 'success') {
                    jGrowl_to_notyfy('The Action is created successfully.!', {theme: 'jmsg-success'});
                    //location = "<?php echo $this->webroot . 'clients/product_list_first'; ?>";
                    window.setTimeout(function() {
                        window.location.reload(true)
                    }, 3000);
                }
            }
        });

    }


    function del(id, product_name) {
        if (confirm("Are you want to delete this record!")) {
            location = "<?php echo $this->webroot ?>clients/del_product/" + id + '/' + product_name;
        }
    }

    function change_pwd(obj, id) {

        var tr = $(obj).parent().parent();
        var edit = $("#key_list").find("a[title='save edit']");

        if (edit.length == 0) {
            str_edit = tr.html();
            tr = tr.get(0);
            tr.cells[1].innerHTML = "<input class='in-text' type='password' name='pwd'>";
            tr.cells[9].innerHTML = "<a title='save edit' href='javascript:void(0);' onclick='save_pwd(this," + id + ")'><img src ='<?php echo $this->webroot . '/images/menuIcon_004.gif' ?>'></a>\n\
            <a title='cancel' href='javascript:void(0);' onclick='del_edit_key(this)' ><i class='icon-remove'></i></a>";
        }
    }
    function save_pwd(obj, id) {
        var tr = $(obj).parent().parent();
        var pwd = tr.find("input[name=pwd]").val();
        tr = tr.get(0);
        var name = $.trim(tr.cells[0].innerHTML);
        $.ajax({
            'url': "<?php echo $this->webroot . 'agent/save_pwd'; ?>",
            'type': 'post',
            'data': {'pwd': pwd, 'id': id, 'name': name},
            'dataType': 'json',
            'async': false,
            'success': function(data) {
                if (data['status'] == 'success') {
                    jGrowl_to_notyfy('The Product[' + name + '] is modified successfully.', {theme: 'jmsg-success'});
                    $(tr.cells[1]).html("******");
                    window.setTimeout(function() {
                        window.location.reload(true)
                    }, 3000);
                }
            }
        });
    }

    function edit_key(obj, id) {
        var tr = $(obj).parent().parent();

        var edit = $("#key_list").find("a[title='save edit']");

        if (edit.length == 0) {
            str_edit = tr.html();
            tr = tr.get(0);
            var email_type = $.trim(tr.cells[1].innerHTML);
            var email_action = $.trim(tr.cells[2].innerHTML);

            tr.cells[0].innerHTML = "";
            tr.cells[1].innerHTML = "<select name='email_type' class = 'in-select' >\n\
<option value='1'>Primary Email</option>\n\
<option value='2'>Technical Email</option>\n\
<option value='3'>Billing Email</option>\n\
<option value='4'>Rate Email</option>\n\
</select>";
            tr.cells[2].innerHTML = "<select name='email_action' class = 'in-select' >\n\
<option value='1'>Push List</option>\n\
<option value='2'>Target List</option>\n\
<option value='3'>Trouble Ticket</option>\n\
<option value='4'>Invoice</option>\n\
<option value='5'>Finance Alert</option>\n\
<option value='6'>Rate</option></select>";

            tr.cells[3].innerHTML = "<a title='save edit' href='javascript:void(0);' onclick='save_edit(this," + id + ")'><img src ='<?php echo $this->webroot . '/images/menuIcon_004.gif' ?>'></a>\n\
            <a title='cancel' href='javascript:void(0);' onclick='del_edit_key(this)' ><i class='icon-remove'></i></a>";

            $(tr.cells[1]).find('select option[text=' + email_type + ']').attr('selected', 'true');
            $(tr.cells[2]).find('select option[text=' + email_action + ']').attr('selected', 'true');

        } else {
            jGrowl_to_notyfy('You must first save!', {theme: 'jmsg-error'});
            return false;
        }

    }

    function save_edit(obj, id) {
        var tr = $(obj).parent().parent();

        var email_type = tr.find("select[name=email_type]").val();
        var email_action = tr.find("select[name=email_action]").val();
        $.ajax({
            'url': "<?php echo $this->webroot . 'agent/save_agent_set'; ?>",
            'type': 'post',
            'data': {'email_type': email_type, 'email_action': email_action, 'id': id},
            'dataType': 'json',
            'async': false,
            'success': function(data) {
                if (data['status'] == 'success') {
                    jGrowl_to_notyfy('The Action is modified successfully.', {theme: 'jmsg-success'});
                    window.setTimeout(function() {
                        window.location.reload(true)
                    }, 3000);
                }
            }
        });
    }

    function del_add_key(obj) {
        var tr = $(obj).parent().parent().parent();
        if (tr.find('tr').length == 1) {
            $('.msg').show();
            $(obj).parent().parent().parent().parent().remove();
        } else {
            $(obj).parent().parent().remove();
        }
    }

    function del_edit_key(obj) {
        var tr = $(obj).parent().parent();
        tr.html(str_edit);
    }


</script>

