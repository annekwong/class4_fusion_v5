
<!--<style type="text/css">-->
<!--    .label{ width:100px; text-align:right;}-->
<!--    .value{ text-align:left !important; }-->
<!--    .input_width{ width:50px;}-->
<!--    .select_width{ width:50px;}-->
<!--</style>-->

<div id="add_rountingplan">
<!--    <div style="height:30px; line-height:25px; float:left;">-->
<!--        <label>--><?php //echo __('name', true); ?><!--:</label><input class="input in-text" id="name1" type="text" name="name" />-->
<!--        <input type="button" id="addroute_strategy" class="input btn btn-primary" value="--><?php //echo __('submit', true); ?><!--" />-->
<!--        <input type="button" id="addroute_route_record" style="width:auto; display:none;" class="input in-submit" value="Create New Route Table" />-->
<!--    </div>-->
<!--    <div id="editor1" style="clear:both;">-->


        <form action="post" name="myform" id="myform">
            <input type="hidden" name="route_strategy_id" id="route_strategy_id" />
            <table id="resource_table1" class="list  footable table table-striped tableTools table-bordered  table-white table-primary default footable-loaded">
                <thead>
                    <tr>
                        <th><?php echo __('name', true); ?></th>
                        <th><?php echo __('tech_prefix', true); ?></th>
                        <th><?php echo __('Rate Table', true); ?></th>
                        <th><?php echo __('Route Plan', true); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><input type="text" maxlength="16" style="width:80px;" class="input in-text validate[required,custom[onlyLetterNumberLineSpace]]" id="product_name" name="product_name"  /></td>
                        <td><input type="text" maxlength="16" style="width:80px;" class="input in-text validate[required,custom[integer]]" id="tech_prefix" name="tech_prefix"  /></td>
                        <td>
                            <select id="rate_table_id" class="rate_table_id"  name="rate_table_id" >
                                <?php foreach ($rate_table as $value)
                                { ?>
                                    <option value="<?php echo $value[0]['id'] ?>" ><?php echo $value[0]['name'] ?></option>
                                <?php } ?>
                            </select>
                        </td>
                        <td>
                            <select id="route_strategy_id" class="route_strategy_id" name="route_strategy_id">
                                <?php foreach ($rout_list as $value)
                                { ?>
                                    <option value="<?php echo $value[0]['id'] ?>"><?php echo $value[0]['name'] ?></option>
                                <?php } ?>
                            </select>
                        </td>

                    <tr>
                </tbody>
            </table>
            <div>
                <button id="ssave" onclick="return false;" class="btn-primary" style="width:150px;"><?php echo __('Save and return', true); ?></button>
            </div>

        </form>
<!--    </div>-->
</div>

<script>
    $(function(){
        $('#ssave').click(function(){
            var validate_name = $('#product_name').validationEngine('validate');
            var validate_prefix = $('#tech_prefix').validationEngine('validate');
            if(validate_name || validate_prefix) return false;

            var product_name = $('#add_rountingplan #product_name').val();
            var tech_prefix = $('#add_rountingplan #tech_prefix').val();
            var rate_table_id = $('#add_rountingplan #rate_table_id').find('option:selected').attr('value');
            var route_strategy_id = $('#add_rountingplan #route_strategy_id').find('option:selected').attr('value');

            $.ajaxSettings.async = false;

            var is_false = false;
            $.ajax({
                url:'<?php echo $this->webroot ?>clients/ajax_product_add',// 跳转到 action
                data:{product_name:product_name,tech_prefix:tech_prefix,rate_table_id:rate_table_id,route_strategy_id:route_strategy_id},
                type:'post',
                cache:false,
                dataType:'json',
                success:function(data) {
                    if(!data){
                        showMessages_new([{'field':'','code':'101','msg':'The product name is unique!'}]);
                        is_false = true;
                    }

                }

            });

            if(is_false) return false;



            $.getJSON('<?php echo $this->webroot ?>clients/ajax_procuct_list', function(data){product_arr = data;});
            $('#product_id').find('option:gt(0)').remove();

            $.each(product_arr,function(i,v){
                $("<option value='"+i+"'>"+v['product_name']+"</option>").appendTo("#product_id");
            });
            showMessages_new([{'field':'','code':'201','msg':'Success'}]);
            $('.ui-dialog-titlebar-close').click();

            return false;
        })
    })
</script>
