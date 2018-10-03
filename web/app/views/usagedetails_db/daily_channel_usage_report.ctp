<!--导入所有reoprt页面的input和select样式文件-->
<style>
    #stats-period{display: inline-block}
</style>
<?php echo $this->element('magic_css'); ?>
<?php $data = $p->getDataArray(); ?>
<?php

$user_id = $_SESSION['sst_user_id'];
$res = $cdr_db->query("select * from users where user_id = {$user_id} ");


//var_dump($data);
?>
<ul class="breadcrumb">
    <li><?php echo __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>usagedetails_db/daily_channel_usage_report">
            <?php __('Statistics') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>usagedetails_db/daily_channel_usage_report">
            <?php echo __('Daily Channel Usage Report') ?></a></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Daily Channel Usage Report') ?></h4>
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>

<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">

        <div class="widget-body">
            <?php if ($show_nodata): ?>
                <?php echo $this->element('report_db/real_period') ?>
            <?php endif; ?>
            <!-- ****************************************普通输出******************************************* -->
            <div class="table_container">
                <?php if (empty($data)): ?>
                    <?php if ($show_nodata): ?>
                        <h2 class="msg center"><?php  echo __('no_data_found') ?></h2>
                    <?php endif; ?>
                <?php else: ?>

                    <table class="list footable table table-striped table-bordered table-white table-primary">
                        <thead>
                        <tr>
                            <th><?php __('Report Time'); ?></th>
                            <th><?php __('Maximum Ingress Channel') ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($data as $item): ?>
                            <?php
                            $item_total = 0;
                            $item_total_bill_time = 0;
                            ?>
                            <tr>
                                <td><?php echo $item[0]['report_time'] ?></td>
                                <td><?php echo $item[0]['max'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>

                    <div class="bottom row-fluid">
                        <div class="pagination pagination-large pagination-right margin-none">
                            <?php echo $this->element('page'); ?>
                        </div>
                    </div>
                <?php endif; ?>

                <fieldset class="query-box" style=" clear:both;overflow:hidden;margin-top:10px;">
                    <h4 class="heading glyphicons search"><i></i> <?php __('search') ?></h4>
                    <?php echo $this->element('search_report/search_js'); ?><?php echo $form->create('Cdr', array('type' => 'get', 'url' => '/usagedetails_db/daily_channel_usage_report/', 'onsubmit' => "if ($('#query-output').val() == 'web') loading();")); ?>  <?php echo $this->element('search_report/search_hide_input'); ?>

                    <table class="form" style="width:100%">
                        <?php echo $this->element('report_db/form_period', array('group_time' => true, 'gettype' => '<select style="width:120px;" name="show_type">
            <option value="0">Web</option>
            <option value="1">CSV</option>
            <option value="2">XLS</option>
        </select>')) ?>
                    </table>
                    <div id="advance_panel" class="widget widget-heading-simple widget-body-gray">
                        <table class="form" style="width:100%">
                            <tbody>
                            <tr>
                                <td class="align_right padding-r10"><?php __('Server IP')?>:</td>
                                <td colspan="3">
                                    <select name="server_ip" id="">
                                        <?php foreach ($voipGateways as $voipGateway): ?>
                                            <option value="<?php echo $voipGateway[0]['lan_ip']; ?>" <?php echo $voipGateway[0]['lan_ip'] == $serverIp ? 'selected' : ''; ?> ><?php echo $voipGateway[0]['lan_ip']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </fieldset>

                <?php echo $this->element('search_report/search_js_show'); ?>
            </div>
        </div>

