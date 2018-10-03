<ul class="breadcrumb">
    <li><?php __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Tools') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Check Route') ?></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php __('Client List') ?></h4>

</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
    <!--<a class="btn btn-primary btn-icon glyphicons file_import" href="<?php echo $this->webroot ?>uploads/carrier"><i></i>Import</a>-->
    <?php
    if($_SESSION['role_menu']['Tools']['check_route']['model_w']){
        ?>
        <a class="btn btn-primary btn-icon glyphicons circle_plus" href="<?php echo $this->webroot ?>check_route/add"><i></i> <?php __('Create New') ?></a>
        <?php
    }
    ?>
</div>
<div class="clearfix"></div>
<?php
$is_exchange = Configure::read('system.type') === 2 ? TRUE : FALSE;
$data = $p->getDataArray();
?>
<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">

        <div class="widget-body">
            <div class="filter-bar">
                <form method="get">
                    <div>
                        <label><?php __('Create Time') ?>:</label>
                        <input id="start_date" class="input in-text wdate " value="<?php
                        if (isset($get_data['time']))
                        {
                            echo $get_data['time'];
                        }
                        ?>" type="text" readonly="" onfocus="WdatePicker({dateFmt: 'yyyy-MM-dd HH:mm:ss'});" name="time">
                        ~
                        <input id="end_date" class="input in-text wdate " value="<?php
                        if (isset($get_data['end_time']))
                        {
                            echo $get_data['end_time'];
                        }
                        ?>" type="text" readonly="" onfocus="WdatePicker({dateFmt: 'yyyy-MM-dd HH:mm:ss'});" name="end_time">

                    </div>
                    <!-- // Filter END -->
                    <!-- Filter -->
                    <div>
                        <button name="submit" class="btn query_btn"><?php __('Query') ?></button>
                    </div>
                    <!-- // Filter END -->
                </form>
            </div>
            <div class="clearfix"></div>
            <!-- Table -->
            <table id="list" class=" footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
                <!-- Table heading -->
                <thead>
                <tr>
                    <th></th>
                    <th><?php echo $appCommon->show_order('start_time', __('Create On', true)) ?></th>
                    <th><?php __('Completed Time')?></th>
<!--                    <th>--><?php //__('Destinations') ?><!--</th>-->
                    <th><?php __('Trunk') ?></th>
                    <th><?php __('Calls') ?></th>
                    <th><?php __('ASR') ?></th>
                    <th><?php __('Time') ?>(s)</th>
                    <th><?php __('Action') ?></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($data as $item): ?>
                    <tr >
                        <td>
                            <?php if(empty($item[0]['end_time'])): ?>
                                <i class="icon-spinner icon-spin icon-large" name="noresult" data-value="<?php echo $item[0]['id']?>"></i>
                            <?php else: ?>
                                <a title="<?php __('Show Detail'); ?>" href="<?php echo  $this->webroot."check_route/showalldetail/".base64_encode($item[0]['id']);?>">
                                    <i class="icon-lightbulb icon-large"></i>
                                </a>
                            <?php endif; ?>
                        </td>
                        <td><?=str_ireplace(" ", '<br/>', $item[0]['start_time']);?></td>
                        <td><?=str_ireplace(" ", '<br/>', $item[0]['end_time']);?></td>
<!--                        <td>--><?php //echo $item[0]['code_name'] ?><!--</td>-->
                        <td><?=str_ireplace(" ", '<br/>', $item[0]['egress_name']);?></td>
                        <td>
                            <span class="success_calls"><?php echo intval($item[0]['success_calls']) ?></span>
                                &nbsp;
                            <?php __('of'); ?>&nbsp;
                            <span><?php echo intval($item[0]['total_calls']); ?></span>
                        </td>
                        <td class="asr"><?php echo $item[0]['asr'] ?></td>
                        <td><?php echo $item[0]['sec'] ?></td>
                        <td>
                            <a href="javascript:void(0);" onclick="delRate(<?=$item[0]['id']?>,this)" title="Delete">
                                <i class="icon-remove"></i>
                            </a>
                        </td>

                    </tr>
                <?php endforeach; ?>

                </tbody>
                <!-- // Table body END -->

            </table>
            <!-- // Table END -->
            <div class="row-fluid separator">
                <div class="pagination pagination-large pagination-right margin-none">
                    <?php echo $this->element('page'); ?>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>

</div>
<scirpt type="text/javascript" src="<?php $this->webroot ?>js/jquery.center.js"></scirpt>
<script type="text/javascript">

    $(function(){
        if($("#list").find('i[name=noresult]').size() != 0){
            //getdata();
            var interval_time = 3000;
            window.setInterval("getdata()", interval_time);
        }
    });


    function getdata(){
        var load = $("#list").find('i[name=noresult]');
        $.each(load, function (index,content){
            var id = $(content).data('value');
            $.post("<?php echo $this->webroot ?>check_route/get_result_index", {cdr_id:id},
                function (data){
                    if(data['end_time'] != '' && data['end_time'] != null){
                        tr = $(content).parents('tr').eq(0);
                        tr.find('td').eq(2).html(data['end_time']);
                        tr.find('td').eq(0).html("<a href=\"<?php echo  $this->webroot."check_route/showalldetail";?>/"+id+"\"><i class='icon-lightbulb icon-large'></i></a>");
                        tr.find('.success_calls').html(data['success_calls']);
                        tr.find('.asr').html(data['asr']);
                    }
                },'json'
            );
        });
    }

    function delRate(id,obj){
        bootbox.confirm('<?php __('sure to delete'); ?>', function(result) {
            if(result) {
                var url="<?php echo $this->webroot?>check_route/del_cdr/"+id;
                var data=jQuery.ajaxData(url);
                if(data.indexOf('true')==-1){
                    jQuery.jGrowlError('delete fail!');
                }else{
                    // $waiting.remove();
                    jQuery.jGrowlSuccess('The Record is removed successfully.');
                    $(obj).parents('tr').remove();
                }
            }
        });
    }

</script>
