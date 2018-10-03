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
    <a class="list-export btn btn-primary btn-icon glyphicons repeat" onclick="return myconfirm('<?php __('Are you sure to test again'); ?>?',this);"
       href="<?php echo $this->webroot; ?>check_route/test_again/<?php echo $this->params['pass'][0] ?>">
        <i></i><?php __('Test Again'); ?>
    </a>
    <a class="list-export btn btn-primary btn-icon glyphicons envelope">
        <i></i><?php __('Trouble Ticket'); ?>
    </a>
    <a class="list-export btn btn-primary btn-icon glyphicons file_export" target="_blank"
       href="<?php echo $this->webroot; ?>check_route/create_result_pdf/<?php echo $this->params['pass'][0] ?>">
        <i></i><?php __('Export'); ?>
    </a>
    <a class="btn btn-icon btn-inverse glyphicons circle_arrow_left" href="<?php echo $this->webroot; ?>check_route"><i></i><?php __('Back')?></a>
</div>
<div class="clearfix"></div>
<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">

        <div class="widget-body">
            <div class="widget">
                <div class="widget-head"><h4 class="heading"><?php __('Test ID') ?>:&nbsp;&nbsp;<?php echo $egress_test_info['EgressTest']['id']; ?></h4></div>
                <div class="widget-body">
                    <table class="form footable table table-striped  tableTools table-bordered  table-white table-primary default footable-loaded">
                        <colgroup>
                            <col width="20%">
                            <col width="30%">
                            <col width="20%">
                            <col width="30%">
                        </colgroup>
                        <tr>
                            <td class="align_right padding-r20"><?php echo __('Create By') ?>:</td>
                            <td><?php echo $egress_test_info['EgressTest']['create_by']; ?></td>
                            <td class="align_right padding-r20"><?php echo __('Initiated') ?></td>
                            <td><?php echo $egress_test_info['EgressTest']['total_calls']; ?>&nbsp;<?php __('calls'); ?></td>
                        </tr>
                        <tr>
                            <td class="align_right padding-r20"><?php echo __('Create On') ?>:</td>
                            <td><?php echo $egress_test_info['EgressTest']['start_time']; ?></td>
                            <td class="align_right padding-r20"><?php echo __('Answered') ?></td>
                            <td><?php echo intval($egress_test_info['EgressTest']['success_calls']);?>&nbsp;<?php __('calls'); ?></td>
                        </tr>
                        <tr>
                            <td class="align_right padding-r20"><?php echo __('Trunk') ?>:</td>
                            <td><?php echo $egress_test_info['Resource']['alias']; ?></td>
                            <td></td>
                            <td></td>
<!--                            <td class="align_right padding-r20">--><?php //echo __('Destinations') ?><!--</td>-->
<!--                            <td>--><?php //echo $egress_test_info['EgressTest']['code_name']; ?><!--</td>-->
                        </tr>
                    </table>
                </div>
            </div>
            <div class="clearfix"></div>
            <!-- Table -->
            <table class=" footable table table-striped tableTools table-bordered  table-white table-primary" id="result_table">
                <!-- Table heading -->
                <thead>
                <tr>
                    <th><?php __('Calling No'); ?></th>
                    <th><?php __('Called No')?></th>
                    <th><?php __('PDD') ?></th>
                    <th><?php __('Start Time') ?></th>
                    <th><?php __('Answer Time') ?></th>
                    <th><?php __('Duration') ?></th>
                    <th><?php __('Call Result') ?></th>
                    <th><?php __('SIP File') ?></th>
                </tr>
                </thead>
                <tbody>
                <?php
                if (!empty($egress_test_info)):
                    foreach ($egress_test_info['EgressTestResult'] as $item): ?>
                        <tr>
                            <td><?php echo $item['ani']; ?></td>
                            <td><?php echo $item['dnis']; ?></td>
                            <td><?php echo $item['pdd']; ?></td>
                            <td><?php echo $item['start_time']; ?></td>
                            <td><?php echo $item['answer_time']; ?></td>
                            <td><?php echo $item['duration']; ?></td>
                            <td><?php echo $item['call_result']; ?></td>
                            <td>
                                <?php if (!$item['cdr_time']): ?>
                                    <a title="<?php __('Download'); ?>" class="download_sip" data-value="<?php echo base64_encode($item['id']); ?>"
                                       href="javascript:void(0)">
                                        <i class="icon-file"></i></a>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php
                    endforeach;
                endif;
                ?>

                </tbody>
                <!-- // Table body END -->

            </table>

            <div class="clearfix"></div>
        </div>
    </div>

</div>
<!--<scirpt type="text/javascript" src="--><?php //$this->webroot ?><!--js/jquery.center.js"></scirpt>-->
<script type="text/javascript">
    $(function() {
        $("#result_table").dataTable({
            paging: false,
        });

        $("#result_table").find('.download_sip').click(function(){
            var result_id = $(this).data('value');
            $.ajax({
                type: "POST",
                url: "<?php echo $this->webroot; ?>check_route/get_sip/"+result_id,
                dataType:'json',
                success: function(data){
                    if (data.status == 1){
                        window.open(data.msg);
                    }else{
                        jGrowl_to_notyfy(data.msg,{'theme':'jmsg-error'});
                    }
                }
            });
        });
    });

</script>
