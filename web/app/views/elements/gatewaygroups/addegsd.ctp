<div id="addegsd"> 
    <a id="addRouting" href="###">
        <img src="<?php echo $this->webroot; ?>images/add.png" />
        <?php echo __('Add This Egress T0 Routing Table',true);?>
    </a>
    <table id="routingtable" class="list list-form">
        <thead>
            <tr>
                <td></td>
                <td><?php echo __('Dynamic Routing',true);?></td>
                <td><?php echo __('Static Routing',true);?></td>
                <td><?php echo __('Prefix',true);?></td>
                <td><?php echo __('action',true);?></td>
            </tr>
        </thead>
        <tbody>
            <tr id='workclone'>
                <td>
                    <select name="data[Gatewaygroup][routing_type][]" class="type">
                        <option value="1"><?php __('Dynamic Routing')?></option>
                        <option value="2"><?php __('Static Routing')?></option>
                        <option value="3"><?php __('Static Routing And Dynamic Routing')?></option>
                    </select>
                </td>
                <td>
                    <select name="data[Gatewaygroup][add_dynamic_id][]" class="dynamic">
                        <option value="0"></option>
                        <?php foreach($dynamiclist as $dynamic): ?>
                        <option value="<?php echo $dynamic[0]['dynamic_route_id'] ?>"><?php echo $dynamic[0]['name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
                <td>
                    <select name="data[Gatewaygroup][add_static_id][]" class="static">
                        <option value="0"></option>
                        <?php foreach($staticlist as $static): ?>
                        <option value="<?php echo $static[0]['product_id'] ?>"><?php echo $static[0]['name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
                <td>
                    <select name="data[Gatewaygroup][prefix][]" class="prefix">
                        <option></option>                        
                    </select>
                    <input type="text" name="data[Gatewaygroup][prefix_new][]" style="display:none;" />
                </td>
                <td>
                    <a href="###" class="delete">
                        <i class='icon-remove'></i>
                    </a>
                </td>
            </tr>
        </tbody>
    </table>
</div>

<script type="text/javascript">
$(function() {

    var $tr = $('#workclone').remove();

    $('select.static', $tr).hide();

    $('select.static', $tr).change(function() {
        var $this = $(this);
        $.ajax({
            url:'<?php echo $this->webroot; ?>prresource/gatewaygroups/get_prefix/' + $(this).val(),
            type:'GET',
            dataType:'json',
            success:function(data) {
                var $prefix = $this.parent().parent().find('select.prefix');
                $prefix.empty();
                $prefix.append('<option></option>');
                $prefix.append('<option value="new">New Prefix</option>');
                $.each(data, function(index, item) {
                    $prefix.append('<option>'+item[0]['digits']+'</option>');
                });
            }
        });
    });

    $('select.prefix', $tr).change(function() {
        if($(this).val() == 'new') {
            $(this).siblings('input').show();
        } else {
            $(this).siblings('input').hide();
        }
    });

  

    $('select.prefix', $tr).hide();

    $('select.type', $tr).change(function() {
        var $this = $(this);
        if($this.val() == '1') {
            $(this).parent().parent().find('select.dynamic').show();
            $(this).parent().parent().find('select.static').hide();
            $(this).parent().parent().find('select.prefix').hide().siblings('input').hide();
        } else if($this.val() == '2') {
            $(this).parent().parent().find('select.dynamic').hide();
            $(this).parent().parent().find('select.static').show();
            $(this).parent().parent().find('select.prefix').show();
        } else if($this.val() == '3') {
            $(this).parent().parent().find('select.dynamic').show();
            $(this).parent().parent().find('select.static').show();
            $(this).parent().parent().find('select.prefix').show();
        }
    });

    $('a.delete', $tr).click(function() {
        $(this).parent().parent().remove();
    });


    $('#addRouting').bind('click', function() {
        $tr.clone(true).appendTo('#routingtable tbody');
        return false;
    });

});
</script>