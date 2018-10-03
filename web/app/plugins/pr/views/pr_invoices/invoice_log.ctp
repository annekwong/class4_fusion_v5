<script src="<?php echo $this->webroot?>js/ajaxTable.js" type="text/javascript"></script>

<ul class="breadcrumb">
    <li><?php __('You are here')?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?= $this->webroot?>pr/pr_invoices/invoice_log"><?php __('Log') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?= $this->webroot?>pr/pr_invoices/invoice_log">
            <?php echo __('Invoice Log') ?></a></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Invoice Log') ?></h4>

</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
    <a href="javascript:void(0)" id="refresh_btn" class="link_btn btn btn-primary btn-icon glyphicons refresh">
        <i></i><?php echo __('Refresh',true);?>
    </a>
    <a href="<?php echo $this->webroot ?>pr/pr_invoices/view/1" class="link_back_new btn btn-icon btn-inverse glyphicons circle_arrow_left">
        <i></i><?php echo __('goback',true);?>
    </a>
</div>
<div class="clearfix"></div>

<div class="innerLR">

    <div class="widget widget-heading-simple widget-body-white">


        <div class="widget-body">
            <div class="filter-bar">

                <form action="" method="get">
                    <!-- Filter -->
                    <div>
                        <label><?php __('Time')?>:</label>
                        <input id="start_date" class="input in-text wdate " value="<?php if (isset($get_data['time']))
                        {
                            echo $get_data['time'];
                        } ?>" type="text" readonly="" onfocus="WdatePicker({dateFmt: 'yyyy-MM-dd HH:mm:ss'});" name="time">

                    </div>
                    <!-- Filter -->



                    <div>
                        <button name="submit" class="btn query_btn"><?php __('Query')?></button>
                    </div>
                    <!-- // Filter END -->


                </form>
            </div>
            <?php
            $data = $p->getDataArray();
            if (empty($data)):
                ?>
                <h2 class="msg center"><br /><?php echo __('no_data_found',true);?></h2>
            <?php else: ?>

                <div class="clearfix"></div>
                <table class="list footable table table-striped tableTools table-bordered  table-white table-primary">
                    <thead>
                    <tr>
                        <th></th>
                        <th><?php echo $appCommon->show_order('InvoiceLog.id', __('Invoice Request ID', true)) ?></th>
                        <th><?php echo $appCommon->show_order('InvoiceLog.start_time', __('Start Time', true)) ?></th>
                        <th><?php echo $appCommon->show_order('InvoiceLog.end_time', __('End Time', true)) ?></th>
                        <th><?php __('Status')?></th>
                        <th><?php __('Progress')?></th>
                        <th><?php __('Action')?></th>
                    </tr>
                    </thead>
                    <?php
                    $count = count($data);
                    for($i = 0; $i < $count; $i++):
//                $all_total = 0;
//                foreach($data[$i][0]['invoices'] as $item) {
//                    $all_total += $item[0]['total_amount'];
//                }
//                if ($all_total > 0):
                        ?>
                        <tbody id="resInfo<?php echo $i?>">
                        <tr class="row-<?php echo $i%2 +1;?>">
                            <td>
                                <img id="image<?php echo $i; ?>"  onclick="pull('<?php echo $this->webroot?>',this,<?php echo $i;?>)"  class="jsp_resourceNew_style_1"  src="<?php echo $this->webroot?>images/+.gif" title="<?php  __('View All')?>"/>
                            </td>
                            <td>#<?php echo $data[$i][0]['log_id'] ?></td>
                            <td><?php echo $data[$i][0]['start_time'] ?></td>
                            <td><?php echo $data[$i][0]['end_time'] ?></td>
                            <td><?php echo $status[$data[$i][0]['status']]; ?></td>
                            <td><?php echo count($data[$i][0]['invoices']) ?>/<?php echo $data[$i][0]['cnt'] ?></td>
                            <td>
                                <a class="send_invoices" title="Sent" href="javascript:void(0)">
                                    <i class="icon-envelope"></i>
                                </a>
                            </td>
                        </tr>
                        <tr style="height:auto">
                            <td colspan="7">
                                <div id="ipInfo<?php echo $i?>" class=" jsp_resourceNew_style_2" style="padding:5px;display:none;">
                                    <table class="table tableTools table-bordered  table-white">
                                        <tr>
                                            <td><?php __('Carrier')?></td>
                                            <td><?php __('Amount')?></td>
                                            <td><?php __('Invoice Period')?></td>
                                            <td><?php __('Invoice Due Date')?></td>
                                            <td><?php __('Status')?></td>
                                            <td><?php __('Action')?></td>
                                        </tr>
                                        <?php foreach($data[$i][0]['invoices'] as $invoice): ?>
                                            <tr>
                                                <td><?php echo $invoice[0]['name']; ?></td>
                                                <td><?php echo $invoice[0]['total_amount']; ?></td>
                                                <td><?php echo $invoice[0]['invoice_start']; ?> ~ <?php echo $invoice[0]['invoice_end']; ?></td>
                                                <td><?php echo $invoice[0]['due_date']; ?></td>
                                                <td><?php echo $sub_status[$invoice[0]['status']]; ?></td>
                                                <td>
                                                    <a class="send_invoice" invoice_id="<?php echo $invoice[0]['invoice_id']; ?>" title="Sent" href="javascript:void(0)">
                                                        <i class="icon-envelope"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </table>
                                </div>
                            </td>
                        </tr>
                        </tbody>
                        <?php
//            endif;
                    endfor;
                    ?>
                </table>
                <div class="row-fluid separator">
                    <div class="pagination pagination-large pagination-right margin-none">
                        <?php echo $this->element('page'); ?>
                    </div>
                </div>
                <div class="clearfix"></div>
            <?php endif; ?>
        </div>
    </div>
</div>
<script>
    function validateEmail(email) {
        var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(email);
    }

    $(function() {
        var $send_invoice = $('.send_invoice');
        var $send_invoices = $('.send_invoices');

        $send_invoices.click(function() {
            var arr = new Array();
            var $this = $(this);
            var $invoices = $this.parents('tr').next().find('a.send_invoice');
            $invoices.each(function() {
                arr.push($(this).attr('invoice_id'));
            });
            if (arr.length) {
                bootbox.prompt("Please enter email", function(result){
                    if (result) {
                        if (validateEmail(result)) {
                            $.ajax({
                                'url' : '<?php echo $this->webroot ?>pr/pr_invoices/mail_invoice',
                                'type' : 'POST',
                                'dataType' : 'text',
                                'data' : {
                                    'ids[]': arr,
                                    'email': result
                                },
                                'success' : function(data) {
                                    let response = JSON.parse(data);

                                    if (response.status == 1) {
                                        jGrowl_to_notyfy('Email sent successfully!', {theme: 'jmsg-success'});
                                        setTimeout(function () {
                                            window.location.reload();
                                        }, 1000);
                                    } else {
                                        jGrowl_to_notyfy('Failed to send mail to carrier. Please contact with support.', {theme: 'jmsg-error'});
                                    }
                                }
                            });
                        } else {
                            jGrowl_to_notyfy('Please enter a valid email!', {theme: 'jmsg-error'});
                        }
                    }
                });
            }
        });

        $('#refresh_btn').click(function() {
            window.location.reload();
        });
    });
</script>