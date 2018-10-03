<style>
    form{margin: 0;}
</style>
<ul class="breadcrumb">
    <li><?php __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Log') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Rate Import Log') ?></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Rate Import Log') ?></h4>

</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
    <a href="###" id="refresh_btn" class="link_btn btn btn-primary btn-icon glyphicons refresh">
        <i></i><?php echo __('Refresh', true); ?>
    </a>

    <?php if ($rate_table_id != null) : ?>
        <a href="<?php echo $this->webroot ?>clientrates/view/<?php echo $rate_table_id; ?>" class="link_back_new btn btn-icon btn-inverse glyphicons circle_arrow_left">
            <i></i><?php echo __('goback', true); ?>
        </a>
    <?php endif; ?>
</div>
<div class="clearfix"></div>
<div class="innerLR">

    <div class="widget widget-heading-simple widget-body-white">



        <div class="widget-body">
            <div class="filter-bar">

                <form action="" method="get">

                    <!-- Filter -->
                    <div>
                        <label><?php __('Rate Table') ?>:</label>
                        <select name="rate_table" style="width:200px;" class="in-select select" id="rate_table">
                            <option value=""></option>
                            <?php
                            foreach ($rate_table as $key => $name)
                            {
                                ?>
                                <option value="<?php echo $key; ?>" <?php
                                if (!strcmp($rate_table_id, $key))
                                {
                                ?>selected="selected"<?php } ?>><?php echo $name; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <!-- // Filter END -->
                    <div>
                        <label><?php __('Start Time') ?>:</label>
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
                    <!-- Filter -->
                    <div>
                        <button name="submit" class="btn query_btn"><?php __('Query') ?></button>
                    </div>
                    <!-- // Filter END -->

                </form>
            </div>
            <div class="clearfix"></div>
            <?php if(!count($this->data)): ?>
                <div class="msg center"><br /><h2><?php  echo __('no_data_found') ?></h2></div>
            <?php else: ?>
            <div class="overflow_x">
                <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
                    <thead style="background:none;">
                    <tr>
                        <th class="footable-first-column expand" data-class="expand" ><?php echo $appCommon->show_order('rate_table.name', __('Rate Table', true)) ?></th>
                        <th><?php __('File Name') ?></th>
                        <th><?php __('User') ?></th>
                        <th><?php __('Status') ?></th>
                        <th><?php __('Records') ?></th>
                        <th><?php echo $appCommon->show_order('RateUploadTask.method', __('Method', true)) ?></th>
                        <th><?php echo $appCommon->show_order('RateUploadTask.start_time', __('Start Time', true)) ?></th>
                        <th data-hide="phone,tablet"  style="display: table-cell;"><?php echo $appCommon->show_order('RateUploadTask.end_time', __('Finish Time', true)) ?></th>
                        <th data-hide="phone,tablet"  style="display: table-cell;"><?php echo $appCommon->show_order('RateUploadTask.time', __('Upload Time', true)) ?></th>
                        <th data-hide="phone,tablet" class="footable-last-column"  style="display: table-cell;"><?php __('Action') ?></th>
                    </tr>
                    </thead>

                    <tbody>
                    <?php foreach ($this->data as $item): ?>
                        <tr>
                            <td class="footable-first-column expand" data-class="expand" >
                                <a href="<?php echo $this->webroot ?>clientrates/view/<?php echo base64_encode($item['rate_table']['rate_table_id']) ?>">
                                    <?php echo $item['rate_table']['name']; ?>
                                </a>
                            </td>
                            <td><?php echo $item['RateUploadTask']['upload_orig_file']; ?></td>
                            <td><?php echo $item['RateUploadTask']['operator_user']; ?></td>
                            <td><?php echo $status[$item['RateUploadTask']['status']]; ?></td>
                            <td><?php echo $item['RateUploadTask']['progress']; ?></td>
                            <td><?php echo $method[$item['RateUploadTask']['reduplicate_rate_action']]; ?></td>
                            <td>
                                <?php if (strcmp('1', $item['RateUploadTask']['status']) && strcmp('-1', $item['RateUploadTask']['status'])){
                                    echo date('Y-m-d H:i:sO', $item['RateUploadTask']['start_time']);
                                } ?>
                            </td>
                            <td data-hide="phone,tablet"  style="display: table-cell;">
                                <?php echo $item['RateUploadTask']['end_time'] ? date('Y-m-d H:i:sO', $item['RateUploadTask']['end_time']) : ''; ?>
                            </td>
                            <td data-hide="phone,tablet"  style="display: table-cell;">
                                <?php echo $item['RateUploadTask']['create_time'] ? date('Y-m-d H:i:sO', $item['RateUploadTask']['create_time']) : ''; ?>
                            </td>
                            <td data-hide="phone,tablet" class="footable-last-column"  style="display: table-cell;">
                                <a class="show_default" href="#MyModal_showRateImportDefault" data-toggle="modal" info="<?php echo $item['RateUploadTask']['default_info']; ?>"
                                   title="<?php __('Default Value'); ?>:<br /><?php echo $item['RateUploadTask']['default_info']; ?>">
                                    <i class="icon-info"></i>
                                </a>
                                <a target="_blank" title="<?php __('Upload File') ?>" href="<?php echo $this->webroot; ?>rate_log/get_file/?file=<?php echo base64_encode($item['RateUploadTask']['upload_file_path'] . DS .$item['RateUploadTask']['upload_format_file']); ?>">
                                    <i class="icon-file-text"></i></a>
                                <a target="_blank" title="<?php __('Error File') ?>" href="<?php echo $this->webroot; ?>rate_log/get_file/?file=<?php echo base64_encode($item['RateUploadTask']['result_file_path'] . 'rate_import.log'); ?>">
                                    <i class="icon-download"></i></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="row-fluid">
                <div class="pagination pagination-large pagination-right margin-none">
                    <?php echo $this->element('xpage'); ?>
                </div>
            </div>
            <div class="clearfix"></div>
            <?php endif; ?>
        </div>
    </div>
</div>
<div id="MyModal_showRateImportDefault" class="modal hide">
    <div class="modal-header">
        <button data-dismiss="modal" class="close" type="button">&times;</button>
        <h3><?php __('Import Default'); ?></h3>
    </div>
    <div class="modal-body">
        <table class="table table-bordered">
        </table>
    </div>
    <div class="modal-footer">
        <a href="javascript:void(0)" id="change_password_close" data-dismiss="modal" class="btn btn-default"><?php __('Close'); ?></a>
    </div>
</div>
<script>
    $(function() {
        $('#refresh_btn').click(function() {
            window.location.reload();
        });

        $(".show_default").click(function(){
            $("#MyModal_showRateImportDefault").find('.table').html('');
            var default_info = $(this).attr('info');
            var default_info_arr= new Array();
            default_info_arr=default_info.split("<br />");
            for (i=0;i<default_info_arr.length ;i++ )
            {
                var default_item_arr = new Array();
                var default_item = default_info_arr[i];
                var default_item_length = default_item.length;
                var delimiter_pos = default_item.indexOf(':');
                var default_name = default_item.substring(0,delimiter_pos);
                var default_value = default_item.substring(delimiter_pos+1,default_item_length);
                var html_item = '<tr><td class="align_right">'+default_name+'</td><td>'+default_value+'</td></tr>';
                $("#MyModal_showRateImportDefault").find('.table').append(html_item);
            }
        });
    });
</script>