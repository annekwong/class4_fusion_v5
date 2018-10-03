<ul class="breadcrumb">
    <li><?php __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?= $this->webroot?>systemparams/ftp_server_log"><?php __('Log') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?= $this->webroot?>systemparams/ftp_server_log">
        <?php echo __('FTP Server Log') ?></a></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('FTP Server Log') ?></h4>
    
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>

<div class="innerLR">

    <div class="widget widget-heading-simple widget-body-white">
        <div class="widget-body">
    <!--
    <ul class="tabs">
        <li>
            <a href="<?php echo $this->webroot ?>systemparams/ftp_conf">
                <img width="16" height="16" src="<?php echo $this->webroot ?>images/config.png">
                FTP Config
            </a>
        </li>
        <li>
            <a href="<?php echo $this->webroot ?>systemparams/ftp_trigger">
                <img width="16" height="16" src="<?php echo $this->webroot; ?>images/execute.png">
                Trigger
            </a>
        </li>
        <li>
            <a href="<?php echo $this->webroot ?>systemparams/ftp_log">
                <img width="16" height="16" src="<?php echo $this->webroot; ?>images/log.png">
                Log
            </a>
        </li>
        <li class="active">
            <a href="<?php echo $this->webroot ?>systemparams/ftp_server_log">
                <img width="16" height="16" src="<?php echo $this->webroot; ?>images/ftp.gif">
                Ftp Server Log
            </a>
        </li>
    </ul>
    -->
    <?php
        if(empty($this->data)): 
    ?>
    <h2 class="msg center"><?php echo __('no_data_found',true);?></h2>
    <?php else: ?>
<!--    <div class="separator bottom row-fluid">
                <div class="pagination pagination-large pagination-right margin-none">
                    <?php //echo $this->element('page'); ?>
                </div> 
            </div>-->
            <div class="clearfix"></div>
    <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
        <thead>
            <th><?php echo $appCommon->show_order('FtpServerLog.time', __('Time', true)) ?></th>
            <th><?php __('FTP Command')?></th>
            <th><?php __('FTP Response')?></th>
        </thead>
        <tbody>
            <?php foreach($this->data as $item): ?>
            <tr>
                <td><?php echo $item['FtpServerLog']['time']; ?></td>
                <td><?php echo $item['FtpServerLog']['cmd']; ?></td>
                <td><?php echo $item['FtpServerLog']['response']; ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<!--    <div class="row-fluid">
                <div class="pagination pagination-large pagination-right margin-none">
                    <?php //echo $this->element('page'); ?>
                </div> 
            </div>-->
            <div class="clearfix"></div>
    <?php endif; ?>
    <fieldset class="query-box" style=" clear:both;overflow:hidden;margin-top:10px;">
        <h4 class="heading glyphicons search"><i></i> <?php __('Search')?></h4>
        <div style="margin:0px auto; text-align:center;">
        <form id="myform" method="get" name="myform">
            <?php __('Period')?>:
            <input type="text" value="<?php echo $start_time; ?>" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',lang:'en'})" style="width:120px;" name="start" class="input in-text in-input">
            ~
            <input type="text" value="<?php echo $end_time; ?>" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',lang:'en'})" style="width:120px;" name="end" class="input in-text in-input">
            <?php __('GMT')?>:
            <select id="gmt" name="gmt" class="input in-select select">
                <option value="-1200">GMT -12:00</option>
                <option value="-1100">GMT -11:00</option>
                <option value="-1000">GMT -10:00</option>
                <option value="-0900">GMT -09:00</option>
                <option value="-0800">GMT -08:00</option>
                <option value="-0700">GMT -07:00</option>
                <option value="-0600">GMT -06:00</option>
                <option value="-0500">GMT -05:00</option>
                <option value="-0400">GMT -04:00</option>
                <option value="-0300">GMT -03:00</option>
                <option value="-0200">GMT -02:00</option>
                <option value="-0100">GMT -01:00</option>
                <option selected="selected" value="+0000">GMT +00:00</option>
                <option value="+0100">GMT +01:00</option>
                <option value="+0200">GMT +02:00</option>
                <option value="+0300">GMT +03:00</option>
                <option value="+0330">GMT +03:30</option><option value="+0400">GMT +04:00</option><option value="+0500">GMT +05:00</option><option value="+0600">GMT +06:00</option><option value="+0700">GMT +07:00</option><option value="+0800">GMT +08:00</option><option value="+0900">GMT +09:00</option><option value="+1000">GMT +10:00</option><option value="+1100">GMT +11:00</option><option value="+1200">GMT +12:00</option>
            </select>
            <input type="submit" value="<?php __('Submit')?>" class="input in-submit btn btn-primary" style="margin-bottom: 10px;">
        </form>
        </div>
   </fieldset>
</div>
    </div>
</div>
<div id="dd"> </div> 
<link rel="stylesheet" type="text/css" href="<?php echo $this->webroot?>easyui/themes/default/easyui.css">
<link rel="stylesheet" type="text/css" href="<?php echo $this->webroot?>easyui/themes/icon.css">
<script type="text/javascript" src="<?php echo $this->webroot?>easyui/jquery.easyui.min.js"></script>

<script>
   
</script>