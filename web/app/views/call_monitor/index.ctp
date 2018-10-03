<ul class="breadcrumb">
    <li>You are here</li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Tools') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Call Monitor') ?></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Call Monitor') ?></h4>
    <div class="buttons pull-right">
       
    </div>
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>

<div class="innerLR">

    <div class="widget widget-heading-simple widget-body-white">
        <div class="widget-body">
     <?php
        if(empty($this->data)): 
    ?>
            <div class="clearfix"></div>
    <h2 class="msg center"><?php echo __('no_data_found',true);?></h2>
    <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary" style="display:none;">
        
        <thead>
            <tr>
                <th>ID</th>
                <th>Start Time</th>
                <th>End Time</th>
                <th>ANI</th>
                <th>DNIS</th>
                <th>Remote Ip</th>
                <th>Remote Port</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        
        <tbody>
         
        </tbody>
    </table>
    <?php else: ?>
            <div class="clearfix"></div>
    <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
        <thead>
            <tr>
                <th><?php echo $appCommon->show_order('CallMonitor.id', __('ID', true)) ?></th>
                <th><?php echo $appCommon->show_order('CallMonitor.start_time', __('Start Time', true)) ?></th>
                <th><?php echo $appCommon->show_order('CallMonitor.end_time', __('End Time', true)) ?></th>
                <th><?php echo $appCommon->show_order('CallMonitor.ani', __('ANI', true)) ?></th>
                <th><?php echo $appCommon->show_order('CallMonitor.dnis', __('DNIS', true)) ?></th>
                <th>Remote Ip</th>
                <th>Remote Port</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        
        <tbody>
            <?php foreach($this->data as $item): ?>
            <tr>
                <td>#<?php echo $item['CallMonitor']['id']; ?></td>
                <td><?php echo $item['CallMonitor']['start_time']; ?></td>
                <td><?php echo $item['CallMonitor']['end_time']; ?></td>
                <td><?php echo $item['CallMonitor']['ani']; ?></td>
                <td><?php echo $item['CallMonitor']['dnis']; ?></td>
                <td><?php echo $item['CallMonitor']['remote_ip']; ?></td>
                <td><?php echo $item['CallMonitor']['remote_port']; ?></td>
                <td><?php echo $item['CallMonitor']['status'] == 0 ? 'Running' : 'Halted'; ?></td>
                <td>
                    <?php if ($item['CallMonitor']['status'] == 0): ?>
                    <a title="Stop"  href="<?php echo $this->webroot ?>call_monitor/stop/<?php echo $item['CallMonitor']['id']?>" >
                        <i class="icon-stop"></i>
                    </a>
                    <?php 
                    else:
                    $start_time = $item['CallMonitor']['start_time'];
                    $end_time   = $item['CallMonitor']['end_time'];
                    $start_time = explode(' ', $start_time);
                    $end_time = explode(' ', $end_time);
                    ?>
                    <a title="View" target="_blank"  href="<?php echo $this->webroot ?>cdrreports_db/summary_reports?smartPeriod=custom&min_start_date=<?php echo $start_time[0] ?>&min_start_time=<?php echo substr($start_time[1], 0, 8); ?>&max_stop_date=<?php echo $start_time[0] ?>&max_stop_time=<?php echo substr($end_time[1], 0, 8); ?>&open_callmonitor=1" >
                        <i class="icon-list"></i>
                    </a>
                    <?php endif; ?>
                </td>
            </tr>
           
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="row-fluid">
                <div class="pagination pagination-large pagination-right margin-none">
                    <?php echo $this->element('xpage'); ?>
                </div> 
            </div>
            <div class="clearfix"></div>
    <?php endif; ?>
    <?php //if ($count == 0): ?>
    <fieldset class="query-box" style=" clear:both;overflow:hidden;margin-top:10px;">
        <h4 class="heading glyphicons pencil"><i></i> Panel</h4>
        <form method="post">
        <table class="form table table-condensed" style="width: 100%">
            <tbody>
                <tr>
                    <td>Server:</td>
                    <td>
                        <select class="input-medium" name="server">
                            <?php foreach ($servers as $server): ?>
                            <option value="<?php echo $server[0]['id'] ?>"><?php echo $server[0]['sip_ip'] ?>:<?php echo $server[0]['sip_port'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                    <td>ANI:</td>
                    <td>
                        <input type="text" name="ani"  class="input-small">
                    </td>
                    <td>DNIS:</td>
                    <td>
                        <input type="text" name="dnis" class="input-small">
                    </td>
                    <td>Remote IP:</td>
                    <td>
                        <input type="text" name="remote_ip" class="input-small">
                    </td>
                    <td>Remote Port:</td>
                    <td>
                        <input type="text" name="remote_port" class="input-small">
                    </td>
                    <td>
                        <input type="submit" value="Submit" class="btn btn-primary">
                    </td>
                </tr>
            </tbody>
        </table>
        </form>
    </fieldset>
    <?php //endif; ?>
</div>
    </div>
</div>