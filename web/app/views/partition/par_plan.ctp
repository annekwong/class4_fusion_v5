<style>
    #container input {
        width:100px;
    }
</style>
<ul class="breadcrumb">
    <li><?php __('You are here')?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Management') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Partition Plan') ?></li>
</ul>
<div class="heading-buttons">
    <h4 class="heading"><?php __('Partition Plan')?></h4>
    
</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
        <a id="add" class="btn btn-primary btn-icon glyphicons circle_plus" href="javascript:void(0);" onclick="add();"><i></i>
            <?php __('Create New')?>
        </a>
    </div>
<div class="clearfix"></div>

<div class="innerLR">

    <div class="widget widget-heading-simple widget-body-white">
        <div class="widget-body">


            <div id="container">
                <?php
                $data = $p->getDataArray();
 $type = array(
            0=>'By Month',
            1=>'By Minutes'
        );
                ?>

<?php
        if(count($data) == 0){
    ?>
        <h2 class="msg center"><?php echo __('no_data_found')?></h2>
    <?php    
        }else{
    ?>
                <div class="separator bottom row-fluid">
                    <div class="pagination pagination-large pagination-right margin-none">
                        <?php echo $this->element('page'); ?>
                    </div> 
                </div>

                <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary" id="key_list" >
                    <thead>

                        <tr>
                            <th><?php echo $appCommon->show_order('money', __('Amount(USD)', true)) ?></th>
                            <th>Type</th>
                            <th><?php echo $appCommon->show_order('port', __('Port<', true)) ?></th>
                            <th><?php echo $appCommon->show_order('minutes', __('Minute', true)) ?></th>
                            <th class="last"><?php __('Action')?></th>
                        </tr>
                    </thead>
                    <tbody>
							<?php foreach($data as $item): ?>
							<tr>
								<td><?php echo $item[0]['money']?></td>
								<td><?php echo $type[$item[0]['type']]?></td>
								<td><?php 
								    
								    if($item[0]['type'] == 0){
								            echo $item[0]['port'];
								     }
								    
								        //echo $item[0]['port']
								    ?>
								</td>
								<td><?php 
								        if($item[0]['type'] == 1){
								            echo $item[0]['minutes'];
								        }
								    ?></td>
								<td class="last">
								    <a title="edit" href="javascript:void(0);" onclick="edit_key(this,'<?php echo $item[0]['id']?>')" ><i class="icon-edit"></i></a>
								    <a title="delete" href="javascript:void(0);" onclick="del('<?php echo $item[0]['id']?>')"><i class="icon-remove"></i></a>
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
   
   function add(){
       
       if($(".msg").length == 0 || $(".msg").css('display')=='none'){
                            $("#key_list").append("<tr>\n\
                <td><input class='in-text' type='text' name='money'></td>\n\
                <td><select onchange='check_min();' class='in-select type'  name='type'><option value=0>By Month</option><option value=1>By Minutes</option></select></td>\n\
                <td><input class='in-text' type='text' name='port'></td>\n\
                <td><input class='in-text' type='text' name='minutes'></td>\n\
                <td><a title='save' href='javascript:void(0);' onclick='add_key(this)'><i class='icon-save'></i></a>\n\
                <a title='cancel' href='javascript:void(0);' onclick='del_add_key(this)'><i class='icon-remove'></i></a></td>\n\
            </tr>");
                        }else{
                            $(".msg").hide();
                            $("#container").append("<table id=\"key_list\" cellspacing=\"0\" cellpadding=\"0\" class=\"list\">\n\
    <thead>\n\
        <tr>\n\
            <td>Amount(USD)</td>\n\
            <td>Type</td>\n\
            <td>Port</td>\n\
            <td>Minutes</td>\n\
            <td>Action</td>\n\
        </tr>\n\
    </thead>\n\
    <tbody>\n\
        <tr>\n\
            <td><input class='in-text' type='text' name='money'></td>\n\
            <td><select onchange='check_min();' class='in-select type'  name='type'><option value=0>By Month</option><option value=1>By Minutes</option></select></td>\n\
            <td><input class='in-text' type='text' name='port'></td>\n\
            <td><input class='in-text' type='text' name='minutes'></td>\n\
            <td><a title='save' href='javascript:void(0);' onclick='add_key(this)'><i class='icon-save'></i></a>\n\
                            <a title='cancel' href='javascript:void(0);' onclick='del_add_key(this)'><i class='icon-remove'></i></a></td>\n\
            </tr>\n\
    </tbody>\n\
</table>");
                        }
                        
                        check_min()
                        
                        
                    
    }
    
    function check_min(){
        
        $(".type").each(function(index,content){
            var tr = $(content).parent().parent();
        
            if($(content).val() == 0){
                tr.find("input[name=port]").show();
                tr.find("input[name=minutes]").hide();
            }else{
                tr.find("input[name=port]").hide();
                tr.find("input[name=minutes]").show();
            }
            
        });
        
        
        
        
        
        
    }
    
    function add_key(obj){
            var tr = $(obj).parent().parent();
           
            var money = tr.find("input[name=money]").val();
            var port = tr.find("input[name=port]").val();
            var minutes = tr.find("input[name=minutes]").val();
            var type = tr.find("select[name=type]").val();
           
           
            $.ajax({
                'url':"<?php echo $this->webroot.'partition/add_plan';?>",
                'type':'post',
                'data':{'money':money,'port':port,'minutes':minutes,'type':type},
                'dataType':'json',
                'async':false,
                'success':function (data){
                    if(data['status'] == 'success'){
                        jGrowl_to_notyfy('The plan [' + money + '] is created successfully.!',{theme:'jmsg-success'});
                        //location = "<?php echo $this->webroot.'clients/product_list_first';?>";
                        window.setTimeout(function() {window.location.reload(true)},3000);
                    }else if(data['status'] == 'isEmpty'){
                        
                        if(type == 0){
                            jGrowl_to_notyfy('The Amount and Port can not be empty!',{theme:'jmsg-error'});
                        }else{
                            jGrowl_to_notyfy('The Amount and Minutes can not be empty!',{theme:'jmsg-error'});
                        }
                        
                        
                    }else if(data['status'] == 'email_error'){
                        
                        if(type == 0){
                            jGrowl_to_notyfy('The Amount and Port must be numeric.',{theme:'jmsg-error'});
                        }else{
                            jGrowl_to_notyfy('The Amount and Minutes must be numeric.',{theme:'jmsg-error'});
                        }
                        
                    }else{
                         //jGrowl_to_notyfy('The plan [' + money + '] or Domain ['+domain+'] is already exists!',{theme:'jmsg-error'});
                    }
                }
            });
            
        }
    
    
    function del(id){
        if(confirm("Are you want to delete this record!")){
            location = "<?php echo $this->webroot?>partition/del_plan/"+id;
        }
    }
    
    function change_pwd(obj,id){
        
            var tr = $(obj).parent().parent();
            var edit = $("#key_list").find("a[title='save edit']");
            
            if(edit.length == 0){
               str_edit = tr.html();
               tr = tr.get(0);
               tr.cells[1].innerHTML = "<input class='in-text' type='password' name='pwd'>";
               tr.cells[10].innerHTML = "<a title='save edit' href='javascript:void(0);' onclick='save_pwd(this,"+id+")'><i class='icon-save'></i></a>\n\
            <a title='cancel' href='javascript:void(0);' onclick='del_edit_key(this)' ><i class='icon-remove'></i></a>";
            }
    }
    function save_pwd(obj,id){
        var tr = $(obj).parent().parent();
        var pwd = tr.find("input[name=pwd]").val();
        tr = tr.get(0);
        var name = $.trim(tr.cells[0].innerHTML);
        $.ajax({
            'url':"<?php echo $this->webroot.'agent/save_pwd';?>",
            'type':'post',
            'data':{'pwd':pwd,'id':id,'name':name},
            'dataType':'json',
            'async':false,
            'success':function (data){
                if(data['status'] == 'success'){
                    jGrowl_to_notyfy('The Product[' + name + '] is modified successfully.',{theme:'jmsg-success'});
                    $(tr.cells[1]).html("******");
                    window.setTimeout(function() {window.location.reload(true)},3000);
                }
            }
        });
    }
    
    function edit_key(obj,id){
            var tr = $(obj).parent().parent();
            
            var edit = $("#key_list").find("a[title='save edit']");
            
            if(edit.length == 0){
               str_edit = tr.html();
               tr = tr.get(0);
               var money = $.trim(tr.cells[0].innerHTML);
               var type = $.trim(tr.cells[1].innerHTML);
               
               var port = $.trim(tr.cells[2].innerHTML);
               var minutes = $.trim(tr.cells[3].innerHTML);
               
               var select_1 = "";
               var select_2 = "";
               
               if(type == 'By Month'){
                   select_1 = "selected";
               }else{
                   select_2 = "selected";
               }
               
               
               tr.cells[0].innerHTML = "<input type='text' class='in-text' value='"+money+"' name='money'>";
               tr.cells[1].innerHTML = "<select type='text' onchange='check_min();' class='in-select type' name='type'>\n\
                                        <option "+select_1+" value=0>By Month</option>\n\
                                        <option "+select_2+" value=1>By Minutes</option>\n\
                                        </select>";
               
               tr.cells[2].innerHTML = "<input type='text' class='in-text' value='"+port+"' name='port'>";
               tr.cells[3].innerHTML = "<input type='text' class='in-text' value='"+minutes+"' name='minutes'>";
               
               
               
               tr.cells[4].innerHTML = "<a title='save edit' href='javascript:void(0);' onclick='save_edit(this,"+id+")'><i class='icon-save'></i></a>\n\
            <a title='cancel' href='javascript:void(0);' onclick='del_edit_key(this)' ><i class='icon-remove'></i></a>";
               
               
               check_min();
               
               //$(tr.cells[7]).find('select option[text='+status+']').attr('selected','true');
               
            }else{
                jGrowl_to_notyfy('You must first save!',{theme:'jmsg-error'});
                return false;
            }
            
        }
    
    function save_edit(obj,id){
            var tr = $(obj).parent().parent();
            
             //var money = tr.find("input[name=money]").val();
             //var cost_min = tr.find("input[name=cost_min]").val();
             
            var money = tr.find("input[name=money]").val();
            var port = tr.find("input[name=port]").val();
            var minutes = tr.find("input[name=minutes]").val();
            var type = tr.find("select[name=type]").val();
             
             
             
            $.ajax({
                'url':"<?php echo $this->webroot.'partition/save_plan';?>",
                'type':'post',
                'data':{'money':money,'port':port,'id':id,'minutes':minutes,"type":type},
                'dataType':'json',
                'async':false,
                'success':function (data){
                    if(data['status'] == 'success'){
                        jGrowl_to_notyfy('The Plan [' + money + '] is modified successfully.',{theme:'jmsg-success'});
                        window.setTimeout(function() {window.location.reload(true)},3000);
                    }else if(data['status'] == 'isEmpty'){
                        
                        if(type == 0){
                            jGrowl_to_notyfy('The Amount and Port can not be empty!',{theme:'jmsg-error'});
                        }else{
                            jGrowl_to_notyfy('The Amount,Port,Minutes can not be empty!',{theme:'jmsg-error'});
                        }
                        
                        
                    }else if(data['status'] == 'email_error'){
                        
                        if(type == 0){
                            jGrowl_to_notyfy('The Amount and Port must be numeric.',{theme:'jmsg-error'});
                        }else{
                            jGrowl_to_notyfy('The Amount,Port,Minutes must be numeric.',{theme:'jmsg-error'});
                        }
                        
                    }else{
                         //jGrowl_to_notyfy('The plan [' + money + '] or Domain ['+domain+'] is already exists!',{theme:'jmsg-error'});
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

