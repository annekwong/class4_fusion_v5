<style>
    #container input {
        width:100px;
    }
    #container select{width: 100px;}
</style>

<ul class="breadcrumb">
    <li><?php __('You are here')?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Management') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Partitions') ?></li>
</ul>
<div class="heading-buttons">
    <h4 class="heading"><?php __('Partitions')?></h4>
</div>
<div class="separator bottom"></div>

<?php
    $status = array(
        0=>'Inactive',
        1=>'Active'
    );

?>


<div class="innerLR">

    <div class="widget widget-heading-simple widget-body-white">
        <div class="widget-body">
            <div class="filter-bar">
                <form method="get" id="myform1">
                    <div>
                        <label><?php __('Name')?>:</label>
                        <input type="text" id="search-_q" class="in-search default-value input in-text defaultText in-input" title="<?php __('Search')?>" value="Search" name="search">
                    </div>

                    <div>
                        <button name="submit" class="btn query_btn search_submit input in-submit"><?php __('Query')?></button>
                    </div>
                </form>
            </div>

            
            <?php
            $status = array(
                0 => 'Inactive',
                1 => 'Active'
            );
            ?>
            <div id="container">
                <?php
                $data = $p->getDataArray();
                ?>
                <div id="toppage"></div>
                <?php
                if (count($data) == 0) {
                    ?>
                    <div class="msg"><?php echo __('no_data_found') ?></div>
                    <?php
                } else {
                    ?>

                    <div class="separator bottom row-fluid">
                        <div class="pagination pagination-large pagination-right margin-none">
                            <?php echo $this->element('page'); ?>
                        </div> 
                    </div>
                    <div class="clearfix"></div>
                    <div class="overflow_x">
                    <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary" id="key_list" >
                        <thead>

                            <tr>
										<th><?php echo $appCommon->show_order('username', __('Name', true)) ?></th>
										<th><?php __('Password')?></th>
										<th><?php echo $appCommon->show_order('company_name', __('Company', true)) ?></th>
										<th><?php echo $appCommon->show_order('email', __('Email', true)) ?></th>
										<th><?php echo $appCommon->show_order('phone_number', __('Phone Number', true)) ?></th>
										<th><?php echo $appCommon->show_order('domain_name', __('Domain Name', true)) ?></th>
										<th><?php echo $appCommon->show_order('last_login_time', __('Last Login Time', true)) ?></th>
										<th><?php __('Status')?></th>
										<!--<td>Is Default</th>-->
										<th><?php __('Ip')?></th>
										<th><?php __('Port')?></th>
										<th class="last"><?php __('Action')?></th>
									</tr>
                        </thead>
                        <tbody>

                            <?php foreach($data as $item): ?>
									<tr>
										<td><?php echo $item[0]['username']?></td>
										<td>******</td>
										<td><?php echo $item[0]['company_name']?></td>
										<td><?php echo $item[0]['email']?></td>
										<td><?php echo $item[0]['phone_number']?></td>
										<td><?php echo $item[0]['domain_name']?></td>
										<td><?php echo $item[0]['last_login_time']?></td>
										<td><?php echo $status[$item[0]['status']]?></td>
										<!--<td><?php echo $item[0]['is_default']?'YES':'NO'; ?></td>-->
										<td><?php echo $item[0]['sip_ip']?></td>
										<td><?php echo $item[0]['sip_port']?></td>
										<td class="last">
											<a title="partition" target="_blank"   href="<?php echo $this->webroot?>partition/admin_login?par_id=<?php echo $item[0]['id']?>"> 
												<i class="icon-arrow-right"></i>
											</a>
											<a title="edit" href="javascript:void(0);" onclick="edit_key(this,'<?php echo $item[0]['id']?>')" ><i class="icon-edit"></i></a>
											<a href="javascript:void(0);" title=" Change password" onclick="change_pwd(this,'<?php echo $item[0]['id']?>')" ><i class="icon-key"></i></a>
											
										</td>
									</tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    </div>
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
                <td><input class='in-text' type='text' name='name'></td>\n\
                <td><input class='in-text' type='password' name='pwd'></td>\n\
                <td><input class='in-text' type='text' name='company_name'></td>\n\
                <td><input class='in-text' type='text' name='email'></td>\n\
                <td><input class='in-text' type='text' name='phone_number'></td>\n\
                <td><input class='in-text' type='text' name='domain_name'></td><td></td>\n\
                <td><select name='status' class = 'in-select' ><option value='0'>Inactive</option><option value='1'>Active</option></select></td>\n\
                <td><input class='in-text' type='text' name='sip_ip'></td>\n\
                <td><input class='in-text' type='text' name='sip_port'></td>\n\
                <td><a title='save' href='javascript:void(0);' onclick='add_key(this)'><i class='icon-save'></i></a>\n\
                \n\<a title='cancel' href='javascript:void(0);' onclick='del_add_key(this)'><i class='icon-remove'></i></a></td>\n\
            </tr>");
        } else {
            $(".msg").hide();
            $("#container").append("<table id=\"key_list\" cellspacing=\"0\" cellpadding=\"0\" class=\"list\">\n\
    <thead>\n\
        <tr>\n\
            <td>Name</td>\n\
            <td>Password</td>\n\
            <td>Company</td>\n\
            <td>Email</td>\n\
            <td>Phone Number</td>\n\
            <td>Domain Name</td>\n\
            <td>Last Login Time</td>\n\
            <td>Status</td>\n\
            <td>Ip</td><td>Port</td><td>Action</td>\n\
        </tr>\n\
    </thead>\n\
    <tbody>\n\
        <tr>\n\
            <td><input class='in-text' type='text' name='name'></td>\n\
            <td><input class='in-text' type='password' name='pwd'></td>\n\
            <td><input class='in-text' type='text' name='company_name'></td>\n\
            <td><input class='in-text' type='text' name='email'></td>\n\
            <td><input class='in-text' type='text' name='phone_number'></td>\n\
            <td><input class='in-text' type='text' name='domain_name'></td>\n\
            <td></td>\n\
            <td><select name='status' class = 'in-select'><option value='0'>Inactive</option><option value='1'>Active</option></select></td>\n\
            <td><input class='in-text' type='text' name='sip_ip'></td>\n\
            <td><input class='in-text' type='text' name='sip_port'></td>\n\
            <td><a title='save' href='javascript:void(0);' onclick='add_key(this)'><i class='icon-save'></i></a>\n\
                            <a title='cancel' href='javascript:void(0);' onclick='del_add_key(this)'><i class='icon-remove'></i></a></td>\n\
            </tr>\n\
    </tbody>\n\
</table>");
        }

    }



    function add_key(obj) {
        var tr = $(obj).parent().parent();

        var name = tr.find("input[name=name]").val();
        var pwd = tr.find("input[name=pwd]").val();
        var company_name = tr.find("input[name=company_name]").val();
        var email = tr.find("input[name=email]").val();
        var phone_number = tr.find("input[name=phone_number]").val();
        var domain = tr.find("input[name=domain_name]").val();
        var status = tr.find("select[name=status]").val();
        var ip = tr.find("input[name=sip_ip]").val();
        var port = tr.find("input[name=sip_port]").val();

        $.ajax({
            'url': "<?php echo $this->webroot . 'partition/add_partition'; ?>",
            'type': 'post',
            'data': {'port': port, 'ip': ip, 'name': name, 'pwd': pwd, 'company_name': company_name, 'email': email, 'phone_number': phone_number, 'domain': domain, 'status': status},
            'dataType': 'json',
            'async': false,
            'success': function(data) {
                if (data['status'] == 'success') {
                    jGrowl_to_notyfy('The Agent [' + name + '] is created successfully.!', {theme: 'jmsg-success'});
                    //location = "<?php echo $this->webroot . 'clients/product_list_first'; ?>";
                    window.setTimeout(function() {
                        window.location.reload(true)
                    }, 3000);
                } else if (data['status'] == 'isEmpty') {
                    jGrowl_to_notyfy('Agent Name can not be empty!', {theme: 'jmsg-error'});
                } else if (data['status'] == 'email_error') {
                    jGrowl_to_notyfy('The email field must contain a valid email address!', {theme: 'jmsg-error'});
                } else {
                    jGrowl_to_notyfy('The Agent[' + name + '] or Domain [' + domain + '] is already exists!', {theme: 'jmsg-error'});
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
            tr.cells[10].innerHTML = "<a title='save edit' href='javascript:void(0);' onclick='save_pwd(this," + id + ")'><i class='icon-save'></i></a>\n\
            <a title='cancel' href='javascript:void(0);' onclick='del_edit_key(this)' ><i class='icon-remove'></i></a>";
        }
    }
    function save_pwd(obj, id) {
        var tr = $(obj).parent().parent();
        var pwd = tr.find("input[name=pwd]").val();
        tr = tr.get(0);
        var name = $.trim(tr.cells[0].innerHTML);
        $.ajax({
            'url': "<?php echo $this->webroot . 'partition/save_par_pwd'; ?>",
            'type': 'post',
            'data': {'pwd': pwd, 'id': id, 'name': name},
            'dataType': 'json',
            'async': false,
            'success': function(data) {
                if (data['status'] == 'success') {
                    jGrowl_to_notyfy('The Partition[' + name + '] is modified successfully.', {theme: 'jmsg-success'});
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
            var name = $.trim(tr.cells[0].innerHTML);
            var company_name = $.trim(tr.cells[2].innerHTML);
            var email = $.trim(tr.cells[3].innerHTML);
            var phone_number = $.trim(tr.cells[4].innerHTML);
            var domain_name = $.trim(tr.cells[5].innerHTML);
            var status = $.trim(tr.cells[7].innerHTML);
            //var ip = $.trim(tr.cells[9].innerHTML);

            tr.cells[0].innerHTML = "<input type='text' class='in-text' value='" + name + "' name='name'>";
            tr.cells[1].innerHTML = "";
            tr.cells[2].innerHTML = "<input class='in-text' type='text' value='" + company_name + "'  name='company_name'>";
            tr.cells[3].innerHTML = "<input class='in-text' type='text' value='" + email + "'  name='email'>";
            tr.cells[4].innerHTML = "<input class='in-text' type='text' value='" + phone_number + "'  name='phone_number'>";
            tr.cells[5].innerHTML = "<input class='in-text' type='text' value='" + domain_name + "'  name='domain_name'>";
            //tr.cells[9].innerHTML = "<input class='in-text' type='text' value='"+ip+"'  name='ip'>";
            tr.cells[7].innerHTML = "<select name='status' class = 'in-select'><option value='0'>Inactive</option><option value='1'>Active</option></select>";
            tr.cells[10].innerHTML = "<a title='save edit' href='javascript:void(0);' onclick='save_edit(this," + id + ")'><i class='icon-save'></i></a>\n\
            <a title='cancel' href='javascript:void(0);' onclick='del_edit_key(this)' ><i class='icon-remove'></i></a>";

            $(tr.cells[7]).find('select option[text=' + status + ']').attr('selected', 'true');

        } else {
            jGrowl_to_notyfy('You must first save!', {theme: 'jmsg-error'});
            return false;
        }

    }

    function save_edit(obj, id) {
        var tr = $(obj).parent().parent();

        var name = tr.find("input[name=name]").val();
        var company_name = tr.find("input[name=company_name]").val();
        var email = tr.find("input[name=email]").val();
        var phone_number = tr.find("input[name=phone_number]").val();
        var domain = tr.find("input[name=domain_name]").val();
        //var ip = tr.find("input[name=ip]").val();
        var status = tr.find("select[name=status]").val();
        $.ajax({
            'url': "<?php echo $this->webroot . 'partition/save_partition'; ?>",
            'type': 'post',
            'data': {'name': name, 'company_name': company_name, 'email': email, 'phone_number': phone_number, 'domain': domain, 'id': id, 'status': status},
            'dataType': 'json',
            'async': false,
            'success': function(data) {
                if (data['status'] == 'success') {
                    jGrowl_to_notyfy('The Partition [' + name + '] is modified successfully.', {theme: 'jmsg-success'});
                    window.setTimeout(function() {
                        window.location.reload(true)
                    }, 3000);
                } else if (data['status'] == 'isEmpty') {
                    jGrowl_to_notyfy('The Partition [' + name + '] can not be empty!', {theme: 'jmsg-error'});
                } else if (data['status'] == 'email_error') {
                    jGrowl_to_notyfy('The email field must contain a valid email address!', {theme: 'jmsg-error'});
                } else {
                    jGrowl_to_notyfy('The Partition [' + name + '] or Domain [' + domain + '] is already exists!', {theme: 'jmsg-error'});
                }
            }
        });
    }

    function del_add_key(obj){
            var tr = $(obj).parent().parent().parent();
            if(tr.find('tr').length == 1){
                $('.msg').show();
                $(obj).parent().parent().parent().parent().remove();
            }else{
                $(obj).parent().parent().remove();
            }
    }
        
        function del_edit_key(obj){
            var tr = $(obj).parent().parent();
            tr.html(str_edit);
    }


</script>

