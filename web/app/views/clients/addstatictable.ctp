<?php echo $this->element("selectheader")?>
<style type="text/css">
#editor {
    margin-top:10px;
    overflow:hidden;
}
#editor h1 {
    float:right;
}
#editor ol {
    margin:10px;
}
#editor ol li{
    list-style-type:decimal;
    margin-left:30px;
    float:left;
}
.container table td {
    padding:2px;
}
.in-submit {
    padding:0;
    margin:0;
    margin-left:10px;
}
</style>

<div id="title"><h1><?php __('Static Route Table')?></h1></div>
<div class="container">
    <label><?php echo __('name',true);?>:<input class="input in-text" id="name" type="text" name="name" /></label><button id="addnamebtn" class="input in-submit"><?php echo __('Add Name',true);?></button>
    <button id="showadd" class="input in-submit"><?php echo __('add',true);?></button>
    <form action="" method="post" id="myform" name="myform">
        <input type="hidden" name="product_id" id="product_id" />
        <table id="tbl" class="list" border="1">
            <thead>
                <tr>
                    <td><?php echo __('Prefix',true);?></td>
                    <td><?php echo __('Strategy',true);?></td>
                    <td><?php echo __('Time Profile',true);?></td>
                    <td><?php echo __('Trunks',true);?></td>
                    <td><?php echo __('action',true);?></td>
                </tr>
            </thead> 
            <tbody>
                
            </tbody>
        </table>
        <div id="editor">
            <label>
                <?php echo __('Prefix',true);?>:<input type="text" class="input in-text" name="prefix" />
            </label>
            <label>
                <?php echo __('strategy',true);?>:
                <select class="select in-select" name="strategy">
                    <option value="1"><?php __('Top-Down')?></option>
                    <option value="0"><?php __('By Percentage')?> </option>
                    <option value="2"><?php __('Round Robin')?></option>
                </select>
            </label>
            <label>
                <?php echo __('time profile',true);?>:
                <select class="select in-select" id="profile" name="profile">

                </select>
            </label>
            <h1><button id="addtrunk" class="input in-submit"><?php __('Add Trunk')?></button></h1>
            <ol>
                <li>
                    <select class="select in-select" onchange="updatetrunks(this)" name="carriers[]"></select>
                    <select class="select in-select" name="trunks[]"></select>
                    <input type="text" class="input in-text" name="percents[]" />
                </li>
            </ol>
            <a style="display:block;clear:both;text-align:center;">
                <input id="sub" class="input in-submit" type="button" value="<?php echo __('submit',true);?>" />
                <input id="editbtn" class="input in-submit" type="button" value="<?php echo __('edit',true);?>" />
            </a>
        </div>
    </form>
</div>

<script type="text/javascript">
$(document).ready(function() {
    $('#myform').hide();
    $('#showadd').hide();
    $('#editbtn').hide();
    $('#editor select[name=strategy]').change(function() {
        if($(this).val() == 0) {
            $('#editor input[name=percents[]]').show();
        } else {
            $('#editor input[name=percents[]]').hide();
        }
    });
    $('#addnamebtn').click(function() {
        $.ajax({
            url:'<?php echo $this->webroot; ?>clients/add_statictable_name',
            type:'post',
            dataType:'text',
            data:{name:$('#name').val()},
            success:function(data) {
                data = data.replace(/(^\s*)|(\s*$)/g,"");
                if(data == '0') {
                    alert('The name has exists!');
                } else {
                    $('#product_id').val(data);
                    $('#name').attr('readonly','readonly');
                    $('#addnamebtn').remove();
                    $('#myform').show();
                    $('#editor').hide();
                    $('#showadd').show();
                    getprofile();
                }   
            }
        });
    });
    
    $('#showadd').toggle(function() {
        $('#editor').show();
        $('#showadd').text('Cancel');
    }, function() {
        $('#editor').hide();
        $('#editbtn').hide();
        $('#sub').show();
        $('#showadd').text('Add');
        $('#editor input[name=prefix]').val('');
        $('#editor ol').empty();
    });

    $li = $('#editor ol li').remove();
    
    

    $.getJSON('<?php echo $this->webroot ?>clients/get_carriers', function(data2) {
        $li.find('select[name=carriers[]]').append('<option value="0"></option>');
        $.each(data2, function(idx, item){
            var temp = $('<option value="'+item.id+'">'+item.name+'</option>');
            $li.find('select[name=carriers[]]').append(temp);
        });
    }); 

    
    $('#addtrunk').click(function() {
        $li.clone(true).appendTo('#editor ol');
        if($('#editor select[name=strategy]').val() != 0) {
            $('#editor input[name=percents[]]').hide();
        }
        //updatacarriers();
        return false;
    });
    
    $('#sub').click(function() {
        $.ajax({
            url:"<?php echo $this->webroot ?>clients/addstatictable_sub",
            type:'post',
            dataType:'text',
            data:$('#myform').serialize(),
            success:function(data) {
                data = data.replace(/(^\s*)|(\s*$)/g,"");
                if(data == '1') {
                    $('#editor input[name=prefix]').val('');
                    $('#editor ol').empty();
                    $('#editor').hide();
                    $('#showadd').click();
                    getall();
                }
            }
        });
        return false;
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
     $brother = $(elem).siblings('select[name=trunks[]]');
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

function del(id) {
     $.ajax({
            url:'<?php echo $this->webroot; ?>clients/delete_static_table/' + id,
            type:'get',
            dataType:'text',
            success:function(data) {
                data = data.replace(/(^\s*)|(\s*$)/g,"");
                if(data == '1') {
                    getall();
                }
            }
     });
}


function getall() {
    $.getJSON('<?php echo $this->webroot ?>clients/get_name_statictable/' + $('#product_id').val(), function(data) {
        $('#tbl tr:gt(0)').remove();
        $.each(data, function(idx, item) {
            if(item.strategy == '0') {
                item.strategy = "By Percentage";
            } else if(item.strategy == '1') {
                item.strategy = "Top-Down";
            } else if(item.strategy == '2') {
                item.strategy = "Round Robin";
            }
            $('<tr />')
                .append('<td>'+item.prefix+'</td>')
                .append('<td>'+item.strategy+'</td>')
                .append('<td>'+item.time_profile+'</td>')
                .append('<td>'+item.trunks.replace(/[\{\}]/g, '')+'</td>')
                .append('<td><a href="#" onclick="edit('+item.id+')"><i class="icon-edit"></i></a><a href="#" onclick="del('+item.id+')"><i class='icon-remove'></i></a></td>')
                .appendTo('#tbl tbody');
        });
    });
}


function edit(id) {
    //TODO 修改
    $('#showadd').click();
    $('#editor ol').empty();
    $('#sub').hide();
    $('#editbtn').show();
    $.getJSON('<?php echo $this->webroot ?>clients/get_static_item/'+id, function(data) {
        $('#editor input[name=prefix]').val(data.item.digits);
        var strategy = "#editor select[name=strategy] option[value="+data.item.strategy+"]";
        $(strategy).attr('selected','selected');
        var profile = "#editor select[name=profile] option[value="+data.item.time_profile+"]";
        $(profile).attr('selected','selected');
        var len = data.len;
        var arr = new Array();
        for(var i=0; i<len; i++) {
            var li = $li.clone(true).appendTo('#editor ol');
            $('select[name=carriers[]] option[value='+data.resource[i].client_id+']', li).attr('selected','selected').change();
            $('select[name=trunks[]] option[value='+data.resource[i].resource_id+']', li).attr('selected','selected');
            $('input[name=percents[]]', li).val(data.resource[i].by_percentage);
            arr.push(li);
        } 
    });
     $('#editbtn').click(function() {
        $.ajax({
            url:"<?php echo $this->webroot ?>clients/addstatictable_edit",
            type:'post',
            dataType:'text',
            data:$('#myform').serialize()+"&id="+id,
            success:function(data) {
                    data = data.replace(/(^\s*)|(\s*$)/g,"");
                    if(data == '1') {
                        getall();
                    }
                }
          });
     });
}







window.onbeforeunload = function() {
    window.opener.staticback($('#product_id').val());
    window.opener=null;      
    window.open('','_self');      
    window.close();
}
</script>

<?php echo $this->element("selectfooter")?>