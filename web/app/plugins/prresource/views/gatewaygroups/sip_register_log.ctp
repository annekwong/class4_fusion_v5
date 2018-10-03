<ul class="breadcrumb">
    <li><?php __('You are here')?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?= $this->webroot?>prresource/gatewaygroups/sip_register_log">
        <?php echo __('Log', true); ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?= $this->webroot?>prresource/gatewaygroups/sip_register_log">
        <?php echo __('Sip Register Log') ?></a></li>
</ul>

<div class="heading-buttons">
    <h4 class="detail"><?php echo __('Sip Register Log',true);?></h4>
    <div class="buttons pull-right">
       
    </div>
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>


<div class="innerLR">

    <div class="widget widget-heading-simple widget-body-white">
        <div class="widget-body">
            <div class="clearfix"></div>
            <?php if (!count($this->data)): ?>
            <div class="msg center">
                <br />
                <h2>
                    <?php echo __('No Data Found', true); ?>
                </h2>
            </div>
            <?php else: ?>
    <table class="dynamicTable list footable table table-striped tableTools table-bordered  table-white table-primary">
        <thead>
            <tr>
                <th><?php echo $appCommon->show_order('username', __('Username', true)) ?></th>
                <th><?php __('Trunk Name') ?></th>
                <th><?php __('Carrier Name') ?></th>
                <th><?php echo __('Ip', true)?></th>
                <th><?php __('Port')?></th>
                <th><?php __('Status')?></th>
                <th><?php __('Expire')?></th>
                <th><?php __('Contact')?></th>
                <th><?php echo $appCommon->show_order('uptime', __('Uptime', true)) ?></th>
            </tr>
        </thead>
        
        <tbody>
            <?php foreach($this->data as $item): ?>
            <tr>
                <td><?php echo $item[0]['username']; ?></td>
                <td><?php echo $item[0]['trunk_name']; ?></td>
                <td><?php echo $item[0]['carrier_name']; ?></td>
                <td><?php echo $item[0]['network_ip']; ?></td>
                <td><?php echo $item[0]['network_port']; ?></td>
                <td>
                    <?php
                    echo array_key_exists($item[0]['status'], $statuses) ? $statuses[$item[0]['status']] : '';
                    ?>
                </td>
                <td><?php echo $item[0]['expires']; ?></td>
                <td>
                    <a href="javascript:void(0)" data-layout="center" data-type="primary" data-toggle="notyfy"
                       data-value="<?php echo htmlspecialchars($item[0]['contact']) ?>"><?php echo $appCommon->sub_string(htmlspecialchars($item[0]['contact']),100); ?>
                    </a>
                </td>
<!--                <td>--><?php //echo $item[0]['contact']; ?><!--</td>-->
                <td><?php echo date('Y-m-d H:i:s',$item[0]['uptime']); ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="row-fluid">
                <div class="pagination pagination-large pagination-right margin-none">
                    <?php echo $this->element('xpage'); ?>
                </div> 
            </div>
            <?php endif ?>
            <div class="clearfix"></div>


<fieldset class="query-box" style=" clear:both;overflow:hidden;margin-top:10px;">
        <h4 class="heading glyphicons search"><i></i> <?php __('Search')?></h4>
        <div style="margin:0px auto; text-align:center;">
        <form method="get" name="myform">
            <?php __('Status')?>
            <select name="status">
                <option value=""><?php __('All')?></option>
                <?php foreach ($statuses as $key=>$value): ?>
                <option value="<?php echo $key; ?>" <?php if(isset($_GET['status']) && $_GET['status'] == $key && $_GET['status'] !== '') echo 'selected="selected"'; ?>><?php echo $value; ?></option>
                <?php endforeach; ?>
            </select>
            <?php __('Username')?>:
            <input type="text" value="<?php echo isset($_GET['username']) ? $_GET['username'] : '' ?>" name="username" />
            <?php __('Time')?>:
            <input type="text" value="<?php echo $start_time ?>" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',lang:'en'})" style="width:120px;" name="start_time" class="input in-text in-input">
            ~
            <input type="text" value="<?php echo $end_time; ?>" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',lang:'en'})" style="width:120px;" name="end_time" class="input in-text in-input">
            <input type="submit" value="<?php __('Submit')?>" class="input in-submit btn btn-primary margin-bottom10">
        </form>
        </div>
   </fieldset>

            </div>
    </div>
</div>
<script type="text/javascript" src="<?php echo $this->webroot ?>js/custom_notyfy.js"></script>

