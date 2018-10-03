<style type="text/css">
	.label{ width:100px; text-align:right;}
	.value{ text-align:left !important; }
	.input_width{ width:50px;}
	.select_width{ width:50px;}
</style>

<div class="add_dynamic_route" style="padding:0px 10px;">
    
    <form action="" method="post" id="myform2" name="myform2">
        <input type="hidden" name="dynamic_route_id" id="dynamic_route_id" />
         <div id="editor">
         <table class="form">
                	<tr>
                        <td class="label"><?php echo __('name',true);?>:</td>
                        <td class="value"><input class="input in-text" id="name2" type="text" name="name" value="" /></td>
                        </tr>
                        <tr>
                        <td class="label"><?php echo __('Strategy',true);?>:</td>
                        <td class="value"><select class="select in-select" name="routing_rule">
                    <option selected="selected" value="4"><?php __('Largest ASR')?></option>
                    <option value="5"><?php __('Largest ACD')?></option>
                    <option value="6"><?php __('LCR')?></option>
                </select></td>
                </tr>
                <tr>
                        <td class="label"><?php echo __('Time Profile',true);?>:</td>
                        <td class="value"><select class="select in-select" id="profile" name="profile">

                </select></td>
                </tr>
                </table>
            
</div>
            <h1 style="text-align:left;margin-bottom:5px;">
              <button id="addtrunk" class="input in-submit" style="width:80px;"><?php echo __('Add Trunk',true);?></button>
            </h1>
            
            <table id="list" class="list list-form form">
            <tbody>
                  <tr>
                    <td><select class="select in-select" onchange="updatetrunks(this)" name="carriers[]"></select>
                    </td>
                    <td>
                    <select class="select in-select" name="trunks[]"></select>
               </td>
               </tr>
               </tbody>
            </table>
            <div id="form_footer">
                <button id="sub" class="input in-submit"><?php echo __('submit',true);?></button>
              </div>
           
        </div>
    </form>
</div>

<script type="text/javascript">
$(document).ready(function() {
    getprofile();
    $('#showadd').toggle(function() {
        $('#editor').show();
        $('#showadd').text('Cancel');
    }, function() {
        $('#editor').hide();
        $('#editbtn').hide();
        $('#sub').show();
        $('#showadd').text('Add');
        $('#editor tbody').empty();
    });

    $li = $('#list tbody tr').remove();
    
    

    $.getJSON('<?php echo $this->webroot ?>clients/get_carriers', function(data2) {
        $li.find('select[name=carriers[]]').append('<option value="0"></option>');
        $.each(data2, function(idx, item){
            var temp = $('<option value="'+item.id+'">'+item.name+'</option>');
            $li.find('select[name=carriers[]]').append(temp);
        });
    }); 

    
    $('#addtrunk').click(function() {
		$('#pop-div-dynamic').css({'height':'auto'});
        $li.clone(true).appendTo('#list tbody');
        //updatacarriers();
        return false;
    });
    /*
    $('#sub').click(function() {
        $.ajax({
            url:"<?php echo $this->webroot ?>clients/adddynamictable_sub",
            type:'post',
            dataType:'text',
            data:$('#myform2').serialize(),
            success:function(data) {
                data = data.replace(/(^\s*)|(\s*$)/g,"");
                window.opener.dynamicback(data);
                window.opener=null;      
                window.open('','_self');      
                window.close();
            }
        });
        return false;
    });
    */
	
	
	$('#sub').click(function() {
		if($("#name2").val()==''){
				alert('The field name cannot be NULL.');
				return false;
		}
		$.ajax({
			url:"<?php echo $this->webroot; ?>clients/adddynamictable_sub",
			type:"POST",
			dataType:"text",
			data:$('#myform2').serialize(),
			success:function(data) {
				data = data.replace(/(^\s*)|(\s*$)/g,"");
				test5(data);
				$("#pop-div-dynamic").hide();
				$("#pop-clarity-dynamic").hide();
				$("#pop-div").show();
			}
		});
		return false;
	});
	$('#pop-close-dynamic').click(function(){
		$("#pop-div-dynamic").hide();
		$("#pop-clarity-dynamic").hide();
		$("#pop-div").show();
	});
	
});


function getprofile() {
    $.getJSON('<?php echo $this->webroot ?>clients/get_profile', function(data) {
        $.each(data, function(idx, item){
            $('<option value="'+item.id+'">'+item.name+'</option>').appendTo('#profile');
        });
    });
}

function updatetrunks(elem) {
    $.getJSON('<?php echo $this->webroot?>trunks/ajax_options?filter_id='+$(elem).val()+'&type=egress', function(data) {
     $brother = $(elem).parent().parent().find('select[name=trunks[]]');
     $brother.empty();
     $.each(data, function(idx, item){
            $('<option value="'+item.resource_id+'">'+item.alias+'</option>')
                    .appendTo($brother);
        });
    })    
}

function updatacarriers() {
    $.getJSON('<?php echo $this->webroot ?>clients/get_carriers', function(data) {
        $.each(data, function(idx, item){
            $('<option value="'+item.id+'">'+item.name+'</option>').appendTo('select[name=carriers[]]');
        });
    });
}




/*
window.onbeforeunload = function() {
    window.opener.dynamicback($('#dynamic_route_id').val());
    window.opener=null;      
    window.open('','_self');      
    window.close();
}*/
</script>
