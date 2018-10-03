
<div class="buttons">
    <a id="add_resource_prefix" class="btn btn-primary btn-icon glyphicons circle_plus" href="###"><i></i> <?php echo __('Add Resource Prefix', true); ?> </a>
</div>
<div class="clearfix"></div>
<div class="separator bottom"></div>
<table class="list  footable table table-striped tableTools table-bordered  table-white table-primary default footable-loaded"  id="resource_table">
    <thead>
        <tr>
            <th> 
                <?php echo __('Product Name',true);?>
            </th>
            <th> 
                <?php echo __('Rate Table', true); ?>
            </th>
            <th><?php echo __('Route Plan', true); ?></th>
            <th> <?php echo __('tech_prefix', true); ?></th>
            <th><?php echo __('action', true); ?></th>
        </tr>
    </thead>
    <tbody>

        <tr id="mb" >
            <td>
                <?php echo $form->input('product_id', array('name' => 'resource[product_id][]', 'empty' => 'By Rate and Route Plan', 'options' => $product_name_arr,'type' => 'select', 'label' => false, 'div' => false, 'class' => 'input in-text in-input product_id','id' =>'product_id')) ?>
                <a><i id="addproduct" style="cursor:pointer;" class="icon-plus" onclick="showDiv('pop-div', '750', '200', '<?php echo $this->webroot ?>clients/addproduct');" ></i></a>
            </td> 
            <td>
                <?php
                $arr = array();
                foreach($rate_table as $v){

                    $arr[$v[0]['id']] = $v[0]['name'];
                }
                echo $form->input('rate_table_id', array('name' => 'resource[rate_table_id][]', 'options' => $arr,'type' => 'select', 'label' => false, 'div' => false, 'class' => 'input in-text in-input rate_table_id','id' =>'rate_table_id')) ?>
                <a><i id="addratetable" style="cursor:pointer;" class="icon-plus" onclick="showDiv('pop-div', '700', '300', '<?php echo $this->webroot ?>clients/addratetable');" ></i></a>
            </td>
            <td>
                <?php
                $arr = array();
                foreach($route_list as $v){

                    $arr[$v[0]['id']] = $v[0]['name'];
                }
                echo $form->input('route_strategy_id', array('name' => 'resource[route_strategy_id][]', 'options' => $arr,'type' => 'select', 'label' => false, 'div' => false, 'class' => 'input in-text in-input route_strategy_id','id' =>'route_strategy_id')) ?>
                <a><i id="addrouteplan" style="cursor:pointer;" class="icon-plus" onclick="showDiv('pop-div', '500', '200', '<?php echo $this->webroot ?>clients/addroutingplan');" ></i></a>
            </td>
            <td>
                <?php echo $form->input('tech_prefix', array('name' => 'resource[tech_prefix][]', 'type' => 'text', 'label' => false, 'div' => false, 'class' => 'input in-text in-input tech_prefix','id' =>'tech_prefix')) ?>
            </td>
            <td class="last">
                <a  title="Delete" onclick="$(this).closest('tr').remove();">
                    <i class='icon-remove'></i>
                </a>  
            </td>

        </tr>
    </tbody>
</table>

<script type="text/javascript" src="<?php echo $this->webroot ?>js/jquery.livequery.js"></script>
<script type="text/javascript">

           jQuery(document).ready(
                   function() {

                       $.ajaxSettings.async = false;
                       //product 过来
                       $.getJSON('<?php echo $this->webroot ?>clients/ajax_procuct_list', function(data){product_arr = data;});
                       $('.product_id').live('change',function(){
                           var product_id = $(this).val();
                           var tr = $(this).parent().parent();
                           if(product_id){
                               tr.find('.product_ingress_name').val("<?php echo $client_name ?>_"+product_arr[product_id]['product_name']);
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



                       //添加提交过来的route信息
                       var fun;
                       <?php
                           if(!empty($product_id_arr)):
                           $i=1;
                               foreach($product_id_arr as $val): ?>
                       function func<?php echo $i?>(){
                           $('#add_resource_prefix').click();
                           $('.product_id:last').val('<?php echo $val?>');
                           $('.product_id:last').change();
                       }
                       fun = func<?php echo $i++?>;
                       setTimeout(fun,50);



                       <?php endforeach;endif;?>


                       var mb = jQuery('#mb').remove();
                       jQuery('#add_resource_prefix').click(function() {
                           mb.clone(true).appendTo('#resource_table tbody');
                           return false;
                       });
                   });

           $(document).ready(function() {
               $('#addrouteplan').live('click', function() {
                   $(this).prev().addClass('clicked');
                   // window.open('<?php echo $this->webroot ?>clients/addroutingplan', 'addroutingplan',    'height=600,width=600,top=0,left=0,toolbar=no,menubar=no,scrollbars=no, resizable=no,location=no, status=no');
               });

               $('#addratetable').live('click', function() {
                   $(this).prev().addClass('clicked');
                   //window.open('<?php echo $this->webroot ?>clients/addratetable', 'addratetable',        'height=800,width=1000,top=0,left=0,toolbar=no,menubar=no,scrollbars=no, resizable=no,location=no, status=no');
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

           function test4(name) {
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
</script>







