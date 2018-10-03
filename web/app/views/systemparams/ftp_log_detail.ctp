<div id="title">
    <h1><?php echo __('Log'); ?> &gt;&gt; <?php echo __('FTP Log'); ?></h1>
</div>

<div id="container">
    
    <ul class="tabs">
        <li>
            <a href="<?php echo $this->webroot ?>systemparams/ftp_conf">
                <img width="16" height="16" src="<?php echo $this->webroot ?>images/config.png">
                <?php __('FTP Config')?>
            </a>
        </li>
        <li>
            <a href="<?php echo $this->webroot ?>systemparams/ftp_trigger">
                <img width="16" height="16" src="<?php echo $this->webroot; ?>images/execute.png">
                <?php __('Trigger')?>
            </a>
        </li>
        <li class="active">
            <a href="<?php echo $this->webroot ?>systemparams/ftp_log">
                <img width="16" height="16" src="<?php echo $this->webroot; ?>images/log.png">
                <?php __('Log')?>
            </a>
        </li>
    </ul>
    <?php
        if(empty($this->data)): 
    ?>
    <div class="msg"><?php echo __('no_data_found',true);?></div>
    <?php else: ?>
    <?php echo $this->element("xpage")?>
    <table class="list">
        <thead>
            <th><?php __('Created On')?></th>
            <th><?php __('File Name')?></th>
            <th><?php __('FTP IP')?></th>
            <th><?php __('FTP Directory')?></th>
            <th><?php __('FTP Transfer Detail')?></th>
        </thead>
        <tbody>
            <?php foreach($this->data as $item): ?>
            <tr>
                <td><?php echo $item['FtpCdrLogDetail']['create_time']; ?></td>
                <td><a taget="_blank" href="<?php echo $this->webroot; ?>systemparams/ftp_download/<?php  echo $item['FtpCdrLogDetail']['id']; ?>"><?php echo $item['FtpCdrLogDetail']['file_name']; ?></a></td>
                <td><?php echo $item['FtpCdrLogDetail']['ftp_ip']; ?></td>
                <td><?php echo $item['FtpCdrLogDetail']['ftp_dir']; ?></td>
                <td>
                    <a control="<?php echo $item['FtpCdrLogDetail']['id']; ?>" href="###" class="transfer_detail">
                        <img src="<?php echo $this->webroot ?>images/detail.png">
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php echo $this->element("xpage")?>
    <?php endif; ?>
</div>
<div id="dd"> </div> 
<link rel="stylesheet" type="text/css" href="<?php echo $this->webroot?>easyui/themes/default/easyui.css">
<link rel="stylesheet" type="text/css" href="<?php echo $this->webroot?>easyui/themes/icon.css">
<script type="text/javascript" src="<?php echo $this->webroot?>easyui/jquery.easyui.min.js"></script>

<script>
    var $dd = $('#dd');
    var $transfer_detail = $(".transfer_detail");
    
    $transfer_detail.click(function() {
        var $this = $(this);
        var control = $this.attr('control');

        $dd.dialog({  
            title: 'FTP Transfer Detail',  
            width: 500,  
            height: 350,  
            closed: false,  
            cache: false,  
            resizable: true,
            href: '<?php echo $this->webroot?>systemparams/ftp_log_detail_message/' + control,  
            modal: true,                
            buttons:[{
                    text:'Close',
                    handler:function(){
                        $dd.dialog('close');
                    }
            }]
        });

        $dd.dialog('refresh', '<?php echo $this->webroot?>systemparams/ftp_log_detail_message/' + control);  
    });
</script>