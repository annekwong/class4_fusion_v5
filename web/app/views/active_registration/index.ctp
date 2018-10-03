<style type="text/css">
    .overflow_x{overflow-x:auto; margin-bottom: 10px;}
    input[type="text"]{width:220px;}
</style>
<ul class="breadcrumb">
    <li><?php __('You are here')?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>active_registration">
            <?php __('Statistics') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>active_registration">
            <?php echo __('Active Registration') ?></a></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Active Registration') ?></h4>
    <div class="buttons pull-right">

    </div>
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>

<div class="innerLR">

    <div class="widget widget-heading-simple widget-body-white">
        <div class="widget-body">
            <div class="overflow_x">
                <table class="list footable table table-striped tableTools table-bordered  table-white table-primary" id="registrationTable">
                    <thead>
                    <tr>
                        <th><?php __('Username'); ?></th>
                        <th><?php __('Registered On'); ?></th>
                        <th><?php __('Expired On'); ?></th>
                        <th><?php __('Registered From'); ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($this->data as $data_item): ?>
                        <tr>
                            <td><?php echo $data_item[0]['username'] ?></td>
                            <td><?php echo date('Y-m-d H:i:sO',intval($data_item[0]['uptime'])); ?></td>
                            <td><?php echo date('Y-m-d H:i:sO',(intval($data_item[0]['expires']) + intval($data_item[0]['uptime']))); ?></td>
                            <td><?php echo $data_item[0]['network_ip'] ?>:<?php echo $data_item[0]['network_port'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>
<script type="text/javascript">
    $(function() {
        $("#registrationTable").DataTable( {
            "bPaginate": true,
            "bSort": true,
            "paging": true,
            "aoColumnDefs": [
                {
                    sDefaultContent: '',
                    aTargets: [ '_all' ]
                }
            ],
        } );
    });
</script>