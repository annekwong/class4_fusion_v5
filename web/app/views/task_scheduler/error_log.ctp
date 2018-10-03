<ul class="breadcrumb">
    <li><?php __('You are here'); ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Log') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Error Log', true); ?></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Error Log', true); ?></h4>
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>
<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
            <div class="clearfix"></div>
            
            <div class="widget-body">
<div class="filter-bar">

            <form action="" method="get">
                <!-- Filter -->

                <!-- // Filter END -->
                <!-- Filter -->
                
                <!-- // Filter END -->
                <div>
                    <label><?php __('Time') ?>:</label>
                    <input id="start_date" class="input in-text wdate " value="<?php if(isset($get_data['time_start'])){ echo $get_data['time_start'];} ?>" type="text" readonly="" onfocus="WdatePicker({dateFmt: 'yyyy-MM-dd HH:mm:ss'});" name="time_start">
                    -- 
                    <input id="end_date" class="wdate input in-text" type="text" value="<?php if(isset($get_data['time_end'])){ echo $get_data['time_end'];} ?>" readonly="" onfocus="WdatePicker({dateFmt: 'yyyy-MM-dd HH:mm:ss'});" name="time_end">
                </div>
                <!-- Filter -->
                
                <div>
                    <label><?php __('Status') ?>:</label>
                    <select  name="status" class="in-select select input-small" >
                        <option value=""></option>
                        <option value="0" <?php if (isset($get_data['status']) && !strcmp('0', $get_data['status'])){ ?>selected="selected"<?php } ?>><?php __('not been sent') ?></option>
                        <option value="1" <?php if (isset($get_data['status']) && !strcmp('1', $get_data['status'])){ ?>selected="selected"<?php } ?>><?php __('Has been sent') ?></option>
                    </select>
                </div>
                
                <div>
                    <button name="submit" class="btn query_btn"><?php __('Query') ?></button>
                </div>
                <!-- // Filter END -->


            </form>
        </div>
                <?php
                $data = $p->getDataArray();
                ?>
                <?php if (count($data) == 0)
                { ?>
                    <h2 class="msg center"><?php echo __('no_data_found') ?></h2>
                    <table class="list" style="display:none;">
                        <thead>
                            <tr>
                                <th><?php echo $appCommon->show_order('error_time', __('Time', true)) ?></th>
                                <th><?php echo $appCommon->show_order('users', __('User', true)) ?></th>
                                <th><?php echo $appCommon->show_order('detail', __('Detail', true)) ?></th>
                                <th><?php echo $appCommon->show_order('sent', __('Sent', true)) ?></th>
                            </tr>
                        </thead>

                        <tbody>

                        </tbody>
                    </table>
<?php }
else
{ ?>
                    <div class="clearfix"></div>
                    <fieldset>
                        <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
                            <thead>
                                <tr>
                                    <th><?php echo $appCommon->show_order('error_time', __('Time', true)) ?></th>
                                    <th><?php echo $appCommon->show_order('users', __('User', true)) ?></th>
                                    <th><?php echo $appCommon->show_order('detail', __('Detail', true)) ?></th>
                                    <th><?php echo $appCommon->show_order('sent', __('Sent', true)) ?></th>
                                </tr>
                            </thead>

                            <tbody>
    <?php foreach ($data as $item)
    { ?>
                                    <tr>
                                        <td><?php echo $item[0]['error_time']; ?></td>
                                        <td><?php echo $item[0]['users']; ?></td>
                                        <td><?php echo $item[0]['detail']; ?></td>
                                        <td>
                                            <?php if($item[0]['sent']){ ?>
                                            Has been sent
                                            <?php }else{ ?>
                                            <a title="<?php __('Send Email to DENOVOLAB')?>" href="<?php echo $this->webroot; ?>task_scheduler/send_error_email/<?php echo base64_encode($item[0]['id']); ?>">
                                            <i class="icon-envelope"></i></a>
                                            <?php } ?>
                                        </td>
                                        
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                        <div class="row-fluid">
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


