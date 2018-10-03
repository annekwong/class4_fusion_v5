
<div id="title">
    <h1><?php __('Tools')?>&gt;&gt;<?php echo __('Vendor Test',true);?></h1>
</div>

<div class="container">
    <form action="###" method="post" name="myform" id="myform">
        
        <div class="block">
        	<ul><li><span class="block_label"><?php echo __('code_name',true);?>:</span><span class="block_value"><select name="code_name" class="input in-select select" style="width:300px;">
                    <?php foreach($codenames as $codename): ?>
                    <option value="<?php echo $codename['code_name']; ?>"><?php echo $codename['code_name']; ?></option>
                    <?php endforeach; ?>
                </select></span></li>
            <li><span class="block_label"></span><span class="block_value"></span></li>
            <li><span class="block_label"></span><span class="block_value"></span></li>
            </ul>
            
        </div>
		
        <div style="float:left;">
           <img id="addnumber" style="cursor:pointer;" src="<?php echo $this->webroot ?>images/add.png" />
        </div>
       <table class="list">
          <tr class="numberblock">
            <td style="width:10%;"><?php echo __('DEST Number',true);?>:
            </td>
            <td class="value" style="width:20%;">
           
            <input type="text" name="numbers[]" class="input in-text in-input"   style="float:left;"/>
            <img class="searchnumber" src="<?php echo $this->webroot ?>images/search-small.png" style="cursor:pointer; float:left;" />
            </td>
            <td style="width:10%;"><?php echo __('SRC Numbers',true);?>
            </td>
            <td class="value"style="width:20%;"><input type="text" name="sources[]" class="input in-text in-input" />
            </td>
            <td style="width:10%;"><?php echo __('Call Time',true);?></td>
            <td class="value"style="width:20%;"><input type="text" name="times[]" class="input in-text in-input" value="60" />s</td>
            <td style="width:10%;">
                
                <img class="deletenumber" src="<?php echo $this->webroot ?>images/delete.png" style="cursor:pointer;" /></td>
          </tr>
        </table>
    
    <!--
        <div class="block">
            <label><span><img id="addnumber" style="cursor:pointer;" src="<?php echo $this->webroot ?>images/add.png" /></span></label>
            <p class="numberblock">
                Test Numbers&nbsp;:&nbsp;<input type="text" name="numbers[]" class="input in-text in-input" />
                <img class="searchnumber" src="<?php echo $this->webroot ?>images/search-small.png" style="cursor:pointer;" />
                Source Numbers&nbsp;:&nbsp;<input type="text" name="sources[]" class="input in-text in-input" />
                <?php echo __('Call Time',true);?>&nbsp;:&nbsp;<input type="text" name="times[]" class="input in-text in-input" value="5" />
                
                <img class="deletenumber" src="<?php echo $this->webroot ?>images/delete.png" style="cursor:pointer;" />
                
            </p>
            <br class="clear" />
        </div>
        -->

        <div id="form_footer">
            <p>
                <input type="submit" value="<?php echo __('add',true);?>" class="input in-submit" />
                &nbsp;
                <input type="button" id="back" value="<?php echo __('back',true);?>" />
            </p>
        </div>
    </form>
</div>

<script type="text/javascript">

Array.prototype.inArray = function(value) {
    var i;
    for (i = 0;i < this.length; i++) {
        var reg = new RegExp('^' + this[i]);
        if(reg.test(value)) {
            return true;
        }
    }
    return false;
}

$(function() {
    

    $('#addnumber').click(function() {
        var $newinput = $('.list tr.numberblock:last').clone().insertAfter('.list tr.numberblock:last');
        $newinput.find('input').val('');
        $newinput.find('input[name=times[]]').val('60');
    });
    
    $('#back').click(function() {
        window.location = '<?php echo $this->webroot ?>vendortests';
    });
    
    $('.numberblock img.searchnumber').live('click', function() {
        if($('select[name=code_name]').val() == null) {
            alert('Please select the `code name`!');
            return false;
        }
        $(this).prev().addClass('clicked');
        window.open('<?php echo $this->webroot?>vendortests/clientcdr/<?php echo $rate_table_id; ?>/' + $('select[name=code_name]').val(), 'clientcdr', 
        'height=800,width=1000,top=0,left=0,toolbar=no,menubar=no,scrollbars=no, resizable=no,location=no, status=no');
    });
    
    $('.list tr.numberblock img.deletenumber').live('click', function() {
        if($('.list tr.numberblock').length > 1) {
			
            $(this).parent().parent().remove();
        }
    });
    
    $('#myform').submit(function() {
        var codes = new Array();
        $.ajax({
            url:'<?php echo $this->webroot?>vendortests/get_code',
            type:'POST',
            async:false,
            data:{'rate_table_id':'<?php echo $rate_table_id; ?>', 'code_name':$('select[name=code_name]').val()},
            dataType:'json',
            success:function(data) {
                $.each(data, function(index,value){
                    codes.push(value.code);
                });

                var flag = true;
                
                $('input[name=numbers[]]').each(function(index) {
                    if(!codes.inArray($(this).val())) {
                        flag = false;
                    }
                });

                if(flag) {
                    $('#myform').unbind('submit');
                    $('#myform').submit();
                } else {
                    alert("Error code!");
                    return false;
                }
            }
        });
        return false;
    });
});

function fillinput(text) {
   $('input[name=numbers[]]').each(function(index) {
       if($(this).hasClass('clicked')) {
           $(this).val(text);
           $(this).removeClass('clicked');
       }
    });
}
</script>