<style type="text/css">
#massAdd h1 {
    color:#FF6D06;
    font-size:16px;
    cursor:pointer;
    width:70px;
}
#editor {
    padding:30px;
}
</style>
<div id="massAdd">
    <div id="editor">
            <form method="post" name="myform1" id="myform1">
            <p>
                
                
                
               <table class="form footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
                   <tr>
                       <td style="width:20%"><?php echo __('name',true);?>:</td>
                       <td><input id="name" type="text" name ="data[Dynamicroute][name]"></td>
                       
                       <td style="width:20%"><?php echo __('Routing rule',true);?>:</td>
                       <td style="width:35%">
                           <select id="routing_rule" name="data[Dynamicroute][routing_rule]">
                                <option selected="selected" value="4"><?php __('Largest ASR')?></option>
                                <option value="5"><?php __('Largest ACD')?></option>
                                <option value="6"><?php __('LCR')?></option>
                            </select>
                       </td>
                   </tr>
                   
                   <tr>
                       <td><?php echo __('Time Profile',true);?>:</td>
                       <td>
                            <select id="time_profile_id" name="data[Dynamicroute][time_profile_id]">
                                <?php foreach($user as $k=>$v): ?>
                                    <option value="<?php echo $k; ?>"><?php echo $v; ?></option>
                                <?php endforeach; ?>
                            </select>
                       </td>
                       
                       <td><?php echo __('QoS Cycle',true);?>:</td>
                       <td>
                           <select id="lcr_flag" class="select in-select" name="data[Dynamicroute][lcr_flag]">
                                <option value="1">15 <?php __('Minutes')?></option>
                                <option value="2">30 <?php __('Minutes')?></option>
                                <option value="3">1 <?php __('Hour')?></option>
                                <option value="4">1 <?php __('Day')?></option>
                            </select>
                       </td>
                   </tr>
                   
               </table> 
               
                   
                
    
                
               
                    
                
                       
                
                
                
            </p>
            <p>
               <?php  if ($_SESSION['role_menu']['Routing']['dynamicroutes']['model_w']) {?> <h1><button id="addtrunk" class="input in-button"><?php echo __('add',true);?></button></h1><?php }?>
                <table id="tbl" class="mylist footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
                    <thead>
                        <tr>
                            <th width="40%"><?php echo __('Carriers',true);?></th>
                            <th width="40%"><?php echo __('Trunk Name',true);?></th>
                            <th width="20%"></th>
                        <tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td width="40%">
                                <select id="Carriers1"  onchange="updatetrunks(this)" name="Carriers1[]">
                                    <?php foreach($carriers as $carrier): ?>
                                        <option value="<?php echo $carrier[0]['id']; ?>"><?php echo $carrier[0]['name']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td width="40%">
                                <select id="engress_res_id" name="engress_res_id[]">
                                </select>
                            </td>
                            <td width="20%">
                                <a href="###" onclick="removeitem(this);"><i class='icon-remove'></i></a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </p>
           <?php  if ($_SESSION['role_menu']['Routing']['dynamicroutes']['model_w']) {?> <p style="text-align:center; margin-top:10px;">
                <label><a id="massbtn" class="input in-button"><?php echo __('submit',true);?></a></label>
            </p>
            <?php }?>
        </form> 
    </div>
</div>

<script type="text/javascript">
jQuery(function($) {
    
    $('#massAdd > h1').click(function() {
       $('#editor').slideToggle();
    });
    $('#tbl select').prepend('<option selected="selected" value=""></option>');
    $('#selectall').click(function() {
        if($(this).attr('checked')) {
             $('.mylist input[type=checkbox]').attr('checked','true');
        } else {
             $('.mylist input[type=checkbox]').removeAttr('checked');  
        }
    });
    $('#addtrunk').click(function() {
        $('#tbl tbody tr').clone(true).appendTo('#tbl tbody');
        return false;
    });
    
    $('#massbtn').click(function() {
      
        var trunks = false;
        
        
        for(var i=0;i<$("select[name='engress_res_id[]']").length ;i++){
               if($("select[name='engress_res_id[]']")[i].value != ''){
                   trunks = true;
               }
        }
        
        if(!trunks){
            jQuery.jGrowlError('Egress can not be null!');
            return false;
        }
        
        if($("#name").val() == ""){
            jQuery.jGrowlError('name can not be null!');
            return false;
        }
     
        $.ajax({
            url:'<?php echo $this->webroot; ?>routestrategys/addDynamicRouting',
            type:"POST",
            dataType:"text",
            data: $("#myform1").serialize(),
            // data:{"UserID":11, "Name":[{"FirstName":"Truly","LastName":"Zhu"}], "Email":"zhuleipro◎hotmail.com"},
            //data:{name:$("#name").val(),routing_rule:$("#routing_rule").val(),time_profile_id:$("#time_profile_id").val(),lcr_flag:$("#lcr_flag").val(),Carriers1:$("#Carriers1").val(),engress_res_id:$("#engress_res_id").val()},
            success:function(data) {
                //alert(data);
                //window.location.reload();
                if(data == 'isHavaName'){
                    jQuery.jGrowlError('This name already exists');
                }else if(data == ""){
                    
                }else{
                    $(".marginTopDyna").append("<option value = '"+data+"'>"+$("#name").val()+"</option>");
                    $(".marginTopDyna").attr('value',$("#name").val());
                    $(".marginTopDyna").parent().attr('itemvalue',data);
                    $("#name").attr("value","");
                    $(".ui-icon-closethick").click();
                }
                
            }
        });
        return false;
    });
});
function updatetrunks(elem) {
    $.getJSON('<?php echo $this->webroot?>trunks/ajax_options?filter_id='+$(elem).val()+'&type=egress', function(data) {
     $brother = $(elem).parent().next().find('select'); 
     $brother.empty();
     $.each(data, function(idx, item){
            $('<option value="'+item.resource_id+'">'+item.alias+'</option>')
                    .appendTo($brother);
        });
    })    
}

function removeitem(elem) {
    if($('#tbl tbody tr').length == 1) {
        return;
    }
    $(elem).parent().parent().remove();
}
</script>