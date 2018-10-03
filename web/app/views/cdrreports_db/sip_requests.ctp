<style>
    .popup-content {
        display: none;
    }

    .pcap-table {
        width: 100%;
    }

    .field {
        width: 100%;
    }
</style>
<ul class="breadcrumb">
    <li><?php __('You are here')?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>cdrreports_db/sip_packet">
            <?php __('Tools') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>cdrreports_db/sip_packet">
            <?php echo __('SIP Requests') ?></a></li>
</ul>
<div class="clearfix"></div>
<div class="innerLR">
    <div class="widget widget-tabs widget-body-white">
        <div class="widget-head">
            <ul>
                <li><a href="<?php echo $this->webroot; ?>cdrreports_db/sip_packet" class="glyphicons left_arrow"><i></i><?php __('SIP PACKET'); ?></a></li>
                <li class="active"><a href="<?php echo $this->webroot; ?>cdrreports_db/sip_requests" class="glyphicons right_arrow"><i></i><?php __('SIP Requests'); ?></a></li>
            </ul>
        </div>
        <div class="widget-body">
            <?php
            if (!count($requestQueries))
            {
                ?>
                <div>
                    <br /><h3 class="msg center"><?php echo __('no data found') ?></h3>
                </div>
            <?php } else {?>
            <div  id="refresh_div">
                <style type="text/css">
                    .form .value, .list-form .value{text-align:left;}
                </style>
                <div class="clearfix"></div>
                <div class="wrapper small">
                    <table class="table large template table-bordered table-striped table-primary cdr_table" style="table-layout: auto; min-width: 0px;">
                        <thead>
                        <tr>
                            <th>Requested On</th>
                            <th>Complete Time</th>
                            <th>Call-id</th>
                            <th>Requested By</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        foreach ($requestQueries as $requestQuery) {
                            ?>
                            <tr>
                                <td><?php echo $requestQuery['queued_time'];?></td>
                                <td><?php echo $requestQuery['complete_time'];?></td>
                                <td><?php echo $requestQuery['callid'];?></td>
                                <td><?php echo $requestQuery['username'];?></td>
                                <td><?php echo empty($requestQuery['segment']) ? $requestQuery['msg'] : 'Success';?></td>
                                <td>
                                    <?php if(!empty($requestQuery['segment'])) {?>
                                        <a title="PCAP Download" target="_blank" class="download" href="<?php echo $this->webroot.'cdrreports_db/get_pcap_file?segment='.base64_encode($requestQuery['segment']);?>">
                                            <i class="icon-download"></i>
                                        </a>
                                        <a title="View in CloudShark" target="_blank" class="cloudshark" href="http://<?php echo $cloudSharkUrl; ?>/api/v1/<?php echo $cloudSharkToken; ?>/open?url=<?php echo $domainName.'cdrreports_db/get_pcap_file?segment='.base64_encode($requestQuery['segment']);?>">
                                            <i class="icon-bullseye"></i>
                                        </a>
                                        <!--                                    <a title="Copy Link" target="_blank" class="copy" href="--><?php //echo $this->webroot; ?><!--cdrreports_db/sip_copy?link=http://--><?php //echo $cloudSharkUrl; ?><!--/view?url=--><?php //echo $requestQuery['url'];?><!--">-->
                                        <!--                                        <i class="icon-edit"></i>-->
                                        <!--                                    </a>-->
                                        <a title="Copy Link" class="copy" href="javascript:void(0);" data-href="<?php echo $requestQuery['scheme'].'://'.$requestQuery['switch_ip'].'/cdrreports_db/get_pcap_file?segment='.base64_encode($requestQuery['segment']);?>">
                                            <i class="icon-edit"></i>
                                        </a>
                                        <!--a title="Send Email" class="pcap_email" href="javascript:void(0)" data-href="<?php echo $requestQuery['url'];?>">
                                        <i class="icon-user"></i>
                                    </a-->
                                    <?php } ?>
                                    <?php if(isset($requestQuery['query_key']) && !empty($requestQuery['query_key'])): ?>
                                        <!--a class="redo" onclick="myconfirm('Do you want redo this request? ',this);return false;" href="/cdrreports_db/redoSipRequest/<?php echo base64_encode($requestQuery['db_id']); ?>" title="Redo" aria-describedby="qtip-153">
                                        <i class="icon-sun"></i>
                                    </a-->
                                    <?php endif; ?>
                                    <!--a class="delete" onclick="myconfirm('Are you sure to delete it? ',this);return false;" href="/cdrreports_db/deleteSipRequest/<?php echo base64_encode($requestQuery['db_id']); ?>" title="Delete" aria-describedby="qtip-153">
                                        <i class="icon-remove"></i>
                                    </a-->
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                        </tbody>
                    </table>
                    <div class="separator"></div>
                </div>
                <?php }?>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/clipboard.js/1.5.16/clipboard.min.js"></script>

<script>
    $(function () {
        $(".pcap_email").click(function () {

            let popupContent = "<form action='<?php echo $this->webroot;?>cdrreports_db/send_pcap' id='pcap_form' method='post'>" +
                "<table class='pcap-table'>" +
                "<tbody>" +
                "<tr>" +
                "<td>From</td>" +
                "<td>" +
                "<select name='from'>" +
                <?php foreach ($mailSenders as $key => $item): ?>
                "<option value='<?php echo $key; ?>'><?php echo $item; ?></option>" +
                <?php endforeach; ?>
                "</select>" +
                "</td>" +
                "</tr>" +
                "<tr>" +
                "<td>Subject</td>" +
                "<td>" +
                "<input type='hidden' name='pcap_url' value='" + $(this).data('href') + "'/>" +
                "<input class='field validate[required]' id='pcap_subject' type='text' name='subject'></td>" +
                "</tr>" +
                "<tr>" +
                "<td>Emails</td>" +
                "<td><input class='field validate[required]' id='pcap_emails' type='text' name='emails'></td>" +
                "</tr>" +
                "<tr>" +
                "<td>Content</td>" +
                "<td><textarea class='field' name='pcap_content'></textarea></td>" +
                "</tr>" +
                "</tbody>" +
                "</table>" +
                "</form>";

            bootbox.dialog(popupContent, [{
                "label" : "Cancel"
            }, {
                "label" : "Send",
                "class" : "btn-success",
                "callback": function() {
                    let pcapEmails = $("#pcap_emails").val();
                    if($("#pcap_subject").val().length == 0) {
                        jQuery.jGrowl('Subject should be not empty',{'theme':'jmsg-error'});
                    } else if(pcapEmails.length == 0) {
                        jQuery.jGrowl('Emails should be not empty',{'theme':'jmsg-error'});
                    } else if(pcapEmails.match(/^(([^<>()[\]\\,;:\s@\"]+(\.[^<>()[\]\\,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))*/) == null) {
                        jQuery.jGrowl('Invalid email address',{'theme':'jmsg-error'});
                    } else if(pcapEmails.match(/^((([a-zA-Z0-9\+_\.-]+)@([\da-zA-Z\.-]+)\.([a-zA-Z\.]{2,6}\;))*(([a-zA-Z0-9\+_\.-]+)@([\da-zA-Z\.-]+)\.([a-zA-Z\.]{2,6})))$/) == null) {
                        jQuery.jGrowl('Email and split by ;',{'theme':'jmsg-error'});
                    } else {
                        $('#pcap_form').submit();
                    }
                }
            }]);
        });


        $("a.copy").click(function () {

            let link = $(this).data('href');
            let popupContent = '<input id="foo" style="width: 465px; max-width: 465px;border-radius: 5px 0px 0px 5px;" type="text" readonly="readonly" value="' + link + '">'+
                '<button style="margin-bottom: 10px;margin-left: -1px;" class="btn" data-clipboard-target="#foo">'+
                'Copy'+
                '</button>';

            bootbox.dialog(popupContent, [{
                "label" : "Close"
            }]);

            let clipboard = new Clipboard('.btn');

            clipboard.on('success', function(e) {
                showMessages_new("[{field:'', code: '201', msg: 'Copied'}]");

                e.clearSelection();
            });

            clipboard.on('error', function(e) {
                console.error('Action:', e.action);
                console.error('Trigger:', e.trigger);
            });
        });
    });
</script>