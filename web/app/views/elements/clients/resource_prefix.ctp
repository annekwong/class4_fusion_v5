
<div class="buttons">
    <a id="add_resource_prefix" class="btn btn-primary btn-icon glyphicons circle_plus" href="###"><i></i> <?php echo __('Add Resource Prefix', true); ?> </a>
</div>
<div class="clearfix"></div>
<div class="separator bottom"></div>
<table class="list  footable table table-striped tableTools table-bordered  table-white table-primary default footable-loaded"  id="resource_table">
    <thead>
    <tr>
        <th><?php echo __('Product',true);?></th>
        <th> <?php echo __('tech_prefix', true); ?></th>
        <th><?php echo __('Rate Table', true); ?></th>
        <th><?php echo __('Route Plan', true); ?></th>
        <th><?php echo __('action', true); ?></th>
    </tr>
    </thead>
    <tbody>

    <tr id="mb" >
        <td>
            <?php echo $form->input('product_id', array('name' => 'resource[product_id][]', 'empty' => 'By Rate and Route Plan', 'options' => $product_name_arr,'type' => 'select', 'label' => false, 'div' => false, 'class' => 'input in-text in-input product_id','id' =>'product_id')) ?>
            <!--            <a  title="Create New Product" class="add_product" href="#myModal_add_product" data-toggle="modal">-->
            <!--                <i class='icon-plus'></i>-->
            <!--            </a>-->
            <a><i id="addproduct" style="cursor:pointer;" class="icon-plus" onclick="showDiv('pop-div', '750', '200', '<?php echo $this->webroot ?>clients/addproduct');" ></i></a>
        </td>
        <td >
            <input type="text" class="tech_prefix" name="resource[tech_prefix][]" />
        </td>
        <td>
            <?php #echo $xform->input('tech_prefix',Array('name'=>'resource[tech_prefix]','options'=>""))?>
            <select id="ClientRateTableId" class="rate_table_id"  name="resource[rate_table_id][]" >
                <?php foreach ($rate_tables as $key => $value)
                { ?>
                    <option value="<?php echo $key ?>" ><?php echo $value ?></option>
                <?php } ?>
            </select>
            <a><i  style="cursor:pointer;" class="icon-plus" onclick="showDiv('pop-div', 'auto', 'auto', '<?php echo $this->webroot ?>clients/addratetable');" ></i></a>
        </td>
        <td>
            <select class="route_strategy_id" name="resource[route_strategy_id][]">
                <?php foreach ($rout_list as $value)
                { ?>
                    <option value="<?php echo $value[0]['id'] ?>"><?php echo $value[0]['name'] ?></option>
                <?php } ?>
            </select>
            <a><i style="cursor:pointer;" class="icon-plus" onclick="showDiv($(this).closest('td').find('select').attr('row_id'), 'auto', 'auto', '<?php echo $this->webroot ?>clients/addroutingplan');" ></i></a>
        </td>
        <td class="last">
            <a  title="Delete" onclick="$(this).closest('tr').remove();">
                <i class='icon-remove'></i>
            </a>
        </td>

    </tr>
    </tbody>
</table>

<div class="separator bottom"></div>

<div id="myModal_add_product" class="modal hide">
    <div class="modal-header">
        <button data-dismiss="modal" class="close" type="button">&times;</button>
        <h3><?php __('Change Password'); ?></h3>
    </div>
    <div class="modal-body">
        <table class="table table-bordered">
            <tr>
                <td class="align_right"><?php echo __('Tech Prefix')?> </td>
                <td>
                    <input class="input in-text validate[required] tech_prefix" type="text" >
                </td>
            </tr>
            <tr>
                <td class="align_right"><?php echo __('Rate Table')?> </td>
                <td>
                  <?php foreach ($rate_tables as $key => $value)
                  { ?>
                      <option value="<?php echo $key ?>" ><?php echo $value ?></option>
                  <?php } ?>
                </td>
            </tr>
            <tr>
                <td class="align_right"><?php echo __('Route Plan')?> </td>
                <td>
                    <select class="route_plan" >
                        <?php foreach ($rout_list as $value) { ?>
                            <option value="<?php echo $value[0]['id'] ?>"><?php echo $value[0]['name'] ?></option>
                        <?php } ?>
                    </select>
                </td>
            </tr>
        </table>
    </div>
    <div class="modal-footer">
        <input type="button" class="btn btn-primary sub" value="<?php __('Submit'); ?>">
        <a href="javascript:void(0)" data-dismiss="modal" class="btn btn-default close_btn"><?php __('Close'); ?></a>
    </div>

</div>
<script type="text/javascript" src="<?php echo $this->webroot ?>js/jquery.livequery.js"></script>
<script type="text/javascript">


    jQuery(document).ready(
        function() {
            //product 过来
            $.ajaxSettings.async = false;
            $.getJSON('<?php echo $this->webroot ?>clients/ajax_procuct_list', function(data){product_arr = data;});

            $('.product_id').live('change',function(){
                var product_id = $(this).val();
                var tr = $(this).parent().parent();
                if(product_id){
                    tr.find('.route_strategy_id').val(product_arr[product_id]['route_strategy_id']);
                    tr.find('.route_strategy_id').attr('disabled',true);
                    tr.find('.rate_table_id').val(product_arr[product_id]['rate_table_id']);
                    tr.find('.rate_table_id').attr('disabled',true);
                    tr.find('.tech_prefix').val(product_arr[product_id]['tech_prefix']);
                    tr.find('.tech_prefix').attr('disabled',true);
                } else {
                    tr.find('.route_strategy_id').attr('disabled',false);
                    tr.find('.rate_table_id').attr('disabled',false);
                    tr.find('.tech_prefix').attr('disabled',false);
                }


            }).trigger('change');



            var mb = jQuery('#mb').remove();
            jQuery('#add_resource_prefix').click(function() {
                var mb_clone = mb.clone(true);
                var timestamp=Math.round(new Date().getTime()/1000);
                mb_clone.find('.route_strategy_id').attr('row_id',timestamp);
                mb_clone.appendTo('#resource_table tbody');
                return false;
            });
        });

    function test(name) {
        $('#routeplan').livequery(function() {
            var $routeplan = $(this);
            $.getJSON('<?php echo $this->webroot ?>clients/getrouteplan', function(data) {
                $.each(data, function(idx, item) {
                    var option = $("<option value='" + item['id'] + "'>" + item['name'] + "</option>");
                    if ($routeplan.hasClass('clicked')) {
                        if (item['name'] == name) {
                            option.attr('selected', 'selected');
                        }
                    }
                    $routeplan.append(option);
                });
                $routeplan.removeClass('clicked');
            });

        });

    }

    function test2(id) {
        $('#ClientRateTableId').livequery(function() {
            var $ratetable = $(this);
            $.getJSON('<?php echo $this->webroot ?>clients/getratetable', function(data) {
                $.each(data, function(idx, item) {
                    var option = $("<option value='" + item['id'] + "'>" + item['name'] + "</option>");
                    if ($ratetable.hasClass('clicked')) {
                        if (item['id'] == id) {
                            option.attr('selected', 'selected');
                        }
                    }
                    $ratetable.append(option);
                });
                $ratetable.removeClass('clicked');
            })
        });
    }

    function test3(id) {
        $.getJSON('<?php echo $this->webroot ?>clients/getratetable', function(data) {
            $.each(data, function(idx, item) {
                var option = $("<option value='" + item['id'] + "'>" + item['name'] + "</option>");
                if (item['id'] == id) {
                    option.attr('selected', 'selected');
                }
                var $ratetable = $('#ClientRateTableId');
                $ratetable.append(option);
            });
        })
    }

    function test4(name,foreign_id) {
        $(".route_strategy_id[row_id='"+foreign_id+"']").livequery(function() {
            var $routeplan = $(this);
            $routeplan.html('');
            $.getJSON('<?php echo $this->webroot ?>clients/getrouteplan', function(data) {
                $.each(data, function(idx, item) {
                    var option = $("<option value='" + item['id'] + "'>" + item['name'] + "</option>");
                    if (item['name'] == name) {
                        option.attr('selected', 'selected');
                    }
                    $routeplan.append(option);
                });
            });

        });

    }
</script>







