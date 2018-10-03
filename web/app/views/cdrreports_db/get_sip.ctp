<style>
    .curl-table {
        width: 100%;
        max-width: 100%;
        table-layout: fixed;
        word-wrap: break-word;
    }

    .curl-table tbody tr td {
        padding: 5px;
    }

    .curl-table tr th{
        width: 50%;
    }
</style>
<?php if (!$this->params['isAjax']): ?>
    <ul class="breadcrumb">
        <li><?php __('You are here')?></li>
        <li class="divider"><i class="icon-caret-right"></i></li>
        <li><?php __('Statistics') ?></li>
        <li class="divider"><i class="icon-caret-right"></i></li>
        <li><?php echo __('SIP PACKET Search') ?></li>
        <li class="divider"><i class="icon-caret-right"></i></li>
        <li><?php echo __('Get SIP') ?></li>
    </ul>
    <div class="clearfix"></div>
<div class="innerLR">

    <div class="widget widget-heading-simple widget-body-white">
        <div class="widget-body">
            <?php endif; ?>
            <table class="form footable table tableTools table-bordered  table-white default ">
                <colgroup>
                    <col width="40%">
                    <col width="60%">
                </colgroup>
                <tr>
                    <td class="align_right padding-r10">
                        <?php __('Search Process'); ?>
                    </td>
                    <td>
                        <p style="color: red" class="process_msg">query init</p>
                    </td>
                </tr>
                <tr>
                    <td class="align_right padding-r10">
                        <?php __('Search Status'); ?>
                    </td>
                    <td>
                        <p style="color: red" class="result_msg"></p>
                        <input id="refresh_pcap" class="generate_btn btn btn-primary" style="width: 120px; background: #0bbae3; border: 1px solid; display: none;" type="button"  value="<?php __('Refresh'); ?>" />
                    </td>
                </tr>
                <tr>
                    <td class="align_right padding-r10">
                        <?php __('Download PCAP'); ?>
                    </td>
                    <td>
                        <input type="button" disabled="disabled" class="download_btn btn btn-primary" style="width: 120px;cursor:not-allowed" value="<?php __('Download'); ?>" />
                        <input id="download_pcap" type="hidden"/>
                    </td>
                </tr>
                <tr>
                    <td class="align_right padding-r10">
                        <?php __('View in CloudShark'); ?>
                    </td>
                    <td>
                        <input type="button" disabled="disabled" class="download_btn btn btn-primary" style="width: 120px;cursor:not-allowed" value="<?php __('CloudShark'); ?>" />
                        <input id="cloudshark_pcap" type="hidden"/>
                    </td>
                </tr>
                <!--                <tr>-->
                <!--                    <td class="align_right padding-r10">-->
                <!--                        --><?php //__('Generate PCAP Public Download Link'); ?>
                <!--                    </td>-->
                <!--                    <td class="generate_td">-->
                <!--                        <input disabled="disabled" class="generate_btn btn btn-primary" style="width: 120px;cursor:not-allowed" type="button"  value="--><?php //__('Generate'); ?><!--" />-->
                <!--                    </td>-->
                <!--                </tr>-->
            </table>
            <?php if (!$this->params['isAjax']): ?>
        </div>
    </div>
</div>
<?php endif; ?>
<?php if($debug): ?>
    <div class="curl-debug">
        <table class="curl-table" border="0" cellspacing="0" cellpadding="0">
            <thead>
            <tr>
                <th>CURL Request</th>
                <th>Response</th>
            </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>
<?php endif; ?>

<script type="text/javascript">
    var refreshIntervalId = -1;
    var outData = [];
    function refreshPcap() {
        $.ajax({
            'url': '<?php echo $this->webroot ?>cdrreports_db/refreshResult',
            'type': 'post',
            'data': outData,
            'dataType': 'json',
            'success': function (data) {
                $("p.process_msg").html(data.data.msg);
                if (data.self_status == 1) {
                    clearInterval(refreshIntervalId);
                    $("p.result_msg").html('<?php __('Successfully'); ?>').css('color', 'green');
                    $(".download_btn").removeAttr('disabled').css('cursor', 'pointer');
                    $("#download_pcap").val("<?php echo $this->webroot.'cdrreports_db/get_pcap_file?segment='?>" + btoa(data.download_url));
                    $("#cloudshark_pcap").val("http://<?php echo $cloudSharkUrl; ?>/api/v1/<?php echo $cloudSharkToken; ?>/open?url=" + "<?php echo $domainName.'cdrreports_db/get_pcap_file?segment='?>" + btoa(data.download_url));
                    $("#link_pcap").attr('href', data.download_url).text(data.download_url).css({
                        visibility: 'visible',
                        display: 'block'
                    });

                } else if (data.self_status == 2) {
                    $("p.result_msg").html('<?php __('Waiting'); ?>').css('color', 'blue');
                } else {
                    $("p.result_msg").html('<?php __('Failed'); ?>').css('color', 'red');
                }

                $.each(data.queries, function(element, value) {
                    $(".curl-table tbody").append("" +
                        "<tr>" +
                        "<td>" + value.request + "</td>" +
                        "<td>" + value.response + "</td>" +
                        "</tr>");
                });
            }
        });
    }

    $(function(){
        $.ajax({
            'url' : '<?php echo $this->webroot ?>cdrreports_db/ajax_get_sip',
            'type' : 'POST',
            'data' : <?php
            $obj = json_encode($url);
            echo $obj;
            ?>,
            'dataType' : 'json',
            'success' : function(data) {
                outData = data;
                $("p.process_msg").html(data.msg);
                if (data.self_status == 1){
                    $("p.result_msg").html('<?php __('Successfully'); ?>').css('color', 'green');
                    $(".download_btn").removeAttr('disabled').css('cursor','pointer');
                    $("#download_pcap").val("<?php echo $this->webroot.'cdrreports_db/get_pcap_file?segment='?>" + btoa(data.download_url));
                    $("#cloudshark_pcap").val("http://<?php echo $cloudSharkUrl; ?>/api/v1/<?php echo $cloudSharkToken; ?>/open?url=" + "<?php echo $domainName.'cdrreports_db/get_pcap_file?segment='?>" + btoa(data.download_url));
                    $("#link_pcap").attr('href', data.download_url).text(data.download_url).css({
                        visibility: 'visible',
                        display: 'block'
                    });
                    $("#GetSIP").click();
                    $("#refresh_pcap").hide();
                } else if (data.self_status == 2) {
                    $("p.result_msg").html('<?php __('Waiting'); ?>').css('color', 'blue');
                    $("#GetSIP").click();
                    refreshIntervalId = setInterval(refreshPcap, 2000);
                } else {
                    $("p.result_msg").html('<?php __('Failed'); ?>').css('color', 'red');
                    jGrowl_to_notyfy(data.msg, {theme: 'jmsg-error'});
                }

                $.each(data.queries, function(element, value) {
                    $(".curl-table tbody").append("" +
                        "<tr>" +
                        "<td>" + value.request + "</td>" +
                        "<td>" + value.response + "</td>" +
                        "</tr>");
                });
            }
        });

        $(".download_btn").click(function () {
            let url =  $(this).next().val();
            window.open(url);
        });
    });

    $(document).ready(function() {
        $(".cake-sql-log:first").before($(".curl-debug"));
    });
</script>
