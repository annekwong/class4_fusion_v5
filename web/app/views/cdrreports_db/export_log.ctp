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
    <li><a href="<?php echo $this->webroot ?>cdrreports_db/export_log">
            <?php __('CDRs list') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>cdrreports_db/export_log">
            <?php __('CDR Export Log') ?></a></li>
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
        class="btn btn-icon btn-primary pull-right glyphicons refresh">
    <i></i><?php __('Refresh'); ?>
</button>
<div class="separator bottom"></div>
<div class="innerLR" style="padding-top: 30px;">
    <div class="widget widget-tabs widget-body-white">
        <div class="widget-head">
            <ul>
                <?php if ($session->read('login_type') == 3): ?>

                    <?php echo $this->element('report_db/cdr_report/cdr_report_user_tab', array('active' => 'export_log2')) ?>
                <?php else: ?>
                    <li>
                        <a href="<?php echo $this->webroot; ?>cdrreports_db/summary_reports" class="glyphicons list">
                            <i> </i>
                            <?php __('CDR Search') ?>
                        </a>
                    </li>
                    <li class="active">
                        <a href="<?php echo $this->webroot; ?>cdrreports_db/export_log" class="glyphicons book_open">
                            <i></i>
                            <?php __('CDR Export Log') ?>
                        </a>
                    </li>

                    <!--                    <li>-->
                    <!--                        <a href="--><?php //echo $this->webroot; ?><!--cdrreports_db/consolidated_cdr" class="glyphicons book_open">-->
                    <!--                            <i></i>-->
                    <!--                            --><?php //__('Consolidated Cdr') ?>
                    <!--                        </a>-->
                    <!--                    </li>-->
                    <!--        <li>
                                <a href="<?php echo $this->webroot; ?>cdrreports_db/mail_send_log" class="glyphicons no-js e-mail" >
                                    <i></i>
                                    Mail CDR Log
                                </a>
                            </li>-->
                <?php endif; ?>
            </ul>
        </div>

        <div class="widget-body">


            <div class="filter-bar">
                <form method="get">
                    <div>
                        <label><?php __('Tiggered Time') ?>:</label>
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
                        <th><?php __('Triggered Start Time') ?></th>
                        <th><?php __('Triggered End Time') ?></th>
                        <th><?php __('Start Time') ?></th>
                        <th><?php __('End Time') ?></th>
                        <th><?php __('Status') ?></th>
                        <th><?php __('Progress') ?>(h)</th>
                        <th><?php __('Number of Rows') ?></th>
                        <th><?php __('File Size') ?></th>
                        <th><?php __('Action') ?></th>
                    </tr>
                    </thead>

                    <tbody>
                    <?php foreach ($this->data as $item):?>
                        <tr <?php if ($item['CdrExportLog']['status'] != 4 and $item['CdrExportLog']['status'] != 5) { echo "class='tr-update' data-id='{$item['CdrExportLog']['id']}'"; } ?>>
                            <td>#<?php echo $item['CdrExportLog']['id']; ?></td>
                            <td>
                                <?php echo $item['CdrExportLog']['export_time']; ?>
                            </td>
                            <td><?php echo $item['CdrExportLog']['finished_time']; ?></td>
                            <td><?php echo $item['CdrExportLog']['cdr_start_time']; ?></td>
                            <td><?php echo $item['CdrExportLog']['cdr_end_time']; ?></td>
                            <td>
                                <?php echo $status[$item['CdrExportLog']['status']]; ?>
                            </td>
                            <td>
                                <?php echo $item['CdrExportLog']['completed_days'] ?: 0; ?>/<?php echo $item['CdrExportLog']['total_days'] ?: 0; ?>
                            </td>
                            <td>
                                <?php echo $item['CdrExportLog']['file_rows']; ?>
                            </td>
                            <td>
                                <?php if ($item['CdrExportLog']['status'] == 4 || $item['CdrExportLog']['status'] == -2): ?>
                                    <?php
//                                    $file_name = realpath(ROOT . '/../download/cdr_download/' . str_replace('.csv','.tar.bz2',$item['CdrExportLog']['file_name']));
                                    $file_name = realpath(Configure::read('database_export_path') . DS . "cdr_download" . DS . $item['CdrExportLog']['file_name']);

                                    echo $appCommon->to_readable_size(@filesize($file_name));
//                                    echo $appCommon->to_readable_size(@filesize(realpath(ROOT . '/../download/cdr_download/' . $item['CdrExportLog']['file_name'] . '.bz2')));
                                    ?>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($item['CdrExportLog']['status'] == 4): ?>
                                    <a title="Progress Info" target="_blank" href="<?php echo $this->webroot ?>cdrreports_db/get_process_info?key=<?php echo base64_encode($item['CdrExportLog']['id']); ?>">
                                        <i class="icon-info"></i>
                                    </a>
                                    <?php if(file_exists(Configure::read('database_export_path') . DS . "cdr_download" . DS . $item['CdrExportLog']['file_name'])): ?>
                                        <a title="Download" class="cdr_download_link" target="_blank" href="<?php echo $this->webroot ?>cdrreports_db/download_csv/<?php echo $item['CdrExportLog']['id']; ?>">
                                            <i class="icon-download-alt"></i>
                                        </a>
                                    <?php endif; ?>
                                    <a title="Reprocess" onclick="return myconfirm('<?php __('Are you sure to download again?'); ?>',this);" href="<?php echo $this->webroot ?>cdrreports_db/reprocess/<?php echo base64_encode($item['CdrExportLog']['id']); ?>">
                                        <i class="icon-reply"></i>
                                    </a>
                                    <?php if($_SESSION['login_type'] != 2): ?>
                                        <a title="<?PHP __('Send Mail'); ?>" class="send_mail_btn" href="#MyModalSendMail" data-toggle="modal" data-value="<?php echo $item['CdrExportLog']['id']; ?>" data-mail="<?php echo $item['CdrExportLog']['send_mail'];  ?>" >
                                            <i class="icon-envelope"></i>
                                        </a>
                                    <?php endif; ?>
                                <?php elseif($item['CdrExportLog']['status'] == 5): ?>
                                    <a title="Reprocess" onclick="return myconfirm('<?php __('Are you sure to download again?'); ?>',this);" href="<?php echo $this->webroot ?>cdrreports_db/reprocess/<?php echo base64_encode($item['CdrExportLog']['id']); ?>">
                                        <i class="icon-reply"></i>
                                    </a>
                                <?php elseif($item['CdrExportLog']['status'] == 3 && $item['CdrExportLog']['completed_days'] > 0): ?>
                                    <a class="views"  title="View" onclick="get_view('<?php echo base64_encode($item['CdrExportLog']['id']);  ?>',this);" href="javascript:void(0)">
                                        <i class="icon-align-justify"></i>
                                    </a>
                                <?php elseif($item['CdrExportLog']['status'] == -2): ?>
                                    <a title="<?php __('Continue Job'); ?>" href="<?php echo $this->webroot ?>cdrreports_db/reprocess/<?php echo base64_encode($item['CdrExportLog']['id']); ?>">
                                        <i class="icon-play"></i>
                                    </a>
                                    <a title="Download" class="cdr_download_link" href="javascript:void(0)" url="<?php echo $this->webroot ?>cdr_download/index/<?php echo base64_encode("key=".base64_encode($item['CdrExportLog']['id'])); ?>">
                                        <i class="icon-download-alt"></i>
                                    </a>
                                <?php elseif($item['CdrExportLog']['status'] < 0): ?>
                                    <a target="_blank" title="Error File"  href="<?php echo $this->webroot ?>cdrreports_db/export_download_error?key=<?php echo base64_encode($item['CdrExportLog']['id']); ?>">
                                        <i class="icon-download"></i>
                                    </a>
                                    <a title="Reprocess" onclick="return myconfirm('<?php __('Are you sure to download again?'); ?>',this);" href="<?php echo $this->webroot ?>cdrreports_db/reprocess/<?php echo base64_encode($item['CdrExportLog']['id']); ?>">
                                        <i class="icon-reply"></i>
                                    </a>

                                <?php else: ?>
                                    <a title="<?php __('Stop Job'); ?>" href="<?php echo $this->webroot ?>cdrreports_db/export_log_stop?key=<?php echo base64_encode($item['CdrExportLog']['id']); ?>">
                                        <i class="icon-pause"></i>
                                    </a>
                                    <a title="Kill Job" href="<?php echo $this->webroot ?>cdrreports_db/export_log_kill?key=<?php echo base64_encode($item['CdrExportLog']['id']); ?>">
                                        <i class="icon-remove-circle"></i>
                                    </a>

                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
                <div class="row-fluid separator">
                    <div class="pagination pagination-large pagination-right margin-none">
                        <?php echo $this->element('xpage'); ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<form method="post" action="<?php echo $this->webroot; ?>cdrreports_db/send_cdr_mail" id="test-form">
    <div id="MyModalSendMail" class="modal hide"  style="min-width: 800px;">
        <div class="modal-header">
            <button data-dismiss="modal" class="close" type="button">&times;</button>
            <h3><?php __('CDR Email'); ?></h3>
        </div>
        <div class="modal-body">
            <table class="form table dynamicTable tableTools table-bordered  table-white">
                <colgroup>
                    <col width="10%">
                    <col width="80%">
                    <col width="10%">
                </colgroup>
                <tbody>
                <?php if ($_SESSION['login_type'] == 1): ?>
                    <tr>
                        <td class="align_right"><?php __('From') ?>:</td>
                        <td colspan="2">
                            <?php echo $form->input('download_cdr_from',array('type' => 'select','label' => false,
                                'div' => false,'options' => $mail_senders,'class' => 'mail_template_from','selected' => $download_cdr_template['download_cdr_from'])); ?>
                        </td>
                    </tr>
                <?php else: ?>
                    <input type="hidden" name="data[download_cdr_from]" value="default">
                <?php endif; ?>
                <tr>
                    <td class="align_right"><?php __('To') ?>:</td>
                    <td colspan="2">
                        <input type="text" name="data[send_mail]" class="input in-text in-input validate[required,custom[email]] email_input" data-prompt-position="centerRight:0"  />
                    </td>
                </tr>
                <?php if ($_SESSION['login_type'] == 1): ?>
                    <tr>
                        <td></td>
                        <td colspan="2">
                            <input type="radio"  name="data[send_type]" class="send_type" value="1" checked /><?php __('User Default Template'); ?>&nbsp;&nbsp;
                            <input type="radio"  name="data[send_type]" class="send_type" value="2" /><?php __('Specify Your Email Content'); ?>
                        </td>
                    </tr>
                    <tr class="specify_email_btn hide">
                        <td class="align_right"><?php __('Cc') ?></td>
                        <td colspan="2">
                            <?php echo $form->input('download_cdr_cc',array('type' => 'text','label' => false,
                                'div' => false,'class' => 'validate[custom[email]] mail_template_cc','value' => $download_cdr_template['download_cdr_cc'])); ?>
                        </td>
                    </tr>
                    <tr class="specify_email_btn hide">
                        <td class="align_right"><?php echo __('subject') ?> </td>
                        <td colspan="2">
                            <?php echo $form->input('download_cdr_subject',array('type' => 'text','label' => false,
                                'div' => false,'class' => 'mail_template_subject','value' => $download_cdr_template['download_cdr_subject'])); ?>
                        </td>
                    </tr>
                    <tr class="specify_email_btn hide">
                        <td class="align_right"><?php echo __('content') ?></td>
                        <td>
                            <?php echo $form->input('download_cdr_content',array('type' => 'textarea','label' => false,
                                'div' => false,'class' => 'mail_template_content','value' => $download_cdr_template['download_cdr_content'])); ?>
                        </td>
                        <td class="mail_content_tags">
                            <h4><i class="icon-tags"></i><?php __('Tags'); ?>:</h4>
                            <span class="btn btn-block btn-default">{download_link}</span>
                        </td>
                    </tr>
                <?php else: ?>
                    <input type="hidden" name="data[send_type]" value="1">
                <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="modal-footer">
            <input type="hidden" name="log_id" />
            <input type="submit" class="btn btn-primary submit_btn" value="<?php __('Submit'); ?>">
            <a href="javascript:void(0)" data-dismiss="modal" class="btn btn-default"><?php __('Close'); ?></a>
        </div>
    </div>
</form>

<?php if ($_SESSION['login_type'] == 1): ?>
    <script type="text/javascript" src="<?php echo $this->webroot ?>js/ckeditor_full/ckeditor.js"></script>
<?php endif; ?>
<script type="text/javascript">

    function get_view(id,obj){

        if($(obj).parents('tr').eq(0).next().find('.viewss').length == 0){

            $.ajax({
                'url':'<?php echo $this->webroot ?>cdrreports_db/get_view?key='+id,
                'dataType':'html',
                'type':'post',
                'data':{'id':id},
                'beforeSend':function(){
                    //$(obj).parents('td').eq(0).find('.td_loading').show();
                    //$(obj).parents('td').eq(0).find('.views').hide();
                },
                'success':function(data){
                    //$(obj).parents('td').eq(0).find('.td_loading').hide();
                    //$(obj).parents('td').eq(0).find('.views').show();
                    $(obj).parents('tr').eq(0).after("<tr><td colspan='8'>"+data+"</td></tr>");
                },
                'error': function(XMLHttpRequest, textStatus, errorThrown) {
                    if(XMLHttpRequest.status == 500){

                    }
                    // $(obj).parents('td').eq(0).find('.td_loading').hide();
                    //$(obj).parents('td').eq(0).find('.views').show();
                    //alert(XMLHttpRequest.status);
                }
            });


        }else{
            $(obj).parents('tr').eq(0).next().remove();
        }
    }

    function sendTypeChange(){
        var sendType = $(this).val();
        if (sendType == 1){
            $(".specify_email_btn").hide();
        }else{
            $(".specify_email_btn").show();
        }
    }

    $(function() {

        <?php if ($_SESSION['login_type'] == 1): ?>
        $("#MyModalSendMail").find('.send_type').change(sendTypeChange);
        CKEDITOR.replace('download_cdr_content');
        $('.mail_content_tags').find('span').click(function(){
            var $tag_value = $(this).html();
            var $this_textarea_id = $(this).closest('tr').find('textarea').attr('id');
            var editor = CKEDITOR.instances[$this_textarea_id];
            editor.insertHtml( $tag_value );
        });
        <?php endif; ?>

        $('.refresh').click(function(){
            location.reload();
        });

        $("#test-form").validationEngine({promptPosition : "centerRight", scroll: false});

//        $(".cdr_download_link").click(function() {
//            var $this = $(this);
//            var $domain = "<?php //echo $_SERVER['HTTP_HOST'] ?>//";
//            var link = $this.attr('url');
//            console.log(link);
//            var show_link = $domain + $this.attr('url');
//            if (!$('#dd').length) {
//                $(document.body).append("<div id='dd'></div>");
//            }
//            var $dd = $('#dd');
//            var table_html = '<table class="list table dynamicTable tableTools table-bordered  table-primary table-white">\
//<tbody><tr><td><a  id="download_link" href="javascript:void(0)">' + show_link + '</a></td></tr></tbody>\
//</table>';
//            $dd.html("");
//            $dd.append(table_html);
//            $dd.dialog({
//                'title': 'Download Link',
//                'width': '450px',
//                'buttons': [{text: "Download", "class": "btn btn-primary", click: function() {
//                    $(this).dialog("close");
//                    window.open(link,'_blank');
//                }}, {text: "Cancel", "class": "btn btn-inverse", click: function() {
//                    $(this).dialog("close");
//                }}]
//
//            });
//        });


        $("a.send_mail_btn").click(function(){
            var $this = $(this);
            $("#MyModalSendMail").find('.email_input').val($this.data('mail'));
            $("#MyModalSendMail").find('input[name="log_id"]').val($this.data('value'));
            console.log($("#MyModalSendMail").find('input[name="log_id"]').val());
        });
    });
</script>

<script>
    function updateRows() {
        var indexes = [];

        $('.tr-update').each(function (key, item) {
            indexes.push($(item).attr('data-id'));
        });

        if (indexes.length > 0) {
            $.ajax({
                url: "<?php echo $this->webroot; ?>cdrreports_db/get_export_rows",
                type: "POST",
                format: 'json',
                data: {
                    "ids[]": indexes
                },
                success: function (response) {
                    if (response.length > 0) {
                        let data = JSON.parse(response);

                        for (var item in data) {
                            data[item].CdrExportLog.completed_days = data[item].CdrExportLog.completed_days ? data[item].CdrExportLog.completed_days : 0;
                            data[item].CdrExportLog.total_days = data[item].CdrExportLog.total_days ? data[item].CdrExportLog.total_days : 0;
                            $('.tr-update[data-id=' + data[item].CdrExportLog.id + ']').find("td:eq(2)").html(data[item].CdrExportLog.finished_time);
                            $('.tr-update[data-id=' + data[item].CdrExportLog.id + ']').find("td:eq(5)").html(data[item].CdrExportLog.textStatus);
                            $('.tr-update[data-id=' + data[item].CdrExportLog.id + ']').find("td:eq(6)").html(data[item].CdrExportLog.completed_days + "/" + data[item].CdrExportLog.total_days);
                            $('.tr-update[data-id=' + data[item].CdrExportLog.id + ']').find("td:eq(7)").html(data[item].CdrExportLog.file_rows);
                            $('.tr-update[data-id=' + data[item].CdrExportLog.id + ']').find("td:eq(8)").html(data[item].CdrExportLog.file_size);
                            let textAction = '';

                            if (data[item].CdrExportLog.status == 4) {
                                textAction += '<a title="Progress Info" target="_blank" href="<?php echo $this->webroot ?>cdrreports_db/get_process_info?key=' + btoa(data[item].CdrExportLog.id) + '">' +
                                    '<i class="icon-info"></i>' +
                                    '</a> ' +
                                    '<a title="Download" class="cdr_download_link" target="_blank" href="<?php echo $this->webroot ?>cdrreports_db/download_csv/' + data[item].CdrExportLog.id + '">' +
                                    '<i class="icon-download-alt"></i>' +
                                    '</a> ' +
                                    '<a title="Reprocess" onclick="return myconfirm(\'<?php __('Are you sure to download again?'); ?>\',this);" href="<?php echo $this->webroot ?>cdrreports_db/reprocess/' + btoa(data[item].CdrExportLog.id) + '">' +
                                    '<i class="icon-reply"></i>' +
                                    '</a> ';
                                <?php if($_SESSION['login_type'] != 2): ?>
                                textAction += '<a title="<?PHP __('Send Mail'); ?>" class="send_mail_btn" href="#MyModalSendMail" data-toggle="modal" data-value="<?php echo $item['CdrExportLog']['id']; ?>" data-mail="' + data[item].CdrExportLog.send_mail + '" >' +
                                    '<i class="icon-envelope"></i>' +
                                    '</a> ';
                                <?php endif; ?>
                            } else if (data[item].CdrExportLog.status == 5) {
                                textAction += '<a title="Reprocess" onclick="return myconfirm(\'<?php __('Are you sure to download again?'); ?>\',this);" href="<?php echo $this->webroot ?>cdrreports_db/reprocess/' + btoa(data[item].CdrExportLog.id) + '">' +
                                    '<i class="icon-reply"></i>' +
                                    '</a> ';
                            } else if (data[item].CdrExportLog.status == 3 && data[item].CdrExportLog.completed_days > 0) {
                                textAction += '<a class="views"  title="View" onclick="get_view(\'' + btoa(data[item].CdrExportLog.id) + '\',this);" href="javascript:void(0)">' +
                                    '<i class="icon-align-justify"></i>' +
                                    '</a> ';
                            } else if (data[item].CdrExportLog.status == -2) {
                                textAction += '<a title="<?php __('Continue Job'); ?>" href="<?php echo $this->webroot ?>cdrreports_db/reprocess/' + btoa(data[item].CdrExportLog.id) + '">' +
                                    '<i class="icon-play"></i>' +
                                    '</a> ';
                            } else if (data[item].CdrExportLog.status < 0) {
                                textAction += '<a target="_blank" title="Error File"  href="<?php echo $this->webroot ?>cdrreports_db/export_download_error?key=' + btoa(data[item].CdrExportLog.id) + '">' +
                                    '<i class="icon-download"></i>' +
                                    '</a> ' +
                                    '<a title="Reprocess" onclick="return myconfirm(\'<?php __('Are you sure to download again?'); ?>\',this);" href="<?php echo $this->webroot ?>cdrreports_db/reprocess/' + btoa(data[item].CdrExportLog.id) + '">' +
                                    '<i class="icon-reply"></i>' +
                                    '</a>';
                            } else {
                                textAction += '<a title="<?php __('Stop Job'); ?>" href="<?php echo $this->webroot ?>cdrreports_db/export_log_stop?key=' + btoa(data[item].CdrExportLog.id) + '">' +
                                    '<i class="icon-pause"></i>' +
                                    '</a> ' +
                                    '<a title="Kill Job" href="<?php echo $this->webroot ?>cdrreports_db/export_log_kill?key=' + btoa(data[item].CdrExportLog.id) + '">' +
                                    '<i class="icon-remove-circle"></i>' +
                                    '</a> ';
                            }

                            $('.tr-update[data-id=' + data[item].CdrExportLog.id + ']').find("td:eq(9)").html(textAction);
                        }
                    }
                }
            });
        }
    }

    $(document).ready(function () {
        setInterval(function () {
            updateRows();
        }, 5000);
    });
</script>

