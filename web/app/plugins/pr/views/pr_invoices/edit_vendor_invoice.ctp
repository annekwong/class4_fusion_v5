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
    <h4 class="heading"><?php echo __('Alert'); ?></h4>

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
        <div class="widget-head">

        </div>
        <div class="widget-body">
            <form action="" method="post" class="vendor_invoice">
                <?php echo $form->input('vendor_invoice_id',array('type' => 'hidden','value' =>$this->data['VendorInvoice']['vendor_invoice_id'] )); ?>
                <div class="widget" >
                    <div class="widget-head"><h4 class="heading"><?php __('Vendor Invoice Info'); ?></h4></div>
                    <div class="widget-body">
                        <table class="form table dynamicTable tableTools table-bordered  table-white">
                            <colgroup>
                                <col width="20%">
                                <col width="25%">
                                <col width="20%">
                                <col width="25%">
                            </colgroup>
                            <tbody>
                            <tr>
                                <td class="align_right padding-r20"><?php __('Carrier Name')?>:</td>
                                <td><?php echo @$clients[$this->data['VendorInvoice']['client_id']]; ?></td>
                                <td class="align_right padding-r20"><?php __('Status')?>:</td>
                                <td><?php echo $status[ $this->data['VendorInvoice']['status']] ?></td>
                            </tr>
                            <tr>
                                <td class="align_right padding-r20"><?php __('Calculated On')?>:</td>
                                <td><?php echo $this->data['VendorInvoice']['invoice_time'] ?></td>
                                <td class="align_right padding-r20"><?php __('Billing Duration')?>:</td>
                                <td>
                                    <small>
                                        <?php echo $this->data['VendorInvoice']['billing_start'] ?><br>
                                        <?php echo $this->data['VendorInvoice']['billing_end'] ?>
                                    </small>
                                </td>
                            </tr>
                            <tr>
                                <td class="align_right padding-r20"><?php __('System Minute')?>:</td>
                                <td><?php echo $this->data['VendorInvoice']['system_mins'] ?>&nbsp;min(s)</td>
                                <td class="align_right padding-r20"><?php __('System Total')?>:</td>
                                <td><?php echo $this->data['VendorInvoice']['system_total'] ?></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <table class="cols table dynamicTable tableTools table-bordered  table-white">
                    <colgroup>
                        <col width="40%">
                        <col width="60%">
                    </colgroup>
                    <tr>
                        <td class="align_right padding-r20"><?php echo __('Import File', true); ?>:</td>
                        <td>
                            <input type="file" id="myfile" name="file" />
                            <span id="analysis" style="display:block;">
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td class="align_right padding-r20"><?php __('Status')?>:</td>
                        <td>
                            <?php echo $form->input('status',array('type' => 'select','options' => $status,'selected' => $this->data['VendorInvoice']['status'],
                                'div' => false,'label' => false)); ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="align_right padding-r20"><?php __('Billed Minute')?>:</td>
                        <td>
                            <?php echo $form->input('billing_mins',array('div' => false,'label' => false,
                                'class' => 'validate[custom[number]]','value' => $this->data['VendorInvoice']['billing_mins'])); ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="align_right padding-r20"><?php __('Billed Total')?>:</td>
                        <td>
                            <?php echo $form->input('billing_total',array('div' => false,'label' => false,
                                'class' => 'validate[custom[number]]','value' => $this->data['VendorInvoice']['billing_total'])); ?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" class="center">
                            <input type="submit" class="btn btn-primary" value="<?php __('Submit'); ?>" />
                        </td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
</div>
<script src="<?php echo $this->webroot; ?>ajaxupload/swfupload.js"></script>
<script src="<?php echo $this->webroot; ?>ajaxupload/jquery-asyncUpload-0.1.js"></script>
<script type="text/javascript">

    $(function(){

        $("#myfile").makeAsyncUploader({
            upload_url: '<?php echo $this->webroot ?>uploads/async_upload',
            flash_url: '<?php echo $this->webroot; ?>ajaxupload/swfupload.swf',
            button_image_url: '<?php echo $this->webroot; ?>ajaxupload/blankButton.png',
            post_params: {"PHPSESSID": "<?php echo session_id(); ?>",'upload_type':1},
            file_size_limit: '1024 MB',
            upload_success_handler: function(file, response) {
                var container = $('#content');
                $("#analysis").empty();
                $("input[name$=_filename]", container).val(file.name);
                $("input[name$=_guid]", container).val(response);
                $("span[id$=_completedMessage]", container).html("Uploaded <b>{0}</b> ({1} KB)"
                        .replace("{0}", file.name)
                        .replace("{1}", (file.size / 1024).toFixed(3))
                );
            }
        });



    })
</script>
