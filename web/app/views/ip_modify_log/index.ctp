<ul class="breadcrumb">
    <li><?php __('You are here'); ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?= $this->webroot?>ip_modify_log/index"><?php __('Log') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?= $this->webroot?>ip_modify_log/index">
        <?php echo __('Ip modify Log', true); ?></a></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Ip modify Log', true); ?></h4>
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>
<div class="innerLR">

    <div class="widget  widget-heading-simple widget-body-white">
        <div class="clearfix"></div>
        
        <div class="widget-body">
<div class="filter-bar">

            <form action="" method="get">
                <!-- Filter -->

                <!-- // Filter END -->
                <!-- Filter -->

                <!-- // Filter END -->
                <div>
                    <label><?php __('Time')?>:</label>
                    <input id="start_date" class="input in-text wdate " value="<?php
                    if (isset($get_data['time_start']))
                    {
                        echo $get_data['time_start'];
                    }
                    ?>" type="text" readonly="" onfocus="WdatePicker({dateFmt: 'yyyy-MM-dd HH:mm:ss'});" name="time_start">
                    -- 
                    <input id="end_date" class="wdate input in-text" type="text" value="<?php
                           if (isset($get_data['time_end']))
                           {
                               echo $get_data['time_end'];
                           }
                           ?>" readonly="" onfocus="WdatePicker({dateFmt: 'yyyy-MM-dd HH:mm:ss'});" name="time_end">
                </div>
                <!-- Filter -->

                <div>
                    <label><?php __('Type')?>:</label>
<?php echo $form->input('type', array('options' => $type_arr, 'label' => false, 'class' => 'select', 'selected' => isset($get_data['data']['type'])?$get_data['data']['type'] : "", 'div' => false, 'type' => 'select')); ?>
                </div>


                <div>
                    <label><?php __('Trunk')?>:</label>
                    <select name="trunk_id" >
                        <option value="">All</option>
                    <?php foreach ($trunk_list as $resource_id => $alias): ?>
                        <option value="<?php echo $resource_id; ?>"
                                <?php if(isset($get_data['trunk_id']) && !strcmp($resource_id,$get_data['trunk_id'])): ?>
                                selected="selected" 
                                <?php endif; ?>
                                ><?php echo $alias; ?></option>
                    <?php endforeach; ?>
                        </select>
                </div>


                <div>
                    <label><?php __('New Values')?>:</label>
<?php echo $form->input('new', array('options' => array('Modification', 'Add', 'Delete'), 'label' => false, 'type' => 'text', 'value' => isset($get_data['data']['new'])?$get_data['data']['new'] : "", 'div' => false)); ?>
                </div>

                <div>
                    <button name="submit" class="btn query_btn"><?php __('Query')?></button>
                </div>
                <!-- // Filter END -->


            </form>
        </div>
        <br class="clear" />
            <?php
            $data = $p->getDataArray();
            ?>
            <?php
            if (count($data) == 0)
            {
                ?>
                <h2 class="msg center"><?php echo __('no_data_found') ?></h2>

    <?php
}
else
{
    ?>
                <div class="clearfix"></div>
                <fieldset>
                    <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
                        <thead>
                            <tr>
                                <th><?php echo $appCommon->show_order('client.name', __('Carrier Name', true)) ?></th>
                                <th><?php echo $appCommon->show_order('resource.alias', __('Trunk Name', true)) ?></th>
                                <th><?php echo __('Trunk Type', true) ?></th>
                                <th><?php echo $appCommon->show_order('ip_modif_log.modify', __('Type', true)) ?></th>
                                <th><?php echo __('Old Value') ?></th>
                                <th><?php echo __('New Value') ?></th>
                                <th><?php echo $appCommon->show_order('ip_modif_log.update_at', __('Modified Time', true)) ?></th>
                                <th><?php echo $appCommon->show_order('ip_modif_log.update_by', __('Modified By', true)) ?></th>
                                <th><?php echo $appCommon->show_order('ip_modif_log.email', __('Send Email To', true)) ?></th>
                            </tr>
                        </thead>

                        <tbody>
    <?php
    foreach ($data as $item)
    {
        ?>
                                <tr>
                                    <td>
                                        <?php if(empty($item[0]['alias'])){
                                            echo "(trunk has been deleted)";
                                        } else {
                                            echo $item[0]['name'];
                                        }

                                        ?>
                                    </td>
                                    <td>
                                        <?php echo !empty($item[0]['alias']) ? $item[0]['alias'] : "resource_id :{$item[0]['trunk_id']}"; ?>
                                    </td>
                                    <td>
                                        <?php if($item[0]['egress']){
                                            echo 'egress';
                                        } elseif(!empty($item[0]['alias'])) {
                                            echo 'ingress';
                                        } else {
                                            echo "(trunk has been deleted)";
                                        }

                                        ?>
                                    </td>
                                    <td><?php echo $type_arr[intval($item[0]['modify'])]; ?></td>

                                        <td><?php echo $item[0]['old']; ?></td>
                                        <td><?php echo $item[0]['new']; ?></td>

                                    <td><?php echo $item[0]['update_at']; ?></td>
                                    <td><?php echo $item[0]['update_by']; ?></td>
                                    <td><?php echo $item[0]['email']; ?></td>
                                </tr>
    <?php } ?>
                        </tbody>
                    </table>
                    <div class="row-fluid separator">
                        <div class="pagination pagination-large pagination-right margin-none">
    <?php echo $this->element('page'); ?>
                        </div> 
                    </div>
                </fieldset>
<?php } ?>
        </div>
    </div>
</div>
</div>


