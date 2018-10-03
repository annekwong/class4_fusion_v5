<style type="text/css">

    input[type="text"] {
        width: 220px;
    }
    .tr_unread{
        font-weight:bold;
    }
</style>

<ul class="breadcrumb">
    <li><?php __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Statistics') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Finance') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo $this->pageTitle; ?></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo $this->pageTitle; ?></h4>

    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
    <a class="btn btn-primary btn-icon glyphicons list"  href="<?php echo $this->webroot; ?>pr/pr_invoices/dispute_note_list">
        <i></i> <?php __('Dispute Note List')?></a>
</div>
<div class="clearfix"></div>


<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-head">
        <ul>
            <li>
                <a class="glyphicons no-js left_arrow" href="<?php echo $this->webroot ?>pr/pr_invoices/view/0"><i></i>
                    <?php echo __('Auto Client Invoice') ?></a>
            </li>
            <li>
                <a class="glyphicons no-js left_arrow" href="<?php echo $this->webroot ?>pr/pr_invoices/view/0"><i></i>
                    <?php echo __('Manual Client Invoice') ?></a>
            </li>
            <li class="active"><a class="glyphicons no-js right_arrow" href="<?php echo $this->webroot ?>pr/pr_invoices/vendor_invoice">
                    <i></i><?php echo __('Vendor Invoice') ?></a>
            </li>
            <li><a class="glyphicons no-js right_arrow" href="<?php echo $this->webroot ?>pr/pr_invoices/incoming_invoice"><i></i><?php echo __('Old Vendor Invoice') ?></a></li>
        </ul>
        </div>
        <div class="widget-body">
            <div class="filter-bar">
                <form method="get">
                    <!-- Filter -->
                    <div>
                        <label><?php __('Carrier'); ?>:</label>
                        <select name="client" >
                            <option value="">All</option>
                            <?php foreach ($clients as $client_id => $client_name): ?>
                                <option value="<?php echo $client_id; ?>"><?php echo $client_name; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <?php echo $this->element('common/log_query_datetime',array('datetime' => 1,'label' => __('Calculated On',true),)) ?>
                    <div>
                        <button name="submit" class="btn query_btn"><?php __('Query')?></button>
                    </div>
                    <!-- // Filter END -->
                </form>
            </div>
            <div class="clearfix"></div>
            <div id="report_box">
                <?php if (!count($this->data)): ?>
                    <div class="msg center">
                        <br />
                        <h2>
                            <?php echo __('no_data_found', true); ?>
                        </h2>
                    </div>
                <?php else: ?>
                    <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary default footable-loaded">
                        <thead>
                        <tr>
                            <th><?php __('Report By Day')?></th>
                            <th>
                                <?php echo $appCommon->show_order('create_time', __('Carrier Name', true)) ?>
                            </th>
                            <th><?php echo $appCommon->show_order('invoice_time', __('Calculated On', true)) ?></th>
                            <th><?php __('Billing Duration')?></th>
                            <th><?php echo $appCommon->show_order('invoice_time', __('System Minute', true)) ?></th>
                            <th><?php echo $appCommon->show_order('invoice_time', __('System Total', true)) ?></th>
                            <th><?php echo $appCommon->show_order('invoice_time', __('Billed Minute', true)) ?></th>
                            <th><?php echo $appCommon->show_order('invoice_time', __('Billed Total', true)) ?></th>
                            <th><?php __('Status')?></th>
                            <th><?php __('Action')?></th>
                        </tr>
                        </thead>
                        <tbody class="alert_tbody">
                        <?php foreach($this->data as $item): ?>
                            <tr>
                                <td>
                                    <a href="javascript:void(0)" class="show_detail" data-value="<?php echo $item['VendorInvoice']['vendor_invoice_id'];?>">
                                        <i class="icon-plus" style="margin-right: 20px"></i>
                                    </a>
                                </td>
                                <td><?php echo @$clients[$item['VendorInvoice']['client_id']] ?></td>
                                <td><?php echo $item['VendorInvoice']['invoice_time'] ?></td>
                                <td>
                                    <small>
                                        <?php echo $item['VendorInvoice']['billing_start'] ?><br>
                                        <?php echo $item['VendorInvoice']['billing_end'] ?>
                                    </small>
                                </td>
                                <td><?php echo $item['VendorInvoice']['system_mins'] ?></td>
                                <td><?php echo $item['VendorInvoice']['system_total'] ?></td>
                                <td><?php echo $item['VendorInvoice']['billing_mins'] ?></td>
                                <td><?php echo $item['VendorInvoice']['billing_total'] ?></td>
                                <td><?php echo $status[ $item['VendorInvoice']['status']];?></td>
                                <td>
                                    <a href="javascript:void(0)" title="<?php __('View Report by Day'); ?>" onclick="$(this).closest('tr').find('.show_detail').click();">
                                        <i class="icon-list"></i></a>
                                    <a href="<?php echo $this->webroot; ?>pr/pr_invoices/edit_vendor_invoice/<?php echo  base64_encode($item['VendorInvoice']['vendor_invoice_id']); ?>"
                                       title="<?php __('Submit Billed Amount'); ?>" >
                                        <i class="icon-edit"></i></a>
                                    <?php if(file_exists($item['VendorInvoice']['file_path'])): ?>
                                        <a target="_blank" href="<?php echo $this->webroot; ?>pr/pr_invoices/download_vendor_invoice_pdf/<?php echo  base64_encode($item['VendorInvoice']['vendor_invoice_id']); ?>"
                                           title="<?php __('Download Vendor Invoice'); ?>" >
                                            <i class="icon-file"></i></a>
                                    <?php endif; ?>
                                    <?php if($item['VendorInvoice']['billing_mins'] - $item['VendorInvoice']['system_mins'] > 0): ?>
                                        <a  onclick="myconfirm('<?php __('sure to do that'); ?>?',this);return false;"
                                            href="<?php echo $this->webroot; ?>pr/pr_invoices/send_vendor_invoice_dispute/<?php echo  base64_encode($item['VendorInvoice']['vendor_invoice_id']); ?>"
                                           title="<?php __('Submit Dispute Note'); ?>" >
                                            <i class="icon-plus-sign"></i></a>
                                    <?php endif; ?>
                                    <a href="javascript:void(0)" title="<?php __('View Dispute Note'); ?>" data-value="<?php echo $item['VendorInvoice']['vendor_invoice_id'];?>" class="show_dispute" >
                                        <i class="icon-list"></i></a>
                                </td>
                            </tr>
                        <?php endforeach;?>
                        </tbody>
                    </table>
                    <div class="row-fluid separator">
                        <div class="pagination pagination-large pagination-right margin-none">
                            <?php echo $this->element('xpage'); ?>
                        </div>
                    </div>

                <?php endif;?>





                <div class="clearfix"></div>

            </div>
        </div>
    </div>
</div>
<div id="MyModal_submitCredit" class="modal hide">
    <input type="hidden" class="dispute_info"  />
    <input type="hidden" class="vendor_info"  />
    <div class="modal-header">
        <button data-dismiss="modal" class="close" type="button">&times;</button>
        <h3><?php __('Submit Credit Note'); ?></h3>
    </div>
    <div class="separator"></div>
        <div class="widget-body">


        </div>
    <div class="modal-footer">
        <input type="button" class="btn btn-primary sub_btn" value="<?php __('Submit'); ?>">
        <a href="javascript:void(0)"  data-dismiss="modal" class="btn btn-default"><?php __('Close'); ?></a>
    </div>

</div>
<script type="text/javascript">



    $(function(){

        $('a.show_detail').click(function(){
            var $this = $(this);
            var vendor_invoice_id = $this.data('value');
            var item = '.show_detail_' + $this.data('value');
            if ($this.closest('tbody').find(item).size() == 0){
                var html = "<tr class='show_detail_"+vendor_invoice_id+"' style='display:none;height: auto'><td colspan='10'></td></tr>";
                $this.closest('tr').after(html);
                $this.closest('tr').next().find('td').load('<?php echo $this->webroot; ?>pr/pr_invoices/ajax_get_vendor_detail',{'vendor_invoice_id':vendor_invoice_id});
            }
//           console.log(item);
            $(item).slideToggle('600');
            $.sleep(400);
            $this.find('i').toggleClass('icon-minus');

        });

        $('a.show_dispute').click(function(){
            var $this = $(this);
            var vendor_invoice_id = $this.data('value');
            var item = '.show_dispute_' + $this.data('value');
            if ($this.closest('tbody').find(item).size() == 0){
                var html = "<tr class='show_dispute_"+vendor_invoice_id+"' style='display:none;height: auto'><td colspan='10'></td></tr>";
                $this.closest('tr').after(html);
                $this.closest('tr').next().find('td').load('<?php echo $this->webroot; ?>pr/pr_invoices/ajax_get_vendor_dispute',{'vendor_invoice_id':vendor_invoice_id});
            }
//           console.log(item);
            $(item).slideToggle('600');
            $.sleep(400);
//            $this.find('i').toggleClass('icon-minus');

        });

        $('a.submit_credit').live('click',function(){
            var $this = $(this);
            var dispute_id = $this.data('value');
            $("#MyModal_submitCredit").find('.dispute_info').val(dispute_id);
            $("#MyModal_submitCredit").find('.vendor_info').val($this.data('vendor'));
            $("#MyModal_submitCredit").find('.widget-body').load('<?php echo $this->webroot; ?>pr/pr_invoices/ajax_get_vendor_credit',{'dispute_id':dispute_id});
        });

        $("#MyModal_submitCredit").find('.sub_btn').click(function(){
            var $this_sub = $(this);
            var $this = $("#MyModal_submitCredit");
            var dispute_item = '.show_dispute_' + $this.find(".vendor_info").val();
            var dispute_info = $this.find(".dispute_info").val();
            var credit_value = $this.find('.credit_value').val();
            var credit_note = $this.find('.credit_note').val();
            $.ajax({
                type: "POST",
                url: "<?php echo $this->webroot; ?>pr/pr_invoices/ajax_save_vendor_credit",
                dataType: 'json',
                data: {'dispute_info': dispute_info,'credit_value': credit_value,'credit_note': credit_note},
                beforeSend: function(XMLHttpRequest) {
                    if($this.find('.credit_value').validationEngine('validate')){
                        return false;
                    }
                    $this_sub.before('<i class="icon-spinner icon-spin icon-large" id="loading_i"></i>');//显示等待消息
                    $this_sub.val('Sending...').attr('disabled',true);
                },
                success: function(data){
                    if(data.status == 1){
                        jGrowl_to_notyfy(data.msg, {theme: 'jmsg-success'});
                        $(dispute_item).find('.credit_value').html(credit_value);
                        $(dispute_item).find('.credit_note').html(credit_note);
                    }
                    else{
                        jGrowl_to_notyfy(data.msg, {theme: 'jmsg-error'});
                    }
                    $this_sub.next().click();
                    $this_sub.val('Submit').attr('disabled',false);
                    $("#loading_i").remove();
                }
            });

        });




    })
</script>
