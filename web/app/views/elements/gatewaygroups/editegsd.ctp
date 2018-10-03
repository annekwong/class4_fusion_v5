<style type="text/css">
#switch_route {
  background-color: #CCCCCC;
  border-radius: 4px 4px 4px 4px;
  clear: both;
  font-size: 14px;
  font-weight: bold;
  height: 30px;
  line-height: 30px;
  padding-left: 15px;
}
#showbox {
  background: none repeat scroll 0 0 #FFFFFF;
  border: 10px solid #7EAC00;
  height: 200px;
  overflow: hidden;
  width: 300px;
  position: fixed;
  display:none;
}
#showbox h1 {
  background: none repeat scroll 0 0 #CCCCCC;
  font-weight: bold;
  line-height: 30px;
  margin: 5px;
  padding-left: 10px;
  text-align: left;
  overflow:hidden;
}
#showbox span {
  cursor: pointer;
  float: right;
  padding-right: 10px;
}
#showbox ul {
  overflow-y: auto;
  padding: 10px;
}
#showbox ul li {
  float:left;
  width:80px;
}
#showbox p {
  text-align:center;
}
</style>

<div>
    <div id="switch_route">
        <?php __('Add Trunk To Route')?>
        <img src="<?php echo $this->webroot; ?>images/bullet_toggle_plus.png">
    </div>
    <table id="route_table" class="list" style="display:none;">
        <thead>
            <tr>
                <td>
                    <a id="add_route" href="###">
                        <img src="/Class4/images/add.png" style="width:16px;height:16px;" />
                    </a>
                </td>
                <td><?php __('Dynamic Routing')?></td>
                <td><?php __('Static Routing')?></td>
                <td><?php __('Action')?></td>
            </tr>
        </thead>

        <tbody>
            <tr>
                <td>
                    <select class="route_type" style="width:220px;" name="route_type[]">
                        <option value="0"><?php __('Dynamic Routing')?></option>
                        <option value="1"><?php __('Static Routing')?></option>
                        <option value="2" selected><?php __('Dynamic Routing And Static Routing')?></option>
                    </select>
                </td>
                <td>
                    <select name="dynamic[]">
                        <?php foreach($dynamiclist as $dynamic): ?>
                        <option value="<?php echo $dynamic[0]['dynamic_route_id']; ?>"><?php echo $dynamic[0]['name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
                <td>
                    <select name="static[]">
                        <?php foreach($staticlist as $static): ?>
                        <option value="<?php echo $static[0]['product_id']; ?>"><?php echo $static[0]['name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <input type="text" class="showprefix" readonly="readonly" />
                    <input type="hidden" name="prefix[]" />
                </td>
                <td>
                    <a class="delete" href="###">
                        <i class='icon-remove'></i>
                    </a>
                </td>
            </tr>
            <?php foreach($relation_routes as $relation_route): ?>
            <tr>
                <td>
                    <select class="route_type" style="width:220px;" name="route_type[]">
                        <option value="0" <?php if($relation_route['type'] == '0') echo 'selected'; ?>><?php __('Dynamic Routing')?></option>
                        <option value="1" <?php if($relation_route['type'] == '1') echo 'selected'; ?>><?php __('Static Routing')?></option>
                        <option value="2" <?php if($relation_route['type'] == '2') echo 'selected'; ?>><?php __('Dynamic Routing And Static Routing')?></option>
                    </select>
                </td>
                <td>
                    <select name="dynamic[]">
                        <?php foreach($dynamiclist as $dynamic): ?>
                        <option value="<?php echo $dynamic[0]['dynamic_route_id']; ?>" <?php if($relation_route['dynamic'] == $dynamic[0]['dynamic_route_id']) echo 'selected'; ?>><?php echo $dynamic[0]['name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
                <td>
                    <select name="static[]">
                        <?php foreach($staticlist as $static): ?>
                        <option value="<?php echo $static[0]['product_id']; ?>" <?php if($relation_route['static'] == $static[0]['product_id']) echo 'selected'; ?>><?php echo $static[0]['name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <input type="text" class="showprefix" value="<?php echo strlen($relation_route['prefix']) > 20 ? substr($relation_route['prefix'], 0, 20) . "..." : $relation_route['prefix']; ?>" readonly="readonly" />
                    <input type="hidden" name="prefix[]" value="<?php echo $relation_route['prefix']; ?>" />
                </td>
                <td>
                    <a class="delete" href="###">
                        <i class='icon-remove'></i>
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<div id="showbox">
    <h1 id="drag">
        <span><img src="<?php echo $this->webroot; ?>images/showbox_close.png" /></span>
    </h1>
    <p>
        <input type="text" style="width:80px;" />&nbsp;<input type="button" value="Add New" />
    </p>
    <ul>
    </ul>
</div>
<script type="text/javascript" src="<?php echo $this->webroot; ?>js/jquery.center.js"></script>
<script type="text/javascript" src="<?php echo $this->webroot; ?>js/jquery.easydrag.js"></script>
<script type="text/javascript">
$(function() {
    var $item = $('#route_table tbody tr:first').remove();

    $('#add_route').click(function() {
        $item.clone(true).appendTo('#route_table tbody');
    });

    $('.delete').live('click', function() {
        $(this).parent().parent().remove();
    });

    $('.showprefix').live('click', function() {
        var $this = $(this);
        var static_id = $this.prev().val();
        var $showbox = $("#showbox");
        $showbox.center().easydrag();
        $showbox.setHandler('drag');
        var $real_input = $this.siblings("input:hidden");
        var selected_prefix = $real_input.val().split(',');

        $.ajax({
            url : "<?php echo $this->webroot;?>prresource/gatewaygroups/show_prefixs/" + static_id,
            type : 'GET',
            dataType : 'json',
            success : function(data) {
                var $showbox_content = $("ul", $showbox);
                $showbox_content.empty();
                if(data != "") {
                    $.each(data, function(key, item) {
                        temp = "";
                        if($.inArray(item, selected_prefix) != -1)
                            temp = "checked"
                        $("<li />").text(item).prepend("<input type='checkbox' value='" + item + "'" + temp + " />").appendTo($showbox_content);
                    });
                }
            }
        });

        $('input:button', $showbox).click(function() {
            var new_prefix = $('input:text', $showbox).val();
            $.ajax({
                url : "<?php echo $this->webroot;?>prresource/gatewaygroups/add_prefix",
                type : 'POST',
                dataType : 'text',
                data : {prefix:new_prefix, static_id:static_id},
                success : function(data) {
                    if(data == '1')
                        $("<li />").text(new_prefix).prepend("<input type='checkbox' value='" + new_prefix + "' checked />").appendTo("ul", $showbox);
                    else 
                        jGrowl_to_notyfy('<?php echo __('The prefix already exists!')?>',{theme:'jmsg-error'});	
                }
            });
        });

        $('#drag span').click(function() {
            var seletedItems = new Array();
            $('input:checked', $showbox).each(function(index, item) { 
                seletedItems.push($(this).val());
            });
            var prefix_str = seletedItems.join(',');
            $this.val(cutString(prefix_str, 20));
            $real_input.val(prefix_str);
            $(this).unbind('click');
            $('input:button', $showbox).unbind('click');
            $('input:text', $showbox).val('');
            $showbox.hide();
        });
    });

    $('.route_type').live('change', function() {
        var $this = $(this);
        var type = $this.val();
        var $tr = $this.parent().parent();
        switch(type) {
            case '0':
                $('td:nth-child(3)', $tr).children().hide(); 
                $('td:nth-child(2)', $tr).children().show();
                break;
            case '1':
                $('td:nth-child(3)', $tr).children().show();
                $('td:nth-child(2)', $tr).children().hide();
                break;
            case '2':
                $('td:nth-child(3)', $tr).children().show();
                $('td:nth-child(2)', $tr).children().show();
                break;
        }
    });
    
    $('.route_type').change();

    $("#switch_route").toggle(function(){
        $("#route_table").show();
        $("img", $(this)).attr('src', "<?php echo $this->webroot; ?>images/bullet_toggle_minus.png");
    },function(){
        $("#route_table").hide();
        $('#route_table tbody').empty();
        $("img", $(this)).attr('src', "<?php echo $this->webroot; ?>images/bullet_toggle_plus.png");
    });

});
</script>