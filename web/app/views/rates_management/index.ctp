<ul class="breadcrumb">
    <li><?php __('You are here'); ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>rates_management/index">
        <?php __('Tools') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot . $this->params['url']['url'] ?>">
        <?php echo __('Auto Rate Upload', true); ?></a></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Auto Rate Upload', true); ?></h4>
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>
<div class="clearfix"></div>
<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="clearfix"></div>
        <div class="widget-head">
            <ul>
                <li <?php if (!strcmp($this->params['pass'][0], 'unprocessed')): ?>class="active"<?php endif; ?>>
                    <a class="glyphicons book_open" href="<?php echo $this->webroot; ?>rates_management/index/unprocessed">
                        <i></i>
                        <?php __('Unprocessed Decks') ?>
                    </a>
                </li>
                <li <?php if (!strcmp($this->params['pass'][0], 'processed')): ?>class="active"<?php endif; ?>>
                    <a class="glyphicons book_open" href="<?php echo $this->webroot; ?>rates_management/index/processed">
                        <i></i>
                        <?php __('Processed Decks') ?>
                    </a>
                </li>
                <li <?php if (!strcmp($this->params['pass'][0], 'unrecognized')): ?>class="active"<?php endif; ?>>
                    <a class="glyphicons book_open" href="<?php echo $this->webroot; ?>rates_management/index/unrecognized">
                        <i></i>
                        <?php __('Unrecognized Decks') ?>
                    </a>
                </li>
            </ul>
        </div>
        <div class="widget-body">
            <?php
            if (count($rateManagements) == 0)
            {
                ?>
                <br />
                <h2 class="msg center"><?php echo __('no data found') ?></h2>
                <?php
            }
            else
            {
                ?>
                <div class="clearfix"></div>
                <table class="footable table table-striped tableTools table-bordered  table-white table-primary">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th><?php __('Received Time') ?></th>
                        <th><?php __('From Address') ?></th>
                        <th># <?php __('of Attachment') ?></th>
                        <th><?php __('Success/Fail Upload') ?></th>
                        <th><?php __('Action') ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($rateManagements as $rateManagement): ?>
                        <tr>
                            <td>#<?php echo $rateManagement['RateManagement']['id'] ?></td>
                            <td><?php echo $rateManagement['RateManagement']['received_time'] ?></td>
                            <td><?php echo $rateManagement['RateManagement']['from_address'] ?></td>
                            <td><?php echo count($rateManagement['RateManagementDetail']) ?></td>
                            <td class="main_result"></td>
                            <td>
                                <a class="btn btn-primary" href="#myModal<?php echo $rateManagement['RateManagement']['id'] ?>" data-toggle="modal"><i class="icon-eye-open icon-white"></i> <?php __('View') ?></a>
                                <a class="btn btn-primary expandtr" href="javascript:void(0)"><i class="icon-circle-arrow-down icon-white"></i> <?php __('Expand') ?></a>
                                <div id="myModal<?php echo $rateManagement['RateManagement']['id'] ?>" class="modal hide">
                                    <div class="modal-header">
                                        <button data-dismiss="modal" class="close" type="button">&times;</button>
                                        <h3><?php echo $rateManagement['RateManagement']['mail_subject'] ?></h3>
                                    </div>
                                    <div class="modal-body">
                                        <p><?php echo str_replace("\r\n", "<br>", $rateManagement['RateManagement']['mail_content']); ?></p>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr class="slidetr hide">
                            <td colspan="9" style="padding-left:0px;padding-right:0px;">
                                <table class="table-striped" style="width:100% ">
                                    <tr>
                                        <th><?php __('ID') ?></th>
                                        <th><?php __('File Name') ?></th>
                                        <th><?php __('File Size') ?></th>
                                        <th><?php __('Rate Table') ?></th>
                                        <th><?php __('Status') ?></th>
                                        <th><?php __('Upload Time') ?></th>
                                        <th><?php __('Action') ?></th>
                                    </tr>
                                    <?php foreach ($rateManagement['RateManagementDetail'] as $detail): ?>
                                        <tr>
                                            <td>#<?php echo $detail['id']; ?></td>
                                            <td><?php echo basename($detail['file_path']); ?></td>
                                            <td><?php echo $appCommon->to_readable_size(@filesize($detail['file_path'])); ?></td>
                                            <td><?php echo $detail['rate_table_id'] ? $detail['rate_table_name'] : 'UNMATCHED'; ?></td>
                                            <td class="single_status" status="<?php echo $detail['status']; ?>"><?php echo $status[$detail['status']]; ?></td>
                                            <td><?php echo $detail['upload_time']; ?></td>
                                            <td>
                                                <?php if ($detail['log_id']): ?>
                                                    <!--                                                        <a class="btn btn-primary view_result"  controlId="--><?php //echo $detail['id'] ?><!--" href="javascript:void(0)"><i class="icon-list icon-white"></i> --><?php //__('View Result') ?><!--</a>-->
                                                <?php endif; ?>
                                                <?php //if (!$detail['rate_handler_id || !$detail['rate_table_id): ?>
                                                <a class="btn btn-primary move_to_process" controlId="<?php echo $detail['id'] ?>"  data-toggle="modal" href="#myModal_move" title="Moved To Processed"><i class="icon-pencil icon-white"></i> <?php __('Move') ?></a>
                                                <?php //endif; ?>
                                                <?php if (file_exists($detail['file_path'])): ?>
                                                    <a target="_blank" class="btn btn-primary" href="<?php echo $this->webroot . 'rates_management/download/' . base64_encode($detail['id']); ?>"><i class="icon-download-alt icon-white"></i> <?php __('Download') ?></a>
                                                <?php else: ?>
                                                    <a class="btn btn-primary" onclick="jGrowl_to_notyfy('<?php __('file is not exist'); ?>',{'theme':'jmsg-error'}); return false;"><i class="icon-download-alt icon-white"></i> <?php __('Download') ?></a>
                                                <?php endif; ?>
                                                <?php if ($detail['rate_table_id']): ?>
                                                    <?php if (file_exists($detail['file_path'])): ?>
                                                        <a class="btn btn-primary upload_btn" controlId="<?php echo $detail['id'] ?>" controlRateTableId="<?php echo $detail['rate_table_id'] ?>" data-toggle="modal" href="#myModal_upload"><i class="icon-upload icon-white"></i> <?php __('Upload') ?></a>
                                                    <?php else: ?>
                                                        <a class="btn btn-primary" onclick="jGrowl_to_notyfy('<?php __('file is not exist'); ?>',{'theme':'jmsg-error'}); return false;"><i class="icon-upload icon-white"></i> <?php __('Upload') ?></a>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </table>
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
                <div class="clearfix"></div>
            <?php } ?>
        </div>
    </div>
</div>
<form action="<?php echo $this->webroot; ?>rates_management/move_to_process" method="post">

    <div id="myModal_move" class="modal hide">
        <div class="modal-header">
            <button data-dismiss="modal" class="close" type="button">&times;</button>
            <h3><?php __('Rate Table'); ?></h3>
        </div>
        <div class="modal-body">
            <select name="rate_table_id">
                <?php foreach ($rate_table as $rate_table_id => $rate_table_name): ?>
                    <option value="<?php echo $rate_table_id ?>"><?php echo $rate_table_name; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="modal-footer">
            <input type="hidden" name="detail_id" id="move_detail_id" />
            <input type="submit" class="btn btn-primary" value="<?php __('Submit'); ?>">
            <a href="javascript:void(0)" data-dismiss="modal" class="btn btn-default"><?php __('Close'); ?></a>
        </div>

    </div>
</form>


<form action="<?php echo $this->webroot; ?>rates_management/upload" method="post">
    <div id="myModal_upload" class="modal hide" style="width: auto;height: auto;">
        <div class="modal-body" id="upload_body">
        </div>
        <div class="modal-footer">
            <input type="hidden" name="detail_id" id="upload_detail_id" />
            <input type="submit" class="btn btn-primary" value="<?php __('Submit'); ?>">
            <a href="javascript:void(0)" data-dismiss="modal" class="btn btn-default"><?php __('Close'); ?></a>
        </div>

    </div>
</form>
<script type="text/javascript">
    $(function () {

        $(".main_result").each(function(){
            var $this = $(this);
            var success_num = 0;
            var failed_num = 0;
            $this.parent().next().find(".single_status").each(function(){
                var single_status = $(this).attr('status');
                if (single_status == '2'){
                    success_num ++;
                }else if(single_status == '3'){
                    failed_num ++;
                }
            });
            $this.html(success_num + '/' + failed_num);
        });

        $(".expandtr").click(function () {
            $slidetr = $(this).parent().parent().next().fadeToggle("slow", "linear");
        });
        $(".move_to_process").click(function(){
            var detail_id = $(this).attr('controlId');
            $("#move_detail_id").val(detail_id);
        });

        $(".upload_btn").click(function(){
            var rate_table_id = $(this).attr('controlRateTableId');
            var detail_id = $(this).attr('controlId');
            $("#upload_detail_id").val(detail_id);
            $("#upload_body").load("<?php echo $this->webroot?>rates_management/ajax_get_upload_parameters/" + rate_table_id,{});
        });

        $("#with_header").live('click',function(){
            var checked = $(this).attr('checked');
            $("#start_from_line").hide();
            if(checked == 'checked')
            {
                $("#start_from_line").show();
            }
        });

    });

</script>


