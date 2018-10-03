<style>
    a.show_downloads {
        float: right;
    }

    a.show_downloads:after {
        clear: both;
    }
</style>

<ul class="breadcrumb">
    <li><?php __('You are here')?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Log') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Rate Delivery Log') ?></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Rate Delivery Log') ?></h4>
    <div class="buttons pull-right">
    </div>
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>
<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-body">
            <?php if (!count($this->data)): ?>
                <div class="msg center">
                    <br />
                    <h2><?php echo __('no_data_found') ?></h2>
                </div>
            <?php else: ?>
                <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
                    <thead>
                    <tr>
                        <th><?php __('Sent Area') ?></th>
                        <th><?php __('Rate Table') ?></th>
                        <th><?php __('Create Time') ?></th>
                        <th><?php __('Type') ?></th>
                        <th><?php __('Recipient Count') ?></th>
                        <th><?php __('Email Count') ?></th>
                        <th><?php __('Process') ?></th>
                        <th><?php __('File') ?></th>
                        <th><?php __('Action') ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($this->data as $key => $items)
                    {
                        ?>
                        <tr data-log="<?php echo $items['RateSendLog']['id'];?>">
                            <td><?php echo $items['RateSendLog']['sent_area'] ?></td>
                            <td><?php echo $items['RateTable']['name'] ?></td>
                            <td><?php echo $items['RateSendLog']['create_time'] ?></td>
                            <td>
                                <?php echo isset($items['RateSendLog']['download_method']) && $items['RateSendLog']['download_method'] == 1 ? 'Attachment' : 'Link'; ?>
                            </td>
                            <td>
                                <a href="javascript:void(0)" class="recipient_count">
                                    <?php echo $items['RateSendLog']['recipient_count'] ?>
                                </a>
                            </td>
                            <td><?php echo $items['RateSendLog']['emails_count'] ?></td>
                            <td><?php echo $items['RateSendLog']['total_records'] ? $items['RateSendLog']['completed_records'] . "/" . $items['RateSendLog']['total_records'] : "" ?></td>
                            <td>
                                <?php if($items['RateSendLog']['file']): ?>
                                    <a target="_blank" href="<?php echo $this->webroot; ?>rates/download_send_rate_file?flg=<?php echo base64_encode($items['RateSendLog']['id']) ?>"><i class="icon-file-text"></i></a>
                                <?php else: ?>
                                    --
                                <?php  endif; ?>
                            </td>
                            <td>
                                <a  data-target="#accordion-<?php echo $key;?>" class="view_details clickable" title="<?php __('Detail'); ?>" href="javascript:void(0)">
                                    <i class="icon-list"></i>
                                </a>
                                <a title="<?php __('Resend')?>" href="<?php echo $this->webroot; ?>rates/send_rate_record/0/<?php echo base64_encode($items['RateSendLog']['id']); ?>">
                                    <i class="icon-envelope"></i>
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="9" style="padding:0;">
                                <div id="accordion-<?php echo $key;?>" style="display:none; padding: 0 10px;" >
                                    <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
                                        <thead>
                                        <tr>
                                            <th class="background-primary"><?php __('Trunk') ?></th>
                                            <th class="background-primary"><?php __('Carrier') ?></th>
                                            <th class="background-primary"><?php __('Sent On') ?></th>
                                            <?php if(isset($items['RateSendLog']['download_method']) && $items['RateSendLog']['download_method'] == 2 ):?>
                                                <th class="background-primary"><?php __('Download Deadline') ?></th>
                                            <?php endif;?>
                                            <th class="background-primary"><?php __('Delivered To') ?></th>
                                            <th class="background-primary"><?php __('Status') ?></th>
                                            <th class="background-primary"><?php __('Action') ?></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
                <div class="row-fluid separator">
                    <div class="pagination pagination-large pagination-right margin-none">
                        <?php echo $this->element('xpage'); ?>
                    </div>
                </div>
                <div class="clearfix"></div>
            <?php endif; ?>
        </div>



        <div class="clearfix"></div>
    </div>
</div>

<div id="myModal_about" class="modal hide" style="width:400px;margin-left:-200px;">
    <div class="modal-header">
        <button data-dismiss="modal" class="close" type="button">&times;</button>
        <h3><?php __('Download Info'); ?></h3>
    </div>
    <table  id="download-table" class="footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">

        <thead>
        <tr>
            <th><?php __('IP'); ?></th>
            <th><?php __('Date'); ?></th>
        </tr>

        </thead>

        <tbody>
        </tbody>
    </table>
</div>
</div>
<script type="text/javascript">

    $(document).on('click', '.modal-backdrop', function (event) {
        bootbox.hideAll()
    });

    $(function(){
        $('.recipient_count').on('click', function(){
            $(this).closest('tr').find('.view_details').trigger('click')
        })
        $('.view_details').on('click', function(){
            let target = $(this).attr('data-target');
            let log_id = $(this).closest('tr').attr('data-log');
            let row = "";
            let error = "";
            let tbody = "";
            if(!$(target + ' tbody').children().length){
                $.ajax({
                    'url': '<?php echo $this->webroot; ?>rates/rate_log_detail/'+log_id,
                    'dataType': 'json',
                    'success': function(data) {
                        if(data){

                            $.each(data, function (i, obj) {
                                row = "";
                                if(obj['RateSendLog']['send_type'] != 1){
                                    row += "<td>" +(obj['Resource']['alias'] !='null' && obj['Resource']['alias'] !=null ? obj['Resource']['alias'] : '')+ "</td>";
                                    row += "<td>" +(obj['Client']['name'] !='null' && obj['Client']['name'] !=null ? obj['Client']['name'] : '')+ "</td>";
                                }else{
                                    row += "<td></td><td></td>";
                                }
                                row += "<td>" +(obj['RateSendLogDetail']['sent_on'] !='null' && obj['RateSendLogDetail']['sent_on'] !=null ? obj['RateSendLogDetail']['sent_on'] : '')+ "</td>";
                                if(typeof obj['RateSendLog']['download_method'] !=='undefined' &&  obj['RateSendLog']['download_method'] == 2){
                                    row += "<td>" +obj['RateSendLog']['download_deadline'] +"</td>";
                                }
                                row += "<td>" +(obj['RateSendLogDetail']['send_to'] !='null' ? obj['RateSendLogDetail']['send_to'] : '')+ "</td>";
                                if (obj['RateSendLogDetail']['status'] == 'Downloaded'){
                                    row += "<td>"+obj['RateSendLogDetail']['status'] +"<a href='javascript:void(0)' class='show_downloads' data-value='" + obj['RateSendLogDetail']['id'] + "'><i class='icon-info-sign'></i></a></td>";
                                }else{
                                    row += "<td>" +obj['RateSendLogDetail']['status'] +"</td>";
                                }

                                row += "<td>";
                                row += "<a title='Resend' href='javascript:void(0)' data-id = '" +obj['RateSendLogDetail']['id']+ "' class='resend_email'><i class='icon-envelope'></i></a>";

                                if (obj['RateSendLogDetail']['orig_status'] == 2) {
                                    console.log(obj['RateSendLogDetail']['error'].toString());
                                    let message = obj['RateSendLogDetail']['error'].toString();
                                    row += "<a title='View Error' href='javascript:void(0)' onclick='$.jGrowl(\"" + message + "\", {theme: \"jmsg-error\"});'><i class='icon-bullseye'></i></a>";
                                }
                                row += "</td>";

                                tbody+="<tr>" + row + "</tr>";
                            });
                            $(target + ' tbody').html(tbody);
                            $(target).slideToggle();

                        }

                        jQuery("a.resend_email").click(function () {
                            resend(jQuery(this).data('id'));
                        });
                        jQuery("a.show_downloads").click(function () {
                            show_download(jQuery(this).data('value'));
                        });

                    }
                });
            }else{
                $(target).slideToggle();
            }

        });

        function resend(id){
            jQuery.post('<?php echo $this->webroot; ?>rates/resend_email', {
                id: id
            }, function (result) {console.log(result);
                if(result.status){
                    jGrowl_to_notyfy(result.msg,{theme:'jmsg-success'});
                }else{
                    jGrowl_to_notyfy(result.msg,{theme:'jmsg-error'});
                }
            }, "json");
        }

        function show_download(detailId){
            jQuery.post('<?php echo $this->webroot; ?>rates/getFileDownloads', {
                detailId: detailId
            }, function (result) {
                let data = jQuery.parseJSON(result);
                jQuery("#download-table tbody").html('');
                jQuery.each(data, function (e, value) {

                    jQuery("#download-table tbody").prepend("<tr><td>" + value.ip + "</td><td>" + value.time + "</td></tr>");
                    jQuery('#myModal_about').modal('show');
                });
            });
        }

        $('.show_error').click(function (){
            notyfy({
                text: $(this).data('value') ? $(this).data('value') : 'Error message is missing!',
                type: 'error',
                layout: 'center'
            });
        });

        $('.show_error').click(function (){
            notyfy({
                text: $(this).data('value'),
                type: 'error',
                layout: 'center'
            });
        });
    })


</script>