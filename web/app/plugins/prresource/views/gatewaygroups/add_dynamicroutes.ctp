<?php $d = $p->getDataArray(); ?>

<ul class="breadcrumb">
    <li><?php __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Routing') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Dynamic Routing') ?></li>
</ul>


<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Dynamic Routing') ?>[<?php echo $trunk_name; ?>]</h4>
    
</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
        <a class="btn btn-icon btn-inverse glyphicons circle_arrow_left" href="<?php echo $this->webroot; ?>prresource/gatewaygroups/dynamicroutes/<?php echo base64_encode($resource_id); ?>"><i></i>Back</a>
    </div>
    <div class="clearfix"></div>
<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-head">
            <?php echo $this->element('egress_tab', array('active_tab' => 'dynamic_routing')); ?>
        </div>
        <div class="widget-body">
            <div class="filter-bar">
                <form method="get">
                    <!-- Filter -->
                    <div>
                        <label><?php __('Search')?>:</label>
                        <input type="text" id="search-_q" class="in-search default-value input in-text defaultText" title="<?php echo __('search') ?>" 
                               value="<?php
                               if (isset($_POST['search']))
                               {
                                   echo $_POST['search'];
                               }
                               else
                               {
                                   echo '';
                               }
                               ?>"  onclick="this.value = ''" name="search">
                    </div>
                    <!-- // Filter END -->
                    <!-- Filter -->
                    <div>
                        <label><?php __('Routing Rule')?>:</label>
                        <?php
                        $arr1 = array('4' => __('routerule1', true), '5' => __('routerule2', true), '6' => __('routerule3', true));
                        echo $form->input('routing_rule', array('options' => $arr1, 'name' => 'routing_rule', 'empty' => '', 'label' => false,
                            'div' => false, 'type' => 'select', 'value' => $routing_rule));
                        ?>
                        <script type="text/javascript">
                            jQuery(document).ready(function() {
                                jQuery('#routing_rule').val('<?php echo $routing_rule ?>')
                            });
                        </script>
                    </div>
                    <!-- // Filter END -->
                    <!-- Filter -->
                    <div>
                        <button name="submit" class="btn query_btn"><?php __('Query')?></button>
                    </div>
                    <!-- // Filter END -->
                </form>
            </div>
            <div class="clearfix"></div>
            <?php
            if (count($d) == 0)
            {
                ?>
                <h2 class="msg center"><?php  echo __('no_data_found') ?></h2>
                <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary" style="display:none">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="selectall" /></th>
                            <!--	    			<th> <?php echo $appCommon->show_order('dynamic_route_id', __('ID', true)) ?></th>-->
                            <th><?php echo $appCommon->show_order('name', __('Name'), true) ?></th>
                            <th><?php echo $appCommon->show_order('routing_rule', __('Routing Rule', true)) ?></th>
                            <th><?php echo $appCommon->show_order('time_profile_id', __('Time Profile', true)) ?></th>

                            <th><?php echo $appCommon->show_order('lcr_flag', __('QoS Cycle', true)) ?></th>


                        </tr>
                    </thead>
                </table>
                <?php
            }
            else
            {
                ?>
                <div id="toppage"></div>
                <div style="height:10px"></div>
                <?php //*********************表格�?************************************    ?>
                <div>
                    <form action="<?php echo $this->webroot; ?>prresource/gatewaygroups/modify_dynamicroutes" method="post" id="myform" >
                        <input type="hidden" value="<?php echo $resource_id; ?>" name="resource_id" />
                        <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
                            <thead>
                                <tr>
                                    <th><input type="checkbox" id="selectall" /></th>

                            <!--	    			<th> <?php echo $appCommon->show_order('dynamic_route_id', __('ID', true)) ?></th>-->
                                    <th><?php echo $appCommon->show_order('name', __(''), true) ?></th>
                                    <th><?php echo $appCommon->show_order('routing_rule', __('Routing Rule', true)) ?></th>
                                    <th><?php echo $appCommon->show_order('time_profile_id', __('Time Profile', true)) ?></th>

                                    <th><?php echo $appCommon->show_order('lcr_flag', __('QoS Cycle', true)) ?></th>

                                </tr>
                            </thead>
                            <?php //*********************表格�?************************************  ?>
                            <?php //*********************循环输出的动态部�?************************************ ?>
                            <?php
                            $mydata = $p->getDataArray();
                            $loop = count($mydata);
                            for ($i = 0; $i < $loop; $i++)
                            {
                                ?>

                                <tr class="row-<?php echo $i % 2 + 1; ?>">
                                    <td><input <?php
                                        if (in_array($mydata[$i][0]['dynamic_route_id'], $dynamic_route_id_arr))
                                        {
                                            ?>checked="checked"<?php } ?> type="checkbox" name="dynamic_route_id_arr[<?php echo $mydata[$i][0]['dynamic_route_id']; ?>]" /></td>

                                                <!--		    		<td  align="center">
                                    <?php echo $mydata[$i][0]['dynamic_route_id'] ?>	
                                                                      </td>-->
                                    <td  align="center"  style="font-weight: bold;"><?php echo $mydata[$i][0]['name']; ?></td >
                                    <td align="center">
                                        <?php
                                        if ($mydata[$i][0]['routing_rule'] == 4)
                                        {
                                            echo __('routerule1');
                                            ?>
                                            <?php
                                        }if ($mydata[$i][0]['routing_rule'] == 5)
                                        {
                                            echo __('routerule2');
                                            ?>
                                            <?php
                                        }if ($mydata[$i][0]['routing_rule'] == 6)
                                        {
                                            echo __('routerule3');
                                            ?>
                                        <?php } ?></td>
                                    <td><?php echo $mydata[$i][0]['time_profile_id'] ?>
                                        <?php
                                        if (empty($mydata[$i][0]['time_profile_id']))
                                        {
                                            echo '';
                                        }
                                        ?></td>

                                    <td><?php echo isset($mydata[$i][0]['lcr_flag']) ? $mydata[$i][0]['lcr_flag'] : ''; ?></td>

                                </tr>
                            <?php } ?>
                        </table>
                    </form>
                </div>
                <?php //*****************************************循环输出的动态部�?************************************       ?>
                <div style="height:10px"></div>
                <div class="row-fluid">
                    <div class="pagination pagination-large pagination-right margin-none">
                        <?php echo $this->element('page'); ?>
                    </div> 
                </div>
                <div class="clearfix"></div>


            <?php } ?>
            <div class="bottom"></div>
            <div class="center">
                <input type="button" value="Add" id="modify" class="btn btn-primary">
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(function() {

        $("#modify").click(function() {

            $("#myform").submit();
        });
    });
    $('#selectall').change(function() {

        if ($(this).attr('checked'))
        {
            $('table.list tbody input:checkbox').attr('checked', 'checked');
        } else
        {
            $('table.list tbody input:checkbox').removeAttr('checked');
        }

    });

</script>

