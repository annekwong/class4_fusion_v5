<ul class="breadcrumb">
    <li><?php __('You are here')?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Management', true); ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Registration') ?></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Registration',true);?></h4>
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
                        <option <?php if(isset($_GET['status_type']) && $_GET['status_type'] == 0) echo 'selected="selected"'; ?>  value="0"><?php echo $status_name[0]?></option>
                        <option <?php if(isset($_GET['status_type']) && $_GET['status_type'] == 1) echo 'selected="selected"'; ?>  value="1"><?php echo $status_name[1]?></option>
                        <option <?php if(isset($_GET['status_type']) && $_GET['status_type'] == 2) echo 'selected="selected"'; ?>  value="2"><?php echo $status_name[2]?></option>
                </select>
            </div>
<!--             // Filter END-->
<!--             Filter-->
            <div>
                <label><?php __('Signup Time')?>:</label>
                <input type="text" value="<?php echo $start_time ?>" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',lang:'en'})" style="width:120px;" name="start_time" class="input in-text in-input">
            </div>

            <div>
                <label><?php __('~')?></label>
                <input type="text" value="<?php echo $end_time; ?>" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',lang:'en'})" style="width:120px;" name="end_time" class="input in-text in-input">
            </div>
            <div>
                <button class="btn query_btn" name="submit"><?php __('Query')?></button>
            </div>
            <!-- // Filter END -->

            </form>
        </div>
            <div class="clearfix"></div>
            <table class="footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
                <thead>
                <tr>
<!--                    <th>--><?php //echo $appCommon->show_order('Registration.id', __('ID', true)) ?><!--</th>-->
                    <th><?php echo $appCommon->show_order('Registration.login', __('Username', true)) ?></th>
                    <th><?php echo $appCommon->show_order('Registration.email', __('Main Email Address', true)) ?></th>
                    <th><?php echo $appCommon->show_order('Registration.company', __('Company Name', true)) ?></th>
                    <th><?php echo $appCommon->show_order('Registration.address', __('Address', true)) ?></th>
                    <th><?php echo $appCommon->show_order('Registration.phone', __('Phone Number', true)) ?></th>
                    <th><?php echo $appCommon->show_order('Registration.skype', __('Skype ID', true)) ?></th>
                    <th><?php echo $appCommon->show_order('Registration.signup_time', __('Signup Time', true)) ?></th>
                    <th><?php echo $appCommon->show_order('Registration.modify_time', __('Modify Time', true)) ?></th>
                    <th><?php echo $appCommon->show_order('Registration.status', __('Status', true)) ?></th>
                    <th><?php __('Action')?></th>
                </tr>
                </thead>

                <tbody>
                <?php foreach($this->data as $item): ?>
                    <tr>
<!--                        <td>--><?php //echo $item[0]['id']; ?><!--</td>-->
                        <td><?php echo $item[0]['login']; ?></td>
                        <td><?php echo $item[0]['email']; ?></td>
                        <td><?php echo $item[0]['company']; ?></td>
                        <td><?php echo $item[0]['address']; ?></td>
                        <td><?php echo $item[0]['phone']; ?></td>
                        <td><?php echo $item[0]['skype']; ?></td>

                        <td><?php echo $item[0]['signup_time']; ?></td>
                        <td><?php echo $item[0]['modify_time']; ?></td>
                        <td><?php echo $status_name[$item[0]['status']]; ?></td>
                        <td>
                        <a title="<?php __('Approve')?>" onclick="return myconfirm('Are you sure to inactivate the client [<?php echo $item[0]['login'] ?>] ?', this)" href="<?php echo $this->webroot ?>clients/dis_able/<?php echo base64_encode($item[0]['id']) ?>?<?php echo $$hel->getParams('getUrl') ?>"><i class="icon-ok"></i></a>
                            <a title="<?php __('Reject')?>" onclick="return myconfirm('Are you sure to reject the user [<?php echo $item[0]['login'] ?>] ?', this)" href="<?php echo $this->webroot ?>registration/del/<?php echo $item[0]['id'] ?>" class=""><i class="icon-remove"></i></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <div class="row-fluid separator">
                <div class="pagination pagination-large pagination-right margin-none">
                    <?php echo $this->element('xpage'); ?>
                </div>
            </div>
            <div class="clearfix"></div>



        </div>
    </div>
</div>


