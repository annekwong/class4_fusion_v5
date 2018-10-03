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
    <a class="link_back btn btn-default btn-icon btn-inverse glyphicons circle_arrow_left" href="<?php echo $this->webroot; ?>pr/pr_invoices/vendor_invoice">
        <i></i><?php __('Back'); ?></a>
</div>
<div class="clearfix"></div>


<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">

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
                    <?php echo $this->element('common/log_query_datetime',array('datetime' => 1,'label' => __('Sent On',true),)) ?>
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
                            <?php echo __('No Data Found', true); ?>
                        </h2>
                    </div>
                <?php else: ?>
                    <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary default footable-loaded">
                        <thead>
                        <tr>
                            <th><?php __('Vendor Invoice')?></th>
                            <th>
                                <?php echo $appCommon->show_order('create_on', __('Sent On', true)) ?>
                            </th>
                            <th>
                                <?php echo $appCommon->show_order('create_by', __('Sent By', true)) ?>
                            </th>
                            <th>
                                <?php echo $appCommon->show_order('dispute', __('Disputed Amount', true)) ?>
                            </th>
                            <th>
                                <?php echo $appCommon->show_order('credit', __('Credit Amount', true)) ?>
                            </th>
                            <th><?php __('Credit Note')?></th>
                            <th><?php __('action')?></th>
                        </tr>
                        </thead>
                        <tbody class="alert_tbody">
                        <?php foreach($this->data as $item): ?>
                            <tr class="show_dispute_<?php echo $item['VendorInvoiceDispute']['vendor_invoice_id']; ?>">
                                <td>
                                    <a href="javascript:void(0)" title="<ul><li><?php __('Carrier Name');?>:<?php echo @$clients[$item['VendorInvoice']['client_id']];?></li>
                                <li><?php __('Calculated On');?>:<?php echo $item['VendorInvoice']['invoice_time'] ?></li>
                                <li><?php __('Billing Duration')?>:
                                    <small>
                                        <?php echo $item['VendorInvoice']['billing_start'] ?><br>
                                        <?php echo $item['VendorInvoice']['billing_end'] ?>
                                    </small>
                                </li>
                                <li><?php __('Status')?>:<?php echo $status[ $item['VendorInvoice']['status']];?></li>
                            </ul>">
                                        <b>#<?php echo $item['VendorInvoiceDispute']['vendor_invoice_id'] ?></b>
                                    </a>
                                </td>
                                <td><?php echo $item['VendorInvoiceDispute']['create_on'] ?></td>
                                <td><?php echo $item['VendorInvoiceDispute']['create_by'] ?></td>
                                <td><?php echo $item['VendorInvoiceDispute']['dispute'] ?></td>
                                <td class="credit_value"><?php echo $item['VendorInvoiceDispute']['credit'] ?></td>
                                <td class="credit_note"><?php echo $item['VendorInvoiceDispute']['credit_note'] ?></td>
                                <td>
                                    <a href="#MyModal_submitCredit" class="submit_credit"
                                       data-vendor="<?php echo $item['VendorInvoiceDispute']['vendor_invoice_id'] ?>"
                                       data-value="<?php echo $item['VendorInvoiceDispute']['id'] ?>" data-toggle="modal"
                                       title="<?php __('Submit Credit Note'); ?>" >
                                        <i class="icon-plus-sign"></i></a>
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