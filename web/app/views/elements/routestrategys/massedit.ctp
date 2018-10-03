
<div id="massedit">
   <?php  if ($_SESSION['role_menu']['Routing']['routestrategys']['model_w']) {?> <h1><?php __('Mass Edit')?> »</h1><?php }?>
    <form action="###" name="myform" id="myform" method="post">
    <div id="editor">
        
        <ul>
            <li>
            <label>
                <?php echo __('lrn',true);?>
                    <input type="checkbox" name="lnp" />
            </label>
            </li>
            <li>
            <label>
                <?php echo __('Block LRN',true);?>
                    <input type="checkbox" name="block" />
            </label>
            </li>
            <li>
            <label>
                <?php echo __('DNIS Only',true);?>
                    <input type="checkbox" name="dnis" />
            </label>
			</li>
            <li>
            <label><?php echo __('Route Type',true);?>
                <select name="routetype">
                        <option value="1">Dynamic Routing</option>
                        <option value="2">Static Routing</option>
                        <option value="3">Static Routing After Dynamic routing</option>
                    </select>
            </label>
            </li>
            <li>
            <label><?php echo __('Static Routing',true);?>
                <select name="static">

                </select>
            </label>
            </li>
            <li>
            <label><?php echo __('Dynamic Routing',true);?>
                <select name="dynamic">

                </select>
            </label>
            </li>
            
        </ul>
        <?php  if ($_SESSION['role_menu']['Routing']['routestrategys']['model_w']) {?>
        <div style="clear:both; height:10px;"></div>
        <div id="form_footer">
            <label><button id="masseditbtn" class="input in-button"><?php echo __('submit',true);?></button></label>
        </div>
        <?php }?>
        
    </div>
    </form>
</div>

<script type="text/javascript">

jQuery(function($){
    $('#editor').hide();
    $('#massedit h1').click(function() {
        $('#editor').slideToggle();
        getSelectStatic();
        getSelectDynamic();
    });
    //getSelectStatic();
    //getSelectDynamic();
    $('#editor select[name=routetype]').change(function() {
        if($(this).val() == '2') {
            $('#editor select[name=static]').parent().show();
            $('#editor select[name=dynamic]').parent().hide();
        } else if ($(this).val() == '3') {
            $('#editor select[name=static]').parent().show();
            $('#editor select[name=dynamic]').parent().show();
        } else if ($(this).val() == '1') {
            $('#editor select[name=static]').parent().hide();
            $('#editor select[name=dynamic]').parent().show();
        }
    });
    $('#editor select[name=routetype]').change();
    $('#masseditbtn').click(function() {
        var arr = new Array();
        $('.list tbody input[type=checkbox]').each(function() {
            if($(this).attr('checked')) {
                arr.push($(this).attr('value'));
            }
        }); 
        if(arr.length == 0) {
            return false;
        }
        $.ajax({
            url:'<?php echo $this->webroot; ?>routestrategys/massedit',
            type:'POST',
            dataType:'text',
            data:$('#myform').serialize()+"&idx="+arr.join(","),
            success:function(data) {
                 window.location.reload();
            }
        });
        return false;
    });
});

function getSelectStatic() {
    $.getJSON('<?php echo $this->webroot ?>clients/getstaticroute', function(data) {
        var $statictables = $('select[name=static]');
        $statictables.each(function(index) {
            var $statictable = $(this);
            $.each(data, function(idx, item) {
                var option = "<option value='" + item['id'] + "'>" + item['name'] + "</option>";
                $statictable.append(option);
            });
        })
    })    
}

function getSelectDynamic() {
    $.getJSON('<?php echo $this->webroot ?>clients/getdynamicroute', function(data) {
        var $dynamictables = $('select[name=dynamic]');
        $dynamictables.parent().find('img').remove();
        $dynamictables.each(function(index) {
            var $dynamictable = $(this);
            $dynamictable.empty();
            $.each(data, function(idx, item) {
                var option = "<option value='" + item['id'] + "'>" + item['name'] + "</option>";
                $dynamictable.append(option);
            });
        })
    })    
}

</script>