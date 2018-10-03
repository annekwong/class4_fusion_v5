<ul class="breadcrumb">
    <li><?php __('You are here')?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?= $this->webroot?>retrieve_password_log">
        <?php echo __('Log', true); ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?= $this->webroot?>retrieve_password_log">
        <?php echo __('Retrieve Password Log') ?></a></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Retrieve Password Log',true);?></h4>
    <div class="buttons pull-right">

    </div>
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>


<div class="innerLR">

    <div class="widget widget-heading-simple widget-body-white">
        <div class="widget-body">
            <div class="filter-bar">
                <form method="get">
            <!-- Filter -->
            <div>
                <label><?php __('Status')?>:</label>
                <select name="status_type">
                    <option value=""><?php __('All')?></option>
                        <option <?php if(isset($_GET['status_type']) && $_GET['status_type'] == 1) echo 'selected="selected"'; ?>  value="1"><?php echo $status_name[1]?></option>
                        <option <?php if(isset($_GET['status_type']) && $_GET['status_type'] == 2) echo 'selected="selected"'; ?>  value="2"><?php echo $status_name[2]?></option>
                        <option <?php if(isset($_GET['status_type']) && $_GET['status_type'] == 3) echo 'selected="selected"'; ?>  value="3"><?php echo $status_name[3]?></option>
                </select>
            </div>
            <!-- // Filter END -->
            <!-- Filter -->
            <div>
                <label><?php __('Start Date')?>:</label>
                <input type="text" value="<?php echo $start_time ?>" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',lang:'en'})" style="width:120px;" name="start_time" class="input in-text in-input">
            </div>

            <div>
                <label><?php __('End Date')?>:</label>
                <input type="text" value="<?php echo $end_time; ?>" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',lang:'en'})" style="width:120px;" name="end_time" class="input in-text in-input">
            </div>
            <div>
                <button class="btn query_btn" name="submit"><?php __('Query')?></button>
            </div>
            <!-- // Filter END -->

            </form>
        </div>
            <div class="clearfix"></div>
            <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
                <thead>
                <tr>
                    <th><?php echo $appCommon->show_order('RetrievePasswordLog.operation_time', __('Operation Time', true)) ?></th>
                    <th><?php echo $appCommon->show_order('RetrievePasswordLog.username', __('Username', true)) ?></th>
                    <th><?php echo $appCommon->show_order('RetrievePasswordLog.email_addresses', __('Email Address', true)) ?></th>
                    <th><?php echo $appCommon->show_order('RetrievePasswordLog.status', __('Status', true)) ?></th>
                    <th><?php echo $appCommon->show_order('RetrievePasswordLog.modify_time', __('Modify Time', true)) ?></th>
                </tr>
                </thead>

                <tbody>
                <?php foreach($this->data as $item): ?>
                    <tr>
                        <td><?php echo $item['RetrievePasswordLog']['operation_time']; ?></td>
                        <td><?php echo $item['RetrievePasswordLog']['username']; ?></td>
                        <td><?php echo $item['RetrievePasswordLog']['email_addresses']; ?></td>
                        <?php if($item['RetrievePasswordLog']['status'] == 2 && isset($item['RetrievePasswordLog']['mark']) &&  $item['RetrievePasswordLog']['mark'] === true):?>
                        <td style="color:red;"><?php echo $status_name[4]; ?></td>
                        <?php else: ?>
                        <td><?php echo $status_name[$item['RetrievePasswordLog']['status']]; ?></td>
                        <?php endif ?>
                        <td><?php echo $item['RetrievePasswordLog']['modify_time']; ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <?php if(count($this->data)): ?>
            <div class="row-fluid">
                <div class="pagination pagination-large pagination-right margin-none">
                    <?php echo $this->element('xpage'); ?>
                </div>
            </div>
            <?php endif; ?>
            <div class="clearfix"></div>



        </div>
    </div>
</div>