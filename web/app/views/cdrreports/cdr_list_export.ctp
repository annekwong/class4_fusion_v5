<ul class="breadcrumb">
    <li><?php __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Statistics') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('CDRs list') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('CDR Export Log') ?></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php __('CDR Export Log') ?></h4>
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>

<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-head">

            <?php if ($session->read('login_type') == 3): ?>

                <?php echo $this->element('report/cdr_report/cdr_report_user_tab', array('active' => 'export_log2')) ?>
            <?php else: ?>

                <ul>
                    <li>
                        <a href="<?php echo $this->webroot; ?>cdrreports/summary_reports"  class="glyphicons list">
                            <i></i><?php __('CDR Search') ?>
                        </a>
                    </li>
                    <li class="active">
                        <a href="<?php echo $this->webroot; ?>cdrreports/cdr_list_export" class="glyphicons book_open">
                            <i></i><?php __('CDR Export Log') ?>
                        </a>
                    </li>
                    <!--                    <li>
                                            <a href="<?php echo $this->webroot; ?>cdrreports/mail_send_log" class="glyphicons no-js e-mail" >
                                                <i></i> Mail CDR Log
                                            </a>
                                        </li>-->
                </ul>
            <?php endif; ?>

        </div>
        <div class="widget-body">
            <div class="filter-bar">
                <form action="" method="get">
                    <!-- Filter -->
                    <div>
                        <label><?php __('Start Time') ?>:</label>
                        <input type="text" value="<?php echo $start_time ?>" onclick="WdatePicker({dateFmt: 'yyyy-MM-dd HH:mm:ss', lang: 'en'})" style="width:120px;" name="start_time" class="input in-text in-input">
                    </div>

                    <div>
                        <label><?php __('End Time') ?>:</label>
                        <input type="text" value="<?php echo $end_time; ?>" onclick="WdatePicker({dateFmt: 'yyyy-MM-dd HH:mm:ss', lang: 'en'})" style="width:120px;" name="end_time" class="input in-text in-input">
                    </div>
                    <div>
                        <button class="btn query_btn" name="submit"><?php __('Query') ?></button>
                    </div>
                    <!-- // Filter END -->
            </div>



            <div id="container">
                <?php if (empty($this->data)): ?>
                    <div class="msg center">
                        <br />
                        <h3><?php echo __('no_data_found') ?></h3>
                    </div>
                <?php else: ?>
                    <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
                        <thead>
                            <tr>
                                <th><?php __('Job ID') ?></th>
                                <th><?php __('CDR Start Time') ?></th>
                                <th><?php __('CDR End Time') ?></th>
                                <th><?php __('Job Start Time') ?></th>
                                <th><?php __('Job End Time') ?></th>
                                <th><?php __('Line Count') ?></th>
                                <th><?php __('Status') ?></th>
                                <th><?php __('File Size') ?></th>
                                <th><?php __('Action') ?></th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php foreach ($this->data as $item): ?>
                                <tr>
                                    <td>#<?php echo $item['CdrListExportLog']['id']; ?></td>
                                    <td><?php echo $item['CdrListExportLog']['start_time']; ?></td>
                                    <td><?php echo $item['CdrListExportLog']['end_time']; ?></td>
                                    <td><?php echo $item['CdrListExportLog']['job_start_time']; ?></td>
                                    <td><?php echo $item['CdrListExportLog']['job_end_time']; ?></td>
                                    <td><?php echo $item['CdrListExportLog']['line_count']; ?></td>
                                    <td><?php echo $status[$item['CdrListExportLog']['status']]; ?></td>
                                    <td>
                                        <?php if ($item['CdrListExportLog']['status'] == 2): ?> 
                                            <?php
                                            echo $appCommon->to_readable_size(@filesize($item['CdrListExportLog']['file_path']));
                                            ?>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($item['CdrListExportLog']['status'] == 2): ?> 
                                            <a title="Download" href="<?php echo $this->webroot ?>cdrreports/cdr_list_export_down?key=<?php echo base64_encode($item['CdrListExportLog']['id']); ?>">
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
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
    $(function() {

        var interv = null;

        $('#changetime').change(function() {
            if (interv)
                window.clearInterval(interv);
            var time = $(this).val() * 1000;
            interv = window.setInterval("loading();window.location.reload()", time);
        });

        $('#changetime').change();

        var $show_files = $('.show_files');

        $show_files.click(function() {
            var $this = $(this);
            if (!$('#dd').length) {
                $(document.body).append("<div id='dd'></div>");
            }
            var $dd = $('#dd');
            var $form = null;
            $dd.load('<?php echo $this->webroot; ?>cdrreports/export_log_files/',
                    {'key': $this.data('id')},
            function(responseText, textStatus, XMLHttpRequest) {
                $dd.dialog({
                    'width': '600px'
                });
            }
            );

        });
    });
</script>