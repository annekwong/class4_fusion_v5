<div id="add_rountingplan" style="padding:0px 10px;">
    <div class="widget-body">
        <table class="list table dynamicTable tableTools table-bordered  table-white" >
            <tbody>
            <tr>
                <td class="right"><?php echo __('Route Plan name', true); ?></td>
                <td><input class="input in-text" id="name1" type="text" name="name" /></td>
            </tr>
            </tbody>
            <tfoot>
            <tr>
                <td colspan="2" class="center">
                    <input type="button" id="addroute_strategy" class="input btn btn-primary" value="<?php echo __('submit', true); ?>" />
                </td>
            </tr>
            </tfoot>
        </table>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        var route_strategy_id;
        $('#addroute_strategy').click(function() {
            if ($("#name1").val() == '') {
                jGrowl_to_notyfy('<?php __('The name cannot be null'); ?>', {theme: 'jmsg-error'});
                return false;
            }
            $.ajax({
                url: '<?php echo $this->webroot ?>clients/addroute_strategy',
                type: 'post',
                dataType: 'text',
                data: {name: $('#name1').val()},
                success: function(data) {
                    data = data.replace(/(^\s*)|(\s*$)/g, "");
                    if (data == '0') {
                        jGrowl_to_notyfy('<?php __('The name has exists!'); ?>', {theme: 'jmsg-error'});
                    } else {
                        var route_plan_select_key = $("#dd").find('.foreign_id').val();
                        test4($('#name1').val(),route_plan_select_key);
                        $('#dd').dialog('close');
                        bootbox.dialog('<?php __('Do you want to configure the route plan right now'); ?>?',[{
                            'label': 'some time later',
                        },{
                            'label': 'Configure Now',
                            'class': 'btn btn-primary',
                            'callback': function(){
                                window.open("<?php echo $this->webroot; ?>routestrategys/routes_list/" + data);
                            }
                        }]);
                        $('#name1').val('');
                    }
                }
            });
            return false;
        });
    });

</script>


