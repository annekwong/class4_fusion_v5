<style>
    #container input {
        width:100px;
    }
</style>

<ul class="breadcrumb">
    <li>You are here</li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Exchange Manage') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Users') ?></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading">Users</h4>

    <div class="clearfix"></div>
</div>

<?php
$status = array(
    0 => 'Inactive',
    1 => 'Active'
);
?>
<div class="separator bottom"></div>
<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-head">
            <ul>
                <li <?php if (!strcmp($type, 'agent')) { ?>class="active"<?php } ?>><a class="glyphicons no-js group" href="<?php echo $this->webroot; ?>exchangeuser/index/agent"><i></i>Agent User</a></li>
                <li <?php if (!strcmp($type, 'partition')) { ?>class="active"<?php } ?>><a class="glyphicons no-js group" href="<?php echo $this->webroot; ?>exchangeuser/index/partition"><i></i>Partition User</a></li>
                <li <?php if (!strcmp($type, 'exchange')) { ?>class="active"<?php } ?>><a class="glyphicons no-js group" href="<?php echo $this->webroot; ?>exchangeuser/index/exchange"><i></i>Exchange User</a></li>
            </ul>
        </div>
        <div class="widget-body">
            <div class="filter-bar">

                <form method="get" id="myform1">
                    <!-- Filter -->
                    <div>
                        <label>Search:</label>
                        <input type="text" id="search-_q" class="in-search default-value input in-text defaultText" title="<?php echo __('namesearch') ?>" value="<?php if (!empty($search)) echo $search; ?>" name="search">
                    </div>


                    <!-- Filter -->
                    <div>
                        <button name="submit" class="btn query_btn">Query</button>
                    </div>
                    <!-- // Filter END -->


                </form>
            </div>



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
                                <th>User Name</th>
                                <th>Role Name</th>
                                <th>Last Login Time</th>
                                <th class="last">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($data as $item): ?>
                                <tr>
                                    <td><?php echo $item[0][$field] ?></td>
                                    <td><?php echo empty($item[0]['role_name']) ? 'Default' : $item[0]['role_name']; ?></td>
                                    <td><?php echo $item[0]['last_login_time'] ?></td>
                                    <td class="last">
                                        <a type="<?php echo $type; ?>" role_id="<?php echo $item[0]['role_id'] ?>" title="edit" href="javascript:void(0);" onclick="edit_key(this, '<?php echo $item[0]['client_id'] ?>')" ><i class="icon-edit"></i></a>
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



    function edit_key(obj, id) {
        var tr = $(obj).parent().parent();

        var edit = $("#key_list").find("a[title='save edit']");

        if (edit.length == 0) {
            str_edit = tr.html();
            tr = tr.get(0);


            var role_id = $(obj).attr('role_id');
            var type = $(obj).attr('type');
            var select_str = "<option value='0'>Default</option>"
            $.ajax({
                'url': "<?php echo $this->webroot . 'exchangeuser/get_roles'; ?>",
                'type': 'post',
                'data': {'type': type},
                'dataType': 'json',
                'async': false,
                'success': function(data) {
                    $.each(data, function(index, content) {
                        if (content[0]['role_id'] == role_id) {
                            select_str += "<option selected value='" + content[0]['role_id'] + "'>" + content[0]['role_name'] + "</option>";
                        } else {
                            select_str += "<option  value='" + content[0]['role_id'] + "'>" + content[0]['role_name'] + "</option>";
                        }

                    });


                    tr.cells[1].innerHTML = "<select name='role' class = 'in-select'>" + select_str + "</select>";
                    tr.cells[3].innerHTML = "<a type='" + type + "' title='save edit' href='javascript:void(0);' onclick='save_edit(this," + id + ")'><i class='icon-save'></i></a>\n\
                    <a title='cancel' href='javascript:void(0);' onclick='del_edit_key(this)' ><i class='icon-remove'></i></a>";
                }
            });




        } else {
            jGrowl_to_notyfy('You must first save!', {theme: 'jmsg-error'});
            return false;
        }

    }

    function save_edit(obj, id) {
        var tr = $(obj).parent().parent();

        var type = $(obj).attr('type');
        var name = tr.find("td").eq(0).html();

        var role_id = tr.find("select[name=role]").val();

        $.ajax({
            'url': "<?php echo $this->webroot . 'exchangeuser/save_user'; ?>",
            'type': 'post',
            'data': {'id': id, 'type': type, 'role_id': role_id},
            'dataType': 'json',
            'async': false,
            'success': function(data) {

                if (data['status'] == 'success') {
                    jGrowl_to_notyfy('The User [' + name + '] is modified successfully.', {theme: 'jmsg-success'});
                    window.setTimeout(function() {
                        window.location.reload(true)
                    }, 3000);
                } else {
                    jGrowl_to_notyfy('The User [' + name + '] is modified unsuccessfully.', {theme: 'jmsg-error'});
                }
            }
        });
    }

    function del_edit_key(obj) {
        var tr = $(obj).parent().parent();
        tr.html(str_edit);
    }


</script>

