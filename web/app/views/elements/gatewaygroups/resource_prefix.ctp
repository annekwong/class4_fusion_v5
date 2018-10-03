<?php
if ($_SESSION['role_menu']['Routing']['gatewaygroups:view']['model_w']) {
    ?><a id="add_resource_prefix" class="btn btn-primary btn-icon glyphicons circle_plus" onclick="return false;"
         href="###">
    <i></i> <?php echo __('Add Resource Prefix', true); ?>
    </a>
<?php } ?>
<div style="margin-top: 10px;overflow-x: auto">
    <table class="list footable table table-striped tableTools table-bordered  table-white table-primary"
           id="resource_table">
        <thead>
        <tr>
            <th>
                <?php echo __('Product Name', true); ?>
            </th>
            <th><?php echo __('Tech Prefix', true); ?></th>
            <th><?php echo __('Code', true); ?></th>
            <th>CPS</th>
            <th><?php echo __('CAP', true); ?></th>
            <th>
                <?php echo __('Rate Table', true); ?>
            </th>
            <th><?php echo __('Route Plan', true); ?></th>
            <?php
            if ($_SESSION['role_menu']['Routing']['gatewaygroups:view']['model_w']) {
                ?>
                <th><?php echo __('action', true); ?></th><?php } ?>
        </tr>
        </thead>
        <tbody>
        <?php
        $data_list = array();
        $data_list = isset($resouce_prefix_list) ? $resouce_prefix_list : $data_list;

        foreach ($data_list as $key => $resouce) {
            ?>
            <tr>

                <!--		<td>
                    <?php echo $resouce[0]['id'] ?>
                                    </td> -->

                <input type="hidden" value="<?php echo $resouce[0]['id'] ?>" name="resource[id][]"/>
                <td>
                    <?php
                    if ($_SESSION['login_type'] == 3) {
                        echo $form->input('product_id', array('name' => 'resource[product_id][]', 'options' => $product_name_arr, 'type' => 'select', 'label' => false, 'div' => false, 'class' => 'input in-text in-input product_id', 'id' => 'product_id', 'value' => $resouce[0]['product_id']));
                    } else {
                        echo $form->input('product_id', array('name' => 'resource[product_id][]', 'empty' => 'By Rate and Route Plan', 'options' => $product_name_arr, 'type' => 'select', 'label' => false, 'div' => false, 'class' => 'input in-text in-input product_id', 'id' => 'product_id', 'value' => $resouce[0]['product_id']));
                    } ?>
                </td>
                <td class="value">
                    <?php #echo $xform->input('rate_table_id',Array('name'=>'resource[rate_table_id]','options'=>""))
                    ?>
                    <input type="text" class="input in-input in-text tech_prefix" name="resource[tech_prefix][]"
                           id="tech_prefix"
                           value="<?php echo $resouce[0]['tech_prefix'] ?>" <?php echo isset($resouce[0]) && !empty($resouce[0]['product_id']) ? 'disabled' : ''; ?>/>
                </td>
                <td>
                    <input type="text" class="input in-input in-text" name="resource[code][]"
                           value="<?php echo isset($resouce[0]['code']) ? $resouce[0]['code'] : '' ?>"/>
                </td>
                <td>
                    <input type="text" class="input in-input in-text validate[custom[integer]]"
                           name="resource[code_cps][]"
                           value="<?php echo !isset($resouce[0]['code_cps']) || $resouce[0]['code_cps'] === '0' ? '' : $resouce[0]['code_cps']; ?>"/>
                </td>
                <td>
                    <input type="text" class="input in-input in-text validate[custom[integer]]"
                           name="resource[code_cap][]"
                           value="<?php echo !isset($resouce[0]['code_cap']) || $resouce[0]['code_cap'] === '0' ? '' : $resouce[0]['code_cap']; ?>"/>
                </td>
                <td>
                    <?php #echo $xform->input('tech_prefix',Array('name'=>'resource[tech_prefix]','options'=>""))
                    ?>
                    <?php
                    $options = array('options' => $rate_tables, 'label' => false, 'div' => false, 'type' => 'select', 'autocomplete' => "off", 'id' => 'reource_prefix_rate_' . $key, 'name' => "resource[rate_table_id][]", 'selected' => $resouce[0]['rate_table_id'], 'style' => 'width:200px', 'class' => 'rate_table_id');

                    if ($resouce[0] && !empty($resouce[0]['product_id'])) {
                        $options['disabled'] = true;
                    }
                    echo $form->input('currency_id', $options);
                    ?>


                </td>
                <td>
                    <select name="resource[route_strategy_id][]" class="route_strategy_id" autocomplete="off"
                            style="width:200px;" <?php echo isset($resouce[0]) && !empty($resouce[0]['product_id']) ? 'disabled' : ''; ?>>
                        <?php
                        foreach ($rout_list as $value) {
                            ?>
                            <option
                                value="<?php echo $value[0]['id'] ?>" <?php echo $value[0]['id'] == $resouce[0]['route_strategy_id'] ? 'selected' : ''; ?>><?php echo $value[0]['name'] ?></option>
                        <?php } ?>
                    </select>
                    <?php #echo $xform->input('route_strategy_id',Array('name'=>'resource[route_strategy_id]','options'=>""))
                    ?>
                </td>

                <?php
                if ($_SESSION['role_menu']['Routing']['gatewaygroups:view']['model_w']) {
                    ?>
                    <td>
                        <a href="###" title="Delete" rel="delete"
                           onClick="dele_tr('<?php echo $this->webroot ?>gatewaygroups/delete_resource_prefix/<?php echo $resouce[0]['id'] ?>', this, 'Resource Prefix <?php echo $resouce[0]['tech_prefix'] ?>')">
                            <i class="icon-remove"></i>
                        </a>
                    </td>
                <?php } ?>

            </tr>
            <?php
        }
        ?>
        <tr id="mb" style="display: none;">
            <!--        <td>
            <?php #echo $resource[0]['id']   ?>

                </td> -->
            <input type="hidden" value="" name="resource[id][]"/>
            <td>
                <?php
                if ($_SESSION['login_type'] == 3) {
                    echo $form->input('product_id', array('name' => 'resource[product_id][]', 'options' => $product_name_arr, 'type' => 'select', 'label' => false, 'div' => false, 'class' => 'input in-text in-input product_id', 'id' => 'product_id'));
                } else {
                    echo $form->input('product_id', array('name' => 'resource[product_id][]', 'empty' => 'By Rate and Route Plan', 'options' => $product_name_arr, 'type' => 'select', 'label' => false, 'div' => false, 'class' => 'input in-text in-input product_id', 'id' => 'product_id'));
                } ?>

                <?php if ($_SESSION['login_type'] != 3) { ?>
                    <a><i id="addproduct" style="cursor:pointer;" class="icon-plus"
                          onclick="showDiv('pop-div', '750', '200', '<?php echo $this->webroot ?>clients/addproduct','Add product');"></i></a>
                <?php } ?>
            </td>
            <td class="value">
                <?php #echo $xform->input('rate_table_id',Array('name'=>'resource[rate_table_id]','options'=>""))   ?>
                <input type="text" class="input in-input in-text tech_prefix" id="tech_prefix"
                       name="resource[tech_prefix][]"/>
            </td>
            <td>
                <input type="text" class="input in-input in-text" name="resource[code][]"/>
            </td>
            <td>
                <input type="text" class="input in-input in-text validate[custom[integer]]"
                       name="resource[code_cps][]"/>
            </td>
            <td>
                <input type="text" class="input in-input in-text validate[custom[integer]]"
                       name="resource[code_cap][]"/>
            </td>
            <td>
                <?php #echo $xform->input('tech_prefix',Array('name'=>'resource[tech_prefix]','options'=>""))  ?>
                <select id="ratetable" name="resource[rate_table_id][]" class="rate_table_id" style="width:200px;">
                    <?php
                    foreach ($rate_tables as $key => $value) {
                        ?>
                        <option value="<?php echo $key ?>"><?php echo $value ?></option>
                    <?php } ?>
                </select>

                <?php if ($_SESSION['login_type'] != 3) { ?>
                    <a id="addratetable" style="cursor:pointer;" src="<?php echo $this->webroot ?>images/add.png"
                       onclick="showDiv('pop-div', '500', '200', '<?php echo $this->webroot ?>clients/addratetable', 'Add Rate');">
                        <i class="icon-plus"></i>
                    </a>
                <?php } ?>
            </td>
            <td>
                <select name="resource[route_strategy_id][]" style="width:200px;" class="route_strategy_id">
                    <?php
                    foreach ($rout_list as $value) {
                        ?>
                        <option value="<?php echo $value[0]['id'] ?>"><?php echo $value[0]['name'] ?></option>
                    <?php } ?>
                </select>
                <?php #echo $xform->input('route_strategy_id',Array('name'=>'resource[route_strategy_id]','options'=>""))   ?>
            </td>

            <?php
            if ($_SESSION['role_menu']['Routing']['gatewaygroups:view']['model_w']) {
                ?>
                <td>
                    <a title="Delete" onclick="$(this).closest('tr').remove();">
                        <i class="icon-remove"></i>
                    </a>
                </td>
            <?php } ?>

        </tr>
        </tbody>
    </table>
</div>

<style>
    input[disabled] {
        background-color: #eeeeee;
    }
</style>
<script type="text/javascript" src="<?php echo $this->webroot ?>js/jquery.livequery.js"></script>
<script type="text/javascript">

    jQuery(document).ready(
        function () {

            $('#resource_table').on('change', '.product_id', function () {
                var product_id = $(this).val();

                if (product_id == "") {
                    $(this).parent().parent().find('.route_strategy_id').attr('disabled', false);
                    $(this).parent().parent().find('.rate_table_id').attr('disabled', false);
                    $(this).parent().parent().find('.tech_prefix').attr('disabled', false);
                } else {
                    $(this).parent().parent().find('.route_strategy_id').attr('disabled', true);
                    $(this).parent().parent().find('.rate_table_id').attr('disabled', true);
                    $(this).parent().parent().find('.tech_prefix').attr('disabled', true);
                }

                var self = this;
                $.ajax({
                    url: '<?php echo $this->webroot?>clients/get_product_info/' + product_id,
                    'type': 'GET',
                    dataType: 'json',
                    success: function (response) {
                        if (response.status) {
                            var tr = $(self).closest('tr');
                            tr.find('.rate_table_id').val(response.data.rate_table_id).end().attr('disabled', true);
                            tr.find('.tech_prefix').val(response.data.tech_prefix).end().attr('disabled', true);
                            tr.find('.route_strategy_id').val(response.data.route_strategy_id).end().attr('disabled', true);
                        }

                    }
                })
            });


            var mb = jQuery('#mb');
            jQuery('#add_resource_prefix').click(function () {
                mb.clone(true).removeAttr('id').show().appendTo('#resource_table tbody');
                if ($('#resource_table td select#product_id').last().val() != null) {
                    $('#resource_table td select#ratetable').last().parent().find('a').hide();
                }
                return false;
            });

            $('#addratetable').live('click', function () {
                $(this).prev().addClass('clicked');
                // window.open('<?php echo $this->webroot ?>clients/addratetable', 'addratetable', 'height=800,width=1000,top=0,left=0,toolbar=no,menubar=no,scrollbars=no, resizable=no,location=no, status=no');
            });
        });

    function test2(id) {
        $('#ratetable').livequery(function () {
            var $ratetable = $(this);
            $ratetable.empty();
            $.getJSON('<?php echo $this->webroot ?>clients/getratetable', function (data) {
                $.each(data, function (idx, item) {
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
        var $ratetable = $("#ratetable");
        $.getJSON('<?php echo $this->webroot ?>clients/getratetable', function (data) {
            $.each(data, function (idx, item) {
                var option = $("<option value='" + item['id'] + "'>" + item['name'] + "</option>");
                if (item['id'] == id) {
                    option.attr('selected', 'selected');
                }
                $ratetable.append(option);
            });
        })
    }

    $('#GatewaygroupAddResouceIngressForm').submit(
        function () {
            $('.route_strategy_id').attr('disabled', false);
            $('.rate_table_id').attr('disabled', false);
            $('.tech_prefix').attr('disabled', false);

            $('tr#mb').remove();
        }
    );
    $('#myform').submit(
        function () {
            $('.route_strategy_id').attr('disabled', false);
            $('.rate_table_id').attr('disabled', false);
            $('.tech_prefix').attr('disabled', false);
            $('tr#mb').remove();
        }
    );
</script>