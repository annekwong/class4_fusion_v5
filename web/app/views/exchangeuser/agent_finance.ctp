<style>
    #container input {
        width:100px;
    }
</style>
<div id="title">
 <h1><?php  __('Management')?> &gt;&gt;<?php echo __('Agents Finance')?></h1>
 <ul id="title-search">
        <form method="get" id="myform1">
            
            <li>
                <input type="text" id="query-start_date-wDt1" onFocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:00:00'});" class="input in-text" value="<?php echo date('Y-m-d 00:00:00');?>" readonly="readonly" name="start_date" style="width:140px;" />
                         &mdash;&nbsp;
                            <input type="text" id="query-stop_date-wDt1"  onfocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:00:00'});" class="input in-text" value="<?php echo date('Y-m-d 23:59:59');?>" readonly="readonly"   name="stop_date" style="width:140px;" />
                          in:
                            <select id="tz" name="tz" class="input in-select">
                                <option value="-1200">GMT -12:00</option>
                                <option value="-1100">GMT -11:00</option>
                                <option value="-1000">GMT -10:00</option>
                                <option value="-0900">GMT -09:00</option>
                                <option value="-0800">GMT -08:00</option>
                                <option value="-0700">GMT -07:00</option>
                                <option value="-0600">GMT -06:00</option>
                                <option value="-0500">GMT -05:00</option>
                                <option value="-0400">GMT -04:00</option>
                                <option value="-0300">GMT -03:00</option>
                                <option value="-0200">GMT -02:00</option>
                                <option value="-0100">GMT -01:00</option>
                                <option selected="selected" value="+0000">GMT +00:00</option>
                                <option value="+0100">GMT +01:00</option>
                                <option value="+0200">GMT +02:00</option>
                                <option value="+0300">GMT +03:00</option>
                                <option value="+0330">GMT +03:30</option>
                                <option value="+0400">GMT +04:00</option>
                                <option value="+0500">GMT +05:00</option>
                                <option value="+0600">GMT +06:00</option>
                                <option value="+0700">GMT +07:00</option>
                                <option value="+0800">GMT +08:00</option>
                                <option value="+0900">GMT +09:00</option>
                                <option value="+1000">GMT +10:00</option>
                                <option value="+1100">GMT +11:00</option>
                                <option value="+1200">GMT +12:00</option>
                            </select>
            </li>
                <li> 
                    <span style="float:left; height:25px; line-height:25px; font-size:14px; color:#EEEEEE; font-weight:bold;">Serail Number:</span>
                    <input type="text" id="search-_q" class="in-search default-value input in-text defaultText in-input" title="Search" value="Search" name="search">
                </li>
                    <input type="submit" id="submit" class="search_submit input in-submit" value="" name="submit">
        </form>
    </ul>
 
    <!--<ul id="title-menu">
        <li>
        <a id="add" class="link_btn" href="javascript:void(0);" onclick="add();">
            <img width="16" height="16" alt="" src="<?php echo $this->webroot?>images/add.png">
            Create New
        </a>
        </li>
    </ul>-->
 
</div>
<?php
    $status = array(
        0=>'Inactive',
        1=>'Active'
    );

?>
<div id="container">
     <?php
        $data = $p->getDataArray();
    ?>
    <div id="toppage"></div>
    <?php
        if(count($data) == 0){
    ?>
        <div class="msg"><?php echo __('no_data_found')?></div>
    <?php    
        }else{
    ?>
    <table class="list" id="key_list" >
        <thead>
           
            <tr>
                <td>Serail Number</td>
                <td>Method</td>
                <td>Amount</td>
                <td>Actual Amount</td>
                <td>Fee</td>
                <td>Transaction Date</td>
                <td>Status</td>
                <td>Completed Date</td>
                <td class="last">Action</td>
            </tr>
        </thead>
        <tbody>
            <?php foreach($data as $item): ?>
            <tr>
                <td><?php echo $item[0]['action_number']?></td>
                <td><?php echo $item[0]['action_method']==1?"Paypal":"Wire"?></td>
                <td><?php echo $item[0]['total_amount']?></td>
                <td><?php echo $item[0]['actual_amount']?></td>
                <td><?php echo $item[0]['action_fee']?></td>
                
                <td><?php echo $item[0]['action_time']?></td>
                <td>
                    <?php 
                        if($item[0]['status']==0){
                            if($item[0]['action_method'] == 2){
                                echo "Waiting for confirmation";
                            }
                        }else if($item[0]['status']==1){
                            echo "<font style=\"color:#FF6D06;\">Complete</font>";
                        }
                    ?>                       
                </td>
                <td><?php echo $item[0]['complete_time']?></td>
                <td class="last">
                    
                    <?php 
                        if($item[0]['status']==0){
                            if($item[0]['action_method'] == 2){
                                ?>
                                <a title="confirm" href="javascript:void(0);" onclick="edit_key(this,'<?php echo $item[0]['id']?>')" ><img src="<?php echo $this->webroot.'/images/flag-1.png'?>"></a>
                                <?php
                            }
                        }
                    ?>      
                    
                    <a title="more"href="javascript:void(0);" onclick="get_more_message(this,'<?php echo $item[0]['id']?>');" >more</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div id="tmppage"> <?php echo $this->element('page');?> </div>
    <?php }?>
</div>

<script>
    
   var str_edit = '';
   
   
    
    function edit_key(obj,id){
        $ser_num = $.trim($(obj).parent().parent().find('td').eq(0).html());
                
            if(confirm("Are you sure?")){
                
                $.ajax({
                    'url':'<?=$this->webroot."agent/confirm_finance"?>',
                    'type':'post',
                    'dataType':'json',
                    'data':{'id':id},
                    'success':function (data){
                        if(data['status'] == 'success'){
                            $(obj).parent().prev().prev().html('');
                            $(obj).remove();
                            jGrowl_to_notyfy('The Serail Number [' + $ser_num + '] is modified successfully.',{theme:'jmsg-success'});
                        }
                    }
                });
            }
            
        }
    
    function get_more_message(obj,id){
            if($(obj).parent().parent().next().find('table').length == 0){
                $.ajax({
                'url':"<?php echo $this->webroot.'agent/get_agent_finance_message';?>",
                'type':'post',
                'dataType':'json',
                'data':{'id':id},
                'success':function (data){
                    var message_str = "";
                    $.each(data,function(index,content){
                        message_str += "<tr>\n\
<td>"+content[0]['name']+"</td>\n\
<td>"+content[0]['amount']+"</td>\n\
<td>"+content[0]['actual_amount']+"</td>\n\
<td>"+content[0]['action_fee']+"</td>\n\
</tr>";
                    });
                    
                    $(obj).parent().parent().after("<tr><td colspan='9' style='text-align:center;'><table style='width:80%;margin:auto;' class='list'>\n\
<thead><tr><td>Client Name</td><td>Amount</td><td>Actual Amount</td><td>Fee</td></tr></thead>\n\
<tbody>"+message_str+"</tbody>\n\
</table></td></tr>");
                    
                }
            });
            }else{
                $(obj).parent().parent().next().remove();
            }
            
        }
    
        
        function del_edit_key(obj){
            var tr = $(obj).parent().parent();
            tr.html(str_edit);
    }
    
    
</script>

