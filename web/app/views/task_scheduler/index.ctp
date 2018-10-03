<style type="text/css">
    .product_list input {
        display:block; float:left;margin-left: 50px;
    }
    .product_list label {
        display:block; float:left;margin-left: 10px;
    }
</style>
<ul class="breadcrumb">
    <li><?php __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Configuration') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Task Scheduler') ?></li>
</ul>


<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Task Scheduler') ?></h4>
    <div class="buttons pull-right">

    </div>
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>

<div class="innerLR">

    <div class="widget widget-heading-simple widget-body-white">
        <div class="widget-body">

            <table class="footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
                <thead>
                    <tr>
                        <th><?php echo $appCommon->show_order('name', __('Name', true)) ?></th>
                        <th><?php __('Run at') ?></th>
                        <th><?php echo $appCommon->show_order('last_run', __('Last Run', true)) ?></th>
                        <th><?php __('Active') ?></th>
                        <th><?php __('Action') ?></th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($taskSchedulers as $taskScheduler): ?>
                        <tr>
                            <td><?php echo $taskScheduler[0]['name'] ?></td>
                            <td><?php echo $taskScheduler[0]['run_at'] ?></td>
                            <td><?php echo $taskScheduler[0]['last_run'] ?></td>
                            <td>
                                <a href="<?php echo $this->webroot ?>task_scheduler/change_status/<?php echo base64_encode($taskScheduler[0]['id']) ?>">
                                    <?php if ($taskScheduler[0]['active']): ?>
                                        <i class="icon-check"></i>
                                    <?php else: ?>
                                        <i class="icon-unchecked"></i>
                                    <?php endif; ?>
                                </a>
                            </td>
                            <td>
    <!--                                <a title="Manual Run" href="<?php echo $this->webroot ?>task_scheduler/run/<?php echo base64_encode($taskScheduler[0]['id']) ?>">
                                    <i class="icon-fire"></i>
                                </a>-->
                                <a href="javascript:void(0)" edited_value="<?php echo $taskScheduler[0]['id'] ?>" class="edited_item">
                                    <i class="icon-edit"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
<!--                <tfoot>
                    <tr>
                        <td colspan="5" class="button-groups center">
                            <button class="btn btn-primary" id="refresh">Refresh</button>
                        </td>
                    </tr>
                </tfoot>-->
            </table>

        </div>
    </div>
</div>

<div id="dd"> </div>  

<link rel="stylesheet" type="text/css" href="<?php echo $this->webroot ?>easyui/themes/default/easyui.css">
<!--<link rel="stylesheet" type="text/css" href="<?php echo $this->webroot ?>easyui/themes/icon.css">-->
<script type="text/javascript" src="<?php echo $this->webroot ?>easyui/jquery.easyui.min.js"></script>
<script src="<?php echo $this->webroot ?>js/jquery.jgrowl.js" type="text/javascript"></script>

<script>
    $(function() {
        var $edited_item = $('.edited_item');

        $edited_item.click(function() {
            if (!$('#dd').length) {
                $(document.body).append("<div id='dd'></div>");
            }
            var $dd = $('#dd');
            var edited_value = $(this).attr('edited_value');

            $dd.dialogui({
                title: 'Task Scheduler',
                width: 600,
                height: 420,
                closed: false,
                cache: false,
                href: '<?php echo $this->webroot ?>task_scheduler/edit/' + edited_value,
                modal: true,
                onClose: function() {
                    $dd.dialogui('destroy');
                },
                buttons: [{
                        text: 'Save',
                        handler: function() {
                            $('#myform').submit();
                        }
                    }, {
                        text: 'Close',
                        handler: function() {
                            $dd.dialogui('destroy');
                        }
                    }]
            });

            $dd.dialogui('refresh', '<?php echo $this->webroot ?>task_scheduler/edit/' + edited_value);


        });

//        $('#refresh').click(function() {
//            $.get('<?php echo $this->webroot ?>task_scheduler/refresh', function(data) {
//                showMessages("[{'field':'#ingrLimit','code':'201','msg':'Succeeded!'}]");
//            });
//        });
    });
</script>