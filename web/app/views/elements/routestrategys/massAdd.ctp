<style type="text/css">
#massAdd1 h1 {
    color:#FF6D06;
    font-size:16px;
    cursor:pointer;
    width:70px;
}
#editor1 {
    padding:30px;
}
</style>
<div id="massAdd1">
    <div id="editor1">
            <form method="post" name="myform2" id="myform2">
            <p>
              <table class="form footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
                   <tr>
                       <td><?php echo __('code',true);?>:</td>
                       <td><input id="name1" type="text" name ="digits">
                       <input type="hidden" name="product_id" id="product_id">
                       </td>
                       
                       <td><?php echo __('strategy',true);?>:</td>
                       <td>
                          <select class="input in-select select" name="strategy" id="strategy">
                            <option value="1"><?php __('Top-Down')?></option>
                            <option value="0"><?php __('By Percentage')?> </option>
                            <option value="2"><?php __('Round Robin')?></option>
                          </select>
                       </td>
                       
                       
                       <td><?php echo __('Time Profile',true);?>:</td>
                       <td>
                            <select id="time_profile_id1" name="time_profile_id">
                                <?php foreach($user as $k=>$v): ?>
                                    <option value="<?php echo $k; ?>"><?php echo $v; ?></option>
                                <?php endforeach; ?>
                            </select>
                       </td>
                   </tr>
                   
                   <tr>
                       
                       
                       <!--<td><?php echo __('QoS Cycle',true);?>:</td>
                       <td>
                           <select id="lcr_flag1" class="select in-select" name="">
                                <option value="1">15 Minutes</option>
                                <option value="2">30 Minutes</option>
                                <option value="3">1 Hour</option>
                                <option value="4">1 Day</option>
                            </select>
                       </td>-->
                   </tr>
                   
               </table> 
                
            </p>
            <p>
               <?php  if ($_SESSION['role_menu']['Routing']['dynamicroutes']['model_w']) {?> <h1><button id="addtrunk1" class="input in-button"><?php echo __('add',true);?></button></h1><?php }?>
                <table id="tbl1" class="mylist footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
                    <thead>
                        <tr>
                            <th width="20%"><?php echo __('Carriers',true);?></th>
                            <th width="20%"><?php echo __('Trunk Name',true);?></th>
                            <th width="20%"><?php echo __('Rate',true);?></th>
                            <th width="20%"><?php echo __('Percentage',true);?></th>
                            <th width="20%"></th>
                        <tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td width="20%">
                                <select id="Carriers11"  onchange="updatetrunks1(this)" name="Carriers1[]">
                                    <?php foreach($carriers as $carrier): ?>
                                        <option value="<?php echo $carrier[0]['id']; ?>"><?php echo $carrier[0]['name']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td width="20%">
                                <select onchange="updateRate1(this);" id="resource_id[]" name="resource_id[]">
                                </select>
                            </td>
                            <td width="20%">
                                
                            </td>
                            <td width="20%">
                                <input style="display:none;" type="text" name ="percentage[]" id="percentage[]">
                            </td>
                            <td width="20%">
                                <a href="###" onclick="removeitem1(this);"><i class='icon-remove'></i></a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </p>
           <?php  if ($_SESSION['role_menu']['Routing']['dynamicroutes']['model_w']) {?> <p style="text-align:center; margin-top:10px;">
                <label><a id="massbtn1" class="input in-button btn btn-primary"><?php echo __('submit',true);?></a></label>
            </p>
            <?php }?>
        </form> 
    </div>
</div>

<script type="text/javascript">
jQuery(function($) {
    //$('#editor1').hide();
    
    $('#massAdd1 > h1').click(function() {
       $('#editor1').slideToggle();
    });
    $('#tbl1 select').prepend('<option selected="selected" value=""></option>');
    $('#selectall1').click(function() {
        if($(this).attr('checked')) {
             $('.mylist input[type=checkbox]').attr('checked','true');
        } else {
             $('.mylist input[type=checkbox]').removeAttr('checked');  
        }
    });
    $('#addtrunk1').click(function() {
        $('#tbl1 tbody tr').clone(true).appendTo('#tbl1 tbody');
        return false;
    });
    
    
    $("#strategy").click(function (){
        
        if($(this).val()=='0'){
                $('input[id^=percentage]').show().val('');
        }else{
                $('input[id^=percentage]').hide();
        }
        
        
    });
    
    $('#massbtn1').click(function() {
      
        /*var trunks = false;
        
        
        for(var i=0;i<$("select[name='engress_res_id[]']").length ;i++){
               if($("select[name='engress_res_id[]']")[i].value != ''){
                   trunks = true;
               }
        }
        
        if(!trunks){
            alert('Egress can not be null!');
            return false;
        }
        
        if($("#name").val() == ""){
            alert('name can not be null!');
            return false;
        }*/
     
        $.ajax({
            url:'<?php echo $this->webroot;?>routestrategys/add_static_route',
            type:"POST",
            dataType:"text",
            data: $("#myform2").serialize(),
            // data:{"UserID":11, "Name":[{"FirstName":"Truly","LastName":"Zhu"}], "Email":"zhuleipro◎hotmail.com"},
            //data:{name:$("#name").val(),routing_rule:$("#routing_rule").val(),time_profile_id:$("#time_profile_id").val(),lcr_flag:$("#lcr_flag").val(),Carriers1:$("#Carriers1").val(),engress_res_id:$("#engress_res_id").val()},
            success:function(data) {
                //alert(data);
                //window.location.reload();
                if(data == 'codePreg'){
                    jQuery.jGrowlError('The code numbers only');
                }else if(data == "codeNll"){
                    jQuery.jGrowlError('The code cannot be NULL!');
                }else if(data == "codeIsHave"){
                   jQuery.jGrowlError('Code is already in use!'); 
                }else if(data == "PercentPreg"){
                    jQuery.jGrowlError('The percent numbers only');
                }else if(data == "PercentNo100"){
                    
                }else if(data == "no"){
                    jQuery.jGrowlError('Failed');
                }else{
                    //$($(".marginTop9")[3]).append("<option value = '"+data+"'>"+$("#name").val()+"</option>");
                    //$("#name").attr("value","");
                    $("#name1").attr('value',"");
                    $("#product_id").attr('value',"");
                    $(".ui-icon-closethick").click();
                    jGrowl_to_notyfy('Successfully!', {theme: 'jmsg-success'});
                }
                
            }
        });
        return false;
    });
});
function updatetrunks1(elem) {
    $.getJSON('<?php echo $this->webroot?>trunks/ajax_options?filter_id='+$(elem).val()+'&type=egress', function(data) {
     $brother = $(elem).parent().next().find('select'); 
     $brother.empty();
     $.each(data, function(idx, item){
            $('<option value="'+item.resource_id+'">'+item.alias+'</option>')
                    .appendTo($brother);
                   
        }
     );
      updateRate1($brother.get(0));
    }) 
    
    
}


function updateRate1(obj){
        var val = $(obj).val();
        var prefix = $("input[name=digits]").val();
        var data=jQuery.ajaxData('<?php echo $this->webroot?>products/get_rate/' + val + '/' + prefix);
        if(isNaN(data)) data = 0;
        $(obj).parent().next().text(new Number(data).toFixed(5));
}

function removeitem1(elem) {
    if($('#tbl1 tbody tr').length == 1) {
        return;
    }
    $(elem).parent().parent().remove();
}
</script>