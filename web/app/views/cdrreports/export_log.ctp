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
                        <a href="<?php echo $this->webroot; ?>cdrreports/export_log" class="glyphicons book_open">
                            <i></i><?php __('CDR Export Log') ?>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo $this->webroot; ?>cdrreports/mail_send_log" class="glyphicons no-js e-mail" >
                            <i></i><?php __('Mail CDR Log') ?> 
                        </a>
                    </li>
                </ul>
            <?php endif; ?>

        </div>
        <div class="widget-body">
            <div class="filter-bar">
                <form action="" method="get">
                    <!-- Filter -->
                    <div>
                        <label><?php __('Tiggered Time') ?>:</label>
                        <input id="start_date" class="input in-text wdate " value="<?php
                        if (isset($get_data['time_start']))
                        {
                            echo $get_data['time_start'];
                        }
                        ?>" type="text" readonly="" onfocus="WdatePicker({dateFmt: 'yyyy-MM-dd HH:mm:ss'});" name="time_start">
                        -- 
                        <input id="end_date" class="wdate input in-text" type="text" value="<?php
                        if (isset($get_data['time_end']))
                        {
                            echo $get_data['time_end'];
                        }
                        ?>" readonly="" onfocus="WdatePicker({dateFmt: 'yyyy-MM-dd HH:mm:ss'});" name="time_end">
                    </div>
                    <!-- // Filter END -->
                    <div>
                        <button name="submit" class="btn query_btn"><?php __('Query') ?></button>
                    </div>
                </form>
                <div>
                    <label><?php echo __('Refresh Every', true); ?>:</label>
                    <select id="changetime">
                        <option value="180">3 <?php __('minutes') ?></option>
                        <option value="300">5 <?php __('minutes') ?></option>
                        <option value="800">15 <?php __('minutes') ?></option>
                    </select>
                </div>
            </div>



            <div id="container">


                <?php if (empty($this->data)): ?>
                    <div class="msg center">
                        <br />
                        <h3><?php  echo __('no_data_found') ?></h3>
                    </div>
                <?php else: ?>
                    <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
                        <thead>
                            <tr>
                                <th><?php __('Job ID')?></th>
                                <th><?php __('Tiggered Time')?></th>
                                <th><?php __('Status')?></th>
                                <th><?php __('Completed/Total(day)')?></th>
                                <th><?php __('File Size')?></th>
                                <th><?php __('Action')?></th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php foreach ($this->data as $item): ?>
                                <tr>
                                    <td>#<?php echo $item['CdrExportLog']['id']; ?></td>
                                    <td><?php echo $item['CdrExportLog']['export_time']; ?></td>
                                    <td><?php echo $status[$item['CdrExportLog']['status']]; ?></td>
                                    <td>
                                        <?php if (!is_null($item['CdrExportLog']['total_days'])): ?>
                                            <?php echo $item['CdrExportLog']['completed_days']; ?>/<?php echo $item['CdrExportLog']['total_days']; ?>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($item['CdrExportLog']['status'] == 4): ?> 
                                            <?php
                                            echo $appCommon->to_readable_size(@filesize($item['CdrExportLog']['file_path']));
                                            ?>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($item['CdrExportLog']['status'] == 4): ?> 
                                            <a title="Download" href="<?php echo $this->webroot ?>cdrreports/export_log_down?key=<?php echo base64_encode($item['CdrExportLog']['id']); ?>">
                                                <i class="icon-download-alt"></i>
                                            </a>
                                        <?php endif; ?>
                                        <?php if ($item['CdrExportLog']['status'] != 0): ?> 
                                            <a title="Show Files" class="show_files" data-id="<?php echo base64_encode($item['CdrExportLog']['id']); ?>" href="###">
                                                <i class="icon-list"></i>
                                            </a>
                                        <?php endif; ?>
                                        <?php if ($item['CdrExportLog']['status'] != 4 and $item['CdrExportLog']['status'] != 5): ?> 
                                            <a title="Kill Job" href="<?php echo $this->webroot ?>cdrreports/export_log_kill?key=<?php echo base64_encode($item['CdrExportLog']['id']); ?>">
                                                <i class="icon-remove-circle"></i>
                                            </a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <div class="separator bottom row-fluid">
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