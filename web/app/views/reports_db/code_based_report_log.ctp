<style>
    .mail_template_subject,.mail_template_cc,.email_input{
        max-width: none;
        width: 60%;
    }
</style>
<ul class="breadcrumb">
    <li><?php __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>cdrreports_db/summary_reports">
        <?php __('Statistics') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot . $this->params['url']['url'] ?>">
        <?php __('Code Based Report Log') ?></a></li>
</ul>
<style>
    .formError {
        top: 5px !important;
    }
    .refresh {
        z-index: 1000;
    }
</style>

<button style="margin-top: 10px; margin-right: 15px;"
        class="btn btn-icon btn-primary pull-right glyphicons refresh" onclick="location.reload();">
    <i></i><?php __('Refresh'); ?>
</button>
<div class="separator bottom"></div>
<div class="innerLR" style="padding-top: 30px;">
    <div class="widget widget-tabs widget-body-white">
        <div class="widget-head">
            <ul>
                <li>
                    <a href="<?php echo $this->webroot; ?>reports_db/code_based_report" class="glyphicons list">
                        <i> </i>
                        <?php __('Report Request') ?>
                    </a>
                </li>
                <li class="active">
                    <a href="<?php echo $this->webroot; ?>reports_db/code_based_report_log" class="glyphicons book_open">
                        <i></i>
                        <?php __('Report Log') ?>
                    </a>
                </li>
            </ul>
        </div>

        <div class="widget-body">
            <div class="filter-bar">
                <form method="get">
                    <div>
                        <label><?php __('Tiggered Time') ?>:</label>
                        <input id="start_date" class="input in-text wdate " value="" type="text" readonly="" onfocus="WdatePicker({dateFmt: 'yyyy-MM-dd HH:mm:ss'});" name="time">
                        ~
                        <input id="end_date" class="input in-text wdate " value="" type="text" readonly="" onfocus="WdatePicker({dateFmt: 'yyyy-MM-dd HH:mm:ss'});" name="end_time">
                    </div>

                    <div>
                        <button name="submit" class="btn query_btn"><?php __('Query') ?></button>
                    </div>
                    <!-- // Filter END -->

                </form>
            </div>

            <?php if (empty($this->data)): ?>
            <div class="msg center">
                <br />
                <h2><?php echo __('no_data_found') ?></h2></div>
            <?php else: ?>

            <table  class="scroll_table list nowrap with-fields footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
                <thead>
                <tr>
                    <th><?php __('Job ID') ?></th>
                    <th><?php __('Triggered Time') ?></th>
                    <th><?php __('Start Time') ?></th>
                    <th><?php __('End Time') ?></th>
                    <th><?php __('Status') ?></th>
                    <th><?php __('Action') ?></th>
                </tr>
                </thead>

                <tbody>
                    <?php foreach ($this->data as $item): ?>
                        <tr>
                            <td><?php echo $item['CodeBasedReportLog']['id']; ?></td>
                            <td><?php echo date('Y-m-d H:i:s', strtotime($item['CodeBasedReportLog']['export_start_time'])); ?></td>
                            <td><?php echo date('Y-m-d', strtotime($item['CodeBasedReportLog']['search_start_date'])); ?></td>
                            <td><?php echo date('Y-m-d', strtotime($item['CodeBasedReportLog']['search_end_date'])); ?></td>
                            <td><?php echo $item['CodeBasedReportLogStatus']['status_value']; ?></td>
                            <td>
                                <?php if ($item['CodeBasedReportLogStatus']['status_value'] == 'Done'): ?>
                                    <a title="Download" class="cbr_download_link" href="javascript:void(0)" url="<?php echo $this->webroot . 'reports_db/download_cbr/' . base64_encode($item['CodeBasedReportLog']['id']); ?>">
                                        <i class="icon-download-alt"></i>
                                    </a>
                                    <a title="Send Email" class="send-email" href="javascript:void(0)" url="#" data-file-id="<?php echo $item['CodeBasedReportLog']['id'] ?>">
                                        <i class="icon-envelope"></i>
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div class="row-fluid separator">
                <div class="pagination pagination-large pagination-right margin-none">
                    <?php echo ''; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){

        $(".send-email").click(function () {
            var $this = $(this);
            var fileId = $(this).attr('data-file-id');

            if (!$('#dd').length) {
                $(document.body).append("<div id='dd'></div>");
            }
            var $dd = $('#dd');
            var table_html = '<table class="list table dynamicTable tableTools table-bordered  table-primary table-white">\
<tbody><tr><input type="text" name="send_email_to" id="send_email_to"></td></tr></tbody>\
</table>';
            $dd.html("");
            $dd.append(table_html);
            $dd.dialog({
                'title': 'Send Email',
                'width': '450px',
                'buttons': [{text: "Send", "class": "btn btn-primary", click: function() {
                    var $that = $(this);
                    $.ajax({
                        url: "<?php $this->webroot ?>send_cbr_email_ajax",
                        data: {
                            email: $('#send_email_to').val(),
                            fileId: fileId
                        },
                        method: 'post',
                        success: function () {
                            $that.dialog("close");
                        }
                    });
//                    window.open(link,'_blank');
                }}, {text: "Cancel", "class": "btn btn-inverse", click: function() {
                    $(this).dialog("close");
                }}]

            });
        });

        $(".cbr_download_link").click(function() {
            var $this = $(this);
            var $domain = "<?php echo $_SERVER['HTTP_HOST'] ?>";
            var link = $this.attr('url');
//            console.log(link);
            var show_link = $domain + $this.attr('url');
            if (!$('#dd').length) {
                $(document.body).append("<div id='dd'></div>");
            }
            var $dd = $('#dd');
            var table_html = '<table class="list table dynamicTable tableTools table-bordered  table-primary table-white">\
<tbody><tr><td><a  id="download_link" href="javascript:void(0)">' + show_link + '</a></td></tr></tbody>\
</table>';
            $dd.html("");
            $dd.append(table_html);
            $dd.dialog({
                'title': 'Download Link',
                'width': '450px',
                'buttons': [{text: "Download", "class": "btn btn-primary", click: function() {
                    $(this).dialog("close");
                    window.open(link,'_blank');
                }}, {text: "Cancel", "class": "btn btn-inverse", click: function() {
                    $(this).dialog("close");
                }}]

            });
        });
    });
</script>

<script type="text/javascript" src="<?php echo $this->webroot ?>js/ckeditor_full/ckeditor.js"></script>


