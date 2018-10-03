<ul class="breadcrumb">
    <li><?php __('You are here')?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Statistics') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Simple CDR Export') ?></li>
</ul>
<div class="separator bottom"></div>
<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
    <?php echo $this->element('quickcdr/tab', array('active' => 'log')); ?>
        <div class="widget-body">
        <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary footable-loaded default">
        <thead>
            <tr>
                <th><?php __('Start Date')?></th>
                <th><?php __('End Date')?></th>
                <th><?php __('Type')?></th>
                <th><?php __('Client')?></th>
                <th><?php __('Status')?></th>
                <th><?php __('Action')?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($this->data as $item): ?>
                <tr>
                    <td><?php echo $item['Quickcdr']['start_date'] ?></td>
                    <td><?php echo $item['Quickcdr']['end_date'] ?></td>
                    <td><?php echo (int)$item['Quickcdr']['type'] == 0 ? 'Ingress' : 'Egress' ?></td>
                    <td><?php echo $clients[$item['Quickcdr']['client_id']] ?></td>
                    <td><?php echo $status[$item['Quickcdr']['status']] ?></td>
                    <td>
                        <?php if ($item['Quickcdr']['status'] == 2): ?>
                        <a href="<?php echo $this->webroot ?>quickcdr/export/<?php echo $item['Quickcdr']['id'] ?>" title="Download">
                            <i class="icon-download-alt"></i>
                        </a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="bottom row-fluid">
        <div class="pagination pagination-large pagination-right margin-none">
            <?php echo $this->element('xpage'); ?>
        </div> 
     </div>
        </div>
    </div>    
</div>

<div id="dd">
</div>

<link rel="stylesheet" type="text/css" href="<?php echo $this->webroot?>easyui/themes/icon.css">
<script type="text/javascript" src="<?php echo $this->webroot?>easyui/jquery.easyui.min.js"></script>
<script>
    jQuery(function($) {
        var $email_out = $('.email_out');
        var $dd = $('#dd');
        
        $email_out.click(function() {
            var $this = $(this);
            var control = $this.attr('control');

            $dd.dialog({
                title: 'Email out',
                width: 400,
                height: 200,
                closed: false,
                cache: false,
                resizable: true,
                href: '<?php echo $this->webroot ?>hung_calls_detection/send_huang_call_email/' + control,
                modal: true
            });

            $dd.dialog('refresh', '<?php echo $this->webroot ?>hung_calls_detection/send_huang_call_email/' + control);
        });
    });
</script>